<div class="row">
<div class="col-md-offset-1 col-md-10">
<div class="row" style="margin-top:12px; ">
	<div class="col-md-4">	
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Filter by Location</span>
		  <select class="form-control" name="location">
			<option value="1">-All Locations-</option>
			<?php
				$s = '';
				if( isset( $data['store'] ) && $data['store'] ){
					$s = $data['store'];
				}
				
				if( isset( $data['stores'] ) && is_array( $data['stores'] ) ){
					foreach( $data['stores'] as $key => $val ){
						$selected = '';
						if( $s == $key )$selected = ' selected="selected" ';
						?>
						<option value="<?php echo $key; ?>" <?php echo $selected; ?>>
							<?php echo $val; ?>
						</option>
						<?php
					}
				}
			?>
		 </select>
		 </div>
		 <br />
		 <a href="#" class="custom-single-selected-record-button" style="display:none;" id="property-chart-button" action="?module=&action=property&todo=display_property_chart" override-selected-record="1" >Property Chart</a>
	</div>
	<!--
	<div class="col-md-4">	
		<div class="input-group">
		 <span class="input-group-addon" style="color:#777;">Search by Tenant</span>
		  <select class="form-control" name="customer">
			<option value="">-All Tenant-</option>
			<?php
				if( isset( $data['customer'] ) && is_array( $data['customer'] ) ){
					foreach( $data['customer'] as $key => $val ){
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
		 <br />
	</div>
	-->
</div>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<div class="report-table-preview" style="max-height:400px; overflow-y:auto;">
<table class="table table-bordered" cellspacing="0">
<thead>
<?php
	$body = "";
	
	$all_rooms = array();
	
	if( isset( $data['properties'] ) && $data['properties'] ){
		$all_rooms = $data['properties'];
	}
	
	$all_rentals = array();
	if( isset( $data['rentals'] ) && $data['rentals'] ){
		$all_rentals = $data['rentals'];
	}
	
	$rents = array();
	if( ! empty( $all_rentals ) ){
		foreach( $all_rentals as $v ){
			$rents[ $v["extra_reference"] . $v["store"] ] = $v;
		}
	}
	
	$o = get_occupancy_status();
	$rooms = get_items_categories();
	
	if( ! empty( $all_rooms ) ){
		
		$sn = 0;
		$body .= '<tr>';
		
		foreach( $all_rooms as $sval ){
			$class = "#8eda8e";
			
			//$sval["occupancy_status"] = "";
			$status = ( isset( $o[ $sval["occupancy_status"] ] )?$o[ $sval["occupancy_status"] ]:$sval["occupancy_status"] );
			//$cstatus = ( isset( $c[ $sval["cleaning_status"] ] )?$c[ $sval["cleaning_status"] ]:$sval["cleaning_status"] );
			$cstatus = "";
			
			switch( $sval["occupancy_status"] ){
			case "out_of_order":
			case "faulty":
			case "in_maintenance":
				$class = '#efe19e';
				
				$cstatus .= '<br /><br /><br /><br /><button class="btn btn-xs dark custom-single-selected-record-button" action="?module=&action=property&todo=view_vacant_room_status" override-selected-record="'.$sval["id"].'" title="Check In or Update Property Status"><i class="icon-key"></i> More Info</button>';
			break;
			case "blocked":
				$class = '#dddddd';
				
				$cstatus .= '<br /><br /><br /><br /><button class="btn btn-xs dark custom-single-selected-record-button" action="?module=&action=property&todo=view_vacant_room_status" override-selected-record="'.$sval["id"].'" title="Check In or Update Property Status"><i class="icon-key"></i> More Info</button>';
			break;
			case "ready_for_use":
			case "active":
				$status = 'Vacant';
				$in = 0;
				$g = '';
				$todo = 'view_vacant_property_status';
				$in_date = '';
				
				if( isset( $rents[ $sval["id"] . $sval["location"] ] ) ){
					$in_date = "";
					switch( $rents[ $sval["id"] . $sval["location"] ]["sales_status"] ){
					case "occuppied":
						$status = 'Occuppied Paid';
						$class = '#ff9955';
						
						$in = 1;
						
						$in_date = '<br />' . date( "d-M-Y", doubleval( $rents[ $sval["id"] . $sval["location"] ][ "date" ] ) );
						
						$amount_due = doubleval( $rents[ $sval["id"] . $sval["location"] ][ "amount_due" ] );
						$amount_paid = doubleval( $rents[ $sval["id"] . $sval["location"] ][ "amount_paid" ] );
						
						if(  $amount_paid < $amount_due ){
							$status = 'Occuppied Owing';
							$class = '#f39292';
						}
					break;
					default:
						$status = ucwords( $occuppied_rooms[ $sval["id"] ]["status"] );
						$class = '#ffffff';
						$in_date = date(" d-M-Y", doubleval( $occuppied_rooms[ $sval["id"] ]["checkin_date"] ) );
						
						$todo = 'view_booked_room_status';
					break;
					}
					
					$guest = get_customers_details( array( "id" =>  $rents[ $sval["id"] . $sval["location"] ]["customer"] ) );
					$g = '<strong>'.strtoupper( isset($guest["name"])?$guest["name"]:"" ).'</strong>' . $in_date;
					
				}
				
				if( $in ){
					$cstatus .= '<br />'.$g.'<br /><br /><button class="btn btn-xs red custom-single-selected-record-button" action="?module=&action=property&todo=view_occuppied_room_status" override-selected-record="'.$rents[ $sval["id"] . $sval["location"] ][ "id" ].'" mod="'.$rents[ $sval["id"] . $sval["location"] ]["extra_reference"].'" title="View More Information"><i class="icon-info-sign"></i> More Info</button>';
				}else{
					$cstatus .= '<br />'.$g.'<br /><br /><br /><button class="btn btn-xs green custom-single-selected-record-button" action="?module=&action=property&todo='.$todo.'" override-selected-record="'.$sval["id"].'" title="View More Information"><i class="icon-key"></i> More Info</button>';
				}
				
			break;
			}
			
			$body .= '<td align="center" width="16.66667%" bgcolor="'.$class.'"><strong>' . $sval["description"] . "<br />". strtoupper( isset( $rooms[ $sval["category"] ] )?$rooms[ $sval["category"] ]:"" ) . '</strong><br /><small>'.$status.' '.$cstatus.'</small></td>';
			
			$active1 = "vacant";
			if( $status == 'Occuppied Paid' || $status == 'Occuppied Owing' ){
				$active1 = "occuppied";
			}
			
			if( ! isset( $room_count[ $sval["category"] ][ $active1 ] ) )
				$room_count[ $sval["category"] ] = array( "vacant" => 0, "occuppied" => 0 );
			
			$room_count[ $sval["category"] ][ $active1 ] += 1;
			
			++$sn;
			if( ! ( $sn % 6 ) ){
				$body .= '</tr><tr>';
			}
		}
		
		$body .= '</tr>';
	}
	?>
	<!--
	<tr><td colspan="6">
		<button class="btn btn-sm pull-right red custom-action-button" function-name="group_checkin" skip-title="1" function-class="hotel" function-id="1"><i class="icon-group"></i> Group Check In</button>
		<h4><strong><?php //echo $title; ?></strong></h4></td>
	</tr>
	-->
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
<tfoot>
	<tr>
		<td colspan="6">
			<br />
			<?php
				$total_occuppied = 0;
				$total_vacant = 0;
				if( is_array( $room_count ) && ! empty( $room_count ) ){
					?>
					<table class="table table-bordered" cellspacing="0" style="width:50%; float:left;">
					<thead>
						<tr>
							<th>Room Type</th>
							<th>Occuppied</th>
							<th>Vacant</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach( $room_count as $k => $sval ){
						$total_vacant += $sval["vacant"];
						$total_occuppied += $sval["occuppied"];
						?>
						<tr>
							<td><?php echo ( isset( $rooms[ $k ] )?$rooms[ $k ]:"" ); ?></td>
							<td><?php echo $sval["occuppied"]; ?></td>
							<td><?php echo $sval["vacant"]; ?></td>
							<td><?php echo $sval["vacant"] + $sval["occuppied"]; ?></td>
						</tr>
						<?php
					}
					?>
					<tr class="total-row">
						<td>TOTAL</td>
						<td><?php echo $total_occuppied; ?></td>
						<td><?php echo $total_vacant; ?></td>
						<td><?php echo $total_vacant + $total_occuppied; ?></td>
					</tr>
					</tbody>
					</table>
					<?php
				}
			?>
			<table class="table table-bordered" cellspacing="0" style="width:40%; float:right;">
			<tbody>
				<tr>
					<td colspan="2" align="center">Key</td>
				</tr>
				<tr>
					<td bgcolor="#f39292" width="50%"><strong>OCCUPPIED OWING</strong></td>
					<td bgcolor="#ff9955"><strong>OCCUPPIED PAID</strong></td>
					
				</tr>
				<tr>
					<td bgcolor="#ffffff" width="50%"><strong>BOOKED</strong></td>
					<td bgcolor="#8eda8e"><strong>VACANT</strong></td>
				</tr>
				<tr>
					<td bgcolor="#efe19e" width="50%"><strong>OUT OF ORDER</strong></td>
					<td bgcolor="#dddddd"><strong>BLOCKED</strong></td>
				</tr>
			</tbody>
			</table>
		</td>
	</tr>
</tfoot>
</table>
</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>
</div>