<?php
	include dirname( dirname( __FILE__ ) ) . "/globals/invoice-css.php"; 
	$pr = get_project_data();		
		
	$show_buttons = 1;
	if( isset( $data["hide_buttons"] ) && $data["hide_buttons"] )
		$show_buttons = 0;
	
	$backend = 0;
	if( isset( $data["backend"] ) && $data["backend"] )
		$backend = $data["backend"];
	
	$show_image = 0;
	if( isset( $data["show_item_image"] ) && $data["show_item_image"] )
		$show_image = $data["show_item_image"];
	
	$customers = get_customers_details( array( "id" => $data["event"]["customer"] ) );
	
?>
<style type="text/css">
@media print{
	html, body{
		background:#fff;
	}
	
	#invoice-container .invoice{
		border:none;
		padding:10px;
	}
	
	#invoice-container{
		margin:auto;
	}
	.hidden-print{
		display:none;
	}
}
</style>
<div style="width:100%; ">
	<div class="page-container">
	<div class="container" id="invoice-container" >
	<div class="invoice" style="<?php if( ! $backend ){ ?>margin-top:20px; height:982px; background:url(<?php echo $pr["domain_name"]; ?>images/appraisal.png) #fff top center no-repeat; background-size: cover;<?php } ?>">
		<table width="100%" style="padding-top:10px;">
			<?php if( ! $backend ){ ?>
			<tr>
				<td width="30%"></td>
				<td width="40%" style="font-family:algerian; font-size:40px; text-align:center; line-height:1; padding-bottom:10px; ">Certified Appraisal<div style="font-family:arial; font-size:14px; font-weight:bold; color:#666;"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?></div></td>
				<td width="30%"></td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="3" style="font-family:arial; <?php if( ! $backend ){ ?> padding-left:15%; padding-right:15%; <?php } ?> line-height:1.4;  font-size:15px;">
				<?php if( ! $backend ){ ?>
				This is to certify that we are, and for many years have been engaged in the jewelry business, purchasing, selling and appraising diamonds, watches, jewelry and precious stones of every name and nature
				<br />
				<br />
				<?php } ?>
				We herewith certify that we have this day carefully examined the following listed and described articles<br />
				<table class="table table-striped" width="100%">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Item</th>
                           <th>Description</th>
                           <th style="text-align:right;">Appraised Value</th>
                        </tr>
                     </thead>
                     <tbody>
						<?php 
						$total = 0;
						$serial = 0;
						$refund = 0;
						
						if( isset( $data["event_items"] ) && is_array( $data["event_items"] ) && ! empty( $data["event_items"] ) ){
							$count = count( $data["event_items"] );
							$width = 80;
							$style = "";
							$style1 = " font-size:13px; line-height:1.4; ";
							if( $count > 3 ){
								$width = 50;
								$style = " font-size:10px; ";
								$style1 = " font-size:12px;  line-height:1.2; ";
							}
								
							foreach( $data["event_items"] as $items ){
								++$serial;
								
								$price = $items["appraised_value"];
								$title = "";
								$item_details = get_items_details( array( "id" => $items["item"] ) );
								
								$title = '<img src="' . $pr["domain_name"] . $item_details["image"] . '" width="'.$width.'" style="margin-right:5px; border:1px solid #aaa;" />';
								
								$total += $price;
								?>
								<tr>
								   <td><?php echo $serial; ?></td>
								   <td><?php echo $title; ?></td>
								   <td >
								   <strong><?php echo $item_details["description"]; ?></strong><br />
								   <div style="<?php echo $style; ?>">
								   Color of Gold: <?php echo $item_details['color_of_gold']; ?><br />
								   Length of Chain: <?php echo $item_details['length_of_chain']; ?><br />
								   Weight In Grams: <?php echo $item_details['weight_in_grams']; ?>
								   </div>
								   </td>
								   <td align="right"><?php echo format_and_convert_numbers( $price , 4 ); ?></td>
								</tr>
								<?php
							}
						}
						?>
                        
					 </tbody>
				</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="font-family:arial; <?php if( ! $backend ){ ?> padding-left:15%; padding-right:15%; <?php }  echo $style1; ?>">
				
				Following is the name and address of the Purchaser and Owner
				<br />
				NAME:&nbsp; &nbsp; &nbsp; &nbsp;<strong><?php if( isset( $customers[ "name" ] ) )echo $customers[ "name" ]; ?></strong><br />
				ADDRESS: <strong><?php if( isset( $customers[ "address" ] ) )echo $customers[ "address" ]; ?></strong><br />
				REF:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong><?php if( isset( $data[ "event" ][ "serial_num" ] ) )echo "#" . $data[ "event" ][ "serial_num" ]; ?></strong><br /><br />
				<?php if( ! $backend ){ ?>
				Notice: This appraisal tells you the approximate price at which you could replace the foregoing articles with comparable merchandise at a retail jewelry establishment selling jewelry of like quality.<br /><br />
				You should not expect to be able to sell articles for this amount. The opinion of appraisers concerning value vary.<br />
				We do not promise to buy the articles from you at the appraised value or any price less than the appraised value.<br /><br />
				IN WITNESS WHEREOF, the undersigned has hereto affixed its Seal and authorised signature this day of ________________________________
				
					<br />
				  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print Invoice <i class="icon-print"></i></a>
				  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
				  <?php }else{ ?>
				  <br />
				  <a href="../?page=print-appraisal&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue hidden-print">Print Appraisal Certificate <i class="icon-print"></i></a>
				  <?php } ?>
				</td>
			</tr>
		</table>
	</div>
	</div>
	</div>
	</div>
	
