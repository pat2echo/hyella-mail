<?php
	if( ( isset( $data["selected_record"] ) && $data["selected_record"] ) ){
?>
		<style type="text/css">
			<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
		</style>
		<?php
			if( isset( $data["html"] ) && $data["html"] )echo $data["html"];
		?>
		<input type="hidden" id="rename-selected-record" value="<?php echo $data["selected_record"]; ?>" />
		<script type="text/javascript">
			<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
		</script>
<?php
	}
?>