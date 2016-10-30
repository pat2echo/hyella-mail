<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	
	$vat = 0;
	$service_charge = 0;
	$service_tax = 0;
?>
<!--EXCEL IMPOT FORM-->
<div class="row">
	
	<div class="col-md-5"> 
		 <input type="hidden" name="date" value="<?php echo date("Y-m-d") ?>" class="form-control" value="" />
		
		<div class="input-group">
		 <span class="input-group-addon" >Group</span>
		 <select class="form-control select-group-guest" name="group">
			<option value="">-Select Group-</option>
			<?php
				if( isset( $data['customers'] ) && is_array( $data['customers'] ) ){
					foreach( $data['customers'] as $key => $val ){
						?>
						<option value="<?php echo $key; ?>">
							<?php echo $val; ?>
						</option>
						<?php
					}
				}
			?>
		 </select>
		</div>	 
		<hr />
		<h4>Add Guest(s) to Group</h4>
		<div class="row">
			<div class="col-md-12">
				 <label>Room Guest</label>
				 
				 <select class="form-control select-room-guest group-member" name="guest">
					<option value="">-Select Room Guest-</option>
					<?php
						if( isset( $data['customers'] ) && is_array( $data['customers'] ) ){
							foreach( $data['customers'] as $key => $val ){
								?>
								<option value="<?php echo $key; ?>">
									<?php echo $val; ?>
								</option>
								<?php
							}
						}
					?>
				 </select>
				
			</div>
			<div class="col-md-12">
				<br />
				<label>Room</label>
				 <select class="form-control group-member" placeholder="Available Rooms" name="room">
					<option value="" class="all">-Available Rooms-</option>
					<?php
						if( isset( $data['available_rooms'] ) && is_array( $data['available_rooms'] ) ){
							$room_types = get_hotel_room_types();
							foreach( $data['available_rooms'] as $key => $val ){
								?>
								<option value="<?php echo $key; ?>" data-room-type="<?php echo $val["room_type"]; ?>" class="room-<?php echo $key; ?> <?php echo $val["room_type"]; ?>">
									<?php echo $val["room_number"] ." - ". ( isset( $room_types[ $val["room_type"] ] )?$room_types[ $val["room_type"] ]:"" ) ; ?>
								</option>
								<?php
							}
						}
					?>
				</select>
				<br />
			</div>
			<br />
			<div class="clearfix"></div>
			<div class="col-md-6">
				 <label>Check Out Date</label>
				 <input type="date" class="form-control group-member" name="checkout_date" value="<?php echo date("Y-m-d", date("U") + ( 3600 * 24 ) ); ?>" />
				 <br />
			</div>
			<div class="col-md-6">
				 <label>Comment</label>
				 <input type="text" class="form-control group-member" name="comment" placeholder="Optional Comment" />
				 <br />
			</div>
			<div class="clearfix"></div>
			<div class="col-md-12">
				<button class="btn dark btn-block " id="add-group-member" title="Add Group Member(s)">Save Group Member</button>
			</div>
		</div>
	</div>	
	<div class="col-md-6 col-md-offset-1">
		
		<button class="btn dark custom-action-button" type="button" function-class="customers" function-name="new_group_guest_form" function-id="340" skip-title="1" title="Add New Group">Create New Group</button>
		<button class="btn dark custom-action-button" type="button" function-class="customers" function-name="new_room_guest_form" function-id="340" skip-title="1" title="Add New Guest">Create New Room Guest</button>
		 <div id="room-guest-container">
			<div class="alert alert-warning alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
				<strong>Click the button above to create a new room guest or a new group</strong>
			</div>
		 </div>
					 
		<div class="shopping-cart-table">
			<h4>Group Members</h4>
			<div class="table-responsive">
				<table class="table table-striped table-hover bordered">
				<thead>
				   <tr>
					  <th style="font-size:10px;">Room Guest</th>
					  <th style="font-size:10px;" class="r">Room</th>
					  <th style="font-size:10px;" class="r">Checkout Date</th>
					  <th style="font-size:10px;" class="r"></th>
				   </tr>
				</thead>
				<tbody id="group-members">
				   
				</tbody>
				<tfoot>
				   
				</tfoot>
				</table>
			</div>
			<hr />
			
			<div class="row">
				<div class="col-md-12">
					<button class="btn btn-lg green btn-block custom-single-selected-record-button" action="?module=&action=hotel&todo=save_group_checkin" id="save-group-checkin" style="display:none;">Save</button>
					<button class="btn btn-lg green btn-block" id="confirm-group-checkin" >Confirm Group Check In</button>
				</div>
				<br />
				<div class="col-md-12">
					<button class="btn btn-lg default btn-block" onclick="nwGroupCheckIn.emptyCart();">Cancel Operation</button>
				</div>
			</div>
		</div>
		
	</div>	
	
</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>