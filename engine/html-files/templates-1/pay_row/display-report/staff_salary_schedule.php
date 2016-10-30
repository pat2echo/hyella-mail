<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total net pay"] = 0;
	$total["total staff income"] = 0;
	$total["total staff deduction"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	if( ! empty( $report_data ) ){
		
		$emp = get_all_employees_info();
		$banks = get_bank_names();
		$serial = 0;
		
		foreach( $report_data as $sval ){
			$sval = __pay_row__calculate_pay( $sval );
			
			$body .= '<tr>';
				
				$body .= '<td class="company">'. ++$serial .'</td>';
				$body .= '<td class="company">' . ( isset( $emp[ $sval["staff_name"] ] )?( $emp[ $sval["staff_name"] ]["lastname"] . " " .$emp[ $sval["staff_name"] ]["firstname"] ):"N/A" )  . '</td>';
				
				$body .= '<td>' . number_format( $sval["basic_salary"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["housing"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["transport"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["utility"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["lunch"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["medical_allowance"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["overtime"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["bonus"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["other_allowance"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["leave_allowance"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["gross_pay"], 2 )  . '</td>';
				
				$body .= '<td>' . number_format( $sval["paye_deduction"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["pension"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["salary_advance"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["other_deduction"], 2 )  . '</td>';
				$body .= '<td>' . number_format( $sval["absent_deduction"], 2 )  . '</td>';
				
				$body .= '<td>' . number_format( $sval["net_deduction"], 2 )  . '</td>';
				$body .= '<td class="company">' . number_format( $sval["gross_pay"] - $sval["absent_deduction"], 2 )  . '</td>';
				
			$body .= '</tr>';
			
		}
		
		//foreach( $total as & $v )$v = number_format( $v, 2 );
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
	
	<tr><td colspan="20" ><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
	
	<tr>
		<th class="company">S/N</th>
		<th>Staff</th>
		<th>Basic Salary</th>
		<th>Housing</th>
		<th>Transport</th>
		<th>Utility</th>
		<th>Lunch</th>
		<th>Medical Allowance</th>
		<th>Overtime</th>
		<th>Bonus</th>
		<th>Other Allowance</th>
		<th>Leave Allowance</th>
		<th>Gross Pay</th>
		<th>Paye Deduction</th>
		<th>Pension</th>
		<th>Salary Advance</th>
		<th>Other Deduction</th>
		<th>Absent Deduction</th>
		<th>Net Deduction</th>
		<th>Net Pay</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>