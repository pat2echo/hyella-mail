<?php 
	if( isset( $data["item"] ) && $data["item"] ){
		$sval = $data["item"];
		$cat = get_types_of_expenditure();
		$vendor = get_vendors();
		$payment_method = get_payment_method();
		
		$body = '<tr>';
			$status = '';
			if( $sval["status"] == 'draft' ){
				$status = '[' . $sval["status"] . ']';
			}
			
			$body .= '<td><strong>'. date( "d-M-Y", doubleval( $sval["date"] ) ) . '</strong><br />' . $sval["description"] . '<br />' . $status . '</td>';
			$body .= '<td>'. ( isset( $cat[ $sval['category_of_expense'] ] )?$cat[ $sval['category_of_expense'] ]:$sval['category_of_expense'] ) . '</td>';
			$body .= '<td>'. ( isset( $vendor[ $sval["vendor"] ] )?$vendor[ $sval["vendor"] ]:$sval["vendor"] ) . '</td>';
			
			
			$body .= '<td>' . number_format( $sval["amount_due"], 2 ) . '</td>';
			$owed = $sval["amount_due"] - $sval["amount_paid"];
			
			$body .= '<td><input type="number" step="any" class="form-control" name="amount_paid" value="' . $owed . '" /></td>';
			
			$body .= '<td>'. ( isset( $payment_method[ $sval["mode_of_payment"] ] )?$payment_method[ $sval["mode_of_payment"] ]:$sval["mode_of_payment"] ) . '</td>';
			$body .= '<td><input type="checkbox" class="checkbox form-control" /></td>';
			
		$body .= '</tr>';
		
		echo $body;
	}
?>