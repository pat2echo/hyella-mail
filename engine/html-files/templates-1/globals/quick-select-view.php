<?php 
	$s = 0;
	if( isset( $data[ 'months' ] ) && $data[ 'months' ] ){
		$option = '<select class="pull-right" id="select-month">';
		$sel = "";
		if( isset( $data[ 'months_selected_option' ] ) && $data[ 'months_selected_option' ] ){
			$sel = $data[ 'months_selected_option' ];
		}
		$option .= '<option value="all-months">All Months</option>';
		foreach( $data[ 'months' ] as $k => $v ){
			$option .= '<option value="' . $k . '"';
			if( $sel == $k ){ $option .= ' selected="selected" '; }
			$option .= '>' . $v . '</option>';
		}
		$option .= '</select>';
		echo $option;
		$s = 1;
	}
	
	if( isset( $data[ 'years' ] ) && $data[ 'years' ] ){
		$option = '<select class="pull-right" id="select-year">';
		$sel = "";
		if( isset( $data[ 'years_selected_option' ] ) && $data[ 'years_selected_option' ] ){
			$sel = $data[ 'years_selected_option' ];
		}
		foreach( $data[ 'years' ] as $k => $v ){
			$option .= '<option value="' . $k . '"';
			if( $sel == $k ){ $option .= ' selected="selected" '; }
			$option .= '>' . $v . '</option>';
		}
		$option .= '</select>';
		echo $option;
		$s = 1;
	}
	
	if( isset( $data[ 'table' ] ) && $s ){
		?>
		<a href="#" class="btn dark btn-sm custom-action-button" function-id="1" function-class="<?php echo $data[ 'table' ]; ?>" function-name="reload_datatable" title="Filter Data" year="" month="" id="select-change" style="display:none;">Go</a>
		<script type="text/javascript">
			$("select#select-year")
			.on("change", function(){
				$("a#select-change")
				.attr("year", $(this).val() )
				.attr("month", $("select#select-month").val() )
				.click();
			});
			
			$("select#select-month")
			.on("change", function(){
				$("select#select-year").change();
			});
		</script>
		<?php
	}
?>