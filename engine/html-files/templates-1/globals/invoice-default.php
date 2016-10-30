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
						if( ! $skip_logo ){
						?><img src="<?php echo $pr["domain_name"]."frontend-assets/img/logo-b.png"; ?>" style="max-height:60px;" align="left" />
						<?php } ?>
						<span class="store-name"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php echo $store_name; ?></span><?php 
					}else{
						$store_name = "Support";
				?>
				<img src="<?php echo $pr["domain_name"]."frontend-assets/img/logo-b.png"; ?>" style="max-height:60px;" />
				<span class="store-name"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?></span>
				<?php } ?>
			   </div>
               <div class="col-xs-7 ">
                  <p style="margin-bottom:0;">#<small><?php echo $serial_number; ?> / <?php $key = "creation_date"; if( isset( $data["event"][$key] ) )echo date("j M Y", doubleval( $data["event"][$key] ) ); ?></small>
				  <span class="muted"><small style="line-height:1.2;"><?php echo $support_addr;  ?></small></span>
				  
				  <span style="font-size:10px;"><a href="#"><?php echo $support_line; ?></a>, <a href="#"><?php echo $support_email; ?></a></span>
				  
				  </p>
				  
               </div>
            </div>
			<h4 style="text-align:center;"><strong>Sales <?php echo $sales_label; ?> #<?php echo $serial_number; ?></strong></h4>
            <hr style="margin-top:10px;" />
            <?php 
				if( $show_signature ){
			?>
			<div class="row ">
				<?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
               <div class="col-xs-5 ">
                  <ul class="list-unstyled">
                     
					 <li><h5 style="margin-bottom:0px; margin-top:10px;">Comment:</h5> <?php echo $data["event"][$key]; ?></li>
					
                  </ul>
               </div>
			   <?php } ?>
			   <?php if( $show_buttons || $bookings ){ ?>
               <div class="col-xs-7 invoice-payment">
                  <div class="well payment-status-container">
					
					  <div class="btn-group btn-group-justified">
						
						<?php if( $bookings ){ ?>
						<a class="btn btn-sm green custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=deliver_booked_sale" href="#"> Mark As Delivered</a>
						<?php } ?>
						
						<?php if( $show_buttons ){ ?>
						<a class="btn btn-sm dark default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=delete_app_sales" href="#"><i class="icon-trash"></i> Delete</a>
						<?php } ?>
						
					</div>
					
					
				  </div>
               </div>
			   <?php } ?>
            </div>
			<?php 
				}else{
			?>
			<div class="row ">
               <div class="col-xs-5 ">
                  <h4>Client:  <?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "booked" ){ $bookings = 1; ?><span class="pull-right label label-info"><small><strong><i class="icon-pushpin"></i> booked</strong></small></span><?php } ?></h4>
                  <ul class="list-unstyled">
                     <li><strong><?php $key = "customer"; if( isset( $data["event"][$key] ) )echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_customers" ) ); ?></strong></li>
					 
					 
					 <?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
					 <li><h5 style="margin-bottom:0px; margin-top:10px;">Comment:</h5> <?php echo $data["event"][$key]; ?></li>
					<?php } ?>
                  </ul>
               </div>
               <div class="col-xs-7 invoice-payment">
                  <div class="well payment-status-container">
					
					 <?php if( $branch ){ ?><span><?php echo $branch; ?></span><?php } ?>
					 <ul class="list-unstyled">
						 <li><strong><?php echo $sales_label; ?> No.:</strong> #<?php echo $serial_number; ?></li>
						 
						 <li><strong>Raised By:</strong> <?php $key = "modified_by"; if( isset( $data["event"][$key] ) )echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
						 
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
			<?php 
				}
			?>
			<div class="row">
			   <div class="col-xs-12">
					
				  <table class="table table-striped table-hover" style="margin-top:5px;">
					 <thead>
						<tr>
						   <th>#</th>
						   <th>Item</th>
						   <th class="hidden-480"><?php echo $unit_type_text; ?></th>
						   <th style="text-align:right;" class="hidden-480"><?php echo $price_label; ?></th>
						   <th style="text-align:right;" class="hidden-480">Discount</th>
						   <th style="text-align:right;">Total</th>
						</tr>
					 </thead>
					 <tbody>
						<?php 
						$total = 0;
						$serial = 0;
						$refund = 0;
						$subtotal = 0;
						
						if( isset( $data["event_items"] ) && is_array( $data["event_items"] ) && ! empty( $data["event_items"] ) ){
							foreach( $data["event_items"] as $items ){
								++$serial;
								
								$price = $items["cost"];
								$q = $items["quantity"];
								if( isset( $items["quantity_returned"] ) )$q -= $items["quantity_returned"];
								
								$dis = $items["discount"];
								
								$title = "";
								if( $items["item_id"] == "refund" ){
									$title = "Customer Refund";
									$refund = 1;
								}else{
									$item_details = get_items_details( array( "id" => $items["item_id"] ) );
									
									if( isset( $item_details["description"] ) ){
										if( $show_image ){
											$title = '<a href="#" class="custom-single-selected-record-button" override-selected-record="' . $item_details["id"] . '" action="?module=&action=items&todo=view_item_details" title="View Details"><img src="' . $pr["domain_name"] . $item_details["image"] . '" width="40" align="left" style="margin-right:5px; border:1px solid #aaa;" />';
										}
										
										$title .= $item_details["description"] . "<br /><strong><small>#" . $item_details["barcode"] . "</small></strong>";
										
										if( $show_image ){
											$title .= '</a>';
										}
									}else{
										$title = ucwords( $items["item_id"] );
									}
								}
								$total += ( $price * $q ) - $dis;
								?>
								<tr>
								   <td><?php echo $serial; ?></td>
								   <td><?php echo $title; ?></td>
								   <td class="hidden-480"><?php echo $q; ?></td>
								   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
								   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $dis , 4 ); ?></td>
								   <td align="right"><?php echo format_and_convert_numbers( ( $price * $q ) - $dis, 4 ); ?></td>
								</tr>
								<?php
							}
							
							if( isset( $total_title ) && $total_title ){
							?>
							<tr>
							   <td></td>
							   <td align="right" colspan="4"><strong><?php echo $total_title; ?></strong></td>
							   <td align="right"><strong><?php echo format_and_convert_numbers( $total, 4 ); ?></strong></td>
							</tr>
							<?php
							}
							
							$subtotal = $total;
							
							$discount = 0;
							$discounted_amount = 0;
							$key = "discount"; 
							if( isset( $data["event"][$key] ) && $data["event"][$key] )
								$discount = doubleval( $data["event"][$key] );
							
							$discount_type = "";
							$key = "discount_type";
							if( isset( $data["event"][$key] ) && $data["event"][$key] )
								$discount_type = $data["event"][$key];
							
							switch( $discount_type ){
							case "fixed_value_after_tax":
							case "percentage_after_tax":
								$g_discount_after_tax = 1;
							break;
							case "fixed_value":
								$g_discount_after_tax = 0;
								$discounted_amount = $discount;
							break;
							case "percentage":
								$g_discount_after_tax = 0;
								$discounted_amount = $total * $discount / 100;
							break;
							}
							
							if( ! $g_discount_after_tax ){
								$total = $total - $discounted_amount;
							}
						}
						
						
						if( $service_tax )$service_tax = round( $service_tax * $total / 100, 2 );
						if( $service_charge )$service_charge = round( $service_charge * $total / 100 , 2);
						if( $vat )$vat = round( $vat * $total / 100 , 2);
						
						$total += $service_charge + $service_tax + $vat;
						
						switch( $discount_type ){
						case "fixed_value_after_tax":
							$g_discount_after_tax = 1;
							$discounted_amount = $discount;
						break;
						case "percentage_after_tax":
							$g_discount_after_tax = 1;
							$discounted_amount = $total * $discount / 100;
						break;
						}
						
						if( $g_discount_after_tax ){
							$subtotal = $total;
							$total = $total - $discounted_amount;
						}
						?>
					 </tbody>
				  </table>
			   </div>
			</div>

			<div class="row">
			  
               <div class="col-xs-5">
                  <?php if( ( ! ( isset( $refund ) && $refund ) ) && $amount_paid ){ ?>
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
									$ow = $total - $amount_paid;
									
									if( $ow > 0 ){
								?>
								<span style="font-size: 1em; line-height: 1.6;">
								<br />
								OWING: <span class="pull-right"><strong><?php echo number_format( $ow , 2 ); ?></strong></span>
								</span>
								<?php }
								
								if( $ow < 0 ){ ?>
									<span style="font-size: 1em; line-height: 1.6;">
									<br />
									Customer Bal.: <span class="pull-right"><strong><?php echo number_format( abs( $ow ) , 2 ); ?></strong></span><br class="hidden-print" />
									<?php if( $backend ){ ?>
									<br class="hidden-print" />
									<a class="btn btn-xs red hidden-print btn-block custom-single-selected-record-button" action="?module=&action=sales&todo=refund_customer&customer=<?php echo number_format( abs( $ow ) , 2 ); ?>&store=<?php echo $data["event"][ "store" ]; ?>" override-selected-record="<?php echo round( abs( $ow ) , 2 ); ?>" mod="<?php echo $data["event"][ "id" ]; ?>" title="Click to refund customer <?php echo number_format( abs( $ow ) , 2 ); ?>" >Refund Customer</a>
									<?php } ?>
									</span>
							<?php }
								}
							}
						?>
						<?php } ?>
                     </address>
                     
					 <?php 
						if( $support_msg ){ 
							echo $support_msg; 
						} 
					 ?>
                  </div>
				  <?php } ?>
               </div>
			  
               <div class="col-xs-7 invoice-block <?php if( $refund ){ ?> col-md-offset-5 <?php } ?>">
                  <ul class="list-unstyled amounts">
					<?php
						if( ! $reference ){
							
						if( ! $g_discount_after_tax && isset( $discount ) && $discount ){
							?>
							<li><strong>Sub - Total Amount:</strong> <?php echo number_format( $subtotal , 2 ); ?></li>
							<?php
							switch( $discount_type ){
							case "percentage":
								?>
								<li><strong><?php echo $discount."%" ?> Discount:</strong> <?php echo number_format( $discounted_amount , 2 ); ?></li>
								<?php
							break;
							default:
								?>
								<li><strong>Discount:</strong> <?php echo number_format( $discounted_amount, 2 ); ?></li>
								<?php
							break;
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
						
						if( $g_discount_after_tax && isset( $discount ) && $discount ){
							?>
							<li><strong>Sub - Total Amount:</strong> <?php echo number_format( $subtotal , 2 ); ?></li>
							<?php
							switch( $discount_type ){
							case "percentage_after_tax":
								?>
								<li><strong><?php echo $discount."%" ?> Discount:</strong> <?php echo number_format( $discounted_amount , 2 ); ?></li>
								<?php
							break;
							default:
								?>
								<li><strong>Discount:</strong> <?php echo number_format( $discounted_amount, 2 ); ?></li>
								<?php
							break;
							}
						}
					?>
                     <li style="font-size:1.3em;"><strong><?php if( $refund )echo "Refund"; else echo "Net Total"; ?>:</strong> <?php echo convert_currency( $total ); ?></li>
                     <?php } ?>
					 
					 <?php $key = "staff_responsible"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
					 <li style=""><strong>by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
					 <?php } ?>
					 
                  </ul>
                 
               </div>
            </div>
			
			<?php if( $show_signature ){ ?>
			<div class="row ">
               <div class="col-xs-6">
                  <?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "booked" ){ $bookings = 1; ?><h4><span class="pull-right label label-info"><small><strong><i class="icon-pushpin"></i> booked</strong></small></span></h4><?php } ?>
                  <ul class="list-unstyled">
                     <?php if( $staff ){ ?>
					  <li>Compliment to Staff</li>
					  <li><strong><?php echo get_select_option_value( array( "id" => $staff, "function_name" => "get_employees" ) ); ?></strong></li>
					 <?php }else{ ?>
					 <li>Customer</li>
                     <li><strong><?php $key = "customer"; if( isset( $data["event"][$key] ) )echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_customers" ) ); ?></strong></li>
					 <?php } ?>
					 
					 <li>Signature: ____________________</li>
                  </ul>
               </div>
               <div class="col-xs-6">
					<ul class="list-unstyled">
						 
						 <li>Raised By:</li>
						 <li><strong><?php $key = "modified_by"; if( isset( $data["event"][$key] ) )echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></strong> </li>
						 <li>Signature: ____________________</li>
					  </ul>
					
               </div>
            </div>
			<?php } ?>
			
			<div class="row">
				<div class="col-md-offset-5 col-md-7 " style="text-align:right;">
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
				<?php if( $amount_paid  || $show_owing ){ ?>
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
				<?php } ?>
			<?php }else{ ?>
				<div class="alert alert-danger">
					<h3>Cannot Retrieve Receipt</h3>
					<p>Multiple Records Selected, please do select a single sales record to view its receipt</p>
				</div>
			<?php } ?>
		 </div>
			<!-- END ABOUT INFO -->  