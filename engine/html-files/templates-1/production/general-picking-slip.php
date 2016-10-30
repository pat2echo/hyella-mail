<div class="page-container" id="picking-slip-<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>">
<?php
	include dirname( dirname( __FILE__ ) ) . "/globals/invoice-css.php"; 
?>
<div class="container" id="invoice-container">
<div class="invoice">
<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
	<?php 
		$backend = 0;
		if( isset( $data["backend"] ) && $data["backend"] )
			$backend = $data["backend"];
		
		$show_buttons = 1;
		if( isset( $data["hide_buttons"] ) && $data["hide_buttons"] )
			$show_buttons = 0;
		
		$pr = get_project_data();
		
		$support_line = "";
		if( isset( $pr['support_line'] ) )$support_line = $pr['support_line'];
		
		$support_email = "";
		if( isset( $pr['support_email'] ) )$support_email = $pr['support_email'];
		
		$support_addr = "";
		if( isset( $pr['street_address'] ) )$support_addr = $pr['street_address'] . " " . $pr['city'] ." ". $pr['state'];
		
		$store_name = "";
		$branch = "";
		$store = array();
		if( isset( $data['event']["store"] ) && $data['event']["store"] ){
			$store = get_store_details( array( "id" => $data['event']["store"] ) );
			
			if( isset( $store["phone"] ) ){
				//test for sub location
				if( $store["name"] != "." ){ 
					$store1 = get_store_details( array( "id" => $store["name"] ) );
					if( isset( $store1["phone"] ) ){
						$branch = $store["address"];
						$store = $store1;
					}
				}
				$store_name = $store["name"];
				$support_line = $store["phone"];
				$support_addr = $store["address"];
				$support_email = $store["email"];
				$support_msg = $store["comment"];
				
				if( $store_name == "." ){ $store_name = " "; }
			}
		}
		
		$show_image = 0;
		if( isset( $data["show_item_image"] ) && $data["show_item_image"] )
			$show_image = $data["show_item_image"];
		
		$st = get_stores();
		$source_name = ( isset( $st[ $data['event']["store"] ] )?$st[ $data['event']["store"] ]:"" );
		$factory_name = ( isset( $st[ $data['event']["factory"] ] )?$st[ $data['event']["factory"] ]:"" );
		
		$e = $data["event"];
		
		$show_signature = 0;
		if( ! $backend ){
			$show_signature = get_show_signature_in_stock_requisition_settings();
		}
	?>
		<h4><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php echo $store_name; ?> - Stock Requisition Slip</h4>
		<div class="row">
		   <div class="col-xs-6">
			  <label><small><strong>Reason</strong></small></label><br />
			 <?php echo get_select_option_value( array( "id" => $e["status"], "function_name" => "get_stock_status" ) ); ?>
			 
			 <?php if( $show_signature ){ ?>
			 <br /><label><small><strong>Comment</strong></small></label><br />
			<?php echo $e["comment"]; ?>
			 <?php } ?>
		   </div>
		   <div class="col-xs-6 invoice-payment">
		   <div class="well" style="padding:10px;">
			<p><strong>Date:</strong> <?php echo date("d-M-Y", doubleval( $e["date"] ) ); ?></p>
			  <ul class="list-unstyled">
				 <li><strong>Stock Req. Slip No:</strong> #<?php echo mask_serial_number( $e["serial_num"], 'IN' ); ?></li>
				 <li><strong>Raised By:</strong> <?php $key = "created_by"; if( isset( $e[$key] ) )echo get_select_option_value( array( "id" => $e[$key], "function_name" => "get_employees" ) ); ?></li>
			  </ul>
			</div>
		   </div>
	  </div>
	  <div class="row">
		  <div class="col-xs-12">
			<div class="shopping-cart-table">
			<table class="table table-striped table-hover">
			 <thead>
				<tr>
				   <th>#</th>
				   <th>Item</th>
				   <th style="text-align:right;">Opening Stock</th>
				   <th style="text-align:right;">Quantity Picked</th>
				   <th style="text-align:right;">Closing Stock</th>
				</tr>
			 </thead>
			 
			 <tbody>
				 <?php
					if( isset( $data["materials"] ) && is_array( $data["materials"] ) && ! empty( $data["materials"] ) ){
						$serial = 0;
						$total = 0;
						$total1 = 0;
						foreach( $data["materials"] as $items ){
							++$serial;
							$q = $items["quantity"];
							$q1 = $items["quantity_instock"];
							
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
							$total += $q;
							$total1 += $q1;
							?>
							<tr>
							   <td><?php echo $serial; ?></td>
							   <td><?php echo $title; ?></td>
							   <td align="right"><?php echo $q1; ?></td>
							   <td align="right"><?php echo $q; ?></td>
							   <td align="right"><?php echo ( $q1 - $q ); ?></td>
							</tr>
							<?php
						}
						?>
						<tr>
						   <td colspan="2"><strong>TOTAL</strong></td>
						   <td align="right"><strong><?php echo format_and_convert_numbers( $total1 , 4 ); ?></strong></td>
						   <td align="right"><strong><?php echo format_and_convert_numbers( $total , 4 ); ?></strong></td>
						   <td align="right"><strong><?php echo format_and_convert_numbers( $total1 - $total , 4 ); ?></strong></td>
						</tr>
						<?php
					}			
				 ?>
			 </tbody>
			 </table>
		  </div>
		  </div>
	  </div>
	  <?php if( $show_signature ){ ?>
	  <div class="row">
		<div class="col-xs-6">
			<label><small><strong>Raised By</strong></small></label><br />
			<?php $key = "created_by"; if( isset( $e[$key] ) )echo get_select_option_value( array( "id" => $e[$key], "function_name" => "get_employees" ) ); ?>
			<br /><label><small>Signature: _______________</small></label>
		</div>
		<div class="col-xs-6">
			<label><small><strong>Staff Responsible</strong></small></label><br />
			<?php $key = "staff_responsible"; if( isset( $e[$key] ) )echo get_select_option_value( array( "id" => $e[$key], "function_name" => "get_employees" ) ); ?>
			<br /><label><small>Signature: _______________</small></label>
		</div>
	</div>
	  <?php }else{ ?>
	  <div class="row">
		<div class="col-xs-6">
			<label><small><strong>Comment</strong></small></label><br />
			<?php echo $e["comment"]; ?>
		</div>
		<div class="col-xs-6">
			<label><small><strong>Staff Responsible</strong></small></label><br />
			<?php $key = "staff_responsible"; if( isset( $e[$key] ) )echo get_select_option_value( array( "id" => $e[$key], "function_name" => "get_employees" ) ); ?>
		</div>
	</div>
	<?php } ?>
	
	<div class="row">
		<div class="col-xs-12">
			<br />
			<?php if( ! $backend ){ ?>
			  <a class="btn pull-right blue hidden-print" onclick="javascript:window.print();">Print Invoice <i class="icon-print"></i></a>
			  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
			<?php }else{ ?>
			  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-manifest&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue pull-right hidden-print">Print Preview <i class="icon-print"></i></a><br /><br /><br /><br />
			<?php } ?>
		</div>
	</div>
</div>
</div>
			
<?php }else{ ?>
	Error Message
<?php } ?>
</div>
<!-- END PAGE CONTAINER -->  