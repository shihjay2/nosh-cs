function radioselect(form, radioname, groupname){
	var x = document.getElementsByName(radioname);
	for (i=0;i<x.length;i++) {
		if (x[i].checked) {
			break
		}
	}
	var p = document.getElementById(groupname);
	if (p.value=="") {
		var t=x[i].value
	}
	else {
		var t=p.value
		t=t+"\r\n"+x[i].value
	}
	p.value=t
}

function clearthis(form, divid, groupname){
	var x = document.getElementById(divid);
	var y = x.getElementsByTagName("input");
	for (i=0;i<y.length; i++) {
		field_type = y[i].type.toLowerCase();
		switch(field_type) {
			case "text":
			case "password":
			case "textarea":
			case "hidden":
			y[i].value = "";
			break;
			case "radio":
			case "checkbox":
			if (y[i].checked) {
				y[i].checked = false;
			}
			break;
			case "select-one":
			case "select-multi":
			y[i].selectedIndex = -1;
			break;
			default:
			break;
		}
	}
	document.getElementById(groupname).value=""
}

function norm(form, name, table, groupname){
	var z = document.getElementsByName(name)[0];
	if (z.checked) {
		var x = document.getElementById(table);
		var y = x.getElementsByTagName("tr");
		for (i=1;i<y.length;i++) {
			var w = y[i].getElementsByTagName("td")[2];
			w.getElementsByTagName("input")[0].checked = true;
		}
	}
	var p = document.getElementById(groupname);
	if (p.value=="") {
		var t=""
		t=t+z.value
	}
	else {
		var t=p.value
	t=t+z.value
	}
	p.value=t
}

function displayMessage(message){
	$("#alertmessage").html(message).fadeIn();
}

function isChange1(value, text){
	var htmlstr = $("#familyhistoryspan").html();
	$("#familyhistoryspan").html(htmlstr + value);
}

function isChange2(value, text){
	var htmlstr = $("#familyhistoryspan").html();
	$("#familyhistoryspan").html(htmlstr + ": " + value);
}

function updateInfo(table, div, heading){
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/getcurrentinfo');?>",
		data: id=table,
		dataType: "json",
		success: function(data){
			displayMessage(data.result);
			$(div).html("<strong>" + heading + ":  </strong>" + data.text  + "<br />");
		}
	});
}

function createGraph(style){
	$.tb_show("Growth Chart", "<?php echo site_url ('provider/graph/');?>"+style+"?KeepThis=true&TB_iframe=true&height=850&width=850", false);
}

function doSearch1(ev){
	if(timeoutHnd1){
		clearTimeout(timeoutHnd1);
	}
	timeoutHnd1 = setTimeout(gridReload1,500);
}

function gridReload1(){
	var lab_mask = jQuery("#search_lab").val();
	jQuery("#lablist").setGridParam({
		url:"<?php echo site_url('provider/orders/lab/');?>"+lab_mask,
		page:1
	}).trigger("reloadGrid"); 
}

function doSearch2(ev){
	if(timeoutHnd2){
		clearTimeout(timeoutHnd2);
	}
	timeoutHnd2 = setTimeout(gridReload2,500);
}

function gridReload2(){
	var rad_mask = jQuery("#search_rad").val();
	jQuery("#radlist").setGridParam({
		url:"<?php echo site_url('provider/orders/rad/');?>"+rad_mask,
		page:1
	}).trigger("reloadGrid"); 
}

$("#cancel_encounter").click(function() {
	self.location = "<?php echo site_url('provider/encounters/view/') . $encounter_id;?>";
});

