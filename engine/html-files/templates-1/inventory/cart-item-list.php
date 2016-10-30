<?php 
	if( isset( $data['stocked_items'] ) && is_array( $data['stocked_items'] ) ){
		
		$pr = get_project_data();
		$site_url = $pr["domain_name"];
		
		if( isset( $data[ "barcode" ] ) && $data[ "barcode" ] ){
			$active = 1;
		}
		
		$type = "";
		if( isset( $data[ "type" ] ) && $data[ "type" ] ){
			$type = $data[ "type" ];
		}
		
		$package = "";
		if( defined( "HYELLA_PACKAGE" ) ){
			$package = HYELLA_PACKAGE;
		}
		
		//$file = "/cart/display-shopping-cart/cart-item-list.php";
		$file = "/cart/display-shopping-cart-advance/cart-item-list.php";
		
		switch( $package ){
		case "jewelry":	
			$file = "/cart/display-shopping-cart-jewelry/cart-item-list.php"; 
		break;
		}
		
		$unlimited_items = 1;
		/*
		$unlimited_items = 0;
		switch( $type ){
		case "purchase_item_search":
			$unlimited_items = 1;
		break;
		case "sales_order":
			$unlimited_items = get_unlimited_items_in_sales_order_settings();
		break;
		}
		*/
		foreach( $data['stocked_items'] as $sval ){
			$q = floor( $sval["quantity"] );
			$qpicked = 0;								
			if( isset( $sval["quantity_used"] ) )$q -= $sval["quantity_used"];
			if( isset( $sval["quantity_sold"] ) )$q -= $sval["quantity_sold"];
			if( isset( $sval["quantity_damaged"] ) )$q -= $sval["quantity_damaged"];
			if( isset( $sval["quantity_picked"] ) )$qpicked = $sval["quantity_picked"];
			
			$do_not_show_all = 0;
			$qty_left_after_order = 0;
			switch( $type ){
			case "sales_order":
				$do_not_show_all = 1;
				if( isset( $sval["quantity_ordered"] ) )$qty_left_after_order = $q - $sval["quantity_ordered"];
				if( $sval["quantity_ordered"] == $qpicked )$qty_left_after_order = 0;
			break;
			}
			$q -= $qpicked;
			
			if( $do_not_show_all && $sval["type"] == "service" ){
				continue;
			}
			
			if( ! $unlimited_items ){
				if( $sval["type"] != "service" )
					if( $q < 1 )continue;
			}
			
			include dirname( dirname( __FILE__ ) ) .  $file;
			if( isset( $active ) )unset( $active );
		}
		
	}
?>