<!-- BEGIN ABOUT INFO -->   
<div class="invoice invoice-small" style="padding:1px;">
	<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
	<?php //print_r($data['event']); ?>
	
	<?php 
		$unit_type_text = "Qty.";
	?>
	
	<div class="row invoice-logo-small">
	   <div class="col-xs-12" style="line-height:0.5;">
		<?php 
			if( $store_name ){
				?><span class="store-name-small"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php echo $store_name; ?></span><?php 
			}else{
				$store_name = "Support";
		?>
		<img src="<?php echo $pr["domain_name"]."frontend-assets/img/logo_blue.png"; ?>" style="max-height:60px;" />
		<?php } ?>
	   </div>
	   <div class="col-xs-12 smaller-font">
		  <p style="margin-bottom:2px;"><span class="muted"><small><?php echo $support_addr;  ?></small></span>
		  <br />#<small><?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?> / <?php $key = "creation_date"; if( isset( $data["event"][$key] ) )echo date("j M Y", doubleval( $data["event"][$key] ) ); ?></small>
		  </p>
	   </div>
	</div>
	<hr>
	<div class="row invoice-no-small">
	   <div class="col-xs-12 invoice-payment">
		  <div class="" >
			 <ul class="list-unstyled">
				 <li style="font-size:7px;"><strong>Receipt No.:</strong> #<?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>-<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?></li>
				 
			  </ul>
			 
			 <?php if( $show_buttons ){ ?>
			  <div class="btn-group btn-group-justified">
				<a class="btn btn-sm red custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=update_sales_status" href="#">Update Status</a>
				<a class="btn btn-sm dark default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=delete_app_sales" href="#"><i class="icon-trash"></i> Delete</a>
			</div>
			<?php } ?>
			
		  </div>
	   <div class="col-xs-12 smaller-font">
		  <ul class="list-unstyled">
			 <?php $key = "customer"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
			 <li>customer: <strong><?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_customers" ) ); ?></strong></li>
			 <?php } ?>
			 
			 <?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "booked" ){ ?><li>status: <strong>booked</strong></li><?php } ?>
			 <?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
			 <li>comment: <strong><?php echo $data["event"][$key]; ?></strong></li>
			<?php } ?>
		  </ul>
	   </div>
	   </div>
	</div>
	<div class="row">
	   <div class="col-xs-12">
		  <table class="table table-striped table-hover">
			 <tbody>
				<tr>
				   <th class="hidden-480">#</th>
				   <th>Item</th>
				   <th ><?php echo $unit_type_text; ?></th>
				   <th style="text-align:right;" class="hidden-480">S. Price</th>
				   
				   <?php if( ! $g_discount_after_tax ){ ?>
				   <th style="text-align:right;" class="hidden-480">Discount</th>
				   <?php } ?>
				   
				   <th style="text-align:right;">Total</th>
				</tr>
				<?php 
				$total = 0;
				$serial = 0;
				$refund = 0;
				$subtotal = 0;
				$total_discount = 0;
				
				$colspan = 4;
				if( $g_discount_after_tax ){
					$colspan = 3;
				}
				
				if( isset( $data["event_items"] ) && is_array( $data["event_items"] ) && ! empty( $data["event_items"] ) ){
					foreach( $data["event_items"] as $items ){
						++$serial;
						
						$price = $items["cost"];
						$q = $items["quantity"] - $items["quantity_returned"];
						$dis = $items["discount"];
						
						$title = "";
						if( $items["item_id"] == "refund" ){
							$title = "Customer Refund";
							$refund = 1;
						}else{
							$item_details = get_items_details( array( "id" => $items["item_id"] ) );
							
							if( isset( $item_details["description"] ) ){
								$title = $item_details["description"];
							}
						}
						
						if( $g_discount_after_tax ){
							$total_discount += $dis;
							$total += ( $price * $q );
						}else{
							$total += ( $price * $q ) - $dis;
						}
						
						?>
						<tr>
						   <td class="hidden-480"><?php echo $serial; ?></td>
						   <td><?php echo $title; ?></td>
						   <td ><?php echo $q; ?></td>
						   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
						   
						   <?php if( $g_discount_after_tax ){ ?>
						   <td align="right"><?php echo format_and_convert_numbers( ( $price * $q ), 4 ); ?></td>
						   <?php }else{ ?>
						   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $dis , 4 ); ?></td>
						   <td align="right"><?php echo format_and_convert_numbers( ( $price * $q ) - $dis, 4 ); ?></td>
						   <?php } ?>
						   
						</tr>
						<?php
					}
					
					$subtotal = $total;
					
					if( ! $g_discount_after_tax ){
						$discount = 0;
						$key = "discount"; 
						if( isset( $data["event"][$key] ) && $data["event"][$key] )
							$discount = doubleval( $data["event"][$key] );
						
						$discount_type = "";
						$key = "discount_type";
						if( isset( $data["event"][$key] ) && $data["event"][$key] )
							$discount_type = $data["event"][$key];
						
						if( $discount_type == "percentage" ){
							$discounted_amount = $total * $discount / 100;
						}else{
							$discounted_amount = $discount;
						}
						$total = $total - $discounted_amount;
					}
				}
								
				if( $service_tax )$service_tax = round( $service_tax * $total / 100, 2 );
				if( $service_charge )$service_charge = round( $service_charge * $total / 100 , 2);
				if( $vat )$vat = round( $vat * $total / 100 , 2);
				
				$total += $service_charge + $service_tax + $vat;
				
				if( $g_discount_after_tax ){
					$discount = 0;
					$key = "discount"; 
					if( isset( $data["event"][$key] ) && $data["event"][$key] )
						$discount = doubleval( $data["event"][$key] );
					
					$discount_type = "";
					$key = "discount_type";
					if( isset( $data["event"][$key] ) && $data["event"][$key] )
						$discount_type = $data["event"][$key];
					
					if( $discount_type == "percentage" ){
						$discounted_amount = $total * $discount / 100;
					}else{
						$discounted_amount = $discount;
					}
					$discounted_amount += $total_discount;
					
					$total = $total - $discounted_amount;
				}
				?>
				
			 </tbody>
		  </table>
	   </div>
	</div>
	<div class="row payment-status-container">
	   <div class="col-xs-12 invoice-block">
		  <ul class="list-unstyled amounts smaller-font">
			<?php
				
				if( $discount ){
					?>
					<li><strong>Sub - Total Amount:</strong> <?php echo format_and_convert_numbers( $subtotal, 4 ); ?></li>
					<?php
						if( ! $g_discount_after_tax ){
					?>
						<li><strong>Discount:</strong> <?php echo format_and_convert_numbers( $discounted_amount , 4 ); ?></li>
						<?php
					}
				}
				if( $service_charge ){
					?>
					<li><strong>Service Charge <?php echo $iservice_charge; ?>% :</strong> <?php echo format_and_convert_numbers( $service_charge , 4 ); ?></li>
					<?php
				}
				if( $service_tax ){
					?>
					<li><strong>Service Tax <?php echo $iservice_tax; ?>% :</strong> <?php echo format_and_convert_numbers( $service_tax , 4 ); ?></li>
					<?php
				}
				if( $vat ){
					?>
					<li><strong>VAT <?php echo $ivat; ?>% :</strong> <?php echo format_and_convert_numbers( $vat , 4 ); ?></li>
					<?php
				}
				if( $discount ){
					if( $g_discount_after_tax ){
						?>
						<li><strong>Discount:</strong> <?php echo number_format( $discounted_amount, 2 ); ?></li>
						<?php
					}
				}
			?>
			<!--
			 <li><strong>Sub - Total amount:</strong> $9265</li>
			 <li><strong>Discount:</strong> 12.9%</li>
			 <li><strong>VAT:</strong> -----</li>
			 -->
			 <li><strong><small ><?php if( $refund )echo "Refund"; else echo "Net Total"; ?>:</small></strong> <?php echo format_and_convert_numbers( $total, 4 ); ?></li>
			 
			 <?php $key = "staff_responsible"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
			 <li><strong >sold by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
			 <?php } ?>
			 
		  </ul>
	   </div>
	   <?php if( ! ( isset( $refund ) && $refund ) ){ ?>
	   <div class="col-xs-12 smaller-font">
			 <address>
				<strong>Payment Details</strong><br>
				<span >
					PAID: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $amount_paid , 4 ); ?></strong></span><br />
					<?php if( ! $refund ){ ?>
					OWING: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $total - $amount_paid , 4 ); ?></strong></span>
					<?php } ?>
				</span>
				<?php if( 1 == 2 ){ ?>
				<br />
				<br />
				<strong><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php 
					echo ucwords( $store_name );
				?></strong><br>
				<a href="tel:<?php echo $support_line; ?>"><?php echo $support_line; ?></a><br />
				<a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a>
				<?php } ?>
			 </address>
			 
			 <?php 
				if( $support_msg ){ 
					?><div style="text-align:center;"><?php echo $support_msg; ?></div><?php
				} 
			 ?>
	   </div>
	   <?php } ?>
	</div>
	<?php if( ! $backend ){ ?>
		  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print Invoice <i class="icon-print"></i></a>
		  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
		  <?php }else{ ?>
		  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue hidden-print">Print Preview <i class="icon-print"></i></a>
		  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>&pos=1" target="_blank" class="btn dark hidden-print">POS Print Preview <i class="icon-print"></i></a>
		  
		  <br /><br /><br /><br />
		  <?php } ?>
	<style type="text/css">
		.payment-status-container{
			background-image:url(<?php
				if( ( $total - $amount_paid ) <= 0 ){
					$stamp = "paid-in-full.png";
				}
				
				if( ! ( isset( $refund ) && $refund ) ){
					echo $pr["domain_name"]."images/" . $stamp;
				}
			?>);
			background-position:center top;  background-repeat:no-repeat;  background-size:100px;
		}
	  </style>
  
	<?php }else{ ?>
		<div class="alert alert-danger">
			<h3>Cannot Retrieve Receipt</h3>
			<p>Multiple Records Selected, please do select a single sales record to view its receipt</p>
		</div>
	<?php } ?>
 </div>
<!-- END ABOUT INFO --> 