jQuery("#oh_issues").jqGrid({
	url:"<?php echo site_url('provider/issues/');?>",
	editurl:"<?php echo site_url('provider/issues/edit/');?>",
	datatype: "json",
	mtype: "POST",
	colNames:['Date Active','Date Inactive','Issue','ID'],
	colModel:[
		{name:'issue_date_active',index:'issue_date_active',width:80,editable:true},
		{name:'issue_date_inactive',index:'issue_date_inactive',width:80},
		{name:'issue',index:'issue',width:150,editable:true},
		{name:'issue_id',index:'issue_id',width:1,hidden:true}
	],
	rowNum:10,
	rowList:[10,20,30],
	imgpath: gridimgpath,
	pager: jQuery('#oh_pager1'),
	sortname: 'issue_date_active',
 	viewrecords: true,
 	sortorder: "desc",
 	caption:"Issues",
 	emptyrecords:"No issues"
}).navGrid('#oh_pager1',{edit:false,add:false,del:false
}).navButtonAdd('#oh_pager1',{caption:"Add Issue",
	onClickButton:function(){ 
		jQuery("#oh_issues").editGridRow("new",{height:280});
	} 
}).navButtonAdd('#oh_pager1',{caption:"Edit Issue",
	onClickButton:function(){ 
		var clickedit = jQuery("#oh_issues").getGridParam('selrow');
		if(clickedit){ 
			jQuery("#oh_issues").editGridRow(ohedit,{height:280, afterSubmit:});
		} else {
			alert("Please select issue to edit!");
		}
	} 
}).navButtonAdd('#oh_pager1',{caption:"Inactivate Issue",
	onClickButton:function(){ 
		var clickinactivate = jQuery("#oh_issues").getGridParam('selrow');
		if(clickinactivate){ 
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/issues/inactivate/');?>",
				data: id=clickinactivate,
				success: function(data){
					displayMessage(data.result);
					jQuery("#oh_issues").trigger(“reloadGrid”);
				}
			});
		} else {
			alert("Please select issue to inactivate!");
		}
	} 
});

$("#copyissuetopmh").click(function(){
	var ohcopypmh = jQuery("#oh_issues").getGridParam('selrow');
	if(ohcopypmh){
		var copiedvalue = jQuery("#oh_issues").getRowData(ohcopypmh);
		var oldvalue = $("#oh_pmh").val();
		if (oldvalue != ''){
			$("oh_pmh").val(oldvalue + "\r\n" + copiedvalue.issue);
		} else {
			$("oh_pmh").val(copiedvalue.issue);
		}
	} else {
		alert("Please select issue to copy!");
	}
});

$("#copyissuetopsh").click(function(){
	var ohcopypsh = jQuery("#oh_issues").getGridParam('selrow');
	if(ohcopypsh){
		var copiedvalue = jQuery("#oh_issues").getRowData(ohcopypsh);
		var oldvalue = $("#oh_psh").val();
		if (oldvalue != ''){
			$("oh_psh").val(oldvalue + "\r\n" + copiedvalue.issue);
		} else {
			$("oh_psh").val(copiedvalue.issue);
		}
		
	} else {
		alert("Please select issue to copy!");
	}
});

$("#copyicd9topmh").click(function(){
	var ohcopyicd9pmh = jQuery("#oh_issues").getGridParam('selrow');
	if(ohcopyicd9pmh){
		var htmlStr = $("#icd9result").html();
		var oldvalue = $("#oh_pmh").val();
		if (oldvalue != ''){
			$("oh_pmh").val(oldvalue + "\r\n" + htmlStr);
		} else {
			$("oh_pmh").val(htmlStr);
		}
		$("#icd9result").html("");
	}
});

$("#copyicd9topsh").click(function(){
	var ohcopyicd9psh = jQuery("#oh_issues").getGridParam('selrow');
	if(ohcopyicd9pmh){
		var htmlStr = $("#icd9result").html();
		var oldvalue = $("#oh_psh").val();
		if (oldvalue != ''){
			$("oh_psh").val(oldvalue + "\r\n" + htmlStr);
		} else {
			$("oh_psh").val(htmlStr);
		}
		$("#icd9result").html("");
	}
});

$('#issue').autocomplete('<?php echo site_url("search/icd9/");?>', {
	dataType:"json",
	formatItem: function(data,i,max,value,term){
		return value;
	},
	parse: function(data){
		var array = new Array();
		for(var i=0;i<data.length;i++){
			var tempValue = data[i].icd9_description + " (" + data[i].icd9 + ")";
			var tempResult = data[i].icd9_description + " (" + data[i].icd9 + ")";
			array[array.length] = { data:data[i], value: tempValue, result: tempResult};
		}
		return array;
	},
});

$('#searchicd9').autocomplete('<?php echo site_url("search/icd9/");?>', {
	dataType:"json",
	formatItem: function(data,i,max,value,term){
		return value;
	},
	parse: function(data){
		var array = new Array();
		for(var i=0;i<data.length;i++){
			var tempValue = data[i].icd9_description + " (" + data[i].icd9 + ")";
			var tempResult = data[i].icd9_description + " (" + data[i].icd9 + ")";
			array[array.length] = { data:data[i], value: tempValue, result: tempResult};
		}
		return array;
	},
});

