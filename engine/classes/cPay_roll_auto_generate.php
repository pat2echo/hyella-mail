<?php
	/**
	 * pay_roll_auto_generate Class
	 *
	 * @used in  				pay_roll_auto_generate Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	pay_roll_auto_generate
	 */

	/*
	|--------------------------------------------------------------------------
	| pay_roll_auto_generate Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cPay_roll_auto_generate{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'pay_roll_auto_generate';
		
		private $associated_cache_keys = array(
			'pay_roll_auto_generate',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'month' => 'pay_roll_auto_generate001',
			'year' => 'pay_roll_auto_generate010',
			'generation_type' => 'pay_roll_auto_generate002',
			
			'previous_month' => 'pay_roll_auto_generate003',
			'previous_year' => 'pay_roll_auto_generate004',
			
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
	
		function pay_roll_auto_generate(){
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
			case 'get_all_pay_roll_auto_generate':
				$returned_value = $this->_get_all_pay_roll_auto_generate();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'auto_generate_form':
				$returned_value = $this->_auto_generate_form();
			break;
			case 'save_and_auto_generate':
				$returned_value = $this->_save_and_auto_generate();
			break;
			}
			
			return $returned_value;
		}
		
		private function _save_and_auto_generate(){
			//check for previous month info
			if( isset( $_POST[ $this->table_fields['generation_type'] ] ) ){
				switch( $_POST[ $this->table_fields['generation_type'] ] ){
				case "salary_schedule":
					if( isset( $_POST[ $this->table_fields["month"] ] ) && $_POST[ $this->table_fields["month"] ] && isset( $_POST[ $this->table_fields["year"] ] ) && $_POST[ $this->table_fields["year"] ] ){
						$month = $_POST[ $this->table_fields["month"] ];
						$year = $_POST[ $this->table_fields["year"] ];
						
						$pay_row = new cPay_row();
						$pay_row->class_settings = $this->class_settings;
						
						$cdate = mktime( 0,0,0, $month, 1, $year );
						$pay_row->class_settings["start_date"] = $cdate;
						$pay_row->class_settings["end_date"] = mktime( 0,0,0, $month, date("t", $cdate ), $year );
							
						$pay_row->class_settings[ 'action_to_perform' ] = 'get_pay_roll_records';
						$p = $pay_row->pay_row();
						
						if( ! ( is_array( $p ) && ! empty( $p ) ) ){
							
							$pay_row->class_settings["date"] = mktime( 0,0,0, $month, 27, $year );
							$pay_row->class_settings[ 'action_to_perform' ] = 'generate_new_pay_roll_from_schedule';
							$r = $pay_row->pay_row();
							
							if( $r ){
								$err = new cError(010011);
								$err->html_format = 2;
								$err->action_to_perform = 'notify';
								$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
								$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
								$err->additional_details_of_error = '<h4>Pay Roll Generation Successful</h4><p>Generated Pay Roll: <strong>'.date("F, Y", $cdate).'</strong><br />Generated from <strong>Grade Level Schedule</strong></p>';
								$r1 = $err->error();
								
								$r1[ "html_replacement_selector" ] = "#modal-replacement-handle";
								$r1[ "html_replacement" ] = $r1["html"];
								unset( $r1["html"] );
								$r1[ "status" ] = "new-status";
								
								return $r1;
							}else{
								$err = new cError(010014);
								$err->action_to_perform = 'notify';
								$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
								$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
								$err->additional_details_of_error = '<h4>Unknown Error</h4>Failed to generate Pay Roll';
								return $err->error();
							}
						}
						
						$err = new cError(010014);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = '<h4>Existing Pay Roll</h4><p>Data already exists for the month you intend to generate pay roll</p><p>You will have to first delete the existing month data in order to continue generating the pay roll</p>';
						return $err->error();
					
					}
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Undefined Current Month Info</h4>Please specify the month to generate pay roll for';
					return $err->error();
				break;
				default:
					if( isset( $_POST[ $this->table_fields["previous_month"] ] ) && $_POST[ $this->table_fields["previous_month"] ] && isset( $_POST[ $this->table_fields["previous_year"] ] ) && $_POST[ $this->table_fields["previous_year"] ] ){
						$pmonth = $_POST[ $this->table_fields["previous_month"] ];
						$pyear = $_POST[ $this->table_fields["previous_year"] ];
						
						$pdate = mktime( 0,0,0, $pmonth, 1, $pyear );
						$this->class_settings["start_date"] = $pdate;
						$this->class_settings["end_date"] = mktime( 0,0,0, $pmonth, date("t", $pdate ), $pyear );
						
						if( isset( $_POST[ $this->table_fields["month"] ] ) && $_POST[ $this->table_fields["month"] ] && isset( $_POST[ $this->table_fields["year"] ] ) && $_POST[ $this->table_fields["year"] ] ){
							$month = $_POST[ $this->table_fields["month"] ];
							$year = $_POST[ $this->table_fields["year"] ];
							
							$pay_row = new cPay_row();
							$pay_row->class_settings = $this->class_settings;
							$pay_row->class_settings[ 'action_to_perform' ] = 'get_pay_roll_records';
							$p = $pay_row->pay_row();
							
							if( is_array( $p ) && ! empty( $p ) ){
								
								$cdate = mktime( 0,0,0, $month, 1, $year );
								$pay_row->class_settings["start_date"] = $cdate;
								$pay_row->class_settings["end_date"] = mktime( 0,0,0, $month, date("t", $cdate ), $year );
								
								$c = $pay_row->pay_row();
								
								if( is_array( $c ) && ! empty( $c ) ){
									$err = new cError(010014);
									$err->action_to_perform = 'notify';
									$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
									$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
									$err->additional_details_of_error = '<h4>Existing Pay Roll</h4><p>Data already exists for the month you intend to generate pay roll</p><p>You will have to first delete the existing month data in order to continue generating the pay roll</p>';
									return $err->error();
								}
								unset( $c  );
								
								$return = $this->_save_changes();
								if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
									$pay_row->class_settings["reference"] = $return['saved_record_id'];
									unset( $return );
									
									$pay_row->class_settings["dataset"] = $p;
									$pay_row->class_settings["date"] = mktime( 0,0,0, $month, 27, $year );
									$pay_row->class_settings[ 'action_to_perform' ] = 'generate_new_pay_roll';
									$r = $pay_row->pay_row();
									
									if( $r ){
										$err = new cError(010011);
										$err->html_format = 2;
										$err->action_to_perform = 'notify';
										$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
										$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
										$err->additional_details_of_error = '<h4>Pay Roll Generation Successful</h4><p>Generated Pay Roll: <strong>'.date("F, Y", $cdate).'</strong><br />Rolled Over Pay Roll: <strong>'.date("F, Y", $pdate).'</strong></p>';
										$r1 = $err->error();
										
										$r1[ "html_replacement_selector" ] = "#modal-replacement-handle";
										$r1[ "html_replacement" ] = $r1["html"];
										unset( $r1["html"] );
										$r1[ "status" ] = "new-status";
										
										
										return $r1;
									}else{
										$err = new cError(010014);
										$err->action_to_perform = 'notify';
										$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
										$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
										$err->additional_details_of_error = '<h4>Unknown Error</h4>Failed to generate Pay Roll';
										return $err->error();
									}
								}
							}
							
							$err = new cError(010014);
							$err->action_to_perform = 'notify';
							$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
							$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
							$err->additional_details_of_error = '<h4>No Previous Month Info</h4>There are no records to roll over in the previous month that you specified';
							return $err->error();
						}
							
						$err = new cError(010014);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = '<h4>Undefined Current Month Info</h4>Please specify the month to generate pay roll for';
						return $err->error();
					}
					
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Undefined Previous Month Info</h4>Please specify previous month info to roll over';
					return $err->error();
				break;
				}
			}
		}
		
		private function _auto_generate_form(){
			$this->class_settings['form_submit_button'] = 'Auto Generate &rarr;';
			$this->class_settings[ 'form_action_todo' ] = 'save_and_auto_generate';
			
			$this->class_settings['do_not_show_headings'] = 1;
			$this->class_settings['form_values_important'][ $this->table_fields["month"] ] = date("n");
			$this->class_settings['form_values_important'][ $this->table_fields["year"] ] = date("Y");
			
			$pyear = date("Y");
			$pmonth = date("n");
			if( $pmonth == 1 ){
				$pmonth = 12;
				--$pyear;
			}else{
				--$pmonth;
			}
			$this->class_settings['form_values_important'][ $this->table_fields["previous_month"] ] = $pmonth;
			$this->class_settings['form_values_important'][ $this->table_fields["previous_year"] ] = $pyear;
			
			return $this->_generate_new_data_capture_form();
		}
		
		private function _display_all_records_full_view(){
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Auto Generated Pay Roll Manager";
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
			
			$this->class_settings['form_heading_title'] = 'New Auto Generated Pay Roll';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Auto Generated Pay Roll';
			break;
			}
			
			//$this->class_settings['hidden_records'][ $this->table_fields["total_salary"] ] = 1;
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
				
				$err->class_that_triggered_error = 'cpay_roll_auto_generate.php';
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
					$record = $this->_get_pay_roll_auto_generate();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_pay_roll_auto_generate(){
			
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
					//$this->_get_pay_roll_auto_generate();
				}
				
				return $single_data;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-pay_roll_auto_generate-',//.$record["member_id"],
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
					'cache_key' => $cache_key.'-pay_roll_auto_generate-'.$record["member_id"],
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