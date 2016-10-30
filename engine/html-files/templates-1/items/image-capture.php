<div style="position:absolute; right:16px; z-index:10;">
<a href="#" class="btn dark btn-sm pull-right" id="close-image-capture" onclick="<?php 
	$function = "nwInventory.closeImageCapture();";
	if( isset( $data[ "type" ] ) && $data[ "type" ] ){
		switch( $data[ "type" ] ){
		case "repairs":
			$function = "nwRepairs.closeImageCapture();";
		break;
		}
	}
	echo $function;
	?>return false;">Close</a>
<iframe src="<?php $pr = get_project_data(); echo $pr["domain_name"]; ?>html-files/templates-1/items/image-capture.html" style="border:none; /*width:192px; height:180px;*/ width:326px; height:274px; border: 3px solid #555; overflow:hidden;"></iframe>
</div>