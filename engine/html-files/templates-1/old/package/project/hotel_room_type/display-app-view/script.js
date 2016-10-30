var nwVendors = function () {
	return {
		recordItem: {
			id:"",
			type:"",
			name_of_vendor:"",
			address:"",
			phone:"",
			email:"",
			comment:"",
		},
		init: function () {			
			$('#expense-view')
			.find("tr.item-record")
			.not(".activated")
			.on('click', function(e){
				e.preventDefault();
				
				$('#expense-view')
				.find("tr.item-record")
				.removeClass("active");
				
				$(this).addClass("active");
				
				var $item = $(this);
				nwVendors.recordItem.id = $item.attr("id");
				nwVendors.recordItem.name_of_vendor = $item.attr("data-name_of_vendor");
				nwVendors.recordItem.address = $item.attr("data-address");
				nwVendors.recordItem.phone = $item.attr("data-phone");
				nwVendors.recordItem.email = $item.attr("data-email");
				nwVendors.recordItem.comment = $item.attr("data-comment");
				nwVendors.recordItem.type = $item.attr("data-type");
				
				$("form#vendors")
				.find(".custom-single-selected-record-button")
				.attr("override-selected-record", nwVendors.recordItem.id );
				
				nwVendors.edit();
				
			})
			.addClass("activated");
			
		},
		showRecentExpensesTab: function () {	
			//$('a[href="#recent-expenses"]').click();
		},
		reClick: function () {	
			$('#expense-view').find("tr.item-record.active").click();
		},
		edit: function () {	
			
			$.each( nwVendors.recordItem, function( key, val ){
				if( $("form#vendors").find('.form-control[name="'+key+'"]') ){
					$("form#vendors").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
		},
		emptyNewItem: function(){
			$('#expense-view')
			.find("tr.item-record")
			.removeClass("active");
			
			$("form#vendors")
			.find(".custom-single-selected-record-button")
			.attr("override-selected-record", "" );
			
			$("form#vendors").find(".form-control").val('');
			nwVendors.recordItem = {
				id:"",
				name_of_vendor:"",
				address:"",
				phone:"",
				email:"",
				comment:"",
			};
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
nwVendors.init();