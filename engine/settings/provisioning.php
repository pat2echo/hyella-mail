<?php
	//KWAALA PROVISIONING SCRIPT
	$database_name = 'water_factory';
	
	define( 'PLATFORM' , 'windows' );
	define( 'HYELLA_INSTALL_PATH' , 'feyi/engine/' );
	define( 'HYELLA_INSTALL_ENGINE' , 'feyi/' );
	
	//define( 'HYELLA_PACKAGE' , 'accounting' );
	define( 'HYELLA_PACKAGE' , 'newsletter' );
	
	define( 'HYELLA_NO_FRONTEND_UPDATE' , '1' );
	//define( 'HYELLA_WEB_COPY' , '2fe55f5f44044f4b02db59711ffc2a6e======/==1690009473.6223' );
	
	define( 'HYELLA_SINGLE_STORE' , '1' );
	define( 'HYELLA_SERVER_FILTER' , '1' );
	define( 'HYELLA_IGNORE_INVENTORY_COST_OF_GOODS' , '1' );
	
	define( 'HYELLA_TREATE_PURCHASE_ORDER_AS_SEPERATE_DOC' , '1' );
	
	//exquiste laces = ty
	define( 'HYELLA_APP_TITLE' , 'QagM0x/aRG4bzvp49IL8zqIOhh5Viw3p' );
	define( 'HYELLA_CLIENT_NAME' , '8JEbuZe7UxdM9fbNfTnO9TQLwOH/Aa797BZfgi0Ugeg=' );
	define( 'HYELLA_CLIENT_PASSWORD' , '5rWqSsAhnl4=' );
	define( 'HYELLA_CLIENT_SCOPE' , 'single' );
	
	define( 'HYELLA_MAIN_STORE' , "Store" );
	
	define( 'HYELLA_SHOW_ACCOUNTING_VENDORS' , "1" );
	define( 'HYELLA_SHOW_ACCOUNTING_CUSTOMERS' , "1" );
	define( 'HYELLA_SHOW_ACCOUNTING_BILLS' , "1" );
	
	//define( 'HYELLA_NO_APP' , '1' );
	define( 'HYELLA_DEFAULT_LOCATION' , "main.html" );
	define( 'HYELLA_DEFAULT_STORE' , "11071545211" );
	
	function __map_store_locations(){
		return array(
			"10173870046" => array(
				"name" => "Warehouse",
				//"file" => "inventory.html",
			),
			"11070988277" => array(
				"name" => "Bar",
			),
			"11070993234" => array(
				"name" => "Restaurant",
			),
			"11070994512" => array(
				"name" => "Laundry",
			),
			"11071000849" => array(
				"name" => "Swimming Pool",
			),
			"11071004339" => array(
				"name" => "Gym",
			),
			"11071004738" => array(
				"name" => "House Keeping",
				"file" => "kitchen.html",
			),
			"11071545211" => array(
				"name" => "Hotel Frontdesk",
				"file" => "hotel.html",
			),
			"11071585560" => array(
				"name" => "Kitchen",
				"file" => "kitchen.html",
			),
			"11133508021" => array(
				"name" => "Hall Rental",
			),
			"11133508863" => array(
				"name" => "Car Hire",
			),
			"11133509471" => array(
				"name" => "Business Center",
			),
		);
	}
	
	function __map_financial_accounts(){
		return array(
			"customer_refund" => "petty_cash",
			"bank_account" => "cash_book",
			"account_receivable" => "accounts_receivable",
			
			"inventory" => "inventory",
			
			"marketing_expense" => "inventory_marketing_expense",
			"cost_of_goods_sold" => "cost_of_goods_sold",
			"operating_expense" => "operating_expense",
			
			"current_liabilities" => "current_liabilities",
			
			"value_added_tax" => "value_added_tax",
			"service_charge" => "service_charge",
			"service_tax" => "service_tax",
			
			"damaged_goods" => "damaged_items",
			"used_goods" => "purchase_of_materials",
			
			"account_payable" => "account_payable",
			
			"discount" => "",
			"revenue" => "revenue",
			"revenue_category" => "revenue_category",
			//map individual product sales, discount, inventory & cost_of_goods
			
			"revenue_sales" => "revenue_from_sales",
			"revenue_hotel" => "room_booking_revenue",
			
			"salary_bank_account" => "bank1",
			
			"staff_medical_account" => "staff_medical",
			"salary_advance_account" => "salary_advance",
			"salary_expense_account" => "salary",
			
			"payroll_liabilities" => "payroll_liabilities",
			"payroll_net_pay" => "payroll_net_pay",
			"payroll_paye" => "payroll_paye",
			"payroll_pension" => "payroll_pension",
			"payroll_other_deductions" => "payroll_other_deductions",
		);
	}
	
	function __map_payment_methods_to_financial_accounts(){
		return array(
			"cash" => "cash_payment",
			"cheque" => "cheque_payment",
			"transfer" => "pos_payment",
			"others" => "bank1",
		);
	}
	
	function __list_of_installed_modules(){
		$return = array(
			"Inventory Management",
			"Barcode Management",
			"Financial Accounting",
			"Pay Roll Management",
		);
		asort( $return );
		return $return;
	}
	
	function __list_of_available_modules(){
		$return = array(
			"Purchase Order Management",
			"Sales Order Management",
			"Point of Sales",
			"Hotel Management",
			"Multi-store Management",
			"Production Management",
			"Farm Management",
			"Assets Management (coming soon)",
		);
		asort( $return );
		return $return;
	}
?>