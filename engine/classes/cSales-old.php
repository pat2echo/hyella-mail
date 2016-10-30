<?php
	/**
	 * sales Class
	 *
	 * @used in  				sales Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	sales
	 */

	/*
	|--------------------------------------------------------------------------
	| sales Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cSales{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'sales';
		
		private $associated_cache_keys = array(
			'sales',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'sales001',
			'quantity' => 'sales002',
			'cost' => 'sales003',
			'discount' => 'sales004',
			'discount_type' => 'sales005',
			
			'amount_due' => 'sales006',
			'amount_paid' => 'sales007',
			'balance' => 'sales011',
			'payment_method' => 'sales008',
			'customer' => 'sales009',
			'store' => 'sales010',
			
			'sales_status' => 'sales013',
			
			'comment' => 'sales012',
			'staff_responsible' => 'sales014',
			'reference' => 'sales015',
			'reference_table' => 'sales016',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
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
	
		function sales(){
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
			case 'save_update_sales_status':
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
			case 'display_calendar_view_only':
			case 'display_calendar_view':
			case "display_calendar_view_mobile":
			case 'display_calendar_view_only_mobile':
			case "display_calendar_view_only_mobile_enable_redirection":
				$returned_value = $this->_display_calendar_view();
			break;
			case "display_calendar_view_agenda":
				$returned_value = $this->_display_calendar_view_agenda();
			break;
			case "edit_event_hall_type":
			case "edit_event_other_rented_items":
			case "edit_event_organizer":
			case "edit_event_discount":
			case "edit_event_refundable_fee":
			case "edit_event_details":
			case "edit_event_view":
				$returned_value = $this->_edit_event_view();
			break;
			case "add_remove_visit_schedule":
				$returned_value = $this->_add_remove_visit_schedule();
			break;
			case "create_new_event":
				$returned_value = $this->_create_new_event();
			break;
			case "get_first_step_sales_form":
				$returned_value = $this->_get_first_step_sales_form();
			break;
			case "book_event_hall":
			case "book_event_package":
			case "book_event_other_items":
			case "book_event":
				$returned_value = $this->_book_event();
			break;
			case "save_book_event_add_items":
			case "save_book_event":
				$returned_value = $this->_save_site_changes();
			break;
			case "conclude_booking":
				$returned_value = $this->_conclude_booking();
			break;
			case "cancel_sale":
				$returned_value = $this->_cancel_sale();
			break;
			case "track_invoice":
				$returned_value = $this->_track_invoice();
			break;
			case "get_event":
				$returned_value = $this->_get_sales();
			break;
			case "display_hall_selector":
			case "display_other_items_selector":
			case "display_packaged_plan_selector":
				$returned_value = $this->_display_packaged_plan_selector();
			break;
			case "view_invoice_app1":
			case "view_invoice_app":
			case "view_invoice":
				$returned_value = $this->_view_invoice();
			break;
			case "display_all_financial_reports_full_view":
				$returned_value = $this->_display_all_financial_reports_full_view();
			break;
			case 'get_users_bar_chart':
			case 'get_users_pie_chart':
				$returned_value = $this->_get_users_pie_chart();
			break;
			case 'refresh_sales_info':
				$returned_value = $this->_refresh_sales_info();
			break;
			case 'save_sales_and_return_receipt':
				$returned_value = $this->_save_sales_and_return_receipt();
			break;
			case 'display_app_sales_report':
				$returned_value = $this->_display_app_sales_report();
			break;
			case 'update_sales_status':
				$returned_value = $this->_update_sales_status();
			break;
			case 'display_app_reports_full_view':
			case 'display_all_reports_full_view':
				$returned_value = $this->_display_all_reports_full_view();
			break;
			case "generate_app_sales_report":
			case 'generate_sales_report':
				$returned_value = $this->_generate_sales_report();
			break;
			case 'search_all_sales_record':
			case 'search_sales_record':
			case 'search_sales_record2':
			case 'search_sales_record3':
				$returned_value = $this->_search_sales_record();
			break;
			case 'update_table_field':
				$returned_value = $this->_update_table_field();
			break;
			case 'delete_app_sales':
				$returned_value = $this->_delete_app_sales();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'refund_customer':
				$returned_value = $this->_refund_customer();
			break;
			case 'get_all_sales_and_payment_by_customer':
				$returned_value = $this->_get_all_sales_and_payment_by_customer();
			break;
			}
			
			return $returned_value;
		}
		
		private function _refund_customer(){
			if( ! ( isset( $_POST["id"] ) && doubleval( $_POST["id"] ) ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Amount to Refund Customer';
				return $err->error();
			}
			
			if( ! ( isset( $_GET["customer"] ) && $_GET["customer"] && isset( $_GET["store"] ) && $_GET["store"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Customer Details';
				return $err->error();
			}
			
			$sales_id = get_new_id();
			$store = $_GET["store"];
			$customer = $_GET["customer"];
			$amount = doubleval( $_POST["id"] );
			
			$this->class_settings["sales_id"] = $sales_id;
			$this->class_settings["sales"] = array(
				"amount_due" => $amount,
				"amount_paid" => $amount,
				"discount" => 0,
				"customer" => $customer,
				"quantity" => 1,
				"sales_status" => "sold",
				"store" => $store,
				"staff_responsible" => $this->class_settings["user_id"],
				"comment" => "Customer Refund",
				"payment_method" => "cash",
			);
			
			$sales_items = new cSales_items();
			$sales_items->class_settings = $this->class_settings;
			
			$sales_items->class_settings["sales_id"] = $sales_id;
			$sales_items->class_settings["sales_items"] = array(
				0 => array(
					"id" => "refund",
					"price" => $amount,
					"quantity" => 1,
					"total" => $amount,
				),
			);
			$sales_items->class_settings["action_to_perform"] = "save_sales_items";
			$sales_items->sales_items();
			
			$replace = 0;
			if( isset( $_POST["mod"] ) && $_POST["mod"] ){
				$this->class_settings["action_to_perform"] = "view_invoice_app";
			}
			$return = $this->_save_sales_and_return_receipt();
			
			$return["html_replacement_selector"] = "#invoice-container-wrapper";
			return $return;
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
			$this->_get_sales();
		}
		
		private function _delete_app_sales(){
			$_POST["mod"] = "delete-" . md5( $this->table_name );
			return $this->_delete_records();
		}
		
		private function _search_sales_record(){
			$where = "";
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'search_sales_record3':
			case 'search_sales_record2':
				if( isset( $this->class_settings[ "customer" ] ) && $this->class_settings[ "customer" ] && isset( $this->class_settings[ "end_date" ] ) && $this->class_settings[ "end_date" ] && isset( $this->class_settings[ "start_date" ] ) && $this->class_settings[ "start_date" ] ){
					$where = " `".$this->table_name."`.`record_status` = '1' AND `".$this->table_name."`.`".$this->table_fields["customer"]."` = '" . $this->class_settings[ "customer" ] . "' AND `".$this->table_name."`.`".$this->table_fields["date"]."` >= ".$this->class_settings[ "start_date" ]." AND `".$this->table_name."`.`".$this->table_fields["date"]."` <= ( ".$this->class_settings[ "end_date" ]." + ( 23 * 3600 ) ) ";
				}
			break;
			default:
				if( isset( $_POST["receipt_num"] ) && $_POST["receipt_num"] ){
					$where .= " `".$this->table_name."`.`record_status` = '1' AND `".$this->table_name."`.`serial_num` = ".$_POST["receipt_num"];
				}
				
				if( isset( $_POST["customer"] ) && $_POST["customer"] ){
					if( $where )$where .= " OR ( `".$this->table_name."`.`record_status` = '1' AND `".$this->table_name."`.`".$this->table_fields["customer"]."` = '".$_POST["customer"] . "' ) ";
					else $where = " `".$this->table_name."`.`record_status` = '1' AND `".$this->table_name."`.`".$this->table_fields["customer"]."` = '".$_POST["customer"] . "' ";
				}
			break;
			}
			
			if( $where ){
				
				if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$where .= " AND `".$this->table_name."`.`".$this->table_fields[ 'store' ]."` = '" . $_SESSION[ "store" ] . "' ";
				}
				
				$select = "";
				
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "amount_due":
					case "amount_paid":
					case "cost":
					case "balance":
					case "quantity":
					case "discount_type":
					break;
					default:
						if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
						else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
					break;
					}
				}
				
				$sales_items = new cSales_items();
				$payment = new cPayment();
				
				$group = "";
				switch ( $this->class_settings['action_to_perform'] ){
				case 'search_sales_record2':
					$select .= ", `".$sales_items->table_name."`.`".$sales_items->table_fields["quantity"]."` as 'quantity', `".$sales_items->table_name."`.`".$sales_items->table_fields["quantity_returned"]."` as 'quantity_returned', `".$sales_items->table_name."`.`".$sales_items->table_fields["cost"]."` as 'cost', `".$sales_items->table_name."`.`".$sales_items->table_fields["item_id"]."` as 'item_id', `".$sales_items->table_name."`.`".$sales_items->table_fields["discount"]."` as 'discount' ";
					
					$group = " GROUP BY `".$sales_items->table_name."`.`id` ";
				break;
				default:
					$select .= ", SUM( `".$sales_items->table_name."`.`".$sales_items->table_fields["quantity"]."` - `".$sales_items->table_name."`.`".$sales_items->table_fields["quantity_returned"]."` ) as 'quantity', SUM( ( (`".$sales_items->table_name."`.`".$sales_items->table_fields["quantity"]."` - `".$sales_items->table_name."`.`".$sales_items->table_fields["quantity_returned"]."`) * `".$sales_items->table_name."`.`".$sales_items->table_fields["cost"]."` ) - `".$sales_items->table_name."`.`".$sales_items->table_fields["discount"]."` ) as 'amount_due' ";
					
					$where .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["item_id"]."` != 'refund' ";
					
					$group = " GROUP BY `".$this->table_name."`.`id` ";
				break;
				}
				
				$select .= ", ( SELECT SUM( `".$payment->table_name."`.`".$payment->table_fields['amount_paid']."` ) FROM `" . $this->class_settings['database_name'] . "`.`".$payment->table_name."` WHERE `".$payment->table_name."`.`".$payment->table_fields['sales_id']."` = `".$this->table_name."`.`id` AND `".$payment->table_name."`.`record_status` = '1' ) as 'amount_paid' ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$sales_items->table_name."` ON `".$this->table_name."`.`id` = `".$sales_items->table_name."`.`".$sales_items->table_fields['sales_id']."` WHERE ".$where." ".$group." ORDER BY `".$this->table_name."`.`".$this->table_fields[ 'date' ]."` ASC ";
					
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 0,
					'tables' => array( $this->table_name, $sales_items->table_name, $payment->table_name ),
				);
				$sales = execute_sql_query($query_settings);
				
				switch ( $this->class_settings['action_to_perform'] ){
				case 'search_sales_record3':
				case 'search_sales_record2':
					return $sales;
				break;
				}
				
				if( ! empty( $sales ) ){
					$this->class_settings[ 'data' ][ "sales_record" ] = $sales;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/sales-record.php' );
					
					switch ( $this->class_settings['action_to_perform'] ){
					case 'search_all_sales_record':
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/all-sales-record.php' );
					break;
					}
					
					$returning_html_data = $this->_get_html_view();
					return array(
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
					$err->additional_details_of_error = '<h4>No Record Found</h4>Please check your receipt number / customer name';
					$r = $err->error();
					
					$r["status"] = "new-status";
					$r["html_replacement_selector"] = "#sales-record-search-result";
					$r["html_replacement"] = $r["html"];
					
					unset( $r["html"] );
					
					return $r;
				}
				
			}
			
		}
		
		private function _generate_sales_report(){
			$returning_html_data = "";
			
			$field_name = "date";
			$initial_where = " `".$this->table_name."`.`record_status`='1' ";
			$where = $initial_where;
			
			$title = "";
			$select = "";
			$grouping = 1;
			
			$regard_store = 1;
			
			switch( $this->class_settings["action_to_perform"] ){
			case "generate_app_sales_report":
			break;
			case "generate_sales_report":
				$regard_store = 0;
			break;
			}
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "amount_due":
				case "amount_paid":
				case "cost":
				case "balance":
				case "quantity":
				case "discount_type":
				case "payment_method":
				case "comment":
				break;
				default:
					if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
					else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
				break;
				}
			}
			
			$report_type = "periodic_sales_report";
			//$report_type = "production_report";
			if( isset( $_GET["department"] ) && $_GET["department"] ){
				$report_type = $_GET["department"];
			}
			
			$start_date_timestamp = 0;
			if( isset( $_GET["start_date"] ) && $_GET["start_date"] ){
				$st = explode( "-", $_GET["start_date"] );
				if( isset( $st[2] ) ){
					$start_date_timestamp = mktime( 0,0,0, $st[1], $st[2], $st[0] );
				}
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
			$skip_pen_val = 0;
			
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
			case "individual":
				$date_filter = "d-M-Y";
				$grouping = 50;
			break;
			}
			
			switch( $report_type ){
			case 'part_payment_sales_report':
			case 'all_debtors_sales_report':
			case "unpaid_sales_report":
				$skip_joins = 0;
				$skip_pen_val = 1;
				$grouping = 20;
				$do_group_items = 1;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_sales_report_types" ) );
			break;
			case 'most_profitable_item_report':
			case 'most_sold_item_report':
				$skip_pen_val = 1;
				$skip_joins = 0;
				$grouping = 20;
				$do_group_items = 3;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_sales_report_types" ) );
			break;
			case 'most_valued_customers_report':
			case 'customers_owing_report':
			case "customers_transaction_report":
				$skip_pen_val = 1;
				$skip_joins = 0;
				$grouping = 20;
				$do_group_items = 2;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_sales_report_types" ) );
			break;
			case "periodic_sales_report_data_only":
			case "periodic_sales_report":
				$skip_joins = 0;
				$do_group_items = 1;
				$title = "PERIODIC SALES REPORT";
			break;
			case "today_sales_report":
				$skip_joins = 0;
				$skip_pen_val = 1;
				$grouping = 20;
				$do_group_items = 1;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_sales_report_types" ) );
				
				$end_date = $start_date + ( 24 * 3600 ) - 1;
				$end_date_timestamp = $end_date;
			break;
			}
			
			$subtitle = "";
			
			if( $start_date ){
				$subtitle .= "From: <strong>" . date( "d-M-Y", doubleval( $start_date ) ) . "</strong> ";
			}
			
			if( $end_date ){
				$subtitle .= " To: <strong>" . date( "d-M-Y", doubleval( $end_date ) ) . "</strong>";
			}
			
			if( $where ){
				
				$all_data = array();
				
				if( ! $skip_joins ){
					//Egg Sales
					//$sales_record = new cSales();
					$sales_items = new cSales_items();
					$payment = new cPayment();
					
					$group_items = "";
					switch( $do_group_items ){
					case 1:
						$group_items = " `".$this->table_name."`.`id`, `".$this->table_name."`.`".$this->table_fields[ 'date' ]."` ";
					break;
					case 2:
						$group_items = " `".$this->table_name."`.`".$this->table_fields['customer']."`, `".$this->table_name."`.`".$this->table_fields[ 'date' ]."` ";
					break;
					case 3:
						$group_items =  " `".$sales_items->table_name."`.`".$sales_items->table_fields['item_id']."`, `".$this->table_name."`.`".$this->table_fields[ 'date' ]."` ";
					break;
					}
					//$group_items = " `".$sales_items->table_name."`.`".$sales_items->table_fields['item_id']."`, ";
					
					
					$where2 = "";
					if( $start_date )
						$where2 .= " `".$this->table_name."`.`".$this->table_fields["date"]."` >= " . $start_date;
					
					if( $end_date ){
						if( $where2 )$where2 .= " AND `".$this->table_name."`.`".$this->table_fields["date"]."` <= " . $end_date;
						else $where2 .= " `".$this->table_name."`.`".$this->table_fields["date"]."` <= " . $end_date;
					}
					
					$where1 = " `".$this->table_name."`.`record_status`='1' AND `".$sales_items->table_name."`.`record_status`='1' ";
					
					if( $regard_store && isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
						$where1 .= " AND `".$this->table_name."`.`".$this->table_fields[ 'store' ]."` = '" . $_SESSION[ "store" ] . "' ";
					}
					
					$pen_val = "";
					if( ( ! $skip_pen_val ) && isset( $_GET["operator"] ) && $_GET["operator"] ){
						$p = get_items_details( array( "id" => $_GET["operator"] ) );
						if( isset( $p[ "id" ] ) ){
							$title .= "<br /><strong>".ucwords( $p[ "description" ] )."</strong>";
							$pen_val = $p[ "id" ];
						}
					}
					if( $pen_val ){
						$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["item_id"]."` = '".$pen_val."' ";
					}else{
						if( $pen_required ){
							$error_file = "select-pen-message.php";
							$where = "";
						}
					}
					
					if( $where2 )$where1 = " AND " . $where1;
					
					$where = " ( " . $where2 . $where1 . " ) ";
					
					$select .= ", ( `".$sales_items->table_name."`.`".$sales_items->table_fields['item_id']."` ) as 'item', SUM( `".$sales_items->table_name."`.`".$sales_items->table_fields['quantity']."` - `".$sales_items->table_name."`.`".$sales_items->table_fields['quantity_returned']."` ) as 'quantity_sold', SUM( ( (`".$sales_items->table_name."`.`".$sales_items->table_fields['quantity']."` - `".$sales_items->table_name."`.`".$sales_items->table_fields['quantity_returned']."`) * `".$sales_items->table_name."`.`".$sales_items->table_fields['cost']."` ) - `".$sales_items->table_name."`.`".$sales_items->table_fields['discount']."` ) as 'amount_due', ( SELECT SUM( `".$payment->table_name."`.`".$payment->table_fields['amount_paid']."` ) FROM `" . $this->class_settings['database_name'] . "`.`".$payment->table_name."` WHERE `".$payment->table_name."`.`".$payment->table_fields['sales_id']."` = `".$this->table_name."`.`id` AND `".$payment->table_name."`.`record_status` = '1' ) as 'amount_paid' ";
					
					$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$sales_items->table_name."` ON `".$this->table_name."`.`id` = `".$sales_items->table_name."`.`".$sales_items->table_fields['sales_id']."` WHERE ".$where." GROUP BY ".$group_items." ORDER BY `".$this->table_name."`.`".$this->table_fields[ 'date' ]."` DESC ";
					
					//echo $query; exit;
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 0,
						'tables' => array( $this->table_name, $sales_items->table_name, $payment->table_name ),
					);
					$sales = execute_sql_query($query_settings);
					
				}
				
				//print_r($sales); exit;
				switch( $skip_joins ){
				case 1:
					$all_data = $sales;
				break;
				default:
					$all_data = $sales; //$all_data = array_merge( $farm_daily_record, $sales );
				break;
				}
				
				if( empty( $all_data ) ){
					
					$error_file = "error-message.php";
					$this->class_settings[ 'data' ][ "start_date" ] = $start_date;
					$this->class_settings[ 'data' ][ "end_date" ] = $end_date;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$error_file );
					$returning_html_data = $this->_get_html_view();
					
					return array(
						'do_not_reload_table' => 1,
						'html_replacement' => $returning_html_data,
						'html_replacement_selector' => "#data-table-section",
						'method_executed' => $this->class_settings['action_to_perform'],
						'status' => 'new-status',
					);
				}
				
				//print_r($all_data); exit;
				switch( $grouping ){
				case 3:
					//group data based on weeks
					$all_new = array();
					$birds = 0;
					$pen_start_date = array();
					$perf_obj = array();
					
					$std = 0;
					switch( $report_age ){
					case "standard":
						$std = 1;
					break;
					}
					$days = 7;
					
					foreach( $all_data as & $sval ){
						
						$key = floor( ( doubleval( $sval["date"] ) ) / ( 86400 * $days ) );
						$sval["age"] = $key;
						//$key = $sval[ "start_date" ];
						
						if( isset( $all_new[ $key ] ) ){
							foreach( $sval as $k => $v ){
								switch( $k ){
								case "body_weight":
								case "egg_weight":
								case "type_of_feed":
								case "drug_administered":
								case "number_of_animals":
								case "std_percentage_mortality_cumm":
								case "std_production":
								case "std_feed_intake":
								case "std_body_weight":
								break;
								case "age":
								case "serial_num":
								case "id":
								case "start_date":
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
					$all_data = $all_new;
					
					switch( $report_view ){
					case "graphical":
						//return production chart
						$this->class_settings["chart_data"]["highchart_container_selector"] = "#data-table-section";
						$this->class_settings["chart_data"]["data"] = $all_data;
						$this->class_settings["chart_data"]["days"] = $days;
						
						$this->class_settings["chart_data"]["highchart_data"] = basic_column_chart();
						$this->class_settings["chart_data"]["highchart_data"]["title"]["text"] = get_select_option_value( array( "id" => $report_age, "function_name" => "get_birds_age" ) ) . " Layers Performance Chart";
						
						$this->class_settings["chart_data"]["highchart_data"]["xAxis"]["title"]["text"] = "Age in " . get_select_option_value( array( "id" => $report_age, "function_name" => "get_birds_age_text" ) );
						
						$this->class_settings["chart_data"]["highchart_data"]["subtitle"]["text"] = $title;
						
						return $this->_get_weekly_production_line_graph();
					break;
					}
				break;
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
								case "item":
								case "date":
								break;
								case "serial_num":
								case "id":
									$all_new[ $key ][ $k ] = "*Several";
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
				
				switch ( $this->class_settings['action_to_perform'] ){
				case "periodic_sales_report_data_only":
				case 'get_layers_performance_chart':
					return array(
						'report_title' => $title,
						'report_data' => $all_data,
					);
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
				//'javascript_functions' => array( 'activate_tree_view', 'set_function_click_event' ) 
			);
		}
		
		private function _display_all_reports_full_view(){
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-reports-full-view' );
			
			$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
			
			$this->class_settings[ 'data' ][ 'report_type2' ] = get_items_grouped_goods();
			$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-items";
			
			$this->class_settings[ 'data' ][ 'report_type3' ] = get_report_periods_without_weeks();
			$this->class_settings[ 'data' ][ 'selected_option3' ] = "monthly";
			
			$this->class_settings[ 'data' ][ 'report_type5' ] = get_sales_report_types();
			$this->class_settings[ 'data' ][ 'selected_option5' ] = "periodic_sales_report";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'display_app_reports_full_view':
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_app_sales_report";
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'html_replacement_selector' =>  "#dash-board-main-content-area",
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ) 
				);
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
		
		private function _delete_sales_manifest(){
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
				$this->class_settings["sales_id"] = $return['deleted_record_id'];
				
				//delete materials
				$sales_items = new cSales_items();
				$sales_items->class_settings = $this->class_settings;
				$sales_items->class_settings["action_to_perform"] = 'delete_materials';
				$sales_items->sales_items();
				
				//delete inventory
				$inventory = new cInventory();
				$inventory->class_settings = $this->class_settings;
				$inventory->class_settings["action_to_perform"] = 'delete_goods_produced';
				$inventory->inventory();
				
				$return["html_removal"] = "#" . $return['deleted_record_id'] ;
				
				$return["html_replace_selector"] = "#manifest-" . $return['deleted_record_id'];
				
				$project = get_project_data();
				$return["html_replace"] = '<div style="text-align:center;"><img src="'.$project['domain_name'].'frontend-assets/img/logo_blue.png" alt="" align="center"></div>';
			}
			
			unset( $return["html"] );
			$return["status"] = "new-status";
			
			return $return;
		}
		
		private function _update_sales_status(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$this->class_settings["sales_id"] = $this->class_settings["current_record_id"];
			$e["event"] = $this->_get_sales();
			
			$this->class_settings["date"] = $e["event"]["date"];
			$this->class_settings["store"] = $e["event"]["store"];
			
			$_POST["mod"] = "edit-".md5( $this->table_name );
			
			$this->class_settings["do_not_show_headings"] = 1;
			
			$this->class_settings["hidden_records"][ $this->table_fields["date"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["discount"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["amount_due"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["balance"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["amount_paid"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["discount_type"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["quantity"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["cost"] ] = 1;
			
			$this->class_settings[ 'form_action_todo' ] = 'save_update_sales_status';
			
			$e["sales_form"] = $this->_generate_new_data_capture_form();
			
			$sales_items = new cSales_items();
			$sales_items->class_settings = $this->class_settings;
			$sales_items->class_settings["action_to_perform"] = 'view_all_sales_items_editable';
			$e['items_sold'] = $sales_items->sales_items();
			
			$payment = new cPayment();
			$payment->class_settings = $this->class_settings;
			$payment->class_settings["action_to_perform"] = 'view_all_payments_editable';
			$e['payment'] = $payment->payment();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/sales-status-update.php' );
			$this->class_settings[ 'data' ] = $e;
			$this->class_settings[ 'data' ]["backend"] = 1;
			$html = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = "Update Sales Status";
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( "prepare_new_record_form_new" , "set_function_click_event" ),
			);
			
		}
		
		private function _display_app_sales_report(){
			
			$this->class_settings['action_to_perform'] = "periodic_sales_report_data_only";
			
			$mon = date("n");
			if( $mon == 1 ){
				$_GET["start_date"] = ( date("Y") - 1 ) . "-12-1";
			}else{
				$_GET["start_date"] = date("Y") . "-" . ( $mon - 1 ) . "-1";
			}
			
			$_GET["end_date"] = date("Y-n-t");
			$_GET["budget"] = "individual";
			$_GET["department"] = "periodic_sales_report_data_only";
			$this->class_settings[ 'data' ][ 'recent_expenses' ] = $this->_generate_sales_report();
			
			$_GET["budget"] = "daily";
			$_GET["start_date"] = date("Y-m-1");
			$_GET["end_date"] = date("Y-m-t");
			$this->class_settings[ 'data' ][ 'this_month' ] = $this->_generate_sales_report();
			
			$_GET["budget"] = "monthly";
			$_GET["start_date"] = date("Y-1-1");
			$_GET["end_date"] = date("Y-12-31");
			$this->class_settings[ 'data' ][ 'this_year' ] = $this->_generate_sales_report();
			
			$file = "display-app-sales";
			$selector = "#dash-board-main-content-area";
			$js = array();
			
			$js = array( 'set_function_click_event' );
			
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
		
		private function _save_sales_and_return_receipt(){
			
			if( ! ( isset( $this->class_settings["sales"] ) && is_array( $this->class_settings["sales"] ) ) ){
				return 0;
			}
			if( ! ( isset( $this->class_settings[ 'sales_id' ] ) ) ){
				return 0;
			}
			
			$array_of_dataset = array();
			
			$new_record_id = $this->class_settings[ 'sales_id' ];
			
			$date = date("U");
			if( isset( $this->class_settings["sales"]["date"] ) && $this->class_settings["sales"]["date"] ){
				$d = convert_date_to_timestamp( $this->class_settings["sales"]["date"] );
				if( $d ){
					$date = $d;
				}
			}
			
			$ip_address = get_ip_address();
			
			$dataset_to_be_inserted = array(
				'id' => $new_record_id,
				'created_role' => $this->class_settings[ 'priv_id' ],
				'created_by' => $this->class_settings[ 'user_id' ],
				'creation_date' => $date,
				'modified_by' => $this->class_settings[ 'user_id' ],
				'modification_date' => $date,
				'ip_address' => $ip_address,
				'record_status' => 1,
				
				$this->table_fields["date"] => $date,
				
				$this->table_fields["cost"] => $this->class_settings["sales"]["amount_due"],
				$this->table_fields["quantity"] => $this->class_settings["sales"]["quantity"],
				$this->table_fields["amount_paid"] => $this->class_settings["sales"]["amount_paid"],
				$this->table_fields["discount"] => $this->class_settings["sales"]["discount"],
				$this->table_fields["customer"] => $this->class_settings["sales"]["customer"],
				$this->table_fields["sales_status"] => $this->class_settings["sales"]["sales_status"],
				$this->table_fields["store"] => $this->class_settings["sales"]["store"],
				$this->table_fields["staff_responsible"] => $this->class_settings["sales"]["staff_responsible"],
				$this->table_fields["comment"] => $this->class_settings["sales"]["comment"],
				
				$this->table_fields["payment_method"] => $this->class_settings["sales"]['payment_method'],
				
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
			$return["javascript_functions"][] = "nwCart.emptyCart";
			
			return $return;
		}
		
		private function _get_users_pie_chart(){
			
			$start_date = mktime( 0,0,0, 1, 1, date("Y") );
			$end_date = mktime( 0,0,0, 12, 31 , date("Y") );
			
			$event_items = new cSales_items();
			$select = ", ( Select SUM( ( `".$event_items->table_name."`.`".$event_items->table_fields["cost"]."` * `".$event_items->table_name."`.`".$event_items->table_fields["quantity"]."` ) - `".$event_items->table_name."`.`".$event_items->table_fields["discount"]."` ) FROM `".$this->class_settings['database_name']."`.`".$event_items->table_name."` WHERE `".$event_items->table_name."`.`".$event_items->table_fields["sales_id"]."` = `".$this->table_name."`.`id` AND `".$event_items->table_name."`.`record_status` = '1' ) AS 'OTHER_ITEMS_PRICE' ";
			
			$query = "SELECT ( `".$this->table_fields["cost"]."` - `".$this->table_fields["discount"]."` ) as 'total', `".$this->table_fields["date"]."` as 'date' ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND ( `".$this->table_fields["date"]."` >= ".$start_date." AND `".$this->table_fields["date"]."` <= ".$end_date." ) ";
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
			
			$rec = array();
			
			$expenditure = new cExpenditure();
			$expenditure->class_settings = $this->class_settings;
			$expenditure->class_settings[ 'action_to_perform' ] = 'get_monthly_expenses';
			$di = $expenditure->expenditure();
			
			if( isset( $di ) && ! empty( $di ) && is_array( $di ) ){
				$rec["Expenditure"] = $di;
			}
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				$data = array();
				$total = 0;
				
				foreach( $sql_result as $sval ){
					$d = date( "F", doubleval( $sval["date"] ) );
					
					if( isset( $rec["Revenue Generated"][ $d ] ) )
						$rec["Revenue Generated"][ $d ] += doubleval( $sval["total"] );
					else
						$rec["Revenue Generated"][ $d ] = doubleval( $sval["total"] );
					
					//if( isset( $rec["Revenue Generated"][ $d ] ) )
						//$rec["Revenue Generated"][ $d ] += doubleval( $sval["OTHER_ITEMS_PRICE"] );
					//else
						//$rec["Revenue Generated"][ $d ] = doubleval( $sval["OTHER_ITEMS_PRICE"] );
						
				}
			}
			
			if( ! empty( $rec ) ){
				$m1 = array();
				$months = get_months_of_year();
				$x_axis = array();
				foreach( $months as $ki => $month ){
					$m1[ $month ] = $ki;
					$x_axis[] = $month;
				}
				
				//$reg = get_types_of_expenditure();
				
				$data1 = array();
				foreach( $rec as $name => $sval ){
					//if( $sval["total"] && isset( $reg[ $sval["payment_type"] ] ) ){
						
						$push = array();
						foreach( $m1 as $m2 => $m3 ){
							if( isset( $sval[ $m2 ] ) )
								$push[] = doubleval( $sval[ $m2 ] );
							else
								$push[] = 0;
						}
						
						$data1[] = array(
							"name" => $name,
							"data" => $push,
						);
					//}
				}
				
				if( $piechart ){
					$return["highchart_data"] = pie_legend_chart();
					$return["highchart_data"]["subtitle"]["text"] = date("F, Y");
					$return["highchart_data"]["series"][0]["data"] = $data;
				}else{
					$return["highchart_data"] = basic_column_chart();
					$return["highchart_data"]["subtitle"]["text"] = date("Y");
					$return["highchart_data"]["xAxis"]["categories"] = $x_axis;
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
				
				if( $piechart ){					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = 1;
					$return['action'] = '?action=sales_record&todo=get_users_bar_chart';
				}
			
				return $return;
			}
		}
		
		private function _display_all_financial_reports_full_view(){
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-financial-reports-full-view' );
			
			$this->class_settings[ 'data' ][ 'report_type' ] = get_calendar_years();
			$this->class_settings[ 'data' ][ 'selected_option' ] = date("Y");
			
			$m = get_months_of_year();
			//$m["all-months"] = "All Months";
			
			$this->class_settings[ 'data' ][ 'report_type1' ] = $m;
			$this->class_settings[ 'data' ][ 'selected_option1' ] = "all-months";
			
			$this->class_settings[ 'data' ][ 'report_type2' ] = get_types_of_income();
			$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-income";
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _track_invoice(){
			$return["html_replacement_selector"] = "#dash-board-main-content-area";
			
			$error = '<div class="note note-danger"><h4 class="block">Invalid Invoice Number</h4><p>Please Provide a valid invoice number </p></div>';
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				
				$return['html_replacement'] = $error;
				$return['status'] = "new-status";
				return $return;
			}
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$event = $this->_get_sales();
			
			if( ! ( isset( $event["id"] ) && $event["id"] ) ){
				$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `serial_num` = ".$this->class_settings[ 'current_record_id' ];
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$all_data = execute_sql_query($query_settings);
				if( isset( $all_data[0]["id"] ) && $all_data[0]["id"] ){
					$this->class_settings["current_record_id"] = $all_data[0]["id"];
					$event = $this->_get_sales();
				}
			}
			
			if( ! ( isset( $event["id"] ) && $event["id"] ) ){
				$return['html_replacement'] = $error;
				$return['status'] = "new-status";
				return $return;
			}
			
			$return['status'] = "new-status";
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/book-event-success-message.php' );
			$this->class_settings[ 'data' ]["hide_info"] = 1;
			$this->class_settings[ 'data' ]["event"] = $event;
			$return["html_replacement"] = $this->_get_html_view();
			
			
			return $return;
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
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$this->class_settings["sales_id"] = $this->class_settings["current_record_id"];
			$e["event"] = $this->_get_sales();
			
			$sales_items = new cSales_items();
			$sales_items->class_settings = $this->class_settings;
			$sales_items->class_settings["action_to_perform"] = "get_specific_sales_items";
			$e['event_items'] = $sales_items->sales_items();
			
			$payment = new cPayment();
			$payment->class_settings = $this->class_settings;
			$payment->class_settings["action_to_perform"] = 'get_total_amount_paid';
			$e['payment'] = $payment->payment();
			
			if( isset( $e["event"]["customer"] ) && $e["event"]["customer"] ){
				$this->class_settings["customer"] = $e["event"]["customer"];
				$e['all_transactions'] = $this->_get_all_sales_and_payment_by_customer();
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/invoice.php' );
			$this->class_settings[ 'data' ] = $e;
			$this->class_settings[ 'data' ]["backend"] = 1;
			
			if( isset( $this->class_settings["hide_buttons"] ) )
				$this->class_settings[ 'data' ]["hide_buttons"] = 1;
			
			switch( $this->class_settings["action_to_perform"] ){
			case "view_invoice_app":
			case "view_invoice_app1":
				$this->class_settings[ 'data' ]["hide_buttons"] = 1;
			break;
			}
			
			if( isset( $this->class_settings["show_small_invoice"] ) )
				$this->class_settings[ 'data' ]["show_small_invoice"] = 1;
			
			if( isset( $this->class_settings["show_print_button"] ) )
				$this->class_settings[ 'data' ]["backend"] = 0;
			
			$this->class_settings[ 'data' ]["show_item_image"] = get_general_settings_value( array( "key" => "SHOW PICTURES OF ITEMS IN SALES RECEIPT", "table" => $this->table_name ) );
			
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
			
			if( isset( $_GET["modal"] ) && $_GET["modal"] ){
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => "#modal-replacement-handle",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ),
				);
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = "Sales Receipt";
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
		
		private function _get_all_sales_and_payment_by_customer(){
			if( isset( $this->class_settings["customer"] ) && $this->class_settings["customer"] ){
				if( defined( "HYELLA_PACKAGE" ) ){
					switch( HYELLA_PACKAGE ){
					case "hotel":
						return 0;
					break;
					}
				}
				
				$sales_items = new cSales_items();
				$payment = new cPayment();
				
				$group_items = " `".$this->table_name."`.`".$this->table_fields[ 'customer' ]."`, `".$this->table_name."`.`id` ";
				$where = " `".$this->table_name."`.`record_status`='1' AND `".$this->table_name."`.`".$this->table_fields["customer"]."` = '".$this->class_settings["customer"]."' ";
				
				$select = " SUM( (`".$sales_items->table_name."`.`".$sales_items->table_fields['quantity']."` - `".$sales_items->table_name."`.`".$sales_items->table_fields['quantity_returned']."`) * `".$sales_items->table_name."`.`".$sales_items->table_fields['cost']."` ) as 'amount_due', SUM( `".$this->table_name."`.`".$this->table_fields[ 'discount' ]."` ) as 'discount' ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$sales_items->table_name."` ON `".$sales_items->table_name."`.`".$sales_items->table_fields['sales_id']."` = `".$this->table_name."`.`id` WHERE ".$where."  AND `".$sales_items->table_name."`.`record_status` = '1'  ";
				
				//echo $query; exit;
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 0,
					'tables' => array( $this->table_name, $sales_items->table_name ),
				);
				$sales = execute_sql_query($query_settings);
				
				$query = "SELECT SUM( `".$payment->table_name."`.`".$payment->table_fields['amount_paid']."` ) as 'amount_paid' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$payment->table_name."` ON `".$payment->table_name."`.`".$payment->table_fields['sales_id']."` = `".$this->table_name."`.`id` WHERE `".$payment->table_name."`.`record_status` = '1' AND " . $where;
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 0,
					'tables' => array( $this->table_name, $payment->table_name ),
				);
				$sales2 = execute_sql_query($query_settings);
				
				if( isset( $sales[0]["amount_due"] ) )
					$sales[0]["amount_paid"] = 0;
				
				if( isset( $sales2[0]["amount_paid"] ) )
					$sales[0]["amount_paid"] = $sales2[0]["amount_paid"];
				
				if( isset( $sales[0][ "amount_due" ] ) && isset( $sales[0][ "amount_paid" ] ) ){
					return array( "amount_due" => $sales[0][ "amount_due" ], "amount_paid" => $sales[0][ "amount_paid" ], "discount" => $sales[0][ "discount" ] );
				}
				
			}
		}
		
		private function _cancel_sale(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Event</h4>Please Select an Event to Cancel';
				return $err->error();
			}
			$sales_id = $_POST["id"];
			
			$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `id`='".$sales_id."'"; 
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'DELETE',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			execute_sql_query($query_settings);
			
			$_GET["month"] = "monthly";
			$this->class_settings['action_to_perform'] = "display_calendar_view_mobile";
			return $this->_display_calendar_view();
		}
		
		private function _refresh_sales_info(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Sales Record</h4>Please Select a Sales Record to Refresh';
				return $err->error();
			}
			$sales_id = $_POST["id"];
			$this->class_settings["sales_id"] = $sales_id;
			
			//get sales items
			$sales_items = new cSales_items();
			$sales_items->class_settings = $this->class_settings;
			$sales_items->class_settings["action_to_perform"] = "get_specific_sales_items";
			$items = $sales_items->sales_items();
			
			$units = 0;
			$cost = 0;
			$discount = 0;
			
			if( is_array( $items ) && ! empty( $items ) ){
				foreach( $items as $item ){
					$units += ( $item["quantity"] - $item["quantity_returned"] );
					$cost += ( ( $item["quantity"] - $item["quantity_returned"] ) * $item["cost"] );
					$discount += doubleval( $item["discount"] );
				}
			}
			
			$this->class_settings["update_fields"][ "quantity" ] = $units;
			$this->class_settings["update_fields"][ "cost" ] = $cost - $discount;
			return $this->_update_table_field();
		}
		
		private function _conclude_booking(){
			$return = array();
			if( ( isset( $_GET["department"] ) && $_GET["department"] ) ){
				$hall = $_GET["department"];
				
				$this->class_settings["current_record_id"] = $hall;
				$event = $this->_get_sales();
			}
			
			if( ! ( isset( $event["id"] ) ) ){
				//error message
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Booking</h4>Please try again';
				return $err->error();
			}
			
			$return['status'] = "new-status";
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/book-event-success-message.php' );
			$this->class_settings[ 'data' ]["event"] = $event;
			$return["html_replacement"] = $this->_get_html_view();
			$return["html_replacement_selector"] = "#dash-board-main-content-area";
			
			return $return;
		}
		
		private function _save_site_changes(){
			//save event & generate invoice
			$return = $this->_save_changes();
			
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] && isset( $return["event_details"]["id"] ) ){
				$return['status'] = "new-status";
				
				unset( $return['html'] );
				
				switch( $this->class_settings["action_to_perform"] ){
				case "save_book_event_add_items":
					$this->class_settings[ 'data' ]["other_items"] = $return["event_details"]["id"];
				break;
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/book-event-success-message.php' );
				$this->class_settings[ 'data' ]["event"] = $return["event_details"];
				$return["html_replacement"] = $this->_get_html_view();
				$return["html_replacement_selector"] = "#dash-board-main-content-area";
				
				$return["javascript_functions"] = array( "set_function_click_event" );
				
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
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->datatable_settings["custom_edit_button"] = $this->_get_html_view();
			
			$_SESSION[ $this->table_name ]['order_by'] = " ORDER BY `".$this->table_name."`.`".$this->table_fields["date"]."` DESC ";
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Sales";
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
				$this->class_settings['form_heading_title'] = 'Modify Sales';
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
				
				//delete sales items
				$sales_items = new cSales_items();
				$sales_items->class_settings = $this->class_settings;
				$sales_items->class_settings["action_to_perform"] = "delete_all_sales_items";
				
				$d = explode(":::", $returning_html_data['deleted_record_id'] );
				foreach( $d as $dd ){
					if( ! $dd )continue;
					
					$settings = array(
						'cache_key' => $cache_key . '-' . $dd,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					clear_cache_for_special_values( $settings );
					
					$sales_items->class_settings["sales_id"] = $dd;
					$sales_items->sales_items();
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
				
				$err->class_that_triggered_error = 'csales.php';
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
					$returning_html_data["event_details"] = $this->_get_sales();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_sales(){
			
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