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
                'default_appearance_in_table_fields' => 'show',
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
				'field_label' => 'Status',
				'form_field' => 'select',
				'form_field_options' => 'get_stock_status',
				
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
	
?>