<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$state =  get_hotel_room_status();
	$rooms = get_hotel_rooms_with_details();
	$store = get_stores();
	
	$total_deposit = 0;
	$total_con = 0;
	$total_credit = 0;
	$total_debit = 0;
	
?>
<?php
$group = array();
$group_guest = array();
$data = array();

$g_discount_after_tax = get_discount_after_tax_settings();

if( ! empty( $report_data["rooms"] ) ){
	foreach( $report_data["rooms"] as $sval ){
		$sval["type"] = "hotel_checkin";
		
		$out1 = doubleval( $sval["checkout_date"] );
		$in = doubleval( $sval["checkin_date"] );
		$out = get_date_difference( $out1, $in );
		$rate = ( $out * $sval["amount_due"] );
		
		$sval["room_discount"] = doubleval( $sval["room_discount"] );
		
		if( isset( $group_guest[ $sval["booking_ref"] ] ) ){
			$sval["discount"] = 0;
		}else{
			$group_guest[ $sval["booking_ref"] ] = 1;
		}
		
		if( $g_discount_after_tax ){
			$due =  $rate;
		}else{
			if( $sval["room_discount_percentage"] )$sval["room_discount"] += ( $sval["room_discount_percentage"] / 100 ) * $rate;
			$dis = $sval["discount"] + $sval["room_discount"];
			if( $sval["discount_percentage"] )
				$dis += ( $sval["discount_percentage"] / 100 ) * ( $rate );
			
			$due =  $rate - $dis;
		}
		
		if( $due <= 0 )$due = 0;
		
		$vat = 0;
		$service_charge = 0;
		$service_tax = 0;
		if( isset( $sval["vat"] ) && doubleval( $sval["vat"] ) )
			$vat = round( $due * $sval["vat"] / 100, 2 );
		
		if( isset( $sval["service_charge"] ) && doubleval( $sval["service_charge"] ) )
			$service_charge = round( $due * $sval["service_charge"] / 100, 2 );
		
		if( isset( $sval["service_tax"] ) && doubleval( $sval["service_tax"] ) )
			$service_tax = round( $due * $sval["service_tax"] / 100, 2 );
		
		if( $g_discount_after_tax ){
			$nrate = $due + $service_charge + $vat + $service_tax;
			
			if( $sval["room_discount_percentage"] )$sval["room_discount"] += ( $sval["room_discount_percentage"] / 100 ) * $nrate;
			
			$dis = $sval["discount"] + $sval["room_discount"];
			if( $sval["discount_percentage"] )
				$dis += ( $sval["discount_percentage"] / 100 ) * ( $nrate );
			
			$due = $due - $dis;
		}
		
		$sval[ "dis" ] = $dis;
		$sval[ "out" ] = $out;
		$sval[ "income" ] = $due + $service_charge + $vat + $service_tax;
		$sval[ "due_text" ] = number_format( $sval["amount_paid"] , 2 );
		
		$data[ $sval["main_guest"] ][ "rooms" ][] = ( isset( $rooms[ $sval["room"] ]["room_number"] )?$rooms[ $sval["room"] ]["room_number"]:$sval["room"] );
		$data[ $sval["main_guest"] ][ "checkin_date" ] = $sval["checkin_date"];
		$data[ $sval["main_guest"] ][ "checkout_date" ] = $sval["checkout_date"];
		
		if( $sval["room_guest"] != $sval["main_guest"] ){
			$data[ $sval["main_guest"] ][ "room_guest" ][] = $sval["room_guest"];
		}
		
		if( ! isset( $data[ $sval["main_guest"] ][ "total_paid" ] ) )$data[ $sval["main_guest"] ][ "total_paid" ] = 0;
		if( ! isset( $data[ $sval["main_guest"] ][ "total_due" ] ) )$data[ $sval["main_guest"] ][ "total_due" ] = 0;
		
		if( $sval[ "status" ] != "cancelled" )
			$data[ $sval["main_guest"] ][ "total_due" ] += $sval[ "income" ];
		
		if( ! isset( $group[ $sval["booking_ref"] ] ) )
			$data[ $sval["main_guest"] ][ "total_paid" ] += $sval[ "amount_paid" ];
		
		$group[ $sval["booking_ref"] ] = 1;
	}
}

if( ! empty( $report_data["rooms_sales"] ) ){
	foreach( $report_data["rooms_sales"] as $sval ){
		$sval["type"] = 'sales';
		
		$due = $sval["amount_due"] - $sval["discount"];
	
		$vat = 0;
		$service_charge = 0;
		$service_tax = 0;
		if( isset( $sval["vat"] ) && doubleval( $sval["vat"] ) )
			$vat = round( $due * $sval["vat"] / 100, 2 );
		
		if( isset( $sval["service_charge"] ) && doubleval( $sval["service_charge"] ) )
			$service_charge = round( $due * $sval["service_charge"] / 100, 2 );
		
		if( isset( $sval["service_tax"] ) && doubleval( $sval["service_tax"] ) )
			$service_tax = round( $due * $sval["service_tax"] / 100, 2 );
		
		$sval[ "income" ] = $due + $service_charge + $vat + $service_tax;
		$sval[ "due_text" ] = number_format( $sval["amount_paid"] , 2 );
		
		if( ! isset( $data[ $sval["customer"] ][ "total_paid" ] ) ){
			$data[ $sval["customer"] ][ "room" ] = "Room Service";
			$data[ $sval["customer"] ][ "checkin_date" ] = $sval["date"];
			$data[ $sval["customer"] ][ "checkout_date" ] = $sval["date"];
			$data[ $sval["customer"] ][ "total_paid" ] = 0;
			$data[ $sval["customer"] ][ "total_due" ] = 0;
		}
		
		$data[ $sval["customer"] ][ "total_due" ] += $sval[ "income" ];
		$data[ $sval["customer"] ][ "total_paid" ] += $sval[ "amount_paid" ];
		 
	}
}

