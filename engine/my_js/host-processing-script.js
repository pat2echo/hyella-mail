(function($) {
    $.fn.pHost = {
        loadGuests: function( options ) {
			
		    //Establish our default settings
            var settings = $.extend({
                form : $('form[name="sign-in-form"]'),
                back_button : $('#back-to-signin'),
                recent_activity: $('#chats').find('.chats'),
            }, options );
            
			//event binding
            settings.form.on('submit', function(e){
                $('.pass-code-auth').hide();
                $('.successful-pass-code-auth').hide();
                $('.processing-pass-code-auth').slideDown();
            });
            
            settings.back_button.on('click', function(e){
                e.preventDefault();
                $('.successful-pass-code-auth').hide();
                $('.processing-pass-code-auth').hide();
                $('.pass-code-auth').slideDown();
            });
            
			//populate guest list
			$.fn.cProcessForm.ajax_data = {
                ajax_data: { filter: "my-visitors" },
                form_method: 'post',
                ajax_data_type: 'json',
                ajax_action: 'request_function_output',
                ajax_container: '',
                ajax_get_url: "?action=visit_schedule&todo=get_all_visitors_and_forms",
            };
            $.fn.cProcessForm.ajax_send();
			
            //$.fn.cProcessForm.handleSubmission( settings.form );
            //$.fn.cProcessForm.populateRecentActivities( settings.recent_activity );
        },
        loadCalendar: function( options ) {
			//populate guest list
			$.fn.cProcessForm.ajax_data = {
                ajax_data: { filter: "my-calendar" },
                form_method: 'post',
                ajax_data_type: 'json',
                ajax_action: 'request_function_output',
                ajax_container: '',
                ajax_get_url: "?action=events&todo=display_calendar_view_agenda&mobile=1",
            };
            $.fn.cProcessForm.ajax_send();
			
            //$.fn.cProcessForm.handleSubmission( settings.form );
            //$.fn.cProcessForm.populateRecentActivities( settings.recent_activity );
        },
        loadEventNotes: function( options ) {
			//populate guest list
			var param = document.location.search;
			var params = param.replace("?", "").split("=");
			
			var event = "";
			if( params[0] ){
				event = params[0];
			}
			
			$.fn.cProcessForm.ajax_data = {
                ajax_data: { id: event },
                form_method: 'post',
                ajax_data_type: 'json',
                ajax_action: 'request_function_output',
                ajax_container: '',
                ajax_get_url: "?action=event_notes&todo=display_event_note_form&mobile=1",
            };
            $.fn.cProcessForm.ajax_send();
			
            //$.fn.cProcessForm.handleSubmission( settings.form );
            //$.fn.cProcessForm.populateRecentActivities( settings.recent_activity );
        },
        activateApproveDenyForm: function() {
			var d = $.fn.cProcessForm.returned_ajax_data;
			
			$("#modal-replacement-handle")
			.html( d.visit_card + d.visit_form );
        },
        moveTileToFirst: function() {
			var d = $.fn.cProcessForm.returned_ajax_data;
			
			if( d.tile_to_move && d.tile_to_move_container ){
				
				$( d.tile_to_move )
				.prependTo( d.tile_to_move_container );
			}
        },
        activateDateRangePicker: function() {
			$('#reportrange').daterangepicker({
                opens: 'left', //(App.isRTL() ? 'left' : 'right'),
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                minDate: '01/01/2014',
                maxDate: '12/31/2016',
                dateLimit: {
                    days: 60
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                buttonClasses: ['btn'],
                applyClass: 'green',
                cancelClass: 'default',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Apply',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },
            function (start, end) {
                console.log("Callback has been called!");
                $('#reportrange span').html(start.format('MM D, YYYY') + ' - ' + end.format('MM D, YYYY'));
            }
			);
			//Set the initial state of the picker label
			$('#reportrange span').html(moment().subtract('days', 29).format('MM D, YYYY') + ' - ' + moment().format('MM D, YYYY'));
        },
    }
}(jQuery));