<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<div class="container">
	<div class="row">
	<div class="col-md-6">
		<?php
			if( isset( $data['verification_data'] ) && $data['verification_data'] && ( ! is_array($data['verification_data']) ) ){
				echo $data['verification_data'];
			}else{
		?>
			<img src="<?php echo $display_pagepointer; ?>frontend-assets/img/sliders/revolution/man-winner.png" style="max-width:80%; margin-top:40px;" />
		<?php 
			}
		?>
	</div>
	<div class="col-md-6">
		 <a class="btn btn-success pull-right btn-sm" href="?page=register" title="<?php echo SITE_USERS_SIGN_UP_LINK; ?>"><?php echo SITE_USERS_SIGN_UP_LINK; ?></a>
		 <h3><?php echo SITE_USERS_SIGN_IN; ?> &darr;</h3>
		
		<div class="shade">
			<?php if( isset( $data['html'] ) )echo $data['html']; ?>
			<div style="clear:both;"></div>
			<hr />
			<br />
			<div class="btn-group pull-right-desktop">
				<?php
					$field = 'ALLOW FACEBOOK SIGNIN';
					if( isset( $data[ 'general_settings' ][ $field ][ 'default' ] ) && $data[ 'general_settings' ][ $field ][ 'default' ] == 'TRUE' && isset( $data['service_url'][$field] ) && $data['service_url'][$field] ){
				?>
				<a href="<?php echo $data['service_url'][$field]; ?>" class="btn btn-primary"><?php echo SITE_USERS_SIGN_IN_WITH_FACEBOOK; ?></a>
				<?php
				}
				?>
				<?php
					$field = 'ALLOW GMAIL SIGNIN';
					if( isset( $data[ 'general_settings' ][ $field ][ 'default' ] ) && $data[ 'general_settings' ][ $field ][ 'default' ] == 'TRUE' && isset( $data['service_url'][$field] ) && $data['service_url'][$field] ){
				?>
				<a href="<?php echo $data['service_url'][$field]; ?>" id="click-me" class="btn btn-danger"><?php echo SITE_USERS_SIGN_IN_WITH_GMAIL; ?></a>
				<?php
				}
				?>
			</div>
		</div>
		
	  <div style="clear:both;"></div>
	<?php 
		if( isset( $data['form_data'] ) ){		
			echo $data['form_data'];
		}
	?>
	</div>
	</div>
</div>
<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>