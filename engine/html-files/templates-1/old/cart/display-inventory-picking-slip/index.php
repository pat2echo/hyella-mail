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
	
	$store_name = "";
	if( isset( $data['factory'] ) && is_array( $data['factory'] ) ){
		if( count( $data['factory'] ) > 1 && isset( $data['factory'][ $store ] ) ){
			$store_name = $data['factory'][ $store ];
		}
	}
	
	$quantity = 0;
	if( isset( $data['quantity'] ) && $data['quantity'] ){
		$quantity = doubleval( $data['quantity'] );
	}

	$server = 1;
	if( defined( "HYELLA_SERVER_FILTER" ) && HYELLA_SERVER_FILTER ){
		$server = 1;
	}
	
	$reference_id = "cart";
	$reference_table = "cart";
?>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	
	<div class="col-md-4 col-md-offset-1"> 
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
									<button type="button" class="btn dark btn-lg1 dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"><i class="icon-angle-down"></i>&nbsp;</button>
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
	
	<div class="col-md-6" >
		<div id="picking-slip-container" style="padding-bottom:50px; min-height:480px; max-height:500px; overflow-y:auto; overflow-x:hidden;">
		<?php
			$sales_id = "";
			$sales_table = "sales";
			$customer = "";
			$html = "";
				
			$html = "";
			if( isset( $data[ "picking_slips" ] ) && is_array( $data[ "picking_slips" ] ) && ! empty( $data[ "picking_slips" ] ) ){
				$d = $data[ "picking_slips" ];
				$serial = 0;
				
				$emp = array();
				if( isset( $data['staff_responsible'] ) && is_array( $data['staff_responsible'] ) ){
					$emp = $data['staff_responsible'];
				}
				
				$ids = array();
				$item_picked = array();
				
				foreach( $d as $sval ){
					++$serial;
					
						$desc = '';
						$issuer = '';
						if( isset( $emp[ $sval["staff_responsible"] ] ) )
							$desc = $emp[ $sval["staff_responsible"] ] . '<br />';
						
						if( isset( $emp[ $sval["created_by"] ] ) )
							$issuer = $emp[ $sval["created_by"] ];
						
						$desc .= '[ '.$sval["comment"].' ]';
						
						$html .= '<tr><td>'.$serial.'</td>
							<td>'.date("d-M-Y", doubleval( $sval["date"] ) ).'</td>
							<td>'.$desc.'</td>
							<td>'.$issuer.'</td>
							<td style="text-align:right;">'.number_format( $sval["quantity_instock"] , 2 ).'</td>
							<td style="text-align:right;">'.number_format( $sval["quantity"] , 2 ).'</td>
							<td style="text-align:right;">'.number_format( $sval["quantity_instock"] - $sval["quantity"] , 2 ).'</td>
							<td style="text-align:right;"><a href="#" class="btn blue btn-xs custom-single-selected-record-button" action="?module=&action=production&todo=view_invoice" override-selected-record="'.$sval["id"].'" title="View Manifest">View Details</a></td></tr>';
							
				}
			}
			
			$show_image = 0;
			if( isset( $data["show_item_image"] ) && $data["show_item_image"] )
				$show_image = $data["show_item_image"];
		?>
		
		<div class="portlet box purple" id="new-picking-slip">
			<div class="portlet-title">
				<div class="caption"><small><small>New Picking Slip</small></small></div>
			</div>
			<div class="portlet-body">
				<h4><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php echo $store_name; ?> - Picking Slips</h4>
				<div class="row">
				   <div class="col-xs-6">
					  <label><small>Reason</small></label>
						 <select class="form-control" name="reason">
							<option value="utilization">
								Utilization Report
							</option>
							<option value="damage">
								Damage Report
							</option>
						 </select>
				   </div>
				   <div class="col-xs-6 invoice-payment">
				   <div class="well" style="padding:10px;">
					<p><strong>Date:</strong> <?php echo date("d-M-Y"); ?></p>
					  <ul class="list-unstyled">
						 <li><strong>Picking Slip No:</strong> unsaved</li>
						 <li><strong>Raised By:</strong> <?php if( isset( $user_info["user_full_name"] ) )echo $user_info["user_full_name"]; ?></li>
					  </ul>
					</div>
				   </div>
			  </div>
			  <div class="row">
				  <div class="col-xs-12">
					  <div class="shopping-cart-table" id="picked-items">
						<table class="table table-striped table-hover bordered">
						 <thead>
							<tr>
							   <th style="width:50px;">#</th>
							   <th>Item</th>
							   <th style="text-align:right;">Opening Stock</th>
							   <th style="text-align:right;">Qty Picked</th>
							   <th style="text-align:right;">Closing Stock</th>
							</tr>
						 </thead>
						 <tbody>
						 </tbody>
						 <tfoot>
						 </tfoot>
						 </table>
					  </div>
					  <div id="item-edit-template">
						  <span class="1"><button class="btn btn-sm1 dark" onclick="nwInventory.deleteCartItem();"><i class="icon-trash"></i> delete</button> <button onclick="nwInventory.saveCartItemEdit();" class="btn btn-sm1 dark"><i class="icon-save"></i> save</button> </span>
						  <span class="3"><form onsubmit="nwInventory.saveCartItemEdit(); return false;"><input type="number" class="form-control quantity" style="width:90px; float:right" /></form></span>
					   </div>
				  </div>
			  </div>
			  <div class="row">
				<div class="col-xs-6">
					<label><small>Comment</small></label>
					<input type="text" class="form-control" name="comment" placeholder="Optional Comment" />
					<?php
						if( isset( $data['factory'] ) && is_array( $data['factory'] ) ){
							if( count( $data['factory'] ) > 1 ){
					?>
					<br />
					<label><small>Point of Use</label></small>
					<select class="form-control" name="factory">
						<?php
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
						?>
					 </select>
					<?php } ?>
					<?php } ?>
				</div>
				<div class="col-xs-6">
					<label><small>Staff Responsible</small></label>
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
				<div class="row">
					<div class="col-xs-12">
						<br />
						<a class="btn pull-right red custom-single-selected-record-button" action="?module=&action=cart&todo=save_general_picking_slip" id="cart-finish" href="#">Save Picking Slip</a>
					</div>
				</div>
			</div>
		</div>
		<?php if( $html ){ ?>
		<br />
		<div class="portlet box purple" >
			<div class="portlet-title">
				<div class="caption"><small><small>Picking History (10 Most Recent)</small></small></div>
			</div>
			<div class="portlet-body">
			  <div class="row">
				  <div class="col-xs-12">
					  <div class="shopping-cart-table">
						<table class="table table-striped table-hover bordered">
						 <thead>
							<tr>
							   <th>#</th>
							   <th>Date</th>
							   <th>Supervisor [comment]</th>
							   <th>Issued By</th>
							   <th style="text-align:right;">Opening Stock</th>
							   <th style="text-align:right;">Quantity Picked</th>
							   <th style="text-align:right;">Closing Stock</th>
							   <th style="text-align:right;"></th>
							</tr>
						 </thead>
						 <tbody>
						 <?php
							echo $html;		
						 ?>
						 </tbody>
						 </table>
					  </div>
				  </div>
			  </div>
			</div>
		</div>
		<?php } ?>
	</div>
	</div>
</div>

<script type="text/javascript" class="auto-remove">
	var server = <?php echo $server; ?>;
	var quantity = <?php echo $quantity; ?>;
	
	var global_reference_id = "<?php echo $reference_id; ?>";
	var global_reference_table = "<?php echo $reference_table; ?>";
	
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>