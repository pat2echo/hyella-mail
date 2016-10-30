
<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<div class="form-control-table">
<?php 
	if( isset( $data['html'] ) && isset( $data['html'] ) ){		
		echo $data['html'];
	}
	
	if( isset( $data['educational'] ) && isset( $data['educational'] ) ){		
		echo $data['educational'];
	}
	
	if( isset( $data['work'] ) && isset( $data['work'] ) ){		
		echo $data['work'];
	}
	
?>
</div>
<script type="text/javascript" class="auto-remove">
	var active_tab = 1;
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>