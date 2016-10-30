<tr class="<?php echo $class; ?> item-record" id="<?php echo $sval["id"]; ?>" >
  <td>
	<?php echo date( "d-M-Y", doubleval( $sval["date"] ) ); ?>
  </td>
  <td>
	<strong><?php echo $sval["item"]; ?></strong><br />
	<?php echo $sval["description"]; ?>
	<?php if( $sval["comment"] ){ ?><br /><small><?php echo $sval["comment"]; ?></small><?php } ?>
  </td>
 <td>
	<?php echo date( "d-M-Y", doubleval( $sval["date_needed"] ) ); ?>
 </td>
 <td class="r">
	<?php if( isset( $e[ $sval["modified_by"] ] ) ){  echo $e[ $sval["modified_by"] ]; } ?>
 </td>
</tr>