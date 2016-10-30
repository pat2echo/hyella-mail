<?php
if( isset( $data['items'] ) && is_array( $data['items'] ) ){
	?>
	<form class="activate-ajax" method="post" id="sales_items" action="?action=cart&todo=save_returned_items">
	<div class="shopping-cart-table">
	<div class="table-responsive">
		<table class="table table-striped table-hover bordered">
		<thead>
		   <tr>
			  <th>S/N</th>
			  <th>Item</th>
			  <th class="r">Quantity Sold</th>
			  <th class="r">Quantity Returned</th>
		   </tr>
		</thead>
		<tbody>
		    <?php
				$serial = 0;
				
				foreach( $data['items'] as $val ){
					
					?>
					
					<tr class="item-sales" id="<?php echo $val["id"]; ?>">
					  <td><?php echo ++$serial; ?></td>
					<td><?php 
						$price = $val["cost"];
						$q = ( $val["quantity"] - $val["quantity_returned"] );
						
						$item_details = get_items_details( array( "id" => $val["item_id"] ) );
						$title = "";
						if( isset( $item_details["description"] ) ){
							echo $item_details["description"];
						}
						echo "<br />Price: <strong>" . format_and_convert_numbers( $price, 4 )."</strong>";
					?>
					</td>
					<td class="r"><?php echo format_and_convert_numbers( $q, 3 ); ?></td>
					<td class="r"><input name="quantity_returned[<?php echo $val["id"]; ?>]" type="number" step="any" class="form-control pull-right" max="<?php echo $q; ?>" style="max-width:100px;" /></td>
					
					</tr>
					<?php
				}
		?>
		</tbody>
		</table>
	</div>
	<hr />
	<input type="submit" value="Save Returned Items" class="green btn btn-lg btn-block" />
</div>
</form>
<?php } ?>