<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	
	$todo1 = '?action=expenditure&todo=expenditure_search';
	
	$package = "";
	if( defined( "HYELLA_PACKAGE" ) ){
		$package = HYELLA_PACKAGE;
	}
	
	$all_stores = 0;
	if( isset( $data['all_stores'] ) && $data['all_stores'] ){
		$all_stores = 1;
		$todo1 = '?action=expenditure&todo=expenditure_search_all';
	}	
?>
<!--EXCEL IMPOT FORM-->
<div class="row">
	<div class="col-md-7"> 
		<!--grey-->
		<div class="portlet  box">
			<div class="portlet-body" style=" background:transparent;">
				<form class="activate-ajax" method="post" id="sales" action="<?php echo $todo1; ?>">
				<div class="row">
					<div class="col-md-4">
					<select class="form-control" onchange="nwRecordPayment.search();" placeholder="Select Vendor" name="vendor">
						<option value="">-Select Vendor-</option>
						<?php
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
					<div class="col-md-3">
						<div class="input-group">
						 <span class="input-group-addon" style="color:#777;">from</span>
						 <input type="date" name="start_date" onchange="nwRecordPayment.search();" class="form-control" value="<?php echo date("Y-01-01"); ?>" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="input-group">
						 <span class="input-group-addon" style="color:#777;">to</span>
						 <input type="date" name="end_date" onchange="nwRecordPayment.search();" class="form-control" value="<?php echo date("Y-12-31"); ?>" />
						</div>
					</div>
					<div class="col-md-2">
						<button class="btn dark btn-block" type="submit" style="">GO</button>
					 </div>
				</div>
				</form>
				<hr />
				
				<div id="main-table-view" class="portlet-body1 shopping-cart-table" style="background:transparent !important;">
				 <div class="tabbable-custom nav-justified">
					<ul class="nav nav-tabs nav-justified">
					   <li class="active"><a href="#transactions" data-toggle="tab">Purchase Orders</a></li>
					   <li ><a href="#payment-history" data-toggle="tab">Payment for Services</a></li>
					</ul>
					<div class="tab-content" style="background:transparent !important;">
					   <div class="tab-pane allow-scroll-2 " id="payment-history">
							<div id="payment-record-search-result">
							
					      </div>
					   </div>
					   <div class="tab-pane allow-scroll-2 active" id="transactions">
						  <div id="sales-record-search-result">
							
					      </div>
					   </div>
					</div>
				</div>
			</div>
		
			</div>
		</div>
		
	</div>
	
	<div class="col-md-5" > 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="portlet-body1 shopping-cart-table allow-scroll-1" style="background:transparent !important;">
				<h3>Update Status</h3>
				<hr />	
				<form class="activate-ajax" method="post" id="cart" action="?action=cart&todo=save_record_vendor_payment">
				
					 <input type="hidden" name="amount_paid" class="form-control" value="" />
					 <input type="hidden" name="id" class="form-control" value="" />
					 <input type="hidden" name="store" class="form-control" value="" />
					 <input type="hidden" name="vendor" class="form-control" value="" />
					 <!--<input class="form-control" name="mode_of_payment" type="hidden" value="" />-->
					 
					 <br />
					 
					<div class="row">
						<div class="col-md-12">
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777; line-height: 1.5;">Tracking No.</span>
							 
							 <span id="sales-order-selectbox" class="input-group-addon" style="background: #A7E862; line-height: 1.5;"></span>
							  <!--<select class="form-control" name="sales_status" >
								<option value="">None</option>
							 </select>-->
							</div>
							<br />
							<!--
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
							-->
						</div>
						<div class="col-md-12">
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Description</span>
							  <input type="text" class="form-control" name="description" value="" />
							</div>
						</div>
						<br />
						<div class="col-md-12">
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Amount Paid</span>
							 <input type="number" step="any" min="0.01" class="form-control" name="amount_owed" value="0">
							</div>
							<br />
						</div>
						<div class="col-md-12">
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Payment Method</span>
							  
							  <select required="required" class="form-control" name="mode_of_payment">
								<?php
									$pm = get_payment_method_grouped();
									if( isset( $pm ) && is_array( $pm ) ){
										foreach( $pm as $k => $v ){
											?>
											<optgroup label="<?php echo $k; ?>">
											<?php
											foreach( $v as $key => $val ){
											?>
											<option value="<?php echo $key; ?>">
												<?php echo $val; ?>
											</option>
											<?php
											}
											?>
											</optgroup>
											<?php
										}
									}
								?>
							 </select>
							</div>
						</div>
					</div>
					<hr />
					<div class="row">
						<div class="col-md-6">
							<input type="submit" class="btn btn-lg green btn-block" value="Post Payment" />
						</div>
						<div class="col-md-6">
							<a class="btn btn-lg btn-block default" onclick="nwRecordPayment.clear(); return false;" href="#">Cancel</a>
						</div>
					</div>
				</form>
		
			</div>
		</div>
	</div>

</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>