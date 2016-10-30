<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$col_1 = 6;
	$col_2 = 6;
	if( isset( $data['col_1'] ) && isset( $data['col_2'] ) ){		
		$col_1 = $data['col_1'];
		$col_2 = $data['col_2'];
	}
	
	if( ! isset( $col_1 ) )$col_1 = 3;
	if( ! isset( $col_2 ) )$col_2 = 9;
?>

<div class="row">
    <div class="col-md-<?php echo $col_2; ?>"> 
		<?php include dirname( dirname( dirname( __FILE__ ) ) ) . "/globals/line-items-datatable-view.php"; ?>
	</div>
    <div class="col-md-<?php echo $col_1; ?>">
		<div class="portlet box grey">
			<div class="portlet-title">
				<div class="caption">
					<small>
					<i class="icon-envelope"></i> Message
					</small>
				</div>
			</div>
			<div class="portlet-body" style="padding-bottom:40px;" id="message-section">				
				<h1>&nbsp;</h1>
				<h3 style="text-align:center; color:#333; font-size:2em;">Message Preview Pane</h3>
				<hr />
				<h5 style="text-align:center; color:#333; font-size:1.4em;">Click on the File Link in the Table to Preview Its Contents</h5>
			</div>
		</div>
	</div>
</div>

<div style="display:none;">
	<a id="open-notifications-button" mod="edit" todo="mark_as_read" action="?module=&action=notifications&todo=mark_as_read" function-id="1" function-class="notifications" function-name="mark_as_read" title="Mark As Read" class="btn btn-default btn-sm custom-single-selected-record-button" selected-record="" override-selected-record="" style="height: 28px;"><i class="icon-play"></i></a>
</div>
<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>