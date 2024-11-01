<?php
/*
Plugin Name: Sky Systemz
Plugin URI: https://SkySystemz.com/
description:  Sky systemz is a payment gateway and using with creadit card.
Version: 1.03
Author: Sky Systemz
Author URI: https://skysystemz.com
License:      GPL2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Tags: Business, Services, Large Businesses, payment gateway
Tested up to: 5.9
Stable tag: 1.1
Requires at least: 5.0
*/

define( 'SKYSYSTEMZ_VERSION', '1.3' );
define( 'SKYSYSTEMZ__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SKYSYSTEMZ__PLUGIN_DIR_URL', plugin_dir_url(__FILE__) );

define( 'SKYSYSTEMZ_API_URL', 'https://api.skysystemz.com' );
define('DATACAP_JS_APIURL', 'https://token.dcap.com/v1/client');

// define( 'SKYSYSTEMZ_API_URL', 'https://livestaging-api.skysystemz.com' );
// define('DATACAP_JS_APIURL', 'https://token-cert.dcap.com/v1/client');

register_activation_hook( __FILE__, array( 'SkySystemz', 'SS_plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'SkySystemz', 'SS_plugin_deactivation' ) );

require_once( SKYSYSTEMZ__PLUGIN_DIR . 'class.SkySystemz.php' );
require_once( SKYSYSTEMZ__PLUGIN_DIR . 'SkySystemz-woocommerceCheckout.php' );
//add_action( 'init', array( 'Pinesucceed_WoocommerceCheckout', 'init' ) );

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once( SKYSYSTEMZ__PLUGIN_DIR . 'class.SkySystemz-admin.php' );
	add_action( 'init', array( 'SkySystemz_Admin', 'init' ) );
}

add_action("wp_footer", "SkySyaytemz_SS_remove_content_from_checkout");
function SkySyaytemz_SS_remove_content_from_checkout() {
	if ( is_checkout() ) {
		?>
		<script>
			jQuery(document).bind("contextmenu",function(e) {
			 e.preventDefault();
			});
			jQuery(document).keydown(function(e){
			    if(e.which === 123){
			       return false;
			    }
			});
		</script>
		<?php
	}
}

function SysSystemz_SS_add_theme_scripts() {
  wp_enqueue_script( 'custom.jss', SKYSYSTEMZ__PLUGIN_DIR_URL.'assets/js/Custom.js', array ( 'jquery' ), 1.1, true);
}
add_action( 'wp_enqueue_scripts', 'SysSystemz_SS_add_theme_scripts' );
?>
