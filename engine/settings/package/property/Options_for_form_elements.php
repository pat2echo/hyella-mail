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
	
	function get_billing_cycle(){
		return array( 
			"daily" => "Daily",
			"weekly" => "Weekly",
			"monthly" => "Monthly", 
			"yearly" => "Yearly", 
		);
	}
	
	function get_billing_cycle_property(){
		return array( 
			"inherit" => "Inherit from Category",
			"daily" => "Daily",
			"weekly" => "Weekly",
			"monthly" => "Monthly", 
			"yearly" => "Yearly", 
		);
	}
	
	function get_billing_cycle_text( $cycle ){
		switch( $cycle ){
		case "daily":
			return "Day";
		break;
		case "weekly":
			return "Week";
		break;
		case "monthly":
			return "Month";
		break;
		case "yearly":
			return "Year";
		break;
		}
	}
	
	function get_occupancy_status(){
		return array( 
			"ready_for_use" => "Ready for Use",
			"blocked" => "Blocked",
			"out_of_order" => "Out of Order",
		);
	}
?>