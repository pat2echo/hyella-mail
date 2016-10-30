<!--EXCEL IMPOT FORM-->
<style type="text/css">
	#excel-import-form-container .input-row{
		padding-left:0px !important;
		padding-right:5px !important;
	}
	.bottom-row{
		clear:both;
	}
</style>
<div class="row">
<div class="col-md-10 col-md-offset-1">

<div class="portlet grey box">
	<div class="portlet-title">
		<div class="caption"><i class="icon-globe"></i>Import Excel<?php if( isset( $data["import_type"] ) && $data["import_type"] ) echo ": ".$data["import_type"]; ?></div>
	</div>
	<div class="portlet-body" id="excel-import-form-container" style="overflow-y:auto; max-height:450px;">
		<div class="row" style="margin-right: -10px;  margin-left: -10px;">
			<div class="col-md-6">
				<?php
				if( isset( $data['excel_import_form'] ) && $data['excel_import_form'] ){
					echo $data['excel_import_form']['html'];
				}
				?>
			</div>
			<div class="col-md-6"> 
				<div class="portlet box purple">
					<div class="portlet-title">
						<div class="caption"><i class="icon-bell"></i>Recent Activities</div>
					</div>
					<div class="portlet-body">
						<?php if( isset( $data[ 'recent_activity' ] ) )echo $data[ 'recent_activity' ]; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</div>
</div>
<div style="display:none;">
	<a href="#" class="custom-action-button " month-id="-" budget-id="-" function-id="1" function-class="units" function-name="get_departmental_units" id="dept-units">
		Get Departmental Units
	</a>
</div>
