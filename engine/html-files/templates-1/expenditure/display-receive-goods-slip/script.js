var nwProduction = function () {
	return {
		cartItems: {
			item:{},
			id:reference_id,
			description:$('input[name="description"]').val(),
			staff_responsible: nwProduction_getStaffResponsible(),
		},
		init: function () {			
			var $t = $("#picked-items").find("tbody tr.picked-item");
			
			$t.each( function(){
				var max = parseFloat( $(this).attr("data-quantity_expected") * 1 );
				var q = parseFloat( $(this).attr("data-quantity") * 1 );
				if( isNaN( q ) )q = 0;
				if( isNaN( max ) )max = 0;
				
				nwProduction.cartItems.item[ $(this).attr("id") ] = {
					id: $(this).attr("id"),
					desc: $(this).attr("data-title"),
					quantity: q,
					quantity_expected: max,
					mode: "#recent-expenses",
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
			
			$('input[name="description"]')
			.on('blur', function(e){
				nwProduction.cartItems.description = $(this).val();
			});
			
		},
		reClick: function(){
			nwProduction.emptyCart();
			$("#search-expenditure-invoice").submit();
		},
		refreshCart: function () {
				
			var $tf = $("#picked-items").find("tfoot");
			var htmlf = "";
			
			var tq = 0;
			var tt = 0;
			$.each(nwProduction.cartItems.item, function(key, val){
				tq += val.quantity;
				tt += val.quantity_expected;
			});
			
			if( tq ){
				htmlf += "<tr><th colspan='2'>TOTAL</th><th class='r'>"+nwProduction.addComma( tt )+"</th><th class='r'>"+ nwProduction.addComma( tq ) +"</th></tr>";
			}
			$tf.html( htmlf );
			
		},
		emptyCart: function(){
			
			$('#receive-goods-container').html("");
			$('#sales-record-search-result').html("");
			
			nwProduction.cartItems = {
				item:{},
				id:reference_id,
				description:$('input[name="description"]').val(),
				staff_responsible: nwProduction_getStaffResponsible(),
			};
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