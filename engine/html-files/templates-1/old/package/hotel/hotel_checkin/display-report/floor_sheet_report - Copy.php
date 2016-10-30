<div class="report-table-preview">
<table class="table table-bordered" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$room_report = 0;
	if( isset( $in_active_rooms_report ) && $in_active_rooms_report )
		$room_report = $in_active_rooms_report;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$rooms = get_hotel_room_types();
	$all_rooms = get_all_hotel_rooms_with_details();
	
	$c = get_hotel_room_cleaning_status();
	$o = get_hotel_room_status();
	
	$occuppied_rooms = array();
	if( ! empty( $report_data ) ){
		foreach( $report_data as $sval ){
			if( isset( $sval["room_id"] ) )$occuppied_rooms[ $sval["room_id"] ] = $sval;
		}
	}
	//print_r($occuppied_rooms);
	//print_r($all_rooms);
	//print_r($rooms);
	
	$room_count = array();
	
	if( ! empty( $all_rooms ) ){
		
		$sn = 0;
		
		foreach( $all_rooms as $sval ){
			$body .= '<tr>';
			
			$class = "#8eda8e";
			$g = '';
			$g1 = '';
			$in_date = "";
			$out_date = "";
			
			$rate = ( isset( $room_details[ $sval["room_type"] ]["rate"] )?$room_details[ $sval["room_type"] ]["rate"]:0 );
			$vat = 0;
			$service_charge = 0;
			$service_tax = 0;
			
			$status = ( isset( $o[ $sval["occupancy_status"] ] )?$o[ $sval["occupancy_status"] ]:$sval["occupancy_status"] );
			//$cstatus = ( isset( $c[ $sval["cleaning_status"] ] )?$c[ $sval["cleaning_status"] ]:$sval["cleaning_status"] );
			$cstatus = "";
			
			switch( $sval["occupancy_status"] ){
			case "faulty":
			case "in_maintenance":
				$class = '#efe19e';
				
				//$cstatus .= '<br /><br /><br /><button class="btn btn-xs dark custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_vacant_room_status" override-selected-record="'.$sval["id"].'" title="Check In or Update Room Status"><i class="icon-key"></i> Update Room Status</button>';
			break;
			case "blocked":
				$class = '#dddddd';
				
				//$cstatus .= '<br /><br /><br /><button class="btn btn-xs dark custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_vacant_room_status" override-selected-record="'.$sval["id"].'" title="Check In or Update Room Status"><i class="icon-key"></i> Update Room Status</button>';
			break;
			case "active":
				$status = 'Vacant';
				$in = 0;
				$g = '';
				$todo = 'view_vacant_room_status';
				
				if( isset( $occuppied_rooms[ $sval["id"] ] ) ){
					$in_date = "";
					$out_date = "";
					$in_date = date(" d-M-Y", doubleval( $occuppied_rooms[ $sval["id"] ]["checkin_date"] ) );
					$out_date = date(" d-M-Y", doubleval( $occuppied_rooms[ $sval["id"] ]["checkout_date"] ) );
						
					switch( $occuppied_rooms[ $sval["id"] ]["status"] ){
					case "checked_in":
						$status = 'Occuppied Paid';
						$class = '#ff9955';
						
						$in = 1;
						
						$nights = get_date_difference( $occuppied_rooms[ $sval["id"] ]["checkout_date"] , $occuppied_rooms[ $sval["id"] ]["checkin_date"] );
						$amount_due = ( $nights * $occuppied_rooms[ $sval["id"] ]["room_rate"] ) - $occuppied_rooms[ $sval["id"] ]["room_discount"];
						
						if( doubleval( $occuppied_rooms[ $sval["id"] ]["amount_paid"] ) < $amount_due ){
							$status = 'Occuppied Owing';
							$class = '#f39292';
						}
					break;
					default:
						$status = ucwords( $occuppied_rooms[ $sval["id"] ]["status"] );
						$class = '#ffffff';
						
						$todo = 'view_booked_room_status';
					break;
					}
					
					$guest = get_customers_details( array( "id" => $occuppied_rooms[ $sval["id"] ]["guest"] ) );
					$g = '<strong>'.strtoupper( isset($guest["name"])?$guest["name"]:"" ).'</strong>';
					
					if( $occuppied_rooms[ $sval["id"] ]["guest"] != $occuppied_rooms[ $sval["id"] ]["main_guest"] ){
						$guest = get_customers_details( array( "id" => $occuppied_rooms[ $sval["id"] ]["main_guest"] ) );
						$g1 = strtoupper( isset($guest["name"])?$guest["name"]:"" );
					}
				}
				/*
				if( $in ){
					$cstatus .= '<br />'.$g.'<br /><br /><button class="btn btn-xs red custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_occuppied_room_status" override-selected-record="'.$occuppied_rooms[ $sval["id"] ]["booking_ref"].'" mod="'.$occuppied_rooms[ $sval["id"] ]["room"].'" title="View More Information"><i class="icon-info-sign"></i> More Info</button>';
				}else{
					$cstatus .= '<br />'.$g.'<br /><br /><button class="btn btn-xs green custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo='.$todo.'" override-selected-record="'.$sval["id"].'" title="Check In or Update Room Status"><i class="icon-key"></i> More Info</button>';
				}
				*/
			break;
			}
			
			//$body .= '<td align="center" width="16.66667%" bgcolor="'.$class.'"><strong>' . $sval["room_number"] . " - ". ( isset( $rooms[ $sval["room_type"] ] )?$rooms[ $sval["room_type"] ]:"" ) . '</strong><br /><small>'.$status.' '.$cstatus.'</small></td>';
			
			$active1 = "vacant";
			if( $status == 'Occuppied Paid' || $status == 'Occuppied Owing' ){
				$active1 = "occuppied";
			}
			
			if( ! isset( $room_count[ $sval["room_type"] ][ $active1 ] ) )
				$room_count[ $sval["room_type"] ] = array( "vacant" => 0, "occuppied" => 0 );
			
			$room_count[ $sval["room_type"] ][ $active1 ] += 1;
			
			
			$body .= '<td class="company">' . ++$sn . '</td>';
			$body .= '<td><strong>' . $sval["room_number"] . " - ". ( isset( $rooms[ $sval["room_type"] ] )?$rooms[ $sval["room_type"] ]:"" ) . '</strong></td>';
			$body .= '<td><strong>'.$status.' '.$cstatus.'</strong></td>';
			$body .= '<td>'.$g.'</td>';
			$body .= '<td>'.$g1.'</td>';
			$body .= '<td>'.$in_date.'</td>';
			$body .= '<td>'.$out_date.'</td>';
			
			$body .= '</tr>';
		}
		
		
	}
	?>
	<tr><td colspan="7">
		<h4><strong><?php echo $title; ?></strong></h4></td>
	</tr>
	
	<tr>
		<th>S/N</th>
		<th>Room</th>
		<th>Status</th>
		<th>Room Guest</th>
		<th>Group</th>
		<th>Checkin Date</th>
		<th>Checkout Date</th>
	</tr>
	
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>