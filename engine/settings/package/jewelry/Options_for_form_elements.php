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
			
			"sales" => "color_of_gold",
			"color_of_gold" => "production",
			
			"production" => "country_list",
			"country_list" => "state_list",
			"state_list" => "cities_list",
			
			"cities_list" => "departments",
			
			"departments" => "general_settings",
			"general_settings" => "modules",
			"modules" => "functions",
			"functions" => "access_roles",
			"access_roles" => "finish",
		);
	}
	
	function get_colors(){
		return array( 
			"white" => "White",
		);
	}
	
	function get_source_of_percentage_markup(){
		return array( 
			"category" => "Category",
			"item" => "Item",
		);
	}
	
	function get_color_of_gold(){
		$cache_key = 'color_of_gold';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
?>