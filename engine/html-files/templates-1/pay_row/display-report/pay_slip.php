<div class="report-table-preview">
<?php
	$body = "";
	
	$pr = get_project_data();
	
	$total["total net pay"] = 0;
	$total["total staff income"] = 0;
	$total["total staff deduction"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$store = get_store_details( array( "id" => "10173870046" ) );
	$store_name = "";
	$support_line = "";
	$support_addr = "";
	$support_email = "";
	$support_msg = "";
		
	if( isset( $store["phone"] ) ){
		$store_name = $store["name"];
		$support_line = $store["phone"];
		$support_addr = $store["address"];
		$support_email = $store["email"];
		$support_msg = $store["comment"];
	}
	
	$total_serial = 0;
	if( ! empty( $report_data ) ){
		
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
		
		$total_serial = count( $report_data );
		
		foreach( $report_data as $sval ){
			
			?>
			<table class="table table-striped table-bordered table-hover" cellspacing="0" align="center" width="100%;">
			<thead>
			<?php
			$body = '<tr>';
				
				$body .= '<td class="company">'. ++$serial .'</td>';
				
				$subtitle = date( ( ($date_filter)?$date_filter:"F, Y" ), doubleval( $sval["date"] ) );
				
				$name = ( isset( $emp[ $sval["staff_name"] ] )?( $emp[ $sval["staff_name"] ]["lastname"] . " " .$emp[ $sval["staff_name"] ]["firstname"] ):"N/A" );
				
				$account_number = ( isset( $emp[ $sval["staff_name"] ] )?( $emp[ $sval["staff_name"] ]['account_number'] ):"" ) . " - " . ( isset( $banks[ $emp[ $sval["staff_name"] ]["bank_name"] ] )?$banks[ $emp[ $sval["staff_name"] ]["bank_name"] ]:"" );
				
				
				$body .= '<td style="font-size:10.5px; line-height:1.7;">Total No. of Days in Month: ' . "<strong style='text-align:right; float:right;'>".$sval['total_days']."</strong><br />" ;
				$body .= 'No. of Overtime Days: ' . "<strong style='text-align:right; float:right;'>".$sval['overtime_days']."</strong><br />";
				$body .= 'No. of Absent Days: ' . "<strong style='text-align:right; float:right;'>".$sval['absent_days']."</strong><br />";
				$body .= '</td>';
				
				//+ $sval["overtime"] 
				$income = $sval["basic_salary"] + $sval["housing"] + $sval["transport"] + $sval["utility"] + $sval["lunch"] + $sval["medical_allowance"];
				$gincome = $income;
				
				$deduction = $sval["paye_deduction"] + $sval["pension"] + $sval["salary_advance"] + $sval["other_deduction"];
				//+ $sval["absent_deduction"] 
				
				$body .= '<td style="font-size:10.5px; line-height:1.7;">';
				foreach( $in as $ik => $iv ){
					switch( $ik ){
					case "overtime":
						$sval[ $ik  ] = 0;
						if( $sval['total_days'] )$sval[ $ik  ] = ( $income / $sval['total_days'] ) * $sval['overtime_days'];
						
						$income += $sval[ $ik  ];
					break;
					case "bonus":
					case "other_allowance":
					
					break;
					}
					$body .= "<span style='width:60%; float:left;'>" . $iv . ":</span> <strong style='width:38%; text-align:right; float:right;'>" . number_format( $sval[ $ik ] , 2 ) . '</strong><br />';
				}
				$income += $sval["bonus"] + $sval["other_allowance"] + $sval["leave_allowance"];
				
				$body .= '</td>';
				
				$body .= '<td style="font-size:10.5px; line-height:1.7;">';
				foreach( $de as $ik => $iv ){
					switch( $ik ){
					case "absent_deduction":
						$sval[ $ik  ] = 0;
						if( $sval['total_days'] )$sval[ $ik  ] = ( $gincome / $sval['total_days'] ) * $sval['absent_days'];
						
						$deduction += $sval[ $ik  ];
					break;
					}
					
					$body .= "<span style='width:60%; float:left;'>" . $iv . ":</span> <strong style='width:38%; text-align:right; float:right;'>" . number_format( $sval[ $ik ] , 2 ) . '</strong><br />';
				}
					
				$body .= '</td>';
				
				$body .= '<td class="company alternate">' . number_format( $income - $deduction , 2 ) . '</td>';
				
				$total["total net pay"] = ( $income - $deduction );
				$total["total staff income"] = ( $income );
				$total["total staff deduction"] = $deduction;
				
			$body .= '</tr>';
			$body .= '<tr class="total-row">';
			$body .= '<td></td><td></td><td align="right">'.number_format( $income, 2 ).'</td><td align="right">'.number_format( $deduction, 2 ).'</td><td>'.number_format( $income - $deduction, 2 ).'</td>';
			$body .= '</tr>';
			?>
			<tr><td colspan="2" style="width:200px;"><h4><strong id="e-report-title"><?php echo '<img src="'.$pr["domain_name"].'files/resource_library/logo.jpg" width="72" align="left" />' . "<br />" . $store_name . "<small style='line-height:1.5; font-size:12px;'><br />".$support_addr."</small><br />"; ?></strong></h4></td><td colspan="3"><img src="<?php echo $pr["domain_name"] . $emp[ $sval["staff_name"] ]['photograph']; ?>" width="72" align="left" style="margin-right:10px;" /><h4><strong><?php echo $name; ?> - Pay Slip</strong></h4><p><?php echo $subtitle; ?></p></td></tr>	
			
			<tr ><td colspan="5" class="landscape-only"><h5>SUMMARY</h5></td></tr>
			<tr ><td colspan="2"><strong>Account Number</strong></td><td colspan="3" ><strong><?php echo $account_number; ?></strong></td></tr>
			<?php
			foreach( $total as $key => $val ){
				?>
				<tr ><td colspan="2"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="3" ><strong><?php echo number_format( $val , 2 ); ?></strong></td></tr>
				<?php
			}
			?>
			
			<tr>
				<th class="company">S/N</th>
				<th>Work Details</th>
				<th>Earnings (=N=)</th>
				<th>Deductions (=N=)</th>
				<th class="company alternate">Net Pay (=N=)</th>
			</tr>
		</thead>
		<tbody>
		<?php echo $body; ?>
		</tbody>
		</table>
		<hr />
			<?php
			if( ! ( $serial % 2 ) && ( $total_serial > $serial ) ){
				?> <div style="page-break-before:always;"></div> <?php
			}
		}
		
		//foreach( $total as & $v )$v = number_format( $v, 2 );
		
	}
	?>
</div>