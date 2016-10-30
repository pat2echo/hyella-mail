<?php 
	if( isset( $data['accounts'] ) && is_array( $data['accounts'] ) && ! empty( $data['accounts'] ) ){
		foreach( $data['accounts'] as $key => $val ){
			?><option value="<?php echo $key; ?>"><?php echo $val; ?></option><?php
		}
	}
?>