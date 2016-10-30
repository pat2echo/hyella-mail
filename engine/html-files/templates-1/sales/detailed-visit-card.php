<?php
	$addition = '';
	
	if( $data[ 'visitor_info' ][ 'approval_status' ] == 'cancelled' || $data[ 'visitor_info' ][ 'approval_status' ] == 'declined' )
		$addition = "background:url(".$site_url .'images/cancelled.png) center no-repeat;';
?>
<div class="row" style="<?php echo $addition; ?>" data-file="visit_schedule/detailed-visit-card.php">
   <div class="col-md-3" style="text-align:center;">
		
		<?php if( $data[ 'visitor_info' ][ 'approval_status' ] == 'approved' ){ ?>
		<img src="<?php echo $site_url; ?>images/qr_code.jpg"  width="120px" alt="Pass Code" align="center" />
		<h6 style="text-align:center;">Pass Code: <?php echo $data[ 'visitor_info' ][ 'id' ]; ?></h6>
		<?php }else{ ?>
		<img src="<?php echo $site_url . $data[ 'visitor_info' ][ 'photograph' ]; ?>"  width="90px" alt="<?php echo $data[ 'visitor_info' ][ 'full_name' ]; ?>" align="center" />
		<?php } ?>
	  
   </div>
   <div class="col-md-9">
	 <?php if( $data[ 'visitor_info' ][ 'approval_status' ] == 'approved' ){ ?>
		<div class="row">
			<div class="col-md-2" style="text-align:center;">
				<img src="<?php echo $site_url . $data[ 'visitor_info' ][ 'photograph' ]; ?>"  width="40px" alt="<?php echo $data[ 'visitor_info' ][ 'full_name' ]; ?>" align="center" />
			</div>
			<div class="col-md-10">
	 <?php } ?>
	  
	  <h4 style="margin-top:0;"><?php echo $data[ 'visitor_info' ][ 'full_name' ]; ?></h4>
	  <p style="line-height:1;">
		<small><a href="tel:<?php echo $data[ 'visitor_info' ][ 'phone_number' ]; ?>"><?php echo $data[ 'visitor_info' ][ 'phone_number' ]; ?></a>, <a href="mailto:<?php echo $data[ 'visitor_info' ][ 'email' ]; ?>"><?php echo $data[ 'visitor_info' ][ 'email' ]; ?></a></small>
	  </p>
	  
	  <?php if( $data[ 'visitor_info' ][ 'approval_status' ] == 'approved' ){ ?>
			</div>
		</div>
	  <?php } ?>
	  <hr class="half-margin" />
	  <p>
		<?php
			if( doubleval( $data[ 'visitor_info' ][ 'approved_start_date_time' ] ) && doubleval( $data[ 'visitor_info' ][ 'approved_start_date_time' ] ) != doubleval( $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ) ){
		?>
		<small><strong>Proposed Visiting Date:</strong> <?php echo date( "d-M-Y", doubleval( $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ) ); ?></small><br />
		<small><strong>Approved Visiting Date:</strong> <?php echo date( "d-M-Y", doubleval( $data[ 'visitor_info' ][ 'approved_start_date_time' ] ) ); ?></small><br />
		<?php }else{ ?>
		<small><strong>Visiting Date:</strong> <?php echo date( "d-M-Y", doubleval( $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ) ); ?></small><br />
		<?php } ?>
		<strong>Reason:</strong> <?php echo $data[ 'visitor_info' ][ 'reason_for_visit' ]; ?>
	  </p>
	  <p>
		<?php
			if( $data[ 'visitor_info' ][ 'street_address' ] ){
				?><strong>Address:</strong> <?php
				echo $data[ 'visitor_info' ][ 'street_address' ] . "<br />"; 
			}
		?>
		<?php
			if( $data[ 'visitor_info' ][ 'name_of_organization' ] ){
				?><strong>Organization:</strong> <?php
				echo $data[ 'visitor_info' ][ 'name_of_organization' ]; 
			}
		?>
	  </p>
   </div>
</div>
<div class="row">
	<div class="col-md-12">
	  <table border="0" cellpadding="0" cellspacing="0" style="width:100%;" class="textdark">
			<tr>
				<td colspan="2"><hr class="half-margin" style="border-top:1px solid #f7f7f7; border-bottom:none;" /></td>
			</tr>
			<tr>
				<td>Status</td>
				<td>
				<strong style="<?php 
					$colors = get_approval_status_colors();
					if( isset( $colors[ $data[ 'visitor_info' ][ 'approval_status' ] ] ) )
						echo $colors[ $data[ 'visitor_info' ][ 'approval_status' ] ]
				?>">
					<?php 
						$icons = get_approval_status_icons();
						if( isset( $icons[ $data[ 'visitor_info' ][ 'approval_status' ] ] ) )
							echo $icons[ $data[ 'visitor_info' ][ 'approval_status' ] ]." ";
					?>
					<?php echo get_select_option_value( array( 'id' => $data[ 'visitor_info' ][ 'approval_status' ], 'function_name' => 'get_approval_status' ) ); //echo ucwords($data[ 'card_type' ]); ?>
				</strong>
				</td>
			</tr>
			<tr>
				<td>HOST</td>
				<td><strong><?php echo $data[ 'host_info' ]['lastname'] . ' '. substr( $data[ 'host_info' ]['firstname'] , 0, 1 ) . ' ' . ' ('. $data[ 'host_info' ]['phone_number'] .')'; ?></strong></td>
			</tr>
		</table>
	</div>
</div>
<style type="text/css">
	.form-body{
		padding:10px 0;
	}
	.form-body .control-group{
		padding-left:0 !important;
	}
	.form-body .control-label{
		font-size:0.8em;
		font-weight:bold;
	}
	.form-body .bottom-row{
		clear:both;
	}
</style>