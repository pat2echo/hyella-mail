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
	
	$.fn.cProcessForm.ajax_data = {
		ajax_data: { filter: "my-calendar" },
		form_method: 'post',
		ajax_data_type: 'json',
		ajax_action: 'request_function_output',
		ajax_container: '',
		ajax_get_url: "?action=discount&todo=refresh_discount_list",
	};
	$.fn.cProcessForm.ajax_send();
});