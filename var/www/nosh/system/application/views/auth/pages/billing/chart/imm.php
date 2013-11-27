<div id="immunizations_list_dialog" title="Immunizations">
	<table id="immunizations" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="immunizations_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<script type="text/javascript">
	$("#immunizations_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#immunizations").jqGrid('GridUnload');
			jQuery("#immunizations").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/immunizations/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Given','Immunization','Sequence','Given Elsewhere','Body Site','Dosage','Unit','Route','Lot Number','Manufacturer','Expiration Date','VIS'],
				colModel:[
					{name:'imm_id',index:'imm_id',width:1,hidden:true},
					{name:'imm_date',index:'imm_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'imm_immunization',index:'imm_immunization',width:410},
					{name:'imm_sequence',index:'imm_sequence',width:65},
					{name:'imm_elsewhere',index:'imm_elsewhere',width:150},
					{name:'imm_body_site',index:'imm_body_site',width:1,hidden:true},
					{name:'imm_dosage',index:'imm_dosage',width:1,hidden:true},
					{name:'imm_dosage_unit',index:'imm_dosage_unit',width:1,hidden:true},
					{name:'imm_route',index:'imm_route',width:1,hidden:true},
					{name:'imm_lot',index:'imm_lot',width:1,hidden:true},
					{name:'imm_manufacturer',index:'imm_manufacturer',width:1,hidden:true},
					{name:'imm_expiration',index:'imm_expiration',width:1,hidden:true},
					{name:'imm_vis',index:'imm_vis',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#immunizations_pager'),
				sortname: 'imm_immunization',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Immunizations",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#immunizations_pager',{search:false,edit:false,add:false,del:false});
		}
	});
	$("#immunizations_list").click(function() {
		$("#immunizations_list_dialog").dialog('open');
		$('#imm_order').hide('fast');
		$('#imm_menu').show('fast');
	});
	$('#cpnsent_immunization1').button();
</script>
