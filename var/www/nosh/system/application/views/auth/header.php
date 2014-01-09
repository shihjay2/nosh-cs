<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo 'NOS'.'H Ch'.'art'.'ing'.'Sys'.'tem';?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="author" content="root"/>
<meta name="description" content="NOSH (New, Open Source, Health) Record System" />
<meta name="keywords" content="NOSH, Electronic Medical Record" />
<meta name="robots" content="noindex, nofollow" />
<meta name="rating" content="general" />
<meta name="language" content="english" />
<meta name="copyright" content="Copyright (c) <?php echo date("Y");?> Michael Chen, MD" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/cupertino/jquery-ui.css" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/ui.jqgrid.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/jquery.jgrowl.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/fullcalendar.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/fullcalendar.print.css';?>" rel="Stylesheet" media="print"/>
<link type="text/css" href="<?php echo base_url().'css/styledButton.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/main.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/jquery.timepicker.css';?>" rel="Stylesheet" />
<!--<link type="text/css" href="<?php echo base_url().'js/plugins/buttonCaptcha/jquery.buttonCaptcha.styles.css';?>" rel="stylesheet" />-->
<link type="text/css" href="<?php echo base_url().'css/jquery.signaturepad.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/searchFilter.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/ui.multiselect.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/chosen.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/jquery.Jcrop.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/jquery.realperson.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/tagit.css';?>" rel="Stylesheet" />
<!--<link type="text/css" href="<?php echo base_url().'css/jquery.tagsinput.css';?>" rel="Stylesheet" />-->
<!--<link type="text/css" href="<?php echo base_url().'css/jquery-pdfdoc.css';?>" rel="Stylesheet" />-->
<script type="text/javascript" src="<?php echo base_url().'js/jquery-migrate-1.1.0.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.ajaxQueue.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/i18n/grid.locale-en.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.jqGrid.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/ajaxupload.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.timepicker.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.jgrowl.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.maskedinput.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.selectboxes.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/fullcalendar.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery-idleTimeout.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.styledButton.js';?>"></script>
<!--<script type="text/javascript" src="<?php echo base_url().'js/plugins/buttonCaptcha/jquery.buttonCaptcha.js';?>"></script>-->
<script type="text/javascript" src="<?php echo base_url().'js/jquery.iframer.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.serializeObject.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.signaturepad.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/json2.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/highcharts.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/exporting.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.dform-1.0.0.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/grid.addons.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/grid.postext.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/grid.setcolumns.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.contextmenu.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.searchFilter.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.tablednd.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.chosen.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/ui.multiselect.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.themeswitcher.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.color.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.Jcrop.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.realperson.js';?>"></script>
<!--<script type="text/javascript" src="<?php echo base_url().'js/jquery.tagsinput.js';?>"></script>-->
<script type="text/javascript" src="<?php echo base_url().'js/tagit-themeroller.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.jstree.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.populate.js';?>"></script>
<!--<script type="text/javascript" src="<?php echo base_url().'js/pdf.js';?>"></script>-->
<!--<script type="text/javascript" src="<?php echo base_url().'js/jquery-pdfdoc.js';?>"></script>-->
<script type="text/javascript">
$(function() {
	$("#dashboard_admin_tabs").tabs();
});
$(function(){
	$("#users_admin_tabs").tabs();
});
$(function() {
	$("#schedule_admin_tabs").tabs();
});
$(function() {
	$("#schedule_provider_tabs").tabs();
});
$(function() {
	$("#schedule_assistant_tabs").tabs();
});
$(function() {
	$("#schedule_billing_tabs").tabs();
});
$(function() {
	$("#office_provider_tabs").tabs();
});
$(function() {
	$("#office_assistant_tabs").tabs();
});
$(function() {
	$("#office_billing_tabs").tabs();
});
$(function() {
	$("#billing_provider_tabs").tabs();
});
$(function() {
	$("#billing_assistant_tabs").tabs();
});
$(function() {
	$("#billing_billing_tabs").tabs();
});
$(function() {
	$("#messaging_provider_tabs").tabs();
});
$(function() {
	$("#messaging_assistant_tabs").tabs();
});
$(function() {
	$("#messaging_billing_tabs").tabs();
});
$(function() {
	$("#encounter_provider_tabs").tabs({
		ajaxOptions: {cache: true},
		cache: true,
		beforeLoad: function(event, ui) {
			if ($(ui.panel).html()) {
				event.preventDefault()
			}
		},
		activate: function(event, ui) {
			var $tabs = $('#encounter_provider_tabs').tabs();
			var selected = $tabs.tabs('option', 'active');
			var isValid = true;
			if (selected != 0) {
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
			if (selected != 2) {
				var old2a = $("#oh_pmh_old").val();
				var new2a = $("#oh_pmh").val();
				var old2b = $("#oh_psh_old").val();
				var new2b = $("#oh_psh").val();
				var old2c = $("#oh_fh_old").val();
				var new2c = $("#oh_fh").val();
				if (old2a != new2a || old2b != new2b || old2c != new2c) {
					var oh_str = $("#oh_form").serialize();
					if(oh_str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/encounters/oh_save');?>",
							data: oh_str,
							success: function(data){
								$.jGrowl(data);
								var a = $("#oh_pmh").val();
								var b = $("#oh_psh").val();
								var c = $("#oh_fh").val();
								$("#oh_pmh_old").val(a);
								$("#oh_psh_old").val(b);
								$("#oh_fh_old").val(c);
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			}
			if (selected != 3) {
				var old3a = $("#vitals_weight_old").val();
				var new3a = $("#vitals_weight").val();
				var old3b = $("#vitals_height_old").val();
				var new3b = $("#vitals_height").val();
				var old3c = $("#vitals_BMI_old").val();
				var new3c = $("#vitals_BMI").val();
				var old3d = $("#vitals_headcircumference_old").val();
				var new3d = $("#vitals_headcircumference").val();
				var old3e = $("#vitals_temp_old").val();
				var new3e = $("#vitals_temp").val();
				var old3f = $("#vitals_temp_method_old").val();
				var new3f = $("#vitals_temp_method").val();
				var old3g = $("#vitals_bp_systolic_old").val();
				var new3g = $("#vitals_bp_systolic").val();
				var old3h = $("#vitals_bp_diastolic_old").val();
				var new3h = $("#vitals_bp_diastolic").val();
				var old3i = $("#vitals_bp_position_old").val();
				var new3i = $("#vitals_bp_position").val();
				var old3j = $("#vitals_pulse_old").val();
				var new3j = $("#vitals_pulse").val();
				var new3k = $("#vitals_respirations").val();
				var old3k = $("#vitals_respirations_old").val();
				var old3l = $("#vitals_o2_sat_old").val();
				var new3l = $("#vitals_o2_sat").val();
				var old3m = $("#vitals_vitals_other_old").val();
				var new3m = $("#vitals_vitals_other").val();
				if (old3a != new3a || old3b != new3b || old3c != new3c || old3d != new3d || old3e != new3e || old3f != new3f || old3g != new3g || old3h != new3h || old3i != new3i || old3j != new3j || old3k != new3k || old3l != new3l || old3m != new3m) {
					var vitals_str = $("#vitals_form").serialize();
					if(vitals_str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/encounters/vitals_save');?>",
							data: vitals_str,
							success: function(data){
								$.jGrowl(data);
								var a = $("#vitals_weight").val();
								var b = $("#vitals_height").val();
								var c = $("#vitals_BMI").val();
								var d = $("#vitals_headcircumference").val();
								var e = $("#vitals_temp").val();
								var f = $("#vitals_temp_method").val();
								var g = $("#vitals_bp_systolic").val();
								var h = $("#vitals_bp_diastolic").val();
								var i = $("#vitals_bp_position").val();
								var j = $("#vitals_pulse").val();
								var k = $("#vitals_respirations").val();
								var l = $("#vitals_o2_sat").val();
								var m = $("#vitals_vitals_other").val();
								$("#vitals_weight_old").val(a);
								$("#vitals_height_old").val(b);
								$("#vitals_BMI_old").val(c);
								$("#vitals_headcircumference_old").val(d);
								$("#vitals_temp_old").val(e);
								$("#vitals_temp_method_old").val(f);
								$("#vitals_bp_systolic_old").val(g);
								$("#vitals_bp_diastolic_old").val(h);
								$("#vitals_bp_position_old").val(i);
								$("#vitals_pulse_old").val(j);
								$("#vitals_respirations_old").val(k);
								$("#vitals_o2_sat_old").val(l);
								$("#vitals_vitals_other_old").val(m);
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			}
			if (selected != 6) {
				var old6a = $("#procedure_type_old").val();
				var new6a = $("#procedure_type").val();
				var old6b = $("#procedure_cpt_old").val();
				var new6b = $("#procedure_cpt").val();
				var old6c = $("#procedure_description_old").val();
				var new6c = $("#procedure_description").val();
				var old6d = $("#procedure_complications_old").val();
				var new6d = $("#procedure_complications").val();
				var old6e = $("#procedure_ebl_old").val();
				var new6e = $("#procedure_ebl").val();
				if (old6a != new6a || old6b != new6b || old6c != new6c || old6d != new6d || old6e != new6e) {
					var proc_str = $("#procedure_form").serialize();
					if(proc_str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/encounters/proc_save');?>",
							data: proc_str,
							success: function(data){
								$.jGrowl(data);
								var a = $("#procedure_type").val();
								var b = $("#procedure_description").val();
								var c = $("#procedure_complications").val();
								var d = $("#procedure_ebl").val();
								var e = $("#procedure_cpt").val();
								$("#procedure_type_old").val(a);
								$("#procedure_description_old").val(b);
								$("#procedure_complications_old").val(c);
								$("#procedure_ebl_old").val(d);
								$("#procedure_cpt_old").val(e);
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			}
			if (selected != 7) {
				var old7a = $("#assessment_icd1_old").val();
				var new7a = $("#assessment_icd1").val();
				var old7b = $("#assessment_icd2_old").val();
				var new7b = $("#assessment_icd2").val();
				var old7c = $("#assessment_icd3_old").val();
				var new7c = $("#assessment_icd3").val();
				var old7d = $("#assessment_icd4_old").val();
				var new7d = $("#assessment_icd4").val();
				var old7e = $("#assessment_icd5_old").val();
				var new7e = $("#assessment_icd5").val();
				var old7f = $("#assessment_icd6_old").val();
				var new7f = $("#assessment_icd6").val();
				var old7g = $("#assessment_icd7_old").val();
				var new7g = $("#assessment_icd7").val();
				var old7h = $("#assessment_icd8_old").val();
				var new7h = $("#assessment_icd8").val();
				var old7i = $("#assessment_1_old").val();
				var new7i = $("#assessment_1").val();
				var old7j = $("#assessment_2_old").val();
				var new7j = $("#assessment_2").val();
				var old7k = $("#assessment_3_old").val();
				var new7k = $("#assessment_3").val();
				var old7l = $("#assessment_4_old").val();
				var new7l = $("#assessment_4").val();
				var old7m = $("#assessment_5_old").val();
				var new7m = $("#assessment_5").val();
				var old7n = $("#assessment_6_old").val();
				var new7n = $("#assessment_6").val();
				var old7o = $("#assessment_7_old").val();
				var new7o = $("#assessment_7").val();
				var old7p = $("#assessment_8_old").val();
				var new7p = $("#assessment_8").val();
				var old7q = $("#assessment_other_old").val();
				var new7q = $("#assessment_other").val();
				var old7r = $("#assessment_ddx_old").val();
				var new7r = $("#assessment_ddx").val();
				var old7s = $("#assessment_notes_old").val();
				var new7s = $("#assessment_notes").val();
				if (old7a != new7a || old7b != new7b || old7c != new7c || old7d != new7d || old7e != new7e || old7f != new7f || old7g != new7g || old7h != new7h || old7i != new7i || old7j != new7j || old7k != new7k || old7l != new7l || old7m != new7m || old7n != new7n || old7o != new7o || old7p != new7p || old7q != new7q || old7r != new7r || old7s != new7s) {
					var assessment_str = $("#assessment_form").serialize();
					if(assessment_str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/encounters/assessment_save');?>",
							data: assessment_str,
							success: function(data){
								$.jGrowl(data);
								var a = $("#assessment_icd1").val();
								var b = $("#assessment_icd2").val();
								var c = $("#assessment_icd3").val();
								var d = $("#assessment_icd4").val();
								var e = $("#assessment_icd5").val();
								var f = $("#assessment_icd6").val();
								var g = $("#assessment_icd7").val();
								var h = $("#assessment_icd8").val();
								var i = $("#assessment_1").val();
								var j = $("#assessment_2").val();
								var k = $("#assessment_3").val();
								var l = $("#assessment_4").val();
								var m = $("#assessment_5").val();
								var n = $("#assessment_6").val();
								var o = $("#assessment_7").val();
								var p = $("#assessment_8").val();
								var q = $("#assessment_other").val();
								var r = $("#assessment_ddx").val();
								var s = $("#assessment_notes").val();
								$("#assessment_icd1_old").val(a);
								$("#assessment_icd2_old").val(b);
								$("#assessment_icd3_old").val(c);
								$("#assessment_icd4_old").val(d);
								$("#assessment_icd5_old").val(e);
								$("#assessment_icd6_old").val(f);
								$("#assessment_icd7_old").val(g);
								$("#assessment_icd8_old").val(h);
								$("#assessment_1_old").val(i);
								$("#assessment_2_old").val(j);
								$("#assessment_3_old").val(k);
								$("#assessment_4_old").val(l);
								$("#assessment_5_old").val(m);
								$("#assessment_6_old").val(n);
								$("#assessment_7_old").val(o);
								$("#assessment_8_old").val(p);
								$("#assessment_other_old").val(q);
								$("#assessment_ddx_old").val(r);
								$("#assessment_notes_old").val(s);
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('provider/encounters/get_billing');?>",
									dataType: "json",
									success: function(data){
										$("#billing_icd").removeOption(/./);
										$("#billing_icd").addOption(data, false);
									}
								});
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			}
			if (selected != 8) {
				var old8a = $("#orders_plan_old").val();
				var new8a = $("#orders_plan").val();
				var old8b = $("#orders_duration_old").val();
				var new8b = $("#orders_duration").val();
				var old8c = $("#orders_followup_old").val();
				var new8c = $("#orders_followup").val();
				if (old8a != new8a || old8b != new8b || old8c != new8c) {
					var orders_str = $("#orders_form").serialize();
					if(orders_str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/encounters/orders_save');?>",
							data: orders_str,
							success: function(data){
								$.jGrowl(data);
								var a = $("#orders_plan").val();
								var b = $("#orders_duration").val();
								var c = $("#orders_followup").val();
								$("#orders_plan_old").val(a);
								$("#orders_duration_old").val(b);
								$("#orders_followup_old").val(c);
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			}
			if (selected != 9) {
				var old9a = $("#insurance_id_1_old").val();
				var new9a = $("#insurance_id_1").val();
				var old9b = $("#insurance_id_2_old").val();
				var new9b = $("#insurance_id_2").val();
				var old9c = $("#billing_bill_complex_old").val();
				var new9c = $("#billing_bill_complex").val();
				if (old9a != new9a || old9b != new9b || old9c != new9c) {
					var ins1 = $("#insurance_id_1").val();
					var ins2 = $("#insurance_id_2").val();
					var bc = $("#billing_bill_complex").val();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/billing_save1');?>",
						data: "insurance_id_1=" + ins1 + "&insurance_id_2=" + ins2 + "&bill_complex=" + bc,
						success: function(data){
							if (data == 'Close Chart') {
								window.location = "<?php echo site_url();?>";
							} else {
								$.jGrowl(data);
								$("#insurance_id_1_old").val(ins1);
								$("#insurance_id_2_old").val(ins2);
								$("#billing_bill_complex_old").val(bc);
							}
						}
					});
				}
			}
			if (selected == 9) {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/check_assessment');?>",
					success: function(data){
						if (data == "OK!") {
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/encounters/compile_billing');?>",
								success: function(data){
									$.jGrowl(data);
									jQuery("#cpt_list").trigger("reloadGrid");
								}
							});
						} else {
							$.jGrowl(data);
						}
						jQuery("#cpt_list").trigger("reloadGrid");
					}
				});
			}
			return isValid;
		}
	});
});

$(function() {
	$("#encounter_assistant_tabs").tabs({
		ajaxOptions: {cache: true},
		cache: true,
		beforeLoad: function(event, ui) {
			if ($(ui.panel).html()) {
				event.preventDefault()
			}
		},
		activate: function(event, ui) {
			var $tabs = $('#encounter_assistant_tabs').tabs();
			var selected = $tabs.tabs('option', 'active');
			var isValid = true;
			if (selected != 0) {
				var old2a = $("#oh_pmh_old").val();
				var new2a = $("#oh_pmh").val();
				var old2b = $("#oh_psh_old").val();
				var new2b = $("#oh_psh").val();
				var old2c = $("#oh_fh_old").val();
				var new2c = $("#oh_fh").val();
				if (old2a != new2a || old2b != new2b || old2c != new2c) {
					var oh_str = $("#oh_form").serialize();
					if(oh_str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('assistant/encounters/oh_save');?>",
							data: oh_str,
							success: function(data){
								$.jGrowl(data);
								var a = $("#oh_pmh").val();
								var b = $("#oh_psh").val();
								var c = $("#oh_fh").val();
								$("#oh_pmh_old").val(a);
								$("#oh_psh_old").val(b);
								$("#oh_fh_old").val(c);
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			}
			if (selected != 1) {
				var old3a = $("#vitals_weight_old").val();
				var new3a = $("#vitals_weight").val();
				var old3b = $("#vitals_height_old").val();
				var new3b = $("#vitals_height").val();
				var old3c = $("#vitals_BMI_old").val();
				var new3c = $("#vitals_BMI").val();
				var old3d = $("#vitals_headcircumference_old").val();
				var new3d = $("#vitals_headcircumference").val();
				var old3e = $("#vitals_temp_old").val();
				var new3e = $("#vitals_temp").val();
				var old3f = $("#vitals_temp_method_old").val();
				var new3f = $("#vitals_temp_method").val();
				var old3g = $("#vitals_bp_systolic_old").val();
				var new3g = $("#vitals_bp_systolic").val();
				var old3h = $("#vitals_bp_diastolic_old").val();
				var new3h = $("#vitals_bp_diastolic").val();
				var old3i = $("#vitals_bp_position_old").val();
				var new3i = $("#vitals_bp_position").val();
				var old3j = $("#vitals_pulse_old").val();
				var new3j = $("#vitals_pulse").val();
				var new3k = $("#vitals_respirations").val();
				var old3k = $("#vitals_respirations_old").val();
				var old3l = $("#vitals_o2_sat_old").val();
				var new3l = $("#vitals_o2_sat").val();
				var old3m = $("#vitals_vitals_other_old").val();
				var new3m = $("#vitals_vitals_other").val();
				if (old3a != new3a || old3b != new3b || old3c != new3c || old3d != new3d || old3e != new3e || old3f != new3f || old3g != new3g || old3h != new3h || old3i != new3i || old3j != new3j || old3k != new3k || old3l != new3l || old3m != new3m) {
					var vitals_str = $("#vitals_form").serialize();
					if(vitals_str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('assistant/encounters/vitals_save');?>",
							data: vitals_str,
							success: function(data){
								$.jGrowl(data);
								var a = $("#vitals_weight").val();
								var b = $("#vitals_height").val();
								var c = $("#vitals_BMI").val();
								var d = $("#vitals_headcircumference").val();
								var e = $("#vitals_temp").val();
								var f = $("#vitals_temp_method").val();
								var g = $("#vitals_bp_systolic").val();
								var h = $("#vitals_bp_diastolic").val();
								var i = $("#vitals_bp_position").val();
								var j = $("#vitals_pulse").val();
								var k = $("#vitals_respirations").val();
								var l = $("#vitals_o2_sat").val();
								var m = $("#vitals_vitals_other").val();
								$("#vitals_weight_old").val(a);
								$("#vitals_height_old").val(b);
								$("#vitals_BMI_old").val(c);
								$("#vitals_headcircumference_old").val(d);
								$("#vitals_temp_old").val(e);
								$("#vitals_temp_method_old").val(f);
								$("#vitals_bp_systolic_old").val(g);
								$("#vitals_bp_diastolic_old").val(h);
								$("#vitals_bp_position_old").val(i);
								$("#vitals_pulse_old").val(j);
								$("#vitals_respirations_old").val(k);
								$("#vitals_o2_sat_old").val(l);
								$("#vitals_vitals_other_old").val(m);
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			}
			if (selected != 3) {
				var old6a = $("#procedure_type_old").val();
				var new6a = $("#procedure_type").val();
				var old6b = $("#procedure_cpt_old").val();
				var new6b = $("#procedure_cpt").val();
				var old6c = $("#procedure_description_old").val();
				var new6c = $("#procedure_description").val();
				var old6d = $("#procedure_complications_old").val();
				var new6d = $("#procedure_complications").val();
				var old6e = $("#procedure_ebl_old").val();
				var new6e = $("#procedure_ebl").val();
				if (old6a != new6a || old6b != new6b || old6c != new6c || old6d != new6d || old6e != new6e) {
					var proc_str = $("#procedure_form").serialize();
					if(proc_str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('assistant/encounters/proc_save');?>",
							data: proc_str,
							success: function(data){
								$.jGrowl(data);
								var a = $("#procedure_type").val();
								var b = $("#procedure_description").val();
								var c = $("#procedure_complications").val();
								var d = $("#procedure_ebl").val();
								var e = $("#procedure_cpt").val();
								$("#procedure_type_old").val(a);
								$("#procedure_description_old").val(b);
								$("#procedure_complications_old").val(c);
								$("#procedure_ebl_old").val(d);
								$("#procedure_cpt_old").val(e);
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			}
			if (selected != 4) {
				var old8a = $("#orders_plan_old").val();
				var new8a = $("#orders_plan").val();
				var old8b = $("#orders_duration_old").val();
				var new8b = $("#orders_duration").val();
				var old8c = $("#orders_followup_old").val();
				var new8c = $("#orders_followup").val();
				if (old8a != new8a || old8b != new8b || old8c != new8c) {
					var orders_str = $("#orders_form").serialize();
					if(orders_str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('assistant/encounters/orders_save');?>",
							data: orders_str,
							success: function(data){
								$.jGrowl(data);
								var a = $("#orders_plan").val();
								var b = $("#orders_duration").val();
								var c = $("#orders_followup").val();
								$("#orders_plan_old").val(a);
								$("#orders_duration_old").val(b);
								$("#orders_followup_old").val(c);
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			}
			if (selected != 5) {
				var old9a = $("#insurance_id_1_old").val();
				var new9a = $("#insurance_id_1").val();
				var old9b = $("#insurance_id_2_old").val();
				var new9b = $("#insurance_id_2").val();
				var old9c = $("#billing_bill_complex_old").val();
				var new9c = $("#billing_bill_complex").val();
				if (old9a != new9a || old9b != new9b || old9c != new9c) {
					var ins1 = $("#insurance_id_1").val();
					var ins2 = $("#insurance_id_2").val();
					var bc = $("#billing_bill_complex").val();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/encounters/billing_save1');?>",
						data: "insurance_id_1=" + ins1 + "&insurance_id_2=" + ins2 + "&bill_complex=" + bc,
						success: function(data){
							if (data == 'Close Chart') {
								window.location = "<?php echo site_url();?>";
							} else {
								$.jGrowl(data);
								$("#insurance_id_1_old").val(ins1);
								$("#insurance_id_2_old").val(ins2);
								$("#billing_bill_complex_old").val(bc);
							}
						}
					});
				}
			}
			if (selected == 5) {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/encounters/check_assessment');?>",
					success: function(data){
						if (data == "OK!") {
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('assistant/encounters/compile_billing');?>",
								success: function(data){
									$.jGrowl(data);
									jQuery("#cpt_list").trigger("reloadGrid");
								}
							});
						} else {
							$.jGrowl(data);
						}
						jQuery("#cpt_list").trigger("reloadGrid");
					}
				});
			}
			return isValid;
		}
	});
});

$(function() {
	$('.menu_tooltip').tooltip({
		content: function(callback){
			var id = $(this).attr("id");
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/');?>/" + id,
				success: function(data){
					callback(data);
				}
			});
		}
	});
});

$(function() {
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('start/get_version/');?>/",
		success: function(data){
			$(".version").html(data);
		}
	});
});

function checkEmpty(o,n) {
	if (o.val() === '' || o.val() === null) {
		$.jGrowl(n + " Required");
		return false;
	} else {
		return true;
	}
}

function checkRegexp( o, regexp, n ) {
	if ( !( regexp.test( o.val() ) ) ) {
		$.jGrowl("Incorrect format: " + n);
		return false;
	} else {
		return true;
	}
}

function parse_date(string) {
	var date = new Date();
	var parts = String(string).split(/[- :]/);
	date.setFullYear(parts[0]);
	date.setMonth(parts[1] - 1);
	date.setDate(parts[2]);
	date.setHours(parts[3]);
	date.setMinutes(parts[4]);
	date.setSeconds(parts[5]);
	date.setMilliseconds(0);
	return date;
}

function parse_date1(string) {
	var date = new Date();
	var parts = String(string).split("/");
	date.setFullYear(parts[2]);
	date.setMonth(parts[0] - 1);
	date.setDate(parts[1]);
	date.setHours(0);
	date.setMinutes(0);
	date.setSeconds(0);
	date.setMilliseconds(0);
	return date;
}

function editDate(string) {
	var result = string.split("-");
	var edit_date = result[1] + '/' + result[2] + '/' + result[0];
	return edit_date;
}

function editDate1(string) {
	var result1 = string.split(" ");
	var result = result1[0].split("-");
	var edit_date = result[1] + '/' + result[2] + '/' + result[0];
	if (edit_date == '00/00/0000') {
		var edit_date1 = '';
	} else {
		var edit_date1 = edit_date;
	}
	return edit_date1;
}

function editDate2(string) {
	var result1 = string.split(" ");
	var result = result1[1].split(":");
	var hour1 = result[0];
	var hour2 = parseInt(hour1);
	if (hour2 > 12) {
		var hour3 = hour2 - 12;
		var hour4 = hour3 + '';
		var pm = 'PM';
		if (hour4.length == 1) {
			var hour = "0" + hour4;
		} else {
			var hour = hour4;
		}
	} else {
		if (hour2 == 0) {
			var hour = '12';
			var pm = 'AM';
		}
		if (hour2 == 12) {
			var hour = hour2;
			var pm = 'PM';
		}
		if (hour2 < 12) {
			var pm = 'AM';
			if (hour2.length == 1) {
				var hour = "0" + hour2;
			} else {
				var hour = hour2;
			}
		}
	}
	var minute1 = result[1];
	var minute2 = minute1 + '';
	if (minute2.length == 1) {
		var minute = "0" + minute2;
	} else {
		var minute = minute2;
	}
	var time = hour + ":" + minute + ' ' + pm;
	return time;
}

function getCurrentDate() {
	var d = new Date();
	var day1 = d.getDate();
	var day2 = day1 + '';
	if (day2.length == 1) {
		var day = "0" + day2;
	} else {
		var day = day2;
	}
	var month1 = d.getMonth();
	var month2 = parseInt(month1);
	var month3 = month2 + 1;
	var month4 = month3 + '';
	if (month4.length == 1) {
		var month = "0" + month4;
	} else {
		var month = month4;
	}
	var date = month + "/" + day + "/" + d.getFullYear();
	return date;
}
	
function getCurrentTime() {
	var d = new Date();
	var hour1 = d.getHours();
	var hour2 = parseInt(hour1);
	if (hour2 > 12) {
		var hour3 = hour2 - 12;
		var hour4 = hour3 + '';
		var pm = 'PM';
		if (hour4.length == 1) {
			var hour = "0" + hour4;
		} else {
			var hour = hour4;
		}
	} else {
		if (hour2 == 0) {
			var hour = '12';
			var pm = 'AM';
		}
		if (hour2 == 12) {
			var hour = hour2;
			var pm = 'PM';
		}
		if (hour2 < 12) {
			var pm = 'AM';
			if (hour2.length == 1) {
				var hour = "0" + hour2;
			} else {
				var hour = hour2;
			}
		}
	}
	var minute1 = d.getMinutes();
	var minute2 = minute1 + '';
	if (minute2.length == 1) {
		var minute = "0" + minute2;
	} else {
		var minute = minute2;
	}
	var time = hour + ":" + minute + ' ' + pm;
	return time;
}

$.fn.clearForm = function() {
	return this.each(function() {
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'form')
			return $(':input',this).clearForm();
		if (type == 'text' || type == 'password' || type == 'hidden' || tag == 'textarea')
			this.value = '';
		else if (type == 'checkbox' || type == 'radio')
			this.checked = false;
		else if (tag == 'select')
			this.selectedIndex = -1;
	});
};

$.fn.clearDiv = function() {
	return this.each(function() {
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'div')
			return $(':input',this).clearForm();
		if (type == 'text' || type == 'password' || type == 'hidden' || tag == 'textarea')
			this.value = '';
		else if (type == 'checkbox' || type == 'radio')
			this.checked = false;
		else if (tag == 'select')
			this.selectedIndex = -1;
	});
};
$.fn.serializeJSON = function() {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};
var success_doc = false;
var id_doc = '';
var old_text = '';
$.widget( "custom.catcomplete", $.ui.autocomplete, {
	_renderMenu: function( ul, items ) {
		var that = this,
		currentCategory = "";
		$.each( items, function( index, item ) {
			if ( item.category != currentCategory ) {
				ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
				currentCategory = item.category;
			}
			that._renderItemData( ul, item );
		});
	}
});
$.ajaxSetup({
	headers: {"cache-control":"no-cache"}
});
var supportsTouch = 'ontouchstart' in window || navigator.msMaxTouchPoints;
$(document).ready(function(){
	$("#switcher").themeswitcher({
		imgpath: "<?php echo base_url().'images/';?>",
		loadtheme: "redmond"
	});
	$("#mainborder").addClass("ui-tabs ui-widget ui-widget-content ui-corner-all");
	$("#box").addClass("ui-tabs ui-widget ui-widget-content ui-corner-all");
	$("#options_load").load('<?php echo site_url("start/options_load");?>');
});
$(document).on("click", ".ui-jqgrid-titlebar", function() {
	$(".ui-jqgrid-titlebar-close", this).click();
});
</script>
<?php if (isset($extraHeadContent)) {echo $extraHeadContent;}?>
</head>
<body>
<div id="options_load"></div>
<div id="allpage" class="allpage">
	<div id="header" class="header">
		<?php if(logged_in()) {?>
			<div id="header_left">
				<strong><?php echo anchor('start', 'Tasks') . ' '; ?></strong>
				<?php if(user_group('admin')) { echo anchor('admin/setup', 'Setup'); } ?>
				<?php if(user_group('admin') && $this->session->userdata('practice_active') == 'Y') { echo anchor('admin/users', 'Users'); } ?>
				<?php if(user_group('admin') && $this->session->userdata('practice_active') == 'Y') { echo anchor('admin/schedule', 'Schedule'); } ?>
				<?php if(user_group('admin')) { echo anchor('admin/logs', 'Logs'); } ?>
				<?php if(user_group('assistant')) { echo anchor('assistant/messaging', 'Messages'); } ?>
				<?php if(user_group('assistant')) { echo anchor('assistant/schedule', 'Schedule'); } ?>
				<?php if(user_group('assistant')) { echo anchor('assistant/billing', 'Financial'); } ?>
				<?php if(user_group('assistant')) { echo anchor('assistant/office', 'Office'); } ?>
				<?php if(user_group('patient')) { echo anchor('patient/chartmenu', 'Your Chart'); } ?>
				<?php if(user_group('patient')) { echo anchor('patient/messaging', 'Messages'); } ?>
				<?php if(user_group('patient')) { echo anchor('patient/schedule', 'Schedule'); } ?>
				<?php if(user_group('provider')) { echo anchor('provider/messaging', 'Messages'); } ?>
				<?php if(user_group('provider')) { echo anchor('provider/schedule', 'Schedule'); } ?>
				<?php if(user_group('provider')) { echo anchor('provider/billing', 'Financial'); } ?>
				<?php if(user_group('provider')) { echo anchor('provider/office', 'Office'); } ?>
				<?php if(user_group('billing')) { echo anchor('billing/messaging', 'Messages'); } ?>
				<?php if(user_group('billing')) { echo anchor('billing/schedule', 'Schedules'); } ?>
				<?php if(user_group('billing')) { echo anchor('billing/billing', 'Financial'); } ?>
				<?php if(user_group('provider') || user_group('assistant') || user_group('billing')) { echo '<a href="#" id="nosh_configuration">Configure</a>'; } ?>
				<br>
			</div>
			<div id="header_right">
				<div style="float:left;width:190px">
					<span id="switcher"></span>
				</div>
				<div style="float:left;">
					&nbspVersion <span class="version"></span>&nbsp|&nbsp
					<?php echo $this->session->userdata('displayname') . ' ';?>&nbsp|&nbsp
					<?php $datestring = '%M %d, %Y'; echo mdate($datestring) . ' ';?>&nbsp
					<?php echo anchor('logout', 'Logout'); ?>
				</div>
			</div>
			<br />
			<hr class="ui-state-default"/>
		<?php } else {?>
			<strong>NOSH ChartingSystem</strong> Version <span class="version"></span><br>
			<hr class="ui-state-default"/>
		<?php }?>
	</div>
