<?php
if( isset( $data['vendor'] ) && is_array( $data['vendor'] ) ){
	$c = $data['vendor'];
	?>
	<div class="row vendor-item" id="<?php echo $c["id"]; ?>" <?php foreach( $c as $ck => $cv ){ echo ' data-' . $ck . '="'.$cv.'" '; } ?> >
		<div class="col-md-8">
			<div class="well">
				<h4><i class="icon-user" style="font-size:1.3em;"></i> <strong><?php echo $c['name_of_vendor']; ?></strong></h4>
				<p><?php echo $c["phone"]; ?> | <?php echo $c["email"]; ?>
				<br /><?php echo $c["address"]; ?></p>
				<p><?php echo $c["comment"]; ?></p>
			</div>
		</div>
		<div class="col-md-4">
			<div style="font-weight:bold; color:#333; font-size:1.1em; line-height:1.2;">
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
					$blabel = "Vendors Debt";
					$bstyle = "color:#b30000;";
				}elseif( $balance < 0 ){
					$blabel = "Money Owed to Vendor";
					$balance = abs( $balance );
				}
				
				if( $blabel ){
					?><div style="<?php echo $bstyle; ?>"><small><?php echo $blabel; ?></small><br /><?php echo number_format( $balance , 2 ); ?></div><?php
				}
			?>
			</div>
		</div>
		
	</div>
	<?php
}

/*
if( isset( $data[ "transaction" ] ) && is_array( $data[ "transaction" ] ) ){ 
	
?>
<div class="row">
<div class="col-md-12">

<div class="portlet-body shopping-cart-table">
	<div class="table-responsive">
		<table class="table table-striped table-hover bordered">
		<thead>
			<tr>
				<th colspan="2">Total Value of Goods from Vendor</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				foreach( $data[ "transaction" ] as $k => $v ){
					?>
					 <tr>
						<td style="border-right:1px solid #ddd;">Total <?php echo ucwords( str_replace("_", " ", $k ) ); ?></td>
						<td><strong><?php echo format_and_convert_numbers( $v, 4 ); ?></strong></td>
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
*/

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
				<th>Debit</th>
				<th>Credit</th>
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