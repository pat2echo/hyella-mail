<div class="row">
   <div class="col-xs-12">
		<?php 
			$total = 0;
			$serial = 0;
			
			if( isset( $data["room_types"] ) && is_array( $data["room_types"] ) && ! empty( $data["room_types"] ) ){
				?>
				 <h4>Refundable Deposit</h4>
				 <table class="table table-striped table-hover">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Room Type</th>
					   <th class="hidden-480">Number of Rooms</th>
					   <th style="text-align:right;" class="hidden-480">Deposit per Room</th>
					   <th style="text-align:right;">Total Deposit</th>
					</tr>
				 </thead>
				 <tbody>
				<?php
				$room_types = get_hotel_room_types();
				$total_deposit = 0;
				
				foreach( $data["room_types"] as $items ){
					++$serial;
					
					$price = $items["deposit"];
					$q = isset( $items["quantity"] )?$items["quantity"]:1;
					
					$title = "";
					if( isset( $room_types[ $items["room_type"] ] ) ){
						$title = $room_types[ $items["room_type"] ];
					}
					
					$total_deposit += ( $price * $q );
					?>
					<tr>
					   <td><?php echo $serial; ?></td>
					   <td><?php echo $title; ?></td>
					   <td class="hidden-480"><?php echo $q; ?></td>
					   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
					   <td align="right"><?php echo format_and_convert_numbers( $price * $q, 4 ); ?></td>
					</tr>
					<?php
				}
				?>
					<tr>
					   <td></td>
					   <td align="right" colspan="3"><strong>TOTAL AMOUNT DEPOSITED</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total_deposit , 4 ); ?></strong></td>
					</tr>
					<?php
				?>
				</tbody>
				</table>
				
				 <h4>Bill</h4>
				 <table class="table table-striped table-hover">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Room Type</th>
					   <th class="hidden-480">Number of Rooms</th>
					   <th style="text-align:right;" class="hidden-480">Rate per Room per Night</th>
					   <th style="text-align:right;">Total Rate per Night</th>
					</tr>
				 </thead>
				 <tbody>
				<?php
				$room_types = get_hotel_room_types();
				$serial = 0;
				
				foreach( $data["room_types"] as $items ){
					++$serial;
					
					$price = $items["rate"];
					$q = $items["quantity"];
					
					$title = "";
					if( isset( $room_types[ $items["room_type"] ] ) ){
						$title = $room_types[ $items["room_type"] ];
					}
					
					$total += ( $price * $q );
					?>
					<tr>
					   <td><?php echo $serial; ?></td>
					   <td><?php echo $title; ?></td>
					   <td class="hidden-480"><?php echo $q; ?></td>
					   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
					   <td align="right"><?php echo format_and_convert_numbers( $price * $q, 4 ); ?></td>
					</tr>
					<?php
				}
				?>
				<tr>
				   <td></td>
				   <td align="right" colspan="3"><strong>TOTAL RATE PER NIGHT</strong></td>
				   <td align="right"><strong><?php echo format_and_convert_numbers( $total , 4 ); ?></strong></td>
				</tr>
				<tr>
				   <td></td>
				   <td align="right" colspan="3"><strong>TOTAL NUMBER OF NIGHT(s)</strong></td>
				   <td align="right"><strong><?php echo $no_of_nights; ?></strong></td>
				</tr>
				<tr>
				   <td></td>
				   <td align="right" colspan="3"><strong>TOTAL BILL</strong></td>
				   <td align="right"><strong><?php $total *= $no_of_nights; echo format_and_convert_numbers( $total, 4 ); ?></strong></td>
				</tr>
				<?php
				
				$key = "discount"; 
				if( isset( $data["event"][$key] ) && $data["event"][$key] )
					$discount = doubleval( $data["event"][$key] );
				
				$discount_type = "";
				$discounted_amount = $discount;
				$total = $total - $discounted_amount;
				
				if( $discounted_amount ){
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="3"><strong>DISCOUNT</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $discounted_amount, 4 ); ?></strong></td>
					</tr>
					<?php
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
				
				$total = $total + $service_charge + $service_tax + $vat;
				//$total_plus_tax = $total + $service_charge + $service_tax + $vat;
				if( $service_charge ){
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="3"><strong>Service Charge <?php echo $iservice_charge; ?>%</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $service_charge , 4 ); ?></strong></td>
					</tr>
					<?php
				}
				if( $service_tax ){
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="3"><strong>Service Tax <?php echo $iservice_tax; ?>%</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $service_tax , 4 ); ?></strong></td>
					</tr>
					<?php
				}
				if( $vat ){
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="3"><strong>VAT <?php echo $ivat; ?>%</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $vat , 4 ); ?></strong></td>
					</tr>
					<?php
				}
				?>
				</tbody>
				</table>
				<?php
			}
			?>
			
   </div>
</div>