var nwCheckIn = function () {
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
				var number_of_rooms = parseInt( $item.attr("data-quantity") * 1 );
				if( isNaN( number_of_rooms ) )number_of_rooms = 1;
				
				nwCheckIn.cartItems.rooms = {};
				
				nwCheckIn.selectedItemID = $item.attr("data-room_type_id");
				
				for( var x = 0; x < number_of_rooms; x++ ){
					var rate = parseFloat( $item.attr("data-rate") * 1 );
					if( isNaN( rate ) )rate = 0;
					
					nwCheckIn.cartItems.rooms[x] = {
						room_number_text:"",
						room_number:"",
						rate: rate,
						deposit: $item.attr("data-deposit"),
						checkin_date: $item.attr("data-checkin_date"),
						checkin_date_text: $item.attr("data-checkin_date_text"),
						checkout_date: $item.attr("data-checkin_date"),
						checkout_date_text: $item.attr("data-checkout_date_text"),
						comment:$item.attr("data-comment"),
						guest: $item.attr("data-guest"),
						guest_text: $item.attr("data-main_guest_text"),
						number_of_nights: nwCheckIn.calculateNumberOfNights( $item.attr("data-checkout_date_text") , $item.attr("data-checkin_date_text") ),
						number_of_people: 1,
						room_type: $item.attr("data-room_type_id"),
					};
				}
				nwCheckIn.cartItems.room_type_real = $item.attr("data-room_type");
				nwCheckIn.cartItems.room_type_id = $item.attr("data-room_type_id");
				nwCheckIn.cartItems.room_type = $item.attr("data-room_type_text");
				nwCheckIn.cartItems.id = $item.attr("data-id");
				nwCheckIn.cartItems.booking_status = $item.attr("data-booking_status");
				nwCheckIn.cartItems.comment = $item.attr("data-comment");
				nwCheckIn.cartItems.main_guest = $item.attr("data-main_guest_text");
				
				nwCheckIn.checkIn();
			})
			.addClass("activated");
			
			$('select[name="customer"]')
			.select2();
			
			$("#finish-check-in")
			.on("click", function(){
				$("body").data("json", nwCheckIn.cartItems );
				$(this).attr("override-selected-record", "json" );
			});
			
			nwCheckIn.clear();
		},
		clear: function(){
			$("#check-in-notification").show();
			$("#check-in-container-rooms").html('');
			$('a[href="#recent-expenses"]').click();
		},
		search: function(){
			$("form#hotel_checkin").submit();
		},
		selectPaymentInfoClick: function(){
			$('a[href="#recent-goods"]').click();
		},
		selectPaymentInfo: function(){
			if( ! nwCheckIn.cartItems.main_guest ){
				var data = {theme:'alert-info', err:'Select Reservation First', msg:'Click on a reservation in the table to the left to begin checking in', typ:'jsuerror' };
				$.fn.cProcessForm.display_notification( data );
				
				setTimeout( function(){ $('a[href="#recent-expenses"]').click(); }, 200 );
				return false;
			}
			
			if( nwCheckIn.cartItems.rooms ){
				var error = 0;
				$.each( nwCheckIn.cartItems.rooms, function( key, val ){
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
			.text( nwCheckIn.cartItems.main_guest );
			
			$.each( nwCheckIn.cartItems.rooms, function( key, val ){
				var item_total = val.quantity * val.rate;
				if( isNaN( item_total ) )item_total = 0;
				
				var trate = val.number_of_nights * val.rate;
				
				html += "<tr><td>Room No: <strong>"+val.room_number_text+"</strong><small><br />Guest: <strong>"+val.guest_text+"</strong></small></td><td class='r'>"+val.number_of_nights+"</td></tr>";
				
				tt += trate;
			} );
			
			$tf.html( htmlf );
			$t.html( html );
		},
		checkIn: function () {	
			$("#check-in-notification").hide();
			$("#check-in-container-rooms").html('');
			var dhtml = $("#check-in-container").html();
			var dhtml_button = $("#check-in-container-button").html();
			
			$('a[href="#recent-expenses"]').click();
			
			var i = 0;
			var html = '';
			$.each( nwCheckIn.cartItems.rooms, function( key, val ){
				var id = nwCheckIn.selectedItemID + '-' + key;
				i += 1;
				
				html = '<div id="'+id+'">';
					html += '<h4>' + nwCheckIn.cartItems.room_type + ' Room ' + i + '</h4><hr />' + dhtml;
				html += '</div>';
				
				$("#check-in-container-rooms").append( html );
				
				$("#"+id)
				.find( ".room_number" )
				.attr("name", "room_number" + key )
				.attr("serial", key )
				.attr("data-name", "room_number" )
				.find("option")
				.not(".all")
				.not("." + nwCheckIn.cartItems.room_type_real )
				.remove();
				
				$("#"+id)
				.find( ".room_number" )
				.select2();
				
				$("#"+id)
				.find( ".checkout_date" )
				.attr("name", "checkout_date" + key )
				.attr("serial", key )
				.attr("data-name", "checkout_date" )
				.val( val.checkout_date_text )
				.datepicker({
					rtl: App.isRTL(),
					autoclose: true,
					format: 'yyyy-mm-dd',
				});
				
				$("#"+id)
				.find( ".guest" )
				.attr("name", "guest" + key )
				.attr("serial", key )
				.attr("data-name", "guest" )
				.val( val.guest )
				.select2();
				
				$("#"+id)
				.find( ".comment" )
				.attr("name", "comment" + key )
				.attr("data-name", "comment" )
				.attr("serial", key );
			} );
			
			$("#check-in-container-rooms").append( dhtml_button );
			
			$('input[name="amount_paid"]')
			.attr("max", nwCheckIn.cartItems.amount_owed );
			
			$("#check-in-container-rooms")
			.find(".room_number")
			.on("change", function(){
				nwCheckIn.captureInputValue( $(this) );
			});
			
			$("#check-in-container-rooms")
			.find(".guest")
			.on("change", function(){
				nwCheckIn.captureInputValue( $(this) );
			});
			
			$("#check-in-container-rooms")
			.find(".checkout_date")
			.on("blur, change", function(){
				nwCheckIn.captureInputValue( $(this) );
			});
			
			$("#check-in-container-rooms")
			.find(".comment")
			.on("blur", function(){
				nwCheckIn.captureInputValue( $(this) );
			});
		},
		captureInputValue: function( $me ){
			if( $me.attr("serial") ){
				var serial = parseInt( $me.attr("serial") * 1 );
				if( isNaN( serial ) )serial = 0;
				
				if( nwCheckIn.cartItems.rooms[serial] ){
					switch( $me.attr("data-name") ){
					case "room_number":
						nwCheckIn.cartItems.rooms[serial].room_number = $me.val();
						nwCheckIn.cartItems.rooms[serial].room_number_text = $me.find("option[value='"+$me.val()+"']").text();
					break;
					case "guest":
						nwCheckIn.cartItems.rooms[serial].guest = $me.val();
					break;
					case "checkout_date":
						nwCheckIn.cartItems.rooms[serial].checkout_date_text = $me.val();
						
						nwCheckIn.cartItems.rooms[serial].number_of_nights = nwCheckIn.calculateNumberOfNights( nwCheckIn.cartItems.rooms[serial].checkout_date_text , nwCheckIn.cartItems.rooms[serial].checkin_date_text );
					break;
					case "comment":
						nwCheckIn.cartItems.rooms[serial].comment = $me.val();
					break;
					}
					
				}
			}
		},
		emptyCart: function(){
			nwCheckIn.cartItems = {
				rooms:{},
				id:"",
				booking_status:"",
				comment:"",
				room_type:"",
				main_guest:"",
			};
			$("tr.item-sales" ).removeClass("active");
			nwCheckIn.clear();
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
nwCheckIn.init();