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
	
	<div class="col-md-10 col-md-offset-1" id="main-table-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="tabbable-custom nav-justified">
				<ul class="nav nav-tabs nav-justified">
				   <li class="active"><a href="#recent-expenses" data-toggle="tab">1. Select Date</a></li>
				   <li><a href="#extra-cost" onclick="nwMakeReservation.selectRoomClick();" data-toggle="tab">2. Select Room</a></li>
				   <li><a href="#recent-goods" onclick="nwMakeReservation.selectGuestClick();" data-toggle="tab">3. Select Guest</a></li>
				</ul>
				<div class="tab-content" style="background:transparent !important;">
				   <div class="allow-scroll-1 tab-pane active" id="recent-expenses">
						
						 <input type="hidden" name="date" value="<?php echo date("Y-m-d") ?>" class="form-control" value="" />
						 <br />
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Check In Date</span>
								 <input type="date" class="form-control" name="checkin_date" value="<?php echo date("Y-m-d"); ?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Check Out Date</span>
								 <input type="date" class="form-control" name="checkout_date" value="<?php echo date("Y-m-d", date("U") + ( 3600 * 24 ) ); ?>" />
								</div>
								
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Number of Adults</span>
								 <input type="number" class="form-control" name="number_of_adults" value="1" min="1" step="1" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Number of Children</span>
								 <input type="number" class="form-control" name="number_of_children" value="0" min="0" step="1" />
								</div>
								
							</div>
						</div>
						<hr />
						<div class="row">
							<div class="col-md-6">
								<button class="btn btn-lg default btn-block" onclick="nwMakeReservation.emptyCart();" >Cancel Operation</button>
							</div>
							<div class="col-md-6">
								<button class="btn btn-lg green btn-block" onclick="nwMakeReservation.proceedSelectRoomClick();" >Proceed to Select Room</button>
							</div>
						</div>
						
				   </div>
				   <div class="allow-scroll-1 tab-pane" id="extra-cost">
						<div class="row">
							<div class="col-md-4">
								
								<div class="shopping-cart-table">
									
									<div class="table-responsive">
										<table class="table table-striped table-hover bordered">
										<thead>
										   <tr>
											  <th style="font-size:10px;">Room Type</th>
											  <th style="font-size:10px;" class="r">No. of Rooms</th>
											  <th style="font-size:10px;" class="r">Total Rate / Night</th>
										   </tr>
										</thead>
										<tbody>
										   
										</tbody>
										<tfoot>
										   
										</tfoot>
										</table>
									</div>
									<div class="input-group">
									 <span class="input-group-addon" >Number of Nights</span>
									 <span  class="input-group-addon number-of-nights" style="background: #A7E862;">0</span>
									</div>
									<hr />
									<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Discount</span>
										  <select class="form-control" id="discount" style="font-size:12px; font-weight:bold;">
											<?php if( file_exists( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ).'/globals/shopping-cart-discount-list.php' ) ) ) )
													include dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ).'/globals/shopping-cart-discount-list.php'; ?>
										 </select>
										 
										 <span class="input-group-btn">
										 <button class="btn dark custom-action-button" type="button" function-name="manage_discount" function-class="discount" function-id="Manage Discount" skip-title="1" ><i class="icon-edit"></i>&nbsp;</button>
										 </span>
									  </div>
									<hr />
									
								  <?php 
									if( ( isset( $data["surcharge"]["VAT"] ) && $data["surcharge"]["VAT"] ) || ( isset( $data["surcharge"]["SERVICE CHARGE"] ) && $data["surcharge"]["SERVICE CHARGE"] ) || ( isset( $data["surcharge"]["SERVICE TAX"] ) && $data["surcharge"]["SERVICE TAX"] ) ){
								  ?>
								  
								 <?php if( ( isset( $data["surcharge"]["VAT"] ) && $data["surcharge"]["VAT"] ) ){ ?>
								 <div class="input-group" >
								 <span class="input-group-addon"><strong><small>VAT <?php $vat = $data["surcharge"]["VAT"]; echo $data["surcharge"]["VAT"]; ?>%</small></strong></span>
								 <span  class="input-group-addon vat-amount-due" style="background: #A7E862;">0.00</span>
								 </div>
								 <?php } ?>
									 
								 <?php if( isset( $data["surcharge"]["SERVICE CHARGE"] ) && $data["surcharge"]["SERVICE CHARGE"] ){  ?>
								 
								<div class="input-group" style="margin-top:4px;">
								 <span class="input-group-addon"><strong><small>SERVICE CHARGE <?php $service_charge = $data["surcharge"]["SERVICE CHARGE"]; echo $data["surcharge"]["SERVICE CHARGE"]; ?>%</small></strong></span>
								 <span  class="input-group-addon service-charge-amount-due" style="background: #A7E862;">0.00</span>
								</div>
								<?php } ?>
								 
								 <?php if( isset( $data["surcharge"]["SERVICE TAX"] ) && $data["surcharge"]["SERVICE TAX"] ){  ?>
								 
								 <div class="input-group" style="margin-top:4px;">
									 <span class="input-group-addon"><strong><small>SERVICE TAX <?php $service_tax = $data["surcharge"]["SERVICE TAX"]; echo $data["surcharge"]["SERVICE TAX"]; ?>%</small></strong></span>
									 <span  class="input-group-addon service-tax-amount-due" style="background: #A7E862;">0.00</span>
									
								  </div>
								  <?php } ?>
								  
								  
								  <br />
								<?php } ?>
								
									<div class="input-group">
									 <span class="input-group-addon" style="font-size: 18px; line-height: 1.5;">Amount Due</span>
									 <span  class="input-group-addon amount-due" style="background: #A7E862; font-size: 18px; line-height: 1.5;">0.00</span>
									</div>
									<hr />
									
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-lg green btn-block" onclick="nwMakeReservation.proceedSelectGuestClick();">Proceed to Select Guest</button>
										</div>
										<br />
										<div class="col-md-12">
											<button class="btn btn-lg default btn-block" onclick="nwMakeReservation.emptyCart();">Cancel Operation</button>
										</div>
									</div>
								</div>
								
							</div>
							<div class="col-md-8">
								<?php 
									if( isset( $data['available_room_types'] ) && is_array( $data['available_room_types'] ) ){
										foreach( $data['available_room_types'] as $key => $sval ){
											include dirname( dirname( __FILE__ ) ) . "/room-types-list.php";
										}
									}else{
										?>
										<div class="alert alert-danger">
											<h4>No Available Room</h4>
											<p>The Hotel is fully booked and there are no available rooms</p>
										</div>
										<?php
									}
								?>
							</div>
						</div>
				   </div>
					
				   <div class="allow-scroll-1 tab-pane" id="recent-goods">
						
						<div class="row">
							<div class="col-md-7">
								<button class="btn dark btn-sm pull-right custom-action-button" function-id="1" function-class="hotel" function-name="new_guest_form" title="Create New Guest" skip-title="1">Add New Guest</button>
								<h4>Guest Information</h4>
								<hr />
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Main Guest</span>
								 <select class="form-control" name="main_guest">
									<option value="">-Select Main Guest-</option>
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
								<br />
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Additional Guest (optional)</span>
								  <select multiple="multiple" class="form-control" name="other_guest" placeholder="Select Additional Guest">
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
								<br />
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Comment</span>
								 <input type="text" class="form-control" name="comment" placeholder="Optional Comment" />
								</div>
							</div>
							<div class="col-md-5">								
								<h4>Payment Information</h4>
								<hr />
								<div class="input-group">
								 <span class="input-group-addon" style=" font-size: 18px; line-height: 1.5;">Amount Due</span>
								 <span  class="input-group-addon amount-due" style="background: #A7E862; font-size: 18px; line-height: 1.5;">0.00</span>
								</div>
								<br />
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Amount Paid</span>
								 <input type="number" step="any" class="form-control" id="amount-paid" value="0">
								</div>
								<br />
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Payment Method</span>
								 <select class="form-control" name="payment_method">
									<?php
										$pm = get_payment_method();
										if( isset( $pm ) && is_array( $pm ) ){
											foreach( $pm as $key => $val ){
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
						</div>
						<hr />
						<div class="row">
							<div class="col-md-4">
								<a class="btn btn-lg default btn-block" onclick="nwMakeReservation.emptyCart(); return false;" href="#">Cancel Operation</a>
							</div>
							<div class="col-md-4">
								<a class="btn btn-lg green btn-block custom-single-selected-record-button" action="?module=&action=hotel&todo=save_reservation" id="save-reservation" href="#">Save Reservation</a>
							</div>
							<!--
							<div class="col-md-4">
								<a class="btn btn-lg red btn-block" action="?module=&action=hotel&todo=save_check_in" id="save-check-in" href="#">Check-In</a>
							</div>
							-->
						</div>
						
				   </div>
				   
				</div>
			 </div>
			
		</div>
	</div>

</div>

<script type="text/javascript" class="auto-remove">
	var vat = <?php echo $vat; ?>;
	var service_charge = <?php echo $service_charge; ?>;
	var service_tax = <?php echo $service_tax; ?>;
	
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>