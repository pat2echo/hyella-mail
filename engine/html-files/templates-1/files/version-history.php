<?php if( isset( $data["report_version_info"] ) )$v = $data["report_version_info"]; ?>
<li>
	<a href="<?php echo $site_url . $v["file"]; ?>" title="<?php echo $v["version_title"]; ?>" target="divisional_report">
	<small><?php echo $v["version_title"]; ?></small>
	</a>
</li>