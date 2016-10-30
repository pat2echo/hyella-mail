<?php
if( isset( $data['vendor'] ) && is_array( $data['vendor'] ) ){
	$c = $data['vendor'];
	?>
	<div class="row vendor-item" id="<?php echo $c["id"]; ?>" <?php foreach( $c as $ck => $cv ){ echo ' data-' . $ck . '="'.$cv.'" '; } ?> >
		<div class="col-md-9">
			<div class="well">
				<h4><i class="icon-user" style="font-size:1.3em;"></i> <strong><?php echo $c['name_of_vendor']; ?></strong></h4>
				<p><?php echo $c["phone"]; ?> | <?php echo $c["email"]; ?>
				<br /><?php echo $c["address"]; ?></p>
				<p><?php echo $c["comment"]; ?></p>
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