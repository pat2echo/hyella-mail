<!-- BEGIN PAGE CONTAINER -->  
<div class="page-container">
	<div class="tabbable-custom nav-justified">
		<ul class="nav nav-tabs nav-justified">
		   <li class="active"><a href="#tab_1_1_1" data-toggle="tab">General Info</a></li>
		   <li><a href="#tab_1_1_2" data-toggle="tab">Capture Payment</a></li>
		   <li><a href="#tab_1_1_3" data-toggle="tab">Items Sold</a></li>
		</ul>
		<div class="tab-content">
		   <div class="tab-pane active" id="tab_1_1_1">
			  <?php if( isset( $data["sales_form"]["html"] ) )echo $data["sales_form"]["html"]; ?>
		   </div>
		   <div class="tab-pane" id="tab_1_1_2">
			  <?php if( isset( $data['payment']["html_replacement"] ) )echo $data['payment']["html_replacement"]; ?>
		   </div>
		   <div class="tab-pane" id="tab_1_1_3">
			  <?php if( isset( $data['items_sold']["html_replacement"] ) )echo $data['items_sold']["html_replacement"]; ?>
		   </div>
		</div>
	 </div>
</div>
<!-- END PAGE CONTAINER -->  