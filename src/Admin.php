<?php
namespace FloatMobileFooter;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin {
	private $option_name = 'float_mobile_footer_options';

	public function register_settings() {
		register_setting(
			'fmf_settings_group',
			$this->option_name,
			[
				'sanitize_callback' => [ $this, 'sanitize_settings' ],
				'default'           => $this->get_default_settings()
			]
		);

		add_settings_section(
			'fmf_general_section',
			__( 'General Settings', 'float-mobile-footer' ),
			[ $this, 'general_section_callback' ],
			'fmf-settings'
		);

		add_settings_field( 'fmf_enable', __( 'Enable Floating Footer', 'float-mobile-footer' ), [ $this, 'checkbox_field_callback' ], 'fmf-settings', 'fmf_general_section', [ 'id' => 'enable' ] );
		add_settings_field( 'fmf_bg_color', __( 'Background Color', 'float-mobile-footer' ), [ $this, 'color_field_callback' ], 'fmf-settings', 'fmf_general_section', [ 'id' => 'bg_color' ] );
		add_settings_field( 'fmf_text_color', __( 'Text/Icon Color', 'float-mobile-footer' ), [ $this, 'color_field_callback' ], 'fmf-settings', 'fmf_general_section', [ 'id' => 'text_color' ] );

		add_settings_section(
			'fmf_buttons_section',
			__( 'Buttons Configuration', 'float-mobile-footer' ),
			[ $this, 'buttons_section_callback' ],
			'fmf-settings'
		);

		add_settings_field( 'fmf_phone', __( 'Phone Number', 'float-mobile-footer' ), [ $this, 'text_field_callback' ], 'fmf-settings', 'fmf_buttons_section', [ 'id' => 'phone', 'placeholder' => '+1234567890', 'desc' => __( 'Leave empty to disable', 'float-mobile-footer' ) ] );
		add_settings_field( 'fmf_whatsapp', __( 'WhatsApp Number', 'float-mobile-footer' ), [ $this, 'text_field_callback' ], 'fmf-settings', 'fmf_buttons_section', [ 'id' => 'whatsapp', 'placeholder' => '+1234567890', 'desc' => __( 'Leave empty to disable. Make sure to include country code without + or zeros.', 'float-mobile-footer' ) ] );
		add_settings_field( 'fmf_email', __( 'Email Address', 'float-mobile-footer' ), [ $this, 'text_field_callback' ], 'fmf-settings', 'fmf_buttons_section', [ 'id' => 'email', 'placeholder' => 'contact@example.com', 'desc' => __( 'Leave empty to disable', 'float-mobile-footer' ) ] );
		add_settings_field( 'fmf_custom_link', __( 'Custom Link URL', 'float-mobile-footer' ), [ $this, 'url_field_callback' ], 'fmf-settings', 'fmf_buttons_section', [ 'id' => 'custom_link', 'placeholder' => 'https://example.com', 'desc' => __( 'Leave empty to disable', 'float-mobile-footer' ) ] );
		add_settings_field( 'fmf_custom_link_text', __( 'Custom Link Text', 'float-mobile-footer' ), [ $this, 'text_field_callback' ], 'fmf-settings', 'fmf_buttons_section', [ 'id' => 'custom_link_text', 'placeholder' => 'Visit Us', 'desc' => '' ] );
	}

	public function sanitize_settings( $input ) {
		$sanitized = [];
		if ( isset( $input['enable'] ) ) {
			$sanitized['enable'] = absint( $input['enable'] ) === 1 ? 1 : 0;
		} else {
			$sanitized['enable'] = 0;
		}

		$sanitized['bg_color'] = isset( $input['bg_color'] ) ? sanitize_hex_color( $input['bg_color'] ) : '#ffffff';
		$sanitized['text_color'] = isset( $input['text_color'] ) ? sanitize_hex_color( $input['text_color'] ) : '#333333';
		$sanitized['phone'] = isset( $input['phone'] ) ? sanitize_text_field( $input['phone'] ) : '';
		$sanitized['whatsapp'] = isset( $input['whatsapp'] ) ? sanitize_text_field( $input['whatsapp'] ) : '';
		$sanitized['email'] = isset( $input['email'] ) ? sanitize_email( $input['email'] ) : '';
		$sanitized['custom_link'] = isset( $input['custom_link'] ) ? esc_url_raw( $input['custom_link'] ) : '';
		$sanitized['custom_link_text'] = isset( $input['custom_link_text'] ) ? sanitize_text_field( $input['custom_link_text'] ) : '';

		return $sanitized;
	}

