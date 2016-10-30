<style type="text/css">	
	.shopping-cart-table thead th{
		font-size:12px;
	}
	.shopping-cart-table tfoot th{
		font-size:14px;
	}

	.shopping-cart-table tfoot th,
	.shopping-cart-table thead th{
		background:#A7E862;
	}
	th.r,
	td.r{
	text-align:right;
	}
	th.c,
	td.c{
	text-align:center;
	}
	.shopping-cart-table .table-striped > tfoot > tr.use:nth-child(even) > td,
	.shopping-cart-table .table-striped > tbody > tr:nth-child(even) > td, .table-striped > tbody > tr:nth-child(even) > th{
		background:#DEF7C4;
	}
	.shopping-cart-table .table-striped > tfoot > tr.use:nth-child(odd) > td,
	.shopping-cart-table .table-striped > tbody > tr:nth-child(odd) > td, .table-striped > tbody > tr:nth-child(odd) > th{
		background:#f9f9f9;
	}
</style>
<?php
	if( isset( $data['emails'] ) && is_array( $data['emails'] ) && ! empty( $data['emails'] ) ){
		?>
		<div class="shopping-cart-table allow-scroll-1" style="background:transparent !important;">
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<thead>
			   <tr>
				  <th>S/N</th>
				  <th>Email</th>
			   </tr>
			</thead>
			<tbody>
		<?php
		$serial = 0;
		$status = '<span class="badge badge-roundless badge-danger">failed</span>';
		foreach( $data['emails'] as $email ){
			?>
			<tr>
				<td><?php echo ++$serial; ?></td>
				<td><?php echo $email["email"]; ?></td>
			</tr>
			<?php
		}
		?>		
		</tbody>
		<tfoot>
		   
		</tfoot>
		</table>
	</div>
	</div>
	<?php
	}else{
		?>
		<div class="note note-danger">
			<h4 class="block">Could not Retrieve Emails</h4>
			<p>Oops Something happened</p>
		</div>
		<?php
	}
?>