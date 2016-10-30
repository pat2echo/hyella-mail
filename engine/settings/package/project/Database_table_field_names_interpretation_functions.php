<?php 
	include dirname( dirname( __FILE__ ) ) . "/hotel/Database_table_field_names_interpretation_functions.php";
	
	function project(){
		return array(
			'project001' => array(
				'field_label' => 'Project Type',
				'form_field' => 'select',
				'form_field_options' => 'get_project_categories',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project002' => array(
				'field_label' => 'Project Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				'placeholder' => 'Project Description',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'project003' => array(
				'field_label' => 'Project Manager',
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'class' => ' no-x-padding-1 ',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project004' => array(
				'field_label' => 'Project Team',
				'form_field' => 'multi-select',
				'form_field_options' => 'get_employees',
				
				'class' => ' no-x-padding-1 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project005' => array(
				'field_label' => 'Budget',
				'form_field' => 'decimal',
				
				'class' => ' no-x-padding-1 col-md-6 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'project006' => array(
				'field_label' => 'Start Date',
				'form_field' => 'date-5',
				
				'class' => ' no-x-padding-1 col-md-6 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'project007' => array(
				'field_label' => 'Contractor',
				
				'form_field' => 'select',
				'form_field_options' => 'get_vendors',
				
				'class' => ' no-x-padding-1 col-md-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project008' => array(
				'field_label' => 'Client',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				'class' => ' no-x-padding-1 col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project009' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project010' => array(
				'field_label' => 'Supporting Document',
				'form_field' => 'file',
				
				'acceptable_files_format' => 'doc:::xlsx:::xls:::docx:::pdf:::png:::jpg:::jpeg',
				'class' => ' no-x-padding-1 ',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project011' => array(
				'field_label' => 'Location',
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				'class' => ' no-x-padding-1 col-md-6 ',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project012' => array(
				'field_label' => 'Status',
				'form_field' => 'select',
				'form_field_options' => 'get_project_status',
				'class' => ' no-x-padding-1 col-md-6 ',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function project_task(){
		return array(
			'project_task001' => array(
				'field_label' => 'Project Ref',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'project_task002' => array(
				'field_label' => 'Task Type',
				'form_field' => 'select',
				'form_field_options' => 'get_task_types',
				'required_field' => 'yes',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'project_task003' => array(
				'field_label' => 'Task Description',
				'form_field' => 'text',
				'required_field' => 'yes',
				'placeholder' => 'Task Desc.',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'project_task004' => array(
				'field_label' => 'Est. Start Date',
				'form_field' => 'date-5',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task005' => array(
				'field_label' => 'Act. Start Date',
				'form_field' => 'date-5',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task006' => array(
				'field_label' => 'Est. End Date',
				'form_field' => 'date-5',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task007' => array(
				'field_label' => 'Act. End Date',
				'form_field' => 'date-5',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task008' => array(
				'field_label' => 'Task Manager',
				
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task009' => array(
				'field_label' => 'Budget',
				
				'form_field' => 'decimal',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task010' => array(
				'field_label' => 'Status',
				
				'form_field' => 'text',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task011' => array(
				'field_label' => 'Parent Task',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task012' => array(
				'field_label' => 'Dependent Task',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task013' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task014' => array(
				'field_label' => 'Supporting Document',
				'form_field' => 'file',
				
				'acceptable_files_format' => 'doc:::xlsx:::xls:::docx:::pdf:::png:::jpg:::jpeg',
				'class' => ' no-x-padding-1 ',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function project_task_status(){
		return array(
			'project_task_status001' => array(
				'field_label' => 'Task Ref',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'project_task_status002' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'project_task_status003' => array(
				'field_label' => 'Percentage Completion',
				'form_field' => 'decimal',
				'required_field' => 'yes',
				
				'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'project_task_status004' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'project_task_status005' => array(
				'field_label' => 'Supporting Document',
				'form_field' => 'file',
				
				'acceptable_files_format' => 'doc:::xlsx:::xls:::docx:::pdf:::png:::jpg:::jpeg',
				'class' => ' no-x-padding-1 ',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
?>