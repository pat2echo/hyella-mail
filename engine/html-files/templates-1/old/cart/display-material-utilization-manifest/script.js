var nwProduction = function () {
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
			mode: "#recent-expenses",
			stock_status: nwProduction_getStockStatus(),
			staff_responsible: nwProduction_getStaffResponsible(),
			factory: nwProduction_getFactory(),
			comment: "",
			store:nwCurrentStore.currentStore.id,
		},
		init: function () {			
			$('#cart-category')
			.find(".category")
			.on('click', function(e){
				e.preventDefault();
				
				$("#stocked-items")
				.find("." + $(this).attr("id") )
				.show();
				
				$("#stocked-items")
				.find(".go-back" )
				.show();
				
				$('#cart-category').hide();
			});
			
			$("#stocked-items")
			.find(".go-back" )
			.on('click', function(e){
				e.preventDefault();
				
				$('#cart-category').show();
				$('.active-category-box').show();
				
				$("#stocked-items")
				.find(".cart-item" )
				.hide();
			});
			
			$("#stocked-items")
			.find(".cart-item-select" )
			.on('click', function(e){
				e.preventDefault();
				
				var q = $(this).attr("data-max");
				
				var $b = $(this).find(".badge");
				var x = parseFloat( $b.text() ) * 1;
				if( isNaN(x) )x = 0;
				
				if( q > x ){
					++x;
				}else{
					var data = {theme:'alert-info', err:'Max. Quantity Reached', msg:'You have selected the maximum quantity for this item based on current stock level', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
				
				if( x > 0 ){
					
					var p = parseFloat( $(this).attr("data-cost") ) * 1;
					var sp = parseFloat( $(this).attr("data-price") ) * 1;
					
					nwProduction.cartItems.item[ $(this).attr("id") ] = {
						id: $(this).attr("id"),
						desc: $(this).find(".item-title").text(),
						price: p,
						selling_price: sp,
						quantity: x,
						total: x * p,
						max: $(this).attr("data-max"),
						mode: nwProduction.cartItems.mode,
						type: $(this).attr("data-type"),
					};
					nwProduction.refreshCart();
				}else{
					var data = {theme:'alert-info', err:'Out of Stock Item', msg:'Please restock this item first', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
				
			});
			
			$("#cart-finish")
			.on("click", function(){
				$("body").data("json", nwProduction.cartItems );
				$(this).attr("override-selected-record", "json" );
			});
			
			$('select[name="staff_responsible"]')
			.on('change', function(e){
				nwProduction.cartItems.staff_responsible = $(this).val();
			});
			
			$('select[name="factory"]')
			.on('change', function(e){
				nwProduction.cartItems.factory = $(this).val();
			});
			
			$('input[name="comment"]')
			.on('blur', function(e){
				nwProduction.cartItems.comment = $(this).val();
			});
			
			$("#expiry-date")
			.on('change blur', function(e){
				nwProduction.cartItems.expiry_date = $(this).val();
			})
			.not("active")
			.datepicker({
				rtl: App.isRTL(),
				autoclose: true,
				format: 'yyyy-mm-dd',
			})
			.addClass("active");
			
			$("#production-status")
			.on('change', function(e){
				if( $(this).is(":checked") ){
					nwProduction.cartItems.stock_status = 'complete';
				}else{
					nwProduction.cartItems.stock_status = "in-progress";
				}
			});
			
			nwCurrentStore.searchScope = "";
			nwCurrentStore.activateSearchForm();
			
		},
		goToFirstStep: function(){
			
		},
		addExtraCost: function(){
			var e = {};
			var err = 0;
			$("#new-extra-cost")
			.find('.form-control')
			.each(function(){
				if( ! $(this).val() )err = 1;
				e[ $(this).attr("name") ] = $(this).val();
			});
			
			if( err ){
				var data = {theme:'alert-danger', err:'Missing Info', msg:'Please ensure you provide info in all the fields', typ:'jsuerror' };
				$.fn.cProcessForm.display_notification( data );
			}else{
				var d = new Date();
				var n = d.getTime();
				
				nwProduction.cartItems.expenses[n] = e;
				nwProduction.evaluateExtraCost();
				
				$("#new-extra-cost").find('input.form-control').val('');
			}
			
		},
		evaluateExtraCost: function(){
			nwProduction.cartItems.extra_cost = 0;
			var html = "";
			
			$.each( nwProduction.cartItems.expenses, function( k , v ){
				html += '<tr id="extra-cost-'+k+'"><td><button data-id="'+k+'" type="button" class="btn btn-sm dark remove-cost"><i class="icon-remove"></i> </button></td><td>'+v.description+'</td><td>'+( v.category_of_expense )+'</td><td class="r">'+( nwProduction.addComma( (v.amount_paid * 1).toFixed(2) ) )+'</td></tr>';
				
				nwProduction.cartItems.extra_cost += ( v.amount_paid * 1 );
			} );
			
			nwProduction.calculateTotal();
			
			$("tbody#extra-cost-table")
			.html( html );
			
			$("tbody#extra-cost-table")
			.find("button.remove-cost")
			.on("click", function(){
				var id = $(this).attr("data-id");
				delete nwProduction.cartItems.expenses[id];
				nwProduction.evaluateExtraCost();
			});
			
			if( nwProduction.cartItems.extra_cost ){
				html = '<tr><td colspan="4">&nbsp;</td></tr>';
				html += '<tr><th>&nbsp;</th><th>&nbsp;</th><th>EXTRA COST</th><th class="r total-extra-cost">'+( nwProduction.addComma( (nwProduction.cartItems.extra_cost).toFixed(2) ) )+'</th></tr>';
			}
			
			$("tfoot#total-extra-cost").html( html );
			
		},
		displayProductionSummary: function () {
			$('#production-summary-container').show();
			
			$('#production-view').hide();
			$('#cart-category').hide();
			$("#stocked-items")
			.find(".cart-item" )
			.hide();
		},
		saveCartItemEdit: function(){
			var id = $("tr.active-edit").attr("id");
			var q = 0;
			
			if( id ){
				var m = $("tr.active-edit").find("input.quantity").attr("max") * 1;
				q = $("tr.active-edit").find("input.quantity").val() * 1;
				if( isNaN( q ) )q = 0;
				
				if( nwProduction.cartItems.mode != "#recent-goods" ){
					if( q > m )q = m;
				}
			}
			
			if( q ){
				nwProduction.cartItems.item[ id ].quantity = q;
				nwProduction.cartItems.item[ id ].total = nwProduction.cartItems.item[ id ].price * q;
			}
			nwProduction.refreshCart();
		},
		deleteCartItem: function(){
			var id = $("tr.active-edit").attr("id");
			
			if( id ){
				delete nwProduction.cartItems.item[ id ];
				$("#"+id+".cart-item-select").find(".badge").text("");
			}
			
			nwProduction.refreshCart();
		},
		refreshCart: function () {
			var $t = $(nwProduction.cartItems.mode).find(".shopping-cart-table").find("tbody");
			var $tf = $(nwProduction.cartItems.mode).find(".shopping-cart-table").find("tfoot");
			
			$t.html('');
			$(nwProduction.cartItems.mode).find("#discount-container").hide();
			
			var html = "";
			var htmlf = "";
			
			var tq = 0;
			var tt = 0;
			
			$.each(nwProduction.cartItems.item, function(key, val){
				if( val.mode == nwProduction.cartItems.mode ){
					html += "<tr id='"+val.id+"' max='"+val.max+"'><td>"+val.desc+"</td><td class='r'>"+ nwProduction.addComma( val.price.toFixed(2) ) +"</td><td class='r'>"+nwProduction.addComma( val.quantity )+"</td><td class='r'>"+ nwProduction.addComma( val.total.toFixed(2) ) +"</td></tr>";
					
					$("#stocked-items").find("#"+key+".cart-item-select" ).find("span.b-c").html('<span class="badge badge-success">'+val.quantity+'</span>');
					
					tq += val.quantity;
					tt += val.total;
				}
			});
			
			nwProduction.cartItems.quantity = tq;
			nwProduction.cartItems.total_amount = tt;
			
			switch( nwProduction.cartItems.mode ){
			case "#recent-goods":
				totalLabel = "TOTAL GOODS PRODUCED";
				nwProduction.cartItems.total_income = tt;
				nwProduction.cartItems.quantity_goods = nwProduction.cartItems.quantity;
			break;
			default:
				var totalLabel = "TOTAL MATERIALS";
				nwProduction.cartItems.total_cost = tt;
				nwProduction.cartItems.quantity_materials = nwProduction.cartItems.quantity;
			break;
			}
				
			if( tq ){
				htmlf += "<tr><td colspan='4'>&nbsp;</td></tr><tr><th class='r' colspan='2'>"+totalLabel+"</th><th class='r'>"+nwProduction.addComma( tq )+"</th><th class='r'>"+ nwProduction.addComma( tt.toFixed(2) ) +"</th></tr>";
				
				$("#discount-container").show();
			}
			nwProduction.calculateTotal();
			
			$t.html( html );
			
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
				
				if( nwProduction.cartItems.mode != "#recent-goods" )
					$sec.find("input").attr("max", $(this).attr("max") );
			});
			
			//update cost price
			if( nwProduction.cartItems.mode == "#recent-goods" && nwProduction.cartItems.total_cost && nwProduction.cartItems.quantity_goods ){
				var tc = ( nwProduction.cartItems.total_cost + nwProduction.cartItems.extra_cost ) / nwProduction.cartItems.quantity_goods;
				htmlf += "<tr><th class='r' colspan='2'>COST PER GOODS PRODUCED</th><th colspan='2' class='r'>"+ nwProduction.addComma( tc.toFixed(2) ) +"</th></tr>";
			}
			
			$tf.html( htmlf );
			
		},
  clearGoods:function(){
			$.each(nwProduction.cartItems.item, function(key, val){
				if( val.mode == nwProduction.cartItems.mode ){
					delete nwProduction.cartItems.item[key];
				}
			});
			nwProduction.refreshCart();
		},
		emptyCart: function(){
			$("tbody#extra-cost-table")
			.add("tfoot#total-extra-cost")
			.html( '' );
			
			$('#production-summary-container').hide();		
			$('#production-view').show();
			$('#cart-category').show();
			$("#stocked-items")
			.find(".cart-item" )
			.hide();
			
			$('#cart-category-products').hide();
			$('#cart-category-materials').show();
			
			$("#stocked-items")
			.find(".badge" )
			.html('');
			
			$('input[name="comment"]').val("");
			$("#expiry-date").val("");
			
			nwProduction.cartItems = {
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
				mode: "#recent-goods",
				stock_status: nwProduction_getStockStatus(),
				staff_responsible: nwProduction_getStaffResponsible(),
				factory: nwProduction_getFactory(),
				comment: "",
				store:nwCurrentStore.currentStore.id,
			};
			nwProduction.refreshCart();
			
			nwProduction.cartItems.mode = "#recent-expenses";
			nwProduction.refreshCart();
			nwProduction.specifyMaterialsButtonClick();
		},
		specifyGoods: function(){
			nwProduction.cartItems.mode = "#recent-goods";
			
			nwCurrentStore.searchScope = ".raw_materials";
			
			$('#cart-checkout-container').hide();				
			$('#cart-category').show();
			
			$(".active-category-box").removeClass("active-category-box");
			
			$('#cart-category-products')
			.addClass("active-category-box")
			.show();
			
			$('#cart-category-materials').hide();
			
			$("#stocked-items")
			.find(".cart-item" )
			.hide();
			
			nwProduction.refreshCart();
		},
		specifyGoodsButtonClick: function(){
			//nwProduction.specifyGoods();
			$('a[href="#recent-goods"]').click();
		},
		specifyMaterialsButtonClick: function(){
			$('a[href="#recent-expenses"]').click();
		},
		specifyExtraCostButtonClick: function(){
			$('a[href="#extra-cost"]').click();
		},
		specifyMaterials: function(){
			nwProduction.cartItems.mode = "#recent-expenses";
			nwCurrentStore.searchScope = ".produced_goods, .service";
			
			$(".active-category-box").removeClass("active-category-box");
			
			$('#production-summary-container').hide();	
			$('#cart-category').show();
			
			$('#cart-category-products').hide();
			
			$('#cart-category-materials')
			.addClass("active-category-box")
			.show();
			
			$("#stocked-items")
			.find(".cart-item" )
			.hide();
		},
		calculateTotal: function(){
			var e = 0;
			var d = 0;
			var t = 0;
			var i = 0;
			var a = 0;
			
			/* total irrespective of materials / goods */
			if( nwProduction.cartItems.total_amount )a = nwProduction.cartItems.total_amount;
			
			if( nwProduction.cartItems.total_income )i = nwProduction.cartItems.total_income;
			if( nwProduction.cartItems.total_cost )t = nwProduction.cartItems.total_cost;
			
			if( nwProduction.cartItems.discount )d = nwProduction.cartItems.discount;
			if( nwProduction.cartItems.extra_cost )e = nwProduction.cartItems.extra_cost;
			
			var tt = ( a + e ) - d;
			var margin = i - ( ( t + e ) - d );
			var gp = 0;
			if( i > 0 )
				gp = margin * 100 / i;
			
			switch( nwProduction.cartItems.mode ){
			case "#recent-goods":
				tt = i;
			break;
			}
			
			$(nwProduction.cartItems.mode).find(".total-amount").text( nwProduction.addComma( tt.toFixed(2) ) );
			$(".profit-margin").text( nwProduction.addComma( margin.toFixed(2) ) + " ( "+ gp.toFixed(1) +"% )" );
			
			$(".cost-of-production").text( nwProduction.addComma( ( ( t + e ) - d ).toFixed(2) ) );
			$(".estimated-revenue").text( nwProduction.addComma( i.toFixed(2) ) );
			
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

function nwProduction_getStockStatus(){
	if( $("input#production-status").is(":checked") ){
		return "complete";
	}else{
		return "in-progress";
	}
};

function nwProduction_getStaffResponsible(){
	if( $('select[name="staff_responsible"]') ){
		return $('select[name="staff_responsible"]').val();
	}else{
		return "";
	}
};

function nwProduction_getFactory(){
	if( $('select[name="factory"]') ){
		return $('select[name="factory"]').val();
	}else{
		return "";
	}
};

nwProduction.init();