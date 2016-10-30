<?php
if( isset( $data['sales_record'] ) && is_array( $data['sales_record'] ) && ! empty( $data['sales_record'] ) ){	
	?>
	<option value="">None</option>
	<?php
	foreach( $data['sales_record'] as $sval ){
		if( $sval["amount_paid"] >= $sval["amount_due"] )continue;
	?>
	<option value="<?php echo $sval["id"]; ?>"><?php echo $sval["serial_num"]; ?> - <?php echo $sval["id"]; ?></option>
	<?php
	}
} 
?>