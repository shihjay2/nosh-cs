<div id="issues_list_dialog" title="Issues">
	<table id="issues" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="issues_pager" class="scroll" style="text-align:center;"></div><br>
	<table id="issues_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="issues_inactive_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<script type="text/javascript">
	$("#issue").tooltip({
		items: "#issue",
		content: "Use a comma to separate distinct search terms."
	});
	$("#issues_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function(event,ui) {
			jQuery("#issues").jqGrid('GridUnload');
			jQuery("#issues").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/issues/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Issue'],
				colModel:[
					{name:'issue_id',index:'issue_id',width:1,hidden:true},
					{name:'issue_date_active',index:'issue_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'issue',index:'issue',width:635}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#issues_pager'),
				sortname: 'issue_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Issues",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#issues_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#issues_inactive").jqGrid('GridUnload');
			jQuery("#issues_inactive").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/issues_inactive/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Issue'],
				colModel:[
					{name:'issue_id',index:'issue_id',width:1,hidden:true},
					{name:'issue_date_active',index:'issue_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'issue',index:'issue',width:635}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#issues_inactive_pager'),
				sortname: 'issue_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption: "Inactive Issues",
			 	hiddengrid: true,
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#issues_inactive_pager',{search:false,edit:false,add:false,del:false});
		}
	});
	$("#issues_list").click(function() {
		$("#issues_list_dialog").dialog('open');
	});
</script>
