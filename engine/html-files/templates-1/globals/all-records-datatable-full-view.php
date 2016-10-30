<?php
	if( ! isset( $col_1 ) )$col_1 = 3;
	if( ! isset( $col_2 ) )$col_2 = 9;
?>

<div class="row">
    <div class="col-md-<?php echo $col_1; ?>">
		<?php include dirname( dirname( __FILE__ ) ) . "/globals/form-details-report-view.php"; ?>
	</div>
    <div class="col-md-<?php echo $col_2; ?>"> 
		<?php include dirname( dirname( __FILE__ ) ) . "/globals/line-items-datatable-view.php"; ?>
	</div>
</div>