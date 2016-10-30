var nwNewsletter_message = function () {
	return {
		cartItems: {
			id:g_message_id,
			message:"",
		},
		init: function () {
			nwNewsletter_message.tinyMCEInline();
			
			$("#send-finish")
			.on("click", function(){
				nwNewsletter_message.cartItems.message = $('div.editable').html();
				
				$("body").data("json", nwNewsletter_message.cartItems );
				$("#cart-finish").attr("override-selected-record", "json" ).click();
			});
		},
		tinyMCEInline: function(){
			tinymce.init({
			  selector: 'div.editable',
			  inline: true,
			  plugins: [
				'advlist autolink lists link image charmap print preview anchor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime media table textcolor contextmenu paste'
			  ],
			  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
			});
		},
	};
	
}();

nwNewsletter_message.init();