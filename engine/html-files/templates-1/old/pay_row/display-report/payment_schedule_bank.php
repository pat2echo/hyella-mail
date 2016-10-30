<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total net pay"] = 0;
	//$total["total staff income"] = 0;
	//$total["total staff deduction"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	if( ! empty( $report_data ) ){
		//Array ( [0] => Array ( [id] => 10817932715 [serial_num] => 1 [date] => 1454194800 [staff_name] => 7367611442 [salary_schedule] => none [basic_salary] => 30000 [bonus] => 5000 [housing] => 2000 [transport] => 1500 [utility] => 2000 [lunch] => 7000 [overtime] => 3000 [other_allowance] => 0 [paye_deduction] => 2000 [pension] => 200 [salary_advance] => 0 [absent_deduction] => 500 [other_deduction] => 0 [total_salary] => [comment] => January 2016 Salary ) ) 
		
		$emp = get_all_employees_info();
		$banks = get_bank_names();
		$serial = 0;
		
		foreach( $report_data as $sval ){
			$body .= '<tr>';
				
				$body .= '<td class="company">'. ++$serial .'</td>';
				//$body .= '<td >'. date( ( ($date_filter)?$date_filter:"M-Y" ), doubleval( $sval["date"] ) ) .'</td>';
				$body .= '<td>' . ( isset( $emp[ $sval["staff_name"] ] )?( $emp[ $sval["staff_name"] ]["lastname"] . " " .$emp[ $sval["staff_name"] ]["firstname"] ):"N/A" )  . '</td>';
				$body .= '<td>' . ( isset( $emp[ $sval["staff_name"] ] )?( $emp[ $sval["staff_name"] ]['account_number'] ):"" ) . '</td>';
				
				//$body .= '<td>' . ( isset( $banks[ $emp[ $sval["staff_name"] ]["bank_name"] ] )?$banks[ $emp[ $sval["staff_name"] ]["bank_name"] ]:"" ) . '</td>';
				
				//+ $sval["overtime"]
				$income = $sval["basic_salary"] + $sval["housing"] + $sval["transport"] + $sval["utility"] + $sval["lunch"] + $sval["medical_allowance"];
				$gincome = $income;
				
				$sval[ "overtime"  ] = 0;
				if( $sval['total_days'] )$sval[ "overtime"  ] = ( $income / $sval['total_days'] ) * $sval['overtime_days'];
				$income += $sval[ "overtime"  ];
				
				$income += $sval["bonus"] + $sval["other_allowance"] + $sval["leave_allowance"];
				
				//+ $sval["absent_deduction"]
				$sval[ "absent_deduction"  ] = 0;
				$deduction = $sval["paye_deduction"] + $sval["pension"] + $sval["salary_advance"] + $sval["other_deduction"];
				if( $sval['total_days'] )$sval[ "absent_deduction"  ] = ( $gincome / $sval['total_days'] ) * $sval['absent_days'];
				$deduction += $sval[ "absent_deduction"  ];
				
				//$body .= '<td >' . number_format( $income , 2 ) . '</td>';
				//$body .= '<td >' . number_format( $deduction , 2 ) . '</td>';
				$body .= '<td class="company alternate">' . number_format( $income - $deduction , 2 ) . '</td>';
				
				$total["total net pay"] += ( $income - $deduction );
				//$total["total staff income"] += ( $income );
				//$total["total staff deduction"] += $deduction;
				
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
	
	<tr><td colspan="2" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="2" class="landscape-only"><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr ><td colspan="1"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="1" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
	<tr>
		<th rowspan="2" class="company">S/N</th>
		<th colspan="2">Staff</th>
		<!--<th colspan="2">Income & Deduction</th>-->
		<th rowspan="2" class="company alternate">Net Pay (=N=)</th>
	</tr>
	<tr>
		<th class="company">Name</th>
		<th>Account Number</th>
		<!--<th>Bank Name</th>-->
		
		<!--<th>Income</th>
		<th>Deductions</th>-->
		
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>