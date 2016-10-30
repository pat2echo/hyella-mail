<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	$total["total income"] = 0;
	$total["total income from hall rental"] = 0;
	$total["total income from other items rental"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$other_items = get_other_requirements();
	
	if( ! empty( $report_data ) ){
		
		foreach( $report_data as $sval ){
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["start_date"] ) ). '</td>';
				
				if( ! isset( $sval["TOTAL_HALL_PRICE"] ) )$sval["TOTAL_HALL_PRICE"] = 0;
				if( ! isset( $sval["hall_price"] ) )$sval["hall_price"] = 0;
				
				$sval["hall_price"] = $sval["TOTAL_HALL_PRICE"];
				
				if( ! isset( $sval["OTHER_ITEMS_PRICE"] ) )$sval["OTHER_ITEMS_PRICE"] = 0;
				
				if( ! isset( $sval["INCOME"] ) )$sval["INCOME"] = 0;
				
				if( ! isset( $sval["hall"] ) )$sval["hall"] = "-";
				
				$sval["other_items"] = $sval["OTHER_ITEMS_PRICE"];
				
				if( $sval["hall"] != "*Several" )
					$sval["hall"] = get_select_option_value( array( "id" => $sval["hall"], "function_name" => "get_halls" ) );
				
				if( ! isset( $sval["hall_description"] ) )$sval["hall_description"] = "-";
				
				if( isset( $sval["other_requirements"] ) && $sval["other_requirements"] ){
					$xs = explode(":::", $sval["other_requirements"] );
					foreach( $xs as $xss ){
						if( isset( $other_items[ $xss ] ) ){
							$vi = $other_items[ $xss ];
							
							if( $sval["hall_description"] )$sval["hall_description"] .= ", " . $vi;
							else $sval["hall_description"] .= $vi;
						}
					}
				}
				
				$body .= '<td>' . $sval["hall"] . '</td>';
				$body .= '<td>' . $sval["hall_description"] . '</td>';
				$body .= '<td>' . ( ( $sval["hall_price"] ) ? number_format( $sval["hall_price"], 2 ) : "-" ) . '</td>';
				$body .= '<td>' . ( ( $sval["other_items"] )? number_format( $sval["other_items"], 2 ) :"-" ) . '</td>';
				
				$sval['INCOME'] = $sval["hall_price"] + $sval["other_items"];
				$body .= '<td>' . ( ( $sval['INCOME'] )?number_format( $sval['INCOME'], 2 ):"-" ) . '</td>';
				
				$total["total income from hall rental"] += ( $sval["hall_price"] );
				$total["total income from other items rental"] += ( $sval["other_items"] );
				$total["total income"] += ( $sval["INCOME"] );
				
			$body .= '</tr>';
			
		}
		
		$total["total income from other items rental"] = number_format( $total["total income from other items rental"], 2 );
		$total["total income from hall rental"] = number_format( $total["total income from hall rental"], 2 );
		$total["total income"] = number_format( $total["total income"], 2 );
		
		$body .= '<tr class="total-row">';
			$body .= '<td class="company">TOTAL</td>';
			$body .= '<td></td>';
			$body .= '<td></td>';
			$body .= '<td>' . $total["total income from hall rental"]  . '</td>';
			$body .= '<td>' . $total["total income from other items rental"]  . '</td>';
			
			$body .= '<td>' . $total["total income"]  . '</td>';
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
		<th >Source of Income</th>
		<th >Description</th>
		<th >Income from Hall Rental</th>
		<th >Other Items Rental</th>
		<th >Total Income</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>