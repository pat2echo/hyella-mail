<tr class="<?php echo $class; ?> item-record" id="<?php echo $sval["id"]; ?>" data-name="<?php echo $sval["name"]; ?>" data-type="<?php echo $sval["type"]; ?>">
  <td>
	<strong><?php echo ucwords( strtolower( $sval["name"] ) ); ?></strong>
  </td>
  <td>
	<strong><?php echo ( isset( $type[ $sval["type"] ] )?$type[ $sval["type"] ]:$sval["type"] ); ?></strong>
  </td>
</tr>