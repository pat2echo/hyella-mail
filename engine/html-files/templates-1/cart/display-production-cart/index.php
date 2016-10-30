<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
?>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	
	<div class="col-md-6 col-md-offset-3" id="production-summary-container">
		<div class="portlet  box">
			<div class="portlet-body allow-scroll" style="background:transparent;">
				
				<div >
					<h3>Production Summary</h3>
					<hr />
					<div class="input-group">
					 <span class="input-group-addon" style=" font-size: 18px; line-height: 1.5;">Cost of Production</span>
					 <span  class="input-group-addon cost-of-production" style="background: #A7E862; font-size: 18px; line-height: 1.5;">0.00</span>
					</div>
					<br />
					<div class="input-group">
					 <span class="input-group-addon" style=" font-size: 18px; line-height: 1.5;">Estimated Revenue</span>
					 <span  class="input-group-addon estimated-revenue" style="background: #A7E862; font-size: 18px; line-height: 1.5;">0.00</span>
					</div>
					<hr />
					<div class="input-group">
					 <span class="input-group-addon" style=" font-size: 18px; line-height: 1.5;">Gross Profit</span>
					 <span  class="input-group-addon profit-margin" style="background: #A7E862; font-size: 18px; line-height: 1.5;">0.00</span>
					</div>
					<hr />
					
					<div class="checkbox-list">
						<label>
						<input type="checkbox" id="production-status"> Stock Produced Goods <small>{upon saving goods would be added to inventory & manifest would be uneditable}</small>
						</label>
					 </div>
					 
					<hr />
					
					<div class="row" style="margin-left:0; margin-right:0; ">
						<div class="col-md-6" style="padding-left:0;">
							<button class="btn btn-lg default btn-block" onclick="nwProduction.emptyCart();">Cancel</button>
							<!--OR DELETE BUTTON IF ALREADY SAVED-->
						</div>
						<div class="col-md-6" style="padding-right:0;">
							<button class="btn btn-lg red btn-block custom-single-selected-record-button" action="?module=&action=cart&todo=save_production_manifest" id="cart-finish">Save Manifest</button>
						</div>
					</div>
					
				</div>
				
			</div>
		</div>
	</div>
	
	<div class="col-md-6"> 
		<!--grey-->
		<div class="portlet box">
			<div class="portlet-body allow-scroll" style="background:transparent;">
				
				<div id="cart-category">
					<form id="search-form">
					<div class="input-group">
						<input type="search" class="form-control input-lg" style="" placeholder="Search / Scan Barcode" />
						<span class="input-group-btn">
							<button class="btn btn-lg green" style="border: 1px solid #35AA47;" type="submit">Go</button>
						 </span>
					</div>
					</form>
					<hr />
					<div id="cart-category-materials" class="active-category-box">
					<?php
						if( isset( $data['categories'] ) && is_array( $data['categories'] ) ){
							foreach( $data['categories'] as $key => $val ){
								?>
								<a href="#" class="category icon-btn" id="<?php echo $key; ?>">
									<i class="icon-th"></i>
									<div><?php echo $val; ?></div>
									<span class="badge badge-success" ></span>
								</a>
								<?php
							}
						}
					?>
					</div>
					<div id="cart-category-products">
					<?php
						if( isset( $data['categories_products'] ) && is_array( $data['categories_products'] ) ){
							foreach( $data['categories_products'] as $key => $val ){
								?>
								<a href="#" class="category icon-btn" id="<?php echo $key; ?>">
									<i class="icon-th"></i>
									<div><?php echo $val; ?></div>
									<span class="badge badge-success" ></span>
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
					<?php
						if( isset( $data['stocked_items'] ) && is_array( $data['stocked_items'] ) ){
							foreach( $data['stocked_items'] as $sval ){
								$q = $sval["quantity"];
								if( isset( $sval['quantity_sold'] ) )$q -= $sval['quantity_sold'];
								if( isset( $sval['quantity_used'] ) )$q -= $sval['quantity_used'];
								
								if( $sval["type"] == "raw_materials" && ! $q )continue;
								?>
								<div class="col-md-4 col-sm-3 cart-item cart-item-select <?php echo $sval["category"]." ".$sval["barcode"] . " " . $sval["type"]; ?>" id="<?php echo $sval["item"]; ?>" data-type="<?php echo $sval["type"]; ?>" data-max="<?php echo $q; ?>" data-price="<?php if( $sval["type"] == "raw_materials" )echo $sval["cost_price"]; else echo $sval["selling_price"]; ?>" data-cost="<?php echo $sval["cost_price"]; ?>" title="<?php echo $sval["description"]; ?>">
									<div class="item-container">
										<span class="b-c"><span class="badge badge-success" ></span></span>
										<div class="item-image">
											<img src="<?php echo $site_url . $sval["image"]; ?>" >
										</div>
									  <p class="item-title">
										<?php echo $sval["description"]; ?>
									  </p>
									  <p class="item-price">
										<?php if( $sval["type"] == "raw_materials" )echo format_and_convert_numbers( $sval["cost_price"], 4 ); else echo format_and_convert_numbers( $sval["selling_price"], 4 ); ?>
									  </p>
									</div>
								</div>
								<?php
							}
						}
					?>
				</div>
				
			</div>
		</div>
		
	</div>
	
	<div class="col-md-6" id="production-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="tabbable-custom nav-justified">
				<ul class="nav nav-tabs nav-justified">
				   <li class="active"><a href="#recent-expenses" onclick="nwProduction.specifyMaterials();" data-toggle="tab">1. Specify Raw Materials</a></li>
				   <li><a href="#extra-cost" data-toggle="tab">2. Extra Cost</a></li>
				   <li><a href="#recent-goods" onclick="nwProduction.specifyGoods();" data-toggle="tab">3. Specify Products</a></li>
				</ul>
				<div class="tab-content" style="background:transparent !important;">
				   <div class="allow-scroll-1 tab-pane active" id="recent-expenses">
						
					<div class="shopping-cart-table">
						
						<div class="table-responsive">
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
						<hr />
						<div id="item-edit-template">
						  <span class="1"><button class="btn btn-sm1 dark" onclick="nwProduction.deleteCartItem();"><i class="icon-trash"></i> delete</button> <button onclick="nwProduction.saveCartItemEdit();" class="btn btn-sm1 dark"><i class="icon-save"></i> save</button> </span>
						  <span class="3"><form onsubmit="nwProduction.saveCartItemEdit(); return false;"><input type="number" class="form-control quantity" style="width:90px; float:right" /></form></span>
					   </div>
					   
						<div id="discount-container">
							<div class="row">
								<div class="col-md-6">
									<div class="input-group">
									 <span class="input-group-addon" style="color:#777;">Factory</span>
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
							
							<hr />
						</div>
						
						<div class="input-group">
						 <span class="input-group-addon" style=" font-size: 18px; line-height: 1.5;">Cost of Production</span>
						 <span  class="input-group-addon total-amount" style="background: #A7E862; font-size: 18px; line-height: 1.5;">0.00</span>
						</div>
						<hr />
						
						<div class="row">
							<div class="col-md-6">
								<a class="btn btn-lg default btn-block" onclick="nwProduction.emptyCart(); return false;" href="#">New Manifest</a>
							</div>
							<div class="col-md-6">
								<a class="btn btn-lg green btn-block" onclick="nwProduction.specifyExtraCostButtonClick(); return false;" href="#">Proceed to Next Step</a>
							</div>
						</div>
						
					</div>
					
				   </div>
				   <div class="allow-scroll-1 tab-pane" id="extra-cost">
					 
					 <div id="new-extra-cost">
						<form onsubmit="nwProduction.addExtraCost(); return false;">
							<input type="text" class="form-control" name="description" placeholder="Description of Expenditure" required="required" />
							<div class="input-group" >
							  <select class="form-control" name="category_of_expense" style="width:60%; display:inline-block;">
								<?php
									if( isset( $data['category_of_expense'] ) && is_array( $data['category_of_expense'] ) ){
										foreach( $data['category_of_expense'] as $key => $val ){
											?>
											<option value="<?php echo $key; ?>">
												<?php echo $val; ?>
											</option>
											<?php
										}
									}
								?>
							  </select>
							  <input type="number" step="any" min="0" class="form-control" name="amount_paid" placeholder="Amount" required="required" style="width:39.2%; display:inline-block;" />
							 <span class="input-group-btn">
							 <button class="btn dark" type="submit" >Add Cost</button>
							 </span>
						  </div>
						  </form>
					  </div>
					  <br />
					  
					<div class="shopping-cart-table">
						
						<div class="table-responsive">
							<table class="table table-striped table-hover bordered">
							<thead>
							   <tr>
								  <th>&nbsp;</th>
								  <th>Description</th>
								  <th>Category</th>
								  <th class="r">Amount</th>
							   </tr>
							</thead>
							<tbody id="extra-cost-table">
							   
							</tbody>
							<tfoot id="total-extra-cost">
							   
							</tfoot>
							</table>
						</div>
						
						<div class="input-group">
						 <span class="input-group-addon" style=" font-size: 18px; line-height: 1.5;">Cost of Production</span>
						 <span  class="input-group-addon cost-of-production" style="background: #A7E862; font-size: 18px; line-height: 1.5;">0.00</span>
						</div>
						<hr />
						
						<div class="row">
							<div class="col-md-6">
								<a class="btn btn-lg default btn-block" onclick="nwProduction.specifyMaterialsButtonClick(); return false;" href="#">Go Back</a>
							</div>
							<div class="col-md-6">
								<a class="btn btn-lg green btn-block" onclick="nwProduction.specifyGoodsButtonClick(); return false;" href="#">Proceed to Next Step</a>
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
								  <th class="r">Selling Price</th>
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
						<hr />
						<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Expiry Date</span>
							 <input type="date" class="form-control" id="expiry-date">
							</div>
							<hr />
							
						<div class="input-group">
						 <span class="input-group-addon" >Estimated Revenue</span>
						 <span  class="input-group-addon total-amount" style="background: #A7E862;">0.00</span>
						</div>
						<hr />
						
						<div class="input-group">
						 <span class="input-group-addon" style="font-size: 18px; line-height: 1.5;">Gross Profit</span>
						 <span  class="input-group-addon profit-margin" style="background: #A7E862; font-size: 18px; line-height: 1.5;">0.00</span>
						</div>
						<hr />
						
						<div class="row">
							<div class="col-md-6">
								<button class="btn btn-lg default btn-block" onclick="nwProduction.emptyCart();">Cancel</button>
							</div>
							<div class="col-md-6">
								<button class="btn btn-lg green btn-block" onclick="nwProduction.displayProductionSummary();">Finish</button>
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
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>