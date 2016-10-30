<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>


<?php 
	$mobile = 0;
		if( isset( $data['mobile'] ) )
			$mobile = $data['mobile'];
?>
<!--EXCEL IMPOT FORM-->
<div class="row resize-container" >
	<div class="col-md-2 resize-drag mobile-settings-view"> 
		<div class="portlet grey box">
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small><small>Income vs Expenditure</small></small></div>
				
			</div>
			<div class="portlet-body" style="max-height:470px; overflow-y:auto;">
				<?php if( ! ( isset( $data[ 'hide_report' ] ) && $data[ 'hide_report' ] ) ){ ?>
				<label style="font-weight:bold; font-size:11px;">Report</label>
				<select id="report-report-type" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;">
					
					<?php 
						$sel = "";
						if( isset( $data[ 'selected_option5' ] ) && $data[ 'selected_option5' ] ){
							$sel = $data[ 'selected_option5' ];
						}
						if( isset( $data[ 'report_type5' ] ) && $data[ 'report_type5' ] ){
							foreach( $data[ 'report_type5' ] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>" <?php if( $sel == $k ){ ?>selected="selected"<?php } ?>><?php echo $v; ?></option>
								<?php
							}
						}
					?>
				</select>
				<hr />
				<?php } ?>
				
				<?php if( ! ( isset( $data[ 'hide_years' ] ) && $data[ 'hide_years' ] ) ){ ?>
				<label style="font-weight:bold; font-size:11px;">Start Date</label>
				<input type="date" id="start-date" value="<?php echo date("Y-m-d" , mktime(0,0,0, 1, 1, date("Y") ) ); ?>" class="form-control date-range" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;" />
				<br />
				<label style="font-weight:bold; font-size:11px;">End Date</label>
				<input type="date" id="end-date" value="<?php echo date("Y-m-d", mktime(23,59,59, 12, 31 , date("Y") ) ); ?>" class="form-control date-range" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;" />
				<hr />
				<?php } ?>
				
				<?php if( ! ( isset( $data[ 'hide_months' ] ) && $data[ 'hide_months' ] ) ){ ?>
				<label style="font-weight:bold; font-size:11px;">Pen</label>
				<select id="report-pen" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;">
					<?php if( ! ( isset( $data[ 'hide_all_pens' ] ) && $data[ 'hide_all_pens' ] ) ){ ?>
					<option value="all-pens">All Pens</option>
					<?php } ?>
					
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
				<br />
				<?php } ?>
				
				<label style="font-weight:bold; font-size:11px;">Type</label>
				<select id="report-period" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;">
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
				<hr />
				
				<?php if( ! $mobile ){ ?>
					<a href="#" budget-id="-" month-id="-" operator-id="-" department-id="-" skip-title="1" function-id="activate_report_year" function-class="expenditure" function-name="<?php if( isset( $data[ 'report_action' ] ) && $data[ 'report_action' ] ){ echo $data[ 'report_action' ]; }else{ ?>get_production_reports<?php } ?>" id="activate_report_year" class="btn btn-block btn-success custom-action-button">Generate Report</a>
				<?php }else{ ?>
					
					<a href="#" budget-id="-" month-id="-" operator-id="-" department-id="-" skip-title="1" function-id="activate_report_year" function-class="expenditure" function-name="<?php if( isset( $data[ 'report_action' ] ) && $data[ 'report_action' ] ){ echo $data[ 'report_action' ]; }else{ ?>get_production_reports<?php } ?>" id="activate_report_year" class="btn btn-block btn-success custom-action-button" onclick="$.fn.pHost.showMobileMainView();">Generate Report</a>
				<?php } ?>
				
			</div>
		</div>
		
	</div>
	
	<div class="col-md-10 mobile-main-view resize-sibling" id="main-table-view"> 
	<?php if( ! $mobile ){ ?>
		<div class="portlet box purple">
			<div class="portlet-body" style="padding-bottom:50px; min-height:480px; max-height:500px; overflow-y:auto;" id="data-table-section">
	<?php }else{ ?>
	
		<div class="">
			<div class="" style="padding-bottom:50px;" id="data-table-section">
	<?php } ?>	
				<div style="text-align:center;">
					<br /><br /><br /><br /><br /><h1>Select Reporting Year</h1><br /><br />
					<p>Use the dropdown button to the left to first select a reporting year</p>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" class="auto-remove">
	var mobile = <?php echo $mobile; ?>;
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>