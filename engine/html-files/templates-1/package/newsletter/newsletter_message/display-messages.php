<?php							
	if( isset( $data[ "messages" ] ) && $data[ "messages" ] ){
		foreach( $data[ "messages" ] as $msg ){
			$data["message"] = $msg;
			include "display-message.php";
		}
		echo '<hr />';
	}
?>