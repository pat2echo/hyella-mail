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
	
	$sales_order = 0;
	$vat = 0;
	$service_charge = 0;
	$service_tax = 0;
	$hide_reserve_items = 0;
	$show_change_on_payment = 0;
	
	if( ( isset( $data["surcharge"]["HIDE RESERVE ITEMS"] ) && $data["surcharge"]["HIDE RESERVE ITEMS"] ) ){
		$hide_reserve_items = $data["surcharge"]["HIDE RESERVE ITEMS"];
	}
	if( ( isset( $data["surcharge"]["VAT"] ) && $data["surcharge"]["VAT"] ) ){
		$vat = $data["surcharge"]["VAT"];
	}
	if( ( isset( $data["surcharge"]["SERVICE CHARGE"] ) && $data["surcharge"]["SERVICE CHARGE"] ) ){
		$service_charge = $data["surcharge"]["SERVICE CHARGE"];
	}
	if( ( isset( $data["surcharge"]["SERVICE TAX"] ) && $data["surcharge"]["SERVICE TAX"] ) ){
		$service_tax = $data["surcharge"]["SERVICE TAX"];
	}
	if( ( isset( $data["surcharge"]["SHOW CUSTOMER CHANGE DURING PAYMENT"] ) && $data["surcharge"]["SHOW CUSTOMER CHANGE DURING PAYMENT"] ) ){
		$show_change_on_payment = $data["surcharge"]["SHOW CUSTOMER CHANGE DURING PAYMENT"];
	}
	
	$unlimited_items = 0;
	$disable_line_discount = 0;
	if( ( isset( $data["surcharge"]["DISABLE ITEM DISCOUNT"] ) && $data["surcharge"]["DISABLE ITEM DISCOUNT"] ) ){
		$disable_line_discount = $data["surcharge"]["DISABLE ITEM DISCOUNT"];
	}
	
	$capture_payment = get_capture_payment_on_sales_settings();
	
	$label1 = 'Checkout';
	$label2 = 'Point of Sale';
	$label3 = 'Confirm Sale';
	
	$type = "";
	
	if( isset( $data["sales_order"] ) && $data["sales_order"] ){
		$hide_reserve_items = 1;
		$sales_order = 1;
		$type = "sales_order";
		
		$label1 = 'Sales Order';
		$label2 = 'Ordered Items';
		$label3 = 'Confirm Order';
		
		$unlimited_items = get_unlimited_items_in_sales_order_settings();
	}else{
		$capture_payment = 1;
	}
	
	$fixed_discount_type = 'fixed_value_after_tax';
	$fixed_discount_type = 'fixed_value';
	
	
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
							<form id="search-form" <?php if( $server ){ ?> class="activate-ajax" method="post" action="?action=inventory&todo=pos_item_search" <?php } ?> >
							<div class="input-group">
								<input type="search" class="form-control input-lg1" name="search" placeholder="Search / Scan">
								<input type="hidden" name="category_filter" value="" />
								<input type="hidden" name="type" value="<?php echo $type; ?>" />
								<span class="input-group-btn">
									<button class="btn btn-lg1 dark" style="border: 1px solid #555;" <?php if( $server ){ ?> onclick="nwCurrentStore.showAllItems(); nwCart.submitSearchForm();" <?php }else{ ?> onclick="nwCurrentStore.showAllItems();" <?php } ?> type="reset"><i class="icon-remove"></i>&nbsp;</button>
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
								<div class="btn-group pull-right item-filter-box">
									<button type="button" class="btn dark btn-lg1 item-filter-display-text">All Categories</button>
									<button type="button" class="btn dark btn-lg1 dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"><i class="icon-angle-down"></i>&nbsp;</button>
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
							foreach( $data['stocked_items'] as $sval ){
								$q = floor( $sval["quantity"] );
								$qpicked = 0;
								
								if( isset( $sval["quantity_used"] ) )$q -= $sval["quantity_used"];
								if( isset( $sval["quantity_sold"] ) )$q -= $sval["quantity_sold"];
								if( isset( $sval["quantity_damaged"] ) )$q -= $sval["quantity_damaged"];
								if( isset( $sval["quantity_picked"] ) )$qpicked = $sval["quantity_picked"];
								
								$do_not_show_all = 0;
								switch( $type ){
								case "sales_order":
									$do_not_show_all = 1;
									if( isset( $sval["quantity_ordered"] ) )$qty_left_after_order = $q - $sval["quantity_ordered"];
									if( isset( $qty_left_after_order ) && $sval["quantity_ordered"] == $qpicked )unset( $qty_left_after_order );
								break;
								}
								$q -= $qpicked;
								
								if( $do_not_show_all && $sval["type"] == "service" ){
									continue;
								}
								
								if( ! $unlimited_items ){
									if( $sval["type"] != "service" )
										if( $q < 1 )continue;
								}
								
								include "cart-item-list.php";
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
					 <span class="input-group-addon" style="color:#777;">Customer</span>
					  <select class="form-control" name="customer">
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
					 <span class="input-group-btn">
					 <button class="btn dark custom-single-selected-record-button" action="?module=&action=customers&todo=view_customer_details" id="view-customer-info" type="button" title="View Details"><i class="icon-external-link"></i>&nbsp;</button>
					 <button class="btn dark custom-action-button" type="button" function-name="new_customer_popup_form" function-class="customers" function-id="Add New Customer" skip-title="1" title="Add New Customer">New Customer</button>
					 </span>
					 </div>
					 <br />
				</div>
			</div>
			<div class="portlet-body1" style="max-height:450px; overflow-y:auto; background:transparent !important;">
                     <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                           <li class="active"><a href="#new-stock" onclick="nwCart.showPOS(); return false;" data-toggle="tab"><?php echo $label2 ; ?></a></li>
                           <!--<li><a href="#catalogue" onclick="nwCart.showCatalogue(); return false;" data-toggle="tab">Catalogue</a></li>-->
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
													  <th class="r">S.Price</th>
													  <th class="r">Qty</th>
													  <?php if( ! $disable_line_discount ){ ?>
													  <th class="r">Discount</th>
													  <?php } ?>
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
											  <span class="3"><form onsubmit="nwCart.saveCartItemEdit(); return false;"><input type="number" class="form-control quantity" min="0" style="width:90px; float:right" /></form></span>
											  <span class="4"><form onsubmit="nwCart.saveCartItemEdit(); return false;"><input type="number" class="form-control discount" step="any" min="0" style="width:90px; float:right" /></form></span>
										   </div>
										  
											<?php 
												$dstyle = " display:none; ";
												if( isset( $data['allow_manage_discount'] ) && $data['allow_manage_discount'] ){
													$dstyle = "";
												}
										   ?>
										   <!--
											<div id="discount-container">
												<div class="input-group">
												 <span class="input-group-addon" style="color:#777;">Discount</span>
												 <input type="number" step="any" class="form-control" id="discount" placeholder="Enter Value to Discount" value="0">
												</div>
												<hr />
											</div>
											-->
											<div id="discount-container-1" style="<?php echo $dstyle; ?>">
												<div class="input-group">
												 <span class="input-group-addon" style="color:#777;">Discount</span>
												  <select class="form-control" id="discount">
													<?php if( file_exists( dirname( dirname( dirname( __FILE__ ) ) ).'/globals/shopping-cart-discount-list.php' ) )
															include dirname( dirname( dirname( __FILE__ ) ) ).'/globals/shopping-cart-discount-list.php'; ?>
												 </select>
												 
												 <span class="input-group-btn">
												 <button class="btn dark custom-action-button" type="button" function-name="manage_discount" function-class="discount" function-id="Manage Discount" skip-title="1" > <i class="icon-edit"></i>&nbsp;</button>
												 </span>
											  </div>
											  <hr />
										  </div>
										  
										</div>
									</div>
									
								</div>
								
							  
                           </div>
							
						   <div class="tab-pane" id="catalogue" >
								<div class="clearfix">
									<a href="#" class="btn btn-sm default green-stripe" onclick="nwCart.showCatalogue(); return false;">View Items in Cart</a>
									<a href="#" class="btn btn-sm default green-stripe custom-single-selected-record-button" action="?module=&action=items&todo=show_item_catalogue" id="view-items" style="display:none;">View Items</a>
									<a href="#" class="btn btn-sm default blue-stripe" onclick="nwCart.checkOutCatalogue(); return false;">Proceed To Checkout</a>
									<a href="#" class="btn btn-sm default red-stripe" onclick="nwCart.clearCatalogue(); return false;">Clear All</a>
								</div>
								<div id="catalogue-container">
								</div>
						   </div>
						   
						   <div class="tab-pane" id="appraisal" >
								<div class="clearfix">
									<a href="#" class="btn btn-sm default green-stripe custom-single-selected-record-button" action="?module=&action=items&todo=show_item_appraisal" id="view-appraisal" style="display:none;">Set Appraisal Value</a>
									
									<a href="#" class="btn btn-sm default green-stripe custom-single-selected-record-button" action="?module=&action=appraisal&todo=save_and_preview_item_appraisal" id="print-appraisal" style="display:none;">Save & View Appraisal Certificate</a>
									
									<a href="#" class="btn btn-sm default green-stripe" onclick="nwCart.printAppraisal(); return false;" >View Appraisal Certificate</a>
									<a href="#" class="btn btn-sm default red-stripe" onclick="nwCart.clearAppraisal(); return false;">Clear All</a>
								</div>
								<div id="appraisal-container">
								</div>
						   </div>
					   </div>
                     </div>
                     
                  </div>
				
		</div>
	</div>
	<div class="col-md-3" id="cart-checkout-container"> 
		<h3><?php echo $label1 ; ?></h3>
		<hr />
		<div class="input-group">
		 <span class="input-group-addon" style=" line-height: 1.5;">Amount Due</span>
		 <span  class="input-group-addon amount-due" style="background: #A7E862; line-height: 1.5;">0.00</span>
		</div>
		<hr />
		<?php if( $capture_payment ){ ?>
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
		<div id="use-room-number">
			<br />
			 <div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Room</span>
			  <select class="form-control" name="room">
				<option value="">-Select Room-</option>
				<?php
					if( isset( $data['rooms'] ) && is_array( $data['rooms'] ) ){
						foreach( $data['rooms'] as $key => $val ){
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
		
		<div id="use-staff-responsible">
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
		<br />
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Amount Paid</span>
		 <input type="number" step="any" class="form-control" id="amount-paid" value="0">
		</div>
		<?php if( $show_change_on_payment ){ ?>
		<br />
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Change</span>
		 <span  class="input-group-addon" id="customer-change" style="background: #A7E862;">0.00</span>
		</div>
		<?php } ?>
		
		<hr />
		<?php } ?>
		
		<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Comment</span>
			  <input type="text" class="form-control" name="comment" placeholder="Optional Comment" />
		  </div>

		<div <?php if( $hide_reserve_items ){ ?> style="display:none;" <?php } ?>>
		<br />
		<div class="checkbox-list">
			<label>
			<input type="checkbox" id="sales-status"> Reserve Items <small>{selected items would be reserved for the customer}</small>
			</label>
		 </div>
		</div>
		
		<hr />
		<div class="btn-group btn-group-justified">
			
			<a class="btn btn-lg default custom-single-selected-record-button" id="cart-cancel" href="#">Cancel</a>
			
			<a class="btn btn-lg red custom-single-selected-record-button" action="?module=&action=cart&todo=checkout" id="cart-finish" href="#"><?php echo $label3 ; ?></a>
			
		</div>
	</div>
	
</div>

<script type="text/javascript" class="auto-remove">
	var server = <?php echo $server; ?>;
	
	var vat = <?php echo $vat; ?>;
	var service_charge = <?php echo $service_charge; ?>;
	var service_tax = <?php echo $service_tax; ?>;
	var capture_payment = <?php echo $capture_payment; ?>;
	var global_sales_order = <?php echo $sales_order; ?>;
	
	var global_disable_line_discount = <?php echo $disable_line_discount; ?>;
	var global_unlimited_items = <?php echo $unlimited_items; ?>;
	var global_fixed_discount_type = "<?php echo $fixed_discount_type; ?>";
	
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>