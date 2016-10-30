var nwNewsletter_message = function () {
	return {
		cartItems: {
			recipients:"",
			sending_channel:"",
			message:g_message,
			password:"",
			username:"",
		},
		init: function () {
				
			$("#send-finish")
			.on("click", function(){
				$("body").data("json", nwNewsletter_message.cartItems );
				$("#cart-finish").attr("override-selected-record", "json" ).click();
			});
			
			$('select[name="recipients"]')
			.on('change', function(e){
				nwNewsletter_message.cartItems.recipients = $(this).val();
				
				$("#view-recipients-info")
				.attr("override-selected-record", nwNewsletter_message.cartItems.recipients );
				
			}).change();
			
			$('select[name="sending_channel"]')
			.on('change', function(e){
				nwNewsletter_message.cartItems.sending_channel = $(this).val();
			}).change();
			
			$('input[name="username"]')
			.on('blur', function(e){
				nwNewsletter_message.cartItems.username = $(this).val();
			}).blur();
			
			$('input[name="password"]')
			.on('blur', function(e){
				nwNewsletter_message.cartItems.password = $(this).val();
			}).blur();
			
			$("#cart-cancel")
			.on("click", function(){
				nwNewsletter_message.cartItems = {
					recipients:"",
					sending_channel:"",
					message:g_message,
					password:"",
					username:"",
				};
			});
			
		},
		checkStatus: function(){
			$("#check-all-status").click();
		},
		timerId: "",
		delayCheckStatus: function(){
			if( nwNewsletter_message.timerId ){
				clearTimeout( nwNewsletter_message.timerId );
			}
			nwNewsletter_message.timerId = setTimeout( function(){ nwNewsletter_message.checkStatus() }, 20000 );
		},
	};
	
}();

nwNewsletter_message.init();