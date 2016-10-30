<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0" style="max-width:700px; min-width:700px; margin:auto;">
<thead>
<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	if( ! isset( $summary ) )
		$summary = 0;
	
	$previous_data = 0;
	$r1 = array();
	
	$start_date = 0;
	if( isset( $data['previous_report_data']["data"] ) && is_array( $data['previous_report_data']["data"] ) && ! empty( $data['previous_report_data']["data"] ) ){
		$r1 = __transform_dataset( $data['previous_report_data']["data"] , $summary );
		$previous_data = 1;
		$start_date = $data['previous_report_data']["start_date"];
		$nstart_date = $data['previous_report_data']["nstart_date"];
		unset( $data['previous_report_data']["data"] );
	}
	
	$span = 3;
	
	if( ! empty( $report_data ) ){
		//print_r( $report_data );
		
		//transform data
		$r = __transform_dataset( $report_data, $summary );
		
		$serial = 0;
		
		$grand_total = 0;
		$grand_total1 = 0;
		foreach( $r as $key => $rv ){
			
			if( $previous_data ){
				$body .= '<tr><td class="company">'. ucwords( $key ) .'</td><td class="company item-amount">'.date( "M-Y" , doubleval( $start_date ) ).'</td><td class="company item-amount">'.date( "M-Y" , doubleval( $nstart_date ) ).'</td></tr>';
			}else{
				$body .= '<tr><td class="company" colspan="'. $span .'">'. ucwords( $key ) .'</td></tr>';
			}
			
			$total = 0;
			$total1 = 0;
			unset( $rv["total"] );
			
			foreach( $rv as $kv => $sval ){
				$total += $sval;
				
				$prev_d = "";
				if( $previous_data && isset( $r1[ $key ][ $kv ] ) ){
					$total1 += $r1[ $key ][ $kv ];
					$prev_d = format_and_convert_numbers( $r1[ $key ][ $kv ] , 4 );
					
					unset( $r1[ $key ][ $kv ] );
				}
				
				$body .= '<tr><td class="indent item-desc">'. ucwords( $kv ) .'</td><td class="item-amount">'. format_and_convert_numbers( $sval ) .'</td><td class="item-amount">'. $prev_d .'</td></tr>';
			}
			
			if( $previous_data && isset( $r1[ $key ] ) ){
				foreach( $r1[ $key ] as $kv => $sval ){
					$total1 += $sval;
					$body .= '<tr><td class="indent item-desc">'. ucwords( $kv ) .'</td><td class="item-amount"></td><td class="item-amount">'. format_and_convert_numbers( $sval ) .'</td></tr>';
				}
			}
			
			$body .= '<tr class="total-row"><td class="item-desc"><strong>Total '. ucwords( $key ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $total, 4 ) .'</strong></td><td class="item-amount"></td></tr>';
			$body .= '<tr><td colspan="'. $span .'">&nbsp;</td></tr>';
			
			$grand_total += $total;
			$grand_total1 += $total1;
		}
		
		if( $grand_total ){
			$body .= '<tr class="total-row"><td class="item-desc"><strong>GRAND TOTAL</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $grand_total, 4 ) .'</strong></td><td class="item-amount"></td></tr>';
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
<?php 
	function __transform_dataset( $report_data , $summary ){
		$stores = get_stores();
		$category = get_items_categories();
		$account = get_first_level_accounts();
		
		$acc_type = get_types_of_account();
		
		$r = array();
		foreach( $report_data as $key => $sval ){
			
			$ckey = "";
			$store = "";
			switch( $sval[ "account_type" ] ){
			case "cost_of_goods_sold":
				if( isset( $category[ $sval["account"] ] ) ){
					$acc_details["title"] = $category[ $sval["account"] ];
				}
				//$store = ( isset( $stores[ $sval["store"] ] ) ? $stores[ $sval["store"] ] : $sval["store"] );
				$store = $acc_type["cost_of_goods_sold"];
			break;
			case "operating_expense":
			case "expenses":
				$acc_details = get_chart_of_accounts_details( array( "id" => $sval["account"] ) );
				//$store = ( isset( $stores[ $sval["store"] ] ) ? $stores[ $sval["store"] ] : $sval["store"] );
				$store = "Expenses";
			break;
			}
						
			if( $store ){
				if( ! isset( $r[ $store ][ $acc_details["title"] ] ) )
					$r[ $store ][ $acc_details["title"] ] = 0;
				
				if( $sval["type"] == "debit" )
					$r[ $store ][ $acc_details["title"] ] += $sval["amount"];
			}
		}
		return $r;
	}
?>