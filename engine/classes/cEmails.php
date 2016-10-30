<?php
	/**
	 * Emails Sending Class
	 *
	 * @used in  				/classes/*.php
	 * @created  				19:11 | 27-12-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Sends emails to users on execution of various actions
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cEmails{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'emails';
		
		public $mail_certificate = array();
		public $mail_template = '';
		
		public $sender = array();
		
		private $table = 'emails';
		
		public $saved = 0;
		
		public $message_type = 0;
		
		function emails(){
			//INITIALIZE RETURN VALUE
			$return = '';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'send_mail':
				$return = $this->_send_mail();
			break;
			case 'emails_log':
				$return = $this->_emails_log();
			break;
			case 'view_today_transaction_status':
			case 'display_all_records_full_view':
				$return = $this->_display_all_records_full_view();
			break;
			}
			
			return $return;
		}
		
		private function _send_mail(){
			$h = '';
			$hbc = '';
			
			//foreach($this->class_settings['destination']['email'] as $k => $to){
				$message = $this->class_settings['message'];
				$subject = $this->class_settings['subject'];
				
                /*
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				// Additional headers
				$headers .= 'To: '.$this->class_settings['destination']['full_name'][$k].' <'.$to.'>' . "\r\n";
				$headers .= 'From: '.$this->class_settings['project_data']['project_name'].' <'.$this->class_settings['project_data']['email'].'>' . "\r\n";
				$headers .= 'Bcc: '.$this->class_settings['project_data']['admin_email'] . "\r\n";
				*/
				//Exempt Particular Emails
				$attachments = array();
				switch($this->class_settings['message_type']){
				case 99:
					//create pdf attachment
					/*
					$my_pdf = new cMypdf();
					$my_pdf->class_settings = $this->class_settings;
					$my_pdf->class_settings[ 'action_to_perform' ] = 'create';
					
					$my_pdf->html = $message;
					$my_pdf->filename = $this->class_settings["calling_page"] . "tmp/sent_mails/attach.pdf";
					$my_pdf->mypdf();
					
					if( file_exists( $my_pdf->filename ) )
						$attachments[] = $my_pdf->filename;
					*/
					if( isset( $this->class_settings['attachment'] ) )
						$attachments = $this->class_settings['attachment'];
				break;
				case 2:		//Account Verification Email after registration
				case 15:	//Admin Login Token
				case 23:	//Send Pick-up Email to Delivery Agent
				case 40:	//Send Pick-up Email to Delivery Agent
				break;
				default:
					//Log Notification
					$this->class_settings['notification_data'] = array(
						'title' => $this->class_settings['subject'],
						'detailed_message' => strip_tags( $message , '<ul><ol><li><p><a><br><div><span><h1><h2><h3><h4><h5><h6><hr><table><td><tr><th><tbody><thead><b><strong><i><u><small><i><u><super><sub>' ),
						'send_email' => 'no',
						'notification_type' => 'no_task',
						'target_user_id' => $this->class_settings['user_id'],
						'class_name' => $this->table_name,
						'method_name' => 'send_mail',
					);
					
					$notifications = new cNotifications();
					$notifications->class_settings = $this->class_settings;
					$notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
					$notifications->notifications();
				break;
				}
                
				send_mail( array(
                    'pagepointer' => $this->class_settings[ 'calling_page' ],
                    'subject' => $subject,
                    'message' => $message,
                    'attachments' => $attachments,
                    'recipient_fullnames' => $this->class_settings['destination']['full_name'],
                    'recipient_emails' => $this->class_settings['destination']['email'],
                ) );
				/*
				if( ! empty( $attachments ) ){
					foreach( $attachments as $attach )unlink( $attach );
				}
				*/
			//}
		}
        
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/notice.php' );
			$notice = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/form.php' );
			$form = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			//$datatable = $this->_display_data_table();
			//$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['form_data'] = $form;
			$this->class_settings[ 'data' ]['html'] = $notice;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['title'] = "Email Reports";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
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
		
		private function _emails_log(){
			$return = array();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'view_today_transaction_status':
				$_POST["date"] = date("Y-m-D");
			break;
			}
			
			$emails = array();
			$date = '';
			$title = '';
			if( isset( $_POST["date"] ) && $_POST["date"] ){
				$date = $_POST["date"];
				$title = "Showing Emails Sent On ".$date;
				
				//check for all mails within 24hour period
				$ds = explode( "-", $date );
				if( isset( $ds[0] ) && isset( $ds[2] ) ){
					$stop_key = $this->table_name . date( $ds[2]."-".$ds[1]."-".$ds[0]."-" );
					for( $i = 0; $i < 24; $i++ ){
						if( $i < 10 )$k = $stop_key."0".$i;
						else $k = $stop_key.$i;
						
						$cache = get_cache_for_special_values( array( 'cache_key' => $k, 'directory_name' => $this->table_name, 'permanent' => true ) );
						if( is_array( $cache ) && ! empty( $cache ) ){
							$emails[ $i.":00" ] = $cache;
						}
					}
				}
				
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/report.php' );
			$this->class_settings[ 'data' ] = array( 
				'emails' => $emails,
				'date' => $date,
				'title' => $title,
				'total' => 0,
			);
			
			$return['html_replacement'] = $this->_get_html_view();
			$return['html_replacement_selector'] = "#data-table-section";
            
            $return['status'] = 'new-status';
			
			return $return;
		}
		
	}
?>