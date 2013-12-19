<script type="text/javascript">
$(document).ready(function() {
	$("#dialog_load2").dialog({
		height: 100,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		modal: true
	});
	$("#heading2").load('<?php echo site_url("search/loadpage");?>');
	$('#leftcol').load('<?php echo site_url("provider/chartmenu/menu");?>');
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("provider/chartmenu");?>',
		redirect_url: '<?php echo site_url("logout");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: false
	});
	$("#encounter_location").val("<?php echo $default_pos;?>");
	$("#encounter_location").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/pos');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		minLength: 1
	});
	$("#encounter_date").mask("99/99/9999");
	var currentDate = getCurrentDate();
	var currentTime = getCurrentTime();
	$("#encounter_date").val(currentDate);
	$("#encounter_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#encounter_time").val(currentTime);
	$("#encounter_role").addOption({"":"Choose Provider Role","Primary Care Provider":"Primary Care Provider","Consulting Provider":"Consulting Provider","Referring Provider":"Referring Provider"},false).change(function(){
		if ($(this).val() == "Consulting Provider" || $(this).val() == "Referring Provider") {
			$("#referring_provider_div").show();
		} else {
			$("#referring_provider_div").hide().val('');
		}
	});
	$("#referring_provider_npi").mask("9999999999");
	$("#referring_provider").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/all_contacts2');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		minLength: 3,
		select: function(event, ui){
			$("#referring_provider_npi").val(ui.item.npi);
		}
	});
	$("#encounter_cc").val('');
	$("#encounter_condition_work").addOption({"":"","No":"No","Yes":"Yes"},false);
	$("#encounter_condition_auto").addOption({"":"","No":"No","Yes":"Yes"},false);
	$("#encounter_condition_other").addOption({"":"","No":"No","Yes":"Yes"},false);
	$("#encounter_condition_auto_state").addOption({"":"State where accident occured.","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#encounter_cc").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/cc');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		minLength: 3
	});
	$('#encounter_time').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	$("#new_encounter_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 600, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/get_copay');?>",
				success: function(data){
					$("#encounter_copay").html(data);
				}
			});
			$("#referring_provider_div").hide();
		},
		buttons: {
			'Add Encounter': function() {
				var date = $("#encounter_date");
				var time = $("#encounter_time");
				var location = $("#encounter_location");
				var cc = $("#encounter_cc");
				var work = $("#encounter_condition_work");
				var auto = $("#encounter_condition_auto");
				var other = $("#encounter_condition_other");
				var role = $("#encounter_role");
				var bValid = true;
				bValid = bValid && checkEmpty(date,"Date of Service");
				bValid = bValid && checkEmpty(time,"Time of Service");
				bValid = bValid && checkEmpty(location,"Encounter Location");
				bValid = bValid && checkEmpty(cc,"Chief Complaint");
				bValid = bValid && checkEmpty(work,"Work Accident");
				bValid = bValid && checkEmpty(auto,"Auto Accident");
				bValid = bValid && checkEmpty(other,"Other Accident");
				bValid = bValid && checkEmpty(role,"Provider Role");
				if (bValid) {
					if($("#encounter_type").val() == '') {
						if(confirm('Are you sure you want to create a new encounter without an associated appointment?')){
							var str = $("#new_encounter_form").serialize();
							if(str){
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('provider/encounters/new_encounter');?>",
									data: str,
									success: function(data){
										$.jGrowl(data);
										$("#new_encounter_dialog").dialog('close');
										window.location = "<?php echo site_url ('provider/encounters/view/');?>";
									}
								});
							} else {
								$.jGrowl("Please complete the form");
							}
						}
					} else {
						var str = $("#new_encounter_form").serialize();
						if(str){
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/encounters/new_encounter');?>",
								data: str,
								success: function(data){
									$.jGrowl(data);
									$("#new_encounter_dialog").dialog('close');
									window.location = "<?php echo site_url ('provider/encounters/view/');?>";
								}
							});
						} else {
							$.jGrowl("Please complete the form");
						}
					}
				}
			},
			Cancel: function() {
				$("#new_encounter_dialog").dialog('close');
			}
		}
	});
	$("#new_encounter").click(function() {
		$("#new_encounter_dialog").dialog('open');
		$("#encounter_type").addOption({'':'Choose appointment to associate encounter!'});
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/get_appointments');?>",
			dataType: "json",
			success: function(data){
				$("#encounter_type").addOption(data,false);
			}
		});
		$("#encounter_condition_work").val('No');
		$("#encounter_condition_auto").val('No');
		$("#encounter_condition_other").val('No');
	});
	function t_messages_tags() {
		var id = $("#t_messages_id").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('search/get_tags/t_messages_id');?>" + "/" + id,
			dataType: "json",
			success: function(data){
				$("#t_messages_tags").tagit("fill",data);
			}
		});
	}
	$("#new_message").click(function() {
		$("#edit_message_form").clearForm();
		$.ajax({
			url: "<?php echo site_url('provider/chartmenu/new_message');?>",
			dataType: "json",
			type: "POST",
			success: function(data){
				$("#t_messages_id").val(data);
			}
		});
		var currentDate = getCurrentDate();
		$("#t_messages_dos").val(currentDate);
		jQuery("#messages").trigger("reloadGrid");
		$("#messages_list_dialog").dialog('open');
		$("#edit_message_fieldset").show('fast');
		$("#t_messages_subject").focus();
		$("#message_view").html('');
		t_messages_tags();
		$("#messages_main_dialog").dialog('open');
	});
	$("#new_letter").click(function() {
		$("#letter_dialog").dialog('open');
	});
	$("#new_import_documents_from").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/document_from');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		minLength: 2
	});
	$("#new_import_documents_desc").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/document_description');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		minLength: 2
	});
	$("#new_import_documents_type").addOption({"Laboratory":"Laboratory","Imaging":"Imaging","Cardiopulmonary":"Cardiopulmonary","Endoscopy":"Endoscopy","Referrals":"Referrals","Past Records":"Past Records","Other Forms":"Other Forms"}, false);
	$("#new_import_documents_date").mask("99/99/9999");
	$("#new_import_documents_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#new_import_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		beforeclose: function(event, ui) {
			var a = $("#new_import_documents_id").val();
			if(a != ''){
				if(confirm('You have not completed importing the document.  Are you sure you want to close this window?')){ 
					$('#new_import_form').clearForm();
					$("#new_import_message").html('');
					return true;
				} else {
					return false;
				}
			} else {
				$('#new_import_form').clearForm();
				$("#new_import_message").html('');
				return true;
			}
		}
	});
	$("#new_import").click(function() {
		var html = '<input type="file" name="fileToUpload" id="fileToUpload"> <input type="submit" id="import_upload_upload" value="Upload">';
		$("#import_upload_span").html(html);
		$("#import_upload_upload").button();
		$("#import_upload_div").show('fast');
		$('#new_import_upload').show('fast');
		$("#new_import_message").html('');
	});
	$('#import_upload_form').iframer({ 
		iframe: 'import_upload_iframe',
		returnType: 'json',
		onComplete: function(data){ 
			$.jGrowl(data.result);
			$("#new_import_message").html(data.result1);
			$("#new_import_documents_id").val(data.id);
			$('#new_import1').val('');
			$("#new_import_dialog").dialog('open');
			$("#new_import_documents_from").focus();
			$("#import_upload_div").hide('fast');
			$("#import_upload_span").html('');
		} 
	});
	$("#save_new_import").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#cancel_new_import").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#save_new_import").click(function() {
		var type = $("#new_import_documents_type");
		var date = $("#new_import_documents_date");
		var bValid = true;
		bValid = bValid && checkEmpty(type,"Documents Type");
		bValid = bValid && checkEmpty(date,"Documents Date");
		if (bValid) {
			var str = $("#new_import_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/documents_upload1');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$('#new_import_form').clearForm();
						$("#new_import_message").html('');
						$("#new_import_dialog").dialog('close');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_new_import").click(function() {
		var id = $("#new_import_documents_id").val();
		if(id){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/delete_upload');?>",
				data: "documents_id=" + id,
				success: function(data){
					$.jGrowl(data);
					$('#new_import_form').clearForm();
					$("#new_import_message").html('');
					$("#new_import_dialog").dialog('close');
				}
			});
		} else {
			$.jGrowl("Error canceling upload!");
		}
	});
	$("#import_ccr").click(function() {
		var html = '<input type="file" name="fileToUpload1" id="fileToUpload1"> <input type="submit" id="import_ccr_upload" value="Upload">';
		$("#import_ccr_span").html(html);
		$("#import_ccr_upload").button();
		$("#import_ccr_div").show('fast');
	});
	$('#import_ccr_form').iframer({ 
		iframe: 'import_ccr_iframe',
		returnType: 'html',
		onComplete: function(data){ 
			$.jGrowl(data);
			$("#import_ccr_div").hide('fast');
			$("#import_ccr_span").html('');
		} 
	});
	$("#print_ccr").click(function() {
		window.open("<?php echo site_url('provider/chartmenu/print_ccr');?>/");
	});
	
	$("#internal_messages_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false
	});
	function split1( val ) {
		return val.split( /;\s*/ );
	}
	function extractLast1( term ) {
		return split( term ).pop();
	}
	$("#messages_to").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/all_users');?>",
				dataType: "json",
				type: "POST",
				data: "term=" + extractLast1(req.term),
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		search: function() {
			var term = extractLast1( this.value );
			if ( term.length < 2 ) {
				return false;
			}
		},
		focus: function() {
			return false;
		},
		select: function(event, ui){
			var terms = split1( this.value );
			terms.pop();
			terms.push( ui.item.value );
			terms.push( "" );
			this.value = terms.join( ";" );
			return false;
		}
	});
	$("#messages_cc").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/all_users');?>",
				dataType: "json",
				type: "POST",
				data: "term=" + extractLast1(req.term),
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		search: function() {
			var term = extractLast1( this.value );
			if ( term.length < 2 ) {
				return false;
			}
		},
		focus: function() {
			return false;
		},
		select: function(event, ui){
			var terms = split1( this.value );
			terms.pop();
			terms.push( ui.item.value );
			terms.push( "" );
			this.value = terms.join( ";" );
			return false;
		}
	});
	$("#messages_patient").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		minLength: 1,
		select: function(event, ui){
			$("#messages_pid").val(ui.item.id);
			$("messages_to").focus();
		}
	});
	$("#send_message1").button({
		icons: {
			primary: "ui-icon-mail-closed"
		}
	});
	$("#send_message1").click(function(){
		var a = $("#messages_to");
		var b = $("#messages_subject");
		var c = $("#messages_body");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"To");
		bValid = bValid && checkEmpty(b,"Subject");
		bValid = bValid && checkEmpty(c,"Message");
		if (bValid) {
			var str = $("#internal_messages_form_id").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/messaging/send_message/');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$("#internal_messages_form_id").clearForm();
						$("#internal_messages_form_id").hide('fast');
						$("#internal_messages_dialog").dialog('close');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#draft_message1").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#draft_message1").click(function(){
		var str = $("#internal_messages_form_id").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/messaging/draft_message/');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$("#internal_messages_form_id").clearForm();
					$("#internal_messages_form_id").hide('fast');
					$("#internal_messages_dialog").dialog('close');
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#cancel_message1").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_message1").click(function(){
		var message_id = $("#messages_message_id").val();
		if (message_id == '') {
			$("#internal_messages_form_id").clearForm();
			$("#internal_messages_form_id").hide('fast');
			$("#internal_messages_dialog").dialog('close');
		} else {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/messaging/delete_message/');?>",
				data: "message_id=" + message_id,
				success: function(data){
					$.jGrowl(data);
					$("#internal_messages_form_id").clearForm();
					$("#internal_messages_form_id").hide('fast');
					$("#internal_messages_dialog").dialog('close');
				}
			});
		}
	});
	$("#create_patient_message").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('search/patient_is_user');?>",
			dataType: 'json',
			success: function(data){
				if (data.message == 'yes') {
					$("#internal_messages_form").clearForm();
					$("#messages_to").val(data.messages_to);
					$("#messages_patient").val(data.messages_patient);
					$("#messages_pid").val(data.pid);
					$("#internal_messages_dialog").dialog('open');
					$("#messages_subject").focus();
				} else {
					$.jGrowl("Patient is not a portal user.  Register the patient so that you can send a secure direct message to the patient.");
				}
			}
		});
	});
	$(document).ajaxStop(function(){
		$("#dialog_load2").dialog("close");
	});
	$("#eprescribe_dialog").dialog({
		height: 200,
		width: 500,
		autoOpen: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		modal: true
	});
	$("#eprescribe_website").button();
	$("#eprescribe_website").click(function(){
		window.open("https://erxnow.allscripts.com/default.aspx");
	});
	$("#eprescribe_greasemonkey").button();
	$("#eprescribe_send_medications").button();
	$("#eprescribe_update_demographics").button();
});
</script>
<div id="dialog_load2" title="Loading...">
	<img src="<?php echo base_url().'images/indicator.gif';?>"> Loading patient chart.
