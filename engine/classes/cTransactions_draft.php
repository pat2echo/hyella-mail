<?php
	/**
	 * transactions_draft Class
	 *
	 * @used in  				transactions_draft Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	transactions_draft
	 */

	/*
	|--------------------------------------------------------------------------
	| transactions_draft Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cTransactions_draft{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'transactions_draft';
		
		private $associated_cache_keys = array(
			'transactions_draft',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'transactions_draft001',
			'description' => 'transactions_draft002',
			'reference' => 'transactions_draft003',
			'reference_table' => 'transactions_draft004',
			
			'debit' => 'transactions_draft005',
			'credit' => 'transactions_draft010',
			
			'status' => 'transactions_draft006',
			
			'submitted_by' => 'transactions_draft007',
			'submitted_on' => 'transactions_draft008',
			'store' => 'transactions_draft009',
			'extra_reference' => 'transactions_draft011',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 0,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 0,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				
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
	
		function transactions_draft(){
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
			case 'delete_only':
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
			case "view_invoice_app":
			case "view_invoice":
				$returned_value = $this->_view_invoice();
			break;
			case 'display_all_payables_full_view':
			case 'display_all_receivables_full_view':
			case "display_all_reports_full_view":
			case "display_all_cash_book_full_view":
				$returned_value = $this->_display_all_reports_full_view();
			break;
			case 'get_financial_reports':
				$returned_value = $this->_get_financial_reports();
			break;
			case 'refresh_transactions_draft_info':
				$returned_value = $this->_refresh_transactions_draft_info();
			break;
			case 'save_transactions_draft_and_return_receipt':
				$returned_value = $this->_save_transactions_draft_and_return_receipt();
			break;
			case 'delete_transactions_draft_manifest':
				$returned_value = $this->_delete_transactions_draft_manifest();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'post_draft_transaction':
				$returned_value = $this->_post_draft_transaction();
			break;
			case 'save_transaction_manifest':
				$returned_value = $this->_save_transaction_manifest();
			break;
			}
			
			return $returned_value;
		}
		
		private function _transform_items( $items = array(), $source_account = "" ){
			$transformed = array();
			
			foreach( $items as $mode => $i ){
				foreach( $i as $k => & $v ){
					
					$atype = "";
					$type = "";
					$rtype = "";
					
					switch( $mode ){
					case "pay-vendors":
						$atype = "account_payable";
						$type = "debit";
					break;
					case "pay-bills":
						$atype = "operating_expense";
						$type = "debit";
					break;
					case "post-customer-payment":
						$atype = "accounts_receivable";
						$type = "credit";
					break;
					case "transfer-money":
						$atype = "cash_book";
						$type = "debit";
					break;
					}
					
					if( $atype ){
						if( $type == "debit" ){
							$rtype = "credit";
						}else{
							$rtype = "debit";
						}
						
						$v["account_type"] = $atype;
						$v["type"] = $type;
						$transformed[ $k ] = $v;
						
						if( $source_account ){
							if( ! isset( $transformed[ $rtype . $source_account ] ) ){
								$transformed[ $rtype . $source_account ] = array(
									"account" => $source_account,
									"amount" => 0,
									"type" => $rtype,
									"account_type" => "cash_book",
									"currency" => "",
									"extra_reference" => "",
								);
							}
							$transformed[ $rtype . $source_account ]["amount"] += $v["amount"];
						}
					}
				}
			}
			
			return $transformed;
		}
		
		private function _preview_transaction_manifest(){
			
			if( isset( $_POST["json"]["item"] ) && is_array( $_POST["json"]["item"] ) && ! empty( $_POST["json"]["item"] ) ){
				$cart_items = $_POST["json"];
				//store in temp location
				
				$e['debit_and_credit_draft'] = $this->_transform_items( $cart_items["item"], $cart_items["source_account"] );
				
				unset( $cart_items["item"] );
				$cart_items["id"] = 'preview';
				$e["event"] = $cart_items;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/transactions_draft-manifest.php' );
				$this->class_settings[ 'data' ] = $e;
				$this->class_settings[ 'data' ]["preview"] = 1;
				
				$html = $this->_get_html_view();
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
				
				$this->class_settings[ 'data' ]["html_title"] = "Preview transactions_draft";
				
				$this->class_settings[ 'data' ]['html'] = $html;
				
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'do_not_reload_table' => 1,
					'html_prepend' => $returning_html_data,
					'html_prepend_selector' => "#dash-board-main-content-area",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ),
				);
			}
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>An Unknown Error Occured</h4>Transaction could not be saved';
			return $err->error();
		}
		
		private function _save_transaction_manifest(){
			
			if( ! ( isset( $_POST["json"]["item"] ) && is_array( $_POST["json"]["item"] ) && ! empty( $_POST["json"]["item"] ) ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Accounts To Debit / Credit</h4>Please specify accounts to debit / credit first';
				return $err->error();
			}
			
			if( ! ( isset( $_POST["json"]["description"] ) && $_POST["json"]["description"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Description of Transaction</h4>Please describe the transaction that you wish to post';
				return $err->error();
			}
			
			if( ! ( isset( $_POST["json"]["credit"] ) && isset( $_POST["json"]["debit"] ) && $_POST["json"]["debit"] == $_POST["json"]["credit"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Non-matching Credit & Debit</h4>Please ensure that the credit & debit sides of the transaction matches';
				return $err->error();
			}
			
			$cart_items = $_POST["json"];
			
			$status = "";
			if( isset( $_POST["mod"] ) && $_POST["mod"] ){
				$status = $_POST["mod"];
			}
			
			switch( $status ){
			case "preview":
				if( ! ( isset( $_POST["json"]["source_account"] ) && $_POST["json"]["source_account"] ) ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Source Account</h4>Please specify the source account';
					return $err->error();
				}
				return $this->_preview_transaction_manifest();
			break;
			case "draft":
			case "flat-draft":
			case "preview-draft":
				if( ! ( isset( $_POST["json"]["source_account"] ) && $_POST["json"]["source_account"] ) ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Source Account</h4>Please specify the source account';
					return $err->error();
				}
				$cart_items["item"] = $this->_transform_items( $cart_items["item"], $cart_items["source_account"] );
			break;
			}
			
			if( isset( $cart_items["id"] ) && $cart_items["id"] ){
				$transaction_id = $cart_items["id"];
			}else{
				$transaction_id = get_new_id();
			}
			
			$debit_and_credit_draft = new cDebit_and_credit_draft();
			$debit_and_credit_draft->class_settings = $this->class_settings;
			
			if( isset( $cart_items[ 'delete_existing' ] ) && $cart_items[ 'delete_existing' ] ){
				$query = "DELETE FROM `".$this->class_settings['database_name']."`.`".$this->table_name."` WHERE `id` = '".$cart_items[ 'delete_existing' ]."' ";
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'EXECUTE',
					'set_memcache' => 0,
					'tables' => array( $this->table_name ),
				);
				execute_sql_query($query_settings);
				
				$query = "DELETE FROM `".$this->class_settings['database_name']."`.`".$debit_and_credit_draft->table_name."` WHERE `".$debit_and_credit_draft->table_fields["transaction_id"]."` = '".$cart_items[ 'delete_existing' ]."' ";
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'EXECUTE',
					'set_memcache' => 0,
					'tables' => array( $debit_and_credit_draft->table_name ),
				);
				execute_sql_query($query_settings);
			}
			
			if( ! ( isset( $this->class_settings["do_not_post_account_receivables"] ) && $this->class_settings["do_not_post_account_receivables"] ) ){
				$debit_and_credit_draft->class_settings["post_account_receivables"] = 1;
			}
			
			$debit_and_credit_draft->class_settings["transaction_id"] = $transaction_id;
			$debit_and_credit_draft->class_settings["debit_and_credit_draft"] = $cart_items["item"];
			
			$debit_and_credit_draft->class_settings["action_to_perform"] = "save_debit_and_credit_draft";
			$result = $debit_and_credit_draft->debit_and_credit_draft();
			
			$modal = 1;
			switch( $status ){
			case "draft":
			case "flat-draft":
				$modal = 0;
			break;
			}
			
			if( $result ){
				$this->class_settings["transaction_id"] = $transaction_id;
				$this->class_settings["transactions_draft"] = $cart_items;
				//$this->class_settings["action_to_perform"] = "save_transactions_draft_and_return_receipt";
				
				if( $modal )
					$this->class_settings["action_to_perform"] = "view_invoice_app";
				else
					$this->class_settings["action_to_perform"] = "view_invoice_app1";
				
				$return = $this->_save_transactions_draft_and_return_receipt();
				
				if( isset( $return[ 'html_replacement_selector' ] ) && $return[ 'html_replacement_selector' ] )
					$return[ 'html_replacement_selector' ] = "#modal-replacement-handle";
				
				return $return;
			}
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>An Unknown Error Occured</h4>Transaction could not be saved';
			return $err->error();
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
			$this->_get_transactions_draft();
		}
		
		private function _post_draft_transaction(){
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			$this->class_settings["current_record_id"] = $_POST["id"];
			$record = $this->_get_transactions_draft();
			
			$this->class_settings[ "transactions" ] = $record;
			$this->class_settings[ "transactions" ]["date"] = date( 'Y-n-j' , doubleval( $record["date"] ) );
			$this->class_settings[ "transaction_id" ] = $this->class_settings["current_record_id"];
			
			$transaction = new cTransactions();
			$transaction->class_settings = $this->class_settings;
			$transaction->class_settings["action_to_perform"] = 'save_transactions_and_return_receipt_only';
			$tx = $transaction->transactions();
			
			if( $tx ){
				//move debit & credit
				$this->class_settings["transaction_id"] = $this->class_settings["current_record_id"];
				
				$debit_and_credit_draft = new cDebit_and_credit_draft();
				$debit_and_credit_draft->class_settings = $this->class_settings;
				$debit_and_credit_draft->class_settings["action_to_perform"] = "get_specific_debit_and_credit_draft";
				$this->class_settings["debit_and_credit"] = $debit_and_credit_draft->debit_and_credit_draft();
				
				$debit_and_credit = new cDebit_and_credit();
				$debit_and_credit->class_settings = $this->class_settings;
				$debit_and_credit->class_settings["action_to_perform"] = "save_debit_and_credit";
				$debit_and_credit->debit_and_credit();
			}
			
			$_POST["id"] = $this->class_settings["current_record_id"];
			$_POST["mod"] = "delete-".md5( $this->table_name );
			$return = $this->_delete_records();
			
			if( isset( $return['deleted_record_id'] ) && $return['deleted_record_id'] ){
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Successful Posting</h4>The Transaction has been successfully posted';
				$return = $err->error();
			}
			
			unset( $return["html"] );
			$return["status"] = "new-status";
			
			return $return;
		}
		
		private function _delete_transactions_draft_manifest(){
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			$_POST["mod"] = "delete-".md5( $this->table_name );
			$return = $this->_delete_records();
			
			if( isset( $return['deleted_record_id'] ) && $return['deleted_record_id'] ){
				$return["html_removal"] = "#" . $return['deleted_record_id'] ;
					
				$return["html_replace_selector"] = "#manifest-" . $return['deleted_record_id'];
				$return["html_replace"] = '';
			}
			
			unset( $return["html"] );
			$return["status"] = "new-status";
			
			return $return;
		}
		
		private function _save_transactions_draft_and_return_receipt(){
			
			if( ! ( isset( $this->class_settings["transactions_draft"] ) && is_array( $this->class_settings["transactions_draft"] ) ) ){
				return 0;
			}
			if( ! ( isset( $this->class_settings[ 'transaction_id' ] ) ) ){
				return 0;
			}
			
			$array_of_dataset = array();
			
			$new_record_id = $this->class_settings[ 'transaction_id' ];
			
			$ip_address = get_ip_address();
			$date = date("U");
			$tdate = date("U");
			
			$status = "draft";
			$store = "";
			
			if( isset( $this->class_settings["transactions_draft"]["store"] ) ){
				$store = $this->class_settings["transactions_draft"]["store"];
			}
			
			if( isset( $this->class_settings["transactions_draft"]["date"] ) && $this->class_settings["transactions_draft"]["date"] ){
				$tdate = convert_date_to_timestamp( $this->class_settings["transactions_draft"]["date"] );
			}
			
			$dataset_to_be_inserted = array(
				'id' => $new_record_id,
				'created_role' => $this->class_settings[ 'priv_id' ],
				'created_by' => $this->class_settings[ 'user_id' ],
				'creation_date' => $date,
				'modified_by' => $this->class_settings[ 'user_id' ],
				'modification_date' => $date,
				'ip_address' => $ip_address,
				'record_status' => 1,
				
				$this->table_fields["date"] => $tdate,
				
				$this->table_fields["description"] => $this->class_settings["transactions_draft"]["description"],
				$this->table_fields["reference_table"] => $this->class_settings["transactions_draft"]["reference_table"],
				$this->table_fields["reference"] => isset( $this->class_settings["transactions_draft"]["reference"] )?$this->class_settings["transactions_draft"]["reference"]:"",
				
				$this->table_fields["status"] => $status,
				$this->table_fields["submitted_by"] => isset( $this->class_settings["transactions_draft"]["submitted_by"] )?$this->class_settings["transactions_draft"]["submitted_by"]:$this->class_settings[ "user_id" ],
				$this->table_fields["submitted_on"] => isset( $this->class_settings["transactions_draft"]["submitted_on"] )?$this->class_settings["transactions_draft"]["submitted_on"]:$date,
				
				$this->table_fields["extra_reference"] => isset( $this->class_settings["transactions_draft"]["extra_reference"] )?$this->class_settings["transactions_draft"]["extra_reference"]:"",
				
				$this->table_fields["store"] => $store,
			);
			
			//new
			$array_of_dataset[] = $dataset_to_be_inserted;
				
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
			
			$_POST["id"] = $new_record_id;
			
			$this->class_settings["hide_buttons"] = 1;
			$return = $this->_view_invoice();
			$return["javascript_functions"][] = "nwTransactions.emptyCart";
			
			return $return;
		}
		
		private function _display_all_reports_full_view(){
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-reports-full-view' );
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'display_all_payables_full_view':
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
				
				$this->class_settings[ 'data' ][ 'report_type5' ] = get_vendors_financial_accounting_reports();
				$this->class_settings[ 'data' ][ 'selected_option5' ] = "vendors_transactions_draft";
				
				$this->class_settings[ 'data' ][ 'report_title2' ] = "Vendors";
				$this->class_settings[ 'data' ][ 'report_type2' ] = get_vendors();
				$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-vendors";
				
			break;
			case 'display_all_receivables_full_view':
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
				
				$this->class_settings[ 'data' ][ 'report_type5' ] = get_customers_financial_accounting_reports();
				$this->class_settings[ 'data' ][ 'selected_option5' ] = "customers_transactions_draft";
				
				$this->class_settings[ 'data' ][ 'report_title2' ] = "Customer";
				$this->class_settings[ 'data' ][ 'report_type2' ] = get_customers();
				$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-customers";
				
			break;
			case "display_all_cash_book_full_view":
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
				
				$this->class_settings[ 'data' ][ 'report_type5' ] = get_cash_book_financial_accounting_reports();
				$this->class_settings[ 'data' ][ 'selected_option5' ] = "cash_book_transactions_draft";
				
				$this->class_settings[ 'data' ][ 'report_title2' ] = "Cash Account";
				$this->class_settings[ 'data' ][ 'report_type2' ] = get_cash_book_accounts();
				$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-accounts";
				
			break;
			default:
				
				$this->class_settings[ 'data' ][ 'report_type' ] = get_calendar_years();
				$this->class_settings[ 'data' ][ 'selected_option' ] = date("Y");
				
				$m = get_months_of_year();
				//$m["all-months"] = "All Months";
				
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
				
				$this->class_settings[ 'data' ][ 'report_type5' ] = get_financial_accounting_reports();
				$this->class_settings[ 'data' ][ 'selected_option5' ] = "income_statement";
				
				$this->class_settings[ 'data' ][ 'report_type1' ] = $m;
				$this->class_settings[ 'data' ][ 'selected_option1' ] = "all-months";
				
				$this->class_settings[ 'data' ][ 'report_title2' ] = "Account Type";
				$this->class_settings[ 'data' ][ 'report_type2' ] = get_first_level_accounts();
				unset( $this->class_settings[ 'data' ][ 'report_type2' ]["0"] );
			break;
			}
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _view_invoice(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "view_invoice_app":
			break;
			default:
				$this->class_settings["hide_buttons"] = 1;
			break;
			}
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$this->class_settings["transaction_id"] = $this->class_settings["current_record_id"];
			$e["event"] = $this->_get_transactions_draft();
			
			$debit_and_credit_draft = new cDebit_and_credit_draft();
			$debit_and_credit_draft->class_settings = $this->class_settings;
			$debit_and_credit_draft->class_settings["action_to_perform"] = "get_specific_debit_and_credit_draft";
			$e['debit_and_credit'] = $debit_and_credit_draft->debit_and_credit_draft();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/transactions-manifest.php' );
			$this->class_settings[ 'data' ] = $e;
			$this->class_settings[ 'data' ]["backend"] = 1;
			
			if( isset( $this->class_settings["show_print_button"] ) )
				$this->class_settings[ 'data' ]["backend"] = 0;
			
			if( isset( $this->class_settings["hide_buttons"] ) )
				$this->class_settings[ 'data' ]["hide_buttons"] = 1;
		
			$html = $this->_get_html_view();
			
			switch( $this->class_settings["action_to_perform"] ){
			case "view_invoice_app":
				return array(
					'html_replacement' => $html,
					'html_replacement_selector' => "#invoice-receipt-container",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ),
				);
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			
			$this->class_settings[ 'data' ]["html_title"] = "Financial Transaction Manifest";
			
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				//'javascript_functions' => array( 'prepare_new_record_form_new' ),
			);
			
		}
		
		private function _update_table_field(){
			$applicant = array();
			$return = array();
			
			if( ! isset( $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
			$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
			$_POST[ "table" ] = $this->table_name;
			$_POST[ "processing" ] = md5(1);
			if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
				define('SKIP_USE_OF_FORM_TOKEN', 1);
			
			$push = 0;
			if( isset( $this->class_settings["update_fields"] ) && is_array( $this->class_settings["update_fields"] ) ){
				foreach( $this->class_settings["update_fields"] as $key => $val ){
					if( isset( $this->table_fields[ $key ] ) ){
						$_POST[ $this->table_fields[ $key ] ] = $val;
						$push = 1;
					}
				}
			}
			
			if( $push )
				$return = $this->_save_changes();
			
			return $return;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->datatable_settings["custom_edit_button"] = $this->_get_html_view();
			
			$_SESSION[ $this->table_name ]['order_by'] = " ORDER BY `".$this->table_name."`.`".$this->table_fields["date"]."` DESC ";
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Transactions Draft";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
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
				//$this->class_settings['form_heading_title'] = 'Modify Materials Utilized';
			break;
			default:
				if( ! isset( $this->class_settings['form_values_important'][ $this->table_fields["date"] ] ) )
					$this->class_settings['form_values_important'][ $this->table_fields["date"] ] = date("U");
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
				
				//delete sales items
				$transactions_draft_items = new cDebit_and_credit_draft();
				$transactions_draft_items->class_settings = $this->class_settings;
				$transactions_draft_items->class_settings["action_to_perform"] = 'delete_items';
				
				//delete financial accounting transactions_draft
				$d = explode(":::", $returning_html_data['deleted_record_id'] );
				
				$ref_tx = array();
				
				foreach( $d as $dd ){
					if( ! $dd )continue;
					
					$transactions_draft_items->class_settings["transaction_id"] = $dd;
					$transactions_draft_items->debit_and_credit_draft();
					
					$settings = array(
						'cache_key' => $cache_key.'-'.$dd,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$cached_values = get_cache_for_special_values( $settings );
					
				}
				
				unset( $returning_html_data[ 'html' ] );
				$returning_html_data[ 'status' ] = "new-status";
				$returning_html_data[ 'html_removals' ] = array( "#invoice-container", "#".$returning_html_data['deleted_record_id'] );
				
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
				
				$err->class_that_triggered_error = 'ctransactions_draft.php';
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
					$returning_html_data["event_details"] = $this->_get_transactions_draft();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_transactions_draft(){
			
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
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `created_by`, `modified_by`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `id` = '".$this->class_settings[ 'current_record_id' ]."'";
			
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
					
				}
				
				return $single_data;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			return 1;
		}
	}
?>