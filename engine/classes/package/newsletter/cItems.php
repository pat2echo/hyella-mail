<?php
	/**
	 * items Class
	 *
	 * @used in  				items Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	items
	 */

	/*
	|--------------------------------------------------------------------------
	| items Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cItems{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'items';
		
		private $associated_cache_keys = array(
			'default' => 'items',
			'extracted-line-items' => 'items_raw_data_import-line-items-data-',
			'imported-cash-call' => 'import_cash_call-',
			'line-item-details' => 'budget_line_items-',
		);
		
		public $table_fields = array(
			'category' => 'items001',
			
			'description' => 'items002',
			'image' => 'items003',
			'barcode' => 'items004',
			
			'type' => 'items005',
			'low_stock' => 'items009',
			
			'cost_price' => 'items006',
			'selling_price' => 'items007',
			'source' => 'items008',
			
			'percentage_markup' => 'items015',
			'color_of_gold' => 'items010',
			'length_of_chain' => 'items011',
			'weight_in_grams' => 'items012',
			'sub_category' => 'items013',
			'currency' => 'items014',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_generate_report' => 0,		//Determines whether or not to show delete button
				
			'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
				//'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
			
			'show_details' => 1,				//Determines whether or not to show details
			'show_serial_number' => 1,			//Determines whether or not to show serial number
			
			'show_verification_status' => 0,	//Determines whether or not to show verification status
			'show_creator' => 0,				//Determines whether or not to show record creator
			'show_modifier' => 0,				//Determines whether or not to show record modifier
			'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
		);
			
		function __construct(){
			
		}
	
		function items(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError(000017);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = '_language_initialization';
					$err->additional_details_of_error = 'no language file';
					return $err->error();
				}
			}
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'display_all_records':
				unset( $_SESSION[$this->table_name]['filter']['show_my_records'] );
				unset( $_SESSION[$this->table_name]['filter']['show_deleted_records'] );
				
				$returned_value = $this->_display_data_table();
			break;
			case 'display_deleted_records':
				$_SESSION[$this->table_name]['filter']['show_deleted_records'] = 1;
				
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'get_items':
				$returned_value = $this->_get_items();
			break;
			case 'store_extracted_line_items_data':
				$returned_value = $this->_store_extracted_line_items_data();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'new_item':
				$returned_value = $this->_new_item();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'load_image_capture':
				$returned_value = $this->_load_image_capture();
			break;
			case 'save_captured_image':
				$returned_value = $this->_save_captured_image();
			break;
			case 'view_item_details':
				$returned_value = $this->_view_item_details();
			break;
			case 'update_item':
				$returned_value = $this->_update_item();
			break;
			case 'update_items':
				$returned_value = $this->_update_items();
			break;
			case 'print_selected_items_barcodes':
				$returned_value = $this->_print_selected_items_barcodes();
			break;
			}
			
			return $returned_value;
		}
		
		private function _print_selected_items_barcodes(){
			$details = $this->_get_item_details_from_id();
			
			if( ! empty( $details ) && ! isset( $details["typ"] ) ){
				unset( $_POST["id"] );
				unset( $_POST["ids"] );
				
				$barcode = new cBarcode();
				$barcode->class_settings = $this->class_settings;
				$barcode->class_settings["barcodes"] = $details;
				$barcode->class_settings["action_to_perform"] = "display_barcode_print";
				return $barcode->barcode();
			}
			return $details;
		}
		
		private function _get_item_details_from_id(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Item';
				return $err->error();
			}
			$ids[] = $_POST["id"];
			
			if( isset( $_POST["ids"] ) && $_POST["ids"] ){
				$i = explode(":::", $_POST["ids"] );
				if( count( $i ) > 1 )
					$ids = $i;
			}
			
			$item_details = array();
			foreach( $ids as $id ){
				$i = get_items_details( array( "id" => $id ) );
				if( $i ){
					$item_details[ $id ] = $i;
				}
			}
			return $item_details;
		}
		
		private function _update_items(){
			if( isset( $this->class_settings[ 'items' ] ) && is_array( $this->class_settings[ 'items' ] ) && ! empty( $this->class_settings[ 'items' ] ) ){
				$i = $this->class_settings[ 'items' ];
				
				foreach( $i as $sval ){
					$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_fields["cost_price"]."` = ".$sval["cost_price"]." WHERE `".$this->table_name."`.`id` = '".$sval[ "item" ]."' ";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 0,
						'tables' => array( $this->table_name ),
					);
					execute_sql_query($query_settings);
					
					$this->class_settings[ 'selected_item' ] = $sval["item"];
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->_get_items();
				}
				
				return 1;
			}
		}
		
		private function _update_item(){
			
			if( ! isset( $this->class_settings["selling_price"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Selling Price';
				return $err->error();
			}
			
			if( ! isset( $this->class_settings["cost_price"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Cost Price';
				return $err->error();
			}
			
			if( ! isset( $this->class_settings["item"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Item';
				return $err->error();
			}
			
			$i = $this->class_settings;
			
			$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_fields["cost_price"]."` = ".$i["cost_price"].", `".$this->table_fields["selling_price"]."` = ".$i["selling_price"]." WHERE `".$this->table_name."`.`id` = '".$i[ "item" ]."' ";
			
			unset( $i );
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'UPDATE',
				'set_memcache' => 0,
				'tables' => array( $this->table_name ),
			);
			execute_sql_query($query_settings);
			
			$this->class_settings[ 'selected_item' ] = $this->class_settings["item"];
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->_get_items();
			
			return 1;
		}
		
		private function _view_item_details(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Item ID';
				return $err->error();
			}
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/view-item-details.php' );
			
			$t = $this->table_name;
			$this->class_settings[ 'data' ][ "labels" ] = $t();
			$this->class_settings[ 'data' ][ "fields" ] = $this->table_fields;
			$this->class_settings[ 'data' ][ "item" ] = get_items_details( array( "id" => $this->class_settings["current_record_id"] ) );
			
			$html = $this->_get_html_view();
			
			if( isset( $_GET["modal"] ) && $_GET["modal"] ){
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => "#modal-replacement-handle",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
				);
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = "Item Details";
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				//'javascript_functions' => array( 'set_function_click_event' ),
			);
		}
		
		private function _save_captured_image(){
			$return = array();
			
			if( ! ( isset( $_POST["json"] ) && $_POST["json"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Image</h4><p>Image file was not received</p>';
				return $err->error();
			}
			
			//echo $_POST["json"]; exit;
			$dir = $this->class_settings["calling_page"] . "files/" . $this->table_name;
			$dir1 = "files/" . $this->table_name;
			if( ! is_dir( $dir ) ){
				create_folder( "", "", $dir );
			}
			
			$id = date("U");
			
			$data = base64_decode( preg_replace( '#^data:image/\w+;base64,#i', '', $_POST["json"] ) );
			file_put_contents( $dir . "/" . $id . ".jpg" , $data );
			
			$err = new cError(010011);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>Successful Image Capture</h4><p>Image file has been saved</p>';
			$return = $err->error();
			
			unset( $return["html"] );
			$return["status"] = "new-status";
			$return["javascript_functions"] = array( "nwInventory.saveCapturedImage" );
			
			$return["stored_path"] = $dir1 . "/" . $id . ".jpg";
			$return["full_path"] = $this->class_settings[ 'project_data' ][ "domain_name" ] . $return["stored_path"];
			
			return $return;
		}
		
		private function _load_image_capture(){
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/image-capture.php' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				"html_replacement_selector" => "#capture-container",
				"html_replacement" => $returning_html_data,
				"status" => "new-status",
				//"javascript_functions" => array( "nwInventory.activateRecentSupplyItems" )
			);
		}
		
		private function _refresh_cache(){
			//empty permanent cache folder
			clear_cache_for_special_values_directory( array(
				"permanent" => true,
				"directory_name" => $this->table_name,
			) );
			
			unset( $this->class_settings['user_id'] );
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->class_settings[ 'current_record_id' ] = 'pass_condition';
			$this->_get_items();
		}
		
		private function _new_item(){
			$store = "";
			if( isset( $_POST["store"] ) && $_POST["store"] ){
				$store = $_POST["store"];
			}
			$post = $_POST;
			
			if( isset( $_POST['id'] ) ){
				//$_POST['id'] = "";
				$new = 1;
				
				foreach( $this->table_fields as $key => $val ){
					if( isset( $_POST[ $key ] ) ){
						$_POST[ $val ] = $_POST[ $key ];
						unset( $_POST[ $key ] );
					}
				}
				
				if( $_POST['id'] ){
					//not new mode
					$new = 0;
				}
				
				//check for items with same description & update instead
				
				$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
				$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
				$_POST[ "table" ] = $this->table_name;
				$_POST[ "processing" ] = md5(1);
				if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
					define('SKIP_USE_OF_FORM_TOKEN', 1);
				
				$return = $this->_save_changes();
				if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
					//return new item
					$item = get_items_details( array( "id" => $return['saved_record_id'] ) );
					$item["quantity"] = isset( $post["quantity"] )?$post["quantity"]:0;
					//$item["selling_price"] = 0;
					//$item["cost_price"] = 0;
					$item["source"] = "";
					$item["item"] = $item["id"];
					
					if( $new ){
						//make initial inventory entry
						$this->class_settings[ 'data' ]["item"] = $item;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/inventory-list.php' );
						$returning_html_data = $this->_get_html_view();
						
						unset( $return["html"] );
						$return["status"] = "new-status";
						
						$return["html_prepend_selector"] = "#stocked-items";
						$return["html_prepend"] = $returning_html_data;
						$return["javascript_functions"] = array( 'nwInventory.init', 'nwInventory.showRestockTabforNewItem', 'nwInventory.reClick' ) ;
						return $return;
					}
					
					$inventory = new cInventory();
					$inventory->class_settings = $this->class_settings;
					$inventory->class_settings[ 'specific_item' ] = $item["id"];
					$inventory->class_settings["action_to_perform"] = 'get_all_inventory';
					$i = $inventory->inventory();
					
					if( isset( $i[0] ) ){
						$this->class_settings[ 'data' ]["item"] = $i[0];
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/inventory-list.php' );
						$returning_html_data = $this->_get_html_view();
						
						unset( $return["html"] );
						$return["status"] = "new-status";
						
						$return["html_replace_selector"] = "#" . $item["id"] . "-container";
						$return["html_replace"] = $returning_html_data;
						$return["javascript_functions"] = array( 'nwInventory.init', 'nwInventory.emptyNewItemForEdit', 'nwInventory.reClick' ) ;
						return $return;
						
					}
					
				}
			}
			
			
			return $return;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->datatable_settings["custom_edit_button"] = $this->_get_html_view();
			
			$this->class_settings['form_heading_title'] = 'Create & Manage Items';
			$this->class_settings['form_submit_button'] = 'Save Changes &rarr;';
			
			$datatable = $this->_display_data_table();
			
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Items Manager";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			//$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new', 'recreateDataTables', 'set_function_click_event', 'update_column_view_state' ),
			);
		}
		
		private function _get_existing_itemss(){
			//RETURN CODES OF EXISTING LINE ITEMS FOR EACH MONTH
			if( ! ( isset( $this->class_settings['user_id'] ) && $this->class_settings['user_id']) )
				return array("Invalid User ID");
			
			$cache_key = $this->table_name . '-available-invoices-' . $this->class_settings['user_id'];
			
			//Cache Settings
			$settings = array(
				'cache_key' => $cache_key,
			);
			//$cache_values = get_cache_for_special_values( $settings );
			if( isset( $cache_values ) && is_array( $cache_values ) && ! empty( $cache_values ) ){
				$this->class_settings['available_line_items'] = $cache_values;
				return $cache_values;
			}
			
			$query = "SELECT `id`, `serial_num`, `".$this->table_fields['invoice_id']."` as 'invoice_id', `".$this->table_fields['item_id']."` as 'item_id', `".$this->table_fields['unit_price']."` as 'unit_price' FROM `".$this->class_settings["database_name"]."`.`".$this->table_name."`  WHERE `record_status`='1' AND `created_by` = '".$this->class_settings['user_id']."' ";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 0,
				'tables' => array( $this->table_name ),
			);
			
			$codes = array();
			$des = array();
			$des1 = array();
			$data = execute_sql_query($query_settings);
			
			foreach( $data as $sval ){
				$des[ md5( $sval['invoice_id'] . $sval['item_id'] . $sval['unit_price'] ) ] = $sval['serial_num'];
				//$des1[ md5( $sval['customer_id'] . $sval['issue_date'] . $sval['delivery_date'] . $sval['due_date'] ) ] = $sval['id'];
			}
			
			//Cache Settings
			$settings = array(
				'cache_key' => $cache_key,
				'cache_values' => $des,
				'cache_time' => 'load-time',
			);
			
			set_cache_for_special_values( $settings );
			$this->class_settings['available_line_items'] = $des;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_existing_invoices_id':	
				return $des1;
			break;
			}
			
			return $des;
		}
		
		private function _store_extracted_line_items_data(){
			//STORE DATA IN exploration_drilling TABLE
			/*---------------------------*/
			
			//for testing
			if( isset($_GET['id']) )
				$_POST['id'] = $_GET['id'];
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				
				$this->class_settings['current_record_id'] = $_POST['id'];
				
				$barcode_items = array();
				
				//RETRIEVE EXTRACTED LINE ITEMS
				$settings = array(
					'cache_key' => $this->associated_cache_keys['extracted-line-items'].$this->class_settings['current_record_id'],
					'directory_name' => $this->associated_cache_keys['extracted-line-items'],
				);
				$line_items = get_cache_for_special_values( $settings );
				
				//check for existing line items
				//$existing_line_items = $this->_get_existing_itemss();
				$existing_line_items = array();
				
				$array_of_dataset = array();
				$array_of_dataset2 = array();
				
				$new_record_id = get_new_id();
				$new_record_id_serial = 0;
				
				$ip_address = get_ip_address();
				$date = date("U");
				
				$already_has_code = array();
				$already_has_desc = array();
				
				$recent_activity_new_record_inserts = 0;
				$recent_activity_updated_records = 0;
				$recent_activity_invalid_tender = 0;
				$recent_activity_duplicate_records = 0;
				
				$previous_pre_key = "";
				
				$i = new cInventory();
				
				foreach( $line_items as $k => $v ){
					
					$dataset_to_be_inserted = array(
						'id' => $new_record_id . ++$new_record_id_serial,
						'created_role' => $this->class_settings[ 'priv_id' ],
						'created_by' => $this->class_settings[ 'user_id' ],
						'creation_date' => $date,
						'modified_by' => $this->class_settings[ 'user_id' ],
						'modification_date' => $date,
						'ip_address' => $ip_address,
						'record_status' => 1,
						//$this->table_fields["type"] => "purchased_goods",
						$this->table_fields["type"] => "consignment",
					);
					
					foreach( $v as $kv => $vv ){
						if( isset( $this->table_fields[ $kv ] ) )
							$dataset_to_be_inserted[ $this->table_fields[ $kv ] ] = $vv;
					}
					
					if( 1 == 2 && isset( $existing_line_items[ $tender_key ] ) && $existing_line_items[ $tender_key ] ){
						//update
						unset( $dataset_to_be_inserted[ 'id' ] );
						unset( $dataset_to_be_inserted[ 'created_by' ] );
						unset( $dataset_to_be_inserted[ 'creation_date' ] );
						unset( $dataset_to_be_inserted[ 'creator_role' ] );
						
						$update_conditions_to_be_inserted = array(
							'where_fields' => 'serial_num',
							'where_values' => $existing_line_items[ $tender_key ],
						);

						$array_of_dataset_update[] = $dataset_to_be_inserted;

						$array_of_update_conditions[] = $update_conditions_to_be_inserted;
						
						++$recent_activity_updated_records;
					}else{
						//new
						$array_of_dataset[] = $dataset_to_be_inserted;
						
						$dataset_to_be_inserted = array(
							'id' => $new_record_id . $new_record_id_serial,
							'created_role' => $this->class_settings[ 'priv_id' ],
							'created_by' => $this->class_settings[ 'user_id' ],
							'creation_date' => $date,
							'modified_by' => $this->class_settings[ 'user_id' ],
							'modification_date' => $date,
							'ip_address' => $ip_address,
							'record_status' => 1,
							$i->table_fields["date"] => $date,
							$i->table_fields["store"] => "",
							$i->table_fields["item"] => $new_record_id . $new_record_id_serial,
							$i->table_fields["quantity"] => 1,
							$i->table_fields["comment"] => "New Item Created ".$v["image"],
						);
						
						foreach( $v as $kv => $vv ){
							if( isset( $i->table_fields[ $kv ] ) )
								$dataset_to_be_inserted[ $i->table_fields[ $kv ] ] = $vv;
						}
						$array_of_dataset2[] = $dataset_to_be_inserted;
						
						
						++$recent_activity_new_record_inserts;
					}
					
				}
				
				$saved = 0;
				
				if( ! empty( $array_of_dataset ) ){
					
					$function_settings = array(
						'database' => $this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'],
						'table' => $this->table_name,
						'dataset' => $array_of_dataset,
					);
					
					$returned_data = insert_new_record_into_table( $function_settings );
					$saved = 1;
				}
				
				if( ! empty( $array_of_dataset2 ) ){
					
					$function_settings = array(
						'database' => $this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'],
						'table' => $i->table_name,
						'dataset' => $array_of_dataset2,
					);
					
					$returned_data = insert_new_record_into_table( $function_settings );
					$saved = 1;
				}
				
				if( ! empty( $array_of_dataset_update ) && ! empty( $array_of_update_conditions ) ){
					$function_settings = array(
						'database' => $this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'],
						'table' => $this->table_name,
						'dataset' => $array_of_dataset_update,
					);
					
					$function_settings[ 'update_conditions' ] = $array_of_update_conditions;
					
					$returned_data = insert_new_record_into_table( $function_settings );
					
					$saved = 1;
				}
				
				if( $saved ){
					//update cache
					$this->class_settings['where'] = " AND `".$this->table_name."`.`modification_date` = ".$date;
					
					//update barcode
					$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_fields["barcode"]."` = `serial_num` WHERE `".$this->table_name."`.`modification_date` = ".$date." AND `".$this->table_fields["barcode"]."` = '' ";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					execute_sql_query($query_settings);
						
					//rename pictures
					$query = "SELECT `".$this->table_fields["currency"]."` as 'currency', `".$this->table_fields["image"]."` as 'image', '1' as 'quantity', `".$this->table_fields["category"]."` as 'category', `".$this->table_fields["barcode"]."` as 'barcode', `".$this->table_fields["selling_price"]."` as 'selling_price', `id`, `serial_num` FROM `".$this->class_settings['database_name']."`.`".$this->table_name."` WHERE `".$this->table_name."`.`modification_date` = ".$date;
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 0,
						'tables' => array( $this->table_name ),
					);
					$data = execute_sql_query($query_settings);
					
					$folder = "files/import/";
					$nfolder = "files/items/";
					foreach( $data as $sval ){
						if( file_exists( $this->class_settings["calling_page"] . $folder . $sval["image"] . ".jpg" ) ){
							copy( $this->class_settings["calling_page"] . $folder . $sval["image"] . ".jpg" , $this->class_settings["calling_page"] . $nfolder . $sval["id"] . ".jpg" );
							
							//queue barcodes
							$barcode_items[ $sval["id"] ] = $sval;
							
							$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_fields["image"]."` = '" . $nfolder . $sval["id"] . ".jpg' WHERE `".$this->table_name."`.`serial_num` = ".$sval["serial_num"];
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'UPDATE',
								'set_memcache' => 0,
								'tables' => array( $this->table_name ),
							);
							execute_sql_query($query_settings);
							
						}
					}
					
				}
				
				
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Loading Extracted Excel Rows &darr;';
				$return = $err->error();
				
				$return['status'] = 'new-status';
				
				$return['err'] = $err->additional_details_of_error;
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-progress.php' );
				$this->class_settings[ 'data' ] = array(
					'import_progress' => array( 
						0 => array( 'title' => 'Started Creation of Load Queries' ),
						1 => array( 'title' => 'Successfully Created Load Queries' ),
						2 => array( 'title' => 'Started Loading Data' ),
						3 => array( 'title' => 'Successfully Loaded Data' ),
						4 => array( 'title' => 'Finished! <i class="icon-thumbs-up-alt"></i>' ),
					),
				);
				
				$return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view();
				
				$invoice_table = "barcode";
				$cache_key = $invoice_table;
				
				//save queue barcodes for print
				$settings = array(
					'cache_key' => $cache_key.'-'.$this->class_settings["current_record_id"],
					'cache_values' => $barcode_items,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				//settings for ajax request reprocessing
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-'.md5( $this->table_name );
				$return['id'] = $this->class_settings[ 'current_record_id' ];
				$return['action'] = '?action='.$invoice_table.'&todo=bulk_queue_barcode';
				//$return['javascript_functions'] = array( 'refresh_tree_view' );
				
				if( isset( $this->class_settings['where'] ) && $this->class_settings['where'] ){
					$this->class_settings['where_clause'] = $this->class_settings['where'];
				}
				$this->_get_items();
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		//BASED METHODS
		private function _get_html_view(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			return $script_compiler->script_compiler();
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Item';
				//
			break;
			}
			
			if( get_general_settings_value( array( "key" => "AUTO GENERATE BARCODE", "table" => $this->table_name ) ) ){
				$this->class_settings['hidden_records'][ $this->table_fields["barcode"] ] = 1;
			}
			
			if( get_general_settings_value( array( "key" => "HIDE CAPTURE SELLING PRICE", "table" => $this->table_name ) ) ){
				$this->class_settings['hidden_records'][ $this->table_fields["selling_price"] ] = 1;
			}
			
			if( get_general_settings_value( array( "key" => "HIDE CAPTURE COST PRICE", "table" => $this->table_name ) ) ){
				$this->class_settings['hidden_records'][ $this->table_fields["cost_price"] ] = 1;
			}
			
			$this->class_settings['form_submit_button'] = 'Save Changes &rarr;';
			
			if( ! isset( $this->class_settings['form_class'] ) )
				$this->class_settings['form_class'] = 'activate-ajax';
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
				'record_id' => isset( $returning_html_data[ 'record_id' ] )?$returning_html_data[ 'record_id' ]:"",
			);
		}
		
		private function _delete_records(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'delete_records';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'deleted-records';
			
			//$this->class_settings[ 'do_not_check_cache' ] = 1;
			//$this->_get_items();
			
			return $returning_html_data;
		}
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
			unset($_SESSION[ $this->table_name ]);
			
			$fields = array();
			$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$this->table_name."`";
			$query_settings = array(
				'database'=>$this->class_settings['database_name'],
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'DESCRIBE',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval)
					$fields[] = $sval;
			}else{
				//REPORT INVALID TABLE ERROR
				$err = new cError(000001);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'citems.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$this->datatable_settings['current_module_id'] = $this->class_settings['current_module'];
			
			$form->datatables_settings = $this->datatable_settings;
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-datatable',
			);
		}
		
		private function _save_changes(){
			$returning_html_data = array();
			
			if( isset( $_POST['id'] ) && ! $_POST['id'] ){
				if( ! ( isset( $_POST[ $this->table_fields["image"] ] ) && $_POST[ $this->table_fields["image"] ] ) ){
					$img = get_general_settings_value( array( "key" => "DEFAULT IMAGE FILE", "table" => $this->table_name ) );
					if( $img )$_POST[ $this->table_fields["image"] ] = "files/resource_library/" . $img;
				}
			}
			
			$post = $_POST;
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			if( is_array( $returning_html_data ) ){
				$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returning_html_data['status'] = 'saved-form-data';
				
				if( isset( $returning_html_data['saved_record_id'] ) && $returning_html_data['saved_record_id'] ){
					
					if( isset( $returning_html_data['new_record_created'] ) && $returning_html_data['new_record_created'] ){
						if( get_general_settings_value( array( "key" => "AUTO GENERATE BARCODE", "table" => $this->table_name ) ) ){
							//update barcode
							$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_fields["barcode"]."` = `serial_num` WHERE `".$this->table_name."`.`id` = '".$returning_html_data[ "saved_record_id" ]."' ";
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'UPDATE',
								'set_memcache' => 1,
								'tables' => array( $this->table_name ),
							);
							execute_sql_query($query_settings);
						}
					}
					
					$this->class_settings["current_record_id"] = $returning_html_data['saved_record_id'];
					$this->class_settings[ 'selected_item' ] = $this->class_settings["current_record_id"];
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$item = $this->_get_items();
					
					if( isset( $returning_html_data['new_record_created'] ) && $returning_html_data['new_record_created'] ){
						//create inventory record
						$_POST["id"] = "";
						
						$inventory = new cInventory();
						$inventory->class_settings = $this->class_settings;
						$inventory->class_settings["update_fields"] = array(
							//"quantity" => 1,
							"selling_price" => isset( $item['selling_price'] )?$item['selling_price']:"",
							"cost_price" => isset( $item['cost_price'] )?$item['cost_price']:"",
							"source" => "",
							"item" => $this->class_settings[ 'selected_item' ],
							"date" => date("Y-m-d"),
							"store" => isset( $post['store'] )?$post['store']:"",
							"staff_responsible" => $this->class_settings["user_id"],
							"status" => 'complete',
							"comment" => "New item created",
						);
						$q = 0;
						if( isset( $post["quantity"] ) && doubleval( $post["quantity"] ) ){
							$q = doubleval( $post["quantity"] );
							$inventory->class_settings["update_fields"]["quantity"] = $q;
						}
						
						$inventory->class_settings["action_to_perform"] = 'update_table_field';
						$inventory->inventory();
						
						if( $q ){
							$gsettings = get_general_settings_value_array( 
								array( 
									"key" => array(
										"RECORD RESTOCKED ITEMS AS EXPENDITURE" => 0,
										"DISABLE DIRECT RESTOCKING" => 0,
									),
									"table" => $inventory->table_name 
								)
							);
							
							if( isset( $gsettings["DISABLE DIRECT RESTOCKING"] ) && doubleval( $gsettings["DISABLE DIRECT RESTOCKING"] ) ){
								$gsettings["RECORD RESTOCKED ITEMS AS EXPENDITURE"] = 0;
							}
							
							if( $gsettings["RECORD RESTOCKED ITEMS AS EXPENDITURE"] && doubleval( $gsettings["RECORD RESTOCKED ITEMS AS EXPENDITURE"] ) ){
								//capture stocking as expenditure
								$this->class_settings["source"] = $this->table_name;
								$this->class_settings["production_id"] = $i[ "id" ];
								$this->class_settings["production"] = array(
									"staff_in_charge" => $this->class_settings['user_id'],
									"amount_due" => ( $item[ "cost_price" ] * $q ),
									"amount_paid" => ( $item[ "cost_price" ] * $q ),
									"date" => date( "Y-m-d" ),
									"vendor" => $item["source"],
									"description" => $item['description'],
									"category_of_expense" => ( $item['type'] == 'purhased_goods' )?'purchase_of_items':'purchase_of_materials',
									"store" => isset( $post['store'] )?$post['store']:"",
								);
								
								$expenditure = new cExpenditure();
								$expenditure->class_settings = $this->class_settings;
								$expenditure->class_settings["action_to_perform"] = "save_expenditure";
								$expenditure->expenditure();
							}
						}
							
						if( isset( $item["barcode"] ) && $item["barcode"] ){
							$print_barcode = get_general_settings_value( array( "key" => "PRINT BARCODE", "table" => $inventory->table_name ) );
							if( $print_barcode ){
								//generate barcode
								$barcode = new cBarcode();
								$barcode->class_settings = $this->class_settings;
								$barcode->class_settings["item"] = $item;
								$barcode->class_settings["action_to_perform"] = "generate_barcode";
								$return = $barcode->barcode();
							}
						}
						
					}
					
					unset( $this->class_settings[ 'selected_item' ] );
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_items(){
			
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key,
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			
			$returning_array = array(
				'html' => '',
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'get-product-features',
			);
			
			$where = "";
			$include_list = 1;
			if( ( isset( $this->class_settings[ 'selected_item' ] ) && $this->class_settings[ 'selected_item' ] ) ){
				$where = " AND `id` = '".$this->class_settings[ 'selected_item' ]."' ";
				$include_list = 0;
			}
			
			//CHECK WHETHER TO CHECK FOR CACHE VALUES
			if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
				
				//CHECK FOR CACHED VALUES
				
				//CHECK IF CACHE IS SET
				$cached_values = get_cache_for_special_values( $settings );
				if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
					
					return $cached_values;
				}
				
			}
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ".$where." ORDER BY `".$this->table_fields["description"]."` ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_departments = execute_sql_query($query_settings);
			
			$departments = array(
				"all" => array(),
				"grouped" => array(),
			);
			
			$grouped = array();
			
			$cs = array();
			if( isset( $ck["all"] ) )
				$cs = $ck["all"];
			
			if( is_array( $all_departments ) && ! empty( $all_departments ) ){
				
				foreach( $all_departments as $category ){
					$departments[ 'all' ][ $category[ 'id' ] ] = $category[ 'description' ];
					
					$departments[ 'grouped' ][ $category["type"] ][ $category[ 'id' ] ] = $category[ 'description' ];
					
					$grouped[ $category["type"] ][ $category[ 'id' ] ] = $category[ 'description' ];
					
					$settings = array(
						'cache_key' => $cache_key.'-'.$category[ 'id' ],
						'cache_values' => $category,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					$settings = array(
						'cache_key' => $cache_key.'-barcode-'.$category[ 'barcode' ],
						'cache_values' => $category,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				if( $include_list ){
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key."-all",
						'cache_values' => $departments[ 'all' ],
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key."-grouped",
						'cache_values' => $departments[ 'grouped' ],
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key."-grouped-category",
						'cache_values' => $grouped,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}else{
					$settings = array(
						'cache_key' => $cache_key."-all",
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$update = get_cache_for_special_values( $settings );
					if( ! is_array( $update ) ){
						$update = array();
					}
					$update[ $category[ 'id' ] ] = $category[ 'description' ];
					
					$settings = array(
						'cache_key' => $cache_key."-all",
						'cache_values' => $update,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
					
					$settings = array(
						'cache_key' => $cache_key."-grouped",
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$update = get_cache_for_special_values( $settings );
					if( ! is_array( $update ) ){
						$update = array();
					}
					$update[ $category["type"] ][ $category[ 'id' ] ] = $category[ 'description' ];
					
					$settings = array(
						'cache_key' => $cache_key."-grouped",
						'cache_values' => $update,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
					
					$settings = array(
						'cache_key' => $cache_key."-grouped-category",
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$update = get_cache_for_special_values( $settings );
					if( ! is_array( $update ) ){
						$update = array();
					}
					$update[ $category["type"] ][ $category[ 'id' ] ] = $category[ 'description' ];
					
					$settings = array(
						'cache_key' => $cache_key."-grouped-category",
						'cache_values' => $update,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				return $category;
			}
		}
		
	}
?>