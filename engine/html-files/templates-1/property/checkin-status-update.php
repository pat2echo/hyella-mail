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
			<div class="col-md-6"> 
			 <div>
				<?php 
					$show_checkin_button = 0;
					if( isset( $data["show_info"] ) && $data["show_info"]  ){
					switch( $data["show_info"] ){
					case "view_booked_room_status":
						$show_checkin_button = 1;
					break;
					case "view_vacant_room_status":
						$show_checkin_button = 0;
					break;
					case "view_occuppied_room_status":
						if( isset( $data["room_info"]["id"] ) ){
							$guest = get_customers_details( array( "id" => $data["room_info"]["customer"] ) );
						?>
						<div class="alert alert-info">
						
						<small>Entry Date: <strong><?php echo date( "d-M-Y", doubleval( $data["room_info"]["date"] ) ); ?></strong>
						<br />Vacation Date: <strong><?php echo date( "d-M-Y", doubleval( $data["room_info"]["date"] ) + ( 31536000 ) ); ?></strong></small>
						<br /><br />
						Comment: <strong><?php echo $data["room_info"]["comment"]; ?></strong>
						</div>
						
						<div class="alert alert-warning">
						<h4>Billing Info</h4>
						Reference No.: <strong>#<?php echo mask_serial_number( $data["room_info"]["serial_num"] , 'S' ); ?></strong>
						<br /><br />
						Amount Due: <strong><?php echo number_format( doubleval( $data["room_info"]["amount_due"] ), 2 ); ?></strong>
						<br />
						Amount Paid: <strong><?php echo number_format( doubleval( $data["room_info"]["amount_paid"] ), 2 ); ?></strong>
						
						<?php if( doubleval( $data["room_info"]["amount_due"] - $data["room_info"]["amount_paid"] ) ){ ?>
						<br />
						Outstanding: <strong style="color:#b30000;"><?php echo number_format( doubleval( $data["room_info"]["amount_due"] - $data["room_info"]["amount_paid"] ), 2 ); ?></strong>
						<?php } ?>
						
						<br /><br />
						Comment: <strong><?php echo $data["room_info"]["comment"]; ?></strong>
						</div>
						
						</div>
						</div>
						<div class="col-md-5 col-md-offset-1"> 
						<div>
						<strong><?php echo strtoupper( isset($guest["name"])?$guest["name"]:"" ); ?></strong>
						<?php 
							$pr = get_project_data();
							if( isset( $guest["photograph"] ) && $guest["photograph"] && file_exists( $pagepointer . $guest["photograph"] ) ){
						?>
						<div style="text-align:center;"><img src="<?php echo $pr["domain_name"] . $guest["photograph"]; ?>" width="60%" /></div>
						<?php
							}
						?>
						<button class="btn btn-block btn-sm red custom-single-selected-record-button" action="?module=&action=property&todo=vacate_tenant" override-selected-record="<?php echo $data["room_info"]["id"]; ?>" mod="<?php echo $data["room_info"]["id"]; ?>" title="Vacate this Tenant">Vacate Tenant</button>
						
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
					//print_r( $data['room_type_details'] );
					$billing_label = get_billing_cycle_text( $data['room_details']["billing_cycle"] );
					
					$rate = doubleval( $data['room_type_details']["rate"] );
					$service_tax = $service_charge = $vat = 0;
				?>
				<div class="col-md-5 col-md-offset-1">
				<div class="alert alert-info">
					<small><?php echo get_select_option_value( array( "id" => $data['room_details']["location"], "function_name" => "get_stores" ) ); ?></small>
					<div style="font-size:14px;"><strong><?php echo $data['room_details']["description"] . " - " . $data['room_type_details']["name"];  ?></strong></div><br />
					<p>
						Rate Per <?php echo $billing_label; ?>: <strong class="pull-right"><?php echo number_format( $data['room_details']["rate"] , 2 ); ?></strong><br />
						
						 <?php if( isset( $data['room_details']["service_charge"] ) && $data['room_details']["service_charge"] ){  ?>
						 Service Charge: <strong class="pull-right"><?php $service_charge = $data['room_details']["service_charge"]; echo number_format( $service_charge , 2 ); ?></strong><br />
						<?php } ?>
						 
						<br />
						TOTAL RATE PER <?php echo strtoupper( $billing_label ); ?>: <strong class="pull-right"><?php echo number_format( $service_tax + $service_charge + $vat + $rate , 2 ); ?></strong>
						  
					</p>
				</div>
				<hr />
				<?php if( $show_checkin_button ){ ?>
				<button class="btn btn-block red custom-single-selected-record-button" action="?module=&action=property&todo=assign_tenant_form" override-selected-record="<?php echo $data['room_details']["id"]; ?>" >Assign Tenant</button>
				<!--
				<button class="btn btn-block yellow custom-single-selected-record-button" action="?module=&action=hotel_room_checkin&todo=check_in_to_room_form_complimentary" override-selected-record="<?php echo $data['room_details']["id"]; ?>" title="Guest would not be charged any fee">Complimentary Check In</button>-->
				<?php } ?>
				
				<!--
				<button class="btn btn-block default custom-single-selected-record-button" action="?module=&action=hotel_room_checkin&todo=view_room_booking_form" override-selected-record="<?php echo $data['room_details']["id"]; ?>" >Book Room</button>
				<button class="btn btn-block default custom-single-selected-record-button" action="?module=&action=hotel_room_checkin&todo=view_room_reservation_form" override-selected-record="<?php echo $data['room_details']["id"]; ?>" >Make Reservation</button>
				-->
				</div>
				<?php } ?>
				</div>
				
		   </div>
		   <div class="tab-pane" id="tab_1_1_2">
			  <div class="row">
			<div class="col-md-12"> 
			  <?php if( isset( $data['payment']["html_replacement"] ) )echo $data['payment']["html_replacement"]; ?>
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