<?php 
	if( isset( $data["item"] ) && $data["item"] ){
		$type = "";
		$sval = $data["item"];
		$categories = get_types_of_expenditure();
		$vendors = get_vendors();
		$date_filter = "d-M-Y";
		$class = "active";
		
		include "display-app-expenditure/expense-list.php";
	}
?>