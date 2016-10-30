var nwFilesRename = function () {
	
    return {
        //main function to initiate the module
        init: function () {
			
			var record_id = $("#rename-selected-record").attr("value");
			var $td = $("table.display").find("#"+record_id+"-files002").parent("td");
			
			if( ! ( $td && $td.offset() && $td.offset().top ) ){
				setTimeout( function(){ nwFilesRename.init(); }, 1000 );
				return false;
			}
			
			$("#files002")
			.css({
				zIndex:900000,
				position:"fixed",
				top: $td.offset().top,
				left: $td.offset().left,
				width: $td.outerWidth(),
				height: $td.outerHeight(),
				fontSize:'11px',
				color:"#000",
			});
			setTimeout( function(){ 
				$("#files002")
				.on("blur", function(){
					if( $(this).data("submitting") )return false;
					
					$(this).data("submitting", 1 );
					$(this).parents("form").submit();
					
				})
				.select(); 
				
			}, 500 );
			
        },
		destroy: function () {
			$("#hidden-main-table-view")
			.empty();
        }

    };

}();