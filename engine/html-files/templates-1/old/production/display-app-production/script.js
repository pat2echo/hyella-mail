var nwSales = function () {
	return {
		expenseItem: {
			id:"",
			date:0,
			vendor:"",
			description:"",
			amount_due:0,
			amount_paid:0,
			mode_of_payment:"",
			receipt_no:"",
			category_of_expense:"",
			staff_in_charge:"",
		},
		init: function () {			
			$('#sales-view')
			.find("tr.item-sales")
			.not(".activated")
			.on('click', function(e){
				e.preventDefault();
				
				$('#sales-view')
				.find("tr.item-sales")
				.removeClass("active");
				
				$(this).addClass("active");
				
				var $item = $(this);
				nwSales.expenseItem.id = $item.attr("id");
				nwSales.expenseItem.date = $item.attr("data-date");
				nwSales.expenseItem.vendor = $item.attr("data-vendor");
				nwSales.expenseItem.description = $item.attr("data-description");
				nwSales.expenseItem.amount_due = $item.attr("data-amount_due");
				nwSales.expenseItem.amount_paid = $item.attr("data-amount_paid");
				nwSales.expenseItem.mode_of_payment = $item.attr("data-mode_of_payment");
				nwSales.expenseItem.receipt_no = $item.attr("data-receipt_no");
				nwSales.expenseItem.category_of_expense = $item.attr("data-category_of_expense");
				nwSales.expenseItem.staff_in_charge = $item.attr("data-staff_in_charge");
				
				nwSales.edit();
				
			})
			.addClass("activated");
			
		},
		showRecentExpensesTab: function () {	
			$('a[href="#recent-expenses"]').click();
		},
		reClick: function () {	
			$('#sales-view').find("tr.item-sales.active").click();
		},
		edit: function () {	
			
			$.each( nwSales.expenseItem, function( key, val ){
				if( $("form#expenditure").find('.form-control[name="'+key+'"]') ){
					$("form#expenditure").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
		},
		emptyNewItem: function(){
			$('#sales-view')
			.find("tr.item-sales")
			.removeClass("active");
				
			$("form#expenditure").find(".form-control").val('');
			nwSales.expenseItem = {
				id:"",
				date:0,
				vendor:"",
				description:"",
				amount_due:0,
				amount_paid:0,
				mode_of_payment:"",
				receipt_no:"",
				category_of_expense:"",
				staff_in_charge:"",
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
nwSales.init();