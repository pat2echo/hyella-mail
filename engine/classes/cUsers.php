<?php
	/**
	 * users Class
	 *
	 * @used in  				users Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	users
	 */

	/*
	|--------------------------------------------------------------------------
	| users Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cUsers{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'users';
		
        private $table_fields = array(
			'email' => 'users004',
			'other_names' => 'users003',
			
			'firstname' => 'users001',
			'lastname' => 'users002',
			
			'password' => 'users006',
			'confirmpassword' => 'users007',
			'oldpassword' => 'users008',
			
			'phone_number' => 'users005',
            
			'department' => 'users010',
			'grade_level' => 'users020',
			
			'date_of_birth' => 'users011',
			'sex' => 'users012',
			'address' => 'users013',
			'date_employed' => 'users014',
			'ref_no' => 'users015',
			'account_number' => 'users016',
			'bank_name' => 'users017',
            
			'photograph' => 'users018',
			'push_notification_id' => 'users019',
            
			'role' => 'users009',
		);
        
		function users(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit_password':
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
			break;
			case 'display':
				$returned_value = $this->_display();
			break;
			case 'users_registration':
				$returned_value = $this->_users_registration_process();
			break;
			case 'verify_email_address':
				$returned_value = $this->_verify_email_address();
			break;
			case 'display_user_details':
				$returned_value = $this->_display_user_details();
			break;
			case 'change_user_password':
				$returned_value = $this->_change_user_password();
			break;
			case 'get_all_users_details':
				$returned_value = $this->_get_all_users_details();
			break;
			case 'get_user_details':
				$returned_value = $this->_get_user_details();
			break;
			case 'authenticate_employee':
				$returned_value = $this->_authenticate_employee();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'display_my_profile_manager':
				$returned_value = $this->_display_my_profile_manager();
			break;
			case 'save_update_profile':
				$returned_value = $this->_save_update_profile();
			break;
			case 'get_all_admin_users':
				$returned_value = $this->_get_all_admin_users();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
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
			case 'authenticate_app':
				$returned_value = $this->_authenticate_app();
			break;
			case 'app_check_logged_in_user4':
			case 'app_check_logged_in_user3':
			case 'app_check_logged_in_user2':
			case 'app_check_logged_in_user':
			case 'app_check_logged_in_user5':
				$returned_value = $this->_app_check_logged_in_user();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			}
			
			return $returned_value;
		}
		
		private function _refresh_cache(){
			//empty permanent cache folder
			clear_cache_for_special_values_directory( array(
				"permanent" => true,
				"directory_name" => $this->table_name,
			) );
			
			unset( $this->class_settings['user_id'] );
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->_get_user_details();
		}
		
		private function _app_check_logged_in_user(){
			$returned_data = array(
				"status" => "new-status",
				"redirect_url" => "index.html",
				//"redirect_url" => $this->class_settings[ 'project_data' ][ "domain_name" ],
			);
			
			$main_url = "main.html";
			if( defined( "HYELLA_DEFAULT_LOCATION" ) && HYELLA_DEFAULT_LOCATION ){
				$main_url = HYELLA_DEFAULT_LOCATION;
			}
					
			$f = "$.fn.pHost.displayUserDetails";
			switch( $this->class_settings["action_to_perform"] ){
			case 'app_check_logged_in_user5':
				$f = "$.fn.pHost.displayUserDetails4";
			break;
			case 'app_check_logged_in_user4':
				$f = "$.fn.pHost.displayUserDetails3";
			break;
			case 'app_check_logged_in_user3':
				$f = "$.fn.pHost.displayUserDetails2";
			break;
			case 'app_check_logged_in_user2':
				$returned_data = array(
					"status" => "new-status",
					'html_replacement_selector' => "#registered-company",
					'html_replacement' => $this->class_settings[ 'project_data' ][ "company_name" ],
					
					'html_replacement_selector_one' => '#logo-container',
					'html_replacement_one' => '<img src="'.$this->class_settings[ 'project_data' ][ "domain_name" ].'logo-b.png" />',
				);
			break;
			}
			
			$front_end = array(
				"10859468911" => "#role-frontoffice-reports",	//reports
				"10859469548" => "#role-frontoffice-payments",	//payments
				"10859468177" => "#role-frontoffice-roomchart",	//room chart
				"11297334616" => "#role-frontoffice-more",	//more
				
				"11297375343" => "#role-issuestock",	//issue stock
				"11297388210" => "#role-purchaseorder",	//purchase order
				"11297398525" => "#role-expenses",	//expenses
				"11297392020" => "#role-vendorpayments",	//vendor payments
				
				"10859433674" => "#role-more",	//more
				"10859430820" => "#role-customerpayments",	//customer payments, debts
				"10859428846" => "#role-stock",	//stock
				"10859427329" => "#role-sell",	//sell
				
				"11297443254" => "#role-backend",	//visible backend
			);
				
			$key = md5('ucert'.$_SESSION['key']);
			
			if( isset($_SESSION[$key]) ){
				$user_details = $_SESSION[$key];
				$user_info = $user_details;
				
				//get access_roles
				$super = 0;
				
				$access = array();
				if( isset( $user_info["privilege"] ) && $user_info["privilege"] ){
					
					if( $user_info["privilege"] == "1300130013" ){
						$super = 1;
					}else{
						$functions = get_access_roles_details( array( "id" => $user_info["privilege"] ) );
						if( isset( $functions[ $user_info["privilege"] ]["accessible_functions"] ) ){
							$a = explode( ":::" , $functions[ $user_info["privilege"] ]["accessible_functions"] );
							if( is_array( $a ) && $a ){
								foreach( $a as $k => $v ){
									$access[ $v ] = $v;
								}
							}
						}
					}
				}
				
				$returned_data = array(
					"status" => "new-status",
					"user_details" => $user_details,
					"javascript_functions" => array( $f ),
				);
				
				
				foreach( $front_end as $k => $v ){
					if( ! ( isset( $access[ $k ] ) ||  $super ) )
						$returned_data["html_removals"][] = $v; 
				}
				
				switch( $this->class_settings["action_to_perform"] ){
				case 'app_check_logged_in_user2':
					
					$returned_data = array(
						"status" => "new-status",
						"redirect_url" => $main_url,
					);
					
				break;
				}
				
				if( defined( "HYELLA_NO_FRONTEND_UPDATE" ) ){
					if( HYELLA_NO_FRONTEND_UPDATE ){
						$returned_data["html_removal"] = "#update-app-container";
					}
				}
				
			}
			return $returned_data;
		}
		
		private function _authenticate_app(){
			if( ! ( isset( $_POST["email"] ) && isset( $_POST["password"] ) ) ){
				return array("please provide valid email & password");
			}
			$email = $_POST["email"];
			$pass = $_POST["password"];
			
			//Login New User
			$authentication = new cAuthentication();
			$authentication->class_settings = $this->class_settings;
			
			$authentication->class_settings["username"] = $email;
			$authentication->class_settings["password"] = md5( $pass . get_websalter() );
			
			$authentication->class_settings["action_to_perform"] = 'confirm_username_and_password';
			$returned_data  = $authentication->authentication();
			
			if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
				//Get Logged in User Details
				$user_details = array();
				$key = md5('ucert'.$_SESSION['key']);
				if( isset($_SESSION[$key]) ){
					$user_details = $_SESSION[$key];
					
					$loc = "main.html";
					if( defined( "HYELLA_DEFAULT_LOCATION" ) && HYELLA_DEFAULT_LOCATION ){
						$loc = HYELLA_DEFAULT_LOCATION;
					}
					
					$key = md5( 'ucert' . $_SESSION['key'] );
					if( isset( $_SESSION[ $key ] ) ){
						$user_details = $_SESSION[ $key ];
						$user_info = $user_details;
						
						//get access_roles
						$super = 0;
						$allow_update = 0;
						$access = array();
						if( isset( $user_info["privilege"] ) && $user_info["privilege"] ){
							
							if( $user_info["privilege"] == "1300130013" ){
								$super = 1;
							}else{
								$functions = get_access_roles_details( array( "id" => $user_info["privilege"] ) );
								if( isset( $functions[ $user_info["privilege"] ]["accessible_functions"] ) ){
									$a = explode( ":::" , $functions[ $user_info["privilege"] ]["accessible_functions"] );
									if( is_array( $a ) && $a ){
										foreach( $a as $k => $v ){
											$access[ $v ] = $v;
										}
									}
								}
							}
						}
						
						$key = 'allow_update_of_application'; //allow update
						if( ( isset( $access[ $key ] ) ||  $super ) ){
							$allow_update = 1;
						}
						
						if( $allow_update ){
							$loc = "../engine/?activity=update";
						}
					}
					
					$returned_data = array(
						"status" => "new-status",
						"redirect_url" => $loc,
					);
				}else{
					//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
					$err = new cError(000014);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cUsers.php';
					$err->method_in_class_that_triggered_error = '_users_registration_process';
					$err->additional_details_of_error = '';
					$returned_data = $err->error();
				}
			}else{
				//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
				$err = new cError(000014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cusers.php';
				$err->method_in_class_that_triggered_error = '_users_registration_process';
				$err->additional_details_of_error = '';
				$returned_data = $err->error();
			}
			
			return $returned_data;
		}
		
		private function _delete_app_record(){
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$_POST['mod'] = 'delete-'.md5( $this->table_name );
				$return = $this->_delete_records();
				if( isset( $return['deleted_record_id'] ) && $return['deleted_record_id'] ){
					unset( $return["html"] );
					$return["status"] = "new-status";
					
					$return["html_removal"] = "#".$return['deleted_record_id'];
					$return["javascript_functions"] = array( "nwUsers.emptyNewItem" );
					return $return;
					
				}
			}
		}
		
		private function _save_app_changes(){
			$return = array();
			$js = array( 'nwUsers.init' );
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
					$js[] = 'nwUsers.reClick';
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
					
					$this->class_settings['current_record_id'] = $return['saved_record_id'];
					$e = get_users( array("id" => $return['saved_record_id'] ) );
					
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
			
			$this->class_settings[ 'data' ]['users'] = $this->_get_all_admin_users();
			
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
		
		private function _get_all_admin_users(){
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case 'email':
				case 'other_names':			
				case 'firstname':
				case 'lastname':
				case 'phone_number':
				case 'role':
				case 'ref_no':
				case 'grade_level':
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `".$val."` as '".$key."'";
				break;
				}
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status` = '1' ";
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			return execute_sql_query($query_settings);
			
		}
		
		private function _save_update_profile(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$return = $this->_save_changes();
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/user-info.php' );
			
			unset( $this->class_settings[ 'do_not_check_cache' ] );
			$this->class_settings[ 'data' ]["user_info"] = $this->_get_user_details();
			
			$returning_html_data = $this->_get_html_view();
			
			unset( $return["html"] );
			
			$return['html_replacement'] = $returning_html_data;
			$return['html_replacement_selector'] = "#user-info-page";
			$return['status'] = 'new-status';
			
			return $return;
		}
		
		private function _display_my_profile_manager(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/user-info.php' );
			$this->class_settings[ 'data' ]["user_info"] = $this->_get_user_details();
			$user_details = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-my-profile-manager' );
			$this->class_settings[ 'data' ] = array();
			
			$this->class_settings[ 'form_submit_button' ] = 'Update Changes &rarr;';
			$this->class_settings[ 'form_action_todo' ] = 'save_update_profile';
			
			unset( $_GET["id"] );
			$_POST["id"] = $this->class_settings["user_id"];
			$_POST["mod"] = "edit-".md5($this->table_name);
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]["personal_info_form"] = $form[ 'html' ];
			
			$this->class_settings['action_to_perform'] = "change_user_password";
			$form = $this->_change_user_password();
			$this->class_settings[ 'data' ]["password_form"] = $form[ 'html' ];
			
			$this->class_settings[ 'data' ]['user_details'] = $user_details;
			
			$this->class_settings[ 'data' ]['title'] = "Users Manager";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$this->class_settings['change_passowrd'] = 1;
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Staff Manager";
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
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
			if( ! isset( $this->class_settings['hidden_records'] ) ){
				$this->class_settings['hidden_records'] = array(
					//$this->table_fields['bank_name'] => 1,
					//$this->table_fields['account_number'] => 1,
					//$this->table_fields['ref_no'] => 1,
					$this->table_fields['other_names'] => 1,
					$this->table_fields['oldpassword'] => 1,
					$this->table_fields['push_notification_id'] => 1,
				);
			}
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['hidden_records'][ $this->table_fields['password'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['confirmpassword'] ] = 1;
			break;
			case 'display_my_profile_manager':
				$this->class_settings['hidden_records'][ $this->table_fields['oldpassword'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['department'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['role'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['password'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['confirmpassword'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['account_number'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['bank_name'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['grade_level'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['date_employed'] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields['ref_no'] ] = 1;
			break;
			case 'edit_password':
				$this->class_settings['hidden_records'] = array(
					$this->table_fields['email'] => 1,
					$this->table_fields['other_names'] => 1,
					$this->table_fields['phone_number'] => 1,
					$this->table_fields['firstname'] => 1,
					$this->table_fields['lastname'] => 1,
					$this->table_fields['address'] => 1,
					$this->table_fields['bank_name'] => 1,
					$this->table_fields['account_number'] => 1,
					$this->table_fields['ref_no'] => 1,
					$this->table_fields['date_employed'] => 1,
					$this->table_fields['sex'] => 1,
					$this->table_fields['date_of_birth'] => 1,
					$this->table_fields['phone_number'] => 1,
					$this->table_fields['other_names'] => 1,
					$this->table_fields['oldpassword'] => 1,
					$this->table_fields['push_notification_id'] => 1,
					$this->table_fields['role'] => 1,
					$this->table_fields['department'] => 1,
					$this->table_fields['grade_level'] => 1,
					$this->table_fields['photograph'] => 1,
				);
				//$this->class_settings['hidden_records'][ $this->table_fields['password'] ] = 1;
				//$this->class_settings['hidden_records'][ $this->table_fields['confirmpassword'] ] = 1;
			break;
			}
			
			$this->class_settings["do_not_show_headings"] = 1;
			
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
			
			unset( $this->class_settings['user_id'] );
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->_get_user_details();
				
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
				
				$err->class_that_triggered_error = 'cusers.php';
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
					'show_add_new' => 1,			//Determines whether or not to show add new record button
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
					'show_delete_button' => 1,		//Determines whether or not to show delete button
					
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
			
			if( isset( $this->class_settings['change_passowrd'] ) && $this->class_settings['change_passowrd'] ){
				$form->datatables_settings["show_edit_password_button"] = 1;
			}
			//
			
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
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'saved-form-data';
			
			if( isset( $returning_html_data['saved_record_id'] ) ){
				$this->class_settings['user_id'] = $returning_html_data['saved_record_id'];
				
				unset( $this->class_settings['user_id'] );
				$this->class_settings[ 'do_not_check_cache' ] = 1;
				$this->_get_user_details();
				
				$this->class_settings['user_id'] = $returning_html_data['saved_record_id'];
            }
			
			return $returning_html_data;
		}
		
		private function _display_user_details(){
			
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			$processing_status = $this->_save_changes();
			
			if( is_array( $processing_status ) && !empty( $processing_status ) ){
				$result_of_all_processing = $processing_status;
			}
			
			//SET VARIABLES FOR EDIT MODE
			$_POST['id'] = $this->class_settings['user_id'];
			$_POST['mod'] = 'edit-'.md5( $this->table_name );
			
			//GENERATE REGISTRATION FORM WITH USER DETAILS
			//Disable appearance of all headings on forms
			$this->class_settings['do_not_show_headings'] = true;
				
			//Hide certain form fields
			$this->class_settings[ 'hidden_records' ] = array(
				'users003' => 1,
				'users004' => 1,
				'users005' => 1,
				'users014' => 1,
				'users015' => 1,
				'users016' => 1,
			);
			
			//Form button caption
			$this->class_settings[ 'form_submit_button' ] = 'Update Changes';
			
			$returned_data = $this->_generate_new_data_capture_form();
			
			if( ! empty ( $result_of_all_processing ) && isset( $result_of_all_processing['html'] ) ){
				$result_of_all_processing['html'] = $returned_data['html'];
				
				return $result_of_all_processing;
			}
			
			return $returned_data;
		}
		
		private function _change_user_password(){
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			
			//CHECK FOR OLD PASSWORD
			if( isset( $_POST['users008'] ) ){
				
				if( $_POST['users008'] ){
				
					//TEST OLD PASSWORD TO ENSURE IT MATCHES STORED PASSWORD
					$query = "SELECT * FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`id`='" . $this->class_settings['user_id'] . "' AND `" . $this->table_name . "`.`users006`='" . md5( $_POST['users008'] . get_websalter() ) . "' AND `" . $this->table_name . "`.`record_status`='1' ";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					$sql_result = execute_sql_query($query_settings);
					
					if( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ){
						//DESTROY OLD PASSWORD FIELD
						unset( $_POST['users008'] );
						
						$processing_status = $this->_save_changes();
						
						if( is_array( $processing_status ) && !empty( $processing_status ) ){
							$result_of_all_processing = $processing_status;
							
							if( isset( $result_of_all_processing['typ'] ) && $result_of_all_processing['typ'] == 'saved' ){
								//TRANSFORM SUCCESS MESSAGE
								$err = new cError(010008);
								$err->action_to_perform = 'notify';
								$err->class_that_triggered_error = 'cusers.php';
								$err->method_in_class_that_triggered_error = '_change_user_password';
								$err->additional_details_of_error = 'successful password change';
								
								$result_of_all_processing = $err->error();
							}
						}
						
					}
				
				}
				
				//RETURN NON-MATCHING OLD PASSWORD ERROR
				$err = new cError(000103);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cusers.php';
				$err->method_in_class_that_triggered_error = '_change_user_password';
				
				if( isset( $query ) && $query ){
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 138';
				}
				
				$result_of_all_processing = $err->error();
			}
			
			//SET VARIABLES FOR EDIT MODE
			$_POST['id'] = $this->class_settings['user_id'];
			$_POST['mod'] = 'edit-'.md5( $this->table_name );
			
			//GENERATE REGISTRATION FORM WITH USER DETAILS
			//Disable appearance of all headings on forms
			$this->class_settings['do_not_show_headings'] = true;
				
			//Hide certain form fields
			$this->class_settings[ 'hidden_records' ] = array(
				'users001' => 1,
				'users002' => 1,
				'users003' => 1,
				'users004' => 1,
				'users005' => 1,
				'users009' => 1,
				'users010' => 1,
				'users011' => 1,
				'users012' => 1,
				'users013' => 1,
				'users014' => 1,
				'users015' => 1,
				'users016' => 1,
				'users017' => 1,
				'users018' => 1,
				'users019' => 1,
				'users020' => 1,
				'users021' => 1,
			);
			
			//Hide certain form fields
			$this->class_settings[ 'hidden_records_css' ] = array(
				'users016' => 1,
			);
			
			//Form button caption
			$this->class_settings[ 'form_submit_button' ] = 'Change Password';
			
			$returned_data = $this->_generate_new_data_capture_form();
			
			if( ! empty ( $result_of_all_processing ) && isset( $result_of_all_processing['html'] ) ){
				$result_of_all_processing['html'] = $returned_data['html'];
				
				return $result_of_all_processing;
			}
			
			return $returned_data;
		}
		
		private function _verify_email_address(){
			//PROJECT DATA
			$project = get_project_data();
			
			$generate_form = true;
			
			//INITIALIZE RETURNING ARRAY
			$result_of_all_processing = array();
			
			if(isset($_GET['verify']) && $_GET['verify']){
				
				//Get User Details prior to verification / authentication
				$query = "SELECT * FROM `".$this->class_settings['database_name']."`.`" . $this->table_name . "` WHERE md5(`".$this->table_name."`.`id`) = '" . $_GET['verify'] . "' AND `".$this->table_name."`.`record_status`='1' AND `".$this->table_name."`.`users016` = '10'";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				$email_address = '';
				$password = '';
				$user_id = '';
				$first_name = '';
				$last_name = '';
				
				if(isset($sql_result[0])){
					
					$email_address = $sql_result[0]['users007'];
					$password = $sql_result[0]['users004'];
					$user_id = $sql_result[0]['id'];
					
					$first_name = $sql_result[0]['users001'];
					$last_name = $sql_result[0]['users002'];
					
					//Verify user account if not already verified
					$query = "UPDATE `".$this->class_settings['database_name']."`.`" . $this->table_name . "` SET `".$this->table_name."`.`users016`='20', `".$this->table_name."`.`modification_date`='" . date("U") . "' WHERE md5(`".$this->table_name."`.`id`) = '" . $_GET['verify'] . "' AND `".$this->table_name."`.`record_status`='1'";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					
					$save = execute_sql_query($query_settings);
				}else{
					//REPORT INVALID TABLE ERROR
					$err = new cError(000001);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'cusers.php';
					$err->method_in_class_that_triggered_error = '_verify_email_address';
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 160';
					
					$result_of_all_processing = $err->error();
				}
				
				if( isset( $save['success'] ) && $save['success'] == 1 ){
					//SUCCESSFUL VERIFICATION
					
					if( $email_address && $user_id && $password ){
						
						//Send Successful Email Verification Message
						$email = new cEmails();
						$email->class_settings = array(
							'database_connection' => $this->class_settings[ 'database_connection' ],
							'database_name' => $this->class_settings[ 'database_name' ],
							'calling_page' => $this->class_settings[ 'calling_page' ],
							
							'user_id' => $user_id ,
							
							'action_to_perform' => 'send_mail',
						);
						
						$email->destination['email'][] = $email_address;
						$email->destination['full_name'][] = ucwords( $first_name . ' ' . $last_name );
						$email->destination['id'][] = $user_id;
						
						$email->message_type = 30;	//Successful Registration template
						$email->sender = $project;
						
						$email->emails();
					}
					
					//Prevent Form Generation
					$generate_form = false;
					
					//Login New User
					$classname = 'authentication';
					$actual_name_of_class = 'c'.ucwords($classname);
					
					$module = new $actual_name_of_class();
					
					$module->class_settings = array(
						'database_connection' => $this->class_settings[ 'database_connection' ],
						'database_name' => $this->class_settings[ 'database_name' ],
						'calling_page' => $this->class_settings[ 'calling_page' ],
						
						'username' => $email_address ,
						'password' => $password ,
						
						'action_to_perform' => 'confirm_username_and_password',
					);
					$returned_data = $module->$classname();
					
					if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
						//LOG SUCCESSFUL OPERATION IN AUDIT TRAIL
						//Auditor
						auditor( $this->class_settings , 'verified_account' , $this->table_name , 'user account with id ' . $user_id . ' in the table was verified ' );
						
						//Redirect to appropriate page
						header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'profile' );
						exit;
					}else{
						//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
						$err = new cError(000014);
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cusers.php';
						$err->method_in_class_that_triggered_error = '_users_registration_process';
						$err->additional_details_of_error = '';
						$result_of_all_processing = $err->error();
						
						header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'sign-in' );
						exit;
					}
				}else{
					//UNSUCCESSFUL VERIFICATION
					$err = new cError(000015);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cusers.php';
					$err->method_in_class_that_triggered_error = '_users_registration_process';
					$err->additional_details_of_error = '';
					$result_of_all_processing = $err->error();
				}
				
			}
			
			//2. NO DATA - GENERATE REGISTRATION FORM
			if( $generate_form ){
				//Disable appearance of all headings on forms
				$this->class_settings['do_not_show_headings'] = true;
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records' ] = array(
					'users008' => 1,
					
				);
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records_css' ] = array(
					'users009' => 1,
				);
				
				//Set Agreement Text
				$this->class_settings[ 'agreement_text' ] = 'I agree to the ' . $project['project_title'] . ' <a href="' . $this->class_settings['calling_page'] . 'footer/terms_of_agreement" class="special">Terms of Service</a> and <a href="' . $this->class_settings['calling_page'] . 'footer/privacy_policy" class="special">Privacy Policy</a>';
				
				
				return array_merge( $result_of_all_processing , $this->_generate_new_data_capture_form() );
			}
		}
		
		private function _users_registration_process(){
			//PROJECT DATA
			$project = get_project_data();
			
			//INITIALIZE RETURNING ARRAY
			$result_of_all_processing = array();
			
			//SET VARIABLE TO GENERATE FORM
			$generate_form = true;
			
			$email_exists = false;
			
			//1. CHECK FOR EXISTING EMAIL ADDRESS
			if( isset( $_POST['users004'] ) && $_POST['users004'] ){
			
				$query = "SELECT * FROM `" . $this->class_settings['database_name']."`.`" . $this->table_name . "` WHERE `" . $this->table_name . "`.`users007`='" . $_POST['users004'] . "' AND `" . $this->table_name . "`.`record_status`='1' ";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if( isset($sql_result[0]) ){
				
					$email_exists = true;
					
				}
				
			}
			
			$processing_status = null;
			
			if( $email_exists ){
				//EXISTING EMAIL ADDRESS ERROR
				$err = new cError(000102);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cusers.php';
				$err->method_in_class_that_triggered_error = '_users_registration_process';
				$err->additional_details_of_error = '';
				$processing_status = $err->error();
				
			}else{
				//CHECK FOR SUBMITTED FORM DATA
				$processing_status = $this->_save_changes();
			}
			
			//2. FOUND SUBMITTED FORM DATA - PROCESS THE DATA
			if( $processing_status && is_array( $processing_status ) ){
				//Process Response
				if( isset( $processing_status[ 'typ' ] ) ){
				
					switch( $processing_status[ 'typ' ] ){
					case 'saved':
						//Prevent Form Generation
						$generate_form = false;
						
						//Login New User
						$classname = 'authentication';
						$actual_name_of_class = 'c'.ucwords($classname);
						
						$module = new $actual_name_of_class();
						
						$module->class_settings = array(
							'database_connection' => $this->class_settings[ 'database_connection' ],
							'database_name' => $this->class_settings[ 'database_name' ],
							'calling_page' => $this->class_settings[ 'calling_page' ],
							
							'username' => $_POST[ 'users004' ],
							'password' => md5( $_POST[ 'users006' ] . get_websalter() ) ,
							
							'action_to_perform' => 'confirm_username_and_password',
						);
						$returned_data = $module->$classname();
						
						if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
							//Get Logged in User Details
							$user_details = array();
							$key = md5('ucert'.$_SESSION['key']);
							if( isset($_SESSION[$key]) ){
								$user_details = $_SESSION[$key];
								
								//Send Email Verification Message
								$email = new cEmails();
								$email->class_settings = array(
									'database_connection' => $this->class_settings[ 'database_connection' ],
									'database_name' => $this->class_settings[ 'database_name' ],
									'calling_page' => $this->class_settings[ 'calling_page' ],
									
									'user_id' => $user_details[ 'id' ],
									
									'action_to_perform' => 'send_mail',
								);
								
								$email->destination['email'][] = $user_details[ 'email' ];
								$email->destination['full_name'][] = ucwords($user_details[ 'fname' ] . ' ' . $user_details[ 'lname' ] );
								$email->destination['id'][] = $user_details[ 'id' ];
								
								$email->message_type = 1;	//Successful Registration template
								$email->sender = $project;
								
								$email->emails();
								
								$email->message_type = 2;	//Verification template
								$email->emails();
								
								//Redirect to appropriate page
								header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'profile' );
								exit;
							}
						}else{
							//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
							$err = new cError(000014);
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cusers.php';
							$err->method_in_class_that_triggered_error = '_users_registration_process';
							$err->additional_details_of_error = '';
							$processing_status = $err->error();
							
							header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'sign-in' );
							exit;
						}
						
						//Return Saved Succcessfully Message
						return $processing_status;
					break;
					default:
						//Return Error Message
						$result_of_all_processing = $processing_status;
					break;
					}
					
				}
			}
			
			//3. NO DATA - GENERATE REGISTRATION FORM
			if( $generate_form ){
				//Disable appearance of all headings on forms
				$this->class_settings['do_not_show_headings'] = true;
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records' ] = array(
					'users008' => 1,
				);
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records_css' ] = array(
					'users009' => 1,
				);
				
				$this->class_settings[ 'form_submit_button' ] = 'Register';
				
				//Set Agreement Text
				$this->class_settings[ 'agreement_text' ] = 'I agree to the ' . $project['project_title'] . ' <a href="' . $this->class_settings['calling_page'] . 'footer/terms_of_agreement" class="special">Terms of Service</a> and <a href="' . $this->class_settings['calling_page'] . 'footer/privacy_policy" class="special">Privacy Policy</a>';
				
				
				return array_merge( $result_of_all_processing , $this->_generate_new_data_capture_form() );
			}
		}
		
		private function _get_all_users_details(){
			$returned_data = array();
			
			$cache_key = $this->table_name;
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case 'email':
				case 'other_names':
				case 'firstname':
				case 'lastname':
				case 'bank_name':
				case 'department':
				case 'ref_no':
				case 'photograph':
				case 'account_number':
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `".$val."` as '".$key."'";
				break;
				}
			}
			
            $this->class_settings['where'] = " WHERE `record_status` = '1' AND `".$this->table_fields["role"]."` != '1300130013' ";
            
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ".$this->class_settings['where'];
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
            $id = '';
            
			$returned_data = array();
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $s_val ){
					$returned_data[ "default" ][ $s_val['id'] ] = $s_val["firstname"] . " " . $s_val["lastname"];
					
					$returned_data[ "info" ][ $s_val['id'] ] = $s_val;
				}
				
				//Cache Settings
				$settings = array(
					'cache_key' => $cache_key.'-all-users',
					'cache_values' => $returned_data[ "default" ],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				//Cache Settings
				$settings = array(
					'cache_key' => $cache_key.'-all-users-info',
					'cache_values' => $returned_data[ "info" ],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
			}
			return $returned_data;
		}
		
		private function _get_user_details(){
			$returned_data = array();
			
			$cache_key = $this->table_name;
			
			if( isset( $this->class_settings['user_id'] ) && $this->class_settings['user_id'] ){
				$settings = array(
					'cache_key' => $cache_key . '-' . $this->class_settings['user_id'],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				
				//CHECK WHETHER TO CLEAR CACHE VALUES
				if( isset( $this->class_settings[ 'clear_cache' ] ) && $this->class_settings[ 'clear_cache' ] ){
					unset( $this->class_settings[ 'clear_cache' ] );
					return clear_cache_for_special_values( $settings );
				}
				
				//CHECK WHETHER TO CHECK FOR CACHE VALUES
				if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
					
					//CHECK IF CACHE IS SET
					$cached_values = get_cache_for_special_values( $settings );
					if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
						
						return $cached_values;
						
					}
					
				}
				
				if( ! isset( $this->class_settings['where'] ) ){
					$this->class_settings['where'] = " WHERE `id`='".$this->class_settings['user_id']."' ";
				}
			}
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $key != 'oldpassword' ){
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `".$val."` as '".$key."'";
				}
			}
			
            if( ! isset( $this->class_settings['where'] ) )$this->class_settings['where'] = " WHERE `record_status`='1' ";
            
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ".$this->class_settings['where'];
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
            $id = '';
            
			$access_roles = array();
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $s_val ){
					$returned_data[ $s_val['id'] ] = $s_val;
					
					$access_roles[ $s_val["role"] ][ $s_val['id'] ] = $s_val['id'];
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$s_val['id'],
						'cache_values' => $s_val,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
                    
				}
			}
			
			foreach( $access_roles as $k_role => $val ){
				if( ! isset( $access_roles[ $k_role ] ) )continue;
				
				//Cache Settings
				$settings = array(
					'cache_key' => $cache_key.'-'.$k_role,
					'cache_values' => $access_roles[ $k_role ],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
			}
			
			$this->_get_all_users_details();
			
            if( isset( $s_val ) )return $s_val;
		}
		
	}
?>