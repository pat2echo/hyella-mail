<?php
	$shareable = 1;
	
	$editable = 0;
	if( isset( $data["show_editable_report"] ) && $data["show_editable_report"] ){
		$editable = $data["show_editable_report"];
	}
	
	$report_id = "";
	if( isset( $data["report_id"] ) && $data["report_id"] ){
		$report_id = $data["report_id"];
	}
	
	$title = "";
	if( isset( $data["title"] ) && $data["title"] ){
		$title = $data["title"];
	}
	
	$height = "350px";
	if( isset( $data["height"] ) && $data["height"] ){
		$height = $data["height"];
	}
?>
 <div class="row">
    <div class="col-md-7"> 
		<?php include dirname( dirname( dirname( __FILE__ ) ) ) . "/globals/line-items-datatable-view.php"; ?>
	</div>
	<div class="col-md-5">
		<div class="portlet purple box">
			<div class="portlet-title">
				<?php 
					$show_title_icon = 0;
					$show_small_title = 1;
					$title_label = "Preview:";
					$show_main_buttons = 0;
					$show_action_buttons = 1;
					include dirname( dirname( __FILE__ ) )."/frame-header.php"; 
				?>
			</div>
			<div class="portlet-body">
				<?php include dirname( dirname( __FILE__ ) )."/frame-content.php"; ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>