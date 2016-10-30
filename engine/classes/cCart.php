<?php
	/**
	 * cart Class
	 *
	 * @used in  				cart Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	cart
	 */

	/*
	|--------------------------------------------------------------------------
	| cart Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cCart{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'cart';
		
		private $associated_cache_keys = array(
			'cart',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		private $table_fields = array(
			'date' => 'cart001',
			'quantity' => 'cart002',
			'cost' => 'cart003',
			'discount' => 'cart004',
			'discount_type' => 'cart005',
			
			'amount_due' => 'cart006',
			'amount_paid' => 'cart007',
			'balance' => 'cart011',
			'payment_method' => 'cart008',
			'customer' => 'cart009',
			'store' => 'cart010',
			
			'comment' => 'cart012',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 0,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				
			'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
				//'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
			
			'show_details' => 1,				//Determines whether or not to show details
			'show_serial_number' => 1,			//Determines whether or not to show serial number
			
			'show_verification_status' => 0,	//Determines whether or not to show verification status
			'show_creator' => 0,				//Determines whether or not to show record creator
			'show_modifier' => 0,				//Determines whether or not to show record modifier
			'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
		);
			
		function __construct(){
			
		}
	
		function cart(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError(000017);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = '_language_initialization';
					$err->additional_details_of_error = 'no language file';
					return $err->error();
				}
			}
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_shopping_cart':
			case 'display_shopping_cart2':
			case 'display_sales_order_cart':
			case 'display_sales_order_cart2':
				$returned_value = $this->_display_shopping_cart();
			break;
			case 'display_inventory_picking_slips':
			case 'display_app_transfer_inventory':
			case 'display_material_utilization_manifest':
			case 'display_production_cart':
				$returned_value = $this->_display_production_cart();
			break;
			case "checkout":
				$returned_value = $this->_checkout();
			break;
			case 'save_receive_goods':
			case "save_general_picking_slip":
			case "save_material_transfer_manifest":
			case "save_material_utilization_manifest":
			case "save_production_manifest":
			case "save_sales_order_picking_slip":
				$returned_value = $this->_save_production_manifest();
			break;
			case "display_record_payment":
				$returned_value = $this->_display_record_payment();
			break;
			case "save_record_payment":
				$returned_value = $this->_save_record_payment();
			break;
			case 'save_returned_items':
				$returned_value = $this->_save_returned_items();
			break;
			case 'display_cashier_screen_all_stores':
			case 'display_cashier_screen':
				$returned_value = $this->_display_cashier_screen();
			break;
			case 'display_purchase_order':
			case 'display_purchase_order2':
			case 'display_supplier_invoice':
			case 'display_goods_received_note':
			case 'display_general_goods_received_note':
				$returned_value = $this->_display_purchase_order();
			break;
			case 'display_vendor_payments':
			case 'display_vendor_payments_all_stores':
				$returned_value = $this->_display_vendor_payments();
			break;
			case 'save_general_goods_received_as_seperate_doc':
			case 'save_supplier_goods_received_as_seperate_doc':
			case 'save_supplier_invoice_as_seperate_doc':
			case 'save_purchase_order_as_seperate_doc':
			case 'save_purchase_order':
				$returned_value = $this->_save_purchase_order();
			break;
			case 'save_record_vendor_payment':
				$returned_value = $this->_save_record_vendor_payment();
			break;
			case 'display_receive_goods_view':
				$returned_value = $this->_display_receive_goods_view();
			break;
			}
			
			return $returned_value;
		}
		
		private function _display_receive_goods_view(){
			$this->class_settings[ 'data' ][ "vendors" ] = get_vendors();
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-receive-goods-view' );
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _save_record_vendor_payment(){
			if( ! ( isset( $_POST[ "id" ] ) && $_POST[ "id" ] && isset( $_POST[ "amount_paid" ] ) && isset( $_POST[ "mode_of_payment" ] ) ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No Record Found</h4>Please select a record before you update';
				return $err->error();
			}
			
			$id = $_POST[ "id" ];
			$amount_paid = doubleval( $_POST["amount_owed"] );
			$_POST[ "amount_paid" ] = $amount_paid;
			
			$this->class_settings[ 'return_after_save' ] = 1;
			
			$expenditure = new cExpenditure();
			$expenditure->class_settings = $this->class_settings;
			$expenditure->class_settings[ 'current_record_id' ] = $id;
			$expenditure->class_settings["action_to_perform"] = "get_expenditure";
			$record = $expenditure->expenditure();
			
			$expenditure_payment = new cExpenditure_payment();
			$expenditure_payment->class_settings = $this->class_settings;
			$expenditure_payment->class_settings['new_id'] = 1;
			$expenditure_payment->class_settings['expenditure_id'] = $id;
			$expenditure_payment->class_settings["reference_table"] = $expenditure->table_name;
			
			$expenditure_payment->class_settings["amount_paid"] = $amount_paid;
			$expenditure_payment->class_settings['payment_method'] = $_POST[ "mode_of_payment" ];
			$expenditure_payment->class_settings['vendor'] = isset( $_POST[ "vendor" ] )?$_POST[ "vendor" ]:$record[ "vendor" ];
			$expenditure_payment->class_settings['store'] = isset( $_POST[ "store" ] )?$_POST[ "store" ]:$record[ "store" ];
			$expenditure_payment->class_settings['comment'] = isset( $_POST[ "description" ] )?$_POST[ "description" ]:"Part Payment Of Expenditure";
			$expenditure_payment->class_settings['extra_reference'] = $record["production_id"];
			
			$expenditure_payment->class_settings['staff_responsible'] = $this->class_settings["user_id"];
			
			$expenditure_payment->class_settings["action_to_perform"] = 'save_expenditure_payment';
			$saved = $expenditure_payment->expenditure_payment();
			
			$expenditure->class_settings["do_not_check_cache"] = 1;
			$expenditure->expenditure();
			
			if( $saved ){
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Payment Successfully Posted</h4>';
				$return = $err->error();
				
				$return["status"] = "new-status";
				unset( $return["html"] );
				$return["javascript_functions"] = array( 'nwRecordPayment.search' );
			}
			
			return $return;
		}
		
		private function _display_vendor_payments(){
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_vendor_payments_all_stores':
				$this->class_settings[ 'data' ]['all_stores'] = 1;
			break;
			}
			
			$this->class_settings[ 'data' ]['vendors'] = get_vendors();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-vendor-payments' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _save_purchase_order(){
			//print_r( $_POST["json"] );exit;
			if( isset( $_POST["json"]["item"] ) && is_array( $_POST["json"]["item"] ) && ! empty( $_POST["json"]["item"] ) ){
				$cart_items = $_POST["json"];
				
				if( ! $cart_items["vendor"] ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>No Vendor Specified</h4>Please you must specify a vendor';
					return $err->error();
				}
				
				$status = "";
				$desc = "Purchase Order: " . $cart_items[ 'comment' ];
				$new_id = get_new_id();
				$production_id = $new_id;
				
				switch( $this->class_settings["action_to_perform"] ){
				case 'save_purchase_order_as_seperate_doc':
					$status = "draft";
					$cart_items[ 'sales_status' ] = $status;
					if( $cart_items[ 'comment' ] )$desc = $cart_items[ 'comment' ];
					else $desc = "Purchase Order";
				break;
				case 'save_general_goods_received_as_seperate_doc':
					$status = "stock";
					$cart_items[ 'sales_status' ] = $status;
					if( $cart_items[ 'comment' ] )$desc = $cart_items[ 'comment' ];
					else $desc = "Goods Restocked";
				break;
				case 'save_supplier_invoice_as_seperate_doc':
					$status = "update-supplier-invoice";
					$cart_items[ 'sales_status' ] = $status;
					if( $cart_items[ 'comment' ] )$desc = $cart_items[ 'comment' ];
					else $desc = "Supplier Invoice";
					
					if( isset( $cart_items["reference"] ) && $cart_items["reference"] ){
						//create new expense but retain inventory
						$production_id = $cart_items["reference"];
					}
				break;
				case 'save_supplier_goods_received_as_seperate_doc':
					$status = "update-supplier-goods";
					$cart_items[ 'sales_status' ] = $status;
					if( $cart_items[ 'comment' ] )$desc = $cart_items[ 'comment' ];
					else $desc = "Goods Received Note";
					
					if( isset( $cart_items["reference"] ) && $cart_items["reference"] ){
						//create new expense but retain inventory
						$production_id = $cart_items["reference"];
					}
				break;
				}
				
				$expenditure = new cExpenditure();
				
				//inventory
				$inventory = new cInventory();
				$inventory->class_settings = $this->class_settings;
				$inventory->class_settings["production_items"] = $cart_items["item"];
				
				$inventory->class_settings[ 'source' ] = $cart_items["vendor"];
				$inventory->class_settings[ 'status' ] = $cart_items[ 'sales_status' ];
				$inventory->class_settings[ 'staff_responsible' ] = ( $cart_items[ 'staff_responsible' ] )?$cart_items[ 'staff_responsible' ]:$this->class_settings["user_id"];
				
				$inventory->class_settings[ "production_id" ] = $production_id;
				$inventory->class_settings[ "store" ] = $cart_items[ 'store' ];
				$inventory->class_settings[ "comment" ] = "Purchase Order: " . $cart_items[ 'comment' ];
				$inventory->class_settings[ "transfer" ] = 1;
				$inventory->class_settings[ "reference_table" ] = $expenditure->table_name;
				
				$inventory->class_settings["action_to_perform"] = "save_produced_goods";
				$goods_transfer = $inventory->inventory();
				
				switch( $cart_items[ 'sales_status' ] ){
				case "update-supplier-invoice":
					$cart_items[ 'sales_status' ] = "pending";
					
					if( isset( $cart_items["reference"] ) && $cart_items["reference"] ){
						$production_id = $new_id;
						$cart_items["reference_table"] = $expenditure->table_name;
					}
				break;
				case "update-supplier-goods":
					$cart_items[ 'sales_status' ] = "stocked";
					
					if( isset( $cart_items["reference"] ) && $cart_items["reference"] ){
						$production_id = $new_id;
						$cart_items["reference_table"] = $expenditure->table_name;
					}
				break;
				}
				
				if( $goods_transfer ){
					//capture p.o as expenditure
					$this->class_settings["source"] = $this->table_name;
					$this->class_settings["production_id"] = $production_id;
					$this->class_settings["production"] = array(
						"staff_in_charge" => ( $cart_items[ 'staff_responsible' ] )?$cart_items[ 'staff_responsible' ]:$this->class_settings["user_id"],
						"amount_due" => ( $cart_items["total_amount_due"] )?$cart_items["total_amount_due"]:$cart_items["amount_due"],
						"amount_paid" => $cart_items["amount_paid"],
						"date" => date( "Y-m-d" ),
						"vendor" => $cart_items["vendor"],
						"description" => $desc,
						"category_of_expense" => 'purchase_order',
						"store" => $cart_items[ 'store' ],
						"status" => $cart_items[ 'sales_status' ],
						"mode_of_payment" => isset( $cart_items[ 'payment_method' ] )?$cart_items[ 'payment_method' ]:"",
						
						"percentage_discount" => isset( $cart_items[ 'percentage_discount' ] )?$cart_items[ 'percentage_discount' ]:"",
						"tax" => isset( $cart_items[ 'tax' ] )?$cart_items[ 'tax' ]:"",
						
						"production_id" => isset( $cart_items["reference"] )?$cart_items["reference"]:"",
						"reference_table" => isset( $cart_items["reference_table"] )?$cart_items["reference_table"]:"",
					);
					
					$expenditure->class_settings = $this->class_settings;
					$expenditure->class_settings["action_to_perform"] = "save_expenditure";
					$r = $expenditure->expenditure();
					
					if( isset( $r["saved_record_id"] ) && $r["saved_record_id"] ){
						$_POST["id"] = $r["saved_record_id"];
					}
					
					$expenditure->class_settings["action_to_perform"] = "view_invoice_app1";
					$return = $expenditure->expenditure();
					
					if( isset( $return['html_prepend_selector'] )&& $return['html_prepend_selector'] == "#dash-board-main-content-area" ){
						$return["javascript_functions"][] = "nwCart.emptyCart";
					}
					
				}
				
				return $return;
			}
		}
		
		private function _display_purchase_order(){
			//get category for purchased items & raw materials
			$this->class_settings[ 'data' ]['categories'] = get_items_categories_grouped_purchased_goods();
			//$this->class_settings[ 'data' ]['categories'] = get_items_categories_grouped_goods();
			//$this->class_settings[ 'data' ]['categories'] = get_items_categories();
			
			/*
			$store = "";
			if( isset( $_GET["store"] ) && $_GET["store"] ){
				$store = $_GET["store"];
				$this->class_settings[ 'store' ] = $store;
			}
			*/
			$supplier_invoice = 0;
			$supplier_goods_received = 0;
			$general_goods_received = 0;
			
			$this->class_settings[ 'purchased_items_only' ] = 1;
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_supplier_invoice':
				$supplier_invoice = 1;
			break;
			case 'display_goods_received_note':
				$supplier_goods_received = 1;
			break;
			case 'display_general_goods_received_note':
				unset( $this->class_settings[ 'purchased_items_only' ] );
				$this->class_settings[ 'produced_items_only' ] = 1;
				$general_goods_received = 1;
			break;
			}
			
			$inventory = new cInventory();
			$inventory->class_settings = $this->class_settings;
			$inventory->class_settings[ 'action_to_perform' ] = 'get_all_inventory';
			$this->class_settings[ 'data' ]['stocked_items'] = $inventory->inventory();
			
			if( $general_goods_received ){
				$this->class_settings[ 'data' ]['vendors'] = get_vendors();
				//$this->class_settings[ 'data' ]['vendors'] = get_vendors_factory();
			}else{
				$this->class_settings[ 'data' ]['vendors'] = get_vendors_supplier();
			}
			
			$this->class_settings[ 'data' ]['staff_responsible'] = get_employees();
			$this->class_settings[ 'data' ]['discount'] = get_discount();
			$this->class_settings[ 'data' ]['supplier_invoice'] = $supplier_invoice;
			$this->class_settings[ 'data' ]['supplier_goods_received'] = $supplier_goods_received;
			$this->class_settings[ 'data' ]['general_goods_received'] = $general_goods_received;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-purchase-order' );
			$returning_html_data = $this->_get_html_view();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_supplier_invoice':
			case 'display_purchase_order2':
			case 'display_goods_received_note':
			case 'display_general_goods_received_note':
				$stores = new cStores();
				$stores->class_settings = $this->class_settings;
				$stores->class_settings[ 'action_to_perform' ] = 'get_stores_list';
				$s = $stores->stores();
				
				if( isset( $s["html_replacement"] ) ){
					$returning_html_data = $s["html_replacement"] . $returning_html_data;
				}
			break;
			}
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _display_cashier_screen(){
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_cashier_screen_all_stores':
				$this->class_settings[ 'data' ]['all_stores'] = 1;
			break;
			}
			
			$this->class_settings[ 'data' ]['customers'] = get_customers();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-cashier-screen' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _save_returned_items(){
			if( ! ( isset( $_POST[ "quantity_returned" ] ) && is_array( $_POST[ "quantity_returned" ] ) && ! empty( $_POST[ "quantity_returned" ] ) ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No Quantity Specified</h4>Please specify quantities that were returned';
				return $err->error();
			}
			
			$this->class_settings[ "quantities_returned" ] = $_POST[ "quantity_returned" ];
			
			$sales_items = new cSales_items();
			$sales_items->class_settings = $this->class_settings;
			$sales_items->class_settings["action_to_perform"] = 'update_quantities_purchased';
			$r = $sales_items->sales_items();
			
			unset( $r["html"] );
			$r[ "status" ] = "new-status";
			$r[ "javascript_functions" ] = array( 'nwRecordPayment.search', 'nwRecordPayment.clear' );
			
			return $r;
		}
		
		private function _save_record_payment(){
			if( ! ( isset( $_POST[ "id" ] ) && $_POST[ "id" ] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No Record Found</h4>Please select a record before you update';
				return $err->error();
			}
			
			$sales_id = $_POST[ "id" ];
			
			$sales = new cSales();
			
			//check for amount paid
			if( isset( $_POST["amount_paid"] ) && doubleval( $_POST["amount_paid"] ) ){
				//record payment
				$payment = new cPayment();
				$payment->class_settings = $this->class_settings;
				$payment->class_settings["action_to_perform"] = 'save_payment';
				
				$payment->class_settings["reference_table"] = ( isset( $_POST["reference_table"] ) && $_POST["reference_table"] )?$_POST["reference_table"]:$sales->table_name;
				$payment->class_settings["extra_reference"] = ( isset( $_POST["extra_reference"] ) )?$_POST["extra_reference"]:"";
				
				$payment->class_settings["amount_paid"] = $_POST["amount_paid"];
				$payment->class_settings["sales_id"] = $sales_id;
				$payment->class_settings["comment"] = $_POST["comment"];
				$payment->class_settings["payment_method"] = $_POST["payment_method"];
				$payment->class_settings["staff_responsible"] = ( isset( $_POST["staff_responsible"] )?$_POST["staff_responsible"]:$this->class_settings["user_id"] );
				$payment->class_settings["store"] = ( isset( $_POST["store"] )?$_POST["store"]:"" );
				$payment->class_settings["customer"] = ( isset( $_POST["customer"] )?$_POST["customer"]:"" );
				
				$payment->payment();
			}
			
			//update sales
			$_POST[ "id" ] = $sales_id;
			$this->class_settings["update_fields"] = $_POST;
			
			
			$sales->class_settings = $this->class_settings;
			$sales->class_settings["action_to_perform"] = 'update_table_field';
			$r = $sales->sales();
			
			unset( $r["html"] );
			$r["status"] = "new-status";
			$r["javascript_functions"] = array( 'nwRecordPayment.search', 'nwRecordPayment.clear' );
			
			return $r;
		}
		
		private function _save_production_manifest(){
			
			if( isset( $_POST["json"]["item"] ) && is_array( $_POST["json"]["item"] ) && ! empty( $_POST["json"]["item"] ) ){
				$cart_items = $_POST["json"];
				
				$production_id = get_new_id();
				$picking_slips = 0;
				
				switch( $this->class_settings["action_to_perform"] ){
				case "save_sales_order_picking_slip":
					$picking_slips = "sales-order";
				break;
				case "save_general_picking_slip1":
					$picking_slips = "materials-utilized";
				break;
				case 'save_receive_goods':
					$inventory = new cInventory();
					$inventory->class_settings = $this->class_settings;
					$inventory->class_settings["production_items"] = $cart_items["item"];
					$inventory->class_settings["production_id"] = $cart_items["id"];
					
					$inventory->class_settings[ 'status' ] = "update-goods-received";
					$inventory->class_settings[ 'staff_responsible' ] = $cart_items[ 'staff_responsible' ];
					$inventory->class_settings[ "comment" ] = $cart_items["description"];
					
					$inventory->class_settings["action_to_perform"] = "save_produced_goods";
					$inventory->inventory();
					
					//
					$_POST["id"] = $cart_items["id"];
					
					$expenditure = new cExpenditure();
					$expenditure->class_settings = $this->class_settings;
					$expenditure->class_settings["action_to_perform"] = "update_stock_status_direct_only";
					return $expenditure->expenditure();
				break;
				case "save_material_transfer_manifest":
					if( isset( $cart_items[ 'factory' ] ) && $cart_items[ 'factory' ] && $cart_items[ 'factory' ] == $cart_items["store"] ){
						$err = new cError(010014);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = '<h4>Invalid Destination</h4>You cannot transfer items to the same location';
						return $err->error();
					}
				break;
				}
				
				$production_items = new cProduction_items();
				$production_items->class_settings = $this->class_settings;
				
				$production_items->class_settings["production_id"] = $production_id;
				$production_items->class_settings["production_items"] = $cart_items["item"];
				
				$production_items->class_settings["action_to_perform"] = "save_production_items";
				$production_items->production_items();
				
				$production = new cProduction();
				$this->class_settings[ "reference_table" ] = $production->table_name;
				
				switch( $this->class_settings["action_to_perform"] ){
				case "save_general_picking_slip":
				case "save_sales_order_picking_slip":
				case "save_material_utilization_manifest":
				break;
				case "save_material_transfer_manifest":
					$cart_items[ 'stock_status' ] = 'materials-transfer';
					
					$inventory = new cInventory();
					$inventory->class_settings = $this->class_settings;
					$inventory->class_settings["production_items"] = $cart_items["item"];
					
					$inventory->class_settings[ 'source' ] = $cart_items["store"];
					$inventory->class_settings[ 'status' ] = $cart_items[ 'stock_status' ];
					$inventory->class_settings[ 'staff_responsible' ] = $cart_items[ 'staff_responsible' ];
					
					$inventory->class_settings[ "production_id" ] = $production_id;
					$inventory->class_settings[ "inventory" ] = $cart_items["item"];
					$inventory->class_settings[ "store" ] = $cart_items[ 'factory' ];
					$inventory->class_settings[ "comment" ] = "Transfer of Stock";
					$inventory->class_settings[ "transfer" ] = 1;
					
					$inventory->class_settings["action_to_perform"] = "save_produced_goods";
					$goods_transfer = $inventory->inventory();
				break;
				default:
					$inventory = new cInventory();
					$inventory->class_settings = $this->class_settings;
					$inventory->class_settings["production_items"] = $cart_items["item"];
					
					$inventory->class_settings[ 'source' ] = $cart_items[ 'factory' ];
					$inventory->class_settings[ 'status' ] = $cart_items[ 'stock_status' ];
					$inventory->class_settings[ 'staff_responsible' ] = $cart_items[ 'staff_responsible' ];
					$inventory->class_settings[ 'expiry_date' ] = convert_date_to_timestamp( $cart_items["expiry_date"] );
					$inventory->class_settings[ "production_id" ] = $production_id;
					$inventory->class_settings[ "inventory" ] = $cart_items["item"];
					$inventory->class_settings[ "store" ] = $cart_items["store"];
					$inventory->class_settings[ "comment" ] = "Goods Produced";
					
					if( $cart_items["quantity_goods"] )
						$inventory->class_settings[ "cost_price" ] = ( $cart_items["total_cost"] + $cart_items["extra_cost"] ) / $cart_items["quantity_goods"];
					
					$inventory->class_settings["action_to_perform"] = "save_produced_goods";
					$goods_produced = $inventory->inventory();
				break;
				}
				
				$production->class_settings = $this->class_settings;
				
				if( isset( $cart_items["reason"] ) ){
					switch( $cart_items["reason"] ){
					case "marketing-expense":
						$cart_items["account"] = 'marketing_expense';
						$cart_items["stock_status"] = "materials-utilized";
					break;
					}
				}
				
				if( isset( $goods_produced ) ){
					$production->class_settings["goods_produced"] = $goods_produced;
				}else{
					if( isset( $goods_transfer ) ){
						$cart_items["stock_status"] = "materials-transfer";
					}else{
						$cart_items["stock_status"] = "materials-utilized";
						
						if( isset( $cart_items["reason"] ) && $cart_items["reason"] == "damage" ){
							$cart_items["stock_status"] = "damaged-materials";
						}
						$cart_items["factory"] = $cart_items["store"];
					}
				}
				
				if( $picking_slips ){
					$cart_items["stock_status"] = $picking_slips;
					if( isset( $_SESSION["store"] ) && $_SESSION["store"] ){
						$cart_items["store"] = $_SESSION["store"];
					}
					$cart_items["factory"] = $cart_items["store"];
				}
				
				$production->class_settings["production_id"] = $production_id;
				$production->class_settings["production"] = $cart_items;
				$production->class_settings["action_to_perform"] = "save_production_and_return_receipt";
				return $production->production();
				
			}
		}
		
		private function _checkout(){
			
			if( isset( $_POST["json"]["item"] ) && is_array( $_POST["json"]["item"] ) && ! empty( $_POST["json"]["item"] ) ){
				$cart_items = $_POST["json"];
				$add_payment = 0;
				$sales_id = get_new_id();
				
				$error = '';
				if( isset( $cart_items["sales_status"] ) && $cart_items["sales_status"] == "sales_order" ){
					if( ! ( isset( $cart_items["customer"] ) && $cart_items["customer"] ) ){
						$error = '<h4>Specify Customer</h4>When raising <strong>Sales Order</strong> You must specify the customer';
					}
				}
				
				if( $error ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = $error;
					return $err->error();
				}
					
				switch( $cart_items["payment_method"] ){
				case "complimentary_staff":
					if( ! ( isset( $cart_items["staff_responsible"] ) && $cart_items["staff_responsible"] ) ){
						$err = new cError(010014);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = '<h4>Please Select A Staff</h4>When using <strong>Complimentary Staff Payment</strong> You must specify the staff';
						return $err->error();
					}
				break;
				case 'charge_from_deposit':
					$error = "";
					if( ! ( isset( $cart_items["payment_option"] ) && $cart_items["payment_option"] ) ){
						$error = '<h4>Select Payment Option</h4>When using <strong>Charge from Deposit</strong> You must specify the account (payment option) where the money will be transfered to';
					}
					if( ! ( isset( $cart_items["customer"] ) && $cart_items["customer"] ) ){
						$error = '<h4>Select Customer</h4>When using <strong>Charge from Deposit</strong> You must specify the customer deposit account to bill';
					}
					if( ! ( isset( $cart_items["amount_paid"] ) && doubleval( $cart_items["amount_paid"] ) ) ){
						$error = '<h4>Invalid Amount to Pay</h4>Please specify an amount to pay';
					}
					if( class_exists("cCustomer_deposits") ){
						if( ! $error ){
							//get customer balance
							$customer_deposits = new cCustomer_deposits();
							$customer_deposits->class_settings = $this->class_settings;
							
							$customer_deposits->class_settings["payment_method"] = isset( $cart_items["payment_option"] )?$cart_items["payment_option"]:"";
							$customer_deposits->class_settings["comment"] = ( isset( $cart_items["comment"] ) && $cart_items["comment"] )?$cart_items["comment"]:"Payment during Sales";
							$customer_deposits->class_settings["store"] = isset( $cart_items["store"] )?$cart_items["store"]:"";
							$customer_deposits->class_settings["reference_table"] = "sales";
							$customer_deposits->class_settings["reference"] = $sales_id;
							$customer_deposits->class_settings["customer"] = $cart_items["customer"];
							$customer_deposits->class_settings["amount_paid"] = doubleval( $cart_items["amount_paid"] );
							
							$customer_deposits->class_settings["action_to_perform"] = "charge_customer_from_deposit";
							$r = $customer_deposits->customer_deposits();
							
							if( ! ( isset( $r['saved_record_id'] ) && $r['saved_record_id'] ) ){
								return $r;
							}
							$acc = isset( $cart_items["payment_option"] )?$cart_items["payment_option"]:"";
							if( ! $acc ){
								$a = __map_financial_accounts();
								if( isset( $a["charge_from_deposit"] ) )$acc = $a["charge_from_deposit"];
							}
							$cart_items["payment_method"] = $acc;
						}
					}else{
						$error = '<h4>Unavailable Option</h4><strong>Charge from Deposit Option</strong> is not included in your package. Please contact your application vendor';
					}
					
					if( $error ){
						$err = new cError(010014);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = $error;
						return $err->error();
					}
				break;
				case "complimentary":
				case "charge_to_room":
					//check for room booking number
					$error  = 1;
					if( isset( $cart_items["room"] ) && $cart_items["room"] ){
						$room = "";
						$rooms = get_hotel_rooms();
						$pm = get_payment_method();
						if( isset( $rooms[ $cart_items["room"] ] ) ){
							$room = $rooms[ $cart_items["room"] ];
						}
						
						$error  = 0;
						if( class_exists("cHotel_checkin") ){
							$this->class_settings[ 'room_id' ] = $cart_items["room"];
							
							$hotel_checkin = new cHotel_checkin();
							$hotel_checkin->class_settings = $this->class_settings;
							$hotel_checkin->class_settings[ 'action_to_perform' ] = 'get_room_checkin_details';
							$t = $hotel_checkin->hotel_checkin();
							
							if( isset( $t["main_guest"] ) && isset( $t["booking_ref"] ) && isset( $t["id"] ) ){
								$cart_items["comment"] .= ( isset( $pm[ $cart_items["payment_method"] ] )?$pm[ $cart_items["payment_method"] ]:$cart_items["payment_method"] ) . " ".$room." #" . $t["booking_ref"];
								$cart_items["customer"] = $t["main_guest"];
								$cart_items["customer_name"]  = "";
								
								$cart_items["extra_reference"]  = $t["id"];
							}else{
								$error = 1;
							}
						}
					}
					
					if( $error ){
						$err = new cError(010014);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = '<h4>No Guest Found in Room</h4>When using <strong>Complimentary Payment</strong> or <strong>Charge to Room Payment</strong>.<br /> You must specify the room where the guest is resident';
						return $err->error();
					}
					$add_payment = $cart_items["comment"];
				break;
				}
				
				switch( $cart_items["payment_method"] ){
				case "complimentary":
				case "complimentary_staff":
					$cart_items["discount"] = $cart_items["amount_due"];
					$cart_items["amount_paid"] = 0;
				break;
				}
				
				$sales_items = new cSales_items();
				$sales_items->class_settings = $this->class_settings;
				
				$sales_items->class_settings["sales_id"] = $sales_id;
				$sales_items->class_settings["sales_items"] = $cart_items["item"];
				
				$sales_items->class_settings["action_to_perform"] = "save_sales_items";
				$sales_items->sales_items();
				
				//check for new customer
				$new_customer = "";
				if( $cart_items["customer_name"] ){
					//add customer
					$customer = new cCustomers();
					$customer->class_settings = $this->class_settings;
					
					$customer->class_settings["customer_phone"] = $cart_items["customer_phone"];
					$customer->class_settings["customer_name"] = $cart_items["customer_name"];
					$customer->class_settings["action_to_perform"] = "save_new_customer";
					$cart_items["customer"] = $customer->customers();
					
					if( $cart_items["customer"] ){
						$new_customer = '<option value="'.$cart_items["customer"].'">'.ucwords( $customer->class_settings["customer_name"] ).' ('.$customer->class_settings["customer_phone"].')</option>';	
					}
				}
				
				$sales = new cSales();
				/*
				if( isset( $cart_items["amount_paid"] ) && doubleval( $cart_items["amount_paid"] ) ){
					//capture payment				
					$payment = new cPayment();
					$payment->class_settings = $this->class_settings;
					$payment->class_settings["sales_id"] = $sales_id;
					$payment->class_settings["reference_table"] = $sales->table_name;
					
					$payment->class_settings["amount_paid"] = $cart_items["amount_paid"];
					$payment->class_settings['payment_method'] = isset( $cart_items['payment_method'] )?$cart_items['payment_method']:"";
					$payment->class_settings['customer'] = isset( $cart_items["customer"] )?$cart_items["customer"]:"";
					$payment->class_settings['store'] = isset( $cart_items["store"] )?$cart_items["store"]:"";
					$payment->class_settings['comment'] = ( ( $add_payment )?$add_payment:"Payment On Checkout" );
					
					$payment->class_settings["action_to_perform"] = 'save_payment';
					$payment->payment();
				}
				*/
				
				$sales->class_settings = $this->class_settings;
				$sales->class_settings["sales_id"] = $sales_id;
				$sales->class_settings["sales"] = $cart_items;
				$sales->class_settings["action_to_perform"] = "save_sales_and_return_receipt";
				$return = $sales->sales();
				
				if( $new_customer ){
					$return["html_append"] = $new_customer;
					$return["html_append_selector"] = 'select[name="customer"]';
				}
				
				return $return;
			}
			
			$error = '<h4>No Items Selected</h4>Specify Items to Order';
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $error;
			return $err->error();
		}
		
		private function _display_shopping_cart(){
			
			$key = md5('ucert'.$_SESSION['key']);
			$allow_manage_discount = 0;
			if( isset($_SESSION[$key]) ){
				$user_details = $_SESSION[$key];
				$user_info = $user_details;
				//get access_roles
				$super = 0;
				
				$access = array();
				if( isset( $user_info["privilege"] ) && $user_info["privilege"] ){
					
					if( $user_info["privilege"] == "1300130013" ){
						$super = 1;
					}else{
						$functions = get_access_roles_details( array( "id" => $user_info["privilege"] ) );
						
						if( isset( $functions[ $user_info["privilege"] ]["accessible_functions"] ) ){
							$a = explode( ":::" , $functions[ $user_info["privilege"] ]["accessible_functions"] );
							if( is_array( $a ) && $a ){
								foreach( $a as $k => $v ){
									$access[ $v ] = $v;
								}
							}
						}
					}
				}
				
				$key = "11287957724"; //apply discounts during sale
				if( ( isset( $access[ $key ] ) ||  $super ) ){
					$allow_manage_discount = 1;
				}
			}
			
			$this->class_settings[ 'data' ]['categories'] = get_items_categories_grouped_goods();
			//$this->class_settings[ 'data' ]['categories'] = get_items_categories();
			$store = "";
			if( isset( $_GET["store"] ) && $_GET["store"] ){
				$store = $_GET["store"];
				$this->class_settings[ 'store' ] = $store;
			}
			if( get_single_store_settings() ){
				unset( $this->class_settings[ 'store' ] );
			}
			
			$this->class_settings[ 'sold_items_only' ] = 1;
			
			$inventory = new cInventory();
			$inventory->class_settings = $this->class_settings;
			$inventory->class_settings[ 'action_to_perform' ] = 'get_all_inventory';
			$this->class_settings[ 'data' ]['stocked_items'] = $inventory->inventory();
			
			//$this->class_settings[ 'data' ]['discount'] = get_discount();
			//$this->class_settings[ 'data' ]['discount_type'] = get_discount_type_settings();
			//$this->class_settings[ 'data' ]['manage_discount'] = get_discount_management_during_sale_settings();
			//$this->class_settings[ 'data' ]['backdate_sales'] = get_ability_to_back_date_sales_settings();
			
			$this->class_settings[ 'data' ]['allow_manage_discount'] = $allow_manage_discount;
			$this->class_settings[ 'data' ]['customers'] = get_customers();
			$this->class_settings[ 'data' ]['staff_responsible'] = get_employees();
			$this->class_settings[ 'data' ]['discount'] = get_discount();
			
			$this->class_settings[ 'data' ]["surcharge"] = get_general_settings_value_array( 
				array( 
					"key" => array(
						"VAT" => 0,
						"SERVICE CHARGE" => 0,
						"SERVICE TAX" => 0,
						"HIDE RESERVE ITEMS" => 0,
						"DISABLE ITEM DISCOUNT" => 0,
						"SHOW CUSTOMER CHANGE DURING PAYMENT" => 0,
					),
					"table" => "sales" 
				)
			);
			
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_sales_order_cart':
			case 'display_sales_order_cart2':
				$this->class_settings[ 'data' ]["sales_order"] = 1;
			break;
			}
			
			//$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-shopping-cart' );
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-shopping-cart-advance' );
			
			if( defined("HYELLA_PACKAGE") ){
				switch( HYELLA_PACKAGE ){
				case "hotel":
					$this->class_settings[ 'data' ]['rooms'] = get_hotel_rooms();
				break;
				case "jewelry":
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-shopping-cart-jewelry' );
				break;
				}
			}
			$returning_html_data = $this->_get_html_view();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_shopping_cart2':
			case 'display_sales_order_cart2':
				$stores = new cStores();
				$stores->class_settings = $this->class_settings;
				$stores->class_settings[ 'action_to_perform' ] = 'get_stores_list';
				$s = $stores->stores();
				
				if( isset( $s["html_replacement"] ) ){
					$returning_html_data = $s["html_replacement"] . $returning_html_data;
				}
			break;
			}
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _display_record_payment(){
			$store = "";
			if( isset( $_GET["store"] ) && $_GET["store"] ){
				$store = $_GET["store"];
				$this->class_settings[ 'store' ] = $store;
			}
			
			$inventory = new cInventory();
			$inventory->class_settings = $this->class_settings;
			$inventory->class_settings[ 'action_to_perform' ] = 'get_all_inventory';
			$this->class_settings[ 'data' ]['stocked_items'] = $inventory->inventory();
			
			$this->class_settings[ 'data' ]['customers'] = get_customers();
			$this->class_settings[ 'data' ]['staff_responsible'] = get_employees();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-record-payment' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _display_production_cart(){
			
			$this->class_settings[ 'data' ]['categories'] = get_items_categories_grouped_raw_materials();
			//$this->class_settings[ 'data' ]['categories'] = get_items_categories();
				
			$filename = 'display-production-cart';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_inventory_picking_slips':
				$this->class_settings[ 'data' ]['categories'] = get_items_categories();
				$this->class_settings[ 'data' ]['categories_grouped'] = get_items_categories_grouped();
				$this->class_settings[ 'data' ]['categories_type'] = get_product_types();
				
				if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$this->class_settings[ 'store' ] = $_SESSION[ "store" ];
					$this->class_settings[ 'data' ]['store'] = $_SESSION[ "store" ];
				}
				$filename = 'display-inventory-picking-slip';
			break;
			case 'display_app_transfer_inventory':
				$this->class_settings[ 'data' ]['categories'] = get_items_categories();
				$this->class_settings[ 'data' ]['categories_grouped'] = get_items_categories_grouped();
				$this->class_settings[ 'data' ]['categories_type'] = get_product_types();
				
				if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$this->class_settings[ 'store' ] = $_SESSION[ "store" ];
					$this->class_settings[ 'data' ]['store'] = $_SESSION[ "store" ];
				}
				$filename = 'display-material-transfer-manifest';
			break;
			case 'display_material_utilization_manifest':
				$filename = 'display-material-utilization-manifest';
				$this->class_settings[ 'production_items' ] = 2;
				$this->class_settings[ 'data' ]['categories_products'] = get_items_categories_grouped_goods();
			break;
			default:
				if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$this->class_settings[ 'store' ] = $_SESSION[ "store" ];
					$this->class_settings[ 'data' ]['store'] = $_SESSION[ "store" ];
				}
				$this->class_settings[ 'production_items' ] = 1;
				$this->class_settings[ 'data' ]['categories_products'] = get_items_categories_grouped_produced_goods();
			break;
			}
			
			if( get_single_store_settings() ){
				unset( $this->class_settings[ 'store' ] );
				unset( $this->class_settings[ 'data' ][ 'store' ] );
			}
			
			$inventory = new cInventory();
			$inventory->class_settings = $this->class_settings;
			$inventory->class_settings[ 'action_to_perform' ] = 'get_all_inventory';
			$this->class_settings[ 'data' ]['stocked_items'] = $inventory->inventory();
			
			$this->class_settings[ 'data' ]['staff_responsible'] = get_employees();
			$this->class_settings[ 'data' ]['factory'] = get_factories(); //get_vendors_factory(); //get_vendors();
			$this->class_settings[ 'data' ]['category_of_expense'] = get_types_of_expenditure();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_inventory_picking_slips':
				$production = new cProduction();
				$production->class_settings = $this->class_settings;
				$production->class_settings["reference_table"] = $this->table_name;
				$production->class_settings["action_to_perform"] = 'get_picking_slips';
				$this->class_settings[ 'data' ][ "picking_slips" ] = $production->production();
				
				unset( $production );
			break;
			}			
				
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
			$returning_html_data = $this->_get_html_view();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_inventory_picking_slips':
				$stores = new cStores();
				$stores->class_settings = $this->class_settings;
				$stores->class_settings[ 'action_to_perform' ] = 'get_stores_list';
				$s = $stores->stores();
				
				if( isset( $s["html_replacement"] ) ){
					$returning_html_data = $s["html_replacement"] . $returning_html_data;
				}
			break;
			}
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		//BASED METHODS
		private function _get_html_view(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			return $script_compiler->script_compiler();
		}
	}
?>