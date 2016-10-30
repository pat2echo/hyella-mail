<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0" style="max-width:700px; min-width:700px; margin:auto;">
<thead>
<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$stores = get_stores();
	$accs = get_types_of_account();
	
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
			
			$acc_details = get_chart_of_accounts_details( array( "id" => $sval["account_type"] ) );
			
			if( isset( $sval[ 'account_type' ] ) ){
				switch( $sval[ 'account_type' ] ){
				case "cash_book":
					$acc_details = get_chart_of_accounts_details( array( "id" => $sval["account"] ) );
				default:
					$ckey = $acc_details["type"];
					//$ckey = $sval[ 'account_type' ];
					
					if( ! isset( $r[ $ckey ]["total"] ) )$r[ $ckey ]["total"] = 0;
					$r[ $ckey ]["total"] += $sval["amount"];
					
					if( $summary ){
						if( ! isset( $r[ $ckey ][ $sval["type"] ] ) )$r[ $ckey ][ $sval["type"] ] = 0;
						$r[ $ckey ][ $sval["type"] ] += $sval["amount"];
					}else{
						if( ! isset( $r[ $ckey ][ $acc_details["title"] ][ $sval["type"] ] ) )$r[ $ckey ][ $acc_details["title"] ][ $sval["type"] ] = 0;
						$r[ $ckey ][ $acc_details["title"] ][ $sval["type"] ] += $sval["amount"];
					}
				break;
				}
			}
		}
		
		$total_credit = 0;
		$total_debit = 0;
		$add = 0;
		foreach( $r as $key => $rv ){
			
			if( $summary ){
				$sval = $rv;
				
				$debit = "";
				$credit = "";
				$balance = 0;
				if( isset( $sval["debit"] ) )$balance += $sval["debit"];
				if( isset( $sval["credit"] ) )$balance -= $sval["credit"];
				
				if( $balance == 0 )continue;
				if( $balance > 0 ){
					$debit = format_and_convert_numbers( $balance , 4 );
					$total_debit += $balance;
				}else{
					$total_credit += $balance * -1;
					$credit = format_and_convert_numbers( $balance * -1 , 4 );
				}
				$add = 1;
					
				$body_tmp = '<tr><td class="item-desc">'. ucwords( isset( $accs[ $key ] )?$accs[ $key ]:$key  ) .'</td><td class="item-amount">'. $debit .'</td><td class="item-amount">'. $credit .'</td></tr>';
					
			}else{
				
				$body_tmp = '<tr><td class="company" colspan="'. $span .'">'. ucwords( isset( $accs[ $key ] )?$accs[ $key ]:$key  ) .'</td></tr>';
				$total = $rv["total"];
				unset( $rv["total"] );
				
				foreach( $rv as $kv => $sval ){
					$debit = "";
					$credit = "";
					$balance = 0;
					if( isset( $sval["debit"] ) )$balance += $sval["debit"];
					if( isset( $sval["credit"] ) )$balance -= $sval["credit"];
					
					if( $balance == 0 )continue;
					
					$add = 1;
					if( $balance > 0 ){
						$debit = format_and_convert_numbers( $balance , 4 );
						$total_debit += $balance;
					}else{
						$total_credit += $balance * -1;
						$credit = format_and_convert_numbers( $balance * -1 , 4 );
					}
						
					$body_tmp .= '<tr><td class="indent item-desc">'. ucwords( $kv ) .'</td><td class="item-amount">'. $debit .'</td><td class="item-amount">'. $credit .'</td></tr>';
				}
				$body_tmp .= '<tr><td colspan="'. $span .'">&nbsp;</td></tr>';
			}
			
			if( $add )$body .= $body_tmp;
			$add = 0;
			
		}
		
		
		$body .= '<tr class="total-row"><td>TOTALS</td><td class="item-amount">'. format_and_convert_numbers( $total_debit , 4 ) .'</td><td class="item-amount">'. format_and_convert_numbers( $total_credit , 4 ) .'</td></tr>';
	}
	
	?>
	
	<tr><td colspan="<?php echo $span; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
	<tr><th>Account</th><th>Debit</th><th>Credit</th></tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>