<?php if( isset( $data['paid'] ) && $data['paid'] ){ ?>
<div class="alert alert-success" >
	<h4><i class="icon-check"></i> SUCCESSFUL PAYMENT OF REGISTRATION FEE</h4>
	<hr />
	<p>Your payment was successful</p>
</div>
<?php }else{ ?>
<div class="alert alert-info" >
	<h4><i class="icon-money"></i> PAY REGISTRATION FEE</h4>
	<p>You are to pay a non-refundable processing fee to the LRCN Bank Account</p>
	<hr />
	<?php //if( isset( $data["name"] ) )echo $data["name"]; ?>
	<p>ACCOUNT NAME / COLLECTOR NAME: <strong>Librarians' Registration Council of Nigeria</strong></p>
	<!--<p>BANK: <strong>First Bank PLC</strong></p>-->
	<p>REGISTRATION FEE: <strong>&#8358; <?php echo number_format( get_general_settings_value( array( "table" => "site_users", "key" => "REGISTRATION FEE" ) ), 2 ); ?></strong></p>
	<p>TSA REMITA PLATFORM NUMBER: <strong>0517 0270 0100</strong></p>
	<br />
	<p>
	<a class="btn red btn-lg" target="_blank" href="https://login.remita.net/remita/external/collector/payments.reg?extView=Y">MAKE PAYMENT NOW &rarr;</a>
	</p>
</div>
<?php } ?>