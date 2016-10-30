<?php
if( isset( $data['expense_record'] ) && is_array( $data['expense_record'] ) ){
		$vendors = get_vendors();
		$pm = get_payment_method();
		
		//$emp = get_employees();
		$owe = 0;
		$first = 1;
		foreach( $data['expense_record'] as $sval ){
			
			$sval["amount_paid"] = $sval["total_amount_paid"];
			
			$due = $sval["amount_due"];
			$a = $due - round( $sval["amount_paid"], 2 );
			
			$charge = "";
			if( $a <= 0 ){ 
				$charge = '<span class="label label-info">paid in full</span>';
			}
			
			$show_details = 0;
			switch( $sval["category_of_expense"] ){
			case "purchase_order":
				$show_details = 1;
			break;
			}
			
			$charge1 = 0;
			if( $charge ){
				$charge1 = 1;
			}
			
			if( $first ){
				$first = 0;
				?>
				<div class="shopping-cart-table">
				<div class="table-responsive">
					<table class="table table-striped table-hover bordered">
					<thead>
					   <tr>
						  <th>Date</th>
						  <th>Details</th>
						  <th class="r">Amount Due</th>
						  <th class="r">Amount Owed</th>
					   </tr>
					</thead>
					<tbody>
				<?php
			}
			$owe = 1;
			
			?>
			<tr class="item-sales" id="<?php echo $sval["id"]; ?>" serial="<?php echo $sval["serial_num"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-amount_due="<?php echo $sval["amount_due"]; ?>" data-amount_paid="<?php echo $sval["amount_paid"]; ?>" data-mode_of_payment="<?php echo $sval["mode_of_payment"]; ?>" data-store="<?php echo $sval["store"]; ?>" data-vendor="<?php echo $sval["vendor"]; ?>" data-status="<?php echo $sval["status"]; ?>" data-description="<?php echo $sval["description"]; ?>" data-receipt_no="<?php echo $sval["receipt_no"]; ?>" data-amount_owed="<?php echo $a; ?>" status="<?php echo $charge1; ?>">
			  <td>
				<?php echo date( "d-M-Y" , doubleval( $sval["date"] ) ); ?>
				<?php if( isset( $sval["status"] ) && $sval["status"] == "pending" ){ ?><br /><br /><small><small><span class="label label-info"><i class="icon-pushpin"></i> pending</span></small></small><?php } ?>
			  </td>
			  <td>
				<a href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=expenditure&todo=view_invoice_app1" title="View Invoice">#<strong><?php echo $sval["serial_num"]; ?>-<?php echo $sval["id"]; ?></strong></a>
				<br />
				
				<?php echo $sval["description"]; ?><?php echo ( isset( $vendors[ $sval["vendor"] ] )?("<br /><strong>".$vendors[ $sval["vendor"] ]."</strong>"):"" ); ?>
				
				<?php if( $sval["receipt_no"] ){ ?>
				<br /><small><i><?php echo $sval["receipt_no"]; ?></i></small>
				<?php } ?>
				
				<?php if( $show_details ){ ?>
				<br />
				<a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=expenditure&todo=view_invoice_app1" title="View Invoice / Receipt">View Details <i class="icon-external-link"></i></a>
					<?php if( isset( $sval["status"] ) && $sval["status"] == "pending" ){ ?>
						<a href="#" class="btn btn-xs red custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=expenditure&todo=update_stock_status_direct" id="update-button-<?php echo $sval["id"]; ?>" title="Update items in Stock & Mark Purchase Order as received">Update Stock</a>
					<?php } ?>
				<?php } ?>
			  </td>
			   
			  <td class="r">
				<?php echo format_and_convert_numbers( $due, 4 ); ?>
				
			  </td>
			  <td class="r amount-owed">
			  <?php 
				if( $charge ){ 
					echo $charge; 
				}else{
					echo format_and_convert_numbers( $a , 4 );
				}  
			 ?>
				</td>
			</tr>
			<?php
			
			
		}
		
		if( ! $owe ){
			?>
			<div class="alert alert-info">
				<h4><i class="icon-bell"></i> No Transaction(s)</h4>
				<p>
				No Transactions have been carried out for the vendor in question
				</p>
			</div>
			<?php
		}else{
			?>
			</tbody>
			</table>
			</div>

			</div>
			<?php
		}
?>

<?php } ?>