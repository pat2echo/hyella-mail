<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<p>&nbsp;</p>
<div class="container">	
<div class="row">
	<div class="col-md-2">
	
		<ul class="ver-inline-menu margin-bottom-10" style="margin-top:45px;">
			<?php
			$role = "";
			$cus = 0;
			
			$default_logo = "assets/img/profile/main-logo.png";
			$u = array();
			if( isset( $user_info["user_id"] ) && $user_info["user_id"] ){
				$u = get_users_details( array( "id" => $user_info["user_id"] ) );
				$key = "photograph"; 
				if( isset( $u[$key] ) ){
					$default_logo = $u[$key]; 
				}
				$key = "role"; 
				if( isset( $u[$key] ) ){
					$role = $u[$key]; 
				}
			}
				?>
			<li id="profile-img-passport"><img src="<?php echo $display_pagepointer; ?><?php 
				
				echo $default_logo;
			?>" class="img-responsive" alt="" > 
				  </li>
				  
		  <li class="active">
			 <a href="?page=user-dashboard">
			 <i class="icon-dashboard"></i> <small>Dashboard</small>
			 <span class="after"></span>
			 </a>
		  </li>
		 
		 <?php
			$status = 0;
			$key = "registration_status";
			if( isset( $data['applicant_data'][ $key ] ) ){
				switch( $data['applicant_data'][ $key ] ){
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
			
			<li><a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="online_registration_form" function-class="site_users"><i class="icon-plus"></i> <small>Online Registration</small></a></li>
			
			<?php if( $status ){ ?>
			<li><a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="update_view_membership_renewal" function-class="membership_renewal"><i class="icon-plus"></i> <small>Membership Renewal</small></a></li>
			
			<li><a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="update_view_induction_fee" function-class="payment_type"><i class="icon-plus"></i> <small>Pay Induction Fee</small></a></li>
			
			<li><a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="update_view_training_workshop_fee" function-class="payment_type"><i class="icon-plus"></i> <small>Pay Training / Workshop Fee</small></a></li>
		  
			<li><a data-toggle="tab" href="#tab_3" function-id="1" class="custom-action-button" function-name="update_bio_data_form" function-class="site_users"><i class="icon-plus"></i> <small>Update Profile</small></a></li>
			<?php } ?>
			<li><a href="?action=signout" ><i class="icon-power-off"></i> <small>Sign Out</small></a></li>
			
		</ul>       
	
	</div>
   <div class="col-md-10" id="dash-board-main-content-area">
		<?php if( isset( $data["content"] ) && $data["content"] ){
			echo $data["content"];
		}else{
		?>
	  <div class="row">
		 <div class="col-md-8 profile-info">
			<a href="#" class="btn btn-sm default pull-right custom-action-button" function-id="1" function-name="display_my_profile_manager" function-class="site_users" >
			 <i class="icon-edit"></i> Change Password
			 </a> 
			<h1><?php if( ( isset( $user_info["user_full_name"] ) && $user_info["user_full_name"] ) )echo $user_info["user_full_name"]; ?></h1>
			<p>LRCN Registration Number: <strong><?php  if( isset( $data['applicant_data']["registration_number"] ) && doubleval( $data['applicant_data']["registration_number"] ) )echo $data['applicant_data']["registration_number"]; ?></strong></p>
			
			<p><a href="mailto:<?php $key = "email"; if( isset( $u[$key] ) )echo $u[$key]; ?>"><?php $key = "email"; if( isset( $u[$key] ) )echo $u[$key]; ?></a>, <a href="tel:<?php $key = "phonenumber"; if( isset( $u[$key] ) )echo $u[$key]; ?>"><?php $key = "phonenumber"; if( isset( $u[$key] ) )echo $u[$key]; ?></a><br /><?php $key = "street_address"; if( isset( $u[$key] ) )echo $u[$key]; ?></p>
			<style type="text/css">
			.form-control-table table thead tr th,
			.form-control-table table{
				font-size:0.9em;
			}
			.form-control-table table tr th{
				background-color:#CCFFD2;/*#f4ccFF*/
				font-weight:bold;
			}
			th.limit,
			td.limit{
				display:none;
			}
			
		</style>
			<br />
			<br />
			<div class="form-control-table">
			<?php if( isset( $data["content1"] ) && $data["content1"] )
				echo $data["content1"]; ?>
			</div>
		 </div>
		 <!--end col-md-8-->
		 <div class="col-md-4">
			<div class="portlet sale-summary">
			   <div class="portlet-title">
				  <h5 style="margin-top:0; font-weight:bold; border-bottom:1px solid #ddd; padding-bottom:10px;">Important</h5>
				  <div class="tools">
					 <a class="reload" href="javascript:;"></a>
				  </div>
			   </div>
			   <div class="portlet-body">
				  <ul class="list-unstyled">
					 <li>
						<span class="sale-info">MEMBERSHIP STATUS<i class="icon-img-up"></i></span> 
						<span class="sale-num"><?php if( isset( $data['applicant_data']["registration_status"] ) )echo strtoupper( get_select_option_value( array( "id" => $data['applicant_data']["registration_status"], "function_name" => "get_registration_status" ) ) ); ?></span>
					 </li>
					 <li>
						<span class="sale-info">MEMBERSHIP EXPIRY DATE<i class="icon-img-up"></i></span> 
						<span class="sale-num"><?php if( isset( $data['applicant_data']["registration_date"] ) && doubleval( $data['applicant_data']["registration_date"] ) )echo date("d-M-Y", doubleval( $data['applicant_data']["registration_date"] ) + (3600*24*365) ); ?></span>
					 </li>
				  </ul>
			   </div>
			</div>
		 </div>
		 <!--end col-md-4-->
	  </div>
	  <?php } ?>
  </div>
</div>
</div>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>