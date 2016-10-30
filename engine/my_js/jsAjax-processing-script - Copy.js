/*
 * JavaScript Dashboard Class
 * Created On: 15-JULY-2013
 * Created By: Ogbuitepu O. Patrick
 *
	WebBrowser1.Navigate("http://datamanagement.northwindproject.com")
	WebControl1.Source = New Uri("http://datamanagement.northwindproject.com")
 *
*/
$( document ).ready( function() {
	var current_module = '1357383943_1';
	var current_record = '';
	
	//Store HTML ID of Last Clicked Function
	var clicked_menu = '';
	
	//Store HTML ID of Last Clicked Popup Function
	var clicked_main_menu = '';
	
	//Name of Table to Search
	var search_table = '';
	
	//Name of Table to Toggle its Columns
	var column_toggle_table = '';
	
	//Number of Column to toggle
	var column_toggle_num = '';
	
	//Name of Column to toggle
	var column_toggle_name = '';
	
	//Selected Record ID
	var single_selected_record = '';
	
	//Selected Records IDs
	var multiple_selected_record_id = '';
	
	//Selected Records Details
	var details_of_multiple_selected_records = '';
	
	var class_action = '';
	var class_name = '';
	var module_id = '';
	
	var pagepointer = $('#pagepointer').text();
	
	var clicked_action_button = '';
	var confirm_action_prompt = 1;
	
	var form_method = 'get';
	var ajax_data_type = 'json';
	var ajax_data = '';
	var ajax_get_url = '';
	var ajax_action = '';
	var ajax_container;
	var ajax_notice_container;
	
	//AJAX Request Data Before Sending
	var ajax_request_data_before_sending_to_server = '';
	
	var function_click_process = 1;
	
	var cancel_ajax_recursive_function = false;
	
	var oTable;
	
	var oNormalTable;
	
	//Last Position of Mouse on mouseup event
	var last_position_of_mouse_on_mouse_up_x = 0;
	var last_position_of_mouse_on_mouse_up_y = 0;
	
	//Variable to determine if entity is being renamed
	var renaming_entity_in_progress = 0;
	
	//Currently Opened Label
	var currently_opened_label_id_in_report_letters_memo = '';
	
	var editted_entity_source = '';
	
	//Determine if Menus have been bound to actions after initialization
	var bound_menu_items_to_actions = 0;
	
	var test_view_entity = 0;
	
	//Variable that determines the number of times notifications have to be closed prior to status update
	var update_notifications_to_read = 0;
	
	//Variable that determines whether to archive dataset
	var archive_dataset = 0;
	
	//Variable that determines the currently edited textarea
	var editing_textarea;
	
	//Variable that determines the currently view port opened in reports , letters & memo
	var report_letters_memo_current_view = '';
	
	//Request Modules
	function generate_modules(){
		ajax_data = {action:'modules', todo:'display'};
		form_method = 'get';
		ajax_data_type = 'json';
		ajax_action = 'generate_modules';
		ajax_container = '';
		ajax_send();
	};
    
	function populate_dashbaord(){
		ajax_data = {action:'dashboard', todo:'populate_dashboard'};
		form_method = 'get';
		ajax_data_type = 'json';
		ajax_action = 'request_function_output';
		ajax_container = '';
		ajax_send();
	};
	
	get_properties_of_school();
	function get_properties_of_school(){
		var t = '';
        if( $('#rollover-for-auth') && $('#rollover-for-auth').val() ){
            t = $('#rollover-for-auth').val();
        }
        
		if( $('#update-app') && $('#update-app').val() ){
			set_function_click_event();
            return false;
        }
		
        ajax_data = {action:'appsettings', todo:'get_appsettings', tt:t };
		form_method = 'get';
		ajax_data_type = 'json';
		ajax_action = 'request_function_output';
		ajax_container = '';
		ajax_send();
	};
	
	//FLAG Records
	function flag_certain_records(){
		var $rows = $('#example').find('tr');
		
		$rows.each( function(){
			var flag = $(this).find('span.flag:first').attr('flag');
			var flags = $(this).find('span.flag').length;
			
			if( flags > 1 ){
				$(this).addClass( flag );
			}
		});
		
	};
	
	//Rehighlight Selected Record
	function rehighlight_selected_record_function( $context ){
		
		var $selected_record = $('#'+single_selected_record).parents('tr');
		
		if($selected_record){
			
			$selected_record.removeClass('row_selected');
			
			//Select Record Click Event
			$clicked_element_parent = $context;
			$clicked_element_group_selector = 'tr';
			
			var shiftctrlKey = false;
			select_record_click_event($clicked_element_parent, $selected_record , $clicked_element_group_selector, shiftctrlKey);
			
		}
	};
	
	//Quick Edit Form
	function activate_quick_edit_form_submit(){
		
		activate_tooltip_for_form_element( $('#form-content-area').find('form').not('.ajax-activated') );
		activate_validation_for_required_form_element( $('#form-content-area').find('form').not('.ajax-activated') );
		
		//Bind Form Submit Event
		$('form.quick-edit-form')
		.not('.ajax-activated')
		.bind('submit', function( e ){
			e.preventDefault();
			
			$('form.quick-edit-form').data('pending', 0 );
			submit_form_data( $(this) );	
		})
		.addClass("ajax-activated");
		
		//Activate Ajax file upload
		createUploader();
		
		//Bind Form Change Event
		$('form.quick-edit-form')
		.find('.form-gen-element')
		.bind('change', function( e ){
			var id = $('form.quick-edit-form').find('input#id').val();
			
			var form_name = $('form.quick-edit-form').attr("name");
			var attr_name = $(this).attr('name');
			
			var $element = $( '#'+id+'-'+attr_name ).parent('td');
			
			if( ! $element.is(":visible") ){
				switch( form_name ){
				case "cash_calls":
					if( attr_name == "cash_calls003" )attr_name = "description";
					if( attr_name == "cash_calls002" )attr_name = "code";
				break;
				}
				
				var $element = $( '#'+id+'-'+attr_name ).parent('td');
			}
			
			$( '#'+id+'-'+attr_name ).attr('real-value', $(this).val() );
			
			var text = $element.text();
			var html = $element.html().replace( text , '' );
			
			$element.html( $(this).val()+''+html );
			
			if( $(".details-section-container-row-"+attr_name) ){
				$(".details-edit").removeClass("details-edit");
				
				$('table.main-details-table-'+id)
				.find(".details-section-container-row-"+attr_name)
				.addClass("details-edit")
				.find(".details-section-container-value")
				.text( $(this).val() );
			}

			$('form.quick-edit-form').data('pending', 1 );
		})
		.bind('keyup', function(e){ 
			
			switch(e.keyCode){
			case 13:	//Enter key
			case 35:	//End key
			case 36:	//Home key
			case 37:	//Left arrow
			case 38:	//Up arrow
			case 39:	//Right arrow
			case 40:	//Down arrow
			case 34:	//Page down button
			case 33:	//Page up button
			case 65:	//Enter Key
			break;
			default:
				$(this).change();
			break;				
			}
		});
		
	};
	
	//Form Submission
	function select_record_click_function( $context ){
		if( ! $context ){
			var data_table_id = getDataTableID( 1 );
			$context = $('#'+data_table_id);
		}
		
		$context.find('tr').off('click');
		$context.find('tr td').off('dblclick');
		
		//Bind Row Click Event
		$context
		.find('tr')
		.on('click',function(e){
			//Select Record Click Event
			$clicked_element_parent = $(this).parents('table');
			$clicked_element_group_selector = 'tr';
			
			if( $(this).hasClass("line-items-total-row") || $(this).hasClass("line-items-space-row") ){
				return false;
			}
			
			var shiftctrlKey = false;
			if( e.ctrlKey || e.shiftKey )
				shiftctrlKey = true;
			
			select_record_click_event( $clicked_element_parent, $(this) , $clicked_element_group_selector, shiftctrlKey );
			
			if( single_selected_record && $('form.quick-edit-form') && $('form.quick-edit-form').is(':visible') ){
				if( $('form.quick-edit-form').data('pending') )$('form.quick-edit-form').submit();
				populate_form_with_datatable_values( $('form.quick-edit-form'), $(this).find('.datatables-cell-id:eq(2)').parent('td') );
				
				$('form.quick-edit-form')
				.find('.form-gen-element:focus')
				.focus();
			}
			
			if( single_selected_record ){
				scroll_to_top_of_selected_record();
			}
		});
		
		/*
		$context
		.find('tr')
		.find('td')
		.on('dblclick', function(e){
			//check if edit record button is visible
			if( $('a#edit-selected-record') && $('a#edit-selected-record').is(':visible') ){
				$clicked_element_parent = $(this).parents('table');
				$clicked_element_group_selector = 'tr';
			
				var shiftctrlKey = false;
				
				select_record_click_event($clicked_element_parent, $(this).parents('tr') , $clicked_element_group_selector, shiftctrlKey);
				
				$form
				.find('.form-gen-element')
				.attr('disabled', true);
				
				populate_form_with_datatable_values( $('#inline-edit-form-wrapper'), $(this) );
			}
		});*/
		
		$context
		.parents('.dataTables_scrollBody')
		.bind('scroll' , function(){
			var scrollpos = $(this).scrollLeft();
			
			var scrollwidth_lower = $(this).width();
			var scrollwidth_upper = $(this).siblings('.dataTables_scrollHead').find("table.display").width();
			
			var scrollwidth = scrollwidth_upper - scrollwidth_lower;
			
			if( scrollpos > scrollwidth ){
				$(this)
				.scrollLeft( scrollwidth );
			}
		});
		
	};
	
	function populate_form_with_datatable_values( $form, $me ){
		//$('#inline-edit-form-wrapper')
		
		//if( $('a#edit-selected-record') && $('a#edit-selected-record').is(':visible') ){
			//clear normal form content
			$('#form-content-area').html('');
			
			$("ul.qq-upload-list")
			.empty();
			
			$form
			.find('input[alt="file"]')
			.val('');
			
			//display inline edit form
			$me
			.parents('tr')
			.addClass('inline-edit-in-progress');
			
			var input_id = $me.find('.datatables-cell-id').val();
			var record_id = $me.find('.datatables-cell-id').attr('jid');
			
			$form
			.find('input[name="id"]')
			.val( record_id );
			
			var $input = $form.find('.'+input_id);
			var max_value = "";
			
			switch(input_id){
			case "code":
				input_id = "cash_calls002";
			break;
			case "description":
				input_id = "cash_calls003";
			break;
			case "budget_details009":
				max_value = ( $me.siblings().find( '#'+record_id+'-budget_details007' ).parent("td").text().replace( /[^0-9\-\.]/g, '' ) ) * 1;
			break;
			case "budget_details010":
				max_value = ( $me.siblings().find( '#'+record_id+'-budget_details008' ).parent("td").text().replace( /[^0-9\-\.]/g, '' ) ) * 1;
			break;
			case "budget_details011":
				max_value = ( $me.siblings().find( '#'+record_id+'-budget_details009' ).parent("td").text().replace( /[^0-9\-\.]/g, '' ) ) * 1;
			break;
			case "budget_details012":
				max_value = ( $me.siblings().find( '#'+record_id+'-budget_details010' ).parent("td").text().replace( /[^0-9\-\.]/g, '' ) ) * 1;
			break;
			}
			
			//check input type
			switch( $input.attr('alt') ){
			case 'select':
				$input
				.val(  $me.find('.datatables-cell-id').attr('real-value') );
			break;
			case 'number':
				$input
				.val( $me.text().replace( /[^0-9\-\.]/g, '' ) * 1 );
				
				if( ! isNaN( max_value ) ){
					$input
					.attr("max", max_value);
				}
			break;
			case 'date':
				var real_values = $me.text().split('-');
				var text = '';		
				if( real_values.length == 3 && real_values[1] ){
					text = real_values[2]+'-'+months_reverse[ real_values[1] ]+'-'+real_values[0];
				}
				$input
				.val( text );
			break;
			case 'file':
				$input
				.val(  $me.find('.datatables-cell-id').attr('real-value') );
				
				var $clone = $me.clone();
				$clone.find('input').remove();
				var real_values = $clone.html();
				
				$("ul.qq-upload-list")
				.html( real_values );
			break;
			default:
				$input
				.val( $me.text() );
			break;
			}
			
			if( ! $form.hasClass('quick-edit-form') ){
				if( $me.find('.datatables-cell-id') && $me.find('.datatables-cell-id').val() ){
					var text = $me.text();
					var html = $me.html().replace( text , '' );
					
					$me
					.data('cell-html', html )
					.empty();
				
					$input
					.css( 'width' , ($me.width() - 20)+'px')
					.appendTo( $me )
					.removeClass('form-gen-element')
					.addClass('inline-form-element')
					.attr('disabled', false)
					.focus();
				}
			}
			
			var title = '';
			$me.siblings().each( function(){
				var $e = $(this);
				var max_value = "";
				
				if( $e.find('.datatables-cell-id') ){
				
					var input_id = $e.find('.datatables-cell-id').val();
					
					if( input_id ){
						
						switch(input_id){
						case "code":
							input_id = "cash_calls002";
						break;
						case "description":
							input_id = "cash_calls003";
						break;
						case "budget_details009":
							max_value = ( $e.siblings().find( '#'+record_id+'-budget_details007' ).parent("td").text().replace( /[^0-9\-\.]/g, '' ) ) * 1;
						break;
						case "budget_details010":
							max_value = ( $e.siblings().find( '#'+record_id+'-budget_details008' ).parent("td").text().replace( /[^0-9\-\.]/g, '' ) ) * 1;
						break;
						case "budget_details011":
							max_value = ( $e.siblings().find( '#'+record_id+'-budget_details009' ).parent("td").text().replace( /[^0-9\-\.]/g, '' ) ) * 1;
						break;
						case "budget_details012":
							max_value = ( $e.siblings().find( '#'+record_id+'-budget_details010' ).parent("td").text().replace( /[^0-9\-\.]/g, '' ) ) * 1;
						break;
						}
						
						var $input = $form.find('.'+input_id);
						
						var text = $e.text();
						var html = $e.html().replace( text , '' );
						
						//check input type
						switch( $input.attr('alt') ){
						case 'select':
							$input
							.val(  $e.find('.datatables-cell-id').attr('real-value') );
						break;
						case 'number':
							$input
							.val( text.replace( /[^0-9\-\.]/g, '' ) * 1 );
							
							if( ! isNaN( max_value ) ){
								$input
								.attr("max", max_value);
							}
						break;
						case 'date':
							var real_values = text.split('-');
							
							if( real_values.length == 3 && real_values[1] ){
								text = real_values[2]+'-'+months_reverse[ real_values[1] ]+'-'+real_values[0];
							}
							$input
							.val( text );
						break;
						case 'file':
							$input
							.val(  $e.find('.datatables-cell-id').attr('real-value') );
							
							var $clone = $e.clone();
							$clone.find('input').remove();
							var real_values = $clone.html();
							
							$("ul.qq-upload-list")
							.html( real_values );
							
						break;
						default:
							$input
							.val( text );
						break;
						}
						
						$input
						.attr('default-value', $input.val() );
						
						switch(input_id){
						case "code":
						case "description":
						case "budget_details002":
						case "budget_details003":
						case "cash_calls002":
						case "cash_calls003":
							//if( title )title += '<hr class="line-item-sep" />'+text;
							//else title += text;
						break;
						}
						
						if( ! $form.hasClass('quick-edit-form') ){
							$e
							.data('cell-html', html )
							.empty();
							
							$input
							.css( 'width' , ($e.width() - 20)+'px')
							.appendTo( $e )
							.removeClass('form-gen-element')
							.addClass('inline-form-element')
							.attr('disabled', false);
						}
					}
				}
			});
			
			//$('#form-title').html('<strong>'+title+'</strong>');
			//readjust table width
			//oTable.fnAdjustColumnSizing();
			
		//}
	};
	
	var months = {
		"01":"Jan",
		"02":"Feb",
		"03":"Mar",
		"04":"Apr",
		"05":"May",
		"06":"Jun",
		"07":"Jul",
		"08":"Aug",
		"09":"Sep",
		"10":"Oct",
		"11":"Nov",
		"12":"Dec",
	};
	
	var months_reverse = {
		"Jan":"01",
		"Feb":"02",
		"Mar":"03",
		"Apr":"04",
		"May":"05",
		"Jun":"06",
		"Jul":"07",
		"Aug":"08",
		"Sep":"09",
		"Oct":"10",
		"Nov":"11",
		"Dec":"12",
	};
	
	function select_record_click_event($clicked_element_parent, $clicked_element, $clicked_element_group_selector, shiftctrlKey ){
		/*
		if( multiple_selected_record_id ){
			$('#example')
			.find('tr')
			.removeClass('row_selected');
			multiple_selected_record_id = '';
		}
		*/
		
		if($clicked_element.hasClass('row_selected')){
			
			if( ! $clicked_element.hasClass('inline-edit-in-progress') ){
				//Mark DataTable Row as Selected
				$clicked_element.removeClass('row_selected');
				
				//Store ID of Selected Row
				single_selected_record = $clicked_element.find('.datatables-record-id').attr('id');
				//console.log('before', single_selected_record+'	-	'+multiple_selected_record_id);
				
				multiple_selected_record_id = multiple_selected_record_id.replace( single_selected_record+':::' , '' );
				single_selected_record = '';
			}
			//console.log('after', single_selected_record+'	-	'+multiple_selected_record_id);
		}else{
			if( shiftctrlKey ){
				if( single_selected_record && multiple_selected_record_id == '' )
					multiple_selected_record_id = single_selected_record;
					
				multiple_selected_record_id += ':::'+$clicked_element.find('.datatables-record-id').attr('id');
			}else{
				//Clear All Previously Selected Rows in DataTable
				$clicked_element_parent.find($clicked_element_group_selector).removeClass('row_selected');
				
				if( $('.inline-form-element') && $('.inline-form-element').length > 0 ){
					$('.inline-form-element')
					.each(function(){
						var value = '';
						var real_value = '';
						
						switch( $(this).attr('alt') ){
						case 'select':
							real_value = $(this).val();
							value = $(this).find('option[value="'+real_value+'"]').text()+''+$(this).parent( 'td' ).data( 'cell-html' );
						break;
						case 'date':
							var real_values = $(this).val().split('-');
							
							if( real_values.length == 3 && real_values[1] ){
								value = real_values[2]+'-'+months[ real_values[1] ]+'-'+real_values[0]+''+$(this).parent( 'td' ).data( 'cell-html' );
							}
							
						break;
						default:
							value = $(this).val()+''+$(this).parent( 'td' ).data( 'cell-html' );
						break;
						}
							
						var $parent_cell = $(this).parent( 'td' );
						
						$parent_cell
						.html( value );
						
						switch( $(this).attr('alt') ){
						case 'select':
							$parent_cell.find('.datatables-cell-id').attr('real-value', real_value );
						break;
						}
						
						$(this)
						.removeClass('inline-form-element')
						.addClass('form-gen-element')
						.appendTo(	$('#inline-edit-form-wrapper').find('form') );
					});
					
					$clicked_element_parent
					.find('.inline-edit-in-progress')
					.removeClass('inline-edit-in-progress');
					
					$('#inline-edit-form-wrapper')
					.find('input[name="skip_validation"]')
					.val('true');
					
					$('#inline-edit-form-wrapper')
					.find('form')
					.data('reload-table', 2);
					
					submit_form_data( $('#inline-edit-form-wrapper').find('form') );
				}
				
				multiple_selected_record_id = '';
			}
			//Mark DataTable Row as Selected
			$clicked_element.addClass('row_selected');
			
			//Store ID of Selected Row
			single_selected_record = $clicked_element.find('.datatables-record-id').attr('id');
			
			//multiple_selected_record_id
		}
		
		//CHECK WHETHER OR NOT TO DISPLAY DETAILS
		//console.log('single',single_selected_record);
		//console.log('multiple', multiple_selected_record_id);
		
		if( $('#record-details-home').is(':visible') ){
			if( single_selected_record && ( multiple_selected_record_id == '' || multiple_selected_record_id == single_selected_record )  ){
				//Replace Container Content with entire record details
				$('#record-details-home')
				.html( $('#main-details-table-'+single_selected_record).html() );
			}
			
			if( multiple_selected_record_id ){
				var array_of_selected_records = multiple_selected_record_id.split(':::');
				
				var count = array_of_selected_records.length;
				
				details_of_multiple_selected_records = '';
				
				for( var i = 0; i < count; i++ ){
					//Push All Details to display container
					details_of_multiple_selected_records += $( '#main-details-table-' + array_of_selected_records[i] ).html();
				}
				
				if( $('#record-details-home').is(':visible') && details_of_multiple_selected_records ){
					$('#record-details-home')
					.html( details_of_multiple_selected_records );
				}
			}
		}
		
		if( single_selected_record ){
			try {
				$.fn.nwRecordDetailsSidePane.populateData( single_selected_record );
			}
			catch(err) {
				// Handle error(s) here
			}
			
			if( ! ( multiple_selected_record_id == '' || multiple_selected_record_id == single_selected_record ) ){
				$('#'+single_selected_record)
				.parents('table')
				.attr('tabIndex', 1 )
				.focus();
				
				$(document).scrollTop(0);
			}
		}
		//return false;
	};
	
	function ajax_send(){
		if(function_click_process){
		//Send Data to Server
		
		if( $("body").hasClass("modal-open") ){
			if( ajax_get_url )ajax_get_url += "&modal=1";
			else ajax_get_url = "?modal=1";
		}
				
		$.ajax({
			dataType:ajax_data_type,
			type:form_method,
			data:ajax_data,
			url: pagepointer+'php/ajax_request_processing_script.php'+ajax_get_url,
			timeout:100000,
			beforeSend:function(){
				//Display Loading Gif
				function_click_process = 0;
				
				cancel_ajax_recursive_function = false;
				
				confirm_action_prompt = 0;
				
				/*ajax_container.html('<div id="loading-gif" class="no-print">Please Wait</div>');*/
				
				$('#generate-report-progress-bar')
				.html('<div id="virtual-progress-bar" class="progress progress-striped"><div class="progress-bar bar"></div></div>');
				progress_bar_change();
				
				ajax_request_data_before_sending_to_server = '';
				ajax_request_data_before_sending_to_server += '<p><b>dataType:</b> '+ajax_data_type+'</p>';
				ajax_request_data_before_sending_to_server += '<p><b>type:</b> '+form_method+'</p>';
				if( typeof(ajax_data) == "object" )
					ajax_request_data_before_sending_to_server += '<p><b>data:</b> '+ $.param( ajax_data ) +'</p>';
				else
					ajax_request_data_before_sending_to_server += '<p><b>data:</b> '+ ajax_data +'</p>';
				
				ajax_request_data_before_sending_to_server += '<p><b>url:</b> '+pagepointer+'php/backend.php'+ajax_get_url+'</p>';
				
			},
			error: function(event, request, settings, ex) {
				
				if( function_click_process == 0 && event.responseText ){
					
					//Refresh Page
					function_click_process = 1;
					
					//Display Timeout Error Message
					var theme = 'a';
					var message_title = 'AJAX Request Error';
					var message_message = "Error requesting page!<br /><br /><h4>Request Parameters</h4>" + ajax_request_data_before_sending_to_server + "<br /><h4>Response Text</h4><p><textarea>" + event.responseText + "</textarea></p>";
					var auto_close = 'no';
					
					no_function_selected_prompt(theme, message_title, message_message, auto_close);
					
				}
			},
			success: function(data){
				
				function_click_process = 1;

				switch(ajax_action){
				case "generate_modules":
					ajax_generate_modules(data);
				break;
				case "request_function_output":
					ajax_request_function_output(data);
				break;
				case "action_button_submit_form":
					ajax_action_button_submit_form(data);
				break;
				case "submit_form_data":
					ajax_submit_form_data(data);
				break;
				}
				
				//CHECK FOR NOTIFICATION
				if( data && data.notification ){
					console.log(data.notification);
				}
			
			}
		});
		}
	};
    
	function ajax_generate_modules(data){
		if(data.fullname.length > 1){
			var icons = [ 'icon-folder-open', 'icon-wrench', 'icon-list', 'icon-calendar' ];
			var icon_count = 0;
			
            
			//PREPARE DATA FOR DISPLAY TO BROWSER
            //<ul id="module-menu" class="nav nav-list bs-docs-sidenav">
			var html = '<li><!-- BEGIN SIDEBAR TOGGLER BUTTON --><div class="sidebar-toggler hidden-phone"></div><!-- BEGIN SIDEBAR TOGGLER BUTTON --></li><li class="start active "><a href="index.html"><i class="icon-home"></i> <span class="title">Dashboard</span><span class="selected"></span></a></li>';
			$.each(data.modules, function(key, value){
				//Exclude user details
				if(key!='user_details'){
                    //html += '<div class="accordion-group">';
                    
					$.each(value, function(ki, vi){
						
						html = html + '<li class=""><a href="javascript:;" data-href="#'+key+'"><i class="'+icons[icon_count]+'"></i> <span class="title">'+ki+'</span><span class="arrow "></span></a><ul class="sub-menu module-menu">';
						$.each(vi, function(k, v){
							switch( v.id ){
							default:
								html = html + '<li><a href="#" id="'+v.id+'" function-id="'+v.id+'" function-class="'+v.phpclass+'" function-name="'+v.todo+'" module-id="'+key+'" module-name="'+ki+'" title="'+v.name+'">'+v.name+' </a></li>';
							break;
							}
						});
						html = html + '</ul></li>';
						
						++icon_count;
						
						if( icon_count > 3 )icon_count = 0;
					});
                    
                    //html += '</div>';
				}
			});
			html = html + '</div>';
			
			//$('#horizontal-nav')
			$('#side-nav')
			.html(html);
			
			//Bind Actions
			set_function_click_event();
			
			//Obtain Functiions of Selected Module
			//generate_functions();
			
			//Update Name of Currently Logged In User
			$( '#current-user-name-module' )
			.add( '#user-info-user-name' )
			.text( data.fullname );
			
			var user_info = '';
			
            $('.login-role').text( data.role );
            $('.last-login-time').text( data.login_time );
            
			//user_info += '<div class="row-fluid"><div class="span6">Email</div><div class="span6"><b>'+data.email+'</b></div></div>';
			
			//$('#app-user-information').html( user_info );
			
			$('#appsettings-name').text( data.project_title );
			
			$('title').text( data.project_title );
			
			$('#project-version').text( data.project_version );
			
            $('#toggle-workspace')
            .on('click', function(e){
                e.preventDefault();
                
                $('#side-nav').toggle();
                
                if( $('#side-nav') && $('#side-nav').is(':visible') ){
                    $('#switched-workspace-original-container')
                    .append( $('#switched-workspace') );
                }else{
                    $('#switched-workspace-container')
                    .append( $('#switched-workspace') );
                }
            });
            
         
            //populate dashboard
            populate_dashbaord();
            
            App.init(); // initlayout and core plugins
             Index.init();
			 /*
             Index.initJQVMAP(); // init index page's custom scripts
             Index.initCalendar(); // init index page's custom scripts
             Index.initCharts(); // init index page's custom scripts
             Index.initChat();
             Index.initMiniCharts();
             Index.initDashboardDaterange();
             Index.initIntro();
             Tasks.initDashboardWidget();
			 */
		}else{
			//Redirect to login page
			//$.mobile.navigate('#authentication-page');
		}
	};
	
	function set_function_click_event(){
		if(!bound_menu_items_to_actions){
			//Ensure that Menus are bound only once
			$('ul.module-menu')
			.find('a')
			.not('.drop-down')
			.bind('click',function( e ){
				e.preventDefault();
				set_the_function_click_event($(this));
				
			});
			
			bound_menu_items_to_actions = 1;
		}
		
		$('a#show-dashboard-page')
		.not(".activated-click-event")
        .bind('click', function( e ){
            populate_dashbaord();
        })
		.addClass("activated-click-event");
        
		if( $.fn.cProcessForm && typeof( $.fn.cProcessForm ) == "object" ){
			$('a#add-new-record')
			.add('a#generate-report')
			.add('a#import-excel-table')
			.add('a#navigation-pane')
			.add('a#advance-search')
			.add('a#clear-search')
			.add('a#delete-forever')
			.not(".activated-click-event")
			.bind('click',function( e ){
				e.preventDefault();
				set_the_function_click_event( $(this) );
			})
			.addClass("activated-click-event");
			
			$('#dashboard-menus')
			.find('.custom-action-button')
			.not(".activated-click-event")
			.bind('click',function( e ){
				e.preventDefault();
				set_the_function_click_event( $(this) );
			})
			.addClass("activated-click-event");
			
		}else{
			$('a#add-new-record')
			.add('a#generate-report')
			.add('a#import-excel-table')
			.add('a#navigation-pane')
			.add('a#advance-search')
			.add('a#clear-search')
			.add('.custom-action-button')
			.add('a#delete-forever')
			.not(".activated-click-event")
			.bind('click',function( e ){
				e.preventDefault();
				set_the_function_click_event( $(this) );
			})
			.addClass("activated-click-event");
		}
		
		$('#refresh-datatable')
		.not(".activated-click-event")
		.bind('click',function( e ){
			e.preventDefault();
			
			var data_table_id = getDataTableID( 1 );
			
			if( $('#'+data_table_id) && $('#'+data_table_id).is(":visible") ){
				var oTable1 = $('#'+data_table_id).dataTable();
				
				oTable1.fnReloadAjax();
				
				$(this).hide();
			}
		})
		.addClass("activated-click-event");
		
		//Bind Edit Buttons Event and entity right click menu buttons events
		
		if( $.fn.cProcessForm && typeof( $.fn.cProcessForm ) == "object" ){
			$('#dynamic')
			.find('.custom-single-selected-record-button')
			.add('a#edit-selected-record')
			.add('#edit-selected-record-password')
			.not(".activated-click-event")
			.bind('click',function( e ){
				e.preventDefault();
				
				var record = "";
				
				if( $(this).attr("override-selected-record-only") ){
					record = $(this).attr("override-selected-record");
				}else{
					if( $(this).attr("override-selected-record") ){
						single_selected_record = $(this).attr("override-selected-record");
					}
				}
				
				if( ( ! single_selected_record  ) && $(this).attr("selected-record") ){
					single_selected_record = $(this).attr("selected-record");
				}
				
				if( single_selected_record || record ){
					if( single_selected_record && ! record )record = single_selected_record;
					
					clicked_action_button = $(this);
					
					if( record == "json" ){
						ajax_data = {mod:$(this).attr('mod'), id:record, json:$("body").data("json") };
					}else{
						ajax_data = {mod:$(this).attr('mod'), id:record};
					}
					
					form_method = 'post';
					
					ajax_data_type = 'json';
					
					ajax_action = 'request_function_output';
					ajax_container = '';
					ajax_get_url = $(this).attr('action');
					
					ajax_send();
					
				}else{
					no_record_selected_prompt();
				}
			});
		}else{
			
			$('.custom-single-selected-record-button')
			.add('a#edit-selected-record')
			.add('#edit-selected-record-password')
			.not(".activated-click-event")
			.bind('click',function( e ){
				e.preventDefault();
				
				var record = "";
				
				if( $(this).attr("override-selected-record-only") ){
					record = $(this).attr("override-selected-record");
				}else{
					if( $(this).attr("override-selected-record") ){
						single_selected_record = $(this).attr("override-selected-record");
					}
				}
				
				if( ( ! single_selected_record  ) && $(this).attr("selected-record") ){
					single_selected_record = $(this).attr("selected-record");
				}
				
				if( single_selected_record || record ){
					if( single_selected_record && ! record )record = single_selected_record;
					
					clicked_action_button = $(this);
					
					if( record == "json" ){
						ajax_data = {mod:$(this).attr('mod'), id:record, json:$("body").data("json") };
					}else{
						ajax_data = {mod:$(this).attr('mod'), id:record};
					}
					
					form_method = 'post';
					
					ajax_data_type = 'json';
					
					ajax_action = 'request_function_output';
					ajax_container = '';
					ajax_get_url = $(this).attr('action');
					
					ajax_send();
					
				}else{
					no_record_selected_prompt();
				}
			});

		}
		
		//Bind Generate Report Buttons Event
		
		$('a.custom-multi-selected-record-button')
		.add('a#generate-report-first-term')
		.not(".activated-click-event")
		.bind('click',function(e){
			e.preventDefault();
			
			if(single_selected_record || multiple_selected_record_id){
				clicked_action_button = $(this);
				
				var budget_id = '';
				var month_id = '';
				
				if( $(this).attr('budget-id') && $(this).attr('month-id') ){
					budget_id = $(this).attr('budget-id');
					month_id = $(this).attr('month-id');
				}
				
				ajax_data = {mod:$(this).attr('mod'), id:single_selected_record, ids:multiple_selected_record_id, budget:budget_id, month:month_id };
				form_method = 'post';
				
				ajax_data_type = 'json';
				
				ajax_action = 'request_function_output';
				ajax_container = '';
				ajax_get_url = $(this).attr('action');
			
				ajax_send();
			}else{
				no_record_selected_prompt();
			}
		})
		.addClass("activated-click-event");
		
        //preview-content
		$('a.preview-content')
		.not(".activated-click-event")
		.bind('click',function(e){
			 
			e.preventDefault();
			
			if( single_selected_record ){
				var html = $('table#the-main-details-table-'+single_selected_record).find('tr[jid="'+$(this).attr('row-id')+'"]').find('td.details-section-container-value').html();
                
                if( html ){
                    var x=window.open();
                    x.document.open();
                    x.document.write( html );
                    x.document.close();
                }
			}else{
				no_record_selected_prompt();
			}
		})
		.addClass("activated-click-event");
		
		//Bind Delete Buttons Event
		$('a#restore-selected-record')
		.add('a#delete-selected-record')
		.not(".activated-click-event")
		.bind('click',function(e){
			e.preventDefault();
			
			if(single_selected_record || multiple_selected_record_id){
				
				clicked_action_button = $(this);
				
				ajax_data = {mod:$(this).attr('mod'), id:single_selected_record, ids:multiple_selected_record_id};
				form_method = 'post';
				ajax_data_type = 'json';
				
				//ajax_action = 'action_button_submit_form';
				ajax_action = 'request_function_output';
				ajax_container = '';
				ajax_get_url = $(this).attr('action');
				
				confirm_action_prompt = 1;
				
				$(this).popover('show');
			}else{
				no_record_selected_prompt();
			}
		})
		.addClass("activated-click-event");
		
	};
	
	function set_the_function_click_event($me){
		//Get HTML ID
		clicked_menu = $me.attr('id');
		
		//Last Clicked function from main menu
		switch($me.attr('id')){
		case 'add-new-record':
		case 'generate-report':
		case 'import_excel_table':
		case 'restore-all':
		case 'advance-search':
		case 'navigation-pane':
		case 'clear-search':
		case 'add-new-memo-report-letter':
		break;
		default:
			clicked_main_menu = $me.attr('id');
		break;
		}
		
		//Get function id
		var function_id = $me.attr('function-id');
		var function_name = $me.attr('function-name');
		var function_class = $me.attr('function-class');
		
		var budget_id = '';
		var month_id = '';
		var operator_id = '';
		var department_id = '';
		var start_date = '';
		var end_date = '';
		var year = '';
		var month = '';
		
		if( $me.attr('budget-id') && $me.attr('month-id') ){
			budget_id = $me.attr('budget-id');
			month_id = $me.attr('month-id');
		}
		
		if( $me.attr('department-id') && $me.attr('operator-id') ){
			operator_id = $me.attr('operator-id');
			department_id = $me.attr('department-id');
		}
		
		if( $me.attr('start-date') && $me.attr('end-date') ){
			start_date = $me.attr('start-date');
			end_date = $me.attr('end-date');
		}
		
		if( $me.attr('year') ){
			year = $me.attr('year');
		}
		
		if( $me.attr('month') ){
			month = $me.attr('month');
		}
		
		column_toggle_table = '';
		column_toggle_num = '';
		column_toggle_name = '';
		
		search_table = '';
		if($me.attr('search-table'))
			search_table = $me.attr('search-table');
		
		if($me.attr('column-toggle-table')){
			column_toggle_table = $me.attr('column-toggle-table');
			column_toggle_name = $me.attr('name');
			column_toggle_num = $me.attr('column-num');
		}	
			
		var module_id = $me.attr('module-id');
		var url = "";
		if( $me.hasClass("custom-action-button-url") ){
			url = $me.attr("href");
		}
		
		if(function_id && function_id!='do-nothing'){
			//Request Function Output
			request_function_output(function_name, function_class, module_id, function_id , budget_id, month_id, operator_id, department_id, url, start_date, end_date, year, month );
			
			//Update name of the active function
			if( $me.attr('module-name') && $me.attr('module-name').length > 3 && $me.text()){
				$('#active-function-name')
				.attr('function-class', function_class)
				.attr('function-id', function_id)
				.html($me.attr('module-name') + ' &rarr; ' + $me.text());
				
				$('#secondary-display-title').html( '<i class="icon-info-sign"></i> ' + $me.attr('module-name') + ' &rarr; ' + $me.text() );
				
				$('title').html($me.attr('module-name') + ' &rarr; ' + $me.text());
			}
		}
	};
	
	function request_function_output(function_name, function_class, module_id, function_id, budget_id, month_id, operator_id, department_id, url, start_date, end_date, year, month ){
		//IF ADVANCE SEARCH - THEN PASS VALUE OF CURRENT TABLE
		
		switch(function_class){
		case "myexcel":
		case "search":
			ajax_data = {action:function_class, todo:function_name, module:module_id, search_table:search_table};
		break;
		case "column_toggle":
			ajax_data = {action:function_class, todo:function_name, module:module_id, column_toggle_table:column_toggle_table, column_toggle_name:column_toggle_name, column_toggle_num:column_toggle_num};
		break;
		default:
			if(function_id){
				ajax_data = {action:function_class, todo:function_name, module:module_id, id:function_id, budget:budget_id, month:month_id, department:department_id, operator:operator_id, end_date:end_date, start_date:start_date, year:year, month_of_year:month };
			}else{
				ajax_data = {action:function_class, todo:function_name, module:module_id, budget:budget_id, month:month_id, department:department_id, operator:operator_id, end_date:end_date, start_date:start_date, year:year, month_of_year:month };
			}
			
			if( function_name == 'create_new_record' && single_selected_record ){
				ajax_data = {action:function_class, todo:function_name, module:module_id, id:single_selected_record };
			}
		break;
		}
		
		class_action = function_name;
		class_name = function_class;
		module_id = module_id;
		
		form_method = 'get';
		ajax_data_type = 'json';
		
		//if(function_name=='rename_entity' && before==1)ajax_data_type = 'text'; //NEW RECORD
		//if(function_name=='new')ajax_data_type = 'text'; //NEW RECORD
		//if(function_name=='select_audit_trail')ajax_data_type = 'text'; //NEW RECORD
		//if(function_name=='search')ajax_data_type = 'text'; //SEARCH RECORD
		//if(function_name=='clear_search')ajax_data_type = 'text'; //SEARCH RECORD
		//if(function_name=="column_toggle")ajax_data_type = 'text'; //TOGGLE COLUMN RECORD
		//if(function_name=="delete_forever")ajax_data_type = 'text'; //TOGGLE COLUMN RECORD
		
		ajax_action = 'request_function_output';
		ajax_container = '';
		ajax_get_url = '';
		
		if( url ){
			ajax_get_url = url;
			ajax_data = {};
		}
		ajax_send();
	};
	
	function re_process_previous_request( data ){
        
		if( data.re_process && ! cancel_ajax_recursive_function ){
			if( data.re_process_code )trigger_new_ajax_request( data );
			else set_the_function_click_event( $( data.re_process ) );
		}else{
			//Reload DataTable
			if( data.reload_table && oTable ){
				oTable.fnReloadAjax();
			}
		}
	}
	
	function trigger_new_ajax_request( data ){
		
		ajax_data = {mod:data.mod, id:data.id};
		form_method = 'post';
		
		ajax_data_type = 'json';
		ajax_action = 'request_function_output';
		ajax_container = '';
		ajax_get_url = data.action;
		
		ajax_send();
	}
	
	var tmp_data;
	function ajax_request_function_output(data){
		//alert(data);
		//Close Pop-up Menu
		
		data.reload_table = 1;
		
		if( data.status ){
			tmp_data = data;
			
			switch(data.status){
			case "new-status":
				if( data ){
					
					if( data.redirect_url ){
						document.location = data.redirect_url;
					}
					
					if( data.html ){
						$('#dash-board-main-content-area')
						.html( data.html );
					}
					
					if( data.html_replacement_selector && data.html_replacement ){
						$( data.html_replacement_selector )
						.html( data.html_replacement );
					}
					
					if( data.html_replacement_selector_one && data.html_replacement_one ){
						$( data.html_replacement_selector_one )
						.html( data.html_replacement_one );
					}
					
					if( data.html_replacement_selector_two && data.html_replacement_two ){
						$( data.html_replacement_selector_two )
						.html( data.html_replacement_two );
					}
					
					if( data.html_replacement_selector_three && data.html_replacement_three ){
						$( data.html_replacement_selector_three )
						.html( data.html_replacement_three );
					}
					
					if( data.html_prepend_selector && data.html_prepend ){
						$(data.html_prepend_selector)
						.prepend( data.html_prepend );
					}
					
					if( data.html_append_selector && data.html_append ){
						$(data.html_append_selector)
						.append( data.html_append );
					}
					
					if( data.html_replace_selector && data.html_replace ){
						$(data.html_replace_selector)
						.replaceWith( $(data.html_replace) );
					}
					
					if( data.html_removal ){
						$(data.html_removal).remove();
					}
					
					if( data.javascript_functions ){
						
						$.each( data.javascript_functions , function( key, value ){
							eval( value + "()" );
						} );
					}
					
					if(data.clear_saved_record_id){
						single_selected_record = "";
					}
					
					if(data.saved_record_id){
						single_selected_record = data.saved_record_id;
					}
					
					if( data.do_not_reload_table )
						data.reload_table = 0;
				}
			break;
			case "got-quick-details-view":
				//Update Create New School Button Attributes
				if( $('#custom-details-display-container') && data.html ){
					$('#custom-details-display-container')
					.html( data.html );
                    data.reload_table = 0;
				}
			break;
			case "display-appsettings-setup-page":
				//Update Create New School Button Attributes
				if( $('#create_new_appsettings') && data.create_new_appsettings_data ){
					$('#create_new_appsettings')
					.attr('function-class', data.create_new_appsettings_data.function_class )
					.attr('function-name', data.create_new_appsettings_data.function_name )
					.attr('module-id', data.create_new_appsettings_data.module_id );
					
					//Bind Click Event of Button
					$('#create_new_appsettings')
					.bind('click',function(){
						set_the_function_click_event($(this));
					});
				}
				
			break;
			case "display-data-capture-form":
				//Update Create New School Button Attributes				
				if( data.do_not_reload_table )
					data.reload_table = 0;
					
				prepare_new_record_form(data);
				
				if( data.country )
					activate_country_select_field();
				
				//Display Form Tab
				$('#form-home-control-handle')
				.click();
			break;
			case "display-advance-search-form":
				//Update Create New School Button Attributes
				prepare_new_record_form(data);
				
                if( $('#side-nav') && $('#side-nav').is(':visible') ){
                    //Display Top Accordion
                    if( ! $('#collapseTop').hasClass('in') ){
                        $('#collapseTop')
                        .collapse('show');
                    }
                    
                    $('#collapseBottom')
                    .find('.portlet-body')
                    .hide();
				}
				//Display Form Tab
				$('#form-home-control-handle')
				.click();
				
				//bind advance search controls
				bind_search_field_select_control();
			break;
			case "modify-appsettings-settings":
				//Update Create New School Button Attributes
				prepare_new_record_form(data);
				
				//Update Application with School Properties
				update_application_with_school_properties( data );
			break;
			case "redirect-to-dashboard":
				if( data.reload ){
					document.location = document.location;
				}
				
				//Redirect to dashboard page
				$('#page-body-wrapper')
				.html( data.html );
				
				//Update Application with School Properties
				update_application_with_school_properties( data );
				
				//Get Menus
				generate_modules();
				
				//Activate Tabs
				$('#myTab')
				.find('a')
				.bind('click', function (e) {
				  e.preventDefault();
				  $(this).tab('show');
				  
				   //Link Tab Clicks to Accordion
				   if( ! $('#collapseTop').hasClass('in') ){
						$('#collapseTop')
						.collapse('show');
					}
				});
				
				//bind clear tab contents button
				$('#clear-tab-contents')
				.bind('click', function (e) {
					e.preventDefault();
					
					$('.tab-content')
					.find('div.active.tab-content-to-clear')
					.empty();
						
					$('.tab-content')
					.find('div.active')
					.find('.tab-content-to-clear')
					.empty();
						
				});
			break;
			case "reload-page":
				document.location = document.location;
			break;
			case "redirect-to-login":
				//Redirect to login page
				prepare_new_record_form(data);
				
				//Update Application with School Properties
				update_application_with_school_properties( data );
			break;
			case "authenticate-user":
				//Refresh Form Token
				//refresh_form_token( data );
			break;
			case "displayed-dashboard":
				//Refresh Form Token
				$('#data-table-container')
				.html( data.html );
                
                bind_details_view_control();
			break;
			case "deleted-records":
				var data_table_id = getDataTableID( 1 );
				
				var oTable1 = $('#'+data_table_id).dataTable();
				if( oTable1 ){
					oTable1.fnReloadAjax();
					
					if( data.reload_other_tables ){
						$.each( data.reload_other_tables, function( k, v ){
							var oTable2 = $('#'+v+'-datatable').dataTable();
							oTable2.fnReloadAjax();
						});
					}
				}
			break;
			case "column-toggle":
				ajax_hide_show_column_checkbox( data );
                bind_details_view_control();
			break;
			case "reload-datatable":
				//Activate DataTables Plugin
				if( data.searched_table ){
					class_name = data.searched_table;
				
					$('#search-query-display-container')
					.html( data.search_query )
					.attr( 'title', $('#search-query-display-container').text() );
				}
				
				if( $('#collapseTop').hasClass('in') ){
					$('#collapseTop')
					.collapse('hide');
				}
				if( ! $('#collapseBottom').hasClass('in') ){
					$('#collapseBottom')
					.collapse('show');
				}
				reload_datatable();
			break;
			case "display-datatable":
				
				if( $('#collapseTop').hasClass('in') ){
					$('#collapseTop')
                    .find('.portlet-body')
                    .hide();
                    
                    $('#collapseTop')
                    .find('.collapse')
                    .removeClass('collapse')
                    .addClass('expand');
				}
				if( ! $('#collapseBottom').hasClass('in') ){
					$('#collapseBottom')
					.collapse('show');
				}
				
				//Display HTML
				var html = data.html;
				
				if( data.inline_edit_form ){
					html += data.inline_edit_form;
				}
				
				$('#data-table-container')
				.html( html );
				
				if( data.search_query )
					$('#search-query-display-container')
					.html( data.search_query )
					.attr( 'title', $('#search-query-display-container').text() );
				
				
				//Activate DataTables Plugin
				recreateDataTables();
				
				set_function_click_event();
				
				//UPDATE HIDDEN / SHOWN COLUMNS
				update_column_view_state();
				
			break;
			case "saved-form-data":
				if( data.typ == 'serror' || data.typ == 'uerror' )
					break;
				
				if( $('#collapseTop').hasClass('in') ){
					$('#collapseTop')
					.collapse('hide');
				}
				if( ! $('#collapseBottom').hasClass('in') ){
					$('#collapseBottom')
					.collapse('show');
				}
				
				//Check for saved record id
				if(data.saved_record_id){
					single_selected_record = data.saved_record_id;
				}
				
				if( $('form.quick-edit-form') && data.clear_stepmaxstep ){
					$('form.quick-edit-form')
					.find('input[name="stepmaxstep"]')
					.val( data.clear_stepmaxstep );
				}
				
				//Refresh Token
				if( data.go_to_next_record ){
					$('#'+single_selected_record).parents('tr').next().click();
				}
				
				var data_table_id = getDataTableID( 1 );
				
				if( data.do_not_reload_table ){
					data.reload_table = 0;
					
					$('.dynamic')
					.find('#refresh-datatable')
					.show();
					
				}else{
					//Reload DataTable
					if( $('#'+data_table_id) && $('#'+data_table_id).is(":visible") ){
						var oTable1 = $('#'+data_table_id).dataTable();
						
						oTable1.fnReloadAjax();
						//recreateDataTables();
						
						//Bind form submission event
						select_record_click_function( $('#'+data_table_id) );
					}
					if( data.reload_other_tables ){
						$.each( data.reload_other_tables, function( k, v ){
							var oTable2 = $('#'+v+'-datatable').dataTable();
							oTable2.fnReloadAjax();
						});
					}
					
					//Bind details open and close event to table
					bind_details_button_click();
				}
				
				if( data.javascript_functions ){
					
					$.each( data.javascript_functions , function( key, value ){
						eval( value + "()" );
					} );
				}
			break;
			case "download-report":	
				if( $('#monthly-report-link-con') && $('#monthly-report-link-con').is(':visible') && data.html ){
					$('#monthly-report-link-con').html( data.html );
				}
			break;
			}
		}else{
			data.reload_table = 0;
		}
		
		//Handle / Display Error Messages / Notifications
		display_notification( data );
		
		//Check for re-process command
		re_process_previous_request( data );
		
		tmp_data = {};
	};
	
	function reload_datatable(){
		var data_table_id = getDataTableID( 1 );
		var oTable1 = $('#'+data_table_id).dataTable();
		
		if( oTable1 ){
			oTable1.fnReloadAjax();
		}else{
			recreateDataTables();
		}
	};
	
	//Function to Update Application with School Properties
	function update_application_with_school_properties( data ){
		//Update school properties if set
		if( data.appsettings_properties ){
			$('#appsettings-name')
			.text( data.appsettings_properties.appsettings_name );
		}
	};
	
	//Function to Refresh Form Token After Processing
	function refresh_form_token( data ){
		//Update school properties if set
		if( data.tok && $('form') ){
			$('form')
			.find('input[name="processing"]')
			.val( data.tok );
		}
	};
	
	/******************************************************/
	/****************DISPLAY DETAILS OF ROW****************/
	/******************************************************/
	function fnFormatDetails ( oTable, nTr, details, img, duration ){
		var aData = oTable.fnGetData( nTr );
		sOut = '<div class="grid-inner-content">'+details+'</div>';
		
		return sOut;
	};
	/******************************************************/
	
	function bind_details_button_click(){
		/******************************************************/
		/********LISTENER FOR OPENING & CLOSING DETAILS********/
		/******************************************************/
		
		$('.datatables-details').off('click');
		
		$('.datatables-details').on('click', function () {
			
			if($(this).data('details')!='true'){
				$(this)
				.data('details','true');
				
				var nTr = $(this).parents('tr')[0];
				oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr, $(this).next('div').html(), $(this).next('div').next('div').html(),$(this).next('div').next('div').next('div').html()), 'details' );
				
				if($(this).hasClass('pull-location-differential')){
					//Remove location differential class so request is made once
					$(this).removeClass('show-location-differential');
					
					//Make data request to the server
					class_name = 'global_g_m';
					ajax_data = {action:class_name, todo:'location_differential', module:module_id, record_id:$(this).attr('jid')};
					
					form_method = 'get';
					ajax_data_type = 'json';
					ajax_action = 'evaluate_location_differential';
					ajax_container = $(this).parents('tr').next().find('.location-differential');
					
					ajax_send();
				}
			}else{
				$(this)
				.data('details','false');
				
				var nTr = $(this).parents('tr')[0];
				oTable.fnClose( nTr );
			}
		} );
	};
	
	//Display Notification Message
	var notificationTimerID;
	function display_notification( data ){
		nwDisplayNotification.display_notification( data );
	};
		
	function prepare_new_record_form( data ){
		//Bind Html text-editor
		bind_html_text_editor_control();
		
		//Prepare and Display New Record Form
		$('#form-content-area')
		//.html('<div id="form-panel-wrapper1">'+data.html+'</div>')
		.html(data.html);
		
		if( data.status ){
			switch(data.status){
			case "display-data-capture-form":
				//Bind Html text-editor
				bind_html_text_editor_control();
				
				//Activate Client Side Validation / Tooltips
				activate_tooltip_for_form_element( $('#form-content-area').find('form') );
				activate_validation_for_required_form_element( $('#form-content-area').find('form') );
				
			break;
			}
		}
		
		//Bind Form Submit Event
		$('#form-content-area')
		.find('form')
		.bind('submit', function( e ){
			e.preventDefault();
			
			submit_form_data( $(this) );
		});
		
		//Bind form submission event
		//action_button_submit_form();
		select_record_click_function();
		
		//Activate Ajax file upload
		createUploader();
		
		$('#form-content-area')
		.find('form')
		.find("select.select2")
		.select2({ allowClear: true, });
			
		handleDatePickers();
	};
		
	function prepare_new_record_form_new(){
		//Bind Html text-editor
		bind_html_text_editor_control();
		
		//Activate Client Side Validation / Tooltips
		activate_tooltip_for_form_element( $('form.activate-ajax').not('.ajax-activated') );
		activate_validation_for_required_form_element( $('form.activate-ajax').not('.ajax-activated') );
		
		//Bind Form Submit Event
		$('form.activate-ajax')
		.not('.ajax-activated')
		.bind('submit', function( e ){
			e.preventDefault();
			submit_form_data( $(this) );
		})
		.find("select.select2")
		.select2({ allowClear: true, });
		
		//Bind form submission event
		//action_button_submit_form();
		//select_record_click_function();
		
		//Activate Ajax file upload
		createUploader();
		handleDatePickers();
		
		
		$('form.activate-ajax').addClass('ajax-activated');
	};
	
	function handleDatePickers() {
        if ( jQuery().datepicker) {
			var FromEndDate = new Date();
			
            $('input[type="date"]')
			.not(".limit-date")
			.not(".active")
			.datepicker({
                rtl: App.isRTL(),
                autoclose: true,
				format: 'yyyy-mm-dd',
            })
			.addClass("active");
			
            $('input.limit-date')
			.not(".active")
			.datepicker({
                rtl: App.isRTL(),
                autoclose: true,
				format: 'yyyy-mm-dd',
				endDate: FromEndDate, 
            })
			.addClass("active");
			
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    };
	
	var reload_table_after_form_submit = 1;
	
	function submit_form_data( $me ){
		if( $me.data('do-not-submit') ){
			return false;
		}
		
		reload_table_after_form_submit = 1;
		if( $me.data('reload-table') ){
			reload_table_after_form_submit = $me.data('reload-table');
		}
		//console.log('r',reload_table_after_form_submit);
		
		ajax_data = $me.serialize();
	
		form_method = 'post';
		ajax_data_type = 'json';	//SAVE CHANGES, SEARCH
		ajax_action = 'request_function_output';
		//ajax_action = 'submit_form_data';
		
		ajax_container = '';
		ajax_get_url = $me.attr('action');
		
		ajax_notice_container = 'window';
		
		ajax_send();
	};
	
	function bind_html_text_editor_control(){
		$('#myModal')
		.not(".modal-key-down-bind")
		.on('show.bs.modal', function(){
			tinyMCE.activeEditor.setContent( editing_textarea.val() );
		})
		.on('hide.bs.modal', function(){
			editing_textarea
			.val( $("#popTextArea_ifr").contents().find("body").html() );
		})
		.addClass("modal-key-down-bind");
	
		$('textarea')
		.not('.key-down-bind')
		.bind('keydown', function(e){
		
			switch(e.keyCode){
			case 69:	//E Ctrl [17]
				if(e.ctrlKey){
					e.preventDefault();
					
					editing_textarea = $(this);
					
					//Set Contents
					$('#myModal')
					.modal('show');
					
					$(this).attr('tip', '');
					display_tooltip($(this), '');
				}
			break;
			}
			
		})
		.bind('focus', function(){
			$(this).attr('tip', 'Press Ctrl+E to display full text editor');
			
			display_tooltip($(this), '');
		})
		.bind('blur', function(){
			$(this).attr('tip', '');
			
			display_tooltip($(this), '');
		})
		.addClass("key-down-bind");
	};
	
	//Bind Show/Hide Column Checkboxes to Show/Hide Column Menu Button
	bind_show_hide_column_checkbox();
	function bind_show_hide_column_checkbox(){
		
		$('ul.show-hide-column-con')
		.find('input[type="checkbox"]')
		.live('click' , function(){
			
			//get current column
			var col = $(this).parents('li').index();
			
			$(this).attr('column-num' , col);
			
			set_the_function_click_event( $(this) );
			
			oTable.fnAdjustColumnSizing();
		});
		
		//bind delete popover buttons
		$('input#delete-button-yes')
		.live('click',function(e){
			$('a#delete-selected-record').popover('hide');
			
			if( confirm_action_prompt ){
				ajax_send();
				
				//Reset confirmation value once pop-up closes
				confirm_action_prompt = 0;
			}
		});
		
		$('input#restore-button-yes')
		.live('click',function(e){
			$('a#restore-selected-record').popover('hide');
			
			if( confirm_action_prompt ){
				ajax_send();
				
				//Reset confirmation value once pop-up closes
				confirm_action_prompt = 0;
			}
		});
		
		$('input#delete-button-no')
		.live('click',function(e){
			$('a#delete-selected-record').popover('hide');
		});
		
		$('input#restore-button-no')
		.live('click',function(e){
			$('a#restore-selected-record').popover('hide');
		});
		
		//Bind Cancel Operation for recursive ajax requests
		$('button.stop-current-operation')
		.live('click',function(){
			cancel_ajax_recursive_function = true;
		});
	};
	
	//Bind Search Field Select Control
	function bind_search_field_select_control(){
		$('#search-field-select-combo')
		.on('change',function(){
			$('form')
			.find('.default-hidden-row')
			.hide();
			
			$('form')
			.find('.'+$(this).val())
			.show();
		});
	};
	
	//Hide / Show Column after serverside processing
	function ajax_hide_show_column_checkbox( data ){
		var data_table_id = getDataTableID( 1 );
		
		//get current column
		var col = data.column_num;
		++col;
		++col;
		fnShowHide( col , data_table_id );
		
		//Toggle Check Box State
		$('#show-hide-column-con')
		.find('input[name="'+data.column_name+'"]')
		.attr('checked', data.column_state);
		
		var parent = $('#show-hide-column-con').find('input[name="'+data.column_name+'"]').parents('.ui-checkbox');
		
	};
		
	//Update Hidden / Show Columns
	function update_column_view_state(){
		
		$('ul.show-hide-column-con')
		.find('input[type="checkbox"]')
		.each(function(){
			if(!$(this).is(':checked')){
				//get current column
				var col = $(this).parents('li').index();
				col += 2;
				fnHide(col , $(this).parents('ul').attr("data-table") );
			}
		});
		
		
		var data_table_id = getDataTableID( 1 );
		var oTable1 = $('#'+data_table_id).dataTable();
		oTable1.fnAdjustColumnSizing();
		
	};
	
	//Column Selection
	function fnShowHide( iCol, dataTableName )
	{
		/* Get the DataTables object again - this is not a recreation, just a get of the object */
		var oTable1 = $('#'+dataTableName).dataTable();
		 
		var bVis = oTable1.fnSettings().aoColumns[iCol].bVisible;
		oTable1.fnSetColumnVis( iCol, bVis ? false : true );
		
	};
	
	//Column Selection
	function fnHide( iCol, dataTableName )
	{
		/* Get the DataTables object again - this is not a recreation, just a get of the object */
		var oTable1 = $('#'+dataTableName).dataTable();
		oTable1.fnSetColumnVis( iCol, false, false);
	};
	
	//File Uploader
	function createUploader(){
		if($('.upload-box').hasClass('cell-element')){
			
			$('.upload-box').each(function(){
				var id = $(this).attr('id');
				var name = $(this).find('input').attr('name');
				var acceptable_files_format = $(this).find('input').attr('acceptable-files-format');
				var table = $("#"+id).parents('form').find('input[name="table"]').val();
				var form_id = $("#"+id).parents('form').find('input[name="processing"]').val();
				var form_record_id = $("#"+id).parents('form').find('input[name="id"]').val();
				var actual_form_id = $("#"+id).parents('form').attr('id');
				
				//instead of sending processing id | rather send record id
				if( form_record_id && form_record_id.length > 1 )form_id = form_record_id;
				
				$("."+name+"-replace").attr( 'name' , $(this).find('input').attr('name') );
				$("."+name+"-replace").attr( 'id' , $(this).find('input').attr('id') );
				$("."+name+"-replace").attr( 'class' , $(this).find('input').attr('class') );
				$("."+name+"-replace").attr( 'alt' , $(this).find('input').attr('alt') );
				
				var uploader = new qq.FileUploader({
					element: document.getElementById(id),
					listElement: document.getElementById('separate-list'),
					action: pagepointer+'php/upload.php',
					params: {
						tableID: table,
						formID: form_id,
						name:name,
						actualFormID:actual_form_id,
						acceptable_files_format:acceptable_files_format,
					},
					onComplete: function(id, fileName, responseJSON){
						if(responseJSON.success=='true'){
							$('.qq-upload-success')
							.find('.qq-upload-failed-text')
							.text('Success')
							.css('color','#ff6600');
							
							if( $('form.quick-edit-form').is(":visible") && $('form.quick-edit-form').find("."+responseJSON.element+"-row").is(":visible") ){
								$('form.quick-edit-form').data('pending', 1 );
							}
							if( responseJSON.stored_name ){
								var i = $('input[name="'+responseJSON.element+'"]').val();
								if( i && i.length > 1 )i = i + ":::" + responseJSON.stored_name;
								else i = responseJSON.stored_name;
								$('input[name="'+responseJSON.element+'"]').val( i );
							}
						}else{
							//alert('failed');
						}
					}
				});
			});
			
		}
	};
	
	var g_report_title = '';
	var g_all_signatories_html = '';
	
	bind_quick_print_function();
	function bind_quick_print_function(){
		$('button#summary-view')
		.live('click', function(){
			$('#example')
			.find('tbody')
			.find('tr')
			.not('.total-row')
			.toggle();
		});
		
		$('body')
		.on('click', 'a.quick-print', function(e){
			e.preventDefault();
			
			var html = get_printable_contents( $(this) );
			
			if( ! g_report_title ){
				g_report_title = $('title').text();
			}
			
			var x=window.open();
			x.document.open();
			x.document.write( '<link href="'+ $('#print-css').attr('href') +'" rel="stylesheet" />' + '<body style="padding:0;">' + g_report_title + html + g_all_signatories_html + '</body>' );
			x.document.close();
			x.print();
		});
		
		$('input.advance-print-preview, input.advance-print, button.quick-print-record')
		.live('click', function(e){
			e.preventDefault();
			
			var html = get_printable_contents( $(this) );
			
			var report_title = $('title').text();
			
			var $form = $('.popover-content').find('form.report-settings-form');
			
			var r_title = $form.find('input[name="report_title"]').val();
			var r_sub_title = $form.find('input[name="report_sub_title"]').val();
			
			var orientation = $form.find('select[name="orientation"]').val();
			var paper = $form.find('select[name="paper"]').val();
			
			var rfrom = $form.find('input[name="report_from"]').val();
			var rto = $form.find('input[name="report_to"]').val();
			var rref = $form.find('input[name="report_ref"]').val();
			
			var r_type = '';
			var r_type = 'mypdf';
			
			var r_user_info = '';
			
			if( $(this).hasClass( 'advance-print' ) ){
				var r_type = $form.find('input[name="report_type"]').filter(':checked').val();
				
				if( $form.find('input[name="report_show_user_info"]').is(':checked') ){
					var r_user_info = 'yes';
				}
			}
			
			var r_signatory = $form.find('input[name="report_signatories"]').val();
			
			var r_template = $form.find('select[name="report_template"]').val();
			var r_ainfo = $form.find('input[name="additional_info"]').val();
			
			var direct_print = 0;
			if( $(this).hasClass("direct-print") )
				direct_print = 1;
			
			g_all_signatories_html = '';
			g_report_title = '';
			
			if( r_title ){
				report_title = '<h3 style="text-align:center;">' + r_title + ' ';
				
				if( r_sub_title ){
					report_title += '<small style="display:block;">' + r_sub_title + '</small>';
				}
				
				report_title += '</h3>';
				
				g_report_title = report_title;
			}
			
			var all_signatories_html = '';
			var signatories_html = '';
			if( r_signatory ){
				if( $form.find('#report-signatory-fields').is(':visible') ){
					
					signatories_html = '<table width="100%">';
					
					$form
					.find('.signatory-fields')
					.each( function(){
						if( $(this).val() ){
							signatories_html += '<tr><td width="20%">' + $(this).val() + '</td><td style="border-bottom:1px solid #dddddd;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
						}
					} );
					
					signatories_html += '</table>';
				}
				
				all_signatories_html = '<div><table width="100%"><tr>';
				
				for( var i = 0; i < r_signatory; i++ ){
					all_signatories_html += '<td style="padding:10px;">';
						all_signatories_html += signatories_html;
					all_signatories_html += '</td>';
				}
				
				all_signatories_html += '</tr></table></div>';
				
				g_all_signatories_html = all_signatories_html;
			}
			
			switch( r_type ){
			case "mypdf":
				ajax_get_url = '?action='+r_type+'&todo=generate_pdf';
				
				ajax_data = {html:report_title + html + all_signatories_html, current_module:$('#active-function-name').attr('function-class'), current_function:$('#active-function-name').attr('function-id'), report_title:report_title, report_show_user_info:r_user_info , orientation:orientation, paper:paper, rfrom:rfrom, rto:rto, rref:rref, report_template:r_template, info:r_ainfo, direct_print:direct_print };
		
				form_method = 'post';
				ajax_data_type = 'json';
				ajax_action = 'request_function_output';
				ajax_container = '';
				ajax_send();
			break;
			case "myexcel":
				ajax_get_url = '?action='+r_type+'&todo=generate_excel';
				ajax_data = {html:html, current_module:$('#active-function-name').attr('function-class'), current_function:$('#active-function-name').attr('function-id') , report_title:report_title, rfrom:rfrom, rto:rto, rref:rref, report_template:r_template, info:r_ainfo };
		
				form_method = 'post';
				ajax_data_type = 'json';
				ajax_action = 'request_function_output';
				ajax_container = '';
				ajax_send();
			break;
			default:
				var x=window.open();
				x.document.open();
				x.document.write( '<link href="'+ $('#print-css').attr('href') +'" rel="stylesheet" />' + '<body style="padding:0;"><div id="watermark"></div>' + report_title + html + all_signatories_html + '</body>' );
				x.document.close();
				
				if( $(this).hasClass( 'advance-print' ) ){
					x.print();
				}
			break;
			}
		});
	};
	
	function get_printable_contents( $printbutton ){
		var html = '';
		
		if( $printbutton.attr('merge-and-clean-data') && $printbutton.attr('merge-and-clean-data') == 'true' ){
			var $content = $( $printbutton.attr('target') ).clone();
			
			//Get Records
			var target_table = $printbutton.attr('target-table');
			var tbody = $content.find(target_table).find('tbody');
			
			//Remove Action Button Column
			tbody.find('.view-port-hidden-table-row').remove();
			tbody.find('.remove-before-export').parents('td').remove();
			
			tbody.find('.hide-custom-view-select-classes').remove();
			
			tbody.find('.line-items-space-row').find("td").html("");
			
			//Get Heading
			var thead = $content.find('.dataTables_scrollHeadInner').find('thead');
			if( thead ){
				thead.find('th').css('width','auto');
				
				//Remove Action Button Column
				thead.find('.remove-before-export').parents('th').remove();
				thead.find('.remove-before-export').remove();
				
				//Adjust Colspan
				thead
				.find('.change-column-span-before-export')
				.attr('colspan', thead.find('.change-column-span-before-export').attr('exportspan') );
			}
			
			//Get Screen Data
			html = '<div id="dynamic"><table class="'+$content.find(target_table).attr('class')+'" width="100%" style="position:relative;" cellspacing="0" cellpadding="0"><thead>'+thead.html()+'</thead><tbody>'+tbody.html()+'</tbody></table></div>';
		}else{
			html = $( $printbutton.attr('target') ).html();
		}
		
		return html;
	};
	
	initiate_tiny_mce_for_popup_textarea( 'textarea#popTextArea' );
	
	function initiate_tiny_mce_for_popup_textarea( selector ){
		
		$( selector ).tinymce({
			// Location of TinyMCE script
			script_url : 'js/tiny_mce/tinymce.min.js',
			
			// General options
			theme: "modern",
			height : 280,
			width : 520,
			plugins: [
					"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
					"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					"table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
			],

			toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
			toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink image | inserttime preview fullpage | forecolor backcolor",
			toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | spellchecker | pagebreak restoredraft",

			menubar: false,
			toolbar_items_size: 'small',

			style_formats: [
					{title: 'Bold text', inline: 'b'},
					{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
					{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
					{title: 'Example 1', inline: 'span', classes: 'example1'},
					{title: 'Example 2', inline: 'span', classes: 'example2'},
					{title: 'Table styles'},
					{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			],

			templates: [
					{title: 'Test template 1', content: 'Test 1'},
					{title: 'Test template 2', content: 'Test 2'}
			]

		});
	};
	
	function no_record_selected_prompt(){
		//alert('display prompt that no record was selected');
		var data = {theme:'alert-info', err:'No Selected Record', msg:'Please select a record by clicking on it', typ:'jsuerror' };
		display_notification( data );
	};
	
	function no_function_selected_prompt(theme, message_title, message_message, auto_close){
		//alert('display prompt that no record was selected');
		
		/*
		var html = '<div data-role="popup" data-dismissible="false" data-transition="slide" id="errorNotice" data-position-to="#" class="ui-content" data-theme="'+theme+'">';
			html += '<a href="" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right no-print">Close</a>';
			html = html + '<h3>'+message_title+'</h3>';
			html = html + '<p>'+message_message+'</p>';
		html = html + '</div>';
		
		ajax_container
		.append(html)
		.trigger("create");
		
		//Display Notification Popup
		display_popup_notice( auto_close );
		*/
	};
	
	function getDataTableID( type ){
		switch( type ){
		case 1:
			if( tmp_data && tmp_data.data_table_name ){
				return tmp_data.data_table_name + '-datatable';
			}
		break;
		}
		return $('#dynamic').attr("data-table") + "-datatable";
	};
	
	function recreateDataTables(){
		//INITIALIZE DATA TABLES
		var data_table_id = getDataTableID( 1 );
		var tb = $('#'+data_table_id).attr("class-name");
		
		oTable = $('#'+data_table_id).dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "ajax_server/"+tb+"_server.php",
			"sScrollY": "350",
			"sPaginationType": "full_numbers",
			"sScrollX": "100%",
			"bJQueryUI": true,
			"bDestroy": true,
			//"sDom": "Rlfrtip",
			"sDom": 'R<"H"lfr>t<"F"ip>',
			"bStateSave": true,
			"iDisplayLength": 25,
			 "aoColumnDefs": [ 
				 { "bSortable": false, "aTargets": [ 0 ] }
			   ],
			/*
			THIS CODE HAS TO BE INSERTED BEFORE THE TABLE REFRESHES
				//Return inline-edit fields to their container
				if( $('#inline-edit-form-wrapper') && $('.inline-form-element') ){
					$('.inline-form-element')
					.removeClass('inline-form-element')
					.addClass('form-gen-element')
					.appendTo(	$('#inline-edit-form-wrapper').find('form') );
				}
			*/
			"fnInitComplete": function () {
				//Store Normal Table
				//$('#cloned-content-area').html($('#content-area').html());
				/*
				new FixedColumns( oTable, {
					"iLeftColumns": 4,
					"iLeftWidth": 450,
				} );
				*/
				$('#'+ $(this).attr("id") +'_filter')
				.find('input')
				.attr({
					placeholder:"Quick Search",
					title:"Perform quick search",
				})
				.addClass("form-control input-sm");
				
				$('#'+$(this).attr("id")+'_length')
				.find('select')
				.css({
					'padding': '2px',
					'margin': '2px',
					'width': 'auto',
				});
				
				//UPDATE MORE DETAILS COLUMN SELECTOR
				create_field_selector_control( $(this) );
				
				$('a.pop-up-button').on('click', function(e){
					if( $('#refresh-datatable').is(":visible") ){					
						$('#refresh-datatable')
						.click();	
					}
					
					e.preventDefault();
				});
				
				$('.pop-up-button').popover({
					html:true,
					container:'#data-table-section',
					content:function(){
						if( ! $(this).data("insert") ){
							var html = $(this).next('div.pop-up-content').html();
							$(this).data("insert", 1);
							return html;
						}
					},
				});
				
                if( $('.dropdown-toggle') ){
                    $('.dropdown-toggle').dropdown();
                }
				/*
				setTimeout( function(){ 
					$(".dataTables_scrollBody").find(".dataTable.display").dataTable().fnAdjustColumnSizing(); 
					setTimeout( function(){ 
						$(".dataTables_scrollBody").find(".dataTable.display").dataTable().fnAdjustColumnSizing(); 
					}, 800 );
				}, 2000 );
                */
				setTimeout( function(){ nwResizeWindow.resizeWindow(); }, 300 );
			},
			"fnDrawCallback": function() {
			   //Optimize Search Field
			   
				select_record_click_function( $(this) );
		
				//Bind details open and close event to table
				bind_details_button_click();
				
                bind_details_view_control();
                
				//Rehighlight Selected Record
				rehighlight_selected_record_function( $(this) );
				
				bind_page_up_down_scrolling( $(this) );
				
			}
		
		})
		.addClass("activated-table");
		
	};
	
	function bind_popover_controls(){
		var $parent = $(this).parent();
		
		$('a.pop-up-button-control')
		.not("bound")
		.on("click", function(){
			//if( $(this).data("remove") )
				$(this).attr("function-id","");
			//else
				$(this).data("remove",1);
		});
		
		$('a.pop-up-button-control')
		.not("bound")
		.popover({
			html:true,
			container:$("#form-home-control-handle"),
			content:function(){
				if( ! $(this).data("insert") ){
					var html = $(this).next('div.pop-up-content').html();
					$(this).data("insert", 1);
					$(this).next('div.pop-up-content').remove();
					return html;
				}
			},
		})
		.addClass("bound");;
		
	};
	
	function bind_details_view_control(){
        //Bind Delete Buttons Event
        if( $('a.quick-details-field') ){
            $('a.quick-details-field')
            .bind('click',function(e){
                e.preventDefault();
                
                ajax_data = {};
                form_method = 'post';
                ajax_data_type = 'json';
                
                ajax_action = 'request_function_output';
                ajax_container = '';
                ajax_get_url = $(this).attr('action');
                ajax_send();
            });
        }
    };
	
	function activate_custom_view_select_button(){
		$('#custom-view-select')
		.find('a.custom-view-select-button')
		.bind('click',function(e){
			e.preventDefault();
			
			$('#custom-view-select-text')
			.text( $(this).text() );
			
			if( $(this).hasClass('hide-selected') && $(this).attr('data-class') ){
				$( $(this).attr('data-class') )
				.addClass('hide-custom-view-select-classes');
			}
			
			if( $(this).hasClass('show-selected') && $(this).attr('data-class') ){
				$('.hide-custom-view-select-classes')
				.removeClass('hide-custom-view-select-classes');
				
				$( "tr.even, tr.odd" )
				.not( $(this).attr('data-class') )
				.addClass( 'hide-custom-view-select-classes' );
			}
			
			if( $(this).hasClass('show-all') ){
				$('.hide-custom-view-select-classes')
				.removeClass('hide-custom-view-select-classes');
			}
			
		});
	};
    
	//bind_page_up_down_scrolling();
	function bind_page_up_down_scrolling( $context ){
		
		//$context
		//.not('.keyed-down')
		$(document)
		.on('keydown', function(e){
			//get selected record
			if( ! single_selected_record )return;
			var allow_scroll = 1;
			/*
			var allow_scroll = 0;
			if( ( e.keyCode == 40 || e.keyCode == 38 ) && $('form.quick-edit-form').find('input, select, textarea').is(':focus') ){
				allow_scroll = 1;
				
				$('form.quick-edit-form')
				.find('input[name="stepmaxstep"]')
				.val( '10' );
			}
			*/
			if( ! $('input, select, textarea').is(':focus') || allow_scroll ){
				
				var scrollTopAmountLarge = 300;
			
				var scrollTopAmountSmall = 50;
				
				var scrollTopAmount = 0;
				var scrollAlongAmount = 0;
				
				var scrollTopToSelectedRow = 0;
				
				var current_view;
				//var $element = $(this).parents(".dataTables_scrollBody");
				//var $element_to_scroll = $(this);
				
				var $element = $("#dynamic");
				var $element_to_scroll = $("#dynamic").find(".dataTables_scrollBody");
				
				current_view = 'table';
				//console.log(22);
				
				switch(e.keyCode){
				case 35:	//End key
					scrollAlongAmount = $element.width();
				break;
				case 36:	//Home key
					scrollAlongAmount = $element.width() * -1;
				break;
				case 37:	//Left arrow
					scrollAlongAmount = (scrollTopAmountSmall * -1);
				break;
				case 38:	//Up arrow
					scrollTopAmount = (scrollTopAmountSmall * -1);
					scrollTopToSelectedRow = 2;
				break;
				case 39:	//Right arrow
					scrollAlongAmount = (scrollTopAmountSmall);
				break;
				case 40:	//Down arrow
					scrollTopAmount = scrollTopAmountSmall;
					scrollTopToSelectedRow = 1;
				break;
				case 34:	//Page down button
					//scrollTopAmount = scrollTopAmountLarge;
				break;
				case 33:	//Page up button
					//scrollTopAmount = (scrollTopAmountLarge * -1);
				break;
				case 65:	//A Ctrl [17]
					if(e.ctrlKey){
						e.preventDefault();
						
						multiple_selected_record_id = '';
						
						details_of_multiple_selected_records = '';
						
						$element
						.find('tr:visible')
						.each(function(){
							var id_of_record = $(this).find('.datatables-record-id').attr('id');
							if( id_of_record ){									
								multiple_selected_record_id = multiple_selected_record_id +':::'+id_of_record;
								
								//Push All Details to display container
								var passed_value = $('#main-details-table-'+id_of_record).html();
								if( passed_value )
									details_of_multiple_selected_records += passed_value;
							}
						});
						
						if( $('#record-details-home').is(':visible') && details_of_multiple_selected_records ){
							$('#record-details-home')
							.html( details_of_multiple_selected_records );
						}
						
						$element
						.find('tr')
						.addClass('row_selected');
						
						return false;
					}
				break;
				}
				
				if(scrollTopAmount){
					var scrollPosition = $element_to_scroll.scrollTop();
					
					if( scrollTopToSelectedRow && single_selected_record ){
						e.preventDefault();
						
						if( $('#'+single_selected_record) ){
							
							switch( scrollTopToSelectedRow ){
							case 1:
								$('#'+single_selected_record).parents('tr').next().click();
								scrollTopToSelectedRow = $('#'+single_selected_record).parents('tr').height();
							break;
							case 2:
								$('#'+single_selected_record).parents('tr').prev().click();
								scrollTopToSelectedRow = $('#'+single_selected_record).parents('tr').height() * -1;
							break;
							}
						}else{
							scrollTopToSelectedRow = 0;
						}
					}else{
						scrollTopToSelectedRow = 0;
					}
					
					scrollTopToSelectedRow  = 0;
					scrollPosition = 0;
					var ref = $element.offset().top;
					scrollTopAmount = $element.scrollTop() + $('#'+single_selected_record).parents('tr').offset().top - ref;
				
					if( scrollTopToSelectedRow ){
						$element_to_scroll
						.scrollTop( scrollPosition + scrollTopToSelectedRow );
					}else{
						$element
						.scrollTop(scrollPosition + scrollTopAmount);
					}
				}
				
				if(scrollAlongAmount){
					var scrollPosition = $element_to_scroll.scrollLeft();
					
					$element_to_scroll
					.scrollLeft(scrollPosition + scrollAlongAmount);
				}
				
				scrollTopAmount = 0;
				scrollAlongAmount = 0;
			}
		})
		.addClass('keyed-down');
		/*
		$('#example_wrapper')
		.find('.dataTables_scrollBody')
		.bind('keydown',function(e){
			alert(e.code);
			$(this).scrollTop($(this).scrollTop()+scrollTopAmountLarge);
		});
		*/
	};
	
	function scroll_to_top_of_selected_record(){
		var scrollTopAmount = 0;
		var ref = 0;
		
		var scrollTopToSelectedRow = 0;
		
		if( single_selected_record && $('#'+single_selected_record) ){
			var $element_to_scroll = $( "table#" + $('#'+single_selected_record).parents(".dynamic").attr("data-table") );
			var $element = $('#'+single_selected_record).parents(".dataTables_scrollBody");
			scrollTopToSelectedRow = $('#'+single_selected_record).parents('tr').height();
			
			
			var scrollPosition = $element_to_scroll.scrollTop();
			var ref = $element.offset().top;
			scrollTopAmount = $element.scrollTop() + $('#'+single_selected_record).parents('tr').offset().top - ref;
			
			$element
			.scrollTop(scrollPosition + scrollTopAmount);
		}
	};
	
	bind_create_field_selector_control();
	function bind_create_field_selector_control(){
		//bind click events
		$('ul#record-details-field-selector')
		.find('input[type="checkbox"]')
		.live('change',function(){
			if( $(this).attr('checked') )
				$( '.details-section-container-row-'+$(this).val() ).show();
			else
				$( '.details-section-container-row-'+$(this).val() ).hide();
		});
	};
	
	function create_field_selector_control( $context ){
		
		var $details_table = $context.find('table.main-details-table:first');
		
		var list_elements = '';
		
		if( $details_table ){
			$details_table
			.find('tr')
			.each(function(){
				
				list_elements += '<li><label class="checkbox"><input type="checkbox" value="'+$(this).attr('jid')+'" checked="checked" />'+$(this).find('td.details-section-container-label').text()+'</label></li>';
				
			});
			
			$('ul#record-details-field-selector')
			.html( list_elements );
			
		}
	};
	
	//Bind Multi-select option tooltip
	var timer_interval;
	var mouse_vertical_position;
	
	var progress_bar_timer_id;
	function progress_bar_change(){
		var total = 97;
		var step = 1;
		
		if(function_click_process==0){
			var $progress = $('#virtual-progress-bar').find('.progress-bar');
			
			if($progress.data('step') && $progress.data('step')!='undefined'){
				step = $progress.data('step');
			}
			
			var percentage_step = (step/total)*100;
			++step;
			
			if( percentage_step > 100 ){
				$progress
				.css('width', '100%');
				
				$('#virtual-progress-bar')
				.remove();
				
				//Refresh Page
				function_click_process = 1;
				
				//Stop All Processing
				//window.stop();
				
				//Display Timeout Error Message
				var theme = 'a';
				var message_title = 'Server Script Timeout Error';
				var message_message = "The request was taking too long!<br /><br /><h4>Request Parameters</h4>" + ajax_request_data_before_sending_to_server;
				var auto_close = 'no';
				
				no_function_selected_prompt(theme, message_title, message_message, auto_close);
				
			}else{
				$progress
				.data('step',step)
				.css('width', percentage_step+'%');
				
				progress_bar_timer_id = setTimeout(function(){
					progress_bar_change();
				},1000);
			}
		}else{
			$('#virtual-progress-bar')
			.find('.progress-bar')
			.css('width', '100%');
			
			setTimeout(function(){
				$('#virtual-progress-bar')
				.remove();
			},1500);
		}
	};
	
	function display_tooltip(me, name, removetip){
		
		if( removetip ){
			$('#ogbuitepu-tip-con').fadeOut(800);
			return;
		}
		
		//Check if tooltip is set
		if(me.attr('tip') && me.attr('tip').length > 1){
			$('#ogbuitepu-tip-con')
			.find('> div')
			.html(me.attr('tip'));
			
			//Display tooltip
			var offsetY = 8;
			var offsetX = 12;
			
			//var left = me.offset().left - (offsetX + $('#ogbuitepu-tip-con').width() );
			//var top = (me.offset().top + ((me.height() + offsetY)/2)) - ($('#ogbuitepu-tip-con').height()/2);
			
			var left = me.offset().left;
			var top = (me.offset().top + ((me.height() + offsetY)));
			
			if( parseFloat( name ) ){
				top = (name) - ($('#ogbuitepu-tip-con').height()/2);
			}
			
			$('#ogbuitepu-tip-con')
			.find('> div')
			.css({
				padding:me.css('padding'),
			});
			
			$('#ogbuitepu-tip-con')
			.css({
				width:me.width()+'px',
				top:top,
				left:left,
			})
			.fadeIn(800);
		}else{
			//Hide tooltip container
			$('#ogbuitepu-tip-con').fadeOut(800);
		}
		
	};
	
	$('<style>.invalid-data{ background:#faa !important; }</style><div id="ogbuitepu-tip-con"><div></div></div>').prependTo('body');
	$('#ogbuitepu-tip-con')
	.css({
		position:'absolute',
		display:'none',
		top:0,
		left:0,
		backgroundColor:'transparent',
		backgroundImage:'url('+pagepointer+'images/tip-arrow-r.png)',
		backgroundPosition:'top center',
		backgroundRepeat:'no-repeat',
		opacity:0.95,
		paddingTop:'11px',
		/*width:'220px',*/
		height:'auto',
		color:'#fff',
		zIndex:90000,
	});
	$('#ogbuitepu-tip-con')
	.find('> div')
	.css({
		position:'relative',
		background:'#ee1f19',
		opacity:0.95,
		/*padding:'5%',*/
		width:'100%',
		height:'95%',
		color:'#fff',
		textShadow:'none',
		borderRadius:'8px',
		boxShadow:'1px 1px 3px #222',
		fontSize:'0.85em',
		fontFamily:'arial',
	});

	function activate_tooltip_for_form_element( $form ){
		$form
		.find('.form-gen-element')
		.bind('focus',function(){
			display_tooltip($(this) , $(this).attr('name'), false);
		});
		
		$form
		.find('.form-gen-element')
		.bind('blur',function(){
			display_tooltip( $(this) , '', true );
		});
	};
	
	function activate_validation_for_required_form_element( $form ){
		$form
		.find('.form-element-required-field')
		.bind('blur',function(){
			validate( $(this) );
		});
		
		$form
		.not('.skip-validation')
		.bind('submit', function(){
			validate_and_submit_form( $(this) );
		});
		
		$form
		.not('.skip-validation')
		.find('input#form-gen-submit')
		.bind("click", function(){
			validate_and_submit_form( $(this).parents("form") );
		});
		
	};
	
	function validate_and_submit_form( $me ){
		
		$me
		.find('.form-element-required-field')
		.blur();
		
		if( $me.find('.form-element-required-field').hasClass('invalid-data') ){
			$me
			.find('.invalid-data:first')
			.focus();
			
			var html = "<br /><br /><strong>Form Fields with Invalid Data</strong><br />";
			var no = 0;
			$me
			.find('.invalid-data')
			.each(function(){
				++no;
				html += no + ". " + $(this).parents(".control-group").find(".control-label").text() + "<br />";
			});
			
			$me.data('do-not-submit', true);
			
			//display notification to fill all required fields
			var data = {
				typ:'jsuerror',
				err:'Invalid Form Field',
				msg:'Please do ensure to correctly fill all required fields with appropriate values' + html,
			};
			display_notification( data );
			
			return false;
		}
		
		$me.data('do-not-submit', false);		
	};
	
	function validate( me ){
		//var e = $('#required'+name);
		//alert(e.attr('alt'));
		
		if( testdata( me.val() , me.attr('alt') ) ){
			if(me.hasClass('invalid-data')){
				me.removeClass('invalid-data').addClass('valid-data');
			}else{
				me.addClass('valid-data');
			}
		}else{
			if(me.hasClass('valid-data')){
				me.addClass('invalid-data').removeClass('valid-data');
			}else{
				me.addClass('invalid-data');
			}
		}
	};
	
	function testdata(data,id){
		
		switch (id){
		case 'text':
		case 'textarea':
		case 'upload':
			if(data.length>0)return 1;
			else return 0;
		break;
		case 'category':
			if(data.length>11)return 1;
			else return 0;
		break;
		case 'number':
		case 'currency':
			/*/[^0-9\-\.]/g*/
			data = ( data.replace( ",", '' ) );
			return (data - 0) == data && data.length > 0;
		break;
		case 'email':
			return vemail(data);
		break;
		case 'password':
			return vpassword(data);
		break;
			if(data.length>6)return 1;
			else return 0;
		break;
		case 'phone':
		case 'tel':
			return vphone(data);
			break;
		case 'select':
		case 'multi-select':
			return data;
			break;
		case 'date':
			return data;
			break;
		default:
			return 0;
		}
	};
	
	function vphone(phone) {
		var phoneReg = /[a-zA-Z]/;
		if( phone.length<5 || phoneReg.test( phone ) ) {
			return 0;
		} else {
			return 1;
		}
	};
	
	function vemail(email) {
		
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if( email.length<1 || !emailReg.test( email ) ) {
			return 0;
		} else {
			return 1;
		}
	};
	
	var pass = 0;
	function vpassword(data){
		if($('input[type="password"]:first').val()!=pass){
			pass = 0;
		}
		
		if(!pass){
			//VERIFY PASSWORD
			if( data.length > 6 ){
				/*
				//TEST FOR AT LEAST ONE NUMBER
				var passReg = /[0-9]/;
				if( passReg.test( data ) ) {
					//TEST FOR AT LEAST ONE UPPERCASE ALPHABET
					passReg = /[A-Z]/;
					if( passReg.test( data ) ){
						//TEST FOR AT LEAST ONE LOWERCASE ALPHABET
						passReg = /[a-z]/;
						if( passReg.test( data ) ){
							//STORE FIRST PASSWORD
							pass = data;
							return 1;
						}else{
							//NO LOWERCASE ALPHABET IN PASSWORD
							pass = 0;
							return 0;
						}
					}else{
						//NO UPPERCASE ALPHABET IN PASSWORD
						pass = 0;
						return 0;
					}
				}else{
					//NO NUMBER IN PASSWORD
					pass = 0;
					return 0;
				}
				*/
				pass = data;
				return 1;
			}else{ 
				pass = 0;
				return 0;
			}
			/*
			a.	User ID and password cannot match
			b.	Minimum of 1 upper case alphabetic character required
			c.	Minimum of 1 lower case alphabetic character required
			d.	Minimum of 1 numeric character required
			e.	Minimum of 8 characters will constitute the password
			*/
		}else{
			//CONFIRM SECOND PASSWORD
			if(data==pass)return 1;
			else return 0;
		}
	};
	/******************************************************/
	
	function refresh_tree_view(){
		if( $('#ui-navigation-tree') ){
			var instance = $('#ui-navigation-tree').jstree(true);
			if( instance )instance.refresh();
		}
	};
	
	function activate_tree_view_main( selector ){
		var action = "budget";
		var record_id = "";
		var todo = "get_operators";
		var operator_id = "";
		
		if( tmp_data && tmp_data.tree_action && tmp_data.tree_todo ){
			action = tmp_data.tree_action;
			todo = tmp_data.tree_todo;
			
			if( tmp_data.record_id )record_id = tmp_data.record_id;
			if( tmp_data.operator_id )operator_id = tmp_data.operator_id;
		}
		$( selector )
		.on("changed.jstree", function (e, data) {
			if(data.selected.length) {
				switch( $(this).attr("id") ){
				case 'ui-navigation-tree':
					//console.log(data.instance.get_node(data.selected[0]));
					var d = data.instance.get_node(data.selected[0]).id;
					var ids = d.split(':::');
					if( ids.length > 3 ){
						class_name = ids[0];
						var db_class_name = ids[0];
						var mc = '';
						var md = '';
						if( ids[4] ){
							mc = ids[4];
							switch( class_name ){
							case "cash_calls_reporting_view":
								db_class_name = "cash_calls_" + ids[2] + "_" + ids[3];
							break;
							}
							
							switch( class_name ){
							case "cash_calls_reporting_view":
							case "tendering":
								//check if parent table is visible
								if( ! $('table.display').hasClass( db_class_name ) ){
									var f = data.instance.get_node(data.selected[0]).parent;
									var ids = f.split(':::');
									
									var data = {theme:'alert-info', err:'Parent Item was not clicked', msg:'Please click item '+data.instance.get_node(data.selected[0]).text+' once more to filter', typ:'jsuerror' };
									display_notification( data );
								}
							break;
							}
						}
						
						if( ids[5] ){
							md = ids[5];
						}
						switch( class_name ){
						case "tenders_and_contracts":
						case "tendering":
						case "tendering_reports":
							if( ids[1] != 'generate_reports'  && ids[1] != 'display_new_tendering_view' )
								ids[1] = "display_datatable_view";
							
							var display_view = $("#tendering-select-tree-view-activator").val();
							ajax_data = { action:ids[0], todo:ids[1], year_and_month:ids[2], tender_filter:ids[3], operator_filter:mc, display_view:display_view };
						break;
						case "exploration":
						case "exploration_drilling":
						case "exploration_activities":
						case "geophysics_plan_and_actual_performance":
							if( ids[1] != 'generate_reports'  && ids[1] != 'display_new_record_view' )
								ids[1] = "display_datatable_view";
							
							var display_view = $("select#exploration-select-tree-view-activator").val();
							ajax_data = { action:ids[0], todo:ids[1], year_and_month:ids[2], tender_filter:ids[3], operator_filter:mc, display_view:display_view };
						break;
						case "divisional_reports":
							var display_view = $("select#divisional-reports-select-tree-view-activator").val();
							ajax_data = { action:ids[0], todo:ids[1], month:ids[2], budget_id:ids[3], main_code:mc, department:md, display_view:display_view };
						break;
						default:
							ajax_data = { action:ids[0], todo:ids[1], month:ids[2], budget_id:ids[3], main_code:mc, department:md };
						break;
						}
						
						ajax_get_url = "";
						form_method = 'get';
						ajax_data_type = 'json';
						ajax_action = 'request_function_output';
						ajax_container = '';
						ajax_send();
					}
				break;
				case 'reports-table-of-content-tree-view':
					var d = data.instance.get_node(data.selected[0]).id;
					$('iframe#iframe-container-of-content')
					.attr( 'src', $('iframe#iframe-container-of-content').attr('data-src')+'#'+d );
					
					$(document).scrollTop(0);
				break;
				}
			}
		})
		.jstree({
			'core' : {
				'data' : {
					"url" : pagepointer+'php/ajax_request_processing_script.php?action='+action+'&todo='+todo+'&id='+record_id+'&record_id='+record_id+'&operator='+operator_id,
					"dataType" : "json", // needed only if you do not supply JSON headers
					"data" : function (node) {
						return { "id" : node.id, "method" : node.id };
					}
				}
			}
		});
		
	};
	
	function activate_tree_view(){
		$("select#report_type-select-tree-view-activator")
		.bind( "change" , function(e){
			var l = $(this).val();
			if( l ){
				tmp_data.tree_action = l;
				tmp_data.tree_todo = "get_tree_view";
				
				$("#ui-navigation-tree-container")
				.html('<div id="ui-navigation-tree" class="demo"></div>');
				
				activate_tree_view_main( '#ui-navigation-tree' );
				
				tmp_data = {};
			}
		});
		$("#refresh-tree-view")
		.on("click", function(){
			refresh_tree_view();
		});
	};
	
	function activate_exploration_tree_view(){
		$("select#exploration-select-tree-view-activator")
		.bind( "change" , function(e){
			var l = $(this).val();
			if( l ){
				tmp_data.tree_action = "exploration";
				tmp_data.tree_todo = "get_exploration_tree_view";
				tmp_data.record_id = l;
				
				$("#tree-view-selector-container")
				.html('<div id="ui-navigation-tree" class="demo"></div>');
				
				activate_tree_view_main( '#ui-navigation-tree' );
				
				tmp_data = {};
			}
		}).change();
	};
	
	function activate_tendering_tree_view(){
		$("select#tendering-select-tree-view-activator")
		.bind( "change" , function(e){
			var l = $(this).val();
			if( l ){
				tmp_data.tree_action = "tenders_and_contracts";
				tmp_data.tree_todo = "get_tenders_and_contracts_tree_view";
				tmp_data.record_id = l;
				
				$("#tree-view-selector-container")
				.html('<div id="ui-navigation-tree" class="demo"></div>');
				
				activate_tree_view_main( '#ui-navigation-tree' );
				
				tmp_data = {};
			}
		}).change();
	};
	
	function activate_tree_view_new(){
		if( $("select#divisional-reports-select-tree-view-activator") && $("select#divisional-reports-select-tree-view-activator").is(":visible") ){
			if( tmp_data ){
				tmp_data.operator_id = $("select#divisional-reports-select-tree-view-activator").val();
				
				$("select#divisional-reports-select-tree-view-activator")
				.attr("tree_action", tmp_data.tree_action )
				.attr("tree_todo", tmp_data.tree_todo );
			}
			
			$("select#divisional-reports-select-tree-view-activator")
			.not("activated")
			.on("change", function(e){
				$('#tree-view-selector-container').html('<div id="ui-navigation-tree" class="demo"></div>');
				tmp_data.tree_action = $(this).attr("tree_action");
				tmp_data.tree_todo = $(this).attr("tree_todo");
				tmp_data.operator_id = $(this).val();
				activate_tree_view_main( '#ui-navigation-tree' );
			})
			.addClass("activated");
		}
		activate_tree_view_main( '#ui-navigation-tree' );
	};
	
	function activate_divisional_report_content_tree_view(){
		activate_tree_view_main( '#reports-table-of-content-tree-view' );
	};
	
	function activate_export_to_word_button(){
		$("#save-editable-content")
		.bind("click", function(){
			var html = $( $(this).attr('iframe-target') ).contents().find("body").html();
			var report_title = $( $(this).attr('title-target') ).text();
			var report_id = $( $(this).attr('iframe-target') ).attr('report-id');
			
			ajax_get_url = $(this).attr('action');
			ajax_data = { report_id:report_id, html:html, current_module:$('#active-function-name').attr('function-class'), current_function:$('#active-function-name').attr('function-id') , report_title:report_title };

			form_method = 'post';
			ajax_data_type = 'json';
			ajax_action = 'request_function_output';
			ajax_container = '';
			ajax_send();
		});
	};
	
	function activate_export_pdf_button(){
		$("#export-to-pdf")
		.bind("click", function(){
			var html = $( $(this).attr('iframe-target') ).contents().find("body").find(".body").html();
			var report_title = $( $(this).attr('title-target') ).text();
			var report_id = $( $(this).attr('iframe-target') ).attr('report-id');
			var exclude_header = "";
			if( $( $(this).attr('iframe-target') ).attr('exclude-header') );
				exclude_header = $( $(this).attr('iframe-target') ).attr('exclude-header');
			
			ajax_get_url = $(this).attr('action');
			ajax_data = { report_id:report_id, html:html, current_module:$('#active-function-name').attr('function-class'), current_function:$('#active-function-name').attr('function-id') , report_title:report_title, exclude_header:exclude_header, rfrom:$(this).attr("data-from"), rto:$(this).attr("data-to"), rref:$(this).attr("data-ref") };

			form_method = 'post';
			ajax_data_type = 'json';
			ajax_action = 'request_function_output';
			ajax_container = '';
			ajax_send();
		});
	};
	
	function activate_highcharts(){
		if( tmp_data && Object.getOwnPropertyNames( tmp_data ).length && tmp_data.highchart_data && tmp_data.highchart_container_selector ){
			nwHighCharts.initChart( tmp_data );
		}else{
			alert("Could not Generate Chart, due to invalid data");
		}
	};
	
	function activate_and_export_highcharts(){
		if( tmp_data && Object.getOwnPropertyNames( tmp_data ).length && tmp_data.highchart_data && tmp_data.highchart_container_selector ){
			var dataString = nwHighCharts.initChartAndExport( tmp_data );
			
			$.ajax({
				type: 'POST',
				data: dataString,
				url: pagepointer + 'classes/highcharts/exporting-server/php/php-batik/',
				success: function( data ){
					//console.log( data );
					resume_reprocessing();
				}
			});
		}else{
			alert("Could not Generate Chart, due to invalid data");
		}
	};
	
	function pause_reprocessing(){
		//console.log('pause');
		if( tmp_data && Object.getOwnPropertyNames( tmp_data ).length && tmp_data.re_process_code ){
			if( tmp_data.highchart_data )
				delete tmp_data.highchart_data;
			
			if( tmp_data.html )
				delete tmp_data.html;
			
			$(document).data( 're_process', tmp_data );
			tmp_data.re_process = 0;
		}else{
			alert("Could not Pause Processing, due to invalid data");
		}
	};
	
	function resume_reprocessing(){
		if( $(document).data( 're_process' ) ){
			var data = $(document).data( 're_process' );
			if( data.re_process_code )trigger_new_ajax_request( data );
			$(document).data( 're_process', '' );
		}else{
			alert("Could not Resume Processing");
		}
	};
		
	//activate_country_select_field();
	function activate_country_select_field(){
		function_click_process = 1;
		if( $('.state-select-to-city-field') && $('.country-select-to-state-field') ){
			var $o = $('.state-select-to-city-field');
			var $select = $('<select class="'+$o.attr('class')+'"></select>');
			
			$select
			.insertAfter( $o );
			
			$o
			.hide();
			
			var $o = $('.cities-select-field');
			var $select = $('<select class="'+$o.attr('class')+'"></select>');
			
			//.html( data.html )
			
			$select
			.insertAfter( $o );
			
			$o
			.hide();
			
			
			$('select.state-select-to-city-field')
			.on('change', function(){
				if( $(this).val() ){
					$('input.state-select-to-city-field')
					.val( $(this).val() );
					
					ajax_data = {action:'cities_list', todo:'get_cities_in_a_country_option_list', record_id:$(this).val()};
					form_method = 'get';
					ajax_data_type = 'json';
					ajax_action = 'request_function_output';
					ajax_container = '';
					ajax_send();
				}
			});
			
			$('select.cities-select-field')
			.on('change', function(){
				if( $(this).val() ){
					switch( $(this).val() ){
					case 'specify':
						$(this)
						.remove();
						
						$('input.cities-select-field')
						.val('')
						.show();
					break;
					default:
						$('input.cities-select-field')
						.val( $(this).val() );
					break;
					}
				}
			});
			
			
			$('.country-select-to-state-field')
			.on('change', function(){
				
				if( ! ( $('input#country-select-hack') && $('input#country-select-hack').hasClass('hacked') ) ){
					$('<input type="hidden" name="'+$(this).attr('name')+'" id="country-select-hack" class="hacked"/>')
					.insertAfter( $(this) );
					
					$(this).attr('name', '');
				}
				
				$('input#country-select-hack').val( $(this).val() );
				
				ajax_data = {action:'state_list', todo:'get_states_in_a_country_option_list', record_id:$(this).val()};
				form_method = 'get';
				ajax_data_type = 'json';
				ajax_action = 'request_function_output';
				ajax_container = '';
				ajax_send();
				
			})
			.change();
			
		}
	};
	
	function activate_state_select_field(){
		if( $('select.state-select-to-city-field') && ! $('select.state-select-to-city-field').is(':visible') ){
			$('.state-select-to-city-field').hide();
			
			$('select.state-select-to-city-field')
			.show();
		}
		if( $('select.state-select-to-city-field') ){
			if( $('input.state-select-to-city-field').val() ){
				
				if( $('select.state-select-to-city-field').find('option[value="'+$('input.state-select-to-city-field').val()+'"]') ){
					$('select.state-select-to-city-field').val( $('input.state-select-to-city-field').val() );
				}
				
				if( $('select.state-select-to-city-field') && ! $('select.state-select-to-city-field').val() ){
					var x = $('input.state-select-to-city-field').val();
					y = x.replace( /[0-9\-\.]/g, '' );
					if( y ){
						$('select.state-select-to-city-field').prepend( "<option selected='selected' value='"+$('input.state-select-to-city-field').val()+"'>"+ $('input.state-select-to-city-field').val() +"</option>" );
					}
				}
			}
			
			$('select.state-select-to-city-field')
			.change();
		}
	};
	
	function activate_city_select_field(){
		if( $('select.cities-select-field') && ! $('select.cities-select-field').is(':visible') ){
			$('.cities-select-field').hide();
			
			$('select.cities-select-field')
			.show();
		}
		if( $('select.cities-select-field') ){
			if( $('input.cities-select-field').val() ){
				
				if( $('select.cities-select-field').find('option[value="'+$('input.cities-select-field').val()+'"]') ){
					$('select.cities-select-field').val( $('input.cities-select-field').val() );
				}
				
				if( $('select.cities-select-field') && ! $('select.cities-select-field').val() ){
					var x = $('input.cities-select-field').val();
					y = x.replace( /[0-9\-\.]/g, '' );
					if( y ){
						$('select.cities-select-field').prepend( "<option selected='selected' value='"+$('input.cities-select-field').val()+"'>"+ $('input.cities-select-field').val() +"</option>" );
					}
				}
			}
			
			$('select.cities-select-field')
			.change();
		}
	};
	
	function activate_no_state_select_field(){
		if( $('select.state-select-to-city-field') ){
			$('.state-select-to-city-field').show();
			
			$('select.state-select-to-city-field').hide();
		}
	};
	
	function activate_no_city_select_field(){
		if( $('select.cities-select-field') ){
			$('.cities-select-field')
			.show();
			
			$('select.cities-select-field').hide();
		}
	};

	function show_hidden_columns(){
		$(".hidden-column")
		.removeClass("hidden-column");
		
		$(".hidden-column-shown")
		.removeClass( $(".hidden-column-shown").attr("old-class") )
		.addClass( $(".hidden-column-shown").attr("new-class") )
		.removeClass("hidden-column-shown");
		
		if( $( "table.activated-table") ){
			
			$( "table.activated-table")
			.dataTable()
			.fnAdjustColumnSizing();
		}
	};
	
	var isOverIFrame = false;
	$('body')
	.on('mouseout', 'iframe[name="help"]', function(e){
		$(document).scrollTop(0);
	});
});

