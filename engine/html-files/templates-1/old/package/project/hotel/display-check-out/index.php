<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
?>
<!--EXCEL IMPOT FORM-->
<div class="row">
	<div class="col-md-5"> 
		<!--grey-->
		<div class="portlet grey box" style="background:transparent !important; border-color:#fff !important;">
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small>Occuppied Rooms</small></div>
			</div>
			<div class="portlet-body allow-scroll" style="background:transparent;">
				<form class="activate-ajax" method="post" id="hotel_checkin" action="?action=hotel_checkin&todo=search_occuppied_rooms">
					<div class="row">
						<div class="col-md-5">
							<select class="form-control input-lg1" onchange="nwCheckOut.search();" placeholder="Select Room No." name="receipt_num">
							<option value="">-Select Room No.-</option>
								<?php
									if( isset( $data['unavailable_rooms'] ) && is_array( $data['unavailable_rooms'] ) ){
										$room_types = get_hotel_room_types();
										foreach( $data['unavailable_rooms'] as $key => $val ){
											?>
											<option value="<?php echo $val["room_id"]; ?>" data-room-type="<?php echo $val["room_type"]; ?>" class="room-<?php echo $val["room"]; ?> <?php echo $val["room_type"]; ?>">
												<?php echo $val["room_number"] ." - ". ( isset( $room_types[ $val["room_type"] ] )?$room_types[ $val["room_type"] ]:"" ) ; ?>
											</option>
											<?php
										}
									}
								?>
							</select>
						</div>
						<div class="col-md-2">
							<button class="btn btn-lg1 btn-block" type="submit" style="">OR</button>
						 </div>
						<div class="col-md-5">
							<select class="form-control input-lg1" onchange="nwCheckOut.search();" placeholder="Select Guest" name="customer">
								<option value="">-Select Guest-</option>
								<?php
									if( isset( $data['customers'] ) && is_array( $data['customers'] ) ){
										foreach( $data['customers'] as $key => $val ){
											?>
											<option value="<?php echo $key; ?>">
												<?php echo $val; ?>
											</option>
											<?php
										}
									}
								?>
							</select>
						</div>
					</div>
				</form>
				<hr>
				<div id="hotel_checkin-record-search-result">
					<?php 
						if( isset( $data[ "occuppied_rooms" ] ) )echo $data[ "occuppied_rooms" ];
					?>
				</div>
			</div>
		</div>
		
	</div>
	
	<div class="col-md-7" id="main-table-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="tabbable-custom nav-justified">
				<ul class="nav nav-tabs nav-justified">
				   <li class="active"><a href="#recent-expenses" data-toggle="tab">Check Out</a></li>
				   <li><a href="#recent-goods" id="view-guest-account" class="custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_customer_account" override-selected-record="" data-toggle="tab">View Guest Account</a></li>
				   
				</ul>
				<div class="tab-content" style="background:transparent !important;">
				   <div class="allow-scroll-1 tab-pane active" id="recent-expenses">
						<div id="check-in-notification">
							<div class="alert alert-info">
								<h4><i class="icon-bell"></i> Select Occuppied Room First</h4>
								<p>
								Click on an occuppied room in the table to the left to begin checking out
								</p>
							</div>
						</div>
						
						<div id="check-out-container">
							<div id="invoice-receipt-container">
								
							</div>
						</div>
						
						<div id="check-in-container">
							<button class="btn btn-lg default btn-block custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_room_by_room_invoice" mod=""  id="view-bill">View Bill</button>
						</div>
				   </div>
				   <div class="allow-scroll-1 tab-pane" id="recent-goods">
						<div id="guest-account-container-wrapper">
						</div>
				   </div>
				   
				</div>
			 </div>
			
		</div>
	</div>

</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>