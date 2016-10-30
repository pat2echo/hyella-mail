<body contenteditable="true">
<div>
<?php include dirname( dirname( __FILE__ ) ) . "/globals/iframe-content-stylesheet-tmp.php"; ?>
</div>
<div class="body">

	<?php include dirname( dirname( __FILE__ ) ) . "/globals/iframe-content-stylesheet.php"; ?>

	<?php if( isset( $data["template"] ) && $data["template"] ){ 
		include $data["template"].".php";
	}else{ ?>
		<br style="line-height:0.5;"/>
		<div class="alert alert-warning">
			<h4><i class="icon-bell"></i> File Type Template Data Not Found</h4>
			<p>
			The selected file type template data was not found
			</p>
		</div>
	<?php } ?>

</div>
</body>