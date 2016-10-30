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
		foreach( $report_data as $sval ){
			if( ! isset( $r[ $sval["customer"] ] ) )$r[ $sval["customer"] ] = $sval;
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
			}
		}
		$report_data = $r;
		
		foreach( $report_data as $sval ){
			
			$income = $sval["amount_due"] - $sval["discount"];
			
			//if( $income < $sval["amount_paid"] )continue;
			
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td><strong>' . ( isset( $cus[ $sval["customer"] ] )?$cus[ $sval["customer"] ]:$sval["customer"] ) . '</strong></td>';
				//$body .= '<td><strong>' . $sval["serial_num"] . "-" . $sval["id"] . '</td>';
				
				//$body .= '<td>'. ( isset( $state[ $sval["sales_status"] ] )?$state[ $sval["sales_status"] ]:$sval["sales_status"] ) . '</td>';
				$body .= '<td>'.number_format( $sval["quantity_sold"] , 0 ). '</td>';
				
				$body .= '<td>' . number_format( $sval["amount_due"], 2 ) . '</td>';
				$body .= '<td>' . number_format( $sval["discount"], 2 ) . '</td>';
				
				
				$body .= '<td class="company">' . number_format( $income , 2 ) . '</td>';
				
				$body .= '<td>' . number_format( $sval["amount_paid"] , 2 ) . '</td>';
				$body .= '<td class="company">' . number_format( $income - $sval["amount_paid"] , 2 ) . '</td>';
				
				
				$total["cash in bank"] += $sval["amount_paid"];
				
				$total["total goods sold"] += ( $sval["quantity_sold"] );
				$total["total income"] += $income;
				$total["total amount owed"] += ( $income - $sval["amount_paid"] );
				
			$body .= '</tr>';
			
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
	<tr><td colspan="3" rowspan="<?php echo count( $total ) + 1; ?>"><h4 id="e-report-title"><?php echo $title; ?></h4></td><td colspan="4" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="2"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="2" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th rowspan="2" class="company">Last Transaction Date</th>
		<th rowspan="2">Customer</th>
		
		<th colspan="4">Transaction Info</th>
		<th colspan="2">Payment Info</th>
		
	</tr>
	<tr>
		<th>Units of Goods Purchased</th>
		<th>Value of Goods Purchased</th>
		<th>Discount</th>
		<th class="company">Amount Due</th>
		
		<th>Amount Paid</th>
		<th class="company">Amount Owed</th>
		
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>