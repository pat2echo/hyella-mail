<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total amount due from room rate"] = 0;
	$total["total amount paid by guests"] = 0;
	$total["total refunds"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$cus = get_customers();
	$state =  get_hotel_room_status();
	$rooms = get_hotel_room_types();
	$staff = get_employees();
	
	$g_discount_after_tax = get_discount_after_tax_settings();
	
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
			
			$body .= '<tr '.$ocuppied.' id="booking-'.$sval["id"].'">';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td><strong>' . ( isset( $rooms[ $sval["room_type"] ] )?$rooms[ $sval["room_type"] ]:$sval["room_type"] ) . '</strong>';
				$body .= '<br /><small>Check In: '.date( ( "d-M-Y" ), doubleval( $sval["checkin_date"] ) ). '</small>';
				$body .= '<br /><small>Check Out: '.date( ( "d-M-Y" ), doubleval( $sval["checkout_date"] ) ). '</small>';
				$body .= '<br /><br /><a href="#" class="custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_invoice&hide=1" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'">Booking REF: '.$sval["id"]. '</a>';
				$body .= '</td>';
				
				$body .= '<td><small>Paying Guest</small><br /><strong>' . ( isset( $cus[ $sval["main_guest"] ] )?$cus[ $sval["main_guest"] ]:$sval["main_guest"] ) . '</strong><br />';
				$body .= '<br /><small>Room Guest</small><br /><strong>';
					$dz = explode( ":::", $sval["other_guest"] );
					if( ! empty( $dz ) && is_array( $dz ) ){
						foreach( $dz as $dv ){
							$body .= ( isset( $cus[ $dv ] )?( $cus[ $dv ] . ", " ):"" );
						}
					}
					
				$body .= '</strong></td>';
				
				$body .= '<td class="hide-in-mobile">'. ( isset( $state[ $sval['status'] ] )?$state[ $sval['status'] ]:$sval['status'] ) . '</td>';
				
				$dis = $sval["discount"] + $sval["room_discount"];
				$dis += $sval["amount_due"] * ( $sval["discount_percentage"] + $sval['room_discount_percentage'] ) / 100;
				
				$out1 = doubleval( $sval["checkout_date"] );
				$in = doubleval( $sval["checkin_date"] );
				$out = get_date_difference( $out1, $in );
				$due = ( $out * $sval["amount_due"] );
				
				if( $g_discount_after_tax ){
					$dis = $sval["discount"] + $sval["room_discount"];
					if( $sval["room_discount_percentage"] )$dis += ( $sval["room_discount_percentage"] / 100 ) * $due;
					
				}else{
					$dis = $sval["discount"] + $sval["room_discount"];
					$dis += $due * ( $sval["discount_percentage"] + $sval['room_discount_percentage'] ) / 100;
					$due = $due - $dis;
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
				
				$income = $due + $service_charge + $vat + $service_tax;
				if( $g_discount_after_tax ){
					if( $sval["discount_percentage"] )$dis += ( $sval["discount_percentage"] / 100 ) * $income;
					$income = $income - $dis;
				}
				
				$body .= '<td class="hide-in-mobile">' . number_format( $out, 0 ) . '</td>';
				$body .= '<td class="hide-in-mobile">' . number_format( $sval["amount_due"] , 2 ) . '</td>';
				$body .= '<td class="hide-in-mobile">' . number_format( $dis, 2 ) . '</td>';
				
				$body .= '<td class="company alternate">' . number_format( $income , 2 ) . '</td>';
				
				$pay_button = '';
				if( doubleval( $sval["amount_paid"] ) < $income ){
					$pay_button = '<br /><br /><a href="#" class="btn btn-xs blue hidden-print custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=pay_bills_from_report_view" title="Capture Guest Payment" override-selected-record="'.$sval["id"].'">Pay Now</a>';
				}
				
				$body .= '<td>' . number_format( $sval["amount_paid"], 2 ) . $pay_button . '</td>';
				$body .= '<td class="hide-in-mobile">' . number_format( $sval["amount_refund"], 2 ) . '</td>';
				
				$body .= '<td>' . ( isset( $staff[ $sval["created_by"] ] )?$staff[ $sval["created_by"] ]:"" ) . '</td>';
				
				$total["total amount due from room rate"] += $income;
				$total["total amount paid by guests"] += $sval["amount_paid"];
				$total["total refunds"] += $sval["amount_refund"];
				
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
	<tr><td colspan="4" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="6" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="3"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="3" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th rowspan="2" class="company">Date</th>
		<th rowspan="2">Room Type</th>
		<th rowspan="2">Guest</th>
		
		<th rowspan="2" class="alternate">Room Status</th>
		<th colspan="6">Financial</th>
		<th rowspan="2">Staff Responsible</th>
		
	</tr>
	<tr>
		<th class="hide-in-mobile">No. of Nights</th>
		<th class="hide-in-mobile">Room Rate / Night</th>
		
		<th class="hide-in-mobile">Discount</th>
		<th class="hide-in-mobile">Total Room Rate <small>excluding deposit</small></th>
		<th class="hide-in-mobile">Amount Paid <small>excluding deposit</small></th>
		<th class="hide-in-mobile">Refund</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>