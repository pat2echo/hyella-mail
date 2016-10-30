<br />
<div class="alert alert-danger">
	<?php if( isset( $data["type"] ) && $data["type"] ){ ?>
	<h4>INVALID RECOMMENDATION REQUEST</h4>
	<p>The Applicant has changed your email address in his/her application form</p>
	<p>Please contact the Applicant to restart the <strong>"Recommendation Process"</strong> and enter your email address, if you are to recommend him/her</p>
	<?php }else{ ?>
	<h4>INVALID RECOMMENDATION LINK / DETAILS</h4>
	<p>Please contact the Applicant to restart the <strong>"Recommendation Process"</strong> as this will send you a new recommendation email & link</p>
	<?php } ?>
</div>