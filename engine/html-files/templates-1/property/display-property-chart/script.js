var nwProperty = function () {
	return {
		init: function(){
			$('select.select2').select2();
			
			$('select[name="location"]')
			.on("change", function(){
				$("#property-chart-button")
				.attr('override-selected-record', $(this).val() )
				.click();
			});
		},
		refreshChart: function(){
			$("#property-chart-button").click();
		},
	};
}();

nwProperty.init();