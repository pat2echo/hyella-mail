<?php 
	if( $g_discount_after_tax ){
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
	
	$due += $service_charge + $vat + $service_tax;
	if( $g_discount_after_tax ){
		$due = $due - $sval["discount"];
	}
	$a = $due - round( $sval["amount_paid"], 2 );
?>
<tr class="<?php echo $class; ?> item-sales custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app" override-selected-record="<?php echo $sval["id"]; ?>" id="<?php echo $sval["id"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-quantity="<?php echo $sval["quantity_sold"]; ?>" data-cost="<?php echo $sval["amount_due"]; ?>" data-discount="<?php echo $sval["discount"]; ?>" data-amount_due="<?php echo $sval["amount_due"]; ?>" data-amount_paid="<?php echo $sval["amount_paid"]; ?>" data-customer="<?php echo $sval["customer"]; ?>" data-store="<?php echo $sval["store"]; ?>">
  <td>
	<?php echo date( $date_filter , doubleval( $sval["date"] ) ); ?> 
	<?php if( isset( $sval["sales_status"] ) && $sval["sales_status"] == "booked" ){ ?><br /><br /><small><small><span class="label label-info"><i class="icon-pushpin"></i> booked</span></small></small><?php } ?>
  </td>
  <td>
	<?php if( $date_filter != "F, Y" ){ ?>
	#<strong><?php echo $sval["serial_num"]; ?></strong>
	<br />
	<?php } ?>
	
	Units Sold: <strong><?php echo $sval["quantity_sold"]; ?></strong><?php echo ( isset( $customers[ $sval["customer"] ] )?("<br /><strong>".$customers[ $sval["customer"] ]."</strong>"):"" ); ?>
  </td>
   
  <td class="r"><?php echo format_and_convert_numbers( $due, 4 ); ?></td>
  <td class="r"><?php echo format_and_convert_numbers( $a, 4 ); ?></td>
 
</tr>