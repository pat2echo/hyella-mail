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
                           <li class="active"><a href="#recent-expenses" data-toggle="tab">Recent Repair Jobs</a></li>
                           <li><a href="#in-repair" data-toggle="tab">In-Repair</a></li>
                           <li><a href="#repaired" data-toggle="tab">Repaired</a></li>
                        </ul>
                        <div class="tab-content" style="background:transparent !important;">
                           <div class="tab-pane active" id="recent-expenses">
								<?php 
									if( isset( $data['repairs'] ) && is_array( $data['repairs'] ) ){
										__expenses( $data['repairs'], "" );
									}
								?>
						   </div>
                           <div class="tab-pane" id="in-repair">
								<?php 
									if( isset( $data['in_repair'] ) && is_array( $data['in_repair'] ) ){
										__expenses( $data['in_repair'], "" );
									}
								?>
						   </div>
                           <div class="tab-pane" id="repaired">
								<?php 
									if( isset( $data['repaired'] ) && is_array( $data['repaired'] ) ){
										__expenses( $data['repaired'], "" );
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
				<div class="caption"><i class="icon-globe"></i><small>Repair Job Manager</small></div>
			</div>
			<div class="portlet-body shopping-cart-table allow-scroll-1" style="background:transparent !important;">
				
				<button class="btn dark btn-sm" href="#" onclick="nwRepairs.showNewItem();" >New Repair Job</button>
				<button class="btn btn-sm default custom-action-button" function-id="1" function-class="repairs" function-name="new_guest_form" title="Create New Customer" skip-title="1">New Customer</button>
					
				<div id="new-repair-job-container">
					 <form class="activate-ajax" method="post" id="repairs" action="?action=repairs&todo=save_app_changes">
					 <input type="hidden" name="id" class="form-control" value="" />
					 <input type="hidden" name="date" value="<?php echo date("Y-m-d") ?>" class="form-control" value="" />
					 <br />
					
					<div class="row">
						<div class="col-md-8">
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Description</span>
							 <input type="text" required="required" class="form-control" placeholder="Description of Repair" name="description" />
							</div>
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Customer</span>
							 
							 <select class="form-control" name="customer">
							 <?php
									$vendors = get_customers();
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
							 <span class="input-group-addon" style="color:#777;">Amount Due</span>
							 <input type="number" step="any" min="0" class="form-control" name="amount_due" />
							</div>
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Amount Paid</span>
							 <input type="number" step="any" min="0" class="form-control" name="amount_paid" />
							</div>
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Comment</span>
							 <input type="text" class="form-control" placeholder="Optional Comment" name="comment" />
							</div>
							
						</div>
						<div class="col-md-4">
							<div >
								<a class="btn default btn-sm btn-block" href="#">Image</a>
								<input alt="file" type="hidden" class="image-replace" />
								<div class="cell-element upload-box" id="upload-box-1" >
									<input type="file" class="form-control" name="image" acceptable-files-format="png:::jpg:::jpeg" id="image" />
									<span class="input-status"></span>
								</div>
								<div id="capture-image-button" >
									<img id="image-img" class="form-gen-element-image-upload-preview" style="display:none; width:100%;" />
									<button class="btn dark btn-block custom-action-button" function-name="load_image_capture_repairs" function-class="items" function-id="image-capture" skip-title="1" onclick="nwRepairs.openImageCapture();"><i class="icon-edit"></i> Capture Image</button>
									
									<button class="btn dark btn-block custom-single-selected-record-button" action="?action=items&todo=save_captured_image" id="save-captured-image" mod="repairs" style="display:none;">Save Image</button>
								</div>
								<div id="capture-container">
									
								</div>
							</div>
						</div>
					</div>
					<hr />
					<div class="row">
						<div class="col-md-12">
							<input type="submit" class="btn btn-lg green btn-block" value="Save Changes" /><br />
						</div>
						
					</div>
					
				  </form>
				</div>
				
				<div id="view-repair-job-container" style="display:none;">
					
				</div>
			</div>
		</div>

	</div>
	
</div>
<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>