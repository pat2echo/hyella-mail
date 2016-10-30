<?php 
	
	//SELECT @i :=0;# Rows: 1
	//UPDATE state_list SET serial_num = ( SELECT @i := @i +1 ) ;# 3715 rows affected.
	
	function audit(){
		return array(
			'audit001' => array(
				
				'field_label' => 'Select Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),'id' => array(
				
				'field_label' => 'Select Database File',
				'form_field' => 'file',
				'required_field' => 'yes',
				'acceptable_files_format' => 'ela:::hyella',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'user_mail' => array(
				
				'field_label' => 'User',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'user_action' => array(
				
				'field_label' => 'Action',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'audit004' => array(
				
				'field_label' => 'Class',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'comment' => array(
				
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'date' => array(
				
				'field_label' => 'Date',
				'form_field' => 'datetime',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function appsettings(){
		return array(
			'appsettings001' => array(
				
				'field_label' => 'Name of App',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings002' => array(
				
				'field_label' => 'App Logo',
				'form_field' => 'file',
				'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings004' => array(
				
				'field_label' => 'Slogan',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings005' => array(
				
				'field_label' => 'Contact Address',
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings006' => array(
				
				'field_label' => 'Contact Phone Number',
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				
				'serial_number' => '',
			),
			'appsettings007' => array(
				
				'field_label' => 'Contact Email Address',
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings008' => array(
				
				'field_label' => 'Support Line',
				'form_field' => 'tel',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function general_settings(){
		return array(
			'general_settings001' => array(
				
				'field_label' => 'Key',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings002' => array(
				
				'field_label' => 'Value',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings003' => array(
				
				'field_label' => 'Description',
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings004' => array(
				
				'field_label' => 'Type',
				'form_field' => 'select',
				'form_field_options' => 'get_form_field_types',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				
				'serial_number' => '',
			),
			'general_settings005' => array(
				
				'field_label' => 'Class',
				'form_field' => 'select',
				'form_field_options' => 'get_class_names',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings006' => array(
				
				'field_label' => 'Country',
				'form_field' => 'select',
				'form_field_options' => 'get_countries_general_settings',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings007' => array(
				
				'field_label' => 'Start Date',
				'form_field' => 'date-5',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings008' => array(
				
				'field_label' => 'End Date',
				'form_field' => 'date-5',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function access_roles(){
		return array(
			'access_roles001' => array(
				
				'field_label' => 'Access Role',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'access_roles002' => array(
				
				'field_label' => 'Accessible Functions',
				'form_field' => 'multi-select',
				'required_field' => 'yes',
				
				'form_field_options' => 'get_accessible_functions',
				
				//'display_position' => 'more-details',
				'display_position' => 'display-in-table-row',
				
				'serial_number' => '',
			),
		);
	}
	
	function functions(){
		return array(
			'functions001' => array(
				
				'field_label' => 'Function Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions002' => array(
				
				'field_label' => 'Module Name',
				'form_field' => 'select',
				'form_field_options' => 'get_modules_in_application',
				
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions003' => array(
				
				'field_label' => 'Action',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions004' => array(
				
				'field_label' => 'Class Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions005' => array(
				
				'field_label' => 'Tooltip',
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'functions006' => array(
				
				'field_label' => 'Help Topic',
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function modules(){
		return array(
			'modules001' => array(
				
				'field_label' => 'Module Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'modules002' => array(
				
				'field_label' => 'Module Description',
				'form_field' => 'textarea',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
		);
	}
	
	function myexcel(){
		return array(
			'myexcel005' => array(
				
				'field_label' => 'Excel File',
				'form_field' => 'file',
				'required_field' => 'no',
				
				'acceptable_files_format' => 'xls',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel006' => array(
				
				'field_label' => 'Import Table',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel007' => array(
				
				'field_label' => 'Mapping Options',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_import_file_field_mapping_options',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel008' => array(
				
				'field_label' => 'Import Options',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_file_import_options',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel009' => array(
				
				'field_label' => 'Equating Table Field for Update',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_import_table_fields',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function site_users(){
		return array(
			'site_users001' => array(
				
				//'field_label' => 'First Name',
				'field_label' => SITE_USERS001,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'class' => ' personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users002' => array(
				
				//'field_label' => 'Last Name',
				'field_label' => SITE_USERS002,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'class' => ' personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users003' => array(
				
				//'field_label' => 'Email Address',
				'field_label' => SITE_USERS003,
				'form_field' => 'email',
				'required_field' => 'yes',
				
				'class' => ' personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users004' => array(
				
				//'field_label' => 'Phone Number',
				'field_label' => SITE_USERS004,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'class' => ' personal-info ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users006' => array(
				
				'field_label' => 'Current Password',
				'form_field' => 'old-password',
				'required_field' => 'yes',
				
				'class' => ' old-password password-info ',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'site_users007' => array(
				
				'field_label' => 'Password',
				'form_field' => 'password',
				'required_field' => 'yes',
				
				'class' => ' password-info ',
				
				'tooltip' => 'Minimum of 7 characters required',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'site_users008' => array(
				
				'field_label' => 'Confirm Password',
				'form_field' => 'password',
				'required_field' => 'yes',
				
				'class' => ' password-info ',
				
				'tooltip' => 'Re-type password',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'site_users009' => array(
				
				'field_label' => 'Access Role',
				'form_field' => 'select',
				'form_field_options' => 'get_site_users_access_roles',
				//'form_field_options' => 'get_access_roles',
				
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users010' => array(
				
				'field_label' => 'Department',
				'form_field' => 'select',
				'required_field' => 'no',
				//'form_field_options' => 'get_site_users_access_roles',
                
				'class' => ' personal-info ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users011' => array(
				
				'field_label' => 'Unit',
				'form_field' => 'select',
				'required_field' => 'yes',
				
				'class' => ' personal-info ',
				
				'form_field_options' => 'get_sex',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users012' => array(
				
				'field_label' => 'Job Role',
				'form_field' => 'select',
				'form_field_options' => 'get_countries',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users013' => array(
				
				'field_label' => 'Branch Office',
				'form_field' => 'select',
				'form_field_options' => 'get_countries',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users014' => array(
				
				'field_label' => 'REF NO.',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users015' => array(
				
				'field_label' => 'Passcode',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users016' => array(
				
				'field_label' => 'Assistant',
				'form_field' => 'text',
				
				'class' => ' contact-info ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users018' => array(
				
				'field_label' => 'Photograph',
				'form_field' => 'file',
				
				'class' => ' personal-info ',
				
				'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users019' => array(
				
				'field_label' => 'Push Notification ID',
				'form_field' => 'textarea-unlimited',
				
				'class' => ' personal-info ',
				
				'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function users(){
		return array(
			'users001' => array(
				
				'field_label' => 'First Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users002' => array(
				
				'field_label' => 'Last Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users003' => array(
				
				'field_label' => 'Other Names',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users004' => array(
				
				'field_label' => 'Email Address',
				'form_field' => 'email',
				'required_field' => 'yes',
				'placeholder' => 'Email Address',
				
                'icon' => '<i class="icon-user"></i>',
                
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users005' => array(
				
				'field_label' => 'Phone Number',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users006' => array(
				
				'field_label' => 'Password',
				'form_field' => 'password',
				'required_field' => 'yes',
				'placeholder' => 'Password',
                
				'icon' => '<i class="icon-lock"></i>',
                
				'tooltip' => 'Minimum of 7 characters required',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'users007' => array(
				
				'field_label' => 'Confirm Password',
				'form_field' => 'password',
				'required_field' => 'yes',
				
				'tooltip' => 'Re-type password',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'users008' => array(
				
				'field_label' => 'Old Password',
				'form_field' => 'password',
				'required_field' => 'yes',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'users009' => array(
				
				'field_label' => 'Access Role',
				'form_field' => 'select',
				'form_field_options' => 'get_access_roles',
				
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users010' => array(
				'field_label' => 'Designation',
				
				'form_field' => 'select',
				'form_field_options' => 'get_departments',
				//'required_field' => 'yes',
				'placeholder' => 'Designation',
				
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users020' => array(
				
				'field_label' => 'Grade Level',
				'form_field' => 'select',
				'form_field_options' => 'get_grade_levels',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users015' => array(
				
				'field_label' => 'Staff Ref Num',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users011' => array(
				
				'field_label' => 'Date of Birth',
				'form_field' => 'date-5',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users012' => array(
				
				'field_label' => 'Sex',
				'form_field' => 'select',
				'form_field_options' => 'get_sex',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users013' => array(
				
				'field_label' => 'Residential Address',
				'form_field' => 'text',
				//'form_field_options' => 'get_job_roles',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users014' => array(
				
				'field_label' => 'Date Employed',
				'form_field' => 'date-5',
				//'form_field_options' => 'get_branch_offices',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users016' => array(
				
				'field_label' => 'Bank Account Number',
				'form_field' => 'text',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users017' => array(
				
				'field_label' => 'Bank Name',
				'form_field' => 'select',
				'form_field_options' => 'get_bank_names',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users018' => array(
				
				'field_label' => 'Photograph',
				'form_field' => 'file',
				
				'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),/*
			'users019' => array(
				
				'field_label' => 'Push Notification ID',
				'form_field' => 'textarea-unlimited',
				
				'class' => ' personal-info ',
				
				'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
		);
	}
	
	function reports(){
		return array(
			'reports001' => array(
				
				'field_label' => 'File Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports002' => array(
				
				'field_label' => 'File URL',
				'form_field' => 'file',
				'required_field' => 'yes',
				
				//'acceptable_files_format' => 'xls',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports003' => array(
				
				'field_label' => 'Reference',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_import_file_field_mapping_options',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports004' => array(
				
				'field_label' => 'Source',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports005' => array(
				
				'field_label' => 'Keywords',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports006' => array(
				
				'field_label' => 'Description',
				'form_field' => 'textarea',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function division(){
		return array(
			'division001' => array(
				//dept name
				'field_label' => 'Division Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'division002' => array(
				//desc
				'field_label' => 'Description',
				'form_field' => 'text',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'division003' => array(
				//head
				'field_label' => 'Head of Division',
				'form_field' => 'select',
				'form_field_options' => 'get_website_pages_width',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'division004' => array(
				//Assistant
				'field_label' => 'Assistant Head of Division',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'division005' => array(
				//secretary
				'field_label' => 'Secretary',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function departments(){
		return array(
			'departments001' => array(
				//dept name
				'field_label' => 'Department Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'departments002' => array(
				//desc
				'field_label' => 'Description',
				'form_field' => 'text',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'departments003' => array(
				//head
				'field_label' => 'Head of Department',
				'form_field' => 'select',
				'form_field_options' => 'get_website_pages_width',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'departments004' => array(
				//Assistant
				'field_label' => 'Assistant Head of Department',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'departments005' => array(
				//secretary
				'field_label' => 'Secretary',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function units(){
		return array(
			'units001' => array(
				//dept name
				'field_label' => 'Unit Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'units002' => array(
				//desc
				'field_label' => 'Description',
				'form_field' => 'text',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'units003' => array(
				//head
				'field_label' => 'Head of Unit',
				'form_field' => 'select',
				'form_field_options' => 'get_website_pages_width',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'units004' => array(
				//Assistant
				'field_label' => 'Assistant Head of Unit',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'units005' => array(
				//secretary
				'field_label' => 'Secretary',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function job_roles(){
		return array(
			'job_roles001' => array(
				//Job Title
				'field_label' => 'Job Title',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'job_roles002' => array(
				//desc
				'field_label' => 'Description',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function branch_offices(){
		return array(
			'branch_offices001' => array(
				//Branch Name
				'field_label' => 'Branch Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'branch_offices002' => array(
				//address
				'field_label' => 'Street Address',
				'form_field' => 'text',
				
                'default_appearance_in_table_fields' => 'show',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'branch_offices003' => array(
				//city
				'field_label' => 'City',
				'form_field' => 'text',
				'required_field' => 'yes',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'branch_offices004' => array(
				//state
				'field_label' => 'State',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'branch_offices005' => array(
				//country
				'field_label' => 'Country',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function resource_library(){
		return array(
			'resource_library001' => array(
				//pages
				'field_label' => RESOURCE_LIBRARY001,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'resource_library002' => array(
				//title
				'field_label' => RESOURCE_LIBRARY002,
				'form_field' => 'file',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'resource_library003' => array(
				//content type
				'field_label' => RESOURCE_LIBRARY003,
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function country_list(){
		return array(
			'country_list001' => array(
				
				'field_label' => COUNTRY_LIST001,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'country_list002' => array(
				
				'field_label' => COUNTRY_LIST002,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'country_list003' => array(
				
				'field_label' => COUNTRY_LIST003,
				'form_field' => 'decimal',
				//'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'country_list004' => array(
				
				'field_label' => COUNTRY_LIST004,
				'form_field' => 'text',
				//'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'country_list005' => array(
				
				'field_label' => COUNTRY_LIST005,
				'form_field' => 'select',
				'form_field_options' => 'get_yes_no',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'country_list006' => array(
				
				'field_label' => COUNTRY_LIST006,
				'form_field' => 'select',
				//'form_field_options' => 'get_languages',
				'default_appearance_in_table_fields' => 'show',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'country_list007' => array(
				
				'field_label' => COUNTRY_LIST007,
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function state_list(){
		return array(
			'state_list001' => array(
				
				'field_label' => STATE_LIST001,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'state_list002' => array(
				
				'field_label' => STATE_LIST002,
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'state_list003' => array(
				
				'field_label' => STATE_LIST003,
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_countries',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'state_list004' => array(
				
				'field_label' => STATE_LIST004,
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'state_list005' => array(
				
				'field_label' => STATE_LIST005,
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function cities_list(){
		return array(
			
			'cities_list001' => array(
				
				'field_label' => "Country",
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_countries',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
            'cities_list002' => array(
				
				'field_label' => "State",
				'form_field' => 'calculated',
				'required_field' => 'yes',
				
				'calculations' => array(
					'type' => 'state-id',
					'form_field' => 'text',
					'variables' => array( array( 'cities_list001', 'cities_list002' ) ),
				),
                
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'cities_list003' => array(
				
				'field_label' => "City",
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),/*
			'cities_list004' => array(
				
				'field_label' => CITIES_LIST004,
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
		);
	}
	
	function expenditure(){
	
		$return = array(
			'expenditure001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'expenditure002' => array(
				'field_label' => 'Vendor / Source',
				
				'form_field' => 'select',
				"form_field_options" => "get_vendors",
				//"form_field_options" => "get_vendors_supplier",
				//'required_field' => 'yes',
				
				'placeholder' => '',
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure003' => array(
				'field_label' => 'Description',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'placeholder' => '',
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),/*
			'expenditure009' => array(
				'field_label' => 'Quantity (optional)',
				
				'form_field' => 'number',
				'placeholder' => 'Quantity Supplied',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
			'expenditure004' => array(
				'field_label' => 'Amount Due',
				
				'form_field' => 'currency',
				//'required_field' => 'yes',
				
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure005' => array(
				'field_label' => 'Amount Paid',
				
				'form_field' => 'currency',
				//'required_field' => 'yes',
				
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure006' => array(
				'field_label' => 'Balance',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'difference',
					'form_field' => 'currency',
					'variables' => array( array( 'expenditure004' ), array( 'expenditure005' ) ),
				),
				
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure010' => array(
				'field_label' => 'Mode of Payment',
				
				'form_field' => 'select',
				"form_field_options" => "get_payment_method",
				
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure011' => array(
				'field_label' => 'Receipt No.',
				
				'form_field' => 'text',
				
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure007' => array(
				'field_label' => 'Category',
				
				'form_field' => 'select',
				'required_field' => 'yes',
				"form_field_options" => "get_types_of_expenditure",
				
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure008' => array(
				'field_label' => 'Staff In Charge',
				
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'placeholder' => 'Staff in Charge',
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure012' => array(
				'field_label' => 'REF',
				'form_field' => 'text',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'expenditure013' => array(
				'field_label' => 'Store',
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure014' => array(
				'field_label' => 'Status',
				'form_field' => 'select',
				'form_field_options' => 'get_expenditure_status',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure015' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure016' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure017' => array(
				'field_label' => '% Discount',
				'form_field' => 'decimal',
				
				'class' => ' col-md-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure018' => array(
				'field_label' => 'Tax',
				'form_field' => 'decimal',
				
				'class' => ' col-md-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		
		$capture_payment = get_capture_payment_on_purchase_order_settings();
		if( ! $capture_payment ){
			unset( $return['expenditure005'] );
			unset( $return['expenditure006'] );
			unset( $return['expenditure010'] );
		}
		unset( $return['expenditure006'] );
		unset( $return['expenditure005'] );
		
		return $return;
	}
	
	function production(){
		return array(
			'production001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production002' => array(
				'field_label' => 'Quantity',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production003' => array(
				'field_label' => 'Total Cost',
				
				'form_field' => 'currency',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production004' => array(
				'field_label' => 'Extra Cost',
				'form_field' => 'decimal',
				
				//'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production005' => array(
				'field_label' => 'Factory',
				
				'form_field' => 'select',
				'form_field_options' => 'get_factories',
				'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production006' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				'class' => ' col-lg-6 ',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production007' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				
				//'class' => ' col-lg-6 personal-info ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production008' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stock_status',
				
				//'class' => ' col-lg-6 personal-info ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production009' => array(
				'field_label' => 'Staff Responsible',
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production010' => array(
				'field_label' => 'Reference',
				'form_field' => 'text',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production011' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production012' => array(
				'field_label' => 'Customer',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
		);
	}
	
	function production_items(){
		$return = array(
			'production_items001' => array(
				'field_label' => 'Reference',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'production-ref-num',
					'form_field' => 'text',
					'variables' => array( array( 'production_items001' ) ),
				),
				
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production_items002' => array(
				'field_label' => 'Item',
				
				'form_field' => 'select',
				'form_field_options' => 'get_items_raw_materials',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items003' => array(
				'field_label' => 'Cost',
				
				'form_field' => 'currency',
				'class' => ' col-lg-6 ',
				'required_field' => 'yes',
				
				'placeholder' => 'Unit Cost',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items004' => array(
				'field_label' => 'Quantity',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items005' => array(
				'field_label' => 'Extra Cost',
				'form_field' => 'currency',
				
				//'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items006' => array(
				'field_label' => 'Product Type',
				'form_field' => 'select',
				'form_field_options' => 'get_product_types',
				
				//'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items007' => array(
				'field_label' => 'Amount Due',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'production-items-amount-due',
					'form_field' => 'currency',
					'variables' => array( array( 'production_items003' ), array( 'production_items004' ) ),
					'extra_cost' => array( array( 'production_items005' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items008' => array(
				'field_label' => 'Currency',
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items009' => array(
				'field_label' => 'Opening Stock',
				'form_field' => 'decimal',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		
		if( isset( $_SESSION["production_items"][ "template" ] ) ){
			switch( $_SESSION["production_items"][ "template" ] ){
			case "picking_history":
				return production_items__picking_history();
			break;
			}
			unset( $_SESSION["production_items"][ "template" ] );
		}
		
		return $return;
	}
	
	function production_items__picking_history(){
		$return = array(
			'production_items001' => array(
				'field_label' => 'Reference',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'production-ref-num',
					'form_field' => 'text',
					'variables' => array( array( 'production_items001' ) ),
				),
				
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production_items002' => array(
				'field_label' => 'Item',
				
				'form_field' => 'select',
				'form_field_options' => 'get_items',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items003' => array(
				'field_label' => 'Opening Stock',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'has_value',
					'form_field' => 'decimal',
					'variables' => array( array( 'production_items009' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items004' => array(
				'field_label' => 'Quantity Picked',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items005' => array(
				'field_label' => 'Closing Stock',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'difference',
					'form_field' => 'decimal',
					'variables' => array( array( 'production_items009' ), array( 'production_items004' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		return $return;
	}
	
	function sales(){
		return array(
			'sales001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'sales002' => array(
				'field_label' => 'Quantity',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales003' => array(
				'field_label' => 'Items Cost',
				
				'form_field' => 'currency',
				'required_field' => 'yes',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales004' => array(
				'field_label' => 'Discount',
				'form_field' => 'decimal',
				
				//'class' => ' col-md-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales005' => array(
				'field_label' => 'Discount Type',
				
				'form_field' => 'select',
				'form_field_options' => 'get_discount_types',
				'placeholder' => '',
				
				//'class' => ' col-md-6 ',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales006' => array(
				'field_label' => 'Amount Due',
				'form_field' => 'currency',
				/*
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-items-amount-due',
					'form_field' => 'currency',
					'variables' => array( array( 'sales003' ) ),
					'discount' => array( array( 'sales004' ) ),
				),
				*/
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales007' => array(
				'field_label' => 'Amount Paid',
				
				'form_field' => 'currency',
				'placeholder' => '',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),/*
			'sales011' => array(
				'field_label' => 'Outstanding',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-items-amount-due',
					'form_field' => 'currency',
					'variables' => array( array( 'sales003' ) ),
					'discount' => array( array( 'sales004' ), array( 'sales007' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
			'sales008' => array(
				'field_label' => 'Payment Method',
				
				'form_field' => 'select',
				'form_field_options' => 'get_payment_method',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales009' => array(
				'field_label' => 'Customer',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				//'class' => ' col-md-6 ',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales010' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				//'class' => ' col-md-6 ',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales013' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_sales_status',
				
				//'class' => ' col-md-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales012' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => '',
				
				//'class' => ' col-md-6 personal-info ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales014' => array(
				'field_label' => 'Staff Responsible',
				
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales015' => array(
				'field_label' => 'VAT',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales016' => array(
				'field_label' => 'Service Charge',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales017' => array(
				'field_label' => 'Service Tax',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales018' => array(
				'field_label' => 'REF',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function payment(){
		return array(
			'payment001' => array(
				'field_label' => 'Receipt Num',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-receipt-num',
					'form_field' => 'text',
					'variables' => array( array( 'payment001' ) ),
				),
				
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'payment002' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'payment003' => array(
				'field_label' => 'Amount Paid',
				
				'form_field' => 'currency',
				'class' => ' col-md-6 ',
				'required_field' => 'yes',
				
				'placeholder' => 'Amount Recd',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'payment004' => array(
				'field_label' => 'Payment Method',
				
				'form_field' => 'select',
				"form_field_options" => "get_payment_method",
				'form_field_options_group' => 'get_payment_method_grouped',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'payment005' => array(
				'field_label' => 'Staff Responsible',
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'payment006' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'payment007' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'payment008' => array(
				'field_label' => 'Customer',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'payment009' => array(
				'field_label' => 'Store',
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'payment010' => array(
				'field_label' => 'REF',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'payment011' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function expenditure_payment(){
		return array(
			'expenditure_payment001' => array(
				'field_label' => 'Ref',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-receipt-num',
					'form_field' => 'text',
					'variables' => array( array( 'expenditure_payment001' ) ),
				),
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'expenditure_payment002' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment003' => array(
				'field_label' => 'Amount Paid',
				
				'form_field' => 'currency',
				'class' => ' col-md-6 ',
				'required_field' => 'yes',
				
				'placeholder' => 'Amount Recd',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment004' => array(
				'field_label' => 'Payment Method',
				
				'form_field' => 'select',
				'form_field_options' => 'get_payment_method',
				'form_field_options_group' => 'get_payment_method_grouped',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment005' => array(
				'field_label' => 'Staff Responsible',
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment006' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment007' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment008' => array(
				'field_label' => 'Vendor',
				'form_field' => 'select',
				'form_field_options' => 'get_vendors',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment009' => array(
				'field_label' => 'Store',
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment010' => array(
				'field_label' => 'REF',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment011' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function sales_items(){
		return array(
			'sales_items001' => array(
				'field_label' => 'Receipt Num',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-receipt-num',
					'form_field' => 'text',
					'variables' => array( array( 'sales_items001' ) ),
				),
				
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'sales_items002' => array(
				'field_label' => 'Item',
				
				'form_field' => 'select',
				'form_field_options' => 'get_items',
				'form_field_options_group' => 'get_items_grouped',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items003' => array(
				'field_label' => 'Cost',
				
				'form_field' => 'currency',
				'class' => ' col-md-6 ',
				'required_field' => 'yes',
				
				'placeholder' => 'Unit Cost',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items004' => array(
				'field_label' => 'Quantity',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items005' => array(
				'field_label' => 'Discount',
				'form_field' => 'decimal',
				
				//'class' => ' col-md-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),/*
			'sales_items006' => array(
				'field_label' => 'Discount Type',
				
				'form_field' => 'select',
				'form_field_options' => 'get_discount_types',
				'placeholder' => '',
				
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
			'sales_items007' => array(
				'field_label' => 'Amount Due',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-items-amount-due',
					'form_field' => 'currency',
					'variables' => array( array( 'sales_items003' ), array( 'sales_items004' ) ),
					'discount' => array( array( 'sales_items005' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items008' => array(
				'field_label' => 'Quantity Returned',
				
				'form_field' => 'number',
				'placeholder' => 'Qty. Returned',
				
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items009' => array(
				'field_label' => 'Cost Price',
				
				'form_field' => 'decimal',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items010' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function category(){
		if( function_exists("__category") ){
			return __category();
		}
		
		return array(
			'category001' => array(
				'field_label' => 'Name of Category',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'category002' => array(
				'field_label' => 'Type of Category',
				
				'form_field' => 'select',
				'form_field_options' => 'get_product_types',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function stores(){
		return array(
			'stores001' => array(
				'field_label' => 'Branch Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stores002' => array(
				'field_label' => 'Location',
				
				'form_field' => 'text',
				'placeholder' => '',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stores003' => array(
				'field_label' => 'Phone',
				
				'form_field' => 'text',
				'placeholder' => 'Phone Number',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stores004' => array(
				'field_label' => 'Email',
				'form_field' => 'text',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stores005' => array(
				'field_label' => 'Receipt Message',
				
				'form_field' => 'text',
				'placeholder' => '',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function vendors(){
		return array(
			'vendors001' => array(
				'field_label' => 'Name of Vendor',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'vendors006' => array(
				'field_label' => 'Type',
				'form_field' => 'select',
				'form_field_options' => 'get_type_of_vendor',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'vendors002' => array(
				'field_label' => 'Address',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'placeholder' => '',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'vendors003' => array(
				'field_label' => 'Phone',
				
				'form_field' => 'text',
				'placeholder' => 'Phone Number',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'vendors004' => array(
				'field_label' => 'Email',
				'form_field' => 'text',
				'placeholder' => 'Email',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'vendors005' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				//'required_field' => 'yes',
				'placeholder' => '',
				
				//'class' => ' col-md-6 personal-info ',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function discount(){
		return array(
			'discount001' => array(
				'field_label' => 'Description',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'discount002' => array(
				'field_label' => 'Type',
				'form_field' => 'select',
				'form_field_options' => 'get_discount_types',
				'form_field_options_group' => 'get_discount_types_grouped',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'discount003' => array(
				'field_label' => 'Discount Value',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'discount004' => array(
				'field_label' => 'Minimum Sale Value',
				
				'form_field' => 'decimal',
				
				'tooltip' => 'Enter the minimum sale value prior to discount being automatically applied',
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function pay_row(){
		return array(
			'pay_row001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'pay_row002' => array(
				'field_label' => 'Staff Name',
				
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => "get_employees_with_ref",
				'placeholder' => 'Staff Name',
				'class' => ' select2 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row024' => array(
				'field_label' => 'Staff Ref',
				
				'form_field' => 'text',
				'placeholder' => 'Staff Ref',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row026' => array(
				'field_label' => 'Grade Level',
				
				'form_field' => 'select',
				'form_field_options' => 'get_grade_levels',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row019' => array(
				'field_label' => 'Type of Pay Slip',
				
				'class' => ' col-lg-6 ',
				'form_field' => 'select',
				'form_field_options' => "get_salary_schedule",
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row020' => array(
				'field_label' => 'No. of Days in Month',
				
				'class' => ' col-lg-6 ',
				'required_field' => 'yes',
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row021' => array(
				'field_label' => 'No. of Days Overtime',
				
				'class' => ' col-lg-6 ',
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row022' => array(
				'field_label' => 'No. of Days Absent',
				
				'class' => ' col-lg-6 ',
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row003' => array(
				'field_label' => 'Basic Salary',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				
				'placeholder' => 'Basic Salary',
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row004' => array(
				'field_label' => 'Bonus',
				'form_field' => 'decimal',
				
				'placeholder' => 'Bonus',
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row005' => array(
				'field_label' => 'Housing',
				'form_field' => 'decimal',
				
				'placeholder' => 'Housing Allowance',
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row006' => array(
				'field_label' => 'Transport',
				'form_field' => 'decimal',
				
				'placeholder' => 'Transport',
				'class' => ' col-lg-6 no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row007' => array(
				'field_label' => 'Utility',
				'form_field' => 'decimal',
				
				'placeholder' => 'Utility',
				'class' => ' col-lg-6 no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row008' => array(
				'field_label' => 'Lunch',
				'form_field' => 'decimal',
				
				'placeholder' => 'Lunch',
				'class' => ' col-lg-6 no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row009' => array(
				'field_label' => 'Overtime',
				'form_field' => 'decimal',
				
				'placeholder' => 'Overtime Allowance',
				'class' => ' col-lg-6 no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row010' => array(
				'field_label' => 'Medical Allowance',
				'form_field' => 'decimal',
				
				'placeholder' => 'Medical Allowance',
				'class' => ' col-lg-6 no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row025' => array(
				'field_label' => 'Leave Allowance',
				'form_field' => 'decimal',
				
				'placeholder' => 'Leave Allowance',
				'class' => ' col-lg-6 no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row023' => array(
				'field_label' => 'Other Allowance',
				'form_field' => 'decimal',
				
				'placeholder' => 'Other Allowance',
				//'class' => ' col-lg-6 no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row011' => array(
				'field_label' => 'Paye Deduction',
				
				'form_field' => 'decimal',
				'placeholder' => 'Paye',
				
				'class' => ' col-lg-6 personal-info ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row012' => array(
				'field_label' => 'Pension',
				
				'form_field' => 'decimal',
				'placeholder' => 'Pension',
				
				'class' => ' col-lg-6 personal-info ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row013' => array(
				'field_label' => 'Salary Advance',
				
				'form_field' => 'decimal',
				'placeholder' => 'Loans / Salary Advance',
				
				'class' => ' col-lg-6 personal-info ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row014' => array(
				'field_label' => 'Absent Deduction',
				
				'form_field' => 'decimal',
				'placeholder' => 'Absent',
				
				'class' => ' col-lg-6 personal-info ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row015' => array(
				'field_label' => 'Other Deductions',
				
				'form_field' => 'decimal',
				'placeholder' => 'Other Deductions',
				
				'class' => ' col-lg-6 personal-info ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row016' => array(
				
				'field_label' => 'Gross Salary',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'addition',
					'form_field' => 'decimal',
					'variables' => array( array( 'pay_row003' ), array( 'pay_row004' ), array( 'pay_row005' ), array( 'pay_row006' ), array( 'pay_row007' ), array( 'pay_row008' ),  array( 'pay_row010' ),  array( 'pay_row023' ) ),
					//array( 'pay_row009' ),
					//'subtrend' => array( array( 'pay_row011' ), array( 'pay_row012' ), array( 'pay_row013' ), array( 'pay_row014' ), array( 'pay_row015' ) ),
				),
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_row017' => array(
				
				'field_label' => 'Comment',
				'form_field' => 'textarea',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			)
		);
	}
	
	function pay_roll_post(){
		return array(
			'pay_roll_post001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'pay_roll_post002' => array(
				'field_label' => 'End Date',
				
				'form_field' => 'date-5',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post003' => array(
				'field_label' => 'Gross Pay',
				
				'form_field' => 'decimal',
				'placeholder' => 'Staff Ref',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post004' => array(
				'field_label' => 'Total Deductions',
				
				'form_field' => 'decimal',
				'placeholder' => 'Staff Ref',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post005' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_salary_schedule',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post006' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post007' => array(
				'field_label' => 'Staff Salary',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post008' => array(
				'field_label' => 'Staff Welfare',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post009' => array(
				'field_label' => 'Medical Allowance',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post010' => array(
				'field_label' => 'PAYE Deduction',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post011' => array(
				'field_label' => 'Pension',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post012' => array(
				'field_label' => 'Salary Advance',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post013' => array(
				'field_label' => 'Other Deduction',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function pay_roll_auto_generate(){
		return array(
			'pay_roll_auto_generate001' => array(
				'field_label' => 'Month to Generate',
				
				'form_field' => 'select',
				'form_field_options' => "get_months_of_year",
				'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_auto_generate010' => array(
				'field_label' => 'Year to Generate',
				
				'form_field' => 'select',
				'form_field_options' => "get_calendar_years",
				'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_auto_generate002' => array(
				'field_label' => 'Generation Type',
				
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => "get_salary_generation_type",
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_auto_generate003' => array(
				'field_label' => 'Previous Month for Rollover',
				
				'form_field' => 'select',
				'form_field_options' => "get_months_of_year",
				'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_auto_generate004' => array(
				'field_label' => 'Previous Year for Rollover',
				
				'form_field' => 'select',
				'form_field_options' => "get_calendar_years",
				'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function banks(){
	
		return array(
			'banks001' => array(
				'field_label' => 'Name of Bank',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'banks002' => array(
				'field_label' => 'Bank Location',
				
				'form_field' => 'text',
				
				'placeholder' => '',
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	include "package/".HYELLA_PACKAGE."/Database_table_field_names_interpretation_functions.php";
	
	function import_items(){
		return array(
			'import_items001' => array(
				
				'field_label' => 'Excel File (*.xls, *.xlsx)',
				'form_field' => 'file',
				'required_field' => 'yes',
				
				'acceptable_files_format' => 'xls:::xlsx',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-12 ',
			),/*
			'import_items002' => array(
				
				'field_label' => 'Operator',
				'form_field' => 'select',
				'required_field' => 'yes',
                'form_field_options' => 'get_operators',
				
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
			'import_items003' => array(
				'field_label' => 'Import Template',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-12 ',
				
			),/*
			'import_items005' => array(
			
				'field_label' => 'Month',
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_months_of_year',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'import_items006' => array(
				
				'field_label' => 'Department',
				'form_field' => 'select',
				'form_field_options' => 'get_departments',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'import_items004' => array(
				
				'field_label' => 'Departmental Unit',
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_units',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-6 ',
				
			),
			'import_items007' => array(
				
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
			'import_items008' => array(
				
				'field_label' => 'Starting Row',
				'form_field' => 'number',
				
				'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items009' => array(
				
				'field_label' => 'Pic Code Column',
				'form_field' => 'number',
				
				'attributes' => 'col-label="PIC CODE" ',
				'class' => 'column-select-field',
				'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items010' => array(
				
				'field_label' => 'Description Column',
				'form_field' => 'number',
				
				'attributes' => 'col-label="DESC" ',
				'class' => 'column-select-field',
				'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items011' => array(
				
				'field_label' => 'Weight',
				'form_field' => 'number',
				
				'attributes' => 'col-label="WEIGHT" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items012' => array(
				
				'field_label' => 'Cost Price',
				'form_field' => 'number',
				
				'attributes' => 'col-label="CP" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items013' => array(
				
				'field_label' => 'Selling Price',
				'form_field' => 'number',
				
				'attributes' => 'col-label="SP" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items014' => array(
				
				'field_label' => '% Mark-up',
				'form_field' => 'number',
				
				'attributes' => 'col-label="% MARK UP" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items015' => array(
				
				'field_label' => 'Color',
				'form_field' => 'number',
				
				'attributes' => 'col-label="COLOR" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items016' => array(
				
				'field_label' => 'Category',
				'form_field' => 'number',
				
				'attributes' => 'col-label="CATEGORY" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items030' => array(
				
				'field_label' => 'Vendor',
				'form_field' => 'number',
				
				'attributes' => 'col-label="VENDOR" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items031' => array(
				
				'field_label' => 'Currency',
				'form_field' => 'number',
				
				'attributes' => 'col-label="CUR" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items017' => array(
				
				'field_label' => 'Quantity',
				'form_field' => 'number',
				
				'attributes' => 'col-label="QTY" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items018' => array(
				
				'field_label' => 'BARCODE',
				'form_field' => 'number',
				
				'attributes' => 'col-label="BARCODE" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items019' => array(
				
				'field_label' => 'Recommended Budget N\'000 (optional)',
				'form_field' => 'number',
				
				'attributes' => 'col-label="REC BUDGET N\'000" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items020' => array(
				
				'field_label' => 'Recommended Budget $\'000 (optional)',
				'form_field' => 'number',
				
				'attributes' => 'col-label="REC BUDGET $\'000" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items021' => array(
				
				'field_label' => 'Approved Budget N\'000 (optional)',
				'form_field' => 'number',
				
				'attributes' => 'col-label="APPR BUDGET N\'000" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items022' => array(
				
				'field_label' => 'Approved Budget $\'000 (optional)',
				'form_field' => 'number',
				
				'attributes' => 'col-label="APPR BUDGET $\'000" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items023' => array(
				
				'field_label' => 'Remarks (optional)',
				'form_field' => 'number',
				
				'attributes' => 'col-label="REMARKS" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
		);
	}
	
	function items_raw_data_import(){
		for( $x = 1; $x < 41; ++$x ){
			$key = 'items_raw_data_import0';
			if( $x < 10 )$key .= '0'.$x;
			else $key .= $x;
			
			$return[ $key ] = array(
				'field_label' => 'Column '.($x-1),
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			);
		}
		$return['items_raw_data_import041'] = array(
				
				'field_label' => 'Excel Reference ID',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
		);	
		return $return;
	}
	
	function chart_of_accounts(){
		return array(
			'chart_of_accounts001' => array(
				'field_label' => 'Type of Account',
				
				'form_field' => 'select',
				'form_field_options' => "get_types_of_account",
				'class' => ' personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts002' => array(
				'field_label' => 'Title of Account',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts003' => array(
				'field_label' => 'Code',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts004' => array(
				'field_label' => '1st Parent',
				
				'form_field' => 'select',
				'form_field_options' => "get_first_level_accounts",
				'class' => ' personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts005' => array(
				'field_label' => '2nd Parent',
				
				'form_field' => 'select',
				'form_field_options' => "get_second_level_accounts",
				'class' => ' personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts006' => array(
				'field_label' => '3rd Parent',
				
				'form_field' => 'select',
				'form_field_options' => "get_second_level_accounts",
				'class' => ' personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function transactions(){
		return array(
			'transactions001' => array(
				'field_label' => 'Date',
				
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions002' => array(
				'field_label' => 'Description',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions003' => array(
				'field_label' => 'Reference',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions004' => array(
				'field_label' => 'Tag',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions005' => array(
				'field_label' => 'Debit',
				
				'default_appearance_in_table_fields' => 'show',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'debit-transactions',
					'form_field' => 'text',
					'variables' => array( array( 'id' ) ),
				),
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions010' => array(
				'field_label' => 'Credit',
				
				'form_field' => 'calculated',
				'default_appearance_in_table_fields' => 'show',
				
				'calculations' => array(
					'type' => 'credit-transactions',
					'form_field' => 'text',
					'variables' => array( array( 'id' ) ),
				),
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions006' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => "get_transaction_status",
				
				//'default_appearance_in_table_fields' => 'show',
				'display_position' => 'do-not-display-in-table',
				//'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions007' => array(
				'field_label' => 'Submitted By',
				
				'form_field' => 'select',
				'form_field_options' => "get_employees",
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions008' => array(
				'field_label' => 'Submitted On',
				
				'form_field' => 'date-5',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions009' => array(
				'field_label' => 'Location',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions011' => array(
				'field_label' => 'REF',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function debit_and_credit(){
		return array(
			'debit_and_credit001' => array(
				'field_label' => 'Transaction Ref',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit002' => array(
				'field_label' => 'Account',
				
				'form_field' => 'calculated',
				'required_field' => 'yes',
				
				'calculations' => array(
					'type' => 'account-name',
					'form_field' => 'text',
					'variables' => array( array( 'debit_and_credit002', 'debit_and_credit005' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit003' => array(
				'field_label' => 'Amount',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit004' => array(
				'field_label' => 'Type',
				
				'form_field' => 'select',
				'form_field_options' => 'get_transaction_type',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'debit_and_credit005' => array(
				'field_label' => 'Acc Type',
				
				'form_field' => 'text',
				//'form_field_options' => 'get_first_level_accounts',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'debit_and_credit006' => array(
				'field_label' => 'REF',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit007' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function transactions_draft(){
		$r = transactions();
		
		$r[ 'transactions005' ][ 'calculations' ][ 'type' ] = 'debit-draft-transactions';
		$r[ 'transactions010' ][ 'calculations' ][ 'type' ] = 'credit-draft-transactions';
		
		$return = array();
		foreach( $r as $rk => $rv ){
			$return[ str_replace( 'transactions', 'transactions_draft', $rk ) ] = $rv;
		}
		return $return;
	}
	
	function debit_and_credit_draft(){
		$r = debit_and_credit();
		$return = array();
		foreach( $r as $rk => $rv ){
			$return[ str_replace( 'debit_and_credit', 'debit_and_credit_draft', $rk ) ] = $rv;
		}
		return $return;
	}
	
	function grade_level(){
		return array(
			'grade_level001' => array(
				'field_label' => 'Grade Level',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'grade_level002' => array(
				'field_label' => 'Step',
				
				'form_field' => 'number',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'grade_level003' => array(
				'field_label' => 'Basic Salary',
				
				'form_field' => 'decimal',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'grade_level004' => array(
				'field_label' => 'Housing Allowance',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),/*
			'grade_level005' => array(
				'field_label' => 'Per Child Bonus',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'grade_level006' => array(
				'field_label' => 'Per Dependent Bonus',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),*/
			'grade_level007' => array(
				'field_label' => 'Medical Allowance',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'grade_level008' => array(
				'field_label' => 'Transport Allowance',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'grade_level009' => array(
				'field_label' => 'Utility Allowance',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'grade_level010' => array(
				'field_label' => 'Lunch Allowance',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'grade_level012' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => "get_salary_schedule",
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function assets(){
		return array(
			'assets001' => array(
				'field_label' => 'Asset Description',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets002' => array(
				'field_label' => 'Asset Category',
				
				'form_field' => 'select',
				'form_field_options' => 'get_assets_category',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets003' => array(
				'field_label' => 'Identification No.',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets004' => array(
				'field_label' => 'Barcode',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets005' => array(
				'field_label' => 'Purchase Date',
				
				'form_field' => 'date-5',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'assets006' => array(
				'field_label' => 'Cost of Purchase',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'assets016' => array(
				'field_label' => 'Useful Life',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'assets007' => array(
				'field_label' => 'Salvage Value',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets008' => array(
				'field_label' => 'Gross Value',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets017' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets009' => array(
				'field_label' => 'Location',
				
				'form_field' => 'text',
				//'form_field_options' => 'get_stores',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets010' => array(
				'field_label' => 'Supplier',
				
				'form_field' => 'select',
				'form_field_options' => 'get_vendors',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'assets011' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets012' => array(
				'field_label' => 'Picture',
				
				'form_field' => 'file',
				'acceptable_files_format' => 'png:::jpg:::jpeg',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets013' => array(
				'field_label' => 'Supporting Document',
				
				'form_field' => 'file',
				'acceptable_files_format' => 'png:::jpg:::jpeg:::pdf:::docx:::doc:::ppt:::pptx:::xls:::xlsx',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets014' => array(
				'field_label' => 'Reference',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets015' => array(
				'field_label' => 'Reference Table',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function assets_category(){
		return array(
			'assets_category001' => array(
				'field_label' => 'Category Name',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets_category002' => array(
				'field_label' => 'Type of Depreciation',
				
				'form_field' => 'select',
				'form_field_options' => 'get_type_of_depreciation',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets_category003' => array(
				'field_label' => 'Computation Method',
				
				'form_field' => 'select',
				'form_field_options' => 'get_computation_methods',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets_category004' => array(
				'field_label' => 'Time Method',
				
				'form_field' => 'select',
				'form_field_options' => 'get_time_methods',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets_category005' => array(
				'field_label' => 'Number of Depreciations',
				
				'form_field' => 'number',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'assets_category006' => array(
				'field_label' => 'Number of Months in a Period',
				
				'form_field' => 'number',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'assets_category007' => array(
				'field_label' => 'Ending Date',
				
				'form_field' => 'date-5',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
		);
	}
	
	function customer_deposits(){
		return array(
			'customer_deposits001' => array(
				'field_label' => 'Date',
				
				'form_field' => 'date-5',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits002' => array(
				'field_label' => 'Customer Name',
				
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				'class' => ' select2 ',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits003' => array(
				'field_label' => 'Amount Deposited',
				
				'form_field' => 'decimal',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits004' => array(
				'field_label' => 'Amount Withdrawn',
				
				'form_field' => 'decimal',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits010' => array(
				'field_label' => 'Payment Method',
				
				'form_field' => 'select',
				'form_field_options' => 'get_payment_method',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits005' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => 'Optional Comment',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits006' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits007' => array(
				'field_label' => 'Reference Table',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits008' => array(
				'field_label' => 'Reference',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits009' => array(
				'field_label' => 'Store',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
    /*
    ALTER TABLE `budget_details` CHANGE `serial_num` `serial_num` INT(11) NOT NULL;
	ALTER TABLE `budget_details` DROP PRIMARY KEY;
	
	SELECT @i :=0;
	UPDATE keep_cash_calls SET serial_num = ( SELECT @i := @i +1 ) ;
	ALTER TABLE `cash_calls` ADD PRIMARY KEY(`serial_num`);
	ALTER TABLE `cash_calls` CHANGE `serial_num` `serial_num` INT(11) NOT NULL AUTO_INCREMENT;
	
    ADD `serial_num` INT NOT NULL AFTER `dhl_international_cost004`,
    
	ALTER TABLE `newsletters`  ADD `serial_num` INT NOT NULL AFTER `receipients_data1_data13`, ADD `creator_role` VARCHAR(33) NOT NULL AFTER `serial_num`,  ADD `created_by` VARCHAR(33) NOT NULL AFTER `creator_role`,  ADD `creation_date` INT NOT NULL AFTER `created_by`,  ADD `modified_by` VARCHAR(33) NOT NULL AFTER `creation_date`,  ADD `modification_date` INT NOT NULL AFTER `modified_by`,  ADD `ip_address` INT NOT NULL AFTER `modification_date`
    
	ALTER TABLE `support_enquiry` ADD `creator_role` VARCHAR(33) NOT NULL AFTER `serial_num`,  ADD `created_by` VARCHAR(33) NOT NULL AFTER `creator_role`,  ADD `creation_date` INT NOT NULL AFTER `created_by`,  ADD `modified_by` VARCHAR(33) NOT NULL AFTER `creation_date`,  ADD `modification_date` INT NOT NULL AFTER `modified_by`,  ADD `ip_address` INT NOT NULL AFTER `modification_date`
	*/
?>