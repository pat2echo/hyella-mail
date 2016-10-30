<!--EXCEL IMPOT FORM-->

	<div class="portlet box purple">
		<div class="portlet-title">
			<div class="caption">
				<small>
				<i class="icon-bell"></i> <span id="datatable-title"><?php if( isset( $data['title'] ) && $data['title'] )echo $data['title']; ?></span>
				</small>
			</div>
			<?php include "quick-select-view.php"; ?>
			<small style="float:right; font-size:11px; max-width:550px;">
				<small id="search-title">
					<?php if( defined( "SEARCH_QUERY" ) )echo SEARCH_QUERY; ?>
				</small>
			</small>
		</div>
		<div class="portlet-body" style="padding-bottom:50px;" id="data-table-section">
		<?php
		if( isset( $data['html'] ) && $data['html'] ){
			echo $data['html'];
		}
		include "datatable-record-details-popup.php";
		?>
		</div>
	</div>

