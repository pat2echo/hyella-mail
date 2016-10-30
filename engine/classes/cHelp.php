<?php
	/**
	 * help Class
	 *
	 * @used in  				help Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	help
	 */

	/*
	|--------------------------------------------------------------------------
	| help Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cHelp{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'help';
		
		private $associated_cache_keys = array(
			'default' => 'help',
			'extracted-line-items' => 'cash_calls_raw_data_import-line-items-data-',
			'imported-cash-call' => 'import_cash_call-',
		);
		
		private $table_fields = array(
			'category' => 'help001',
			'description' => 'help002',
			'price' => 'help003',
			'currency' => 'help004',
			'year' => 'help005',
			'remark' => 'help006',
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
	
		function help(){
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
			case 'get_help':
				$returned_value = $this->_get_help();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'display_popup_menu':
				$returned_value = $this->_display_popup_menu();
			break;
			case 'display_help_library':
				$returned_value = $this->_display_help_library();
			break;
			}
			
			return $returned_value;
		}
		
		private function _display_popup_menu(){
			//ATTACH FILES
			/*----------*/
			$return = array();
			
			$title = "";
			$file = "";
			$sel = "#main-table-view";
			if( isset( $_GET["month"] ) ){
				switch( $_GET["month"] ){
				case "budget":
					$title = "Budgets / Cash Calls Video Guide";
					$file = "videos/budget-cash-calls/Budget - Cash Calls - Get Started_player.html";
				break;
				case "budget1":
					$title = "Budgets / Cash Calls Video Guide";
					$file = "videos/budget-cash-calls/Budget - Cash Calls - Get Started_player.html";
					$sel = "#dash-board-main-content-area";
				break;
				case "tendering":
					$title = "Tenders & Contracts Video Guide";
					$file = "videos/tenders/Untitled_player.html";
				break;
				case "exploration":
					$title = "Exploration Video Guide";
					$file = "videos/tenders/exploration_player.html";
				break;
				case "dreports":
					$title = "Divisional Reports Video Guide";
					$file = "Divisional Reports.htm";
				break;
				case "memos":
					$title = "Letters & Memos Video Guide";
					$file = "Letters & Memos.htm";
				break;
				case "analytics":
					$title = "Budget & Cash Calls Analytics Video Guide";
					$file = "videos/analtytics/analtytics_player.html";
					$sel = "#dash-board-main-content-area";
				break;
				default:
					$title = "PDF Report";
					$file = $_GET["month"];
					$sel = "#dash-board-main-content-area";
				break;
				}
			}
			
			if( $file ){
				
				$this->class_settings[ 'data' ] = array();
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/pop-up' );
				
				$this->class_settings[ 'data' ][ "html_title" ] = $title;
				
				$this->class_settings[ 'data' ]['html'] = '<iframe src="'.$file.'" style="border:none; min-height:400px; width:100%;"></iframe>';
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'do_not_reload_table' => 1,
					'html_prepend' => $returning_html_data,
					'html_prepend_selector' => $sel,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'prepare_new_record_form_new' ),
				);
			}
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$title = "";
			$file = "";
			
			if( isset( $_GET["month"] ) ){
				switch( $_GET["month"] ){
				case "budget":
					$title = "Budgets / Cash Calls Help Files";
					$file = "Budgets & Cash Calls.htm";
				break;
				case "tendering":
					$title = "Tenders & Contracts Help Files";
					$file = "Tenders.htm";
				break;
				case "exploration":
					$title = "Exploration Help Files";
					$file = "Exploration.htm";
				break;
				case "dreports":
					$title = "Divisional Reports Help Files";
					$file = "Divisional Reports.htm";
				break;
				case "memos":
					$title = "Letters & Memos Help Files";
					$file = "Letters & Memos.htm";
				break;
				}
			}
			
			if( $file ){
				$this->class_settings[ 'data' ]['title'] = $title;
				$this->class_settings[ 'data' ]['file'] = $file;
				$this->class_settings[ 'data' ]['hide_main_title'] = 1;
				
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'html' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'do_not_reload_table' => 1,
					'javascript_functions' => array(  'set_function_click_event', 'nwResizeWindow.resizeWindowImport' ) 
				);
			}
		}
		
		private function _display_help_library(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-help-library' );
			
			$title = "";
			$file = "";
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'do_not_reload_table' => 1,
				'javascript_functions' => array(  'set_function_click_event' ) 
			);
			
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
			
			$this->class_settings['do_not_show_headings'] = 1;
			$this->class_settings['form_submit_button'] = 'Save Changes &rarr;';
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			$this->class_settings['values'] = array(
				$this->table_fields[ 'year' ] => date("U"),
			);
			
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
			
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->_get_help();
			$this->_clear_related_tables();
			
			return $returning_html_data;
		}
		
		private function _clear_related_tables(){
			//clear budgets_details
			$budget = new cBudget();
			$budget->class_settings = $this->class_settings;
			$budget->class_settings["action_to_perform"] = "clear_deleted_records";
			$budget->budget();
			unset( $budget );
			
			$exploration_drilling = new cExploration_drilling();
			$exploration_drilling->class_settings = $this->class_settings;
			$exploration_drilling->class_settings["action_to_perform"] = "clear_deleted_records";
			$exploration_drilling->exploration_drilling();
			unset( $exploration_drilling );
			
			$exploration_activities = new cExploration_activities();
			$exploration_activities->class_settings = $this->class_settings;
			$exploration_activities->class_settings["action_to_perform"] = "clear_deleted_records";
			$exploration_activities->exploration_activities();
			unset( $exploration_activities );
			
			$tendering = new cTendering();
			$tendering->class_settings = $this->class_settings;
			$tendering->class_settings["action_to_perform"] = "clear_deleted_records";
			$tendering->tendering();
			unset( $tendering );
			
			$geophysics_plan_and_actual_performance = new cGeophysics_plan_and_actual_performance();
			$geophysics_plan_and_actual_performance->class_settings = $this->class_settings;
			$geophysics_plan_and_actual_performance->class_settings["action_to_perform"] = "clear_deleted_records";
			$geophysics_plan_and_actual_performance->geophysics_plan_and_actual_performance();
			unset( $geophysics_plan_and_actual_performance );
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
				
				$err->class_that_triggered_error = 'chelp.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			$budget_id = '';
			if( isset( $_GET['budget_id'] ) && $_GET['budget_id'] ){
				$budget_id = $_GET['budget_id'];
				
				$title_text = "Apply all Changes Made to Line Items";
				$caption = "Apply Changes";
				
				$this->datatable_settings['custom_edit_button'] = '<a href="#" class="custom-action-button btn btn-mini btn-sm btn-danger" function-id="'.$budget_id.'" search-table="" function-class="'.$this->table_name.'" function-name="apply_changes" module-id="'.$this->class_settings['current_module'].'" module-name="" action="?&module='.$this->class_settings['current_module'].'&action='.$this->table_name.'&todo=apply_changes" mod="apply_changes-'.md5($this->table_name).'" todo="apply_changes" title="'.$title_text.'">'.$caption.'</a>';
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
				//'status' => 'new-status',
				//'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state' ),
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
					$this->class_settings['current_record_id'] = $returning_html_data['saved_record_id'];
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->_get_help();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_help(){
			
			$cache_key = $this->table_name;
			
			if( ! isset( $this->class_settings['current_record_id'] ) )return array();
			
			$settings = array(
				'cache_key' => $cache_key,
				'directory_name' => $cache_key,
				'permanent' => true,
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
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `id`='".$this->class_settings['current_record_id']."'";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$data = execute_sql_query($query_settings);
			
			$single_data = array();
			$bulk_data = array();
			
			if( is_array( $data ) && ! empty( $data ) ){
				
				foreach( $data as $record ){
					$settings = array(
						'cache_key' => $cache_key.'-'.$record['id'],
						'cache_values' => $record,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				return $bulk_data;
			}
		}
		
	}
?>