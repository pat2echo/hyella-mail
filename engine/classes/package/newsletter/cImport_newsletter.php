<?php
	/**
	 * import_newsletter Class
	 *
	 * @used in  				import_newsletter Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	import_newsletter
	 */

	/*
	|--------------------------------------------------------------------------
	| import_newsletter Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cImport_newsletter{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'import_newsletter';
		
		private $associated_cache_keys = array(
			'import_newsletter',
		);
		
		private $table_fields = array(
			'file' => 'import_newsletter001',
			'operator' => 'import_newsletter002',
			'import_template_type' => 'import_newsletter003',
			'unit' => 'import_newsletter004',
			'month' => 'import_newsletter005',
			'department' => 'import_newsletter006',
			'budget_code' => 'import_newsletter007',
			
			'starting_row' => 'import_newsletter008',
			'email_column' => 'import_newsletter009',
			'first_name_column' => 'import_newsletter010',
			'last_name_column' => 'import_newsletter011',
			'phone_column' => 'import_newsletter012',
			'category_column' => 'import_newsletter013',

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
	
		function import_newsletter(){
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
			case 'get_import_newsletter':
				$returned_value = $this->_get_import_newsletter();
			break;
			case 'generate_excel_import_form':
				$returned_value = $this->_generate_excel_import_form();
			break;
			case 'save_and_start_operator_excel_import':
				$returned_value = $this->_save_and_start_operator_excel_import();
			break;
			case 'import_excel_file_data':
				$returned_value = $this->_import_excel_file_data();
			break;
			case 'import_newsletter':
			case 'import_napims_cash_calls':
			case 'import_napims_returns':
			case 'import_returns':
			case 'import_budget':
			case 'import_napims_budget':
				$returned_value = $this->_import_newsletter();
			break;
			case 'get_field_mapping_form':
				$returned_value = $this->_get_field_mapping_form();
			break;
			case 'save_and_proceed_with_import':
				$returned_value = $this->_save_and_proceed_with_import();
			break;
			}
			
			return $returned_value;
		}
		
		private function _save_and_proceed_with_import(){
			//SAVE AND PROCEED WITH IMPORT PROCESS
			/*----------------------------------*/
			$import_table = 'newsletter_raw_data_import';
			$return = $this->_save_changes();
			
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
				
				$return['status'] = 'new-status';
				$return['err'] = 'Storing Mapped Columns &darr;';
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/import-progress.php' );
				$this->class_settings[ 'data' ] = array(
					'import_progress' => array( 
						0 => array( 'title' => 'Storing Mapped Columns' ),
						1 => array( 'title' => 'Successfully Stored Mapped Columns' ),
					),
				);
				
				$return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view();
				
				//settings for ajax request reprocessing
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-'.md5( $this->table_name );
				$return['id'] = $return['saved_record_id'];
				$return['post_id'] = $return['id'];
				$return['action'] = '?action='.$import_table.'&todo=extract_line_newsletter_data';
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		private function _get_last_mapping_data( $table_name = "" ){
			//RETURN CODES OF EXISTING LINE newsletter FOR EACH MONTH
			if( ! ( isset( $this->class_settings['user_id'] ) && $this->class_settings['user_id']) )
				return array();
			
			if( ! $table_name )$table_name = $this->table_name;
			
			$csettings = array(
				'cache_key' => 'stored-mapping-' . $table_name . $this->class_settings['user_id'],
				'permanent' => true,
				'directory_name' => $this->table_name,
			);
			$import_map_id = get_cache_for_special_values( $csettings );
			
			if( $import_map_id ){
				if( isset( $this->class_settings['current_record_id'] ) )
					$keep = $this->class_settings['current_record_id'];
				
				$this->class_settings['current_record_id'] = $import_map_id;
				$import_details = $this->_get_import_newsletter();
				
				if( isset( $keep ) )
					$this->class_settings['current_record_id'] = $keep;
				
				if( isset( $import_details["id"] ) && $import_details["id"] )
					return $import_details;
			}
		}
		
		private function _get_field_mapping_form(){
			//ALLOW USERS SELECT COLUMNS FOR DATA EXTRACTION
			/*--------------------------------------------*/
			
			$table = "newsletter";
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				
				$this->class_settings[ 'current_record_id' ] = $_POST['id'];
				$this->class_settings[ 'current_record_details' ] = $this->_get_import_newsletter();
				
			}
			
			$previous_mapping = $this->_get_last_mapping_data( $table );
				
			foreach( $this->table_fields as $k => $v ){
				switch( $k ){
				case 'starting_row':
				case 'email_column':
				case 'first_name_column':
				case 'last_name_column':
				case 'phone_column':
				case 'category_column':
				break;
				default:
					$this->class_settings['hidden_records'][ $v ] = 1;
				break;
				}
				
				if( isset( $this->class_settings['hidden_records'][ $v ] ) && $this->class_settings['hidden_records'][ $v ] ){
					continue;
				}
				
				if( isset( $previous_mapping[ $k ] ) && $previous_mapping[ $k ] ){
					$this->class_settings[ 'form_values_important' ][ $v ] = $previous_mapping[ $k ];
				}
			}
			
			unset( $this->class_settings[ 'form_values_important' ][ $this->table_fields[ 'starting_row' ] ] );
			
			$_POST['mod'] = 'edit-'.md5( $this->table_name );
			/*
			$this->class_settings[ 'form_values_important' ] = array(
				$this->table_fields["budget_code"] => $budget_id,
				$this->table_fields["import_template_type"] => $import_type,
			);
			*/
			$this->class_settings['hide_clear_form_button'] = 1;
			$this->class_settings['do_not_show_headings'] = 1;
			$this->class_settings['form_submit_button'] = 'Continue &rarr;';
			$this->class_settings['form_action_todo'] = 'save_and_proceed_with_import';
			
			return $this->_generate_new_data_capture_form();
		}
		
		private function _import_newsletter(){
			//IMPORT WIZARD
			/*-----------*/
			$budget_id = '';
			$month = 0;
			if( isset( $_GET['month'] ) && $_GET['month'] ){
				$month = $_GET['month'];
			}
			if( isset( $_GET['budget'] ) && $_GET['budget'] ){
				$budget_id = $_GET['budget'];
			}
			
			if( isset( $_GET['budget_id'] ) && $_GET['budget_id'] ){
				$budget_id = $_GET['budget_id'];
			}
			
			$mapped_values = array(
				'import_budget' => 'operator-budget',
				'import_napims_budget' => 'napims-budget',
				'import_newsletter' => 'operator-cash-calls',
				'import_returns' => 'operator-performance-returns',
				
				'import_napims_cash_calls' => 'napims-cash-calls',
				'import_napims_returns' => 'napims-performance-returns',
				
				'import_tendering' => 'tendering',
				'import_tendering_status_updates' => 'tendering-status-update',
			);
			
			$import_type = "";
			if( isset( $mapped_values[ $this->class_settings['action_to_perform'] ] ) )
				$import_type = $mapped_values[ $this->class_settings['action_to_perform'] ];
				
			$this->class_settings[ 'form_values_important' ] = array(
				$this->table_fields["budget_code"] => $budget_id,
				$this->table_fields["import_template_type"] => $import_type,
			);
			
			switch( $this->class_settings['action_to_perform'] ){
			case 'import_newsletter':
			case 'import_returns':
				//$this->class_settings['hidden_records'][ $this->table_fields["operator"] ] = 1;
				//$this->class_settings['hidden_records'][ $this->table_fields["unit"] ] = 1;
				$this->class_settings["recent_activity_type"] = "newsletter";
			break;
			}
			
			$this->class_settings["import_type"] = get_select_option_value( array( 'id' => $import_type, 'function_name' => "get_import_template_types" ) );
			
			if( $budget_id && $budget_id != "-" ){
				//$this->class_settings[ 'hidden_records_css' ][ $this->table_fields["budget_code"] ] = 1;
				$this->class_settings["operator_text"] = get_select_option_value( array( 'id' => $budget_id, 'function_name' => "get_all_budgets" ) );
			}
			
			if( $import_type ){
				//$this->class_settings[ 'hidden_records_css' ][ $this->table_fields["import_template_type"] ] = 1;
			}
			
			if( ! defined( "SHOW_ALL_DEPARTMENTS" ) ){
				define( "SHOW_ALL_DEPARTMENTS", 1 );
			}
			
			if( ! defined( "SHOW_NO_UNITS" ) ){
				define( "SHOW_NO_UNITS", 1 );
			}
			
			$return = $this->_generate_excel_import_form();
			
			$return["html_replacement"] = $return["html"];
			unset( $return["html"] );
			$return["html_replacement_selector"] = "#main-table-view";
			$return["do_not_reload_table"] = 1;
			
			return $return;
		}
		
		private function _import_excel_file_data(){
			//IMPORT INTO CASH CALLS RAW DATA TABLE & TRIGGER CREATION OF CACHED VIEW FOR IMPORTED DATA
			/*---------------------------------------------------------------------------------------*/
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				
				$import_table = 'newsletter_raw_data_import';
				$this->class_settings[ 'current_record_id' ] = $_POST['id'];
				$this->class_settings[ 'current_record_details' ] = $this->_get_import_newsletter();
				
				$files = explode( ':::', $this->class_settings[ 'current_record_details' ][ 'file' ] );
				
				foreach( $files as $file ){
					if( $file && file_exists( $this->class_settings['calling_page'] . $file ) ){
						$this->class_settings['excel_reference_id'] = $_POST['id'];
						$this->class_settings['excel_file_name'] = $this->class_settings['calling_page'] . $file;
						$this->class_settings['import_table'] = $import_table;
						$this->class_settings['excel_field_mapping_option'] = 'serial-import';
						$this->class_settings['update_existing_records_based_on_fields'] = '';
						$this->class_settings['keep_excel_file_after_import'] = 1;
						$this->class_settings['accept_blank_excel_cells'] = 1;
						
						$myexcel = new cMyexcel();
						$myexcel->class_settings = $this->class_settings;
						$myexcel->class_settings[ 'action_to_perform' ] = 'bulk_import_excel_file_data';
						$excel = $myexcel->myexcel();
						
						if( ! ( isset( $excel['typ'] ) && $excel['typ'] == 'saved' ) ){
							//Return excel import error
							return $excel;
						}
					}else{
						$err = new cError(010014);
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = 'Invalid Excel File for Import';
						return $err->error();
					}
				}
					
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Extracting Data from Excel File &darr;';
				$return = $err->error();
				
				$return['status'] = 'new-status';
				
				$return['err'] = $err->additional_details_of_error;
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/import-progress.php' );
				$this->class_settings[ 'data' ] = array(
					'import_progress' => array( 
						0 => array( 'title' => 'Opened Excel File 4MB' ),
						1 => array( 'title' => 'Checked total number of columns in Excel File' ),
						2 => array( 'title' => 'Data Extraction Routines Started' ),
						3 => array( 'title' => 'Data Loading Routines Started' ),
						4 => array( 'title' => 'Data Successfully Loaded' ),
					),
				);
				
				$return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view();
				
				//settings for ajax request reprocessing
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-'.md5( $this->table_name );
				$return['id'] = $this->class_settings[ 'current_record_id' ];
				$return['post_id'] = $return['id'];
				$return['action'] = '?action='.$import_table.'&todo=create_cached_view_for_imported_records';
				
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		private function _save_and_start_operator_excel_import(){
			//SAVE EXCEL FILE IN DATABASE & TRIGGER IMPORT INTO CASH CALLS RAW DATA TABLE
			/*-------------------------------------------------------------------------*/
			$return = $this->_save_changes();
			
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
				
				$return['status'] = 'new-status';
				$return['err'] = 'Excel Import Started &darr;';
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/import-started.php' );
				$this->class_settings[ 'data' ] = array(
					'current_record_details' => $this->class_settings[ 'current_record_details' ],
				);
				
				$return['html_replacement_selector'] = '#excel-import-form-container';
				$return['html_replacement'] = $this->_get_html_view();
				
				//settings for ajax request reprocessing
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-'.md5( $this->table_name );
				$return['id'] = $return['saved_record_id'];
				$return['post_id'] = $return['id'];
				$return['action'] = '?action='.$this->table_name.'&todo=import_excel_file_data';
				
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		private function _generate_excel_import_form(){
			//GENERATE EXCEL IMPORT FORM FOR CASH CALLS, RETURNS, BUDGET
			/*---------------------------------------------------------*/
			foreach( $this->table_fields as $k => $v ){
				switch( $k ){
				case 'file':
				//case 'import_template_type':
				break;
				default:
					$this->class_settings['hidden_records'][ $v ] = 1;
				break;
				}
			}
			
			$this->class_settings['hide_clear_form_button'] = 1;
			$this->class_settings['form_heading_title'] = '<h4>Select Excel File & Start Import &darr;</h4>';
			$this->class_settings['form_submit_button'] = 'Start Import &rarr;';
			$this->class_settings['form_action_todo'] = 'save_and_start_operator_excel_import';
			
			$recent_activity_data = array();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/import-excel-form.php' );
			$this->class_settings[ 'data' ] = array(
                'excel_import_form' => $this->_generate_new_data_capture_form(),
				'import_type' => isset( $this->class_settings["import_type"] )?$this->class_settings["import_type"]:"",
            );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new', 'set_function_click_event' ),
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
				'record_id' => isset( $returning_html_data[ 'record_id' ] )?$returning_html_data[ 'record_id' ]:"",
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
			$this->_get_import_newsletter();
			
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
				
				$err->class_that_triggered_error = 'cimport_newsletter.php';
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
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state' ),
				//'status' => 'display-datatable',
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
					$this->class_settings[ 'current_record_id' ] = $returning_html_data['saved_record_id'];
					$this->class_settings[ 'current_record_details' ] = $this->_get_import_newsletter();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_import_newsletter(){
			
			$cache_key = $this->table_name;
			
			if( ! isset( $this->class_settings['current_record_id'] ) )return array();
			
			$settings = array(
				'cache_key' => $cache_key.'-'.$this->class_settings['current_record_id'],
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
		
	}
?>