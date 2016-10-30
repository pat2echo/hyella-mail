<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$state =  get_hotel_room_status();
	$rooms = get_hotel_rooms_with_details();
	$store = get_stores();
	
	$total_deposit = 0;
	$total_con = 0;
	$total_credit = 0;
	$total_debit = 0;
	
	if( ! empty( $report_data ) ){
		
		foreach( $report_data as $sval ){
			
			$body .= '<tr>';
			
				$body .= '<td>' . date( "d-M-Y" , $sval["checkin_date"] ) . '</td>';
				$body .= '<td>' . date( "d-M-Y" , $sval["checkout_date"] ) . '</td>';
				
				if( isset( $sval["room"] ) )$body .= '<td class="company"><strong>' . ( isset( $rooms[ $sval["room"] ]["room_number"] )?$rooms[ $sval["room"] ]["room_number"]:$sval["room"] ) . '</strong></td>';
				else $body .= '<td>-</td>';
				
				$cus = get_customers_details( array( "id" => $sval["room_guest"] ) );
				$body .= '<td>' . ( isset( $cus["name"] )?$cus["name"]:"-" ) .'</td>';
				
				$credit_deposit = doubleval( $sval["credit"] );
				$debit_consumption = doubleval( $sval["debit"] );
				
				$bal = $credit_deposit - $debit_consumption;
				
				$total_deposit += $credit_deposit;
				$total_con += $debit_consumption;
				
				$body .= '<td>' . number_format( $credit_deposit , 2 ) . '</td>';
				$body .= '<td>' . number_format( $debit_consumption , 2 ) . '</td>';
				
				if( $bal > 0 ){
					$body .= '<td>' . number_format( $bal , 2 ) . '</td>';
					$body .= '<td>-</td>';
					$total_credit += $bal;
				}else{
					$body .= '<td>-</td>';
					$body .= '<td>(' . number_format( abs( $bal ) , 2 ) . ')</td>';
					
					$total_debit += abs( $bal );
				}
				
			$body .= '</tr>';
			
		}
		
		$body .= '<tr><td colspan="8">&nbsp;</td></tr>';
		$body .= '<tr class="total-row"><td colspan="4" align="right">TOTAL</td><td>' . number_format( $total_deposit, 2 ) . '</td><td>' . number_format( $total_con, 2 ) . '</td><td>' . number_format( $total_credit, 2 ) . '</td><td>' . number_format( $total_debit, 2 ) . '</td></tr>';
		$body .= '<tr><td colspan="4" align="right"><strong>BALANCE</strong></td><td colspan="4"><strong>' . number_format( $total_deposit - $total_con, 2 ) . '</strong></td></tr>';
	}
?>
<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<tr><td colspan="8"><h4><strong><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
	<tr>
		<th>Check In</th>
		<th>Check Out</th>
		<th class="company">Room</th>
		<th >Guest</th>
		
		<th>Deposit</th>
		<th>Consumption</th>
		<th>Credit Balance</th>
		<th>Debit Balance</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>	