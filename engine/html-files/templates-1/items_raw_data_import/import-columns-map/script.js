var import_items = {
	form_id:"import_items-form",
	row_select_field:"import_items008",
	col_select_field:"column-select-field",
	
};

	//hide all elements
	$("#"+import_items.form_id)
	.find('.control-group')
	.addClass('hide-group');
	
	//create next button
	var $clone = $("#"+import_items.form_id).find('.bottom-row').clone();
	$clone
	.removeClass('bottom-row')
	.removeClass('hide-group')
	.find('#form-gen-submit')
	.attr('id', 'next-button')
	.attr('type', 'button')
	.attr('value', 'Next >>');
	
	$clone
	.insertAfter( $("#"+import_items.form_id).find('.bottom-row') );
	
	var $clone2 = $('#next-button').clone();
	
	$clone2
	.attr('id', 'prev-button')
	.attr('type', 'button')
	.attr('value', '<< Prev')
	.removeClass('btn-primary')
	.removeClass('blue')
	.insertAfter( $('#next-button') );
	
	//bind next-button click event
	$('#next-button')
	.bind('click', function(e){
		e.preventDefault();
		var $next = $( '.current-group' ).next();
		
		if( $next.hasClass('form-group') ){
			
			var $v = $( '.current-group' ).find( 'input.form-gen-element' );
			if( $v.hasClass( import_items.col_select_field ) ){
				disable_selected_col( $('input.import-col-'+$v.val() ) );
			}else{
				//disable all rows
				$('input[name="starting_row"]').attr('disabled', true );
			}
			
			$( '.current-group' )
			.addClass('hide-group')
			.removeClass( 'current-group' );
			
			$next
			.addClass('current-group')
			.removeClass('hide-group');
			
			$( '.current-group' ).find( 'input.form-gen-element' ).focus();
			
		}else{
			
			var p = confirm("MAPPING COMPLETE!\n----------------------------\n\nPress OK to Continue Import");
			if( p == true ){
				$("#"+import_items.form_id).submit();
			}
		}
		
	});
	
	//bind previous-button click event
	$('#prev-button')
	.bind('click', function(e){
		e.preventDefault();
		var $prev = $( '.current-group' ).prev();
		
		if( $prev.hasClass('form-group') ){
			$( '.current-group' )
			.addClass('hide-group')
			.removeClass( 'current-group' );
			
			$prev
			.addClass('current-group')
			.removeClass('hide-group');
			
			var $v = $( '.current-group' ).find( '.form-gen-element' );
			if( $v.hasClass( import_items.col_select_field ) ){
				enable_selected_col( $('input.import-col-'+$v.val() ) );
			}else{
				//enable all rows
				$('input[name="starting_row"]').attr('disabled', false );
			}
			$v.focus();
		}
	});
	
	//show first row
	$("#"+import_items.form_id)
	.find('.form-group:first')
	.removeClass('hide-group')
	.addClass('current-group');
	
	//set max & min limit
	$( "#" + import_items.row_select_field )
	.attr('min', 1 )
	.attr('max', $('input[name="starting_row"]').filter(":last").val() )
	.bind( 'blur keyup change', function(){
		var max = parseInt( $(this).attr('max') );
		
		if( $(this).val() > max ){
			alert("You cannot exceed the maximum value of "+max);
			$(this).val( max );
		}
		
		$('input.import-row-'+$(this).val() ).attr( 'checked', 'checked' );
		hightlight_selected_row( $('input.import-row-'+$(this).val() ) );
		
	});
	
	//set max & min limit
	$( "input." + import_items.col_select_field )
	.attr('min', 1 )
	.attr('max', $('input.import-col').filter(":last").val() )
	.bind( 'blur keyup change', function(){
		var max = parseInt( $(this).attr('max') );
		
		if( $(this).val() > max ){
			alert("You cannot exceed the maximum value of "+max);
			$(this).val( max );
		}
		
		$('input.import-col-'+$(this).val() ).attr( 'checked', 'checked' );
		hightlight_selected_col( $('input.import-col-'+$(this).val() ) );
		
	});
	
	$('input.import-row-'+$( "#" + import_items.row_select_field ).val() ).attr( 'checked', 'checked' );
	
	$('input[name="starting_row"]')
	.bind('change', function(){
		if( $( "#" + import_items.row_select_field ).is(":visible") ){		
			hightlight_selected_row( $(this) );
			
			//update row_select_field if different
			if( $(this).val() != $( "#" + import_items.row_select_field ).val() ){
				$( "#" + import_items.row_select_field ).val( $(this).val() );
			}
		}
	});

	$('input[name="column_field"]')
	.bind('change', function(){
		if( $( "input." + import_items.col_select_field ).is(":visible") ){		
			hightlight_selected_col( $(this) );
			
			//update row_select_field if different
			if( $(this).val() != $( "input." + import_items.col_select_field ).filter(":visible").val() ){
				$( "input." + import_items.col_select_field ).filter(":visible").val( $(this).val() );
			}
		}
	});
	
	alert("MAP EXCEL COLUMNS FOR IMPORT\n----------------------------\n\nUse the Column / Fields Map Form to Select Appropriate Excel Columns and Click Next");

function hightlight_selected_row( $e ){
	$(".highlighted-starting-row")
	.removeClass("highlighted-starting-row");
		
	//hightlight table row
	$e
	.parents( "tr" )
	.addClass( "highlighted-starting-row" );
};

function hightlight_selected_col( $e ){
	$(".highlighted-col")
	.not('.disabled-col')
	.removeClass("highlighted-col");
		
	//hightlight table row
	$( "td.col-"+$e.val() )
	.add( "th.col-"+$e.val() )
	.addClass( "highlighted-col" );
	
	$( "th.col-"+$e.val() )
	.find('.col-label')
	.text( $( "input." + import_items.col_select_field ).filter(":visible").attr('col-label') );
};

function disable_selected_col( $e ){
	$( "td.col-"+$e.val() )
	.add( "th.col-"+$e.val() )
	.addClass('disabled-col');
	
	$( "th.col-"+$e.val() )
	.find('input[type="radio"]')
	.attr('disabled', true);
};

function enable_selected_col( $e ){
	$( "td.col-"+$e.val() )
	.add( "th.col-"+$e.val() )
	.removeClass('disabled-col');
	
	$( "th.col-"+$e.val() )
	.find('input[type="radio"]')
	.attr('disabled', false);
};