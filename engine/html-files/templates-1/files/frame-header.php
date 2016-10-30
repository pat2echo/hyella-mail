<div class="caption" title="<?php echo $title; ?>">
	<?php if( $show_title_icon ){ ?>
	<i class="icon-globe"></i>
	<?php } ?>
	
	<?php if( $show_small_title ){ ?>
	<small style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:inline-block; width: 300px;"><small>
	<?php } ?>
	
	<?php echo $title_label; ?> <span id="divisional-report-title-text"><?php echo $title; ?></span>
	
	<?php if( $show_small_title ){ ?>
	</small></small>
	<?php } ?>
</div>

<?php if( $show_action_buttons ){ ?>
<div class="btn-group btn-group-solid pull-right">
	<button type="button" class="btn btn-sm red dropdown-toggle" data-toggle="dropdown"> Actions
	<i class="icon-angle-down"></i>
	</button>
	<ul class="dropdown-menu">
		<?php if( $editable ){ ?>
		<li><a href="#call ajax request to activate edit view with current version selected" ><i class="icon-edit"></i> Edit</a></li>
		<?php } ?>
		<li><a href="#"><i class="icon-share"></i> Share</a></li>
	</ul>
</div>
<?php } ?>

<?php if( $editable && $show_main_buttons ){ ?>
<a href="#" class="pull-right btn btn-sm red" id="export-to-pdf" iframe-target="#iframe-container-of-content" title-target="#divisional-report-title-text" action="?action=mypdf&todo=generate_pdf"><i class="icon-save"></i> Export As PDF</a>
<a href="#" class="pull-right btn btn-sm blue" id="save-editable-content" iframe-target="#iframe-container-of-content" title-target="#divisional-report-title-text" action="?action=files&todo=save_edited_content"><i class="icon-save"></i> Save</a>
<?php } ?>
<?php if( $shareable && $show_main_buttons ){ ?>
<a href="#" class="pull-right btn btn-sm dark custom-single-selected-record-button" override-selected-record="<?php echo $report_id; ?>" mod="edit" todo="share" action="?module=&action=files&todo=share" function-id="1" function-class="files" function-name="share" ><i class="icon-share"></i> Share With...</a>
<?php } ?>
<?php if( $editable && $show_main_buttons ){ ?>
<a href="#" class="pull-right btn btn-sm dark custom-single-selected-record-button" override-selected-record="<?php echo $report_id; ?>" mod="edit" todo="attach_file" action="?module=&action=files&todo=attach_file" function-id="1" function-class="files" function-name="attach_file"><i class="icon-picture"></i> Attach File</a>
<a href="#" class="pull-right btn btn-sm dark custom-single-selected-record-button" override-selected-record="<?php echo $report_id; ?>" mod="edit" todo="comment_file_form" action="?module=&action=comment&todo=comment_file_form" function-id="1" function-class="comment" function-name="comment_file_form"><i class="icon-comments-alt"></i> Comment</a>
<?php } ?>