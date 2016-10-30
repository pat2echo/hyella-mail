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
				<h4><i class="icon-user" style="font-size:1.3em;"></i> <strong><?php echo $c["name"]; ?></strong></h4>
				<p><?php echo $c["phone"]; if( isset( $c["second_phone"] ) && $c["second_phone"] )echo ", " . $c["second_phone"]; ?> | <?php echo $c["email"]; ?>
				<br /><?php echo $c["address"]; if( isset( $c["city"] ) && $c["city"] )echo ", " . $c["city"]; ?>
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
				
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
}

if( isset( $data[ "transaction" ] ) && is_array( $data[ "transaction" ] ) ){ 
	
?>
<div class="row">
<div class="col-md-12">

<div class="portlet-body shopping-cart-table">
	<div class="table-responsive">
		<table class="table table-striped table-hover bordered">
		<thead>
			<tr>
				<th colspan="2">Total Value of Customer Transaction</th>
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