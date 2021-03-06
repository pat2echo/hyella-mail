<?php
	/**
	 * modules Class
	 *
	 * @used in  				modules Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	modules
	 */

	/*
	|--------------------------------------------------------------------------
	| modules Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cGeneral_settings{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'general_settings';
		
		private $table_fields = array(
			'key' => 'general_settings001',
			'value' => 'general_settings002',
			'description' => 'general_settings003',
			'type' => 'general_settings004',
			'classname' => 'general_settings005',
			'country' => 'general_settings006',
            'start_date' => 'general_settings007',
			'end_date' => 'general_settings008',
		);
		
		function general_settings(){
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
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'display_all_records':
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
				
				//Add id of Newly Created Record
				if( $this->current_record_id ){
					$returned_value['saved_record_id'] = $this->current_record_id;
				}
			break;
			case 'get_general_settings':
				$returned_value = $this->_get_general_settings();
			break;
			case 'reset_website_cache':
				$returned_value = $this->_reset_website_cache();
			break;
			case 'clear_cache':
				$returned_value = $this->_clear_cache();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			}
			
			return $returned_value;
		}
		
		private function _refresh_cache(){
			$this->_update_cache();
		}
		
		private function _display_datatable_view(){
			//RETURN CASH CALLS VIEW FOR SELECTED MONTH
			/*---------------------------------------*/
			$budget_id = '';
			if( isset( $_GET['budget_id'] ) && $_GET['budget_id'] ){
				$budget_id = $_GET['budget_id'];
			}
			
			//SET FILTER QUERY
			$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = " AND `".$this->table_name."`.`".$this->table_fields[ 'budget_id' ]."` = '".$budget_id."' ";
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/line-items-datatable-view.php' );
			$datatable = $this->_display_data_table();
			
			$this->class_settings[ 'data' ]['title'] = get_select_option_value( array( 'id' => $budget_id, 'function_name' => 'get_all_budgets' ) ).' All Line Items';
			$this->class_settings[ 'data' ]['form_title'] = 'Add / Edit Line Items';
			
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state' ),
				'html_replacement_selector' => '#main-table-view',
			);
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage General Settings";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
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
		
        private function _reset_website_cache(){
            clear_load_time_cache();
            
            $err = new cError(010002);
            $err->action_to_perform = 'notify';
            $err->class_that_triggered_error = 'cGeneral_settings.php';
            $err->method_in_class_that_triggered_error = '_reset_website_cache';
            $err->additional_details_of_error = '';
            $return  = $err->error();
                
            $return['err'] = 'Successful Cache Reset';
			$return['msg'] = '<p>Website cache successfully reset</p>';
            
            return $return;
        }
        
        private function _clear_cache(){
            clear_load_time_cache();
            
            $err = new cError(010002);
            $err->action_to_perform = 'notify';
            $err->class_that_triggered_error = 'cGeneral_settings.php';
            $err->method_in_class_that_triggered_error = '_reset_website_cache';
            $err->additional_details_of_error = '';
            $return  = $err->error();
                
            $return['err'] = 'Successful Cache Reset';
			$return['msg'] = '<p>Application Cache Successfully Reset</p>';
            
            return $return;
        }
        
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			$process_handler->class_settings['hidden_records1'] = array(
                $this->table_fields['description'] => 1,
                $this->table_fields['classname'] => 1,
                $this->table_fields['country'] => 1,
                $this->table_fields['start_date'] => 1,
                $this->table_fields['end_date'] => 1,
            );
			$process_handler->class_settings['disable_form_element1'] = array(
                $this->table_fields['key'] => ' disabled="disabled" ',
                $this->table_fields['type'] => ' disabled="disabled" ',
            );
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
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
				//REPORT INVALid TABLE ERROR
				$err = new cError(000001);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cgeneral_settings.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$form->datatables_settings = array(
				'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
					'show_add_new' => 0,			//Determines whether or not to show add new record button
					'show_advance_search' => 1,		//Determines whether or not to show advance search button
					'show_column_selector' => 1,	//Determines whether or not to show column selector button
					'show_units_converter' => 0,	//Determines whether or not to show units converter
						'show_units_converter_volume' => 0,
						'show_units_converter_currency' => 0,
						'show_units_converter_currency_per_unit_kvalue' => 0,
						'show_units_converter_kvalue' => 0,
						'show_units_converter_time' => 0,
						'show_units_converter_pressure' => 0,
					'show_edit_button' => 1,		//Determines whether or not to show edit button
					'show_delete_button' => 0,		//Determines whether or not to show delete button
					
				'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
					//'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
				
				'show_details' => 1,				//Determines whether or not to show details
				'show_serial_number' => 1,			//Determines whether or not to show serial number
				
				'show_verification_status' => 0,	//Determines whether or not to show verification status
				'show_creator' => 0,				//Determines whether or not to show record creator
				'show_modifier' => 0,				//Determines whether or not to show record modifier
				'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
				
				'current_module_id' => $this->class_settings['current_module'],	//Set id of the currently viewed module
			);
			
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
			
			$this->_update_cache( $returning_html_data );
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'saved-form-data';
			
			return $returning_html_data;
		}
		
		private function _update_cache( $settings = array() ){
			$query = "";
			
			$cache_settings = array(
				'cache_key' => $this->table_name,
				'permanent' => true,
			);
			$cached_values = clear_cache_for_special_values( $cache_settings );
			$cached_values = get_cache_for_special_values( $cache_settings );
			if( ! $cached_values ){
				$cached_values = array();
				
				$query = "SELECT `".$this->table_fields['key']."` as 'key', `".$this->table_fields['value']."` as 'value', `".$this->table_fields['classname']."` as 'classname', `".$this->table_fields['country']."` as 'country', `".$this->table_fields['start_date']."` as 'start_date', `".$this->table_fields['end_date']."` as 'end_date' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1'";
			}else{
				if( isset( $settings['saved_record_id'] ) ){
					//Pull up user role record to get functions data
					$query = "SELECT `".$this->table_fields['key']."` as 'key', `".$this->table_fields['value']."` as 'value', `".$this->table_fields['classname']."` as 'classname', `".$this->table_fields['country']."` as 'country', `".$this->table_fields['start_date']."` as 'start_date', `".$this->table_fields['end_date']."` as 'end_date' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `id`='" . $settings['saved_record_id'] . "'";
				}
			}
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $a ){
					if( $a['country'] && $a['country'] != 'default' && $a['country'] != 'undefined' ){
						$cached_values[ $a['classname'] ][ $a['key'] ][ $a['country'] ] = $a['value'];
					}else{
						$cached_values[ $a['classname'] ][ $a['key'] ][ 'default' ] = $a['value'];
					}
                    $cached_values[ $a['classname'] ][ $a['key'] ][ 's_date' ] = $a['start_date'];
                    $cached_values[ $a['classname'] ][ $a['key'] ][ 'e_date' ] = $a['end_date'];
				}
			}
			
			$cache_settings = array(
				'cache_key' => $this->table_name,
				'cache_values' => $cached_values,
				'permanent' => true,
			);
			set_cache_for_special_values( $cache_settings );
		}
		
		private function _get_general_settings(){
			$cache_settings = array(
				'cache_key' => $this->table_name,
				'permanent' => true,
			);
			$cached_values = get_cache_for_special_values( $cache_settings );
			if( $cached_values ){
				return $cached_values;
			}
			
			//Pull up user role record to get functions data
			$query = "SELECT `".$this->table_fields['key']."` as 'key', `".$this->table_fields['value']."` as 'value', `".$this->table_fields['classname']."` as 'classname' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1'";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			$cached_values = array();
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $a ){
					$cached_values[ $a['classname'] ][ $a['key'] ] = $a['value'];
				}
			}
			
			$cache_settings = array(
				'cache_key' => $this->table_name,
				'cache_values' => $cached_values,
				'permanent' => true,
			);
			set_cache_for_special_values( $cache_settings );
			
			return $cached_values;
		}
		
	}
?>