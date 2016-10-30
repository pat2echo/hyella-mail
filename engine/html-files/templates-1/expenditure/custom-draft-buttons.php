<a href="#" class="custom-single-selected-record-button btn green btn-sm" mod="edit-<?php echo md5("expenditure"); ?>" action="?module=&action=expenditure&todo=edit_draft_record" title="Edit Draft Expenses">Edit Record</a>
<div class="btn-group">
	<button type="button" class="btn btn-sm red dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
	Post Draft Expenses <i class="icon-angle-down"></i>
	</button><div class="dropdown-backdrop"></div>
	 
	<ul class="dropdown-menu" role="menu">
		<li><a href="#" class="custom-single-selected-record-button" action="?module=&action=expenditure&todo=post_selected_draft" title="Post Selected Draft Expenses">Post Selected</a></li>
		<li><a href="#" class="custom-single-selected-record-button" override-selected-record="1" action="?module=&action=expenditure&todo=post_all_draft" title="Post All Draft Expenses">Post All</a></li>
		<li class="divider"></li>
		<li><a href="#" class="custom-single-selected-record-button" action="?module=&action=expenditure&todo=post_selected_draft_as_paid" title="Post Selected As Paid">Post Selected As Paid &nbsp;&nbsp;</a></li>
		<li><a href="#" class="custom-single-selected-record-button" override-selected-record="1" action="?module=&action=expenditure&todo=post_all_draft_as_paid" title="Post All As Paid">Post All As Paid &nbsp;&nbsp;</a></li>
	</ul>
</div>