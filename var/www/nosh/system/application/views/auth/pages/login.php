<script type="text/javascript">
$(document).ready(function() {
	$("#username").focus();
	$("#confirm").hide('fast');
	$("#login_button").button();
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('start/check_cookie');?>",
		success: function(data){
			if (data != "") {
				$.jGrowl(data);
			}
		}
	});
	$("#register_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 450, 
		width: 600, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function (event, ui) {
			$("#new_password_count").val("0");
			$('#numberReal').realperson({includeNumbers: true});
			$("#username1").removeClass("ui-state-error");
		},
		close: function(event, ui) {
			$("#register_form").clearForm();
			$('#numberReal').realperson('destroy'); 
		}
	});
	$("#register").click(function(){
		$("#register_dialog").dialog('open');
	});
	$("#dob").mask("99/99/9999");
	$("#submit1").button();
	$("#submit1").click(function(){
		var a = $("#lastname");
		var b = $("#firstname");
		var c = $("#dob");
		var d = $("#email");
		var e = $("#username1");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Last name");
		bValid = bValid && checkEmpty(b,"First name");
		bValid = bValid && checkEmpty(c,"Date of birth");
		bValid = bValid && checkEmpty(d,"E-mail address");
		bValid = bValid && checkEmpty(e,"Desired username");
		bValid = bValid && checkRegexp(d, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. user@nosh.com" );
		if (bValid) {
			var str = $("#register_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('start/register_user');?>",
					data: str,
					dataType: "json",
					success: function(data){
						if (data.response == "1") {
							$("#new_password_id").val(data.id);
							$("#register_dialog").dialog('close');
							$("#new_password_dialog").dialog('open');
						} else if (data.response == "2") {
							$.jGrowl('Incorrect registration code, CAPTCHA, or patient information!');
							$("#new_password_count").val(data.count);
						} else if (data.response == "3") {
							$.jGrowl("Too many tries.  Contact the practice administrator to manually reset your password.");
							$("#register_dialog").dialog('close');
						} else {
							$.jGrowl('Your registration information has been sent to the administrator and you will receive your registration code within 48-72 hours by e-mail after confirmation of your idenity.<br>Thank you!');
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#forgot_password_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 600, 
		modal: true,
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			$("#secret_question").html("");
			$("#forgot_password_form").clearForm();
			$("#forgot_password_form1").clearForm();
		}
	});
	$("#forgot_password").click(function(){
		var a = $("#username").val();
		if (a) {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('start/forgot_password');?>/" + a,
				dataType: "json",
				success: function(data){
					if (data.response == "You are not a registered user." || data == "You need to setup a secret question and answer.  Contact the practice administrator to manually reset your password.") {
						$.jGrowl(data.response);
					} else {
						$("#secret_question").html(data.response);
						$("#id").val(data.id);
						$("#count").val("0");
						$("#forgot_password_form1").hide();
						$("#forgot_password_dialog").dialog('open');
					}
				}
			});
		} else {
			$.jGrowl("Username field is required.");
		}
	});
	$("#submit2").button().click(function(){
		var a = $("#secret_answer");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Secret Answer");
		if (bValid) {
			var str = $("#forgot_password_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('start/forgot_password1');?>",
					data: str,
					dataType: "json",
					success: function(data){
						if (data.response == "OK") {
							$("#forgot_password_form").hide('fast');
							$("#forgot_password_form1").show('fast');
						} else if (data.response == "Close") {
							$.jGrowl("Too many tries.  Contact the practice administrator to manually reset your password.");
							$("#forgot_password_dialog").dialog('close');
						} else {
							$.jGrowl(data.response);
							$("#secret_answer").val('');
							$("#count").val(data.count);
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#submit3").button().click(function(){
		var a = $("#new_password");
		var b = $("#new_password_confirm");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"New Password");
		bValid = bValid && checkEmpty(b,"Confirm New Password");
		if (bValid) {
			var c = $("#new_password").val();
			var d = $("#new_password_confirm").val();
			if (c != d) {
				$.jGrowl("New passwords do not match!");
				$("#forgot_password_form1").clearForm();
			} else {
				var str = $("#forgot_password_form1").serialize();
				if(str){
					var id = $("#id").val();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('start/forgot_password2');?>/" + id,
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#forgot_password_dialog").dialog('close');
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			}
		}
	});
	$("#new_password_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 600, 
		modal: true,
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			$("#new_password_form").clearForm();
		}
	});
	$("#submit4").button().click(function(){
		var a = $("#new_password1");
		var b = $("#new_password_confirm1");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"New Password");
		bValid = bValid && checkEmpty(b,"Confirm New Password");
		if (bValid) {
			var c = $("#new_password1").val();
			var d = $("#new_password_confirm1").val();
			if (c != d) {
				$.jGrowl("New passwords do not match!");
				$("#new_password_form").clearForm();
			} else {
				var str = $("#new_password_form").serialize();
				if(str){
					var id = $("#new_password_id").val();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('start/new_password');?>/" + id,
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#new_password_dialog").dialog('close');
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			}
		}
	});
	$('#username1').focusout(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('start/check_username');?>",
			data: 'username=' + $('#username1').val(),
			success: function(data){
				if (data != "OK") {
					$.jGrowl(data);
					$("#username1").addClass("ui-state-error");
				} else {
					$("#username1").removeClass("ui-state-error");
				}
			}
		});
	});
});
</script>
<div id="login">
	<div id="logo" align="center">Nosh</div><br>
	<div align="center" >
		<div id="box" align="left" class="ui-corner-all">
			<form method="POST">
				Username:<br><input type="text" id="username" name="username" class="text ui-widget-content ui-corner-all" value="<?php echo set_value('username'); ?>" style="width:292px" class="form" /><br><br>
				Password:<br><input type="password" name="password" class="text ui-widget-content ui-corner-all" value="<?php echo set_value('password'); ?>" style="width:292px" class="form" />
				<br><?php echo form_error('username'); ?><?php echo form_error('password'); ?>
				<div align="center">
				<input type="submit" id="login_button" value="Login" name="login" class="ui-button ui-state-default ui-corner-all"/>
				</div>
			</form>
		</div>
		<br>
		<a href="#" id="register" style="font-size:14px;">Are you new to the Patient Portal?</a><br>
		<a href="#" id="forgot_password"style="font-size:14px;">Did you forget your password?</a><br><br>
		<img src="<?php echo base_url(). 'images/important.png';?>" height="40" width="40" border="0"> NOSH ChartingSystem is compatible with Mozilla Firefox, Google Chrome, Apple Safari, Internet Explorer 8 and up, and Opera web browsers only.
	</div>
	<div id="register_dialog" title="New User Registration">
		<form id="register_form">
			<input type="hidden" name="count" id="new_password_count" value="" />
			Last name:<br><input type="text" style="width:200px" id="lastname" name="lastname" class="text ui-widget-content ui-corner-all" /><br>
			First name:<br><input type="text" style="width:200px" id="firstname" name="firstname" class="text ui-widget-content ui-corner-all" /><br>
			Date of birth:<br><input type="text" style="width:200px" id="dob" name="dob" class="text ui-widget-content ui-corner-all" /><br>
			E-mail address:<br><input type="text" style="width:200px" id="email" name="email" class="text ui-widget-content ui-corner-all" /><br>
			Desired username:<br><input type="text" style="width:200px" id="username1" name="username" class="text ui-widget-content ui-corner-all" /><br>
			Registration code:<br><input type="password" style="width:200px" id="registration_code" name="registration_code" class="text ui-widget-content ui-corner-all" placeholder="Optional"/><br><br><br>
			CAPTCHA<br><div style="width:201px"><input type="text" style="width:200px" id="numberReal" name="numberReal" class="text ui-widget-content ui-corner-all" /><br></div>
			If you don't have a registration code, a registration request will be sent to the practice administrator.<br>
			You will then receive a registration code sent to your e-mail address before you proceed further.
			<hr/>
			<button type="button" id="submit1">Register</button>
		</form>
	</div>
	<div id="forgot_password_dialog" title="Password Recovery">
		<form id="forgot_password_form">
			<input type="hidden" name="id" id="id" value="" />
			<input type="hidden" name="count" id="count" value="" />
			Secret Question:<br><div id="secret_question"></div><br>
			Secret Answer:<br><input type="text" style="width:200px" id="secret_answer" name="secret_answer" class="text ui-widget-content ui-corner-all" /><br>
			<hr/>
			<button type="button" id="submit2">OK</button>
		</form>
		<form id="forgot_password_form1">
			New Password:<br><input type="password" style="width:200px" id="new_password" name="new_password" class="text ui-widget-content ui-corner-all" /><br>
			Confirm New Password:<br><input type="password" style="width:200px" id="new_password_confirm" name="new_password_confirm" class="text ui-widget-content ui-corner-all" /><br>
			<hr/>
			<button type="button" id="submit3">OK</button>
		</form>
	</div>
	<div id="new_password_dialog" title="Create Password">
		<form id="new_password_form">
			<input type="hidden" name="id" id="new_password_id" value="" />
			Password:<br><input type="password" style="width:200px" id="new_password1" name="new_password1" class="text ui-widget-content ui-corner-all" /><br>
			Confirm Password:<br><input type="password" style="width:200px" id="new_password_confirm1" name="new_password_confirm1" class="text ui-widget-content ui-corner-all" /><br>
			<hr/>
			<button type="button" id="submit4">OK</button>
		</form>
	</div>
</div>
