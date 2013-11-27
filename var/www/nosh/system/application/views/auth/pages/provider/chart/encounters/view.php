<script type="text/javascript">
$(document).ready(function() {
	$("#dialog_load1").dialog({
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
		alive_url: '<?php echo site_url("provider/encounters/view");?>',
		redirect_url: '<?php echo site_url("logout");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: false
	});
	$("#preview_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		modal: true
	});
	$("#preview_encounter").button({
		icons: {
			primary: "ui-icon-comment"
		},
	});
	$("#preview_encounter").click(function() {
		$("#preview").load('<?php echo site_url("provider/encounters/modal_view2");?>/<?php echo $this->session->userdata("eid");?>');
		$("#preview_dialog").dialog('open');
	});
	$("#detail_encounter_condition_work").addOption({"":"","No":"No","Yes":"Yes"});
	$("#detail_encounter_condition_auto").addOption({"":"","No":"No","Yes":"Yes"});
	$("#detail_encounter_condition_auto_state").addOption({"":"State where accident occured.","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#detail_encounter_condition_other").addOption({"":"","No":"No","Yes":"Yes"});
	$("#detail_encounter_location").autocomplete({
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
		minLength: 1,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
	});
	$("#detail_encounter_role").addOption({"":"","Primary Care Provider":"Primary Care Provider","Consulting Provider":"Consulting Provider","Referring Provider":"Referring Provider"},false).change(function(){
		if ($(this).val() == "Consulting Provider" || $(this).val() == "Referring Provider") {
			$("#detail_referring_provider_div").show();
		} else {
			$("#detail_referring_provider_div").hide().val('');
		}
	});
	$("#detail_referring_provider_npi").mask("9999999999");
	$("#detail_referring_provider").autocomplete({
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
			$("#detail_referring_provider_npi").val(ui.item.npi);
		}
	});
	$("#detail_encounter").button({
		icons: {
			primary: "ui-icon-pencil"
		},
	});
	$('#detail_encounter').click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url ('provider/encounters/get_encounter/');?>",
			dataType: "json",
			success: function(data){
				$('#detail_encounter_number').html(data.eid);
				$('#detail_encounter_date').val(data.encounter_date);
				$('#detail_encounter_time').val(data.encounter_time);
				$('#detail_encounter_location').val(data.encounter_location);
				$('#detail_encounter_cc').val(data.encounter_cc);
				$('#detail_encounter_condition').val(data.encounter_condition);
				$("#detail_encounter_condition_work").val(data.encounter_condition_work);
				$("#detail_encounter_condition_auto").val(data.encounter_condition_auto);
				$('#detail_encounter_condition_auto_state').val(data.encounter_condition_auto_state);
				$("#detail_encounter_condition_other").val(data.encounter_condition_other);
				$("#detail_encounter_role").val(data.encounter_role);
				$("#detail_referring_provider").val(data.referring_provider);
				$("#detail_referring_provider_npi").val(data.referring_provider_npi);
				if (data.encounter_role == "Consulting Provider" || data.encounter_role == "Referring Provider") {
					$("#detail_referring_provider_div").show();
				} else {
					$("#detail_referring_provider_div").hide();
				}
			}
		});
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/get_appointments');?>",
			dataType: "json",
			success: function(data){
				$("#detail_encounter_type").addOption(data,false);
			}
		});
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/get_copay');?>",
			success: function(data){
				$("#detail_encounter_copay").html(data);
			}
		});
		$("#detail_encounter_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
		$('#detail_encounter_time').timepicker({
			'scrollDefaultNow': true,
			'timeFormat': 'h:i A',
			'step': 15
		});
		$("#detail_encounter_dialog").dialog('open');
	});
	$("#detail_encounter_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 550, 
		modal: true,
		buttons: {
			'Edit Encounter': function() {
				var date = $("#detail_encounter_date");
				var time = $("#detail_encounter_time");
				var location = $("#detail_encounter_location");
				var cc = $("#detail_encounter_cc");
				var work = $("#detail_encounter_condition_work");
				var auto = $("#detail_encounter_condition_auto");
				var other = $("#detail_encounter_condition_other");
				var role = $("#detail_encounter_role");
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
					var str = $("#detail_encounter_form").serialize();		
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/encounters/edit_encounter');?>",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#detail_encounter_dialog").dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#detail_encounter_dialog").dialog('close');
			}
		}
	});
	$("#prenatal_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 500, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'OK': function() {
				var edc = $("#pregnancy_edc");
				var bValid = true;
				bValid = bValid && checkEmpty(edc,"Consensus EDC");
				if (bValid) {
					var edc1 = $("#pregnancy_edc").val();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/edit_pregnancy');?>",
						data: "pregnant=" + edc1,
						success: function(data){
							$.jGrowl(data);
							var origin = $("#prenatal_dialog_origin").val();
							var a = $("#pregnancy_edc").val();
							if (a != 'no') {
								var result1 = a.split(";");
								var string = result1[0];
								var result = string.split("/");
								var starto = new Date();
								starto.setFullYear(result[2]);	
								starto.setMonth(result[0] - 1);
								starto.setDate(result[1]);
								var daymsecs = 86400000;
								var timenow = new Date();	
								var elapsed = Math.round((timenow.getTime()-starto.getTime())/daymsecs);
								var b = "" + (Math.floor(elapsed/7)+2) + " weeks, " + Math.floor(elapsed%7) + " days";
								var duedate = new Date(); 
								duedate.setTime(starto.getTime() + daymsecs*266);
								var month = new Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
								var c = "" + month[duedate.getMonth()] + "/" + duedate.getDate() + "/" + duedate.getFullYear();
								var intro = "Pregnancy status: Pregnant.\nEstimated date of conception: " + string + "\nEstimated gestational age: " + b + "\nEstimated due date: " + c;
							} else {
								var intro = "Pregnancy status: Not pregnant.";
							}
							if (origin == "1") {
								var old = $("#hpi").val();
								if(old){
									var pos = old.lastIndexOf('\n');
									if (pos == -1) {
										var old1 = old + '\n\n';
									} else {
										var a = old.slice(pos);
										if (a == '') {
											var old1 = old + '\n';
										} else {
											var old1 = old + '\n\n';
										}
									}
								} else {
									var old1 = '';
								}
								$("#hpi").val(old1+intro);
							} else {
								if (a != 'no') {
									$("#prenatal_ega").val(b);
									$("#prenatal_duedate").val(c);
								}
							}
							$("#prenatal_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#prenatal_dialog").dialog('close');
			}
		}
	});
	$("#save_draft").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#save_draft").click(function() {
		window.location = "<?php echo site_url ('provider/chartmenu/');?>";
	});
	$("#sign_encounter").button({
		icons: {
			primary: "ui-icon-check"
		},
	});
	$("#sign_encounter").click(function() {
		if(confirm('Are you sure you want to sign?')){ 
			var signed = "Yes";
			$.ajax({
				type: "POST",
				url: "<?php echo site_url ('provider/encounters/check_encounter/');?>",
				success: function(data){
					if (data == "") {
						$.ajax({
							type: "POST",
							url: "<?php echo site_url ('provider/encounters/sign_encounter/');?>",
							success: function(data){
								if (data == "Close Chart") {
									window.location = "<?php echo site_url ('provider/chartmenu/');?>";
								} else {
									$.jGrowl(data);
									jQuery("#encounters").trigger("reloadGrid");
									window.location = "<?php echo site_url ('provider/chartmenu/');?>";
								}
							}
						});
					} else {
						$.jGrowl(data);
					}
				}
			});
		}
	});
	$("#delete_encounter").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#delete_encounter").click(function() {
		if(confirm('Are you sure you want to delete this encounter? ')){ 
			var eid = "";
			$.ajax({
				type: "POST",
				url: "<?php echo site_url ('provider/encounters/delete_encounter/');?>",
				data: "eid=" + eid,
				success: function(data){
					if (data == "") {
						window.location = "<?php echo site_url ('provider/chartmenu/');?>";
					} else {
						$.jGrowl(data);
					}
				}
			});
		}
	});
	$("#update_encounter1").click(function() {
		if ($("#encounter_date1").val() == '') {
			$.jGrowl("Date of Service Required");
		} else {
			if ($("#encounter_time1").val() == '') {
				$.jGrowl("Time of Service Required");
			} else {	
				if ($("#encounter_type1").val() == '') {
					$.jGrowl("Encounter Type Required");
				} else {	
					if ($("#encounter_location1").val() == '') {
						$.jGrowl("Encounter Location Required");
					} else {
						if ($("#encounter_cc1").val() == '') {
							$.jGrowl("Chief Complaint Required");
						} else {
							var str = $("#detail_encounter_form").serialize();		
							if(str){
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('provider/encounters/edit_encounter');?>",
									data: str,
									success: function(data){
										$.jGrowl(data);
										$('#encounter_provider_tabs').tabs('select', 1);
									}
								});
							} else {
								$.jGrowl("Please complete the form");
							}
						}
					}
				}
			}
		}
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/get_hpi');?>",
		success: function(data){
			if (!!data) {
				$("#hpi").val(data);
				$("#hpi_old").val(data);
			} else {
				var age1 = "<?php echo $this->session->userdata('age');?>";
				var age = age1.replace("Years Old", "year-old ");
				age = age.replace("Month Old", "month-old ");
				age = age.replace("Months Old", "month-old ");
				age = age.replace("Week Old", "week-old ");
				age = age.replace("Weeks Old", "weeks-old ");
				age = age.replace("Year", 'year');
				var gender = "<?php echo $this->session->userdata('gender');?>";
				var intro = age + gender + " here for the following concerns:";
				$("#hpi").val(intro);
			}
		}
	});
	$("#hpi").focus();
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/pf_template_select_list/HPI');?>",
		dataType: "json",
		success: function(data){
			$('#hpi_pf_template').addOption(data.options);
			$('#hpi_pf_template').sortOptions();
			$('#hpi_pf_template').val("");
		}
	});
	$('#hpi_pf_template').change(function(){
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_pf_template');?>" + "/" + a,
			success: function(data){
				var old = $("#hpi").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#hpi").val(b);
			}
		});
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/hpi_template_select_list');?>",
		dataType: "json",
		success: function(data){
			$('#hpi_template').addOption({"":"*Select a template"});
			$('#hpi_template').addOption(data.options);
			$('#hpi_template').sortOptions();
			$('#hpi_template').val("");
		}
	});
	$('#hpi_template').change(function(){
		var a = $(this).val();
		$('#hpi_template_form').html('');
		if (a != '') {
			var text = $('#hpi_template option:selected').text();
			var old = $("#hpi").val();
			if (text != 'Generic') {
				if (old != '') {
					var b = old + '\n\n' + text;
				} else {
					var b = text;
				}
				$("#hpi").val(b);
			}
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/get_hpi_template');?>" + "/" + a,
				dataType: "json",
				success: function(data){
					$('#hpi_template_form').dform(data);
					$('#hpi_template_form').find('input').first().focus();
					$('#hpi_template_form').find('.hpi_buttonset').buttonset();
					$('#hpi_template_form').find('.hpi_detail_text').hide();
					$('#hpi_template_form').find('input[type="checkbox"]').change(function() {
						var parent_id = $(this).attr("id");
						var old = $("#hpi").val();
						var a = $(this).val();
						if ($(this).attr('checked')) {
							if (old != '') {
								var b = old + '\n' + a;
							} else {
								var b = a;
							}
							$("#hpi").val(b); 
						} else {
							var a1 = '\n' + a;
							var c = old.replace(a1,'');
							c = c.replace(a, '');
							$("#hpi").val(c); 
						}
					});
					$('#hpi_template_form').find('input[type="text"]').focusin(function() {
						old_text = $(this).val();
					});
					$('#hpi_template_form').find('input[type="text"]').focusout(function() {
						var a = $(this).val();
						if (a != '') {
							var parent_id = $(this).attr("id");
							var x = parent_id.length - 1;
							var parent_div = parent_id.slice(0,x);
							var start1 = $("#" + parent_div + "_div").find('span:first').text();
							var start2 = $("label[for='" + parent_id + "']").text();
							var start3_n = start1.lastIndexOf('degrees');
							if (start3_n != -1) {
								var end_text = ' degrees.';
							} else {
								var end_text = '';
							}
							if (!!start2) {
								var start_text = start2 + ' ' + start1;
							} else {
								var start_text = start1;
							}
							var old = $("#hpi").val();
							var a_pointer = a.length - 1;
							var a_pointer2 = a.lastIndexOf('.');
							if (!!old) {
								if (!!start_text) {
									var c = start_text + ' ' + a + end_text;
									if (old_text != '') {
										var c_old = start_text + ' ' + old_text + end_text;
									}
								} else {
									if (a_pointer != a_pointer2) {
										var c = a + '.';
									} else {
										var c = a;
									}
								}
								if (old_text != '') {
									var old_text_pointer = old_text.length - 1;
									var old_text_pointer2 = old_text.lastIndexOf('.');
									if (old_text_pointer != old_text_pointer2) {
										var old_text1 = old_text + '.';
									} else {
										var old_text1 = old_text;
									}
									if (!!start_text) {
										var b = old.replace(c_old, c);
									} else {
										var b = old.replace(old_text1, c);
									}
									old_text = '';
								} else {
									var item_class = $(this).attr("class");
									if (item_class == "hpi_other hpi_detail_text ui-dform-text ui-widget-content ui-corner-all") {
										var b = old + " " +c;
									} else {
										var b = old + "\n" + c;
									}
								}
							} else {
								if (!!start_text) {
									var b = start_text + ' ' + a + end_text;
								} else {
									if (a_pointer != a_pointer2) {
										var b = a + '.';
									} else {
										var b = a;
									}
								}
							}
							$("#hpi").val(b);
						}
					});
					$('#hpi_template_form').find('.hpi_detail').click(function() {
						var detail_id = $(this).attr("id") + '_detail';
						if ($(this).attr('checked')) {
							$('#' + detail_id).show('fast');
							$('#' + detail_id).focus();
						} else {
							var parent_id = $(this).attr("id");
							var old = $("#hpi").val();
							var a = ' ' + $('#' + detail_id).val();
							var a1 = a + '  ';
							var c = old.replace(a1,'');
							c = c.replace(a, '');
							$("#hpi").val(c);
							$('#' + detail_id).hide('fast');
						}
					});
					$('#hpi_template_form').find('.hpi_normal').click(function() {
						if ($(this).attr('checked')) {
							$("#hpi_template_form").find('.hpi_other:checkbox').each(function(){
								var parent_id = $(this).attr("id");
								$(this).attr('checked',false);
								var old = $("#hpi").val();
								var a = $(this).val();
								var a1 = '\n' + a;
								var c = old.replace(a1,'');
								c = c.replace(a, '');
								$("#hpi").val(c);
								$("#hpi_template_form input").button('refresh');
							});
							$("#hpi_template_form").find('.hpi_detail_text').each(function(){
								if ($(this).val() != '') {
									var parent_id = $(this).attr("id");
									var old = $("#hpi").val();
									var a = ' ' + $(this).val();
									var a1 = '\n' + a;
									var c = old.replace(a1,'');
									c = c.replace(a, '');
									$("#hpi").val(c);
								}
								$(this).hide();
							});
						}
					});
				}
			});
		}
	});
	$('#hpi_reset').click(function(){
		$("#hpi").val('');
	});
	$('#hpi_wcc').click(function(){
		var old = $("#hpi").val();
		if(old){
			var pos = old.lastIndexOf('\n');
			if (pos == -1) {
				var old1 = old + '\n\n';
			} else {
				var a = old.slice(pos);
				if (a == '') {
					var old1 = old + '\n';
				} else {
					var old1 = old + '\n\n';
				}
			}
		} else {
			var old1 = '';
		}
		var age1 = "<?php echo $this->session->userdata('age');?>";
		var age = age1.replace("Years Old", "year-old ");
		age = age.replace("Month Old", "month-old ");
		age = age.replace("Months Old", "month-old ");
		age = age.replace("Week Old", "week-old ");
		age = age.replace("Weeks Old", "weeks-old ");
		age = age.replace("Year", 'year');
		var gender = "<?php echo $this->session->userdata('gender');?>";
		var intro = age + gender + " here for a well child check.";
		$("#hpi").val(old1+intro);
	});
	$('#hpi_cpe').click(function(){
		var old = $("#hpi").val();
		if(old){
			var pos = old.lastIndexOf('\n');
			if (pos == -1) {
				var old1 = old + '\n\n';
			} else {
				var a = old.slice(pos);
				if (a == '') {
					var old1 = old + '\n';
				} else {
					var old1 = old + '\n\n';
				}
			}
		} else {
			var old1 = '';
		}
		var age1 = "<?php echo $this->session->userdata('age');?>";
		var age = age1.replace("Years Old", "year-old ");
		age = age.replace("Month Old", "month-old ");
		age = age.replace("Months Old", "month-old ");
		age = age.replace("Week Old", "week-old ");
		age = age.replace("Weeks Old", "weeks-old ");
		age = age.replace("Year", 'year');
		var gender = "<?php echo $this->session->userdata('gender');?>";
		var intro = age + gender + " here for a complete physical examination.";
		$("#hpi").val(old1+intro);
	});
	$("#prenatal_ega").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#prenatal_duedate").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#prenatal_ega").mask("99/99/9999");
	$("#prenatal_duedate").mask("99/99/9999");
	$('#calculate_ega').click(function(){
		$('#pregnancy_form').clearForm();
		$('#edc_text').html('');
		$('#prenatal_dialog_origin').val('2');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_prenatal');?>",
			success: function(data){
				if (data != 'no') {
					var result1 = data.split(";");
					if (result1[1] == "Ultrasound"){
						$("#pregnancy_us").val(result1[0]);
					} else {
						var result2 = result1[1].split(" ");
						$("#pregnancy_lmp").val(result2[1]);
						$("#pregnancy_cycle").val(result2[2]);
					}
					$("#pregnancy_edc").val(data);
					$("#edc_text").html(result1[0]);
				} else {
					$("#pregnancy_edc").val(data);
					$("#edc_text").html('Not Pregnant');
				}	
			}
		});
		$("#prenatal_dialog").dialog('open');
	});
	$('#hpi_preg').click(function(){
		$('#pregnancy_form').clearForm();
		$('#edc_text').html('');
		$('#prenatal_dialog_origin').val('1');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_prenatal');?>",
			success: function(data){
				if (data != 'no') {
					var result1 = data.split(";");
					if (result1[1] == "Ultrasound"){
						$("#pregnancy_us").val(result1[0]);
					} else {
						var result2 = result1[1].split(" ");
						$("#pregnancy_lmp").val(result2[1]);
						$("#pregnancy_cycle").val(result2[2]);
					}
					$("#pregnancy_edc").val(data);
					$("#edc_text").html(result1[0]);
				} else {
					$("#pregnancy_edc").val(data);
					$("#edc_text").html('Not Pregnant');
				}	
			}
		});
		$("#prenatal_dialog").dialog('open');
	});
	$("input[name='prenatal_headache']").click(function(){
		var a = $('#prenatal_headache_y').attr('checked');
		if(a){
			$('#prenatal_headache_input').show('fast');
			$('#prenatal_headache_text').focus();
		} else {
			$('#prenatal_headache_input').hide('fast');
		}
	});
	$("input[name='prenatal_bleeding']").click(function(){
		var a = $('#prenatal_bleeding_y').attr('checked');
		if(a){
			$('#prenatal_bleeding_input').show('fast');
			$('#prenatal_bleeding_text').focus();
		} else {
			$('#prenatal_bleeding_input').hide('fast');
		}
	});
	$("input[name='prenatal_pain']").click(function(){
		var a = $('#prenatal_pain_y').attr('checked');
		if(a){
			$('#prenatal_pain_input').show('fast');
			$('#prenatal_pain_text').focus();
		} else {
			$('#prenatal_pain_input').hide('fast');
		}
	});
	$("input[name='prenatal_nausea']").click(function(){
		var a = $('#prenatal_nausea_y').attr('checked');
		if(a){
			$('#prenatal_nausea_input').show('fast');
			$('#prenatal_nausea_text').focus();
		} else {
			$('#prenatal_nausea_input').hide('fast');
		}
	});
	$("input[name='prenatal_vomiting']").click(function(){
		var a = $('#prenatal_vomiting_y').attr('checked');
		if(a){
			$('#prenatal_vomiting_input').show('fast');
			$('#prenatal_vomiting_text').focus();
		} else {
			$('#prenatal_vomiting_input').hide('fast');
		}
	});
	$("input[name='prenatal_swelling']").click(function(){
		var a = $('#prenatal_swelling_y').attr('checked');
		if(a){
			$('#prenatal_swelling_input').show('fast');
			$('#prenatal_swelling_text').focus();
		} else {
			$('#prenatal_swelling_input').hide('fast');
		}
	});
	$("input[name='prenatal_movement']").click(function(){
		var a = $('#prenatal_movement_y').attr('checked');
		if(a){
			$('#prenatal_movement_input').show('fast');
			$('#prenatal_movement_text').focus();
		} else {
			$('#prenatal_movement_input').hide('fast');
		}
	});
	$('#save_hpi_prenatal_form').click(function(){
		var old = $("#hpi").val();
		if(old){
			var pos = old.lastIndexOf('\n');
			if (pos == -1) {
				var old1 = old + '\n\n';
			} else {
				var a = old.slice(pos);
				if (a == '') {
					var old1 = old + '\n';
				} else {
					var old1 = old + '\n\n';
				}
			}
		} else {
			var old1 = '';
		}
		var age1 = "<?php echo $this->session->userdata('age');?>";
		var age = age1.replace("Years Old", "year-old ");
		age = age.replace("Month Old", "month-old ");
		age = age.replace("Months Old", "month-old ");
		age = age.replace("Week Old", "week-old ");
		age = age.replace("Weeks Old", "weeks-old ");
		age = age.replace("Year", 'year');
		var gender = "<?php echo $this->session->userdata('gender');?>";
		var intro = age + gender + " here for a prenatal visit.\n";
		var a = $("#prenatal_ega").val();
		var z = $("#prenatal_duedate").val();
		var b = $("input[name='prenatal_headache']:checked").val();
		var b0 = $("#prenatal_headache_text").val();
		var c = $("input[name='prenatal_bleeding']:checked").val();
		var c0 = $("#prenatal_bleeding_text").val();
		var d = $("input[name='prenatal_pain']:checked").val();
		var d0 = $("#prenatal_pain_text").val();
		var e = $("input[name='prenatal_nausea']:checked").val();
		var e0 = $("#prenatal_nausea_text").val();
		var f = $("input[name='prenatal_vomiting']:checked").val();
		var f0 = $("#prenatal_vomiting_text").val();
		var g = $("input[name='prenatal_swelling']:checked").val();
		var g0 = $("#prenatal_swelling_text").val();
		var h = $("input[name='prenatal_movement']:checked").val();
		var h0 = $("#prenatal_movement_text").val();
		if(a){
			var a1 = 'Estimated gestational age:  ' + a +'\n';
		} else {
			var a1 = '';
		}
		if(z){
			var z1 = 'Estimated due date:  ' + z +'\n';
		} else {
			var z1 = '';
		}
		if(b){
			var b1 = b + b0 + '\n';
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = c + c0 + '\n';
		} else {
			var c1 = '';
		}
		if(d){
			var d1 = d + d0 + '\n';
		} else {
			var d1 = '';
		}
		if(e){
			var e1 = e + e0 + '\n';
		} else {
			var e1 = '';
		}
		if(f){
			var f1 = f + f0 + '\n';
		} else {
			var f1 = '';
		}
		if(g){
			var g1 = g + g0 + '\n';
		} else {
			var g1 = '';
		}
		if(h){
			var h1 = h + h0 + '\n';
		} else {
			var h1 = '';
		}
		var full = intro+a1+z1+b1+c1+d1+e1+f1+g1+h1;
		var len = full.length;
		var len1 = len - 1;
		var full1 = full.slice(0, len1);
		$("#hpi").val(old1+full1);
		$('#hpi_prenatal_form').clearForm();
		$("#hpi_prenatal_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$('#cancel_hpi_prenatal_form').click(function(){
		$('#hpi_prenatal_form').clearForm();
		$("#hpi_prenatal_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	
	$('#not_pregnant').click(function(){
		$('#pregnancy_edc').val('no');
		$('#edc_text').html('Not Pregnant');
		$('#pregnancy_lmp').val('');
		$('#pregnancy_cycle').val('');
		$('#pregnancy_us').val('');
	});
	$("#pregnancy_lmp").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#pregnancy_us").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#pregnancy_lmp").mask("99/99/9999");
	$("#pregnancy_us").mask("99/99/9999");
	$('#edc_lmp').click(function(){
		var a = $("#pregnancy_lmp");
		var b = $("#pregnancy_cycle");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Last menstrural period");
		bValid = bValid && checkEmpty(a,"Number of days in cycle");
		if (bValid) {
			var codate = new Date();
			var daymsecs = 86400000;
			var c = $('#pregnancy_cycle').val();
			var string = $('#pregnancy_lmp').val();
			var result = string.split("/");
			var starto = new Date();
			starto.setFullYear(result[2]);	
			starto.setMonth(result[0] - 1);
			starto.setDate(result[1]);
			starto.setTime(starto.getTime() + ((c * daymsecs) - daymsecs*14));
			codate.setTime(starto.getTime());
			var month = new Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			var a = "" + month[starto.getMonth()] + "/" + starto.getDate() + "/" + starto.getFullYear() + ";LMP " + string + " " + c;	
			var a1 = month[starto.getMonth()] + "/" + starto.getDate() + "/" + starto.getFullYear();
			$('#pregnancy_edc').val(a);
			$('#edc_text').html(a1);
		}
	});
	$('#edc_us').click(function(){
		var a = $("#pregnancy_us");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Ultrasound EDC");
		if (bValid) {
			var a = $('#pregnancy_us').val() + ";Ultrasound";
			$('#pregnancy_edc').val(a);
			$('#edc_text').html($('#pregnancy_us').val());
		}
	});
	$('#hpi_mtm').click(function(){
		var old = $("#hpi").val();
		if(old){
			var pos = old.lastIndexOf('\n');
			if (pos == -1) {
				var old1 = old + '\n\n';
			} else {
				var a = old.slice(pos);
				if (a == '') {
					var old1 = old + '\n';
				} else {
					var old1 = old + '\n\n';
				}
			}
		} else {
			var old1 = '';
		}
		var age1 = "<?php echo $this->session->userdata('age');?>";
		var age = age1.replace("Years Old", "year-old ");
		age = age.replace("Month Old", "month-old ");
		age = age.replace("Months Old", "month-old ");
		age = age.replace("Week Old", "week-old ");
		age = age.replace("Weeks Old", "weeks-old ");
		age = age.replace("Year", 'year');
		var gender = "<?php echo $this->session->userdata('gender');?>";
		var intro = age + gender + " here for a Medication Therapy Management encounter.";
		$("#hpi").val(old1+intro);
	});
	$('.nosh_button').button();
	$(".nosh_button_save").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$(".nosh_button_cancel").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$(".nosh_button_calculator").button({
		icons: {
			primary: "ui-icon-calculator"
		},
	});
	$(".nosh_button_check").button({
		icons: {
			primary: "ui-icon-check"
		},
	});
	$("#encounter_tags").tagit({
		tagSource: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/search_tags');?>",
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
		tagsChanged: function(a, b) {
			if (b == "added") {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/save_tag/eid') . '/' . $this->session->userdata('eid');?>",
					data: 'tag=' + a
				});
			}
			if (b == "popped") {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/remove_tag/eid') . '/' . $this->session->userdata('eid');?>",
					data: 'tag=' + a
				});
			}
		}
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('search/get_tags/eid') . '/' . $this->session->userdata('eid');?>",
		dataType: "json",
		success: function(data){
			$("#encounter_tags").tagit("fill",data);
		}
	});
	$(document).ajaxStop(function(){
		$("#dialog_load1").dialog("close");
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
	function hpi_autosave() {
		var old0 = $("#hpi_old").val();
		var new0 = $("#hpi").val();
		if (old0 != new0) {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/hpi_save');?>",
				data: 'hpi=' + $("#hpi").val(),
				success: function(data){
					$.jGrowl(data);
					var a = $("#hpi").val();
					$("#hpi_old").val(a);
				}
			});
		}
	}
	setInterval(hpi_autosave, 10000);
});
</script>
<div id="dialog_load1" title="Loading...">
	<img src="<?php echo base_url().'images/indicator.gif';?>"> Loading patient encounter.