var nwDisplayNotification = function () {
	return {
	notificationTimerID: "",
	display_notification: function ( data ){
		if( data.typ ){
			var theme = 'alert-danger';
			
			if( data.theme ){
				theme = data.theme;
			}
			
			switch(data.typ){
			case "search_cleared":
			case "report_generated":
			case "searched":
			case "saved":
			case "jsuerror":
			case "uerror":
			case "deleted":
			case "serror":
				//Refresh Token
				if( data.tok && $('form') ){
					$('form')
					.find('input[name="processing"]')
					.val( data.tok );
				}
				
				var html = '<div class="alert ' + theme + ' alert-block1 alert-dismissable">';
				  html += '<button type="button" class="close" id="alert-close-button" data-dismiss="alert"></button>';
				  html += '<h4>' + data.err + '</h4>';
				  html += data.msg;
				html += '</div>';
				
				var $notification_container = $('#notification-container');
				
				if( nwDisplayNotification.notificationTimerID )clearTimeout( nwDisplayNotification.notificationTimerID );
				
				
				$notification_container
				.html( html );
				
				switch(data.typ){
				case "report_generated":
					set_function_click_event();
				break;
				default:
					nwDisplayNotification.notificationTimerID = setTimeout( function(){
						$('#notification-container')
						.empty();
					} , 15000 );
				break;
				}
				
				$('#alert-close-button')
				.bind('click', function(){
					$('#notification-container')
					.empty();
				});
				
			break;
			}
		}
	}
		
	};
}();

