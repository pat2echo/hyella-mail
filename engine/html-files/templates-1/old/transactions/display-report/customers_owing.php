<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	$real_body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$stores = get_stores();
	$cus = get_customers();
	
	if( ! isset( $summary ) )
		$summary = 0;
	
	$span = 4;
	
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
				
				if( ! isset( $r[ $store ][ $sval["reference"] ][ "debit" ] ) ){
					$r[ $store ][ $sval["reference"] ][ "credit" ] = 0;
					$r[ $store ][ $sval["reference"] ][ "debit" ] = 0;
					
					$r[ $store ][ $sval["reference"] ][ "title" ] = $sval['description'];
					$r[ $store ][ $sval["reference"] ][ "date" ] = $sval["date"];
				}
				
				$r[ $store ][ $sval["reference"] ][ $sval["type"] ] += $sval["amount"];
				
			break;
			}
			
		}
		
		$grand_total = 0;
		$grand_totalc = 0;
		foreach( $r as $key => $rv ){
			
			$body = '<tr class="total-row"><td>Date</td><td>'. ucwords( $key ) .'</td><td>Bill</td><td>Payment</td></tr>';
			$total = 0;
			$totalc = 0;
			
			foreach( $rv as $kv => $vvv ){
				$body .= '<tr><td>'. date("d-M-Y", doubleval( $vvv["date"] ) ) .'</td><td class="item-desc">'. ucwords( $vvv["title"] ) .'</td><td class="item-amount">'. format_and_convert_numbers( $vvv["debit"] , 4 ) .'</td><td class="item-amount">'. format_and_convert_numbers( $vvv["credit"] , 4 ) .'</td></tr>';
				
				$total += $vvv["debit"];
				$totalc += $vvv["credit"];
			}
			
			if( $total == $totalc )continue;
			
			$body .= '<tr class="total-row"><td class="item-desc" colspan="'. ( $span - 2 ) .'"><strong>Total '. ucwords( $key ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $total, 4 ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $totalc , 4 ) .'</strong></td></tr>';
			
			$dbal = 0;
			$bal = $total - $totalc;
			$label = ucwords( $key ).' BALANCE';
			
			if( $bal > 0 ){
				$dbal = $bal;
				$bal = 0;
			}
			
			$body .= '<tr class="total-row"><td class="item-desc" colspan="'. ( $span - 2 ) .'"><strong>'. $label .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $dbal , 4 ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( abs( $bal ) , 4 ) .'</strong></td></tr>';
			$body .= '<tr><td colspan="'. $span .'">&nbsp;</td></tr>';
			
			$real_body .= $body;
			
			$grand_total += $total;
			$grand_totalc += $totalc;
		}
		
		if( $grand_total ){
			$real_body .= '<tr class="total-row"><td class="item-desc" colspan="'. ( $span - 2 ) .'"><strong>GRAND TOTAL</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $grand_total, 4 ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $grand_totalc, 4 ) .'</strong></td></tr>';
			
			$dbal = 0;
			$bal = $grand_total - $grand_totalc;
			$label = 'CREDIT BALANCE';
			
			if( $bal > 0 ){
				$label = 'DEBIT BALANCE';
				$dbal = $bal;
				$bal = 0;
			}
			
			$real_body .= '<tr class="total-row"><td class="item-desc" colspan="'. ( $span - 2 ) .'"><strong>'. $label .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $dbal , 4 ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( abs( $bal ) , 4 ) .'</strong></td></tr>';
		}
		
	}
	
	?>
	
	<tr><td colspan="<?php echo $span; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>
</thead>
<tbody>
<?php echo $real_body; ?>
</tbody>
</table>
</div>