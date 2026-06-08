<?php
namespace FloatMobileFooter;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Frontend {
	private $option_name = 'float_mobile_footer_options';
	private $options;

	public function __construct() {
		$this->options = get_option( $this->option_name, [] );
	}

	/**
	 * Get the active settings, checking for page-specific meta box overrides.
	 */
	private function get_active_settings() {
		$settings = $this->options;

		if ( ! is_array( $settings ) ) {
			$settings = [];
		}

		if ( is_singular() ) {
			$post_id = get_queried_object_id();

			// Check if mobile footer is disabled on this specific page/post
			$disabled = get_post_meta( $post_id, '_fmf_disable_footer', true );
			if ( '1' === $disabled ) {
				$settings['enable'] = 0;
				return $settings;
			}

			// Check if page overrides global settings
			$override = get_post_meta( $post_id, '_fmf_override_global', true );
			if ( '1' === $override ) {
				$settings['enable']      = 1;
				$settings['phone']       = get_post_meta( $post_id, '_fmf_override_phone', true );
				$settings['whatsapp']    = get_post_meta( $post_id, '_fmf_override_whatsapp', true );
				$settings['email']       = get_post_meta( $post_id, '_fmf_override_email', true );
				$settings['custom_link'] = get_post_meta( $post_id, '_fmf_override_custom_link', true );
				$settings['custom_link_text'] = get_post_meta( $post_id, '_fmf_override_custom_link_text', true );
			}
		}

		return $settings;
	}

	public function enqueue_styles_and_scripts() {
		$options = $this->get_active_settings();

		// Only load assets if footer is enabled or if it's not the admin preview iframe.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $options['enable'] ) || $options['enable'] !== 1 || isset( $_GET['fmf_preview'] ) ) {
			return;
		}

		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 
			'fmf-public-css', 
			FMF_PLUGIN_URL . 'assets/public/css/fmf-public.css', 
			[], 
			FMF_VERSION 
		);

		// Pass Dynamic Colors to CSS properly.
		$bg_color   = ! empty( $options['bg_color'] ) ? sanitize_hex_color( $options['bg_color'] ) : '#ffffff';
		$text_color = ! empty( $options['text_color'] ) ? sanitize_hex_color( $options['text_color'] ) : '#333333';
		
		$custom_css = "
			:root {
				--fmf-bg-color: {$bg_color};
				--fmf-text-color: {$text_color};
			}
		";
		wp_add_inline_style( 'fmf-public-css', $custom_css );
	}

	public function display_footer() {
		$options = $this->get_active_settings();

		// Only display if footer is enabled and it's not the admin preview iframe.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $options['enable'] ) || $options['enable'] !== 1 || isset( $_GET['fmf_preview'] ) ) {
			return;
		}

		$buttons = [];

		if ( ! empty( $options['phone'] ) ) {
			$phone = preg_replace( '/[^0-9\+]/', '', $options['phone'] );
			$buttons[] = [
				'url'   => esc_url( 'tel:' . $phone ),
				'icon'  => 'dashicons-phone',
				'label' => __( 'Call', 'float-mobile-footer' )
			];
		}

		if ( ! empty( $options['whatsapp'] ) ) {
			$whatsapp = preg_replace( '/[^0-9]/', '', $options['whatsapp'] );
			$buttons[] = [
				'url'   => esc_url( 'https://wa.me/' . $whatsapp ),
				'icon'  => 'dashicons-whatsapp', // Dashicons actually supports whatsapp now via 'dashicons-whatsapp' since WP 5.5
				'label' => __( 'WhatsApp', 'float-mobile-footer' )
			];
		}

		if ( ! empty( $options['email'] ) ) {
			$buttons[] = [
				'url'   => esc_url( 'mailto:' . sanitize_email( $options['email'] ) ),
				'icon'  => 'dashicons-email-alt',
				'label' => __( 'Email', 'float-mobile-footer' )
			];
		}

		if ( ! empty( $options['custom_link'] ) ) {
			$custom_text = ! empty( $options['custom_link_text'] ) ? sanitize_text_field( $options['custom_link_text'] ) : __( 'Link', 'float-mobile-footer' );
			$buttons[] = [
				'url'   => esc_url( $options['custom_link'] ),
				'icon'  => 'dashicons-admin-links',
				'label' => $custom_text
			];
		}

		// Don't output if no buttons configured
		if ( empty( $buttons ) ) {
			return;
		}

		// Calculate total buttons to distribute width evenly
		$count = count( $buttons );
		$item_class = 'fmf-item-count-' . absint( $count );

		// Output securely escaped HTML
		?>
		<div id="fmf-floating-footer" class="fmf-floating-footer-wrap <?php echo esc_attr( $item_class ); ?>">
			<div class="fmf-footer-inner">
				<?php foreach ( $buttons as $button ) : ?>
					<a href="<?php echo esc_url( $button['url'] ); ?>" class="fmf-footer-btn" target="_blank" rel="noopener noreferrer">
						<span class="dashicons <?php echo esc_attr( $button['icon'] ); ?>"></span>
						<span class="fmf-btn-text"><?php echo esc_html( $button['label'] ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