</div>
<div id="heading2"></div>
<div id="leftcol"></div>
<div id ="mainborder" class="ui-corner-all">
	<div id="maincontent1">
		<table>
			<tr>
				<td valign="top">
					<table id="main_column1">
						<tbody>
							<tr>
								<td><img src="<?php echo base_url().'images/newmessage.png';?>" border="0"></td>
								<td><a href="#" id="new_message">New Telephone Message</a></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url().'images/newencounter.png';?>" border="0"></td>
								<td><a href="#" id="new_encounter">New Encounter</a></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url().'images/newletter.png';?>" border="0"></td>
								<td><a href="#" id="new_letter">New Letter</a></td>
							</tr>					
							<tr>
								<td><img src="<?php echo base_url().'images/download.png';?>" border="0"></td>
								<td>
									<a href="#" id="new_import">Import New Documents</a>
									<div id="import_upload_div" style="display:none"> 
										<br>
										<form id="import_upload_form" action="<?php echo site_url('provider/chartmenu/documents_upload');?>" method="post" enctype="multipart/form-data">
											<span id="import_upload_span"></span>
										</form>
									</div>
								</td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url().'images/printmgr.png';?>" border="0"></td>
								<td><a href="#" id="print_chart">Print Chart</a></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td valign="top">
					<table id="main_column2">
						<tbody>
							<tr>
								<td><img src="<?php echo base_url().'images/email.png';?>" border="0"></td>
								<td><a href="#" id="create_patient_message">Send Message to Patient</a></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url().'images/users.png';?>" border="0"></td>
								<td>
									<a href="#" id="import_ccr">Import Continuity of Care Record (XML)</a>
									<div id="import_ccr_div" style="display:none"> 
										<br>
										<form id="import_ccr_form" action="<?php echo site_url('provider/chartmenu/import_ccr');?>" method="post" enctype="multipart/form-data">
											<span id="import_ccr_span"></span>
										</form>
									</div>
								</td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url().'images/chart1.png';?>" border="0"></td>
								<td><a href="#" id="print_ccr">Print Continuity of Care Record</a></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>	
	</div>
