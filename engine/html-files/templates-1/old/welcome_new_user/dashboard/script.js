/*
var tabulate_form = {
	form_id:"exploration_activities-form",
	insert_after:$( "#exploration_activities-form" ).find(".bottom-row"),
	active_tab: 1,
	tabs:{
		1:{  
			id: "-tab1", 
			title: "Status",
			fields_by_class: $(".exploration_activities030-row, .exploration_activities031-row, .exploration_activities033-row"),
		},
		2:{ 
			id: "-tab2",
			title: "Depths",
			fields_by_class: $(".exploration_activities007-row, .exploration_activities008-row, .exploration_activities009-row, .exploration_activities010-row,  .exploration_activities011-row, .exploration_activities012-row, .exploration_activities027-row, .exploration_activities028-row, .exploration_activities029-row, .exploration_activities023-row "),
		},
		3:{ 
			id: "-tab3",
			title: "Volume",
			fields_by_class: $(".exploration_activities017-row, .exploration_activities018-row, .exploration_activities019-row, .exploration_activities020-row, .exploration_activities021-row, .exploration_activities022-row, .exploration_activities024-row, .exploration_activities025-row"),
		},
		4:{ 
			id: "-tab4",
			title: "Costs",
			fields_by_class: $(".exploration_activities026-row, .exploration_activities016-row, .exploration_activities015-row, .exploration_activities014-row, .exploration_activities013-row, .exploration_activities026-row, .exploration_activities027-row"),
		},
	}
};
*/
//nwTabulateForm.init( tabulate_form );

/*
$.ajax({
	type: 'POST',
	dataType:'json',
	url: $('#pagepointer').text() + 'php/ajax_request_processing_script.php?action=bidders&todo=get_all_bidders',
	success: function( data ){
		amplify.store( 'bidders', data );
		activateSelect2();
	}
});
*/

function activateSelect2(){
	$('input[name="exploration_activities018"]')
	.add('input[name="exploration_activities027"]')
	.select2({
		tags: function(){
			return amplify.store( 'bidders' );
		}, //function to populate contractors
		separator: ":::",
		selectOnBlur: false,
	})
	.on("change", function(e){
		if( e.added ){
			//store in database
			if( e.added.id == e.added.text ){
				$.ajax({
					type: 'POST',
					dataType:'json',
					data: { bidder_company_name: e.added.id },
					url: $('#pagepointer').text() + 'php/ajax_request_processing_script.php?action=bidders&todo=add_bidders',
					success: function( data ){
						//add to autocomplete list
						var d = { id: data.id, text:data.company_name };
						var a = amplify.store( 'bidders' );
						a.push( d );
						amplify.store( 'bidders' , a );
					}
				});
			}
		}
	});

	setTimeout( function(){
		$('.auto-remove').remove();
	}, 2000 );
};	

var nwExplorationActivitiesSave = function () {
	
    return {
        //main function to initiate the module
        init: function () {
			
			//$("#report-card-home-control-handle").click();
			
        },
		updateStatusInit: function () {
			
			//$("#form-home-control-handle").click();
			
        }

    };

}();

//nwTenderingMexcomSave.init();
//nwTenderingMexcomSave.updateStatusInit();
//$("#custom-form-tab")
//.insertAfter( $( tabulate_form.form_id ).find(".bottom-row") );