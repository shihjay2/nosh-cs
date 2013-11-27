<div id="messages_lab_dialog" title="Lab Helper">
	<input type="hidden" name="messages_lab_origin" id="messages_lab_origin"/>
	<form name="edit_message_lab_form" id="edit_message_lab_form">
	<div id="messages_lab_grid">
		<button type="button" id="save_lab_helper"><div id="save_lab_helper_label"></div></button>
		<button type="button" id="cancel_lab_helper">Cancel</button> 
		<hr class="ui-state-default"/>
		<table id="messages_lab_list" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_lab_list_pager" class="scroll" style="text-align:center;"></div><br>
		<input type="button" id="messages_add_lab" value="Add" class="messages_lab_button"/> 
		<input type="button" id="messages_edit_lab" value="Edit" class="messages_lab_button"/> 
		<input type="button" id="messages_resend_lab" value="Resend" class="messages_lab_button"/> 
		<input type="button" id="messages_delete_lab" value="Delete" class="messages_lab_button"/><br><br>
	</div>
	<div id="messages_lab_edit_fields">
		<button type="button" id="messages_lab_save">Save</button>
		<button type="button" id="messages_lab_cancel">Cancel</button>
		<div style="float:right;" id="messages_lab_status"></div>
		<hr class="ui-state-default"/>
		<input type="hidden" name="orders_id" id="messages_lab_orders_id"/>
		<input type="hidden" name="t_messages_id" id="messages_lab_t_messages_id"/>
		<div id="messages_lab_accordion">
			<h3><a href="#">Lab Tests</a></h3>
			<div>
				<div style="display:block;float:left;width:310px">
					Preview:<br><textarea name="orders_labs" id="messages_lab_orders" rows="10" style="width:290px" class="text ui-widget-content ui-corner-all" placeholder="Type a few letters of order to search."></textarea>
				</div>
				<div style="display:block;float:left;">
					<button type="button" id="messages_lab_orderslist_link">Edit Lab Orders Templates</button>
				</div>
				<div id="add_test_cpt" title="Add Order to Database">
					<input type="hidden" id="messages_lab"/>
					<input type="hidden" id="messages_lab_orders_text"/>
					Order Type: <select id="messages_lab_orders_type"></select><br>
					CPT Code (optional):<br><input type="text" name="messages_lab_cpt" id="messages_lab_cpt" style="width:400px" class="text ui-widget-content ui-corner-all"/><br>
					<div id="add_test_snomed_div">
						SNOMED Code (optional):<br><input type="text" name="messages_lab_snomed" id="messages_lab_snomed" style="width:400px" class="text ui-widget-content ui-corner-all" placeholder="Type a few letters to search or select from hierarchy."/><br><br>
						SNOMED Database: Click on arrow to expand hierarchy.  Click on item to select code.<br>
						<div id="snomed_tree" style="height:250px; overflow:auto;"></div>
					</div>
				</div>
			</div>
			<h3><a href="#">Diagnosis Codes</a></h3>
			<div>
				<div style="display:block;float:left;width:310px">
					Preview:<br><textarea name="orders_labs_icd" id="messages_lab_codes" rows="10" style="width:290px" class="text ui-widget-content ui-corner-all" placeholder="Use a comma to separate distinct search terms."></textarea>
				</div>
				<div style="display:block;float:left">
					<input type="button" id="messages_lab_codes_clear" value="Clear" class="messages_lab_button"/> 
					<input type="button" id="messages_lab_issues" value="Issues" class="messages_lab_button"/><br>
				</div>
			</div>
			<h3><a href="#">Location</a></h3>
			<div>
				<select name="address_id" id="messages_lab_location" class="text ui-widget-content ui-corner-all"></select><input type="button" id="messages_select_lab_location2" value="Add/Edit" class="messages_lab_button"/>
				<div id="messages_edit_lab_location" title="">
					<input type="hidden" name="messages_lab_location_address_id" id="messages_lab_location_address_id"/>
					<table>
						<tr>
							<td colspan="3">Facility:<br><input type="text" name="facility" id="messages_lab_location_facility" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Address:<br><input type="text" name="street_address1" id="messages_lab_location_address" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Address2:<br><input type="text" name="street_address2" id="messages_lab_location_address2" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>City:<br><input type="text" name="city" id="messages_lab_location_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>State:<br><select name="state" id="messages_lab_location_state" class="text ui-widget-content ui-corner-all"></td>
							<td>Zip:<br><input type="text" name="zip" id="messages_lab_location_zip" style="width:100px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Phone:<br><input type="text" name="phone" id="messages_lab_location_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Fax:<br><input type="text" name="fax" id="messages_lab_location_fax" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="3">Comments:<br><input type="text" name="comments" id="messages_lab_location_comments" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Provider/Clinic Identity:<br><input type="text" name="ordering_id" id="messages_lab_location_ordering_id" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td colspan="2">Electronic Order Interface (optional)<br><select name="electronic_order" id="messages_lab_location_electronic_order" class="text ui-widget-content ui-corner-all"></select></td>
						</tr>
					</table>
				</div>
			</div>
			<h3><a href="#">Insurance</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_insurance"  id="messages_lab_insurance" rows="3" style="width:500px" class="text ui-widget-content ui-corner-all"/></textarea></td>
						<td valign="top"><br>
							<input type="button" id="messages_lab_insurance_clear" value="Clear" class="messages_lab_button"/><br>
							<input type="button" id="messages_lab_insurance_client" value="Bill Client" class="messages_lab_button"/>
						</td>
					</tr>
				</table><br>
				<table id="messages_lab_insurance_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="messages_lab_insurance_pager" class="scroll" style="text-align:center;"></div><br>
			</div>
			<h3><a href="#">Obtained Specimens</a></h3>
			<div>
				<div style="display:block;float:left;width:310px">
					Preview:<br><textarea name="orders_labs_obtained" id="messages_lab_obtained" rows="12" style="width:290px" class="text ui-widget-content ui-corner-all"/></textarea>
				</div>
				<div style="display:block;float:left">
					Fasting:<br><select name="messages_lab_fasting" id="messages_lab_fasting" class="text ui-widget-content ui-corner-all"><option value="">Choose an option</option><option value="Yes">Yes</option><option value="No">No</option></select><br>
					Date Obtained:<br><input type="text" id="messages_lab_date_obtained" name="messages_lab_date_obtained" style="width:164px" class="text ui-widget-content ui-corner-all"/><br>
					Time Obtained:<br><input type="text" id="messages_lab_time_obtained" name="messages_lab_time_obtained" style="width:164px" class="text ui-widget-content ui-corner-all"/><br>
					Body Location Obtained:<br><input type="text" id="messages_lab_location_obtained" name="messages_lab_location_obtained" style="width:290px" class="text ui-widget-content ui-corner-all"/><br>
					Time of Last Medication Dosage:<br><input type="text" id="messages_lab_medication_obtained" name="messages_lab_medication_obtained" style="width:164px" class="text ui-widget-content ui-corner-all"/><br>
					<button type="button" id="messages_lab_obtained_import">Enter</button>
				</div>
			</div>
		</div>
	</div>
	<div id="messages_lab_action_fieldset" style="display:none">
		<div id="messages_lab_choice"></div><br>
		<input type="button" id="messages_print_lab" value="Print" class="messages_lab_button"/> 
		<input type="button" id="messages_electronic_lab" value="Electronic" class="messages_lab_button"/>
		<input type="button" id="messages_fax_lab" value="Fax" class="messages_lab_button"/>
		<input type="button" id="messages_done_lab" value="Done" class="messages_lab_button"/> 
	</div>
	</form>
