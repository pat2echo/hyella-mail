var nwRecordPayment = function () {
	return {
		cartItems: {
			id:"",
			sales_status:"",
			staff_responsible:"",
			date:"",
			description:"",
			mode_of_payment:"",
			amount_paid:0,
			amount_owed:0,
			store:"",
			vendor:"",
		},
		selectedItem:"",
		selectedItemID:"",
		selectedItemAmountPaid:0,
		init: function(){			
			
			$("#sales-record-search-result")
			.find("tr.item-sales" )
			.not(".activated")
			.on('click', function(e){
				e.preventDefault();
				
				$(this).siblings().removeClass("active");
				$(this).addClass("active");
				
				$("#update-button")
				.attr( "override-selected-record", $(this).attr("id") )
				.click();
			})
			.addClass("activated");
		},
		clear: function(){
			nwRecordPayment.emptyCart();
		},
		search: function(){
			$("form#search-expenditure-invoice").submit();
		},
		recordPayment: function () {	
			$('a[href="#capture-payment"]').click();
			
			$.each( nwRecordPayment.cartItems, function( key, val ){
				if( $("form#cart").find('.form-control[name="'+key+'"]') ){
					$("form#cart").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
			$('input[name="amount_paid"]')
			.attr("max", nwRecordPayment.cartItems.amount_owed );
		},
		emptyCart: function(){
			nwRecordPayment.cartItems = {
				id:"",
				sales_status:"",
				staff_responsible:"",
				date:"",
				description:"",
				mode_of_payment:"",
				amount_paid:0,
				amount_owed:0,
				store:"",
				vendor:"",
			};
		},
		activateRecentSupplyItems: function () {
			
		},
		cancelDeleteStockItem: function () {
			
		},
		deleteStockItem: function () {
			
		},
		deletedStockItemSuccess: function () {
			
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
nwRecordPayment.init();