var nwCustomers = function () {
	return {
		recordItem: {
			id:"",
			name:"",
			address:"",
			phone:"",
			email:"",
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
				nwCustomers.recordItem.id = $item.attr("id");
				nwCustomers.recordItem.name = $item.attr("data-name");
				nwCustomers.recordItem.address = $item.attr("data-address");
				nwCustomers.recordItem.phone = $item.attr("data-phone");
				nwCustomers.recordItem.email = $item.attr("data-email");
				
				$("form#customers")
				.find(".custom-single-selected-record-button")
				.attr("override-selected-record", nwCustomers.recordItem.id );
				
				nwCustomers.edit();
				
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
			
			$.each( nwCustomers.recordItem, function( key, val ){
				if( $("form#customers").find('.form-control[name="'+key+'"]') ){
					$("form#customers").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
		},
		emptyNewItem: function(){
			$('#expense-view')
			.find("tr.item-record")
			.removeClass("active");
			
			$("form#customers")
			.find(".custom-single-selected-record-button")
			.attr("override-selected-record", "" );
			
			$("form#customers").find(".form-control").val('');
			nwCustomers.recordItem = {
				id:"",
				name:"",
				address:"",
				phone:"",
				email:"",
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
nwCustomers.init();