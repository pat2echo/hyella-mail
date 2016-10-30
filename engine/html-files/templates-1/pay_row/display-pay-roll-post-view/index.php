<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	
?>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	
	<div class="col-md-10 col-md-offset-1"> 
		<!--grey-->
		<div class="portlet grey box">
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small><small>Pay Roll Summary</small></small></div>
				<?php
					if( isset( $data[ 'years' ] ) && $data[ 'years' ] ){
						$option = '<select class="pull-right" id="select-year">';
						$sel = "";
						if( isset( $data[ 'years_selected_option' ] ) && $data[ 'years_selected_option' ] ){
							$sel = $data[ 'years_selected_option' ];
						}
						foreach( $data[ 'years' ] as $k => $v ){
							$option .= '<option value="' . $k . '"';
							if( $sel == $k ){ $option .= ' selected="selected" '; }
							$option .= '>' . $v . '</option>';
						}
						$option .= '</select>';
						echo $option;
					}
				?>
			</div>
			<div class="portlet-body">
				<div class="row" >
				<?php
					$post_info = array();
					if( isset( $data['post_info'] ) && is_array( $data['post_info'] ) ){
						$post_info = $data['post_info'];
					}
					
					
					
					if( isset( $data['month_info'] ) && is_array( $data['month_info'] ) ){
						$count = count( $data['month_info'] );
						
						if( $count < 13 ){
							?>
							<div class="col-md-3">
							<div class="row" >
								<div class="col-md-11 col-md-offset-1" style="border:1px solid #ddd; padding:10px;">
								  <p class="item-title">
									New Pay Roll
								  </p><div style="clear:both; height:8px;"></div>
								  
								  <a href="#" class="btn btn-block dark custom-single-selected-record-button-old" action="?module=&action=pay_row&todo=display_all_records_full_view" override-selected-record="1" >Add New Pay Roll</a>
								  
								  <a href="#" class="btn btn-block btn-default custom-single-selected-record-button-old" action="?module=&action=pay_roll_post&todo=display_all_records_full_view" override-selected-record="1" >Manage Posting</a>
								  
								  <a href="#" class="btn btn-block btn-default custom-single-selected-record-button-old" action="?module=&action=pay_row&todo=view_pay_slip_schedule" override-selected-record="1" >Pay Slips / Schedule</a>
								  
								  <div style="clear:both; height:7px;"></div>
								</div>
							</div>
							</div>
							<?php
						}
						
						foreach( $data['month_info'] as $month => $sval ){
							
							$status = "";
							?>
							<div class="col-md-3">
							<div class="row" >
								<div class="col-md-11 col-md-offset-1" style="border:1px solid #ddd; padding:10px;">
								  <p class="item-title">
									<?php echo $month; ?>
								  </p>
								  <?php 
									foreach( $sval as $k => $v ){
										$get_variables = "";
										
										foreach( $v as $k1 => $v1 ){
											$get_variables .= "&" . $k1 . "=" . $v1;
										}
										
										$net_pay = $v["amount_paid"] - $v["amount_deducted"];
								  ?>
								  <div class="input-group">
									 <span class="input-group-addon" >Net Pay</span>
									 <span  class="input-group-addon" style="background: #A7E862;"><?php echo number_format( $net_pay , 2 ); ?></span>
									</div>
									<div style="clear:both; height:5px;"></div>
								  <div class="input-group">
									 <span class="input-group-addon" >Gross Pay</span>
									 <span  class="input-group-addon"><?php echo number_format( $v["amount_paid"] , 2 ); ?></span>
									</div>
									<div style="clear:both; height:5px;"></div>
								  <div class="input-group">
									 <span class="input-group-addon" >Deduction</span>
									 <span  class="input-group-addon" ><?php echo number_format( $v["amount_deducted"] , 2 ); ?></span>
									</div>
									<div style="clear:both; height:5px;"></div>
								  <?php
										if( isset( $post_info[ $month ][ $k ][ "amount_paid" ] ) ){
											
											if( floor( doubleval( $post_info[ $month ][ $k ][ "amount_paid" ] ) ) == floor( doubleval( $v["amount_paid"] ) ) && floor( doubleval( $post_info[ $month ][ $k ][ "amount_deducted" ] ) ) == floor( doubleval( $v["amount_deducted"] ) ) ){
												?>
												<div style="clear:both; height:7px;"></div>
												<code><strong>Posted On <?php echo date( "d-M-Y H:i" , doubleval( $post_info[ $month ][ $k ]["modification_date"] ) ); ?></strong></code>
												<div style="clear:both; height:5px;"></div>
												<?php
											}else{
												?>
												<a href="#" class="btn btn-sm btn-block green custom-single-selected-record-button-old" action="?module=&action=pay_roll_post&todo=post_pay_roll_data&date=<?php echo $v["date"]; ?>&salary_schedule=<?php echo $k; ?><?php echo $get_variables; ?>&record_id=<?php echo $post_info[ $month ][ $k ]["id"]; ?>" override-selected-record="<?php echo doubleval( $v["amount_paid"] ); ?>" mod="<?php echo doubleval( $v["amount_deducted"] ); ?>" >Re-Post <?php echo $month; ?></a>
												<?php
											}
										}else{
											?>
											<a href="#" class="btn btn-sm btn-block red custom-single-selected-record-button-old" action="?module=&action=pay_roll_post&todo=post_pay_roll_data&date=<?php echo $v["date"]; ?>&salary_schedule=<?php echo $k; ?><?php echo $get_variables; ?>" override-selected-record="<?php echo doubleval( $v["amount_paid"] ); ?>" mod="<?php echo doubleval( $v["amount_deducted"] ); ?>" >Post <?php echo $month; ?></a>
											<?php
										}
									}
								  ?>
							</div>
							</div>
							</div>
							<?php
						}
					}else{
						?>
							<div class="col-md-3">
							<div class="row" >
								<div class="col-md-11 col-md-offset-1" style="border:1px solid #ddd; padding:10px;">
								  <p class="item-title">
									New Pay Roll
								  </p>
								  <a href="#" class="btn btn-block dark">Add New Pay Roll</a>
								</div>
							</div>
							</div>
							<?php
					}
				?>
				</div>
			</div>
		</div>
		
	</div>
	
</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>