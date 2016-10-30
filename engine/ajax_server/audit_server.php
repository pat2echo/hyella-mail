<?php
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	//INITIALIZE USER PERMISSION
	$allow_delete = 0;
	$allow_edit = 0;
	$allow_view = 0;
	$allow_verify = 0;
	
	//CONFIGURATION
	$pagepointer = '../';
	$fakepointer = '';
	require_once $fakepointer.$pagepointer."settings/Config.php";
	require_once $fakepointer.$pagepointer."settings/Setup.php";
	
	require_once $fakepointer.$pagepointer."classes/cError.php";
	
	//SET TABLE
	$getpage = explode('/',$_SERVER['SCRIPT_FILENAME']);
	$page = $getpage[sizeof($getpage)-1];

	$table = str_replace('_server.php','',$page);
	
	//GET DETAILS OF CURRENTLY LOGGED IN USER
	require_once $pagepointer."settings/Current_user_details_session_settings.php";
	
	//Set Ajax Query
	require_once $pagepointer."includes/ajax_server_table_fields.php";
	
	$arr = array();
	if( isset( $_SESSION[ $table ][ 'filter' ][ 'file' ] ) && is_array( $_SESSION[ $table ][ 'filter' ][ 'file' ] ) && ! empty( $_SESSION[ $table ][ 'filter' ][ 'file' ] ) ){
		foreach( $_SESSION[ $table ][ 'filter' ][ 'file' ] as $file ){
			if( file_exists( $pagepointer.$file ) ){
				$arr = array_merge( $arr, json_decode( file_get_contents( $pagepointer.$file ), true ) );
			}
		}
	}
	
	$user = '';
	if( isset( $_SESSION[ $table ][ 'filter' ][ 'user' ] ) && $_SESSION[ $table ][ 'filter' ][ 'user' ] ){
		$user = $_SESSION[ $table ][ 'filter' ][ 'user' ];
	}
	
	/* Data set length after filtering */
	$iFilteredTotal = count($arr);
	
	//Configure Settings for JSON dataset
	$json_settings = array(
		'show_details' => 1,		//Determine whether or not details button will be displayed
			'special_details_functions' => array(),	//Determine whether or not function will be called to display special details
			'show_details_more' => 1,				//Determine whether to show more details
			
		'show_serial_number' => 1,	//Determine whether or not to show serial number
		
		
		'special_table_formatting_visible_columns' => count($fields),	//Number of Columns Displayed in Table
		'special_table_formatting_modify_row_data' => '',	//Function that determines how row data will be modified
	);
	
	$count = 0;
	$arr1 = $arr;
	$arr = array();
	foreach( $arr1 as & $val ){
		if( $user && $user != $val["user"] ){
			unset( $val );
			continue;
		}
		
		$val["id"] = ++$count;
		$val["audit001"] = "";
		$val["audit004"] = $val["table"];
		$val["audit008"] = $val["table"];
		$val["serial_num"] = "";
		$val["modified_by"] = "";
		$val["record_status"] = "";
		$val["creator_role"] = "";
		$val["created_by"] = "";
		$val["creation_date"] = 0;
		$val["modification_date"] = 0;
		
		$arr[] = $val;
	}
	//Further Ajax Request that will be made by details button
	$future_request = '';
	
	//Set JSON datasets
	require_once $pagepointer."includes/ajax_server_json_data.php";
	
	echo json_encode( $output );
?>