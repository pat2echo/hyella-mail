<a class="item cart-item-select" href="#" id="<?php echo $sval["id"]; ?>-container">
<div class="col-md-12 room-type-item room-type-select" style="padding-left:0;" id="<?php echo $sval["id"]; ?>" data-rate="<?php echo $sval["rate"]; ?>" data-deposit_amount="<?php echo $sval["deposit_amount"]; ?>" data-max_adults="<?php echo $sval["max_adults"]; ?>" data-max_children="<?php echo $sval["max_children"]; ?>" data-name="<?php echo $sval["name"]; ?>">

<div class="row" style="margin-left:0;">
	<div class="col-xs-4" style="margin-left:0; padding-left:0;">
		<div class="item-image-1">
			<img src="<?php echo $site_url . $sval["picture"]; ?>" id="<?php //echo $sval["item"]; ?>-image" >
		</div>
	</div>
	<div class="col-xs-8" style="margin-left:0; padding-left:0;">
	  <p class="item-price pull-right">
		<?php echo format_and_convert_numbers( $sval["rate"], 4 ); ?> / night
	  </p>
	  
	  <p class="item-title" id="<?php echo $sval["id"]; ?>-title">
		<?php echo $sval["name"]; ?>
	  </p>
	  
	  <div class="features-list">
		<?php echo $sval["features"]; ?>
	  </div>
	  
	  <span class="pull-right badge badge-primary"><strong class="stock-levels"><?php echo format_and_convert_numbers( $sval["ROOMS_AVAILABLE"] - $sval["ROOMS_BOOKED"] , 1 ); ?> Room(s) Left</strong></span>
	  
	</div>
</div>
</div>
</a>