<?php
	/**
	 * customer_deposits Class
	 *
	 * @used in  				customer_deposits Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	customer_deposits
	 */

	/*
	|--------------------------------------------------------------------------
	| customer_deposits Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cCustomer_deposits{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'customer_deposits';
		
		private $associated_cache_keys = array(
			'customer_deposits',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'customer_deposits001',
			'customer' => 'customer_deposits002',
			'amount_deposit' => 'customer_deposits003',
			'amount_withdrawn' => 'customer_deposits004',
			
			'payment_method' => 'customer_deposits010',
			'comment' => 'customer_deposits005',
			
			'currency' => 'customer_deposits006',
			'reference_table' => 'customer_deposits007',
			'reference' => 'customer_deposits008',
			'store' => 'customer_deposits009',
			
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => array( 
					'function-text' => 'Post Deposit / Withdrawal',
					'function-title' => 'Post New Deposit / Withdrawal' ),			//Determines whether or not to show add new record button
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
	
		function customer_deposits(){
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
			case 'charge_customer_from_deposit':
				$returned_value = $this->_charge_customer_from_deposit();
			break;
			case 'get_customer_deposit_balance':
				$returned_value = $this->_get_customer_deposit_balance();
			break;
			case 'view_balance_details':
				$returned_value = $this->_view_balance_details();
			break;
			case 'filter_customer_search_all':
				$returned_value = $this->_filter_customer_search_all();
			break;
			case 'display_all_reports_full_view':
			case 'display_app_reports_full_view':
				$returned_value = $this->_display_all_reports_full_view();
			break;
			case 'generate_app_sales_report':
			case 'generate_sales_report':
				$returned_value = $this->_generate_sales_report();
			break;
			case "new_record_popup_form":
				$returned_value = $this->_new_record_popup_form();
			break;
			case "save_new_record_popup":
				$returned_value = $this->_save_new_record_popup();
			break;
			}
			
			return $returned_value;
		}
			
		private function _save_new_record_popup(){
			$return = $this->_save_changes();
			
			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				$id = $return["saved_record_id"];
				$record = $this->_get_customer_deposits();
				
				$title = "Deposit / Withdrawal Successfully Posted";
				$more_msg = "";
				if( isset( $record["id"] ) ){
					$more_msg = "Amount Deposited: <strong>".number_format( $record["amount_deposit"], 2 )."</strong>";
					$more_msg .= "<br />Amount Withdrawn: <strong>".number_format( $record["amount_withdrawn"], 2 )."</strong>";
					$more_msg .= "<br />Comment: <strong>".$record["comment"] ."</strong>";
				}
				
				$err = new cError(010011);
				$err->html_format = 2;
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>'.$title.'</h4>' . $more_msg;
				$return = $err->error();
				
				$return["html_replacement"] = $return["html"];
				
				unset( $return["html"] );
				$return["status"] = "new-status";
				
				$return[ "html_replacement_selector" ] = "#modal-replacement-handle";
				$return[ "javascript_functions" ] = array( "nwCustomer_deposits.refresh" );
				
			}
			return $return;
		}
		
		private function _new_record_popup_form(){
			$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_new_record_popup';
			
			$customer = '';
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$customer = $_POST["id"];
			}
			$this->class_settings['form_values_important'][ $this->table_fields["customer"] ] = $customer;
			if( $customer ){
				$this->class_settings['hidden_records_css'][ $this->table_fields["customer"] ] = 1;
			}
			$this->class_settings['hidden_records_css'][ $this->table_fields["date"] ] = 1;
			
			$this->class_settings['do_not_show_headings'] = 1;
			$d = $this->_generate_new_data_capture_form();
			
			$html = "";
			if( isset( $d["html"] ) )$html = $d["html"];
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = "Post Deposit / Withdrawal";
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
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`modified_by`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			$report_type = "customer_deposit_history_report";
			if( isset( $_GET["department"] ) && $_GET["department"] ){
				$report_type = $_GET["department"];
			}
			$report_type = "customer_deposit_history_report";
			
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
			
			$where10 = "";
			$limit10 = "";
			
			switch( $report_type ){
			case 'customer_deposit_history_report':
				
				$skip_joins = 0;
				$skip_pen_val = 0;
				$grouping = 20;
				$do_group_items = 1;
				$pen_required = 1;
				
				$title = 'Deposit History';
			break;
			}
			
			$subtitle = "";
			
			if( $start_date ){
				$subtitle .= "From: <strong>" . date( "d-M-Y", doubleval( $start_date ) ) . "</strong> ";
			}
			
			if( $end_date ){
				$subtitle .= " To: <strong>" . date( "d-M-Y", doubleval( $end_date ) ) . "</strong>";
			}
			
			$pen_val = "";
			if( ( ! $skip_pen_val ) && isset( $_GET["operator"] ) && $_GET["operator"] ){
				$p = get_customers_details( array( "id" => $_GET["operator"] ) );
				if( isset( $p[ "id" ] ) ){
					$title = "<small>".ucwords( $p[ "name" ] )."</small><br />" . $title;
					$pen_val = $p[ "id" ];
				}
			}
			if( $pen_val ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["customer"]."` = '".$pen_val."' ";
			}else{
				if( $pen_required ){
					$error_file = "select-pen-message.php";
					$where = "";
				}
			}
					
			if( $where ){
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields[ 'date' ]."` DESC ";
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$all_data = execute_sql_query($query_settings);
				
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
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _display_all_reports_full_view(){
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-reports-full-view' );
			
			$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
			
			$this->class_settings[ 'data' ][ 'report_type2' ] = get_customers();
			$this->class_settings[ 'data' ][ 'selected_option2' ] = "all";
			
			$this->class_settings[ 'data' ][ 'hide_option3' ] = 1;
			
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
		
		private function _filter_customer_search_all(){
			$where = "";
			
			if( isset( $_POST["customer"] ) && $_POST["customer"] ){
				$this->class_settings["customer"] = $_POST["customer"];
			}
			
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$this->class_settings["start_date"] = convert_date_to_timestamp( $_POST["start_date"] );
			}
			
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$this->class_settings["end_date"] = convert_date_to_timestamp( $_POST["end_date"] );
			}
			
			if( isset( $this->class_settings["customer"] ) && $this->class_settings["customer"] )
				$where .= " AND `".$this->table_fields["customer"]."` = '".$this->class_settings["customer"]."' ";
			
			if( isset( $this->class_settings["start_date"] ) && doubleval( $this->class_settings["start_date"] ) ){
				$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
			}
			
			if( isset( $this->class_settings["end_date"] ) && doubleval( $this->class_settings["end_date"] ) ){
				$where .= " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
			}
			
			unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
			if( $where )$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = $where;
			
			return array(
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
			);
		}
		
		private function _view_balance_details(){
			if( ! isset( $_POST["id"] ) && $_POST["id"] ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			$this->class_settings[ 'current_record_id' ] = $_POST["id"];
			$c = $this->_get_customer_deposits();
			
			if( isset( $c["customer"] ) && $c["customer"] ){
				$_POST["id"] = $c["customer"];
				
				$customer = new cCustomers();
				$customer->class_settings = $this->class_settings;
				
				$customer->class_settings["action_to_perform"] = "view_customer_details";
				return $customer->customers();
			}
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = 'Invalid Customer';
			return $err->error();
		}
		
		private function _charge_customer_from_deposit(){
			$error = '';
			
			if( ( isset( $this->class_settings["amount_paid"] ) && $this->class_settings["amount_paid"] ) ){
				$d = $this->_get_customer_deposit_balance();
				if( isset( $d["balance"] ) ){
					if( $d["balance"] >= $this->class_settings["amount_paid"] ){
						$array_of_dataset = array();
			
						$new_record_id = get_new_id();
						
						$ip_address = get_ip_address();
						$date = date("U");
						
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
							
							$this->table_fields['amount_withdrawn'] => $this->class_settings["amount_paid"],
							$this->table_fields['payment_method'] => isset( $this->class_settings['payment_method'] )?$this->class_settings['payment_method']:"",
							$this->table_fields['comment'] => isset( $this->class_settings['comment'] )?$this->class_settings['comment']:"",
							$this->table_fields['store'] => isset( $this->class_settings["store"] )?$this->class_settings["store"]:"",
							$this->table_fields['customer'] => $this->class_settings["customer"],
							$this->table_fields['reference_table'] => isset( $this->class_settings["reference_table"] )?$this->class_settings["reference_table"]:$this->table_name,
							$this->table_fields['reference'] => isset( $this->class_settings["reference"] )?$this->class_settings["reference"]:"",
						);
						$array_of_dataset[] = $dataset_to_be_inserted;
						
						if( ! empty( $array_of_dataset ) ){
							
							$function_settings = array(
								'database' => $this->class_settings['database_name'],
								'connect' => $this->class_settings['database_connection'],
								'table' => $this->table_name,
								'dataset' => $array_of_dataset,
							);
							
							$returned_data = insert_new_record_into_table( $function_settings );
							return array( 'saved_record_id' => $new_record_id );
						}else{
							$error = '<h4>Error Occurred During Charge Operation</h4>Please try again';
						}
						
					}else{
						$error = '<h4>Customer Deposit is Too Low</h4>Customer Balance in Deposit: <strong>' . number_format( $d["balance"], 2 ).'</strong><br /><br />Please reduce the Amount Paid to be equal to or lower than the Customer Balance';
					}
				}else{
					return $d;
				}
			}else{
				$error = '<h4>Invalid Amount to Pay</h4>To Pay from deposit the total <strong>Amount to Pay</strong> for the transaction must be specified';
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
		
		private function _get_customer_deposit_balance(){
			$error = '';
			
			if( isset( $this->class_settings["customer"] ) && $this->class_settings["customer"] ){
				$query = "SELECT SUM( `".$this->table_fields["amount_deposit"]."` ) as 'deposit', SUM( `".$this->table_fields["amount_withdrawn"]."` ) as 'withdrawal' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields["customer"]."` = '".$this->class_settings["customer"]."'";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$all_data = execute_sql_query($query_settings);
				if( isset( $all_data[0]['withdrawal'] ) && isset( $all_data[0]['deposit'] ) && ( $all_data[0]['deposit'] || $all_data[0]['withdrawal'] ) ){
					return array( 
						"balance" => doubleval( $all_data[0]['deposit'] ) - doubleval( $all_data[0]['withdrawal'] ), 
						"deposit" => doubleval( $all_data[0]['deposit'] ), 
						'withdrawal' => doubleval( $all_data[0]['withdrawal'] ) 
					);
				}else{
					$error = '<h4>Invalid Customer Deposit</h4>The selected customer has not made any deposits';
				}
			}else{
				$error = '<h4>Invalid Customer</h4>Please specify an existing customer';
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
			unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
			$show_delete = 0;
			$key = md5('ucert'.$_SESSION['key']);
			
			if( isset($_SESSION[$key]) ){
				$user_details = $_SESSION[$key];
				$user_info = $user_details;
				
				//get access_roles
				$super = 0;
				
				$access = array();
				if( isset( $user_info["privilege"] ) && $user_info["privilege"] ){
					
					if( $user_info["privilege"] == "1300130013" ){
						$super = 1;
						$show_delete = 1;
					}else{
						$functions = get_access_roles_details( array( "id" => $user_info["privilege"] ) );
						if( isset( $functions[ $user_info["privilege"] ]["accessible_functions"] ) ){
							$a = explode( ":::" , $functions[ $user_info["privilege"] ]["accessible_functions"] );
							if( is_array( $a ) && $a ){
								foreach( $a as $k => $v ){
									$access[ $v ] = $v;
								}
							}
						}
						$key = "delete_advance_deposit"; //delete advance deposit
						if( ( isset( $access[ $key ] ) ) ){
							$show_delete = 1;
						}
						
					}
				}
				
			}
			
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->datatable_settings['custom_edit_button'] = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-filter-form.php' );
			$this->class_settings[ 'data' ]['form_data'] = $this->_get_html_view();
				
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			if( $show_delete ){
				$this->datatable_settings['show_delete_button'] = 1;
			}
			$datatable = $this->_display_data_table();
			
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Advance Deposits";
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
				$this->class_settings['form_heading_title'] = 'Modify Advance deposits';
			break;
			default:
				$this->class_settings['form_heading_title'] = 'Post Advance Deposits';
				if( ! isset( $this->class_settings['form_values_important'][ $this->table_fields["date"] ] ) )
					$this->class_settings['form_values_important'][ $this->table_fields["date"] ] = date("U");
			break;
			}
			
			$this->class_settings["hidden_records"][ $this->table_fields["reference"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["reference_table"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["currency"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["payment_method"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["store"] ] = 1;
			
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
					$module->class_settings["action_to_perform"] = 'delete_skip_customer_deposits';
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
					$module->class_settings["action_to_perform"] = 'delete_skip_customer_deposits';
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
				
				$err->class_that_triggered_error = 'ccustomer_deposits.php';
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
					$record = $this->_get_customer_deposits();
					
					$rv = __map_financial_accounts();
					
					$customer_account = $rv[ "account_receivable" ];
					$bank_account = $rv[ "bank_account" ];
					
					switch( $record[ "customer_deposits_method" ] ){
					case "cash_refund":
						if( isset( $rv[ "customer_refund" ] ) )
							$bank_account = $rv[ "customer_refund" ];
						
						$dc[] = array(
							"transaction_id" => $record["id"],
							"account" => $bank_account,
							"amount" => abs( $record["amount_paid"] ),
							"type" => "credit",
							"account_type" => $bank_account,
							"currency" => $record[ "currency" ],
						);
						
						$dc[] = array(
							"transaction_id" => $record["id"],
							"account" => ( ( $record["customer"] )?$record["customer"]:$customer_account ),
							"amount" => abs( $record["amount_paid"] ),
							"type" => "debit",
							"account_type" => $rv[ "account_receivable" ],
							"extra_reference" => isset( $record[ "extra_reference" ] )?$record[ "extra_reference" ]:"",
							"currency" => $record[ "currency" ],
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
							"currency" => $record[ "currency" ],
						);
						
						$bs = explode( ":::", $record[ "customer_deposits_method" ] );
						$bank_account = $record[ "customer_deposits_method" ];
						if( isset( $bs[1] ) && $bs[1] && isset( $bs[0] ) && $bs[0] ){
							$bank_account = $bs[0];
						}
						
						$dc[] = array(
							"transaction_id" => $record["id"],
							"account" => $record[ "customer_deposits_method" ],
							"amount" => $record["amount_paid"],
							"type" => "debit",
							"account_type" => $bank_account,
							"currency" => $record[ "currency" ],
						);
						
						$desc = "customer_deposits for ".$record["reference_table"]." #" . $record["sales_id"] . " " . ( ( $record["comment"] )?$record["comment"]:"" );
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
					
					if( ! ( isset( $returning_html_data['new_record_created'] ) && $returning_html_data['new_record_created'] ) ){
						$data['delete_existing'] = $record["id"];
					}
					
					$transactions = new cTransactions();
					$transactions->class_settings = $this->class_settings;
					$transactions->class_settings["data"] = $data;
					$transactions->class_settings["action_to_perform"] = "add_transaction_from_sales";
					$transactions->transactions();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_customer_deposits(){
			
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
					//$this->_get_customer_deposits();
				}
				
				return $single_data;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-customer_deposits-',//.$record["member_id"],
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
					'cache_key' => $cache_key.'-customer_deposits-'.$record["member_id"],
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