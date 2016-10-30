<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<!--EXCEL IMPOT FORM-->
<div class="row">
	<div class="col-md-2"> 
		<div class="portlet grey box">
			<div class="portlet-body" id="tree-view-selector-container">
				<div id="ui-navigation-tree" class="demo"></div>
			</div>
		</div>
		
		<div id="hidden-main-table-view" style="display:none1; position:absolute; left:-500px;">
		</div>
	</div>
	<div class="col-md-10">
		<div class="row">
			<div class="">
				 <div class="input-group col-md-12">
					<div class="input-group-btn">
						<a href="#" title="Home" function-id="1" function-class="files" function-name="display_all_records_full_view" class="btn blue btn-sm custom-action-button" style="height: 28px;"><i class="icon-home"></i> Home</a>
					 </div>
					 <select id="folder-selector" class=" col-md-12" style="font-size: 11px; padding: 4px 6px; height: 28px; font-weight: bold; border-right:none; border-left:none; margin-left:-1px; margin-right:1px;">
						
					</select>
					<div class="input-group-btn">
						<a id="open-file-folder-button" mod="edit" todo="open_file_folder" action="?module=&action=files&todo=open_file_folder" function-id="1" function-class="files" function-name="open_file_folder" title="Open File / Folder" type="button" class="btn btn-default btn-sm custom-single-selected-record-button" selected-record="" override-selected-record="" style="height: 28px;"><i class="icon-play"></i></a>
					 </div>
					<!--<input type="search" class="col-md-3" placeholder="Search" style="font-size: 11px; padding: 4px 5px; height: 28px;" />-->
				</div>
			</div>
		</div>
		<div class="row" id="main-table-view">
			<?php include dirname( dirname( __FILE__ ) ) . "/files-table-view.php"; ?>
		</div>
	</div>
</div>


<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>