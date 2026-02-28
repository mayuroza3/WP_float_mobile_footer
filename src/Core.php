<?php
namespace FloatMobileFooter;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Core {
	public function __construct() {
		// Define hooks and initialization
	}

	public function run() {
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies() {
		// Because we're using PSR-4 autoloading via float_mobile_footer.php, 
		// we don't need manual require statements for our plugin classes here.
	}

	private function define_admin_hooks() {
		if ( is_admin() ) {
			$plugin_admin = new Admin();
			add_action( 'admin_init', [ $plugin_admin, 'register_settings' ] );
			add_action( 'admin_menu', [ $plugin_admin, 'add_plugin_admin_menu' ] );
			add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_styles_and_scripts' ] );
			add_filter( 'plugin_action_links_' . FMF_PLUGIN_BASENAME, [ $plugin_admin, 'add_action_links' ] );
		}
	}

	private function define_public_hooks() {
		if ( ! is_admin() ) {
			$plugin_public = new Frontend();
			add_action( 'wp_enqueue_scripts', [ $plugin_public, 'enqueue_styles_and_scripts' ] );
			add_action( 'wp_footer', [ $plugin_public, 'display_footer' ] );
		}
	}
}
