var nwCustomers = function () {
	return {
		recordItem: {
			id:"",
			name:"",
			address:"",
			phone:"",
			second_phone:"",
			date_of_birth:"",
			city:"",
			credit_limit:0,
			his_ring_size:0,
			her_ring_size:0,
			email:"",
			spouse:"",
			referral_source:"",
			comment:"",
		},
		init: function () {			
			$('select[name="customer"]')
			.on("change", function(){
				nwCustomers.search();
			})
			.select2();
		},
		search: function () {	
			$("form#customer").submit();
		},
		searchCustomerWishList: function () {	
			$("form#customer_wish_list").submit();
		},
		searchCustomerCallLog: function () {	
			$("form#customer_call_log").submit();
		},
		activateTabs: function () {	
			var $item = $(".customer-item");
			$.each( nwCustomers.recordItem, function( k, v ){
				nwCustomers.recordItem[ k ] = $item.attr("data-" + k );
			} );
			
			$('a[href="#appraisals"]')
			.add(".populate-with-selected")
			.attr("override-selected-record", nwCustomers.recordItem.id );
			
			$("input.get-customer-id").val( nwCustomers.recordItem.id );
			
			nwCustomers.edit();
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
			
			$("input.get-customer-id").val( "" );
			
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