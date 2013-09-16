<div id="immunizations_list_dialog" title="Immunizations" style="font-size: 0.9em">
	<div id="orders_imm_header" style="display:none">
		<button type="button" id="save_orders_imm">Save Immunizations</button> 
		<button type="button" id="cancel_orders_imm_helper">Cancel</button> 
		<hr />
	</div>
	<table id="immunizations" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="immunizations_pager" class="scroll" style="text-align:center;"></div><br>
	<input type="hidden" name="imm_text" id="imm_text"/>
	<form name="edit_immunization_form" id="edit_immunization_form" style="display: none">
		<input type="hidden" name="imm_id" id="imm_id"/>
		<input type="hidden" name="imm_cvxcode" id="imm_cvxcode"/>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>Immunization</legend>
			<table>
				<tbody>
					<tr>
						<td>Immunization:</td>
						<td><input type="text" name="imm_immunization" id="imm_immunization" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Sequence:</td>
						<td><select id ="imm_sequence" name="imm_sequence" class="text ui-widget-content ui-corner-all"></select></td>
						<td>Given Elsewhere: <input type="checkbox" name="imm_elsewhere" id="imm_elsewhere" value="Yes" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>Route:</td>
						<td><select id ="imm_route" name="imm_route" class="text ui-widget-content ui-corner-all"></select></td>
						<td></td>
					</tr>
					<tr>
						<td>Body Site:</td>
						<td><select id ="imm_body_site" name="imm_body_site" class="text ui-widget-content ui-corner-all"></select></td>
						<td></td>
					</tr>
					<tr>
						<td>Dosage:</td>
						<td><input type="text" name="imm_dosage" id="imm_dosage" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Unit:</td>
						<td><input type="text" name="imm_dosage_unit" id="imm_dosage_unit" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Lot Number:</td>
						<td><input type="text" name="imm_lot" id="imm_lot" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Manufacturer:</td>
						<td><input type="text" name="imm_manufacturer" id="imm_manufacturer" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Expiration Date:</td>
						<td><input type="text" name="imm_expiration" id="imm_expiration" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Date Active:</td>
						<td><input type="text" name="imm_date" id="imm_date" class="text ui-widget-content ui-corner-all"/></td>
						<td>
							<button type="button" id="save_immunization">Save</button> 
							<button type="button" id="cancel_immunization">Cancel</button>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
	</form>
	<form name="edit_immunization_form1" id="edit_immunization_form1" style="display: none">
		<input type="hidden" name="imm_id" id="imm_id1"/>
		<input type="hidden" name="cpt" id="imm_cpt"/>
		<input type="hidden" name="imm_cvxcode" id="imm_cvxcode1"/>
		<input type="hidden" name="vaccine_id" id="imm_vaccine_id"/>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>Immunization</legend>
			<table>
				<tbody>
					<tr>
						<td>Immunization:</td>
						<td><input type="text" name="imm_immunization" id="imm_immunization1" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Sequence:</td>
						<td><select id ="imm_sequence1" name="imm_sequence" class="text ui-widget-content ui-corner-all"></select></td>
						<td>Vaccine Information Sheet Given: <input type="checkbox" name="imm_vis" id="imm_vis1" value="Yes" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>Route:</td>
						<td><select id ="imm_route1" name="imm_route" class="text ui-widget-content ui-corner-all"></select></td>
						<td></td>
					</tr>
					<tr>
						<td>Body Site:</td>
						<td><select id ="imm_body_site1" name="imm_body_site" class="text ui-widget-content ui-corner-all"></select></td>
						<td></td>
					</tr>
					<tr>
						<td>Dosage:</td>
						<td><input type="text" name="imm_dosage" id="imm_dosage1" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Unit:</td>
						<td><input type="text" name="imm_dosage_unit" id="imm_dosage_unit1" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Lot Number:</td>
						<td><input type="text" name="imm_lot" id="imm_lot1" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Manufacturer:</td>
						<td><input type="text" name="imm_manufacturer" id="imm_manufacturer1" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Expiration Date:</td>
						<td><input type="text" name="imm_expiration" id="imm_expiration1" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Date Active:</td>
						<td><input type="text" name="imm_date" id="imm_date1" class="text ui-widget-content ui-corner-all"/></td>
						<td>
							<button type="button" id="save_immunization1">Save</button> 
							<button type="button" id="cancel_immunization1">Cancel</button>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
	</form>
