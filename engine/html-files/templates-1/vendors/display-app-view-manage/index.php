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
		<form class="activate-ajax" method="post" id="vendor" action="?action=vendors&todo=search_vendor">
		<div class="row">
			<div class="col-md-8">
			<select class="form-control" onchange="nwVendors.search();" placeholder="Select Vendor" name="vendor">
				<option value="">-Select Supplier / Vendor-</option>
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
			<div class="col-md-2">
				<button class="btn btn-lg1 btn-block green" type="submit">GO</button>
			 </div>
			<div class="col-md-2">
				 <button class="btn dark btn-block custom-action-button" type="button" function-name="new_vendors_popup_form" function-class="vendors" function-id="Add New Customer" skip-title="1" title="Add New Vendor">New</button>
			 </div>
			
		</div>
		</form>
		<hr />
		<div id="sales-record-search-result" class="allow-scroll">
			
		</div>

	</div>
	
	<div class="col-md-7" id="expense-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="portlet-body1" style="max-height:500px; overflow-y:auto; background:transparent !important;">
                     <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                           <li class="active"><a href="#recent-expenses" data-toggle="tab">Edit Details</a></li>
                           <li><a href="#pending-transactions" data-toggle="tab">Supply Activities</a></li>
                        </ul>
                        <div class="tab-content" style="background:transparent !important;">
                           <div class="tab-pane active" id="recent-expenses">
							
							  <form class="activate-ajax" method="post" id="vendors" action="?action=vendors&todo=save_manage_app_changes">
								 <input type="hidden" name="id" class="form-control" value="" />
								 <input type="hidden" name="date" value="<?php echo date("Y-m-d") ?>" class="form-control" value="" />
								 <br />
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Name of Vendor</span>
								 <input type="text" required="required" class="form-control" name="name_of_vendor" />
								</div>
								<br />
								<div class="input-group">
								 <span class="input-group-addon" style="color:#777;">Address</span>
								 <input type="text" class="form-control" name="address" />
								</div>
								<div class="row">
									<div class="col-md-6">
										<br />
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Phone</span>
										 <input type="text" class="form-control" name="phone" />
										</div>
										<br />
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Email</span>
										 <input type="email" class="form-control" name="email" />
										</div>
										
									</div>
									<div class="col-md-6">
										
										<br />
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Type</span>
										 <select class="form-control" name="type">
										 <?php
												$vendors = get_type_of_vendor();
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
										 <span class="input-group-addon" style="color:#777;">Comment</span>
										 <input type="text" class="form-control" name="comment" />
										</div>
									</div>
								</div>
								<hr />
								<div class="row">
									<div class="col-md-6">
										<input type="submit" class="btn btn-lg green btn-block" value="Update" />
									</div>
									<div class="col-md-6">
										<input type="reset" class="btn btn-lg dark btn-block custom-single-selected-record-button" action="?module=&action=vendors&todo=delete_app_record" override-selected-record="" value="Delete" />
									</div>
								</div>
								
							  </form>
				
						   </div>
							
							<div class="tab-pane" id="pending-transactions">
								<form class="activate-ajax" method="post" id="vendor" action="?action=vendors&todo=search_vendor">
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
								<div id="vendors-transactions">
									
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