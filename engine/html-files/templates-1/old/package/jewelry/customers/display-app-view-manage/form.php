<form class="activate-ajax" method="post" id="customers" action="?action=customers&todo=save_app_changes&manage=1">
	 <input type="hidden" name="id" class="form-control" value="" />
	 <br />
	
	<div class="row">
		<div class="col-md-8">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Full Name</span>
			 <input type="text" required="required" class="form-control" name="name" />
			</div>
		</div>
		<div class="col-md-4">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;" title="Date of Birth">DOB</span>
			 <input type="date" class="form-control" name="date_of_birth" style="font-size:11px;" />
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-4">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Phone</span>
			 <input type="text" class="form-control" name="phone" />
			</div>
		</div>
		<div class="col-md-4">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Phone 2</span>
			 <input type="text" class="form-control" name="second_phone" />
			</div>
		</div>
		<div class="col-md-4">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Email</span>
			 <input type="email" class="form-control" name="email" />
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-8">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Address</span>
			 <input type="text" class="form-control" name="address" />
			</div>
		</div>
		<div class="col-md-4">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">City</span>
			
			  <select class="form-control" name="city">
				<?php
					$vendors =  get_all_states_in_nigeria();
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
	</div>
	<br />
	<div class="row">
		<div class="col-md-8">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Spouse Name</span>
			 <input type="text" class="form-control" name="spouse" />
			</div>
		</div>
		<div class="col-md-4">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Credit Limit</span>
			 <input type="number" step="any" min="0" value="0" class="form-control" name="credit_limit" />
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-4">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">His Ring Size</span>
			 <input type="number" step="any" min="0" value="0" class="form-control" name="his_ring_size" />
			</div>
		</div>
		<div class="col-md-4">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Her Ring Size</span>
			 <input type="number" step="any" min="0" value="0" class="form-control" name="her_ring_size" />
			</div>
		</div>
		<div class="col-md-4">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Source</span>
			 <input type="text" class="form-control" name="referral_source" />
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-12">
			<div class="input-group">
			 <span class="input-group-addon" style="color:#777;">Comment</span>
			 <input type="text" class="form-control" name="comment" placeholder="Optional Comment" />
			</div>
		</div>
	</div>
	
	<hr />
	<div class="row">
		<div class="col-md-6">
			<input type="submit" class="btn btn-lg green btn-block" value="Update" /><br />
		</div>
		<div class="col-md-6">
			<input type="reset" class="btn btn-lg dark btn-block populate-with-selected custom-single-selected-record-button" action="?module=&action=customers&todo=delete_app_record" override-selected-record="" value="Delete" />
		</div>
	</div>
	
  </form>