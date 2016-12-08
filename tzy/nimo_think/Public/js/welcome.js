function resize(){
	var margintop = ($(window).height() - $("#container").height())/2;
	$("#container").css("margin-top",margintop);
}
$(document).ready(function(){
	resize();
	$(window).resize(function() {
		resize();
	});
	$("#logout").click(function(){
		$.get("",{logout:1},function(r){
		window.location = 'index';
		},'json');
	});
});