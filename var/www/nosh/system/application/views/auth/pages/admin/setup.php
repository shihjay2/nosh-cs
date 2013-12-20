<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load('<?php echo site_url("search/loadpage");?>');
	$.ajax({
		url: "<?php echo site_url('admin/setup/check_admin');?>",
		type: "POST",
		success: function(data){
			if (data == "Yes") {
				$("#setup_cpt").show();
				$("#setup_update").show();
				$("#setup_cancel").hide();
			} else {
				$("#setup_cpt").hide();
				$("#setup_update").hide();
				$(".practice_exclude").hide();
				$("#setup_cancel").show();
				var practice_active = "<?php $this->session->userdata('practice_active');?>";
				if (practice_active == "Y") {
					$("#setup_cancel_div").show();
					$("#setup_restart_div").hide();
					$("#subscription text").text("Cancel Subscription");
				} else {
					$("#setup_cancel_div").hide();
					$("#setup_restart_div").show();
					$("#subscription text").text("Restart Subscription");
				}
			}
		}
	});
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
	$('#cancel_button').button().click(function() {
		if ($('#cancel_confirm').is(':checked')) {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('registerpractice/cancel_subscription');?>",
				success: function(data){
					if (data == "Your account has been canceled!") {
						window.location="<?php echo site_url('admin/setup');?>";
					} else {
						$.jGrowl(data);
					}
				}
			});
		} else {
			$.jGrowl('You need to check mark the confirmation statement to cancel your subscription!');
		}
	});
	$('#restart_button').button().click(function() {
		window.location="<?php echo site_url('registerpractice/') . "/" . $this->session->userdata('practice_id');?>";
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
					<li id="setup_cpt"><?php echo anchor('admin/setup/cpt/', 'CPT Codes');?></li>
					<li id="setup_update"><?php echo anchor('admin/setup/update/', 'Update ');?></li>
					<li id="setup_cancel"><a href="#admin_tabs_9" id="subscription_text">Cancel Subscription</a></li>
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
							<tr class="practice_exclude">
								<td>Gmail username for sending e-mail:</td>
								<td><input type="text" name="smtp_user" id="smtp_user" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr class="practice_exclude">
								<td>Gmail password for sending e-mail:</td>
								<td><input type="password" name="smtp_pass" id="smtp_pass" class="text ui-widget-content ui-corner-all" style="width:400px"/></td>
							</tr>
							<tr>
								<td>Additional message for e-mailed<br>appointment reminders:</td>
								<td><textarea name="additional_message" id="additional_message" class="text ui-widget-content ui-corner-all" rows="4" style="width:400px"></textarea></td>
							</tr>
							<tr class="practice_exclude">
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
				<div id="admin_tabs_9" style="overflow:auto;"> 
					<div id="setup_cancel_div">
						<h4>We're so sorry to see you go!</h4>
						Before you cancel your subscription to NOSH ChartingSystem, please make sure you have reviewed this checklist.  You are responsible for reviewing and completing these prior to cancellation.  It is suggested that you run through these items and return back to this page before commiting your cancellation.<br>
						<ul>
							<li>Ensure all <strong>encounters</strong> (drafts, or otherwise) have been signed by all of your providers.</li>
							<li>Ensure that all <strong>orders and prescriptions</strong> have been accounted for so that they can be tracked by you on your transition to another system.</li>
							<li>Ensure that all <strong>pending claims</strong> have been accounted for so that they can be tracked by you on your transition to another system.</li>
							<li><strong>Notify your patients</strong> that your system will no longer be active.  Patients will not be able to access the practice portal after cancellation takes place.</li>
							<li>Remove any <strong>links</strong> to your NOSH instance such as you practice website, business cards, and email signatures.</li>
							<li>Keep in mind, the administrator account (the username and password for which the practice account was first set up with), will always be active, even after cancellation.  After all, the data belongs to you and we will not erase or destroy your data and we will not take your data hostage for any reason.  You will not be charged for having this active administrator account.  It is our commitment to making sure that you have full access to your data.  With this active account, your practice administrator can print (to PDF) any or all patient records for future records requests, or to incorporate the records with another electronic health record system.</li>
						</ul>
						If you change your mind after cancellation, you are always welcome to re-register.  With your one active administrator account, you can re-register at any time.<br>
						<br><input type="checkbox" id="cancel_confirm" value="y"/> I confirm that I want to cancel my NOSH ChartingSystem subscription for my practice.<br>
						<br><button type="button" id="cancel_button">Cancel Subscription</button>
					</div>
					<div id="setup_restart_div">
						<h4>We're so happy you'd like to return to using NOSH ChartingSystem.</h4>
						Once you click the button below, you'll be sent to the registration page.  Don't worry, all of your previous data is still intact and will be re-opened for editing once you receive your activation confirmation e-mail from us.  Once you are activated, please make sure you re-activate all exisiting users and designate new passwords.  Other things to check after you activate:
						<ul>
							<li>Ensure that your online fax system is still current and is accurately entered in the NOSH ChartingSystem Admin Setup page.</li>
							<li>Ensure that all <strong>orders and prescriptions</strong> from your previous system (if applicable) has been accounted for and immediately entered in NOSH ChartingSystem to the correct patient.</li>
							<li>Ensure that all <strong>pending claims</strong> from your previous system (if applicable) has been accounted for and immediately entered in NOSH ChartingSystem to the correct patient so that they can be tracked by your practice.</li>
							<li>Remember that you can upload PDF documents of previous medical records from your previous system (if applicable) in the patient chart dashboard.</li>
						</ul>
						<br><button type="button" id="restart_button">Restart My Subscription</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

