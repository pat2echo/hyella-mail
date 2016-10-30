$("#dynamic")
.on("click", "a.view-file-link-1", function(e){
	e.preventDefault();
	
	$("iframe#iframe-container-of-content")
	.attr("src", $(this).attr("href") );
});

$("iframe#iframe-container-of-content")
.contents().find("body").html( $("#iframe-container-of-content-preview").html() );