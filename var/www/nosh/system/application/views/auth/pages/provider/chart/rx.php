<div id="messages_rx_dialog" title="Prescribing Medications Helper">
	<div id="messages_rx_main">
		<div id="orders_rx_header" style="display:none">
			<button type="button" id="rcopia_orders_rx" style="display:none">Update from RCopia</button> 
			<button type="button" id="save_orders_rx">Save Prescriptions</button> 
			<button type="button" id="cancel_orders_rx_helper">Cancel</button> 
			<hr class="ui-state-default"/>
		</div>
		<div id="messages_rx_header" style="display:none">
			<button type="button" id="rcopia_rx_helper" style="display:none">Update from RCopia</button> 
			<button type="button" id="save_rx_helper">Import Prescriptions to Message</button> 
			<button type="button" id="cancel_rx_helper">Cancel</button> 
			<hr class="ui-state-default"/>
		</div>
		<table id="messages_medications" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_medications_pager" class="scroll" style="text-align:center;"></div><br>
		<input type="button" id="messages_add_rx" value="Add Medication" class="ui-button ui-state-default ui-corner-all"/>
		<input type="button" id="messages_edit_rx" value="Refill Medication" class="ui-button ui-state-default ui-corner-all"/>
		<input type="button" id="messages_eie_rx" value="Entered in Error" class="ui-button ui-state-default ui-corner-all"/>
		<input type="button" id="messages_inactivate_rx" value="Inactivate Medication" class="ui-button ui-state-default ui-corner-all"/>
		<input type="button" id="messages_delete_rx" value="Delete Medication" class="ui-button ui-state-default ui-corner-all"/>
		<button type="button" id="messages_print_rx">Print List</button><br><br>
		<input type="hidden" name="messages_rx_text" id="messages_rx_text"/>
		<input type="hidden" name="messages_rx_eie_text" id="messages_rx_eie_text"/>
		<input type="hidden" name="messages_rx_inactivate_text" id="messages_rx_inactivate_text"/>
		<input type="hidden" name="messages_rx_reactivate_text" id="messages_rx_reactivate_text"/>
		<form name="messages_action_rx_form" id="messages_action_rx_form" style="display: none">
			<input type="hidden" name="prescribe_id" id="prescribe_id"/>
			<fieldset class="ui-state-default ui-corner-all">
				<legend>Action</legend>
				<div id="prescribe_choice"></div><br><br>
				<input type="button" id="messages_print_medication" value="Print Prescription" class="ui-button ui-state-default ui-corner-all"/> 
				<!--<input type="button" id="messages_eprescribe_medication" value="ePrescribe" class="ui-button ui-state-default ui-corner-all"/> -->
				<input type="button" id="messages_fax_medication" value="Fax" class="ui-button ui-state-default ui-corner-all"/> 
				<input type="button" id="messages_done_medication" value="Done" class="ui-button ui-state-default ui-corner-all"/> 
			</fieldset><br>
		</form>
		<form name="messages_edit_rx_form" id="messages_edit_rx_form" style="display: none">
			<input type="hidden" name="rxl_id" id="messages_rxl_id"/>
			<input type="hidden" name="rxl_ndcid" id="messages_rxl_ndcid"/>
			<input type="hidden" id="messages_rxl_medication_list"/>
			<input type="hidden" id="messages_rxl_name"/>
			<input type="hidden" id="messages_rxl_form"/>
			<fieldset class="ui-state-default ui-corner-all">
				<legend>Medication</legend>
				<table cellspacing="0" cellpadding="1">
					<tbody>
						<tr>
							<td colspan="3">Medication:<br><input type="text" name="rxl_medication" id="messages_rxl_medication" style="width:356px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Dosage:<br><input type="text" name="rxl_dosage" id="messages_rxl_dosage" style="width:117px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Unit:<br><input type="text" name="rxl_dosage_unit" id="messages_rxl_dosage_unit" style="width:117px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="2">Sig:<br><input type="text" name="rxl_sig" id="messages_rxl_sig" style="width:235px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Route:<br><select id ="messages_rxl_route" name="rxl_route" class="text ui-widget-content ui-corner-all"></select></td>
							<td colspan="2">Frequency:<br><input type="text" name="rxl_frequency" id="messages_rxl_frequency" style="width:240px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="5">Special Instructions:<br><input type="text" name="rxl_instructions" id="messages_rxl_instructions" style="width:600px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Quantity:<br><input type="text" name="rxl_quantity" id="messages_rxl_quantity" style="width:116px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Refills:<br><input type="text" name="rxl_refill" id="messages_rxl_refill" style="width:116px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Days:<br><input type="text" name="rxl_days" id="messages_rxl_days" style="width:116px" class="text ui-widget-content ui-corner-all"/></td>
							<td colspan="2">Reason:<br><input type="text" name="rxl_reason" id="messages_rxl_reason" style="width:240px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="2">Dispense as Written: <input type="checkbox" name="daw" id="messages_rxl_daw" value="Yes" class="text ui-widget-content ui-corner-all"/></td>
							<td colspan="3">DEA Number on Prescription: <input type="checkbox" name="dea" id="messages_rxl_dea" value="Yes" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="5">Date of Prescription:<br><input type="text" name="rxl_date_prescribed" id="messages_rxl_date_prescribed" class="text ui-widget-content ui-corner-all"/></td>
							<td>
								<button type="button" id="messages_prescribe_medication">Prescribe</button><br>
								<button type="button" id="messages_cancel_medication">Cancel</button>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset><br>
		</form>
		<table id="messages_medications_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_medications_inactive_pager" class="scroll" style="text-align:center;"></div><br>
		<input type="button" id="messages_reactivate_rx" value="Reactivate Medication" class="ui-button ui-state-default ui-corner-all"/><br><br>
	</div>
	<div id="messages_rx_fax" style="display: none">
		<table id="messages_rx_fax_list" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_rx_fax_list_pager" class="scroll" style="text-align:center;"></div><br>
		<input type="button" id="messages_rx_fax_viewpage" value="View Page" class="ui-button ui-state-default ui-corner-all"/><br><br>
		<form name="messages_rx_fax_form" id="messages_rx_fax_form">
			<input type="hidden" name="fax_prescribe_id" id="fax_prescribe_id"/>
			<fieldset class="ui-state-default ui-corner-all">
				<legend>Fax Prescription</legend>
				<table>
					<tbody>
						<tr>
							<td>Pharmacy:<br><input type="text" name="messages_pharmacy_name" id="messages_pharmacy_name" size="50" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Fax Number:<br><input type="text" name="messages_pharmacy_fax_number" id="messages_pharmacy_fax_number" class="text ui-widget-content ui-corner-all"/> <input type="button" id="messages_add_fax_contact" value="Add Pharmacy Contact to Address Book" class="ui-button ui-state-default ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Action for fax job <div id="messages_fax_id"></div><br><input type="button" id="messages_send_fax" value="Send Fax Queue" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_save_fax" value="Save Fax Queue to Send Later" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_cancel_fax" value="Cancel Fax Queue" class="ui-button ui-state-default ui-corner-all"/></td>
						</tr>
					</tbody>
				</table>
			</fieldset><br>
		</form>
	</div>
