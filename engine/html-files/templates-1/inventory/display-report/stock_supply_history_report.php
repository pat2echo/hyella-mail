<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$total["total goods supplied"] = 0;
	$total["cost of goods supplied"] = 0;
	$total["estimated value of all goods supplied"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	//$item = get_items();
	$vendor = get_vendors();
	$stores = get_stores();
	
	$span = "2";
	$package = "";
	$weight_in_grams = 0;
	if( defined( "HYELLA_PACKAGE" ) ){
		$package = HYELLA_PACKAGE;
		$span = "3";
		$weight_in_grams = 1;
		$total["total weight of gold supplied in kg"] = 0;
	}
	
	if( ! empty( $report_data ) ){
		//transform data
		
		foreach( $report_data as $sval ){
			if( ! isset( $item_details[ $sval["item"] ] ) ){
				$item_details[ $sval["item"] ] = get_items_details( array( "id" => $sval["item"] ) );
			}
			
			if( isset( $item_details[ $sval["item"] ]["type"] ) && $item_details[ $sval["item"] ]["type"] == "service" )continue;
			
			//$sval["cost_price"] = $item_details[ $sval["item"] ][ "cost_price" ];
			//$sval["selling_price"] = $item_details[ $sval["item"] ][ "selling_price" ];
			
			$body .= '<tr>';
				
				$body .= '<td class="company">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ). '</td>';
				$body .= '<td><strong>' . ( isset( $item_details[ $sval["item"] ]["description"] )?$item_details[ $sval["item"] ]["description"]:$sval["item"] ) . '</strong></td>';
				$body .= '<td class="landscape-only">' . ( isset( $item_details[ $sval["item"] ]["barcode"] )?$item_details[ $sval["item"] ]["barcode"]:"" ) . '</td>';
				
				$body .= '<td class="company">'.number_format( $sval["quantity"] , 0 ). '</td>';
				
				if( isset( $stores[ $sval["source"] ] ) && $stores[ $sval["source"] ] ){
					$body .= '<td><strong>' . $stores[ $sval["source"] ] . '</strong></td>';
				}else{
					$body .= '<td><strong>' . ( isset( $vendor[ $sval["source"] ] )?$vendor[ $sval["source"] ]:$sval["source"] ) . '</strong></td>';
				}
				
				switch( $package ){
				case "jewelry":
					if( isset( $item_details[ $sval["item"] ]["color_of_gold"] ) ){
						$body .= '<td class="landscape-only">' . $item_details[ $sval["item"] ]["color_of_gold"] . '</td>';
						$body .= '<td class="landscape-only">' .  number_format( $item_details[ $sval["item"] ]["weight_in_grams"] , 2 ) . '</td>';
						$body .= '<td class="landscape-only">' . ( ( $sval["cost_price"] && $item_details[ $sval["item"] ]["weight_in_grams"] )? number_format( $sval["cost_price"] / $item_details[ $sval["item"] ]["weight_in_grams"] , 2 ):"-" ) . '</td>';
						
						$total["total weight of gold supplied in kg"] += $item_details[ $sval["item"] ]["weight_in_grams"];
					}else{
						$body .= '<td class="landscape-only">-</td>';
						$body .= '<td class="landscape-only">-</td>';
						$body .= '<td class="landscape-only">-</td>';
					}
				break;
				default:
					if( doubleval( $sval["expiry_date"] ) )$body .= '<td class="landscape-only">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["expiry_date"] ) ). '</td>';
					else $body .= '<td class="landscape-only">-</td>';
				break;
				}
				
				$body .= '<td class="landscape-only">'.number_format( $sval["cost_price"] , 2 ). '</td>';
				$body .= '<td class="landscape-only">'.number_format( $sval["selling_price"] , 2 ). '</td>';
				
				$total["total goods supplied"] += $sval["quantity"];
				$total["cost of goods supplied"] += ( $sval["cost_price"] * $sval["quantity"] );
				$total["estimated value of all goods supplied"] += ( $sval["selling_price"] * $sval["quantity"] );
	
			$body .= '</tr>';
			
		}
		
		if( isset( $total["total weight of gold supplied in kg"] ) )
			$total["total weight of gold supplied in kg"] = $total["total weight of gold supplied in kg"] / 1000;
		
		foreach( $total as & $v )$v = number_format( $v, 2 );
		
	}
	
	?>
	
	<tr><td colspan="<?php echo $span; ?>" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="7" ><h5>SUMMARY</h5></td></tr>
	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="3"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="4" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tr>
		<th >Supply Date</th>
		<th >Item</th>
		<th class="landscape-only">Barcode</th>
		
		<th >Units Supplied</th>
		<th >Supplier / Vendor</th>
		<?php
		switch( $package ){
		case "jewelry":
			?> 
			<th class="landscape-only">Color</th> 
			<th class="landscape-only">Weight in Grams</th> 
			<th class="landscape-only">Cost per Gram</th> 
			<?php
		break;
		default:
			?> <th class="landscape-only">Expiry Date</th> <?php
		break;
		}
		?>
		
		<th class="landscape-only">Cost Price</th>
		<th class="landscape-only">Selling Price</th>
		
	</tr>
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>