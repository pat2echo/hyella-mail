<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$time = "";
	if( isset( $data[ 'time' ] ) )
		$time = str_replace(":", ".", $data[ 'time' ] );
	
	$etime = "";
	if( isset( $data[ 'end_time' ] ) )
		$etime = str_replace(":", ".", $data[ 'end_time' ] );
	
	$time = doubleval( $time );
	$etime = doubleval( $etime );
	
	$day_time = "";
	if( isset( $data[ 'day_time' ] ) )
		$day_time = $data[ 'day_time' ];
	
	
	$state =  get_hotel_room_status();
	$rooms = get_hotel_rooms();
	$staff = get_employees();
	
	$g_discount_after_tax = get_discount_after_tax_settings();
	
	if( $g_discount_after_tax ){
		$total["occuppied rooms"] = 0;
		$total["total revenue after tax"] = 0;
		$total["total discount"] = 0;
		$total["total revenue after discount"] = 0;
	}else{
		$total["occuppied rooms"] = 0;
		$total["total revenue before discount"] = 0;
		$total["total discount"] = 0;
		$total["total revenue after discount"] = 0;
		$total["total revenue after tax"] = 0;
	}
	
	
	if( ! empty( $report_data ) ){
		
		$group = array();
		$serial = 0;
		
		$total_rate = 0;
		$total_vat = 0;
		$total_service_charge = 0;
		$total_service_tax = 0;
		$total_discount = 0;
		$total_income = 0;
		$total_revenue = 0;
		
		$group_guest = array();
		
		foreach( $report_data as $sval ){
			$ocuppied = '';
			
			$sval["modification_date"] = doubleval( $sval["a_modification_date"] );
			$pass = 1;
			switch( $day_time ){
			case "day":
				$pass = 0;
				if( ( date("H.i", $sval["modification_date"] ) < $time && date("H.i", $sval["modification_date"] ) > $etime ) ){
					$pass = 1;
				}
			break;
			case "night":
				$pass = 0;
				if( ( doubleval( date("H.i", $sval["modification_date"] ) ) > $time || doubleval( date("H.i", $sval["modification_date"] ) ) < $etime ) ){
					$pass = 1;
				}
			break;
			}
			
			if( ! $pass )continue;
			
			$body .= '<tr '.$ocuppied.'>';
				
				$body .= '<td class="company">' . ++$serial . '</td>';
				$body .= '<td><strong>' . ( isset( $rooms[ $sval["room"] ] )?$rooms[ $sval["room"] ]:$sval["room"] ) . '</strong></td>';
				
				$cus = get_customers_details( array( "id" => $sval["room_guest"] ) );
				if( isset( $cus["name"] ) )$body .= '<td>' . $cus["name"] .'</td>';
				else $body .= '<td>' . $sval["main_guest"] . '</td>';
				
				$cus = get_customers_details( array( "id" => $sval["main_guest"] ) );
				if( isset( $cus["name"] ) )$body .= '<td>' . $cus["name"] . '</td>';
				else $body .= '<td>' . $sval["main_guest"] . '</td>';
				
				//$body .= '<td>In: &nbsp;&nbsp;&nbsp;'.date( ( "d-M-Y" ), doubleval( $sval["checkin_date"] ) ) . '<br />Out: ' . date( ( "d-M-Y" ), doubleval( $sval["checkout_date"] ) ) . '</td>';
				/*
				$out1 = doubleval( $sval["checkout_date"] );
				$in = doubleval( $sval["checkin_date"] );
				$out = get_date_difference( $out1, $in );
				*/
				//$body .= '<td>'.$out. '</td>';
				$due = doubleval( $sval["amount_due"] );
				
				if( isset( $group_guest[ $sval["booking_ref"] ] ) ){
					$sval["discount"] = 0;
				}else{
					$group_guest[ $sval["booking_ref"] ] = 1;
				}
				
				if( $g_discount_after_tax ){
					
				}else{
					$discount = $due * ( $sval["discount_percentage"] + $sval['room_discount_percentage'] ) / 100;
					if( isset(  $sval["room_discount"] ) )$discount += $sval["room_discount"];
					if( isset( $sval["discount"] ) )$discount += $sval["discount"];
				
					$due -= $discount;
				}
				
				$vat = 0;
				$service_charge = 0;
				$service_tax = 0;
				if( isset( $sval["vat"] ) && doubleval( $sval["vat"] ) )
					$vat = round( $due * $sval["vat"] / 100, 2 );
				
				if( isset( $sval["service_charge"] ) && doubleval( $sval["service_charge"] ) )
					$service_charge = round( $due * $sval["service_charge"] / 100, 2 );
				
				if( isset( $sval["service_tax"] ) && doubleval( $sval["service_tax"] ) )
					$service_tax = round( $due * $sval["service_tax"] / 100, 2 );
				
				$income = $due + $service_charge + $vat + $service_tax;
				if( $g_discount_after_tax ){
					if( $sval["room_discount_percentage"] )$sval["room_discount"] += ( $sval["room_discount_percentage"] / 100 ) * $income;
					$discount = $income * ( $sval["discount_percentage"] ) / 100;
					if( isset(  $sval["room_discount"] ) )$discount += $sval["room_discount"];
					if( isset( $sval["discount"] ) )$discount += $sval["discount"];
					
					$income = $income - $discount;
				}
				
				$body .= '<td class="hide-in-mobile">'. number_format( $sval['amount_due'] , 2 ) . '</td>';
				
				if( ! $g_discount_after_tax ){
					$body .= '<td class="hide-in-mobile">'. number_format( $discount , 2 ) . '</td>';
				}
				
				//$body .= '<td class="hide-in-mobile">'. number_format( $sval['amount_due'] - $discount , 2 ) . '</td>';
				
				$body .= '<td class="hide-in-mobile">'. number_format( $vat , 2 ) . '</td>';
				$body .= '<td class="hide-in-mobile">'. number_format( $service_charge , 2 ) . '</td>';
				$body .= '<td class="hide-in-mobile">'. number_format( $service_tax , 2 ) . '</td>';
				
				if( $g_discount_after_tax ){
					$body .= '<td class="hide-in-mobile">'. number_format( $discount , 2 ) . '</td>';
				}
				
				$body .= '<td class="hide-in-mobile">'. number_format( $income , 2 ) . '</td>';
				
				$body .= '<td class="hide-in-mobile">'. $sval['comment'] . '</td>';
				$body .= '<td class="hide-in-mobile">'. ( isset( $state[ $sval['status'] ] )?$state[ $sval['status'] ]:$sval['status'] ) . '</td>';
				
			$body .= '</tr>';
			
			$total_rate += $sval['amount_due'];
			$total_revenue += ( $sval["amount_due"] - $discount );
			$total_vat += $vat;
			$total_service_charge += $service_charge;
			$total_service_tax += $service_tax;
			$total_discount += $discount;
			$total_income +=$income;
			
			if( $g_discount_after_tax ){
				$total["total discount"] += $discount;
				$total["total revenue after discount"] += $income;
				$total["total revenue after tax"] += $income + $discount;
			}else{
				$total["total revenue before discount"] += $sval["amount_due"];
				$total["total discount"] += $discount;
				$total["total revenue after discount"] += ( $sval["amount_due"] - $discount );
				$total["total revenue after tax"] += $income;
			}
			
			$total["occuppied rooms"] += 1;
			
		}
		foreach( $total as & $v )$v = number_format( $v, 2 );
		
	}
	
	?>
	<tr>
		<td colspan="7" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td>
		<td colspan="6" ><h5>SUMMARY</h5></td>
	</tr>	
		<?php
		foreach( $total as $key => $val ){
			?>
			<tr><td colspan="3"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="3" ><?php echo $val; ?></td></tr>
			<?php
		}
	?>