$("#searchicd9").result(function(event, data, formatted) {
	$("#icd9result").html( !data ? "" : data.icd9_description + " (" + data[i].icd9 + ")");
});

$('#familyhistorywhatlist').jselect({
	replaceAll: true,
	onChange: function(value, text){ isChange1(value, text); },
	loadUrl: "<?php echo site_url('provider/encounters/familyhxlist/what/');?>",
	loadType: "POST",
	loadDataType: "json"
});

$('#familyhistorywholist').jselect({
	replaceAll: true,
	onChange: function(value, text){ isChange2(value, text); },
	loadUrl: "<?php echo site_url('provider/encounters/familyhxlist/who/');?>",
	loadType: "POST",
	loadDataType: "json"
});

$("#copyfh").click(function(){
	var htmlstr = $("#familyhistoryspan").html();
	var oldvalue = $("#oh_fh").val();
	$("oh_fh").val(oldvalue + "\r\n" + htmlstr);
	$("#familyhistoryspan").html("");
});

$('#sh1').bind('keyup keypress', function(){
	var areaText = $('#oh_sh').val();
	var sh1Text = 'Family members in the household: ';
	if(areaText == ""){
		$('#oh_sh')[0].value = sh1Text + $(this)[0].value;
	} else {
		$('#oh_sh')[0].value = areaText + "\r\n" + sh1Text + $(this)[0].value;
	}
});
	
$('#sh2').bind('keyup keypress', function(){
	var areaText = $('#oh_sh').val();
	var sh2Text = 'Children: ';
	if(areaText == ""){
		$('#oh_sh')[0].value = sh2Text + $(this)[0].value;
	} else {
		$('#oh_sh')[0].value = areaText + "\r\n" + sh2Text + $(this)[0].value;
	}
});

$('#sh3').bind('keyup keypress', function(){
	var areaText = $('#oh_sh').val();
	var sh3Text = 'Pets: ';
	if(areaText == ""){
		$('#oh_sh')[0].value = sh3Text + $(this)[0].value;
	} else {
		$('#oh_sh')[0].value = areaText + "\r\n" + sh3Text + $(this)[0].value;
	}
});

$("#sh4").click(function(){
	var copyetoh = $('input:radio[name=sh4]:checked').val();
	var areaText = $('#oh_etoh').val();
	if(areaText == ""){
		$('#oh_etoh')[0].value = copyetoh;
	} else {
		$('#oh_etoh')[0].value = areaText + "\r\n" + copyetoh;
	}
});

$('#sh5').bind('keyup keypress', function(){
	var areaText = $('#oh_etoh').val();
	var sh5Text = 'Frequency of alcohol use: ';
	if(areaText == ""){
		$('#oh_etoh')[0].value = sh5Text + $(this)[0].value;
	} else {
		$('#oh_etoh')[0].value = areaText + "\r\n" + sh5Text + $(this)[0].value;
	}
});

$("#sh6").click(function(){
	var copytobacco = $('input:radio[name=sh6]:checked').val();
	var areaText = $('#oh_tobacco').val();
	if(areaText == ""){
		$('#oh_tobacco')[0].value = copytobacco;
	} else {
		$('#oh_tobacco')[0].value = areaText + "\r\n" + copytobacco;
	}
});

$('#sh7').bind('keyup keypress', function(){
	var areaText = $('#oh_tobacco').val();
	var sh7Text = 'Frequency of tobacco use: ';
	if(areaText == ""){
		$('#oh_tobacco')[0].value = sh7Text + $(this)[0].value;
	} else {
		$('#oh_tobacco')[0].value = areaText + "\r\n" + sh7Text + $(this)[0].value;
	}
});

$("#sh8").click(function(){
	var copydrugs = $('input:radio[name=sh8]:checked').val();
	var areaText = $('#oh_drugs').val();
	if(areaText == ""){
		$('#oh_drugs')[0].value = copydrugs;
	} else {
		$('#oh_drugs')[0].value = areaText + "\r\n" + copydrugs;
	}
});

$('#sh9').bind('keyup keypress', function(){
	var areaText = $('#oh_drugs').val();
	var sh9Text = 'Type of illicit drugs: ';
	if(areaText == ""){
		$('#oh_drugs')[0].value = sh9Text + $(this)[0].value;
	} else {
		$('#oh_drugs')[0].value = areaText + "\r\n" + sh9Text + $(this)[0].value;
	}
});

