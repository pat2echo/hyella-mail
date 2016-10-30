<?php $budget = ""; if( isset( $data['id'] ) && $data['id'] )$budget = $data['id']; ?>
<div id="form-container">
<?php
	if( isset( $data["form"] ) && $data["form"] )echo $data["form"];
	
	$no_all_delete = 0;
	if( isset( $data[ 'no_delete_all' ] ) )
		$no_all_delete = $data[ 'no_delete_all' ];
		
?>
</div>
<hr />
<div class="form-control-table">
<?php
	include "form-control-view-table.php";
?>
</div>
		