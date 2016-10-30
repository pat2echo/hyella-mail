<?php
	$super = 0;
	
	$access = array();
	if( isset( $user_info["user_privilege"] ) && $user_info["user_privilege"] ){
		if( $user_info["user_privilege"] == "1300130013" ){
			$super = 1;
		}else{
			$functions = get_access_roles_details( array( "id" => $user_info["user_privilege"] ) );
			if( isset( $functions[ $user_info["user_privilege"] ]["accessible_functions"] ) ){
				$a = explode( ":::" , $functions[ $user_info["user_privilege"] ]["accessible_functions"] );
				if( is_array( $a ) && $a ){
					foreach( $a as $k => $v ){
						$access[ $v ] = $v;
					}
				}
			}
		}
	}
	
	$pr = get_project_data();
?>
 <style type="text/css">
	.datepicker-days th.dow,
	.datepicker-days td.day{
		font-size:10px !important;
	}
	.header-inner .nav-tabs > li > a{
		line-height:1;
	}
	.header-inner .portlet-tabs > .nav-tabs > li{
		float:left;
	}
	.header-inner .nav-tabs > li{
		float:left;
		margin-right:4px;
	}
	.header-inner .nav-tabs {
		margin-top:0px;
		margin-bottom:0px;
		margin-right:100px;
		margin-left:0px;
	}
	.header-inner .nav > li > a{
		padding:7px 15px;
		text-transform:uppercase;
		color:#000;
	}
	.header-inner .portlet.box > .portlet-title{
		padding:0;
		background:#fff;
		border-bottom-color:#ddd;
	}
	.header-inner .portlet > .portlet-title > .caption{
		font-size:12px;
		margin-bottom:0;
	}
	.header-inner .text-white{
		color:#fff;
	}
	/*.header-inner .btn-default:not(.btn-bordered){*/
	.header-inner .btn-default{
		border-width: 0px;
	}
	.header-inner .nav-tabs{
		border-bottom-color:transparent;
	}
	.header-inner .nav-tabs > li > a{
		border-bottom-color:transparent;
		border-bottom-width: 2px;
		margin-bottom: -1px;
	}
	.header-inner .nav-tabs > li.active > a, .header-inner .nav-tabs > li.active > a:hover, .header-inner .nav-tabs > li.active > a:focus{
		border-color:#ddd;
		border-bottom-color:#fff;
		border-bottom-width: 2px;
		margin-bottom: -1px;
	}
	body.login{
		background-color:#f8f8f8 !important;
		background:#f8f8f8 !important;
		overflow:hidden;
	}
	.header .hor-menu ul.nav li a{
		padding:5px 15px;
		font-size:12px;
	}
	
	.header .hor-menu ul.nav li a:hover,
	.header .hor-menu ul.nav li.active a{
		color:#fff;
	}
	#horizontal-nav{
		margin:10px;
	}
	a.top-bar-icon{
		background-color:#fff !important;
		border:1px solid #fff;
	}
	a.top-bar-icon:hover{
		background-color: #FaFeFF !important;
		border: 1px solid #47CCEA !important;
	}
	.border-right{
		border-right:1px solid #ddd;
	}
	.border-left{
		border-left:1px solid #ddd;
	}
	.align-left{
		text-align:left;
	}
	#dash-board-main-content-area .tile{
		height:185px;
	}
 </style>
 
 <!-- BEGIN HEADER -->   
   <div class="header navbar navbar-fixed-top1" style="background:#fff !important; height:auto;">
      <!-- BEGIN TOP NAVIGATION BAR -->
      <div class="header-inner" id="dashboard-menus">
		<div class="row">
			<div class="col-md-3">
				<div class="btn-group">
					<button type="button" class="btn btn-default"><i class="icon-cogs"></i></button>
				</div>
				<span style="font-size:0.8em;">ver <span id="project-version">2016</span></span>
			</div>
			<div class="col-md-6 text-white" style="background:#4D90FE !important; /*c:#4D90FE !important; b:#e02222 !important;*/">
				<p class="text-center" style="margin: 3px 0 8px 0;" id="secondary-display-title"><?php echo $pr["company_name"] . " - " . $pr["app_title"]; ?></p>
			</div>
			<div class="col-md-3" style="text-align:right;">
				<div class="btn-group" style="margin-right:5px;">
					<!--<a href="#" class="btn btn-default custom-action-button" function-id="79799510" function-class="help" function-name="display_help_library" module-id="12" module-name="Help" title="Help"><i class="icon-question"></i></a>-->
					<?php if( ! ( defined("HYELLA_NO_APP") && HYELLA_NO_APP ) ){ ?>
					<a href="../sign-in/" class="btn btn-sm dark" title="App Dashboard">FRONTEND <i class="icon-external-link"></i>&nbsp;</a>
					<?php } ?>
					
					<?php if( defined("HYELLA_WEB_COPY") && HYELLA_WEB_COPY ){ ?>
					<a href="?activity=update" class="btn btn-sm blue" title="License & Billing Info"><i class="icon-upload-alt"></i> License & Billing Info</a>
					<?php }else{ ?>
					<a href="?activity=update" class="btn btn-sm blue" title="Update Application"><i class="icon-upload-alt"></i> Update Application</a>
					<?php } ?>
					<!--
					<button type="button" class="btn btn-default" title="Minimize"><i class="icon-minus"></i></button>
					<button type="button" class="btn btn-default" title="Restore Down / Maximize"><i class="icon-fullscreen"></i></button>
					<button type="button" class="btn btn-default" title="Close"><i class="icon-remove"></i></button>
					-->
				</div>
			</div>
		</div>
		<?php 
			include "package/".HYELLA_PACKAGE."/dashboard-page.php";
		?>
	  </div>
      <!-- END TOP NAVIGATION BAR -->
   </div>
   <!-- END HEADER -->
   <div class="clearfix"></div>
   
  <div class="clearfix"></div> 
  <!-- BEGIN PAGE -->
  <div class="page-content1" id="dash-board-main-content-area" style="margin-right:11px;">  
	
  </div>
</div>
  <!-- END PAGE -->
  <div id="notification-container"></div>
 <script type="text/javascript">
	
</script>
