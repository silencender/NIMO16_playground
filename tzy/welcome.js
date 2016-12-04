function resize(){
	var margintop = ($(window).height() - $("#container").height())/2;
	$("#container").css("margin-top",margintop);
}
$(document).ready(function(){
	resize();
	$(window).resize(function() {
		resize();
	});
	$.ajax({
		type: "POST",
		url: 'welcome.php',
		data: {
			query: 'name'
		},
		datatype: "json",
		success: function(r) {
			r = $.parseJSON(r);
			if(r.status){
				$("#welcome").append(r.data['name']);
			}
			else{
				$("#welcome").html("<a href='login.html'>Sorry,please login first</a>");
			}
		},
		error: function() {
			$("#welcome").text('Server Error');
		}
	});
	$("#logout").click(function(){
		$.get("welcome.php",{logout:1},function(r){
		window.location = 'login.html';
		},'json');
	});
});