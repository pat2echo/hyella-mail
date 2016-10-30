<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$time = "";
	if( isset( $data[ 'time' ] ) )
		$time = str_replace(":", ".", $data[ 'time' ] );
	
	$etime = "";
	if( isset( $data[ 'end_time' ] ) )
		$etime = str_replace(":", ".", $data[ 'end_time' ] );
	
	$time = doubleval( $time );
	$etime = doubleval( $etime );
	
	$day_time = "";
	if( isset( $data[ 'day_time' ] ) )
		$day_time = $data[ 'day_time' ];
	
	$state =  get_hotel_room_status();
	$rooms = get_hotel_rooms_with_details();
	$staff = get_employees();
	$store = get_stores();
	
	$room_type = get_hotel_room_types();
	
	$pm = get_payment_method();
	$pm["cash_refund"] = "Cash Refund";
	
	if( ! empty( $report_data ) ){
		
		$group = array();
		$body1 = array();
		$total = array();
		
		foreach( $report_data as $sval ){
			$ocuppied = '';
			
			if( isset( $group[ $sval["id"] ] ) ){
				++$group[ $sval["id"] ];
				$sval["amount_paid"] = 0;
				$sval["discount"] = 0;
			}else{
				$group[ $sval["id"] ] = 1;
			}
			
			$sval["creation_date"] = doubleval( $sval["creation_date"] );
			$sval["modification_date"] = doubleval( $sval["modification_date"] );
			$pass = 1;
			switch( $day_time ){
			case "day":
				$pass = 0;
				if( ( doubleval( date("H.i", $sval["creation_date"] ) ) < $time && doubleval( date("H.i", $sval["creation_date"] ) ) > $etime ) || ( date("H.i", $sval["modification_date"] ) < $time && date("H.i", $sval["modification_date"] ) > $etime ) ){
					$pass = 1;
				}
			break;
			case "night":
				$pass = 0;
				if( ( doubleval( date("H.i", $sval["creation_date"] ) ) > $time || doubleval( date("H.i", $sval["creation_date"] ) ) < $etime ) || ( doubleval( date("H.i", $sval["modification_date"] ) ) > $time || doubleval( date("H.i", $sval["modification_date"] ) ) < $etime ) ){
					$pass = 1;
				}
			break;
			}
			
			if( ! $pass )continue;
			
			$body = '<tr '.$ocuppied.'>';
			
				$body .= '<td>' . $sval["sales_id"] . '</td>';
				$body .= '<td class="company">' . date( "H:i " , $sval["modification_date"] ) . '</td>';
				$body .= '<td>' . date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ) . '</td>';
				$desc = '';
				
				if( isset( $sval[ 'item' ] ) ){
					$body .= '<td>-</td>';
					$cus = get_customers_details( array( "id" => $sval["customer"] ) );
					
					$desc = '<strong>' . ( isset( $store[ $sval["store"] ] )?$store[ $sval["store"] ]:"" ) . '</strong><br />' . $sval["comment"];
				}else{
					$cus = get_customers_details( array( "id" => $sval["room_guest"] ) );
					
					if( isset( $sval["room"] ) )$body .= '<td><strong>' . ( isset( $rooms[ $sval["room"] ]["room_number"] )?$rooms[ $sval["room"] ]["room_number"]:$sval["room"] ) . '</strong></td>';
					
					if( isset( $sval["room_type"] ) )$body .= '<td><strong>' . ( isset( $room_type[ $sval["room_type"] ] )?$room_type[ $sval["room_type"] ]:$sval["room_type"] ) . '</strong></td>';
					
					$desc = '<strong>Hotel Room</strong><br />' . $sval["comment"];
				}
				
				switch( $sval["payment_method"] ){
				case "complimentary_staff":
					$body .= '<td>' . ( isset( $staff[ $sval["staff_responsible"] ] )?$staff[ $sval["staff_responsible"] ]:"" ) . '</td>';
				break;
				default:
					$body .= '<td>' . ( isset( $cus["name"] )?$cus["name"]:"-" ) .'</td>';
				break;
				}
				
				$body .= '<td>' . $desc .'</td>';
				
				
				$pmv = $sval["payment_method"];
				if( isset( $pm[ $sval["payment_method"] ] ) )$pmv = $pm[ $sval["payment_method"] ];
				
				if( ! isset( $total[ $pmv ] ) )
					$total[ $pmv ] = 0;
				
				switch( $sval["payment_method"] ){
				case "cash_refund":
				case "charge_to_room":
				case "complimentary":
				case "complimentary_staff":
					$body .= '<td>' . number_format( $sval["amount_due"], 2 ) . '</td>';
					$total[ $pmv ] += $sval["amount_due"];
				break;
				default:
					$body .= '<td>' . number_format( $sval["amount_paid"], 2 ) . '</td>';
					$total[ $pmv ] += $sval["amount_paid"];
				break;
				}
				
				$body .= '<td>' . $pmv . '</td>';
				
				$body .= '<td>' . ( isset( $staff[ $sval["created_by"] ] )?$staff[ $sval["created_by"] ]:"" ) . '</td>';
				
			$body .= '</tr>';
			
			if( ! isset( $body1[ $pmv ] ) )$body1[ $pmv ] = "";
			
			$body1[ $pmv ] .= $body;
		}
		
	}
	
	
	?>
<div class="report-table-preview">
	<table class="table table-striped table-bordered table-hover" cellspacing="0">
	<thead>
		<tr><td colspan="9"><h4><strong><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>
	</thead>
	<?php 
		foreach( $body1 as $k => $v ){
	?>
	
	  <tr ><td colspan="9"><?php echo $k; ?></td></tr>
	  <tr >
		<td class="company">Receipt</td>
		<td class="company">Time</td>
		<td class="company">Date</td>
		<td class="company">Room</td>
		<?php 
			switch( $k ){
			case "Complimentary Staff":
		?>
			<td class="company">Staff Name</td>
		<?php
			break;
			default:
			?>
			<td class="company">Guest Name</td>
		<?php
			break;
			}
		?>
		<td class="company">Desc</td>
		<?php 
			switch( $k ){
			case "Complimentary Staff":
			case "Complimentary":
			?> <td class="company">Compliment</td> <?php
			break;
			case "Charge to Room":
			?> <td class="company">Amount Charged</td> <?php
			break;
			case "Cash Refund":
			?> <td class="company">Amount Disbursed</td> <?php
			break;
			default:
			 ?> <td class="company">Amount Received</td> <?php
			break;
			}
		?>
		<td class="company">Payment Method</td>
		<td class="company">Cashier</td>
	</tr>
	
	<?php echo $v; ?>
	<tr><td colspan="6" align="right"><strong>Total <?php echo $k; ?></strong></td><td><strong><?php if( isset( $total[ $k ] ) )echo number_format( $total[ $k ], 2 ); ?></strong></td><td colspan="2"></td></tr>
	<tr><td colspan="9">&nbsp;</td></tr>
	
	<?php } ?>
	
	<tr><td colspan="6" align="right"><strong>TOTAL AMOUNT RECEIVED</strong></td><td><strong><?php 
		$t = 0;
		foreach( $total as $k => $v ){
			switch( $k ){
			case "Complimentary Staff":
			case "Complimentary":
			case "Charge to Room":
			case "Cash Refund":
			break;
			default:
				$t += $v;
			break;
			}
		}
		echo number_format( $t, 2 );
	?>
	</strong></td><td colspan="2"></td></tr>
	</table>
</div>