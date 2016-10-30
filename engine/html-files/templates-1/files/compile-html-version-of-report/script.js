$("iframe#iframe-container-of-content")
.on("load", function(){
	$(this)
	.contents().find("body")
	.attr("contenteditable", "true");
});