</div>
<div id="new_encounter_dialog" title="New Encounter">
	<form name="new_encounter_form" id="new_encounter_form">
		<div style="width:490px">
			<div style="float:left;margin:5px"><label for="encounter_date">Date of Service</label><br><input type="text" name="encounter_date" id="encounter_date" style="width:140px" class="text ui-widget-content ui-corner-all" /></div>
			<div style="float:left;margin:5px"><label for="encounter_time">Time of Service</label><br><input type="text" name="encounter_time" id="encounter_time" style="width:140px" class="text ui-widget-content ui-corner-all" /></div>
			<div style="float:left;margin:5px"><label for="encounter_location">Encounter Location</label><br><input type="text" name="encounter_location" id="encounter_location" style="width:140px" class="text ui-widget-content ui-corner-all" /></div>
		</div>
		<div style="width:490px;float:left;margin:5px"><label for="encounter_type">Encounter Type</label><br><select name="encounter_type" id="encounter_type" class="text ui-widget-content ui-corner-all"></select></div>
		<div style="width:490px">
			<div style="float:left;margin:5px"><label for="encounter_role">Provider Role</label><br><select name="encounter_role" id="encounter_role" class="text ui-widget-content ui-corner-all"></select></div>
			<div id="referring_provider_div">
				<div style="float:left;margin:5px"><label for="referring_provider">Referring Provider</label><br><input type="text" name="referring_provider" id="referring_provider" style="width:164px" class="text ui-widget-content ui-corner-all"/></div>
				<div style="float:left;margin:5px"><label for="referring_provider_npi">Referring Provider NPI</label><br><input type="text" name="referring_provider_npi" id="referring_provider_npi" style="width:140px" class="text ui-widget-content ui-corner-all"/></div>
			</div>
		</div>
		<div style="width:490px;float:left">
			<div style="float:left;margin:5px"><label for="encounter_cc">Chief Complaint</label><br><input type="text" name="encounter_cc" id="encounter_cc" style="width:390px" class="text ui-widget-content ui-corner-all" /></div>
		</div>
		<div style="width:490px;float:left;margin:5px">
			<strong>Condtion Related To:</strong><br>
			<label for="encounter_condition_work">Work: </label><select name="encounter_condition_work" id="encounter_condition_work" class="text ui-widget-content ui-corner-all"></select><br>
			<label for="encounter_condition_auto">Auto Accident: </label><select name="encounter_condition_auto" id="encounter_condition_auto" class="text ui-widget-content ui-corner-all"></select><select name="encounter_condition_auto_state" id="encounter_condition_auto_state" class="text ui-widget-content ui-corner-all"></select><br>
			<label for="encounter_condition_other">Other Accident: </label><select name="encounter_condition_other" id="encounter_condition_other" class="text ui-widget-content ui-corner-all"></select><br>
			<label for="encounter_condition">Other: </label><input type="text" name="encounter_condition" id="encounter_condition" style="width:300px" class="text ui-widget-content ui-corner-all" />
		</div>
		<div style="width:490px;float:left;margin:5px">
			<strong>Insurance information:</strong><br><div id="encounter_copay"></div></div>
		</div>
	</form>
