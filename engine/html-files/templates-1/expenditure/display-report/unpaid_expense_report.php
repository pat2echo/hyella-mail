<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total expenditure"] = 0;
	$total["total amount paid"] = 0;
	$total["total amount owed to vendors"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$vendor = get_vendors();
	
	if( ! empty( $report_data ) ){
		
		//Array ( [Nov-2015] => Array ( [id] => 10467389855 [serial_num] => 10 [date] => 1446336061 [discount] => 0 [customer] => [store] => 10173870046 [sales_status] => sold [item] => cracked_eggs_crate [quantity_sold] => 1360 [amount_due] => 870000 ) ) 
		
		foreach( $report_data as $sval ){
			if( $sval["amount_paid"] )continue;
			
			$body .= '<tr>';
				
				//$body .= '<td >'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td class="company">'. ( isset( $vendor[ $sval["vendor"] ] )?$vendor[ $sval["vendor"] ]:$sval["vendor"] ) . '</td>';
				$body .= '<td class="landscape-only">'. $sval["description"] . '</td>';
				
				$body .= '<td>' . number_format( $sval["amount_due"], 2 ) . '</td>';
				$body .= '<td>' . number_format( $sval["amount_paid"], 2 ) . '</td>';
				
				$owed = $sval["amount_due"] - $sval["amount_paid"];
				$body .= '<td class="company">' . number_format( $owed , 2 ) . '</td>';
								
				$total["total expenditure"] += $sval["amount_due"];
				$total["total amount paid"] += $sval["amount_paid"];
				$total["total amount owed to vendors"] += ( $sval["amount_due"] - $sval["amount_paid"] );
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
	<tr><td colspan="2" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="3" ><h5>SUMMARY</h5></td></tr>
	
	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="1"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="2" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
	<th rowspan="2">Vendor / Supplier</th>
	<th rowspan="2" class="landscape-only">Description of Last Activity</th>
	<th colspan="3">Expenditure</th>
</tr>
<tr>
	<th>Amount Due</th>
	<th>Amount Paid</th>
	<th class="company">Amount Owed to Vendor</th>
</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>