$('#sh10').bind('keyup keypress', function(){
	var areaText = $('#oh_drugs').val();
	var sh10Text = 'Frequency of drug use: ';
	if(areaText == ""){
		$('#oh_drugs')[0].value = sh10Text + $(this)[0].value;
	} else {
		$('#oh_drugs')[0].value = areaText + "\r\n" + sh10Text + $(this)[0].value;
	}
});

$("#sh11").click(function(){
	var copyemployment = $('input:radio[name=sh11]:checked').val();
	var areaText = $('#oh_employment').val();
	if(areaText == ""){
		$('#oh_employment')[0].value = copyemployment;
	} else {
		$('#oh_employment')[0].value = areaText + "\r\n" + copyemployment;
	}
});

$('#sh12').bind('keyup keypress', function(){
	var areaText = $('#oh_employment').val();
	var sh12Text = 'Employment field: ';
	if(areaText == ""){
		$('#oh_employment')[0].value = sh12Text + $(this)[0].value;
	} else {
		$('#oh_employment')[0].value = areaText + "\r\n" + sh12Text + $(this)[0].value;
	}
});

jQuery("#activemedications1").jqGrid({
	url:"<?php echo site_url('provider/medications/activemeds1/');?>",
	editurl:"<?php echo site_url('provider/medications/editRX/');?>",
	datatype: "json",
	mtype: "POST",
	colNames:['Medication','Dosage','Unit','Sig','Route','Frequency','Reason','Date Active','ID'],
	colModel:[
		{name:'rxl_medication',index:'rxl_medication',width:150,editable:true},
		{name:'rxl_dosage',index:'rxl_dosage',width:10,editable:true},
		{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:10,editable:true},
		{name:'rxl_sig',index:'rxl_sig',width:150,editable:true},
		{name:'rxl_route',index:'rxl_route',width:10,editable:true},
		{name:'rxl_frequency',index:'rxl_frequency',width:80,editable:true},
		{name:'rxl_reason',index:'rxl_reason',width:100,editable:true},
		{name:'rxl_date_active',index:'rxl_date_active',width:20,editable:true},
		{name:'rxl_id',index:'rxl_id',width:1,hidden:true}
	],
	rowNum:10,
	rowList:[10,20,30],
	imgpath: gridimgpath,
	pager: jQuery('#medpager1'),
	sortname: 'rxl_medication',
 	viewrecords: true,
 	sortorder: "desc",
 	caption:"Review Medication List",
 	emptyrecords:"No medications"
}).navGrid('#medpager1',{edit:false,add:false,del:false
}).navButtonAdd('#medpager1',{caption:"Add Medication",
	onClickButton:function(){ 
		jQuery("#activemedications1").editGridRow("new",{height:280});
	} 
}).navButtonAdd('#medpager1',{caption:"Edit Medication",
	onClickButton:function(){ 
		var clickedit = jQuery("#activemedications1").getGridParam('selrow');
		if(clickedit){ 
			jQuery("#activemedications1").editGridRow(ohedit,{height:280});
		} else {
			alert("Please select medication to edit!");
		}
	} 
}).navButtonAdd('#medpager1',{caption:"Inactivate Medication",
	onClickButton:function(){ 
		var clickinactivate = jQuery("#activemedications1").getGridParam('selrow');
		if(clickinactivate){ 
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/medications/inactivateRX/');?>",
				data: id=clickinactivate,
				success: function(data){
					displayMessage(data.result);
					jQuery("#activemedications1").trigger(“reloadGrid”);
				}
			});
		} else {
			alert("Please select medication to inactivate!");
		}
	} 
});

$("#copyrx").click(updateInfo('rx_list', '#preview_oh_meds', 'Medications'));

