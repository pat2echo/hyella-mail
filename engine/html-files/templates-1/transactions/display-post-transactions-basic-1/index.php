<!--EXCEL IMPOT FORM-->
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
<!--EXCEL IMPOT FORM-->
<div class="row">
	<div class="col-md-3" style="padding-right:0;">
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Date</span>
		 <input type="date" class="form-control" name="date" value="<?php echo date("Y-m-d") ?>" />
		</div>
	</div>
	<div class="col-md-6" style="padding-right:0;">
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
<h4>Accounts to Debit & Credit <code id="balance-container" class="pull-right green">Balance: <strong class="balance">0.00</strong> <span class="balance-icon"><i class="icon-ok"></i></span></code></h4>
<hr />
<div class="row">
	<div class="col-md-3" style="padding-right:0;">
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Debit / Credit</span>
		 <select class="form-control" name="type">
			<?php
				$pm = get_transaction_type();
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
	<div class="col-md-3" style="padding-right:0;">
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Amount</span>
		 <input type="number" step="any" class="form-control" name="amount" value="0.00"/>
		</div>
	</div>
	<div class="col-md-4" style="padding-right:0;">
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Account</span>
		 <div class="s2-wrapper">
		 <select class="form-control select2" name="account" style="width:100%;">
			<?php
				$pm = get_first_level_accounts();
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
		 <div class="s2-wrapper s2-account-select-2">
		 <select class="form-control select2 account-select-2" style="width:100%;" name="account2" > </select>
		 </div>
		 <div class="s2-wrapper s2-account-select-3">
		 <select class="form-control select2 account-select-3" name="account3" > </select>
		 </div>	
		 
		 <a href="#" id="check-for-sub-account" class="btn green btn-sm custom-single-selected-record-button" action="?module=&action=chart_of_accounts&todo=check_for_subaccount" mod="" title="Check for Sub-account" style="display:none;">Check Sub-Account</a>
		</div>
	</div>
	<div class="col-md-2">
		<button type="button" id="save-account-info" class="btn dark btn-block">Save</button>
	</div>
</div>
<br />
<div class="row">
	<div class="col-md-6" id="debit-side"> 
		<div class="shopping-cart-table">
			<div class="table-responsive">
				<table class="table table-striped table-hover bordered">
				<thead>
				   <tr>
					  <th>Debit</th>
					  <th class="r">Amount</th>
					  <th class="r"></th>
				   </tr>
				</thead>
				<tbody>
				   
				</tbody>
				<tfoot>
				   
				</tfoot>
				</table>
			</div>
			
		</div>
		
	</div>
	
	<div class="col-md-6" id="credit-side"> 
	
		<div class="shopping-cart-table">
			<div class="table-responsive">
				<table class="table table-striped table-hover bordered">
				<thead>
				   <tr>
					  <th>Credit</th>
					  <th class="r">Amount</th>
					  <th class="r"></th>
				   </tr>
				</thead>
				<tbody>
				   
				</tbody>
				<tfoot>
				   
				</tfoot>
				</table>
			</div>
			
		</div>
		
	</div>

</div>
<div class="row">
	<div class="col-md-12">
		<div class="clearfix">
			<button type="button" class="save-transaction btn green" id="draft">Save Transaction</button>
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