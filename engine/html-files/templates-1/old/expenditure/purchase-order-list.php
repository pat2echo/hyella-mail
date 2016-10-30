<?php
if( isset( $data['item'] ) && is_array( $data['item'] ) ){
	
	$intersect = array();
	if( isset( $data['item']["intersect"] ) && $data['item']["intersect"] ){
		foreach( $data['item']["intersect"] as $sval )
			$intersect[ $sval["id"] ] = 1;
	}
	unset( $data['item']["intersect"] );
	
	foreach( $data['item'] as $sval ){
		if( isset( $intersect[ $sval["id"] ] ) )continue;
		?>
		<option value="<?php echo $sval["id"]; ?>">#<?php echo $sval["serial_num"]; ?> <?php echo date( "j-M-Y" , doubleval( $sval["date"] ) ); ?></option>
		<?php
	}
	
	unset( $intersect );
	} 
?>