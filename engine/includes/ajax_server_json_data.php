<?php
	/**
	 * Gas Helix Prepare JSON dataset for Populating DataTables File
	 *
	 * @used in  				ajax_server/*.php
	 * @created  				none
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Gas Helix Prepare JSON dataset for Populating DataTables File
	|--------------------------------------------------------------------------
	|
	| Read array of database columns which would be sent back to DataTables.
	|
	*/
	
	/* Total data set length */
	$iTotal = count($arr);
	
	if(!isset($_GET['sEcho']))$_GET['sEcho']=1;
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	//Get Application Users Names [F. Last Name]
	
	//write_file('','ds.txt',$id2val->txt);
	if(isset($_GET['iDisplayStart']))$sn = $_GET['iDisplayStart'];
	else $sn = 0;
	
	//GET ARRAY OF VALUES FOR FORM LABELS
	$func = $table;
    if( isset( $table_real ) )$func = $table_real;
    
	if(function_exists($func))
		$form_label = $func();
	else
		$form_label = array();
	
	//CHECK FOR CONTROLLER TABLE
	if( isset( $field_controller_function ) ){
		if( function_exists( $field_controller_function ) ){
			$form_label = $field_controller_function();
		}
	}
	
	//CHECK WHETHER OR NOT TO DISPLAY TOTAL FOR SUMS
	if(isset($json_settings['show_total']) && $json_settings['show_total']){
		//Display Further Details
		if(isset($json_settings['show_total']) && $json_settings['show_total'] && isset($json_settings['show_total_function']) && $json_settings['show_total_function']){
			//Set Fixed First Row for Table
			$output['aaData'][0] = array();
		}
	}
	
	$record_id = '';
	
	$special_summed_values = array();
	
	$summed_values = array();
	$sub_summed_values = array();
	
	$summed_values_units = array();
	$summed_values_form_type = array();
	
	$special_table_formatting_top_row = array();
	$special_table_formatting_bottom_summed_values = array();
	
	for($count_records=0; $count_records<count($arr); $count_records++ )
	{
		$aRow = $arr[$count_records];
		
		$record_id = '<span style="font-size:0px; display:none;>'.$aRow['id'].'</span>';
		
		$skip_loop = 1;
		
		$row = array();
		
		if($skip_loop){
			//CHECK WHETHER OR NOT TO DISPLAY DETAILS
			if($json_settings['show_details']){
				$row[0] = '';
			
				//$returning_html_data = '<img src="'.$pagepointer.'images/icons/details_open.png" class="datatables-details" >';
				$returning_html_data = '<button href="#" class="datatables-details btn-xs btn '.$future_request.' remove-before-export" title="Click to View Details" jid="'.$aRow['id'].'" ><i style="font-size:10px;" class=" icon-chevron-down"></i></button>';
			
				$returning_html_data .= '<div style="display:none;"><div id="main-details-table-'.$aRow['id'].'"><table id="the-main-details-table-'.$aRow['id'].'" class="main-details-table table" style="max-width:920px; width:99%;"><tbody>';
				
			}
			
			$DT_RowClass = '';
			
			//for ( $i=0 ; $i<count($aColumns) ; $i++ )
			foreach ( $aColumns as $i => $val_i)
			{
				
				$aRow[ $aColumns[$i] ] = iconv( "UTF-8", "ASCII//IGNORE", $aRow[ $aColumns[$i] ] );
				
				//Get Field Info
				$field = array(
					'form_field' => '',
					'display_position' => '',
					'field_label' => 'undefined',
				);
				if( isset( $form_label[ $aColumns[$i] ] ) && is_array( $form_label[ $aColumns[$i] ] ) ){
					$field = $form_label[ $aColumns[$i] ];
				}
				
				$show_field = false;
				
				switch($aColumns[$i]){
				case 'created_by':
				case 'modified_by':
					$show_field = true;
					$field[ 'field_label' ] = ucwords( str_replace( '_', ' ', $aColumns[$i] ) );
					$field[ 'form_field' ] = 'select';
					$field[ 'form_field_options' ] = 'get_users_names';
                    
					$field[ 'form_field' ] = 'calculated';
                    $field[ 'calculations' ] = array(
                        'type' => 'site-user-id',
                        'form_field' => 'text',
                        'variables' => array( array( $aColumns[$i] ) ),
                    );
				break;
				case 'creation_date':
				case 'modification_date':
					$show_field = true;
					$field[ 'field_label' ] = ucwords( str_replace( '_', ' ', $aColumns[$i] ) );
					$field[ 'form_field' ] = 'date';
				break;
				}
				
				switch($aColumns[$i]){
				case "id":
					//CHECK WHETHER OR NOT TO DISPLAY SERIAL NUMBER
					if($json_settings['show_serial_number']){
						$row[] = '<b id="'.$aRow['id'].'" class="datatables-record-id" style="font-size:0.8em; ">'.++$sn.'</b>';
					}
                    $returning_html_data .= '<tr class="details-section-container-row details-section-container-row-'.$aColumns[$i].'" jid="'.$aColumns[$i].'">';
                        $returning_html_data .= '<td class="details-section-container-label" width="30%">ID';
                        $returning_html_data .= '</td>';
                        $returning_html_data .= '<td class="details-section-container-value">';
                            $returning_html_data .= $aRow['id'];
                        $returning_html_data .= '</td>';
                    $returning_html_data .= '</tr>';
				break;
				case "record_status":
				case "ip_address":
				break;
				default:
					
					//Check to skip field
					if( ( ( isset( $field['display_position'] ) && ( $field['display_position'] == 'more-details' || $field['display_position'] != 'do-not-display-in-table' || ( $field['display_position'] == 'display-in-admin-table' && isset($admin_user) ) ) ) && ( isset( $field['field_label'] ) && $field['field_label'] != 'undefined' ) ) || $show_field ){
						//START - CHECK WHETHER OR NOT TO DISPLAY DETAILS
						if($json_settings['show_details']){
							
							
							//Display Field in Details Section (name_dtX_dtY_dtZ | where Z = 5)
							if(isset( $field['field_label'] ) ){
							$returning_html_data .= '<tr class="details-section-container-row details-section-container-row-'.$aColumns[$i].'" jid="'.$aColumns[$i].'">';
								$returning_html_data .= '<td class="details-section-container-label" width="30%">';
										$returning_html_data .= $field['field_label'];
								$returning_html_data .= '</td>';
								
								$returning_html_data .= '<td class="details-section-container-value">';
								
								//Check for Combo Box Interpretation
								switch($field['form_field']){
								case 'select':
									//Get options function name
									if( isset( $field['form_field_options'] ) ){
										$function_to_call = $field['form_field_options'];
                                        switch($function_to_call){
                                        case "get_states_in_country":
                                            if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                                $_SESSION['temp_storage']['selected_country_id'] = $aRow[ $aColumns[$i - 1] ];
                                            }
                                        break;
                                        case "get_cities_in_state":
                                            if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                                $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 1] ];
                                            }
                                        break;
                                        }
                                            
										$options = $function_to_call();
										
										if(isset($options[$aRow[ $aColumns[$i] ]])){
											$interpreted_option = ucwords($options[$aRow[ $aColumns[$i] ]]);
											$returning_html_data .= $interpreted_option;
										}else{
											if($aRow[ $aColumns[$i] ])
												$returning_html_data .= ucwords($aRow[ $aColumns[$i] ]);
											else
												$returning_html_data .= 'not available';
										}
									}else{
										$returning_html_data .= 'not available';
									}
									
								break;
								case "file":
								case 'text-file':
									//Uploaded Document
									if($aRow[ $aColumns[$i] ]){
										if( isset( $skip_page_pointers_for_files ) )
											$returning_html_data .= get_uploaded_files( $skip_page_pointers_for_files, $aRow[ $aColumns[$i] ], $field['field_label'] );
										else
											$returning_html_data .= get_uploaded_files( $pagepointer, $aRow[ $aColumns[$i] ], $field['field_label'] );
									}else{
										$returning_html_data .= 'not available';
									}
								break;
								case "multi-select":
									//Get Options For Multiple Select Box
									if( isset( $field['form_field_options'] ) ){
										$function_to_call = $field['form_field_options'];
										
										if( function_exists($function_to_call) ){
                                            switch($function_to_call){
                                            case "get_cities_in_state_pay_on_delivery":
                                                if( isset( $aRow[ $aColumns[$i - 2] ] ) && $aRow[ $aColumns[$i - 2] ] ){
                                                    $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 2] ];
                                                }
                                            break;
                                            case "get_cities_in_state":
                                                if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                                    $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 1] ];
                                                }
                                            break;
                                            }
											$options = $function_to_call();
											
											$fun = explode(':::',$aRow[$aColumns[$i]]);
											foreach($fun as $f){
												if($f && isset( $options[$f] ) ){
													if($func)$func .= '<br />'.ucwords( $options[$f] );
													else $func = ucwords( $options[$f] );
												}
											}
											$returning_html_data .= $func;
											
										}else{
											$returning_html_data .= 'not available';
										}
									}else{
										$returning_html_data .= 'not available';
									}
                                    
								break;
								case 'date':
                                case 'date-5':
									//Format date
									if( doubleval($aRow[ $aColumns[$i] ]) )
										$returning_html_data .= date("d-M-Y", doubleval($aRow[ $aColumns[$i] ]) );
								break;
                                case 'datetime':
									//Format date
									$returning_html_data .= date("d-M-Y H:i", doubleval($aRow[ $aColumns[$i] ]) );
								break;
								case 'time':
									//Format date
									$returning_html_data .= format_time( $aRow[ $aColumns[$i] ] );
								break;
								case 'number':
								case 'decimal':
									//Format Numbers
									$returning_html_data .= format_and_convert_numbers($aRow[ $aColumns[$i] ],3);
								break;
								case 'currency':
									/* Format Currency */
                                    $a = $aRow[ $aColumns[$i] ];
                                    if( isset( $field['default_currency_field'] ) && isset( $aRow[ $field['default_currency_field'] ] ) && $aRow[ $field['default_currency_field'] ] && $aRow[ $field['default_currency_field'] ] != 'undefined' ){
                                        $direction = 'from ' . trim( $aRow[ $field['default_currency_field'] ] );
                                        $a = convert_currency( $aRow[ $aColumns[$i] ] , $direction , 1 );
                                    }
									$returning_html_data .= convert_currency( $a );
								break;
                                case 'calculated':
                                    
									$_data = evaluate_calculated_value( 
										array(
											'add_class' => $aColumns[$i],
											'row_data' => $aRow,
											'form_field_data' => $field,
										) 
									);
									
									if( isset( $_data['value'] ) )
										$returning_html_data .= $_data['value'];
                                break;
                                case 'textarea':
                                case 'textarea-unlimited':
                                    $returning_html_data .= $aRow[ $aColumns[$i] ];
                                break;
								default:
									/* General output */
									if($aRow[ $aColumns[$i] ])
										$returning_html_data .= ucwords($aRow[ $aColumns[$i] ]);
									else
										$returning_html_data .= 'not available';
                                    
								break;
								}
							$returning_html_data .= '</td>';
							
							$returning_html_data .= '</tr>';
							}
							
						}//END - CHECK WHETHER OR NOT TO DISPLAY DETAILS
					
					}
					
					if( $field['display_position'] == 'do-not-display-in-table'  ){
						//Do not display field at all
					}
					
					if( $field['display_position'] != 'more-details' && $field['display_position'] != 'do-not-display-in-table' && ( isset( $field['field_label'] ) && $field['field_label'] != 'undefined' ) ){
					/***************************************************/
					/***************************************************/
					/***************************************************/
						$cell_data = '';
						$real_cell_data = '';
						
						//Check for Combo Box Interpretation
						switch($field['form_field']){
						case 'select':
							//Get options function name
							$real_cell_data = $aRow[ $aColumns[$i] ];
							
							if( isset( $field['form_field_options'] ) ){
								$function_to_call = $field['form_field_options'];
                                
                                switch($function_to_call){
                                case "get_states_in_country":
                                    if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                        $_SESSION['temp_storage']['selected_country_id'] = $aRow[ $aColumns[$i - 1] ];
                                    }
                                break;
                                case "get_cities_in_state":
                                    if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                        $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 1] ];
                                    }
                                break;
                                }
                                
								$options = $function_to_call();
								
								if( isset($options[$aRow[ $aColumns[$i] ]]) ){
									$interpreted_option = ucwords($options[$aRow[ $aColumns[$i] ]]);
									$cell_data = $interpreted_option;
								}else{
									if($aRow[ $aColumns[$i] ])
										$cell_data = ucwords($aRow[ $aColumns[$i] ]);
									else
										$cell_data = 'not available';
								}
							}else{
								$cell_data = 'not available';
							}
                            include "quick_details_field.php";
						break;
						case 'multi-select':
							//Get Options For Multiple Select Box
							if( isset( $field['form_field_options'] ) ){
								$function_to_call = $field['form_field_options'];
								
								if( function_exists($function_to_call) ){
									switch($function_to_call){
                                    case "get_cities_in_state_pay_on_delivery":
                                        if( isset( $aRow[ $aColumns[$i - 2] ] ) && $aRow[ $aColumns[$i - 2] ] ){
                                            $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 2] ];
                                        }
                                    break;
                                    case "get_cities_in_state":
                                        if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                            $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 1] ];
                                        }
                                    break;
                                    }
                                    $options = $function_to_call();
									
									$fun = explode(':::',$aRow[$aColumns[$i]]);
									$func = '';
									foreach($fun as $f){
										if($f && isset( $options[$f] ) ){
											if($func)$func .= '<br />'.ucwords( $options[$f] );
											else $func = ucwords( $options[$f] );
										}
									}
									$cell_data = $func;
									
								}else{
									$cell_data = 'not available';
								}
							}else{
								$cell_data = 'not available';
							}
							
						break;
						case 'file':
						case 'text-file':
							//Uploaded Document
							$real_cell_data = $aRow[ $aColumns[$i] ];
							
							$files = '';
							if($aRow[ $aColumns[$i] ]){
								if( isset( $skip_page_pointers_for_files ) )
									$files = get_uploaded_files( $skip_page_pointers_for_files, $aRow[ $aColumns[$i] ], $field['field_label'] );
								else
									$files = get_uploaded_files( $pagepointer, $aRow[ $aColumns[$i] ], $field['field_label'] );
							}else{
								$files .= 'not available';
							}
							$cell_data = $files;
						break;
						case 'date':
						case 'date-5':
							//Format date
							if( doubleval( $aRow[ $aColumns[$i] ] ) )
								$cell_data = date("d-M-Y", doubleval( $aRow[ $aColumns[$i] ] ) );
						break;
						case 'time':
							//Format date
							$cell_data = format_time( $aRow[ $aColumns[$i] ] );
						break;
						case 'datetime':
							//Format date
							$cell_data = date("d-M-Y H:i", doubleval( $aRow[ $aColumns[$i] ] ) );
						break;
						case 'number':
							//Format Numbers
							$cell_data =  format_and_convert_numbers( $aRow[ $aColumns[$i] ] , 4 );
						break;
						case 'decimal':
							//Format Decimals
							if( $aRow[ $aColumns[$i] ] == 'null' )
								$cell_data =  '&nbsp;';
							else
								$cell_data =  format_and_convert_numbers( $aRow[ $aColumns[$i] ] , 4 );
						break;
						case 'currency':
							/* Format Currency */
                            $a = $aRow[ $aColumns[$i] ];
                            if( isset( $field['default_currency_field'] ) && isset( $aRow[ $field['default_currency_field'] ] ) && $aRow[ $field['default_currency_field'] ] && $aRow[ $field['default_currency_field'] ] != 'undefined' ){
                                $direction = 'from ' . trim( $aRow[ $field['default_currency_field'] ] );
                                $a = convert_currency( $aRow[ $aColumns[$i] ] , $direction , 1 );
                            }
							$cell_data = convert_currency( $a );
						break;
						case 'calculated':
							
							$_data = evaluate_calculated_value( 
								array(
									'add_class' => $aColumns[$i],
									'row_data' => $aRow,
									'form_field_data' => $field,
								) 
							);
							$cell_data = "";
							if( isset( $_data['value'] ) )
								$cell_data = $_data['value'];
							
							if( isset( $_data['class'] ) )
								$DT_RowClass .= ' '.$_data['class'];
							
                            include "quick_details_field.php";
						break;
                        case 'textarea':
                        case 'textarea-unlimited':
                            $cell_data = $aRow[ $aColumns[$i] ];
                        break;
						default:
							/* General output */
							//if($aRow[ $aColumns[$i] ])
								$cell_data = ucwords($aRow[ $aColumns[$i] ]);
                                include "quick_details_field.php";
							//else
								//$cell_data = 'not available';
						break;
						}
						
						if( $field['form_field'] == 'calculated' ){
							//$row[] = $cell_data;
							$row[] = $cell_data . '<input type="hidden" id="'.$aRow['id'].'-'.$aColumns[$i].'" value="'.$aColumns[$i].'" class="datatables-cell-id" jid="'.$aRow['id'].'" real-value="'.strip_tags( $cell_data ).'" />';
						}else{
							//$row[] = $cell_data ;
							$row[] = $cell_data . '<input type="hidden" id="'.$aRow['id'].'-'.$aColumns[$i].'" value="'.$aColumns[$i].'" class="datatables-cell-id" jid="'.$aRow['id'].'" real-value="'.$real_cell_data.'" />';
						}
					}
				break;
				}
			}
			
			
			//CHECK WHETHER OR NOT TO DISPLAY DETAILS
			if($json_settings['show_details']){
				//Display Further Details
				if(isset($json_settings['show_details_more']) && $json_settings['show_details_more'] && isset($json_settings['special_details_functions']) && is_array($json_settings['special_details_functions'])){
					foreach($json_settings['special_details_functions'] as $function_name_to_call){
						$more_details = $function_name_to_call."_more_details";
						if(function_exists($more_details)){
							$returning_html_data .= '<tr>';
								$returning_html_data .= '<td colspan="2">';
									$returning_html_data .= $more_details( $aRow , $database_name , $database_connection , $function_name_to_call , $pagepointer );
								$returning_html_data .= '</td>';
							$returning_html_data .= '</tr>';
						}
					}
				}
				$returning_html_data .= '</tbody>';
				$returning_html_data .= '</table>';
				$returning_html_data .= '</div>';
				$returning_html_data .= '</div>';
				
				$row[0] = $returning_html_data;
			}
			
			$row["DT_RowClass"] = $DT_RowClass;
			if( $table == 'product' && ! ( $aRow['product018'] + ( $aRow['product019']*3600 ) > __date() && $aRow['product018'] + 1 < __date() ) ){
                //&& $aRow['product'] == '10' || ( isset( $aRow[ 'ip_address' ] ) && $aRow[ 'ip_address' ] == 'total-row' )
				$row["DT_RowClass"] = 'expired-product';
			}
			if( isset( $table_real ) && $table_real == 'cash_calls_reporting_view' && isset( $aRow['row_class'] ) ){
				switch( $aRow['row_class'] ){
				case "space":
					$row["DT_RowClass"] = 'line-items-space-row';
				break;
				case "total":	
				case "total-heading":
					$row["DT_RowClass"] = 'line-items-total-row';
				break;
				}
			}
			$output['aaData'][] = $row;
			
		}
		
	}
?>