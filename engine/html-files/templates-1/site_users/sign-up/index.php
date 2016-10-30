<?php //if( file_exists( dirname( dirname( dirname( __FILE__ ) ) ).'/globals/breadcrum.php' ) )include dirname( dirname( dirname( __FILE__ ) ) ).'/globals/breadcrum.php'; ?>

<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<div class="container">
	<div class="row">
	<div class="col-md-4">
		<img src="<?php echo $display_pagepointer; ?>frontend-assets/img/sliders/revolution/man-winner-1.png" style="max-width:60%; margin-top:20px;" />
		
		<blockquote>
			<p>1. Obtain Academic Qualification</p>
			 <small>The min. qualification required is first degree in library & information science</small>
		</blockquote>
		
		<blockquote>
			<p>2. Register Online</p>
			 <small>Fill out the online registration form and verify your email address</small>
		</blockquote>
		
		<blockquote>
			<p>3. Pay the required Registration Fee</p>
			 <small>The sum of Five Thousand Naira (N5, 000) only should be paid into any of the LRCN bank account</small>
		</blockquote>
	</div>
	<div class="col-md-7">
		<div style="box-shadow: 1px 11px 11px 3px #ddd; margin-top:0px; margin-bottom:30px; padding:20px;">
			 <small><strong><?php echo GLOBALS_DEFAULT_SITE_TITLE; ?> Membership Form</strong></small>
			 <h3 style="margin-top:0;">Register Online &darr;</h3>
			 <a class="btn btn-success pull-right btn-sm" href="?page=login" title="Login to Continue Registration, Manage your Profile or Renew License">Already Registered? Login</a>
		  <!--
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
		  <div id="registration-form-container">
			<?php if( isset( $data['html'] ) )echo $data['html']; ?>
	
			</div>
		</div>
	</div>
	</div>
</div>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>