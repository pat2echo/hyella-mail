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
	
	$hide_reserve_items = get_stock_items_on_purchase_order_settings();
	$capture_payment = get_capture_payment_on_purchase_order_settings();
	
	$unlimited_items = 1;
	$type = "";
	
	$show_staff = "";
	$save_action = "save_purchase_order";
	$label = "Purchase Order";
	$mode = "";
	
	$search_scope = 'purchase_item_search';
	
	$vendor_label = 'Vendor';
	$vendor_select_label = '-Select Vendor-';
	
	$vendor_new_label = 'New Vendor';
	$vendor_new_label_title = 'Add New Vendor';
	$vendor_new_label_action = 'new_vendors_popup_form';
	
	$vendor_search_action = '';
	$vendor_search_result_label = '';
	
	$data['allow_manage_discount'] = 1;
	$button_label = 'Confirm Order';
	
	$hide_stock_items = 0;
	
	$disable_price = 0;
	$disable_tax = 0;
	$disable_discount = 0;
	$show_quantity_expected = 0;
	
	$disable_editing_items = 0;
	$disable_deleting_items = 0;
	$disable_pricing_items = 0;
	
	$show_amount_due = 1;
	
	$supplier_invoice = 0;
	if( isset( $data['supplier_invoice'] ) && $data['supplier_invoice'] ){
		$supplier_invoice = $data['supplier_invoice'];
		$mode = "supplier_invoice";
		$label = "Supplier Invoice";
		
		$vendor_search_action = '?module=&action=expenditure&todo=expenditure_search_vendor_purchase_order';
		$vendor_search_result_label = '-Select Purchase Order-';
		$button_label = 'Save Invoice';
	}
	
	$supplier_goods_received = 0;
	if( isset( $data['supplier_goods_received'] ) && $data['supplier_goods_received'] ){
		$supplier_goods_received = $data['supplier_goods_received'];
		$mode = "supplier_goods_received";
		$label = "Goods Received Note";
		
		$vendor_search_action = '?module=&action=expenditure&todo=expenditure_search_vendor_purchase_order';
		$vendor_search_result_label = '-Select Purchase Order-';
		//$vendor_search_action = '?module=&action=expenditure&todo=expenditure_search_vendor_supplier_invoice';
		//$vendor_search_result_label = '-Select Supplier Invoice-';
		$button_label = 'Save & Re-Stock';
		
		//$disable_pricing_items = 1;
		$disable_editing_items = 1;
		$disable_deleting_items = 1;
	}
	
	$purchase_order_as_seperate_doc = get_purchase_order_settings();
	if( $purchase_order_as_seperate_doc ){
		$capture_payment = 0;
		$hide_reserve_items = 1;
		$show_staff = "Authorized By";
		$save_action = "save_purchase_order_as_seperate_doc";
		
		if( $supplier_invoice ){
			$save_action = "save_supplier_invoice_as_seperate_doc";
			$hide_stock_items = 1;
		}
		
		if( $supplier_goods_received ){
			$save_action = "save_supplier_goods_received_as_seperate_doc";
			$data['allow_manage_discount'] = 0;
			$hide_stock_items = 1;
		}
	}
	
	$qty_expected_label = 'Qty. Expected';
	$qty_label = "Qty";
	
	$general_goods_received = 0;
	if( isset( $data['general_goods_received'] ) && $data['general_goods_received'] ){
		$general_goods_received = 1;
		$mode = "general_goods_received";
		$label = "Restock Goods";
		
		$vendor_search_action = '?module=&action=expenditure&todo=expenditure_search_vendor_purchase_order';
		$vendor_search_result_label = '-Select Purchase Order-';
		//$vendor_search_action = '?module=&action=expenditure&todo=expenditure_search_vendor_supplier_invoice';
		//$vendor_search_result_label = '-Select Supplier Invoice-';
		$button_label = 'Save & Re-Stock';
		
		$disable_pricing_items = 1;
		$disable_deleting_items = 0;
		$capture_payment = 0;
		$data['allow_manage_discount'] = 0;
		$hide_reserve_items = 1;
		$save_action = "save_general_goods_received_as_seperate_doc";
		$show_staff = 'Staff Responsible';
		
		//get_vendors_supplier
		$vendor_label = 'Source of Goods';
		$vendor_select_label = '-Select Source-';
		
		$vendor_new_label = 'New Source';
		$vendor_new_label_title = 'Add New Source of Goods';
		$vendor_new_label_action = 'new_vendors_popup_form';
		
		$search_scope = 'produced_item_search';
	}
	
	if( $disable_pricing_items ){
		$show_quantity_expected = 1;
		$disable_price = 1;
		$disable_tax = 1;
		$disable_discount = 1;
		$qty_label = "Qty Received";
	}
	
	if( $general_goods_received ){
		$show_quantity_expected = 0;
		$show_amount_due = 0;
	}
	
	if( $show_quantity_expected ){
		$show_amount_due = 0;
	}
