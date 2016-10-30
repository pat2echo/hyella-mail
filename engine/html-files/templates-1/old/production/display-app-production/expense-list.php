<tr class="<?php echo $class; ?> item-sales custom-single-selected-record-button" action="?module=&action=production&todo=view_invoice_app" override-selected-record="<?php echo $sval["id"]; ?>" id="<?php echo $sval["id"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-quantity="<?php echo $sval["quantity"]; ?>" data-cost="<?php echo $sval["cost"]; ?>" data-extra_cost="<?php echo $sval["extra_cost"]; ?>" data-factory="<?php echo $sval["factory"]; ?>" data-status="<?php echo $sval["status"]; ?>" data-staff_responsible="<?php echo $sval["staff_responsible"]; ?>" data-store="<?php echo $sval["store"]; ?>">
  <td><?php echo date( $date_filter , doubleval( $sval["date"] ) ); ?></td>
  <td>
	<?php if( $sval["staff_responsible"] == "*Several" ){
		echo $sval["staff_responsible"]; 
	}else{ echo "#" . $sval["serial_num"] ."-". $sval["id"]; ?><?php echo ( $sval["comment"] )?("<br />".$sval["comment"]):""; ?>
		<?php echo ( isset( $status[ $sval["status"] ] )?("<br />{<strong>".$status[ $sval["status"] ]."</strong>}"):$sval["status"] ); 
		}
	?>
  </td>
   
  <td class="r"><?php $cost = $sval["cost"] + $sval["extra_cost"]; echo format_and_convert_numbers( $cost, 4 ); ?></td>
  <td class="r"><?php echo ( isset( $customers[ $sval["staff_responsible"] ] )?( $customers[ $sval["staff_responsible"] ] ):$sval["staff_responsible"] ); ?></td>
 
</tr>