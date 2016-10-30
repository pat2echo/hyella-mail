<?php
	if( isset( $data["top_title"] ) )echo $data["top_title"];
	
	if( isset( $data['html'] ) && $data['html'] ){
		echo $data['html'];
	}
?>