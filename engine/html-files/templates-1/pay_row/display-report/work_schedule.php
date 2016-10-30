<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	if( ! empty( $report_data ) ){
		
		$emp = get_all_employees_info();
		$banks = get_bank_names();
		$serial = 0;
		
		foreach( $report_data as $sval ){
			$body .= '<tr>';
				
				$body .= '<td class="company">'. ++$serial .'</td>';
				$body .= '<td>' . ( isset( $emp[ $sval["staff_name"] ] )?( $emp[ $sval["staff_name"] ]['ref_no'] ):"" ) . '</td>';
				$body .= '<td class="company">' . ( isset( $emp[ $sval["staff_name"] ] )?( $emp[ $sval["staff_name"] ]["lastname"] . " " .$emp[ $sval["staff_name"] ]["firstname"] ):"N/A" )  . '</td>';
				
				$body .= '<td>' .  ( $sval['total_days'] - $sval['absent_days'] ) . '</td>';
				$body .= '<td>' . $sval['overtime_days'] . '</td>';
				$body .= '<td>' . $sval['absent_days']  . '</td>';
				$body .= '<td>' . ( $sval['total_days'] - $sval['absent_days'] + $sval['overtime_days'] ) . '</td>';
				
			$body .= '</tr>';
			
		}
		
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
	
	<tr><td colspan="7" ><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>
	<tr>
		<th class="company">S/N</th>
		<th >Code</th>
		<th>Staff</th>
		<th>No. of Days Worked</th>
		<th>No. of Days Overtime</th>
		<th>No. of Days Absent</th>
		<th>TOTAL DAYS</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>