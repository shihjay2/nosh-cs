<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load('<?php echo site_url("search/loadpage");?>');
	$(".save_admin_tab1").click(function(){
		var str = $("#admin_form_1").serialize();		
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/setup/practicelocation/');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$(".save_admin_tab1a").click(function(){
		var str = $("#admin_form_1").serialize();		
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/setup/practicelocation/');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					var active = $('#dashboard_admin_tabs').tabs("option","active");
					var newactive = active + 1;
					$('#dashboard_admin_tabs').tabs('option', 'active', newactive);
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$(".cancel_admin_tab1").click(function(){
		window.location="<?php echo site_url('start');?>";
	});
	$("#fax_type").addOption({"":"No fax service.","efaxsend.com":"eFax","rcfax.com":"ExtremeFax","metrofax.com":"MetroFax","rcfax.com":"RingCentral"});
	$("#fax_email").change(function (){
		var a = $("#fax_email").val();
		var b = a.substr(a.indexOf("@")+1);
		var c = b.substring(0, b.indexOf("."));
		if (c == "yahoo") {
			var imap = "imap.mail." + b + ":993";
		} else {
			var imap = "imap." + b + ":993";
		}
		$("#fax_email_hostname").val(imap);
	});
	$("#state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#phone").mask("(999) 999-9999");
	$("#fax").mask("(999) 999-9999");
	$("#billing_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#transfer_address").click(function(){
		var a = $("#street_address1").val();
		var b = $("#street_address2").val();
		var c = $("#city").val();
		var d = $("#state").val();
		var e = $("#zip").val();
		$("#billing_street_address1").val(a);
		$("#billing_street_address2").val(b);
		$("#billing_city").val(c);
		$("#billing_state").val(d);
		$("#billing_zip").val(e);
	});
	$(".save_admin_tab1").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$(".save_admin_tab1a").button({
		icons: {
			primary: "ui-icon-check"
		},
	});
	$(".cancel_admin_tab1").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#transfer_address").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		},
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('admin/setup/get_practicelocation/');?>",
		dataType: "json",
		success: function(data){
			$("#practice_name").val(data.practice_name);
			$("#street_address1").val(data.street_address1);
			$("#street_address2").val(data.street_address2);
			$("#city").val(data.city);
			$("#state").val(data.state);
			$("#zip").val(data.zip);
			$("#phone").val(data.phone);
			$("#fax").val(data.fax);
			$("#email").val(data.email);
			$("#website").val(data.website);
			$("#billing_street_address1").val(data.billing_street_address1);
			$("#billing_street_address2").val(data.billing_street_address2);
			$("#billing_city").val(data.billing_city);
			$("#billing_state").val(data.billing_state);
			$("#billing_zip").val(data.billing_zip);
			$("#fax_type").val(data.fax_type);
			$("#fax_email").val(data.fax_email);
			$("#fax_email_password").val(data.fax_email_password);
			$("#fax_email_hostname").val(data.fax_email_hostname);
			$("#smtp_user").val(data.smtp_user);
			$("#smtp_pass").val(data.smtp_pass);
			$("#patient_portal").val(data.patient_portal);
			$("#additional_message").val(data.additional_message);
		}
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('admin/setup/get_practice_logo/');?>",
		dataType: 'json',
		success: function(data){
			$("#practice_logo_upload_preview").html(data.link);
			$("#practice_logo_message").html(data.message);
			if (data.button != "") {
				$('#image_target').Jcrop({
					maxSize: [350, 100],
					onSelect: updateCoords
				});
				$("#practice_logo_message").append(data.button);
				$('#image_crop').button().click(function(){
					var str = "x=" + $('#x').val() + "&y=" + $('#y').val() + "&w=" + $('#w').val() + "&h=" + $('#h').val();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('admin/setup/cropimage/');?>",
						data: str,
						dataType: 'json',
						success: function(data){
							$.jGrowl(data.growl);
							$("#practice_logo_upload_preview").html(data.link);
							$("#practice_logo_message").html(data.message);
						}
					});
				});
			}
		}
	});
	$('#practice_logo_upload_submit').button();
	$('#practice_logo_upload_form').iframer({ 
		iframe: 'practice_logo_upload_iframe',
		returnType: 'json',
		onComplete: function(data){ 
			$.jGrowl(data.result);
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/setup/get_practice_logo/');?>",
				dataType: 'json',
				success: function(data){
					$("#practice_logo_upload_preview").html(data.link);
					$("#practice_logo_message").html(data.message);
					if (data.button != "") {
						$('#image_target').Jcrop({
							maxSize: [350, 100],
							onSelect: updateCoords
						});
						$("#practice_logo_message").append(data.button);
						$('#image_crop').button().click(function(){
							var str = "x=" + $('#x').val() + "&y=" + $('#y').val() + "&w=" + $('#w').val() + "&h=" + $('#h').val();
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('admin/setup/cropimage/');?>",
								data: str,
								dataType: 'json',
								success: function(data){
									$.jGrowl(data.growl);
									$("#practice_logo_upload_preview").html(data.link);
									$("#practice_logo_message").html(data.message);
								}
							});
						});
					}
				}
			});
		}
	});
	$('#practice_logo_click').click(function() {
		$("#practice_logo_dialog").dialog('open');
	});
	$('#practice_logo_none').button().click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/setup/no_practice_logo/');?>",
			success: function(data){
				$("#practice_logo_upload_preview").html('');
				$("#practice_logo_message").html('');
			}
		});
	});
	function updateCoords(c) {
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};
});
</script>
<div id="heading2"></div>
<div id ="mainborder_full" class="ui-corner-all">
	<div id="maincontent_full">
		<h4>NOSH ChartingSystem Setup</h4>
		<div id="noshtabs">
			<div id="dashboard_admin_tabs">
				<ul>
					<li><a href="#admin_tabs_1">Practice Location</a></li>
					<li><?php echo anchor('admin/setup/information/', 'Practice Information');?></li>
					<li><a href="#admin_tabs_3">Practice Logo</a></li>
					<li><a href="#admin_tabs_4">Fax Service</a></li>
					<li><a href="#admin_tabs_5">Practice Billing</a></li>
					<li><?php echo anchor('admin/setup/extensions/', 'Extensions and Accounts');?></li>
					<li><?php echo anchor('admin/setup/cpt/', 'CPT Codes');?></li>
					<li><?php echo anchor('admin/setup/update/', 'Update ');?></li>
				</ul>
				<form id="admin_form_1" />
					<div id="admin_tabs_1">
						<button type="button" class="save_admin_tab1">Save</button>
						<button type="button" class="save_admin_tab1a">Save and Continue</button>
						<button type="button" class="cancel_admin_tab1">Cancel</button>
						<hr class="ui-state-default"/>
						<table>
							<tr>
								<td>Practice Name:</td>
								<td><input type="text" name="practice_name" id="practice_name" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Street Address:</td>
								<td><input type="text" name="street_address1" id="street_address1" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Street Address Line 2:</td>
								<td><input type="text" name="street_address2" id="street_address2" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>City:</td>
								<td><input type="text" name="city" id="city" class="text ui-widget-content ui-corner-all" style="width:400px"></td>
							</tr>
							<tr>
								<td>State:</td>
								<td><select name="state" id="state" class="text ui-widget-content ui-corner-all" style="width:400px"></select></td>
							</tr>
							<tr>
								<td>Zip:</td>
								<td><input type="text" name="zip" id="zip" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Phone:</td>
								<td><input type="text" name="phone" id="phone" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Fax:</td>
								<td><input type="text" name="fax" id="fax" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>E-mail:</td>
								<td><input type="text" name="email" id="email" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Website:</td>
								<td><input type="text" name="website" id="website" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Gmail username for sending e-mail:</td>
								<td><input type="text" name="smtp_user" id="smtp_user" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Gmail password for sending e-mail:</td>
								<td><input type="text" name="smtp_pass" id="smtp_pass" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Additional message for e-mailed<br>appointment reminders:</td>
								<td><textarea name="additional_message" id="additional_message" class="text ui-widget-content ui-corner-all" rows="4" style="width:400px"></textarea></td>
							</tr>
							<tr>
								<td>Patient Portal web address</td>
								<td><input type="text" name="patient_portal" id="patient_portal" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
						</table>
					</div>
					<div id="admin_tabs_4">
						<button type="button" class="save_admin_tab1">Save</button>
						<button type="button" class="save_admin_tab1a">Save and Continue</button>
						<button type="button" class="cancel_admin_tab1">Cancel</button>
						<hr class="ui-state-default"/>
						<table>
							<tr>
								<td>Fax Program:</td>
								<td><select name="fax_type" id="fax_type" class="text ui-widget-content ui-corner-all"></select></td>
							</tr>
							<tr>
								<td>E-mail address where faxes are sent:</td>
								<td><input type="text" name="fax_email" id="fax_email" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Password :</td>
								<td><input type="password" name="fax_email_password" id="fax_email_password" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>IMAP Hostname (hostname:port):</td>
								<td><input type="text" name="fax_email_hostname" id="fax_email_hostname" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
						</table>
					</div>
					<div id="admin_tabs_5">
						<button type="button" class="save_admin_tab1">Save</button>
						<button type="button" class="save_admin_tab1a">Save and Continue</button>
						<button type="button" id="transfer_address">Copy Practice Address to Billing Address</button>
						<button type="button" class="cancel_admin_tab1">Cancel</button>
						<hr class="ui-state-default"/>
						<table>
							<tr>
								<td>Billing Street Address:</td>
								<td><input type="text" name="billing_street_address1" id="billing_street_address1" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Billing Street Address Line 2:</td>
								<td><input type="text" name="billing_street_address2" id="billing_street_address2" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Billing City:</td>
								<td><input type="text" name="billing_city" id="billing_city" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Billing State:</td>
								<td><select name="billing_state" id="billing_state" class="text ui-widget-content ui-corner-all"></select></td>
							</tr>
							<tr>
								<td>Billing Zip:</td>
								<td><input type="text" name="billing_zip" id="billing_zip" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
						</table>
					</div>
				</form>
				<div id="admin_tabs_3" style="overflow:auto;"> 
					<div id="practice_logo_upload_preview"></div>
					<div id="practice_logo_message"></div>
					<input type="hidden" id="x" name="x" />
					<input type="hidden" id="y" name="y" />
					<input type="hidden" id="w" name="w" />
					<input type="hidden" id="h" name="h" />
					<form id="practice_logo_upload_form" style="float:left;width:300px;" action="<?php echo site_url('admin/setup/practice_logo_upload');?>" method="post" enctype="multipart/form-data">
						<input type="file" name="fileToUpload" id="fileToUpload"> <input type="submit" id="practice_logo_upload_submit" value="Upload"> 
					</form>
					<input style="float:left;" type="button" id="practice_logo_none" value="No Logo"/>
				</div>
			</div>
		</div>
	</div>
</div>

