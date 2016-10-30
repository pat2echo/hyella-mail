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
	
	//$customers = get_customers();
	$state =  get_hotel_room_status();
	$rooms = get_hotel_rooms();
	$staff = get_employees();
	
	include "guest_activity_report_transform.php";
	
	$show_staff = 1;
	if( isset( $skip_staff ) && $skip_staff )
		$show_staff = 0;
	
	if( ! empty( $data ) ){
		$group = array();
		
		foreach( $data as $d ){
			$tpaid = $d["total_paid"];
			$tdue = $d["total_due"];
			
			unset( $d["total_paid"] );
			unset( $d["total_due"] );
			
			foreach( $d as $sval ){
				$ocuppied = '';
				
				$charge = "";
				$charge1 = 0;
				
				$due_text = $sval["due_text"];
				$income = $sval["income"];
				
				if( $sval[ 'type' ] == "sales" ){
					$reference_table = "sales";
					$extra_reference = $sval["id"];
					
					switch( $sval["payment_method"] ){
					case "cash_refund":
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
					
					if( $charge )$charge1 = 1;
					
				}else{
					$reference_table = "hotel_checkin";
					$extra_reference = $sval["room_checkin_id"];
					
					$out = $sval["out"];
					$dis = $sval["dis"];
					
					if( $sval["amount_paid"] >= $income ){
						$charge = '<span class="label label-info">paid in full</span>';
					}
					
					if( $sval[ 'status' ] != "cancelled" )
						$total["total amount due from room rate"] += $income;
					
					if( $charge )$charge1 = 1;
				}
				
				$body .= '<tr class="item-sales" id="'.$sval["id"].'" serial="'.$sval["serial_num"].'" data-customer="'.$guest.'" data-comment="'.$sval["comment"].'" data-amount_owed="'. ($income - $sval["amount_paid"]) .'" data-amount_paid="'.$sval["amount_paid"].'" status="'.$charge1.'" data-reference_table="'.$reference_table.'" data-extra_reference="'.$extra_reference.'">';
					$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
					
					if( $sval[ 'type' ] == "sales" ){
						if( $sval[ 'payment_method' ] == "cash_refund" ){
							$body .= '<td>Refund REF: <strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app1&hide=1" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'" >#' . $sval["serial_num"] . '-' . $sval["id"] . '</a></strong></td>';
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
						$refund_amount = $tpaid - $tdue;
						$refund = '';
						
						$status = '';
						if( $sval[ 'status' ] == "cancelled" ){
							$refund_amount = $tpaid;
							$status = '<br /><br /><span class="label label-danger">rebate</span>';
							$charge = '<span class="label label-danger">refunded</span>';
						}
						
						if( ( ! isset( $group[ $sval["booking_ref"] ] ) ) && $refund_amount > 0 ){
							$refund = ' <a href="#" class="custom-single-selected-record-button btn-xs btn red" action="?module=&action=sales&todo=refund_customer2&customer=' . $guest . '&callback=nwRecordPayment.search" title="Click to Refund Guest" mod="' . $sval["room_checkin_id"] . '" override-selected-record="' . round( $refund_amount, 2 ) . '">Refund '.number_format( $refund_amount , 2 ). '</a>';
							
							$charge = '<span class="label label-danger">pending refund</span>';
						}
						$group[ $sval["booking_ref"] ] = 1;
						
						$body .= '<td><strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_invoice&hide=1" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'">#'.$sval["serial_num"]. '-'.$sval["id"]. '</a></strong>';
					
						$body .= '<br /><small>Check In: '.date( ( "d-M-Y" ), doubleval( $sval["checkin_date"] ) ). '</small>';
						$body .= ' | <small>Check Out: '.date( ( "d-M-Y" ), doubleval( $sval["checkout_date"] ) ). '</small>';
						$body .= '<br /><strong>' . ( isset( $rooms[ $sval["room"] ] )?$rooms[ $sval["room"] ]:$sval["room"] ) . '</strong>';
						$body .= '<br /><a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="'. $sval["id"] . '" action="?module=&action=hotel_checkin&todo=view_invoice&hide=1" title="View Invoice / Receipt">View Details <i class="icon-external-link"></i></a>' . $refund;
						$body .= '</td>';
					}
					
					
					$body .= '<td class="r">' . number_format( $income , 2 ) . '</td>';
					if( $charge )
						$body .= '<td class="r">' . $charge . $status .'</td>';
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
		}
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