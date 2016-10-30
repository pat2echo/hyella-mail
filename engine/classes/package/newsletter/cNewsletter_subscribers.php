<?php
	/**
	 * newsletter_subscribers Class
	 *
	 * @used in  				newsletter_subscribers Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	newsletter_subscribers
	 */

	include "cNewsletter_message.php";
	include "cImport_newsletter.php";
	include "cNewsletter_raw_data_import.php";
	include "cNewsletter_tracking.php";
	
	class cNewsletter_subscribers{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'newsletter_subscribers';
		
		private $associated_cache_keys = array(
			'default' => 'newsletter_subscribers',
			'extracted-subscribers' => 'newsletter_raw_data_import-data-',
			'imported-cash-call' => 'import_cash_call-',
			'line-item-details' => 'budget_line_newsletter_subscribers-',
		);
		
		public $table_fields = array(
			'email' => 'newsletter_subscribers001',
			
			'firstname' => 'newsletter_subscribers002',
			'lastname' => 'newsletter_subscribers003',
			'phone' => 'newsletter_subscribers004',
			
			'category' => 'newsletter_subscribers005',
			'status' => 'newsletter_subscribers006',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_generate_report' => 0,		//Determines whether or not to show delete button
				
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
	
		function newsletter_subscribers(){
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
			case 'get_newsletter_subscribers':
				$returned_value = $this->_get_newsletter_subscribers();
			break;
			case 'store_extracted_data':
				$returned_value = $this->_store_extracted_data();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'update_newsletter_subscribers':
				$returned_value = $this->_update_newsletter_subscribers();
			break;
			case 'get_subscribers':
				$returned_value = $this->_get_subscribers();
			break;
			case 'view_subscribers':
				$returned_value = $this->_view_subscribers();
			break;
			case 'get_categories':
				$returned_value = $this->_get_categories();
			break;
			}
			
			return $returned_value;
		}
		
		private function _view_subscribers(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){ 
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Recipient Category</h4>Please select a valid recipient category';
				return $err->error();
			}
			
			$this->class_settings["category"] = $_POST["id"];
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/subscribers.php' );
			$this->class_settings[ 'data' ]['emails'] = $this->_get_subscribers();;
			$html = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/zero-out-negative-budget' );
			
			$this->class_settings[ 'data' ]["html_title"] = 'View Subscribers';
			if( isset( $this->class_settings["category"] ) ){
				$this->class_settings[ 'data' ]["html_title"] .= ': ' . ucwords( $this->class_settings["category"] );
			}
			
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
			);
		}
		
		private function _get_subscribers(){
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case 'email':
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `serial_num`, `".$val."` as '".$key."'";
				break;
				}
			}
			
			$where = "";
			if( isset( $this->class_settings["category"] ) && $this->class_settings["category"] == 'all' ){
				unset( $this->class_settings["category"] );
			}
			
			if( isset( $this->class_settings["category"] ) && $this->class_settings["category"] ){
				if( strtolower( $this->class_settings["category"] ) == 'uncategorized' ){
					$where = " AND `".$this->table_fields["category"]."` = '' ";
				}else{
					$where = " AND `".$this->table_fields["category"]."` = '" . $this->class_settings["category"] . "' ";
				}
			}
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ".$where." ORDER BY `".$this->table_fields["email"]."` ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_departments = execute_sql_query($query_settings);
			return $all_departments;
		}
		
		private function _get_categories(){
			
			$query = "SELECT `".$this->table_fields["category"]."` as 'category' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' GROUP BY `".$this->table_fields["category"]."` ORDER BY `".$this->table_fields["category"]."` ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_departments = execute_sql_query( $query_settings );
			return $all_departments;
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
			$this->_get_newsletter_subscribers();
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/custom-buttons.php' );
			$this->datatable_settings["custom_edit_button"] = $this->_get_html_view();
			
			$this->class_settings['form_heading_title'] = 'Create & Manage Newsletter Subscribers';
			$this->class_settings['form_submit_button'] = 'Save Changes &rarr;';
			
			$datatable = $this->_display_data_table();
			
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Newsletter Subscribers Manager";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			//$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/display-all-records-full-view' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new', 'recreateDataTables', 'set_function_click_event', 'update_column_view_state' ),
			);
		}
		
		private function _get_existing_newsletter_subscriberss(){
			//RETURN CODES OF EXISTING LINE newsletter_subscribers FOR EACH MONTH
			if( ! ( isset( $this->class_settings['user_id'] ) && $this->class_settings['user_id']) )
				return array("Invalid User ID");
			
			$cache_key = $this->table_name . '-available-invoices-' . $this->class_settings['user_id'];
			
			//Cache Settings
			$settings = array(
				'cache_key' => $cache_key,
			);
			//$cache_values = get_cache_for_special_values( $settings );
			if( isset( $cache_values ) && is_array( $cache_values ) && ! empty( $cache_values ) ){
				$this->class_settings['available_line_newsletter_subscribers'] = $cache_values;
				return $cache_values;
			}
			
			$query = "SELECT `id`, `serial_num`, `".$this->table_fields['invoice_id']."` as 'invoice_id', `".$this->table_fields['item_id']."` as 'item_id', `".$this->table_fields['unit_price']."` as 'unit_price' FROM `".$this->class_settings["database_name"]."`.`".$this->table_name."`  WHERE `record_status`='1' AND `created_by` = '".$this->class_settings['user_id']."' ";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 0,
				'tables' => array( $this->table_name ),
			);
			
			$codes = array();
			$des = array();
			$des1 = array();
			$data = execute_sql_query($query_settings);
			
			foreach( $data as $sval ){
				$des[ md5( $sval['invoice_id'] . $sval['item_id'] . $sval['unit_price'] ) ] = $sval['serial_num'];
				//$des1[ md5( $sval['customer_id'] . $sval['issue_date'] . $sval['delivery_date'] . $sval['due_date'] ) ] = $sval['id'];
			}
			
			//Cache Settings
			$settings = array(
				'cache_key' => $cache_key,
				'cache_values' => $des,
				'cache_time' => 'load-time',
			);
			
			set_cache_for_special_values( $settings );
			$this->class_settings['available_line_newsletter_subscribers'] = $des;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_existing_invoices_id':	
				return $des1;
			break;
			}
			
			return $des;
		}
		
		private function _store_extracted_data(){
			//STORE DATA IN exploration_drilling TABLE
			/*---------------------------*/
			
			//for testing
			if( isset($_GET['id']) )
				$_POST['id'] = $_GET['id'];
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				
				$this->class_settings['current_record_id'] = $_POST['id'];
				
				$barcode_newsletter_subscribers = array();
				
				//RETRIEVE EXTRACTED LINE newsletter_subscribers
				$settings = array(
					'cache_key' => $this->associated_cache_keys['extracted-subscribers'].$this->class_settings['current_record_id'],
					'directory_name' => $this->associated_cache_keys['extracted-subscribers'],
				);
				$line_newsletter_subscribers = get_cache_for_special_values( $settings );
				
				//check for existing line newsletter_subscribers
				//$existing_line_newsletter_subscribers = $this->_get_existing_newsletter_subscriberss();
				$existing_line_newsletter_subscribers = array();
				
				$array_of_dataset = array();
				$array_of_dataset2 = array();
				
				$new_record_id = get_new_id();
				$new_record_id_serial = 0;
				
				$ip_address = get_ip_address();
				$date = date("U");
				
				$already_has_code = array();
				$already_has_desc = array();
				
				$recent_activity_new_record_inserts = 0;
				$recent_activity_updated_records = 0;
				$recent_activity_invalid_tender = 0;
				$recent_activity_duplicate_records = 0;
				
				$previous_pre_key = "";
				
				foreach( $line_newsletter_subscribers as $k => $v ){
					
					$dataset_to_be_inserted = array(
						'id' => $new_record_id . ++$new_record_id_serial,
						'created_role' => $this->class_settings[ 'priv_id' ],
						'created_by' => $this->class_settings[ 'user_id' ],
						'creation_date' => $date,
						'modified_by' => $this->class_settings[ 'user_id' ],
						'modification_date' => $date,
						'ip_address' => $ip_address,
						'record_status' => 1,
					);
					
					foreach( $v as $kv => $vv ){
						if( isset( $this->table_fields[ $kv ] ) )
							$dataset_to_be_inserted[ $this->table_fields[ $kv ] ] = $vv;
					}
					
					if( 1 == 2 && isset( $existing_line_newsletter_subscribers[ $tender_key ] ) && $existing_line_newsletter_subscribers[ $tender_key ] ){
						//update
						unset( $dataset_to_be_inserted[ 'id' ] );
						unset( $dataset_to_be_inserted[ 'created_by' ] );
						unset( $dataset_to_be_inserted[ 'creation_date' ] );
						unset( $dataset_to_be_inserted[ 'creator_role' ] );
						
						$update_conditions_to_be_inserted = array(
							'where_fields' => 'serial_num',
							'where_values' => $existing_line_newsletter_subscribers[ $tender_key ],
						);

						$array_of_dataset_update[] = $dataset_to_be_inserted;

						$array_of_update_conditions[] = $update_conditions_to_be_inserted;
						
						++$recent_activity_updated_records;
					}else{
						//new
						$array_of_dataset[] = $dataset_to_be_inserted;
						
						++$recent_activity_new_record_inserts;
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
				
				if( ! empty( $array_of_dataset_update ) && ! empty( $array_of_update_conditions ) ){
					$function_settings = array(
						'database' => $this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'],
						'table' => $this->table_name,
						'dataset' => $array_of_dataset_update,
					);
					
					$function_settings[ 'update_conditions' ] = $array_of_update_conditions;
					
					$returned_data = insert_new_record_into_table( $function_settings );
					
					$saved = 1;
				}
				
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Loading Extracted Excel Rows &darr;';
				$return = $err->error();
				
				$return['status'] = 'new-status';
				
				$return['err'] = $err->additional_details_of_error;
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/import-progress.php' );
				$this->class_settings[ 'data' ] = array(
					'import_progress' => array( 
						0 => array( 'title' => 'Started Creation of Load Queries' ),
						1 => array( 'title' => 'Successfully Created Load Queries' ),
						2 => array( 'title' => 'Started Loading Data' ),
						3 => array( 'title' => 'Successfully Loaded Data' ),
						4 => array( 'title' => 'Finished! <i class="icon-thumbs-up-alt"></i>' ),
					),
				);
				
				$return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view();
				
				$invoice_table = "barcode";
				$cache_key = $invoice_table;
				
				//save queue barcodes for print
				$settings = array(
					'cache_key' => $cache_key.'-'.$this->class_settings["current_record_id"],
					'cache_values' => $barcode_newsletter_subscribers,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				//settings for ajax request reprocessing
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-'.md5( $this->table_name );
				$return['id'] = $this->class_settings[ 'current_record_id' ];
				$return['action'] = '?action='.$this->table_name.'&todo=display_all_records_full_view';
				//$return['javascript_functions'] = array( 'refresh_tree_view' );
				
				if( isset( $this->class_settings['where'] ) && $this->class_settings['where'] ){
					$this->class_settings['where_clause'] = $this->class_settings['where'];
				}
				$this->_get_newsletter_subscribers();
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
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
				$this->class_settings['form_heading_title'] = 'Modify Subscriber';
				//
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
			
			//$this->class_settings[ 'do_not_check_cache' ] = 1;
			//$this->_get_newsletter_subscribers();
			
			return $returning_html_data;
		}
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
			unset($_SESSION[ $this->table_name ]);
			
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
				
				$err->class_that_triggered_error = 'cnewsletter_subscribers.php';
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
					
					$this->class_settings["current_record_id"] = $returning_html_data['saved_record_id'];
					$this->class_settings[ 'selected_item' ] = $this->class_settings["current_record_id"];
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$item = $this->_get_newsletter_subscribers();
					
					unset( $this->class_settings[ 'selected_item' ] );
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_newsletter_subscribers(){
			
			$cache_key = $this->table_name;
			
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
			
			$where = "";
			$include_list = 1;
			if( ( isset( $this->class_settings[ 'selected_item' ] ) && $this->class_settings[ 'selected_item' ] ) ){
				$where = " AND `id` = '".$this->class_settings[ 'selected_item' ]."' ";
				$include_list = 0;
			}
			
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
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ".$where." ORDER BY `".$this->table_fields["email"]."` ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_departments = execute_sql_query($query_settings);
			
			$departments = array(
				"all" => array(),
				"grouped" => array(),
			);
			
			$grouped = array();
			
			$cs = array();
			if( isset( $ck["all"] ) )
				$cs = $ck["all"];
			
			if( is_array( $all_departments ) && ! empty( $all_departments ) ){
				
				foreach( $all_departments as $category ){
					$settings = array(
						'cache_key' => $cache_key.'-'.$category[ 'id' ],
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