<?php
if( isset( $data['expense_record'] ) && is_array( $data['expense_record'] ) ){
		$vendors = get_vendors();
		$pm = get_payment_method();
		
		//$emp = get_employees();
		$owe = 0;
		$first = 1;
		foreach( $data['expense_record'] as $sval ){
			
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
						  <th class="r">Status</th>
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
				<a href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=expenditure&todo=view_invoice_app1" title="View Invoice">#<strong><?php echo $sval["serial_num"]; ?>-<?php echo $sval["id"]; ?></strong></a>
				<br />
				
				<?php echo $sval["description"]; ?><?php echo ( isset( $vendors[ $sval["vendor"] ] )?("<br /><strong>".$vendors[ $sval["vendor"] ]."</strong>"):"" ); ?>
				
				<?php if( $sval["receipt_no"] ){ ?>
				<br /><small><i><?php echo $sval["receipt_no"]; ?></i></small>
				<?php } ?>
				
				<br />
				<a href="#" class="btn btn-xs blue custom-single-selected-record-button" override-selected-record="<?php echo $sval["id"]; ?>" action="?module=&action=expenditure&todo=view_invoice_app1" title="View Invoice / Receipt">View Details <i class="icon-external-link"></i></a>
			  </td>
			   
			  <td class="r">
				<span class="label label-danger">Pending</span>
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