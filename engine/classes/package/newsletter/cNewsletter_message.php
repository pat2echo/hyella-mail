<?php
	/**
	 * newsletter_message Class
	 *
	 * @used in  				newsletter_message Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	newsletter_message
	 */

	/*
	|--------------------------------------------------------------------------
	| newsletter_message Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cNewsletter_message{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'newsletter_message';
		
		private $associated_cache_keys = array(
			'newsletter_message',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		private $table_fields = array(
			'subject' => 'newsletter_message001',
			'message' => 'newsletter_message002',
			'date_last_sent' => 'newsletter_message003',
			'number_of_times_sent' => 'newsletter_message004',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 0,		//Determines whether or not to show advance search button
				'show_column_selector' => 0,	//Determines whether or not to show column selector button
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
	
		function newsletter_message(){
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
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case "new_record_popup_form":
				$returned_value = $this->_new_record_popup_form();
			break;
			case "save_new_record_popup_form":
				$returned_value = $this->_save_new_record_popup_form();
			break;
			case "display_message_view":
				$returned_value = $this->_display_message_view();
			break;
			case "view_recipients":
			case "view_failures":
			case "trash_message":
			case "cancel_sending_message":
			case "display_send_message":
			case "check_sending_status":
				$returned_value = $this->_display_send_message();
			break;
			case "send_message":
				$returned_value = $this->_send_message();
			break;
			case "save_message":
				$returned_value = $this->_save_message();
			break;
			}
			
			return $returned_value;
		}
		
		private function _save_message(){
			
			if( isset( $_POST["json"] ) && is_array( $_POST["json"] ) && ! empty( $_POST["json"] ) ){
				$cart_items = $_POST["json"];
				
				$error = '';
				if( ! ( isset( $cart_items["id"] ) && $cart_items["id"] ) ){
					$error = '<h4>Invalid Newsletter</h4>You must select your message';
				}
				
				if( ! ( isset( $cart_items["message"] ) && $cart_items["message"] ) ){
					$error = '<h4>Specify Newsletter Message</h4>You must specify the message you want to send';
				}
				
				if( $error ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = $error;
					return $err->error();
				}
				
				$_POST["id"] = $cart_items["id"];
				$this->class_settings["update_fields"]["message"] = $cart_items["message"];
				$return = $this->_update_table_field();
				
				if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
					unset( $return["html"] );
					//$return["status"] = "new-status";
					//$pr = get_project_data();
					//$return["redirect_url"] = $pr["domain_name"];
				}
				return $return;
			}
			
			$error = '<h4>No Items Selected</h4>Specify Items to Order';
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $error;
			return $err->error();
		}
		
		private function _update_table_field(){
			$applicant = array();
			$return = array();
			
			if( ! isset( $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
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
			
			return $return;
		}
		
		private function _send_message(){
			
			if( isset( $_POST["json"] ) && is_array( $_POST["json"] ) && ! empty( $_POST["json"] ) ){
				$cart_items = $_POST["json"];
				
				$error = '';
				if( ! ( isset( $cart_items["recipients"] ) && $cart_items["recipients"] ) ){
					$error = '<h4>Specify Recipients</h4>You must specify recipients for your message';
				}
				
				if( ! ( isset( $cart_items["sending_channel"] ) && $cart_items["sending_channel"] ) ){
					$error = '<h4>Specify Sending Channel</h4>You must specify sending channel';
				}else{
					
					if( ! ( isset( $cart_items["username"] ) && $cart_items["username"] ) ){
						$error = '<h4>Specify Username</h4>You must specify the username for the sending channel';
					}
					
					if( ! ( isset( $cart_items["password"] ) && $cart_items["password"] ) ){
						$error = '<h4>Specify Passowrd</h4>You must specify the password for the sending channel';
					}
					
				}
				
				if( ! ( isset( $cart_items["message"] ) && $cart_items["message"] ) ){
					$error = '<h4>Specify Newsletter Message</h4>You must specify the message you want to send';
				}
				
				if( $error ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = $error;
					return $err->error();
				}
					
				$sending_channel = $cart_items["sending_channel"];
				$mailing_list = $cart_items["recipients"];
				$msg_id = $cart_items["message"];
				
				$this->class_settings["current_record_id"] = $msg_id;
				$message = $this->_get_newsletter_message();
				if( ! isset( $message["id"] ) ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Newsletter</h4>Please select a newsletter by first clicking on it';
					return $err->error();
				}
				
				$s = new cNewsletter_subscribers();
				$s->class_settings = $this->class_settings;
				$s->class_settings["category"] = $mailing_list;
				$s->class_settings["action_to_perform"] = "get_subscribers";
				$subs = $s->newsletter_subscribers();
				
				if( empty( $subs ) ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Subscribers</h4>No Subscriber found for the selected category';
					return $err->error();
				}
				
				$data = array(
					"id" => $msg_id,
					"subject" => $message["subject"],
					"message" => $message["message"],
					"sending_channel" => $sending_channel,
					"e" => $cart_items["username"],
					"p" => $cart_items["password"],
					"mailing_list" => $subs,
					"mailing_name" => $mailing_list,
					"time" => date("U"),
				);
				
				$data["k"] = $data["id"] . $data["sending_channel"] . $data["e"] . $data["mailing_name"];
				$message_key = $data["k"];
				
				//e: check if message has been queued
				$cache_key = $this->table_name;
				
				$settings = array(
					'cache_key' => $cache_key . '-q-' . $msg_id,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				$datas = get_cache_for_special_values( $settings );
				if( ! is_array( $datas ) ){
					$datas = array();
				}
				
				$queued = 0;
				if( isset( $datas[ $message_key ] ) && ! isset( $datas[ $message_key ]["complete"] ) ){
					//message already in progress
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Message is already being sent</h4>The selected message is already been sent to the specificed recipients';
					return $err->error();
				}
				
				//set in cache
				$datas[ $message_key ] = $data;
				
				$settings = array(
					'cache_key' => $cache_key . '-q-' . $msg_id,
					'directory_name' => $cache_key,
					'cache_values' => $datas,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				//e: commence sending operation
				$url = 'http://localhost:819/feyi/engine/php/send_emails.php';
				$r = file_get_contents( $url . '?app=1&send=1&message=' . $msg_id . '&key='.$message_key );
				
				/*
				$r = json_decode( $r, true );
				
				return array(
					'html_replacement_selector' => "#sending-status-container",
					'html_replacement' => $r["message"],
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ) 
				);
				*/
				
				$j = json_decode( $r, true );
				
				if( isset( $j["error"] ) ){
					clear_cache_for_special_values( $settings );
					
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Error When Sending Request</h4>' . $j["error"];
					return $err->error();
				}
				
				//return progress bar
				$this->class_settings[ 'data' ][ "message" ] = $data;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/display-message.php' );
				$returning_html_data = $this->_get_html_view();
				
				//trigger auto refresh
				
				return array(
					'html_replacement_selector' => "#msg-new-container",
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event', 'nwNewsletter_message.delayCheckStatus' ) 
				);
			}
			
			$error = '<h4>No Items Selected</h4>Specify Items to Order';
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $error;
			return $err->error();
		}
		
		private function _display_send_message(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Newsletter</h4>Please select a newsletter by first clicking on it';
				return $err->error();
			}
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$message = $this->_get_newsletter_message();
			if( ! isset( $message["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Newsletter</h4>Please select a newsletter by first clicking on it';
				return $err->error();
			}
			
			$checked_status = 0;
			$replacement_handle1 = "";
			$returning_html_data1 = "";
					
			$replacement_handle = "#dash-board-main-content-area";
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/display-send-message' );
			$this->class_settings[ 'data' ][ "message_details" ] = $message;
			
			$js = array( 'set_function_click_event' );
			
			$msg_id = $message["id"];
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key . '-q-' . $msg_id,
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			$datas = get_cache_for_special_values( $settings );
			
			$specific_key = "";
			switch( $this->class_settings["action_to_perform"] ){
			case "check_sending_status":
				$replacement_handle = "#msg-sending-status-container";
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/display-messages.php' );
				
				if( ( isset( $_POST["mod"] ) && $_POST["mod"] ) ){
					$specific_key = $_POST["mod"];
					$replacement_handle = "#message-" . md5( $specific_key );
				}else{
					$replacement_handle1 = "#msg-new-container";
					$returning_html_data1 = "&nbsp;";
				}
				
			break;
			case "view_recipients":
			case "view_failures":
			case "trash_message":
			case "cancel_sending_message":
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/display-messages.php' );
				
				if( ( isset( $_POST["mod"] ) && $_POST["mod"] ) ){
					$specific_key = $_POST["mod"];
					$replacement_handle = "#message-" . md5( $specific_key );
				}else{
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Newsletter</h4>You must specify the exact queued newsletter';
					return $err->error();
				}
			break;
			}
			
			if( $datas && is_array( $datas ) ){
				foreach( $datas as $key => & $data ){
					if( ! isset( $data["k"] ) ){
						unset( $datas[ $key ] );
						continue;
					}
					
					if( $specific_key && $specific_key != $data["k"] ){
						continue;
					}
					
					switch( $this->class_settings["action_to_perform"] ){
					case "trash_message":
						unset( $datas[ $key ] );
						$settings = array(
							'cache_key' => $cache_key . '-q-' . $msg_id,
							'directory_name' => $cache_key,
							'cache_values' => $datas,
							'permanent' => true,
						);
						set_cache_for_special_values( $settings );
						
						$err = new cError(010011);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = '_resend_verification_email';
						$err->additional_details_of_error = '<h4>Successfully Deleted</h4>';
						$return = $err->error();
						
						unset( $return["html"] );
						$return["status"] = "new-status";
						$return["html_removal"] = $replacement_handle;
						
						return $return;
					break;
					case "view_recipients":
						if( isset( $data["mailing_list"] ) && is_array( $data["mailing_list"] ) && ! empty( $data["mailing_list"] ) ){
							
							$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/email-report.php' );
							$this->class_settings[ 'data' ]['emails'] = $data["mailing_list"];
							
							if( isset( $data["status"]["fails"] ) && is_array( $data["status"]["fails"] ) && ! empty( $data["status"]["fails"] ) ){
								$this->class_settings[ 'data' ]['failed_emails'] = $data["status"]["fails"];
							}
							
							$html = $this->_get_html_view();
							
							$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/zero-out-negative-budget' );
							$this->class_settings[ 'data' ]["html_title"] = 'Sending Report';
							$this->class_settings[ 'data' ]['html'] = $html;
							
							$returning_html_data = $this->_get_html_view();
							
							return array(
								'do_not_reload_table' => 1,
								'html_prepend' => $returning_html_data,
								'html_prepend_selector' => "#dash-board-main-content-area",
								'method_executed' => $this->class_settings['action_to_perform'],
								'status' => 'new-status',
							);
						}else{
							$err = new cError(010014);
							$err->action_to_perform = 'notify';
							$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
							$err->method_in_class_that_triggered_error = '_resend_verification_email';
							$err->additional_details_of_error = '<h4>No Failed Emails</h4>Could not retrieve failed emails';
							return $err->error();
						}
					break;
					case "view_failures":
						if( isset( $data["status"]["fails"] ) && is_array( $data["status"]["fails"] ) && ! empty( $data["status"]["fails"] ) ){
							
							$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/email-report.php' );
							$this->class_settings[ 'data' ]['failed_emails'] = $data["status"]["fails"];
							$html = $this->_get_html_view();
							
							$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/zero-out-negative-budget' );
							$this->class_settings[ 'data' ]["html_title"] = 'Failed Emails';
							$this->class_settings[ 'data' ]['html'] = $html;
							
							$returning_html_data = $this->_get_html_view();
							
							return array(
								'do_not_reload_table' => 1,
								'html_prepend' => $returning_html_data,
								'html_prepend_selector' => "#dash-board-main-content-area",
								'method_executed' => $this->class_settings['action_to_perform'],
								'status' => 'new-status',
							);
						}else{
							$err = new cError(010014);
							$err->action_to_perform = 'notify';
							$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
							$err->method_in_class_that_triggered_error = '_resend_verification_email';
							$err->additional_details_of_error = '<h4>No Failed Emails</h4>Could not retrieve failed emails';
							return $err->error();
						}
					break;
					case "cancel_sending_message":
						$url = 'http://localhost:819/feyi/engine/php/send_emails.php';
						$r1 = file_get_contents( $url . '?app=1&cancel=1&message=1&key='.$data["k"] );
						$j = json_decode( $r1 , true );
						if( isset( $j["cancelled"] ) ){
							$data["status"] = $j;
							$data["complete"] = 1;
						}else{
							$err = new cError(010014);
							$err->action_to_perform = 'notify';
							$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
							$err->method_in_class_that_triggered_error = '_resend_verification_email';
							$err->additional_details_of_error = '<h4>Cancellation Failed</h4>Please try again';
							return $err->error();
						}
					break;
					default:
						if( ! isset( $data["complete"] ) ){
							//check status
							$url = 'http://localhost:819/feyi/engine/php/send_emails.php';
							$r1 = file_get_contents( $url . '?app=1&check=1&message=1&key='.$data["k"] );
							
							if( $r1 ){
								$j = json_decode( $r1 , true );
								if( isset( $j["total"] ) && isset( $j["sent"] ) ){
									$data["status"] = $j;
									
									if( intval( $j["total"] ) == ( intval( $j["sent"] ) + intval( $j["fail"] ) ) ){
										$data["complete"] = 1;
									}
									
									$checked_status = 1;
								}
							}
						}
					break;
					}
					
				}
				
				$s = new cNewsletter_subscribers();
				$s->class_settings = $this->class_settings;
				$s->class_settings["action_to_perform"] = 'get_categories';
				$this->class_settings[ 'data' ][ "recipients" ] = $s->newsletter_subscribers();
				
				$this->class_settings[ 'data' ][ "messages" ] = $datas;
				
				$settings = array(
					'cache_key' => $cache_key . '-q-' . $msg_id,
					'directory_name' => $cache_key,
					'cache_values' => $datas,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
			}
			
			if( $checked_status ){
				$js[] = 'nwNewsletter_message.delayCheckStatus';
			}
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector_one' => $replacement_handle,
				'html_replacement_one' => $returning_html_data,
				
				'html_replacement_selector' => $replacement_handle1,
				'html_replacement' => $returning_html_data1,
				
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js, 
			);
		}
		
		private function _display_message_view(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Newsletter</h4>Please select a newsletter by first clicking on it';
				return $err->error();
			}
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$message = $this->_get_newsletter_message();
			
			if( ! isset( $message["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Newsletter</h4>Please select a newsletter by first clicking on it';
				return $err->error();
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/display-message-view' );
			$this->class_settings[ 'data' ][ "message" ] = $message;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
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
			$this->_get_newsletter_message();
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/custom-buttons.php' );
			$this->datatable_settings['custom_edit_button'] = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/display-all-records-full-view' );
			
			$datatable = $this->_display_data_table();
			//$form = $this->_generate_new_data_capture_form();
			
			//$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Newsletters";
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
				$this->class_settings['form_heading_title'] = 'Modify Newsletters';
				//$this->class_settings['hidden_records'][ $this->table_fields["member_id"] ] = 1;
			break;
			}
			$this->class_settings['hidden_records'][ $this->table_fields["number_of_times_sent"] ] = 1;
			$this->class_settings['hidden_records'][ $this->table_fields["date_last_sent"] ] = 1;
			//$this->class_settings['hidden_records'][ $this->table_fields["message"] ] = 1;
			
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
				$this->_get_newsletter_message();
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
				
				$err->class_that_triggered_error = 'cnewsletter_message.php';
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
					$record = $this->_get_newsletter_message();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_newsletter_message(){
			
			$cache_key = $this->table_name;
			
			if( ! isset( $this->class_settings[ 'current_record_id' ] ) )return 0;
			
			$settings = array(
				'cache_key' => $cache_key.'-'.$this->class_settings[ 'current_record_id' ],
				'directory_name' => $cache_key,
				'permanent' => true,
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
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `modified_by`, `created_by`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `id` = '".$this->class_settings[ 'current_record_id' ]."'";
			
			if( $this->class_settings[ 'current_record_id' ] == 'pass_condition' ){
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ";
			}
			
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