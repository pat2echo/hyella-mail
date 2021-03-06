<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0" style="margin:auto; max-width:700px;">
<thead>
<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$stores = get_stores();
	
	if( ! isset( $summary ) )
		$summary = 0;
	
	$span = 2;
	
	if( ! empty( $report_data ) ){
		//print_r( $report_data );
		
		//transform data
		$r = array();
		$r1 = array();
		
		$serial = 0;
		
		foreach( $report_data as $key => $sval ){
			
			$acc_details = get_chart_of_accounts_details( array( "id" => $sval["account"] ) );
			
			$ckey = "";
			switch( $sval[ "account_type" ] ){
			case "operating_expense":
			case "cost_of_goods_sold":
			case "expenses":
				$store = ( isset( $stores[ $sval["store"] ] ) ? $stores[ $sval["store"] ] : $sval["store"] );
				
				if( ! isset( $r[ $store ][ $acc_details["title"] ] ) )
					$r[ $store ][ $acc_details["title"] ] = 0;
				
				if( $sval["type"] == "debit" )
					$r[ $store ][ $acc_details["title"] ] += $sval["amount"];
				
				
			break;
			}
			
		}
		
		$grand_total = 0;
		foreach( $r as $key => $rv ){
			
			$body .= '<tr><td class="company" colspan="'. $span .'">'. ucwords( $key ) .'</td></tr>';
			$total = 0;
			unset( $rv["total"] );
			
			foreach( $rv as $kv => $sval ){
				$total += $sval;
				$body .= '<tr><td class="indent item-desc">'. ucwords( $kv ) .'</td><td class="item-amount">'. format_and_convert_numbers($sval ) .'</td></tr>';
			}
			
			$body .= '<tr class="total-row"><td class="item-desc" colspan="'. ( $span - 2 ) .'"><strong>Total '. ucwords( $key ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $total, 4 ) .'</strong></td></tr>';
			$body .= '<tr><td colspan="'. $span .'">&nbsp;</td></tr>';
			
			$grand_total += $total;
		}
		
		if( $grand_total ){
			$body .= '<tr class="total-row"><td class="item-desc" colspan="'. ( $span - 2 ) .'"><strong>GRAND TOTAL</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $grand_total, 4 ) .'</strong></td></tr>';
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