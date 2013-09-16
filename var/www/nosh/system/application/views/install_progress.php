<html>
<head>
<title>NOSH ChartingSystem Installer</title>
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link type="text/css" href="<?php echo base_url().'css/main.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/redmond/jquery-ui-1.10.0.custom.min.css';?>" rel="Stylesheet" />
<style type="text/css">
h1 {
	text-align: center;
}
</style>
<script type="text/javascript" src="<?php echo base_url().'js/jquery-1.9.1.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery-ui-1.10.0.custom.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/ajaxupload.js';?>"></script>
<script type="text/javascript" >
$(function() {
	$("#progressbar").progressbar({
		value: 20
	});
});
$(document).ready(function() {
	$('#status').html("Database and default values installed.<br>");
	$('#status1').html("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Installing medication list...");
	var a = $('#message').val();
	if (a != '') {
		$.jGrowl(a);
	}
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('install/do_install2');?>",
		dataType: "json",
		success: function(data){
			$('#progressbar').progressbar("value", parseInt(data.status2));
			$('#status').append(data.status);
			$('#status1').html(data.status1);
		}
	});
	$('#progressbar').bind("progressbarchange", function(event, ui){
		var a = $('#progressbar').progressbar("value");
		if (a == '35') {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('install/do_install2b');?>",
				dataType: "json",
				timeout: 6000000,
				success: function(data){
					$('#progressbar').progressbar("value", parseInt(data.status2));
					$('#status').append(data.status);
					$('#status1').html(data.status1);
				}
			});
		}
		if (a == '40') {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('install/do_install3');?>",
				dataType: "json",
				timeout: 6000000,
				success: function(data){
					$('#progressbar').progressbar("value", parseInt(data.status2));
					$('#status').append(data.status);
					$('#status1').html(data.status1);
					var array1 = data.array;
				}
			});
		}
		if (a == '45') {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('install/do_install3a');?>",
				dataType: "json",
				timeout: 6000000,
				success: function(data){
					$('#progressbar').progressbar("value", parseInt(data.status2));
					$('#status').append(data.status);
					$('#status1').html(data.status1);
				}
			});
		}
		if (a == '50') {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('install/do_install3b');?>",
				dataType: "json",
				timeout: 60000000,
				success: function(data){
					$('#progressbar').progressbar("value", parseInt(data.status2));
					$('#status').append(data.status);
					$('#status1').html(data.status1);
				}
			});
		}
		if (a == '55') {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('install/do_install3c');?>",
				dataType: "json",
				timeout: 60000000,
				success: function(data){
					$('#progressbar').progressbar("value", parseInt(data.status2));
					$('#status').append(data.status);
					$('#status1').html(data.status1);
				}
			});
		}
		if (a == '70') {
			$('#cpt').show('fast');
			$('#status_icon').hide('fast');
		}
		if (a == '80') {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('install/do_install5');?>",
				dataType: "json",
				timeout: 60000000,
				success: function(data){
					$('#progressbar').progressbar("value", 100);
					$('#status').append(data.status);
					$('#status1').html('&nbsp;&nbsp;&nbsp;Installation complete!');
					$('#status_icon').hide('fast');
					$('#finish').show('fast');
				}
			});
		}
		if (a == '100') {
			window.location = "<?php echo site_url('install/do_install6/');?>";
		}
	});
	
	new AjaxUpload('#install_cpt_file', {
		action: "<?php echo site_url('install/do_install4a');?>",
		name: 'fileToUpload',
		responseType: 'json',
		onComplete: function(file, data) {
			$('#progressbar').progressbar("value", 80);
			$('#status').append(data.status);
			$('#status1').html(data.status1);
			$('#cpt').hide('fast');
			$('#status_icon').show('fast');
		}
	});
	
	$("#install_cpt_database").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('install/do_install4');?>",
			dataType: "json",
			success: function(data){
				$('#progressbar').progressbar("value", 80);
				$('#status').append(data.status);
				$('#status1').html(data.status1);
				$('#cpt').hide('fast');
				$('#status_icon').show('fast');
			}
		});
	});
	
	$("#status6").click(function(){
		window.location="<?php echo site_url('install/do_install6');?>";
	});
});
</script>
</head>
<body>
	<div id="logo">Nosh</div><br>
	<div id="mainborder_full" class="ui-corner-all">
		<div id="installcontent">
			<h3>Welcome to the NOSH ChartingSystem Installation</h3><br>
			<strong>Status:</strong> <div id="status"></div><br><br><div id="status1" style="float:left;color:white;"></div><div id="status_icon" style="float:right;"><img src="<?php echo base_url().'images/indicator.gif';?>"></div>
			<div id="progressbar"></div>
			<hr /><br>
			<div id="cpt" style="display:none">
				<strong>Choose an option for the CPT database installation</strong><br>
				<table>
					<tr>
						<td><input type="button" id="install_cpt_file" value="Install CPT Database and Import File" class="ui-button ui-state-default ui-corner-all"/></td>
						<td>Comma-separated values (csv) file with the following index with tab as delimiter: cpt code, cpt description</td>
					</tr>
					<tr>
						<td><input type="button" id="install_cpt_database" value="Install CPT Database with General Practice Codes Only" class="ui-button ui-state-default ui-corner-all"/></td>
						<td>Install the CPT database with the common general codes needed for an outpatient practice.</td>
					</tr>
				</table>
			</div>
			<div id="finish" style="display:none">
				<input type="button" id="status6" value="Continue" class="ui-button ui-state-default ui-corner-all"/> 
			</div>
			<input type="hidden" id="message" value="<?php echo $message;?>">
		</div>
	</div>
</body>
</html>
