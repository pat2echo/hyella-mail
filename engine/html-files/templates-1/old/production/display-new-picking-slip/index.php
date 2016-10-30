<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
	$sales_id = "";
	$sales_table = "sales";
	$customer = "";
	
	if( isset( $data["sales_info"]["event"] ) && is_array( $data["sales_info"]["event"] ) && isset( $data["sales_info"]["event_items"] ) && is_array( $data["sales_info"]["event_items"] ) && ! empty( $data["sales_info"]["event_items"] ) ){
		$e = $data["sales_info"]["event"];
		$s = $data["sales_info"]["event_items"];
		
		$pr = get_project_data();
		$store_name = '';
		
		$html = "";
		if( isset( $data[ "picking_slips" ] ) && is_array( $data[ "picking_slips" ] ) && ! empty( $data[ "picking_slips" ] ) ){
			$d = $data[ "picking_slips" ];
			$serial = 0;
			
			$emp = array();
			if( isset( $data['staff_responsible'] ) && is_array( $data['staff_responsible'] ) ){
				$emp = $data['staff_responsible'];
			}
			
			$ids = array();
			$item_picked = array();
			
			foreach( $d as $sval ){
				++$serial;
				
				if( ! isset( $item_picked[ $sval["item_id"] ] ) ){
					$item_picked[ $sval["item_id"] ]["quantity"] = 0;
				}
				
				$item_picked[ $sval["item_id"] ]["quantity"] += $sval["unit_quantity"];
				
				if( ! isset( $ids[ $sval["id"] ] ) ){
					$desc = '';
					$issuer = '';
					if( isset( $emp[ $sval["staff_responsible"] ] ) )
						$desc = $emp[ $sval["staff_responsible"] ] . '<br />';
					
					if( isset( $emp[ $sval["created_by"] ] ) )
						$issuer = $emp[ $sval["created_by"] ];
					
					$desc .= '[ '.$sval["comment"].' ]';
					
					$html .= '<tr><td>'.$serial.'</td>
						<td>'.date("d-M-Y", doubleval( $sval["date"] ) ).'</td>
						<td>'.$desc.'</td>
						<td>'.$issuer.'</td>
						<td style="text-align:right;">'.number_format( $sval["quantity"] , 2 ).'</td>
						<td style="text-align:right;"><a href="#" class="btn blue btn-xs custom-single-selected-record-button" action="?module=&action=production&todo=view_invoice" override-selected-record="'.$sval["id"].'" title="View Manifest">View Details</a></td></tr>';
						
					$ids[ $sval["id"] ] = 1;
				}
			}
		}
		$customer = $e["customer"];
		$sales_id = $e["id"];
		
		$show_image = 0;
		if( isset( $data["show_item_image"] ) && $data["show_item_image"] )
			$show_image = $data["show_item_image"];
		
	}
?>
<?php
	if( isset( $e ) ){
?>
<div class="portlet box purple" id="new-picking-slip">
	<div class="portlet-title">
		<div class="caption"><small><small>New Picking Slip</small></small></div>
	</div>
	<div class="portlet-body">
		<h4><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php echo $store_name; ?> - Picking Slips</h4>
		<div class="row">
		   <div class="col-xs-6">
			  <p>Invoice Number: <strong>#<?php echo $e["serial_num"] . '-'.$e["id"]; ?></strong></p>
			  <p style="margin-bottom:0px; margin-top:10px;">Customer: <strong><?php $key = "customer"; if( isset( $e[$key] ) )echo get_select_option_value( array( "id" => $e[$key], "function_name" => "get_customers" ) ); ?></strong></p>
			</ul>
		   </div>
		   <div class="col-xs-6 invoice-payment">
		   <div class="well" style="padding:10px;">
			<p><strong>Date:</strong> <?php echo date("d-M-Y"); ?></p>
			  <ul class="list-unstyled">
				 <li><strong>Picking Slip No:</strong> unsaved</li>
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
					   <th style="text-align:right;">Qty Picked</th>
					</tr>
				 </thead>
				 <tbody>
				 <?php
					if( isset( $s ) && is_array( $s ) && ! empty( $s) ){
						$serial = 0;
						foreach( $s as $items ){
							++$serial;
							$q = $items["quantity"];
							if( isset( $items["quantity_returned"] ) )$q -= $items["quantity_returned"];
							
							$q1 = $q;
							if( isset( $item_picked[ $items["item_id"] ]["quantity"] ) )$q1 -= $item_picked[ $items["item_id"] ]["quantity"];
							
							if( $q1 <= 0 )continue;
							
							$item_details = get_items_details( array( "id" => $items["item_id"] ) );
							
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
							<tr class="picked-item" id="<?php echo $items["item_id"]; ?>" data-title="<?php echo $title1; ?>" data-max="<?php echo $q; ?>" data-quantity_picked="<?php echo $q1; ?>"  data-cost_price="<?php echo $items["cost_price"]; ?>">
							   <td><?php echo $serial; ?></td>
							   <td><?php echo $title; ?></td>
							   <td align="right"><?php echo $q; ?></td>
							   <td align="right" ><input data-id="<?php echo $items["item_id"]; ?>" type="number" min="0" max="<?php echo $q1; ?>" value="<?php echo $q1; ?>" class="quantity form-control" style="max-width:120px;" /></td>
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
			<label><small>Picker / Comment</small></label>
			<input type="text" class="form-control" name="comment" placeholder="Optional Comment" />
		</div>
		<div class="col-xs-6">
			<label><small>Supervisor</small></label>
			<select class="form-control" name="staff_responsible">
			<?php
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
				<a class="btn pull-right red custom-single-selected-record-button" action="?module=&action=cart&todo=save_sales_order_picking_slip" id="cart-finish" href="#">Save Picking Slip</a>
			</div>
		</div>
	</div>
</div>
<?php if( $html ){ ?>
<br />
<div class="portlet box purple" >
	<div class="portlet-title">
		<div class="caption"><small><small>Picking History</small></small></div>
	</div>
	<div class="portlet-body">
	  <div class="row">
		  <div class="col-xs-12">
			  <div class="shopping-cart-table">
				<table class="table table-striped table-hover bordered">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Date</th>
					   <th>Supervisor [comment]</th>
					   <th>Issued By</th>
					   <th style="text-align:right;">Quantity</th>
					   <th style="text-align:right;"></th>
					</tr>
				 </thead>
				 <tbody>
				 <?php
					echo $html;		
				 ?>
				 </tbody>
				 </table>
			  </div>
		  </div>
	  </div>
	</div>
</div>
<?php } ?>
<script type="text/javascript" class="auto-remove">
	var reference_id = "<?php echo $sales_id; ?>";
	var reference_table = "<?php echo $sales_table; ?>";
	var customer = "<?php echo $customer; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
<?php 
	}
?>