<div>
	<?php
		if( isset( $data['result'] ) && $data['result'] && is_array( $data['result'] )  ){ 
		?>
		<style type="text/css">
			.form-control-table table thead tr th,
			.form-control-table table{
				font-size:0.9em;
			}
			.form-control-table table tr th{
				background-color:#CCFFD2;/*#f4ccFF*/
				font-weight:bold;
			}
			
		</style>
		<div class="form-control-table">
		<table id="work-history-view-table" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>S/N</th>
				<th>Info</th>
				<th>Membership Status</th>
				<th>Registration Number</th>
			</tr>
		</thead>
		<tbody >
		<?php
			$serial = 0;
			
			foreach( $data['result'] as $r => $val ){
				?>
				<tr >
			<td><?php echo ++$serial; ?></td>
			<td><?php echo $val["title"] . " " . $val["firstname"] . " " . $val["lastname"]; ?>
			<br /><?php echo $val["email"]; ?></td>
			<td><?php echo get_select_option_value( array( "id" => $val["registration_status"], "function_name" => "get_registration_status" ) ); ?></td>
			<td><?php if( $val["registration_number"] )echo $val["registration_number"]; ?></td>
			</tr>
				<?php
			}
			?>
			</tbody>
			</table>
			</div>
			<?php
		}else{
			?>
			<div class="alert alert-info">
				<h4>NO RESULT FOUND</h4>
				<p>The Search did not find any match</p>
			</div>
			<?php
			
		}
		
	?>
</div>