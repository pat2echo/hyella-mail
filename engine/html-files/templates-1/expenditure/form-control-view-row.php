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
		
	if( isset( $data["items"] ) && is_array( $data["items"] ) && $data["items"] ){
		$serial = 0;
		foreach( $data["items"] as $val ){
			
		?>		
		<tr id="expenditure-<?php echo $val["id"]; ?>">
			<td><?php if( $new_record )echo '<strong>**new</strong>'; else echo ++$serial; ?></td>
			<td><?php 
				$due = $val["amount_due"];
				$paid = $val["amount_paid"];
				
				echo $val["description"];
			?></td>
			<td><?php echo format_and_convert_numbers( $due, 4 ); ?></td>
			<td><?php echo format_and_convert_numbers( $paid, 4 ); ?></td>
			
			<td>
				<?php if( ! $no_edit ){ ?>
				<a href="#" function-id="1" function-class="expenditure" function-name="edit_expenditure" module="" title="Click Here to Edit Record" class="btn dark btn-sm custom-action-button" month-id="<?php echo $val["id"]; ?>" budget-id="<?php echo $val["production_id"]; ?>" ><i class="icon-edit"></i></a> 
				<?php } ?>
				<?php if( ! $no_delete ){ ?>
				
					<a href="#" function-id="1" function-class="expenditure" function-name="delete_expenditure" module="" title="Click Here to Delete Record" class="btn dark btn-sm custom-action-button" month-id="<?php echo $val["id"]; ?>" budget-id="<?php echo $val["production_id"]; ?>" ><i class="icon-trash"></i></a> 
					
				<?php } ?>
			</td>
		</tr>
		<?php
		}
	}
?>