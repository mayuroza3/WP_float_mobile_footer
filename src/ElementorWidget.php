<?php
namespace FloatMobileFooter;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor Float Mobile Footer Widget.
 */
class ElementorWidget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'float_mobile_footer';
	}

	public function get_title() {
		return esc_html__( 'Float Mobile Footer', 'float-mobile-footer' );
	}

	public function get_icon() {
		return 'eicon-footer';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Footer Settings', 'float-mobile-footer' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'phone',
			[
				'label'       => esc_html__( 'Phone Number', 'float-mobile-footer' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '+1234567890',
			]
		);

		$this->add_control(
			'whatsapp',
			[
				'label'       => esc_html__( 'WhatsApp Number', 'float-mobile-footer' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '+1234567890',
			]
		);

		$this->add_control(
			'email',
			[
				'label'       => esc_html__( 'Email Address', 'float-mobile-footer' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'contact@example.com',
			]
		);

		$this->add_control(
			'custom_link',
			[
				'label'       => esc_html__( 'Custom Link URL', 'float-mobile-footer' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://example.com',
			]
		);

		$this->add_control(
			'custom_link_text',
			[
				'label'       => esc_html__( 'Custom Link Text', 'float-mobile-footer' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'Visit Us',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Styling', 'float-mobile-footer' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'float-mobile-footer' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text/Icon Color', 'float-mobile-footer' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Load global options for fallback if needed
		$global_options = get_option( 'float_mobile_footer_options', [] );

		// Check if any local detail is configured in this widget
		$has_local_details = (
			! empty( $settings['phone'] ) ||
			! empty( $settings['whatsapp'] ) ||
			! empty( $settings['email'] ) ||
			! empty( $settings['custom_link']['url'] )
		);

		if ( $has_local_details ) {
			$phone            = ! empty( $settings['phone'] ) ? $settings['phone'] : '';
			$whatsapp         = ! empty( $settings['whatsapp'] ) ? $settings['whatsapp'] : '';
			$email            = ! empty( $settings['email'] ) ? $settings['email'] : '';
			$custom_link      = ! empty( $settings['custom_link']['url'] ) ? $settings['custom_link']['url'] : '';
			$custom_link_text = ! empty( $settings['custom_link_text'] ) ? $settings['custom_link_text'] : 'Link';
		} else {
			$phone            = ! empty( $global_options['phone'] ) ? $global_options['phone'] : '';
			$whatsapp         = ! empty( $global_options['whatsapp'] ) ? $global_options['whatsapp'] : '';
			$email            = ! empty( $global_options['email'] ) ? $global_options['email'] : '';
			$custom_link      = ! empty( $global_options['custom_link'] ) ? $global_options['custom_link'] : '';
			$custom_link_text = ! empty( $global_options['custom_link_text'] ) ? $global_options['custom_link_text'] : 'Link';
		}

		// Styling colors (always fallback to global if local is empty)
		$bg_color   = ! empty( $settings['bg_color'] ) ? $settings['bg_color'] : ( ! empty( $global_options['bg_color'] ) ? $global_options['bg_color'] : '#ffffff' );
		$text_color = ! empty( $settings['text_color'] ) ? $settings['text_color'] : ( ! empty( $global_options['text_color'] ) ? $global_options['text_color'] : '#333333' );

		$buttons = [];

		if ( ! empty( $phone ) ) {
			$phone_clean = preg_replace( '/[^0-9\+]/', '', $phone );
			$buttons[] = [
				'url'   => esc_url( 'tel:' . $phone_clean ),
				'icon'  => 'dashicons-phone',
				'label' => __( 'Call', 'float-mobile-footer' )
			];
		}

		if ( ! empty( $whatsapp ) ) {
			$whatsapp_clean = preg_replace( '/[^0-9]/', '', $whatsapp );
			$buttons[] = [
				'url'   => esc_url( 'https://wa.me/' . $whatsapp_clean ),
				'icon'  => 'dashicons-whatsapp',
				'label' => __( 'WhatsApp', 'float-mobile-footer' )
			];
		}

		if ( ! empty( $email ) ) {
			$buttons[] = [
				'url'   => esc_url( 'mailto:' . sanitize_email( $email ) ),
				'icon'  => 'dashicons-email-alt',
				'label' => __( 'Email', 'float-mobile-footer' )
			];
		}

		if ( ! empty( $custom_link ) ) {
			$buttons[] = [
				'url'   => esc_url( $custom_link ),
				'icon'  => 'dashicons-admin-links',
				'label' => sanitize_text_field( $custom_link_text )
			];
		}

		if ( empty( $buttons ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div style="padding: 10px; background: #f9f9f9; border: 1px dashed #ccc; text-align: center;">' . esc_html__( 'Float Mobile Footer: No buttons configured.', 'float-mobile-footer' ) . '</div>';
			}
			return;
		}

		$count = count( $buttons );
		$item_class = 'fmf-item-count-' . absint( $count );

		// Inline style for this specific widget output
		$widget_id = $this->get_id();
		?>
		<style>
			#fmf-widget-<?php echo esc_attr( $widget_id ); ?> {
				--fmf-bg-color: <?php echo esc_attr( $bg_color ); ?>;
				--fmf-text-color: <?php echo esc_attr( $text_color ); ?>;
				display: none;
			}
			@media screen and (max-width: 782px) {
				#fmf-widget-<?php echo esc_attr( $widget_id ); ?> {
					display: block;
				}
			}
		</style>
		<div id="fmf-widget-<?php echo esc_attr( $widget_id ); ?>" class="fmf-floating-footer-wrap <?php echo esc_attr( $item_class ); ?>" style="position: static; box-shadow: none; width: 100%; max-width: 100%;">
			<div class="fmf-footer-inner" style="box-shadow: none; border: 1px solid rgba(0,0,0,0.1); border-radius: 4px;">
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
