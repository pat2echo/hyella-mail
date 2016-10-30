<?php 
	/**
	 * Kwaala Select Combo Box Population File
	 *
	 * @used in  				classes/cForms.php, includes/ajax_server_json_data.php
	 * @created  				none
	 * @database table name   	none
	 */
		
	function get_bank_names(){
		$cache_key = 'banks';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_pay_roll_report_types(){
		return array(
			'payment_summary' => 'Salary Summary',
			'work_schedule' => 'Work Schedule',
			'0' => '',
			'staff_salary_schedule' => 'Staff Salary Schedule (Naira)',
			'payment_schedule' => 'Payment Schedule (Summary)',
			'payment_schedule_bank' => 'Payment Schedule for Bank',
			'payment_schedule_details' => 'Payment Schedule (Details)',
			'pay_slip' => 'Pay Slips (Naira)',
			'' => '',
			
			'staff_salary_schedule_dollar' => 'Staff Salary Schedule (Dollar)',
			'payment_schedule_dollar' => 'Payment Schedule (Summary | Dollar) ',
			'payment_schedule_bank_dollar' => 'Payment Schedule for Bank (Dollar)',
			'payment_schedule_details_dollar' => 'Payment Schedule (Details | Dollar)',
			'pay_slip_dollar' => 'Pay Slips (Dollar)',
		);
	}
	
	function get_salary_schedule(){
		return array(
			'naira' => 'Naira',
			'dollar' => 'US Dollar',
		);
	}
	
	function get_salary_generation_type(){
		return array(
			'previous_month_data' => 'Use Previous Month Data',
			'salary_schedule' => 'Use Grade Level Data',
		);
	}
	
	function get_report_periods_without_weeks(){
		$r = get_report_periods();
		unset( $r["weekly"] );
		return $r;
	}
	
	function get_type_of_vendor(){
		return array(
			'supplier' => 'Supplier',
			'factory' => 'Factory',
		);
	}
	
	function get_report_periods(){
		return array(
			'daily' => 'Daily',
			'weekly' => 'Weekly',
			'monthly' => 'Monthly',
            'yearly' => 'Yearly',
		);
	}
	
	function get_factories(){
		return get_stores();
		return array();
	}
	
	function get_income_verse_expenditure_report_types(){
		return array(
			'income_expenditure_report' => 'Tabular Report',
			'income_expenditure_graphical_report' => 'Graphical Report',
		);
	}
	
	function get_inventory_report_types(){
		return array(
			'low_stock_level_report' => 'Low Stock Level Report',
			'stock_level_report' => 'Stock Level Report',
			'stock_supply_history_report' => 'Stock Supply History Report',
			'stock_supply_history_report_picture' => 'Stock Supply History Catalog',
		);
	}
	
	function get_sales_report_types(){
		
		if( defined( "HYELLA_PACKAGE" ) ){
			switch( HYELLA_PACKAGE ){
			case "hotel":				
				return array(
					'today_sales_report' => 'Today Sales Report',
					//'periodic_sales_report' => 'Periodic Sales Report',
					'unpaid_sales_report' => 'Sales Report: All Transactions',
					'part_payment_sales_report' => 'Sales Report: Unpaid Transactions',
					'' => '',
					'most_sold_item_report' => 'Most Sold Item Report',
					'most_profitable_item_report' => 'Most Income Generating Item Report',
				);
			break;
			case "property":				
				return array(
					//'today_sales_report' => 'Today Sales Report',
					'periodic_sales_report' => 'Periodic Rent Report',
					'unpaid_sales_report' => 'Rent Report: All Transactions',
					'part_payment_sales_report' => 'Rent Report: Unpaid Transactions',
					//'booked_sales_report' => 'Booked Sales',
					'' => '',
					'customers_owing_report' => 'Tenants Owing',
					'customers_transaction_report' => 'Tenants Transaction',
					'most_valued_customers_report' => 'Most Valued Tenants',
					//' ' => '',
					//'most_sold_item_report' => 'Most Sold Item Report',
					//'most_profitable_item_report' => 'Most Income Generating Item Report',
				);
			break;
			}
		}
		
		return array(
			'today_sales_report' => 'Today Sales Report',
			'periodic_sales_report' => 'Periodic Sales Report',
			'unpaid_sales_report' => 'Sales Report: All Transactions',
			'part_payment_sales_report' => 'Sales Report: Unpaid Transactions',
			'booked_sales_report' => 'Booked Sales',
			'' => '',
			'customers_owing_report' => 'Customers Owing',
			'customers_transaction_report' => 'Customers Transaction',
			'most_valued_customers_report' => 'Most Valued Customers',
			' ' => '',
			'most_sold_item_report' => 'Most Sold Item Report',
			'most_profitable_item_report' => 'Most Income Generating Item Report',
		);
	}
	
	function get_expenditure_batch_payment_report_types(){
		return array(
			'draft_batch_payment_report' => 'Draft Expenses',
			'pending_payment_report' => 'Unpaid Expenses',
			'all_payment_report' => 'Paid Expenses',
			'all_expenses_report' => 'All Expenses',
		);
	}
	
	function get_expenditure_report_types(){
		return array(
			'periodic_expense_report' => 'Periodic Expense Report',
			'unpaid_expense_report' => 'Expenses Report: Unpaid',
			'part_payment_expense_report' => 'Expenses Report: Unpaid & Part Payments',
		);
	}
	
	function get_customers_details( $settings = array() ){
		$cache_key = 'customers';
        return get_from_cached( array(
            'cache_key' => $cache_key."-".$settings["id"],
			'directory_name' => $cache_key,
        ) );
	}
	
	function get_customers_by_phone( $settings = array() ){
		$cache_key = 'customers';
        return get_from_cached( array(
            'cache_key' => $cache_key."-".$settings["phone"],
			'directory_name' => $cache_key,
        ) );
	}
	
	function get_customers(){
		$cache_key = 'customers';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_items_categories_all(){
		$cache_key = 'category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        return $return;
	}
	
	function get_items_categories_details( $settings = array() ){
		$cache_key = 'category';
		if( isset( $settings["id"] ) && $settings["id"] ){
			$return = get_from_cached( array(
				'cache_key' => $cache_key,
				'directory_name' => $cache_key,
			) );
			
			if( isset( $return[ $settings["id"] ] ) )return $return[ $settings["id"] ];
		}
	}
	
	function get_items_categories(){
		$cache_key = 'category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_items_sub_categories(){
		$cache_key = 'category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_items_categories_grouped(){
		$cache_key = 'category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['group'] ) )return $return['group'];
	}
	
	function get_items_categories_grouped_category_type( $type ){
		$cache_key = 'category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
		$t = get_product_types();
		$tr = (isset( $t[$type] )?$t[$type]:$type );
		
		if( isset( $return['group'][ $tr ] ) ){
			$return = $return['group'][ $tr ];
			asort( $return );
			return $return;
		}
		
		return array();
	}
	
	function get_items_categories_grouped_raw_materials(){
		return get_items_categories_grouped_category_type( "raw_materials" );
	}
	
	function get_items_categories_grouped_goods(){
		$return = get_items_categories_grouped_category_type( "purchased_goods" );
		$r2 = get_items_categories_grouped_category_type( "produced_goods" );
		$r1 = get_items_categories_grouped_category_type( "service" );
		$r3 = get_items_categories_grouped_category_type( "consignment" );
		if( ! empty( $r1 ) ){
			foreach( $r1 as $rk => $rv )
				$return[ $rk ] = $rv;
		}
		if( ! empty( $r2 ) ){
			foreach( $r2 as $rk => $rv )
				$return[ $rk ] = $rv;
		}
		if( ! empty( $r3 ) ){
			foreach( $r3 as $rk => $rv )
				$return[ $rk ] = $rv;
		}
		
		return $return;
	}
	
	function get_items_categories_grouped_purchased_goods(){
		$return = get_items_categories_grouped_category_type( "purchased_goods" );
		$r2 = get_items_categories_grouped_category_type( "raw_materials" );
		$r1 = get_items_categories_grouped_category_type( "consignment" );
		if( ! empty( $r1 ) ){
			foreach( $r1 as $rk => $rv )
				$return[ $rk ] = $rv;
		}
		if( ! empty( $r2 ) ){
			foreach( $r2 as $rk => $rv )
				$return[ $rk ] = $rv;
		}
		
		return $return;
	}
	
	function get_items_categories_grouped_service(){
		return get_items_categories_grouped_category_type( "service" );
	}
	
	function get_items_categories_grouped_produced_goods(){
		return get_items_categories_grouped_category_type( "produced_goods" );
	}
	
	function get_store_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'stores';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key ,
				'directory_name' => $cache_key,
			) );
			
			if( isset( $cached_values[ $settings['id'] ] ) )
				return $cached_values[ $settings['id'] ];
		}
		
		return array();
	}
	
	function get_stores(){
		$cache_key = 'stores';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_sales_status(){
		return array(
			'sold' => 'Sold',
            'booked' => 'Reserved',
		);
	}
	
	function get_stock_status(){
		return array(
			'complete' => 'Production Complete',
            'in-progress' => 'In Production',
            'pending' => 'Pending Production',
            'materials-utilized' => 'Materials Utilized',
            'materials-transfer' => 'Materials Transfered',
            'damaged-materials' => 'Damaged Materials',
		);
	}
	
	function get_message_types(){
		return array(
			'email' => 'Email',
            'sms' => 'SMS',
		);
	}
	
	function get_type_of_note(){
		return array(
			'note' => 'Note',
            'minutes' => 'Minutes',
            'report' => 'Report',
            //'follow_up' => 'Follow Up',
		);
	}
	
	function get_product_types(){
		if( defined( "HYELLA_PACKAGE" ) ){
			switch( HYELLA_PACKAGE ){
			case "jewelry":				
				return array(
					'purchased_goods' => 'Purchased Goods for Sale',
					'service' => 'Service',
				);
			break;
			}
		}
		return array(
			'consignment' => 'Consignment',
			'purchased_goods' => 'Purchased Goods for Sale',
			'produced_goods' => 'Produced Goods for Sale',
			'raw_materials' => 'Raw Material',
			'service' => 'Service',
		);
	}
	
	function get_calendar(){
		return array(
			'general_calendar' => 'General Calendar',
		);
	}
	
	function get_halls_types(){
		return array(
			'hall' => 'Hall Only',
			'package' => 'Package: Hall + Other Items',
		);
	}
	
	function get_unit_types(){
		return array(
			'daily' => 'per Day',
			'hourly' => 'per Hour',
		);
	}
	
	function get_event_category(){
		$return = array(
			'meeting' => 'Meetings & Conference',
			'events' => 'Functions & Special Events',
            'wedding' => 'Wedding',
            'concert_and_shows' => 'Concerts & Shows',
            'camping' => 'Camping',
            'birthday' => 'Birthday',
            'training' => 'Training',
            'gala_night' => 'Gala Night',
		);
		asort( $return );
		return $return;
	}
	
	function get_event_types(){
		return array(
			'once' => "One Time Event",
			'weekly' => "Recurring Weekly",
			'bi_weekly' => "Recurring Bi-Weekly",
			'monthly' => "Monthly",
			'bi_monthly' => "Bi-Monthly",
			'quarterly' => "Quarterly",
			'half_year' => "Half Year",
			'yearly' => "Yearly",
			'bi_yearly' => "Bi Yearly",
		);
	}
	
	function get_list_of_guests(){
		return array(
			'email' => 'Email',
            'sms' => 'SMS',
		);
	}
	
	function get_reminder_frequency(){
		return array(
			'one_day' => 'A Day Before',
            'two_days' => '2 Days Before',
            'three_days' => '3 Days Before',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
		);
	}
	
	function get_types_of_expenditure(){
		$return = array();
		if( function_exists( "get_account_children" ) ){
			$return = get_account_children( array( "id" => "operating_expense", "parent" => "parent1" ) );
			$return1 = get_account_children( array( "id" => "cost_of_goods_sold", "parent" => "parent1" ) );
			$return2 = array_merge( $return1 , $return );
			asort( $return2 );
			return $return2;
		}
		
		$return = array(
            'purchase_of_items' => 'Purchase of Goods for Sale',
			'purchase_of_materials' => 'Purchase of Raw Materials',
			//'cost_of_production' => 'Extra Cost of Production',
			'purchase_of_replacement' => 'Purchase of Replacement Parts',
			'payment_of_utilities' => 'Payment of Utilities',
			'fueling' => 'Fueling',
			'repairs' => 'Repairs / Maintenance',
			'consultancy' => 'Consultancy',
			
			'salary' => 'Salary & Wages',
			
			'rent' => 'Rent',
			'vehicle_depreciation' => 'Vehicle Depreciation',
			'equipment_depreciation' => 'Equipment Depreciation',
			'others' => 'Others',
			
			'pr_and_advert' => 'PR & Advert',
			'staff_welfare' => 'Staff Welfare',
			'motor_vehicle_running_expense' => 'Motor Vehicle Running Expense',
			'transport_and_travels' => 'Transport & Travels',
			'printing_and_stationery' => 'Printing & Stationery',
			'staff_uniform' => 'Staff Uniform',
			'motor_vehicle' => 'RM Motor Vehicle',
			'building' => 'RM Building',
			'equipment' => 'RM Equipment',
			'telephone_and_postage' => 'Telephone & Postage',
			'purchase_commission' => 'Purchase Commission',
			'sales_commission' => 'Sales Commission',
			'security_and_safety_measures' => 'Security & Safety Measures',
			'diesel' => 'Diesel',
			'registration_and_bill' => 'Registration & Bill',
			'furniture_and_fitting' => 'Furniture & Fitting',
			'power_and_electricity' => 'Power & Electricity',
			'staff_meal' => 'Staff Meal',
			'internet_subscription' => 'Internet Subscription',
			'cable_tv' => 'Cable TV',
			'bank_charges' => 'Bank Charges',
			'paye_feb' => 'Paye Feb',
			
			'cleaning_and_sanitation' => 'Cleaning & Sanitation',
			'medical' => 'Medical',
			'sundry_expenses' => 'Sundry Expenses',
			'loan' => 'Loan',
			'gratuity' => 'Gratuity',
			'consultancy_fees' => 'Consultancy Fees',
			'leave_allowance' => 'Leave Allowance',
		);
		
		asort( $return );
		return $return;
	}
	
	function get_types_of_expenditure_grouped(){
		$return = array();
		if( function_exists( "get_account_children" ) ){
			$return = get_account_children( array( "id" => "operating_expense", "parent" => "parent1" ) );
			$return1 = get_account_children( array( "id" => "cost_of_goods_sold", "parent" => "parent1" ) );
			
			asort( $return );
			
			return array(
				//"Direct Expenses" => $return1,
				"In-direct Expenses" => $return,
			);
		}
		
		return array(
			"Direct Expenses" => array(
				'purchase_of_items' => 'Purchase of Goods for Sale',
				'purchase_of_materials' => 'Purchase of Raw Materials',
			),
            "In-direct Expenses" => array(
				'purchase_of_replacement' => 'Purchase of Replacement Parts',
				'payment_of_utilities' => 'Payment of Utilities',
				'fueling' => 'Fueling',
				'repairs' => 'Repairs / Maintenance',
				'consultancy' => 'Consultancy',
				'salary' => 'Salary',
				'rent' => 'Rent',
				
				'pr_and_advert' => 'PR & Advert',
				'staff_welfare' => 'Staff Welfare',
				'motor_vehicle_running_expense' => 'Motor Vehicle Running Expense',
				'transport_and_travels' => 'Transport & Travels',
				'printing_and_stationery' => 'Printing & Stationery',
				'staff_uniform' => 'Staff Uniform',
				'motor_vehicle' => 'RM Motor Vehicle',
				'building' => 'RM Building',
				'equipment' => 'RM Equipment',
				'telephone_and_postage' => 'Telephone & Postage',
				'purchase_commission' => 'Purchase Commission',
				'sales_commission' => 'Sales Commission',
				'security_and_safety_measures' => 'Security & Safety Measures',
				'diesel' => 'Diesel',
				'registration_and_bill' => 'Registration & Bill',
				'furniture_and_fitting' => 'Furniture & Fitting',
				'power_and_electricity' => 'Power & Electricity',
				'staff_meal' => 'Staff Meal',
				'internet_subscription' => 'Internet Subscription',
				'cable_tv' => 'Cable TV',
				'bank_charges' => 'Bank Charges',
				'paye_feb' => 'Paye Feb',
				
				'cleaning_and_sanitation' => 'Cleaning & Sanitation',
				'medical' => 'Medical',
				'sundry_expenses' => 'Sundry Expenses',
				'loan' => 'Loan',
				'gratuity' => 'Gratuity',
				'consultancy_fees' => 'Consultancy Fees',
				'leave_allowance' => 'Leave Allowance',
			),
            "Depreciating Asset" => array(
				'vehicle_depreciation' => 'Vehicle Depreciation',
				'equipment_depreciation' => 'Equipment Depreciation',
			),
			"Others" => array(
				'others' => 'Others',
			)
		);
	}
	
	function get_types_of_income(){
		return array(
			'hall' => 'Hall Rental',
            'none' => 'Other Items Rental',
		);
	}
	
	function get_vendors_supplier(){
		return get_vendors_type( "supplier" );
	}
	
	function get_vendors_factory(){
		return get_vendors_type( "factory" );
	}
	
	function get_vendors_type( $type ){
		$cache_key = 'vendors';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
		
        if( isset( $return['all'] ) ){
			foreach( $return['all'] as $k => $v ){
				if( isset( $return[ $k ][ "type" ] ) && $return[ $k ][ "type" ] != $type ){
					unset( $return['all'][ $k ] );
				}
			}
		}
		
		if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_vendors(){
		$cache_key = 'vendors';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
		
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_vendors_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'vendors';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
			return $cached_values;
		}
		
		return array();
	}
	
	function get_discount(){
		$cache_key = 'discount';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
		
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_items_group_items(){
        $return = get_items_group();
	}
	
	function get_items_grouped_category_type( $type, $type1 = "", $type2 = "" ){
		$cache_key = 'items';
        $return = get_from_cached( array(
            'cache_key' => $cache_key."-grouped-category",
			'directory_name' => $cache_key,
        ) );
		
		if( $return ){
			$r1 = array();
			$p = get_product_types();
			foreach( $return as $k => $v ){
				if( ( $k == $type || $k == $type1 || $k == $type2 ) ){
					$r1[ isset( $p[$k] )?$p[$k]:$k ] = $v;
				}
			}
			$return = $r1;
		}
		
		return $return;
	}
	
	function get_items_grouped_raw_materials(){
		return get_items_grouped_category_type( "raw_materials" );
	}
	
	function get_items_grouped_goods(){
		return get_items_grouped_category_type( "produced_goods", "purchased_goods", "service" );
	}
	
	function get_items_grouped_default(){
		return get_items_grouped_category_type( "produced_goods", "purchased_goods", "service", "raw_materials" );
	}
	
	function get_items_produced_goods(){
		$r = get_items_grouped_category_type( "produced_goods" );
		$r1 = array();
		foreach( $r as $rv ){
			$r1 = array_merge( $r1, $rv );
		}
		return $r1;
	}
	
	function get_items_raw_materials(){
		$r = get_items_grouped_category_type( "raw_materials" );
		$r1 = array();
		foreach( $r as $rv ){
			$r1 = array_merge( $r1, $rv );
		}
		return $r1;
	}
	
	function get_items_grouped(){
		$cache_key = 'items';
        $return = get_from_cached( array(
            'cache_key' => $cache_key."-grouped",
			'directory_name' => $cache_key,
        ) );
        if( is_array( $return ) )asort( $return );
		return $return;
	}
	
	function get_items(){
		$cache_key = 'items';
        $return = get_from_cached( array(
            'cache_key' => $cache_key."-all",
			'directory_name' => $cache_key,
        ) );
		
        if( is_array( $return ) )asort( $return );
		return $return;
	}
	
	function get_items_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'items';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
			return $cached_values;
		}
		
		return array();
	}
	
	function get_items_details_by_barcode( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'items';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-barcode-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
			return $cached_values;
		}
		return array();
	}
	
	function get_all_months(){
		$return = get_months_of_year();
		$return["all"] = "All Months";
		return $return;
	}
	
	function get_all_weekdays(){
		$return = get_days_of_week();
		$return["all"] = "All Days";
		return $return;
	}
	
	function get_months_of_year(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December',
		);
	}
	
	function get_days_of_week(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			1 => 'Sunday',
			2 => 'Monday',
			3 => 'Tuesday',
			4 => 'Wednesday',
			5 => 'Thursday',
			6 => 'Friday',
			7 => 'Saturday',
		);
	}
	
	function get_payment_method_grouped(){
		
		$return_group = array();
		$settings = array(
			'cache_key' => 'payment-method-grouped',
			'permanent' => true,
		);
		$cached_values = get_cache_for_special_values( $settings );
		if( ! empty( $cached_values ) )return $cached_values;
		
		$settings = array(
			'cache_key' => 'payment-method',
			'permanent' => true,
		);
		clear_cache_for_special_values( $settings );
		get_payment_method();
			
		$settings = array(
			'cache_key' => 'payment-method-grouped',
			'permanent' => true,
		);
		$cached_values = get_cache_for_special_values( $settings );
		if( ! empty( $cached_values ) ){
			return $cached_values;
		}else{
			$r = get_payment_method_fallback();
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "hotel":
					$return_group["Others"][ 'complimentary' ] = 'Complimentary';
					$return_group["Others"][ 'complimentary_staff' ] = 'Complimentary Staff';
					$return_group["Others"][ 'charge_to_room' ] = 'Charge to Room';
				break;
				}
			}
			$return_group["Main"] = $r;
		}
		
		return $return_group;
	}
	
	function get_payment_method_list(){
		
		$return_group = array();
		$settings = array(
			'cache_key' => 'payment-method-list',
			'permanent' => true,
		);
		$cached_values = get_cache_for_special_values( $settings );
		if( ! empty( $cached_values ) )return $cached_values;
		
		$settings = array(
			'cache_key' => 'payment-method',
			'permanent' => true,
		);
		clear_cache_for_special_values( $settings );
		get_payment_method();
			
		$settings = array(
			'cache_key' => 'payment-method-list',
			'permanent' => true,
		);
		$cached_values = get_cache_for_special_values( $settings );
		if( ! empty( $cached_values ) ){
			return $cached_values;
		}else{
			return array();
		}
	}
	
	function get_payment_method(){
		//check for cache_key
		$set_cache = 0;
		$r = get_payment_method_fallback();
		$return = array();
		$return_group = array();
		$return_list = array();
		$return_list_from_deposit = array();
		
		if( function_exists( "get_account_children" ) ){
			$settings = array(
				'cache_key' => 'payment-method',
				'permanent' => true,
			);
			$cached_values = get_cache_for_special_values( $settings );
			if( ! empty( $cached_values ) )return $cached_values;
			
			$set_cache = 1;
			$return1 = get_account_children( array( "id" => "cash_book", "parent" => "parent1" ) );
			
			if( ! empty( $return1 ) ){
				foreach( $return1 as $key => $val ){
					if( strtolower( trim($val) ) != "bank" && strtolower( trim($val) ) != "bank ()" ){
						switch( $key ){
						case "main_cash":	
						case "petty_cash":
							$return[ $key ] = $val;
							$return_group[ "Cash" ][ $key ] = $val;
						break;
						default:
							$return[ $key ] = $val;
							$return_group[ "Bank" ][ $key ] = $val;
							/*
							foreach( $r as $k => $v ){
								if( $k == "cash" )continue;
								$return[ $key.':::'.$k ] = $v .': '. $val;
								$return_group[ $val ][ $key.':::'.$k ] = $v .': '. $val;
							}
							*/
						break;
						}
						$return_list[ $key ] = $val;
					}
				}
			}
		}else{
			$return = $r;
			$return_group[ "Main" ] = $r;
		}
		
		if( defined( "HYELLA_PACKAGE" ) ){
			switch( HYELLA_PACKAGE ){
			case "hotel":
				$return['complimentary'] = 'Complimentary';
				$return['complimentary_staff'] = 'Complimentary Staff';
				$return['charge_to_room'] = 'Charge to Room';
				
				$return_group[ "Others" ][ 'complimentary' ] = 'Complimentary';
				$return_group[ "Others" ][ 'complimentary_staff' ] = 'Complimentary Staff';
				$return_group[ "Others" ][ 'charge_to_room' ] = 'Charge to Room';
				
				if( get_allow_advance_deposit_payment_settings() ){
					$return['charge_from_deposit'] = 'Charge from Deposit';
					$return_group[ "Others" ][ 'charge_from_deposit' ] = 'Charge From Deposit';
				}
				
			break;
			}
		}
		
		if( $set_cache ){
			$settings = array(
				'cache_key' => 'payment-method',
				'cache_values' => $return,
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$settings = array(
				'cache_key' => 'payment-method-grouped',
				'cache_values' => $return_group,
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$settings = array(
				'cache_key' => 'payment-method-list',
				'cache_values' => $return_list,
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
		}
		
		return $return;
	}
	
	function get_payment_method_fallback(){
		$return = array(
			'cash' => 'Cash',
			'cheque' => 'Cheque',
			'pos' => 'POS',
			'transfer' => 'Transfer',
		);
		return $return;
	}
	
	function get_inventory_status(){
		$return = array(
			'good' => 'Good Condition',
			'need_repairs' => 'Need Repairs',
			'damaged' => 'Damaged',
		);
		return $return;
	}
	
	function get_discount_types(){
		$return = array(
			'fixed_value' => 'Before Tax: Specific Amount',
			'percentage' => 'Before Tax: Percentage Discount',
			
			'fixed_value_after_tax' => 'After Tax: Specific Amount',
			'percentage_after_tax' => 'After Tax: Percentage Discount',
			
			'surcharge' => 'Specific Amount Surcharge',
			'surcharge_percentage' => 'Percentage Surcharge',
		);
		return $return;
	}
	
	function get_discount_types_grouped(){
		$return = array(
			"Before Tax" => array(
				'fixed_value' => 'Specific Amount',
				'percentage' => 'Percentage Discount',
			),
			"After Tax" => array(
				'fixed_value_after_tax' => 'Specific Amount',
				'percentage_after_tax' => 'Percentage Discount',
			),
			"Surcharge / Tax" => array(
				'surcharge' => 'Surcharge',
				'surcharge_percentage' => 'Percentage Surcharge',
			),
		);
		return $return;
	}
	
	function get_approval_status(){
		$return = array(
            'pending' => 'Pending',
			
			//'part_payment' => 'Part Payment',
			
            'paid' => 'Paid',
			'paid_unavailable' => 'Paid & Unavailable',
			
			'unavailable_date' => 'Unavailable Date',
		);
		return $return;
	}
	
	function get_approval_status_icons(){
		$return = array(
			'cancelled' => '<i class="icon-remove"></i>',
			're_scheduled' => '<i class="icon-refresh"></i>',
            
            'pending' => '<i class="icon-question-sign"></i>',
			
            'approved' => '<i class="icon-check"></i>',
			'declined' => '<i class="icon-remove"></i>',
			'postponed' => '<i class="icon-retweet"></i>',
			
			'complete' => '<i class="icon-thumbs-up"></i>',
			'missed' => '<i class="icon-thumbs-down"></i>',
		);
		return $return;
	}
	
	function get_approval_status_colors(){
		$return = array(
			'cancelled' => 'color:#d84a38;',
			're_scheduled' => 'color:#852b99;',
            
            'pending' => 'color:#ed9c28;',
			
            'approved' => 'color:#35aa47;',
			'declined' => 'color:#d84a38;',
			'postponed' => 'color:#852b99;',
			
			'complete' => 'color:#0362fd;',
			'missed' => 'color:#852b99;',
		);
		return $return;
	}
	
	function get_appsettings(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		$cache_key = 'appsettings';
		return get_from_cached( array(
			'cache_key' => $cache_key,
		) );
	}
    
	function get_site_user_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'site_users';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
			return $cached_values;
		}
		
		return array();
	}
	
	function get_calendar_years(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		$this_year = date("Y") + 10;
		
		$return = array();
		for( $i = $this_year; $i > 1997; --$i ){
			$return[ $i ] = $i;
		}
		return $return;
	}
	
	function get_payment_type_status(){
		$return = array(
			'pending' => 'Pending',
            'paid' => 'Paid',
			'failed' => 'Failed',
			'cancelled' => 'Cancelled',
		);
		return $return;
	}
	
	function get_pages(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'login' => 'Login Page',
			'register' => 'Register Page',
		);
	}
	
	function get_type_invoice_quotation(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'quotation' => 'Quotation',
			'invoice' => 'Invoice',
		);
	}
	
	function get_my_customers( $settings = array() ){
		$r = array();
		if( isset( $_SESSION["customers_option"] ) && $_SESSION["customers_option"] ){
			return $_SESSION["customers_option"];
		}
		return $r;
	}
	
	function get_my_surcharges_taxes( $settings = array() ){
		$r = array();
		if( isset( $_SESSION["surcharges_taxes_option"] ) && $_SESSION["surcharges_taxes_option"] ){
			$r = $_SESSION["surcharges_taxes_option"];
		}
		$r[""] = "--Select Tax--";
		$r["new"] = "Add Tax";
		return $r;
	}
	
	function get_payment_status(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'pending' => 'Pending',
			'paid' => 'Paid',
			'failed' => 'Failed',
		);
	}
	
	function get_payment_type(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'paid_in_full' => 'Paid in Full',
			'partial_payment' => 'Partial Payment',
		);
	}
	
	function get_payment_option(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'paid_to_teller' => 'Paid to Teller',
			'electronic_payment' => 'Electronic Payment',
			'transfer' => 'Transfer',
		);
	}
	
	function get_class_names(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'sales' => 'Sales',
			'discount' => 'Discount',
			'customers' => 'Customers',
			'items' => 'Items',
			'inventory' => 'Inventory',
			'barcode' => 'Barcode',
			'hotel_room_checkin' => 'Hotel Room Check-In',
			'hotel_checkin' => 'Hotel Check-In',
			'pay_row' => 'Pay Roll',
			'expenditure' => 'Expenditure',
			'customer_deposits' => 'Customer Deposits',
		);
	}
	
	function get_website_pages_width(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'wide' => 'Wide',
			'narrow' => 'Narrow',
		);
	}
	
	function get_website_pages(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		$cache_key = 'website_pages';
		return get_from_cached( array(
			'cache_key' => $cache_key,
		) );
	}
	
	function get_production_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'production';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_sales_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'sales';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_production_items_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'production_items';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_sales_items_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'sales_items';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_merchants( $settings = array() ){
		$cache_key = 'site_users';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key.'-merchants',
			'directory_name' => $cache_key,
		) );
		if( is_array( $cached_values ) )
			return $cached_values;
		
		return array();
	}
	
	function get_website_sidebars_type(){
		return array(
			'user_defined' => 'User Defined',
			'system_defined' => 'System Defined',
		);
	}
	
	function get_discount_type(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'percentage' => 'Percentage',
			'fixed_value' => 'Fixed Value',
		);
	}
	
	function get_notification_states(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'read' => 'Read',
			'unread' => 'Unread',
		);
	}
	
	function get_task_status(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'pending' => 'Pending',
			'complete' => 'Complete',
		);
	}

	function get_unit_of_time(){
		//RETURN ARRAY OF TIME UNITS
		return array(
			'1' => 'Seconds',
			'60' => 'Minutes',
			'3600' => 'Hours',
			'86400' => 'Days',
			'2592000' => 'Months',
			'31536000' => 'Years',
		);
	}

	function get_notification_types(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'pending_task' => 'Pending Task',
			'completed_task' => 'Completed Task',
			'no_task' => 'No Task',
		);
	}

	function get_yes_no(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'yes' => 'Yes',
			'no' => 'No',
		);
	}

	function get_no_yes(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'' => '',
			'no' => 'No',
			'yes' => 'Yes',
		);
	}
    
	function get_i_agree(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'no' => 'I Do Not Agree',
			'yes' => 'I Agree',
		);
	}
    
	function get_entry_exit(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'entry' => 'Entry',
			'exit' => 'Exit',
		);
	}
    
	function get_form_field_types(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'text' => 'Textbox',
			'number' => 'Number',
			'decimal' => 'Decimal',
			'tel' => 'Phone Number',
			'url' => 'URL',
			'email' => 'Email Address',
			'date' => 'Date',
			'textarea' => 'Textarea',
			'file' => 'File Upload',
		);
	}

	function get_staff_roles(){
		//RETURN ARRAY OF STAFF ROLES
		
		if(isset($_SESSION['temp_storage']['access_roles']['access_roles']) && is_array($_SESSION['temp_storage']['access_roles']['access_roles'])){
			return $_SESSION['temp_storage']['access_roles']['access_roles'];
		}else{
			return array();
		}
	}

	function get_file_import_options(){
		//RETURN ARRAY OF FILE IMPORT OPTIONS
		return array(
			'100' => 'Insert Data As New Records',
			'200' => 'Update Existing Records',
		);
	}

	function get_import_items_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'import_items';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_import_file_field_mapping_options(){
		//RETURN ARRAY OF IMPORT FILE FIELD MAPPING OPTIONS
		return array(
			'serial-import' => 'Serial Import of Excel Fields',
			'200' => 'Use Field Names Defined in First Row of Excel Table',
			'400' => 'Use NAPIMS Cash Calls Template-1',
		);
	}

	function get_import_table_fields(){
		//RETURN ARRAY OF IMPORT TABLE FIELDS
		$returning_array = array();
		
		if( isset( $_SESSION['temp_storage'][ 'excel_import_table' ] ) && $_SESSION['temp_storage'][ 'excel_import_table' ] ){
			$function_name = $_SESSION['temp_storage'][ 'excel_import_table' ];
			
			if( function_exists( $function_name ) ){
				$untransformed_field_data = $function_name();
				
				foreach( $untransformed_field_data as $key => $value ){
					$returning_array[$key] = $value[ 'field_label' ];
				}
			}
		}
		
		return $returning_array;
	}

	function get_security_questions(){
		//RETURN ARRAY OF SECURITY QUESTIONS
		return array(
			'30' => 'Mother\'s Maiden Name',
			'40' => 'Name of First Pet',
			'50' => 'Favorite Uncle\'s Name',
		);
	}

	function get_sex(){
		//RETURN ARRAY OF SEX
		return array(
			'male' => 'Male',
			'female' => 'Female',
		);
	}

	function get_marital_status(){
		//RETURN ARRAY OF MARITAL STATUS
		return array(
			'30' => 'Single',
			'40' => 'Married',
		);
	}

	function get_accessible_functions_tooltips(){
		//RETURN ARRAY OF ACCESSIBLE FUNCTIONS TOOLTIPS
		if(isset($_SESSION['temp_storage']['accessible_functions_tooltips']) && is_array($_SESSION['temp_storage']['accessible_functions_tooltips'])){
			return $_SESSION['temp_storage']['accessible_functions_tooltips'];
		}else{
			return array();
		}
	}

	function get_paper_size(){
		//RETURN ARRAY OF PAPER SIZE
		return array(
			'a4' => 'A4',
			'a0' => 'A0',
			'a1' => 'A1',
			'a2' => 'A2',
			'a3' => 'A3',
			'a5' => 'A5',
			'a6' => 'A6',
			'a7' => 'A7',
			'a8' => 'A8',
			'a9' => 'A9',
			'a10' => 'A10',
			'b0' => 'B0',
			'b1' => 'B1',
			'b2' => 'B2',
			'b3' => 'B3',
			'b4' => 'B4',
			'b5' => 'B5',
			'b6' => 'B6',
			'b7' => 'B7',
			'b8' => 'B8',
			'b9' => 'B9',
			'b10' => 'B10',
			'c0' => 'C0',
			'c1' => 'C1',
			'c2' => 'C2',
			'c3' => 'C3',
			'c4' => 'C4',
			'c5' => 'C5',
			'c6' => 'C6',
			'c7' => 'C7',
			'c8' => 'C8',
			'c9' => 'C9',
			'c10' => 'C10',
			'ra0' => 'RA0',
			'ra1' => 'RA1',
			'ra2' => 'RA2',
			'ra3' => 'RA3',
			'ra4' => 'RA4',
			'sra0' => 'SRA0',
			'sra1' => 'SRA1',
			'sra2' => 'SRA2',
			'sra3' => 'SRA3',
			'sra4' => 'SRA4',
			'letter' => 'LETTER',
			'legal' => 'LEGAL',
			'ledger' => 'LEDGER',
			'tabloid' => 'TABLOID',
			'executive' => 'EXECUTIVE',
			'folio' => 'FOLIO',
			/*
			'commercial #10 envelope' => 'COMMERCIAL #10 ENVELOPE',
			'catalog #10 1/2 envelope' => 'CATALOG #10 1/2 ENVELOPE',
			*/
			'8.5x11' => '8.5x11',
			'8.5x14' => '8.5x14',
			'11x17' => '11x17',
		);
	}

	function get_orientation(){
		//RETURN ARRAY OF PAPER ORIENTATION
		return array(
			'portrait' => 'Portrait',
			'landscape' => 'Landscape',
		);
	}

	function get_report_css_styling(){
		//RETURN ARRAY OF CSS STYLE SHEET FOR REPORT
		return array(
			'pdf-report-plain' => 'Plain No Borders',
			'pdf-report' => 'Plain with Borders',
			'pdf-report-grid' => 'Show Grids',
		);
	}
	
	//Returns an array of all countries
	function get_countries(){
		$cache_key = 'country_list';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key,
		) );
		
		$country = array();
		
		if( $cached_values && is_array( $cached_values ) ){
			
			foreach( $cached_values as $id => $val ){
				$country[ $id ] = $val['country'];
			}
			$country["1157"] = $country["1157"];
			asort( $country );
			
		}
		return $country;
	}
	
	function get_app_modules(){
		$cache_key = 'modules';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key,
		) );
		
		$country = array();
		
		if( $cached_values && is_array( $cached_values ) ){
			
			foreach( $cached_values as $id => $val ){
				$country[ $id ] = $val['module_name'];
			}
			asort( $country );
			
		}
		return $country;
	}
	
	function get_modules_in_application(){
		return get_app_modules();
	}
	
	function get_accessible_functions(){
		$cache_key = 'functions';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key."-all-functions",
			'directory_name' => $cache_key,
		) );
		
		$country = array();
		
		if( $cached_values && is_array( $cached_values ) ){
			$country = $cached_values;
			asort( $country );
		}
		return $country;
	}
	
	function get_functions_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'functions';
			return get_from_cached( array(
				'cache_key' => $cache_key."-".$settings['id'],
				'directory_name' => $cache_key,
			) );
			
		}
	}
	
	function get_access_roles(){
		$cache_key = 'access_roles';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key."-all-access-roles",
			'directory_name' => $cache_key,
		) );
		
		$country = array();
		
		if( $cached_values && is_array( $cached_values ) ){
			$country = $cached_values;
			asort( $country );
		}
		return $country;
	}
	
	//Returns an array of all countries
	function get_access_roles_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'access_roles';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key."-".$settings['id'],
				'directory_name' => $cache_key,
			) );
			
			if( isset( $cached_values ) && $cached_values ){
				return $cached_values;
			}
		}
	}
	
	function get_countries_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'country_list';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key,
			) );
			
			if( isset( $cached_values[ $settings['id'] ] ) && $cached_values[ $settings['id'] ] ){
				return $cached_values[ $settings['id'] ];
			}
		}
	}
	
	//Returns an array of all countries
	function get_countries_data(){
        $cache_key = 'country_list';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        
        if( isset( $cached_values ) && $cached_values ){
            return $cached_values;
        }
        
		return array();
	}
	
	//Returns an array of all states in a country
	function get_state_details( $settings = array() ){
		if( isset( $settings['country_id'] ) && $settings['country_id'] && isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'state_list';
			$cached_values = get_from_cached( array(
				'data' => $settings['country_id'],
				'cache_key' => $cache_key . '-' . $settings['country_id'],
				'directory_name' => $cache_key,
			) );
            
			if( isset( $cached_values[ $settings['state_id'] ] ) && $cached_values[ $settings['state_id'] ] ){
				return $cached_values[ $settings['state_id'] ];
			}
		}
	}
	
    function get_currency(){
		$currency = array(
			//'ngn' => '&#8358;',
			'ngn' => 'NGN',
			'cfa' => 'CFA',
			//'cedi' => 'Cedi',
			'usd' => 'USD',
		);
		return $currency;
	}
    
    function get_currency_display(){
		$currency = array(
			'ngn' => '&#8358;',
			//'ngn' => 'NGN',
			'cfa' => 'CFA',
			//'cedi' => 'Cedi',
			'usd' => '$',
		);
		return $currency;
	}
    
    //Returns an array of all states in a country
	function get_all_states_in_country( $settings = array() ){
		if( isset( $settings['country_id'] ) && $settings['country_id'] ){
			$cache_key = 'state_list';
			return get_from_cached( array(
				'data' => $settings['country_id'],
				'cache_key' => $cache_key . '-' . $settings['country_id'],
				'directory_name' => $cache_key,
			) );
		}
	}
	
	function get_all_states_in_nigeria(){
		return get_states_in_country( array( 'country_id' => "1157" ) );
	}
	
	function get_city_details( $settings = array() ){
		if( isset( $settings['city_id'] ) && $settings['city_id'] && isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'cities_list';
			$cached_values = get_from_cached( array(
				'data' => $settings['state_id'],
                'cache_key' => $cache_key . '-' . $settings['state_id'],
				'directory_name' => $cache_key,
			) );
			
			if( isset( $cached_values[ $settings['city_id'] ] ) && $cached_values[ $settings['city_id'] ] ){
				return $cached_values[ $settings['city_id'] ];
			}
		}
	}
	
	function get_all_cities_in_state( $settings = array() ){
		if( isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'cities_list';
			return get_from_cached( array(
				'data' => $settings['state_id'],
                'cache_key' => $cache_key . '-' . $settings['state_id'],
				'directory_name' => $cache_key,
			) );
			
		}
	}
	
	function get_state_name( $settings = array() ){
        $state = get_state_details( $settings );
		
        if( isset( $state['state'] ) ){
            return $state['state'];
        }
        
        return $settings['state_id'];
	}
	
	function get_city_name( $settings = array() ){
        $state = get_city_details( $settings );
		
        if( isset( $state['city'] ) ){
            return $state['city'];
        }
        
        return $settings['city_id'];
	}
	
	//Returns an array of all states in a country
	function get_states_in_country( $settings = array() ){
		if( isset( $_SESSION['temp_storage']['selected_country_id'] ) && $_SESSION['temp_storage']['selected_country_id'] ){
            $settings['country_id'] = $_SESSION['temp_storage']['selected_country_id'];
        }
        $return = array();
        if( isset( $settings['country_id'] ) && $settings['country_id'] ){
			$cache_key = 'state_list';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['country_id'],
				'directory_name' => $cache_key,
			) );
            
            if( is_array($cached_values) ){
                foreach( $cached_values as $id => $val ){
                    if( isset( $val['state'] ) )
                        $return[ $id ] = $val['state'];
                }
            }
			
		}
        return $return;
	}
	
	//Returns an array of all states in a country
	function get_cities_in_state( $settings = array() ){
		if( isset( $_SESSION['temp_storage']['selected_state_id'] ) && $_SESSION['temp_storage']['selected_state_id'] ){
            $settings['state_id'] = $_SESSION['temp_storage']['selected_state_id'];
        }
        $return = array();
        if( isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'cities_list';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['state_id'],
				'directory_name' => $cache_key,
			) );
            
            if( is_array($cached_values) ){
                foreach( $cached_values as $id => $val ){
                    if( isset( $val['city'] ) )
                        $return[ $id ] = $val['city'];
                }
            }
		}
        return $return;
	}
    
	function get_cities_in_state_pay_on_delivery( $settings = array() ){
        return get_cities_in_state( $settings );
    }
    
	//Returns an array of all countries
	function get_countries_general_settings(){
		
		$country = get_countries();
		$country['default'] = '-default-';
		asort($country);
		return $country;
	}
	
	//Returns an array of all countries
	function get_active_inactive(){
		return array(
			'active' => 'Active',
			'in_active' => 'In Active',
		);
	}
	
	function get_states(){
		//RETURN ARRAY OF STATES IN THE FEDERATION
		$states = array(
			1 => 'Abia',
			10 => 'Adamawa',
			20 => 'Akwa Ibom',
			30 => 'Anambra',
			40 => 'Bauchi',
			50 => 'Bayelsa',
			60 => 'Benue',
			70 => 'Borno',
			80 => 'Cross River',
			90 => 'Delta',
			100 => 'Ebonyi',
			110 => 'Edo',
			120 => 'Ekiti',
			130 => 'Enugu',
			140 => 'Federal Capital Territory',
			145 => 'Gombe',
			150 => 'Imo',
			160 => 'Kaduna',
			170 => 'Kano',
			180 => 'Katsina',
			190 => 'Kogi',
			200 => 'Kwara',
			210 => 'Lagos',
			220 => 'Nassarawa',
			230 => 'Niger',
			240 => 'Ogun',
			250 => 'Ondo',
			260 => 'Osun',
			270 => 'Oyo',
			280 => 'Plateau',
			290 => 'Rivers',
			300 => 'Sokoto',
			310 => 'Taraba',
			320 => 'Jigawa',
			330 => 'Yobe',
			340 => 'Zamfara',
		);
		return $states;
	}

	function get_audit_trail_logs(){
		//RETURN ARRAY OF AUDIT TRAILS
		$pagepointer = '';
		if(isset($_SESSION['temp_storage']['pagepointer']) &&  $_SESSION['temp_storage']['pagepointer']){
			$pagepointer = $_SESSION['temp_storage']['pagepointer'];
		}
		
		$oldurl = 'tmp/audit_logs/';
		
		$array_to_return = array();
		
		if(is_dir($pagepointer.$oldurl)){
			//3. Open & Read all files in directory
			$cdir = opendir($pagepointer.$oldurl);
			while($cfile = readdir($cdir)){
				if(!($cfile=='.' || $cfile=='..')){
					//Check if report exists
					$get_name = explode('.',$cfile);
					if(isset($get_name[0]) && $get_name[0]){
						$array_to_return[$get_name[0]] = date('j-M-Y',($get_name[0]/1)).' Log';
					}
				}
			}
			closedir($cdir);
		}
		
		return $array_to_return;
	}
    
	//Returns an array of all states in a country
	function get_users_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'site_users';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_users( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'users';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_employees( $settings = array() ){
		$key = 'all-users';
        $cache_key = 'users';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) )return $cached_values;
		
        return $return;
	}
	
	function get_employees_with_designation( $settings = array() ){
		$key = 'all-users-info';
        $cache_key = 'users';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) && ! empty( $cached_values ) ){
			foreach( $cached_values as $a ){
				$return[ $a["id"] ] = '<strong>'.$a['lastname'] . ' '. $a['firstname'] . "</strong> <br />[ " . $a['ref_no'] ." ]";
				
				//$return[ $a["id"] ] = '<strong>'.$a['lastname'] . ' '. $a['firstname'] . "</strong> <br />REF NO: ".$a['ref_no']."<br /> [" . get_select_option_value( array( "id" => $a["department"], "function_name" => "get_departments" ) )."]";
			}	
		}
        
        return $return;
	}
	
	function get_employees_with_ref( $settings = array() ){
		$key = 'all-users-info';
        $cache_key = 'users';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) && ! empty( $cached_values ) ){
			foreach( $cached_values as $a ){
				$return[ $a["id"] ] = $a['lastname'] . ' '. $a['firstname'] . " ( ".$a['ref_no']." )";
			}	
		}
        
        return $return;
	}
	
	function get_employees_with_names( $settings = array() ){
		$key = 'all-users-info';
        $cache_key = 'users';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) && ! empty( $cached_values ) ){
			foreach( $cached_values as $a ){
				$return[ $a["id"] ] = $a['lastname'] . ' '. $a['firstname'];
			}	
		}
        
        return $return;
	}
	
	function get_all_employees_info(){
		$key = 'all-users-info';
        $cache_key = 'users';
        return get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
	}
	
    function enquiry_processing_status(){
        //RETURN ARRAY OF ORDER STATUS
        return array(
            '1' => 'open ticket',
            '2' => 'processing',
            '3' => 'resolved ticket',
        );
    }
    
    function get_add_to_budget_line_items_options(){
        //RETURN ARRAY OF ORDER STATUS
        return array(
            'budget_details' => 'Annual Budget',
            'cash_calls' => 'Cash Calls',
            'performance_returns' => 'Performance Returns',
        );
    }
    
	//Returns an array of all states in a country
	function get_all_users_countries(){
		$cache_key = 'site_users'.'-all-users-countries';
        return get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
	}
	function get_divisions(){
		$cache_key = 'division';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
    
	function get_job_roles(){
		$cache_key = 'job_roles';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_branch_offices(){
		$cache_key = 'branch_offices';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_units(){
		$cache_key = 'units';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_departments(){
		$cache_key = 'departments';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
    function get_station_nigeria(){
        $province = array(
            1 => 'Aba',
            2 => 'Umuahia',
            10 => 'Yola',
            20 => 'Uyo',
            21 => 'Eket',
            30 => 'Nnewi',
            31 => 'Onitsha',
            40 => 'Bauchi',
            50 => 'Yenagoa',
            60 => 'Makurdi',
            70 => 'Maiduguri',
            80 => 'Calabar',
            90 => 'Asaba',
            91 => 'Warri',
            92 => 'Sapele',
            100 => 'Abakaliki',
            110 => 'Benin',
            120 => 'Ado Ekiti',
            130 => 'Enugu',
            131 => 'Nsukka',
            140 => 'Abuja',
            141 => 'Gwagwalada',
            150 => 'Owerri',
            160 => 'Kaduna',
            161 => 'Zaria',
            170 => 'Kano',
            180 => 'Katsina',
            190 => 'Lokoja',
            200 => 'Ilorin',
            210 => 'Lagos',
            220 => 'Lafia',
            230 => 'Minna',
            240 => 'Abeokuta',
            241 => 'Ijebu Ode',
            250 => 'Akure',
            260 => 'Oshogbo',
            261 => 'Ife',
            270 => 'Ibadan',
            280 => 'Jos',
            290 => 'Port Harcourt',
            291 => 'Bonny',
            300 => 'Sokoto',
            310 => 'Jalingo',
            320 => 'Gusau',
        );
        
        return $province;
    }
    
    function get_website_menu_items( $settings ){
        $cache_key = 'website_menu';
        $cached = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        
        $returned = array();
        
        foreach( $settings as $set ){
            if( isset( $cached[ $set ] ) ){
                $returned[ $set ] = $cached[ $set ];
            }
        }
        
        return $returned;
    }
    
	function get_languages(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'US' => 'English',
			'FR' => 'French',
			'SA' => 'Arabic',
			'ZA' => 'Afrikanas',
		);
	}
	
	function get_active_batch_number(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		
		$cache_key = 'batch_number-active-batch';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => 'batch_number',
        ) );
		if( isset( $return["batch_number"] ) )
			return $return["batch_number"];
		
		return -1;
	}
	
	function get_divisional_reports_item_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'divisional_reports';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_divisional_reports(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		
		$cache_key = 'divisional_reports-all';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => 'divisional_reports',
        ) );
		if( is_array( $return ) ){
			ksort( $return );
			return $return;
		}
		return array();
	}
	
	function get_divisional_reports_table_of_contents(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		
		$cache_key = 'divisional_reports_table_of_content-all';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => 'divisional_reports_table_of_content',
        ) );
		if( is_array( $return ) ){
			$return['none'] = '0. No Parent';
			asort( $return );
			return $return;
		}
		return array( 'none' => '0. No Parent' );
	}
	
	function get_divisional_reports_table_of_contents_with_data(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		
		$cache_key = 'divisional_reports_table_of_content-all-with-data';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => 'divisional_reports_table_of_content',
        ) );
		if( is_array( $return ) ){
			return $return;
		}
		return array();
	}
	
	function get_import_template_types(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'operator-budget' => 'Operator Proposed Budget',
			'operator-cash-calls' => 'Operator Monthly Cash Calls',
			'operator-performance-returns' => 'Operator Monthly Performance Returns',
			
			'napims-budget' => 'NAPIMS Budget',
			'napims-cash-calls' => 'NAPIMS Monthly Cash Calls',
			'napims-performance-returns' => 'NAPIMS Monthly Performance Returns',
			
			'realigned-budget' => 'Realigned Budget',
		);
	}
	
	function get_columns_from_excel_raw_data(){
		$return = array();
		for( $x = 1; $x < 41; ++$x ){
			if( $x < 10 )$key = '0'.$x;
			else $key = $x;
			
			$return[ 'cash_calls_raw_data_import0'.$key ] = $x;
		}
		return $return;
	}
	
	include "package/".HYELLA_PACKAGE."/Options_for_form_elements.php";
	
	function get_call_categories(){
		return array(
			'follow_up' => 'Follow Up',
			'new_lead' => 'New Lead',
		);
	}
	
	function get_repairs_status(){
		return array(
			'item_collected' => 'Collected From Customer',
			'sent_to_vendor' => 'Sent to Vendor',
			'received_from_vendor' => 'Received from Vendor',
			'return_to_customer' => 'Returned to Customer',
		);
	}
	
	function get_expenditure_status(){
		if( get_purchase_order_settings() ){
			return array(
				'stocked' => 'Goods Received Note',
				'pending' => 'Supplier Invoice',
				'draft' => 'Purchase Order',
			);
		}
		return array(
			'stocked' => 'Received & Stocked',
			'pending' => 'Pending Receipt of Items',
			'draft' => 'Draft',
		);
	}
	
	function get_currencies(){
		return array(
			'usd' => '$',
			'eur' => '&euro;',
			'gbp' => '&pound;',
			'ngn' => '&#8358;',
		);
	}
	
	function get_grade_levels_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'grade_level';
			$return = get_from_cached( array(
				'cache_key' => $cache_key,
				'directory_name' => $cache_key,
			) );
			if( isset( $return[ $settings['id'] ] ) )
				return $return[ $settings['id'] ];
		}
	}
	
	function get_grade_levels(){
		$cache_key = 'grade_level';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_assets_category_all(){
		$cache_key = 'assets_category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        return $return;
	}
	
	function get_assets_category(){
		$cache_key = 'assets_category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_type_of_depreciation(){
		return array(
			"none" => "No Depreciation",
		);
	}
	
	function get_computation_methods(){
		return array(
			"straight" => "Straight Line Method",
			"linear" => "Linear Method",
		);
	}
	
	function get_time_methods(){
		return array(
			"no_of_depreciation" => "Number of Depreciation",
			"ending_date" => "Ending Date",
		);
	}
	
	/***********Accounting************/
	function get_financial_accounting_reports(){
		return array(
			"general_ledger" => "General Ledger",
			"income_statement" => "Income Statement",
			"trial_balance" => "Trial Balance",
			"balance_sheet" => "Balance Sheet",
			"" => "",
			"general_ledger_summary" => "General Ledger Summary",
			"income_statement_summary" => "Income Statement Summary",
			"trial_balance_summary" => "Trial Balance Summary",
			
			"flood_sheet" => "Flood Sheet",
			"flood_sheet_summary" => "Flood Sheet Summary",
		);
	}
	
	function get_customers_financial_accounting_reports(){
		return array(
			"customers_transactions" => "Accounts Receivable",
			"customers_transactions_summary" => "Accounts Receivable Summary",
			"" => "",
			"customers_owing" => "Customers Owing",
			"customers_owing_summary" => "Customers Owing Summary",
		);
	}
	
	function get_cash_book_financial_accounting_reports(){
		return array(
			"cash_book_transactions" => "Cash Book",
			"cash_book_transactions_summary" => "Cash Book Summary",
		);
	}
	
	function get_vendors_financial_accounting_reports(){
		return array(
			"vendors_transactions" => "Accounts Payable",
			"vendors_transactions_summary" => "Accounts Payable Summary",
		);
	}
	
	function get_types_of_account(){
		return array(
			"asset" => "Current Asset",
			"other_asset" => "Long-term Asset",
			"fixed_asset" => "Fixed Asset",
			
			"liabilities" => "Current Liabilities",
			"other_liabilities" => "Long Term Liabilities",
			
			"equity" => "Equity",
			"revenue" => "Other Income",
			"revenue_category" => "Sales Revenue",
			"expenses" => "Operating Expenses",
			"cost_of_goods_sold" => "Cost of Sales",
		);
	}
	
	function get_transaction_status(){
		return array(
			"draft" => "Draft",
			"submitted" => "Submitted",
			"approved" => "Approved",
		);
	}
	
	function get_transaction_type(){
		return array(
			"debit" => "Debit",
			"credit" => "Credit",
		);
	}
	
	function get_first_level_accounts(){
		$return = get_account_children( array( "id" => "0", "parent" => "parent1" ) );
		$return["0"] = "-None-";
		asort( $return );
		return $return;
	}
	
	function get_account_children( $settings = array() ){
		if( isset( $settings["id"] ) && isset( $settings["parent"] ) && $settings["parent"] ){
			$cache_key = 'chart_of_accounts';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings["parent"] . '-' . $settings["id"],
				'directory_name' => $cache_key,
			) );
			$return = $cached_values;
		}
		return $return;
	}
	
	function get_second_level_accounts(){
		$return["0"] = "-None-";
		asort( $return );
		return $return;
	}
	
	function get_chart_of_accounts_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'chart_of_accounts';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_debit_and_credit_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			
			if( isset( $settings['draft'] ) && $settings['draft'] ){
				$cache_key = 'debit_and_credit_draft';
				$cached_values = get_from_cached( array(
					'cache_key' => $cache_key.'-debit_and_credit_draft-'.$settings['id'],
					'directory_name' => $cache_key,
				) );
				return $cached_values;
			}
			
			$cache_key = 'debit_and_credit';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-debit_and_credit-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_cash_book_accounts(){
		$return = array();
		$return1 = get_account_children( array( "id" => "cash_book", "parent" => "parent1" ) );
			
		if( ! empty( $return1 ) ){
			foreach( $return1 as $key => $val ){
				if( strtolower( trim($val) ) != "bank" && strtolower( trim($val) ) != "bank ()" ){
					$return[ $key ] = $val;
				}
			}
		}
		
		return $return;
	}
	
	function get_liabilities_accounts(){
		$return = array();
		$return1 = get_account_children( array( "id" => "current_liabilities", "parent" => "parent1" ) );
		return $return1;
	}
	
?>