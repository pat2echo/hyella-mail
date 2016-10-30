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
	$no = 1;
	if( isset( $data['emails'] ) && is_array( $data['emails'] ) && ! empty( $data['emails'] ) ){
		$no = 0;
		$failed = array();
		if( isset( $data['failed_emails'] ) && is_array( $data['failed_emails'] ) && ! empty( $data['failed_emails'] ) ){
			foreach( $data['failed_emails'] as $email ){
				$failed[ $email ] = 1;
			}
		}
		?>
		<div class="shopping-cart-table allow-scroll-1" style="background:transparent !important;">
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<thead>
			   <tr>
				  <th>S/N</th>
				  <th>Email</th>
				  <th class="r">Status</th>
			   </tr>
			</thead>
			<tbody>
			<?php
			$serial = 0;
			$status = '<span class="badge badge-roundless badge-success">success</span>';
			foreach( $data['emails'] as $email ){
				if( isset( $failed[ $email["email"] ] ) ){
					$status = '<span class="badge badge-roundless badge-danger">failed</span>';
				}
				?>
				<tr>
					<td><?php echo ++$serial; ?></td>
					<td><?php echo $email["email"]; ?></td>
					<td class="r"><?php echo $status; ?></td>
				</tr>
				<?php
			}
			?>		
			</tbody>
			</table>
		</div>
		</div>
		<?php
	}else{
		if( isset( $data['failed_emails'] ) && is_array( $data['failed_emails'] ) && ! empty( $data['failed_emails'] ) ){
			$no = 0;
			?>
			<div class="shopping-cart-table allow-scroll-1" style="background:transparent !important;">
			<div class="table-responsive">
				<table class="table table-striped table-hover bordered">
				<thead>
				   <tr>
					  <th>S/N</th>
					  <th>Email</th>
					  <th class="r">Status</th>
				   </tr>
				</thead>
				<tbody>
			<?php
			$serial = 0;
			$status = '<span class="badge badge-roundless badge-danger">failed</span>';
			foreach( $data['failed_emails'] as $email ){
				?>
				<tr>
					<td><?php echo ++$serial; ?></td>
					<td><?php echo $email; ?></td>
					<td class="r"><?php echo $status; ?></td>
				</tr>
				<?php
			}
			?>		
			</tbody>
			</table>
		</div>
		</div>
		<?php
		}
	}
	if( $no ){
		?>
		<div class="note note-danger">
			<h4 class="block">Could not Retrieve Emails</h4>
			<p>Oops Something happened</p>
		</div>
		<?php
	}
?>