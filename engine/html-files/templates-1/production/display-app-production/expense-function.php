
<?php 
function __expenses( $data = array(), $type = "" ){
	?>
	<div class=" shopping-cart-table" >
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<thead>
			   <tr>
				  <th>Date</th>
				  <th>Production REF</th>
				  <th class="r">Cost</th>
				  <th class="r">Staff Responsible</th>
			   </tr>
			</thead>
			<tbody>
			   <?php
				if( isset( $data ) && is_array( $data ) ){
					$customers = get_employees();
					$status = get_stock_status();
					$class = "";
					
					$date_filter = "d-M-Y";
					if( $type == "year" )$date_filter = "F, Y";
						
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