</div>
<div id="new_import_dialog" title="Import New Documents">
	<div id="new_import_message"></div><br>
	<div id="new_import_fieldset">		
		<form name="new_import_form" id="new_import_form">
		<input type="hidden" name="documents_id" id="new_import_documents_id"/>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>Uploaded Document</legend>
			<table>
				<tbody>
					<tr>
						<td><label for="new_import_documents_from">From</label></td>
						<td><input type="text" name="documents_from" id="new_import_documents_from" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
						<td></td>
					</tr>
					<tr>
						<td><label for="new_import_documents_type">Document Type</label></td>
						<td><select name="documents_type" id="new_import_documents_type" class="text ui-widget-content ui-corner-all"></select></td>
						<td></td>
					</tr>
					<tr>
						<td><label for="new_import_documents_desc">Description</label></td>
						<td><input type="text" name="documents_desc" id="new_import_documents_desc" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
						<td></td>
					</tr>
					<tr>
						<td><label for="new_import_document_date">Document Date</label></td>
						<td><input type="text" name="documents_date" id="new_import_documents_date" class="text ui-widget-content ui-corner-all" /></td>
						<td>
							<button type="button" id="save_new_import">Save</button> 
							<button type="button" id="cancel_new_import">Cancel</button>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset><br>
		</form>
	</div>
