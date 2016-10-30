<?php
	/**
	 * repairs Class
	 *
	 * @used in  				repairs Function
	 * @created  				08:22 | 06-06-2016
	 * @database table name   	repairs
	 */
	
	class cRepairs{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'repairs';
		
		private $associated_cache_keys = array(
			'repairs',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		private $table_fields = array(
			'date' => 'repairs001',
			'description' => 'repairs002',
			'image' => 'repairs003',
			'customer' => 'repairs004',
			'vendor' => 'repairs005',
			'amount_due' => 'repairs006',
			'amount_paid' => 'repairs007',
			'cost_of_repair' => 'repairs008',
			'status' => 'repairs009',
			'date_sent_to_vendor' => 'repairs010',
			'date_to_collect_from_vendor' => 'repairs011',
			'date_collected_from_vendor' => 'repairs012',
			'date_returned_to_customer' => 'repairs013',
			'comment' => 'repairs014',
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
	
		function repairs(){
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
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes();
			break;
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'view_repair_details':
				$returned_value = $this->_view_repair_details();
			break;
			case 'update_repair_status':
				$returned_value = $this->_update_repair_status();
			break;
			case 'save_update_repair_status':
				$returned_value = $this->_save_update_repair_status();
			break;
			case "new_guest_form":
				$returned_value = $this->_new_guest_form();
			break;
			case "save_new_guest":
				$returned_value = $this->_save_new_guest();
			break;
			}
			
			return $returned_value;
		}
		
		private function _save_new_guest(){
			
			$customers = new cCustomers();
			$customers->class_settings = $this->class_settings;
			$customers->class_settings[ 'action_to_perform' ] = 'save';
			$return = $customers->customers();
			
			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				$id = $return["saved_record_id"];
				$c = get_customers();
				
				$err = new cError(010011);
				$err->html_format = 2;
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>New Customer Successfully Created</h4>';
				$return = $err->error();
				
				$return["html_replacement"] = $return["html"];
				
				unset( $return["html"] );
				$return["status"] = "new-status";
				$return[ "html_replacement_selector" ] = "#modal-replacement-handle";
				
				$return[ "html_append" ] = '<option value="'. $id .'">'. ( isset( $c[ $id ] )?$c[ $id ]:"N/A" ) .'</option>';
				
				$return[ "html_append_selector" ] = 'select[name="customer"]';
				
				//$return[ "javascript_functions" ] = array( "nwMakeReservation.refreshCustomersList" );
				
			}
			return $return;
		}
		
		private function _new_guest_form(){
			
			$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_new_guest';
			$customers = new cCustomers();
			$customers->class_settings = $this->class_settings;
			$customers->class_settings[ 'action_to_perform' ] = 'new_customer_form';
			$d = $customers->customers();
			
			$html = "";
			if( isset( $d["html"] ) )$html = $d["html"];
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = "Create New Customer";
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
		
		private function _save_update_repair_status(){
			$return = $this->_save_changes();
			
			unset( $return["html"] );
			$return["status"] = "new-status";
			//$return["javascript_functions"] = array( "nwRepairs.reClick" );
			
			return $return;
		}
		
		private function _update_repair_status(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			$_POST["mod"] = "edit-" . md5($this->table_name);
			
			$this->class_settings["do_not_show_headings"] = 1;
			$this->class_settings['form_submit_button'] = 'Update Status &rarr;';
			
			$this->class_settings[ 'form_action_todo' ] = 'save_update_repair_status';
			
			$this->class_settings["hidden_records"][ $this->table_fields["description"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["date"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["image"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["customer"] ] = 1;
			
			$html = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = "Update Repair Status";
			$this->class_settings[ 'data' ]['html'] = $html["html"];
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new', "nwRepairs.activateDate"  ),
			);
			
		}
		
		private function _view_repair_details(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			$this->class_settings[ 'current_record_id' ] = $_POST["id"];
			unset( $this->class_settings[ 'do_not_check_cache' ] );
			$e = $this->_get_repairs();
					
			$t = $this->table_name;
			$this->class_settings[ 'data' ][ "labels" ] = $t();
			$this->class_settings[ 'data' ][ "fields" ] = $this->table_fields;
			$this->class_settings[ 'data' ][ "item" ] = $e;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/view-repair-details.php' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#view-repair-job-container",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
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
					$return["javascript_functions"] = array( "nwrepairs.emptyNewItem" );
					return $return;
				}
			}
		}
		
		private function _save_app_changes(){
			$return = array();
			$js = array( 'nwRepairs.init' );
			$new = 0;
			if( isset( $_POST['id'] ) ){
				
				foreach( $this->table_fields as $key => $val ){
					if( isset( $_POST[ $key ] ) ){
						$_POST[ $val ] = $_POST[ $key ];
						unset( $_POST[ $key ] );
					}
				}
				
				if( ! $_POST['id'] ){
					//new mode
					$js[] = 'nwRepairs.reClick';
					$_POST[ $this->table_fields["status"] ] = 'item_collected';
					$new = 1;
				}
				
				$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
				$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
				$_POST[ "table" ] = $this->table_name;
				$_POST[ "processing" ] = md5(1);
				if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
					define('SKIP_USE_OF_FORM_TOKEN', 1);
				
				$return = $this->_save_changes();
				if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
					
					unset( $this->class_settings[ 'do_not_check_cache' ] );
					$e = $this->_get_repairs();
					
					if( isset( $e["id"] ) ){
						$this->class_settings[ 'data' ]["item"] = $e;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/expense-list.php' );
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
			
			$this->class_settings[ 'data' ]['repairs'] = $this->_get_all_repairs();
			
			$this->class_settings[ 'filter' ] = "in-repair";
			$this->class_settings[ 'data' ]['in_repair'] = $this->_get_all_repairs();
			
			$this->class_settings[ 'filter' ] = "repaired";
			$this->class_settings[ 'data' ]['repaired'] = $this->_get_all_repairs();
			
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
		
		private function _get_all_repairs(){
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			$where = "";
			if( isset( $this->class_settings[ 'filter' ] ) && $this->class_settings[ 'filter' ] ){
				switch( $this->class_settings[ 'filter' ] ){
				case "in-repair":
					$where = " AND ( `".$this->table_name."`.`".$this->table_fields["status"]."` = 'item_collected' OR `".$this->table_name."`.`".$this->table_fields["status"]."` = 'sent_to_vendor' ) ";
				break;
				case "repaired":
					$where = " AND ( `".$this->table_name."`.`".$this->table_fields["status"]."` = 'received_from_vendor' OR `".$this->table_name."`.`".$this->table_fields["status"]."` = 'return_to_customer' ) ";
				break;
				}
			}
			
			$query = "SELECT ".$select." FROM `".$this->class_settings['database_name']."`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status` = '1' " . $where . " ORDER BY `".$this->table_name."`.`".$this->table_fields["date"]."` DESC ";
			
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
			
			$this->class_settings[ 'data' ]['title'] = "Manage Repair Jobs";
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
				$this->class_settings['form_heading_title'] = 'Modify Repairs';
				//$this->class_settings['hidden_records'][ $this->table_fields["member_id"] ] = 1;
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
				
				$this->class_settings[ 'do_not_check_cache' ] = 1;
				$this->_get_repairs();
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
				
				$err->class_that_triggered_error = 'crepairs.php';
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
					$record = $this->_get_repairs();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_repairs(){
			
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