if( ! empty( $report_data["direct_sales"] ) ){
	foreach( $report_data["direct_sales"] as $sval ){
		$sval["type"] = 'sales';
		
		$due = $sval["amount_due"] - $sval["discount"];
	
		$vat = 0;
		$service_charge = 0;
		$service_tax = 0;
		if( isset( $sval["vat"] ) && doubleval( $sval["vat"] ) )
			$vat = round( $due * $sval["vat"] / 100, 2 );
		
		if( isset( $sval["service_charge"] ) && doubleval( $sval["service_charge"] ) )
			$service_charge = round( $due * $sval["service_charge"] / 100, 2 );
		
		if( isset( $sval["service_tax"] ) && doubleval( $sval["service_tax"] ) )
			$service_tax = round( $due * $sval["service_tax"] / 100, 2 );
		
		$sval[ "income" ] = $due + $service_charge + $vat + $service_tax;
		$sval[ "due_text" ] = number_format( $sval["amount_paid"] , 2 );
		
		if( ! isset( $data[ $sval["customer"] ][ "total_paid" ] ) ){
			$data[ $sval["customer"] ][ "room" ] = "Direct Sales";
			$data[ $sval["customer"] ][ "checkin_date" ] = $sval["date"];
			$data[ $sval["customer"] ][ "checkout_date" ] = $sval["date"];
			$data[ $sval["customer"] ][ "total_paid" ] = 0;
			$data[ $sval["customer"] ][ "total_due" ] = 0;
		}
		
		$data[ $sval["customer"] ][ "total_due" ] += $sval[ "income" ];
		$data[ $sval["customer"] ][ "total_paid" ] += $sval[ "amount_paid" ];
		 
	}
}

unset( $report_data );
$pm = get_payment_method();
$pm["cash_refund"] = "Cash Refund";

?>
<?php
	
	if( ! empty( $data ) ){
		
		foreach( $data as $customer => $sval ){
			
			$body .= '<tr>';
			
				$body .= '<td>' . date( "d-M-Y" , $sval["checkin_date"] ) . '</td>';
				$body .= '<td>' . date( "d-M-Y" , $sval["checkout_date"] ) . '</td>';
				
				if( isset( $sval["rooms"] ) ){
					$body .= '<td class="company"><strong>' . implode( ', ', $sval["rooms"] ) . '</strong></td>';
				}else{
					if( isset( $sval["room"] ) ){
						$body .= '<td class="company"><strong>' . $sval["room"] . '</strong></td>';
					}else{
						$body .= '<td>-</td>';
					}
				}
				
				$cus = get_customers_details( array( "id" => $customer ) );
				
				$extra = "";
				if( isset( $sval[ "room_guest" ] ) ){
					foreach( $sval[ "room_guest" ] as $rguest ){
						$cus1 = get_customers_details( array( "id" => $rguest ) );
						if( $extra )$extra .=  ', ' . $cus1["name"];
						else $extra =  ' [guest: ' . $cus1["name"];
					}
					
					if( $extra ){
						$extra .= ']';
					}
				}
				$body .= '<td>' . ( isset( $cus["name"] )?$cus["name"]:"-" ) . $extra . '</td>';
				
				$credit_deposit = doubleval( $sval["total_paid"] );
				$debit_consumption = doubleval( $sval["total_due"] );
				
				$bal = $credit_deposit - $debit_consumption;
				
				$total_deposit += $credit_deposit;
				$total_con += $debit_consumption;
				
				$body .= '<td>' . number_format( $credit_deposit , 2 ) . '</td>';
				$body .= '<td>' . number_format( $debit_consumption , 2 ) . '</td>';
				
				if( $bal > 0 ){
					$body .= '<td>' . number_format( $bal , 2 ) . '</td>';
					$body .= '<td>-</td>';
					$total_credit += $bal;
				}else{
					$body .= '<td>-</td>';
					$body .= '<td>(' . number_format( abs( $bal ) , 2 ) . ')</td>';
					
					$total_debit += abs( $bal );
				}
				
			$body .= '</tr>';
			
		}
		
		$body .= '<tr><td colspan="8">&nbsp;</td></tr>';
		$body .= '<tr class="total-row"><td colspan="4" align="right">BALANCE</td><td>' . number_format( $total_deposit, 2 ) . '</td><td>' . number_format( $total_con, 2 ) . '</td><td>' . number_format( $total_credit, 2 ) . '</td><td>' . number_format( $total_debit, 2 ) . '</td></tr>';
		//$body .= '<tr><td colspan="4" align="right"><strong>BALANCE</strong></td><td colspan="4"><strong>' . number_format( $total_deposit - $total_con, 2 ) . '</strong></td></tr>';
	}
?>
<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<tr><td colspan="8"><h4><strong><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
	<tr>
		<th>Check In</th>
		<th>Check Out</th>
		<th class="company">Room</th>
		<th >Guest</th>
		
		<th>Deposit</th>
		<th>Consumption</th>
		<th>Credit Balance</th>
		<th>Debit Balance</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>	