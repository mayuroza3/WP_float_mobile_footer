<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin user capability check
 * - Determine if we should delete the options based on action 
 *
 * @link       https://www.mayuroza.com
 * @since      2.0.0
 *
 * @package    FloatMobileFooter
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete plugin options from the database.
 */
delete_option( 'float_mobile_footer_options' );

// Clean up old options if they exist
delete_option( 'my_option_name' );