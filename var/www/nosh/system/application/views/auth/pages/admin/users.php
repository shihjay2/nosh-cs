<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load("<?php echo site_url("search/loadpage");?>");
	jQuery("#provider_list").jqGrid({
		url:"<?php echo site_url('admin/users/users_list_provider');?>",
		editurl:"<?php echo site_url('admin/users/edit_users_list_provider');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail','Specialty','License Number','State Licensed','NPI','NPI Taxonomy Code','UPIN','DEA Number','Medicare Number','Tax ID Number','RCopia Username'],
		colModel:[
			{name:'id',index:'id',width:1,hidden:true},
			{name:'username',index:'username',width:400,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'password',index:'password',width:1,editable:false,hidden:true},
			{name:'firstname',index:'firstname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'middle',index:'middle',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'lastname',index:'lastname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'title',index:'title',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'displayname',index:'displayname',width:400},
			{name:'email',index:'email',width:1,editable:true,hidden:true,editrules:{edithidden:true, email:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{	name:'specialty',
				index:'specialty',
				width:1,
				editable:true,
				hidden:true,
				editrules:{edithidden:true, required:true},
				editoptions:{	dataInit:function(elem){ 
						$(elem).autocomplete({
							source: function (req, add){
								$.ajax({
									url: "<?php echo site_url('search/specialty');?>",
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
					}
				},
				formoptions:{elmsuffix:"(*)"}
			},
			{name:'license',index:'license',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'license_state',index:'license_state',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},edittype:'select',editoptions:{value:"AL:Alabama;AK:Alaska;AS:America Samoa;AZ:Arizona;AR:Arkansas;CA:California;CO:Colorado;CT:Connecticut;DE:Delaware;DC:District of Columbia;FM:Federated States of Micronesia;FL:Florida;GA:Georgia;GU:Guam;HI:Hawaii;ID:Idaho;IL:Illinois;IN:Indiana;IA:Iowa;KS:Kansas;KY:Kentucky;LA:Louisiana;ME:Maine;MH:Marshall Islands;MD:Maryland;MA:Massachusetts;MI:Michigan;MN:Minnesota;MS:Mississippi;MO:Missouri;MT:Montana;NE:Nebraska;NV:Nevada;NH:New Hampshire;NJ:New Jersey;NM:New Mexico;NY:New York;NC:North Carolina;ND:North Dakota;OH:Ohio;OK:Oklahoma;OR:Oregon;PW:Palau;PA:Pennsylvania;PR:Puerto Rico;RI:Rhode Island;SC:South Carolina;SD:South Dakota;TN:Tennessee;TX:Texas;UT:Utah;VT:Vermont;VI:Virgin Island;VA:Virginia;WA:Washington;WV:West Virginia;WI:Wisconsin;WY:Wyoming"},formoptions:{elmsuffix:"(*)"}},
			{	name:'npi',
				index:'npi',
				width:1,
				editable:true,
				hidden:true,
				editrules:{edithidden:true, required:true},
				editoptions:{	dataInit:function(elem){
						$(elem).mask("9999999999");
					}
				},
				formoptions:{elmsuffix:"(*)"}
			},
			{name:'npi_taxonomy',index:'npi_taxonomy',width:1,editable:true,hidden:true,editrules:{edithidden:true},edittype:'text',editoptions:{readonly:'readonly'}},
			{name:'upin',index:'upin',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
			{	name:'dea',
				index:'dea',
				width:1,
				editable:true,
				hidden:true,
				editrules:{edithidden:true},
				editoptions:{	dataInit:function(elem){
						$(elem).mask("aa9999999");
					}
				}
			},
			{name:'medicare',index:'medicare',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
			{	name:'tax_id',
				index:'tax_id',
				width:1,
				editable:true,
				hidden:true,
				editrules:{edithidden:true},
				editoptions:{	dataInit:function(elem){
						$(elem).mask("99-9999999");
					}
				}
			},
			{name:'rcopia_username',index:'rcopia_username',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#provider_list_pager'),
		sortname: 'displayname',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Medical Providers",
	 	emptyrecords:"No medical providers",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#provider_list_pager',{edit:false,add:false,del:false
	});
	$("#add_provider").click(function(){
		jQuery("#provider_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.',afterComplete: function(response, postdata){
			var res = $.parseJSON(response.responseText);
			$("#user_id").val(res.id);
			$("#reset_password_dialog").dialog('open');
		}});
	});
	$("#edit_provider").click(function(){
		var item = jQuery("#provider_list").getGridParam('selrow');
		if(item){ 
			jQuery("#provider_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jGrowl("Please select provider to edit!");
		}
	});
	$("#disable_provider").click(function(){
		var item = jQuery("#provider_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/users/disable');?>",
				data: "id=" + item,
				success: function(data){
					jQuery("#provider_list").delRowData(item);
					jQuery("#provider_list").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select provider to inactivate!");
		}
	});
	$("#reset_password_provider").click(function(){
		var item = jQuery("#provider_list").getGridParam('selrow');
		if(item){
			$("#user_id").val(item);
			$("#reset_password_dialog").dialog('open');
		}
	});
	
	jQuery("#assistant_list").jqGrid({
		url:"<?php echo site_url('admin/users/users_list_assistant');?>",
		editurl:"<?php echo site_url('admin/users/edit_users_list_assistant');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail'],
		colModel:[
			{name:'id',index:'id',width:1,hidden:true},
			{name:'username',index:'username',width:400,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'password',index:'password',width:1,editable:false,hidden:true},
			{name:'firstname',index:'firstname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'middle',index:'middle',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'lastname',index:'lastname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'title',index:'title',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'displayname',index:'displayname',width:400},
			{name:'email',index:'email',width:1,editable:true,hidden:true,editrules:{edithidden:true, email:true, required:true},formoptions:{elmsuffix:"(*)"}}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#assistant_list_pager'),
		sortname: 'displayname',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Medical Assistants",
	 	emptyrecords:"No medical assistants",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#assistant_list_pager',{edit:false,add:false,del:false
	});
	$("#add_assistant").click(function(){
		jQuery("#assistant_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.',afterComplete: function(response, postdata){
			var res = $.parseJSON(response.responseText);
			$("#user_id").val(res.id);
			$("#reset_password_dialog").dialog('open');
		}});
	});
	$("#edit_assistant").click(function(){
		var item = jQuery("#assistant_list").getGridParam('selrow');
		if(item){ 
			jQuery("#assistant_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jGrowl("Please select assistant to edit!");
		}
	});
	$("#disable_assistant").click(function(){
		var item = jQuery("#assistant_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/users/disable');?>",
				data: "id=" + item,
				success: function(data){
					jQuery("#assistant_list").delRowData(item);
					jQuery("#assistant_list").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select assistant to inactivate!");
		}
	});
	$("#reset_password_assistant").click(function(){
		var item = jQuery("#assistant_list").getGridParam('selrow');
		if(item){
			$("#user_id").val(item);
			$("#reset_password_dialog").dialog('open');
		}
	});
	
	jQuery("#billing_list").jqGrid({
		url:"<?php echo site_url('admin/users/users_list_billing');?>",
		editurl:"<?php echo site_url('admin/users/edit_users_list_billing');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail'],
		colModel:[
			{name:'id',index:'id',width:1,hidden:true},
			{name:'username',index:'username',width:400,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'password',index:'password',width:1,editable:false,hidden:true},
			{name:'firstname',index:'firstname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'middle',index:'middle',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'lastname',index:'lastname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'title',index:'title',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'displayname',index:'displayname',width:400},
			{name:'email',index:'email',width:1,editable:true,hidden:true,editrules:{edithidden:true, email:true, required:true},formoptions:{elmsuffix:"(*)"}}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#billing_list_pager'),
		sortname: 'displayname',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Medical Billers",
	 	emptyrecords:"No medical billers",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#billing_list_pager',{edit:false,add:false,del:false
	});
	$("#add_billing").click(function(){
		jQuery("#billing_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.',afterComplete: function(response, postdata){
			var res = $.parseJSON(response.responseText);
			$("#user_id").val(res.id);
			$("#reset_password_dialog").dialog('open');
		}});
	});
	$("#edit_billing").click(function(){
		var item = jQuery("#billing_list").getGridParam('selrow');
		if(item){ 
			jQuery("#billing_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jGrowl("Please select biller to edit!");
		}
	});
	$("#disable_billing").click(function(){
		var item = jQuery("#billing_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/users/disable');?>",
				data: "id=" + item,
				success: function(data){
					jQuery("#billing_list").delRowData(item);
					jQuery("#billing_list").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select biller to inactivate!");
		}
	});
	$("#reset_password_billing").click(function(){
		var item = jQuery("#billing_list").getGridParam('selrow');
		if(item){
			$("#user_id").val(item);
			$("#reset_password_dialog").dialog('open');
		}
	});
	
	jQuery("#patient_list").jqGrid({
		url:"<?php echo site_url('admin/users/users_list_patient');?>",
		editurl:"<?php echo site_url('admin/users/edit_users_list_patient');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail','Associated Patient ID'],
		colModel:[
			{name:'id',index:'id',width:1,hidden:true},
			{name:'username',index:'username',width:400,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'password',index:'password',width:1,editable:false,hidden:true},
			{name:'firstname',index:'firstname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'middle',index:'middle',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'lastname',index:'lastname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'title',index:'title',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'displayname',index:'displayname',width:400},
			{name:'email',index:'email',width:1,editable:true,hidden:true,editrules:{edithidden:true, email:true, required:true},formoptions:{elmsuffix:"(*)"}},
			{	name:'pid',
				index:'pid',
				width:1,
				editable:true,
				hidden:true,
				editrules:{edithidden:true, required:true},
				editoptions:{	dataInit:function(elem){ 
									$(elem).autocomplete({
										source: function (req, add){
											$.ajax({
												url: "<?php echo site_url('search/pid');?>",
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
								}
				},
				formoptions:{elmsuffix:"(*)"}
			}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#patient_list_pager'),
		sortname: 'displayname',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Patients with Health Record Access",
	 	emptyrecords:"No patients",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#patient_list_pager',{edit:false,add:false,del:false
	});
	$("#add_patient").click(function(){
		jQuery("#patient_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.',afterComplete: function(response, postdata){
			var res = $.parseJSON(response.responseText);
			$("#user_id").val(res.id);
			$("#reset_password_dialog").dialog('open');
		}});
	});
	$("#edit_patient").click(function(){
		var item = jQuery("#patient_list").getGridParam('selrow');
		if(item){ 
			jQuery("#patient_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jGrowl("Please select patient to edit!");
		}
	});
	$("#disable_patient").click(function(){
		var item = jQuery("#patient_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/users/disable');?>",
				data: "id=" + item,
				success: function(data){
					jQuery("#patient_list").delRowData(item);
					jQuery("#patient_list").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select patient to inactivate!");
		}
	});
	$("#reset_password_patient").click(function(){
		var item = jQuery("#patient_list").getGridParam('selrow');
		if(item){
			$("#user_id").val(item);
			$("#reset_password_dialog").dialog('open');
		}
	});
	$("#reset_password_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 300, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#reset_password_password").focus();
		},
		buttons: {
			'Save': function() {
				var password = $("#reset_password_password");
				var bValid = true;
				bValid = bValid && checkEmpty(password,"Password");
				if (bValid) {
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('admin/users/reset_password');?>",
						data: "id="+ $("#user_id").val() + "&password=" + $("#reset_password_password").val(),
						success: function(data){
							$.jGrowl(data);
							$("#reset_password_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#reset_password_dialog").dialog('close');
			}
		},
		close: function(event, ui) {
			$("#reset_password_password").val('');
			$("#user_id").val('');
		}
	});
});
</script>
<div id="heading2"></div>
<div id="mainborder_full" class="ui-corner-all">
	<div id="maincontent_full">
		<h4>NOSH ChartingSystem Users</h4>
		<div id="noshtabs">
			<div id="users_admin_tabs">
				<ul>
					<li><a href="#users_tabs_1">Active Users</a></li>
					<li><?php echo anchor('admin/users/inactive/', 'Inactive Users');?></li>
				</ul>
				<div id="users_tabs_1">
					<table id="provider_list" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="provider_list_pager" class="scroll" style="text-align:center;"></div><br>
					<input type="button" id="add_provider" value="Add Provider" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="edit_provider" value="Edit Provider" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="disable_provider" value="Inactivate Provider" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="reset_password_provider" value="Reset Password" class="ui-button ui-state-default ui-corner-all"/><br><br>
					<table id="assistant_list" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="assistant_list_pager" class="scroll" style="text-align:center;"></div><br>
					<input type="button" id="add_assistant" value="Add Assistant" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="edit_assistant" value="Edit Assistant" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="disable_assistant" value="Inactivate Assistant" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="reset_password_assistant" value="Reset Password" class="ui-button ui-state-default ui-corner-all"/><br><br>
					<table id="billing_list" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="billing_list_pager" class="scroll" style="text-align:center;"></div><br>
					<input type="button" id="add_billing" value="Add Biller" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="edit_billing" value="Edit Biller" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="disable_billing" value="Inactivate Biller" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="reset_password_billing" value="Reset Password" class="ui-button ui-state-default ui-corner-all"/><br><br>
					<table id="patient_list" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="patient_list_pager" class="scroll" style="text-align:center;"></div><br>
					<input type="button" id="add_patient" value="Add Patient" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="edit_patient" value="Edit Patient" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="disable_patient" value="Inactivate Patient" class="ui-button ui-state-default ui-corner-all"/>
					<input type="button" id="reset_password_patient" value="Reset Password" class="ui-button ui-state-default ui-corner-all"/><br><br>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="reset_password_dialog" title="Reset Password">
	<input type="hidden" id="user_id"/>
	New Password:<br><input type="password" id="reset_password_password" style="width:164px" class="text ui-widget-content ui-corner-all"/>
</div>
