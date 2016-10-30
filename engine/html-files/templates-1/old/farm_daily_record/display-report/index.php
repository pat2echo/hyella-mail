<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	if( isset( $data[ "report_data" ] ) && isset( $data[ "report_type" ] ) && file_exists( dirname( __FILE__ ) . "/" . $data[ "report_type" ].".php" ) ){
		$title = "";
		if( isset( $data["report_title"] ) )$title = $data["report_title"];
		
		$report_data = $data[ "report_data" ];
		include $data[ "report_type" ].".php";
	}
?>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>