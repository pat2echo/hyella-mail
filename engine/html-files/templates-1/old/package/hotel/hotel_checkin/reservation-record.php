<?php
if( isset( $data['reservation_record'] ) && is_array( $data['reservation_record'] ) ){
		$customers = get_customers();
		$room_types = get_hotel_room_types();
		
		//$emp = get_employees();
		$owe = 0;
		$first = 1;
		foreach( $data['reservation_record'] as $sval ){
			
			$a = 0;
			$c = 0;
			
			if( $first ){
				$first = 0;
				?>
				<div class="shopping-cart-table">
				<div class="table-responsive">
					<table class="table table-striped table-hover bordered">
					<thead>
					   <tr>
						  <th>Date</th>
						  <th>Details</th>
						  <th class="r">Rate</th>
						  <th class="r">Status</th>
					   </tr>
					</thead>
					<tbody>
				<?php
			}
			$owe = 1;
			
			?>
			<tr class="item-sales" data-id="<?php echo $sval["id"]; ?>" id="<?php echo $sval["id"] . "-" . $sval["room_type_id"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-checkout_date_text="<?php echo date( "Y-m-d" , doubleval( $sval["checkout_date"] ) ); ?>" data-checkin_date_text="<?php echo date( "Y-m-d" , doubleval( $sval["checkin_date"] ) ); ?>" data-amount_paid="<?php echo $sval["amount_paid"]; ?>" data-room_type="<?php echo $sval['room_type']; ?>" data-quantity="<?php echo $sval["quantity"]; ?>" data-customer="<?php echo $sval["main_guest"]; ?>" data-hotel="<?php echo $sval["hotel"]; ?>" data-booking_status="<?php echo $sval["booking_status"]; ?>" data-comment="<?php echo $sval["comment"]; ?>" data-rate="<?php echo $sval["rate"]; ?>" data-room_type_id="<?php echo $sval["room_type_id"]; ?>" data-checkin_date="<?php echo $sval["checkin_date"]; ?>" data-checkout_date="<?php echo $sval["checkout_date"]; ?>" data-deposit="<?php echo $sval["deposit"]; ?>" data-guest="<?php echo $sval["main_guest"]; ?>" data-number_of_people="<?php echo ( $sval['number_of_adults'] + $sval['number_of_children'] ); ?>" data-room_type_text="<?php if( isset( $room_types[ $sval['room_type'] ] ) )echo $room_types[ $sval['room_type'] ]; ?>" data-main_guest_text="<?php if( isset( $customers[ $sval['main_guest'] ] ) )echo $customers[ $sval['main_guest'] ]; ?>">
			  <td>
				<?php echo date( "d-M-Y" , doubleval( $sval["date"] ) ); ?>
			  </td>
			  <td>
				#<strong><?php echo $sval["serial_num"]; ?></strong> - 
				<?php 
					if( isset( $room_types[ $sval['room_type'] ] ) )echo $room_types[ $sval['room_type'] ]; else echo $sval['room_type'];
				?>
				<br /><small>Number of Rooms: <?php echo $sval["quantity"]; ?></small>
				
				<?php echo ( isset( $customers[ $sval["main_guest"] ] )?("<br /><strong>".$customers[ $sval["main_guest"] ]."</strong>"):"" ); ?>
				
				<?php if( $sval["comment"] ){ ?>
				<br /><small><i><?php echo $sval["comment"]; ?></i></small>
				<?php } ?>
				<small>
				<br /><br /><strong>In:</strong> <?php echo date( "d-M-Y" , doubleval( $sval["checkin_date"] ) ); ?>
				 | <strong>Out:</strong> <?php echo date( "d-M-Y" , doubleval( $sval["checkout_date"] ) ); ?></small>
			  </td>
			   
			  <td class="r"><strong><?php echo format_and_convert_numbers( $sval["rate"], 4 ); ?></strong><small><br />per room<br />per night</small></td>
			  
			  <td class="r amount-owed"><?php if( isset( $sval["booking_status"] ) )echo get_select_option_value( array( "id" => $sval["booking_status"], "function_name" => "get_hotel_room_booking_status" ) ); ?></td>
			 
			</tr>
			<?php
			
			
		}
		
		if( ! $owe ){
			?>
			<div class="alert alert-info">
				<h4><i class="icon-bell"></i> No Reservation Ref</h4>
				<p>
				Please make a reservation first
				</p>
			</div>
			<?php
		}else{
			?>
			</tbody>
			</table>
			</div>

			</div>
			<?php
		}
?>

<?php } ?>