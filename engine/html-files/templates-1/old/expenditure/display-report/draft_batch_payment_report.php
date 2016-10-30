<div class="report-table-preview">
<!--<form class="activate-ajax hidden-print" method="post" id="expenditure" action="?action=expenditure&todo=record_expense_batch_payment">-->
<table class="table table-striped table-bordered table-hover" cellspacing="0" id="recent-expenses">
<thead>
<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$stores = get_stores();
	$vendor = get_vendors();
	$cat = get_types_of_expenditure();
	$payment_method = get_payment_method();
	
	if( ! isset( $summary ) )
		$summary = 0;
	
	$span = 4;
	
	if( ! empty( $report_data ) ){
		$serial = 0;
		
		foreach( $report_data as $key => $sval ){
			$body .= '<tr>';
				$status = $sval["status"];
				
				$body .= '<td class="company"><strong>'. date( "d-M-Y", doubleval( $sval["date"] ) ) . '</strong></td>';
				$body .= '<td>' . $sval["description"] . '<br /><strong>#' . $sval["serial_num"] . '-' . $sval["id"] . '</strong></td>';
				$body .= '<td>'. ( isset( $cat[ $sval['category_of_expense'] ] )?$cat[ $sval['category_of_expense'] ]:$sval['category_of_expense'] ) . '</td>';
				$body .= '<td>'. ( isset( $vendor[ $sval["vendor"] ] )?$vendor[ $sval["vendor"] ]:$sval["vendor"] ) . '</td>';
				
				
				$body .= '<td>' . number_format( $sval["amount_due"], 2 ) . '</td>';
				$owed = $sval["amount_due"] - $sval["amount_paid"];
				
				$body .= '<td>' . number_format( $sval["amount_paid"] , 2 ) . '</td>';
				$body .= '<td>' . number_format( $owed , 2 ) . '</td>';
				
				$body .= '<td>'. ( isset( $payment_method[ $sval["mode_of_payment"] ] )?$payment_method[ $sval["mode_of_payment"] ]:$sval["mode_of_payment"] ) . '</td>';
				
				if( $owed ){
					$body .= '<td><span class="label label-danger">owing</span></td>';
				}else{
					$body .= '<td><span class="label label-info">paid</span></td>';
				}
				
			$body .= '</tr>';
		}
	}
	?>
	<tr>
		<td colspan="9">
			<h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p>
		</td>
	</tr>	
	
	<tr>
		<th>Date</th>
		<th>Desc.</th>
		<th>Category</th>
		<th>Vendor</th>
		<th>Amount Due</th>
		<th>Amount Paid</th>
		<th>Amount Owed</th>
		<th>Payment Method</th>
		<th>Status</th>
	</tr>
	
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
<!--</form>-->
</div>