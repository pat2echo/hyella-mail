<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total goods instock"] = 0;
	$total["total goods supplied"] = 0;
	$total["total goods sold / consumed"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$item = get_items();
	$vendor = get_vendors();
	
	if( ! empty( $report_data ) ){
		//transform data
		
		$r1 = array();
		$r2 = array();
		foreach( $report_data as $sval ){
			$r1[ $sval["item"] ] = $sval["quantity"] - ( $sval["quantity_sold"] + $sval["quantity_used"] );
			$r2[ $sval["item"] ] = $sval;
		}
		asort( $r1 );
		
		$serial = 0;
		foreach( $r1 as $k => $v ){
			$sval = $r2[ $k ];
			
			
			//if( ! $sval["quantity"] )continue;
			
			if( ! isset( $item_details[ $sval["item"] ] ) ){
				$item_details[ $sval["item"] ] = get_items_details( array( "id" => $sval["item"] ) );
			}
			
			$type = 1;
			if( isset( $item_details[ $sval["item"] ]["type"] ) && $item_details[ $sval["item"] ]["type"] == "service" )continue;
			++$serial;
			
			$body .= '<tr>';
				
				$body .= '<td class="company">'.$serial. '</td>';
				$body .= '<td><strong>' . ( isset( $item[ $sval["item"] ] )?$item[ $sval["item"] ]:$sval["item"] ) . '</strong></td>';
				//$body .= '<td><strong>' . $sval["serial_num"] . "-" . $sval["id"] . '</td>';
				
				$q = $sval["quantity"] - ( $sval["quantity_sold"] + $sval["quantity_used"] );
				$body .= '<td class="company">'.number_format( $q , 0 ). '</td>';
				
				$body .= '<td class="landscape-only">' . number_format( $sval["quantity"], 0 ) . '</td>';
				$body .= '<td class="landscape-only">' . number_format( $sval["quantity_sold"] + $sval["quantity_used"], 0 ) . '</td>';
				
				$body .= '<td><strong>' . ( isset( $vendor[ $sval["source"] ] )?$vendor[ $sval["source"] ]:$sval["source"] ) . '</strong></td>';
				
				//$body .= '<td>' . number_format( $sval["discount"], 2 ) . '</td>';
				
				$total["total goods instock"] += $q;
				$total["total goods supplied"] += $sval["quantity"];
				$total["total goods sold / consumed"] += ( $sval["quantity_sold"] + $sval["quantity_used"] );
				
			$body .= '</tr>';
			
		}
		
		foreach( $total as & $v )$v = number_format( $v, 0 );
		
	}
	
	?>
	<tr><td colspan="2" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><p><?php echo $subtitle; ?></p></td><td colspan="5" ><h5>SUMMARY</h5></td></tr>	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="1"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="4" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th rowspan="2" class="company">Rank</th>
		<th rowspan="2">Item</th>
		<th rowspan="2">Stock Level</th>
		
		<th class="landscape-only" colspan="2">Inventory Info</th>
		<th rowspan="2">Last Supplier / Vendor</th>
		
	</tr>
	<tr>
		<th class="landscape-only">Total Units Supplied</th>
		<th class="landscape-only">Units Sold / Consumed / Damaged</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>