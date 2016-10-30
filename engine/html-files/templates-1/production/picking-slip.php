<!-- BEGIN PAGE CONTAINER -->  
<div class="page-container" id="picking-slip-<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>">
<?php
	include dirname( dirname( __FILE__ ) ) . "/globals/invoice-css.php"; 
	
	$backend = 0;
	if( isset( $data["backend"] ) && $data["backend"] )
		$backend = $data["backend"];
	
	$print_style = "";
	if( ! $backend ){
		$print_style = ' style="max-width:100% !important; width:100% !important; min-width:100% !important;" ';
	}
?>
<div class="container" id="invoice-container" <?php echo $print_style; ?>>
<div class="invoice" <?php echo $print_style; ?>>
<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
	<?php 
		$pr = get_project_data();
		
		$html = ___picking_slip( $data );
		
		if( ! $backend ){
			$number_of_picking_slips = get_number_of_picking_slip_settings();
	?>
	<div class="row">
		<?php if( $number_of_picking_slips == 2 ){ ?>
		<div class="col-xs-6">
			<strong>Account Department</strong>
			<div style="border-bottom:3px solid #00cc00;"></div>
			<?php echo $html; ?>
		</div>
		<div class="col-xs-6">
			<strong>Gate House</strong>
			<div style="border-bottom:3px solid #6666ff;"></div>
			<?php echo $html; ?>
		</div>	
		<?php }else{ ?>
		<div class="col-xs-4">
			<strong>Account Department</strong>
			<div style="border-bottom:3px solid #00cc00;"></div>
			<?php echo $html; ?>
		</div>
		<div class="col-xs-4">
			
			<strong>Sales Point</strong>
			<div style="border-bottom:3px solid #ff8888;"></div>
			<?php echo $html; ?>
		</div>
		<div class="col-xs-4">
			<strong>Gate House</strong>
			<div style="border-bottom:3px solid #6666ff;"></div>
			<?php echo $html; ?>
		</div>
		<?php } ?>
	</div>
	<?php 
		}else{
			echo $html;
		}
	?>
	<div class="row">
		<div class="col-xs-12">
			<br />
			<?php if( ! $backend ){ ?>
			  <a class="btn pull-right blue hidden-print" onclick="javascript:window.print();">Print Invoice <i class="icon-print"></i></a>
			  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
			<?php }else{ ?>
			  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-manifest&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue pull-right hidden-print">Print Preview <i class="icon-print"></i></a><br /><br /><br /><br />
			<?php } ?>
		</div>
	</div>
</div>
</div>
			
