function __trigger( mobile ){
	if( ! mobile ){
		$("#activate_report_year").click();
	}
};

$("select#report-report-type")
.on("change", function(){
	$("#activate_report_year")
	.attr("department-id", $(this).val() );
	
	__trigger( mobile );
	
	switch( $(this).val() ){
	case "vendors_transactions":
	case "vendors_transactions_summary":
	case "customers_transactions":
	case "customers_transactions_summary":
	case 'periodic_sales_report':
		$("select#report-pen")
		.add("select#report-period")
		.attr( "disabled", false );
	break;
	default:
		$("select#report-pen")
		.add("select#report-period")
		.attr( "disabled", true );
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
