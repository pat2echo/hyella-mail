<!-- BEGIN PAGE CONTAINER -->  
<div class="page-container" >
	<?php if( isset( $data["over_stay"] ) && $data["over_stay"] && isset( $data["room_id"] ) && $data["room_id"] && isset( $data["checkout_date"] ) && $data["checkout_date"] ){ ?>
	<div class="alert alert-danger">
		<h4><i class="icon-bell"></i> Over Stay Notice</h4>
		<p>Your bill will been increased by an extra night because you have stayed past <strong><?php echo $data["over_stay"]; ?></strong></p>
		<p>You must confirm the new bill before you can checkout</p>
		<br />
		<a href="#" class="btn red custom-single-selected-record-button" action="?module=&action=hotel_room_checkin&todo=confirm_overstay_bill" mod="<?php echo $data["checkout_date"]; ?>" override-selected-record="<?php echo $data["room_id"]; ?>">Confirm Bill</a>
	</div>
	<?php }else{ ?>
	
		<?php if( isset( $data['form']["html"] ) )echo $data['form']["html"]; ?>
	
	<?php } ?>
</div>
<!-- END PAGE CONTAINER -->  