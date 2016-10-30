<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$modal = 1;
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
	
	include "guest_activity_report_transform.php";
	
	if( ! empty( $data ) ){
		 
		$group = array();
		
		foreach( $data as $d ){
			$tpaid = $d["total_paid"];
			$tdue = $d["total_due"];
			
			unset( $d["total_paid"] );
			unset( $d["total_due"] );
			
			foreach( $d as $sval ){
				$ocuppied = '';
				/*
				if( isset( $group[ $sval["booking_ref"] ] ) ){
					++$group[ $sval["booking_ref"] ];
					$sval["amount_paid"] = 0;
					$sval["discount"] = 0;
				}else{
					$group[ $sval["booking_ref"] ] = 1;
				}
				*/
				$refund = "";
				
				$body .= '<tr '.$ocuppied.'>';
					$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
					
					$due_text = $sval["due_text"];
					$income = $sval["income"];
						
					if( $sval[ 'type' ] == "sales" ){
						$discount_text = '';
						
						switch( $sval["payment_method"] ){
						case "cash_refund":
						case "charge_to_room":
							$due_text = '<strong>'. $pm[ $sval["payment_method"] ] .'</strong>';
						break;
						case "complimentary_staff":
						case "complimentary":
							$sval["discount"] = $income;
							//$discount_text = $due_text;
							//$income = 0;
							$due_text = '<strong>'. $pm[ $sval["payment_method"] ] .'</strong>';
						break;
						}
						
						if( $sval[ 'payment_method' ] == "cash_refund" ){
							$body .= '<td>Refund REF: <strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app1&hide=1'.$show_modal.'" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'" >#' . $sval["serial_num"] . '-' . $sval["id"] . '</a></strong></td>';
							
							$refund = number_format( $sval["amount_due"], 2 );
							$total["total refund to guest"] += $sval["amount_due"];
						}else{
							$body .= '<td>Sales Receipt REF: <strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app1&hide=1'.$show_modal.'" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'">#' . $sval["serial_num"] . '-' . $sval["id"] . '</a></strong><br /><small>'.$sval["comment"].'</small></td>';
							
							switch( $sval["payment_method"] ){
							case "complimentary_staff":
							case "complimentary":
							break;
							default:
								$total["total amount due from sales"] += $income;
							break;
							}
						}
						
						$body .= '<td></td>';
						$body .= '<td>'. number_format( $sval["quantity_sold"] , 2 ).'</td>';
						if( $discount_text ){
							$body .= '<td>'. $discount_text . '</td>';
						}else{
							$body .= '<td>'. number_format( $sval["discount"] , 2 ).'</td>';
						}
						
						
					}else{
						$out = $sval["out"];
						$dis = $sval["dis"];
						
						$status = '';
						$refund_amount = $tpaid - $tdue;
						if( $sval[ 'status' ] == "cancelled" ){
							$refund_amount = $tpaid;
							$status = '<br /><br /><span class="label label-danger">rebate</span>';
						}
						
						$body .= '<td><strong>' . ( isset( $rooms[ $sval["room"] ] )?$rooms[ $sval["room"] ]:$sval["room"] ) . '</strong>';
						$body .= '<br /><small>Check In: '.date( ( "d-M-Y" ), doubleval( $sval["checkin_date"] ) ). '</small>';
						$body .= '<br /><small>Check Out: '.date( ( "d-M-Y" ), doubleval( $sval["checkout_date"] ) ). '</small>';
						$body .= '<br /><a href="#" class="custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_invoice&hide=1'.$show_modal.'" title="View Invoice / Receipt" override-selected-record="'.$sval["booking_ref"].'">Booking REF: '.$sval["booking_ref"]. '</a>';
						$body .= '</td>';
						
						$body .= '<td class="hide-in-mobile">' . number_format( $sval["amount_due"], 2 ) . '</td>';
						
						$body .= '<td class="hide-in-mobile">' . number_format( $out , 2 ) . '</td>';
						$body .= '<td class="hide-in-mobile">' . number_format( $dis, 2 ) . '</td>';
						//$body .= '<td class="hide-in-mobile">' . number_format( $sval["deposit"], 2 ) . '</td>';
						
						if( $sval[ 'status' ] != "cancelled" )
							$total["total amount due from room rate"] += $income;
						
						$refund_amount = $tpaid - $tdue;
						if( ( ! isset( $group[ $sval["booking_ref"] ] ) ) && $refund_amount > 0 ){
							$refund = '<a href="#" class="custom-single-selected-record-button btn-xs btn red" action="?module=&action=sales&todo=refund_customer&customer=' . $guest . '" title="Click to Refund Guest" mod="' . $sval["room_checkin_id"] . '" override-selected-record="' . round( $refund_amount, 2 ) . '">Refund<br />'.number_format( $refund_amount , 2 ). '</a>';
						}
						
						if( isset( $group[ $sval["booking_ref"] ] ) ){
							$sval["amount_paid"] = 0;
							$due_text = "";
						}
						
						$group[ $sval["booking_ref"] ] = 1;
					}
					
					
					$body .= '<td class="company alternate">' . number_format( $income , 2 ) . '</td>';
					$body .= '<td>' . $due_text . '</td>';
					$body .= '<td>' . $refund . '</td>';
					
					if( $show_staff ){
						$body .= '<td>' . ( isset( $staff[ $sval["created_by"] ] )?$staff[ $sval["created_by"] ]:"" ) . '</td>';
					}
					
					$total["total amount paid by guest"] += $sval["amount_paid"];
					
				$body .= '</tr>';
			}
			$body .= '<tr><td colspan="9"></td></tr>';
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
	
		/*
		<?php if( $refund && $guest ){ ?>
		<a class="btn btn-sm red hidden-print custom-single-selected-record-button" action="?module=&action=sales&todo=refund_customer&customer=<?php echo $guest; ?>&store=10173870046" mod="<?php echo $room_checkin_id; ?>" override-selected-record="<?php echo $ab * -1; ?>" title="Click to refund guest <?php echo $ab * -1; ?>" >Refund Guest:  <?php echo number_format( $ab * -1, 2 ); ?></a>
		<?php } ?>
		*/
	?>
	<tr>
	<td colspan="3" rowspan="<?php echo count( $total ) + 1; ?>">
		<h4 style="color:#333;"><strong><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p>
		
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