</div>
<div id="immunizations_vis_dialog" title="Vaccine Information Sheets" style="font-size: 0.9em">
	<ul>
		<li><a id="vis_dtap" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-dtap.pdf">DTaP</a></li>
        <li><a id="vis_hep_a" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-hep-a.pdf">Hepatitis A</a></li>
        <li><a id="vis_hep_b" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-hep-b.pdf">Hepatitis B</a></li>
        <li><a id="vis_hib" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-hib.pdf">Hib</a></li>
        <li><a id="vis_hpv" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-hpv.pdf">HPV</a></li>
        <li><a id="vis_flulive" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-flulive.pdf">Flu (Live, Intranasal)</a></li>
        <li><a id="vis_flu" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-flu.pdf">Flu (Inactivated)</a></li>
        <li><a id="vis_mmr" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-mmr.pdf">MMR</a></li>
        <li><a id="vis_mening" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-mening.pdf">Meningococcal</a></li>
        <li><a id="vis_pcv" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-pcv.pdf">Pneumococcal (PCV7)</a></li>
        <li><a id="vis_ppv" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-ppv.pdf">Pneumococcal (PPSV23)</a></li>
        <li><a id="vis_ipv" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-ipv.pdf">Polio</a></li>
        <li><a id="vis_rotavirus" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-rotavirus.pdf">Rotavirus</a></li>
        <li><a id="vis_shingles" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-shingles.pdf">Shingles</a></li>
        <li><a id="vis_td_tdap" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-td-tdap.pdf">Td/Tdap</a></li>
        <li><a id="vis_varicella" href="http://www.cdc.gov/vaccines/pubs/vis/downloads/vis-varicella.pdf">Varicella (Chickenpox)</a></li>     
	</ul>
	<hr/>
	Immunizations to be given:<br>
	<input type="text" name="consent_vaccine_list" id="consent_vaccine_list" style="width:400px" class="text ui-widget-content ui-corner-all"> 
	<input type="button" id="consent_immunization1" value="Consent Form" class="ui-button ui-state-default ui-corner-all"/>
