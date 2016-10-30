<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	
	$package = "";
	if( defined( "HYELLA_PACKAGE" ) ){
		$package = HYELLA_PACKAGE;
	}
	
	$server = 0;
	if( defined( "HYELLA_SERVER_FILTER" ) && HYELLA_SERVER_FILTER ){
		$server = 1;
	}
	
	$hide_reserve_items = 0;
	if( ( isset( $data["surcharge"]["HIDE RESERVE ITEMS"] ) && $data["surcharge"]["HIDE RESERVE ITEMS"] ) ){
		$hide_reserve_items = $data["surcharge"]["HIDE RESERVE ITEMS"];
	}
?>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	
	<div class="col-md-4 "> 
		<!--grey-->
		<div class="portlet box">
			<div class="portlet-body" style="background:transparent;">
				
				<div id="cart-category">
					<div class="row">
						<div class="col-md-6">							
							<form id="search-form" <?php if( $server ){ ?> class="activate-ajax" method="post" action="?action=inventory&todo=purchase_item_search" <?php } ?> >
							<div class="input-group">
								<input type="search" class="form-control input-lg1" name="search" placeholder="Search / Scan">
								<input type="hidden" name="category_filter" value="" />
								<span class="input-group-btn">
									<button class="btn btn-lg1 dark" style="border: 1px solid #555;" <?php if( $server ){ ?> onclick="nwCurrentStore.showAllItems(); nwInventory.submitSearchForm();" <?php }else{ ?> onclick="nwCurrentStore.showAllItems();" <?php } ?> type="reset"><i class="icon-remove"></i> </button>
								 </span>
							</div>
							</form>
						</div>
						<div class="col-md-6">
							<div class="btn-toolbar ">
								<?php if( $server ){ ?>
								<select class="form-control" id="item-filter">
									<option value="">All Categories</option>
									<?php
										if( isset( $data['categories'] ) && is_array( $data['categories'] ) ){
											foreach( $data['categories'] as $key => $val ){
												?>
												<option value="<?php echo $key; ?>">
													<?php echo $val; ?>
												</option>
												<?php
											}
										}
									?>
								</select>
								<?php }else{ ?>
								<div class="btn-group item-filter-box">
									<button type="button" class="btn dark btn-lg1 item-filter-display-text">All Categories</button>
									<button type="button" class="btn dark btn-lg1 dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"><i class="icon-angle-down"></i></button>
									<ul class="dropdown-menu " role="menu">
										<li><a href="#" class="category item-filter" id="all">All Categories</a></li>
										<li class="divider"></li>
										<?php
											if( isset( $data['categories'] ) && is_array( $data['categories'] ) ){
												foreach( $data['categories'] as $key => $val ){
													?>
													<li><a href="#" class="category item-filter" id="<?php echo $key; ?>">
														<?php echo $val; ?>
													</a></li>
													<?php
												}
											}
										?>
									</ul>
								</div>
								<!-- /btn-group -->
								<?php } ?>
							</div>
							
						</div>
					</div>
					
					<hr />
					
				</div>
				<div id="stocked-items" class="row" style="max-height:420px; overflow-y:auto; overflow-x:hidden;">
					<?php
						if( isset( $data['stocked_items'] ) && is_array( $data['stocked_items'] ) ){
							$dir = dirname( dirname( __FILE__ ) );
							
							foreach( $data['stocked_items'] as $sval ){
								$q = $sval["quantity"];
								if( isset( $sval["quantity_used"] ) )$q -= $sval["quantity_used"];
								if( isset( $sval["quantity_sold"] ) )$q -= $sval["quantity_sold"];
								
								if( $sval["type"] != "service" )
									if( ! $q )continue;
								
								include  $dir . "/display-shopping-cart-advance/cart-item-list.php";
							}
						}
					?>
				</div>
				
			</div>
		</div>
		
	</div>
	
	<div class="col-md-5 " id="stock-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="row" style="margin-top:12px; ">
				<div class="col-md-12">	
					<div class="input-group">
					 <span class="input-group-addon" style="color:#777;">Vendor</span>
					  <select class="form-control" name="vendor">
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
					 <span class="input-group-btn">
					 <button class="btn dark custom-single-selected-record-button" action="?module=&action=vendors&todo=view_vendor_details" id="view-vendor-info" type="button" title="View Details"><i class="icon-external-link"></i></button>
					 <button class="btn dark custom-action-button" type="button" function-name="new_vendors_popup_form" function-class="vendors" function-id="Add New Vendor" skip-title="1" title="Add New Vendor">New Vendor</button>
					 </span>
					 </div>
					 <br />
				</div>
			</div>
			<div class="portlet-body1" style="max-height:450px; overflow-y:auto; background:transparent !important;">
                     <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                           <li class="active"><a href="#new-stock" data-toggle="tab">Purchase Order</a></li>
                        </ul>
                        <div class="tab-content" style="background:transparent !important;">
                           <div class="tab-pane active" id="new-stock">
								
								<div class="row">
									
									<div class="col-md-12">
										 
										<div class="shopping-cart-table allow-scroll-1" style="background:transparent !important;">
											<div class="table-responsive">
												<table class="table table-striped table-hover bordered">
												<thead>
												   <tr>
													  <th>Item</th>
													  <th class="r">Cost Price</th>
													  <th class="r">Qty</th>
													  <th class="r">Total</th>
												   </tr>
												</thead>
												<tbody>
												
												</tbody>
												<tfoot>
												   
												</tfoot>
												</table>
											</div>
											<hr />
											<div id="item-edit-template">
											  <span class="1"><button class="btn btn-sm dark" onclick="nwCart.deleteCartItem();"><i class="icon-trash"></i> delete</button> <button onclick="nwCart.saveCartItemEdit();" class="btn btn-sm dark"><i class="icon-save"></i> save</button> </span>
											  <span class="2"><form onsubmit="nwCart.saveCartItemEdit(); return false;"><input type="number" class="form-control cost-price" step="any" min="0" style="width:90px; float:right" /></form></span>
											  <span class="3"><form onsubmit="nwCart.saveCartItemEdit(); return false;"><input type="number" class="form-control quantity" min="0" style="width:90px; float:right" /></form></span>
										   </div>
										  
											<?php 
												$dstyle = " display:none; ";
												if( isset( $data['allow_manage_surcharge'] ) && $data['allow_manage_surcharge'] ){
													$dstyle = "";
												}
										   ?>
										   <div style="<?php echo $dstyle; ?>">
											<div id="surcharge-container">
												<div class="input-group">
												 <span class="input-group-addon" style="color:#777;">Surcharge</span>
												 <input type="number" step="any" class="form-control" id="surcharge" placeholder="Enter Extra Charge" value="0">
												</div>
												<hr />
											</div>
											</div>
											
										</div>
									</div>
									
								</div>
								
							  
                           </div>
							
					   </div>
                     </div>
                     
                  </div>
				
		</div>
	</div>
	<div class="col-md-3" id="cart-checkout-container"> 
		<h3>Summary</h3>
		<hr />
		<div class="input-group">
		 <span class="input-group-addon" style=" line-height: 1.5;">Amount Due</span>
		 <span  class="input-group-addon amount-due" style="background: #A7E862; line-height: 1.5;">0.00</span>
		</div>
		<hr />
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Amount Paid</span>
		 <input type="number" step="any" class="form-control" id="amount-paid" value="0">
		</div>
		<hr />
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Payment Method</span>
		 <select class="form-control" name="payment_method">
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
			  
		<div <?php if( $hide_reserve_items ){ ?> style="display:none;" <?php } ?>>
		<br />
		<div class="checkbox-list">
			<label>
			<input type="checkbox" checked="checked" id="sales-status"> Add Items to Stock
			</label>
		 </div>
		</div>
		<br />
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Comment</span>
		 <input type="text" class="form-control" name="comment" placeholder="Optional Comment" />
		</div>
		
		<hr />
		<div class="btn-group btn-group-justified">
			
			<a class="btn btn-lg default custom-single-selected-record-button" id="cart-cancel" href="#">Cancel</a>
			
			<a class="btn btn-lg red custom-single-selected-record-button" action="?module=&action=cart&todo=save_purchase_order" id="cart-finish" href="#">Confirm Order</a>
			
		</div>
	</div>
</div>

<script type="text/javascript" class="auto-remove">
	var server = <?php echo $server; ?>;
	
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>