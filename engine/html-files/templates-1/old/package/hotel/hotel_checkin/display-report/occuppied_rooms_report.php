<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total income from room rate"] = 0;
	$total["total occuppied rooms"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$rooms = get_hotel_room_types();
	$cus = get_customers();
	
	if( ! empty( $report_data ) ){
		
		$sn = 0;
		$outdate = date("U");
		foreach( $report_data as $sval ){
			
			$ocuppied = '';
			
			if( $sval["checkout_date"] < $outdate ){
				$ocuppied = ' style="color:#d00;" ';
			}
			
			$body .= '<tr '.$ocuppied.'>';
				
				$body .= '<td class="company">' . ++$sn . '</td>';
				$body .= '<td><strong>' . $sval["room_number"] . " - " . ( isset( $rooms[ $sval["room_type"] ] )?$rooms[ $sval["room_type"] ]:$sval["room_type"] ) . '</strong></td>';
				
				$body .= '<td>' . ( isset( $cus[ $sval["paying_guest"] ] )?$cus[ $sval["paying_guest"] ]:$sval["paying_guest"] ) . '</td>';
				$body .= '<td>' . ( isset( $cus[ $sval["main_guest"] ] )?$cus[ $sval["main_guest"] ]:$sval["main_guest"] ) . '</td>';
				
				$body .= '<td>' . $sval["status"] . '</td>';
				$body .= '<td>' . $sval["floor"] . '</td>';
				$body .= '<td>' . $sval["building"] . '</td>';
				
				$body .= '<td>'.date( ( "d-M-Y" ), doubleval( $sval["checkin_date"] ) ). '</td>';
				$body .= '<td>'.date( ( "d-M-Y" ), doubleval( $sval["checkout_date"] ) ). '</td>';
				
			$body .= '</tr>';
			
		}
	}
	?>
	<tr><td colspan="9" ><h4><strong><?php echo $title; ?></strong></h4></td></tr>
<tr>
	<th class="company">S/N</th>
	<th>Room</th>
	<th>Paying Guest</th>
	<th>Room Guest</th>
	<th>Status</th>
	<th>Floor</th>
	<th>Building</th>
	<th>Check In</th>
	<th>Check Out</th>
</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>