<div class="row">
<div class="col-md-10 col-md-offset-1">

<div class="portlet grey box">
<div class="portlet-title">
	<div class="caption"><small>Post Transaction</small></div>
</div>
<div class="portlet-body" style="padding-bottom:50px;">

<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$user_id = "";
	if( isset( $user_info["user_id"] ) )
		$user_id = $user_info["user_id"];
	
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
?>
<div class="row">
	<div class="col-md-4" style="padding-right:0;">
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Account</span>
		 <input type="hidden" class="form-control" name="date" value="<?php echo date("Y-m-d") ?>" />
		  <select class="form-control" name="source_account">
			<?php
				$pm = get_payment_method_list();
				if( isset( $pm ) && is_array( $pm ) ){
					foreach( $pm as $key => $val ){
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
	<div class="col-md-5" style="padding-right:0;">
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Description</span>
		 <input type="text" class="form-control" name="description" placeholder="Description of Transaction">
		</div>
	</div>
	<div class="col-md-3">
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Tag</span>
		 <input type="text" class="form-control" name="reference_table" placeholder="Optional Tag">
		</div>
	</div>
</div>
<br />
<div class="tabbable tabbable-custom" id="transaction-tabs">
	<ul class="nav nav-tabs">
	   <li class="active"><a data-toggle="tab" href="#transfer-money">Transfer Money</a></li>
	   <li><a data-toggle="tab" href="#pay-vendors">Pay Vendors</a></li>
	   <li><a data-toggle="tab" href="#pay-bills">Pay Bills</a></li>
	   <li><a data-toggle="tab" href="#pay-liabilities">Pay Liabilities</a></li>
	   <li><a data-toggle="tab" href="#post-customer-payment">Post Customer Payment</a></li>
	   <button type="button" class="btn btn-sm red pull-right custom-action-button" function-id="grace-id" function-class="transactions" function-name="post_new_general_transaction" module-id="gracem-1" module-name="-" skip-title="1">Post General Transaction</button>
	</ul>
	<div class="tab-content" style="overflow-y:auto; max-height:300px; overflow-x:hidden;">
		<div class="tab-pane active" id="transfer-money">
			<?php 
				$pm = get_payment_method_list();
				$account_label = 'Receiving Account';
				$mode = "transfer-money";
				$account_type = "cash_book";
				
				include "transfer-money.php"; 
			?>
		</div>
		<div class="tab-pane" id="pay-vendors">
			<?php 
				$pm = get_vendors();
				$account_label = 'Vendor';
				$mode = "pay-vendors";
				$account_type = "account_payable";
				
				$reason_label = 'Purchase Orders';
				$reason_action = '?module=&action=debit_and_credit&todo=get_vendor_unpaid_goods_delivery_note';
				$reason_details_action = '?module=&action=expenditure&todo=view_invoice';
				$reason_pm = array();
				
				$pm_select = "-Select Vendor-";
				
				include "transfer-money.php"; 
				
				unset( $pm_select );
				unset( $reason_pm );
				unset( $reason_label );
				unset( $reason_action );
				unset( $reason_details_action );
				
			?>
		</div>
		<div class="tab-pane" id="pay-bills">
			<?php 
				$pm = get_types_of_expenditure();
				$account_label = 'Bills';
				$mode = "pay-bills";
				$account_type = "operating_expense";
				
				include "transfer-money.php"; 
			?>
		</div>
		<div class="tab-pane" id="pay-liabilities">
			<?php 
				$pm = get_liabilities_accounts();
				$account_label = 'Liabilities';
				$mode = "pay-liabilities";
				$account_type = "liabilities";
				
				include "transfer-money.php"; 
			?>
		</div>
		<div class="tab-pane" id="post-customer-payment">
			<?php 
				$pm = get_customers();
				$pm_select = "-Select Customer-";
				
				$account_label = 'Customer';
				$account_type = "accounts_receivables";
				
				$reason_label = 'Sales Invoices';
				$reason_action = '?module=&action=debit_and_credit&todo=get_customer_unpaid_sales_invoices';
				$reason_details_action = '?module=&action=sales&todo=view_invoice';
				
				$reason_pm = array();
				
				$mode = "post-customer-payment";
				include "transfer-money.php"; 
			?>
		</div>
		
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="clearfix">
			<button type="button" class="save-transaction btn pull-right" id="preview">Preview Transaction</button>
			<button type="button" class="save-transaction btn green pull-right" id="flat-draft">Post Transaction</button>
			<button type="button" class="save-transaction btn green pull-right" id="preview-draft" style="display:none;">Post Transaction</button>
			<!--
			<button type="button" class="save-transaction btn dark" id="submitted">Submit Transaction for Review</button>
			<button type="button" class="save-transaction btn blue" id="approved">Post Transaction</button>
			-->
			<a href="#" id="save-transaction" class="btn green btn-sm custom-single-selected-record-button" action="?module=&action=transactions&todo=save_transaction_manifest" mod="" title="Save" style="display:none;">Save</a>
		</div>
	</div>
</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>

</div>
</div>
</div>
</div>