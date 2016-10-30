<?php
	/**
	 * transactions Class
	 *
	 * @used in  				transactions Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	transactions
	 */

	/*
	|--------------------------------------------------------------------------
	| transactions Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	include "cTransactions_draft.php";
	include "cDebit_and_credit_draft.php";
	
	class cTransactions{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'transactions';
		
		private $associated_cache_keys = array(
			'transactions',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'transactions001',
			'description' => 'transactions002',
			'reference' => 'transactions003',
			'reference_table' => 'transactions004',
			
			'debit' => 'transactions005',
			'credit' => 'transactions010',
			
			'status' => 'transactions006',
			
			'submitted_by' => 'transactions007',
			'submitted_on' => 'transactions008',
			'store' => 'transactions009',
			'extra_reference' => 'transactions011',
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
	
		function transactions(){
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
			case "view_invoice_app":
			case "view_invoice":
				$returned_value = $this->_view_invoice();
			break;
			case 'display_all_payables_full_view':
			case 'display_all_receivables_full_view':
			case "display_all_reports_full_view":
			case "display_all_cash_book_full_view":
				$returned_value = $this->_display_all_reports_full_view();
			break;
			case 'get_financial_reports':
				$returned_value = $this->_get_financial_reports();
			break;
			case 'refresh_transactions_info':
				$returned_value = $this->_refresh_transactions_info();
			break;
			case 'save_transactions_and_return_receipt_only':
			case 'save_transactions_and_return_receipt':
				$returned_value = $this->_save_transactions_and_return_receipt();
			break;
			case 'update_transactions_status':
				$returned_value = $this->_update_transactions_status();
			break;
			case 'save_update_transactions_status':
				$returned_value = $this->_save_site_changes();
			break;
			case 'delete_transactions_manifest':
				$returned_value = $this->_delete_transactions_manifest();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'post_new_general_transaction':
			case 'post_new_transaction':
				$returned_value = $this->_post_new_transaction();
			break;
			case 'save_transaction_manifest':
				$returned_value = $this->_save_transaction_manifest();
			break;
			case "add_transaction_from_sales":
				$returned_value = $this->_add_transaction_from_sales();
			break;
			case "generate_sales_report":
				$returned_value = $this->_generate_sales_report();
			break;
			}
			
			return $returned_value;
		}
		
		private function _transform_items( $items = array(), $source_account = "" ){
			$transformed = array();
			
			foreach( $items as $mode => $i ){
				foreach( $i as $k => & $v ){
					
					$atype = "";
					$type = "";
					$rtype = "";
					
					switch( $mode ){
					case "pay-vendors":
						$atype = "account_payable";
						$type = "debit";
					break;
					case "pay-bills":
						$atype = "operating_expense";
						$type = "debit";
					break;
					case "post-customer-payment":
						$atype = "accounts_receivable";
						$type = "credit";
					break;
					case "transfer-money":
						$atype = "cash_book";
						$type = "debit";
					break;
					}
					
					if( $atype ){
						if( $type == "debit" ){
							$rtype = "credit";
						}else{
							$rtype = "debit";
						}
						
						$v["account_type"] = $atype;
						$v["type"] = $type;
						$transformed[ $k ] = $v;
						
						if( $source_account ){
							if( ! isset( $transformed[ $rtype . $source_account ] ) ){
								$transformed[ $rtype . $source_account ] = array(
									"account" => $source_account,
									"amount" => 0,
									"type" => $rtype,
									"account_type" => "cash_book",
									"currency" => "",
									"extra_reference" => "",
								);
							}
							$transformed[ $rtype . $source_account ]["amount"] += $v["amount"];
						}
					}
				}
			}
			
			return $transformed;
		}
		
		private function _preview_transaction_manifest(){
			
			if( isset( $_POST["json"]["item"] ) && is_array( $_POST["json"]["item"] ) && ! empty( $_POST["json"]["item"] ) ){
				$cart_items = $_POST["json"];
				//store in temp location
				
				$e['debit_and_credit'] = $this->_transform_items( $cart_items["item"], $cart_items["source_account"] );
				
				unset( $cart_items["item"] );
				$cart_items["id"] = 'preview';
				$e["event"] = $cart_items;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/transactions-manifest.php' );
				$this->class_settings[ 'data' ] = $e;
				$this->class_settings[ 'data' ]["preview"] = 1;
				
				$html = $this->_get_html_view();
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
				
				$this->class_settings[ 'data' ]["html_title"] = "Preview Transactions";
				
				$this->class_settings[ 'data' ]['html'] = $html;
				
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'do_not_reload_table' => 1,
					'html_prepend' => $returning_html_data,
					'html_prepend_selector' => "#dash-board-main-content-area",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ),
				);
			}
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>An Unknown Error Occured</h4>Transaction could not be saved';
			return $err->error();
		}
		
		private function _generate_sales_report(){
			$returning_html_data = "";
			
			$field_name = "date";
			$initial_where = " `".$this->table_name."`.`record_status`='1' ";
			$where = $initial_where;
			
			$title = "";
			$select = "";
			$grouping = 1;
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "comment":
				break;
				default:
					if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
					else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
				break;
				}
			}
			
			$report_type = "periodic_sales_report";
			//$report_type = "production_report";
			if( isset( $_GET["department"] ) && $_GET["department"] ){
				$report_type = $_GET["department"];
			}
			
			$start_date_timestamp = 0;
			if( isset( $_GET["start_date"] ) && $_GET["start_date"] ){
				$st = explode( "-", $_GET["start_date"] );
				if( isset( $st[2] ) ){
					$start_date_timestamp = mktime( 0,0,0, $st[1], $st[2], $st[0] );
				}
			}
			$start_date = $start_date_timestamp;
			
			$end_date_timestamp = 0;
			if( isset( $_GET["end_date"] ) && $_GET["end_date"] ){
				$st = explode( "-", $_GET["end_date"] );
				if( isset( $st[2] ) )
					$end_date_timestamp = mktime( 23,59,59, $st[1], $st[2], $st[0] );
			}
			$end_date = $end_date_timestamp;
			
			$previous_data = array();
			
			$group1 = "";
			$skip_joins = 0;
			$skip_pen_val = 0;
			
			$date_filter = "M-Y";
			$get_opening_stock = 0;
			$age_key = "date";
			
			$pen_required = 0;
			$do_group_items = 0;
			
			$where10 = "";
			$limit10 = "";
			$extra_where = "";
			$pen_val_function = "";
			
			$get_previous = 0;
			
			switch( $report_type ){
			case "income_statement":
			case "income_statement_summary":
				$skip_joins = 0;
				$skip_pen_val = 1;
				$grouping = 20;
				$do_group_items = 4;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_financial_accounting_reports" ) );
				
				$get_previous = 1;
			break;
			case "balance_sheet":
			case "balance_sheet_summary":
			case "trial_balance":
			case "trial_balance_summary":
				$skip_joins = 0;
				$skip_pen_val = 1;
				$grouping = 20;
				$do_group_items = 4;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_financial_accounting_reports" ) );
			break;
			case "general_ledger_summary":
			case "general_ledger":
			case "flood_sheet":
				$skip_joins = 0;
				$skip_pen_val = 0;
				$grouping = 20;
				$do_group_items = 4;
				
				$pen_val_function = "get_first_level_accounts";
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_financial_accounting_reports" ) );
			break;
			case "get_all_transactions_on_account":
				$skip_joins = 0;
				$skip_pen_val = 1;
				$grouping = 20;
				$do_group_items = 2;
				
				$title = "";
				
				$this->class_settings['action_to_perform'] = "return_data";
			break;
			case "get_recent_transactions_on_account":
				$skip_joins = 0;
				$skip_pen_val = 1;
				$grouping = 20;
				$do_group_items = 4;
				
				$title = "";
				$this->class_settings[ "limit" ] = 12;
				$limit10 = " LIMIT ".$this->class_settings[ "limit" ];
				$this->class_settings['action_to_perform'] = "return_data";
			break;
			case "get_transactions_on_account":
				$skip_joins = 0;
				$skip_pen_val = 1;
				$grouping = 20;
				$do_group_items = 4;
				
				$title = "";
				$this->class_settings['action_to_perform'] = "return_data";
			break;
			case "customers_owing_summary":
			case "customers_transactions_summary":
				$skip_joins = 0;
				$skip_pen_val = 0;
				$grouping = 20;
				$do_group_items = 4;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_customers_financial_accounting_reports" ) );
				
				$pen_val_function = "customers";
				//$extra_where = " AND `".$this->table_name."`.`".$this->table_fields['reference_table']."` = 'expenditure' ";
			break;
			case "customers_owing":
			case "customers_transactions":
				$skip_joins = 0;
				$skip_pen_val = 0;
				$grouping = 20;
				$do_group_items = 4;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_customers_financial_accounting_reports" ) );
				
				$pen_val_function = "customers";
				//$extra_where = " AND `".$this->table_name."`.`".$this->table_fields['reference_table']."` = 'expenditure' ";
			break;
			case "vendors_transactions_summary":
				$skip_joins = 0;
				$skip_pen_val = 0;
				$grouping = 20;
				$do_group_items = 4;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_vendors_financial_accounting_reports" ) );
				
				$pen_val_function = "vendors";
				//$extra_where = " AND `".$this->table_name."`.`".$this->table_fields['reference_table']."` = 'expenditure' ";
			break;
			case "vendors_transactions":
				$skip_joins = 0;
				$skip_pen_val = 0;
				$grouping = 20;
				$do_group_items = 4;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_vendors_financial_accounting_reports" ) );
				
				$pen_val_function = "vendors";
				//$extra_where = " AND `".$this->table_name."`.`".$this->table_fields['reference_table']."` = 'expenditure' ";
			break;
			case "cash_book_transactions_summary":
			case "cash_book_transactions":
				$skip_joins = 0;
				$skip_pen_val = 0;
				$grouping = 20;
				$do_group_items = 4;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_cash_book_financial_accounting_reports" ) );
				
				$pen_val_function = "cash_book";
			break;
			case "flood_sheet_summary":
				$skip_joins = 0;
				$skip_pen_val = 1;
				$grouping = 20;
				$do_group_items = 4;
				$title = get_select_option_value( array( "id" => $report_type, "function_name" => "get_financial_accounting_reports" ) );
				
				$get_previous = 1;
				//$extra_where = " AND `".$this->table_name."`.`".$this->table_fields['reference_table']."` = 'expenditure' ";
			break;
			}
			
			$subtitle = "";
			
			if( $start_date ){
				$subtitle .= "From: <strong>" . date( "d-M-Y", doubleval( $start_date ) ) . "</strong> ";
			}
			
			if( $end_date ){
				$subtitle .= " To: <strong>" . date( "d-M-Y", doubleval( $end_date ) ) . "</strong>";
			}
			
			$html_replacement1 = "";
			$html_replacement_selector1 = "";
			
			if( $where ){
				$all_data = array();
				
				if( ! $skip_joins ){
					$sales_items = new cDebit_and_credit();
					
					if( isset( $this->class_settings[ "account" ] ) && $this->class_settings[ "account" ] ){
						$extra_where = " AND `".$sales_items->table_name."`.`".$sales_items->table_fields['account']."` = '".$this->class_settings[ "account" ]."' ";
						
						if( isset( $this->class_settings[ "account_type" ] ) && $this->class_settings[ "account_type" ] ){
							$extra_where .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields['account_type']."` = '".$this->class_settings[ "account_type" ]."' ";
						}
					}
					
					$group_items = "";
					switch( $do_group_items ){
					case 1:
						$group_items =  " `".$sales_items->table_name."`.`".$sales_items->table_fields['account']."`, `".$sales_items->table_name."`.`".$sales_items->table_fields['type']."`, `".$this->table_name."`.`".$this->table_fields['store']."` ";
					break;
					case 2:
						$group_items = " `".$sales_items->table_name."`.`".$sales_items->table_fields['account']."`, `".$sales_items->table_name."`.`".$sales_items->table_fields['type']."` ";
					break;
					case 3:
						$group_items =  " `".$sales_items->table_name."`.`".$sales_items->table_fields['account']."`, `".$this->table_name."`.`".$this->table_fields['store']."` ";
						
						//$where10 = " AND `".$sales_items->table_name."`.`".$sales_items->table_fields['type']."` = 'credit' ";
					break;
					case 4:
						//single expenditures
						$group_items =  " `".$sales_items->table_name."`.`id` ";
					break;
					}
					
					$where2 = "";
					if( $start_date )
						$where2 .= " `".$this->table_name."`.`".$this->table_fields["date"]."` >= " . $start_date;
					
					if( $end_date ){
						if( $where2 )$where2 .= " AND `".$this->table_name."`.`".$this->table_fields["date"]."` <= " . $end_date;
						else $where2 .= " `".$this->table_name."`.`".$this->table_fields["date"]."` <= " . $end_date;
					}
					
					$where1 = " `".$this->table_name."`.`record_status`='1' AND ( `".$sales_items->table_name."`.`record_status`='1' ) " . $where10;
					
					$pen_val = "";
					if( ( ! $skip_pen_val ) && isset( $_GET["operator"] ) && $_GET["operator"] ){
						$t = "";
						switch( $pen_val_function ){
						case "customers":
							$p = get_customers_details( array( "id" => $_GET["operator"] ) );
							if( isset( $p[ "id" ] ) ){
								$t = $p[ "name" ];
								$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account"]."` = '".$p["id"]."' ";
							}else{
								$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account_type"]."` IN ( 'accounts_receivable' ) ";
							}
						break;
						case "vendors":
							$p = get_vendors_details( array( "id" => $_GET["operator"] ) );
							if( isset( $p[ "id" ] ) ){
								$t = $p[ 'name_of_vendor' ];
								$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account"]."` = '".$p["id"]."' ";
							}else{
								$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account_type"]."` IN ( 'account_payable' ) ";
							}
						break;
						case "cash_book":
							$p = get_chart_of_accounts_details( array( "id" => $_GET["operator"] ) );
							if( isset( $p[ "id" ] ) ){
								$t = $p[ 'title' ];
								$where1 .= " AND ( `".$sales_items->table_name."`.`".$sales_items->table_fields["account_type"]."` = '".$p["id"]."' OR `".$sales_items->table_name."`.`".$sales_items->table_fields["account"]."` = '".$p["id"]."' ) ";
							}else{
								$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account_type"]."` IN ( 'cash_book', 'petty_cash', 'main_cash', 'bank1', 'bank2', 'bank3', 'bank4', 'bank5', 'bank6', 'bank7', 'bank8', 'bank9', 'bank10' ) ";
							}
						break;
						case "get_first_level_accounts":
							
							$p = get_chart_of_accounts_details( array( "id" => $_GET["operator"] ) );
							if( isset( $p[ "id" ] ) ){
								$t = $p[ 'title' ];
								
								switch( $p[ "id" ] ){
								case "petty_cash": case "main_cash":
								case "bank6": case "bank7": case "bank8": case "bank9": case "bank10":
								case "bank5": case "bank4": case "bank3": case "bank2": case "bank1":
								break;
								case "cash_book":
									$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account_type"]."` IN ( 'cash_book', 'petty_cash', 'main_cash', 'bank1', 'bank2', 'bank3', 'bank4', 'bank5', 'bank6', 'bank7', 'bank8', 'bank9', 'bank10' ) ";
								break;
								default:
									$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account_type"]."` = '".$p["id"]."' ";
								break;
								}
								
								if( isset( $_GET["budget"] ) && $_GET["budget"] ){
									switch( $p[ "id" ] ){
									case "accounts_receivable":	
										$p = get_customers_details( array( "id" => $_GET["budget"] ) );
										if( isset( $p[ "id" ] ) ){
											$subtitle = $p[ "name" ] . '<br />' . $subtitle;
											$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account"]."` = '".$p["id"]."' ";
										}
									break;
									case "account_payable":
										$p = get_vendors_details( array( "id" => $_GET["budget"] ) );
										if( isset( $p[ "id" ] ) ){
											$subtitle = $p[ "name_of_vendor" ] . '<br />' . $subtitle;
											$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account"]."` = '".$p["id"]."' ";
										}
									break;
									case "cash_book":
										$acc_details = get_chart_of_accounts_details( array( "id" => $_GET["budget"] ) );
										if( isset( $acc_details["title"] ) ){
											$subtitle = $acc_details["title"] . '<br />' . $subtitle;
											$where1 .= " AND ( `".$sales_items->table_name."`.`".$sales_items->table_fields["account_type"]."` = '".$acc_details["id"]."' OR `".$sales_items->table_name."`.`".$sales_items->table_fields["account"]."` = '".$acc_details["id"]."' ) ";
										}
									break;
									case "inventory":
									case "revenue_category":
										$cat = get_items_categories();
										$cat_id = $_GET["budget"];
										if( isset( $cat[ $_GET["budget"] ] ) ){
											$subtitle = $cat[ $_GET["budget"] ] . '<br />' . $subtitle;
											$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account"]."` = '".$cat_id."' ";
										}
									break;
									default:
										$acc_details = get_chart_of_accounts_details( array( "id" => $_GET["budget"] ) );
										if( isset( $acc_details["title"] ) ){
											$subtitle = $acc_details["title"] . '<br />' . $subtitle;
											$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["account"]."` = '".$acc_details["id"]."' ";
										}
									break;
									}
								}else{
									$html_replacement1 = $p[ "id" ];
									$html_replacement_selector1 = "#report-period";
								}
							}
							
						break;
						default:
							$p = get_items_details( array( "id" => $_GET["operator"] ) );
							if( isset( $p[ "id" ] ) ){
								$t = $p[ "description" ];
								$where1 .= " AND `".$sales_items->table_name."`.`".$sales_items->table_fields["item_id"]."` = '".$p["id"]."' ";
							}
						break;
						}
						
						if( $t ){
							$title .= ": <strong>".ucwords( $t )."</strong>";
							$pen_val = $p[ "id" ];
						}
					}
					if( $pen_val ){
						
					}else{
						if( $pen_required ){
							$error_file = "select-pen-message.php";
							$where = "";
						}
					}
					
					if( $where2 )$where1 = " AND " . $where1;
					
					$where = " ( " . $where2 . $where1 . $extra_where . " ) ";
					
					$select .= ", `".$sales_items->table_name."`.`".$sales_items->table_fields['transaction_id']."` as 'transaction_id', `".$sales_items->table_name."`.`".$sales_items->table_fields['account']."` as 'account', SUM( `".$sales_items->table_name."`.`".$sales_items->table_fields['amount']."` ) as 'amount', `".$sales_items->table_name."`.`".$sales_items->table_fields['type']."` as 'type', `".$sales_items->table_name."`.`".$sales_items->table_fields['account_type']."` as 'account_type' , `".$sales_items->table_name."`.`".$sales_items->table_fields['comment']."` as 'comment', `".$sales_items->table_name."`.`".$sales_items->table_fields['extra_reference']."` as 'extra_reference' ";
					
					$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$sales_items->table_name."` ON `".$this->table_name."`.`id` = `".$sales_items->table_name."`.`".$sales_items->table_fields['transaction_id']."` WHERE ".$where." GROUP BY ".$group_items." ORDER BY `".$this->table_name."`.`modification_date` DESC " . $limit10;
					
					//echo $query; exit;
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 0,
						'tables' => array( $this->table_name, $sales_items->table_name ),
					);
					$sales = execute_sql_query($query_settings);
					
					if( $start_date && $end_date && $get_previous && ( $end_date - $start_date ) ){
						$interval = $end_date - $start_date;
						$test_interval = $interval / 3600;
						
						$nstart_date = 0;
						$nend_date = 0;
						
						if( $test_interval > 24 ){
							$test_interval = $test_interval / 24;
							if( $test_interval > 28 ){
								$test_interval = $test_interval / 28;
								if( $test_interval > 12 ){
									//years
									$nstart_date = mktime( 0, 0, 0, date( "n", $start_date ), date( "j", $start_date ), date( "Y", $start_date ) - 1 );
									$nend_date = $start_date - 360;
								}else{
									//months
									$nstart_date = mktime( 0, 0, 0, date( "n", $start_date ) - 1, date( "j", $start_date ), date( "Y", $start_date ) );
									$nend_date = $start_date - 360;
								}
							}
						}
						
						if( $nstart_date ){
							$where2 = " `".$this->table_name."`.`".$this->table_fields["date"]."` <= " . $nend_date . " AND `".$this->table_name."`.`".$this->table_fields["date"]."` >= " . $nstart_date;
							
							$where = " ( " . $where2 . $where1 . $extra_where . " ) ";
						
							$select .= ", `".$sales_items->table_name."`.`".$sales_items->table_fields['transaction_id']."` as 'transaction_id', `".$sales_items->table_name."`.`".$sales_items->table_fields['account']."` as 'account', SUM( `".$sales_items->table_name."`.`".$sales_items->table_fields['amount']."` ) as 'amount', `".$sales_items->table_name."`.`".$sales_items->table_fields['type']."` as 'type', `".$sales_items->table_name."`.`".$sales_items->table_fields['account_type']."` as 'account_type' ";
							
							$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$sales_items->table_name."` ON `".$this->table_name."`.`id` = `".$sales_items->table_name."`.`".$sales_items->table_fields['transaction_id']."` WHERE ".$where." GROUP BY ".$group_items." ORDER BY `".$this->table_name."`.`".$this->table_fields[ 'date' ]."` DESC " . $limit10;
							
							//echo $query; exit;
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'SELECT',
								'set_memcache' => 0,
								'tables' => array( $this->table_name, $sales_items->table_name ),
							);
							$previous_data["data"] = execute_sql_query($query_settings);
							
							$previous_data["start_date"] = $start_date;
							$previous_data["end_date"] = $end_date;
							
							$previous_data["nstart_date"] = $nstart_date;
							$previous_data["nend_date"] = $nend_date;
						
						}
					}
				}
				
				switch( $skip_joins ){
				case 1:
					$all_data = $sales;
				break;
				default:
					$all_data = $sales; //$all_data = array_merge( $farm_daily_record, $sales );
				break;
				}
				
				if( empty( $all_data ) ){
					
					$error_file = "error-message.php";
					$this->class_settings[ 'data' ][ "start_date" ] = $start_date;
					$this->class_settings[ 'data' ][ "end_date" ] = $end_date;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$error_file );
					$returning_html_data = $this->_get_html_view();
					
					return array(
						'do_not_reload_table' => 1,
						'html_replacement' => $returning_html_data,
						'html_replacement_selector' => "#data-table-section",
						'method_executed' => $this->class_settings['action_to_perform'],
						'status' => 'new-status',
					);
				}
				
				switch ( $this->class_settings['action_to_perform'] ){
				case "return_data":
				case "periodic_sales_report_data_only":
				case 'get_layers_performance_chart':
					return array(
						'report_title' => $title,
						'report_data' => $all_data,
					);
				break;
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-report' );
				$this->class_settings[ 'data' ][ 'report_subtitle' ] = $subtitle;
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'report_type' ] = $report_type;
				$this->class_settings[ 'data' ][ 'report_data' ] = $all_data;
				$this->class_settings[ 'data' ][ 'previous_report_data' ] = $previous_data;
				$this->class_settings[ 'data' ][ 'report_age' ] = isset( $report_age )?$report_age:"";
				$this->class_settings[ 'data' ][ 'days_filter' ] = isset( $days )?$days:7;
				$this->class_settings[ 'data' ][ 'selected_pen' ] = isset( $pen_val )?$pen_val:"";
				
				$returning_html_data = $this->_get_html_view();
				
				if( $html_replacement_selector1 && $html_replacement1 ){
					$_POST["id"] = $html_replacement1;
					$ch = new cChart_of_accounts();
					$ch->class_settings = $this->class_settings;
					$ch->class_settings["action_to_perform"] = 'check_for_subaccount';
					$option = $ch->chart_of_accounts();
					
					if( isset( $option["html_replacement"] ) ){
						$html_replacement1 = $option["html_replacement"];
					}
				}
				
			}else{
				//return error message
				if( ! $error_file )$error_file = "error-message.php";
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$error_file );
				$returning_html_data = $this->_get_html_view();
			}
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement_one' => $html_replacement1,
				'html_replacement_selector_one' => $html_replacement_selector1,
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#data-table-section",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
			);
		}
		
		private function _add_transaction_from_sales(){
			if( isset( $this->class_settings["data"] ) && is_array( $this->class_settings["data"] ) && ! empty( $this->class_settings["data"] ) ){
				$_POST["json"] = $this->class_settings["data"];
				
				$this->class_settings["data"];
				$this->class_settings["do_not_post_account_receivables"] = 1;
				return $this->_save_transaction_manifest();
			}
		}
		
		private function _save_transaction_manifest(){
			
			if( ! ( isset( $_POST["json"]["item"] ) && is_array( $_POST["json"]["item"] ) && ! empty( $_POST["json"]["item"] ) ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Accounts To Debit / Credit</h4>Please specify accounts to debit / credit first';
				return $err->error();
			}
			
			if( ! ( isset( $_POST["json"]["description"] ) && $_POST["json"]["description"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Description of Transaction</h4>Please describe the transaction that you wish to post';
				return $err->error();
			}
			
			if( ! ( isset( $_POST["json"]["credit"] ) && isset( $_POST["json"]["debit"] ) && $_POST["json"]["debit"] == $_POST["json"]["credit"] ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Non-matching Credit & Debit</h4>Please ensure that the credit & debit sides of the transaction matches';
				return $err->error();
			}
			
			$cart_items = $_POST["json"];
			
			$status = "";
			if( isset( $_POST["mod"] ) && $_POST["mod"] ){
				$status = $_POST["mod"];
			}
			
			switch( $status ){
			case "preview":
				if( ! ( isset( $_POST["json"]["source_account"] ) && $_POST["json"]["source_account"] ) ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Source Account</h4>Please specify the source account';
					return $err->error();
				}
				return $this->_preview_transaction_manifest();
			break;
			case "flat-draft":
			case "preview-draft":
				if( ! ( isset( $_POST["json"]["source_account"] ) && $_POST["json"]["source_account"] ) ){
					$err = new cError(010014);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Source Account</h4>Please specify the source account';
					return $err->error();
				}
				$cart_items["item"] = $this->_transform_items( $cart_items["item"], $cart_items["source_account"] );
			break;
			}
			
			if( isset( $cart_items["id"] ) && $cart_items["id"] ){
				$transaction_id = $cart_items["id"];
			}else{
				$transaction_id = get_new_id();
			}
			
			$debit_and_credit = new cDebit_and_credit();
			$debit_and_credit->class_settings = $this->class_settings;
			
			if( isset( $cart_items[ 'delete_existing' ] ) && $cart_items[ 'delete_existing' ] ){
				$query = "DELETE FROM `".$this->class_settings['database_name']."`.`".$this->table_name."` WHERE `id` = '".$cart_items[ 'delete_existing' ]."' ";
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'EXECUTE',
					'set_memcache' => 0,
					'tables' => array( $this->table_name ),
				);
				execute_sql_query($query_settings);
				
				$query = "DELETE FROM `".$this->class_settings['database_name']."`.`".$debit_and_credit->table_name."` WHERE `".$debit_and_credit->table_fields["transaction_id"]."` = '".$cart_items[ 'delete_existing' ]."' ";
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'EXECUTE',
					'set_memcache' => 0,
					'tables' => array( $debit_and_credit->table_name ),
				);
				execute_sql_query($query_settings);
			}
			/*
			if( ! ( isset( $this->class_settings["do_not_post_account_receivables"] ) && $this->class_settings["do_not_post_account_receivables"] ) ){
				$debit_and_credit->class_settings["post_account_receivables"] = 1;
			}
			*/
			$debit_and_credit->class_settings["transaction_id"] = $transaction_id;
			$debit_and_credit->class_settings["debit_and_credit"] = $cart_items["item"];
			
			$debit_and_credit->class_settings["action_to_perform"] = "save_debit_and_credit";
			$result = $debit_and_credit->debit_and_credit();
			
			$modal = 1;
			$js_function = '';
			switch( $status ){
			case "draft":
			case "flat-draft":
				$modal = 0;
				$js_function = 'nwTransactions.emptyCart';
			break;
			}
			
			
			if( $result ){
				$this->class_settings["transaction_id"] = $transaction_id;
				$this->class_settings["transactions"] = $cart_items;
				//$this->class_settings["action_to_perform"] = "save_transactions_and_return_receipt";
				
				if( $modal )
					$this->class_settings["action_to_perform"] = "view_invoice_app";
				else
					$this->class_settings["action_to_perform"] = "view_invoice_app1";
				
				$return = $this->_save_transactions_and_return_receipt();
				
				if( isset( $return[ 'html_replacement_selector' ] ) && $return[ 'html_replacement_selector' ] )
					$return[ 'html_replacement_selector' ] = "#modal-replacement-handle";
				
				if( $js_function )
					$return[ 'javascript_functions' ][] = $js_function;
				
				return $return;
			}
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>An Unknown Error Occured</h4>Transaction could not be saved';
			return $err->error();
		}
		
		private function _post_new_transaction(){
			switch ( $this->class_settings['action_to_perform'] ){
			case 'post_new_transaction':
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-post-transactions' );
				$html = $this->_get_html_view();
				
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => "#dash-board-main-content-area",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ),
				);
			break;
			}
			
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-post-transactions-basic' );
			$html = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ),
			);
			/*
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = "New Transaction";
			$this->class_settings[ 'data' ]["modal_dialog_style"] = " min-width:70%; ";
			
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ),
			);
			*/
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
			$this->_get_transactions();
		}
		
		private function _delete_transactions_manifest(){
			
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
				$return["html_removal"] = "#" . $return['deleted_record_id'] ;
					
				$return["html_replace_selector"] = "#manifest-" . $return['deleted_record_id'];
				$return["html_replace"] = '';
			}
			
			unset( $return["html"] );
			$return["status"] = "new-status";
			
			return $return;
		}
		
		private function _save_transactions_and_return_receipt(){
			
			if( ! ( isset( $this->class_settings["transactions"] ) && is_array( $this->class_settings["transactions"] ) ) ){
				return 0;
			}
			if( ! ( isset( $this->class_settings[ 'transaction_id' ] ) ) ){
				return 0;
			}
			
			$array_of_dataset = array();
			
			$new_record_id = $this->class_settings[ 'transaction_id' ];
			
			$ip_address = get_ip_address();
			$date = date("U");
			$tdate = date("U");
			
			$status = "draft";
			$store = "";
			
			if( isset( $this->class_settings["transactions"]["store"] ) ){
				$store = $this->class_settings["transactions"]["store"];
			}
			
			if( isset( $this->class_settings["transactions"]["date"] ) && $this->class_settings["transactions"]["date"] ){
				$tdate = convert_date_to_timestamp( $this->class_settings["transactions"]["date"] );
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
				
				$this->table_fields["date"] => $tdate,
				
				$this->table_fields["description"] => $this->class_settings["transactions"]["description"],
				$this->table_fields["reference_table"] => $this->class_settings["transactions"]["reference_table"],
				$this->table_fields["reference"] => isset( $this->class_settings["transactions"]["reference"] )?$this->class_settings["transactions"]["reference"]:"",
				
				$this->table_fields["status"] => $status,
				$this->table_fields["submitted_by"] => isset( $this->class_settings["transactions"]["submitted_by"] )?$this->class_settings["transactions"]["submitted_by"]:$this->class_settings[ "user_id" ],
				$this->table_fields["submitted_on"] => isset( $this->class_settings["transactions"]["submitted_on"] )?$this->class_settings["transactions"]["submitted_on"]:$date,
				
				$this->table_fields["extra_reference"] => isset( $this->class_settings["transactions"]["extra_reference"] )?$this->class_settings["transactions"]["extra_reference"]:"",
				
				$this->table_fields["store"] => $store,
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
				
				$saved = 1;
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_transactions_and_return_receipt_only':
				return $saved;
			break;
			}
			
			$_POST["id"] = $new_record_id;
			
			$this->class_settings["hide_buttons"] = 1;
			$return = $this->_view_invoice();
			$return["javascript_functions"][] = "nwTransactions.emptyCart";
			
			return $return;
		}
		
		private function _display_all_reports_full_view(){
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-reports-full-view' );
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'display_all_payables_full_view':
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
				
				$this->class_settings[ 'data' ][ 'report_type5' ] = get_vendors_financial_accounting_reports();
				$this->class_settings[ 'data' ][ 'selected_option5' ] = "vendors_transactions";
				
				$this->class_settings[ 'data' ][ 'report_title2' ] = "Vendors";
				$this->class_settings[ 'data' ][ 'report_type2' ] = get_vendors();
				$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-vendors";
				
			break;
			case 'display_all_receivables_full_view':
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
				
				$this->class_settings[ 'data' ][ 'report_type5' ] = get_customers_financial_accounting_reports();
				$this->class_settings[ 'data' ][ 'selected_option5' ] = "customers_transactions";
				
				$this->class_settings[ 'data' ][ 'report_title2' ] = "Customer";
				$this->class_settings[ 'data' ][ 'report_type2' ] = get_customers();
				$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-customers";
				
			break;
			case "display_all_cash_book_full_view":
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
				
				$this->class_settings[ 'data' ][ 'report_type5' ] = get_cash_book_financial_accounting_reports();
				$this->class_settings[ 'data' ][ 'selected_option5' ] = "cash_book_transactions";
				
				$this->class_settings[ 'data' ][ 'report_title2' ] = "Cash Account";
				$this->class_settings[ 'data' ][ 'report_type2' ] = get_cash_book_accounts();
				$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-accounts";
				
			break;
			default:
				
				$this->class_settings[ 'data' ][ 'report_type' ] = get_calendar_years();
				$this->class_settings[ 'data' ][ 'selected_option' ] = date("Y");
				
				$m = get_months_of_year();
				//$m["all-months"] = "All Months";
				
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_sales_report";
				
				$this->class_settings[ 'data' ][ 'report_type5' ] = get_financial_accounting_reports();
				$this->class_settings[ 'data' ][ 'selected_option5' ] = "income_statement";
				
				$this->class_settings[ 'data' ][ 'report_type1' ] = $m;
				$this->class_settings[ 'data' ][ 'selected_option1' ] = "all-months";
				
				$this->class_settings[ 'data' ][ 'report_title2' ] = "Account Type";
				$this->class_settings[ 'data' ][ 'report_type2' ] = get_first_level_accounts();
				unset( $this->class_settings[ 'data' ][ 'report_type2' ]["0"] );
			break;
			}
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
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
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$this->class_settings["transaction_id"] = $this->class_settings["current_record_id"];
			$e["event"] = $this->_get_transactions();
			
			$debit_and_credit = new cDebit_and_credit();
			$debit_and_credit->class_settings = $this->class_settings;
			$debit_and_credit->class_settings["action_to_perform"] = "get_specific_debit_and_credit";
			$e['debit_and_credit'] = $debit_and_credit->debit_and_credit();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/transactions-manifest.php' );
			$this->class_settings[ 'data' ] = $e;
			$this->class_settings[ 'data' ]["backend"] = 1;
			
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
			
			$this->class_settings[ 'data' ]["html_title"] = "Financial Transaction Manifest";
			
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
			
			$this->class_settings[ 'data' ]['title'] = "Manage Transactions";
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
				//$this->class_settings['form_heading_title'] = 'Modify Materials Utilized';
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
				'record_id' => isset( $returning_html_data[ 'record_id' ] )?$returning_html_data[ 'record_id' ]:"",
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
				
				//delete sales items
				$transactions_items = new cDebit_and_credit();
				$transactions_items->class_settings = $this->class_settings;
				$transactions_items->class_settings["action_to_perform"] = 'delete_items';
				
				//delete financial accounting transactions
				$d = explode(":::", $returning_html_data['deleted_record_id'] );
				
				$ref_tx = array();
				
				foreach( $d as $dd ){
					if( ! $dd )continue;
					
					$transactions_items->class_settings["transaction_id"] = $dd;
					$transactions_items->debit_and_credit();
					
					$settings = array(
						'cache_key' => $cache_key.'-'.$dd,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$cached_values = get_cache_for_special_values( $settings );
					
					if( isset( $cached_values[ 'reference' ] ) && $cached_values[ 'reference' ] && $cached_values[ 'reference_table' ] ){
						switch( $cached_values[ 'reference_table' ] ){
						case "production":
						case "expenditure_payment":
						case "expenditure":
						case "hotel_checkin":
						case "sales":
						case "payment":
							$ref_tx[ $cached_values[ 'reference_table' ] ][] = $cached_values[ 'reference' ];
						break;
						}
					}
				}
				
				switch ( $this->class_settings['action_to_perform'] ){
				case 'delete':
					if( ! empty( $ref_tx ) ){
						foreach( $ref_tx as $table => $ids ){
							switch( $table ){
							case "production":
							case "expenditure":
							case "expenditure_payment":
							case "hotel_checkin":
							case "sales":
							case "payment":
								
								unset( $_POST['ids'] );
								unset( $_POST['id'] );
								$_POST['ids'] = implode( ":::" , $ids );
								$_POST["mod"] = 'delete-' . md5( $table );
								
								$actual_name_of_class = 'c'.ucwords( $table );
								$module = new $actual_name_of_class();
								$module->class_settings = $this->class_settings;
								$module->class_settings["action_to_perform"] = 'delete_only';
								$module->$table();
								
							break;
							}
						}
					}
				break;
				}
				
				unset( $returning_html_data[ 'html' ] );
				$returning_html_data[ 'status' ] = "new-status";
				$returning_html_data[ 'html_removals' ] = array( "#invoice-container", "#".$returning_html_data['deleted_record_id'] );
				
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
				
				$err->class_that_triggered_error = 'ctransactions.php';
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
					$returning_html_data["event_details"] = $this->_get_transactions();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_transactions(){
			
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
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `created_by`, `modified_by`, `".$val."` as '".$key."'";
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
					
				}
				
				return $single_data;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			return 1;
		}
	}
?>