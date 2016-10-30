	<hr />
	<div style="display:none;">
		<a href="#" cash_calls-id="1" month-id="-" function-id="1" function-class="tendering_reports_view" function-name="contracts_expiring_soon" id="contracts_expiring_soon-link" class="btn btn-success btn-sm custom-action-button">Contracts Expiring Soon</a>
		
		<a href="#" cash_calls-id="1" month-id="-" function-id="1" function-class="tendering_reports_view" id="recently_expired_contracts-link" function-name="recently_expired_contracts" class="btn btn-success btn-sm custom-action-button">Recently Expired Contracts</a>
		
		<a href="#" cash_calls-id="1" month-id="-" function-id="1" function-class="tendering_reports_view" id="recently_approved_contracts-link" function-name="recently_approved_contracts" class="btn btn-success btn-sm custom-action-button">Recently Approved Tenders</a>
	</div>	
	
	<div class="input-group">
	 <select id="chart-selector" class=" col-md-6" style="font-size: 10px; padding: 2px 5px; height: 25px; font-weight: bold; border-right:none;">
		
		<option value="contracts_expiring_soon">Divisional Report Section Template</option>
		
	</select>
	<select id="chart-selector-department" required="required" class=" col-md-3" style="font-size: 10px; padding: 2px 5px; height: 25px; font-weight: bold; border-right:none;">
		<?php
			$years = get_calendar_years();
			$ty = date("Y");
			
			foreach( $years as $y => $yy ){
				$selected = '';
				if( $y == $ty )$selected = 'selected="selected"';
				?><option value="<?php echo $y; ?>" <?php echo $selected; ?>><?php echo $yy; ?></option><?php
			}
		?>
	</select>
	<select id="chart-selector-operator" required="required" class=" col-md-3" style="font-size: 10px; padding: 2px 5px; height: 25px; font-weight: bold; border-right:none;">
		<?php
			$months = get_months_of_year();
			$ty = intval( date("m") );
			
			foreach( $months as $y => $yy ){
				$selected = '';
				if( $y == $ty )$selected = 'selected="selected"';
				?><option value="<?php echo $y; ?>" <?php echo $selected; ?>><?php echo strtoupper( substr($yy, 0, 3) ); ?></option><?php
			}
		?>
	</select>
	 <div class="input-group-btn">
		<button id="refresh-chart-button" title="Click to Refresh" type="button" class="btn blue btn-sm"><i class="icon-play"></i></button>
	 </div>
	 </div>
	 <!-- /btn-group -->
	
	<div id="report-preview">
	</div>