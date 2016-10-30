<!-- BEGIN PAGE CONTAINER --> 
<?php 	
	$tmp = "invoice-default.php";
	$cs = "";
	if( isset( $data["show_small_invoice"] ) && $data["show_small_invoice"] ){
		$tmp = "invoice-small.php";
		$cs = "invoice-small";
	}
?>
<div id="invoice-container-wrapper" class="page-container <?php echo $cs; ?>">
	<?php
		include "invoice-css.php"; 
		
		$stamp = "part-payment.png";
		$skip_logo = 0;
		
		$amount_paid = 0;
		if( isset( $data["payment"]["TOTAL_AMOUNT_PAID"] ) ){
			$amount_paid = round( doubleval( $data["payment"]["TOTAL_AMOUNT_PAID"] ) , 2 );
		}
		/*
		if( isset( $data["event"]["amount_paid"] ) ){
			$amount_paid = doubleval( $data["event"]["amount_paid"] );
		}
		*/
		
		$service_tax = 0;
		$service_charge = 0;
		$vat = 0;
		
		if( isset( $data["event"]["vat"] ) && doubleval( $data["event"]["vat"] ) )
			$vat = doubleval( $data["event"]["vat"] );
		
		if( isset( $data["event"]["service_charge"] ) && doubleval( $data["event"]["service_charge"] ) )
			$service_charge = doubleval( $data["event"]["service_charge"] );
		
		if( isset( $data["event"]["service_tax"] ) && doubleval( $data["event"]["service_tax"] ) )
			$service_tax = doubleval( $data["event"]["service_tax"] );
		
		
		$iservice_tax = $service_tax;
		$iservice_charge = $service_charge;
		$ivat = $vat;
		
		$pr = get_project_data();
		
		$support_line = "";
		if( isset( $pr['support_line'] ) )$support_line = $pr['support_line'];
		
		$support_email = "";
		if( isset( $pr['support_email'] ) )$support_email = $pr['support_email'];
		
		$support_addr = "";
		if( isset( $pr['street_address'] ) )$support_addr = $pr['street_address'] . " " . $pr['city'] ." ". $pr['state'];
		
		$support_msg = "";
		
		$store_name = "";
		$branch = "";
		$store = array();
		if( isset( $data['event']["store"] ) && $data['event']["store"] ){
			$store = get_store_details( array( "id" => $data['event']["store"] ) );
			
			if( isset( $store["phone"] ) ){
				//test for sub location
				if( $store["name"] != "." ){ 
					$store1 = get_store_details( array( "id" => $store["name"] ) );
					if( isset( $store1["phone"] ) ){
						$branch = $store["address"];
						$store = $store1;
					}
				}
				$store_name = $store["name"];
				$support_line = $store["phone"];
				$support_addr = $store["address"];
				$support_email = $store["email"];
				$support_msg = $store["comment"];
				
				if( $store_name == "." ){ $store_name = " "; }
			}
		}
		
		$show_buttons = 1;
		if( isset( $data["hide_buttons"] ) && $data["hide_buttons"] )
			$show_buttons = 0;
		
		$backend = 0;
		if( isset( $data["backend"] ) && $data["backend"] )
			$backend = $data["backend"];
		
		$invoice_only = 0;
		if( isset( $data["backend"] ) && $data["backend"] )
			$invoice_only = $data["backend"];
		
		$show_buttons = 0;
		$invoice_only = 0;
		
		$show_image = 0;
		if( isset( $data["show_item_image"] ) && $data["show_item_image"] )
			$show_image = $data["show_item_image"];
		
		$bookings = 0;
		$reference = 0;
		$extra_comment = "";
		if( isset( $data["event"]["reference_table"] ) ){
			switch( $data["event"]["reference_table"] ){
			case "debit_and_credit"	:
				$reference = 1;
				$show_buttons = 0;
				$bookings = 0;
				$data["event"]["comment"] .= "<br /><i>Customer Payment Posted from Accounting</i><br />Transaction Ref: <strong>#" . $data["event"]["reference"] . "</strong>";
			break;
			}
		}
		
		$staff = "";
		if( isset( $data["event"]["payment_method"] ) ){
			switch( $data["event"]["payment_method"] ){
			case "complimentary_staff":
				$staff = $data["event"]["staff_responsible"];
				$data["event"]["staff_responsible"] = $data["event"]["created_by"];
			break;
			}
		}
		
		$price_label = 'S. Price';
		
		$show_owing = 0;
		$sales_label = "Receipt";
		if( isset( $data["event"]["sales_status"] ) ){
			switch( $data["event"]["sales_status"] ){
			case "sales_order":
				$sales_label = "Invoice";
			break;
			case "vacated":
			case "occuppied":
				$sales_label = "Receipt";
				$price_label = 'Rate';
			break;
			default:
				$show_owing = 1;
			break;
			}
		}
		
		$key = "serial_num"; 
		$serial_number = '';
		if( isset( $data["event"][$key] ) ){
			$serial_number = mask_serial_number( $data["event"][$key] , 'S' );
		}
		
		$show_signature = 0;
		if( ! $backend ){
			$show_signature = get_show_signature_in_invoice_settings();
		}
		
		$g_discount_after_tax = get_sales_discount_after_tax_settings();
		$discount = 0;
	?>
	
	<!-- BEGIN CONTAINER -->   
	<div class="container <?php echo $cs; ?>" id="invoice-container">
		<?php include $tmp; ?>
	</div>
	<!-- END CONTAINER -->

</div>
<!-- END PAGE CONTAINER -->  