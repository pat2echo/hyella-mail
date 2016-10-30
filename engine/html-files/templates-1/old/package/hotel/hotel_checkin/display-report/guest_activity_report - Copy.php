<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total amount due from room rate"] = 0;
	$total["total amount due from sales"] = 0;
	$total["total amount paid by guest"] = 0;
	$total["total refund to guest"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$guest = "";
	if( isset( $data[ 'selected_pen' ] ) )
		$guest = $data[ 'selected_pen' ];
	
	$room_checkin_id = "";
	if( isset( $data[ 'room_checkin_id' ] ) )
		$room_checkin_id = $data[ 'room_checkin_id' ];
	
	//$cus = get_customers();
	$state =  get_hotel_room_status();
	$rooms = get_hotel_rooms();
	$staff = get_employees();
	
	$show_staff = 1;
	if( isset( $skip_staff ) && $skip_staff )
		$show_staff = 0;
	
	$show_modal = "";
	if( isset( $modal ) && $modal )
		$show_modal = "&modal=1";
	
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
			
			$refund = "";
			
			$body .= '<tr '.$ocuppied.'>';
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				
				if( isset( $sval[ 'item' ] ) ){
					if( $sval[ 'item' ] == "refund" ){
						$body .= '<td>Refund REF: <strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app1&hide=1'.$show_modal.'" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'" >#' . $sval["serial_num"] . '-' . $sval["id"] . '</a></strong></td>';
						
						$refund = number_format( $sval["amount_due"], 2 );
						$total["total refund to guest"] += $sval["amount_due"];
						
						$income = 0;
						
						$sval["a_created_by"] = $sval["created_by"];
						
					}else{
						$body .= '<td>Sales Receipt REF: <strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app1&hide=1'.$show_modal.'" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'">#' . $sval["serial_num"] . '-' . $sval["id"] . '</a></strong></td>';
						
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
						
						$income = $due + $service_charge + $vat + $service_tax;
						
						$sval["a_created_by"] = $sval["created_by"]; 
						$total["total amount due from sales"] += $income;
					}
					
					$body .= '<td></td>';
					$body .= '<td>'. number_format( $sval["quantity_sold"] , 2 ).'</td>';
					$body .= '<td>'. number_format( $sval["discount"] , 2 ).'</td>';
					//$body .= '<td></td>';
					
					
				}else{
					$body .= '<td><strong>' . ( isset( $rooms[ $sval["room"] ] )?$rooms[ $sval["room"] ]:$sval["room"] ) . '</strong>';
					$body .= '<br /><small>Check In: '.date( ( "d-M-Y" ), doubleval( $sval["checkin_date"] ) ). '</small>';
					$body .= '<br /><small>Check Out: '.date( ( "d-M-Y" ), doubleval( $sval["checkout_date"] ) ). '</small>';
					$body .= '<br /><a href="#" class="custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_invoice&hide=1'.$show_modal.'" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'">Booking REF: '.$sval["id"]. '</a>';
					$body .= '</td>';
					
					$body .= '<td class="hide-in-mobile">' . number_format( $sval["amount_due"], 2 ) . '</td>';
					
					$out1 = doubleval( $sval["checkout_date"] );
					$in = doubleval( $sval["checkin_date"] );
					$out = get_date_difference( $out1, $in );
					
					$dis = $sval["discount"] + $sval["room_discount"];
				
					$body .= '<td class="hide-in-mobile">' . number_format( $out , 2 ) . '</td>';
					$body .= '<td class="hide-in-mobile">' . number_format( $dis, 2 ) . '</td>';
					//$body .= '<td class="hide-in-mobile">' . number_format( $sval["deposit"], 2 ) . '</td>';
					
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
					
					$total["total amount due from room rate"] += $income;
					
					$refund_amount = $sval["amount_paid"] - $income;
					if( $refund_amount > 0 ){
						$refund = '<a href="#" class="custom-single-selected-record-button btn-xs btn red" action="?module=&action=sales&todo=refund_customer&customer=' . $guest . '" title="Click to Refund Guest" override-selected-record="'.$sval["id"].'" mod="' . $room_checkin_id . '" override-selected-record="' . $refund_amount . '">Refund<br />'.number_format( $refund_amount , 2 ). '</a>';
					}
				}
				
				
				$body .= '<td class="company alternate">' . number_format( $income , 2 ) . '</td>';
				$body .= '<td>' . number_format( $sval["amount_paid"], 2 ) . '</td>';
				$body .= '<td>' . $refund . '</td>';
				
				if( $show_staff ){
					$body .= '<td>' . ( isset( $staff[ $sval["created_by"] ] )?$staff[ $sval["created_by"] ]:"" ) . '</td>';
				}
				
				$total["total amount paid by guest"] += $sval["amount_paid"];
				
			$body .= '</tr>';
			
		}
		
		$refund = 0;
		$ab = $total["total refund to guest"] + $total["total amount due from room rate"] + $total["total amount due from sales"] - $total["total amount paid by guest"];
		if( $ab >= 0 ){
			$total["total debt"] = $ab;
		}else{
			 $total["customer balance"] = $ab * -1;
			 $refund = 1;
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
	<tr>
	<td colspan="3" rowspan="<?php echo count( $total ) + 1; ?>">
		<h4 style="color:#333;"><strong><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p>
		<?php if( $refund && $guest ){ ?>
		<a class="btn btn-sm red hidden-print custom-single-selected-record-button" action="?module=&action=sales&todo=refund_customer&customer=<?php echo $guest; ?>&store=10173870046" mod="<?php echo $room_checkin_id; ?>" override-selected-record="<?php echo $ab * -1; ?>" title="Click to refund guest <?php echo $ab * -1; ?>" >Refund Guest:  <?php echo number_format( $ab * -1, 2 ); ?></a>
		<?php } ?>
	</td>
	<td colspan="5" ><h5>SUMMARY</h5></td>
	</tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="3"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="2" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th rowspan="2" class="company">Date</th>
		<th rowspan="2">Description</th>
		
		<th colspan="6">Financial</th>
		<?php if( $show_staff ){ ?>
		<th rowspan="2">Staff Responsible</th>
		<?php } ?>
	</tr>
	<tr>
		<th class="hide-in-mobile">Room Rate / Night</th>
		<th class="hide-in-mobile">No. of Nights ( No. of Items )</th>
		<th class="hide-in-mobile">Discount</th>
		<th class="hide-in-mobile">Total Amount Due<br /><small>excluding deposit</small></th>
		<th class="hide-in-mobile">Amount Paid<br /><small>excluding deposit</small></th>
		<th class="hide-in-mobile">Refund</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>