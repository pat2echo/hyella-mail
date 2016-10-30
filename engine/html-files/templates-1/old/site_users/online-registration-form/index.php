<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
	$status = 0;
?>
<div style="/*margin:10px 40px;*/">
	<h4 style="text-align:center;"><?php echo GLOBALS_DEFAULT_SITE_TITLE; ?> Membership Registration Process</h4>
	<h5 style="text-align:center;"><strong id="registration-status-holder"><span style="color:#cc3c3b;"><i class="<?php if( $status )echo "icon-check"; else echo "icon-warning-sign"; ?>"></i> 3 out of 10 Complete</span></strong></h5>
	<hr />
	<div class="row profile-account">
	   <div class="col-md-4">
		  <ul class="ver-inline-menu  margin-bottom-10">
			<?php
				$status = 0;
				$count = 0;
				$keys = array( "firstname", "lastname", "email", "title", "phonenumber", "birth_day", "sex", "country", "state", "city", "street_address" );
				foreach( $keys as $key ){
					if( isset( $data["user_details"][ $key ] ) && $data["user_details"][ $key ] ){
						++$count;
					}
				}
				if( $count == count($keys) )$status = 1;
			?>
			<li class="active">
				<a data-toggle="tab" href="#tab_3" <?php if( ! $status )echo ' style="color:#cc3c3b;" '; ?> function-id="1" class="custom-action-button" function-name="online_registration_form" function-class="site_users">
					<i id="site_users-online_registration_form" class="<?php if( $status )echo "icon-check"; else echo "icon-warning-sign"; ?> registration-status"></i> 1. Online Registration
				</a>
			</li>
			
			<?php
				$status = 0;
				$count = 0;
				$keys = array( "photograph" );
				foreach( $keys as $key ){
					if( isset( $data["user_details"][ $key ] ) && $data["user_details"][ $key ] ){
						++$count;
					}
				}
				if( $count == count($keys) )$status = 1;
			?>
			<li>
				<a data-toggle="tab" href="#tab_3" <?php if( ! $status )echo ' style="color:#cc3c3b;" '; ?> function-id="1" class="custom-action-button" function-name="online_registration_passport_form" function-class="site_users">
					<i id="site_users-online_registration_passport_form" class="<?php if( $status )echo "icon-check"; else echo "icon-warning-sign"; ?> registration-status"></i> 2. Upload Passport Photograph
				</a>
			</li>
			
			<?php
				$status = 1;
				$count = 0;
				/*
				$keys = array( "photograph" );
				foreach( $keys as $key ){
					if( isset( $data["user_details"][ $key ] ) && $data["user_details"][ $key ] ){
						++$count;
					}
				}
				if( $count == count($keys) )$status = 1;
				*/
			?>
			<li>
				<a data-toggle="tab" href="#tab_3" <?php if( ! $status )echo ' style="color:#cc3c3b;" '; ?> function-id="1" class="custom-action-button" function-name="online_registration_employment_form" function-class="site_users">
					<i id="site_users-online_registration_employment_form" class="<?php if( $status )echo "icon-check"; else echo "icon-warning-sign"; ?> registration-status"></i> 3. Present Employment Details
				</a>
			</li>
			
			<?php
				$status = 0;
				$count = 0;
				if( isset( $data['education_history'] ) && is_array( $data['education_history'] ) && ! empty( $data['education_history'] ) )$status = 1;
			?>
			<li>
				<a data-toggle="tab" href="#tab_3" <?php if( ! $status )echo ' style="color:#cc3c3b;" '; ?> function-id="1" class="custom-action-button" function-name="add_view_educational_history" function-class="educational_history">
					<i id="educational_history-add_view_educational_history" class="<?php if( $status )echo "icon-check"; else echo "icon-warning-sign"; ?> registration-status"></i> 4. Education History
				</a>
			</li>
			
			
			<?php
				$status = 0;
				$count = 0;
				if( isset( $data['work_history'] ) && is_array( $data['work_history'] ) && ! empty( $data['work_history'] ) )$status = 1;
			?>
			<li>
				<a data-toggle="tab" href="#tab_3" <?php if( ! $status )echo ' style="color:#cc3c3b;" '; ?> function-id="1" class="custom-action-button" function-name="add_view_work_history" function-class="work_history">
					<i id="work_history-add_view_work_history" class="<?php if( $status )echo "icon-check"; else echo "icon-warning-sign"; ?> registration-status"></i> 5. Work History
				</a>
			</li>
			
			<?php
				$status = 0;
				$count = 0;
				$keys = array( "nysc_certificate", "birth_certificate" );
				foreach( $keys as $key ){
					if( isset( $data["user_details"][ $key ] ) && $data["user_details"][ $key ] ){
						++$count;
					}
				}
				if( $count == count($keys) )$status = 1;
			?>
			<li>
				<a data-toggle="tab" href="#tab_3" <?php if( ! $status )echo ' style="color:#cc3c3b;" '; ?> function-id="1" class="custom-action-button" function-name="online_registration_document_form" function-class="site_users">
					<i id="site_users-online_registration_document_form" class="<?php if( $status )echo "icon-check"; else echo "icon-warning-sign"; ?> registration-status"></i> 6. Upload Registration Documents
				</a>
			</li>
			
			<?php
				$status = 0;
			?>
			<li>
				<a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="online_registration_other_info_form" function-class="site_users">
					<i class="icon-check registration-status"></i> 9. Any Other Information
				</a>
			</li>
			
			<?php
				$status = 0;
				$keys = array( "attestation" );
				foreach( $keys as $key ){
					if( isset( $data["user_details"][ $key ] ) && $data["user_details"][ $key ] == "yes" )$status = 1;
				}
			?>
			<li>
				<a data-toggle="tab" href="#tab_3" <?php if( ! $status )echo ' style="color:#cc3c3b;" '; ?> function-id="1" class="custom-action-button" function-name="online_registration_attestation_form" function-class="site_users">
					<i id="site_users-online_registration_attestation_form" class="<?php if( $status )echo "icon-check"; else echo "icon-warning-sign"; ?> registration-status"></i> 8. Attestation
				</a>
			</li>
			
			<?php
				$status = 0;
				$keys = array( "recommender_status" );
				foreach( $keys as $key ){
					if( isset( $data["user_details"][ $key ] ) && $data["user_details"][ $key ] )$status = 1;
				}
			?>
			<li>
				<a data-toggle="tab" href="#tab_3" <?php if( ! $status )echo ' style="color:#cc3c3b;" '; ?> function-id="1" class="custom-action-button" function-name="online_registration_recommendation_form" function-class="site_users">
					<i class="<?php if( $status )echo "icon-check"; else echo "icon-warning-sign"; ?> registration-status"></i> 9. Recommendation
				</a>
			</li>
			
			<?php
				$status = 0;
				$key = "registration_status";
				if( isset( $data["user_details"][ $key ] ) ){
					switch( $data["user_details"][ $key ] ){
					case 'processed':
					case 'approved':
					case 'registered_member':
					case 'retired':
					case 'payment_confirmed':
					case 'pending_renewal':
					case 'declined':
						$status = 1;
					break;
					default:
					break;
					}
				}
			?>
			<li>
				<a data-toggle="tab" href="#tab_3" <?php if( ! $status )echo ' style="color:#cc3c3b;" '; ?> function-id="1" class="custom-action-button" function-name="online_registration_payment_form" function-class="site_users">
					<i class="<?php if( $status )echo "icon-check"; else echo "icon-warning-sign"; ?> registration-status"></i> 10. Payment
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