jQuery("#supplements1").jqGrid({
	url:"<?php echo site_url('provider/medications/supplements1/');?>",
	editurl:"<?php echo site_url('provider/medications/editSup/');?>",
	datatype: "json",
	mtype: "POST",
	colNames:['Supplement','Dosage','Unit','Sig','Route','Frequency','Reason','Date Active','ID'],
	colModel:[
		{name:'sup_supplement',index:'sup_supplement',width:150,editable:true},
		{name:'sup_dosage',index:'sup_dosage',width:10,editable:true},
		{name:'sup_dosage_unit',index:'sup_dosage_unit',width:10,editable:true},
		{name:'sup_sig',index:'sup_sig',width:150,editable:true},
		{name:'sup_route',index:'sup_route',width:10,editable:true},
		{name:'sup_frequency',index:'sup_frequency',width:80,editable:true},
		{name:'sup_reason',index:'sup_reason',width:100,editable:true},
		{name:'sup_date_active',index:'sup_date_active',width:20,editable:true},
		{name:'sup_id',index:'sup_id',width:1,hidden:true}
	],
	rowNum:10,
	rowList:[10,20,30],
	imgpath: gridimgpath,
	pager: jQuery('#medpager2'),
	sortname: 'sup_supplement',
 	viewrecords: true,
 	sortorder: "desc",
 	caption:"Review Supplement List",
 	emptyrecords:"No supplements"
}).navGrid('#medpager2',{edit:false,add:false,del:false
}).navButtonAdd('#medpager2',{caption:"Add Supplement",
	onClickButton:function(){ 
		jQuery("#supplements1").editGridRow("new",{height:280});
	} 
}).navButtonAdd('#medpager2',{caption:"Edit Supplement",
	onClickButton:function(){ 
		var clickedit = jQuery("#supplements1").getGridParam('selrow');
		if(clickedit){ 
			jQuery("#supplements1").editGridRow(ohedit,{height:280});
		} else {
			alert("Please select supplement to edit!");
		}
	} 
}).navButtonAdd('#medpager2',{caption:"Inactivate Medication",
	onClickButton:function(){ 
		var clickinactivate = jQuery("#supplements1").getGridParam('selrow');
		if(clickinactivate){ 
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/medications/inactivateSup/');?>",
				data: id=clickinactivate,
				success: function(data){
					displayMessage(data.result);
					jQuery("#supplements1").trigger(“reloadGrid”);
				}
			});
		} else {
			alert("Please select supplement to inactivate!");
		}
	} 
});

$("#copysupplements").click(updateInfo('sup_list', '#preview_oh_supplements', 'Supplements'));

jQuery("#allergies1").jqGrid({
	url:"<?php echo site_url('provider/medications/allergies1/');?>",
	editurl:"<?php echo site_url('provider/medications/editAllergies/');?>",
	datatype: "json",
	mtype: "POST",
	colNames:['Allergies','Reaction','Date Active','ID'],
	colModel:[
		{name:'allergies_med',index:'allergies_med',width:150,editable:true},
		{name:'allergies_reaction',index:'allergies_reaction',width:150,editable:true},
		{name:'allergies_date_active',index:'allergies_date_active',width:20,editable:true},
		{name:'allergies_id',index:'allergies_id',width:1,hidden:true}
	],
	rowNum:10,
	rowList:[10,20,30],
	imgpath: gridimgpath,
	pager: jQuery('#medpager3'),
	sortname: 'allergies_med',
 	viewrecords: true,
 	sortorder: "desc",
 	caption:"Review Allergies List",
 	emptyrecords:"No allergies"
}).navGrid('#medpager3',{edit:false,add:false,del:false
}).navButtonAdd('#medpager3',{caption:"Add Allergy",
	onClickButton:function(){ 
		jQuery("#allergies1").editGridRow("new",{height:280});
	} 
}).navButtonAdd('#medpager3',{caption:"Edit Allergy",
	onClickButton:function(){ 
		var clickedit = jQuery("#allergies1").getGridParam('selrow');
		if(clickedit){ 
			jQuery("#allergies1").editGridRow(ohedit,{height:280});
		} else {
			alert("Please select allergy to edit!");
		}
	} 
}).navButtonAdd('#medpager3',{caption:"Inactivate Allergy",
	onClickButton:function(){ 
		var clickinactivate = jQuery("#allergies1").getGridParam('selrow');
		if(clickinactivate){ 
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/medications/inactivateAllergy/');?>",
				data: id=clickinactivate,
				success: function(data){
					displayMessage(data.result);
					jQuery("#allergies1").trigger(“reloadGrid”);
				}
			});
		} else {
			alert("Please select allergy to inactivate!");
		}
	} 
});

$("#copyallergies").click(updateInfo('allergies', '#preview_oh_allergies', 'Allergies'));

