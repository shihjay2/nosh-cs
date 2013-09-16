<button type="button" id="save_billing">Save</button>
<button type="button" id="print_invoice">Print Invoice</button>
<button type="button" id="print_hcfa">Print HCFA-1500</button>
<button type="button" id="encounter_payment">Add Payment for Encounter</button>
<button type="button" id="cancel_billing">Cancel</button>
<input type="hidden" name="insurance_id_1" id="insurance_id_1"/>
<input type="hidden" name="insurance_id_2" id="insurance_id_2"/>
<input type="hidden" name="insurance_id_1_old" id="insurance_id_1_old"/>
<input type="hidden" name="insurance_id_2_old" id="insurance_id_2_old"/>
<input type="hidden" name="bill_complex_old" id="billing_bill_complex_old"/>
<hr />
<table>
	<tr>
		<td>Complexity of encounter:</td>
		<td><select name="bill_complex" id="billing_bill_complex" class="text ui-widget-content ui-corner-all"></select></td>
	</tr>
</table><br>
<hr />
<table>
	<tr>
		<td>Primary Insurance:</td>
		<td id="insuranceinfo1"></td>
		<td><button type="button" id="clear_insurance1">Clear</button></td>
	</tr>
	<tr>
		<td>Secondary Insurance:</td>
		<td id="insuranceinfo2"></td>
		<td><button type="button" id="clear_insurance2">Clear</button></td>
	</tr>
</table><br>
<table id="billing_insurance_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="billing_insurance_pager" class="scroll" style="text-align:center;"></div><br>
<button type="button" id="select_insurance1">Select as primary insurance</button>
<button type="button" id="select_insurance2">Select as secondary insurance</button>
<button type="button" id="select_self_pay">No insurance</button><br>
<hr /><br>
<button type="button" id="compile_billing">Generate Bill from Encounter</button><br><br>
<table id="cpt_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="cpt_list_pager" class="scroll" style="text-align:center;"></div><br>
<button type="button" id="add_billing_cpt">Add Row</button>
<button type="button" id="edit_billing_cpt">Edit Row</button>
<button type="button" id="remove_billing_cpt">Remove Row</button><br><br>
<form id="billing_form">
<input type="hidden" name="billing_core_id" id="billing_core_id"/>
	<fieldset id="billing_fieldset" class="ui-state-default ui-corner-all" style="display:none">
		<legend>CPT Row Editor</legend>		
			<table>
				<tr>
					<td>CPT Code:</td>
					<td><input type="text" name="cpt" id="billing_cpt" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
					<td><button type="button" id="cpt_helper">CPT Helper</button></td>
				</tr>
				<tr>
					<td>Charge:</td>
					<td>$<input type="text" name="cpt_charge" id="billing_cpt_charge" style="width:195px" class="text ui-widget-content ui-corner-all"/></td>
					<td><button type="button" id="update_cpt_charge">Update Charge</button></td>
				</tr>
				<tr>
					<td>Unit:</td>
					<td><input type="text" name="unit" id="billing_unit" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Modifier:</td>
					<td><select name="modifier" id="billing_modifier" style="width:200px" class="text ui-widget-content ui-corner-all"></select></td>
				</tr>
				<tr>
					<td>Date of Service/From:</td>
					<td><input type="text" name="dos_f" id="billing_dos_f" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Date of Service/To:</td>
					<td><input type="text" name="dos_t" id="billing_dos_t" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>ICD Pointer</td>
					<td><select name="icd_pointer[]" id="billing_icd" multiple="multiple" style="width:400px" class="multiselect"></select></td>
				</tr>
			</table><br>
		<button type="button" id="save_cpt_form">Save</button>
		<button type="button" id="cancel_cpt_form">Cancel</button>
	</fieldset><br><br>
</form>

