<?php 
	if( isset( $data['discount'] ) ){
		if( file_exists( dirname( dirname( __FILE__ ) ).'/globals/shopping-cart-discount-list.php' ) )
			include dirname( dirname( __FILE__ ) ).'/globals/shopping-cart-discount-list.php';
	}
?>