<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	<div class="col-md-6"> 
		<div class="portlet grey box">
			<div class="portlet-title">
				<div class="caption"><i class="icon-search"></i><small><small>Search Sales Invoice</small></small></div>
			</div>
			<div class="portlet-body" >
				<form class="activate-ajax" method="post" id="search-sales-invoice" action="?action=sales&todo=display_picking_slips">
					<div class="row">
						<div class="col-md-12">
							<div class="input-group">
							 <input class="form-control" placeholder="Invoice Number E.g 136" name="receipt_num" type="number" min="1" step="1" />
							 <span class="input-group-addon" style="color:#777;">OR</span>
							 <input class="form-control" placeholder="Ref. Number E.g 1402392031" name="id" />
							 <span class="input-group-btn">
								<button class="btn blue " type="submit" style="">GO</button>
							 </span>
							</div>
							<hr />
						</div>
						<div class="col-md-12">
							<div id="invoice-receipt-container" style="padding-bottom:50px; max-height:332px; overflow-y:auto;">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		
	</div>
	
	<div class="col-md-6 mobile-main-view"> 
		<div id="picking-slip-container" style="padding-bottom:50px; min-height:480px; max-height:500px; overflow-y:auto;">
		
		<div class="portlet box purple">
			<div class="portlet-title">
				<div class="caption"><small><small>Picking Slips</small></small></div>
			</div>
			<div class="portlet-body"   >
				<div style="text-align:center;">
					<br /><br /><br /><h1>Picking Slips</h1><br /><br />
					<p>Search Sales Invoice & Create / View Picking Slips</p><br /><br />
				</div>
			</div>
		</div>
		</div>
	</div>
</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>	
</script>