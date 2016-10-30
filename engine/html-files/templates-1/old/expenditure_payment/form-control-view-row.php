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
	
	$pm = get_payment_method();
	$emp = get_employees();
	if( isset( $data["items"] ) && is_array( $data["items"] ) && $data["items"] ){
		$serial = 0;
		foreach( $data["items"] as $val ){
			
		?>		
		<tr id="payment-<?php echo $val["id"]; ?>">
			<td><?php if( $new_record )echo '<strong>**new</strong>'; else echo ++$serial; ?></td>
			<td><?php 
				$price = $val["amount_paid"];
				echo date("d-M-Y" , doubleval( $val["date"] ) );
				
				if( isset( $pm[ $val["payment_method"] ] ) )echo '<br /><strong>' . $pm[ $val[ "payment_method" ] ] . '</strong>';
			?></td>
			<td><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
			<td><?php echo isset( $emp[ $val["staff_responsible"] ] )?$emp[ $val[ "staff_responsible" ] ]:$val[ "staff_responsible" ]; ?></td>
			
			<td>
				<?php if( ! $no_edit ){ ?>
				<a href="#" function-id="1" function-class="expenditure_payment" function-name="delete_expenditure_payment" module="" title="Click Here to Edit Record" class="btn dark btn-sm custom-action-button" month-id="<?php echo $val["id"]; ?>" budget-id="<?php echo $val["expenditure_id"]; ?>" ><i class="icon-edit"></i></a> 
				<?php } ?>
				<?php if( ! $no_delete ){ ?>
				
					<a href="#" function-id="1" function-class="expenditure_payment" function-name="delete_expenditure_payment_and_refresh" module="" title="Click Here to Delete Record" class="btn dark btn-sm custom-action-button" month-id="<?php echo $val["id"]; ?>" budget-id="<?php echo $val["expenditure_id"]; ?>" ><i class="icon-trash"></i></a> 
					
				<?php } ?>
			</td>
		</tr>
		<?php
		}
	}
?>