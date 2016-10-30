<?php
	$body = "";
	$body_left = "";
	$body_right = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$stores = get_stores();
	$accs = get_types_of_account();
	
	$span = 4;
	
	$total_asset = array( "debit" => 0, "credit" => 0 );
	$total_liabilities = array( "debit" => 0, "credit" => 0 );
	$total_equity = array( "debit" => 0, "credit" => 0 );
	
	if( ! empty( $report_data ) ){
		
		//transform data
		$r = array();
		$r1 = array();
		
		$serial = 0;
		
		foreach( $report_data as $key => $sval ){
			
			$acc_details = get_chart_of_accounts_details( array( "id" => $sval["account_type"] ) );
			$ckey = $acc_details["type"];
			
			if( ! isset( $r[ $ckey ][ $acc_details["title"] ][ $sval["type"] ] ) )$r[ $ckey ][ $acc_details["title"] ][ $sval["type"] ] = 0;
			$r[ $ckey ][ $acc_details["title"] ][ $sval["type"] ] += round( $sval["amount"] , 2);
			
			switch( $ckey ){
			case "asset":
			case "other_asset":
			case "fixed_asset":
				$total_asset[ $sval["type"] ] += round( $sval["amount"] , 2);
			break;
			case "liabilities":
			case "other_liabilities":
				$total_liabilities[ $sval["type"] ] += round( $sval["amount"] , 2);
			break;
			case "equity":
				$total_equity[ $sval["type"] ] += round( $sval["amount"] , 2);
			break;
			}
		}
		
		$ta = $total_asset["debit"] - $total_asset["credit"];
		$tl = $total_liabilities["credit"] - $total_liabilities["debit"];
		$te = $total_equity["credit"] - $total_equity["debit"];
		
		$total_credit = 0;
		$total_debit = 0;
		
		foreach( $r as $key => $rv ){
			
			$body_tmp = '<tr><td colspan="'. $span .'"><strong>'. ucwords( isset( $accs[ $key ] )?$accs[ $key ]:$key  ) .'</strong></td></tr>';
			$total = 0;
			
			foreach( $rv as $kv => $sval ){
				$bal = "";
				$percent = "";
				$balance = 0;
				if( isset( $sval["debit"] ) )$balance += round( $sval["debit"], 2 );
				if( isset( $sval["credit"] ) )$balance -= round( $sval["credit"], 2 );
				
				//if( $balance == 0 )continue;
				$negative = 0;
				$abs = 1;
				switch( $key ){
				case "asset":
				case "other_asset":
				case "fixed_asset":
					if( $balance < 0 ){
						$negative = 1;
						$abs = -1;
					}
				break;
				case "liabilities":
				case "other_liabilities":
					if( $balance > 0 ){
						$negative = 1;
					}else{
						$abs = -1;
					}
				break;
				case "equity":
					if( $balance > 0 ){
						$negative = 1;
					}else{
						$abs = -1;
					}
				break;
				}
				
				if( $negative ){
					$bal = '(' . format_and_convert_numbers( $balance * $abs , 4 ) . ')';
					if( $ta )$percent = number_format( ( $balance / $ta ) * 100  , 2 ) . "%";
					
					$total += $balance;
					$total_credit += $balance;
					
				}else{
					$bal = format_and_convert_numbers( $balance * $abs , 4 );
					if( $ta )$percent = number_format( ( $balance / $ta ) * 100 * $abs  , 2 ) . "%";
					
					$total_debit += $balance;
					
					$total += ( $balance  * $abs );
				}
				
				$body_tmp .= '<tr><td class="indent item-desc">'. ucwords( $kv ) .'</td><td class="item-amount">'. $bal .'</td><td class="item-amount">'. $percent .'</td></tr>';
			}
			
			$body_tmp .= '<tr class="total-row"><td>Total '. ucwords( isset( $accs[ $key ] )?$accs[ $key ]:$key  ) .'</td><td class="item-amount">'. format_and_convert_numbers( $total , 4 ) .'</td><td class="item-amount">'. ( ( $ta )?number_format( ( $total / $ta ) * 100  , 2 ):"0" ) .'%</td></tr>';
			$body_tmp .= '<tr><td colspan="'. $span .'">&nbsp;</td></tr>';
			
			$add = 1;
			if( $add ){
				switch( $key ){
				case "asset":
				case "other_asset":
				case "fixed_asset":
					$body_left .= $body_tmp;
				break;
				case "liabilities":
				case "other_liabilities":
					$body_right .= $body_tmp;
				break;
				case "equity":
					$body .= $body_tmp;
				break;
				}
			}
			
			$add = 0;
			
		}
		
		
	}
	
	$bal = $ta - ( $tl + $te );
	
	if( $ta )$percent = number_format( ( $bal / $ta ) * 100  , 2 ) . "%";
	
	//update equity
	$te = $bal;
	
	if( $te < 0 ){
		$te_formatted = '(' . format_and_convert_numbers( $te * -1 , 4 ) . ')';
	}else{
		$te_formatted = format_and_convert_numbers( $te , 4 );
	}
	
	
	$body .= '<tr><td class="indent item-desc">Retained Earnings</td><td class="item-amount">'. $te_formatted .'</td><td class="item-amount">'. $percent .'</td></tr>';
	
	?>
