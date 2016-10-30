<?php 
	if( isset( $data["item"] ) && $data["item"] ){
		
		//$type = get_product_types();
		$sval = $data["item"];
		$class = "active";
		
		include "display-app-view/expense-list.php";
	}
?>