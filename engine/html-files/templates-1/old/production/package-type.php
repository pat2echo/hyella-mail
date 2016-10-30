<?php 
	$date = 0;
	if( isset( $data["date"] ) && doubleval( $data["date"] ) ){
		$date = doubleval( $data["date"] );
	}
	
	$todo = "book_event_package";
	$hall = 0;
	if( isset( $data["hall"] ) && $data["hall"] ){
		$hall = $data["hall"];
		$todo = "book_event_hall";
		//$todo = "display_other_items_selector";
	}
	
	$others = 0;
	if( isset( $data["others"] ) && $data["others"] ){
		$others = $data["others"];
		$todo = "book_event_others";
	}
	
?>
<?php include dirname( dirname( __FILE__ ) ) . "/globals/event-booking-header.php";  ?>
<h4>-- <?php if( isset( $data["title"] ) )echo $data["title"]; ?> --</h4>
<div class="row">
<?php
	if( $others ){
		?>
		<div class="col-md-12">
		<?php
		if( isset( $data["item_form"] ) && $data["item_form"] )
			echo $data["item_form"];
		?>
		</div>
		<?php
	}else{
		if( isset( $data["packages"] ) && is_array( $data["packages"] ) && ! empty( $data["packages"] ) ){
			foreach( $data["packages"] as $key => $val ){
				$p = get_halls_details( array( "id" => $key ) );
				$price = 0;
				$ref = 0;
				?>
				<div class="col-md-6">
					<div class="well">
						<h3 style="margin-top:0;"><?php echo $val; ?></h3>
						<address>
							<?php $k = "description"; if( isset( $p[$k] ) )echo $p[$k]; ?>
						</address>
						<address>
							Billed at <strong><?php $k = 'price'; if( isset( $p[$k] ) ){ $price = $p[$k]; echo convert_currency( $p[$k] ); } ?> <?php $k = 'unit'; if( isset( $p[$k] ) )echo get_select_option_value( array( "id" => $p[$k], "function_name" => "get_unit_types" ) ); ?></strong><br>
							
							<?php $k = 'refundable_fee'; if( isset( $p[$k] ) && $p[$k] ){ ?>
								Refundable Damage Fee: <strong><?php $ref = $p[$k]; echo convert_currency( $p[$k] ); ?></strong>
							<?php } ?>
						</address>
						
						<?php if( ! $hall ){ ?>
						<p><strong>TOTAL AMOUNT DUE: <?php echo convert_currency( $price + $ref ); ?></strong></p>
						<?php } ?>
						
						<button class="btn btn-lg blue btn-block custom-action-button" function-id="1" function-class="events" function-name="<?php echo $todo; ?>" operator-id="-" month-id="-" budget-id="<?php echo $date; ?>" department-id="<?php echo $key; ?>">Book Now</button>
					</div>
				</div>
				<?php
			}
		}
	}
?>
</div>