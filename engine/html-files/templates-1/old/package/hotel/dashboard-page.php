<div class="portlet box tabbable" style="background:#fff; margin-bottom:0; border-bottom:1px solid #ddd;">
	<div class="portlet-title">
		<div class="caption">
			<a style="margin-right:5px;" class="pull-left btn-sm btn blue" href="">RELOAD</a>
			<div class="pull-left">
				<ul class="nav nav-tabs">
					<?php
						$key = "10859350779";
						if( isset( $access[ $key ] ) || $super ){
					?>
				   <li class="active"><a href="#portlet_tab1" module-name="Main Menu" function-id="1" function-class="files" function-name="display_main_menu_full_view" class="custom-action-buttonAX" data-toggle="tab">MAIN MENU</a></li>
				   <?php } ?>
				   
				   <?php
						$key = "10859351455";
						if( isset( $access[ $key ] ) || $super ){
					?>
				   <li><a href="#portlet_tab2" module-name="Reports" function-id="1" function-class="files" function-name="display_main_menu_full_view" class="custom-action-buttonAX" data-toggle="tab">REPORTS</a></li>
				   <?php } ?>
				   
				   <?php
						$key = "10859352012";
						if( isset( $access[ $key ] ) || $super ){
					?>
				   <li><a href="#portlet_tab3" module-name="Reports" function-id="1" function-class="files" function-name="display_main_menu_full_view" class="custom-action-buttonAX" data-toggle="tab">APP SETTINGS</a></li>
				   <?php } ?>
				   
				   <?php
						$key = "11209887664";
						if( isset( $access[ $key ] ) || $super ){
					?>
				   <li><a href="#portlet_tab_vendors" module-name="Vendors & Expenditure Reports" function-id="1" function-class="files" function-name="display_main_menu_full_view" class="custom-action-buttonAX" data-toggle="tab">VENDORS & EXPENSES</a></li>
				   <?php } ?>
				   
				   <?php
						$key = "11209887664";
						if( isset( $access[ $key ] ) || $super ){
					?>
				   <li><a href="#portlet_tab_accounting" module-name="Financial Reports" function-id="1" function-class="files" function-name="display_main_menu_full_view" class="custom-action-buttonAX" data-toggle="tab">FINANCIAL ACCOUNTS</a></li>
				   <?php } ?>
				   
				   <?php
						$key = "10859352637";
						if( isset( $access[ $key ] ) || $super ){
					?>
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
						$key = "10858973873";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="customer_deposits" function-name="display_all_records_full_view" module-id="1412705497" module-name="Advance Deposits" title="Click Here to View / Record Customer Deposits">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Advance Deposits</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10858973873";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="payment" function-name="display_all_records_full_view" module-id="1412705497" module-name="Payment Manager" title="Click Here to View / Record Payment for Sales">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Payment</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key1 = "10858959262";
						if( isset( $access[ $key1 ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="82700" module-name="Hotel Check In" title="Click Here to View All Hotel Check In" function-id="1" function-class="hotel_checkin" function-name="display_all_records_full_view">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>&nbsp; Hotel Check-In &nbsp;</div>
						</a>
					</div>
					<?php } ?>
						
										
					<?php
						$key = "10858996623";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="sales" function-name="display_all_records_full_view" module-id="1412705497" module-name="Sales Manager" title="Click Here to View Sold Items">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Sales</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10858997671";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="inventory" function-name="display_all_records_full_view" module-id="1412705497" module-name="Event Manager" title="Click Here to Manage Inventory">
							<i class="icon-list"></i>
							<div>Inventory</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10858999561";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="production" function-name="display_all_records_full_view" module-id="1412705497" module-name="Production Manager" title="Click Here to View Produced Items / Materials Used">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Production & Utilization</div>
						</a>
					</div>
					<?php } ?>
					
				  </div>
				<!-- END HORIZANTAL MENU -->
			</div>
			
			<div class="tab-pane" id="portlet_tab2">
				  <!-- BEGIN HORIZANTAL MENU -->
				  <div class="row" style="margin:10px 0;">
					<?php
						$key = "10858963073";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="hotel_checkin" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports Manager" title="Click Here to View Hotel Room Reports">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Hotel Room Reports</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10858963073";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="sales" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports Manager" title="Click Here to View Sales Reports">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Sales Reports</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10858964162";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_all_batch_expenditure" module-id="1412705497" module-name="Reports Manager" title="Click Here to View Expenditure Reports">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Expenditure Reports</div>
						</a>
					</div>
					
					<?php } ?>
					
					<?php
						$key = "10858965439";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="inventory" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports Manager" title="Click Here to View Stock Level Reports">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Stock Levels</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10858967144";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="hotel_checkin" function-name="display_night_reports_full_view" module-id="1412705497" module-name="Reports Manager" title="Click Here to View Nightly Audits of All Transactions">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Night Audit</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10858969535";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Reports Manager" title="View Reports Generated by Me" function-id="1" function-class="reports" function-name="display_all_records_full_view">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Archived Reports</div>
						</a>
					</div>
					<?php } ?>
					
				</div>
				<!-- END HORIZANTAL MENU -->
			</div>
			
			<div class="tab-pane" id="portlet_tab3">
				  <!-- BEGIN HORIZANTAL MENU -->
				  <div class="row" style="margin:10px 0;">
					
					<?php
						$key = "10858980143";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="discount" function-name="display_all_records_full_view" module-id="1412705497" module-name="Discount Manager" title="Click Here to View / Modify Discounts for Sales">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Discount</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10858982656";
						$key1 = "10858987337";
						$key2 = "10858981458";
						$key3 = "10858983830";
						if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || isset( $access[ $key2 ] ) || isset( $access[ $key3 ] ) || $super ){
					?>
					<div class="col-sm-5 col-md-3 border-right" style="text-align:center;">
						<?php
							$key = "10858981458";
							$key1 = "10858983830";
							if( isset( $access[ $key1 ] ) || isset( $access[ $key ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							  <?php
								$key = "10858981458";
								if( isset( $access[ $key ] ) || $super ){
							?>
							 <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Store Manager" month-id="-" budget-id="" function-id="1" function-class="stores" function-name="display_all_records_full_view" title="Click Here to view stores"><i class="icon-cogs"></i> Stores</a>
							 <?php } ?>
							 
							  <?php
								$key = "10858983830";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Customers Manager" title="Click Here to View All Customers" function-id="1" function-class="customers" function-name="display_all_records_full_view"><i class="icon-book"></i> Customers</a>
							<?php } ?>
						</div>
						<?php } ?>
						
						<?php
							$key = "10858982656";
							$key1 = "10858987337";
							if( isset( $access[ $key1 ] ) || isset( $access[ $key ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							<?php
								$key = "10858982656";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Category Manager" title="Click Here to View All Categories" function-id="1" function-class="category" function-name="display_all_records_full_view"><i class="icon-list"></i> Items Category</a>
							<?php } ?>
							
							<?php
								$key = "10858987337";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Production Manager" title="Click Here to View All Produced Items / Raw Materials" function-id="1" function-class="production_items" function-name="display_all_records_full_view"><i class="icon-list"></i> Materials Utilized</a>
							<?php } ?>
							
						</div>
						<?php } ?>
					</div>
					<?php } ?>
					
					<?php
						$key = "9312041053";
						$key1 = "9312025618";
						$key2 = "10858988569";
						$key3 = "10858991624";
						if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || isset( $access[ $key2 ] ) || isset( $access[ $key3 ] ) || $super ){
					?>
					<div class="col-sm-5 col-md-3 border-right" style="text-align:center;">
						<?php
							$key = "10858988569";
							$key1 = "10858991624";
							if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							  <?php
								$key = "10858988569";
								if( isset( $access[ $key ] ) || $super ){
							?>
							 <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Hall Manager" month-id="-" budget-id="" function-id="1" function-class="vendors" function-name="display_all_records_full_view" title="Click Here to view vendors"><i class="icon-cogs"></i> Vendors</a>
							 <?php } ?>
							 
							  <?php
								/*
								$key = "10858991624";
								if( isset( $access[ $key ] ) || $super ){
								*/
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="assets" function-name="display_all_records_full_view" module-id="30642723443" module-name="Asset Register" title="Assets Register"><i class="icon-list-alt"></i> Assets</a>
							<?php //} ?>
						</div>
						<?php } ?>
						
						<?php
							$key = "10858990473";
							$key1 = "10858995428";
							if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							<?php
								$key = "10858990473";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Pay Roll Manager" title="Click Here to View All Pay Roll" function-id="1" function-class="pay_row" function-name="display_pay_roll_post_view"><i class="icon-list"></i> Pay Roll</a>
							<?php } ?>
							
							<?php
								/*
								$key = "10858995428";
								if( isset( $access[ $key ] ) || $super ){
								*/
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="assets_category" function-name="display_all_records_full_view" module-id="30642723443" module-name="Asset Register" title="Assets Categories"><i class="icon-list-alt"></i> Assets Category</a>
							<?php //} ?>
							
						</div>
						<?php } ?>
					</div>
					<?php } ?>

					<?php
						$key = "10859003347";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="items" function-name="display_all_records_full_view" module-id="1412705497" module-name="Inventory Manager" title="Click Here to View List of Items in Inventory">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>All Items</div>
						</a>
					</div>
					<?php } ?>
					
					
					<?php
						$key1 = "10858947236";
						$key = "10858950165";
						if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
					?>
					<div class="col-md-2 col-sm-3 border-right" style="text-align:center;">
						<?php
							$key1 = "10858947236";
							$key = "10858950165";
							if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
						?>
						
							 <?php
								$key = "10858947236";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<div class="btn-group btn-group-justified">
							 <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Hotel Rooms" month-id="-" budget-id="" function-id="1" function-class="hotel_room" function-name="display_all_records_full_view" title="Click Here to view hotel rooms"><i class="icon-cogs"></i> Hotel Rooms</a>
							 </div>
							 <?php } ?>
							 
							 <?php
								$key = "10858950165";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<div class="btn-group btn-group-justified">
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Hotel Room Type" title="Click Here to View All Hotel Room Types" function-id="1" function-class="hotel_room_type" function-name="display_all_records_full_view"><i class="icon-book"></i> Hotel Room Type</a>
							</div>
							<?php } ?>
							
						<?php } ?>
						
						
					</div>
					<?php } ?>
					
					<?php
						$key2 = "10858960226";
						$key3 = "10858956955";
						if( isset( $access[ $key2 ] ) || isset( $access[ $key3 ] ) || $super ){
					?>
					<div class="col-md-2 col-sm-4 border-right" style="text-align:center;">
						
						<?php
							$key = "10858960226";
							$key1 = "10858956955";
							if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
						?>
						
							<?php
								$key = "10858956955";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<div class="btn-group btn-group-justified">
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Hotel Room Type Check In" title="Click Here to View All Hotel Room Type Reservation" function-id="1" function-class="hotel_room_type_checkin" function-name="display_all_records_full_view"><i class="icon-list"></i> Hotel Room Type Reservation</a>
							</div>
							<?php } ?>
							
							<?php
								$key = "10858960226";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<div class="btn-group btn-group-justified">
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Hotel Room Check In" title="Click Here to View All Hotel Room Check In" function-id="1" function-class="hotel_room_checkin" function-name="display_all_records_full_view"><i class="icon-list"></i> Hotel Room Check In</a>
							</div>
							<?php } ?>
						
						<?php } ?>
					</div>
					<?php } ?>
					
				</div>
				
				
				<!-- END HORIZANTAL MENU -->
			</div>
			
			<div class="tab-pane" id="portlet_tab_vendors">
				  <!-- BEGIN HORIZANTAL MENU -->
				  <div class="row" style="margin:10px 0;">
					
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendors" function-name="display_all_records_full_view" module-id="1412705497" module-name="Vendors & Expenses" title="Click Here to View All Vendors">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Vendors</div>
						</a>
					</div>
					
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_all_draft_records_full_view" module-id="1412705497" module-name="Vendors & Expenses" title="Click Here to View New Draft Expense">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>New Draft Expense</div>
						</a>
					</div>
					
					<?php
						$key = "10858991624";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_all_records_full_view" module-id="1412705497" module-name="Vendors & Expenses" title="Click Here to View All Expenses">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>All Expenses</div>
						</a>
					</div>
					<!--
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_all_paid_records_full_view" module-id="1412705497" module-name="Vendors & Expenses" title="Click Here to View Paid Expenses">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Paid Expenses</div>
						</a>
					</div>
					
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_all_unpaid_records_full_view" module-id="1412705497" module-name="Vendors & Expenses" title="Click Here to View Unpaid Expenses">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Unpaid Expenses</div>
						</a>
					</div>
					-->
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure_payment" function-name="display_all_records_full_view" module-id="1412705497" module-name="Vendors & Expenses" title="Click Here to View Payment of Expenses">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Payment of Expenses</div>
						</a>
					</div>
					<?php } ?>
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
							<div>View Transactions</div>
						</a>
					</div>
					
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="post_new_transaction" module-id="1412705497" module-name="Transaction" title="Click Here to Post Transaction">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Post Transaction</div>
						</a>
					</div>
					<!--
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="debit_and_credit" function-name="display_all_records_full_view" module-id="1412705497" module-name="Layer Farm Manager" title="Click Here to View Debit & Credit">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Debit & Credit</div>
						</a>
					</div>
					-->
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_cash_book_full_view" module-id="1412705497" module-name="Cash Book Report" title="Click Here to View Cash Book Reports">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Cash Book Reports</div>
						</a>
					</div>
					
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Income Statement" title="Click Here to View Reports">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Financial Reports</div>
						</a>
					</div>
					
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_receivables_full_view" module-id="1412705497" module-name="Accounts Receivable" title="Click Here to View Reports">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Accounts Receivable</div>
						</a>
					</div>
					
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_payables_full_view" module-id="1412705497" module-name="Accounts Payable" title="Click Here to View Reports">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Accounts Payable</div>
						</a>
					</div>

					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Data Settings" title="View Reports Generated by Me" function-id="1" function-class="reports" function-name="display_all_records_full_view">
							<i class="icon-book" style="color:#d2322d;"></i>
							<div>Archived Reports</div>
						</a>
					</div>
					
					<?php
						$key = "10858990473";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-3 col-md-2 border-right" style="text-align:center;">
					
						<div class="btn-group btn-group-justified">
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Financial Accounts" title="Click Here to View All Pay Roll" function-id="1" function-class="pay_row" function-name="display_pay_roll_post_view"><i class="icon-list"></i> Pay Roll</a>
							
						</div>
						<div class="btn-group btn-group-justified">
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="pay_roll_post" function-name="display_all_records_full_view" module-id="30642723443" module-name="Financial Accounts" title="Pay Roll List"><i class="icon-list-alt"></i> Pay Roll Posting</a>
						</div>
					</div>
					<?php } ?>
				</div>
				<!-- END HORIZANTAL MENU -->
			</div>
			
			<div class="tab-pane" id="portlet_tab4">
				<div class="row" style="margin:10px 0;">
					 <?php
						$key = "10859007954";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users" function-name="display_all_records_full_view" module-id="1412705497" module-name="Users Manager" title="Users Manager" budget-id="-" month-id="-">
							<i class="icon-user" style="color:#d2322d;"></i>
							<div>&nbsp;Users Manager&nbsp;</div>
						</a>
					</div>
					<?php } ?>
					
					 <?php
						$key = "10859013761";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="audit" function-name="empty_database" module-id="1412705497" module-name="Empty Database" title="Click Here to Empty Database">
							<i class="icon-trash" style="color:#d2322d;"></i>
							<div>Empty Database</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10859015076";
						$key1 = "10859027774";
						$key2 = "10859016228";
						$key3 = "10859035524";
						if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || isset( $access[ $key2 ] ) || isset( $access[ $key3 ] ) || $super ){
					?>
					<div class="col-sm-5 col-md-3 border-right" >
						<?php
							$key = "10859015076";
							$key1 = "10859027774";
							if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							<?php
								$key = "10859015076";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="departments" function-name="display_all_records_full_view" module-id="30642723443" module-name="Settings" title="Designations / Departments"><i class="icon-list-alt"></i> Departments</a>
							<?php } ?>
							
							<?php
								$key = "10859027774";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="access_roles" function-name="display_all_records_full_view" module-id="30642723443" module-name="Access Roles" title="Access Roles"><i class="icon-list-alt"></i> Access Roles</a>
							<?php } ?>
							
						</div>
						<?php } ?>
						
						
						<?php
							$key1 = "10859035524";
							if( isset( $access[ $key1 ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="grade_level" function-name="display_all_records_full_view" module-id="30642723443" module-name="Grade Level" title="Grade Level"><i class="icon-list-alt"></i> Grade Level</a>
							
							<?php
								$key = "10859035524";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" function-id="8585015678" function-class="audit" function-name="display_all_records_full_view" module-id="8585007445" module-name="Audit Trail" title="Audit Trail"><i class="icon-list-alt"></i> Audit Trail</a>
							<?php } ?>
						</div>
						<?php } ?>
						
					</div>
					<?php } ?>
					
					<?php
						$key = "10859044552";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="general_settings" function-name="display_all_records_full_view" module-id="1412705497" module-name="Settings" title="General Settings Manager" budget-id="-" month-id="-">
							<i class="icon-cogs" style="color:#d2322d;"></i>
							<div>&nbsp;General Settings&nbsp;</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10859091476";
						$key1 = "10859089764";
						if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
					?>
					<div class="col-sm-4 col-md-2 border-left1 border-right" style="text-align:center;">
						<?php if( $super ){ ?>
						<div class="btn-group btn-group-justified">
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="functions" function-name="display_all_records_full_view" module-id="30642723443" module-name="App Settings" title="Functions"><i class="icon-list-alt"></i> Functions</a>
							
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="8585015678" function-class="modules" function-name="display_all_records_full_view" module-id="8585007445" module-name="App Settings" title="Modules"><i class="icon-list-alt"></i> Modules</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "10859091476";
							$key1 = "10859089764";
							if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							
							<?php
								$key = "10859089764";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="audit" function-name="import_db" module-id="30642723443" module-name="App Settings" title="Import Database"><i class="icon-list-alt"></i> Import Db</a>
							<?php } ?>
							
							<?php
								$key = "10859091476";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="audit" function-name="export_db" module-id="30642723443" module-name="App Settings" title="Export Database"><i class="icon-list-alt"></i> Export Db</a>
							<?php } ?>
							
						</div>
						<?php } ?>
						
					</div>
					<?php } ?>
					
					<?php
						$key = "10859016228";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="audit" function-name="refresh_cache" module-id="1412705497" module-name="Refresh App" title="Refresh App" budget-id="-" month-id="-">
							<i class="icon-refresh" style="color:#d2322d;"></i>
							<div>&nbsp;Refresh App&nbsp;</div>
						</a>
					</div>
					<?php } ?>
						
				</div>
					
			</div>
			
		</div>
	</div>
	<!-- END RIBBON -->
</div>