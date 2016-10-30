<?php
	/**
	 * pay_row Class
	 *
	 * @used in  				pay_row Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	pay_row
	 */

	/*
	|--------------------------------------------------------------------------
	| pay_row Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	| ALTER TABLE `pay_row` ADD `pay_row024` VARCHAR(200) NOT NULL AFTER `pay_row002`;
	| ALTER TABLE `pay_row` ADD `pay_row025` DECIMAL(12,2) NOT NULL AFTER `pay_row010`;
	| UPDATE `pay_row`, `users` SET pay_row024 = users003 where pay_row002 = users.id;
	*/
	
	include "cPay_roll_auto_generate.php";
	include "cPay_roll_post.php";
	
	class cPay_row{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'pay_row';
		
		private $associated_cache_keys = array(
			'pay_row',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'pay_row001',
			'staff_name' => 'pay_row002',
			'staff_ref' => 'pay_row024',
			
			'salary_category' => 'pay_row027',
			
			'grade_level' => 'pay_row026',
			'salary_schedule' => 'pay_row019',
			
			'total_days' => 'pay_row020',
			'overtime_days' => 'pay_row021',
			'absent_days' => 'pay_row022',
			
			'basic_salary' => 'pay_row003',
			'bonus' => 'pay_row004',
			'housing' => 'pay_row005',
			'transport' => 'pay_row006',
			'utility' => 'pay_row007',
			'lunch' => 'pay_row008',
			'overtime' => 'pay_row009',
			'medical_allowance' => 'pay_row010',
			'other_allowance' => 'pay_row023',
			'leave_allowance' => 'pay_row025',
			
			'paye_deduction' => 'pay_row011',
			'pension' => 'pay_row012',
			'pension_manager' => 'pay_row028',
			
			'salary_advance' => 'pay_row013',
			'absent_deduction' => 'pay_row014',
			'other_deduction' => 'pay_row015',
			
			'total_salary' => 'pay_row016',
			'comment' => 'pay_row017',
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
	
		function pay_row(){
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
			case 'display_pay_row_datatable':
				$returned_value = $this->_display_pay_row_datatable();
			break;
			case 'get_all_pay_row':
				$returned_value = $this->_get_all_pay_row();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'get_tree_view':
				$returned_value = $this->_get_tree_view();
			break;
			case 'generate_farm_report':
				$returned_value = $this->_generate_farm_report();
			break;
			case 'auto_generate_form':
				$returned_value = $this->_auto_generate_form();
			break;
			case 'get_pay_roll_records':
				$returned_value = $this->_get_pay_roll_records();
			break;
			case 'generate_new_pay_roll':
				$returned_value = $this->_generate_new_pay_roll();
			break;
			case 'generate_new_pay_roll_from_schedule':
				$returned_value = $this->_generate_new_pay_roll_from_schedule();
			break;
			case 'view_pay_slip_schedule':
				$returned_value = $this->_view_pay_slip_schedule();
			break;
			case "get_pay_roll_reports":
			case "get_payment_schedule_reports":
				$returned_value = $this->_get_pay_roll_reports();
			break;
			case "reload_datatable":
				$returned_value = $this->_reload_datatable();
			break;
			case "display_pay_roll_post_view":
				$returned_value = $this->_display_pay_roll_post_view();
			break;
			}
			
			return $returned_value;
		}
		
		private function _display_pay_roll_post_view(){
			
			$year = date("Y");
			$this->class_settings["start_date"] = mktime( 0,0,0, 1, 1, $year );
			$this->class_settings["end_date"] = mktime( 0,0,0, 12, 31, $year );
			$data = $this->_get_pay_roll_records();
			
			$month_info = array();
			$post_info = array();
			
			if( is_array( $data ) && ! empty( $data ) ){
				foreach( $data as $sval ){
					$month = date("F, Y", doubleval( $sval["date"] ) );
					
					if( ! isset( $month_info[ $month ][ $sval["salary_schedule"] ] ) ){
						$month_info[ $month ][ $sval["salary_schedule"] ]["amount_paid"] = 0;
						$month_info[ $month ][ $sval["salary_schedule"] ]["amount_deducted"] = 0;
						$month_info[ $month ][ $sval["salary_schedule"] ]["date"] = $sval["date"];
						
						$month_info[ $month ][ $sval["salary_schedule"] ]["staff_medical"] = 0;
						$month_info[ $month ][ $sval["salary_schedule"] ]["staff_salary"] = 0;
						$month_info[ $month ][ $sval["salary_schedule"] ]["staff_welfare"] = 0;
						$month_info[ $month ][ $sval["salary_schedule"] ]["all_other_deduction"] = 0;
						$month_info[ $month ][ $sval["salary_schedule"] ]["paye_deduction"] = 0;
						$month_info[ $month ][ $sval["salary_schedule"] ]["pension"] = 0;
					}
					
					$pay = __pay_row__calculate_pay( $sval );
					$month_info[ $month ][ $sval["salary_schedule"] ]["amount_paid"] += round( doubleval( $pay["gross_pay"] ), 2 );
					$month_info[ $month ][ $sval["salary_schedule"] ]["amount_deducted"] += round( doubleval( $pay["net_deduction"] ), 2 );
					
					$month_info[ $month ][ $sval["salary_schedule"] ]["staff_medical"] += doubleval( $pay["staff_medical"] );
					$month_info[ $month ][ $sval["salary_schedule"] ]["staff_salary"] += doubleval( $pay["staff_salary"] );
					$month_info[ $month ][ $sval["salary_schedule"] ]["staff_welfare"] += doubleval( $pay["staff_welfare"] );
					$month_info[ $month ][ $sval["salary_schedule"] ]["all_other_deduction"] += doubleval( $pay["all_other_deduction"] );
					$month_info[ $month ][ $sval["salary_schedule"] ]["paye_deduction"] += doubleval( $pay["paye_deduction"] );
					$month_info[ $month ][ $sval["salary_schedule"] ]["pension"] += doubleval( $pay["pension"] );
				}
			}
			
			$pay_roll_post = new cPay_roll_post();
			$pay_roll_post->class_settings = $this->class_settings;
			$pay_roll_post->class_settings[ 'action_to_perform' ] = 'get_all_pay_roll_post';
			$data = $pay_roll_post->pay_roll_post();
			
			if( is_array( $data ) && ! empty( $data ) ){
				foreach( $data as $sval ){
					$month = date("F, Y", doubleval( $sval["date"] ) );
					$post_info[ $month ][ $sval["salary_schedule"] ] = $sval;
				}
			}
			
			$this->class_settings[ 'data' ]["years_selected_option"] = $year;
			$this->class_settings[ 'data' ]["years"] = get_calendar_years();
			$this->class_settings[ 'data' ]["post_info"] = $post_info;
			$this->class_settings[ 'data' ]["month_info"] = $month_info;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-pay-roll-post-view' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _reload_datatable(){
			if( isset( $_GET["year"] ) && isset( $_GET["month_of_year"] ) && doubleval( $_GET["year"] ) && ( $_GET["month_of_year"] ) ){
				$year = doubleval( $_GET["year"] );
				
				$month = doubleval( $_GET["month_of_year"] );
				$start_date = mktime( 0,0,0, $month, 1, $year );
				$end_date = mktime( 23, 59, 59, $month, date("t", $start_date ), $year );
				
				if( $_GET["month_of_year"] == "all-months" ){
					$month = 1;
					$start_date = mktime( 0,0,0, $month, 1, $year );
					$end_date = mktime( 23, 59, 59, 12, 31, $year );
				}
				
				$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = " AND `".$this->table_name."`.`".$this->table_fields[ "date" ]."` >= " . $start_date . " AND `".$this->table_name."`.`".$this->table_fields[ "date" ]."` <= " . $end_date;
			}
			
			return array(
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				//'javascript_functions' => array( 'activate_tree_view', 'set_function_click_event' ) 
			);
		}
		
		private function _get_pay_roll_reports(){
			$returning_html_data = "";
			
			$field_name = "date";
			$initial_where = " `".$this->table_name."`.`record_status`='1' ";
			$where = $initial_where;
			
			$title = "";
			$select = "";
			$grouping = 1;
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			$report_type = "payment_schedule";
			if( isset( $_GET["department"] ) && $_GET["department"] ){
				$report_type = $_GET["department"];
			}
			
			$start_date_timestamp = 0;
			if( isset( $_GET["start_date"] ) && $_GET["start_date"] ){
				$st = explode( "-", $_GET["start_date"] );
				if( isset( $st[2] ) )
					$start_date_timestamp = mktime( 0,0,0, $st[1], $st[2], $st[0] );
			}
			
			$end_date_timestamp = 0;
			if( isset( $_GET["end_date"] ) && $_GET["end_date"] ){
				$st = explode( "-", $_GET["end_date"] );
				if( isset( $st[2] ) )
					$end_date_timestamp = mktime( 23,59,59, $st[1], $st[2], $st[0] );
			}
			
			$group1 = "";
			if( isset( $_GET["budget"] ) && $_GET["budget"] )
				$group1 = $_GET["budget"];
			
			$skip_joins = 0;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_weekly_production_reports':
			default:
				$skip_joins = 1;
				$grouping = 2;
				
				$report_year = date("Y");
				if( isset( $_GET["budget"] ) && intval( $_GET["budget"] ) ){
					$report_year = intval( $_GET["budget"] );	
				}
				
				$all_months = 0;
				$report_month = date("n");
				if( isset( $_GET["month"] ) ){
					if( intval( $_GET["month"] ) )
						$report_month = intval( $_GET["month"] );
					
					if( $_GET["month"] == "all-months" ){
						$all_months = 1;
					}
				}
				
				if( $all_months ){
					$start_date_timestamp = mktime( 0,0,0, 1, 1, $report_year );
					$end_date_timestamp = mktime( 23,59,59, 12, 31, $report_year );
				}else{
					$start_date_timestamp = mktime( 0,0,0, $report_month, 1, $report_year );
					$end_date_timestamp = mktime( 23,59,59, $report_month, date("t", $start_date_timestamp), $report_year );
				}
			break;
			}
			
			$date_filter = "M-Y";
			$get_opening_stock = 0;
			$age_key = "date";
			
			$pen_required = 0;
			
			switch( $group1 ){
			case "monthly":
				$date_filter = "M-Y";
				$grouping = 10;
			break;
			case "daily":
				$date_filter = "d-M-Y";
				$grouping = 100;
			break;
			case "weekly":
				$age_key = "age";
				$date_filter = "jS M-Y";
				$grouping = 50;
			break;
			case "yearly":
				$date_filter = "Y";
				$grouping = 1;
			break;
			}
			
			$start_date = 0;
			$end_date = 0;
			
			switch( $report_type ){
			case "pay_slip":
			case 'pay_slip_dollar':
			case 'staff_salary_schedule_dollar':
			case 'staff_salary_schedule':
			case 'work_schedule':
			case "payment_summary":
			case "payment_schedule":
			case "payment_schedule_dollar":
			case 'payment_schedule_bank':
			case 'payment_schedule_bank_dollar':
			case 'payment_schedule_details':
			case 'payment_schedule_details_dollar':
				$title = get_select_option_value( array( "id" => $report_type , "function_name" => "get_pay_roll_report_types" ) );
				
				$skip_joins = 1;
				$grouping = 2;
				
				$where = $initial_where;
				
				$start_date = $start_date_timestamp;
				if( $start_date_timestamp ){
					$where .= " AND `".$this->table_name."`.`".$this->table_fields["date"]."` >= " . $start_date_timestamp;
				}
				
				$end_date = $end_date_timestamp;
				if( $end_date_timestamp ){
					$where .= " AND `".$this->table_name."`.`".$this->table_fields["date"]."` <= " . $end_date_timestamp ." ";
				}
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
			if( isset( $_GET["operator"] ) && $_GET["operator"] ){
				$p = get_employees_with_names();
				$p1 = explode(",", $_GET["operator"] );
				$atitle = "";
				
				foreach( $p1 as $pp ){
					if( isset( $p[ $pp ] ) ){
						if( $atitle )$atitle .= ", " . ucwords( $p[ $pp ] );
						else $atitle = ucwords( $p[ $pp ] );
						
						if( $pen_val )$pen_val .= ", '" . $pp . "'";
						else $pen_val = "'" . $pp . "'";
					}
				}
				
				if( $atitle ){
					$title .= "<br /><strong><small>".$atitle."</small></strong>";
				}
			}
			
			if( $pen_val ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["staff_name"]."` IN ( ".$pen_val." ) ";
			}else{
				if( $pen_required ){
					$error_file = "select-pen-message.php";
					$where = "";
				}
			}
			
			switch( $report_type ){
			case 'pay_slip_dollar':
			case 'payment_schedule_details_dollar':
			case 'payment_schedule_bank_dollar':
			case "payment_schedule_dollar":
			case "payment_summary_dollar":
			case 'staff_salary_schedule_dollar':
				$where .= " AND `".$this->table_name."`.`".$this->table_fields[ "salary_schedule" ]."` = 'dollar' ";
			break;
			case "payment_summary":
			break;
			default:
				$where .= " AND `".$this->table_name."`.`".$this->table_fields[ "salary_schedule" ]."` != 'dollar' ";
			break;
			}
			
			if( $where ){
				$all_data = array();
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields[ $field_name ]."` ASC ";
				
				//echo $query; exit;
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$pay = execute_sql_query($query_settings);
				
				$all_data = $pay;
				
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
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-report' );
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'report_subtitle' ] = $subtitle;
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
			
			if( isset( $this->class_settings[ 'report_action' ] ) ){
				$this->class_settings[ 'data' ][ 'report_action' ] = $this->class_settings[ 'report_action' ];
			}
			
			if( isset( $this->class_settings[ 'show_report_category' ] ) ){
				$this->class_settings[ 'data' ][ 'show_report_category' ] = $this->class_settings[ 'show_report_category' ];
			}
			
			$this->class_settings[ 'data' ][ 'report_type' ] = get_calendar_years();
			$this->class_settings[ 'data' ][ 'selected_option' ] = date("Y");
			
			$this->class_settings[ 'data' ][ 'report_type3' ] = get_employees_with_ref();
			$this->class_settings[ 'data' ][ 'selected_option3' ] = "all-employees";
			
			$m = get_months_of_year();
			//$m["all-months"] = "All Months";
			
			$this->class_settings[ 'data' ][ 'report_title' ] = "Pay Slips / Schedule Reports";
			$this->class_settings[ 'data' ][ 'report_type1' ] = $m;
			//$this->class_settings[ 'data' ][ 'selected_option1' ] = "all-months";
			$this->class_settings[ 'data' ][ 'selected_option1' ] = date("n");
			
			$this->class_settings[ 'data' ][ 'report_type4' ] = get_pay_roll_report_types();
			$this->class_settings[ 'data' ][ 'selected_option4' ] = "tabular";
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _view_pay_slip_schedule(){
			$this->class_settings[ 'hide_years' ] = 1;
			$this->class_settings[ 'hide_months' ] = 1;
			$this->class_settings[ 'hide_all_pens' ] = 1;
			
			$this->class_settings[ 'show_report_category' ] = 1;
			$this->class_settings[ 'show_birds_age' ] = 1;
			
			$this->class_settings[ 'report_action' ] = "get_payment_schedule_reports";
			$h = $this->_display_all_reports_full_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["modal_dialog_style"] = "width:96%;";
			$this->class_settings[ 'data' ]["html_title"] = "Pay Slips / Payment Schedule Reports";
			$this->class_settings[ 'data' ]['html'] = $h["html"];
			
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
		
		private function _generate_new_pay_roll(){
			if( isset( $this->class_settings["dataset"] ) && is_array( $this->class_settings["dataset"] ) && ! empty( $this->class_settings["dataset"] ) ){
				$gdate = date("U");
				if( isset( $this->class_settings["date"] ) && $this->class_settings["date"] ){
					$gdate = $this->class_settings["date"];
				}
				
				$ref = "";
				if( isset( $this->class_settings["reference"] ) && $this->class_settings["reference"] ){
					$ref = $this->class_settings["reference"];
				}
				
				$array_of_dataset = array();
			
				$new_record_id = get_new_id();
				$new_record_id_serial = 0;
				
				$ip_address = get_ip_address();
				$date = date("U");
				
				foreach( $this->class_settings["dataset"] as $sval ){
					foreach( $this->table_fields as $key => $val ){
						if( isset( $sval[ $key ] ) ){
							$dataset_to_be_inserted[ $val ] = $sval[ $key ];
						}
					}
					if( isset( $dataset_to_be_inserted["serial_num"] ) )
						unset( $dataset_to_be_inserted["serial_num"] );
					
					$dataset_to_be_inserted['id'] = $new_record_id . ++$new_record_id_serial;
					$dataset_to_be_inserted['created_role'] = $this->class_settings[ 'priv_id' ];
					$dataset_to_be_inserted['created_by'] = $this->class_settings[ 'user_id' ];
					$dataset_to_be_inserted['creation_date'] = $date;
					$dataset_to_be_inserted['modified_by'] = $this->class_settings[ 'user_id' ];
					$dataset_to_be_inserted['modification_date'] = $date;
					$dataset_to_be_inserted['ip_address'] = $ip_address;
					$dataset_to_be_inserted['record_status'] = 1;
					$dataset_to_be_inserted[ $this->table_fields["date"] ] = $gdate;
					$dataset_to_be_inserted[ $this->table_fields["comment"] ] = date( "F Y", $gdate ) . " Salary";
					$dataset_to_be_inserted[ $this->table_fields["total_days"] ] = date("t", $gdate );
					//if( $ref )$dataset_to_be_inserted[ $this->table_fields["salary_schedule"] ] = $ref;
					
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
		}
		
		private function _generate_new_pay_roll_from_schedule(){
			
			//get all staff
			$users = new cUsers();
			$users->class_settings = $this->class_settings;
			$users->class_settings[ 'action_to_perform' ] = 'get_all_admin_users';
			$this->class_settings["dataset"] = $users->users();
			
			//get all grade levels
			$grade_level = new cGrade_level();
			$grade_level->class_settings = $this->class_settings;
			$grade_level->class_settings[ 'action_to_perform' ] = 'get_all_grade_level';
			$g = $grade_level->grade_level();
			
			$grade_levels = array();
			if( is_array( $g ) && ! empty( $g ) ){
				foreach( $g as $gval ){
					$grade_levels[ $gval["id"] ] = $gval;
				}
			}
			
			if( isset( $this->class_settings["dataset"] ) && is_array( $this->class_settings["dataset"] ) && ! empty( $this->class_settings["dataset"] ) ){
				$gdate = date("U");
				if( isset( $this->class_settings["date"] ) && $this->class_settings["date"] ){
					$gdate = $this->class_settings["date"];
				}
				
				$array_of_dataset = array();
			
				$new_record_id = get_new_id();
				$new_record_id_serial = 0;
				
				$ip_address = get_ip_address();
				$date = date("U");
				
				foreach( $this->class_settings["dataset"] as $sval ){
					if( isset( $sval["grade_level"] ) && $sval["grade_level"] && isset( $grade_levels[ $sval["grade_level"] ]["basic_salary"] ) && $grade_levels[ $sval["grade_level"] ]["basic_salary"] ){
						foreach( $grade_levels[ $sval["grade_level"] ] as $key => $val ){
							if( isset( $this->table_fields[ $key ] ) ){
								$dataset_to_be_inserted[ $this->table_fields[ $key ] ] = $val;
							}
						}
						
						if( isset( $dataset_to_be_inserted["serial_num"] ) )
							unset( $dataset_to_be_inserted["serial_num"] );
						
						$dataset_to_be_inserted['id'] = $new_record_id . ++$new_record_id_serial;
						$dataset_to_be_inserted['created_role'] = $this->class_settings[ 'priv_id' ];
						$dataset_to_be_inserted['created_by'] = $this->class_settings[ 'user_id' ];
						$dataset_to_be_inserted['creation_date'] = $date;
						$dataset_to_be_inserted['modified_by'] = $this->class_settings[ 'user_id' ];
						$dataset_to_be_inserted['modification_date'] = $date;
						$dataset_to_be_inserted['ip_address'] = $ip_address;
						$dataset_to_be_inserted['record_status'] = 1;
						
						$dataset_to_be_inserted[ $this->table_fields["staff_name"] ] = $sval["id"];
						$dataset_to_be_inserted[ $this->table_fields["staff_ref"] ] = $sval["ref_no"];
						$dataset_to_be_inserted[ $this->table_fields["total_days"] ] = date("t", $gdate );
						
						$dataset_to_be_inserted[ $this->table_fields["date"] ] = $gdate;
						$dataset_to_be_inserted[ $this->table_fields["comment"] ] = date( "F Y", $gdate ) . " Salary";
						
						//if( $ref )$dataset_to_be_inserted[ $this->table_fields["salary_schedule"] ] = $ref;
						
						//new
						$array_of_dataset[] = $dataset_to_be_inserted;
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
				
				return $saved;
			}
		}
		
		private function _get_pay_roll_records(){
			$where = "";
			if( isset( $this->class_settings["start_date"] ) && $this->class_settings["start_date"] && isset( $this->class_settings["end_date"] ) && $this->class_settings["end_date"] ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["date"]."` >= ".$this->class_settings["start_date"]." AND `".$this->table_name."`.`".$this->table_fields["date"]."` <= ".$this->class_settings["end_date"]." ";
			}
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status`='1' ".$where." GROUP BY `".$this->table_name."`.`id` ORDER BY `".$this->table_name."`.`".$this->table_fields[ "date" ]."` DESC ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_data = execute_sql_query($query_settings);
			
			return $all_data;
		}
		
		private function _auto_generate_form(){
			$pay_roll_auto_generate = new cPay_roll_auto_generate();
			$pay_roll_auto_generate->class_settings = $this->class_settings;
			$pay_roll_auto_generate->class_settings[ 'action_to_perform' ] = 'auto_generate_form';
			$h = $pay_roll_auto_generate->pay_roll_auto_generate();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			//$this->class_settings[ 'data' ]["modal_dialog_style"] = "width:96%;";
			$this->class_settings[ 'data' ]["html_title"] = "Auto Generate Salary Wizard";
			$this->class_settings[ 'data' ]['html'] = ( isset( $h["html"] )?$h["html"]:"Missing Info" );
			
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
		
		private function _generate_farm_report(){
			$returning_html_data = "";
			
			$field_name = "";
			$grouping = 0;
			
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
					
					$title = "PAY ROW RECORDS FOR ";
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
						$where = " AND `".$this->table_name."`.`".$this->table_fields[ "date" ]."` >= " . $start_date . " AND `".$this->table_name."`.`".$this->table_fields[ "date" ]."` <= " . $end_date;
					}elseif( $start_date ){
						$where = " AND `".$this->table_name."`.`".$this->table_fields[ "date" ]."` = " . $start_date;
					}
				}
				
			}
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			if( $field_name ){
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status`='1' ".$where." GROUP BY `".$this->table_name."`.`id` ORDER BY `".$this->table_name."`.`".$this->table_fields[ $field_name ]."` DESC ";
				
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
								case "basic_salary":
								case "other_allowance":
								case "amount_debited":
								case "amount_credited":
								case "total_salary":
									$all_new[ $key ][ $k ] += doubleval( $v );
								break;
								case "comment":
								case "staff_name":
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
		
		private function _get_tree_view(){
			$date_filter = 1;
			
			$type_invoice_quotation = "";
			if( isset( $this->class_settings["type_invoice_quotation"] ) ){
				$type_invoice_quotation = $this->class_settings["type_invoice_quotation"];
			}
			$operator_filter = "";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'issue_date':
			case 'due_date':
			case 'delivery_date':
			case 'modification_date':
			case 'creation_date':
				$date_filter = 1;
			break;
			}
		
			$data = array();
			$count = 0;
			
			$field_name = $this->class_settings['action_to_perform'];
			$field_name = 'date';
			//GET YEARS & MONTHS OF DIVISIONAL REPORTS
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'modification_date':
			case 'creation_date':
				$select = " `id`, `serial_num`, `".$field_name."` ";
			break;
			default:
				$select = " `id`, `serial_num`, `".$this->table_fields[ $field_name ]."` as '".$field_name."' ";
			break;
			}
			
			/*
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `".$val."` as '".$key."'";
			}
			*/
			$where = "";
			$type_invoice_quotation = "generate_farm_report";
			
			$start_date = 0;
			$end_date = 0;
			if( isset( $_GET["id"] ) && $_GET["id"] ){
				$pieces = explode( ":::", $_GET["id"] );
				if( isset( $pieces[2] ) && $pieces[2] ){
					$this->class_settings["month_query"] = explode( ";;;" , $pieces[2] );
				}
			}
			
			if( isset( $this->class_settings["month_query"][2] ) && intval( $this->class_settings["month_query"][2] ) ){
				$y = intval( $this->class_settings["month_query"][1] );
				$e = intval( $this->class_settings["month_query"][2] );
				
				$start_date = mktime(0,0,0, $e , 1, $y );
				
				$e++;
				if( $e > 12 ){
					$y++;
					$e = 1;
				}
				$end_date = mktime(0,0,0, $e , 1, $y );
				$where .= " AND ( `".$this->table_name."`.`".$this->table_fields[ $field_name ]."` >= ".($start_date)." AND `".$this->table_name."`.`".$this->table_fields[ $field_name ]."` <= ".$end_date." ) ";
			}
			
			if( $start_date && $end_date ){
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields[ $field_name ]."` DESC";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$all_data = execute_sql_query($query_settings);
				
				$table_name = $this->table_name;
				
				$data = array();
				if( is_array( $all_data ) && ! empty( $all_data ) ){
					foreach( $all_data as $record ){
						$data[] = array(
							'id' => $table_name.':::'.$type_invoice_quotation.':::'.$field_name . ( date( ";;;Y;;;m;;;d", doubleval( $record[ $field_name ] ) ) ) . ':::'.$type_invoice_quotation.':::',
							'text' => date( "l, jS", doubleval( $record[ $field_name ] ) ),
							'icon' => "icon-file",
						);
					}
				}
				return $data;
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ".$where." GROUP BY `".$this->table_name."`.`".$this->table_fields[ $field_name ]."` ORDER BY `".$this->table_name."`.`".$this->table_fields[ $field_name ]."` DESC";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_data = execute_sql_query($query_settings);
			
			$table_name = $this->table_name;
			
			if( is_array( $all_data ) && ! empty( $all_data ) ){
				$years = array();
				/*
				$tree_view_options = $this->tree_view_options;
				if( isset( $tree_view_options[ $field_name ] ) ){
					unset( $tree_view_options[ $field_name ] );
					//unset( $tree_view_options[ "department" ] );
				}
				*/
				$count = 0;
				$map = array();
				$map_month = array();
				
				$map_day = array();
				$map_day_day = array();
				
				foreach( $all_data as $record ){
					$children = array();
					$label = $record[ $field_name ];
					
					if( $date_filter ){
						$label = date("Y", doubleval( $record[ $field_name ] ) );
						$label_month = date("F", doubleval( $record[ $field_name ] ) );
						
						$year = $label;
						$month = date( "m" , doubleval( $record[ $field_name ] ) );
						
						$children[] = array( 'id' => $table_name.':::'.$type_invoice_quotation.':::'.$field_name.';;;'.$year.';;;'.$month.':::'.$type_invoice_quotation.':::'.$operator_filter, 'text' => $label_month, 'children' => true );

						if( isset( $map[ $label ] ) ){
							if( isset( $map_month[ $label ][ $month ] ) )
								continue;
							
							$data[ $map[ $label ] ]["children"][] = $children[0];
							$map_month[ $label ][ $month ] = $month;
							continue;
						}
						
						$map_month[ $label ][ $month ] = $month;
						$operator_filter_tmp = $operator_filter;
						$year_month_tmp = $field_name.';;;'.$year;
					}else{
						switch( $field_name ){
						case "operator":
							$label = isset( $operators[ $record[ $field_name ] ] )?$operators[ $record[ $field_name ] ]:$record[ $field_name ];
							$operator_filter_tmp = $record[ $field_name ];
						break;
						case "department":
							$label = isset( $departments[ $record[ $field_name ] ] )?$departments[ $record[ $field_name ] ]:$record[ $field_name ];
							$type_invoice_quotation = $record[ $field_name ];
							$operator_filter_tmp = "all-operators";
						break;
						}
						
						foreach( $tree_view_options as $k => $v ){
							switch( $k ){
							case "generate_reports":
								$children[] = array( 'id' => $table_name.':::'.$k.':::-:::'.$type_invoice_quotation.':::'.$operator_filter_tmp, 'text' => $v );
							break;
							default:
								$children[] = array( 'id' => $table_name.':::'.$k.':::-:::'.$type_invoice_quotation.':::'.$operator_filter_tmp, 'text' => $v, 'children' => true );
							break;
							}
						}
						
						$year_month_tmp = "-";
					}
					
					$map[ $label ] = $count;
					$data[ $count ] = array(
						'id' => $table_name.':::'.$type_invoice_quotation.':::'.$year_month_tmp.':::'.$type_invoice_quotation.':::'.$operator_filter_tmp,
						'text' => $label,
						'children' => $children,
					);
					++$count;
				}
				
			}
			
			return $data;
		}
		
		private function _display_all_records_full_view(){
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->datatable_settings['custom_single_select_button'] = $this->_get_html_view();
			
			$datatable = $this->_display_data_table();
			
			$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
			
			$this->class_settings[ 'data' ][ 'years' ] = get_calendar_years();
			$this->class_settings[ 'data' ][ 'years_selected_option' ] = date("Y");
			
			$m = get_months_of_year();
			
			$this->class_settings[ 'data' ][ 'months' ] = $m;
			$this->class_settings[ 'data' ][ 'months_selected_option' ] = date("n");
			
			$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = " AND `".$this->table_name."`.`".$this->table_fields[ "date" ]."` >= ".mktime( 0,0,0, date("n"), 1, date("Y") ) . " AND `".$this->table_name."`.`".$this->table_fields[ "date" ]."` <= ".mktime( 23, 59, 59, date("n"), date("t"), date("Y") );
			
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Pay Roll (Salary) Manager";
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
			
			$this->class_settings['form_heading_title'] = 'New Pay Roll';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Salary / Pay Roll';
			break;
			}
			
			if( get_use_grade_level_in_payroll_settings() ){
				$this->class_settings['hidden_records'][ $this->table_fields['basic_salary'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['housing'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['medical_allowance'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['transport'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['utility'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['lunch'] ] = 1;
			}else{
				$this->class_settings['hidden_records'][ $this->table_fields['grade_level'] ] = 1;
			}
			
			$this->class_settings['hidden_records'][ $this->table_fields['staff_ref'] ] = 1;
			$this->class_settings['hidden_records'][ $this->table_fields["total_salary"] ] = 1;
			$this->class_settings['hidden_records'][ $this->table_fields["overtime"] ] = 1;
			$this->class_settings['hidden_records'][ $this->table_fields["absent_deduction"] ] = 1;
			
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
				
				$err->class_that_triggered_error = 'cpay_row.php';
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
			
			if( isset( $_POST[ $this->table_fields['staff_name'] ] ) && $_POST[ $this->table_fields['staff_name'] ] ){
				$emp_names = get_users( array( "id" => $_POST[ $this->table_fields['staff_name'] ] ) );
				if( isset( $emp_names[ "ref_no" ] ) && $emp_names[ "ref_no" ] ){
					$_POST[ $this->table_fields['staff_ref'] ] = $emp_names[ "ref_no" ];
				}
				
				if( isset( $emp_names[ "grade_level" ] ) && $emp_names[ "grade_level" ] ){
					if( ! get_use_grade_level_in_payroll_settings() ){
						$_POST[ $this->table_fields['grade_level'] ] = $emp_names[ "grade_level" ];
					}
				}
				
				if( get_use_grade_level_in_payroll_settings() ){
					if( isset( $_POST[ $this->table_fields['grade_level'] ] ) && $_POST[ $this->table_fields['grade_level'] ] ){
						$grade_details = get_grade_levels_details( array( "id" => $_POST[ $this->table_fields['grade_level'] ] ) );
						if( isset( $grade_details["basic_salary"] ) ){
							$_POST[ $this->table_fields['basic_salary'] ] = $grade_details["basic_salary"];
							$_POST[ $this->table_fields['housing'] ] = $grade_details["housing"];
							$_POST[ $this->table_fields['medical_allowance'] ] = $grade_details["medical_allowance"];
							$_POST[ $this->table_fields['transport'] ] = $grade_details["transport"];
							$_POST[ $this->table_fields['utility'] ] = $grade_details["utility"];
							$_POST[ $this->table_fields['lunch'] ] = $grade_details["lunch"];
						}
					}
				}
			}
			
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
					$record = $this->_get_pay_row();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_pay_row(){
			
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
					//$this->_get_pay_row();
				}
				
				return $single_data;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-pay_row-',//.$record["member_id"],
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
					'cache_key' => $cache_key.'-pay_row-'.$record["member_id"],
					'directory_name' => $cache_key,
					'cache_values' => $members,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				return $members;
			}
		}
	}
	
	function __pay_row__calculate_pay( $sval = array() ){
		$income = $sval["basic_salary"] + $sval["housing"] + $sval["transport"] + $sval["utility"] + $sval["lunch"] + $sval["medical_allowance"];
		$deduction = $sval["paye_deduction"] + $sval["pension"] + $sval["salary_advance"] + $sval["other_deduction"];
		
		if( $sval['total_days'] ){
			$sval[ "overtime" ] = ( $income / $sval['total_days'] ) * $sval['overtime_days'];
			$sval[ "absent_deduction" ] = ( $income / $sval['total_days'] ) * $sval['absent_days'];
		}
		$deduction += $sval[ "absent_deduction" ];
		$income += $sval[ "overtime" ] + $sval["bonus"] + $sval["other_allowance"] + $sval["leave_allowance"];
		
		$sval["staff_salary"] = $sval["basic_salary"] + $sval["bonus"] + $sval[ "overtime" ] + $sval["other_allowance"] + $sval["leave_allowance"];
		$sval["staff_welfare"] = $sval["housing"] + $sval["transport"] + $sval["utility"] + $sval["lunch"];
		$sval["staff_medical"] = $sval["medical_allowance"];
		
		$sval["all_other_deduction"] = $sval[ "absent_deduction" ] + $sval["other_deduction"] + $sval["salary_advance"];
		
		$sval["gross_pay"] = $income;
		$sval["net_deduction"] = $deduction;
		
		return $sval;
	}
?>