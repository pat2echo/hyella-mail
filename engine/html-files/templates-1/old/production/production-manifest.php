<!-- BEGIN PAGE CONTAINER -->  
<div class="page-container" id="manifest-<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>">
<?php
	include dirname( dirname( __FILE__ ) ) . "/globals/invoice-css.php"; 
?>
<!-- BEGIN CONTAINER -->   
	<div class="container" id="invoice-container">
		
		<!-- BEGIN ABOUT INFO -->   
		<div class="invoice">
		<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
		<?php //print_r($data['event']); ?>
		
		<?php 
			$backend = 0;
			if( isset( $data["backend"] ) && $data["backend"] )
				$backend = $data["backend"];
			
			$show_buttons = 1;
			if( isset( $data["hide_buttons"] ) && $data["hide_buttons"] )
				$show_buttons = 0;
			
			$materials_used = 0;
			if( isset( $data["materials_used"] ) && $data["materials_used"] ){
				$materials_used = $data["materials_used"];
			}
			$total_cost_label = 'TOTAL COST OF RAW MATERIALS';	
			
			$show_reject_button = 0;
			
			switch( $materials_used ){ 
			case 1:
			case 3:
				$show_reject_button = "Delete";
			break;
			case 2:
				if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] == $data['event']["factory"] ){
					$show_reject_button = "Reject Transferred Items";
				}
				if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] == $data['event']["store"] ){
					$show_buttons = 0;
				}
			break;
			}
			
			$pr = get_project_data();
			
			$support_line = "";
			if( isset( $pr['support_line'] ) )$support_line = $pr['support_line'];
			
			$support_email = "";
			if( isset( $pr['support_email'] ) )$support_email = $pr['support_email'];
			
			$support_addr = "";
			if( isset( $pr['street_address'] ) )$support_addr = $pr['street_address'] . " " . $pr['city'] ." ". $pr['state'];
			
			$store_name = "";
			$branch = "";
			$store = array();
			if( isset( $data['event']["store"] ) && $data['event']["store"] ){
				$store = get_store_details( array( "id" => $data['event']["store"] ) );
				
				if( isset( $store["phone"] ) ){
					//test for sub location
					if( $store["name"] != "." ){ 
						$store1 = get_store_details( array( "id" => $store["name"] ) );
						if( isset( $store1["phone"] ) ){
							$branch = $store["address"];
							$store = $store1;
						}
					}
					$store_name = $store["name"];
					$support_line = $store["phone"];
					$support_addr = $store["address"];
					$support_email = $store["email"];
					$support_msg = $store["comment"];
					
					if( $store_name == "." ){ $store_name = " "; }
				}
			}
			
			$st = get_stores();
			$source_name = ( isset( $st[ $data['event']["store"] ] )?$st[ $data['event']["store"] ]:"" );
			$factory_name = ( isset( $st[ $data['event']["factory"] ] )?$st[ $data['event']["factory"] ]:"" );
			
			$unit_type_text = "Quantity";
		?>
		
		<div class="row invoice-logo">
		   <div class="col-xs-5 invoice-logo-space"><br />
			<?php 
				if( $store_name ){
					?><span class="store-name"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php echo $store_name; ?></span><?php 
				}else{
			?>
			<img src="<?php echo $pr["domain_name"]."frontend-assets/img/logo_blue.png"; ?>" style="max-height:50px;" />
			<?php } ?>
		   </div>
		   <div class="col-xs-7">
			  <p>#<small><?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?> / <?php $key = "creation_date"; if( isset( $data["event"][$key] ) )echo date("j M Y", doubleval( $data["event"][$key] ) ); ?></small> <span class="muted"><small><?php  echo $support_addr; ?></small></span></p>
		   </div>
		</div>
		<hr>
		<div class="row">
		   <div class="col-xs-5">
			  <h4>Status:</h4>
			  <h4><strong><?php $key = "status"; if( isset( $data["event"][$key] ) )echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_stock_status" ) ); ?></strong></h4>
			  <ul class="list-unstyled">			  
			 <?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
			 <li><h5 style="margin-bottom:0px; margin-top:10px;">Comment:</h5> <i><?php echo $data["event"][$key]; ?></i></li>
			<?php } ?>
			</ul>
		   </div>
		   <div class="col-xs-7 invoice-payment">
		   <div class="well">
			  <span><?php if( $branch ){ echo $branch; }else{  if( isset( $pr['company_name'] ) )echo $pr['company_name']; } ?></span>
			  <ul class="list-unstyled">
				 <li><strong>Production REF:</strong> #<?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>-<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?></li>
			  </ul>
			  
			  <?php if( $show_buttons ){ ?>
			  <div class="btn-group btn-group-justified">
				<?php if( ! $show_reject_button ){ ?>
				<a class="btn btn-sm red custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=production&todo=update_production_status" href="#">Update Status</a>
				<?php } ?>
				<a class="btn btn-sm dark default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=production&todo=delete_production_manifest" href="#"><i class="icon-trash"></i> <?php if( $show_reject_button )echo $show_reject_button; else echo "Delete"; ?></a>
			 </div>
			  <?php } ?>
			  
			</div>
		   </div>
		</div>
		<div class="row">
		   <div class="col-xs-12">
			<?php 
				switch( $materials_used ){ 
				case 1:
					$total_cost_label = 'TOTAL COST OF RAW MATERIALS';
					?><h4>Materials Issued for Utilization</h4><?php 
				break;
				case 2:
					$total_cost_label = 'TOTAL ITEMS TRANSFERED';
					?><h4>Materials Transfered to <?php echo $factory_name; ?></h4><?php 
				break;
				case 3:
					$total_cost_label = 'TOTAL ITEMS DAMAGED';
					?><h4>Damaged Materials</h4><?php 
				break;
				default:
					?><h4>Materials Used for Production</h4><?php 
				break;
				} 
			?>
			
			  <hr />
			  <table class="table table-striped table-hover">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Item</th>
					   <th style="text-align:right;" class="hidden-480">Unit Cost</th>
					   <th style="text-align:right;" class="hidden-480"><?php echo $unit_type_text; ?></th>
					   <th style="text-align:right;">Total</th>
					</tr>
				 </thead>
				 <tbody>
					<?php 
					$total = 0;
					$serial = 0;
					
					$total_cost_e = 0;
					$total_cost_m = 0;
					$total_items_m = 0;
					$extra_cost = 0;
					
					if( isset( $data["materials"] ) && is_array( $data["materials"] ) && ! empty( $data["materials"] ) ){
						foreach( $data["materials"] as $items ){
							++$serial;
							
							$price = $items["cost"];
							$q = $items["quantity"];
							
							$item_details = get_items_details( array( "id" => $items["item_id"] ) );
							$title = "";
							if( isset( $item_details["description"] ) ){
								$title = $item_details["description"];
							}
							
							$total_cost_m += ( $price * $q );
							$total_items_m += $q;
							?>
							<tr>
							   <td><?php echo $serial; ?></td>
							   <td><?php echo $title; ?></td>
							   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
							   <td align="right" class="hidden-480"><?php echo $q; ?></td>
							   <td align="right"><?php echo format_and_convert_numbers( $price * $q, 4 ); ?></td>
							</tr>
							<?php
						}
						
						$key = "extra_cost"; 
						if( isset( $data["event"][$key] ) )$extra_cost = $data["event"][$key];
						?>
						<tr>
						   <td colspan="3"><strong><?php echo $total_cost_label; ?></strong></td>
						   <td align="right" class="hidden-480"><strong><?php echo format_and_convert_numbers( $total_items_m , 3 ); ?></strong></td>
						   <td align="right"><strong><?php echo format_and_convert_numbers( $total_cost_m , 4 ); ?></strong></td>
						</tr>
						<tr>
							<td colspan="5">&nbsp;</td>
						</tr>
					<?php
					}
					
					?>
					
					<?php
					if( isset( $data["expenses"] ) && is_array( $data["expenses"] ) && ! empty( $data["expenses"] ) ){
						foreach( $data["expenses"] as $items ){
							++$serial;
							
							$price = $items["amount_due"];
							
							$title = "";
							if( isset( $items["description"] ) ){
								$title = $items["description"];
							}
							
							$total_cost_e += ( $price );
							?>
							<tr>
							   <td><?php echo $serial; ?></td>
							   <td><?php echo $title; ?></td>
							   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
							   <td align="right" class="hidden-480"></td>
							   <td align="right"><?php echo format_and_convert_numbers( $price , 4 ); ?></td>
							</tr>
							<?php
						}
						$extra_cost = $total_cost_e;
						?>
						<tr>
						   <td colspan="4"><strong>TOTAL EXTRA COST</strong></td>
						   <td align="right"><strong><?php echo format_and_convert_numbers( $extra_cost , 4 ); ?></strong></td>
						</tr>
						<tr>
							<td colspan="5">&nbsp;</td>
						</tr>
					<?php
					}
					
					?>
					
					<?php if( ! $materials_used ){ ?>
					<tr>
					   <td colspan="4"><strong>TOTAL COST OF PRODUCTION</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total_cost_m + $extra_cost , 4 ); ?></strong></td>
					</tr>						
					<?php } ?>
					
				 </tbody>
			  </table>
		   </div>
		</div>
		<?php if( ! $materials_used ){ ?>
		<div class="row">
		   <div class="col-xs-12">
			  <h4>Goods Produced</h4>
			  <hr />
			  <table class="table table-striped table-hover">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Item</th>
					   <th style="text-align:right;" class="hidden-480">Unit Selling Price</th>
					   <th style="text-align:right;" class="hidden-480"><?php echo $unit_type_text; ?></th>
					   <th style="text-align:right;">Total</th>
					</tr>
				 </thead>
				 <tbody>
					<?php 
					$total = 0;
					$serial = 0;
					$total_cost_g = 0;
					$total_items_g = 0;
					
					if( isset( $data["produced_items"] ) && is_array( $data["produced_items"] ) && ! empty( $data["produced_items"] ) ){
						$key = "status"; 
						$status = "";
						if( isset( $data["event"][$key] ) ){
							$status = $data["event"][$key];
						}
						
						$quantity_key = "quantity_expected";
						
						switch( $status ){
						case 'materials-transfer':
						case 'complete':
							$quantity_key = "quantity";
						break;
						}
						
						foreach( $data["produced_items"] as $items ){
							++$serial;
							
							$price = $items["selling_price"];
							$q = $items[ $quantity_key ];
							
							$item_details = get_items_details( array( "id" => $items["item"] ) );
							$title = "";
							if( isset( $item_details["description"] ) ){
								$title = $item_details["description"];
							}
							
							$total_cost_g += ( $price * $q );
							$total_items_g += $q;
							
							$total += ( $price * $q );
							?>
							<tr>
							   <td><?php echo $serial; ?></td>
							   <td><?php echo $title; ?></td>
							   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
							   <td align="right" class="hidden-480"><?php echo $q; ?></td>
							   <td align="right"><?php echo format_and_convert_numbers( $price * $q, 4 ); ?></td>
							</tr>
							<?php
						}
						?>
						<tr>
							<td colspan="5">&nbsp;</td>
						</tr>
						<tr>
						   <td colspan="3"><strong>ESTIMATED REVENUE</strong></td>
						   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $total_items_g , 3 ); ?></td>
						   <td align="right"><strong><?php echo format_and_convert_numbers( $total_cost_g , 4 ); ?></strong></td>
						</tr>
						<tr>
						   <td colspan="4"><strong>COST PER GOODS PRODUCED</strong></td>
						   <td align="right"><strong><?php if( $total_items_g )echo format_and_convert_numbers( ( $total_cost_m + $extra_cost ) / $total_items_g  , 4 ); ?></strong></td>
						</tr>
						<?php
					}
					
					?>
					
				 </tbody>
			  </table>
		   </div>
		</div>
		<?php } ?>
			
		<div class="row">
			<div class="col-xs-6">
				<ul class="list-unstyled amounts">
				<?php $key = "modified_by"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li style=""><strong>issued by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
				 <?php } ?>
				  <?php if( $materials_used == 2 ){ ?>
				 <li style=""><strong>source:</strong> <?php echo $source_name; ?></li>
				 <?php } ?>
				</ul>
			</div>
			<div class="col-xs-6" style="text-align:right;">
				<ul class="list-unstyled amounts">
				<?php $key = "staff_responsible"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li style=""><strong>received by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
				 <?php } ?>
				 <?php if( $materials_used == 2 ){ ?>
				 <li style=""><strong>destination:</strong> <?php echo $factory_name; ?></li>
				 <?php } ?>
				</ul>
			</div>
		</div>
		<div class="row">
		  
		   <div class="col-xs-8">
			   <?php if( ! $materials_used ){ ?>
			  <div class="note note-success" style="background:#C8F198;">
				<h4>Gross Profit: <strong class="pull-right"><?php echo convert_currency( ( $total_cost_g - ( $total_cost_m + $extra_cost ) ) , 4 ); ?></strong></h4>
				<br />
				<h4>Gross Margin: <strong class="pull-right"><?php if( $total_cost_g )echo format_and_convert_numbers( 100 * ( $total_cost_g - ( $total_cost_m + $extra_cost ) ) / $total_cost_g  , 4 )."%"; ?></strong>
				</h4>
			 </div>
		   <?php } ?>
		   </div>
		   
		   <div class="col-xs-4 invoice-block">
				
			  <?php if( ! $backend ){ ?>
			  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print Invoice <i class="icon-print"></i></a>
			  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
			  <?php }else{ ?>
			  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-manifest&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue hidden-print">Print Preview <i class="icon-print"></i></a><br /><br /><br /><br />
			  <?php } ?>
		   </div>
		</div>
		
		
		<?php }else{ ?>
		Error Message
		<?php } ?>
	 </div>
		<!-- END ABOUT INFO -->   
		
	</div>
	<!-- END CONTAINER -->

</div>
<!-- END PAGE CONTAINER -->  