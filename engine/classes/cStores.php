<?php
	/**
	 * stores Class
	 *
	 * @used in  				stores Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	stores
	 */

	/*
	|--------------------------------------------------------------------------
	| stores Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cStores{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'stores';
		
		private $associated_cache_keys = array(
			'stores',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		private $table_fields = array(
			'name' => 'stores001',
			'address' => 'stores002',
			'phone' => 'stores003',
			'email' => 'stores004',
			'comment' => 'stores005',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 0,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
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
		);
			
		function __construct(){
			
		}
	
		function stores(){
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
			case 'get_stores_list4':
			case 'get_stores_list3':
			case 'get_stores_list2':
			case 'get_stores_list':
				$returned_value = $this->_get_stores_list();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'change_store':
				$returned_value = $this->_change_store();
			break;
			}
			
			return $returned_value;
		}
		
		private function _change_store(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Store / Location</h4>Please select a valid store / location';
				return $err->error();
			}
			
			$store = $_POST["id"];
			$stores = $this->_get_stores();
			
			if( isset( $stores[ $store ][ "address" ] ) && $stores[ $store ][ "address" ] ){
				$_SESSION[ "store" ] = $store;
				
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Loading '.$stores[ $store ][ "address" ].'</h4>Please wait...';
				$return = $err->error();
				
				unset( $return["html"] );
				$return["status"] = "new-status";
				$return["redirect_url"] = "main.html";
				
				$loc = __map_store_locations();
				if( isset( $loc[ $store ][ "file" ] ) && $loc[ $store ][ "file" ] ){
					$return["redirect_url"] = $loc[ $store ][ "file" ];
				}
				
				return $return;
			}
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>Missing Information</h4>The selected store / location could not be found';
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
			$this->_get_stores();
		}
		
		private function _get_stores_list(){
			$js = '$.fn.pHost.loadShoppingCart';
			
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "property":
					$js = '$.fn.pHost.loadPropertyView';
				break;
				}
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_stores_list4':
				$js = '$.fn.pHost.loadTransferStock';
			break;
			case 'get_stores_list3':
				$js = '$.fn.pHost.loadStock';
			break;
			case 'get_stores_list2':	
				$js = '$.fn.pHost.viewRoomsReport';
				//$js = '$.fn.pHost.loadMakeReservation';
			break;
			}
			
			$stores = $this->_get_all_stores();
			$all_stores = array();
			foreach( $stores as $sval ){
				$all_stores[ $sval["id"] ] = $sval;
			}
			
			$access = get_accessed_functions( $this->class_settings["priv_id"] );
			$accessible_stores = array();
			
			if( is_array( $access ) ){
				if( ! empty( $access ) ){
					foreach( $access as $sval ){
						switch( $sval["function_class"] ){
						case "stores":
							if( isset( $all_stores[ $sval["function_action"] ] ) ){
								$accessible_stores[ $sval["function_action"] ] = $all_stores[ $sval["function_action"] ];
							}
						break;
						}
					}
				}
				
				if( ! empty( $accessible_stores ) ){
					$all_stores = $accessible_stores;
					unset( $access );
					$access = 1;
				}
			}
			//remove when corrected
			$access = 1;
			if( defined("HYELLA_DEFAULT_STORE") && isset( $all_stores[ HYELLA_DEFAULT_STORE ] ) ){
				$this->class_settings[ 'data' ]['selected_store'] = HYELLA_DEFAULT_STORE;
			}
			
			if( $access == 1 ){
				$this->class_settings[ 'data' ]['stores'] = $all_stores;
			
				if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$this->class_settings[ 'data' ]['selected_store'] = $_SESSION[ "store" ];
				}
				
				if( isset( $_GET["store"] ) && $_GET["store"] ){
					$this->class_settings[ 'data' ]['selected_store'] = $_GET["store"];
				}
				
				if( defined( "HYELLA_PACKAGE" ) ){
					$this->class_settings[ 'data' ][ "package" ] = HYELLA_PACKAGE;
				}
				
				if( defined( "HYELLA_MAIN_STORE" ) ){
					$this->class_settings[ 'data' ][ "main_store" ] = HYELLA_MAIN_STORE;
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-app-store-select' );
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'html_replacement_selector' => "#current-store-container",
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( $js ) 
				);
			}
			
			//redirect to no store page
			return array(
				'redirect_url' => "no-access.html",
				'status' => 'new-status',
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
					$return["javascript_functions"] = array( "nwStores.emptyNewItem" );
										
					$r = $this->_get_stores_list();
					if( isset( $r["html_replacement"] ) && $r["html_replacement_selector"] ){
						$return["html_replacement"] =  $r["html_replacement"];
						$return["html_replacement_selector"] = $r["html_replacement_selector"];
					}
						
					return $return;
					
				}
			}
		}
		
		private function _save_app_changes(){
			$return = array();
			$js = array( 'nwStores.init' );
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
					$js[] = 'nwStores.reClick';
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
					$e = $this->_get_stores();
					
					if( isset( $e[ $return['saved_record_id'] ]["id"] ) ){
						$this->class_settings[ 'data' ]["item"] = $e[ $return['saved_record_id'] ];
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/expense-list.php' );
						$returning_html_data = $this->_get_html_view();
						
						$r = $this->_get_stores_list();
						if( isset( $r["html_replacement"] ) && $r["html_replacement_selector"] ){
							$return["html_replacement"] =  $r["html_replacement"];
							$return["html_replacement_selector"] = $r["html_replacement_selector"];
						}
						
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
						
						$return["html_replace_selector"] = "#".$e[ $return['saved_record_id'] ]["id"];
						$return["html_replace"] = $returning_html_data;
						$return["javascript_functions"] = $js;
						return $return;
					}
				}
			}
			
			
			return $return;
		}
		
		private function _display_app_view(){
			
			$this->class_settings[ 'data' ]['stores'] = $this->_get_all_stores();
			
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
		
		private function _get_all_stores(){
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			$query = "SELECT ".$select." FROM `".$this->class_settings['database_name']."`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status` = '1' ORDER BY `".$this->table_name."`.`".$this->table_fields["name"]."` ";
			
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
			
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "property":
					$this->datatable_settings['show_add_new'] = 1;
				break;
				}
			}
				
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Stores";
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "property":
					$this->class_settings[ 'data' ]['title'] = "Manage Property Locations";
				break;
				}
			}
			
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
				$this->class_settings['form_heading_title'] = 'Modify Store';
				if( defined( "HYELLA_PACKAGE" ) ){
					switch( HYELLA_PACKAGE ){
					case "property":
						$this->class_settings['form_heading_title'] = 'Modify Locations';
					break;
					}
				}
				$this->class_settings['hidden_records'][ $this->table_fields["name"] ] = 1;
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
				$this->_get_stores();
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
				
				$err->class_that_triggered_error = 'cstores.php';
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
					$record = $this->_get_stores();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_stores(){
			
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
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ORDER BY `".$this->table_fields["name"]."`";
			
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
				$package = "";
				$store_name = "";
				if( defined( "HYELLA_PACKAGE" ) )$package = HYELLA_PACKAGE;
				if( defined( "HYELLA_MAIN_STORE" ) )$store_name = HYELLA_MAIN_STORE;
				
				switch( $package ){
				case "hotel":
					foreach( $all_departments as $category ){
						$departments[ $category[ 'id' ] ] = $category;
						
						if( $category['name'] == "." )$category['address'] = $store_name;
						$departments[ 'all' ][ $category[ 'id' ] ] =  $category['address'];
						
					}
				break;
				default:
					foreach( $all_departments as $category ){
						$departments[ 'all' ][ $category[ 'id' ] ] = $category['name'] . " ( ".$category['address']." )";
						$departments[ $category[ 'id' ] ] = $category;
					}
				break;
				}
				
				//Cache Settings
				$settings = array(
					'cache_key' => $cache_key,
					'cache_values' => $departments,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				
				if( ! set_cache_for_special_values( $settings ) ){
					//report cache failure message
				}
				
				return $departments;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-stores-',//.$record["member_id"],
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
					'cache_key' => $cache_key.'-stores-'.$record["member_id"],
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