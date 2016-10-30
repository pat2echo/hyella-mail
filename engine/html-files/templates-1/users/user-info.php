<?php
			//print_r($data["user_info"]);
		?>
<div class="row" style="background:#fff; padding-bottom:20px; border:1px solid #ddd;">
	<div class="col-md-3" style="text-align:center;">
		<?php 
			$key = "photograph"; if( isset( $data["user_info"][$key] ) && $data["user_info"][$key] ){
		?>
			<img src="<?php echo $data["user_info"][$key]; ?>" style="max-width:100%; margin-top:20px;" /> 
			<?php
		}else{
			?>
			<h3 >
			&nbsp;. <i class="icon-user" style="font-size:3em;">&nbsp;</i>&nbsp;
			</h3>
			<?php
		} ?> 
	</div>
	<div class="col-md-9">
		<h3>
			<?php $key = "firstname"; if( isset( $data["user_info"][$key] ) && $data["user_info"][$key] )echo $data["user_info"][$key]; ?> 
			<?php $key = "lastname"; if( isset( $data["user_info"][$key] ) && $data["user_info"][$key] )echo $data["user_info"][$key]; ?>
			<div style="margin-top:10px;">
			<small>
			<a href="mailto:<?php $key = "email"; if( isset( $data["user_info"][$key] ) && $data["user_info"][$key] )echo $data["user_info"][$key]; ?>"><i class="icon-envelope"></i> <?php $key = "email"; if( isset( $data["user_info"][$key] ) && $data["user_info"][$key] )echo $data["user_info"][$key]; ?></a>
			<br />
			<br />
			<?php $key = "role"; if( isset( $data["user_info"][$key] ) && $data["user_info"][$key] )echo "<strong>ACCESS ROLE:</strong> " . strtoupper($data["user_info"][$key]); ?>
			<br />
			<br />
			<?php $key = "department"; if( isset( $data["user_info"][$key] ) && $data["user_info"][$key] )echo "<strong>DEPARTMENT:</strong> " . get_select_option_value( array( "id" => $data["user_info"][$key], "function_name" => "get_departments" ) ) ; ?>
			</small>
			</div>
		</h3>
	</div>
</div>