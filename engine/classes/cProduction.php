<?php
	/**
	 * production Class
	 *
	 * @used in  				production Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	production
	 */

	/*
	|--------------------------------------------------------------------------
	| production Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cProduction{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'production';
		
		private $associated_cache_keys = array(
			'production',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'production001',
			'quantity' => 'production002',
			'cost' => 'production003',
			'extra_cost' => 'production004',
			
			'factory' => 'production005',
			'store' => 'production006',
			
			'comment' => 'production007',
			'status' => 'production008',
			
			'staff_responsible' => 'production009',
			'reference' => 'production010',
			'reference_table' => 'production011',
			'customer' => 'production012',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 0,			//Determines whether or not to show add new record button
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
	
		function production(){
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
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'display_all_records':
				unset( $_SESSION[$this->table_name]['filter']['show_my_records'] );
				unset( $_SESSION[$this->table_name]['filter']['show_deleted_records'] );
				
				$returned_value = $this->_display_data_table();
			break;
			case 'display_deleted_records':
				$_SESSION[$this->table_name]['filter']['show_deleted_records'] = 1;
				
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
			case 'delete_only':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_datatable_view':
				$returned_value = $this->_display_datatable_view();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case "track_invoice":
				$returned_value = $this->_track_invoice();
			break;
			case "view_invoice_app":
			case "view_invoice":
				$returned_value = $this->_view_invoice();
			break;
			case "display_all_financial_reports_full_view":
				$returned_value = $this->_display_all_financial_reports_full_view();
			break;
			case 'get_financial_reports':
				$returned_value = $this->_get_financial_reports();
			break;
			case 'get_users_bar_chart':
			case 'get_users_pie_chart':
				$returned_value = $this->_get_users_pie_chart();
			break;
			case 'refresh_production_info':
				$returned_value = $this->_refresh_production_info();
			break;
			case 'save_production_and_return_receipt':
				$returned_value = $this->_save_production_and_return_receipt();
			break;
			case 'display_app_production_report':
				$returned_value = $this->_display_app_production_report();
			break;
			case 'update_production_status':
				$returned_value = $this->_update_production_status();
			break;
			case 'save_update_production_status':
				$returned_value = $this->_save_site_changes();
			break;
			case 'delete_production_manifest':
				$returned_value = $this->_delete_production_manifest();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'search_sales_invoice_picking_slips':
				$returned_value = $this->_search_sales_invoice_picking_slips();
			break;
			case 'get_picking_slips':
				$returned_value = $this->_get_picking_slips();
			break;
			}
			
			return $returned_value;
		}
		
		private function _get_picking_slips(){
			if( ! ( isset( $this->class_settings["reference_table"] ) && $this->class_settings["reference_table"] ) )return 0;
			
			$select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`created_by`, `".$this->table_name."`.`".$this->table_fields["date"]."` as 'date', `".$this->table_name."`.`".$this->table_fields["staff_responsible"]."` as 'staff_responsible', `".$this->table_name."`.`".$this->table_fields["quantity"]."` as 'quantity', `".$this->table_name."`.`".$this->table_fields["comment"]."` as 'comment' ";
			
			switch( $this->class_settings["reference_table"] ){
			case "sales":
				//get previous picking slips
				$produced_items = new cProduction_items();
				$select .= ", `".$produced_items->table_name."`.`".$produced_items->table_fields["item_id"]."` as 'item_id', `".$produced_items->table_name."`.`".$produced_items->table_fields["quantity"]."` as 'unit_quantity' ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$produced_items->table_name."` ON `".$produced_items->table_name."`.`".$produced_items->table_fields["production_id"]."` = `".$this->table_name."`.`id` WHERE `".$this->table_name."`.`record_status`='1' AND `".$produced_items->table_name."`.`record_status`='1' AND `".$this->table_name."`.`".$this->table_fields["reference"]."` = '".$this->class_settings["reference"]."' AND `".$this->table_name."`.`".$this->table_fields["reference_table"]."` = '".$this->class_settings["reference_table"]."' GROUP BY `".$produced_items->table_name."`.`id` ORDER BY `".$this->table_name."`.`".$this->table_fields["date"]."` DESC ";
			break;
			case "cart":
				$produced_items = new cProduction_items();
				$select .= ", ( SELECT SUM( `".$produced_items->table_name."`.`".$produced_items->table_fields["quantity"]."` ) FROM `" . $this->class_settings['database_name'] . "`.`".$produced_items->table_name."` WHERE `".$produced_items->table_name."`.`".$produced_items->table_fields["production_id"]."` = `".$this->table_name."`.`id` AND `".$produced_items->table_name."`.`record_status`='1' ) as 'quantity_picked', ( SELECT SUM( `".$produced_items->table_name."`.`".$produced_items->table_fields["quantity_instock"]."` ) FROM `" . $this->class_settings['database_name'] . "`.`".$produced_items->table_name."` WHERE `".$produced_items->table_name."`.`".$produced_items->table_fields["production_id"]."` = `".$this->table_name."`.`id` AND `".$produced_items->table_name."`.`record_status`='1' ) as 'quantity_instock' ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status`='1' AND `".$this->table_name."`.`".$this->table_fields["status"]."` IN ( 'materials-utilized', 'damaged-materials' ) ORDER BY `".$this->table_name."`.`".$this->table_fields["date"]."` DESC LIMIT 10 ";
			break;
			}
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			return execute_sql_query($query_settings);
		}
			
		private function _search_sales_invoice_picking_slips(){
			if( ! ( isset( $this->class_settings["reference_table"] ) && $this->class_settings["reference_table"] ) )return 0;
			if( ! ( isset( $this->class_settings["reference"] ) && $this->class_settings["reference"] ) )return 0;
			if( ! ( isset( $this->class_settings["sales_info"] ) && $this->class_settings["sales_info"] ) )return 0;
			
			$all_data = $this->_get_picking_slips();
			$returning_html_data1 = "";
			
			if( isset( $all_data[0]["id"] ) && $all_data[0]["id"] ){
				$this->class_settings[ 'data' ][ "picking_slips" ] = $all_data;
			}
			
			$this->class_settings[ 'data' ]['staff_responsible'] = get_employees();
			$this->class_settings[ 'data' ]["sales_info"] = $this->class_settings["sales_info"];
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-new-picking-slip' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _record_in_accounting(){
			
			if( ! ( isset( $this->class_settings["current_record_id"] ) && $this->class_settings["current_record_id"] ) )return 0;
			
			$this->class_settings["production_id"] = $this->class_settings["current_record_id"];
			$e = $this->_get_production();
			
			$production_items = new cProduction_items();
			$production_items->class_settings = $this->class_settings;
			$production_items->class_settings["action_to_perform"] = "get_specific_production_items";
			$i = $production_items->production_items();
			
			$items_categories = array();
			
			$cost = 0;
			if( is_array( $i ) && ! empty( $i ) ){			
				foreach( $i as $items ){
					$item_details = get_items_details( array( "id" => $items["item_id"] ) );
					if( isset( $item_details["category"] ) ){
						if( ! isset( $items_categories[ $item_details["category"] ] ) ){
							$items_categories[ $item_details["category"] ] = 0;
						}
						$items_categories[ $item_details["category"] ] += $items["quantity"] * $items["cost"];
					}
					$cost += $items["quantity"] * $items["cost"];
				}
			}
			
			if( ! $cost )return 0;
			
			$dc = array();
			
			$rv = __map_financial_accounts();
			//$expense_account = $rv[ "used_goods" ];
			$expense_account = $rv[ "cost_of_goods_sold" ];
			$expense_account_type = $rv[ "cost_of_goods_sold" ]; //$rv[ "operating_expense" ];
			
			$inventory_account = $rv[ "inventory" ];
			
			$desc = "Used Goods ";
			switch( $e["status"] ){
			case 'damaged-materials':
				$expense_account = $rv[ "damaged_goods" ];
				$expense_account_type = $rv[ "damaged_goods" ];
				$desc = "Damaged Goods #".mask_serial_number( $e["serial_num"], 'IN' ) . "";
			break;
			case 'sales-order':
				$expense_account = $rv[ "cost_of_goods_sold" ];
				$expense_account_type = $rv[ "cost_of_goods_sold" ];
				
				$si = get_sales_details( array( "id" => $e["reference"] ) );
				$s_ref = '';
				if( isset( $si["serial_num"] ) )$s_ref = mask_serial_number( $si["serial_num"], 'S' );
				$desc = "Cost of Sales #".mask_serial_number( $e["serial_num"], 'IN' ) . " for sales #" . $s_ref . " ";
			break;
			default:
				$desc = "Material Used #".mask_serial_number( $e["serial_num"], 'IN' ) . " ";
			break;
			}
			
			if( isset( $this->class_settings["production"]["account"] ) && isset( $rv[ $this->class_settings["production"]["account"] ] ) ){
				
				$expense_account = $rv[ $this->class_settings["production"]["account"] ];
				$expense_account_type = $rv[ $this->class_settings["production"]["account"] ];
				$desc = "Material Used for Marketing #".mask_serial_number( $e["serial_num"], 'IN' ) . " ";
			}
			
			if( ! empty( $items_categories ) ){
				foreach( $items_categories as $cat => $am ){
					$final = $am;
					
					$dc[] = array(
						"transaction_id" => $e["id"],
						"account" => $cat,
						"amount" => $final,
						"type" => "debit",
						"account_type" => $expense_account_type,
					);
					
					$dc[] = array(
						"transaction_id" => $e["id"],
						"account" => $cat,
						"amount" => $final,
						"type" => "credit",
						"account_type" => $rv[ "inventory" ],
					);
				}
			}else{
				//debit operating expense
				$dc[] = array(
					"transaction_id" => $e["id"],
					"account" => $expense_account,
					"amount" => $cost,
					"type" => "debit",
					"account_type" => $expense_account_type,
				);
					
				//credit inventory for goods or raw materials that would be stocked
				$dc[] = array(
					"transaction_id" => $e["id"],
					"account" => $inventory_account,
					"amount" => $cost,
					"type" => "credit",
					"account_type" => $rv[ "inventory" ],
				);
			}
			
			$data = array(
				"id" => $e["id"] ,
				"date" => date( "Y-n-j", doubleval( $e["date"] ) ) ,
				"reference" => $e["id"] ,
				"reference_table" => $this->table_name,
				"description" => $desc . ( ( $e["comment"] )?$e["comment"]:"" ),
				"credit" => $cost,
				"debit" => $cost,
				"status" => "approved",
				'submitted_by' => $e["staff_responsible"],
				'submitted_on' => $e["creation_date"],
				'store' => $e["store"],
				'item' => $dc,
			);
			
			$transactions = new cTransactions();
			$transactions->class_settings = $this->class_settings;
			$transactions->class_settings["data"] = $data;
			$transactions->class_settings["action_to_perform"] = "add_transaction_from_sales";
			return $transactions->transactions();
		}
		
		private function _refresh_cache(){
			//empty permanent cache folder
			clear_cache_for_special_values_directory( array(
				"permanent" => true,
				"directory_name" => $this->table_name,
			) );
			
			unset( $this->class_settings['user_id'] );
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->class_settings[ 'current_record_id' ] = 'pass_condition';
			$this->_get_production();
		}
		
		private function _delete_production_manifest(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			$_POST["mod"] = "delete-".md5( $this->table_name );
			$return = $this->_delete_records();
			
			if( isset( $return['deleted_record_id'] ) && $return['deleted_record_id'] ){
				$this->class_settings["production_id"] = $return['deleted_record_id'];
				
				//delete inventory
				$inventory = new cInventory();
				$inventory->class_settings = $this->class_settings;
				$inventory->class_settings["action_to_perform"] = 'delete_goods_produced';
				$inventory->inventory();
				
				$return["html_removal"] = "#" . $return['deleted_record_id'] ;
				
				$return["html_replace_selector"] = "#manifest-" . $return['deleted_record_id'];
				
				$project = get_project_data();
				$return["html_replace"] = '<div style="text-align:center;"><img src="'.$project['domain_name'].'frontend-assets/img/logo_blue.png" alt="" align="center"></div>';
			}
			
			unset( $return["html"] );
			$return["status"] = "new-status";
			
			return $return;
		}
		
		private function _update_production_status(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$this->class_settings["production_id"] = $this->class_settings["current_record_id"];
			$e["event"] = $this->_get_production();
			
			$this->class_settings["date"] = $e["event"]["date"];
			$this->class_settings["store"] = $e["event"]["store"];
			$this->class_settings["source"] = $e["event"]["factory"];
			$this->class_settings["vendor"] = $e["event"]["factory"];
			
			$_POST["mod"] = "edit-".md5( $this->table_name );
			
			$this->class_settings["do_not_show_headings"] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["date"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["extra_cost"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["quantity"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["cost"] ] = 1;
			
			if( isset( $e["event"]["status"] ) && ( $e["event"]["status"] == "materials-transfer"  ) ){
				$this->class_settings['disable_form_element'][ $this->table_fields["factory"] ] = ' disabled="disabled" ';
				$this->class_settings["hidden_records"][ $this->table_fields["status"] ] = 1;
			}
			
			if( isset( $e["event"]["status"] ) && ( $e["event"]["status"] == "materials-utilized" || $e["event"]["status"] == "damaged-materials" ) ){
				$this->class_settings['hidden_records'][ $this->table_fields["store"] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields["factory"] ] = 1;
				$this->class_settings["hidden_records"][ $this->table_fields["status"] ] = 1;
			}
			
			$this->class_settings[ 'form_action_todo' ] = 'save_update_production_status';
			
			$e["production_form"] = $this->_generate_new_data_capture_form();
			
			$production_items = new cProduction_items();
			$production_items->class_settings = $this->class_settings;
			$production_items->class_settings["action_to_perform"] = 'view_all_production_items_editable';
			$e['materials'] = $production_items->production_items();
			
			$title = "Update Production Manifest Status";
			
			if( ! ( isset( $e["event"]["status"] ) && ( $e["event"]["status"] == "materials-utilized" || $e["event"]["status"] == "damaged-materials" ) ) ){
				if( isset( $e["event"]["status"] ) && $e["event"]["status"] == "materials-transfer" ){
					$inventory = new cInventory();
					$inventory->class_settings = $this->class_settings;
					$inventory->class_settings["action_to_perform"] = 'view_all_produced_items_editable';
					$e['produced_items'] = $inventory->inventory();
					
					$title = "Update Material Transfer Status";
				}else{
					
					$expenditure = new cExpenditure();
					$expenditure->class_settings = $this->class_settings;
					$expenditure->class_settings["action_to_perform"] = 'view_all_production_items_editable';
					$e['expenses'] = $expenditure->expenditure();
				}
			}else{
				$title = "Update Material Utilization Status";
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/production-manifest-status-update.php' );
			$this->class_settings[ 'data' ] = $e;
			$this->class_settings[ 'data' ]["backend"] = 1;
			
			if( isset( $e["event"]["status"] ) && $e["event"]["status"] == "materials-transfer" ){
				$this->class_settings[ 'data' ]["goods_produced_title"] = "Goods Received";
				$this->class_settings[ 'data' ]["raw_materials_title"] = "Goods Issued";
			}
			
			$html = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = $title;
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( "prepare_new_record_form_new" , "set_function_click_event" ),
			);
			
		}
		
		private function _display_app_production_report(){
			
			$this->class_settings[ 'return_data' ] = 1;
			$_GET["month"] = "date;;;".date("Y;;;n");
			$this->class_settings[ 'data' ][ 'this_month' ] = $this->_generate_farm_report();
			
			$this_month = date("n");
			$this_year = date("Y");
			
			$_GET["month"] = "date;;;".$this_year;
			$this->class_settings[ 'data' ][ 'this_year' ] = $this->_generate_farm_report();
			
			if( --$this_month < 1 ){
				$this_month = 12;
				--$this_year;
			}
			
			//$_GET["month"] = "date;;;".$this_year.";;;".$this_month;
			//$this->class_settings[ 'data' ][ 'last_month' ] = $this->_generate_farm_report();
			
			$file = "display-app-production";
			$selector = "#dash-board-main-content-area";
			$js = array();
			
			$where = "";
			if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields[ 'store' ]."` = '" . $_SESSION[ "store" ] . "' OR `".$this->table_name."`.`".$this->table_fields[ 'factory' ]."` = '" . $_SESSION[ "store" ] . "' ) ";
			}
				
			$_GET["month"] = "date;;;".$this_year.";;;".$this_month;
			$this->class_settings["reset_conditions"] = " WHERE `".$this->table_name."`.`record_status` = '1' ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields[ "date" ]."` DESC LIMIT 30 ";
			$this->class_settings[ 'data' ][ 'recent_expenses' ] = $this->_generate_farm_report();
			unset( $this->class_settings["reset_conditions"] );
			
			$js = array( 'set_function_click_event' );
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$file );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $selector,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js 
			);
		}
		
		private function _generate_farm_report(){
			$returning_html_data = "";
			
			$field_name = "";
			$grouping = 0;
			
			$regard_store = 1;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'display_app_production_report':
			break;
			case "generate_production_report":
				$regard_store = 0;
			break;
			}
			
			$field_def = "`".$this->table_name."`.`".$this->table_fields[ "date" ]."`";
			
			if( isset( $this->class_settings["field_def"] ) && $this->class_settings["field_def"] ){
				$field_def = $this->class_settings["field_def"];
			}
			
			if( isset( $_GET["month"] ) && $_GET["month"] ){
				$pieces = explode( ";;;" , $_GET["month"] );
				if( isset( $pieces[0] ) && $pieces[0] ){
					$field_name = $pieces[0];
				}
				
				$y = 0;
				$m = 1;
				$d = 0;
				$em = 0;
				
				$where = "";
				$title = "";
			
				$start_date = 0;
				$end_date = 0;
				if( isset( $pieces[1] ) && $pieces[1] ){
					$y = intval( $pieces[1] );
					
					$m = 0;
					if( isset( $pieces[2] ) && $pieces[2] ){
						$m = intval( $pieces[2] );
						$em = $m + 1;
						if( $em > 12 ){
							$em = 1;
							++$y;
						}
						if( isset( $pieces[3] ) && $pieces[3] ){
							$em = 0;
							$d = intval( $pieces[3] );
						}
						
					}
					
					$title = "PRODUCTION RECORDS FOR ";
					if( $em ){
						$start_date = mktime(0,0,0, $m , 1, $y );
						$end_date = mktime(0,0,0, $em , 1, $y );
						 $title .= date( "F, Y", $start_date );
					}else{
						if( $d ){
							$start_date = mktime(0,0,0, $m , $d, $y );
							$title .= date( "jS F, Y", $start_date );
						}else{
							$start_date = mktime(0,0,0, 1 , 1, $y );
							$end_date = mktime(0,0,0, 1 , 1, ($y+1) );
							$title .= date( "Y", $start_date );
							$grouping = 1;
						}
					}
					
					if( $start_date && $end_date ){
						$where = " AND ".$field_def." >= " . $start_date . " AND ".$field_def." <= " . $end_date;
					}elseif( $start_date ){
						$where = " AND ".$field_def." = " . $start_date;
					}
				}
				
			}
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			if( $field_name ){
				
				if( $regard_store && isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$where .= " AND ( `".$this->table_name."`.`".$this->table_fields[ 'store' ]."` = '" . $_SESSION[ "store" ] . "' OR `".$this->table_name."`.`".$this->table_fields[ 'factory' ]."` = '" . $_SESSION[ "store" ] . "' ) ";
				}
					
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status`='1' ".$where." GROUP BY `".$this->table_name."`.`id` ORDER BY `".$this->table_name."`.`".$this->table_fields[ $field_name ]."` DESC ";
				
				if( isset( $this->class_settings["reset_conditions"] ) && $this->class_settings["reset_conditions"] ){
					$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` " . $this->class_settings["reset_conditions"];
				}
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$all_data = execute_sql_query($query_settings);
				
				
				if( $grouping == 1 ){
					//group data based on year
					$all_new = array();
					foreach( $all_data as $sval ){
						$key = date( "F", doubleval( $sval["date"] ) );
						if( isset( $all_new[ $key ] ) ){
							foreach( $sval as $k => $v ){
								switch( $k ){
								case "extra_cost":
								case "cost":
								case "quantity":
									$all_new[ $key ][ $k ] += doubleval( $v );
								break;
								case "store":
								case "staff_responsible":
									$all_new[ $key ][ $k ] =  "*Several";
								break;
								}
							}
						}else{
							$all_new[ $key ] = $sval;
						}
					}
					$all_data = $all_new;
					$this->class_settings[ 'data' ][ 'date_filter' ] = "F";
				}
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-report' );
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'report_type' ] = "daily_report";
				$this->class_settings[ 'data' ][ 'report_data' ] = $all_data;
				
				if( isset( $this->class_settings[ 'return_data' ] ) && $this->class_settings[ 'return_data' ] ){
					return $this->class_settings[ 'data' ];
				}
				
				$returning_html_data = $this->_get_html_view();
				//$returning_html_data = $query;
				
			}else{
				//return error message
			}
			
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#data-table-section",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				//'javascript_functions' => array( 'activate_tree_view', 'set_function_click_event' ) 
			);
		}
		
		private function _save_production_and_return_receipt(){
			
			if( ! ( isset( $this->class_settings["production"] ) && is_array( $this->class_settings["production"] ) ) ){
				return 0;
			}
			if( ! ( isset( $this->class_settings[ 'production_id' ] ) ) ){
				return 0;
			}
			
			$array_of_dataset = array();
			
			$new_record_id = $this->class_settings[ 'production_id' ];
			
			$ip_address = get_ip_address();
			$date = date("U");
			
			$comment = "Materials Utilized";
			if( isset( $this->class_settings["production"]["comment"] ) ){
				$comment = $this->class_settings["production"]["comment"];
			}
			
			$dataset_to_be_inserted = array(
				'id' => $new_record_id,
				'created_role' => $this->class_settings[ 'priv_id' ],
				'created_by' => $this->class_settings[ 'user_id' ],
				'creation_date' => $date,
				'modified_by' => $this->class_settings[ 'user_id' ],
				'modification_date' => $date,
				'ip_address' => $ip_address,
				'record_status' => 1,
				
				$this->table_fields["date"] => $date,
				
				$this->table_fields["extra_cost"] => $this->class_settings["production"]["extra_cost"],
				$this->table_fields["cost"] => $this->class_settings["production"]["total_cost"],
				$this->table_fields["quantity"] => isset( $this->class_settings["production"]["quantity"] )?$this->class_settings["production"]["quantity"]:1,
				$this->table_fields["factory"] => $this->class_settings["production"]["factory"],
				$this->table_fields["status"] => $this->class_settings["production"]["stock_status"],
				$this->table_fields["staff_responsible"] => $this->class_settings["production"][ 'staff_responsible' ],
				$this->table_fields["comment"] => ( isset( $this->class_settings["goods_produced"] )?( "Production of " . $this->class_settings["goods_produced"] ) : $comment ),
				$this->table_fields["reference"] => ( isset( $this->class_settings["production"]["reference"] )?$this->class_settings["production"]["reference"] : "" ),
				$this->table_fields["reference_table"] => ( isset( $this->class_settings["production"]["reference_table"] )?$this->class_settings["production"]["reference_table"] : "" ),
				$this->table_fields["customer"] => ( isset( $this->class_settings["production"]["customer"] )?$this->class_settings["production"]["customer"] : "" ),
				$this->table_fields["store"] => $this->class_settings["production"][ 'store' ],
			);
			
			//new
			$array_of_dataset[] = $dataset_to_be_inserted;
				
			$saved = 0;
			if( ! empty( $array_of_dataset ) ){
				
				$function_settings = array(
					'database' => $this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'table' => $this->table_name,
					'dataset' => $array_of_dataset,
				);
				
				$returned_data = insert_new_record_into_table( $function_settings );
				
				if( isset( $this->class_settings["production"]["stock_status"] ) ){
					switch( $this->class_settings["production"]["stock_status"] ){
					case "materials-utilized":
					case "sales-order":
					case "damaged-materials":
						//record in accounting
						$this->class_settings["current_record_id"] = $this->class_settings[ 'production_id' ];
						$this->_record_in_accounting();
					break;
					}
				}
				/*
				//Enable for single cost expense i.e extra cost during production
				if( doubleval( $this->class_settings["production"]["extra_cost"] ) ){
					$expenditure = new cExpenditure();
					$expenditure->class_settings = $this->class_settings;
					
					$expenditure->class_settings["action_to_perform"] = "save_expenditure";
					$expenditure->expenditure();
				}
				*/
				
				//multiple cost during production
				if( isset( $this->class_settings["production"]["expenses"] ) && is_array( $this->class_settings["production"]["expenses"] ) && ! empty( $this->class_settings["production"]["expenses"] ) ){
					$expenditure = new cExpenditure();
					$expenditure->class_settings = $this->class_settings;
					
					$expenditure->class_settings["action_to_perform"] = "save_mulitple_expenses";
					$expenditure->expenditure();
				}
				
				$saved = 1;
			}
			
			$_POST["id"] = $new_record_id;
			
			$this->class_settings["hide_buttons"] = 1;
			$return = $this->_view_invoice();
			$return["javascript_functions"][] = "nwProduction.emptyCart";
			
			return $return;
		}
		
		private function _get_users_pie_chart(){
			
			$start_date = mktime( 0,0,0, 1, 1, date("Y") );
			$end_date = mktime( 0,0,0, 12, 31 , date("Y") );
			
			$event_items = new cproduction_items();
			$select = ", ( Select SUM( ( `".$event_items->table_name."`.`".$event_items->table_fields["cost"]."` * `".$event_items->table_name."`.`".$event_items->table_fields["quantity"]."` ) - `".$event_items->table_name."`.`".$event_items->table_fields["discount"]."` ) FROM `".$this->class_settings['database_name']."`.`".$event_items->table_name."` WHERE `".$event_items->table_name."`.`".$event_items->table_fields["production_id"]."` = `".$this->table_name."`.`id` AND `".$event_items->table_name."`.`record_status` = '1' ) AS 'OTHER_ITEMS_PRICE' ";
			
			$query = "SELECT ( `".$this->table_fields["cost"]."` - `".$this->table_fields["discount"]."` ) as 'total', `".$this->table_fields["date"]."` as 'date' ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND ( `".$this->table_fields["date"]."` >= ".$start_date." AND `".$this->table_fields["date"]."` <= ".$end_date." ) ";
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
            $id = '';
            
			$piechart = 1;
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_users_bar_chart':	
				$piechart = 0;
			break;
			}
			
			$rec = array();
			
			$expenditure = new cExpenditure();
			$expenditure->class_settings = $this->class_settings;
			$expenditure->class_settings[ 'action_to_perform' ] = 'get_monthly_expenses';
			$di = $expenditure->expenditure();
			
			if( isset( $di ) && ! empty( $di ) && is_array( $di ) ){
				$rec["Expenditure"] = $di;
			}
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				$data = array();
				$total = 0;
				
				foreach( $sql_result as $sval ){
					$d = date( "F", doubleval( $sval["date"] ) );
					
					if( isset( $rec["Revenue Generated"][ $d ] ) )
						$rec["Revenue Generated"][ $d ] += doubleval( $sval["total"] );
					else
						$rec["Revenue Generated"][ $d ] = doubleval( $sval["total"] );
					
					//if( isset( $rec["Revenue Generated"][ $d ] ) )
						//$rec["Revenue Generated"][ $d ] += doubleval( $sval["OTHER_ITEMS_PRICE"] );
					//else
						//$rec["Revenue Generated"][ $d ] = doubleval( $sval["OTHER_ITEMS_PRICE"] );
						
				}
			}
			
			if( ! empty( $rec ) ){
				$m1 = array();
				$months = get_months_of_year();
				$x_axis = array();
				foreach( $months as $ki => $month ){
					$m1[ $month ] = $ki;
					$x_axis[] = $month;
				}
				
				//$reg = get_types_of_expenditure();
				
				$data1 = array();
				foreach( $rec as $name => $sval ){
					//if( $sval["total"] && isset( $reg[ $sval["payment_type"] ] ) ){
						
						$push = array();
						foreach( $m1 as $m2 => $m3 ){
							if( isset( $sval[ $m2 ] ) )
								$push[] = doubleval( $sval[ $m2 ] );
							else
								$push[] = 0;
						}
						
						$data1[] = array(
							"name" => $name,
							"data" => $push,
						);
					//}
				}
				
				if( $piechart ){
					$return["highchart_data"] = pie_legend_chart();
					$return["highchart_data"]["subtitle"]["text"] = date("F, Y");
					$return["highchart_data"]["series"][0]["data"] = $data;
				}else{
					$return["highchart_data"] = basic_column_chart();
					$return["highchart_data"]["subtitle"]["text"] = date("Y");
					$return["highchart_data"]["xAxis"]["categories"] = $x_axis;
					$return["highchart_data"]["series"] = $data1;
				}
				
				
				if( $piechart ){
					$return["highchart_exported_chart_name"] = "pie-chart";
					$return["highchart_container_selector"] = "#chart-container";
				}else{
					$return["highchart_exported_chart_name"] = "bar-chart";
					$return["highchart_container_selector"] = "#chart-container-1";
				}
					
				
				//$return["highchart_container_selector"] = "#".$chartname;
				
				$return["javascript_functions"] = array( 'activate_highcharts' );
				$return["status"] = "new-status";
				
				$return["do_not_reload_table"] = 1;
				
				if( $piechart ){					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = 1;
					$return['action'] = '?action=production_record&todo=get_users_bar_chart';
				}
			
				return $return;
			}
		}
		
		private function _get_financial_reports(){
			$returning_html_data = "";
			
			$field_name = "start_date";
			$where = "";
			$title = "";
			$select = "";
			$grouping = 1;
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "other_requirements":
				case "hall_description":
				case "start_date":
				case 'hall':
				case 'hall_price':
				case 'hall_price_unit':
				case 'number_of_days':
				case "discount":
				case "discount_type":
					if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
					else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
				break;
				}
				
			}
			
			if( isset( $_GET["budget"] ) && intval( $_GET["budget"] ) ){
				$year = intval( $_GET["budget"] );
				$end_year  = $year + 1;
				$month = 1;
				$end_days = 1;
				
				$title = "INCOME REPORT FOR ".$year;
				
				if( isset( $_GET["month"] ) && intval( $_GET["month"] ) ){
					$m = get_months_of_year();
					$month = intval( $_GET["month"] );
					if( isset( $m[ $month ] ) ){
						
						$grouping = 0;
						
						$end_days = cal_days_in_month(CAL_GREGORIAN, intval( $_GET["month"] ) , $year );
						$end_year = $year;
						
						$title = "INCOME REPORT FOR ".$m[ $month ].", ".$year;
					}
				}
				
				
				$expense_val = "";
				$expense_query = "";
				$skip_sub_query = 0;
				if( isset( $_GET["operator"] ) && $_GET["operator"] ){
					$p = get_types_of_income();
					$pp = $_GET["operator"];
					if( isset( $p[ $pp ] ) ){
						
						$title .= "<br /><strong>".ucwords( $p[ $pp ] )."</strong>";
						$expense_val = $pp;
						if( $pp == "none" ){
							$expense_query = " AND `".$this->table_name."`.`".$this->table_fields["hall"]."` = '".$expense_val."' ";
						}else{
							$expense_query = " AND `".$this->table_name."`.`".$this->table_fields["hall"]."` != '".$expense_val."' ";
							$skip_sub_query = 1;
						}
							
					}
				}
				
				$start_date = mktime(0,0,0,$month , 1, $year );
				$end_date = mktime(0,0,0,$month , $end_days, $end_year );
				$where = " ( `".$this->table_name."`.`record_status`='1' AND `".$this->table_name."`.`".$this->table_fields["start_date"]."` >= " . $start_date . " AND `".$this->table_name."`.`".$this->table_fields["start_date"]."` < " . $end_date ." ) ";
				
				if( $expense_val ){
					$where .= $expense_query;
				}
			}
			
			if( $where ){
				$event_items = new cEvent_notes();
				
				if( ! $skip_sub_query ){
					$select .= ", ( Select SUM(`".$event_items->table_name."`.`".$event_items->table_fields["unit_price"]."` * `".$event_items->table_name."`.`".$event_items->table_fields["quantity"]."` * `".$event_items->table_name."`.`".$event_items->table_fields["duration"]."`) FROM `".$this->class_settings['database_name']."`.`".$event_items->table_name."` WHERE `".$event_items->table_name."`.`".$event_items->table_fields["production_id"]."` = `".$this->table_name."`.`id` AND `".$event_items->table_name."`.`record_status` = '1' ) AS 'OTHER_ITEMS_PRICE' ";
				}
				
				$select .= ", ( `".$this->table_name."`.`".$this->table_fields["hall_price"]."` * `".$this->table_name."`.`".$this->table_fields["number_of_days"]."` ) AS 'TOTAL_HALL_PRICE' ";
				
				$all_data = array();
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields[ $field_name ]."` DESC ";
				//GROUP BY `".$this->table_name."`.`id` 
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$income = execute_sql_query($query_settings);
				
				$all_data = $income;
				//$all_data = array_merge( $expenses , $salary, $income, $bank_transactions );
				
				if( $grouping == 1 ){
					//group data based on year
					$all_new = array();
					foreach( $all_data as $sval ){
						$key = date( "F", doubleval( $sval["start_date"] ) );
						if( isset( $all_new[ $key ] ) ){
							foreach( $sval as $k => $v ){
								switch( $k ){
								case "start_date":
								break;
								case "other_requirements":
								case "hall_description":
								case "hall":
									$all_new[ $key ][ $k ] = "*Several";
								break;
								default:	
									if( ! isset( $all_new[ $key ][ $k ] ) )$all_new[ $key ][ $k ] = 0;
									$all_new[ $key ][ $k ] += doubleval( $v );
								break;
								}
							}
						}else{
							$all_new[ $key ] = $sval;
						}
					}
					$all_data = $all_new;
					$this->class_settings[ 'data' ][ 'date_filter' ] = "F";
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-report' );
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'report_type' ] = "financial_report";
				$this->class_settings[ 'data' ][ 'report_data' ] = $all_data;
				$returning_html_data = $this->_get_html_view();
				//$returning_html_data = $query;
				
			}else{
				//return error message
			}
			
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#data-table-section",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				//'javascript_functions' => array( 'activate_tree_view', 'set_function_click_event' ) 
			);
		}
		
		private function _display_all_financial_reports_full_view(){
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-financial-reports-full-view' );
			
			$this->class_settings[ 'data' ][ 'report_type' ] = get_calendar_years();
			$this->class_settings[ 'data' ][ 'selected_option' ] = date("Y");
			
			$m = get_months_of_year();
			//$m["all-months"] = "All Months";
			
			$this->class_settings[ 'data' ][ 'report_type1' ] = $m;
			$this->class_settings[ 'data' ][ 'selected_option1' ] = "all-months";
			
			$this->class_settings[ 'data' ][ 'report_type2' ] = get_types_of_income();
			$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-income";
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _track_invoice(){
			$return["html_replacement_selector"] = "#dash-board-main-content-area";
			
			$error = '<div class="note note-danger"><h4 class="block">Invalid Invoice Number</h4><p>Please Provide a valid invoice number </p></div>';
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				
				$return['html_replacement'] = $error;
				$return['status'] = "new-status";
				return $return;
			}
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$event = $this->_get_production();
			
			if( ! ( isset( $event["id"] ) && $event["id"] ) ){
				$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `serial_num` = ".$this->class_settings[ 'current_record_id' ];
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$all_data = execute_sql_query($query_settings);
				if( isset( $all_data[0]["id"] ) && $all_data[0]["id"] ){
					$this->class_settings["current_record_id"] = $all_data[0]["id"];
					$event = $this->_get_production();
				}
			}
			
			if( ! ( isset( $event["id"] ) && $event["id"] ) ){
				$return['html_replacement'] = $error;
				$return['status'] = "new-status";
				return $return;
			}
			
			$return['status'] = "new-status";
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/book-event-success-message.php' );
			$this->class_settings[ 'data' ]["hide_info"] = 1;
			$this->class_settings[ 'data' ]["event"] = $event;
			$return["html_replacement"] = $this->_get_html_view();
			
			
			return $return;
		}
		
		private function _view_invoice(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "view_invoice_app":
			break;
			default:
				$this->class_settings["hide_buttons"] = 1;
			break;
			}
			
			//$this->_refresh_production_info();
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$this->class_settings["production_id"] = $this->class_settings["current_record_id"];
			$e["event"] = $this->_get_production();
			
			$production_items = new cProduction_items();
			$production_items->class_settings = $this->class_settings;
			$production_items->class_settings["action_to_perform"] = "get_specific_production_items";
			$e['materials'] = $production_items->production_items();
			
			$materials_used = 0;
			$title = "Production Manifest";
			$filename = 'production-manifest.php';
			
			//expenses
			if( isset( $e["event"]["status"] ) ){
				switch( $e["event"]["status"] ){
				case "sales-order":
					$filename = 'picking-slip.php';
					$title = "Sales Order Picking Slip";
				break;
				case "materials-utilized":
				case "damaged-materials":
					$materials_used = 1;
					$title = "Materials Utilization Manifest";
					
					switch( $e["event"]["status"] ){
					case "damaged-materials":
						$materials_used = 3;
						$title = "Damaged Materials Manifest";
					break;
					}
				break;
				default:
					if( isset( $e["event"]["status"] ) && $e["event"]["status"] == "materials-transfer" ){
						$materials_used = 2;
						$title = "Materials Transfer Manifest";
					}else{
						$expenditure = new cExpenditure();
						$expenditure->class_settings = $this->class_settings;			
						$expenditure->class_settings["action_to_perform"] = "get_specific_produced_items";
						$e['expenses'] = $expenditure->expenditure();
					}
					
					$this->class_settings["reference_table"] = $this->table_name;
					
					$inventory = new cInventory();
					$inventory->class_settings = $this->class_settings;
					$inventory->class_settings["action_to_perform"] = "get_specific_produced_items";
					$e['produced_items'] = $inventory->inventory();
				break;
				}	
			}
			
			if( isset( $e["event"]["reference_table"] ) ){
				switch( $e["event"]["reference_table"] ){
				case "cart":
					$title = "Stock Requisition Slip";
					$filename = 'general-picking-slip.php';
				break;
				}
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
			$this->class_settings[ 'data' ] = $e;
			$this->class_settings[ 'data' ]["backend"] = 1;
			$this->class_settings[ 'data' ]["materials_used"] = $materials_used;
			
			if( isset( $this->class_settings["show_print_button"] ) )
				$this->class_settings[ 'data' ]["backend"] = 0;
			
			if( isset( $this->class_settings["hide_buttons"] ) )
				$this->class_settings[ 'data' ]["hide_buttons"] = 1;
		
			$html = $this->_get_html_view();
			
			switch( $this->class_settings["action_to_perform"] ){
			case "view_invoice_app":
				return array(
					'html_replacement' => $html,
					'html_replacement_selector' => "#invoice-receipt-container",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ),
				);
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = $title;
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				//'javascript_functions' => array( 'prepare_new_record_form_new' ),
			);
			
		}
		
		private function _refresh_production_info(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid production Record</h4>Please Select a production Record to Refresh';
				return $err->error();
			}
			$production_id = $_POST["id"];
			$this->class_settings["production_id"] = $production_id;
			
			//get production items
			$production_items = new cProduction_items();
			$production_items->class_settings = $this->class_settings;
			$production_items->class_settings["action_to_perform"] = "get_specific_production_items";
			$items = $production_items->production_items();
			
			//get expenses
			$expenditure = new cExpenditure();
			$expenditure->class_settings = $this->class_settings;
			$expenditure->class_settings["action_to_perform"] = "get_total_expenditure";
			$total = $expenditure->expenditure();
			
			$units = 0;
			$cost = 0;
			
			if( is_array( $items ) && ! empty( $items ) ){
				foreach( $items as $item ){
					$units += $item["quantity"];
					$cost += ( $item["quantity"] * $item["cost"] );
				}
			}
			if( isset( $total[0]["TOTAL"] ) ){
				$this->class_settings["update_fields"][ "extra_cost" ] = doubleval( $total[0]["TOTAL"] );
			}
			
			$this->class_settings["update_fields"][ "quantity" ] = $units;
			$this->class_settings["update_fields"][ "cost" ] = $cost;
			
			return $this->_update_table_field();
		}
		
		private function _save_site_changes(){
			//save event & generate invoice
			$return = $this->_save_changes();
			
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
				$return['status'] = "new-status";
				
				unset( $return['html'] );
				
				switch( $this->class_settings["action_to_perform"] ){
				case 'save_update_production_status':
					//update inventory
					unset( $this->class_settings['do_not_check_cache'] );
					$this->class_settings["current_record_id"] = $return['saved_record_id'];
					$e = $this->_get_production();
					
					$this->class_settings["update_fields"] = array(
						"source" => $e["factory"],
						"store" => $e["store"],
						"staff_responsible" => $e["staff_responsible"],
						"status" => $e["status"],
						"comment" => $e["comment"],
						"production_id" => $e["id"],
					);
					
					$inventory = new cInventory();
					$inventory->class_settings = $this->class_settings;
					$inventory->class_settings["action_to_perform"] = 'update_inventory_records';
					$inventory->inventory();
				break;
				}
			}
			
			return $return;
		}
		
		private function _update_table_field(){
			$applicant = array();
			$return = array();
			
			if( ! isset( $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
			$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
			$_POST[ "table" ] = $this->table_name;
			$_POST[ "processing" ] = md5(1);
			if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
				define('SKIP_USE_OF_FORM_TOKEN', 1);
			
			$push = 0;
			if( isset( $this->class_settings["update_fields"] ) && is_array( $this->class_settings["update_fields"] ) ){
				foreach( $this->class_settings["update_fields"] as $key => $val ){
					if( isset( $this->table_fields[ $key ] ) ){
						$_POST[ $this->table_fields[ $key ] ] = $val;
						$push = 1;
					}
				}
			}
			
			if( $push )
				$return = $this->_save_changes();
			
			return $return;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->datatable_settings["custom_edit_button"] = $this->_get_html_view();
			
			$_SESSION[ $this->table_name ]['order_by'] = " ORDER BY `".$this->table_name."`.`".$this->table_fields["date"]."` DESC ";
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Production";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state', 'prepare_new_record_form_new' ) 
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
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Production';
			break;
			default:
				if( ! isset( $this->class_settings['form_values_important'][ $this->table_fields["date"] ] ) )
					$this->class_settings['form_values_important'][ $this->table_fields["date"] ] = date("U");
			break;
			}
			
			if( ! isset( $this->class_settings['form_submit_button'] ) )
				$this->class_settings['form_submit_button'] = 'Save Changes &rarr;';
			
			if( ! isset( $this->class_settings['form_class'] ) )
				$this->class_settings['form_class'] = 'activate-ajax';
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
				'record_id' => $returning_html_data[ 'record_id' ],
			);
		}

		private function _delete_records(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'delete_records';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'deleted-records';
			
			if( isset( $returning_html_data['deleted_record_id'] ) && $returning_html_data['deleted_record_id'] ){
				$cache_key = $this->table_name;
				
				switch ( $this->class_settings['action_to_perform'] ){
				case 'delete':
					$table = 'transactions';
					$_POST["mod"] = 'delete-' . md5( $table );
					
					$actual_name_of_class = 'c'.ucwords( $table );
					$module = new $actual_name_of_class();
					$module->class_settings = $this->class_settings;
					$module->class_settings["action_to_perform"] = 'delete_only';
					$module->$table();
				break;
				}
				
				$d = '';
				$d1 = explode( ":::", $returning_html_data['deleted_record_id'] );
				foreach( $d1 as $dd ){
					if( ! $dd )continue;
					if( $d )$d .= ", '" . $dd . "' ";
					else $d = "'" . $dd . "' ";
				}
				
				$production_items = new cProduction_items();
				
				$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$production_items->table_name."` SET `record_status` = '0' WHERE `".$production_items->table_fields['production_id']."` IN ( ".$d." ) "; 
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $production_items->table_name ),
				);
				execute_sql_query($query_settings);
				
				$inventory = new cInventory();
				
				$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$inventory->table_name."` SET `record_status` = '0' WHERE `".$inventory->table_fields['production_id']."` IN ( ".$d." ) "; 
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $inventory->table_name ),
				);
				execute_sql_query($query_settings);
				
				$expenditure = new cExpenditure();
				$expenditure->class_settings = $this->class_settings;
				$expenditure->class_settings["production_query"] = "( ".$d." )";
				
				$expenditure->class_settings["action_to_perform"] = "delete_expenditure_from_production";
				$expenditure->expenditure();
				
				$returning_html_data[ 'data_table_name' ] = $this->table_name;
			}
			return $returning_html_data;
		}
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
			
			$fields = array();
			$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$this->table_name."`";
			$query_settings = array(
				'database'=>$this->class_settings['database_name'],
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'DESCRIBE',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval)
					$fields[] = $sval;
			}else{
				//REPORT INVALID TABLE ERROR
				$err = new cError(000001);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cproduction.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$this->datatable_settings['current_module_id'] = $this->class_settings['current_module'];
			
			$form->datatables_settings = $this->datatable_settings;
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-datatable',
			);
		}
		
		private function _save_changes(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			if( is_array( $returning_html_data ) ){
				$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returning_html_data['status'] = 'saved-form-data';
				
				if( isset( $returning_html_data['saved_record_id'] ) && $returning_html_data['saved_record_id'] ){
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->class_settings['current_record_id'] = $returning_html_data['saved_record_id'];
					$returning_html_data["event_details"] = $this->_get_production();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_production(){
			
			$cache_key = $this->table_name;
			
			if( ! isset( $this->class_settings[ 'current_record_id' ] ) )return 0;
			
			$settings = array(
				'cache_key' => $cache_key.'-'.$this->class_settings[ 'current_record_id' ],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			
			//CHECK WHETHER TO CHECK FOR CACHE VALUES
			if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
				
				//CHECK FOR CACHED VALUES
				$cached_values = get_cache_for_special_values( $settings );
				if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
					return $cached_values;
				}
				
			}
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `modified_by`, `created_by`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `id` = '".$this->class_settings[ 'current_record_id' ]."'";
			
			if( $this->class_settings[ 'current_record_id' ] == 'pass_condition' ){
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ";
			}
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_data = execute_sql_query($query_settings);
			
			$single_data = array();
			
			if( is_array( $all_data ) && ! empty( $all_data ) ){
				
				foreach( $all_data as $record ){
					$single_data = $record;
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$record['id'],
						'cache_values' => $record,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					//$this->_reset_members_cache( $record );
					//$this->class_settings["member_id"] = $record["bill_id"];
					//$this->_get_production();
				}
				
				return $single_data;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			return 1;
		}
	}
?>