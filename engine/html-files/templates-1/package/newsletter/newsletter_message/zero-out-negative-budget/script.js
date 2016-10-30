$('#zero-out-negative-budget')
.modal('show');

$('#zero-out-negative-budget')
.on('show.bs.modal', function(){
	if( $('input[type="date"]').is(":focus") ){
		$('#zero-out-negative-budget').addClass("date-in-use");
	}
})
.on('hide.bs.modal', function(){
	
	if( $('#zero-out-negative-budget').hasClass("date-in-use") ){
		$('#zero-out-negative-budget').removeClass("date-in-use");
	}else{
		$("#ajax-modal-container")
		.remove();
		
		$("body")
		.removeClass("modal-open");
		
		if( typeof( nwCheckOut ) == "object" )nwCheckOut.refreshBill();
		
		if( refresh_code ){
			switch( refresh_code ){
			case "nwReportView.regenerateReport":
				if( typeof( nwReportView ) == "object" )nwReportView.regenerateReport();
			break;
			}
		}
		
	}
	
});