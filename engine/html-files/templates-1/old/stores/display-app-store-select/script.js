var nwCurrentStore = function () {
	return {
		currentStore: {
			id:"",
			name:"",
			address:"",
			phone:"",
			email:"",
			comment:"",
		},
		firstLoad: 0,
		searchScope: ".raw_materials",	//produced_goods, raw_materials, service
		init: function () {			
			$('#current-store-container')
			.find("select")
			.not(".activated")
			.on('change', function(e){
				e.preventDefault();
				
				var $item = $(this);
				nwCurrentStore.currentStore.id = $item.val();
				
				var file = $item.find('option[value="'+nwCurrentStore.currentStore.id+'"]').attr("data-file");
				var loc = document.location.href;
				
				if( loc && file ){
					if( loc.search( file ) < 0 ) {
						nwCurrentStore.firstLoad = 1;
					}
				}
				
				if( nwCurrentStore.firstLoad ){
					$("#current-store-button")
					.attr("override-selected-record", nwCurrentStore.currentStore.id )
					.click();
				}
				nwCurrentStore.firstLoad = 1;
			})
			.addClass("activated")
			.change();
		},
		clearSearchForm: function(){
			$("form#search-form")
			.find("input")
			.val("");
		},
		activateSearchForm: function(){
			$("form#search-form")
			.on("submit", function(e){
				e.preventDefault();
				return false;
			});
			
			$("form#search-form")
			.find("input")
			.on("change", function(){
				$(this).keyup();
			})
			.on("keyup", function(){
				if( $(this).val().length > 1 ){
					
					var v = $(this).val();
					var show = 0;
					var vint = parseInt( v );
					
					if( ! isNaN( vint ) && $("#stocked-items").find( ".barcode-" + vint ) ){
						$("#stocked-items").find(".cart-item-select").hide();
						$("#stocked-items").find( ".barcode-" + vint ).show();
					}else{
						$("#stocked-items")
						.find(".cart-item-select")
						.not( nwCurrentStore.searchScope )
						.each( function(){
							var t = new RegExp( v, "i");
							var vl = $(this).find(".item-title").text();
							
							if( ( t.test(vl) ) ){
								$(this).show();
								show = 1;
							}else{
								$(this).hide();
							}
						});
					}
					
					if( show ){
						$(".active-category-box").hide();
						$(".go-back").show();
					}else{
						$(".active-category-box").show();
						$(".go-back").hide();
					}
				}
			});
		},
		showAllItems: function(){
			$(".cart-item-select").show();
		},
		activateStockSearchForm: function(){
			$("form#search-form")
			.on("submit", function(e){
				e.preventDefault();
				return false;
			});
			
			$("form#search-form")
			.find("input")
			.on("change", function(){
				$(this).keyup();
			})
			.on("keyup", function(){
				if( $(this).val().length > 1 ){
					
					var v = $(this).val();
					var show = 0;
					var vint = parseInt( v );
					
					if( ! isNaN( vint ) && $("#stocked-items").find( ".barcode-" + vint ) ){
						$("#stocked-items").find(".cart-item-select").hide();
						$("#stocked-items").find( ".barcode-" + vint ).show();
					}else{					
						$("#stocked-items")
						.find(".cart-item-select")
						.each( function(){
							var t = new RegExp( v, "i");
							var vl = $(this).find(".item-title").text();
							
							if( ( t.test(vl) ) ){
								$(this).show();
								show = 1;
							}else{
								$(this).hide();
							}
						});	
					}
				}else{
					$("#stocked-items").find(".cart-item-select").show();
				}
			});
		},
	};
}();
nwCurrentStore.init();