</div>
<div id="heading2"></div>
<div id="leftcol"></div>
<div id ="mainborder" class="ui-corner-all">
	<div id="maincontent">
		<ul id="encounter_tags"></ul>
		<button type="button" id="preview_encounter">Preview</button>
		<button type="button" id="detail_encounter">Details</button>
		<button type="button" id="save_draft">Save Draft</button>
		<button type="button" id="sign_encounter">Sign</button>
		<button type="button" id="delete_encounter">Delete</button>
		<hr class="ui-state-default"/>
		<div id="noshtabs">
			<div id="encounter_provider_tabs">
				<ul>
					<li><a href="#provider_encounter_tabs_1">HPI</a></li>
					<li><?php echo anchor('provider/encounters/ros/', 'ROS');?></li>
					<li><?php echo anchor('provider/encounters/oh/', 'Other History');?></li>
					<li><?php echo anchor('provider/encounters/vitals/', 'VS');?></li>
					<li><?php echo anchor('provider/encounters/pe/', 'PE');?></li>
					<li><?php echo anchor('provider/encounters/labs/', 'Labs');?></a></li>
					<li><?php echo anchor('provider/encounters/proc/', 'Procedure');?></li>
					<li><?php echo anchor('provider/encounters/assessment/', 'DX');?></li>
					<li><?php echo anchor('provider/encounters/orders/', 'Orders');?></li>
					<li><?php echo anchor('provider/encounters/billing/', 'Billing');?></li>
				</ul>
				<div id="provider_encounter_tabs_1" style="overflow:auto">
					<div id="hpi_form">
						<input type="hidden" name="hpi_old" id="hpi_old"/>
						<span style="float:left;width:370px">
							Preview:<br><textarea style="width:350px" rows="16" name="hpi" id="hpi" class="text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ch1_old"/>
						</span>
						<span>
							Patient Forms: <select id="hpi_pf_template" class="text ui-widget-content ui-corner-all"></select><br>
							Choose Template: <select id="hpi_template" class="hpi_template_choose text ui-widget-content ui-corner-all"></select><br>
							<br><button type="button" id="hpi_reset" class="reset nosh_button">Clear</button><?php echo $cpe;?><?php echo $wcc;?><?php echo $preg;?><?php echo $mtm;?>
							<div class="hpi_template_div">
								<br><form id="hpi_template_form" class="hpi_template_form ui-widget"></form>
							</div>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="detail_encounter_dialog" title="Edit Encounter">
	<form name="detail_encounter_form" id="detail_encounter_form">
		<div style="width:490px;float:left;margin:5px">
			<strong>Encounter Number:</strong> <span id="detail_encounter_number"></span>
		</div>
		<div style="width:490px">
			<div style="float:left;margin:5px"><label for="detail_encounter_date">Date of Service</label><br><input type="text" name="encounter_date" id="detail_encounter_date" style="width:140px" class="text ui-widget-content ui-corner-all" /></div>
			<div style="float:left;margin:5px"><label for="detail_encounter_time">Time of Service</label><br><input type="text" name="encounter_time" id="detail_encounter_time" style="width:140px" class="text ui-widget-content ui-corner-all" /></div>
			<div style="float:left;margin:5px"><label for="detail_encounter_location">Encounter Location</label><br><input type="text" name="encounter_location" id="detail_encounter_location" style="width:140px" class="text ui-widget-content ui-corner-all" /></div>
		</div>
		<div style="width:490px">
			<div style="float:left;margin:5px"><label for="detail_encounter_role">Provider Role</label><br><select name="encounter_role" id="detail_encounter_role" class="text ui-widget-content ui-corner-all"></select></div>
			<div id="detail_referring_provider_div">
				<div style="float:left;margin:5px"><label for="detail_referring_provider">Referring Provider</label><br><input type="text" name="referring_provider" id="detail_referring_provider" style="width:164px" class="text ui-widget-content ui-corner-all"/></div>
				<div style="float:left;margin:5px"><label for="detail_referring_provider_npi">Referring Provider NPI</label><br><input type="text" name="referring_provider_npi" id="detail_referring_provider_npi" style="width:140px" class="text ui-widget-content ui-corner-all"/></div>
			</div>
		</div>
		<div style="width:490px;float:left">
			<div style="float:left;margin:5px"><label for="encounter_cc">Chief Complaint</label><br><input type="text" name="encounter_cc" id="detail_encounter_cc" style="width:390px" class="text ui-widget-content ui-corner-all" /></div>
		</div>
		<div style="width:490px;float:left;margin:5px">
			<strong>Condtion Related To:</strong><br>
			<label for="detail_encounter_condition_work">Work: </label><select name="encounter_condition_work" id="detail_encounter_condition_work" class="text ui-widget-content ui-corner-all"></select><br>
			<label for="detail_encounter_condition_auto">Auto Accident: </label><select name="encounter_condition_auto" id="detail_encounter_condition_auto" class="text ui-widget-content ui-corner-all"></select><select name="encounter_condition_auto_state" id="detail_encounter_condition_auto_state" class="text ui-widget-content ui-corner-all"></select><br>
			<label for="detail_encounter_condition_other">Other Accident: </label><select name="encounter_condition_other" id="detail_encounter_condition_other" class="text ui-widget-content ui-corner-all"></select><br>
			<label for="detail_encounter_condition">Other: </label><input type="text" name="encounter_condition" id="detail_encounter_condition" style="width:300px" class="text ui-widget-content ui-corner-all" />
		</div>
		<div style="width:490px;float:left;margin:5px">
			<strong>Insurance information:</strong><br><div id="detail_encounter_copay"></div></div>
		</div>
	</form>
