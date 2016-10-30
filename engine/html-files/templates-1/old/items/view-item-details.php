<?php 
	if( isset( $data[ "item" ] ) && is_array( $data[ "item" ] ) && isset( $data[ "fields" ] ) && is_array( $data[ "fields" ] ) && isset( $data[ "labels" ] ) && is_array( $data[ "labels" ] ) ){
		$pr = get_project_data();
		
		$ap = 0;
		if( isset( $data[ "appraisal" ] ) && $data[ "appraisal" ] ){
			$ap = 1;
		}
		
		$cat = 0;
		if( isset( $data[ "catalogue" ] ) && $data[ "catalogue" ] ){
			$cat = 1;
		}
		
		$default_app_mark_up = 0;
		
		$data[ "item" ] = array_reverse( $data[ "item" ], true );
		
		foreach( $data[ "item" ] as $item => $item_data ){
			$default_app_mark_up = 25;
			
			if( isset( $item_data["percentage_markup"] ) && doubleval( $item_data["percentage_markup"] ) )
				$default_app_mark_up = doubleval( $item_data["percentage_markup"] );
			
			$dass = get_items_details( array( "id" => $item ) );
?>
<h4><?php echo $dass[ "description" ]; ?></h4>
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
						case "description":	
						case "image":	
						case "id":	
						case "serial_num":
							$break = 1;
						break;
						}
						
						if( $cat ){
							switch( $k ){
							case "percentage_markup":	
							case "cost_price":	
							case "type":
							case "low_stock":
								$break = 1;
							break;
							}
						}
						
						if( $ap ){
							switch( $k ){
							case "sub_category":	
							case "category":	
							case "barcode":
							case "source":
								$break = 1;
							break;
							}
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
					if( $ap ){
						?>
						 <tr>
						<td style="border-right:1px solid #ddd;"><strong>% Mark-up of Selling Price</strong></td>
						<td><input type="number" step="any" min="0" class="form-control percentage_markup" value="<?php echo $default_app_mark_up; ?>" data-id="<?php echo $dass["id"]; ?>" name="percentage_markup-<?php echo $dass["id"]; ?>" /></td>
					   </tr>
						 <tr>
						<td style="border-right:1px solid #ddd;"><strong>Appraised Value</strong></td>
						<td><input type="number" step="any" min="<?php echo $dass[ "selling_price" ]; ?>" class="form-control selling_price" value="<?php echo number_format( $dass[ "selling_price" ] + ( $dass[ "selling_price" ] * $default_app_mark_up / 100 ), 2 , ".", "" ); ?>" data-id="<?php echo $dass["id"]; ?>" name="selling_price-<?php echo $dass["id"]; ?>" />
						
						<input type="hidden" class="selling_price_only" value="<?php echo $dass[ "selling_price" ]; ?>" data-id="<?php echo $dass["id"]; ?>" name="selling_price_only-<?php echo $dass["id"]; ?>" /></td>
						</td>
					   </tr>
						<?php
					}
				?>
			</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-4 col-sm-4">
		<?php if( $dass[ "image" ] && file_exists( $pagepointer . $dass[ "image" ] ) ){ ?>
		<div >
			<a class="btn default btn-sm btn-block" href="#">Image</a>
			<img style="width:100%;" src="<?php echo $pr["domain_name"] . $dass[ "image" ]; ?>" />
		</div>
		<?php } ?>
		
	</div>
</div>
	<?php } ?>
<?php } ?>