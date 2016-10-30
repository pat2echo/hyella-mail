var nwCart = function () {
	return {
		cartItems: {
			item:{},
			discount:0,
			discount_type:"",
			item_discount:0,
			amount_due:0,
			amount_paid:0,
			quantity:0,
			customer:"",
			customer_name:"",
			customer_phone:"",
			sales_status:nwCart_getSalesStatus(),
			store:nwCurrentStore.currentStore.id,
			staff_responsible:"",
			date:"",
			comment:"",
			room:"",
			payment_method:"",
			vat:vat,
			service_charge:service_charge,
			service_tax:service_tax,
			total_amount_due:0,
		},
		fixed_discount_type: global_fixed_discount_type,
		capture_payment: capture_payment,
		server: server,
		appraisalItems: {},
		catalogueItems: {},
		mode: "pos",
		init: function () {			
			nwCart.activateItemFilter();
			nwCart.activateItemSelect();
			
			$("#view-appraisal")
			.on("click", function(){
				$("body").data("json", nwCart.appraisalItems );
				$(this).attr("override-selected-record", "json" );
			});
			
			$("#view-items")
			.on("click", function(){
				$("body").data("json", nwCart.catalogueItems );
				$(this).attr("override-selected-record", "json" );
			});
				
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
				
				$("#stocked-items")
				.find(".cart-item" )
				.hide();
			});
			
			$("#amount-paid")
			.on('keyup blur', function(e){
				nwCart.updateChange();
			});
			
			$("input#discount")
			.on('keyup blur', function(e){
				nwCart.cartItems.discount = parseFloat( $(this).val() ) * 1;
				nwCart.cartItems.discount_type = nwCart.fixed_discount_type;
				nwCart.calculateTotal();
			});
			
			$("select#discount")
			.on('change', function(e){
				if( $(this).find("option[value='"+$(this).val()+"']").attr("data-type") ){
					nwCart.setDiscount();
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
				
				$("#view-customer-info")
				.attr("override-selected-record", nwCart.cartItems.customer );
			}).select2();
			
			if( $('select[name="payment_method"]') ){
				$('select[name="payment_method"]')
				.on('change', function(e){
				nwCart.cartItems.payment_method = $(this).val();
				nwCart.updatePaymentMethod();
			}).change();
			}
			
			$('select[name="staff_responsible"]')
			.on('change', function(e){
				nwCart.cartItems.staff_responsible = $(this).val();
			});
			
			$('input[name="comment"]')
			.on('blur', function(e){
				nwCart.cartItems.comment = $(this).val();
			});
			
			if( $('select[name="room"]') ){
				$('select[name="room"]')
				.on('change', function(e){
					nwCart.cartItems.room = $(this).val();
				}).change();				
			}
			
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
		updateChange: function(){
			if( $('#customer-change') && $('#customer-change').is(":visible") ){
				var topay = parseFloat( $("#amount-paid").val() ) * 1;
				if( isNaN( topay ) )topay = 0;
				
				var balance = topay - nwCart.cartItems.total_amount_due;
				if( balance >= 0 ){
					nwCart.cartItems.amount_paid = nwCart.cartItems.total_amount_due;
					$('#customer-change').text( nwCart.addComma( balance.toFixed(2) ) );
				}else{
					nwCart.cartItems.amount_paid = topay;
					$('#customer-change').text('0.00');
				}
			}else{
				nwCart.cartItems.amount_paid = parseFloat( $("#amount-paid").val() ) * 1;
			}
		},
		updatePaymentMethod: function(){
			$("#use-staff-responsible").hide();
			$("#use-room-number").hide();
			
			switch( nwCart.cartItems.payment_method ){
			case "complimentary":
				$("#use-room-number").show();
				
				nwCart.cartItems.amount_paid = 0;
				$("#amount-paid")
				.val( 0 )
				.attr("disabled", true );
				$(".amount-due").text('0');
			break;
			case "complimentary_staff":
				$("#use-staff-responsible").show();
				nwCart.cartItems.amount_paid = 0;
				
				$("#amount-paid")
				.val( 0 )
				.attr("disabled", true );
				$(".amount-due").text('0');
			break;
			case "charge_to_room":
				$("#use-room-number").show();
				nwCart.cartItems.amount_paid = 0;
				$(".amount-due").text( nwCart.addComma( nwCart.cartItems.total_amount_due.toFixed(2) ) );
				
				$("#amount-paid")
				.val( 0 )
				.attr("disabled", true );
			break;
			default:
				$("#use-room-number").hide();
				nwCart.cartItems.amount_paid = nwCart.cartItems.total_amount_due;
				$(".amount-due").text( nwCart.addComma( nwCart.cartItems.total_amount_due.toFixed(2) ) );
				
				$("#amount-paid")
				.val( nwCart.cartItems.amount_paid )
				.attr("disabled", false );
			break;
			}
			
			if( ! nwCart.capture_payment ){
				if( $("#amount-paid") ){ $("#amount-paid").val( 0 ); }
				nwCart.cartItems.amount_paid = 0;
			}
			nwCart.updateChange();
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
					
					if( nwCart.cartItems.item[ $(this).attr("id") ] && nwCart.cartItems.item[ $(this).attr("id") ].quantity ){
						var x1 = parseFloat( nwCart.cartItems.item[ $(this).attr("id") ].quantity );
						if( isNaN( x1 ) )x1 = 0;
						if( x1 > x )x = x1;
					}
					
					if( $(this).attr("data-type") == "service" ){
						q = x + 1;
					}
					
					if( global_unlimited_items ){
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
				case "catalogue":
					nwCart.catalogueItems[ $(this).attr("id") ] = $(this).attr("id");
					$("#view-items").click();
				break;
				case "appraisal":
					nwCart.refreshAppraisalItems();
					nwCart.appraisalItems[ $(this).attr("id") ] = $(this).attr("id");
					$("#view-appraisal").click();
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
			$('select[name="customer"]').change();
		},
		showPOS: function(){
			nwCart.mode = "pos";
		},
		refreshAppraisalItems: function(){
			$.each( nwCart.appraisalItems , function(key, val){
				if( $('input[name="selling_price-'+key+'"]') && $('input[name="percentage_markup-'+key+'"]') ){
					nwCart.appraisalItems[ key ] = {
						id: key,
						appraised_value: $('input[name="selling_price-'+key+'"]').val(),
						percentage_markup: $('input[name="percentage_markup-'+key+'"]').val(),
					};
				}
			});
		},
		printAppraisal: function(){
			var customer = $('select[name="customer"]').val();
			if( customer ){
				nwCart.refreshAppraisalItems();
				$("body").data("json", nwCart.appraisalItems );
				
				$("#print-appraisal")
				.attr("override-selected-record", "json" )
				.attr("mod", customer )
				.click();
			}else{
				var data = {theme:'alert-info', err:'Invalid Customer', msg:'Please select a customer first', typ:'jsuerror' };
				$.fn.cProcessForm.display_notification( data );
			}
			
		},
		enableAppraisal: function(){
			$("input.selling_price")
			.on("change", function(){
				var id = $(this).attr("data-id");
				var sp = $('input[name="selling_price_only-'+id+'"]').val();
				var me = $(this).val();
				
				var r = ( ( me - sp ) / sp ) * 100;
				$('input[name="percentage_markup-'+id+'"]').attr( "value", r.toFixed(2) );
			}).on( "keyup", function(){ $(this).change(); } );
			
			$("input.percentage_markup")
			.on("change", function(){
				var id = $(this).attr("data-id");
				var sp = $('input[name="selling_price_only-'+id+'"]').val() * 1;
				var me = $(this).val() * 1;
				
				var r = ( me * sp / 100 ) + sp;
				$('input[name="selling_price-'+id+'"]').attr( "value", r.toFixed(2) );
			}).on( "keyup", function(){ $(this).change(); } );
		},
		showAppraisal: function(){
			nwCart.mode = "appraisal";
			
			if( ( nwCart.cartItems.item && Object.getOwnPropertyNames( nwCart.cartItems.item ).length ) ){
				$.each( nwCart.cartItems.item , function(key, val){
					nwCart.appraisalItems[ val.id ] = val.id;
				});
				
				//send request
				$("#view-appraisal").click();
			}
		},
		clearAppraisal: function(){
			$("#appraisal-container").html("");
			nwCart.appraisalItems = {};
		},
		clearCatalogue: function(){
			$("#catalogue-container").html("");
			nwCart.catalogueItems = {};
		},
		checkOutCatalogue: function(){
			if( ( nwCart.catalogueItems && Object.getOwnPropertyNames( nwCart.catalogueItems ).length ) ){
				nwCart.emptyCart();
				$('a[href="#new-stock"]').click();
				
				$.each( nwCart.catalogueItems , function(key, val){
					$("#stocked-items")
					.find("#"+ key +".cart-item-select")
					.click();
				});
			}else{
				
			}
		},
		showCatalogue: function(){
			nwCart.mode = "catalogue";
						
			//if empty, show items in cart
			if( ( nwCart.cartItems.item && Object.getOwnPropertyNames( nwCart.cartItems.item ).length ) ){
				$.each( nwCart.cartItems.item , function(key, val){
					nwCart.catalogueItems[ val.id ] = val.id;
				});
				
				//send request
				$("#view-items").click();
			}
			
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
				
				d = $("tr.active-edit").find("input.discount").val() * 1;
				if( isNaN( d ) )d = 0;
				
				if( type != "service" ){
					if( q > m )q = m;
				}
			}
			
			if( q ){
				nwCart.cartItems.item[ id ].discount = d;
				nwCart.cartItems.item[ id ].quantity = q;
				nwCart.cartItems.item[ id ].total = ( nwCart.cartItems.item[ id ].price * q ) - d;
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
				
				if( global_disable_line_discount ){
					html += "<tr id='"+val.id+"' max='"+val.max+"' max-price='"+val.total+"' type='"+val.type+"'><td>"+val.desc+"<br /><strong><small>#" + val.barcode + w + "</small></strong></td><td class='r'>"+ nwCart.addComma( val.price.toFixed(2) ) +"</td><td class='r'>"+nwCart.addComma( val.quantity )+"</td><td class='r'>"+ nwCart.addComma( val.total.toFixed(2) ) +"</td></tr>";
				}else{
					html += "<tr id='"+val.id+"' max='"+val.max+"' max-price='"+val.total+"' type='"+val.type+"'><td>"+val.desc+"<br /><strong><small>#" + val.barcode + w + "</small></strong></td><td class='r'>"+ nwCart.addComma( val.price.toFixed(2) ) +"</td><td class='r'>"+nwCart.addComma( val.quantity )+"</td><td class='r'>"+nwCart.addComma( val.discount )+"</td><td class='r'>"+ nwCart.addComma( val.total.toFixed(2) ) +"</td></tr>";
				}
				
				$("#stocked-items")
				.find("#"+key+".cart-item-select" )
				.find("span.b-c")
				.html('<span class="badge quantity-select badge-roundless badge-success">'+val.quantity+'</span>');
				
				td += val.discount;
				tq += val.quantity;
				tt += val.total;
			});
			
			nwCart.cartItems.item_discount = td;
			nwCart.cartItems.quantity = tq;
			nwCart.cartItems.amount_due = tt;
			
			if( tt ){
				$("#discount-container").show();
			}
			
			$t.html( html );
			$tf.html( htmlf );
			nwCart.calculateTotal();
			nwCart.updatePaymentMethod();
			
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
					
					if( ! global_disable_line_discount ){
						$("tr.active-edit")
						.find("td:eq(3)")
						.html( $("tr.active-edit").attr("discount") );
					}
					
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
				
				if( ! global_disable_line_discount ){
					var $thir = $(this).find("td:eq(3)");
					$(this).attr("discount", $thir.text() );
					$thir.html( $new.find("span.4").html() );
					$thir.find("input").val( $(this).attr("discount") );
					
					$thir.find("input").attr("max", $(this).attr("max-price") );
				}
				
				if( ! global_unlimited_items ){
					if( $(this).attr("type") != "service" ){
						$sec.find("input").attr("max", $(this).attr("max") );
					}
				}
				
				
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
			
			var staff_responsible = '';
			if( $('select[name="staff_responsible"]') && $('select[name="staff_responsible"]').val() ){
				staff_responsible = $('select[name="staff_responsible"]').val();
			}
			
			var payment_method = '';
			if( $('select[name="payment_method"]') && $('select[name="payment_method"]').val() ){
				payment_method = $('select[name="payment_method"]').val();
			}
			nwCart.cartItems = {
				item:{},
				discount_type:"",
				discount:0,
				amount_due:0,
				amount_paid:0,
				quantity:0,
				customer:"",
				customer_name:"",
				customer_phone:"",
				sales_status:nwCart_getSalesStatus(),
				store:nwCurrentStore.currentStore.id,
				staff_responsible:staff_responsible,
				date:$('input[name="date"]').val(),
				max:0,
				comment:"",
				payment_method:payment_method,
				room:$('select[name="room"]').val(),
				vat:vat,
				service_charge:service_charge,
				service_tax:service_tax,
				total_amount_due:0,
			};
			
			nwCart.setDiscount();
			nwCart.showSelectCustomerForm();
			nwCart.refreshCart();
			
			if( server ){
				nwCart.lastSearchValue = "ogbuitepugloriaokpans";
				nwCart.submitSearchForm();
			}
		},
		refreshDiscount: function(){
			if( $("select#discount") )$("select#discount").change();
		},
		setDiscount: function(){
			var $me = $("select#discount");
			if( $me && $me.find("option[value='"+$me.val()+"']").attr("data-type") ){
				var dtype = $me.find("option[value='"+$me.val()+"']");
				
				nwCart.cartItems.discount = dtype.attr("data-value");
				nwCart.cartItems.discount_type = dtype.attr("data-type");
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
			$('select[name="customer"]').val('').select2("val", "");
			$('input[name="customer_name"]').val('');
			$('input[name="customer_phone"]').val('');
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
			if( nwCart.cartItems.amount_due )t = nwCart.cartItems.amount_due;
			
			if( t ){
				switch( nwCart.cartItems.discount_type ){
				case 'surcharge_percentage':
				case 'percentage':
					d = ( t * nwCart.cartItems.discount * 0.01 );
				break;
				case 'fixed_value':
					d = nwCart.cartItems.discount * 1;
				break;
				}
			}
			
			var htmlf = "";
			var html = "";
			
			if( t ){
				if( global_disable_line_discount ){
					htmlf = "<tr><td colspan='4'>&nbsp;</td></tr><tr><th class='r' colspan='2'>TOTAL</th><th class='r'>"+nwCart.addComma( nwCart.cartItems.quantity )+"</th><th class='r'>"+ nwCart.addComma( t.toFixed(2) ) +"</th></tr>";
				}else{
					htmlf = "<tr><td colspan='5'>&nbsp;</td></tr><tr><th class='r' colspan='2'>TOTAL</th><th class='r'>"+nwCart.addComma( nwCart.cartItems.quantity )+"</th><th class='r'>"+nwCart.addComma( nwCart.cartItems.item_discount )+"</th><th class='r'>"+ nwCart.addComma( t.toFixed(2) ) +"</th></tr>";
				}
			}
			
			var tt = t;
			
			switch( nwCart.cartItems.discount_type ){
			case 'fixed_value':
			case 'percentage':
				if( tt && d ){
					if( global_disable_line_discount ){
						htmlf += "<tr class='use'><td class='r' colspan='3'>DISCOUNT</td><td class='r'>"+nwCart.addComma( d.toFixed(2) )+"</td></tr>";
					}else{
						htmlf += "<tr class='use'><td class='r' colspan='4'>DISCOUNT</td><td class='r'>"+nwCart.addComma( d.toFixed(2) )+"</td></tr>";
					}
					tt -= d;
				}
			break;
			}
			
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
				tt += vat + sc + st;
				
				if( vat ){
					html += "<tr class='use'><td class='r' colspan='4'>VAT "+ nwCart.cartItems.vat.toFixed(2) +"%</td><td class='r'>"+ nwCart.addComma( vat.toFixed(2) ) +"</td></tr>";
				}
				if( sc ){
					html += "<tr class='use'><td class='r' colspan='4'>SERVICE CHARGE "+ nwCart.cartItems.service_charge.toFixed(2) +"%</td><td class='r'>"+ nwCart.addComma( sc.toFixed(2) ) +"</td></tr>";
				}
				if( st ){
					html += "<tr class='use'><td class='r' colspan='4'>SERVICE CHARGE "+ nwCart.cartItems.service_tax.toFixed(2) +"%</td><td class='r'>"+ nwCart.addComma( st.toFixed(2) ) +"</td></tr>";
				}
			}
			
			var after_tax = 0;
			
			if( tt ){
				switch( nwCart.cartItems.discount_type ){
				case 'percentage_after_tax':
					after_tax = 1;
					d = ( tt * nwCart.cartItems.discount * 0.01 );
				break;
				case 'fixed_value_after_tax':
					after_tax = 1;
					d = nwCart.cartItems.discount * 1;
				break;
				}
			}
			
			if( tt && after_tax && d ){
				tt -= d;
				if( global_disable_line_discount ){
					html += "<tr class='use'><td class='r' colspan='3'>DISCOUNT</td><td class='r'>"+nwCart.addComma( d.toFixed(2) )+"</td></tr>";
				}else{
					html += "<tr class='use'><td class='r' colspan='4'>DISCOUNT</td><td class='r'>"+nwCart.addComma( d.toFixed(2) )+"</td></tr>";
				}
			}
				
			if( ( vat + sc + st ) ){
				if( html ){
					html += "<tr><th class='r' colspan='4'>NET TOTAL</th><th class='r'>"+ nwCart.addComma( tt.toFixed(2) ) +"</th></tr>";
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
			.find('input[name="search"]')
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
			
			$("form#search-form")
			.find('input[name="search"]')
			.on("focus", function(){
				this.select();
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
	if( global_sales_order )return "sales_order";
	
	if( $("input#sales-status").is(":checked") ){
		return "booked";
	}else{
		return "sold";
	}
};

nwCart.init();