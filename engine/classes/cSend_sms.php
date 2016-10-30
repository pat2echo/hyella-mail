<?php
	/**
	 * send_sms Trail Class
	 *
	 * @used in  				send_sms Trail Function
	 * @created  				19:57 | 22-01-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| send_sms Trail Function in Users Manager Module
	|--------------------------------------------------------------------------
	|
	| Stores log of all users activites and create JSON files of such logs at
	| intervals of 24 hours, and creates snapshots of the database
	|
	*/
	
	class cSend_sms{
		public $table_name = 'send_sms';
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		//Table that contained records
		public $table = '';
		
		//Comment describing action performed by user
		public $comment = '';
		
		private $database_name_store = 'db';
		private $tmail = 'trail.gashelix@gmail.com';
		private $trail = 'tr';
		
		private $trail_json = 'send_sms_logs';
		
		public $saved = 0;
		
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
		
		function send_sms(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'send_sms':
				$returned_value = $this->_send_sms();
			break;
			}
			
			return $returned_value;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/notice.php' );
			$notice = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$this->datatable_settings[ 'show_add_new' ] = 0;
            $this->datatable_settings[ 'show_edit_button' ] = 0;
            
			$this->datatable_settings[ 'show_delete_button' ] = 1;
			$this->datatable_settings[ 'show_advance_search' ] = 1;
			$this->datatable_settings[ 'custom_view_button' ] = '';
			
			//$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['form_data'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $notice;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['title'] = "send_sms Trail";
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
		
		private function _send_sms(){
			/*
			$settings = array(
				'cache_key' => "sms",
			);
			$token = get_cache_for_special_values( $settings );
			
			if( ! ( isset( $token ) && $token ) ){
				$token = file_get_contents("http://www.smslive247.com/http/index.aspx?cmd=login&owneremail=lrcn.gov.ng@gmail.com&subacct=LRCN&subacctpwd=mrcharles");
				
				$p = explode(":", $token);
				if ( isset( $p[0] ) && strtolower( $p[0] ) == "ok" ) { //no error
					
					$settings = array(
						'cache_key' => "sms",
						'cache_values' => trim( $p[1] ),
					);
					set_cache_for_special_values( $settings );
					$token = trim( $p[1] );
				} 
			}
			*/
			
			//$this->class_settings["phone"] = "08052529580";
			//$this->class_settings["message"] = "Loria";
			$token = 1;
			
			if( isset( $this->class_settings["phone"] ) && ( strlen( $this->class_settings["phone"] ) == 11 || strlen( $this->class_settings["phone"] ) == 14 ) ){
				if( isset( $this->class_settings["message"] ) && $this->class_settings["message"] ){
					$this->class_settings["message"] .= '. Check your email for further info.';
					
					if( isset( $token ) && $token ){
						$url = "http://www.smslive247.com/http/index.aspx?cmd=sendquickmsg&owneremail=lrcn.gov.ng@gmail.com&subacct=LRCN&subacctpwd=mrcharles&sessionid=".$token."&message=".rawurlencode( $this->class_settings["message"] )."&sender=LRCN&sendto=".$this->class_settings["phone"]."&msgtype=0";
						//call the SendSMS method
						$r = file_get_contents( $url );
						
						//$r = file_get_contents( "http://www.smslive247.com/http/index.aspx?cmd=sendquickmsg&sessionid=".$token."&message=".$this->class_settings["message"]."&sender=LRCN&sendto=".$this->class_settings["phone"]."&msgtype=0" );
						
						$p = explode(":", $r);
						if ( isset( $p[0] ) && strtolower( $p[0] ) == "ok" ) {
							return 1;
						}
					}
				}
			}
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'csend_sms.php';
			$err->method_in_class_that_triggered_error = '_sync_progress';
			$err->additional_details_of_error = '<h4>SMS Failed</h4>';
			return $err->error();
			
		}
	}
?>