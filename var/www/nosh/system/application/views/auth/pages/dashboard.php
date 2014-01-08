<script type="text/javascript">
$(document).ready(function() {
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("start");?>',
		redirect_url: '<?php echo site_url("logout");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: false
	});
	$("#heading2").load("<?php echo site_url('search/loadpage');?>");
	$("#provider_draft").click(function(){
		jQuery("#draft_messages").jqGrid('GridUnload');
		jQuery("#draft_messages").jqGrid({
			url:"<?php echo site_url('start/draft_messages');?>",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','PID','Date of Service','Last Name','First Name','Subject'],
			colModel:[
				{name:'t_messages_id',index:'t_messages_id',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true},
				{name:'t_messages_dos',index:'t_messages_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
				{name:'lastname',index:'lastname',width:100},
				{name:'firstname',index:'firstname',width:100},
				{name:'t_messages_subject',index:'t_messages_subject',width:300}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#draft_messages_pager'),
			sortname: 't_messages_dos',
		 	viewrecords: true,
		 	sortorder: "desc",
		 	caption:"Telephone Message Drafts",
		 	emptyrecords:"No messages.",
		 	height: "100%",
		 	onSelectRow: function(id) {
		 		var pid = jQuery("#draft_messages").getCell(id,'pid');
		 		$("#hidden_pid").val(pid);
		 		var oldpt = '<?php echo $this->session->userdata("pid");?>';
				if(!oldpt){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('search/openchart/');?>",
						data: "pid=" + pid,
						success: function(data){
							window.location = "<?php echo site_url('search/openchart1/');?>";
						}
					});
				} else {
					if(pid != oldpt){
						$("#search_dialog").dialog('open');
					} else {
						$.jGrowl("Patient chart already open!");
					}
				}
		 	},
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#draft_messages_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#draft_encounters").jqGrid('GridUnload');
		jQuery("#draft_encounters").jqGrid({
			url:"<?php echo site_url('start/draft_encounters');?>",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','PID','Date of Service','Last Name','First Name','Chief Complaint'],
			colModel:[
				{name:'eid',index:'eid',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true},
				{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
				{name:'lastname',index:'lastname',width:100},
				{name:'firstname',index:'firstname',width:100},
				{name:'encounter_cc',index:'encounter_cc',width:300}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#draft_encounters_pager'),
			sortname: 'encounter_DOS',
		 	viewrecords: true,
		 	sortorder: "desc",
		 	caption:"Encounter Drafts - Click to open encounter",
		 	emptyrecords:"No encounters.",
		 	height: "100%",
		 	onSelectRow: function(id) {
		 		var pid = jQuery("#draft_encounters").getCell(id,'pid');
		 		$("#hidden_pid").val(pid);
		 		var eid = jQuery("#draft_encounters").getCell(id,'eid');
		 		$("#hidden_eid").val(eid);
		 		var oldpt = '<?php echo $this->session->userdata("pid");?>';
				if(!oldpt){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('search/openchart/');?>",
						data: "pid=" + pid,
						success: function(data){
							var eid = jQuery("#draft_encounters").getCell(id,'eid');
		 					$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/encounter_id_set/');?>",
								data: "eid=" + eid,
								success: function(data) {
									window.location = "<?php echo site_url ('provider/encounters/view/');?>";
								}
							});
						}
					});
				} else {
					if(pid != oldpt){
						$("#search_dialog").dialog('open');
					} else {
						$.jGrowl("Patient chart already open!");
					}
				}
		 	},
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#draft_encounters_pager',{search:false,edit:false,add:false,del:false});
		$("#draft_div").show('fast');
	});
	$("#provider_alerts").click(function(){
		jQuery("#dashboard_alert").jqGrid('GridUnload');
		jQuery("#dashboard_alert").jqGrid({
			url:"<?php echo site_url('start/alerts');?>",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','PID','Due Date','Last Name','First Name','Alert','Description'],
			colModel:[
				{name:'alert_id',index:'alert_id',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true},
				{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
				{name:'lastname',index:'lastname',width:100},
				{name:'firstname',index:'firstname',width:100},
				{name:'alert',index:'alert',width:100},
				{name:'alert_description',index:'alert',width:200}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#dashboard_alert_pager'),
			sortname: 'alert_date_active',
		 	viewrecords: true,
		 	sortorder: "asc",
		 	caption:"Reminders - Click to open chart",
		 	emptyrecords:"No reminders.",
		 	height: "100%",
		 	onSelectRow: function(id) {
		 		var pid = jQuery("#dashboard_alert").getCell(id,'pid');
		 		$("#hidden_pid").val(pid);
		 		var oldpt = '<?php echo $this->session->userdata("pid");?>';
				if(!oldpt){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('search/openchart/');?>",
						data: "pid=" + pid,
						success: function(data){
							window.location = "<?php echo site_url('search/openchart1/');?>";
						}
					});
				} else {
					if(pid != oldpt){
						$("#search_dialog").dialog('open');
					} else {
						$.jGrowl("Patient chart already open!");
					}
				}
		 	},
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#dashboard_alert_pager',{search:false,edit:false,add:false,del:false});
		$("#alert_div").show('fast');
	});
	$("#provider_mtm_alerts").click(function(){
		jQuery("#dashboard_mtm_alert").jqGrid('GridUnload');
		jQuery("#dashboard_mtm_alert").jqGrid({
			url:"<?php echo site_url('start/mtm_alerts');?>",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','PID','Last Name','First Name'],
			colModel:[
				{name:'alert_id',index:'alert_id',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true},
				{name:'lastname',index:'lastname',width:250},
				{name:'firstname',index:'firstname',width:250}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#dashboard_mtm_alert_pager'),
			sortname: 'lastname',
		 	viewrecords: true,
		 	sortorder: "asc",
		 	caption:"Medication Therapy Managment Patient Roster - Click to open chart",
		 	emptyrecords:"No patients.",
		 	height: "100%",
		 	onSelectRow: function(id) {
		 		var pid = jQuery("#dashboard_mtm_alert").getCell(id,'pid');
		 		$("#hidden_pid").val(pid);
		 		var oldpt = '<?php echo $this->session->userdata("pid");?>';
				if(!oldpt){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('search/openchart/');?>",
						data: "pid=" + pid,
						success: function(data){
							window.location = "<?php echo site_url('search/openchart1/');?>";
						}
					});
				} else {
					if(pid != oldpt){
						$("#search_dialog").dialog('open');
					} else {
						$.jGrowl("Patient chart already open!");
					}
				}
		 	},
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#dashboard_mtm_alert_pager',{search:false,edit:false,add:false,del:false});
		$("#mtm_alert_div").show('fast');
	});
	$("#assistant_draft").click(function(){
		jQuery("#draft_messages").jqGrid('GridUnload');
		jQuery("#draft_messages").jqGrid({
			url:"<?php echo site_url('start/draft_messages');?>",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','PID','Date of Service','Last Name','First Name','Subject'],
			colModel:[
				{name:'t_messages_id',index:'t_messages_id',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true},
				{name:'t_messages_dos',index:'t_messages_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
				{name:'lastname',index:'lastname',width:100},
				{name:'firstname',index:'firstname',width:100},
				{name:'t_messages_subject',index:'t_messages_subject',width:300}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#draft_messages_pager'),
			sortname: 't_messages_dos',
		 	viewrecords: true,
		 	sortorder: "desc",
		 	caption:"Telephone Message Drafts - Click to open chart",
		 	emptyrecords:"No messages.",
		 	height: "100%",
		 	onSelectRow: function(id) {
		 		var pid = jQuery("#draft_messages").getCell(id,'pid');
		 		$("#hidden_pid").val(pid);
				var oldpt = '<?php echo $this->session->userdata("pid");?>';
				if(!oldpt){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('search/openchart/');?>",
						data: "pid=" + pid,
						success: function(data){
							window.location = "<?php echo site_url('search/openchart1/');?>";
						}
					});
				} else {
					if(pid != oldpt){
						$("#search_dialog").dialog('open');
					} else {
						$.jGrowl("Patient chart already open!");
					}
				}
		 	},
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#draft_messages_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#draft_encounters").jqGrid('GridUnload');
		$("#draft_div").show('fast');
	});
	$("#assistant_alerts").click(function(){
		jQuery("#dashboard_alert").jqGrid('GridUnload');
		jQuery("#dashboard_alert").jqGrid({
			url:"<?php echo site_url('start/alerts');?>",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','PID','Due Date','Last Name','First Name','Alert','Description'],
			colModel:[
				{name:'alert_id',index:'alert_id',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true},
				{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
				{name:'lastname',index:'lastname',width:100},
				{name:'firstname',index:'firstname',width:100},
				{name:'alert',index:'alert',width:100},
				{name:'alert_description',index:'alert',width:200}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#dashboard_alert_pager'),
			sortname: 'alert_date_active',
		 	viewrecords: true,
		 	sortorder: "asc",
		 	caption:"Reminders - Click to open chart",
		 	emptyrecords:"No reminders.",
		 	height: "100%",
		 	onSelectRow: function(id) {
		 		var pid = jQuery("#dashboard_alert").getCell(id,'pid');
		 		$("#hidden_pid").val(pid);
		 		var oldpt = '<?php echo $this->session->userdata("pid");?>';
				if(!oldpt){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('search/openchart/');?>",
						data: "pid=" + pid,
						success: function(data){
							window.location = "<?php echo site_url('search/openchart1/');?>";
						}
					});
				} else {
					if(pid != oldpt){
						$("#search_dialog").dialog('open');
					} else {
						$.jGrowl("Patient chart already open!");
					}
				}
		 	},
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#dashboard_alert_pager',{search:false,edit:false,add:false,del:false});
		$("#alert_div").show('fast');
	});
	$("#change_password_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 550, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		open: function () {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('start/get_secret_answer');?>",
				dataType: "json",
				success: function(data){
					$("#secret_question").val(data.secret_question);
					$("#secret_answer").val(data.secret_answer);
				}
			});
		},
		buttons: {
			'OK': function() {
				var a = $("#old_password");
				var b = $("#new_password");
				var c = $("#new_password2");
				var d = $("#secret_question");
				var e = $("#secret_answer");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Old Password");
				bValid = bValid && checkEmpty(b,"New Password");
				bValid = bValid && checkEmpty(c,"Confirm New Password");
				bValid = bValid && checkEmpty(d,"Secret Question");
				bValid = bValid && checkEmpty(e,"Secret Answer");
				if (bValid) {
					var f = $("#new_password").val();
					var g = $("#new_password2").val();
					if (f != g) {
						$.jGrowl("New passwords do not match!");
						$("#change_password_form").clearForm();
					} else {
						var str = $("#change_password_form").serialize();
						if(str){
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('start/change_password');?>",
								data: str,
								success: function(data){
									if (data == "Your old password is incorrect!") {
										$.jGrowl(data);
										$("#change_password_form").clearForm();
									} else {
										$.jGrowl(data);
										$("#change_password_form").clearForm();
										$("#change_password_dialog").dialog('close');
									}
								}
							});
						} else {
							$.jGrowl("Please complete the form");
						}
					}
				}
			},
			Cancel: function() {
				$("#change_password_form").clearForm();
				$("#change_password_dialog").dialog('close');
			}
		}
	});
	$("#change_password").click(function(){
		$("#change_password_dialog").dialog('open');
	});
	var secret_question = {"What was your childhood nickname?":"What was your childhood nickname?","In what city did you meet your spouse/significant other?":"In what city did you meet your spouse/significant other?","What is the name of your favorite childhood friend?":"What is the name of your favorite childhood friend?","What street did you live on in third grade?":"What street did you live on in third grade?","What is your oldest sibling’s birthday month and year? (e.g., January 1900)":"What is your oldest sibling’s birthday month and year? (e.g., January 1900)","What is the middle name of your oldest child?":"What is the middle name of your oldest child?","What is your oldest sibling's middle name?":"What is your oldest sibling's middle name?","What school did you attend for sixth grade?":"What is your oldest sibling's middle name?","What was your childhood phone number including area code? (e.g., 000-000-0000)":"What was your childhood phone number including area code? (e.g., 000-000-0000)","What is your oldest cousin's first and last name?":"What is your oldest cousin's first and last name?","What was the name of your first stuffed animal?":"What was the name of your first stuffed animal?","In what city or town did your mother and father meet?":"In what city or town did your mother and father meet?","Where were you when you had your first kiss?":"Where were you when you had your first kiss?","What is the first name of the boy or girl that you first kissed?":"What is the first name of the boy or girl that you first kissed?","What was the last name of your third grade teacher?":"What was the last name of your third grade teacher?","In what city does your nearest sibling live?":"In what city does your nearest sibling live?","What is your oldest brother’s birthday month and year? (e.g., January 1900)":"What is your oldest brother’s birthday month and year? (e.g., January 1900)","What is your maternal grandmother's maiden name?":"What is your maternal grandmother's maiden name?","In what city or town was your first job?":"In what city or town was your first job?","What is the name of the place your wedding reception was held?":"What is the name of the place your wedding reception was held?","What is the name of a college you applied to but didn't attend?":"What is the name of a college you applied to but didn't attend?"};
	$("#secret_question").addOption(secret_question, false);
	$("#secret_question1").addOption(secret_question, false);
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('start/check_secret_answer');?>",
		success: function(data){
			if (data == "Need secret question and answer!") {
				$.jGrowl(data);
				$("#change_secret_answer_dialog").dialog('open');
			}
		}
	});
	$("#change_secret_answer_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 550, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'OK': function() {
				var a = $("#secret_question1");
				var b = $("#secret_answer1");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Secret Question");
				bValid = bValid && checkEmpty(b,"Secret Answer");
				if (bValid) {
					var str = $("#change_secret_answer_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('start/change_secret_answer');?>",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#change_secret_answer_form").clearForm();
								$("#change_secret_answer_dialog").dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#change_secret_answer_form").clearForm();
				$("#change_secret_answer_dialog").dialog('close');
			}
		}
	});
	$('.sigPad').signaturePad({drawOnly:true});
	$("#provider_info_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 800, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		open: function() {
			$("#provider_info_accordion").accordion({ heightStyle: "content" });
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('start/check_rcopia_extension');?>",
				success: function(data){
					if (data == 'y') {
						$('#rcopia_username').show();
					} else {
						$('#rcopia_username').hide();
						$('#rcopia_status').html('rCopia extension not enabled.');
					}
				}
			});
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('start/preview_signature');?>",
				success: function(data){
					$("#preview_signature").html(data);
				}
			});
		}
	});
	$("#provider_info").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('start/provider_info');?>",
			dataType: "json",
			success: function(data){
				$.each(data, function(key, value){
					$('#' + key).val(value);
				});
			}
		});
		$("#provider_info_dialog").dialog('open');
	});
	$("#specialty").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/specialty');?>",
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
	$("#license_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#upin").mask("aa9999999");
	$("#tax_id").mask("99-9999999");
	$("#save_provider_info").button({icons: {primary: "ui-icon-disk"}}).click(function(){
		var str = $("#provider_info_form").serialize();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('start/edit_provider_info');?>",
			data: str,
			success: function(data){
				$.jGrowl(data);
					$("#provider_info_form").clearForm();
					$("#provider_info_dialog").dialog('close');
			}
		});
	});
	$("#cancel_provider_info").button({icons: {primary: "ui-icon-close"}}).click(function(){
		$("#provider_info_form").clearForm();
		$("#provider_info_dialog").dialog('close');
	});
	$("#restore_database_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 320, 
		width: 500, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		}
	});
	$("#restore_database_link").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('backup/find_backups');?>",
			dataType: 'json',
			success: function(data){
				$("#backup_select").addOption(data.options);
			}
		});
		$("#restore_database_dialog").dialog('open');
	});
	$("#restore_backup_button").button().click(function(){
		var a = $("#backup_select").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('backup/restore');?>",
			data: "file=" + a,
			success: function(data){
				$.jGrowl(data);
				$("#restore_database_dialog").dialog('close');
			}
		});
	});
	
	$("#demographics_accordion").accordion();
	$("#guardian_import").button().click(function(){
		$('#menu_guardian_address').val($('#menu_address').val());
		$('#menu_guardian_city').val($('#menu_city').val());
		$('#menu_guardian_zip').val($('#menu_zip').val());
		$('#menu_guardian_phone_home').val($('#menu_phone_home').val());
		$('#menu_guardian_phone_cell').val($('#menu_phone_cell').val());
		$('#menu_guardian_phone_work').val($('#menu_phone_work').val());
	});
	$("#demographics_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/demographics');?>",
				dataType: "json",
				success: function(data){
					$("#menu_lastname").val(data.lastname);
					$("#menu_firstname").val(data.firstname);
					$("#menu_middle").val(data.middle);
					$("#menu_title").val(data.title);
					$("#menu_nickname").val(data.nickname);
					var dob = editDate1(data.DOB);
					$("#menu_DOB").val(dob);
					$("#menu_gender").val(data.sex);
					$("#menu_ss").val(data.ss);
					$("#menu_race").val(data.race);
					$("#menu_ethnicity").val(data.ethnicity);
					$("#menu_language").val(data.language);
					$("#menu_address").val(data.address);
					$("#menu_city").val(data.city);
					$("#menu_state").val(data.state);
					$("#menu_zip").val(data.zip);
					$("#menu_phone_home").val(data.phone_home);
					$("#menu_phone_work").val(data.phone_work);
					$("#menu_phone_cell").val(data.phone_cell);
					$("#menu_email").val(data.email);
					$("#menu_marital_status").val(data.marital_status);
					$("#menu_partner_name").val(data.partner_name);
					$("#menu_employer").val(data.employer);
					$("#menu_emergency_contact").val(data.emergency_contact);
					$("#menu_emergency_phone").val(data.emergency_phone);
					$("#menu_reminder_method").val(data.reminder_method);
					$("#menu_cell_carrier").val(data.cell_carrier);
					$("#menu_preferred_provider").val(data.preferred_provider);
					$("#menu_preferred_pharmacy").val(data.preferred_pharmacy);
					$("#menu_other1").val(data.other1);
					$("#menu_other2").val(data.other2);
					$("#menu_comments").val(data.comments);
					$("#menu_caregiver").val(data.caregiver);
					$("#menu_active").val(data.active);
					$("#menu_referred_by").val(data.referred_by);
					$("#menu_race_code").val(data.race_code);
					$("#menu_ethnicity_code").val(data.ethnicity_code);
					$("#menu_guardian_lastname").val(data.guardian_lastname);
					$("#menu_guardian_firstname").val(data.guardian_firstname);
					$("#menu_guardian_relationship").val(data.guardian_relationship);
					$("#menu_guardian_code").val(data.guardian_code);
					$("#menu_guardian_address").val(data.guardian_address);
					$("#menu_guardian_city").val(data.guardian_city);
					$("#menu_guardian_state").val(data.guardian_state);
					$("#menu_guardian_zip").val(data.guardian_zip);
					$("#menu_guardian_phone_home").val(data.guardian_phone_home);
					$("#menu_guardian_phone_work").val(data.guardian_phone_work);
					$("#menu_guardian_phone_cell").val(data.guardian_phone_cell);
					$("#menu_guardian_email").val(data.guardian_email);
					$("#menu_lang_code").val(data.lang_code);
					$("#menu_lastname").focus();
				}
			});
		},
		close: function(event, ui) {
			$('#edit_demographics_form').clearForm();
		}
	});
	$("#demographics_insurance_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#demographics_insurance_details").html("");
			jQuery("#demographics_insurance").jqGrid('GridUnload');
			jQuery("#demographics_insurance").jqGrid({
				url:"<?php echo site_url('patient/chartmenu/insurance/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Phone','Copay','Deductible','Comments','Address ID','Relationship'],
				colModel:[
					{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
					{name:'insurance_plan_name',index:'insurance_plan_name',width:450},
					{name:'insurance_id_num',index:'insurance_id_num',width:100},
					{name:'insurance_group',index:'insurance_group',width:100},
					{name:'insurance_order',index:'insurance_order',width:105},
					{name:'insurance_insu_lastname',index:'insurance_insu_lastname',width:1,hidden:true},
					{name:'insurance_insu_firstname',index:'insurance_insu_firstname',width:1,hidden:true},
					{name:'insurance_insu_dob',index:'insurance_insu_dob',width:1,hidden:true},
					{name:'insurance_insu_gender',index:'insurance_insu_gender',width:1,hidden:true},
					{name:'insurance_insu_address',index:'insurance_insu_address',width:1,hidden:true},
					{name:'insurance_insu_city',index:'insurance_insu_city',width:1,hidden:true},
					{name:'insurance_insu_state',index:'insurance_insu_state',width:1,hidden:true},
					{name:'insurance_insu_zip',index:'insurance_insu_zip',width:1,hidden:true},
					{name:'insurance_insu_phone',index:'insurance_insu_phone',width:1,hidden:true},
					{name:'insurance_copay',index:'insurance_copay',width:1,hidden:true},
					{name:'insurance_deductible',index:'insurance_deductible',width:1,hidden:true},
					{name:'insurance_comments', index:'insurance_comments',width:1,hidden:true},
					{name:'address_id',index:'address_id',width:1,hidden:true},
					{name:'insurance_relationship',index:'insurance_relationship',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#demographics_insurance_pager'),
				sortname: 'insurance_order',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Insurance Payors",
			 	height: "100%",
			 	onSelectRow: function(id){
			 		var copay = jQuery("#demographics_insurance").getCell(id,'insurance_copay');
					var deductible = jQuery("#demographics_insurance").getCell(id,'insurance_deductible');
					var comments = jQuery("#demographics_insurance").getCell(id,'insurance_comments');
					var text = '<strong>Additional insurance information for ' + jQuery("#demographics_insurance").getCell(id,'insurance_plan_name') + ":</strong><br>";
					if(copay != ''){
						text += "Copay: " + copay + "<br>";
					}
					if(deductible != ''){
						text += "Deductible: " + deductible + "<br>";
					}
					if (comments != ''){
						text += "Comments: " + comments;
					}
					$("#demographics_insurance_details").html(text);
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#demographics_insurance_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#demographics_insurance_inactive").jqGrid('GridUnload')
			jQuery("#demographics_insurance_inactive").jqGrid({
				url:"<?php echo site_url('patient/chartmenu/insurance_inactive/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Copay','Deductible','Comments','Address ID','Relationship'],
				colModel:[
					{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
					{name:'insurance_plan_name',index:'insurance_plan_name',width:450},
					{name:'insurance_id_num',index:'insurance_id_num',width:100},
					{name:'insurance_group',index:'insurance_group',width:100},
					{name:'insurance_order',index:'insurance_order',width:105},
					{name:'insurance_insu_lastname',index:'insurance_insu_lastname',width:1,hidden:true},
					{name:'insurance_insu_firstname',index:'insurance_insu_firstname',width:1,hidden:true},
					{name:'insurance_insu_dob',index:'insurance_insu_dob',width:1,hidden:true},
					{name:'insurance_insu_gender',index:'insurance_insu_gender',width:1,hidden:true},
					{name:'insurance_insu_address',index:'insurance_insu_address',width:1,hidden:true},
					{name:'insurance_insu_city',index:'insurance_insu_city',width:1,hidden:true},
					{name:'insurance_insu_state',index:'insurance_insu_state',width:1,hidden:true},
					{name:'insurance_insu_zip',index:'insurance_insu_zip',width:1,hidden:true},
					{name:'insurance_copay',index:'insurance_copay',width:1,hidden:true},
					{name:'insurance_deductible',index:'insurance_deductible',width:1,hidden:true},
					{name:'insurance_comments', index:'insurance_comments',width:1,hidden:true},
					{name:'address_id',index:'address_id',width:1,hidden:true},
					{name:'insurance_relationship',index:'insurance_relationship',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#demographics_insurance_inactive_pager'),
				sortname: 'insurance_plan_name',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Inactive Insurance Payors",
			 	height: "100%",
			 	hiddengrid: true,
			 	onSelectRow: function(id){
			 		var copay = jQuery("#demographics_insurance").getCell(id,'insurance_copay');
					var deductible = jQuery("#demographics_insurance").getCell(id,'insurance_deductible');
					var comments = jQuery("#demographics_insurance").getCell(id,'insurance_comments');
					var text = '<strong>Additional insurance information for ' + jQuery("#demographics_insurance").getCell(id,'insurance_plan_name') + ":</strong><br>";
					if(copay != ''){
						text += "Copay: " + copay + "<br>";
					}
					if(deductible != ''){
						text += "Deductible: " + deductible + "<br>";
					}
					if (comments != ''){
						text += "Comments: " + comments;
					}
					$("#demographics_insurance_details").html(text);
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#demographics_insurance_inactive_pager',{search:false,edit:false,add:false,del:false});
			$.ajax({
				url: "<?php echo site_url('search/insurance3');?>",
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#menu_insurance_plan_select").addOption({"":"Select or add insurance provider."});
						$("#menu_insurance_plan_select").addOption(data.message);
					} else {
						$("#menu_insurance_plan_select").addOption({"":"No insurance providers.  Click Add."});
					}
				}
			});
		},
		close: function(event, ui) {
			$("#edit_menu_insurance_main_form").clearForm();
		}
	});
	$("#menu_address").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/address');?>",
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
	$("#menu_city").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/city');?>",
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
	$("#menu_guardian_relationship").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/guardian_relationship');?>",
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
		select: function( event, ui ) {
			$("#menu_guardian_code").val(ui.item.code);
		}
	});
	$("#menu_guardian_address").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/address');?>",
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
	$("#menu_guardian_city").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/city');?>",
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
	$("#menu_preferred_provider").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/provider');?>",
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
	$("#menu_preferred_pharmacy").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/pharmacy1');?>",
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
	var gender = {"m":"Male","f":"Female"};
	$("#menu_gender").addOption(gender, false);
	var marital = {"":"","Single":"Single","Married":"Married","Common law":"Common law","Domestic partner":"Domestic partner","Registered domestic partner":"Registered domestic partner","Interlocutory":"Interlocutory","Living together":"Living together","Legally Separated":"Legally Separated","Divorced":"Divorced","Separated":"Separated","Widowed":"Widowed","Other":"Other","Unknown":"Unknown","Unmarried":"Unmarried","Unreported":"Unreported"};
	$("#menu_marital_status").addOption(marital, false);
	$("#menu_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#menu_guardian_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#menu_DOB").mask("99/99/9999");
	$("#menu_DOB").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#menu_ss").mask("999-99-9999");
	var race_options = [
		{
			value: "American Indian or Alaska Native",
			label: "American Indian or Alaska Native",
			code: "1002-5"
		},
		{
			value: "Asian",
			label: "Asian",
			code: "2028-9"
		},
		{
			value: "Black or African American",
			label: "Black or African American",
			code: "2054-5"
		},
		{
			value: "Native Hawaiian or Other Pacific Islander",
			label: "Native Hawaiian or Other Pacific Islander",
			code: "2076-8"
		},
		{
			value: "White",
			label: "White",
			code: "2106-3"
		}
	];
	var ethnicity_options = [
		{
			value: "Hispanic or Latino",
			label: "Hispanic or Latino",
			code: "2135-2"
		},
		{
			value: "Not Hispanic or Latino",
			label: "Not Hispanic or Latino",
			code: "2186-5"
		}
	];
	$("#menu_race").autocomplete({
		source: race_options,
		minLength: 0,
		delay: 0,
		select: function( event, ui ) {
			$("#menu_race_code").val(ui.item.code);
		}
	}).click(function () {
		$(this).autocomplete("search", "");
	});
	$("#menu_ethnicity").autocomplete({
		source: ethnicity_options,
		minLength: 0,
		delay: 0,
		select: function( event, ui ) {
			$("#menu_ethnicity_code").val(ui.item.code);
		}
	}).click(function () {
		$(this).autocomplete("search", "");
	});
	$("#menu_language").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/language');?>",
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
		select: function( event, ui ) {
			$("#menu_lang_code").val(ui.item.code);
		}
	});
	$("#menu_phone_home").mask("(999) 999-9999");
	$("#menu_phone_work").mask("(999) 999-9999");
	$("#menu_phone_cell").mask("(999) 999-9999");
	$("#menu_guardian_phone_home").mask("(999) 999-9999");
	$("#menu_guardian_phone_work").mask("(999) 999-9999");
	$("#menu_guardian_phone_cell").mask("(999) 999-9999");
	$("#menu_emergency_phone").mask("(999) 999-9999");
	$("#menu_reminder_method").addOption({"":"","Email":"Email","Cellular Phone":"Cellular Phone"}, false);
	$("#menu_cell_carrier").addOption({"":"","txt.att.net":"AT&T","sms.mycricket.com":"Cricket","messaging.nextel.com":"Nextel","qwestmp.com":"Qwest","messaging.sprintpcs.com":"Sprint(PCS)","number@page.nextel.com":"Sprint(Nextel)","tmomail.net":"T-Mobile","email.uscc.net":"US Cellular","vtext.com":"Verizon","vmobl.com":"Virgin Mobile"}, false);
	$("#menu_active").addOption({"1":"Active","0":"Inactive"}, false);
	$("#patient_demographics").click(function() {
		$("#demographics_list_dialog").dialog('open');
	});
	$("#save_menu_demographics").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_menu_demographics").click(function() {
		var a = $("#menu_reminder_method").val();
		var b = $("#menu_cell_carrier").val();
		if (a == "Cellular Phone") {
			if (b != "") {
				var str = $("#edit_demographics_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('patient/chartmenu/edit_demographics');?>",
						data: str,
						success: function(data){
							if (data == 'Close Chart') {
								window.location = "<?php echo site_url();?>";
							} else {
								$.jGrowl(data);
							}
						}
					});
				} else {
					$.jGrowl("Please complete the form.");
				}
			} else {
				$.jGrowl("Cellular carrier needs to be completed for cellular phone appointment reminders!");
			}
		} else {
			var str = $("#edit_demographics_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('patient/chartmenu/edit_demographics');?>",
					data: str,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#save_menu_demographics1").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_menu_demographics1").click(function() {
		var a = $("#menu_reminder_method").val();
		var b = $("#menu_cell_carrier").val();
		if (a == "Cellular Phone") {
			if (b != "") {
				var str = $("#edit_demographics_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('patient/chartmenu/edit_demographics');?>",
						data: str,
						success: function(data){
							if (data == 'Close Chart') {
								window.location = "<?php echo site_url();?>";
							} else {
								$.jGrowl(data);
								$("#demographics_list_dialog").dialog('close');
							}
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			} else {
				$.jGrowl("Cellular carrier needs to be completed for cellular phone appointment reminders!");
			}
		} else {
			var str = $("#edit_demographics_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('patient/chartmenu/edit_demographics');?>",
					data: str,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							$("#demographics_list_dialog").dialog('close');
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_menu_demographics").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_menu_demographics").click(function() {
		$("#edit_demographics_form").clearForm();
		$("#demographics_list_dialog").dialog('close');
	});
	$("#insurance_menu_demographics").button({
		icons: {
			primary: "ui-icon-suitcase"
		}
	});
	$("#insurance_menu_demographics").click(function() {
		$("#demographics_insurance_dialog").dialog('open');
	});
	
	$("#demographics_add_insurance").button();
	$("#demographics_add_insurance").click(function(){
		$('#edit_menu_insurance_main_form').clearForm();
		$('#menu_insurance_plan_select').val('');
		$("#add_insurance_plan span").text("Add Insurance Provider");
		$('#menu_insurance_main_dialog').dialog('open');
	});
	$("#demographics_edit_insurance").button();
	$("#demographics_edit_insurance").click(function(){
		var item = jQuery("#demographics_insurance").getGridParam('selrow');
		if(item){
			jQuery("#demographics_insurance").GridToForm(item,"#edit_menu_insurance_main_form");
			var dob1 = $("#menu_insurance_insu_dob").val();
			var dob = editDate1(dob1);
			$("#menu_insurance_insu_dob").val(dob);
			$("#menu_insurance_plan_name").val($("#menu_insurance_plan_select option:selected").text());
			$("#add_insurance_plan span").text("Edit Insurance Provider");
			$('#menu_insurance_main_dialog').dialog('open');
		} else {
			$.jGrowl("Please select insurance to edit!")
		}
	});
	$("#demographics_inactivate_insurance").button();
	$("#demographics_inactivate_insurance").click(function(){
		var item = jQuery("#demographics_insurance").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/inactivate_insurance');?>",
				data: "insurance_id=" + item,
				success: function(data){
					if (data == 'Close Chart') {
						window.location = "<?php echo site_url();?>";
					} else {
						$.jGrowl(data);
						jQuery("#demographics_insurance").trigger("reloadGrid");
						jQuery("#demographics_insurance_inactive").trigger("reloadGrid");
						jQuery("#messages_lab_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_rad_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_cp_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_ref_insurance_grid").trigger("reloadGrid");
					}
				}
			});
			jQuery("#demographics_insurance").delRowData(item);
		} else {
			$.jGrowl("Please select insurance to inactivate!")
		}
	});
	$("#demographics_delete_insurance").button();
	$("#demographics_delete_insurance").click(function(){
		var item = jQuery("#demographics_insurance").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this insurance?  This is not recommended unless entering the insurance was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('patient/chartmenu/delete_insurance');?>",
					data: "insurance_id=" + item,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							jQuery("#demographics_insurance").trigger("reloadGrid");
							jQuery("#demographics_insurance_inactive").trigger("reloadGrid");
							jQuery("#messages_lab_insurance_grid").trigger("reloadGrid");
							jQuery("#messages_rad_insurance_grid").trigger("reloadGrid");
							jQuery("#messages_cp_insurance_grid").trigger("reloadGrid");
							jQuery("#messages_ref_insurance_grid").trigger("reloadGrid");
						}
					}
				});
			}
		} else {
			$.jGrowl("Please select insurance to delete!")
		}
	});
	$("#demographics_reactivate_insurance").button();
	$("#demographics_reactivate_insurance").click(function(){
		var item = jQuery("#demographics_insurance_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/reactivate_insurance');?>",
				data: "insurance_id=" + item,
				success: function(data){
					if (data == 'Close Chart') {
						window.location = "<?php echo site_url();?>";
					} else {
						$.jGrowl(data);
						jQuery("#demographics_insurance_inactive").trigger("reloadGrid");
						jQuery("#demographics_insurance").trigger("reloadGrid");
						jQuery("#messages_lab_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_rad_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_cp_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_ref_insurance_grid").trigger("reloadGrid");
					}
				}
			});
		} else {
			$.jGrowl("Please select medication to reactivate!")
		}
	});
	$("#menu_insurance_main_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#menu_insurance_plan_select").focus();
		},
		buttons: {
			'Save': function() {
				var plan_name = $("#menu_insurance_plan_name");
				var id_num = $("#menu_insurance_id_num");
				var lastname = $("#menu_insurance_insu_lastname");
				var firstname = $("#menu_insurance_insu_firstname");
				var relationship = $("#menu_insurance_relationship");
				var address = $("#menu_insurance_insu_address");
				var city = $("#menu_insurance_insu_city");
				var state = $("#menu_insurance_insu_state");
				var zip = $("#menu_insurance_insu_zip");
				var bValid = true;
				bValid = bValid && checkEmpty(plan_name,"Insurance Plan name");
				bValid = bValid && checkEmpty(id_num,"ID Number");
				bValid = bValid && checkEmpty(lastname,"Insured Last Name");
				bValid = bValid && checkEmpty(firstname,"Insured First Name");
				bValid = bValid && checkEmpty(relationship,"Relationship");
				bValid = bValid && checkEmpty(address,"Insured Address");
				bValid = bValid && checkEmpty(city,"Insured City");
				bValid = bValid && checkEmpty(state,"Insured State");
				bValid = bValid && checkEmpty(zip,"Insured Zip");
				if (bValid) {
					var str = $("#edit_menu_insurance_main_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('patient/chartmenu/edit_insurance');?>",
							data: str,
							success: function(data){
								if (data == 'Close Chart') {
									window.location = "<?php echo site_url();?>";
								} else {
									$.jGrowl(data);
									$("#edit_menu_insurance_main_form").clearForm();
									$("#menu_insurance_main_dialog").dialog('close');
									jQuery("#demographics_insurance").trigger("reloadGrid");
									jQuery("#messages_lab_insurance_grid").trigger("reloadGrid");
									jQuery("#messages_rad_insurance_grid").trigger("reloadGrid");
									jQuery("#messages_cp_insurance_grid").trigger("reloadGrid");
									jQuery("#messages_ref_insurance_grid").trigger("reloadGrid");
								}
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_menu_insurance_main_form').clearForm();
				$("#menu_insurance_main_dialog").dialog('close');
			}
		},
		close: function(event, ui) {
			$('#edit_menu_insurance_main_form').clearForm();
		}
	});
	$("#demographics_insurance_plan_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#menu_insurance_plan_facility").focus();
			var id = $("#menu_insurance_plan_select").val();
			if (id != "") {
				$("#demographics_insurance_plan_dialog").dialog("option", "title", "Edit Insurance Provider");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/insurance1');?>",
					data: "address_id=" + id,
					dataType: "json",
					success: function(data){
						$("#menu_insurance_plan_facility").val(data.facility);
						$("#menu_insurance_plan_address").val(data.street_address1);
						$("#menu_insurance_plan_address2").val(data.street_address2);
						$("#menu_insurance_plan_city").val(data.city);
						$("#menu_insurance_plan_state").val(data.state);
						$("#menu_insurance_plan_zip").val(data.zip);
						$("#menu_insurance_plan_phone").val(data.phone);
						$("#menu_insurance_plan_payor_id").val(data.insurance_plan_payor_id);
						$("#menu_insurance_plan_type").val(data.insurance_plan_type);
						$("#menu_insurance_plan_assignment").val(data.insurance_plan_assignment);
						$("#menu_insurance_plan_ppa_phone").val(data.insurance_plan_ppa_phone);
						$("#menu_insurance_plan_ppa_fax").val(data.insurance_plan_ppa_fax);
						$("#menu_insurance_plan_ppa_url").val(data.insurance_plan_ppa_url);
						$("#menu_insurance_plan_mpa_phone").val(data.insurance_plan_mpa_phone);
						$("#menu_insurance_plan_mpa_fax").val(data.insurance_plan_mpa_fax);
						$("#menu_insurance_plan_mpa_url").val(data.insurance_plan_mpa_url);
						$("#menu_insurance_plan_address_id").val(data.address_id);
					}
				});
			} else {
				$("#demographics_insurance_plan_dialog").attr("option", "title", "Add Insurance Provider");
			}
		},
		buttons: {
			'Save': function() {
				var a = $("#menu_insurance_plan_facility");
				var b = $("#menu_insurance_plan_type");
				var c = $("#menu_insurance_plan_assignment");
				var d = $("#menu_insurance_plan_address");
				var e = $("#menu_insurance_plan_city");
				var f = $("#menu_insurance_plan_state");
				var g = $("#menu_insurance_plan_zip");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Insurance Plan Name");
				bValid = bValid && checkEmpty(b,"Insurance Plan Type");
				bValid = bValid && checkEmpty(c,"Accept Assignment");
				bValid = bValid && checkEmpty(d,"Street Address");
				bValid = bValid && checkEmpty(e,"City");
				bValid = bValid && checkEmpty(f,"State");
				bValid = bValid && checkEmpty(g,"Zip");
				if (bValid) {
					var str = $("#edit_menu_insurance_plan_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('patient/chartmenu/edit_insurance_provider');?>",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								$("#menu_insurance_plan_select").removeOption(/./);
								$.ajax({
									url: "<?php echo site_url('search/insurance3');?>",
									dataType: "json",
									type: "POST",
									success: function(data1){
										if(data1.response =='true'){
											$("#menu_insurance_plan_select").addOption(data1.message);
											$("#menu_insurance_plan_select").val(data.id);
											$("#menu_insurance_plan_name").val($("#menu_insurance_plan_select option:selected").text());
											$("#demographics_insurance_plan_dialog").dialog('close');
											$("#demographics_insurance_plan_dialog").dialog("option", "title", "");
										}
									}
								});
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_menu_insurance_plan_form').clearForm();
				$("#demographics_insurance_plan_dialog").dialog('close');
				$("#demographics_insurance_plan_dialog").dialog("option", "title", "");
			}
		},
		close: function(event, ui) {
			$('#edit_menu_insurance_plan_form').clearForm();
			$("#demographics_insurance_plan_dialog").dialog("option", "title", "");
		}
	});
	$("#add_insurance_plan").button().click(function(){
		$("#demographics_insurance_plan_dialog").dialog('open');
	});
	$('#menu_insurance_plan_select').change(function() {
		if ($(this).val() != ""){
			$("#menu_insurance_plan_name").val($("#menu_insurance_plan_select option:selected").text());
			$("#add_insurance_plan span").text("Edit Insurance Provider");
		} else {
			$("#add_insurance_plan span").text("Add Insurance Provider");
		}
	});
	$("#menu_insurance_insu_city").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/city');?>",
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
	$("#menu_insurance_plan_city").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/city');?>",
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
	$("#menu_insurance_insu_gender").addOption(gender, false);
	$("#menu_insurance_order").addOption({"":"","Primary":"Primary","Secondary":"Secondary","Unassigned":"Unassigned"}, false);
	$("#menu_insurance_relationship").addOption({"":"","Self":"Self","Spouse":"Spouse","Child":"Child","Other":"Other"}, false);
	$("#menu_insurance_plan_type").addOption({"":"","Other":"Other","Medicare":"Medicare","Medicaid":"Medicaid","Tricare":"Tricare"}, false);
	$("#menu_insurance_plan_assignment").addOption({"":"","No":"No","Yes":"Yes"}, false);
	$("#menu_insurance_insu_dob").mask("99/99/9999");
	$("#menu_insurance_insu_dob").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#menu_insurance_insu_phone").mask("(999) 999-9999");
	$("#menu_insurance_plan_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#menu_insurance_insu_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#menu_insurance_plan_phone").mask("(999) 999-9999");
	$("#menu_insurance_plan_ppa_phone").mask("(999) 999-9999");
	$("#menu_insurance_plan_ppa_fax").mask("(999) 999-9999");
	$("#menu_insurance_plan_mpa_phone").mask("(999) 999-9999");
	$("#menu_insurance_plan_mpa_fax").mask("(999) 999-9999");
	$("#menu_insurance_relationship").change(function(){
		if($("#menu_insurance_relationship").val() == "Self") {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/copy_address');?>",
				dataType: "json",
				success: function(data){
					$("#menu_insurance_insu_lastname").val(data.lastname);
					$("#menu_insurance_insu_firstname").val(data.firstname);
					var dob = editDate1(data.DOB);
					$("#menu_insurance_insu_dob").val(dob);
					$("#menu_insurance_insu_gender").val(data.sex);
					$("#menu_insurance_insu_address").val(data.address);
					$("#menu_insurance_insu_city").val(data.city);
					$("#menu_insurance_insu_state").val(data.state);
					$("#menu_insurance_insu_zip").val(data.zip);
					if (data.phone_home != '') {
						$("#menu_insurance_insu_phone").val(data.phone_home);
					} else {
						$("#menu_insurance_insu_phone").val(data.phone_cell);
					}
				}
			});
		}
	});	
	$("#insurance_copy").button();
	$("#insurance_copy").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('patient/chartmenu/copy_address');?>",
			dataType: "json",
			success: function(data){
				$("#menu_insurance_insu_address").val(data.address);
				$("#menu_insurance_insu_city").val(data.city);
				$("#menu_insurance_insu_state").val(data.state);
				$("#menu_insurance_insu_zip").val(data.zip);
				if (data.phone_home != '') {
					$("#menu_insurance_insu_phone").val(data.phone_home);
				} else {
					$("#menu_insurance_insu_phone").val(data.phone_cell);
				}
			}
		});
	});
	$("#assistant_test_reconcile").click(function(){
		$("#tests_reconcile_dialog").dialog('open');
	});
	$("#provider_test_reconcile").click(function(){
		$("#tests_reconcile_dialog").dialog('open');
	});
	$("#tests_reconcile_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		open: function(event, ui) {
			jQuery("#tests_reconcile_list").jqGrid('GridUnload');
			jQuery("#tests_reconcile_list").jqGrid({
				url:"<?php echo site_url('start/tests/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Patient','Test','Result','Unit','Normal','Flags','Type'],
				colModel:[
					{name:'tests_id',index:'tests_id',width:1,hidden:true},
					{name:'test_datetime',index:'test_datetime',width:75,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'test_unassigned',index:'test_unassigned',width:110},
					{name:'test_name',index:'test_name',width:200},
					{name:'test_result',index:'test_result',width:120},
					{name:'test_units',index:'test_units',width:50},
					{name:'test_reference',index:'test_reference',width:100},
					{name:'test_flags',index:'test_flags',width:50,
						cellattr: function (rowId, val, rawObject, cm, rdata) {
							if (rawObject.test_flags == "L") {
								var response = "Below low normal";
							}
							if (rawObject.test_flags == "H") {
								var response = "Above high normal";
							}
							if (rawObject.test_flags == "LL") {
								var response = "Below low panic limits";
							}
							if (rawObject.test_flags == "HH") {
								var response = "Above high panic limits";
							}
							if (rawObject.test_flags == "<") {
								var response = "Below absolute low-off instrument scale";
							}
							if (rawObject.test_flags == ">") {
								var response = "Above absolute high-off instrument scale";
							}
							if (rawObject.test_flags == "N") {
								var response = "Normal";
							}
							if (rawObject.test_flags == "A") {
								var response = "Abnormal";
							}
							if (rawObject.test_flags == "AA") {
								var response = "Very abnormal";
							}
							if (rawObject.test_flags == "U") {
								var response = "Significant change up";
							}
							if (rawObject.test_flags == "D") {
								var response = "Significant change down";
							}
							if (rawObject.test_flags == "B") {
								var response = "Better";
							}
							if (rawObject.test_flags == "W") {
								var response = "Worse";
							}
							if (rawObject.test_flags == "S") {
								var response = "Susceptible";
							}
							if (rawObject.test_flags == "R") {
								var response = "Resistant";
							}
							if (rawObject.test_flags == "I") {
								var response = "Intermediate";
							}
							if (rawObject.test_flags == "MS") {
								var response = "Moderately susceptible";
							}
							if (rawObject.test_flags == "VS") {
								var response = "Very susceptible";
							}
							if (rawObject.test_flags == "") {
								var response = "";
							}
							return 'title="' + response + '"';
						}
					},
					{name:'test_type',index:'test_type',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#tests_reconcile_list_pager'),
				sortname: 'test_datetime',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Test Results",
			 	height: "100%",
			 	gridview: true,
			 	multiselect: true,
				multiboxonly: true,
			 	rowattr: function (rd) {
					if (rd.test_flags == "HH" || rd.test_flags == "LL" || rd.test_flags == "H" || rd.test_flags == "L") {
						return {"class": "myAltRowClass"};
					}
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#tests_reconcile_list_pager',{search:false,edit:false,add:false,del:false});
		}
	});
	$("#reconcile_tests").button({
		icons: {
			primary: "ui-icon-disk"
		}
	}).click(function(){
		var click_id = jQuery("#tests_reconcile_list").getGridParam('selarrrow');
		if(click_id.length > 0){
			$("#reconcile_tests_pid").val('');
			$("#scan_patient_search1").val('');
			$("#reconcile_tests_div").show();
			$("#reconcile_test_patient_search1").focus();
		} else {
			$.jGrowl("Choose test to reconcile!");
		}
	});
	$("#reconcile_test_patient_search1").autocomplete({
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
			$("#reconcile_tests_pid").val(ui.item.id);
		}
	});
	$("#reconcile_tests_send").button({
		icons: {
			primary: "ui-icon-disk"
		}
	}).click(function(){
		var click_id = jQuery("#tests_reconcile_list").getGridParam('selarrrow');
		var pid = $("#reconcile_tests_pid").val();
		if(click_id){
			var json_flat = JSON.stringify(click_id);
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('start/tests_import');?>",
				data: "tests_id_array=" + json_flat + "&pid=" + pid,
				success: function(data){
					$.jGrowl('Imported ' + data + ' tests!');
					$("#reconcile_tests_pid").val('');
					$("#reconcile_test_patient_search1").val('');
					$("#reconcile_tests_div").hide();
					jQuery("#tests_reconcile_list").trigger("reloadGrid");
				}
			});
		}
	});
	$("#reconcile_tests_cancel").button({
		icons: {
			primary: "ui-icon-close"
		}
	}).click(function(){
		$("#reconcile_tests_pid").val('');
			$("#reconcile_test_patient_search1").val('');
			$("#reconcile_tests_div").hide();
	});
	$("#delete_tests").button({
		icons: {
			primary: "ui-icon-trash"
		}
	}).click(function(){
		var click_id = jQuery("#tests_reconcile_list").getGridParam('selarrrow');
		if(click_id.length > 0){
			if(confirm('Are you sure you want to delete the selected tests?')){ 
				var count = click_id.length;
				for (var i = 0; i < count; i++) {
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('start/delete_tests');?>",
						data: "tests_id=" + click_id[i],
						success: function(data){
						}
					});
				}
				$.jGrowl('Deleted ' + i + ' tests!');
				jQuery("#tests_reconcile_list").trigger("reloadGrid");
			}
		} else {
			$.jGrowl("Please select test to delete!");
		}
	});
	$("#print_entire_charts_progressbar").progressbar({
		value: false,
		change: function() {
			var value = $("#print_entire_charts_progressbar").progressbar("option", "value");
			$(".print_entire_charts_progressbar_label").text(value + "%" );
		},
		complete: function() {
			$(".print_entire_charts_progressbar_label").text( "Complete!" );
		}
	});
	$("#print_entire_charts").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/chartmenu/check_print_entire_chart');?>",
			success: function(data){
				if (data == "OK") {
					$("#print_entire_charts_progress_div").show();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('admin/chartmenu/print_entire_chart');?>",
						dataType: 'json',
						success: function(data1){
							if (data1.message="OK") {
								$("#print_download").html(data1.html);
							}
						}
					});
					setTimeout(print_chart_progress, 1000);
				} else {
					$.jGrowl(data);
				}
			}
		});
	}).tooltip({ content: "Clicking on this will create a ZIP file with individual PDF files of complete medical records for every patient in your practice." });
	function print_chart_progress() {
		var val = $("#print_entire_charts_progressbar").progressbar("option", "value" ) || 0;
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/chartmenu/print_entire_chart_progress');?>",
			success: function(data){
				$("#print_entire_charts_progressbar").progressbar("option","value", parseInt(data));
				if (data < 99) {
					setTimeout(print_chart_progress, 1000);
				}
			}
		});
	}
	$("#generate_csv_patient_demographics").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/chartmenu/check_csv_patient_demographics');?>",
			success: function(data){
				if (data == "OK") {
					$("#print_entire_charts_progress_div").show();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('admin/chartmenu/generate_csv_patient_demographics');?>",
						dataType: 'json',
						success: function(data1){
							if (data1.message="OK") {
								$("#print_download").html(data1.html);
							}
						}
					});
					setTimeout(csv_progress, 1000);
				} else {
					$.jGrowl(data);
				}
			}
		});
	}).tooltip({ content: "Clicking on this will create a CSV file of demographic information for every patient in your practice." });
	function csv_progress() {
		var val = $("#print_entire_charts_progressbar").progressbar("option", "value" ) || 0;
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/chartmenu/csv_progress');?>",
			success: function(data){
				$("#print_entire_charts_progressbar").progressbar("option","value", parseInt(data));
				if (data < 99) {
					setTimeout(csv_progress, 1000);
				}
			}
		});
	}
	$(".dash_tip").tooltip();
});
</script>
<?php if(user_group('admin') || user_group('provider') || user_group('assistant') || user_group('billing')) { echo '<div id ="heading2"></div>';}?>
<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="maincontent_full">
		<h4>Welcome <?php echo $displayname;?>.</h4>
		<table>
			<tr>
				<td valign="top">
					<table class="table_text">
						<tr><td><img src="<?php echo base_url(). 'images/usersadmin.png';?>" height="40" width="40" border="0"></td><td>If this is your first time logging in, you should <a href="#" id="change_password">change your password</a>.</td></tr>
						<?php if(user_group('admin')) { echo '<tr><td><img src="' . base_url().'images/control.png' . '" height="40" width="40" border="0"></td><td>' . anchor('admin/setup', 'Modify clinic settings') . '.</td></tr>';}?>
						<?php if(user_group('admin')) { echo '<tr><td><img src="' . base_url().'images/usersadmin.png' . '" height="40" width="40" border="0"></td><td>' . anchor('admin/users', 'Administer users') . '.</td></tr>';}?>
						<?php if(user_group('admin')) { echo '<tr><td><img src="' . base_url().'images/schedule.png' . '" height="40" width="40" border="0"></td><td>' . anchor('admin/schedule', 'Setup schedule') . '.</td></tr>';}?>
						<?php if(user_group('admin')) { echo '<tr><td><img src="' . base_url().'images/newencounter.png' . '" height="40" width="40" border="0"></td><td>' . anchor('admin/logs', 'View system logs') . '.</td></tr>';}?>
						<?php if(user_group('admin')) { echo '<tr><td><img src="' . base_url().'images/printmgr.png' . '" height="40" width="40" border="0"></td><td><a href="#" id="print_entire_charts" title="">Export All Charts</a>.</td></tr>';}?>
						<?php if(user_group('admin')) { echo '<tr><td><img src="' . base_url().'images/download.png' . '" height="40" width="40" border="0"></td><td><a href="#" id="generate_csv_patient_demographics" title="">Export All Patient Demographics</a>.</td></tr>';}?>
						<?php if(user_group('admin') && $saas_admin == "y") { echo '<tr><td><img src="' . base_url().'images/kdisknav.png' . '" height="40" width="40" border="0"></td><td><a href="#" id="restore_database_link">Restore the database</a>.</td></tr>';}?>
						<?php if(user_group('provider')) { echo '<tr><td><img src="' . base_url().'images/personal.png' . '" height="40" width="40" border="0"></td><td><a href="#" id="provider_info">Update your user information</a>.</td></tr>';}?>
						<?php if(user_group('provider')) { echo '<tr><td><img src="' . base_url().'images/email.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_messages . ' ' . anchor('provider/messaging', 'messages to view') . '.</td></tr>';}?>
						<?php if(user_group('provider')) { echo '<tr><td><img src="' . base_url().'images/scanner.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_documents . ' ' . anchor('provider/messaging', 'new documents from the fax or scanner to view') . '.</td></tr>';}?>
						<?php if(user_group('provider')) { echo '<tr><td><img src="' . base_url().'images/schedule.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_appts . ' ' . anchor('provider/schedule', 'pending appointments today') . '.</td></tr>';}?>
						<?php if(user_group('provider')) { echo '<tr><td><img src="' . base_url().'images/chart1.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_drafts . ' <a href="#" id="provider_draft">unsigned messages and encounters</a>.</td></tr>';}?>
						<?php if(user_group('provider')) { echo '<tr><td><img src="' . base_url().'images/important.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_reminders . ' <a href="#" id="provider_alerts">reminders</a>.</td></tr>';}?>
						<?php if(user_group('provider')) { echo '<tr><td><img src="' . base_url().'images/science.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_tests . ' <a href="#" id="provider_test_reconcile">test results to reconcile</a>.</td></tr>';}?>
						<?php if(user_group('provider') && $mtm_alerts_status == "y") { echo '<tr><td><img src="' . base_url().'images/search.png' . '" height="40" width="40" border="0"></td><td>You have ' . $mtm_alerts . ' <a href="#" id="provider_mtm_alerts">patients on Medical Therapy Management</a>.</td></tr>';}?>
						<?php if(user_group('provider')) { echo '<tr><td><img src="' . base_url().'images/billing.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_bills . ' ' . anchor('provider/billing', 'new bills to process and send') . '.</td></tr>';}?>
						<?php if(user_group('assistant')) { echo '<tr><td><img src="' . base_url().'images/email.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_messages . ' ' . anchor('assistant/messaging', 'messages to view') . '.</td></tr>';}?>
						<?php if(user_group('assistant')) { echo '<tr><td><img src="' . base_url().'images/scanner.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_documents . ' ' . anchor('assistant/messaging', 'new documents from the fax or scanner to view') . '.</td></tr>';}?>
						<?php if(user_group('assistant')) { echo '<tr><td><img src="' . base_url().'images/chart1.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_drafts . ' <a href="#" id="assistant_draft">unsigned messages</a>.</td></tr>';}?>
						<?php if(user_group('assistant')) { echo '<tr><td><img src="' . base_url().'images/important.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_reminders . ' <a href="#" id="assistant_alerts">reminders</a>.</td></tr>';}?>
						<?php if(user_group('assistant')) { echo '<tr><td><img src="' . base_url().'images/science.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_tests . ' <a href="#" id="assistant_test_reconcile">test results to reconcile</a>.</td></tr>';}?>
						<?php if(user_group('assistant')) { echo '<tr><td><img src="' . base_url().'images/billing.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_bills . ' ' . anchor('assistant/billing', 'new bills to review') . '.</td></tr>';}?>
						<?php if(user_group('billing')) { echo '<tr><td><img src="' . base_url().'images/email.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_messages . ' ' . anchor('billing/messaging', 'messages to view') . '.</td></tr>';}?>
						<?php if(user_group('billing')) { echo '<tr><td><img src="' . base_url().'images/scanner.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_documents . ' ' . anchor('billing/messaging', 'new documents from the fax or scanner to view') . '.</td></tr>';}?>
						<?php if(user_group('billing')) { echo '<tr><td><img src="' . base_url().'images/schedule.png' . '" height="40" width="40" border="0"></td><td>' . anchor('biller/schedule', 'Clinic schedule') . '.</td></tr>';}?>
						<?php if(user_group('billing')) { echo '<tr><td><img src="' . base_url().'images/billing.png' . '" height="40" width="40" border="0"></td><td>You have ' . $number_bills . ' ' . anchor('billing/billing', 'new bills to process and send') . '.</td></tr>';}?>
						<?php if(user_group('patient')) { echo '<tr><td><img src="' . base_url().'images/personal.png' . '" height="40" width="40" border="0"></td><td><a href="#" id="patient_demographics">Update your demographic and insurance information</a>.</td></tr>';}?>
						<?php if(user_group('patient')) { echo '<tr><td><img src="' . base_url().'images/schedule.png' . '" height="40" width="40" border="0"></td><td>' . anchor('patient/schedule', 'Schedule an appointment') . '.</td></tr>';}?>
						<?php if(user_group('patient')) { echo '<tr><td><img src="' . base_url().'images/email.png' . '" height="40" width="40" border="0"></td><td class="dash_tip" title="Send a message to your provider here too!">' . anchor('patient/messaging', 'View your messages') . '.</td></tr>';}?>
						<?php if(user_group('patient')) { echo '<tr><td><img src="' . base_url().'images/chart1.png' . '" height="40" width="40" border="0"></td><td class="dash_tip" title="View your lab results and patient instructions here!">' . anchor('patient/chartmenu', 'View your personal health record') . '.</td></tr>';}?>
						<?php if(user_group('patient')) { echo '<tr><td><img src="' . base_url().'images/chart.png' . '" height="40" width="40" border="0"></td><td class="dash_tip" title="Your provider may want you to fill out forms for the practice.  Do this here!">' . anchor('patient/chartmenu/forms', 'Fill out forms') . '.</td></tr>';}?>
					</table>
				</td>
				<td valign="top">
					<div id="draft_div" style="display:none;">
						<table id="draft_messages" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="draft_messages_pager" class="scroll" style="text-align:center;"></div><br>
						<table id="draft_encounters" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="draft_encounters_pager" class="scroll" style="text-align:center;"></div><br>
					</div>
					<div id="alert_div" style="display:none;">
						<table id="dashboard_alert" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="dashboard_alert_pager" class="scroll" style="text-align:center;"></div><br>
					</div>
					<div id="mtm_alert_div" style="display:none;">
						<table id="dashboard_mtm_alert" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="dashboard_mtm_alert_pager" class="scroll" style="text-align:center;"></div><br>
					</div>
					<div id="print_entire_charts_progress_div" style="display:none;">
						<div id="print_entire_charts_progressbar" style="width:600px";>
							<div class="print_entire_charts_progressbar_label">Loading...</div>
						</div>
						<div id="print_download"></div>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="change_password_dialog" title="Change Password">
	<form id="change_password_form">
		<table>
			<tr>
				<td><label for="old_password">Old Password</label></td>
				<td><input type="password" name="old_password" id="old_password" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="new_password">New Password</label></td>
				<td><input type="password" name="new_password" id="new_password" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="new_password2">Confirm New Password</label></td>
				<td><input type="password" name="new_password2" id="new_password2" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="secret_question">Secret Question</label></td>
				<td><select name="secret_question" id="secret_question" class="text ui-widget-content ui-corner-all"></select></td>
			</tr>
			<tr>
				<td><label for="secret_answer">Secret Answer</label></td>
				<td><input type="text" name="secret_answer" id="secret_answer" class="text ui-widget-content ui-corner-all"></td>
			</tr>
		</table>
	</form>
</div>
<div id="change_secret_answer_dialog" title="First Time Secert Question/Answer Setup">
	<form id="change_secret_answer_form">
		<table>
			<tr>
				<td><label for="secret_question1">Secret Question</label></td>
				<td><select name="secret_question" id="secret_question1" class="text ui-widget-content ui-corner-all"></select></td>
			</tr>
			<tr>
				<td><label for="secret_answer1">Secret Answer</label></td>
				<td><input type="text" name="secret_answer" id="secret_answer1" class="text ui-widget-content ui-corner-all"></td>
			</tr>
		</table>
	</form>
</div>
<div id="restore_database_dialog" title="Restore Database">
	<form id="restore_database_form" action="<?php echo site_url('backup/restore');?>" method="post" enctype="multipart/form-data">
		Pick one to restore: <select id="backup_select" class="text ui-widget-content ui-corner-all"></select>
		<button type="button" id="restore_backup_button">Select</button>
	</form>
</div>
<div id="demographics_list_dialog" title="Demographics">
	<form name="edit_demographics_form" id="edit_demographics_form">
		<button type="button" id="save_menu_demographics">Save</button>
		<button type="button" id="save_menu_demographics1">Save and Close</button>
		<button type="button" id="cancel_menu_demographics" >Cancel</button>
		<button type="button" id="insurance_menu_demographics">Insurance</button>
		<hr/>
		<input type="hidden" name="pid" id="pid"/>
		<input type="hidden" name="race_code" id="race_code">
		<input type="hidden" name="ethnicity_code" id="ethnicity_code">
		<input type="hidden" name="guardian_code" id="menu_guardian_code">
		<input type="hidden" name="lang_code" id="menu_lang_code">
		<div id="demographics_accordion">
			<h3>Name and Identity</h3>
			<div>
				<table cellspacing="0" cellpadding="3">
					<tbody>
						<tr>
							<td>Last Name:<br><input type="text" name="lastname" id="menu_lastname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>First Name:<br><input type="text" name="firstname" id="menu_firstname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Nickname:<br><input type="text" name="nickname" id="menu_nickname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Middle Name:<br><input type="text" name="middle" id="menu_middle" style="width:82px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Title:<br><input type="text" name="title" id="menu_title" style="width:82px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
					</tbody>
				</table>
				<table cellspacing="0" cellpadding="3">
					<tbody>
						<tr>
							<td>Date of Birth:<br><input type="text" name="DOB" id="menu_DOB" style="width:148px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Gender:<br><select name="gender" id="menu_gender" class="text ui-widget-content ui-corner-all"></td>
							<td>SSN:<br><input type="text" name="ss" id="menu_ss" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Race:<br><input type="text" name="race" id="menu_race" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Marital Status:<br><select name="marital_status" id="menu_marital_status" style="width:164px" class="text ui-widget-content ui-corner-all"></td>
							<td>Spouse/Partner Name:<br><input type="text" name="partner_name" id="menu_partner_name" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Employer:<br><input type="text" name="employer" id="menu_employer" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Ethnicity:<br><input type="text" name="ethnicity" id="menu_ethnicity" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Caregiver(s):<br><input type="text" name="caregiver" id="menu_caregiver" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Status:<br><select name="active" id="menu_active" style="width:164px" class="text ui-widget-content ui-corner-all"></select></td>
							<td>Referred By:<br><input type="text" name="referred_by" id="menu_referred_by" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Preferred Language:<br><input type="text" name="language" id="menu_language" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Contact</h3>
			<div>
				<table cellspacing="0" cellpadding="3">
					<tbody>
						<tr>
							<td colspan="3">Address:<br><input type="text" name="address" id="menu_address" style="width:560px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>City:<br><input type="text" name="city" id="menu_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>State:<br><select name="state" id="menu_state" class="text ui-widget-content ui-corner-all"></select></td>
							<td>Zip:<br><input type="text" name="zip" id="menu_zip" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Email:<br><input type="text" name="email" id="menu_email" style="width:560px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Home Phone:<br><input type="text" name="phone_home" id="menu_phone_home" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Work Phone:<br><input type="text" name="phone_work" id="menu_phone_work" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Cellular Phone:<br><input type="text" name="phone_cell" id="menu_phone_cell" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Emergency Contact:<br><input type="text" name="emergency_contact" id="menu_emergency_contact" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Emergency Phone:<br><input type="text" name="emergency_phone" id="menu_emergency_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Appointment Reminder Method:<br><select name="reminder_method" id="menu_reminder_method" class="text ui-widget-content ui-corner-all"></select><td>
							<td>Cellular Phone Carrier:<br><select name="cell_carrier" id="menu_cell_carrier" class="text ui-widget-content ui-corner-all"></select><td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Guardian</h3>
			<div>
				<button type="button" id="guardian_import">Same contact information as patient</button>
				<table cellspacing="0" cellpadding="3">
					<tbody>
						<tr>
							<td>Last Name:<br><input type="text" name="guardian_lastname" id="menu_guardian_lastname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>First Name:<br><input type="text" name="guardian_firstname" id="menu_guardian_firstname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Relationship:<br><input type="text" name="guardian_relationship" id="menu_guardian_relationship" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Address:<br><input type="text" name="guardian_address" id="menu_guardian_address" style="width:560px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>City:<br><input type="text" name="guardian_city" id="menu_guardian_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>State:<br><select name="guardian_state" id="menu_guardian_state" class="text ui-widget-content ui-corner-all"></select></td>
							<td>Zip:<br><input type="text" name="guardian_zip" id="menu_guardian_zip" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Email:<br><input type="text" name="guardian_email" id="menu_guardian_email" style="width:560px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Home Phone:<br><input type="text" name="guardian_phone_home" id="menu_guardian_phone_home" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Work Phone:<br><input type="text" name="guardian_phone_work" id="menu_guardian_phone_work" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Cellular Phone:<br><input type="text" name="guardian_phone_cell" id="menu_guardian_phone_cell" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Other</h3>
			<div>
				<table cellspacing="0" cellpadding="3">
					<tbody>
						<tr>
							<td>Preferred Provider:<br><input type="text" name="preferred_provider" id="menu_preferred_provider" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Preferred Pharmacy:<br><input type="text" name="preferred_pharmacy" id="menu_preferred_pharmacy" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Other Field 1:<br><input type="text" name="other1" id="menu_other1" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Other Field 2:<br><input type="text" name="other2" id="menu_other2" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="4">Comments:<br><textarea name="comments" id="menu_comments" rows="1" style="width:560px" class="text ui-widget-content ui-corner-all"></textarea></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>
<div id="demographics_insurance_plan_dialog" title="">
	<form name="edit_menu_insurance_plan_form" id="edit_menu_insurance_plan_form">
		<input type="hidden" name="address_id" id="menu_insurance_plan_address_id"/>
		<table>
			<tbody>
				<tr>
					<td colspan="3">Insurance Plan Name:<br><input type="text" name="facility" id="menu_insurance_plan_facility" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Payor ID:<br><input type="text" name="insurance_plan_payor_id" id="menu_insurance_plan_payor_id" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Insurance Type:<br><select name="insurance_plan_type" id="menu_insurance_plan_type" class="text ui-widget-content ui-corner-all"></td>
					<td>Accept Assignment:<br><select name="insurance_plan_assignment" id="menu_insurance_plan_assignment" class="text ui-widget-content ui-corner-all"/></select></td>
				</tr>
				<tr>
					<td colspan="3">Address:<br><input type="text" name="street_address1" id="menu_insurance_plan_address" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td colspan="3">Address2:<br><input type="text" name="street_address2" id="menu_insurance_plan_address2" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>City:<br><input type="text" name="city" id="menu_insurance_plan_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>State:<br><select name="state" id="menu_insurance_plan_state" class="text ui-widget-content ui-corner-all"></td>
					<td>Zip:<br><input type="text" name="zip" id="menu_insurance_plan_zip" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Phone:<br><input type="text" name="phone" id="menu_insurance_plan_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Procedure PA Phone:<br><input type="text" name="insurance_plan_ppa_phone" id="menu_insurance_plan_ppa_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Procedure PA Fax:<br><input type="text" name="insurance_plan_ppa_fax" id="menu_insurance_plan_ppa_fax" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td colspan="3">Procedure PA Website:<br><input type="text" name="insurance_plan_ppa_url" id="menu_insurance_plan_ppa_url" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td colspan="3">Medication PA Website:<br><input type="text" name="insurance_plan_mpa_url" id="menu_insurance_plan_mpa_url" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Medication PA Phone:<br><input type="text" name="insurance_plan_mpa_phone" id="menu_insurance_plan_mpa_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Medication PA Fax:<br><input type="text" name="insurance_plan_mpa_fax" id="menu_insurance_plan_mpa_fax" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<div id="menu_insurance_main_dialog" title="Insurance Plan">
	<form name="edit_menu_insurance_main_form" id="edit_menu_insurance_main_form">
		<input type="hidden" name="insurance_id" id="menu_insurance_id"/>
		<input type="hidden" name="insurance_plan_name" id="menu_insurance_plan_name"/>
		<table>
			<tbody>
				<tr>
					<td>Insurance Provider:<br><select name="address_id" id="menu_insurance_plan_select" class="text ui-widget-content ui-corner-all"></select></td>
					<td><button type="button" id="add_insurance_plan">Add Insurance Provider</button></td>
					<td>Insurance Priority:<br><select name="insurance_order" id="menu_insurance_order" class="text ui-widget-content ui-corner-all"></td>
				</tr>
				<tr>
					<td>ID Number:<br><input type="text" name="insurance_id_num" id="menu_insurance_id_num" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Group Number:<br><input type="text" name="insurance_group" id="menu_insurance_group" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Relationship:<br><select name="insurance_relationship" id="menu_insurance_relationship" class="text ui-widget-content ui-corner-all"></td>
				</tr>
				<tr>
					<td>Insured Last Name:<br><input type="text" name="insurance_insu_lastname" id="menu_insurance_insu_lastname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Insured First Name:<br><input type="text" name="insurance_insu_firstname" id="menu_insurance_insu_firstname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Insured Date of Birth:<br><input type="text" name="insurance_insu_dob" id="menu_insurance_insu_dob" style="width:148px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Insured Gender:<br><select name="insurance_insu_gender" id="menu_insurance_insu_gender" class="text ui-widget-content ui-corner-all"></select></td>
					<td><br><input type="button" id="insurance_copy" value="Use Patient's Address" class="nosh_button"/></td>
				</tr>
				<tr>
					<td colspan="3">Insured Address:<br><input type="text" name="insurance_insu_address" id="menu_insurance_insu_address" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
				<tr>
					<td>Insured City:<br><input type="text" name="insurance_insu_city" id="menu_insurance_insu_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Insured State:<br><select name="insurance_insu_state" id="menu_insurance_insu_state" class="text ui-widget-content ui-corner-all"></td>
					<td>Insured Zip:<br><input type="text" name="insurance_insu_zip" id="menu_insurance_insu_zip" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Insured Phone:<br><input type="text" name="insurance_insu_phone" id="menu_insurance_insu_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Copay:<br><input type="text" name="insurance_copay" id="menu_insurance_copay" style="widteh:164px" class="text ui-widget-content ui-corner-all"/></td>
					<td>Deductible:<br><input type="text" name="insurance_deductible" id="menu_insurance_deductible" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td colspan="3">Comments:<br><textarea name="insurance_comments" id="menu_insurance_comments" rows="3" style="width:562px" class="text ui-widget-content ui-corner-all"></textarea></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<div id="demographics_insurance_dialog" title="Insurance">
	<table id="demographics_insurance" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="demographics_insurance_pager" class="scroll" style="text-align:center;"></div><br>
	<div id="demographics_insurance_details"></div><br>
	<button type="button" id="demographics_add_insurance">Add Insurance</button>
	<button type="button" id="demographics_edit_insurance">Edit Insurance</button>
	<button type="button" id="demographics_inactivate_insurance">Inactivate Insurance</button>
	<button type="button" id="demographics_delete_insurance">Delete Insurance</button><br><br>
	<table id="demographics_insurance_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="demographics_insurance_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<input type="button" id="demographics_reactivate_insurance" value="Reactivate Insurance" class="nosh_button"/><br><br>
</div>
<div id="provider_info_dialog" title="Edit Provider Information">
	<div id="provider_info_accordion">
		<h3 class="provider_info_class1"><a href="#">Accounts</a></h3>
		<div class="provider_info_class1">
			<form name="provider_info_form" id="provider_info_form">
				<button type="button" id="save_provider_info">Save</button>
				<button type="button" id="cancel_provider_info" >Cancel</button>
				<hr class="ui-state-default"/>
				<input type="hidden" name="id" id="id"/>
				<table>
					<tr>
						<td>Specialty:</td>
						<td><input type="text" name="specialty" id="specialty" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>License Number:</td>
						<td><input type="text" name="license" id="license" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>State Licensed:</td>
						<td><select name="license_state" id="license_state" style="width:164px" class="text ui-widget-content ui-corner-all"></select></td>
					</tr>
					<tr>
						<td>NPI:</td>
						<td><input type="text" name="npi" id="npi" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>UPIN:</td>
						<td><input type="text" name="upin" id="upin" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>DEA Number:</td>
						<td><input type="text" name="dea" id="dea" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>Medicare Number:</td>
						<td><input type="text" name="medicare" id="medicare" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>Tax ID Number:</td>
						<td><input type="text" name="tax_id" id="tax_id" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>RCopia Username:</span></td>
						<td><input type="text" name="rcopia_username" id="rcopia_username" style="width:164px" class="text ui-widget-content ui-corner-all"/><span id="rcopia_status"></span></td>
					</tr>
					<tr>
						<td>PeaceHealth Labs ID:</span></td>
						<td><input type="text" name="peacehealth_id" id="peacehealth_id" style="width:164px" class="text ui-widget-content ui-corner-all"/><span id="rcopia_status"></span></td>
					</tr>
					<tr>
						<td>Time increments for schedule (minutes):</span></td>
						<td><input type="text" name="schedule_increment" id="schedule_increment" style="width:164px" class="text ui-widget-content ui-corner-all"/><span id="rcopia_status"></span></td>
					</tr>
				</table>
			</form>
		</div>
		<h3 class="provider_info_class2"><a href="#">Signature</a></h3>
		<div class="provider_info_class2">
			<form method="post" action="<?php echo site_url('start/change_signature');?>" class="sigPad">
				Preview Signature:<br>
				<div id="preview_signature"></div>
				<label for="name">Print your name for verification.</label>
				<input type="text" name="name" id="name" class="name">
				<p class="drawItDesc">Draw your signature</p>
				<ul class="sigNav">
					<li class="drawIt"><a href="#draw-it">Draw It</a></li>
					<li class="clearButton"><a href="#clear">Clear</a></li>
				</ul>
				<div class="sig sigWrapper">
					<div class="typed"></div>
					<canvas class="pad" width="198" height="55"></canvas>
					<input type="hidden" name="output" class="output">
				</div>
				<button type="submit">Save signature</button>
			</form>
		</div>
</div>
<div id="tests_reconcile_dialog" title="Test Reconciliation">
	<table id="tests_reconcile_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="tests_reconcile_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="reconcile_tests">Reconcile</button><button type="button" id="delete_tests">Delete</button>
	<div id="reconcile_tests_div" style="display:none">
		<br><br>
		<input type="hidden" id="reconcile_tests_pid"/>
		Choose patient:<br><input type="text" id="reconcile_test_patient_search1" style="width:300px" class="text ui-widget-content ui-corner-all" /> 
		<button type="button" id="reconcile_tests_send">Import</button>
		<button type="button" id="reconcile_tests_cancel">Cancel</button>
	</div>
</div>
