<?php //if( file_exists( dirname( dirname( dirname( __FILE__ ) ) ).'/globals/breadcrum.php' ) )include dirname( dirname( dirname( __FILE__ ) ) ).'/globals/breadcrum.php'; ?>

<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<div class="container">
	<div class="row">
	<div class="col-md-12">
		<h4>Application Information</h4>
		<hr />
		<?php 
			$applicant_fields = array();
			if( isset( $data['applicant_fields'] ) && $data['applicant_fields'] && is_array( $data['applicant_fields'] )  ){ 
				$applicant_fields = $data['applicant_fields'];
			}
			if( isset( $data['applicant_data'] ) && $data['applicant_data'] && is_array( $data['applicant_data'] )  ){ 
				$fields = $data['applicant_data'];
				
				foreach( $fields as $k => $v )
					//unset( $data['applicant_data'][$k] );
				?>
				<table class="table table-striped table-bordered table-hover">
				<tbody>
				<?php
				$keep = 0;
				if( isset( $_SESSION["admin_page"] ) ){
					$keep = $_SESSION["admin_page"];
					unset($_SESSION["admin_page"]);
				}
				
				foreach( $data['applicant_data'] as $key => $val ){
					switch( $key ){
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
	</div>
</div>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>