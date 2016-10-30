<?php
	$width = "";
	if( ! ( isset( $data["skip_style"] ) && $data["skip_style"] ) ){
		include "email-style.php";
		$width = "500px";
	}
?>
<style type="text/css">
.textdark { 
color: #444444;
font-family: Open Sans;
font-size: 13px;
line-height: 150%;
text-align: left;
}
</style>
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
	<tr>
		<td>
			<center>
				<table border="0" cellpadding="0" cellspacing="0" width="<?php echo $width; ?>" style="height:100%; background-color:#dff0d8; border-bottom:1px solid #d6e9c6;">
					<tr>
						<td valign="top" style="text-align:right; padding:20px;">
						<strong><?php echo GLOBALS_DEFAULT_SITE_TITLE; ?></strong>
						</td>
					</tr>
					<tr>
						<td valign="top" style="padding:20px;">
							<strong>Dear <?php if( isset( $data["full_name"] ) ) echo $data["full_name"]; ?>,</strong>
							<h4>
								<strong><?php $key = "title"; if( isset( $data[ $key ] ) && $data[ $key ] )echo $data[ $key ]; ?></strong>
							</h4>
							<br />
							<hr />
							<div class="textdark">
								<strong><?php $key = "info"; if( isset( $data[ $key ] ) && $data[ $key ] )echo $data[ $key ]; ?></strong>
							</div>
							<hr />
							<br />
							<div class="textdark">
							<?php 
								if( isset( $data["mail_type"] ) && $data["mail_type"] ){ //recommendation 
									switch( $data["mail_type"] ){
									case 1:
							?>
							<p>The applicant with the above stated details has applied to become a certified librarian at the LIBRARIANS REGISTRATION COUNCIL OF NIGERIA</p>
							<p>Before the application can get through to the next stage, you are expected <strong>RECOMMENDED</strong> this applicant.</p>
							<p><strong>To recommend this applicant:</strong></p>
							
							<p>Click this link to <a target="_blank" href="<?php echo $site_url; ?>?page=recommendation&m=<?php $key = "email"; if( isset( $data[ $key ] ) && $data[ $key ] )echo md5($data[ $key ]); ?>&id=<?php $key = "applicant_id"; if( isset( $data[ $key ] ) && $data[ $key ] )echo $data[ $key ]; ?>" title="RECOMMEND APPLICANT" class="btn red">Recommend <?php $key = "applicant_name"; if( isset( $data[ $key ] ) && $data[ $key ] )echo $data[ $key ]; ?></a></p>
							<?php 
									break;
									case 2:
									?>
									<p>The applicant with the above stated details have been recommended to become a certified librarian at the LIBRARIANS REGISTRATION COUNCIL OF NIGERIA</p>
									<p>Thank you for recommending this applicant</p>
									<?php
									break;
									case 3:
							?>
							<p>Click this link to Login to your LRCN Account<br /><br /><a target="_blank" href="<?php echo $site_url; ?>?page=login" title="LOGIN TO YOUR LRCN ACCOUNT" class="btn red">LOGIN TO YOUR LRCN ACCOUNT</a></p>
							<?php 
									break;
									case 4:
							?>
							<p>Click this link to Login to your LRCN Account and Renew your Membership Status / Update your Profile<br /><br /><a target="_blank" href="<?php echo $site_url; ?>?page=login" title="LOGIN TO YOUR LRCN ACCOUNT" class="btn red">LOGIN TO YOUR LRCN ACCOUNT</a></p>
							<br />
							<?php 
									case 5:
									case 6:
									case 7:
										if( isset( $data["html"] ) )echo $data["html"];
									break;
									}
								}else{
							?>
							<p>You have successfully completed the first stage of the LRCN Online Registration</p>
							<p><strong>To continue with the LRCN Membership Registration:</strong></p>
							
							<p>Click this link to <a href="<?php echo $site_url; ?>?page=login" target="_blank" title="LOGIN TO CONTINUE REGISTRATION" class="btn red">Login & Continue Registration</a></p>
								<?php
							} ?>
							</div><br />
						</td>
					</tr>
				</table>
			</center>
		</td>
	</tr>
</table>