<script type="text/javascript">
$(document).ready(function() {
	jQuery("#provider_list_inactive").jqGrid({
		url:"<?php echo site_url('admin/users/users_list_provider_inactive');?>",
		editurl:"<?php echo site_url('admin/users/enable');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail','Specialty','License Number','State Licensed','NPI','NPI Taxonomy Code','UPIN','DEA Number','Medicare Number','Tax ID Number'],
		colModel:[
			{name:'id',index:'id',width:1,hidden:true},
			{name:'username',index:'username',width:400},
			{name:'password',index:'password',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},edittype:'password',formoptions:{elmsuffix:"Required"}},
			{name:'firstname',index:'firstname',width:1,hidden:true},
			{name:'middle',index:'middle',width:1,hidden:true},
			{name:'lastname',index:'lastname',width:1,hidden:true},
			{name:'title',index:'title',width:1,hidden:true},
			{name:'displayname',index:'displayname',width:400},
			{name:'email',index:'email',width:1,hidden:true},
			{name:'specialty',index:'specialty',width:1,hidden:true},
			{name:'license',index:'license',width:1,hidden:true},
			{name:'license_state',index:'license_state',width:1,hidden:true},
			{name:'npi',index:'npi',width:1,hidden:true},
			{name:'npi_taxonomy',index:'npi_taxonomy',width:1,hidden:true},
			{name:'upin',index:'upin',width:1,hidden:true},
			{name:'dea',index:'dea',width:1,hidden:true},
			{name:'medicare',index:'medicare',width:1,hidden:true},
			{name:'tax_id',index:'tax_id',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#provider_list_inactive_pager'),
		sortname: 'lastname',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Inactive Medical Providers",
	 	emptyrecords:"No inactive medical providers",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#provider_list_inactive_pager',{search:false,edit:false,add:false,del:false
	});
	
	$("#view_provider").click(function(){
		var item = jQuery("#provider_list_inactive").getGridParam('selrow');
		if(item){ 
			jQuery("#provider_list_inactive").viewGridRow(item);
		} else {
			$.jgrowl("Please select inactive provider to view!");
		}
	});
	
	$("#enable_provider").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/users/check_admin');?>",
			success: function(data){
				if (data == "OK") {
					var item = jQuery("#provider_list_inactive").getGridParam('selrow');
					if(item){
						jQuery("#provider_list_inactive").editGridRow(item,{closeAfterEdit:true});
						$("#password").val('');
						jQuery("#provider_list_inactive").trigger("reloadGrid");
					} else {
						$.jgrowl("Please select provider to reactivate!");
					}
				} else {
					$.jGrowl(data);
					$("#practice_upgrade1").show();
				}
			}
		});
	});
	
	jQuery("#assistant_list_inactive").jqGrid({
		url:"<?php echo site_url('admin/users/users_list_assistant_inactive');?>",
		editurl:"<?php echo site_url('admin/users/enable');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail'],
		colModel:[
			{name:'id',index:'id',width:1,hidden:true},
			{name:'username',index:'username',width:400},
			{name:'password',index:'password',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},edittype:'password',formoptions:{elmsuffix:"Required"}},
			{name:'firstname',index:'firstname',width:1,hidden:true},
			{name:'middle',index:'middle',width:1,hidden:true},
			{name:'lastname',index:'lastname',width:1,hidden:true},
			{name:'title',index:'title',width:1,hidden:true},
			{name:'displayname',index:'displayname',width:400},
			{name:'email',index:'email',width:1,editable:true,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#assistant_list_inactive_pager'),
		sortname: 'lastname',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Inactive Medical Assistants",
	 	emptyrecords:"No inactivemedical assistants",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#assistant_list_inactive_pager',{search:false,edit:false,add:false,del:false
	});
	
	$("#view_assistant").click(function(){
		var item = jQuery("#assistant_list_inactive").getGridParam('selrow');
		if(item){ 
			jQuery("#assistant_list_inactive").viewGridRow(item);
		} else {
			$.jgrowl("Please select inactive assistant to view!");
		}
	});
	
	$("#enable_assistant").click(function(){
		var item = jQuery("#assistant_list_inactive").getGridParam('selrow');
		if(item){
			jQuery("#assistant_list_inactive").editGridRow(item,{closeAfterEdit:true});
			$("#password").val('');
			jQuery("#assistant_list_inactive").trigger("reloadGrid");
		} else {
			$.jgrowl("Please select assistant to reactivate!");
		}
	});
	
	jQuery("#billing_list_inactive").jqGrid({
		url:"<?php echo site_url('admin/users/users_list_billing_inactive');?>",
		editurl:"<?php echo site_url('admin/users/enable');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail'],
		colModel:[
			{name:'id',index:'id',width:1,hidden:true},
			{name:'username',index:'username',width:400},
			{name:'password',index:'password',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},edittype:'password',formoptions:{elmsuffix:"Required"}},
			{name:'firstname',index:'firstname',width:1,hidden:true},
			{name:'middle',index:'middle',width:1,hidden:true},
			{name:'lastname',index:'lastname',width:1,hidden:true},
			{name:'title',index:'title',width:1,hidden:true},
			{name:'displayname',index:'displayname',width:400},
			{name:'email',index:'email',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#billing_list_inactive_pager'),
		sortname: 'lastname',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Inactive Medical Billers",
	 	emptyrecords:"No medical inactive billers",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#billing_list_inactive_pager',{search:false,edit:false,add:false,del:false
	});
	
	$("#view_billing").click(function(){
		var item = jQuery("#billing_list_inactive").getGridParam('selrow');
		if(item){ 
			jQuery("#billing_list_inactive").viewGridRow(item);
		} else {
			$.jgrowl("Please select inactive biller to view!");
		}
	});
	
	$("#enable_billing").click(function(){
		var item = jQuery("#billing_list_inactive").getGridParam('selrow');
		if(item){
			jQuery("#billing_list_inactive").editGridRow(item,{closeAfterEdit:true});
			$("#password").val('');
			jQuery("#billing_list_inactive").trigger("reloadGrid");
		} else {
			$.jgrowl("Please select biller to reactivate!");
		}
	});
	
	jQuery("#patient_list_inactive").jqGrid({
		url:"<?php echo site_url('admin/users/users_list_patient_inactive');?>",
		editurl:"<?php echo site_url('admin/users/enable');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail','Associated Patient ID'],
		colModel:[
			{name:'id',index:'id',width:1,hidden:true},
			{name:'username',index:'username',width:400},
			{name:'password',index:'password',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},edittype:'password',formoptions:{elmsuffix:"Required"}},
			{name:'firstname',index:'firstname',width:1,hidden:true},
			{name:'middle',index:'middle',width:1,hidden:true},
			{name:'lastname',index:'lastname',width:1,hidden:true},
			{name:'title',index:'title',width:1,hidden:true},
			{name:'displayname',index:'displayname',width:400},
			{name:'email',index:'email',width:1,hidden:true},
			{name:'pid',index:'pid',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#patient_list_inactive_pager'),
		sortname: 'lastname',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Inactive Patients with Health Record Access",
	 	emptyrecords:"No inactive patients",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#patient_list_inactive_pager',{search:false,edit:false,add:false,del:false
	});
	
	$("#view_patient").click(function(){
		var item = jQuery("#patient_list_inactive").getGridParam('selrow');
		if(item){ 
			jQuery("#patient_list_inactive").viewGridRow(item);
		} else {
			$.jgrowl("Please select inactive patient to view!");
		}
	});
	
	$("#enable_patient").click(function(){
		var item = jQuery("#patient_list_inactive").getGridParam('selrow');
		if(item){
			jQuery("#patient_list_inactive").editGridRow(item,{closeAfterEdit:true});
			$("#password").val('');
			jQuery("#patient_list_inactive").trigger("reloadGrid");
		} else {
			$.jgrowl("Please select patient to reactivate!");
		}
	});	
});
</script>
<div id="practice_upgrade1" style="display:none;"><a href="<?php echo site_url('registerpractice/' . $this->session->userdata('practice_id'));?>">Upgrade your practice for more providers.</a></div>
<table id="provider_list_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="provider_list_inactive_pager" class="scroll" style="text-align:center;"></div><br>
<input type="button" id="view_provider" value="View Details" class="ui-button ui-state-default ui-corner-all"/>
<input type="button" id="enable_provider" value="Reactivate Provider" class="ui-button ui-state-default ui-corner-all"/><br><br>
<table id="assistant_list_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="assistant_list_inactive_pager" class="scroll" style="text-align:center;"></div><br>
<input type="button" id="view_assistant" value="View Details" class="ui-button ui-state-default ui-corner-all"/>
<input type="button" id="enable_assistant" value="Reactivate Assistant" class="ui-button ui-state-default ui-corner-all"/><br><br>
<table id="billing_list_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="billing_list_inactive_pager" class="scroll" style="text-align:center;"></div><br>
<input type="button" id="view_billing" value="View Details" class="ui-button ui-state-default ui-corner-all"/>
<input type="button" id="enable_billing" value="Reactivate Biller" class="ui-button ui-state-default ui-corner-all"/><br><br>
<table id="patient_list_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="patient_list_inactive_pager" class="scroll" style="text-align:center;"></div><br>
<input type="button" id="view_patient" value="View Details" class="ui-button ui-state-default ui-corner-all"/>
<input type="button" id="enable_patient" value="Reactivate Patient" class="ui-button ui-state-default ui-corner-all"/><br><br>
