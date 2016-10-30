<div class="portlet  box">
<div class="portlet-body" style=" background:transparent;">
<form class="activate-ajax" method="post" id="production_items" action="?action=production_items&todo=filter_production_items_search_all">
	<div class="row">
		<div class="col-md-12">
		<label>Item Description</label>
		<select class="form-control select2" placeholder="Select Item" name="item_id">
			<option value="">-Select Item-</option>
			<?php
				$data['vendors'] = get_items();
				if( isset( $data['vendors'] ) && is_array( $data['vendors'] ) ){
					foreach( $data['vendors'] as $key => $val ){
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
		<!--
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
		-->
		<br />
		<div class="col-md-12">
			<button class="btn blue btn-block" type="submit" style="">SEARCH</button>
		 </div>
	</div>
</form>
</div>
</div>