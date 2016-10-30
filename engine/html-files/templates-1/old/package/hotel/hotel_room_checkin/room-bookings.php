<div class="form-control-table">
<style type="text/css">
	.form-control-table table thead tr th,
	.form-control-table table{
		font-size:0.9em;
	}
	.form-control-table table tr th{
		background-color:#CCFFD2;/*#f4ccFF*/
		font-weight:bold;
	}
</style>
<h4><strong>Bookings / Reservations</strong></h4>
<table class="table table-striped table-bordered table-hover">
<thead>
	<tr>
		<th>S/N</th>
		<th>Guest</th>
		<th>Check In</th>
		<th>Check Out</th>
		<th>Status</th>
		<th> </th>
	</tr>
</thead>
<tbody>
<?php
	if( isset( $data["items"] ) && is_array( $data["items"] ) && $data["items"] ){
		$sn = 0;
		foreach( $data["items"] as $sval ){
			$guest = get_customers_details( array( "id" => $sval["guest"] ) );
			$g = '<strong>'.strtoupper( isset($guest["name"])?$guest["name"]:"" ).'</strong>';
			?>
			<tr id="row-<?php echo $sval["id"]; ?>">
				<td><?php echo ++$sn; ?></td>
				<td><?php echo $g; ?></td>
				<td><?php echo date("d-M-Y", doubleval( $sval["checkin_date"] ) ); ?></td>
				<td><?php echo date("d-M-Y", doubleval( $sval["checkout_date"] ) ); ?></td>
				<td><?php echo strtoupper( $sval["status"] ); ?>  <span class="pull-right">#<?php echo $sval["booking_ref"]; ?></span></td>
				<td> <button class="btn btn-xs dark custom-single-selected-record-button" action="?module=&action=hotel_room_checkin&todo=update_status_to_cancelled" override-selected-record="<?php echo $sval["id"]; ?>" >Cancel</button> <button class="btn btn-xs blue custom-single-selected-record-button" action="?module=&action=hotel_room_checkin&todo=update_status_to_checkin" override-selected-record="<?php echo $sval["id"]; ?>" >Check In</button> </td>
			</tr>
			<?php
		}
	}
		
?>
</tbody>
</table>
</div>