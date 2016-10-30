<?php
	/**
	 * Site_users Class
	 *
	 * @used in  				Site_users Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	Site_users
	 */

	/*
	|--------------------------------------------------------------------------
	| Site_users Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cSite_users{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'site_users';
		
        private $default_password = '1234567890';
        
		private $table_fields = array(
			'email' => 'site_users003',
			
			'title' => 'site_users030',
			
			'firstname' => 'site_users001',
			'lastname' => 'site_users002',
			'previous_names' => 'site_users031',
			
			'oldpassword' => 'site_users006',
			'password' => 'site_users007',
			'confirmpassword' => 'site_users008',
			
			'phonenumber' => 'site_users004',
			'birth_day' => 'site_users010',
			'sex' => 'site_users011',
			'photograph' => 'site_users018',
			
			'role' => 'site_users009',
			
			'updated_primary_address' => 'site_users019',
			'verified_email_address' => 'site_users020',
			
			'country' => 'site_users012',
			'state' => 'site_users013',
			'city' => 'site_users014',
			'postal_code' => 'site_users015',
			'street_address' => 'site_users016',
			
			'office_address' => 'site_users032',
			'office_email' => 'site_users033',
			'office_phone' => 'site_users034',
			
			'employer' => 'site_users035',
			'employer_address' => 'site_users036',
			'employer_date_of_employment' => 'site_users037',
			'employer_status' => 'site_users038',
			'employer_salary' => 'site_users039',
			
			'additional_information' => 'site_users040',
			'attestation' => 'site_users041',
			
			'birth_certificate' => 'site_users042',
			'change_of_name' => 'site_users043',
			'nysc_certificate' => 'site_users044',
			'other_documents' => 'site_users045',
			
			'recommender' => 'site_users046',
			'recommender_email' => 'site_users047',
			'recommender_phonenumber' => 'site_users048',
			'recommender_organization' => 'site_users049',
			'recommender_address' => 'site_users050',
			'recommender_signature' => 'site_users051',
			'recommender_status' => 'site_users052',
			'recommendation_date' => 'site_users053',
			
			'number' => 'site_users054',
			'form_number' => 'site_users055',
			'receipt_number' => 'site_users056',
			'file_number' => 'site_users057',
			
			'submitted_complete_documents' => 'site_users058',
			'registration_date' => 'site_users059',
			'registration_number' => 'site_users060',
			'submitted_certificate' => 'site_users061',
			
			'application_batch' => 'site_users062',
			'application_qualification' => 'site_users063',
			'date_application_was_approved' => 'site_users064',
			'professional_qualification' => 'site_users065',
			'professional_qualification_date' => 'site_users066',
			'official_remark' => 'site_users067',
			
			'registration_status' => 'site_users070',
			'date_of_last_renewal' => 'site_users071',
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
		
		private $default_application_status = 'pending_application';
		
		function __construct(){
			
		}
	
		function site_users(){
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
			case 'display_all_records_buyers':
			case 'display_all_records_sellers':
			case 'display_all_records_admin_sellers':
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'save_personal_info':
			case 'save_contact_info':
			case 'save_password_info':
				$returned_value = $this->_save_user_info();
			break;
			case 'save_registration':
				$returned_value = $this->_save_registration();
			break;
			case 'display':
				$returned_value = $this->_display();
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
			case 'edit_password':
				$returned_value = $this->_change_user_password_admin();
			break;
			case 'get_user_details':
				$returned_value = $this->_get_user_details();
			break;
			case 'get_all_users_countries':
				$returned_value = $this->_get_all_users_countries();
			break;
			case 'site_registration':
			case 'applicant_recommendation':
			case 'verify_membership':
				$returned_value = $this->_site_registration();
			break;
			case 'site_users_authentication':
			case 'site_users_reset_password':
				$returned_value = $this->_site_users_authentication();
			break;
			case 'authenticate_user':
				$returned_value = $this->_authenticate_user();
			break;
			case 'reset_user_password':
				$returned_value = $this->_reset_user_password();
			break;
			case 'get_bank_account_verification_fee':
				$returned_value = $this->_get_bank_account_verification_fee();
			break;
			case 'display_user_details_data_capture_form':
			case 'display_site_users_address_data_capture_form':
				$returned_value = $this->_display_user_details_data_capture_form();
			break;
			case 'site_users_authentication_form_only':
				$returned_value = $this->_get_authentication_form();
			break;
			case 'site_users_registration_form_only':
			case 'site_users_guest_registration_form':
				$returned_value = $this->_get_registration_form();
			break;
			case 'site_users_google_authentication':
				$returned_value = $this->_site_users_google_authentication();
			break;
			case 'site_users_facebook_authentication':
				$returned_value = $this->_site_users_facebook_authentication();
			break;
			case 'create_new_user_account_and_authenticate_user':
				$returned_value = $this->_create_new_user_account_and_authenticate();
			break;
            case 'get_newly_registered_users':
			case 'get_all_registered_users':
				$returned_value = $this->_get_all_registered_users();
			break;
            case 'quick_details_view':
				$returned_value = $this->_quick_details_view();
			break;
            case 'resend_verification_email':
				$returned_value = $this->_resend_verification_email();
			break;
            case 'user_registration_form':
            case 'teller_registration_form':
				$returned_value = $this->_user_registration_form();
			break;
			case 'display_my_profile_manager':
				$returned_value = $this->_display_my_profile_manager();
			break;
			case 'save_update_profile':
				$returned_value = $this->_save_update_profile();
			break;
			case 'online_registration_form':
			case 'update_bio_data_form':
				$returned_value = $this->_online_registration_form();
			break;
			case "update_employment_details_form":
			case "update_additional_documents_form":
			case 'online_registration_employment_form':
			case 'online_registration_attestation_form':
			case 'online_registration_passport_form':
			case 'online_registration_recommendation_form':
			case 'online_registration_document_form':
			case 'online_registration_other_info_form':
			case 'online_registration_payment_form':
				$returned_value = $this->_online_registration_passport_form();
			break;
			case 'save_online_registration_recommendation_form':
			case 'save_online_registration_employment_form':
			case 'save_online_registration_attestation_form':
			case 'save_online_registration_passport_form':
			case 'save_online_registration_document_form':
			case 'save_online_registration_other_info_form':
			case 'save_online_registration_form':
			case 'save_applicant_recommendation':
			case "save_process_and_review_apllication":
			case "save_process_and_review_application":
				$returned_value = $this->_save_online_registration_form();
			break;
			case 'unapproved':
			case 'unprocessed_applications':
			case 'processed_applications':
			case 'approved_applications':
			case 'unapproved_applications':
			case 'in_progress':
			case 'unpaid':
			case 'all_active_members':
			case 'renewal_in_progress':
			case 'recent_inductees':
			case 'expired_members':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case "payment_confirmed_application":
			case "payment_failed_application":
			case "approved_application":
			case "declined_application":
			case "registered_member_application":
			case "pending_renewal_application":
			case "retired_application":
			case 'process_and_review_application':
				$returned_value = $this->_process_and_review_application();
			break;
			case 'assign_lrcn_registration_number':
				$returned_value = $this->_assign_lrcn_registration_number();
			break;
			case 'get_education_and_work_history':
				$returned_value = $this->_get_education_and_work_history();
			break;
			case 'upload':
				$returned_value = $this->_upload();
			break;
			case 'save_verify_membership':
				$returned_value = $this->_save_verify_membership();
			break;
			case 'auto_load_daily_update':
				$returned_value = $this->_auto_load_daily_update();
			break;
			case 'get_users_bar_chart':
			case 'get_users_pie_chart':
				$returned_value = $this->_get_users_pie_chart();
			break;
			case 'get_applicants_stats':
				$returned_value = $this->_get_applicants_stats();
			break;
			case 'new_password':
				$returned_value = $this->_new_password();
			break;
			case 'view_statement_of_account':
			case 'statement_of_account':
				$returned_value = $this->_statement_of_account();
			break;
			case 'update_table_field':
				$returned_value = $this->_update_table_field();
			break;
			case 'bulk_new_password':
			case 'bulk_statement_of_account':
				$returned_value = $this->_bulk_statement_of_account();
			break;
			case 'bulk_update_registration_status_and_send_mail':
				$returned_value = $this->_bulk_update_registration_status_and_send_mail();
			break;
			case "pending_application":
			case "expired_librarians":
			case "active_librarians":
			case "all_librarians":
			case "processed_applications":
			case "approved_applications":
			case "declined_applications":
				$returned_value = $this->_get_users();
			break;
			}
			
			return $returned_value;
		}
		
		private function _get_users(){
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case 'email':
				case 'other_names':			
				case 'firstname':
				case 'lastname':
				case 'phone_number':
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `".$val."` as '".$key."'";
				break;
				}
			}
			
			$where = "";
			switch ( $this->class_settings['action_to_perform'] ){
			case "pending_application":
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'pending_application' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'application_recommended' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'application_complete' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'pending_payment' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'payment_confirmed' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'payment_failed' ) ";
			break;
			case "expired_librarians":
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'pending_renewal' ) ";
			break;
			case "active_librarians":
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'registered_member' ) ";
			break;
			case "all_librarians":
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'pending_renewal' ) ";
			break;
			case "processed_applications":
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'processed' ) ";
			break;
			case "approved_applications":
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'approved' ) ";
			break;
			case "declined_applications":
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'declined' ) ";
			break;
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
		
		private function _bulk_update_registration_status_and_send_mail(){
			//update registration status of members
			
			$check = get_general_settings_value( array(
				'table' => $this->table_name,
				'key' => 'AUTO CHECK EXPIRED MEMBERS',
			) );
			if( strtolower( trim( $check ) ) == "yes" ){
				
				$query = "SELECT `id`, serial_num` FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND `".$this->table_fields["registration_status"]."` = 'registered_member' AND ( `".$this->table_fields["date_of_last_renewal"]."` < " . ( date("U") - ( 365*24*3600 ) ) . " )";
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if( isset($sql_result) && is_array($sql_result) ){
					foreach( $sql_result as $val ){
						$_POST["id"] = $val["id"];
						$_POST[ $this->table_fields[ "registration_status" ] ] = "pending_renewal";
						
						$this->class_settings["email_type"] = 5;
						$this->class_settings["email_title"] = "MEMBERSHIP EXPIRY";
						
						$this->_update_table_field();
					}
				}
			}
			
			//notify registered members 30 days to expiry
			$query = "SELECT `id`, serial_num`, `".$this->table_fields["date_of_last_renewal"]."` as 'date_of_last_renewal'  FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND `".$this->table_fields["registration_status"]."` = 'registered_member' AND ( `".$this->table_fields["date_of_last_renewal"]."` < " . ( date("U") - ( 335*24*3600 ) ) . " )";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			$start = date("U") - ( 335*24*3600 );
			if( isset($sql_result) && is_array($sql_result) ){
				foreach( $sql_result as $val ){
					$cache_settings = array(
						'cache_key' => "expiry-notice-".$val["id"],
					);
					$cache = get_cache_for_special_values( $cache_settings );
					if( $cache )continue;
					
					unset( $_POST );
					$_POST["id"] = $val["id"];
					
					$days_left = $start - $val["date_of_last_renewal"];
					
					$this->class_settings["send_email"] = 1;
					$this->class_settings["email_type"] = 5;
					$this->class_settings["email_title"] = "MEMBERSHIP EXPIRING IN ".$days_left." Days";
					
					$this->_update_table_field();
					
					//set cache to prevent sending notification every day
					$cache_settings = array(
						'cache_key' => "expiry-notice-".$val["id"],
						'cache_values' => 1,
						'cache_time' => 'notice-delay',
					);
					set_cache_for_special_values( $cache_settings );
				}
			}
		}
		
		private function _bulk_statement_of_account(){
			$key = 'account-statements';
			$action = "statement_of_account";
			switch ( $this->class_settings['action_to_perform'] ){
			case 'bulk_new_password':
				$key = 'new-passwords';
				$action = "new_password";
			break;
			}
			
			$cache_settings = array(
				'cache_key' => $key,
			);
			$requests = get_cache_for_special_values( $cache_settings );
			if( is_array( $requests ) && ! empty( $requests ) ){
				//return existing request message with buttons to view progress / cancel action
				foreach( $requests as $id ){
					unset( $_POST["ids"] );
					unset( $_POST["id"] );
					
					$_POST["id"] = $id;
					$this->class_settings["action_to_perform"] = $action;
					$ex  = "_".$action;
					$return = $this->$ex();
					
					//sleep
					sleep(4);
				}
				clear_cache_for_special_values( $cache_settings );
			}
		}
		
		private function _statement_of_account(){
			$applicant = array();
			$return = array();
			
			//check if multiple records are selected
			if( isset( $_POST["ids"] ) && $_POST["ids"] && str_replace( ":::", "", $_POST["ids"] ) ){
				$m = explode(":::", $_POST["ids"] );
				if( is_array( $m ) && count( $m ) > 1 ){
					//check if an exisiting request has been queued
					$key = 'account-statements';
					$cache_settings = array(
						'cache_key' => $key,
					);
					$requests = get_cache_for_special_values( $cache_settings );
					if( is_array( $requests ) && ! empty( $requests ) ){
						//return existing request message with buttons to view progress / cancel action
						$this->class_settings[ 'data' ]["title"] = "Statements of Accounts are already in queue and being sent to the following users";
						
						$this->class_settings[ 'data' ]["body"] = "<ol>";
						foreach( $requests as $id ){
							$applicant = get_users_details( array( "id" => $id ) );
							if( isset( $applicant["id"] ) ){
								$this->class_settings[ 'data' ]["body"] .= "<li>".$applicant["firstname"]." ".$applicant["lastname"]." [".$applicant["email"]."] - ".$applicant["id"]."</li>";
							}
						}
						$this->class_settings[ 'data' ]["body"] .= "</ol>";
						
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/background-process-running.php' );
						$return = array();
						
						$return["status"] = "new-status";
						$return["html_replacement"] = $this->_get_html_view();
						$return["html_replacement_selector"] = "#form-content-area";
						$return["do_not_reload_table"] = 1;
						$return["javascript_functions"] = array( "set_function_click_event" );
						
						return $return;
					}
					
					//load requests in cache
					$cache_settings = array(
						'cache_key' => $key,
						'cache_values' => $m,
					);
					set_cache_for_special_values( $cache_settings );
					
					//trigger background process
					run_in_background( "send_statement_of_account", 0 );
					
					//display notification & msg informing users of how to check progress of the action
					$this->class_settings[ 'data' ]["title"] = "Statements of Accounts are currently being sent to the following users";
					$this->class_settings[ 'data' ]["body"] = "<ol>";
					foreach( $m as $id ){
						$applicant = get_users_details( array( "id" => $id ) );
						if( isset( $applicant["id"] ) ){
							$this->class_settings[ 'data' ]["body"] .= "<li>".$applicant["firstname"]." ".$applicant["lastname"]." [".$applicant["email"]."] - ".$applicant["id"]."</li>";
						}
					}
					$this->class_settings[ 'data' ]["body"] .= "</ol>";
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/background-process-running.php' );
					$return = array();
					
					$return["status"] = "new-status";
					$return["html_replacement"] = $this->_get_html_view();
					$return["html_replacement_selector"] = "#form-content-area";
					$return["do_not_reload_table"] = 1;
					$return["javascript_functions"] = array( "set_function_click_event" );
					
					return $return;
				}
			}
			
			if( isset( $_POST["id"] ) ){
				$applicant = get_users_details( array( "id" => $_POST["id"] ) );
				
				if( ! isset( $applicant["id"] ) ){
					$keep = $this->class_settings["user_id"];
					$this->class_settings["user_id"] = $_POST["id"];
					$this->class_settings["do_not_check_cache"] = 1;
					$applicant = $this->_get_user_details();
					$this->class_settings["user_id"] = $keep;
				}
			}
			
			if( ! isset( $applicant["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cSite_users.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Applicant. Please Select a Valid Applicant or Contact System Admin If Problem Exists';
				return $err->error();
			}
			$u = $applicant;
			
			//get statement of account
			$this->class_settings['message_type'] = 1;
			
			$title = "LIBRARIANS REGISTRATION COUNCIL OF NIGERIAN: STATEMENT OF ACCOUNT";
			
			$info = 'ACCOUNT NAME: <b>'.strtoupper($u[ 'firstname' ] . " " . $u[ 'lastname' ]).'</b><br />';
			$info .= 'PHONE NUMBER: <b>' . $u[ 'phonenumber' ] . '</b><br />';
			$info .= 'EMAIL: <b>' . $u[ 'email' ] . '</b><br />';
			$info .= '<hr />ACCOUNT NUMBER: <b>' . $u[ 'id' ] . "/" . $u[ 'number' ] . '</b><br />';
			$info .= 'LRCN REG. NUMBER: <b>' . $u[ 'registration_number' ] . '</b><br />';
			$info .= '<hr />MEMBERSHIP STATUS: <b>' . strtoupper( get_select_option_value( array( "id" => $u["registration_status"], "function_name" => "get_registration_status" ) ) ) . '</b><br />';
			
			$this->class_settings[ 'payee_id' ] = $u[ 'id' ];
			$this->class_settings[ 'member_id' ] = $u[ 'id' ];
			$this->class_settings[ 'no_delete' ] = 1;
			
			$edu = new cMembership_renewal();
			$edu->class_settings = $this->class_settings;
			$edu->class_settings["action_to_perform"] = 'get_membership_renewals_view';
			$wh = $edu->membership_renewal();
			$extra = "<h5>Membership Renewals</h5>".$wh["html"];
			
			$edu = new cPayment_type();
			$edu->class_settings = $this->class_settings;
			$edu->class_settings["action_to_perform"] = 'get_payment_type_view';
			$wh = $edu->payment_type();
			$extra .= "<hr /><br /><h5>Other Payments</h5>".$wh["html"];
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-style.php' );
			$email_style = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
			
			$this->class_settings[ 'data' ] = array();
			$this->class_settings[ 'data' ]["html"] = $extra;
			$this->class_settings[ 'data' ]["full_name"] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
			$this->class_settings[ 'data' ]["email"] = $u["email"];
			$this->class_settings[ 'data' ]["title"] = $title;
			$this->class_settings[ 'data' ]["info"] = $info;
			$this->class_settings[ 'data' ]["skip_style"] = 1;
			$this->class_settings[ 'data' ]["mail_type"] = 4;
			
			$this->class_settings[ 'user_email' ] = $u["email"];
			$this->class_settings[ 'user_full_name' ] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
			$this->class_settings[ 'user_id' ] = $u[ 'id' ];
			
			$email_msg = $this->_get_html_view();
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'view_statement_of_account':
			break;
			default:
				$this->class_settings['message'] = $email_style . $email_msg;
				$this->class_settings['subject'] = $title;
				$this->_send_email();
				
				$sms = get_general_settings_value( array(
					'table' => $this->table_name,
					'key' => 'ACTIVATE SMS NOTIFICATION',
				) );
				
				if( "yes" == strtolower( trim( $sms ) ) ){
					$sms = 1;
				}else{
					$sms = 0;
				}
				if( $sms ){
					$this->class_settings["message"] = "STATEMENT OF YOUR LRCN MEMBERSHIP ACCOUNT HAS BEEN SENT TO YOUR EMAIL";
					$this->class_settings["phone"] = $u[ 'phonenumber' ];
					
					if( isset( $this->class_settings["message"] ) && $this->class_settings["message"] && isset( $this->class_settings["phone"] ) ){
						$edu = new cSend_sms();
						$edu->class_settings = $this->class_settings;
						$edu->class_settings["action_to_perform"] = 'send_sms';
						$edu->send_sms();
					}
				}
					
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cSite_users.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Account Statement has been sent to <b>'.strtoupper($u[ 'firstname' ] . " " . $u[ 'lastname' ]).' ('.$u["email"].')</b>';
				$return = $err->error();
				unset( $return["html"] );
			break;
			}
			
			$return["status"] = "new-status";
			$return["html_replacement"] = $email_msg;
			$return["html_replacement_selector"] = "#form-content-area";
			$return["do_not_reload_table"] = 1;
			
			return $return;
		}
		
		private function _new_password(){
			$applicant = array();
			//check if multiple records are selected
			if( isset( $_POST["ids"] ) && $_POST["ids"] && str_replace( ":::", "", $_POST["ids"] ) ){
				$m = explode(":::", $_POST["ids"] );
				if( is_array( $m ) && count( $m ) > 1 ){
					//check if an exisiting request has been queued
					$key = 'new-passwords';
					$cache_settings = array(
						'cache_key' => $key,
					);
					$requests = get_cache_for_special_values( $cache_settings );
					if( is_array( $requests ) && ! empty( $requests ) ){
						//return existing request message with buttons to view progress / cancel action
						$this->class_settings[ 'data' ]["title"] = "New Passwords are already in queue and being sent to the following users";
						
						$this->class_settings[ 'data' ]["body"] = "<ol>";
						foreach( $requests as $id ){
							$applicant = get_users_details( array( "id" => $id ) );
							if( isset( $applicant["id"] ) ){
								$this->class_settings[ 'data' ]["body"] .= "<li>".$applicant["firstname"]." ".$applicant["lastname"]." [".$applicant["email"]."] - ".$applicant["id"]."</li>";
							}
						}
						$this->class_settings[ 'data' ]["body"] .= "</ol>";
						
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/background-process-running.php' );
						$return = array();
						
						$return["status"] = "new-status";
						$return["html_replacement"] = $this->_get_html_view();
						$return["html_replacement_selector"] = "#form-content-area";
						$return["do_not_reload_table"] = 1;
						$return["javascript_functions"] = array( "set_function_click_event" );
						
						return $return;
					}
					
					//load requests in cache
					$cache_settings = array(
						'cache_key' => $key,
						'cache_values' => $m,
					);
					set_cache_for_special_values( $cache_settings );
					
					//trigger background process
					run_in_background( "send_new_password", 0 );
					
					//display notification & msg informing users of how to check progress of the action
					$this->class_settings[ 'data' ]["title"] = "New Passwords are currently being sent to the following users";
					$this->class_settings[ 'data' ]["body"] = "<ol>";
					foreach( $m as $id ){
						$applicant = get_users_details( array( "id" => $id ) );
						if( isset( $applicant["id"] ) ){
							$this->class_settings[ 'data' ]["body"] .= "<li>".$applicant["firstname"]." ".$applicant["lastname"]." [".$applicant["email"]."] - ".$applicant["id"]."</li>";
						}
					}
					$this->class_settings[ 'data' ]["body"] .= "</ol>";
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/background-process-running.php' );
					$return = array();
					
					$return["status"] = "new-status";
					$return["html_replacement"] = $this->_get_html_view();
					$return["html_replacement_selector"] = "#form-content-area";
					$return["do_not_reload_table"] = 1;
					$return["javascript_functions"] = array( "set_function_click_event" );
					
					return $return;
				}
			}
			
			if( isset( $_POST["id"] ) ){
				$applicant = get_users_details( array( "id" => $_POST["id"] ) );
				
				if( ! isset( $applicant["id"] ) ){
					$keep = $this->class_settings["user_id"];
					$this->class_settings["user_id"] = $_POST["id"];
					$this->class_settings["do_not_check_cache"] = 1;
					$applicant = $this->_get_user_details();
					$this->class_settings["user_id"] = $keep;
				}
			}
			
			if( ! isset( $applicant["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cSite_users.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Applicant. Please Select a Valid Applicant or Contact System Admin If Problem Exists';
				return $err->error();
			}
			
			//reset password
			$password = generatePassword( 8, 1, 0, 1, 0);
			$_POST[ "id" ] = $applicant["id"];
			$_POST[ "uid" ] = $this->class_settings["user_id"];
			$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"";
			$_POST[ "table" ] = $this->table_name;
			$_POST[ "processing" ] = md5(1);
			define('SKIP_USE_OF_FORM_TOKEN', 1);
			
			$_POST[ $this->table_fields["oldpassword"] ] = $password;
			$_POST[ $this->table_fields["password"] ] = $password;
			$_POST[ $this->table_fields["confirmpassword"] ] = $password;
			$return = $this->_save_changes();
			
			if( isset( $return["typ"] ) && $return["typ"] == "saved" ){
				unset( $this->class_settings['user_id'] );
				$this->class_settings['where'] = " WHERE `id`='".$_POST['id']."' ";
				$u = $this->_get_user_details();
				
				if( isset( $u[ 'email' ] ) && $u[ 'email' ] ){
					//send email to applicant 
					$this->class_settings['message_type'] = 1;
					
					$title = "LIBRARIANS REGISTRATION COUNCIL OF NIGERIAN: NEW PASSWORD";
					
					$info = 'ACCOUNT NAME: <b>'.strtoupper($u[ 'firstname' ] . " " . $u[ 'lastname' ]).'</b><br />';
					$info .= 'PHONE NUMBER: <b>' . $u[ 'phonenumber' ] . '</b><br />';
					$info .= 'EMAIL: <b>' . $u[ 'email' ] . '</b><br />';
					$info .= '<hr />NEW PASSOWRD: <b>' . $u[ 'oldpassword' ] . '</b><br />';
						
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
					
					$this->class_settings[ 'data' ] = array();
					$this->class_settings[ 'data' ]["full_name"] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
					$this->class_settings[ 'data' ]["email"] = $u["email"];
					$this->class_settings[ 'data' ]["title"] = $title;
					$this->class_settings[ 'data' ]["info"] = $info;
					$this->class_settings[ 'data' ]["mail_type"] = 3;
					
					$this->class_settings[ 'user_email' ] = $u["email"];
					$this->class_settings[ 'user_full_name' ] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
					$this->class_settings[ 'user_id' ] = $u[ 'id' ];
					
					$this->class_settings['message'] = $this->_get_html_view();
					$this->class_settings['subject'] = $title;
					$this->_send_email();
					
					$sms = get_general_settings_value( array(
						'table' => $this->table_name,
						'key' => 'ACTIVATE SMS NOTIFICATION',
					) );
					
					if( "yes" == strtolower( trim( $sms ) ) ){
						$sms = 1;
					}else{
						$sms = 0;
					}
					if( $sms ){
						$this->class_settings["message"] = "A NEW PASSWORD HAS BEEN SET FOR YOUR LRCN ACCOUNT. NEW PASSWORD: ".$u[ 'oldpassword' ];
						$this->class_settings["phone"] = $u[ 'phonenumber' ];
						
						if( isset( $this->class_settings["message"] ) && $this->class_settings["message"] && isset( $this->class_settings["phone"] ) ){
							$edu = new cSend_sms();
							$edu->class_settings = $this->class_settings;
							$edu->class_settings["action_to_perform"] = 'send_sms';
							$edu->send_sms();
						}
					}
					
					$err = new cError(010011);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'cSite_users.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = 'Account Password has been reset and sent to <b>'.strtoupper($u[ 'firstname' ] . " " . $u[ 'lastname' ]).' ('.$u["email"].')</b>';
					return $err->error();
				}
			}
			return $return;
		}
		
		private function _update_table_field(){
			$applicant = array();
			
			if( ! isset( $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cSite_users.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Applicant. Please Select a Valid Applicant or Contact System Admin If Problem Exists';
				return $err->error();
			}
			
			$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
			$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
			$_POST[ "table" ] = $this->table_name;
			$_POST[ "processing" ] = md5(1);
			if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
				define('SKIP_USE_OF_FORM_TOKEN', 1);
			
			$push = 0;
			if( isset( $this->class_settings["update_fields"] ) && is_array( $this->class_settings["update_fields"] ) ){
				foreach( $this->class_settings["update_fields"] as $key => $val ){
					if( isset( $this->table_fields[ $key ] ) ){
						$_POST[ $this->table_fields[ $key ] ] = $val;
						$push = 1;
					}
				}
			}
			
			if( $push )
				$return = $this->_save_changes();
			
			
			$sms = get_general_settings_value( array(
				'table' => $this->table_name,
				'key' => 'ACTIVATE SMS NOTIFICATION',
			) );
			
			if( "yes" == strtolower( trim( $sms ) ) ){
				$sms = 1;
			}else{
				$sms = 0;
			}
			
			if( ( isset( $this->class_settings["send_email"] ) && $this->class_settings["send_email"] ) || isset( $return["typ"] ) && $return["typ"] == "saved" ){
				if( isset( $this->class_settings["email_data"]["name"] ) && isset( $this->class_settings["email_data"]["email"] ) && isset( $this->class_settings["email_data"]["phone"] ) ){
					$u['firstname'] =  $this->class_settings["email_data"]["name"];
					$u['lastname'] =  "";
					$u['id'] =  1;
					$u['email'] =  $this->class_settings["email_data"]["email"];
					$u['phonenumber'] =  $this->class_settings["email_data"]["phone"];
				}else{
					unset( $this->class_settings['user_id'] );
					$this->class_settings['where'] = " WHERE `id`='".$_POST['id']."' ";
					$u = $this->_get_user_details();
				}
				
				if( isset( $this->class_settings["email_title"] ) && isset( $this->class_settings["email_type"] ) && isset( $u[ 'email' ] ) && $u[ 'email' ] ){
					//send email to applicant 
					$this->class_settings['message_type'] = 1;
					
					$title = "LIBRARIANS REGISTRATION COUNCIL OF NIGERIAN: " . $this->class_settings["email_title"];
					
					$info = 'ACCOUNT NAME: <b>'.strtoupper($u[ 'firstname' ] . " " . $u[ 'lastname' ]).'</b><br />';
					$info .= 'PHONE NUMBER: <b>' . $u[ 'phonenumber' ] . '</b><br />';
					$info .= 'EMAIL: <b>' . $u[ 'email' ] . '</b><br />';
					
					switch( $this->class_settings["email_type"] ){
					case 5:
						$info .= '<hr />ACCOUNT NUMBER: <b>' . $u[ 'id' ] . "/" . $u[ 'number' ] . '</b><br />';
						$info .= 'LRCN REG. NUMBER: <b>' . $u[ 'registration_number' ] . '</b><br />';
						$info .= '<hr />MEMBERSHIP STATUS: <b>' . strtoupper( get_select_option_value( array( "id" => $u["registration_status"], "function_name" => "get_registration_status" ) ) ) . '</b><br />';
						
						if( $sms ){
							$this->class_settings["message"] = 'LRCN MEMBERSHIP STATUS HAS BEEN UPDATED TO: ' . strtoupper( get_select_option_value( array( "id" => $u["registration_status"], "function_name" => "get_registration_status" ) ) );
						}
					break;
					case 6:
						$info .= '<hr />ACCOUNT NUMBER: <b>' . $u[ 'id' ] . "/" . $u[ 'number' ] . '</b><br />';
						$info .= 'LRCN REG. NUMBER: <b>' . $u[ 'registration_number' ] . '</b><br />';
					break;
					case 7:
					break;
					}
					
					if( $sms ){
						if( isset( $this->class_settings["sms"] ) ){
							$this->class_settings["message"] = $this->class_settings["sms"];
							$this->class_settings["phone"] = $u[ 'phonenumber' ];
						}
						
						if( isset( $this->class_settings["message"] ) && $this->class_settings["message"] && isset( $this->class_settings["phone"] ) ){
							$edu = new cSend_sms();
							$edu->class_settings = $this->class_settings;
							$edu->class_settings["action_to_perform"] = 'send_sms';
							$edu->send_sms();
						}
					}
						
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
					
					$this->class_settings[ 'data' ] = array();
					$this->class_settings[ 'data' ]["full_name"] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
					$this->class_settings[ 'data' ]["email"] = $u["email"];
					$this->class_settings[ 'data' ]["title"] = $title;
					$this->class_settings[ 'data' ]["info"] = $info;
					$this->class_settings[ 'data' ]["mail_type"] = $this->class_settings["email_type"];
					
					if( isset( $this->class_settings["email_html"] ) && $this->class_settings["email_html"] ){
						$this->class_settings[ 'data' ]["html"] = $this->class_settings["email_html"];
					}
					
					$this->class_settings[ 'user_email' ] = $u["email"];
					$this->class_settings[ 'user_full_name' ] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
					$this->class_settings[ 'user_id' ] = $u[ 'id' ];
					
					$this->class_settings['message'] = $this->_get_html_view();
					$this->class_settings['subject'] = $title;
					$this->_send_email();
					
					$err = new cError(010011);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'cSite_users.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = $this->class_settings["email_title"].' : <b>'.strtoupper($u[ 'firstname' ] . " " . $u[ 'lastname' ]).' ('.$u["email"].')</b>';
					return $err->error();
				}
			}
			
			return $return;
		}
		
		private function _get_applicants_stats(){
			$return = array();
			
			//$query = "SELECT COUNT(`id`) as 'total' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND `creation_date` >= ".date("U", mktime( 0,0,0, intval(date("m")), 1, date("y") ) );
			$query = "SELECT COUNT(`id`) as 'total' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND ( `".$this->table_fields["registration_status"]."` = 'application_complete' OR `".$this->table_fields["registration_status"]."` = 'application_recommended' OR `".$this->table_fields["registration_status"]."` = 'pending_application'  OR `".$this->table_fields["registration_status"]."` = 'pending_payment' OR `".$this->table_fields["registration_status"]."` = 'payment_confirmed' OR `".$this->table_fields["registration_status"]."` = 'payment_failed' ) AND `creation_date` >= ".date("U", mktime( 0,0,0, intval(date("m")), 1, date("y") ) );
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
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]["total"]) ){
				$return["this_month"] = doubleval( $sql_result[0]["total"] );
			}
			
			$query = "SELECT COUNT(`id`) as 'total' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND `".$this->table_fields["registration_status"]."` = 'processed' AND `creation_date` >= ".date("U", mktime( 0,0,0, 1, 1, date("y") ) );
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
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]["total"]) ){
				$return["this_year"] = doubleval( $sql_result[0]["total"] );
			}
			
			$query = "SELECT COUNT(`id`) as 'total' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' ";
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
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]["total"]) ){
				$return["total_librarians"] = doubleval( $sql_result[0]["total"] );
			}
			
			return $return;
		}
		
		private function _get_users_pie_chart(){
			
			$query = "SELECT COUNT(`id`) as 'total', `".$this->table_fields["registration_status"]."` as 'registration_status' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' GROUP BY `".$this->table_fields["registration_status"]."` ";
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
            
			$piechart = 1;
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_users_bar_chart':	
				$piechart = 0;
			break;
			}
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				$data = array();
				$total = 0;
				/*
				foreach( $sql_result as $sval ){
					$total += doubleval( $sval["total"] );
				}
				*/
				$reg = get_registration_status();
				$x_axis = array();
				$data1 = array();
				foreach( $sql_result as $sval ){
					if( $sval["total"] && isset( $reg[ $sval["registration_status"] ] ) ){
						$data[] = array(
							"name" => isset( $reg[ $sval["registration_status"] ] )?$reg[ $sval["registration_status"] ]:"Not Available",
							"y" => doubleval($sval["total"]),
						);
						
						$data1[] = array(
							"name" => isset( $reg[ $sval["registration_status"] ] )?$reg[ $sval["registration_status"] ]:"Not Available",
							"data" => array( doubleval($sval["total"]) ),
						);
					}
				}
				
				if( $piechart ){
					$return["highchart_data"] = pie_legend_chart();
					$return["highchart_data"]["series"][0]["data"] = $data;
				}else{
					$return["highchart_data"] = basic_column_chart();
					$return["highchart_data"]["series"] = $data1;
				}
				
				if( $piechart ){
					$return["highchart_exported_chart_name"] = "pie-chart";
					$return["highchart_container_selector"] = "#chart-container";
				}else{
					$return["highchart_exported_chart_name"] = "bar-chart";
					$return["highchart_container_selector"] = "#chart-container-1";
				}
					
				
				//$return["highchart_container_selector"] = "#".$chartname;
				
				$return["javascript_functions"] = array( 'activate_highcharts' );
				$return["status"] = "new-status";
				
				$return["do_not_reload_table"] = 1;
				
				if( $piechart ){					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = 1;
					$return['action'] = '?action=site_users&todo=get_users_bar_chart';
				}
			
				return $return;
			}
		}
		
		function get_date( $d, $split = "-" ){
			$e = explode( $split, $d );
			$day = 0;
			$month = 0;
			$year = 0;
			
			$months = array(
				"jan" => 1,
				"feb" => 2,
				"mar" => 3,
				"apr" => 4,
				"may" => 5,
				"jun" => 6,
				"jul" => 7,
				"aug" => 8,
				"sep" => 9,
				"oct" => 10,
				"nov" => 11,
				"dec" => 12,
			);
			if( is_array( $e ) && ! empty( $e ) ){
				
				if( isset( $d[0] ) )
					$day = intval( $d[0] );
				
				if( isset( $d[1] ) ){
					$m = $d[1];
					
					if( isset( $months[ strtolower( $m ) ] ) )
						$month = $months[ strtolower( $m ) ];
				}
				if( $month && $day ){
					if( isset( $d[2] ) )
						$year = intval( $d[2] );
				}else{
					$year = $day;
				}
				
			}else{
				if( $e ){
					$year = intval( $e );
				}else{
					return $this->get_date( $d, "/" );
				}
			}
			
			return mktime(0,0,0,$month,$day,$year);
		}
		
		private function _upload(){
			$data = json_decode( file_get_contents( "applicant.json" ), true );
			
			$states = get_states_in_country( array("country_id" => "1157") );
			$states_inv = array();
			foreach( $states as $id => $sv )
				$states_inv[ strtolower( trim($sv) ) ] = $id;
			
			print_r($data);
			exit;
			$array_of_dataset = array();
			$array_of_dataset1 = array();
			
			//$new_record_id = get_new_id();
			$new_record_id = 1;
			$new_record_id_serial = 1;
			
			$ip_address = get_ip_address();
			$date = date("U");
			foreach( $this->table_fields as $kf => $vf )
				$fields[ $vf ] = $kf;
				
			foreach( $data as $v ){
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
				$dataset_to_be_inserted1 = array();
				
				$dataset_to_be_inserted2 = $dataset_to_be_inserted;
				$dataset_to_be_inserted3 = $dataset_to_be_inserted;
				$dataset_to_be_inserted4 = $dataset_to_be_inserted;
				
				foreach( $v as $kv => $vv ){
					switch( $kv ){
					case "Renewal 2013":
						$dataset_to_be_inserted[ "id" ] = ++$new_record_id_serial;
						
						$dataset_to_be_inserted[ "membership_renewal001" ] = $v["site_users054"];
						if( strtolower($vv) == "1" ){
							$dataset_to_be_inserted[ "membership_renewal002" ] = "paid";
							$dataset_to_be_inserted[ "membership_renewal003" ] = mktime( 0,0,0,1,1,2013 );
						}else{
							$dataset_to_be_inserted[ "membership_renewal002" ] = "pending";
						}
						$array_of_dataset[] = $dataset_to_be_inserted;
					break;
					case "Renewal 2014":
						$dataset_to_be_inserted[ "id" ] = ++$new_record_id_serial;
						
						$dataset_to_be_inserted[ "membership_renewal001" ] = $v["site_users054"];
						if( strtolower($vv) == "1" ){
							$dataset_to_be_inserted[ "membership_renewal002" ] = "paid";
							$dataset_to_be_inserted[ "membership_renewal003" ] = mktime( 0,0,0,1,1,2014 );
						}else{
							$dataset_to_be_inserted[ "membership_renewal002" ] = "pending";
						}
						$array_of_dataset[] = $dataset_to_be_inserted;
					break;
					case "Renewal 2015":
						$dataset_to_be_inserted[ "id" ] = ++$new_record_id_serial;
						
						$dataset_to_be_inserted[ "membership_renewal001" ] = $v["site_users054"];
						if( strtolower($vv) == "1" ){
							$dataset_to_be_inserted[ "membership_renewal002" ] = "paid";
							$dataset_to_be_inserted[ "membership_renewal003" ] = mktime( 0,0,0,1,1,2015 );
						}else{
							$dataset_to_be_inserted[ "membership_renewal002" ] = "pending";
						}
						$array_of_dataset[] = $dataset_to_be_inserted;
					break;
					case "Renewal 2015z":
						if( isset( $states_inv[ strtolower( trim( $vv ) ) ] ) )
								$dataset_to_be_inserted[ "cities_list002" ] = $states_inv[ strtolower( trim( $vv ) ) ];
							else
								$dataset_to_be_inserted[ "cities_list002" ] = $vv;
							
						$array_of_dataset[] = $dataset_to_be_inserted;
					break;
					}
					++$new_record_id_serial;
					
					continue;
					
					if( isset( $fields[ $kv ] ) ){
						switch( $fields[ $kv ] ){
						case 'number':
							$dataset_to_be_inserted[ "id" ] = $vv;
							$dataset_to_be_inserted1[ "id" ] = $dataset_to_be_inserted[ "id" ];
							
						case "state":
							if( isset( $states_inv[ strtolower( trim( $vv ) ) ] ) )
								$dataset_to_be_inserted[ $kv ] = $states_inv[ strtolower( trim( $vv ) ) ];
							else
								$dataset_to_be_inserted[ $kv ] = $vv;
							
							$dataset_to_be_inserted1[ $fields[ $kv ] ] = $dataset_to_be_inserted[ $kv ];
						break;
						case "city":
							$dataset_to_be_inserted[ $kv ] = $vv;
							$dataset_to_be_inserted1[ $fields[ $kv ] ] = $dataset_to_be_inserted[ $kv ];
						break;
						case "creation_date":
							$dataset_to_be_inserted[ $kv ] = $this->get_date( $vv );
							$dataset_to_be_inserted1[ $kv ] = $dataset_to_be_inserted[ $kv ];
						break;
						case "attestation":
						case "recommendation_date":
						case "employer_date_of_employment":
						case "registration_date":
						case "date_application_was_approved":
						case "professional_qualification_date":
						case "birth_day":
							$dataset_to_be_inserted[ $kv ] = $this->get_date( $vv );
							$dataset_to_be_inserted1[ $fields[ $kv ] ] = $dataset_to_be_inserted[ $kv ];
						break;
						case "application_batch":
							$dataset_to_be_inserted[ $kv ] = intval( $vv );
							$dataset_to_be_inserted1[ $fields[ $kv ] ] = intval( $vv );
						break;
						case "country":
							if( strtolower(trim($vv)) == "nigerian" || strtolower(trim($vv)) == "nigeria" ){
								$dataset_to_be_inserted[ $kv ] = "1157";
							}else{
								$dataset_to_be_inserted[ $kv ] = strtolower($vv);
							}
							
							$dataset_to_be_inserted1[ $fields[ $kv ] ] = $dataset_to_be_inserted[ $kv ];
						break;
						case "sex":
							if( strtolower($vv) == "m" ){
								$dataset_to_be_inserted[ $kv ] = "male";
							}else{
								$dataset_to_be_inserted[ $kv ] = "female";
							}
							
							$dataset_to_be_inserted1[ $fields[ $kv ] ] = $dataset_to_be_inserted[ $kv ];
						break;
						case "application_qualification":
							if( strtolower($vv) == "q" ){
								$dataset_to_be_inserted[ $kv ] = "yes";
							}else{
								$dataset_to_be_inserted[ $kv ] = "no";
							}
							
							$dataset_to_be_inserted1[ $fields[ $kv ] ] = $dataset_to_be_inserted[ $kv ];
						break;
						case "registration_status":
							if( strtolower($vv) == "app" ){
								$dataset_to_be_inserted[ $kv ] = "registered_member";
							}else{
								//Retiree
								if( $v["Retiree"] ){
									$dataset_to_be_inserted[ $kv ] = "retired";
								}else{
									if( ! $v["Renewal Cummulative"] ){
										$dataset_to_be_inserted[ $kv ] = "pending_renewal";
									}else{
										$dataset_to_be_inserted[ $kv ] = strtolower($vv);
									}
								}
							}
							
							if( strtolower( trim( $v["site_users038"] ) ) == "retired" )
								$dataset_to_be_inserted[ $kv ] = "retired";
							
							$dataset_to_be_inserted1[ $fields[ $kv ] ] = $dataset_to_be_inserted[ $kv ];
							
						break;
						case "submitted_complete_documents":
						case "submitted_certificate":
							if( strtolower($vv) == "1" ){
								$dataset_to_be_inserted[ $kv ] = "yes";
							}else{
								$dataset_to_be_inserted[ $kv ] = "no";
							}
							
							$dataset_to_be_inserted1[ $fields[ $kv ] ] = $dataset_to_be_inserted[ $kv ];
							
						break;
						default:
							if( isset( $fields[ $kv ] ) ){
								$dataset_to_be_inserted[ $kv ] = $vv;
								$dataset_to_be_inserted1[ $fields[ $kv ] ] = $vv;
							}
						break;
						}
					}
				}
				
				//$array_of_dataset1[] = $dataset_to_be_inserted1;
				//$array_of_dataset[] = $dataset_to_be_inserted;
			}
			
			$saved = 0;
			if( ! empty( $array_of_dataset ) ){
				
				$function_settings = array(
					'database' => $this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'table' => "membership_renewal", //$this->table_name,
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
			
		}
		
		private function _auto_load_daily_update(){
			//CHECK FOR FILE
			$dir = $this->class_settings["calling_page"]."files/mail-loader/";
			$files = array();
			
			if( is_dir( $dir ) ){
				//3. Open & Read all files in directory
				$cdir = opendir( $dir );
				while( $cfile = readdir($cdir) ){
					if(!( $cfile == '.' || $cfile == '..' ) ){
						if ( preg_match("/.xlsx/", $cfile ) ) {
							$files[] = $cfile;
						}
					}
				}
				closedir($cdir);
			}
			
			$tx_data = array();
			$count = 0;
			
			foreach( $files as $file ){				
				if( ! file_exists( $dir.$file ) ){
					continue;
				}
				
				//READ FILE CONTENTS AND Cache
				$this->class_settings['excel_file_name'] = $dir.$file;
				$this->class_settings['import_table'] = $this->table_name;
				$this->class_settings['excel_field_mapping_option'] = 'serial-import';
				$this->class_settings['update_existing_records_based_on_fields'] = '';
				$this->class_settings['keep_excel_file_after_import'] = 1;
				$this->class_settings['accept_blank_excel_cells'] = 1;
				$this->class_settings['return_data'] = 1;
				
				$myexcel = new cMyexcel();
				$myexcel->class_settings = $this->class_settings;
				$myexcel->class_settings[ 'action_to_perform' ] = 'bulk_import_excel_file_data';
				$data = $myexcel->myexcel();
				
				$k = array();
				foreach( $data as $dd ){
					
					foreach( $dd as $k1 => $d1 ){
						//$k[ $d1 ] = $dd;
						foreach( $data as $ke => $ve ){
							$k[$ke][ $d1 ] = $ve[ $k1 ];
						}
					}
					
					break;
				}
				
				$settings = array(
					'cache_key' => "applicant",
					'cache_values' => $k,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				print_r($k);
				exit;
			}
			
		}
		
		private function _save_verify_membership(){
			$return = array();
			
			$field = "reg_no";
			$reg_no = "";
			if( isset( $_POST[ $field ] ) && $_POST[ $field ] )
				$reg_no = trim($_POST[ $field ]);
			
			$field = "surname";
			$surname = "";
			if( isset( $_POST[ $field ] ) && $_POST[ $field ] )
				$surname = trim($_POST[ $field ]);
			
			$field = "other_names";
			$other_names = "";
			if( isset( $_POST[ $field ] ) && $_POST[ $field ] )
				$other_names = trim($_POST[ $field ]);
			
			$field = "email";
			$email = "";
			if( isset( $_POST[ $field ] ) && $_POST[ $field ] )
				$email = trim($_POST[ $field ]);
			
			if( $reg_no || $email || $surname || $other_names ){
					
				$select = "";
				
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "title":	
					case "email":	
					case "registration_date":						
					case "registration_status":						
					case "firstname":						
					case "lastname":						
					case "photograph":						
					case "registration_date":						
					case "registration_number":						
						if( $select )$select .= ", `".$val."` as '".$key."'";
						else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `".$val."` as '".$key."'";
					break;
					}
				}
				
				$this->class_settings['where'] = " WHERE `record_status` = '1' AND ( ";
				
				$or = "";
				if( $other_names ){
					$this->class_settings['where'] .= $or . "`".$this->table_fields["lastname"]."` REGEXP '".$other_names."'";
					$or = " OR ";
				}
				if( $surname ){
					$this->class_settings['where'] .= $or . "`".$this->table_fields["firstname"]."` REGEXP '".$surname."'";
					$or = " OR ";
				}
				if( $email ){
					$this->class_settings['where'] .= $or . "`".$this->table_fields["email"]."` REGEXP '".$email."'";
					$or = " OR ";
				}
				if( $reg_no ){
					$this->class_settings['where'] .= $or . "`".$this->table_fields["registration_number"]."` REGEXP '".$reg_no."'";
				}
				$this->class_settings['where'] .= " ) ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ".$this->class_settings['where'];
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$this->class_settings[ 'data' ]["result"] = execute_sql_query($query_settings);
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/verify-members-result.php' );
				
				$return["html_replacement"] = $this->_get_html_view();
				$return["html_replacement_selector"] = "#verify-membership-results";
				
			}else{
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/no-verify-members-result.php' );
				$return["html_replacement"] = $this->_get_html_view();
				$return["html_replacement_selector"] = "#verify-membership-results";
			}
			$return["status"] = "new-status";
			
			return $return;
		}
		
		private function _get_education_and_work_history(){
			$this->class_settings[ 'member_id' ] = $this->class_settings["user_id"];
			
			$edu = new cEducational_history();
			$edu->class_settings = $this->class_settings;
			$edu->class_settings["action_to_perform"] = 'get_educational_histories_view';
			$eh = $edu->educational_history();
			
			$edu = new cWork_history();
			$edu->class_settings = $this->class_settings;
			$edu->class_settings["action_to_perform"] = 'get_work_histories_view';
			$wh = $edu->work_history();
			
			$returning_html_data = "<h4>Educational History</h4><hr />". $eh['html']. "<br /><h4>Work History</h4><hr />".$wh['html'];
			
			return array(
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
				'html_replacement_selector' => '#form-content-area',
			);
		}
		
		private function _assign_lrcn_registration_number(){
			//1. get last registration number
			$query = "SELECT MAX(`".$this->table_fields['registration_number']."`) as 'registration_number' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 0,
				'tables' => array( $this->table_name ),
			);
			
			$last_reg_num = 0;
			$all_data = execute_sql_query($query_settings);
			if( isset( $all_data[0]['registration_number'] ) && $all_data[0]['registration_number'] ){
				$last_reg_num = doubleval( $all_data[0]['registration_number'] );
			}
			
			//
			//2. get applicants for assignment
			//insert condition
			$query = "SELECT `serial_num` FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields['registration_number']."` < 1 AND `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'approved' ";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$saved = 0;
			$app_count = 0;
			$batch_no = 0;
			
			$all_data = execute_sql_query($query_settings);
			if( isset( $all_data[0]['serial_num'] ) && $all_data[0]['serial_num'] ){
				$array_of_dataset_update = array();
				$array_of_update_conditions = array();
				
				$ip_address = get_ip_address();
				$date = date("U");
				
				foreach( $all_data as $sval ){
					$dataset_to_be_inserted = array(
						'modified_by' => $this->class_settings[ 'user_id' ],
						'modification_date' => $date,
						'ip_address' => $ip_address,
						$this->table_fields['registration_number'] => ++$last_reg_num,
						$this->table_fields['registration_date'] => date("U"),
						$this->table_fields['date_of_last_renewal'] => date("U"),
						$this->table_fields['registration_status'] => 'registered_member',
					);
					
					$update_conditions_to_be_inserted = array(
						'where_fields' => 'serial_num',
						'where_values' => $sval[ "serial_num" ],
					);

					$array_of_dataset_update[] = $dataset_to_be_inserted;
					$array_of_update_conditions[] = $update_conditions_to_be_inserted;
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
				$app_count = count($all_data[0]);
				$batch_no = get_active_batch_number();
			}
			//reset cache
			
			//3. assign registration number
			
			//send email notification to applicants
			
			//4. display notification msg
			if( $saved ){				
				//increment current batch number -> create batch number settings class
				$app_batch = new cBatch_number();
				$app_batch->class_settings = $this->class_settings;
				$app_batch->class_settings["action_to_perform"] = 'increment_application_batch';
				$app_batch->batch_number();
			
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cSite_users.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Congratulations</h4> Registration Numbers have been successfully assigned to '.$app_count.' Nos. of Approved Applicants for Batch '.$batch_no;
			}else{
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cSite_users.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Could not Assign Registration Numbers</h4>The System could not identify any new approved applicant to assign registration number';
			}
			return $err->error();
		}
		
		private function _process_and_review_application(){
			$applicant = array();
			if( isset( $_POST["id"] ) ){
				$applicant = get_users_details( array( "id" => $_POST["id"] ) );
				
				if( ! isset( $applicant["id"] ) ){
					$keep = $this->class_settings["user_id"];
					$this->class_settings["user_id"] = $_POST["id"];
					$this->class_settings["do_not_check_cache"] = 1;
					$applicant = $this->_get_user_details();
					$this->class_settings["user_id"] = $keep;
				}
			}
			
			if( ! isset( $applicant["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cSite_users.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Applicant. Please Select a Valid Applicant or Contact System Admin If Problem Exists';
				return $err->error();
			}
			
			$this->class_settings["form_heading_title"] = "Review & Process Application<br /><br /><small id='regis-status' style='font-size:0.9em;'>CURRENT STATUS: ".strtoupper( get_select_option_value( array( "id" => $applicant["registration_status"], "function_name" => "get_registration_status" ) ) )."</small>";
			$this->class_settings[ 'form_submit_button' ] = "Update Application Status to Processed &rarr;";
			
			$fields = $this->table_fields;
			$status = "processed";
			
			switch( $this->class_settings["action_to_perform"] ){			
			case "payment_confirmed_application":
				$this->class_settings[ 'form_submit_button' ] = "Update Payment Status as Confirmed &rarr;";
				$status = "payment_confirmed";
			break;
			case "payment_failed_application":
				$this->class_settings[ 'form_submit_button' ] = "Update Payment Status as Failed &rarr;";
				$status = "payment_failed";
			break;
			case "approved_application":
				$this->class_settings[ 'form_submit_button' ] = "Update Membership Status as Approved &rarr;";
				$status = "approved";
			break;
			case "declined_application":
				$this->class_settings[ 'form_submit_button' ] = "Update Membership Status as Unapproved &rarr;";
				$status = "declined";
			break;
			case "registered_member_application":
				$this->class_settings[ 'form_submit_button' ] = "Update Membership Status to Active Registered Member &rarr;";
				$status = "registered_member";
			break;
			case "pending_renewal_application":
				$this->class_settings[ 'form_submit_button' ] = "Update Membership Status to Pending Renewal &rarr;";
				$status = "pending_renewal";
			break;
			case "retired_application":	
				$this->class_settings[ 'form_submit_button' ] = "Update Membership Status to Retired &rarr;";
				$status = "retired";
			break;
			default:
			break;
			}
			
			unset( $fields["additional_information"] );
			unset( $fields["attestation"] );
			unset( $fields["photograph"] );
			
			unset( $fields["recommender"] );
			unset( $fields["recommender_email"] );
			unset( $fields["recommender_phonenumber"] );
			
			unset( $fields["recommender_address"] );
			unset( $fields["recommender_organization"] );
			unset( $fields["recommender_status"] );
			unset( $fields["recommender_signature"] );
			unset( $fields["recommendation_date"] );
				
			unset( $fields["birth_certificate"] );
			unset( $fields["change_of_name"] );
			unset( $fields["nysc_certificate"] );
			unset( $fields["other_documents"] );
			unset( $fields["office_address"] );
			unset( $fields["office_email"] );
			unset( $fields["office_phone"] );
			unset( $fields["employer"] );
			unset( $fields["employer_address"] );
			unset( $fields["employer_date_of_employment"] );
			unset( $fields["employer_status"] );
			unset( $fields["employer_salary"] );
			unset( $fields["email"] );
			unset( $fields["title"] );
			unset( $fields["firstname"] );
			unset( $fields["lastname"] );
			unset( $fields["previous_names"] );
			unset( $fields["phonenumber"] );
			unset( $fields["birth_day"] );
			unset( $fields["sex"] );
			unset( $fields["country"] );
			unset( $fields["state"] );
			unset( $fields["city"] );
			unset( $fields["street_address"] );
			
			unset( $fields['number'] );
			unset( $fields['form_number'] );
			unset( $fields['file_number'] );
			unset( $fields['receipt_number'] );
			unset( $fields['submitted_complete_documents'] );
			
			unset( $fields['submitted_certificate'] );
			unset( $fields['application_batch'] );
			unset( $fields['application_qualification'] );
			unset( $fields['date_application_was_approved'] );
			unset( $fields['professional_qualification'] );
			unset( $fields['professional_qualification_date'] );
			unset( $fields['official_remark'] );
			
			unset( $fields['registration_status'] );
			
			foreach( $fields as $key => $val ){
				/*switch( $key ){
				case "password"	:
				case "confirmpassword":
				case "oldpassword":
				case "updated_primary_address":
				case "verified_email_address":
					
				break;
				}*/
				$this->class_settings[ 'hidden_records' ][ $val ] = 1;
			}
			$fname = "status-update-tabbed-form";
			
			$this->class_settings['form_action_todo'] = "save_process_and_review_apllication";
			$this->class_settings['form_values_important'][ $this->table_fields["registration_status"] ] = $status;
			
			$this->class_settings['form_values_replace'][ $this->table_fields["number"] ] = $applicant["serial_num"];
			$this->class_settings['form_values_replace'][ $this->table_fields["form_number"] ] = $applicant["serial_num"];
			$this->class_settings['form_values_replace'][ $this->table_fields["receipt_number"] ] = $applicant["serial_num"];
			//$this->class_settings['form_values_replace'][ $this->table_fields["file_number"] ] = $applicant["serial_num"];
			
			$this->class_settings['hidden_records_css'][ $this->table_fields["registration_status"] ] = 1;
			
			$return = $this->_generate_new_data_capture_form();			
		
			$this->class_settings[ 'member_id' ] = $applicant["id"];
			
			$edu = new cEducational_history();
			$edu->class_settings = $this->class_settings;
			$edu->class_settings["action_to_perform"] = 'get_educational_histories_view';
			$eh = $edu->educational_history();
			
			$edu = new cWork_history();
			$edu->class_settings = $this->class_settings;
			$edu->class_settings["action_to_perform"] = 'get_work_histories_view';
			$wh = $edu->work_history();
			
			$this->class_settings[ 'data' ]['educational'] = $eh['html'];
			$this->class_settings[ 'data' ]['work'] = $wh['html'];
			$this->class_settings[ 'data' ]['html'] = $return['html'];
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$fname );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new', 'activate_country_select_field' ),
				'html_replacement_selector' => '#form-content-area',
			);
			
			return $return;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->datatable_settings['custom_edit_button'] = $this->_get_html_view();
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$title = "All Applicants";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'unprocessed_applications':
			case 'processed_applications':
			case 'approved_applications':
			case 'unapproved_applications':
			case 'in_progress':
			case 'all_active_members':
			case 'renewal_in_progress':
			case 'recent_inductees':
			case 'expired_members':
				$title = ucwords( str_replace( "_", " ", $this->class_settings["action_to_perform"] ) );
			break;
			case 'unpaid':
				$title = "All Unpaid Applications";
			break;
			case 'display_all_records_full_view':
			break;
			}
			
			unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
			$where = "";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'unprocessed_applications':
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'application_recommended' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'application_complete' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'pending_payment' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'payment_confirmed' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'payment_failed' ) ";
			break;
			case 'processed_applications':
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'processed' ) ";
			break;
			case 'approved_applications':
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'approved' ) ";
			break;
			case 'unapproved_applications':
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'declined' ) ";
			break;
			case 'in_progress':
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'application_recommended' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'pending_application' ) ";
			break;
			case 'all_active_members':
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'registered_member' ) ";
			break;
			case 'recent_inductees':
				//
				$batch_no = get_active_batch_number();
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'registered_member' AND `".$this->table_name."`.`".$this->table_fields["application_batch"]."` = ".( $batch_no - 1 )."  ) ";
			break;
			case 'renewal_in_progress':
			case 'expired_members':
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'pending_renewal' ) ";
			break;
			case 'unpaid':
				$title = "All Unpaid Applications";
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'payment_failed' OR `".$this->table_name."`.`".$this->table_fields["registration_status"]."` = 'pending_payment' ) ";
			break;
			case 'display_all_records_full_view':
			break;
			}
			
			if( $where ){
				$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = $where;
			}
			
			$this->class_settings[ 'data' ]['title'] = $title;
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 0;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 5;
			$this->class_settings[ 'data' ]['col_2'] = 7;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _save_online_registration_form(){
			$return = $this->_save_changes();
			
			switch( $this->class_settings['action_to_perform'] ){
			case 'save_online_registration_other_info_form':
			case 'save_online_registration_passport_form':	
			case 'save_online_registration_attestation_form':
			case 'save_online_registration_form':
			case 'save_online_registration_employment_form':
			case 'save_online_registration_recommendation_form':
			case 'save_online_registration_document_form':
				//check for 
				if( isset( $return["typ"] ) && $return["typ"] == "saved" ){
					$return["html_replace"]  = '<i id="'.$this->table_name."-".str_replace("save_" , "", $this->class_settings['action_to_perform'] ).'" class="icon-check registration-status"></i>';
					$return["html_replace_selector"]  = "#".$this->table_name."-".str_replace("save_" , "", $this->class_settings['action_to_perform']);
					
					unset( $return["html"] );
					$return["status"]  = "new-status";
					
					$return['javascript_functions'] = array( 're_evaluate_registration_status' );
				}
			break;
			}
			
			switch( $this->class_settings['action_to_perform'] ){
			case 'save_online_registration_passport_form':
				$u = $this->_get_user_details();
				if( isset( $u["photograph"] ) && $u["photograph"]  ){
					//$this->class_settings["calling_page"]
					$return['html_replacement_one'] = '<img src="engine/' . $u["photograph"] . '" class="img-responsive" alt="" />';
					$return['html_replacement_selector_one'] = "#profile-img-passport";
				}
			break;
			case "save_process_and_review_apllication":
				$u = $this->_get_user_details();
				
				if( isset( $u["registration_status"] )  ){
					$return["html_replace"]  = "<small id='regis-status' style='font-size:0.9em;'>CURRENT STATUS: ".strtoupper( get_select_option_value( array( "id" => $u["registration_status"], "function_name" => "get_registration_status" ) ) )."</small>";
					$return["html_replace_selector"]  = "#regis-status";
					
					unset( $return["html"] );
					$return["status"]  = "new-status";
				}
				
				if( isset( $u["firstname"] ) && isset( $u["email"] ) && $u["email"] ){
					//update application status
					
					//send email to applicant 
					$this->class_settings['message_type'] = 1;
					
					$title = "LIBRARIANS REGISTRATION COUNCIL OF NIGERIAN: STATUS UPDATE";
					
					$info = 'APPLICANT NAME: <b>'.strtoupper($u[ 'firstname' ] . " " . $u[ 'lastname' ]).'</b><br />';
					$info .= 'PHONE NUMBER: <b>' . $u[ 'phonenumber' ] . '</b><br />';
					$info .= 'EMAIL: <b>' . $u[ 'email' ] . '</b><br />';
					$info .= '<hr />ACCOUNT NUMBER: <b>' . $u[ 'id' ] . "/" . $u[ 'number' ] . '</b><br />';
					$info .= 'LRCN REG. NUMBER: <b>' . $u[ 'registration_number' ] . '</b><br />';
					$info .= '<hr />MEMBERSHIP STATUS: <b>' . strtoupper( get_select_option_value( array( "id" => $u["registration_status"], "function_name" => "get_registration_status" ) ) ) . '</b><br />';
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
					
					$this->class_settings[ 'data' ] = array();
					$this->class_settings[ 'data' ]["full_name"] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
					$this->class_settings[ 'data' ]["email"] = $u["email"];
					$this->class_settings[ 'data' ]["title"] = $title;
					$this->class_settings[ 'data' ]["info"] = $info;
					$this->class_settings[ 'data' ]["mail_type"] = 4;
					
					$this->class_settings[ 'user_email' ] = $u["email"];
					$this->class_settings[ 'user_full_name' ] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
					$this->class_settings[ 'user_id' ] = $u[ 'id' ];
					
					$this->class_settings['message'] = $this->_get_html_view();
					$this->class_settings['subject'] = $title;
					$this->_send_email();
					
				}
			break;
			case 'save_applicant_recommendation':
				$u = $this->_get_user_details();
				
				if( isset( $u["recommender"] ) && isset( $u["recommender_email"] ) && $u["recommender"] && $u["recommender_email"] && isset( $u["recommender_status"] ) && $u["recommender_status"] ){
					//update application status
					
					//send email to applicant 
					$this->class_settings['message_type'] = 1;
					
					$title = "LIBRARIANS REGISTRATION COUNCIL OF NIGERIAN RECOMMENDATION CONFIRMED BY " . strtoupper($u["recommender"]);
					
					$info = 'APPLICANT NAME: <b>'.strtoupper($u[ 'firstname' ] . " " . $u[ 'lastname' ]).'</b><br />';
					$info .= 'PHONE NUMBER: <b>' . $u[ 'phonenumber' ] . '</b><br />';
					$info .= 'EMAIL: <b>' . $u[ 'email' ] . '</b><br />';
						
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
					
					$this->class_settings[ 'data' ] = array();
					$this->class_settings[ 'data' ]["full_name"] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
					$this->class_settings[ 'data' ]["email"] = $u["email"];
					$this->class_settings[ 'data' ]["title"] = $title;
					$this->class_settings[ 'data' ]["info"] = $info;
					$this->class_settings[ 'data' ]["mail_type"] = 2;
					
					$this->class_settings[ 'user_email' ] = $u["email"];
					$this->class_settings[ 'user_full_name' ] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
					$this->class_settings[ 'user_id' ] = $u[ 'id' ];
					
					$this->class_settings['message'] = $this->_get_html_view();
					$this->class_settings['subject'] = $title;
					$this->_send_email();
					
					$this->class_settings[ 'data' ]["skip_style"] = 1;
					
					$this->class_settings[ 'data' ]["full_name"] = $u[ 'firstname' ] . " " . $u[ 'lastname' ];
					$this->class_settings[ 'data' ]["email"] = $u["email"];
					$this->class_settings[ 'data' ]["title"] = $title;
					$this->class_settings[ 'data' ]["info"] = $info;
					$this->class_settings[ 'data' ]["mail_type"] = 2;
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
					$return['html_replacement'] = "<h4>The following Electronic Request has been sent to the applicant confirming your recommendation</h4>".$this->_get_html_view();
					$return['html_replacement_selector'] = "#registration-form-container";
					
					unset( $return["html"] );
					$return["status"]  = "new-status";
					
					$return['javascript_functions'][] = 'set_function_click_event';
					
					//display message
				}
			break;
			case 'save_online_registration_recommendation_form':
				$u = $this->_get_user_details();
				
				if( isset( $u["recommender"] ) && isset( $u["recommender_email"] ) && $u["recommender_email"] ){
					//send recommendation email message
					//
					$this->class_settings['message_type'] = 1;
						
					$title = "LIBRARIANS REGISTRATION COUNCIL OF NIGERIAN RECOMMENDATION REQUEST FROM " . strtoupper($this->class_settings[ 'user_full_name' ]);
					
					$info = 'FULL NAME: <b>'.strtoupper($this->class_settings[ 'user_full_name' ]).'</b><br />';
					$info .= 'PHONE NUMBER: <b>' . $u[ 'phonenumber' ] . '</b><br />';
					$info .= 'EMAIL: <b>' . $u[ 'email' ] . '</b><br />';
						
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
					
					$this->class_settings[ 'data' ] = array();
					$this->class_settings[ 'data' ]["full_name"] = $u["recommender"];
					$this->class_settings[ 'data' ]["email"] = $u["recommender_email"];
					$this->class_settings[ 'data' ]["title"] = $title;
					$this->class_settings[ 'data' ]["info"] = $info;
					$this->class_settings[ 'data' ]["applicant_id"] = $this->class_settings["user_id"];
					$this->class_settings[ 'data' ]["applicant_name"] = strtoupper($this->class_settings[ 'user_full_name' ]);
					$this->class_settings[ 'data' ]["mail_type"] = 1;
					
					$this->class_settings[ 'user_email' ] = $u["recommender_email"];
					
					$this->class_settings['message'] = $this->_get_html_view();
					$this->class_settings['subject'] = $title;
					$this->_send_email();
					
					$this->class_settings[ 'data' ]["skip_style"] = 1;
					
					$this->class_settings[ 'data' ]["full_name"] = $u["recommender"];
					$this->class_settings[ 'data' ]["email"] = $u["recommender_email"];
					$this->class_settings[ 'data' ]["title"] = $title;
					$this->class_settings[ 'data' ]["info"] = $info;
					$this->class_settings[ 'data' ]["applicant_id"] = $this->class_settings["user_id"];
					$this->class_settings[ 'data' ]["applicant_name"] = strtoupper($this->class_settings[ 'user_full_name' ]);
					$this->class_settings[ 'data' ]["mail_type"] = 1;
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
					$return['html_replacement'] = "<h4>The following Electronic Request has been sent to your recommender</h4>".$this->_get_html_view();
					$return['html_replacement_selector'] = "#form-content-area";
					
					$return['javascript_functions'][] = 'set_function_click_event';
					
				}
			break;
			}
			
			return $return;
		}
		
		private function _online_registration_passport_form(){
			
			switch($this->class_settings['action_to_perform']){
			case 'online_registration_recommendation_form':
				$u = $this->_get_user_details();
				$status = 0;
				$count = 0;
				$keys = array( "firstname", "lastname", "email", "title", "phonenumber", "birth_day", "sex", "country", "state", "city", "street_address", "photograph", "nysc_certificate", "birth_certificate", "attestation" );
				foreach( $keys as $key ){
					if( isset( $u[ $key ] ) && $u[ $key ] ){
						++$count;
					}
				}
				if( $count == count($keys) )$status = 1;
				
				//GET EDUCATION HISTORY
				$this->class_settings[ 'member_id' ] = $this->class_settings["user_id"];
				
				$edu = new cEducational_history();
				$edu->class_settings = $this->class_settings;
				$edu->class_settings["action_to_perform"] = 'get_educational_histories';
				$eh = $edu->educational_history();
				if( ( is_array( $eh ) && ! empty( $eh ) ) ){
					$edu = new cWork_history();
					$edu->class_settings = $this->class_settings;
					$edu->class_settings["action_to_perform"] = 'get_work_histories';
					$wh = $edu->work_history();
					
					if( ! ( is_array( $wh ) && ! empty( $wh ) ) ){
						$status = 0;
					}
				}else{
					$status = 0;
				}
				
				if( ! $status ){
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/recommendation-error.php' );
					
					$t = $this->_get_html_view();
					
					return array(
						'html_replacement' => $t,
						'method_executed' => $this->class_settings['action_to_perform'],
						'status' => 'new-status',
						'html_replacement_selector' => '#form-content-area',
					);
				}else{
					//Already Recommended
					if( isset( $u["recommender"] ) && isset( $u["recommender_email"] ) && $u["recommender"] && $u["recommender_email"] && isset( $u["recommender_status"] ) && $u["recommender_status"] ){
						$this->class_settings[ 'data' ]["name"] = $u["recommender"];
						$this->class_settings[ 'data' ]["email"] = $u["recommender_email"];
						$this->class_settings[ 'data' ]["status"] = $u["recommender_status"];
						
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/already-recommended-notice.php' );
						
						return array(
							'html_replacement' => $this->_get_html_view(),
							'method_executed' => $this->class_settings['action_to_perform'],
							'status' => 'new-status',
							'html_replacement_selector' => '#form-content-area',
						);
					}
				}
			break;
			case 'online_registration_payment_form':
				//PAYMENT
				$u = $this->_get_user_details();
				
				if( isset( $u["registration_status"] ) ){
					switch( $u["registration_status"] ){
					case 'processed':
					case 'approved':
					case 'registered_member':
					case 'retired':
					case 'payment_confirmed':
					case 'pending_renewal':
					case 'declined':
						$this->class_settings[ 'data' ]['paid'] = 1;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/online-registration-payment.php' );
					break;
					default:
						$this->class_settings[ 'data' ]['registration_fee'] = 5000;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/online-registration-payment.php' );
					break;
					}
				}
				
				return array(
					'html_replacement' => $this->_get_html_view(),
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					//'javascript_functions' => $js_array,
					'html_replacement_selector' => '#form-content-area',
				);
			break;
			}
			
			unset( $_SESSION["admin_page"] );
			$_SESSION["add_engine_page"] = 1;
			
			$form = $this->_user_registration_form();
			$js_array = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			
			unset( $_SESSION["add_engine_page"] );
			
			switch($this->class_settings['action_to_perform']){
			case 'online_registration_attestation_form':
				
				$this->class_settings[ 'data' ]["user_details"] = $this->_get_user_details();
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/attestation.php' );
				
				$t = $this->_get_html_view();
				
				$this->class_settings[ 'data' ] = array();
				$this->class_settings[ 'data' ]['top_title'] = $t;
			break;
			case 'online_registration_recommendation_form':
				
				$this->class_settings[ 'data' ]["user_details"] = $this->_get_user_details();
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/recommendation.php' );
				
				$t = $this->_get_html_view();
				
				$this->class_settings[ 'data' ] = array();
				$this->class_settings[ 'data' ]['top_title'] = $t;
			break;
			}
			
			$this->class_settings[ 'data' ]['html'] = $form['html'];
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/online-registration-form.php' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js_array,
				'html_replacement_selector' => '#form-content-area',
			);
		}
		
		private function _online_registration_form(){
			$_SESSION["add_engine_page"] = 1;
			
			$form = $this->_user_registration_form();
			unset( $_SESSION["add_engine_page"] );
			
			unset($this->class_settings['where']);
			$user_details = $this->_get_user_details();
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'update_bio_data_form':
				$this->class_settings[ 'data' ]['html'] = $form['html'];
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/update-bio-data-form' );
				
				$js_array = array( 'set_function_click_event', 'prepare_new_record_form_new', 'activate_country_select_field' );
			break;
			default:
				//GET EDUCATION HISTORY
				$this->class_settings[ 'member_id' ] = $this->class_settings["user_id"];
				
				$edu = new cEducational_history();
				$edu->class_settings = $this->class_settings;
				$edu->class_settings["action_to_perform"] = 'get_educational_histories';
				$this->class_settings[ 'data' ]['education_history'] = $edu->educational_history();
				
				$edu = new cWork_history();
				$edu->class_settings = $this->class_settings;
				$edu->class_settings["action_to_perform"] = 'get_work_histories';
				$this->class_settings[ 'data' ]['work_history'] = $edu->work_history();
				
				$this->class_settings[ 'data' ]['user_details'] = $user_details;
				$this->class_settings[ 'data' ]['html'] = $form['html'];
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/online-registration-form' );
				$js_array = array( 'set_function_click_event', 'prepare_new_record_form_new', 're_evaluate_registration_status', 'activate_country_select_field' );
			break;
			}
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js_array,
				'html_replacement_selector' => '#dash-board-main-content-area',
			);
		}
		
		private function _save_update_profile(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$return = $this->_save_changes();
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/user-info.php' );
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
			$form = $this->_user_registration_form();
			
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
				'javascript_functions' => array( 'prepare_new_record_form_new', 'activate_country_select_field' ) 
			);
		}
		
		private function _save_site_edit(){
			$check_email = 0;
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'save_edit_teller_form':
			break;
			case 'save_new_teller_form':
				$password = generatePassword( 8, 1, 0, 1, 0);
				$_POST[ $this->table_fields["oldpassword"] ] = $password;
				$_POST[ $this->table_fields["password"] ] = $password;
				$_POST[ $this->table_fields["confirmpassword"] ] = $password;
				
				$check_email = 1;
			break;
			}
			
			if( $check_email && isset( $this->table_fields[ 'email' ] ) && $this->table_fields[ 'email' ] ){
				if( isset( $_POST[ $this->table_fields[ 'email' ] ] ) && $_POST[ $this->table_fields[ 'email' ] ] ){
					$email = $_POST[ $this->table_fields[ 'email' ] ];
					$hashed_email = md5( $_POST[ $this->table_fields[ 'email' ] ] );
					
					$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE MD5( `".$this->table_name."`.`".$this->table_fields[ 'email' ]."` ) = '" . $hashed_email . "' AND `" . $this->table_name . "`.`record_status`='1' ";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 0,
						'tables' => array( $this->table_name ),
					);
					$sql_result = execute_sql_query( $query_settings );
					
					if( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ){
						//DUPLICATE EMAIL ADDRESS
						$err = new cError(000102);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'cSite_users.php';
						$err->method_in_class_that_triggered_error = '_save_registration';
						$err->additional_details_of_error = 'Duplicate Email Address';
						
						$returning_html_data = $err->error();
						$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
						$returning_html_data['status'] = 'saved-form-data';
						
						return $returning_html_data;
					}
				}
			}
			
			$returning_html_data = $this->_save_changes();
			
			if( isset( $returning_html_data['typ'] ) && $returning_html_data['typ'] == 'saved' ){
				
				switch ( $this->class_settings['action_to_perform'] ){
				case 'save_edit_teller_form':
				break;
				case 'save_new_teller_form':
					//Send Welcome Email
					$this->class_settings['message_type'] = 2;
					
					$this->class_settings[ 'user_email' ] = isset( $_POST[ $this->table_fields[ 'email' ] ] )?$_POST[ $this->table_fields[ 'email' ] ]:"";
					$this->class_settings[ 'user_full_name' ] = isset( $_POST[ $this->table_fields[ 'firstname' ] ] )?( $_POST[ $this->table_fields[ 'firstname' ] ] . " " . $_POST[ $this->table_fields[ 'lastname' ] ] ):"";
					
					$this->class_settings[ 'user_id' ] = $returning_html_data['saved_record_id'];
				
					$info = 'DEFAULT PASSWORD: <b>'.$password.'</b><br />';
					$title = "TELLER REGISTRATION DETAILS";
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
					
					$this->class_settings[ 'data' ]["title"] = $title;
					$this->class_settings[ 'data' ]["info"] = $info;
					$this->class_settings[ 'data' ]["type"] = "teller";
					$this->class_settings[ 'data' ]["message"] = " ";
					
					$this->class_settings['message'] = $this->_get_html_view();
					$this->class_settings['subject'] = $title;
					
					$this->_send_email();
					
				break;
				}
				unset( $returning_html_data['html'] );
				
				switch ( $this->class_settings['action_to_perform'] ){
				case 'save_edit_teller_form':
				case 'save_new_teller_form':
					$this->class_settings['action_to_perform'] = 'teller_registration_form_1';
					$return = $this->_user_registration_form();
					
					$returning_html_data['html_replacement_selector'] = "#data-entry-form-container";
					$returning_html_data['html_replacement' ] = $return["html"];
					$returning_html_data['status' ] = "new-status";
					
					$returning_html_data['javascript_functions' ] = array( 'prepare_new_record_form_new' );
					
				break;
				}
			}
			
			return $returning_html_data;
		}
		
		private function _edit_teller_details_form(){
			$this->class_settings['form_action_todo'] = 'save_edit_teller_form';
			
			$form = $this->_user_registration_form();
			
			$js_func = array( 'prepare_new_record_form_new' );
			$js_func[] = 'activate_country_select_field';
			
			return array(
				'html_replacement_selector' => "#data-entry-form-container",
				'html_replacement' => $form['html'],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js_func,
			);
		}
		
		private function _user_registration_form(){
			$fields = $this->table_fields;
			$this->class_settings[ 'do_not_show_headings'] = 1;
			
			//$this->class_settings['hidden_records'][ $this->table_fields["sex"] ] = 1;
			$this->class_settings['hidden_records_css'] = array();
			
			unset( $_GET );
			
			$js_func = array( 'prepare_new_record_form_new' );
			
			$selector = "#main-view";
			switch($this->class_settings['action_to_perform']){
			case 'display_my_profile_manager':
				
				unset( $this->class_settings['hidden_records'][ $this->table_fields["photograph"] ] );
			break;
			case 'online_registration_payment_form':
			break;
			case 'verify_membership':
				unset( $fields["email"] );
				//unset( $fields["title"] );
				unset( $fields["firstname"] );
				unset( $fields["lastname"] );
				//unset( $fields["previous_names"] );
				unset( $fields["phonenumber"] );
				
				$this->class_settings[ 'form_submit_button' ] = 'Search for Librarians &rarr;';
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
			break;
			case 'online_registration_other_info_form':
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				
				$this->class_settings[ 'form_submit_button' ] = 'Save & Proceed to Next Stage &rarr;';
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				$this->class_settings["read_only"] = 1;
				
				unset( $fields["additional_information"] );
			break;
			case 'online_registration_attestation_form':
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				
				unset( $fields["attestation"] );
				
				$this->class_settings[ 'form_submit_button' ] = 'Save & Proceed to Next Stage &rarr;';
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				$this->class_settings["read_only"] = 1;
			break;
			case 'online_registration_passport_form':
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				
				unset( $fields["photograph"] );
				
				$this->class_settings[ 'form_submit_button' ] = 'Save & Proceed to Next Stage &rarr;';
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				$this->class_settings["read_only"] = 1;
			break;
			case 'online_registration_recommendation_form':
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				
				unset( $fields["recommender"] );
				unset( $fields["recommender_email"] );
				unset( $fields["recommender_phonenumber"] );
				
				$this->class_settings[ 'form_submit_button' ] = 'Send Recommendation Request Email &rarr;';
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				$this->class_settings["read_only"] = 1;
			break;
			case 'applicant_recommendation':
				unset( $_GET );
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				
				unset( $fields["recommender"] );
				unset( $fields["recommender_email"] );
				unset( $fields["recommender_phonenumber"] );
				unset( $fields["recommender_address"] );
				unset( $fields["recommender_organization"] );
				unset( $fields["recommender_status"] );
				unset( $fields["recommender_signature"] );
				unset( $fields["recommendation_date"] );
				
				unset( $fields["registration_status"] );
				
				$this->class_settings[ 'hidden_records_css' ][ $this->table_fields["recommendation_date"] ] = 1;
				$this->class_settings[ 'form_values_important' ][ $this->table_fields["recommendation_date"] ] = date("U");
				
				$this->class_settings[ 'hidden_records_css' ][ $this->table_fields["registration_status"] ] = 1;
				$this->class_settings[ 'form_values_important' ][ $this->table_fields["registration_status"] ] = 'application_recommended';
				
				$this->class_settings[ 'form_submit_button' ] = 'Recommendation Applicant for LRCN Membership &rarr;';
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				$this->class_settings["read_only"] = 1;
			break;
			case 'online_registration_document_form':
				
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				
				unset( $fields["birth_certificate"] );
				unset( $fields["change_of_name"] );
				unset( $fields["nysc_certificate"] );
				unset( $fields["other_documents"] );
				
				$this->class_settings[ 'form_submit_button' ] = 'Save & Proceed to Next Stage &rarr;';
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				$this->class_settings["read_only"] = 1;
			break;
			case 'online_registration_employment_form':
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				
				unset( $fields["office_address"] );
				unset( $fields["office_email"] );
				unset( $fields["office_phone"] );
				unset( $fields["employer"] );
				unset( $fields["employer_address"] );
				unset( $fields["employer_date_of_employment"] );
				unset( $fields["employer_status"] );
				unset( $fields["employer_salary"] );
				
				$this->class_settings[ 'form_submit_button' ] = 'Save & Proceed to Next Stage &rarr;';
				
				//unset( $fields["additional_information"] );
				//unset( $fields["attestation"] );
				$this->class_settings["read_only"] = 1;
			break;
			case "update_employment_details_form":
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				
				unset( $fields["office_address"] );
				unset( $fields["office_email"] );
				unset( $fields["office_phone"] );
				unset( $fields["employer"] );
				unset( $fields["employer_address"] );
				unset( $fields["employer_date_of_employment"] );
				unset( $fields["employer_status"] );
				unset( $fields["employer_salary"] );
			break;
			case "update_additional_documents_form":
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				
				unset( $fields["other_documents"] );
				unset( $fields["additional_information"] );
			break;
			case 'update_bio_data_form':
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				
				unset( $fields["email"] );
				//unset( $fields["title"] );
				//unset( $fields["firstname"] );
				//unset( $fields["lastname"] );
				//unset( $fields["previous_names"] );
				unset( $fields["phonenumber"] );
				//unset( $fields["birth_day"] );
				//unset( $fields["sex"] );
				unset( $fields["country"] );
				unset( $fields["state"] );
				unset( $fields["city"] );
				unset( $fields["street_address"] );
				unset( $fields["photograph"] );
				
			break;
			case 'online_registration_form':
				$this->class_settings['form_action_todo'] = 'save_'.$this->class_settings['action_to_perform'];
				
				$_POST["id"] = $this->class_settings["user_id"];
				$_POST["mod"] = "edit-".md5($this->table_name);
				
				unset( $fields["email"] );
				unset( $fields["title"] );
				unset( $fields["firstname"] );
				unset( $fields["lastname"] );
				unset( $fields["previous_names"] );
				unset( $fields["phonenumber"] );
				unset( $fields["birth_day"] );
				unset( $fields["sex"] );
				unset( $fields["country"] );
				unset( $fields["state"] );
				unset( $fields["city"] );
				unset( $fields["street_address"] );
				
				$this->class_settings["read_only"] = 1;
			break;
			default:
				$this->class_settings[ 'form_submit_button' ] = 'Register';
			break;
			}
			$read_only = 0;
			if( isset( $this->class_settings["read_only"] ) && $this->class_settings["read_only"] ){
				$u = $this->_get_user_details();
				if( isset( $u["recommender"] ) && isset( $u["recommender_email"] ) && $u["recommender"] && $u["recommender_email"] && isset( $u["recommender_status"] ) && $u["recommender_status"] ){
					//read only form
					$read_only = 1;
				}
			}
			
			if( $read_only ){
				$fx = $this->table_name;
				$fs = $fx();
				foreach( $this->table_fields as $k => $v ){
					if( isset( $fs[ $v ] ) )
						$tb_fields[ $k ] = $fs[ $v ];
				}
				
				foreach( $fields as $ki => $vi ){
					if( isset( $u[$ki] ) )unset( $u[$ki] );
				}
				
				unset($u["postal_code"]);
				unset($u["id"]);
				unset($u["role"]);
				unset($u["modification_date"]);
				unset($u["creation_date"]);
				unset($u["serial_num"]);
				
				$this->class_settings[ 'data' ]['pagepointer'] = $this->class_settings["calling_page"];
				$this->class_settings[ 'data' ]['applicant_data'] = $u;
				$this->class_settings[ 'data' ]['applicant_fields'] = $tb_fields;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/read-only-form-values' );
				
				$form["html"] = $this->_get_html_view();
				
			}else{
				
				foreach( $fields as $key => $val )
					$this->class_settings['hidden_records'][ $val ] = 1;
				
				$form = $this->_generate_new_data_capture_form();
			}
			
			switch($this->class_settings['action_to_perform']){
			case 'online_registration_recommendation_form':
			case 'online_registration_form':
			case 'online_registration_payment_form':
			case 'teller_registration_form_1':
			case 'display_my_profile_manager':
			case 'verify_membership':
			case 'online_registration_passport_form':
			case 'online_registration_employment_form':
			case 'online_registration_attestation_form':
			case 'online_registration_other_info_form':
			case 'online_registration_document_form':
			case 'applicant_recommendation':
			case 'update_bio_data_form':
			case "update_employment_details_form":
			case "update_additional_documents_form":
				return $form;
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.get_file_name_from_action( $this->class_settings['action_to_perform'] ) );
			$this->class_settings[ 'data' ]['form_data'] = $form['html'];
			
			$returning_html_data = $this->_get_html_view();
			
			$js_func[] = 'activate_country_select_field';
			
			return array(
				'html_replacement_selector' => $selector,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js_func,
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
		
		private function _get_general_settings(){
			return get_from_cached( array( 'cache_key' => 'general_settings' ) );
		}
		
		private function _get_registration_form(){
			
			$general_settings_data = $this->_get_general_settings();
			
			$fields = $this->table_fields;
				
			switch( $this->class_settings["action_to_perform"] ){
			default:
				$this->class_settings['form_heading_title'] = '';
				
				$this->class_settings['agreement_text'] = SITE_USERS_REGISTER_GET_STARTED;
				
					
				if( isset( $general_settings_data[ $this->table_name ][ 'TERMS OF SERVICE URL' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'TERMS OF SERVICE URL' ][ 'default' ] ){
					//$this->class_settings['agreement_text'] .= ' <a href="' . $general_settings_data[ $this->table_name ][ 'TERMS OF SERVICE URL' ][ 'default' ] . '" target="_blank" title="' . SITE_USERS_TERMS_OF_SERVICE . '">' . SITE_USERS_TERMS_OF_SERVICE .'</a>';
				}
				
				if( isset( $general_settings_data[ $this->table_name ][ 'PRIVACY POLICY URL' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'PRIVACY POLICY URL' ][ 'default' ] ){
					//$this->class_settings['agreement_text'] .= ' <a href="' . $general_settings_data[ $this->table_name ][ 'PRIVACY POLICY URL' ][ 'default' ] . '" target="_blank" title="' . SITE_USERS_PRIVACY_POLICY . '">' . SITE_USERS_PRIVACY_POLICY .'</a>';
				}
				
				if( isset( $general_settings_data[ $this->table_name ][ 'SHOW RECAPTCHA' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW RECAPTCHA' ][ 'default' ] == 'TRUE' ){
					$this->class_settings['show_recaptcha'] = 1;
				}
				//print_r($general_settings_data);
				
				$this->class_settings['unset_hidden_records_css'] = array(
					'country' => $this->table_fields['country'],
					'state' => $this->table_fields['state'],
					'city' => $this->table_fields['city'],
					'street_address' => $this->table_fields['street_address'],
					'postal_code' => $this->table_fields['postal_code'],
				);
				if( isset( $general_settings_data[ $this->table_name ][ 'SHOW ADDRESS FIELDS' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW ADDRESS FIELDS' ][ 'default' ] == 'TRUE' ){
					$this->class_settings['unset_hidden_records_css'] = array(
						'country' => $this->table_fields['country'],
						'state' => $this->table_fields['state'],
						'city' => $this->table_fields['city'],
						'street_address' => $this->table_fields['street_address'],
						'postal_code' => $this->table_fields['postal_code'],
					);
				}
				
				
				if( isset( $general_settings_data[ $this->table_name ][ 'SHOW NAME FIELDS' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW NAME FIELDS' ][ 'default' ] != 'TRUE' ){
					$this->class_settings['hidden_records'][$this->table_fields['firstname']] = 1;
					$this->class_settings['hidden_records'][$this->table_fields['lastname']] = 1;
				}
				
				if( isset( $general_settings_data[ $this->table_name ][ 'SHOW PHONE NUMBER FIELD' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW PHONE NUMBER FIELD' ][ 'default' ] != 'TRUE' ){
					$this->class_settings['hidden_records'][$this->table_fields['phonenumber']] = 1;
				}
				/*
				$this->class_settings['hidden_records'][$this->table_fields['birthday']] = 1;
				if( isset( $general_settings_data[ $this->table_name ][ 'SHOW BIRTHDAY FIELD' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW BIRTHDAY FIELD' ][ 'default' ] != 'TRUE' ){
					$this->class_settings['hidden_records'][$this->table_fields['birthday']] = 1;
				}
				*/
				//$this->class_settings['hidden_records'][$this->table_fields['sex']] = 1;
				if( isset( $general_settings_data[ $this->table_name ][ 'SHOW SEX FIELD' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW SEX FIELD' ][ 'default' ] != 'TRUE' ){
					$this->class_settings['hidden_records'][$this->table_fields['sex']] = 1;
				}
				
				if( defined( 'SELECTED_COUNTRY_ID' ) && SELECTED_COUNTRY_ID != '1' ){
					$this->class_settings['form_values_important'][ $this->table_fields['address_fields']['country'] ] = SELECTED_COUNTRY_ID;
				}
				   
				//$this->class_settings['hidden_records'][ $this->table_fields["merchant_ids"] ] = 1;
				unset( $fields["email"] );
				unset( $fields["title"] );
				unset( $fields["firstname"] );
				unset( $fields["lastname"] );
				unset( $fields["previous_names"] );
				unset( $fields["phonenumber"] );
				unset( $fields["birth_day"] );
				unset( $fields["sex"] );
				unset( $fields["country"] );
				unset( $fields["state"] );
				unset( $fields["city"] );
				unset( $fields["street_address"] );
				unset( $fields["registration_status"] );
				unset( $fields['application_batch'] );
				
			break;
			}
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'site_users_guest_registration_form':
				$this->class_settings['form_heading_title'] = 'Guest Checkout';
				$this->class_settings['hidden_records'][$this->table_fields['sex']] = 1;
				$this->class_settings['hidden_records'][$this->table_fields['birthday']] = 1;
				//$this->class_settings['unset_hidden_records_css'] = $this->table_fields['address_fields'];
				$this->class_settings['show_recaptcha'] = 0;
			break;
			}
			
			$this->class_settings['hidden_records_css'][ $this->table_fields['application_batch'] ] = 1;
			$this->class_settings['form_values_important'][ $this->table_fields['application_batch'] ] = get_active_batch_number();
			
			$this->class_settings['hidden_records_css'][ $this->table_fields['registration_status'] ] = 1;
			$this->class_settings['form_values_important'][ $this->table_fields['registration_status'] ] = $this->default_application_status;
			
			foreach( $fields as $key => $val )
				$this->class_settings['hidden_records'][ $val ] = 1;
				
            $this->class_settings[ 'form_submit_button' ] = 'Register & Proceed to Next Step &rarr;';
            
			return $this->_generate_new_data_capture_form();
		}
		
		private function _site_registration(){
			$general_settings_data = $this->_get_general_settings();
			
			$applicant = array();
			$tb_fields = array();
			
			$work_history = "";
			
			$file = "sign-up";
			switch( $this->class_settings["action_to_perform"] ){
			case 'verify_membership':
				$file = "verify-membership";
				$a = array();
				if( isset( $_GET["m"] ) && $_GET["m"] && isset( $_GET["id"] ) && $_GET["id"] ){
					$a = get_users_details( array( "id" => $_GET["id"] ) );
				}
				$go = 0;
				$e = 0;
				
				if( isset( $a["id"] ) && isset( $a["recommender_email"] ) ){
					if( md5( $a["recommender_email"] ) == $_GET["m"] ){
						$go = 1;
						$this->class_settings["user_id"] = $a["id"];
					}else{
						//invalid recommender email
						$e = 1;
					}
				}
				$form_data = $this->_user_registration_form();
				
			break;
			case 'applicant_recommendation':
				$file = "recommend-applicant";
				$a = array();
				if( isset( $_GET["m"] ) && $_GET["m"] && isset( $_GET["id"] ) && $_GET["id"] ){
					$a = get_users_details( array( "id" => $_GET["id"] ) );
				}
				$go = 0;
				$e = 0;
				
				if( isset( $a["id"] ) && isset( $a["recommender_email"] ) ){
					if( md5( $a["recommender_email"] ) == $_GET["m"] ){
						$go = 1;
						$this->class_settings["user_id"] = $a["id"];
					}else{
						//invalid recommender email
						$e = 1;
					}
				}
				
				if( $go ){
					$applicant = $a;
					$fx = $this->table_name;
					$fs = $fx();
					foreach( $this->table_fields as $k => $v ){
						if( isset( $fs[ $v ] ) )
							$tb_fields[ $k ] = $fs[ $v ];
					}
					
					if( isset( $a["recommender"] ) && isset( $a["recommender_email"] ) && $a["recommender"] && $a["recommender_email"] && isset( $a["recommender_status"] ) && $a["recommender_status"] ){
						$this->class_settings[ 'data' ]["name"] = $a["recommender"];
						$this->class_settings[ 'data' ]["email"] = $a["recommender_email"];
						$this->class_settings[ 'data' ]["status"] = $a["recommender_status"];
						
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/already-recommended-notice.php' );
						$form_data["html"] = $this->_get_html_view();
					}else{
						$form_data = $this->_user_registration_form();
					}
					
					$this->class_settings["member_id"] = $this->class_settings["user_id"];
					$this->class_settings["no_delete"] = 1;
					
					$edu = new cEducational_history();
					$edu->class_settings = $this->class_settings;
					$edu->class_settings["action_to_perform"] = 'get_educational_histories_view';
					$eh = $edu->educational_history();
					
					$edu = new cWork_history();
					$edu->class_settings = $this->class_settings;
					$edu->class_settings["action_to_perform"] = 'get_work_histories_view';
					$wh = $edu->work_history();
					
					
					$work_history = "<h5>Educational History</h5><hr />".$eh["html"]."<h5>Work History</h5><hr />".$wh["html"];
				}else{
					$this->class_settings[ 'data' ]["type"] = $e;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/invalid-recommendation-url-error.php' );
					$form_data["html"] = $this->_get_html_view();
				}
				
			break;
			default:
				$this->class_settings['form_action'] = '?action='.$this->table_name.'&todo=save_registration';
				$form_data = $this->_get_registration_form();
			break;
			}
			
			
			$this->class_settings[ 'bundle-name' ] = $this->class_settings[ 'action_to_perform' ];
			
			$this->class_settings[ 'js' ][] = 'my_js/custom/registration.js';
			$this->class_settings[ 'js' ][] = 'my_js/custom/authentication.js';
            $this->class_settings[ 'js' ][] = 'my_js/custom/website.js';
			$this->class_settings[ 'css' ][] = 'css/template-1/site_registration.css';
			
            $serviceURL = array();
            if( isset( $general_settings_data[ $this->table_name ]['ALLOW GMAIL SIGNIN']['default'] ) ){
                //get google url
                $serviceURL['ALLOW GMAIL SIGNIN'] = $this->google( array( 'type' => "getURL" ) );
            }
            
            if( isset( $general_settings_data[ $this->table_name ]['ALLOW FACEBOOK SIGNIN']['default'] ) ){
                //get facebook url
                $serviceURL['ALLOW FACEBOOK SIGNIN'] = $this->facebook( array( 'type' => "getURL" ) );
            }
            
			$website = new cWebsite();
			$website->class_settings = $this->class_settings;
			$website->class_settings[ 'action_to_perform' ] = 'setup_website';
			$returning = $website->website();
			
			$this->class_settings = $website->class_settings;
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array(
				'html' => $form_data['html'],
				'table_fields' => $this->table_fields,
				'general_settings' => $general_settings_data[ $this->table_name ],
                'service_url' => $serviceURL,
                'applicant_data' => $applicant,
                'applicant_fields' => $tb_fields,
                'work_history' => $work_history,
                'pagepointer' => $this->class_settings['calling_page'],
			);
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$file );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning['html'] = $script_compiler->script_compiler();
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
		}
		
		private function _get_authentication_form(){
			
			foreach( $this->table_fields as $key => $val ){
				$this->class_settings['hidden_records'][ $val ] = 1;
			}
			
			unset( $this->class_settings['hidden_records'][ $this->table_fields["email"] ] );
			unset( $this->class_settings['hidden_records'][ $this->table_fields["password"] ] );
			
			switch( $this->class_settings['action_to_perform'] ){
			case 'site_users_authentication_form_only':
				$this->class_settings['form_heading_title'] = '';
				
				if( ! isset( $this->class_settings[ 'form_action' ] ) )
					$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=authenticate_user';
								
				$this->class_settings[ 'form_submit_button' ] = 'Sign In';
			break;
			case 'site_users_authentication':
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=authenticate_user';
				
				$this->class_settings[ 'forgot_password_link' ] = '<a href="?action='.$this->table_name.'&todo=site_users_reset_password" class="special" rel="external">Forgot your password?</a>';
					
				$this->class_settings[ 'form_submit_button' ] = 'Sign In';
			break;
			case 'site_users_reset_password':
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=reset_user_password';
				
				$this->class_settings[ 'forgot_password_link' ] = '<a href="?action='.$this->table_name.'&todo=site_users_authentication" class="special" rel="external">Sign in</a>';
				
				$this->class_settings['hidden_records']['site_users007'] = 1;
				
				$this->class_settings[ 'form_submit_button' ] = 'Reset Password';
			break;
			}
			
			return $this->_generate_new_data_capture_form();
		}
		
		private function _site_users_authentication(){
			
			$general_settings_data = $this->_get_general_settings();
			
			$verification_data = $this->_verify_email_address();
			
			if( is_array($verification_data) && isset( $verification_data['typ'] ) && $verification_data['typ'] == 'authenticated' && isset( $verification_data['redirect_url'] ) ){
				header( 'location: ' . $verification_data['redirect_url'] );
				exit;
			}
			
			$this->class_settings['form_heading_title'] = '';
			
			//$this->class_settings['form_class'] = 'skip-validation';
			$form_data = $this->_get_authentication_form();
			
			$this->class_settings[ 'bundle-name' ] = $this->class_settings[ 'action_to_perform' ];
			
			$this->class_settings[ 'js' ][] = 'my_js/custom/authentication.js';
			$this->class_settings[ 'js' ][] = 'my_js/custom/website.js';
			$this->class_settings[ 'css' ][] = 'css/template-1/site_registration.css';
			
            $serviceURL = array();
			/*
            if( isset( $general_settings_data[ $this->table_name ]['ALLOW GMAIL SIGNIN']['default'] ) ){
                //get google url
                $serviceURL['ALLOW GMAIL SIGNIN'] = $this->google( array( 'type' => "getURL" ) );
            }
            if( isset( $general_settings_data[ $this->table_name ]['ALLOW FACEBOOK SIGNIN']['default'] ) ){
                //get facebook url
                $serviceURL['ALLOW FACEBOOK SIGNIN'] = $this->facebook( array( 'type' => "getURL" ) );
            }
            */
			$website = new cWebsite();
			$website->class_settings = $this->class_settings;
			$website->class_settings[ 'action_to_perform' ] = 'setup_website';
			$returning = $website->website();
			
			$this->class_settings = $website->class_settings;
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array(
				'html' => $form_data['html'],
				'verification_data' => $verification_data,
				'general_settings' => $general_settings_data[ $this->table_name ],
				'service_url' => $serviceURL,
				'pagepointer' => $this->class_settings['calling_page'],
			);
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/sign-in' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning['html'] = '<div class="container"><div id="page-wrapper"><br />' . $script_compiler->script_compiler() . '</div></div>';
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
			if( ! isset( $this->class_settings['hidden_records'] ) ){
				$this->class_settings['hidden_records'] = array(
					'site_users006' => 1,
					'site_users018' => 1,
					'site_users019' => 1,
					'site_users020' => 1,
				);
			}
				
			if( ! isset( $this->class_settings['hidden_records_css'] ) ){
				$this->class_settings['hidden_records_css'] = array(
					'site_users009' => 1,
					'site_users012' => 1,
					'site_users013' => 1,
					'site_users014' => 1,
					'site_users015' => 1,
					'site_users016' => 1,
					'site_users017' => 1,
				);
			}
			
            if( isset( $this->class_settings['priv_id'] ) && $this->class_settings['priv_id'] ){
                switch ( $this->class_settings['action_to_perform'] ){
                case 'create_new_record':
                case 'edit':
                    switch( $this->class_settings['priv_id'] ){
                    case 'admin_seller':
                    case 'seller':
                    case 'buyer':
                    break;
                    default:
                        unset( $this->class_settings['hidden_records_css'] );
                        unset( $this->class_settings['hidden_records'] );
                        
                        $this->class_settings['hidden_records'] = array(
                            $this->table_fields['oldpassword'] => 1,
                            $this->table_fields['password'] => 1,
                            $this->table_fields['confirmpassword'] => 1,
                        );
                    break;
                    }
                break;
                }
            }
            
			if( isset( $this->class_settings['unset_hidden_records_css'] ) ){
				foreach( $this->class_settings['unset_hidden_records_css'] as $key ){
					unset( $this->class_settings['hidden_records_css'][ $key ] );
				}
			}
			
			//unset( $this->class_settings['hidden_records_css'][ $this->table_fields["role"] ] );
			//unset( $this->class_settings['hidden_records'][ $this->table_fields["role"] ] );
			
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
				'country' => 1,
				'message' => 'Returned form data capture form',
				'do_not_reload_table' => 1,
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
			
            $settings = array(
                'cache_key' => $this->table_name . '-' . 'newly-registered-users',
                'directory_name' => $this->table_name,
                'permanent' => true,
            );
            clear_cache_for_special_values( $settings );
            
			//IMPORTANT
			//make provision for array
			if( isset( $_POST['id'] ) ){
				$this->class_settings['user_id'] = $_POST['id'];
				$this->class_settings[ 'clear_cache' ] = 1;
				$this->_get_user_details();
			}
			
			return $returning_html_data;
		}
		
		private function _display_data_table(){
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'teller_registration_form':
			case 'teller_registration_form_1':
			case 'edit_teller_details_form':
				$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = " AND `".$this->table_name."`.`".$this->table_fields["role"]."` = 'teller' AND `".$this->table_name."`.`created_by` = '".$this->class_settings["user_id"]."' ";
			break;
			case 'display_all_records_buyers':
				$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = " AND `".$this->table_name."`.`".$this->table_fields["role"]."` = 'buyer' ";
			break;
			case 'display_all_records_sellers':
				$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = " AND `".$this->table_name."`.`".$this->table_fields["role"]."` = 'seller' ";
			break;
			case 'display_all_records_admin_sellers':
				$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = " AND `".$this->table_name."`.`".$this->table_fields["role"]."` = 'admin_seller' ";
			break;
			default:
				unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
			break;
			}
			
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
				
				$err->class_that_triggered_error = 'cSite_users.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			/*
			$form->datatables_settings = array(
					'show_edit_button' => 1,		//Determines whether or not to show edit button
					'show_delete_button' => 1,		//Determines whether or not to show delete button
                    'show_edit_password_button' => 1,
					'custom_edit_button' => '<a href="#" class="custom-single-selected-record-button btn btn-mini btn-danger" function-id="1" search-table="" function-class="'.$this->table_name.'" function-name="resend_verification_email" module-id="" module-name="" action="?module=&action='.$this->table_name.'&todo=resend_verification_email" mod="edit-'.md5($this->table_name).'" todo="resend_verification_email" title="Resend Verification Email">Resend V.Email</a>',
                    
			);
			*/
			$this->datatable_settings['current_module_id'] = $this->class_settings['current_module'];
			
			$form->datatables_settings = $this->datatable_settings;
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-datatable',
			);
		}
		
		private function _authenticate_user(){
			$returning_html_data = array();
			
			$this->class_settings['return_form_data_only'] = true;
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			if( isset( $returning_html_data['form_data'][ $this->table_fields['email'] ]['value'] ) && isset( $returning_html_data['form_data'][ $this->table_fields['password'] ]['value'] ) ){
				$this->class_settings['username'] = $returning_html_data['form_data'][ $this->table_fields['email'] ]['value'];
				$this->class_settings['password'] = $returning_html_data['form_data'][ $this->table_fields['password'] ]['value'];	//hashed password
				
				return $this->_confirm_user_authentication_details();
			}
			
			return $returning_html_data;
		}
		
		private function _reset_user_password(){
			$returning_html_data = array();
			
			$this->class_settings['return_form_data_only'] = true;
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			if( isset( $returning_html_data['form_data'][ $this->table_fields['email'] ]['value'] ) ){
				$this->class_settings['table_name'] = $this->table_name;
				$this->class_settings['user_email'] = $returning_html_data['form_data'][ $this->table_fields['email'] ]['value'];
				
				$authentication = new cAuthentication();
				$authentication->class_settings = $this->class_settings;
				
				$authentication->table_fields = $this->table_fields;
				$authentication->class_settings[ 'action_to_perform' ] = 'reset_user_password';
			
				return $authentication->authentication();
			}
		}
		
		private function _confirm_user_authentication_details(){
			$this->class_settings['tables'] = array( $this->table_name );
			
			$this->class_settings['query'] = "SELECT `".$this->table_name."`.`".$this->table_fields['password']."` as 'password', `".$this->table_name."`.`id`, `".$this->table_name."`.`".$this->table_fields['role']."` as 'role', `".$this->table_name."`.`".$this->table_fields['firstname']."` as 'firstname', `".$this->table_name."`.`".$this->table_fields['lastname']."` as 'lastname', `".$this->table_name."`.`".$this->table_fields['email']."` as 'email', `".$this->table_name."`.`".$this->table_fields['verified_email_address']."` as 'verification_status'  FROM `" . $this->class_settings[ 'database_name' ] . "`.`".$this->table_name."` WHERE `record_status` = '1' AND `".$this->table_fields['email']."` = '" . $this->class_settings['username'] . "' LIMIT 1";
			
			$authentication = new cAuthentication();
			$authentication->class_settings = $this->class_settings;
			$authentication->class_settings[ 'action_to_perform' ] = 'confirm_username_and_password';
			
			$returning_html_data = $authentication->authentication();
			
			if( isset( $returning_html_data['typ'] ) && $returning_html_data['typ'] == 'authenticated' ){
                if( isset( $this->class_settings['id'] ) && $this->class_settings['id'] ){
                    $this->class_settings['where'] = "WHERE `id`='" . $this->class_settings['id'] . "'";
                    $user_details = $this->_get_user_details();
                    
                    $this->class_settings[ 'user_email' ] = $user_details['email'];
                    $this->class_settings[ 'user_full_name' ] = $user_details['firstname'] . ' ' . $user_details['lastname'];
                    $this->class_settings[ 'user_id' ] = $this->class_settings['id'];
                }
                
                //set redirection url
				$returning_html_data['status'] = 'new-status';
				$returning_html_data['redirect_url'] = get_successful_authentication_url();
			}
			
			return $returning_html_data;
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
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `".$val."` as '".$key."'";
			}
			
            if( ! isset( $this->class_settings['where'] ) )$this->class_settings['where'] = '';
            
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
            
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $s_val ){
					$returned_data[ $s_val['id'] ] = $s_val;
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$s_val['id'],
						'cache_values' => $returned_data[ $s_val['id'] ],
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
                    
                    $id  = $s_val['id'];
				}
			}
		
				
            if( isset( $returned_data[ $id ] ) )return array_merge( $returned_data[ $id ] , $returned_data );
            return $returned_data;
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
					$this->class_settings['user_id'] = $returning_html_data['saved_record_id'];
					
					switch ( $this->class_settings['action_to_perform'] ){
					case 'save_contact_info':
					case 'save_personal_info':
					//break;
					default:
                        unset( $this->class_settings[ 'where' ] );
						$this->class_settings[ 'do_not_check_cache' ] = 1;
						$this->_get_user_details();
					break;
					}
					
				}
			}
			
			return $returning_html_data;
		}
		
		private function _save_registration(){
			$returning_html_data = array();
			
			//check for duplicate email
			if( isset( $this->table_fields[ 'email' ] ) && $this->table_fields[ 'email' ] ){
				if( isset( $_POST[ $this->table_fields[ 'email' ] ] ) && $_POST[ $this->table_fields[ 'email' ] ] ){
					$email = $_POST[ $this->table_fields[ 'email' ] ];
					$hashed_email = md5( $_POST[ $this->table_fields[ 'email' ] ] );
					
					$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE MD5( `".$this->table_name."`.`".$this->table_fields[ 'email' ]."` ) = '" . $hashed_email . "' AND `" . $this->table_name . "`.`record_status`='1' ";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 0,
						'tables' => array( $this->table_name ),
					);
					$sql_result = execute_sql_query( $query_settings );
					
					if( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ){
						//DUPLICATE EMAIL ADDRESS
						$err = new cError(000102);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'cSite_users.php';
						$err->method_in_class_that_triggered_error = '_save_registration';
						$err->additional_details_of_error = 'Duplicate Email Address';
						
						$returning_html_data = $err->error();
						$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
						$returning_html_data['status'] = 'saved-form-data';
						
						return $returning_html_data;
					}
					
					$this->default_password = generatePassword( 8, 1, 0, 1, 0);
					$_POST[ $this->table_fields[ 'password' ] ] = $this->default_password;
					$_POST[ $this->table_fields[ 'confirmpassword' ] ] = $this->default_password;
					$_POST[ $this->table_fields[ 'oldpassword' ] ] = $this->default_password;
				}
			}
			
			$returning_html_data = $this->_save_changes();
			
			if( isset( $returning_html_data['typ'] ) && $returning_html_data['typ'] == 'saved' ){
				
				$returning_html_data['status'] = "new-status";
				unset( $returning_html_data['html'] );
				
                $settings = array(
					'cache_key' => $this->table_name . '-' . 'newly-registered-users',
					'directory_name' => $this->table_name,
					'permanent' => true,
				);
				clear_cache_for_special_values( $settings );
                
                $settings = array(
					'cache_key' => $this->table_name . '-' . 'all-registered-users',
					'directory_name' => $this->table_name,
					'permanent' => true,
				);
				clear_cache_for_special_values( $settings );
                
				$this->class_settings['id'] = $returning_html_data['saved_record_id'];
				$this->class_settings['where'] = "WHERE `id`='" . $this->class_settings['id'] . "'";
				$user_details = $this->_get_user_details();
				
				if( isset( $user_details['email'] ) && isset( $user_details[ 'password' ] ) ){
					$this->class_settings[ 'user_email' ] = $user_details['email'];
					$this->class_settings[ 'user_full_name' ] = ucwords( strtolower( $user_details['firstname'] . " " . $user_details['lastname'] ) );
					$this->class_settings[ 'user_id' ] = $this->class_settings['id'];
				
					$returning_html_data['err'] = 'Successful!';
					$returning_html_data['msg'] = 'You have completed the first stage of the online registration process';
					$returning_html_data['status'] = 'new-status';
					unset( $returning_html_data['html'] );
					
					//Send Welcome Email
					$this->class_settings['message_type'] = 1;
						
					$title = "SUCCESSFUL COMPLETION OF THE FIRST STAGE OF LRCN ONLINE REGISTRATION";
					$info = 'DEFAULT PASSWORD: <b>'.$this->default_password.'</b><br />';
						
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
					
					$this->class_settings[ 'data' ]["full_name"] = $this->class_settings[ 'user_full_name' ];
					$this->class_settings[ 'data' ]["email"] = $this->class_settings[ 'user_email' ];
					$this->class_settings[ 'data' ]["title"] = $title;
					$this->class_settings[ 'data' ]["info"] = $info;
					
					$this->class_settings['message'] = $this->_get_html_view();
					$this->class_settings['subject'] = $title;
					
					$this->class_settings[ 'data' ] = array();
					$this->class_settings[ 'data' ]["full_name"] = $this->class_settings[ 'user_full_name' ];
					$this->class_settings[ 'data' ]["email"] = $this->class_settings[ 'user_email' ];
					$this->class_settings[ 'data' ]["skip_style"] = 1;
					$this->class_settings[ 'data' ]["title"] = $title;
					$this->class_settings[ 'data' ]["info"] = $info;
					//$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/welcome-message.php' );
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
					
					$returning_html_data['html_replacement'] = $this->_get_html_view();
					
					$returning_html_data['html_replacement_selector'] = "#registration-form-container";
					
					$this->_send_email();
				}
				
				return $returning_html_data;
				
				//CHECK IF USER ADDRESS IS SET
				$this->class_settings['id'] = $returning_html_data['saved_record_id'];
				$this->class_settings['where'] = "WHERE `id`='" . $this->class_settings['id'] . "'";
				$user_details = $this->_get_user_details();
                
				if( isset( $user_details['email'] ) && isset( $user_details[ 'password' ] ) ){
					$this->class_settings['username'] = $user_details['email'];
					$this->class_settings['password'] = $user_details[ 'password' ];	//hashed password
					
					$auth_data = $this->_confirm_user_authentication_details();
					
					if( isset( $auth_data['user_details'] ) )$returning_html_data['user_details'] = $auth_data['user_details'];
					
				}
				
				//create pending task
				$this->_create_pending_task( $user_details );
                
				$general_settings_data = $this->_get_general_settings();
				
				if( isset( $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ][ 'default' ] ){
					
					//$returning_html_data['status'] = 'redirect-to-successful-registration-url';
					$returning_html_data['redirect_url'] = $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ]['default'];
					
				}
				
				if( isset( $general_settings_data[ $this->table_name ][ 'ALLOW EMAIL ACCOUNT VERIFICATION' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'ALLOW EMAIL ACCOUNT VERIFICATION' ][ 'default' ] == 'TRUE' ){
					
					if( ! ( isset( $user_details[ $this->class_settings['id'] ]['verified_email_address'] ) && $user_details[ $this->class_settings['id'] ][ 'verified_email_address' ] == 'yes' ) ){
						
						$this->class_settings['message_type'] = 2;	//Send Verification Email
						$this->class_settings['message'] = 'Please verify your email';
						$this->class_settings['subject'] = 'Send Verification Email';
						$this->_send_email();

						//CREATE NOTIFICATION && //CREATE PENDING TASKS
						$this->class_settings['notification_data'] = array(
							'title' => 'Verify Your Email Address',
							'detailed_message' => 'A verification email was sent to your email address, you should click the link in the email to verify your email account<br /><br />Check your <b>spam folder</b> if you cannot find the email',
							'send_email' => 'no',
							'notification_type' => 'pending_task',
							'target_user_id' => $this->class_settings['id'],
							'class_name' => $this->table_name,
							'method_name' => 'verify_email_address',
							
							'task_status' => 'pending',
							'task_type' => 'system_generated',
							
							'trigger_function' => '?action=site_users&todo=display_user_details',
						);
						
						$notifications = new cNotifications();
						$notifications->class_settings = $this->class_settings;
						$notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
						$notifications->notifications();
					}else{
						//CREATE ACCOUNT VERIFICATION TASK
						$returning_html_data['redirect_url'] = $this->_activate_merchant_account_verification();
					}
					
				}else{
					//CREATE ACCOUNT VERIFICATION TASK
					$returning_html_data['redirect_url'] = $this->_activate_merchant_account_verification();
				}
				
				$returning_html_data['redirect_url'] = "?page=user-dashboard";
			}
			
			return $returning_html_data;
		}
		
        private function _create_pending_task( $user_details = array() ){
			
            if( ! ( isset( $user_details['address_status'] ) && $user_details[ 'address_status' ] ) ){
                
                $address = '';
                if( isset( $user_details['address_html'] ) )
                    $address = $user_details['address_html'];
                
                //CREATE NOTIFICATION && //CREATE PENDING TASKS
                $this->class_settings['notification_data'] = array(
                    'title' => 'Update Primary Contact Address',
                    'detailed_message' => 'Your primary contact address is incomplete, endeavour to update your address<br /><br />Your current address is<address>' . $address . '</address>',
                    'send_email' => 'no',
                    'notification_type' => 'pending_task',
                    'target_user_id' => $this->class_settings['id'],
                    'class_name' => $this->table_name,
                    'method_name' => 'save_contact_info',
                    
                    'task_status' => 'pending',
                    'task_type' => 'system_generated',
                    
                    'trigger_function' => '?action=site_users&todo=display_user_details',
                );
                
                $notifications = new cNotifications();
                $notifications->class_settings = $this->class_settings;
                $notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
                $notifications->notifications();
                
                //email verification task
                if( $user_details['verified_email_address'] != 'yes' ){
                    $this->class_settings['notification_data'] = array(
                        'title' => 'Verify Your Email Address',
                        'detailed_message' => 'A verification email was sent to your email address, you should click the link in the email to verify your email account<br /><br />Check your <b>spam folder</b> if you cannot find the email',
                        'send_email' => 'no',
                        'notification_type' => 'pending_task',
                        'target_user_id' => $this->class_settings['id'],
                        'class_name' => $this->table_name,
                        'method_name' => 'verify_email_address',
                        
                        'task_status' => 'pending',
                        'task_type' => 'system_generated',
                        
                        'trigger_function' => '?action=site_users&todo=display_user_details',
                    );
                    
                    $notifications->class_settings = $this->class_settings;
                    $notifications->notifications();
                }
            }
        }
        
		private function _save_user_info(){
			$returning_html_data = array();
			
			//check for matching old password
			if( isset( $this->table_fields[ 'oldpassword' ] ) && $this->table_fields[ 'oldpassword' ] ){
				if( isset( $_POST[ $this->table_fields[ 'oldpassword' ] ] ) && $_POST[ $this->table_fields[ 'oldpassword' ] ] ){
					
					$hashed_password = md5( $_POST[ $this->table_fields[ 'oldpassword' ] ] . get_websalter() );
					
					$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`".$this->table_fields[ 'password' ]."` = '" . $hashed_password . "' AND `" . $this->table_name . "`.`record_status`='1' AND `id`='".$this->class_settings['user_id']."'";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					$sql_result = execute_sql_query( $query_settings );
					
					if( ! ( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ) ){
						$err = new cError(000103);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'cSite_users.php';
						$err->method_in_class_that_triggered_error = '_save_user_info';
						$err->additional_details_of_error = 'Incorrect Password';
						
						$returning_html_data = $err->error();
						$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
						$returning_html_data['status'] = 'saved-form-data';
						
						return $returning_html_data;
					}
				}
			}
			
			$returning_html_data = $this->_save_changes();
			
			if( isset( $returning_html_data['typ'] ) && $returning_html_data['typ'] == 'saved' ){
				
				switch ( $this->class_settings['action_to_perform'] ){
					//update task to complete
				case 'save_contact_info':
				case 'save_personal_info':
					$returning_html_data['status'] = 'saved-personal-info';
                    
                    $this->class_settings['hacked_calling_page'] = './engine/'; //hacked page pointer
					$returning_html_data['html'] = $this->_get_user_info_for_display();
					
					if( ! ( isset( $this->class_settings['user_details'][ 'updated_primary_address' ] ) && $this->class_settings['user_details'][ 'updated_primary_address' ] == 'yes' ) ){
						
						if( isset( $this->class_settings['user_details'][ 'address_status' ] ) && $this->class_settings['user_details'][ 'address_status' ] ){
						
							$this->class_settings['notification_data'] = array(
								'title' => 'Primary Contact Address Updated',
								'detailed_message' => 'Congratulations, you\'ve successfully updated your primary contact address',
								'send_email' => 'no',
								'notification_type' => 'completed_task',
								'target_user_id' => $this->class_settings['user_id'],
								'class_name' => $this->table_name,
								'method_name' => 'save_contact_info',
								
								'task_status' => 'complete',
								'task_type' => 'system_generated',
							);
							
							$notifications = new cNotifications();
							$notifications->class_settings = $this->class_settings;
							$notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
							$notifications->notifications();
							
							//update address status
							$settings_array = array(
								'database_name' => $this->class_settings['database_name'] ,
								'database_connection' => $this->class_settings['database_connection'] ,
								'table_name' => $this->table_name ,
								'field_and_values' => array(
									
									$this->table_fields['updated_primary_address'] => array( 'value' => 'yes' ),
									
								) ,
								'where_fields' => 'id',
								'where_values' => $returning_html_data['saved_record_id'],
							);
							
							$save = update( $settings_array );
						}
						
					}
					
					$returning_html_data['form_id'] = $this->table_name . '-form';
					
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->_get_user_details();
				break;
				case 'save_password_info':
					$returning_html_data['status'] = 'saved-password-info';
					$returning_html_data['form_id'] = $this->table_name . '-form';
					
					$returning_html_data['err'] = 'Password Change Successful!';
					$returning_html_data['msg'] = 'Your password has been successfully changed';
				break;
				}
				
			}
			
			return $returning_html_data;
		}
		
		private function _get_all_users_countries(){
			$returned_data = array();
			
			$cache_key = $this->table_name . '-all-users-countries';
			
            $settings = array(
                'cache_key' => $cache_key,
				'permanent' => true,
            );
            
            //CHECK IF CACHE IS SET
			/*
            $cached_values = get_cache_for_special_values( $settings );
            if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
                return $cached_values;
            }
			*/
				
			$this->class_settings['where'] = " WHERE `record_status`='1' ";
			
            $select = "`id`, `".$this->table_fields['address_fields']['country']."` as 'country', `".$this->table_fields['email']."` as 'email' ";
            
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
            
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $s_val ){
					$returned_data[ $s_val['id'] ] = $s_val;
				}
                
                //Cache Settings
                $settings = array(
                    'cache_key' => $cache_key,
                    'cache_values' => $returned_data,
                    'permanent' => true,
                );
                set_cache_for_special_values( $settings );
                
			}
			
            return $returned_data;
		}
		
		private function _display_user_details_data_capture_form(){
			//SET VARIABLES FOR EDIT MODE
			$_POST['id'] = $this->class_settings['user_id'];
			$_POST['mod'] = 'edit-'.md5( $this->table_name );
			
			//GENERATE REGISTRATION FORM WITH USER DETAILS
			//Disable appearance of all headings on forms
			$this->class_settings['do_not_show_headings'] = true;
				
			//Hide certain form fields
			$this->class_settings[ 'hidden_records' ] = array(
				//'site_users006' => 1,
				'site_users009' => 1,
				'site_users019' => 1,
				'site_users020' => 1,
			);
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_site_users_address_data_capture_form':
				foreach( $this->table_fields as $key => $val ){
					if( $key != 'address_fields' ){
						$this->class_settings[ 'hidden_records' ][ $val ] = 1;
					}
				}
			break;
			}
			
			$this->class_settings[ 'hidden_records_css' ] = array();
			
            //$this->class_settings['form_action'] = '?action='.$this->table_name.'&todo=save_contact_info';
            
			//Form button caption
			$this->class_settings[ 'form_submit_button' ] = 'Update Changes';
			
			return $this->_generate_new_data_capture_form();
		}
		
		private function _display_user_details(){
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			$processing_status = $this->_save_changes();
			
			if( is_array( $processing_status ) && !empty( $processing_status ) ){
				$result_of_all_processing = $processing_status;
			}
			
			$returned_data = $this->_display_user_details_data_capture_form();
			
			$this->class_settings[ 'js' ][] = 'my_js/custom/display-user-details.js';
			$this->class_settings[ 'css' ][] = 'css/template-1/contact-details.css';
			
			$dashboard = new cDashboard();
			$dashboard->class_settings = $this->class_settings;
			$dashboard->class_settings[ 'action_to_perform' ] = 'setup_dashboard';
			$returning = $dashboard->dashboard();
			
			$this->class_settings = $dashboard->class_settings;
			
			$returning[ 'html' ] = '<div id="page-wrapper">';
				$returning[ 'html' ] .= '<br /><div class="row"><div class="col-lg-9"><div class="panel panel-default"><div class="panel-heading"><i class="fa fa-user fa-fw"></i> User Profile</div><div class="panel-body">';
				
					$returning[ 'html' ] .= '<style type="text/css">#site_users-form{ display:none;	}</style>';
					$returning[ 'html' ] .= '<div class="row-fluid" id="display-user-details-view">';
						$returning[ 'html' ] .= $this->_get_user_info_for_display();
					$returning[ 'html' ] .= '</div><hr />';
					
					$returning[ 'html' ] .= $returned_data['html'];
					
				$returning[ 'html' ] .= '</div></div></div></div>';
			$returning[ 'html' ] .= '</div>';
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
		}
		
		private function _get_user_info_for_display(){
			//GET USER DETAILS
			//$this->class_settings['where'] = "WHERE `id`='" . $this->class_settings['user_id'] . "'";
			$this->class_settings['user_details'] = $this->_get_user_details();
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/site_users/display-user-details.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			$script_compiler->class_settings[ 'data' ] = array(
				'user_details' => $this->class_settings['user_details'],
				'save_password_info' => '?action='.$this->table_name.'&todo=save_password_info',
				'save_personal_info' => '?action='.$this->table_name.'&todo=save_personal_info',
				'save_contact_info' => '?action='.$this->table_name.'&todo=save_contact_info',
				//'page_pointer' => $this->class_settings['calling_page'],
				'page_pointer' => isset( $this->class_settings['hacked_calling_page'] )?$this->class_settings['hacked_calling_page']:$this->class_settings['calling_page'],
			);
			
			return $script_compiler->script_compiler();
		}
		
		private function _change_user_password(){
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			
			//CHECK FOR OLD PASSWORD
			if( isset( $_POST[ $this->table_fields['oldpassword'] ] ) ){
				
				if( $_POST[ $this->table_fields['oldpassword'] ] ){
				
					//TEST OLD PASSWORD TO ENSURE IT MATCHES STORED PASSWORD
					$query = "SELECT * FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`id`='" . $this->class_settings['user_id'] . "' AND `" . $this->table_name . "`.`".$this->table_fields['oldpassword']."`='" . md5( $_POST[ $this->table_fields['oldpassword'] ] . get_websalter() ) . "' AND `" . $this->table_name . "`.`record_status`='1' ";
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
						unset( $_POST[ $this->table_fields['oldpassword'] ] );
						
						$processing_status = $this->_save_changes();
						
						if( is_array( $processing_status ) && !empty( $processing_status ) ){
							$result_of_all_processing = $processing_status;
							
							if( isset( $result_of_all_processing['typ'] ) && $result_of_all_processing['typ'] == 'saved' ){
								//TRANSFORM SUCCESS MESSAGE
								$err = new cError(010008);
								$err->action_to_perform = 'notify';
								$err->class_that_triggered_error = 'cSite_users.php';
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
				$err->class_that_triggered_error = 'cSite_users.php';
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
				
			$this->class_settings["hidden_records"] = array();
			$this->class_settings["hidden_records_css"] = array();
				
			foreach( $this->table_fields as $k => $v ){
				$this->class_settings["hidden_records"][ $v ] = 1;
			}
			
			unset( $this->class_settings['hidden_records'][ $this->table_fields["oldpassword"] ] );
			
			unset( $this->class_settings['hidden_records'][ $this->table_fields["password"] ] );
			unset( $this->class_settings['hidden_records'][ $this->table_fields["confirmpassword"] ] );
			
			//Form button caption
			$this->class_settings[ 'form_submit_button' ] = 'Change Password';
			
			$returned_data = $this->_generate_new_data_capture_form();
			
			if( ! empty ( $result_of_all_processing ) && isset( $result_of_all_processing['html'] ) ){
				$result_of_all_processing['html'] = $returned_data['html'];
				
				return $result_of_all_processing;
			}
			
			return $returned_data;
		}
		
		private function _change_user_password_admin(){
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			
			//CHECK FOR OLD PASSWORD
			if( isset( $_POST[ $this->table_fields['password'] ] ) && isset( $_POST[ $this->table_fields['confirmpassword'] ] ) ){
				
                $processing_status = $this->_save_changes();
                
                if( is_array( $processing_status ) && !empty( $processing_status ) ){
                    $result_of_all_processing = $processing_status;
                    
                    if( isset( $result_of_all_processing['typ'] ) && $result_of_all_processing['typ'] == 'saved' ){
                        //TRANSFORM SUCCESS MESSAGE
                        $err = new cError(010008);
                        $err->action_to_perform = 'notify';
                        $err->class_that_triggered_error = 'cSite_users.php';
                        $err->method_in_class_that_triggered_error = '_change_user_password';
                        $err->additional_details_of_error = 'successful password change';
                        
                        return $err->error();
                    }
                    
                    return $processing_status;
                }
			}
			
			//SET VARIABLES FOR EDIT MODE
			//$_POST['id'] = $this->class_settings['user_id'];
			//$_POST['mod'] = 'edit-'.md5( $this->table_name );
			
			//GENERATE REGISTRATION FORM WITH USER DETAILS
			//Disable appearance of all headings on forms
			$this->class_settings['do_not_show_headings'] = true;
				
			//Hide certain form fields
			$this->class_settings[ 'hidden_records' ] = array(
				'site_users001' => 1,
				'site_users002' => 1,
				'site_users003' => 1,
				'site_users004' => 1,
				'site_users005' => 1,
				'site_users006' => 1,
				//'site_users007' => 1,
				//'site_users008' => 1,
				'site_users009' => 1,
				'site_users010' => 1,
				'site_users011' => 1,
				'site_users012' => 1,
				'site_users013' => 1,
				'site_users014' => 1,
				'site_users015' => 1,
				'site_users016' => 1,
				'site_users017' => 1,
				'site_users018' => 1,
				'site_users019' => 1,
				'site_users020' => 1,
				'site_users021' => 1,
			);
			
			//Hide certain form fields
			$this->class_settings[ 'hidden_records_css' ] = array(
				'site_users016' => 1,
			);
			
			//Form button caption
			$this->class_settings[ 'form_submit_button' ] = 'Change Password';
			
			$returned_data = $this->_generate_new_data_capture_form();
			
			return $returned_data;
		}
		
		private function _resend_verification_email(){
            //CHECK IF USER ADDRESS IS SET
            if( isset( $_POST['id'] ) && isset( $_POST['mod'] ) && $_POST['mod'] == 'edit-'.md5( $this->table_name ) ){
                $this->class_settings['user_id'] = $_POST['id'];
                $user_details = $this->_get_user_details();
                
                if( isset( $user_details['id'] ) ){
                    $this->class_settings[ 'user_email' ] = $user_details['email'];
                    $this->class_settings[ 'user_full_name' ] = $user_details['firstname'] .' '. $user_details['lastname'];
                    $this->class_settings[ 'verified_email_address' ] = $user_details['verified_email_address'];
                }else{
                    if( isset( $user_details[ $this->class_settings['user_id'] ]['id'] ) ){
                        $this->class_settings[ 'user_email' ] = $user_details[ $this->class_settings['user_id'] ]['email'];
                        $this->class_settings[ 'user_full_name' ] = $user_details[ $this->class_settings['user_id'] ]['firstname'] .' '. $user_details[ $this->class_settings['user_id'] ]['lastname'];
                        $this->class_settings[ 'verified_email_address' ] = $user_details[ $this->class_settings['user_id'] ]['verified_email_address'];
                    }
                }
            
                if( isset( $this->class_settings[ 'verified_email_address' ] ) && $this->class_settings[ 'verified_email_address' ] == 'yes'  ){
                    $err = new cError(010014);
                    $err->action_to_perform = 'notify';
                    $err->class_that_triggered_error = 'cSite_users.php';
                    $err->method_in_class_that_triggered_error = '_resend_verification_email';
                    $err->additional_details_of_error = 'The email address associated with this account has already been verified';
                    return $err->error();
                }else{
                    $this->class_settings['message_type'] = 2;	//Send Verification Email
					
					$this->class_settings['message'] = 'Please verify your email';
					$this->class_settings['subject'] = 'Send Verification Email';
                    $this->_send_email();
                    
                    $err = new cError(010013);
                    $err->action_to_perform = 'notify';
                    $err->class_that_triggered_error = 'cSite_users.php';
                    $err->method_in_class_that_triggered_error = '_resend_verification_email';
                    $err->additional_details_of_error = 'Verification email has been successfully sent';
                    return $err->error();
                }
            }
            
            $err = new cError(000021);
            $err->action_to_perform = 'notify';
            $err->class_that_triggered_error = 'cSite_users.php';
            $err->method_in_class_that_triggered_error = '_resend_verification_email';
            $err->additional_details_of_error = 'Could not resend verification email';
            
            return $err->error();
        }
        
		private function _verify_email_address(){
			
			//INITIALIZE RETURNING ARRAY
			$result_of_all_processing = array();
			
			$general_settings_data = $this->_get_general_settings();
			
			if( isset( $general_settings_data[ $this->table_name ][ 'ALLOW EMAIL ACCOUNT VERIFICATION' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'ALLOW EMAIL ACCOUNT VERIFICATION' ][ 'default' ] == 'TRUE' ){
				
				if( isset( $_GET['data'] ) && $_GET['data'] ){
					
					$this->class_settings['user_id'] = '';
					$this->class_settings['where'] = "WHERE MD5( MD5( `id` ) )='" . md5( $_GET['data'] ) . "' LIMIT 1";
					$user_details = $this->_get_user_details();
					
					if( empty( $user_details ) ){
						//FAILED VERIFICATION
						$script_compiler = new cScript_compiler();
						$script_compiler->class_settings = $this->class_settings;
						
						$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/site_users/failed-verification-message.php' );
						$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
						
						return $script_compiler->script_compiler();
					}else{
						
						set_successful_authentication_url( '?action=welcome_new_user&todo=user_dashboard' );
						
						if( isset( $user_details["id"] ) ){
							$a[ $user_details["id"] ] = $user_details;
							$user_details = $a;
						}
						//print_r($user_details);
						//exit;
						
                        if( is_array( $user_details ) ){
                            foreach( $user_details as $id => $sval ){
								
                                if( isset( $sval[ 'verified_email_address' ] ) && $sval[ 'verified_email_address' ] == 'yes' ){
                                    //ALREADY VERIFIED EMAIL ADDRESS
                                    $this->class_settings['notification_data'] = array(
                                        'title' => 'Attempted Email Address Verification',
                                        'detailed_message' => 'There was an attempt to re-verify your already verified email address',
                                        'send_email' => 'no',
                                        'notification_type' => 'no_task',
                                        'target_user_id' => $sval['id'],
                                        'class_name' => $this->table_name,
                                        'method_name' => 'verify_email_address',
                                    );
                                    
                                    $notifications = new cNotifications();
                                    $notifications->class_settings = $this->class_settings;
                                    $notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
                                    $notifications->notifications();
                                }else{
                                    
                                    set_successful_authentication_url( '?action=welcome_new_user&todo=dashboard_email_verified_message' );
                                    
                                    if( isset( $sval['id'] ) ){
                                        //PERFORM VERIFICATION
                                        $query = "UPDATE `".$this->class_settings['database_name']."`.`" . $this->table_name . "` SET `".$this->table_name."`.`".$this->table_fields['verified_email_address']."`='yes', `".$this->table_name."`.`modification_date`='" . date("U") . "' WHERE `".$this->table_name."`.`id` = '" . $sval['id'] . "'";
                                        $query_settings = array(
                                            'database' => $this->class_settings['database_name'] ,
                                            'connect' => $this->class_settings['database_connection'] ,
                                            'query' => $query,
                                            'query_type' => 'UPDATE',
                                            'set_memcache' => 1,
                                            'tables' => array( $this->table_name ),
                                        );
                                        execute_sql_query($query_settings);
                                    }
                                    unset( $this->class_settings[ 'where' ] );
                                    $this->class_settings[ 'user_id' ] = $id;
                                    $this->class_settings[ 'do_not_check_cache' ] = 1;
                                    $this->class_settings['user_details'] = $this->_get_user_details();
                                    
                                    //NOTIFY OF VERIFICATION EMAIL TASK COMPLETION
                                    if( isset( $sval['email'] ) ){
                                        $this->class_settings['notification_data'] = array(
                                            'title' => 'Successful Email Address Verification',
                                            'detailed_message' => 'Your email address <a href="mailto:'.$sval['email'].'">'.$sval['email'].'</a> has been successfully verified',
                                            'send_email' => 'no',
                                            'notification_type' => 'completed_task',
                                            'target_user_id' => $sval['id'],
                                            'class_name' => $this->table_name,
                                            'method_name' => 'verify_email_address',
                                            
                                            'task_status' => 'complete',
                                            'task_type' => 'system_generated',
                                        );
                                        
                                        $notifications = new cNotifications();
                                        $notifications->class_settings = $this->class_settings;
                                        $notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
                                        $notifications->notifications();
                                    }
                                    //Send Welcome Email
                                    $this->class_settings['message_type'] = 30;
                                    $this->_send_email();
                                    
                                    $return_url = $this->_activate_merchant_account_verification();
                                    if( $return_url ){
                                        set_successful_authentication_url( $return_url );
                                    }
                                }
                                
                                if( isset( $sval['email'] ) ){
                                //authenticate
                                $this->class_settings['username'] = $sval[ 'email' ];
                                $this->class_settings['password'] = $sval[ 'password' ];	//hashed password
                                $this->class_settings[ 'skip_password' ] = true;
                                }
                                
                                $return = $this->_confirm_user_authentication_details();
                                if( isset( $return['redirect_url'] ) && $return['redirect_url'] ){
                                    header('Location: '.$return['redirect_url']);
                                    exit;
                                }
                            }
                        }
					}
					
				}
				
			}
			
		}
		
		private function _activate_merchant_account_verification(){
			
			//1. if activation is required based on user country
			$activation = $this->_get_bank_account_verification_fee();
			
			$role = '';
			if( isset( $this->class_settings['user_details'][ ''.$this->class_settings['user_id'] ][ 'role' ] ) ){
				$role = $this->class_settings['user_details'][ ''.$this->class_settings['user_id'] ][ 'role' ];
			}
			
			if( $activation ){
				//2. check gen settings for user role
				if( $role == 'seller' ){
					
					//CREATE NOTIFICATION && //CREATE PENDING TASKS
					$this->class_settings['notification_data'] = array(
						'title' => 'Verify Your Bank Account',
						'detailed_message' => 'Your don\'t have a verified bank account, that would enable us send payment to you once your items have been purchased',
						'send_email' => 'no',
						'notification_type' => 'pending_task',
						'target_user_id' => $this->class_settings['user_id'],
						'class_name' => $this->table_name,
						'method_name' => 'bank_account_verified',
						
						'task_status' => 'pending',
						'task_type' => 'system_generated',
						
						'trigger_function' => '?action=site_users&todo=display_user_details',
					);
					
					$notifications = new cNotifications();
					$notifications->class_settings = $this->class_settings;
					$notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
					$notifications->notifications();
					
					//Send Verify Bank Account Email
					/*
					$this->class_settings['message_type'] = 1;
					$this->_send_email();
					*/
					
					//. redirect to activation menu - ACTIVATION MENU + Welcome Message
					return '?action=merchant_accounts&todo=bank_account_manager';
				}
			}
			
		}
		
		private function _get_bank_account_verification_fee(){
			$user_details = get_site_user_details( array( 'id' => $this->class_settings['user_id'] ) );
			
			$country = '';
			if( isset( $user_details[ ''.$this->class_settings['user_id'] ][ 'country' ] ) ){
				$country = $user_details[ ''.$this->class_settings['user_id'] ][ 'country' ];
			}
			
			//1. if activation is required based on user country
			return get_general_settings_value( array(
				'table' => $this->table_name,
				'key' => 'MERCHANT BANK ACCOUNT VERIFICATION FEE',
				'country' => $country,
			) );
		}
		
		private function _send_email(){
			
			//Send Successful Email Verification Message
			$email = new cEmails();
			$email->class_settings = $this->class_settings;
			
			$email->class_settings[ 'action_to_perform' ] = 'send_mail';
			
			$email->class_settings[ 'destination' ]['email'][] = $this->class_settings[ 'user_email' ];
			$email->class_settings[ 'destination' ]['full_name'][] = $this->class_settings[ 'user_full_name' ];
			$email->class_settings[ 'destination' ]['id'][] = $this->class_settings[ 'user_id' ];
			
			$email->emails();
		}
		
        private function _site_users_google_authentication(){
            //$this->google( array( 'type' => 'process' ) );
        }
        
        private function _site_users_facebook_authentication(){
            $this->facebook( array( 'type' => 'process' ) );
        }
        
        private function google( $settings = array() ){
			/*
		   require_once $this->class_settings['calling_page']."classes/google-api-php-client/src/apiClient.php";
            require_once $this->class_settings['calling_page']."classes/google-api-php-client/src/contrib/apiOauth2Service.php";
                
            $client = new apiClient();
            $client->setApplicationName("Zidoff");
            // Visit https://code.google.com/apis/console?api=plus to generate your
            // oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
             $client->setClientId('339311571981-ead3q8g2sq5ts61fudkmhvcn0khlp43f.apps.googleusercontent.com');

             $client->setClientSecret('OB2am2KOxRDhIX5H6sEdio80');

             $client->setRedirectUri('https://www.zidoff.com/google/');
            // $client->setDeveloperKey('insert_your_developer_key');
            $oauth2 = new apiOauth2Service($client);
            */
            if( isset( $settings['type'] ) ){
                switch( $settings['type'] ){
                case "getURL":
                    //return $client->createAuthUrl();
                break;
                case "process":
                    if (isset($_GET['code'])) {
                      $client->authenticate();
                      $_SESSION['token'] = $client->getAccessToken();
                      
                      unset($_GET['code']);
                      $getparams = '';
                        foreach( $_GET as $key => $val ){
                            if($getparams)$getparams .= '&'.$key.'='.$val;
                            else $getparams = $key.'='.$val;
                        }
                        
                      $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].'?toks='.$_SESSION['token'] .'&'.$getparams;
                      
                      header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
                    }

                    if (isset($_SESSION['token']) || isset($_GET['toks'])) {
                    
                     if($_GET['toks']!=null)$clientTok = $_GET['toks'];
                     if(isset($_SESSION['token']) && $_SESSION['token']!=null)$clientTok = $_SESSION['token'];
                        $clientTok = stripslashes($clientTok);
                        $client->setAccessToken($clientTok);
                    }

                    if (isset($_REQUEST['logout'])) {
                      unset($_SESSION['token']);
                      $client->revokeToken();
                    }

                    if ($client->getAccessToken()) {
                      $user = $oauth2->userinfo->get();
                        
                        $email = trim( filter_var($user['email'], FILTER_SANITIZE_EMAIL) );
                        
                        //check for existing email
                        $query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`".$this->table_fields[ 'email' ]."` = '" . $email . "' AND `" . $this->table_name . "`.`record_status`='1' ";
                        $query_settings = array(
                            'database' => $this->class_settings['database_name'] ,
                            'connect' => $this->class_settings['database_connection'] ,
                            'query' => $query,
                            'query_type' => 'SELECT',
                            'set_memcache' => 1,
                            'tables' => array( $this->table_name ),
                        );
                        $sql_result = execute_sql_query( $query_settings );
                        
                        if( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ){
                            //REGISTERED EMAIL ADDRESS
                            $this->class_settings['username'] = $email;
                            $this->class_settings['password'] = $this->default_password;
                            
                            $this->class_settings['skip_password'] = true;
                            
                            //Authenticate user with email only
                            //redirect to successful auth url
                            $returning_html_data = $this->_confirm_user_authentication_details();
                            if( isset( $returning_html_data['redirect_url'] ) ){
                                header('Location: '.$returning_html_data['redirect_url'] );
                                exit;
                            }
                        }
                        //set user email verification as verified
                        
                        /*
                        Array ( [id] => 111325829366867272256 [email] => pat2echo@gmail.com [verified_email] => 1 [name] => Patrick Ogbuitepu [given_name] => Patrick [family_name] => Ogbuitepu [link] => https://plus.google.com/111325829366867272256 [picture] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg [gender] => male [locale] => en ) Array ( [email] => pat2echo@gmail.com [name] => Patrick Ogbuitepu [id] => 111325829366867272256 [gender] => male [picture] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg [locale] => en )
                        */
                        //create user account & mail user default password
                        $data['firstname'] = filter_var($user['given_name'], FILTER_SANITIZE_STRING);
                        $data['lastname'] = filter_var($user['family_name'], FILTER_SANITIZE_STRING);
                        
                        $data['email'] = $email;
                        $data['sex'] = filter_var($user['gender'], FILTER_SANITIZE_STRING);
                        $data['verified_email_address'] = 'yes';
                        
                        $this->class_settings['user_data'] = $data;
                        
                        $this->_create_new_user_account_and_authenticate();
                        
                        // The access token may have been updated lazily.
                        $_SESSION['token'] = $client->getAccessToken();
                    }
                break;
                }
            }
            
        }
        
        private function facebook( $settings = array() ){
            
            //require_once $this->class_settings['calling_page']."classes/google-api-php-client/src/apiClient.php";
            
            if( isset( $settings['type'] ) ){
                
                switch( $settings['type'] ){
                case "getURL":
                    //echo $helper->getLoginUrl();exit;
                    return '/facebook';
                break;
                case "process":
                    if ( isset( $_SESSION['facebook_object']['fbid'] ) && $_SESSION['facebook_object']['fbid'] ) {
                        
                        $email = trim( filter_var( $_SESSION['facebook_object']['email'] , FILTER_SANITIZE_EMAIL) );
                        
                        if ( $email ) {
                            //check for existing email
                            $query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`".$this->table_fields[ 'email' ]."` = '" . $email . "' AND `" . $this->table_name . "`.`record_status`='1' ";
                            $query_settings = array(
                                'database' => $this->class_settings['database_name'] ,
                                'connect' => $this->class_settings['database_connection'] ,
                                'query' => $query,
                                'query_type' => 'SELECT',
                                'set_memcache' => 1,
                                'tables' => array( $this->table_name ),
                            );
                            $sql_result = execute_sql_query( $query_settings );
                            
                            if( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ){
                                //REGISTERED EMAIL ADDRESS
                                $this->class_settings['username'] = $email;
                                $this->class_settings['password'] = $this->default_password;
                                
                                $this->class_settings['skip_password'] = true;
                                
                                //Authenticate user with email only
                                //redirect to successful auth url
                                $returning_html_data = $this->_confirm_user_authentication_details();
                                if( isset( $returning_html_data['redirect_url'] ) ){
                                    header('Location: '.$returning_html_data['redirect_url'] );
                                    exit;
                                }
                            }
                            //set user email verification as verified
                            
                            /*
                            Array ( [id] => 111325829366867272256 [email] => pat2echo@gmail.com [verified_email] => 1 [name] => Patrick Ogbuitepu [given_name] => Patrick [family_name] => Ogbuitepu [link] => https://plus.google.com/111325829366867272256 [picture] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg [gender] => male [locale] => en ) Array ( [email] => pat2echo@gmail.com [name] => Patrick Ogbuitepu [id] => 111325829366867272256 [gender] => male [picture] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg [locale] => en )
                            */
                            //create user account & mail user default password
                            $data['firstname'] = filter_var( $_SESSION['facebook_object']['first_name'] , FILTER_SANITIZE_STRING);
                            $data['lastname'] = filter_var( $_SESSION['facebook_object']['last_name'] , FILTER_SANITIZE_STRING);
                            
                            $data['email'] = $email;
                            $data['sex'] = filter_var( $_SESSION['facebook_object']['gender'] , FILTER_SANITIZE_STRING);
                            
                            $data['verified_email_address'] = 'no';
                            if( $_SESSION['facebook_object']['email_source'] )
                                $data['verified_email_address'] = 'yes';
                            
                            $this->class_settings['user_data'] = $data;
                            
                            $this->_create_new_user_account_and_authenticate();
                            
                            unset($_SESSION['facebook_object']);
                        }else{
                            //prompt for user email
                            $script_compiler = new cScript_compiler();
                            $script_compiler->class_settings = $this->class_settings;
                            
                            $script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
                            $script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/facebook-no-email-prompt.php' );
                            echo $script_compiler->script_compiler();
                            exit;
                        }
                    }else{
                        //not logged in
                    }
                break;
                }
            }
            
        }
        
        private function _create_new_user_account_and_authenticate(){
            if( ! ( isset( $this->class_settings['user_data'] ) && $this->class_settings['user_data'] ) )return 0;
            
            $data = $this->class_settings['user_data'];
            
            $record_id = get_new_id();
            
            $settings_array = array(
                'database_name' => $this->class_settings['database_name'] ,
                'database_connection' => $this->class_settings['database_connection'] ,
                'table_name' => $this->table_name ,
                'field_and_values' => array(
                    
                    'id' => array( 'value' => $record_id ),
                    
                    'created_by' => array( 'value' => $record_id ),
                    'creation_date' => array( 'value' => date("U") ),
                    'modified_by' => array( 'value' => $record_id ),
                    'modification_date' => array( 'value' => date("U") ),
                    'ip_address' => array( 'value' => get_ip_address() ),
                    'record_status' => array( 'value' => 1 ),
                ) ,
            );
            
            if( ! ( isset( $data['password'] ) && $data['password'] ) ){
                $data['password'] = md5( $this->default_password . get_websalter());
                $data['confirmpassword'] = md5( $this->default_password . get_websalter());
            }
            
            if( ! ( isset( $data['role'] ) && $data['role'] ) ){
                $data['role'] = 'buyer';
            }
            
            foreach( $data as $k => $v ){
                if( isset( $this->table_fields[ 'address_fields' ][ $k ] ) && $this->table_fields[ 'address_fields' ][ $k ] )
                    $settings_array['field_and_values'][ $this->table_fields[ 'address_fields' ][ $k ] ] = array( 'value' => $v );
                else
                    $settings_array['field_and_values'][ $this->table_fields[ $k ] ] = array( 'value' => $v );
            }
            
            //if email does not exists register user
            $save = create( $settings_array );
            
            if( $save ){
                $this->class_settings['id'] = $record_id;
                $this->class_settings['where'] = "WHERE `id`='" . $this->class_settings['id'] . "'";
                $user_details = $this->_get_user_details();
                
                //redirect to successful registration url
                $this->class_settings['username'] = $user_details['email'];
                $this->class_settings['password'] = $user_details[ 'password' ];	
                $this->class_settings['skip_password'] = true;
                
                $auth_data = $this->_confirm_user_authentication_details();
                
                //send user account creation details + password
                //Send Welcome Email
                $this->class_settings['message_type'] = 1;
				
				$title = "";
				$msg = " ";
				$info = "";
				$role = strtolower( $user_details[ 'role' ] );
				
				switch( strtolower( $user_details[ 'role' ] ) ){
				case "teller":
					$title = "TELLER REGISTRATION DETAILS";
					$info = 'DEFAULT PASSWORD: <b>'.$this->default_password.'</b><br />';
				break;
				case "customer":
					$title = "SUCCESSFUL CUSTOMER REGISTRATION";
				break;
				case "merchant":
					$title = "SUCCESSFUL MERCHANT REGISTRATION";
				break;
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/email-message.php' );
				
				$this->class_settings[ 'data' ]["title"] = $title;
				$this->class_settings[ 'data' ]["message"] = $msg;
				$this->class_settings[ 'data' ]["info"] = $info;
				$this->class_settings[ 'data' ]["type"] = $role;
				
				$this->class_settings['message'] = $this->_get_html_view();
				$this->class_settings['subject'] = $title;
				
                $this->_send_email();
                
                //create pending task
                $this->_create_pending_task( $user_details );
                
                if( isset( $this->class_settings['skip_redirection'] ) && $this->class_settings['skip_redirection'] ){
                    return $record_id;
                }
                
                $general_settings_data = $this->_get_general_settings();
                
                if( isset( $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ][ 'default' ] ){
                    
                    $redirect_url = $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ]['default'];
                    header('Location: '.$redirect_url );
                    exit;
                }
    
                return $record_id;
            }
        }
        
        private function _get_all_registered_users(){
            $topic = 'all-registered-users';   //default all topics
            $limit = "";
            
            switch ( $this->class_settings['action_to_perform'] ){
            case 'get_newly_registered_users':
                $topic = 'newly-registered-users';
                $limit = " ORDER BY `creation_date` DESC LIMIT 0, 20 ";
            break;
            }
            /*
            $cache_settings = array(
				'cache_key' => $this->table_name.'-'.$topic,
				'directory' => $this->table_name,
				'permanent' => true,
			);
			$cached_values = get_cache_for_special_values( $cache_settings );
			if( $cached_values ){
				return $cached_values;
			}
			*/
            
			//Pull up user role record to get functions data
			$query = "SELECT `id`, `serial_num`, `creation_date`, `".$this->table_fields['email']."` as 'email' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' ".$limit;
			
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
				$cached_values = $sql_result;
			}
			
			$cache_settings = array(
				'cache_key' => $this->table_name.'-'.$topic,
				'cache_values' => $cached_values,
                'directory' => $this->table_name,
				'permanent' => true,
			);
			set_cache_for_special_values( $cache_settings );
			
			return $cached_values;
        }
        
        private function _quick_details_view(){
            $return = array();
            
            if( isset($_GET['id']) && $_GET['id'] ){
                $this->class_settings['user_id'] = $_GET['id'];
                
                $script_compiler = new cScript_compiler();
                $script_compiler->class_settings = $this->class_settings;
                $script_compiler->class_settings[ 'data' ]['user_details'] = $this->_get_user_details();
                
                $merchant_accounts = new cMerchant_accounts();
                $merchant_accounts->class_settings = $this->class_settings;
                $merchant_accounts->class_settings[ 'action_to_perform' ] = 'get_merchant_bank_accounts';
                $script_compiler->class_settings[ 'data' ]['merchant_accounts'] = $merchant_accounts->merchant_accounts();
                
                $script_compiler->class_settings[ 'data' ]['table_fields'] = $this->table_fields;
                
                $script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/quick-details-view.php' );
                $script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
                $return[ 'html' ] = $script_compiler->script_compiler();
                $return[ 'status' ] = 'got-quick-details-view';
                
                return $return;
            }
        }
    }
?>