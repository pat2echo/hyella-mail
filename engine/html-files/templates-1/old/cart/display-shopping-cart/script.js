var nwCart = function () {
	return {
		cartItems: {
			item:{},
			percentage_discount:0,
			discount:0,
			amount_due:0,
			amount_paid:0,
			quantity:0,
			room:"",
			customer:"",
			customer_name:"",
			customer_phone:"",
			sales_status:nwCart_getSalesStatus(),
			store:nwCurrentStore.currentStore.id,
			staff_responsible:"",
			date:"",
			comment:"",
			payment_method:"",
			vat:vat,
			service_charge:service_charge,
			service_tax:service_tax,
		},
		server: server,
		init: function () {			
			$('#cart-category')
			.find(".category")
			.on('click', function(e){
				e.preventDefault();
				
				$("#stocked-items")
				.find("." + $(this).attr("id") )
				.show();
				
				$("#stocked-items")
				.find(".go-back" )
				.show();
				
				$('#cart-category').hide();
			});
			
			nwCart.activateItemFilter();
			
			$("#cart-finish")
			.on("click", function(){
				$("body").data("json", nwCart.cartItems );
				$(this).attr("override-selected-record", "json" );
			});
			
			$("#cart-cancel")
			.on("click", function(){
				nwCart.emptyCart();
			});
			
			$("#cart-checkout")
			.on("click", function(){
				
				$('#cart-checkout-container').show();
				$('#cart-category').hide();
				$('#main-table-view').hide();
				
				nwCart.updatePaymentMethod();
				
				$("#stocked-items")
				.find(".cart-item" )
				.hide();
			});
			
			$("#amount-paid")
			.on('keyup blur', function(e){
				nwCart.cartItems.amount_paid = parseFloat( $(this).val() ) * 1;
			});
			
			$("input#discount")
			.on('keyup blur', function(e){
				nwCart.cartItems.discount = parseFloat( $(this).val() ) * 1;
				nwCart.cartItems.percentage_discount = 0;
				nwCart.calculateTotal();
			});
			
			$("select#discount")
			.on('change', function(e){
				var s = 1;
				if( $(this).find("option[value='"+$(this).val()+"']").attr("data-type") ){
					switch( $(this).find("option[value='"+$(this).val()+"']").attr("data-type") ){
					case 'percentage':
						nwCart.cartItems.percentage_discount = parseFloat( $(this).val() ) * 1;
						nwCart.cartItems.discount = 0;
						nwCart.calculateTotal();
						s = 0;
					break;
					}
				}
				
				if( s ){
					nwCart.cartItems.discount = parseFloat( $(this).val() ) * 1;
					nwCart.cartItems.percentage_discount = 0;
					nwCart.calculateTotal();
				}
			});
			
			$('input[name="customer_name"]')
			.add('input[name="customer_phone"]')
			.on('keyup blur', function(e){
				nwCart.cartItems.customer_name = $('input[name="customer_name"]').val();
				nwCart.cartItems.customer_phone = $('input[name="customer_phone"]').val();
			});
			
			$('select[name="customer"]')
			.on('change', function(e){
				nwCart.cartItems.customer = $(this).val();
			});
			
			$('select[name="payment_method"]')
			.on('change', function(e){
				nwCart.cartItems.payment_method = $(this).val();
				nwCart.updatePaymentMethod();
			}).change();
			
			$('select[name="room"]')
			.on('change', function(e){
				nwCart.cartItems.room = $(this).val();
			}).change();
			
			$('select[name="staff_responsible"]')
			.on('change', function(e){
				nwCart.cartItems.staff_responsible = $(this).val();
			});
			
			$('input[name="comment"]')
			.on('blur', function(e){
				nwCart.cartItems.comment = $(this).val();
			});
			
			$('input[name="date"]')
			.on('change', function(e){
				nwCart.cartItems.date = $(this).val();
			})
			.not("active")
			.datepicker({
				rtl: App.isRTL(),
				autoclose: true,
				format: 'yyyy-mm-dd',
			})
			.addClass("active");
			
			$("#sales-status")
			.on('change', function(e){
				if( $(this).is(":checked") ){
					nwCart.cartItems.sales_status = 'booked';
				}else{
					nwCart.cartItems.sales_status = "sold";
				}
			});
			
			if( server ){
				nwCart.activateServerSearchForm();
				nwCart.activateServerItemFilter();
			}else{
				nwCurrentStore.searchScope = ".raw_materials";
				nwCurrentStore.activateSearchForm();
			}
		},
		updatePaymentMethod: function(){
			
			switch( nwCart.cartItems.payment_method ){
			case "complimentary":
				$("#use-room-number").show();
				$("#select-customer").hide();
				$("#new-customer").hide();
				
				nwCart.cartItems.amount_paid = 0;
				$("#amount-paid")
				.val( 0 )
				.attr("disabled", true );
				$(".amount-due").text('0');
			break;
			case "charge_to_room":
				$("#use-room-number").show();
				$("#select-customer").hide();
				$("#new-customer").hide();
				
				nwCart.calculateTotal();
				
				nwCart.cartItems.amount_paid = 0;
				$("#amount-paid")
				.val( 0 )
				.attr("disabled", true );
			break;
			default:
				$("#use-room-number").hide();
				$("#select-customer").show();
				$("#new-customer").hide();
				
				nwCart.calculateTotal();
				
				$("#amount-paid")
				.val( nwCart.cartItems.amount_paid )
				.attr("disabled", false );
			break;
			}
			
		},
		activateItemFilter: function(){
			
			$("#stocked-items")
			.find(".go-back" )
			.on('click', function(e){
				e.preventDefault();
				
				$('#cart-category').show();
				$('.active-category-box').show();
				
				$("#stocked-items")
				.find(".cart-item" )
				.hide();
				
				nwCurrentStore.clearSearchForm();
			});
			
			$("#stocked-items")
			.find(".cart-item-select" )
			.on('click', function(e){
				e.preventDefault();
				
				var q = $(this).attr("data-max");
				
				var $b = $(this).find(".badge");
				var x = parseFloat( $b.text() ) * 1;
				if( isNaN(x) )x = 0;
				
				if( $(this).attr("data-type") == "service" ){
					q = x + 1;
				}
				
				if( q > x ){
					++x;
				}else{
					var data = {theme:'alert-info', err:'Max. Quantity Reached', msg:'You have selected the maximum quantity for this item based on current stock level', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
				
				if( x > 0 ){
					var p = parseFloat( $(this).attr("data-price") );
					var cp = parseFloat( $(this).attr("data-cost_price") );
					
					nwCart.cartItems.item[ $(this).attr("id") ] = {
						id: $(this).attr("id"),
						desc: $(this).find(".item-title").text(),
						price: p,
						cost_price: cp,
						quantity: x,
						max: $(this).attr("data-max"),
						total: x * p,
						type: $(this).attr("data-type"),
					};
					nwCart.refreshCart();
				}else{
					var data = {theme:'alert-info', err:'Out of Stock Item', msg:'Please restock this item first', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
				
			});
		},
		reClick: function(){
			$("#stocked-items")
			.find(".cart-item-select.active" )
			.click()
			.removeClass("active");
		},
		saveCartItemEdit: function(){
			var id = $("tr.active-edit").attr("id");
			var type = $("tr.active-edit").attr("type");
			var q = 0;
			
			if( id ){
				var m = $("tr.active-edit").find("input.quantity").attr("max") * 1;
				
				q = $("tr.active-edit").find("input.quantity").val() * 1;
				if( isNaN( q ) )q = 0;
				
				if( type != "service" ){
					if( q > m )q = m;
				}
			}
			
			if( q ){
				nwCart.cartItems.item[ id ].quantity = q;
				nwCart.cartItems.item[ id ].total = nwCart.cartItems.item[ id ].price * q;
			}
			nwCart.refreshCart();
		},
		deleteCartItem: function(){
			var id = $("tr.active-edit").attr("id");
			
			if( id ){
				delete nwCart.cartItems.item[ id ];
				$("#"+id+".cart-item-select").find(".badge").text("");
			}
			
			nwCart.refreshCart();
		},
		refreshCart: function () {	
			var $t = $(".shopping-cart-table").find("tbody");
			var $tf = $(".shopping-cart-table").find("tfoot");
			
			$t.html('');
			$("#discount-container").hide();
			
			var html = "";
			var htmlf = "";
			
			var tq = 0;
			var tt = 0;
			
			$.each(nwCart.cartItems.item, function(key, val){
				html += "<tr id='"+val.id+"' max='"+val.max+"' type='"+val.type+"'><td>"+val.desc+"</td><td class='r'>"+ nwCart.addComma( val.price.toFixed(2) ) +"</td><td class='r'>"+nwCart.addComma( val.quantity )+"</td><td class='r'>"+ nwCart.addComma( val.total.toFixed(2) ) +"</td></tr>";
				
				$("#stocked-items").find("#"+key+".cart-item-select" ).find("span.b-c").html('<span class="badge badge-success">'+val.quantity+'</span>');
				
				tq += val.quantity;
				tt += val.total;
			});
			
			nwCart.cartItems.quantity = tq;
			nwCart.cartItems.amount_due = tt;
			
			if( tt ){
				htmlf += "<tr><td colspan='4'>&nbsp;</td></tr><tr><th class='r' colspan='2'>TOTAL</th><th class='r'>"+nwCart.addComma( tq )+"</th><th class='r'>"+ nwCart.addComma( tt.toFixed(2) ) +"</th></tr>";
				
				$("#discount-container").show();
			}
			nwCart.calculateTotal();
			
			$t.html( html );
			
			$t
			.find("tr")
			.on("click", function(){
				if( $(this).hasClass("active-edit") )return 1;
				
				if( $("tr.active-edit") ){
					$("tr.active-edit")
					.find("td:eq(0)")
					.html( $("tr.active-edit").attr("title") );
					
					$("tr.active-edit")
					.find("td:eq(2)")
					.html( $("tr.active-edit").attr("quantity") );
					
					$("tr.active-edit").removeClass( "active-edit" );
				}
				
				$(this).addClass("active-edit");
				
				var $new = $("#item-edit-template");
				
				var $first = $(this).find("td:eq(0)");
				$(this).attr("title", $first.text() );
				$first.html( $new.find("span.1").html() );
				
				var $sec = $(this).find("td:eq(2)");
				$(this).attr("quantity", $sec.text() );
				$sec.html( $new.find("span.3").html() );
				
				if( $(this).attr("type") != "service" ){
					$sec.find("input").attr("max", $(this).attr("max") );
				}
			});
			
			$tf.html( htmlf );
		},
		emptyCart: function(){
			$('#cart-checkout-container').hide();				
			
			$('#cart-category').show();
			$('#main-table-view').show();
			$('#cart-category-items').show();
			
			$("#stocked-items")
			.find(".cart-item" )
			.hide();
			
			$("#stocked-items")
			.find(".badge" )
			.html('');
			
			$('input[name="comment"]').val('');
			$('select[name="room"]').val('');
			
			nwCart.cartItems = {
				item:{},
				discount:0,
				percentage_discount:0,
				amount_due:0,
				amount_paid:0,
				quantity:0,
				customer:"",
				customer_name:"",
				customer_phone:"",
				sales_status:nwCart_getSalesStatus(),
				store:nwCurrentStore.currentStore.id,
				staff_responsible:$('select[name="staff_responsible"]').val(),
				date:$('input[name="date"]').val(),
				max:0,
				comment:"",
				payment_method:$('select[name="payment_method"]').val(),
				room:$('select[name="room"]').val(),
				vat:vat,
				service_charge:service_charge,
				service_tax:service_tax,
			};
			
			nwCart.setDiscount();
			nwCart.showSelectCustomerForm();
			nwCart.refreshCart();
		},
		refreshDiscount: function(){
			if( $("select#discount") )$("select#discount").change();
		},
		setDiscount: function(){
			var s = 1;
			var $me = $("select#discount");
			if( $me && $me.find("option[value='"+$me.val()+"']").attr("data-type") ){
				switch( $me.find("option[value='"+$me.val()+"']").attr("data-type") ){
				case 'percentage':
					nwCart.cartItems.percentage_discount = parseFloat( $me.val() ) * 1;
					nwCart.cartItems.discount = 0;
					s = 0;
				break;
				}
			}
			
			if( s ){
				nwCart.cartItems.discount = parseFloat( $("#discount").val() ) * 1;
				nwCart.cartItems.percentage_discount = 0;
			}
		},
		showNewCustomerForm: function(){
			$("#select-customer").hide();
			$("#new-customer").show();
			$('input[name="customer_name"]').focus();
		},
		showSelectCustomerForm: function(){
			$("#select-customer").show();
			$("#new-customer").hide();
			
			nwCart.emptyCustomerForm();
		},
		emptyCustomerForm: function(){
			$('select[name="customer"]').val('');
			$('input[name="customer_name"]').val('');
			$('input[name="customer_phone"]').val('');
		},
		calculateTotal: function(){
			var d = 0;
			var t = 0;
			if( nwCart.cartItems.amount_due )t = nwCart.cartItems.amount_due;
			
			if( nwCart.cartItems.percentage_discount ){
				nwCart.cartItems.discount = ( t * nwCart.cartItems.percentage_discount * 0.01 ).toFixed(2);
			}
			
			if( nwCart.cartItems.discount )d = nwCart.cartItems.discount;
			
			var tt = t - d;
			
			tt = nwCart.addTaxes( tt );
			nwCart.cartItems.amount_paid = tt;
			
			$(".amount-due").text( nwCart.addComma( tt.toFixed(2) ) );
			$("#amount-paid").val( tt );
		},
		addTaxes: function( tt ){
			var vat = 0;
			var sc = 0;
			var st = 0;
			if( nwCart.cartItems.vat ){
				vat = nwCart.cartItems.vat * tt / 100;
				$('.vat-amount-due').text( nwCart.addComma( vat.toFixed(2) ) );
			}
			if( nwCart.cartItems.service_charge ){
				sc = nwCart.cartItems.service_charge * tt / 100;
				$('.service-charge-amount-due').text( nwCart.addComma( sc.toFixed(2) ) );
			}
			if( nwCart.cartItems.service_tax ){
				st = nwCart.cartItems.service_tax * tt / 100;
				$('.service-tax-amount-due').text( nwCart.addComma( st.toFixed(2) ) );
			}
			tt += vat + sc + st;
			
			return tt;
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
		},
		debounceTimer: "",
		lastSearchValue: "",
		activateServerSearchForm: function(){
			
			$("form#search-form")
			.find("input")
			.on("change", function(){
				$(this).keyup();
			})
			.on("keyup", function( e ){
				
				switch(e.keyCode){
				case 46: case 17: case 16: case 35: case 36: case 40:
				case 38: case 37: case 39: case 32: case 8: case 27:
				case 13: break;
				default:
					if( nwCart.debounceTimer ){
						clearTimeout( nwCart.debounceTimer );
						nwCart.debounceTimer = "";
					}
					
					if( $(this).val() && $(this).val().length > 1 ){
						nwCart.debounceTimer = setTimeout( function(){ $("form#search-form").submit(); }, 1200 );
					}
				break;
				}
			});
			
			$("form#search-form")
			.on("submit", function(){
				if( nwCart.debounceTimer ){
					clearTimeout( nwCart.debounceTimer );
					nwCart.debounceTimer = "";
				}
			});
		},
		activateServerItemFilter: function(){
			$("select#item-filter")
			.on("change", function(e){
				e.preventDefault();
				
				$("form#search-form")
				.find('input[name="category_filter"]')
				.val( $(this).val() );
				
				nwCart.submitSearchForm();
			});
		},
		submitSearchForm: function(){
			nwCart.debounceTimer = setTimeout( function(){ $("form#search-form").submit(); } , 300 );
		}
	};
	
}();

function nwCart_getSalesStatus(){
	if( $("input#sales-status").is(":checked") ){
		return "booked";
	}else{
		return "sold";
	}
};

nwCart.init();