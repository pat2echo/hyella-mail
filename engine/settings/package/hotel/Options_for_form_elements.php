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
	
	function get_hotel_checkin_report_types(){
		return array(
			"all_rooms_chart" => "Chart of All Rooms",
			"available_rooms_report" => "List of Vacant Rooms",
			"occuppied_rooms_report" => "List of Occuppied Rooms",
			"in_active_rooms_report" => "List of Out-of-Order Rooms",
			"blocked_rooms_report" => "List of Blocked Rooms",
			"" => "",
			"room_checkin_log" => "List of Checked-in Guest",
			"room_checkin_checkout_log" => "Room Checkin / Checkout Log",
			"room_reservations_log" => "Room Reservations Log",
			"room_cancellation_log" => "Room Cancellations",
			"guest_activity_report" => "Guest Activity Report",
		);
	}
	
	function get_night_audit_report_type(){
		return array(
			/*
			"transactions_initiated_report" => "Transactions Initiated", 
			"transactions_completed_report" => "Transactions Completed",
			*/
			"list_of_guest_owing" => "Customers Owing Summary",
			"list_of_guest_owing_details" => "Customers Owing",
			"" => "",
			"floor_sheet_report" => "Floor Sheet Report",
			"all_transactions_report" => "All Transactions by Shift",
			"high_credit_balance_report" => "Daily High Credit Balance",
			/*
			"" => "",
			"room_activity_report" => "Room Checked-in / Checked-out",
			*/
		);
	}
	
	function get_night_audit_report_time(){
		return array(
			"all" => "Day & Night", 
			"day" => "Day Only",
			"night" => "Night Only",
		);
	}
	
	function get_hotel_room_service_types(){
		return array(
			"inclusive" => "Inclusive of Room Rate", 
			"exclusive" => "Exclusive of Room Rate",
		);
	}
	
	function get_checkin_types(){
		return array(
			"guest" => "Individual Guest", 
			"group" => "Group Guest",
		);
	}
	
	function get_hotel_room_cleaning_status(){
		return array(
			"cleaned" => "Cleaned", 
			"uncleaned" => "Uncleaned",
		);
	}
	
	function get_hotel_room_status(){
		return array( 
			//"faulty" => "Faulty",
			"blocked" => "Blocked",
			"in_maintenance" => "Out of Order", //In Maintenance
			"active" => "Ready for Use",	//Active
		);
	}
	
	function get_hotel_room_booking_status(){
		return array(
			"reserved" => "Reserved",
			"booked" => "Booked",
			"checked_in" => "Checked-in",
			"checked_out" => "Checked-out",
			"cancelled" => "Rebate",
		);
	}
	
	function get_hotel_room_types(){
		$cache_key = 'hotel_room_type';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
		
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_hotel_room_types_details(){
		$cache_key = 'hotel_room_type';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
		
        if( isset( $return['all'] ) )unset( $return['all'] );
		return $return;
	}
	
	function get_hotel_rooms(){
		
		$cache_key = 'hotel_room';
        $return = get_from_cached( array(
            'cache_key' => $cache_key . "-available-rooms",
			'directory_name' => $cache_key,
        ) );
		
		if( isset( $_SESSION["show_avaialable_rooms"] ) && is_array( $_SESSION["show_avaialable_rooms"] ) ){
			$r = $return;
			$return = array();
			foreach( $_SESSION["show_avaialable_rooms"] as $val ){
				if( isset( $r[ $val["id"] ] ) ){
					$return[ $val["id"] ] = $r[ $val["id"] ];
				}
			}
			asort($return);
		}
		
        if( isset( $return ) )return $return;
	}
	
	function get_hotel_rooms_with_details(){
		$cache_key = 'hotel_room';
        $return = get_from_cached( array(
            'cache_key' => $cache_key . "-all-available-rooms",
			'directory_name' => $cache_key,
        ) );
		
		return $return;
	}
	
	function get_unavailable_hotel_rooms_with_details(){
		$cache_key = 'hotel_room';
        $return = get_from_cached( array(
            'cache_key' => $cache_key . "-all-unavailable-rooms",
			'directory_name' => $cache_key,
        ) );
		
		return $return;
	}
	
	function get_all_hotel_rooms_with_details(){
		$cache_key = 'hotel_room';
        $return = get_from_cached( array(
            'cache_key' => $cache_key . "-all-rooms",
			'directory_name' => $cache_key,
        ) );
		
		return $return;
	}
	
	function get_hotel_room_services(){
		$cache_key = 'hotel_room_service';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
		
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_hotel_checkin_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'hotel_checkin';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
?>