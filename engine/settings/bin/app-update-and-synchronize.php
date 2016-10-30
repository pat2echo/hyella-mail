<?php 
function get_app_id(){
	//return "kwaala";
	return get_l_key();
}
	
function get_mac_address(){	
	$key = md5("mac_address");
	if( isset( $_SESSION[ $key ] ) && $_SESSION[ $key ] )
		return $_SESSION[ $key ];
	
	$settings = array(
		'cache_key' => 'mac-address',
		'permanent' => true,
	);
	$mac = get_cache_for_special_values( $settings );
	if( $mac ){
		$_SESSION[ $key ] = $mac;
		return $mac;
	}
	
	ob_start(); // Turn on output buffering
	system('ipconfig /all'); //Execute external program to display output
	$mycom = ob_get_contents(); // Capture the output into a variable
	ob_clean(); // Clean (erase) the output buffer

	$findme = 'Physical';
	$pmac = strpos( $mycom, $findme ); // Find the position of Physical text
	$mac = substr( $mycom,( $pmac + 36 ) ,17 ); // Get Physical Address
	
	if( ! $mac ){
		$mac = md5( date("U") . rand( 1, 100000 ) );
	}
	
	$settings = array(
		'cache_key' => 'mac-address',
		'permanent' => true,
		'cache_values' => $mac,
	);
	set_cache_for_special_values( $settings );
	
	$_SESSION[ $key ] = $mac;
	return $mac;
}

function create_update_manifest( $query, $query_type, $table = "" ){
	//table & query
	$cache_key = "update-manifest";
	$settings = array(
        'cache_key' => $cache_key,
		'directory_name' => $cache_key,
		'permanent' => true,
    );
	
	$new_key = 0;
    $manifest_id = get_cache_for_special_values( $settings );
	if( is_array( $manifest_id ) && ! empty( $manifest_id ) ){
		$last_num = ( count($manifest_id) - 1 );
		if( isset( $manifest_id[ $last_num ]["size"] ) && $manifest_id[ $last_num ]["size"] < 100 ){ //200 = 120kb, 100 = 60kb
			$new_key = $manifest_id[ $last_num ]["key"];
		}else{
			unset( $last_num );
			//new key
			$new_key = 1;
		}
	}else{
		$manifest_id = array();
		$new_key = 1;
	}
	
    if( $new_key ){
		$manifest_values = array();		
		$gsettings = array(
			'cache_key' => $cache_key."-".$new_key,
			'directory_name' => $cache_key,
			'permanent' => true,
		);
			
		if( $new_key == 1 ){
			$new_key = get_new_id();
			$manifest_values[] = array(
				"id" => $new_key,
				"query" => $query,
				"query_type" => $query_type,
				"table" => $table,
				"time" => date("U"),
			);
			$gsettings['cache_key'] = $cache_key."-".$new_key;
			
		}else{
			$manifest_values = get_cache_for_special_values( $gsettings );
			if( ! is_array( $manifest_values ) )$manifest_values = array();
			
			$manifest_values[] = array(
				"id" => $new_key,
				"query" => $query,
				"query_type" => $query_type,
				"table" => $table,
				"time" => date("U"),
			);
		}
       
	    $gsettings['cache_values'] = $manifest_values;
		set_cache_for_special_values( $gsettings );
		
		if( ( isset( $last_num ) && $manifest_id[ $last_num ] ) ){
			$manifest_id[ $last_num ]["key"] = $new_key;
			$manifest_id[ $last_num ]["size"] = count( $manifest_values );
		}else{
			$manifest_id[] = array( 
				"key" => $new_key,
				"size" => count( $manifest_values ),
			);
		}
		$settings['cache_values'] = $manifest_id;
		set_cache_for_special_values( $settings );
		
    }
}

function get_update_manifest(){
	//table & query
	$cache_key = "update-manifest";
	$settings = array(
        'cache_key' => $cache_key,
		'directory_name' => $cache_key,
		'permanent' => true,
    );
	
    $manifest_id = get_cache_for_special_values( $settings );
	if( isset( $manifest_id[0]["key"] ) && $manifest_id[0]["key"] ){
		$cache_key = "update-manifest";
		$settings = array(
			'cache_key' => $cache_key."-".$manifest_id[0]["key"],
			'directory_name' => $cache_key,
			'permanent' => true,
		);
		
		return array(
			"data" => get_cache_for_special_values( $settings ),
			"key" => $manifest_id[0]["key"],
		);
	}
	return 0;
}

