<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
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
	$c = get_hotel_room_cleaning_status();
	$o = get_hotel_room_status();
	
	if( ! empty( $report_data ) ){
		
		//Array ( [Nov-2015] => Array ( [id] => 10467389855 [serial_num] => 10 [date] => 1446336061 [discount] => 0 [customer] => [store] => 10173870046 [sales_status] => sold [item] => cracked_eggs_crate [quantity_sold] => 1360 [amount_due] => 870000 ) ) 
		//print_r( $report_data );
		
		$sn = 0;
		foreach( $report_data as $sval ){
			
			$ocuppied = '';
			
			$body .= '<tr '.$ocuppied.'>';
				
				$body .= '<td class="company">' . ++$sn . '</td>';
				$body .= '<td><strong>' . $sval["room_number"] . " ". ( isset( $rooms[ $sval["room_type"] ] )?$rooms[ $sval["room_type"] ]:"" ) . '</strong></td>';
				$body .= '<td>' . $sval["floor"] . '</td>';
				$body .= '<td>' . $sval["building"] . '</td>';
				$body .= '<td>' . ( isset( $c[ $sval["cleaning_status"] ] )?$c[ $sval["cleaning_status"] ]:$sval["cleaning_status"] ) . '</td>';
				$body .= '<td>' . ( isset( $o[ $sval["occupancy_status"] ] )?$o[ $sval["occupancy_status"] ]:$sval["occupancy_status"] ) . '</td>';
				$body .= '<td>' . $sval["comment"] . '</td>';
				
			$body .= '</tr>';
			
		}
	}
	?>
	<tr><td colspan="7" ><h4><strong><?php echo $title; ?></strong></h4></td></tr>
<tr>
	<th class="company">S/N</th>
	<th>Room</th>
	<th>Floor</th>
	<th>Building</th>
	<th>Cleaning Status</th>
	<th>Occupancy Status</th>
	<th>Comment</th>
</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>