<style type="text/css">
.textdark { 
color: #444444;
font-family: Open Sans;
font-size: 13px;
line-height: 150%;
text-align: left;
}
</style>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f8f8f8;border-bottom:1px solid #e7e7e7;">
	<tr>
		<td>
			<center>
				<table border="0" cellpadding="0" cellspacing="0" width="500px" style="height:100%;">
					<tr>
						<td valign="top" style="padding:20px;">
							<div class="textdark">
								<strong>FROM: <?php $key = "sharer"; if( isset( $data[ $key ] ) && $data[ $key ] )echo $data[ $key ]; ?></strong> 
								<h4><?php $key = "title"; if( isset( $data[ $key ] ) && $data[ $key ] )echo $data[ $key ]; ?></h4>
							</div>
							<hr />
							<?php 
							$key = "message"; if( isset( $data[ $key ] ) && $data[ $key ] ){
							?>
							<div class="textdark">
								<strong>Message:</strong><br />
							<?php
								echo $data[ $key ];
							?>
							</div><br />
							<?php
								} 
							?>
							<hr />
							<div class="textdark">
								<strong>Recipients:</strong><br />
								<?php 
									$key = "recipient"; 
									if( isset( $data[ $key ] ) && is_array( $data[ $key ] ) ){
										echo "<ol>";
										foreach( $data[ $key ] as $id => $file ){
											echo "<li>" . $file . "</li>"; 
										}
										echo "</ol>";
									} 
								?>
							</div>
						</td>
					</tr>
				</table>
			</center>
		</td>
	</tr>
</table>