<a class="item cart-item cart-item-select <?php if( isset( $active ) && $active )echo "active"; ?> <?php echo $sval["category"]." barcode-".$sval["barcode"]; ?>" href="#" id="<?php echo $sval["item"]; ?>" title="<?php echo $sval["description"]; ?>" data-barcode="<?php echo $sval["barcode"]; ?>" data-max="<?php echo $q; ?>" data-price="<?php echo $sval["selling_price"]; ?>" data-cost="<?php echo $sval["cost_price"]; ?>" data-cost_price="<?php echo $sval["cost_price"]; ?>" <?php foreach( $sval as $ck => $cv ){ echo ' data-' . $ck . '="'.$cv.'" '; } ?> >
<div class="col-md-12 " style="margin-left:0; padding-left:0;">
<div class="row" style="margin-left:0; padding-left:0;">
	<div class="col-xs-4" style="margin-left:0; padding-left:0;">
		<div class="item-image-1">
			<img src="<?php echo $site_url . $sval["image"]; ?>" id="<?php echo $sval["item"]; ?>-image" >
		</div>
	</div>
	<div class="col-xs-8" style="margin-left:0; padding-left:0;">
		<span class="b-c"><span class="badge quantity-select badge-roundless badge-success"></span></span>
	  <p class="item-title" id="<?php echo $sval["item"]; ?>-title">
		<?php echo $sval["description"]; ?>
	  </p>
	  <code class="pull-right" style="color:#2725c7;"><?php echo $sval["barcode"]; ?></code><code class="pull-right"><?php echo $sval["weight_in_grams"]; ?>g</code>
	  <p class="item-price">
		<span class="badge badge-primary pull-left" ><strong><?php echo format_and_convert_numbers( $sval["selling_price"] , 4 ); ?></strong></span>
	  </p>
	</div>
</div>
</div>
</a>