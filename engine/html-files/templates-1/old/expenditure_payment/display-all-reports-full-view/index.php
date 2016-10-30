<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	
?>
<!--EXCEL IMPOT FORM-->
<div class="row resize-container" >
	<div class="col-md-3 resize-drag"> 
		<div class="portlet grey box">
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small><small>Farm Report</small></small></div>
				
			</div>
			<div class="portlet-body" id="tree-view-selector-container" style="max-height:320px; overflow-y:auto;">
				<div class="input-group">
				<select id="report_type-select-tree-view-activator" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 24px; font-weight: bold;">
					<option value="">--- Select Report Type ---</option>
					<?php 
						if( isset( $data[ 'report_type' ] ) && $data[ 'report_type' ] ){
							foreach( $data[ 'report_type' ] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php
							}
						}
					?>
				</select>
				<div class="input-group-btn">
					<button id="refresh-tree-view" title="Click to Reload Tree View" type="button" class="btn default btn-sm" style="height:24px;"><i class="icon-refresh"></i></button>
				 </div>
				 <!-- /btn-group -->
				</div>
				<div id="ui-navigation-tree-container">
					<div id="ui-navigation-tree" class="demo"></div>
				</div>
			</div>
		</div>
		
	</div>
	<div class="col-md-9 resize-sibling" id="main-table-view"> 
		<div class="portlet box purple">
			<div class="portlet-body" style="padding-bottom:50px; min-height:480px;" id="data-table-section">
				<div style="text-align:center;">
					<br /><br /><br /><br /><br /><h1>Select Report Type</h1><br /><br />
					<p>Use the dropdown button to the left to first select a report type</p>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>