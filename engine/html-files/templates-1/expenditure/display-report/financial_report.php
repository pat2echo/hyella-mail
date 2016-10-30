<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total expense incurred + salaries"] = 0;
	
	$total_row["total expense incurred"] = 0;
	$total_row["total expense paid"] = 0;
	$total["amount owed to vendors"] = 0;
	
	$total_row["total salaries"] = 0;
	$total["total income"] = 0;
	$total_row["total income received"]  = 0;
	
	$total["amount owed by customers"]  = 0;
	$total["amount in bank"]  = 0;
	
	$total_row["total deposit"] = 0;
	$total_row["total withdrawal"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	if( ! empty( $report_data ) ){
		
		foreach( $report_data as $sval ){
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				
				if( ! isset( $sval["amount_due"] ) )$sval["amount_due"] = 0;
				if( ! isset( $sval["amount_paid"] ) )$sval["amount_paid"] = 0;
				if( ! isset( $sval["SALARY_PAID"] ) )$sval["SALARY_PAID"] = 0;
				if( ! isset( $sval["INCOME_RECEIVED"] ) )$sval["INCOME_RECEIVED"] = 0;
				if( ! isset( $sval["INCOME"] ) )$sval["INCOME"] = 0;
				
				if( ! isset( $sval["description"] ) )$sval["description"] = "-";
				
				if( ! isset( $sval["DEPOSIT"] ) )$sval["DEPOSIT"] = 0;
				if( ! isset( $sval["WITHDRAWAL"] ) )$sval["WITHDRAWAL"] = 0;
				
				$body .= '<td>' . $sval["description"] . '</td>';
				$body .= '<td>' . ( ( $sval["amount_due"] ) ? number_format( $sval["amount_due"], 2 ) : "-" ) . '</td>';
				$body .= '<td>' . ( ( $sval["amount_paid"] )? number_format( $sval["amount_paid"], 2 ) :"-" ) . '</td>';
				
				
				$out = $sval["amount_due"] - $sval["amount_paid"];
				$body .= '<td>' . ( ( $out ) ? number_format( $out , 2 ) : "-" ) . '</td>';
				
				$body .= '<td>' . ( ( $sval['SALARY_PAID'] )?number_format( $sval['SALARY_PAID'], 2 ):"-" ) . '</td>';
				$body .= '<td>' . ( ( $sval['INCOME'] )?number_format( $sval['INCOME'], 2 ):"-" ) . '</td>';
				$body .= '<td>' . ( ( $sval['INCOME_RECEIVED'] )?number_format( $sval['INCOME_RECEIVED'], 2 ):"-" ) . '</td>';
				
				$body .= '<td>' . ( ( $sval['DEPOSIT'] )?number_format( $sval['DEPOSIT'], 2 ):"-" ) . '</td>';
				$body .= '<td>' . ( ( $sval['WITHDRAWAL'] )?number_format( $sval['WITHDRAWAL'], 2 ):"-" ) . '</td>';
				
				$total_row["total deposit"] += ( $sval["DEPOSIT"] );
				$total_row["total withdrawal"] += ( $sval["WITHDRAWAL"] );
				
				$total_row["total income received"] += ( $sval["INCOME_RECEIVED"] );
				$total["total income"] += ( $sval["INCOME"] );
				$total_row["total salaries"] += ( $sval["SALARY_PAID"] );
				
				$total_row["total expense incurred"] += ( $sval["amount_due"] );
				$total_row["total expense paid"] += ( $sval["amount_paid"] );
				
				$total["amount owed to vendors"] += $out;
				
			$body .= '</tr>';
			
		}
		
		$total["total expense incurred + salaries"] = number_format( $total_row["total expense incurred"] + $total_row["total salaries"] , 2 );
		$total["amount owed by customers"]  = number_format( $total["total income"] - $total_row["total income received"] , 2 );
		$total["amount in bank"]  = number_format( $total_row["total deposit"] - $total_row["total withdrawal"] , 2 );
		
		$total_row["total deposit"] = number_format( $total_row["total deposit"] , 2 );
		$total_row["total withdrawal"] = number_format( $total_row["total withdrawal"] , 2 );
	
		$total_row["total income received"]  = number_format( $total_row["total income received"] , 2 );
		$total["total income"] = number_format( $total["total income"], 2 );
		
		$total_row["total salaries"] = number_format( $total_row["total salaries"], 2 );
		
		$total_row["total expense incurred"] = number_format( $total_row["total expense incurred"], 2 );
		$total_row["total expense paid"] = number_format( $total_row["total expense paid"] , 2 );
		$total["amount owed to vendors"] = number_format( $total["amount owed to vendors"], 2 );
		
		$body .= '<tr class="total-row">';
			$body .= '<td class="company">TOTAL</td>';
			$body .= '<td></td>';
			$body .= '<td>'. $total_row["total expense incurred"] . '</td>';
			$body .= '<td>' . $total_row["total expense paid"] . '</td>';
			$body .= '<td>' . $total["amount owed to vendors"] . '</td>';
			$body .= '<td>' . $total_row["total salaries"] . '</td>';
			$body .= '<td>' . $total["total income"]  . '</td>';
			$body .= '<td>' . $total_row["total income received"]  . '</td>';
			
			$body .= '<td>' . $total_row["total deposit"]  . '</td>';
			$body .= '<td>' . $total_row["total withdrawal"]  . '</td>';
		$body .= '</tr>';
		
	
	}
	
	?>
	<tr><td colspan="5" rowspan="<?php echo count( $total ) + 1; ?>"><h4><?php echo $title; ?></h4></td><td colspan="5" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="3"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="2" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th  class="company">Date</th>
		<th >Description of Expense</th>
		<th >Expenses Incurred</th>
		<th >Expenses Paid</th>
		<th >Outstanding Expenses</th>
		<th >Salaries Paid</th>
		<th >Income</th>
		<th >Income Received</th>
		<th >Deposit in Bank</th>
		<th >Withdrawal From Bank</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>