function check_for_update_manifest(){
	$cache_dir = get_cache_directory();
	$size = 0;
	$count = 0;
	
	if( $cache_dir ){
		$cache_key = "update-manifest";
		$dir = $cache_dir . $cache_key . "/";
		
		if( is_dir( $dir ) ){
			
			$cdir = opendir( $dir );
			while($cfile = readdir($cdir)){
				if( ! ( $cfile=='.' || $cfile=='..' || $cfile==$cache_key.'.json' ) ){
					$size += filesize( $dir . $cfile );
					++$count;
				}
			}
			closedir($cdir);
		}
	}
	
	return array(
		"size" => format_bytes( $size ),
		"count" => $count,
	);
}

function format_bytes( $size ){
	$divide = 1;
	$units = " bytes";
	if( $size > ( 1024*1024 ) ){
		$units = " Mb";
		$divide = 1024*1024;
	}else{
		if( $size > 1024 ){
			$units = " Kb";
			$divide = 1024;
		}
	}
	return format_and_convert_numbers( $size / $divide, 4 ) . $units;
}

function clear_update_manifest( $key ){
	//table & query
	$cache_key = "update-manifest";
	$settings = array(
        'cache_key' => $cache_key,
		'directory_name' => $cache_key,
		'permanent' => true,
    );
	
    $manifest_id = get_cache_for_special_values( $settings );
	$i = 0;
	foreach( $manifest_id as $i => $iv ){
		if( $iv["key"] == $key ){
			break;
		}
	}
	
	if( isset( $manifest_id[$i]["key"] ) && $manifest_id[$i]["key"] == $key ){
		$cache_key = "update-manifest";
		$gsettings = array(
			'cache_key' => $cache_key."-".$manifest_id[0]["key"],
			'directory_name' => $cache_key,
			'permanent' => true,
		);
		clear_cache_for_special_values( $gsettings );
		
		unset( $manifest_id[$i] );
		$cache_key = "update-manifest";
		$settings["cache_values"] = $manifest_id;
		set_cache_for_special_values( $settings );
	}
	return 0;
}

