var nwMakeReservation = function () {
	return {
		cartItems: {
			item:{},
			percentage_discount:0,
			discount:0,
			amount_due:0,
			amount_paid:0,
			quantity:0,
			number_of_children:"",
			number_of_adults:"",
			checkin_date:"",
			checkout_date:"",
			store:nwCurrentStore.currentStore.id,
			other_guest:"",
			main_guest:"",
			date:"",
			number_of_nights:0,
			comment:"",
			payment_method:"",
			vat:vat,
			service_charge:service_charge,
			service_tax:service_tax,
		},
		init: function () {			
			
			$("#extra-cost")
			.find(".cart-item-select" )
			.on('click', function(e){
				e.preventDefault();
				
				var $room_item = $(this).find(".room-type-item");
				var id = $room_item.attr("id");
				
				if( id ){
					if( $(this).hasClass("active") ){
						$(this).removeClass("active");
						
						if( nwMakeReservation.cartItems.item[ id ] ){
							delete nwMakeReservation.cartItems.item[ id ];
						}
						
					}else{
						$(this).addClass("active");
						
						var rate = parseFloat( $room_item.attr("data-rate") * 1 );
						var deposit_amount = parseFloat( $room_item.attr("data-deposit_amount") * 1 );
						var max_adults = parseFloat( $room_item.attr("data-max_adults") * 1 );
						var max_children = parseFloat( $room_item.attr("data-max_children") * 1 );
						
						if( isNaN( rate ) )rate = 0;
						if( isNaN( deposit_amount ) )deposit_amount = 0;
						if( isNaN( max_adults ) )max_adults = 0;
						if( isNaN( max_children ) )max_children = 0;
						
						nwMakeReservation.cartItems.item[ id ] = {
							id: id,
							rate: rate,
							quantity: 1,
							deposit_amount: deposit_amount,
							max_adults: max_adults,
							max_children: max_children,
							name: $room_item.attr("data-name"),
						};
					}
					nwMakeReservation.refreshCart(0);
				}
				
				
			});
			
			$("#save-reservation")
			.add("#save-check-in")
			.on("click", function(){
				$("body").data("json", nwMakeReservation.cartItems );
				$(this).attr("override-selected-record", "json" );
			});
			
			$("#cart-cancel")
			.on("click", function(){
				nwMakeReservation.emptyCart();
			});
			
			$("#amount-paid")
			.on('keyup blur', function(e){
				nwMakeReservation.cartItems.amount_paid = parseFloat( $(this).val() ) * 1;
			});
			
			$("input#discount")
			.on('keyup blur', function(e){
				nwMakeReservation.cartItems.discount = parseFloat( $(this).val() ) * 1;
				nwMakeReservation.cartItems.percentage_discount = 0;
				nwMakeReservation.calculateTotal();
			});
			
			$("select#discount")
			.on('change', function(e){
				var s = 1;
				if( $(this).find("option[value='"+$(this).val()+"']").attr("data-type") ){
					switch( $(this).find("option[value='"+$(this).val()+"']").attr("data-type") ){
					case 'percentage':
						nwMakeReservation.cartItems.percentage_discount = parseFloat( $(this).val() ) * 1;
						nwMakeReservation.cartItems.discount = 0;
						nwMakeReservation.calculateTotal();
						s = 0;
					break;
					}
				}
				
				if( s ){
					nwMakeReservation.cartItems.discount = parseFloat( $(this).val() ) * 1;
					nwMakeReservation.cartItems.percentage_discount = 0;
					nwMakeReservation.calculateTotal();
				}
			});
			
			$("#amount-paid")
			.on('keyup blur', function(e){
				nwMakeReservation.cartItems.amount_paid = parseFloat( $(this).val() ) * 1;
			});
			
			$('select[name="payment_method"]')
			.on('change', function(e){
				nwMakeReservation.cartItems.payment_method = $(this).val();
			}).change();
			
			$('select[name="other_guest"]')
			.on('change', function(e){
				nwMakeReservation.cartItems.other_guest = $(this).val();
			}).change();
			
			$('select[name="main_guest"]')
			.on('change', function(e){
				nwMakeReservation.cartItems.main_guest = $(this).val();
			});
			
			$('input[name="comment"]')
			.on('blur', function(e){
				nwMakeReservation.cartItems.comment = $(this).val();
			});
			
			$('input[name="checkin_date"]')
			.on('change', function(e){
				nwMakeReservation.cartItems.checkin_date = $(this).val();
			}).change();
			
			$('input[name="checkout_date"]')
			.on('change', function(e){
				nwMakeReservation.cartItems.checkout_date = $(this).val();
			}).change();
			
			$('input[name="number_of_adults"]')
			.on('blur', function(e){
				nwMakeReservation.cartItems.number_of_adults = $(this).val();
			}).blur();
			
			$('input[name="number_of_children"]')
			.on('blur', function(e){
				nwMakeReservation.cartItems.number_of_children = $(this).val();
			});
			
			$('select[name="main_guest"]')
			.add('select[name="other_guest"]')
			.select2();
			
			$('input[type="date"]')
			.on('change', function(e){
				nwMakeReservation.cartItems.date = $(this).val();
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
					nwMakeReservation.cartItems.sales_status = 'booked';
				}else{
					nwMakeReservation.cartItems.sales_status = "sold";
				}
			});
			
			nwCurrentStore.searchScope = ".raw_materials";
			nwCurrentStore.activateSearchForm();
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
				nwMakeReservation.cartItems.item[ id ].quantity = q;
				nwMakeReservation.cartItems.item[ id ].total = nwMakeReservation.cartItems.item[ id ].price * q;
			}
			nwMakeReservation.refreshCart(0);
		},
		deleteCartItem: function(){
			var id = $("tr.active-edit").attr("id");
			
			if( id ){
				delete nwMakeReservation.cartItems.item[ id ];
				$("#"+id+".cart-item-select").find(".badge").text("");
			}
			
			nwMakeReservation.refreshCart(0);
		},
		refreshCart: function ( refreshType ) {
			var add_html = 1;
			
			switch( refreshType ){
			case "1":
			case 1:
				add_html = 0;
			break;
			}
			
			var $t = $("#extra-cost").find(".shopping-cart-table tbody");
			var $tf = $("#extra-cost").find(".shopping-cart-table tfoot");
			
			if( add_html ){
				$t.html('');
				$("#discount-container").hide();
			}
			
			var html = "";
			var htmlf = "";
			
			var tq = 0;
			var tt = 0;
			
			$.each(nwMakeReservation.cartItems.item, function(key, val){
				var item_total = val.quantity * val.rate;
				if( isNaN( item_total ) )item_total = 0;
				
				if( add_html ){
					html += "<tr id='"+val.id+"' max='"+val.max_adults+"' rate='"+val.rate+"'><td>"+val.name+"</td><td class='r'><input name='"+val.id+"' type='number' value='"+val.quantity+"' min='1' class='form-control pull-right room-quantity' style='max-width:60px;' /></td><td class='room-total r'>"+ nwMakeReservation.addComma( item_total.toFixed(2) ) +"</td></tr>";
				}
				
				//$("#stocked-items").find("#"+key+".cart-item-select" ).find("span.b-c").html('<span class="badge badge-success">'+val.quantity+'</span>');
				
				tq += val.quantity;
				tt += item_total;
			});
			
			nwMakeReservation.cartItems.quantity = tq;
			nwMakeReservation.cartItems.amount_due = tt;
			
			if( tt ){
				$(".number-of-nights").text( nwMakeReservation.cartItems.number_of_nights );
				tt = tt * nwMakeReservation.cartItems.number_of_nights;
				nwMakeReservation.cartItems.amount_due = tt;
				
				htmlf += "<tr><td colspan='2'>&nbsp;</td></tr><tr><th class='r'>TOTAL <small style='font-size:7px;'>per NIGHT</small></th><th class='r'>"+ nwMakeReservation.addComma( tq ) +"</th><th class='r'>"+ nwMakeReservation.addComma( tt.toFixed(2) ) +"</th></tr>";
				
				$("#discount-container").show();
			}
			nwMakeReservation.calculateTotal();
			
			if( add_html ){
				$t.html( html );
				
				$t
				.find("input.room-quantity")
				.blur(function(){
					nwMakeReservation.refreshQuantitySelect( $(this) );
				})
				.change( function(){ 
					nwMakeReservation.refreshQuantitySelect( $(this) );
				} )
				.on("keypress", function(e){
					switch(e.keyCode){
					case "13":
					case 13:
						nwMakeReservation.refreshQuantitySelect( $(this) );
					break;
					}
				});
			}
			
			$tf.html( htmlf );
		},
		refreshQuantitySelect: function( $e ){
			var $tr = $e.parents("tr");
			var id = $tr.attr("id");
			
			if( id && nwMakeReservation.cartItems.item[id] ){
				var q = $e.val() * 1;
				if( isNaN( q ) )q = 1;
				
				nwMakeReservation.cartItems.item[id].quantity = q;
				
				var t = nwMakeReservation.cartItems.item[id].quantity * nwMakeReservation.cartItems.item[id].rate;
				$tr.find("td.room-total").text( nwMakeReservation.addComma( t.toFixed(2) ) );
				
				nwMakeReservation.refreshCart( 1 );
			}
		},
		emptyCart: function(){
			$("#extra-cost")
			.find(".cart-item-select" )
			.removeClass("active");
			
			$('select[name="main_guest"]')
			.add('select[name="other_guest"]')
			.add('input[name="comment"]')
			.add('input[name="number_of_children"]')
			.val('');
			
			$('input[name="number_of_adults"]')
			.val(1);
			
			nwMakeReservation.cartItems = {
				item:{},
				discount:0,
				percentage_discount:0,
				amount_due:0,
				amount_paid:0,
				quantity:0,
				number_of_children:"",
				number_of_adults:$('input[name="number_of_adults"]').val(),
				checkin_date:$('input[name="checkin_date"]').val(),
				checkout_date:$('input[name="checkout_date"]').val(),
				store:nwCurrentStore.currentStore.id,
				other_guest:"",
				main_guest:"",
				date:"",
				max:0,
				number_of_nights:0,
				comment:"",
				payment_method:$('select[name="payment_method"]').val(),
				vat:vat,
				service_charge:service_charge,
				service_tax:service_tax,
			};
			
			nwMakeReservation.setDiscount();
			nwMakeReservation.refreshCart(0);
			
			$('a[href="#recent-expenses"]').click();
		},
		refreshDiscount: function(){
			if( $("select#discount") )$("select#discount").change();
		},
		refreshCustomersList: function(){
			$('select[name="main_guest"]')
			.add('select[name="other_guest"]')
			.select2("updateResults");
			//
		},
		setDiscount: function(){
			var s = 1;
			var $me = $("select#discount");
			if( $me && $me.find("option[value='"+$me.val()+"']").attr("data-type") ){
				switch( $me.find("option[value='"+$me.val()+"']").attr("data-type") ){
				case 'percentage':
					nwMakeReservation.cartItems.percentage_discount = parseFloat( $me.val() ) * 1;
					nwMakeReservation.cartItems.discount = 0;
					s = 0;
				break;
				}
			}
			
			if( s ){
				nwMakeReservation.cartItems.discount = parseFloat( $("#discount").val() ) * 1;
				nwMakeReservation.cartItems.percentage_discount = 0;
			}
		},
		selectRoomClick: function(){
			//check for date & adult
			if( ! ( nwMakeReservation.cartItems.checkin_date && nwMakeReservation.cartItems.checkout_date && nwMakeReservation.cartItems.number_of_adults ) ){
				var data = {theme:'alert-info', err:'Missing Information', msg:'Please make sure you select check-in & check-out dates, & specify the number of adults', typ:'jsuerror' };
				$.fn.cProcessForm.display_notification( data );
				
				setTimeout( function(){ 
					$('a[href="#recent-expenses"]').click(); 
				}, 200 );
			}
			//calculate number of nights
			
			var split = nwMakeReservation.cartItems.checkout_date.split("-");
			var year = parseInt( split[0] * 1 );
			var month = parseInt( split[1] * 1 ) - 1;
			var day = parseInt( split[2] * 1 );
			
			var split = nwMakeReservation.cartItems.checkin_date.split("-");
			var year1 = parseInt( split[0] * 1 );
			var month1 = parseInt( split[1] * 1 ) - 1;
			var day1 = parseInt( split[2] * 1 );
			
			var last = new Date( year , month, day );
			var first = new Date( year1 , month1, day1 );
			
			var time = last.getTime() - first.getTime();
			
			nwMakeReservation.cartItems.number_of_nights = ( time / ( 3600 * 24 * 1000 ) );
			$(".number-of-nights").text( nwMakeReservation.cartItems.number_of_nights );
		},
		proceedSelectRoomClick: function(){
			$('a[href="#extra-cost"]').click(); 
		},
		selectGuestClick: function(){
			if( ! ( nwMakeReservation.cartItems.quantity ) ){
				var data = {theme:'alert-info', err:'Missing Information', msg:'You must first select a Room Type', typ:'jsuerror' };
				$.fn.cProcessForm.display_notification( data );
				
				setTimeout( function(){ 
					nwMakeReservation.proceedSelectRoomClick();
				}, 200 );
				
			}
		},
		proceedSelectGuestClick: function(){
			$('a[href="#recent-goods"]').click(); 
		},
		emptyCustomerForm: function(){
			$('select[name="customer"]').val('');
			$('input[name="customer_name"]').val('');
			$('input[name="customer_phone"]').val('');
		},
		calculateTotal: function(){
			var d = 0;
			var t = 0;
			if( nwMakeReservation.cartItems.amount_due )t = nwMakeReservation.cartItems.amount_due;
			
			if( nwMakeReservation.cartItems.percentage_discount ){
				nwMakeReservation.cartItems.discount = ( t * nwMakeReservation.cartItems.percentage_discount * 0.01 ).toFixed(2);
			}
			
			if( nwMakeReservation.cartItems.discount )d = nwMakeReservation.cartItems.discount;
			
			var tt = t - d;
			
			var vat = 0;
			var sc = 0;
			var st = 0;
			if( nwMakeReservation.cartItems.vat ){
				vat = nwMakeReservation.cartItems.vat * tt / 100;
				$('.vat-amount-due').text( nwMakeReservation.addComma( vat.toFixed(2) ) );
			}
			if( nwMakeReservation.cartItems.service_charge ){
				sc = nwMakeReservation.cartItems.service_charge * tt / 100;
				$('.service-charge-amount-due').text( nwMakeReservation.addComma( sc.toFixed(2) ) );
			}
			if( nwMakeReservation.cartItems.service_tax ){
				st = nwMakeReservation.cartItems.service_tax * tt / 100;
				$('.service-tax-amount-due').text( nwMakeReservation.addComma( st.toFixed(2) ) );
			}
			
			tt += vat + sc + st;
			
			nwMakeReservation.cartItems.amount_paid = tt;
			
			$(".amount-due").text( nwMakeReservation.addComma( tt.toFixed(2) ) );
			$("#amount-paid").val( tt );
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

function nwMakeReservation_getSalesStatus(){
	if( $("input#sales-status").is(":checked") ){
		return "booked";
	}else{
		return "sold";
	}
};

nwMakeReservation.init();