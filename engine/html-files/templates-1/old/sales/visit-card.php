<div id="visit-card-container-<?php echo $data[ 'visitor_info' ][ 'id' ]; ?>">
<div class="tile double double-down-flex <?php if( $data[ 'visitor_info' ][ 'approval_status' ] == 'approved' ){ ?>bg1-dark <?php }else{ ?>bg-dark <?php } ?> ajax-request ajax-request-modals" href="<?php echo $site_url; ?>?page=view_details" data-internalcard="1" data-id="<?php echo $data[ 'visitor_info' ][ 'id' ]; ?>" action="visit_schedule" todo="view_details" data-href="#notlong" data-toggle="modal">
   <div class="tile-body" title="<?php echo $data[ 'visitor_info' ][ 'full_name' ]; ?>">
	  <img src="<?php echo $site_url . $data[ 'visitor_info' ][ 'photograph' ]; ?>"  width="90px" alt="<?php echo $data[ 'visitor_info' ][ 'full_name' ]; ?>" alt="" class="pull-right">
	  <h4 style="line-height:1.1;"><?php echo return_first_few_characters_of_a_string( $data[ 'visitor_info' ][ 'full_name' ] , 25 ); ?></h4>
	  <p style="line-height:1;">
		<small><small><a href="tel:<?php echo $data[ 'visitor_info' ][ 'phone_number' ]; ?>"><?php echo $data[ 'visitor_info' ][ 'phone_number' ]; ?></a>, <a href="mailto:<?php echo $data[ 'visitor_info' ][ 'email' ]; ?>"><?php echo $data[ 'visitor_info' ][ 'email' ]; ?></a></small></small>
	  </p>
	  <hr class="half-margin" />
	  <p title="<?php echo $data[ 'visitor_info' ][ 'reason_for_visit' ]; ?>">
		@<small><?php echo $data[ 'host_info' ]['lastname'] . ' '. substr( $data[ 'host_info' ]['firstname'] , 0, 1 ) . ' ' . ' ('. $data[ 'host_info' ]['phone_number'] .')'; ?></small><br />
		<?php echo return_first_few_characters_of_a_string( $data[ 'visitor_info' ][ 'reason_for_visit' ] , 50 ); ?>
	  </p>
	  <p>
		 
	  </p>
   </div>
   <div class="tile-object">
	  <div class="name">
		<?php if( $data[ 'visitor_info' ][ 'approval_status' ] == 'cancelled' || $data[ 'visitor_info' ][ 'approval_status' ] == 'declined' || $data[ 'visitor_info' ][ 'approval_status' ] == 'pending' ){ ?>
		<a href="<?php echo str_replace("engine/","", $site_url); ?>?page=approve&record_id=<?php echo $data[ 'visitor_info' ][ "id" ]; ?>" data-internalcard="1" data-id="<?php echo $data[ 'visitor_info' ][ 'id' ]; ?>" action="visit_schedule" todo="approve" class="ajax-request btn btn-sm green ajax-request-modals" data-href="#notlong" data-toggle="modal">Approve</a>
		<?php } ?>
		
		<?php if( $data[ 'visitor_info' ][ 'approval_status' ] == 'approved' || $data[ 'visitor_info' ][ 'approval_status' ] == 're_scheduled' || $data[ 'visitor_info' ][ 'approval_status' ] == 'postponed' || $data[ 'visitor_info' ][ 'approval_status' ] == 'pending' ){ ?>
		<a href="<?php echo str_replace("engine/","", $site_url); ?>?page=decline&record_id=<?php echo $data[ 'visitor_info' ][ "id" ]; ?>" data-internalcard="1" data-id="<?php echo $data[ 'visitor_info' ][ 'id' ]; ?>" action="visit_schedule" todo="decline" class="ajax-request btn btn-sm red ajax-request-modals" data-href="#notlong" data-toggle="modal">Deny</a>
		<?php } ?>
	  </div>
	  <div class="number">
		 <small style="display:block; margin-bottom:5px; font-weight:normal;"><small>
		<?php 
			$icons = get_approval_status_icons();
			if( isset( $icons[ $data[ 'visitor_info' ][ 'approval_status' ] ] ) )
				echo $icons[ $data[ 'visitor_info' ][ 'approval_status' ] ]." ";
		?>
		 <?php echo get_select_option_value( array( 'id' => $data[ 'visitor_info' ][ 'approval_status' ], 'function_name' => 'get_approval_status' ) ); ?>
		 </small></small>
		 <?php
			if( doubleval( $data[ 'visitor_info' ][ 'approved_start_date_time' ] ) && doubleval( $data[ 'visitor_info' ][ 'approved_start_date_time' ] ) != doubleval( $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ) ){
		?>
		<?php echo date( "d-M-Y", doubleval( $data[ 'visitor_info' ][ 'approved_start_date_time' ] ) ); ?>
		<?php }else{ ?>
		<?php echo date( "d-M-Y", doubleval( $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ) ); ?>
		<?php } ?>
		 
	  </div>
   </div>
</div>
</div>