<?php
	/*
	* AIM: Prepare Query for Ajax Server Files
	* 
	* DESCRIPTION: To display data in grids, 
	* a jQuery datatable plugin is used, 
	* the source of data used in populating the grids / tables by this plugin is obtained from a php server side file,
	* this file contains some group of action buttons for each record,
	* those action buttons are generated by this file
	*
	* WRITTEN ON: 18-08-2013
	* BY: PATRICK O. OGBUITEPU
	*
	*/
	
	//CHECK USER PRIVILEGE
	$use_cache = 1;
	$classname = $table;
	
	$allow_view = permission( $current_user_session_details , 'display_all_records'  , $classname , $database_connection , $database_name );
	
	if(!(isset($_SESSION['key'])))exit;
	
	if(!($allow_view))exit;
	
	if( ! ( isset( $allow_no_lang ) && $allow_no_lang ) && ! defined( strtoupper( $classname ) ) ){
		if( ! ( load_language_file( array( 
			'id' => $classname , 
			'pointer' => $pagepointer, 
			'language' => 'US',//SELECTED_COUNTRY_ISO_CODE
		) ) && defined( strtoupper( $classname ) ) ) ){
			//REPORT INVALID TABLE ERROR
			$err = new cError(000017);
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'c'.ucwords($classname).'.php';
			$err->method_in_class_that_triggered_error = '_language_initialization';
			$err->additional_details_of_error = 'no language file';
			echo json_encode( $err->error() );
			exit;
		}
	}
    
    if( isset( $current_user_session_details['role'] ) && isset( $current_user_session_details['id'] ) ){
        switch( strtolower( $current_user_session_details['role'] ) ){
        case 'seller':
        case 'buyer':
        case 'admin_seller':
        break;
        default:
            //admin users
            $admin_user = 1;
        break;
        }
    }
        
	$returning_html_data = '';
	$hbc = '';
	$hsbc = '';	
		
	//GET ALL FIELDS IN TABLE
	include "ajax_server_table_fields.php";
	
	/* DB table to use */
	//$sTable = "categories";
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	$offset = 0;
	$numrows = 0;
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
		
		//$offset  = intval($_GET['iDisplayStart']);  // skip this many rows
		//$numrows = intval($_GET['iDisplayLength']);  // return 5 rows
	}
	
	
	/*
	 * Ordering
	 */
	
	$static_fields = array(
        0 => 'created_role',
        1 => 'created_by',
        2 => 'creation_date',
        3 => 'modified_by',
        4 => 'modification_date',
    );
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) && isset ( $_GET['iSortingCols'] ) )
	{	
		//Enable for Mysql
		--$_GET['iSortCol_0']; //disabled last
		//--$_GET['iSortCol_0']; //disabled last
		//++$_GET['iSortCol_0'];
		
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( isset (  $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] ) && $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
                $scol = intval( $_GET['iSortCol_'.$i] );
                if( $scol > 9 ){
                    $scol = '0'.$scol;
                }else{
                    $scol = '00'.$scol;
                }
				//Get Field Info
                
                $id = 'none';
				$field1['form_field'] = 'text';
                if( isset( $form_label[ $table.$scol ] ) ){
                    $field1 = $form_label[ $table.$scol ];
                    $id = $table.$scol;
                }
                
                $size = ( count( $displayed_table_columns ) );
                
                if( intval($scol) > $size ){
                    if( isset( $static_fields[ $scol - $size ] ) ){
                        $id = $static_fields[ $scol - $size ];
                    }
                }
                //print_r($form_label[ $displayed_table_columns[ intval( $_GET['iSortCol_'.$i] ) ] ]);
				//$x = rand(876566,346353422224445432564398765);
				//write_file('','sql/'.$x.'sortcol.php',$_GET['iSortCol_'.$i]);
		
				//CHECK FOR DATA TYPE
                switch( $id ){
                case 'creator_role':
                case 'none':
                break;
                default:
                    $sOrder .= "(`".$table."`.`".$id."`) ".( $_GET['sSortDir_'.$i] ) .", ";
                break;
                }
				
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
			
			if( isset( $table_real ) ){
				switch( $table_real ){
				case 'cash_calls_reporting_view':
					$use_cache = 0;
				case 'cash_calls':
				case 'budget_details':
				default:
					if ( isset( $_SESSION[ $table ]['order_by'] ) && $_SESSION[ $table ]['order_by'] && ($sOrder == "ORDER BY" || $sOrder == "") ){
						$sOrder = $_SESSION[ $table ]['order_by'];
					}else{
						$sOrder = "ORDER BY `".$table."`.`modification_date` desc";
					}
				break;
				}
			}else{
				$sOrder = "ORDER BY `".$table."`.`modification_date` desc";
			}
			
		}else{
			$sOrder .= ", `".$table."`.`modification_date` desc";
		}
		
	}
	
	
	if ( $sOrder == "ORDER BY" || $sOrder == "" ){
		$sOrder = "ORDER BY `".$table."`.`modification_date` desc";
	}
    
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "WHERE `".$table."`.`record_status`='1'";
	
	$sJoin = "";
	$sJoin_count = "";
	$sGroup = "";
	
	$additional_where_of_query = "";
	
	if( isset( $_SESSION[ $table ][ 'filter' ][ 'join' ] ) && $_SESSION[ $table ][ 'filter' ][ 'join' ] ){
        $sJoin .= $_SESSION[ $table ][ 'filter' ][ 'join' ];
    }
	
	if( isset( $_SESSION[ $table ][ 'filter' ][ 'additional_where' ] ) && $_SESSION[ $table ][ 'filter' ][ 'additional_where' ] ){
        $additional_where_of_query = $_SESSION[ $table ][ 'filter' ][ 'additional_where' ];
    }
	
	if( isset( $_SESSION[ $table ][ 'filter' ][ 'join_count' ] ) && $_SESSION[ $table ][ 'filter' ][ 'join_count' ] ){
        $sJoin_count .= $_SESSION[ $table ][ 'filter' ][ 'join_count' ];
    }
	
	if( isset( $_SESSION[ $table ][ 'filter' ][ 'group' ] ) && $_SESSION[ $table ][ 'filter' ][ 'group' ] ){
        $sGroup .= $_SESSION[ $table ][ 'filter' ][ 'group' ];
    }
	
	$sSelect = " * ";
	if( isset( $_SESSION[ $table ][ 'filter' ][ 'select' ] ) && $_SESSION[ $table ][ 'filter' ][ 'select' ] ){
        $sSelect = $_SESSION[ $table ][ 'filter' ][ 'select' ];
    }
	
	$sFrom = $sSelect;
	
	if( isset( $_SESSION[ $table ][ 'filter' ][ 'from' ] ) && $_SESSION[ $table ][ 'filter' ][ 'from' ] ){
        $sFrom .= $_SESSION[ $table ][ 'filter' ][ 'from' ];
    }else{
		$sFrom .= "FROM `".$database_name."`.`".$table."`".$sJoin;
	}
	
	$sCount_From = " COUNT( `".$table."`.`id` ) as 'count' FROM `".$database_name."`.`".$table."`".$sJoin_count;
	
	
	//Apply Class Method Filtering
	if(isset($_SESSION[$table]['filter']['show_deleted_records']) && $_SESSION[$table]['filter']['show_deleted_records']){
		$sWhere = "WHERE `".$table."`.`record_status`='0'";
	}
	
	//Apply Class Method Filtering
	if(isset($_SESSION[$table]['filter']['selected_record']) && $_SESSION[$table]['filter']['selected_record']){
		$sWhere .= $_SESSION[$table]['filter']['selected_record'];
	}
	
	//Apply Class Method Filtering
	if(isset($_SESSION[$table]['filter']['show_my_records']) && $_SESSION[$table]['filter']['show_my_records']){
		$sWhere .= " AND ( `".$table."`.`created_by`='".$current_user_session_details['id']."' ) ";
	}
	
    $more_where = "";
    
	switch($table){
	case 'users':
		//Apply Class Method Filtering
		if(isset($_SESSION[$table]['filter']['show_my_user_account']) && $_SESSION[$table]['filter']['show_my_user_account']==1){
			$more_where .= " AND `".$table."`.`ID`='".$current_user_session_details['id']."'";
		}
        $more_where .= " AND `".$table."`.`users009` != '1300130013'";
	break;
	case 'access_roles':
		//Apply Class Method Filtering
		$more_where .= " AND `".$table."`.`id` != '1300130013'";
	break;
	case 'payout_requests':
	case 'coupons':
	case 'tasks':
	case 'notifications':
        if( isset( $current_user_session_details['role'] ) && isset( $current_user_session_details['id'] ) ){
            switch( strtolower( $current_user_session_details['role'] ) ){
            case 'seller':
            case 'buyer':
            case 'admin_seller':
                $more_where .= " AND `".$table."`.`created_by` = '" . $current_user_session_details['id'] . "'";
                
                $skip_page_pointers_for_files = 'engine/';
            break;
            default:
                //admin users
            break;
            }
        }
	break;
	}
	
    if( isset( $_SESSION[ $table ][ 'filter' ][ 'where' ] ) && $_SESSION[ $table ][ 'filter' ][ 'where' ] ){
        $sWhere .= $_SESSION[ $table ][ 'filter' ][ 'where' ];
    }
    
	
    $sWhere .= $more_where;
    
    
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		
		$sWhere .= " AND (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if( isset( $form_label[ $aColumns[$i] ] ) ){
				//Get Field Info
				$field = $form_label[ $aColumns[$i] ];
				
				//CHECK FOR DATA TYPE
				switch($field['form_field']){
				case "select":
				case "multi-select":
				case "date":
					//Transform incoming data to matching key of options array
					$transformed_search = transform_search_value(( $_GET['sSearch'] ), $field, $table);
					$sWhere .= "`".$table."`.`".$aColumns[$i]."` LIKE '%".$transformed_search."%' OR ";
				break;
				default:
					$sWhere .= "`".$table."`.`".$aColumns[$i]."` LIKE '%".( $_GET['sSearch'] )."%' OR ";
				break;
				}
			}
			$sWhere .= "`".$table."`.`id` = '".( $_GET['sSearch'] )."' OR ";
			if( doubleval( $_GET['sSearch'] ) )$sWhere .= "`".$table."`.`serial_num` = '".doubleval( $_GET['sSearch'] )."' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE `".$table."`.`record_status`='1' AND ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			
			//Get Field Info
			$field = get_form_field_type($aColumns[$i]);
			//CHECK FOR DATA TYPE
			switch($field['form_type']){
			case "9":
			case "4":
				//Transform incoming data to matching key of options array
				$transformed_search = transform_search_value( ($_GET['sSearch_'.$i]), $field, $table);
				$sWhere .= "`".$table."`.`".$aColumns[$i]."` LIKE '%".$transformed_search."%' ";
			break;
			default:
				$sWhere .= "`".$table."`.`".$aColumns[$i]."` LIKE '%".($_GET['sSearch_'.$i])."%' ";
			break;
			}
			
		}
	}
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	/**************************************************************************/
	$arr = array();
	
	if( ! isset( $controller_table ) ){
		$controller_table = $table;
	}
	
	//CHECK IF ADVANCE SEARCH MODE IS ACTIVE
	//Set Temporary Search Query for Ajax_server
	$sq = md5('search_query'.$_SESSION['key']);
	if(isset($_SESSION[$sq]) && is_array($_SESSION[$sq]) && isset($_SESSION[$sq][$controller_table]['select']) && isset($_SESSION[$sq][$controller_table]['where']) && isset($_SESSION[$sq][$controller_table]['from'])){
		$query = $_SESSION[$sq][$controller_table]['select']." ".$sFrom." ".$_SESSION[$sq][$controller_table]['where'].$additional_where_of_query." ".$sGroup." ".$sOrder." ".$sLimit;
		
		$query_settings = array(
			'database'=>$database_name,
			'connect'=>$database_connection,
			'query'=>$query,
			'query_type'=> $_SESSION[$sq][$controller_table]['select'],
			'source_file'=>'ajax_server',
			'offset'=>$offset,
			'number_of_rows'=>$numrows,
			'set_memcache'=>1,
			'tables'=>array($controller_table),
		);
		$sql_result = execute_sql_query($query_settings);
		//unset($_SESSION[$sq][$table]);
	}else{
		$query = "SELECT ".$sFrom." ".$sWhere.$additional_where_of_query." ".$sGroup." ".$sOrder."  ".$sLimit;
		
		$query_settings = array(
			'database'=>$database_name,
			'connect'=>$database_connection,
			'query'=>$query,
			'query_type'=>'SELECT',
			'source_file'=>'ajax_server',
			'offset'=>$offset,
			'number_of_rows'=>$numrows,
			'set_memcache'=>$use_cache,
			'tables'=>array($table),
		);
		$sql_result = execute_sql_query($query_settings);
		
		//print_r($sql_result);
		//echo $query;
		//exit;
	}
	
	//
	if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
		$arr = $sql_result;
	}else{
		//REPORT INVALID TABLE ERROR
		$err = new cError(000001);
		$err->action_to_perform = 'notify';
		
		$err->class_that_triggered_error = 'ajax_server_query.php';
		$err->method_in_class_that_triggered_error = $table;
		$err->additional_details_of_error = '';
		
		//echo json_encode( $err->error() );
		$err->error();
		//exit;
	}
    
?>