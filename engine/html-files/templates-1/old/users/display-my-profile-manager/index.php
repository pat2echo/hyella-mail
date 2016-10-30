<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<div style="margin:10px 40px;">
	<div class="row profile-account">
	   <div class="col-md-1">
	   </div>
	   <div class="col-md-2">
		  <ul class="ver-inline-menu tabbable margin-bottom-10">
			 <li class="active">
				<a data-toggle="tab" href="#tab_1-1">
				<i class="icon-cog"></i> 
				Personal info
				</a> 
				<span class="after"></span>                                    
			 </li>
			 <!--<li class=""><a data-toggle="tab" href="#tab_2-2"><i class="icon-picture"></i> Change Avatar</a></li>-->
			 <li class=""><a data-toggle="tab" href="#tab_3-3"><i class="icon-lock"></i> Change Password</a></li>
			 <!--<li class=""><a data-toggle="tab" href="#tab_4-4"><i class="icon-eye-open"></i> Privacy Settings</a></li>-->
		  </ul>
	   </div>
	   <div class="col-md-8">
		  <div class="tab-content remove-padding">
			 <div id="tab_1-1" class="tab-pane active">
				<div class="row">
				   <div class="col-md-6" id="user-info-page">
					<?php
						if( isset( $data['user_details'] ) )
							echo $data['user_details'];
					?>
				   </div>
				   <div class="col-md-6" style="max-height:450px; overflow-y:auto;">
					  <?php if( isset( $data["personal_info_form"] ) && $data["personal_info_form"] )echo $data["personal_info_form"]; ?>
				   </div>
				</div>
			 </div>
			 <div id="tab_3-3" class="tab-pane">
				<?php if( isset( $data["password_form"] ) && $data["password_form"] )echo $data["password_form"]; ?>
			 </div>
			 <div id="tab_4-4" class="tab-pane">
				<form action="#" class="">
				   <table class="table table-bordered table-striped">
					  <tbody><tr>
						 <td>
							Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus..
						 </td>
						 <td>
							<label class="uniform-inline">
							<div class="radio"><span><input type="radio" name="optionsRadios1" value="option1"></span></div>
							Yes
							</label>
							<label class="uniform-inline">
							<div class="radio"><span class="checked"><input type="radio" name="optionsRadios1" value="option2" checked=""></span></div>
							No
							</label>
						 </td>
					  </tr>
					  <tr>
						 <td>
							Enim eiusmod high life accusamus terry richardson ad squid wolf moon
						 </td>
						 <td>
							<label class="uniform-inline">
							<div class="checker"><span><input type="checkbox" value=""></span></div> Yes
							</label>
						 </td>
					  </tr>
					  <tr>
						 <td>
							Enim eiusmod high life accusamus terry richardson ad squid wolf moon
						 </td>
						 <td>
							<label class="uniform-inline">
							<div class="checker"><span><input type="checkbox" value=""></span></div> Yes
							</label>
						 </td>
					  </tr>
					  <tr>
						 <td>
							Enim eiusmod high life accusamus terry richardson ad squid wolf moon
						 </td>
						 <td>
							<label class="uniform-inline">
							<div class="checker"><span><input type="checkbox" value=""></span></div> Yes
							</label>
						 </td>
					  </tr>
				   </tbody></table>
				   <!--end profile-settings-->
				   <div class="margin-top-10">
					  <a href="#" class="btn green">Save Changes</a>
					  <a href="#" class="btn default">Cancel</a>
				   </div>
				</form>
			 </div>
		  </div>
	   </div>
	   <!--end col-md-9-->
	   <div class="col-md-1">
	   </div>
	</div>
 </div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>