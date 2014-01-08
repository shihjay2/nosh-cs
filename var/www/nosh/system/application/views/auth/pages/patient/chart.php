<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load('<?php echo site_url("search/loadpage");?>');
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("patient/chartmenu");?>',
		redirect_url: '<?php echo site_url("logout");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: false
	});
	$("#documents_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		close: function(event, ui) {
			var a = $("#document_filepath").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/close_document');?>",
				data: "document_filepath=" + a,
				success: function(data){
					$("#embedURL").html('');
					$("#document_filepath").val('');
					$("#view_document_id").val('');
				}
			});	
		}
	});
	$("#save_document").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#save_document").click(function() {
		var id = $("#view_document_id").val();
		window.open("<?php echo site_url('patient/chartmenu/view_documents');?>/" + id);
	});
	$("#documents_list").click(function() {
		$("#documents_list_dialog").dialog('open');
	});
	jQuery("#labs").jqGrid({
		url:"<?php echo site_url('patient/chartmenu/labs/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','From','Description','Type','URL'],
		colModel:[
			{name:'documents_id',index:'documents_id',width:1,hidden:true},
			{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'documents_from',index:'documents_from',width:300},
			{name:'documents_desc',index:'documents_desc',width:355},
			{name:'documents_type',index:'documents_type',width:1,hidden:true},
			{name:'documents_url',index:'documents_url',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#pager8'),
		sortname: 'documents_date',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Labs",
	 	hiddengrid: true,
	 	height: "100%",
	 	onSelectRow: function(id){
	 		$("#view_document_id").val(id);
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/view_documents1');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#embedURL").html(data.html);
					$("#document_filepath").val(data.filepath);
					$("#documents_view_dialog").dialog('open');
				}
			});
	 	},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#pager8',{search:false,edit:false,add:false,del:false});
	jQuery("#radiology").jqGrid({
		url:"<?php echo site_url ('patient/chartmenu/radiology/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','From','Description','Type','URL'],
		colModel:[
			{name:'documents_id',index:'documents_id',width:1,hidden:true},
			{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'documents_from',index:'documents_from',width:300},
			{name:'documents_desc',index:'documents_desc',width:355},
			{name:'documents_type',index:'documents_type',width:1,hidden:true},
			{name:'documents_url',index:'documents_url',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#pager9'),
		sortname: 'documents_date',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Imaging",
	 	hiddengrid: true,
	 	height: "100%",
	 	onSelectRow: function(id){
	 		$("#view_document_id").val(id);
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/view_documents1');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#embedURL").html(data.html);
					$("#document_filepath").val(data.filepath);
					$("#documents_view_dialog").dialog('open');
				}
			});
	 	},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#pager9',{search:false,edit:false,add:false,del:false});
	jQuery("#cardiopulm").jqGrid({
		url:"<?php echo site_url('patient/chartmenu/cardiopulm/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','From','Description','Type','URL'],
		colModel:[
			{name:'documents_id',index:'documents_id',width:1,hidden:true},
			{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'documents_from',index:'documents_from',width:300},
			{name:'documents_desc',index:'documents_desc',width:355},
			{name:'documents_type',index:'documents_type',width:1,hidden:true},
			{name:'documents_url',index:'documents_url',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#pager10'),
		sortname: 'documents_date',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Cardiopulmonary",
	 	hiddengrid: true,
	 	height: "100%",
	 	onSelectRow: function(id){
	 		$("#view_document_id").val(id);
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/view_documents1');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#embedURL").html(data.html);
					$("#document_filepath").val(data.filepath);
					$("#documents_view_dialog").dialog('open');
				}
			});
	 	},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#pager10',{search:false,edit:false,add:false,del:false});
	jQuery("#endoscopy").jqGrid({
		url:"<?php echo site_url('patient/chartmenu/endoscopy/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','From','Description','Type','URL'],
		colModel:[
			{name:'documents_id',index:'documents_id',width:1,hidden:true},
			{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'documents_from',index:'documents_from',width:300},
			{name:'documents_desc',index:'documents_desc',width:355},
			{name:'documents_type',index:'documents_type',width:1,hidden:true},
			{name:'documents_url',index:'documents_url',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#pager11'),
		sortname: 'documents_date',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Endoscopy",
	 	hiddengrid: true,
	 	height: "100%",
	 	onSelectRow: function(id){
	 		$("#view_document_id").val(id);
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/view_documents1');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#embedURL").html(data.html);
					$("#document_filepath").val(data.filepath);
					$("#documents_view_dialog").dialog('open');
				}
			});
	 	},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#pager11',{search:false,edit:false,add:false,del:false});
	jQuery("#letters").jqGrid({
		url:"<?php echo site_url('patient/chartmenu/letters/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','From','Description','Type','URL'],
		colModel:[
			{name:'documents_id',index:'documents_id',width:1,hidden:true},
			{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'documents_from',index:'documents_from',width:300},
			{name:'documents_desc',index:'documents_desc',width:355},
			{name:'documents_type',index:'documents_type',width:1,hidden:true},
			{name:'documents_url',index:'documents_url',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#pager15'),
		sortname: 'documents_date',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Letters",
	 	hiddengrid: true,
	 	height: "100%",
	 	onSelectRow: function(id){
	 		$("#view_document_id").val(id);
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/view_documents1');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#embedURL").html(data.html);
					$("#document_filepath").val(data.filepath);
					$("#documents_view_dialog").dialog('open');
				}
			});
	 	},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#pager15',{search:false,edit:false,add:false,del:false});
	jQuery("#encounters").jqGrid({
		url:"<?php echo site_url('patient/chartmenu/encounters/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','Chief Complaint'],
		colModel:[
			{name:'eid',index:'eid',width:1,hidden:true},
			{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'encounter_cc',index:'encounter_cc',width:660}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#encounters_pager'),
		sortname: 'encounter_DOS',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Encounters - Click on encounter to view your instructions for this visit.",
	 	height: "100%",
	 	onSelectRow: function(id) {
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/view_instructions');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#embedURL").html(data.html);
					$("#document_filepath").val(data.filepath);
					$("#documents_view_dialog").dialog('open');
				}
			});
	 	},
	 	hiddengrid: true,
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#encounters_pager',{search:false,edit:false,add:false,del:false});
	jQuery("#medications").jqGrid({
		url:"<?php echo site_url('patient/chartmenu/medications/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Active','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason'],
		colModel:[
			{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
			{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'rxl_medication',index:'rxl_medication',width:280},
			{name:'rxl_dosage',index:'rxl_dosage',width:50},
			{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
			{name:'rxl_sig',index:'rxl_sig',width:50},
			{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
			{name:'rxl_frequency',index:'rxl_frequency',width:105},
			{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
			{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#medications_pager'),
		sortname: 'rxl_date_active',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Medications",
	 	height: "100%",
	 	onSelectRow: function(id){
	 		var med = jQuery("#medications").getCell(id,'rxl_medication');
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/past_medication');?>",
				data: "rxl_medication=" + med,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.item, {sticky:true, header:data.header});
				}
			});
		},
		hiddengrid: true,
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#medications_pager',{search:false,edit:false,add:false,del:false});
	jQuery("#supplements").jqGrid({
		url:"<?php echo site_url('patient/chartmenu/supplements/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Active','Supplement','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason','Supplement ID'],
		colModel:[
			{name:'sup_id',index:'sup_id',width:1,hidden:true},
			{name:'sup_date_active',index:'sup_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'sup_supplement',index:'sup_supplement',width:385},
			{name:'sup_dosage',index:'sup_dosage',width:50},
			{name:'sup_dosage_unit',index:'sup_dosage_unit',width:50},
			{name:'sup_sig',index:'sup_sig',width:50},
			{name:'sup_route',index:'sup_route',width:1,hidden:true},
			{name:'sup_frequency',index:'sup_frequency',width:105},
			{name:'sup_instructions',index:'sup_instructions',width:1,hidden:true},
			{name:'sup_reason',index:'sup_reason',width:1,hidden:true},
			{name:'supplement_id',index:'supplement_id',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#supplements_pager'),
		sortname: 'sup_date_active',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Supplements",
	 	height: "100%",
	 	hiddengrid: true,
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#supplements_pager',{search:false,edit:false,add:false,del:false});
	jQuery("#allergies").jqGrid({
		url:"<?php echo site_url('patient/chartmenu/allergies/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Active','Medication','Reason'],
		colModel:[
			{name:'allergies_id',index:'allergies_id',width:1,hidden:true},
			{name:'allergies_date_active',index:'allergies_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'allergies_med',index:'allergies_med',width:335},
			{name:'allergies_reaction',index:'allergies_reaction',width:320}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#allergies_pager'),
		sortname: 'allergies_date_active',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Allergies",
	 	height: "100%",
	 	hiddengrid: true,
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#allergies_pager',{search:false,edit:false,add:false,del:false});
	jQuery("#immunizations").jqGrid({
		url:"<?php echo site_url('patient/chartmenu/immunizations/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Given','Immunization','Sequence','Given Elsewhere','Body Site','Dosage','Unit','Route','Lot Number','Manufacturer','Expiration Date','VIS'],
		colModel:[
			{name:'imm_id',index:'imm_id',width:1,hidden:true},
			{name:'imm_date',index:'imm_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'imm_immunization',index:'imm_immunization',width:435},
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
	 	sortorder: "asc",
	 	caption:"Immunizations",
	 	height: "100%",
	 	hiddengrid: true,
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#immunizations_pager',{search:false,edit:false,add:false,del:false});
});
</script>
<div id="heading2"></div>
<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="maincontent_full">
		<h4>NOSH ChartingSystem Personal Chart</h4>
		<div id="documents_list_dialog">
			<fieldset class="ui-corner-all" style="width:750px">
				<legend>Instructions</legend>
				<ol>
					<li>Click on each header to expand the grid.</li>
					<li>Click on an item in the grid to view the document.</li>
				</ol>
			</fieldset>
			<br>
			<table id="encounters" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="encounters_pager" class="scroll" style="text-align:center;"></div>
			<br>
			<table id="medications" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="medications_pager" class="scroll" style="text-align:center;"></div>
			<br>
			<table id="supplements" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="supplements_pager" class="scroll" style="text-align:center;"></div>
			<br>
			<table id="allergies" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="allergies_pager" class="scroll" style="text-align:center;"></div>
			<br>
			<table id="immunizations" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="immunizations_pager" class="scroll" style="text-align:center;"></div>
			<br>
			<table id="labs" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="pager8" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="radiology" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="pager9" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="cardiopulm" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="pager10" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="endoscopy" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="pager11" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="letters" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="pager15" class="scroll" style="text-align:center;"></div> 
			<br>
		</div>
	</div>
</div>
<div id="documents_view_dialog" title="Documents Viewer">
	<input type="hidden" id="view_document_id"/>
	<input type="hidden" id="document_filepath"/>
	<button type="button" id="save_document">Save</button><br>
	<div id="embedURL"></div>
</div>
