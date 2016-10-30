var nwStores = function () {
	return {
		recordItem: {
			id:"",
			name:"",
			address:"",
			phone:"",
			email:"",
			comment:"",
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
				nwStores.recordItem.id = $item.attr("id");
				nwStores.recordItem.name = $item.attr("data-name");
				nwStores.recordItem.address = $item.attr("data-address");
				nwStores.recordItem.phone = $item.attr("data-phone");
				nwStores.recordItem.email = $item.attr("data-email");
				nwStores.recordItem.comment = $item.attr("data-comment");
				
				$("form#stores")
				.find(".custom-single-selected-record-button")
				.attr("override-selected-record", nwStores.recordItem.id );
				
				nwStores.edit();
				
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
			
			$.each( nwStores.recordItem, function( key, val ){
				if( $("form#stores").find('.form-control[name="'+key+'"]') ){
					$("form#stores").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
		},
		emptyNewItem: function(){
			$('#expense-view')
			.find("tr.item-record")
			.removeClass("active");
			
			$("form#stores")
			.find(".custom-single-selected-record-button")
			.attr("override-selected-record", "" );
			
			$("form#stores").find(".form-control").val('');
			nwStores.recordItem = {
				id:"",
				name:"",
				address:"",
				phone:"",
				email:"",
				comment:"",
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
nwStores.init();