<?php
	/**
	 * expenditure Class
	 *
	 * @used in  				expenditure Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	expenditure
	 */

	/*
	|--------------------------------------------------------------------------
	| expenditure Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	include "cExpenditure_payment.php";
	
	class cExpenditure{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'expenditure';
		
		private $associated_cache_keys = array(
			'expenditure',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'expenditure001',
			'vendor' => 'expenditure002',
			'description' => 'expenditure003',
			'quantity' => 'expenditure009',
			'amount_due' => 'expenditure004',
			'amount_paid' => 'expenditure005',
			'balance' => 'expenditure006',
			
			'mode_of_payment' => 'expenditure010',
			'receipt_no' => 'expenditure011',
			
			'category_of_expense' => 'expenditure007',
			'staff_in_charge' => 'expenditure008',
			
			'production_id' => 'expenditure012',
			'store' => 'expenditure013',
			'status' => 'expenditure014',
			'reference_table' => 'expenditure015',
			
			'currency' => 'expenditure016',
			'percentage_discount' => 'expenditure017',
			'tax' => 'expenditure018',
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
	
		function expenditure(){
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
			case 'create_new_draft_record':
			case "edit_draft_record":
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
			case 'get_all_expenditure':
				$returned_value = $this->_get_all_expenditure();
			break;
			case 'display_all_unpaid_records_full_view':
			case 'display_all_paid_records_full_view':
			case 'display_all_draft_records_full_view':
			case 'display_all_records_full_view':
			case 'display_custom_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'get_users_bar_chart':
			case 'get_users_pie_chart':
				$returned_value = $this->_get_users_pie_chart();
			break;
			case 'get_monthly_expenses':
				$returned_value = $this->_get_monthly_expenses();
			break;
			case 'get_stats':
				$returned_value = $this->_get_stats();
			break;
			case 'display_app_reports_full_view':
			case 'display_all_reports_full_view':
			case 'display_income_expenditure_reports_full_view':
			case 'display_app_income_expenditure_reports_full_view':
				$returned_value = $this->_display_income_expenditure_reports_full_view();
			break;
			case 'generate_income_expenditure_report':
				$returned_value = $this->_generate_income_expenditure_report();
			break;
			case 'this_year_expenses':
			case 'this_month_expenses':
			case 'display_app_expenditure':
				$returned_value = $this->_display_app_expenditure();
			break;
			case 'record_expense':
			case 'record_expense_batch_payment':
				$returned_value = $this->_record_expense();
			break;
			case 'save_expenditure':
				$returned_value = $this->_save_expenditure();
			break;
			case "delete_expense_from_stock":
				$returned_value = $this->_delete_expense_from_stock();
			break;
			case "save_mulitple_expenses":
				$returned_value = $this->_save_mulitple_expenses();
			break;
			case "get_total_expenditure":
			case 'view_all_production_items_editable':
			case 'get_specific_produced_items':
				$returned_value = $this->_view_all_produced_items();
			break;
			case "delete_expenditure":
				$returned_value = $this->_delete_expenditure();
			break;
			case "save_new_expenditure":
				$returned_value = $this->_save_new_expenditure();
			break;
			case "delete_expenditure_from_production":
				$returned_value = $this->_delete_expenditure_from_production();
			break;
			case "expenditure_search_vendor_goods_received_note":
			case "expenditure_search_vendor_supplier_invoice":
			case "expenditure_search_vendor_purchase_order":
			case "expenditure_search_all":
			case "expenditure_search":
			case 'expenditure_draft_search_all':
			case "filter_expenditure_search_all":
				$returned_value = $this->_expenditure_search();
			break;
			case "view_invoice_app1":
			case "view_invoice_app":
			case "view_invoice":
			case "get_invoice_info":
				$returned_value = $this->_view_invoice();
			break;
			case "update_stock_status_direct":
			case "update_stock_status":
				$returned_value = $this->_update_stock_status();
			break;
			case "display_all_batch_expenditure":
				$returned_value = $this->_display_all_batch_expenditure();
			break;
			case "capture_payment_form_refresh":
			case "capture_payment_form":
				$returned_value = $this->_capture_payment_form();
			break;
			case "post_all_draft":
			case "post_all_draft_as_paid":
			case "post_selected_draft_as_paid":
			case "post_selected_draft":
				$returned_value = $this->_post_draft();
			break;
			case "get_expenditure":
				$returned_value = $this->_get_expenditure();
			break;
			}
			
			return $returned_value;
		}
		
		private function _post_draft(){
			$ids = array();
			
			switch( $this->class_settings['action_to_perform'] ){
			case "post_selected_draft":
			case "post_selected_draft_as_paid":
				if( ! ( isset( $_POST[ "id" ] ) && $_POST[ "id" ] ) ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>No record was selected</h4>Please select records by clicking on them or pressing CTRL + Click to select multiple records';
					return $err->error();
				}
				
				$ids = array( $_POST[ "id" ] );
				if( isset( $_POST[ "ids" ] ) && $_POST[ "ids" ] ){
					$ids = explode( ":::", $_POST[ "ids" ] );
				}
			break;
			}
			
			$paid_update = "";
			switch( $this->class_settings['action_to_perform'] ){
			case "post_all_draft_as_paid":
			case "post_selected_draft_as_paid":
				$paid_update = ", `".$this->table_fields["amount_paid"]."` = `".$this->table_fields["amount_due"]."` ";
			break;
			}
			
			$where = "";
			if( ! empty( $ids ) ){
				foreach( $ids as $id ){
					if( $where )$where .= " OR `id` = '".$id."' ";
					else $where = " `id` = '".$id."' ";
				}
				$where = " AND ( ".$where." ) ";
			}
			
			$to_state = "pending";
			$from_where = " `".$this->table_fields["status"]."` = 'draft' ";
			
			//update or insert record into main table
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $paid_update ){
					switch( $key ){
					case "amount_paid":
						$val = $this->table_fields["amount_due"];
					break;
					}
				}
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `".$val."` as '".$key."'";
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND ( " . $from_where . " ) " . $where;
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 0,
				'tables' => array( $this->table_name ),
			);
			$e = execute_sql_query( $query_settings );
			
			if( ! ( is_array( $e ) && ! empty( $e ) ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Unapproved Records</h4>Note: You cannot approve records that were last updated by you';
				return $err->error();
			}
			
			$expenditure_payment = new cExpenditure_payment();
			$expenditure_payment->class_settings = $this->class_settings;
			$expenditure_payment->class_settings['dataset'] = $e;
			$expenditure_payment->class_settings["action_to_perform"] = 'update_changes_in_db';
			$r = $expenditure_payment->expenditure_payment();
			
			if( $r ){
				$date = date("U");
				
				$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `".$this->table_fields["status"]."` = '".$to_state."', `modified_by` = '".$this->class_settings["user_id"]."', `modification_date` = '".$date."' ".$paid_update." WHERE `record_status`='1' AND ( " . $from_where . " ) ".$where;
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$return = execute_sql_query( $query_settings );
				
				foreach( $e as $sval ){
					$this->class_settings["current_record_id"] = $sval["id"];
					$this->class_settings["do_not_check_cache"] = 1;
					$this->_record_in_accounting();
				}
				
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Successful Post Operation</h4><p>The draft expenses have been posted as actual expenses</p>';
				$return = $err->error();
				
				unset( $return["html"] );
				$return["status"] = "new-status";
				return $return;
			}else{
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Unknown Error</h4>Draft were not posted';
				return $err->error();
			}
		}
		
		private function _capture_payment_form(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			$title = "Capture Payment";
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			
			switch( $this->class_settings["action_to_perform"] ){
			case "capture_payment_form_refresh":
				$this->class_settings[ 'do_not_check_cache' ] = 1;
			break;
			}
			
			$e = $this->_get_expenditure();
			
			$expenditure_payment = new cExpenditure_payment();
			$expenditure_payment->class_settings = $this->class_settings;
			$expenditure_payment->class_settings['expenditure'] = $e;
			$expenditure_payment->class_settings["action_to_perform"] = 'capture_payment_form';
			$r = $expenditure_payment->expenditure_payment();
			
			//get info
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/expense-info.php' );
			$this->class_settings[ 'data' ]['item'] = $e;
			$html = $this->_get_html_view();
			
			$html .= $r["html_replacement"];
			
			switch( $this->class_settings["action_to_perform"] ){
			case "capture_payment_form_refresh":
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => "#modal-replacement-handle",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
				);
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = $title;
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
			);
			
		}
		
		private function _display_all_batch_expenditure(){
			$file_name = "display-all-batch-expenditure";
			
			$this->class_settings[ 'data' ][ 'report_type5' ] = get_expenditure_batch_payment_report_types();
			$this->class_settings[ 'data' ][ 'selected_option5' ] = 'draft_batch_payment_report';
			
			$this->class_settings[ 'data' ][ 'report_action' ] = 'generate_income_expenditure_report';
			
			//$this->class_settings[ 'data' ][ 'report_type2' ] = get_types_of_expenditure_grouped();
				
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$file_name );
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' =>  "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _update_stock_status(){
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			$this->class_settings[ 'return_after_save' ] = 1;
			$_POST["status"] = 'stocked';
			$return = $this->_record_expense();
			
			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				$id = $return["saved_record_id"];
				//update stock
				$this->class_settings["update_fields"] = array(
					"comment" => "items received & stocked",
					"production_id" => $return["saved_record_id"],
					"status" => "complete",
				);
				
				$inventory = new cInventory();
				$inventory->class_settings = $this->class_settings;
				$inventory->class_settings["action_to_perform"] = "update_inventory_records";
				$inventory->inventory();
				
				
				switch( $this->class_settings["action_to_perform"] ){
				case "update_stock_status_direct":
					$err = new cError(010011);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Stock Updated</h4><p>Items purchased has been added to stock</p>';
					$return = $err->error();
					
					$return["status"] = "new-status";
					unset( $return["html"] );
					$return["html_removal"] = "#update-button-" . $id;
					
				break;
				default:
					$return["status"] = "new-status";
					unset( $return["html"] );
				
					$this->class_settings["action_to_perform"] = "view_invoice_modal";
					return $this->_view_invoice();
				break;
				}
			}
			return $return;
		}
		
		private function _expenditure_search(){
			$filename = "all-expenditure-record.php";
			
			switch ( $this->class_settings['action_to_perform'] ){
			case "expenditure_search_vendor_purchase_order":
			case "expenditure_search_vendor_supplier_invoice":
			case "expenditure_search_vendor_goods_received_note":
				if( ! ( isset( $_POST[ "id" ] ) && $_POST[ "id" ] ) ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>No Vendor was selected</h4>Please select a Vendor first';
					return $err->error();
				}
				$this->class_settings["vendor"] = $_POST[ "id" ];
			break;
			}
			
			$err_title = '';
			switch ( $this->class_settings['action_to_perform'] ){
			case "expenditure_search_vendor_supplier_invoice":
				$this->class_settings["status"] = "pending";
				$err_title = 'No Supplier Invoice Found';
			break;
			case "expenditure_search_vendor_purchase_order":
				$this->class_settings["status"] = "draft";
				$err_title = 'No Purchase Order Found';
			break;
			case "expenditure_search_vendor_goods_received_note":
				$this->class_settings["status"] = "stocked";
				$err_title = 'No Purchase Order Found';
			break;
			case "filter_expenditure_search_all":
				$this->class_settings["return_query"] = 1;
			break;
			case 'expenditure_draft_search_all':
				$this->class_settings["status"] = 'pending';
				$filename = "draft-expenditure-record.php";
			break;
			}
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'expenditure_search_all':
			case 'expenditure_draft_search_all':
			case "filter_expenditure_search_all":
			case "expenditure_search_vendor_supplier_invoice":
			case "expenditure_search_vendor_purchase_order":
			case "expenditure_search_vendor_goods_received_note":
				$this->class_settings['all_stores'] = 1;
			break;
			}
			
			if( isset( $_POST["vendor"] ) && $_POST["vendor"] ){
				$this->class_settings["vendor"] = $_POST["vendor"];
			}
			
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$this->class_settings["start_date"] = convert_date_to_timestamp( $_POST["start_date"] );
			}
			
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$this->class_settings["end_date"] = convert_date_to_timestamp( $_POST["end_date"] );
			}
			
			$this->class_settings["category_of_expense"] = "purchase_order";
			$data = $this->_view_all_produced_items();
			unset( $this->class_settings["category_of_expense"] );
			
			if( isset( $this->class_settings["return_query"] ) && $this->class_settings["return_query"] ){
				unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
				
				if( $data )$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = $data;
				
				return array(
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
				);
			}
			
			if( isset( $this->class_settings["return_data"] ) && $this->class_settings["return_data"] ){
				return $data;
			}
			
			switch ( $this->class_settings['action_to_perform'] ){
			case "expenditure_search_vendor_purchase_order":
			case "expenditure_search_vendor_supplier_invoice":
			case "expenditure_search_vendor_goods_received_note":	
				if( ! empty( $data ) ){
					
					$this->class_settings[ 'data' ][ "item" ] = $data;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/purchase-order-list.php' );
					$returning_html_data = $this->_get_html_view();
					
					$return = array(
						'html_replacement_selector' => 'select[name="purchase_order"]',
						'html_replacement' => $returning_html_data,
						'method_executed' => $this->class_settings['action_to_perform'],
						'status' => 'new-status',
						'javascript_functions' => array( 'nwCart.purchaseOrderSelectRefresh' ),
					);
				}else{
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>'.$err_title.'</h4>';
					$r = $err->error();
					
					$r["status"] = "new-status";
					$r["javascript_functions"] = array( 'nwCart.purchaseOrderSelectEmpty' );
					unset( $r["html"] );
					
					$return = $r;
				}
				return $return;
			break;
			}
			
			if( ! empty( $data ) ){
				$this->class_settings[ 'data' ][ "expense_record" ] = $data;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				
				$returning_html_data = $this->_get_html_view();
				$return = array(
					'html_replacement_selector' => "#sales-record-search-result",
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event', 'nwRecordPayment.init' ),
				);
			}else{
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No Record Found</h4>Please check your vendor name';
				$r = $err->error();
				
				$r["status"] = "new-status";
				$r["html_replacement_selector"] = "#sales-record-search-result";
				$r["html_replacement"] = $r["html"];
				unset( $r["html"] );
				
				$return = $r;
			}
			
			$this->class_settings["not_category_of_expense"] = "purchase_order";
			$data = $this->_view_all_produced_items();
			if( ! empty( $data ) ){
				$this->class_settings[ 'data' ][ "expense_record" ] = $data;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/all-expenditure-record.php' );
				
				$returning_html_data = $this->_get_html_view();
				$return['html_replacement_selector_one'] = "#payment-record-search-result";
				$return["html_replacement_one"] = $returning_html_data;
				$return["javascript_functions"] = array( 'set_function_click_event', 'nwRecordPayment.init' );
			}else{
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No Record Found</h4>Please check your vendor name';
				$r = $err->error();
				
				$return["status"] = "new-status";
				$return["html_replacement_selector_one"] = "#payment-record-search-result";
				$return["html_replacement_one"] = $r["html"];
			}
			return $return;
			
		}
		
		private function _record_in_accounting(){
			
			if( ! ( isset( $this->class_settings["current_record_id"] ) && $this->class_settings["current_record_id"] ) )return 0;
			
			$e = $this->_get_expenditure();
			$extra_desc1 = '';
			
			$skip = 0;
			if( get_purchase_order_settings() ){
				switch( $e["status"] ){
				case "stocked":
					if( $e["production_id"] && $e["reference_table"] == $this->table_name ){
						$this->class_settings[ 'current_record_id' ] = $e["production_id"];
						$s = $this->_get_expenditure();
						
						if( isset( $s["production_id"] ) ){
							$this->class_settings[ 'current_record_id' ] = $s["production_id"];
						}
						$extra_desc1 = " #" . mask_serial_number( $e["serial_num"], 'GR' );
					}
				break;
				default:
					$skip = 1;
				break;
				}
			}
			
			if( $skip ){
				return 0;
			}
			
			$units = $e["quantity"];
			$cost = $e["amount_due"];
			
			$dc = array();
			
			$rv = __map_financial_accounts();
			$expense_account = $rv[ "operating_expense" ];
			$vendor_account = $rv[ "account_payable" ];
			$bank_account = $rv[ "bank_account" ];
			$inventory_account = $rv[ "inventory" ];
			$inventory_or_cost_of_goods = $rv[ "cost_of_goods_sold" ];
			
			$operating_expense = 1;
			$check_for_category = 0;
			
			switch( $e["category_of_expense"] ){
			case 'purchase_order':
			// case 'purchase_of_items':
			// case 'purchase_of_materials':
				$operating_expense = 0;
				$check_for_category = 1;
			break;
			}
			
			if( ! $operating_expense ){
				$inventory_or_cost_of_goods = $rv[ "inventory" ];
			}
			
			$extra_desc = '';
			
			if( $check_for_category ){
				$this->class_settings["production_id"] = $this->class_settings["current_record_id"];
				$this->class_settings["reference_table"] = $this->table_name;
				
				$inventory = new cInventory();
				$inventory->class_settings = $this->class_settings;
				$inventory->class_settings["action_to_perform"] = "get_specific_produced_items";
				$purchased_items = $inventory->inventory();
				
				$units = 0;
				$cost = 0;
				$cost_price = 0;
				
				$items_categories = array();
				
				if( is_array( $purchased_items ) && ! empty( $purchased_items ) ){
					foreach( $purchased_items as $item ){
						
						$item_details = get_items_details( array( "id" => $item["item"] ) );
						if( isset( $item_details["category"] ) ){
							if( ! isset( $items_categories[ $item_details["category"] ] ) ){
								$items_categories[ $item_details["category"] ] = 0;
							}
							//$items_categories[ $item_details["category"] ] += ( $item["quantity_expected"] * $item["cost_price"] );
							$items_categories[ $item_details["category"] ] += ( $item["quantity"] * $item["cost_price"] );
						}
						
						//$units += $item["quantity_expected"];
						$units += $item["quantity"];
						//$cost += ( $item["quantity_expected"] * $item["cost_price"] );
						$cost += ( $item["quantity"] * $item["cost_price"] );
						//$cost_price += ( $item["quantity_expected"] * $item["cost_price"] );
						$cost_price += ( $item["quantity"] * $item["cost_price"] );
						
					}
					$extra_desc = $units.' units of goods ';
				}
			}
			
			if( ! empty( $items_categories ) ){
				foreach( $items_categories as $cat => $am ){
					$final = $am;
					
					$dc[] = array(
						"transaction_id" => $e["id"],
						"account" => $cat,
						"amount" => $final,
						"type" => "debit",
						"account_type" => $inventory_or_cost_of_goods,
					);
				}
			}else{
				if( $operating_expense ){
					//debit operating expense
					$dc[] = array(
						"transaction_id" => $e["id"],
						"account" => ( ( $e["category_of_expense"] )?$e["category_of_expense"]:$expense_account ),
						"amount" => $cost,
						"type" => "debit",
						"account_type" => $rv[ "operating_expense" ],
						"currency" => $e[ "currency" ],
					);
				}else{
					//debit inventory for goods or raw materials that would be stocked
					$dc[] = array(
						"transaction_id" => $e["id"],
						"account" => $inventory_account,
						"amount" => $cost,
						"type" => "debit",
						"account_type" => $rv[ "inventory" ],
						"currency" => $e[ "currency" ],
					);
				}
			}
			
			if( isset( $this->class_settings["capture_amount_paid"] ) && doubleval( $this->class_settings["capture_amount_paid"] ) ){
				$amount_paid = doubleval( $this->class_settings["capture_amount_paid"] );
				$pm = get_payment_method_list();
				
				$bank_account_type = $bank_account;
				if( $e[ "payment_method" ] && isset( $pm[ $e[ "payment_method" ] ] ) ){
					$bank_account = $pm[ $e[ "payment_method" ] ];
				}
				
				$dc[] = array(
					"transaction_id" => $e["id"],
					"account" => ( ( $e["vendor"] )?$e["vendor"]:$vendor_account ),
					"amount" => $amount_paid,
					"type" => "debit",
					"account_type" => $rv[ "account_payable" ],
					"currency" => $e[ "currency" ],
					"extra_reference" => $e["id"],
				);
				
				$dc[] = array(
					"transaction_id" => $e["id"],
					"account" => $bank_account,
					"amount" => $amount_paid,
					"type" => "credit",
					"account_type" => $bank_account_type,
					"currency" => $e[ "currency" ],
				);
				
			}
			
			$dc[] = array(
				"transaction_id" => $e["id"],
				"account" => ( ( $e["vendor"] )?$e["vendor"]:$vendor_account ),
				"amount" => $cost,
				"type" => "credit",
				"account_type" => $rv[ "account_payable" ],
				"currency" => $e[ "currency" ],
			);
			
			$data = array(
				"id" => $e["id"] ,
				"date" => date( "Y-n-j", doubleval( $e["date"] ) ) ,
				"reference" => $e["id"] ,
				"reference_table" => $this->table_name,
				"description" => $e["description"] . ' ' . $extra_desc . $extra_desc1,
				"credit" => $cost,
				"debit" => $cost,
				"status" => "approved",
				'submitted_by' => $e['staff_in_charge'],
				'submitted_on' => $e["creation_date"],
				'store' => $e["store"],
				'item' => $dc,
				'delete_existing' => $e["id"],
			);
			
			$transactions = new cTransactions();
			$transactions->class_settings = $this->class_settings;
			$transactions->class_settings["data"] = $data;
			$transactions->class_settings["action_to_perform"] = "add_transaction_from_sales";
			return $transactions->transactions();
		}
		
		private function _delete_expenditure_from_production(){
			
			if( ! ( isset( $this->class_settings["production_query"] ) && $this->class_settings["production_query"] ) ){
				return 0;
			}
			
			$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `".$this->table_fields[ "production_id" ]."` IN ".$this->class_settings["production_query"];
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql = execute_sql_query($query_settings);
			
			$ids = "";
			if( is_array( $sql ) && ! empty( $sql ) ){
				foreach( $sql as $val ){
					if( $ids )$ids .= ":::" . $val["id"];
					else $ids = $val["id"];
				}
			}
			
			if( $ids ){
				unset( $_POST["id"] );
				unset( $_POST["ids"] );
				
				$_POST["ids"] = $ids;
				$_POST["mod"] = 'delete-' . md5( $this->table_name );
				return $this->_delete_records();
			}
		}
		
		private function _save_new_expenditure(){
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
				$record = $this->_get_expenditure();
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/form-control-view-row.php' );
			
				$this->class_settings[ 'data' ][ 'pagepointer' ] = $this->class_settings["calling_page"];
				$this->class_settings[ 'data' ][ 'new_record' ] = 1;
				$this->class_settings[ 'data' ][ 'id' ] = $record[ 'production_id' ];
				$this->class_settings[ 'data' ][ 'items' ] = array( $record );
				$this->class_settings[ 'data' ][ 'no_edit' ] = 1;
				
				if( $edit_mode ){
					$return["html_replace"] = $this->_get_html_view();
					$return["html_replace_selector"] = "#expenditure-".$record[ 'id' ];
				}else{							
					$return["html_prepend"] = $this->_get_html_view();
					$return["html_prepend_selector"] = "#form-control-table-expenditure";
				}
				
				$return["javascript_functions"] = array( "set_function_click_event" );
				unset( $return['saved_record_id'] );
			}
			
			$return["status"] = "new-status";
			
			unset( $return["html"] );
			return $return;
		}
		
		private function _delete_expenditure(){
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
			$return["html_removal"] = "#expenditure-" . $this->class_settings[ 'current_record_id' ];
			
			unset( $return["html"] );
			return $return;
		}
		
		private function _view_all_produced_items(){
			
			$where = "";
			switch ( $this->class_settings['action_to_perform'] ){
			case "expenditure_search_vendor_purchase_order":
			case "expenditure_search_vendor_supplier_invoice":
			case "expenditure_search_vendor_goods_received_note":
			case "expenditure_search_all":
			case "expenditure_search":
			case 'expenditure_draft_search_all':
				if( ! isset( $this->class_settings["vendor"] ) )return 0;
				
				$where = " AND `".$this->table_fields["vendor"]."`='".$this->class_settings["vendor"]."' ";
				
				if( isset( $this->class_settings["start_date"] ) && doubleval( $this->class_settings["start_date"] ) ){
					$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
				}
				
				if( isset( $this->class_settings["end_date"] ) && doubleval( $this->class_settings["end_date"] ) ){
					$where .= " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
				}
				
				if( isset( $this->class_settings["category_of_expense"] ) && $this->class_settings["category_of_expense"] ){
					$where .= " AND `".$this->table_fields["category_of_expense"]."` = '" . $this->class_settings["category_of_expense"] . "' ";
				}
				
				if( isset( $this->class_settings["status"] ) && $this->class_settings["status"] ){
					$where .= " AND `".$this->table_fields["status"]."` = '" . $this->class_settings["status"] . "' ";
				}
				
				if( isset( $this->class_settings["not_category_of_expense"] ) && $this->class_settings["not_category_of_expense"] ){
					$where .= " AND `".$this->table_fields["category_of_expense"]."` != '" . $this->class_settings["not_category_of_expense"] . "' ";
				}
			break;
			case "filter_expenditure_search_all":
				if( isset( $this->class_settings["vendor"] ) && $this->class_settings["vendor"] )
					$where = " AND `".$this->table_fields["vendor"]."`='".$this->class_settings["vendor"]."' ";
				
				if( isset( $this->class_settings["start_date"] ) && doubleval( $this->class_settings["start_date"] ) ){
					$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
				}
				
				if( isset( $this->class_settings["end_date"] ) && doubleval( $this->class_settings["end_date"] ) ){
					$where .= " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
				}
				
				if( isset( $this->class_settings["category_of_expense"] ) && $this->class_settings["category_of_expense"] ){
					$where .= " AND `".$this->table_fields["category_of_expense"]."` = '" . $this->class_settings["category_of_expense"] . "' ";
				}
				
				if( isset( $this->class_settings["not_category_of_expense"] ) && $this->class_settings["not_category_of_expense"] ){
					$where .= " AND `".$this->table_fields["category_of_expense"]."` != '" . $this->class_settings["not_category_of_expense"] . "' ";
				}
				
				return $where;
			break;
			default:
				if( ! isset( $this->class_settings["production_id"] ) )return 0;
				
				$where = " AND `".$this->table_fields["production_id"]."`='".$this->class_settings["production_id"]."' ";
			break;
			}
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `".$val."` as '".$key."'";
			}
			
			$es = "";
			
			$payment = new cDebit_and_credit();
			$select .= ", ( SELECT SUM( `".$payment->table_name."`.`".$payment->table_fields['amount']."` ) FROM `" . $this->class_settings['database_name'] . "`.`".$payment->table_name."` WHERE `".$payment->table_name."`.`".$payment->table_fields['extra_reference']."` = `".$this->table_name."`.`id` AND `".$payment->table_name."`.`record_status` = '1' AND `".$payment->table_fields["type"]."` = 'debit' AND `".$payment->table_fields["account_type"]."` = 'account_payable' ) as 'total_amount_paid' ";
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' " . $where;
			//echo $query; exit;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_total_expenditure':
				$query = "SELECT SUM( `".$this->table_fields["amount_due"]."` ) as 'TOTAL' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields["production_id"]."`='".$this->class_settings["production_id"]."' ";
			break;
			}
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name, $payment->table_name ),
			);
			
			$bills = execute_sql_query($query_settings);
			$form = "";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_specific_produced_items':
			case 'get_total_expenditure':
			case "expenditure_search_all":
			case "expenditure_search":
			case "expenditure_search_vendor_goods_received_note":
				return $bills;
			break;
			case "expenditure_search_vendor_purchase_order":
				//run second query
				$query = "SELECT `".$this->table_fields["production_id"]."` as 'id' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields["production_id"]."` != '' AND `".$this->table_fields["vendor"]."`='".$this->class_settings["vendor"]."' AND `".$this->table_fields["status"]."` = 'stocked' ";
				//$query = "SELECT `".$this->table_fields["production_id"]."` as 'id' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields["production_id"]."` != '' AND `".$this->table_fields["vendor"]."`='".$this->class_settings["vendor"]."' AND `".$this->table_fields["status"]."` = 'pending' ";
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$bills["intersect"] = execute_sql_query($query_settings);
				
				return $bills;
			break;
			case "expenditure_search_vendor_supplier_invoice":
				//run second query
				$query = "SELECT `".$this->table_fields["production_id"]."` as 'id' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields["production_id"]."` != '' AND `".$this->table_fields["vendor"]."`='".$this->class_settings["vendor"]."' AND `".$this->table_fields["status"]."` = 'stocked' ";
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$bills["intersect"] = execute_sql_query($query_settings);
				
				return $bills;
			break;
			case 'view_all_production_items_editable':
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "description":	
					case "amount_due":	
					case "category_of_expense":
					case "amount_paid":
					break;
					case "date":
					case "production_id":
					case "store":
					case "vendor":
						$this->class_settings["hidden_records_css"][ $val ] = 1;
						
						if( isset( $this->class_settings[ $key ] ) )
							$this->class_settings["form_values_important"][ $val ] = $this->class_settings[ $key ];
					break;
					default:
						$this->class_settings["hidden_records"][ $val ] = 1;
					break;
					}
				}
				
				$this->class_settings[ 'form_action_todo' ] = 'save_new_expenditure';
				
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
		
		private function _save_mulitple_expenses(){
			
			if( ! ( isset( $this->class_settings["production"]["expenses"] ) && is_array( $this->class_settings["production"]["expenses"] ) && ! empty( $this->class_settings["production"]["expenses"] ) ) ){
				return 0;
			}
			if( ! ( isset( $this->class_settings[ 'production_id' ] ) ) ){
				return 0;
			}
			
			$array_of_dataset = array();
			
			$new_record_id = get_new_id();
			$new_record_id_serial = 0;
			
			$ip_address = get_ip_address();
			$date = date("U");
			
			$exp = $this->class_settings["production"]["expenses"];
			$ids = array();
			
			foreach( $exp as $expense ){
				$id = $new_record_id . 'W' . ++$new_record_id_serial;
				
				$ids[ $id ] = $id;
				
				$dataset_to_be_inserted = array(
					'id' => $id,
					'created_role' => $this->class_settings[ 'priv_id' ],
					'created_by' => $this->class_settings[ 'user_id' ],
					'creation_date' => $date,
					'modified_by' => $this->class_settings[ 'user_id' ],
					'modification_date' => $date,
					'ip_address' => $ip_address,
					'record_status' => 1,
					
					$this->table_fields["date"] => $date,
					
					$this->table_fields["description"] => $expense["description"],
					$this->table_fields["category_of_expense"] => $expense["category_of_expense"],
					$this->table_fields["staff_in_charge"] => ( isset( $this->class_settings["production"][ 'staff_responsible' ] )?$this->class_settings["production"][ 'staff_responsible' ] : $this->class_settings[ 'user_id' ] ),
					
					$this->table_fields["amount_due"] => $expense["amount_paid"],
					$this->table_fields["amount_paid"] => $expense["amount_paid"],
					$this->table_fields["mode_of_payment"] => "cash",
					$this->table_fields["receipt_no"] => $this->class_settings[ 'production_id' ],
					$this->table_fields["production_id"] => $this->class_settings[ 'production_id' ],
					$this->table_fields["store"] => ( isset( $this->class_settings["production"][ 'store' ] ) ? $this->class_settings["production"][ 'store' ] : "" ),
					$this->table_fields["vendor"] => ( isset( $this->class_settings["production"]["factory"] ) ? $this->class_settings["production"]["factory"] : "" ),
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
				
				foreach( $ids as $id ){
					$this->class_settings["current_record_id"] = $id;
					$this->_record_in_accounting();
				}
			}
			
			return $saved;
		}
		
		private function _delete_expense_from_stock(){
			$_POST['mod'] = 'delete-'.md5( $this->table_name );
			$r = $this->_delete_records();
			
			if( isset( $r[ "deleted_record_id" ] ) && $r[ "deleted_record_id" ] ){
				unset( $r["html"] );
				$r["status"] = "new-status";
				$r["html_removal"] = "tr#" . $r[ "deleted_record_id" ];
				$r["javascript_functions"] = array( "nwExpenses.emptyNewItem" );
			}
			
			return $r;
		}
		
		private function _save_expenditure(){
			
			if( ! ( isset( $this->class_settings["production"] ) && is_array( $this->class_settings["production"] ) ) ){
				return 0;
			}
			if( ! ( isset( $this->class_settings[ 'production_id' ] ) ) ){
				return 0;
			}
			$_POST["id"] = "";
			
			$this->class_settings[ 'return_after_save' ] = 1;
			//check for existing expense with same production id
			$_POST['tmp'] = $this->class_settings[ 'production_id' ];
			
			$_POST[ "status" ] = "stocked";
			
			$_POST[ "production_id" ] = $this->class_settings[ 'production_id' ];
			$_POST[ "date" ] = date("Y-m-d");
			$_POST[ "amount_due" ] = isset( $this->class_settings["production"]["extra_cost"] )?$this->class_settings["production"]["extra_cost"]:"";
			$_POST[ "amount_paid" ] = isset( $this->class_settings["production"]["extra_cost"] )?$this->class_settings["production"]["extra_cost"]:"";
			
			$_POST[ "staff_in_charge" ] = isset( $this->class_settings["production"]["staff_responsible"] )?$this->class_settings["production"]["staff_responsible"]:"";
			$_POST[ "category_of_expense" ] = 'cost_of_production';
			$_POST[ "description" ] = "Extra Cost Incurred during production. REF: " . $this->class_settings["production_id"];
			$_POST[ "vendor" ] = isset( $this->class_settings["production"]["factory"] )?$this->class_settings["production"]["factory"]:"";
			
			if( isset( $this->class_settings["source"] ) ){
				switch( $this->class_settings["source"] ){
				case "cart":
				case "inventory":
					$_POST[ "store" ] = $this->class_settings["production"]["store"];
					$_POST[ "date" ] = $this->class_settings["production"]["date"];
					$_POST[ "amount_due" ] = $this->class_settings["production"]["amount_due"];
					$_POST[ "amount_paid" ] = $this->class_settings["production"]["amount_paid"];
					$this->class_settings["capture_amount_paid"] = $this->class_settings["production"]["amount_paid"];
					
					$_POST[ "staff_in_charge" ] = $this->class_settings["production"]["staff_in_charge"];
					$_POST[ "category_of_expense" ] = $this->class_settings["production"]["category_of_expense"];
					$_POST[ "description" ] = $this->class_settings["production"]["description"];
					$_POST[ "vendor" ] = $this->class_settings["production"]["vendor"];
					
					if( isset( $this->class_settings["production"]["status"] ) && $this->class_settings["production"]["status"] )
						$_POST[ "status" ] = $this->class_settings["production"]["status"];
					
					if( isset( $this->class_settings["production"]['mode_of_payment'] ) && $this->class_settings["production"]['mode_of_payment'] )
						$_POST[ 'mode_of_payment' ] = $this->class_settings["production"]['mode_of_payment'];
					
					if( isset( $this->class_settings["production"]['percentage_discount'] ) && $this->class_settings["production"]['percentage_discount'] )
						$_POST[ 'percentage_discount' ] = $this->class_settings["production"]['percentage_discount'];
					
					if( isset( $this->class_settings["production"]['tax'] ) && $this->class_settings["production"]['tax'] )
						$_POST[ 'tax' ] = $this->class_settings["production"]['tax'];
					
					if( isset( $this->class_settings["production"]['production_id'] ) && $this->class_settings["production"]['production_id'] )
						$_POST[ 'production_id' ] = $this->class_settings["production"]['production_id'];
					
					if( isset( $this->class_settings["production"]['reference_table'] ) && $this->class_settings["production"]['reference_table'] )
						$_POST[ 'reference_table' ] = $this->class_settings["production"]['reference_table'];
				break;
				}
			}
			
			return $this->_record_expense();
		}
		
		private function _record_expense(){
			$return = array();
			$js = array( 'nwExpenses.init' );
			$new = 0;
			
			$file_name = 'expense-list.php';
			$mode = 0;
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'record_expense_batch_payment':
				$_POST[ "status" ] = 'draft';
				if( isset( $_SESSION["store"] ) && $_SESSION["store"] ){
					$_POST[ "store" ] = $_SESSION["store"];
				}else{
					$store = get_stores();
					if( ! empty( $store ) ){
						foreach( $store as $key => $val ){
							$_POST[ "store" ] = $key;
							break;
						}
					}					
				}
				$file_name = 'batch-payment-list.php';
				$mode = 1;
				$js = array();
			break;
			}
			
			if( isset( $_POST['id'] ) ){
				
				foreach( $this->table_fields as $key => $val ){
					if( isset( $_POST[ $key ] ) ){
						$_POST[ $val ] = $_POST[ $key ];
						unset( $_POST[ $key ] );
					}
				}
				
				if( ! $_POST['id'] ){
					//new mode
					$new = 1;
					if( $mode != 1 ){
						$js[] = 'nwExpenses.showRecentExpensesTab';
						$js[] = 'nwExpenses.reClick';
					}
				}
				
				$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
				$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
				$_POST[ "table" ] = $this->table_name;
				$_POST[ "processing" ] = md5(1);
				if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
					define('SKIP_USE_OF_FORM_TOKEN', 1);
				
				$return = $this->_save_changes();
				
				if( isset( $this->class_settings[ 'return_after_save' ] ) && $this->class_settings[ 'return_after_save' ] ){
					return $return;
				}
				
				if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
					
					unset( $this->class_settings[ 'do_not_check_cache' ] );
					$e = $this->_get_expenditure();
					
					if( isset( $e["id"] ) ){
						$this->class_settings[ 'data' ]["item"] = $e;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$file_name );
						$returning_html_data = $this->_get_html_view();
						
						unset( $return["html"] );
						$return["status"] = "new-status";
						
						if( $new ){	
							$return["html_prepend_selector"] = "#recent-expenses tbody";
							$return["html_prepend"] = $returning_html_data;
							$return["javascript_functions"] = $js;
							return $return;
						}
						
						$return["html_replace_selector"] = "#".$e["id"];
						$return["html_replace"] = $returning_html_data;
						$return["javascript_functions"] = $js;
						return $return;
					}
				}
			}
			
			
			return $return;
		}
		
		private function _display_app_expenditure(){
			
			$this->class_settings[ 'return_data' ] = 1;
			$_GET["month"] = "date;;;".date("Y;;;n");
			
			$this_month = date("n");
			$this_year = date("Y");
			/*
			if( --$this_month < 1 ){
				$this_month = 12;
				--$this_year;
			}
			
			$_GET["month"] = "date;;;".$this_year.";;;".$this_month;
			$this->class_settings[ 'data' ][ 'last_month' ] = $this->_generate_farm_report();
			*/
			$file = "display-app-expenditure";
			$selector = "#dash-board-main-content-area";
			$js = array();
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'this_year_expenses':
				$_GET["month"] = "date;;;".$this_year;
				$this->class_settings[ 'data' ] = $this->_generate_farm_report();
				$this->class_settings[ 'data' ]["type"] = "year";
				$file = "expense-table.php";
				$selector = "#this-year";
				
				$js = array( 'nwExpenses.init' );
			break;
			case 'this_month_expenses':
				$this->class_settings[ 'data' ] = $this->_generate_farm_report();
				$file = "expense-table.php";
				$selector = "#this-month";
				
				$js = array( 'nwExpenses.init' );
			break;
			default:
				$where = "";
				if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$where = " AND `".$this->table_name."`.`".$this->table_fields[ 'store' ]."` = '" . $_SESSION[ "store" ] . "' ";
				}
				$where .= " AND `".$this->table_fields["category_of_expense"]."` != 'purchase_order' ";
				$this->class_settings["reset_conditions"] = " WHERE `".$this->table_name."`.`record_status` = '1' ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields[ "date" ]."` DESC LIMIT 20 ";
				$this->class_settings[ 'data' ][ 'recent_expenses' ] = $this->_generate_farm_report();
				unset( $this->class_settings["reset_conditions"] );
				
				$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$file );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $selector,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js 
			);
		}
		
		private function _generate_farm_report(){
			$returning_html_data = "";
			
			$field_name = "";
			$grouping = 0;
			
			$regard_store = 1;
			
			switch( $this->class_settings["action_to_perform"] ){
			case "generate_sales_report":
				$regard_store = 0;
			break;
			}
			
			$field_def = "`".$this->table_name."`.`".$this->table_fields[ "date" ]."`";
			
			if( isset( $this->class_settings["field_def"] ) && $this->class_settings["field_def"] ){
				$field_def = $this->class_settings["field_def"];
			}
			
			if( isset( $_GET["month"] ) && $_GET["month"] ){
				$pieces = explode( ";;;" , $_GET["month"] );
				if( isset( $pieces[0] ) && $pieces[0] ){
					$field_name = $pieces[0];
				}
				
				$y = 0;
				$m = 1;
				$d = 0;
				$em = 0;
				
				$where = "";
				$title = "";
			
				$start_date = 0;
				$end_date = 0;
				if( isset( $pieces[1] ) && $pieces[1] ){
					$y = intval( $pieces[1] );
					
					$m = 0;
					if( isset( $pieces[2] ) && $pieces[2] ){
						$m = intval( $pieces[2] );
						$em = $m + 1;
						if( $em > 12 ){
							$em = 1;
							++$y;
						}
						if( isset( $pieces[3] ) && $pieces[3] ){
							$em = 0;
							$d = intval( $pieces[3] );
						}
						
					}
					
					$title = "EXPENDITURE RECORDS FOR ";
					if( $em ){
						$start_date = mktime(0,0,0, $m , 1, $y );
						$end_date = mktime(0,0,0, $em , 1, $y );
						 $title .= date( "F, Y", $start_date );
					}else{
						if( $d ){
							$start_date = mktime(0,0,0, $m , $d, $y );
							$title .= date( "jS F, Y", $start_date );
						}else{
							$start_date = mktime(0,0,0, 1 , 1, $y );
							$end_date = mktime(0,0,0, 1 , 1, ($y+1) );
							$title .= date( "Y", $start_date );
							$grouping = 1;
						}
					}
					
					if( $start_date && $end_date ){
						$where = " AND ".$field_def." >= " . $start_date . " AND ".$field_def." <= " . $end_date;
					}elseif( $start_date ){
						$where = " AND ".$field_def." = " . $start_date;
					}
				}
				
			}
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			if( $field_name ){
				
				if( $regard_store && isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$where .= " AND `".$this->table_name."`.`".$this->table_fields[ 'store' ]."` = '" . $_SESSION[ "store" ] . "' ";
				}
					
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status`='1' ".$where." GROUP BY `".$this->table_name."`.`id` ORDER BY `".$this->table_name."`.`".$this->table_fields[ $field_name ]."` DESC ";
				
				if( isset( $this->class_settings["reset_conditions"] ) && $this->class_settings["reset_conditions"] ){
					$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` " . $this->class_settings["reset_conditions"];
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
				
				
				if( $grouping == 1 ){
					//group data based on year
					$all_new = array();
					foreach( $all_data as $sval ){
						$key = date( "F", doubleval( $sval["date"] ) );
						if( isset( $all_new[ $key ] ) ){
							foreach( $sval as $k => $v ){
								switch( $k ){
								case "amount_due":
								case "amount_paid":
								case "balance":
								case "quantity":
									$all_new[ $key ][ $k ] += doubleval( $v );
								break;
								case "vendor":
								case "description":
								case "payment_type":
								case "staff_in_charge":
									$all_new[ $key ][ $k ] =  "*Several";
								break;
								}
							}
						}else{
							$all_new[ $key ] = $sval;
						}
					}
					$all_data = $all_new;
					$this->class_settings[ 'data' ][ 'date_filter' ] = "F";
				}
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-report' );
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'report_type' ] = "daily_report";
				$this->class_settings[ 'data' ][ 'report_data' ] = $all_data;
				
				if( isset( $this->class_settings[ 'return_data' ] ) && $this->class_settings[ 'return_data' ] ){
					return $this->class_settings[ 'data' ];
				}
				
				$returning_html_data = $this->_get_html_view();
				//$returning_html_data = $query;
				
			}else{
				//return error message
			}
			
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#data-table-section",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				//'javascript_functions' => array( 'activate_tree_view', 'set_function_click_event' ) 
			);
		}
		
		private function _generate_income_expenditure_report(){
			
			$returning_html_data = "";
			
			$field_name = "date";
			$initial_where = " `".$this->table_name."`.`record_status`='1' ";
			$where = $initial_where;
			
			$title = "";
			$select = "";
			$grouping = 1;
			
			$js = array();
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "date":
				case "amount_due":
				case "amount_paid":
					if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
					else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
				break;
				}
			}
			
			$report_type = "income_expenditure_report";
			if( isset( $_GET["department"] ) && $_GET["department"] && $_GET["department"] != "-" ){
				$report_type = $_GET["department"];
			}
			
			$start_date_timestamp = 0;
			if( isset( $_GET["start_date"] ) && $_GET["start_date"] ){
				$st = explode( "-", $_GET["start_date"] );
				if( isset( $st[2] ) )
					$start_date_timestamp = mktime( 0,0,0, $st[1], $st[2], $st[0] );
			}
			$start_date = $start_date_timestamp;
			
			$end_date_timestamp = 0;
			if( isset( $_GET["end_date"] ) && $_GET["end_date"] ){
				$st = explode( "-", $_GET["end_date"] );
				if( isset( $st[2] ) )
					$end_date_timestamp = mktime( 23,59,59, $st[1], $st[2], $st[0] );
			}
			$end_date = $end_date_timestamp;
			
			$group1 = "";
			if( isset( $_GET["budget"] ) && $_GET["budget"] )
				$group1 = $_GET["budget"];
			
			$skip_joins = 0;
			
			$date_filter = "M-Y";
			$get_opening_stock = 0;
			$age_key = "date";
			
			$pen_required = 0;
			$do_group_items = 0;
			
			switch( $group1 ){
			case "monthly":
				$date_filter = "M-Y";
				$grouping = 10;
			break;
			case "daily":
				$date_filter = "d-M-Y";
				$grouping = 100;
			break;
			case "yearly":
				$date_filter = "Y";
				$grouping = 1;
			break;
			}
			
			$subtitle = "";
			$group = " ";
			//$group = " GROUP BY `".$this->table_name."`.`".$this->table_fields[ 'date' ]."` ";
			
			switch( $report_type ){
			case "income_expenditure_report":
			case "income_expenditure_graphical_report":
				$skip_joins = 0;
				$do_group_items = 1;
				$title = "INCOME vs EXPENDITURE REPORT";
			break;
			case "periodic_expense_report":
				$skip_joins = 1;
				$title = strtoupper( get_select_option_value( array( "id" => $report_type , "function_name" => "get_expenditure_report_types" ) ) );
				
				$select = "";
				foreach( $this->table_fields as $key => $val ){
					if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
					else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
				}
				
				$pen_val = "";
				if( isset( $_GET["operator"] ) && $_GET["operator"] ){
					$p = $_GET["operator"];
					$exp = get_types_of_expenditure();
					if( isset( $exp[ $p ] ) ){
						$title .= "<br /><strong>".ucwords( $exp[ $p ] )."</strong>";
						$pen_val = $p;
					}
				}
				if( $pen_val ){
					$where .= " AND `".$this->table_name."`.`".$this->table_fields["category_of_expense"]."` = '".$pen_val."' ";
				}
			break;
			case "part_payment_expense_report":
			case "unpaid_expense_report":
				$skip_joins = 1;
				$grouping = 21;
				$title = strtoupper( get_select_option_value( array( "id" => $report_type , "function_name" => "get_expenditure_report_types" ) ) );
				
				$select = "";
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "amount_due":
					case "amount_paid":
						$select .= ", SUM(`".$this->table_name."`.`".$val."`) as '".$key."'";
					break;
					default:
						if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
						else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
					break;
					}
				}
				
				$group = " GROUP BY `".$this->table_name."`.`".$this->table_fields["vendor"]."` ";
				
				$pen_val = "";
			break;
			case 'draft_batch_payment_report':
			case 'pending_payment_report':
			case 'all_payment_report':
			case 'all_expenses_report':
				$skip_joins = 1;
				$grouping = 21;
				$title = strtoupper( get_select_option_value( array( "id" => $report_type , "function_name" => "get_expenditure_batch_payment_report_types" ) ) );
				
				$select = "";
				foreach( $this->table_fields as $key => $val ){
					if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
					else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
				}
				
				switch( $report_type ){
				case 'draft_batch_payment_report':
					$where .= " AND  `".$this->table_name."`.`".$this->table_fields["status"]."` = 'draft' ";
				break;
				case 'pending_payment_report':
					$where .= " AND `".$this->table_name."`.`".$this->table_fields["amount_due"]."` > `".$this->table_name."`.`".$this->table_fields["amount_paid"]."` AND  `".$this->table_name."`.`".$this->table_fields["status"]."` != 'draft' ";
				break;
				case 'all_payment_report':
					$where .= " AND `".$this->table_name."`.`".$this->table_fields["amount_due"]."` = `".$this->table_name."`.`".$this->table_fields["amount_paid"]."` AND  `".$this->table_name."`.`".$this->table_fields["status"]."` != 'draft' ";
				break;
				case 'all_expenses_report':
					$where .= " AND  `".$this->table_name."`.`".$this->table_fields["status"]."` != 'draft' ";
				break;
				}
				
				$group = "";
				$js = array( "set_function_click_event" , "prepare_new_record_form_new" );
				
				$pen_val = "";
				/*
				if( isset( $_GET["operator"] ) && $_GET["operator"] ){
					$p = $_GET["operator"];
					$exp = get_types_of_expenditure();
					if( isset( $exp[ $p ] ) ){
						$title .= "<br /><strong>".ucwords( $exp[ $p ] )."</strong>";
						$pen_val = $p;
					}
				}
				if( $pen_val ){
					$where .= " AND `".$this->table_name."`.`".$this->table_fields["category_of_expense"]."` = '".$pen_val."' ";
				}
				*/
			break;
			}
			
			if( $where ){
				$all_data = array();
				
				if( $start_date ){
					$where .= " AND `".$this->table_name."`.`".$this->table_fields["date"]."` >= " . $start_date;
					$subtitle .= "From " . date( $date_filter, $start_date );
				}
				
				if( $end_date ){
					$where .= " AND `".$this->table_name."`.`".$this->table_fields["date"]."` <= " . $end_date;
					$subtitle .= " To " . date( $date_filter, $end_date );
				}
					
				$subtitle = "";
			
				if( $start_date ){
					$subtitle .= "From: <strong>" . date( "d-M-Y", doubleval( $start_date ) ) . "</strong> ";
				}
				
				if( $end_date ){
					$subtitle .= " To: <strong>" . date( "d-M-Y", doubleval( $end_date ) ) . "</strong>";
				}
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE ".$where." ".$group." ORDER BY `".$this->table_name."`.`".$this->table_fields[ 'date' ]."` DESC ";
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$expenditure = execute_sql_query($query_settings);
				
				if( ! $skip_joins ){
					//Egg Sales
					$sales_record = new cSales();
					$sales_items = new cSales_items();
					$payment = new cPayment();
					
					$group_items = "";
					switch( $do_group_items ){
					case 1:
						$group_items = " `".$sales_record->table_name."`.`id`, `".$sales_record->table_name."`.`".$sales_record->table_fields[ 'date' ]."` ";
					break;
					}
					
					$where2 = "";
					if( $start_date )
						$where2 .= " `".$sales_record->table_name."`.`".$sales_record->table_fields["date"]."` >= " . $start_date;
					
					if( $end_date ){
						if( $where2 )$where2 .= " AND `".$sales_record->table_name."`.`".$sales_record->table_fields["date"]."` <= " . $end_date;
						else $where2 .= " `".$sales_record->table_name."`.`".$sales_record->table_fields["date"]."` <= " . $end_date;
					}
					
					$where1 = " `".$sales_record->table_name."`.`record_status`='1' AND `".$sales_items->table_name."`.`record_status`='1' ";
					
					if( $where2 )$where1 = " AND " . $where1;
					
					$where = " ( " . $where2 . $where1 . " ) ";
					
					$select = " ( `".$sales_record->table_name."`.`".$sales_record->table_fields['date']."` ) as 'date', ( `".$sales_record->table_name."`.`".$sales_record->table_fields['discount']."` ) as 'discount', SUM( `".$sales_items->table_name."`.`".$sales_items->table_fields['quantity']."` - `".$sales_items->table_name."`.`".$sales_items->table_fields['quantity_returned']."` ) as 'quantity_sold', SUM( ( (`".$sales_items->table_name."`.`".$sales_items->table_fields['quantity']."` - `".$sales_items->table_name."`.`".$sales_items->table_fields['quantity_returned']."`) * `".$sales_items->table_name."`.`".$sales_items->table_fields['cost']."` ) - `".$sales_items->table_name."`.`".$sales_items->table_fields['discount']."` ) as 'income_amount_due', ( SELECT SUM( `".$payment->table_name."`.`".$payment->table_fields['amount_paid']."` ) FROM `" . $this->class_settings['database_name'] . "`.`".$payment->table_name."` WHERE `".$payment->table_name."`.`".$payment->table_fields['sales_id']."` = `".$sales_record->table_name."`.`id` AND `".$payment->table_name."`.`record_status` = '1' ) as 'income_amount_paid' ";
					
					$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$sales_record->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$sales_items->table_name."` ON `".$sales_record->table_name."`.`id` = `".$sales_items->table_name."`.`".$sales_items->table_fields['sales_id']."` WHERE ".$where." GROUP BY ".$group_items." ORDER BY `".$sales_record->table_name."`.`".$sales_record->table_fields[ 'date' ]."` DESC ";
					
					//echo $query; exit;
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 0,
						'tables' => array( $sales_record->table_name, $sales_items->table_name ),
					);
					$sales = execute_sql_query($query_settings);
					
				}
				
				//print_r($sales); exit;
				switch( $skip_joins ){
				case 1:
					$all_data = $expenditure;
				break;
				default:
					$all_data = array_merge( $expenditure, $sales );
				break;
				}
				//print_r($all_data); exit;
				switch( $grouping ){
				case 1:		//based on year
				case 10:	//based on months
				case 100:	//based on days
					if( ! $date_filter )$date_filter = "F";
					
					//group data based on year
					$all_new = array();
					
					$birds = 0;
					
					foreach( $all_data as $sval ){
						$key = date( $date_filter , doubleval( $sval["date"] ) );
						if( isset( $all_new[ $key ] ) ){
							foreach( $sval as $k => $v ){
								//echo $k.":::"; 
								switch( $k ){
								case "description":
								case "vendor":
									$all_new[ $key ][ $k ] = "*Several";
								break;
								case "item":
								case "date":
								break;
								default:	
									if( ! isset( $all_new[ $key ][ $k ] ) )$all_new[ $key ][ $k ] = 0;
									$all_new[ $key ][ $k ] += doubleval( $v );
								break;
								}
							}
						}else{
							$all_new[ $key ] = $sval;
						}
					}
					//exit;
					//print_r($all_new); exit;
					$all_data = $all_new;
					$this->class_settings[ 'data' ][ 'date_filter' ] = $date_filter;
					
				break;
				}
				
				
				switch( $report_type ){
				case "income_expenditure_graphical_report":
					$x_axis = array();
					$data1 = $x_axis;
					
					foreach( $all_data as $key => $sval ){
						$x_axis[] = $key;
						
						if( ! isset( $sval["amount_due"] ) )$sval["amount_due"] = 0;
						if( ! isset( $sval["income_amount_due"] ) )$sval["income_amount_due"] = 0;
						
						$data1[ 0 ][ "name" ] = "Expenditure";
						$data1[ 0 ][ "data" ][] = doubleval( $sval["amount_due"] );
						
						$data1[ 1 ][ "name" ] = "Income";
						$data1[ 1 ][ "data" ][] = doubleval( $sval["income_amount_due"] );
						
					}
					
					$return["highchart_data"] = basic_column_chart();
					$return["highchart_data"]["title"]["text"] = $title;
					$return["highchart_data"]["subtitle"]["text"] = $subtitle;
					$return["highchart_data"]["xAxis"]["categories"] = $x_axis;
					$return["highchart_data"]["yAxis"]["title"]["text"] = "Amount in Naira";
					$return["highchart_data"]["series"] = $data1;
					
					$return["highchart_exported_chart_name"] = "bar-chart";
					$return["highchart_container_selector"] = "#chart-container-1";
					
					$return["javascript_functions"] = array( 'activate_highcharts' );
					$return["status"] = "new-status";
					
					$return["do_not_reload_table"] = 1;
					
					$return['html_replacement'] = "<div id='chart-container-1'></div>";
					$return['html_replacement_selector'] = "#data-table-section";
					
					return $return;
				break;
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-report' );
				$this->class_settings[ 'data' ][ 'report_subtitle' ] = $subtitle;
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'report_type' ] = $report_type;
				$this->class_settings[ 'data' ][ 'report_data' ] = $all_data;
				$this->class_settings[ 'data' ][ 'report_age' ] = isset( $report_age )?$report_age:"";
				$this->class_settings[ 'data' ][ 'days_filter' ] = isset( $days )?$days:7;
				$this->class_settings[ 'data' ][ 'selected_pen' ] = isset( $pen_val )?$pen_val:"";
				
				$returning_html_data = $this->_get_html_view();
				//$returning_html_data = $query;
			}else{
				//return error message
				if( ! $error_file )$error_file = "error-message.php";
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$error_file );
				$returning_html_data = $this->_get_html_view();
			}
			
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#data-table-section",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js,
			);
		}
		
		private function _display_income_expenditure_reports_full_view(){
			$file_name = "display-income-expenditure-reports-full-view";
			
			$this->class_settings[ 'data' ][ 'report_action' ] = "generate_income_expenditure_report";
			$this->class_settings[ 'data' ][ 'hide_months' ] = 1;
			
			$this->class_settings[ 'data' ][ 'report_type5' ] = get_income_verse_expenditure_report_types();
			$this->class_settings[ 'data' ][ 'selected_option5' ] = "income_expenditure_report";
			
			$this->class_settings[ 'data' ][ 'report_type3' ] = get_report_periods_without_weeks();
			$this->class_settings[ 'data' ][ 'selected_option3' ] = "monthly";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'display_all_reports_full_view':
			case 'display_app_reports_full_view':
				$this->class_settings[ 'data' ][ 'report_type5' ] = get_expenditure_report_types();
				$this->class_settings[ 'data' ][ 'selected_option5' ] = 'periodic_expense_report';
				
				$this->class_settings[ 'data' ][ 'report_type2' ] = get_types_of_expenditure_grouped();
				
				$file_name = "display-all-reports-full-view";
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$file_name );
			
			//$this->class_settings[ 'data' ][ 'report_type2' ] = get_items_grouped_goods();
			//$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-items";
			
			//$this->class_settings[ 'data' ][ 'hide_report' ] = 1;
			
			$returning_html_data = $this->_get_html_view();
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'display_app_reports_full_view':
			case 'display_app_income_expenditure_reports_full_view':
				return array(
					'html_replacement_selector' =>  "#dash-board-main-content-area",
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ) 
				);
			break;
			}
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _get_stats(){
			$return = array();
			
			$start_date = mktime( 0,0,0, 1, 1, date("Y") );
			$end_date = mktime( 23,59,59, 12, 31 , date("Y") );
			
			$query = "SELECT SUM( `".$this->table_fields["amount_due"]."` ) as 'amount_deposited', SUM( `".$this->table_fields["amount_due"]."` ) as 'amount_withdrawn', `".$this->table_fields["date"]."` as 'date' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND ( `".$this->table_fields["date"]."` >= ".$start_date." AND `".$this->table_fields["date"]."` <= ".$end_date." ) ";
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]["amount_deposited"])  && isset($sql_result[0]["amount_withdrawn"]) ){
				$return["amount_deposited"] = doubleval( $sql_result[0][ "amount_deposited" ] );
				$return["amount_withdrawn"] = doubleval( $sql_result[0][ "amount_withdrawn" ] );
			}
			
			$query = "SELECT SUM( `".$this->table_fields["amount_due"]."` ) as 'amount_deposited', SUM( `".$this->table_fields["amount_due"]."` ) as 'amount_withdrawn', `".$this->table_fields["date"]."` as 'date' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' ";
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]["amount_deposited"])  && isset($sql_result[0]["amount_withdrawn"]) ){
				$return["balance"] = doubleval( $sql_result[0][ "amount_deposited" ] ) - doubleval( $sql_result[0][ "amount_withdrawn" ] );
			}
			
			return $return;
		}
		
		private function _get_monthly_expenses(){
			
			$start_date = mktime( 0,0,0, 1, 1, date("Y") );
			$end_date = mktime( 23,59,59, 12, 31 , date("Y") );
			
			$query = "SELECT ( `".$this->table_fields["amount_due"]."` ) as 'total', `".$this->table_fields["date"]."` as 'date' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND ( `".$this->table_fields["date"]."` >= ".$start_date." AND `".$this->table_fields["date"]."` <= ".$end_date." ) ";
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
            $id = '';
            $rec = array();
				
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				$data = array();
				$total = 0;
				
				foreach( $sql_result as $sval ){
					$d = date( "F", doubleval( $sval["date"] ) );
					
					if( isset( $rec[ $d ] ) )
						$rec[ $d ] += doubleval( $sval['total'] );
					else
						$rec[ $d ] = doubleval( $sval['total'] );
				}
			}
			return $rec;
		}
		
		private function _get_users_pie_chart(){
			
			$start_date = mktime( 0,0,0, date("m"), 1, date("Y") );
			$end_date = mktime( 23,59,59, date("m"), cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y") ) , date("Y") );
			
			$query = "SELECT SUM( `".$this->table_fields["amount_due"]."` ) as 'total', `".$this->table_fields["category_of_expense"]."` as 'payment_type' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND ( `".$this->table_fields["date"]."` >= ".$start_date." AND `".$this->table_fields["date"]."` <= ".$end_date." ) GROUP BY `".$this->table_fields["category_of_expense"]."` ";
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
            $id = '';
            
			$piechart = 1;
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_users_bar_chart':	
				$piechart = 0;
			break;
			}
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				$data = array();
				$total = 0;
				/*
				foreach( $sql_result as $sval ){
					$total += doubleval( $sval["total"] );
				}
				*/
				$reg = get_types_of_expenditure();
				$x_axis = array();
				$data1 = array();
				foreach( $sql_result as $sval ){
					if( $sval["total"] && isset( $reg[ $sval["payment_type"] ] ) ){
						$data[] = array(
							"name" => isset( $reg[ $sval["payment_type"] ] )?$reg[ $sval["payment_type"] ]:"Not Available",
							"y" => doubleval($sval["total"]),
						);
						
						$data1[] = array(
							"name" => isset( $reg[ $sval["payment_type"] ] )?$reg[ $sval["payment_type"] ]:"Not Available",
							"data" => array( doubleval($sval["total"]) ),
						);
					}
				}
				
				if( $piechart ){
					$return["highchart_data"] = pie_legend_chart();
					$return["highchart_data"]["subtitle"]["text"] = date("F, Y");
					$return["highchart_data"]["series"][0]["data"] = $data;
				}else{
					$return["highchart_data"] = basic_column_chart();
					$return["highchart_data"]["series"] = $data1;
				}
				
				if( $piechart ){
					$return["highchart_exported_chart_name"] = "pie-chart";
					$return["highchart_container_selector"] = "#chart-container";
				}else{
					$return["highchart_exported_chart_name"] = "bar-chart";
					$return["highchart_container_selector"] = "#chart-container-1";
				}
					
				
				//$return["highchart_container_selector"] = "#".$chartname;
				
				$return["javascript_functions"] = array( 'activate_highcharts' );
				$return["status"] = "new-status";
				
				$return["do_not_reload_table"] = 1;
				/*
				if( $piechart ){					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = 1;
					$return['action'] = '?action=sales_record&todo=get_users_bar_chart';
				}
				*/
				//return $return;
			}	
			$return["status"] = "new-status";
			
			if( ! isset( $return["javascript_functions"] ) )$return["javascript_functions"] = array( "nwResizeWindow.adjustBarChart" );
			
			//$return['re_process'] = 1;
			//$return['re_process_code'] = 1;
			$return['mod'] = 'import-';
			$return['id'] = 1;
			$return['action'] = '?action=sales_record&todo=get_users_bar_chart';
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/chart-display' );
			$return['html_replacement'] = $this->_get_html_view();
			$return['html_replacement_selector'] = "#dash-board-main-content-area";
			
			return $return;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$capture_payment = 0;
			$view_details = 0;
			$show_filter_form = 0;
			
			$where = "";
			unset( $_SESSION[ $this->table_name ] );
			$title = "Manage Expenditures";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'display_all_draft_records_full_view':
				$where = " AND  `".$this->table_name."`.`".$this->table_fields["status"]."` = 'draft' ";
				$title = "Manage Draft Expenditures";
				
				$this->datatable_settings['show_edit_button'] = 0;
				$this->datatable_settings['show_add_new'] = array();
				$this->datatable_settings['show_add_new']['function-name'] = "create_new_draft_record";
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-draft-buttons.php' );
				$this->datatable_settings['custom_edit_button'] = $this->_get_html_view();
			break;
			case 'display_all_unpaid_records_full_view':
				$where = " AND `".$this->table_name."`.`".$this->table_fields["amount_due"]."` > `".$this->table_name."`.`".$this->table_fields["amount_paid"]."` AND  `".$this->table_name."`.`".$this->table_fields["status"]."` != 'draft' ";
				$title = "Manage Unpaid Expenditures";
				
				$this->datatable_settings['show_add_new'] = 0;
				$this->datatable_settings['show_edit_button'] = 0;
				$this->datatable_settings['show_delete_button'] = 0;
				
				$capture_payment = 1;
			break;
			case 'display_all_paid_records_full_view':
				$where = " AND `".$this->table_name."`.`".$this->table_fields["amount_due"]."` = `".$this->table_name."`.`".$this->table_fields["amount_paid"]."` AND  `".$this->table_name."`.`".$this->table_fields["status"]."` != 'draft' ";
				$title = "Manage Paid Expenditures";
				
				$this->datatable_settings['show_add_new'] = 0;
				$this->datatable_settings['show_edit_button'] = 0;
				$this->datatable_settings['show_delete_button'] = 0;
			break;
			case 'display_custom_records_full_view':
				if( ! get_purchase_order_settings() ){
					$where = " AND  `".$this->table_name."`.`".$this->table_fields["status"]."` != 'draft' ";
				}
				$title = "Manage Purchase Orders";
				
				$capture_payment = 0;
				$view_details = 1;
				$show_filter_form = 1;
				$this->datatable_settings['show_add_new'] = 0;
				$this->datatable_settings['show_edit_button'] = 0;
				$this->datatable_settings['show_delete_button'] = 0;
			break;
			default:
				$where = " AND  `".$this->table_name."`.`".$this->table_fields["status"]."` != 'draft' ";
				$capture_payment = 1;
			break;
			}
			
			if( $capture_payment ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
				$this->datatable_settings['custom_edit_button'] = $this->_get_html_view();
			}
			
			if( $view_details ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-view-details-buttons.php' );
				$this->datatable_settings['custom_edit_button'] = $this->_get_html_view();
			}
			
			if( $show_filter_form ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-filter-form.php' );
				$this->class_settings[ 'data' ]['form_data'] = $this->_get_html_view();
				
				$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
				$this->class_settings[ 'data' ]['form_title'] = 'Search Purchase Orders';
			}
			
			if( $where ){
				$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = $where;
			}
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$datatable = $this->_display_data_table();
			
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = $title;
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
			
			$title = "Purchase Order";
			$this->class_settings["current_record_id"] = $_POST["id"];
			$this->class_settings["production_id"] = $this->class_settings["current_record_id"];
			$this->class_settings["sales_id"] = $this->class_settings["current_record_id"];
			
			$e["event"] = $this->_get_expenditure();
			
			if( get_purchase_order_settings() ){
				switch( $e["event"]["status"] ){
				case "pending":
					$title = "Supplier Invoice";
					if( $e["event"]["production_id"] && $e["event"]["reference_table"] == $this->table_name ){
						$this->class_settings["production_id"] = $e["event"]["production_id"];
						$this->class_settings["hide_all_buttons"] = 1;
					}
				break;
				case "stocked":
					$title = "Goods Received Note";
					if( $e["event"]["production_id"] && $e["event"]["reference_table"] == $this->table_name ){
						$this->class_settings[ 'current_record_id' ] = $e["event"]["production_id"];
						$s = $this->_get_expenditure();
						
						if( isset( $s["production_id"] ) ){
							$this->class_settings["production_id"] = $s["production_id"];
						}
						$this->class_settings["hide_all_buttons"] = 1;
					}
				break;
				}
			}
			
			switch( $e["event"]["status"] ){
			case "stock":
				$title = "Restocked Goods";
			break;
			}
			
			$this->class_settings["reference_table"] = $this->table_name;
			
			$inventory = new cInventory();
			$inventory->class_settings = $this->class_settings;
			$inventory->class_settings["action_to_perform"] = "get_specific_produced_items";
			$e['purchased_items'] = $inventory->inventory();
			
			switch( $this->class_settings["action_to_perform"] ){
			case "get_invoice_info":
				foreach( $e['purchased_items'] as & $v ){
					$item_details = get_items_details( array( "id" => $v["item"] ) );
					
					if( isset( $item_details["description"] ) ){
						$v["desc"] = $item_details["description"];
						$v["barcode"] = $item_details["barcode"];
					}else{
						$v["desc"] = "";
						$v["barcode"] = "";
					}
					
					$v["cost_price"] = doubleval( $v["cost_price"] );
					$v["quantity_ordered"] = doubleval( $v["quantity_ordered"] );
					$v["quantity_expected"] = ( doubleval( $v["quantity_expected"] ) )?doubleval( $v["quantity_expected"] ):$v["quantity_ordered"];
					$v["quantity"] = ( $v["quantity_expected"] )?$v["quantity_expected"]:$v["quantity_ordered"];
					$v["tax"] = doubleval( $v["tax"] );
					$v["discount"] = doubleval( $v["discount"] );
					
				}
				$e["event"]["tax"] = doubleval( $e["event"]["tax"] );
				$e["event"]["percentage_discount"] = doubleval( $e["event"]["percentage_discount"] );
				$e["event"]["reference"] = $e["event"]["id"];
				$e["event"]["payment_method"] = $e["event"]["mode_of_payment"];
				
				return array(
					'data' => $e,
					'status' => 'new-status',
					'javascript_functions' => array( 'nwCart.rePopulateCart' ),
				);
			break;
			}
			
			$payment = new cPayment();
			$payment->class_settings = $this->class_settings;
			$payment->class_settings["action_to_perform"] = 'get_total_amount_paid_vendor';
			$e['payment'] = $payment->payment();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/expenditure-manifest.php' );
			$this->class_settings[ 'data' ] = $e;
			$this->class_settings[ 'data' ]["backend"] = 1;
			$this->class_settings[ 'data' ]["title"] = $title;
			$this->class_settings[ 'data' ]["purchase_order"] = 1;
			
			if( isset( $this->class_settings["show_print_button"] ) )
				$this->class_settings[ 'data' ]["backend"] = 0;
			
			if( isset( $this->class_settings["hide_buttons"] ) )
				$this->class_settings[ 'data' ]["hide_buttons"] = 1;
		
			if( isset( $this->class_settings["hide_all_buttons"] ) )
				$this->class_settings[ 'data' ]["hide_all_buttons"] = 1;

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
			case "view_invoice_modal":
				return array(
					'html_replacement' => $html,
					'html_replacement_selector' => "#modal-replacement-handle",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ),
				);
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = $title;
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
			
			$this->class_settings["hidden_records"][ $this->table_fields["balance"] ] = 1;
			$this->class_settings['hidden_records'][ $this->table_fields["status"] ] = 1;
			$this->class_settings['hidden_records'][ $this->table_fields["reference_table"] ] = 1;
			$this->class_settings['hidden_records'][ $this->table_fields["production_id"] ] = 1;
			
			switch ( $this->class_settings['action_to_perform'] ){
			case "create_new_draft_record":
				unset( $this->class_settings['hidden_records'][ $this->table_fields["status"] ] );
				$this->class_settings['hidden_records_css'][ $this->table_fields["status"] ] = 1;
				$this->class_settings['form_values_important'][ $this->table_fields["status"] ] = "draft";
			break;
			case "edit_draft_record":
				unset( $this->class_settings['hidden_records'][ $this->table_fields["status"] ] );
				$this->class_settings['hidden_records_css'][ $this->table_fields["status"] ] = 1;
				$this->class_settings['form_values_important'][ $this->table_fields["status"] ] = "draft";
			break;
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Expenditures';
				$this->class_settings['hidden_records'][ $this->table_fields["amount_paid"] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields["amount_due"] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields["currency"] ] = 1;
			break;
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
				
				switch ( $this->class_settings['action_to_perform'] ){
				case 'delete_only':
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
				
				switch ( $this->class_settings['action_to_perform'] ){
				case "delete":
				case 'delete_only':
					//remove inventory
					$table = 'inventory';
					$_POST["mod"] = 'delete-' . md5( $table );
					
					$actual_name_of_class = 'c'.ucwords( $table );
					$module = new $actual_name_of_class();
					$module->class_settings = $this->class_settings;
					$module->class_settings["action_to_perform"] = 'delete_only';
					$module->$table();
				break;
				}
				
				switch ( $this->class_settings['action_to_perform'] ){
				case "delete":
				case 'delete_only':
					$d = '';
					$d1 = explode( ":::", $returning_html_data['deleted_record_id'] );
					foreach( $d1 as $dd ){
						if( ! $dd )continue;
						if( $d )$d .= ", '" . $dd . "' ";
						else $d = "'" . $dd . "' ";
					}
					if( $d ){
						$query = "SELECT `".$this->table_fields["production_id"]."` as 'id' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `id` IN ( ".$d." ) ";
						
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'SELECT',
							'set_memcache' => 1,
							'tables' => array( $this->table_name ),
						);
						$sql = execute_sql_query($query_settings);
						
						$expenditure_payment = new cExpenditure_payment();
						$expenditure_payment->class_settings = $this->class_settings;
						$expenditure_payment->class_settings["action_to_perform"] = 'delete_all_reference_table_items';
						
						$expenditure_payment->class_settings["sales_query"] = $d;
						$expenditure_payment->expenditure_payment();
					
						$ids = "";
						if( is_array( $sql ) && ! empty( $sql ) ){
							foreach( $sql as $val ){
								if( ! $val["id"] )continue;
								if( $ids )$ids .= ":::" . $val["id"];
								else $ids = $val["id"];
							}
						}
						
						if( $ids ){
							unset( $_POST["id"] );
							unset( $_POST["ids"] );
							$_POST["ids"] = $ids;
							
							$table = 'production';
							$_POST["mod"] = 'delete-' . md5( $table );
							
							$actual_name_of_class = 'c'.ucwords( $table );
							$module = new $actual_name_of_class();
							$module->class_settings = $this->class_settings;
							$module->class_settings["action_to_perform"] = 'delete';
							$module->$table();
						}
					}
				break;
				}
				
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
				
				$err->class_that_triggered_error = 'cexpenditure.php';
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
					$record = $this->_get_expenditure();
					
					//update accounting records
					unset( $this->class_settings[ 'do_not_check_cache' ] );
					if( isset( $returning_html_data['new_record_created'] ) && $returning_html_data['new_record_created'] ){
						
						if( isset( $record["status"] ) && $record["status"] != 'draft' ){
							/*
							if( isset( $record["amount_paid"] ) && doubleval( $record["amount_paid"] ) ){
								$expenditure_payment = new cExpenditure_payment();
								$expenditure_payment->class_settings = $this->class_settings;
								$expenditure_payment->class_settings['expenditure_id'] = $record["id"];
								$expenditure_payment->class_settings["reference_table"] = $this->table_name;
								
								$expenditure_payment->class_settings["amount_paid"] = $record["amount_paid"];
								$expenditure_payment->class_settings['payment_method'] = $record[ "mode_of_payment" ];
								$expenditure_payment->class_settings['vendor'] = $record["vendor"];
								$expenditure_payment->class_settings['store'] = $record["store"];
								$expenditure_payment->class_settings['comment'] = "Expenditure Payment On Creation";
								$expenditure_payment->class_settings['extra_reference'] = $record["production_id"];
								
								if( isset( $record["staff_in_charge"] ) && $record["staff_in_charge"] )
									$expenditure_payment->class_settings['staff_responsible'] = $record["staff_in_charge"];
								
								$expenditure_payment->class_settings["action_to_perform"] = 'save_expenditure_payment';
								$expenditure_payment->expenditure_payment();
								
								$this->class_settings[ 'do_not_check_cache' ] = 1;
								$this->class_settings['current_record_id'] = $returning_html_data['saved_record_id'];
								$this->_get_expenditure();
							}
							*/
							$this->_record_in_accounting();
							
							if( isset( $this->class_settings["capture_amount_paid"] ) && doubleval( $this->class_settings["capture_amount_paid"] ) ){
								$this->class_settings[ 'do_not_check_cache' ] = 1;
								$this->class_settings['current_record_id'] = $returning_html_data['saved_record_id'];
								$this->_get_expenditure();
							}
						}
					}
					
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_expenditure(){
			
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
				else $select = "`id`, `serial_num`, `modification_date`, `modified_by`, `creation_date`, `".$val."` as '".$key."'";
			}
			
			//remove later
			$payment = new cDebit_and_credit();
			$select .= ", ( SELECT SUM( `".$payment->table_name."`.`".$payment->table_fields['amount']."` ) FROM `" . $this->class_settings['database_name'] . "`.`".$payment->table_name."` WHERE `".$payment->table_name."`.`".$payment->table_fields['extra_reference']."` = `".$this->table_name."`.`id` AND `".$payment->table_name."`.`record_status` = '1' AND `".$payment->table_fields["type"]."` = 'debit' AND `".$payment->table_fields["account_type"]."` = 'account_payable' ) as 'total_amount_paid' ";
			
			//PREPARE FROM DATABASE
			$query = "SELECT " . $select . " FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `id` = '".$this->class_settings[ 'current_record_id' ]."'";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name, $payment->table_name ),
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
					//$this->_get_expenditure();
				}
				
				return $single_data;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-expenditure-',//.$record["member_id"],
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
					'cache_key' => $cache_key.'-expenditure-'.$record["member_id"],
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