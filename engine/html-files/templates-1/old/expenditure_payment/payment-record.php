<?php
if( isset( $data['payment_record'] ) && is_array( $data['payment_record'] ) && ! empty( $data['payment_record'] ) ){
		$emp = get_employees();
		$p = get_payment_method();
		?>
		<div class="shopping-cart-table">
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<thead>
			   <tr>
				  <th>Date</th>
				  <th>Details</th>
				  <th class="r">Amount Paid</th>
			   </tr>
			</thead>
			<tbody id="payment-histories">
		<?php
		foreach( $data['payment_record'] as $sval ){
			?>
			<tr class="item-payment" id="<?php echo $sval["id"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-amount_paid="<?php echo $sval["amount_paid"]; ?>" data-sales_id="<?php echo $sval["sales_id"]; ?>" data-payment_method="<?php echo $sval["payment_method"]; ?>" data-comment="<?php echo $sval["comment"]; ?>">
			  <td>
				<?php echo date( "d-M-Y" , doubleval( $sval["date"] ) ); ?>
			  </td>
			  <td>
				#<small><?php echo $sval["id"]; ?></small>
				<small><small><span class="label label-info pull-right"> <?php echo ( isset( $p[ $sval["payment_method"] ] )?$p[ $sval["payment_method"] ]:$sval["payment_method"] ); ?></span></small></small>
				<br />
				Staff: <?php echo ( isset( $emp[ $sval["staff_responsible"] ] )?("<strong>".$emp[ $sval["staff_responsible"] ]."</strong>"):"" ); ?>
				
				<?php if( $sval["comment"] ){ ?>
				<br /><small><i><?php echo $sval["comment"]; ?></i></small>
				<?php } ?>
			  </td>
			   
			  <td class="r"><?php echo format_and_convert_numbers( $sval["amount_paid"], 4 ); ?></td>
			 
			</tr>
			<?php
			
			
		}
		?>
	</tbody>
	</table>
	<div style="display:none;" id="delete-button-holder">
		<button class="btn btn-sm dark" onclick="nwRecordPayment.deleteStockItem();"><i class="icon-trash"></i> Delete</button>
		<button class="btn btn-sm dark" onclick="nwRecordPayment.cancelDeleteStockItem(); return false;"> Cancel</button>
	</div>
	<div style="display:none;">
		<button id="actual-delete-payment" class="btn btn-sm dark custom-single-selected-record-button" action="?module=&action=payment&todo=delete_payment_record" override-selected-record="">Actual Delete</button>
	</div>
	</div>

	</div>
<?php }else{
	?>
	<div class="alert alert-danger">
		<h4><i class="icon-bell"></i> No Payment</h4>
		<p>
		No payment has been recorded for this transaction
		</p>
	</div>
	<?php
} ?>