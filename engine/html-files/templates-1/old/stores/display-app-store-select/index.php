<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	$selected = "";
	if( isset( $data['selected_store'] ) ){
		$selected = $data['selected_store'];
	}
	
	$package = "";
	if( isset( $data['package'] ) ){
		$package = $data['package'];
	}
	
	$main_store = "";
	if( isset( $data['main_store'] ) ){
		$main_store = $data['main_store'];
	}
	
	$map = array();
	if( function_exists("__map_store_locations") ){
		$map = __map_store_locations();
	}
	
	$style = '';
	if( defined("HYELLA_SINGLE_STORE") && HYELLA_SINGLE_STORE ){
		$style = ' display:none; ';
	}
	
	if( isset( $data['stores'] ) && is_array( $data['stores'] ) ){
		?>
		<select class="form-control" style="padding-top:2px; padding-bottom:2px; height:28px; font-size:12px; font-weight:bold; <?php echo $style; ?>">
		<?php		
		switch( $package ){
		case "hotel":
			foreach( $data['stores'] as $key => $val ){
				$file = "main.html";
				if( isset( $map[ $val["id"] ] ) && isset( $map[ $val["id"] ][ "file" ] ) && $map[ $val["id"] ][ "file" ] ){
					$file = $map[ $val["id"] ][ "file" ];
				}
				if( $val["name"] == "." ){
					$val["address"] = $main_store;
				}
				?><option value="<?php echo $val["id"]; ?>" data-file="<?php echo $file; ?>" <?php if( $selected == $val["id"] )echo ' selected="selected" '; ?> ><?php echo $val["address"]; ?></option><?php	
			}
		break;
		default:
			foreach( $data['stores'] as $key => $val ){
				$file = "main.html";
				if( isset( $map[ $val["id"] ] ) && isset( $map[ $val["id"] ][ "file" ] ) && $map[ $val["id"] ][ "file" ] ){
					$file = $map[ $val["id"] ][ "file" ];
				}

				?><option value="<?php echo $val["id"]; ?>" data-file="<?php echo $file; ?>" <?php if( $selected == $val["id"] )echo ' selected="selected" '; ?> ><?php echo $val["name"] . " (".$val["address"].")"; ?></option><?php
			}
		break;
		}
		?>
		</select>
		<?php
	}
?>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>