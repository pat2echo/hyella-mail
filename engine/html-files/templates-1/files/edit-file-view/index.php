<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<!--EXCEL IMPOT FORM-->

	<div class="col-md-12">
		<div class="portlet purple box">
			<div class="portlet-title" id="frame-container-header">
			<?php 
			$shareable = 1;
	
			$editable = 1;
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
			
			$height = "500px";
			if( isset( $data["height"] ) && $data["height"] ){
				$height = $data["height"];
			}
			
			$show_title_icon = 0;
			$show_small_title = 0;
			$title_label = "";
			$show_main_buttons = 1;
			$show_action_buttons = 0;
			include dirname( dirname( __FILE__ ) )."/frame-header.php"; 
		?>
		</div>
		<div class="portlet-body" style="padding-top:0px; background-color: #f7f7f7;">
		<div class="row">
			<div class="col-md-2">
				
				<div class="portlet grey box">
					<div class="portlet-title">
						<div class="caption"><small><small>Properties</small></small></div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
					</div>
					<div class="portlet-body" style="overflow-x:auto;">
						<small id="reports-properties-view">
							<?php if( isset( $data["report_properties"] ) && $data["report_properties"] )echo $data["report_properties"]; ?>
						</small>
					</div>
				</div>
				
				<div class="portlet grey box">
					<div class="portlet-title">
						<div class="caption"><small><small>Versions</small></small></div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
					</div>
					<div class="portlet-body" style="overflow:auto; max-height:200px;">
						<small>
							<?php $report_versions = array(); if( isset( $data['report_versions'] ) && $data['report_versions'] )$report_versions = $data['report_versions']; ?>
							<ol id="version-history" style="padding-left:10px;">
							<?php
								foreach( $report_versions as $v ){
									include dirname( dirname( __FILE__ ) )."/version-history.php";
								}
							?>
							</ol>
						</small>
					</div>
				</div>
				
				<div class="portlet grey box">
					<div class="portlet-title">
						<div class="caption"><small><small>Comments</small></small></div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
					</div>
					<div class="portlet-body" style="overflow:auto; max-height:200px;">
						<small>
							
							<ol id="comments" style="padding-left:10px;">
							<?php $report_versions = array(); if( isset( $data['comments'] ) && $data['comments'] )echo $data['comments']; ?>
							</ol>
						</small>
					</div>
				</div>
				
			</div>
			<div class="col-md-10">
				<div class="portlet-body" style="max-height:520px; min-height:500px; overflow-y:auto; padding:0;">
				
				<?php if( isset( $data["src"] ) && $data["src"] ){ 
					include dirname( dirname( __FILE__ ) )."/frame-content.php";
				}else{ ?>
					<br style="line-height:0.5;"/>
					<div class="alert alert-warning">
						<h4><i class="icon-bell"></i> File Not Found</h4>
						<p>
						The selected file was not found
						</p>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
			
		</div>
		
	</div>

<?php if( $editable ){ ?>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
<?php } ?>