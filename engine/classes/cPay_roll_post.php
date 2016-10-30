<?php
	/**
	 * pay_roll_post Class
	 *
	 * @used in  				pay_roll_post Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	pay_roll_post
	 */

	/*
	|--------------------------------------------------------------------------
	| pay_roll_post Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cPay_roll_post{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'pay_roll_post';
		
		private $associated_cache_keys = array(
			'pay_roll_post',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		private $table_fields = array(
			'date' => 'pay_roll_post001',
			'end_date' => 'pay_roll_post002',
			'amount_paid' => 'pay_roll_post003',
			'amount_deducted' => 'pay_roll_post004',
			'salary_schedule' => 'pay_roll_post005',
			'comment' => 'pay_roll_post006',
			
			'staff_salary' => 'pay_roll_post007',
			'staff_welfare' => 'pay_roll_post008',
			'staff_medical' => 'pay_roll_post009',
			
			'paye_deduction' => 'pay_roll_post010',
			'pension' => 'pay_roll_post011',
			'salary_advance' => 'pay_roll_post012',
			'all_other_deduction' => 'pay_roll_post013',
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
	
		function pay_roll_post(){
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
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'get_all_pay_roll_post':
				$returned_value = $this->_get_all_pay_roll_post();
			break;
			case 'post_pay_roll_data':
				$returned_value = $this->_post_pay_roll_data();
			break;
			}
			
			return $returned_value;
		}
		
		private function _post_pay_roll_data(){
			if( ! ( isset( $_POST["id"] ) && doubleval( $_POST["id"] ) ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Amount Paid</h4><p>Modify the Pay Roll & Set Amount Paid to Staff</p>';
				return $err->error();
			}
			
			if( ! ( isset( $_GET["date"] ) && doubleval( $_GET["date"] ) ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Pay Roll Date</h4><p>Modify the Existing Pay Roll & Try Again</p>';
				return $err->error();
			}
			
			if( ! ( isset( $_GET["salary_schedule"] ) && $_GET["salary_schedule"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Pay Roll Date</h4><p>Modify the Existing Pay Roll & Try Again</p>';
				return $err->error();
			}
			
			
			$date = doubleval( $_GET["date"] );
			
			$salary_schedule = $_GET["salary_schedule"];
			$amount = doubleval( $_POST["id"] );
			$deduction = 0;
			if( isset( $_POST["mod"] ) && doubleval( $_POST["mod"] ) ){
				$deduction = doubleval( $_POST["mod"] );
				unset( $_POST["mod"] );
			}
			
			$_POST["id"] = "";
			if( isset( $_GET["record_id"] ) && $_GET["record_id"] ){
				$_POST["id"] = $_GET["record_id"];
			}

			$this->class_settings["update_fields"] = array(
				'date' => date( "Y", $date ) . '-' . date( "m", $date ) . '-1',
				'end_date' => date( "Y", $date ) . '-' . date( "m", $date ) . '-' . date( "t", $date ),
				'amount_paid' => $amount,
				'amount_deducted' => $deduction,
				'salary_schedule' => $salary_schedule,
				'comment' => 'Salary for ' . date("F, Y", $date ),
			);
			
			unset( $_GET[ "date" ] );
			unset( $_GET[ "record_id" ] );
			unset( $_GET[ "salary_schedule" ] );
			
			foreach( $this->table_fields as $key => $val ){
				if( isset( $_GET[ $key ] ) && doubleval( $_GET[ $key ] ) ){
					$this->class_settings["update_fields"][ $key ] = doubleval( $_GET[ $key ] );
				}
			}
			
			$return = $this->_update_table_field();
			
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Successful Posting</h4><p>Posted Pay Roll: <strong>'.date("F, Y", $date).'</strong><br />Net Pay: <strong>' . number_format( $amount - $deduction , 2 ) . '</strong></p>';
				$return = $err->error();
				
				unset( $return["html"] );
				$return["html"] = 'new-status';
				
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = '1';
				$return['id'] = 1;
				$return['action'] = '?action=pay_row&todo=display_pay_roll_post_view';
					
				return $return;
			}
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = '<h4>Unknown Error</h4><p>Please try again</p>';
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
			$this->_get_pay_roll_post();
		}
		
		private function _get_all_pay_roll_post(){
			$select = "";
			$where = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`,  `".$this->table_name."`.`modification_date`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			if( isset( $this->class_settings["start_date"] ) && $this->class_settings["start_date"] && isset( $this->class_settings["end_date"] ) && $this->class_settings["end_date"] ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["date"]."` >= ".$this->class_settings["start_date"]." AND `".$this->table_name."`.`".$this->table_fields["date"]."` <= ".$this->class_settings["end_date"]." ";
			}
			
			$query = "SELECT ".$select." FROM `".$this->class_settings['database_name']."`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status` = '1' ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields["date"]."` DESC ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			return $sql_result;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			//$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			//$this->datatable_settings['custom_edit_button'] = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$datatable = $this->_display_data_table();
			
			//$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Pay Roll Post";
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
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Pay Roll Post';
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
				
				$this->class_settings[ 'do_not_check_cache' ] = 1;
				$this->_get_pay_roll_post();
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
				
				$err->class_that_triggered_error = 'cpay_roll_post.php';
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
					$record = $this->_get_pay_roll_post();
					
					$rv = __map_financial_accounts();
					
					$payable_account_type = $rv["payroll_liabilities"];
					
					$staff_medical_account = $rv[ "staff_medical_account" ];
					$salary_advance_account = $rv[ "salary_advance_account" ];
					$salary_expense = $rv[ "salary_expense_account" ];
					
					$net_pay_account = $rv[ "payroll_net_pay" ];
					$paye = $rv[ "payroll_paye" ];
					$pension = $rv[ "payroll_pension" ];
					$other_deductions = $rv[ "payroll_other_deductions" ];
					
					$salary_bank_account = $rv[ "salary_bank_account" ];
					$bank_account = $rv[ "bank_account" ];
					
					$net_pay = $record["amount_paid"] - $record["amount_deducted"];
					
					$dc[] = array(
						"transaction_id" => $record["id"],
						"account" => $net_pay_account,
						"amount" => $net_pay,
						"type" => "credit",
						"account_type" => $payable_account_type,
						//"currency" => $record[ "salary_schedule" ],
					);
					
					$dc[] = array(
						"transaction_id" => $record["id"],
						"account" => $salary_expense,
						"amount" => ( $record["amount_paid"] - ( $record['salary_advance'] ) ),
						"type" => "debit",
						"account_type" => $rv["operating_expense"],
						//"currency" => $record[ "salary_schedule" ],
					);
					
					if( $record['salary_advance'] ){
						//reduce salary advance
						$dc[] = array(
							"transaction_id" => $record["id"],
							"account" => $salary_advance_account,
							"amount" => $record['salary_advance'],
							"type" => "credit",
							"account_type" => $rv["operating_expense"],
							//"currency" => $record[ "salary_schedule" ],
						);
					}
					
					/*
					if( $record['staff_medical'] ){
						//reduce salary advance
						$dc[] = array(
							"transaction_id" => $record["id"],
							"account" => $staff_medical_account,
							"amount" => $record['staff_medical'],
							"type" => "credit",
							"account_type" => $rv["operating_expense"],
						);
					}
					*/
					/*
					'staff_salary' => 'pay_roll_post007',
					'staff_welfare' => 'pay_roll_post008',
					'staff_medical' => 'pay_roll_post009',
					*/
					
					if( $record['paye_deduction'] ){
						$dc[] = array(
							"transaction_id" => $record["id"],
							"account" => $paye,
							"amount" => $record['paye_deduction'],
							"type" => "credit",
							"account_type" => $payable_account_type,
							//"currency" => $record[ "salary_schedule" ],
						);
					}
					
					if( $record['pension'] ){
						$dc[] = array(
							"transaction_id" => $record["id"],
							"account" => $pension,
							"amount" => $record['pension'],
							"type" => "credit",
							"account_type" => $payable_account_type,
							//"currency" => $record[ "salary_schedule" ],
						);
					}
					
					if( $record["all_other_deduction"] ){
						$dc[] = array(
							"transaction_id" => $record["id"],
							"account" => $other_deductions,
							"amount" => $record["all_other_deduction"],
							"type" => "credit",
							"account_type" => $payable_account_type,
							//"currency" => $record[ "salary_schedule" ],
						);
					}
					
					//payment of salary
					$dc[] = array(
						"transaction_id" => $record["id"],
						"account" => $salary_bank_account,
						"amount" => $net_pay,
						"type" => "credit",
						"account_type" => $bank_account,
						//"currency" => $record[ "salary_schedule" ],
					);
					
					$dc[] = array(
						"transaction_id" => $record["id"],
						"account" => $net_pay_account,
						"amount" => $net_pay,
						"type" => "debit",
						"account_type" => $payable_account_type,
						//"currency" => $record[ "salary_schedule" ],
					);
					
					$data = array(
						"id" => $record["id"] ,
						"date" => date( "Y-n-j", doubleval( $record["date"] ) ) ,
						"reference" => $record["id"] ,
						"reference_table" => $this->table_name,
						"description" => $record["comment"],
						"credit" => $net_pay,
						"debit" => $net_pay,
						"status" => "approved",
						'submitted_by' => $record["modified_by"],
						'submitted_on' => $record["modification_date"],
						'store' => "",
						'item' => $dc,
						"extra_reference" => "",
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
		
		private function _get_pay_roll_post(){
			
			$cache_key = $this->table_name;
			if( ! isset( $this->class_settings['current_record_id'] ) )return 0;
			
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
				else $select = "`id`, `serial_num`, `modification_date`, `modified_by`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `id` = '".$this->class_settings['current_record_id']."' ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_departments = execute_sql_query($query_settings);
			
			$departments = array();
			
			if( is_array( $all_departments ) && ! empty( $all_departments ) ){
				
				foreach( $all_departments as $pay_roll_post ){
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$pay_roll_post["id"],
						'cache_values' => $pay_roll_post,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
				}
				
				return $pay_roll_post;
			}
		}
		
	}
?>