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

	public function enqueue_styles_and_scripts() {
		// Only load assets if footer is enabled or if it's not the admin preview iframe.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $this->options['enable'] ) || $this->options['enable'] !== 1 || isset( $_GET['fmf_preview'] ) ) {
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
		$bg_color   = ! empty( $this->options['bg_color'] ) ? sanitize_hex_color( $this->options['bg_color'] ) : '#ffffff';
		$text_color = ! empty( $this->options['text_color'] ) ? sanitize_hex_color( $this->options['text_color'] ) : '#333333';
		
		$custom_css = "
			:root {
				--fmf-bg-color: {$bg_color};
				--fmf-text-color: {$text_color};
			}
		";
		wp_add_inline_style( 'fmf-public-css', $custom_css );
	}

	public function display_footer() {
		// Only display if footer is enabled and it's not the admin preview iframe.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $this->options['enable'] ) || $this->options['enable'] !== 1 || isset( $_GET['fmf_preview'] ) ) {
			return;
		}

		$buttons = [];

		if ( ! empty( $this->options['phone'] ) ) {
			$phone = preg_replace( '/[^0-9\+]/', '', $this->options['phone'] );
			$buttons[] = [
				'url'   => esc_url( 'tel:' . $phone ),
				'icon'  => 'dashicons-phone',
				'label' => __( 'Call', 'float-mobile-footer' )
			];
		}

		if ( ! empty( $this->options['whatsapp'] ) ) {
			$whatsapp = preg_replace( '/[^0-9]/', '', $this->options['whatsapp'] );
			$buttons[] = [
				'url'   => esc_url( 'https://wa.me/' . $whatsapp ),
				'icon'  => 'dashicons-whatsapp', // Dashicons actually supports whatsapp now via 'dashicons-whatsapp' since WP 5.5
				'label' => __( 'WhatsApp', 'float-mobile-footer' )
			];
		}

		if ( ! empty( $this->options['email'] ) ) {
			$buttons[] = [
				'url'   => esc_url( 'mailto:' . sanitize_email( $this->options['email'] ) ),
				'icon'  => 'dashicons-email-alt',
				'label' => __( 'Email', 'float-mobile-footer' )
			];
		}

		if ( ! empty( $this->options['custom_link'] ) ) {
			$custom_text = ! empty( $this->options['custom_link_text'] ) ? sanitize_text_field( $this->options['custom_link_text'] ) : __( 'Link', 'float-mobile-footer' );
			$buttons[] = [
				'url'   => esc_url( $this->options['custom_link'] ),
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
