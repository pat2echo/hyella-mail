<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total deposits"] = 0;
	$total["total withdrawal"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$emp = get_employees();
	
	if( ! empty( $report_data ) ){
		
		foreach( $report_data as $sval ){
			
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				
				$body .= '<td align="right">' . ( ( doubleval( $sval["amount_deposit"] ) )?( number_format( $sval["amount_deposit"], 2 ) ):"-" ) . '</td>';
				$body .= '<td align="right">' . ( ( doubleval( $sval["amount_withdrawn"] ) )?( number_format( $sval["amount_withdrawn"], 2 ) ):"-" ) . '</td>';
				
				$body .= '<td>' . $sval["comment"] . '</td>';
				
				if( isset( $emp[ $sval["modified_by"] ] ) ){
					$body .= '<td>' . $emp[ $sval["modified_by"] ] . '</td>';
				}else{
					$body .= '<td>-</td>';
				}
				
				$total["total deposits"] += $sval["amount_deposit"];
				$total["total withdrawal"] += $sval["amount_withdrawn"];
				
			$body .= '</tr>';
			
		}
		
		$total["balance"] = $total["total deposits"] - $total["total withdrawal"];
		
		foreach( $total as & $v )$v = number_format( $v, 2 );
		
		if( $total["balance"] > 0 ){
			$total["balance"] = '<strong>' . $total["balance"] . '</strong>';
		}else{
			$total["balance"] = '<strong style="color:#b30000;">( ' . abs( $total["balance"] ) . ') </strong>';
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
	<tr><td colspan="2" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="6" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="2"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="4" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th class="company">Date</th>
		<th>Deposits</th>
		<th>Withdrawal</th>
		<th>Comment</th>
		<th>Staff Responsible</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>