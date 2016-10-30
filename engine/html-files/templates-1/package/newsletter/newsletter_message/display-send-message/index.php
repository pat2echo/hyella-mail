<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<?php 
				if( isset( $data[ "message_details" ][ "id" ] ) ){
					$m = $data[ "message_details" ];
			?>
			<div class="col-md-5"> 
				<h3>Settings</h3>
				<hr />
				<div class="input-group">
				 <span class="input-group-addon" style=" line-height: 1.5;">Subject</span>
				 <span  class="input-group-addon" style="background: #A7E862; line-height: 1.5;"><?php echo $m["subject"]; ?></span>
				</div>
				<br />
				<div class="input-group">
					 <span class="input-group-addon" style="color:#777;">Recipient Category</span>
					  <select class="form-control" name="recipients">
						<option value="all">All Recipients</option>
						<?php
							if( isset( $data['recipients'] ) && is_array( $data['recipients'] ) ){
								foreach( $data['recipients'] as $key => $val ){
									if( ! $val['category'] )$val['category'] = 'Uncategorized';
									
									?>
									<option value="<?php echo $val['category']; ?>">
										<?php echo $val['category']; ?>
									</option>
									<?php
								}
							}
						?>
					 </select>
					 <span class="input-group-btn">
						 <button class="btn dark custom-single-selected-record-button" action="?module=&action=newsletter_subscribers&todo=view_subscribers" id="view-recipients-info" type="button" title="View Recipients"><i class="icon-external-link"></i>&nbsp;</button>
					 </span>
				  </div>
				<hr />
				<div class="input-group">
				 <span class="input-group-addon" style="color:#777;">Sending Channel</span>
				 <select class="form-control" name="sending_channel">
					<?php
						$pm = get_sending_channels();
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
				<br />
				<div class="input-group">
				 <span class="input-group-addon" style="color:#777;">Username</span>
				 <input class="form-control" name="username" type="text" />
				</div>
				<br />
				<div class="input-group">
				 <span class="input-group-addon" style="color:#777;">Password</span>
				 <input type="password" class="form-control" name="password" />
				</div>
				<hr />
				
				<div class="btn-group btn-group-justified">
					
					<a class="btn btn-lg red custom-single-selected-record-button" override-selected-record="1" action="?module=&action=newsletter_message&todo=send_message" style="display:none;" id="cart-finish" href="#">Send</a>
					<a class="btn btn-lg red" id="send-finish" href="#">Send</a>
					
				</div>
			</div>
			
			<div class="col-md-7"> 
				<div class="portlet grey box">
					<div class="portlet-title">
						<div class="caption"><small>Sending Status</small></div>
					</div>
					<div class="portlet-body" style="padding-bottom:50px; overflow-y:auto; max-height:400px;" id="sending-status-container">
						<div id="msg-new-container">
							<!--container for new newsletter-->
						</div>
						
						<div id="msg-sending-status-container">
						<?php include dirname( dirname( __FILE__ ) ) . "/display-messages.php"; ?>
						</div>
						
						<a href="#" style="display:none;" id="check-all-status" class="btn dark btn-sm custom-single-selected-record-button" action="?module=&action=newsletter_message&todo=check_sending_status" override-selected-record="<?php echo $m["id"]; ?>">Check All Status</a>
						<?php
							echo $m["message"];
						?>
					</div>
				</div>
			</div>
			<?php 
				}else{
			?>
			<div class="alert alert-danger">
				<h4><i class="icon-bell"></i> No Message</h4>
				<div>
				Please try again
				</div>
				<br />
			</div>

			<?php
				}
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
	var g_message = "<?php if( isset( $m["id"] ) )echo $m["id"]; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>