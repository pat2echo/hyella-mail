<?php
if( isset( $data['sales_record'] ) && is_array( $data['sales_record'] ) ){
			$customers = get_customers();
			//$emp = get_employees();
			$owe = 0;
			$first = 1;
			$g_discount_after_tax = get_sales_discount_after_tax_settings();
			
			foreach( $data['sales_record'] as $sval ){
				if( $g_discount_after_tax ){
					$due = $sval["amount_due"];
				}else{
					$due = $sval["amount_due"] - $sval["discount"];
				}
				
				$vat = 0;
				$service_charge = 0;
				$service_tax = 0;
				if( isset( $sval["vat"] ) && doubleval( $sval["vat"] ) )
					$vat = round( $due * $sval["vat"] / 100, 2 );
				
				if( isset( $sval["service_charge"] ) && doubleval( $sval["service_charge"] ) )
					$service_charge = round( $due * $sval["service_charge"] / 100, 2 );
				
				if( isset( $sval["service_tax"] ) && doubleval( $sval["service_tax"] ) )
					$service_tax = round( $due * $sval["service_tax"] / 100, 2 );
				
				$due += $service_charge + $vat + $service_tax;
				if( $g_discount_after_tax ){
					$due = $due - $sval["discount"];
				}
				$a = $due - round( $sval["amount_paid"], 2 );
				
				$c = 0;
				if( ! ceil( $a ) )$c = 1;
				if( $c && $sval["sales_status"] == "booked" )$c = 0;
				
				if( $c )continue;
				
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
				<tr class="item-sales" id="<?php echo $sval["id"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-quantity="<?php echo $sval["quantity"]; ?>" data-discount="<?php echo $sval["discount"]; ?>" data-amount_due="<?php echo $sval["amount_due"]; ?>" data-amount_paid="<?php echo $sval["amount_paid"]; ?>" data-payment_method="<?php echo $sval["payment_method"]; ?>" data-customer="<?php echo $sval["customer"]; ?>" data-store="<?php echo $sval["store"]; ?>" data-sales_status="<?php echo $sval["sales_status"]; ?>" data-comment="<?php echo $sval["comment"]; ?>" data-amount_owed="<?php echo $a; ?>">
				  <td>
					<?php echo date( "d-M-Y" , doubleval( $sval["date"] ) ); ?>
					<?php if( isset( $sval["sales_status"] ) && $sval["sales_status"] == "booked" ){ ?><br /><br /><small><small><span class="label label-info"><i class="icon-pushpin"></i> booked</span></small></small><?php } ?>
				  </td>
				  <td>
					<a href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=sales&todo=view_invoice_app1" title="View Invoice / Receipt">#<strong><?php echo $sval["serial_num"]; ?></strong></a>
					<br />
					
					Units Sold: <strong><?php echo $sval["quantity"]; ?></strong>
					<?php if( isset( $customers[ $sval["customer"] ] ) ){ ?>
						<br /><a href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $sval["customer"]; ?>" action="?module=&action=customers&todo=view_customer_details" title="View Customer Details"><strong><?php echo $customers[ $sval["customer"] ]; ?></strong></a>
					<?php } ?>
					
					<?php if( $sval["comment"] ){ ?>
					<br /><small><i><?php echo $sval["comment"]; ?></i></small>
					<?php } ?>
					<br />
					<a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=sales&todo=view_invoice_app1" title="View Invoice / Receipt">View Details <i class="icon-external-link"></i></a>
					
				  </td>
				   
				  <td class="r"><?php echo format_and_convert_numbers( $due , 4 ); ?></td>
				  <td class="r amount-owed"><?php echo format_and_convert_numbers( $a , 4 ); ?></td>
				 
				</tr>
				<?php
				
				
			}
			
			if( ! $owe ){
				?>
				<div class="alert alert-info">
					<h4><i class="icon-bell"></i> No Debt(s)</h4>
					<p>
					All / Selected Transactions have been paid in full
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