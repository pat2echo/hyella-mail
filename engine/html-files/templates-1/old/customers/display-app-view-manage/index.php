<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	include "expense-function.php";
?>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	
	<div class="col-md-5"> 
		<form class="activate-ajax" method="post" id="customer" action="?action=customers&todo=search_customer">
		<div class="row">
			<div class="col-md-8">
			<select class="form-control" onchange="nwCustomer.search();" placeholder="Select Customer" name="customer">
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
			<div class="col-md-2">
				<button class="btn btn-lg1 btn-block green" type="submit">GO</button>
			 </div>
			<div class="col-md-2">
				 <button class="btn dark btn-block custom-action-button" type="button" function-name="new_customer_popup_form" function-class="customers" function-id="Add New Customer" skip-title="1" title="Add New Customer">New</button>
			 </div>
			
		</div>
		</form>
		<hr />
		<div id="sales-record-search-result" class="allow-scroll">
			
		</div>

	</div>
	
	<div class="col-md-7" id="expense-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="portlet-body1" style="background:transparent !important;">
                     <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                           <li class="active"><a href="#recent-expenses" data-toggle="tab">Edit Details</a></li>
                           <li><a href="#pending-transactions" data-toggle="tab">Transactions</a></li>
                           <li><a href="#appraisals" class="custom-single-selected-record-button" action="?module=&action=appraisal&todo=search_all_appraisal_record" data-toggle="tab">Appraisals</a></li>
                           <li><a href="#wish-list" onclick="nwCustomers.searchCustomerWishList();"  data-toggle="tab">Wish List</a></li>
                           <li><a href="#call-log" onclick="nwCustomers.searchCustomerCallLog();" data-toggle="tab">Call Log</a></li>
                        </ul>
                        <div class="tab-content" style="background:transparent !important;">
                           <div class="tab-pane active allow-scroll" id="recent-expenses">
							 <?php 
								if( defined("HYELLA_PACKAGE") && HYELLA_PACKAGE == "jewelry" ){
									include dirname( dirname( dirname( __FILE__ ) ) ) . "/package/" . HYELLA_PACKAGE . "/customers/display-app-view-manage/form.php";
								}else{
							?>
							  <form class="activate-ajax" method="post" id="customers" action="?action=customers&todo=save_app_changes">
								 <input type="hidden" name="id" class="form-control" value="" />
								 <br />
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Customer's Name</span>
								 <input type="text" required="required" class="form-control" name="name" />
								</div>
								<div class="row">
									<div class="col-md-6">
										<br />
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Phone</span>
										 <input type="text" class="form-control" name="phone" />
										</div>
									</div>
									<div class="col-md-6">
										<br />
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Email</span>
										 <input type="email" class="form-control" name="email" />
										</div>
									</div>
								</div>
								<br />
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Address</span>
								 <input type="text" class="form-control" name="address" />
								</div>
								<hr />
								<div class="row">
									<div class="col-md-6">
										<input type="submit" class="btn btn-lg green btn-block" value="Update" /><br />
									</div>
									<div class="col-md-6">
										<input type="reset" class="btn btn-lg dark btn-block custom-single-selected-record-button" action="?module=&action=customers&todo=delete_app_record" override-selected-record="" value="Delete" />
									</div>
								</div>
								
							  </form>
							<?php } ?>
							
						   </div>
							
							<div class="tab-pane allow-scroll" id="wish-list">
								<form class="activate-ajax" method="post" id="customer_wish_list" action="?action=customer_wish_list&todo=search_customer_wish_list">
									<input type="hidden" name="customer" class="get-customer-id" value="" />
								<br />
								<div class="row">
									<div class="col-md-4">
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">From</span>
										 <input type="date" name="start_date" class="form-control" value="<?php echo date("Y-01-01"); ?>" />
										</div>
									</div>
									<div class="col-md-4">
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">To</span>
										 <input type="date" name="end_date" class="form-control" value="<?php echo date("Y-12-31"); ?>" />
										</div>
									</div>
									<div class="col-md-4">
										<button class="btn btn-lg1 btn-block1 green" type="submit">Search</button>
										<button class="btn dark populate-with-selected custom-single-selected-record-button" action="?module=&action=customer_wish_list&todo=new_popup_form" override-selected-record="" href="#" >New Wish List</button>
									</div>
								</div>
								</form>
								<hr />
								<div id="customer-wish-list-container">
									
								</div>
								
							</div>
							<div class="tab-pane allow-scroll" id="call-log">
								
								<form class="activate-ajax" method="post" id="customer_call_log" action="?action=customer_call_log&todo=search_customer_call_log">
									<input type="hidden" name="customer" class="get-customer-id" value="" />
								<br />
								<div class="row">
									<div class="col-md-4">
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">From</span>
										 <input type="date" name="start_date" class="form-control" value="<?php echo date("Y-01-01"); ?>" />
										</div>
									</div>
									<div class="col-md-4">
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">To</span>
										 <input type="date" name="end_date" class="form-control" value="<?php echo date("Y-12-31"); ?>" />
										</div>
									</div>
									<div class="col-md-4">
										<button class="btn btn-lg1 btn-block1 green" type="submit">Search</button>
										<button class="btn dark populate-with-selected custom-single-selected-record-button" action="?module=&action=customer_call_log&todo=new_popup_form" override-selected-record="" href="#">New Call Log</button>
									</div>
								</div>
								</form>
								<hr />
								<div id="customer-call-log-container">
									
								</div>
								
								
							</div>
							<div class="tab-pane allow-scroll" id="pending-transactions">
								<form class="activate-ajax" method="post" id="customer" action="?action=customers&todo=search_customer">
								<br />
								<div class="row">
									<div class="col-md-4">
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">From</span>
										 <input type="date" name="start_date" class="form-control" value="<?php echo date("Y-01-01"); ?>" />
										</div>
									</div>
									<div class="col-md-4">
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">To</span>
										 <input type="date" name="end_date" class="form-control" value="<?php echo date("Y-12-31"); ?>" />
										</div>
									</div>
									<div class="col-md-4">
										<button class="btn btn-lg1 btn-block green" type="submit">Search</button>
									</div>
								</div>
								</form>
								<hr />
								<div id="customers-transactions">
									
								</div>
							</div>
							<div class="tab-pane allow-scroll" id="appraisals">
								<form class="activate-ajax" method="post" id="customer_wish_list" action="?action=customer_wish_list&todo=search_customer_wish_list">
									<input type="hidden" name="customer" class="get-customer-id" value="" />
								<br />
								<div class="row">
									<div class="col-md-4">
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">From</span>
										 <input type="date" name="start_date" class="form-control" value="<?php echo date("Y-01-01"); ?>" />
										</div>
									</div>
									<div class="col-md-4">
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">To</span>
										 <input type="date" name="end_date" class="form-control" value="<?php echo date("Y-12-31"); ?>" />
										</div>
									</div>
									<div class="col-md-4">
										<button class="btn btn-lg1 btn-block green" type="submit">Search</button>
									</div>
								</div>
								</form>
								<hr />
								<div id="appraisal-record-search-result">
									
								</div>
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