jQuery("#vitalsigns").jqGrid({
	url:"<?php echo site_url('provider/vitalsigns/');?>",
	editurl:"<?php echo site_url('provider/vitalsigns/edit/');?>",
	datatype: "json",
	mtype: "POST",
	colNames:['Date','Weight in <?php echo $weight_unit;?>','Height in <?php echo $height_unit;?>','HC in <?php echo $hc_unit;?>','BMI','Temp in <?php echo $temp_unit;?>','Temp Method','BP Systolic','BP Diastolic','BP Position','Pulse','Resp','O2 Sat','Notes','ID'],
	colModel:[
		{name:'vitals_date',index:'vitals_date',width:20,editable:true,
			editoptions:{
				size:12,
				dataInit:function(el){
					$(el).datepicker({dateFormat:'yy-mm-dd'});
				},
				defaultValue: function(){
					var currentTime = new Date();
					var month = parseInt(currentTime.getMonth() + 1);
					month = month <= 9 ? "0"+month : month;
					var day = currentTime.getDate();
					day = day <= 9 ? "0"+day : day;
					var year = currentTime.getFullYear();
					return year+"-"+month + "-"+day;
				}
			},
			formoptions:{ rowpos:2, elmprefix:"(*)",elmsuffix:" yyyy-mm-dd" } 
		},
		{name:'weight',index:'weight',width:20,editable:true},
		{name:'height',index:'height',width:20,editable:true},
		{name:'headcircumference',index:'headcircumference',width:20,editable:true},
		{name:'BMI',index:'BMI',width:20},
		{name:'temp',index:'temp',width:20,editable:true},
		{name:'temp_method',index:'temp_method',width:20,editable:true,edittype:'select',editoption: { value: “Oral:Oral; Axillary:Axillary; Temporal:Temporal; Tympanic:Tympanic; Rectal:Rectal” }},
		{name:'bp_systolic',index:'bp_systolic',width:20,editable:true},
		{name:'bp_diastolic',index:'bp_diastolic',width:20,editable:true},
		{name:'bp_position',index:'bp_position',width:20,editable:true,edittype:'select',editoption: { value: “Sitting:Sitting; Standing:Standing; Supine:Supine” }},
		{name:'pulse',index:'pulse',width:20,editable:true},
		{name:'respirations',index:'respirations',width:20,editable:true},
		{name:'o2_sat',index:'o2_sat',width:20,editable:true},
		{name:'vitals_other',index:'vitals_other',width:80,editable:true},
		{name:'vitals_id',index:'vitals_id',width:1,hidden:true}
	],
	rowNum:10,
	rowList:[10,20,30],
	imgpath: gridimgpath,
	pager: jQuery('#vspager'),
	sortname: 'vitals_date',
 	viewrecords: true,
 	sortorder: "desc",
 	caption:"Vital Signs History",
 	emptyrecords:"No vital signs"
}).navGrid('#vspager',{edit:false,add:false,del:false
}).navButtonAdd('#vspager',{caption:"Add Vital Signs",
	onClickButton:function(){ 
		jQuery("#vitalsigns").editGridRow("new",{height:280});
	} 
}).navButtonAdd('#vspager',{caption:"Edit Vital Signs",
	onClickButton:function(){ 
		var clickedit = jQuery("#vitalsigns").getGridParam('selrow');
		if(clickedit){ 
			jQuery("#vitalsigns").editGridRow(ohedit,{height:280});
		} else {
			alert("Please select vital signs to edit!");
		}
	} 
});

$("#weight_chart").click(createGraph('weight-age'));
$("#height_chart").click(createGraph('height-age'));
$("#hc_chart").click(createGraph('hc-age'));
$("#bmi_chart").click(createGraph('bmi-age'));