</div>
<div id="rx_dialog_confirm" title="Confirmation">
	<div id="rx_dialog_confirm_text"></div>
</div>
<div id="interactions_load" title="Checking...">
	<img src="<?php echo base_url().'images/indicator.gif';?>"> Checking for drug interactions.
</div>

<script type="text/javascript">
	$.ajax({
		url: "<?php echo site_url('start/check_fax');?>",
		type: "POST",
		success: function(data){
			if (data == "Yes") {
				$("#messages_fax_medication").show('fast');
			} else {
				$("#messages_fax_medication").hide('fast');
			}
		}
	});
	$("#interactions_load").dialog({
		height: 100,
		autoOpen: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		modal: true
	});
	$("#messages_rx_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#messages_medications").jqGrid('GridUnload');
			jQuery("#messages_medications").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/medications/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Date Prescribed','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason','Days','Quantity','Refills','NDC'],
				colModel:[
					{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
					{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_date_prescribed',index:'rxl_date_prescribed',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_medication',index:'rxl_medication',width:180},
					{name:'rxl_dosage',index:'rxl_dosage',width:50},
					{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
					{name:'rxl_sig',index:'rxl_sig',width:50},
					{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
					{name:'rxl_frequency',index:'rxl_frequency',width:75},
					{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
					{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true},
					{name:'rxl_days',index:'rxl_days',width:1,hidden:true},
					{name:'rxl_quantity',index:'rxl_quantity',width:1,hidden:true},
					{name:'rxl_refill',index:'rxl_refill',width:1,hidden:true},
					{name:'rxl_ndcid',index:'rxl_ndcid',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_medications_pager'),
				sortname: 'rxl_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Medications - Click on Date Active column to get previous prescription dates.",
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol == 1) {
						var med = jQuery("#messages_medications").getCell(id,'rxl_medication');
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/past_medication');?>",
							data: "rxl_medication=" + med,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.item, {sticky:true, header:data.header});
							}
						});
					}
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_medications_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#messages_medications_inactive").jqGrid('GridUnload');
			jQuery("#messages_medications_inactive").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/medications_inactive/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Date Prescribed','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason','Days'],
				colModel:[
					{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
					{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_date_prescribed',index:'rxl_date_prescribed',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_medication',index:'rxl_medication',width:180},
					{name:'rxl_dosage',index:'rxl_dosage',width:50},
					{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
					{name:'rxl_sig',index:'rxl_sig',width:50},
					{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
					{name:'rxl_frequency',index:'rxl_frequency',width:75},
					{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
					{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true},
					{name:'rxl_days',index:'rxl_days',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_medications_inactive_pager'),
				sortname: 'rxl_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Inactive Medications",
			 	height: "100%",
			 	hiddengrid: true,
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_medications_inactive_pager',{search:false,edit:false,add:false,del:false});
		},
		beforeclose: function(event, ui) {
			var a = $("#messages_rx_text").val();
			var b = $("#messages_rx_eie_text").val();
			var c = $("#messages_rx_inactivate_text").val();
			var d = $("#messages_rx_reactivate_text").val();
			if(a != '' || b != '' || c != '' || d != ''){
				if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
					$('#messages_edit_rx_form').hide('fast');
					$('#messages_edit_rx_form').clearForm();
					$("#messages_rx_text").val('');
					$("#messages_rx_eie_text").val('');
					$("#messages_rx_inactivate_text").val('');
					$("#messages_rx_reactivate_text").val('');
					$("#messages_lab1_fieldset").hide('fast');
					$("#messages_lab2_fieldset").hide('fast');
					$("#messages_lab3_fieldset").hide('fast');
					$("#messages_lab_action_fieldset").hide('fast');
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#rx_dialog_confirm").dialog({
		resizable: false,
		height:400,
		width: 400,
		modal: true,
		bgiframe: true, 
		autoOpen: false, 
		draggable: false,
		buttons: {
			"Prescribe": function() {
				var str = $("#messages_edit_rx_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/prescribe_medication');?>",
						data: str,
						dataType: "json",
						success: function(data){
							if (data.message == 'Close Chart') {
								window.location = "<?php echo site_url();?>";
							} else {
								if(data.id) {
									$.jGrowl(data.message);
									$("#rx_dialog_confirm").dialog("close");
									jQuery("#messages_medications").trigger("reloadGrid");
									jQuery("#medications").trigger("reloadGrid");
									$('#prescribe_id').val(data.id);
									var old = $('#messages_rx_text').val();
									$('#messages_rx_text').val(old + '\n' + data.medtext);
									$('#prescribe_choice').html(data.med);
									$('#messages_edit_rx_form').hide('fast');
									$('#messages_edit_rx_form').clearForm();
									$('#messages_action_rx_form').show('fast');
								} else {
									$.jGrowl(data.message);
								}
							}
						}
					});
				}
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		}
	});

	$('#save_orders_rx').click(function(){
		var a = $("#messages_rx_text").val();
		var b = $("#messages_rx_eie_text").val();
		var c = $("#messages_rx_inactivate_text").val();
		var d = $("#messages_rx_reactivate_text").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/orders_rx_save');?>",
			data: "rx=" + a + "&eie=" + b + "&inactivate=" + c + "&reactivate=" +d,
			success: function(data){
				if (data == 'Close Chart') {
					window.location = "<?php echo site_url();?>";
				} else {
					$.jGrowl(data);
					$("#orders_rx_header").hide('fast');
					$("#messages_rx_text").val('');
					$("#messages_rx_eie_text").val('');
					$("#messages_rx_inactivate_text").val('');
					$("#messages_rx_reactivate_text").val('');
					$("#messages_rx_dialog").dialog('close');
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/check_orders');?>",
						dataType: "json",
						success: function(data){
							$('#button_orders_labs_status').html(data.labs_status);
							$('#button_orders_rad_status').html(data.rad_status);
							$('#button_orders_cp_status').html(data.cp_status);
							$('#button_orders_ref_status').html(data.ref_status);
							$('#button_orders_rx_status').html(data.rx_status);
							$('#button_orders_imm_status').html(data.imm_status);
							$('#button_orders_sup_status').html(data.sup_status);
						}
					});
				}
			}
		});
	});
	$("#cancel_orders_rx_helper").click(function() {
		var a = $("#messages_rx_text").val();
		var b = $("#messages_rx_eie_text").val();
		var c = $("#messages_rx_inactivate_text").val();
		var d = $("#messages_rx_reactivate_text").val();
		if(a != '' || b != '' || c != '' || d != ''){
			if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
				$('#messages_edit_rx_form').hide('fast');
				$('#messages_edit_rx_form').clearForm();
				$("#messages_rx_text").val('');
				$("#messages_rx_eie_text").val('');
				$("#messages_rx_inactivate_text").val('');
				$("#messages_rx_reactivate_text").val('');
				$("#messages_lab1_fieldset").hide('fast');
				$("#messages_lab2_fieldset").hide('fast');
				$("#messages_lab3_fieldset").hide('fast');
				$("#messages_lab_action_fieldset").hide('fast');
				$("#messages_rx_dialog").dialog('close');
				return true;
			} else {
				return false;
			}
		} else {
			$('#messages_edit_rx_form').hide('fast');
			$('#messages_edit_rx_form').clearForm();
			$("#messages_rx_text").val('');
			$("#messages_rx_eie_text").val('');
			$("#messages_rx_inactivate_text").val('');
			$("#messages_rx_reactivate_text").val('');
			$("#messages_rx_dialog").dialog('close');
		}	
	});
	$("#save_rx_helper").click(function() {
		var old = $("#t_messages_message").val();
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
		var a = $("#messages_rx_text").val();
		var b = $("#messages_rx_eie_text").val();
		var c = $("#messages_rx_inactivate_text").val();
		var d = $("#messages_rx_reactivate_text").val();
		if(a){
			var a1 = 'PRESCRIBED MEDICATIONS:  ' + a + '\n\n';	
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'ENTERED MEDICATIONS IN ERROR:  ' + b + '\n\n';	
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'DISCONTINUED MEDICATIONS:  ' + c + '\n\n';	
		} else {
			var c1 = '';
		}
		if(d){
			var d1 = 'REINSTATED MEDICATIONS:  ' + d + '\n\n';	
		} else {
			var d1 = '';
		}
		$("#t_messages_message").val(old1+a1+b1+c1+d1);
		$('#messages_edit_rx_form').hide('fast');
		$('#messages_edit_rx_form').clearForm();
		$("#messages_rx_text").val('');
		$("#messages_rx_eie_text").val('');
		$("#messages_rx_inactivate_text").val('');
		$("#messages_rx_reactivate_text").val('');
		$("#messages_rx_dialog").dialog('close');
	});
	$("#cancel_rx_helper").click(function() {
		var a = $("#messages_rx_text").val();
		var b = $("#messages_rx_eie_text").val();
		var c = $("#messages_rx_inactivate_text").val();
		var d = $("#messages_rx_reactivate_text").val();
		if(a != '' || b != '' || c != '' || d != ''){
			if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
				$('#messages_edit_rx_form').hide('fast');
				$('#messages_edit_rx_form').clearForm();
				$("#messages_rx_text").val('');
				$("#messages_rx_eie_text").val('');
				$("#messages_rx_inactivate_text").val('');
				$("#messages_rx_reactivate_text").val('');
				$("#messages_lab1_fieldset").hide('fast');
				$("#messages_lab2_fieldset").hide('fast');
				$("#messages_lab3_fieldset").hide('fast');
				$("#messages_lab_action_fieldset").hide('fast');
				$("#messages_rx_dialog").dialog('close');
				return true;
			} else {
				return false;
			}
		} else {
			$('#messages_edit_rx_form').hide('fast');
			$('#messages_edit_rx_form').clearForm();
			$("#messages_rx_text").val('');
			$("#messages_rx_eie_text").val('');
			$("#messages_rx_inactivate_text").val('');
			$("#messages_rx_reactivate_text").val('');
			$("#messages_rx_dialog").dialog('close');
		}	
	});
	$("#messages_rxl_date_prescribed").mask("99/99/9999");
	$("#messages_rxl_date_prescribed").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#messages_rxl_route").addOption({"by mouth":"PO","per rectum":"PR","subcutaneously":"SC","intramuscularly":"IM","intravenously":"IV"}, false);
	$("#messages_rxl_route").selectOptions();
	var medcache = {};
	$("#messages_rxl_medication").catcomplete({
		source: function (req, add){
			var term = req.term;
			if (term in medcache) {
				add(medcache[term]);
				return;
			}
			$.ajax({
				url: "<?php echo site_url('search/rx_name1');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						medcache[term] = data.message;
						add(data.message);
					}
				}
			});
		},
		minLength: 3,
		select: function(event, ui){
			if (ui.item.category == "Previously Prescribed") {
				$("#messages_rxl_dosage").val(ui.item.dosage);
				$("#messages_rxl_dosage_unit").val(ui.item.dosage_unit);
				$("#messages_rxl_ndcid").val(ui.item.rxl_ndcid);
				$("#messages_rxl_name").val('');
				$("#messages_rxl_form").val('');
			} else {
				$("#messages_rxl_dosage").val('');
				$("#messages_rxl_dosage_unit").val('');
				$("#messages_rxl_ndcid").val('');
				$("#messages_rxl_name").val(ui.item.name);
				$("#messages_rxl_form").val(ui.item.form);
			}
		}
	});
	$("#messages_rxl_dosage").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_dosage');?>",
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
		minLength: 0,
		select: function(event, ui){
			$("#messages_rxl_dosage_unit").val(ui.item.unit);
			$.ajax({
				url: "<?php echo site_url('search/rx_ndc_convert');?>/" + ui.item.ndc,
				type: "POST",
				success: function(data){
					$("#messages_rxl_ndcid").val(data);
				}
			});
		}
	});
	$("#messages_rxl_dosage").focus(function(){
		var rx_name = $("#messages_rxl_name").val();
		if (rx_name != '') {
			rx_name = rx_name + ";" + $("#messages_rxl_form").val();
			$("#messages_rxl_dosage").autocomplete("search", rx_name);
		}
	});
	$("#messages_rxl_sig").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_sig');?>",
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
	$("#messages_rxl_frequency").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_frequency');?>",
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
	$("#messages_rxl_instructions").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_instructions');?>",
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
	$("#messages_rxl_reason").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_reason');?>",
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
	$("#messages_add_rx").click(function(){
		$('#messages_edit_rx_form').clearForm();
		var currentDate = getCurrentDate();
		$('#messages_rxl_date_prescribed').val(currentDate);
		$('#messages_edit_rx_form').show('fast');
		$('#messages_rxl_medication').focus();
	});
	$("#messages_edit_rx").click(function(){
		var item = jQuery("#messages_medications").getGridParam('selrow');
		if(item){
			jQuery("#messages_medications").GridToForm(item,"#messages_edit_rx_form");
			var currentDate = getCurrentDate();
			$('#messages_rxl_date_prescribed').val(currentDate);
			$('#messages_edit_rx_form').show('fast');
			$('#messages_rxl_quantity').focus();
		} else {
			$.jGrowl("Please select medication to edit!");
		}
	});
	$("#messages_eie_rx").click(function(){
		var item = jQuery("#messages_medications").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/eie_medication');?>",
				data: "rxl_id=" + item,
				dataType: 'json',
				success: function(data){
					$.jGrowl(data.message);
					var old = $('#messages_rx_eie_text').val();
					$('#messages_rx_eie_text').val(old + '\n' + data.medtext);
					$('#messages_action_rx_form').clearForm();
					$('#messages_action_rx_form').hide('fast');
					jQuery("#messages_medications").trigger("reloadGrid");
					jQuery("#messages_medications_inactive").trigger("reloadGrid");
					jQuery("#medications").trigger("reloadGrid");
					jQuery("#medications_inactive").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select medication!");
		}
	});
	$("#messages_inactivate_rx").click(function(){
		var item = jQuery("#messages_medications").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/inactivate_medication');?>",
				data: "rxl_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					var old = $('#messages_rx_inactivate_text').val();
					$('#messages_rx_inactivate_text').val(old + '\n' + data.medtext);
					jQuery("#messages_medications").trigger("reloadGrid");
					jQuery("#messages_medications_inactive").trigger("reloadGrid");
					jQuery("#medications").trigger("reloadGrid");
					jQuery("#medications_inactive").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select medication to inactivate!");
		}
	});
	$("#messages_delete_rx").click(function(){
		var item = jQuery("#messages_medications").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this medication?  This is not recommended unless entering the medication was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/delete_medication');?>",
					data: "rxl_id=" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#messages_medications").trigger("reloadGrid");
						jQuery("#messages_medications_inactive").trigger("reloadGrid");
						jQuery("#medications").trigger("reloadGrid");
						jQuery("#medications_inactive").trigger("reloadGrid");
					}
				});
			}
		} else {
			$.jGrowl("Please select medication to inactivate!");
		}
	});
	$("#messages_reactivate_rx").click(function(){
		var item = jQuery("#messages_medications_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/reactivate_medication');?>",
				data: "rxl_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					var old = $('#messages_rx_reactivate_text').val();
					$('#messages_rx_reactivate_text').val(old + '\n' + data.medtext);
					jQuery("#messages_medications_inactive").trigger("reloadGrid");
					jQuery("#messages_medications").trigger("reloadGrid");
					jQuery("#medications").trigger("reloadGrid");
					jQuery("#medications_inactive").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select medication to reactivate!")
		}
	});
	$.extend({
		rx: {
			callback: function (result) {
				var id = result["references"][0]["id"];
				var a = $("#messages_rxl_medication_list").val();
				if (a == '') {
					$("#messages_rxl_medication_list").val(id);
				} else {
					$("#messages_rxl_medication_list").val(a + ',' + id);
				}
			}
		}
	});
	$("#messages_prescribe_medication").click(function(){
		var medication = $("#messages_rxl_medication");
		var reason = $("#messages_rxl_reason");
		var days = $("#messages_rxl_days");
		var bValid = true;
		bValid = bValid && checkEmpty(medication,"Medication");
		bValid = bValid && checkEmpty(reason,"Reason");
		bValid = bValid && checkEmpty(days,"Days");
		if (bValid) {
			var str = $("#messages_edit_rx_form").serialize();
			if(str){
				$("#interactions_load").dialog('open');
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/interactions_medication1');?>",
					data: str,
					dataType: "json",
					success: function(data){
						if (data.message == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} 
						if (data.message == 'Allergies') {
							$("#rx_dialog_confirm_text").html(data.info);
							$("#rx_dialog_confirm").dialog("open");
						}
						if (data.message == 'Multiple') {
							$("#rx_dialog_confirm_text").html(data.info);
							$("#rx_dialog_confirm").dialog("open");
						}
						if (data.message == 'None') {
							var str = $("#messages_edit_rx_form").serialize();
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/prescribe_medication');?>",
								data: str,
								dataType: "json",
								success: function(data){
									if (data.message == 'Close Chart') {
										window.location = "<?php echo site_url();?>";
									} else {
										if(data.id) {
											$.jGrowl(data.message);
											jQuery("#messages_medications").trigger("reloadGrid");
											jQuery("#medications").trigger("reloadGrid");
											$('#prescribe_id').val(data.id);
											var old = $('#messages_rx_text').val();
											$('#messages_rx_text').val(old + '\n' + data.medtext);
											$('#prescribe_choice').html(data.med);
											$('#messages_edit_rx_form').hide('fast');
											$('#messages_edit_rx_form').clearForm();
											$('#messages_action_rx_form').show('fast');
										} else {
											$.jGrowl(data.message);
										}
									}
								}
							});
						}
					}
				});
				$("#interactions_load").ajaxStop(function(){
					$(this).dialog("close");
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#messages_print_medication").click(function(){
		var medication = $("#prescribe_id");
		var bValid = true;
		bValid = bValid && checkEmpty(medication,"Prescription ID");
		if (bValid) {
			var prescribe_id = $("#prescribe_id").val();
			window.open("<?php echo site_url('provider/chartmenu/print_medication');?>/" + prescribe_id);
		}
	});
	$("#messages_eprescribe_medication").click(function(){
		var medication = $("#prescribe_id");
		var bValid = true;
		bValid = bValid && checkEmpty(medication,"Prescription ID");
		if (bValid) {
			var prescribe_id = $("#prescribe_id").val();
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "<?php echo site_url('provider/chartmenu/eprescribe_medication');?>/" + prescribe_id,
				success: function(data){
					$('#e_medication').val(data.e_medication);
					$('#e_dosage').val(data.e_dosage);
					$('#e_sig').val(data.e_sig);
					$('#e_quantity').val(data.e_quantity);
					$('#e_refills').val(data.e_refills);
					$('#e_days').val(data.e_days);
					$('#e_daw').val(data.e_daw);
					$('#e_lastname').val(data.e_lastname);
					$('#e_firstname').val(data.e_firstname);
					$('#e_dob').val(data.e_dob);
					$('#e_sex').val(data.e_sex);
					$('#e_address').val(data.e_address);
					$('#e_city').val(data.e_city);
					$('#e_state').val(data.e_state);
					$('#e_zip').val(data.e_zip);
					$('#e_phone').val(data.e_phone);
					$('#e_mobile').val(data.e_mobile);
					$('#e_pid').val(data.e_pid);
					$('#eprescribe_dialog').dialog('open');
				}
			});
		}
	});
	$("#messages_fax_medication").click(function(){
		var medication = $("#prescribe_id");
		var bValid = true;
		bValid = bValid && checkEmpty(medication,"Prescription ID");
		if (bValid) {
			var id = $("#prescribe_id").val();
			$("#fax_prescribe_id").val(id);
			var str = $("#messages_rx_fax_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/start_fax_medication');?>",
					data: str,
					dataType: "json",
					success: function(data){
						$.jGrowl(data.message);
						$('#messages_fax_id').html(data.id);
						jQuery("#messages_rx_fax_list").trigger("reloadGrid");
						$('#messages_rx_main').hide('fast');
						$('#messages_rx_fax').show('fast');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#messages_done_medication").click(function(){
		$('#messages_action_rx_form').clearForm();
		$('#messages_action_rx_form').hide('fast');
	});
	jQuery("#messages_rx_fax_list").jqGrid({
		url:"<?php echo site_url('provider/chartmenu/rx_fax_list');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','File','Pages','Full Path'],
		colModel:[
			{name:'pages_id',index:'pages_id',width:1,hidden:true},
			{name:'file_original',index:'file_original',width:555},
			{name:'pagecount',index:'pagecount',width:100},
			{name:'file',index:'file',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#messages_rx_fax_list_pager'),
		sortname: 'file_original',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Fax Queue",
	 	emptyrecords:"No pages",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#messages_rx_fax_list_pager',{search:false,edit:false,add:false,del:false
	});
	$("#messages_rx_fax_viewpage").click(function(){
		var click_id = jQuery("#messages_rx_fax_list").getGridParam('selrow');
		if(click_id){
			window.open("<?php echo site_url('provider/chartmenu/view_faxpage');?>/" + click_id);
		}
	});
	$("#messages_pharmacy_fax_number").mask("(999) 999-9999");
	$("#messages_pharmacy_name").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/pharmacy');?>",
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
			$("#messages_pharmacy_fax_number").val(ui.item.fax);
		}
	});
	$("#messages_add_fax_contact").click(function(){
		var pharmacy = $("#messages_pharmacy_name");
		var number = $("#messages_pharmacy_fax_number");
		var bValid = true;
		bValid = bValid && checkEmpty(pharmacy,"Pharmacy Name");
		bValid = bValid && checkEmpty(number,"Pharmacy Fax Number");
		if (bValid) {
			var str = $("#messages_rx_fax_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/add_pharmacy');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#messages_send_fax").click(function(){
		var medication = $("#prescribe_id");
		var id = $("#prescribe_id").val();
		$('#fax_prescribe_id').val(id);
		var bValid = true;
		bValid = bValid && checkEmpty(medication,"Prescription ID");
		if (bValid) {
			var str = $("#messages_rx_fax_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/send_fax_medication');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$('#messages_rx_fax_form').clearForm();
						$('#messages_rx_fax').hide('fast');
						$('#messages_rx_main').show('fast');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#messages_save_fax").click(function(){
		$("#fax_prescribe_id").val('');
		$('#messages_rx_fax').hide('fast');
		$('#messages_rx_main').show('fast');
	});
	$("#messages_cancel_fax").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/cancel_fax_medication');?>",
			success: function(data){
				$.jGrowl(data);
				$('#messages_rx_fax_form').clearForm();
				$('#messages_rx_fax').hide('fast');
				$('#messages_rx_main').show('fast');
			}
		});
	});
	$("#messages_cancel_medication").click(function(){
		$('#messages_edit_rx_form').clearForm();
		$('#messages_edit_rx_form').hide('fast');
	});
	$("#messages_print_rx").button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$("#messages_print_rx").click(function() {
		window.open("<?php echo site_url('provider/chartmenu/print_medication_list');?>/");
	});
	$("#messages_add_rx").button();
	$("#messages_edit_rx").button();
	$("#messages_inactivate_rx").button();
	$("#messages_eie_rx").button();
	$("#messages_delete_rx").button();
	$("#messages_reactivate_rx").button();
	$("#save_orders_rx").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#cancel_orders_rx_helper").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#save_rx_helper").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#cancel_rx_helper").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#messages_print_medication").button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$("#messages_eprescribe_medication").button();
	$("#messages_fax_medication").button();
	$("#messages_done_medication").button();
	$("#messages_prescribe_medication").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#messages_cancel_medication").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#messages_save_fax").button();
	$("#messages_send_fax").button();
	$("#messages_cancel_fax").button();
	$("#rcopia_orders_rx").button({
		icons: {
			primary: "ui-icon-link"
		}
	}).click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/rcopia_update_medication');?>",
			dataType: "json",
			success: function(data){
				if (data.response == 'Error connecting to DrFirst RCopia.  Try again later.' || data.response == 'No updated prescriptions.') {
					$.jGrowl(data.response);
				} else {
					$.jGrowl(data.response);
					var old = $('#messages_rx_text').val();
					$('#messages_rx_text').val(old + '\n' + data.medtext);
					jQuery("#messages_medications").trigger("reloadGrid");
					jQuery("#medications").trigger("reloadGrid");
				}
			}
		});
	});
	$("#rcopia_rx_helper").button({
		icons: {
			primary: "ui-icon-link"
		}
	}).click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/rcopia_update_medication');?>",
			dataType: "json",
			success: function(data){
				if (data.response == 'Error connecting to DrFirst RCopia.  Try again later.' || data.response == 'No updated prescriptions.') {
					$.jGrowl(data.response);
				} else {
					var old = $("#t_messages_message").val();
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
					$("#t_messages_message").val(old1+data.medtext);
					$('#messages_edit_rx_form').hide('fast');
					$('#messages_edit_rx_form').clearForm();
					$("#messages_rx_text").val('');
					$("#messages_rx_eie_text").val('');
					$("#messages_rx_inactivate_text").val('');
					$("#messages_rx_reactivate_text").val('');
					$("#messages_rx_dialog").dialog('close');
				}
			}
		});
	});
</script>
