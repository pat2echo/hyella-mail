<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total amount due"] = 0;
	$total["total salary"] = 0;
	$total["total deductions"] = 0;
	
	$total_row["total basic salary"] = 0;
	$total_row["total bonus"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	if( ! empty( $report_data ) ){
		
		foreach( $report_data as $sval ){
			$body .= '<tr>';
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td>'.get_select_option_value( array( "id" => $sval["staff_name"], "function_name" => "get_employees" ) ). '</td>';
				
				$body .= '<td>' . number_format( $sval["basic_salary"], 2 ) . '</td>';
				$body .= '<td>' . number_format( $sval["other_allowance"], 2 ) . '</td>';
				$body .= '<td>' . number_format( $sval["amount_debited"], 2 ) . '</td>';
				
				$am = $sval["basic_salary"] + $sval["other_allowance"] - $sval["amount_debited"];
				$body .= '<td>' . number_format( $am , 2 ) . '</td>';
				
				$total_row["total basic salary"] += ( $sval["basic_salary"] );
				$total_row["total bonus"] += ( $sval["other_allowance"] );
				
				$total["total salary"] += ( $sval["basic_salary"] + $sval["other_allowance"] );
				$total["total deductions"] += ( $sval["amount_debited"] );
				$total["total amount due"] += $am;
				
				$body .= '<td>'.$sval["comment"]. '</td>';
				
			$body .= '</tr>';
			
		}
		
		$total_row["total basic salary"] = number_format( $total_row["total basic salary"] , 2 ) ;
		$total_row["total bonus"] = number_format( $total_row["total bonus"], 2 );
		$total["total deductions"] = number_format( $total["total deductions"], 2 );
		$total["total amount due"] = number_format( $total["total amount due"] , 2 );
		$total["total salary"] = number_format( $total["total salary"], 2 );
		
		$body .= '<tr class="total-row">';
			$body .= '<td class="company">TOTAL</td>';
			$body .= '<td>-</td>';
			$body .= '<td>'. $total_row["total basic salary"] . '</td>';
			$body .= '<td>' . $total_row["total bonus"] . '</td>';
			$body .= '<td>' . $total["total deductions"] . '</td>';
			$body .= '<td>' . $total["total amount due"] . '</td>';
			$body .= '<td>-</td>';
		$body .= '</tr>';
		
	
	}
	
	?>
	<tr><td colspan="3" rowspan="<?php echo count( $total ) + 1; ?>"><h4><?php echo $title; ?></h4></td><td colspan="4" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="2"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="2" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th  class="company">Date</th>
		<th >Staff Name</th>
		<th >Basic Salary</th>
		<th >Bonus</th>
		<th >Deduction</th>
		<th >Amount Paid</th>
		<th >Comment</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>