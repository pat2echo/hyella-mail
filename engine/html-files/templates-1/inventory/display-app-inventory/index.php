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
	
	$centralize = 0;
	if( isset( $data['centralize'] ) && $data['centralize'] ){
		$centralize = $data['centralize'];
	}
	
	$quantity = 0;
	if( isset( $data['quantity'] ) && $data['quantity'] ){
		$quantity = doubleval( $data['quantity'] );
	}
	
	$hide_capture_cost_price = 0;
	if( isset( $data['hide_capture_cost_price'] ) && $data['hide_capture_cost_price'] ){
		$hide_capture_cost_price = $data['hide_capture_cost_price'];
	}
	
	$hide_capture_selling_price = 0;
	if( isset( $data['hide_capture_selling_price'] ) && $data['hide_capture_selling_price'] ){
		$hide_capture_selling_price = $data['hide_capture_selling_price'];
	}
	
	$cost_per_gram = 0;
	if( isset( $data['cost_per_gram'] ) && $data['cost_per_gram'] ){
		$cost_per_gram = doubleval( $data['cost_per_gram'] );
	}
	
	$percentage_markup = 0;
	if( isset( $data['percentage_markup'] ) && $data['percentage_markup'] ){
		$percentage_markup = doubleval( $data['percentage_markup'] );
	}
	
	$show_expiry_date = 0;
	if( isset( $data['general_settings']['SHOW EXPIRY DATE'] ) ){
		$show_expiry_date = doubleval( $data['general_settings']['SHOW EXPIRY DATE'] );
	}
	
	$capture_restock_as_expenditure = 0;
	if( isset( $data['general_settings']['RECORD RESTOCKED ITEMS AS EXPENDITURE'] ) ){
		$capture_restock_as_expenditure = doubleval( $data['general_settings']['RECORD RESTOCKED ITEMS AS EXPENDITURE'] );
	}
	
	$allow_override_date = 0;
	if( isset( $data['general_settings']["ALLOW OVERRIDE DATE"] ) ){
		$allow_override_date = doubleval( $data['general_settings']["ALLOW OVERRIDE DATE"] );
	}
	
	$show_vendor_during_capture = 0;
	if( isset( $data['general_settings']["SHOW VENDOR DURING CAPTURE"] ) ){
		$show_vendor_during_capture = doubleval( $data['general_settings']["SHOW VENDOR DURING CAPTURE"] );
	}
	
	$disable_direct_restocking = 0;
	if( isset( $data['general_settings']['DISABLE DIRECT RESTOCKING'] ) ){
		$disable_direct_restocking = doubleval( $data['general_settings']['DISABLE DIRECT RESTOCKING'] );
		if( $disable_direct_restocking ){
			$capture_restock_as_expenditure = 0;
		}
	}
	
	$allow_multi_currency = get_multi_currency_settings();
	
	$server = 0;
	if( defined( "HYELLA_SERVER_FILTER" ) && HYELLA_SERVER_FILTER ){
		$server = 1;
	}	
?>

