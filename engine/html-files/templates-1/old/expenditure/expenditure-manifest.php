<!-- BEGIN PAGE CONTAINER -->  
<div class="page-container" id="manifest-<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>">
<?php
	include dirname( dirname( __FILE__ ) ) . "/globals/invoice-css.php"; 
?>
<!-- BEGIN CONTAINER -->   
	<div class="container" id="invoice-container">
		
		<!-- BEGIN ABOUT INFO -->   
		<div class="invoice">
		<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
		
		<?php 
			$backend = 0;
			if( isset( $data["backend"] ) && $data["backend"] )
				$backend = $data["backend"];
			
			$show_buttons = 1;
			if( isset( $data["hide_buttons"] ) && $data["hide_buttons"] )
				$show_buttons = 0;
			
			$show_all_buttons = 1;
			if( isset( $data["hide_all_buttons"] ) && $data["hide_all_buttons"] )
				$show_all_buttons = 0;
			
			$purchase_order = 0;
			if( isset( $data["purchase_order"] ) && $data["purchase_order"] ){
				$purchase_order = isset( $data["title"] )?$data["title"]:"Purchase Order";
			}
			
			$show_receive_button = 0;
			$total_cost_label = 'TOTAL COST OF RAW MATERIALS';	
			
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
			
			$st = get_stores();
			$source_name = ( isset( $st[ $data['event']["store"] ] )?$st[ $data['event']["store"] ]:"" );
			
			$amount_paid = ( isset( $data['event']["total_amount_paid"] )?$data['event']["total_amount_paid"]:0 );
			$amount_due = ( isset( $data['event']["amount_due"] )?$data['event']["amount_due"]:0 );
			
			$unit_type_text = "Quantity";
			
			$show_receive_button = 0;
			switch( $data['event']["status"] ){
			case "pending":
				if( $show_all_buttons ){
					$show_buttons = 1;
					$show_receive_button = 1;
				}
			break;
			}
		?>
		
		<div class="row invoice-logo">
		   <div class="col-xs-5 invoice-logo-space"><br />
			<?php 
				if( $store_name ){
					?><img src="<?php echo $pr["domain_name"]."frontend-assets/img/logo-b.png"; ?>" style="max-height:60px;" align="left" /><span class="store-name"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php echo $store_name; ?></span><?php 
				}else{
					$store_name = "Support";
			?>
			<img src="<?php echo $pr["domain_name"]."frontend-assets/img/logo-b.png"; ?>" style="max-height:60px;" />
			<?php } ?>
				
		   </div>
		   <div class="col-xs-7">
			  <p>#<small><?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?> / <?php $key = "creation_date"; if( isset( $data["event"][$key] ) )echo date("j M Y", doubleval( $data["event"][$key] ) ); ?></small> <span class="muted"><small><?php  echo $support_addr; ?></small></span></p>
		   </div>
		</div>
		
		<?php if( $purchase_order){ ?>
		<h4 style="text-align:center;"><strong><?php echo $purchase_order; ?></strong></h4>
		<?php } ?>
		<hr style="margin-top:10px;" />
			
		<div class="row">
		   <div class="col-xs-5">
			  <h4>Status:</h4>
			  <h4><strong><?php $key = "status"; if( isset( $data["event"][$key] ) )echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_expenditure_status" ) ); ?></strong></h4>
			  <ul class="list-unstyled">			  
			 <?php $key = "description"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
			 <li><i><?php echo $data["event"][$key]; ?></i></li>
			<?php } ?>
			</ul>
		   </div>
		   <div class="col-xs-7 invoice-payment">
		   <div class="well payment-status-container">
			  <span><?php if( $branch ){ echo $branch; }else{  if( isset( $pr['company_name'] ) )echo $pr['company_name']; } ?></span>
			  <ul class="list-unstyled">
				 <li><strong>Tracking No.:</strong> #<?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>-<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?></li>
			  </ul>
			  
			  <?php if( $show_buttons ){ ?>
			  <div class="btn-group btn-group-justified">
				<?php if( $show_receive_button ){ ?>
				<a class="btn btn-sm red custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=expenditure&todo=update_stock_status" href="#">Update Stock</a>
				<?php } ?>
				<!--<a class="btn btn-sm dark default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=production&todo=delete_production_manifest" href="#"><i class="icon-trash"></i> <?php if( $show_receive_button )echo $show_receive_button; else echo "Delete"; ?></a>-->
			 </div>
			  <?php } ?>
			  
			</div>
		   </div>
		</div>
		<div class="row">
		   <div class="col-xs-12">
			
		<?php if( $purchase_order ){ ?>
		<div class="row">
		   <div class="col-xs-12">
			  <table class="table table-striped table-hover">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Item</th>
					   <th style="text-align:right;" class="hidden-480">Unit Price</th>
					   <th style="text-align:right;" class="hidden-480">Quantity</th>
					   <th style="text-align:right;" class="hidden-480">Discount</th>
					   <th style="text-align:right;" class="hidden-480">Tax</th>
					   <th style="text-align:right;">Total</th>
					</tr>
				 </thead>
				 <tbody>
					<?php 
					$total = 0;
					$serial = 0;
					$total_cost_g = 0;
					$total_items_g = 0;
					
					$discount = 0;
					$tax = 0;
					$gtax = 0;
					$gdiscount = 0;
					
					if( isset( $data["purchased_items"] ) && is_array( $data["purchased_items"] ) && ! empty( $data["purchased_items"] ) ){
						$key = "status"; 
						$status = "";
						if( isset( $data["event"][$key] ) ){
							$status = $data["event"][$key];
						}
						
						$key = "percentage_discount";
						if( isset( $data["event"][ $key ] ) && $data["event"][ $key ] ){
							$discount = doubleval( $data["event"][ $key ] );
						}
						
						$key = "tax";
						if( isset( $data["event"][ $key ] ) && $data["event"][ $key ] ){
							$tax = doubleval( $data["event"][ $key ] );
						}
						
						$quantity_key = "quantity_expected";
						
						switch( $status ){
						case 'materials-transfer':
						case 'complete':
						case 'stocked':
							$quantity_key = "quantity";
						break;
						case 'draft':
							$quantity_key = "quantity_ordered";
						break;
						}
						
						foreach( $data["purchased_items"] as $items ){
							++$serial;
							
							$price = $items["cost_price"];
							$q = $items[ $quantity_key ];
							
							$item_details = get_items_details( array( "id" => $items["item"] ) );
							$title = "";
							if( isset( $item_details["description"] ) ){
								$title = $item_details["description"];
							}
							
							$t = ( $price * $q );
							
							$i_discount = ( $t * $items["discount"] / 100 );
							$t -= $i_discount;
							
							$i_tax = ( $t * $items["tax"] / 100 );
							$t += $i_tax;
							
							$total_cost_g += $t;
							$total_items_g += $q;
							
							$total += $t;
							?>
							<tr>
							   <td><?php echo $serial; ?></td>
							   <td><?php echo $title; ?></td>
							   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
							   <td align="right" class="hidden-480"><?php echo $q; ?></td>
							   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $i_discount, 4 ); ?></td>
							   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $i_tax, 4 ); ?></td>
							   <td align="right"><?php echo format_and_convert_numbers( $t , 4 ); ?></td>
							</tr>
							<?php
						}
						?>
						<tr>
							<td colspan="7">&nbsp;</td>
						</tr>
						<tr>
						   <td colspan="3"><strong>TOTAL COST</strong></td>
						   <td align="right" class="hidden-480"><strong><?php echo format_and_convert_numbers( $total_items_g , 4 ); ?></strong></td>
						   <td colspan="3" align="right"><strong><?php echo format_and_convert_numbers( $total , 4 ); ?></strong></td>
						</tr>
						<?php
						if( $discount ){
							$gdiscount = ( $total * $discount / 100 );
							$total -= $gdiscount;
						?>
						<tr>
						   <td colspan="6">DISCOUNT (<?php echo $discount; ?>%)</td>
						   <td align="right"><?php echo format_and_convert_numbers( $gdiscount , 4 ); ?></td>
						</tr>
						<?php
							}
							
						if( $tax ){
							$gtax = ( $total * $tax / 100 );
							$total += $gtax;
						?>
						<tr>
						   <td colspan="6">TAX (<?php echo $tax; ?>%)</td>
						   <td align="right"><?php echo format_and_convert_numbers( $gtax , 4 ); ?></td>
						</tr>
						<?php
							}
							
						if( $gtax || $gdiscount ){
						?>
						<tr>
						   <td colspan="6"><strong>NET TOTAL</strong></td>
						   <td align="right"><strong><?php echo format_and_convert_numbers( $total, 4 ); ?></strong></td>
						</tr>
						<?php
							}
							
						$amount_due = $total;
					}
					
					?>
					
				 </tbody>
			  </table>
		   </div>
		</div>
		<?php } ?>
			
		<div class="row">
			<div class="col-xs-6">
				<div class="well">
                     <address>
						<strong>Payment Details </strong><br>
						<span style="font-size: 1em; line-height: 1.6;">
							PAID: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $amount_paid , 4 ); ?></strong></span>
						</span>
						<?php 
							$owing = $amount_due - $amount_paid;
							if( $owing > 0 ){
						?>
						<br />
						<span style="font-size: 1em; line-height: 1.6;">
							OWING: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $owing , 4 ); ?></strong></span>
						</span>
						<?php } ?>
					 </address>
				  </div>
				  <style type="text/css">
				.payment-status-container{
					background-image:url(<?php
						$stamp = "part-payment.png";
						if( $owing <= 0 ){
							$stamp = "paid-in-full.png";
						}
						echo $pr["domain_name"]."images/" . $stamp;
					?>);
					background-position:right top;  background-repeat:no-repeat;  background-size:100px;
				}
			  </style>
			</div>
			<div class="col-xs-6" style="text-align:right;">
				<ul class="list-unstyled amounts">
				<?php $key = "staff_responsible"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li style=""><strong>ordered by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
				 <?php } ?>
				 <?php $key = "modified_by"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li style=""><strong>received by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
				 <?php } ?>
				 <li style=""><strong>destination:</strong> <?php echo $source_name; ?></li>
				</ul>
			</div>
		</div>
		<div class="row">
		  
		   <div class="col-xs-4 col-md-offset-8 invoice-block">
				
			  <?php if( ! $backend ){ ?>
			  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print Purchase Order <i class="icon-print"></i></a>
			  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
			  <?php }else{ ?>
			  <a href="../?page=print-expenditure-manifest&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue hidden-print">Print Preview <i class="icon-print"></i></a><br /><br /><br /><br />
			  <?php } ?>
		   </div>
		</div>
		
		
		<?php }else{ ?>
		Error Message
		<?php } ?>
	 </div>
		<!-- END ABOUT INFO -->   
		
	</div>
	<!-- END CONTAINER -->

</div>
<!-- END PAGE CONTAINER -->  