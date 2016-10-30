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
	
	$room_count = array();
	
	if( ! empty( $all_rooms ) ){
		
		$sn = 0;
		$body .= '<tr>';
		
		foreach( $all_rooms as $sval ){
			$class = "#8eda8e";
			
			$status = ( isset( $o[ $sval["occupancy_status"] ] )?$o[ $sval["occupancy_status"] ]:$sval["occupancy_status"] );
			$cstatus = ( isset( $c[ $sval["cleaning_status"] ] )?$c[ $sval["cleaning_status"] ]:$sval["cleaning_status"] );
			
			switch( $sval["occupancy_status"] ){
			case "faulty":
			case "in_maintenance":
				$class = '#efe19e';
				
				$cstatus .= '<br /><br /><br /><button class="btn btn-xs dark custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_vacant_room_status" override-selected-record="'.$sval["id"].'" title="Check In or Update Room Status"><i class="icon-key"></i> Update Room Status</button>';
			break;
			case "blocked":
				$class = '#dddddd';
				
				$cstatus .= '<br /><br /><br /><button class="btn btn-xs dark custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_vacant_room_status" override-selected-record="'.$sval["id"].'" title="Check In or Update Room Status"><i class="icon-key"></i> Update Room Status</button>';
			break;
			case "active":
				$status = 'Vacant';
				$in = 0;
				$g = '';
				$todo = 'view_vacant_room_status';
				
				if( isset( $occuppied_rooms[ $sval["id"] ] ) ){
					$in_date = "";
					switch( $occuppied_rooms[ $sval["id"] ]["status"] ){
					case "checked_in":
						$status = 'Occuppied';
						$class = '#f39292';
						$in = 1;
					break;
					default:
						$status = ucwords( $occuppied_rooms[ $sval["id"] ]["status"] );
						$class = '#ffffff';
						$in_date = date(" d-M-Y", doubleval( $occuppied_rooms[ $sval["id"] ]["checkin_date"] ) );
						
						$todo = 'view_booked_room_status';
					break;
					}
					
					$guest = get_customers_details( array( "id" => $occuppied_rooms[ $sval["id"] ]["guest"] ) );
					$g = '<strong>'.strtoupper( isset($guest["name"])?$guest["name"]:"" ).'</strong>' . $in_date;
				}
				
				if( $in ){
					$cstatus .= '<br />'.$g.'<br /><br /><button class="btn btn-xs red custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo=view_occuppied_room_status" override-selected-record="'.$occuppied_rooms[ $sval["id"] ]["booking_ref"].'" mod="'.$occuppied_rooms[ $sval["id"] ]["room"].'" title="View More Information"><i class="icon-info-sign"></i> More Info</button>';
				}else{
					$cstatus .= '<br />'.$g.'<br /><br /><button class="btn btn-xs green custom-single-selected-record-button" action="?module=&action=hotel_checkin&todo='.$todo.'" override-selected-record="'.$sval["id"].'" title="Check In or Update Room Status"><i class="icon-key"></i> More Info</button>';
				}
				
			break;
			}
			
			$body .= '<td align="center" width="16.66667%" bgcolor="'.$class.'"><strong>' . $sval["room_number"] . " - ". ( isset( $rooms[ $sval["room_type"] ] )?$rooms[ $sval["room_type"] ]:"" ) . '</strong><br /><small>'.$status.', '.$cstatus.'</small></td>';
			
			$active1 = "vacant";
			if( $status == "Occuppied" ){
				$active1 = "occuppied";
			}
			
			if( ! isset( $room_count[ $sval["room_type"] ][ $active1 ] ) )
				$room_count[ $sval["room_type"] ] = array( "vacant" => 0, "occuppied" => 0 );
			
			$room_count[ $sval["room_type"] ][ $active1 ] += 1;
			
			++$sn;
			if( ! ( $sn % 6 ) ){
				$body .= '</tr><tr>';
			}
		}
		
		$body .= '</tr>';
	}
	?>
	<tr><td colspan="6">
		<button class="btn btn-sm pull-right red custom-action-button" function-name="group_checkin" skip-title="1" function-class="hotel" function-id="1"><i class="icon-group"></i> Group Check In</button>
		<h4><strong><?php echo $title; ?></strong></h4></td>
	</tr>

</thead>
<tbody>
<?php echo $body; ?>
</tbody>
<tfoot>
	<tr>
		<td colspan="6">
			<br />
			<?php
				$total_occuppied = 0;
				$total_vacant = 0;
				if( is_array( $room_count ) && ! empty( $room_count ) ){
					?>
					<table class="table table-bordered" cellspacing="0" style="width:50%; float:left;">
					<thead>
						<tr>
							<th>Room Type</th>
							<th>Occuppied</th>
							<th>Vacant</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach( $room_count as $k => $sval ){
						$total_vacant += $sval["vacant"];
						$total_occuppied += $sval["occuppied"];
						?>
						<tr>
							<td><?php echo ( isset( $rooms[ $k ] )?$rooms[ $k ]:"" ); ?></td>
							<td><?php echo $sval["occuppied"]; ?></td>
							<td><?php echo $sval["vacant"]; ?></td>
							<td><?php echo $sval["vacant"] + $sval["occuppied"]; ?></td>
						</tr>
						<?php
					}
					?>
					<tr class="total-row">
						<td>TOTAL</td>
						<td><?php echo $total_occuppied; ?></td>
						<td><?php echo $total_vacant; ?></td>
						<td><?php echo $total_vacant + $total_occuppied; ?></td>
					</tr>
					</tbody>
					</table>
					<?php
				}
			?>
			<table class="table table-bordered" cellspacing="0" style="width:40%; float:right;">
			<tbody>
				<tr>
					<td colspan="2" align="center">Key</td>
				</tr>
				<tr>
					<td bgcolor="#f39292" width="50%"><strong>OCCUPPIED</strong></td>
					<td bgcolor="#8eda8e"><strong>VACANT</strong></td>
				</tr>
				<tr>
					<td bgcolor="#efe19e" width="50%"><strong>FAULTY / IN-MAINTENANCE</strong></td>
					<td bgcolor="#dddddd"><strong>BLOCKED</strong></td>
				</tr>
			</tbody>
			</table>
		</td>
	</tr>
</tfoot>
</table>
</div>