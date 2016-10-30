<tr class="<?php echo $class; ?> item-record custom-single-selected-record-button" id="<?php echo $sval["id"]; ?>" data-description="<?php echo $sval["description"]; ?>" data-image="<?php echo $sval["image"]; ?>" data-customer="<?php echo $sval["customer"]; ?>" data-amount_due="<?php echo $sval["amount_due"]; ?>" data-amount_paid="<?php echo $sval["amount_paid"]; ?>" data-date="<?php echo date( "d-M-Y" , doubleval( $sval["date"] ) ); ?>" data-status="<?php echo $sval["status"]; ?>" data-comment="<?php echo $sval["comment"]; ?>" data-cost_of_repair="<?php echo $sval["cost_of_repair"]; ?>" override-selected-record="<?php echo $sval["id"]; ?>" action="?action=repairs&todo=view_repair_details">
  <td>
	<?php echo date( "d-M-Y" , doubleval( $sval["date"] ) ); ?>
  </td>
  <td>
	<strong><?php echo ucwords( isset( $customers[ $sval["customer"] ] )?$customers[ $sval["customer"] ]:"" ); ?></strong><br />
	<?php echo $sval["description"]; ?><br /><br />
	<?php if( isset( $status[ $sval["status"] ] ) ){ ?><small>STATUS: <strong><?php echo $status[ $sval["status"] ]; ?></strong><br /></small><?php } ?>
	<i><?php echo $sval["comment"]; ?></i>
  </td>
  <td align="right">
	<strong><?php echo format_and_convert_numbers( $sval["amount_due"] - $sval["amount_paid"], 4 ); ?></strong><br />
  </td>
</tr>