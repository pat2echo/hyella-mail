function __trigger( mobile ){
	if( ! mobile ){
		$("#activate_report_year").click();
	}
};

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
