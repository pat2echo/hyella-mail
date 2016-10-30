
<?php 
function __expenses( $data = array(), $type = "" ){
	?>
	<div class=" shopping-cart-table">
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<thead>
			   <tr>
				  <th>Date</th>
				  <th>Repair Details</th>
				  <th style="text-align:right !important;">Amount Owed</th>
			   </tr>
			</thead>
			<tbody>
			   <?php
				if( isset( $data ) && is_array( $data ) ){
					$class = "";
					$customers = get_customers();
					$status = get_repairs_status();
					
					foreach( $data as $sval ){
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