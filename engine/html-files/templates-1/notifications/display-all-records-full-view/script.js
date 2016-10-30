var nwNotifications = function () {
	
    return {
        //main function to initiate the module
        init: function () {
			
			$("#notifications-datatable_wrapper")
			.on("click", "tr", function(){
				var record_id = $(this).find('.datatables-record-id').attr('id');
				if( record_id && $("#"+record_id).is(":visible") ){
					var $row = $("#"+record_id).parents("tr");
					
					var $content = $('#main-details-table-'+record_id).find("tbody");
					
					var classname = "details-section-container-row-notifications"; //001
					
					var html = "";
					html += "<h4 style='line-height:1.5;'><small><i class='icon-calendar'></i> "+$content.find( ".details-section-container-row-creation_date" ).find(".details-section-container-value").html()+"<br /><br /></small>"+$content.find( "." + classname + "001" ).find(".details-section-container-value").html()+"</h4><hr />";
					html += "<p>"+$content.find( "." + classname + "005" ).find(".details-section-container-value").html()+"</p>";
					
					$('#message-section')
					.html( html );
					
					$("#open-notifications-button")
					.attr("selected-record", record_id )
					.click()
					.attr("selected-record", "" )
					.attr("override-selected-record", "" );
				}
			});
			
        },
		updateCurrentFolder: function () {
			
        },

    };

}();