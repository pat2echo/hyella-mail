
<br style="line-height:0.5;"/>
<div class="alert alert-danger">
	<h4><i class="icon-bell"></i> No Data Found</h4>
	<?php if( isset( $data["start_date"] ) && $data["start_date"] ){ ?>
		<br /><p>Selected Dates: From <strong><?php echo date( "d-M-Y", doubleval( $data["start_date"] ) ); ?></strong> 
			<?php if( isset( $data["end_date"] ) && $data["end_date"] ){ ?> to <strong><?php echo date( "d-M-Y", doubleval( $data["end_date"] ) ); ?></strong><?php } ?>
		</p><br />
	<?php } ?>
	<div>
	Please select the report type & set the date
	</div>
	<br />
</div>
