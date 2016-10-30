var nwCheckOut = function () {
	return {
		cartItems: {
			rooms:{},
			id:"",
			booking_status:"",
			comment:"",
			main_guest:"",
			room_type:"",
		},
		selectedItem:"",
		selectedItemID:"",
		selectedItemAmountPaid:0,
		init: function () {			
			
			$("#hotel_checkin-record-search-result")
			.find("tr.item-sales" )
			.not(".activated")
			.on('click', function(e){
				e.preventDefault();
				
				$(this).siblings().removeClass("active");
				$(this).addClass("active");
				
				var $item = $(this);
				
				$("#view-bill")
				.attr("override-selected-record", $item.attr("data-id") )
				.attr( "mod", $item.attr("data-room") )
				.click();
				
				$("#view-guest-account")
				.attr("override-selected-record", $item.attr("data-main_guest") )
				.attr( "mod", $item.attr("data-id") );
				
				$("#check-in-notification").hide();
				$('a[href="#recent-expenses"]').click();
			})
			.addClass("activated");
			
			$('select[name="customer"]')
			.add('select[name="receipt_num"]')
			.select2();
			
			$("#finish-check-in")
			.on("click", function(){
				$("body").data("json", nwCheckOut.cartItems );
				$(this).attr("override-selected-record", "json" );
			});
			
			nwCheckOut.clear();
		},
		reClickGuestAccountIfInView: function(){
			if( ! $("#recent-expenses").hasClass("active") ){
				nwCheckOut.reClickGuestAccount();
			}
		},
		reClickGuestAccount: function(){
			$("#view-guest-account").click();
		},
		refreshBill: function(){
			//$("#view-bill").click();
			$("#hotel_checkin-record-search-result")
			.find("tr.item-sales.active" ).click();
		},
		clear: function(){
			$("#check-in-notification").show();
			$("#invoice-receipt-container").html('');
			$('a[href="#recent-expenses"]').click();
		},
		search: function(){
			$("form#hotel_checkin").submit();
		},
		selectPaymentInfoClick: function(){
			$('a[href="#recent-goods"]').click();
		},
		selectPaymentInfo: function(){
			if( ! nwCheckOut.cartItems.main_guest ){
				var data = {theme:'alert-info', err:'Select Reservation First', msg:'Click on a reservation in the table to the left to begin checking in', typ:'jsuerror' };
				$.fn.cProcessForm.display_notification( data );
				
				setTimeout( function(){ $('a[href="#recent-expenses"]').click(); }, 200 );
				return false;
			}
			
			if( nwCheckOut.cartItems.rooms ){
				var error = 0;
				$.each( nwCheckOut.cartItems.rooms, function( key, val ){
					if( ! ( val.room_number && val.number_of_nights && val.checkout_date_text ) ){
						error = 1;
					}
				});
				if( error ){
					var data = {
						theme:'alert-info', 
						err:'Specify Room Number Details', 
						msg:'Specify Room Number, Number of Nights & Checkout Date for each room', 
						typ:'jsuerror' 
					};
					$.fn.cProcessForm.display_notification( data );
					
					setTimeout( function(){ $('a[href="#recent-expenses"]').click(); }, 200 );
					return false;
				}
			}
			
			var $t = $("#recent-goods").find(".shopping-cart-table tbody");
			var $tf = $("#recent-goods").find(".shopping-cart-table tfoot");
			$t.html('');
			
			var html = "";
			var htmlf = "";
			
			var tq = 0;
			var tt = 0;
			
			$( "#recent-goods" )
			.find(".paying-guest")
			.text( nwCheckOut.cartItems.main_guest );
			
			$.each( nwCheckOut.cartItems.rooms, function( key, val ){
				var item_total = val.quantity * val.rate;
				if( isNaN( item_total ) )item_total = 0;
				
				var trate = val.number_of_nights * val.rate;
				
				html += "<tr><td>Room No: <strong>"+val.room_number_text+"</strong><small><br />Guest: <strong>"+val.guest_text+"</strong></small></td><td class='r'>"+val.number_of_nights+"</td><td class='r'>"+ nwCheckOut.addComma( trate.toFixed(2) ) +"</td></tr>";
				
				tt += trate;
			} );
			
			if( tt ){
				htmlf = "<tr><th colspan='2' class='r'>TOTAL </th><th class='r'>"+ nwCheckOut.addComma( tt.toFixed(2) ) +"</th></tr>";
			}
			$(".amount-due").text( nwCheckOut.addComma( tt.toFixed(2) ) );
			
			$tf.html( htmlf );
			$t.html( html );
		},
		checkIn: function () {	
			
		},
		emptyCart: function(){
			$("tr.item-sales" ).removeClass("active");
			nwCheckOut.clear();
		},
		calculateNumberOfNights: function ( last1, first1 ) {
			var split = last1.split("-");
			var year = parseInt( split[0] * 1 );
			var month = parseInt( split[1] * 1 ) - 1;
			var day = parseInt( split[2] * 1 );
			
			var split = first1.split("-");
			var year1 = parseInt( split[0] * 1 );
			var month1 = parseInt( split[1] * 1 ) - 1;
			var day1 = parseInt( split[2] * 1 );
			
			var last = new Date( year , month, day );
			var first = new Date( year1 , month1, day1 );
			
			var time = last.getTime() - first.getTime();
			
			return ( time / ( 3600 * 24 * 1000 ) );
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
nwCheckOut.init();