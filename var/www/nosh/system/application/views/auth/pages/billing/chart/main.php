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
	$('#leftcol').load('<?php echo site_url("billing/chartmenu/menu");?>');
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("billing/chartmenu");?>',
		redirect_url: '<?php echo site_url("start");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: 300000
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
		minLength: 1,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
	});
	$("#encounter_date").mask("99/99/9999");
	var currentDate = getCurrentDate();
	var currentTime = getCurrentTime();
	$("#encounter_date").val(currentDate);
	$("#encounter_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#encounter_time").val(currentTime);
	$("#encounter_cc").val('');
	$("#encounter_condition_work").addOption({"":"","No":"No","Yes":"Yes"},false);
	$("#encounter_condition_auto").addOption({"":"","No":"No","Yes":"Yes"},false);
	$("#encounter_condition_other").addOption({"":"","No":"No","Yes":"Yes"},false);
	$("#encounter_condition_auto_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
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
		minLength: 3,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
	});
	$("#new_encounter_dialog").dialog({ 
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
			'Add Encounter': function() {
				var date = $("#encounter_date");
				var time = $("#encounter_time");
				var type = $("#encounter_type");
				var location = $("#encounter_location");
				var cc = $("#encounter_cc");
				var work = $("#encounter_condition_work");
				var auto = $("#encounter_condition_auto");
				var other = $("#encounter_condition_other");
				var bValid = true;
				bValid = bValid && checkEmpty(date,"Date of Service");
				bValid = bValid && checkEmpty(time,"Time of Service");
				bValid = bValid && checkEmpty(type,"Encounter Type");
				bValid = bValid && checkEmpty(location,"Encounter Location");
				bValid = bValid && checkEmpty(cc,"Chief Complaint");
				bValid = bValid && checkEmpty(work,"Work Accident");
				bValid = bValid && checkEmpty(auto,"Auto Accident");
				bValid = bValid && checkEmpty(other,"Other Accident");
				if (bValid) {
					var str = $("#new_encounter_form").serialize();		
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('billing/encounters/new_encounter');?>",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#new_encounter_dialog").dialog('close');
								window.location = "<?php echo site_url ('billing/encounters/view/');?>";
							}
						});
					} else {
						$.jGrowl("Please complete the form");
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
		$('#encounter_time').timeEntry({spinnerImage: '<?php echo base_url()."images/spinnerDefault.png";?>',ampmPrefix: ' '});
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/chartmenu/get_appointments');?>",
			dataType: "json",
			success: function(data){
				$("#encounter_type").addOption(data,false);
			}
		});
		$("#encounter_condition_work").val('No');
		$("#encounter_condition_auto").val('No');
		$("#encounter_condition_other").val('No');
	});
	$("#new_message").click(function() {
		$("#edit_message_form").clearForm();
		$.ajax({
			url: "<?php echo site_url('billing/chartmenu/new_message');?>",
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
		minLength: 2,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
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
		minLength: 2,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
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
					url: "<?php echo site_url('billing/chartmenu/documents_upload1');?>",
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
				url: "<?php echo site_url('billing/chartmenu/delete_upload');?>",
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
	$("#encounter_provider").autocomplete({
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
		minLength: 1,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
	});
	$("#print_ccr").click(function() {
		window.open("<?php echo site_url('billing/chartmenu/print_ccr');?>/");
	});
	$(document).ajaxStop(function(){
		$("#dialog_load2").dialog("close");
	});
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
					<table>
						<tr>
							<td><img src="<?php echo base_url().'images/download.png';?>" border="0"></td>
							<td>
								<a href="#" id="new_import">Import New Documents</a>
								<div id="import_upload_div" style="display:none"> 
									<br>
									<form id="import_upload_form" action="<?php echo site_url('billing/chartmenu/documents_upload');?>" method="post" enctype="multipart/form-data">
										<span id="import_upload_span"></span>
									</form>
								</div>
							</td>
						</tr>
						<tr>
							<td><img src="<?php echo base_url().'images/printmgr.png';?>" border="0"></td>
							<td><a href="#" id="print_chart">Print Chart</a></td>
						</tr>
					</table>
				</td>
				<td valign="top">
					<table>	
					</table>
				</td>
			</tr>
		</table>	
	</div>
</div>
<div id="new_encounter_dialog" title="New Encounter">
	<form name="new_encounter_form" id="new_encounter_form">
		<table>
			<tr>
				<td><label for="encounter_provider">Encounter Provider</label></td>
				<td colspan="2"><input type="text" name="encounter_provider" id="encounter_provider" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="encounter_date">Date of Service</label></td>
				<td colspan="2"><input type="text" name="encounter_date" id="encounter_date" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="encounter_time">Time of Service</label></td>
				<td colspan="2"><input type="text" name="encounter_time" id="encounter_time" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="encounter_type">Encounter Type</label></td>
				<td colspan="2"><select name="encounter_type" id="encounter_type" class="text ui-widget-content ui-corner-all"></select></td>
			</tr>
			<tr>
				<td><label for="encounter_location">Encounter Location</label></td>
				<td colspan="2"><input type="text" name="encounter_location" id="encounter_location" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="encounter_cc">Chief Complaint</label></td>
				<td colspan="2"><input type="text" name="encounter_cc" id="encounter_cc" size="42" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="encounter_condition">Condition Related To</label></td>
				<td colspan="2"><input type="text" name="encounter_condition" id="encounter_condition" size="42" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="encounter_condition_work">Condition Related To Work</label></td>
				<td colspan="2"><select name="encounter_condition_work" id="encounter_condition_work" class="text ui-widget-content ui-corner-all"></select></td>
			</tr>
			<tr>
				<td><label for="encounter_condition_auto">Condition Related To Auto Accident</label></td>
				<td><select name="encounter_condition_auto" id="encounter_condition_auto" class="text ui-widget-content ui-corner-all"></select></td>
				<td><select name="encounter_condition_auto_state" id="encounter_condition_auto_state" class="text ui-widget-content ui-corner-all"></select></td>
			</tr>
			<tr>
				<td><label for="encounter_condition_other">Condition Related To Other Accident</label></td>
				<td colspan="2"><select name="encounter_condition_other" id="encounter_condition_other" class="text ui-widget-content ui-corner-all"></select></td>
			</tr>
		</table>
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
