<?php 
	if( isset( $data["item"] ) && $data["item"] ){
		$type = "";
		$sval = $data["item"];
		$class = "active";
		
		$customers = get_customers();
		$status = get_repairs_status();
		
		include "display-app-view/expense-list.php";
	}
?>