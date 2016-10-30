<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total goods sold"] = 0;
	$total["total income"] = 0;
	$total["cash in bank"] = 0;
	$total["total amount owed"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$cus = get_customers();
	$state = get_sales_status();
	$pm = get_payment_method();
	$g_discount_after_tax = get_sales_discount_after_tax_settings();
	
	if( ! empty( $report_data ) ){
		
		//Array ( [Nov-2015] => Array ( [id] => 10467389855 [serial_num] => 10 [date] => 1446336061 [discount] => 0 [customer] => [store] => 10173870046 [sales_status] => sold [item] => cracked_eggs_crate [quantity_sold] => 1360 [amount_due] => 870000 ) ) 
		
		foreach( $report_data as $sval ){
			$continue = 0;
			switch( $sval["payment_method"] ){
			case "cash_refund":
			case "charge_to_room":
			case "complimentary":
				$continue = 1;
			break;
			}
			if( $continue )continue;
			
			if( $g_discount_after_tax ){
				$due = $sval["amount_due"];
			}else{
				$due = $sval["amount_due"] - $sval["discount"];
			}
			
			$vat = 0;
			$service_charge = 0;
			$service_tax = 0;
			if( isset( $sval["vat"] ) && doubleval( $sval["vat"] ) )
				$vat = round( $due * $sval["vat"] / 100, 2 );
			
			if( isset( $sval["service_charge"] ) && doubleval( $sval["service_charge"] ) )
				$service_charge = round( $due * $sval["service_charge"] / 100, 2 );
			
			if( isset( $sval["service_tax"] ) && doubleval( $sval["service_tax"] ) )
				$service_tax = round( $due * $sval["service_tax"] / 100, 2 );
			
			$income = $due + $service_charge + $vat + $service_tax;
			if( $g_discount_after_tax ){
				$income = $income - $sval["discount"];
			}
			if( $income <= $sval["amount_paid"] )continue;
			$paid = number_format( $sval["amount_paid"] , 2 );
			
			$due_text = number_format( $income - $sval["amount_paid"] , 2 );
			
			switch( $sval["payment_method"] ){
			case "charge_to_room":
				//$income = 0;
				$due_text = '<strong>'. $pm[ $sval["payment_method"] ] .'</strong>';
			break;
			case "complimentary":
				$income = 0;
				$due_text = '<strong>'. $pm[ $sval["payment_method"] ] .'</strong>';
			break;
			default:
			break;
			}
			
			$total["cash in bank"] += $sval["amount_paid"];
			$total["total goods sold"] += ( $sval["quantity_sold"] );
			$total["total income"] += $income;
			$total["total amount owed"] += ( $income - $sval["amount_paid"] );
				
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td><strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app1&hide=1" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'">#' . $sval["serial_num"] . '-' . $sval["id"] . '</a></strong></td>';
				
				$body .= '<td class="landscape-only hide-in-mobile">'. ( isset( $state[ $sval["sales_status"] ] )?$state[ $sval["sales_status"] ]:$sval["sales_status"] ) . '</td>';
				$body .= '<td class="landscape-only hide-in-mobile">'.number_format( $sval["quantity_sold"] , 0 ). '</td>';
				
				$body .= '<td class="landscape-only hide-in-mobile">' . number_format( $sval["amount_due"], 2 ) . '</td>';
				$body .= '<td class="landscape-only hide-in-mobile">' . number_format( $sval["discount"], 2 ) . '</td>';
				
				
				$body .= '<td class="company alternate">' . number_format( $income , 2 ) . '</td>';
				
				$body .= '<td class="landscape-only">' . $paid . '</td>';
				$body .= '<td class="company">' . $due_text . '</td>';
				
				$body .= '<td>' . ( isset( $cus[ $sval["customer"] ] )?$cus[ $sval["customer"] ]:$sval["customer"] ) . '</td>';
				
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
	<tr><td colspan="2" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="8" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="2"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="6" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th rowspan="2" class="company">Date</th>
		<th rowspan="2">Receipt Num</th>
		
		
		<th colspan="4" class="hide-in-mobile">Sales Info</th>
		<th class="company alternate" rowspan="2">Amount Due</th>
		
		<th rowspan="2" class="landscape-only">Amount Paid</th>
		<th rowspan="2" class="company">Amount Owed</th>
		
		<th rowspan="2">Customer</th>
	</tr>
	<tr>
		<th class="landscape-only hide-in-mobile">Status</th>
		<th class="landscape-only hide-in-mobile">Units of Goods Sold</th>
		<th class="landscape-only hide-in-mobile">Value of Goods Sold</th>
		<th class="landscape-only hide-in-mobile">Discount</th>
		
		
		
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>