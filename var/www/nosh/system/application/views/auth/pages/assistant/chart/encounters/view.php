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
	$('#leftcol').load('<?php echo site_url("assistant/chartmenu/menu");?>');
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("assistant/encounters/view");?>',
		redirect_url: '<?php echo site_url("logout");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: false
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
		$("#preview").load('<?php echo site_url("assistant/encounters/modal_view2");?>/<?php echo $this->session->userdata("eid");?>');
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
			url: "<?php echo site_url ('assistant/encounters/get_encounter/');?>",
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
			url: "<?php echo site_url('assistant/chartmenu/get_appointments');?>",
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
							url: "<?php echo site_url('assistant/encounters/edit_encounter');?>",
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
	$("#save_draft").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#save_draft").click(function() {
		window.location = "<?php echo site_url ('assistant/chartmenu/');?>";
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
				url: "<?php echo site_url ('assistant/encounters/check_encounter/');?>",
				success: function(data){
					if (data == "") {
						$.ajax({
							type: "POST",
							url: "<?php echo site_url ('assistant/encounters/sign_encounter/');?>",
							success: function(data){
								if (data == "Close Chart") {
									window.location = "<?php echo site_url ('assistant/chartmenu/');?>";
								} else {
									$.jGrowl(data);
									jQuery("#encounters").trigger("reloadGrid");
									window.location = "<?php echo site_url ('assistant/chartmenu/');?>";
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
				url: "<?php echo site_url ('assistant/encounters/delete_encounter/');?>",
				data: "eid=" + eid,
				success: function(data){
					if (data == "") {
						window.location = "<?php echo site_url ('assistant/chartmenu/');?>";
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
									url: "<?php echo site_url('assistant/encounters/edit_encounter');?>",
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
	$(document).ajaxStop(function(){
		$("#dialog_load1").dialog("close");
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
		<hr class="ui-state-default"/>
		<div id="noshtabs">
			<div id="encounter_assistant_tabs">
				<ul>
					<li><?php echo anchor('assistant/encounters/ros/', 'ROS');?></li>
					<li><?php echo anchor('assistant/encounters/oh/', 'Other History');?></li>
					<li><?php echo anchor('assistant/encounters/vitals/', 'VS');?></li>
					<li><?php echo anchor('assistant/encounters/labs/', 'Labs');?></a></li>
					<li><?php echo anchor('assistant/encounters/proc/', 'Procedure');?></li>
					<li><?php echo anchor('assistant/encounters/orders/', 'Orders');?></li>
					<li><?php echo anchor('assistant/encounters/billing/', 'Billing');?></li>
				</ul>
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
<div id="preview_dialog" title="Encounter Preview">
	<div id="preview"></div>
</div>
