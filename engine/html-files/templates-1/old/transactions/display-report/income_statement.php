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
	
	$span = 4;
	
	if( ! empty( $report_data ) ){
		//print_r( $r1 );
		//print_r( $report_data );
		
		$serial = 0;
		
		$r = __transform_dataset( $report_data, $summary );
		
		foreach( $r as $key => $rv ){
			
			if( $previous_data ){
				$body .= '<tr><td class="company">'. ucwords( $key ) .'</td><td class="company item-amount">'.date( "M-Y" , doubleval( $start_date ) ).'</td><td class="company item-amount">'.date( "M-Y" , doubleval( $nstart_date ) ).'</td></tr>';
			}else{
				$body .= '<tr><td class="company" colspan="'. $span .'">'. ucwords( $key ) .'</td></tr>';
			}
			
			$total = $rv["total"];
			unset( $rv["total"] );
			
			$prev_d_total = "";
			if( $previous_data && isset( $r1[ $key ][ "total" ] ) ){
				$prev_d_total = format_and_convert_numbers( $r1[ $key ][ "total" ] , 4 );
				unset( $r1[ $key ][ "total" ] );
			}
			
			foreach( $rv as $kv => $sval ){
				if( is_array( $sval ) ){
					
					//$body .= '<tr><td class="indent item-desc">'. ucwords( $kv ) .'</td><td class="item-amount"></td><td class="item-amount"></td></tr>';
					$subtotal = 0;
					
					foreach( $sval as $kkv => $vvv ){
						//$body .= '<tr><td class="indent-1 item-desc">'. ucwords( $kkv ) .'</td><td class="item-amount">'. format_and_convert_numbers( $vvv , 4 ) .'</td><td class="item-amount"></td></tr>';
						
						$subtotal += $vvv;
					}
					
					$prev_d = "";
					if( $previous_data && isset( $r1[ $key ][ $kv ] ) ){
						$subtotal1 = 0;
						foreach( $r1[ $key ][ $kv ] as $kkv => $vvv ){
							$subtotal1 += $vvv;
						}
						$prev_d = format_and_convert_numbers( $subtotal1, 4 );
						
						unset( $r1[ $key ][ $kv ] );
					}
					
					$body .= '<tr><td class="indent item-desc"><strong>'. ucwords( $kv ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $subtotal, 4 ) .'</strong></td><td class="item-amount">'.$prev_d.'</td></tr>';
					//$body .= '<tr><td>&nbsp;</td><td></td><td></td></tr>';
				}else{
					//if( $total == $sval )continue;
					$prev_d = "";
					if( $previous_data && isset( $r1[ $key ][ $kv ] ) ){
						$prev_d = format_and_convert_numbers( $r1[ $key ][ $kv ], 4 );
						unset( $r1[ $key ][ $kv ] );
					}
					
					$body .= '<tr><td class="indent item-desc">'. ucwords( $kv ) .'</td><td class="item-amount">'. format_and_convert_numbers( $sval ) .'</td><td class="item-amount">'.$prev_d.'</td></tr>';
				}
			}
			
			if( $previous_data && ! empty( $r1[ $key ] ) ){
				foreach( $r1[ $key ] as $kv => $sval ){
					if( is_array( $sval ) ){
						$subtotal = 0;
					
						foreach( $sval as $kkv => $vvv ){
							$subtotal += $vvv;
						}
						
						$body .= '<tr><td class="indent item-desc"><strong>'. ucwords( $kv ) .'</strong></td><td class="item-amount"><strong></strong></td><td class="item-amount">'. format_and_convert_numbers( $subtotal, 4 ) .'</td></tr>';
					}else{
						$body .= '<tr><td class="indent item-desc">'. ucwords( $kv ) .'</td><td class="item-amount"></td><td class="item-amount">'. format_and_convert_numbers( $sval ) .'</td></tr>';
					}
				}
			}
			
			$body .= '<tr class="total-row"><td class="item-desc"><strong>Total '. ucwords( $key ) .'</strong></td><td class="item-amount"><strong>'. format_and_convert_numbers( $total, 4 ) .'</strong></td><td class="item-amount">'.$prev_d_total.'</td></tr>';
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
<?php 
	function __transform_dataset( $report_data , $summary ){
		$r = array();
		$category = get_items_categories();
		$state = get_sales_status();
		$stores = get_stores();
		
		foreach( $report_data as $key => $sval ){
			
			$acc_details = get_chart_of_accounts_details( array( "id" => $sval["account"] ) );
			
			$ckey = "";
			switch( $sval[ "account_type" ] ){
			case "operating_expense":
			case "expenses":
				$ckey = "expenses";
			break;
			case "cost_of_goods_sold":
				if( isset( $category[ $sval["account"] ] ) && $category[ $sval["account"] ] ){
					$acc_details["title"] = $category[ $sval["account"] ];
				}else{
					$acc_details["title"] = 'Uncategorized Expenses';
				}
				$ckey = "expenses";
			break;
			case "revenue_category":
				if( isset( $category[ $sval["account"] ] ) ){
					$acc_details["title"] = $category[ $sval["account"] ];
				}else{
					$acc_details["title"] = 'Uncategorized Revenue';
				}
				$ckey = "revenue";
			break;
			case "revenue":
				$ckey = "revenue";
			break;
			}
			
			if( $ckey ){
				switch( $ckey ){
				case "expenses":
				case "revenue":
					//$ckey = $acc_details[ "type" ];
					//$ckey = $sval[ "account_type" ];
					
					if( ! isset( $r[ $ckey ]["total"] ) )$r[ $ckey ]["total"] = 0;
					$r[ $ckey ]["total"] += $sval["amount"];
					
					if( $summary ){
						if( ! isset( $r[ $ckey ][ $acc_details["title"] ] ) )$r[ $ckey ][ $acc_details["title"] ] = 0;
						$r[ $ckey ][ $acc_details["title"] ] += $sval["amount"];
					}else{
						$store = ( isset( $stores[ $sval["store"] ] ) ? $stores[ $sval["store"] ] : $sval["store"] );
						
						if( ! isset( $r[ $ckey ][ $acc_details["title"] ][ $store ] ) )$r[ $ckey ][ $acc_details["title"] ][ $store ] = 0;
						$r[ $ckey ][ $acc_details["title"] ][ $store ] += $sval["amount"];
						
						$sr[ $store ] = $store;
					}
				break;
				}
			}
		}
		return $r;
	}
?>