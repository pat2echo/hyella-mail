<br style="line-height:0.5;"/>
<div class="alert alert-warning">
	<h4><i class="icon-bell"></i> Please Select a Guest</h4>
	<p>
	This report requires that you select a Guest first<br />
	</p>
	<hr />
	<label style="font-weight:bold; font-size:11px;">Available Guests</label>
	<select id="select-pen" alt="report-pen" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;">
		<option value="">--Please Select a Guest--</option>
		
		<?php 
			$all_pens = get_customers();
			foreach( $all_pens  as $k => $v ){
				?>
				<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
				<?php
			}
		?>
	</select>
	<script type="text/javascript">
		$("select#select-pen")
		.on("change", function(){
			$("select#" + $(this).attr("alt") ).val( $(this).val() ).change();
		});
	</script>
</div>
