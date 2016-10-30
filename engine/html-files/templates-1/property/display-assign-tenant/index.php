<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	  $item_id = '';
	  if( isset( $data["property"] ) && $data["property"] ){
		  $item_id = $data["property"];
	  }
	  
	  $propert_info = array();
	  if( isset( $data["property_info"] ) && $data["property_info"] ){
		  $propert_info = $data["property_info"];
	  }
	  
	  //if( isset( $data["html"] ) ){
	  if( $item_id ){
		  ?>
		  <div class="row">
			<div class="col-md-7">
				<h4><strong><?php echo $data["title"]; ?></strong></h4>
				<hr />
				<form class="activate-ajax" method="post" id="property" action="?action=property&todo=save_new_tenant_form">
				<input class="form-control" name="item_id" type="hidden" value="<?php echo $item_id; ?>" />
				
				<div class="row">
					<div class="col-md-12">
						<div class="input-group">
						 <span class="input-group-addon" style="color:#777;">Tenant</span>
						  <select class="form-control select2 select-room-guest" name="customer" required="required" >
							<option value="">-Select Tenant-</option>
							<?php
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
					</div>
				</div>
					
				<div class="row">
					<div class="col-md-7">
						<div class="input-group">
						 <span class="input-group-addon" style="color:#777;">Entry Date</span>
						  <input class="form-control" name="date" required="required" type="date" value="<?php echo date("Y-m-d"); ?>" />
						 </div>
					</div>
					<div class="col-md-5">
						<div class="input-group">
						 <span class="input-group-addon" style="color:#777;">No. of <?php echo get_billing_cycle_text( $propert_info["billing_cycle"] ); ?>(s)</span>
						  <input class="form-control" name="quantity" required="required" type="number" min="1" value="1" />
						 </div>
					</div>
				</div>
				
				<br />
				<div class="row">
					<div class="col-md-7">
						<div class="input-group">
						 <span class="input-group-addon" style="color:#777;">Amount Paid</span>
						  <input class="form-control" name="amount_paid" type="number" step="any" min="0" value="<?php echo $propert_info["rate"] + $propert_info["service_charge"]; ?>" />
						 </div>
					</div>
					<div class="col-md-5">
						<div class="input-group">
						 <span class="input-group-addon" style="color:#777;">Method</span>
						  <select class="form-control" name="payment_method">
							<?php
								if( isset( $data['payment_method'] ) && is_array( $data['payment_method'] ) ){
									foreach( $data['payment_method'] as $key => $val ){
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
					</div>
				</div>
					
				<br />
				<div class="row">
					<div class="col-md-7">
						<div class="input-group">
						 <span class="input-group-addon" style="color:#777;">Comment</span>
						  <input class="form-control" name="comment" type="text" placeholder="Optional Comment" />
						 </div>
					</div>
					<div class="col-md-5">
						<div class="input-group">
						 <span class="input-group-addon" style="color:#777;">Discount</span>
						  <input class="form-control" name="discount" type="number" step="any" min="0" value="0" />
						 </div>
					</div>
				</div>
				
				<br />
				<div class="row">
					<div class="col-md-12">
						<input type="submit" value="Save Changes" class="btn btn-block blue" />
					</div>
				</div>
				</form>
			</div>
			<div class="col-md-5">
			 <a class="dark btn btn-block custom-action-button" skip-title="1" function-class="customers" function-name="new_room_guest_form" function-id="120" href="#">
				 <i class="icon-plus"></i> Create New Tenant
			 </a>
			 <div id="room-guest-container">
				<div class="alert alert-warning">
					<strong>Click the button above to create a new room guest</strong>
				</div>
			 </div>
			 </div>
		 </div>
		  <?php
	  }else{ ?>
		  <h4>Invalid Property</h4>
	  <?php } ?>
<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>