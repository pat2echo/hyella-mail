<?php
	$new_record = 0;
	if( isset( $data[ 'new_record' ] ) )
		$new_record = $data[ 'new_record' ];
	
	$no_delete = 0;
	if( isset( $data[ 'no_delete' ] ) )
		$no_delete = $data[ 'no_delete' ];
		
	$no_edit = 0;
	if( isset( $data[ 'no_edit' ] ) )
		$no_edit = $data[ 'no_edit' ];
	
	$emp = get_employees();
	if( isset( $data["items"] ) && is_array( $data["items"] ) && $data["items"] ){
		$serial = 0;
		$rooms = get_hotel_rooms();
		
		foreach( $data["items"] as $val ){
			
		?>		
		<tr id="hotel_room_checkin-<?php echo $val["id"]; ?>">
			<td><?php if( $new_record )echo '<strong>**new</strong>'; else echo ++$serial; ?></td>
			<td><?php 
				$price = $val["rate"];
				$q = $val["quantity"];
				
				$title = "";
				if( isset( $rooms[ $val['room'] ] ) ){
					$title = $rooms[ $val['room'] ] ;
				}
				
				echo $title;
				echo "<br /><small>DISCOUNT: <strong>".format_and_convert_numbers( $val["discount"] , 4 )."</strong></small>";
				echo "<br /><small>".$val["comment"]."</small>";
			?></td>
			<td><?php echo date( "d-M-Y", doubleval( $val['checkin_date'] ) ); ?></td>
			<td><?php echo date( "d-M-Y" , doubleval( $val['checkout_date'] ) ); ?></td>
			
			<td>
				<?php if( ! $no_edit ){ ?>
				<a href="#" function-id="1" function-class="hotel_room_checkin" skip-title="1" function-name="edit_hotel_room_checkin" module="" title="Click Here to Edit Record" class="btn dark btn-sm custom-action-button" month-id="<?php echo $val["id"]; ?>" budget-id="<?php echo $val["booking_ref"]; ?>" ><i class="icon-edit"></i></a> 
				<?php } ?>
				<?php if( ! $no_delete ){ ?>
				
					<a href="#" function-id="1" function-class="hotel_room_checkin" function-name="delete_hotel_room_checkin" module="" title="Click Here to Delete Record" class="btn dark btn-sm custom-action-button" skip-title="1" month-id="<?php echo $val["id"]; ?>" budget-id="<?php echo $val["booking_ref"]; ?>" ><i class="icon-trash"></i></a> 
					
				<?php } ?>
			</td>
		</tr>
		<?php
		}
	}
?>