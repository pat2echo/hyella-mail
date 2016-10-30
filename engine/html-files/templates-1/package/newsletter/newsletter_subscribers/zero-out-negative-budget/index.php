<!--EXCEL IMPOT FORM-->
<div id="ajax-modal-container">
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
	$refresh_code = "";
	if( isset( $data["refresh_code"] ) && $data["refresh_code"] ){
		$refresh_code = $data["refresh_code"];
	}
	
	include dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . "/globals/zero-out-negative-budget.php"; 
?>
<script type="text/javascript">
	var refresh_code = "<?php echo $refresh_code; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>