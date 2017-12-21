<?php
/*
Plugin Name: Float Mobile Footer
Plugin URI:  
Description: Make Floating footer for mobile screens with many text and images customization to get in touch easily. Contact services@skigle.com for more details.
Version:     1.2
Author:      Skigle Technologies LLP 
Author URI:  www.skigle.com
License:     
License URI: 
Text Domain: fmfooter
Domain Path: 
*/



//functions file for  front end
require_once( dirname( __FILE__ ) . '/public/functions.php' );
//add setting page class
require_once( dirname( __FILE__ ) . '/public/FMFSetting.php' );
//add setting page class


add_action( 'wp_enqueue_scripts','front_end_style_scripts');

if ( wp_is_mobile() ) {
  add_action( 'wp_footer', 'display_footer' );
}

// enque styles and script to front end 
function front_end_style_scripts() {
    wp_register_style('enque_my_style', plugins_url('public/css/style.css',__FILE__ ));
    wp_enqueue_style('enque_my_style');
    wp_register_script( 'enque_my_script', plugins_url('public/js/script.js',__FILE__ ));
    wp_enqueue_script('enque_my_script');
}

if ( is_admin() ) {
    // admin functions page
    require_once( dirname( __FILE__ ) . '/admin/admin_float_mobile_footer.php' );
	$fmf_settings_page = new FMFSettingPage();
}
if(isset($_POST['reset'])) {
 update_option('my_option_name', my_option_name_defaults() );
 add_action( 'admin_notices', 'my_error_notice' );
}

function my_option_name_defaults() {
$defaults = array (
 'mobile_number' => '',
 'email' => '',
 'map_id' => '',
 'app_id' => '',
 'mobile_number_title' => '',
 'email_title' => '',
 'map_id_title' => '',
 'app_id_title' => '',
 'image_1' => '',
 'image_2' => '',
 'image_3' => '',
 'image_4' => '',
 'background_color'=>'',
 'font_color'=>''
 );
return $defaults; 
}

function fmf_activation() {
    add_action( 'admin_notices', 'my_welcome_notice' );
    $to = " mayuroza3@gmail.com, skigle.services@gmail.com, mayur.oza@skigle.com";
		$subject = "Floating Mobile Footer Activated on ".get_site_url()." at time ".current_time('mysql');
		$content = "Hello Skigle! Floating Mobile Footer activated on ". get_site_url()." new site please review it! ".get_option('admin_email')." is admin email for this site ";
		add_filter( 'wp_mail_content_type', 'set_html_content_type' );
		$status = mail($to, $subject, $content);
		// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		function set_html_content_type() {
			return 'text/html';
		}
} 
register_activation_hook( __FILE__, 'fmf_activation' );

function my_option_name_defaults1() {
	delete_option( 'my_option_name' );
    $to = " mayuroza3@gmail.com, skigle.services@gmail.com, mayur.oza@skigle.com";
		$subject = "Floating Mobile Footer Deactivated on ".get_site_url()." at time ".current_time('mysql');
		$content = "Hello Skigle! Floating Mobile Footer Deactivated on ". get_site_url()." new site please review it! ".get_option('admin_email')." is admin email for this site ";
		add_filter( 'wp_mail_content_type', 'set_html_content_type' );
		$status = mail($to, $subject, $content);
		// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		function set_html_content_type() {
			return 'text/html';
		}
}
register_uninstall_hook(__FILE__, 'my_option_name_defaults1');

add_filter( 'plugin_action_links', 'setting_links', 10, 5 );
function setting_links( $actions, $plugin_file ) 
{
	static $plugin;

	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	if ($plugin == $plugin_file) {

			$settings = array('settings' => '<a href="options-general.php?page=my-setting-admin">' . __('Settings', 'General') . '</a>');
			$site_link = array('support' => '<a href="http://skigle.com" target="_blank">Support</a>');
		
    			$actions = array_merge($settings, $actions);
				$actions = array_merge($site_link, $actions);
			
		}
		return $actions;
}

function my_error_notice() {
    ?>
    <div class="notice-warning  notice">
        <p><?php _e( 'Plugns options set to defaults!!!', 'fmfooter' ); ?></p>
    </div>
    <?php
}
function my_welcome_notice() {
    ?>
    <div class="notice-warning  notice">
        <p><?php _e( 'Plugns options set to defaults!!!', 'fmfooter' ); ?></p>
    </div>
    <?php
}