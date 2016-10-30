<?php 

	function get_cache_refresh_keys(){
		return array( 
			"users" => "banks",
			"banks" => "category",
			"category" => "items", 
			"items" => "vendors", 
			"vendors" => "discount", 
			"discount" => "customers", 
			"customers" => "stores",
			"stores" => "sales",
			"sales" => "production",
			"production" => "departments", 
			"departments" => "general_settings",			
			"general_settings" => "modules",
			
			"modules" => "functions",
			"functions" => "access_roles",
			"access_roles" => "finish",
		);
	}
	
?>