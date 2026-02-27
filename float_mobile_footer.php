<?php
/**
 * Plugin Name: Float Mobile Footer
 * Plugin URI: https://wordpress.org/plugins/float-mobile-footer/
 * Description: Modern, lightweight, and customizable floating mobile footer to boost engagement and conversions on mobile screens.
 * Version: 2.0.0
 * Author: Mayur Oza
 * Author URI: https://www.mayuroza.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: float-mobile-footer
 * Domain Path: /languages
 * Requires at least: 5.6
 * Requires PHP: 7.4
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
function run_float_mobile_footer() {
	$plugin = new \FloatMobileFooter\Core();
	$plugin->run();
}

run_float_mobile_footer();