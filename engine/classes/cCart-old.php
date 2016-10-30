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
				$returned_value = $this->_display_shopping_cart();
			break;
			case 'display_app_transfer_inventory':
			case 'display_material_utilization_manifest':
			case 'display_production_cart':
				$returned_value = $this->_display_production_cart();
			break;
			case "checkout":
				$returned_value = $this->_checkout();
			break;
			case "save_material_transfer_manifest":
			case "save_material_utilization_manifest":
			case "save_production_manifest":
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
			}
			
			return $returned_value;
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
			
			//check for amount paid
			if( isset( $_POST["amount_paid"] ) && doubleval( $_POST["amount_paid"] ) ){
				//record payment
				$payment = new cPayment();
				$payment->class_settings = $this->class_settings;
				$payment->class_settings["action_to_perform"] = 'save_payment';
				
				$payment->class_settings["amount_paid"] = $_POST["amount_paid"];
				$payment->class_settings["sales_id"] = $sales_id;
				$payment->class_settings["comment"] = $_POST["comment"];
				$payment->class_settings["payment_method"] = $_POST["payment_method"];
				$payment->class_settings["staff_responsible"] = ( isset( $_POST["staff_responsible"] )?$_POST["staff_responsible"]:$this->class_settings["user_id"] );
				
				$payment->payment();
			}
			
			//update sales
			$_POST[ "id" ] = $sales_id;
			$this->class_settings["update_fields"] = $_POST;
			
			$sales = new cSales();
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
				
				switch( $this->class_settings["action_to_perform"] ){
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
				
				switch( $this->class_settings["action_to_perform"] ){
				case "save_material_utilization_manifest":
				break;
				case "save_material_transfer_manifest":
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
				
				$production = new cProduction();
				$production->class_settings = $this->class_settings;
				
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
				
				$production->class_settings["production_id"] = $production_id;
				$production->class_settings["production"] = $cart_items;
				$production->class_settings["action_to_perform"] = "save_production_and_return_receipt";
				return $production->production();
				
			}
		}
		
		private function _checkout(){
			
			if( isset( $_POST["json"]["item"] ) && is_array( $_POST["json"]["item"] ) && ! empty( $_POST["json"]["item"] ) ){
				$cart_items = $_POST["json"];
				
				$sales_id = get_new_id();
				
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
				
				if( isset( $cart_items["amount_paid"] ) && doubleval( $cart_items["amount_paid"] ) ){
					//capture payment				
					$payment = new cPayment();
					$payment->class_settings = $this->class_settings;
					$payment->class_settings["sales_id"] = $sales_id;
					
					$payment->class_settings["amount_paid"] = $cart_items["amount_paid"];
					$payment->class_settings['payment_method'] = isset( $cart_items['payment_method'] )?$cart_items['payment_method']:"";
					
					$payment->class_settings["action_to_perform"] = 'save_payment';
					$payment->payment();
				}
				
				$sales = new cSales();
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
		}
		
		private function _display_shopping_cart(){
			
			$key = md5('ucert'.$_SESSION['key']);
			$a = 0;
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
				
				$key = "10858980143"; //discounts
				if( ( isset( $access[ $key ] ) ||  $super ) ){
					$a = 1;
				}
			}
			
			$this->class_settings[ 'data' ]['categories'] = get_items_categories_grouped_goods();
			//$this->class_settings[ 'data' ]['categories'] = get_items_categories();
			$store = "";
			if( isset( $_GET["store"] ) && $_GET["store"] ){
				$store = $_GET["store"];
				$this->class_settings[ 'store' ] = $store;
			}
			
			$inventory = new cInventory();
			$inventory->class_settings = $this->class_settings;
			$inventory->class_settings[ 'action_to_perform' ] = 'get_all_inventory';
			$this->class_settings[ 'data' ]['stocked_items'] = $inventory->inventory();
			
			
			//$this->class_settings[ 'data' ]['discount'] = get_discount();
			//$this->class_settings[ 'data' ]['discount_type'] = get_discount_type_settings();
			//$this->class_settings[ 'data' ]['manage_discount'] = get_discount_management_during_sale_settings();
			//$this->class_settings[ 'data' ]['backdate_sales'] = get_ability_to_back_date_sales_settings();
			
			$this->class_settings[ 'data' ]['allow_manage_discount'] = $a;
			$this->class_settings[ 'data' ]['customers'] = get_customers();
			$this->class_settings[ 'data' ]['staff_responsible'] = get_employees();
			$this->class_settings[ 'data' ]['discount'] = get_discount();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-shopping-cart' );
			
			if( defined("HYELLA_PACKAGE") ){
				switch( HYELLA_PACKAGE ){
				case "jewelry":
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-shopping-cart-jewelry' );
				break;
				}
			}
			$returning_html_data = $this->_get_html_view();
			
			return array(
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
				$this->class_settings[ 'production_items' ] = 1;
				$this->class_settings[ 'data' ]['categories_products'] = get_items_categories_grouped_produced_goods();
			break;
			}
			
			$inventory = new cInventory();
			$inventory->class_settings = $this->class_settings;
			$inventory->class_settings[ 'action_to_perform' ] = 'get_all_inventory';
			$this->class_settings[ 'data' ]['stocked_items'] = $inventory->inventory();
			
			$this->class_settings[ 'data' ]['staff_responsible'] = get_employees();
			$this->class_settings[ 'data' ]['factory'] = get_factories(); //get_vendors_factory(); //get_vendors();
			$this->class_settings[ 'data' ]['category_of_expense'] = get_types_of_expenditure();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
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