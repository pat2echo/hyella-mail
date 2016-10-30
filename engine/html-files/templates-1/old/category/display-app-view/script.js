var nwCategory = function () {
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
				nwCategory.recordItem.id = $item.attr("id");
				nwCategory.recordItem.name = $item.attr("data-name");
				nwCategory.recordItem.type = $item.attr("data-type");
				
				$("form#category")
				.find(".custom-single-selected-record-button")
				.attr("override-selected-record", nwCategory.recordItem.id );
				
				nwCategory.edit();
				
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
			
			$.each( nwCategory.recordItem, function( key, val ){
				if( $("form#category").find('.form-control[name="'+key+'"]') ){
					$("form#category").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
		},
		emptyNewItem: function(){
			$('#expense-view')
			.find("tr.item-record")
			.removeClass("active");
			
			$("form#category")
			.find(".custom-single-selected-record-button")
			.attr("override-selected-record", "" );
			
			$("form#category").find(".form-control").val('');
			nwCategory.recordItem = {
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
nwCategory.init();