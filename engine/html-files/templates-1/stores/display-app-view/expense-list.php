<tr class="<?php echo $class; ?> item-record" id="<?php echo $sval["id"]; ?>" data-name="<?php echo $sval["name"]; ?>" data-phone="<?php echo $sval["phone"]; ?>" data-email="<?php echo $sval["email"]; ?>" data-address="<?php echo $sval["address"]; ?>" data-comment="<?php echo $sval["comment"]; ?>">
  <td>
	<strong><?php echo ucwords( strtolower( $sval["name"] ) ); ?></strong><br />
	{<a href="tel:<?php echo $sval["phone"]; ?>"><?php echo $sval["phone"]; ?></a>}
  </td>
  <td>
	<?php echo $sval["address"]; ?><br />
	{<a href="mailto:<?php echo $sval["email"]; ?>"><?php echo $sval["email"]; ?></a>}
  </td>
   
  <td ><?php echo $sval["comment"]; ?></td>
</tr>