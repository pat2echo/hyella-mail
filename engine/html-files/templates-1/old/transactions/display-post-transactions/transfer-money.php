<div class="row">
	<div class="col-md-4">
		<form id="account-list-form-<?php echo $mode; ?>" class="account-list-form">
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;"><?php if( ! isset( $account_label ) )$account_label = 'Account'; echo $account_label; ?></span>
		 <div class="s2-wrapper">
		 <select class="form-control select2" name="account" style="width:100%;">
			<?php
				if( ! isset( $pm ) ){
					$pm = get_first_level_accounts();
				}
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
		<br />
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Amount</span>
		 <input type="number" step="any" class="form-control" name="amount" value="0.00"/>
		</div>
		<br />
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Comment</span>
		 <input type="text" class="form-control" name="comment" placeholder="Optional Comment" />
		</div>
		<br />
		<button type="submit" id="save-account-info" class="btn dark btn-block">Add Account</button>
		</form>
	</div>
	<div class="col-md-7 col-md-offset-1 accounts-list" id="accounts-list-<?php echo $mode; ?>">
		<div class="shopping-cart-table">
			<div class="table-responsive">
				<table class="table table-striped table-hover bordered">
				<thead>
				   <tr>
					  <th>S/N</th>
					  <th><?php echo $account_label; ?></th>
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
<br />