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
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers003' => array(
				'field_label' => 'City',
				
				'form_field' => 'select',
				'form_field_options' => 'get_all_states_in_nigeria',
				
				'class' => ' col-lg-6 personal-info ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers004' => array(
				'field_label' => 'Phone',
				
				'form_field' => 'text',
				
				'placeholder' => 'Phone Number',
				'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers005' => array(
				'field_label' => 'Other Phone',
				
				'form_field' => 'text',
				
				'placeholder' => 'Phone Number',
				'class' => ' col-lg-6 personal-info ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers006' => array(
				'field_label' => 'Email',
				'form_field' => 'email',
				
				'placeholder' => 'Email',
				'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers007' => array(
				'field_label' => 'Birth Day',
				'form_field' => 'date-5',
				
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers008' => array(
				'field_label' => 'Spouse',
				'form_field' => 'text',
				
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers009' => array(
				'field_label' => 'Credit Limit',
				'form_field' => 'decimal',
				
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers010' => array(
				'field_label' => 'His Ring Size',
				'form_field' => 'decimal',
				
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers011' => array(
				'field_label' => 'Her Ring Size',
				'form_field' => 'decimal',
				
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers012' => array(
				'field_label' => 'Referral Source',
				'form_field' => 'text',
				'placeholder' => 'Name of Referral / Walk in',
				
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customers013' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
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
				'class' => ' col-lg-6 ',
				//'form_field_options_group' => 'get_vendors_group',
				
				'placeholder' => '',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory003' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'class' => ' col-lg-6 ',
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
				
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory006' => array(
				'field_label' => 'Cost Price',
				
				'form_field' => 'currency',
				'placeholder' => '',
				
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory007' => array(
				'field_label' => 'Selling Price',
				
				'form_field' => 'currency',
				'placeholder' => '',
				
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory008' => array(
				'field_label' => 'Expiry Date',
				'form_field' => 'date-5',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory013' => array(
				'field_label' => 'Percentage Markup',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'inventory014' => array(
				'field_label' => 'Percentage Markup Source',
				'form_field' => 'select',
				'form_field_options' => 'get_source_of_percentage_markup',
				
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
				
				'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'inventory012' => array(
				'field_label' => 'Status',
				'form_field' => 'select',
				'form_field_options' => 'get_stock_status',
				
				'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory009' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => '',
				
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'inventory015' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				
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
				
				'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'items009' => array(
				'field_label' => 'Low Stock Level',
				'form_field' => 'number',
				
				'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'items006' => array(
				'field_label' => 'Cost Price',
				
				'form_field' => 'currency',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'items007' => array(
				'field_label' => 'Selling Price',
				
				'form_field' => 'currency',
				'class' => ' col-lg-6 ',
				
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
			'items015' => array(
				'field_label' => 'Percentage Mark-up',
				
				'form_field' => 'decimal',
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'items010' => array(
				'field_label' => 'Color of Gold',
				
				'form_field' => 'select',
				'form_field_options' => 'get_color_of_gold',
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'items011' => array(
				'field_label' => 'Length of Chain',
				
				'class' => ' col-lg-6 ',
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'items012' => array(
				'field_label' => 'Weight in Grams',
				
				'class' => ' col-lg-6 ',
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'items013' => array(
				'field_label' => 'Sub Category',
				
				//'class' => ' col-lg-6 ',
				'form_field' => 'select',
				'form_field_options' => 'get_items_sub_categories',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'items014' => array(
				'field_label' => 'Currency',
				
				//'class' => ' col-lg-6 ',
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function appraisal(){
		return array(
			'appraisal001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'appraisal002' => array(
				'field_label' => 'Customer',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'appraisal003' => array(
				'field_label' => 'Comment',
				
				//'class' => ' col-lg-6 ',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function appraised_items(){
		return array(
			'appraised_items001' => array(
				'field_label' => 'Appraisal',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'appraised_items002' => array(
				'field_label' => 'Item',
				'form_field' => 'select',
				'form_field_options' => 'get_items',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'appraised_items003' => array(
				'field_label' => 'Appraised Value',
				'form_field' => 'decimal',
				'required_field' => 'yes',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'appraised_items004' => array(
				'field_label' => 'Comment',
				
				//'class' => ' col-lg-6 ',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function repairs(){
		return array(
			'repairs001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'repairs002' => array(
				'field_label' => 'Description of Repair',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'repairs003' => array(
				'field_label' => 'Image of Item',
				
				'form_field' => 'file',
				'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs004' => array(
				'field_label' => 'Customer',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs005' => array(
				'field_label' => 'Repair Vendor',
				'form_field' => 'select',
				'form_field_options' => 'get_vendors',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs006' => array(
				'field_label' => 'Amount Due',
				
				'form_field' => 'currency',
				//'required_field' => 'yes',
				
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs007' => array(
				'field_label' => 'Amount Paid',
				
				'form_field' => 'currency',
				//'required_field' => 'yes',
				
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs008' => array(
				'field_label' => 'Actual Cost of Repair',
				
				'form_field' => 'currency',
				
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs009' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_repairs_status',
				
				'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs010' => array(
				'field_label' => 'Date Sent to Vendor',
				'form_field' => 'date-5',
				
				'class' => ' col-lg-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs011' => array(
				'field_label' => 'Expected Collection Date',
				'form_field' => 'date-5',
				
				'class' => ' col-lg-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs012' => array(
				'field_label' => 'Actual Collection Date',
				'form_field' => 'date-5',
				
				'class' => ' col-lg-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs013' => array(
				'field_label' => 'Date Returned to Customer',
				'form_field' => 'date-5',
				
				'class' => ' col-lg-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'repairs014' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'placeholder' => 'Optional Comment',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function customer_call_log(){
		return array(
			'customer_call_log001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'customer_call_log002' => array(
				'field_label' => 'Customer',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'customer_call_log003' => array(
				'field_label' => 'Reason for Call',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_call_log004' => array(
				'field_label' => 'Customer Feedback',
				//'form_field' => 'multi-select',
				'form_field' => 'text',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_call_log005' => array(
				'field_label' => 'Call Category',
				
				'form_field' => 'select',
				'form_field_options' => 'get_call_categories',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_call_log006' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'placeholder' => 'Optional Comment',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function customer_wish_list(){
		return array(
			'customer_wish_list001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'customer_wish_list002' => array(
				'field_label' => 'Customer',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'customer_wish_list003' => array(
				'field_label' => 'Item',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_wish_list004' => array(
				'field_label' => 'Item Description',
				'form_field' => 'text',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_wish_list005' => array(
				'field_label' => 'Date Needed',
				
				'form_field' => 'date-5',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_wish_list006' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'placeholder' => 'Optional Comment',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function color_of_gold(){
		return array(
			'color_of_gold001' => array(
				'field_label' => 'Color of Gold',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),/*
			'color_of_gold002' => array(
				'field_label' => 'Type of color_of_gold',
				
				'form_field' => 'select',
				'form_field_options' => 'get_product_types',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
		);
	}
	
?>