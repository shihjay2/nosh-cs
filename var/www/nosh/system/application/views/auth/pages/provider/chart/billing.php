<div id="billing_list_dialog" title="Patient Billing">
	<input type="hidden" id="billing_list_eid"/>
	<input type="hidden" id="billing_list_other_billing_id"/>
	<div id="total_balance"></div>
	<button type="button" id="billing_notes">Edit Billing Notes</button><button type="button" id="insurance_billing">Insurance</button><br>
	<hr class="ui-state-default"/>
	<table id="billing_encounters" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="billing_encounters_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="edit_encounter_charge">View or Edit Billing Details</button>
	<button type="button" id="payment_encounter_charge">Make Payment for Encounter</button>
	<button type="button" id="invoice_encounter_charge">Print Invoice for Encounter</button><br><br>
	<hr class="ui-state-default"/>
	<table id="billing_other" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="billing_other_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="add_charge">Add Special Bill</button>
	<button type="button" id="edit_charge">Edit Special Bill</button>
	<button type="button" id="payment_charge">Make Payment for Bill</button>
	<button type="button" id="invoice_charge">Print Invoice for Bill</button>
	<button type="button" id="delete_charge">Delete Special Bill</button><br><br>
</div>
<div id="billing_notes_dialog" title="Billing Notes">
	<form id="billing_notes_form">
		<input type="hidden" id="billing_billing_notes_old"/>
		<table>
			<tr>
				<td>Billing Notes:</td>
				<td><textarea name="billing_notes" id="billing_billing_notes" rows="4" style="width:500px" class="text ui-widget-content ui-corner-all"></textarea></td>
			</tr>
		</table><br>
		<button type="button" id="save_billing_notes">Save</button>
		<button type="button" id="cancel_billing_notes">Cancel</button>
	</form>
</div>
<div id="billing_detail_dialog" title="Detailed Billing Information">
	<button type="button" id="save_billing1">Save</button>
	<button type="button" id="print_invoice1">Print Invoice</button>
	<button type="button" id="print_hcfa1">Print HCFA-1500</button>
	<input type="hidden" name="eid" id="billing_eid_1"/>
	<input type="hidden" name="insurance_id_1" id="billing_insurance_id_1"/>
	<input type="hidden" name="insurance_id_2" id="billing_insurance_id_2"/>
	<input type="hidden" name="insurance_id_1_old" id="billing_insurance_id_1_old"/>
	<input type="hidden" name="insurance_id_2_old" id="billing_insurance_id_2_old"/>
	<input type="hidden" name="bill_complex_old" id="billing_bill_complex_old1"/>
	<hr class="ui-state-default"/>
	<table>
		<tr>
			<td>Primary Insurance:</td>
			<td id="billing_insuranceinfo1"></td>
			<td><button type="button" id="billing_clear_insurance1">Clear</button></td>
		</tr>
		<tr>
			<td>Secondary Insurance:</td>
			<td id="billing_insuranceinfo2"></td>
			<td><button type="button" id="billing_clear_insurance2">Clear</button></td>
		</tr>
	</table><br>
	<table id="billing_insurance_list1" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="billing_insurance_pager1" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="billing_select_insurance1">Select as primary insurance</button>
	<button type="button" id="billing_select_insurance2">Select as secondary insurance</button>
	<button type="button" id="billing_select_self_pay">No insurance</button><br>
	<hr class="ui-state-default"/><br>
	<div id="billing_icd9"></div>
	<hr class="ui-state-default"/><br>
	<table id="billing_cpt_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="billing_cpt_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="add_billing_cpt1">Add Row</button>
	<button type="button" id="edit_billing_cpt1">Edit Row</button>
	<button type="button" id="remove_billing_cpt1">Remove Row</button><br><br>
</div>
<div id="cpt_billing_dialog1" title="CPT Editor">
	<form id="billing_form1">
	<input type="hidden" name="billing_core_id" id="billing_core_id1"/>
		<table>
				<tr>
					<td>CPT Code:</td>
					<td><input type="text" name="cpt" id="billing_cpt1" style="width:200px" class="text ui-widget-content ui-corner-all"/> <button type="button" id="cpt_helper1">CPT Helper</button><button type="button" id="cpt_link1">CPT Editor</button></td>
				</tr>
				<tr>
					<td>Charge:</td>
					<td>$<input type="text" name="cpt_charge" id="billing_cpt_charge1" style="width:195px" class="text ui-widget-content ui-corner-all"/> <button type="button" id="update_cpt_charge1">Update Charge</button></td>
				</tr>
				<tr>
					<td>Unit:</td>
					<td><input type="text" name="unit" id="billing_unit1" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Modifier:</td>
					<td><select name="modifier" id="billing_modifier1" style="width:200px" class="text ui-widget-content ui-corner-all"></select></td>
				</tr>
				<tr>
					<td>Date of Service/From:</td>
					<td><input type="text" name="dos_f" id="billing_dos_f1" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Date of Service/To:</td>
					<td><input type="text" name="dos_t" id="billing_dos_t1" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Diagnosis Pointer</td>
					<td><select name="icd_pointer[]" id="billing_icd1" multiple="multiple" style="width:400px" class="multiselect"></select></td>
				</tr>
			</table><br>
	</form>
