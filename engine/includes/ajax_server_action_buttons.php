<?php
	/*
	* AIM: Draw up Action Buttons for Ajax Server Files
	* 
	* DESCRIPTION: To display data in grids, 
	* a jQuery datatable plugin is used, 
	* the source of data used in populating the grids / tables by this plugin is obtained from a php server side file,
	* this file contains some group of action buttons for each record,
	* those action buttons are generated by this file
	*
	* WRITTEN ON: 15-08-2013
	* BY: PATRICK O. OGBUITEPU
	*
	*/
	
	$returning_html_data = '';
	
	if(isset($allow_check) && $allow_check){
		$returning_html_data .= '<form class="icon-form remove-before-export action-button-form" action="?module='.$_SESSION['module'].'&action='.$table.'&todo=check" method="post" todo="edit-check">';
			$returning_html_data .= '<input type="submit" value="check" data-role="button" data-theme="b" data-icon="forward" data-iconpos="notext" data-inline="true" title="Mark as Checked">';
			$returning_html_data .= '<input name="id" value="'.$aRow['ID'].'" type="hidden" />';
			$returning_html_data .= '<input name="mod" value="edit-'.md5($table).'" type="hidden" />';
		$returning_html_data .= '</form>';
	}
	
	if(isset($allow_approve) && $allow_approve){
		$returning_html_data .= '<form class="icon-form remove-before-export action-button-form" action="?module='.$_SESSION['module'].'&action='.$table.'&todo=approve" method="post" todo="edit-approve">';
			$returning_html_data .= '<input type="submit" value="check" data-role="button" data-theme="b" data-icon="check" data-iconpos="notext" data-inline="true" title="Mark as Approved" >';
			$returning_html_data .= '<input name="id" value="'.$aRow['ID'].'" type="hidden" />';
			$returning_html_data .= '<input name="mod" value="edit-'.md5($table).'" type="hidden" />';
		$returning_html_data .= '</form>';
	}
	
	if(isset($allow_edit) && $allow_edit){
		$returning_html_data .= '<form class="icon-form remove-before-export action-button-form edit-action-button" action="?module='.$_SESSION['module'].'&action='.$table.'&todo=new" method="post" todo="edit" id="edit-action-button-id-'.$aRow['ID'].'">';
			$returning_html_data .= '<input type="submit" value="edit" data-role="button" data-theme="f" data-icon="edit" data-iconpos="notext" data-inline="true" title="Modify Record">';
			$returning_html_data .= '<input name="id" value="'.$aRow['ID'].'" type="hidden" />';
			$returning_html_data .= '<input name="mod" value="edit-'.md5($table).'" type="hidden" />';
		$returning_html_data .= '</form>';
	}
	
	if(isset($allow_delete) && $allow_delete){
		$returning_html_data .= '<form class="icon-form remove-before-export action-button-form delete-action-button" action="?module='.$_SESSION['module'].'&action='.$table.'&todo=delete" method="post" todo="delete">';
			$returning_html_data .= '<input type="submit" value="delete" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" data-inline="true" title="Delete Record">';
			$returning_html_data .= '<input name="id" value="'.$aRow['ID'].'" type="hidden" />';
			$returning_html_data .= '<input name="mod" value="delete-'.md5($table).'" type="hidden" />';
		$returning_html_data .= '</form>';
	}
?>