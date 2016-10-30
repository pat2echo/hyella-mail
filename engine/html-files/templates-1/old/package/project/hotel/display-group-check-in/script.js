var nwGroupCheckIn = function () {
	return {
		cartItems: {
			item:{},
			rooms:{},
			guests:{},
			group:"",
			empty:0,
			store:nwCurrentStore.currentStore.id,
		},
		init: function () {
			
			$("#add-group-member")
			.on('click', function(e){
				e.preventDefault();
				
				error_title = "";
				var member = {};
				
				$(".group-member").each(function(){
					switch( $(this).attr("name") ){
					case "comment":
						member[ $(this).attr("name") ] = $(this).val();
					break;
					case "room":
					case "guest":
						if( ! $(this).val() ){
							error_title = $(this).attr("name");
						}
						member[ $(this).attr("name") + "_text" ] = $( this ).select2( 'data' ).text;
						member[ $(this).attr("name") ] = $(this).val();
					break;
					case "checkout_date":
						if( ! $(this).val() ){
							error_title = $(this).attr("name");
						}
						member[ $(this).attr("name") ] = $(this).val();
					break;
					}
				});
				
				if( error_title ){
					var data = {theme:'alert-info', err:'Missing Information', msg:'Please value for <strong>'+ error_title +'</strong> field', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
					return false;
				}
				
				if( nwGroupCheckIn.cartItems.guests[ member.guest ] ){
					var data = {theme:'alert-info', err:'Invalid Information', msg:'The guest <strong>'+ member.guest_text +'</strong> selected is already part of the group', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
					return false;
				}
				
				if( nwGroupCheckIn.cartItems.rooms[ member.room ] ){
					var data = {theme:'alert-info', err:'Invalid Information', msg:'The room <strong>'+ member.room_text +'</strong> selected is already part of the group', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
					return false;
				}
				
				nwGroupCheckIn.cartItems.guests[ member.guest ] = 1;
				nwGroupCheckIn.cartItems.rooms[ member.room ] = 1;
				
				nwGroupCheckIn.cartItems.item[ member.guest ] = member;
				
				nwGroupCheckIn.refreshCart();
			});
			
			$("#confirm-group-checkin")
			.on("click", function(){
				if( nwGroupCheckIn.cartItems.group && nwGroupCheckIn.cartItems.empty ){
					$("body").data("json", nwGroupCheckIn.cartItems );
					$("#save-group-checkin").attr("override-selected-record", "json" ).click();
				}else{
					
					var msg = 'Please select a valid group';
					if( ! nwGroupCheckIn.cartItems.empty ){
						msg = 'Please add guest to the group first';
					}
					
					var data = {theme:'alert-info', err:'Missing Information', msg:msg, typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
			});
			
			$("#cart-cancel")
			.on("click", function(){
				//nwGroupCheckIn.emptyCart();
			});
			
			$('input[name="checkout_date"]')
			.not("active")
			.datepicker({
				rtl: App.isRTL(),
				autoclose: true,
				format: 'yyyy-mm-dd',
			}).addClass("active");
			
			$('select[name="group"]')
			.on("change", function(){
				nwGroupCheckIn.cartItems.group = $(this).val();
			});
			
			$('select[name="group"]')
			.add('select[name="room"]')
			.add('select[name="guest"]')
			.select2({allowClear: true});
			
		},
		refreshGroupGuestList: function(){
			$('select.select-group-guest').select2("destroy").change();
			$('select.select-group-guest').select2();
		},
		refreshRoomGuestList: function(){
			$('select.select-room-guest').select2("destroy").change();
			$('select.select-room-guest').select2();
		},
		emptyCart: function (){
			
			nwGroupCheckIn.cartItems = {
				item:{},
				rooms:{},
				guests:{},
				group: $('select[name="group"]').val(),
				empty:0,
				store:nwCurrentStore.currentStore.id,
			};
			$("#group-members").html( '' );
		},
		refreshCart: function (){
			var html = "";
			nwGroupCheckIn.cartItems.empty = 0;
			
			$.each(nwGroupCheckIn.cartItems.item, function(key, val){
				html += "<tr id='"+val.guest+"' room='"+val.room+"'><td>"+val.guest_text+"</td><td class='r'>"+val.room_text+"</td><td class='r'>"+ val.checkout_date +"</td><td class='r'><button class='btn btn-sm dark remove-guest'><i class='icon-trash'></i> </button></td></tr>";
			});
			
			if( html )nwGroupCheckIn.cartItems.empty = 1;
			
			$("#group-members").html( html );
			
			$("button.remove-guest")
			.on("click", function(){
				var guest = $(this).parents('tr').attr("id");
				var room = $(this).parents('tr').attr("room");
				
				delete nwGroupCheckIn.cartItems.item[ guest ];
				delete nwGroupCheckIn.cartItems.guests[ guest ];
				delete nwGroupCheckIn.cartItems.rooms[ room ];
				
				nwGroupCheckIn.refreshCart();
			});
		},
		emptyCustomerForm: function(){
			$('select[name="customer"]').val('');
			$('input[name="customer_name"]').val('');
			$('input[name="customer_phone"]').val('');
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
nwGroupCheckIn.init();