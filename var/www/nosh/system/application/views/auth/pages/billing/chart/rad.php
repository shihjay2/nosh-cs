<div id="messages_rad_dialog" title="Imaging Helper">
	<input type="hidden" name="messages_rad_origin" id="messages_rad_origin"/>
	<form name="edit_message_rad_form" id="edit_message_rad_form">
	<div id="messages_rad_grid">
		<button type="button" id="save_rad_helper" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
			<span style="float:right;" class="ui-button-text"><div id="save_rad_helper_label"></div></span>
		</button><button type="button" id="cancel_rad_helper" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
			<span style="float:right;" class="ui-button-text">Cancel</span>
		</button> 
		<hr class="ui-state-default"/>
		<table id="messages_rad_list" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_rad_list_pager" class="scroll" style="text-align:center;"></div><br>
		<input type="button" id="messages_add_rad" value="Add imaging" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_edit_rad" value="Edit imaging" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_delete_rad" value="Delete imaging" class="ui-button ui-state-default ui-corner-all"/><br>
	</div>
	<div id="messages_rad_edit_fields">
		<button type="button" id="messages_rad_save" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
			<span style="float:right;" class="ui-button-text">Save</span>
		</button><button type="button" id="messages_rad_cancel" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
			<span style="float:right;" class="ui-button-text">Cancel</span>
		</button> 
		<div style="float:right;" "id="messages_rad_status"></div>
		<hr class="ui-state-default"/>
		<input type="hidden" name="address_id" id="messages_rad_location_id"/>
		<input type="hidden" name="orders_id" id="messages_rad_orders_id"/>
		<input type="hidden" name="t_messages_id" id="messages_rad_t_messages_id"/>
		<div id="messages_rad_accordion">
			<h3><a href="#">Imaging Tests</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_radiology" id="messages_rad_orders" rows="3" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"></textarea></td>
						<td valign="top"><br><input type="button" id="messages_rad_orders_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
					<tr>
						<td valign="top">Search Imaging Tests:<br><input type="text" name="messages_rad_search" id="messages_rad_search" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_select_rad1" value="Select" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_select_rad2" value="Add/Edit" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
				</table>
				<div id="messages_rad_edit" style="display:none"><hr class="ui-state-default"/>
					<table>
						<tr>
							<td valign="top">Test:<br><input type="text" name="messages_rad" id="messages_rad" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td valign="top"><br>
								<button type="button" id="messages_add_rad2" style="font-size: 0.8em" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
									<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
									<span style="float:right;" class="ui-button-text">Add and Save Template</span>
								</button><br> 
								<button type="button" id="messages_add_rad1" style="font-size: 0.8em" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
									<span style="float:right;" class="ui-button-text">Order Only</span>
								</button>
							</td>
						</tr>
						<tr>
							<td>CPT Code:<br><input type="text" name="messages_rad_cpt"  id="messages_rad_cpt" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>	
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
						<td valign="top">Preview:<br><textarea name="orders_radiology_icd" id="messages_rad_codes" rows="3" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"></textarea></td>	
						<td valign="top"><br>
							<input type="button" id="messages_rad_codes_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/><br>
							<input type="button" id="messages_rad_issues" value="Issues" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/>
						</td>
					</tr>
					<tr>
						<td valign="top">Search ICD:<br><input type="text" name="messages_rad_icd_search" id="messages_rad_icd_search" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_select_rad_icd" value="Select" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
				</table>
			</div>
			<h3><a href="#">Location</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><input type="text" name="messages_rad_location"  id="messages_rad_location" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_rad_location_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
						<td></td>
					</tr>
				</table>
				<table>
					<tr>
						<td valign="top">Search Imaging Provider:<br><input type="text" name="messages_rad_location_search" id="messages_rad_location_search" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_select_rad_location1" value="Select" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_select_rad_location2" value="Add/Edit" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
				</table>
				<div id="messages_edit_rad_location" style="display:none"><hr class="ui-state-default"/>
				<input type="hidden" name="messages_rad_location_address_id" id="messages_rad_location_address_id"/>
					<table>
						<tr>
							<td colspan="3">Facility:<br><input type="text" name="facility" id="messages_rad_location_facility" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="3">Address:<br><input type="text" name="street_address1" id="messages_rad_location_address" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="3">Address2:<br><input type="text" name="street_address2" id="messages_rad_location_address2" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td>City:<br><input type="text" name="city" id="messages_rad_location_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>State:<br><select name="state" id="messages_rad_location_state" class="text ui-widget-content ui-corner-all"></td>
							<td>Zip:<br><input type="text" name="zip" id="messages_rad_location_zip" style="width:100px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td>Phone:<br><input type="text" name="phone" id="messages_rad_location_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Fax:<br><input type="text" name="fax" id="messages_rad_location_fax" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="3">Comments:<br><input type="text" name="comments" id="messages_rad_location_comments" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="3" valign="top">Provider/Clinic Identity:<br><input type="text" name="ordering_id" id="messages_rad_location_ordering_id" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
							<td valign="top">
								<br><button type="button" id="messages_add_rad_location" style="font-size: 0.8em" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
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
						<td valign="top">Preview:<br><textarea name="orders_insurance"  id="messages_rad_insurance" rows="3" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"/></textarea></td>
						<td valign="top"><br>
							<input type="button" id="messages_rad_insurance_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/><br>
							<input type="button" id="messages_rad_insurance_client" value="Bill Client" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/>
						</td>
					</tr>
				</table><br>
				<table id="messages_rad_insurance_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="messages_rad_insurance_pager" class="scroll" style="text-align:center;"></div><br>
			</div>
		</div>
	</div>
	<div id="messages_rad_action_fieldset" style="display:none">
		<fieldset class="ui-state-default ui-corner-all">
		<legend>Action</legend>
		<div id="messages_rad_choice"></div><br><br>
		<input type="button" id="messages_print_rad" value="Print" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_electronic_rad" value="Electronic" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_fax_rad" value="Fax" class="ui-button ui-state-default ui-corner-all"/>
		<input type="button" id="messages_done_rad" value="Done" class="ui-button ui-state-default ui-corner-all"/> 
	</div>
	</form>
