<?php
class SkySystemz {
	public static function SS_plugin_activation() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'skysystemz_payment';
		$sql = "CREATE TABLE `$table_name` ( 
			`id` INT(11) NOT NULL AUTO_INCREMENT ,  
			`user_id` INT(11) NOT NULL ,  
			`cust_name` VARCHAR(220) NOT NULL , 
			`card_no` VARCHAR(220) NOT NULL , 
			`expiration_month` VARCHAR(220) NOT NULL ,
			`expiration_year` VARCHAR(220) NOT NULL ,
			`security_code` INT(4) NOT NULL ,
			`status` INT(2) NOT NULL ,  
			`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,  
			`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,    PRIMARY KEY  (`id`)) ENGINE = InnoDB DEFAULT CHARSET=latin1;";
		
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		    dbDelta($sql);
		}

		$table_keys_name = $wpdb->prefix . 'skysystemz_keys';
		$sql = "CREATE TABLE `$table_keys_name` ( 
			`id` INT(11) NOT NULL AUTO_INCREMENT ,  
			`merchant_keys` VARCHAR(500) NOT NULL ,  
			`api_keys` VARCHAR(500) NOT NULL ,
			`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,  
			`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,    PRIMARY KEY  (`id`)) ENGINE = InnoDB DEFAULT CHARSET=latin1;";
		
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_keys_name'") != $table_keys_name) {
		    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		    dbDelta($sql);
		}
	}

	public static function SS_plugin_deactivation() {
		global $wpdb;
	    $table_name = $wpdb->prefix . 'skysystemz_payment';
	    $sql = "DROP TABLE IF EXISTS $table_name";
	    $wpdb->query($sql);

	    $table_keys_name = $wpdb->prefix . 'skysystemz_keys';
	    $sql2 = "DROP TABLE IF EXISTS $table_keys_name";
	    $wpdb->query($sql2);
	}
}