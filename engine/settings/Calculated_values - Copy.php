<?php 
	/**
	 * Calculated Values
	 *
	 * @used in  				classes/cForms.php, includes/ajax_server_json_data.php
	 * @created  				none
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Calculated Values
	|--------------------------------------------------------------------------
	|
	| Functions that define which functions to use in populating select combo 
	| boxes during form generation and dataTables population
	|
	*/
	function evaluate_calculated_value( $settings = array() ){
		
		$return = array(
			'value' => '',
			'class' => '',
		);
		
		if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'type' ] ) && isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ] ) && isset( $settings[ 'row_data' ] ) && is_array( $settings[ 'row_data' ] ) ){
			
			$var1 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 0 ];
			
            $extra = '';
			switch( $settings[ 'form_field_data' ][ 'calculations' ][ 'type' ] ){
			case 'production-ref-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_production_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$return["value"] = "<strong>".$sales_details['serial_num']."-".$sales_details['id']."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
					}
				}
				
				return $return;
			break;
			case 'expenditure-receipt-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ "serial_num" ] ) ){
					$return["value"] = "<strong>#".$settings[ 'row_data' ][ "serial_num" ]."</strong><br />".date("d-M-Y", doubleval( $settings[ 'row_data' ][ $var1 ] ) );
				}
				
				return $return;
			break;
			case 'sales-receipt-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_sales_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$return["value"] = "<strong>".$sales_details['serial_num']."-".$sales_details['id']."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
					}else{
						$sales_details = get_hotel_checkin_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
						
						if( isset( $sales_details['date'] ) && $sales_details['date'] ){
							$return["value"] = "<strong>".$sales_details['serial_num']."-".$sales_details['id']."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
						}
					}
				}
				
				return $return;
			break;
			case 'hotel-receipt-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_hotel_checkin_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
						
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$return["value"] = "<strong>".$sales_details['serial_num']."-".$sales_details['id']."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
					}
				}
				
				return $return;
			break;
			case 'reference':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$return["value"] = "<strong>".$settings[ 'row_data' ]['serial_num']."-".$settings[ 'row_data' ][ $var1 ]."</strong>";
				}
				
				return $return;
			break;
			case 'state-id':
				$var2 = '';
				$return = array(
					'value' => '',
					'class' => '',
				);
					
                if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] ) )
                    $var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ];
                    
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && isset( $settings[ 'row_data' ][ $var2 ] ) ){
					$id = $settings[ 'row_data' ][ $var1 ];
					$sid = $settings[ 'row_data' ][ $var2 ];
                    
                    $return["value"] = get_state_name( array( 'country_id' => $id, 'state_id' => $sid ) );
				}
				return $return;
			break;
			case 'cities-id':
				$var2 = '';
				$return = array(
					'value' => '',
					'class' => '',
				);
					
                if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] ) )
                    $var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ];
                    
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && isset( $settings[ 'row_data' ][ $var2 ] ) ){
					$sid = $settings[ 'row_data' ][ $var1 ];
					$cid = $settings[ 'row_data' ][ $var2 ];
                    
                    $return["value"] = get_city_name( array( 'city_id' => $cid, 'state_id' => $sid ) );
				}
				return $return;
			break;
            case 'site-user-id':
                //return $settings[ 'row_data' ][ $var1 ];
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$id = $settings[ 'row_data' ][ $var1 ];
                    $cached_values = get_site_user_details( array( 'id' => $id ) );
                    
                    if( isset( $cached_values['email'] ) && isset( $cached_values['registration_number'] ) ){
                        $return['value'] = $cached_values['email']."<br />{Reg No: ".$cached_values['registration_number']."}<br />{Acc No: ".$id."}";
                    }
				}
			break;
			case 'difference':
				if( isset( $settings[ 'row_data' ][ 'row_class' ] ) && $settings[ 'row_data' ][ 'row_class' ] == 'total-heading' ){
					return $return;
				}
				
				if( isset( $var1[ 'type' ] ) && isset( $var1[ 'variables' ] ) ){
						switch( $var1[ 'type' ] ){
						case "has_value":
							foreach( $var1[ 'variables' ] as $v ){
								if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
									$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
									if( $vv ){
										$return['value'] = $vv;
										break;
									}
								}
							}
						break;
						default:
							if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
								$return['value'] = format_and_convert_numbers( $settings[ 'row_data' ][ $var1 ] , 3 );
							}else{
								$return['value'] = 0;
							}
						break;
						}
				}else{
					if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
						$return['value'] = format_and_convert_numbers( $settings[ 'row_data' ][ $var1 ] , 3 );
					}else{
						$return['value'] = 0;
					}
				}
				
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 1 ] ) ){
					
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 1 ] as $var2 ){
						
						if( isset( $settings[ 'row_data' ][ $var2 ] ) && $settings[ 'row_data' ][ $var2 ] ){
							$return['value'] -= format_and_convert_numbers( $settings[ 'row_data' ][ $var2 ] , 3 );
						}
						
					}
					
				}	
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'negative_class' ] ) && $return['value'] < 0 )
					$return['class'] = $settings[ 'form_field_data' ][ 'calculations' ][ 'negative_class' ];
			break;
			case 'has_value':
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] ) ){
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] = $vv;
								break;
							}
						}
					}
				}
				//$return['value'] = 780;
			break;
			case 'addition':
				$return['value'] = 0;
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] ) ){
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] += $vv;
							}
						}
					}
				}
				
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'subtrend' ] ) ){
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'subtrend' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] -= $vv;
							}
						}
					}
				}
			break;
			case 'production-items-amount-due':
			case 'sales-items-amount-due':
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] ) ){
					$return['value'] = 1;
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] *= $vv;
							}
						}
					}
				}
				
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'discount' ] ) ){
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'discount' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] -= $vv;
							}
						}
					}
				}
				
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'extra_cost' ] ) ){
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'extra_cost' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] += $vv;
							}
						}
					}
				}
			break;
			case 'multiplication':
				$return['value'] = 1;
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] ) ){
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] *= $vv;
							}
						}
					}
				}
			break;
			case 'account-name':
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$account_type = '';
					if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] ) ){
						$var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ];
						$account_type = $settings[ 'row_data' ][ $var2 ];
					}
						
					
					$account = $settings[ 'row_data' ][ $var1 ];
					
					$title = "";
					switch( $account_type ){
					case "cost_of_goods_sold":
						$category = get_items_categories();
						if( isset( $category[ $account ] ) ){
							$title = "CGS: " . $category[ $account ];
						}
					break;
					case "inventory":
						$category = get_items_categories();
						if( isset( $category[ $account ] ) ){
							$title = "INV: " . $category[ $account ];
						}
					break;
					case "revenue_category":
						$category = get_items_categories();
						if( isset( $category[ $account ] ) ){
							$title = "REV: " . $category[ $account ];
						}
					break;
					case "accounts_receivable":
						$customer = get_customers();
						if( isset( $customer[ $account ] ) ){
							$title = $customer[ $account ];
						}
					break;
					case "cash_book": case "petty_cash": case "main_cash":
					case "bank6": case "bank7": case "bank8": case "bank9": case "bank10":
					case "bank5": case "bank4": case "bank3": case "bank2": case "bank1":
						$payment_methods = get_payment_method();
						if( isset( $payment_methods[ $account ] ) ){
							$title = $payment_methods[ $account ];
						}
					break;
					case "account_payable":
						$vendor = get_vendors();
						if( isset( $vendor[ $account ] ) ){
							$title = $vendor[ $account ];
						}
					break;
					}
					
					if( ! $title ){
						$acc = get_chart_of_accounts_details( array( "id" => $account ) );
						if( isset( $acc[ "title" ] ) && $acc[ "title" ] ){
							$title = $acc[ "title" ];
						}
					}
					
					$return["value"] = $title;
				}
				return $return;
			break;
			case 'debit-transactions':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$tx = get_debit_and_credit_details( array( "id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( is_array( $tx ) && ! empty( $tx ) ){
						$return["value"] = "";
						$customer = get_customers();
						$vendor = get_vendors();
						$category = get_items_categories();
						$payment_methods = get_payment_method();
						
						foreach( $tx as $txv ){
							if( $txv['type'] != "debit" )continue;
							
							$title = "";
							switch( $txv["account_type"] ){
							case "cost_of_goods_sold":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "CGS: " . $category[ $txv['account'] ];
								}
							break;
							case "inventory":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "INV: " . $category[ $txv['account'] ];
								}
							break;
							case "revenue_category":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "REV: " . $category[ $txv['account'] ];
								}
							break;
							case "accounts_receivable":
								if( isset( $customer[ $txv['account'] ] ) ){
									$title = $customer[ $txv['account'] ];
								}
							break;
							case "cash_book": case "petty_cash": case "main_cash":
							case "bank6": case "bank7": case "bank8": case "bank9": case "bank10":
							case "bank5": case "bank4": case "bank3": case "bank2": case "bank1":
								if( isset( $payment_methods[ $txv['account'] ] ) ){
									$title = $payment_methods[ $txv['account'] ];
								}
							break;
							case "account_payable":
								if( isset( $vendor[ $txv['account'] ] ) ){
									$title = $vendor[ $txv['account'] ];
								}
							break;
							}
							
							if( ! $title ){
								$acc = get_chart_of_accounts_details( array( "id" => $txv['account'] ) );
								if( isset( $acc[ "title" ] ) && $acc[ "title" ] ){
									$title = $acc[ "title" ];
								}
							}
							
							$return["value"] .= "<strong>" . $title . "</strong>: <span style='text-align:right; float:right;'>". format_and_convert_numbers( $txv["amount"] , 4 )."</span><br /><br />";
						}
					}
				}
				
				return $return;
			break;
			case 'credit-transactions':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$tx = get_debit_and_credit_details( array( "id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( is_array( $tx ) && ! empty( $tx ) ){
						$return["value"] = "";
						
						$customer = get_customers();
						$vendor = get_vendors();
						$category = get_items_categories();
						$payment_methods = get_payment_method();
						
						foreach( $tx as $txv ){
							if( $txv['type'] != "credit" )continue;
							
							$title = "";
							switch( $txv["account_type"] ){
							case "cost_of_goods_sold":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "CGS: " . $category[ $txv['account'] ];
								}
							break;
							case "inventory":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "INV: " . $category[ $txv['account'] ];
								}
							break;
							case "revenue_category":
								if( isset( $category[ $txv['account'] ] ) ){
									$title = "REV: " . $category[ $txv['account'] ];
								}
							break;
							case "accounts_receivable":
								if( isset( $customer[ $txv['account'] ] ) ){
									$title = $customer[ $txv['account'] ];
								}
							break;
							case "cash_book": case "petty_cash": case "main_cash":
							case "bank6": case "bank7": case "bank8": case "bank9": case "bank10":
							case "bank5": case "bank4": case "bank3": case "bank2": case "bank1":
								if( isset( $payment_methods[ $txv['account'] ] ) ){
									$title = $payment_methods[ $txv['account'] ];
								}
							break;
							case "account_payable":
								if( isset( $vendor[ $txv['account'] ] ) ){
									$title = $vendor[ $txv['account'] ];
								}
							break;
							}
							
							if( ! $title ){
								$acc = get_chart_of_accounts_details( array( "id" => $txv['account'] ) );
								if( isset( $acc[ "title" ] ) && $acc[ "title" ] ){
									$title = $acc[ "title" ];
								}
							}
							
							$return["value"] .= "<strong>" . $title . "</strong>: <span style='text-align:right; float:right;'>". format_and_convert_numbers( $txv["amount"] , 4 )."</span><br /><br />";
						}
					}
				}
				
				return $return;
			break;
			default:
				$return["value"] = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ];
			break;
			}
			
		}
		
		if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'form_field' ] ) ){
			switch( $settings[ 'form_field_data' ][ 'calculations' ][ 'form_field' ] ){
			case 'decimal':
				$return['value'] = format_and_convert_numbers( $return['value'] , 4 );
			break;
			case 'currency':
                $a = $return["value"];
                
                if( isset( $settings[ 'form_field_data' ]['default_currency_field'] ) && isset( $settings['row_data'][ $settings[ 'form_field_data' ]['default_currency_field'] ] ) && $settings['row_data'][  $settings[ 'form_field_data' ]['default_currency_field'] ] && $settings['row_data'][ $settings[ 'form_field_data' ]['default_currency_field'] ] != 'undefined' ){
                    $direction = 'from ' . trim( $settings['row_data'][ $settings[ 'form_field_data' ]['default_currency_field'] ] );
                    $a = convert_currency( $return["value"] , $direction , 1 );
                }
				$return["value"] = convert_currency( $a );
			break;
			}
		}
		
		return $return;
	}
	
	//Returns formatted value that would be displayed for each record of the monthly cash calls table
	function prepare_line_items_for_row_data( $settings = array() ){
		$dataset = array();
		
		$cache = array();
		$space_cache = array();
		$tmp_dataset = array();
		$tmp_count = 0;
		
		$modified_dataset = array();
		
		$code_properties = array();
		
		$table = $settings[ 'table' ];
		$func = $settings[ 'table' ];
		
		$clear_row_values = 0;
		
		if( isset( $settings[ 'dataset' ] ) && is_array( $settings[ 'dataset' ] ) && isset($settings[ 'dataset' ][0]) ){
			$dataset = $settings[ 'dataset' ];
			foreach( $dataset[0] as $k => $v ){
				$space_cache[ $k ] = '';
			}
			$space_cache['row_class'] = 'space';
			$d = $space_cache;
			$d['id'] = '9';
			
			$dataset[] = $d;
			
			$insert_total_row = false;
			
			$previous_group_parent = '';
			
			$total_row_index = array();
			
			foreach( $dataset as $key => & $data ){
				
				if( $key == 0 )$clear_row_values = 1;
				
				$a_heading = 0;
				$terminate_current = 0;
				
				if( ! isset( $cache[ $data['code'] ] ) ){
					$cache[ $data['code'] ] = get_codes_id_and_parent( $data );
					//$cache[ $dataset[ $key + 1 ]['code'] ]['data'] = $data;
				}
				if( isset( $dataset[ $key + 1 ] ) ){
					$cache[ $dataset[ $key + 1 ]['code'] ] = get_codes_id_and_parent( $dataset[ $key + 1 ] );
					//$cache[ $dataset[ $key + 1 ]['code'] ]['data'] = $dataset[ $key + 1 ];
				}
				
				//get all parents
				
				$parents = array();
				$p = explode('.', $cache[ $data['code'] ][ 'parent' ] );
				if( empty( $p ) )$p = array( $cache[ $data['code'] ][ 'parent' ] );
				foreach( $p as $pp ){
					if( empty( $parents ) )$parents[] = implode('.', $parents ) . $pp;
					else $parents[] = implode('.', $parents ) .'.'. $pp;
				}
				
				if( ! empty( $parents ) ){
					foreach( $data as $k => $v ){
						$cc = $v;
						$c1 = 0;
						switch( $k ){
						case 'description':
						case 'id':
						case 'code':
						case 'remark':
						case 'modification_date':
						case 'creation_date':
						case 'record_status':
						break;
						default:
							$cc = doubleval( $v );
							$c1 = 1;
						break;
						}
						foreach( $parents as $pp ){
							if( isset( $cache[ $pp ][ 'data' ][ $k ] ) && $c1 )
								$cache[ $pp ][ 'data' ][ $k ] += $cc;
							else
								$cache[ $pp ][ 'data' ][ $k ] = $cc;
						}
					}
				}
				
				
				//next row
				if( isset( $dataset[ $key + 1 ] ) && isset( $cache[ $dataset[ $key + 1 ]['code'] ][ 'parent' ] ) ){
					if( $cache[ $data['code'] ][ 'parent' ] == $cache[ $dataset[ $key + 1 ]['code'] ][ 'parent' ] ){
						
					}else{
						//different parents - terminate previous row
						if( isset( $dataset[ $key - 1 ] ) && $cache[ $dataset[ $key + 1 ]['code'] ][ 'parent' ] == $data['code'] ){
							if( $cache[ $dataset[ $key - 1 ]['code'] ][ 'parent' ] == $cache[ $data['code'] ]['parent'] ){
								
								$clear_row_values = 1;
								
								$tmp_dataset = $dataset[ $key - 1 ];
								/*
								$p_code = $cache[ $dataset[ $key - 1 ]['code'] ][ 'parent' ];
								if( isset( $cache[ $p_code ][ 'data' ] ) )
									$tmp_dataset = $cache[ $p_code ][ 'data' ];
								*/
								//$tmp_dataset = $cache[ $dataset[ $key - 1 ]['code'] ][ 'data' ];
								
								$tmp_dataset['description'] = '<strong><small>TOTAL '.$tmp_dataset['code'].' </small></strong>';
								$tmp_dataset['code'] = '&nbsp;';
								$tmp_dataset['row_class'] = 'total';
								$modified_dataset[] = $tmp_dataset;
								
								$modified_dataset[] = $space_cache;
								
							}
						}else{
							if( isset( $dataset[ $key - 1 ] ) && $cache[ $dataset[ $key - 1 ]['code'] ][ 'parent' ] == $cache[ $data['code'] ]['parent'] ){
								$terminate_current = 1;
								
								$modified_dataset[] = $data;
								
								$tmp_dataset = $data;
								$p_code = $cache[ $data['code'] ][ 'parent' ];
								if( isset( $cache[ $p_code ]['data'] ) ){
									$tmp_dataset = $cache[ $p_code ]['data'];
								}
								
								$desc = '';
								if( isset( $cache[ $p_code ][ 'description' ] ) ){
									$desc = $cache[ $p_code ][ 'description' ];
								}
								
								$tmp_dataset['description'] = '<strong><small>TOTAL '.$p_code.' </small></strong>';
								$tmp_dataset['code'] = '&nbsp;';
								$tmp_dataset['row_class'] = 'total';
								
								$modified_dataset[] = $tmp_dataset;
								
								$modified_dataset[] = $space_cache;
								
								//recursive function
								$parent_code = $cache[ $data['code'] ][ 'parent' ];
								if( isset( $cache[ $parent_code ][ 'parent' ] ) && $cache[ $parent_code ][ 'parent' ] != $cache[ $dataset[ $key + 1 ]['code'] ][ 'parent' ] ){
									
									$tmp_dataset = $data;
									$desc = '';
									
									$p_code = $cache[ $parent_code ][ 'parent' ];
									if( isset( $cache[ $p_code ]['data'] ) ){
										$tmp_dataset = $cache[ $p_code ]['data'];
									}
									
									if( isset( $cache[ $cache[ $parent_code ][ 'parent' ] ][ 'description' ] ) )
										$desc = $cache[ $cache[ $parent_code ][ 'parent' ] ][ 'description' ];
									
									$tmp_dataset['code'] = '&nbsp;';
									$tmp_dataset['description'] = '<strong><small>TOTAL '. $cache[ $parent_code ][ 'parent' ] .' </small></strong>';
									$tmp_dataset['row_class'] = 'total';
									$modified_dataset[] = $tmp_dataset;
									
									$modified_dataset[] = $space_cache;
									
								}
							}
						}
					}
				}else{
					//last row
				}
				
				if( ! $terminate_current ){
					if( $clear_row_values ){
						$clear_row_values = 0;
						foreach( $data as $k => & $v ){
							switch( $k ){
							case 'description':
							case 'id':
							case 'code':
							case 'remark':
							case 'modification_date':
							case 'creation_date':
							case 'record_status':
							break;
							default:
								$v = '';
							break;
							}
						}
						$data['row_class'] = 'total-heading';
					}
					
					$modified_dataset[] = $data;
				}else{
					$clear_row_values = 1;
				}
				
			}
		
		}
		//print_r($dataset);
		//print_r($cache);
		//exit;
		return $modified_dataset;
	}
	
	function get_codes_id_and_parent( $data ){
		$cache = array();
		$cache[ 'codes' ] = explode( '.', $data['code'] );
		$cache[ 'description' ] = $data['description'];
		
		$i = count( $cache[ 'codes' ] );
		if( isset( $cache[ 'codes' ][ $i - 1 ] ) ){
			$c = $cache[ 'codes' ];
			unset( $c[ $i - 1 ] );
			$cache[ 'parent' ] = implode( '.', $c );
		}else{
			$cache[ 'parent' ] = $data['code'];
		}
		return $cache;
	}
	
?>