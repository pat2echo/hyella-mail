<?php
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	
	$cat = get_items_categories();
	$color = array();
	
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
	
	if( isset( $data['stocked_items'] ) && is_array( $data['stocked_items'] ) ){
		if( isset( $data[ "barcode" ] ) && $data[ "barcode" ] ){
			$active = 1;
		}
			
		foreach( $data['stocked_items'] as $sval ){
			include dirname( dirname( __FILE__ ) ) . "/globals/inventory-list.php";
			if( isset( $active ) )unset( $active );
		}
	}
?>