<?php if( $centralize ){ ?> 
	<div class="row" >
	<div class="col-md-10 col-md-offset-1" >
<?php } ?>
<div class="row" >
	
	<div class="col-md-5"> 
		<!--grey-->
		<div class="portlet  box">
			<div class="portlet-body" style="background:transparent;">
				
				<div id="cart-category">
					<div class="row">
						<div class="col-md-6">							
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
						<div class="col-md-6">
							<div class="btn-toolbar margin-bottom-10 pull-right1">
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
			<div class="portlet-body1" style="max-height:500px; overflow-y:auto; background:transparent !important;">
                     <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                           <li class="active"><a href="#new-stock" onclick="nwInventory.emptyNewItem();" data-toggle="tab">New Item</a></li>
                           <li><a href="#re-stock" data-toggle="tab"><?php if( $disable_direct_restocking ){ ?>Set Pricing<?php }else{ ?>Re-stock<?php } ?></a></li>
                           <!--<li><a href="#report-damage" data-toggle="tab">Report Damage</a></li>-->
                           <li><a href="#supply-history" class="custom-single-selected-record-button" action="?module=&action=inventory&todo=get_recent_supplies" override-selected-record="" data-toggle="tab">History</a></li>
                        </ul>
                        <div class="tab-content" style="background:transparent !important;">
                           <div class="tab-pane active" id="new-stock">
								<form class="activate-ajax" method="post" id="items" action="?action=items&todo=new_item">
								<div class="row">
									<br />
									<div class="col-md-8">
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Item Desc.</span>
										 <input type="text" required="required" class="form-control" name="description" />
										</div>
										<br />
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Category</span>
										 <select required="required" class="form-control" name="category" alt="text" >
											<?php
												if( isset( $data['categories_grouped'] ) && is_array( $data['categories_grouped'] ) ){
													foreach( $data['categories_grouped'] as $key => $opts ){
														?>
														<optgroup label="<?php echo $key; ?>">
														<?php
														foreach( $opts as $k => $v ){
															?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
														}
														?>
														</optgroup>
														<?php
													}
												}
											?>
										 </select>
										 <span class="input-group-btn">
										 <button class="btn dark custom-action-button" type="button" function-name="new_record_popup_form" function-class="category" function-id="Add New Category" skip-title="1" title="Add New Category">New</button>
										 </span>
										</div>
										<br />
										<?php if( $package == "jewelry" ){ ?>
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Color of Gold</span>
										 <select class="form-control color_of_gold" name="color_of_gold" alt="text" ><!--name="color_of_gold[]" multiple="multiple"-->
											<?php
												if( isset( $data['color_of_gold'] ) && is_array( $data['color_of_gold'] ) ){
													foreach( $data['color_of_gold'] as $k => $v ){
														?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
													}
												}
											?>
										 </select>
										</div>
										<br />
										<?php } ?>
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Type</span>
										 <select required="required" class="form-control" name="type" alt="text">
											<?php
												if( isset( $data['categories_type'] ) && is_array( $data['categories_type'] ) ){
													foreach( $data['categories_type'] as $k => $v ){
														?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
													}
												}
											?>
										 </select>
										</div>
										<?php if( ! ( isset( $data['auto_generate_barcode'] ) && $data['auto_generate_barcode'] ) ){ ?>
										<br />
										<div class="input-group">
										 <span class="input-group-addon" style="color:#777;">Barcode</span>
										 <input type="text" class="form-control" name="barcode" />
										</div>
										<?php } ?>
										
										<?php if( $package == "jewelry" ){ ?>
										<br />
										<div class="row">
										<div class="col-md-6">
											<div class="input-group">
											 <span class="input-group-addon" style="color:#777;">Weight in Grams</span>
											 <input type="number" value="0" min="0" step="any" class="form-control" name="weight_in_grams" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="input-group">
											 <span class="input-group-addon" style="color:#777;">Length of Chain</span>
											 <input type="text" class="form-control" name="length_of_chain" />
											</div>
										</div>
										</div>
										<?php } ?>
										<?php __restock_form_field( $package , $percentage_markup, $capture_restock_as_expenditure, $show_expiry_date, $disable_direct_restocking , $allow_multi_currency, $allow_override_date, $show_vendor_during_capture, $hide_capture_cost_price, $hide_capture_selling_price ); ?>
										
										 <input type="hidden" name="store" value="" class="form-control" />
										 <input type="hidden" name="id" value="" class="form-control" />
										 <input type="hidden" name="processing" value="items" />
										 <input type="hidden" name="table" value="items" />
									</div>
									<div class="col-md-4">
									<div >
										<a class="btn default btn-sm btn-block" href="#">Image</a>
										<input alt="file" type="hidden" class="image-replace" />
										<div class="cell-element upload-box" id="upload-box-1" >
											<input type="file" class="form-control" name="image" acceptable-files-format="png:::jpg:::jpeg" id="image" />
											<span class="input-status"></span>
										</div>
										
										<?php if( isset( $data['capture_image'] ) && $data['capture_image'] ){ ?>
										<div id="capture-image-button" >
											<img id="image-img" class="form-gen-element-image-upload-preview" style="display:none; width:100%;" />
											<button class="btn dark btn-block custom-action-button" function-name="load_image_capture" function-class="items" function-id="image-capture" skip-title="1" onclick="nwInventory.openImageCapture();"><i class="icon-edit"></i> Capture Image</button>
											
											<button class="btn dark btn-block custom-single-selected-record-button" action="?action=items&todo=save_captured_image" id="save-captured-image" style="display:none;">Save Image</button>
										</div>
										<div id="capture-container">
											
										</div>
										<?php } ?>
									</div>
									</div>
								</div>
								<hr />
								<input type="submit" class="btn btn-lg green btn-block" value="Save Item" />
							  </form>
                           </div>
                           <div class="tab-pane" id="re-stock">
                             
								<div class="row">
									<div class="col-md-12">
										<a class="btn default btn-sm btn-block" href="#" id="inventory-title" style="text-overflow: ellipsis; overflow: hidden; font-weight:600;"></a>
									</div>
								</div>
								<div class="row">
									<div class="col-md-8">
										<div class="clearfix">
											<div class="btn-group btn-group-justified1 pull-right">
												<a href="#" class="btn btn-sm default" ><small>Barcode:</small> <strong id="inventory-barcode"></strong></a>
												<a href="#" class="btn btn-sm default" disabled="disabled"><small>Category:</small> <strong id="inventory-category"></strong></a>
												<?php if( $package == "jewelry" ){ ?>
												<a href="#" class="btn btn-sm default" ><small>Weight:</small> <strong id="inventory-weight_in_grams"></strong></a>
												<a href="#" class="btn btn-sm default" disabled="disabled"><small>Color:</small> <strong id="inventory-color"></strong></a>
												<?php } ?>
											</div>
										</div>
										
										 <form class="activate-ajax" method="post" id="inventory" action="?action=inventory&todo=restock">
										 <input type="hidden" name="item" class="form-control" value="" />
										 
										 <input type="hidden" name="print_barcode" class="form-control" value="0" />
										<?php __restock_form_field( $package , $percentage_markup, $capture_restock_as_expenditure, $show_expiry_date, $disable_direct_restocking , $allow_multi_currency, $allow_override_date, $show_vendor_during_capture, $hide_capture_cost_price, $hide_capture_selling_price ); ?>
										<hr />
										<input type="submit" class="btn btn-lg green btn-block" value="Update Stock" />
										</form>
									</div>
									
									<div class="col-md-4">
										
										<img src="" id="inventory-image" style="background:#fff; width:100%;">
										<button class="btn btn-lg1 dark btn-block" onclick="nwInventory.editSelectedItem(); return false;"><i class="icon-edit"></i> Edit Item</button>
										<button class="btn btn-lg1 dark btn-block" onclick="nwInventory.editSelectedItem(); nwInventory.clearID(); return false;"><i class="icon-copy"></i> Copy Item</button>
										<br />
										<?php if( isset( $data['print_barcode'] ) && $data['print_barcode'] ){ ?>
										<div id="barcode-container">
											<div class="checkbox-list">
												<label>
												<input type="checkbox" checked="checked" id="print-barcode-checkbox"> <small>Add Barcode [ <strong class="barcode-text"></strong> ] to Print Queue</small>
												</label>
											 </div>
											 <button action="?action=barcode&todo=display_barcode_print" id="print-barcode-button" skip-title="1" class="btn btn-sm dark btn-block custom-single-selected-record-button" title="Print Item Barcode" ><i class="icon-print"></i> Print Barcode</button>
											 <button function-name="display_barcode_print_queue" skip-title="1" function-class="barcode" function-id="Stock: Print Barcode" class="btn btn-sm dark btn-block custom-action-button" title="View Barcode Print Queue" ><i class="icon-print"></i> Print Barcode Queue</button>
										</div>
										<?php } ?>
									</div>
								</div>
							  
                           </div>
                           <div class="tab-pane" id="report-damage">
							In-progress
						   </div>
                           <div class="tab-pane" id="supply-history">
                             
                           </div>
                        </div>
                     </div>
                     
                  </div>
				
		</div>
	</div>
