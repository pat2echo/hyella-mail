<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	$body1 = "";
	
	$total["total salary"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	if( ! empty( $report_data ) ){
		
		$r = array();
		$r1 = array();
		$salary_categories = get_salary_category();
		$pension = get_pension_manager();
		
		//print_r($report_data);
		foreach( $report_data as $sval ){
			$d = __pay_row__calculate_pay( $sval );
			if( ! isset( $r[ $sval['salary_schedule'] ][ $sval['salary_category'] ] ) ){
				$r[ $sval['salary_schedule'] ][ $sval['salary_category'] ] = 0;
			}
			if( $sval['pension_manager'] && ! isset( $r1[ $sval['salary_schedule'] ][ $sval['pension_manager'] ] ) ){
				$r1[ $sval['salary_schedule'] ][ $sval['pension_manager'] ] = 0;
			}
			
			if( ! isset( $r1[ $sval['salary_schedule'] ][ "paye" ] ) ){
				$r1[ $sval['salary_schedule'] ][ "paye" ] = 0;
			}
			
			$r[ $sval['salary_schedule'] ][ $sval['salary_category'] ] += $d["gross_pay"] - $d["net_deduction"];
			
			if( $sval['pension_manager'] )
				$r1[ $sval['salary_schedule'] ][ $sval['pension_manager'] ] += $d["pension"];
			
			$r1[ $sval['salary_schedule'] ][ "paye" ] += $d["paye_deduction"];
		}
		$serial = 0;
		
		$ssch = get_salary_schedule();
		foreach( $ssch as $k2 => $r2 ){
			$body = '<tr>';
				$body .= '<td colspan="4" align="center"><strong>'.strtoupper( $r2 ).'</strong></td>';
			$body .= '</tr>';
			
			$semi_total = 0;
			
			foreach( $salary_categories as $k => $val ){
				if( isset( $r[ $k2 ][ $k ] ) && $r[ $k2 ][ $k ] ){					
					$body .= '<tr>';
						$value = '';
						
							$value = number_format( $r[ $k2 ][ $k ] , 2 );
							$total["total salary"] += $r[ $k2 ][ $k ];
							$semi_total += $r[ $k2 ][ $k ];
						
						$body .= '<td class="company">'. ++$serial .'</td>';
						$body .= '<td>' . strtoupper( $val )  . '</td>';
						$body .= '<td align="right">' . $value . '</td>';
						$body .= '<td>' . '-' . '</td>';
						
					$body .= '</tr>';
				}
			}
			
			if( isset( $r1[ $k2 ] ) && ! empty( $r1[ $k2 ] ) ){
				
				foreach( $r1[ $k2 ] as $k1 => $v1 ){
					if( $v1 ){
						$body .= '<tr>';
							$val = strtoupper( $k1 );
							if( isset( $pension[ $k1 ] ) ){
								$val = strtoupper( $pension[ $k1 ] );
							}
							
							$value = '';
							
								$value = number_format( $v1 , 2 );
								$total["total salary"] += $v1;
								$semi_total += $v1;
							
							
							$body .= '<td class="company">'. ++$serial .'</td>';
							$body .= '<td>' . strtoupper( $val )  . '</td>';
							$body .= '<td align="right">' . $value . '</td>';
							$body .= '<td>' . '-' . '</td>';
							
						$body .= '</tr>';
					}
				}
			}
			
			if( $semi_total ){
				$body .= '<tr>';
					$body .= '<td class="company"></td>';
					$body .= '<td><strong>SUB-TOTAL ' . strtoupper( $r2 ) . '</strong></td>';
					$body .= '<td align="right">' . number_format( $semi_total, 2 ) . '</td>';
					$body .= '<td><strong>' . '-' . '</strong></td>';
				$body .= '</tr>';
				
				$body .= '<tr>';
					$body .= '<td colspan="4" align="center">&nbsp;</td>';
				$body .= '</tr>';
				$body1 .= $body;
			}
			
		}
		
		foreach( $total as & $v )$v = number_format( $v, 2 );
		
	}
	
	?>
	
	<tr><td colspan="4"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
	
	<tr>
		<th class="company">S/N</th>
		<th>Description</th>
		<th align="right">Amount</th>
		<th>-</th>
	</tr>
</thead>
<tbody>
<?php echo $body1; ?>
</tbody>
<tfoot>
<tr>
	<td colspan="4">&nbsp;</td>
</tr>
<tr>
	<td></td>
	<td><strong>TOTAL SALARY FOR THE MONTH</strong></td>
	<td align="right"><strong><?php echo $total["total salary"]; ?></strong></td>
	<td>-</td>
</tr>
</tfoot>
</table>
</div>