<?php 
	if( isset( $data["item"] ) && $data["item"] ){
		
		$sval = $data["item"];
		$pr = get_project_data();
		$site_url = $pr["domain_name"];
		$active = 1;
		
		$color = array();
		
		$cat = get_items_categories();
		
		$package = "";
		if( defined( "HYELLA_PACKAGE" ) ){
			$package = HYELLA_PACKAGE;
		}
		
		switch( $package ){
		case "hotel":
		break;
		case "jewelry":
			$color = get_color_of_gold();
		break;
		}
	
		include dirname( dirname( __FILE__ ) ) . "/globals/inventory-list.php"; 
	}
?>