<?php
if( isset( $data['sales_record'] ) && is_array( $data['sales_record'] ) ){
			$customers = get_customers();
			$pm = get_payment_method();
			$g_discount_after_tax = get_sales_discount_after_tax_settings();
			
			//$emp = get_employees();
			$owe = 0;
			$first = 1;
			foreach( $data['sales_record'] as $sval ){
				$due = $sval["amount_due"];
				$a = $due - round( $sval["amount_paid"], 2 );
				
				$charge = "";
				if( $a <= 0 ){ 
					$charge = '<span class="label label-info">paid in full</span>';
				}
				
				switch( $sval["payment_method"] ){
				case "charge_to_room":
				case "complimentary":
					if( isset( $pm[ $sval["payment_method"] ] ) )$charge = '<span class="label label-default">'.$pm[ $sval["payment_method"] ].'</span>';
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
				<tr class="item-sales" id="<?php echo $sval["id"]; ?>" serial="<?php echo $sval["serial_num"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-quantity="<?php echo $sval["quantity"]; ?>" data-discount="<?php echo $sval["discount"]; ?>" data-amount_due="<?php echo $sval["amount_due"]; ?>" data-amount_paid="<?php echo $sval["amount_paid"]; ?>" data-payment_method="<?php echo $sval["payment_method"]; ?>" data-customer="<?php echo $sval["customer"]; ?>" data-store="<?php echo $sval["store"]; ?>" data-sales_status="<?php echo $sval["sales_status"]; ?>" data-comment="<?php echo $sval["comment"]; ?>" data-amount_owed="<?php echo $a; ?>" data-status="<?php echo $charge1; ?>">
				  <td>
					<?php echo date( "d-M-Y" , doubleval( $sval["date"] ) ); ?>
					<?php if( isset( $sval["sales_status"] ) && $sval["sales_status"] == "booked" ){ ?><br /><br /><small><small><span class="label label-info"><i class="icon-pushpin"></i> booked</span></small></small><?php } ?>
				  </td>
				  <td>
					<a href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=sales&todo=view_invoice_app1" title="View Invoice">#<strong><?php echo mask_serial_number( $sval["serial_num"] , 'S' ); ?></strong></a>
					<br />
					
					Units Sold: <strong><?php echo $sval["quantity"]; ?></strong><?php echo ( isset( $customers[ $sval["customer"] ] )?("<br /><strong>".$customers[ $sval["customer"] ]."</strong>"):"" ); ?>
					
					<?php if( $sval["comment"] ){ ?>
					<br /><small><i><?php echo $sval["comment"]; ?></i></small>
					<?php } ?>
					<br />
					<a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=sales&todo=view_invoice_app1" title="View Invoice / Receipt">View Details <i class="icon-external-link"></i></a>
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
					No Transactions have been carried out for the customer in question
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