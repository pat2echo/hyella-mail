<style type="text/css">
	.form-control-table table thead tr th,
	.form-control-table table{
		font-size:0.9em;
	}
	.form-control-table table tr th{
		background-color:#CCFFD2;/*#f4ccFF*/
		font-weight:bold;
	}
	.control-label{
		font-size:11px;
		font-weight:bold;
	}
</style>
<table id="discount-view-table" class="table table-striped table-bordered table-hover">
<thead>
	<tr>
		<th>S/N</th>
		<th>Description</th>
		<th>Discount Value</th>
		<th> </th>
	</tr>
</thead>
<tbody id="form-control-table-discount">
<?php
	if( isset( $data["items"] ) && is_array( $data["items"] ) && $data["items"] )
		include "form-control-view-row.php";
?>
</tbody>
</table>