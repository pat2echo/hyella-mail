<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total goods instock"] = 0;
	$total["total goods supplied"] = 0;
	$total["total goods sold / consumed"] = 0;
	$total["cost of all goods in-stock"] = 0;
	$total["estimated value of all goods in-stock"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	//$item = get_items();
	$vendor = get_vendors();
	
	$weight_in_grams = 0;
	if( defined( "HYELLA_PACKAGE" ) ){
		switch( HYELLA_PACKAGE ){
		case "jewelry":
			$weight_in_grams = 1;
			$total["total weight of gold purchased in kg"] = 0;
			$total["total weight of gold available in kg"] = 0;
		break;
		}
	}
			
	$item_details = array();
	
	if( ! empty( $report_data ) ){
		//transform data
		
		foreach( $report_data as $sval ){
			
			$type = 1;
			if( isset( $item_details[ $sval["item"] ]["type"] ) && $item_details[ $sval["item"] ]["type"] == "service" )$type = 0;
			
			if( ! $type )continue;
			
			if( ! isset( $item_details[ $sval["item"] ] ) ){
				$item_details[ $sval["item"] ] = get_items_details( array( "id" => $sval["item"] ) );
				if( $item_details[ $sval["item"] ]["type"] == "service" )continue;
			}
			
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td><strong>' . ( isset( $item_details[ $sval["item"] ] )?$item_details[ $sval["item"] ][ "description" ]:$sval["item"] ) . '</strong></td>';
				$body .= '<td>' . ( isset( $item_details[ $sval["item"] ] )?$item_details[ $sval["item"] ][ "barcode" ]:"-" ) . '</td>';
				//$body .= '<td><strong>' . $sval["serial_num"] . "-" . $sval["id"] . '</td>';
				
				$q = $sval["quantity"] - ( $sval["quantity_sold"] + $sval["quantity_used"] + $sval["quantity_damaged"] );
				if( $type ){
					//$body .= '<td class="company">'.number_format( $q , 0 ). '</td>';
					$body .= '<td class="landscape-only">' . number_format( $sval["quantity"], 0 ) . '</td>';
				}else{
					//$body .= '<td class="company"> service </td>';
					$body .= '<td class="landscape-only">-</td>';
					$sval["cost_price"] = 0;
				}
				
				$body .= '<td class="landscape-only">' . number_format( $sval["quantity_sold"] , 0 ) . '</td>';
				$body .= '<td class="landscape-only">' . number_format( $sval["quantity_used"], 0 ) . '</td>';
				$body .= '<td class="landscape-only">' . number_format( $sval["quantity_damaged"], 0 ) . '</td>';
				$body .= '<td class="landscape-only">' . number_format( $sval["cost_price"], 0 ) . '</td>';
				$body .= '<td class="landscape-only">' . number_format( $sval["selling_price"], 0 ) . '</td>';
				
				if( $weight_in_grams ){
					$body .= '<td class="company">' . ( isset( $sval["weight_in_grams"] )? ( number_format( $sval["weight_in_grams"] , 2 ) ):"" ) . '</td>';
					$total["total weight of gold purchased in kg"] += doubleval( $sval["weight_in_grams"] ) * $sval["quantity"];
					$total["total weight of gold available in kg"] += doubleval( $sval["weight_in_grams"] ) * $q;
				}
				
				$body .= '<td><strong>' . ( isset( $vendor[ $sval["source"] ] )?$vendor[ $sval["source"] ]:$sval["source"] ) . '</strong></td>';
				
				//$body .= '<td>' . number_format( $sval["discount"], 2 ) . '</td>';
				
				if( $type )$total["total goods instock"] += $q;
				$total["total goods supplied"] += $sval["quantity"];
				$total["total goods sold / consumed"] += ( $sval["quantity_sold"] + $sval["quantity_used"] );
				
				$total["cost of all goods in-stock"] += ( $sval["cost_price"] * $q );
				$total["estimated value of all goods in-stock"] += ( $sval["selling_price"] * $q );
				
				
			$body .= '</tr>';
			
		}
		
		if( isset( $total["total weight of gold purchased in kg"] ) ){
			$total["total weight of gold purchased in kg"] /= 1000;
			$total["total weight of gold available in kg"] /= 1000;
		}
		
		foreach( $total as & $v )$v = number_format( $v, 2 );
		
	}
	
	?>
	<tr><td colspan="4" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><p><?php echo $subtitle; ?></p></td><td colspan="7" ><h5>SUMMARY</h5></td></tr>
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="3"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="4" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th rowspan="2" class="company">Last Supply Date</th>
		<th rowspan="2">Item</th>
		<th rowspan="2">Barcode</th>
		
		<th class="landscape-only" colspan="6">Inventory Info</th>
		<?php if( $weight_in_grams ){ ?>
		<th rowspan="2">Weight (g)</th>
		<?php } ?>
		<th rowspan="2">Last Supplier / Vendor</th>
		
	</tr>
	<tr>
		<th class="landscape-only">Units Supplied</th>
		<th class="landscape-only">Units Sold</th>
		<th class="landscape-only">Units Consumed</th>
		<th class="landscape-only">Units Damaged</th>
		<th class="landscape-only">Cost Price</th>
		<th class="landscape-only">Selling Price</th>
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>