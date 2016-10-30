<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	
?>
<!--EXCEL IMPOT FORM-->
<div class="row resize-container" >
	<div class="col-md-2 resize-drag"> 
		<div class="portlet grey box">
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small><small>Expenditure Report</small></small></div>
				
			</div>
			<div class="portlet-body" id="tree-view-selector-container" style="max-height:420px; overflow-y:auto;">
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
				<hr />
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
				</select><hr />
				<label style="font-weight:bold; font-size:11px;">Expenditure</label>
				<select id="report-pen" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;">
					<option value="all-expenses">All Expenses<option>
					<?php 
						$sel = "";
						if( isset( $data[ 'selected_option2' ] ) && $data[ 'selected_option2' ] ){
							$sel = $data[ 'selected_option2' ];
						}
						if( isset( $data[ 'report_type2' ] ) && $data[ 'report_type2' ] ){
							foreach( $data[ 'report_type2' ] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>" <?php if( $sel == $k ){ ?>selected="selected"<?php } ?>><?php echo $v; ?></option>
								<?php
							}
						}
					?>
				</select>
				<hr />
					<a href="#" budget-id="-" month-id="-" operator-id="-" department-id="-" function-id="activate_report_year" function-class="expenditure" function-name="get_financial_reports" id="activate_report_year" class="btn btn-success custom-action-button">Generate Report</a>
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
						
						$("select#report-pen")
						.on("change", function(){
							$("#activate_report_year")
							.attr("operator-id", $(this).val() )
							.click();
						}).change();
					</script>
				 
			</div>
		</div>
		
	</div>
	<div class="col-md-10 resize-sibling" id="main-table-view"> 
		<div class="portlet box purple">
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