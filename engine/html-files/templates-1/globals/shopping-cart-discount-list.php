<option value="0">-Select Discount-</option>
<?php
	if( isset( $data['discount'] ) && is_array( $data['discount'] ) && ! empty( $data['discount'] ) ){
		foreach( $data['discount'] as $key => $val ){
			?>
			<option data-type="<?php echo $val["type"]; ?>" value="<?php echo $val["id"]; ?>" data-value="<?php echo $val["value"]; ?>">
				<?php 
					switch( $val["type"] ){
					case 'surcharge_percentage':
					case 'percentage_after_tax':
					case 'percentage':
						echo $val["name"] . ' ( '.format_and_convert_numbers( $val["value"], 4 ).' % )';
					break;
					default:
						echo $val["name"] . ' ( '.format_and_convert_numbers( $val["value"], 4 ).' )';
					break;
					}
				?>
			</option>
			<?php
		}
	}
?>