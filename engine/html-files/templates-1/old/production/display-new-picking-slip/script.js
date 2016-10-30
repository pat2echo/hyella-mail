var nwProduction = function () {
	return {
		cartItems: {
			item:{},
			expenses:{},
			discount:0,
			extra_cost:0,
			total_cost:0,
			total_income:0,
			total_amount:0,
			quantity:0,
			quantity_materials:0,
			quantity_goods:0,
			expiry_date:"",
			mode: "#recent-expenses",
			stock_status: "sales-order",
			staff_responsible: nwProduction_getStaffResponsible(),
			factory: nwProduction_getFactory(),
			comment: "",
			reference: reference_id,
			reference_table: reference_table,
			store:nwProduction_getCurrentStore(),
			customer:customer,
		},
		init: function () {			
			var $t = $("#picked-items").find("tbody tr.picked-item");
			
			$t.each( function(){
				var max = parseFloat( $(this).attr("data-max") * 1 );
				var q = parseFloat( $(this).attr("data-quantity_picked") * 1 );
				var p = parseFloat( $(this).attr("data-cost_price") * 1 );
				if( isNaN( q ) )q = 0;
				if( isNaN( p ) )p = 0;
				if( isNaN( max ) )max = 0;
				
				nwProduction.cartItems.item[ $(this).attr("id") ] = {
					id: $(this).attr("id"),
					desc: $(this).attr("data-title"),
					price: p,
					selling_price: 0,
					quantity: q,
					total: q * p,
					max: max,
					mode: "#recent-expenses",
					type: "",
				};
			});
			
			nwProduction.refreshCart();
			
			$t
			.find("input.quantity")
			.on("change", function(){
				var key = $(this).attr("data-id");
				var q = parseFloat( $(this).val() * 1 );
				if( isNaN( q ) )q = 0;
				
				nwProduction.cartItems.item[ key ].quantity = q;
				nwProduction.cartItems.item[ key ].total = nwProduction.cartItems.item[ key ].price * nwProduction.cartItems.item[ key ].quantity;
				nwProduction.refreshCart();
			});
			
			$("#cart-finish")
			.on("click", function(){
				$("body").data("json", nwProduction.cartItems );
				$(this).attr("override-selected-record", "json" );
			});
			
			$('select[name="staff_responsible"]')
			.on('change', function(e){
				nwProduction.cartItems.staff_responsible = $(this).val();
			});
			
			$('select[name="factory"]')
			.on('change', function(e){
				nwProduction.cartItems.factory = $(this).val();
			});
			
			$('input[name="comment"]')
			.on('blur', function(e){
				nwProduction.cartItems.comment = $(this).val();
			});
			
		},
		reClick: function(){
			if( $("form#search-sales-invoice") && $("form#search-sales-invoice").is(":visible") ){
				$("form#search-sales-invoice").submit();
			}
		},
		refreshCart: function () {
				
			var $tf = $("#picked-items").find("tfoot");
			var htmlf = "";
			
			var tq = 0;
			var tt = 0;
			$.each(nwProduction.cartItems.item, function(key, val){
				tq += val.quantity;
				tt += val.max;
			});
			
			nwProduction.cartItems.quantity_materials = tq;
			nwProduction.cartItems.quantity_goods = tq;
			nwProduction.cartItems.quantity = tq;
			if( tq ){
				htmlf += "<tr><th colspan='2'>TOTAL</th><th class='r'>"+nwProduction.addComma( tt )+"</th><th class='r'>"+ nwProduction.addComma( tq ) +"</th></tr>";
			}else{
				$("#new-picking-slip").remove();
			}
			$tf.html( htmlf );
			
		},
		emptyCart: function(){
			
			$('input[name="comment"]').val("");
			
			nwProduction.cartItems = {
				item:{},
				expenses:{},
				extra_cost:0,
				discount:0,
				total_cost:0,
				total_income:0,
				total_amount:0,
				quantity:0,
				quantity_materials:0,
				quantity_goods:0,
				expiry_date:"",
				mode: "#recent-expenses",
				stock_status: "sales-order",
				staff_responsible: nwProduction_getStaffResponsible(),
				factory: nwProduction_getFactory(),
				comment: "",
				store:nwProduction_getCurrentStore,
				customer:customer,
				reference:reference_id,
				reference_table:reference_table,
			};
			nwProduction.refreshCart();
			
			nwProduction.cartItems.mode = "#recent-expenses";
		},
		calculateTotal: function(){
			
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

function nwProduction_getStaffResponsible(){
	if( $('select[name="staff_responsible"]') ){
		return $('select[name="staff_responsible"]').val();
	}else{
		return "";
	}
};

function nwProduction_getFactory(){
	if( $('select[name="factory"]') ){
		return $('select[name="factory"]').val();
	}else{
		return "";
	}
};

function nwProduction_getCurrentStore(){
	return "";
	/*
	if( nwCurrentStore && nwCurrentStore.currentStore && nwCurrentStore.currentStore.id ){
		return nwCurrentStore.currentStore.id;
	}else{
		return "";
	}
	*/
};

nwProduction.init();