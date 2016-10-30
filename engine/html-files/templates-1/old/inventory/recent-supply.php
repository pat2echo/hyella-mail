<?php
if( isset( $data['recent_supplies'] ) && is_array( $data['recent_supplies'] ) ){
	
	$type = 1;
	$plabel = "Cost Price";
	$plabel2 = "Selling Price";
	if( isset( $data['item_details']["type"] ) && $data['item_details']["type"] ){
		switch( $data['item_details']["type"] ){
		case "service":
			$type = 2;
			$plabel = $plabel2;
			$plabel2 = "";
		break;
		case "raw_materials":
			$type = 3;
			$plabel2 = "";
		break;
		}
	}
	
	$store_label = "";
	$stores = get_stores();
	if( is_array( $stores ) && count( $stores ) > 1 ){
		$store_label = "Location";
	}
?>
<div class="shopping-cart-table">
	<div class="table-responsive">
		<table class="table table-striped table-hover bordered">
		<thead>
		   <tr>
			  <th>Date</th>
			  <th class="r"><?php echo $plabel; ?></th>
			  <?php 
				switch( $type ){
				case 1:	
				?>
				<th class="r"><?php echo $plabel2; ?></th>
				<th class="r">Quantity</th>
				<?php
				break;
				case 3:	
				?>
				<th class="r">Quantity</th>
				<?php
				break;
				}
			  ?>
			  
			<th class="r">Vendor</th>
			  <?php if( $store_label ){
				  ?>
				  <th class="r"><?php echo $store_label; ?></th>
				  <?php
			  } ?>
		   </tr>
		</thead>
		<tbody id="recent-supply-items">
		   <?php
				$vendors = get_vendors();
				$emp = get_employees();
				foreach( $data['recent_supplies'] as $sval ){
					?>
					<tr class="item-supply item-supply-<?php echo $sval["item"]; ?>" id="<?php echo $sval[ "id" ]; ?>" quantity="<?php echo $sval["quantity"]; ?>" data-item="<?php echo $sval["item"]; ?>">
					  <td><?php echo date("d-M-Y", doubleval( $sval["date"] ) ); ?>
						<?php if( isset( $emp[ $sval["staff_responsible"] ] ) ){ ?><br />by: <strong><?php echo $emp[ $sval["staff_responsible"] ]; ?></strong><?php } ?>
					  </td>
					  
					  
					  <?php 
						switch( $type ){
						case 1:	
						?>
						<td class="r"><?php echo format_and_convert_numbers( $sval["cost_price"], 4 ); ?></td>
						<td class="r"><?php echo format_and_convert_numbers( $sval["selling_price"], 4 ); ?></td>
						<td class="r"><?php echo format_and_convert_numbers( $sval["quantity"], 3 ); ?></td>
						<?php
						break;
						case 3:	
						?>
						<td class="r"><?php echo format_and_convert_numbers( $sval["cost_price"], 4 ); ?></td>
						<td class="r"><?php echo format_and_convert_numbers( $sval["quantity"], 3 ); ?></td>
						<?php
						break;
						case 2:	
						?>
						<td class="r"><?php echo format_and_convert_numbers( $sval["selling_price"], 4 ); ?></td>
						<?php
						break;
						}
					  ?>
					  <td class="r">
						<?php echo ( isset( $vendors[ $sval["source"] ] )?$vendors[ $sval["source"] ]:$sval["source"] ); ?>
						
						<?php if( isset( $sval["comment"] ) && $sval["comment"] ){ ?>
						<br /><i><?php echo $sval[ "comment" ]; ?></i>
						<?php } ?>
						
					  </td>
					  
					   <?php if( $store_label ){
						  ?>
						  <td class="r"><strong><?php echo ( isset( $stores[ $sval["store"] ] )?$stores[ $sval["store"] ]:$sval["store"] ); ?></strong></td>
						  <?php
					  } ?>
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
			<a id="actual-delete-inventory" class="btn btn-sm dark custom-single-selected-record-button" action="?module=&action=inventory&todo=delete_stocked_item" override-selected-record="" href="#">Actual Delete</a>
		</div>
	</div>
</div>
<?php } ?>