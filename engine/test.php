<?php 
	
	$pagepointer = './';
    $display_pagepointer = '';
    
	require_once $pagepointer."settings/Config.php";
	require_once $pagepointer."settings/Setup.php";
	require_once $pagepointer."classes/package/hotel/cItems.php";
	
	echo date("e d-M-Y H:i");
	exit;
	
	$array_of_dataset = array();
	
	$ip_address = get_ip_address();
	$date = date("U") - ( 3600 * 6 );
	$tdate = date("U");
	
	$i = new cItems();
	$select = '';
	foreach( $i->table_fields as $key => $val ){
		if( $select )$select .= ", `".$i->table_name."`.`".$val."` as '".$key."'";
		else $select = " `".$i->table_name."`.`id`, `".$i->table_name."`.`serial_num`, `".$i->table_name."`.`".$val."` as '".$key."'";
	}
	
	$query = "SELECT ".$select." FROM `" . $database_name . "`.`".$i->table_name."` WHERE `serial_num` > 1600 ";
				/*
	UPDATE items SET items001 = '11430506528' WHERE items001 = 'CLEANING CHEMICALS';
	UPDATE items SET items001 = '11430505669' WHERE items001 = 'PAPER PRODUCTS';
	UPDATE items SET items001 = '11430504555' WHERE items001 = 'DISPENSERS';
	UPDATE items SET items001 = '11430503635' WHERE items001 = 'EQUIPMENT';
	UPDATE items SET items001 = '11430502621' WHERE items001 = 'SUNDRIES';
	UPDATE items SET items001 = '11430498136' WHERE items001 = 'Special  Surface Cleaners';
	UPDATE items SET items001 = '11430501730' WHERE items001 = 'Storage and Organizing';
	UPDATE items SET items001 = '' WHERE items001 = '';
	UPDATE items SET items001 = '' WHERE items001 = '';
	*/
	//echo $query; exit;
	$query_settings = array(
		'database' => $database_name ,
		'connect' => $database_connection ,
		'query' => $query,
		'query_type' => 'SELECT',
		'set_memcache' => 0,
		'tables' => array( $i->table_name ),
	);
	$return = execute_sql_query($query_settings);
				
	foreach( $return as $k => $v ){
		
		$store = "10173870046";/*
		switch( trim( $v["image"] ) ){
		case "house_keeping":
			$store = "11071004738";
		break;
		case "laundry":
			$store = "11070994512";
		break;
		}
		*/
		
		$dataset_to_be_inserted = array(
			'id' => $v["id"].'W1',
			'created_role' => "1300130013",
			'created_by' => "1300130013",
			'creation_date' => $date,
			'modified_by' => "1300130013",
			'modification_date' => $date,
			'ip_address' => $ip_address,
			'record_status' => 1,
			
			'inventory001' => "1471297420",	//date
			
			'inventory002' => $store, //source: warehouse
			'inventory003' => $store, //store
			
			'inventory004' => $v["id"], //item id
			'inventory005' => "1", //quantity
			'inventory006' => $v["cost_price"], //cost price
			'inventory007' => $v["selling_price"], //sp
			'inventory009' => "batch import 1 new item",
			
		);
		
		//new
		$array_of_dataset[] = $dataset_to_be_inserted;
	}		
	
	print_r( $array_of_dataset ); exit;
	
	$saved = 0;
	if( ! empty( $array_of_dataset ) ){
		
		$function_settings = array(
			'database' => $database_name,
			'connect' => $database_connection,
			'table' => "inventory",
			'dataset' => $array_of_dataset,
		);
		
		$returned_data = insert_new_record_into_table( $function_settings );
		
		$saved = 1;
	}
	echo $saved;
	exit;
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
		
	$array_of_dataset = array();
	
	$ip_address = get_ip_address();
	$date = date("U");
	$tdate = date("U");
	
	foreach( $return as $k => $v ){
		
		$dataset_to_be_inserted = array(
			'id' => $k,
			'created_role' => "1300130013",
			'created_by' => "1300130013",
			'creation_date' => $date,
			'modified_by' => "1300130013",
			'modification_date' => $date,
			'ip_address' => $ip_address,
			'record_status' => 1,
			
			'chart_of_accounts001' => "expenses",
			
			'chart_of_accounts002' => $v,
			
			'chart_of_accounts004' => "operating_expense",
			'chart_of_accounts005' => "0",
			'chart_of_accounts006' => "0",
			
		);
		
		//new
		$array_of_dataset[] = $dataset_to_be_inserted;
	}		
	
	$saved = 0;
	if( ! empty( $array_of_dataset ) ){
		
		$function_settings = array(
			'database' => $database_name,
			'connect' => $database_connection,
			'table' => "chart_of_accounts",
			'dataset' => $array_of_dataset,
		);
		
		$returned_data = insert_new_record_into_table( $function_settings );
		
		$saved = 1;
	}
		
	
?>