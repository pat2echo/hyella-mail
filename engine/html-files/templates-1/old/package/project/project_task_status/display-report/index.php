<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<div id="report-preview-container-id">
<?php 
	if( isset( $data[ "report_data" ] ) && isset( $data[ "report_type" ] ) && file_exists( dirname( __FILE__ ) . "/" . $data[ "report_type" ].".php" ) ){
		
		echo get_export_and_print_popup( ".table" , "#quick-print-container", "" ) . "</div>";
		
		$title = "";
		if( isset( $data["report_title"] ) )$title = $data["report_title"];
		
		$subtitle = "";
		if( isset( $data["report_subtitle"] ) )$subtitle = $data["report_subtitle"];
		
		$report_data = $data[ "report_data" ];
		echo '<div id="quick-print-container">';
			include $data[ "report_type" ].".php";
		echo '</div>';
	}
?>
</div>
<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>