?>
<style>
	<?php if( $disable_deleting_items ){ ?>
	.delete-items-button{
		display:none;
	}
	<?php } ?>
</style>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	<?php if( ! $hide_stock_items ){ ?>
	<div class="col-md-4 "> 
		<!--grey-->
		<div class="portlet box">
			<div class="portlet-body" style="background:transparent;">
				
				<div id="cart-category">
					<div class="row">
						<div class="col-md-6">							
							<form id="search-form" <?php if( $server ){ ?> class="activate-ajax" method="post" action="?action=inventory&todo=<?php echo $search_scope; ?>" <?php } ?> >
							<div class="input-group">
								<input type="search" class="form-control input-lg1" name="search" placeholder="Search / Scan">
								<input type="hidden" name="category_filter" value="" />
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
							$dir = dirname( dirname( __FILE__ ) );
							
							foreach( $data['stocked_items'] as $sval ){
								$q = floor( $sval["quantity"] );
								$qpicked = 0;
								
								if( isset( $sval["quantity_used"] ) )$q -= $sval["quantity_used"];
								if( isset( $sval["quantity_sold"] ) )$q -= $sval["quantity_sold"];
								if( isset( $sval["quantity_damaged"] ) )$q -= $sval["quantity_damaged"];
								if( isset( $sval["quantity_picked"] ) )$qpicked = $sval["quantity_picked"];
								
								$do_not_show_all = 0;
								$qty_left_after_order = 0;
								switch( $type ){
								case "sales_order":
									$do_not_show_all = 1;
									if( isset( $sval["quantity_ordered"] ) )$qty_left_after_order = $q - $sval["quantity_ordered"];
									if( $sval["quantity_ordered"] == $qpicked )$qty_left_after_order = 0;
								break;
								}
								$q -= $qpicked;
								
								if( $do_not_show_all && $sval["type"] == "service" ){
									continue;
								}
								
								if( ! $unlimited_items ){
									if( $sval["type"] != "service" )
										if( ! $q )continue;
								}
								$sval["selling_price"] = $sval["cost_price"];
								
								include  $dir . "/display-shopping-cart-advance/cart-item-list.php";
							}
						}
					?>
				</div>
				
			</div>
		</div>
		
	</div>
	<?php } ?>
	
	<div <?php if( ! $hide_stock_items ){ ?>class="col-md-5 "<?php }else{ ?> class="col-md-7 col-md-offset-1" <?php } ?> id="stock-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="row" style="margin-top:12px; ">
				
				<?php 
					switch( $mode ){
					case "supplier_invoice":
					case "supplier_goods_received": 
				?>
				<div class="col-md-5">	
					<select class="form-control select2" name="vendor">
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
					 <button class="btn dark custom-single-selected-record-button" style="display:none;" action="<?php echo $vendor_search_action; ?>" id="filter-vendor-info" type="button" title="View Details"><i class="icon-external-link"></i></button>
				</div>
				<div class="col-md-7">
					<div class="input-group">
					  <select class="form-control select2" name="purchase_order" data-default="<?php echo $vendor_search_result_label; ?>">
						<option value=""><?php echo $vendor_search_result_label; ?></option>
					 </select>
					 <span class="input-group-btn">
					 <button class="btn dark custom-single-selected-record-button" action="?module=&action=expenditure&todo=view_invoice" id="view-details-info" type="button" title="View Details"><i class="icon-external-link"></i>&nbsp;</button>
					 </span>
					 </div>
					 <button class="btn dark custom-single-selected-record-button" style="display:none;" action="?module=&action=expenditure&todo=get_invoice_info" id="repopulate-item-button" type="button" title="View Details"><i class="icon-external-link"></i></button>
					 <br />
				</div>
				<?php 
					break;
					default:
				?>
				<div class="col-md-12">	
					<div class="input-group">
					 <span class="input-group-addon" style="color:#777;"><?php echo $vendor_label; ?></span>
					  <select class="form-control select2" name="vendor">
						<option value=""><?php echo $vendor_select_label; ?></option>
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
					 <button class="btn dark custom-single-selected-record-button" action="?module=&action=vendors&todo=view_vendor_details" id="view-vendor-info" type="button" title="View Details"><i class="icon-external-link"></i>&nbsp;</button>
					 
					 <button class="btn dark custom-action-button" type="button" function-name="<?php echo $vendor_new_label_action; ?>" function-class="vendors" function-id="Add New Vendor" skip-title="1" title="<?php echo $vendor_new_label_title; ?>"><?php echo $vendor_new_label; ?></button>
					 </span>
					 </div>
					 <br />
				</div>
				<?php
					break;
					} 
				?>
			</div>
			<div class="portlet-body1" style="max-height:450px; overflow-y:auto; background:transparent !important;">
                     <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                           <li class="active"><a href="#new-stock" data-toggle="tab"><?php echo $label; ?></a></li>
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
													  
													  <?php if( ! $disable_price ){ ?>
													  <th class="r">Price</th>
													  <?php } ?>
													  
													  <?php if( $show_quantity_expected ){ ?>
													  <th class="r"><?php echo $qty_expected_label; ?></th>
													  <?php } ?>
													  
													  <th class="r"><?php echo $qty_label; ?></th>
													  
													  <?php if( ! $disable_discount ){ ?>
													  <th class="r">% Discount</th>
													  <?php } ?>
													  
													  <?php if( ! $disable_tax ){ ?>
													  <th class="r">% Tax</th>
													  <?php } ?>
													  
													  <?php if( ! $disable_price ){ ?>
													  <th class="r">Total</th>
													  <?php } ?>
												   </tr>
												</thead>
												<tbody>
												
												</tbody>
												<tfoot>
												   
												</tfoot>
												</table>
											</div>
											<hr />
											
											<?php if( ! $disable_editing_items ){ ?>
											<div id="item-edit-template">
											  <span class="1"><button class="btn btn-sm dark delete-items-button" onclick="nwCart.deleteCartItem();"><i class="icon-trash"></i> delete</button> <button onclick="nwCart.saveCartItemEdit();" class="btn btn-sm dark"><i class="icon-save"></i> save</button> </span>
											  <span class="2"><form onsubmit="nwCart.saveCartItemEdit(); return false;"><input type="number" class="form-control cost-price" step="any" min="0" style="width:90px; float:right" /></form></span>
											  <span class="3"><form onsubmit="nwCart.saveCartItemEdit(); return false;"><input type="number" class="form-control quantity" min="0" style="width:90px; float:right" /></form></span>
											  
											  <span class="4"><form onsubmit="nwCart.saveCartItemEdit(); return false;"><input type="number" class="form-control discount" min="0" max="100" style="width:70px; float:right" /></form></span>
											  <span class="5"><form onsubmit="nwCart.saveCartItemEdit(); return false;"><input type="number" class="form-control tax" min="0" max="100" style="width:70px; float:right" /></form></span>
										   </div>
											<?php } ?>
											
											<?php 
												$dstyle = " display:none; ";
												if( isset( $data['allow_manage_discount'] ) && $data['allow_manage_discount'] ){
													$dstyle = "";
												}
										   ?>
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
											  <br />
										  </div>
											
											<?php 
												$dstyle = " display:none; ";
												if( isset( $data['allow_manage_discount'] ) && $data['allow_manage_discount'] ){
													$dstyle = "";
												}
										   ?>
											<div id="tax-container-1" style="<?php echo $dstyle; ?>">
												<div class="input-group">
												 <span class="input-group-addon" style="color:#777;">Tax</span>
												  <select class="form-control" id="tax">
													<?php if( file_exists( dirname( dirname( dirname( __FILE__ ) ) ).'/globals/shopping-cart-discount-list.php' ) )
															include dirname( dirname( dirname( __FILE__ ) ) ).'/globals/shopping-cart-discount-list.php'; ?>
												 </select>
												 
												 <span class="input-group-btn">
												 <button class="btn dark custom-action-button" type="button" function-name="manage_discount" function-class="discount" function-id="Manage Tax" skip-title="1" > <i class="icon-edit"></i>&nbsp;</button>
												 </span>
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
	<div class="col-md-3" id="cart-checkout-container"> 
		<h3>Summary</h3>
		<hr />
		<?php if( $show_amount_due ){ ?>
		<div class="input-group">
		 <span class="input-group-addon" style=" line-height: 1.5;">Amount Due</span>
		 <span  class="input-group-addon amount-due" style="background: #A7E862; line-height: 1.5;">0.00</span>
		</div>
		<hr />
		<?php } ?>
		
		<?php if( $capture_payment ){ ?>
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
		<br />
		<?php } ?>
		
		<div <?php if( $hide_reserve_items ){ ?> style="display:none;" <?php } ?>>
		
		<div class="checkbox-list">
			<label>
			<input type="checkbox" <?php if( ! $hide_reserve_items ){ ?> checked="checked" <?php } ?> id="sales-status"> Add Items to Stock
			</label>
		 </div>
		 <br />
		</div>
		
		<?php if( $show_staff ){ ?>
		<div id="use-staff-responsible">
			<div class="input-group">
				 <span class="input-group-addon" style="color:#777;"><?php echo $show_staff; ?></span>
				  <select class="form-control" name="staff_responsible">
					<?php
						if( isset( $data['staff_responsible'] ) && is_array( $data['staff_responsible'] ) ){
							$c = "";
							if( isset( $user_info["user_id"] ) && $user_info["user_id"] ){
								$c = $user_info["user_id"];
							}
							foreach( $data['staff_responsible'] as $key => $val ){
								$sel = '';
								if( $c == $key ){
									$sel = ' selected="selected" ';
								}
								?>
								<option value="<?php echo $key; ?>" <?php echo $sel; ?>>
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
		<?php } ?>
		
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Comment</span>
		 <input type="text" class="form-control" name="comment" placeholder="Optional Comment / Terms" />
		</div>
		
		<hr />
		<div class="btn-group btn-group-justified">
			
			<a class="btn btn-lg default custom-single-selected-record-button" id="cart-cancel" href="#">Cancel</a>
			
			<a class="btn btn-lg red custom-single-selected-record-button" action="?module=&action=cart&todo=<?php echo $save_action; ?>" id="cart-finish" href="#"><?php echo $button_label; ?></a>
			
		</div>
	</div>
</div>

<script type="text/javascript" class="auto-remove">
	var server = <?php echo $server; ?>;
	var capture_payment = <?php echo $capture_payment; ?>;
	
	var g_show_quantity_expected = <?php echo $show_quantity_expected; ?>;
	var g_disable_price = <?php echo $disable_price; ?>;
	var g_disable_discount = <?php echo $disable_discount; ?>;
	var g_disable_tax = <?php echo $disable_tax; ?>;
	var g_disable_editing_items = <?php echo $disable_editing_items; ?>;
	
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>