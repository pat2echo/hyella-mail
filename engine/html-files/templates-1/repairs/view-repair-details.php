<hr />
<?php 
	if( isset( $data[ "item" ] ) && is_array( $data[ "item" ] ) && isset( $data[ "fields" ] ) && is_array( $data[ "fields" ] ) && isset( $data[ "labels" ] ) && is_array( $data[ "labels" ] ) ){
		$pr = get_project_data();
?>
<div class="btn-group btn-group-justified">
	<?php 
		switch( $data[ "item" ][ "status" ] ){
		case 'return_to_customer':
	?>
	<?php break; ?>
	<?php case 'received_from_vendor': ?>
	<?php break; ?>
	<?php } ?>
</div>
<br />
<div class="row">
	<div class="col-md-8 col-sm-8">
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<tbody>
				<?php 
					foreach( $data[ "item" ] as $k => $v ){
						$value = $v;
						$break = 0;
						
						switch( $k ){
						case "image":	
						case "id":	
						case "serial_num":
							$break = 1;
						break;
						}
						
						if( $break )continue;
						?>
					   <tr>
						<td style="border-right:1px solid #ddd;"><?php
							$l = $k;
							$t = "";
							$t1 = "";
							if( isset( $data[ "labels" ][ $data[ "fields" ][ $k ] ] ) ){
								$l = $data[ "labels" ][ $data[ "fields" ][ $k ] ]['field_label'];
								$t = $data[ "labels" ][ $data[ "fields" ][ $k ] ]['form_field'];
								
							}
							
							switch( $t ){
							case "currency":
							case "decimal":
								$value = format_and_convert_numbers( $value, 4 );
							break;
							case "date-5":
							case "date":
								if( doubleval( $value ) )$value = date( "d-M-Y", doubleval( $value ) );
								else $value = "-";
							break;
							case "select":
								$t1 = $data[ "labels" ][ $data[ "fields" ][ $k ] ]['form_field_options'];
								$value = get_select_option_value( array( "id" => $value, "function_name" => $t1 ) );
							break;
							}
							
							echo $l; 
						?></td>
						<td><strong><?php echo $value; ?></strong></td>
					   </tr>
						<?php
					}
				?>
			</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-4 col-sm-4">
		<?php if( $data["item"][ "image" ] && file_exists( $pagepointer . $data["item"][ "image" ] ) ){ ?>
		<div >
			<a class="btn default btn-sm btn-block" href="#">Image</a>
			<img style="width:100%;" src="<?php echo $pr["domain_name"] . $data["item"][ "image" ]; ?>" />
		</div>
		<?php } ?>
		<button class="btn dark btn-block" onclick="nwRepairs.editItem();"><i class="icon-edit"></i> Edit Repair Job</button>
		<a class="btn red custom-single-selected-record-button btn-block" href="#" action="?module=&action=repairs&todo=update_repair_status" override-selected-record="<?php echo $data[ "item" ][ "id" ]; ?>" value="Update Repair Status" >Update Repair Status</a>
		<a class="btn dark custom-single-selected-record-button btn-block" href="#" action="?module=&action=repairs&todo=delete_app_record" override-selected-record="<?php echo $data[ "item" ][ "id" ]; ?>" value="Delete" ><i class="icon-trash"></i> Delete</a>
	</div>
</div>
<?php } ?>