var nwResizeWindow = function () {
	
    return {
        //main function to initiate the module
        e1: "#dash-board-main-content-area",
        e2: ".dataTables_scrollBody",
        e3: "#excel-import-form-container",
        e4: "#chart-container-parent",
        e5: ".resizable-height",
        init: function () {
			$(window).on("resize", function(){
				nwResizeWindow.resizeWindow();
			});
        },
		resizeWindow: function () {
			
			var sel = nwResizeWindow.e2;
			if( $(sel).is(":visible") ){
				if( $(sel).length > 1 ){
					$(sel).each(function(){
						nwResizeWindow.resizeWindowAction( $(this) );
					});
				}else{
					nwResizeWindow.resizeWindowAction(sel);
				}
			}else{
				var sel = nwResizeWindow.e3;
				if( $(sel).is(":visible") ){
					nwResizeWindow.resizeWindowAction(sel);
				}else{
					var sel = nwResizeWindow.e4;
					if( $(sel).is(":visible") ){
						nwResizeWindow.resizeWindowAction(sel);
					}
				}
			}
			
			var sel = nwResizeWindow.e5;
			if( $(sel).is(":visible") ){
				nwResizeWindow.resizeWindowAction(sel);
			}
			
			$(document).scrollTop(0);
        },
		resizeWindowAction: function ( sel ) {
			
			var top = $( sel ).offset().top;
			var docHeight = window.innerHeight; //$(window).height();
			
			var h = docHeight - top;
			if( h > 55 ){
				if( $( sel ).hasClass("dataTables_scrollBody") ){
					h -= 55;
				}
				$( sel )
				.css("height", h );
			}else{
				//alert("Screen Height Too Small \n\nPlease Maximize you window");
			}
			
        },
		resizeWindowImport: function () {
			
			var sel = nwResizeWindow.e3;
			nwResizeWindow.resizeWindowAction( sel );
			
        },
		resizeWindowChart: function () {
			
			var sel = nwResizeWindow.e4;
			nwResizeWindow.resizeWindowAction( sel );
			
        },
		adjustColumnSizing: function () {
			setTimeout( function(){
				
			$( "table.activated-table")
			.dataTable()
			.fnAdjustColumnSizing();
			}, 1000 );
        },
		adjustBarChart: function () {
			$( "#chart-container")
			.parent()
			.remove();
			
			$( "#chart-container-1")
			.parent()
			.removeClass("col-md-7")
			.addClass("col-md-10");
			
        },
    };
	
}();
