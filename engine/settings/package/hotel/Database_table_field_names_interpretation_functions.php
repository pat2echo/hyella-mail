<?php 
	
	function customers(){
		return array(
			'customers001' => array(
				'field_label' => 'Name of Customer',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'customers002' => array(
				'field_label' => 'Address',
				
				'form_field' => 'textarea',
				'placeholder' => '',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers003' => array(
				'field_label' => 'Phone',
				
				'form_field' => 'text',
				
				'placeholder' => 'Phone Number',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers004' => array(
				'field_label' => 'Email',
				'form_field' => 'text',
				
				'placeholder' => 'Email',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function inventory(){
		return array(
			'inventory001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'inventory002' => array(
				'field_label' => 'Vendor / Warehouse',
				
				'form_field' => 'select',
				'form_field_options' => 'get_vendors',
				'class' => ' col-md-6 ',
				//'form_field_options_group' => 'get_vendors_group',
				
				'placeholder' => '',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory003' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'class' => ' col-md-6 ',
				'form_field_options' => 'get_stores',
				//'form_field_options_group' => 'get_vendors_group',
				
				'placeholder' => '',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory004' => array(
				'field_label' => 'Item Description',
				
				'form_field' => 'select',
				'form_field_options' => 'get_items',
				'form_field_options_group' => 'get_items_grouped',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory005' => array(
				'field_label' => 'Qty. In-Stock',
				'form_field' => 'decimal',
				//'required_field' => 'yes',
				
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory006' => array(
				'field_label' => 'Cost Price',
				
				'form_field' => 'currency',
				'placeholder' => '',
				
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory007' => array(
				'field_label' => 'Selling Price',
				
				'form_field' => 'currency',
				'placeholder' => '',
				
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory008' => array(
				'field_label' => 'Expiry Date',
				'form_field' => 'date-5',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory010' => array(
				'field_label' => 'Production REF',
				'form_field' => 'text',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'inventory011' => array(
				'field_label' => 'Staff Responsible',
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'class' => ' no-x-padding-1 col-md-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'inventory012' => array(
				'field_label' => 'Qty. Expected',
				'form_field' => 'decimal',
				
				'class' => ' no-x-padding-1 col-md-6 ',
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory013' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				
				'class' => ' no-x-padding-1 col-md-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory009' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => '',
				
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function items(){
		return array(
			'items001' => array(
				'field_label' => 'Category',
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_items_categories',
				//'form_field_options_group' => 'get_items_categories_grouped',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'items002' => array(
				'field_label' => 'Item Description',
				'form_field' => 'text',
				'required_field' => 'yes',
				'placeholder' => 'Item Desc.',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'items004' => array(
				'field_label' => 'Barcode',
				'form_field' => 'text',
				
				'placeholder' => 'Barcode',
				'class' => ' no-x-padding-1 ',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'items003' => array(
				'field_label' => 'Image',
				'form_field' => 'file',
				
				'acceptable_files_format' => 'png:::jpg:::jpeg',
				'class' => ' no-x-padding-1 ',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'items005' => array(
				'field_label' => 'Item Type',
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_product_types',
				
				'class' => ' no-x-padding-1 col-md-6 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'items009' => array(
				'field_label' => 'Low Stock Level',
				'form_field' => 'number',
				
				'class' => ' no-x-padding-1 col-md-6 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'items006' => array(
				'field_label' => 'Cost Price',
				
				'form_field' => 'currency',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'items007' => array(
				'field_label' => 'Selling Price',
				
				'form_field' => 'currency',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'items008' => array(
				'field_label' => 'Vendor / Warehouse',
				
				'form_field' => 'select',
				'form_field_options' => 'get_vendors',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function hotel_room(){
		return array(
			'hotel_room001' => array(
				'field_label' => 'Room Number',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room002' => array(
				'field_label' => 'Room Type',
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_room_types',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room003' => array(
				'field_label' => 'Floor',
				
				'form_field' => 'number',
				'required_field' => 'yes',
				
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room004' => array(
				'field_label' => 'Building',
				'form_field' => 'text',
				
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room005' => array(
				'field_label' => 'Hotel',
				'form_field' => 'text',
				
				'placeholder' => '',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room006' => array(
				'field_label' => 'Occupancy Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_room_status',
				
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room007' => array(
				'field_label' => 'Cleaning Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_room_cleaning_status',
				
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room008' => array(
				'field_label' => 'Rate',
				
				'form_field' => 'currency',
				
				'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room009' => array(
				'field_label' => 'Deposit Amount',
				'form_field' => 'currency',
				
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room010' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'placeholder' => 'Optional Comment',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function hotel_room_type(){
		return array(
			'hotel_room_type001' => array(
				'field_label' => 'Name of Room Type',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_type002' => array(
				'field_label' => 'Max. No. of Adults',
				'form_field' => 'number',
				'required_field' => 'yes',
				
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_type003' => array(
				'field_label' => 'Max. No. of Children',
				
				'form_field' => 'number',
				'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_type004' => array(
				'field_label' => 'Hotel',
				//'form_field' => 'multi-select',
				'form_field' => 'select',
				
				'placeholder' => '',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_type005' => array(
				'field_label' => 'Rate',
				
				'form_field' => 'currency',
				'required_field' => 'yes',
				
				'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_type006' => array(
				'field_label' => 'Deposit Amount',
				'form_field' => 'currency',
				
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_type007' => array(
				'field_label' => 'Room Type Features',
				'form_field' => 'textarea',
				
				'placeholder' => 'List features of room type',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_type011' => array(
				'field_label' => 'Description',
				'form_field' => 'textarea',
				
				'placeholder' => 'Describe room type',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_type008' => array(
				'field_label' => 'Picture',
				'form_field' => 'file',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_type009' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'placeholder' => 'Optional Comment',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function hotel_room_service(){
		return array(
			'hotel_room_service001' => array(
				'field_label' => 'Description',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_service002' => array(
				'field_label' => 'Service Billing Type',
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_room_service_types',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_service003' => array(
				'field_label' => 'Rate',
				
				'form_field' => 'currency',
				'required_field' => 'yes',
				
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_service004' => array(
				'field_label' => 'Service Limit',
				
				'form_field' => 'number',
				
				'placeholder' => 'Enter Zero(0) for Unlimited',
				'tooltip' => 'Number of times that a guest can request a service that is inclusive of room rate.<br />Leave as zero(0) for unlimited',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_service005' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				
				'placeholder' => 'Optional Comment',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function hotel_group_guest(){
		return array(
			'hotel_group_guest001' => array(
				'field_label' => 'Group Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_group_guest002' => array(
				'field_label' => 'Guests',
				'form_field' => 'multi-select',
				'form_field_options' => 'get_customers',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_group_guest003' => array(
				'field_label' => 'Paying Party',
				
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				'placeholder' => '',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_group_guest004' => array(
				'field_label' => 'Hotel',
				'form_field' => 'select',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_group_guest005' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				
				'placeholder' => 'Optional Comment',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function hotel_checkin(){
		return array(
			'hotel_checkin001' => array(
				'field_label' => 'Ref',
				//'form_field' => 'select',
				//'form_field_options' => 'get_checkin_types',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'reference',
					'form_field' => 'text',
					'variables' => array( array( 'id' ) ),
				),
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),/*
			'hotel_checkin002' => array(
				'field_label' => 'Room Type',
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_room_types',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),*/
			'hotel_checkin003' => array(
				'field_label' => 'Main Guest',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_checkin004' => array(
				'field_label' => 'Group',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				'display_position' => 'display-in-table-row',
                
				'serial_number' => '',
			),
			'hotel_checkin005' => array(
				'field_label' => 'Other Guests',
				
				'form_field' => 'multi-select',
				'form_field_options' => 'get_customers',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin013' => array(
				'field_label' => 'Discount',
				
				'form_field' => 'currency',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin019' => array(
				'field_label' => 'Percentage Discount',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin014' => array(
				'field_label' => 'Number of Adults',
				
				'class' => ' col-lg-6 ',
				'form_field' => 'number',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin015' => array(
				'field_label' => 'Number of Children',
				
				'class' => ' col-lg-6 ',
				'form_field' => 'number',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin006' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin007' => array(
				'field_label' => 'P. Check-in Date',
				
				'form_field' => 'date-5',
				'placeholder' => '',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin008' => array(
				'field_label' => 'P. Check-out Date',
				
				'form_field' => 'date-5',
				'placeholder' => '',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin009' => array(
				'field_label' => 'Booking Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_room_booking_status',
				
				'placeholder' => '',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin010' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'placeholder' => 'Optional Comment',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin011' => array(
				'field_label' => 'Hotel',
				'form_field' => 'select',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin016' => array(
				'field_label' => 'VAT',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin017' => array(
				'field_label' => 'Service Charge',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_checkin018' => array(
				'field_label' => 'Service Tax',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function hotel_room_type_checkin(){
		return array(
			'hotel_room_type_checkin001' => array(
				'field_label' => 'Booking Reference',
				'form_field' => 'calculated',
				'required_field' => 'yes',
				
				'calculations' => array(
					'type' => 'hotel-receipt-num',
					'form_field' => 'text',
					'variables' => array( array( 'hotel_room_type_checkin001' ) ),
				),
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_type_checkin003' => array(
				'field_label' => 'Room Type',
				
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_room_types',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_type_checkin004' => array(
				'field_label' => 'Number of Rooms',
				
				'class' => ' col-lg-6 ',
				'form_field' => 'number',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_type_checkin005' => array(
				'field_label' => 'Room Rate',
				'form_field' => 'currency',
				
				'class' => ' col-lg-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'default_appearance_in_table_fields' => 'show',
			),
			'hotel_room_type_checkin006' => array(
				'field_label' => 'Room Deposit Amount',
				'form_field' => 'currency',
				
				'class' => ' col-lg-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'default_appearance_in_table_fields' => 'show',
			),
			'hotel_room_type_checkin007' => array(
				'field_label' => 'Discount',
				'form_field' => 'currency',
				
				'class' => ' col-lg-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'default_appearance_in_table_fields' => 'show',
			),
			'hotel_room_type_checkin008' => array(
				'field_label' => 'Amount Due',
				'form_field' => 'currency',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'default_appearance_in_table_fields' => 'show',
			),
			'hotel_room_type_checkin011' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => 'Optional Comment',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
				
			),
			'hotel_room_type_checkin012' => array(
				'field_label' => 'Hotel',
				'form_field' => 'select',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function hotel_room_checkin(){
		return array(
			'hotel_room_checkin001' => array(
				'field_label' => 'Booking Reference',
				'form_field' => 'calculated',
				'required_field' => 'yes',
				
				'calculations' => array(
					'type' => 'hotel-receipt-num',
					'form_field' => 'text',
					'variables' => array( array( 'hotel_room_checkin001' ) ),
				),
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_checkin002' => array(
				'field_label' => 'Room Guest',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				'class' => ' select-room-guest ',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_checkin003' => array(
				'field_label' => 'Number of People',
				'form_field' => 'number',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_checkin004' => array(
				'field_label' => 'Room Number',
				
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_rooms',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin005' => array(
				'field_label' => 'Room Rate',
				'form_field' => 'currency',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin006' => array(
				'field_label' => 'Refundable Deposit Amount',
				'form_field' => 'currency',
				'placeholder' => 'Refundable Deposit In Case of Damage',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin007' => array(
				'field_label' => 'Discount',
				'form_field' => 'currency',
				'placeholder' => 'Enter Amount Discounted from Room Rate',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin015' => array(
				'field_label' => 'Percentage Discount',
				'form_field' => 'decimal',
				'placeholder' => 'Enter Percentage Discount',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin008' => array(
				'field_label' => 'Amount Due',
				'form_field' => 'currency',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin009' => array(
				'field_label' => 'Check-in Date',
				
				'form_field' => 'date-5',
				'placeholder' => '',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin010' => array(
				'field_label' => 'Check-out Date',
				
				'form_field' => 'date-5',
				'placeholder' => '',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin011' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => 'Optional Comment',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_checkin012' => array(
				'field_label' => 'Hotel',
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin013' => array(
				'field_label' => 'Room Type',
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_room_types',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin014' => array(
				'field_label' => 'Check In Status',
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_room_booking_status',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin016' => array(
				'field_label' => '<small><strong>VAT (%)</strong></small>',
				'form_field' => 'decimal',
				'class' => ' col-md-4 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin017' => array(
				'field_label' => '<small><strong>Service Charge (%)</strong></small>',
				'form_field' => 'decimal',
				'class' => ' col-md-4 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin018' => array(
				'field_label' => '<small><strong>Service Tax (%)</strong></small>',
				'form_field' => 'decimal',
				'class' => ' col-md-4 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function hotel_room_checkin_service(){
		return array(
			'hotel_room_checkin_service001' => array(
				'field_label' => 'Booking Reference',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_checkin_service002' => array(
				'field_label' => 'Room Reference',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_checkin_service003' => array(
				'field_label' => 'Service Reference',
				'form_field' => 'select',
				'form_field_options' => 'get_hotel_room_services',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'hotel_room_checkin_service004' => array(
				'field_label' => 'Service Rate',
				'form_field' => 'currency',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin_service005' => array(
				'field_label' => 'Discount',
				'form_field' => 'currency',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin_service006' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => 'Optional Comment',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'hotel_room_checkin_service007' => array(
				'field_label' => 'Hotel',
				'form_field' => 'select',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
?>