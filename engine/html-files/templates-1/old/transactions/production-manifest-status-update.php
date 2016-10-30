<!-- BEGIN PAGE CONTAINER -->  
<div class="page-container">
	<div class="tabbable-custom nav-justified">
		<ul class="nav nav-tabs nav-justified">
		   <li class="active"><a href="#tab_1_1_1" data-toggle="tab">Info</a></li>
		   <li><a href="#tab_1_1_2" data-toggle="tab">Goods Produced</a></li>
		   <li><a href="#tab_1_1_3" data-toggle="tab">Raw Materials</a></li>
		   <li><a href="#tab_1_1_4" data-toggle="tab">Expenses</a></li>
		</ul>
		<div class="tab-content">
		   <div class="tab-pane active" id="tab_1_1_1">
			  <?php if( isset( $data["production_form"]["html"] ) )echo $data["production_form"]["html"]; ?>
		   </div>
		   <div class="tab-pane" id="tab_1_1_2">
			  <?php if( isset( $data['produced_items']["html_replacement"] ) )echo $data['produced_items']["html_replacement"]; ?>
		   </div>
		   <div class="tab-pane" id="tab_1_1_3">
			  <?php if( isset( $data['materials']["html_replacement"] ) )echo $data['materials']["html_replacement"]; ?>
		   </div>
		   <div class="tab-pane" id="tab_1_1_4">
			  <?php if( isset( $data['expenses']["html_replacement"] ) )echo $data['expenses']["html_replacement"]; ?>
		   </div>
		</div>
	 </div>
</div>
<!-- END PAGE CONTAINER -->  