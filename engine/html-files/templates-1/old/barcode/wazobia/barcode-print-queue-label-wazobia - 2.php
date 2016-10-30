<body style="margin-top:0px; margin-bottom:0; padding:0;">
<?php 
	$pr = get_project_data();
	
	$link = $pr["domain_name"] . "files/barcode/";
	$count = 0;
	
	$height = 92;
	$width = 320;
	$print = 0;
	$dcss = " border-top:1px dotted #ddd; padding-top:10px; ";
	if( isset( $data["print_mode"] ) && $data["print_mode"] ){
		$print = $data["print_mode"];
		$width = 195;
		$height = 74;
		$dcss = "";
	}
	
	if( isset( $data["barcodes"] ) && $data["barcodes"] ){
		if( ! $print ){
		?>
		<div class="btn-group btn-group-justified1">
			<a class="btn btn-sm default" href="../?page=print-barcode&record_id=<?php if( isset( $data["print_queue"] ) && $data["print_queue"] )echo $data["print_queue"]; ?>" target="_blank" title="Print Barcodes"><i class="icon-print"></i> Print Barcodes</a>
			<a class="btn btn-sm dark custom-single-selected-record-button" title="Delete All Barcodes in Queue" action="?module=&action=barcode&todo=empty_queue" override-selected-record="<?php if( isset( $data["print_queue"] ) && $data["print_queue"] )echo $data["print_queue"]; else echo 1; ?>" mod="1" href="#"><i class="icon-trash"></i> Empty Queue, this action cannot be reversed</a>
		</div>
		<hr />
		<div style="width:<?php echo $width - 3; ?>px; margin-top:-10px; clear:both;"><label class="pull-right" ><span><strong>Select All:</strong> <input class="check-all"  type="checkbox" style="height:15px; width:15px; margin-top:5px;" /></span></label></div><br /><br />
		<div style="clear:both;"></div>
		<form method="post" action="" class="activate-ajax ">
		<?php
		}
		
		foreach( $data["barcodes"] as $key => $val ){
			
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
			
			<div style="height:80px; margin-bottom:3px;">
				<span style="width:100%; float:left; line-height:10px; text-align:center; font-size:10px;">
					<div style="text-align:left; font-size:12px;">SHOPWAZOBIA</div><br />
					<div style="text-align:left; font-size:9px; line-height:10px;">Baby shoes with cream dress Baby shoes with cream dress</div>
					<img src="<?php if( isset( $val[ "image" ] ) )echo $link . $val[ "image" ]; ?>" align="center" /><br /><span style="font-size:9px; margin-top:1px; display:block; font-weight:bold;"><?php if( isset( $val[ "text" ] ) )echo $val[ "text" ]; ?></span></span>
				</span>
				
				<?php if( ! $print ){ ?>
				<span style="width:100px; margin-left:50px; float:right;"> <input type="number" name="quantity[<?php echo $val["id"]; ?>]" value="<?php echo $qq; ?>" class="form-control pull-right1" style="float:left; margin-right:15px; width:65px;" placeholder="No. of Labels" /> <input type="checkbox" name="barcode" value="<?php echo $val["id"]; ?>" style="height:15px; width:15px;" /></span>
				<?php } ?>
				
			</div>
		</div>
				
			<?php
		
		}
		
		}

		?>
		
		<?php
		if( ! $print ){
		?>
		<hr />
		<div class="btn-group btn-group-justified1">
			<a class="btn btn-sm green custom-single-selected-record-button" title="Delete All Barcodes in Queue" action="?module=&action=cart&todo=checkout" id="cart-finish" href="#"> Save Changes</a>
			<a class="btn btn-sm dark custom-single-selected-record-button" title="Delete Selected Barcodes from Queue" action="?module=&action=cart&todo=checkout" href="#"><i class="icon-trash"></i> Delete Selected Barcodes</a>
		</div>
		
		</form>
		<script type="text/javascript">
			$("input.check-all")
			.on("change", function(){
				if( $(this).is(":checked") ){
					$('input[name="barcode"]')
					.attr( "checked", true );
				}else{
					$('input[name="barcode"]')
					.attr( "checked", false );
				}
				
			});
		</script>
		<?php
		}else{
			?>
			<script type="text/javascript">
				setTimeout( function(){ window.print(); }, 800 );
			</script>
			<?php
		}
	} 
?>
</body>