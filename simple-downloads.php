<?php
/*
Plugin Name: Simple Downloads
Plugin URI: http://wordpress.org/extend/plugins/simple-downloads/
Description: Simple Downloads helps you manage your downloads (restrict to users/roles).
Author: Stas Sușcov
Version: 0.1
Author URI: http://stas.nerd.ro/
*/

define( 'SDW_ROOT', dirname( __FILE__ ) );
define( 'SDW_WEB_ROOT', WP_PLUGIN_URL . '/' . basename( SDW_ROOT ) );

require_once SDW_ROOT . '/includes/sdw.class.php';
require_once SDW_ROOT . '/includes/plugins/membership.class.php';

/**
 * i18n
 */
function sdw_textdomain() {
    load_plugin_textdomain( 'sdw', false, basename( SDW_ROOT ) . '/languages' );
}
add_action( 'init', 'sdw_textdomain' );

SDW::init();
SDW_Membership::init();

?>
