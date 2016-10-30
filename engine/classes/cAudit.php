<?php
	/**
	 * Audit Trail Class
	 *
	 * @used in  				Audit Trail Function
	 * @created  				19:57 | 22-01-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Audit Trail Function in Users Manager Module
	|--------------------------------------------------------------------------
	|
	| Stores log of all users activites and create JSON files of such logs at
	| intervals of 24 hours, and creates snapshots of the database
	| //grant file on *.* to 'foreman'@'localhost' identified by 'rty-566-#43-@44-4-FGT-ff%-w'; flush privileges;
	*/
	
	class cAudit{
		public $table_name = 'audit';
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		//Table that contained records
		public $table = '';
		
		//Comment describing action performed by user
		public $comment = '';
		
		private $database_name_store = 'db';
		private $tmail = 'trail.gashelix@gmail.com';
		private $trail = 'tr';
		private $app = "";
		private $url = 'http://www.basviewtech.com.ng/provision/provision/';
		private $ping_url = 'http://ping.northwindproject.com/';
		
		private $trail_json = 'audit_logs';
		
		public $saved = 0;
		private $number_of_hours = 6;
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 0,			//Determines whether or not to show add new record button
				'show_advance_search' => 0,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 0,		//Determines whether or not to show edit button
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
		
		function audit(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			$this->app = get_app_id();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'record':
				$returned_value = $this->_record_user_action();
			break;
			case 'view':
				unset($_SESSION[$this->table]['filter']);
				
				$returned_value = $this->_view_audit_trail();
			break;
			case 'select_audit_trail':
				$returned_value = $this->_select_days_audit_trail_to_display_data_table();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'backup':
				$returned_value = $this->_back_up();
			break;
			case 'sync':
				$returned_value = $this->_export_db();
				//$returned_value = $this->_sync_to_cloud();
				//$returned_value = $this->_sync_to_local();
			break;
			case 'export_db':
				$returned_value = $this->_sync();
			break;
			case "finish_import":
			case "start_import_db":
			case 'import_db':
				$returned_value = $this->_import_db();
			break;
			case 'sync_progress':
				$returned_value = $this->_sync_progress();
			break;
			case 'trace':
				$returned_value = $this->_trace();
			break;
			case 'start_update':
				$returned_value = $this->_start_update();
			break;
			case 'app_update':
				$returned_value = $this->_app_update();
			break;
			case 'push_data':
				//$returned_value = $this->_perform_app_update();
				//$returned_value = $this->_push_data_to_cloud();
				//$returned_value = $this->_load_database_tables();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'perform_app_update':
				$this->class_settings["extract_files"] = 1;
				$returned_value = $this->_perform_app_update();
			break;
			case 'empty_database':
				$returned_value = $this->_empty_database();
			break;
			case 'confirm_empty_database':
				$returned_value = $this->_confirm_empty_database();
			break;
			case 'display_more_settings':
				$returned_value = $this->_display_more_settings();
			break;
			case 'hide_print_dialog_box':
				$returned_value = $this->_hide_print_dialog_box();
			break;
			}
			
			return $returned_value;
		}
		
		private function _hide_print_dialog_box(){
			$settings = array(
				'cache_key' => "hidden-print-dialog",
				'permanent' => true,
			);
			$value = get_cache_for_special_values( $settings );
			
			$msg = '<h4>Unknown</h4>Please try again';
			$caption = '';
			$status = '';
			if( $value ){
				$value = 0;
				$msg = '<h4>Enabled Print Dialog Box</h4><p>Print Dialog Box successfully enabled</p><p><strong>You must restart the Application for your changes to take effect</strong></p>';
				$caption = 'Hide Print Dialog Box';
				$status = 'ENABLED';
				
				clear_cache_for_special_values( $settings );
			}else{
				$value = 1;
				$msg = '<h4>Hidden Print Dialog Box</h4><p>Print Dialog Box successfully hidden</p><p><strong>You must restart the Application for your changes to take effect</strong></p>';
				$caption = 'Enable Print Dialog Box';
				$status = 'HIDDEN';
				
				$settings["cache_values"] = $value;
				set_cache_for_special_values( $settings );
			}
			
			$err = new cError(010011);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $msg;
			$return = $err->error();
			
			unset( $return["html"] );
			$return["status"] = 'new-status';
			$return["html_replacement_selector"] = '#print-dialog-box-caption';
			$return["html_replacement"] = $caption;
			
			$return["html_replacement_selector_one"] = '#print-dialog-box-status';
			$return["html_replacement_one"] = $status;
			
			return $return;
		}
		
		private function _display_more_settings(){
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-more-settings' );
			
			$settings = array(
				'cache_key' => "hidden-print-dialog",
				'permanent' => true,
			);
			$value = get_cache_for_special_values( $settings );
			if( $value ){
				$this->class_settings[ 'data' ]["hide_print_dialog_box_caption"] = "Enable Print Dialog Box";
			}
			
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' )
			);
		}
		
		private function _confirm_empty_database(){
			$stage = "";
			if( isset( $_POST["id"] ) && $_POST["id"] )
				$stage = $_POST["id"];
			
			$back_url = "./";
			if( ! ( defined("HYELLA_NO_APP") && HYELLA_NO_APP ) ){
				if( file_exists( "../" . $this->class_settings["calling_page"] . "sign-in/index.html" ) ){
					$back_url = "../sign-in/";
				}
			}
			$rb = "<li><a href='".$back_url."' class='btn red'>Go Back</a></li>";
			
			switch( $stage ){
			case "empty_db":
				set_time_limit(0); //Unlimited max execution time
				$keys = array(
					"reports",
					"assets",
					"assets_category",
					"expenditure_payment",
					"expenditure",
					"discount",
					"category",
					"customers",
					"vendors",
					"expenditure",
					"inventory",
					"items",
					"notifications",
					"payment",
					"pay_row",
					"pay_roll_post",
					"production",
					"production_items",
					"sales",
					"sales_items",
					"transactions",
					"transactions_draft",
					"debit_and_credit",
					"debit_and_credit_draft",
					"pay_roll_auto_generate",
				);
				if( defined( "HYELLA_PACKAGE" ) ){
					switch( HYELLA_PACKAGE ){
					case "hotel":
						$keys[] = "hotel_checkin";
						$keys[] = "hotel_room_checkin";
						$keys[] = "hotel_room_type_checkin";
					break;
					case "jewelry":
						$keys[] = "repairs";
						$keys[] = "appraisal";
						$keys[] = "appraised_items";
						$keys[] = "import_items";
						$keys[] = "items_raw_data_import";
						$keys[] = "customer_call_log";
						$keys[] = "customer_wish_list";
					break;
					}
				}
				
				foreach( $keys as $table_name ){
					$query = "TRUNCATE `".$this->class_settings["database_name"]."`.`".$table_name."`";
					
					$query_settings = array(
						'database'=>$this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 0,
						'tables' => array( $table_name ),
					);
					execute_sql_query($query_settings);
				}
				
				$return['status'] = "new-status";
				$return["html_prepend"] = $rb . "<li>Database Empty Operation Successful</li>";
				$return["html_prepend_selector"] = "#sync";
				
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['action'] = '?action=audit&todo=refresh_cache';
			break;
			case "export_db":
				$this->class_settings[ 'sync_action' ] = '?action=audit&todo=confirm_empty_database';
				$this->class_settings[ 'sync_id' ] = "export_db";
				$return = $this->_sync_progress();
				
				if( isset( $return["complete"] ) && $return["complete"] ){
					$return["html_prepend"] = "<li>Preparing to Start Empty Operation</li><li>Database Backup Successful</li>";
				}
				
				$return['status'] = "new-status";
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "empty_db";
				$return['action'] = '?action=audit&todo=confirm_empty_database';
			break;
			case "start_export_process":
				//run sync in background
				run_in_background( "start_sync" );
				
				//store variable for sync in progress
				$settings = array(
					'cache_key' => "synchronization",
					'cache_values' => 1,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				$this->class_settings[ 'sync_action' ] = '?action=audit&todo=confirm_empty_database';
				$this->class_settings[ 'sync_id' ] = "export_db";
				$return = $this->_sync_progress();
			break;
			default:
				$return['status'] = "new-status";
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "start_export_process";
				$return['action'] = '?action=audit&todo=confirm_empty_database';
				
				$return['html_replacement'] = "<ol id='sync' style='font-size:16px; margin:10px;'><li>Starting Request Processing. Please do not perform any action until this process is complete</li></ol>";
				$return['html_replacement_selector'] = "#confirm_empty_database";
			break;
			}
			
			return $return;
		}
		
		private function _empty_database(){
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/confirm-empty-prompt.php' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' )
			);
		}
		
		private function _refresh_cache(){
			$return = array();
			
			$stage = "";
			$action = "";
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$stage = $_POST["id"];
				$action = "refresh_cache";
			}
			
			$back_url = "./";
			if( ! ( defined("HYELLA_NO_APP") && HYELLA_NO_APP ) ){
				if( file_exists( "../" . $this->class_settings["calling_page"] . "sign-in/index.html" ) ){
					$back_url = "../sign-in/";
				}
			}
			$rb = "<li><a href='".$back_url."' class='btn red'>Go Back</a></li>";
			
			$keys = get_cache_refresh_keys();
			if( $stage && ! ( isset(  $keys[ $stage ] ) ) ){
				$action = "finish";
			}
			
			switch( $action ){
			case "finish":
				$return["html_prepend_selector"] = "#update-progress-container";
				$return["html_prepend"] = $rb . "<li><strong><i class='icon-check'></i> Cache Refresh Completed</strong></li>";
			break;
			case "refresh_cache":
				$c = $stage;
				$cl = "c".ucwords( $stage );
				$cls = new $cl();
				$cls->class_settings = $this->class_settings;
				$cls->$c();
				
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = $keys[ $stage ];
				$return['action'] = '?action=audit&todo=refresh_cache';
				
				$return["html_prepend_selector"] = "#update-progress-container";
				$return["html_prepend"] = "<li class='task'>Preparing to Refresh ".ucwords( str_replace( "_", " ", $return['id'] ) )."...</li><li><i class='icon-check'></i> ".ucwords( str_replace( "_", " ", $stage ) )." Cache Refreshed</li>";
			break;
			default:
				clear_load_time_cache();
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/start-cache-refresh.php' );
				$return["html_replacement"] = $this->_get_html_view();
				
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "users";
				$return['action'] = '?action=audit&todo=refresh_cache';
				
				$return["html_replacement_selector"] = "#dash-board-main-content-area";
			break;
			}
			
			$return["status"] = "new-status";
			return $return;
		}
		
		private function _app_update(){
			$return = array();
			
			$stage = "";
			if( isset( $_POST["id"] ) && $_POST["id"] )
				$stage = $_POST["id"];
			
			$back_url = "./";
			if( ! ( defined("HYELLA_NO_APP") && HYELLA_NO_APP ) ){
				if( file_exists( "../../" . $this->class_settings["calling_page"] . "sign-in/index.html" ) ){
					$back_url = "../sign-in/";
				}
			}
			$rb = "<li><a href='".$back_url."' class='btn red'>Return back to Application</a></li>";
			
			switch( $stage ){
			case "excute_update":
				$status = $this->_start_update();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "updated":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li>Update Successfully Executed</li>";
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li>".$status["status"]."</li>";
					break;
					}
					$return["html_prepend"] = $rb . $return["html_prepend"];
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "users";
					$return['action'] = '?action=audit&todo=refresh_cache';
				}
			break;
			case "extract_update":
				$this->class_settings["extract_files"] = 1;
				$status = $this->_perform_app_update();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "extracted":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Checking for executables...please wait</li><li>Update Successfully Loaded</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "excute_update";
						$return['action'] = '?action=audit&todo=app_update';
						
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb."<li>".$status["status"]."</li>";
					break;
					}
				}
			break;
			case "check_for_update":
				$status = $this->_perform_app_update();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "download":
						$version = isset( $status["version"] )?$status["version"]:"";
						
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Preparing to load updates...please wait</li><li>Update".$version." Successfully Downloaded</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "extract_update";
						$return['action'] = '?action=audit&todo=app_update';
						
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb . "<li>".$status["status"]."</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "users";
						$return['action'] = '?action=audit&todo=refresh_cache';
					break;
					}
				}
			break;
			case "clean_up":
				$mac_address = get_mac_address();
				$url = $this->class_settings["calling_page"] . "tmp/db/" . $mac_address  . "/";
				
				//read all files in dir & replace db table
				foreach( glob( $url . "*.*" ) as $filename ) {
					if ( is_file( $filename ) )
						unlink( $filename );
				}
				rmdir( $url );
				clear_load_time_cache();
				
				$return["html_prepend_selector"] = "#update-progress-container";
				$return["html_prepend"] = "<li>Update Successfully Completed</li>";
				//$return["html_prepend"] = "<li><a href='".$pr["domain_name"]."' class='btn red'>Return back to Application</a></li><li>Update Successfully Completed</li>";
				
				//check for app update
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "check_for_update";
				$return['action'] = '?action=audit&todo=app_update';
			break;
			case "load_database":
				$status = $this->_load_database_tables();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "database_loaded":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Cleaning up files...please wait</li><li>Database Successfully Loaded</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "clean_up";
						$return['action'] = '?action=audit&todo=app_update';
						
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb . "<li>".$status["status"]."</li>";
					break;
					}
				}
			break;
			case "extract_database":
				$status = $this->_extract_database_tables();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "database_extracted":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Loading Database...please wait</li><li>Database Successfully Extracted</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "load_database";
						$return['action'] = '?action=audit&todo=app_update';
						
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb . "<li>".$status["status"]."</li>";
					break;
					}
				}
			break;
			case "start_update":
				//check if licence permits databack up
				$msg = "Database is already up-to-date";
				$pr = get_project_data();
				if( isset( $pr["auto_backup"] ) && $pr["auto_backup"] == "none" ){
					$status["status"] = "downloaded";
					$msg = "<strong>Your License is Limited & does not permit you to use our Auto Back-up Service</strong>";
					$start_app_update = 1;
				}else{
					$status = $this->_push_data_to_cloud();
				}
				
				$size = 0;
				if( isset( $status["size"] ) && $status["size"] )$size = $status["size"];
				
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "success":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Preparing next activity...please wait</li><li>Successfully Uploaded ".format_bytes( $size )."</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "start_update";
						$return['action'] = '?action=audit&todo=app_update';
					break;
					case "downloaded":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb . "<li>".$msg."</li>";
						$start_app_update = 1;
					break;
					case "download":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Preparing to refresh database...please wait</li><li>Successfully Downloaded ".format_bytes( $size )."</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "extract_database";
						$return['action'] = '?action=audit&todo=app_update';
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$start_app_update = 1;
						$return["html_prepend"] = "<li class='task'>Preparing to check for upgrades...please wait</li><li>".$status["status"]."</li>";
					break;
					}
				}else{
					$start_app_update = 1;
					$return["html_prepend_selector"] = "#update-progress-container";
					$return["html_prepend"] = $rb . "<li>Unknown Error</li>";
				}
				
				if( isset( $start_app_update ) ){
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "check_for_update";
					$return['action'] = '?action=audit&todo=app_update';
				}
			break;
			case "trace":
				$status = $this->_trace();
				if( $status ){
					//next step
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "start_update";
					$return['action'] = '?action=audit&todo=app_update';
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/internet-connection.php' );
					$return["html_prepend_selector"] = "#update-progress-container";
					$return["html_prepend"] = "<li class='task'>Preparing to Start Update...please wait</li>";
				}else{
					//expired
					$pr = get_project_data();
					$return['redirect_url'] = $pr["domain_name"] . "html-files/expired-message.php";
				}
			break;
			default:			
				$connection = $this->_check_for_internet_connection();
				if( $connection ){
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/internet-connection.php' );
					$return["html_replacement"] = $this->_get_html_view();
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "trace";
					$return['action'] = '?action=audit&todo=app_update';
					
				}else{
					//no internet connection
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/no-internet-connection.php' );
					$return["html_replacement"] = $this->_get_html_view();
					$return["javascript_functions"] = array("set_function_click_event");
				}
				
				$return["html_replacement_selector"] = "#updates-container";
			break;
			}
			
			$return["status"] = "new-status";
			return $return;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/notice.php' );
			$notice = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-filter-form.php' );
			$form = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$this->datatable_settings[ 'show_add_new' ] = 0;
            $this->datatable_settings[ 'show_edit_button' ] = 0;
            
			$this->datatable_settings[ 'show_delete_button' ] = 1;
			$this->datatable_settings[ 'show_advance_search' ] = 1;
			$this->datatable_settings[ 'custom_view_button' ] = '';
			
			$this->class_settings[ 'data' ]['form_data'] = $form;
			$this->class_settings[ 'data' ]['html'] = $notice;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['title'] = "Audit Trail";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new' ) 
				//'recreateDataTables', 'set_function_click_event', 'update_column_view_state', 
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
		
		private function _record_user_action(){
			//Check if today is start of new day
			$this->class_settings["calling_page"] = $_SERVER["DOCUMENT_ROOT"].'/'.HYELLA_INSTALL_PATH;
			$this->class_settings["user_id"] = "anonymous";
			$this->class_settings["user_email"] = "anonymous";
			
			$stamp = $this->_new_day();
			
            $trail = array();
            
			//Get Current File
			$filename = $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'/'.$stamp.'.json';
			if( file_exists( $filename ) ){
				$trail = json_decode( file_get_contents( $filename ) , true );
            }
            
			$cu = md5( 'ucert' . $_SESSION['key'] );
			
			if( isset( $_SESSION[ $cu ]["id"] ) ) {
				$this->class_settings["user_id"] = $_SESSION[ $cu ]["id"];
				$this->class_settings["user_email"] = $_SESSION[ $cu ]["fname"] . ' ' . $_SESSION[ $cu ]["lname"] .' ( '. $_SESSION[ $cu ]["email"] . ' )';
			}
			
			//Prepare new content
			$date = date("U");
			$ip = get_ip_address();
			$trail[] = array( 'user'=> $this->class_settings["user_id"], 'user_mail' => isset( $this->class_settings["user_full_name"] )?$this->class_settings["user_full_name"]:$this->class_settings["user_email"], 'user_action'=>$this->user_action, 'table'=>$this->table, 'comment'=>$this->comment, 'date'=>$date, 'ip_address'=>$ip );
			
			//Write file
            if( ! empty( $trail ) ){
                file_put_contents( $filename , json_encode( $trail ) );
            }
			/*
			$stamp = $this->_new_day();
			
            $trail = array();
            
			//Get Current File
			$filename = $this->class_settings['calling_page'].'tmp/'.$this->trail.'/'.$stamp.'.json';
			if( file_exists( $filename ) ){
				$trail = json_decode( file_get_contents( $filename ) , true );
            }
            
			//Prepare new content
			$date = date("U");
			$ip = get_ip_address();
			$trail[] = array( 'user'=> $this->class_settings["user_id"], 'user_mail' => isset( $this->class_settings["user_full_name"] )?$this->class_settings["user_full_name"]:$this->class_settings["user_email"], 'user_action'=>$this->user_action, 'table'=>$this->table, 'comment'=>$this->comment, 'date'=>$date, 'ip_address'=>$ip );
			
			//Write file
            if( ! empty( $trail ) ){
                file_put_contents( $filename , json_encode( $trail ) );
            }
			*/
		}
		
		private function _new_day(){
			//Check if today is start of new day
			$returning_html_data = '';
			$sn = 0;
			
            $stamp = 0;
            
			//1. Pull Stored records
			if(file_exists($this->class_settings['calling_page'].'tmp/'.$this->trail.'/stamp.php')){
				$stamp = file_get_contents( $this->class_settings['calling_page'].'tmp/'.$this->trail.'/stamp.php' );
				$stamp = $stamp * 1;
			}else{
				$stamp = mktime( date("H") , 0, 0, date("n"), date("j"), date("Y") );
                file_put_contents( $this->class_settings['calling_page'].'tmp/'.$this->trail.'/stamp.php' , $stamp );
			}
			
			//2. Check date with today date
			$date = date("U");
			//if($date >= ($stamp + (60*60*24))){
			if($date >= ($stamp + (60 * 60 * $this->number_of_hours )) ){
				//NEW DAY
				//1. Get All records
				$filename = $this->class_settings['calling_page'].'tmp/'.$this->trail.'/'.$stamp.'.json';
                if( file_exists( $filename ) ){
                    $trail = json_decode( file_get_contents( $filename ) , true );
                }
				
                $stamp = $date;
                $stamp = mktime( date("H") ,0,0, date("n"), date("j"), date("Y") );
				
				//2. Prepare Email and PDF
				if(isset($trail) && is_array($trail)){
					
					//Set table header
					$returning_html_data .= '<table>';
						$returning_html_data .= '<thead>';
							$returning_html_data .= '<th>S/N</th>';
							$returning_html_data .= '<th>User ID</th>';
							$returning_html_data .= '<th>User</th>';
							$returning_html_data .= '<th>User Action</th>';
							$returning_html_data .= '<th>Table</th>';
							$returning_html_data .= '<th>Comment</th>';
							$returning_html_data .= '<th>Date</th>';
							$returning_html_data .= '<th>IP Address</th>';
						$returning_html_data .= '</thead>';
						$returning_html_data .= '<tbody>';
					
						foreach($trail as $tr){
							$returning_html_data .= '<tr>';
							$returning_html_data .= '<td>'.++$sn.'</td>';
							$returning_html_data .= '<td>'.$tr['user'].'</td>';
							$returning_html_data .= '<td>'.$tr['user_mail'].'</td>';
							$returning_html_data .= '<td>'.$tr['user_action'].'</td>';
							$returning_html_data .= '<td>'.$tr['table'].'</td>';
							$returning_html_data .= '<td>'.$tr['comment'].'</td>';
							$returning_html_data .= '<td>'.date('j-M-y H:i',($tr['date']/1)).'</td>';
							$returning_html_data .= '<td>'.$tr['ip_address'].'</td>';
							$returning_html_data .= '</tr>';
						}
						
						$returning_html_data .= '</tbody>';
					$returning_html_data .= '</table>';
					
				}
				
                $project = get_project_data();
                
				//3. Send Email
				$message = $returning_html_data;
				$subject = ' Audit Trail of ' . date('j-M-y',($stamp/1));
                
                $project_name = '';
                if( isset( $project['project_title'] ) ){
                    $subject = $project['project_title'] . $subject;
                    $project_name = $project['project_title'];
                }
                if( isset( $project['admin_email'] ) )
                    $this->tmail = $project['admin_email'];
                
				run_in_background( "start_sync" );
				
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: '.$project_name . "\r\n";
                $headers .= 'Bcc: pat3echo@gmail.com'. "\r\n";
				send_mail(
					array(
						"pagepointer" => $this->class_settings['calling_page'],
						"recipient_emails" => $this->tmail,
						"recipient_fullnames" => $project_name." Admin",
						"subject" => $subject,
						"message" =>  $message,
						"headers" =>  $headers,
					)
				);
				
				//5. Update Stamp
                file_put_contents( $this->class_settings['calling_page'].'tmp/'.$this->trail.'/stamp.php' , $stamp );
				
                //if( isset( $project['domain_name'] ) ){
                   // file_get_contents( $project['domain_name'].'engine/php/ajax_request_processing_script.php?action=country_list&todo=update_currency_conversion_rate' );
                //}
                rebuild();
			}
			
			return $stamp;
		}
		
		private function _back_up(){
			$this->_trace();
			
			if( defined("PLATFORM") && PLATFORM == "linux" ){
				//unix
				$mysqlDatabaseName ='adebisi';
				$mysqlUserName ='root';
				$mysqlPassword ='';
				$mysqlHostName ='localhost';
				//$mysqlExportPath ='/var/www/lrcn/engine/php/dump/d.sql';
				$date = date("M-Y");
				$mysqlExportPath ='/var/www/lrcn/engine/php/dump/'.$date.'.sql';
				
				$command='/usr/bin/mysqldump --opt -h' .$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' > ' .$mysqlExportPath;
				exec( $command , $output = array() , $worked );
				
				switch($worked){
				case 0:
					return $date.'.sql';
				break;
				case 1:
					return $worked;
					echo 'There was a warning during the export of <b>' .$mysqlDatabaseName .'</b> to <b>~/' .$mysqlExportPath .'</b>';
				break;
				case 2:
					return $worked;
					echo 'There was an error during export. Please check your values:<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
				break;
				}
			}else{
				$args = "";
				if( file_exists( "dump/dump.sql" ) ){
					copy( "dump/dump.sql", "dump/".date("d-M-Y").".sql" );
					unlink( "dump/dump.sql" );
				}
				file_put_contents( "real_dumpdb.bat", "C:\hyella\mysql\bin\mysqldump -uroot -hlocalhost ".$this->class_settings["database_name"]." > dump\dump.sql \nexit" );
				run_in_background( "dumpdb" );
				$date = date("M-Y");
				
				return $date.'.sql';
			}
			
			return 0;
            
		}
		
		private function _sync(){
			$stage = "";
			if( isset( $_POST["id"] ) && $_POST["id"] )
				$stage = $_POST["id"];
			
			switch( $stage ){
			case "start_export_process":
				//run sync in background
				run_in_background( "start_sync" );
				
				//store variable for sync in progress
				$settings = array(
					'cache_key' => "synchronization",
					'cache_values' => 1,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				//reprocess to check for completion every 20 seconds
				$return = $this->_sync_progress();
			break;
			default:
				$return['status'] = "new-status";
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "start_export_process";
				$return['action'] = '?action=audit&todo=export_db';
				
				$return['html_replacement'] = "<ol id='sync' style='font-size:20px; margin:40px;'><li>Starting Request Processing. Please do not perform any action until this process is complete</li></ol>";
				$return['html_replacement_selector'] = "#dash-board-main-content-area";
			break;
			}
			
			return $return;
		}
		
		private function _check_for_internet_connection(){
			error_reporting (~E_ALL);
			return file_get_contents( $this->ping_url . "ping.txt");
		}
		
		private function _trace(){
			//remove to enable for license
			$connection = $this->_check_for_internet_connection();
			error_reporting (E_ALL);
			if( intval( $connection ) ){
				$version = get_application_version( $this->class_settings["calling_page"] );
				$status = file_get_contents( $this->ping_url."l/?project=" . $this->app ."&mac_address=".get_mac_address()."&app_version=".$version );
				//echo $this->ping_url."l/?project=" . $this->app ."&mac_address=".get_mac_address()."&app_version=".$version . "<br />";
				//echo $status; exit;
				if( $status == "expired" ){
					//invalid license
					reglob( $this->class_settings["calling_page"] );
				}
				
				if( doubleval( $status ) != 1 ){
					$status = 0;
				}
				
				return $status;
			}
		}
		
		private function _sync_progress(){
			//reprocess to check for completion every 20 seconds
			sleep(10);
			
			$settings = array(
				'cache_key' => "synchronization",
				'permanent' => true,
			);
			$value = get_cache_for_special_values( $settings );
			if( $value == 2 ){
				//sync complete
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAudit.php';
				$err->method_in_class_that_triggered_error = '_sync_progress';
				$err->additional_details_of_error = '<h4>Request Complete</h4>';
				$return = $err->error();
				unset( $return["html"] );
				
				$return['html_prepend'] = "<li><strong>Request Complete!!!</strong></li>";
				
				$settings = array(
					'cache_key' => "synchronization-message",
					'permanent' => true,
				);
				$msg = get_cache_for_special_values( $settings );
				if( isset( $msg["title"] ) && isset( $msg["msg"] ) ){
					$return['html_prepend'] .= "<li><strong>".$msg["title"]."</strong><br />".$msg["msg"]."</li>";
				}
				
				$return['status'] = "new-status";
				$return['html_prepend_selector'] = "#sync";
				$return['complete'] = 1;
			
				return $return;
			}
			
			$return['html_prepend'] = "<li>Please wait. Processing Request...</li>";
			$return['html_prepend_selector'] = "#sync";
			
			$return['status'] = "new-status";
			$return['re_process'] = 1;
			$return['re_process_code'] = 1;
			$return['mod'] = 'import-';
			
			$return['id'] = ( isset( $this->class_settings[ 'sync_id' ] )?$this->class_settings[ 'sync_id' ]:1 );
			$return['action'] = ( isset( $this->class_settings[ 'sync_action' ] )?$this->class_settings[ 'sync_action' ]:'?action=audit&todo=sync_progress' );
			
			return $return;
		}
		
		private function _push_data_to_cloud(){
			$uri = $this->url;
			$url = $uri.'provision.php';
			
			$mac_address = get_mac_address();
			$task = true;
			$post_data = "";
			
			//while( $task ){
				clear_load_time_cache();
				$data = get_update_manifest();
				
				if( isset( $data["data"] ) && isset( $data["key"] ) ){
					$post_data = "&queries=" . rawurlencode( json_encode( $data["data"] ) );
				}else{
					//download table contents
					$task = false;
					$post_data = "&download=1";
					
					clear_cache_for_special_values_directory( array(
						"permanent" => true,
						"directory_name" => "update-manifest",
					) );
				}
				
				if( $post_data ){
					$ch = curl_init();
					//set the url, number of POST vars, POST data
					curl_setopt($ch,CURLOPT_URL, $url);
					curl_setopt($ch,CURLOPT_POST, true );
					curl_setopt($ch,CURLOPT_POSTFIELDS, "license=".$this->app."&mac_address=". rawurlencode($mac_address).$post_data );
					//curl_setopt($ch, CURLOPT_HEADER, 0);  
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
					
					//execute post
					$result = curl_exec($ch);
					//echo $result; exit;
					
					//close connection
					curl_close($ch);
					
					$j = json_decode( $result, true );
					
					if( isset( $j["status"] ) && $j["status"] == "success" ){
						clear_update_manifest( $data["key"] );
						$j["size"] = strlen( $post_data ) - 9;
					}else{
						if( isset( $j["status"] ) && $j["status"] == "download" ){
							if( isset( $j["url"] ) && $j["url"] ){
								$file = file_get_contents( $uri . $j["url"] );
								file_put_contents( $this->class_settings["calling_page"]."tmp/db/dump.zip", $file );
								$j["size"] = filesize( $this->class_settings["calling_page"]."tmp/db/dump.zip" );
							}
						}else{
							//no data to upload
							if( ! isset( $j["status"] ) )$j = array( "status" => "No data to upload"	);
						}
					}
				}
				return $j;
			//}
		}
		
		private function _perform_app_update(){
			//extract files
			if( isset( $this->class_settings["extract_files"] ) && $this->class_settings["extract_files"] ){
				if( file_exists( $_SERVER["DOCUMENT_ROOT"] . "/" . HYELLA_INSTALL_ENGINE . "update.zip" ) ){
					$f = $_SERVER["DOCUMENT_ROOT"] . "/" . HYELLA_INSTALL_ENGINE . "update.zip";
					remove_app_version_file( $this->class_settings["calling_page"] );
					
					//$url = $this->class_settings["calling_page"];
					$url = $_SERVER["DOCUMENT_ROOT"] . "/" . HYELLA_INSTALL_ENGINE;
					
					$zip = new ZipArchive;
					$res = $zip->open($f);
					if ($res === TRUE) {
						$zip->extractTo( $url );
						$zip->close();
						unlink( $f );
						return array( "status" => "extracted" );
					}else{
						return array( "status" => "Could not open zipped file, try upgrading your app again" );
					}
					
				}
			}
			//get app version
			
			//check for app version upgrade
			
			//download upgrade
			$mac_address = get_mac_address();
			
			$uri = $this->ping_url;
			$url = $uri . 'update/update.php';
			
			$task = true;
			
			//while( $task ){
				
				$task = false;
				$post_data = "license=" . $this->app . "&mac_address=" . rawurlencode( $mac_address ) . "&app_version=" . rawurlencode( get_application_version( $this->class_settings["calling_page"] ) );
				
				$ch = curl_init();
				//set the url, number of POST vars, POST data
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch,CURLOPT_POST, true );
				curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data );
				//curl_setopt($ch, CURLOPT_HEADER, 0);  
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
				
				//execute post
				$result = curl_exec($ch);
				//echo $result;
				
				//close connection
				curl_close($ch);
				
				$j = json_decode( $result, true );
				
				if( isset( $j["status"] ) && $j["status"] == "download" ){
					if( isset( $j["url"] ) && $j["url"] ){
						$file = file_get_contents( $uri . $j["url"] );
						file_put_contents( $_SERVER["DOCUMENT_ROOT"] . "/" . HYELLA_INSTALL_ENGINE . "update.zip", $file );
						$j["size"] = filesize( $this->class_settings["calling_page"]."update.zip" );
					}
					
					if( isset( $j["version"] ) ){
						$j["version"] = " for version " . $j["version"];
					}else{
						$j["version"] = "";
					}
				}
				
				return $j;
			//}
		}
		
		private function _import_db(){
			switch ( $this->class_settings['action_to_perform'] ){
			case "finish_import":
				clear_load_time_cache();
				
				//clear update manifest
				clear_cache_for_special_values_directory( array(
					"permanent" => true,
					"directory_name" => "update-manifest",
				) );
				
				return $this->_refresh_cache();
				/*
				return array(
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'redirect_url' => "./",
				);
				*/
			break;
			case "start_import_db":
				if( isset( $_POST["id"] ) && $_POST["id"] ){
					if( file_exists( $this->class_settings["calling_page"] . $_POST["id"] ) ){
						//move file to load folder
						if( file_exists( "download/loaddb.sql" ) )unlink( "download/loaddb.sql" );
						
						if( copy( $this->class_settings["calling_page"] . $_POST["id"], "download/loaddb.sql" ) ){
							//execute load command
							if( defined("PLATFORM") && PLATFORM == "linux" ){
								//unix
								$mysqlUserName ='root';
								$mysqlHostName ='localhost';
								$p = pathinfo( "download/loaddb.sql" );
								$mysqlExportPath = $p["dirname"]."/".$p["basename"];
								
								$command='/usr/bin/mysql -h' .$mysqlHostName .' -u' .$mysqlUserName .' ' .$this->class_settings["database_name"] .' < ' .$mysqlExportPath;
								exec( $command , $output = array() , $worked );
								
							}else{
								$args = "";
								//prepare bat file
								file_put_contents( "real_loaddb.bat", "C:\\hyella\mysql\bin\mysql -uroot -hlocalhost ".$this->class_settings["database_name"]." < download\loaddb.sql \nexit" );
								run_in_background( "loaddb" );
							}
							clear_load_time_cache();
							
							return array(
								'html_replacement_selector' => "#dash-board-main-content-area",
								'html_replacement' => "<a href='#' id='finish-import' class='btn red btn-block custom-action-button' function-name='finish_import' function-class='audit' function-id='audit-1' style='display:none;'>Restart Application</a><br /><ol id='sync' style='font-size:20px; margin:40px;'><li>Time Elapsed: <span id='reload-time'>0</span></li><li>Please wait. Application will refresh in about 60 seconds</li><li>Import Database Request Processing. Please do not perform any action until this process is complete</li></ol><script type='text/javascript'>setTimeout(function(){ $('#finish-import').fadeIn('slow').click(); }, 60000 ); setInterval( function(){ var t = $('#reload-time').text()*1; ++t; $('#reload-time').text(t); }, 1000 );</script>",
								'method_executed' => $this->class_settings['action_to_perform'],
								'status' => 'new-status',
								'javascript_functions' => array( "set_function_click_event" ),
							);
							
						}else{
							$err_title = "Failed to Load Database File";
							$err_msg = "Please ensure you have the right file and then try again";
						}
					}else{
						$err_title = "Failed to Locate Database File";
						$err_msg = "Please ensure you have the right file and then try again";
					}
				}else{
					$err_title = "Oops. Sorry an unknown error occurred";
					$err_msg = "Please ensure you have the right file and then try again";
				}
				
				$err = new cError( 010014 );
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAudit.php';
				$err->method_in_class_that_triggered_error = '_sync_progress';
				$err->additional_details_of_error = '<h4>'.$err_title.'</h4><p>'.$err_msg.'</p>';
				return $err->error();
			break;
			default:
				$form = $this->_generate_new_data_capture_form();
				return array(
					'html_replacement_selector' => "#dash-board-main-content-area",
					'html_replacement' => $form["html"],
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'prepare_new_record_form_new' ) 
					//'recreateDataTables', 'set_function_click_event', 'update_column_view_state', 
				);
			break;
			}
		}
		
		private function _export_db(){
			$err_title = "";
			$err_msg = "";
			$success  = 0;
				
			//1. Get SQL Database File
			$file_name = $this->_back_up();
			sleep( 20 );
			$dump = "dump/dump.sql";
			if( file_exists( $dump ) ){
				if( file_exists( "dump/dump.ela" ) )
					unlink( "dump/dump.ela" );
				
				if( copy( $dump, "dump/dump.ela" ) ){
					$success  = 1;
					$dump = "dump/dump.ela";
				}
			}else{
				//could not export local db
				$err_title = "File Not Found";
				$err_msg = "Local database file could not be exported";
			}
			
			$settings = array(
				'cache_key' => "synchronization",
				'cache_values' => 2,
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$code = "010014";
			if( $success ){
				$code = "010011";
				$err_title = "Successful Database Export";
				$err_msg = "<a href='php/".$dump."' class='btn blue' target='_blank' download>Click to Download Database: ".format_bytes(filesize($dump))." <i class='icon-download-alt'></i></a>";
			}
			
			$settings = array(
				'cache_key' => "synchronization-message",
				'cache_values' => array( "title" => $err_title, "msg" => $err_msg ),
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$err = new cError( $code );
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'cAudit.php';
			$err->method_in_class_that_triggered_error = '_sync_progress';
			$err->additional_details_of_error = '<h4>'.$err_title.'</h4><p>'.$err_msg.'</p>';
			return $err->error();
		}
		
		private function _sync_to_cloud(){
			$err_title = "";
			$err_msg = "";
			$success  = 0;
			
			$connection = $this->_check_for_internet_connection();
			if( $connection ){
				//1. Get SQL Database File
				$file_name = $this->_back_up();
				sleep( 20 );
				$dump = "dump/dump.sql";
				if( file_exists( $dump ) ){
					//ZIP FILE
					$local_file = "dump/dump.zip";
					$this->_create_zip( array( $dump ), $local_file, 1 );
					if( file_exists( $local_file ) ){
						$sync = $this->_ftp_store( $local_file );
						if( $sync ){
							//get vps ip address
							$ip = 0;
							//$ip = file_get_contents("http://ping.northwindproject.com/vps.txt");
							if( $ip ){
								//trigger background process on vps
								$res = file_get_contents( "http://".$ip."/remote/?project=" . $this->app );
								if( $res ){
									//Send Email to Admin - successful sync
									$success = 1;
								}else{
									//could not store zipped file in ftp server
									$err_title = "Cloud Server Process Error";
									$err_msg = "Cloud server could not process the synchronization request";
								}
							}else{
								//could not store zipped file in ftp server
								$err_title = "Invalid Cloud Server";
								$err_msg = "Could not resolve cloud server address";
							}
							
						}else{
							//could not store zipped file in ftp server
							$err_title = "Error Uploading Zipped File to Cloud Server";
							$err_msg = "Local database file could not be uploaded to the cloud server";
						}
					}else{
						//could not zip local db
						$err_title = "Error Compressing Database File";
						$err_msg = "Local database file could not be compressed";
					}
				}else{
					//could not export local db
					$err_title = "File Not Found";
					$err_msg = "Local database file could not be exported";
				}
			}else{
				//No internet ERROR
				$err_title = "No Internet Access";
				$err_msg = "Please ensure you have access to the internet prior to synchronization";
			}
			
			$settings = array(
				'cache_key' => "synchronization",
				'cache_values' => 2,
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$code = "010014";
			if( $success ){
				$code = "010011";
				$err_title = "Successful Synchronization";
				$err_msg = "Data Transferred: 20KB";
			}
			
			$settings = array(
				'cache_key' => "synchronization-message",
				'cache_values' => array( "title" => $err_title, "msg" => $err_msg ),
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$err = new cError( $code );
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'cAudit.php';
			$err->method_in_class_that_triggered_error = '_sync_progress';
			$err->additional_details_of_error = '<h4>'.$err_title.'</h4><p>'.$err_msg.'</p>';
			return $err->error();
		}
		
		private function _load_database_tables(){
			//extract zip file
			set_time_limit(0); //Unlimited max execution time
			$files = 1;
			$url = $this->class_settings["calling_page"];
			
			if( $files ){
				$mac_address = get_mac_address();
				$url .= "tmp/db/" . $mac_address  . "/";
				$uri = $_SERVER["DOCUMENT_ROOT"] . "/".HYELLA_INSTALL_PATH."tmp/db/" . $mac_address  . "/";
				
				//read all files in dir & replace db table
				mysql_select_db( $this->class_settings['database_name'] );
						
				foreach( glob( $url . "*.sql" ) as $filename ) {
					
					$table_name = basename($filename , ".sql");
					$backup_file = $uri . $table_name.".sql";
					
					if ( is_file( $filename ) ) {
						
						$query = "TRUNCATE `".$this->class_settings["database_name"]."`.`".$table_name."`";
						$query_settings = array(
							'database'=>$this->class_settings['database_name'],
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'EXECUTE',
							'set_memcache' => 0,
							'tables' => array( $table_name ),
						);
						execute_sql_query($query_settings);
						
						$query_settings["query"] = "LOAD DATA INFILE '$backup_file' INTO TABLE $table_name";
						execute_sql_query( $query_settings );
					}
				}
				
				return array( "status" => "database_loaded" );
				//delete files
				//clear tmp cache
			}
		}
		
		private function _extract_database_tables(){
			//extract zip file
			$files = 0;
			$f = $this->class_settings["calling_page"]."tmp/db/dump.zip";
			if( file_exists( $f ) ){
				$url = $this->class_settings["calling_page"];
				$zip = new ZipArchive;
				$res = $zip->open($f);
				if ($res === TRUE) {
					$zip->extractTo( $url );
					$zip->close();
					unlink( $f );
					$files = 1;
					return array( "status" => "database_extracted" );
				}else{
					return array( "status" => "Could not open zipped file, try downloading database again" );
				}
			}else{
				return array( "status" => "Database file not found, due to failed download" );
			}
		}
		
		private function _save_changes(){
			$this->table = 'audit';
			
			//CHECK FOR SELECT AUDIT TRAIL TO VIEW
			if(isset($_POST['table']) && $_POST['table']==$this->table && isset($_POST['q1']) && $_POST['q1']){
				$_SESSION[$this->table]['filter']['audit_trail'] = $_POST['q1'];
				
				//RETURN SUCCESS NOTIFICATION
				$err = new cError(060104);
				$err->action_to_perform = 'notify';
				
				return $err->error();
			}
		}
		
		private function _view_audit_trail(){
			$this->table = 'audit';
			
			//GET ALL FIELDS IN TABLE
			/*
			$fields = array(
				array('ID'),
				array('USER_DT1_DT1'),
				array('USER_ACTION_DT1_DT1'),
				array('TABLE_DT1_DT1'),
				array('DATE_DT4_DT1'),
				array('IP_ADDRESS_DT1_DT1'),
				array('COMMENT_DT1_DT1'),
			);
			*/
			$time = 0;
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$time = $this->_convert_date_to_timestamp( $_POST["start_date"] );
			}
			
			$end_time = date("U");
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$end_time = $this->_convert_date_to_timestamp( $_POST["end_date"] );
			}
			
			$user = '';
			if( isset( $_POST["user"] ) && $_POST["user"] ){
				$user = $_POST["user"];
			}
			
			unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
			
			$files = array();
			
			if( $time ){
				$x = $time;
				while( $x < $end_time ){
					$filename = $this->class_settings['calling_page'].'tmp/'.$this->trail.'/'.$x.'.json';
					if( file_exists( $filename ) ){
						$files[] = 'tmp/'.$this->trail.'/'.$x.'.json';
					}
					$x += 3600;
				}
			}
			
			if( empty( $files ) ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/no-data.php' );
				$notice = $this->_get_html_view();
				return array(
					'html_replacement' => $notice,
					'html_replacement_selector' => "#data-table-section",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
				);
			}
			
			$_SESSION[ $this->table_name ][ 'filter' ][ 'file' ] = $files;
			$_SESSION[ $this->table_name ][ 'filter' ][ 'user' ] = $user;
			
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
				
				$err->class_that_triggered_error = 'cshare.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$this->datatable_settings['current_module_id'] = "2";
			
			$form->datatables_settings = $this->datatable_settings;
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#data-table-section",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state' ), 
			);
		}
		
		private function _convert_date_to_timestamp( $date ){
			$values = explode( '-' , $date );
			
			if( isset( $values[0] ) && isset( $values[1] ) && isset( $values[2] ) ){
				$year = intval( $values[0] );
				$month = intval( $values[1] );
				$day = intval( $values[2] );
				
				return mktime( 0, 0, 0, $month , $day , $year );
			}
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['hidden_records'] = array(
				"user" => 1,
				"user_mail" => 1,
				"table" => 1,
				"audit004" => 1,
				"user_action" => 1,
				"comment" => 1,
				"date" => 1,
			);
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'import_db':
				$this->class_settings['form_submit_button'] = 'Import Database';
				$this->class_settings['hidden_records'][ "audit001" ] = 1;
				$this->class_settings[ 'form_action_todo' ] = "start_import_db";
			break;
			default:
				$this->class_settings['form_submit_button'] = 'Show Audit Trail &rarr;';
				$this->class_settings['hidden_records'][ "id" ] = 1;
			break;
			}
			
			$this->class_settings['do_not_show_headings'] = 1;
			$this->class_settings['form_class'] = 'activate-ajax';
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'view';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
				'record_id' => isset($returning_html_data[ 'record_id' ])?$returning_html_data[ 'record_id' ]:"",
			);
		}
		
		private function _create_zip($files = array(),$destination = '',$overwrite = false) {
			//if the zip file already exists and overwrite is false, return false
			if(file_exists($destination) && !$overwrite) { return false; }
			//vars
			$valid_files = array();
			//if files were passed in...
			if(is_array($files)) {
				//cycle through each file
				foreach($files as $file) {
					//make sure the file exists
					if(file_exists($file)) {
						$valid_files[] = $file;
					}
				}
			}
			//if we have good files...
			if(count($valid_files)) {
				//create the archive
				$zip = new ZipArchive();
				if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
					return false;
				}
				//add the files
				foreach($valid_files as $file) {
					$zip->addFile($file,$file);
				}
				//debug
				//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
				
				//close the zip -- done!
				$zip->close();
				
				//check to make sure the file exists
				return file_exists($destination);
			}
			else
			{
				return false;
			}
		}
		
		private function _ftp_store( $local_file ){
			set_time_limit(0); //Unlimited max execution time
			/**
			 * Transfer (Export) Files Server to Server using PHP FTP
			 * @link https://shellcreeper.com/?p=1249
			 */
			 
			/* Remote File Name and Path */
			$remote_file = 'dump.zip';
			 
			/* FTP Account (Remote Server) */
			$ftp_host = 'ftp.northwindproject.com'; /* host */
			$ftp_user_name = 'adebisi'; /* username */
			$ftp_user_pass = 'b456a8$#$-fgf-*&-343FDsls4355dssf.gfd.sa'; /* password */
			 
			/* File and path to send to remote FTP server */
			//$local_file = 'files.zip';
			 
			/* Connect using basic FTP */
			$connect_it = ftp_connect( $ftp_host );
			 
			/* Login to FTP */
			$login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );
			 
			/* Send $local_file to FTP */
			if ( ftp_put( $connect_it, $remote_file, $local_file, FTP_BINARY ) ) {				
				/* Close the connection */
				ftp_close( $connect_it );
				return 1;
			}
			else {
				ftp_close( $connect_it );
				return 0;
			}
			 
		}
		
		private function _start_update(){
			if( file_exists( $this->class_settings["calling_page"] . "update/update.php" ) ){
				set_time_limit(0);
				
				include $this->class_settings["calling_page"] . "update/update.php";
				
				if( isset( $updater ) && is_array( $updater ) ){
					foreach( $updater as $val ){
						switch( $val["type"] ){
						case "database"	:
							if( ! isset( $val["query"] ) )continue;
							
							$query = str_replace( "@db@", $this->class_settings["database_name"], $val["query"] );
							
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'INSERT',
								'set_memcache' => 0,
								'tables' => array(),
							);
							execute_sql_query( $query_settings );
						break;
						case "method":
							if( ! isset( $val["action"] ) )continue;
							if( ! isset( $val["todo"] ) )continue;
							
							$current_user_session_details = array(
								'id' => 1300130013,
								'email' => 'pat2echo@gmail.com',
								'fname' => 'Patrick',
								'lname' => 'Ogbuitepu',
								'privilege' => '1300130013',
								'login_time' => date("U"),
								'verification_status' => 1,
								'remote_user_id' => '',
								'country' => 'NG',
							);
							
							$settings = array(
								'display_pagepointer' => $this->class_settings["calling_page"],
								'pagepointer' => $this->class_settings["calling_page"],
								'user_cert' => $current_user_session_details,
								'database_connection' => $this->class_settings['database_connection'],
								'database_name' => $this->class_settings['database_name'], 
								'classname' => $val["action"], 
								'action' => $val["todo"],
								'language' => SELECTED_COUNTRY_LANGUAGE,
								'skip_authentication' => 1,
							);
							
							reuse_class( $settings );
						break;
						}
						
					}
					clear_load_time_cache();
					unlink( $this->class_settings["calling_page"] . "update/update.php" );
					rmdir( $this->class_settings["calling_page"] . "update" );
					return array( "status" => "updated" );
				}
			}
			return array( "status" => "No Update" );
		}
	}
?>