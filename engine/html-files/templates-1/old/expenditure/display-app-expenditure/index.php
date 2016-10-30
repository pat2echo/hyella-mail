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
                           <li class="active"><a href="#recent-expenses" data-toggle="tab">Recent Expenses</a></li>
						   <li><a href="#this-month" data-toggle="tab" class="custom-action-button" function-name="this_month_expenses" function-class="expenditure" function-id="1">This Month</a></li>
                           <li><a href="#this-year" data-toggle="tab" class="custom-action-button" function-name="this_year_expenses" function-class="expenditure" function-id="2">This Year</a></li>
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
									/*
									if( isset( $data[ 'this_month' ]["report_data"] ) && is_array( $data[ 'this_month' ]["report_data"] ) ){
										__expenses( $data[ 'this_month' ]["report_data"], "" );
									}
									*/
								?>
						   </div>
                           <div class="tab-pane" id="this-year">
                             <?php 
									/*
									if( isset( $data[ 'this_year' ]["report_data"] ) && is_array( $data[ 'this_year' ]["report_data"] ) ){
										__expenses( $data[ 'this_year' ]["report_data"], "year" );
									}
									*/
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
				<div class="caption"><i class="icon-globe"></i><small>Expenses</small></div>
			</div>
			<div class="portlet-body shopping-cart-table" style="background:transparent !important;">
				  <form class="activate-ajax" method="post" id="expenditure" action="?action=expenditure&todo=record_expense">
					 <input type="hidden" name="id" class="form-control" value="" />
					 <input type="hidden" name="date" value="<?php echo date("Y-m-d") ?>" class="form-control" value="" />
					 <input type="hidden" name="store" class="form-control" value="" />
					 <br />
					<div class="input-group">
					 <span class="input-group-addon" style="color:#777;">Description</span>
					 <input type="text" required="required" class="form-control" name="description" />
					</div>
					<div class="row">
						<div class="col-md-7">
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Date</span>
							 <input type="date" required="required" class="form-control" name="date" value="<?php echo date("Y-m-d") ?>" />
							</div>
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Category</span>
							 <select required="required" class="form-control" name="category_of_expense" >
								<?php
									//$vendors = get_types_of_expenditure();
									$vendors = get_types_of_expenditure_grouped();
									if( ( ! empty( $vendors ) ) && is_array( $vendors ) ){
										foreach( $vendors as $key => $val ){
											?>
											<optgroup label="<?php echo $key; ?>">
												<?php foreach( $val as $k => $v ){ ?>
												<option value="<?php echo $k; ?>">
													<?php echo $v; ?>
												</option>
												<?php } ?>
											</optgroup>
											<?php
										}
									}
								?>
							 </select>
							</div>
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Vendor</span>
							  <select required="required" class="form-control" name="vendor" >
								<?php
									$vendors = get_vendors_supplier();
									//get_vendors();
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
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Staff</span>
							  <select required="required" class="form-control" name="staff_in_charge" >
								<?php
									$vendors = get_employees();
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
						</div>
						<div class="col-md-5">
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Amount Due</span>
							 <input type="number" required="required" min="1" step="any" name="amount_due" class="form-control" value="0">
							</div>
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Amount Paid</span>
							 <input type="number" step="any" class="form-control" name="amount_paid" value="0">
							</div>
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Receipt Num</span>
							 <input type="text" class="form-control" name="receipt_no" value="">
							</div>
							<br />
							<div class="input-group">
							 <span class="input-group-addon" style="color:#777;">Payment</span>
							 <select required="required" class="form-control" name="mode_of_payment">
								<?php
									$pm = get_payment_method_grouped();
									if( isset( $pm ) && is_array( $pm ) ){
										foreach( $pm as $k => $v ){
											?>
											<optgroup label="<?php echo $k; ?>">
											<?php
											foreach( $v as $key => $val ){
											?>
											<option value="<?php echo $key; ?>">
												<?php echo $val; ?>
											</option>
											<?php
											}
											?>
											</optgroup>
											<?php
										}
									}
								?>
							 </select>
							</div>
							
						</div>
					</div>
					<hr />
					<div class="row">
						<div class="col-md-12">
							<input type="submit" class="btn btn-lg green btn-block" value="Save Changes" /><br />
						</div>
						<div class="col-md-6">
							<input type="reset" class="btn btn-lg default btn-block" onclick="nwExpenses.emptyNewItem();" value="New Expense" />
						</div>
						<div class="col-md-6">
							<input id="delete-expense-button" class="btn btn-lg dark btn-block custom-single-selected-record-button" override-selected-record="" action="?module=&action=expenditure&todo=delete_expense_from_stock" value="Delete" />
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