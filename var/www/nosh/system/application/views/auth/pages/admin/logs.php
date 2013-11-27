<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load("<?php echo site_url("search/loadpage");?>");
	jQuery("#audit_list").jqGrid({
		url:"<?php echo site_url('admin/logs/audit');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','User ID','Date','User','Group','Patient ID','Action','Query'],
		colModel:[
			{name:'audit_id',index:'audit_id',width:1,hidden:true,editrules : {edithidden:true}},
			{name:'user_id',index:'user_id',width:1,hidden:true,editrules : {edithidden:true}},
			{name:'timestamp',index:'timestamp',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'displayname',index:'displayname',width:200},
			{name:'group_id',index:'group_id',width:1,hidden:true,editrules : {edithidden:true}},
			{name:'pid',index:'pid',width:50},
			{name:'action',index:'action',width:100},
			{name:'query',index:'query',width:300}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#audit_list_pager'),
		sortname: 'timestamp',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Audit Log",
	 	height: "100%",
	 	onCellSelect: function(id,iCol) {
			if (iCol > 0) {
				jQuery("#audit_list").viewGridRow(id,{width:500, labelswidth:"35%"});
			}
		},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#audit_list_pager',{edit:false,add:false,del:false
	});
	jQuery("#extensions_list").jqGrid({
		url:"<?php echo site_url('admin/logs/extensions');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','Patient ID','Name','Action','Description'],
		colModel:[
			{name:'extensions_id',index:'extensions_id',width:1,hidden:true,editrules : {edithidden:true}},
			{name:'timestamp',index:'timestamp',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'pid',index:'pid',width:50},
			{name:'extensions_name',index:'extensions_name',width:200},
			{name:'action',index:'action',width:200},
			{name:'description',index:'description',width:200}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#extensions_list_pager'),
		sortname: 'timestamp',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Extensions Log",
	 	height: "100%",
	 	onCellSelect: function(id,iCol) {
			if (iCol > 0) {
				jQuery("#extensions_list").viewGridRow(id,{width:500, labelswidth:"35%"});
			}
		},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#extensions_list_pager',{edit:false,add:false,del:false
	});
});
</script>
<div id ="heading2"></div>
<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="maincontent_full">
	<h4>Logs</h4>
		<div id="noshtabs">
			<table id="audit_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="audit_list_pager" class="scroll" style="text-align:center;"></div><br>
			<table id="extensions_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="extensions_list_pager" class="scroll" style="text-align:center;"></div><br>
		</div>
	</div>
</div>