</div>

<?php if( $centralize ){ ?> 
	</div>
	</div>
<?php } ?>

<script type="text/javascript" class="auto-remove">
	var server = <?php echo $server; ?>;
	var cost_per_gram = <?php echo $cost_per_gram; ?>;
	var percentage_markup = <?php echo $percentage_markup; ?>;
	var quantity = <?php echo $quantity; ?>;
	
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
<?php 
	function __restock_form_field( $package = "", $percentage_markup = 0, $capture_restock_as_expenditure = 0, $show_expiry_date = 0, $disable_direct_restocking = 0, $allow_multi_currency = 0, $allow_override_date = 0, $show_vendor_during_capture = 0, $hide_capture_cost_price = 0, $hide_capture_selling_price = 0 ){
		?>	
		<?php if( $capture_restock_as_expenditure || $show_vendor_during_capture ){ ?>
		<div class="not-service">
			<br />
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Vendor</span>
			  <select class="form-control" name="source">
				<?php
					$vendors = get_vendors();
					if( ( ! empty( $vendors ) ) && is_array( $vendors ) ){
						foreach( $vendors as $key => $val ){
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
			 <button class="btn dark custom-action-button" type="button" function-name="new_source_popup_form" function-class="vendors" function-id="Add New Vendor" skip-title="1" title="Add New Vendor">New</button>
			 </span>
			</div>
		</div>
		<?php } ?>
		
		<?php 
			$stores = get_stores();
			if( is_array( $stores ) && count( $stores ) > 1 ){
				?>
				<div>
					<br />
					<div class="input-group">
					 <span class="input-group-addon" style="color:#777;">Location</span>
					  <select class="form-control" name="store">
						<?php
							if( ( ! empty( $stores ) ) && is_array( $stores ) ){
								foreach( $stores as $key => $val ){
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
				<?php
			}else{
				?>
				<input type="hidden" name="store" class="form-control" value="" />
				<?php
			}
		?>
		
		<?php if( $allow_override_date ){ ?>
		<br />
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Date</span>
		 <input type="date" required="required" class="form-control" name="date" value="<?php echo date("Y-m-d") ?>"/>
		</div>
		<?php } ?>
		
		<?php if( $package != "jewelry" && $show_expiry_date ){ ?>
		<div class="not-service">
			<br />
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Expiry Date</span>
			 <input type="date" class="form-control" name="expiry_date" />
			</div>
		</div>
		<?php } ?>
		
		<div class="not-service">
			<?php if( ! $disable_direct_restocking ){ ?>
			<br />
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Quantity</span>
			 <input type="number" required="required" min="0" step="any" name="quantity" class="form-control" value="0">
			</div>
			<?php } ?>
			
			<?php if( $package == "jewelry" ){ ?>
			<?php if( ! $disable_direct_restocking ){ ?>
				<?php if( ! $hide_capture_cost_price ){ ?>
			<br />
			<div class="row">
			<div class="col-md-6">
				<div class="input-group">
				 <span class="input-group-addon" style="color:#777;">Cost Price</span>
				 <input type="number" step="any" min="0" class="form-control" name="cost_price" value="0">
				</div>
			</div>
			<div class="col-md-6">
				<div class="input-group">
				 <span class="input-group-addon" style="color:#777;">Cost / Gram</span>
				 <input type="number" step="any" min="0" class="form-control" name="cost_per_gram" value="0">
				</div>
			</div>
			</div>
				<?php } ?>
			<br />
			<div class="row">
			<div class="col-md-6">
				<div class="input-group">
				 <span class="input-group-addon" style="color:#777; line-height: 1.5;">Default % Mark-up</span>
				 <span  class="input-group-addon default-mark-up" data-value="<?php echo $percentage_markup; ?>" style="background: #A7E862; line-height: 1.5;" ><?php echo number_format( $percentage_markup , 2 ); ?></span>
				</div>
			</div>
			<div class="col-md-6">
				<div class="input-group">
				 <span class="input-group-addon" style="color:#777;">% Mark-up</span>
				 <input type="number" step="any" class="form-control" name="percentage_markup" value="0">
				</div>
			</div>
			</div>
			<?php } ?>
			
			<?php if( ! $hide_capture_selling_price ){ ?>
			<div class="not-raw-material">
				<br />
				<div class="row">
				<div class="col-md-6">
					<div class="input-group">
					 <span class="input-group-addon" style="color:#777;">Selling Price</span>
					 <input type="number" required="required" min="0" step="any" class="form-control" name="selling_price" value="0">
					</div>
				</div>
				<div class="col-md-6">
					<div class="input-group">
					 <span class="input-group-addon" style="color:#777;">Selling / Gram</span>
					 <span  class="input-group-addon selling-per-gram" style="background: #A7E862; line-height: 1.5;" >0.00</span>
					</div>
				</div>
				</div>
			</div>
			<?php } ?>
			
			<?php }else{
				
				if( ! $disable_direct_restocking ){
				?>
					<?php if( ! $hide_capture_cost_price ){ ?>
				<br />
				<div class="input-group">
				 <span class="input-group-addon" style="color:#777;">Cost Price</span>
				 <input type="number" step="any" min="0" class="form-control" name="cost_price" value="0">
				</div>
					<?php } ?>
				<?php
				}
				
			} ?>
		</div>
		<?php if( $package != "jewelry" ){ ?>
			<?php if( ! $hide_capture_selling_price ){ ?>
		<div class="not-raw-material">
			<br />
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Selling Price</span>
			 <input type="number" required="required" min="0" step="any" class="form-control" name="selling_price" value="0">
			</div>
		</div>
			<?php } ?>
		<?php } ?>
		
		<?php if( $allow_multi_currency ){ ?>
		<br />
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Currency</span>
		 <select class="form-control" name="currency">
				<?php
					$vendors = get_currencies();
					if( ( ! empty( $vendors ) ) && is_array( $vendors ) ){
						foreach( $vendors as $key => $val ){
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
		<?php } ?>
			
		<?php
	}
?>