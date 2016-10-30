<button type="button" title="Select Another Date" class="custom-action-button btn btn-sm pull-right" function-id="1" function-class="events" function-name="display_calendar_view_mobile" operator-id="-" department-id="-" budget-id="<?php echo date("U"); ?>" month-id="monthly"><i class="icon-calendar"></i> Select Another Date</button>
<span style="font-size:1.2em;"><?php 
	if( isset( $data["date"] ) )echo date("D jS M, Y", $data["date"] );
?></span>
<br />
<label style="margin-top:10px;">
	<?php 
		$add_script = 1;
		if( isset( $data["other_items"] ) && is_array( $data["other_items"] ) && ! empty( $data["other_items"] ) ){ 
			$add_script = 0;
			foreach( $data["other_items"] as $items ){
				echo get_select_option_value( array( "id" => $items["item_id"] , "function_name" => "get_other_requirements" ) ).", ";
			}
		}else{ ?>
	Hall Price: <strong><?php if( isset( $data["hall_price"] ) )echo convert_currency( $data["hall_price"] ); ?> <?php $k = 'hall_price_unit'; $unit = ""; if( isset( $data[$k] ) ){ $unit = $data[$k]; echo get_select_option_value( array( "id" => $data[$k], "function_name" => "get_unit_types" ) ); } ?></strong>
	<?php } ?>
</label>
<hr style="margin-top:0;" />
<?php if( isset( $data['form'] ) )echo $data['form']; ?>

<?php if( $add_script ){ ?>
<script type="text/javascript">
	$(".control-group.duration-label")
	.find("label")
	.text('<?php switch($unit){
		case 'hourly':
			echo "Duration ( No. of Hours )";
		break;
		case 'daily':
			echo "Duration ( No. of Days )";
		break;
	} ?>');
</script>
<?php } ?>