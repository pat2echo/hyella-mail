<?php $key = "id"; if( isset( $data["item"][$key] ) ){ ?>
<div class="alert alert-info">
	<p>
	<?php $key = "description"; echo $data["item"][$key]; ?><br />
	Vendor: <strong><?php $key = "vendor"; echo get_select_option_value( array( "id" => $data["item"][$key], "function_name" => "get_vendors" ) ); ?></strong><br />
	Amount Due: <strong><?php $key = "amount_due"; echo number_format( $data["item"][$key] , 2 ); ?></strong><br />
	Amount Paid: <strong><?php $key = "total_amount_paid"; echo number_format( $data["item"][$key] , 2 ); ?></strong>
	</p>
</div>

<?php }else{ ?>
<div class="alert alert-danger">
	<h4><i class="icon-bell"></i> Invalid Expenditure</h4>
	<p>
	No data was found<br />
	<strong>Please Select the record you wish to capture payment for.</strong>
	</p>
</div>
<?php } ?>