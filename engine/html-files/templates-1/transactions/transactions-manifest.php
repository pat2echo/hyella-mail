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
			
			$preview = 0;
			if( isset( $data["preview"] ) && $data["preview"] )
				$preview = $data["preview"];
			
			$pr = get_project_data();
			
			$show_buttons = 0;
			if( isset( $data["hide_buttons"] ) && $data["hide_buttons"] )
				$show_buttons = 0;
			
			
		?>
		
		  <?php if( ! $preview ){ ?>
			  <?php if( $show_buttons ){ ?>
			  <div class="btn-group hidden-print">
				<!--
				<a class="btn btn-sm default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=transactions&todo=update_transactions_status" href="#">Submit Transaction</a>
				
				<a class="btn btn-sm blue custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=transactions&todo=update_transactions_status" href="#">Post Transaction</a>
				-->
				<a class="btn btn-sm dark default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=transactions&todo=delete_transactions_manifest" href="#"><i class="icon-trash"></i> Delete</a>
			 </div><hr />
			  <?php } ?>
		  <?php } ?>
			  
		<div class="row">
		   
		   <div class="col-xs-8 invoice-payment">
		   <div class="well">
			  <ul class="list-unstyled">
				 <li><?php $key = "creation_date"; if( isset( $data["event"][$key] ) )echo date("d-M-Y", doubleval( $data["event"][$key] ) ); ?></li>
				 <li><strong><?php $key = "description"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?></strong><br /></li>
				 
				 <?php $key = "created_by"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li style=""><br /><strong>Initiated by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
				 <?php } ?>
			  </ul>
			  
			</div>
		   </div>
		   
		   <div class="col-xs-4 ">
			  <h4><small>Status:  <small><strong>POSTED</strong></small></small></h4>
			  <ul class="list-unstyled">
				 <li><strong>REF:</strong> #<?php $key = "serial_num"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>-<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?></li>
				 <?php $key = "reference_table"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li><strong>Tag:</strong> <i><?php echo $data["event"][$key]; ?></i></li>
				<?php } ?>
			  </ul>
		   </div>
			   
		</div>
		<div class="row">
		   <div class="col-xs-6">
			  <table class="table table-striped table-hover">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Debit</th>
					   <th style="text-align:right;">Amount</th>
					</tr>
				 </thead>
				 <tbody>
					<?php 
					$total = 0;
					$serial = 0;
					$total_c = 0;
					
					$category = get_items_categories();
					$customer = get_customers();
					$vendor = get_vendors();
					$payment_methods = get_payment_method();
					$payment_methods_list = get_payment_method_list();
					
					if( isset( $data["debit_and_credit"] ) && is_array( $data["debit_and_credit"] ) && ! empty( $data["debit_and_credit"] ) ){
						foreach( $data["debit_and_credit"] as $txv ){
							if( $txv['type'] != "debit" )continue;
							
							++$serial;
							$price = $txv["amount"];
							
							$title = "";
							switch( $txv["account_type"] ){
							case "cost_of_goods_sold":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "CGS: " . $category[ $txv['account'] ];
								}
							break;
							case "inventory_marketing_expense":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "MKT: " . $category[ $txv['account'] ];
								}
							break;
							case "damaged_items":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "DMG: " . $category[ $txv['account'] ];
								}
							break;
							case "inventory":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "INV: " . $category[ $txv['account'] ];
								}
							break;
							case "revenue_category":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "REV: " . $category[ $txv['account'] ];
								}
							break;
							case "accounts_receivable":
								if( isset( $customer[ $txv['account'] ] ) ){
									$title = $customer[ $txv['account'] ];
								}
							break;
							case "cash_book": 
								if( isset( $payment_methods_list[ $txv['account'] ] ) ){
									$title = $payment_methods_list[ $txv['account'] ];
								}
							break;
							case "petty_cash": case "main_cash":
							case "bank6": case "bank7": case "bank8": case "bank9": case "bank10":
							case "bank5": case "bank4": case "bank3": case "bank2": case "bank1":
								if( isset( $payment_methods[ $txv['account'] ] ) ){
									$title = $payment_methods[ $txv['account'] ];
								}
							break;
							case "account_payable":
								if( isset( $vendor[ $txv['account'] ] ) ){
									$title = $vendor[ $txv['account'] ];
								}
							break;
							}
							
							if( ! $title ){
								$acc = get_chart_of_accounts_details( array( "id" => $txv['account'] ) );
								if( isset( $acc[ "title" ] ) && $acc[ "title" ] ){
									$title = $acc[ "title" ];
								}
							}
							
							if( isset( $txv['comment'] ) && $txv['comment'] ){
								$title .= '<br />' . $txv['comment'];
							}
							$total += $price;
							?>
							<tr>
							   <td><?php echo $serial; ?></td>
							   <td><?php echo $title; ?></td>
							   <td align="right" ><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
							</tr>
							<?php
						}
					}
					
					?>
					
					<tr>
					   <td colspan="2"><strong>TOTAL DEBIT</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total , 4 ); ?></strong></td>
					</tr>
					
					
				 </tbody>
			  </table>
		   </div>
		   <div class="col-xs-6">
			  <table class="table table-striped table-hover">
				 <thead>
					<tr>
					   <th>#</th>
					   <th>Credit</th>
					   <th style="text-align:right;">Amount</th>
					</tr>
				 </thead>
				 <tbody>
					<?php 
					$serial = 0;
					
					if( isset( $data["debit_and_credit"] ) && is_array( $data["debit_and_credit"] ) && ! empty( $data["debit_and_credit"] ) ){
						foreach( $data["debit_and_credit"] as $txv ){
							if( $txv['type'] != "credit" )continue;
							
							++$serial;
							$price = $txv["amount"];
							
							$title = "";
							switch( $txv["account_type"] ){
							case "cost_of_goods_sold":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "CGS: " . $category[ $txv['account'] ];
								}
							break;
							case "inventory_marketing_expense":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "MKT: " . $category[ $txv['account'] ];
								}
							break;
							case "damaged_items":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "DMG: " . $category[ $txv['account'] ];
								}
							break;
							case "inventory":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "INV: " . $category[ $txv['account'] ];
								}
							break;
							case "revenue_category":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "REV: " . $category[ $txv['account'] ];
								}
							break;
							case "accounts_receivable":
								if( isset( $customer[ $txv['account'] ] ) ){
									$title = $customer[ $txv['account'] ];
								}
							break;
							case "cash_book": 
								if( isset( $payment_methods_list[ $txv['account'] ] ) ){
									$title = $payment_methods_list[ $txv['account'] ];
								}
							break;
							case "petty_cash": case "main_cash":
							case "bank6": case "bank7": case "bank8": case "bank9": case "bank10":
							case "bank5": case "bank4": case "bank3": case "bank2": case "bank1":
								if( isset( $payment_methods[ $txv['account'] ] ) ){
									$title = $payment_methods[ $txv['account'] ];
								}
							break;
							case "account_payable":
								if( isset( $vendor[ $txv['account'] ] ) ){
									$title = $vendor[ $txv['account'] ];
								}
							break;
							}
							
							if( ! $title ){
								$acc = get_chart_of_accounts_details( array( "id" => $txv['account'] ) );
								if( isset( $acc[ "title" ] ) && $acc[ "title" ] ){
									$title = $acc[ "title" ];
								}
							}
							
							
							if( isset( $txv['comment'] ) && $txv['comment'] ){
								$title .= '<br />' . $txv['comment'];
							}
							$total_c += $price;
							?>
							<tr>
							   <td><?php echo $serial; ?></td>
							   <td><?php echo $title; ?></td>
							   <td align="right" ><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
							</tr>
							<?php
						}
					}
					
					?>
					
					<tr>
					   <td colspan="2"><strong>TOTAL CREDIT</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total_c , 4 ); ?></strong></td>
					</tr>
					
					
				 </tbody>
			  </table>
		   </div>
		</div>
		
		<div class="row">
		  
		   <div class="col-xs-6">
			  <div class="note note-success" style="background:#C8F198;">
				<h4>Balance: <strong class="pull-right"><?php echo format_and_convert_numbers( $total - $total_c , 4 ); ?></strong>
				</h4>
			 </div>
		   </div>
		   
		   <div class="col-xs-6 invoice-block">
				 <ul class="list-unstyled amounts">
				<?php $key = "created_by"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li style=""><strong>by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
				 <?php } ?>
				</ul>
				
			  <?php if( ! $preview ){ ?>
				  <?php if( ! $backend ){ ?>
				  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();"><i class="icon-print"></i> Print Invoice </a>
				  <script type="text/javascript">
					setTimeout(function(){ window.print(); }, 800 );
				  </script>
				  <?php }else{ ?>
				  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-transaction&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue hidden-print"><i class="icon-print"></i> Print Preview</a>
				  <?php } ?>
			  <?php }else{ ?>
				<a class="btn green hidden-print" onclick="nwTransactions.postPreviewDraftTransactions();">Post Transaction</a>
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