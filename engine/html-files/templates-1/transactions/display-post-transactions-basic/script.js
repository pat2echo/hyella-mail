var nwTransactions = function () {
	return {
		cartItems: {
			item:{},
			date:$('input[name="date"]').val(),
			description:$('input[name="description"]').val(),
			reference_table:$('input[name="reference_table"]').val(),
			store:"",
			credit:0,
			debit:0,
		},
		init: function () {
			
			$("button.save-transaction")
			.on('click', function(e){
				e.preventDefault();
				var err = "";
				var msg = "";
				
				if( ! nwTransactions.cartItems.description ){
					err = "Invalid Description";
					msg = "Please enter a valid description of the transaction that you wish to post";
				}
				
				if( ! ( nwTransactions.cartItems.debit > 0 && nwTransactions.cartItems.credit > 0 ) ){
					err = "Invalid Amount to Debit / Credit";
					msg = "Please ensure that you have included accounts to debit & credit";
				}
				
				if( nwTransactions.cartItems.debit != nwTransactions.cartItems.credit ){
					err = "Non-matching Debit & Credit";
					msg = "Please ensure that the debit & credit sides of the transaction are equal";
				}
				
				if( err ){
					var data = {theme:'alert-danger', err:err, msg:msg, typ:'jsuerror' };
					nwDisplayNotification.display_notification( data );
				}else{
					$("body").data("json", nwTransactions.cartItems );
					
					$("a#save-transaction")
					.attr("override-selected-record", "json" )
					.attr("mod", $(this).attr("id") )
					.click();
				}
			});
			
			$("button#save-account-info")
			.on('click', function(e){
				e.preventDefault();
				
				var launch_date = new Date();
				var id = launch_date.getTime();
				
				var account = $('select[name="account"]').val();
				var account_text1 = $('select[name="account"]').select2( 'data' );
				var account_text = account_text1.text;
				
				var account_type = account;
				
				if( $(".s2-account-select-2").is(":visible") && $('select[name="account2"]').val() && $('select[name="account2"]').val().length > 1 ){
					var account = $('select[name="account2"]').val();
					var account_text1 = $('select[name="account2"]').select2( 'data' );
					var account_text = account_text1.text;
					
					if( $(".s2-account-select-3").is(":visible") && $('select[name="account3"]').val() && $('select[name="account3"]').val().length > 1 ){
						var account = $('select[name="account3"]').val();
						var account_text1 = $('select[name="account3"]').select2( 'data' );
						var account_text = account_text1.text;
					}
				}
				
				var amount = parseFloat( $('input[name="amount"]').val() * 1 );
				
				var err = "";
				var msg = "";
				if( ! ( amount > 0 ) ){
					err = "Invalid Amount";
					msg = "The amount that you want to debit or credit must be greater than zero(0)";
				}
				
				if( account == 0 || account == "0" ){
					err = "Invalid Acoount";
					msg = "Please select a valid account type to debit or credit";
				}
				
				if( err ){
					var data = {theme:'alert-danger', err:err, msg:msg, typ:'jsuerror' };
					nwDisplayNotification.display_notification( data );
				}else{
					nwTransactions.cartItems.item[ id ] = {
						id: id,
						type:$('select[name="type"]').val(),
						amount:amount,
						account:account,
						account_type:account_type,
						account_text:account_text,
					};
					nwTransactions.refreshCart();
				}
				
			});
			
			$('input[name="description"]')
			.on('blur', function(e){
				nwTransactions.cartItems.description = $(this).val();
			});
			
			$('input[name="reference_table"]')
			.on('blur', function(e){
				nwTransactions.cartItems.reference_table = $(this).val();
			});
			
			$('input[name="date"]')
			.on('blur', function(e){
				nwTransactions.cartItems.date = $(this).val();
			});
			
			$('select.select2')
			.not("active")
			.select2()
			.addClass("active");
			
			$('select[name="account"]')
			.on("change", function(){
				if( $(this).val() && $(this).val() != "0" ){
					$('#check-for-sub-account')
					.attr("override-selected-record", $(this).val() )
					.click();
				}
				
				//emtpy select box
				$(".s2-account-select-3")
				.add(".s2-account-select-2")
				.hide();
			});
			
			$('input[type="date"]')
			.not("active")
			.datepicker({
				rtl: App.isRTL(),
				autoclose: true,
				format: 'yyyy-mm-dd',
			})
			.addClass("active");
			
		},
		show_account_select_2: function(){
			$("select.account-select-2").select2("val", "");
			$(".s2-account-select-2").show();
		},
		show_account_select_3: function(){
			$("select.account-select-3").select2("val", "");
			$(".s2-account-select-3").show();
		},
		deleteCartItem: function( id ){
			if( id ){
				delete nwTransactions.cartItems.item[ id ];
				nwTransactions.refreshCart();
			}
		},
		refreshCart: function () {
			var $td = $("#debit-side").find(".shopping-cart-table").find("tbody");
			var $tc = $("#credit-side").find(".shopping-cart-table").find("tbody");
			
			var $tfd = $("#debit-side").find(".shopping-cart-table").find("tfoot");
			var $tfc = $("#credit-side").find(".shopping-cart-table").find("tfoot");
			
			$td.html('');
			$tc.html('');
			
			$tfd.html('');
			$tfc.html('');
			
			var htmld = "";
			var htmlc = "";
			
			var td = 0;
			var tc = 0;
			
			$.each(nwTransactions.cartItems.item, function(key, val){
				var h = "<tr id='"+val.id+"'><td>"+val.account_text+"</td><td class='r'>"+ nwTransactions.addComma( val.amount.toFixed(2) ) +"</td><td class='r'> <button class='btn btn-sm dark remove-account' data-id='"+val.id+"'><i class='icon-trash'></i></button> </td></tr>";
				if( val.type == "debit" ){
					htmld += h;
					td += val.amount;
				}else{
					htmlc += h;
					tc += val.amount;
				}
			});
			
				
			if( td ){
				$td.html( htmld );
				
				$tfd
				.html( "<tr><th>TOTAL DEBIT</th><th class='r'>"+ nwTransactions.addComma( td.toFixed(2) ) +"</th><th class='r'></th></tr>" );
			}
			
			if( tc ){
				$tc.html( htmlc );
				
				$tfc
				.html( "<tr><th>TOTAL CREDIT</th><th class='r'>"+ nwTransactions.addComma( tc.toFixed(2) ) +"</th><th class='r'></th></tr>" );
			}
			
			nwTransactions.cartItems.credit = tc;
			nwTransactions.cartItems.debit = td;
			
			var bal = td - tc;
			$("#balance-container").find(".balance").text( nwTransactions.addComma( ( bal ).toFixed(2) ) );
			
			if( bal == 0 ){
				$("#balance-container")
				.addClass("green")
				.find(".balance-icon")
				.html( '<i class="icon-ok"></i>' );
			}else{
				$("#balance-container")
				.removeClass("green")
				.find(".balance-icon")
				.html( '<i class="icon-remove"></i>' );
			}
			
			$("button.remove-account")
			.on("click", function(){
				nwTransactions.deleteCartItem( $(this).attr("data-id") );
			});
			
		},
		emptyCart: function(){
			
			$('input[name="amount"]').val("0.00");
			$('input[name="description"]').val("");
			$('input[name="reference_table"]').val("");
			
			$("#sub-accounts").html('');
			
			nwTransactions.cartItems = {
				item:{},
				date:$('input[name="date"]').val(),
				description:$('input[name="description"]').val(),
				reference_table:$('input[name="reference_table"]').val(),
				store:"",
				credit:0,
				debit:0,
			};
			nwTransactions.refreshCart();
			
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
nwTransactions.init();