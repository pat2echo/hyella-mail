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
		//Array ( [0] => Array ( [id] => 10817932715 [serial_num] => 1 [date] => 1454194800 [staff_name] => 7367611442 [salary_schedule] => none [basic_salary] => 30000 [bonus] => 5000 [housing] => 2000 [transport] => 1500 [utility] => 2000 [lunch] => 7000 [overtime] => 3000 [other_allowance] => 0 [paye_deduction] => 2000 [pension] => 200 [salary_advance] => 0 [absent_deduction] => 500 [other_deduction] => 0 [total_salary] => [comment] => January 2016 Salary ) ) 
		
		$emp = get_all_employees_info();
		$banks = get_bank_names();
		$serial = 0;
		
		$in = array(
			"basic_salary" => "Basic Salary",
			"housing" => "Housing",
			"transport" => "Transport",
			"medical_allowance" => "Medical Allowance",
			"utility" => "Utility",
			"lunch" => "Lunch",
			"overtime" => "Overtime",
			"leave_allowance" => "Leave Allowance",
			"other_allowance" => "Other Allowance",
			"bonus" => "Bonus",
		);
		
		$de = array(
			"paye_deduction" => "Paye Deduction",
			"pension" => "Pension",
			"salary_advance" => "Salary Advance",
			"absent_deduction" => "Absent Deduction",
			"other_deduction" => "Other Deduction",
		);
		
		foreach( $report_data as $sval ){
			$body .= '<tr>';
				
				$body .= '<td class="company">'. ++$serial .'</td>';
				$body .= '<td >'. date( ( ($date_filter)?$date_filter:"M-Y" ), doubleval( $sval["date"] ) ) .'</td>';
				$body .= '<td class="company">' . ( isset( $emp[ $sval["staff_name"] ] )?( $emp[ $sval["staff_name"] ]["lastname"] . " " .$emp[ $sval["staff_name"] ]["firstname"] ):"N/A" )  . '</td>';
				$body .= '<td>' . ( isset( $emp[ $sval["staff_name"] ] )?( $emp[ $sval["staff_name"] ]['account_number'] ):"" ) . '<br /><small>' .( isset( $banks[ $emp[ $sval["staff_name"] ]["bank_name"] ] )?$banks[ $emp[ $sval["staff_name"] ]["bank_name"] ]:"" ) . '</small></td>';
				
				$body .= '<td style="font-size:10.5px; line-height:1.7;">Total No. of Days in Month: ' . "<strong style='text-align:right; float:right;'>".$sval['total_days']."</strong><br />" ;
				$body .= 'No. of Overtime Days: ' . "<strong style='text-align:right; float:right;'>".$sval['overtime_days']."</strong><br />";
				$body .= 'No. of Absent Days: ' . "<strong style='text-align:right; float:right;'>".$sval['absent_days']."</strong><br />";
				$body .= '</td>';
				
				//+ $sval["overtime"] 
				$deduction = 0;
				$income = 0;
				
				$sval = __pay_row__calculate_pay( $sval );
				if( isset( $sval["gross_pay"] ) )$income = $sval["gross_pay"];
				if( isset( $sval["net_deduction"] ) )$deduction = $sval["net_deduction"];
				
				$body .= '<td style="font-size:10.5px; line-height:1.7;">';
				foreach( $in as $ik => $iv ){
					$body .= $iv . ": <strong style='text-align:right; float:right;'>" . number_format( $sval[ $ik ] , 2 ) . '</strong><br />';
				}
				
				$body .= '</td>';
				$body .= '<td >' . number_format( $income , 2 ) . '</td>';
				
				$body .= '<td style="font-size:10.5px; line-height:1.7;">';
				foreach( $de as $ik => $iv ){
					$body .= $iv . ": <strong style='text-align:right; float:right;'>" . number_format( $sval[ $ik ] , 2 ) . '</strong><br />';
				}
					
				$body .= '</td>';
				$body .= '<td >' . number_format( $deduction , 2 ) . '</td>';
				
				$body .= '<td class="company alternate">' . number_format( $income - $deduction , 2 ) . '</td>';
				
				$total["total net pay"] += ( $income - $deduction );
				$total["total staff income"] += ( $income );
				$total["total staff deduction"] += $deduction;
				
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
	
	<tr><td colspan="4" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="6" class="landscape-only"><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr ><td colspan="3"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="3" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
	<tr>
		<th rowspan="2" class="company">S/N</th>
		<th rowspan="2" >Date</th>
		<th colspan="3">Staff</th>
		<th colspan="4">Income & Deduction</th>
		<th rowspan="2" class="company alternate">Net Pay (=N=)</th>
	</tr>
	<tr>
		<th class="company">Name</th>
		<th>Account Number</th>
		<th>Work Details</th>
		
		<th>Income Breakdown (=N=)</th>
		<th>Income (=N=)</th>
		
		<th>Deductions Breakdown (=N=)</th>
		<th>Deductions (=N=)</th>
		
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>