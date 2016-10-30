<?php 
	if( isset( $data["item"] ) && $data["item"] ){
		$service = 0;
		if( isset( $data["service"] ) && $data["service"] )$service = $data["service"];
		
		$sval = $data["item"];
		$pr = get_project_data();
		$site_url = $pr["domain_name"];
		$active = 1;
		include dirname( dirname( __FILE__ ) ) . "/globals/inventory-list.php"; 
	}
?>