function reset() {
	$(".warning").hide("fast");
}

function toggle() {
	$("#form_email,#save").toggle("fast");
}

function signin() {
	reset();
	toggle();
	$("title").text("Sign in");
	$("input[name='method']").val("signin");
	$("#form").find("label[for='user']").text("Username\\Email");
	$("#user").attr("placeholder", "Name or Email");
	$("#head").html('Sign in<a href="javascript:void(0)" onclick="signup()" style="font-size: 0.6em">/up</a>');
}

function signup() {
	reset();
	toggle();
	$("title").text("Sign up");
	$("input[name='method']").val("signup");
	$("label[for='user']").text("Username");
	$("#user").attr("placeholder", "Your name");
	$("#head").html('Sign up<a href="javascript:void(0)" onclick="signin()" style="font-size: 0.6em">/in</a>');
}

function encrypt(callback) {
	reset();
	$.ajax({
		type: "POST",
		url: '',
		data: {
			query: 'time'
		},
		datatype: "json",
		success: function(r) {
			//r = $.parseJSON(r);
			var timestamp = r;
			console.log(r);
			$("input[name='time']").val(timestamp);
			var encrypted = md5(md5($("#pwd").val()) + timestamp);
			$("#pwd").val(encrypted);
			if(callback) callback();
		},
		error: function() {
			$("#server_error").show("fast");
		}
	});
}
function resize(){
	var margintop = ($(window).height() - $("#container").height())/2;
	$("#container").css("margin-top",margintop);
}
$(document).ready(function() {
	resize();
	$(window).resize(function() {
		resize();
	});
	/*
	$.post("welcome.php",{query:'name'},function(r){
		if(r.status){
			window.location = 'welcome.html';
		}
	},'json');*/
	$.post("",{query:'auto'},function(r){
		if(r){
			window.location = 'welcome';
		}
	},'json');
	$("#submit").click(function() {
		reset();
		$("input").each(function() {
			$(this).val($.trim($(this).val())); 
		});
		var reg = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
		if ($("#user").val() == '') {
			$("#invalid_uid").show("fast");
			return;
		}
		if ($("input[name='method']").val() == "signup") {
			if (!reg.test($("#email").val())) {
				$("#invalid_email").show("fast");
				return;
			}
			if(reg.test($("#user").val())){
				$("#noemail_uid").show("fast");
				return;
			}
			if ($("#pwd").val() == '') {
				$("#invalid_pwd").show("fast");
				return;
			}
			$.ajax({
				type: "POST",
				url: '',
				data: {
					query: 'is_exist',
					username: $("#user").val(),
					email: $("#email").val()
				},
				datatype: "json",
				success: function(r) {
					//r = $.parseJSON(r);
					if(r){
						if (!r['valid_user']) {
							$("#name_used").show("fast");
						} else if (!r['valid_email']) {
							$("#email_used").show("fast");
						} else {
							var encrypted = md5($("#pwd").val());
							$("#pwd").val(encrypted);
							var data = {};
							$("input").map(function(a,b){
								data[b.name] = b.value;
							});
							$.post('',data,function(r){
								console.log(r);
								if(r){
									alert("Account created successfully");
									window.location = 'welcome';
								}
							},'json');
						}
					}
					else{
						alert('Please ensure your input');
					}
				},
				error: function() {
					$("#server_error").show("fast");
				}
			});
		}else if ($("#pwd").val() == '') {
			$("#invalid_pwd").show("fast");
			return;
		} else {
			reset();
			encrypt(function(){
				var data = {};
				$("input").map(function(a,b){
					data[b.name] = b.value;
				});
				$.post('',data,function(r){
					console.log(r);
					if(r){
						window.location = 'welcome';
					}
					else{
						$("#login_fail").show("fast");
					}
				},'json');
		});
		}
	});
});