</div>
<script type="text/javascript">
	$("#messages_rad_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function(){
			 $("#messages_rad_accordion").accordion({ autoHeight: false });
			 $("#messages_rad_grid").show('fast');
			 $("#messages_rad_edit_fields").hide('fast');
			 jQuery("#messages_rad_list").jqGrid('GridUnload');
			 jQuery("#messages_rad_list").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/rad_list');?>",
				postData: {t_messages_id: function(){return $("#messages_rad_t_messages_id").val();}},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Tests','Diagnosis','Location1','Location','Insurance'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_radiology',index:'orders_radiology',width:300},
					{name:'orders_radiology_icd',index:'orders_radiology_icd',width:200},
					{name:'address_id',index:'address_id',hidden:true},
					{name:'displayname',index:'displayname',width:100},
					{name:'orders_insurance',index:'orders_insurance',hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_rad_list_pager'),
				sortname: 'orders_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Imaging Orders - Click on Location to get full description",
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
			}).navGrid('#messages_rad_list_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#messages_rad_insurance_grid").jqGrid('GridUnload');
			jQuery("#messages_rad_insurance_grid").jqGrid({
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
				pager: jQuery('#messages_rad_insurance_pager'),
				sortname: 'insurance_order',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Insurance Payors - Double click to select insurance for imaging order",
			 	height: "100%",
			 	ondblClickRow: function(id){
			 		var insurance_plan_name = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_plan_name');
					var insurance_id_num = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_id_num');
					var insurance_group = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_group');
					var insurance_insu_lastname = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_insu_lastname');
					var insurance_insu_firstname = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_insu_firstname');
					var text = insurance_plan_name + '; ID: ' + insurance_id_num;
					if(insurance_group != ''){
						text += "; Group: " + insurance_group;
					}
					text += "; " + insurance_insu_lastname + ", " + insurance_insu_firstname;
					var old = $("#messages_rad_insurance").val();
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
					$("#messages_rad_insurance").val(old1+text);
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_rad_insurance_pager',{search:false,edit:false,add:false,del:false});
		},
		beforeclose: function(event, ui) {
			var a = $("#messages_rad_orders").val();
			if(a != ''){
				if(confirm('The form fields are not empty.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
					$('#edit_message_rad_form').clearForm();
					$("#messages_rad").val('');
					$("#messages_rad_cpt").val('');
					$("#messages_rad_icd_search").val('');
					$("#edit_message_rad_location_form").clearForm();
					$("#messages_rad_location_search").val('');
					$("#messages_rad_action_fieldset").hide('fast');
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	
	$("#messages_add_rad").click(function(){
		$('#edit_message_rad_form').clearForm();
		var id = $("#t_messages_id").val();
		$("#messages_rad_t_messages_id").val(id);
		$("#messages_rad_status").html('');
		$("#messages_rad_edit_fields").show('fast');
		$("#messages_rad_accordion").accordion("activate", 0);
		$("#messages_rad_grid").hide('fast');
	});
	$("#messages_edit_rad").click(function(){
		var item = jQuery("#messages_rad_list").getGridParam('selrow');
		if(item){
			jQuery("#messages_rad_list").GridToForm(item,"#edit_message_rad_form");
			var address_id = $("#messages_rad_location_id").val();
			$.ajax({
				url: "<?php echo site_url('provider/chartmenu/addressdefine');?>",
				type: "POST",
				data: "address_id=" + address_id,
				dataType: 'json',
				success: function(data){
					$("#messages_rad_location").val(data.item);
				}
			});
			var status = 'Details for Radiology Order #' + item;
			$("#messages_rad_status").html(status);
			$("#messages_rad_edit_fields").show('fast');
			$("#messages_rad_accordion").accordion("activate", 0);
			$("#messages_rad_grid").hide('fast');
		} else {
			$.jGrowl("Please select rad to edit!");
		}
	});var i = $("#messages_rad_location_address_id").val();
	$("#messages_delete_rad").click(function(){
		var item = jQuery("#messages_rad_list").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "<?php echo site_url('provider/chartmenu/delete_rad');?>",
				type: "POST",
				data: "orders_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#messages_rad_list").trigger("reloadGrid");			
				}
			});
		} else {
			$.jGrowl("Please select rad to delete!");
		}
	});
	$("#messages_rad_orders_clear").click(function(){
		$("#messages_rad_orders").val('');
	});
	$("#messages_rad_codes_clear").click(function(){
		$("#messages_rad_codes").val('');
	});
	$("#messages_rad_location_clear").click(function(){
		$("#messages_rad_location").val('');
	});
	$("#messages_rad_insurance_clear").click(function(){
		$("#messages_rad_insurance").val('');
	});
	$("#messages_rad_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rad');?>",
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
	$("#messages_select_rad1").click(function(){
		var a = $("#messages_rad_search").val();
		var pos = a.indexOf('[');
		if (pos == -1) {
			$.jGrowl("Please enter test!");
		} else {
			var pos1 = pos - 1;
			var rad = a.slice(0, pos1);
			var old = $("#messages_rad_orders").val();
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
			$("#messages_rad_orders").val(old1+rad);
			$("#messages_rad_search").val('');
		}
	});
	$("#messages_select_rad2").click(function(){
		var a = $("#messages_rad_search").val();
		var pos = a.indexOf('[');
		if (pos == -1) {
			$("#messages_rad").val(a);
			$("#messages_rad_search").val('');
			$("#messages_rad").focus();
			$("#messages_rad_edit").show('fast');
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
						$("#messages_rad").val(data.orders_description);
						$("#messages_rad_id").val(data.orderslist_id);
						$("#messages_rad_cpt").val(data.cpt);
						$("#messages_rad_search").val('');
						$("#messages_rad").focus();
						$("#messages_rad_edit").show('fast');
					}
				});
			} else {
				$.jGrowl("Please enter test to edit!");
			}
		}	
	});
	$("#messages_add_rad1").click(function(){
		var a = $("#messages_rad").val();
		var b = $("#messages_rad_cpt").val();
		var old = $("#messages_rad_orders").val();
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
				$("#messages_rad_orders").val(old1+a1);
				$("#messages_rad").val('');
				$("#messages_rad_cpt").val('');
			} else {
				$.jGrowl("Test field is empty!");
			}
		} else {
			if(a){
				var a1 = a;	
				$("#messages_rad_orders").val(old1+a1);
				$("#messages_rad").val('');
				$("#messages_rad_cpt").val('');
			} else {
				$.jGrowl("Test field is empty!");
			}
		}
	});
	$("#messages_add_rad2").click(function(){
		var a = $("#messages_rad").val();
		var b = $("#messages_rad_cpt").val();
		var c = a + b;
		if(c != ''){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/add_orderslist');?>",
				data: "orders_description=" + a + "&cpt=" + b + "&orders_category=Imaging",
				success: function(data){
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Fields are empty!");
		}
		var old = $("#messages_rad_orders").val();
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
				$("#messages_rad_orders").val(old1+a1);
				$("#messages_rad").val('');
				$("#messages_rad_cpt").val('');
			} else {
				$.jGrowl("Test field is empty!");
			}
		} else {
			if(a){
				var a1 = a;	
				$("#messages_rad_orders").val(old1+a1);
				$("#messages_rad").val('');
				$("#messages_rad_cpt").val('');
			} else {
				$.jGrowl("Test field is empty!");
			}
		}
	});
	$("#messages_rad_icd_search").autocomplete({
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
	$('#messages_rad_icd_search').bt('Use a comma to separate distinct search terms!',{width: 200});
	$("#messages_select_rad_icd").click(function(){
		var item = $("#messages_rad_icd_search").val();
		var old = $("#messages_rad_codes").val();
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
			$("#messages_rad_codes").val(old1+item);
			$("#messages_rad_icd_search").val('');
		} else {
			$.jGrowl("Field is empty!");
		}
	});
	$("#messages_rad_issues").click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').hide('fast');
		$('#issues_psh_header').hide('fast');
		$('#issues_rad_header').show('fast');
		$('#issues_lab_header').hide('fast');
		$('#issues_cp_header').hide('fast');
		$('#issues_ref_header').hide('fast');
		$('#issues_assessment_header').hide('fast');
		$('#edit_issue_form').hide('fast');
	});	
	$("#messages_rad_location_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rad_provider');?>",
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
	$("#messages_rad_location_city").autocomplete({
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
	$("#messages_select_rad_location1").click(function (){
		var item = $("#messages_rad_location_search").val();
		var pos = item.indexOf('(');
		if (pos == -1) {
			$.jGrowl("Please enter imaging provider!");
		} else {
			var id1 = item.slice(pos);
			var id2 = id1.replace("(", "");
			var id = id2.replace(")", "");
			if(id){
				$("#messages_rad_location").val(item);
				$("#messages_rad_location_id").val(id);
			} else {
				$.jGrowl("Please enter imaging provider!");
			}       
		}
	});
	$("#messages_select_rad_location2").click(function (){
		var item = $("#messages_rad_location_search").val();
		var pos = item.indexOf('(');
		if (pos == -1) {
			$("#messages_rad_location_facility").val(item);
			$("#messages_rad_location_facility").focus();
			$("#messages_edit_rad_location").show('fast');
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
						$("#messages_rad_location_facility").val(data.facility);
						$("#messages_rad_location_address").val(data.street_address1);
						$("#messages_rad_location_address2").val(data.street_address2);
						$("#messages_rad_location_city").val(data.city);
						$("#messages_rad_location_state").val(data.state);
						$("#messages_rad_location_zip").val(data.zip);
						$("#messages_rad_location_phone").val(data.phone);
						$("#messages_rad_location_fax").val(data.fax);
						$("#messages_rad_location_address_id").val(data.address_id);
						$("#messages_rad_location_search").val('');
						$("#messages_rad_location_facility").focus();
						$("#messages_edit_rad_location").show('fast');
					}
				});
			} else {
				$.jGrowl("Please enter radoratory provider!");
			}       
		}
	});
	$("#messages_rad_location_state").addOption({"AL":"Aradama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#messages_rad_location_phone").mask("(999) 999-9999");
	$("#messages_rad_location_fax").mask("(999) 999-9999");
	$("#messages_add_rad_location").click(function(){
		var facility = $("#messages_rad_location_facility");
		var bValid = true;
		bValid = bValid && checkEmpty(facility,"Facility");
		if (bValid) {
			var a = $("#messages_rad_location_facility").val();
			var b = $("#messages_rad_location_address").val();
			var c = $("#messages_rad_location_address2").val();
			var d = $("#messages_rad_location_city").val();
			var e = $("#messages_rad_location_state").val();
			var f = $("#messages_rad_location_zip").val();
			var g = $("#messages_rad_location_phone").val();
			var h = $("#messages_rad_location_fax").val();
			var i = $("#messages_rad_location_address_id").val();
			var j = $("#messages_rad_location_comments").val();
			var k = $("#messages_rad_location_ordering_id").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/edit_rad_provider');?>",
				data: "facility=" + a + "&street_address1=" + b + "&street_address2=" + c + "&city=" + d + "&state=" + e + "&zip=" + f + "&phone=" + g + "&fax=" + h + "&address_id=" + i + "&comments=" + j + "&ordering_id=" + k,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					$("#messages_rad_location").val(data.item);
					$("#messages_rad_location_id").val(data.id);
					$("#messages_rad_location_facility").val('');
					$("#messages_rad_location_address").val('');
					$("#messages_rad_location_address2").val('');
					$("#messages_rad_location_city").val('');
					$("#messages_rad_location_state").val('');
					$("#messages_rad_location_zip").val('');
					$("#messages_rad_location_phone").val('');
					$("#messages_rad_location_fax").val('');
					$("#messages_rad_location_address_id").val('');
					$("#messages_rad_location_comments").val('');
					$("#messages_rad_location_ordering_id").val('');
					$("#messages_edit_rad_location").hide('fast');
				}
			});
		}
	});
	$("#messages_rad_insurance_client").click(function(){
		var text = "Bill Client";
		var old = $("#messages_rad_insurance").val();
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
		$("#messages_rad_insurance").val(old1+text);
	});
	$("#messages_rad_save").click(function(){
		var order = $("#messages_rad_orders");
		var codes = $("#messages_rad_codes");
		var location = $("#messages_rad_location");
		var insurance = $("#messages_rad_insurance")
		var bValid = true;
		bValid = bValid && checkEmpty(order,"Tests");
		bValid = bValid && checkEmpty(codes,"Diagnosis Codes");
		bValid = bValid && checkEmpty(location,"radoratory Provider");
		bValid = bValid && checkEmpty(insurance,"Insurance");
		if (bValid) {
			var a = $("#messages_rad_orders").val();
			var b = $("#messages_rad_codes").val();
			var c = $("#messages_rad_location_id").val();
			var d = $("#messages_rad_t_messages_id").val();
			var e = $("#messages_rad_orders_id").val();
			var f = $("#messages_rad_insurance").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/add_rad_order');?>",
				data: "orders_radiology=" + a + "&orders_radiology_icd=" + b + "&address_id=" + c + "&t_messages_id=" + d + "&orders_id=" + e + "&orders_insurance=" + f,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					$("#messages_rad_orders_id").val(data.id);
					$('#messages_rad_choice').html(data.choice);
					$("#messages_rad_action_fieldset").show('fast');
					$("#messages_rad_edit_fields").hide('fast');
					$("#messages_edit_rad").hide('fast');
					$("#messages_edit_rad_location").hide('fast');
					jQuery("#alerts").trigger("reloadGrid");
					jQuery("messages_rad_list").trigger("reloadGrid");
				}
			});
		}
	});
	$("#messages_rad_cancel").click(function(){
		$("#messages_rad_edit_fields").hide('fast');
		$("#messages_rad_action_fieldset").hide('fast');
		$("#messages_rad_grid").show('fast');
		jQuery("#messages_rad_list").trigger("reloadGrid");
	});
	$("#save_rad_helper").click(function(){
		var origin = $("#messages_rad_origin").val();
		if (origin == 'message') {
			var id = $("#t_messages_id").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/import_rad1');?>",
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
						url: "<?php echo site_url('provider/encounters/tip_orders/rad');?>",
						success: function(data){
							$('#orders_rad_tip').html(data);
						}
					});
				}
			});
		}
		$('#edit_message_rad_form').clearForm();
		$("#messages_rad").val('');
		$("#messages_rad_origin").val('');
		$("#messages_rad_cpt").val('');
		$("#messages_rad_icd_search").val('');
		$("#edit_message_rad_location_form").clearForm();
		$("#messages_rad_location_search").val('');
		$("#messages_rad_edit_fields").hide('fast');
		$("#messages_rad_action_fieldset").hide('fast');
		$("#messages_edit_rad").hide('fast');
		$("#messages_edit_rad_location").hide('fast');
		$("#edit_message_rad_form").show('fast');
		$("#messages_rad_dialog").dialog('close');
	});
	$("#cancel_rad_helper").click(function(){
		$('#edit_message_rad_form').clearForm();
		$("#messages_rad").val('');
		$("#messages_rad_origin").val('');
		$("#messages_rad_cpt").val('');
		$("#messages_rad_icd_search").val('');
		$("#edit_message_rad_location_form").clearForm();
		$("#messages_rad_location_search").val('');
		$("#messages_rad_edit_fields").hide('fast');
		$("#messages_rad_action_fieldset").hide('fast');
		$("#messages_edit_rad").hide('fast');
		$("#messages_edit_rad_location").hide('fast');
		$("#edit_message_rad_form").show('fast');
		$("#messages_rad_dialog").dialog('close');
	});
	$("#messages_print_rad").click(function(){
		var rad = $("#messages_rad_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(rad,"Radiology Order");
		if (bValid) {
			var order_id = $("#messages_rad_orders_id").val();
			window.open("<?php echo site_url('provider/chartmenu/print_orders');?>/" + order_id);
		}
	});
	$("#messages_electronic_rad").click(function(){
		$.jGrowl('Future feature!');
	});
	$("#messages_fax_rad").click(function(){
		var rad = $("#messages_rad_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(rad,"Radiology Order");
		if (bValid) {
			var order_id = $("#messages_rad_orders_id").val();
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
	$("#messages_done_rad").click(function(){
		$("#messages_rad_action_fieldset").hide('fast');
		$("#messages_rad_grid").show('fast');
		jQuery("#messages_rad_list").trigger("reloadGrid");
	});
</script>
