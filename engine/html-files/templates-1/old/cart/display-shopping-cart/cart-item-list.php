<div class="col-md-4 col-sm-3 cart-item cart-item-select <?php if( isset( $active ) && $active )echo "active"; ?> <?php echo $sval["category"]." barcode-".$sval["barcode"] . " " . $sval["type"]; ?>" id="<?php echo $sval["item"]; ?>" data-type="<?php echo $sval["type"]; ?>" data-max="<?php echo $q; ?>" data-price="<?php if( $sval["type"] == "raw_materials" )echo $sval["cost_price"]; else echo $sval["selling_price"]; ?>" data-cost_price="<?php echo $sval["cost_price"]; ?>" title="<?php echo $sval["description"]; ?>">
	<div class="item-container">
		<span class="b-c"><span class="badge badge-success" ></span></span>
		<div class="item-image">
			<img src="<?php echo $site_url . $sval["image"]; ?>" >
		</div>
	  <p class="item-title">
		<?php echo $sval["description"]; ?>
	  </p>
	  <p class="item-price">
		<?php if( $sval["type"] == "raw_materials" )echo format_and_convert_numbers( $sval["cost_price"], 4 ); else echo format_and_convert_numbers( $sval["selling_price"], 4 ); ?>
	  </p>
	</div>
</div>