<?php }else{ ?>
	Error Message
<?php } ?>
</div>
<!-- END PAGE CONTAINER --> 
<?php 
	function ___picking_slip( $data ){
		ob_start();
		
		$backend = 0;
		if( isset( $data["backend"] ) && $data["backend"] )
			$backend = $data["backend"];
		
		$show_buttons = 1;
		if( isset( $data["hide_buttons"] ) && $data["hide_buttons"] )
			$show_buttons = 0;
		
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
		
		$show_image = 0;
		if( isset( $data["show_item_image"] ) && $data["show_item_image"] )
			$show_image = $data["show_item_image"];
		
		$st = get_stores();
		$source_name = ( isset( $st[ $data['event']["store"] ] )?$st[ $data['event']["store"] ]:"" );
		$factory_name = ( isset( $st[ $data['event']["factory"] ] )?$st[ $data['event']["factory"] ]:"" );
		
		$e = $data["event"];
		
		$sales_info = get_sales_details( array( "id" => $e["reference"] ) );
		
		$show_signature = 0;
		if( ! $backend ){
			$show_signature = get_show_signature_in_picking_slip_settings();
		}
		?>
		<h5><strong><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php echo $store_name; ?> - Picking Slips</strong></h5>
		<hr />
		<div class="row">
		   <div class="col-xs-6">
			  <p>Invoice Number: <strong>#<?php echo isset( $sales_info["serial_num"] )?( mask_serial_number( $sales_info["serial_num"], 'S' ) ):""; ?></strong></p>
			  <p style="margin-bottom:0px; margin-top:10px;">Customer: <strong><?php $key = "customer"; if( isset( $e[$key] ) )echo get_select_option_value( array( "id" => $e[$key], "function_name" => "get_customers" ) ); ?></strong></p>
			</ul>
		   </div>
		   <div class="col-xs-6 invoice-payment">
		   <div class="well" style="padding:10px;">
			<p><strong>Date:</strong> <?php echo date("d-M-Y", doubleval( $e["date"] ) ); ?></p>
			  <ul class="list-unstyled">
				 <li><strong>Picking Slip No:</strong> #<?php echo mask_serial_number( $e["serial_num"], 'IN' ); ?></li>
				 <li><strong>Raised By:</strong> <?php $key = "created_by"; if( isset( $e[$key] ) )echo get_select_option_value( array( "id" => $e[$key], "function_name" => "get_employees" ) ); ?></li>
			  </ul>
			</div>
		   </div>
	  </div>
	  <div class="row">
		  <div class="col-xs-12">
			<div class="shopping-cart-table">
			<table class="table table-striped table-hover">
			 <thead>
				<tr>
				   <th>#</th>
				   <th>Item</th>
				   <th style="text-align:right;">Quantity Issued</th>
				</tr>
			 </thead>
			 
			 <tbody>
				 <?php
					if( isset( $data["materials"] ) && is_array( $data["materials"] ) && ! empty( $data["materials"] ) ){
						$serial = 0;
						$total = 0;
						
						foreach( $data["materials"] as $items ){
							++$serial;
							$q = $items["quantity"];
							$q1 = $q;
							
							$item_details = get_items_details( array( "id" => $items["item_id"] ) );
							
							$title = "";
							$title1 = "";
							if( isset( $item_details["description"] ) ){
								$title1 = $item_details["description"];
								if( $show_image ){
									$title = '<a href="#" class="custom-single-selected-record-button" override-selected-record="' . $item_details["id"] . '" action="?module=&action=items&todo=view_item_details" title="View Details"><img src="' . $pr["domain_name"] . $item_details["image"] . '" width="40" align="left" style="margin-right:5px; border:1px solid #aaa;" />';
								}
								
								$title .= $item_details["description"] . "<br /><strong><small>#" . $item_details["barcode"] . "</small></strong>";
								
								if( $show_image ){
									$title .= '</a>';
								}
							}
							$total += $q;
							?>
							<tr>
							   <td><?php echo $serial; ?></td>
							   <td><?php echo $title; ?></td>
							   <td align="right"><?php echo $q; ?></td>
							</tr>
							<?php
						}
						?>
						<tr>
						   <td colspan="2"><strong>TOTAL</strong></td>
						   <td align="right"><strong><?php echo format_and_convert_numbers( $total , 4 ); ?></strong></td>
						</tr>
						<?php
					}			
				 ?>
			 </tbody>
			 </table>
		  </div>
		  </div>
	  </div>
	  <div class="row">
		<div class="col-xs-6">
			<label><small><strong>Picker</strong></small></label><br />
			<?php echo $e["comment"]; ?>
			
			 <?php if( $show_signature ){ ?>
			 <br /><label><small>Signature: _______________</small></label>
			 <?php } ?>
		</div>
		<div class="col-xs-6">
			<label><small><strong>Supervisor</strong></small></label><br />
			<?php $key = "staff_responsible"; if( isset( $e[$key] ) )echo get_select_option_value( array( "id" => $e[$key], "function_name" => "get_employees" ) ); ?>
			
			 <?php if( $show_signature ){ ?>
			 <br /><label><small>Signature: _______________</small></label>
			 <?php } ?>
		</div>
	</div>
		<?php
		$html_file_content = ob_get_contents();
		ob_end_clean();
		return iconv( "UTF-8", "ASCII//IGNORE", $html_file_content );
	}
?>