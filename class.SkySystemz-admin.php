<?php
class SkySystemz_Admin {
	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	public static function init_hooks() {
		self::$initiated = true;
		add_action( 'admin_menu', array( 'SkySystemz_Admin', 'SS_admin_menu' ), 5 );
		add_action( 'admin_enqueue_scripts', array( 'SkySystemz_Admin', 'load_resources' ) );
		add_action("wp_ajax_save_keys", array('SkySystemz_Admin', "save_keys"));
	}

	public static function SS_admin_menu() {
		add_menu_page('SkySystemz Settings', 'SkySystemz Settings', 'manage_options', 'SkySystemz-key-config', array( 'SkySystemz_Admin', 'SS_display_page' ) );
	}

	public static function SS_display_page() {
		require_once( SKYSYSTEMZ__PLUGIN_DIR . 'includes/start.php' );
	}

	public static function load_resources() {
		wp_register_style( 'Style.css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), SKYSYSTEMZ_VERSION );
		wp_enqueue_style( 'Style.css');

		wp_register_style( 'toastr.css', plugin_dir_url( __FILE__ ) . 'assets/css/toastr.css', array(), SKYSYSTEMZ_VERSION );
		wp_enqueue_style( 'toastr.css');

		wp_register_script( 'Custom.js', plugin_dir_url( __FILE__ ) . 'assets/js/Custom.js', array('jquery'), SKYSYSTEMZ_VERSION );
		wp_enqueue_script( 'Custom.js' );

		wp_register_script( 'toastr.js', plugin_dir_url( __FILE__ ) . 'assets/js/toastr.js', array('jquery'), SKYSYSTEMZ_VERSION );
		wp_enqueue_script( 'toastr.js' );
	}

	public static function save_keys()
	{
		global $wpdb;
		$table_keys_name = $wpdb->prefix . 'skysystemz_keys';
		$json = array();
		$merchant_key = sanitize_text_field($_POST['merchant_key']);
		$api_key = sanitize_text_field($_POST['api_key']);
		$nonce = sanitize_text_field($_POST['nonce']);
		if(!empty(sanitize_text_field($_POST['nonce']))) {
			$json['status'] = "security_breach";
		}
		if(!empty($merchant_key) && !empty($api_key)) {
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_keys_name'") == $table_keys_name) {
				$result = $wpdb->get_row("SELECT * FROM ".$table_keys_name." WHERE id = 1");
				if(!empty($result)) {
					$update = $wpdb->update($table_keys_name, array(
					    'merchant_keys' => $merchant_key,
					    'api_keys' => $api_key,
					), array('id' => 1));
					if($update) {
						$json['status'] = "success";
					} else {
						$json['status'] = "failure";
					}
				} else {
				    $save = $wpdb->insert($table_keys_name, array(
					    'merchant_keys' => $merchant_key,
					    'api_keys' => $api_key,
					));
					if($save) {
						$json['status'] = "success";
					} else {
						$json['status'] = "failure";
					}
				}
			}
			else {
				$json['status'] = "table_not_found";
			}
		}
		else {
			$json['status'] = "keys_not_found";
		}
		echo json_encode($json);
		exit();
	}
}