</div>
<div id="internal_messages_dialog" title="Internal Message">
	<form name="internal_messages_form" id="internal_messages_form_id">
		<input type="hidden" name="message_id" id="messages_message_id"/>
		<input type="hidden" name="pid" id="messages_pid">
		<input type="hidden" name="t_messages_id" id="messages_t_messages_id">
		<button type="button" id="send_message1">Send</button>
		<button type="button" id="draft_message1">Save Draft</button>
		<button type="button" id="cancel_message1">Cancel</button>
		<hr class="ui-state-default"/>
		<table>
			<tbody>
				<tr>
					<td>Subject:<br>
					<input type="text" name="subject" id="messages_subject" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Concerning this patient (optional):<br>
					<input type="text" name="patient_name" id="messages_patient" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>To:<br>
					<input type="text" id="messages_to" name="message_to" style="width:400px"class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>CC:<br>
					<input type="text" id="messages_cc" name="cc" style="width:400px"class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Message:<br>
					<textarea name="body" id="messages_body" rows="12" style="width:400px" class="text ui-widget-content ui-corner-all"></textarea></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<div id="eprescribe_dialog" title="E-Prescribing">
	<input type="hidden" id="e_medication" />
	<input type="hidden" id="e_dosage" />
	<input type="hidden" id="e_sig" />
	<input type="hidden" id="e_quantity" />
	<input type="hidden" id="e_refills" />
	<input type="hidden" id="e_daw" />
	<input type="hidden" id="e_days" />
	<input type="hidden" id="e_lastname" />
	<input type="hidden" id="e_firstname" />
	<input type="hidden" id="e_dob" />
	<input type="hidden" id="e_address" />
	<input type="hidden" id="e_city" />
	<input type="hidden" id="e_state" />
	<input type="hidden" id="e_zip" />
	<input type="hidden" id="e_sex" />
	<input type="hidden" id="e_phone" />
	<input type="hidden" id="e_mobile" />
	<input type="hidden" id="e_pid" />
	<input type="button" id="eprescribe_website" value="Allscripts Website" class="ui-button ui-state-default ui-corner-all"/> 
	<a id="eprescribe_greasemonkey" href="<?php echo base_url().'js/erxnow.allscripts.integr.user.js';?>">Install Greasemonkey User Script for E-prescribing</a> 
	<hr class="ui-state-default"/>
	Commands:<br />
	<input type="button" id="eprescribe_send_medications" value="Send Medication Only" class="ui-button ui-state-default ui-corner-all"/>
	<input type="button" id="eprescribe_update_demographics" value="Export Demographics and Send Medication" class="ui-button ui-state-default ui-corner-all"/>
</div>
