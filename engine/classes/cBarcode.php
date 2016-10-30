<?php
	/**
	 * barcode Class
	 *
	 * @used in  				barcode Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	barcode
	 */

	/*
	|--------------------------------------------------------------------------
	| barcode Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cBarcode{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'barcode';
		
		private $associated_cache_keys = array(
			'barcode',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		private $table_fields = array(
			'date' => 'barcode001',
			'quantity' => 'barcode002',
			'cost' => 'barcode003',
			'discount' => 'barcode004',
			'discount_type' => 'barcode005',
			
			'amount_due' => 'barcode006',
			'amount_paid' => 'barcode007',
			'balance' => 'barcode011',
			'payment_method' => 'barcode008',
			'customer' => 'barcode009',
			'store' => 'barcode010',
			
			'comment' => 'barcode012',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
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
	
		function barcode(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			//$this->class_settings[ 'project_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'generate_barcode':
				$returned_value = $this->_generate_barcode();
			break;
			case 'queue_barcode':
				$returned_value = $this->_queue_barcode();
			break;
			case 'bulk_queue_barcode':
				$returned_value = $this->_bulk_queue_barcode();
			break;
			case "display_barcode_print":
			case "print_barcode_queue":
			case 'display_barcode_print_queue2':
			case 'display_barcode_print_queue':
				$returned_value = $this->_display_barcode_print_queue();
			break;
			case 'empty_queue':
				$returned_value = $this->_empty_queue();
			break;
			case 'print_bulk_queue':
				$returned_value = $this->_print_bulk_queue();
			break;
			case 'delete_barcodes_from_queue':
			case 'update_quantities_in_queue':
				$returned_value = $this->_update_quantities_in_queue();
			break;
			}
			
			return $returned_value;
		}
		
		private function _update_quantities_in_queue(){
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'delete_barcodes_from_queue':
				$post_key = "barcode";
			break;
			case 'update_quantities_in_queue':
				$post_key = "quantity";
			break;
			}
			
			if( ! ( isset( $_POST[ $post_key ] ) && is_array( $_POST[ $post_key ] ) && ! empty( $_POST[ $post_key ] ) ) ){
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No Barcodes Selected</h4><p>No barcodes selected</p>';
				return $err->error();
			}
			$codes = $_POST[ $post_key ];
			
			$cache_key = $this->table_name;
			$ckey = $cache_key.'-'.$this->class_settings["user_id"].'-queue';
			
			$settings = array(
				'cache_key' => $ckey,
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			$cached = get_cache_for_special_values( $settings );
			
			if( ! ( is_array( $cached ) && ! empty( $cached ) ) ){
				$err = new cError(010014);
				$err->html_format = 2;
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Empty Barcode Print Queue</h4><p>You need to add items to your barcode print queue first</p>';
				return $err->error();
			}
			
			foreach( $codes as $barcode => $q ){
				if( isset( $cached[ $barcode ] ) ){
					switch( $post_key ){
					case  "barcode":
						unset( $cached[ $barcode ] );
					break;
					default:
						$cached[ $barcode ]["quantity"] = $q;
					break;
					}
				}
			}
			
			$settings = array(
				'cache_key' => $ckey,
				'directory_name' => $cache_key,
				'cache_values' => $cached,
				'permanent' => true,
			);
			
			if( empty( $cached ) ){
				clear_cache_for_special_values( $settings );
				$msg = 'Successful Deletion of All Barcodes';
			}else{
				set_cache_for_special_values( $settings );
				$msg = 'Successful Update of Queue';
			}
			//refresh view
			$this->class_settings["action_to_perform"] = 'display_barcode_print_queue2';
			$r = $this->_display_barcode_print_queue();
			
			$err = new cError(010011);
			$err->html_format = 2;
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>'.$msg.'</h4>';
			$return = $err->error();
			
			unset( $return["html"] );
			return array_merge( $r, $return );
		}
		
		private function _print_bulk_queue(){
			
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$cache_key = $this->table_name;
				$this->class_settings["cache_key"] = $cache_key.'-'.$_POST["id"];
				
				//$this->class_settings["action_to_perform"] = "print_barcode_queue";
				return $this->_display_barcode_print_queue();
			}else{
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid Batch ID, Could not Generate Barcodes';
				return $err->error();
			}
		}
		
		private function _empty_queue(){
			
			$cache_key = $this->table_name;
			$settings = array(
				'cache_key' => $cache_key.'-'.$this->class_settings["user_id"].'-queue',
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			clear_cache_for_special_values( $settings );
			
			$err = new cError(010011);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>Empty Barcode Print Queue</h4><p>Your barcode print queue has been successfully emptied</p><p>User Account: ' . $this->class_settings["user_full_name"] . '</p>';
			$return = $err->error();
			
			$return["html_replacement"] = $return["html"];
			unset( $return["html"] );
			$return[ 'html_replacement_selector' ] = "#modal-replacement-handle";
			$return[ 'status' ] = "new-status";
			
			return $return;
		}
		
		private function _display_barcode_print_queue(){
			$cache_key = $this->table_name;
			$ckey = $cache_key.'-'.$this->class_settings["user_id"].'-queue';
			
			$items = array();
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$ckey = $_POST["id"];
				$items[] = $_POST["id"];
			}
			
			$barcodes = 0;
			if( isset( $this->class_settings["barcodes"] ) && ! empty( $this->class_settings["barcodes"] ) && is_array( $this->class_settings["barcodes"] ) ){
				$items = $this->class_settings["barcodes"];
				$barcodes = 1;
			}
			
			$set_queue = 0;
			
			switch( $this->class_settings["action_to_perform"] ){
			case "display_barcode_print":
				if( empty( $items ) ){
					$err = new cError(010014);
					$err->html_format = 2;
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Item</h4><p>Please select the item that you wish to print the barcode</p>';
					return $err->error();
				}
				
				//get barcodes
				$cached = array();
				foreach( $items as $i ){
					if( $barcodes ){
						$item = $i;
					}else{
						$item = get_items_details( array( "id" => $i ) );
					}
					
					if( isset( $item["barcode"] ) && $item["barcode"] ){
						$dir = $this->class_settings["calling_page"] . "files/" . $this->table_name;
						create_folder( "", $dir, "" );
						
						$text = $item["barcode"];
						$filepath = $dir . "/" . $text . ".png";
						
						if( ! file_exists( $filepath ) ){
							$this->class_settings["item"]["barcode"] = $text;
							$r = $this->_generate_barcode();
							if( ! ( isset( $r["typ"] ) && $r["typ"] == "saved" ) ){
								return $r;
							}
						}
						
						$a = array();
						
						$cur = get_default_currency_settings();
						$currencies = get_currencies();
						if( isset( $this->class_settings["item"]["currency"] ) && $this->class_settings["item"]["currency"] ){
							$cur = $this->class_settings["item"]["currency"];
							if( isset( $currencies[ $this->class_settings["item"]["currency"] ] ) ){
								$cur = $currencies[ $this->class_settings["item"]["currency"] ];
							}
						}
						$sp = $cur .' '. number_format( doubleval( $item["selling_price"] ) , 2 );
						
						$a[ "id" ] = $text;
						$a[ "price" ] = $sp;
						$a[ "text" ] = $text;
						$a[ "quantity" ] = 1;
						$a[ "image" ] = $text . ".png";
						$a[ "category" ] = $item["category"];
						
						if( isset( $item["weight_in_grams"] ) )$a[ "weight" ] = $item["weight_in_grams"] . "g";
						
						$cached[ $item["barcode"] ] = $a;
					}
				}
				
				if( empty( $cached ) ){
					$err = new cError(010014);
					$err->html_format = 2;
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Barcodes to Print</h4><p>The Selected items have no barcodes to print</p>';
					return $err->error();
				}
				
				$this->class_settings[ 'data' ]["print_only_button"] = 1;
				$ckey = $cache_key.'-'.$this->class_settings["user_id"]."selected-barcodes";
				$set_queue = 1;
				
				$settings = array(
					'cache_key' => $ckey,
					'directory_name' => $cache_key,
					'cache_values' => $cached,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
			break;
			default:
				
				if( isset( $this->class_settings["cache_key"] ) && $this->class_settings["cache_key"] ){
					$ckey = $this->class_settings["cache_key"];
					$set_queue = 1;
				}
					
				$settings = array(
					'cache_key' => $ckey,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				$cached = get_cache_for_special_values( $settings );
				
				if( ! ( is_array( $cached ) && ! empty( $cached ) ) ){
					$err = new cError(010014);
					$err->html_format = 2;
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Empty Barcode Print Queue</h4><p>You need to add items to your barcode print queue first</p>';
					$return = $err->error();
					
					$return["html_replacement"] = $return["html"];
					return $return;
				}
			
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/barcode-print-queue.php' );
			$this->class_settings[ 'data' ]["barcodes"] = $cached;
			
			if( $set_queue ){
				$this->class_settings[ 'data' ]["print_queue"] = $ckey;
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "print_barcode_queue":
				$this->class_settings[ 'data' ]["print_mode"] = 1;
			break;
			}
			
			$html = $this->_get_html_view();
			
			switch( $this->class_settings["action_to_perform"] ){
			case "print_barcode_queue":
			case 'display_barcode_print_queue2':
				return array(
					'html_replacement' => $html,
					'html_replacement_selector' => "#modal-replacement-handle",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
				);
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = "Barcode Print Queue";
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
			);
			
		}
		
		private function _bulk_queue_barcode(){
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$cache_key = $this->table_name;
				
				$settings = array(
					'cache_key' => $cache_key.'-'.$_POST["id"],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				$cached_values = get_cache_for_special_values( $settings );
				
				if( is_array( $cached_values ) && ! empty( $cached_values ) ){
					$this->class_settings["cache_key"] = $cache_key.'-'.$_POST["id"];
					
					$settings = array(
						'cache_key' => $this->class_settings["cache_key"],
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					//clear_cache_for_special_values( $settings );
					
					foreach( $cached_values as $k => $sval ){
						$this->class_settings["item"] = $sval;
						$this->_queue_barcode();
						//sleep(1);
					}
					
					$return[ 'status' ] = "new-status";
					$return[ 'html_prepend' ] = "<a href='#' override-selected-record='".$_POST["id"]."' class='btn red custom-single-selected-record-button' action='?module=&action=barcode&todo=print_bulk_queue' >Print Barcodes</a>";
					$return[ 'html_prepend_selector' ] = "#excel-import-form-container";
					$return[ 'javascript_functions' ] = array( "set_function_click_event" );
					
					return $return;
				}
				
			}else{
				$err = new cError(010014);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid Batch ID, Could not Generate Barcodes';
				return $err->error();
			}
		}
		
		private function _queue_barcode(){
			if( isset( $this->class_settings["item"]["barcode"] ) && $this->class_settings["item"]["barcode"] ){
				
				// For demonstration purposes, get pararameters that are passed in through $_GET or set to the default value
				$dir = $this->class_settings["calling_page"] . "files/" . $this->table_name;
				create_folder( "", $dir, "" );
				
				$sp = 0;
				if( isset( $this->class_settings["item"]["selling_price"] ) && $this->class_settings["item"]["selling_price"] ){
					$sp = doubleval( $this->class_settings["item"]["selling_price"] );
				}
				
				$q = 1;
				$extra_title = '';
				if( isset( $this->class_settings["item"]["quantity"] ) && $this->class_settings["item"]["quantity"] ){
					$q = doubleval( $this->class_settings["item"]["quantity"] );
					$extra_title = 'Item Restocked & ';
				}
				
				$weight = 0;
				if( isset( $this->class_settings["item"]["weight_in_grams"] ) ){
					$weight = $this->class_settings["item"]["weight_in_grams"];
				}
				
				$length = 0;
				if( isset( $this->class_settings["item"]["length_of_chain"] ) ){
					$length = $this->class_settings["item"]["length_of_chain"];
				}
				/*
				if( $sp > 1000 ){
					if( $sp > 1000000 ){
						$sp = number_format( $sp / 1000000 , 2 ) . "M";
					}else{
						$sp = number_format( $sp / 1000 , 1 ) . "k";
					}
				}
				*/
				$category = '';
				if( isset( $this->class_settings["item"]["category"] ) && $this->class_settings["item"]["category"] ){
					$category = $this->class_settings["item"]["category"];
				}
				
				$cur = get_default_currency_settings();
				$currencies = get_currencies();
				if( isset( $this->class_settings["item"]["currency"] ) && $this->class_settings["item"]["currency"] ){
					$cur = $this->class_settings["item"]["currency"];
					if( isset( $currencies[ $this->class_settings["item"]["currency"] ] ) ){
						$cur = $currencies[ $this->class_settings["item"]["currency"] ];
					}
				}
				$sp = $cur .' '. number_format( $sp , 2 );
				
				$text = $this->class_settings["item"]["barcode"];
				$filepath = $dir . "/" . $text . ".png";
				
				if( ! file_exists( $filepath ) ){
					$r = $this->_generate_barcode();
					if( ! ( isset( $r["typ"] ) && $r["typ"] == "saved" ) ){
						return $r;
					}
				}
				
				$a = array();
				$a[ "id" ] = $text;
				$a[ "price" ] = $sp;
				$a[ "text" ] = $text;
				$a[ "quantity" ] = $q;
				$a[ "image" ] = $text . ".png";
				$a[ "category" ] = $category;
				
				if( $weight )$a[ "weight" ] = $weight . "g";
				if( $length )$a[ "length" ] = $length . " yard(s)";
				
				$cache_key = $this->table_name;
				$ckey = $cache_key.'-'.$this->class_settings["user_id"].'-queue';
				
				if( isset( $this->class_settings["cache_key"] ) && $this->class_settings["cache_key"] ){
					$ckey = $this->class_settings["cache_key"];
				}
				
				$settings = array(
					'cache_key' => $ckey,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				$cached = get_cache_for_special_values( $settings );
				
				if( ! is_array( $cached ) ){
					$cached = array();
				}
				$cached[ $a[ "id" ] ] = $a;
				
				$settings['cache_values'] = $cached;
				set_cache_for_special_values( $settings );
				
				$err = new cError(010011);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>'.$extra_title.'Successfully Added to Queue</h4><p>Barcode: <strong>'.$text.'</strong></p>';
				return $err->error();
			}
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>Invalid Barcode</h4><p>Please specify barcode</p>';
			return $err->error();
		}
		
		private function _generate_barcode(){
			if( isset( $this->class_settings["item"]["barcode"] ) && $this->class_settings["item"]["barcode"] ){
				
				// For demonstration purposes, get pararameters that are passed in through $_GET or set to the default value
				$dir = $this->class_settings["calling_page"] . "files/" . $this->table_name;
				create_folder( "", $dir, "" );
				
				//$text = "3210";
				$text = $this->class_settings["item"]["barcode"];
				$filepath = $dir . "/" . $text . ".png";
				
				$size = get_general_settings_value( array( "key" => "BARCODE SIZE", "table" => $this->table_name ) );
				if( ! $size )$size = "20";
				
				$orientation = "horizontal";
				
				$code_type = get_general_settings_value( array( "key" => "BARCODE TYPE", "table" => $this->table_name ) );
				if( ! $code_type )$code_type = "code25";
				
				$print = false;
				
				// This function call can be copied into your project and can be made from anywhere in your code
				$img = $this->_barcode( $filepath, $text, $size, $orientation, $code_type, $print );
				
				if( file_exists( $filepath ) ){
					$err = new cError(010011);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Barcode Successfully Generated</h4><p>Barcode: <strong>'.$text.'</strong></p>';
					return $err->error();
					//["typ"] == "saved"
				}
			}
			
			$err = new cError(010014);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>Failed to Generate Barcode</h4><p>Barcode failed to generate. Please try again</p>';
			return $err->error();
		}
		
		private function _barcode( $filepath="", $text="0", $size="20", $orientation="horizontal", $code_type="code128", $print=false ) {
			$code_string = "";
			// Translate the $text into barcode the correct $code_type
			if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
				$chksum = 104;
				// Must not change order of array elements as the checksum depends on the array's key to validate final code
				$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
				$code_keys = array_keys($code_array);
				$code_values = array_flip($code_keys);
				for ( $X = 1; $X <= strlen($text); $X++ ) {
					$activeKey = substr( $text, ($X-1), 1);
					$code_string .= $code_array[$activeKey];
					$chksum=($chksum + ($code_values[$activeKey] * $X));
				}
				$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

				$code_string = "211214" . $code_string . "2331112";
			} elseif ( strtolower($code_type) == "code128a" ) {
				$chksum = 103;
				$text = strtoupper($text); // Code 128A doesn't support lower case
				// Must not change order of array elements as the checksum depends on the array's key to validate final code
				$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
				$code_keys = array_keys($code_array);
				$code_values = array_flip($code_keys);
				for ( $X = 1; $X <= strlen($text); $X++ ) {
					$activeKey = substr( $text, ($X-1), 1);
					$code_string .= $code_array[$activeKey];
					$chksum=($chksum + ($code_values[$activeKey] * $X));
				}
				$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

				$code_string = "211412" . $code_string . "2331112";
			} elseif ( strtolower($code_type) == "code39" ) {
				$code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");

				// Convert to uppercase
				$upper_text = strtoupper($text);

				for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
					$code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
				}

				$code_string = "1211212111" . $code_string . "121121211";
			} elseif ( strtolower($code_type) == "code25" ) {
				$code_array1 = array("1","2","3","4","5","6","7","8","9","0");
				$code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");

				for ( $X = 1; $X <= strlen($text); $X++ ) {
					for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
						if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
							$temp[$X] = $code_array2[$Y];
					}
				}

				for ( $X=1; $X<=strlen($text); $X+=2 ) {
					if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
						$temp1 = explode( "-", $temp[$X] );
						$temp2 = explode( "-", $temp[($X + 1)] );
						for ( $Y = 0; $Y < count($temp1); $Y++ )
							$code_string .= $temp1[$Y] . $temp2[$Y];
					}
				}

				$code_string = "1111" . $code_string . "311";
			} elseif ( strtolower($code_type) == "codabar" ) {
				$code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
				$code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");

				// Convert to uppercase
				$upper_text = strtoupper($text);

				for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
					for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
						if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
							$code_string .= $code_array2[$Y] . "1";
					}
				}
				$code_string = "11221211" . $code_string . "1122121";
			}

			// Pad the edges of the barcode
			$code_length = 5;
			if ($print) {
				$text_height = 30;
			} else {
				$text_height = 0;
			}
			
			for ( $i=1; $i <= strlen($code_string); $i++ ){
				$code_length = $code_length + (integer)(substr($code_string,($i-1),1));
				}

			if ( strtolower($orientation) == "horizontal" ) {
				$img_width = $code_length;
				$img_height = $size;
			} else {
				$img_width = $size;
				$img_height = $code_length;
			}

			$image = imagecreate($img_width, $img_height + $text_height);
			$black = imagecolorallocate ($image, 0, 0, 0);
			$white = imagecolorallocate ($image, 255, 255, 255);

			imagefill( $image, 0, 0, $white );
			if ( $print ) {
				imagestring($image, 5, 31, $img_height, $text, $black );
			}

			$location = 2;
			for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
				$cur_size = $location + ( substr($code_string, ($position-1), 1) );
				if ( strtolower($orientation) == "horizontal" )
					imagefilledrectangle( $image, $location, 0, $cur_size, $img_height, ($position % 2 == 0 ? $white : $black) );
				else
					imagefilledrectangle( $image, 0, $location, $img_width, $cur_size, ($position % 2 == 0 ? $white : $black) );
				$location = $cur_size;
			}
			
			// Draw barcode to the screen or save in a file
			if ( $filepath == "" ) {
				header ('Content-type: image/png');
				imagepng($image);
				imagedestroy($image);
			} else {
				imagepng($image,$filepath);
				imagedestroy($image);		
			}
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
	}
?>