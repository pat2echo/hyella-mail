<?php
	/**
	 * sales_items Class
	 *
	 * @used in  				sales_items Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	sales_items
	 */

	/*
	|--------------------------------------------------------------------------
	| sales_items Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cSales_items{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'sales_items';
		
		private $associated_cache_keys = array(
			'sales_items',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'sales_id' => 'sales_items001',
			'item_id' => 'sales_items002',
			'cost' => 'sales_items003',
			'quantity' => 'sales_items004',
			
			'discount' => 'sales_items005',
			'discount_type' => 'sales_items006',
			
			'amount_due' => 'sales_items007',
			
			'quantity_returned' => 'sales_items008',
			'cost_price' => 'sales_items009',
			'currency' => 'sales_items010',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 0,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 0,		//Determines whether or not to show edit button
				'show_delete_button' => 0,		//Determines whether or not to show delete button
				
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
	
		function sales_items(){
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
			case 'display_datatable_view':
				$returned_value = $this->_display_datatable_view();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'display_event_note_form':
			case 'add_event_note_popup':
				$returned_value = $this->_add_event_note_popup();
			break;
			case 'save_new_event_note':
				$returned_value = $this->_save_new_event_note();
			break;
			case 'edit_sales_items':
			case 'add_event_note_form':
			case 'edit_event_note_form':
				$returned_value = $this->_add_event_note_form();
			break;
			case 'get_specific_sales_items':
			case 'view_all_sales_items':
			case 'view_all_sales_items_editable':
				$returned_value = $this->_view_all_sales_items();
			break;
			case 'delete_sales_items':
				$returned_value = $this->_delete_sales_items();
			break;
			case 'save_sales_items':
				$returned_value = $this->_save_sales_items();
			break;
			case 'save_new_sales_items':
				$returned_value = $this->_save_new_sales_items();
			break;
			case 'get_return_items':
				$returned_value = $this->_get_return_items();
			break;
			case 'update_quantities_purchased':
				$returned_value = $this->_update_quantities_purchased();
			break;
			case 'delete_all_sales_items':
				$returned_value = $this->_delete_all_sales_items();
			break;
			}
			
			return $returned_value;
		}
		
		private function _delete_all_sales_items(){
			if( ! isset( $this->class_settings["sales_id"] ) )return 0;
			
			//PREPARE FROM DATABASE
			$query = "UPDATE  `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `modification_date` = ".date("U").", `modified_by` = '".$this->class_settings["user_id"]."', `record_status`='0' where `record_status`='1' AND `".$this->table_fields["sales_id"]."`='".$this->class_settings["sales_id"]."' ";
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
		
		private function _update_quantities_purchased(){
			if( isset( $this->class_settings[ "quantities_returned" ] ) && is_array( $this->class_settings[ "quantities_returned" ] ) && ! empty( $this->class_settings[ "quantities_returned" ] ) ){
				foreach( $this->class_settings[ "quantities_returned" ] as $key => $val ){
					
					$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `".$this->table_fields["quantity_returned"]."` = `".$this->table_fields["quantity_returned"]."` + ".$val." WHERE `record_status`='1' AND `id` = '".$key."' ";
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
				
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Successful Update</h4>Quantities returned has been deducted';
				return $err->error();
			}
			
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = '<h4>Oops, an error occurred</h4>';
			return $err->error();
		}
		
		private function _get_return_items(){
			if( ! ( isset( $_POST[ "id" ] ) && $_POST[ "id" ] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No Record Found</h4>Please check your receipt number';
				return $err->error();
			}
			
			$this->class_settings[ 'sales_id' ] = $_POST[ "id" ];
			$r = $this->_view_all_sales_items();
			
			$r[ 'html_replacement_selector' ] = "#returned-item";
			return $r;
		}
		
		private function _save_new_sales_items(){
			//check for duplicate record
			$edit_mode = 0;
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$edit_mode = 1;
			}
				
			$return = $this->_save_changes();
			
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
				$html = "";
				
				unset( $this->class_settings[ 'do_not_check_cache' ] );
				$this->class_settings['current_record_id'] = $return['saved_record_id'];
				$record = $this->_get_sales_items();
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/form-control-view-row.php' );
			
				$this->class_settings[ 'data' ][ 'pagepointer' ] = $this->class_settings["calling_page"];
				$this->class_settings[ 'data' ][ 'new_record' ] = 1;
				$this->class_settings[ 'data' ][ 'id' ] = $record[ 'sales_id' ];
				$this->class_settings[ 'data' ][ 'items' ] = array( $record );
				$this->class_settings[ 'data' ][ 'no_edit' ] = 1;
				
				if( $edit_mode ){
					$return["html_replace"] = $this->_get_html_view();
					$return["html_replace_selector"] = "#sales_items-".$record[ 'id' ];
				}else{							
					$return["html_prepend"] = $this->_get_html_view();
					$return["html_prepend_selector"] = "#form-control-table-sales_items";
				}
				
				$return["javascript_functions"] = array( "set_function_click_event" );
				unset( $return['saved_record_id'] );
			}
			
			$return["status"] = "new-status";
			
			unset( $return["html"] );
			return $return;
		}
		
		private function _save_sales_items(){
			
			if( ! ( isset( $this->class_settings["sales_items"] ) && is_array( $this->class_settings["sales_items"] ) ) ){
				return 0;
			}
			if( ! ( isset( $this->class_settings[ 'sales_id' ] ) ) ){
				return 0;
			}
			
			$array_of_dataset = array();
			
			$new_record_id = get_new_id();
			$new_record_id_serial = 0;
			
			$ip_address = get_ip_address();
			$date = date("U");
			
			foreach( $this->class_settings["sales_items"] as $k => $v ){
				
				$dataset_to_be_inserted = array(
					'id' => $new_record_id . 'W' . ++$new_record_id_serial,
					'created_role' => $this->class_settings[ 'priv_id' ],
					'created_by' => $this->class_settings[ 'user_id' ],
					'creation_date' => $date,
					'modified_by' => $this->class_settings[ 'user_id' ],
					'modification_date' => $date,
					'ip_address' => $ip_address,
					'record_status' => 1,
					$this->table_fields["sales_id"] => $this->class_settings[ 'sales_id' ],
					
					$this->table_fields["item_id"] => $v["id"],
					$this->table_fields["cost"] => $v["price"],
					$this->table_fields["cost_price"] => isset( $v["cost_price"] )?$v["cost_price"]:0,
					$this->table_fields["quantity"] => $v["quantity"],
					$this->table_fields["amount_due"] => $v["total"],
					$this->table_fields["discount"] => ( isset( $v["discount"] )?$v["discount"]:0 ),
				);
				
				//new
				$array_of_dataset[] = $dataset_to_be_inserted;
				
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
			
			return $saved;
		}
		
		private function _delete_sales_items(){
			//check for duplicate record
			
			if( ! ( isset( $_GET["month"] ) && $_GET["month"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid Item ID';
				return $err->error();
			}
			
			$this->class_settings[ 'current_record_id' ] = $_GET["month"];
			
			$sales_id = "";
			if( isset( $_GET["budget"] ) && $_GET["budget"] )
				$sales_id = $_GET["budget"];
			
			$_POST['id'] = $this->class_settings[ 'current_record_id' ];
			$_POST['mod'] = 'delete-'.md5( $this->table_name );
			
			$this->_delete_records();
			$this->_reset_members_cache( array( "id" => $this->class_settings[ 'current_record_id' ], "sales_id" => $sales_id ) , 1 );
			
			$return["status"] = "new-status";
			$return["html_removal"] = "#sales_items-" . $this->class_settings[ 'current_record_id' ];
			$return["do_not_reload_table"] = 1;
			
			unset( $return["html"] );
			return $return;
		}
		
		private function _view_all_sales_items(){
			
			if( ! isset( $this->class_settings["sales_id"] ) )return 0;
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields["sales_id"]."` = '".$this->class_settings["sales_id"]."' ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$bills = execute_sql_query($query_settings);
			
			$form = "";
			$file_name = 'form-control-view.php';
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_specific_sales_items':
				return $bills;
			break;
			case 'get_return_items':
				$file_name = 'returned-items.php';
			break;
			case 'view_all_sales_items_editable':
				
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "item_id":
					case "cost":
					case "quantity":
					case "discount":
					break;
					case "sales_id":
						$this->class_settings["hidden_records_css"][ $val ] = 1;
						
						if( isset( $this->class_settings[ $key ] ) )
							$this->class_settings["form_values_important"][ $val ] = $this->class_settings[ $key ];
					break;
					default:
						$this->class_settings["hidden_records"][ $val ] = 1;
					break;
					}
				}
				
				$this->class_settings[ 'form_action_todo' ] = 'save_new_sales_items';
				
				$form1 = $this->_generate_new_data_capture_form();
				$form = $form1["html"];
			break;
			}
			$this->class_settings[ 'data' ][ 'no_edit' ] = 1;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$file_name );
			$this->class_settings[ 'data' ]['items'] = $bills;
			$this->class_settings[ 'data' ]['form'] = $form;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new', 'set_function_click_event' ),
			);
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Sold Items";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state', 'prepare_new_record_form_new' ) 
			);
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
			
			$this->class_settings["hidden_records"][ $this->table_fields['discount_type'] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields['discount']  ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields['amount_due']  ] = 1;
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Event Item';
			break;
			}
			
			if( ! isset( $this->class_settings['form_submit_button'] ) )
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
				'record_id' => $returning_html_data[ 'record_id' ],
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
			
			if( isset( $returning_html_data['deleted_record_id'] ) && $returning_html_data['deleted_record_id'] ){
				$cache_key = $this->table_name;
				
				$d = explode(":::", $returning_html_data['deleted_record_id'] );
				foreach( $d as $dd ){
					
					$settings = array(
						'cache_key' => $cache_key . '-' . $dd,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					clear_cache_for_special_values( $settings );
					
				}
					
				//delete sales items
				$returning_html_data[ 'data_table_name' ] = $this->table_name;
			}
			return $returning_html_data;
		}
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
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
				
				$err->class_that_triggered_error = 'csales_items.php';
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
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			if( is_array( $returning_html_data ) ){
				$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returning_html_data['status'] = 'saved-form-data';
				
				if( isset( $returning_html_data['saved_record_id'] ) && $returning_html_data['saved_record_id'] ){
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->class_settings['current_record_id'] = $returning_html_data['saved_record_id'];
					$record = $this->_get_sales_items();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_sales_items(){
			
			$cache_key = $this->table_name;
			
			if( ! isset( $this->class_settings[ 'current_record_id' ] ) )return 0;
			
			$settings = array(
				'cache_key' => $cache_key.'-'.$this->class_settings[ 'current_record_id' ],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			
			//CHECK WHETHER TO CHECK FOR CACHE VALUES
			if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
				
				//CHECK FOR CACHED VALUES
				$cached_values = get_cache_for_special_values( $settings );
				if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
					return $cached_values;
				}
				
			}
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `id` = '".$this->class_settings[ 'current_record_id' ]."'";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_data = execute_sql_query($query_settings);
			
			$single_data = array();
			
			if( is_array( $all_data ) && ! empty( $all_data ) ){
				
				foreach( $all_data as $record ){
					$single_data = $record;
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$record['id'],
						'cache_values' => $record,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					$this->_reset_members_cache( $record );
					//$this->class_settings["member_id"] = $record["bill_id"];
					//$this->_get_sales_items();
				}
				
				return $single_data;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-sales_items-'.$record["sales_id"],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			$members = get_cache_for_special_values( $settings );
			
			if( ! is_array( $members ) )$members = array();
			
			if( is_array( $members ) ){
				if( $clear ){
					unset( $members[ $record['id'] ] );
				}else{
					$members[ $record['id'] ] = $record;
				}
				
				$settings = array(
					'cache_key' => $cache_key.'-sales_items-'.$record["sales_id"],
					'directory_name' => $cache_key,
					'cache_values' => $members,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				return $members;
			}
		}
	}
?>