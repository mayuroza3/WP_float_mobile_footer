<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
$option_name = 'my_option_name';
 
delete_option($option_name);
 