var nwInventory = function () {
	return {
		inventoryItem: {
			item:"",
			cost_price:0,
			selling_price:0,
			quantity:0,
			source:"",
			description:"",
			type:"",
			weight_in_grams:0,
			sub_category:"",
			percentage_markup:0,
			sub_category:"",
			color_of_gold:"",
			length_of_chain:"",
			barcode:"",
			print_barcode:0,
			image:"",
			color_of_gold_text:"",
			category_text:"",
			currency:"",
			store:nwCurrentStore.currentStore.id,
		},
		defaultQuantity:quantity,
		defaultCostPerGram:cost_per_gram,
		defaultPercentageMarkup:percentage_markup,
		screenCapture:0,
		selectedItem:"",
		selectedItemID:"",
		selectedItemQuantity:0,
		init: function () {
			
			$('.form-control[name="store"]').val( nwCurrentStore.currentStore.id );
			
			nwInventory.activateItemSelect();
			nwInventory.setDefaultValues();
			
			$('a[href="#re-stock"]')
			.add('a[href="#supply-history"]')
			.on("click", function(){
				if( ! nwInventory.inventoryItem.item ){
					var data = {theme:'alert-info', err:'No Selected Record', msg:'Please select a record by clicking on it', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
					$('a[href="#new-stock"]').click();
					return false;
				}
				return true;
			});
			
			$('select.color_of_gold')
			.select2();
			
			$("input[type='date']")
			.not(".active")
			.datepicker({
				rtl: App.isRTL(),
				autoclose: true,
				format: 'yyyy-mm-dd',
			})
			.addClass("active");
			
			$('input#print-barcode-checkbox')
			.on('change', function(e){
				if( $(this).is(":checked") ){
					nwInventory.inventoryItem.print_barcode = 1;
				}else{
					nwInventory.inventoryItem.print_barcode = 0;
				}
				$('input[name="print_barcode"]').val( nwInventory.inventoryItem.print_barcode );
			}).change();
			
			$('input[name="weight_in_grams"]')
			.on("change", function(){
				nwInventory.inventoryItem.weight_in_grams = $(this).val() * 1;
				if( $(this).parents("form").find('input[name="cost_per_gram"]').val() ){
					$(this).parents("form").find('input[name="cost_per_gram"]').change();
				}else{
					if( $(this).parents("form").find('input[name="cost_price"]').val() ){
						$(this).parents("form").find('input[name="cost_price"]').change();
					}
				}
				
				//$('input[name="cost_price"]').change();
			});
			
			$('input[name="percentage_markup"]')
			.on("change", function(){
				var c = $(this).parents("form").find('input[name="cost_price"]').val() * 1;
				if( isNaN(c) )c = 0;
				
				var p = $(this).val() * 1;
				if( isNaN(p) )p = 0;
				
				if( ! p ){
					p = $(this).parents("form").find('.default-mark-up').attr( "data-value" ) * 1;
					if( isNaN(p) )p = 0;
				}
				
				if( p && c ){
					var sp = ( c * p / 100 ) + c;
					$(this).parents("form").find('input[name="selling_price"]').val( ( sp ).toFixed(2) );
					if( $('.selling-per-gram') && $('.selling-per-gram').is(":visible") ){
						var c = sp;
						if( nwInventory.inventoryItem.weight_in_grams && c ){
							var p = c / nwInventory.inventoryItem.weight_in_grams;
							$(this).parents("form").find('.selling-per-gram').text( nwInventory.addComma( p.toFixed(2) ) );
						}
					}
				}
			})
			.on("keyup", function(){ $(this).change(); });
			
			$('input[name="selling_price"]')
			.on("change", function(){
				if( $('input[name="percentage_markup"]') && $('input[name="percentage_markup"]').is(":visible") ){					
					var c = $(this).parents("form").find('input[name="cost_price"]').val() * 1;
					if( isNaN(c) )c = 0;
					
					var p = $(this).val() * 1;
					if( isNaN(p) )p = 0;
					
					if( p && c ){
						$(this).parents("form").find('input[name="percentage_markup"]').val( ( ( ( p - c ) / c ) * 100 ).toFixed(2) );
					}
				}
				
				if( $('.selling-per-gram') && $('.selling-per-gram').is(":visible") ){
					var c = $( this ).val() * 1;
					if( isNaN(c) )c = 0;
					
					if( nwInventory.inventoryItem.weight_in_grams && c ){
						var p = c / nwInventory.inventoryItem.weight_in_grams;
						$(this).parents("form").find('.selling-per-gram').text( nwInventory.addComma( p.toFixed(2) ) );
					}
				}
			}).on("keyup", function(){ $(this).change(); });
			
			$('input[name="cost_price"]')
			.on("change", function(){
				if( $('input[name="percentage_markup"]') && $('input[name="percentage_markup"]').is(":visible") ){					
					$(this).parents("form").find('input[name="percentage_markup"]').change();
				}
				if( $('input[name="cost_per_gram"]') && $('input[name="cost_per_gram"]').is(":visible") ){
					var c = $( this ).val() * 1;
					if( isNaN(c) )c = 0;
					
					if( nwInventory.inventoryItem.weight_in_grams && c ){
						var p = c / nwInventory.inventoryItem.weight_in_grams;
						$(this).parents("form").find('input[name="cost_per_gram"]').val( p.toFixed(2) );
					}
				}
			}).on("keyup", function(){ $(this).change(); });
			
			$('input[name="cost_per_gram"]')
			.on("change", function(){
				var c = $( this ).val() * 1;
				if( isNaN(c) )c = 0;
				
				if( nwInventory.inventoryItem.weight_in_grams && c ){
					var p = c * nwInventory.inventoryItem.weight_in_grams;
					$(this).parents("form").find('input[name="cost_price"]').val( p.toFixed(2) );
				}
				if( $('input[name="percentage_markup"]') && $('input[name="percentage_markup"]').is(":visible") ){					
					$(this).parents("form").find('input[name="percentage_markup"]').change();
				}
			}).on("keyup", function(){ $(this).change(); });
		},
		activateItemSelect: function(){
			
			$('#stocked-items')
			.find("a.item")
			.not(".activated")
			.on('click', function(e){
				e.preventDefault();
				
				$(this).siblings().removeClass("active");
				$(this).addClass("active");
				
				var $item = $(this);
				$.each( nwInventory.inventoryItem, function( k , v ){
					if( $item.attr( "data-"+k ) ){
						nwInventory.inventoryItem[k] = $item.attr( "data-"+k );
					}
				} );
				nwInventory.inventoryItem.item = $item.attr("data-id");
				nwInventory.inventoryItem.cost_price = $item.attr("data-cost");
				nwInventory.inventoryItem.selling_price = $item.attr("data-price");
				nwInventory.inventoryItem.image = $item.attr("data-image");
				
				nwInventory.inventoryItem.color_of_gold_text = $item.attr("data-color_of_gold-text");
				nwInventory.inventoryItem.category_text = $item.attr("data-category-text");
				
				nwInventory.inventoryItem.id = $item.attr("data-id");
				
				var s = $item.attr("data-type");
				switch( s ){
				case "produced_goods":
				case "service":
				case "crate_of_eggs":
					nwInventory.inventoryItem.quantity = 1;
					$(".not-raw-material").show();
					$(".not-service").hide();
				break;
				case "raw_materials":
					nwInventory.inventoryItem.quantity = 1;
					$(".not-service").show();
					$(".not-raw-material").hide();
				break;
				default:
					nwInventory.inventoryItem.quantity = 0;
					$(".not-service").show();
					$(".not-raw-material").show();
				break;
				}
				
				$('a[href="#supply-history"]')
				.add("#print-barcode-button")
				.attr( "override-selected-record", $item.attr("data-id") );
				
				nwInventory.inventoryItem.store = nwCurrentStore.currentStore.id;
				
				nwInventory.restockInventory();
				
				if( nwInventory.inventoryItem.barcode ){
					$("#barcode-container").show();
					$(".barcode-text").text( nwInventory.inventoryItem.barcode );
				}else{
					$("#barcode-container").hide();
				}
				
				$("tr.item-supply").hide();
				$("tr.item-supply-"+nwInventory.inventoryItem.item).show();
				
				$('input#print-barcode-checkbox').change();
			})
			.addClass("activated");
			
		},
		activateRecentSupplyItems: function () {
			$("tbody#recent-supply-items")
			.find("tr")
			.not(".cancelled")
			.on("click", function(e){
				e.preventDefault();
				
				if( $(this).hasClass("cancelled") ){
					$(this).removeClass("cancelled");
				}else{
					nwInventory.cancelDeleteStockItem();
										
					if( ! $(this).hasClass("active") ){
						nwInventory.selectedItemID = $(this).attr("id");
						nwInventory.selectedItemQuantity = $(this).attr("quantity") * 1;
						nwInventory.selectedItem = $(this).attr("data-item");
						
						$(this).addClass("active");
						
						var $td = $(this).find("td:eq(0)");
						$td.attr( "default", $td.html() );
						$td.html( $("#delete-button-holder").html() );
					}
				}
			});
		},
		cancelDeleteStockItem: function () {
			
			var $otd = $("tbody#recent-supply-items").find("tr.active").find("td:eq(0)");
			$otd.html( $otd.attr("default") );
			
			$("tbody#recent-supply-items").find("tr.active").removeClass( "active" ).addClass("cancelled");
			
		},
		updateStockLevels: function(){
			
			if( nwInventory.selectedItemQuantity ){
				var max = $( "#" + nwInventory.selectedItem + "-container" ).attr( "data-max" ) * 1;
				var txt = $( "#" + nwInventory.selectedItem + "-container" ).find(".stock-levels").text() * 1;
				
				$( "#" + nwInventory.selectedItem + "-container" ).attr( "data-max" , max - nwInventory.selectedItemQuantity );
				$( "#" + nwInventory.selectedItem + "-container" ).find(".stock-levels").text( txt - nwInventory.selectedItemQuantity );
				
			}
			
			nwInventory.selectedItem = "";
			nwInventory.selectedItemID = "";
			nwInventory.selectedItemQuantity = 0;
		},
		deleteStockItem: function () {
			$("#actual-delete-inventory")
			.attr( "override-selected-record" , nwInventory.selectedItemID )
			.click();
		},
		reClick: function () {	
			$('#stocked-items').find("a.active").click();
		},
		restockInventory: function () {
			$('a[href="#re-stock"]').click();
			
			$.each( nwInventory.inventoryItem, function( key, val ){
				if( $("form#inventory").find('.form-control[name="'+key+'"]') ){
					$("form#inventory").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
			$('input[name="cost_price"]')
			.add('input[name="selling_price"]')
			.change();
			
			$('input[name="quantity"]').attr("max", $( "#" + nwInventory.selectedItem + "-container" ).attr( "data-max" ) );
			$("#inventory-image").attr("src", $("#"+nwInventory.inventoryItem.item+"-image").attr("src") );
			$("#inventory-title").text( $("#"+nwInventory.inventoryItem.item+"-title").text() );
			
			$("#inventory-barcode").text( nwInventory.inventoryItem.barcode );
			$("#inventory-color").text( nwInventory.inventoryItem.color_of_gold_text );
			$("#inventory-category").text( nwInventory.inventoryItem.category_text );
			$("#inventory-weight_in_grams").text( nwInventory.inventoryItem.weight_in_grams + 'g' );
		},
		clearID: function () {	
			$("form#items").find('input[name="id"]').val("");
			$("form#items").find('input[name="image"]').val( nwInventory.inventoryItem.image );
			$('#stocked-items').find("a.active").removeClass("active");
			
			nwInventory.setDefaultValues();
		},
		editSelectedItem: function () {	
			$("#stock-view")
			.find("ul.nav > li")
			.removeClass("active");
			
			$("#stock-view")
			.find(".tab-content > .tab-pane")
			.removeClass("active");
			
			$("#stock-view")
			.find("#new-stock")
			.addClass("active");
			
			$.each( nwInventory.inventoryItem, function( key, val ){
				if( $("form#items").find('.form-control[name="'+key+'"]') ){
					/*
					switch( key ){
					case "color_of_gold":
						if( val ){
							var m = val.replace(":::", ",");
							$("form#items").find('.form-control.color_of_gold').val( m );
						}else{
							$("form#items").find('.form-control.color_of_gold').val( "" );
						}
					break;
					default:
						$("form#items").find('.form-control[name="'+key+'"]').val( val );
					break;
					}
					*/
					$("form#items").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
			$('select[name="color_of_gold"]').select2( "destroy" ).select2();
			
			$("#image-img").attr( "src", $("#"+nwInventory.inventoryItem.item+"-image").attr("src") ).show();
		},
		showRestockTabforNewItem: function(){
			nwInventory.emptyNewItemForEdit();
			
			$('a[href="#supply-history"]')
			.attr( "override-selected-record", "" );
			
			$('#supply-history').html('');
			
			nwInventory.inventoryItem = {
				item:"",
				cost_price:0,
				selling_price:0,
				quantity:0,
				source:"",
				description:"",
				type:"",
				weight_in_grams:0,
				sub_category:"",
				percentage_markup:0,
				sub_category:"",
				color_of_gold:"",
				length_of_chain:"",
				barcode:"",
				print_barcode:0,
				image:"",
				color_of_gold_text:"",
				category_text:"",
				currency:"",
				store:nwCurrentStore.currentStore.id,
			};
			
		},
		emptyNewItemForEdit: function(){
			$("form#items").find("input.form-control").val('');
			$("form#inventory").find("input.form-control").val('');
			
			$("#image-img").hide();
			$("input.image-replace").val('');
			
			$('.form-control[name="store"]').val( nwCurrentStore.currentStore.id );
		},
		emptyNewItem: function(){
			$('#stocked-items')
			.find("a.item")
			.removeClass("active");
			
			nwInventory.showRestockTabforNewItem();
			nwInventory.setDefaultValues();
		},
		setDefaultValues: function(){
			if( nwInventory.defaultCostPerGram ){
				$("form#items")
				.find('input[name="cost_per_gram"]')
				.val( nwInventory.defaultCostPerGram );
			}
			
			if( nwInventory.defaultPercentageMarkup ){
				$("form#items")
				.find('input[name="percentage_markup"]')
				.val( nwInventory.defaultPercentageMarkup );
			}
			
			if( nwInventory.defaultQuantity ){
				$("form#items")
				.find('input[name="quantity"]')
				.val( nwInventory.defaultQuantity );
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
		openImageCapture: function(){
			$("#capture-image-button").hide();
			$("#close-image-capture")
			.text( "Close" )
			.attr( "disabled", false );
		},
		closeImageCapture: function(){
			
			$("#close-image-capture")
			.text( "Processing..." )
			.attr( "disabled", true );
			
			var img = $("#capture-container").find("iframe").contents().find('input[name="image"]').val();
			
			if( img ){
				$("body").data("json", img );
				$("#save-captured-image")
				.attr("override-selected-record", "json" )
				.click();
			}else{
				var data = {theme:'alert-info', err:'No Captured Image', msg:'No image was captured, to capture an image click on the SNAP PHOTO button before you close the capture screen', typ:'jsuerror' };
				$.fn.cProcessForm.display_notification( data );
				nwInventory.saveCapturedImage();
			}
		},
		saveCapturedImage: function(){
			if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.stored_path && $.fn.cProcessForm.returned_ajax_data.full_path ){
				var element = "image";
				$('input[name="'+ element +'"]').val( $.fn.cProcessForm.returned_ajax_data.stored_path );
				$('img#'+ element +'-img')
				.attr( "src", $.fn.cProcessForm.returned_ajax_data.full_path )
				.slideDown(1000 , function(){
					$('.qq-upload-success').empty();
				});
			}
			$("#capture-container").html("");
			$("#capture-image-button").show();
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
						nwInventory.debounceTimer = setTimeout( function(){ $("form#search-form").submit(); }, 1200 );
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
nwInventory.init();
if( server ){
	nwInventory.activateServerSearchForm();
	nwInventory.activateServerItemFilter();
}else{
	nwInventory.activateItemFilter();
	nwCurrentStore.activateStockSearchForm();
}