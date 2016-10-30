<?php
	/**
	 * customer_call_log Class
	 *
	 * @used in  				customer_call_log Function
	 * @created  				08:22 | 06-06-2016
	 * @database table name   	customer_call_log
	 */
	
	class cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'customer_call_log';
		
		private $associated_cache_keys = array(
			'customer_call_log',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		private $table_fields = array(
			'date' => 'customer_call_log001',
			'customer' => 'customer_call_log002',
			'reason_for_call' => 'customer_call_log003',
			'feedback' => 'customer_call_log004',
			'category' => 'customer_call_log005',
			'comment' => 'customer_call_log006',
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
	
		function customer_call_log(){
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
			case 'display_app_view':
				$returned_value = $this->_display_app_view();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes();
			break;
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
				$returned_value = $this->_new_popup_form();
			break;
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log();
			break;
			}
			
			return $returned_value;
		}
		
		private function _search_customer_call_log(){
			if( ! ( isset( $_POST["customer"] ) && $_POST["customer"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Customer</h4>Please select a customer first';
				return $err->error();
			}
			
			$this->class_settings["where"] = " AND `".$this->table_name."`.`".$this->table_fields[ "customer" ]."` = '".$_POST["customer"]."' ";
			$data = $this->_get_all_customer_call_log();
			
			$this->class_settings[ 'data' ][ "items" ] = $data;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/item-list.php' );
			$returning_html_data = $this->_get_html_view();
			
			$return = array();
			
			$return["status"] = "new-status";						
			$return["html_replacement_selector"] = "#customer-call-log-container";
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}
		
		private function _new_popup_form(){
			$err = 0;
			$c_name = "";
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = 1;
			}else{
				$c = get_customers();
				if( ! isset( $c[ $_POST["id"] ] ) ){
					$err = 1;
				}
				$c_name = $c[ $_POST["id"] ];
			}
			
			if( $err ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Customer</h4>Please select a customer first';
				return $err->error();
			}
			
			$this->class_settings[ "form_values_important" ][ $this->table_fields[ "customer" ] ] = $_POST["id"];
			$this->class_settings[ "form_values_important" ][ $this->table_fields[ "date" ] ] = date("U");
			
			$this->class_settings[ "hidden_records_css" ][ $this->table_fields[ "date" ] ] = 1;
			$this->class_settings[ "hidden_records_css" ][ $this->table_fields[ "customer" ] ] = 1;
			
			$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_new_popup';
			$this->class_settings['do_not_show_headings'] = 1;
			$d = $this->_generate_new_data_capture_form();
			
			$html = "";
			if( isset( $d["html"] ) )$html = $d["html"];
			
			switch( $this->class_settings["action_to_perform"] ){
			case "new_popup_form_in_popup":
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => "#modal-replacement-handle",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( "prepare_new_record_form_new" , "set_function_click_event" ),
				);
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = $c_name.": New Call Log";
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
		
		private function _delete_app_record(){
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$_POST['mod'] = 'delete-'.md5( $this->table_name );
				$return = $this->_delete_records();
				if( isset( $return['deleted_record_id'] ) && $return['deleted_record_id'] ){
					unset( $return["html"] );
					$return["status"] = "new-status";
					
					$return["html_removal"] = "#".$return['deleted_record_id'];
					$return["javascript_functions"] = array( "nwcustomer_call_log.emptyNewItem" );
					return $return;
					
				}
			}
		}
		
		private function _save_app_changes(){
			$return = array();
			$js = array( 'nwcustomer_call_log.init' );
			$new = 0;
			
			$filename = "item-list.php";
			$no_skip = 1;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				$no_skip = 0;
				$filename = "view-details.php";
			break;
			}
			
			if( isset( $_POST['id'] ) ){
				
				if( $no_skip ){
					foreach( $this->table_fields as $key => $val ){
						if( isset( $_POST[ $key ] ) ){
							$_POST[ $val ] = $_POST[ $key ];
							unset( $_POST[ $key ] );
						}
					}
					
					if( ! $_POST['id'] ){
						//new mode
						$js[] = 'nwcustomer_call_log.reClick';
						$new = 1;
					}
					
					$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
					$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
					$_POST[ "table" ] = $this->table_name;
					$_POST[ "processing" ] = md5(1);
					if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
						define('SKIP_USE_OF_FORM_TOKEN', 1);
				}
				
				$return = $this->_save_changes();
				if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
					
					$this->class_settings[ 'current_record_id' ] = $return['saved_record_id'];
					unset( $this->class_settings[ 'do_not_check_cache' ] );
					$e = $this->_get_customer_call_log();
					
					if( isset( $e["id"] ) && $return['saved_record_id'] == $e["id"] ){
						
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
						
						switch( $this->class_settings["action_to_perform"] ){
						case 'save_new_popup':
							$t = $this->table_name;
							$this->class_settings[ 'data' ][ "labels" ] = $t();
							$this->class_settings[ 'data' ][ "fields" ] = $this->table_fields;
							$this->class_settings[ 'data' ]["items"] = array( $return['saved_record_id'] => $e );
							$returning_html_data = $this->_get_html_view();
							
							$js = array( "set_function_click_event" );
							
							unset( $return["html"] );
							$return["status"] = "new-status";
							
							$return["html_replacement_selector"] = "#modal-replacement-handle";
							$return["html_replacement"] = $returning_html_data;
							$return["javascript_functions"] = $js;
							return $return;
						break;
						}
						
						$this->class_settings[ 'data' ]["item"] = $e;
						$returning_html_data = $this->_get_html_view();
						
						if( $new ){							
							unset( $return["html"] );
							$return["status"] = "new-status";
							
							$return["html_prepend_selector"] = "#recent-expenses tbody";
							$return["html_prepend"] = $returning_html_data;
							$return["javascript_functions"] = $js;
							return $return;
						}
						
						unset( $return["html"] );
						$return["status"] = "new-status";
						
						$return["html_replace_selector"] = "#".$e["id"];
						$return["html_replace"] = $returning_html_data;
						$return["javascript_functions"] = $js;
						return $return;
					}
				}
			}
			
			
			return $return;
		}
		
		private function _display_app_view(){
			
			$this->class_settings[ 'data' ]['customer_call_log'] = $this->_get_all_customer_call_log();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-app-view' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _get_all_customer_call_log(){
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`modified_by`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			$where = "";
			if( isset( $this->class_settings["where"] ) && $this->class_settings["where"] )
				$where = $this->class_settings["where"];
			
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
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Customer Call Log";
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
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Call Log';
				//$this->class_settings['hidden_records'][ $this->table_fields["member_id"] ] = 1;
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
				$this->_get_customer_call_log();
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
				
				$err->class_that_triggered_error = 'ccustomer_call_log.php';
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
					$record = $this->_get_customer_call_log();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_customer_call_log(){
			
			$cache_key = $this->table_name;
			if( ! isset( $this->class_settings[ 'current_record_id' ] ) )return 0;
			
			$settings = array(
				'cache_key' => $cache_key . "-" . $this->class_settings[ 'current_record_id' ],
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
				else $select = "`id`, `serial_num`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `id` = '".$this->class_settings[ 'current_record_id' ]."'";
			
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
				
				foreach( $all_departments as $category ){
					$departments[ $category["id"] ] = $category;
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key . "-" . $this->class_settings[ 'current_record_id' ],
						'cache_values' => $category,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
				}
				return $category;
			}
			
		}
		
	}
?>