<div id="cpt_helper_dialog" title="CPT Helper" style="font-size: 0.9em">
	<div id="cpt_helper_items">
		<h3><a href="#">Evaluation and Management Codes</a></h3>
		<div>
			<table>
				<tr>
					<th>Established Patient</th>
					<th>New Patient</th>
				</tr>
				<tr>
					<td><input name="cpt_helper1" id="99212" value="99212" type="radio"> 99212</td>
					<td><input name="cpt_helper1" id="99202" value="99202" type="radio"> 99202</td>
				</tr>
				<tr>
					<td><input name="cpt_helper1" id="99213" value="99213" type="radio"> 99213</td>
					<td><input name="cpt_helper1" id="99203" value="99203" type="radio"> 99203</td>
				</tr>
				<tr>
					<td><input name="cpt_helper1" id="99214" value="99214" type="radio"> 99214</td>
					<td><input name="cpt_helper1" id="99204" value="99204" type="radio"> 99204</td>
				</tr>
				<tr>
					<td><input name="cpt_helper1" id="99215" value="99215" type="radio"> 99215</td>
					<td><input name="cpt_helper1" id="99205" value="99205" type="radio"> 99205</td>
				</tr>
			</table><br>		
		</div>
		<h3><a href="#">Preventative Visit Codes</a></h3>
		<div>
			<table>
				<tr>
					<th>Established Patient</th>
					<th>New Patient</th>
				</tr>
				<tr>
					<td><input name="cpt_helper1" id="established_prevent" value="<?php echo $prevent_established;?>" type="radio"> <?php echo $prevent_established;?></td>
					<td><input name="cpt_helper1" id="new_prevent" value="<?php echo $prevent_new;?>" type="radio"> <?php echo $prevent_new;?></td>
				</tr>
			</table>
		</div>
		<h3><a href="#">Prolonged Visit Codes</a></h3>
		<div>
			<table>
				<tr>
					<td><input name="cpt_helper1" id="99354" value="99354" type="radio"> 99354</td>
					<td><input name="cpt_helper1" id="99355" value="99355" type="radio"> 99355 - Additional 30 minutes</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/get_billing');?>",
		dataType: "json",
		success: function(data){
			$("#billing_icd").addOption(data, false);
		}
	});	
	$("#billing_bill_complex").addOption({"":"","Low Complexity":"Low Complexity","Medium Complexity":"Medium Complexity","High Complexity":"High Complexity"}, false);
	$("#billing_modifier").addOption({"":"","25":"25 - Significant, Separately Identifiable E & M Service by the Same Physician on the Day of a Procedure:","59":"59 - Distinct Procedural Service"}, false);
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/get_insurance_id');?>",
		dataType: "json",
		success: function(data){
			$("#insurance_id_1").val(data.insurance_id_1);
			$("#insurance_id_2").val(data.insurance_id_2);
			$("#billing_bill_complex").val(data.bill_complex);
			$("#insurance_id_1_old").val(data.insurance_id_1);
			$("#insurance_id_2_old").val(data.insurance_id_2);
			$("#billing_bill_complex_old").val(data.bill_complex);
			if (data.insurance_id_1 == '') {
				$("#insuranceinfo1").html("No primary insurance chosen");
			}
			if (data.insurance_id_2 == '') {
				$("#insuranceinfo2").html("No secondary insurance chosen");
			}
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
				data: "insurance_id_1=" + data.insurance_id_1 + "&insurance_id_2=" + data.insurance_id_2,
				dataType: "json",
				success: function(data){
					$("#insuranceinfo1").html(data.result1);
					$("#insuranceinfo2").html(data.result2);
				}
			});
		}
	});
	$("#cpt_helper_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#cpt_helper_items").accordion({active: false, fillSpace: true});
		},
		close: function(event, ui) {
			var b = $("input[name='cpt_helper1']:checked").val();
			if (b) {
				$("#billing_cpt").val(b);
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_cpt_charge');?>",
					data: "cpt=" + b,
					success: function(data){
						$("#billing_cpt_charge").val(data);
					}
				});
			}
			$('#cpt_helper_items').clearDiv();
		}
	});
	$("#save_billing").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#compile_billing").button({
		icons: {
			primary: "ui-icon-calculator"
		},
	});
	$("#print_invoice").button({
		icons: {
			primary: "ui-icon-print"
		},
	});
	$("#print_hcfa").button({
		icons: {
			primary: "ui-icon-print"
		},
	});
	$("#cancel_billing").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#clear_insurance1").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#clear_insurance2").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#save_billing").click(function(){
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
				}
			}
		});
	});
	$("#compile_billing").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/compile_billing');?>",
			success: function(data){
				$.jGrowl(data);
				jQuery("#cpt_list").trigger("reloadGrid");
			}
		});
	});
	$("#print_invoice").click(function(){
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
					window.open("<?php echo site_url('provider/encounters/print_invoice');?>");
				}
			}
		});
	});
	$("#print_hcfa").click(function(){
		var a = $("#insurance_id_1");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Primary Insurance");
		if (bValid) {
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
						var a = $("#insurance_id_1").val();
						if (a == '' || a == '0') {
							$.jGrowl("No HCFA-1500 printed due to no primary insurance!")
						} else {
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/encounters/generate_hcfa');?>",
								success: function(data){
									if (data == "OK") {
										window.open("<?php echo site_url('provider/encounters/print_hcfa');?>");
									} else {
										$.jGrowl(data);
									}			
								}
							});
						}
					}
				}
			});
		}	
	});
	$("#encounter_payment").button({
		icons: {
			primary: "ui-icon-check"
		},
	});
	$("#encounter_payment").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_eid');?>",
			success: function(data){
				$('#billing_payment_eid').val(data);
				var currentDate = getCurrentDate();
				$('#billing_payment_payment').val('');
				$('#billing_payment_payment_old').val('');
				$('#billing_payment_payment_type').val('');
				$('#billing_payment_payment_type_old').val('');
				$('#billing_payment_dos_f').val(currentDate);
				$('#billing_payment_dos_f_old').val(currentDate);
				$('#billing_payment_dialog').dialog('open');
				$("#billing_payment_payment").focus();		
			}
		});
	});
	$("#cancel_billing").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_insurance_id');?>",
			dataType: "json",
			success: function(data){
				$("#insurance_id_1").val(data.insurance_id_1);
				$("#insurance_id_2").val(data.insurance_id_2);
				$("#billing_bill_complex").val(data.bill_complex);
				$("#insurance_id_1_old").val(data.insurance_id_1);
				$("#insurance_id_2_old").val(data.insurance_id_2);
				$("#billing_bill_complex_old").val(data.bill_complex);
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
					data: "insurance_id_1=" + data.insurance_id_1 + "&insurance_id_2=" + data.insurance_id_2,
					dataType: "json",
					success: function(data){
						$("#insuranceinfo1").html(data.result1);
						$("#insuranceinfo2").html(data.result2);
					}
				});
			}
		});
	});
	jQuery("#cpt_list").jqGrid({
		url:"<?php echo site_url('provider/encounters/procedure_codes/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','CPT','CPT Description','Charge','Units','Modifier','ICD Pointer','DOS From','DOS To'],
		colModel:[
			{name:'billing_core_id',index:'billing_core_id',width:1,hidden:true},
			{name:'cpt',index:'cpt',width:100},
			{name:'cpt_description',index:'cpt_description',width:100},
			{name:'cpt_charge',index:'cpt_charge',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			{name:'unit',index:'unit',width:100},
			{name:'modifier',index:'modifier',width:100},
			{name:'icd_pointer',index:'icd_pointer',width:100,edittype: 'select'},
			{name:'dos_f',index:'dos_f',width:100},
			{name:'dos_t',index:'dos_t',width:100}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#cpt_list_pager'),
		sortname: 'cpt_charge',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Procedure codes for this encounter - Double click on row to get diagnosis codes for each procedure.",
	 	height: "100%",
	 	ondblClickRow: function(id){
	 		var item = jQuery("#cpt_list").getCell(id,'icd_pointer');
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/define_icd');?>",
				data: "icd=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.item, {sticky:true});	
				}
			});
		},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#cpt_list_pager',{search:false,edit:false,add:false,del:false});
	$("#add_billing_cpt").button();
	$("#add_billing_cpt").click(function(){
		$('#billing_form').clearForm();
		$("#billing_unit").val('1');
		$("#billing_modifier").val('');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_billing_dos');?>",
			success: function(data){
				$("#billing_dos_f").val(data);
				$("#billing_dos_t").val(data);
			}
		});
		$('#billing_icd').multiselect("destroy");
		$("#billing_icd").multiselect({
			selectedText: "# of # selected"
		});
		$("#billing_fieldset").show('fast');
	});
	$("#edit_billing_cpt").button();
	$("#edit_billing_cpt").click(function(){
		var item = jQuery("#cpt_list").getGridParam('selrow');
		if(item){
			jQuery("#cpt_list").GridToForm(item,"#billing_form");
			var dx = jQuery("#cpt_list").getCell(item,"icd_pointer");
			var icd_array = String(dx).split("");
			$('#billing_icd').val(icd_array);
			$('#billing_icd').multiselect("destroy");
			$("#billing_icd").multiselect({
				selectedText: "# of # selected"
			});
			$("#billing_fieldset").show('fast');
		} else {
			$.jGrowl("Please select row to edit!");
		}
	});
	$("#remove_billing_cpt").button();
	$("#remove_billing_cpt").click(function(){
		var item = jQuery("#cpt_list").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "<?php echo site_url('provider/encounters/remove_cpt');?>",
				type: "POST",
				data: "billing_core_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#cpt_list").trigger("reloadGrid");			
				}
			});
		} else {
			$.jGrowl("Please select row to remove!");
		}
	});
	$("#billing_cpt").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/cpt');?>",
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
	$("#cpt_helper").button({
		icons: {
			primary: "ui-icon-copy"
		},
	});
	$('#cpt_helper').click(function(){
		$("#cpt_helper_dialog").dialog('open');
	});
	$("#update_cpt_charge").button({
		icons: {
			primary: "ui-icon-check"
		},
	});
	$('#update_cpt_charge').click(function(){
		var item = $("#billing_cpt").val();
		if(item != ''){
			var item2 = $("#billing_cpt_charge").val();
			$.ajax({
				url: "<?php echo site_url('provider/encounters/update_cpt_charge');?>",
				type: "POST",
				data: "cpt=" + item + "&cpt_charge=" + item2,
				success: function(data){
					$.jGrowl(data);		
				}
			});
		} else {
			$.jGrowl("Please enter a CPT code to update!");
		}
	});
	$("#save_cpt_form").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$('#save_cpt_form').click(function(){
		var str = $("#billing_form").serialize();
		var val = $("#billing_icd").val();
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/billing_save');?>",
				data: str,
				success: function(data){
					if (data == 'Close Chart') {
						window.location = "<?php echo site_url();?>";
					} else {
						$.jGrowl(data);
						$("#billing_fieldset").hide('fast');
						jQuery("#cpt_list").trigger("reloadGrid");
					}
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#cancel_cpt_form").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$('#cancel_cpt_form').click(function(){
		$("#billing_form").clearForm();
		$("#billing_fieldset").hide('fast');
	});
	$("#billing_dos_f").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#billing_dos_t").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#billing_dos_f").mask("99/99/9999");
	$("#billing_dos_t").mask("99/99/9999");
	jQuery("#billing_insurance_list").jqGrid({
		url:"<?php echo site_url('provider/chartmenu/insurance/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Copay','Deductible','Comments','Address ID','Relationship'],
		colModel:[
			{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
			{name:'insurance_plan_name',index:'insurance_plan_name',width:350},
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
		pager: jQuery('#billing_insurance_pager'),
		sortname: 'insurance_order',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Insurance Payors",
	 	height: "100%",
	 	ondblClickRow: function(id){
	 		var insurance_plan_name = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_plan_name');
			var insurance_id_num = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_id_num');
			var insurance_group = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_group');
			var insurance_insu_lastname = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_insu_lastname');
			var insurance_insu_firstname = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_insu_firstname');
			var text = insurance_plan_name + '; ID: ' + insurance_id_num;
			if(insurance_group != ''){
				text += "; Group: " + insurance_group;
			}
			text += "; " + insurance_insu_lastname + ", " + insurance_insu_firstname;
			var old = $("#messages_lab_insurance").val();
			if(old){
				var pos = old.lastIndexOf('\n');
				if (pos == -1) {
					var old1 = old + '\n';
				} else {
					var a = old.slice(pos);
					if (a == '') {
						var old1 = old;
					} else {
						var old1 = old + '\n';
					}
				}
			} else {
				var old1 = '';
			}
			$("#messages_lab_insurance").val(old1+text);
		},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#billing_insurance_pager',{search:false,edit:false,add:false,del:false});
	$("#select_insurance1").button();
	$("#select_insurance1").click(function(){
		var item = jQuery("#billing_insurance_list").getGridParam('selrow');
		if(item){
			$("#insurance_id_1").val(item);
			var a = $("#insurance_id_1").val();
			var b = $("#insurance_id_2").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
				data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
				dataType: "json",
				success: function(data){
					$("#insuranceinfo1").html(data.result1);
					$("#insuranceinfo2").html(data.result2);
				}
			});
		} else {
			$.jGrowl("Please select insurance!");
		}
	});
	$("#select_insurance2").button();
	$("#select_insurance2").click(function(){
		var item = jQuery("#billing_insurance_list").getGridParam('selrow');
		if(item){
			$("#insurance_id_2").val(item);
			var a = $("#insurance_id_1").val();
			var b = $("#insurance_id_2").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
				data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
				dataType: "json",
				success: function(data){
					$("#insuranceinfo1").html(data.result1);
					$("#insuranceinfo2").html(data.result2);
				}
			});
		} else {
			$.jGrowl("Please select insurance!");
		}
	});
	$("#select_self_pay").button();
	$("#select_self_pay").click(function(){
		$("#insurance_id_1").val('0');
		$("#insurance_id_2").val('');
		var a = $("#insurance_id_1").val();
		var b = $("#insurance_id_2").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
			data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
			dataType: "json",
			success: function(data){
				$("#insuranceinfo1").html(data.result1);
				$("#insuranceinfo2").html(data.result2);
			}
		});
	});
	$("#clear_insurance1").click(function(){
		$("#insurance_id_1").val('');
		var a = $("#insurance_id_1").val();
		var b = $("#insurance_id_2").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
			data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
			dataType: "json",
			success: function(data){
				$("#insuranceinfo1").html(data.result1);
				$("#insuranceinfo2").html(data.result2);
			}
		});
	});
	$("#clear_insurance2").click(function(){
		$("#insurance_id_2").val('');
		var a = $("#insurance_id_1").val();
		var b = $("#insurance_id_2").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
			data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
			dataType: "json",
			success: function(data){
				$("#insuranceinfo1").html(data.result1);
				$("#insuranceinfo2").html(data.result2);
			}
		});
	});
</script>