jQuery("#proceduretemplatelist").jqGrid({
	url:"<?php echo site_url('provider/templates/procedure/');?>",
	editurl:"<?php echo site_url('provider/templates/editProcedure/');?>",
	datatype: "json",
	mtype: "POST",
	colNames:['Procedure Type','Procedure Description','ID'],
	colModel:[
		{name:'procedure_type',index:'procedure_type',width:150,editable:true},
		{name:'procedure_description',index:'procedure_description',width:150,hidden:true,editable:true,edittype:'textarea'},
		{name:'procedure_complications',index:'procedure_complications',width:150,hidden:true,editable:true,edittype:'textarea'},
		{name:'procedure_ebl',index:'procedure_ebl',width:150,hidden:true,editable:true},
		{name:'procedurelist_id',index:'procedurelist_id',width:1,hidden:true}
	],
	rowNum:10,
	rowList:[10,20,30],
	imgpath: gridimgpath,
	pager: jQuery('#templatepager1'),
	sortname: 'procedure_type',
 	viewrecords: true,
 	sortorder: "desc",
 	caption:"Procedure Templates",
 	emptyrecords:"No templates"
}).navGrid('#templatepager1',{edit:false,add:false,del:false
}).navButtonAdd('#templatepager1',{caption:"Add Procedure Template",
	onClickButton:function(){ 
		jQuery("#proceduretemplatelist").editGridRow("new",{height:280});
	} 
}).navButtonAdd('#templatepager1',{caption:"Edit Procedure Template",
	onClickButton:function(){ 
		var clickedit = jQuery("#proceduretemplatelist").getGridParam('selrow');
		if(clickedit){ 
			jQuery("#proceduretemplatelist").editGridRow(ohedit,{height:280});
		} else {
			alert("Please select procedure to edit!");
		}
	} 
}).navButtonAdd('#templatepager1',{caption:"Delete Procedure Template",
	onClickButton:function(){ 
		var clickinactivate = jQuery("#proceduretemplatelist").getGridParam('selrow');
		if(clickinactivate){ 
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/templates/deleteProcedureTemplate/');?>",
				data: id=clickinactivate,
				success: function(data){
					displayMessage(data.result);
					jQuery("#proceduretemplatelist").trigger(“reloadGrid”);
				}
			});
		} else {
			alert("Please select procedure to delete!");
		}
	} 
});

$("#selectproceduretemplate").click(function(){
	var selecttemplate = jQuery("#selectproceduretemplate").getGridParam('selrow');
	if(selecttemplate){
		var copiedvalue = jQuery("#selectproceduretemplate").getRowData(selecttemplate);
		$("proc_type").val(copiedvalue.procedure_type);
		$("proc_description").val(copiedvalue.procedure_description);
	} else {
		alert("Please select procedure template to use!");
	}
});

$('#assessment1').autocomplete('<?php echo site_url("search/icd9/");?>', {
	dataType:"json",
	formatItem: function(data,i,max,value,term){
		return value;
	},
	parse: function(data){
		var array = new Array();
		for(var i=0;i<data.length;i++){
			var tempValue = data[i].icd9_description + " (" + data[i].icd9 + ")";
			var tempResult = data[i].icd9_description + " (" + data[i].icd9 + ")";
			array[array.length] = { data:data[i], value: tempValue, result: tempResult};
		}
		return array;
	},
});

$("#assessment1").result(function(event, data, formatted) {
	$("#assessment_icd1").val( !data ? "" : data[i].icd9);
});

$('#assessment2').autocomplete('<?php echo site_url("search/icd9/");?>', {
	dataType:"json",
	formatItem: function(data,i,max,value,term){
		return value;
	},
	parse: function(data){
		var array = new Array();
		for(var i=0;i<data.length;i++){
			var tempValue = data[i].icd9_description + " (" + data[i].icd9 + ")";
			var tempResult = data[i].icd9_description + " (" + data[i].icd9 + ")";
			array[array.length] = { data:data[i], value: tempValue, result: tempResult};
		}
		return array;
	},
});

$("#assessment2").result(function(event, data, formatted) {
	$("#assessment_icd2").val( !data ? "" : data[i].icd9);
});

$('#assessment3').autocomplete('<?php echo site_url("search/icd9/");?>', {
	dataType:"json",
	formatItem: function(data,i,max,value,term){
		return value;
	},
	parse: function(data){
		var array = new Array();
		for(var i=0;i<data.length;i++){
			var tempValue = data[i].icd9_description + " (" + data[i].icd9 + ")";
			var tempResult = data[i].icd9_description + " (" + data[i].icd9 + ")";
			array[array.length] = { data:data[i], value: tempValue, result: tempResult};
		}
		return array;
	},
});

$("#assessment3").result(function(event, data, formatted) {
	$("#assessment_icd3").val( !data ? "" : data[i].icd9);
});

