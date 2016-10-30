var nwCart = function () {
	return {
		cartItems: {
			item:{},
			percentage_discount:0,
			discount:0,
			item_discount:0,
			amount_due:0,
			amount_paid:0,
			quantity:0,
			vendor:"",
			vendor_name:"",
			vendor_phone:"",
			sales_status:nwCart_getSalesStatus(),
			store:nwCurrentStore.currentStore.id,
			staff_responsible:"",
			date:"",
			comment:"",
			payment_method:"",
			total_amount_due:0,
		},
		server: server,
		appraisalItems: {},
		catalogueItems: {},
		mode: "pos",
		init: function () {			
			nwCart.activateItemFilter();
			nwCart.activateItemSelect();
			
			$("#cart-finish")
			.on("click", function(){
				$("body").data("json", nwCart.cartItems );
				$(this).attr("override-selected-record", "json" );
			});
			
			$("#cart-cancel")
			.on("click", function(){
				nwCart.emptyCart();
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
			
			$('input[name="vendor_name"]')
			.add('input[name="vendor_phone"]')
			.on('keyup blur', function(e){
				nwCart.cartItems.vendor_name = $('input[name="vendor_name"]').val();
				nwCart.cartItems.vendor_phone = $('input[name="vendor_phone"]').val();
			});
			
			$('select[name="vendor"]')
			.on('change', function(e){
				nwCart.cartItems.vendor = $(this).val();
				
				$("#view-vendor-info")
				.attr("override-selected-record", nwCart.cartItems.vendor );
			});
			
			$('select[name="payment_method"]')
			.on('change', function(e){
				nwCart.cartItems.payment_method = $(this).val();
			}).change();
			
			$('select[name="staff_responsible"]')
			.on('change', function(e){
				nwCart.cartItems.staff_responsible = $(this).val();
			});
			
			$('input[name="comment"]')
			.on('blur', function(e){
				nwCart.cartItems.comment = $(this).val();
			});
			
			$("#sales-status")
			.on('change', function(e){
				if( $(this).is(":checked") ){
					nwCart.cartItems.sales_status = 'stocked';
				}else{
					nwCart.cartItems.sales_status = "pending";
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
		activateItemSelect: function(){
			
			$("#stocked-items")
			.find(".cart-item-select" )
			.on('click', function(e){
				e.preventDefault();
				
				switch( nwCart.mode ){
				case "pos":
					var q = parseFloat( $(this).attr("data-max") ) * 1;
				
					var $b = $(this).find(".badge.quantity-select");
					var x = parseFloat( $b.text() ) * 1;
					
					if( isNaN(x) )x = 0;
					q = x + 1;
					
					if( q > x ){
						++x;
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
							weight: $(this).attr("data-weight_in_grams"),
							barcode: $(this).attr("data-barcode"),
							discount: 0,
						};
						
						nwCart.refreshCart();
					}else{
						var data = {theme:'alert-info', err:'Out of Stock Item', msg:'Please restock this item first', typ:'jsuerror' };
						$.fn.cProcessForm.display_notification( data );
					}
				break;
				}
				
			});
			
		},
		reClick: function(){
			$("#stocked-items")
			.find(".cart-item-select.active" )
			.click()
			.removeClass("active");
		},
		refreshCustomersList: function(){
			$('select[name="vendor"]').change();
		},
		showPOS: function(){
			nwCart.mode = "pos";
		},
		activateItemFilter: function(){
			$(".item-filter")
			.on("click", function(e){
				e.preventDefault();
				
				var $display = $(this).parents(".item-filter-box").find(".item-filter-display-text");
				$display.text( $(this).text() );
				
				switch( $(this).attr("id") ){
				case "all":
					$(".cart-item-select").show();
				break;
				default:
					$(".cart-item-select").hide();
					$(".cart-item-select." + $(this).attr("id") ).show();
				break;
				}
			});
		},
		saveCartItemEdit: function(){
			var id = $("tr.active-edit").attr("id");
			var type = $("tr.active-edit").attr("type");
			var q = 0;
			var d = 0;
			
			if( id ){
				var m = $("tr.active-edit").find("input.quantity").attr("max") * 1;
				
				q = $("tr.active-edit").find("input.quantity").val() * 1;
				if( isNaN( q ) )q = 0;
				
				d = $("tr.active-edit").find("input.cost-price").val() * 1;
				if( isNaN( d ) )d = 0;
			}
			
			if( q ){
				nwCart.cartItems.item[ id ].price = d;
				nwCart.cartItems.item[ id ].quantity = q;
				nwCart.cartItems.item[ id ].total = ( nwCart.cartItems.item[ id ].price * q );
			}
			nwCart.refreshCart();
		},
		deleteCartItem: function(){
			var id = $("tr.active-edit").attr("id");
			
			if( id ){
				delete nwCart.cartItems.item[ id ];
				$("#"+id+".cart-item-select").find(".badge.quantity-select").text("");
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
			var td = 0;
			
			$.each(nwCart.cartItems.item, function(key, val){
				var w = "";
				if( val.weight )w = " | " + val.weight + "g";
				
				html += "<tr id='"+val.id+"' max='"+val.max+"' max-price='"+val.total+"' type='"+val.type+"'><td>"+val.desc+"<br /><strong><small>#" + val.barcode + w + "</small></strong></td><td class='r'>"+ nwCart.addComma( val.price.toFixed(2) ) +"</td><td class='r'>"+nwCart.addComma( val.quantity )+"</td><td class='r'>"+ nwCart.addComma( val.total.toFixed(2) ) +"</td></tr>";
				
				$("#stocked-items").find("#"+key+".cart-item-select" ).find("span.b-c").html('<span class="badge quantity-select badge-roundless badge-success">'+val.quantity+'</span>');
				
				tq += val.quantity;
				tt += val.total;
			});
			
			nwCart.cartItems.quantity = tq;
			nwCart.cartItems.amount_due = tt;
			
			if( tt ){
				$("#surcharge-container").show();
			}
			
			$t.html( html );
			$tf.html( htmlf );
			nwCart.calculateTotal();
			
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
					
					$("tr.active-edit")
					.find("td:eq(1)")
					.html( $("tr.active-edit").attr("cost-price") );
					
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
				$sec.find("input").val( $(this).attr("quantity") );
				
				var $thir = $(this).find("td:eq(1)");
				$(this).attr("cost-price", $thir.text() );
				$thir.html( $new.find("span.2").html() );
				$thir.find("input").val( $(this).attr("cost-price") );
				
			});
			
		},
		emptyCart: function(){
			//$('#cart-checkout-container').hide();				
			
			$('#cart-category').show();
			$('#main-table-view').show();
			$('#cart-category-items').show();
			/*
			$("#stocked-items")
			.find(".cart-item" )
			.hide();
			*/
			$("#stocked-items")
			.find(".badge.quantity-select" )
			.html('');
			
			$('input[name="comment"]').val('');
			
			nwCart.cartItems = {
				item:{},
				discount:0,
				percentage_discount:0,
				amount_due:0,
				amount_paid:0,
				quantity:0,
				vendor:"",
				vendor_name:"",
				vendor_phone:"",
				sales_status:nwCart_getSalesStatus(),
				store:nwCurrentStore.currentStore.id,
				staff_responsible:$('select[name="staff_responsible"]').val(),
				date:$('input[name="date"]').val(),
				max:0,
				comment:"",
				payment_method:$('select[name="payment_method"]').val(),
				total_amount_due:0,
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
			$("#select-vendor").hide();
			$("#new-vendor").show();
			$('input[name="vendor_name"]').focus();
		},
		showSelectCustomerForm: function(){
			$("#select-vendor").show();
			$("#new-vendor").hide();
			
			nwCart.emptyCustomerForm();
		},
		emptyCustomerForm: function(){
			$('select[name="vendor"]').val('');
			$('input[name="vendor_name"]').val('');
			$('input[name="vendor_phone"]').val('');
		},
		calculateTotal: function(){
			var tt = nwCart.addTaxes();
			
			nwCart.cartItems.total_amount_due = tt;
			nwCart.cartItems.amount_paid = tt;
			
			$(".amount-due").text( nwCart.addComma( tt.toFixed(2) ) );
			$("#amount-paid").val( tt );
		},
		addTaxes: function(){
			var d = 0;
			var t = 0;
			if( nwCart.cartItems.amount_due )t = nwCart.cartItems.amount_due;
			
			if( nwCart.cartItems.percentage_discount ){
				nwCart.cartItems.discount = ( t * nwCart.cartItems.percentage_discount * 0.01 ).toFixed(2);
			}
			
			if( nwCart.cartItems.discount )d = nwCart.cartItems.discount;
			
			var htmlf = "";
			var html = "";
			
			if( t ){
				htmlf = "<tr><td colspan='4'>&nbsp;</td></tr><tr><th class='r' colspan='2'>TOTAL</th><th class='r'>"+nwCart.addComma( nwCart.cartItems.quantity )+"</th><th class='r'>"+ nwCart.addComma( t.toFixed(2) ) +"</th></tr>";
			}
			
			if( d ){
				htmlf += "<tr class='use'><td class='r' colspan='3'>DISCOUNT</td><td class='r'>"+nwCart.addComma( d )+"</td></tr>";
			}
			
			var tt = t - d;
			
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
			
			var $tf = $(".shopping-cart-table").find("tfoot");
			if( ( vat + sc + st ) ){
				
				if( vat ){
					html += "<tr class='use'><td class='r' colspan='3'>VAT "+ nwCart.cartItems.vat.toFixed(2) +"%</td><td class='r'>"+ nwCart.addComma( vat.toFixed(2) ) +"</td></tr>";
				}
				if( sc ){
					html += "<tr class='use'><td class='r' colspan='3'>SERVICE CHARGE "+ nwCart.cartItems.service_charge.toFixed(2) +"%</td><td class='r'>"+ nwCart.addComma( sc.toFixed(2) ) +"</td></tr>";
				}
				if( st ){
					html += "<tr class='use'><td class='r' colspan='3'>SERVICE CHARGE "+ nwCart.cartItems.service_tax.toFixed(2) +"%</td><td class='r'>"+ nwCart.addComma( st.toFixed(2) ) +"</td></tr>";
				}
				
				if( html ){
					tt += vat + sc + st;
					html += "<tr><th class='r' colspan='3'>NET TOTAL</th><th class='r'>"+ nwCart.addComma( tt.toFixed(2) ) +"</th></tr>";
				}
			}
			$tf.html( htmlf + html );
			
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
		return "stocked";
	}else{
		return "pending";
	}
};

nwCart.init();