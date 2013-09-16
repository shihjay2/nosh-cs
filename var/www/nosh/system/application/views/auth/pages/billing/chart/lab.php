<div id="messages_lab_dialog" title="Lab Helper">
	<input type="hidden" name="messages_lab_origin" id="messages_lab_origin"/>
	<form name="edit_message_lab_form" id="edit_message_lab_form">
	<div id="messages_lab_grid">
		<button type="button" id="save_lab_helper" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
			<span style="float:right;" class="ui-button-text"><div id="save_lab_helper_label"></div></span>
		</button><button type="button" id="cancel_lab_helper" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
			<span style="float:right;" class="ui-button-text">Cancel</span>
		</button> 
		<hr class="ui-state-default"/>
		<table id="messages_lab_list" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_lab_list_pager" class="scroll" style="text-align:center;"></div><br>
		<input type="button" id="messages_add_lab" value="Add Lab" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_edit_lab" value="Edit Lab" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_delete_lab" value="Delete Lab" class="ui-button ui-state-default ui-corner-all"/><br>
	</div>
	<div id="messages_lab_edit_fields">
		<button type="button" id="messages_lab_save" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
			<span style="float:right;" class="ui-button-text">Save</span>
		</button><button type="button" id="messages_lab_cancel" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
			<span style="float:right;" class="ui-button-text">Cancel</span>
		</button> 
		<div style="float:right;" "id="messages_lab_status"></div>
		<hr class="ui-state-default"/>
		<input type="hidden" name="address_id" id="messages_lab_location_id"/>
		<input type="hidden" name="orders_id" id="messages_lab_orders_id"/>
		<input type="hidden" name="t_messages_id" id="messages_lab_t_messages_id"/>
		<div id="messages_lab_accordion">
			<h3><a href="#">Lab Tests</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_labs" id="messages_lab_orders" rows="3" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"></textarea></td>
						<td valign="top"><br><input type="button" id="messages_lab_orders_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
					<tr>
						<td valign="top">Search Lab Tests:<br><input type="text" name="messages_lab_search" id="messages_lab_search" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_select_lab1" value="Select" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_select_lab2" value="Add/Edit" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
				</table>
				<div id="messages_lab_edit" style="display:none"><hr class="ui-state-default"/>
					<table>
						<tr>
							<td valign="top">Test:<br><input type="text" name="messages_lab" id="messages_lab" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td valign="top"><br>
								<button type="button" id="messages_add_lab2" style="font-size: 0.8em" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
									<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
									<span style="float:right;" class="ui-button-text">Add and Save Template</span>
								</button><br> 
								<button type="button" id="messages_add_lab1" style="font-size: 0.8em" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
									<span style="float:right;" class="ui-button-text">Order Only</span>
								</button>
							</td>
						</tr>
						<tr>
							<td>CPT Code:<br><input type="text" name="messages_lab_cpt"  id="messages_lab_cpt" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>	
							<td>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<h3><a href="#">Diagnosis Codes</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_labs_icd" id="messages_lab_codes" rows="3" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"></textarea></td>	
						<td valign="top"><br>
							<input type="button" id="messages_lab_codes_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/><br>
							<input type="button" id="messages_lab_issues" value="Issues" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/>
						</td>
					</tr>
					<tr>
						<td valign="top">Search ICD:<br><input type="text" name="messages_lab_icd_search" id="messages_lab_icd_search" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_select_lab_icd" value="Select" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
				</table>
			</div>
			<h3><a href="#">Location</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><input type="text" name="messages_lab_location"  id="messages_lab_location" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_lab_location_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
						<td></td>
					</tr>
				</table>
				<table>
					<tr>
						<td valign="top">Search Laboratory Provider:<br><input type="text" name="messages_lab_location_search" id="messages_lab_location_search" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_select_lab_location1" value="Select" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_select_lab_location2" value="Add/Edit" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
				</table>
				<div id="messages_edit_lab_location" style="display:none"><hr class="ui-state-default"/>
				<input type="hidden" name="messages_lab_location_address_id" id="messages_lab_location_address_id"/>
					<table>
						<tr>
							<td colspan="3">Facility:<br><input type="text" name="facility" id="messages_lab_location_facility" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="3">Address:<br><input type="text" name="street_address1" id="messages_lab_location_address" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="3">Address2:<br><input type="text" name="street_address2" id="messages_lab_location_address2" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td>City:<br><input type="text" name="city" id="messages_lab_location_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>State:<br><select name="state" id="messages_lab_location_state" class="text ui-widget-content ui-corner-all"></td>
							<td>Zip:<br><input type="text" name="zip" id="messages_lab_location_zip" style="width:100px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td>Phone:<br><input type="text" name="phone" id="messages_lab_location_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Fax:<br><input type="text" name="fax" id="messages_lab_location_fax" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="3">Comments:<br><input type="text" name="comments" id="messages_lab_location_comments" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="3" valign="top">Provider/Clinic Identity:<br><input type="text" name="ordering_id" id="messages_lab_location_ordering_id" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td valign="top">
								<br><button type="button" id="messages_add_lab_location" style="font-size: 0.8em" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
									<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
									<span style="float:right;" class="ui-button-text">Add and Save Contact to Address Book</span>
								</button>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<h3><a href="#">Insurance</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_insurance"  id="messages_lab_insurance" rows="3" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"/></textarea></td>
						<td valign="top"><br>
							<input type="button" id="messages_lab_insurance_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/><br>
							<input type="button" id="messages_lab_insurance_client" value="Bill Client" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/>
						</td>
					</tr>
				</table><br>
				<table id="messages_lab_insurance_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="messages_lab_insurance_pager" class="scroll" style="text-align:center;"></div><br>
			</div>
			<h3><a href="#">Obtained Specimens</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_labs_obtained"  id="messages_lab_obtained" rows="3" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"/></textarea></td>
						<td valign="top"><br>
							<input type="button" id="messages_lab_obtained_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/><br>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td>Fasting:</td>
						<td>Yes <input type="radio" id="messages_lab_fasting_y" name="messages_lab_fasting" value="Yes"/></td>
						<td>No <input type="radio" id="messages_lab_fasting_n" name="messages_lab_fasting" value="No"/></td>
						<td>
							<button type="button" id="messages_lab_obtained_import" style="font-size: 0.8em" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
								<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
								<span style="float:right;" class="ui-button-text">Import</span>
							</button>
						</td>
					</tr>
					<tr>
						<td>Date Obtained:</td>
						<td colspan="2"><input type="text" id="messages_lab_date_obtained" name="messages_lab_date_obtained" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Time Obtained:</td>
						<td colspan="2"><input type="text" id="messages_lab_time_obtained" name="messages_lab_time_obtained" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Body Location Obtained:</td>
						<td colspan="2"><input type="text" id="messages_lab_location_obtained" name="messages_lab_location_obtained" style="width:336px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Time of last medication dosage:</td>
						<td colspan="2"><input type="text" id="messages_lab_medication_obtained" name="messages_lab_medication_obtained" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
				</table><br>		
			</div>
		</div>
	</div>
	<div id="messages_lab_action_fieldset" style="display:none">
		<fieldset class="ui-state-default ui-corner-all">
		<legend>Action</legend>
		<div id="messages_lab_choice"></div><br><br>
		<input type="button" id="messages_print_lab" value="Print" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_electronic_lab" value="Electronic" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_fax_lab" value="Fax" class="ui-button ui-state-default ui-corner-all"/>
		<input type="button" id="messages_done_lab" value="Done" class="ui-button ui-state-default ui-corner-all"/> 
	</div>
	</form>
