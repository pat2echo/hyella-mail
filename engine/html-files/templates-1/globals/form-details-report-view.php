<style type="text/css">
#extra-tab label,
#report-card-home label,
#form-content-area label {
    font-size: 10px;
    font-weight: 600;
}
#extra-tab h3,
#report-card-home h3,
#form-content-area h3,
#data-entry-form-container h3{
	font-size:14px !important;
	font-weight:600 !important;
	margin-top:0;
	border-bottom:1px solid #ddd;
}
#extra-tab .input-row,
#report-card-home .input-row,
#form-content-area .input-row{
	padding-left:0px !important;
	padding-right:5px !important;
}
select.modified-height{
	height:150px !important;
}
 </style>
           <div class="portlet">
            <?php if( ! ( isset( $data['hide_main_title'] ) && $data['hide_main_title'] ) ){ ?>
			<div class="portlet-title">
             <div class="caption"><i class="icon-bell"></i><span id="secondary-display-title">Title</span></div>
             <div class="tools">
                <a href="" class="collapse" ></a>
                <a href="#portlet-config" data-toggle="modal" class="config"></a>
                <a href="" class="reload"></a>
                <a href="" class="remove"></a>
             </div>
			 
          </div>
			<?php } ?>
          <div class="portlet-body">
            <div class="tabbable tabbable-custom">
                <ul class="nav nav-tabs">
                   <li class="active"><a id="form-home-control-handle" data-toggle="tab" href="#form-home"><?php if( isset( $data['form_title'] ) && $data['form_title'] )echo $data['form_title']; else echo "Form"; ?></a></li>
				   <?php if( ! ( isset( $data['hide_details_tab'] ) && $data['hide_details_tab'] ) ){ ?>
                   <li><a id="record-details-home-control-handle" data-toggle="tab" href="#record-details-display-home">Details</a></li>
				   <?php } ?>
                   <?php if( ! ( isset( $data['hide_reports_tab'] ) && $data['hide_reports_tab'] ) ){ ?>
				   <li><a id="report-card-home-control-handle" data-toggle="tab" href="#report-card-home"><?php if( isset( $data['hide_reports_tab_title'] ) && $data['hide_reports_tab_title'] )echo $data['hide_reports_tab_title']; else echo "Reports"; ?></a></li>
                   <?php } ?>
                   <?php if( ( isset( $data['show_extra_tab'] ) && isset( $data['show_extra_tab_title'] ) && $data['show_extra_tab'] ) ){ ?>
				   <li><a id="extra-tab-control-handle" data-toggle="tab" href="#extra-tab"><?php echo $data['show_extra_tab_title']; ?></a></li>
                   <?php } ?>
				   <?php if( ! ( isset( $data['hide_clear_tab'] ) && $data['hide_clear_tab'] ) ){ ?>
				   <li><button id="clear-tab-contents" title="Clear Open Tab Contents" class="pull-right btn-danger btn btn-mini btn-xs">Clear Tab</button></li>
				   <?php } ?>
                </ul>
                <div class="tab-content resizable-height" style=" overflow-y:auto;">
                    <div class="tab-pane active" id="form-home">
                        <div id="form-content-area" class="tab-content-to-clear">
							<?php if( isset( $data['form_data'] ) && $data['form_data'] ){ echo $data['form_data']; }else{  ?>
							<br style="line-height:0.5;"/>
							<div class="alert alert-info alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4>Data Capture Form Area</h4>
								<p>
								When you click the <strong>"Add New Record"</strong> or <strong>"Edit Record"</strong> button, THE DATA CAPTURE FORM WILL BE DISPLAYED IN THIS AREA
								</p>
							</div>
							<?php } ?>
						</div>
                    </div>    
                  <div class="tab-pane" id="record-details-display-home" style="min-height:200px;">
                    <div class="btn-group">
                        
                        <button class="btn btn-xs quick-print" target="#record-details-home" title="Print">
                            <i class="icon-print"></i> Print
                        </button>
                        <button class="btn btn-xs pop-up-button" data-toggle="popover" data-placement="bottom" title="Show / Hide Fields">
                            Show / Hide Fields
                            <span class="caret"></span>
                            
                            <div class="pop-up-content" style="display:none;">
                                <ul id="record-details-field-selector"  style="margin:0; list-style:none;">
                                    <li>Initializing...</li>
                                </ul>
                            </div>
                        </button>
                    </div>
                    <div id="record-details-home" class="tab-content-to-clear">
                        
                    </div>
                  </div>
                  
                  <div class="tab-pane tab-content-to-clear" id="report-card-home" >
                    <?php if( isset( $data['reports_tab_data'] ) && $data['reports_tab_data'] ){ echo $data['reports_tab_data']; }  ?>
                  </div>
				  
				  <?php if( ( isset( $data['show_extra_tab'] ) && $data['show_extra_tab'] ) ){ ?>
                  <div class="tab-pane tab-content-to-clear" id="extra-tab" >
                    <?php if( isset( $data['extra_tab_data'] ) && $data['extra_tab_data'] ){ echo $data['extra_tab_data']; }  ?>
                  </div>
				  <?php } ?>
                </div>
          </div>
		  </div>
			
	 </div>
		