<?php
	foreach( $data["barcodes"] as $key => $val ){
		
		$qq = $val["quantity"];
		if( ! $print )$val["quantity"] = 1;
		
		for( $x = 0; $x < $val["quantity"]; $x++ ){
		++$count;
	if( ( $count % 2 ) ){
	?>
	<?php if( $count > 1 ){ ?>
	<div style="page-break-before:always; height:4px;" ></div>
	<div style="margin-top:0px; width:<?php echo $width; ?>px; height:<?php echo $height; ?>px; font-size:12px; font-family:arial; margin-bottom:0; padding:0; <?php echo $dcss; ?>">
	<?php }else{ ?>
	<div style="margin-top:4px; width:<?php echo $width; ?>px; height:<?php echo $height; ?>px; font-size:12px; font-family:arial; margin-bottom:0; padding:0; <?php echo $dcss; ?>">
	<?php } ?>
	
		<div style="height:34px; margin-bottom:5px;">
			<span style="width:60px; float:left; line-height:15px; text-align:center; font-size:11px;"><?php if( isset( $val[ "price" ] ) )echo $val[ "price" ]; ?><?php if( isset( $val[ "weight" ] ) )echo '<br />' . $val[ "weight" ]; ?></span>
			
			<?php if( ! $print ){ ?>
			<?php if( $show_all_buttons ){ ?>
			<span style="width:100px; margin-left:50px; float:right;"> <input type="number" min="1" step="1" name="quantity[<?php echo $val["text"]; ?>]" value="<?php echo $qq; ?>" class="form-control pull-right1" style="float:left; margin-right:15px; width:65px;" placeholder="No. of Labels" /> <input type="checkbox" name="barcode[<?php echo $val["text"]; ?>]" value="<?php echo $val["text"]; ?>" style="height:15px; width:15px;" /></span>
			<?php } ?>
			<?php } ?>
			
			<span style="width:75px; float:right; text-align:center;"><img src="<?php if( isset( $val[ "image" ] ) )echo $link . $val[ "image" ]; ?>" align="center" /><br /><span style="font-size:8px; margin-top:1px; display:block; font-weight:bold;"><?php if( isset( $val[ "text" ] ) )echo $val[ "text" ]; ?></span></span>
			
		</div>
			
		<?php
	}else{
		?>
		<div style="height:34px; margin-top:10px; margin-bottom:0;">
			<span style="width:60px; float:left; line-height:15px; text-align:center; font-size:11px;"><?php if( isset( $val[ "price" ] ) )echo $val[ "price" ]; ?><?php if( isset( $val[ "weight" ] ) )echo '<br />' . $val[ "weight" ]; ?></span>
			
			<?php if( ! $print ){ ?>
			<?php if( $show_all_buttons ){ ?>
			<span style="width:100px; margin-left:50px; float:right;"> <input type="number" min="1" step="1" name="quantity[<?php echo $val["text"]; ?>]" value="<?php echo $qq; ?>" class="form-control pull-right1" style="float:left; margin-right:15px; width:65px;" placeholder="No. of Labels" /> <input type="checkbox" name="barcode[<?php echo $val["text"]; ?>]" value="<?php echo $val["text"]; ?>" style="height:15px; width:15px;" /></span>
			<?php } ?>
			<?php } ?>
			
			<span style="width:75px; float:right; text-align:center;"><img src="<?php if( isset( $val[ "image" ] ) )echo $link . $val[ "image" ]; ?>" align="center" /><br /><span style="font-size:8px; margin-top:1px; display:block; font-weight:bold;"><?php if( isset( $val[ "text" ] ) )echo $val[ "text" ]; ?></span></span>
		</div>
	</div>
		<?php
		}
	}
	
	}
?>