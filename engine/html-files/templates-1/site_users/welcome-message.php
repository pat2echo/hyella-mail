<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<h3>You have successfully completed the first stage of the LRCN Online Registration</h3>
	<br />
	<h4>Welcome <?php if( isset( $data["full_name"] ) ) echo $data["full_name"]; ?></h4>
	<br />
	<br />
	<p><strong>To continue with the LRCN Membership Registration:</strong></p>
	<br />
	<p>1. Log into your email<?php if( isset( $data["email"] ) ) echo " (".$data["email"].")"; ?> and retrieve your default password</p>
	<br />
	<p>2. Click this button to <a href="<?php echo $site_url; ?>?page=login" title="LOGIN TO CONTINUE REGISTRATION" class="btn btn-primary">Login & Continue Registration</a></p>
	<br />
	<br />
</div>