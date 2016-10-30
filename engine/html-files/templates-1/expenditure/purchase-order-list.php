<?php
$prefix = 'P';
if( isset( $data['item'] ) && is_array( $data['item'] ) ){
	
	$intersect = array();
	if( isset( $data['item']["intersect"] ) && $data['item']["intersect"] ){
		foreach( $data['item']["intersect"] as $sval )
			$intersect[ $sval["id"] ] = 1;
	}
	unset( $data['item']["intersect"] );
	
	$skip_paid1 = 0;
	if( isset( $skip_paid ) && $skip_paid )$skip_paid1 = 1;
	?>
	<option value="">-Select-</option>
	<?php
	foreach( $data['item'] as $sval ){
		if( isset( $intersect[ $sval["id"] ] ) )continue;
		$a = $sval["amount_due"] - $sval["total_amount_paid"];
		
		if( $skip_paid1 && ! $a )continue;
		?>
		<option value="<?php echo $sval["id"]; ?>" data-amount_owed="<?php echo $a; ?>">#<?php echo mask_serial_number( $sval["serial_num"], $prefix ); ?> <?php echo date( "j-M-Y" , doubleval( $sval["date"] ) ); ?></option>
		<?php
	}
	
	unset( $intersect );
	} 
?>