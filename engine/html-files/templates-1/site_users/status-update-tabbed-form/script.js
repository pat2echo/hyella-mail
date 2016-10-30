var tabulate_form = {
	form_id:"site_users-form",
	insert_after:$( "#site_users-form" ).find(".bottom-row"),
	active_tab: active_tab,
	tabs:{
		1:{  
			id: "-tab1", 
			title: "LRCN Review",
			fields_by_class: $(".official-use-row"),
		},
		2:{ 
			id: "-tab2",
			title: "Applicant Info",
			fields_by_class: $(".applicant-use-row, .state-select-to-city-field-row, .country-select-to-state-field-row, .cities-select-field-row"),
		},
		3:{ 
			id: "-tab3",
			title: "Edu. History",
			fields_by_class: $("#educational-history-view-table"),
		},
		4:{ 
			id: "-tab4",
			title: "Work History",
			fields_by_class: $("#work-history-view-table"),
		},
	}
};

nwTabulateForm.init( tabulate_form );

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
			
			$("#extra-tab-control-handle").click();
			
        },
		updateStatusInit: function () {
			
			$("#form-home-control-handle").click();
			
        }

    };

}();

$("#extra-tab-control-handle")
.off("click")
.on("click", function(){
	setTimeout( function(){				
		$("#exploration_activities_status-datatable")
		.dataTable().fnAdjustColumnSizing();
		//.dataTable().fnReloadAjax();
	}, 600 );
	
	setTimeout( function(){				
		$("#exploration_activities_status-datatable")
		.dataTable().fnAdjustColumnSizing();
		
		nwResizeWindow.resizeWindow();
	},1000 );
});
//nwTenderingMexcomSave.init();
//nwTenderingMexcomSave.updateStatusInit();
//$("#custom-form-tab")
//.insertAfter( $( tabulate_form.form_id ).find(".bottom-row") );