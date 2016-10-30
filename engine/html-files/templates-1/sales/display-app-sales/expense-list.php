<?php 
	$due = $sval["amount_due"];
	$a = $due - round( $sval["amount_paid"], 2 );
?>
<tr class="<?php echo $class; ?> item-sales custom-single-selected-record-button" action="?module=&action=sales&todo=view_invoice_app" override-selected-record="<?php echo $sval["id"]; ?>" id="<?php echo $sval["id"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-quantity="<?php echo $sval["quantity_sold"]; ?>" data-cost="<?php echo $sval["amount_due"]; ?>" data-discount="<?php echo $sval["discount"]; ?>" data-amount_due="<?php echo $sval["amount_due"]; ?>" data-amount_paid="<?php echo $sval["amount_paid"]; ?>" data-customer="<?php echo $sval["customer"]; ?>" data-store="<?php echo $sval["store"]; ?>">
  <td>
	<?php echo date( $date_filter , doubleval( $sval["date"] ) ); ?> 
	<?php if( isset( $sval["sales_status"] ) && $sval["sales_status"] == "booked" ){ ?><br /><br /><small><small><span class="label label-info"><i class="icon-pushpin"></i> booked</span></small></small><?php } ?>
  </td>
  <td>
	<?php if( $date_filter != "F, Y" ){ ?>
	#<strong><?php echo mask_serial_number( $sval["serial_num"] , 'S' ); ?></strong>
	<br />
	<?php } ?>
	
	Units Sold: <strong><?php echo $sval["quantity_sold"]; ?></strong><?php echo ( isset( $customers[ $sval["customer"] ] )?("<br /><strong>".$customers[ $sval["customer"] ]."</strong>"):"" ); ?>
  </td>
   
  <td class="r"><?php echo format_and_convert_numbers( $due, 4 ); ?></td>
  <td class="r"><?php echo format_and_convert_numbers( $a, 4 ); ?></td>
 
</tr>