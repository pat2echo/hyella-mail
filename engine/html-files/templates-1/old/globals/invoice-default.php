<!-- BEGIN ABOUT INFO -->   
			<div class="invoice">
            <?php if( isset( $data['event'] ) && $data['event'] ){ ?>
			<?php //print_r($data['event']); ?>
			
			<?php 
				$unit_type_text = "Quantity";
			?>
			
			<div class="row invoice-logo" style="margin-bottom:0px;">
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
               <div class="col-xs-7 ">
                  <p style="margin-bottom:0;">#<small><?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?> / <?php $key = "creation_date"; if( isset( $data["event"][$key] ) )echo date("j M Y", doubleval( $data["event"][$key] ) ); ?></small>
				  <span class="muted"><small style="line-height:1.2;"><?php echo $support_addr;  ?></small></span>
				  
				  <span style="font-size:10px;"><a href="#"><?php echo $support_line; ?></a>, <a href="#"><?php echo $support_email; ?></a></span>
				  
				  </p>
				  
               </div>
            </div>
            <hr style="margin-top:10px;" />
            <div class="row ">
               <div class="col-xs-5 ">
				 <?php if( $staff ){ ?>
					<h4>Compliment to Staff:  <?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "booked" ){ $bookings = 1; ?><span class="pull-right label label-info"><small><strong><i class="icon-pushpin"></i> booked</strong></small></span><?php } ?></h4>
					<ul class="list-unstyled">
                     <li><strong><?php echo get_select_option_value( array( "id" => $staff, "function_name" => "get_employees" ) ); ?></strong></li>
				 <?php }else{ ?>
                  <h4>Client:  <?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "booked" ){ $bookings = 1; ?><span class="pull-right label label-info"><small><strong><i class="icon-pushpin"></i> booked</strong></small></span><?php } ?></h4>
                  <ul class="list-unstyled">
                     <li><strong><?php $key = "customer"; if( isset( $data["event"][$key] ) )echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_customers" ) ); ?></strong></li>
				<?php } ?>
					 
					 <?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
					 <li><h5 style="margin-bottom:0px; margin-top:10px;">Comment:</h5> <?php echo $data["event"][$key]; ?></li>
					<?php } ?>
                  </ul>
               </div>
               <div class="col-xs-7 invoice-payment">
                  <div class="well payment-status-container">
					 <span><?php  if( $branch ){ echo $branch; }else{  if( isset( $pr['company_name'] ) )echo $pr['company_name']; } ?></span>
					 <ul class="list-unstyled">
						 <li><strong>Receipt No.:</strong> #<?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>-<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?></li>
						 
					  </ul>
					 
					 <?php if( $show_buttons || $bookings ){ ?>
					  <div class="btn-group btn-group-justified">
						
						<?php if( $bookings ){ ?>
						<a class="btn btn-sm green custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=deliver_booked_sale" href="#"> Mark As Delivered</a>
						<?php } ?>
						
						<?php if( $show_buttons ){ ?>
						<a class="btn btn-sm dark default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=delete_app_sales" href="#"><i class="icon-trash"></i> Delete</a>
						<?php } ?>
						
					</div>
					<?php } ?>
					
				  </div>
               </div>
            </div>
			<div class="row">
			   <div class="col-xs-12">
					
				  <table class="table table-striped table-hover" style="margin-top:5px;">
					 <thead>
						<tr>
						   <th>#</th>
						   <th>Item</th>
						   <th class="hidden-480"><?php echo $unit_type_text; ?></th>
						   <th style="text-align:right;" class="hidden-480">S. Price</th>
						   
						   <?php if( ! $g_discount_after_tax ){ ?>
						   <th style="text-align:right;" class="hidden-480">Discount</th>
						   <?php } ?>
						   
						   <th style="text-align:right;">Total</th>
						</tr>
					 </thead>
					 <tbody>
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
								$q = $items["quantity"];
								if( isset( $items["quantity_returned"] ) )$q -= $items["quantity_returned"];
								
								$title = "";
								if( $items["item_id"] == "refund" ){
									$dis = 0;
									$title = "Customer Refund";
									$refund = 1;
								}else{
									$dis = $items["discount"];
									
									$item_details = get_items_details( array( "id" => $items["item_id"] ) );
									
									if( isset( $item_details["description"] ) ){
										if( $show_image ){
											$title = '<a href="#" class="custom-single-selected-record-button" override-selected-record="' . $item_details["id"] . '" action="?module=&action=items&todo=view_item_details" title="View Details"><img src="' . $pr["domain_name"] . $item_details["image"] . '" width="40" align="left" style="margin-right:5px; border:1px solid #aaa;" />';
										}
										
										$title .= $item_details["description"] . "<br /><strong><small>#" . $item_details["barcode"] . "</small></strong>";
										
										if( $show_image ){
											$title .= '</a>';
										}
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
								   <td><?php echo $serial; ?></td>
								   <td><?php echo $title; ?></td>
								   <td class="hidden-480"><?php echo $q; ?></td>
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
							
							if( isset( $total_title ) && $total_title ){
							?>
							<tr>
							   <td></td>
							   <td align="right" colspan="<?php echo $colspan; ?>"><strong><?php echo $total_title; ?></strong></td>
							   <td align="right"><strong><?php echo format_and_convert_numbers( $total, 4 ); ?></strong></td>
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

			<div class="row">
			  <?php if( ! ( isset( $refund ) && $refund ) ){ ?>
               <div class="col-xs-5">
                  <div class="well">
                     <address>
                        <strong>Payment Details </strong><br>
                        <span style="font-size: 1em; line-height: 1.6;">
							PAID: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $amount_paid , 4 ); ?></strong></span>
                        </span>
						<?php if( ! $reference ){ ?>
						<?php 
							if( isset( $data['all_transactions']["amount_due"] ) && isset( $data['all_transactions']["amount_paid"] ) && isset( $data['all_transactions']["discount"] ) ){
								?>
								<br /><br />
								<strong><small>All Transaction Details</small></strong><br>
								<span style="font-size: 0.9em; line-height: 1.6;">
									Amount Due: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $data['all_transactions']["amount_due"] - $data['all_transactions']["discount"] , 4 ); ?></strong></span><br />
									Amount Paid: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $data['all_transactions']["amount_paid"] , 4 ); ?></strong></span><br />
									<?php 
										$ow = ( $data['all_transactions']["amount_due"] - $data['all_transactions']["discount"] ) - $data['all_transactions']["amount_paid"];
										if( $ow > 0 ){
											?>
											Total Debt: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $ow , 4 ); ?></strong></span>
											<?php
										}
										if( $ow < 0 ){
											?>
											Customer Bal.: <span class="pull-right"><strong><?php $m = format_and_convert_numbers( $ow * -1 , 4 ); echo $m; ?></strong></span><br class="hidden-print" /><br class="hidden-print" />
											 <a class="btn btn-xs red hidden-print btn-block custom-single-selected-record-button" action="?module=&action=sales&todo=refund_customer&customer=<?php echo $data["event"][ "customer" ]; ?>&store=<?php echo $data["event"][ "store" ]; ?>" override-selected-record="<?php echo $ow * -1; ?>" mod="<?php echo $data["event"][ "id" ]; ?>" title="Click to refund customer <?php echo $m; ?>" >Refund Customer</a>
											<?php
										}
									?>
								</span>
								<?php
							}else{
								if( ! $refund ){
								?>
								<span style="font-size: 1em; line-height: 1.6;">
								<br />
								OWING: <span class="pull-right"><strong><?php echo number_format( $total - $amount_paid , 2 ); ?></strong></span>
								</span>
								<?php
								}
							}
						?>
						<?php } ?>
                     </address>
                     
                  </div>
               </div>
			  <?php } ?>
               <div class="col-xs-7 invoice-block <?php if( $refund ){ ?> col-md-offset-5 <?php } ?>">
                  <ul class="list-unstyled amounts">
					<?php
						if( ! $reference ){
							
						if( $discount ){
							?>
							<li><strong>Sub - Total Amount:</strong> <?php echo number_format( $subtotal , 2 ); ?></li>
							<?php
							if( ! $g_discount_after_tax ){
								?>
								<li><strong>Discount:</strong> <?php echo number_format( $discounted_amount, 2 ); ?></li>
								<?php
							}
						}
						if( $service_charge ){
							?>
							<li><strong>Service Charge <?php echo $iservice_charge; ?>% :</strong> <?php echo number_format( $service_charge , 2 ); ?></li>
							<?php
						}
						if( $service_tax ){
							?>
							<li><strong>Service Tax <?php echo $iservice_tax; ?>% :</strong> <?php echo number_format( $service_tax , 2 ); ?></li>
							<?php
						}
						if( $vat ){
							?>
							<li><strong>VAT <?php echo $ivat; ?>% :</strong> <?php echo number_format( $vat , 2 ); ?></li>
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
                     <li style="font-size:1.3em;"><strong><?php if( $refund )echo "Refund"; else echo "Net Total"; ?>:</strong> <?php echo convert_currency( $total ); ?></li>
                     <?php } ?>
					 
					 <?php $key = "staff_responsible"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
					 <li style=""><strong>by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
					 <?php } ?>
					 
                  </ul>
                  <br>
				  <?php if( ! $backend ){ ?>
                  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print Invoice <i class="icon-print"></i></a>
				  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
				  <?php }else{ ?>
				  <!--<a href="../?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>&pos=1" class="btn dark hidden-print"><i class="icon-print"></i></a>-->
				  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>&pos=1" target="_blank" class="btn dark hidden-print">POS Print Preview </a>
				  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue hidden-print">Print Preview <i class="icon-print"></i></a><br /><br /><br />
				  <?php } ?>
               </div>
            </div>
			<style type="text/css">
				.payment-status-container{
					background-image:url(<?php
						if( ( isset( $ow ) && $ow <= 0 ) || ( $total - $amount_paid ) <= 0 ){
							$stamp = "paid-in-full.png";
						}
						if( ! ( isset( $refund ) && $refund ) ){
							echo $pr["domain_name"]."images/" . $stamp;
						}
					?>);
					background-position:right top;  background-repeat:no-repeat;  background-size:100px;
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