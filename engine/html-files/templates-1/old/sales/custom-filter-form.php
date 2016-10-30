<div class="portlet  box">
<div class="portlet-body" style=" background:transparent;">
<form class="activate-ajax" method="post" id="sales" action="?action=sales&todo=filter_sales_search_all">
	<div class="row">
		<div class="col-md-12">
		<label>Customer</label>
		<select class="form-control select2" placeholder="Select Customer" name="customer">
			<option value="">-Select Customer-</option>
			<?php
				$data['customers'] = get_customers();
				if( isset( $data['customers'] ) && is_array( $data['customers'] ) ){
					foreach( $data['customers'] as $key => $val ){
						?>
						<option value="<?php echo $key; ?>">
							<?php echo $val; ?>
						</option>
						<?php
					}
				}
			?>
		</select>
		</div>
		<br />
		<div class="col-md-12">
			<label>Start Date</label>
			 <input type="date" name="start_date" class="form-control" value="<?php echo date("Y-01-01"); ?>" />
		</div>
		<br />
		<div class="col-md-12">
			<label>End Date</label>
			 <input type="date" name="end_date" class="form-control" value="<?php echo date("Y-12-31"); ?>" />
		</div>
		<br />
		<div class="col-md-12">
			<label>Invoice / Receipt Number</label>
			 <input type="number" min="1" step="1" name="receipt_num" class="form-control" value="" placeholder="E.g 442" />
		</div>
		<br />
		<div class="col-md-12">
			<label>Reference Number</label>
			 <input type="text" name="id" class="form-control" value="" placeholder="E.g 14528329119" />
		</div>
		<br />
		<div class="col-md-12">
			<button class="btn blue btn-block" type="submit" style="">SEARCH</button>
		 </div>
	</div>
</form>
</div>
</div>