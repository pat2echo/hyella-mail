<br />
<div class="alert alert-info">
	<?php if( isset( $data["title"] ) && $data["title"] ){ ?>
	<h4><?php echo $data["title"]; ?></h4>
	<?php } ?>
	<a href="#" function-id="1" function-class="emails" function-name="view_today_transaction_status" module="" title="Click Here to View the Progress of the transactions" class="btn red pull-right btn-sm custom-action-button" ><i class="icon-plus"></i> View Progress Status</a>
	<p>&nbsp;</p>
	<?php if( isset( $data["body"] ) && $data["body"] ){ ?>
	<p><?php echo $data["body"]; ?></p>
	<?php } ?>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<a href="#" function-id="1" function-class="emails" function-name="view_today_transaction_status" module="" title="Click Here to View the Progress of the transactions" class="btn red btn-sm custom-action-button" ><i class="icon-plus"></i> View Progress Status</a>
</div>