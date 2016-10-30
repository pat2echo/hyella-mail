<?php
	/**
	 * chart_of_accounts Class
	 *
	 * @used in  				chart_of_accounts Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	chart_of_accounts
	 */

	/*
	|--------------------------------------------------------------------------
	| chart_of_accounts Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cChart_of_accounts{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'chart_of_accounts';
		
		private $associated_cache_keys = array(
			'default' => 'chart_of_accounts',
			'extracted-line-chart_of_accounts' => 'invoices_raw_data_import-line-chart_of_accounts-data-',
			'imported-cash-call' => 'import_cash_call-',
			'line-item-details' => 'budget_line_chart_of_accounts-',
		);
		
		public $table_fields = array(
			'type' => 'chart_of_accounts001',
			
			'title' => 'chart_of_accounts002',
			'code' => 'chart_of_accounts003',
			
			'parent1' => 'chart_of_accounts004',
			'parent2' => 'chart_of_accounts005',
			'parent3' => 'chart_of_accounts006',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 0,		//Determines whether or not to show delete button
				'show_generate_report' => 0,		//Determines whether or not to show delete button
				
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
	
		function chart_of_accounts(){
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
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'new_item':
				$returned_value = $this->_new_item();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'check_for_subaccount':
			case 'get_second_level_accounts':
			case 'get_third_level_accounts':
				$returned_value = $this->_check_for_subaccount();
			break;
			}
			
			return $returned_value;
		}
		
		private function _check_for_subaccount(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			$return = array();
			$js = array();
			$replacement_handle = "";
			
			$id = $_POST["id"];
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_second_level_accounts':
				$acc = get_account_children( array( "id" => $id, "parent" => "parent1" ) );
				$replacement_handle = "select#".$this->table_fields["parent2"];
			break;
			case 'get_third_level_accounts':
				$acc = get_account_children( array( "id" => $id, "parent" => "parent2" ) );
				$replacement_handle = "select#".$this->table_fields["parent3"];
			break;
			default:
				$acc_details = get_chart_of_accounts_details( array( "id" => $id ) );
				$parent = 0;
				if( isset( $acc_details["parent1"] ) && $acc_details["parent1"] ){
					$parent = 1;
					if( $acc_details["parent2"] ){
						$parent = 2;
					}
				}
				++$parent;
				
				switch( $id ){
				case "accounts_receivable":
					$acc = get_customers();
				break;
				case "account_payable":
					$acc = get_vendors();
				break;
				case "inventory":
				case "damaged_items":
				case "inventory_marketing_expense":
				case "revenue_category":
					$acc = get_items_categories();
				break;
				case "cash_book":
					$return1 = get_account_children( array( "id" => "cash_book", "parent" => "parent1" ) );
					if( ! empty( $return1 ) ){
						foreach( $return1 as $key => $val ){
							if( strtolower( $val ) == "bank" )continue;
							$acc[ $key ] = $val;
						}
					}
				break;/*
				case "operating_expense":
					$return2 = get_account_children( array( "id" => "operating_expense", "parent" => "parent1" ) );
					$return1 = get_account_children( array( "id" => "cost_of_goods_sold", "parent" => "parent1" ) );
					$acc = array_merge( $return1 , $return2 );
				break;*/
				default:
					$acc = get_account_children( array( "id" => $id, "parent" => "parent" . $parent ) );
				break;
				}
				
				$replacement_handle = "select.account-select-" . ($parent + 1);
				$js = array( "nwTransactions.show_account_select_" . ($parent + 1) );
			break;
			}
			
			if( is_array( $acc ) && ! empty( $acc ) ){
				$acc["0"] = "-None-";
				asort( $acc );
				
				$this->class_settings[ 'data' ]['accounts'] = $acc;
			
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/select-options.php' );
				$data = $this->_get_html_view();
				
				if( $data ){
					$return["html_replacement"] = $data;
					
					$return["html_replacement_selector"] = $replacement_handle;
					$return["javascript_functions"] = $js;
					
					unset( $return["html"] );
					$return["status"] = "new-status";
					$return["do_not_reload_table"] = 1;
					
					return $return;
				}
			}
			
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
			$this->_get_chart_of_accounts();
		}
		
		private function _new_item(){
			$store = "";
			if( isset( $_POST["store"] ) && $_POST["store"] ){
				$store = $_POST["store"];
			}
			
			if( isset( $_POST['id'] ) ){
				//$_POST['id'] = "";
				$new = 1;
				
				foreach( $this->table_fields as $key => $val ){
					if( isset( $_POST[ $key ] ) ){
						$_POST[ $val ] = $_POST[ $key ];
						unset( $_POST[ $key ] );
					}
				}
				
				if( $_POST['id'] ){
					//not new mode
					$new = 0;
				}
				
				//check for chart_of_accounts with same title & update instead
				
				$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
				$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
				$_POST[ "table" ] = $this->table_name;
				$_POST[ "processing" ] = md5(1);
				if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
					define('SKIP_USE_OF_FORM_TOKEN', 1);
				
				$return = $this->_save_changes();
				if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
					//return new item
					$item = get_chart_of_accounts_details( array( "id" => $return['saved_record_id'] ) );
					$item["quantity"] = 0;
					$item["selling_price"] = 0;
					$item["cost_price"] = 0;
					$item["source"] = "";
					$item["item"] = $item["id"];
					
					if( $new ){
						//make initial inventory entry
						$_POST["id"] = "";
						
						$inventory = new cInventory();
						$inventory->class_settings = $this->class_settings;
						$inventory->class_settings["update_fields"] = array(
							//"quantity" => 1,
							"selling_price" => 0,
							"cost_price" => 0,
							"source" => "",
							"item" => $item["id"],
							"date" => date("Y-m-d"),
							"store" => $store,
							"staff_responsible" => $this->class_settings["user_id"],
							"status" => 'complete',
							"comment" => "New item created",
						);
						$inventory->class_settings["action_to_perform"] = 'update_table_field';
						$inventory->inventory();
						
						$this->class_settings[ 'data' ]["item"] = $item;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/inventory-list.php' );
						$returning_html_data = $this->_get_html_view();
						
						unset( $return["html"] );
						$return["status"] = "new-status";
						
						$return["html_prepend_selector"] = "#stocked-chart_of_accounts";
						$return["html_prepend"] = $returning_html_data;
						$return["javascript_functions"] = array( 'nwInventory.init', 'nwInventory.showRestockTabforNewItem', 'nwInventory.reClick' ) ;
						return $return;
					}
					
					$inventory = new cInventory();
					$inventory->class_settings = $this->class_settings;
					$inventory->class_settings[ 'specific_item' ] = $item["id"];
					$inventory->class_settings["action_to_perform"] = 'get_all_inventory';
					$i = $inventory->inventory();
					
					if( isset( $i[0] ) ){
						$this->class_settings[ 'data' ]["item"] = $i[0];
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/inventory-list.php' );
						$returning_html_data = $this->_get_html_view();
						
						unset( $return["html"] );
						$return["status"] = "new-status";
						
						$return["html_replace_selector"] = "#" . $item["id"] . "-container";
						$return["html_replace"] = $returning_html_data;
						$return["javascript_functions"] = array( 'nwInventory.init', 'nwInventory.emptyNewItemForEdit', 'nwInventory.reClick' ) ;
						return $return;
						
					}
					
				}
			}
			
			
			return $return;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings['form_heading_title'] = 'Create & Manage Chart of Accounts';
			$this->class_settings['form_submit_button'] = 'Save Changes &rarr;';
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Chart of Accounts Manager";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			//$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new', 'recreateDataTables', 'set_function_click_event', 'update_column_view_state' ),
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
				$this->class_settings['form_heading_title'] = 'Modify Chart of Account';
				//$this->class_settings['hidden_records'][ $this->table_fields["member_id"] ] = 1;
			break;
			}
			
			
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
			
			//$this->class_settings[ 'do_not_check_cache' ] = 1;
			//$this->_get_chart_of_accounts();
			
			return $returning_html_data;
		}
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
			unset($_SESSION[ $this->table_name ]);
			
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
				
				$err->class_that_triggered_error = 'cchart_of_accounts.php';
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
					$this->class_settings["current_record_id"] = $returning_html_data['saved_record_id'];
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->_get_chart_of_accounts();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_chart_of_accounts(){
			
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key,
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			
			$returning_array = array(
				'html' => '',
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'get-product-features',
			);
			
			//CHECK WHETHER TO CHECK FOR CACHE VALUES
			if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
				//CHECK IF CACHE IS SET
				$cached_values = get_cache_for_special_values( $settings );
				if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
					return $cached_values;
				}
			}
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ORDER BY `".$this->table_fields["title"]."` ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_departments = execute_sql_query($query_settings);
			
			$de1 = array();
			$grouped = array();
			$ts = array();
			
			if( is_array( $all_departments ) && ! empty( $all_departments ) ){
				
				foreach( $all_departments as $category ){
					$code = '';
					if( $category['code'] )$code = ' (' . $category['code'] . ')';
					
					$departments["parent1"][ $category[ 'parent1' ] ][ $category[ 'id' ] ] = $category[ 'title' ] . $code;
					if( $category[ 'parent1' ] && $category[ 'parent2' ] ){
						$departments["parent2"][ $category[ 'parent2' ] ][ $category[ 'id' ] ] = $category[ 'title' ] . $code;
						
						if( $category[ 'parent2' ] && $category[ 'parent3' ] ){
							$departments["parent3"][ $category[ 'parent3' ] ][ $category[ 'id' ] ] = $category[ 'title' ] . $code;
						}
					}
					
					$de1[ $category[ 'id' ] ] = $category[ 'title' ] . $code;
					
					$settings = array(
						'cache_key' => $cache_key.'-'.$category[ 'id' ],
						'cache_values' => $category,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
				}
				
				foreach( $departments as $k => $v ){
					
					switch( $k ){
					case "inventory":
					case "revenue_category":
					case "account_payable":
					case "accounts_receivable":
						continue;
					break;
					}
					
					foreach( $v as $kk => $vv ){
						$settings = array(
							'cache_key' => $cache_key . "-" . $k . "-" . $kk,
							'cache_values' => $vv,
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						set_cache_for_special_values( $settings );
					}
				}
				
				//clear payment method cache
				$settings = array(
					'cache_key' => 'payment-method',
					'permanent' => true,
				);
				clear_cache_for_special_values( $settings );
				
				$settings = array(
					'cache_key' => 'payment-method-grouped',
					'permanent' => true,
				);
				clear_cache_for_special_values( $settings );
				
				$settings = array(
					'cache_key' => 'payment-method-list',
					'permanent' => true,
				);
				clear_cache_for_special_values( $settings );
				
				return $departments;
			}
		}
		
	}
?>