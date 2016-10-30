<?php
	if( isset( $data["guest_list"] ) && $data["guest_list"] ){
		foreach( $data["guest_list"] as $day => $visitors_today ){
			
			?>
			<h4><?php echo date( "d-M-Y", $day ); ?></h4>
			<ul class="chats">
			<?php
			foreach( $visitors_today as $visitor ){
				?>
				<li class="in" title="<?php echo $visitor[ 'full_name' ]; ?>">
				  <img class="avatar img-responsive" alt="" src="<?php echo $site_url . $visitor[ 'photograph' ]; ?>">
				  <div class="message">
					 <span class="arrow"></span>
					 <a href="#" class="name"><?php echo return_first_few_characters_of_a_string( $visitor[ 'full_name' ] , 25 ); ?></a>
					 <span class="datetime">at 
					 <?php
						if( doubleval( $visitor[ 'approved_start_date_time' ] ) && doubleval( $visitor[ 'approved_start_date_time' ] ) != doubleval( $visitor[ 'proposed_start_date_time' ] ) ){
					?>
					<?php echo date( "d-M-Y", doubleval( $visitor[ 'approved_start_date_time' ] ) ); ?>
					<?php }else{ ?>
					<?php echo date( "d-M-Y", doubleval( $visitor[ 'proposed_start_date_time' ] ) ); ?>
					<?php } ?>
					 </span>
					 <span class="body">
					 <a href="tel:<?php echo $visitor[ 'phone_number' ]; ?>"><?php echo $visitor[ 'phone_number' ]; ?></a>, <a href="mailto:<?php echo $visitor[ 'email' ]; ?>"><?php echo $visitor[ 'email' ]; ?></a>
					 </span>
				  </div>
			   </li>
				<?php
			}
			?>
			</ul>
			<?php
		}
	}
?>