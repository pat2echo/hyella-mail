<?php
$store = array();
$_store = '';
$address = '';
if( get_single_store_settings() ){
	$sa = get_stores();
	if( is_array( $sa ) ){
		foreach( $sa as $kas => $kkas ){
			$_store = $kas;
		}
	}
}
if( isset( $_store ) && $_store ){
	$store = get_store_details( array( "id" => $_store ) );
	if( isset( $store["address"] ) ){
		$address = $store["address"];
	}
}

$show_selling_price = 0;
if( isset( $show_price ) && $show_price ){
	$show_selling_price = 1;
}

$cat = get_items_categories();
foreach( $data["barcodes"] as $key => $val ){
	$item = get_items_details_by_barcode( array( "id" => $val["text"] ) );
	
	$qq = $val["quantity"];
	if( ! $print )$val["quantity"] = 1;
	
	for( $x = 0; $x < $val["quantity"]; $x++ ){
	++$count;

	if( $count > 1 ){
?>
			<div style="page-break-before:always; height:4px;" ></div>
			<div style="margin-top:0px; width:<?php echo $width; ?>px; height:<?php echo $height; ?>px; font-size:12px; font-family:arial; margin-bottom:0; padding:0; <?php echo $dcss; ?>">
			<?php }else{ ?>
			<div style="margin-top:4px; width:<?php echo $width; ?>px; height:<?php echo $height; ?>px; font-size:12px; font-family:arial; margin-bottom:0; padding:0; <?php echo $dcss; ?>">
			<?php } ?>
			
			<div style="min-height:70px; ">
				<span style="<?php if( $print ){ ?>width:100%;<?php } ?> float:left; line-height:15px; text-align:center; font-size:12px;">
					<div style="margin-bottom:5px;"><?php echo strtoupper( $pr["company_name"] ); ?><br /><span style="text-decoration:underline; font-size:10px;"><?php echo ucwords( $address ); ?></span></div><br />
					<img src="<?php if( isset( $val[ "image" ] ) )echo $link . $val[ "image" ]; ?>"  align="center" /><br /><span style="font-size:9px; margin-top:1px; display:block; font-weight:bold;"><?php if( isset( $val[ "text" ] ) )echo $val[ "text" ]; ?></span>
					<br />
					<?php if( $show_selling_price ){ ?>
					<div style="font-size:15px; font-weight:bold;"><?php if( isset( $val[ "price" ] ) )echo $val[ "price" ]; ?></div>
					<?php } ?>
					<div><?php if( isset( $item[ "description" ] ) )echo $item[ "description" ]; ?></div>
					<div style="font-size:10px;"><?php if( isset( $val[ "category" ] ) )echo ( isset( $cat[ $val[ "category" ] ] )?$cat[ $val[ "category" ] ]:$val[ "category" ] ); ?></div>
					<br />
				</span>
				
				<?php if( ! $print ){ ?>
				<?php if( $show_all_buttons ){ ?>
				<span style="width:100px; margin-left:50px; float:right;"> <input type="number" min="1" step="1" name="quantity[<?php echo $val["text"]; ?>]" value="<?php echo $qq; ?>" class="form-control pull-right1" style="float:left; margin-right:15px; width:65px;" placeholder="No. of Labels" /> <input type="checkbox" name="barcode[<?php echo $val["text"]; ?>]" value="<?php echo $val["text"]; ?>" style="height:15px; width:15px;" /></span>
				<?php } ?>
				<?php } ?>
				
			</div>
		</div>
				
			<?php
		
		}
		
		}
?>