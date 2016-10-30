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
		init: function () {			
			
			$("#sales-record-search-result")
			.add("#payment-record-search-result")
			.find("tr.item-sales" )
			.not(".activated")
			.on('click', function(e){
				e.preventDefault();
				
				if( $(this).attr("status") && parseFloat( $(this).attr("status") ) ){
					var data = {theme:'alert-info', err:'Expenditure has been fully paid', msg:'The expenditure you are trying to select has been fully paid for', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}else{
				
					$(this).siblings().removeClass("active");
					$(this).addClass("active");
					
					var $item = $(this);
					nwRecordPayment.cartItems.id = $item.attr("id");
					nwRecordPayment.cartItems.description = $item.attr("data-description");
					nwRecordPayment.cartItems.mode_of_payment = $item.attr("data-mode_of_payment");
					
					nwRecordPayment.cartItems.amount_paid = $item.attr("data-amount_paid");
					nwRecordPayment.cartItems.amount_owed = $item.attr("data-amount_owed");
					
					nwRecordPayment.cartItems.store = $item.attr("data-store");
					nwRecordPayment.cartItems.vendor = $item.attr("data-vendor");
					
					$("#sales-order-selectbox").html( '#' + $item.attr("serial") + '-' + $item.attr("id") );
					nwRecordPayment.recordPayment();
				}
			})
			.addClass("activated");
			
			$('select[name="vendor"]')
			.select2();
			
			nwRecordPayment.clear();
		},
		clear: function(){
			$("#sales-order-selectbox").html('');
			
			$("form#cart").find(".form-control").val('');
			nwRecordPayment.emptyCart();
			$('a[href="#capture-payment"]').click();
		},
		search: function(){
			$("form#sales").submit();
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
			$("tbody#payment-histories")
			.find("tr")
			.not(".cancelled")
			.on("click", function(e){
				e.preventDefault();
				
				if( $(this).hasClass("cancelled") ){
					$(this).removeClass("cancelled");
				}else{
					nwRecordPayment.cancelDeleteStockItem();
					
					if( ! $(this).hasClass("active") ){
						nwRecordPayment.selectedItemID = $(this).attr("id");
						nwRecordPayment.selectedItem = $(this).attr("data-sales_id");
						nwRecordPayment.selectedItemAmountPaid = $(this).attr("data-amount_paid") * 1;
						if( isNaN( nwRecordPayment.selectedItemAmountPaid ) )nwRecordPayment.selectedItemAmountPaid = 0;
						
						$(this).addClass("active");
						
						var $td = $(this).find("td:eq(0)");
						$td.attr( "default", $td.html() );
						$td.html( $("#delete-button-holder").html() );
					}
				}
			});
		},
		cancelDeleteStockItem: function () {
			
			var $otd = $("tbody#payment-histories").find("tr.active").find("td:eq(0)");
			$otd.html( $otd.attr("default") );
			
			$("tbody#payment-histories").find("tr.active").removeClass( "active" ).addClass("cancelled");
			
		},
		deleteStockItem: function () {
			$("#actual-delete-payment")
			.attr( "override-selected-record" , nwRecordPayment.selectedItemID )
			.click();
		},
		deletedStockItemSuccess: function () {
			if( nwRecordPayment.selectedItem && nwRecordPayment.selectedItemAmountPaid ){
				if( $( "tr#" + nwRecordPayment.selectedItem ) ){
					var paid = $( "tr#" + nwRecordPayment.selectedItem ).attr( "data-amount_paid" ) * 1;
					
					var due = ( $( "tr#" + nwRecordPayment.selectedItem ).attr( "data-amount_due" ) * 1 ) - ( $( "tr#" + nwRecordPayment.selectedItem ).attr( "data-discount" ) * 1 );
					
					var new_paid = paid - nwRecordPayment.selectedItemAmountPaid;
					var owed = due - new_paid;
					
					$( "tr#" + nwRecordPayment.selectedItem ).attr( "data-amount_paid", new_paid );
					$( "tr#" + nwRecordPayment.selectedItem ).attr( "data-amount_owed", owed );
					$( "tr#" + nwRecordPayment.selectedItem ).find("td.amount-owed").text( nwRecordPayment.addComma( owed.toFixed(2) ) );
					
				}
			}
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