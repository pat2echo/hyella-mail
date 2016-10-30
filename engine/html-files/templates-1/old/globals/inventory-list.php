<?php 
	$q = $sval["quantity"];
	if( isset( $sval["quantity_used"] ) )$q -= $sval["quantity_used"];
	if( isset( $sval["quantity_sold"] ) )$q -= $sval["quantity_sold"];
	
?>
<a class="item <?php if( isset( $active ) && $active )echo "active"; ?> cart-item-select <?php echo $sval["category"]." barcode-".$sval["barcode"]; ?>" href="#" id="<?php echo $sval["item"]; ?>-container" data-id="<?php echo $sval["item"]; ?>" data-max="<?php echo $q; ?>" data-price="<?php echo $sval["selling_price"]; ?>" data-cost="<?php echo $sval["cost_price"]; ?>" style="margin-left:0; padding-left:0;" <?php foreach( $sval as $ck => $cv ){ echo ' data-' . $ck . '="'.$cv.'" '; } ?> data-color_of_gold-text="<?php if( isset( $sval["color_of_gold"] ) && isset( $color[ $sval["color_of_gold"] ] ) )echo $color[ $sval["color_of_gold"] ]; ?>" data-category-text="<?php if( isset( $cat[ $sval["category"] ] ) )echo $cat[ $sval["category"] ]; ?>" title="<?php echo $sval["description"]; ?>">

<div class="col-md-12" style="margin-left:0; padding-left:0;">
<div class="row" style="margin-left:0; padding-left:0;">
	<div class="col-xs-3" style="margin-left:0; padding-left:0;">
		<div class="item-image-1">
			<img src="<?php echo $site_url . $sval["image"]; ?>" id="<?php echo $sval["item"]; ?>-image" >
		</div>
	</div>
	<div class="col-xs-9">
	  <span class="b-c"><span class="badge quantity-select badge-roundless badge-success"></span></span>
		
	  <p class="item-title" id="<?php echo $sval["item"]; ?>-title">
		<?php echo $sval["description"]; ?>
	  </p>
	  
	  <code class="pull-right"><?php if( $sval["type"] == "service" ){ ?><strong>SERVICE<?php }else{ ?>IN-STOCK: <strong class="stock-levels"><?php echo format_and_convert_numbers( $q , 1 ); } ?></strong></code>
	  <?php if( $sval["barcode"] ){ ?> <code class="pull-right" style="color:#2725c7;"><?php echo $sval["barcode"]; ?></code> <?php } ?>
	  
	  <?php if( isset($sval["weight_in_grams"]) ){ ?><code class="pull-right" style="color:#2725c7;"><?php echo number_format( $sval["weight_in_grams"], 2 ); ?>g</code><?php } ?>
	  <p class="item-price">
		<span class="badge badge-primary pull-left" ><strong><?php if( $sval["type"] == "raw_materials" )echo format_and_convert_numbers( $sval["cost_price"], 4 ); else echo format_and_convert_numbers( $sval["selling_price"], 4 ); ?></strong></span>
	  </p>
	</div>
</div>
</div>

</a>