<?php 
	if( isset( $data["report_data"] ) && is_array( $data["report_data"] ) && ! empty( $data["report_data"] ) ){
		$type = "";
		if( isset( $data["type"] ) )$type = $data["type"];
		
		include "display-app-expenditure/expense-function.php";
		__expenses( $data["report_data"], $type );
	}
?>