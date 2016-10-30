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
		?>
		<div class="input-group">
		 <span class="input-group-addon" style=" line-height: 1.5;"><?php echo $blabel; ?></span>
		 <span  class="input-group-addon amount-due" style="<?php echo $bstyle; ?> background: #A7E862; line-height: 1.5;"><strong><?php echo number_format( $balance , 2 ); ?></strong></span>
		</div><br />
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
				<th colspan="2">
					<?php if( ( isset( $data[ "start_date" ] ) && $data[ "start_date" ] ) || ( isset( $data[ "end_date" ] ) && $data[ "end_date" ] ) ){ ?>
					Transactions from <?php echo date("d-M-Y", doubleval( $data[ "start_date" ] ) ); ?> to <?php echo date("d-M-Y", doubleval( $data[ "end_date" ] ) ); ?>
					<?php }else{ ?>
					Last <?php echo count( $data[ "recent_transaction" ] ); ?> Transactions
					<?php } ?>
				</th>
				<th>Bill (Debit)</th>
				<th>Payment (Credit)</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				foreach( $data[ "recent_transaction" ] as $k => $v ){
					//$v["extra_reference"] = '';
					if( $v["extra_reference"] ){
						$v["description"] = '<a href="#" action="?module=&action=sales&todo=view_invoice" class="custom-single-selected-record-button" override-selected-record="'.$v["extra_reference"].'">'.$v["description"].'</a>';
					}else{
						$v["description"] = '<a href="#" action="?module=&action=transactions&todo=view_invoice" class="custom-single-selected-record-button" override-selected-record="'.$v["id"].'">'.$v["description"].'</a>';
					}
					
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