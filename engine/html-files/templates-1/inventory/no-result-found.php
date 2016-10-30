<div class="alert alert-danger">
	<h4><i class="icon-bell"></i> No Result Found</h4>
	<p>
	<?php if( isset( $data["barcode"] ) && $data["barcode"] ){ ?>
		The Item with the barcode <?php echo '<strong>'.$data[ "search" ].'</strong>'; ?> is out-of-stock or has been deleted.
	<?php }else{ ?>
		No item was found<?php if( isset( $data[ "search" ] ) && $data[ "search" ] )echo ' for search criteria = "<strong>'.$data[ "search" ].'</strong>" '; ?> <?php if( isset( $data[ "category" ] ) && $data[ "category" ] )echo ' in category = "<strong>'.$data[ "category" ].'</strong>" '; ?>
		</p>
		<p>
		<strong>Please enter the item description or barcode & Press the "Enter Key" on your Keyboard.</strong>
	<?php } ?>
	</p>
</div>