</div>
<script type="text/javascript">
	$.ajax({
		url: "<?php echo site_url('start/check_fax');?>",
		type: "POST",
		success: function(data){
			if (data == "Yes") {
				$("#messages_fax_lab").show('fast');
			} else {
				$("#messages_fax_lab").hide('fast');
			}
		}
	});
	$(".messages_lab_button").button();
	$("#messages_lab_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(){
			 $("#messages_lab_accordion").accordion({ heightStyle: "content" });
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
			 	caption:"Insurance Payors - Click to select insurance for lab order",
			 	height: "100%",
			 	onSelectRow: function(id){
			 		var insurance_plan_name = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_plan_name');
					var insurance_id_num = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_id_num');
					var insurance_group = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_group');
					var insurance_insu_lastname = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_insu_lastname');
					var insurance_insu_firstname = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_insu_firstname');
					var address_id = jQuery("#messages_lab_insurance_grid").getCell(id,'address_id');
					$.ajax({
						url: "<?php echo site_url('search/payor_id');?>/" + address_id,
						type: "POST",
						success: function(data){
							var text = insurance_plan_name + '; Payor ID: ' + data + '; ID: ' + insurance_id_num;
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
						}
					});
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_lab_insurance_pager',{search:false,edit:false,add:false,del:false});
			$.ajax({
				url: "<?php echo site_url('search/lab_provider');?>",
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#messages_lab_location").addOption({"":"Add lab provider."});
						$("#messages_lab_location").addOption(data.message);
					} else {
						$("#messages_lab_location").addOption({"":"No lab provider.  Click Add."});
					}
				}
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#messages_lab_orders").val();
			if(a != ''){
				if(confirm('The form fields are not empty.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
					$('#edit_message_lab_form').clearForm();
					$("#messages_lab").val('');
					$("#messages_lab_cpt").val('');
					$("#edit_message_lab_location_form").clearForm();
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
		$("#messages_lab_accordion").accordion("option", "active", 0);
		$("#messages_lab_orders").focus();
		$("#messages_lab_location").val('');
		$("#messages_lab_grid").hide('fast');
	});
	$("#messages_edit_lab").click(function(){
		var item = jQuery("#messages_lab_list").getGridParam('selrow');
		if(item){
			jQuery("#messages_lab_list").GridToForm(item,"#edit_message_lab_form");
			var status = 'Details for Lab Order #' + item;
			$("#messages_lab_status").html(status);
			$("#messages_lab_edit_fields").show('fast');
			$("#messages_lab_action_fieldset").hide('fast');
			$("#messages_lab_accordion").accordion("option", "active", 0);
			$("#messages_lab_orders").focus();
			$("#messages_lab_grid").hide('fast');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_resend_lab").click(function(){
		var item = jQuery("#messages_lab_list").getGridParam('selrow');
		if(item){
			$("#messages_lab_orders_id").val(item);
			$('#messages_lab_choice').html("Choose an action for the lab order, reference number " + item);
			$("#messages_lab_action_fieldset").show('fast');
			$("#messages_lab_edit_fields").hide('fast');
		} else {
			$.jGrowl("Please select order to edit!");
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
			$.jGrowl("Please select order to delete!");
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
	function split( val ) {
		return val.split( /\n\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}
	$("#messages_lab_orders").catcomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/lab');?>",
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
				$("#messages_lab").val(ui.item.value1);
				$("#messages_lab_orders_text").val(this.value);
				$("#add_test_cpt").dialog('open');
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
	$("#messages_lab_codes").autocomplete({
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
	$("#messages_select_lab_location2").click(function (){
		$("#messages_edit_lab_location").dialog('open');
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
		minLength: 3
	});
	$("#messages_lab_location_state").addOption({"AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#messages_lab_location_phone").mask("(999) 999-9999");
	$("#messages_lab_location_fax").mask("(999) 999-9999");
	$("#messages_lab_location_electronic_order").addOption({"":"Select Electronic Order Interface","PeaceHealth":"PeaceHealth Labs"});
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
	
	$("#messages_lab_save").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#messages_lab_save").click(function(){
		var order = $("#messages_lab_orders");
		var codes = $("#messages_lab_codes");
		var location = $("#messages_lab_location");
		var insurance = $("#messages_lab_insurance");
		var bValid = true;
		bValid = bValid && checkEmpty(order,"Tests");
		bValid = bValid && checkEmpty(codes,"Diagnosis Codes");
		bValid = bValid && checkEmpty(location,"Laboratory Provider");
		bValid = bValid && checkEmpty(insurance,"Insurance");
		if (bValid) {
			var a = $("#messages_lab_orders").val();
			var b = $("#messages_lab_codes").val();
			var c = $("#messages_lab_location").val();
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
					jQuery("#alerts").trigger("reloadGrid");
					jQuery("messages_lab_list").trigger("reloadGrid");
				}
			});
		}
	});
	$("#messages_lab_cancel").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#messages_lab_cancel").click(function(){
		$("#messages_lab_edit_fields").hide('fast');
		$("#messages_lab_action_fieldset").hide('fast');
		$("#messages_lab_grid").show('fast');
		jQuery("#messages_lab_list").trigger("reloadGrid");
	});
	$("#save_lab_helper").button({
		icons: {
			primary: "ui-icon-disk"
		}
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
				}
			});
		}
		$('#edit_message_lab_form').clearForm();
		$("#messages_lab").val('');
		$("#messages_lab_origin").val('');
		$("#messages_lab_cpt").val('');
		$("#edit_message_lab_location_form").clearForm();
		$("#messages_lab_edit_fields").hide('fast');
		$("#messages_lab_action_fieldset").hide('fast');
		$("#edit_message_lab_form").show('fast');
		$("#messages_lab_dialog").dialog('close');
	});
	$("#cancel_lab_helper").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_lab_helper").click(function(){
		$('#edit_message_lab_form').clearForm();
		$("#messages_lab").val('');
		$("#messages_lab_origin").val('');
		$("#messages_lab_cpt").val('');
		$("#edit_message_lab_location_form").clearForm();
		$("#messages_lab_edit_fields").hide('fast');
		$("#messages_lab_action_fieldset").hide('fast');
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
		var lab = $("#messages_lab_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(lab,"Lab Order");
		if (bValid) {
			var order_id = $("#messages_lab_orders_id").val();
			if(order_id){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/electronic_orders');?>",
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
	$('#messages_lab_time_obtained').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	$('#messages_lab_medication_obtained').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	$("#messages_lab_obtained_import").button();
	$("#messages_lab_obtained_import").click(function(){
		var a1 = $("#messages_lab_date_obtained");
		var b1 = $("#messages_lab_time_obtained");
		var bValid = true;
		bValid = bValid && checkEmpty(a1,"Date Obtained");
		bValid = bValid && checkEmpty(b1,"Time Obtained");
		if (bValid) {
			var item = '';
			var a = $("#messages_lab_fasting").val();
			if(a){
				item += 'Fasting: ' + $("#messages_lab_fasting").val() + '\n';
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
			$("#messages_lab_date_obtained").val(currentDate);
			$("#messages_lab_time_obtained").val(currentTime);
			$("#messages_lab_location_obtained").val('');
			$("#messages_lab_medication_obtained").val('');
		}
	});
	$("#messages_edit_lab_location").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#messages_lab_location_facility").focus();
			var id = $("#messages_lab_location").val();
			if(id){
				$("#messages_edit_lab_location").dialog("option", "title", "Edit Laboratory Provider");
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
						$("#messages_lab_location_comments").val(data.comments);
						$("#messages_lab_location_ordering_id").val(data.ordering_id);
						$("#messages_lab_location_electronic_order").val(data.electronic_order);
					}
				});
			} else {
				$("#messages_edit_lab_location").dialog("option", "title", "Add Laboratory Provider");
			}
		},
		buttons: {
			'Save': function() {
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
					var l = $("#messages_lab_location_electronic_order").val();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/edit_lab_provider');?>",
						data: "facility=" + a + "&street_address1=" + b + "&street_address2=" + c + "&city=" + d + "&state=" + e + "&zip=" + f + "&phone=" + g + "&fax=" + h + "&address_id=" + i + "&comments=" + j + "&ordering_id=" + k + "&electronic_order=" + l,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.message);
							$("#messages_edit_lab_location").clearDiv();
							$("#messages_edit_lab_location").dialog('close');
							$("#messages_edit_lab_location").dialog("option", "title", "");
							$("#messages_lab_location").removeOption(/./);
							$.ajax({
								url: "<?php echo site_url('search/lab_provider');?>",
								dataType: "json",
								type: "POST",
								success: function(data1){
									if(data1.response =='true'){
										$("#messages_lab_location").addOption(data1.message);
										$("#messages_lab_location").val(data.id);
									}
								}
							});
						}
					});
				}
			},
			Cancel: function() {
				$("#messages_edit_lab_location").clearDiv();
				$("#messages_edit_lab_location").dialog('close');
				$("#messages_edit_lab_location").dialog("option", "title", "");
			}
		},
		close: function(event, ui) {
			$("#messages_edit_lab_location").clearDiv();
			$("#messages_edit_lab_location").dialog("option", "title", "");
		}
	});
	$("#messages_lab_orders_type").addOption({"0":'Global',"<?php echo $this->session->userdata('user_id');?>":'Personal'});
	$("#add_test_cpt").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		buttons: {
			'Save': function() {
				var a = $("#messages_lab").val();
				var b = $("#messages_lab_cpt").val();
				var c = $("#messages_lab_orders_type").val();
				var d = a + b;
				var e = $("#messages_lab_snomed").val();
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/add_orderslist');?>",
					data: "orders_description=" + a + "&cpt=" + b + "&orders_category=Laboratory&user_id=" + c + "&snomed=" +e,
					success: function(data){
						$.jGrowl(data);
					}
				});
				if(b){
					a = a + ', CPT ' + b;
				}
				var terms = split($("#messages_lab_orders_text").val());
				terms.pop();
				terms.push(a);
				terms.push( "" );
				$("#messages_lab_orders").focus();
				$("#messages_lab_orders").val(terms.join( "\n" ));
				$("#add_test_cpt").clearDiv();
				$("#add_test_cpt").dialog('close');
				return false;
			},
			Cancel: function() {
				var terms = split($("#messages_lab_orders_text").val());
				terms.pop();
				terms.push( "" );
				$("#messages_lab_orders").focus();
				$("#messages_lab_orders").val(terms.join( "\n" ));
				$("#add_test_cpt").clearDiv();
				$("#add_test_cpt").dialog('close');
				return false;
			}
		},
		open: function(event, ui) {
			$.ajax({
				url: "<?php echo site_url('start/check_snomed_extension');?>",
				type: "POST",
				success: function(data){
					if(data =='y'){
						$("#add_test_snomed_div").show();
						$("#snomed_tree").jstree({
							"plugins" : [ "json_data", "sort", "ui", "themeroller" ],
							"json_data" : {
								"ajax" : {
									"type": 'POST',
									"url": function (node) {
										var nodeId = "";
										var url = "";
										if (node == -1) {
											url = "<?php echo site_url('search/snomed_parent/lab');?>";
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
							$("#messages_lab_snomed").val(data.rslt.obj.attr("id"));
						});
						$("#messages_lab_snomed").autocomplete({
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
						$("#add_test_snomed_div").hide();
					}
				}
			});
			$("#messages_lab_cpt").autocomplete({
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
						$('#configuration_cpt_origin').val("messages_lab_cpt");
						$('#configuration_cpt_dialog').dialog('open');
						$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
					}
				},
				minLength: 3
			});
			$("#messages_lab_orders_type").val('0');
		},
		close: function(event, ui) {
			var a = $("#messages_lab").val();
			if (a != "") {
				if(confirm('Are you sure you want to close this window?  The test has not been saved.')){ 
					var terms = split($("#messages_lab_orders_text").val());
					terms.pop();
					terms.push( "" );
					$("#messages_lab_orders").focus();
					$("#messages_lab_orders").val(terms.join( "\n" ));
					$("#add_test_cpt").clearDiv();
					$("#add_test_cpt").dialog('close');
					return false;
				}
			}
		}
	});
	$("#messages_lab_orderslist_link").button().click(function(){
		$("#configuration_dialog").dialog('open');
		$("#configuration_accordion").accordion("option", "active", 3);
	});
</script>
