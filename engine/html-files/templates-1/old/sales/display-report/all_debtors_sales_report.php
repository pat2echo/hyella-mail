<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total goods sold"] = 0;
	$total["total income"] = 0;
	$total["total amount owed"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$cus = get_customers();
	$state = get_sales_status();
	
	if( ! empty( $report_data ) ){
		
		//Array ( [Nov-2015] => Array ( [id] => 10467389855 [serial_num] => 10 [date] => 1446336061 [discount] => 0 [customer] => [store] => 10173870046 [sales_status] => sold [item] => cracked_eggs_crate [quantity_sold] => 1360 [amount_due] => 870000 ) ) 
		
		foreach( $report_data as $sval ){
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td><strong>' . $sval["serial_num"] . "-" . $sval["id"] . '</strong></td>';
				
				$body .= '<td class="landscape-only">'. ( isset( $state[ $sval["sales_status"] ] )?$state[ $sval["sales_status"] ]:$sval["sales_status"] ) . '</td>';
				$body .= '<td>'.number_format( $sval["quantity_sold"] , 0 ). '</td>';
				
				$body .= '<td>' . number_format( $sval["amount_due"], 2 ) . '</td>';
				$body .= '<td>' . number_format( $sval["discount"], 2 ) . '</td>';
				
				$income = $sval["amount_due"] - $sval["discount"];
				$body .= '<td class="company">' . number_format( $income , 2 ) . '</td>';
				
				$body .= '<td>' . ( isset( $cus[ $sval["customer"] ] )?$cus[ $sval["customer"] ]:$sval["customer"] ) . '</td>';
				
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
	
	<tr><td colspan="4" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="4" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="2"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="2" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th rowspan="2" class="company">Date</th>
		<th rowspan="2">Receipt Num</th>
		
		<th colspan="5">Sales Info</th>
		<th rowspan="2">Customer</th>
	</tr>
	<tr>
		<th class="landscape-only">Status</th>
		<th>Units of Goods Sold</th>
		<th>Value of Goods Sold</th>
		<th>Discount</th>
		<th class="company">Amount Owed by Customer</th>
		
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>