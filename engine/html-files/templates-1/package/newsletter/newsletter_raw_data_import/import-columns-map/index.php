<!--EXCEL IMPOT FORM-->
Please Map Columns for Data Extraction & Click Continue
<div style="margin-top:10px;">
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<div class="row">
	<div class="col-md-2"> 
		<div class="portlet purple box">
			<div class="portlet-title">
				<div class="caption"><small style="font-size:0.65em;">Columns / Fields Map</small></div>
			</div>
			<div class="portlet-body" id="form-content-area">
			<?php
			if( isset( $data['data_entry_form'] ) && $data['data_entry_form'] ){
				echo $data['data_entry_form'];
			}
			?>
			</div>
		</div>
	</div>
	<div class="col-md-10"> 
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption"><i class="icon-bell"></i>Select Columns to Map</div>
				<div class="tools">
				<a href="" class="collapse" ></a>
				<a href="#portlet-config" data-toggle="modal" class="config"></a>
				<a href="" class="reload"></a>
				<a href="" class="remove"></a>
				</div>
			</div>
			<div class="portlet-body">
			<?php
			if( isset( $data['html_data'] ) && $data['html_data'] ){
				$count = 1;
				?>
				<div style="width:100%; height:450px; overflow:auto;">
				<table class="table table-striped table-bordered table-hover" id="mapping-table">
				
				<?php
				foreach( $data['html_data'] as $record_id => $v ){
					if( $count == 1 ){
					?>
					<thead>
					   <tr>
					   <th class="table-checkbox" style="min-width:35px;">#</th>
					   <?php 
							foreach( $v as $kk => $vv ){ 
								switch( $kk ){
								case "serial_num":
								case "id":
								case "reference_id":
								break;
								default:
					   ?>
								<th class="content col-<?php echo $kk; ?>"><div class="col-label"></div><label><input class="radio import-col import-col-<?php echo $kk; ?>" value="<?php echo $kk; ?>" type="radio" name="column_field" > Col <?php echo $kk; ?></label></th>
					   <?php
								break;
								}
							} 
						?>
					   </tr>
					</thead>
					<tbody>
					<?php
					}
					?>
					<tr>
					   <td><label><input name="starting_row" type="radio" class="radio import-row-<?php echo $count; ?>" value="<?php echo $count; ?>" /> <?php echo $count; ?></label></td>
					   <?php 
						foreach( $v as $kk => $vv ){ 
							switch( $kk ){
							case "serial_num":
							case "id":
							case "reference_id":
							break;
							default:
					   ?>
						  <td class="col-<?php echo $kk; ?>"><?php echo $vv; ?></th>
					   <?php
							break;
							}
						} 
					   ?>
					</tr>
					<?php
					++$count;
				}
				?>
				</tbody></table>
				</div>
				<?php
			}
			?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>