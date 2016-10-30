<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0" >
<thead>
<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$stores = get_stores();
	$cus = get_customers();
	
	if( ! isset( $summary ) )
		$summary = 0;
	
	$span = 5;
	
	if( ! empty( $report_data ) ){
		//print_r( $report_data );
		
		//transform data
		$r = array();
		$r1 = array();
		
		$serial = 0;
		
		foreach( $report_data as $key => $sval ){
			
			$ckey = "";
			switch( $sval[ "account_type" ] ){
			case "accounts_receivable":
				//$store = ( isset( $stores[ $sval["store"] ] ) ? $stores[ $sval["store"] ] : $sval["store"] );
				$store = ( isset( $cus[ $sval["account"] ] ) ? $cus[ $sval["account"] ] : $sval["account"] );
				
				if( ! isset( $r[ $store ][ "debit" ] ) ){
					$r[ $store ][ "credit" ] = 0;
					$r[ $store ][ "debit" ] = 0;
				}
				
				$r[ $store ][ $sval["type"] ] += $sval["amount"];
				
			break;
			}
			
		}
		
		$grand_total = 0;
		$grand_totalc = 0;
		
		$grand_totald = 0;
		$grand_totalr = 0;
		foreach( $r as $key => $rv ){
			
			$total = 0;
			$totalc = 0;
			
			if( $rv["debit"] == $rv["credit"] )continue;
			
			$total += $rv["debit"];
			$totalc += $rv["credit"];
			
			$dbal = 0;
			$bal = $total - $totalc;
			$label = ucwords( $key );
			
			if( $bal > 0 ){
				$dbal = $bal;
				$bal = 0;
			}
			
			$body .= '<tr><td class="item-desc">'. $label .'</td><td class="item-amount">'. format_and_convert_numbers( $rv["debit"] , 4 ) .'</td><td class="item-amount">'. format_and_convert_numbers( $rv["credit"] , 4 ) .'</td>';
			
			$body .= '<td class="item-amount"><strong>'. format_and_convert_numbers( $dbal , 4 ) .'</strong></td><td class="item-amount">'. format_and_convert_numbers( abs( $bal ) , 4 ) .'</td></tr>';
			
			$grand_total += $total;
			$grand_totalc += $totalc;
			
			$grand_totalr = 0;
			$grand_totald = $grand_total - $grand_totalc;
			if( $grand_totald < 0 ){
				$grand_totalr = abs( $grand_totald );
				$grand_totald = 0;
			}
		}
		
		if( $grand_total ){
			$body .= '<tr class="total-row"><td class="item-desc"><strong>BALANCE</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $grand_total, 4 ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $grand_totalc, 4 ) .'</strong></td>';
			
			$body .= '<td class="item-amount"><strong>'. format_and_convert_numbers( $grand_totald, 4 ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $grand_totalr, 4 ) .'</strong></td></tr>';
			
		}
	}
	
	?>
	
	<tr><td colspan="<?php echo $span; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
	<tr>
		<th>Customers</th>
		<th>Bills</th>
		<th>Payments</th>
		<th>Debit</th>
		<th>Credit</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>