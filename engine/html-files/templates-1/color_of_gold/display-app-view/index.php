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
                           <li class="active"><a href="#recent-expenses" data-toggle="tab">All Colors of Gold</a></li>
                        </ul>
                        <div class="tab-content" style="background:transparent !important;">
                           <div class="tab-pane active" id="recent-expenses">
								<?php 
									if( isset( $data['color_of_gold'] ) && is_array( $data['color_of_gold'] ) ){
										__expenses( $data['color_of_gold'], "" );
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
				<div class="caption"><i class="icon-globe"></i><small>Color of Gold</small></div>
			</div>
			<div class="portlet-body shopping-cart-table" style="background:transparent !important;">
				  <form class="activate-ajax" method="post" id="color_of_gold" action="?action=color_of_gold&todo=save_app_changes">
					 <input type="hidden" name="id" class="form-control" value="" />
					 <br />
					<div class="input-group">
					 <span class="input-group-addon" style="color:#777;">Color</span>
					 <input type="text" required="required" class="form-control" name="name" />
					</div>
					<!--
					<br />
					<div class="input-group">
					 <span class="input-group-addon" style="color:#777;">Type of color_of_gold</span>
					  <select class="form-control" name="type">
						<?php
							if( isset( $data['type'] ) && is_array( $data['type'] ) ){
								foreach( $data['type'] as $key => $val ){
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
					-->
					<hr />
					<div class="row">
						<div class="col-md-12">
							<input type="submit" class="btn btn-lg green btn-block" value="Update" /><br />
						</div>
						<div class="col-md-6">
							<input type="reset" class="btn btn-lg default btn-block" onclick="nwColor_of_gold.emptyNewItem()" value="New Color Of Gold" />
						</div>
						<div class="col-md-6">
							<input type="reset" class="btn btn-lg dark btn-block custom-single-selected-record-button" action="?module=&action=color_of_gold&todo=delete_app_record" override-selected-record="" value="Delete" />
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