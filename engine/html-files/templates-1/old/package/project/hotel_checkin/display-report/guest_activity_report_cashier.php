<div class="shopping-cart-table">
<table class="table table-striped bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total amount due from room rate"] = 0;
	$total["total amount due from sales"] = 0;
	$total["total amount paid by guest"] = 0;
	
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
	$pm = get_payment_method();
	
	$show_staff = 1;
	if( isset( $skip_staff ) && $skip_staff )
		$show_staff = 0;
	
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
			$charge = "";
			$charge1 = 0;
			
			if( isset( $sval[ 'item' ] ) ){
				if( $sval[ 'item' ] == "refund" ){
					$sval["amount_paid"] = $sval["amount_due"] * -1;
					$income = 0;
				}else{
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
					
					$total["total amount due from sales"] += $income;
					
					switch( $sval["payment_method"] ){
					case "charge_to_room":
					case "complimentary":
						if( isset( $pm[ $sval["payment_method"] ] ) )$charge = '<span class="label label-default">'.$pm[ $sval["payment_method"] ].'</span>';
					break;
					default:						
						if( $sval["amount_paid"] >= $income ){
							$charge = '<span class="label label-info">paid in full</span>';
						}
					break;
					}
				}
			}else{
				$out1 = doubleval( $sval["checkout_date"] );
				$in = doubleval( $sval["checkin_date"] );
				$out = get_date_difference( $out1, $in );
				
				$dis = $sval["discount"] + $sval["room_discount"];
				
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
				
				if( $sval["amount_paid"] >= $income ){
					$charge = '<span class="label label-info">paid in full</span>';
				}
				
				$total["total amount due from room rate"] += $income;
			}
			
			if( $charge )$charge1 = 1;
			
			$body .= '<tr class="item-sales" id="'.$sval["id"].'" serial="'.$sval["serial_num"].'" data-customer="'.$guest.'" data-comment="'.$sval["comment"].'" data-amount_owed="'. ($income - $sval["amount_paid"]) .'" data-amount_paid="'.$sval["amount_paid"].'" status="'.$charge1.'">';
			
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				
				if( isset( $sval[ 'item' ] ) ){
					if( $sval[ 'item' ] == "refund" ){
						$body .= '<td>Refund REF: <strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app1&hide=1" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'" >#' . $sval["serial_num"] . '-' . $sval["id"] . '</a></strong></td>';
						
						$sval["a_created_by"] = $sval["created_by"];
						
					}else{
						$body .= '<td><strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app1&hide=1" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'">#' . $sval["serial_num"] . '-' . $sval["id"] . '</a></strong>';
						
						$body .= '<br />Units Sold: <strong>' . $sval["quantity_sold"] . '</strong>' . ( isset( $customers[ $sval["customer"] ] )?("<br /><strong>".$customers[ $sval["customer"] ]."</strong>"):"" );
						
						if( $sval["comment"] ){
							$body .= '<br /><small><i>' . $sval["comment"] . '</i></small>';
						}
						
						$body .= '<br />';
						$body .= '<a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="'. $sval["id"] . '" action="?module=&action=sales&todo=view_invoice_app1" title="View Invoice / Receipt">View Details <i class="icon-external-link"></i></a>';
						
						$body .= '</td>';
					}
					
				}else{
					$body .= '<td><strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_invoice&hide=1" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'">#'.$sval["serial_num"]. '-'.$sval["id"]. '</a></strong>';
					
					$body .= '<br /><small>Check In: '.date( ( "d-M-Y" ), doubleval( $sval["checkin_date"] ) ). '</small>';
					$body .= ' | <small>Check Out: '.date( ( "d-M-Y" ), doubleval( $sval["checkout_date"] ) ). '</small>';
					$body .= '<br /><strong>' . ( isset( $rooms[ $sval["room"] ] )?$rooms[ $sval["room"] ]:$sval["room"] ) . '</strong>';
					$body .= '<br /><a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="'. $sval["id"] . '" action="?module=&action=hotel_checkin&todo=view_invoice&hide=1" title="View Invoice / Receipt">View Details <i class="icon-external-link"></i></a>';
					$body .= '</td>';
				}
				
				
				$body .= '<td class="r">' . number_format( $income , 2 ) . '</td>';
				if( $charge )
					$body .= '<td class="r">'.$charge.'</td>';
				else	
					$body .= '<td class="r">' . number_format( $income - $sval["amount_paid"], 2 ) . '</td>';
				/*
				if( $show_staff ){
					$body .= '<td>' . ( isset( $staff[ $sval["created_by"] ] )?$staff[ $sval["created_by"] ]:"" ) . '</td>';
				}
				*/
				$total["total amount paid by guest"] += $sval["amount_paid"];
				
			$body .= '</tr>';
			
		}
		
		$refund = 0;
		$ab = $total["total amount due from room rate"] + $total["total amount due from sales"] - $total["total amount paid by guest"];
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
	  <th>Date</th>
	  <th>Details</th>
	  <th class="r">Amount Due</th>
	  <th class="r">Amount Owed</th>
   </tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>