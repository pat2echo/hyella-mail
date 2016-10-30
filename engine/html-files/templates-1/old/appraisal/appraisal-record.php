<?php
if( isset( $data['appraisal_record'] ) && is_array( $data['appraisal_record'] ) ){
				$customers = get_customers();
				//$emp = get_employees();
				$owe = 0;
				$first = 1;
				foreach( $data['appraisal_record'] as $sval ){
					
					$a = ( $sval["amount_due"] - $sval["discount"] ) - $sval["amount_paid"];
					
					$c = 0;
					if( ! ceil( $a ) )$c = 1;
					if( $c && $sval["appraisal_status"] == "booked" )$c = 0;
					
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
					<tr class="item-appraisal" id="<?php echo $sval["id"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-quantity="<?php echo $sval["quantity"]; ?>" data-discount="<?php echo $sval["discount"]; ?>" data-amount_due="<?php echo $sval["amount_due"]; ?>" data-amount_paid="<?php echo $sval["amount_paid"]; ?>" data-payment_method="<?php echo $sval["payment_method"]; ?>" data-customer="<?php echo $sval["customer"]; ?>" data-store="<?php echo $sval["store"]; ?>" data-appraisal_status="<?php echo $sval["appraisal_status"]; ?>" data-comment="<?php echo $sval["comment"]; ?>" data-amount_owed="<?php echo $a; ?>">
					  <td>
						<?php echo date( "d-M-Y" , doubleval( $sval["date"] ) ); ?>
						<?php if( isset( $sval["appraisal_status"] ) && $sval["appraisal_status"] == "booked" ){ ?><br /><br /><small><small><span class="label label-info"><i class="icon-pushpin"></i> booked</span></small></small><?php } ?>
					  </td>
					  <td>
						<a href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=appraisal&todo=view_invoice_app1" title="View Invoice / Receipt">#<strong><?php echo $sval["serial_num"]; ?></strong></a>
						<br />
						
						Units Sold: <strong><?php echo $sval["quantity"]; ?></strong>
						<?php if( isset( $customers[ $sval["customer"] ] ) ){ ?>
							<br /><a href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $sval["customer"]; ?>" action="?module=&action=customers&todo=view_customer_details" title="View Customer Details"><strong><?php echo $customers[ $sval["customer"] ]; ?></strong></a>
						<?php } ?>
						
						<?php if( $sval["comment"] ){ ?>
						<br /><small><i><?php echo $sval["comment"]; ?></i></small>
						<?php } ?>
						<br />
						<a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=appraisal&todo=view_invoice_app1" title="View Invoice / Receipt">View Details <i class="icon-external-link"></i></a>
						
					  </td>
					   
					  <td class="r"><?php echo format_and_convert_numbers( $sval["amount_due"] - $sval["discount"], 4 ); ?></td>
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