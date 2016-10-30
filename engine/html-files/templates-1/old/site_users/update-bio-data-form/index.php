<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
	$status = 0;
?>
<div style="/*margin:10px 40px;*/">
	<h4 style="text-align:center;"><?php echo GLOBALS_DEFAULT_SITE_TITLE; ?> Members Profile Update</h4>
	<hr />
	<div class="row profile-account">
	   <div class="col-md-4">
		  <ul class="ver-inline-menu  margin-bottom-10">
			
			<li class="active">
				<a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="update_bio_data_form" function-class="site_users">
					<i class=""></i> Update Bio Data
				</a>
			</li>
			
			<li>
				<a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="update_view_change_of_name" function-class="change_of_name"> Request Change of Name</a>
			</li>
			
			<li>
				<a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="update_employment_details_form" function-class="site_users">
					<i ></i> Present Employment Details
				</a>
			</li>
			
			<li>
				<a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="update_additional_documents_form" function-class="site_users">
					<i ></i> Additional Documents
				</a>
			</li>
			
			<li>
				<a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="update_view_educational_history" function-class="educational_history">
					<i></i> Education History
				</a>
			</li>
			
			
			<li>
				<a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="update_view_work_history" function-class="work_history">
					<i></i> Work History
				</a>
			</li>
			
		  </ul>
	   </div>
	   <div class="col-md-8" id="form-content-area">
		
			<?php if( isset( $data["html"] ) )echo $data["html"]; ?>
	   </div>
	   <!--end col-md-9-->
	</div>
 </div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>