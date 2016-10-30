<?php
if( isset( $data['reservation_record'] ) && is_array( $data['reservation_record'] ) ){
		$customers = get_customers();
		$room_types = get_hotel_room_types();
		
		//$emp = get_employees();
		$owe = 0;
		$first = 1;
		$today = mktime(0,0,0,date("n"), date("j"), date("Y") );
		
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
						  <th>Room</th>
						  <th>Details</th>
						  <th class="r">Check Out</th>
					   </tr>
					</thead>
					<tbody>
				<?php
			}
			$owe = 1;
			
			?>
			<tr class="item-sales" data-id="<?php echo $sval["id"]; ?>" id="<?php echo $sval["id"] . "-" . $sval["room"]; ?>" data-checkout_date_text="<?php echo date( "Y-m-d" , doubleval( $sval["checkout_date"] ) ); ?>" data-checkin_date_text="<?php echo date( "Y-m-d" , doubleval( $sval["checkin_date"] ) ); ?>" data-room="<?php echo $sval['room']; ?>" data-comment="<?php echo $sval["comment"]; ?>" data-checkin_date="<?php echo $sval["checkin_date"]; ?>" data-checkout_date="<?php echo $sval["checkout_date"]; ?>" data-main_guest="<?php echo $sval["main_guest"]; ?>">
			  <td><strong><?php echo $sval["room_number"] ?></strong><br /><?php echo ( isset( $room_types[ $sval["room_type"] ] )?$room_types[ $sval["room_type"] ]:"" ) ; ?></td>
			  <td>
				Booking Ref: #<strong><?php echo $sval["serial_num"]; ?></strong>
				
				<small><?php echo ( isset( $customers[ $sval["guest"] ] )?("<br />Room Guest: <strong>".$customers[ $sval["guest"] ]."</strong>"):"" ); ?></small>
				
				<?php echo ( isset( $customers[ $sval["main_guest"] ] )?("<br />Paying Guest: <strong>".$customers[ $sval["main_guest"] ]."</strong>"):"" ); ?>
				
				
				<?php if( $sval["comment"] ){ ?>
				<br /><small><i><?php echo $sval["comment"]; ?></i></small>
				<?php } ?>
				<small>
				<br /><br /><strong>In:</strong> <?php echo date( "d-M-Y" , doubleval( $sval["checkin_date"] ) ); ?></small>
			  </td>
			   
			  <td class="r"><?php echo date( "d-M-Y" , doubleval( $sval["checkout_date"] ) ); ?><?php 
				if( $sval["checkout_date"] >= $today && $sval["checkout_date"] <= ( $today + ( 3600 * 23 ) ) ){
					?><br /><br /><span title="You have to checkout today" class="pull-right badge badge-primary"><small><strong>check-out today</strong></small></span> <?php
				}else{
					if( $sval["checkout_date"] <= $today ){
						?><br /><br /><span title="Checkout time has passed & guest have not been checked out from the room" class="pull-right badge badge-danger"><small><strong>check-out overdue</strong></small></span><?php
					}
				}
			  ?></td>
			 
			</tr>
			<?php
			
			
		}
		
		if( ! $owe ){
			?>
			<div class="alert alert-info">
				<h4><i class="icon-bell"></i> Hotel is Empty</h4>
				<p>
				Please make a reservation first, then check-in guests to view info here
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