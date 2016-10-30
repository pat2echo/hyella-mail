(function($) {
    $.fn.cProcessForm = {
        requestURL: $("#pagepointer").text(),
		activateAjaxRequestButton: function(){
			$("body")
			.on( "click", ".ajax-request", function(e){
				e.preventDefault();
				
				if( $(this).hasClass("ajax-request-modals") )
					$(this).attr("href", $(this).attr("data-href") );
				
				var data_id = ( $(this).attr("data-id") )?$(this).attr("data-id"):"";
				var data_filter = ( $(this).attr("data-filter") )?$(this).attr("data-filter"):"";
				var data_internalcard = ( $(this).attr("data-internalcard") )?$(this).attr("data-internalcard"):"";
				
				$.fn.cProcessForm.ajax_data = {
					ajax_data: {filter: data_filter, id:data_id , internalcard:data_internalcard },
					form_method: 'post',
					ajax_data_type: 'json',
					ajax_action: 'request_function_output',
					ajax_container: '',
					ajax_get_url: "?action=" + $(this).attr("action") + "&todo=" + $(this).attr("todo"),
				};
				$.fn.cProcessForm.ajax_send();
				
			});
			
			$("body")
			.on( "click", ".custom-action-button", function(e){
				e.preventDefault();
				var $me = $(this);
				
				var function_id = $me.attr('function-id');
				var function_name = $me.attr('function-name');
				var function_class = $me.attr('function-class');
				
				var budget_id = '';
				var month_id = '';
				var operator_id = '';
				var department_id = '';
				
				if( $me.attr('budget-id') && $me.attr('month-id') ){
					budget_id = $me.attr('budget-id');
					month_id = $me.attr('month-id');
				}
				
				if( $me.attr('department-id') && $me.attr('operator-id') ){
					operator_id = $me.attr('operator-id');
					department_id = $me.attr('department-id');
				}
				var module_id = "";
				$.fn.cProcessForm.ajax_data = {
					ajax_data: {action:function_class, todo:function_name, module:module_id, id:function_id, budget:budget_id, month:month_id, department:department_id, operator:operator_id },
					form_method: 'get',
					ajax_data_type: 'json',
					ajax_action: 'request_function_output',
					ajax_container: '',
					ajax_get_url: "?",
				};
				$.fn.cProcessForm.ajax_send();
				
			});
			
			$("body")
			.on( "click", ".custom-single-selected-record-button", function(e){
				e.preventDefault();
				var single_selected_record = "";
				
				if( $(this).attr("override-selected-record") ){
					single_selected_record = $(this).attr("override-selected-record");
				}
				
				if( ( ! single_selected_record  ) && $(this).attr("selected-record") ){
					single_selected_record = $(this).attr("selected-record");
				}
					
				if( single_selected_record ){
					
					var module_id = "";
					$.fn.cProcessForm.ajax_data = {
						ajax_data: {mod:$(this).attr('mod'), id:single_selected_record},
						form_method: 'post',
						ajax_data_type: 'json',
						ajax_action: 'request_function_output',
						ajax_container: '',
						ajax_get_url: $(this).attr('action'),
					};
					if( single_selected_record = "json" ){
						$.fn.cProcessForm.ajax_data.ajax_data.json = $("body").data("json");
					}
					$.fn.cProcessForm.ajax_send();

				}
				
			});
		},
        handleSubmission: function( $form ){
            $form.on('submit', function(e){
                e.preventDefault();
                var d = $.fn.cProcessForm.transformData( $(this) );
                
				console.log(d);
                if( d.error ){
                    var settings = {
                        message_title:d.title,
                        message_message: d.message,
                        auto_close: 'no'
                    };
                    display_popup_notice( settings );
                }else{
                    var local_store = 0;
					internetConnection = true;
					
                    d[ 'object' ] = $(this).attr('name');
                    
                    if( $(this).attr('local-storage') ){
                        local_store = 1;
                        
                        //store data
                        //var stored = store_record( data );
                        //successful_submit_action( stored );
                        
                        alert('local storage');
                    }
             
                    if( ! local_store ){
						$(this).data('do-not-submit', 'submit' )
						$.fn.cProcessForm.post_form_data( $(this) );
						
						tempData = d;
                    }
                    
                    $form
                    .find('input')
                    .not('.do-not-clear')
                    .val('');
                }
                return d;
            });
        },
        transformData: function( $form ){
            
            var data = $form.serializeArray();
            
            var error = {};
            var txData = { error:false };
            var unfocused = true;
            
            $.each( data , function( key , value ){
                var $input = $form.find('#'+value.name+'-field');
                if( $input ){
                    if( $input.attr('data-validate') ){
                        var validated = $.fn.cProcessForm.validate.call( $input , unfocused );
                        
                        if( ! ( error.error ) && validated.error ){
                            //throw error & display message
                            error = validated;
                            unfocused = false;
                        }else{
                            //start storing object
                            txData[ value.name ] = value.value;
                        }
                        
                    }else{
                        txData[ value.name ] = value.value;
                    }
                }
            });
            
            if( error.error ){
                return error;
            }
            
            return txData;
        },
        validate: function( $element , unfocused ){
            var tit = "Error Title";
            var msg = "Error Msg";
            var err = false;
            
            if( $element.attr('data-validate') && $element.attr('required') ){
                switch( $element.attr('data-validate') ){
                case 'date':
                case 'text':
                case 'number':
                case 'tel':
                    if( $element.val().length < 1 ){
                        err = true;
                    }
                break;
                case 'email':
                    var email = $element.val();
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    if( email.length<1 || !emailReg.test( email ) ) {
                        err = true;
                    }
                break;
                case 'confirm-email':
                    var email = $element.val();
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    if( email.length<1 || !emailReg.test( email ) ) {
                        err = true;
                    }
                    
                    var $emails = $element.parents('form').find('input[type="email"]');
                    if( $emails ){
                        if( $emails.length > 1 ){
                            $emails.each(function(){
                                if( email != $(this).val() )err = true;
                            });
                        }else{
                            if( email != $emails.val() )err = true;
                        }
                    }
                break;
                case 'password':
                    if( $element.val().length < 6 ) {
                        err = true;
                    }
                break;
                case 'confirm-password':
                    var pw = $element.val();
                    var $pws = $element.parents('form').find('input[type="password"]');
                    if( $pws ){
                        if( $pws.length > 1 ) {
                            $pws.each(function(){
                                if( pw != $(this).val() )err = true;
                            });
                        }else{
                            if( pw != $pws.val() )err = true;
                        }
                    }
                break;
                }
                
                if( err ){
                    if( $element.attr('data-error-title') )tit = $element.attr('data-error-title');
                    if( $element.attr('data-error-msg') )msg = $element.attr('data-error-msg');
                    if( unfocused )$element.focus().select();
                }
            }
            
            var validated = {
                error: err,
                title: tit,
                message: msg,
            };
            return validated;
        },
        ajax_data: {},
        returned_ajax_data: {},
        post_form_data: function( $form ){
			
            if( $form.data('do-not-submit') != 'submit' ){
				return false;
			}
            $.fn.cProcessForm.ajax_data = {
                ajax_data: $form.serialize(),
                form_method: 'post',
                ajax_data_type: 'json',
                ajax_action: 'request_function_output',
                ajax_container: '',
                ajax_get_url: $form.attr('action'),
            };
            $.fn.cProcessForm.ajax_send();
        },
        function_click_process: 1,
        ajax_send: function( settings ){
            //Send Data to Server
            
            if( $.fn.cProcessForm.function_click_process ){
                $.ajax({
                    dataType: $.fn.cProcessForm.ajax_data.ajax_data_type,
                    type:$.fn.cProcessForm.ajax_data.form_method,
                    data:$.fn.cProcessForm.ajax_data.ajax_data,
                    crossDomain:true,
                    url: $.fn.cProcessForm.requestURL + 'engine/php/ajax_request_processing_script.php' + $.fn.cProcessForm.ajax_data.ajax_get_url,
                    timeout:80000,
                    beforeSend:function(){
                        $.fn.cProcessForm.function_click_process = 0;
                        $('div#generate-report-progress-bar')
                        .html('<div class="virtual-progress-bar progress progress-striped"><div class="progress-bar progress-bar-info"></div></div>');
                        
                        $.fn.cProcessForm.progress_bar_change.call();
                    },
                    error: function(event, request, settings, ex) {
                        $.fn.cProcessForm.ajaxError.call( event, request, settings, ex );
                    },
                    success: function(data){
                        $.fn.cProcessForm.requestRetryCount = 0;
                        $.fn.cProcessForm.function_click_process = 1;
                        if( data.status ){
                            switch(data.status){
                            case 'authenticated-visitor':
                                data.url = $.fn.cProcessForm.requestURL;
                                authenticated_visitor( data );
                                return;
                            break;
                            case 'got-recent-activities':
                                data.url = $.fn.cProcessForm.requestURL;
                                got_recent_activities( data );
                                return;
                            break;
							case "new-status":
								if( data ){
									
									if( data.html ){
										$('#main-view')
										.html( data.html );
									}
									
									if( data.html_replacement_selector && data.html_replacement ){
										$(data.html_replacement_selector)
										.html( data.html_replacement );
									}
									
									if( data.html_replacement_selector_one && data.html_replacement_one ){
										$(data.html_replacement_selector_one)
										.html( data.html_replacement_one );
									}
									
									if( data.html_replacement_selector_two && data.html_replacement_two ){
										$(data.html_replacement_selector_two)
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
										$.fn.cProcessForm.returned_ajax_data = data;
										tmp_data = data;
										$.each( data.javascript_functions , function( key, value ){
											eval( value + "()" );
										} );
									}
									
									if(data.saved_record_id){
										single_selected_record = data.saved_record_id;
									}

								}
							break;
                            }
                        }
                        
                        if( data.typ ){
                            switch(data.typ){
                            case 'serror':
                            case 'uerror':
                            case 'saved':
                            case 'generated-report':
                                if( data.err && data.msg ){
                                    var settings = {
                                        'message_title':data.err,
                                        'message_message':data.msg,
                                    };
                                    display_popup_notice( settings );
                                }
                            break;
                            }
                        }
						
						if( data.tok && $('form') ){
							$('form')
							.find('input[name="processing"]')
							.val( data.tok );
						}
						
						if( data.re_process && ! $.fn.cProcessForm.cancelAjaxRecursiveFunction ){
							$.fn.cProcessForm.triggerNewAjaxRequest();
						}
                    }
                });
            }
        },
		cancelAjaxRecursiveFunction:0,
		triggerNewAjaxRequest: function(){
			$.fn.cProcessForm.ajax_data = {
				ajax_data: {mod: $.fn.cProcessForm.returned_ajax_data.mod, id:$.fn.cProcessForm.returned_ajax_data.id },
				form_method: 'post',
				ajax_data_type: 'json',
				ajax_action: 'request_function_output',
				ajax_container: '',
				ajax_get_url: $.fn.cProcessForm.returned_ajax_data.action,
			};
			$.fn.cProcessForm.ajax_send();
		},
        ajaxError: function( event, request, settings, ex ){
            
        },
		activate_highcharts: function(){
			var tmp_data = $.fn.cProcessForm.returned_ajax_data;
			if( tmp_data && Object.getOwnPropertyNames( tmp_data ).length && tmp_data.highchart_data && tmp_data.highchart_container_selector ){
				nwHighCharts.initChart( tmp_data );
			}else{
				alert("Could not Generate Chart, due to invalid data");
			}
		},
		activate_and_export_highcharts: function(){function activate_and_export_highcharts(){
			var tmp_data = $.fn.cProcessForm.returned_ajax_data;
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
		},
        activateAjaxForm: function(){
			
			//Bind Html text-editor
			$.fn.cProcessForm.activateFullTextEditor();
			
			//Activate Client Side Validation / Tooltips
			$.fn.cProcessForm.activateTooltip();
			
			//Bind Form Submit Event
			$('form.activate-ajax')
			.not('.ajax-activated')
			.bind('submit', function( e ){
				e.preventDefault();
				$.fn.cProcessForm.activateFormValidation( $(this) );
				$.fn.cProcessForm.post_form_data( $(this) );
			});
			
			//Activate Ajax file upload
			$.fn.cProcessForm.ajaxFileUploader();
			
			var first = 1;
			var html = "";
			var inline = "";
			$('select.select-checkbox')
			.not('.select-activated')
			.addClass("select-activated")
			.hide()
			.find("option")
			.each( function(){
				if( $(this).attr("value") ){
					inline = ' style="width:47%; font-size:14px; margin-top:10px; " ';
					if( first ){
						first = 0;
						inline = ' style="padding-left:20px; margin-left:10px; width:47%; font-size:14px; margin-top:10px; " ';
					}
					html += '<label class="checkbox-inline" '+inline+'> <div class="checker" ><span><input class="bound-select" parent="'+$(this).parents("select").attr("name")+'" type="checkbox" id="' + $(this).attr('value') + '" value="' + $(this).attr('value') + '"></span></div> ' + $(this).text() + '</label>';
				}
			} )
			.parents(".cell-element")
			.append( '<div class="row" style="padding-left:15px;"><div class="col-md-12"><div class="checkbox-list">'+html+'</div></div></div>' );
			
			$("input.bound-select")
			.not('.bounded-select')
			.on("change", function(){
				var v = $( "select[name='"+$(this).attr("parent")+"']" ).val();
				if( $(this).attr("checked") ){					
					if( v )
						v += "," + $(this).attr("value");
					else
						v = $(this).attr("value");
					
					v = v.split(",");
				}else{
					var i = v.indexOf( $(this).attr("value") );
					if(i != -1) {
						v.splice(i, 1);
					}
				}
				$( "select[name='"+$(this).attr("parent")+"']" ).val( v );
			})
			.addClass("bounded-select");
			
			$('form.activate-ajax').addClass('ajax-activated');
        },
        activateFullTextEditor: function(){
			
			$('textarea')
			.not( '.activated' )
			.bind('keydown', function(e){
			
				switch(e.keyCode){
				case 69:	//E Ctrl [17]
					if(e.ctrlKey){
						e.preventDefault();
						
						editing_textarea = $(this);
						
						//Set Contents
						$('#myModal')
						.modal('show')
						.on('shown', function(){
							tinyMCE.activeEditor.setContent( editing_textarea.val() );
						})
						.on('hidden', function(){
							editing_textarea
							.val( $('#popTextArea').html() );
						});
						
						$(this).attr('tip', '');
						$.fn.cProcessForm.displayTooltip.call($(this), '');
					}
				break;
				}
				
			})
			.bind('focus', function(){
				$(this).attr('tip', 'Press Ctrl+E to display full text editor');
				
				$.fn.cProcessForm.displayTooltip.call($(this), '');
			})
			.bind('blur', function(){
				$(this).attr('tip', '');
				
				$.fn.cProcessForm.displayTooltip.call($(this), '');
			})
			.addClass( 'activated' );
        },
        populateRecentActivities: function( $container ){
            $.fn.cProcessForm.ajax_data = {
                ajax_data: {},
                form_method: 'post',
                ajax_data_type: 'json',
                ajax_action: 'request_function_output',
                ajax_container: '',
                ajax_get_url: '?todo=get_recent_activties&action=entry_exit_log',
            };
            $.fn.cProcessForm.ajax_send.call();
        },
        ajaxFileUploader: function(){
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
						action: $.fn.cProcessForm.requestURL + 'engine/php/upload.php',
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
        },
        activateTooltip: function(){
			
			var $form = $('form.activate-ajax').not('.ajax-activated');
			
            $form
			.find('.form-gen-element')
			.bind('focus',function(){
				$.fn.cProcessForm.displayTooltip($(this) , $(this).attr('name'), false);
			});
			
			$form
			.find('.form-gen-element')
			.bind('blur',function(){
				$.fn.cProcessForm.displayTooltip( $(this) , '', true );
			});
			
			$form
			.find('.form-element-required-field')
			.bind('blur',function(){
				var v = $.fn.cProcessForm.validate( $(this) );
				if( v.error ){
					$(this).addClass('invalid-data');
				}else{
					$(this).removeClass('invalid-data');
				}
			});
			
        },
		activateFormValidation: function( $form ){
			$form
			.find('.form-element-required-field')
			.blur();
			
			if( $form.find('.form-element-required-field').hasClass('invalid-data') ){
				$form
				.find('.invalid-element:first')
				.focus();
				
				$form.data('do-not-submit', true);
				
				//display notification to fill all required fields
				var data = {
					typ:'jsuerror',
					err:'Invalid Form Field',
					msg:'Please do ensure to correctly fill all required fields with appropriate values',
				};
				display_notification( data );
				
				return false;
			}
			
			$form.data('do-not-submit', 'submit' );
			
        },
        displayTooltip: function( me, name, removetip ){
			
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
        },
        requestRetryCount: 0,
        progress_bar_timer_id: 0,
        progress_bar_change: function(){
            var total = 80;
            var step = 1;
            
            if( $.fn.cProcessForm.progress_bar_timer_id )
                clearTimeout( $.fn.cProcessForm.progress_bar_timer_id );
                
            if( $.fn.cProcessForm.function_click_process == 0 ){
                var $progress = $('.virtual-progress-bar:visible').find('.progress-bar');
                
                if($progress.data('step') && $progress.data('step')!='undefined'){
                    step = $progress.data('step');
                }
                
                var percentage_step = ( step / total ) * 100;
                ++step;
                
                if( percentage_step > 100 ){
                    $progress
                    .css('width', '100%');
                    
                    $('.virtual-progress-bar')
                    .remove();
                    
                    $('.progress-bar-container')
                    .html('');
                    
                    //Refresh Page
                    $.fn.cProcessForm.function_click_process = 1;
                    
                    ++$.fn.cProcessForm.requestRetryCount;
                    
                    //Stop All Processing
                    window.stop();
                    
                    //check retry count
                    if( $.fn.cProcessForm.requestRetryCount > 1 ){
                        //display no network access msg
                        //requestRetryCount = 0;
                        
                        var settings = {
                            message_title:'No Network Access',
                            message_message: 'The request was taking too long!',
                            auto_close: 'no'
                        };
                        display_popup_notice( settings );
                        
                        internetConnection = false;
                    }else{
                        //display retrying msg
                        
                        var settings = {
                            message_title:'Refreshing...',
                            message_message: 'Please Wait.',
                            auto_close: 'yes'
                        };
                        //$.fn.cProcessForm.display_popup_notice.call( settings );
                        
                        //request resources again
                        $.fn.cProcessForm.ajax_send.call();
                        
                    }
                    
                }else{
                    $progress
                    .data('step',step)
                    .css('width', percentage_step+'%');
                    
                    $.fn.cProcessForm.progress_bar_timer_id = setTimeout(function(){
                        $.fn.cProcessForm.progress_bar_change.call();
                    },1000);
                }
            }else{
                $('.virtual-progress-bar')
                .find('.progress-bar')
                .css('width', '100%');
                
                setTimeout(function(){
                    $('.virtual-progress-bar')
                    .remove();
                    
                    $('.progress-bar-container')
                    .html('');
                },800);
            }
        },
    }
}(jQuery));

function display_popup_notice( settings ){
    var theme = 'a';
    var html = settings.message_title + "\n" + settings.message_message;
    alert( html );
    
    $('.pass-code-auth').slideDown();
    $('.processing-pass-code-auth').hide();
    $('.successful-pass-code-auth').hide();
};

var gCheck_sum = '';


function set_function_click_event(){
};

function prepare_new_record_form_new(){
	$.fn.cProcessForm.activateAjaxForm();
};

function activate_highcharts(){ $.fn.cProcessForm.activate_highcharts(); };
function activate_and_export_highcharts(){ $.fn.cProcessForm.activate_and_export_highcharts(); };
/*
if (!window.DOMTokenList) {
  Element.prototype.containsClass = function(name) {
    return new RegExp("(?:^|\\s+)" + name + "(?:\\s+|$)").test(this.className);
  };

  Element.prototype.addClass = function(name) {
    if (!this.containsClass(name)) {
      var c = this.className;
      this.className = c ? [c, name].join(' ') : name;
    }
  };

  Element.prototype.removeClass = function(name) {
    if (this.containsClass(name)) {
      var c = this.className;
      this.className = c.replace(
          new RegExp("(?:^|\\s+)" + name + "(?:\\s+|$)", "g"), "");
    }
  };
}

// sse.php sends messages with text/event-stream mimetype.
var source = new EventSource('../engine/php/sse.php');

function closeConnection() {
  source.close();
  updateConnectionStatus('Disconnected', false);
}

function updateConnectionStatus(msg, connected) {
  var el = document.querySelector('#connection');
  if (connected) {
    if (el.classList) {
      el.classList.add('connected');
      el.classList.remove('disconnected');
    } else {
      el.addClass('connected');
      el.removeClass('disconnected');
    }
  } else {
    if (el.classList) {
      el.classList.remove('connected');
      el.classList.add('disconnected');
    } else {
      el.removeClass('connected');
      el.addClass('disconnected');
    }
  }
  el.innerHTML = msg + '<div></div>';
}

source.addEventListener('message', function(event) {
  if( event.data ){
  var data = JSON.parse(event.data);

  var options = {
        iconUrl: data.pic,
        title: data.title,
        body: data.msg+"\n"+data.host,
        timeout: 5000, // close notification in 1 sec
        onclick: function () {
            //console.log('Pewpew');
        }
    };
	if ( $("#push-notification-support") ) {
		var notification = $.notification(options)
		.then(function (notification) {
			//window.focus();
			//console.log('Ok!');
		}, function (error) {
			console.error('Rejected with status ' + error);
		});
		console.log('receive', data.check_sum );
		console.log('receiveG', gCheck_sum );
		
		if( data.check_sum && gCheck_sum != data.check_sum )
			authenticated_visitor( {visitor_data: data, url:$.fn.cProcessForm.requestURL } );
			
		$('.b-level').text('Notifications are ' + $.notification.permissionLevel());
	}
  }
}, false);

source.addEventListener('open', function(event) {
  updateConnectionStatus('Connected', true);
}, false);

source.addEventListener('error', function(event) {
  if (event.eventPhase == 2) { //EventSource.CLOSED
    updateConnectionStatus('Disconnected', false);
  }
}, false);
*/