<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	  if( isset( $data["html"] ) ){
		  ?>
		  <div class="row">
			<div class="col-md-7">
				<h4><strong><?php echo $data["title"]; ?></strong></h4>
				<hr />
				<?php  echo $data["html"];  ?>
			</div>
			<div class="col-md-5">
			 <a class="dark btn btn-block custom-action-button" skip-title="1" function-class="customers" function-name="new_room_guest_form" function-id="120" href="#">
				 <i class="icon-plus"></i> Create New Room Guest
			 </a>
			 <div id="room-guest-container">
				<div class="alert alert-warning">
					<strong>Click the button above to create a new room guest</strong>
				</div>
			 </div>
			 </div>
		 </div>
		  <?php
		
	  }
	?>
<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>