<?php 
	
	function newsletter_subscribers(){
		return array(
			'newsletter_subscribers001' => array(
				'field_label' => 'Email',
				'form_field' => 'email',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'newsletter_subscribers002' => array(
				'field_label' => 'First Name',
				
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'newsletter_subscribers003' => array(
				'field_label' => 'Last Name',
				
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'newsletter_subscribers004' => array(
				'field_label' => 'Phone',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'newsletter_subscribers005' => array(
				'field_label' => 'Category',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'newsletter_subscribers006' => array(
				'field_label' => 'Status',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function newsletter_tracking(){
		return array(
			'newsletter_tracking001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'newsletter_tracking002' => array(
				'field_label' => 'Email',
				
				'form_field' => 'email',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'newsletter_tracking003' => array(
				'field_label' => 'Action',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'newsletter_tracking004' => array(
				'field_label' => 'Message',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'newsletter_tracking005' => array(
				'field_label' => 'IP Address',
				'form_field' => 'text',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function newsletter_message(){
		return array(
			'newsletter_message001' => array(
				'field_label' => 'Subject',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'newsletter_message002' => array(
				'field_label' => 'Message',
				
				'form_field' => 'textarea-unlimited',
				'placeholder' => '',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'newsletter_message003' => array(
				'field_label' => 'Date Last Sent',
				
				'form_field' => 'date-5',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'newsletter_message004' => array(
				'field_label' => 'Number of Times Sent',
				
				'form_field' => 'number',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function import_newsletter(){
		return array(
			'import_newsletter001' => array(
				
				'field_label' => 'Excel File (*.xls, *.xlsx)',
				'form_field' => 'file',
				'required_field' => 'yes',
				
				'acceptable_files_format' => 'xls:::xlsx',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-12 ',
			),/*
			'import_newsletter002' => array(
				
				'field_label' => 'Operator',
				'form_field' => 'select',
				'required_field' => 'yes',
                'form_field_options' => 'get_operators',
				
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
			'import_newsletter003' => array(
				'field_label' => 'Import Template',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-12 ',
				
			),/*
			'import_newsletter005' => array(
			
				'field_label' => 'Month',
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_months_of_year',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'import_newsletter006' => array(
				
				'field_label' => 'Department',
				'form_field' => 'select',
				'form_field_options' => 'get_departments',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'import_newsletter004' => array(
				
				'field_label' => 'Departmental Unit',
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_units',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-6 ',
				
			),
			'import_newsletter007' => array(
				
				'field_label' => 'Budget Code',
				//'form_field' => 'text',
				'form_field' => 'select',
				
				'required_field' => 'yes',
				'form_field_options' => 'get_all_budgets',
				//tie this to existing budgets
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' ',
			),*/
			'import_newsletter008' => array(
				
				'field_label' => 'Starting Row',
				'form_field' => 'number',
				
				'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_newsletter009' => array(
				
				'field_label' => 'Email Column',
				'form_field' => 'number',
				
				'attributes' => 'col-label="EMAIL" ',
				'class' => 'column-select-field',
				'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_newsletter010' => array(
				
				'field_label' => 'First Name Column',
				'form_field' => 'number',
				
				'attributes' => 'col-label="FIRST NAME" ',
				'class' => 'column-select-field',
				'display_position' => 'display-in-table-row',
			),
			'import_newsletter011' => array(
				
				'field_label' => 'Last Name',
				'form_field' => 'number',
				
				'attributes' => 'col-label="LAST NAME" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_newsletter012' => array(
				
				'field_label' => 'Phone',
				'form_field' => 'number',
				
				'attributes' => 'col-label="PHONE" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_newsletter013' => array(
				
				'field_label' => 'Category',
				'form_field' => 'number',
				
				'attributes' => 'col-label="CATEGORY" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
		);
	}
	
	function newsletter_raw_data_import(){
		for( $x = 1; $x < 41; ++$x ){
			$key = 'newsletter_raw_data_import0';
			if( $x < 10 )$key .= '0'.$x;
			else $key .= $x;
			
			$return[ $key ] = array(
				'field_label' => 'Column '.($x-1),
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			);
		}
		$return['newsletter_raw_data_import041'] = array(
				
				'field_label' => 'Excel Reference ID',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
		);	
		return $return;
	}
	
?>