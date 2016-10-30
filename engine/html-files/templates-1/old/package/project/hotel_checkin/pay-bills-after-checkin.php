<!-- BEGIN PAGE CONTAINER -->  
<div class="page-container">
	<div class="tabbable-custom nav-justified">
		<ul class="nav nav-tabs nav-justified">
		   <li class="active"><a href="#tab_1_1_1" data-toggle="tab">Capture Payment</a></li>
		   <?php if( isset( $data['room_types']["html_replacement"] ) ){ ?><li><a href="#tab_1_1_3" data-toggle="tab">Change Room / Extend Stay</a></li><?php } ?>
		</ul>
		<div class="tab-content">
		   <div class="tab-pane active" id="tab_1_1_1">
			  <?php if( isset( $data['payment']["html_replacement"] ) )echo $data['payment']["html_replacement"]; ?>
		   </div>
		   <div class="tab-pane" id="tab_1_1_3">
			  
			  <?php if( isset( $data['room_types']["html_replacement"] ) ){ ?>
				<h5 style="margin-left:15px;"><strong>Extend Stay in Room & Move Check Out Date Forward</strong></h5>
				<div id="change-room-container">
				<?php echo $data['room_types']["html_replacement"];  ?>
				</div>
			  <?php } ?>
			  
		   </div>
		</div>
	 </div>
</div>
<!-- END PAGE CONTAINER -->  