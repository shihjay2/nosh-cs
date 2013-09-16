<script type="text/javascript">
$(document).ready(function() {
	jQuery("#visit_type_list").jqGrid({
		url:"<?php echo site_url('admin/schedule/visit_type_list');?>",
		editurl:"<?php echo site_url('admin/schedule/edit_visit_type_list');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Visit Type','Duration','Color'],
		colModel:[
			{name:'calendar_id',index:'calendar_id',width:1,hidden:true},
			{name:'visit_type',index:'repeat_day',width:400,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'duration',index:'duration',width:1,hidden:true,editable:true,editrules:{edithidden:true, required:true},edittype:'select',editoptions:{value:"900:15 minutes;1200:20 minutes;1800:30 minutes;2400:40 minutes;2700:45 minutes;3600:60 minutes;4500:75 minutes;4800:80 minutes;5400:90 minutes;6000:100 minutes;6300:105 minutes;7200:120 minutes"},formoptions:{elmsuffix:"(*)"}},
			{name:'classname',index:'classname',width:400,editable:true,edittype:'select',formatter:colorlabel,editoptions:{value:"colorred:Red;colororange:Orange;coloryellow:Yellow;colorgreen:Green;colorblue:Blue;colorpurple:Purple;colorbrown:Brown"},formoptions:{elmsuffix:"(*)"}},
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#visit_type_list_pager'),
		sortname: 'visit_type',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Visit Types",
	 	emptyrecords:"No visits",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#visit_type_list_pager',{edit:false,add:false,del:false
	});
	function colorlabel (cellvalue, options, rowObject) {
		if(cellvalue=="colorred"){
			return "Red";
		}
		if(cellvalue=="colororange"){
			return "Orange";
		}
		if(cellvalue=="coloryellow"){
			return "Yellow"; mi
		}
		if(cellvalue=="colorgreen"){
			return "Green";
		}
		if(cellvalue=="colorblue"){
			return "Blue";
		}
		if(cellvalue=="colorpurple"){
			return "Purple";
		}
		if(cellvalue=="colorbrown"){
			return "Brown"
		}
		if(cellvalue=="colorblack"){
			return "Black"
		}
	}
	$("#add_visit_type").click(function(){
		jQuery("#visit_type_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.'});	
	});
	
	$("#edit_visit_type").click(function(){
		var item = jQuery("#visit_type_list").getGridParam('selrow');
		if(item){ 
			jQuery("#visit_type_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jGrowl("Please select visit type to edit!");
		}
	});
	
	$("#delete_visit_type").click(function(){
		var item = jQuery("#visit_type_list").getGridParam('selrow');
		if(item){ 
			alert(item);
			jQuery("#visit_type_list").delGridRow(item);
			jQuery("#visit_type_list").delRowData(item);
		} else {
			$.jGrowl("Please select visit type to delete!");
		}
	});
});
</script>

<table id="visit_type_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="visit_type_list_pager" class="scroll" style="text-align:center;"></div><br>
<input type="button" id="add_visit_type" value="Add Visit Type" class="ui-button ui-state-default ui-corner-all"/>
<input type="button" id="edit_visit_type" value="Edit Visit Type" class="ui-button ui-state-default ui-corner-all"/>
<input type="button" id="delete_visit_type" value="Delete Visit Type" class="ui-button ui-state-default ui-corner-all"/>
