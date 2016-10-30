<?php 
	if( isset( $data["event"]["id"] ) ){
		if( isset( $data["other_items"] ) && $data["other_items"] ){
	?>
	<button class="btn btn-lg blue btn-block custom-action-button" function-id="1" function-class="events" function-name="display_other_items_selector" operator-id="-" department-id="-" budget-id="<?php echo $data["event"]["start_date"]; ?>" month-id="<?php echo $data["other_items"]; ?>">Specify Services / Other Items<span class="desc">Specify Services / Other Items such as Chairs, Tables, Canopies etc for use with the selected hall</span></button>
	
	<h3 style="text-align:center;">OR</h3>
	<?php
		}
	?>
	<div class="alert alert-success">
		<?php if( ! ( isset( $data["hide_info"] ) && $data["hide_info"] ) ){ ?>
		<p>Your booking was successful, proceed to print your invoice and make payment</p>
		<?php } ?>
		<br /><strong>Booking Info</strong>
		<hr />
		<h4><?php echo date( "jS F, Y", doubleval( $data["event"]["start_date"] ) ); ?></h4>
		<?php /*
			if( $data["event"]['hall'] == "none" ){
				$es = explode( ":::", $data["event"]["other_requirements"] );
				foreach( $es as $item ){
					echo get_select_option_value( array( "id" => $item , "function_name" => "get_other_requirements" ) ).", ";
				}
			}else{
		?>
		<h5>Hall Price: <?php echo convert_currency( $data["event"]["hall_price"] ); echo " ".get_select_option_value( array( "id" => $data["event"]["hall_price_unit"], "function_name" => "get_unit_types" ) ); ?></h5>
		<?php } */ ?>
		<address>
			<strong><?php echo $data["event"]["organizer_name"]; ?></strong><br />
			<?php echo $data["event"]["organizer_email"]; ?>, <?php echo $data["event"]["organizer_phone"]; ?><br />
			<?php echo $data["event"]["organizer_address"]; ?>
		</address>
		
		<a href="<?php echo frontend( $site_url ); ?>?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>" class="btn green" target="_blank"><i class="icon-print"></i> Print Invoice</a>
		<a href="#" class="custom-single-selected-record-button btn red" action="?action=events&todo=cancel_event" override-selected-record="<?php echo $data["event"]["id"]; ?>" ><i class="icon-trash"></i> Cancel Booking</a>
	</div>
	<?php
	}else{
	?>
	Error Message
	<?php
	}
?>