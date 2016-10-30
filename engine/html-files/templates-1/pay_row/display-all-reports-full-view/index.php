<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	
?>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	<div class="col-md-12"> 
		<div class="portlet grey1 box1">
			<!--
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small><small><?php //if( isset( $data[ 'report_title' ] ) )echo $data[ 'report_title' ]; else echo "Farm Production Report"; ?></small></small></div>
				
			</div>
			-->
			<div class="portlet-body" id="tree-view-selector-container" style="overflow:hidden;">
				<div class="row">
				
				<?php if( ! ( isset( $data[ 'hide_years' ] ) && $data[ 'hide_years' ] ) ){ ?>
				<div class="col-md-2">
				<label style="font-weight:bold; font-size:11px;">Year</label>
				<select id="report-year" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;">
					<option value="">-Year-</option>
					<?php 
						$sel = "";
						if( isset( $data[ 'selected_option' ] ) && $data[ 'selected_option' ] ){
							$sel = $data[ 'selected_option' ];
						}
						if( isset( $data[ 'report_type' ] ) && $data[ 'report_type' ] ){
							foreach( $data[ 'report_type' ] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>" <?php if( $sel == $k ){ ?>selected="selected"<?php } ?>><?php echo $v; ?></option>
								<?php
							}
						}
					?>
				</select>
				</div>
				<?php } ?>
				
				<?php if( ! ( isset( $data[ 'hide_months' ] ) && $data[ 'hide_months' ] ) ){ ?>
				<div class="col-md-2">
				<label style="font-weight:bold; font-size:11px;">Month</label>
				<select id="report-month" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;">
					<option value="all-months">All Months</option>
					<?php 
						$sel = "";
						if( isset( $data[ 'selected_option1' ] ) && $data[ 'selected_option1' ] ){
							$sel = $data[ 'selected_option1' ];
						}
						if( isset( $data[ 'report_type1' ] ) && $data[ 'report_type1' ] ){
							foreach( $data[ 'report_type1' ] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>" <?php if( $sel == $k ){ ?>selected="selected"<?php } ?>><?php echo $v; ?></option>
								<?php
							}
						}
					?>
				</select>
				</div>
				<?php } ?>
				
				<?php if( ( isset( $data[ 'show_report_category' ] ) && $data[ 'show_report_category' ] ) ){ ?>
				<div class="col-md-3">
				<label style="font-weight:bold; font-size:11px;">Report Type</label>
				<select id="report-type" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;">
					<?php 
						$sel = "";
						if( isset( $data[ 'selected_option4' ] ) && $data[ 'selected_option4' ] ){
							$sel = $data[ 'selected_option4' ];
						}
						if( isset( $data[ 'report_type4' ] ) && $data[ 'report_type4' ] ){
							foreach( $data[ 'report_type4' ] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>" <?php if( $sel == $k ){ ?>selected="selected"<?php } ?>><?php echo $v; ?></option>
								<?php
							}
						}
					?>
				</select>
				</div>
				<?php } ?>
				
				<!--<option value="all-employees">All Employees</option>-->
				<div class="col-md-3">
				<label style="font-weight:bold; font-size:11px;">Employees</label>
				<select id="report-age" multiple="multiple" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;">
					<?php
						$sel = "";
						if( isset( $data[ 'selected_option3' ] ) && $data[ 'selected_option3' ] ){
							$sel = $data[ 'selected_option3' ];
						}
						if( isset( $data[ 'report_type3' ] ) && $data[ 'report_type3' ] ){
							foreach( $data[ 'report_type3' ] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>" <?php if( $sel == $k ){ ?>selected="selected"<?php } ?>><?php echo $v; ?></option>
								<?php
							}
						}
					?>
				</select>
				</div>
				
				<div class="col-md-2">
					<br />
					<a href="#" budget-id="-" month-id="-" operator-id="-" department-id="-" function-id="activate_report_year" function-class="pay_row" function-name="<?php if( isset( $data[ 'report_action' ] ) && $data[ 'report_action' ] ){ echo $data[ 'report_action' ]; }else{ ?>get_production_reports <?php } ?>" id="activate_report_year" class="btn btn-block btn-success custom-action-button">Generate Report</a>
					<script type="text/javascript">
						$("select#report-year")
						.on("change", function(){
							$("#activate_report_year")
							.attr("budget-id", $(this).val() )
							.click();
						}).change();
						
						$("select#report-month")
						.on("change", function(){
							$("#activate_report_year")
							.attr("month-id", $(this).val() )
							.click();
						}).change();
						
						$("select#report-type")
						.on("change", function(){
							$("#activate_report_year")
							.attr("department-id", $(this).val() )
							.click();
						}).change();
						
						$("select#report-age")
						.on("change", function(){
							var v = $(this).val();
							if( ! v )v = "-";
							$("#activate_report_year")
							.attr("operator-id", v )
							.click();
						}).change().select2();
						
					</script> 
				</div>
				
				</div>
			</div>
		</div>
		
	</div>
	<div class="col-md-12 " id="main-table-view"> 
		<div class="portlet box purple" style="border-top: 1px solid #af5cc1 !important;">
			<div class="portlet-body" style="padding-bottom:50px; min-height:480px; max-height:500px; overflow-y:auto;" id="data-table-section">
				<div style="text-align:center;">
					<br /><br /><br /><br /><br /><h1>Select Reporting Year</h1><br /><br />
					<p>Use the dropdown button to the left to first select a reporting year</p>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>