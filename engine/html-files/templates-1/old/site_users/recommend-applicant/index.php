<?php //if( file_exists( dirname( dirname( dirname( __FILE__ ) ) ).'/globals/breadcrum.php' ) )include dirname( dirname( dirname( __FILE__ ) ) ).'/globals/breadcrum.php'; ?>

<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<div class="container">
	<div class="row">
	<div class="col-md-7">
		<br />
		<br />
		<br />
		<br />
		<br />
		<h4>Applicant Information for Recommendation</h4>
		<hr />
		<?php 
			$applicant_fields = array();
			if( isset( $data['applicant_fields'] ) && $data['applicant_fields'] && is_array( $data['applicant_fields'] )  ){ 
				$applicant_fields = $data['applicant_fields'];
			}
			if( isset( $data['applicant_data'] ) && $data['applicant_data'] && is_array( $data['applicant_data'] )  ){ 
				$fields = $data['applicant_data'];
				
				unset( $fields["additional_information"] );
				unset( $fields["attestation"] );
				unset( $fields["photograph"] );
				
				unset( $fields["birth_certificate"] );
				unset( $fields["change_of_name"] );
				unset( $fields["nysc_certificate"] );
				unset( $fields["other_documents"] );
				unset( $fields["office_address"] );
				unset( $fields["office_email"] );
				unset( $fields["office_phone"] );
				unset( $fields["employer"] );
				unset( $fields["employer_address"] );
				unset( $fields["employer_date_of_employment"] );
				unset( $fields["employer_status"] );
				unset( $fields["employer_salary"] );
				unset( $fields["email"] );
				unset( $fields["title"] );
				unset( $fields["firstname"] );
				unset( $fields["lastname"] );
				unset( $fields["previous_names"] );
				unset( $fields["phonenumber"] );
				unset( $fields["birth_day"] );
				unset( $fields["sex"] );
				unset( $fields["country"] );
				unset( $fields["state"] );
				unset( $fields["city"] );
				unset( $fields["street_address"] );
				
				foreach( $fields as $k => $v )
					unset( $data['applicant_data'][$k] );
				?>
				<table class="table table-striped table-bordered table-hover">
				<tbody>
				<?php
				$keep = 0;
				if( isset( $_SESSION["admin_page"] ) ){
					$keep = $_SESSION["admin_page"];
					unset($_SESSION["admin_page"]);
				}
				?>
					<tr>
						<td>
							<?php echo get_uploaded_files( $data['pagepointer'] , $data['applicant_data'][ "photograph" ] , $data['applicant_data'][ "firstname" ] . " " . $data['applicant_data'][ "lastname" ] ); ?>
						</td>
						<td>
							<?php echo $data['applicant_data'][ "title" ] . " " . $data['applicant_data'][ "firstname" ] . " " . $data['applicant_data'][ "lastname" ] ; ?>
						</td>
					</tr>
					<?php
				foreach( $data['applicant_data'] as $key => $val ){
					switch( $key ){
					case "firstname":
					case "title":
					case "lastname":
					case "photograph":	
					break;
					case "state":
					?>
					<tr>
						<td><?php if( isset( $applicant_fields[$key]["field_label"] ) )echo $applicant_fields[$key]["field_label"]; else echo $key; ?></td>
						<td><?php 
							echo get_state_name( array( 'country_id' => $data['applicant_data']["country"], 'state_id' => $val ) );
						?></td>
					</tr>
					<?php
					break;
					case "city":
					?>
					<tr>
						<td><?php if( isset( $applicant_fields[$key]["field_label"] ) )echo $applicant_fields[$key]["field_label"]; else echo $key; ?></td>
						<td><?php 
							echo get_city_name( array( 'city_id' => $val, 'state_id' => $data['applicant_data']["state"] ) );
						?></td>
					</tr>
					<?php
					break;
					default:
					?>
					<tr>
						<td><?php if( isset( $applicant_fields[$key]["field_label"] ) )echo $applicant_fields[$key]["field_label"]; else echo $key; ?></td>
						<td><?php 
							$v = $val; 
							if( isset( $applicant_fields[$key]["form_field"] ) ){
								switch( $applicant_fields[$key]["form_field"] ){
								case "file":
									$v = get_uploaded_files( $data['pagepointer'] , $val, $applicant_fields[$key]["field_label"] );
								break;
								case "date-5":
									$v = date( "d-M-Y", doubleval( $val ) );
								break;
								case "select":
									$v = get_select_option_value( array( "id" => $val, "function_name" => isset( $applicant_fields[$key]["form_field_options"] )?$applicant_fields[$key]["form_field_options"]:"" ) );
								break;
								}
							}
							echo $v; 
						?></td>
					</tr>
					<?php
					break;
					}
				}
				
				if( $keep )
					$_SESSION["admin_page"] = $keep;
				?>
				</tbody>
				</table>
				<?php
				if( isset( $data["work_history"] ) )echo $data["work_history"];
			}
		?>
	</div>
	<div class="col-md-5">
		<div style="box-shadow: 1px 11px 11px 3px #ddd; margin-top:0px; margin-bottom:30px; padding:20px;">
			 <small><strong><?php echo GLOBALS_DEFAULT_SITE_TITLE; ?> Applicants Recommendation Form</strong></small>
			 <h3 style="margin-top:0;">Electronic Recommendation &darr;</h3>
		  <!--
			 <a class="btn btn-success pull-right btn-sm" href="?page=login" title="Login to Continue Registration, Manage your Profile or Renew License">Already Registered? Login</a>
		  <div class="pull-right1">
			  <div class="radio1 pull-left" style="margin-right:30px;">
				  <label>
					  <input type="radio" name="register_type" data-control-id="#site_users009"  value="merchant" checked="">
					  Merchant
				  </label>
			  </div>
			  <div class="radio1 pull-left">
				  <label>
					  <input type="radio" name="register_type" data-control-id="#site_users009" value="customer" checked="checked">
					  Customer
				  </label>
			  </div>
		  </div>
		-->
		  <div style="clear:both;"></div>
		  <div id="registration-form-container">
			<?php if( isset( $data['html'] ) )echo $data['html']; ?>
	
			</div>
		</div>
	</div>
	</div>
</div>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>