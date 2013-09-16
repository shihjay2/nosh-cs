<script type="text/javascript">
var timeoutHnd;
	
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd);
		timeoutHnd = setTimeout(gridReload,500);
}

function gridReload(){ 
	var mask = jQuery("#search_all_cpt").val();
	jQuery("#cpt_list").setGridParam({url:"<?php echo site_url('admin/setup/cpt_list');?>/"+mask,page:1}).trigger("reloadGrid");
}

$(document).ready(function() {
	$("#add_cpt_upload").button();
	jQuery("#cpt_list").jqGrid({
		url:"<?php echo site_url('admin/setup/cpt_list');?>",
		editurl:"<?php echo site_url('admin/setup/edit_cpt_list');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','CPT Code','Description','Charge'],
		colModel:[
			{name:'cpt_id',index:'cpt_id',width:1,hidden:true},
			{name:'cpt',index:'cpt',width:100,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'cpt_description',index:'cpt_description',width:700,editable:true,editrules:{required:true},edittype:"textarea",editoptions:{rows:"4",cols:"50"},formoptions:{elmsuffix:"(*)"}},
			{name:'cpt_charge',index:'cpt_charge',width:100,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#cpt_list_pager'),
		sortname: 'cpt',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"CPT Codes",
	 	emptyrecords:"No CPT codes",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#cpt_list_pager',{edit:false,add:false,del:false
	});
	$("#add_cpt").button().click(function(){
		jQuery("#cpt_list").editGridRow("new",{closeAfterAdd:true,width:'550',bottominfo:'Fields marked in (*) are required.'});	
	});
	$("#edit_cpt").button().click(function(){
		var item = jQuery("#cpt_list").getGridParam('selrow');
		if(item){ 
			jQuery("#cpt_list").editGridRow(item,{closeAfterEdit:true,width:'550',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jGrowl("Please select CPT code to edit!");
		}
	});
	$("#delete_cpt").button().click(function(){
		var item = jQuery("#cpt_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/setup/delete_cpt');?>",
				data: "cpt_id=" + item,
				success: function(data){
					jQuery("#cpt_list").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select CPT code to delete!");
		}
	});
});
</script>
<img id="loading" src="<?php echo base_url().'images/indicator.gif';?>" style="display:none;">
<h3>CPT Codes Database:</h3>
Search: <input type="text" size="50" id="search_all_cpt" class="text ui-widget-content ui-corner-all" onkeydown="doSearch(arguments[0]||event)"/><br><br> 
<table id="cpt_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="cpt_list_pager" class="scroll" style="text-align:center;"></div><br>
<input type="button" id="add_cpt" value="Add CPT Code"/>
<input type="button" id="edit_cpt" value="Edit CPT Code"/>
<input type="button" id="delete_cpt" value="Delete CPT Code"/>
<hr />
<h3>Import CPT Codes:</h3>
Obtain the AMA CPT Data File disk that you have purchased.  Import the LONGULT.TXT file into database (format: [cpt code] [cpt description])<br><br>
<form id="add_cpt_form" action="<?php echo site_url('admin/setup/cpt_update');?>" method="post" enctype="multipart/form-data">
	<span id="add_cpt_span"><input type="file" name="fileToUpload" id="fileToUpload"> <input type="submit" id="add_cpt_upload" value="Upload"></span>
</form>
