<?php
	$n = 0;
	$fields = array();
	$displayed_table_columns = array();
	
	$func = $table;
    if( isset( $table_real ) )$func = $table_real;
    
	if(function_exists($func))
		$form_label = $func();
	else
		$form_label = array();
			
	$query = "DESCRIBE `".$database_name."`.`".$table."`";
	$query_settings = array(
		'database'=>$database_name,
		'connect'=>$database_connection,
		'query'=>$query,
		'query_type'=>'DESCRIBE',
		'set_memcache'=>1,
		'tables'=>array($table),
	);
	$sql_result = execute_sql_query($query_settings);
	
	if($sql_result && is_array($sql_result)){
        
        $sql_result = reorder_fields_based_on_serial_number( $sql_result , $form_label );
        
		foreach($sql_result as $sval){
			$fields[] = $sval[0];
			
			$field['display_position'] = 'none';
			//$field = get_form_field_type($sval[0]);
			if( isset( $form_label[ $sval[0] ] ) )
                $field = $form_label[ $sval[0] ];
            
			switch($field['display_position']){
			case 'do-not-display-in-table':
			case 'none':
            break;
			case 'display-in-table-row':
            default:
				$displayed_table_columns[] = $sval[0];
			break;
			}
			
			++$n;
		}
        //print_r($fields);
	}else{
		//REPORT INVALID TABLE ERROR
		$err = new cError(000001);
		$err->action_to_perform = 'notify';
		
		$err->class_that_triggered_error = 'ajax_server_query.php';
		$err->method_in_class_that_triggered_error = $table;
		$err->additional_details_of_error = 'executed query '.str_replace("'","",$query);
		return $err->error();
	}
    
	$aColumns = $fields;
	
	/* Indexed column (used for fast and accurate table budgetdinality) */
	$sIndexColumn = "id";
	
?>