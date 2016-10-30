<?php
	/**
	 * debit_and_credit Class
	 *
	 * @used in  				debit_and_credit Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	debit_and_credit
	 */

	/*
	|--------------------------------------------------------------------------
	| debit_and_credit Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cDebit_and_credit{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'debit_and_credit';
		
		private $associated_cache_keys = array(
			'debit_and_credit',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'transaction_id' => 'debit_and_credit001',
			'account' => 'debit_and_credit002',
			'amount' => 'debit_and_credit003',
			'type' => 'debit_and_credit004',
			
			'account_type' => 'debit_and_credit005',
			'extra_reference' => 'debit_and_credit006',
			'currency' => 'debit_and_credit007',
			'comment' => 'debit_and_credit008',
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
	
		function debit_and_credit(){
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
			case 'get_specific_debit_and_credit':
			case 'view_all_debit_and_credit_editable':
				$returned_value = $this->_view_all_debit_and_credit();
			break;
			case 'save_debit_and_credit':
				$returned_value = $this->_save_debit_and_credit();
			break;
			case 'delete_debit_and_credit':
				$returned_value = $this->_delete_debit_and_credit();
			break;
			case 'delete_items':
				$returned_value = $this->_delete_items();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'view_all_payments':
			case 'get_total_amount_paid_by_customer':
			case 'view_all_payments_editable':
				$returned_value = $this->_view_all_payments();
			break;
			case 'get_customer_unpaid_sales_invoices':
				$returned_value = $this->_get_customer_unpaid_sales_invoices();
			break;
			case 'get_vendor_unpaid_goods_delivery_note':
				$returned_value = $this->_get_vendor_unpaid_goods_delivery_note();
			break;
			}
			
			return $returned_value;
		}
		
		private function _get_vendor_unpaid_goods_delivery_note(){
			$error = '';
			
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				
				$expenditure = new cExpenditure();
				$expenditure->class_settings = $this->class_settings;
				//$expenditure->class_settings["action_to_perform"] = "expenditure_search_vendor_purchase_order";
				//$expenditure->class_settings["action_to_perform"] = "expenditure_search_vendor_supplier_invoice";
				$expenditure->class_settings["action_to_perform"] = "expenditure_search_vendor_goods_received_note";
				$expenditure->class_settings["return_data"] = 1;
				$return = $expenditure->expenditure();
				
				if( empty( $return ) ){
					$error = '<h4>No Record Found</h4>The selected vendor has no unpaid purchase orders';
				}else{
					$this->class_settings[ 'data' ][ "item" ] = $return;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/unpaid-goods-delivery-note.php' );
					$returning_html_data = $this->_get_html_view();
					
					return array(
						'html_replacement_selector' => '#pay-vendors-extra-reference',
						'html_replacement' => $returning_html_data,
						'method_executed' => $this->class_settings['action_to_perform'],
						'status' => 'new-status',
						'javascript_functions' => array( 'nwTransactions.reactivateVendorExtraReference' ),
					);
				}
			}else{
				$error = '<h4>Invalid Vendor</h4>Please select a Vendor';
			}
			
			
			if( $error ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = $error;
				return $err->error();
			}
		}
		
		private function _get_customer_unpaid_sales_invoices(){
			$error = '';
			
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				
				$_POST["customer"] = $_POST["id"];
				unset( $_POST["id"] );
				
				$sales = new cSales();
				$sales->class_settings = $this->class_settings;
				$sales->class_settings["action_to_perform"] = "search_sales_record_unpaid_invoices";
				$return = $sales->sales();
				
				if( empty( $return ) ){
					$error = '<h4>No Record Found</h4>The selected customer has no unpaid invoice';
				}else{
					$this->class_settings[ 'data' ][ "sales_record" ] = $return;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/unpaid-sales-invoice-list.php' );
					$returning_html_data = $this->_get_html_view();
					
					return array(
						'html_replacement_selector' => '#post-customer-payment-extra-reference',
						'html_replacement' => $returning_html_data,
						'method_executed' => $this->class_settings['action_to_perform'],
						'status' => 'new-status',
						'javascript_functions' => array( 'nwTransactions.reactivateCustomerExtraReference' ),
					);
				}
			}else{
				$error = '<h4>Invalid Customer</h4>Please select a Customer';
			}
			
			
			if( $error ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = $error;
				return $err->error();
			}
		}
		
		private function _delete_items(){
			if( ! ( isset( $this->class_settings[ 'transaction_id' ] ) ) ){
				return 0;
			}
			
			$select = " `modified_by` = '".$this->class_settings["user_id"]."', `modification_date` = '".$this->class_settings[ 'transaction_id' ]."', `record_status` = '0' ";
			$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET ".$select." where `".$this->table_fields[ "transaction_id" ]."` = '".$this->class_settings[ 'transaction_id' ]."' ";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'UPDATE',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			execute_sql_query($query_settings);
			
			$this->_clear_members_cache( $this->class_settings[ 'transaction_id' ] );
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
			$this->_get_debit_and_credit();
		}
		
		private function _save_debit_and_credit(){
			
			if( ! ( isset( $this->class_settings["debit_and_credit"] ) && is_array( $this->class_settings["debit_and_credit"] ) ) ){
				return 0;
			}
			if( ! ( isset( $this->class_settings[ 'transaction_id' ] ) ) ){
				return 0;
			}
			
			$account_receivable = 0;
			if( isset( $this->class_settings["post_account_receivables"] ) && $this->class_settings["post_account_receivables"] ){
				$account_receivable = $this->class_settings["post_account_receivables"];
				
				$sales = new cSales();
				$sales->class_settings = $this->class_settings;
			}
			
			$array_of_dataset = array();
			
			$new_record_id = get_new_id();
			$new_record_id_serial = 0;
			
			$ip_address = get_ip_address();
			$date = date("U");
			
			$cus = get_customers();
			
			$rv = __map_financial_accounts();
			$ar = $rv[ "account_receivable" ];
			$sales_tx = array();
			
			foreach( $this->class_settings["debit_and_credit"] as $k => $v ){
				$id = $new_record_id . 'W' . ++$new_record_id_serial;
				
				$dataset_to_be_inserted = array(
					'id' => $id,
					'created_role' => $this->class_settings[ 'priv_id' ],
					'created_by' => $this->class_settings[ 'user_id' ],
					'creation_date' => $date,
					'modified_by' => $this->class_settings[ 'user_id' ],
					'modification_date' => $date,
					'ip_address' => $ip_address,
					'record_status' => 1,
					$this->table_fields["transaction_id"] => $this->class_settings[ 'transaction_id' ],
					
					$this->table_fields["account"] => $v["account"],
					$this->table_fields["account_type"] => isset( $v["account_type"] )?$v["account_type"]:"",
					$this->table_fields["type"] => $v["type"],
					$this->table_fields["amount"] => $v["amount"],
					$this->table_fields["extra_reference"] => isset( $v["extra_reference"] )?$v["extra_reference"]:"",
					$this->table_fields["currency"] => isset( $v["currency"] )?$v["currency"]:"",
					$this->table_fields["comment"] => isset( $v["comment"] )?$v["comment"]:"",
				);
				
				//new
				$array_of_dataset[] = $dataset_to_be_inserted;
				
				if( $account_receivable && $v["type"] == "credit" && ( isset( $cus[ $v["account"] ] ) || $v["account"] == $ar ) ){
					$sales_tx = array(
						"id" => $id,
						"date" => date("Y-n-j"),
						"quantity" => 0,
						"discount" => 0,
						"cost" => $v["amount"],
						"amount_due" => $v["amount"],
						"amount_paid" => $v["amount"],
						"payment_method" => "others",
						"customer" => $v["account"],
						"store" => "",
						"sales_status" => "payment",
						"comment" => "Payment made by customer " . ( isset( $cus[ $v["account"] ] )?$cus[ $v["account"] ]:$v["account"] ),
						"staff_responsible" => $this->class_settings["user_id"],
						"reference" => $this->class_settings[ 'transaction_id' ],
						"reference_table" => $this->table_name,
					);
					
					$sales->class_settings["sales_id"] = $id;
					$sales->class_settings["sales"] = $sales_tx;
					
					$sales->class_settings["action_to_perform"] = 'save_sales_only';
					$sales->sales();
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
				
				$this->class_settings[ 'current_record_id' ] = $this->class_settings[ 'transaction_id' ];
				$this->class_settings[ 'do_not_check_cache' ] = 1;
				
				$this->_clear_members_cache( $this->class_settings[ 'transaction_id' ] );
				$this->_get_debit_and_credit();
				
				$saved = 1;
			}
			
			return $saved;
		}
		
		private function _delete_debit_and_credit(){
			//check for duplicate record
			
			if( ! ( isset( $_GET["month"] ) && $_GET["month"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid Production Items ID';
				return $err->error();
			}
			
			$this->class_settings[ 'current_record_id' ] = $_GET["month"];
			
			$member_id = "";
			if( isset( $_GET["budget"] ) && $_GET["budget"] )
				$member_id = $_GET["budget"];
			
			$_POST['id'] = $this->class_settings[ 'current_record_id' ];
			$_POST['mod'] = 'delete-'.md5( $this->table_name );
			
			$this->_delete_records();
			
			$return["status"] = "new-status";
			$return["html_removal"] = "#debit_and_credit-" . $this->class_settings[ 'current_record_id' ];
			
			unset( $return["html"] );
			return $return;
		}
		
		private function _save_new_debit_and_credit(){
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
				$record = $this->_get_debit_and_credit();
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/form-control-view-row.php' );
			
				$this->class_settings[ 'data' ][ 'pagepointer' ] = $this->class_settings["calling_page"];
				$this->class_settings[ 'data' ][ 'new_record' ] = 1;
				$this->class_settings[ 'data' ][ 'id' ] = $record[ 'transaction_id' ];
				$this->class_settings[ 'data' ][ 'items' ] = array( $record );
				$this->class_settings[ 'data' ][ 'no_edit' ] = 1;
				
				if( $edit_mode ){
					$return["html_replace"] = $this->_get_html_view();
					$return["html_replace_selector"] = "#debit_and_credit-".$record[ 'id' ];
				}else{							
					$return["html_prepend"] = $this->_get_html_view();
					$return["html_prepend_selector"] = "#form-control-table-debit_and_credit";
				}
				
				$return["javascript_functions"] = array( "set_function_click_event" );
				unset( $return['saved_record_id'] );
			}
			
			$return["status"] = "new-status";
			
			unset( $return["html"] );
			return $return;
		}
		
		private function _view_all_debit_and_credit(){
			
			if( ! isset( $this->class_settings["transaction_id"] ) )return 0;
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields["transaction_id"]."`='".$this->class_settings["transaction_id"]."' ";
			
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
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_specific_debit_and_credit':
				return $bills;
			break;
			case 'view_all_debit_and_credit_editable':
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "item_id":	
					case "quantity":	
					case "cost":
					break;
					case "transaction_id":
						$this->class_settings["hidden_records_css"][ $val ] = 1;
						
						if( isset( $this->class_settings[ $key ] ) )
							$this->class_settings["form_values_important"][ $val ] = $this->class_settings[ $key ];
					break;
					default:
						$this->class_settings["hidden_records"][ $val ] = 1;
					break;
					}
				}
				
				$this->class_settings[ 'form_action_todo' ] = 'save_new_debit_and_credit';
				
				$form1 = $this->_generate_new_data_capture_form();
				$form = $form1["html"];
			break;
			}
			
			$this->class_settings[ 'data' ][ 'no_edit' ] = 1;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/form-control-view.php' );
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
			
			$this->class_settings[ 'data' ]['title'] = "Manage Debits & Credits";
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
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Debit & Credit';
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
			
			if( isset( $returning_html_data['deleted_record_id'] ) && $returning_html_data['deleted_record_id'] ){
				$cache_key = $this->table_name;
				
				$returning_html_data[ 'data_table_name' ] = $this->table_name;
				//$returning_html_data[ 'reload_other_tables' ] = array( $this->table_name );
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
				
				$err->class_that_triggered_error = 'cdebit_and_credit.php';
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
					$record = $this->_get_debit_and_credit();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_debit_and_credit(){
			
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
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields["transaction_id"]."` = '".$this->class_settings[ 'current_record_id' ]."'";
			
			if( $this->class_settings[ 'current_record_id' ] == 'pass_condition' ){
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ";
			}
			
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
					
					$this->_reset_members_cache( $record );
				}
				
				return $single_data;
			}
		}
		
		private function _clear_members_cache( $id ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-debit_and_credit-'.$id,
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			clear_cache_for_special_values( $settings );
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-debit_and_credit-'.$record["transaction_id"],
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
					'cache_key' => $cache_key.'-debit_and_credit-'.$record["transaction_id"],
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