function create_zip($files = array(),$destination = '',$overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

function get_application_version( $pagepointer ){
	$application_version = "";
	if( is_dir( $pagepointer ) ){
		//3. Open & Read all files in directory
		$cdir = opendir( $pagepointer );
		while($cfile = readdir($cdir)){
			if(!($cfile=='.' || $cfile=='..')){
				if ( preg_match("/BUILD/", $cfile ) ) {
					$application_version = trim( str_replace( "BUILD", "", $cfile ) );
					$application_version = str_replace( ".php", "", $application_version );
					$application_version = str_replace( "-", ".", $application_version );
					break;
				}
			}
		}
		closedir($cdir);
	}
	return $application_version;
}

function remove_app_version_file( $pagepointer ){
	if( is_dir( $pagepointer ) ){
		//3. Open & Read all files in directory
		$cdir = opendir( $pagepointer );
		while($cfile = readdir($cdir)){
			if(!($cfile=='.' || $cfile=='..')){
				if ( preg_match("/BUILD/", $cfile ) ) {
					unlink( $pagepointer . $cfile );
				}
			}
		}
		closedir($cdir);
	}
}

function get_cache_directory(){
	
	if( ( isset( $_POST['app_memcache'] ) && $_POST['app_memcache'] ) ){
		$settings['set_memcache'] = $_POST['app_memcache'];
		return $settings['set_memcache']::$path . '/';
	}
	
	return 0;
}

function get_accessed_functions( $priv_id ){
	$access = array();
	
	if( isset( $priv_id ) && $priv_id ){
		
		if( $priv_id == "1300130013" ){
			return 1;
		}else{
			$functions = get_access_roles_details( array( "id" => $priv_id ) );
			if( isset( $functions[ $priv_id ]["accessible_functions"] ) ){
				$a = explode( ":::" , $functions[ $priv_id ]["accessible_functions"] );
				if( is_array( $a ) && $a ){
					foreach( $a as $k => $v ){
						$access[ $v ] = get_functions_details( array( "id" => $v ) );
					}
				}
			}
		}
	}
	return $access;	
}

function get_date_difference( $out, $in ){
	$date1 = date_create( date( "Y-n-j", $out ) );
	$date2 = date_create( date( "Y-n-j", $in ) );
	$diff = date_diff( $date1, $date2 );
	$q = $diff->format("%a");
	
	if( ! $q ){
		$q = 1;
	}
	return $q;
}

function get_inventory_cost_of_goods_settings(){
	if( defined("HYELLA_IGNORE_INVENTORY_COST_OF_GOODS") && HYELLA_IGNORE_INVENTORY_COST_OF_GOODS ){
		return 0;
	}
	return 1;
}

function get_multi_currency_settings(){
	if( defined("HYELLA_MULTI_CURRENCY") && HYELLA_MULTI_CURRENCY ){
		return 1;
	}
	return 0;
}

function get_barcode_template_settings(){
	$t = get_general_settings_value( array( "key" => "BARCODE TEMPLATE", "table" => "barcode" ) );
	if( $t )return $t;
	
	return "default";
}

function get_capture_payment_on_sales_settings(){
	$return  = get_general_settings_value( array( "key" => "DO NOT CAPTURE PAYMENT ON SALES", "table" => "sales" ) );
	if( $return )return 0;
	
	return 1;
}

function get_capture_payment_on_purchase_order_settings(){
	$return  = get_general_settings_value( array( "key" => "DO NOT CAPTURE PAYMENT ON PURCHASE ORDER", "table" => "expenditure" ) );
	if( $return )return 0;
	
	return 1;
}

function get_stock_items_on_purchase_order_settings(){
	return get_general_settings_value( array( "key" => "DO NOT STOCK ITEMS FROM PURCHASE ORDER", "table" => "expenditure" ) );
}

function get_unlimited_items_in_sales_order_settings(){
	return get_general_settings_value( array( "key" => "ALLOW UNLIMITED QUANTITY IN SALES ORDER", "table" => "sales" ) );
}

function get_use_grade_level_in_payroll_settings(){
	return get_general_settings_value( array( "key" => "ALLOW GRADE LEVEL IN PAY ROLL", "table" => "pay_row" ) );
}

function get_single_store_settings(){
	if( defined("HYELLA_SINGLE_STORE") && HYELLA_SINGLE_STORE ){
		return 1;
	}
	return 0;
}

function get_purchase_order_settings(){
	if( defined("HYELLA_TREATE_PURCHASE_ORDER_AS_SEPERATE_DOC") && HYELLA_TREATE_PURCHASE_ORDER_AS_SEPERATE_DOC ){
		return 1;
	}
	return 0;
}

function get_discount_after_tax_settings(){
	return 1;
}

function get_sales_discount_after_tax_settings(){
	return 1;
}

function get_pay_roll_posting_settings(){
	return get_general_settings_value( array( "key" => "DISABLE POSTING PAYROLL TO ACCOUNTS", "table" => "pay_row" ) );
}

function get_allow_advance_deposit_payment_settings(){
	return get_general_settings_value( array( "key" => "ALLOW ADVANCE DEPOSIT PAYMENT METHOD", "table" => "customer_deposits" ) );
}

function get_show_signature_in_invoice_settings(){
	return 1;
	return 0;
}

function get_show_signature_in_purchase_order_settings(){
	return 1;
	return 0;
}

function get_show_signature_in_stock_requisition_settings(){
	return 1;
	return 0;
}

function get_show_signature_in_picking_slip_settings(){
	return 1;
	return 0;
}

function get_number_of_picking_slip_settings(){
	return 2;
	return 3;
}

function get_disable_quantity_picked_in_sales_picking_slips_settings(){
	return 1;
	//return get_general_settings_value( array( "key" => "ALLOW UNLIMITED QUANTITY IN SALES ORDER", "table" => "sales" ) );
}
	function get_expenditure_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'expenditure';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_mask_serial_number_settings(){
		return 1;
	}
	
	function mask_serial_number( $serial, $prefix = '' ){
		if( get_mask_serial_number_settings() ){
			$digits = 5;
			if( $serial ){
				$string = strval( $serial );
				$length = strlen( $string );
				if( $digits > $length ){
					$mask = $digits - $length;
					for( $i = 0; $i < $mask; $i++ )$string = "0".$string;
				}
				return strtoupper( $prefix ) . $string;
			}
		}
		return $serial;
	}
	
	function unmask_serial_number( $serial ){
		if( get_mask_serial_number_settings() ){
			return intval( clean_numbers( $serial ) );
		}else{
			return $serial;
		}
	}
	
	function get_pension_manager(){
		return array(
			"legacy_pension" => "Legacy Pension",
			"trust_fund_pension" => "Trust Fund Pension",
		);
	}
	
	function get_salary_category(){
		return array(
			"expatriates" => "Expatriates Payroll",
			"full_time_staff" => "Staff Payroll",
			"workers_payroll" => "Workers Payroll",
			"security_workers_payroll" => "Security Workers Payroll",
			"contract_workers_payroll" => "Contract Workers Payroll",
			"special_security_payroll" => "Special Security (Night Guards)",
			"spfm_payroll" => "SPFM (Armed Guards)",
		);
	}
		
	function get_default_currency_settings(){
		$return = get_general_settings_value( array( "key" => "DEFAULT CURRENCY", "table" => "items" ) );
		if( $return )return $return;
		
		return 'usd';
	}
?>