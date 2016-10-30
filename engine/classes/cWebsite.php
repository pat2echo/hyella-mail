<?php
	/**
	 * website Class
	 *
	 * @used in  				website Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	website
	 */

	/*
	|--------------------------------------------------------------------------
	| website Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cWebsite{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'website';
        
		private $page_title = 'Perspectiva';
        
		private $page_keywords = 'Perspectiva';
        
		private $page_description = 'Perspectiva';
		
		private $table_fields = array();
		
		function __construct(){
			
		}
	
		function website(){
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
			case 'setup_website':
				$returned_value = $this->_setup_website();
			break;
			case 'setup_store':
				$returned_value = $this->_setup_store();
			break;
			case 'homepage':
			case 'approve':
			case 'decline':
			case 'terms_of_service':
			case 'newsletter':
			default:
				$returned_value = $this->_homepage();
			break;
			}
			
			return $returned_value;
		}
		
		private function _get_general_settings(){
			return get_from_cached( array( 'cache_key' => 'general_settings' ) );
		}
		
		private function _homepage(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$filename = str_replace( '_', '-', $this->class_settings['action_to_perform'] );
			
            $do_not_use_cache = true;
            $cache_key = 'website-cache-1-'.$filename;
            $country_id = '1';
            if( defined( 'SELECTED_COUNTRY_ID' ) ){
                $country_id = SELECTED_COUNTRY_ID;
            }
            $csettings = array(
                'cache_key' => $cache_key.'-'.$country_id,
                'cache_time' => 'load-time',
            );
            //clear_cache_for_special_values( $csettings );
            //$cached_data = get_cache_for_special_values( $csettings );
            if( isset( $cached_data ) && is_array( $cached_data ) && ! empty( $cached_data ) ){
                $script_compiler->class_settings[ 'data' ] = $cached_data;
                $do_not_use_cache = false;
            }
            /*  
            if( isset( $_GET['s'] ) && isset( $_GET['categories'] ) && $_GET['categories'] && $this->class_settings['action_to_perform'] == 'all_products' )
                $do_not_use_cache = true;
            */
            
			switch ( $this->class_settings['action_to_perform'] ){
            case 'print-invoice':
				$this->class_settings['do_not_show_header'] = 1;
				$do_not_use_cache = false;
				
				if( isset( $_GET["record_id"] ) && $_GET["record_id"] ){
					$_POST["id"] = $_GET["record_id"];
					$this->class_settings["hide_buttons"] = 1;
					$this->class_settings["show_print_button"] = 1;
					
					if( isset( $_GET["pos"] ) ){
						$this->class_settings["show_small_invoice"] = 1;
					}
					
					$sales = new cSales();
					$sales->class_settings = $this->class_settings;
					$sales->class_settings[ 'action_to_perform' ] = 'view_invoice_app';
					$e = $sales->sales();
					$script_compiler->class_settings[ 'data' ]['html'] = isset( $e[ 'html_replacement' ] )?$e[ 'html_replacement' ]:"";
					
				}
			break;
            case 'print-manifest':
				$this->class_settings['do_not_show_header'] = 1;
				$do_not_use_cache = false;
				
				if( isset( $_GET["record_id"] ) && $_GET["record_id"] ){
					$_POST["id"] = $_GET["record_id"];
					$this->class_settings["hide_buttons"] = 1;
					$this->class_settings["show_print_button"] = 1;
					
					$production = new cProduction();
					$production->class_settings = $this->class_settings;
					$production->class_settings[ 'action_to_perform' ] = 'view_invoice_app';
					$e = $production->production();
					$script_compiler->class_settings[ 'data' ]['html'] = isset( $e[ 'html_replacement' ] )?$e[ 'html_replacement' ]:"";	
				}
			break;
             case 'print-expenditure-manifest':
				$this->class_settings['do_not_show_header'] = 1;
				$do_not_use_cache = false;
				
				if( isset( $_GET["record_id"] ) && $_GET["record_id"] ){
					$_POST["id"] = $_GET["record_id"];
					$this->class_settings["hide_buttons"] = 1;
					$this->class_settings["show_print_button"] = 1;
					
					$expenditure = new cExpenditure();
					$expenditure->class_settings = $this->class_settings;
					$expenditure->class_settings[ 'action_to_perform' ] = 'view_invoice_app';
					$e = $expenditure->expenditure();
					$script_compiler->class_settings[ 'data' ]['html'] = isset( $e[ 'html_replacement' ] )?$e[ 'html_replacement' ]:"";	
				}
			break;
			case "print-barcode":
				$this->class_settings['do_not_show_header'] = 1;
				$this->class_settings['do_not_show_head_tag'] = 1;
				$do_not_use_cache = false;
				
				if( isset( $_GET["record_id"] ) && $_GET["record_id"] ){
					$_POST["id"] = $_GET["record_id"];
				}
				
				$barcode = new cBarcode();
				$barcode->class_settings = $this->class_settings;
				$barcode->class_settings["action_to_perform"] = "print_barcode_queue";
				$e = $barcode->barcode();
				
				$script_compiler->class_settings[ 'data' ]['html'] = ( isset( $e[ 'html_replacement' ] )?$e[ 'html_replacement' ]:"" );	
			break;
			case "print-appraisal":
				$this->class_settings['do_not_show_header'] = 1;
				$this->class_settings['do_not_show_head_tag'] = 1;
				$do_not_use_cache = false;
				
				if( isset( $_GET["record_id"] ) && $_GET["record_id"] ){
					$_POST["id"] = $_GET["record_id"];
					
					$this->class_settings["hide_buttons"] = 1;
					$this->class_settings["show_print_button"] = 1;
					
					$barcode = new cAppraisal();
					$barcode->class_settings = $this->class_settings;
					$barcode->class_settings["action_to_perform"] = "view_invoice_app";
					$e = $barcode->appraisal();
					
					$script_compiler->class_settings[ 'data' ]['html'] = isset( $e[ 'html_replacement' ] )?$e[ 'html_replacement' ]:"";	
				}
			break;
            case "print-hotel-invoice":
				$this->class_settings['do_not_show_header'] = 1;
				$do_not_use_cache = false;
				
				if( isset( $_GET["record_id"] ) && $_GET["record_id"] ){
					$_POST["id"] = $_GET["record_id"];
					$this->class_settings["hide_buttons"] = 1;
					$this->class_settings["show_print_button"] = 1;
					
					$hotel_checkin = new cHotel_checkin();
					$hotel_checkin->class_settings = $this->class_settings;
					$hotel_checkin->class_settings[ 'action_to_perform' ] = 'view_invoice_app';
					$e = $hotel_checkin->hotel_checkin();
					$script_compiler->class_settings[ 'data' ]['html'] = isset( $e[ 'html_replacement' ] )?$e[ 'html_replacement' ]:"";
					
				}
                
			break;
            case 'print-transaction':
				$this->class_settings['do_not_show_header'] = 1;
				$do_not_use_cache = false;
				
				if( isset( $_GET["record_id"] ) && $_GET["record_id"] ){
					$_POST["id"] = $_GET["record_id"];
					$this->class_settings["hide_buttons"] = 1;
					$this->class_settings["show_print_button"] = 1;
					
					$production = new cTransactions();
					$production->class_settings = $this->class_settings;
					$production->class_settings[ 'action_to_perform' ] = 'view_invoice_app';
					$e = $production->transactions();
					$script_compiler->class_settings[ 'data' ]['html'] = isset( $e[ 'html_replacement' ] )?$e[ 'html_replacement' ]:"";
					
				}
                
			break;
			default:
				exit;
			break;
			}
			
            if( $do_not_use_cache ){
                //set cache
                $csettings[ 'cache_values' ] = $script_compiler->class_settings[ 'data' ];
                set_cache_for_special_values( $csettings );
            }
            
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename.'.php' );
			
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$additional_html = $script_compiler->script_compiler();
			
			if( ! $additional_html ){
				$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/error-404.php' );
				$additional_html = $script_compiler->script_compiler();
			}
            
			$returning = $this->_setup_website();
			$returning['html'] = $additional_html;
			
			return $returning;
		}
		
		private function _setup_website(){
			
			if( isset( $this->class_settings[ 'bundle-name' ] ) ){
				$this->class_settings[ 'script_name' ] = $this->class_settings[ 'bundle-name' ];
				$this->class_settings[ 'stylesheet' ] = $this->class_settings[ 'bundle-name' ];
			}else{
				$this->class_settings[ 'script_name' ] = $this->class_settings[ 'action_to_perform' ];
				$this->class_settings[ 'stylesheet' ] = $this->class_settings[ 'action_to_perform' ];
			}
			
			$this->class_settings[ 'js_lib' ] = $this->get_js_lib();
			$this->class_settings[ 'js' ] = $this->get_js();
			$this->class_settings[ 'css' ] = $this->get_css();
			
			$this->class_settings[ 'html' ] = array( 'html-files/static-markup.php' );
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
            
			if( ! isset( $this->class_settings['do_not_show_head_tag'] ) ){
				
				$script_compiler->class_settings[ 'action_to_perform' ] = 'compile_scripts';
				
				$script_data = $script_compiler->script_compiler();
				
				if( isset( $script_data[ 'js_file' ] ) ){
					$returning[ 'javascript' ] = $script_data[ 'js_file' ];
				}
				
				if( isset( $script_data[ 'css_file' ] ) ){
					$returning[ 'stylesheet' ] = $script_data[ 'css_file' ];
				}
				
				if( isset( $script_data[ 'html_markup' ] ) ){
					$returning[ 'html_markup' ] = $script_data[ 'html_markup' ];
				}
				
				$script_compiler->class_settings[ 'data' ]['title'] = $this->page_title;
				$script_compiler->class_settings[ 'data' ]['keywords'] = $this->page_keywords;
				$script_compiler->class_settings[ 'data' ]['description'] = $this->page_description;
				$script_compiler->class_settings[ 'data' ]['pagepointer'] = $this->class_settings['calling_page'];            
			
				$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/html-head-tag.php' );
				$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
				$returning[ 'html_head_tag' ] = $script_compiler->script_compiler();
			}
			
			$script_compiler->class_settings[ 'data' ] = array( 
				'pagepointer' => $this->class_settings['calling_page'],
				'menu' => get_website_menu_items( array( 'top_menu_bar_right' , 'top_menu_bar_left' ) ),
                'title_heading' => $this->page_title,
			);
            
			if( ! ( isset( $this->class_settings['do_not_show_header'] ) && $this->class_settings['do_not_show_header'] ) ){
				$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/header-website.php' );
				$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
				$returning[ 'html_header' ] = $script_compiler->script_compiler();
			}
			
			if( ! ( isset( $this->class_settings['do_not_show_header'] ) && $this->class_settings['do_not_show_header'] ) ){
				$script_compiler->class_settings[ 'data' ] = array( 
					'pagepointer' => $this->class_settings['calling_page'],
					'menu' => get_website_menu_items( array( 'footer_menu_item_1' , 'footer_menu_item_2' , 'footer_menu_item_3' , 'footer_menu_item_4' , 'footer_menu_item_5', 'bottom_menu_bar_left' ) ),
				);
				$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/footer-website.php' );
				$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
				$returning[ 'html_footer' ] = $script_compiler->script_compiler();
			}
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
		}
		
		private function get_js_lib(){
            
			$js_lib[] = 'assets/plugins/jquery-1.10.2.min.js';
            $js_lib[] = 'js/fileuploader.js';
			$js_lib[] = 'assets/plugins/jquery-migrate-1.2.1.min.js';
			$js_lib[] = 'assets/plugins/bootstrap/js/bootstrap.min.js';
			$js_lib[] = 'assets/plugins/hover-dropdown.js';
			$js_lib[] = 'assets/plugins/back-to-top.js';
			$js_lib[] = 'assets/plugins/fancybox/source/jquery.fancybox.pack.js';
			$js_lib[] = 'assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js';
			$js_lib[] = 'assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js';
			$js_lib[] = 'assets/plugins/bxslider/jquery.bxslider.min.js';
			$js_lib[] = 'assets/scripts/app.js';
			$js_lib[] = 'assets/scripts/index.js';
			
			$js_lib[] = 'my_js/custom.plugin.js';
			$js_lib[] = 'my_js/host-processing-script.js';
            
			$js_lib[] = 'js/ajaxfileupload.js';
			
			$js_lib[] = 'js/amplify.min.js';
			
			if( isset( $this->class_settings[ 'js_lib' ] ) && is_array($this->class_settings[ 'js_lib' ]) ){
				foreach( $this->class_settings[ 'js_lib' ] as $val ){
					$js_lib[] = $val;
				}
			}
			
			return $js_lib;
		}
		
		private function get_js(){
			
			//$js[] = 'my_js/ajax-requests.js';
			//$js[] = 'my_js/form-handler.js';
			//$js[] = 'my_js/navigate.js';
            /*
			$js[] = 'my_js/custom/website.js';
			*/
			if( isset( $this->class_settings[ 'js' ] ) && is_array($this->class_settings[ 'js' ]) ){
				foreach( $this->class_settings[ 'js' ] as $val ){
					$js[] = $val;
				}
			}
			
            //$js[] = 'my_js/custom/select.js';
            
			if( isset( $js ) )
				return $js;
		}
		
		private function get_css(){
			$css[] = 'assets/plugins/font-awesome/css/font-awesome.min.css';
			$css[] = 'assets/plugins/bootstrap/css/bootstrap.min.css';
			$css[] = 'assets/plugins/fancybox/source/jquery.fancybox.css';
			$css[] = 'assets/plugins/revolution_slider/css/rs-style.css';
			$css[] = 'assets/plugins/revolution_slider/rs-plugin/css/settings.css';
			$css[] = 'assets/plugins/bxslider/jquery.bxslider.css';
            
			$css[] = 'assets/css/style-metronic.css';
			$css[] = 'assets/css/style.css';
			//$css[] = 'assets/css/themes/blue.css';
			$css[] = 'assets/css/style-responsive.css';
			$css[] = 'assets/css/custom.css';
			$css[] = 'css/form.css';
            
			if( isset( $this->class_settings[ 'css' ] ) && is_array($this->class_settings[ 'css' ]) ){
				foreach( $this->class_settings[ 'css' ] as $val ){
					$css[] = $val;
				}
			}
			
			return $css;
		}
		
	}
?>