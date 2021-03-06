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
	
	<div class="col-md-6" id="expense-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="portlet-body1" style="max-height:500px; overflow-y:auto; background:transparent !important;">
                     <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                           <li class="active"><a href="#recent-expenses" data-toggle="tab">All Vendors</a></li>
                        </ul>
                        <div class="tab-content" style="background:transparent !important;">
                           <div class="tab-pane active" id="recent-expenses">
								<?php 
									if( isset( $data['vendors'] ) && is_array( $data['vendors'] ) ){
										__expenses( $data['vendors'], "" );
									}
								?>
						   </div>
                        </div>
                     </div>
                     
                  </div>
				
		</div>
	</div>
	
	<div class="col-md-6"> 
		
		<div style="background:transparent !important; border-color:#fff !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small>Vendors</small></div>
			</div>
			<div class="portlet-body shopping-cart-table" style="background:transparent !important;">
				  <form class="activate-ajax" method="post" id="vendors" action="?action=vendors&todo=save_app_changes">
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
						<div class="col-md-12">
							<input type="submit" class="btn btn-lg green btn-block" value="Update" /><br />
						</div>
						<div class="col-md-6">
							<input type="reset" class="btn btn-lg default btn-block" onclick="nwVendors.emptyNewItem()" value="New Vendor" />
						</div>
						<div class="col-md-6">
							<input type="reset" class="btn btn-lg dark btn-block custom-single-selected-record-button" action="?module=&action=vendors&todo=delete_app_record" override-selected-record="" value="Delete" />
						</div>
					</div>
					
				  </form>
				
			</div>
		</div>

	</div>
	
</div>
<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>