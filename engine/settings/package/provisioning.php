<?php
	//KWAALA PROVISIONING SCRIPT
	$database_name = 'hyella_business_manager_hotel';
	define( 'PLATFORM' , 'windows' );
	define( 'HYELLA_INSTALL_PATH' , 'feyi/engine/' );
	define( 'HYELLA_INSTALL_ENGINE' , 'feyi/' );
	
	define( 'HYELLA_PACKAGE' , 'hotel' );
	
	define( 'HYELLA_NO_FRONTEND_UPDATE' , '1' );
	
	define( 'HYELLA_SERVER_FILTER' , '1' );
	define( 'HYELLA_IGNORE_INVENTORY_COST_OF_GOODS' , '1' );
	
	define( 'HYELLA_APP_TITLE' , 'bc33sl2BL7dcOreBjBGenqE7Zq/kF8G4' );
	define( 'HYELLA_CLIENT_NAME' , 'Va0Dtv69Rf34JR+6QonDSUtRb3IJqpGmrOUM00a+EkA=' );
	define( 'HYELLA_CLIENT_PASSWORD' , '8cVj88br688=' );
	define( 'HYELLA_CLIENT_SCOPE' , 'single' );
	define( 'HYELLA_BACKUP' , 'xn8MxSSqW8w=' );
	
	define( 'HYELLA_MAIN_STORE' , "Store" );
	
	define( 'HYELLA_DEFAULT_LOCATION' , "hotel.html" );
	define( 'HYELLA_DEFAULT_STORE' , "11071545211" );
	
	function __map_store_locations(){
		return array(
			"10173870046" => array(
				"name" => "Warehouse",
				"file" => "inventory.html",
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
				"file" => "hall-rental.html",
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
			
			"cost_of_goods_sold" => "cost_of_goods_sold",
			"operating_expense" => "operating_expense",
			
			"current_liabilities" => "current_liabilities",
			"value_added_tax" => "value_added_tax",
			"service_charge" => "service_charge",
			"service_tax" => "service_tax",
			
			"damaged_goods" => "damaged_goods",
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
			
			"payroll_net_pay" => "payroll_net_pay",
			"payroll_paye" => "payroll_paye",
			"payroll_pension" => "payroll_pension",
			"payroll_other_deductions" => "payroll_other_deductions",
			
			"charge_from_deposit" => "bank1",
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
?>