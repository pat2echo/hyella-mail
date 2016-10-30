<?php 
	if( isset( $data[ "items" ] ) && is_array( $data[ "items" ] ) && isset( $data[ "fields" ] ) && is_array( $data[ "fields" ] ) && isset( $data[ "labels" ] ) && is_array( $data[ "labels" ] ) ){
		//$pr = get_project_data();
		
		foreach( $data[ "items" ] as $item => $item_data ){
			
			if( ! isset( $item_data["id"] ) )
				$dass = get_customer_wish_list_details( array( "id" => $item ) );
			else
				$dass = $item_data;
?>
<h4><?php echo $dass[ "item" ]; ?></h4>
<hr />
<div class="row">
	<div class="col-md-8 col-sm-8">
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<tbody>
				<?php 
					foreach( $dass as $k => $v ){
						$value = $v;
						$break = 0;
						
						switch( $k ){
						case "image":	
						case "id":	
						case "created_by":
						case "modified_by":
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
</div>
<button class="btn dark custom-single-selected-record-button" action="?module=&action=customer_wish_list&todo=new_popup_form_in_popup" override-selected-record="<?php echo $dass["customer"]; ?>">Add Another Item ?</button>
	<?php } ?>
<?php } ?>