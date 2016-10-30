<div class="row">
   <div class="col-xs-12">
		<?php 
			$total = 0;
			$serial = 0;
			$total_discount = 0;
			$colspan = 4;
			if( $g_discount_after_tax ){
				$colspan = 3;
			}
			
			if( isset( $data["room_types"] ) && is_array( $data["room_types"] ) && ! empty( $data["room_types"] ) ){
				?>
				<div id="refundable-deposit">
				 <h4>Refundable Deposit</h4>
				 <table class="table table-striped table-hover" style="margin-top:10px;">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Room Number</th>
					   <th style="text-align:right;">Amount Deposited</th>
					</tr>
				 </thead>
				 <tbody>
				<?php
				$room_types = get_hotel_rooms();
				$total_deposit = 0;
				
				foreach( $data["room_types"] as $items ){
					++$serial;
					
					$price = $items["deposit"];
					$q = 1;
					
					$title = "";
					if( isset( $room_types[ $items["room"] ] ) ){
						$title = $room_types[ $items["room"] ];
					}
					
					$total_deposit += ( $price * $q );
					?>
					<tr>
					   <td><?php echo $serial; ?></td>
					   <td><?php echo $title; ?></td>
					   <td align="right"><?php echo format_and_convert_numbers( $price , 4 ); ?></td>
					</tr>
					<?php
				}
				?>
					<tr>
					   <td></td>
					   <td align="right" colspan="1"><strong>TOTAL AMOUNT DEPOSITED</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total_deposit , 4 ); ?></strong></td>
					</tr>
					<?php
				?>
				</tbody>
				</table>
				<?php if( !( $total_deposit > 0 ) ){ ?>
				<style type="text/css">
					#refundable-deposit{
						display:none;
					}
				</style>
				<?php } ?>
				</div>
				
				 <h4>Room Bill</h4>
				 <table class="table table-striped table-hover" style="margin-top:10px;">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Room Number</th>
					   <th style="text-align:right;" class="hidden-480">Rate per Night</th>
					   <th style="text-align:right;" class="hidden-480">No. of Nights</th>
					   <?php if( ! $g_discount_after_tax ){ ?>
					   <th style="text-align:right;" class="hidden-480">Discount</th>
					   <?php } ?>
					   <th style="text-align:right;">Total Rate</th>
					</tr>
				 </thead>
				 <tbody>
				<?php
				$room_types = get_hotel_rooms();
				$serial = 0;
				$c = get_customers();
				$total_percentage_discount = 0;
				
				foreach( $data["room_types"] as $items ){
					++$serial;
					
					$out = doubleval( $items["checkout_date"] );
					$in = doubleval( $items["checkin_date"] );
					$q = get_date_difference( $out, $in );
						
					$price = $items["rate"];
					
					$title = "";
					if( isset( $room_types[ $items["room"] ] ) ){
						$title = $room_types[ $items["room"] ];
					}
					
					$rate = ( $price * $q );
			
					$items["discount"] = doubleval( $items["discount"] );
					
					if( $items["status"] == "cancelled" ){
						$title = "<strong>rebate</strong><br />" . $title;
					}else{
						if( $g_discount_after_tax ){
							if( $items["discount_percentage"] )$total_percentage_discount = $items["discount_percentage"];
							$total_discount += $items["discount"];
							$total += $rate;
						}else{
							$items["discount"] += ( $items["discount_percentage"] / 100 ) * $rate;
							$total += ( $rate - $items["discount"] );
						}
					}
					
					if( $items["status"] == 'checked_out' ){
						$out = $items["modification_date"];
					}
					
					$comment = '';
					if( $items["comment"] ){
						$comment = '<br />' . $items["comment"];
					}
					?>
					<tr>
					   <td><?php echo $serial; ?></td>
					   <td><?php echo $title; ?><small><br />Check Out: <?php echo date("d-M-Y H:i", $out ); echo $comment; ?><br />Room Guest: <strong><?php echo ( isset( $c[ $items["guest"] ] )?$c[ $items["guest"] ]:"" ); ?></strong></small></td>
					   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
					   <td align="right" class="hidden-480"><?php echo $q; ?></td>
					   <?php if( ! $g_discount_after_tax ){ ?>
					   <td align="right"><?php echo format_and_convert_numbers( ( $price * $q ) - $items["discount"] , 4 ); ?></td>
					   <?php }else{ ?>
					   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( ( $price * $q ) , 4 ); ?></td>
					   <?php } ?>
					</tr>
					<?php
				}
				?>
				<tr>
				   <td></td>
				   <td align="right" colspan="<?php echo $colspan; ?>"><strong>TOTAL BILL</strong></td>
				   <td align="right"><strong><?php echo format_and_convert_numbers( $total, 4 ); ?></strong></td>
				</tr>
				<?php
				
				if( ! $g_discount_after_tax ){
					$key = "discount_percentage"; 
					if( isset( $data["event"][$key] ) && $data["event"][$key] )
						$discount_percentage = doubleval( $data["event"][$key] );
					
					$key = "discount"; 
					if( isset( $data["event"][$key] ) && $data["event"][$key] )
						$discount = doubleval( $data["event"][$key] );
					
					$discount_type = "";
					$discounted_amount = $discount + $total_discount;
					if( $discount_percentage )$discounted_amount += ( ( $discount_percentage / 100 ) * $total );
					
					$total = $total - $discounted_amount;
					
					if( $discounted_amount ){
						?>
						<tr>
						   <td></td>
						   <td align="right" colspan="<?php echo $colspan; ?>"><strong>DISCOUNT</strong></td>
						   <td align="right"><strong><?php echo format_and_convert_numbers( $discounted_amount, 4 ); ?></strong></td>
						</tr>
						<?php
					}
				}
				
				$service_tax = 0;
				$service_charge = 0;
				$vat = 0;
				
				if( isset( $data["event"]["vat"] ) && doubleval( $data["event"]["vat"] ) )
					$vat = doubleval( $data["event"]["vat"] );
				
				if( isset( $data["event"]["service_charge"] ) && doubleval( $data["event"]["service_charge"] ) )
					$service_charge = doubleval( $data["event"]["service_charge"] );
				
				if( isset( $data["event"]["service_tax"] ) && doubleval( $data["event"]["service_tax"] ) )
					$service_tax = doubleval( $data["event"]["service_tax"] );
				
				
				$iservice_tax = $service_tax;
				$iservice_charge = $service_charge;
				$ivat = $vat;
				
				if( $service_tax )$service_tax = round( $service_tax * $total / 100, 2 );
				if( $service_charge )$service_charge = round( $service_charge * $total / 100 , 2);
				if( $vat )$vat = round( $vat * $total / 100 , 2);
				
				$total_plus_tax = $total + $service_charge + $service_tax + $vat;
				
				if( $service_charge ){
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="<?php echo $colspan; ?>"><strong>Service Charge <?php echo $iservice_charge; ?>%</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $service_charge , 4 ); ?></strong></td>
					</tr>
					<?php
				}
				if( $service_tax ){
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="<?php echo $colspan; ?>"><strong>Service Tax <?php echo $iservice_tax; ?>%</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $service_tax, 4 ); ?></strong></td>
					</tr>
					<?php
				}
				if( $vat ){
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="<?php echo $colspan; ?>"><strong>VAT <?php echo $ivat; ?>%</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $vat, 4 ); ?></strong></td>
					</tr>
					<?php
				}
				
				if( $g_discount_after_tax ){
					$key = "discount_percentage"; 
					if( isset( $data["event"][$key] ) && $data["event"][$key] )
						$discount_percentage = doubleval( $data["event"][$key] );
					
					$discount_percentage += $total_percentage_discount;
					
					$key = "discount"; 
					if( isset( $data["event"][$key] ) && $data["event"][$key] )
						$discount = doubleval( $data["event"][$key] );
					
					$discount_type = "";
					$discounted_amount = $discount + $total_discount;
					if( $discount_percentage )$discounted_amount += ( ( $discount_percentage / 100 ) * $total_plus_tax );
					
					$total_plus_tax = $total_plus_tax - $discounted_amount;
					
					if( $discounted_amount ){
						?>
						<tr>
						   <td></td>
						   <td align="right" colspan="<?php echo $colspan; ?>"><strong>DISCOUNT</strong></td>
						   <td align="right"><strong><?php echo format_and_convert_numbers( $discounted_amount, 4 ); ?></strong></td>
						</tr>
						<?php
					}
				}
				
				if( $total_plus_tax != $total ){
					$total = $total_plus_tax;
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="<?php echo $colspan; ?>"><strong>GRAND TOTAL</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total ); ?></strong></td>
					</tr>
					<?php
				}
				
				?>
				</tbody>
				</table>
				<?php
			}
			
			if( isset( $data["event_items"] ) && is_array( $data["event_items"] ) && ! empty( $data["event_items"] ) ){
					?>
					 <h4>Other Bills</h4>
					 <table class="table table-striped table-hover" style="margin-top:10px;">
					 <thead>
						<tr>
						   <th>#</th>
						   <th>Description</th>
						   <th style="text-align:right;" class="hidden-480">No. of Items</th>
						   <th style="text-align:right;">Outstanding</th>
						   <th style="text-align:right;">Amount Paid</th>
						   <th style="text-align:right;">Amount Due</th>
						</tr>
					 </thead>
					 <tbody>
					<?php
					$serial = 0;
					$total_paid = 0;
					$total_bill = 0;
					
					$discount_after_tax = get_sales_discount_after_tax_settings();
					
					foreach( $data["event_items"] as $items ){
						++$serial;
						
						if( $items["payment_method"] == "cash_refund" ){
							$apaid =  0;
							$adue = $items["amount_refund"];
							$q = 0;
							$tit = 'Refund';
						}else{
							
							if( $discount_after_tax ){
								$adue =  $items["amount_due"];
							}else{
								$adue = $items["amount_due"] - $items["discount"];
							}
							
							
							$vat = 0;
							$service_charge = 0;
							$service_tax = 0;
							if( isset( $items["vat"] ) && doubleval( $items["vat"] ) )
								$vat = round( $adue * $items["vat"] / 100, 2 );
							
							if( isset( $items["service_charge"] ) && doubleval( $items["service_charge"] ) )
								$service_charge = round( $adue * $items["service_charge"] / 100, 2 );
							
							if( isset( $items["service_tax"] ) && doubleval( $items["service_tax"] ) )
								$service_tax = round( $adue * $items["service_tax"] / 100, 2 );
							
							$adue += $service_charge + $vat + $service_tax;
							
							
							switch( $items["payment_method"] ){
							case "complimentary_staff":
							case "complimentary":
								$items["discount"] = $adue;
							break;
							}
							
							if( $discount_after_tax ){
								$adue = $adue - $items["discount"];
							}
							
							$q = $items["quantity"];
							$apaid = $items["amount_paid"];
							$tit = 'Sales Receipt REF';
						}
						
						$title = "<strong>".date("d-M-Y", doubleval( $items["date"] ) )."</strong><br />".$tit.": <a href='#' class='custom-single-selected-record-button' action='?module=&action=sales&todo=view_invoice_app1&hide=1' title='View Invoice / Receipt' override-selected-record='".$items["id"]."' override-selected-record-only='1' ><strong>#".$items["serial_num"]." - ".$items["id"]."</strong></a>";
						if( $items["comment"] ){
							$title .= "<small><br />".$items["comment"]."</small>";
						}
						
						$total_paid += $apaid;
						$total_bill += $adue;
						?>
						<tr>
						   <td><?php echo $serial; ?></td>
						   <td><?php echo $title; ?></td>
						   <td align="right" class="hidden-480"><?php echo $q; ?></td>
						   <td align="right"><?php echo format_and_convert_numbers( $adue - $apaid , 4 ); ?></td>
						   <td align="right"><?php echo format_and_convert_numbers( $apaid, 4 ); ?></td>
						   <td align="right"><?php echo format_and_convert_numbers( $adue, 4 ); ?></td>
						</tr>
						<?php
					}
					
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="2"><strong>TOTAL OTHER BILLS</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total_bill - $total_paid, 4 ); ?></strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total_paid, 4 ); ?></strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total_bill, 4 ); ?></strong></td>
					</tr>
					<?php
					$amount_paid += $total_paid;
					$total += $total_bill;
					?>
					</tbody>
					</table>
					<?php
				}
			?>
			
   </div>
</div>