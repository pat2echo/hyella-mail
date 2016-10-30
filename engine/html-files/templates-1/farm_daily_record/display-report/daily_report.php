<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover">
<thead>
	<tr><td colspan="12"><h4><?php echo $title; ?></h4></td></tr>
	<tr>
		<th rowspan="2" class="company">Date</th>
		<th rowspan="2">Pen</th>
		<th rowspan="2">Feed Intake (Kg)</th>
		<th rowspan="2">Qty of Water (L)</th>
		<th colspan="3">Egg Production</th>
		<th rowspan="2">Mortality</th>
		<th rowspan="2">Total Birds</th>
		<th rowspan="2">Drugs / Vaccine Administered</th>
		<th rowspan="2">Staff on Duty</th>
		<th rowspan="2">Comment</th>
	</tr>
	<tr>
		<th>No. of Eggs</th>
		<th>% Production</th>
		<th>Damages</th>
	</tr>
</thead>
<tbody>
<?php
	$body = "";
	foreach( $report_data as $sval ){
		$body .= '<tr>';
			$body .= '<td class="company"><?php echo date( "d-M-Y", doubleval( $sval["date"] ) ); ?></td>';
			$body .= '<td><?php echo get_select_option_value( array( "id" => $sval["pen"], "function_name" => "get_pen_types" ) ); ?></td>';
			$body .= '<td><?php echo $sval["FEED"]; ?></td>';
			$body .= '<td><?php echo $sval["quantity_of_water"]; ?></td>';
			$body .= '<td>' . $sval["PICK"] . '</td>';
			$body .= '<td><?php if( $sval["total_birds"] )echo ( ( $sval["PICK"] / $sval["total_birds"] ) * 100 ) . "%"; else echo "-"; ?></td>';
			$body .= '<td><?php echo $sval["DAMAGES"]; ?></td>';
			$body .= '<td><?php echo $sval["mortality"]; ?></td>';
			$body .= '<td><?php echo $sval["total_birds"]; ?></td>';
			$body .= '<td><?php echo get_select_option_value( array( "id" => $sval["drug_administered"], "function_name" => "get_drugs" ) ); ?></td>';
			$body .= '<td><?php echo get_select_option_value( array( "id" => $sval["staff_on_duty"], "function_name" => "get_employees" ) ); ?></td>';
			$body .= '<td><?php echo $sval["comment"]; ?></td>';
		</tr>
		<?php
	}
?>
</tbody>
</table>
</div>