<?php

$data = array();
$group = array();
$group_guest = array();

$g_discount_after_tax = get_discount_after_tax_settings();

if( ! empty( $report_data["rooms"] ) ){
	foreach( $report_data["rooms"] as $sval ){
		$sval["type"] = "hotel_checkin";
		
		$out1 = doubleval( $sval["checkout_date"] );
		$in = doubleval( $sval["checkin_date"] );
		$out = get_date_difference( $out1, $in );
		$rate = ( $out * $sval["amount_due"] );
		
		$sval["room_discount"] = doubleval( $sval["room_discount"] );
		
		
		if( isset( $group_guest[ $sval["booking_ref"] ] ) ){
			$sval["discount"] = 0;
		}else{
			$group_guest[ $sval["booking_ref"] ] = 1;
		}
		
		if( $g_discount_after_tax ){
			$due =  $rate;
		}else{
			if( $sval["room_discount_percentage"] )$sval["room_discount"] += ( $sval["room_discount_percentage"] / 100 ) * $rate;
			$dis = $sval["discount"] + $sval["room_discount"];
			if( $sval["discount_percentage"] )
				$dis += ( $sval["discount_percentage"] / 100 ) * ( $out * $sval["amount_due"] );
			
			$due =  $rate - $dis;
		}
		
		if( $due <= 0 )$due = 0;
		
		$vat = 0;
		$service_charge = 0;
		$service_tax = 0;
		if( isset( $sval["vat"] ) && doubleval( $sval["vat"] ) )
			$vat = round( $due * $sval["vat"] / 100, 2 );
		
		if( isset( $sval["service_charge"] ) && doubleval( $sval["service_charge"] ) )
			$service_charge = round( $due * $sval["service_charge"] / 100, 2 );
		
		if( isset( $sval["service_tax"] ) && doubleval( $sval["service_tax"] ) )
			$service_tax = round( $due * $sval["service_tax"] / 100, 2 );
		
		if( $g_discount_after_tax ){
			$nrate = $due + $service_charge + $vat + $service_tax;
			if( $sval["room_discount_percentage"] )$sval["room_discount"] += ( $sval["room_discount_percentage"] / 100 ) * $nrate;
			
			$dis = $sval["discount"] + $sval["room_discount"];
			if( $sval["discount_percentage"] )
				$dis += ( $sval["discount_percentage"] / 100 ) * ( $nrate );
			
			$due = $due - $dis;
		}
		
		$sval[ "dis" ] = $dis;
		$sval[ "out" ] = $out;
		$sval[ "income" ] = $due + $service_charge + $vat + $service_tax;
		$sval[ "due_text" ] = number_format( $sval["amount_paid"] , 2 );
		
		$data[ $sval["id"] ][ $sval["room_checkin_id"] ] = $sval;
		
		if( ! isset( $data[ $sval["id"] ][ "total_paid" ] ) )$data[ $sval["id"] ][ "total_paid" ] = 0;
		if( ! isset( $data[ $sval["id"] ][ "total_due" ] ) )$data[ $sval["id"] ][ "total_due" ] = 0;
		
		$data[ $sval["id"] ][ "total_due" ] += $sval[ "income" ];
		
		if( ! isset( $group[ $sval["booking_ref"] ] ) )
			$data[ $sval["id"] ][ "total_paid" ] += $sval[ "amount_paid" ];
		
		$group[ $sval["booking_ref"] ] = 1;
	}
}

$discount_after_tax = get_sales_discount_after_tax_settings();

if( ! empty( $report_data["rooms_sales"] ) ){
	foreach( $report_data["rooms_sales"] as $sval ){
		$sval["type"] = 'sales';
		
		if( $discount_after_tax ){
			$due = $sval["amount_due"];
		}else{
			$due = $sval["amount_due"] - $sval["discount"];
		}
	
		$vat = 0;
		$service_charge = 0;
		$service_tax = 0;
		if( isset( $sval["vat"] ) && doubleval( $sval["vat"] ) )
			$vat = round( $due * $sval["vat"] / 100, 2 );
		
		if( isset( $sval["service_charge"] ) && doubleval( $sval["service_charge"] ) )
			$service_charge = round( $due * $sval["service_charge"] / 100, 2 );
		
		if( isset( $sval["service_tax"] ) && doubleval( $sval["service_tax"] ) )
			$service_tax = round( $due * $sval["service_tax"] / 100, 2 );
		
		$sval[ "income" ] = $due + $service_charge + $vat + $service_tax;
		if( $discount_after_tax ){
			$sval[ "income" ] = $sval[ "income" ] - $sval["discount"];
		}
		$sval[ "due_text" ] = number_format( $sval["amount_paid"] , 2 );
		
		$data[ $sval["booking_ref"] ][ $sval["id"] ] = $sval;
		
		if( ! isset( $data[ $sval["booking_ref"] ][ "total_paid" ] ) )$data[ $sval["booking_ref"] ][ "total_paid" ] = 0;
		if( ! isset( $data[ $sval["booking_ref"] ][ "total_due" ] ) )$data[ $sval["booking_ref"] ][ "total_due" ] = 0;
		
		$data[ $sval["booking_ref"] ][ "total_due" ] += $sval[ "income" ];
		
		if( $sval["payment_method"] == "cash_refund" ){
			$data[ $sval["booking_ref"] ][ "total_paid" ] -= $sval[ "income" ];
		}else{
			$data[ $sval["booking_ref"] ][ "total_paid" ] += $sval[ "amount_paid" ];
		}
	}
}

if( ! empty( $report_data["direct_sales"] ) ){
	foreach( $report_data["direct_sales"] as $sval ){
		$sval["type"] = 'sales';
		
		if( $discount_after_tax ){
			$due = $sval["amount_due"];
		}else{
			$due = $sval["amount_due"] - $sval["discount"];
		}
	
		$vat = 0;
		$service_charge = 0;
		$service_tax = 0;
		if( isset( $sval["vat"] ) && doubleval( $sval["vat"] ) )
			$vat = round( $due * $sval["vat"] / 100, 2 );
		
		if( isset( $sval["service_charge"] ) && doubleval( $sval["service_charge"] ) )
			$service_charge = round( $due * $sval["service_charge"] / 100, 2 );
		
		if( isset( $sval["service_tax"] ) && doubleval( $sval["service_tax"] ) )
			$service_tax = round( $due * $sval["service_tax"] / 100, 2 );
		
		$sval[ "income" ] = $due + $service_charge + $vat + $service_tax;
		if( $discount_after_tax ){
			$sval[ "income" ] = $sval[ "income" ] - $sval["discount"];
		}
		$sval[ "due_text" ] = number_format( $sval["amount_paid"] , 2 );
		
		$data[ $sval["id"] ][ $sval["id"] ] = $sval;
		
		if( ! isset( $data[ $sval["id"] ][ "total_paid" ] ) )$data[ $sval["id"] ][ "total_paid" ] = 0;
		if( ! isset( $data[ $sval["id"] ][ "total_due" ] ) )$data[ $sval["id"] ][ "total_due" ] = 0;
		
		$data[ $sval["id"] ][ "total_due" ] += $sval[ "income" ];
		$data[ $sval["id"] ][ "total_paid" ] += $sval[ "amount_paid" ];
		 
	}
}

unset( $report_data );
$pm = get_payment_method();
$pm["cash_refund"] = "Cash Refund";

?>