</div>
<script type="text/javascript">
	$("#messages_lab_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function(){
			 $("#messages_lab_accordion").accordion({ autoHeight: false });
			 $("#messages_lab_grid").show('fast');
			 $("#messages_lab_edit_fields").hide('fast');
			 jQuery("#messages_lab_list").jqGrid('GridUnload');
			 jQuery("#messages_lab_list").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/labs_list');?>",
				postData: {t_messages_id: function(){return $("#messages_lab_t_messages_id").val();}},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Tests','Diagnosis','Location1','Location','Obtained','Insurance'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_labs',index:'orders_labs',width:300},
					{name:'orders_labs_icd',index:'orders_labs_icd',width:200},
					{name:'address_id',index:'address_id',hidden:true},
					{name:'displayname',index:'displayname',width:100},
					{name:'orders_labs_obtained',index:'orders_labs_obtained',width:1,hidden:true},
					{name:'orders_insurance',index:'orders_insurance',hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_lab_list_pager'),
				sortname: 'orders_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Lab Orders - Click on Location to get full description",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" },
			 	onCellSelect: function(rowid,iCol,cellcontent,e){
			 		if (iCol == 'address_id') {
				 		$.ajax({
							url: "<?php echo site_url('provider/chartmenu/addressdefine');?>",
							type: "POST",
							data: "address_id=" + cellcontent,
							dataType: 'json',
							success: function(data){
								$.jGrowl(data.item, {sticky:true});				
							}
						});
			 		}		
				}
			}).navGrid('#messages_lab_list_pager',{search:false,edit:false,add:false,del:false});
			 jQuery("#messages_lab_insurance_grid").jqGrid('GridUnload');
			 jQuery("#messages_lab_insurance_grid").jqGrid({
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
				pager: jQuery('#messages_lab_insurance_pager'),
				sortname: 'insurance_order',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Insurance Payors - Double click to select insurance for lab order",
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
			}).navGrid('#messages_lab_insurance_pager',{search:false,edit:false,add:false,del:false});
		},
		beforeclose: function(event, ui) {
			var a = $("#messages_lab_orders").val();
			if(a != ''){
				if(confirm('The form fields are not empty.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
					$('#edit_message_lab_form').clearForm();
					$("#messages_lab").val('');
					$("#messages_lab_cpt").val('');
					$("#messages_lab_icd_search").val('');
					$("#edit_message_lab_location_form").clearForm();
					$("#messages_lab_location_search").val('');
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
	
	$("#messages_add_lab").click(function(){
		$('#edit_message_lab_form').clearForm();
		var id = $("#t_messages_id").val();
		$("#messages_lab_t_messages_id").val(id);
		$("#messages_lab_status").html('');
		$("#messages_lab_edit_fields").show('fast');
		$("#messages_lab_accordion").accordion("activate", 0);
		$("#messages_lab_grid").hide('fast');
	});
	$("#messages_edit_lab").click(function(){
		var item = jQuery("#messages_lab_list").getGridParam('selrow');
		if(item){
			jQuery("#messages_lab_list").GridToForm(item,"#edit_message_lab_form");
			var address_id = $("#messages_lab_location_id").val();
			$.ajax({
				url: "<?php echo site_url('provider/chartmenu/addressdefine');?>",
				type: "POST",
				data: "address_id=" + address_id,
				dataType: 'json',
				success: function(data){
					$("#messages_lab_location").val(data.item);
				}
			});
			var status = 'Details for Lab Order #' + item;
			$("#messages_lab_status").html(status);
			$("#messages_lab_edit_fields").show('fast');
			$("#messages_lab_accordion").accordion("activate", 0);
			$("#messages_lab_grid").hide('fast');
		} else {
			$.jGrowl("Please select lab to edit!");
		}
	});
	$("#messages_delete_lab").click(function(){
		var item = jQuery("#messages_lab_list").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "<?php echo site_url('provider/chartmenu/delete_lab');?>",
				type: "POST",
				data: "orders_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#messages_lab_list").trigger("reloadGrid");			
				}
			});
		} else {
			$.jGrowl("Please select lab to delete!");
		}
	});
	$("#messages_lab_orders_clear").click(function(){
		$("#messages_lab_orders").val('');
	});
	$("#messages_lab_codes_clear").click(function(){
		$("#messages_lab_codes").val('');
	});
	$("#messages_lab_location_clear").click(function(){
		$("#messages_lab_location").val('');
	});
	$("#messages_lab_insurance_clear").click(function(){
		$("#messages_lab_insurance").val('');
	});
	$("#messages_lab_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/lab');?>",
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
	$("#messages_select_lab1").click(function(){
		var a = $("#messages_lab_search").val();
		var pos = a.indexOf('[');
		if (pos == -1) {
			$.jGrowl("Please enter test!");
		} else {
			var pos1 = pos - 1;
			var lab = a.slice(0, pos1);
			var old = $("#messages_lab_orders").val();
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
			$("#messages_lab_orders").val(old1+lab);
			$("#messages_lab_search").val('');
		}
	});
	$("#messages_select_lab2").click(function(){
		var a = $("#messages_lab_search").val();
		var pos = a.indexOf('[');
		if (pos == -1) {
			$("#messages_lab").val(a);
			$("#messages_lab_search").val('');
			$("#messages_lab").focus();
			$("#messages_lab_edit").show('fast');
		} else {
			var id1 = a.slice(pos);
			var id2 = id1.replace("[", "");
			var id = id2.replace("]", "");
			if(id){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/order1');?>",
					data: "orderslist_id=" + id,
					dataType: "json",
					success: function(data){
						$("#messages_lab").val(data.orders_description);
						$("#messages_lab_id").val(data.orderslist_id);
						$("#messages_lab_cpt").val(data.cpt);
						$("#messages_lab_search").val('');
						$("#messages_lab").focus();
						$("#messages_lab_edit").show('fast');
					}
				});
			} else {
				$.jGrowl("Please enter test to edit!");
			}
		}	
	});
	$("#messages_add_lab1").click(function(){
		var a = $("#messages_lab").val();
		var b = $("#messages_lab_cpt").val();
		var old = $("#messages_lab_orders").val();
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
		if(b){
			if(a){
				var a1 = a + ', CPT ' + b;	
				$("#messages_lab_orders").val(old1+a1);
				$("#messages_lab").val('');
				$("#messages_lab_cpt").val('');
			} else {
				$.jGrowl("Test field is empty!");
			}
		} else {
			if(a){
				var a1 = a;	
				$("#messages_lab_orders").val(old1+a1);
				$("#messages_lab").val('');
				$("#messages_lab_cpt").val('');
			} else {
				$.jGrowl("Test field is empty!");
			}
		}
	});
	$("#messages_add_lab2").click(function(){
		var a = $("#messages_lab").val();
		var b = $("#messages_lab_cpt").val();
		var c = a + b;
		if(c != ''){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/add_orderslist');?>",
				data: "orders_description=" + a + "&cpt=" + b + "&orders_category=Laboratory",
				success: function(data){
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Fields are empty!");
		}
		var old = $("#messages_lab_orders").val();
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
		if(b){
			if(a){
				var a1 = a + ', CPT ' + b;	
				$("#messages_lab_orders").val(old1+a1);
				$("#messages_lab").val('');
				$("#messages_lab_cpt").val('');
			} else {
				$.jGrowl("Test field is empty!");
			}
		} else {
			if(a){
				var a1 = a;	
				$("#messages_lab_orders").val(old1+a1);
				$("#messages_lab").val('');
				$("#messages_lab_cpt").val('');
			} else {
				$.jGrowl("Test field is empty!");
			}
		}
	});
	$("#messages_lab_icd_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/icd9');?>",
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
	$('#messages_lab_icd_search').bt('Use a comma to separate distinct search terms!',{width: 200});
	$("#messages_select_lab_icd").click(function(){
		var item = $("#messages_lab_icd_search").val();
		var old = $("#messages_lab_codes").val();
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
		if(item){
			$("#messages_lab_codes").val(old1+item);
			$("#messages_lab_icd_search").val('');
		} else {
			$.jGrowl("Field is empty!");
		}
	});
	$("#messages_lab_issues").click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').hide('fast');
		$('#issues_psh_header').hide('fast');
		$('#issues_lab_header').show('fast');
		$('#issues_rad_header').hide('fast');
		$('#issues_cp_header').hide('fast');
		$('#issues_ref_header').hide('fast');
		$('#issues_assessment_header').hide('fast');
		$('#edit_issue_form').hide('fast');
	});	
	$("#messages_lab_location_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/lab_provider');?>",
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
	$("#messages_lab_location_city").autocomplete({
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
		minLength: 3,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
	});
	$("#messages_select_lab_location1").click(function (){
		var item = $("#messages_lab_location_search").val();
		var pos = item.indexOf('(');
		if (pos == -1) {
			$.jGrowl("Please enter laboratory provider!");
		} else {
			var id1 = item.slice(pos);
			var id2 = id1.replace("(", "");
			var id = id2.replace(")", "");
			if(id){
				$("#messages_lab_location").val(item);
				$("#messages_lab_location_id").val(id);
			} else {
				$.jGrowl("Please enter laboratory provider!");
			}       
		}
	});
	$("#messages_select_lab_location2").click(function (){
		var item = $("#messages_lab_location_search").val();
		var pos = item.indexOf('(');
		if (pos == -1) {
			$("#messages_lab_location_facility").val(item);
			$("#messages_lab_location_facility").focus();
			$("#messages_edit_lab_location").show('fast');
		} else {
			var id1 = item.slice(pos);
			var id2 = id1.replace("(", "");
			var id = id2.replace(")", "");
			if(id){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/order_provider1');?>",
					data: "address_id=" + id,
					dataType: "json",
					success: function(data){
						$("#messages_lab_location_facility").val(data.facility);
						$("#messages_lab_location_address").val(data.street_address1);
						$("#messages_lab_location_address2").val(data.street_address2);
						$("#messages_lab_location_city").val(data.city);
						$("#messages_lab_location_state").val(data.state);
						$("#messages_lab_location_zip").val(data.zip);
						$("#messages_lab_location_phone").val(data.phone);
						$("#messages_lab_location_fax").val(data.fax);
						$("#messages_lab_location_address_id").val(data.address_id);
						$("#messages_lab_location_search").val('');
						$("#messages_lab_location_facility").focus();
						$("#messages_edit_lab_location").show('fast');
					}
				});
			} else {
				$.jGrowl("Please enter laboratory provider!");
			}       
		}
	});
	$("#messages_lab_location_state").addOption({"AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#messages_lab_location_phone").mask("(999) 999-9999");
	$("#messages_lab_location_fax").mask("(999) 999-9999");
	$("#messages_add_lab_location").click(function(){
		var facility = $("#messages_lab_location_facility");
		var bValid = true;
		bValid = bValid && checkEmpty(facility,"Facility");
		if (bValid) {
			var a = $("#messages_lab_location_facility").val();
			var b = $("#messages_lab_location_address").val();
			var c = $("#messages_lab_location_address2").val();
			var d = $("#messages_lab_location_city").val();
			var e = $("#messages_lab_location_state").val();
			var f = $("#messages_lab_location_zip").val();
			var g = $("#messages_lab_location_phone").val();
			var h = $("#messages_lab_location_fax").val();
			var i = $("#messages_lab_location_address_id").val();
			var j = $("#messages_lab_location_comments").val();
			var k = $("#messages_lab_location_ordering_id").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/edit_lab_provider');?>",
				data: "facility=" + a + "&street_address1=" + b + "&street_address2=" + c + "&city=" + d + "&state=" + e + "&zip=" + f + "&phone=" + g + "&fax=" + h + "&address_id=" + i + "&comments=" + j + "&ordering_id=" + k,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					$("#messages_lab_location").val(data.item);
					$("#messages_lab_location_id").val(data.id);
					$("#messages_lab_location_facility").val('');
					$("#messages_lab_location_address").val('');
					$("#messages_lab_location_address2").val('');
					$("#messages_lab_location_city").val('');
					$("#messages_lab_location_state").val('');
					$("#messages_lab_location_zip").val('');
					$("#messages_lab_location_phone").val('');
					$("#messages_lab_location_fax").val('');
					$("#messages_lab_location_address_id").val('');
					$("#messages_lab_location_comments").val('');
					$("#messages_lab_location_ordering_id").val('');
					$("#messages_edit_lab_location").hide('fast');
				}
			});
		}
	});
	$("#messages_lab_insurance_client").click(function(){
		var text = "Bill Client";
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
	});
	
	$("#messages_lab_save").click(function(){
		var order = $("#messages_lab_orders");
		var codes = $("#messages_lab_codes");
		var location = $("#messages_lab_location");
		var insurance = $("#messages_lab_insurance")
		var bValid = true;
		bValid = bValid && checkEmpty(order,"Tests");
		bValid = bValid && checkEmpty(codes,"Diagnosis Codes");
		bValid = bValid && checkEmpty(location,"Laboratory Provider");
		bValid = bValid && checkEmpty(insurance,"Insurance");
		if (bValid) {
			var a = $("#messages_lab_orders").val();
			var b = $("#messages_lab_codes").val();
			var c = $("#messages_lab_location_id").val();
			var d = $("#messages_lab_t_messages_id").val();
			var e = $("#messages_lab_orders_id").val();
			var f = $("#messages_lab_insurance").val();
			var g = $("#messages_lab_obtained").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/add_lab_order');?>",
				data: "orders_labs=" + a + "&orders_labs_icd=" + b + "&address_id=" + c + "&t_messages_id=" + d + "&orders_id=" + e + "&orders_insurance=" + f + "&orders_labs_obtained=" + g,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					$("#messages_lab_orders_id").val(data.id);
					$('#messages_lab_choice').html(data.choice);
					$("#messages_lab_action_fieldset").show('fast');
					$("#messages_lab_edit_fields").hide('fast');
					//$("#messages_edit_lab").hide('fast');
					$("#messages_edit_lab_location").hide('fast');
					jQuery("#alerts").trigger("reloadGrid");
					jQuery("messages_lab_list").trigger("reloadGrid");
				}
			});
		}
	});
	$("#messages_lab_cancel").click(function(){
		$("#messages_lab_edit_fields").hide('fast');
		$("#messages_lab_action_fieldset").hide('fast');
		$("#messages_lab_grid").show('fast');
		jQuery("#messages_lab_list").trigger("reloadGrid");
	});
	$("#save_lab_helper").click(function(){
		var origin = $("#messages_lab_origin").val();
		if (origin == 'message') {
			var id = $("#t_messages_id").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/import_lab1');?>",
				data: "t_messages_id=" + id,
				success: function(data){
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
					if(data != ''){
						$("#t_messages_message").val(old1+data);	
					}
				}
			});
		} else {
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
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/tip_orders/labs');?>",
						success: function(data){
							$('#orders_labs_tip').html(data);
						}
					});
				}
			});
		}
		$('#edit_message_lab_form').clearForm();
		$("#messages_lab").val('');
		$("#messages_lab_origin").val('');
		$("#messages_lab_cpt").val('');
		$("#messages_lab_icd_search").val('');
		$("#edit_message_lab_location_form").clearForm();
		$("#messages_lab_location_search").val('');
		$("#messages_lab_edit_fields").hide('fast');
		$("#messages_lab_action_fieldset").hide('fast');
		//$("#messages_edit_lab").hide('fast');
		$("#messages_edit_lab_location").hide('fast');
		$("#edit_message_lab_form").show('fast');
		$("#messages_lab_dialog").dialog('close');
	});
	$("#cancel_lab_helper").click(function(){
		$('#edit_message_lab_form').clearForm();
		$("#messages_lab").val('');
		$("#messages_lab_origin").val('');
		$("#messages_lab_cpt").val('');
		$("#messages_lab_icd_search").val('');
		$("#edit_message_lab_location_form").clearForm();
		$("#messages_lab_location_search").val('');
		$("#messages_lab_edit_fields").hide('fast');
		$("#messages_lab_action_fieldset").hide('fast');
		//$("#messages_edit_lab").hide('fast');
		$("#messages_edit_lab_location").hide('fast');
		$("#edit_message_lab_form").show('fast');
		$("#messages_lab_dialog").dialog('close');
	});
	$("#messages_print_lab").click(function(){
		var lab = $("#messages_lab_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(lab,"Lab Order");
		if (bValid) {
			var order_id = $("#messages_lab_orders_id").val();
			window.open("<?php echo site_url('provider/chartmenu/print_orders');?>/" + order_id);
		}
	});
	$("#messages_electronic_lab").click(function(){
		$.jGrowl('Future feature!');
	});
	$("#messages_fax_lab").click(function(){
		var lab = $("#messages_lab_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(lab,"Lab Order");
		if (bValid) {
			var order_id = $("#messages_lab_orders_id").val();
			if(order_id){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/fax_orders');?>",
					data: "orders_id=" + order_id,
					success: function(data){
						$.jGrowl(data);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#messages_done_lab").click(function(){
		$("#messages_lab_action_fieldset").hide('fast');
		$("#messages_lab_grid").show('fast');
		jQuery("#messages_lab_list").trigger("reloadGrid");
	});
	
	$("#messages_lab_obtained_clear").click(function(){
		$("#messages_lab_obtained").val('');
	});
	var currentDate = getCurrentDate();
	var currentTime = getCurrentTime();
	$("#messages_lab_date_obtained").val(currentDate);
	$("#messages_lab_date_obtained").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$('#messages_lab_time_obtained').timeEntry({spinnerImage: '<?php echo base_url()."images/spinnerDefault.png";?>',ampmPrefix: ' '});
	$('#messages_lab_medication_obtained').timeEntry({spinnerImage: '<?php echo base_url()."images/spinnerDefault.png";?>',ampmPrefix: ' '});
	$("#messages_lab_obtained_import").click(function(){
		var a1 = $("#messages_lab_date_obtained");
		var b1 = $("#messages_lab_time_obtained");
		var bValid = true;
		bValid = bValid && checkEmpty(a1,"Date Obtained");
		bValid = bValid && checkEmpty(b1,"Time Obtained");
		if (bValid) {
			var item = '';
			var a = $("input:radio[name=messages_lab_fasting]:checked").val();
			if(a){
				item += 'Fasting: ' + $("input:radio[name=messages_lab_fasting]:checked").val() + '\n';
			}
			item += 'Date/Time specimen obtained: ' + $("#messages_lab_date_obtained").val() + ', ' + $("#messages_lab_time_obtained").val() + '\n';
			var b = $("#messages_lab_location_obtained").val();
			if(b != ''){
				item += 'Body location of specimen: ' + $("#messages_lab_location_obtained").val() + '\n';
			}
			var c = $("#messages_lab_medication_obtained").val();
			if(c != ''){
				item += 'Time of last dosage of medication: ' + $("#messages_lab_medication_obtained").val() + '\n';
			}
			var old = $("#messages_lab_obtained").val();
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
			$("#messages_lab_obtained").val(old1+item);
			$("#messages_lab_fasting_y").attr({'checked': false});
			$("#messages_lab_fasting_n").attr({'checked': false});
			$("#messages_lab_date_obtained").val(currentDate);
			$("#messages_lab_time_obtained").val(currentTime);
			$("#messages_lab_location_obtained").val('');
			$("#messages_lab_medication_obtained").val('');
		}
	});
</script>
