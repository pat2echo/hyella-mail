<?php
	/**
	 * payment Class
	 *
	 * @used in  				payment Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	payment
	 */

	/*
	|--------------------------------------------------------------------------
	| payment Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cPayment{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'payment';
		
		private $associated_cache_keys = array(
			'payment',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'sales_id' => 'payment001',
			'date' => 'payment002',
			'amount_paid' => 'payment003',
			'payment_method' => 'payment004',
			
			'staff_responsible' => 'payment005',
			
			'comment' => 'payment006',
			'reference_table' => 'payment007',
			
			'customer' => 'payment008',
			'store' => 'payment009',
			'extra_reference' => 'payment010',
			'currency' => 'payment011',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
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
	
		function payment(){
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
			case 'restock':
				$returned_value = $this->_restock();
			break;
			case 'view_all_payments':
			case 'get_total_amount_paid':
			case 'view_all_payments_editable':
			case 'get_total_amount_paid_vendor':
				$returned_value = $this->_view_all_payments();
			break;
			case 'save_new_payment':
				$returned_value = $this->_save_new_payment();
			break;
			case 'delete_payment':
				$returned_value = $this->_delete_payment();
			break;
			case 'save_payment':
				$returned_value = $this->_save_payment();
			break;
			case 'get_payment_history':
				$returned_value = $this->_get_payment_history();
			break;
			case 'delete_payment_record':
				$returned_value = $this->_delete_payment_record();
			break;
			case 'delete_all_reference_table_items':
				$returned_value = $this->_delete_all_reference_table_items();
			break;
			case 'search_all_payment_record':
				$returned_value = $this->_search_all_payment_record();
			break;
			}
			
			return $returned_value;
		}
		
		private function _search_all_payment_record(){
			$data = array();
			
			if( isset( $_POST["customer"] ) && $_POST["customer"] ){
				$this->class_settings["customer"] = $_POST["customer"];
				
				$transactions = new cTransactions();
				$transactions->class_settings = $this->class_settings;
				$transactions->class_settings[ "account_type" ] = "accounts_receivable";
				$transactions->class_settings[ "account" ] = $this->class_settings["customer"];
				$transactions->class_settings[ "action_to_perform" ] = 'generate_sales_report';
				
				$_GET["department"] = "get_all_transactions_on_account";
				
				unset( $_GET["end_date"] );
				unset( $_GET["start_date"] );
				
				if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
					$_GET["start_date"] = $_POST["start_date"];
					$this->class_settings[ 'data' ][ "start_date" ] = convert_date_to_timestamp( $_POST["start_date"] );
				}
				
				if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
					$_GET["end_date"] = $_POST["end_date"];
					$this->class_settings[ 'data' ][ "end_date" ] = convert_date_to_timestamp( $_POST["end_date"] );
				}
				
				$d = $transactions->transactions();
				if( isset( $d["report_data"] ) )
					$this->class_settings[ 'data' ][ "transaction" ] = $d["report_data"];
				
				$_GET["department"] = "get_transactions_on_account";
				$transactions->class_settings[ "action_to_perform" ] = 'generate_sales_report';
				$d = $transactions->transactions();
				if( isset( $d["report_data"] ) ){
					$this->class_settings[ 'data' ][ "recent_transaction" ] = $d["report_data"];
					$data = $d["report_data"];
				}
				
			}
			
			//$data = $this->_view_all_payments();
			
								
			if( ! empty( $data ) ){
				//$this->class_settings[ 'data' ][ "payment_record" ] = $data;
				//$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/all-payment-record.php' );
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/all-transaction-record.php' );
				
				$returning_html_data = $this->_get_html_view();
				return array(
					'html_replacement_selector' => "#payment-record-search-result",
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ),
				);
			}else{
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No Record Found</h4>Please check your customer name';
				$r = $err->error();
				
				$r["status"] = "new-status";
				$r["html_replacement_selector"] = "#payment-record-search-result";
				$r["html_replacement"] = $r["html"];
				
				unset( $r["html"] );
				
				return $r;
			}
		}
		
		private function _delete_all_reference_table_items(){
			if( ! isset( $this->class_settings["sales_query"] ) )return 0;
			
			$query = "SELECT  `id` FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_fields["sales_id"]."` IN ( ".$this->class_settings["sales_query"]." ) ";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql = execute_sql_query($query_settings);
			if( isset( $sql[0]["id"] ) ){
				$ids = array();
				foreach( $sql as $sval ){
					$ids[] = $sval["id"];
				}
				
				unset( $_POST["id"] );
				$_POST["ids"] = implode( ":::", $ids );
				$_POST["mod"] = 'delete-' . md5( $this->table_name );
				return $this->_delete_records();
			}
		}
		
		private function _delete_payment_record(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Record ID</h4>Please select a record to delete';
				return $err->error();
			}
			
			$_POST['mod'] = 'delete-'.md5( $this->table_name );
			$r = $this->_delete_records();
			
			if( isset( $r[ "deleted_record_id" ] ) && $r[ "deleted_record_id" ] ){
				
				unset( $r["html"] );
				$r["status"] = "new-status";
				$r["html_removal"] = "tbody#payment-histories tr#" . $r[ "deleted_record_id" ];
				$r["javascript_functions"] = array( "nwRecordPayment.deletedStockItemSuccess" );
				
			}
			
			return $r;
		}
		
		private function _get_payment_history(){
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$this->class_settings[ 'sales_id' ] = $_POST["id"];
				$this->class_settings[ 'action_to_perform' ] = 'get_specific_payments';
				$this->class_settings[ 'data' ]['payment_record'] = $this->_view_all_payments();
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/payment-record.php' );
				$returning_html_data = $this->_get_html_view();
				
				return array(
					"html_replacement_selector" => "#payment-history",
					"html_replacement" => $returning_html_data,
					"status" => "new-status",
					"javascript_functions" => array( "nwRecordPayment.activateRecentSupplyItems" )
				);
			}
		}
		
		private function _save_payment(){
			
			if( ! ( isset( $this->class_settings["amount_paid"] ) && $this->class_settings["amount_paid"] ) ){
				return 0;
			}
			
			if( ! ( isset( $this->class_settings[ 'sales_id' ] ) ) ){
				return 0;
			}
			$saved = 0;
			
			$this->class_settings["update_fields"] = array(
				'sales_id' => $this->class_settings[ 'sales_id' ],
				'date' => date("Y-m-d"),
				'amount_paid' => $this->class_settings["amount_paid"],
				'payment_method' => $this->class_settings['payment_method'],
				'staff_responsible' => ( isset( $this->class_settings["staff_responsible"] ) && $this->class_settings["staff_responsible"] )?$this->class_settings["staff_responsible"]:$this->class_settings['user_id'],
				'comment' => isset( $this->class_settings['comment'] )?$this->class_settings['comment']:"Payment made during sales",
				'reference_table' => isset( $this->class_settings["reference_table"] )?$this->class_settings["reference_table"]:$this->table_name,
				'store' => isset( $this->class_settings["store"] )?$this->class_settings["store"]:"",
				'customer' => isset( $this->class_settings["customer"] )?$this->class_settings["customer"]:"",
				
				'extra_reference' => isset( $this->class_settings["extra_reference"] )?$this->class_settings["extra_reference"]:"",
			);
			
			//include table for payment reason to determine account payment should be posted to
			$this->class_settings['new_record_created'] = 1;
			$this->class_settings["update_fields"][ "extra_reference" ] = $this->class_settings[ 'sales_id' ];
			$this->class_settings["update_fields"][ "date" ] = date("U");
			$this->class_settings["update_fields"][ "creation_date" ] = date("U");
			$this->class_settings["update_fields"][ "id" ] = get_new_id();
			$this->_record_in_accounting( $this->class_settings["update_fields"] );
			return 1;
			
			$_POST['id'] = "";
			$return = $this->_update_table_field();
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
				$saved = 1;
			}
				
			return $saved;
		}
		
		private function _delete_payment(){
			//check for duplicate record
			
			if( ! ( isset( $_GET["month"] ) && $_GET["month"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid Payment ID';
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
			$return["html_removal"] = "#payment-" . $this->class_settings[ 'current_record_id' ];
			
			unset( $return["html"] );
			return $return;
		}
		
		private function _save_new_payment(){
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
				$record = $this->_get_payment();
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/form-control-view-row.php' );
			
				$this->class_settings[ 'data' ][ 'pagepointer' ] = $this->class_settings["calling_page"];
				$this->class_settings[ 'data' ][ 'new_record' ] = 1;
				$this->class_settings[ 'data' ][ 'id' ] = $record[ 'sales_id' ];
				$this->class_settings[ 'data' ][ 'items' ] = array( $record );
				$this->class_settings[ 'data' ][ 'no_edit' ] = 1;
				
				if( $edit_mode ){
					$return["html_replace"] = $this->_get_html_view();
					$return["html_replace_selector"] = "#payment-".$record[ 'id' ];
				}else{							
					$return["html_prepend"] = $this->_get_html_view();
					$return["html_prepend_selector"] = "#form-control-table-payment";
				}
				
				$return["javascript_functions"] = array( "set_function_click_event" );
				unset( $return['saved_record_id'] );
			}
			
			$return["status"] = "new-status";
			
			unset( $return["html"] );
			return $return;
		}
		
		private function _view_all_payments(){
			$transactions = new cTransactions();
			$debit_and_credit = new cDebit_and_credit();
			
			$select = "";
			
			foreach( $debit_and_credit->table_fields as $key => $val ){
				if( $select )$select .= ", `".$debit_and_credit->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$debit_and_credit->table_name."`.`id`, `".$debit_and_credit->table_name."`.`serial_num`, `".$debit_and_credit->table_name."`.`creation_date`, `".$debit_and_credit->table_name."`.`modification_date`, `".$debit_and_credit->table_name."`.`".$val."` as '".$key."'";
			}
			
			$where = "";
			switch ( $this->class_settings['action_to_perform'] ){
			case 'search_all_payment_record':
				if( ! isset( $this->class_settings["customer"] ) )return 0;
				
				//$where = " AND `".$debit_and_credit->table_name."`.`".$debit_and_credit->table_fields["account_type"]."` = 'accounts_receivable' AND `".$debit_and_credit->table_name."`.`".$debit_and_credit->table_fields["account"]."` = '".$this->class_settings["customer"]."' ";
				
				$where = " AND `".$debit_and_credit->table_name."`.`".$debit_and_credit->table_fields["type"]."` = 'credit' AND `".$debit_and_credit->table_name."`.`".$debit_and_credit->table_fields["account_type"]."` = 'accounts_receivable' AND `".$debit_and_credit->table_name."`.`".$debit_and_credit->table_fields["account"]."` = '".$this->class_settings["customer"]."' ";
				
				if( isset( $this->class_settings["start_date"] ) && doubleval( $this->class_settings["start_date"] ) ){
					$where .= " AND `".$transactions->table_name."`.`".$transactions->table_fields["date"]."` >= " . $this->class_settings["start_date"];
				}
				
				if( isset( $this->class_settings["end_date"] ) && doubleval( $this->class_settings["end_date"] ) ){
					$where .= " AND `".$transactions->table_name."`.`".$transactions->table_fields["date"]."` <= " . $this->class_settings["end_date"];
				}
				
			break;
			default:
				if( ! isset( $this->class_settings["sales_id"] ) )return 0;
				
				$where = " AND `".$debit_and_credit->table_fields["extra_reference"]."` = '".$this->class_settings["sales_id"]."' ";
			break;
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_total_amount_paid':
				$where = " AND `".$debit_and_credit->table_fields["extra_reference"]."` = '".$this->class_settings["sales_id"]."' AND `".$debit_and_credit->table_fields["type"]."` = 'credit' AND `".$debit_and_credit->table_fields["account_type"]."` = 'accounts_receivable' ";
				
				$select = " SUM( `".$debit_and_credit->table_fields["amount"]."` ) as 'TOTAL_AMOUNT_PAID' ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$debit_and_credit->table_name."` where `record_status`='1' ".$where;
			break;
			case 'get_total_amount_paid_vendor':
				$where = " AND `".$debit_and_credit->table_fields["extra_reference"]."` = '".$this->class_settings["sales_id"]."' AND `".$debit_and_credit->table_fields["type"]."` = 'debit' AND `".$debit_and_credit->table_fields["account_type"]."` = 'account_payable' ";
				
				$select = " SUM( `".$debit_and_credit->table_fields["amount"]."` ) as 'TOTAL_AMOUNT_PAID' ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$debit_and_credit->table_name."` where `record_status`='1' ".$where;
			break;
			default:
				foreach( $transactions->table_fields as $key => $val ){
					switch( $key ){
					case "store":
					case "reference_table":
					case "date":
						$select .= ", `".$transactions->table_name."`.`".$val."` as '".$key."'";
					break;
					case "submitted_by":
						$select .= ", `".$transactions->table_name."`.`".$val."` as 'staff_responsible' ";
					break;
					}
				}
				
				//$where .= " AND `".$debit_and_credit->table_fields["type"]."` = 'debit' ";
				
				$select .= ", `".$debit_and_credit->table_name."`.`".$debit_and_credit->table_fields["amount"]."` as 'amount_paid' ";
				$select .= ", `".$debit_and_credit->table_name."`.`".$debit_and_credit->table_fields["extra_reference"]."` as 'sales_id' ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$debit_and_credit->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$transactions->table_name."` ON `".$transactions->table_name."`.`id` = `".$debit_and_credit->table_name."`.`".$debit_and_credit->table_fields["transaction_id"]."` WHERE `".$debit_and_credit->table_name."`.`record_status`='1' AND `".$transactions->table_name."`.`record_status`='1' ".$where . " ORDER BY `".$transactions->table_fields["date"]."` DESC ";
			break;
			}
			//echo $query; exit;
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $debit_and_credit->table_name ),
			);
			
			$bills = execute_sql_query($query_settings);
			$form = "";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_total_amount_paid':
			case 'get_total_amount_paid_vendor':
				return isset( $bills[0] )?$bills[0]:0;
			break;
			case 'search_all_payment_record':
			case 'get_specific_payments':
				return $bills;
			break;
			case 'view_all_payments_editable':
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "staff_responsible":
					case "amount_paid":
					case "comment":
					case "payment_method":
						if( isset( $this->class_settings[ $key ] ) )
							$this->class_settings["form_values_important"][ $val ] = $this->class_settings[ $key ];
					break;
					case "date":
						$this->class_settings["hidden_records_css"][ $val ] = 1;
						
						if( isset( $this->class_settings[ $key ] ) )
							$this->class_settings["form_values_important"][ $val ] = date("U");
					break;
					case "store":
					case "customer":
					case "reference_table":
					case "extra_reference":
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
				
				$this->class_settings[ 'form_action_todo' ] = 'save_new_payment';
				
				$form1 = $this->_generate_new_data_capture_form();
				$form = $form1["html"];
			break;
			}
			
			
			$this->class_settings[ 'data' ][ 'no_delete' ] = 1;
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
		
		private function _restock(){
			$return = array();
			if( isset( $_POST['item'] ) && $_POST['item'] ){
				
				$this->class_settings["update_fields"] = $_POST;
				
				$_POST['id'] = "";
				
				$return = $this->_update_table_field();
				if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
					
					unset( $this->class_settings[ 'do_not_check_cache' ] );
					$i = $this->_get_payment();
					$this->class_settings[ 'specific_item' ] = $i["item"];
					
					$inv = $this->_get_all_payment();
					
					if( isset( $inv[0]["item"] ) ){	
						
						$item = get_items_details( array( "id" => $inv[0]["item"] ) );
						
						$this->class_settings[ 'data' ]["item"] = array_merge( $inv[0], $item );
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/payment-list.php' );
						$returning_html_data = $this->_get_html_view();
						
						return array(
							'html_replace_selector' => "#".$inv[0]["item"]."-container",
							'html_replace' => $returning_html_data,
							'method_executed' => $this->class_settings['action_to_perform'],
							'status' => 'new-status',
							'javascript_functions' => array( 'nwpayment.init' ) 
						);
					}
				}
			}
			
			
			return $return;
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
			//$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			//$this->datatable_settings['custom_edit_button'] = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Payment";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			//$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
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
			case "view_all_produced_items_editable":
			break;
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Payment';
			break;
			default:
				if( ! isset( $this->class_settings['form_values_important'][ $this->table_fields["date"] ] ) )
					$this->class_settings['form_values_important'][ $this->table_fields["date"] ] = date("U");
			break;
			}
			//$this->class_settings["hidden_records"][ $this->table_fields["sales_id"] ] = 1;
			
			$this->class_settings['form_submit_button'] = 'Capture Payment &rarr;';
			
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
			
			//delete associated transactions
			if( isset( $returning_html_data['deleted_record_id'] ) && $returning_html_data['deleted_record_id'] ){
				$cache_key = $this->table_name;
				
				$returning_html_data[ 'data_table_name' ] = $this->table_name;
				
				//delete financial accounting transactions
				switch ( $this->class_settings['action_to_perform'] ){
				case 'delete_only':
					/*
					$table = 'sales';
					$_POST["mod"] = 'delete-' . md5( $table );
					
					$actual_name_of_class = 'c'.ucwords( $table );
					$module = new $actual_name_of_class();
					$module->class_settings = $this->class_settings;
					$module->class_settings["action_to_perform"] = 'delete_skip_payment';
					$module->$table();
					*/
				break;
				case 'delete':
					
					$table = 'transactions';
					$_POST["mod"] = 'delete-' . md5( $table );
					
					$actual_name_of_class = 'c'.ucwords( $table );
					$module = new $actual_name_of_class();
					$module->class_settings = $this->class_settings;
					$module->class_settings["action_to_perform"] = 'delete_only';
					$module->$table();
					/*
					$table = 'sales';
					$_POST["mod"] = 'delete-' . md5( $table );
					$actual_name_of_class = 'c'.ucwords( $table );
					$module = new $actual_name_of_class();
					
					$module->class_settings = $this->class_settings;
					$module->class_settings["action_to_perform"] = 'delete_skip_payment';
					$module->$table();
					*/
				break;
				default:
					$table = 'transactions';
					$_POST["mod"] = 'delete-' . md5( $table );
					
					$actual_name_of_class = 'c'.ucwords( $table );
					$module = new $actual_name_of_class();
					$module->class_settings = $this->class_settings;
					$module->class_settings["action_to_perform"] = 'delete_only';
					$module->$table();
				break;
				}
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
				
				$err->class_that_triggered_error = 'cpayment.php';
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
					$record = $this->_get_payment();
					
					if( ! ( isset( $returning_html_data['new_record_created'] ) && $returning_html_data['new_record_created'] ) ){
						$this->class_settings['new_record_created'] = 1;
					}
					
					$this->_record_in_accounting( $record );
				}
			}
			
			return $returning_html_data;
		}
		
		private function _record_in_accounting( $record = array() ){
			
			$rv = __map_financial_accounts();
			
			$customer_account = $rv[ "account_receivable" ];
			$bank_account = $rv[ "bank_account" ];
			
			$bs = __map_payment_methods_to_financial_accounts();
		
			if( isset( $bs[ $record[ "payment_method" ] ] ) && $bs[ $record[ "payment_method" ] ] ){
				$bank_account = $bs[ $record[ "payment_method" ] ];
			}
			
			switch( $record[ "payment_method" ] ){
			case "cash_refund":
				if( isset( $rv[ "customer_refund" ] ) )
					$bank_account = $rv[ "customer_refund" ];
				
				$dc[] = array(
					"transaction_id" => $record["id"],
					"account" => $bank_account,
					"amount" => abs( $record["amount_paid"] ),
					"type" => "credit",
					"account_type" => $bank_account,
					"extra_reference" => isset( $record[ "extra_reference" ] )?$record[ "extra_reference" ]:"",
					"currency" => isset( $record[ "currency" ] )?$record[ "currency" ]:"",
				);
				
				$dc[] = array(
					"transaction_id" => $record["id"],
					"account" => ( ( $record["customer"] )?$record["customer"]:$customer_account ),
					"amount" => abs( $record["amount_paid"] ),
					"type" => "debit",
					"account_type" => $rv[ "account_receivable" ],
					"extra_reference" => isset( $record[ "extra_reference" ] )?$record[ "extra_reference" ]:"",
					"currency" => isset( $record[ "currency" ] )?$record[ "currency" ]:"",
				);
				
				$desc = "Refund #" . $record["sales_id"] . " " . ( ( $record["comment"] )?$record["comment"]:"" );
			break;
			default:
				$dc[] = array(
					"transaction_id" => $record["id"],
					"account" => ( ( $record["customer"] )?$record["customer"]:$customer_account ),
					"amount" => $record["amount_paid"],
					"type" => "credit",
					"account_type" => $rv[ "account_receivable" ],
					"extra_reference" => isset( $record[ "extra_reference" ] )?$record[ "extra_reference" ]:"",
					"currency" => isset( $record[ "currency" ] )?$record[ "currency" ]:"",
				);
				
				$dc[] = array(
					"transaction_id" => $record["id"],
					"account" => $bank_account,
					"amount" => $record["amount_paid"],
					"type" => "debit",
					"account_type" => $bank_account,
					"extra_reference" => isset( $record[ "extra_reference" ] )?$record[ "extra_reference" ]:"",
					"currency" => isset( $record[ "currency" ] )?$record[ "currency" ]:"",
				);
				$s = get_sales_details( array( "id" => $record["sales_id"] ) );
				if( ! isset( $s["serial_num"] ) )$s["serial_num"] = $record["sales_id"];
				
				if( defined( "HYELLA_PACKAGE" ) ){
					switch( HYELLA_PACKAGE ){
					case "property":
						$record["reference_table"] = 'Rent';
					break;
					}
				}
				
				$desc = "Payment for ".$record["reference_table"]." #" . mask_serial_number( $s["serial_num"], 'S' ) . " " . ( ( $record["comment"] )?$record["comment"]:"" );
			break;
			}
			
			$data = array(
				"id" => $record["id"] ,
				"date" => date( "Y-n-j", doubleval( $record["date"] ) ) ,
				"reference" => $record["id"] ,
				"reference_table" => $this->table_name,
				"description" => $desc,
				"credit" => $record["amount_paid"],
				"debit" => $record["amount_paid"],
				"status" => "approved",
				'submitted_by' => $record["staff_responsible"],
				'submitted_on' => $record["creation_date"],
				'store' => $record["store"],
				'item' => $dc,
				"extra_reference" => isset( $record[ "extra_reference" ] )?$record[ "extra_reference" ]:"",
			);
			
			if( isset( $this->class_settings['new_record_created'] ) && $this->class_settings['new_record_created'] ){
				$data['delete_existing'] = $record["id"];
			}
			
			$transactions = new cTransactions();
			$transactions->class_settings = $this->class_settings;
			$transactions->class_settings["data"] = $data;
			$transactions->class_settings["action_to_perform"] = "add_transaction_from_sales";
			$transactions->transactions();
		}
		
		private function _get_payment(){
			
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
				else $select = "`id`, `serial_num`, `modification_date`, `creation_date`, `".$val."` as '".$key."'";
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
					
					//$this->_reset_members_cache( $record );
					//$this->class_settings["member_id"] = $record["member_id"];
					//$this->_get_payment();
				}
				
				return $single_data;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-payment-',//.$record["member_id"],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			$members = get_cache_for_special_values( $settings );
			
			if( is_array( $members ) ){
				if( $clear ){
					unset( $members[ $record['id'] ] );
				}else{
					$members[ $record['id'] ] = $record;
				}
				
				$settings = array(
					'cache_key' => $cache_key.'-payment-'.$record["member_id"],
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