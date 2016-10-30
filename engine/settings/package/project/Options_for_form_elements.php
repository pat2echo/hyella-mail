<?php 
	/*
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
			
			"production" => "hotel_room_type",
			"hotel_room_type" => "hotel_room_type_checkin",
			"hotel_room_type_checkin" => "hotel_room_service",
			"hotel_room_service" => "hotel_room_checkin_service",
			"hotel_room_checkin_service" => "hotel_room_checkin",
			"hotel_room_checkin" => "hotel_room",
			"hotel_room" => "hotel_checkin",
			"hotel_checkin" => "departments",
			
			//"production" => "departments", 
			"departments" => "general_settings",
			"general_settings" => "modules",
			"modules" => "functions",
			"functions" => "access_roles",
			"access_roles" => "finish",
		);
	}
	*/
	include dirname( dirname( __FILE__ ) ) . "/hotel/Options_for_form_elements.php";
	
	function get_task_types(){
		return array(
			"basic" => "Basic Task",
			"milestone" => "Milestone",
		);
	}
	
	function get_project_categories(){
		return array(
			"uncategorized" => "Uncategorized",
		);
	}
	
	function get_project_status(){
		return array(
			"in-progress" => "In-progress",
			"completed" => "Completed",
			"suspended" => "Suspended",
			"abandoned" => "Abandoned",
		);
	}
	
?>