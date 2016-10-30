<?php
if( isset( $data['appraisal_record'] ) && is_array( $data['appraisal_record'] ) ){
				$customers = get_customers();
				//$emp = get_employees();
				$owe = 0;
				$first = 1;
				foreach( $data['appraisal_record'] as $sval ){
					
					if( $first ){
						$first = 0;
						?>
						<div class="shopping-cart-table">
						<div class="table-responsive">
							<table class="table table-striped table-hover bordered">
							<thead>
							   <tr>
								  <th>Date</th>
								  <th>Ref Num</th>
								  <th>Details</th>
							   </tr>
							</thead>
							<tbody>
						<?php
					}
					$owe = 1;
					
					?>
					<tr class="item-appraisal" id="<?php echo $sval["id"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-customer="<?php echo $sval["customer"]; ?>" data-comment="<?php echo $sval["comment"]; ?>" >
					  <td>
						<?php echo date( "d-M-Y" , doubleval( $sval["date"] ) ); ?>
					  </td>
					  <td>
						<a href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=appraisal&todo=view_invoice_app1" title="View Appraisal Certificate">#<strong><?php echo $sval["serial_num"]; ?></strong></a>
					 </td>
					 <td>
						<?php echo ( isset( $customers[ $sval["customer"] ] )?("<strong>".$customers[ $sval["customer"] ]."</strong>"):"" ); ?>
						
						<?php if( $sval["comment"] ){ ?>
						<br /><small><i><?php echo $sval["comment"]; ?></i></small>
						<?php } ?>
						<br />
						<a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=appraisal&todo=view_invoice_app1" title="View Appraisal Certificate">View Details <i class="icon-external-link"></i></a>
					  </td>
					   
					</tr>
					<?php
					
					
				}
				
				if( ! $owe ){
					?>
					<div class="alert alert-info">
						<h4><i class="icon-bell"></i> No Appraisal(s)</h4>
						<p>
						No Appraisal have been carried out for the customer in question
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