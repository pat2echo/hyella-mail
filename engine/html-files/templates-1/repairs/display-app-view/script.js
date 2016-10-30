var nwRepairs = function () {
	return {
		recordItem: {
			id:"",
			description:"",
			customer:"",
			amount_due:0,
			amount_paid:0,
			comment:"",
			date:"",
			image:"",
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
				nwRepairs.recordItem.id = $item.attr("id");
				nwRepairs.recordItem.description = $item.attr("data-description");
				nwRepairs.recordItem.customer = $item.attr("data-customer");
				nwRepairs.recordItem.amount_due = $item.attr("data-amount_due");
				nwRepairs.recordItem.amount_paid = $item.attr("data-amount_paid");
				nwRepairs.recordItem.comment = $item.attr("data-comment");
				nwRepairs.recordItem.date = $item.attr("data-date");
				
				$("form#repairs")
				.find(".custom-single-selected-record-button")
				.attr("override-selected-record", nwRepairs.recordItem.id );
				
				nwRepairs.edit();
				
			})
			.addClass("activated");
			
		},
		showRecentExpensesTab: function () {	
			//$('a[href="#recent-expenses"]').click();
		},
		reClick: function () {
			$('#expense-view').find("tr.item-record.active").click();
		},
		activateDate: function () {
			$("input[type='date']")
			.not(".active")
			.datepicker({
				rtl: App.isRTL(),
				autoclose: true,
				format: 'yyyy-mm-dd',
			})
			.addClass("active");
		},
		edit: function () {	
			
			$.each( nwRepairs.recordItem, function( key, val ){
				if( $("form#repairs").find('.form-control[name="'+key+'"]') ){
					$("form#repairs").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
			$("#new-repair-job-container").hide();
			$("#view-repair-job-container").show();
			
		},
		editItem: function(){
			$("#new-repair-job-container").show();
			$("#view-repair-job-container").hide();
		},
		showNewItem: function(){
			nwRepairs.emptyNewItem();
			$("#new-repair-job-container").show();
			$("#view-repair-job-container").hide();
		},
		emptyNewItem: function(){
			$('#expense-view')
			.find("tr.item-record")
			.removeClass("active");
			
			$("form#repairs")
			.find(".custom-single-selected-record-button")
			.attr("override-selected-record", "" );
			
			$("form#repairs").find(".form-control").val('');
			nwRepairs.recordItem = {
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
				nwRepairs.saveCapturedImage();
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
	};
	
}();
nwRepairs.init();