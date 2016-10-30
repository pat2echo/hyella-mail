<?php //if( file_exists( dirname( dirname( dirname( __FILE__ ) ) ).'/globals/breadcrum.php' ) )include dirname( dirname( dirname( __FILE__ ) ) ).'/globals/breadcrum.php'; ?>

<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<div class="container">
	<div class="row">
	<div class="col-md-6">
		<br />
		<br />
		<br />
		<br />
		<br />
		<h4>Registered Librarians</h4>
		<hr />
		<div id="verify-membership-results">
			 </div>
	</div>
	<div class="col-md-6">
		<div style="box-shadow: 1px 11px 11px 3px #ddd; margin-top:0px; margin-bottom:30px; padding:20px;">
			 <small><strong><?php echo GLOBALS_DEFAULT_SITE_TITLE; ?> Membership Verification</strong></small>
			 <h3 style="margin-top:0;">Search for Librarians &darr;</h3>
			 
		  <!--
			 <a class="btn btn-success pull-right btn-sm" href="?page=login" title="Login to Continue Registration, Manage your Profile or Renew License">Already Registered? Login</a>
		  <div class="pull-right1">
			  <div class="radio1 pull-left" style="margin-right:30px;">
				  <label>
					  <input type="radio" name="register_type" data-control-id="#site_users009"  value="merchant" checked="">
					  Merchant
				  </label>
			  </div>
			  <div class="radio1 pull-left">
				  <label>
					  <input type="radio" name="register_type" data-control-id="#site_users009" value="customer" checked="checked">
					  Customer
				  </label>
			  </div>
		  </div>
		-->
		  <div style="clear:both;"></div>
		  <div >
			<?php include "query-survey-form.php"; ?>
	
			</div>
		</div>
	</div>
	</div>
</div>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>