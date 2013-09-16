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
		redirect_url: '<?php echo site_url("start");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: 300000
	});
	$("#preview_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
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
	$("#detail_encounter_condition_auto_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
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
		$("#detail_encounter_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
		$('#detail_encounter_time').timeEntry({spinnerImage: '<?php echo base_url()."images/spinnerDefault.png";?>',ampmPrefix: ' '});
		$("#detail_encounter_dialog").dialog('open');
	});
	$("#detail_encounter_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 500, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'Edit Encounter': function() {
				var date = $("#detail_encounter_date");
				var time = $("#detail_encounter_time");
				var location = $("#detail_encounter_location");
				var cc = $("#detail_encounter_cc");
				var work = $("#detail_encounter_condition_work");
				var auto = $("#detail_encounter_condition_auto");
				var other = $("#detail_encounter_condition_other");
				var bValid = true;
				bValid = bValid && checkEmpty(date,"Date of Service");
				bValid = bValid && checkEmpty(time,"Time of Service");
				bValid = bValid && checkEmpty(location,"Encounter Location");
				bValid = bValid && checkEmpty(cc,"Chief Complaint");
				bValid = bValid && checkEmpty(work,"Work Accident");
				bValid = bValid && checkEmpty(auto,"Auto Accident");
				bValid = bValid && checkEmpty(other,"Other Accident");
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
		if(confirm('Are you sure you want to sign this encounter?  The encounter cannot be edited in the future!')){ 
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
	$("#save_hpi").click(function(){
		var hpi = $("#hpi");
		var bValid = true;
		bValid = bValid && checkEmpty(hpi,"History of Present Illness");
		if (bValid) {
			var str = $("#hpi_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/hpi_save');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						var a = $("#hpi").val();
						$("#hpi_old").val(a);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_hpi").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_hpi');?>",
			success: function(data){
				$("#hpi").val(data);
			}
		});
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/get_hpi');?>",
		success: function(data){
			$("#hpi").val(data);
			$("#hpi_old").val(data);
		}
	});
	$("#hpi").focus();
	$('#hpi_generic_template').click(function(){
		$('#hpi_generic_fieldset').show('fast');
		$('#hpi_template_fieldset').hide('fast');
		$('#gen_location').focus();
	});
	$('#hpi_asthma_template').click(function(){
		$('#hpi_asthma_fieldset').show('fast');
		$('#hpi_template_fieldset').hide('fast');
		$("#asthma_frequency").focus();
	});
	$('#hpi_pain_template').click(function(){
		$('#hpi_pain_fieldset').show('fast');
		$('#hpi_template_fieldset').hide('fast');
		$("#pain_dx").focus();
	});
	$('#hpi_injury_template').click(function(){
		$('#hpi_injury_fieldset').show('fast');
		$('#hpi_template_fieldset').hide('fast');
		$("#injury_date").focus();
	});
	$('#hpi_sports_template').click(function(){
		$('#hpi_sports_fieldset').show('fast');
		$('#hpi_template_fieldset').hide('fast');
	});
	$('#hpi_birth_hx_template').click(function(){
		$('#hpi_birth_hx_fieldset').show('fast');
		$('#hpi_template_fieldset').hide('fast');
	});
	$('#hpi_wwe_template').click(function(){
		$('#hpi_wwe_fieldset').show('fast');
		$('#hpi_template_fieldset').hide('fast');
	});
	$('#hpi_prenatal_template').click(function(){
		$('#hpi_prenatal_fieldset').show('fast');
		$('#hpi_template_fieldset').hide('fast');
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
	
	$('#save_hpi_generic_form').click(function(){
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
		var intro = age + gender + " here with the following concerns.\n";
		var a = $("#gen_location").val();
		var b = $("#gen_duration").val();
		var c = $("#gen_modify").val();
		if(c){
			var c1 = 'Modifying Factors:  ' + c;
			if(b){
				var b1 = 'Duration:  ' + b + '\n';
				if(a){
					var a1 = 'Location/Description:  ' + a + '\n';	
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'Location/Description:  ' + a;	
				} else {
					var a1 = '';
				}
			}
		} else {
			var c1 = '';
			if(b){
				var b1 = 'Duration:  ' + b;
				if(a){
					var a1 = 'Location/Description:  ' + a + '\n';	
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'Location:  ' + a;	
				} else {
					var a1 = '';
				}
			}
		}
		$("#hpi").val(old1+intro+a1+b1+c1);
		$('#hpi_generic_form').clearForm();
		$("#hpi_generic_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$('#cancel_hpi_generic_form').click(function(){
		$('#hpi_generic_form').clearForm();
		$("#hpi_generic_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$('#save_hpi_asthma_form').click(function(){
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
		var intro = age + gender + " here for evaluation and management of asthma.\n";
		var a = $("#asthma_frequency").val();
		var b = $("#asthma_time").val();
		var c = $("#asthma_er").val();
		var d = $("#asthma_missed").val();
		var e = $("#asthma_awaken").val();
		var f = $("#asthma_restriction").val();
		var g = $("#asthma_infection").val();
		var h = $("#asthma_triggers").val();
		if(a){
			var a1 = 'Frequency of attacks per week:  ' + a + '\n';
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'Time of usual attacks:  ' + b + '\n';
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'Number of ER visits or hospitalizations:  ' + c + '\n';
		} else {
			var c1 = '';
		}
		if(d){
			var d1 = 'Number of school/work days missed:  ' + d + '\n';
		} else {
			var d1 = '';
		}
		if(e){
			var e1 = 'Nocturnal awakenings:  ' + e + '\n';
		} else {
			var e1 = '';
		}
		if(f){
			var f1 = 'Restriction of activities:  ' + f + '\n';
		} else {
			var f1 = '';
		}
		if(g){
			var g1 = 'Recent infections:  ' + g + '\n';
		} else {
			var g1 = '';
		}
		if(h){
			var h1 = 'Asthma triggers:  ' + h + '\n';
		} else {
			var h1 = '';
		}
		var full = intro+a1+b1+c1+d1+e1+f1+g1+h1;
		var len = full.length;
		var len1 = len - 1;
		var full1 = full.slice(0, len1);
		$("#hpi").val(old1+full1);
		$('#hpi_asthma_form').clearForm();
		$("#hpi_asthma_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$('#cancel_hpi_asthma_form').click(function(){
		$('#hpi_asthma_form').clearForm();
		$("#hpi_asthma_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$('#save_hpi_pain_form').click(function(){
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
		var intro = age + gender + " here for followup of chronic pain.\n";
		var a = $("#pain_dx").val();
		var b = $("#pain_meds").val();
		var c = $("#pain_tx").val();
		var d = $("#pain_scale").val();
		var e = $("#pain_relief").val();
		var f = $("#pain_activity").val();
		var g = $("#pain_interactions").val();
		var h = $("#pain_mood").val();
		var i = $("#pain_sleep").val();
		var j = $("#pain_work").val();
		var k = $("#pain_life").val();
		if(a){
			var a1 = 'Diagnosis:  ' + a + '\n';
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'Side effects from medications:  ' + b + '\n';
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'Other treatments:  ' + c + '\n';
		} else {
			var c1 = '';
		}
		if(d){
			var d1 = 'Pain scale (0-10):  ' + d + '\n';
		} else {
			var d1 = '';
		}
		if(e){
			var e1 = 'Relief (% improvement) since last visit:  ' + e + '\n';
		} else {
			var e1 = '';
		}
		if(f){
			var f1 = 'General activity:  ' + f + '\n';
		} else {
			var f1 = '';
		}
		if(g){
			var g1 = 'Interactions with other people:  ' + g + '\n';
		} else {
			var g1 = '';
		}
		if(h){
			var h1 = 'Mood:  ' + h + '\n';
		} else {
			var h1 = '';
		}
		if(i){
			var i1 = 'Sleep:  ' + i + '\n';
		} else {
			var i1 = '';
		}
		if(j){
			var j1 = 'Ability to work:  ' + j + '\n';
		} else {
			var j1 = '';
		}
		if(k){
			var k1 = 'Enjoyment of life:  ' + k + '\n';
		} else {
			var k1 = '';
		}
		var full = intro+a1+b1+c1+d1+e1+f1+g1+h1+i1+j1+k1;
		var len = full.length;
		var len1 = len - 1;
		var full1 = full.slice(0, len1);
		$("#hpi").val(old1+full1);
		$('#hpi_pain_form').clearForm();
		$("#hpi_pain_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$('#cancel_hpi_pain_form').click(function(){
		$('#hpi_pain_form').clearForm();
		$("#hpi_pain_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$("#injury_date").datepicker({dateFormat: 'MM d, yy', showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$('#save_hpi_injury_form').click(function(){
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
		var intro = age + gender + " here for injury evaluation and management.\n";
		var a = $("#injury_date").val();
		var b = $("#injury_desc").val();
		var c = $("#injury_tx").val();
		var d = $("#injury_scale").val();
		var e = $("#injury_relief").val();
		var f = $("#injury_activity").val();
		var g = $("#injury_work").val();
		if(a){
			var a1 = 'Date of injury:  ' + a + '\n';
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'Description of how injury occurred:  ' + b + '\n';
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'Treatments:  ' + c + '\n';
		} else {
			var c1 = '';
		}
		if(d){
			var d1 = 'Pain scale (0-10):  ' + d + '\n';
		} else {
			var d1 = '';
		}
		if(e){
			var e1 = 'Relief (% improvement) since last visit:  ' + e + '\n';
		} else {
			var e1 = '';
		}
		if(f){
			var f1 = 'General activity:  ' + f + '\n';
		} else {
			var f1 = '';
		}
		if(g){
			var g1 = 'Ability to work:  ' + g + '\n';
		} else {
			var g1 = '';
		}
		var full = intro+a1+b1+c1+d1+e1+f1+g1;
		var len = full.length;
		var len1 = len - 1;
		var full1 = full.slice(0, len1);
		$("#hpi").val(old1+full1);
		$('#hpi_injury_form').clearForm();
		$("#hpi_injury_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$('#cancel_hpi_injury_form').click(function(){
		$('#hpi_injury_form').clearForm();
		$("#hpi_injury_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$("#sports_normal").click(function(){
		var a = $('#sports_normal').attr('checked');
		if(a){
			$('#sports_illness_n').attr('checked',true);
			$('#sports_concussion_n').attr('checked',true);
			$('#sports_vision_n').attr('checked',true);
			$('#sports_murmur_n').attr('checked',true);
			$('#sports_heart_n').attr('checked',true);
			$('#sports_cp_n').attr('checked',true);
			$('#sports_lung_n').attr('checked',true);
			$('#sports_asthma_n').attr('checked',true);
			$('#sports_bone_n').attr('checked',true);
			$('#sports_testicle_n').attr('checked',true);
			$('#sports_surgery_n').attr('checked',true);
			$('#sports_epilepsy_n').attr('checked',true);
			$('#sports_absence_n').attr('checked',true);
		} else {
			$('#sports_illness_n').attr('checked',false);
			$('#sports_concussion_n').attr('checked',false);
			$('#sports_vision_n').attr('checked',false);
			$('#sports_murmur_n').attr('checked',false);
			$('#sports_heart_n').attr('checked',false);
			$('#sports_cp_n').attr('checked',false);
			$('#sports_lung_n').attr('checked',false);
			$('#sports_asthma_n').attr('checked',false);
			$('#sports_bone_n').attr('checked',false);
			$('#sports_testicle_n').attr('checked',false);
			$('#sports_surgery_n').attr('checked',false);
			$('#sports_epilepsy_n').attr('checked',false);
			$('#sports_absence_n').attr('checked',false);
		}
	})
	$("input[name='sports_illness']").click(function(){
		var a = $('#sports_illness_y').attr('checked');
		if(a){
			$('#sports_illness_input').show('fast');
			$('#sports_illness_text').focus();
		} else {
			$('#sports_illness_input').hide('fast');
		}
	});
	$("input[name='sports_concussion']").click(function(){
		var a = $('#sports_concussion_y').attr('checked');
		if(a){
			$('#sports_concussion_input').show('fast');
			$('#sports_concussion_text').focus();
		} else {
			$('#sports_concussion_input').hide('fast');
		}
	});
	$("input[name='sports_vision']").click(function(){
		var a = $('#sports_vision_y').attr('checked');
		if(a){
			$('#sports_vision_input').show('fast');
			$('#sports_vision_text').focus();
		} else {
			$('#sports_vision_input').hide('fast');
		}
	});
	$("input[name='sports_murmur']").click(function(){
		var a = $('#sports_murmur_y').attr('checked');
		if(a){
			$('#sports_murmur_input').show('fast');
			$('#sports_murmur_text').focus();
		} else {
			$('#sports_murmur_input').hide('fast');
		}
	});
	$("input[name='sports_heart']").click(function(){
		var a = $('#sports_heart_y').attr('checked');
		if(a){
			$('#sports_heart_input').show('fast');
			$('#sports_heart_text').focus();
		} else {
			$('#sports_heart_input').hide('fast');
		}
	});
	$("input[name='sports_cp']").click(function(){
		var a = $('#sports_cp_y').attr('checked');
		if(a){
			$('#sports_cp_input').show('fast');
			$('#sports_cp_text').focus();
		} else {
			$('#sports_cp_input').hide('fast');
		}
	});
	$("input[name='sports_lung']").click(function(){
		var a = $('#sports_lung_y').attr('checked');
		if(a){
			$('#sports_lung_input').show('fast');
			$('#sports_lung_text').focus();
		} else {
			$('#sports_lung_input').hide('fast');
		}
	});
	$("input[name='sports_asthma']").click(function(){
		var a = $('#sports_asthma_y').attr('checked');
		if(a){
			$('#sports_asthma_input').show('fast');
			$("#sports_asthma_text").focus();
		} else {
			$('#sports_asthma_input').hide('fast');
		}
	});
	$("input[name='sports_bone']").click(function(){
		var a = $('#sports_bone_y').attr('checked');
		if(a){
			$('#sports_bone_input').show('fast');
			$('#sports_bone_text').focus();
		} else {
			$('#sports_bone_input').hide('fast');
		}
	});
	$("input[name='sports_testicle']").click(function(){
		var a = $('#sports_testicle_y').attr('checked');
		if(a){
			$('#sports_testicle_input').show('fast');
			$("#sports_testicle_text").focus();
		} else {
			$('#sports_testicle_input').hide('fast');
		}
	});
	$("input[name='sports_epilepsy']").click(function(){
		var a = $('#sports_epilepsy_y').attr('checked');
		if(a){
			$('#sports_epilepsy_input').show('fast');
			$('#sports_epilepsy_text').focus();
		} else {
			$('#sports_epilepsy_input').hide('fast');
		}
	});
	$("input[name='sports_surgery']").click(function(){
		var a = $('#sports_surgery_y').attr('checked');
		if(a){
			$('#sports_surgery_input').show('fast');
			$('#sports_surgery_text').focus();
		} else {
			$('#sports_surgery_input').hide('fast');
		}
	});
	$("input[name='sports_absence']").click(function(){
		var a = $('#sports_absence_y').attr('checked');
		if(a){
			$('#sports_absence_input').show('fast');
			$('#sports_absence_text').focus();
		} else {
			$('#sports_absence_input').hide('fast');
		}
	});
	$('#save_hpi_sports_form').click(function(){
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
		var intro = age + gender + " here for a sports physical.\n";
		var a = $("input[name='sports_illness']:checked").val();
		var a0 = $("#sports_illness_text").val();
		var b = $("input[name='sports_concussion']:checked").val();
		var b0 = $("#sports_concussion_text").val();
		var c = $("input[name='sports_vision']:checked").val();
		var c0 = $("#sports_vision_text").val();
		var d = $("input[name='sports_murmur']:checked").val();
		var d0 = $("#sports_murmur_text").val();
		var e = $("input[name='sports_heart']:checked").val();
		var e0 = $("#sports_heart_text").val();
		var f = $("input[name='sports_cp']:checked").val();
		var f0 = $("#sports_cp_text").val();
		var g = $("input[name='sports_lung']:checked").val();
		var g0 = $("#sports_lung_text").val();
		var h = $("input[name='sports_asthma']:checked").val();
		var h0 = $("#sports_asthma_text").val();
		var i = $("input[name='sports_bone']:checked").val();
		var i0 = $("#sports_bone_text").val();
		var j = $("input[name='sports_testicle']:checked").val();
		var j0 = $("#sports_testicle_text").val();
		var k = $("input[name='sports_epilepsy']:checked").val();
		var k0 = $("#sports_epilepsy_text").val();
		var l = $("input[name='sports_surgery']:checked").val();
		var l0 = $("#sports_surgery_text").val();
		var m = $("input[name='sports_absence']:checked").val();
		var m0 = $("#sports_absence_text").val();
		if(a){
			var a1 = a + a0 + '\n';
		} else {
			var a1 = '';
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
		if(i){
			var i1 = i + i0 + '\n';
		} else {
			var i1 = '';
		}
		if(j){
			var j1 = j + j0 + '\n';
		} else {
			var j1 = '';
		}
		if(k){
			var k1 = k + k0 + '\n';
		} else {
			var k1 = '';
		}
		if(l){
			var l1 = l + l0 + '\n';
		} else {
			var l1 = '';
		}
		if(m){
			var m1 = m + m0 + '\n';
		} else {
			var m1 = '';
		}
		var full = intro+a1+b1+c1+d1+e1+f1+g1+h1+i1+j1+k1+l1+m1;
		var len = full.length;
		var len1 = len - 1;
		var full1 = full.slice(0, len1);
		$("#hpi").val(old1+full1);
		$('#hpi_sports_form').clearForm();
		$("#hpi_sports_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$('#cancel_hpi_sports_form').click(function(){
		$('#hpi_sports_form').clearForm();
		$("#hpi_sports_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});

	$("#birth_hx_type").addOption({"Vaginal delivery":"Vaginal delivery","Cesarean section":"Cesarean section","Assisted vaginal delivery":"Assisted vaginal delivery"}, false);
	$("input[name='birth_hx_pregnancy']").click(function(){
		var a = $('#birth_hx_pregnancy_y').attr('checked');
		if(a){
			$('#birth_hx_pregnancy_input').show('fast');
			$('#birth_hx_pregnancy_text').focus();
		} else {
			$('#birth_hx_pregnancy_input').hide('fast');
		}
	});
	$("input[name='birth_hx_delivery']").click(function(){
		var a = $('#birth_hx_delivery_y').attr('checked');
		if(a){
			$('#birth_hx_delivery_input').show('fast');
			$('#birth_hx_delivery_text').focus();
		} else {
			$('#birth_hx_delivery_input').hide('fast');
		}
	});
	$("input[name='birth_hx_postpartum']").click(function(){
		var a = $('#birth_hx_postpartum_y').attr('checked');
		if(a){
			$('#birth_hx_postpartum_input').show('fast');
			$('#birth_hx_postpartum_text').focus();
		} else {
			$('#birth_hx_postpartum_input').hide('fast');
		}
	});
	$("input[name='birth_hx_hearing']").click(function(){
		var a = $('#birth_hx_hearing_fail').attr('checked');
		if(a){
			$('#birth_hx_hearing_input').show('fast');
			$('#birth_hx_hearing_text').focus();
		} else {
			$('#birth_hx_hearing_input').hide('fast');
		}
	});
	$("input[name='birth_hx_metabolic']").click(function(){
		var a = $('#birth_hx_metabolic_y').attr('checked');
		if(a){
			$('#birth_hx_metabolic_input').show('fast');
			$('#birth_hx_metabolic_text').focus();
		} else {
			$('#birth_hx_metabolic_input').hide('fast');
		}
	});
	$('#save_hpi_birth_hx_form').click(function(){
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
		var intro = age + gender + " with the following birth history.\n";
		var a = $("input[name='birth_hx_pregnancy']:checked").val();
		var a0 = $("#birth_hx_pregnancy_text").val();
		var b = $("input[name='birth_hx_delivery']:checked").val();
		var b0 = $("#birth_hx_delivery_text").val();
		var c = $("input[name='birth_hx_postpartum']:checked").val();
		var c0 = $("#birth_hx_postpartum_text").val();
		var d = $("#birth_hx_type").val();
		var e = $("#birth_hx_ga").val();
		var f = $("#birth_hx_bw").val();
		var g = $("#birth_hx_apgar").val();
		var h = $("input[name='birth_hx_hearing']:checked").val();
		var h0 = $("#birth_hx_hearing_text").val();
		var i = $("input[name='birth_hx_metabolic']:checked").val();
		var i0 = $("#birth_hx_metabolic_text").val();
		if(a){
			var a1 = a + a0 +'\n';
		} else {
			var a1 = '';
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
			var d1 = 'Delivery type:  ' + d + '\n';
		} else {
			var d1 = '';
		}
		if(e){
			var e1 = 'Gestational age:  ' + e + '\n';
		} else {
			var e1 = '';
		}
		if(f){
			var f1 = 'Birth weight:  ' + f + '\n';
		} else {
			var f1 = '';
		}
		if(g){
			var g1 = 'Apgar scores:  ' + g + '\n';
		} else {
			var g1 = '';
		}
		if(h){
			var h1 = h + h0 + '\n';
		} else {
			var h1 = '';
		}
		if(i){
			var i1 = i + i0 + '\n';
		} else {
			var i1 = '';
		}
		var full = intro+a1+b1+c1+d1+e1+f1+g1+h1+i1;
		var len = full.length;
		var len1 = len - 1;
		var full1 = full.slice(0, len1);
		$("#hpi").val(old1+full1);
		$('#hpi_birth_hx_form').clearForm();
		$("#hpi_birth_hx_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$('#cancel_hpi_birth_hx_form').click(function(){
		$('#hpi_birth_hx_form').clearForm();
		$("#hpi_birth_hx_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	
	$("input[name='wwe_abnormal_pap']").click(function(){
		var a = $('#wwe_abnormal_pap_y').attr('checked');
		if(a){
			$('#wwe_abnormal_pap_input').show('fast');
			$('#wwe_abnormal_pap_text').focus();
		} else {
			$('#wwe_abnormal_pap_input').hide('fast');
		}
	});
	$("input[name='wwe_period_regular']").click(function(){
		var a = $('#wwe_period_regular_y').attr('checked');
		if(a){
			$('#wwe_period_regular_input').show('fast');
			$('#wwe_period_regular_text').focus();
		} else {
			$('#wwe_period_regular_input').hide('fast');
		}
	});
	$("input[name='wwe_period_bleeding']").click(function(){
		var a = $('#wwe_period_bleeding_y').attr('checked');
		if(a){
			$('#wwe_period_bleeding_input').show('fast');
			$('#wwe_period_bleeding_text').focus();
		} else {
			$('#wwe_period_bleeding_input').hide('fast');
		}
	});
	$("input[name='wwe_sex']").click(function(){
		var a = $('#wwe_sex_y').attr('checked');
		if(a){
			$('#wwe_sex_input').show('fast');
			$('#wwe_sex_text').focus();
		} else {
			$('#wwe_sex_input').hide('fast');
		}
	});
	$("input[name='wwe_birth_control']").click(function(){
		var a = $('#wwe_birth_control_y').attr('checked');
		if(a){
			$('#wwe_birth_control_input').show('fast');
			$('#wwe_birth_control_text').focus();
		} else {
			$('#wwe_birth_control_input').hide('fast');
		}
	});
	$("input[name='wwe_breast_exam']").click(function(){
		var a = $('#wwe_breast_exam_y').attr('checked');
		if(a){
			$('#wwe_breast_exam_input').show('fast');
			$('#wwe_breast_exam_text').focus();
		} else {
			$('#wwe_breast_exam_input').hide('fast');
		}
	});
	$("input[name='wwe_abuse']").click(function(){
		var a = $('#wwe_abuse_y').attr('checked');
		if(a){
			$('#wwe_abuse_input').show('fast');
			$('#wwe_abuse_text').focus();
		} else {
			$('#wwe_abuse_input').hide('fast');
		}
	});
	$('#save_hpi_wwe_form').click(function(){
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
		var intro = age + gender + " here for a well-woman checkup.\n";
		var a = $("input[name='wwe_abnormal_pap']:checked").val();
		var a0 = $("#wwe_abnormal_pap_text").val();
		var b = $("input[name='wwe_period_regular']:checked").val();
		var b0 = $("#wwe_period_regular_text").val();
		var c = $("input[name='wwe_period_bleeding']:checked").val();
		var c0 = $("#wwe_period_bleeding_text").val();
		var d = $("#wwe_period_days").val();
		var e = $("#wwe_period_duration").val();
		var f = $("#wwe_period_flow").val();
		var g = $("#wwe_period_pain").val();
		var h = $("#wwe_period_pms").val();
		var i = $("#wwe_sex_pain").val();
		var j = $("input[name='wwe_sex']:checked").val();
		var j0 = $("#wwe_sex_text").val();
		var k = $("input[name='wwe_birth_control']:checked").val();
		var k0 = $("#wwe_birth_control_text").val();
		var l = $("input[name='wwe_breast_exam']:checked").val();
		var l0 = $("#wwe_breast_exam_text").val();
		var m = $("input[name='wwe_abuse']:checked").val();
		var m0 = $("#wwe_abuse_text").val();
		if(a){
			var a1 = a + a0 +'\n';
		} else {
			var a1 = '';
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
			var d1 = 'Number of days between periods:  ' + d + '\n';
		} else {
			var d1 = '';
		}
		if(e){
			var e1 = 'Duration of periods:  ' + e + '\n';
		} else {
			var e1 = '';
		}
		if(f){
			var f1 = 'Period flow:  ' + f + '\n';
		} else {
			var f1 = '';
		}
		if(g){
			var g1 = 'Pain with periods (0-10):  ' + g + '\n';
		} else {
			var g1 = '';
		}
		if(h){
			var h1 = 'Pain with premenstrual tension syndrome (0-10):  ' + h + '\n';
		} else {
			var h1 = '';
		}
		if(i){
			var i1 = 'Pain with sex (0-10):  ' + i + '\n';
		} else {
			var i1 = '';
		}
		if(j){
			var j1 = j + j0 + '\n';
		} else {
			var j1 = '';
		}
		if(k){
			var k1 = k + k0 + '\n';
		} else {
			var k1 = '';
		}
		if(l){
			var l1 = l + l0 + '\n';
		} else {
			var l1 = '';
		}
		if(m){
			var m1 = m + m0 + '\n';
		} else {
			var m1 = '';
		}
		var full = intro+a1+b1+c1+d1+e1+f1+g1+h1+i1+j1+k1+l1+m1;
		var len = full.length;
		var len1 = len - 1;
		var full1 = full.slice(0, len1);
		$("#hpi").val(old1+full1);
		$('#hpi_wwe_form').clearForm();
		$("#hpi_wwe_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
	});
	$('#cancel_hpi_wwe_form').click(function(){
		$('#hpi_wwe_form').clearForm();
		$("#hpi_wwe_fieldset").hide('fast');
		$('#hpi_template_fieldset').show('fast');
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
	
	$("#dialog_load1").ajaxStop(function(){
		$(this).dialog("close");
	});
});
</script>
<div id="dialog_load1" title="Loading...">
	<img src="<?php echo base_url().'images/indicator.gif';?>"> Loading patient encounter.
</div>
<div id="heading2"></div>
<div id="leftcol"></div>
<div id ="mainborder">
	<div id="maincontent">
		<button type="button" id="preview_encounter">Preview</button>
		<button type="button" id="detail_encounter">Encounter Details</button>
		<button type="button" id="save_draft">Save Draft</button>
		<button type="button" id="sign_encounter">Sign Encounter</button>
		<button type="button" id="delete_encounter">Delete Encounter</button>
		<hr />
		<div id="noshtabs">
			<div id="encounter_provider_tabs">
				<ul>
					<li><a href="#provider_encounter_tabs_1">HPI</a></li>
					<li><?php echo anchor('provider/encounters/ros/', 'ROS');?></li>
					<li><?php echo anchor('provider/encounters/oh/', 'Other History');?></li>
					<li><?php echo anchor('provider/encounters/vitals/', 'Vital Signs');?></li>
					<li><?php echo anchor('provider/encounters/pe/', 'PE');?></li>
					<li><?php echo anchor('provider/encounters/labs/', 'Labs');?></a></li>
					<li><?php echo anchor('provider/encounters/proc/', 'Procedure');?></li>
					<li><?php echo anchor('provider/encounters/assessment/', 'Assessment');?></li>
					<li><?php echo anchor('provider/encounters/orders/', 'Orders');?></li>
					<li><?php echo anchor('provider/encounters/billing/', 'Billing');?></li>
				</ul>
			<div id="provider_encounter_tabs_1">
				<form id="hpi_form">
					<button type="button" id="save_hpi" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
						<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
						<span style="float:right;" class="ui-button-text">Save</span>
					</button> 
					<button type="button" id="cancel_hpi" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
						<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
						<span style="float:right;" class="ui-button-text">Cancel</span>
					</button>
					<input type="hidden" name="hpi_old" id="hpi_old"/>
					<hr />
					<table>
						<tr>
							<td valign="top">Preview:</td>
							<td><textarea style="width:500px" rows="10" name="hpi" id="hpi" class="text ui-widget-content ui-corner-all"></textarea></td>
							<td valign="top">
								<input type="button" id="hpi_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"><br>
								<?php echo $cpe;?><?php echo $wcc;?><?php echo $preg;?>
							</td>				
						</tr>
					</table>
				</form><br>
				<div id="hpi_template_fieldset">
					<fieldset class="ui-state-default ui-corner-all">
						<legend>Choose a template</legend>
						<input type="button" id="hpi_generic_template" value="Generic HPI" class="ui-button ui-state-default ui-corner-all">
						<input type="button" id="hpi_asthma_template" value="Asthma" class="ui-button ui-state-default ui-corner-all">
						<input type="button" id="hpi_pain_template" value="Chronic Pain" class="ui-button ui-state-default ui-corner-all">
						<input type="button" id="hpi_injury_template" value="Injury" class="ui-button ui-state-default ui-corner-all">
						<input type="button" id="hpi_sports_template" value="Sports Physicial" class="ui-button ui-state-default ui-corner-all">
						<?php echo $birth;?><?php echo $wwe;?><?php echo $prenatal;?><br>
					</fieldset>
				</div>
				<div id="hpi_generic_fieldset" style="display:none">
					<form id="hpi_generic_form">
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Generic HPI</legend>
							<table>
								<tr>
									<td>Location/Description:</td>
									<td><input type="text" name="gen_location" id="gen_location" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Duration:</td>
									<td><input type="text" name="gen_duration" id="gen_duration" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Modifying Factors:</td>
									<td><input type="text" name="gen_modify" id="gen_modify" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td></td>
									<td>
										<button type="button" id="save_hpi_generic_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
											<span style="float:right;" class="ui-button-text">Import</span>
										</button> 
										<button type="button" id="cancel_hpi_generic_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
											<span style="float:right;" class="ui-button-text">Cancel</span>
										</button>
									</td>
								</tr>
							</table>
						</fieldset>
					</form>
				</div>
				<div id="hpi_asthma_fieldset" style="display:none">
					<form id="hpi_asthma_form">
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Asthma</legend>
							<table>
								<tr>
									<td>Frequency of attacks/week:</td>
									<td><input type="text" name="asthma_frequency" id="asthma_frequency" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Time of usual attacks:</td>
									<td><input type="text" name="asthma_time" id="asthma_time" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Number of ER visits or hospitalizations:</td>
									<td><input type="text" name="asthma_er" id="asthma_er" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Number of school/work days missed:</td>
									<td><input type="text" name="asthma_missed" id="asthma_missed" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Nocturnal awakenings:</td>
									<td><input type="text" name="asthma_awaken" id="asthma_awaken" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Restriction of activities:</td>
									<td><input type="text" name="asthma_restriction" id="asthma_restriction" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Recent infections:</td>
									<td><input type="text" name="asthma_infection" id="asthma_infection" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Asthma triggers:</td>
									<td><input type="text" name="asthma_triggers" id="asthma_triggers" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td></td>
									<td>
										<button type="button" id="save_hpi_asthma_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
											<span style="float:right;" class="ui-button-text">Import</span>
										</button> 
										<button type="button" id="cancel_hpi_asthma_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
											<span style="float:right;" class="ui-button-text">Cancel</span>
										</button>
									</td>
								</tr>
							</table>
						</fieldset>
					</form>
				</div>
				<div id="hpi_pain_fieldset" style="display:none">
					<form id="hpi_pain_form">
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Chronic Pain</legend>
							<table>
								<tr>
									<td>Diagnosis:</td>
									<td><input type="text" name="pain_dx" id="pain_dx" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Side effects from medications:</td>
									<td><input type="text" name="pain_meds" id="pain_meds" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Other treatments:</td>
									<td><input type="text" name="pain_tx" id="pain_tx" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Pain scale (0-10):</td>
									<td><input type="text" name="pain_scale" id="pain_scale" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Relief (% improvement) since last visit:</td>
									<td><input type="text" name="pain_relief" id="pain_relief" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>General activity:</td>
									<td><input type="text" name="pain_activity" id="pain_activity" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Interactions with other people:</td>
									<td><input type="text" name="pain_interactions" id="pain_interactions" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Mood:</td>
									<td><input type="text" name="pain_mood" id="pain_mood" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Sleep:</td>
									<td><input type="text" name="pain_sleep" id="pain_sleep" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Ability to work:</td>
									<td><input type="text" name="pain_work" id="pain_work" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Enjoyment of life:</td>
									<td><input type="text" name="pain_life" id="pain_life" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td></td>
									<td>
										<button type="button" id="save_hpi_pain_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
											<span style="float:right;" class="ui-button-text">Import</span>
										</button> 
										<button type="button" id="cancel_hpi_pain_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
											<span style="float:right;" class="ui-button-text">Cancel</span>
										</button>
									</td>
								</tr>
							</table>
						</fieldset>
					</form>
				</div>
				<div id="hpi_injury_fieldset" style="display:none">
					<form id="hpi_injury_form">
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Injury</legend>
							<table>
								<tr>
									<td>Date of injury:</td>
									<td><input type="text" name="injury_date" id="injury_date" style="width:480px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Description of how injury occurred:</td>
									<td><input type="text" name="injury_desc" id="injury_desc" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Treatments:</td>
									<td><input type="text" name="injury_tx" id="injury_tx" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Pain scale (0-10):</td>
									<td><input type="text" name="injury_scale" id="injury_scale" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Relief (% improvement) since last visit:</td>
									<td><input type="text" name="injury_relief" id="injury_relief" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>General activity:</td>
									<td><input type="text" name="injury_activity" id="injury_activity" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Ability to work:</td>
									<td><input type="text" name="injury_work" id="injury_work" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td></td>
									<td>
										<button type="button" id="save_hpi_injury_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
											<span style="float:right;" class="ui-button-text">Import</span>
										</button> 
										<button type="button" id="cancel_hpi_injury_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
											<span style="float:right;" class="ui-button-text">Cancel</span>
										</button>
									</td>
								</tr>
							</table>
						</fieldset>
					</form>
				</div>
				<div id="hpi_sports_fieldset" style="display:none">
					<form id="hpi_sports_form">
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Sports Physical</legend>
							<table>
								<tr>
									<td>All normal:</td>
									<td><input type="checkbox" id="sports_normal"></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>Illness, surgery, or other medical conditions in the past 2 months:</td>
									<td><input name="sports_illness" id="sports_illness_n" value="No illness, surgery, or other medical conditions in the past 2 months." type="radio"> No</td>
									<td><input name="sports_illness" id="sports_illness_y" value="Illness, surgery, or other medical conditions in the past 2 months:  " type="radio"> Yes</td>
									<td><div id="sports_illness_input" style="display:none"><input type="text" name="sports_illness_text" id="sports_illness_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Concussion, skull fracture, or neck injury:</td>
									<td><input name="sports_concussion" id="sports_concussion_n" value="No history of concussion, skull fracture, or neck injury. " type="radio"> No</td>
									<td><input name="sports_concussion" id="sports_concussion_y" value="Concussion, skull fracture, or neck injury:  " type="radio"> Yes</td>
									<td><div id="sports_concussion_input" style="display:none"><input type="text" name="sports_concussion_text" id="sports_concussion_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Poor or abnormal vision or loss of an eye:</td>
									<td><input name="sports_vision" id="sports_vision_n" value="No history of poor, abnormal vision, or loss of an eye." type="radio"> No</td>
									<td><input name="sports_vision" id="sports_vision_y" value="Poor or abnormal vision or loss of an eye:  " type="radio"> Yes</td>
									<td><div id="sports_vision_input" style="display:none"><input type="text" name="sports_vision_text" id="sports_vision_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Rheumatic fever or heart murmur:</td>
									<td><input name="sports_murmur" id="sports_murmur_n" value="No history of rheumatic fever or heart murmur." type="radio"> No</td>
									<td><input name="sports_murmur" id="sports_murmur_y" value="Rheumatic fever or heart murmur:  " type="radio"> Yes</td>
									<td><div id="sports_murmur_input" style="display:none"><input type="text" name="sports_murmur_text" id="sports_murmur_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Heart condition:</td>
									<td><input name="sports_heart" id="sports_heart_n" value="No history of heart conditions." type="radio"> No</td>
									<td><input name="sports_heart" id="sports_heart_y" value="Heart condition: " type="radio"> Yes</td>
									<td><div id="sports_heart_input" style="display:none"><input type="text" name="sports_heart_text" id="sports_heart_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Chest discomfort during exercise:</td>
									<td><input name="sports_cp" id="sports_cp_n" value="No chest discomfort during exercise." type="radio"> No</td>
									<td><input name="sports_cp" id="sports_cp_y" value="Chest discomfort during exercise:  " type="radio"> Yes</td>
									<td><div id="sports_cp_input" style="display:none"><input type="text" name="sports_cp_text" id="sports_cp_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Lung condition or breathing difficulty:</td>
									<td><input name="sports_lung" id="sports_lung_n" value="No lung conditions or breathing difficulty." type="radio"> No</td>
									<td><input name="sports_lung" id="sports_lung_y" value="Lung condition or breathing difficulty:  " type="radio"> Yes</td>
									<td><div id="sports_lung_input" style="display:none"><input type="text" name="sports_lung_text" id="sports_lung_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Asthma or chronic bronchitis:</td>
									<td><input name="sports_asthma" id="sports_asthma_n" value="No history of asthma or chronic bronchitis." type="radio"> No</td>
									<td><input name="sports_asthma" id="sports_asthma_y" value="Asthma or chronic bronchitis:  " type="radio"> Yes</td>
									<td><div id="sports_asthma_input" style="display:none"><input type="text" name="sports_asthma_text" id="sports_asthma_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Any bone or joint injury:</td>
									<td><input name="sports_bone" id="sports_bone_n" value="No history of bone or joint injury." type="radio"> No</td>
									<td><input name="sports_bone" id="sports_bone_y" value="Any bone or joint injury:  " type="radio"> Yes</td>
									<td><div id="sports_bone_input" style="display:none"><input type="text" name="sports_bone_text" id="sports_bone_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<?php echo $sports1;?>
								<tr>
									<td>Epilepsy or other convulsive disorder:</td>
									<td><input name="sports_epilepsy" id="sports_epilepsy_n" value="No history of epilepsy or other convulsive disorders." type="radio"> No</td>
									<td><input name="sports_epilepsy" id="sports_epilepsy_y" value="Epilepsy or other convulsive disorder:  " type="radio"> Yes</td>
									<td><div id="sports_epilepsy_input" style="display:none"><input type="text" name="sports_epilepsy_text" id="sports_epilepsy_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Any other medical problem or surgical operation other than tonsillectomy:</td>
									<td><input name="sports_surgery" id="sports_surgery_n" value="No history of any other medical problem or surgical operation other than tonsillectomy." type="radio"> No</td>
									<td><input name="sports_surgery" id="sports_surgery_y" value="Any other medical problem or surgical operation other than tonsillectomy:  " type="radio"> Yes</td>
									<td><div id="sports_surgery_input" style="display:none"><input type="text" name="sports_surgery_text" id="sports_surgery_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<?php echo $sports2;?>
								<tr>
									<td>
										<button type="button" id="save_hpi_sports_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
											<span style="float:right;" class="ui-button-text">Import</span>
										</button>
										<button type="button" id="cancel_hpi_sports_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
											<span style="float:right;" class="ui-button-text">Cancel</span>
										</button>
									</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</table>
						</fieldset>
					</form>
				</div>
				<div id="hpi_birth_hx_fieldset" style="display:none">
					<form id="hpi_birth_hx_form">
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Birth History</legend>
							<table>
								<tr>
									<td>Pregnancy complications:</td>
									<td><input name="birth_hx_pregnancy" id="birth_hx_pregnancy_n" value="No complications of pregnancy." type="radio"> No</td>
									<td><input name="birth_hx_pregnancy" id="birth_hx_pregnancy_y" value="Pregnancy complications:  " type="radio"> Yes</td>
									<td><div id="birth_hx_pregnancy_input" style="display:none"><input type="text" name="birth_hx_pregnancy_text" id="birth_hx_pregnancy_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Delivery complications:</td>
									<td><input name="birth_hx_delivery" id="birth_hx_delivery_n" value="No complications of delivery." type="radio"> No</td>
									<td><input name="birth_hx_delivery" id="birth_hx_delivery_y" value="Delivery complications:  " type="radio"> Yes</td>
									<td><div id="birth_hx_delivery_input" style="display:none"><input type="text" name="birth_hx_delivery_text" id="birth_hx_delivery_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Postpartum complications:</td>
									<td><input name="birth_hx_postpartum" id="birth_hx_postpartum_n" value="No complications of postpartum." type="radio"> No</td>
									<td><input name="birth_hx_postpartum" id="birth_hx_postpartum_y" value="Postpartum complications:  " type="radio"> Yes</td>
									<td><div id="birth_hx_postpartum_input" style="display:none"><input type="text" name="birth_hx_postpartum_text" id="birth_hx_postpartum_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
							</table><br>
							<table>
								<tr>
									<td>Delivery type:</td>
									<td><select name="birth_hx_type" id="birth_hx_type" class="text ui-widget-content ui-corner-all"></select></td>
								</tr>
								<tr>
									<td>Gestational age:</td>
									<td><input type="text" name="birth_hx_ga" id="birth_hx_ga" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Birth weight:</td>
									<td><input type="text" name="birth_hx_bw" id="birth_hx_bw" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Apgar Scores:</td>
									<td><input type="text" name="birth_hx_apgar" id="birth_hx_apgar" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
							</table>
							<table>
								<tr>
									<td>Hearing screen:</td>
									<td><input name="birth_hx_hearing" id="birth_hx_hearing_pass" value="Passed hearing screen." type="radio"> Passed</td>
									<td><input name="birth_hx_hearing" id="birth_hx_hearing_fail" value="Failed hearing screen:  " type="radio"> Failed</td>
									<td><div id="birth_hx_hearing_input" style="display:none"><input type="text" name="birth_hx_hearing_text" id="birth_hx_hearing_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Metabolic screen:</td>
									<td><input name="birth_hx_metabolic" id="birth_hx_metabolic_n" value="Metabolic screen negative." type="radio"> Negative</td>
									<td><input name="birth_hx_metabolic" id="birth_hx_metabolic_y" value="Metabolic screen positive:  " type="radio"> Positive</td>
									<td><div id="birth_hx_metabolic_input" style="display:none"><input type="text" name="birth_hx_metabolic_text" id="birth_hx_metabolic_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
							</table><br>
							<button type="button" id="save_hpi_birth_hx_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
								<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
								<span style="float:right;" class="ui-button-text">Import</span>
							</button> 
							<button type="button" id="cancel_hpi_birth_hx_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
								<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
								<span style="float:right;" class="ui-button-text">Cancel</span>
							</button>
						</fieldset>
					</form>
				</div>
				<div id="hpi_wwe_fieldset" style="display:none">
					<form id="hpi_wwe_form">
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Well Woman Exam</legend>
							<table>
								<tr>
									<td>Abnormal pap smears in the past:</td>
									<td><input name="wwe_abnormal_pap" id="wwe_abnormal_pap_n" value="No abnormal pap smears in the past." type="radio"> No</td>
									<td><input name="wwe_abnormal_pap" id="wwe_abnormal_pap_y" value="Abnormal pap smears in the past:  " type="radio"> Yes</td>
									<td><div id="wwe_abnormal_pap_input" style="display:none"><input type="text" name="wwe_abnormal_pap_text" id="wwe_abnormal_pap_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Irregular periods:</td>
									<td><input name="wwe_period_regular" id="wwe_period_regular_n" value="Regular periods." type="radio"> No</td>
									<td><input name="wwe_period_regular" id="wwe_period_regular_y" value="Irregular periods:  " type="radio"> Yes</td>
									<td><div id="wwe_period_regular_input" style="display:none"><input type="text" name="wwe_period_regular_text" id="wwe_period_regular_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Bleeding between periods:</td>
									<td><input name="wwe_period_bleeding" id="wwe_period_bleeding_n" value="No bleeding between periods." type="radio"> No</td>
									<td><input name="wwe_period_bleeding" id="wwe_period_bleeding_y" value="Bleeding between periods:  " type="radio"> Yes</td>
									<td><div id="wwe_period_bleeding_input" style="display:none"><input type="text" name="wwe_period_bleeding_text" id="wwe_period_bleeding_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
							</table>
							<table>
								<tr>
									<td>Days between periods:</td>
									<td><input type="text" name="wwe_period_days" id="wwe_period_days" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Duration of periods:</td>
									<td><input type="text" name="wwe_period_duration" id="wwe_period_duration" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Period flow:</td>
									<td><input type="text" name="wwe_period_flow" id="wwe_period_flow" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Pain with periods (0-10):</td>
									<td><input type="text" name="wwe_period_pain" id="wwe_period_pain" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Pain with premenstrual tension syndrome (0-10):</td>
									<td><input type="text" name="wwe_period_pms" id="wwe_period_pms" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
								<tr>
									<td>Pain with sex (0-10):</td>
									<td><input type="text" name="wwe_sex_pain" id="wwe_sex_pain" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
								</tr>
							</table>
							<table>
								<tr>
									<td>Sexually active:</td>
									<td><input name="wwe_sex" id="wwe_sex_n" value="Currently not sexually active." type="radio"> No</td>
									<td><input name="wwe_sex" id="wwe_sex_y" value="Sexually active; number of partners:  " type="radio"> Yes</td>
									<td style="width=50px"></td>
									<td><div id="wwe_sex_input" style="display:none"> Number of partners: <input type="text" name="wwe_sex_text" id="wwe_sex_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Birth control:</td>
									<td><input name="wwe_birth_control" id="wwe_birth_control_n" value="No use of birth control." type="radio"> No</td>
									<td><input name="wwe_birth_control" id="wwe_birth_control_y" value="Birth control method:  " type="radio"> Yes</td>
									<td style="width=50px"></td>
									<td><div id="wwe_birth_control_input" style="display:none"> Birth control method: <input type="text" name="wwe_birth_control_text" id="wwe_birth_control_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Self breast exams:</td>
									<td><input name="wwe_breast_exam" id="wwe_breast_exam_n" value="Does not perform self breast exams." type="radio"> No</td>
									<td><input name="wwe_breast_exam" id="wwe_breast_exam_y" value="Self breast exam frequency:  " type="radio"> Yes</td>
									<td style="width=50px"></td>
									<td><div id="wwe_breast_exam_input" style="display:none"> Frequency: <input type="text" name="wwe_breast_exam_text" id="wwe_breast_exam_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Past or current history of abuse:</td>
									<td><input name="wwe_abuse" id="wwe_abuse_n" value="No past or current history of abuse." type="radio"> No</td>
									<td><input name="wwe_abuse" id="wwe_abuse_y" value="History of abuse:  " type="radio"> Yes</td>
									<td style="width=50px"></td>
									<td><div id="wwe_abuse_input" style="display:none"> Description: <input type="text" name="wwe_abuse_text" id="wwe_abuse_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
							</table><br>
							<button type="button" id="save_hpi_wwe_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
								<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
								<span style="float:right;" class="ui-button-text">Import</span>
							</button> 
							<button type="button" id="cancel_hpi_wwe_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
								<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
								<span style="float:right;" class="ui-button-text">Cancel</span>
							</button>
						</fieldset>
					</form>
				</div>
				<div id="hpi_prenatal_fieldset" style="display:none">
					<form id="hpi_prenatal_form">
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Prenatal Visit</legend>
							<table>
								<tr>
									<td>Estimated Gestational Age:</td>
									<td><input type="text" name="prenatal_ega" id="prenatal_ega" style="width:300px" class="text ui-widget-content ui-corner-all" /></td>
									<td>
										<button type="button" id="calculate_ega" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
											<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-calculator"></span>
											<span style="float:right;" class="ui-button-text">Pregnancy Calculator</span>
										</button>
									</td>
								</tr>
								<tr>
									<td>Estimated Due Date:</td>
									<td><input type="text" name="prenatal_duedate" id="prenatal_duedate" style="width:300px" class="text ui-widget-content ui-corner-all" /></td>
									<td></td>
								</tr>
							</table>
							<table>
								<tr>
									<td>Headache:</td>
									<td><input name="prenatal_headache" id="prenatal_headache_n" value="No headache." type="radio"> No</td>
									<td><input name="prenatal_headache" id="prenatal_headache_y" value="Headaches reported:  " type="radio"> Yes</td>
									<td><div id="prenatal_headache_input" style="display:none"><input type="text" name="prenatal_headache_text" id="prenatal_headache_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Vaginal Bleeding:</td>
									<td><input name="prenatal_bleeding" id="prenatal_bleeding_n" value="No vaginal bleeding." type="radio"> No</td>
									<td><input name="prenatal_bleeding" id="prenatal_bleeding_y"v alue="Vaginal bleeding reported:  " type="radio"> Yes</td>
									<td><div id="prenatal_bleeding_input" style="display:none"><input type="text" name="prenatal_bleeding_text" id="prenatal_bleeding_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Cramps/Pain:</td>
									<td><input name="prenatal_pain" id="prenatal_pain_n" value="No abdominal cramps or pain." type="radio"> No</td>
									<td><input name="prenatal_pain" id="prenatal_pain_y" value="Abdominal cramps or pain reported:  " type="radio"> Yes</td>
									<td><div id="prenatal_pain_input" style="display:none"><input type="text" name="prenatal_pain_text" id="prenatal_pain_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Nausea:</td>
									<td><input name="prenatal_nausea" id="prenatal_nausea_n" value="No nausea." type="radio"> No</td>
									<td><input name="prenatal_nausea" id="prenatal_nausea_y" value="Nausea reported:  " type="radio"> Yes</td>
									<td><div id="prenatal_nausea_input" style="display:none"><input type="text" name="prenatal_nausea_text" id="prenatal_nausea_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Vomiting:</td>
									<td><input name="prenatal_vomiting" id="prenatal_vomiting_n" value="No vomiting." type="radio"> No</td>
									<td><input name="prenatal_vomiting" id="prenatal_vomiting_y" value="Vomiting reported:  " type="radio"> Yes</td>
									<td><div id="prenatal_nausea_input" style="display:none"><input type="text" name="prenatal_vomiting_text" id="prenatal_vomiting_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Swelling:</td>
									<td><input name="prenatal_swelling" id="prenatal_swelling_n" value="No swelling." type="radio"> No</td>
									<td><input name="prenatal_swelling" id="prenatal_swelling_y" value="Swelling reported:  " type="radio"> Yes</td>
									<td><div id="prenatal_swelling_input" style="display:none"><input type="text" name="prenatal_swelling_text" id="prenatal_swelling_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
								<tr>
									<td>Fetal movement:</td>
									<td><input name="prenatal_movement" id="prenatal_movement_n" value="No fetal movement." type="radio"> No</td>
									<td><input name="prenatal_movement" id="prenatal_movement_y" value="Fetal movement reported:  " type="radio"> Yes</td>
									<td><div id="prenatal_movement_input" style="display:none"><input type="text" name="prenatal_movement_text" id="prenatal_movement_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>
							</table><br>
							<button type="button" id="save_hpi_prenatal_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
								<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
								<span style="float:right;" class="ui-button-text">Import</span>
							</button> 
							<button type="button" id="cancel_hpi_prenatal_form" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
								<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
								<span style="float:right;" class="ui-button-text">Cancel</span>
							</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="detail_encounter_dialog" title="Edit Encounter" style="font-size: 0.9em">
	<form name="detail_encounter_form" id="detail_encounter_form">
		<table>
			<tr>
				<td>Encounter Number:</td>
				<td><div id="detail_encounter_number"></div></td>
			</tr>
			<tr>
				<td><label for="encounter_date">Date of Service</label></td>
				<td colspan="3"><input type="text" name="encounter_date" id="detail_encounter_date" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="encounter_time">Time of Service</label></td>
				<td colspan="3"><input type="text" name="encounter_time" id="detail_encounter_time" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="encounter_location">Encounter Location</label></td>
				<td colspan="3"><input type="text" name="encounter_location" id="detail_encounter_location" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="encounter_cc">Chief Complaint</label></td>
				<td colspan="3"><input type="text" name="encounter_cc" id="detail_encounter_cc" size="42" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="encounter_condition">Condition Related To</label></td>
				<td colspan="3"><input type="text" name="encounter_condition" id="detail_encounter_condition" size="42" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td>Condition Related To Work</td>
				<td><select name="encounter_condition_work" id="detail_encounter_condition_work" class="text ui-widget-content ui-corner-all"></select></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Condition Related To Auto Accident</td>
				<td><select name="encounter_condition_auto" id="detail_encounter_condition_auto" class="text ui-widget-content ui-corner-all"></select></td>
				<td>State accident occurred:</td>
				<td><select name="encounter_condition_auto_state" id="detail_encounter_condition_auto_state" class="text ui-widget-content ui-corner-all"></select></td>
			</tr>
			<tr>
				<td>Condition Related To Other Accident</td>
				<td><select name="encounter_condition_other" id="detail_encounter_condition_other" class="text ui-widget-content ui-corner-all"></select></td>
				<td></td>
				<td></td>
			</tr>
		</table>
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
			<button type="button" id="edc_lmp" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
				<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-check"></span>
				<span style="float:right;" class="ui-button-text">Use for EDC</span>
			</button> 
		</fieldset><br>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>EDC by ultrasound</legend>
			<table>
				<tr>
					<td><label for="pregnancy_us">Ultrasound EDC:</label></td>
					<td><input type="text" name="pregnancy_us" id="pregnancy_us" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
			</table><br>
			<button type="button" id="edc_us" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
				<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-check"></span>
				<span style="float:right;" class="ui-button-text">Use for EDC</span>
			</button>
		</fieldset><br>
		<table>
			<tr>
				<td><label for="pregnancy_edc">Consensus EDC:</label></td>
				<td><input type="hidden" name="pregnancy_edc" id="pregnancy_edc"/><div id="edc_text"></div></td>
			</tr>
		</table>
	</form>
</div>
