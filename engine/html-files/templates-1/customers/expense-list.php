<?php 
	if( isset( $data["item"] ) && $data["item"] ){
		$type = "";
		$sval = $data["item"];
		$class = "active";
		
		include "display-app-view/expense-list.php";
	}
?>