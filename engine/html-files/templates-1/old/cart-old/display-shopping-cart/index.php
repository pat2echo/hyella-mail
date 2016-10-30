<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	
	$server = 0;
	if( defined( "HYELLA_SERVER_FILTER" ) && HYELLA_SERVER_FILTER ){
		$server = 1;
	}
	
	//remove once cart has been test for server side
	$server = 0;
	
	$vat = 0;
	$service_charge = 0;
	$service_tax = 0;
?>
<!--EXCEL IMPOT FORM-->
<div class="row" id="cart-checkout-container">
	<div class="col-md-3 col-md-offset-1">
		<h3>Checkout</h3>
		<hr />
		<div class="input-group">
		 <span class="input-group-addon" style=" font-size: 18px; line-height: 1.5;">Amount Due</span>
		 <span  class="input-group-addon amount-due" style="background: #A7E862; font-size: 18px; line-height: 1.5;">0.00</span>
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
		<hr />
		<div class="btn-group btn-group-justified">
			
			<a class="btn btn-lg default custom-single-selected-record-button" id="cart-cancel" href="#">Cancel</a>
			
			<a class="btn btn-lg red custom-single-selected-record-button" action="?module=&action=cart&todo=checkout" id="cart-finish" href="#">Confirm Sale</a>
			
		</div>
	</div>
	<div class="col-md-6 col-md-offset-1 allow-scroll" >
		
		<div>
			<h3>Additional Information</h3>
			<hr />
			<div class="input-group">
				 <span class="input-group-addon" style="color:#777;">Date</span>
				  <input type="date" value="<?php echo date("Y-m-d"); ?>" placeholder="yyyy-mm-dd" class="form-control" name="date" />
			  </div>
			<hr />
			<div id="select-customer">
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
				 <button class="btn dark" type="button" onclick="nwCart.showNewCustomerForm();">New Customer</button>
				 </span>
			  </div>
			  </div>
			  
			 <div id="new-customer" >
					<div class="input-group" >
					  <input type="text" class="form-control" name="customer_name" placeholder="Customer Name" style="width:60%;" />
					  <input type="tel" class="form-control" name="customer_phone" placeholder="Phone Number" style="width:40%;" />
					 <span class="input-group-btn">
					 <button class="btn dark" type="button" onclick="nwCart.showSelectCustomerForm();">Cancel</button>
					 </span>
				  </div>
			  </div>
			
			 <div id="use-room-number">
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
			
			<hr />
			<div class="input-group">
				 <span class="input-group-addon" style="color:#777;">Sales Rep</span>
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

			<hr />
			<div class="input-group">
				 <span class="input-group-addon" style="color:#777;">Comment</span>
				  <input type="text" class="form-control" name="comment" placeholder="Optional Comment" />
			  </div>

			<hr />
			
			<div class="checkbox-list">
				<label>
				<input type="checkbox" id="sales-status"> Reserve Items <small>{selected items would be reserved for the customer}</small>
				</label>
			 </div>
			 
		</div>
		
	</div>
	
