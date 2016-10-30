<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total amount due from room rate"] = 0;
	$total["total amount paid by guests"] = 0;
	$total["total occuppied rooms"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$cus = get_customers();
	$state =  get_hotel_room_status();
	$rooms = get_hotel_rooms();
	$staff = get_employees();
	
	if( ! empty( $report_data ) ){
		
		//Array ( [Nov-2015] => Array ( [id] => 10467389855 [serial_num] => 10 [date] => 1446336061 [discount] => 0 [customer] => [store] => 10173870046 [sales_status] => sold [item] => cracked_eggs_crate [quantity_sold] => 1360 [amount_due] => 870000 ) ) 
		$group = array();
		
		foreach( $report_data as $sval ){
			$ocuppied = '';
			
			if( isset( $group[ $sval["id"] ] ) ){
				++$group[ $sval["id"] ];
				$sval["amount_paid"] = 0;
				$sval["discount"] = 0;
			}else{
				$group[ $sval["id"] ] = 1;
			}
			
			if( $sval['status'] == "checked_in" ){
				$total["total occuppied rooms"] += 1;
				$ocuppied = ' style="color:#d00;" ';
			}
			
			$body .= '<tr '.$ocuppied.'>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td><strong>' . ( isset( $rooms[ $sval["room"] ] )?$rooms[ $sval["room"] ]:$sval["room"] ) . '</strong></td>';
				
				$body .= '<td>' . ( isset( $cus[ $sval["main_guest"] ] )?$cus[ $sval["main_guest"] ]:$sval["main_guest"] ) . '</td>';
				$body .= '<td>' . ( isset( $cus[ $sval["room_guest"] ] )?$cus[ $sval["room_guest"] ]:$sval["room_guest"] ) . '</td>';
				
				$body .= '<td>'.date( ( "d-M-Y" ), doubleval( $sval["checkin_date"] ) ). '</td>';
				$body .= '<td>'.date( ( "d-M-Y" ), doubleval( $sval["checkout_date"] ) ). '</td>';
				
				$body .= '<td class="hide-in-mobile">'. ( isset( $state[ $sval['status'] ] )?$state[ $sval['status'] ]:$sval['status'] ) . '</td>';
				
				$dis = $sval["discount"] + $sval["room_discount"];
				
				$body .= '<td class="hide-in-mobile">' . number_format( $sval["amount_due"], 2 ) . '</td>';
				$body .= '<td class="hide-in-mobile">' . number_format( $dis, 2 ) . '</td>';
				$body .= '<td class="hide-in-mobile">' . number_format( $sval["deposit"], 2 ) . '</td>';
								
				$out1 = doubleval( $sval["checkout_date"] );
				$in = doubleval( $sval["checkin_date"] );
				$out = get_date_difference( $out1, $in );
				
				
				$due = ( $out * $sval["amount_due"] ) - $dis;
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
				
				$income = $due + $service_charge + $vat + $service_tax;
				
				$body .= '<td class="company alternate">' . number_format( $income , 2 ) . '</td>';
				$body .= '<td>' . number_format( $sval["amount_paid"], 2 ) . '</td>';
				
				$body .= '<td>' . ( isset( $staff[ $sval["created_by"] ] )?$staff[ $sval["created_by"] ]:"" ) . '</td>';
				
				$total["total amount due from room rate"] += $income;
				$total["total amount paid by guests"] += $sval["amount_paid"];
				
			$body .= '</tr>';
			
		}
		
		foreach( $total as & $v )$v = number_format( $v, 2 );
		/*
		$body .= '<tr class="total-row">';
			$body .= '<td class="company">TOTAL</td>';
			$body .= '<td>'. $total["total goods sold"] . '</td>';
			$body .= '<td>' . $total["total income"] . '</td>';
			//$body .= '<td>' . $total["total amount owed"] . '</td>';
		$body .= '</tr>';
		*/
	
	}
	
	?>
	<tr><td colspan="4" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="8" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="4"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="4" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th rowspan="2" class="company">Date</th>
		<th rowspan="2">Room</th>
		<th colspan="2">Guest</th>
		
		<th colspan="2" class="hide-in-mobile">In / Out Date</th>
		<th rowspan="2" class="alternate">Room Status</th>
		<th colspan="5">Financial</th>
		<th rowspan="2">Staff Responsible</th>
		
	</tr>
	<tr>
		<th>Paying Guest</th>
		<th>Room Guest</th>
		
		<th class="hide-in-mobile">Checked In</th>
		<th class="hide-in-mobile">Checked Out</th>
		
		<th class="hide-in-mobile">Room Rate / Night</th>
		<th class="hide-in-mobile">Discount</th>
		<th class="hide-in-mobile">Deposit</th>
		<th class="hide-in-mobile">Total Room Rate <small>excluding deposit</small></th>
		<th class="hide-in-mobile">Amount Paid <small>excluding deposit</small></th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>