<tr>
		<th rowspan="2" class="company">S/N</th>
		<th rowspan="2">Room</th>
		<th colspan="2">Guest</th>
		
		<th rowspan="2">Rate per Night</th>
		<?php if( ! $g_discount_after_tax ){ ?>
		<th rowspan="2">Discount</th>
		<?php } ?>
		<!--<th rowspan="2">Room Revenue</th>-->
		<th rowspan="2">VAT</th>
		<th rowspan="2">Service Charge</th>
		<th rowspan="2">Service Tax</th>
		
		<?php if( $g_discount_after_tax ){ ?>
		<th rowspan="2">Discount</th>
		<?php } ?>
		<th rowspan="2">Total Rate per Night</th>
		
		<th rowspan="2">Comment</th>
		<th rowspan="2">Status</th>
	</tr>
	<tr>
		<th>Room Guest</th>
		<th>Paying Guest</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
<tfoot>
	<tr class="total-row">
		<td></td>
		<td>TOTAL</td>
		<td></td>
		<td></td>
		<td><?php echo number_format( $total_rate, 2 ); ?></td>
		<?php if( ! $g_discount_after_tax ){ ?>
		<td><?php echo number_format( $total_discount, 2 ); ?></td>
		<?php } ?>
		<!--<td><?php //echo number_format( $total_revenue, 2 ); ?></td>-->
		<td><?php echo number_format( $total_vat, 2 ); ?></td>
		<td><?php echo number_format( $total_service_charge, 2 ); ?></td>
		<td><?php echo number_format( $total_service_tax, 2 ); ?></td>
		
		<?php if( $g_discount_after_tax ){ ?>
		<td><?php echo number_format( $total_discount, 2 ); ?></td>
		<?php } ?>
		
		<td><?php echo number_format( $total_income, 2 ); ?></td>
		<td></td>
		<td></td>
	</tr>
</tfoot>
</table>
</div>