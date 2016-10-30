<?php 
	if( isset( $data["item"] ) && $data["item"] ){
		$type = "";
		$sval = $data["item"];
		$class = "active";
		
		$types = get_type_of_vendor();
		
		include "display-app-view/expense-list.php";
	}
?>