	private function get_default_settings() {
		return [
			'enable'           => 1,
			'bg_color'         => '#ffffff',
			'text_color'       => '#333333',
			'phone'            => '',
			'whatsapp'         => '',
			'email'            => '',
			'custom_link'      => '',
			'custom_link_text' => 'Learn More',
		];
	}

	public function add_plugin_admin_menu() {
		add_options_page(
			__( 'Float Mobile Footer Settings', 'float-mobile-footer' ),
			__( 'Mobile Footer', 'float-mobile-footer' ),
			'manage_options',
			'fmf-settings',
			[ $this, 'render_admin_page' ]
		);
	}

	public function add_action_links( $links ) {
		$settings_link = '<a href="options-general.php?page=fmf-settings">' . __( 'Settings', 'float-mobile-footer' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	public function enqueue_styles_and_scripts( $hook ) {
		if ( 'settings_page_fmf-settings' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'fmf-admin-js', FMF_PLUGIN_URL . 'assets/admin/js/fmf-admin.js', [ 'wp-color-picker', 'jquery' ], FMF_VERSION, true );
		wp_enqueue_style( 'fmf-admin-css', FMF_PLUGIN_URL . 'assets/admin/css/fmf-admin.css', [], FMF_VERSION );
	}

	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Use output buffering for the admin view to keep things clean.
		?>
		<div class="wrap fmf-admin-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<p><?php esc_html_e( 'Configure your floating mobile footer. Make sure to only fill in the buttons you want to display.', 'float-mobile-footer' ); ?></p>
			
			<form action="options.php" method="post" id="fmf-settings-form">
				<div class="fmf-settings-grid">
					<div class="fmf-settings-panel">
						<?php
						settings_fields( 'fmf_settings_group' );
						do_settings_sections( 'fmf-settings' );
						submit_button();
						?>
					</div>
					<div class="fmf-preview-panel">
						<div class="fmf-mobile-preview-container">
							<h3><?php esc_html_e( 'Live Mobile Preview', 'float-mobile-footer' ); ?></h3>
							<div class="fmf-mobile-phone-frame">
								<div class="fmf-mobile-screen">
									<div class="fmf-preview-content">
										<p><?php esc_html_e( 'Your website content here...', 'float-mobile-footer' ); ?></p>
									</div>
									<div id="fmf-preview-footer" class="fmf-preview-footer">
										<!-- Preview items inserted via JS -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php
	}

	public function general_section_callback() {
		echo '<p>' . esc_html__( 'Configure the look and feel of your footer.', 'float-mobile-footer' ) . '</p>';
	}

	public function buttons_section_callback() {
		echo '<p>' . esc_html__( 'Configure the Contact and Call-To-Action buttons.', 'float-mobile-footer' ) . '</p>';
	}

	public function checkbox_field_callback( $args ) {
		$options = get_option( $this->option_name, $this->get_default_settings() );
		$id      = $args['id'];
		$checked = isset( $options[ $id ] ) ? (bool) $options[ $id ] : false;
		?>
		<label class="fmf-switch">
			<input type="checkbox" id="fmf_<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $this->option_name ); ?>[<?php echo esc_attr( $id ); ?>]" value="1" <?php checked( $checked, true ); ?> />
			<span class="fmf-slider"></span>
		</label>
		<?php
	}

	public function color_field_callback( $args ) {
		$options = get_option( $this->option_name, $this->get_default_settings() );
		$id      = $args['id'];
		$value   = isset( $options[ $id ] ) ? $options[ $id ] : '';
		?>
		<input type="text" class="fmf-color-picker" id="fmf_<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $this->option_name ); ?>[<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
		<?php
	}

	public function text_field_callback( $args ) {
		$options     = get_option( $this->option_name, $this->get_default_settings() );
		$id          = $args['id'];
		$value       = isset( $options[ $id ] ) ? $options[ $id ] : '';
		$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
		$desc        = isset( $args['desc'] ) ? $args['desc'] : '';
		?>
		<input type="text" id="fmf_<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $this->option_name ); ?>[<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="regular-text" />
		<?php if ( $desc ) : ?>
			<p class="description"><?php echo esc_html( $desc ); ?></p>
		<?php endif;
	}

	public function url_field_callback( $args ) {
		$options     = get_option( $this->option_name, $this->get_default_settings() );
		$id          = $args['id'];
		$value       = isset( $options[ $id ] ) ? $options[ $id ] : '';
		$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
		$desc        = isset( $args['desc'] ) ? $args['desc'] : '';
		?>
		<input type="url" id="fmf_<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $this->option_name ); ?>[<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_url( $value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="regular-text" />
		<?php if ( $desc ) : ?>
			<p class="description"><?php echo esc_html( $desc ); ?></p>
		<?php endif;
	}
}
