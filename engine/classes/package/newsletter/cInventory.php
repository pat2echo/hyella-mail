<?php
	/**
	 * inventory Class
	 *
	 * @used in  				inventory Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	inventory
	 */

	/*
	|--------------------------------------------------------------------------
	| inventory Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cInventory{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'inventory';
		
		private $associated_cache_keys = array(
			'inventory',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'inventory001',
			'source' => 'inventory002',
			'store' => 'inventory003',
			'item' => 'inventory004',
			'quantity' => 'inventory005',
			'cost_price' => 'inventory006',
			'selling_price' => 'inventory007',
			'expiry_date' => 'inventory008',
			
			'production_id' => 'inventory010',
			'staff_responsible' => 'inventory011',
			
			'quantity_expected' => 'inventory012',
			'quantity_ordered' => 'inventory014',
			
			'reference_table' => 'inventory013',
			
			'comment' => 'inventory009',
			'currency' => 'inventory015',
			
			'discount' => 'inventory016',
			'tax' => 'inventory017',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
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
	
		function inventory(){
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
			case 'get_all_inventory':
				$returned_value = $this->_get_all_inventory();
			break;
			case 'display_custom_records_full_view':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'display_app_inventory2':
			case 'display_app_inventory':
				$returned_value = $this->_display_app_inventory();
			break;
			case 'restock':
				$returned_value = $this->_restock();
			break;
			case 'save_produced_goods':
				$returned_value = $this->_save_produced_goods();
			break;
			case 'get_specific_produced_items':
			case 'view_all_produced_items_editable':
			case 'view_all_produced_items':
				$returned_value = $this->_view_all_produced_items();
			break;
			case 'save_new_inventory':
				$returned_value = $this->_save_new_inventory();
			break;
			case 'delete_inventory':
				$returned_value = $this->_delete_inventory();
			break;
			case 'update_inventory_records':
				$returned_value = $this->_update_inventory_records();
			break;
			case 'delete_goods_produced':
				$returned_value = $this->_delete_goods_produced();
			break;
			case 'update_table_field':
				$returned_value = $this->_update_table_field();
			break;
			case 'display_app_reports_full_view':
			case 'display_all_reports_full_view':
				$returned_value = $this->_display_all_reports_full_view();
			break;
			case "generate_app_sales_report":
			case 'generate_sales_report':
				$returned_value = $this->_generate_sales_report();
			break;
			case 'get_recent_supplies':
				$returned_value = $this->_get_recent_supplies();
			break;
			case 'delete_stocked_item':
				$returned_value = $this->_delete_stocked_item();
			break;
			case 'get_all_inventory_by_vendor':
				$returned_value = $this->_get_all_inventory_by_vendor();
			break;
			case 'search_vendor_inventory_record':
				$returned_value = $this->_search_vendor_inventory_record();
			break;
			case 'produced_item_search':
			case 'purchase_item_search':
			case 'pos_item_search':
			case 'stock_item_search':
				$returned_value = $this->_stock_item_search();
			break;
			case 'filter_inventory_search_all':
				$returned_value = $this->_filter_inventory_search_all();
			break;
			}
			
			return $returned_value;
		}
		
		private function _filter_inventory_search_all(){
			$where = "";
			
			if( isset( $_POST["item"] ) && $_POST["item"] ){
				$this->class_settings["item"] = $_POST["item"];
			}
			
			if( isset( $_POST["vendor"] ) && $_POST["vendor"] ){
				$this->class_settings["vendor"] = $_POST["vendor"];
			}
			
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$this->class_settings["start_date"] = convert_date_to_timestamp( $_POST["start_date"] );
			}
			
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$this->class_settings["end_date"] = convert_date_to_timestamp( $_POST["end_date"] );
			}
			
			if( isset( $this->class_settings["item"] ) && $this->class_settings["item"] )
				$where = " AND `".$this->table_fields["item"]."` = '".$this->class_settings["item"]."' ";
			
			if( isset( $this->class_settings["vendor"] ) && $this->class_settings["vendor"] )
				$where .= " AND `".$this->table_fields["source"]."` = '".$this->class_settings["vendor"]."' ";
			
			if( isset( $this->class_settings["start_date"] ) && doubleval( $this->class_settings["start_date"] ) ){
				$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
			}
			
			if( isset( $this->class_settings["end_date"] ) && doubleval( $this->class_settings["end_date"] ) ){
				$where .= " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
			}
			
			unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
			if( $where )$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = $where;
			
			return array(
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
			);
		}
		
		private function _stock_item_search(){
			$filename = "no-result-found.php";
			$js = array();
			
			$category = "";
			$search = "";
			$type = "";
			$cat = get_items_categories();
			
			if( isset( $_POST["type"] ) && $_POST["type"] ){
				$type = $_POST["type"];
			}
			
			if( isset( $_POST["category_filter"] ) && $_POST["category_filter"] ){
				$category = $_POST["category_filter"];
			}
			
			if( isset( $_POST["search"] ) && $_POST["search"] ){
				$search = $_POST["search"];
			}
			
			$scope = $this->class_settings['action_to_perform'];
			$cart_item_list = "cart-item-list.php";
			
			if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
				$this->class_settings[ 'store' ] = $_SESSION[ "store" ];
			}
			if( get_single_store_settings() ){
				unset( $this->class_settings[ 'store' ] );
			}
			
			if( $search || $category ){
				
				$barcode = 0;
				if( $search ){
					$item = get_items_details_by_barcode( array( "id" => $search ) );
					if( isset( $item["id"] ) && $item["id"] ){
						//barcode search
						$barcode = 1;
						$this->class_settings[ 'specific_item' ] = $item["id"];
					}else{
						$this->class_settings[ 'search_item' ] = $search;
					}
				}
				
				if( ! $barcode && $category ){
					$this->class_settings[ 'search_item_category' ] = $category;
				}
				
				$highlight = "nwInventory.clearSearchForm";
				switch( $scope ){
				case 'pos_item_search':
					$this->class_settings[ 'sold_items_only' ] = 1;
					$highlight = "nwCart.clearSearchForm";
				break;
				case 'purchase_item_search':
					$highlight = "nwCart.clearSearchForm";
					$type = $scope;
					$this->class_settings[ 'purchased_items_only' ] = 1;
				break;
				case 'produced_item_search':
					$this->class_settings[ 'produced_items_only' ] = 1;
					$highlight = "nwCart.clearSearchForm";
					$type = $scope;
				break;
				}
				
				$data = $this->_get_all_inventory();
				
				if( is_array( $data ) && ! empty( $data ) ){
					if( $barcode )$this->class_settings[ 'data' ][ "barcode" ] = $barcode;
					
					$this->class_settings[ 'data' ][ "stocked_items" ] = $data;
					
					switch( $scope ){
					case 'produced_item_search':
					case 'purchase_item_search':
					case 'pos_item_search':
						
						$filename = $cart_item_list;
						$js = array( "nwCart.activateItemSelect" );
						
						if( $barcode ){
							$js[] = "nwCart.reClick";
						}
						$js[] = "nwCart.clearSearchForm";
					break;
					default:						
						$filename = "inventory-list-result.php";
						$js = array( "nwInventory.activateItemSelect" );
						
						if( $barcode ){
							$js[] = "nwInventory.reClick";
						}
						//$js[] = "nwInventory.clearSearchForm";
					break;
					}
				}else{
					$this->class_settings[ 'data' ][ "barcode" ] = $barcode;
				}
				
				$this->class_settings[ 'data' ][ "category" ] = ( isset( $cat[ $category ] )?$cat[ $category ]:"" );
				$this->class_settings[ 'data' ][ "search" ] = $search;
			}else{
				switch( $scope ){
				case 'pos_item_search':
					$this->class_settings[ 'sold_items_only' ] = 1;
					$this->class_settings[ 'data' ][ "stocked_items" ] = $this->_get_all_inventory();
					$filename = $cart_item_list;
					$js = array( "nwCart.activateItemSelect", "nwCart.clearSearchForm" );
				break;
				case 'purchase_item_search':
					$this->class_settings[ 'purchased_items_only' ] = 1;
					$this->class_settings[ 'data' ][ "stocked_items" ] = $this->_get_all_inventory();
					$filename = $cart_item_list;
					$js = array( "nwCart.activateItemSelect", "nwCart.clearSearchForm" );
					$type = $scope;
				break;
				case 'produced_item_search':
					$this->class_settings[ 'produced_items_only' ] = 1;
					$this->class_settings[ 'data' ][ "stocked_items" ] = $this->_get_all_inventory();
					$filename = $cart_item_list;
					$js = array( "nwCart.activateItemSelect", "nwCart.clearSearchForm" );
					$type = $scope;
				break;
				default:						
					$this->class_settings[ 'data' ][ "stocked_items" ] = $this->_get_all_inventory();
					$filename = "inventory-list-result.php";
					$js = array( "nwInventory.activateItemSelect" );
				break;
				}
			}
			
			if( isset( $highlight ) && $highlight )$js[] = $highlight;
			
			$this->class_settings[ 'data' ][ "type" ] = $type;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
			$returning_html_data = $this->_get_html_view();
				
			return array(
				"do_not_reload_table" => 1,
				"html_replacement_selector" => "#stocked-items",
				"html_replacement" => $returning_html_data,
				"status" => "new-status",
				"javascript_functions" => $js,
			);
		}
		
		private function _search_vendor_inventory_record(){
			if( isset( $this->class_settings["vendor"] ) && $this->class_settings["vendor"] ){
				$this->class_settings[ 'specific_vendor' ] = $this->class_settings["vendor"];
				$this->class_settings[ 'recent_supplies' ] = 1;
				$this->class_settings[ 'data' ]['recent_supplies'] = $this->_get_all_inventory();
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/vendor-recent-supply.php' );
				$returning_html_data = $this->_get_html_view();
				
				return array(
					"html_replacement_selector" => "#vendors-transactions",
					"html_replacement" => $returning_html_data,
					"status" => "new-status",
					//"javascript_functions" => array( "nwInventory.activateRecentSupplyItems" )
				);
			}
		}
		
		private function _get_all_inventory_by_vendor(){
			if( isset( $this->class_settings["vendor"] ) && $this->class_settings["vendor"] ){
				$expenditure = new cExpenditure();
				
				$group_items = " `".$this->table_name."`.`".$this->table_fields[ 'source' ]."`, `".$this->table_name."`.`id` ";
				$where = " `".$this->table_name."`.`record_status`='1' AND `".$this->table_name."`.`".$this->table_fields["source"]."` = '".$this->class_settings["vendor"]."' ";
				
				$select = " SUM( `".$expenditure->table_name."`.`".$expenditure->table_fields['amount_due']."` ) as 'amount_due', SUM( `".$expenditure->table_name."`.`".$expenditure->table_fields[ 'amount_paid' ]."` ) as 'amount_paid', COUNT( * ) as 'transactions' ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$expenditure->table_name."` ON `".$expenditure->table_name."`.`".$expenditure->table_fields['production_id']."` = `".$this->table_name."`.`id` WHERE ".$where."  AND `".$expenditure->table_name."`.`record_status` = '1'  ";
				
				//echo $query; exit;
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 0,
					'tables' => array( $this->table_name, $expenditure->table_name ),
				);
				$sales = execute_sql_query($query_settings);
				
				if( isset( $sales[0][ "amount_due" ] ) && isset( $sales[0][ "amount_paid" ] ) ){
					return array( "amount_due" => $sales[0][ "amount_due" ], "amount_paid" => $sales[0][ "amount_paid" ], "transactions" => $sales[0][ "transactions" ] );
				}
				
			}
		}
		
		private function _delete_stocked_item(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Record ID</h4>Please select a record to delete';
				return $err->error();
			}
			
			$this->class_settings[ "current_record_id" ] = $_POST[ "id" ];
			$i = $this->_get_inventory();
			
			$_POST['mod'] = 'delete-'.md5( $this->table_name );
			$r = $this->_delete_records();
			
			if( isset( $r[ "deleted_record_id" ] ) && $r[ "deleted_record_id" ] ){
				
				unset( $r["html"] );
				$r["status"] = "new-status";
				$r["html_removal"] = "#recent-supply-items tr#" . $r[ "deleted_record_id" ];
				$r["javascript_functions"] = array( "nwInventory.updateStockLevels" );
				
			}
			
			return $r;
		}
		
		private function _get_recent_supplies(){
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$this->class_settings[ 'specific_item' ] = $_POST["id"];
				$this->class_settings[ 'recent_supplies' ] = 1;
				/*
				//enable history in issue stock view
				if( isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$this->class_settings[ 'store' ] = $_SESSION[ "store" ];
				}
				*/
				$this->class_settings[ 'data' ]['recent_supplies'] = $this->_get_all_inventory();
				
				$item_details = get_items_details( array( "id" => $this->class_settings[ 'specific_item' ] ) );
				$this->class_settings[ 'data' ]['item_details'] = $item_details;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/recent-supply.php' );
				$returning_html_data = $this->_get_html_view();
				
				return array(
					"html_replacement_selector" => "#supply-history",
					"html_replacement" => $returning_html_data,
					"status" => "new-status",
					"javascript_functions" => array( "nwInventory.activateRecentSupplyItems", 'set_function_click_event' )
				);
			}
		}
		
		private function _generate_sales_report(){
			$returning_html_data = "";
			
			$field_name = "date";
			$initial_where = " `".$this->table_name."`.`record_status`='1' ";
			$where = $initial_where;
			
			$title = "";
			$select = "";
			$grouping = 1;
			
			
			$regard_store = 1;
			
			switch( $this->class_settings["action_to_perform"] ){
			case "generate_app_sales_report":
			break;
			case "generate_sales_report":
				$regard_store = 0;
			break;
			}
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "amount_due":
				case "amount_paid":
				case "cost":
				case "balance":
				case "discount_type":
				case "payment_method":
				case "comment":
				break;
				default:
					if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
					else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
				break;
				}
			}
			
			$report_type = "";
			if( isset( $_GET["department"] ) && $_GET["department"] ){
				$report_type = $_GET["department"];
			}
			
			$start_date_timestamp = 0;
			if( isset( $_GET["start_date"] ) && $_GET["start_date"] ){
				$st = explode( "-", $_GET["start_date"] );
				if( isset( $st[2] ) )
					$start_date_timestamp = mktime( 0,0,0, $st[1], $st[2], $st[0] );
			}
			$start_date = $start_date_timestamp;
			
			$end_date_timestamp = 0;
			if( isset( $_GET["end_date"] ) && $_GET["end_date"] ){
				$st = explode( "-", $_GET["end_date"] );
				if( isset( $st[2] ) )
					$end_date_timestamp = mktime( 23,59,59, $st[1], $st[2], $st[0] );
			}
			$end_date = $end_date_timestamp;
			
			$group1 = "";
			if( isset( $_GET["budget"] ) && $_GET["budget"] )
				$group1 = $_GET["budget"];
			
			$skip_joins = 0;
			
			$date_filter = "M-Y";
			$get_opening_stock = 0;
			$age_key = "date";
			
			$pen_required = 0;
			$do_group_items = 0;
			
			$subtitle = "";
			
			switch( $group1 ){
			case "monthly":
				$date_filter = "M-Y";
				$grouping = 10;
			break;
			case "daily":
				$date_filter = "d-M-Y";
				$grouping = 100;
			break;
			case "yearly":
				$date_filter = "Y";
				$grouping = 1;
			break;
			}
			
			switch( $report_type ){
			case 'low_stock_level_report':
				$title = "LOW STOCK LEVEL REPORT";
				$where = 1;
				$skip_joins = 1;
				
				if( $regard_store && isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$s1 = get_stores();
					if( isset( $s1[ $_SESSION[ "store" ] ] ) && $s1[ $_SESSION[ "store" ] ] ){
						$subtitle = '<strong>' . $s1[ $_SESSION[ "store" ] ] . '</strong>';
						$this->class_settings["store"] = $_SESSION[ "store" ];
					}
				}
				$start_date = 0;
				$end_date = 0;
				
				$this->class_settings[ 'ignore_limit' ] = 1;
				$inventory = $this->_get_all_inventory();
				$grouping = 30;
			break;
			case "stock_level_report":
				$title = "STOCK LEVEL REPORT";
				$where = 1;
				$skip_joins = 1;
				
				if( $regard_store && isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
					$s1 = get_stores();
					if( isset( $s1[ $_SESSION[ "store" ] ] ) && $s1[ $_SESSION[ "store" ] ] ){
						$subtitle = '<strong>' . $s1[ $_SESSION[ "store" ] ] . '</strong><br />';
						$this->class_settings["store"] = $_SESSION[ "store" ];
					}
				}
				
				$this->class_settings["start_date"] = $start_date;
				$this->class_settings["end_date"] = $end_date;
				
				$this->class_settings[ 'ignore_limit' ] = 1;
				$this->class_settings["full_stock_level"] = 1;
				$inventory = $this->_get_all_inventory();
				$grouping = 30;
			break;
			case 'stock_supply_history_report':
				$title = "STOCK SUPPLY HISTORY REPORT";
				$skip_joins = 0;
				//$pen_required = 1;
			break;
			}
			
			if( $start_date ){
				$subtitle .= "From: <strong>" . date( "d-M-Y", doubleval( $start_date ) ) . "</strong> ";
			}
			
			if( $end_date ){
				$subtitle .= " To: <strong>" . date( "d-M-Y", doubleval( $end_date ) ) . "</strong>";
			}
				
			if( $where ){
				$all_data = array();
				
				if( ! $skip_joins ){
					
					$where2 = "";
					if( $start_date ){
						$where2 .= " `".$this->table_name."`.`".$this->table_fields["date"]."` >= " . $start_date;
					}
					
					if( $end_date ){
						if( $where2 ){
							$where2 .= " AND `".$this->table_name."`.`".$this->table_fields["date"]."` <= " . $end_date;
						}else{
							$where2 .= " `".$this->table_name."`.`".$this->table_fields["date"]."` <= " . $end_date;
						}
					}
					
					$where1 = " `".$this->table_name."`.`record_status`='1' ";
					
					$pen_val = "";
					if( isset( $_GET["operator"] ) && $_GET["operator"] ){
						$p = get_items_details( array( "id" => $_GET["operator"] ) );
						if( isset( $p[ "id" ] ) ){
							$title .= "<br /><strong>".ucwords( $p[ "description" ] )."</strong>";
							$pen_val = $p[ "id" ];
						}
					}
					if( $pen_val ){
						$where1 .= " AND `".$this->table_name."`.`".$this->table_fields["item"]."` = '".$pen_val."' ";
					}else{
						if( $pen_required ){
							$error_file = "select-pen-message.php";
							$where = "";
						}
					}
					
					if( $regard_store && isset( $_SESSION[ "store" ] ) && $_SESSION[ "store" ] ){
						$s1 = get_stores();
						if( isset( $s1[ $_SESSION[ "store" ] ] ) && $s1[ $_SESSION[ "store" ] ] ){
							$subtitle = '<strong>' . $s1[ $_SESSION[ "store" ] ] . '</strong><br />' . $subtitle;
							$where1 .= " AND `".$this->table_name."`.`".$this->table_fields[ 'store' ]."` = '" . $_SESSION[ "store" ] . "' ";
						}
					}
					
					if( $where2 )$where1 = " AND " . $where1;
					
					$where = " ( " . $where2 . $where1 . " ) ";
					
					$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields[ 'date' ]."` DESC ";
					
					//echo $query; exit;
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					$inventory = execute_sql_query($query_settings);
				}
				
				$all_data = $inventory;
				
				//print_r($all_data); exit;
				switch( $grouping ){
				case 1:		//based on year
				case 10:	//based on months
				case 100:	//based on days
					if( ! $date_filter )$date_filter = "F";
					
					//group data based on year
					$all_new = array();
					
					$birds = 0;
					
					foreach( $all_data as $sval ){
						$key = date( $date_filter , doubleval( $sval["date"] ) ) . $sval["item"];
						if( isset( $all_new[ $key ] ) ){
							foreach( $sval as $k => $v ){
								//echo $k.":::"; 
								switch( $k ){
								case "source":
								case "item":
								case "date":
								case "cost_price":
								case "selling_price":
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
					//exit;
					//print_r($all_new); exit;
					$all_data = $all_new;
					$this->class_settings[ 'data' ][ 'date_filter' ] = $date_filter;
					
				break;
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-report' );
				$this->class_settings[ 'data' ][ 'report_subtitle' ] = $subtitle;
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'report_type' ] = $report_type;
				$this->class_settings[ 'data' ][ 'report_data' ] = $all_data;
				$this->class_settings[ 'data' ][ 'report_age' ] = isset( $report_age )?$report_age:"";
				$this->class_settings[ 'data' ][ 'days_filter' ] = isset( $days )?$days:7;
				$this->class_settings[ 'data' ][ 'selected_pen' ] = isset( $pen_val )?$pen_val:"";
				
				$returning_html_data = $this->_get_html_view();
				//$returning_html_data = $query;
			}else{
				//return error message
				if( ! $error_file )$error_file = "error-message.php";
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$error_file );
				$returning_html_data = $this->_get_html_view();
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
		
		private function _display_all_reports_full_view(){
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-reports-full-view' );
			
			$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
			
			$this->class_settings[ 'data' ][ 'report_type2' ] = get_items_grouped_default();
			//$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-items";
			
			$this->class_settings[ 'data' ][ 'report_type3' ] = get_report_periods_without_weeks();
			$this->class_settings[ 'data' ][ 'selected_option3' ] = "monthly";
			
			$r1 = get_inventory_report_types();
			
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "jewelry":
				break;
				default:
					if( isset( $r1[ 'stock_supply_history_report_picture' ] ) )unset( $r1[ 'stock_supply_history_report_picture' ] );
				break;
				}
			}
			
			$this->class_settings[ 'data' ][ 'report_type5' ] = $r1;
			$this->class_settings[ 'data' ][ 'selected_option5' ] = "low_stock_level_report";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'display_app_reports_full_view':
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_app_sales_report";
			
				$returning_html_data = $this->_get_html_view();
			
				return array(
					'html_replacement_selector' =>  "#dash-board-main-content-area",
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ) 
				);
			break;
			}
			
			$returning_html_data = $this->_get_html_view();
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _delete_goods_produced(){
			if( ! ( isset( $this->class_settings[ 'production_id' ] ) ) ){
				return 0;
			}
			
			$select = " `modified_by` = '".$this->class_settings["user_id"]."', `modification_date` = '".$this->class_settings[ 'production_id' ]."', `record_status` = '0' ";
			$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET ".$select." where `".$this->table_fields[ "production_id" ]."` = '".$this->class_settings[ 'production_id' ]."' ";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'UPDATE',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			execute_sql_query($query_settings);
		}
		
		private function _update_inventory_records(){
			$select = "";
			if( isset( $this->class_settings["update_fields"] ) && isset( $this->class_settings["update_fields"]["production_id"] ) ){
				
				$select = " `modified_by` = '".$this->class_settings["user_id"]."', `modification_date` = '".date("U")."' ";
				
				foreach( $this->class_settings["update_fields"] as $key => $val ){
					if( isset( $this->table_fields[ $key ] ) ){
						$select .= ", `".$this->table_fields[ $key ]."` = '".$val."' ";
					}
				}
				
				$qselect = ", `".$this->table_fields[ "quantity" ]."` = 0 ";
				if( isset( $this->class_settings["update_fields"]["status"] ) ){
					switch( $this->class_settings["update_fields"]["status"] ){
					case 'materials-transfer':
					case 'complete':
						$qselect = ", `".$this->table_fields[ "quantity" ]."` = `".$this->table_fields[ "quantity_expected" ]."` ";
					break;
					}
				}
				$select .= $qselect ;
				
				$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET ".$select." where `".$this->table_fields[ "production_id" ]."` = '".$this->class_settings["update_fields"]["production_id"]."' ";
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				execute_sql_query($query_settings);
			}
		}
		
		private function _view_all_produced_items(){
			
			if( ! isset( $this->class_settings["production_id"] ) )return 0;
			if( ! isset( $this->class_settings["reference_table"] ) )return 0;
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields["production_id"]."`='".$this->class_settings["production_id"]."' AND `".$this->table_fields["reference_table"]."` = '".$this->class_settings["reference_table"]."' ";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$bills = execute_sql_query($query_settings);
			$form = "";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_specific_produced_items':
				return $bills;
			break;
			case 'view_all_produced_items_editable':
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "item":	
					case "quantity":	
					case "selling_price":
					break;
					case "date":
					case "production_id":
					case "store":
					case "source":
						$this->class_settings["hidden_records_css"][ $val ] = 1;
						
						if( isset( $this->class_settings[ $key ] ) )
							$this->class_settings["form_values_important"][ $val ] = $this->class_settings[ $key ];
					break;
					default:
						$this->class_settings["hidden_records"][ $val ] = 1;
					break;
					}
				}
				
				$this->class_settings[ 'form_action_todo' ] = 'save_new_inventory';
				
				$form1 = $this->_generate_new_data_capture_form();
				$form = $form1["html"];
			break;
			}
			
			
			$this->class_settings[ 'data' ][ 'no_edit' ] = 1;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/form-control-view.php' );
			$this->class_settings[ 'data' ]['items'] = $bills;
			$this->class_settings[ 'data' ]['form'] = $form;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new', 'set_function_click_event' ),
			);
		}
		
		private function _delete_inventory(){
			//check for duplicate record
			
			if( ! ( isset( $_GET["month"] ) && $_GET["month"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid inventory ID';
				return $err->error();
			}
			
			$this->class_settings[ 'current_record_id' ] = $_GET["month"];
			
			$member_id = "";
			if( isset( $_GET["budget"] ) && $_GET["budget"] )
				$member_id = $_GET["budget"];
			
			$_POST['id'] = $this->class_settings[ 'current_record_id' ];
			$_POST['mod'] = 'delete-'.md5( $this->table_name );
			
			$this->_delete_records();
			
			$return["status"] = "new-status";
			$return["html_removal"] = "#inventory-" . $this->class_settings[ 'current_record_id' ];
			
			unset( $return["html"] );
			return $return;
		}
		
		private function _save_new_inventory(){
			//check for duplicate record
			$edit_mode = 0;
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$edit_mode = 1;
			}
				
			$return = $this->_save_changes();
			
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
				$html = "";
				
				unset( $this->class_settings[ 'do_not_check_cache' ] );
				$this->class_settings['current_record_id'] = $return['saved_record_id'];
				$record = $this->_get_inventory();
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/form-control-view-row.php' );
			
				$this->class_settings[ 'data' ][ 'pagepointer' ] = $this->class_settings["calling_page"];
				$this->class_settings[ 'data' ][ 'new_record' ] = 1;
				$this->class_settings[ 'data' ][ 'id' ] = $record[ 'production_id' ];
				$this->class_settings[ 'data' ][ 'items' ] = array( $record );
				$this->class_settings[ 'data' ][ 'no_edit' ] = 1;
				
				if( $edit_mode ){
					$return["html_replace"] = $this->_get_html_view();
					$return["html_replace_selector"] = "#inventory-".$record[ 'id' ];
				}else{							
					$return["html_prepend"] = $this->_get_html_view();
					$return["html_prepend_selector"] = "#form-control-table-inventory";
				}
				
				$return["javascript_functions"] = array( "set_function_click_event" );
				unset( $return['saved_record_id'] );
			}
			
			$return["status"] = "new-status";
			
			unset( $return["html"] );
			return $return;
		}
		
		private function _save_produced_goods(){
			
			if( ! ( isset( $this->class_settings["production_items"] ) && is_array( $this->class_settings["production_items"] ) ) ){
				return 0;
			}
			if( ! ( isset( $this->class_settings[ 'production_id' ] ) ) ){
				return 0;
			}
			
			$array_of_dataset = array();
			
			$new_record_id = get_new_id();
			$new_record_id_serial = 0;
			
			$ip_address = get_ip_address();
			$date = date("U");
			
			$cost_price = 0;
			if( isset( $this->class_settings[ "cost_price" ] ) && $this->class_settings[ "cost_price" ] ){
				$cost_price = $this->class_settings[ "cost_price" ];
			}
			
			$transfer = 0;
			if( isset( $this->class_settings[ "transfer" ] ) && $this->class_settings[ "transfer" ] ){
				$transfer = 1;
			}
			
			$stock_items = 0;
			switch( $this->class_settings[ 'status' ] ){
			case 'materials-transfer':
			case 'complete':
			case 'stocked':
			case 'stock':
				$stock_items = 1;
			break;
			}
			$this->class_settings[ 'items' ] = array();
			
			$expiry_date = "";
			$goods_produced = "";
			foreach( $this->class_settings["production_items"] as $k => $v ){
				
				switch( $this->class_settings[ 'status' ] ){
				case "update-goods-received":
					$dataset_to_be_inserted = array(
						$this->table_fields["staff_responsible"] => $this->class_settings[ 'staff_responsible' ],
						$this->table_fields["quantity"] => $v["quantity"],
						
						$this->table_fields["expiry_date"] => $expiry_date,
						$this->table_fields["date"] => $date,
						$this->table_fields["comment"] => $this->class_settings[ "comment" ],
					);
					
					$update_conditions_to_be_inserted = array(
						'where_fields' => 'id',
						'where_values' => $v["id"],
					);

					$array_of_dataset_update[] = $dataset_to_be_inserted;
					$array_of_update_conditions[] = $update_conditions_to_be_inserted;
				break;
				case "update-supplier-invoice":
					$dataset_to_be_inserted = array(
						$this->table_fields["staff_responsible"] => $this->class_settings[ 'staff_responsible' ],
						$this->table_fields["tax"] => $v["tax"],
						$this->table_fields["discount"] => $v["discount"],
						$this->table_fields["cost_price"] => $v["price"],
						$this->table_fields["quantity_expected"] => $v["quantity"],
						
						$this->table_fields["expiry_date"] => $expiry_date,
						$this->table_fields["date"] => $date,
						$this->table_fields["comment"] => $this->class_settings[ "comment" ],
					);
					
					$update_conditions_to_be_inserted = array(
						'where_fields' => 'id',
						'where_values' => $v["id"],
					);

					$array_of_dataset_update[] = $dataset_to_be_inserted;
					$array_of_update_conditions[] = $update_conditions_to_be_inserted;
					$goods_produced = 1;
				break;
				case "update-supplier-goods":
					$dataset_to_be_inserted = array(
						$this->table_fields["staff_responsible"] => $this->class_settings[ 'staff_responsible' ],
						$this->table_fields["quantity"] => $v["quantity"],
						
						$this->table_fields["expiry_date"] => $expiry_date,
						$this->table_fields["date"] => $date,
						$this->table_fields["comment"] => $this->class_settings[ "comment" ],
					);
					
					$update_conditions_to_be_inserted = array(
						'where_fields' => 'id',
						'where_values' => $v["id"],
					);

					$array_of_dataset_update[] = $dataset_to_be_inserted;
					$array_of_update_conditions[] = $update_conditions_to_be_inserted;
					$goods_produced = 1;
				break;
				default:
				
					if( $transfer ){
						$cost_price = $v["price"];
						if( isset( $v["selling_price"] ) )$v["price"] = $v["selling_price"];
						if( isset( $v["expiry_date"] ) )$expiry_date = $v[ 'expiry_date' ];
					}else{
						if( $v["mode"] != "#recent-goods" )continue;
						if( isset( $this->class_settings[ 'expiry_date' ] ) )$expiry_date = $this->class_settings[ 'expiry_date' ];
					}
					
					$qty = 0;
					if( $stock_items ){
						$qty = $v["quantity"];
					}
					
					if( $goods_produced )$goods_produced .= ", " . get_select_option_value( array( "id" => $v["id"], "function_name" => "get_items" ) );
					else $goods_produced = get_select_option_value( array( "id" => $v["id"], "function_name" => "get_items" ) );
					
					$dataset_to_be_inserted = array(
						'id' => $new_record_id .'W'. ++$new_record_id_serial,
						'created_role' => $this->class_settings[ 'priv_id' ],
						'created_by' => $this->class_settings[ 'user_id' ],
						'creation_date' => $date,
						'modified_by' => $this->class_settings[ 'user_id' ],
						'modification_date' => $date,
						'ip_address' => $ip_address,
						'record_status' => 1,
						$this->table_fields["production_id"] => $this->class_settings[ 'production_id' ],
						$this->table_fields["staff_responsible"] => $this->class_settings[ 'staff_responsible' ],
						$this->table_fields["quantity_ordered"] => $v["quantity"],
						$this->table_fields["quantity_expected"] => $v["quantity"],
						
						$this->table_fields["store"] => $this->class_settings[ 'store' ],
						$this->table_fields["source"] => $this->class_settings[ 'source' ],
						$this->table_fields["item"] => $v["id"],
						$this->table_fields["selling_price"] => $v["price"],
						$this->table_fields["quantity"] => $qty,
						
						$this->table_fields["expiry_date"] => $expiry_date,
						$this->table_fields["date"] => $date,
						$this->table_fields["comment"] => $this->class_settings[ "comment" ],
						$this->table_fields["reference_table"] => isset( $this->class_settings[ "reference_table" ] )?$this->class_settings[ "reference_table" ]:"",
						
						$this->table_fields["discount"] => isset( $v[ "discount" ] )?$v[ "discount" ]:"",
						$this->table_fields["tax"] => isset( $v[ "tax" ] )?$v[ "tax" ]:"",
					);
					
					if( $cost_price ){
						$dataset_to_be_inserted[ $this->table_fields["cost_price"] ] = $cost_price;
						
						$this->class_settings[ 'items' ][ $v["id"] ] = array(
							"item" => $v["id"],
							"cost_price" => $cost_price,
						);
					}
				
					//new
					$array_of_dataset[] = $dataset_to_be_inserted;
				break;
				}
			}
			
			$saved = 0;
			if( ! empty( $array_of_dataset ) ){
				
				$function_settings = array(
					'database' => $this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'table' => $this->table_name,
					'dataset' => $array_of_dataset,
				);
				
				$returned_data = insert_new_record_into_table( $function_settings );
				$saved = 1;
				
				if( isset( $this->class_settings[ 'items' ] ) && is_array( $this->class_settings[ 'items' ] ) && ! empty( $this->class_settings[ 'items' ] ) ){
					//update item cost / selling price
					$items = new cItems();
					$items->class_settings = $this->class_settings;
					$items->class_settings["action_to_perform"] = 'update_items';
					$check = $items->items();
				}
			}
			
			if( isset( $array_of_dataset_update ) && ! empty( $array_of_dataset_update ) && ! empty( $array_of_update_conditions ) ){
				$function_settings = array(
					'database' => $this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'table' => $this->table_name,
					'dataset' => $array_of_dataset_update,
				);
				
				$function_settings[ 'update_conditions' ] = $array_of_update_conditions;
				$returned_data = insert_new_record_into_table( $function_settings );
				$saved = 1;
			}
			
			return $goods_produced;
		}
		
		private function _restock(){
			$return = array();
			
			if( isset( $_POST['item'] ) && $_POST['item'] ){
				
				$gsettings = get_general_settings_value_array( 
					array( 
						"key" => array(
							"SHOW EXPIRY DATE" => 0,
							"RECORD RESTOCKED ITEMS AS EXPENDITURE" => 0,
							"CAPTURE PAYMENT WHEN RESTOCKING ITEMS AS EXPENDITURE" => 0,
							"DISABLE DIRECT RESTOCKING" => 0,
						),
						"table" => $this->table_name 
					)
				);
				
				if( isset( $gsettings["DISABLE DIRECT RESTOCKING"] ) && doubleval( $gsettings["DISABLE DIRECT RESTOCKING"] ) ){
					$gsettings["RECORD RESTOCKED ITEMS AS EXPENDITURE"] = 0;
				}
				
				if( ! ( isset( $_POST["date"] ) && $_POST["date"] ) ){
					$_POST["date"] = date("Y-m-d");
				}
				
				$capture_payment_restock_as_expenditure = 0;
				$restock_as_expenditure = 0;
				if( isset( $gsettings["RECORD RESTOCKED ITEMS AS EXPENDITURE"] ) && doubleval( $gsettings["RECORD RESTOCKED ITEMS AS EXPENDITURE"] ) ){
				
					$restock_as_expenditure = 1;
					$_POST['tmp'] = $_POST["production_id"] = get_new_id();
					
					$expenditure = new cExpenditure();
					$_POST["reference_table"] = $expenditure->table_name;
					
					if( isset( $gsettings["CAPTURE PAYMENT WHEN RESTOCKING ITEMS AS EXPENDITURE"] ) && doubleval( $gsettings["CAPTURE PAYMENT WHEN RESTOCKING ITEMS AS EXPENDITURE"] ) ){
						$capture_payment_restock_as_expenditure = 1;
					}
				}
				
				$this->class_settings["update_fields"] = $_POST;
				
				$print_barcode = "";
				if( isset( $_POST["print_barcode"] ) && $_POST["print_barcode"] )
					$print_barcode = $_POST["print_barcode"];
				
				$quantity_to_stock = 0;
				if( isset( $_POST["quantity"] ) && $_POST["quantity"] )
					$quantity_to_stock = $_POST["quantity"];
				
				$_POST['id'] = "";
				
				$return = $this->_update_table_field();
				
				if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
					
					unset( $this->class_settings[ 'do_not_check_cache' ] );
					$i = $this->_get_inventory();
					
					$this->class_settings[ 'specific_item' ] = $i["item"];
					
					$this->class_settings[ 'selling_price' ] = $i["selling_price"];
					$this->class_settings[ 'cost_price' ] = $i["cost_price"];
					$this->class_settings[ 'source' ] = $i["source"];
					$this->class_settings[ 'item' ] = $i["item"];
					
					//update item cost / selling price
					$items = new cItems();
					$items->class_settings = $this->class_settings;
					$items->class_settings["action_to_perform"] = 'update_item';
					$check = $items->items();
					
					if( $check !== 1 ){
						return $check;
					}
					
					$inv = $this->_get_all_inventory();
					
					if( isset( $inv[0]["item"] ) ){
						
						$item = get_items_details( array( "id" => $inv[0]["item"] ) );
						unset( $item["selling_price"] );
						unset( $item["cost_price"] );
						unset( $item["source"] );
						
						switch( $item['type'] ){
						case 'produced_goods':
							$inv[0]["quantity"] = 0;
							$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_fields["quantity"]."` = 0 WHERE `".$this->table_name."`.`id` = '".$i[ "id" ]."' ";
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'UPDATE',
								'set_memcache' => 1,
								'tables' => array( $this->table_name ),
							);
							execute_sql_query($query_settings);
						break;
						case 'service':
						break;
						default:
							if( $restock_as_expenditure ){
								//capture stocking as expenditure
								$this->class_settings["source"] = $this->table_name;
								$this->class_settings["production_id"] = ( isset( $i[ "production_id" ] ) && $i[ "production_id" ] )?$i[ "production_id" ]:$i[ "id" ];
								$this->class_settings["production"] = array(
									"staff_in_charge" => $i['staff_responsible'],
									"amount_due" => ( $i[ "cost_price" ] * $i[ "quantity" ] ),
									"amount_paid" => ( $capture_payment_restock_as_expenditure )?( $i[ "cost_price" ] * $i[ "quantity" ] ):0,
									"date" => date( "Y-m-d" , doubleval( $i[ "date" ] ) ),
									"vendor" => $i["source"],
									"description" => $item['description'],
									"category_of_expense" => 'purchase_order',
									"store" => $i['store'],
									"status" => 'stocked',
								);
								
								$expenditure = new cExpenditure();
								$expenditure->class_settings = $this->class_settings;
								$expenditure->class_settings["action_to_perform"] = "save_expenditure";
								$expenditure->expenditure();
							}
						}
						
						$d = array_merge( $inv[0], $item );
						
						$this->class_settings[ 'data' ]["item"] = $d;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/inventory-list.php' );
						$returning_html_data = $this->_get_html_view();
						
						if( $print_barcode ){
							if( get_general_settings_value( array( "key" => "PRINT BARCODE", "table" => $this->table_name ) ) ){
								$inv[0]["quantity"] = $quantity_to_stock;
								$d = array_merge( $inv[0], $item );
								
								//generate barcode
								$barcode = new cBarcode();
								$barcode->class_settings = $this->class_settings;
								$barcode->class_settings["item"] = $d;
								$barcode->class_settings["action_to_perform"] = "queue_barcode";
								$return = $barcode->barcode();
							}
						}
						
						unset( $return["html"] );
						$return["status"] = "new-status";
						
						$return["html_replace_selector"] = "#".$inv[0]["item"]."-container";
						$return["html_replace"] = $returning_html_data;
						$return["javascript_functions"] = array( 'nwInventory.init' );
						
						return $return;
					}
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
		
		private function _display_app_inventory(){
			
			$capture_image = 0;
			if( defined( "HYELLA_IMAGER" ) && HYELLA_IMAGER == '1' ){
				$capture_image = 1;
			}
			
			$gsettings = get_general_settings_value_array( 
				array( 
					"key" => array(
						"SHOW EXPIRY DATE" => 0,
						"RECORD RESTOCKED ITEMS AS EXPENDITURE" => 0,
						"DISABLE DIRECT RESTOCKING" => 0,
						"ALLOW OVERRIDE DATE" => 0,
						"SHOW VENDOR DURING CAPTURE" => 0,
					),
					"table" => $this->table_name 
				)
			);
			
			$this->class_settings[ 'data' ]['hide_capture_selling_price'] = get_general_settings_value( array( "key" => "HIDE CAPTURE SELLING PRICE", "table" => "items" ) );
			$this->class_settings[ 'data' ]['hide_capture_cost_price'] = get_general_settings_value( array( "key" => "HIDE CAPTURE COST PRICE", "table" => "items" ) );
			
			$this->class_settings[ 'data' ]['quantity'] = get_general_settings_value( array( "key" => "DEFAULT QUANTITY FOR NEW ITEMS", "table" => $this->table_name ) );
			$this->class_settings[ 'data' ]['capture_image'] = $capture_image;
			$this->class_settings[ 'data' ]['print_barcode'] = get_general_settings_value( array( "key" => "PRINT BARCODE", "table" => $this->table_name ) );
			$this->class_settings[ 'data' ]['auto_generate_barcode'] = get_general_settings_value( array( "key" => "AUTO GENERATE BARCODE", "table" => "items" ) );
			$this->class_settings[ 'data' ]['general_settings'] = $gsettings;
			$this->class_settings[ 'data' ]['categories'] = get_items_categories();
			$this->class_settings[ 'data' ]['categories_grouped'] = get_items_categories_grouped();
			$this->class_settings[ 'data' ]['categories_type'] = get_product_types();
			
			$this->class_settings[ 'data' ]['stocked_items'] = $this->_get_all_inventory();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_app_inventory2':
				$this->class_settings[ 'data' ]['centralize'] = 1;
			break;
			}
			
			$filename = 'display-app-inventory';
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
			$returning_html_data = $this->_get_html_view();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_app_inventory2':
				$stores = new cStores();
				$stores->class_settings = $this->class_settings;
				$stores->class_settings[ 'action_to_perform' ] = 'get_stores_list';
				$s = $stores->stores();
				
				if( isset( $s["html_replacement"] ) ){
					$returning_html_data = $s["html_replacement"] . $returning_html_data;
				}
			break;
			}
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _get_all_inventory(){
			$items = new cItems();
			$sales = new cSales();
			$sales_items = new cSales_items();
			
			$production = new cProduction();
			$production_items = new cProduction_items();
			
			$iwhere = "";
			$swhere = "";
			$pwhere = "";
			
			if( isset( $this->class_settings["start_date"] ) && isset( $this->class_settings["end_date"] ) && $this->class_settings["end_date"] && $this->class_settings["start_date"] ){
				$iwhere = " AND `".$this->table_name."`.`".$this->table_fields["date"]."` BETWEEN ".$this->class_settings["start_date"]." AND " . $this->class_settings["end_date"];
				
				$swhere = " AND `".$sales->table_name."`.`".$sales->table_fields["date"]."` BETWEEN ".$this->class_settings["start_date"]." AND " . $this->class_settings["end_date"];
				
				$pwhere = " AND `".$production->table_name."`.`".$production->table_fields["date"]."` BETWEEN ".$this->class_settings["start_date"]." AND " . $this->class_settings["end_date"];
			}
			
			$select = " `".$this->table_name."`.`".$this->table_fields["item"]."` as 'item', SUM( `".$this->table_name."`.`".$this->table_fields["quantity"]."` ) as 'quantity', `".$items->table_name."`.`".$items->table_fields["cost_price"]."` as 'cost_price', `".$items->table_name."`.`".$items->table_fields["selling_price"]."` as 'selling_price', `".$items->table_name."`.`".$items->table_fields["description"]."` as 'description', `".$items->table_name."`.`".$items->table_fields["image"]."` as 'image', `".$items->table_name."`.`".$items->table_fields["category"]."` as 'category', `".$items->table_name."`.`".$items->table_fields["barcode"]."` as 'barcode', `".$items->table_name."`.`".$items->table_fields["type"]."` as 'type', `".$items->table_name."`.`".$items->table_fields["source"]."` as 'source' ";
			
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "jewelry":
					$select .= ", `".$items->table_name."`.`".$items->table_fields["percentage_markup"]."` as 'percentage_markup', `".$items->table_name."`.`".$items->table_fields["color_of_gold"]."` as 'color_of_gold', `".$items->table_name."`.`".$items->table_fields["length_of_chain"]."` as 'length_of_chain', `".$items->table_name."`.`".$items->table_fields["sub_category"]."` as 'sub_category' ";
				break;
				}
			}
			
			$select_sales_items = ", ( SELECT SUM(`".$sales_items->table_name."`.`".$sales_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$sales_items->table_name."` WHERE `".$sales_items->table_name."`.`".$sales_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$sales_items->table_name."`.`record_status` = '1' ) as 'quantity_sold' ";
			
			$select_production_items = ", ( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' ) as 'quantity_used' ";
			
			$join = "LEFT JOIN `".$this->class_settings['database_name']."`.`".$items->table_name."` ON `".$this->table_name."`.`".$this->table_fields["item"]."` = `".$items->table_name."`.`id`";
			
			//$join .= " LEFT JOIN `".$this->class_settings['database_name']."`.`".$sales_items->table_name."` ON `".$sales_items->table_name."`.`".$sales_items->table_fields["item_id"]."` = `".$items->table_name."`.`id`";
			
			$group = " GROUP BY `".$this->table_name."`.`".$this->table_fields["item"]."` ORDER BY `".$items->table_name."`.`".$items->table_fields["description"]."` ";
			$where = " AND `".$items->table_name."`.`record_status` = '1' ";
			
			$select .= ", MAX( `".$this->table_name."`.`".$this->table_fields["date"]."` ) as 'date' ";
			
			if( isset( $this->class_settings[ 'recent_supplies' ] ) && $this->class_settings[ 'recent_supplies' ] ){
				$group = " ORDER BY `".$this->table_name."`.`".$this->table_fields["date"]."` DESC, `".$this->table_name."`.`modification_date` DESC ";
				
				$select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`".$this->table_fields["item"]."` as 'item', `".$this->table_name."`.`".$this->table_fields["quantity"]."` as 'quantity', `".$this->table_name."`.`".$this->table_fields["selling_price"]."` as 'selling_price' , `".$this->table_name."`.`".$this->table_fields["cost_price"]."` as 'cost_price', `".$this->table_name."`.`".$this->table_fields["date"]."` as 'date', `".$this->table_name."`.`".$this->table_fields['staff_responsible']."` as 'staff_responsible', `".$this->table_name."`.`".$this->table_fields["comment"]."` as 'comment' , `".$this->table_name."`.`".$this->table_fields["source"]."` as 'source', `".$this->table_name."`.`".$this->table_fields["store"]."` as 'store' ";
				
				$join = "";
				$where = "";
			}
			
			if( ! ( isset( $this->class_settings[ 'ignore_limit' ] ) && $this->class_settings[ 'ignore_limit' ] ) ){				
				if( defined( "HYELLA_SERVER_FILTER" ) && HYELLA_SERVER_FILTER == '1' ){
					$limit = get_general_settings_value( array( "key" => "NUMBER OF STOCKED RECORDS", "table" => $this->table_name ) );
					if( ! intval( $limit ) )$limit = 300;
					
					$group .= " LIMIT " . $limit;
				}
			}
			
			if( isset( $this->class_settings[ 'sold_items_only' ] ) && $this->class_settings[ 'sold_items_only' ] ){
				$where .= " AND `".$items->table_name."`.`".$items->table_fields["type"]."` NOT IN ( 'raw_materials', 'rearing_feed', 'feed' ) ";
			}
			
			if( isset( $this->class_settings[ 'purchased_items_only' ] ) && $this->class_settings[ 'purchased_items_only' ] ){
				$where .= " AND `".$items->table_name."`.`".$items->table_fields["type"]."` IN ( 'raw_materials', 'purchased_goods' ) ";
			}
			
			if( isset( $this->class_settings[ 'produced_items_only' ] ) && $this->class_settings[ 'produced_items_only' ] ){
				$where .= " AND `".$items->table_name."`.`".$items->table_fields["type"]."` IN ( 'produced_goods', 'consignment' ) ";
			}
			
			if( isset( $this->class_settings[ 'specific_vendor' ] ) && $this->class_settings[ 'specific_vendor' ] ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["source"]."` = '".$this->class_settings[ 'specific_vendor' ]."' ";
			}
			
			if( isset( $this->class_settings[ 'specific_item' ] ) && $this->class_settings[ 'specific_item' ] ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["item"]."` = '".$this->class_settings[ 'specific_item' ]."' ";
			}
			
			if( isset( $this->class_settings[ 'search_item' ] ) && $this->class_settings[ 'search_item' ] ){
				$where .= " AND `".$items->table_name."`.`".$items->table_fields["description"]."` REGEXP '".$this->class_settings[ 'search_item' ]."' ";
			}
			
			if( isset( $this->class_settings[ 'search_item_category' ] ) && $this->class_settings[ 'search_item_category' ] ){
				$where .= " AND `".$items->table_name."`.`".$items->table_fields["category"]."` = '" . $this->class_settings[ 'search_item_category' ] . "' ";
			}
			
			if( isset( $this->class_settings[ 'store' ] ) && $this->class_settings[ 'store' ] ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["store"]."` = '".$this->class_settings[ 'store' ]."' ";
				
				$select_sales_items = ", ( SELECT SUM(`".$sales_items->table_name."`.`".$sales_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$sales_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$sales->table_name."` ON `".$sales->table_name."`.`id` = `".$sales_items->table_name."`.`".$sales_items->table_fields["sales_id"]."` WHERE `".$sales_items->table_name."`.`".$sales_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$sales_items->table_name."`.`record_status` = '1' AND `".$sales->table_name."`.`record_status` = '1' AND `".$sales->table_name."`.`".$sales->table_fields[ "sales_status" ]."` != 'sales_order' AND `".$sales->table_name."`.`".$sales->table_fields[ "store" ]."` = '".$this->class_settings[ 'store' ]."' ".$swhere." ) as 'quantity_sold' ";
				
				$select_sales_items .= ", ( SELECT SUM(`".$sales_items->table_name."`.`".$sales_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$sales_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$sales->table_name."` ON `".$sales->table_name."`.`id` = `".$sales_items->table_name."`.`".$sales_items->table_fields["sales_id"]."` WHERE `".$sales_items->table_name."`.`".$sales_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$sales_items->table_name."`.`record_status` = '1' AND `".$sales->table_name."`.`record_status` = '1' AND `".$sales->table_name."`.`".$sales->table_fields[ "sales_status" ]."` = 'sales_order' AND `".$sales->table_name."`.`".$sales->table_fields[ "store" ]."` = '".$this->class_settings[ 'store' ]."' ".$swhere." ) as 'quantity_ordered' ";
				
				$select_production_items = ", ( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "store" ]."` = '".$this->class_settings[ 'store' ]."' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` NOT IN ( 'damaged-materials', 'sales-order' ) ".$pwhere." ) as 'quantity_used',
				
				( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "store" ]."` = '".$this->class_settings[ 'store' ]."' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` = 'sales-order' ".$pwhere." ) as 'quantity_picked',
				
				( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "store" ]."` = '".$this->class_settings[ 'store' ]."' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` = 'damaged-materials' ".$pwhere." ) as 'quantity_damaged'";
			
			}else{
				//check for sales order settings
				$select_sales_items = ", ( SELECT SUM(`".$sales_items->table_name."`.`".$sales_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$sales_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$sales->table_name."` ON `".$sales->table_name."`.`id` = `".$sales_items->table_name."`.`".$sales_items->table_fields["sales_id"]."` WHERE `".$sales_items->table_name."`.`".$sales_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$sales_items->table_name."`.`record_status` = '1' AND `".$sales->table_name."`.`record_status` = '1' AND `".$sales->table_name."`.`".$sales->table_fields[ "sales_status" ]."` != 'sales_order' ".$swhere." ) as 'quantity_sold' ";
				
				$select_sales_items .= ", ( SELECT SUM(`".$sales_items->table_name."`.`".$sales_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$sales_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$sales->table_name."` ON `".$sales->table_name."`.`id` = `".$sales_items->table_name."`.`".$sales_items->table_fields["sales_id"]."` WHERE `".$sales_items->table_name."`.`".$sales_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$sales_items->table_name."`.`record_status` = '1' AND `".$sales->table_name."`.`record_status` = '1' AND `".$sales->table_name."`.`".$sales->table_fields[ "sales_status" ]."` = 'sales_order' ".$swhere." ) as 'quantity_ordered' ";
				
				$select_production_items = ", ( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` NOT IN ( 'damaged-materials', 'sales-order' ) ".$pwhere." ) as 'quantity_used',  
				
				( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` = 'sales-order' ".$pwhere." ) as 'quantity_picked',

				( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` = 'damaged-materials' ".$pwhere." ) as 'quantity_damaged'";
			}
			
			if( isset( $this->class_settings["full_stock_level"] ) && $this->class_settings["full_stock_level"] ){
				
				if( isset( $this->class_settings[ 'store' ] ) && $this->class_settings[ 'store' ] ){
					$select_production_items = ", ( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "store" ]."` = '".$this->class_settings[ 'store' ]."' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` NOT IN ( 'damaged-materials', 'sales-order' ) ".$pwhere." ) as 'quantity_used',  
				
					( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "store" ]."` = '".$this->class_settings[ 'store' ]."' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` = 'sales-order' ".$pwhere." ) as 'quantity_picked',
					
					( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "store" ]."` = '".$this->class_settings[ 'store' ]."' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` = 'damaged-materials' ".$pwhere." ) as 'quantity_damaged'";
				}else{
					$select_production_items = ", ( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` NOT IN ( 'damaged-materials', 'sales-order' ) ".$pwhere." ) as 'quantity_used',  
				
					( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` = 'sales-order' ".$pwhere." ) as 'quantity_picked',

					( SELECT SUM(`".$production_items->table_name."`.`".$production_items->table_fields["quantity"]."`) FROM `".$this->class_settings['database_name']."`.`".$production_items->table_name."` LEFT JOIN `".$this->class_settings['database_name']."`.`".$production->table_name."` ON `".$production->table_name."`.`id` = `".$production_items->table_name."`.`".$production_items->table_fields["production_id"]."` WHERE `".$production_items->table_name."`.`".$production_items->table_fields["item_id"]."` = `".$this->table_name."`.`".$this->table_fields["item"]."` AND `".$production_items->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`record_status` = '1' AND `".$production->table_name."`.`".$production->table_fields[ "status" ]."` = 'damaged-materials' ".$pwhere." ) as 'quantity_damaged'";
				}
				
				$where .= $iwhere;
			}
			
			$select .= $select_sales_items . $select_production_items;
			
			if( isset( $this->class_settings[ 'production_items' ] ) && $this->class_settings[ 'production_items' ] == 1 ){
				$where .= " AND ( `".$items->table_name."`.`".$items->table_fields["type"]."` IN ( 'raw_materials', 'produced_goods' ) ) ";
			}
			
			if( isset( $this->class_settings[ 'production_items' ] ) && $this->class_settings[ 'production_items' ] == 2 ){
				$where .= " AND ( `".$items->table_name."`.`".$items->table_fields["type"]."` != 'service' ) ";
			}
			
			$query = "SELECT ".$select." FROM `".$this->class_settings['database_name']."`.`".$this->table_name."` ".$join." WHERE `".$this->table_name."`.`record_status` = '1' ".$where.$group;
			
			//echo $query; exit;
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 0,
				'tables' => array( $this->table_name, $items->table_name, $sales_items->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if( ! empty( $sql_result ) ){
				if( class_exists( "cFarm_daily_record" ) ){
					//get quantity of eggs
					$farm_daily_record = new cFarm_daily_record();
					$farm_daily_record->class_settings = $this->class_settings;
					$farm_daily_record->class_settings[ 'action_to_perform' ] = 'get_quantity_of_eggs';
					$eggs = $farm_daily_record->farm_daily_record();
					
					//print_r( $eggs ); exit;
					//print_r($sql_result); exit;
					//pass quantity of eggs
					foreach( $sql_result as & $sval ){
						if( ! isset( $sval["type"] ) )continue;
						switch( $sval["type"] ){
						case "feed":
							if( isset( $eggs[ 'feed_received' ] ) )
								$sval["quantity"] = $eggs[ 'feed_received' ];
							
							if( isset( $eggs[ 'total_feed_consumption' ] ) )
								$sval["quantity_sold"] = $eggs[ 'total_feed_consumption' ];
						break;
						case "eggs":
						case "crate_of_eggs":
							if( isset( $eggs[ $sval["item"] ] ) )
								$sval["quantity"] = $eggs[ $sval["item"] ];
						break;
						}
					}
				}
			}
			
			return $sql_result;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			//$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			//$this->datatable_settings['custom_edit_button'] = $this->_get_html_view();
			$where = "";
			unset( $_SESSION[ $this->table_name ] );
			$title = "Manage Items Supply";
			$show_filter_form = 0;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'display_custom_records_full_view':
				$title = "Items Supply History";
				
				$show_filter_form = 1;
				$this->datatable_settings['show_add_new'] = 0;
				$this->datatable_settings['show_edit_button'] = 0;
				$this->datatable_settings['show_delete_button'] = 0;
			break;
			default:
			break;
			}
			
			if( $show_filter_form ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-filter-form.php' );
				$this->class_settings[ 'data' ]['form_data'] = $this->_get_html_view();
				
				$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
				$this->class_settings[ 'data' ]['form_title'] = 'Search Supply History';
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$datatable = $this->_display_data_table();
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = $title;
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			//$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
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
			case "view_all_produced_items_editable":
			break;
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify Inventory';
				$this->class_settings["hidden_records"][ $this->table_fields["production_id"] ] = 1;
			break;
			default:
				$this->class_settings["hidden_records"][ $this->table_fields["production_id"] ] = 1;
				
				if( ! isset( $this->class_settings['form_values_important'][ $this->table_fields["date"] ] ) )
					$this->class_settings['form_values_important'][ $this->table_fields["date"] ] = date("U");
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
			
			if( isset( $returning_html_data['deleted_record_id'] ) && $returning_html_data['deleted_record_id'] ){
				$cache_key = $this->table_name;
				
				switch ( $this->class_settings['action_to_perform'] ){
				case 'delete_only':
				break;
				default:
					//delete expenditure
					$expenditure = new cExpenditure();
					$expenditure->class_settings = $this->class_settings;
					$expenditure->class_settings["action_to_perform"] = "delete_expense_from_stock";
					$expenditure->expenditure();
					
					$d = '';
					
					$d1 = explode( ":::", $returning_html_data['deleted_record_id'] );
					foreach( $d1 as $dd ){
						if( ! $dd )continue;
						if( $d )$d .= ", '" . $dd . "' ";
						else $d = "'" . $dd . "' ";
					}
					if( $d ){
						$query = "SELECT `".$this->table_fields["production_id"]."` as 'id' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `id` IN ( ".$d." ) ";
						
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'SELECT',
							'set_memcache' => 1,
							'tables' => array( $this->table_name ),
						);
						$sql = execute_sql_query($query_settings);
						
						$ids = "";
						if( is_array( $sql ) && ! empty( $sql ) ){
							foreach( $sql as $val ){
								if( ! $val["id"] )continue;
								if( $ids )$ids .= ":::" . $val["id"];
								else $ids = $val["id"];
							}
						}
						
						if( $ids ){
							unset( $_POST["id"] );
							unset( $_POST["ids"] );
							$_POST["ids"] = $ids;
							
							$table = 'production';
							$_POST["mod"] = 'delete-' . md5( $table );
							
							$actual_name_of_class = 'c'.ucwords( $table );
							$module = new $actual_name_of_class();
							$module->class_settings = $this->class_settings;
							$module->class_settings["action_to_perform"] = 'delete';
							$module->$table();
						}
					}
					
				break;
				}
				
				$returning_html_data[ 'data_table_name' ] = $this->table_name;
				//$returning_html_data[ 'reload_other_tables' ] = array( $this->table_name );
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
				
				$err->class_that_triggered_error = 'cinventory.php';
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
					$record = $this->_get_inventory();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_inventory(){
			
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
				else $select = "`id`, `serial_num`, `modification_date`, `creation_date`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `id` = '".$this->class_settings[ 'current_record_id' ]."'";
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
					//$this->class_settings["member_id"] = $record["member_id"];
					//$this->_get_inventory();
				}
				
				return $single_data;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-inventory-',//.$record["member_id"],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			$members = get_cache_for_special_values( $settings );
			
			if( is_array( $members ) ){
				if( $clear ){
					unset( $members[ $record['id'] ] );
				}else{
					$members[ $record['id'] ] = $record;
				}
				
				$settings = array(
					'cache_key' => $cache_key.'-inventory-'.$record["member_id"],
					'directory_name' => $cache_key,
					'cache_values' => $members,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				return $members;
			}
		}
	}
?>