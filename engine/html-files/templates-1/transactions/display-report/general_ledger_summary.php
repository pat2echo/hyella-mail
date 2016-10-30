<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0" style="max-width:700px; min-width:700px; margin:auto;">
<thead>
<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$stores = get_stores();
	$cus = get_customers();
	$ven = get_vendors();
	$category = get_items_categories();
	$payment_methods = get_payment_method();
	
	$acc_type = get_first_level_accounts();
	
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
			$title1 = "";
			
			switch( $sval[ "account_type" ] ){
			case "current_liabilities":
			case "cash_book":
				$acc_details = get_chart_of_accounts_details( array( "id" => $sval["account"] ) );
				$title1 = $acc_details["title"];
				$sval["account_type"] = $title1;
			break;
			case "petty_cash": case "main_cash":
			case "bank6": case "bank7": case "bank8": case "bank9": case "bank10":
			case "bank5": case "bank4": case "bank3": case "bank2": case "bank1":
				$acc_details = get_chart_of_accounts_details( array( "id" => $sval["account_type"] ) );
				$title1 = $acc_details["title"];
				$sval["account_type"] = $title1;
				//$sval["account_type"] = "cash_book";
			break;
			default:
				$acc_details = get_chart_of_accounts_details( array( "id" => $sval["account_type"] ) );
				$title1 = $acc_details["title"];
			break;
			}
			
			if( ! $sval["reference"] )$sval["reference"] = $sval["id"];
			
			$store = $sval["account_type"];
			if( isset( $acc_type[ $sval["account_type"] ] ) )
				$store = $acc_type[ $sval["account_type"] ];
			
			$sval[ "debit" ] = 0;
			$sval[ "credit" ] = 0;
			$sval[ $sval["type"] ] = $sval["amount"];
			$r[ $store ][ $title1 ][] = $sval;
		}
		//print_r($r);
		unset( $report_data );
		
		
		foreach( $r as $key => $rv ){
			
			$body .= '<tr><td class="company" colspan="3">'. strtoupper( $key ) .'</td><td class="item-amount company">Debit</td><td class="item-amount company">Credit</td></tr>';
			
			$grand_total_d = 0;
			$grand_total_c = 0;
		
			foreach( $rv as $kv1 => $kvv ){
				$total_d = 0;
				$total_c = 0;
			
				//$body .= '<tr><td colspan="3">'. ucwords( $kv1 ) .'</td><td class="item-amount">Debit</td><td class="item-amount">Credit</td></tr>';
				
				foreach( $kvv as $kv => $vvv ){
					//$body .= '<tr><td>'. date("d-M-Y", doubleval( $vvv["date"] ) ) .'</td><td>#'. $vvv["reference"] .'</td><td class="item-desc">'. ucwords( $vvv["description"] ) .'</td><td class="item-amount">'. format_and_convert_numbers( $vvv["debit"] , 4 ) .'</td><td class="item-amount">'. format_and_convert_numbers( $vvv["credit"] , 4 ) .'</td></tr>';
					
					$total_d += $vvv["debit"];
					$total_c += $vvv["credit"];
				}
				
				$grand_total_d += $total_d;
				$grand_total_c += $total_c;
				
				//$body .= '<tr><td class="item-desc indent" colspan="'. ( $span - 2 ) .'"><strong>Sub-Total '. ucwords( $kv1 ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $total_d, 4 ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $total_c, 4 ) .'</strong></td></tr>';
				
				$balance = $total_d - $total_c;
				$total_d = "";
				$total_c = "";
				if( $balance > 0 ){
					$total_d = format_and_convert_numbers( $balance , 4 );
				}else{
					$total_c = format_and_convert_numbers( abs( $balance ) , 4 );
				}
				
				//$body .= '<tr class=""><td class="item-desc" colspan="'. ( $span - 2 ) .'"><strong>ENDING BALANCE</strong></td><td class="item-amount"><strong>'. $total_d .'</strong></td><td class="item-amount"><strong>'. $total_c .'</strong></td></tr>';
				//$body .= '<tr><td colspan="'. $span .'">&nbsp;</td></tr>';
				
			}
			
			$body .= '<tr><td class="item-desc indent" colspan="'. ( $span - 2 ) .'"><strong>Total '. ucwords( $key ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $grand_total_d, 4 ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $grand_total_c, 4 ) .'</strong></td></tr>';
			
			$balance = $grand_total_d - $grand_total_c;
			$grand_total_c = "";
			$grand_total_d = "";
			if( $balance > 0 ){
				$grand_total_d = format_and_convert_numbers( $balance , 4 );
			}else{
				$grand_total_c = format_and_convert_numbers( abs( $balance ) , 4 );
			}
				
			$body .= '<tr class="total-row"><td class="item-desc" colspan="'. ( $span - 2 ) .'"><strong>ENDING BALANCE</strong></td><td class="item-amount"><strong>'. $grand_total_d .'</strong></td><td class="item-amount"><strong>'. $grand_total_c .'</strong></td></tr>';
			$body .= '<tr><td colspan="'. $span .'">&nbsp;</td></tr>';
		}
		
		
	}
	
	?>
	
	<tr><td colspan="<?php echo $span; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
	
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>