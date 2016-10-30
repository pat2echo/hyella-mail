<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	
	$package = "";
	if( isset( $data["package"] ) && $data["package"] ){
		$package = $data["package"];
	}
	
	$store = "";
	if( isset( $data['store'] ) && $data['store'] ){
		$store = $data['store'];
	}
	
	$quantity = 0;
	if( isset( $data['quantity'] ) && $data['quantity'] ){
		$quantity = doubleval( $data['quantity'] );
	}

	$server = 0;
	if( defined( "HYELLA_SERVER_FILTER" ) && HYELLA_SERVER_FILTER ){
		$server = 1;
	}
?>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	
	<div class="col-md-5"> 
		<!--grey-->
		<div class="portlet  box">
			<div class="portlet-body" style="background:transparent;">
				
				<div id="cart-category">
					<div class="row">
						<div class="col-md-5">							
							<form id="search-form" <?php if( $server ){ ?> class="activate-ajax" method="post" action="?action=inventory&todo=stock_item_search" <?php } ?> >
							<div class="input-group">
								<input type="search" class="form-control input-lg1" name="search" placeholder="Search / Barcode">
								<input type="hidden" name="category_filter" value="" />
								<span class="input-group-btn">
									<button class="btn btn-lg1 dark" style="border: 1px solid #555;" <?php if( $server ){ ?> onclick="nwCurrentStore.showAllItems(); nwInventory.submitSearchForm();" <?php }else{ ?> onclick="nwCurrentStore.showAllItems();" <?php } ?> type="reset"><i class="icon-remove"></i> </button>
								 </span>
							</div>
							</form>
						</div>
						<div class="col-md-7">
							<div class="btn-toolbar margin-bottom-10 pull-right">
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
									<ul class="dropdown-menu pull-right" role="menu">
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
				<div id="stocked-items" class="row" style="max-height:450px; overflow-y:auto; overflow-x:hidden; ">
					<?php
						if( isset( $data['stocked_items'] ) && is_array( $data['stocked_items'] ) ){
							$cat = get_items_categories();
							
							if( $package == "jewelry" ){
								$color = get_color_of_gold();
							}
							
							foreach( $data['stocked_items'] as $sval ){
								include dirname( dirname( dirname( __FILE__ ) ) ) . "/globals/inventory-list.php";
							}
						}
					?>
				</div>
				
			</div>
		</div>
		
	</div>
	
	<div class="col-md-7" id="stock-view"> 
			
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="tabbable-custom nav-justified">
				<ul class="nav nav-tabs nav-justified">
				   <li class="active"><a href="#recent-expenses" onclick="nwInventory.specifyMaterials();" data-toggle="tab">Specify Items to Transfer</a></li>
				   <li><a href="#recent-goods" onclick="nwInventory.specifyGoods();" data-toggle="tab">Specify Items to Utilize</a></li>
				</ul>
				<div class="tab-content" style="background:transparent !important;">
				   <div class="allow-scroll-1 tab-pane active" id="recent-expenses">
						
					<div class="shopping-cart-table">
						
						<div class="table-responsive">
							<table class="table table-striped table-hover bordered">
							<thead>
							   <tr>
								  <th>Item</th>
								  <th class="r">Quantity</th>
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
						  <span class="1"><button class="btn btn-sm1 dark" onclick="nwInventory.deleteCartItem();"><i class="icon-trash"></i> delete</button> <button onclick="nwInventory.saveCartItemEdit();" class="btn btn-sm1 dark"><i class="icon-save"></i> save</button> </span>
						  <span class="3"><form onsubmit="nwInventory.saveCartItemEdit(); return false;"><input type="number" class="form-control quantity" style="width:90px; float:right" /></form></span>
					   </div>
					   
						<div class="discount-container">
							<div class="row">
								<div class="col-md-6">
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Destination</span>
									<select class="form-control" name="factory">
										<?php
											if( isset( $data['factory'] ) && is_array( $data['factory'] ) ){
												foreach( $data['factory'] as $key => $val ){
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
							</div>
							<br />
							<div class="row">
								<div class="col-md-12">
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Comment</span>
									 <input class="form-control" name="comment" type="text" placeholder="Optional Comment" />
									</div>
								</div>
								
							</div>
							<hr />
						</div>
							
						<div class="row">
							<div class="col-md-6">
								<a class="btn btn-lg default btn-block" onclick="nwInventory.emptyCart(); return false;" href="#">Cancel</a>
							</div>
							<div class="col-md-6">
								<button class="btn btn-lg red btn-block custom-single-selected-record-button" action="?module=&action=cart&todo=save_material_transfer_manifest" id="cart-finish">Confirm Transfer</button>
							</div>
						</div>
						
					</div>
					
				   </div>
				   
				   <div class="allow-scroll-1 tab-pane" id="recent-goods">
						
					<div class="shopping-cart-table">
						
						<div class="table-responsive">
							<table class="table table-striped table-hover bordered">
							<thead>
							   <tr>
								  <th>Item</th>
								  <th class="r">Quantity</th>
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
						  <span class="1"><button class="btn btn-sm1 dark" onclick="nwInventory.deleteCartItem();"><i class="icon-trash"></i> delete</button> <button onclick="nwInventory.saveCartItemEdit();" class="btn btn-sm1 dark"><i class="icon-save"></i> save</button> </span>
						  <span class="3"><form onsubmit="nwInventory.saveCartItemEdit(); return false;"><input type="number" class="form-control quantity" style="width:90px; float:right" /></form></span>
					   </div>
					   
						<div class="discount-container">
							<div class="row">
								<div class="col-md-6">
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Point of Use</span>
									<select class="form-control" name="factory">
										<?php
											if( isset( $data['factory'] ) && is_array( $data['factory'] ) ){
												if( $store && isset( $data['factory'][ $store ] ) ){
													?>
													<option value="<?php echo $store; ?>">
														<?php echo $data['factory'][ $store ]; ?>
													</option>
													<?php
												}else{
													foreach( $data['factory'] as $key => $val ){
														?>
														<option value="<?php echo $key; ?>">
															<?php echo $val; ?>
														</option>
														<?php
													}
												}
											}
										?>
									 </select>

									</div>
								</div>
								<div class="col-md-6">
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
							</div>
							<br />
							<div class="row">
								<div class="col-md-6">
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Comment</span>
									 <input class="form-control" name="comment" type="text" placeholder="Optional Comment" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Reason</span>
									 <select class="form-control" name="reason">
										<option value="utilization">
											Utilization Report
										</option>
										<option value="damage">
											Damage Report
										</option>
									 </select>
									</div>
								</div>
							</div>
							<hr />
						</div>
							
						<div class="row">
							<div class="col-md-6">
								<a class="btn btn-lg default btn-block" onclick="nwInventory.emptyCart(); return false;" href="#">Cancel</a>
							</div>
							<div class="col-md-6">
								<button class="btn btn-lg red btn-block custom-single-selected-record-button" action="?module=&action=cart&todo=save_material_utilization_manifest" id="cart-finish-1">Confirm Utilization</button>
							</div>
						</div>
						
					</div>
					
				   </div>
				   
				</div>
			 </div>
			
		</div>
	
	</div>
</div>

<script type="text/javascript" class="auto-remove">
	var server = <?php echo $server; ?>;
	var quantity = <?php echo $quantity; ?>;
	
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>