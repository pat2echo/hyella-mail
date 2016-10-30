 var nwAjaxRequests = function () {
	//internal function
    var internalFunction = function (e) {
        
    };

    return {
        //main function to initiate the module
        initAjaxRequest: function ( ajaxData ) {
            $.ajax({            
				dataType: ajaxData.ajax_data_type,
				type: ajaxData.form_method,
				data: ajaxData.ajax_data,
				url: ajaxData.pagepointer + 'php/ajax_request_processing_script.php' + ajaxData.ajax_get_url,
				timeout:30000,
				beforeSend:function(xhr){
					//alert(2);
					/*
					if( ajax_data_type == 'json' ){
						xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
					}*/
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
					ajax_request_function_output(data);
				}
			});
        },
        //main function to initiate the module
        initChart: function ( chart ) {
            // activate Nestable for list 1
            //updateOutput
			console.log( chart );
			
			$( chart.highchart_container_selector ).highcharts( chart.highchart_data );
			
        },
        displayNotifications: function ( data ) {
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
					refresh_form_token( data );
					
					var html = '<div class="alert ' + theme + ' alert-block1 alert-dismissable">';
					  html += '<button type="button" class="close" id="alert-close-button" data-dismiss="alert">&times;</button>';
					  html += '<h4>' + data.err + '</h4>';
					  html += data.msg;
					html += '</div>';
					
					var $notification_container = $('#notification-container');
					
					$notification_container
					.html( html );
					
					$('#alert-close-button')
					.bind('click', function(){
						$('#notification-container')
						.empty();
					});
					
					/*
					$('#form-gen-submit').popover({
						"html": true,
						"content": data.msg,
						"title": data.err,
					})
					.popover('show');
					*/
				break;
				}
			}
        }
    };

}();
//nwHighCharts.init();