</div>
<div id="cpt_helper_dialog1" title="CPT Helper">
	<div id="cpt_helper_items1">
		<h3><a href="#">Evaluation and Management Codes</a></h3>
		<div>
			<table>
				<tr>
					<td>New Patient:</td>
					<td>
						<input name="billing_cpt_helper1" id="99202" value="99202" type="radio" class="cpt_buttons"><label for="99202">99202</label>
						<input name="billing_cpt_helper1" id="99203" value="99203" type="radio" class="cpt_buttons"><label for="99203">99203</label>
						<input name="billing_cpt_helper1" id="99204" value="99204" type="radio" class="cpt_buttons"><label for="99204">99204</label>
						<input name="billing_cpt_helper1" id="99205" value="99205" type="radio" class="cpt_buttons"><label for="99205">99205</label>
					</td>
				</tr>
				<tr>
					<td>Established Patient:</td>
					<td>
						<input name="billing_cpt_helper1" id="99212" value="99212" type="radio" class="cpt_buttons"><label for="99212">99212</label>
						<input name="billing_cpt_helper1" id="99213" value="99213" type="radio" class="cpt_buttons"><label for="99213">99213</label>
						<input name="billing_cpt_helper1" id="99214" value="99214" type="radio" class="cpt_buttons"><label for="99214">99214</label>
						<input name="billing_cpt_helper1" id="99215" value="99215" type="radio" class="cpt_buttons"><label for="99215">99215</label>
					</td>
				</tr>
			</table>
		</div>
		<h3><a href="#">Preventative Visit Codes</a></h3>
		<div>
			<input name="billing_cpt_helper1" id="new_prevent" value="" type="radio" class="cpt_buttons"><label for="new_prevent">New - <span id="new_prevent1_text"></span></label>
			<input name="billing_cpt_helper1" id="established_prevent" value="" type="radio" class="cpt_buttons"><label for="established_prevent">Established - <span id="established_prevent1_text"></span></label>
		</div>
		<h3><a href="#">Prolonged Visit Codes</a></h3>
		<div>
			<input name="billing_cpt_helper1" id="99354" value="99354" type="radio" class="cpt_buttons"><label for="99354">99354</label>
			<input name="billing_cpt_helper1" id="99355" value="99355" type="radio" class="cpt_buttons"><label for="99355">99355 - Additional 30 minutes</label>
		</div>
	</div>
</div>
<div id="billing_other_dialog" title="Detailed Billing Information">
	<form id="billing_other_form1">
	<input type="hidden" name="other_billing_id" id="billing_other_billing_id"/>
	<input type="hidden" id="billing_other_reason_old"/>
	<input type="hidden" id="billing_other_cpt_charge_old"/>
	<input type="hidden" id="billing_other_dos_f_old"/>
		<table>
			<tr>
				<td>Reason:</td>
				<td><input type="text" name="reason" id="billing_other_reason1" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Charge:</td>
				<td>$<input type="text" name="cpt_charge" id="billing_other_cpt_charge1" style="width:195px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Date of Charge:</td>
				<td><input type="text" name="dos_f" id="billing_other_dos_f1" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
		</table><br>
		<button type="button" id="save_other_billing_form">Save</button>
		<button type="button" id="cancel_other_billing_form">Cancel</button>
	</form>
</div>	
<div id="billing_payment_dialog" title="Payment">
	<form id="billing_payment_form">
	<input type="hidden" name="billing_core_id" id="billing_billing_core_id"/>
	<input type="hidden" name="other_billing_id" id="billing_payment_other_billing_id"/>
	<input type="hidden" name="eid" id="billing_payment_eid"/>
	<input type="hidden" name="subgrid_table_id" id="billing_subgrid_table_id"/>
	<input type="hidden" id="billing_payment_payment_old"/>
	<input type="hidden" id="billing_payment_payment_type_old"/>
	<input type="hidden" id="billing_payment_dos_f_old"/>
		<table>
			<tr>
				<td>Payment:</td>
				<td>$<input type="text" name="payment" id="billing_payment_payment" style="width:195px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Payment Type:</td>
				<td><input type="text" name="payment_type" id="billing_payment_payment_type" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Date of Payment:</td>
				<td><input type="text" name="dos_f" id="billing_payment_dos_f" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
		</table><br>
		<button type="button" id="save_billing_payment_form">Save</button>
		<button type="button" id="cancel_billing_payment_form">Cancel</button>
	</form>
