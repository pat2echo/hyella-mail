<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total expense incurred + salaries"] = 0;
	
	$total_row["total expense incurred"] = 0;
	$total_row["total expense paid"] = 0;
	$total["amount owed to vendors"] = 0;
	
	$total["total salaries"] = 0;
	
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
				
				if( ! isset( $sval["description"] ) )$sval["description"] = "-";
				
				$body .= '<td>' . $sval["description"] . '</td>';
				$body .= '<td>' . ( ( $sval["amount_due"] ) ? number_format( $sval["amount_due"], 2 ) : "-" ) . '</td>';
				$body .= '<td>' . ( ( $sval["amount_paid"] )? number_format( $sval["amount_paid"], 2 ) :"-" ) . '</td>';
				
				
				$out = $sval["amount_due"] - $sval["amount_paid"];
				$body .= '<td>' . ( ( $out ) ? number_format( $out , 2 ) : "-" ) . '</td>';
				
				$body .= '<td>' . ( ( $sval['SALARY_PAID'] )?number_format( $sval['SALARY_PAID'], 2 ):"-" ) . '</td>';
				
				$total["total salaries"] += ( $sval["SALARY_PAID"] );
				
				$total_row["total expense incurred"] += ( $sval["amount_due"] );
				$total_row["total expense paid"] += ( $sval["amount_paid"] );
				
				$total["amount owed to vendors"] += $out;
				
			$body .= '</tr>';
			
		}
		
		$total["total expense incurred + salaries"] = number_format( $total_row["total expense incurred"] + $total["total salaries"] , 2 );
		
		$total["total salaries"] = number_format( $total["total salaries"], 2 );
		
		$total_row["total expense incurred"] = number_format( $total_row["total expense incurred"], 2 );
		$total_row["total expense paid"] = number_format( $total_row["total expense paid"] , 2 );
		$total["amount owed to vendors"] = number_format( $total["amount owed to vendors"], 2 );
		
		$body .= '<tr class="total-row">';
			$body .= '<td class="company">TOTAL</td>';
			$body .= '<td></td>';
			$body .= '<td>'. $total_row["total expense incurred"] . '</td>';
			$body .= '<td>' . $total_row["total expense paid"] . '</td>';
			$body .= '<td>' . $total["amount owed to vendors"] . '</td>';
			$body .= '<td>' . $total["total salaries"] . '</td>';
		$body .= '</tr>';
		
	
	}
	
	?>
	<tr><td colspan="2" rowspan="<?php echo count( $total ) + 1; ?>"><h4><?php echo $title; ?></h4></td><td colspan="4" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="2"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="2" ><?php echo $val; ?></td></tr>
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
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>