<div class="row">
	<div class="col-md-6">
		<div class="report-table-preview">
		<table class="table table-striped table-bordered table-hover" cellspacing="0" >
		<thead>
			<tr><td colspan="<?php echo $span; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td></tr>	
			<tr><th>Assets</th><th></th><th>% of Assets</th></tr>
		</thead>
		<tbody>
		<?php echo $body_left; ?>
		</tbody>
		<tfoot>
			<tr class="total-row">
				<td>TOTAL ASSET</td><td class="item-amount"><?php echo format_and_convert_numbers( $ta , 4 ) ?></td><td class="item-amount"><?php echo ( ( $ta )?number_format( ( $ta / $ta ) * 100  , 2 ):"0" ); ?>%</td>
			</tr>
		</tfoot>
		</table>
		</div>
	</div>
	<div class="col-md-6">
		<div class="report-table-preview">
		<table class="table table-striped table-bordered table-hover" cellspacing="0" >
		<thead>
			<tr><th>Liabilities</th><th></th><th>% of Assets</th></tr>
		</thead>
		<tbody>
		<?php echo $body_right; ?>
		</tbody>
		<tfoot>
			<tr class="total-row">
				<td>TOTAL LIABILITIES</td><td class="item-amount"><?php echo format_and_convert_numbers( $tl , 4 ) ?></td><td class="item-amount"><?php echo ( ( $ta )?number_format( ( $tl / $ta ) * 100  , 2 ):"0" ); ?>%</td>
			</tr>
		</tfoot>
		</table>
		</div>
		<br />
		<div class="report-table-preview">
		<table class="table table-striped table-bordered table-hover" cellspacing="0" >
		<thead>
			<tr><th>Owner's Equity</th><th></th><th>% of Assets</th></tr>
		</thead>
		<tbody>
		<?php echo $body; ?>
		</tbody>
		<tfoot>
			<tr class="total-row">
				<td>TOTAL OWNERS EQUITY</td><td class="item-amount"><?php echo $te_formatted; ?></td><td class="item-amount"><?php echo ( ( $ta )?number_format( ( $te / $ta ) * 100  , 2 ):"0" ); ?>%</td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>
			<tr class="total-row">
				<td>TOTAL LIABILITIES + OWNERS EQUITY</td><td class="item-amount"><?php echo format_and_convert_numbers( $ta , 4 ) ?></td><td class="item-amount"><?php echo ( ( $ta )?number_format( ( $ta / $ta ) * 100  , 2 ):"0" ); ?>%</td>
			</tr>
		</tfoot>
		</table>
		</div>
	</div>
</div>