<tr class="<?php echo $class; ?> item-record" id="<?php echo $sval["id"]; ?>" >
  <td>
	<?php echo date( "d-M-Y", doubleval( $sval["date"] ) ); ?>
  </td>
  <td>
	<strong><?php echo $sval["reason_for_call"]; ?></strong><br />
	<small>FEEDBACK:</small> <?php echo $sval["feedback"]; ?>
	<?php if( $sval["comment"] ){ ?><br /><small><?php echo $sval["comment"]; ?></small><?php } ?>
  </td>
 <td>
	<?php if( isset( $cat[ $sval["category"] ] ) ){  echo $cat[ $sval["category"] ]; } ?>
 </td>
 <td class="r">
	<?php if( isset( $e[ $sval["modified_by"] ] ) ){  echo $e[ $sval["modified_by"] ]; } ?>
 </td>
</tr>