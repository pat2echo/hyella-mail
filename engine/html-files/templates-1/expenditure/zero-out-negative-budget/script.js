$('#zero-out-negative-budget')
.modal('show');

$('#zero-out-negative-budget')
.on('show.bs.modal', function(){
	
})
.on('hide.bs.modal', function(){
	$("#ajax-modal-container")
	.remove();
	
	$("body")
	.removeClass("modal-open");
	
	if( typeof( nwSales ) == "object" )nwSales.reClick();
	if( typeof( nwProduction ) == "object" )nwProduction.reClick();
});