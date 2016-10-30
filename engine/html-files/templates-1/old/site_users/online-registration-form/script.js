setTimeout( function(){
var total = 0;
var complete = 0;
$(".registration-status").each(function(){
	++total;
	if( ! $(this).hasClass("icon-warning-sign") ){
		++complete;
	}
});

var html = '<i class="icon-warning-sign"></i> '+complete+' out of '+total+' Complete';
if( total == complete ){
	html = '<i class="icon-check"></i> '+complete+' out of '+total+' Complete';
}

$("#registration-status-holder")
.html( '<span style="color:#cc3c3b;">' + html + '</span>' );

}, 1000);