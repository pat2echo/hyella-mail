<?php 
	
	function get_cache_refresh_keys(){
		return array( 
			"users" => "banks",
			"banks" => "category",
			"category" => "items", 
			"items" => "vendors", 
			//"vendors" => "discount", 
			"vendors" => "assets",
			
			"assets" => "assets_category",
			"assets_category" => "grade_level",
			"grade_level" => "discount",
			
			"discount" => "customers", 
			"customers" => "stores",
			"stores" => "sales",
			"sales" => "production",
			
			"production" => "departments",
			
			//"production" => "departments", 
			"departments" => "general_settings",
			"general_settings" => "modules",
			"modules" => "functions",
			"functions" => "access_roles",
			"access_roles" => "finish",
		);
	}
	
	function get_import_newsletter_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'import_newsletter';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_sending_channels(){
		return array(
			"gmail" => "GMAIL SERVER",
			"unsigned_mail" => "UNSIGNED MAIL SERVER",
		);
	}
	
?>