</div>
<div class="row">
	<div class="col-md-6"> 
		<!--grey-->
		<div class="portlet  box">
			<div class="portlet-body allow-scroll" style=" background:transparent;">
				
				<div id="cart-category">
					<form id="search-form" <?php if( $server ){ ?> class="activate-ajax" method="post" action="?action=inventory&todo=pos_item_search" <?php } ?>>
					<div class="input-group">
						<input type="search" class="form-control input-lg" style="" name="search" placeholder="Search / Scan Barcode" />
						<input type="hidden" name="category_filter" value="" />
						<span class="input-group-btn">
							<button class="btn btn-lg green" style="border: 1px solid #35AA47;" type="submit">Go</button>
						 </span>
					</div>
					</form>
					<hr />
					<div id="cart-category-items" class="active-category-box">
					<?php
						if( isset( $data['categories'] ) && is_array( $data['categories'] ) ){
							foreach( $data['categories'] as $key => $val ){
								?>
								<a href="#" class="category icon-btn catid-<?php echo $key; ?>" id="<?php echo $key; ?>">
									<i class="icon-th"></i>
									<div><?php echo $val; ?></div>
									<span class="badge badge-success"></span>
								</a>
								<?php
							}
						}
					?>
					</div>
				</div>
				<div id="stocked-items" class="row">
					
					<div class="col-md-4 col-sm-3 cart-item go-back">
						<div class="item-container">
							<span class="badge badge-success"></span>
							<div class="item-image">
								<img src="<?php echo $site_url . "images/back.png"; ?>" >
							</div>
						  <p class="item-title">
							Go Back
						  </p>
						  <p class="item-price">
							&nbsp;
						  </p>
						</div>
					</div>
					<style type="text/css">
					#cart-category-items .category{
						display:none !important;
					}
					</style>
					<?php
						$non_empty_categories = array();
						if( isset( $data['stocked_items'] ) && is_array( $data['stocked_items'] ) ){
							
							foreach( $data['stocked_items'] as $sval ){
								if( $sval["type"] == "service" ){
									$q = 1;
								}else{
									$q = $sval["quantity"];
									if( isset( $sval['quantity_sold'] ) )$q -= $sval['quantity_sold'];
									if( isset( $sval['quantity_used'] ) )$q -= $sval['quantity_used'];
									
									if( ! $q )continue;
								}
								
								$non_empty_categories[ $sval["category"] ] = "catid-" . $sval["category"];
								
								include "cart-item-list.php";
							}
							
							if( ! empty( $non_empty_categories ) ){
								$cartegory_ids = implode( ",#cart-category-items a.", $non_empty_categories );
								?>
								<style type="text/css">
									<?php echo "#cart-category-items a." . $cartegory_ids; ?>{
										display:inline-block !important;
									}
								</style>
								<?php
							}
						}
					?>
				</div>
				
			</div>
		</div>
		
	</div>
	
	<div class="col-md-6" id="main-table-view"> 
		
		<div style="background:transparent !important; border-color:#fff !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small>Shopping Cart</small></div>
			</div>
			<div class="portlet-body shopping-cart-table " style="background:transparent !important;">
				<div class="table-responsive allow-scroll-2">
					<table class="table table-striped table-hover bordered">
					<thead>
					   <tr>
						  <th>Item</th>
						  <th class="r">Cost</th>
						  <th class="r">Quantity</th>
						  <th class="r">Total</th>
					   </tr>
					</thead>
					<tbody>
					   
					</tbody>
					<tfoot>
					   
					</tfoot>
					</table>
				</div>
				<br />
				<div id="item-edit-template">
				  <span class="1"><button class="btn btn-sm1 dark" onclick="nwCart.deleteCartItem();"><i class="icon-trash"></i> delete</button> <button onclick="nwCart.saveCartItemEdit();" class="btn btn-sm1 dark"><i class="icon-save"></i> save</button> </span>
				  <span class="3"><form onsubmit="nwCart.saveCartItemEdit(); return false;"><input type="number" class="form-control quantity" style="width:90px; float:right" /></form></span>
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
				 <button class="btn dark custom-action-button" type="button" function-name="manage_discount" function-class="discount" function-id="Manage Discount" skip-title="1" >Manage Discount</button>
				 </span>
			  </div>
			  <?php 
				if( ( isset( $data["surcharge"]["VAT"] ) && $data["surcharge"]["VAT"] ) || ( isset( $data["surcharge"]["SERVICE CHARGE"] ) && $data["surcharge"]["SERVICE CHARGE"] ) || ( isset( $data["surcharge"]["SERVICE TAX"] ) && $data["surcharge"]["SERVICE TAX"] ) ){
			  ?>
			  <br />
			  <div class="input-group">
				 <?php if( ( isset( $data["surcharge"]["VAT"] ) && $data["surcharge"]["VAT"] ) ){ ?>
				 <span class="input-group-addon"><strong><small>VAT <?php $vat = $data["surcharge"]["VAT"]; echo $data["surcharge"]["VAT"]; ?>%</small></strong></span>
				 <span  class="input-group-addon vat-amount-due" style="background: #A7E862;">0.00</span>
				 <?php } ?>
				 
				  <?php if( isset( $data["surcharge"]["SERVICE TAX"] ) && $data["surcharge"]["SERVICE TAX"] ){  ?>
				 <span class="input-group-addon"><strong><small>SERVICE TAX <?php $service_tax = $data["surcharge"]["SERVICE TAX"]; echo $data["surcharge"]["SERVICE TAX"]; ?>%</small></strong></span>
				 <span  class="input-group-addon service-tax-amount-due" style="background: #A7E862;">0.00</span>
				<?php } ?>
				
				 <?php if( isset( $data["surcharge"]["SERVICE CHARGE"] ) && $data["surcharge"]["SERVICE CHARGE"] ){  ?>
				 <span class="input-group-addon"><strong><small>SERVICE CHARGE <?php $service_charge = $data["surcharge"]["SERVICE CHARGE"]; echo $data["surcharge"]["SERVICE CHARGE"]; ?>%</small></strong></span>
				 <span  class="input-group-addon service-charge-amount-due" style="background: #A7E862;">0.00</span>
				 <?php } ?>
			  </div>
			<?php } ?>
			
			  <hr />
			  </div>
			  
				<div class="input-group">
				 <span class="input-group-addon" style=" font-size: 18px; line-height: 1.5;">Amount Due</span>
				 <span  class="input-group-addon amount-due" style="background: #A7E862; font-size: 18px; line-height: 1.5;">0.00</span>
				</div>
				<hr />
				<div class="btn-group btn-group-justified">
					
					<a class="btn btn-lg default" onclick="nwCart.emptyCart(); return false;" href="#">Cancel</a>
					
					<a class="btn btn-lg green btn-block" id="cart-checkout" href="#">Proceed to Checkout</a>
					
				</div>
				
				
			</div>
		</div>
	</div>

</div>

<script type="text/javascript" class="auto-remove">
	var server = <?php echo $server; ?>;
	
	var vat = <?php echo $vat; ?>;
	var service_charge = <?php echo $service_charge; ?>;
	var service_tax = <?php echo $service_tax; ?>;
	
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>