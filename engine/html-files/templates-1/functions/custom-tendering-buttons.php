<?php $budget = ""; if( isset( $data['title'] ) && $data['title'] )$budget = $data['title']; ?>
<!--<a href="#" budget-id="<?php //echo $budget_id; ?>" month-id="-" function-id="1" function-class="budget" function-name="add_new_budget" class="btn btn-success btn-sm custom-action-button">Add New Record</a>-->
<a href="#" function-id="1" function-class="tendering" function-name="edit" mod="edit-<?php echo md5("tendering"); ?>" module="" class="btn red btn-sm custom-single-selected-record-button" todo="update_status" action="?module=&action=tendering&todo=update_status">Update Status</a>