<div class="report-table-preview">
<!--<form class="activate-ajax hidden-print" method="post" id="expenditure" action="?action=expenditure&todo=record_expense_batch_payment">-->
<table class="table table-striped table-bordered table-hover" cellspacing="0" id="recent-expenses">
<thead>
<?php
	$body = "";
	
	$date_filter = "";
	if( isset( $data[ 'date_filter' ] ) )
		$date_filter = $data[ 'date_filter' ];
	
	$stores = get_stores();
	$vendor = get_vendors();
	$cat = get_types_of_expenditure();
	$payment_method = get_payment_method();
	
	if( ! isset( $summary ) )
		$summary = 0;
	
	$span = 4;
	
	if( ! empty( $report_data ) ){
		$serial = 0;
		
		foreach( $report_data as $key => $sval ){
			$body .= '<tr>';
				$status = '';
				if( $sval["status"] == 'draft' ){
					$status = '[' . $sval["status"] . ']';
				}
				
				$body .= '<td><strong>'. date( "d-M-Y", doubleval( $sval["date"] ) ) . '</strong><br />' . $sval["description"] . '<br />' . $status . '</td>';
				$body .= '<td>'. ( isset( $cat[ $sval['category_of_expense'] ] )?$cat[ $sval['category_of_expense'] ]:$sval['category_of_expense'] ) . '</td>';
				$body .= '<td>'. ( isset( $vendor[ $sval["vendor"] ] )?$vendor[ $sval["vendor"] ]:$sval["vendor"] ) . '</td>';
				
				
				$body .= '<td>' . number_format( $sval["amount_due"], 2 ) . '</td>';
				$owed = $sval["amount_due"] - $sval["amount_paid"];
				
				$body .= '<td><input type="number" step="any" class="form-control amount_paid" value="' . $owed . '" /></td>';
				
				$body .= '<td>'. ( isset( $payment_method[ $sval["mode_of_payment"] ] )?$payment_method[ $sval["mode_of_payment"] ]:$sval["mode_of_payment"] ) . '</td>';
				$body .= '<td><input type="checkbox" class="checkbox form-control" /></td>';
				
			$body .= '</tr>';
		}
	}
	?>
	<tr>
		<td colspan="8">
			<h4><strong id="e-report-title"><?php echo $title; ?></strong></h4><br /><p><?php echo $subtitle; ?></p>
		</td>
	</tr>	
	

<tr class="hidden-print">
	
	<td>
		<input type="text" required="required" class="form-control" name="description" />
		<input type="hidden" class="form-control" name="id" />
		<input type="hidden" class="form-control" name="date" value="<?php echo date("Y-n-j"); ?>" />
	</td>
	<td>
		<select required="required" class="form-control" name="category_of_expense" >
			<?php
				$vendors = get_types_of_expenditure_grouped();
				if( ( ! empty( $vendors ) ) && is_array( $vendors ) ){
					foreach( $vendors as $key => $val ){
						?>
						<optgroup label="<?php echo $key; ?>">
							<?php foreach( $val as $k => $v ){ ?>
							<option value="<?php echo $k; ?>">
								<?php echo $v; ?>
							</option>
							<?php } ?>
						</optgroup>
						<?php
					}
				}
			?>
		 </select>
	</td>
	<td>
		<select required="required" class="form-control" name="vendor" >
			<?php
				$vendors = get_vendors_supplier();
				if( ( ! empty( $vendors ) ) && is_array( $vendors ) ){
					foreach( $vendors as $key => $val ){
						?>
						<option value="<?php echo $key; ?>">
							<?php echo $val; ?>
						</option>
						<?php
					}
				}
			?>
		 </select>
	</td>
	<td>
		<input type="number" required="required" min="1" step="any" name="amount_due" class="form-control" value="0" />
	</td>
	<td>
		<input type="number" step="any" class="form-control" name="amount_paid" value="0" />
	</td>
	<td>
		<select class="form-control" name="mode_of_payment" >
			<?php
				$vendors = get_payment_method();
				foreach( $vendors as $key => $val ){
					?>
					<option value="<?php echo $key; ?>">
						<?php echo $val; ?>
					</option>
					<?php
				}
			?>
		 </select>
	</td>
	<td>
		<input type="submit" class="btn dark btn-block" value="Add Item" />
	</td>
</tr>
	<tr>
		<th>Date</th>
		<th>Desc.</th>
		<th>Category</th>
		<th>Vendor</th>
		<th>Amount Due</th>
		<th>Amount Paid</th>
		<th>Amount Owed</th>
		<th>Payment Method</th>
	</tr>
	
</thead>
<tbody>
<?php echo $body; ?>
</tbody>
</table>
<!--</form>-->
</div>