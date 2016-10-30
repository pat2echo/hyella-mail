<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	
	$vat = 0;
	$service_charge = 0;
	$service_tax = 0;
?>
<!--EXCEL IMPOT FORM-->
<div class="row">
	<div class="col-md-5"> 
		<!--grey-->
		<div class="portlet grey box" style="background:transparent !important; border-color:#fff !important;">
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small>Select Reservation</small></div>
			</div>
			<div class="portlet-body allow-scroll" style="background:transparent;">
				<form class="activate-ajax" method="post" id="hotel_checkin" action="?action=hotel_checkin&todo=search_reservations">
					<div class="row">
						<div class="col-md-5">
							<input type="search" class="form-control input-lg1" style="" placeholder="Enter REF No." name="receipt_num">
						</div>
						<div class="col-md-2">
							<button class="btn btn-lg1 btn-block" type="submit" style="">OR</button>
						 </div>
						<div class="col-md-5">
							<select class="form-control input-lg1" onchange="nwCheckIn.search();" placeholder="Select Guest" name="customer">
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
				
				</div>				
			</div>
		</div>
		
	</div>
	
	<div class="col-md-7" id="main-table-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="tabbable-custom nav-justified">
				<ul class="nav nav-tabs nav-justified">
				   <li class="active"><a href="#recent-expenses" data-toggle="tab">1. Specify Room</a></li>
				   <li><a href="#recent-goods" onclick="nwCheckIn.selectPaymentInfo();" data-toggle="tab">2. Summary Info</a></li>
				</ul>
				<div class="tab-content" style="background:transparent !important;">
				   <div class="allow-scroll-1 tab-pane active" id="recent-expenses">
						<div id="check-in-notification">
							<div class="alert alert-info">
								<h4><i class="icon-bell"></i> Select Reservation First</h4>
								<p>
								Click on a reservation in the table to the left to begin checking in
								</p>
							</div>
						</div>
						
						<div id="check-in-container-rooms">
							
						</div>
						
						<div id="check-in-container">
						
						 <input type="hidden" name="date" value="<?php echo date("Y-m-d") ?>" class="form-control" value="" />
						 <br />
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Room Number <span style="color:#f00;">*</span></span>
								 <select class="form-control room_number" placeholder="Available Rooms" name="room_number">
									<option value="" class="all">-Available Rooms-</option>
									<?php
										if( isset( $data['available_rooms'] ) && is_array( $data['available_rooms'] ) ){
											$room_types = get_hotel_room_types();
											foreach( $data['available_rooms'] as $key => $val ){
												?>
												<option value="<?php echo $key; ?>" data-room-type="<?php echo $val["room_type"]; ?>" class="room-<?php echo $key; ?> <?php echo $val["room_type"]; ?>">
													<?php echo $val["room_number"] ." - ". ( isset( $room_types[ $val["room_type"] ] )?$room_types[ $val["room_type"] ]:"" ) ; ?>
												</option>
												<?php
											}
										}
									?>
								</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Check Out Date <span style="color:#f00;">*</span></span>
								 <input type="date" class="form-control checkout_date" name="checkout_date" value="" />
								</div>
								
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Guest(s) <span style="color:#f00;">*</span></span>
								 <select class="form-control guest" placeholder="Select Guest" name="guest">
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
							<div class="col-md-6">
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Comment</span>
								 <input type="text" class="form-control comment" name="comment" placeholder="Optional Comment" />
								</div>
								
							</div>
						</div>
						<br />
						
						</div>
						<div id="check-in-container-button">
							<div class="row">
								<div class="col-md-6">
									<button class="btn btn-lg default btn-block" onclick="nwCheckIn.clear();" >Cancel</button>
								</div>
								<div class="col-md-6">
									<button class="btn btn-lg green btn-block" onclick="nwCheckIn.selectPaymentInfoClick();" >Proceed to Next Step</button>
								</div>
							</div>
						</div>
				   </div>
				   <div class="allow-scroll-1 tab-pane" id="recent-goods">
						
						<div class="row">
							<div class="col-md-8 col-md-offset-2">
								<h4>Check-In Summary</h4>
								<hr />
								<div class="input-group">
								 <span class="input-group-addon" style=" font-size: 15px; line-height: 1.5;">Paying Guest</span>
								 <span  class="input-group-addon paying-guest" style="background: #A7E862; font-size: 15px; font-weight:bold; line-height: 1.5;"></span>
								</div>
								<br />
								<div class="shopping-cart-table">
									<div class="table-responsive">
										<table class="table table-striped table-hover bordered">
										<thead>
										   <tr>
											  <th style="font-size:10px;">Room Details</th>
											  <th style="font-size:10px;" class="r">Number of Nights</th>
										   </tr>
										</thead>
										<tbody>
										   
										</tbody>
										<tfoot>
										   
										</tfoot>
										</table>
									</div>
								</div>
							</div>
							
						</div>
						<hr />
						<div class="row">
							<div class="col-md-6">
								<a class="btn btn-lg default btn-block" onclick="nwCheckIn.emptyCart(); return false;" href="#">Cancel Operation</a>
							</div>
							<div class="col-md-6">
								<a class="btn btn-lg red btn-block custom-single-selected-record-button" action="?module=&action=hotel&todo=save_finish_check_in" id="finish-check-in" href="#">Finish Check-in</a>
							</div>
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