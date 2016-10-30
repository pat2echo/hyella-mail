var nwTransactions = function () {
	return {
		cartItems: {
			item:{},
			date:$('input[name="date"]').val(),
			description:$('input[name="description"]').val(),
			reference_table:$('input[name="reference_table"]').val(),
			source_account:$('select[name="source_account"]').val(),
			store:"",
			mode:$("#transaction-tabs").find(".tab-pane.active").attr("id"),
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
			
			$("form.account-list-form")
			.on('submit', function(e){
				e.preventDefault();
				
				//var launch_date = new Date();
				//var id = launch_date.getTime();
				
				var account = $(this).find('select[name="account2"]').val();
				var account_text1 = $(this).find('select[name="account2"]').select2( 'data' );
				var account_text = account_text1.text;
				
				var account_type = $(this).find('select[name="account_type"]').val();
				
				if( $(this).find(".s2-account-select-3").is(":visible") && $(this).find('select[name="account3"]').val() && $(this).find('select[name="account3"]').val().length > 1 ){
					var account = $(this).find('select[name="account3"]').val();
					var account_text1 = $(this).find('select[name="account3"]').select2( 'data' );
					var account_text = account_text1.text;
				}
					
				var id = account;
				var ref = '';
				
				if( $(this).find('select[name="extra_reference"]').is(":visible") ){
					ref = $(this).find('select[name="extra_reference"]').val();
				}
				
				if( ref )id = ref;
				
				var amount = parseFloat( $(this).find('input[name="amount"]').val() * 1 );
				
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
					if( ! nwTransactions.cartItems.item[ nwTransactions.cartItems.mode ] ){
						nwTransactions.cartItems.item[ nwTransactions.cartItems.mode ] = {};
					}
					
					nwTransactions.cartItems.item[ nwTransactions.cartItems.mode ][ id ] = {
						id: id,
						type:$(this).find('select[name="type"]').val(),
						amount:amount,
						account:account,
						account_type:account_type,
						account_text:account_text,
						comment:$(this).find('input[name="comment"]').val(),
						extra_reference:ref,
					};
					nwTransactions.refreshCart();
				}
				
				return false;
			});
			
			$("#transaction-tabs")
			.find(".nav-tabs")
			.find("a")
			.on("click", function(e){
				nwTransactions.updateMode();
			});
			
			$('select[name="source_account"]')
			.on('change', function(e){
				nwTransactions.cartItems.source_account = $(this).val();
			});
			
			$('select[name="extra_reference"]')
			.on('change', function(e){
				var ref = '';
				var amount = 0;
				var comment = '';
				
				var $parent = $(this).parents("form.account-list-form");
				if( $(this).val() ){
					ref = $(this).val();
					amount = parseFloat( $(this).find('option[value="'+ref+'"]').attr('data-amount_owed') * 1 );
					if( isNaN( amount ) )amount = 0;
					
					comment = $(this).find('option[value="'+ref+'"]').text();
				}
				
				$parent
				.find('input[name="amount"]')
				.val( amount );
				
				$parent
				.find('input[name="comment"]')
				.val( comment );
				
				if( amount ){
					$parent
					.find('input[name="amount"]')
					.attr( 'max', amount );
				}else{
					$parent
					.find('input[name="amount"]')
					.attr( 'max', '' );
				}
				
				$parent
				.find('#view-reference-details')
				.attr("override-selected-record", ref );
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
			
			$('select[name="account2"]')
			.on("change", function(){
				var $parent = $(this).parents("form.account-list-form");
				if( $parent.find('#check-for-reference').attr("action") ){
					if( $(this).val() && $(this).val() != "0" ){
						 $parent
						 .find('#check-for-reference')
						 .attr("override-selected-record", $(this).val() )
						 .click();
					}
				}else{
					if( $(this).val() && $(this).val() != "0" ){
						$parent
						.find('#check-for-sub-account')
						.attr("override-selected-record", $(this).val() )
						.click();
					}
					
					$parent
					.find(".s2-account-select-3")
					.hide();
				}
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
		reactivateVendorExtraReference: function(){
			nwTransactions.reactivateExtraReferenceSelect2( "#pay-vendors-extra-reference" );
		},
		reactivateCustomerExtraReference: function(){
			nwTransactions.reactivateExtraReferenceSelect2( "#post-customer-payment-extra-reference" );
		},
		reactivateExtraReferenceSelect2: function( id ){
			$(id).select2("val", "")
			var $parent = $(id).parents("form.account-list-form");
			
			$parent
			.find('input[name="amount"]')
			.val(0);
				
			$parent
			.find('input[name="comment"]')
			.val('');
			
			$parent
			.find('#view-reference-details')
			.attr("override-selected-record", "" );
		},
		postPreviewDraftTransactions: function(){
			$("#preview-draft.save-transaction").click();
		},
		show_account_select_2: function(){
			$("select.account-select-2").select2("val", "");
			$(".s2-account-select-2").show();
		},
		show_account_select_3: function(){
			$("select.account-select-3").select2("val", "");
			$(".s2-account-select-3").show();
		},
		debounceUpdateMode: "",
		updateMode: function(){
			//check for unsaved transactions
			//nwTransactions.cartItems.mode
			
			if( nwTransactions.debounceUpdateMode )clearTimeout( nwTransactions.debounceUpdateMode );
			nwTransactions.debounceUpdateMode = setTimeout(function(){ 
				nwTransactions.cartItems.mode = $("#transaction-tabs").find(".tab-pane.active").attr("id");
				//alert( "new "+ nwTransactions.cartItems.mode );
			} , 400 );
		},
		deleteCartItem: function( id ){
			if( id && nwTransactions.cartItems.item[ nwTransactions.cartItems.mode ] ){
				delete nwTransactions.cartItems.item[ nwTransactions.cartItems.mode ][ id ];
				nwTransactions.refreshCart();
			}
		},
		refreshCart: function () {
			var $t = $("#accounts-list-"+nwTransactions.cartItems.mode).find(".shopping-cart-table").find("tbody");
			var $tf = $("#accounts-list-"+nwTransactions.cartItems.mode).find(".shopping-cart-table").find("tfoot");
			
			$t.html('');
			
			$tf.html('');
			
			var html = "";
			
			var t = 0;
			var serial = 0;
			
			if( nwTransactions.cartItems.item[ nwTransactions.cartItems.mode ] ){
				$.each(nwTransactions.cartItems.item[ nwTransactions.cartItems.mode ], function(key, val){
					++serial;
					html += "<tr id='"+val.id+"'><td>"+serial+"</td><td>"+val.account_text+"<br /><i>"+val.comment+"</i></td><td class='r'>"+ nwTransactions.addComma( val.amount.toFixed(2) ) +"</td><td class='r'> <button class='btn btn-sm dark remove-account' data-id='"+val.id+"'><i class='icon-trash'></i></button> </td></tr>";
					
					t += val.amount;
				});
			}
				
			if( t ){
				$t.html( html );
				
				$tf
				.html( "<tr><th></th><th>TOTAL</th><th class='r'>"+ nwTransactions.addComma( t.toFixed(2) ) +"</th><th class='r'></th></tr>" );
			}
			
			nwTransactions.cartItems.credit = t;
			nwTransactions.cartItems.debit = t;
			
			$("button.remove-account")
			.on("click", function(){
				nwTransactions.deleteCartItem( $(this).attr("data-id") );
			});
			
		},
		emptyCart: function(){
			
			$('.accounts-list').find(".shopping-cart-table").find("tbody").html('');
			$('.accounts-list').find(".shopping-cart-table").find("tfoot").html('');
			
			$('input[name="amount"]').val("0.00");
			$('input[name="description"]').val("");
			$('input[name="reference_table"]').val("");
			
			$("#sub-accounts").html('');
			
			nwTransactions.cartItems.item = {};
			nwTransactions.cartItems.source_account = $('select[name="source_account"]').val();
			nwTransactions.cartItems.date = $('input[name="date"]').val();
			nwTransactions.cartItems.description = $('input[name="description"]').val();
			nwTransactions.cartItems.reference_table = $('input[name="reference_table"]').val();
			nwTransactions.cartItems.store = "";
			nwTransactions.cartItems.mode = $("#transaction-tabs").find(".tab-pane.active").attr("id");
			nwTransactions.cartItems.credit = 0;
			nwTransactions.cartItems.debit = 0;
			
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