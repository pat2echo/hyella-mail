var nwInventory = function () {
	return {
		cartItems: {
			item:{},
			expenses:{},
			discount:0,
			extra_cost:0,
			total_cost:0,
			total_income:0,
			total_amount:0,
			quantity:0,
			quantity_materials:0,
			quantity_goods:0,
			expiry_date:"",
			reason: nwInventory_getReason(),
			mode: nwInventory_getMode(),
			stock_status: nwInventory_getStockStatus(),
			staff_responsible: nwInventory_getStaffResponsible(),
			factory: nwInventory_getFactory(),
			comment: "",
			reference: global_reference_id,
			reference_table: global_reference_table,
			store:nwCurrentStore.currentStore.id,
		},
		init: function () {
			
			nwInventory.activateItemSelect();
			
			$("#cart-finish")
			.add("#cart-finish-1")
			.on("click", function(){
				$("body").data("json", nwInventory.cartItems );
				$(this).attr("override-selected-record", "json" );
			});
			
			$('select[name="staff_responsible"]')
			.on('change', function(e){
				nwInventory.cartItems.staff_responsible = $(this).val();
			});
			
			$('select[name="factory"]')
			.on('change', function(e){
				nwInventory.cartItems.factory = $(this).val();
			});
						
			$('select[name="reason"]')
			.on('change', function(e){
				nwInventory.cartItems.reason = $(this).val();
			});
			
			$('input[name="comment"]')
			.on('blur', function(e){
				nwInventory.cartItems.comment = $(this).val();
			});
			
			$("#expiry-date")
			.on('change blur', function(e){
				nwInventory.cartItems.expiry_date = $(this).val();
			})
			.not("active")
			.datepicker({
				rtl: App.isRTL(),
				autoclose: true,
				format: 'yyyy-mm-dd',
			})
			.addClass("active");
			
			//nwCurrentStore.searchScope = "";
			//nwCurrentStore.activateSearchForm();
			
		},
		activateItemSelect: function(){
			$("#stocked-items")
			.find(".cart-item-select" )
			.on('click', function(e){
				e.preventDefault();
				
				if( $(this).attr("data-type") == "service" ){
					var data = {theme:'alert-info', err:'Services cannot be transfered', msg:'You have selected a service. Items of type "service" cannot be transfered or utilized', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
					return false;
				}
				
				var q = $(this).attr("data-max");
				
				var $b = $(this).find(".badge.quantity-select");
				var x = parseFloat( $b.text() ) * 1;
				if( isNaN(x) )x = 0;
				
				if( nwInventory.cartItems.item[ $(this).attr("data-id") ] && nwInventory.cartItems.item[ $(this).attr("data-id") ].quantity ){
					var x1 = parseFloat( nwInventory.cartItems.item[ $(this).attr("data-id") ].quantity );
					if( isNaN( x1 ) )x1 = 0;
					if( x1 > x )x = x1;
				}
					
				if( q > x ){
					++x;
				}else{
					var data = {theme:'alert-info', err:'Max. Quantity Reached', msg:'You have selected the maximum quantity for this item based on current stock level', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
				
				if( x > 0 ){
					var max = parseFloat( $(this).attr("data-max") * 1 );
					if( isNaN( max ) )max = 0;
					
					nwInventory.cartItems.item[ $(this).attr("data-id") ] = {
						id: $(this).attr("data-id"),
						desc: $(this).find(".item-title").text(),
						price: $(this).attr("data-cost_price"),
						selling_price: $(this).attr("data-selling_price"),
						expiry_date: "",
						quantity: x,
						quantity_instock: max,
						mode: "#recent-expenses",
						barcode: $(this).attr("data-barcode"),
						type: $(this).attr("data-type"),
					};
					nwInventory.refreshCart();
				}else{
					var data = {theme:'alert-info', err:'Out of Stock Item', msg:'Please restock this item first', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
				
			});
			
		},
		reLoad: function(){
			$("form#search-form").submit();
		},
		reClick: function(){
			$("#stocked-items")
			.find(".cart-item-select.active" )
			.click()
			.removeClass("active");
		},
		goToFirstStep: function(){
			
		},
		displayProductionSummary: function () {
			$('#production-summary-container').show();
			
			$('#production-view').hide();
			$("#stocked-items")
			.find(".cart-item" )
			.hide();
		},
		saveCartItemEdit: function(){
			var id = $("tr.active-edit").attr("id");
			var q = 0;
			
			if( id ){
				var m = nwInventory.cartItems.item[ id ].quantity_instock;
				q = $("tr.active-edit").find("input.quantity").val() * 1;
				if( isNaN( q ) )q = 0;
				if( q > m )q = m;
			}
			
			if( q ){
				nwInventory.cartItems.item[ id ].quantity = q;
				nwInventory.cartItems.item[ id ].total = nwInventory.cartItems.item[ id ].price * q;
			}
			nwInventory.refreshCart();
		},
		deleteCartItem: function(){
			var id = $("tr.active-edit").attr("id");
			
			if( id ){
				delete nwInventory.cartItems.item[ id ];
				$("#"+id+"-container.cart-item-select").find(".badge.quantity-select").text("");
			}
			
			nwInventory.refreshCart();
		},
		refreshCart: function () {
			var $t = $("#picked-items").find("tbody");
			var $tf = $("#picked-items").find("tfoot");
			
			$t.html('');
			var html = "";
			var htmlf = "";
			
			var tq = 0;
			var tt = 0;
			var serial = 0;
			
			$.each(nwInventory.cartItems.item, function(key, val){
				++serial;
				html += "<tr id='"+val.id+"' max='"+val.max+"'><td>"+serial+"</td><td>"+val.desc+"<br />#"+val.barcode+"</td><td class='r'>"+nwInventory.addComma( val.quantity_instock.toFixed(2) )+"</td><td class='r'>"+nwInventory.addComma( val.quantity )+"</td><td class='r'>"+nwInventory.addComma( (val.quantity_instock - val.quantity).toFixed(2) )+"</td></tr>";
				
				$("#stocked-items").find("#"+key+"-container.cart-item-select" ).find("span.b-c").html('<span class="badge quantity-select badge-roundless badge-success">'+val.quantity+'</span>');
				
				tq += val.quantity;
				tt += val.quantity_instock;
			});
			
			nwInventory.cartItems.quantity = tq;
				
			if( tq ){
				htmlf += "<tr><td colspan='5'>&nbsp;</td></tr><tr><th></th><th>TOTAL</th><th class='r'>"+nwInventory.addComma( tt.toFixed(2) )+"</th><th class='r'>"+nwInventory.addComma( tq )+"</th><th class='r'>"+nwInventory.addComma( ( tt - tq ).toFixed(2) )+"</th></tr>";
			}
			nwInventory.calculateTotal();
			
			$t.html( html );
			
			$t
			.find("tr")
			.on("click", function(){
				if( $(this).hasClass("active-edit") )return 1;
				
				if( $("tr.active-edit") ){
					$("tr.active-edit")
					.find("td:eq(1)")
					.html( $("tr.active-edit").attr("title") );
					
					$("tr.active-edit")
					.find("td:eq(3)")
					.html( $("tr.active-edit").attr("quantity") );
					
					$("tr.active-edit").removeClass( "active-edit" );
				}
				
				$(this).addClass("active-edit");
				
				var $new = $("#item-edit-template");
				
				var $first = $(this).find("td:eq(1)");
				$(this).attr("title", $first.text() );
				$first.html( $new.find("span.1").html() );
				
				var $sec = $(this).find("td:eq(3)");
				$(this).attr("quantity", $sec.text() );
				$sec.html( $new.find("span.3").html() );
				
				$sec.find("input").attr("max", $(this).attr("max") );
			});
			
			$tf.html( htmlf );
			
		},
		clearGoods:function(){
			$.each(nwInventory.cartItems.item, function(key, val){
				if( val.mode == nwInventory.cartItems.mode ){
					delete nwInventory.cartItems.item[key];
				}
			});
			nwInventory.refreshCart();
		},
		emptyCart: function(){
			
			$("#stocked-items")
			.find(".badge.quantity-select" )
			.html('');
			
			$('input[name="comment"]').val("");
			$("#expiry-date").val("");
			
			var mode = nwInventory.cartItems.mode;
			nwInventory.cartItems = {
				item:{},
				expenses:{},
				extra_cost:0,
				discount:0,
				total_cost:0,
				total_income:0,
				total_amount:0,
				quantity:0,
				quantity_materials:0,
				quantity_goods:0,
				expiry_date:"",
				mode: mode,
				reason: nwInventory_getReason(),
				stock_status: nwInventory_getStockStatus(),
				staff_responsible: nwInventory_getStaffResponsible(),
				factory: nwInventory_getFactory(),
				comment: "",
				store:nwCurrentStore.currentStore.id,
				reference: global_reference_id,
				reference_table: global_reference_table,
			};
			nwInventory.refreshCart();
			
		},
		specifyGoods: function(){
			nwInventory.cartItems.mode = "#recent-goods";
			nwInventory.emptyCart();
		},
		specifyGoodsButtonClick: function(){
			//nwInventory.specifyGoods();
			$('a[href="#recent-goods"]').click();
		},
		specifyMaterialsButtonClick: function(){
			$('a[href="#recent-expenses"]').click();
		},
		specifyExtraCostButtonClick: function(){
			$('a[href="#extra-cost"]').click();
		},
		specifyMaterials: function(){
			nwInventory.cartItems.mode = "#recent-expenses";
			nwInventory.emptyCart();
		},
		calculateTotal: function(){
			
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
		activateServerItemFilter: function(){
			$("select#item-filter")
			.on("change", function(e){
				e.preventDefault();
				
				$("form#search-form")
				.find('input[name="category_filter"]')
				.val( $(this).val() );
				
				nwInventory.submitSearchForm();
			});
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
			.on("keyup", function(e){
				
				switch(e.keyCode){
				case 46: case 17: case 16: case 35: case 36: case 40:
				case 38: case 37: case 39: case 32: case 8: case 27:
				case 13: break;
				default:
					if( nwInventory.debounceTimer ){
						clearTimeout( nwInventory.debounceTimer );
						nwInventory.debounceTimer = "";
					}
					
					if( $(this).val() && $(this).val().length > 1 ){
						nwInventory.debounceTimer = setTimeout( function(){ $("form#search-form").submit(); }, 5000 );
					}
				break;
				}
			});
			
			$("form#search-form")
			.on("submit", function(){
				if( nwInventory.debounceTimer ){
					clearTimeout( nwInventory.debounceTimer );
					nwInventory.debounceTimer = "";
				}
			});
			
			$("form#search-form")
			.find('input[name="search"]')
			.on("focus", function(){
				this.select();
			});
		},
		submitSearchForm: function(){
			nwInventory.debounceTimer = setTimeout( function(){ $("form#search-form").submit(); } , 300 );
		},
	};
	
}();

function nwInventory_getMode(){
	var $active = $("#stock-view")
	.find("ul.nav > li.active")
	.find("a");
	
	if( $active && $active.attr("href") ){
		return $active.attr("href");
	}
	return "#recent-expenses";
};

function nwInventory_getStockStatus(){
	if( $("input#production-status").is(":checked") ){
		return "complete";
	}else{
		return "in-progress";
	}
};

function nwInventory_getStaffResponsible(){
	if( $('select[name="staff_responsible"]') ){
		return $('select[name="staff_responsible"]').val();
	}else{
		return "";
	}
};

function nwInventory_getFactory(){
	if( $('select[name="factory"]') ){
		return $('select[name="factory"]').val();
	}else{
		return "";
	}
};

function nwInventory_getReason(){
	if( $('select[name="reason"]') ){
		return $('select[name="reason"]').val();
	}else{
		return "";
	}
};

nwInventory.init();

if( server ){
	nwInventory.activateServerSearchForm();
	nwInventory.activateServerItemFilter();
}else{
	nwInventory.activateItemFilter();
	nwCurrentStore.activateStockSearchForm();
}

var nwProduction = function () {
	return {
		init: function () {			
			nwInventory.init();
		},
		goToFirstStep: function(){
			nwInventory.goToFirstStep();
		},
		addExtraCost: function(){
			nwInventory.addExtraCost();
		},
		evaluateExtraCost: function(){
			nwInventory.evaluateExtraCost();
		},
		displayProductionSummary: function () {
			nwInventory.displayProductionSummary();
		},
		saveCartItemEdit: function(){
			nwInventory.saveCartItemEdit();
		},
		reClick: function(){
			nwInventory.reLoad();
		},
		deleteCartItem: function(){
			nwInventory.deleteCartItem();
		},
		refreshCart: function () {
			nwInventory.refreshCart();
		},
		clearGoods:function(){
			nwInventory.clearGoods();
		},
		emptyCart: function(){
			nwInventory.emptyCart();
		},
		specifyGoods: function(){
			nwInventory.specifyGoods();
		},
		specifyGoodsButtonClick: function(){
			nwInventory.specifyGoodsButtonClick();
		},
		specifyMaterialsButtonClick: function(){
			nwInventory.specifyMaterialsButtonClick();
		},
		specifyExtraCostButtonClick: function(){
			nwInventory.specifyExtraCostButtonClick();
		},
	};
	
}();