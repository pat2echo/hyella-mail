<?php
if( isset( $data['sales_record'] ) && is_array( $data['sales_record'] ) && ! empty( $data['sales_record'] ) ){	
	?>
	<option value="">None</option>
	<?php
	foreach( $data['sales_record'] as $sval ){
		if( $sval["amount_paid"] >= $sval["amount_due"] )continue;
		
		$owed = $sval["amount_due"] - $sval["amount_paid"];
	?>
	<option value="<?php echo $sval["id"]; ?>" data-amount_owed="<?php echo $owed; ?>">#<?php echo mask_serial_number( $sval["serial_num"], 'S' ); ?> - <?php echo date( "d-M-Y", doubleval( $sval["date"] ) ); ?></option>
	<?php
	}
} 
?>