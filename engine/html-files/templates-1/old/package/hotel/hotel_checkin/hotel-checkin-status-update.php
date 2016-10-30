<!-- BEGIN PAGE CONTAINER -->  
<div class="page-container">
	
	<div class="tabbable-custom nav-justified">
		<ul class="nav nav-tabs nav-justified">
		   <li class="active"><a href="#tab_1_1_1" data-toggle="tab">General Info</a></li>
		   <?php if( isset( $data['payment']["html_replacement"] ) ){ ?><li><a href="#tab_1_1_2" data-toggle="tab">Capture Payment</a></li><?php } ?>
		   <?php if( isset( $data['payment_history'] ) ){ ?><li><a href="#tab_1_1_a" data-toggle="tab">Bills / Payment History</a></li><?php } ?>
		   <?php if( isset( $data['room_types']["html_replacement"] ) ){ ?><li><a href="#tab_1_1_3" data-toggle="tab">Room Types</a></li><?php } ?>
		   <?php if( isset( $data['change_room']["html"] ) ){ ?><li><a href="#tab_1_1_4" data-toggle="tab">Change Room</a></li><?php } ?>
		</ul>
		<div class="tab-content">
		   <div class="tab-pane active" id="tab_1_1_1">
			<?php 
				if( isset( $data['room_bookings'] ) && $data['room_bookings'] ){
					echo $data['room_bookings'];
				}
			?>
			<div class="row">
			<div class="col-md-7"> 
			 <div>
				<?php 
					$show_checkin_button = 0;
					if( isset( $data["show_info"] ) && $data["show_info"]  ){
					switch( $data["show_info"] ){
					case "view_booked_room_status":
						$show_checkin_button = 1;
					break;
					case "view_vacant_room_status":
						$show_checkin_button = 1;
					break;
					case "view_occuppied_room_status":
						if( isset( $data["room_info"]["id"] ) && isset( $data["booking_id"] ) ){
							$guest = get_customers_details( array( "id" => $data["room_info"]["guest"] ) ) ;
							
							$group_name = "";
							if( $data["event"]["group"] )$group = get_customers_details( array( "id" => $data["event"]["group"] ) ) ;
							
							if( isset( $group["name"] ) ){
								$group_name = $group["name"];
							}
								
						?>
						<div class="alert alert-info">
						
						<?php if( $group_name ){ ?>
						<button class="btn pull-right btn-sm dark custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_group_guest_checkout_invoice" override-selected-record="<?php echo $data["booking_id"]; ?>" mod="<?php echo $data["room_info"]["id"]; ?>" title="Checkout Entire Group">Group Checkout</button>
						
						<button class="btn pull-right btn-sm red custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_single_guest_checkout_invoice" override-selected-record="<?php echo $data["booking_id"]; ?>" mod="<?php echo $data["room_info"]["id"]; ?>" title="Checkout Only this Guest">Checkout: <strong><?php echo strtoupper( isset($guest["name"])?$guest["name"]:"" ); ?></strong></button>
						<?php }else{ ?>
						<button class="btn pull-right btn-sm red custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_single_guest_checkout_invoice" override-selected-record="<?php echo $data["booking_id"]; ?>" mod="<?php echo $data["room_info"]["id"]; ?>" title="Checkout Only this Guest">Guest Checkout</button>
						<?php } ?>
						
						<?php if( $group_name ){ ?>
						Group: <strong><?php echo strtoupper( $group_name ); ?></strong><br />
						<?php } ?>
						
						Guest: <strong><?php echo strtoupper( isset($guest["name"])?$guest["name"]:"" ); ?></strong><br />
						<small>Check In: <strong><?php echo date( "d-M-Y", doubleval( $data["room_info"]["checkin_date"] ) ); ?></strong> | Check Out: <strong><?php echo date( "d-M-Y", doubleval( $data["room_info"]["checkout_date"] ) ); ?></strong></small><br /><br />
						Comment: <strong><?php echo $data["room_info"]["comment"]; ?></strong>
						</div>
						<?php
						}
					break;
					}
				} ?>
			</div>
			  
			  <?php if( isset( $data["sales_form"]["html"] ) )echo $data["sales_form"]["html"]; ?>
			</div>
			  
			  <?php 
				if( isset( $data['room_details'] ) && isset( $data['room_type_details'] ) ){ 
					//print_r( $data['room_details'] );
					$rate = doubleval( $data['room_type_details']["rate"] );
					$service_tax = $service_charge = $vat = 0;
				?>
				<div class="col-md-5">
				<div class="alert alert-info">
					<h5><strong><?php echo $data['room_details']["room_number"] . " - " . $data['room_type_details']["name"];  ?></strong></h5>
					<p>
						Rate Per Night: <strong class="pull-right"><?php echo number_format( $data['room_type_details']["rate"] , 2 ); ?></strong><br />
						<?php if( doubleval( $data['room_type_details']["deposit_amount"] ) ){ ?>Refundable Deposit: <strong class="pull-right"><?php echo number_format( $data['room_type_details']["deposit_amount"] , 2 ); ?></strong><br /><?php } ?>
						
						<?php 
							if( ( $rate && isset( $data["surcharge"]["VAT"] ) && $data["surcharge"]["VAT"] ) || ( isset( $data["surcharge"]["SERVICE CHARGE"] ) && $data["surcharge"]["SERVICE CHARGE"] ) || ( isset( $data["surcharge"]["SERVICE TAX"] ) && $data["surcharge"]["SERVICE TAX"] ) ){
						  ?>
						  
						 <?php if( ( isset( $data["surcharge"]["VAT"] ) && $data["surcharge"]["VAT"] ) ){ ?>
						 VAT: <strong class="pull-right"><?php $vat = round( $rate * $data["surcharge"]["VAT"] / 100 , 2 ); echo number_format( $vat , 2 ); ?></strong><br />
						 <?php } ?>
							 
						 <?php if( isset( $data["surcharge"]["SERVICE CHARGE"] ) && $data["surcharge"]["SERVICE CHARGE"] ){  ?>
						 Service Charge: <strong class="pull-right"><?php $service_charge = round( $rate * $data["surcharge"]["SERVICE CHARGE"] / 100 , 2 ); echo number_format( $service_charge , 2 ); ?></strong><br />
						<?php } ?>
						 
						 <?php if( isset( $data["surcharge"]["SERVICE TAX"] ) && $data["surcharge"]["SERVICE TAX"] ){  ?>
						 Service Tax: <strong class="pull-right"><?php $service_tax = round( $rate * $data["surcharge"]["SERVICE TAX"] / 100 , 2 ); echo number_format( $service_tax , 2 ); ?></strong><br />
						  <?php } ?>
						  
						<?php } ?>
						<br />
						TOTAL RATE PER NIGHT: <strong class="pull-right"><?php echo number_format( $service_tax + $service_charge + $vat + $rate , 2 ); ?></strong>
						  
					</p>
				</div>
				<hr />
				<?php if( $show_checkin_button ){ ?>
				<button class="btn btn-block red custom-single-selected-record-button" action="?module=&action=hotel_room_checkin&todo=check_in_to_room_form" override-selected-record="<?php echo $data['room_details']["id"]; ?>" >Check In</button>
				<button class="btn btn-block yellow custom-single-selected-record-button" action="?module=&action=hotel_room_checkin&todo=check_in_to_room_form_complimentary" override-selected-record="<?php echo $data['room_details']["id"]; ?>" title="Guest would not be charged any fee">Complimentary Check In</button>
				<?php } ?>
				
				<button class="btn btn-block default custom-single-selected-record-button" action="?module=&action=hotel_room_checkin&todo=view_room_booking_form" override-selected-record="<?php echo $data['room_details']["id"]; ?>" >Book Room</button>
				<button class="btn btn-block default custom-single-selected-record-button" action="?module=&action=hotel_room_checkin&todo=view_room_reservation_form" override-selected-record="<?php echo $data['room_details']["id"]; ?>" >Make Reservation</button>
				
				</div>
				<?php } ?>
				</div>
				
		   </div>
		   <div class="tab-pane" id="tab_1_1_2">
			  <div class="row">
			<div class="col-md-8"> 
			  <?php if( isset( $data['payment']["html_replacement"] ) )echo $data['payment']["html_replacement"]; ?>
			  </div>
			  <div class="col-md-4">
				<?php 
					if( isset( $data[ "customer_deposits" ]["balance"] ) && $data[ "customer_deposits" ]["balance"]  ){
						?>Deposit Balance: <br /><strong><?php echo number_format( $data[ "customer_deposits" ]["balance"] , 2 ); ?></strong><?php
					}
				?>
			  </div>
			  </div>
		   </div>
		   <div class="tab-pane" id="tab_1_1_3">
			   <div class="row">
			<div class="col-md-8"> 
			  <?php if( isset( $data['room_types']["html_replacement"] ) )echo $data['room_types']["html_replacement"]; ?>
			  </div>
			  </div>
		   </div>
		  
		  <?php 
			if( isset( $data['payment_history'] ) && isset( $data["room_info"]["id"] ) ){ 
				$start_date = date("Y-m-d" , mktime(0,0,0, date("n"), 1, date("Y") ) );
				if( isset( $data["room_info"]["checkin_date"] ) && doubleval( $data["room_info"]["checkin_date"] ) ){
					$start_date = date("Y-m-d", $data["room_info"]["checkin_date"] );
				}
				
				$end_date = date("Y-m-d" , mktime(0,0,0, date("n"), date("t"), date("Y") ) );
				if( isset( $data["room_info"]["checkout_date"] ) && doubleval( $data["room_info"]["checkout_date"] ) ){
					$end_date = date("Y-m-d", $data["room_info"]["checkout_date"] );
				}
		  ?>
		   <div class="tab-pane" id="tab_1_1_a">
			  <a href="#" budget-id="<?php echo $data["room_info"]["id"]; ?>" month-id="-" operator-id="<?php echo $data['payment_history']; ?>" department-id="guest_activity_report_frontend" skip-title="1" function-id="activate_report_year" function-class="hotel_checkin" function-name="generate_guest_sales_report" class="btn btn-block btn-success custom-action-button activated-click-event" start-date="<?php echo $start_date; ?>" end-date="<?php echo $end_date; ?>">Generate Guest Bills / Payment History Report</a>
			  <div id="generate_guest_sales_report-container">
				  
			  </div>
		   </div>
		   <?php } ?>
		   
		   <div class="tab-pane" id="tab_1_1_4">
			  <div class="row">
			<div class="col-md-8"> 
			  <?php if( isset( $data['change_room']["html"] ) )echo $data['change_room']["html"]; ?>
			   </div>
			   </div>
		   </div>
		</div>
	 </div>
</div>
<!-- END PAGE CONTAINER -->  