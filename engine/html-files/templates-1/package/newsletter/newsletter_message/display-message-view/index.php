<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<div class="col-md-2">
				<br />
				<a class="btn btn-lg red custom-single-selected-record-button" override-selected-record="1" action="?module=&action=newsletter_message&todo=save_message" style="display:none;" id="cart-finish" href="#">Save</a>
				
				<a href="#" class="btn red btn-block" id="send-finish">Save Changes</a>
				<hr />
				<a href="" class="btn dark btn-block">Exit</a>
			</div>
			<div class="col-md-8">
				<div class="portlet grey box">
					<div class="portlet-title">
						<div class="caption"><small><?php if( isset( $data["message"]["subject"] ) )echo $data["message"]["subject"]; ?></small></div>
					</div>
					<div class="portlet-body" style="padding-bottom:50px; overflow-y:auto; max-height:400px;" id="message-container">
						<div class="editable"><?php if( isset( $data["message"]["message"] ) )echo $data["message"]["message"]; ?></div>
						
					</div>
				</div>
			</div>
			<div class="col-md-2">
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var g_message_id = "<?php if( isset( $data["message"]["id"] ) )echo $data["message"]["id"]; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>