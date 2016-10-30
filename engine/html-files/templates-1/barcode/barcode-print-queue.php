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
	
	$show_all_buttons = 1;
	if( isset( $data["print_only_button"] ) && $data["print_only_button"] ){
		$show_all_buttons = 0;
	}
	
	if( isset( $data["barcodes"] ) && $data["barcodes"] ){
		if( ! $print ){
		?>
		<div class="btn-group btn-group-justified1">
			<a class="btn btn-sm default" href="<?php echo $pr["domain_name"]; ?>print.php?page=print-barcode&record_id=<?php if( isset( $data["print_queue"] ) && $data["print_queue"] )echo $data["print_queue"]; ?>" target="_blank" title="Print Barcodes"><i class="icon-print"></i> Print Barcodes</a>
			<?php if( $show_all_buttons ){ ?>
			<a class="btn btn-sm dark custom-single-selected-record-button" title="Delete All Barcodes in Queue" action="?module=&action=barcode&todo=empty_queue" override-selected-record="<?php if( isset( $data["print_queue"] ) && $data["print_queue"] )echo $data["print_queue"]; else echo 1; ?>" mod="1" href="#"><i class="icon-trash"></i> Empty Queue, this action cannot be reversed</a>
			<?php } ?>
		</div>
		<hr />
		<?php if( $show_all_buttons ){ ?>
		<div style="width:<?php echo $width - 3; ?>px; margin-top:-10px; clear:both;"><label class="pull-right" ><span><strong>Select All:</strong> <input class="check-all"  type="checkbox" style="height:15px; width:15px; margin-top:5px;" /></span></label></div><br /><br />
		<?php } ?>
		<div style="clear:both;"></div>
		<form class="activate-ajax" method="post" id="barcodes-form" action="">
		<div style="max-height:250px; background:#f7f7f7; overflow-y:auto;">
		<?php
		}

	$template = get_barcode_template_settings();
	switch( $template ){
	case "jewelry-label-with-cateogry-and-weight":
		include "barcode-print-queue-with-category.php";
	break;
	case "label-wazobia-1-9-by-0-85.php":
		include "barcode-print-queue-label-wazobia.php";
	break;
	case "label-wazobia-1-9-by-0-85.php":
		include "barcode-print-queue-label-wazobia.php";
	break;
	case "jewelry-label-with-cateogry-only":
		include "barcode-category-only.php";
	break;
	case "jewelry-label-with-cateogry-and-selling-price":
		include "barcode-category-and-selling-price.php";
	break;
	case "clothings-shipping-label-with-price":
		$show_price = 1;
		include "clothings-shipping-label.php";
	break;
	case "clothings-shipping-label":
		include "clothings-shipping-label.php";
	break;
	default:
		include "barcode-print-queue-default.php";
	break;
	}

	if( ! $print ){
		if( $show_all_buttons ){
	?>
	</div>
	<hr />
	<div class="btn-group btn-group-justified1">
		<a class="btn btn-sm green" title="Delete All Barcodes in Queue" id="update-barcode-quantities" href="#" action="?action=barcode&todo=update_quantities_in_queue"> Update Quantities to Print</a>
		<a class="btn btn-sm dark" title="Delete Selected Barcodes from Queue" id="delete-barcodes-from-queue" href="#" action="?action=barcode&todo=delete_barcodes_from_queue"><i class="icon-trash"></i> Delete Selected Barcodes</a>
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
		
		$('#update-barcode-quantities')
		.add('#delete-barcodes-from-queue')
		.on("click", function(e){
			e.preventDefault();
			
			$("form#barcodes-form")
			.attr("action", $(this).attr("action") )
			.submit();
		});
	</script>
	<?php
		}
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