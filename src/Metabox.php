<?php
namespace FloatMobileFooter;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Metabox class for page/post override options.
 */
class Metabox {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'add_override_metabox' ] );
		add_action( 'save_post', [ $this, 'save_override_metabox' ] );
	}

	/**
	 * Adds the meta box.
	 */
	public function add_override_metabox() {
		$screens = [ 'post', 'page' ];
		foreach ( $screens as $screen ) {
			add_meta_box(
				'fmf_override_options',
				__( 'Float Mobile Footer Overrides', 'float-mobile-footer' ),
				[ $this, 'render_metabox' ],
				$screen,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Renders the meta box HTML.
	 */
	public function render_metabox( $post ) {
		// Add a nonce field so we can verify it when saving.
		wp_nonce_field( 'fmf_save_override_data', 'fmf_override_nonce' );

		// Retrieve existing values from the database.
		$override    = get_post_meta( $post->ID, '_fmf_override_global', true );
		$disabled    = get_post_meta( $post->ID, '_fmf_disable_footer', true );
		$phone       = get_post_meta( $post->ID, '_fmf_override_phone', true );
		$whatsapp    = get_post_meta( $post->ID, '_fmf_override_whatsapp', true );
		$email       = get_post_meta( $post->ID, '_fmf_override_email', true );
		$custom_link = get_post_meta( $post->ID, '_fmf_override_custom_link', true );
		$link_text   = get_post_meta( $post->ID, '_fmf_override_custom_link_text', true );
		?>
		<style>
			.fmf-meta-row { margin-bottom: 15px; }
			.fmf-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
			.fmf-meta-row input[type="text"], .fmf-meta-row input[type="url"], .fmf-meta-row input[type="email"] { width: 100%; max-width: 400px; }
			.fmf-meta-row-checkbox { margin-bottom: 15px; }
			.fmf-meta-row-checkbox label { font-weight: bold; }
			.fmf-meta-field-group { margin-left: 20px; border-left: 3px solid #0073aa; padding-left: 15px; margin-top: 10px; }
		</style>

		<div class="fmf-meta-row-checkbox">
			<label>
				<input type="checkbox" name="fmf_disable_footer" value="1" <?php checked( $disabled, '1' ); ?> />
				<?php esc_html_e( 'Disable Mobile Footer on this page/post', 'float-mobile-footer' ); ?>
			</label>
		</div>

		<div class="fmf-meta-row-checkbox">
			<label>
				<input type="checkbox" id="fmf_override_global" name="fmf_override_global" value="1" <?php checked( $override, '1' ); ?> onchange="document.getElementById('fmf-fields-override').style.display = this.checked ? 'block' : 'none';" />
				<?php esc_html_e( 'Override Global settings for this page/post', 'float-mobile-footer' ); ?>
			</label>
		</div>

		<div class="fmf-meta-field-group" id="fmf-fields-override" style="display: <?php echo ( '1' === $override ) ? 'block' : 'none'; ?>;">
			<div class="fmf-meta-row">
				<label for="fmf_override_phone"><?php esc_html_e( 'Phone Number', 'float-mobile-footer' ); ?></label>
				<input type="text" id="fmf_override_phone" name="fmf_override_phone" value="<?php echo esc_attr( $phone ); ?>" placeholder="+1234567890" />
			</div>

			<div class="fmf-meta-row">
				<label for="fmf_override_whatsapp"><?php esc_html_e( 'WhatsApp Number', 'float-mobile-footer' ); ?></label>
				<input type="text" id="fmf_override_whatsapp" name="fmf_override_whatsapp" value="<?php echo esc_attr( $whatsapp ); ?>" placeholder="+1234567890" />
			</div>

			<div class="fmf-meta-row">
				<label for="fmf_override_email"><?php esc_html_e( 'Email Address', 'float-mobile-footer' ); ?></label>
				<input type="email" id="fmf_override_email" name="fmf_override_email" value="<?php echo esc_attr( $email ); ?>" placeholder="contact@example.com" />
			</div>

			<div class="fmf-meta-row">
				<label for="fmf_override_custom_link"><?php esc_html_e( 'Custom Link URL', 'float-mobile-footer' ); ?></label>
				<input type="url" id="fmf_override_custom_link" name="fmf_override_custom_link" value="<?php echo esc_url( $custom_link ); ?>" placeholder="https://example.com" />
			</div>

			<div class="fmf-meta-row">
				<label for="fmf_override_custom_link_text"><?php esc_html_e( 'Custom Link Text', 'float-mobile-footer' ); ?></label>
				<input type="text" id="fmf_override_custom_link_text" name="fmf_override_custom_link_text" value="<?php echo esc_attr( $link_text ); ?>" placeholder="Visit Us" />
			</div>
		</div>
		<?php
	}

	/**
	 * Saves the meta box data.
	 */
	public function save_override_metabox( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['fmf_override_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['fmf_override_nonce'], 'fmf_save_override_data' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		// Save the disabled flag
		$disabled = isset( $_POST['fmf_disable_footer'] ) ? '1' : '0';
		update_post_meta( $post_id, '_fmf_disable_footer', $disabled );

		// Save the override flag
		$override = isset( $_POST['fmf_override_global'] ) ? '1' : '0';
		update_post_meta( $post_id, '_fmf_override_global', $override );

		// Save the override fields
		if ( isset( $_POST['fmf_override_phone'] ) ) {
			update_post_meta( $post_id, '_fmf_override_phone', sanitize_text_field( $_POST['fmf_override_phone'] ) );
		}
		if ( isset( $_POST['fmf_override_whatsapp'] ) ) {
			update_post_meta( $post_id, '_fmf_override_whatsapp', sanitize_text_field( $_POST['fmf_override_whatsapp'] ) );
		}
		if ( isset( $_POST['fmf_override_email'] ) ) {
			update_post_meta( $post_id, '_fmf_override_email', sanitize_email( $_POST['fmf_override_email'] ) );
		}
		if ( isset( $_POST['fmf_override_custom_link'] ) ) {
			update_post_meta( $post_id, '_fmf_override_custom_link', esc_url_raw( $_POST['fmf_override_custom_link'] ) );
		}
		if ( isset( $_POST['fmf_override_custom_link_text'] ) ) {
			update_post_meta( $post_id, '_fmf_override_custom_link_text', sanitize_text_field( $_POST['fmf_override_custom_link_text'] ) );
		}
	}
}
