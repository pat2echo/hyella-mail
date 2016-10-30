<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	<div class="col-md-6"> 
		<div class="portlet grey box">
			<div class="portlet-title">
				<div class="caption"><i class="icon-search"></i><small><small>Search Draft Purchase Order</small></small></div>
			</div>
			<div class="portlet-body" >
				<form class="activate-ajax" method="post" id="search-expenditure-invoice" action="?action=expenditure&todo=expenditure_draft_search_all">
					<div class="row">
						<div class="col-md-12">
							<div class="input-group">
							  <select class="form-control select2" name="vendor" onchange="nwRecordPayment.search();">
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
							 <!--
							 <span class="input-group-addon" style="color:#777;">OR</span>
							 <input class="form-control" placeholder="P.O Number E.g 136" name="receipt_num" type="number" min="1" step="1" style="min-width:200px; width:50%;" />
							 -->
							 <span class="input-group-btn">
								<button class="btn blue" type="submit" style="">GO</button>
							 </span>
							</div>
							<hr />
						</div>
						<div class="col-md-12">
							<div id="sales-record-search-result" style="padding-bottom:50px; max-height:332px; overflow-y:auto;">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		
	</div>
	
	<div class="col-md-6 mobile-main-view"> 
		<div style="padding-bottom:50px; min-height:480px; max-height:500px; overflow-y:auto;">
		
		<div class="portlet box purple">
			<div class="portlet-title">
				<div class="caption"><small><small>Receive Goods</small></small></div>
			</div>
			<div class="portlet-body" id="receive-goods-container">
				<div style="text-align:center;">
					<br /><br /><br /><h1>Receive Goods</h1><br /><br />
					<p>Search Draft Purchase Orders & Confirm Receipt of Goods</p><br /><br />
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
<a href="#" style="display:none;" class="btn btn-xs red custom-single-selected-record-button" override-selected-record="" action="?module=&action=expenditure&todo=update_stock_status_received_goods" id="update-button">Pending</a>
<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>	
</script>