<div id="messages_rad_dialog" title="Imaging Helper">
	<input type="hidden" name="messages_rad_origin" id="messages_rad_origin"/>
	<form name="edit_message_rad_form" id="edit_message_rad_form">
	<div id="messages_rad_grid">
		<button type="button" id="save_rad_helper"><div id="save_rad_helper_label"></div></button>
		<button type="button" id="cancel_rad_helper">Cancel</button> 
		<hr class="ui-state-default"/>
		<table id="messages_rad_list" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_rad_list_pager" class="scroll" style="text-align:center;"></div><br>
		<input type="button" id="messages_add_rad" value="Add" class="messages_rad_button"/> 
		<input type="button" id="messages_edit_rad" value="Edit" class="messages_rad_button"/> 
		<input type="button" id="messages_resend_rad" value="Resend" class="messages_rad_button"/> 
		<input type="button" id="messages_delete_rad" value="Delete" class="messages_rad_button"/><br><br>
	</div>
	<div id="messages_rad_edit_fields">
		<button type="button" id="messages_rad_save">Save</button>
		<button type="button" id="messages_rad_cancel">Cancel</button>
		Provider: <select id ="messages_rad_provider_list" name="id" class="text ui-widget-content ui-corner-all"></select>
		<div style="float:right;" id="messages_rad_status"></div>
		<hr class="ui-state-default"/>
		<input type="hidden" name="orders_id" id="messages_rad_orders_id"/>
		<input type="hidden" name="t_messages_id" id="messages_rad_t_messages_id"/>
		<div id="messages_rad_accordion">
			<h3><a href="#">Imaging Tests</a></h3>
			<div>
				<div style="display:block;float:left;width:310px">
					Preview:<br><textarea name="orders_radiology" id="messages_rad_orders" rows="10" style="width:290px" class="text ui-widget-content ui-corner-all" placeholder="Type a few letters of order to search."></textarea>
				</div>
				<div style="display:block;float:left;">
					<button type="button" id="messages_rad_orderslist_link">Edit Imaging Orders Templates</button>
				</div>
				<div id="add_test_cpt1" title="Add Order to Database">
					<input type="hidden" id="messages_rad"/>
					<input type="hidden" id="messages_rad_orders_text"/>
					Order Type: <select id="messages_rad_orders_type"></select><br>
					CPT Code (optional):<br><input type="text" name="messages_rad_cpt" id="messages_rad_cpt" style="width:400px" class="text ui-widget-content ui-corner-all"/><br>
					<div id="add_test_snomed_div1">
						SNOMED Code (optional):<br><input type="text" name="messages_rad_snomed" id="messages_rad_snomed" style="width:400px" class="text ui-widget-content ui-corner-all" placeholder="Type a few letters to search or select from hierarchy."/><br><br>
						SNOMED Database: Click on arrow to expand hierarchy.  Click on item to select code.<br>
						<div id="snomed_tree1" style="height:250px; overflow:auto;"></div>
					</div>
				</div>
			</div>
			<h3><a href="#">Diagnosis Codes</a></h3>
			<div>
				<div style="display:block;float:left;width:310px">
					Preview:<br><textarea name="orders_radiology_icd" id="messages_rad_codes" rows="10" style="width:290px" class="text ui-widget-content ui-corner-all" placeholder="Use a comma to separate distinct search terms."></textarea>
				</div>
				<div style="display:block;float:left">
					<input type="button" id="messages_rad_codes_clear" value="Clear" class="messages_rad_button"/> 
					<input type="button" id="messages_rad_issues" value="Issues" class="messages_rad_button"/><br>
				</div>
			</div>
			<h3><a href="#">Location</a></h3>
			<div>
				<select name="address_id" id="messages_rad_location" class="text ui-widget-content ui-corner-all"></select><input type="button" id="messages_select_rad_location2" value="Add/Edit" class="messages_rad_button"/>
				<div id="messages_edit_rad_location" title="">
					<input type="hidden" name="messages_rad_location_address_id" id="messages_rad_location_address_id"/>
					<table>
						<tr>
							<td colspan="3">Facility:<br><input type="text" name="facility" id="messages_rad_location_facility" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Address:<br><input type="text" name="street_address1" id="messages_rad_location_address" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Address2:<br><input type="text" name="street_address2" id="messages_rad_location_address2" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>City:<br><input type="text" name="city" id="messages_rad_location_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>State:<br><select name="state" id="messages_rad_location_state" class="text ui-widget-content ui-corner-all"></td>
							<td>Zip:<br><input type="text" name="zip" id="messages_rad_location_zip" style="width:100px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Phone:<br><input type="text" name="phone" id="messages_rad_location_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Fax:<br><input type="text" name="fax" id="messages_rad_location_fax" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="3">Comments:<br><input type="text" name="comments" id="messages_rad_location_comments" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3" valign="top">Provider/Clinic Identity:<br><input type="text" name="ordering_id" id="messages_rad_location_ordering_id" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
					</table>
				</div>
			</div>
			<h3><a href="#">Insurance</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_insurance"  id="messages_rad_insurance" rows="3" style="width:500px" class="text ui-widget-content ui-corner-all"/></textarea></td>
						<td valign="top"><br>
							<input type="button" id="messages_rad_insurance_clear" value="Clear" class="messages_rad_button"/><br>
							<input type="button" id="messages_rad_insurance_client" value="Bill Client" class="messages_rad_button"/>
						</td>
					</tr>
				</table><br>
				<table id="messages_rad_insurance_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="messages_rad_insurance_pager" class="scroll" style="text-align:center;"></div><br>
			</div>
		</div>
	</div>
	<div id="messages_rad_action_fieldset" style="display:none">
		<div id="messages_rad_choice"></div><br>
		<input type="button" id="messages_print_rad" value="Print" class="messages_rad_button"/> 
		<!--<input type="button" id="messages_electronic_rad" value="Electronic" class="messages_rad_button"/> -->
		<input type="button" id="messages_fax_rad" value="Fax" class="messages_rad_button"/>
		<input type="button" id="messages_done_rad" value="Done" class="messages_rad_button"/> 
	</div>
	</form>
