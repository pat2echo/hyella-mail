<div id="<?php if( isset( $modal_id ) )echo $modal_id; else echo "myModal"; ?>" class="modal fade" tabindex="-1" data-replace="true">
	<div class="modal-dialog" style="<?php if( isset( $data["modal_dialog_style"] ) )echo $data["modal_dialog_style"]; ?>">
	   <div class="modal-content">
		  <div class="modal-header" style="background:#222; color:#fff;">
			 <button type="button" style="color:#fff;" class="close" data-dismiss="modal" title="Click here to close the modal box" aria-hidden="true"><i class="icon-remove" style="color:#fff;"></i></button>
			 <h4 class="modal-title"><?php if( isset( $modal_title ) )echo $modal_title; ?></h4>
		  </div>
		  <div class="modal-body" id="modal-replacement-handle">
			 <?php if( isset( $modal_body ) )echo $modal_body; ?>
		  </div>
		  <div class="modal-footer" >
             <button id="modal-popup-close" type="button" class="btn btn-danger" title="Click here to close the modal box" data-dismiss="modal"><?php if( isset( $modal_finish_caption ) )echo $modal_finish_caption; else echo "Finish"; ?></button>
		  </div>
	   </div>
	</div>
 </div>