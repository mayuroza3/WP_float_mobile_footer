<?php
/**
 * Plugin Name: Float Mobile Footer
 * Description: Adds a customizable mobile floating footer bar with action buttons.
 * Version: 2.0.0
 * Requires at least: 5.6
 * Requires PHP: 7.4
 * Author: Mayur Oza
 * Author URI: https://www.mayuroza.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: float-mobile-footer
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Plugin Constants
define( 'FMF_VERSION', '2.0.0' );
define( 'FMF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FMF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FMF_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// PSR-4 Autoloader for the plugin
spl_autoload_register( function ( $class ) {
	$prefix = 'FloatMobileFooter\\';
	$base_dir = FMF_PLUGIN_DIR . 'src/';

	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	$relative_class = substr( $class, $len );
	$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}
} );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
call_user_func( function() {
	$plugin = new \FloatMobileFooter\Core();
	$plugin->run();
} );