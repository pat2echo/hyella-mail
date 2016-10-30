<?php
if( isset( $data['customer'] ) && is_array( $data['customer'] ) ){
	$c = $data['customer'];
	?>
	<div class="row customer-item" <?php 
		foreach( $c as $ck => $cv ){ 
			switch( $ck ){
			case "date_of_birth":
				echo ' data-' . $ck . '="'.date("Y-m-d", doubleval( $cv ) ).'" '; 
			break;
			default:
				echo ' data-' . $ck . '="'.$cv.'" '; 
			break;
			}
			
		} 
	?> >
		<div class="col-md-8">
			<div class="well">
				<h4><i class="icon-user" style="font-size:1.3em;"></i> <strong><?php echo $c["name"] . ' ( ' . $c["address"] . ' )'; ?></strong></h4>
				<p>
				<?php if( $c["phone"] || $c["email"] ){ ?>
					<?php echo $c["phone"]; if( isset( $c["second_phone"] ) && $c["second_phone"] )echo ", " . $c["second_phone"]; ?> | <?php echo $c["email"]; ?>
					<br />
				<?php } ?>
				<?php echo $c["address"]; if( isset( $c["city"] ) && $c["city"] )echo ", " . $c["city"]; ?>
				</p>
				<?php if( isset( $c["date_of_birth"] ) ){ ?>
				<p><strong>Birth Day: <?php echo date( "jS, F", doubleval( $c["date_of_birth"] ) ); ?></strong> | Source: <?php echo $c["referral_source"]; ?>
				<br /><i><?php echo $c["comment"]; ?></i></p>
				<?php }else{
					?>
					<p></p>
					<?php
				} ?>
			</div>
		</div>
		<div class="col-md-4">
			<div style="font-weight:bold; color:#333; font-size:1.3em; line-height:1.2;">
				<?php if( isset( $c["spouse"] ) ){ ?>
				<div><small><small>Spouse</small><br /><?php echo $c["spouse"]; ?></small></div>
				<br />
				<div><small><small>Ring Size</small><br />His: <?php echo $c["his_ring_size"]; ?> | Her: <?php echo $c["her_ring_size"]; ?></small></div>
				<br />
				<div><small><small>Credit Limit</small><br /><?php echo number_format( $c["credit_limit"], 2 ); ?></small></div>
				
				<?php }else{ ?>
					<?php
						$billt = 0;
						$payt = 0;
						
						if( isset( $data[ "transaction" ][0]["amount"] ) && $data[ "transaction" ][0]["amount"] ){ 
							if( $data[ "transaction" ][0]["type"] == "debit" ){
								$billt = doubleval( $data[ "transaction" ][0]["amount"] );
							}else{
								$payt = doubleval( $data[ "transaction" ][0]["amount"] );
							}
						}
						
						if( isset( $data[ "transaction" ][1]["amount"] ) && $data[ "transaction" ][1]["amount"] ){ 
							if( $data[ "transaction" ][1]["type"] == "debit" ){
								$billt += doubleval( $data[ "transaction" ][1]["amount"] );
							}else{
								$payt += doubleval( $data[ "transaction" ][1]["amount"] );
							}
						}
						$bstyle = "";
						$blabel = "";
						$balance = round( $billt , 2 ) - round( $payt , 2 );
						if( $balance > 0 ){
							$blabel = "Outstanding Debt";
							$bstyle = "color:#b30000;";
						}elseif( $balance < 0 ){
							$blabel = "Refund";
							$balance = abs( $balance );
						}
						
						if( $blabel ){
							?><div style="<?php echo $bstyle; ?>"><small><small><?php echo $blabel; ?></small><br /><?php echo number_format( $balance , 2 ); ?></small></div><?php
						}
						
						
						if( isset( $data[ "customer_deposits" ]["balance"] ) && $data[ "customer_deposits" ]["balance"] ){
							?><div style="font-weight:normal; color:#333; font-size:13px;"><small><small>Deposit Balance: </small><br /><?php echo number_format( $data[ "customer_deposits" ]["balance"] , 2 ); ?></small></div><?php
						}
					?>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
}

if( isset( $data[ "recent_transaction" ] ) && is_array( $data[ "recent_transaction" ] ) ){ 
	
?>
<div class="row">
<div class="col-md-12">

<div class="portlet-body shopping-cart-table">
	<div class="table-responsive">
		<table class="table table-striped table-hover bordered">
		<thead>
			<tr>
				<th colspan="2">Last <?php echo count( $data[ "recent_transaction" ] ); ?> Transactions</th>
				<th>Bills</th>
				<th>Payment</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				foreach( $data[ "recent_transaction" ] as $k => $v ){
					?>
					 <tr>
						<td style="border-right:1px solid #ddd;"><?php echo date( "d-M-Y", doubleval( $v["date"] ) ); ?></td>
						<td style="border-right:1px solid #ddd;"><?php echo $v["description"]; ?></td>
						<?php
							$bill = "";
							$payment = "";
							if( $v["type"] == "debit" )
								$bill = format_and_convert_numbers( $v["amount"] , 4 );
							else
								$payment = format_and_convert_numbers( $v["amount"] , 4 );
						?>
						<td style="border-right:1px solid #ddd; text-align:right;"><strong><?php echo $bill; ?></strong></td>
						<td style="border-right:1px solid #ddd; text-align:right;"><strong><?php echo $payment; ?></strong></td>
					   </tr>
					<?php
				}
			?>
		</tbody>
		</table>
	</div>
</div>

</div>
</div>
<?php } ?>