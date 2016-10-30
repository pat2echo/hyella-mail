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
	
	$cus = get_items();
	$state = get_sales_status();
	
	if( ! empty( $report_data ) ){
		//transform data
		$r = array();
		$r1 = array();
		foreach( $report_data as $sval ){
			if( $sval["payment_method"] == "cash_refund" )continue;
			
			if( ! isset( $r[ $sval["item"] ] ) ){
				$r[ $sval["item"] ] = $sval;
				$r1[ $sval["item"] ] = ( $sval["amount_due"] - $sval["discount"] );
				
			}
			else{
				foreach( $sval as $k => $v ){
					switch( $k ){
					case "quantity_sold":
					case "amount_due":
					case "amount_paid":
					case "discount":
						$r[ $sval["item"] ][ $k ] += $v;
					break;
					}
				}
				$r1[ $sval["item"] ] += ( $sval["amount_due"] - $sval["discount"] );
			}
		}
		$report_data = $r;
		$serial = 0;
		arsort( $r1 );
		
		foreach( $r1 as $key => $val ){
			$sval = $report_data[ $key ];
			
			++$serial;
			
			$body .= '<tr>';
				
				$body .= '<td class="company">'.$serial. '</td>';
				$body .= '<td>'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td><strong>' . ( isset( $cus[ $sval["item"] ] )?$cus[ $sval["item"] ]:$sval["item"] ) . '</strong></td>';
				//$body .= '<td><strong>' . $sval["serial_num"] . "-" . $sval["id"] . '</td>';
				
				//$body .= '<td>'. ( isset( $state[ $sval["sales_status"] ] )?$state[ $sval["sales_status"] ]:$sval["sales_status"] ) . '</td>';
				$body .= '<td>'.number_format( $sval["quantity_sold"] , 0 ). '</td>';
				
				$body .= '<td class="company alternate">' . number_format( $sval["amount_due"], 2 ) . '</td>';
				
			$body .= '</tr>';
			
		}
		
	}
	
	?>
	<tr><td colspan="10"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
	
	<tr>
		<th rowspan="2" class="company">Rank</th>
		<th rowspan="2">Last Transaction Date</th>
		<th rowspan="2">Item</th>
		
		<th colspan="2">Transaction Info</th>
		
	</tr>
	<tr>
		<th >Units of Goods Sold</th>
		<th class="alternate">Value of Goods Sold</th>
		
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>