</div>	
<script type="text/javascript">
	$("#billing_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#billing_encounters").jqGrid('GridUnload');
			jQuery("#billing_encounters").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/billing_encounters/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Chief Complaint','Charges','Balance'],
				colModel:[
					{name:'eid',index:'eid',width:1,hidden:true},
					{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'encounter_cc',index:'encounter_cc',width:355},
					{name:"charges",index:"charges",width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:"balance",index:"balance",width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#billing_encounters_pager'),
				sortname: 'encounter_DOS',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Bills from Encounters - Expand Row for Payment History",
			 	height: "100%",
			 	loadComplete: function(data) {
			 		var id1 = $('#billing_list_eid').val();
			 		var id2 = $('#billing_list_other_billing_id').val();
			 		if (id1 != '') {
			 			jQuery("#billing_encounters").expandSubGridRow(id1);
			 			$('#billing_list_eid').val('');
			 		}
			 		if (id2 != '') {
			 			jQuery("#billing_other").expandSubGridRow(id2);
			 			$('#billing_list_other_billing_id').val('');
			 		}
			 	},
			 	subGrid: true,
			 	subGridRowExpanded: function(subgrid_id, row_id) {
			 		var subgrid_table_id, pager_id;
			 		subgrid_table_id = subgrid_id+"_t";
			 		pager_id = "p_"+subgrid_table_id;
			 		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			 		jQuery("#"+subgrid_table_id).jqGrid({
			 			url:"<?php echo site_url('provider/chartmenu/billing_payment_history1');?>/"+row_id,
			 			datatype: "json",
			 			mtype: "POST",
			 			colNames:['ID','Date of Payment','Payment Amount','Payment Type'],
			 			colModel:[
			 				{name:"billing_core_id",index:"billing_core_id",width:1,hidden:true},
			 				{name:"dos_f",index:"dos_f",width:100,formatter:'date',formatoptions:{srcformat:"m/d/Y", newformat: "ISO8601Short"}},
			 				{name:"payment",index:"payment",width:200,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			 				{name:"payment_type",index:"payment_type",width:300,align:"right"}, 
			 			], 
			 			rowNum:10,
			 			pager: pager_id,
			 			sortname: 'dos_f', 
			 			sortorder: "desc", 
			 			height: '100%',
			 			footerrow : true,
			 			userDataOnFooter : true,
			 			onSelectRow: function(id) {
			 				$('#billing_billing_core_id').val(id);
			 				$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/get_payment');?>",
								data: "id=" + id,
								dataType: "json",
								success: function(data){
									$('#billing_payment_eid').val(data.eid);
									$('#billing_payment_payment').val(data.payment);
									$('#billing_payment_payment_type').val(data.payment_type);
									$('#billing_payment_dos_f').val(data.dos_f);
									$('#billing_payment_payment_old').val(data.payment);
									$('#billing_payment_payment_type_old').val(data.payment_type);
									$('#billing_payment_dos_f_old').val(data.dos_f);
									$('#billing_payment_dialog').dialog('open');
								}
							});
			 			},
			 			jsonReader: { repeatitems : false, id: "0" }
			 		});
			 		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
			 			search:false,
			 			edit:false,
			 			add:false,
			 			del:false
			 		}).jqGrid('navButtonAdd',"#"+pager_id,{
			 			caption:"Delete Payment", 
						buttonicon:"ui-icon-trash", 
						onClickButton: function(){ 
							var id = jQuery(this).getGridParam('selrow');
							if(id){
				 				$('#billing_billing_core_id').val(id);
				 				$.ajax({
									type: "POST",
									url: "<?php echo site_url('provider/chartmenu/get_payment');?>",
									data: "id=" + id,
									dataType: "json",
									success: function(data){
										$('#billing_list_eid').val(data.eid);
									}
								});
				 				if(confirm('Are you sure you want to delete this payment?')){
									$.ajax({
										type: "POST",
										url: "<?php echo site_url('provider/chartmenu/delete_payment1');?>",
										data: "id=" + id,
										dataType: 'json',
										success: function(data){
											if (data.message == 'Close Chart') {
												window.location = "<?php echo site_url();?>";
											} else {
												$.jGrowl(data.message);
												$("#billing_encounters").setCell(data.id,"balance",data.balance); 
											}
										}
									});
									jQuery(this).trigger("reloadGrid");
									$.ajax({
										type: "POST",
										url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
										success: function(data){
											$('#total_balance').html(data);
										}
									});
								}
							} else {
								$.jGrowl('Choose payment to delete!');
							}
						}, 
						position:"last"
					});
			 	}
			}).navGrid('#billing_encounters_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#billing_other").jqGrid('GridUnload');
			jQuery("#billing_other").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/billing_other/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Reason','Charge','Balance'],
				colModel:[
					{name:'other_billing_id',index:'other_billing_id',width:1,hidden:true},
					{name:'dos_f',index:'dos_f',width:100,formatter:'date',formatoptions:{srcformat:"m/d/Y", newformat: "ISO8601Short"},unformat:editDate},
					{name:'reason',index:'reason',width:355},
					{name:'cpt_charge',index:'cpt_charge',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},		
					{name:"balance",index:"balance",width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#billing_other_pager'),
				sortname: 'dos_f',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Miscellaneous Bills - Expand Row for Payment History",
			 	height: "100%",
			 	loadComplete: function(data) {
			 		var id1 = $('#billing_list_eid').val();
			 		var id2 = $('#billing_list_other_billing_id').val();
			 		if (id1 != '') {
			 			jQuery("#billing_encounters").expandSubGridRow(id1);
			 			$('#billing_list_eid').val('');
			 		}
			 		if (id2 != '') {
			 			jQuery("#billing_other").expandSubGridRow(id2);
			 			$('#billing_list_other_billing_id').val('');
			 		}
			 	},
			 	subGrid: true,
			 	subGridRowExpanded: function(subgrid_id, row_id) {
			 		var subgrid_table_id, pager_id;
			 		subgrid_table_id = subgrid_id+"_t1";
			 		pager_id = "p1_"+subgrid_table_id;
			 		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			 		jQuery("#"+subgrid_table_id).jqGrid({
			 			url:"<?php echo site_url('provider/chartmenu/billing_payment_history2');?>/"+row_id,
			 			datatype: "json",
			 			mtype: "POST",
			 			colNames:['ID','Date of Payment','Payment Amount','Payment Type'],
			 			colModel:[
			 				{name:"billing_core_id",index:"billing_core_id",width:1,hidden:true},
			 				{name:"dos_f",index:"dos_f",width:100,formatter:'date',formatoptions:{srcformat:"m/d/Y", newformat: "ISO8601Short"}},
			 				{name:"payment",index:"payment",width:200,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			 				{name:"payment_type",index:"payment_type",width:300,align:"right"}, 
			 			], 
			 			rowNum:10,
			 			pager: pager_id,
			 			sortname: 'dos_f', 
			 			sortorder: "desc", 
			 			height: '100%',
			 			footerrow : true,
			 			userDataOnFooter : true,
			 			ondblClickRow: function(id) {
			 				$('#billing_billing_core_id').val(id);
			 				$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/get_payment');?>",
								data: "id=" + id,
								dataType: "json",
								success: function(data){
									$('#billing_payment_other_billing_id').val(data.other_billing_id);
									$('#billing_payment_payment').val(data.payment);
									$('#billing_payment_payment_type').val(data.payment_type);
									$('#billing_payment_dos_f').val(data.dos_f);
									$('#billing_payment_payment_old').val(data.payment);
									$('#billing_payment_payment_type_old').val(data.payment_type);
									$('#billing_payment_dos_f_old').val(data.dos_f);
									$('#billing_payment_dialog').dialog('open');
								}
							});
			 			},
			 			jsonReader: { repeatitems : false, id: "0" }
			 		});
			 		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
			 			search:false,
			 			edit:false,
			 			add:false,
			 			del:false
			 		}).jqGrid('navButtonAdd',"#"+pager_id,{
			 			caption:"Delete Payment", 
						buttonicon:"ui-icon-trash", 
						onClickButton: function(){ 
							var id = jQuery(this).getGridParam('selrow');
							if(id){
				 				$('#billing_billing_core_id').val(id);
				 				$.ajax({
									type: "POST",
									url: "<?php echo site_url('provider/chartmenu/get_payment');?>",
									data: "id=" + id,
									dataType: "json",
									success: function(data){
										$('#billing_list_eid').val(data.eid);
									}
								});
				 				if(confirm('Are you sure you want to delete this payment?')){
									$.ajax({
										type: "POST",
										url: "<?php echo site_url('provider/chartmenu/delete_payment2');?>",
										data: "id=" + id,
										dataType: 'json',
										success: function(data){
											if (data.message == 'Close Chart') {
												window.location = "<?php echo site_url();?>";
											} else {
												$.jGrowl(data.message);
												$("#billing_other").setCell(data.id,"balance",data.balance); 
											}
										}
									});
									jQuery(this).trigger("reloadGrid");
									$.ajax({
										type: "POST",
										url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
										success: function(data){
											$('#total_balance').html(data);
										}
									});
								}
							} else {
								$.jGrowl('Choose payment to delete!');
							}
						}, 
						position:"last"
					});
			 	}
			}).navGrid('#billing_other_pager',{search:false,edit:false,add:false,del:false});
		}
	});
	$("#billing_list").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
			success: function(data){
				$('#total_balance').html(data);
			}
		});
		$("#billing_list_dialog").dialog('open');
	});
	$("#billing_detail_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 840, 
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			var b = $("input[name='billing_cpt_helper1']:checked").val();
			if (b != '') {
				$("#billing_cpt1").val(b);
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_cpt_charge');?>",
					data: "cpt=" + b,
					success: function(data){
						$("#billing_cpt_charge1").val(data);
					}
				});
			} else {
				$("#billing_cpt1").val('');
				$("#billing_cpt_charge1").val('');
			}
			$('#cpt_helper_items1').clearDiv();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
				success: function(data){
					$('#total_balance').html(data);
				}
			});
		}
	});
	$("#billing_other_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#billing_other_reason1").val();
			var b = $("#billing_other_cpt_charge1").val();
			var c = $("#billing_other_dos_f1").val();
			var a1 = $("#billing_other_reason_old").val();
			var b1 = $("#billing_other_cpt_charge_old").val();
			var c1 = $("#billing_other_dos_f_old").val();
			if(a != a1 || b != b1 || c != c1){
				if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Save to update the form.')){ 
					$('#billing_other_form1').clearForm();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
						success: function(data){
							$('#total_balance').html(data);
						}
					});
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#billing_notes_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 600, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#billing_billing_notes").val();
			var a1 = $("#billing_billing_notes_old").val();
			if(a != a1){
				if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Save to update the form.')){ 
					$('#billing_notes_form').clearForm();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#billing_notes").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#billing_notes").click(function(){
		$('#billing_notes_form').clearForm();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/get_billing_notes/');?>",
			success: function(data){
				$('#billing_billing_notes').val(data);
				$('#billing_billing_notes_old').val(data)
			}
		});
		$('#billing_notes_dialog').dialog('open');
	});
	$("#save_billing_notes").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_billing_notes").click(function(){
		var str = $("#billing_notes_form").serialize();		
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/edit_billing_notes');?>",
				data: str,
				success: function(data){
					if (data == 'Close Chart') {
						window.location = "<?php echo site_url();?>";
					} else {
						$.jGrowl(data);
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
							success: function(data){
								$('#total_balance').html(data);
							}
						});
						$('#billing_notes_form').clearForm();
						$('#billing_notes_dialog').dialog('close');
					}
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#cancel_billing_notes").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_billing_notes").click(function(){
		$('#billing_notes_form').clearForm();
		$('#billing_notes_dialog').dialog('close');
	});
	$("#billing_other_dos_f1").mask("99/99/9999");
	$("#billing_other_dos_f1").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#billing_payment_dos_f").mask("99/99/9999");
	$("#billing_payment_dos_f").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#billing_payment_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 500, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#billing_payment_payment").val();
			var b = $("#billing_payment_payment_type").val();
			var c = $("#billing_payment_dos_f").val();
			var a1 = $("#billing_payment_payment_old").val();
			var b1 = $("#billing_payment_payment_type_old").val();
			var c1 = $("#billing_payment_dos_f_old").val();
			if(a != a1 || b != b1 || c != c1){
				if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Save to update the form.')){ 
					$('#billing_payment_form').clearForm();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
						success: function(data){
							$('#total_balance').html(data);
						}
					});
					return true;
				} else {
					return false;
				}
			} else {
				$('#billing_payment_form').clearForm();
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/chartmenu/total_balance/');?>",
					success: function(data){
						$('#total_balance').html(data);
					}
				});
				return true;
			}
		}
	});
	$("#billing_payment_payment_type").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/payment_type');?>",
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
		minLength: 0
	});
	$("#billing_payment_payment_type").focus(function(){
		$("#billing_payment_payment_type").autocomplete("search", '1');
	});
	$("#save_billing_payment_form").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_billing_payment_form").click(function(){
		var a = $("#billing_payment_payment");
		var b = $("#billing_payment_payment_type");
		var c = $("#billing_payment_dos_f");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Payment");
		bValid = bValid && checkEmpty(b,"Payment Type");
		bValid = bValid && checkEmpty(c,"Date of Payment");
		if (bValid) {
			var str = $("#billing_payment_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/payment_save/');?>",
					data: str,
					dataType: "json",
					success: function(data){
						$.jGrowl(data.message);
						$("#billing_payment_payment_old").val(data.payment);
						$("#billing_payment_payment_type_old").val(data.payment_type);
						$("#billing_payment_dos_f_old").val(data.dos_f);
						if (data.eid != ''){
							$('#billing_list_eid').val(data.eid);
							jQuery("#billing_encounters").trigger("reloadGrid");
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
								success: function(data){
									$('#total_balance').html(data);
								}
							});
						}
						if (data.other_billing_id != ''){
							$('#billing_list_other_billing_id').val(data.other_billing_id);
							jQuery("#billing_other").trigger("reloadGrid");
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
								success: function(data){
									$('#total_balance').html(data);
								}
							});
						}
						$("#billing_payment_dialog").dialog('close');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_billing_payment_form").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$('#cancel_billing_payment_form').click(function(){
		$("#billing_payment_dialog").dialog('close');
	});
	
	$("#edit_encounter_charge").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#edit_encounter_charge").click(function(){
		var id = jQuery("#billing_encounters").getGridParam('selrow');
		if(id){
			$("#billing_eid_1").val(id);
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/get_billing1');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#billing_icd1").addOption(data, false).trigger("liszt:updated");
				}
			});
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/get_prevention');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#established_prevent1").attr("value", data.prevent_established1);
					$("#new_prevent1").attr("value", data.prevent_new1);
					$("#established_prevent1_text").html(data.prevent_established1);
					$("#new_prevent1_text").html(data.prevent_new1);
				}
			});	
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/get_insurance_id1');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#billing_insurance_id_1").val(data.insurance_id_1);
					$("#billing_insurance_id_2").val(data.insurance_id_2);
					$("#billing_insurance_id_1_old").val(data.insurance_id_1);
					$("#billing_insurance_id_2_old").val(data.insurance_id_2);
					if (data.insurance_id_1 == '') {
						$("#billing_insuranceinfo1").html("No primary insurance chosen");
					}
					if (data.insurance_id_2 == '') {
						$("#billing_insuranceinfo2").html("No secondary insurance chosen");
					}
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
						data: "insurance_id_1=" + data.insurance_id_1 + "&insurance_id_2=" + data.insurance_id_2,
						dataType: "json",
						success: function(data){
							$("#billing_insuranceinfo1").html(data.result1);
							$("#billing_insuranceinfo2").html(data.result2);
						}
					});
				}
			});
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/billing/get_assessment');?>/" + id,
				dataType: "json",
				success: function(data){
					if (data != '') {
						if(data.assessment_1.length!=0){
							var label1 = '<strong>Diagnosis #1:</strong> ' + data.assessment_1;
						}
						if(data.assessment_2.length!=0){
							label1 += '<br><strong>Diagnosis #2:</strong> ' + data.assessment_2;
						}
						if(data.assessment_3.length!=0){
							label1 += '<br><strong>Diagnosis #3:</strong> ' + data.assessment_3;
						}
						if(data.assessment_4.length!=0){
							label1 += '<br><strong>Diagnosis #4:</strong> ' + data.assessment_4;
						}
						if(data.assessment_5.length!=0){
							label1 += '<br><strong>Diagnosis #5:</strong> ' + data.assessment_5;
						}
						if(data.assessment_6.length!=0){
							label1 += '<br><strong>Diagnosis #6:</strong> ' + data.assessment_6;
						}
						if(data.assessment_7.length!=0){
							label1 += '<br><strong>Diagnosis #7:</strong> ' + data.assessment_7;
						}
						if(data.assessment_8.length!=0){
							label1 += '<br><strong>Diagnosis #8:</strong> ' + data.assessment_8;
						}
						$("#billing_icd9").html(label1);
					}
				}
			});
			jQuery("#billing_cpt_list").jqGrid('GridUnload');
			jQuery("#billing_cpt_list").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/procedure_codes1');?>/" + id,
				datatype: "json",
				mtype: "POST",
				colNames:['ID','CPT','CPT Description','Charge','Units','Modifier','ICD Pointer','DOS From','DOS To'],
				colModel:[
					{name:'billing_core_id',index:'billing_core_id',width:1,hidden:true},
					{name:'cpt',index:'cpt',width:50},
					{name:'cpt_description',index:'cpt_description',width:100},
					{name:'cpt_charge',index:'cpt_charge',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:'unit',index:'unit',width:50},
					{name:'modifier',index:'modifier',width:50},
					{name:'icd_pointer',index:'icd_pointer',width:50,edittype: 'select'},
					{name:'dos_f',index:'dos_f',width:75},
					{name:'dos_t',index:'dos_t',width:75}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#billing_cpt_list_pager'),
				sortname: 'cpt_charge',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Procedure codes for this encounter - Double click on row to get diagnosis codes for each procedure.",
			 	height: "100%",
			 	ondblClickRow: function(id){
			 		var item = jQuery("#billing_cpt_list").getCell(id,'icd_pointer');
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/define_icd');?>/" + id,
						data: "icd=" + item,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.item, {sticky:true});	
						}
					});
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#billing_cpt_list_pager',{search:false,edit:false,add:false,del:false});
	 		$("#billing_detail_dialog").dialog('open');
		} else {
			$.jGrowl("Please select encounter to edit billing details!");
		}
	});
	$("#payment_encounter_charge").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	$("#payment_encounter_charge").click(function(){
		var item = jQuery("#billing_encounters").getGridParam('selrow');
		if(item){
			$('#billing_payment_eid').val(item);
			var currentDate = getCurrentDate();
			$("#billing_billing_core_id").val('');
			$('#billing_payment_payment').val('');
			$('#billing_payment_payment_old').val('');
			$('#billing_payment_payment_type').val('');
			$('#billing_payment_payment_type_old').val('');
			$('#billing_payment_dos_f').val(currentDate);
			$('#billing_payment_dos_f_old').val(currentDate);
			$('#billing_payment_dialog').dialog('open');
			$("#billing_payment_payment").focus();
		} else {
			$.jGrowl("Please select encounter to add payment!");
		}
	});
	$("#invoice_encounter_charge").button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$("#invoice_encounter_charge").click(function(){
		var item = jQuery("#billing_encounters").getGridParam('selrow');
		if(item){
			window.open("<?php echo site_url('provider/chartmenu/print_invoice1');?>/" + item);
		} else {
			$.jGrowl("Please select encounter to print invoice!");
		}
	});
	
	$("#add_charge").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#add_charge").click(function(){
		var currentDate = getCurrentDate();
		$('#billing_other_dos_f1').val(currentDate);
		$('#billing_other_dos_f_old').val(currentDate);
		$('#billing_other_dialog').dialog('open');
		$("#billing_other_reason1").focus();
	});
	$("#edit_charge").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#edit_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			jQuery("#billing_other").GridToForm(item,"#billing_other_form1");
			var a = $("#billing_other_reason1").val();
			var b = $("#billing_other_cpt_charge1").val();
			var c = $("#billing_other_dos_f1").val();
			$("#billing_other_reason_old").val(a);
			$("#billing_other_cpt_charge_old").val(b);
			$("#billing_other_dos_f_old").val(c);
			$('#billing_other_dialog').dialog('open');
			$("#billing_other_reason1").focus();
		} else {
			$.jGrowl("Please select miscellaneous bill to edit!");
		}
	});
	$("#payment_charge").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	$("#payment_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			$('#billing_payment_other_billing_id').val(item);
			var currentDate = getCurrentDate();
			$("#billing_billing_core_id").val('');
			$('#billing_payment_payment').val('');
			$('#billing_payment_payment_old').val('');
			$('#billing_payment_payment_type').val('');
			$('#billing_payment_payment_type_old').val('');
			$('#billing_payment_dos_f').val(currentDate);
			$('#billing_payment_dos_f_old').val(currentDate);
			$('#billing_payment_dialog').dialog('open');
			$("#billing_payment_payment").focus();
		} else {
			$.jGrowl("Please select miscellaneous bill to add payment!");
		}
	});
	$("#invoice_charge").button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$("#invoice_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			window.open("<?php echo site_url('provider/chartmenu/print_invoice2');?>/" + item);
		} else {
			$.jGrowl("Please select encounter to print invoice!");
		}
	});
	$("#delete_charge").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#delete_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this miscellaneous bill?')){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/delete_other_bill');?>",
					data: "billing_core_id=" + item,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							jQuery("#billing_other").trigger("reloadGrid");
						}
					}
				});
			}
		} else {
			$.jGrowl("Please select miscellaneous bill to delete!");
		}
	});
	$("#billing_other_reason1").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/billing_reason');?>",
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
	$("#save_other_billing_form").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_other_billing_form").click(function(){
		var a = $("#billing_other_reason1");
		var b = $("#billing_other_cpt_charge1");
		var c = $("#billing_other_dos_f1");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Reason");
		bValid = bValid && checkEmpty(b,"Charge");
		bValid = bValid && checkEmpty(c,"Date of Charge");
		if (bValid) {
			var str = $("#billing_other_form1").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/billing_other_save/');?>",
					data: str,
					dataType: "json",
					success: function(data){
						$.jGrowl(data.message);
						$("#billing_other_reason_old").val(data.reason);
						$("#billing_other_cpt_charge_old").val(data.cpt_charge);
						$("#billing_other_dos_f_old").val(data.dos_f);
						$('#billing_list_other_billing_id').val(data.other_billing_id);
						jQuery("#billing_other").trigger("reloadGrid");
						$("#billing_other_dialog").dialog('close');
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
							success: function(data){
								$('#total_balance').html(data);
							}
						});
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_other_billing_form").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$('#cancel_other_billing_form').click(function(){
		$("#billing_other_form1").clearForm();
		$("#billing_other_dialog").dialog('close');
	});
	$("#billing_modifier1").addOption({"":"","25":"25 - Significant, Separately Identifiable E & M Service.","52":"52 - Reduced Service .","59":"59 - Distinct Procedural Service."}, false);
	$("#cpt_helper_dialog1").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function() {
			$("#cpt_helper_items1").accordion({active: false, fillSpace: true});
		},
		close: function(event, ui) {
			var b = $("input[name='billing_cpt_helper1']:checked").val();
			if (b) {
				$("#billing_cpt1").val(b);
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_cpt_charge');?>",
					data: "cpt=" + b,
					success: function(data){
						$("#billing_cpt_charge1").val(data);
					}
				});
			}
			$('#cpt_helper_items1').clearDiv();
		}
	});
	$("#save_billing1").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#billing_clear_insurance1").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#billing_clear_insurance2").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#save_billing1").click(function(){
		var ins1 = $("#billing_insurance_id_1").val();
		var ins2 = $("#billing_insurance_id_2").val();
		var eid = $("#billing_eid_1").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/billing_save1');?>",
			data: "insurance_id_1=" + ins1 + "&insurance_id_2=" + ins2 + "&eid=" + eid,
			success: function(data){
				if (data == 'Close Chart') {
					window.location = "<?php echo site_url();?>";
				} else {
					$.jGrowl(data);
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
						success: function(data){
							$('#total_balance').html(data);
						}
					});
					jQuery("#billing_encounters").trigger("reloadGrid");
				}
			}
		});
	});
	$("#print_invoice1").button({icons: {primary: "ui-icon-print"}}).click(function(){
		var ins1 = $("#billing_insurance_id_1").val();
		var ins2 = $("#billing_insurance_id_2").val();
		var eid = $("#billing_eid_1").val();
		window.open("<?php echo site_url('provider/chartmenu/print_invoice1');?>/" + eid + "/" + ins1 + "/" + ins2);
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
			success: function(data){
				$('#total_balance').html(data);
			}
		});
		jQuery("#billing_encounters").trigger("reloadGrid");
	});
	$("#print_hcfa1").button({icons: {primary: "ui-icon-print"}}).click(function(){
		var a = $("#billing_insurance_id_1");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Primary Insurance");
		if (bValid) {
			var ins1 = $("#billing_insurance_id_1").val();
			var ins2 = $("#billing_insurance_id_2").val();
			var eid = $("#billing_eid_1").val();
			if (ins1 == '' || ins1 == '0') {
				$.jGrowl("No HCFA-1500 printed due to no primary insurance!");
			} else {
				window.open("<?php echo site_url('provider/chartmenu/generate_hcfa1');?>/" + eid + "/" + ins1 + "/" + ins2);
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/total_balance/');?>",
					success: function(data){
						$('#total_balance').html(data);
					}
				});
				jQuery("#billing_encounters").trigger("reloadGrid");
			}
		}
	});
	$("#billing_icd1").chosen();
	$("#billing_icd1").change(function() {
		var a = $(this).val();
		if (a != '') {
			var b = ["1","2","3","4"];
			var c = 0;
			for (var i=0;i<a.length;i++) {
				if (b.indexOf(a[i]) > 0) {
					c++;
				}
			}
			if (c != 0 && c<a.length) {
				$.jGrowl("You must choose diagnoses grouped 1-4 or 5-6!");
			}
		}
	});
	$("#add_billing_cpt1").button();
	$("#add_billing_cpt1").click(function(){
		$('#billing_form1').clearForm();
		$("#billing_unit1").val('1');
		$("#billing_modifier1").val('');
		$("#billing_dos_f1").val();
		$("#billing_dos_t1").val();
		$("#cpt_billing_dialog1").dialog('open');
		$("#billing_cpt1").focus();
	});
	$("#edit_billing_cpt1").button();
	$("#edit_billing_cpt1").click(function(){
		var item = jQuery("#billing_cpt_list").getGridParam('selrow');
		if(item){
			jQuery("#billing_cpt_list").GridToForm(item,"#billing_form1");
			var dx = jQuery("#billing_cpt_list").getCell(item,"icd_pointer");
			var icd_array = String(dx).split("");
			var length = icd_array.length;
			for (var i = 0; i < length; i++) {
				$("#billing_icd1").selectOptions(icd_array[i]);
			}
			$("#billing_icd1").trigger("liszt:updated");
			$("#cpt_billing_dialog1").dialog('open');
			$("#billing_cpt_charge1").focus();
		} else {
			$.jGrowl("Please select row to edit!");
		}
	});
	$("#remove_billing_cpt1").button();
	$("#remove_billing_cpt1").click(function(){
		var item = jQuery("#billing_cpt_list").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "<?php echo site_url('provider/encounters/remove_cpt');?>",
				type: "POST",
				data: "billing_core_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#billing_cpt_list").trigger("reloadGrid");			
				}
			});
		} else {
			$.jGrowl("Please select row to remove!");
		}
	});
	$("#cpt_billing_dialog1").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		buttons: {
			'Save': function() {
				var a = $("#billing_icd1");
				var b = $("#billing_cpt1");
				var c = $("#billing_cpt_charge1");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"ICD Pointer");
				bValid = bValid && checkEmpty(b,"CPT Code");
				bValid = bValid && checkEmpty(c,"CPT Charge");
				if (bValid) {
					var str = $("#billing_form1").serialize();
					var eid = $("#billing_eid_1").val();
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/billing_save');?>/" + eid,
							data: str,
							success: function(data){
								if (data == 'Close Chart') {
									window.location = "<?php echo site_url();?>";
								} else {
									$.jGrowl(data);
									$("#billing_form1").clearForm();
									$("#cpt_billing_dialog1").dialog('close');
									jQuery("#billing_cpt_list").trigger("reloadGrid");
								}
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#billing_form1").clearForm();
				$("#cpt_billing_dialog1").dialog('close');
			}
		}
	});
	$("#billing_cpt1").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/cpt1');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					} else {
						var addterm = [{"label": req.term + ": Select to add CPT to database.", "value":"*/add/*", "value1": req.term}];
						add(addterm);
					}
				}
			});
		},
		minLength: 3,
		select: function(event, ui){
			if (ui.item.value == "*/add/*") {
				$("#configuration_cpt_form").clearForm();
				if (ui.item.value1.length > 5) {
					$("#configuration_cpt_description").val(ui.item.value1);
				} else {
					$("#configuration_cpt_code").val(ui.item.value1);
				}
				$('#configuration_cpt_origin').val("billing_cpt");
				$('#configuration_cpt_dialog').dialog('open');
				$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
			} else {
				$("#billing_cpt_charge1").val(ui.item.charge);
			}
		},
		change: function (event, ui) {
			if(!ui.item){
				$.jGrowl("CPT code must be selected from the database!");
				$("#billing_cpt").addClass("ui-state-error");
			} else {
				$("#billing_cpt").removeClass("ui-state-error");
			}
		}
	});
	$("#cpt_helper1").button({
		icons: {
			primary: "ui-icon-copy"
		}
	});
	$('#cpt_helper1').click(function(){
		$("#cpt_helper_dialog1").dialog('open');
	});
	$("#update_cpt_charge1").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	$('#update_cpt_charge1').click(function(){
		var item = $("#billing_cpt1").val();
		if(item != ''){
			var item2 = $("#billing_cpt_charge1").val();
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
	
	$("#billing_dos_f1").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#billing_dos_t1").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#billing_dos_f1").mask("99/99/9999");
	$("#billing_dos_t1").mask("99/99/9999");
	jQuery("#billing_insurance_list1").jqGrid({
		url:"<?php echo site_url('provider/chartmenu/insurance/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Copay','Deductible','Comments','Address ID','Relationship'],
		colModel:[
			{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
			{name:'insurance_plan_name',index:'insurance_plan_name',width:270},
			{name:'insurance_id_num',index:'insurance_id_num',width:100},
			{name:'insurance_group',index:'insurance_group',width:100},
			{name:'insurance_order',index:'insurance_order',width:100},
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
		pager: jQuery('#billing_insurance_pager1'),
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
	}).navGrid('#billing_insurance_pager1',{search:false,edit:false,add:false,del:false});
	$("#billing_select_insurance1").button();
	$("#billing_select_insurance1").click(function(){
		var item = jQuery("#billing_insurance_list1").getGridParam('selrow');
		if(item){
			$("#billing_insurance_id_1").val(item);
			var a = $("#billing_insurance_id_1").val();
			var b = $("#billing_insurance_id_2").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
				data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
				dataType: "json",
				success: function(data){
					$("#billing_insuranceinfo1").html(data.result1);
					$("#billing_insuranceinfo2").html(data.result2);
				}
			});
		} else {
			$.jGrowl("Please select insurance!");
		}
	});
	$("#billing_select_insurance2").button();
	$("#billing_select_insurance2").click(function(){
		var item = jQuery("#billing_insurance_list1").getGridParam('selrow');
		if(item){
			$("#billing_insurance_id_2").val(item);
			var a = $("#billing_insurance_id_1").val();
			var b = $("#billing_insurance_id_2").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
				data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
				dataType: "json",
				success: function(data){
					$("#billing_insuranceinfo1").html(data.result1);
					$("#billing_insuranceinfo2").html(data.result2);
				}
			});
		} else {
			$.jGrowl("Please select insurance!");
		}
	});
	$("#billing_select_self_pay").button();
	$("#billing_select_self_pay").click(function(){
		$("#billing_insurance_id_1").val('0');
		$("#billing_insurance_id_2").val('');
		var a = $("#billing_insurance_id_1").val();
		var b = $("#billing_insurance_id_2").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
			data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
			dataType: "json",
			success: function(data){
				$("#billing_insuranceinfo1").html(data.result1);
				$("#billing_insuranceinfo2").html(data.result2);
			}
		});
	});
	$("#billing_clear_insurance1").click(function(){
		$("#billing_insurance_id_1").val('');
		var a = $("#billing_insurance_id_1").val();
		var b = $("#billing_insurance_id_2").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
			data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
			dataType: "json",
			success: function(data){
				$("#billing_insuranceinfo1").html(data.result1);
				$("#billing_insuranceinfo2").html(data.result2);
			}
		});
	});
	$("#billing_clear_insurance2").click(function(){
		$("#billing_insurance_id_2").val('');
		var a = $("#billing_insurance_id_1").val();
		var b = $("#billing_insurance_id_2").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_insurance_info');?>",
			data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
			dataType: "json",
			success: function(data){
				$("#billing_insuranceinfo1").html(data.result1);
				$("#billing_insuranceinfo2").html(data.result2);
			}
		});
	});
	$("#insurance_billing").button({
		icons: {
			primary: "ui-icon-suitcase"
		}
	});
	$("#insurance_billing").click(function() {
		$("#demographics_insurance_dialog").dialog('open');
		$("#menu_insurance_main_fieldset").hide('fast');
	});
	$(".cpt_buttons").button();
	$("#cpt_link1").button().click(function(){
		$("#configuration_dialog").dialog('open');
		$("#configuration_accordion").accordion("option", "active", 7);
	});
</script>
