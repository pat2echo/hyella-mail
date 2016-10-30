<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
	$sales_id = "";
	$sales_table = "sales";
	$customer = "";
	
	if( isset( $data["event"] ) && is_array( $data["event"] ) && isset( $data["purchased_items"] ) && is_array( $data["purchased_items"] ) && ! empty( $data["purchased_items"] ) ){
		$e = $data["event"];
		$s = $data["purchased_items"];
		
		$sales_id = $e["id"];
		
		$show_image = 0;
		if( isset( $data["show_item_image"] ) && $data["show_item_image"] )
			$show_image = $data["show_item_image"];
		
	}
?>
<?php
	if( isset( $e ) ){
?>
		<h4>Premium Poultry Farms Limited - Receipt of Goods</h4>
		<div class="row">
		   <div class="col-xs-6">
			  <p>Purchase Order Number: <strong>#<?php echo $e["serial_num"] . '-'.$e["id"]; ?></strong></p>
			  <p style="margin-bottom:0px; margin-top:10px;">Vendor: <strong><?php $key = "vendor"; if( isset( $e[$key] ) )echo get_select_option_value( array( "id" => $e[$key], "function_name" => "get_vendors" ) ); ?></strong></p>
			</ul>
		   </div>
		   <div class="col-xs-6 invoice-payment">
		   <div class="well" style="padding:10px;">
			<p><strong>Date:</strong> <?php echo date("d-M-Y"); ?></p>
			  <ul class="list-unstyled">
				 <li><strong>Raised By:</strong> <?php if( isset( $user_info["user_full_name"] ) )echo $user_info["user_full_name"]; ?></li>
			  </ul>
			</div>
		   </div>
	  </div>
	  <div class="row">
		  <div class="col-xs-12">
			  <div class="shopping-cart-table" id="picked-items">
				<table class="table table-striped table-hover bordered">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Item</th>
					   <th style="text-align:right;">Qty Ordered</th>
					   <th style="text-align:right;">Qty Received</th>
					</tr>
				 </thead>
				 <tbody>
				 <?php
					if( isset( $s ) && is_array( $s ) && ! empty( $s) ){
						$serial = 0;
						foreach( $s as $items ){
							++$serial;
							$q = $items["quantity_expected"];
							$q1 = $q;
							
							$item_details = get_items_details( array( "id" => $items["item"] ) );
							
							$title = "";
							$title1 = "";
							if( isset( $item_details["description"] ) ){
								$title1 = $item_details["description"];
								if( $show_image ){
									$title = '<a href="#" class="custom-single-selected-record-button" override-selected-record="' . $item_details["id"] . '" action="?module=&action=items&todo=view_item_details" title="View Details"><img src="' . $pr["domain_name"] . $item_details["image"] . '" width="40" align="left" style="margin-right:5px; border:1px solid #aaa;" />';
								}
								
								$title .= $item_details["description"] . "<br /><strong><small>#" . $item_details["barcode"] . "</small></strong>";
								
								if( $show_image ){
									$title .= '</a>';
								}
							}
							?>
							<tr class="picked-item" id="<?php echo $items["id"]; ?>" data-title="<?php echo $title1; ?>" data-quantity_expected="<?php echo $q; ?>" data-quantity="<?php echo $q1; ?>">
							   <td><?php echo $serial; ?></td>
							   <td><?php echo $title; ?></td>
							   <td align="right"><?php echo $q; ?></td>
							   <td align="right" ><input data-id="<?php echo $items["id"]; ?>" type="number" min="0" max="<?php echo $q1; ?>" value="<?php echo $q1; ?>" class="quantity form-control" style="max-width:120px;" /></td>
							</tr>
							<?php
						}
					}			
				 ?>
				 </tbody>
				 <tfoot>
				 </tfoot>
				 </table>
			  </div>
		  </div>
	  </div>
	  <div class="row">
		<div class="col-xs-6">
			<label><small>Comment [optional]</small></label>
			<input type="text" class="form-control" name="description" placeholder="Optional Comment" value="<?php echo $e["description"];  ?>" />
		</div>
		<div class="col-xs-6">
			<label><small>Received By</small></label>
			<select class="form-control" name="staff_responsible">
			<?php
				$data['staff_responsible'] = get_employees();
				if( isset( $data['staff_responsible'] ) && is_array( $data['staff_responsible'] ) ){
					foreach( $data['staff_responsible'] as $key => $val ){
						?>
						<option value="<?php echo $key; ?>">
							<?php echo $val; ?>
						</option>
						<?php
					}
				}
			?>
		 </select>
		</div>
	</div>
		<div class="row">
			<div class="col-xs-12">
				<br />
				<a class="btn pull-right red custom-single-selected-record-button" action="?module=&action=cart&todo=save_receive_goods" id="cart-finish" href="#">Confirm Receipt of Goods</a>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" class="auto-remove">
	var reference_id = "<?php echo $sales_id; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
<?php 
	}
?>