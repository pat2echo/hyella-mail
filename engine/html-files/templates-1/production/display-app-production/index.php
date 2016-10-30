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
	
	<div class="col-md-6" id="sales-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="portlet-body1" style="max-height:500px; overflow-y:auto; background:transparent !important;">
                     <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                           <li class="active"><a href="#recent-expenses" data-toggle="tab">Recent Production</a></li>
						   <li><a href="#this-month" data-toggle="tab">This Month</a></li>
                           <li><a href="#this-year" data-toggle="tab" >This Year</a></li>
                        </ul>
                        <div class="tab-content" style="background:transparent !important;">
                           <div class="tab-pane active" id="recent-expenses">
								<?php 
									if( isset( $data[ 'recent_expenses' ]["report_data"] ) && is_array( $data[ 'recent_expenses' ]["report_data"] ) ){
										__expenses( $data[ 'recent_expenses' ]["report_data"], "" );
									}
								?>
						   </div>
                           <div class="tab-pane" id="this-month">
								<?php 
									if( isset( $data[ 'this_month' ]["report_data"] ) && is_array( $data[ 'this_month' ]["report_data"] ) ){
										__expenses( $data[ 'this_month' ]["report_data"], "" );
									}
								?>
						   </div>
                           <div class="tab-pane" id="this-year">
                             <?php 
									if( isset( $data[ 'this_year' ]["report_data"] ) && is_array( $data[ 'this_year' ]["report_data"] ) ){
										__expenses( $data[ 'this_year' ]["report_data"], "year" );
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
				<div class="caption"><i class="icon-globe"></i><small>Production Manifest</small></div>
			</div>
			<div class="portlet-body shopping-cart-table" style="max-height:500px; overflow-y:auto; background:transparent !important;" id="invoice-receipt-container">
				<div style="text-align:center;">
				 <img src="<?php echo $site_url; ?>frontend-assets/img/logo_blue.png" alt="" align="center">
				</div>
			</div>
		</div>

	</div>
	
</div>
<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>