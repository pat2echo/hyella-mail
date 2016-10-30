<!-- BEGIN PAGE CONTAINER -->  
<div id="invoice-container-wrapper">
<div class="page-container" id="manifest-<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>">
<?php
	include dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . "/globals/invoice-css.php";
	
		$stamp = "part-payment.png";
		
		$amount_paid = 0;
		if( isset( $data["payment"]["TOTAL_AMOUNT_PAID"] ) ){
			$amount_paid = round( doubleval( $data["payment"]["TOTAL_AMOUNT_PAID"] ) , 2 );
		}
		
		$pr = get_project_data();
		
		$support_line = "";
		if( isset( $pr['support_line'] ) )$support_line = $pr['support_line'];
		
		$support_email = "";
		if( isset( $pr['support_email'] ) )$support_email = $pr['support_email'];
		
		$support_addr = "";
		if( isset( $pr['street_address'] ) )$support_addr = $pr['street_address'] . " " . $pr['city'] ." ". $pr['state'];
		
		$store_name = "";
		$branch = "";
		$store = array();
		
		if( isset( $data['event']["hotel"] ) && $data['event']["hotel"] ){
			$store = get_store_details( array( "id" => $data['event']["hotel"] ) );
			
			if( isset( $store["phone"] ) ){
				//test for sub location
				if( $store["name"] != "." ){ 
					$store1 = get_store_details( array( "id" => $store["name"] ) );
					if( isset( $store1["phone"] ) ){
						$branch = $store["address"];
						$store = $store1;
					}
				}
				$store_name = $store["name"];
				$support_line = $store["phone"];
				$support_addr = $store["address"];
				$support_email = $store["email"];
				$support_msg = $store["comment"];
				
				if( $store_name == "." ){ $store_name = " "; }
			}
		}
		
		$show_buttons = 1;
		if( isset( $data["hide_buttons"] ) && $data["hide_buttons"] )
			$show_buttons = 0;
		
		$show_checkout_buttons = 0;
		if( isset( $data["show_checkout_buttons"] ) && $data["show_checkout_buttons"] )
			$show_checkout_buttons = 1;
		
		$show_guest_checkout_buttons = 0;
		if( isset( $data["show_guest_checkout_buttons"] ) && $data["show_guest_checkout_buttons"] )
			$show_guest_checkout_buttons = 1;
		
		$show_group_checkout_buttons = 0;
		if( isset( $data["show_group_checkout_buttons"] ) && $data["show_group_checkout_buttons"] )
			$show_group_checkout_buttons = 1;
		
		$show_payment_buttons = 0;
		if( isset( $data["show_payment_buttons"] ) && $data["show_payment_buttons"] )
			$show_payment_buttons = 1;
		
		$room_id = "";
		if( isset( $data["room_id"] ) && $data["room_id"] )
			$room_id = $data["room_id"];
		
		$discount = 0;
		
		$no_of_nights = 0;
		if( isset( $data['event'][ "number_of_nights" ] ) && $data['event'][ "number_of_nights" ] ){
			$no_of_nights = $data['event'][ "number_of_nights" ];
		}
		
		$key = "booking_status"; 
		$booking_status = "";
		if( isset( $data["event"][$key] ) )$booking_status = $data["event"][$key];
		
		$group_name = "";
		if( isset( $data["event"]["group"] ) && $data["event"]["group"] )
			$group = get_customers_details( array( "id" => $data["event"]["group"] ) ) ;
		
		if( isset( $group["name"] ) ){
			$group_name = $group["name"];
		}
		
		$room_guest = "";
		if( isset( $data['room_guest']["guest"] ) && $data['room_guest']["guest"] ){
			$gr = get_customers_details( array( "id" => $data['room_guest']["guest"] ) ) ;
			if( isset( $gr["name"] ) ){
				$room_guest = $gr["name"];
			}
		}
		
		$booked = 1;
		switch( $booking_status ){
		case "checked_out":
		case "checked_in":
			$booked = 0;
		break;
		}
		
		$delete_caption = "Delete";
		if( $booked ){
			$show_buttons = 1;
			$delete_caption = "Cancel Reservation";
		}
	?>
	
	<!-- BEGIN CONTAINER -->   
	<div class="container" id="invoice-container">
		
		<!-- BEGIN ABOUT INFO -->   
		<div class="invoice">
		<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
		<?php //print_r($data['event']); ?>
		
		<?php 
			$backend = 0;
			if( isset( $data["backend"] ) && $data["backend"] )
				$backend = $data["backend"];
			
			$unit_type_text = "Quantity";
		?>
		
		<div class="row invoice-logo" style="margin-bottom:0px;">
		   <div class="col-xs-5 invoice-logo-space"><br />
			<?php 
				if( $store_name ){
					?><img src="<?php echo $pr["domain_name"]."frontend-assets/img/logo-b.png"; ?>" style="max-height:60px;" align="left" /><span class="store-name"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php echo $store_name; ?></span><?php 
				}else{
					$store_name = "Support";
			?>
			<img src="<?php echo $pr["domain_name"]."frontend-assets/img/logo_blue.png"; ?>" style="max-height:60px;" />
			<?php } ?>
		   </div>
		   <div class="col-xs-7 ">
			  <p style="margin-bottom:0;">#<small><?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?> / <?php $key = "creation_date"; if( isset( $data["event"][$key] ) )echo date("j M Y", doubleval( $data["event"][$key] ) ); ?></small>
			  <span class="muted"><small style="line-height:1.2;"><?php echo $support_addr;  ?></small></span>
			  
			   <span style="font-size:10px;"><a href="tel:<?php echo $support_line; ?>"><?php echo $support_line; ?></a>, <a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a></span>
			  </p>
			  
		   </div>
		</div>
		<hr style="margin-top:10px;" />
		<div class="row ">
		   <div class="col-xs-5 ">
			  <h4>Guest:  <?php $key = "booking_status"; if( isset( $data["event"][$key] ) ){ ?><span class="pull-right label label-info"><small><strong><i class="icon-pushpin"></i> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_hotel_room_booking_status" ) ); ?></strong></small></span><?php } ?></h4>
			  <ul class="list-unstyled">
				 <li><strong><?php $key = "main_guest"; if( isset( $data["event"][$key] ) )echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_customers" ) ); ?></strong></li>
				 
				 <?php 
					$key = "other_guest"; 
					if( isset( $data["event"][$key] ) && $data["event"][$key] ){
						$others = explode(":::", $data["event"][$key] );
						if( is_array( $others ) && ! empty( $others ) ){
							$c = get_customers();
						?><li><i><?php
							foreach( $others as $other ){
								if( isset( $c[ $other ] ) )echo ucwords( $c[ $other ] ) . ", ";
							} 
						?></i></li><?php 
						} 
					} 
				?>
				 
				 <?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li><h5 style="margin-bottom:0px; margin-top:10px;">Comment:</h5> <?php echo $data["event"][$key]; ?></li>
				<?php } ?>
			  </ul>
		   </div>
		   <div class="col-xs-7 invoice-payment">
			  <div class="well payment-status-container">
				 <span><?php if( $branch ){ echo $branch; }else{  if( isset( $pr['company_name'] ) )echo $pr['company_name']; } ?></span>
				 <ul class="list-unstyled">
					 <li><strong>Receipt No.:</strong> #<?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?></li>
					 <li>&nbsp;</li>
					 <li><strong>Check In:</strong> <?php $key = "checkin_date"; if( isset( $data["event"][$key] ) )echo date( "d-M-Y" , doubleval( $data["event"][$key] ) ); ?></li>
					 
					 <?php 
						if( $booked ){
					 ?>
					 <li><strong>Check Out:</strong> <?php $key = "checkout_date"; if( isset( $data["event"][$key] ) )echo date( "d-M-Y" , doubleval( $data["event"][$key] ) ); ?></li>
					<?php } ?>
				  </ul>
				 
				 <?php if( $show_buttons || ( $room_id && $show_payment_buttons ) ){ ?>
				  <div class="btn-group btn-group-justified">
					
					<?php if( $room_id && $show_payment_buttons ){ ?>
					<a class="btn btn-sm red custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" mod="<?php echo $room_id; ?>" action="?module=&action=hotel_checkin&todo=pay_bills_after_checkin" href="#">Capture Payment</a>
					<?php } ?>
					
					<?php if( $show_buttons ){ ?>
					<a class="btn btn-sm dark default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=hotel_checkin&todo=delete_app_hotel_checkin" href="#"><i class="icon-trash"></i> <?php echo $delete_caption; ?></a>
					<?php } ?>
					
				</div>
				<?php } ?>
				
				 <?php if( $show_group_checkout_buttons ){ ?>
				  <div class="btn-group btn-group-justified">
					<a class="btn btn-sm red custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=hotel_room_checkin&todo=check_out_group" mod="<?php echo $room_id; ?>" href="#" title="Confirm Bill & Check Out all members of the Group that are still checked in">Confirm Bill & Check Out the Group</a>
				</div>
				<?php } ?>
				
				 <?php if( $show_guest_checkout_buttons ){ ?>
				  <div class="btn-group btn-group-justified">
					<a class="btn btn-sm red custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=hotel_checkin&todo=update_guest_check_out_status_form" mod="<?php echo $room_id; ?>" href="#" title="Confirm Bill & Check Out"><?php if( $room_guest )echo "Check Out: <strong>" . $room_guest . "</strong>"; else echo "Confirm Bill & Check Out"; ?></a>
				</div>
				<?php } ?>
				
			  </div>
		   </div>
		</div>
		<?php
			/*
			if( $booked ){
				include "hotel-booking-receipt-booked.php";
			}else{
				include "hotel-booking-receipt-checked-in.php";
			}
			*/
			include "hotel-booking-receipt-checked-in.php";
			
			$total_plus_tax = $total;
		?>
		<div class="row">
		   <div class="col-xs-5">
			  <div class="well">
				<address>
                        <strong>Payment Details</strong><br>
                        <span style="font-size: 1em; line-height: 1.6;">
							PAID: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $amount_paid , 4 ); ?></strong></span>
                        </span>
						<?php 
							$ow = $total_plus_tax - $amount_paid;
							
							if( $ow < 0 ){
									?>
								<br />Customer Bal.: <span class="pull-right"><strong><?php $m = format_and_convert_numbers( $ow * -1 , 4 ); echo $m; ?></strong></span><br class="hidden-print" />
								<!--<br class="hidden-print" />
								 <a class="btn btn-xs red hidden-print btn-block custom-single-selected-record-button" action="?module=&action=sales&todo=refund_customer&customer=<?php echo $data["event"][ "main_guest" ]; ?>&store=<?php echo $data["event"][ "hotel" ]; ?>" override-selected-record="<?php echo $ow * -1; ?>" mod="<?php echo $data["event"][ "id" ]; ?>" title="Click to refund guest <?php echo $m; ?>" >Refund Guest</a>-->
								<?php
							}else{
								?>
								<span style="font-size: 1em; line-height: 1.6;">
								<br />
								OWING: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $ow , 4 ); ?></strong></span>
								</span><br class="hidden-print" />
								<?php
							}
						?>
                     </address>
                     
			  </div>
		   </div>
		   <div class="col-xs-7 invoice-block">
			  <ul class="list-unstyled amounts">
				
				 <li style="font-size:1.5em;"><strong>Net Total:</strong> <?php echo convert_currency( $total_plus_tax ); ?></li>
				 
				 <?php $key = "staff_responsible"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li style=""><strong>by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
				 <?php } ?>
				 
			  </ul>
			  <br>
			  <?php if( ! $backend ){ ?>
			  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print Invoice <i class="icon-print"></i></a>
			  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
			  <?php }else{ ?>
			  <a href="../?page=print-hotel-invoice&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue hidden-print">Print Preview <i class="icon-print"></i></a>
			  <?php } ?>
		   </div>
		</div>
		<style type="text/css">
			.payment-status-container{
				background-image:url(<?php
					if( ( isset( $ow ) && $ow <= 0 ) || ( $total_plus_tax - $amount_paid ) <= 0 ){
						$stamp = "paid-in-full.png";
					}
					echo $pr["domain_name"]."images/" . $stamp;
				?>);
				background-position:right top;  background-repeat:no-repeat;  background-size:100px;
			}
		  </style>
	  
		<?php }else{ ?>
			<div class="alert alert-danger">
				<h3>Cannot Retrieve Receipt</h3>
				<p>Multiple Records Selected, please do select a single sales record to view its receipt</p>
			</div>
		<?php } ?>
	 </div>
		<!-- END ABOUT INFO -->   
	 
	  
	</div>
	<!-- END CONTAINER -->

</div>
</div>
<!-- END PAGE CONTAINER -->  