</div>
<div id="preview_dialog" title="Encounter Preview" style="font-size: 0.9em">
	<div id="preview"></div>
</div>
<div id="prenatal_dialog" title="Pregnancy Calculator" style="font-size: 0.9em">
	<form name="pregnancy_form" id="pregnancy_form">
	<input type="hidden" name="prenatal_dialog_origin" id="prenatal_dialog_origin"/>
		<button type="button" id="not_pregnant" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
			<span style="float:right;" class="ui-button-text">Currently Not Pregnant</span>
		</button><br><br>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>EDC by dates</legend>
			<table>
				<tr>
					<td><label for="pregnancy_lmp">Last menstrural period:</label></td>
					<td><input type="text" name="pregnancy_lmp" id="pregnancy_lmp" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="pregnancy_cycle">Number of days in cycle:</label></td>
					<td><input type="text" name="pregnancy_cycle" id="pregnancy_cycle" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
			</table><br>
			<button type="button" id="edc_lmp" class="nosh_button_check">Use for EDC</button> 
		</fieldset><br>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>EDC by ultrasound</legend>
			<table>
				<tr>
					<td><label for="pregnancy_us">Ultrasound EDC:</label></td>
					<td><input type="text" name="pregnancy_us" id="pregnancy_us" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
			</table><br>
			<button type="button" id="edc_us" class="nosh_button_check">Use for EDC</button>
		</fieldset><br>
		<table>
			<tr>
				<td><label for="pregnancy_edc">Consensus EDC:</label></td>
				<td><input type="hidden" name="pregnancy_edc" id="pregnancy_edc"/><div id="edc_text"></div></td>
			</tr>
		</table>
	</form>
</div>
<div id="eprescribe_dialog" title="E-Prescribing" style="font-size: 0.9em">
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
