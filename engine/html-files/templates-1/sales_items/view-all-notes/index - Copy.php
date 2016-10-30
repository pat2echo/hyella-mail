<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	$thead = "<thead><tr>
		<th>Date</th>
		<th>Note / Attachment</th>
	</tr></thead>";
	
	$all_bills = array();
	$serial_no = array();
	$types = get_type_of_note();
	
	if( isset( $data['notes'] ) && is_array( $data['notes'] ) ){
		foreach( $data['notes'] as $bill ){
			//if( $bill['type'] == "awaiting_signature" )$bill['type'] = "law";
			
			if( ! isset( $all_bills[ $bill['type'] ] ) )$all_bills[ $bill['type'] ] = "";
			
			$title = "";
			$last_col = "";
			
			$all_bills[ $bill['type'] ] .= "<tr>
				<td><strong>Modified:</strong> ".date("d-M-Y", doubleval( $bill["modification_date"] ) )."<br /><br /><strong>Created:</strong> ".date("d-M-Y", doubleval( $bill["creation_date"] ) )."</td>
				<td>".$bill["note"]."<hr />".get_uploaded_files( $pagepointer, $bill["document"], "Attachment" )."</td>
			</tr>";
			
			//get_select_option_value( array( "id" => $bill["sponsor"], "function_name" => "get_members" ) )
		}
	}
?>
<div class="tab-style-1 margin-bottom-20">
	<ul class="nav nav-tabs">
		<?php $first = 1; foreach( $types as $key => $val ){ ?>
			<li class="<?php if( $first ){ $first = 0; echo "active"; } ?>"><a href="#<?php echo $key; ?>" data-toggle="tab"><?php echo $val; ?></a></li>
		<?php } ?>
		
	</ul>
	<div class="tab-content">
		<?php $first = 1; foreach( $types as $key => $val ){ ?>
			
		<div class="tab-pane fade <?php  if( $first ){ $first = 0; echo "active in"; } ?>" id="<?php echo $key; ?>">
			<div class="form-control-table">
			<h4><?php echo $val; ?></h4>
			<?php
				echo "<table class='table table-striped table-bordered table-hover'>" . $thead . "<tbody id='table-body-".$key."'>";
				if( isset( $all_bills[ $key ] ) && $all_bills[ $key ] ){
					echo $all_bills[ $key ];
				}
				echo "</tbody></table>";
			?>
			</div>
		</div>
		
		<?php } ?>
		
	</div>
</div>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>