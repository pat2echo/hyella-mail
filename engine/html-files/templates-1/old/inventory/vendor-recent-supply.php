<?php
if( isset( $data['recent_supplies'] ) && is_array( $data['recent_supplies'] ) ){
	
	$type = 1;
	$plabel = "C.P / S.P";
	
?>
<div class="shopping-cart-table">
	<div class="table-responsive">
		<table class="table table-striped table-hover bordered">
		<thead>
		   <tr>
			  <th>Date</th>
			  <th>Item Description</th>
			  <th class="r">Quantity</th>
			  <th class="r">Cost Price</th>
			  <th class="r">Selling Price</th>
		   </tr>
		</thead>
		<tbody id="recent-supply-items">
		   <?php
				$items = get_items();
				$emp = get_employees();
				foreach( $data['recent_supplies'] as $sval ){
					?>
					<tr class="item-supply item-supply-<?php echo $sval["item"]; ?>" id="<?php echo $sval[ "id" ]; ?>" quantity="<?php echo $sval["quantity"]; ?>" data-item="<?php echo $sval["item"]; ?>">
					  <td><?php echo date("d-M-Y", doubleval( $sval["date"] ) ); ?>
						<?php if( isset( $emp[ $sval["staff_responsible"] ] ) ){ ?><br />by: <strong><?php echo $emp[ $sval["staff_responsible"] ]; ?></strong><?php } ?>
					  </td>
					  <td>
						<?php echo ( isset( $items[ $sval["item"] ] )?$items[ $sval["item"] ]:$sval["item"] ); ?>
						
						<?php if( isset( $sval["comment"] ) && $sval["comment"] ){ ?>
						<br /><i><?php echo $sval[ "comment" ]; ?></i>
						<?php } ?>
						
					  </td>
					  <td class="r"><?php if( $type != 2 )echo format_and_convert_numbers( $sval["quantity"], 3 ); ?></td>
					  <td class="r"><?php echo format_and_convert_numbers( $sval["cost_price"], 4 ); ?></td>
					  <td class="r"><?php echo format_and_convert_numbers( $sval["selling_price"], 4 ); ?></td>
					  
					  
				   </tr>
					<?php
				}
		?>
		</tbody>
		</table>
		<div style="display:none;" id="delete-button-holder">
			<button class="btn btn-sm dark" onclick="nwInventory.deleteStockItem();"><i class="icon-trash"></i> Delete</button>
			<button class="btn btn-sm dark" onclick="nwInventory.cancelDeleteStockItem(); return false;"> Cancel</button>
		</div>
		<div style="display:none;">
			<button id="actual-delete-inventory" class="btn btn-sm dark custom-single-selected-record-button" action="?module=&action=inventory&todo=delete_stocked_item" override-selected-record="">Actual Delete</button>
		</div>
	</div>
</div>
<?php } ?>