<?php
if( isset( $data['payment_record'] ) && is_array( $data['payment_record'] ) ){
				$pm = get_payment_method();
				$customers = get_customers();
				$emp = get_employees();
				$owe = 0;
				$first = 1;
				foreach( $data['payment_record'] as $sval ){
					
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
								  <th class="r">Staff <!--<small>[Payment Method]</small>--></th>
								  <th class="r">Amount Paid</th>
							   </tr>
							</thead>
							<tbody>
						<?php
					}
					$owe = 1;
					
					?>
					<tr class="item-sales" id="<?php echo $sval["id"]; ?>">
					  <td>
						<?php echo date( "d-M-Y" , doubleval( $sval["date"] ) ); ?>
					  </td>
					  <td>
						REF: #<strong><?php echo $sval["sales_id"]; ?></strong> - <?php echo $sval['reference_table']; ?>
						
						<?php //echo ( isset( $customers[ $sval["customer"] ] )?("<br /><strong>".$customers[ $sval["customer"] ]."</strong>"):"" ); ?>
						
						<?php if( $sval["comment"] ){ ?>
						<br /><small><i><?php echo $sval["comment"]; ?></i></small>
						<?php } ?>
						<?php switch( $sval['reference_table'] ){
						case "payment":
						case "sales":
						?>
						<br />
						<a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="<?php echo $sval["sales_id"]; ?>" action="?module=&action=sales&todo=view_invoice_app1" title="View Invoice / Receipt">View Details <i class="icon-external-link"></i></a>
						<?php
						break;
						case "hotel_checkin":
						?>
						<br />
						<a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="<?php echo $sval["sales_id"]; ?>" action="?module=&action=hotel_checkin&todo=view_invoice&hide=1" title="View Invoice / Receipt">View Details <i class="icon-external-link"></i></a>
						<?php
						break;
						} 
						?>
					  </td>
					   
					  <td class="r">
						<?php if( isset( $emp[ $sval['staff_responsible'] ] ) )echo $emp[ $sval['staff_responsible'] ] . '<br />'; ?>
						<!--<small>[<?php //if( isset( $pm[ $sval['payment_method'] ] ) )echo $pm[ $sval['payment_method'] ]; ?>]</small>-->
						
					  </td>
					  <td class="r amount-owed"><strong><?php echo format_and_convert_numbers( $sval["amount_paid"] , 4 ); ?></strong></td>
					 
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