</div>
<script type="text/javascript">
	$.ajax({
		url: "<?php echo site_url('start/check_fax');?>",
		type: "POST",
		success: function(data){
			if (data == "Yes") {
				$("#messages_fax_rad").show('fast');
			} else {
				$("#messages_fax_rad").hide('fast');
			}
		}
	});
	$(".messages_rad_button").button();
	$("#messages_rad_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(){
			 $("#messages_rad_accordion").accordion({ heightStyle: "content" });
			 $("#messages_rad_grid").show('fast');
			 $("#messages_rad_edit_fields").hide('fast');
			 jQuery("#messages_rad_list").jqGrid('GridUnload');
			 jQuery("#messages_rad_list").jqGrid({
				url:"<?php echo site_url('assistant/chartmenu/rad_list');?>",
				postData: {t_messages_id: function(){return $("#messages_rad_t_messages_id").val();}},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Tests','Diagnosis','Location1','Location','Insurance','Provider'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_radiology',index:'orders_radiology',width:300},
					{name:'orders_radiology_icd',index:'orders_radiology_icd',width:200},
					{name:'address_id',index:'address_id',hidden:true},
					{name:'displayname',index:'displayname',width:100},
					{name:'orders_insurance',index:'orders_insurance',hidden:true},
					{name:'encounter_provider',index:'encounter_provider',hidden:true}
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
							url: "<?php echo site_url('assistant/chartmenu/addressdefine');?>",
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
				url:"<?php echo site_url('assistant/chartmenu/insurance/');?>",
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
			 	caption:"Insurance Payors - Click to select insurance for imaging order",
			 	height: "100%",
			 	onSelectRow: function(id){
			 		var insurance_plan_name = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_plan_name');
					var insurance_id_num = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_id_num');
					var insurance_group = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_group');
					var insurance_insu_lastname = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_insu_lastname');
					var insurance_insu_firstname = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_insu_firstname');
					var address_id = jQuery("#messages_rad_insurance_grid").getCell(id,'address_id');
					$.ajax({
						url: "<?php echo site_url('search/payor_id');?>/" + address_id,
						type: "POST",
						success: function(data){
							var text = insurance_plan_name + '; Payor ID: ' + data + '; ID: ' + insurance_id_num;
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
						}
					});
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_rad_insurance_pager',{search:false,edit:false,add:false,del:false});
			$.ajax({
				url: "<?php echo site_url('search/rad_provider');?>",
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#messages_rad_location").addOption({"":"Add imaging provider."});
						$("#messages_rad_location").addOption(data.message);
					} else {
						$("#messages_rad_location").addOption({"":"No imaging provider.  Click Add."});
					}
				}
			});
			$.ajax({
				url: "<?php echo site_url('search/providers1');?>",
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#messages_rad_provider_list").addOption({"":"Select a provider for the order."});
						$("#messages_rad_provider_list").addOption(data.message);
					} else {
						$("#messages_rad_provider_list").addOption({"":"No providers."});
					}
				}
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#messages_rad_orders").val();
			if(a != ''){
				if(confirm('The form fields are not empty.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
					$('#edit_message_rad_form').clearForm();
					$("#messages_rad").val('');
					$("#messages_rad_cpt").val('');
					$("#edit_message_rad_location_form").clearForm();
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
		$("#messages_rad_accordion").accordion("option", "active", 0);
		$("#messages_rad_orders").focus();
		$("#messages_rad_location").val('');
		$("#messages_rad_provider_list").val('');
		$("#messages_rad_grid").hide('fast');
	});
	$("#messages_edit_rad").click(function(){
		var item = jQuery("#messages_rad_list").getGridParam('selrow');
		if(item){
			jQuery("#messages_rad_list").GridToForm(item,"#edit_message_rad_form");
			var status = 'Details for Radiology Order #' + item;
			$("#messages_rad_status").html(status);
			$("#messages_rad_edit_fields").show('fast');
			$("#messages_rad_action_fieldset").hide('fast');
			$("#messages_rad_accordion").accordion("option", "active", 0);
			$("#messages_rad_orders").focus();
			$("#messages_rad_grid").hide('fast');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_resend_rad").click(function(){
		var item = jQuery("#messages_rad_list").getGridParam('selrow');
		if(item){
			$("#messages_rad_orders_id").val(item);
			$('#messages_rad_choice').html("Choose an action for the radiology order, reference number " + item);
			$("#messages_rad_action_fieldset").show('fast');
			$("#messages_rad_edit_fields").hide('fast');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_delete_rad").click(function(){
		var item = jQuery("#messages_rad_list").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "<?php echo site_url('assistant/chartmenu/delete_rad');?>",
				type: "POST",
				data: "orders_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#messages_rad_list").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select order to delete!");
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
	function split( val ) {
		return val.split( /\n\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}
	$("#messages_rad_orders").catcomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rad');?>",
				dataType: "json",
				type: "POST",
				data: "term=" + extractLast(req.term),
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					} else {
						var addterm = [{"label": extractLast(req.term) + ": Select to add order to database.", "value":"*/add/*", "value1": extractLast(req.term), "category":"New Item"}];
						add(addterm);
					}
				}
			});
		},
		search: function() {
			var term = extractLast( this.value );
			if ( term.length < 2 ) {
				return false;
			}
		},
		focus: function() {
			return false;
		},
		select: function(event, ui){
			if (ui.item.value == "*/add/*") {
				$("#messages_rad").val(ui.item.value1);
				$("#messages_rad_orders_text").val(this.value);
				$("#add_test_cpt1").dialog('open');
			} else {
				var terms = split( this.value );
				terms.pop();
				terms.push( ui.item.value );
				terms.push( "" );
				this.value = terms.join( "\n" );
				return false;
			}
		}
	});
	$("#messages_rad_codes").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/icd9');?>",
				dataType: "json",
				type: "POST",
				data: "term=" + extractLast(req.term),
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		search: function() {
			var term = extractLast( this.value );
			if ( term.length < 2 ) {
				return false;
			}
		},
		focus: function() {
			return false;
		},
		select: function(event, ui){
			var terms = split( this.value );
			terms.pop();
			terms.push( ui.item.value );
			terms.push( "" );
			this.value = terms.join( "\n" );
			return false;
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
	$("#messages_select_rad_location2").click(function (){
		$("#messages_edit_rad_location").dialog('open');
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
		minLength: 3
	});
	$("#messages_rad_location_state").addOption({"AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#messages_rad_location_phone").mask("(999) 999-9999");
	$("#messages_rad_location_fax").mask("(999) 999-9999");
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
	$("#messages_rad_save").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#messages_rad_save").click(function(){
		var order = $("#messages_rad_orders");
		var codes = $("#messages_rad_codes");
		var location = $("#messages_rad_location");
		var insurance = $("#messages_rad_insurance");
		var bValid = true;
		bValid = bValid && checkEmpty(order,"Tests");
		bValid = bValid && checkEmpty(codes,"Diagnosis Codes");
		bValid = bValid && checkEmpty(location,"Imaging Provider");
		bValid = bValid && checkEmpty(insurance,"Insurance");
		if (bValid) {
			var a = encodeURIComponent($("#messages_rad_orders").val());
			var b = encodeURIComponent($("#messages_rad_codes").val());
			var c = encodeURIComponent($("#messages_rad_location").val());
			var d = encodeURIComponent($("#messages_rad_t_messages_id").val());
			var e = encodeURIComponent($("#messages_rad_orders_id").val());
			var f = encodeURIComponent($("#messages_rad_insurance").val());
			var g = encodeURIComponent($("#messages_rad_provider_list").val());
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/chartmenu/add_rad_order');?>",
				data: "orders_radiology=" + a + "&orders_radiology_icd=" + b + "&address_id=" + c + "&t_messages_id=" + d + "&orders_id=" + e + "&orders_insurance=" + f + "&id=" + g,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					$("#messages_rad_orders_id").val(data.id);
					$('#messages_rad_choice').html(data.choice);
					$("#messages_rad_action_fieldset").show('fast');
					$("#messages_rad_edit_fields").hide('fast');
					jQuery("#alerts").trigger("reloadGrid");
					jQuery("messages_rad_list").trigger("reloadGrid");
				}
			});
		}
	});
	$("#messages_rad_cancel").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#messages_rad_cancel").click(function(){
		$("#messages_rad_edit_fields").hide('fast');
		$("#messages_rad_action_fieldset").hide('fast');
		$("#messages_rad_grid").show('fast');
		jQuery("#messages_rad_list").trigger("reloadGrid");
	});
	$("#save_rad_helper").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_rad_helper").click(function(){
		var origin = $("#messages_rad_origin").val();
		if (origin == 'message') {
			var id = $("#t_messages_id").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/chartmenu/import_rad1');?>",
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
				url: "<?php echo site_url('assistant/encounters/check_orders');?>",
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
		$('#edit_message_rad_form').clearForm();
		$("#messages_rad").val('');
		$("#messages_rad_origin").val('');
		$("#messages_rad_cpt").val('');
		$("#edit_message_rad_location_form").clearForm();
		$("#messages_rad_edit_fields").hide('fast');
		$("#messages_rad_action_fieldset").hide('fast');
		$("#edit_message_rad_form").show('fast');
		$("#messages_rad_dialog").dialog('close');
	});
	$("#cancel_rad_helper").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_rad_helper").click(function(){
		$('#edit_message_rad_form').clearForm();
		$("#messages_rad").val('');
		$("#messages_rad_origin").val('');
		$("#messages_rad_cpt").val('');
		$("#edit_message_rad_location_form").clearForm();
		$("#messages_rad_edit_fields").hide('fast');
		$("#messages_rad_action_fieldset").hide('fast');
		$("#edit_message_rad_form").show('fast');
		$("#messages_rad_dialog").dialog('close');
	});
	$("#messages_print_rad").click(function(){
		var rad = $("#messages_rad_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(rad,"Radiology Order");
		if (bValid) {
			var order_id = $("#messages_rad_orders_id").val();
			window.open("<?php echo site_url('assistant/chartmenu/print_orders');?>/" + order_id);
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
					url: "<?php echo site_url('assistant/chartmenu/fax_orders');?>",
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
	$("#messages_edit_rad_location").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#messages_rad_location_facility").focus();
			var id = $("#messages_rad_location").val();
			if(id){
				$("#messages_edit_rad_location").dialog("option", "title", "Edit Imaging Provider");
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
					}
				});
			} else {
				$("#messages_edit_rad_location").dialog("option", "title", "Add Imaging Provider");
			}
		},
		buttons: {
			'Save': function() {
				var facility = $("#messages_rad_location_facility");
				var bValid = true;
				bValid = bValid && checkEmpty(facility,"Facility");
				if (bValid) {
					var a = encodeURIComponent($("#messages_rad_location_facility").val());
					var b = encodeURIComponent($("#messages_rad_location_address").val());
					var c = encodeURIComponent($("#messages_rad_location_address2").val());
					var d = encodeURIComponent($("#messages_rad_location_city").val());
					var e = encodeURIComponent($("#messages_rad_location_state").val());
					var f = encodeURIComponent($("#messages_rad_location_zip").val());
					var g = encodeURIComponent($("#messages_rad_location_phone").val());
					var h = encodeURIComponent($("#messages_rad_location_fax").val());
					var i = encodeURIComponent($("#messages_rad_location_address_id").val());
					var j = encodeURIComponent($("#messages_rad_location_comments").val());
					var k = encodeURIComponent($("#messages_rad_location_ordering_id").val());
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/chartmenu/edit_rad_provider');?>",
						data: "facility=" + a + "&street_address1=" + b + "&street_address2=" + c + "&city=" + d + "&state=" + e + "&zip=" + f + "&phone=" + g + "&fax=" + h + "&address_id=" + i + "&comments=" + j + "&ordering_id=" + k,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.message);
							$("#messages_edit_rad_location").clearDiv();
							$("#messages_edit_rad_location").dialog('close');
							$("#messages_edit_rad_location").dialog("option", "title", "");
							$("#messages_rad_location").removeOption(/./);
							$.ajax({
								url: "<?php echo site_url('search/rad_provider');?>",
								dataType: "json",
								type: "POST",
								success: function(data1){
									if(data1.response =='true'){
										$("#messages_rad_location").addOption(data1.message);
										$("#messages_rad_location").val(data.id);
									}
								}
							});
						}
					});
				}
			},
			Cancel: function() {
				$("#messages_edit_rad_location").clearDiv();
				$("#messages_edit_rad_location").dialog('close');
				$("#messages_edit_rad_location").dialog("option", "title", "");
			}
		},
		close: function(event, ui) {
			$("#messages_edit_rad_location").clearDiv();
			$("#messages_edit_rad_location").dialog("option", "title", "");
		}
	});
	$("#messages_rad_orders_type").addOption({"0":'Global',"<?php echo $this->session->userdata('user_id');?>":'Personal'});
	$("#add_test_cpt1").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		buttons: {
			'Save': function() {
				var a = encodeURIComponent($("#messages_rad").val());
				var b = encodeURIComponent($("#messages_rad_cpt").val());
				var c = encodeURIComponent($("#messages_rad_orders_type").val());
				var d = a + b;
				var e = encodeURIComponent($("#messages_rad_snomed").val());
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/chartmenu/add_orderslist');?>",
					data: "orders_description=" + a + "&cpt=" + b + "&orders_category=Radiology&user_id=" + c + "&snomed=" +e,
					success: function(data){
						$.jGrowl(data);
					}
				});
				if(b){
					a = a + ', CPT ' + b;
				}
				var terms = split($("#messages_rad_orders_text").val());
				terms.pop();
				terms.push(a);
				terms.push( "" );
				$("#messages_rad_orders").focus();
				$("#messages_rad_orders").val(terms.join( "\n" ));
				$("#add_test_cpt1").clearDiv();
				$("#add_test_cpt1").dialog('close');
				return false;
			},
			Cancel: function() {
				var terms = split($("#messages_rad_orders_text").val());
				terms.pop();
				terms.push( "" );
				$("#messages_rad_orders").focus();
				$("#messages_rad_orders").val(terms.join( "\n" ));
				$("#add_test_cpt1").clearDiv();
				$("#add_test_cpt1").dialog('close');
				return false;
			}
		},
		open: function(event, ui) {
			$.ajax({
				url: "<?php echo site_url('start/check_snomed_extension');?>",
				type: "POST",
				success: function(data){
					if(data =='y'){
						$("#add_test_snomed_div1").show();
						$("#snomed_tree1").jstree({
							"plugins" : [ "json_data", "sort", "ui", "themeroller" ],
							"json_data" : {
								"ajax" : {
									"type": 'POST',
									"url": function (node) {
										var nodeId = "";
										var url = "";
										if (node == -1) {
											url = "<?php echo site_url('search/snomed_parent/imaging');?>";
										} else {
											nodeId = node.attr('id');
											url = "<?php echo site_url('search/snomed_child');?>/" + nodeId;
										}
										return url;
									},
									"success": function (new_data) {
										return new_data;
									}
								}
							},
							"themeroller" : {
								"item" : 'ui-widget-content'
							}
						}).bind("select_node.jstree", function (event, data) {
							$("#messages_rad_snomed").val(data.rslt.obj.attr("id"));
						});
						$("#messages_rad_snomed").autocomplete({
							source: function (req, add){
								$.ajax({
									url: "<?php echo site_url('search/snomed');?>/procedure",
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
					} else {
						$("#add_test_snomed_div1").hide();
					}
				}
			});
			$("#messages_rad_cpt").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "<?php echo site_url('search/cpt');?>",
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
				select: function(event, ui){
					if (ui.item.value == "*/add/*") {
						$("#configuration_cpt_form").clearForm();
						if (ui.item.value1.length > 5) {
							$("#configuration_cpt_description").val(ui.item.value1);
						} else {
							$("#configuration_cpt_code").val(ui.item.value1);
						}
						$('#configuration_cpt_origin').val("messages_rad_cpt");
						$('#configuration_cpt_dialog').dialog('open');
						$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
					}
				},
				minLength: 3
			});
			$("#messages_rad_orders_type").val('0');
		},
		close: function(event, ui) {
			var a = $("#messages_rad").val();
			if (a != "") {
				if(confirm('Are you sure you want to close this window?  The test has not been saved.')){ 
					var terms = split($("#messages_rad_orders_text").val());
					terms.pop();
					terms.push( "" );
					$("#messages_rad_orders").focus();
					$("#messages_rad_orders").val(terms.join( "\n" ));
					$("#add_test_cpt1").clearDiv();
					$("#add_test_cpt1").dialog('close');
					return false;
				}
			}
		}
	});
	$("#messages_rad_orderslist_link").button().click(function(){
		$("#configuration_dialog").dialog('open');
		$("#configuration_accordion").accordion("option", "active", 4);
	});
</script>