$('#assessment4').autocomplete('<?php echo site_url("search/icd9/");?>', {
	dataType:"json",
	formatItem: function(data,i,max,value,term){
		return value;
	},
	parse: function(data){
		var array = new Array();
		for(var i=0;i<data.length;i++){
			var tempValue = data[i].icd9_description + " (" + data[i].icd9 + ")";
			var tempResult = data[i].icd9_description + " (" + data[i].icd9 + ")";
			array[array.length] = { data:data[i], value: tempValue, result: tempResult};
		}
		return array;
	},
});

$("#assessment4").result(function(event, data, formatted) {
	$("#assessment_icd4").val( !data ? "" : data[i].icd9);
});

jQuery("#lablist").jqGrid({
	url:"<?php echo site_url('provider/orders/lab/');?>",
	editurl:"<?php echo site_url('provider/orders/editLab/');?>",
	datatype: "json",
	mtype: "POST",
	colNames:['Laboratory Tests','ID'],
	colModel:[
		{name:'orders_description',index:'procedure_description',width:150,hidden:true,editable:true},
		{name:'orderslist_id',index:'procedurelist_id',width:1,hidden:true}
	],
	rowNum:10,
	rowList:[10,20,30],
	imgpath: gridimgpath,
	pager: jQuery('#orderspager1'),
	sortname: 'orders_description',
 	viewrecords: true,
 	sortorder: "desc",
 	caption:"Order Laboratory Tests",
 	multiselect: true,
 	emptyrecords:"No laboratory tests"
}).navGrid('#orderspager1',{edit:false,add:false,del:false
}).navButtonAdd('#orderspager1',{caption:"Add Laboratory Test",
	onClickButton:function(){ 
		jQuery("#lablist").editGridRow("new",{height:280});
	} 
}).navButtonAdd('#orderspager1',{caption:"Edit Laboratory Test",
	onClickButton:function(){ 
		var clickedit = jQuery("#lablist").getGridParam('selrow');
		if(clickedit){ 
			jQuery("#lablist").editGridRow(ohedit,{height:280});
		} else {
			alert("Please select laboratory test to edit!");
		}
	} 
}).navButtonAdd('#orderspager1',{caption:"Delete Laboratory Test",
	onClickButton:function(){ 
		var clickinactivate = jQuery("#lablist").getGridParam('selrow');
		if(clickinactivate){ 
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/orders/deleteLab/');?>",
				data: id=clickinactivate,
				success: function(data){
					displayMessage(data.result);
					jQuery("#lablist").trigger(“reloadGrid”);
				}
			});
		} else {
			alert("Please select laboratory test to delete!");
		}
	} 
});
var timeoutHnd1;

jQuery("#radlist").jqGrid({
	url:"<?php echo site_url('provider/orders/rad/');?>",
	editurl:"<?php echo site_url('provider/orders/editRad/');?>",
	datatype: "json",
	mtype: "POST",
	colNames:['Radiology Tests','ID'],
	colModel:[
		{name:'orders_description',index:'procedure_description',width:150,hidden:true,editable:true},
		{name:'orderslist_id',index:'procedurelist_id',width:1,hidden:true}
	],
	rowNum:10,
	rowList:[10,20,30],
	imgpath: gridimgpath,
	pager: jQuery('#orderspager2'),
	sortname: 'orders_description',
 	viewrecords: true,
 	sortorder: "desc",
 	caption:"Order Radiology Tests",
 	multiselect: true,
 	emptyrecords:"No radiology tests"
}).navGrid('#orderspager2',{edit:false,add:false,del:false
}).navButtonAdd('#orderspager2',{caption:"Add Radiology Test",
	onClickButton:function(){ 
		jQuery("#radlist").editGridRow("new",{height:280});
	} 
}).navButtonAdd('#orderspager2',{caption:"Edit Radiology Test",
	onClickButton:function(){ 
		var clickedit = jQuery("#radlist").getGridParam('selrow');
		if(clickedit){ 
			jQuery("#radlist").editGridRow(ohedit,{height:280});
		} else {
			alert("Please select radiology test to edit!");
		}
	} 
}).navButtonAdd('#orderspager2',{caption:"Delete Radiology Test",
	onClickButton:function(){ 
		var clickinactivate = jQuery("#radlist").getGridParam('selrow');
		if(clickinactivate){ 
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/orders/deleteRad/');?>",
				data: id=clickinactivate,
				success: function(data){
					displayMessage(data.result);
					jQuery("#radlist").trigger(“reloadGrid”);
				}
			});
		} else {
			alert("Please select radiology test to delete!");
		}
	} 
});
var timeoutHnd2;
