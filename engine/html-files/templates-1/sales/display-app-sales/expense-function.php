<?php 
function __expenses( $data = array(), $type = "" ){
	?>
	<div class=" shopping-cart-table" >
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<thead>
			   <tr>
				  <th>Date</th>
				  <th>Details</th>
				  <th class="r">Amount Due</th>
				  <th class="r">Amount Owed</th>
			   </tr>
			</thead>
			<tbody>
			   <?php
				if( isset( $data ) && is_array( $data ) ){
					$customers = get_customers();
					$class = "";
					
					$date_filter = "d-M-Y";
					if( $type == "year" )$date_filter = "F, Y";
					
					$owing = 0;
					if( $type == "owing" )$owing = 1;
						
					foreach( $data as $sval ){
						if( $owing ){
							$income = $sval["amount_due"];
							if( $income <= $sval["amount_paid"] )continue;
						}
						include "expense-list.php";
					}
				}
			?>
			</tbody>
			</table>
		</div>
		
	</div>
	<?php
}
?>