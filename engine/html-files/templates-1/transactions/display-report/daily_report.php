<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total expense"] = 0;
	$total["total amount paid"] = 0;
	$total["total amount owed"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	if( ! empty( $report_data ) ){
		
		foreach( $report_data as $sval ){
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td>'.get_select_option_value( array( "id" => $sval["vendor"], "function_name" => "get_vendors" ) ). '</td>';
				$body .= '<td>'.$sval["description"]. '</td>';
				$body .= '<td>'.number_format( $sval["quantity"] , 0 ). '</td>';
				
				$body .= '<td>' . number_format( $sval["amount_due"], 2 ) . '</td>';
				$body .= '<td>' . number_format( $sval["amount_paid"], 2 ) . '</td>';
				
				$out = $sval["amount_due"] - $sval["amount_paid"];
				$body .= '<td>' . number_format( $out , 2 ) . '</td>';
				
				$total["total expense"] += ( $sval["amount_due"] );
				$total["total amount paid"] += ( $sval["amount_paid"] );
				$total["total amount owed"] += $out;
				
				
				$body .= '<td>'.get_select_option_value( array( "id" => $sval["category_of_expense"], "function_name" => "get_types_of_expenditure" ) ). '</td>';
				$body .= '<td>'.get_select_option_value( array( "id" => $sval["staff_in_charge"], "function_name" => "get_employees" ) ). '</td>';
				
			$body .= '</tr>';
			
		}
		
		$total["total expense"] = number_format( $total["total expense"], 2 );
		$total["total amount paid"] = number_format( $total["total amount paid"] , 2 );
		$total["total amount owed"] = number_format( $total["total amount owed"], 2 );
		
		$body .= '<tr class="total-row">';
			$body .= '<td class="company">TOTAL</td>';
			$body .= '<td>-</td>';
			$body .= '<td>-</td>';
			$body .= '<td>-</td>';
			$body .= '<td>'. $total["total expense"] . '</td>';
			$body .= '<td>' . $total["total amount paid"] . '</td>';
			$body .= '<td>' . $total["total amount owed"] . '</td>';
			$body .= '<td>-</td>';
			$body .= '<td>-</td>';
		$body .= '</tr>';
		
	
	}
	
	?>
	<tr><td colspan="3" rowspan="<?php echo count( $total ) + 1; ?>"><h4><?php echo $title; ?></h4></td><td colspan="6" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="3"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="3" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th  class="company">Date</th>
		<th >Vendor</th>
		<th >Description</th>
		<th >Quantity</th>
		<th >Amount Due</th>
		<th >Amount Paid</th>
		<th >Outstanding</th>
		<th >Category</th>
		<th >Staff in Charge</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>