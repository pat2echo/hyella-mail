<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	$total["gross profit"] = 0;
	
	$total["total expenditure"] = 0;
	$total["total income"] = 0;
	
	$total["amount owed by customers"]  = 0;
	$total["amount owed to vendors"] = 0;
	
	$total_row["total expenditure paid"] = 0;
	$total_row["total income paid"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	if( ! empty( $report_data ) ){
		$r = array();
		foreach( $report_data as $sval ){
			$r[ $sval["date"] ] = $sval;
		}
		krsort( $r );
		$report_data = $r;
		
		foreach( $report_data as $sval ){
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				
				if( ! isset( $sval["discount"] ) )$sval["discount"] = 0;
				if( ! isset( $sval["amount_due"] ) )$sval["amount_due"] = 0;
				if( ! isset( $sval["amount_paid"] ) )$sval["amount_paid"] = 0;
				
				if( ! isset( $sval["income_amount_due"] ) )$sval["income_amount_due"] = 0;
				if( ! isset( $sval["income_amount_paid"] ) )$sval["income_amount_paid"] = 0;
				
				$body .= '<td  class="company alternate">' . ( ( $sval["amount_due"] ) ? number_format( $sval["amount_due"], 2 ) : "-" ) . '</td>';
				$body .= '<td class="landscape-only hide-in-mobile">' . ( ( $sval["amount_paid"] )? number_format( $sval["amount_paid"], 2 ) :"-" ) . '</td>';
				
				$out = $sval["amount_due"] - $sval["amount_paid"];
				$body .= '<td class="landscape-only hide-in-mobile">' . ( ( $out ) ? number_format( $out , 2 ) : "-" ) . '</td>';
				
				$body .= '<td class="company alternate">' . ( ( $sval["income_amount_due"] ) ? number_format( $sval["income_amount_due"] - $sval["discount"] , 2 ) : "-" ) . '</td>';
				$body .= '<td class="landscape-only">' . ( ( $sval["income_amount_paid"] )? number_format( $sval["income_amount_paid"], 2 ) :"-" ) . '</td>';
				
				$inc = $sval["income_amount_due"] - ( $sval["discount"] + $sval["income_amount_paid"] );
				$body .= '<td class="landscape-only">' . ( ( $inc ) ? number_format( $inc , 2 ) : "-" ) . '</td>';
				
				$body .= '<td class="company alternate">' . ( number_format( ( $sval["income_amount_due"] - $sval["discount"] ) - $sval["amount_due"] , 2 ) ) . '</td>';
				
				$total["gross profit"] += ( $sval["income_amount_due"] - $sval["discount"] ) - $sval["amount_due"];
				
				$total["total expenditure"] += $sval["amount_due"];
				$total["total income"] += $sval["income_amount_due"] - $sval["discount"];
				
				$total_row["total expenditure paid"] += $sval["amount_paid"];
				$total_row["total income paid"] += $sval["income_amount_paid"];
				
				$total["amount owed by customers"] += $inc;
				$total["amount owed to vendors"] += $out;
			$body .= '</tr>';
			
		}
		
		foreach( $total as & $v )$v = number_format( $v, 2 );
		
		$body .= '<tr class="total-row">';
			$body .= '<td class="company">TOTAL</td>';
			$body .= '<td class="company alternate">'. $total["total expenditure"] . '</td>';
			$body .= '<td class="landscape-only hide-in-mobile">' . number_format( $total_row["total expenditure paid"] , 2 ) . '</td>';
			$body .= '<td class="landscape-only hide-in-mobile">' . $total["amount owed to vendors"] . '</td>';
			
			$body .= '<td class="company alternate">' . $total["total income"]  . '</td>';
			$body .= '<td class="landscape-only">' . number_format( $total_row["total income paid"] , 2 ) . '</td>';
			$body .= '<td class="landscape-only">' . $total["amount owed by customers"]  . '</td>';
			
			$body .= '<td class="company alternate">' . $total["gross profit"] . '</td>';
			
		$body .= '</tr>';
		
	
	}
	
	?>
	<tr><td colspan="3" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="5" class="landscape-only"><h5>SUMMARY</h5></td></tr>
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="2" class="landscape-only"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="3" class="landscape-only"><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
	<th rowspan="2" class="company">Date</th>
	<th rowspan="2" class="company alternate">Total Expense</th>
	<th colspan="2" class="landscape-only hide-in-mobile">Expenditure</th>
	
	<th rowspan="2"  class="company alternate">Total Income</th>
	<th colspan="2" class="landscape-only">Income</th>
	<th rowspan="2" class="company alternate">Gross Profit</th>
</tr>
<tr>
	<th class="landscape-only hide-in-mobile">Amount Paid</th>
	<th class="landscape-only hide-in-mobile">Amount Owed to Vendors</th>
	
	<th class="landscape-only">Amount Paid</th>
	<th class="landscape-only">Amount Owed by Customers</th>
	
</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>