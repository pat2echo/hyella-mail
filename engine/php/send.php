<?php 
	$pagepointer = '../';
    $display_pagepointer = '';
	//require_once $pagepointer . "settings/Setup.php";
	$r = array( "error" => 'Invalid Request' );
	define("PLATFORM", "windows");
	
	$queue = json_decode( file_get_contents( 'mail-status/' . 'queue.json' ) , true );
	if( isset( $queue[0] ) ){
		$message_key = $queue[0];
		unset( $queue[0] );
		file_put_contents( 'mail-status/' . 'queue.json', json_encode( $queue ) );
		
		$s = 1;
		if( file_exists( 'mail-status/' . $message_key . '.json' ) ){
			$r = json_decode( file_get_contents( 'mail-status/' . $message_key . '.json' ), true );
			$data = array(
				"sender_email",
				"subject",
				"message",
				"sending_channel",
				"e",
				"p",
				"mailing_list",
				"sender_fullname",
				"admin_email",
				"project_title",
			);
			
			$s = 0;
			foreach( $data as $v ){
				if( ! ( isset( $r[ $v ] ) && $r[ $v ] ) ){
					$r = array( "error" => 'Unable to Retrieve Newsletter Info: '.$v );
					$s = 1;
					break;
				}
			}
		}
		
		if( ! $s ){
			//echo $r["message"];
			//echo "jon";
			//exit;
			
			switch( $r[ "sending_channel" ] ){
			case "gmail":
				require_once $pagepointer.'classes/PHPMailer-master/PHPMailerAutoload.php';
			break;
			}
			
			$state = array(
				"total" => count( $r["mailing_list"] ),
				"sent" => 0,
				"fail" => 0,
				"fails" => array(),
			);
			
			//clear previous queue
			if( file_exists( 'mail-status/' . $message_key . '-status.json' ) ){
				unlink( 'mail-status/' . $message_key . '-status.json' );
			}
			
			foreach( $r["mailing_list"] as $sub ){
				//check for cancellations
				$cstate = array();
				if( file_exists( 'mail-status/' . $message_key . '-status.json' ) ){
					$cstate = json_decode( file_get_contents( 'mail-status/' . $message_key . '-status.json' ), true );
				}
				
				if( isset( $cstate["cancelled"] ) && $cstate["cancelled"] ){
					break;
				}else{
					$recipient_emails[0] = $sub["email"];
					$recipient_fullnames[0] = $sub["email"];
					
					$status = __gmail( $r["e"] , $r["p"] , $r["sender_email"], $r["sender_fullname"], $r["admin_email"], $r["project_title"], $recipient_emails, $recipient_fullnames, $r["subject"], $r["message"] );
					//$status =  "Message sent!";
					
					if( $status ==  "Message sent!" ){
						$state["sent"] += 1;
					}else{
						$state["fail"] += 1;
						$state["fails"][] = $sub["email"];
					}
					$state["last_sent"] = date("U");
					file_put_contents( 'mail-status/' . $message_key . '-status.json', json_encode( $state ) );
					
					sleep( 10 );
				}
			}
		}
		
	}else{
		file_put_contents( 'queue.json', '' );
	}
	
	exit;
	
	function __gmail( $smtp_auth_email , $smtp_auth_password , $sender_email, $sender_fullname, $admin_email, $project_title, $recipient_emails, $recipient_fullnames, $subject, $message ){
		//Create a new PHPMailer instance
		$mail = new PHPMailer();

		//Tell PHPMailer to use SMTP
		$mail->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;

		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';

		//Set the hostname of the mail server
		$mail->Host = 'smtp.gmail.com';
		//$mail->Host = 'ssl://smtp.gmail.com';
		//$mail->Host = 'smtp.ipage.com';
		
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = 587;
		//$mail->Port = 465;

		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = 'tls';

		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = $smtp_auth_email;

		//Password to use for SMTP authentication
		$mail->Password = $smtp_auth_password;

		//Set who the message is to be sent from
		$mail->setFrom( $sender_email , $sender_fullname );

		//Set an alternative reply-to address
		$mail->addReplyTo($admin_email , $project_title);

		//Set who the message is to be sent to
		$mails = '';
		foreach( $recipient_emails as $id => $email ){
			$mail->addAddress( $email , $recipient_fullnames[ $id ] );
			$mails .= $email;
		}

		//Set the subject line
		$mail->Subject = $subject;

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML( $message );

		//Replace the plain text body with one created manually
		//$mail->AltBody = 'This is a plain-text message body';

		//Attach an image file
		if( isset( $settings['attachments'] ) && is_array( $settings['attachments'] ) && ! empty( $settings['attachments'] ) ){
			foreach( $settings['attachments'] as $attach )
				$mail->addAttachment( $attach );
		}
		//$mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		$response = "Mailer Unsent";
		$status = 0;
		
		if ( ! $mail->send()) {
			$response = "Mailer Error: " . $mail->ErrorInfo;
			$status = 0;
		} else {
			$status = 1;
			$response = "Message sent!";
		}
		
		return $response;
	}
	
?>