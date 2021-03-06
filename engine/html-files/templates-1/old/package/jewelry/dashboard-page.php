
        <div class="portlet box tabbable" style="background:#fff; margin-bottom:0; border-bottom:1px solid #ddd;">
			<div class="portlet-title">
				<div class="caption">
					<a style="margin-right:5px;" class="pull-left btn-sm btn blue" href="">RELOAD</a>
					<div class="pull-left">
						<ul class="nav nav-tabs">
						   <li class="active"><a href="#portlet_tab1" module-name="Main Menu" function-id="1" function-class="files" function-name="display_main_menu_full_view" class="custom-action-buttonAX" data-toggle="tab">MAIN MENU</a></li>
						   
						   <li><a href="#portlet_tab2" module-name="Reports" function-id="1" function-class="files" function-name="display_main_menu_full_view" class="custom-action-buttonAX" data-toggle="tab">REPORTS</a></li>
						   
						   
						   <?php
								$key = "9314877225";
								if( isset( $access[ $key ] ) || $super ){
							?>
						   <li><a href="#portlet_tab3" module-name="POS Settings" function-id="1" function-class="files" function-name="display_main_menu_full_view" class="custom-action-buttonAX" data-toggle="tab">POS SETTINGS</a></li>
						   
						   <li><a href="#portlet_tab_others" module-name="Other Settings" function-id="1" function-class="files" function-name="display_main_menu_full_view" class="custom-action-buttonAX" data-toggle="tab">OTHER SETTINGS</a></li>
						   
						   <li><a href="#portlet_tab_accounting" module-name="Financial Reports" function-id="1" function-class="files" function-name="display_main_menu_full_view" class="custom-action-buttonAX" data-toggle="tab">FINANCIAL ACCOUNTS</a></li>
						   
						   <li class=""><a href="#portlet_tab4" class="custom-action-buttonAX" function-id="8270082579" function-class="dashboard" function-name="display_main_menu_full_view" module-id="1412705497" module-name="Dashboard" data-toggle="tab">SYSTEM SETTINGS</a></li>
						   
							<?php } ?>
							
						   <!--
						   <li class=""><a href="#portlet_tab5" class="custom-action-button" function-id="8270082579" function-class="dashboard" function-name="display_main_menu_full_view" module-id="1412705497" module-name="Dashboard" data-toggle="tab">TRASH BIN</a></li>
						   -->
						</ul>
					</div>
				</div>
				
				<!-- BEGIN USER DROPDOWN -->
				<div class="pull-right btn-group">
					<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"><i class="icon-user"></i> <span id="user-info-user-name" class="username">Username</span> <i class="icon-angle-down"></i></button>
					<ul class="dropdown-menu">
						<li>
							<a href="#" class="custom-action-button" id="8270082573" function-id="8270082579" function-class="users" function-name="display_my_profile_manager" module-id="1412705497" module-name="Users Manager" title="My Profile Manager" budget-id="-" month-id="-">
								<i class="icon-user"></i> My Profile
							</a>
						  </li>
						  <li class="divider"></li>
						  <li><a href="sign_out?action=signout"><i class="icon-key"></i> Sign Out</a>
						 </li>
					</ul>
				</div>
				<!-- END USER DROPDOWN -->
				
			</div>
			<!-- BEGIN RIBBON -->
			<div class="portlet-body">
				<div class="tab-content">
					<div class="tab-pane active" id="portlet_tab1">
						  <!-- BEGIN HORIZANTAL MENU -->
						  <div class="row" style="margin:10px 0;">
							<?php
								$key = "9314801176";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<div class="col-md-1 border-right" style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="items" function-name="display_all_records_full_view" module-id="1412705497" module-name="Inventory Manager" title="Click Here to View List of Items in Inventory">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>All Items</div>
								</a>
							</div>
							<?php } ?>
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users" function-name="display_all_records_full_view" module-id="1412705497" module-name="Users Manager" title="Users Manager" budget-id="-" month-id="-">
									<i class="icon-user" style="color:#d2322d;"></i>
									<div>&nbsp;Users Manager&nbsp;</div>
								</a>
							</div>
							
							<div class="col-md-1 border-right" style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="audit" function-name="refresh_cache" module-id="1412705497" module-name="Refresh App" title="Click Here to Refresh App">
									<i class="icon-refresh" style="color:#d2322d;"></i>
									<div>Refresh App</div>
								</a>
							</div>
							
							<div class="col-md-1 border-right" style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="audit" function-name="empty_database" module-id="1412705497" module-name="Empty Database" title="Click Here to Empty Database">
									<i class="icon-trash" style="color:#d2322d;"></i>
									<div>Empty Database</div>
								</a>
							</div>
							
							<div class="col-md-1 border-right" style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="import_items" function-name="generate_excel_import_form" module-id="1412705497" module-name="Empty Database" title="Click Here to Import Items from Excel">
									<i class="icon-share" style="color:#d2322d;"></i>
									<div>Import Items</div>
								</a>
							</div>
							
							<div class="col-md-1 border-right" style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="items" function-name="display_all_records_full_view" module-id="1412705497" module-name="Inventory" title="Click Here to Import Items from Excel">
									<i class="icon-list"></i>
									<div>All Items</div>
								</a>
							</div>
							
						</div>
						
						
						<!-- END HORIZANTAL MENU -->
					</div>
					
					<div class="tab-pane" id="portlet_tab2">
						  <!-- BEGIN HORIZANTAL MENU -->
						  <div class="row" style="margin:10px 0;">
						  
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="sales" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Layer Farm Manager" title="Click Here to View Sales Reports">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Sales Reports</div>
								</a>
							</div>
							
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Layer Farm Manager" title="Click Here to View Sales Reports">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Expenditure Reports</div>
								</a>
							</div>
							
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="inventory" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Layer Farm Manager" title="Click Here to View Stock Level Reports">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Stock Levels</div>
								</a>
							</div>
							
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_income_expenditure_reports_full_view" module-id="1412705497" module-name="Layer Farm Manager" title="Click Here to View Farm Income vs Expenditure Analysis">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Income vs Expenditure</div>
								</a>
							</div>
							
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Data Settings" title="View Reports Generated by Me" function-id="1" function-class="reports" function-name="display_all_records_full_view">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Archived Reports</div>
								</a>
							</div>
							
						</div>
						<!-- END HORIZANTAL MENU -->
					</div>
					
					<div class="tab-pane" id="portlet_tab3">
						  <!-- BEGIN HORIZANTAL MENU -->
						  <div class="row" style="margin:10px 0;">
							
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="payment" function-name="display_all_records_full_view" module-id="1412705497" module-name="Payment Manager" title="Click Here to View / Record Payment for Sales">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Payment</div>
								</a>
							</div>
							
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="discount" function-name="display_all_records_full_view" module-id="1412705497" module-name="Discount Manager" title="Click Here to View / Modify Discounts for Sales">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Discount</div>
								</a>
							</div>
							
							<?php
								$key = "9312041053";
								$key1 = "9312025618";
								if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
							?>
							<div class="col-md-3 border-right" style="text-align:center;">
								<?php
									$key = "9312041053";
									if( isset( $access[ $key ] ) || $super ){
								?>
								<div class="btn-group btn-group-justified">
                                     <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Store Manager" month-id="-" budget-id="" function-id="1" function-class="stores" function-name="display_all_records_full_view" title="Click Here to view stores"><i class="icon-cogs"></i> Stores</a>
									 
                                    <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Customers Manager" title="Click Here to View All Customers" function-id="1" function-class="customers" function-name="display_all_records_full_view"><i class="icon-book"></i> Customers</a>
                                </div>
								<?php } ?>
								
								<?php
									$key = "9312025618";
									if( isset( $access[ $key ] ) || $super ){
								?>
								<div class="btn-group btn-group-justified">
                                    <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Category Manager" title="Click Here to View All Categories" function-id="1" function-class="category" function-name="display_all_records_full_view"><i class="icon-list"></i> Category</a>
                                    
									<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Production Manager" title="Click Here to View All Produced Items / Raw Materials" function-id="1" function-class="production_items" function-name="display_all_records_full_view"><i class="icon-list"></i> Materials Utilized</a>
									
                                </div>
								<?php } ?>
							</div>
							<?php } ?>
							
							<?php
								$key = "9312041053";
								$key1 = "9312025618";
								if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
							?>
							<div class="col-md-3 border-right" style="text-align:center;">
								<?php
									$key = "9312041053";
									if( isset( $access[ $key ] ) || $super ){
								?>
								<div class="btn-group btn-group-justified">
                                     <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Hall Manager" month-id="-" budget-id="" function-id="1" function-class="vendors" function-name="display_all_records_full_view" title="Click Here to view vendors"><i class="icon-cogs"></i> Vendors</a>
									 
                                    <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Hall Manager" title="Click Here to View All Expenditures" function-id="1" function-class="expenditure" function-name="display_all_records_full_view"><i class="icon-book"></i> Expenditures</a>
                                </div>
								<?php } ?>
								
								<?php
									$key = "9312025618";
									if( isset( $access[ $key ] ) || $super ){
								?>
								<div class="btn-group btn-group-justified">
                                    <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Hall Manager" title="Click Here to View All Pay Roll" function-id="1" function-class="pay_row" function-name="display_all_records_full_view"><i class="icon-list"></i> Pay Roll</a>
									
                                    <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Sales Manager" title="Click Here to View All Sold Items" function-id="1" function-class="sales_items" function-name="display_all_records_full_view"><i class="icon-book"></i> Sold Items</a>
                                </div>
								<?php } ?>
							</div>
							<?php } ?>
							
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="sales" function-name="display_all_records_full_view" module-id="1412705497" module-name="Sales Manager" title="Click Here to View Sold Items">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Sales</div>
								</a>
							</div>
							
							<div class="col-md-1 border-right" style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="inventory" function-name="display_all_records_full_view" module-id="1412705497" module-name="Event Manager" title="Click Here to Manage Inventory">
									<i class="icon-list"></i>
									<div>Inventory</div>
								</a>
							</div>
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="production" function-name="display_all_records_full_view" module-id="1412705497" module-name="Production Manager" title="Click Here to View Produced Items / Materials Used">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Production Line</div>
								</a>
							</div>
							
						</div>
						
						
						<!-- END HORIZANTAL MENU -->
					</div>
					
					<div class="tab-pane" id="portlet_tab_accounting">
						  <!-- BEGIN HORIZANTAL MENU -->
						  <div class="row" style="margin:10px 0;">
							
							<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="chart_of_accounts" function-name="display_all_records_full_view" module-id="1412705497" module-name="Chart of Accounts" title="Click Here to View Chart of Accounts">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Chart of Accounts</div>
								</a>
							</div>
							
							<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_records_full_view" module-id="1412705497" module-name="Transaction" title="Click Here to View Transactions">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Transactions</div>
								</a>
							</div>
							
							<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="debit_and_credit" function-name="display_all_records_full_view" module-id="1412705497" module-name="Layer Farm Manager" title="Click Here to View Debit & Credit">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Debit & Credit</div>
								</a>
							</div>
							
							<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Income Statement" title="Click Here to View Reports">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Financial Reports</div>
								</a>
							</div>
							
							<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_income_expenditure_reports_full_view" module-id="1412705497" module-name="Layer Farm Manager" title="Click Here to View Farm Income vs Expenditure Analysis">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Income vs Expenditure</div>
								</a>
							</div>
							
							<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Data Settings" title="View Reports Generated by Me" function-id="1" function-class="reports" function-name="display_all_records_full_view">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Archived Reports</div>
								</a>
							</div>
							
						</div>
						<!-- END HORIZANTAL MENU -->
					</div>
					
					<div class="tab-pane" id="portlet_tab_others">
						  <!-- BEGIN HORIZANTAL MENU -->
						  <div class="row" style="margin:10px 0;">
							
							<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="repairs" function-name="display_all_records_full_view" module-id="1412705497" module-name="Repair Jobs Manager" title="Click Here to View Repair Jobs">
									<i class="icon-book" style="color:#d2322d;"></i>
									<div>Repair Jobs</div>
								</a>
							</div>
							
							<div class="col-sm-4 col-md-3 border-right" style="text-align:center;">
								
								<div class="btn-group btn-group-justified">
                                     <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Customers Manager" month-id="-" budget-id="" function-id="1" function-class="customer_call_log" function-name="display_all_records_full_view" title="Click Here to View Customer Call Log"><i class="icon-cogs"></i> Customer Call Log</a>
									 
                                    <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Customers Manager" title="Click Here to View Customer Wish List" function-id="1" function-class="customer_wish_list" function-name="display_all_records_full_view"><i class="icon-book"></i> Customer Wish List</a>
                                </div>
								
							</div>
							
						</div>
						
						
						<!-- END HORIZANTAL MENU -->
					</div>
					
					<div class="tab-pane" id="portlet_tab4">
						<div class="row" style="margin:10px 0;">
							 <?php
								$key = "9314801176";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<div class="col-md-1 border-right  " style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users" function-name="display_all_records_full_view" module-id="1412705497" module-name="Users Manager" title="Users Manager" budget-id="-" month-id="-">
									<i class="icon-user" style="color:#d2322d;"></i>
									<div>&nbsp;Users Manager&nbsp;</div>
								</a>
							</div>
							<?php } ?>
							
							<div class="col-md-3 border-right" >
								<?php
									$key = "9314805175";
									$key1 = "9314810073";
									if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
								?>
								<div class="btn-group btn-group-justified">
                                    <?php
										$key = "9314805175";
										if( isset( $access[ $key ] ) || $super ){
									?>
									<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="departments" function-name="display_all_records_full_view" module-id="30642723443" module-name="Settings" title="Designations / Departments"><i class="icon-list-alt"></i> Departments</a>
									<?php } ?>
									
									<?php
										$key = "9314810073";
										if( isset( $access[ $key ] ) || $super ){
									?>
                                    <a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="access_roles" function-name="display_all_records_full_view" module-id="30642723443" module-name="Access Roles" title="Access Roles"><i class="icon-list-alt"></i> Access Roles</a>
									<?php } ?>
									
                                </div>
								<?php } ?>
								
								
								<?php
									$key = "9315523110";
									$key1 = "9314812228";
									if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
								?>
								<div class="btn-group btn-group-justified">
                                    
                                    <?php
										$key = "9369099038";
										if( isset( $access[ $key ] ) || $super ){
									?>
                                    <a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="audit" function-name="refresh_cache" module-id="30642723443" module-name="Refresh App" title="Refresh App"><i class="icon-refresh"></i> Refresh App</a>
									<?php } ?>
									
									<?php
										$key = "9314812228";
										if( isset( $access[ $key ] ) || $super ){
									?>
                                    <a href="#" class="btn btn-sm btn-default custom-action-button align-left" function-id="8585015678" function-class="audit" function-name="display_all_records_full_view" module-id="8585007445" module-name="Audit Trail" title="Audit Trail"><i class="icon-list-alt"></i> Audit Trail</a>
									<?php } ?>
                                </div>
								<?php } ?>
								
							</div>
							<div class="col-md-2 border-right" >
								
								<?php
									$key = "9314818216";
									$key1 = "9314821660";
									if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
								?>
								<div class="btn-group btn-group-justified">
									
									<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="emails" function-name="display_all_records_full_view" module-id="30642723443" module-name="App Settings" title="Emails Report"><i class="icon-envelope"></i> Sent Mails</a>
									
									<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="cities_list" function-name="display_all_records_full_view" module-id="30642723443" module-name="Settings" title="Manage Cities"><i class="icon-list-alt"></i> Cities</a>
									
                                </div>
								<div class="btn-group btn-group-justified">
									
									<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="country_list" function-name="display_all_records_full_view" module-id="30642723443" module-name="Settings" title="Manage Countries"><i class="icon-list-alt"></i> Countries</a>
									
									<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="state_list" function-name="display_all_records_full_view" module-id="30642723443" module-name="Settings" title="Manage States"><i class="icon-list-alt"></i> States</a>
									
                                </div>
								<?php } ?>
							</div>
							
							<?php
								$key = "9314824353";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<div class="col-md-1 border-right" style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="general_settings" function-name="display_all_records_full_view" module-id="1412705497" module-name="Settings" title="General Settings Manager" budget-id="-" month-id="-">
									<i class="icon-cogs" style="color:#d2322d;"></i>
									<div>&nbsp;General Settings&nbsp;</div>
								</a>
							</div>
							<?php } ?>
							
							
							<div class="col-md-2 border-left1 border-right" style="text-align:center;">
								<?php if( $super ){ ?>
								<div class="btn-group btn-group-justified">
                                    <a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="functions" function-name="display_all_records_full_view" module-id="30642723443" module-name="App Settings" title="Functions"><i class="icon-list-alt"></i> Functions</a>
									
									<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="8585015678" function-class="modules" function-name="display_all_records_full_view" module-id="8585007445" module-name="App Settings" title="Modules"><i class="icon-list-alt"></i> Modules</a>
                                </div>
								<?php } ?>
								
								<div class="btn-group btn-group-justified">
                                    <?php if( $super ){ ?>
									<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="audit" function-name="import_db" module-id="30642723443" module-name="App Settings" title="Import Database"><i class="icon-list-alt"></i> Import Db</a>
									<?php } ?>
									
									<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="audit" function-name="export_db" module-id="30642723443" module-name="App Settings" title="Export Database"><i class="icon-list-alt"></i> Export Db</a>
                                </div>
								
							</div>
							
						</div>
							
					</div>
					
				</div>
            </div>
			<!-- END RIBBON -->
		</div>