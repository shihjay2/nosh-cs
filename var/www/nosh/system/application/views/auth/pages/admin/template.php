<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load("<?php echo site_url("search/loadpage");?>");
	jQuery("#familyhx_list").jqGrid({
		url:"<?php echo site_url('admin/template/familyhxlist');?>",
		editurl:"<?php echo site_url('admin/template/edit_familyhxlist');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Category','Item'],
		colModel:[
			{name:'familyhxlist_id',index:'familyhxlist_id',width:1,hidden:true},
			{name:'familyhxlist_category',index:'familyhxlist_category',width:400,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'familyhxlist_item',index:'familyhxlist_item',width:400,editable:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#familyhx_list_pager'),
		sortname: 'familyhxlist_category',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Family History Global Templates",
	 	emptyrecords:"No templates",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#familyhx_list_pager',{edit:false,add:false,del:false
	});
	
	$("#add_familyhx").click(function(){
		jQuery("#familyhx_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.'});	
	});
	
	$("#edit_familyhx").click(function(){
		var item = jQuery("#familyhx_list").getGridParam('selrow');
		if(item){ 
			jQuery("#familyhx_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jgrowl("Please select template to edit!");
		}
	});
	
	$("#delete_familyhx").click(function(){
		var item = jQuery("#familyhx_list").getGridParam('selrow');
		if(item){ 
			jQuery("#familyhx_list").delGridRow(item);
			jQuery("#familyhx_list").delRowData(item);
		} else {
			$.jgrowl("Please select template to delete!");
		}
	});
	
	jQuery("#procedure_list").jqGrid({
		url:"<?php echo site_url('admin/template/procedurelist');?>",
		editurl:"<?php echo site_url('admin/template/edit_procedurelist');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Type','Description','Complications','Estimated Blood Loss'],
		colModel:[
			{name:'procedurelist_id',index:'procedurelist_idid',width:1,hidden:true},
			{name:'procedure_type',index:'procedure_type',width:800,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'procedure_description',index:'procedure_description',width:1,editable:true,hidden:true,edittype:'textarea',editrules:{edithidden:true,required:true},editoptions:{rows:'4',cols:'20'},formoptions:{elmsuffix:"(*)"}},
			{name:'procedure_complications',index:'procedure_complications',width:1,editable:true,hidden:true,edittype:'textarea',editrules:{edithidden:true,required:true},editoptions:{rows:'4',cols:'20'},formoptions:{elmsuffix:"(*)"}},
			{name:'procedure_ebl',index:'procedure_ebl',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#procedure_list_pager'),
		sortname: 'procedure_type',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Procedure Global Templates",
	 	emptyrecords:"No templates",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#procedure_list_pager',{edit:false,add:false,del:false
	});
	
	$("#add_procedure").click(function(){
		jQuery("#procedure_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.'});	
	});
	
	$("#edit_procedure").click(function(){
		var item = jQuery("#procedure_list").getGridParam('selrow');
		if(item){ 
			jQuery("#procedure_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jgrowl("Please select template to edit!");
		}
	});
	
	$("#delete_procedure").click(function(){
		var item = jQuery("#procedure_list").getGridParam('selrow');
		if(item){ 
			jQuery("#procedure_list").delGridRow(item);
		} else {
			$.jgrowl("Please select template to delete!");
		}
	});
	
	jQuery("#orders_list").jqGrid({
		url:"<?php echo site_url('admin/template/orderslist');?>",
		editurl:"<?php echo site_url('admin/template/edit_orderslist');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Category','Description'],
		colModel:[
			{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
			{name:'orders_category',index:'procedure_type',width:400,editable:true,edittype:'select',editrules:{required:true},editoptions:{value:"Laboratory:Laboratory; Radiology:Radiology"},formoptions:{elmsuffix:"(*)"}},
			{name:'orders_description',index:'procedure_description',width:400,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#orders_list_pager'),
		sortname: 'orders_category',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Orders Global Templates",
	 	emptyrecords:"No templates",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#orders_list_pager',{edit:false,add:false,del:false
	});
	
	$("#add_order").click(function(){
		jQuery("#orders_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.'});	
	});
	
	$("#edit_order").click(function(){
		var item = jQuery("#orders_list").getGridParam('selrow');
		if(item){ 
			jQuery("#orders_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jgrowl("Please select template to edit!");
		}
	});
	
	$("#delete_order").click(function(){
		var item = jQuery("#orders_list").getGridParam('selrow');
		if(item){ 
			jQuery("#orders_list").delGridRow(item);
		} else {
			$.jgrowl("Please select template to delete!");
		}
	});
	$(".template-button").button();
});
</script>

<div id ="heading2"></div>

<div id="mainborder_full">
	<div id="maincontent_full">
	<h4>Global Templates</h4>
		<div id="noshtabs">
			<table id="procedure_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="procedure_list_pager" class="scroll" style="text-align:center;"></div><br>
			<input type="button" id="add_procedure" value="Add Procedure" class="template-button"/>
			<input type="button" id="edit_procedure" value="Edit Procedure" class="template-button"/>
			<input type="button" id="delete_procedure" value="Delete Procedure" class="template-button"/><br><br>
			<table id="orders_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="orders_list_pager" class="scroll" style="text-align:center;"></div><br>
			<input type="button" id="add_order" value="Add Order" class="template-button"/>
			<input type="button" id="edit_order" value="Edit Order" class="template-button"/>
			<input type="button" id="delete_order" value="Delete Order" class="template-button"/><br><br>
		</div>
	</div>
</div>
