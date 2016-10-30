<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$state =  get_hotel_room_status();
	$rooms = get_hotel_rooms();
	$staff = get_employees();
	
	if( ! empty( $report_data ) ){
		
		$group = array();
		$serial = 0;
		
		foreach( $report_data as $sval ){
			$ocuppied = '';
			
			$body .= '<tr '.$ocuppied.'>';
				
				$body .= '<td class="company">' . ++$serial . '</td>';
				
				$cus = get_customers_details( array( "id" => $sval["main_guest"] ) );
				if( isset( $cus["name"] ) )$body .= '<td>' . $cus["name"] . '<br />' . $cus["phone"] . '<br />'.$cus["address"].'</td>';
				else $body .= '<td>' . $sval["main_guest"] . '</td>';
				
				$cus = get_customers_details( array( "id" => $sval["room_guest"] ) );
				if( isset( $cus["name"] ) )$body .= '<td>' . $cus["name"] . '<br />' . $cus["phone"] . '<br />'.$cus["address"].'</td>';
				else $body .= '<td>' . $sval["main_guest"] . '</td>';
				
				$body .= '<td><strong>' . ( isset( $rooms[ $sval["room"] ] )?$rooms[ $sval["room"] ]:$sval["room"] ) . '</strong></td>';
				
				$body .= '<td>'.date( ( "d-M-Y" ), doubleval( $sval["checkin_date"] ) ). '</td>';
				$body .= '<td>'.date( ( "d-M-Y" ), doubleval( $sval["checkout_date"] ) ). '</td>';
				
				$body .= '<td class="hide-in-mobile">'. ( isset( $state[ $sval['status'] ] )?$state[ $sval['status'] ]:$sval['status'] ) . '</td>';
				
				$out1 = doubleval( $sval["checkout_date"] );
				$in = doubleval( $sval["checkin_date"] );
				$out = get_date_difference( $out1, $in );
				$body .= '<td>' . ( isset( $staff[ $sval["created_by"] ] )?$staff[ $sval["created_by"] ]:"" ) . '</td>';
				
			$body .= '</tr>';
			
		}
		
		//foreach( $total as & $v )$v = number_format( $v, 2 );
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
	<tr><td colspan="10"><h4><strong><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
	
<tr>
		<th rowspan="2" class="company">S/N</th>
		<th colspan="2">Guest</th>
		<th rowspan="2">Room</th>
		
		<th colspan="2" class="hide-in-mobile">In / Out Date</th>
		<th rowspan="2" class="alternate">Room Status</th>
		
		<th rowspan="2">Staff Responsible</th>
		
	</tr>
	<tr>
		<th>Paying Guest</th>
		<th>Room Guest</th>
		
		<th class="hide-in-mobile">Checked In</th>
		<th class="hide-in-mobile">Checked Out</th>
		
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>