function __trigger( mobile ){
	if( ! mobile ){
		$("#activate_report_year").click();
	}
};

var nwReportView = function () {
	return {
		regenerateReport: function () {			
			$("#activate_report_year").click();
		}
	};
}();

$("select#report-report-type")
.on("change", function(){
	$("#activate_report_year")
	.attr("department-id", $(this).val() );
	
	switch( $(this).val() ){
	case "room_checkin_log":
		var d = new Date();
		var mm = d.getMonth() + 1;
		var day = d.getDate();
		
		if( mm < 10 )mm = "0" + mm;
		if( day < 10 )day = "0" + day;
		
		$("#start-date")
		.val( d.getFullYear() + "-" + mm + "-" + day )
		.change();
		
		$("select#report-pen")
		.add("select#report-period")
		.attr( "disabled", true );
		
		$("#start-date")
		.attr( "disabled", false );
		
		return false;
	break;
	case "room_reservations_log":
	case "room_checkin_checkout_log":
		var d = new Date();
		var mm = d.getMonth() + 1;
		
		if( mm < 10 )mm = "0" + mm;
		day = "01";
		
		$("#start-date")
		.val( d.getFullYear() + "-" + mm + "-" + day )
		.change();
		
		$("#start-date")
		.add("#end-date")
		.attr( "disabled", false );
		
		$("select#report-period")
		.add("select#report-pen")
		.attr( "disabled", true );
		
		return false;
	break;
	case "guest_activity_report":
		var d = new Date();
		var mm = d.getMonth() + 1;
		
		if( mm < 10 )mm = "0" + mm;
		day = "01";
		
		$("#start-date")
		.val( d.getFullYear() + "-" + mm + "-" + day )
		.change();
		
		$("select#report-period")
		.attr( "disabled", true );
		
		$("select#report-pen")
		.add("#start-date")
		.add("#end-date")
		.attr( "disabled", false );
		
		return false;
	break;
	}
	
	__trigger( mobile );
	
	switch( $(this).val() ){
	case "available_rooms_report":
	case "occuppied_rooms_report":
	case "all_rooms_chart":
	case "in_active_rooms_report":
		$("select#report-pen")
		.add("select#report-period")
		.add("#start-date")
		.add("#end-date")
		.attr( "disabled", true );
	break;
	default:
		$("select#report-pen")
		.add("select#report-period")
		.add("#start-date")
		.add("#end-date")
		.attr( "disabled", false );
	break;
	}
}).change();

$("select#report-year")
.add("select#report-age")
.add("select#report-period")
.on("change", function(){
	$("#activate_report_year")
	.attr("budget-id", $(this).val() );
	
	__trigger( mobile );
}).change();

$("select#report-month")
.add("select#report-type")
.on("change", function(){
	$("#activate_report_year")
	.attr("month-id", $(this).val() );
	
	__trigger( mobile );
}).change();

$("select#report-pen")
.on("change", function(){
	$("#activate_report_year")
	.attr("operator-id", $(this).val() );
	
	__trigger( mobile );
}).change();

$("input.date-range")
.on("change", function(){
	$("#activate_report_year")
	.attr( $(this).attr("id") , $(this).val() );
	
	__trigger( mobile );
})
.change()
.not(".active")
.datepicker({
	rtl: App.isRTL(),
	autoclose: true,
	format: 'yyyy-mm-dd',
})
.addClass("active");
