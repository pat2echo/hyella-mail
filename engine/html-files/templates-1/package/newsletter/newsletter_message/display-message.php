<?php 
	if( isset( $data["message"]["subject"] ) ){
		$percentage = 1;
		
		$state = 'SENDING...';
		$slbl = '';
		$show_failure = 0;
		$show_success = 0;
		
		if( isset( $data["message"]["status"]["sent"] ) && isset( $data["message"]["status"]["total"] ) && isset( $data["message"]["status"]["fail"] ) ){
			$slbl = 'Sent to recipient number ' . $data["message"]["status"]["sent"] . ' of ' . $data["message"]["status"]["total"] . ' | ' . $data["message"]["status"]["fail"] . ' failures<br />';
			
			if( $data["message"]["status"]["total"] ){
				$percentage = ceil( ( ( intval( $data["message"]["status"]["sent"] ) + intval( $data["message"]["status"]["fail"] ) ) / intval( $data["message"]["status"]["total"] ) ) * 100 ); 
			}
			
			if( isset( $data["message"]["status"]["fails"] ) && is_array( $data["message"]["status"]["fails"] ) && ! empty( $data["message"]["status"]["fails"] ) ){
				$show_failure = count( $data["message"]["status"]["fails"] );
			}
		}
		
		if( isset( $data["message"]["complete"] ) ){
			$percentage = 100;
			$state = 'COMPLETED <i class="icon-check"></i>';
		}
		
		if( isset( $data["message"]["status"]["cancelled"] ) ){
			$percentage = 100;
			$state = 'CANCELLED';
			if( doubleval( $data["message"]["status"]["cancelled"] ) ){
				$state .= date(" @ d-M-Y H:i", doubleval( $data["message"]["status"]["cancelled"] ) );
			}
		}
?>
<div id="message-<?php echo md5( $data["message"]["k"] ); ?>">
<div class="note note-warning">
<h4 class="block">Sending: <?php echo $data["message"]["subject"]; ?> @<?php echo date("d-M-Y H:i", doubleval( $data["message"]["time"] ) ); ?></h4>
<code class="pull-right">STATUS: <strong><?php echo $state; ?></strong></code>
<p>
  Activated By: XYZ<br />
  Sending via: <?php echo $data["message"]["sending_channel"]; ?> | <?php echo $data["message"]["e"]; ?><br />
  <?php echo $slbl; ?>
  
  <?php if( $percentage < 100 ){ ?>
  <div style="height:8px;" class="progress progress-striped active">
	  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%">
		   <span class="sr-only"><?php echo $percentage; ?>% Complete (danger)</span>
	  </div>
  </div>
  <?php } ?>
  
</p>

<?php if( $percentage < 100 ){ ?>
<a href="#" class="btn dark btn-sm custom-single-selected-record-button" action="?module=&action=newsletter_message&todo=check_sending_status" override-selected-record="<?php echo $data["message"]["id"]; ?>" mod="<?php echo $data["message"]["k"]; ?>">Check Status</a>

<a href="#" class="btn red btn-sm custom-single-selected-record-button" action="?module=&action=newsletter_message&todo=cancel_sending_message" override-selected-record="<?php echo $data["message"]["id"]; ?>" mod="<?php echo $data["message"]["k"]; ?>">Cancel Operation</a>
 <?php }else{ ?>
 
	 <?php if( $show_failure ){ ?>
	 <a href="#" class="btn dark btn-sm custom-single-selected-record-button" action="?module=&action=newsletter_message&todo=view_failures" override-selected-record="<?php echo $data["message"]["id"]; ?>" mod="<?php echo $data["message"]["k"]; ?>">View Failed Emails</a>
	 <?php } ?>
 
 <a href="#" class="btn red btn-sm custom-single-selected-record-button" action="?module=&action=newsletter_message&todo=trash_message" override-selected-record="<?php echo $data["message"]["id"]; ?>" mod="<?php echo $data["message"]["k"]; ?>"><i class="icon-trash"></i> Trash</a>
 
 <?php } ?>
 
<a href="#" class="btn dark btn-sm pull-right custom-single-selected-record-button" action="?module=&action=newsletter_message&todo=view_recipients" override-selected-record="<?php echo $data["message"]["id"]; ?>" mod="<?php echo $data["message"]["k"]; ?>">View Recipients [ <?php echo ucwords( $data["message"]["mailing_name"] ); ?> ]</a>
  
</div>
</div>
<?php 
	}
?>