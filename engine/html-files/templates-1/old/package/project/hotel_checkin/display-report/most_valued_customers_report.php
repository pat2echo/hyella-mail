<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total goods sold"] = 0;
	$total["total income"] = 0;
	$total["cash in bank"] = 0;
	$total["total amount owed"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$cus = get_customers();
	$state = get_sales_status();
	
	if( ! empty( $report_data ) ){
		//transform data
		$r = array();
		$r1 = array();
		foreach( $report_data as $sval ){
			if( ! isset( $r[ $sval["customer"] ] ) ){
				$r[ $sval["customer"] ] = $sval;
				$r1[ $sval["customer"] ] = $sval["amount_paid"];
			}
			else{
				foreach( $sval as $k => $v ){
					switch( $k ){
					case "quantity_sold":
					case "amount_due":
					case "amount_paid":
					case "discount":
						$r[ $sval["customer"] ][ $k ] += $v;
					break;
					}
				}
				$r1[ $sval["customer"] ] += $sval["amount_paid"];
			}
		}
		$report_data = $r;
		$serial = 0;
		arsort( $r1 );
		
		foreach( $r1 as $key => $val ){
			$sval = $report_data[ $key ];
			
			$income = $sval["amount_due"] - $sval["discount"];
			
			//if( $income <= $sval["amount_paid"] )continue;
			
			++$serial;
			
			$body .= '<tr>';
				
				$body .= '<td class="company">'.$serial. '</td>';
				$body .= '<td>'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td><strong>' . ( isset( $cus[ $sval["customer"] ] )?$cus[ $sval["customer"] ]:$sval["customer"] ) . '</strong></td>';
				//$body .= '<td><strong>' . $sval["serial_num"] . "-" . $sval["id"] . '</td>';
				
				//$body .= '<td>'. ( isset( $state[ $sval["sales_status"] ] )?$state[ $sval["sales_status"] ]:$sval["sales_status"] ) . '</td>';
				$body .= '<td class="hide-in-mobile">'.number_format( $sval["quantity_sold"] , 0 ). '</td>';
				
				$body .= '<td class="hide-in-mobile">' . number_format( $sval["amount_due"], 2 ) . '</td>';
				$body .= '<td class="hide-in-mobile">' . number_format( $sval["discount"], 2 ) . '</td>';
				
				
				$body .= '<td class="company">' . number_format( $income , 2 ) . '</td>';
				
				$body .= '<td class="company alternate">' . number_format( $sval["amount_paid"] , 2 ) . '</td>';
				$body .= '<td>' . number_format( $income - $sval["amount_paid"] , 2 ) . '</td>';
				
				
				$total["cash in bank"] += $sval["amount_paid"];
				
				$total["total goods sold"] += ( $sval["quantity_sold"] );
				$total["total income"] += $income;
				$total["total amount owed"] += ( $income - $sval["amount_paid"] );
				
			$body .= '</tr>';
			
		}
		
	}
	
	?>
	
	<tr><td colspan="10"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
	
	<tr>
		<th rowspan="2" class="company">Rank</th>
		<th rowspan="2">Last Transaction Date</th>
		<th rowspan="2">Customer</th>
		
		<th colspan="3" class="hide-in-mobile">Transaction Info</th>
		
		<th colspan="3">Payment Info</th>
		
	</tr>
	<tr>
		<th class="hide-in-mobile">Units of Goods Purchased</th>
		<th class="hide-in-mobile">Value of Goods Purchased</th>
		<th class="hide-in-mobile">Discount</th>
		<th class="company">Amount Due</th>
		
		<th class="alternate">Amount Paid</th>
		<th class="company">Amount Owed</th>
		
	</tr>
	
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>