<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total goods sold"] = 0;
	$total["total income"] = 0;
	$total["total amount paid"] = 0;
	$total["total amount owed"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	if( ! empty( $report_data ) ){
		
		//Array ( [Nov-2015] => Array ( [id] => 10467389855 [serial_num] => 10 [date] => 1446336061 [discount] => 0 [customer] => [store] => 10173870046 [sales_status] => sold [item] => cracked_eggs_crate [quantity_sold] => 1360 [amount_due] => 870000 ) ) 
		
		foreach( $report_data as $sval ){
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td>'.number_format( $sval["quantity_sold"] , 0 ). '</td>';
				
				$body .= '<td>' . ( isset(  $sval["value_of_goods"] )?number_format( $sval["value_of_goods"], 2 ):"-" ) . '</td>';
				//$body .= '<td>' . number_format( $sval["discount"], 2 ) . '</td>';
				
				$income = $sval["amount_due"];
				$body .= '<td class="company">' . number_format( $income , 2 ) . '</td>';
				
				$body .= '<td>' . number_format( $sval["amount_paid"], 2 ) . '</td>';
				$body .= '<td class="company landscape-only">' . number_format( $income - $sval["amount_paid"], 2 ) . '</td>';
				
				$total["total amount paid"] += $sval["amount_paid"];
				$total["total goods sold"] += ( $sval["quantity_sold"] );
				$total["total income"] += $income;
				$total["total amount owed"] += ( $income - $sval["amount_paid"] );
				
			$body .= '</tr>';
			
		}
		
		foreach( $total as & $v )$v = number_format( $v, 2 );
		
	}
	
	?>
	
	<tr><td colspan="3" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="4" class="landscape-only"><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr ><td colspan="2"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="2" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th rowspan="2" class="company">Date</th>
		<th rowspan="2">Units of Goods Sold</th>
		<th colspan="2">Income Generated</th>
		<th colspan="2">Income Collected</th>
	</tr>
	<tr>
		<th>Value of Goods Sold</th>
		<!--<th>Discount</th>-->
		<th class="company">Expected Income <small>(After Tax & Discount)</small></th>
		
		<th>Cash Received</th>
		<th class="company landscape-only">Outstanding</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>