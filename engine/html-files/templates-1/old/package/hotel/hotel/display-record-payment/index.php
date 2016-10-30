<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
?>
<!--EXCEL IMPOT FORM-->
<div class="row">
	<div class="col-md-6"> 
		<!--grey-->
		<div class="portlet  box">
			<div class="portlet-body allow-scroll" style=" background:transparent;">
					<form class="activate-ajax" method="post" id="sales" action="?action=sales&todo=search_sales_record">
					<div class="row">
						<div class="col-md-5">
						<input type="search" class="form-control input-lg1" style="" placeholder="Enter Receipt No." name="receipt_num" />
						</div>
						
						<div class="col-md-2">
							<button class="btn btn-lg1 btn-block" type="submit" style="">OR</button>
						 </div>
						<div class="col-md-5">
						<select class="form-control input-lg1" onchange="nwRecordPayment.search();" placeholder="Select Customer" name="customer">
							<option value="">-Select Customer-</option>
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
					</div>
					</form>
					<hr />
					<div id="sales-record-search-result">
					
					</div>
				
			</div>
		</div>
		
	</div>
	
	<div class="col-md-6" id="main-table-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="portlet-body1 shopping-cart-table allow-scroll-1" style="background:transparent !important;">
				 <div class="tabbable-custom nav-justified">
					<ul class="nav nav-tabs nav-justified">
					   <li class="active"><a href="#capture-payment" data-toggle="tab">Record Payment</a></li>
					   <li><a href="#returned-item" class="custom-single-selected-record-button" action="?module=&action=sales_items&todo=get_return_items" override-selected-record="" data-toggle="tab">Returned Items</a></li>
					   <li><a href="#payment-history" class="custom-single-selected-record-button" action="?module=&action=payment&todo=get_payment_history" override-selected-record="" data-toggle="tab">Payment History</a></li>
					</ul>
					<div class="tab-content" style="background:transparent !important;">
					   <div class="tab-pane active" id="capture-payment">
							
						<form class="activate-ajax" method="post" id="cart" action="?action=cart&todo=save_record_payment">
						
							 <input type="hidden" name="id" class="form-control" value="" />
							 <!--<input type="hidden" name="store" class="form-control" value="" />-->
							 <br />
							 
							<div class="row">
								<div class="col-md-6">
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Status</span>
									 
									  <select required="required" class="form-control" name="sales_status" >
										<?php
											$vendors = get_sales_status();
											foreach( $vendors as $key => $val ){
												?>
												<option value="<?php echo $key; ?>">
													<?php echo $val; ?>
												</option>
												<?php
											}
										?>
									 </select>
									</div>
									<br />
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Staff</span>
									  <select class="form-control" name="staff_responsible">
										<?php
											if( isset( $data['staff_responsible'] ) && is_array( $data['staff_responsible'] ) ){
												foreach( $data['staff_responsible'] as $key => $val ){
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
								<div class="col-md-6">
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Amount Paid</span>
									 <input type="number" step="any" class="form-control" name="amount_paid" value="0">
									</div>
									<br />
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Payment Method</span>
									  <select required="required" class="form-control" name="payment_method" >
										<?php
											$vendors = get_payment_method();
											foreach( $vendors as $key => $val ){
												?>
												<option value="<?php echo $key; ?>">
													<?php echo $val; ?>
												</option>
												<?php
											}
										?>
									 </select>
									</div>
									
								</div>
							</div>
							<br />
							<div class="row">
								<div class="col-md-12">
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Comment</span>
									  <input type="text" class="form-control" name="comment" value="">
									</div>
								</div>
							</div>
							<hr />
							<div class="row">
								<div class="col-md-6">
									<input type="submit" class="btn btn-lg green btn-block" value="Save" />
								</div>
								<div class="col-md-6">
									<a class="btn btn-lg btn-block default" onclick="nwRecordPayment.clear(); return false;" href="#">Cancel</a>
								</div>
							</div>
						</form>
				
					   </div>
					   <div class="tab-pane" id="returned-item">
							
						</div>
					   <div class="tab-pane" id="payment-history">
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>