var nwCart = function () {
	return {
		cartItems: {
			item:{},
			tax:0,
			percentage_discount:0,
			discount:0,
			item_discount:0,
			amount_due:0,
			amount_paid:0,
			quantity:0,
			quantity_expected:0,
			vendor:"",
			sales_status:nwCart_getSalesStatus(),
			store:nwCurrentStore.currentStore.id,
			staff_responsible:"",
			date:"",
			comment:"",
			reference:"",
			payment_method:"",
			total_amount_due:0,
		},
		disable_editing_items: g_disable_editing_items,
		show_quantity_expected: g_show_quantity_expected,
		disable_tax: g_disable_tax,
		disable_discount: g_disable_discount,
		disable_price: g_disable_price,
		capture_payment: capture_payment,
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
			
			$("select#tax")
			.on('change', function(e){
				nwCart.cartItems.tax = parseFloat( $(this).val() ) * 1;
				nwCart.calculateTotal();
			});
			
			$('select[name="vendor"]')
			.on('change', function(e){
				nwCart.cartItems.vendor = $(this).val();
				
				if( $("#filter-vendor-info") ){
					$("#filter-vendor-info")
					.attr("override-selected-record", nwCart.cartItems.vendor )
					.click();
				}
				
				if( $("#view-vendor-info") ){
					$("#view-vendor-info")
					.attr("override-selected-record", nwCart.cartItems.vendor );
				}
			});
			
			if( $('select[name="purchase_order"]') ){
				$('select[name="purchase_order"]')
				.on('change', function(e){
					nwCart.cartItems.reference = $(this).val();
					
					$("#view-details-info")
					.add("#repopulate-item-button")
					.attr("override-selected-record", nwCart.cartItems.reference );
					
					$("#repopulate-item-button")
					.click();
				});
			}
			
			$('select[name="payment_method"]')
			.on('change', function(e){
				nwCart.cartItems.payment_method = $(this).val();
			}).change();
			
			$('select[name="staff_responsible"]')
			.on('change', function(e){
				nwCart.cartItems.staff_responsible = $(this).val();
			}).change();
			
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
			
			$("select.select2")
			.select2();
			
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
					var max = parseFloat( $(this).attr("data-max") ) * 1;
				
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
							quantity_ordered: max,
							quantity_expected: max,
							max: max,
							total: x * p,
							type: $(this).attr("data-type"),
							weight: $(this).attr("data-weight_in_grams"),
							barcode: $(this).attr("data-barcode"),
							discount: 0,
							tax: 0,
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
		rePopulateCart: function(){
			if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data && $.fn.cProcessForm.returned_ajax_data.data.event && $.fn.cProcessForm.returned_ajax_data.data.purchased_items ){
				nwCart.emptyCart2();
				
				var e = $.fn.cProcessForm.returned_ajax_data.data.event;
				var i = $.fn.cProcessForm.returned_ajax_data.data.purchased_items;
				
				$.each( nwCart.cartItems, function( k, v ){
					if( k != "item" ){
						if( e[ k ] ){
							nwCart.cartItems[k] = e[k];
						}
					}
				} );
				
				$.each( i, function( k, v ){
					if( v.id ){
						var t = ( v.cost_price * v.quantity );
						
						nwCart.cartItems.item[ v.id ] = {
							id: v.id,
							desc: v.desc,
							price: v.cost_price,
							cost_price: v.cost_price,
							quantity: v.quantity,
							quantity_ordered: v.quantity_ordered,
							quantity_expected: v.quantity_expected,
							max: v.quantity,
							total: t - ( t * v.discount / 100 ) + ( t * v.tax / 100 ),
							type: "",
							weight: "",
							barcode: v.barcode,
							discount: v.discount,
							tax: v.tax,
						};
					}
				} );
				
				nwCart.refreshCart();
			}else{
				var data = {theme:'alert-info', err:'Invalid Info', msg:'Please try again', typ:'jsuerror' };
				$.fn.cProcessForm.display_notification( data );
			}
		},
		purchaseOrderSelectRefresh: function(){
			$('select[name="purchase_order"]')
			.select2("destroy")
			.select2();
		},
		purchaseOrderSelectEmpty: function(){
			if( $('select[name="purchase_order"]') ){
				$('select[name="purchase_order"]')
				.select2("destroy")
				.html('<option value="">'+$('select[name="purchase_order"]').attr("data-default")+'</option>')
				.select2();
			}
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
			var discount = 0;
			var tax = 0;
			
			if( id ){
				var m = $("tr.active-edit").find("input.quantity").attr("max") * 1;
				
				q = $("tr.active-edit").find("input.quantity").val() * 1;
				if( isNaN( q ) )q = 0;
				
				d = $("tr.active-edit").find("input.cost-price").val() * 1;
				if( isNaN( d ) )d = 0;
				
				discount = $("tr.active-edit").find("input.discount").val() * 1;
				if( isNaN( discount ) )discount = 0;
				if( discount > 100 )discount = 100;
				
				tax = $("tr.active-edit").find("input.tax").val() * 1;
				if( isNaN( tax ) )tax = 0;
				if( tax > 100 )tax = 100;
			}
			
			if( q ){
				nwCart.cartItems.item[ id ].price = d;
				nwCart.cartItems.item[ id ].tax = tax;
				nwCart.cartItems.item[ id ].discount = discount;
				nwCart.cartItems.item[ id ].quantity = q;
				
				var total = ( nwCart.cartItems.item[ id ].price * q );
				var total_after = total - ( total * discount / 100 );
				nwCart.cartItems.item[ id ].total = total_after + ( total_after * tax / 100 );
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
			var tqe = 0;
			var tt = 0;
			var td = 0;
			
			$.each(nwCart.cartItems.item, function(key, val){
				var w = "";
				if( val.weight )w = " | " + val.weight + "g";
				
				html += "<tr id='"+val.id+"' max='"+val.max+"' max-price='"+val.total+"' type='"+val.type+"'><td>"+val.desc+"<br /><strong><small>#" + val.barcode + w + "</small></strong></td>";
				
				if( ! nwCart.disable_price )
					html += "<td class='r tprice' value='"+val.price+"'>"+ nwCart.addComma( val.price.toFixed(2) ) +"</td>";
				
				if( nwCart.show_quantity_expected ){
					html += "<td class='r'>"+nwCart.addComma( val.quantity_expected )+"</td>";
				}
				
				html += "<td class='r tquantity'>"+nwCart.addComma( val.quantity )+"</td>";
				
				if( ! nwCart.disable_discount )
					html += "<td class='r tdiscount'>"+nwCart.addComma( val.discount )+"</td>";
				
				if( ! nwCart.disable_tax )
					html += "<td class='r ttax'>"+nwCart.addComma( val.tax )+"</td>";
				
				if( ! nwCart.disable_price )
					html += "<td class='r'>"+ nwCart.addComma( val.total.toFixed(2) ) +"</td>";
				
				html += "</tr>";
				
				$("#stocked-items").find("#"+key+".cart-item-select" ).find("span.b-c").html('<span class="badge quantity-select badge-roundless badge-success">'+val.quantity+'</span>');
				
				tqe += val.quantity_expected;
				tq += val.quantity;
				tt += val.total;
			});
			
			nwCart.cartItems.quantity_expected = tqe;
			nwCart.cartItems.quantity = tq;
			nwCart.cartItems.amount_due = tt;
			
			if( tt ){
				$("#surcharge-container").show();
			}
			
			$t.html( html );
			$tf.html( htmlf );
			nwCart.calculateTotal();
			
			if( ! nwCart.disable_editing_items ){
				$t
				.find("tr")
				.on("click", function(){
					if( $(this).hasClass("active-edit") )return 1;
					
					if( $("tr.active-edit") ){
						$("tr.active-edit")
						.find("td:eq(0)")
						.html( $("tr.active-edit").attr("title") );
						
						$("tr.active-edit")
						.find("td.tquantity")
						.html( $("tr.active-edit").attr("quantity") );
						
						$("tr.active-edit")
						.find("td.tprice")
						.html( $("tr.active-edit").attr("cost-price") );
						
						$("tr.active-edit")
						.find("td.tdiscount")
						.html( $("tr.active-edit").attr("discount") );
						
						$("tr.active-edit")
						.find("td.ttax")
						.html( $("tr.active-edit").attr("tax") );
						
						$("tr.active-edit").removeClass( "active-edit" );
					}
					
					$(this).addClass("active-edit");
					
					var $new = $("#item-edit-template");
					
					var $first = $(this).find("td:eq(0)");
					$(this).attr("title", $first.text() );
					$first.html( $new.find("span.1").html() );
					
					var $sec = $(this).find("td.tquantity");
					$(this).attr("quantity", $sec.text() );
					$sec.html( $new.find("span.3").html() );
					$sec.find("input").val( $(this).attr("quantity") );
					
					if( ! nwCart.disable_price ){
						var $thir = $(this).find("td.tprice");
						$(this).attr("cost-price", $thir.attr('value') );
						$thir.html( $new.find("span.2").html() );
						$thir.find("input").val( $(this).attr("cost-price") );
					}
					
					if( ! nwCart.disable_discount ){
						var $thir = $(this).find("td.tdiscount");
						$(this).attr("discount", $thir.text() );
						$thir.html( $new.find("span.4").html() );
						$thir.find("input").val( $(this).attr("discount") );
					}
					
					if( ! nwCart.disable_tax ){
						var $thir = $(this).find("td.ttax");
						$(this).attr("tax", $thir.text() );
						$thir.html( $new.find("span.5").html() );
						$thir.find("input").val( $(this).attr("tax") );
					}
				});
			}
		},
		emptyCart2: function(){
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
			
			$("select#discount").val("");
			$("select#tax").val("");
			
			$('select[name="vendor"]').select2("val", "");
			$('input[name="comment"]').val('');
			
			nwCart.cartItems = {
				item:{},
				discount:0,
				tax:0,
				percentage_discount:0,
				amount_due:0,
				amount_paid:0,
				quantity:0,
				vendor:"",
				sales_status:nwCart_getSalesStatus(),
				store:nwCurrentStore.currentStore.id,
				staff_responsible:$('select[name="staff_responsible"]').val(),
				date:$('input[name="date"]').val(),
				max:0,
				comment:"",
				payment_method:$('select[name="payment_method"]').val(),
				reference:"",
				total_amount_due:0,
				quantity_expected:0,
			};
			
			nwCart.setDiscount();
			nwCart.refreshCart();
			
			
			if( server ){
				nwCart.lastSearchValue = "ogbuitepugloriaokpans";
				nwCart.submitSearchForm();
			}
		},
		emptyCart: function(){
			nwCart.purchaseOrderSelectEmpty();
			nwCart.emptyCart2();
		},
		refreshDiscount: function(){
			if( $("select#discount") )$("select#discount").change();
			if( $("select#tax") )$("select#tax").change();
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
		},
		calculateTotal: function(){
			var tt = nwCart.addTaxes();
			
			nwCart.cartItems.total_amount_due = tt;
			nwCart.cartItems.amount_paid = tt;
			
			$(".amount-due").text( nwCart.addComma( tt.toFixed(2) ) );
			
			if( $("#amount-paid") ){ $("#amount-paid").val( tt ); }
			
			if( ! nwCart.capture_payment ){
				if( $("#amount-paid") ){ $("#amount-paid").val( 0 ); }
				nwCart.cartItems.amount_paid = 0;
			}
		},
		addTaxes: function(){
			var d = 0;
			var t = 0;
			var tax = 0;
			
			if( nwCart.cartItems.amount_due )t = nwCart.cartItems.amount_due;
			
			if( nwCart.cartItems.percentage_discount ){
				nwCart.cartItems.discount = ( t * nwCart.cartItems.percentage_discount * 0.01 ).toFixed(2);
			}
			
			if( nwCart.cartItems.discount )d = nwCart.cartItems.discount;
			
			var htmlf = "";
			var html = "";
			
			var full_span = 6;
			var short_full_span = 2;
			var half_full_span = 3;
			if( nwCart.disable_price ){
				--full_span;
				--full_span;
				short_full_span = 1;
			}
			if( nwCart.disable_discount ){
				--full_span;
				--half_full_span;
			}
			if( nwCart.disable_tax ){
				--full_span;
				--half_full_span;
			}
			
			if( nwCart.show_quantity_expected ){
				++full_span;
			}
			
			if( nwCart.cartItems.quantity ){
				htmlf = "<tr><td colspan='"+full_span+"'>&nbsp;</td></tr><tr><th class='r' colspan='"+short_full_span+"'>TOTAL</th>";
				
				if( nwCart.show_quantity_expected ){
					htmlf += "<th class='r'>"+nwCart.addComma( nwCart.cartItems.quantity_expected )+"</th>";
				}
				
				htmlf += "<th class='r'>"+nwCart.addComma( nwCart.cartItems.quantity )+"</th>";
				
				if( ! nwCart.disable_price ){
					htmlf += "<th class='r' colspan='"+half_full_span+"'>"+ nwCart.addComma( t.toFixed(2) ) +"</th>";
				}
				htmlf += "</tr>";
			}
			
			if( ! nwCart.disable_price ){
				if( d ){
					htmlf += "<tr class='use'><td class='r' colspan='"+(full_span - 1)+"'>DISCOUNT</td><td class='r'>"+nwCart.addComma( d )+"</td></tr>";
				}
			}
			
			var tt = t - d;
			
			var vat = 0;
			var sc = 0;
			var st = 0;
			var tax = 0;
			if( nwCart.cartItems.tax ){
				tax = nwCart.cartItems.tax * tt / 100;
				$('.tax-amount-due').text( nwCart.addComma( tax.toFixed(2) ) );
			}
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
			if( ( ! nwCart.disable_price ) && ( tax + vat + sc + st ) ){
				
				if( tax ){
					html += "<tr class='use'><td class='r' colspan='"+(full_span - 1)+"'>TAX "+ nwCart.cartItems.tax.toFixed(2) +"%</td><td class='r'>"+ nwCart.addComma( tax.toFixed(2) ) +"</td></tr>";
				}
				if( vat ){
					html += "<tr class='use'><td class='r' colspan='"+(full_span - 1)+"'>VAT "+ nwCart.cartItems.vat.toFixed(2) +"%</td><td class='r'>"+ nwCart.addComma( vat.toFixed(2) ) +"</td></tr>";
				}
				if( sc ){
					html += "<tr class='use'><td class='r' colspan='"+(full_span - 1)+"'>SERVICE CHARGE "+ nwCart.cartItems.service_charge.toFixed(2) +"%</td><td class='r'>"+ nwCart.addComma( sc.toFixed(2) ) +"</td></tr>";
				}
				if( st ){
					html += "<tr class='use'><td class='r' colspan='"+(full_span - 1)+"'>SERVICE TAX "+ nwCart.cartItems.service_tax.toFixed(2) +"%</td><td class='r'>"+ nwCart.addComma( st.toFixed(2) ) +"</td></tr>";
				}
				
				if( html ){
					tt += tax + vat + sc + st;
					html += "<tr><th class='r' colspan='"+(full_span - 1)+"'>NET TOTAL</th><th class='r'>"+ nwCart.addComma( tt.toFixed(2) ) +"</th></tr>";
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
		clearSearchForm: function(){
			$("form#search-form")
			.find('input[name="search"]')
			.focus();
		},
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
					if( nwCart.lastSearchValue == $(this).val() )return false;
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
				nwCart.lastSearchValue = $(this).find('input[name="search"]').val();
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