</div>
<script type="text/javascript">
	$("#immunizations_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#immunizations").jqGrid('GridUnload');
			jQuery("#immunizations").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/immunizations/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Given','Immunization','Sequence','Given Elsewhere','Body Site','Dosage','Unit','Route','Lot Number','Manufacturer','Expiration Date','VIS'],
				colModel:[
					{name:'imm_id',index:'imm_id',width:1,hidden:true},
					{name:'imm_date',index:'imm_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'imm_immunization',index:'imm_immunization',width:410},
					{name:'imm_sequence',index:'imm_sequence',width:65},
					{name:'imm_elsewhere',index:'imm_elsewhere',width:150},
					{name:'imm_body_site',index:'imm_body_site',width:1,hidden:true},
					{name:'imm_dosage',index:'imm_dosage',width:1,hidden:true},
					{name:'imm_dosage_unit',index:'imm_dosage_unit',width:1,hidden:true},
					{name:'imm_route',index:'imm_route',width:1,hidden:true},
					{name:'imm_lot',index:'imm_lot',width:1,hidden:true},
					{name:'imm_manufacturer',index:'imm_manufacturer',width:1,hidden:true},
					{name:'imm_expiration',index:'imm_expiration',width:1,hidden:true},
					{name:'imm_vis',index:'imm_vis',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#immunizations_pager'),
				sortname: 'imm_immunization',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Immunizations",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#immunizations_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$('#edit_immunization_form').clearForm();
		}
	});
	$("#immunizations_vis_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 600, 
		modal: true,
		draggable: false,
		resizable: false
	});
	$("#immunizations_list").click(function() {
		$("#immunizations_list_dialog").dialog('open');
		$('#edit_immunization_form').hide('fast');
		$('#orders_imm_header').hide('fast');
		$('#imm_order').hide('fast');
		$('#imm_menu').show('fast');
	});
	$("#imm_date").mask("99/99/9999");
	$("#imm_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#imm_expiration").mask("99/99/9999");
	$("#imm_expiration").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#imm_route").addOption({"":"","intramuscularly":"IM","subcutaneously":"SC","by mouth":"PO","intravenously":"IV"}, false);
	$("#imm_route").selectOptions();
	$("#imm_sequence").addOption({"":"","1":"First","2":"Second","3":"Third","4":"Fourth","5":"Fifth"}, false);
	$("#imm_sequence").selectOptions();
	$("#imm_body_site").addOption({"Right Deltoid":"Right Deltoid","Left Deltoid":"Left Deltoid","Right Thigh":"Right Thigh","Left Thigh":"Left Thigh"}, false);
	$("#imm_body_site").selectOptions();
	$("#imm_immunization").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/imm');?>",
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
			$("#imm_cvxcode").val(ui.item.cvx);
		}
	});
	$("#imm_date1").mask("99/99/9999");
	$("#imm_date1").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#imm_expiration1").mask("99/99/9999");
	$("#imm_expiration1").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#imm_route1").addOption({"intramuscularly":"IM","subcutaneously":"SC","by mouth":"PO","intravenously":"IV"}, false);
	$("#imm_route1").selectOptions();
	$("#imm_sequence1").addOption({"1":"First","2":"Second","3":"Third","4":"Fourth","5":"Fifth"}, false);
	$("#imm_sequence1").selectOptions();
	$("#imm_body_site1").addOption({"Right Deltoid":"Right Deltoid","Left Deltoid":"Left Deltoid","Right Thigh":"Right Thigh","Left Thigh":"Left Thigh"}, false);
	$("#imm_body_site1").selectOptions();
	$("#imm_immunization1").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/imm1');?>",
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
			if (ui.item.value != '') {
				$("#imm_cpt").val(ui.item.cpt);
				var edit_date = editDate1(ui.item.expiration);
				$("#imm_expiration1").val(edit_date);
				$("#imm_manufacturer1").val(ui.item.manufacturer);
				$("#imm_lot1").val(ui.item.lot);
				$("#imm_cvxcode1").val(ui.item.cvx);
				$("#imm_vaccine_id").val(ui.item.vaccine_id);
			}		
		}
	});
	$("#imm_immunization1").click(function(){
		$("#imm_immunization1").autocomplete("search", " ");
	});
	$("#add_immunization").click(function(){
		$('#edit_immunization_form').clearForm();
		var currentDate = getCurrentDate();
		$('#imm_date').val(currentDate);
		$('#edit_immunization_form').show('fast');
		$("#imm_immunization").focus();
	});
	$("#edit_immunization").click(function(){
		var item = jQuery("#immunizations").getGridParam('selrow');
		if(item){
			jQuery("#immunizations").GridToForm(item,"#edit_immunization_form");
			var date = $('#imm_date').val();
			var edit_date = editDate(date);
			$('#imm_date').val(edit_date);
			var expiration = $('#imm_expiration').val();
			var edit_expiration = editDate1(expiration);
			$('#imm_expiration').val(edit_expiration);
			$('#edit_immunization_form').show('fast');
			$("#imm_immunization").focus();
		} else {
			$.jGrowl("Please select immunization to edit!")
		}
	});
	$("#delete_immunization").click(function(){
		var item = jQuery("#immunizations").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this immunization?')){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/chartmenu/delete_immunizations');?>",
					data: "imm_id=" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#immunizations").trigger("reloadGrid");
						$('#edit_immunization_form').clearForm();
						$('#edit_immunization_form').hide('fast');
					}
				});
			}
		} else {
			$.jGrowl("Please select immunization to delete!")
		}
	});
	$("#add_immunization1").click(function(){
		$('#edit_immunization_form1').clearForm();
		var currentDate = getCurrentDate();
		$('#imm_date1').val(currentDate);
		$('#edit_immunization_form1').show('fast');
		$("#imm_immunization1").focus();
	});
	$("#edit_immunization1").click(function(){
		var item = jQuery("#immunizations").getGridParam('selrow');
		if(item){
			jQuery("#immunizations").GridToForm(item,"#edit_immunization_form1");
			var date = $('#imm_date1').val();
			var edit_date = editDate(date);
			$('#imm_date1').val(edit_date);
			$('#edit_immunization_form1').show('fast');
			$("#imm_immunization1").focus();
		} else {
			$.jGrowl("Please select immunization to edit!")
		}
	});
	$("#delete_immunization1").click(function(){
		var item = jQuery("#immunizations").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this immunization?')){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/chartmenu/delete_immunizations');?>",
					data: "imm_id=" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#immunizations").trigger("reloadGrid");
						$('#edit_immunization_form1').clearForm();
						$('#edit_immunization_form1').hide('fast');
					}
				});
			}
		} else {
			$.jGrowl("Please select immunization to delete!")
		}
	});
	$("#vis_immunization1").click(function(){
		$("#immunizations_vis_dialog").dialog('open');
	});
	$("#consent_immunization1").click(function(){
		var item = $("#consent_vaccine_list").val();
		if(item) {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/chartmenu/consent_immunizations');?>",
				data: "vaccine_list=" + item,
				success: function(data){
					if (data == "OK") {
						window.open("<?php echo site_url('billing/chartmenu/print_consent');?>");
					} else {
						$.jGrowl(data);
					}			
				}
			});
		} else {
			$.jGrowl("Please click at least one vaccine information sheet!")
		}	
	});
	$("#vis_dtap").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'DTaP';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_hep_a").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Hepatitis A';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_hep_b").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Hepatitis B';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_hib").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Hib';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_hpv").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'HPV';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_flulive").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Flu (intranasal, live)';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_flu").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Flu (inactivated)';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_mmr").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'MMR';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_mening").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Meningococcal';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_pcv").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'PCV7';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_ppv").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Pneumococcal';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_ipv").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Polio';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_rotavirus").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Rotavirus';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_shingles").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Shingles';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_td_tdap").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Td/Tdap';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_varicella").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Varicella';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#save_immunization").click(function(){
		var imm = $("#imm_immunization");
		var bValid = true;
		bValid = bValid && checkEmpty(imm,"Immunization");
		if (bValid) {
			var str = $("#edit_immunization_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/chartmenu/edit_immunization');?>",
					data: str,
					dataType: "json",
					success: function(data){
						if (data.message == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data.message);
							jQuery("#immunizations").trigger("reloadGrid");
							$('#edit_immunization_form').clearForm();
							$('#edit_immunization_form').hide('fast');
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_immunization").click(function(){
		$('#edit_immunization_form').clearForm();
		$('#edit_immunization_form').hide('fast');
	});
	$("#save_immunization1").click(function(){
		var imm = $("#imm_immunization1");
		var bValid = true;
		bValid = bValid && checkEmpty(imm,"Immunization");
		if (bValid) {
			var str = $("#edit_immunization_form1").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/chartmenu/edit_immunization1');?>",
					data: str,
					dataType: "json",
					success: function(data){
						if (data.message == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data.message);
							jQuery("#immunizations").trigger("reloadGrid");
							var old = $('#imm_text').val();
							$('#imm_text').val(old + '\n' + data.medtext);
							$('#edit_immunization_form1').clearForm();
							$('#edit_immunization_form1').hide('fast');
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_immunization1").click(function(){
		$('#edit_immunization_form1').clearForm();
		$('#edit_immunization_form1').hide('fast');
	});
	$('#save_orders_imm').click(function(){
		var a = $("#imm_text").val();
		if(a){
			var a1 = a + '\n\n';	
		} else {
			var a1 = '';
		}
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/encounters/orders_imm_save');?>",
			data: "rx_immunizations=" + a1,
			success: function(data){
				if (data == 'Close Chart') {
					window.location = "<?php echo site_url();?>";
				} else {
					$.jGrowl(data);
					$("#orders_imm_header").hide('fast');
					$("#imm_text").val('');
					$('#imm_order').hide('fast');
					$('#imm_menu').hide('fast');
					$("#immunizations_list_dialog").dialog('close');
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/encounters/check_orders');?>",
						dataType: "json",
						success: function(data){
							$('#button_orders_labs_status').html(data.labs_status);
							$('#button_orders_rad_status').html(data.rad_status);
							$('#button_orders_cp_status').html(data.cp_status);
							$('#button_orders_ref_status').html(data.ref_status);
							$('#button_orders_rx_status').html(data.rx_status);
							$('#button_orders_imm_status').html(data.imm_status);
							$('#button_orders_sup_status').html(data.sup_status);
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('billing/encounters/tip_orders/imm');?>",
								success: function(data){
									$('#orders_imm_tip').html(data);
								}
							});
						}
					});
				}
			}
		});
	});
	$("#cancel_orders_imm_helper").click(function() {
		$("#orders_imm_header").hide('fast');
		$('#edit_immunization_form1').clearForm();
		$("#imm_text").val('');
		$('#imm_order').hide('fast');
		$('#imm_menu').hide('fast');
		$('#edit_immunization_form1').hide('fast');
		$("#immunizations_list_dialog").dialog('close');
	});
	$('#save_orders_imm').button({icons: {primary: "ui-icon-disk"},});
	$('#cancel_orders_imm_helper').button({icons: {primary: "ui-icon-close"},});
	$('#save_immunization').button({icons: {primary: "ui-icon-disk"},});
	$('#cancel_immunization').button({icons: {primary: "ui-icon-close"},});
	$('#save_immunization1').button({icons: {primary: "ui-icon-disk"},});
	$('#cancel_immunization1').button({icons: {primary: "ui-icon-close"},});
	$('#add_immunization').button({icons: {primary: "ui-icon-plus"},});
	$('#edit_immunization').button({icons: {primary: "ui-icon-pencil"},});
	$('#delete_immunization').button({icons: {primary: "ui-icon-trash"},});
	$('#add_immunization1').button({icons: {primary: "ui-icon-plus"},});
	$('#edit_immunization1').button({icons: {primary: "ui-icon-pencil"},});
	$('#delete_immunization1').button({icons: {primary: "ui-icon-trash"},});
	$('#vis_immunization1').button();
	$('#cpnsent_immunization1').button();
</script>
