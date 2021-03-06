<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total goods sold"] = 0;
	$total["total income"] = 0;
	$total["total amount paid"] = 0;
	$total["total amount owed"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$cus = get_customers();
	$state = get_sales_status();
	$pm = get_payment_method();
	$pm["cash_refund"] = "Cash Refund";
	$g_discount_after_tax = get_sales_discount_after_tax_settings();
	
	$show_unpaid = 0;
	if( isset( $unpaid_transactions_only ) ){
		$show_unpaid = $unpaid_transactions_only;
	}
	
	if( ! empty( $report_data ) ){
		
		//Array ( [Nov-2015] => Array ( [id] => 10467389855 [serial_num] => 10 [date] => 1446336061 [discount] => 0 [customer] => [store] => 10173870046 [sales_status] => sold [item] => cracked_eggs_crate [quantity_sold] => 1360 [amount_due] => 870000 ) ) 
		
		foreach( $report_data as $sval ){
			
			if( $show_unpaid && $sval["amount_paid"] >= $sval["amount_due"] ){
				continue;
			}
			
			switch( $sval["discount_type"] ){
			case "fixed_value":
				$g_discount_after_tax = 0;
			break;
			case "percentage":	
				$g_discount_after_tax = 0;
				$sval["discount"] = $sval["discount"] * $sval["cost"] * 0.01;
			break;
			case "percentage_after_tax":
			case "fixed_value_after_tax":
				$g_discount_after_tax = 1;
			break;
			}
			
			if( $g_discount_after_tax ){
				$due = $sval["cost"];
			}else{
				$due = $sval["cost"] - $sval["discount"];
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
			
			switch( $sval["discount_type"] ){
			case "percentage_after_tax":
				$sval["discount"] = $sval["discount"] * $income * 0.01;
			break;
			}
			
			if( $g_discount_after_tax ){
				$income = $income - $sval["discount"];
			}
			
			if( $show_unpaid && $sval["amount_paid"] >= $income ){
				continue;
			}
			
			$real_income = $income;
			$paid = number_format( $sval["amount_paid"] , 2 );
			
			$due_text = number_format( $income - $sval["amount_paid"] , 2 );
			
			if( isset( $sval["payment_method"] ) ){
				switch( $sval["payment_method"] ){
				case "charge_to_room":
					$income = 0;
					$due_text = '<strong>'. $pm[ $sval["payment_method"] ] .'</strong>';
				break;
				case "cash_refund":
				case "complimentary_staff":
				case "complimentary":
					$income = 0;
					$real_income = 0;
					$due_text = '<strong>'. $pm[ $sval["payment_method"] ] .'</strong>';
				break;
				default:
				break;
				}
			}
			
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td><strong><a href="#" class="custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app1&hide=1" title="View Invoice / Receipt" override-selected-record="'.$sval["id"].'">#' . mask_serial_number( $sval["serial_num"], 'S' ) . '</a></strong><br />' . $sval["comment"] . '</td>';
				
				$body .= '<td class="hide-in-mobile">'. ( isset( $state[ $sval["sales_status"] ] )?$state[ $sval["sales_status"] ]:$sval["sales_status"] ) . '</td>';
				$body .= '<td class="hide-in-mobile">'.number_format( $sval["quantity_sold"] , 0 ). '</td>';
				
				$body .= '<td class="hide-in-mobile">' . number_format( $sval["amount_due"], 2 ) . '</td>';
				$body .= '<td class="hide-in-mobile">' . number_format( $sval["discount"], 2 ) . '</td>';
				$body .= '<td class="hide-in-mobile">' . number_format( $service_charge + $vat + $service_tax, 2 ) . '</td>';
				
				
				$body .= '<td class="company alternate">' . number_format( $real_income , 2 ) . '</td>';
				
				$body .= '<td class="landscape-only">' . $paid . '</td>';
				$body .= '<td class="company">' . $due_text . '</td>';
				
				$body .= '<td>' . ( isset( $cus[ $sval["customer"] ] )?$cus[ $sval["customer"] ]:$sval["customer"] ) . '</td>';
				
				$total["total amount paid"] += $sval["amount_paid"];
				
				$total["total goods sold"] += ( $sval["quantity_sold"] );
				$total["total income"] += $real_income;
				$total["total amount owed"] += ( $income - $sval["amount_paid"] );
				
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
	<tr><td colspan="3" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="8" ><h5>SUMMARY</h5></td></tr>	
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
		
		<th colspan="5" class="hide-in-mobile">Sales Info</th>
		<th rowspan="2" class="alternate">Amount Due</th>
		<th colspan="2">Payment Info</th>
		<th rowspan="2">Customer</th>
	</tr>
	<tr>
		<th class="hide-in-mobile">Status</th>
		<th class="hide-in-mobile">Units of Goods Sold</th>
		<th class="hide-in-mobile">Value of Goods Sold</th>
		<th class="hide-in-mobile">Discount</th>
		<th class="hide-in-mobile">Surcharge</th>
		
		<th>Amount Paid</th>
		<th class="company">Amount Owed</th>
		
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>