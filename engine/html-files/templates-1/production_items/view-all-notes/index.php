<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	$thead = "<thead><tr>
		<th>Date</th>
		<th>Note / Attachment</th>
	</tr></thead>";
	
	$all_bills = "";
	$serial_no = array();
	
	if( isset( $data['notes'] ) && is_array( $data['notes'] ) ){
		foreach( $data['notes'] as $bill ){
			
			$title = "";
			$last_col = "";
			
			$all_bills .= "<tr>
				<td><strong>Modified:</strong> ".date("d-M-Y", doubleval( $bill["modification_date"] ) )."<br /><br /><strong>Created:</strong> ".date("d-M-Y", doubleval( $bill["creation_date"] ) )."</td>
				<td>".$bill["note"]."<hr />".get_uploaded_files( $pagepointer, $bill["document"], "Attachment" )."</td>
			</tr>";
			
			//get_select_option_value( array( "id" => $bill["sponsor"], "function_name" => "get_members" ) )
		}
	}
?>
<div class="form-control-table">
<h4>Items Selected for Rent</h4>
<?php
	echo "<table class='table table-striped table-bordered table-hover'>" . $thead . "<tbody id='table-body-selected-items'>";
	if( $all_bills ){
		echo $all_bills;
	}
	echo "</tbody></table>";
?>
</div>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>