var nwColor_of_gold = function () {
	return {
		recordItem: {
			id:"",
			name:"",
			type:"",
		},
		init: function () {			
			$('#expense-view')
			.find("tr.item-record")
			.not(".activated")
			.on('click', function(e){
				e.preventDefault();
				
				$('#expense-view')
				.find("tr.item-record")
				.removeClass("active");
				
				$(this).addClass("active");
				
				var $item = $(this);
				nwColor_of_gold.recordItem.id = $item.attr("id");
				nwColor_of_gold.recordItem.name = $item.attr("data-name");
				//nwColor_of_gold.recordItem.type = $item.attr("data-type");
				
				$("form#color_of_gold")
				.find(".custom-single-selected-record-button")
				.attr("override-selected-record", nwColor_of_gold.recordItem.id );
				
				nwColor_of_gold.edit();
				
			})
			.addClass("activated");
			
		},
		showRecentExpensesTab: function () {	
			//$('a[href="#recent-expenses"]').click();
		},
		reClick: function () {	
			$('#expense-view').find("tr.item-record.active").click();
		},
		edit: function () {	
			
			$.each( nwColor_of_gold.recordItem, function( key, val ){
				if( $("form#color_of_gold").find('.form-control[name="'+key+'"]') ){
					$("form#color_of_gold").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
		},
		emptyNewItem: function(){
			$('#expense-view')
			.find("tr.item-record")
			.removeClass("active");
			
			$("form#color_of_gold")
			.find(".custom-single-selected-record-button")
			.attr("override-selected-record", "" );
			
			$("form#color_of_gold").find(".form-control").val('');
			nwColor_of_gold.recordItem = {
				id:"",
				name:"",
				type:"",
			};
		},
		addComma: function( nStr ){
			nStr += '';
			x = nStr.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? '.' + x[1] : '';
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
			}
			return x1 + x2;
		}
	};
	
}();
nwColor_of_gold.init();