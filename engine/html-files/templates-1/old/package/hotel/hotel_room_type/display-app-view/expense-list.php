<tr class="<?php echo $class; ?> item-record" id="<?php echo $sval["id"]; ?>" data-name_of_vendor="<?php echo $sval["name_of_vendor"]; ?>" data-phone="<?php echo $sval["phone"]; ?>" data-email="<?php echo $sval["email"]; ?>" data-address="<?php echo $sval["address"]; ?>" data-comment="<?php echo $sval["comment"]; ?>" data-type="<?php echo $sval["type"]; ?>">
  <td>
	<strong><?php echo ucwords( strtolower( $sval["name_of_vendor"] ) ); ?></strong><br />
	{<a href="tel:<?php echo $sval["phone"]; ?>"><?php echo $sval["phone"]; ?></a>}
  </td>
  <td>
	<?php echo $sval["address"]; ?><br />
	{<a href="mailto:<?php echo $sval["email"]; ?>"><?php echo $sval["email"]; ?></a>}
  </td>
   
  <td>
	<?php if( isset( $types[ $sval["type"] ] ) ){ ?><strong><?php echo $types[ $sval["type"] ]; ?></strong><br /><?php } ?>
	<i><?php echo $sval["comment"]; ?></i>
 </td>
</tr>