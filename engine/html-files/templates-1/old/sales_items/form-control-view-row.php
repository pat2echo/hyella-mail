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
		foreach( $data["items"] as $val ){
			
		?>		
		<tr id="sales_items-<?php echo $val["id"]; ?>">
			<td><?php if( $new_record )echo '<strong>**new</strong>'; else echo ++$serial; ?></td>
			<td><?php 
				$price = $val["cost"];
				$q = ( $val["quantity"] - $val["quantity_returned"] );
				
				$item_details = get_items_details( array( "id" => $val["item_id"] ) );
				$title = "";
				if( isset( $item_details["description"] ) ){
					echo $item_details["description"];
				}
			?></td>
			<td><?php echo format_and_convert_numbers( $q, 3 ); ?></td>
			<td><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
			
			<td>
				<?php if( ! $no_edit ){ ?>
				<a href="#" function-id="1" function-class="sales_items" function-name="edit_sales_items" module="" title="Click Here to Edit Record" class="btn dark btn-sm custom-action-button" month-id="<?php echo $val["id"]; ?>" budget-id="<?php echo $val["sales_id"]; ?>" ><i class="icon-edit"></i></a> 
				<?php } ?>
				<?php if( ! $no_delete ){ ?>
				
					<a href="#" function-id="1" function-class="sales_items" function-name="delete_sales_items" module="" title="Click Here to Delete Record" class="btn dark btn-sm custom-action-button" month-id="<?php echo $val["id"]; ?>" budget-id="<?php echo $val["sales_id"]; ?>" ><i class="icon-trash"></i></a> 
					
				<?php } ?>
			</td>
		</tr>
		<?php
		}
	}
?>