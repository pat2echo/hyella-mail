<div class="report-table-preview">
<table class="table table-striped table-bordered table-hover" cellspacing="0">
<?php
	$body = "";
	
	$total["total goods supplied"] = 0;
	$total["cost of goods supplied"] = 0;
	$total["estimated value of all goods supplied"] = 0;
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$pc = 0;
	if( isset( $product_catalogue ) )
		$pc = $product_catalogue;
	
	//$item = get_items();
	$vendor = get_vendors();
	$color = array();
	
	$span = "1";
	$package = "";
	$weight_in_grams = 0;
	if( defined( "HYELLA_PACKAGE" ) ){
		$package = HYELLA_PACKAGE;
		$span = "1";
		$weight_in_grams = 1;
		//$total["total weight of gold in kg"] = 0;
		$total["total weight of gold available in kg"] = 0;
		
		$color = get_color_of_gold();
	}
	
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	
	if( ! empty( $report_data ) ){
		//transform data
		$count = 0;
		$body .= '<tr>';
		
		foreach( $report_data as $sval ){
			if( ! isset( $item_details[ $sval["item"] ] ) ){
				$item_details[ $sval["item"] ] = get_items_details( array( "id" => $sval["item"] ) );
			}
			
			if( isset( $item_details[ $sval["item"] ]["type"] ) && $item_details[ $sval["item"] ]["type"] == "service" )continue;
			
			if( ! ( $count % 3 ) ){
				$body .= '</tr><tr>';
			}
			++$count;
			
			$body .= '<td>';
				$body .= '<img src="' . $site_url . $item_details[ $sval["item"] ]["image"] . '" align1="left" style="margin-right:10px; width:100%; max-height:400px;" />';
				$body .= '<h5><strong>' . ( isset( $item_details[ $sval["item"] ]["description"] )?$item_details[ $sval["item"] ]["description"]:$sval["item"] ) . '</strong><code class="pull-right">' . $item_details[ $sval["item"] ]["barcode"] . '</code></h5>';
				
				
			$q = $sval["quantity"];
			if( $pc ){
				$q -= $sval["quantity_sold"] + $sval["quantity_used"];
			}
			
			switch( $package ){
			case "jewelry":
				if( isset( $item_details[ $sval["item"] ]["color_of_gold"] ) ){
					$body .= '<span class="badge badge-default badge-roundless"><small>' . ( isset( $color[ $item_details[ $sval["item"] ]["color_of_gold"] ] )?$color[ $item_details[ $sval["item"] ]["color_of_gold"] ]:$item_details[ $sval["item"] ]["color_of_gold"] )  . '</small></span>';
					
					$body .= '<span class="label label-info pull-right"><strong>' . number_format( $item_details[ $sval["item"] ]["weight_in_grams"] , 2 ) . 'g</strong> @ ';
					
					if( $pc ){
						$body .= ( ( $sval["selling_price"] && $item_details[ $sval["item"] ]["weight_in_grams"] )? number_format( $sval["selling_price"] / $item_details[ $sval["item"] ]["weight_in_grams"] , 2 ):"-" );
					}else{
						$body .= ( ( $sval["cost_price"] && $item_details[ $sval["item"] ]["weight_in_grams"] )? number_format( $sval["cost_price"] / $item_details[ $sval["item"] ]["weight_in_grams"] , 2 ):"-" );
					}
					
					 $body .= ' per g</span>';
					
					$total["total weight of gold available in kg"] += $item_details[ $sval["item"] ]["weight_in_grams"] * $q;
				}else{
					$body .= '<small>-</small>';
				}
			break;
			default:
				if( doubleval( $sval["expiry_date"] ) )$body .= '<td class="landscape-only">'.date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["expiry_date"] ) ). '</td>';
				else $body .= '<td class="landscape-only">-</td>';
			break;
			}
			
				$body .= '<p style="margin-top:6px; margin-bottom:4px;"><small>Quantity: <strong>'.number_format( $q , 0 ). '</strong> <span class="pull-right">Supply Date: <strong>' . date( ( ($date_filter)?$date_filter:"d-M-Y" ), doubleval( $sval["date"] ) ) . '</strong></span></small></p>';
				$body .= '<p style="margin-top:2px;">Supplier: <strong>' . ( isset( $vendor[ $sval["source"] ] )?$vendor[ $sval["source"] ]:$sval["source"] ) . '</strong>';
				
			if( $pc ){
				$body .= '<span style="font-size:1.1em; float:right;">Selling Price: <strong>' . number_format( $sval["selling_price"] , 2 ) . '</strong></span></p>';
			}else{
				$body .= '<span style="font-size:1.1em; float:right;">Item Cost: <strong>' . number_format( $sval["cost_price"] , 2 ) . '</strong></span></p>';
			}
			
			//$body .= '<td class="landscape-only">'.number_format( $sval["cost_price"] , 2 ). '</td>';
			//$body .= '<td class="landscape-only">'.number_format( $sval["selling_price"] , 2 ). '</td>';
			
			$body .= '</td>';
			
			$total["total goods supplied"] += $sval["quantity"];
			$total["cost of goods supplied"] += $sval["cost_price"];
			$total["estimated value of all goods supplied"] += $sval["selling_price"];
		}
		
		$body .= '</tr>';
		
		if( isset( $total["total weight of gold available in kg"] ) ){
			$total["total weight of gold available in kg"] = $total["total weight of gold available in kg"] / 1000;
			if( ! $pc ){
				$total["total weight of gold in kg"] = $total["total weight of gold available in kg"];
				unset( $total["total weight of gold available in kg"] );
			}
		}
		
		foreach( $total as & $v )$v = number_format( $v, 2 );
		
	}
	
	?>
	
	<tr><td colspan="<?php echo $span; ?>" rowspan="<?php echo count( $total ) + 1; ?>"><h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p></td><td colspan="2" ><h5>SUMMARY</h5></td></tr>
	
	<?php
	foreach( $total as $key => $val ){
		?>
		<tr><td colspan="1"><strong><?php echo ucwords( $key ); ?></strong></td><td colspan="1" ><?php echo $val; ?></td></tr>
		<?php
	}
?>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
</div>