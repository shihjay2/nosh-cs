<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load('<?php echo site_url("search/loadpage");?>');
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("assistant/office");?>',
		redirect_url: '<?php echo site_url("start");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: 300000
	});
	$("#imm_immunization").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/imm');?>",
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
		minLength: 3,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
			$("#imm_cvxcode").val(ui.item.cvx);
		}
	});
	$("#cpt").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/cpt');?>",
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
		minLength: 3,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
	});
	$("#imm_expiration").mask("99/99/9999");
	$("#imm_expiration").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#date_purchase").mask("99/99/9999");
	$("#date_purchase").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#reactivate_vaccine_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 500, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'Reactivate': function() {
				var a = $("#reactivate_quantity");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Quantity");
				if (bValid) {
					var str = $("#reactivate_quantity").val();
					var item = $("#reactivate_vaccine_id").val();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/office/reactivate_vaccine');?>",
						data: "vaccine_id=" + item + "&quantity=" + str,
						success: function(data){
							$.jGrowl(data);
							jQuery("#vaccine_inventory_inactive").trigger("reloadGrid");
							jQuery("#vaccine_inventory").trigger("reloadGrid");
							$("#reactivate_quantity").val('');
							$("#reactivate_vaccine_id").val('');
							$("#reactivate_vaccine_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#reactivate_quantity").val('');
				$("#reactivate_vaccine_id").val('');
				$("#reactivate_vaccine_dialog").dialog('close');
			}
		}
	});
	jQuery("#vaccine_inventory").jqGrid({
		url:"<?php echo site_url('assistant/office/vaccine_inventory/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Purchased','Expiration Date','Vaccine','Quantity','Lot','Manufacturer','Brand','CVX','CPT'],
		colModel:[
			{name:'vaccine_id',index:'vaccine_id',width:1,hidden:true},
			{name:'date_purchase',index:'date_purchase',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'imm_expiration',index:'imm_expiration',width:100,hidden:true,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'imm_immunization',index:'imm_immunization',width:500},
			{name:'quantity',index:'quantity',width:100},
			{name:'imm_lot',index:'imm_lot',width:1,hidden:true},
			{name:'imm_manufacturer',index:'imm_manufacturer',width:1,hidden:true},
			{name:'imm_brand',index:'imm_brand',width:1,hidden:true},
			{name:'imm_cvxcode',index:'imm_cvxcode',width:1,hidden:true},
			{name:'cpt',index:'cpt',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#vaccine_inventory_pager'),
		sortname: 'imm_immunization',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Vaccine Inventory",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#vaccine_inventory_pager',{search:false,edit:false,add:false,del:false});
	jQuery("#vaccine_inventory_inactive").jqGrid({
		url:"<?php echo site_url('assistant/office/vaccine_inventory_inactive/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Purchased','Expiration Date','Vaccine','Quantity','Lot','Manufacturer','Brand','CVX','CPT'],
		colModel:[
			{name:'vaccine_id',index:'vaccine_id',width:1,hidden:true},
			{name:'date_purchase',index:'date_purchase',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'imm_expiration',index:'imm_expiration',width:100,hidden:true,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'imm_immunization',index:'imm_immunization',width:600},
			{name:'quantity',index:'quantity',width:1,hidden:true},
			{name:'imm_lot',index:'imm_lot',width:1,hidden:true},
			{name:'imm_manufacturer',index:'imm_manufacturer',width:1,hidden:true},
			{name:'imm_brand',index:'imm_brand',width:1,hidden:true},
			{name:'imm_cvxcode',index:'imm_cvxcode',width:1,hidden:true},
			{name:'cpt',index:'cpt',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#vaccine_inventory_inactive_pager'),
		sortname: 'imm_immunization',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Inactive Vaccine Inventory",
	 	height: "100%",
	 	hiddengrid: true,
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#vaccine_inventory_inactive_pager',{search:false,edit:false,add:false,del:false});
	$("#add_vaccine").button();
	$("#add_vaccine").click(function(){
		$('#edit_vaccine_form').clearForm();
		$('#edit_vaccine_form').show('fast');
		$("#imm_immunization").focus();
	});
	$("#edit_vaccine").button();
	$("#edit_vaccine").click(function(){
		var item = jQuery("#vaccine_inventory").getGridParam('selrow');
		if(item){
			jQuery("#vaccine_inventory").GridToForm(item,"#edit_vaccine_form");
			var date = $('#imm_expiration').val();
			var edit_date = editDate(date);
			$('#imm_expiration').val(edit_date);
			$('#edit_vaccine_form').show('fast');
			$("#imm_immunization").focus();
		} else {
			$.jGrowl("Please select vaccine to edit!")
		}
	});
	$("#inactivate_vaccine").button();
	$("#inactivate_vaccine").click(function(){
		var item = jQuery("#vaccine_inventory").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/office/inactivate_vaccine');?>",
				data: "vaccine_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#vaccine_inventory").trigger("reloadGrid");
					jQuery("#vaccine_inventory_inactive").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select vaccine to inactivate!")
		}
	});
	$("#delete_vaccine").button();
	$("#delete_vaccine").click(function(){
		var item = jQuery("#vaccine_inventory").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this vaccination entry?  This is not recommended unless entering the vaccine was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/office/delete_vaccine');?>",
					data: "vaccine_id=" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#vaccine_inventory").trigger("reloadGrid");
						jQuery("#vaccine_inventory_inactive").trigger("reloadGrid");
					}
				});
			}
		} else {
			$.jGrowl("Please select vaccine to delete!")
		}
	});
	$("#reactivate_vaccine").button();
	$("#reactivate_vaccine").click(function(){
		var item = jQuery("#vaccine_inventory_inactive").getGridParam('selrow');
		if(item){
			$("#reactivate_vaccine_id").val(item);
			$("#reactivate_vaccine_dialog").dialog('open');
			$("#reactivate_quantity").focus();
		} else {
			$.jGrowl("Please select vaccine to reactivate!")
		}
	});
	$("#save_vaccine").button();
	$("#save_vaccine").click(function(){
		var a = $("#imm_immunization");
		var b = $("#imm_manufacturer");
		var c = $("#imm_brand");
		var d = $("#imm_lot");
		var e = $("#quantity");
		var f = $("#cpt");
		var g = $("#imm_expiration");
		var h = $("#date_purchase");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Vaccine");
		bValid = bValid && checkEmpty(b,"Manufacturer");
		bValid = bValid && checkEmpty(c,"Brand");
		bValid = bValid && checkEmpty(d,"Lot number");
		bValid = bValid && checkEmpty(e,"Quantity");
		bValid = bValid && checkEmpty(f,"CPT");
		bValid = bValid && checkEmpty(g,"Expiration date");
		bValid = bValid && checkEmpty(h,"Date purchased");
		if (bValid) {
			var str = $("#edit_vaccine_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/office/edit_vaccine');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						jQuery("#vaccine_inventory").trigger("reloadGrid");
						$('#edit_vaccine_form').clearForm();
						$('#edit_vaccine_form').hide('fast');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_vaccine").button();
	$("#cancel_vaccine").click(function(){
		$('#edit_vaccine_form').clearForm();
		$('#edit_vaccine_form').hide('fast');
	});
	
	$("#temp_date").mask("99/99/9999");
	$("#temp_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$('#temp_time').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	jQuery("#vaccine_temp").jqGrid({
		url:"<?php echo site_url('assistant/office/vaccine_temp/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','Temperature','Action Taken If Out of Range'],
		colModel:[
			{name:'temp_id',index:'temp_id',width:1,hidden:true},
			{name:'date',index:'date',width:200},
			{name:'temp',index:'temp',width:100},
			{name:'action',index:'temp',width:500},
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#vaccine_temp_pager'),
		sortname: 'date',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Vaccine Temperatures",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#vaccine_temp_pager',{search:false,edit:false,add:false,del:false});
	$("#add_temp").button();
	$("#add_temp").click(function(){
		$('#edit_vaccine_temp_form').clearForm();
		var date = getCurrentDate();
		var time = getCurrentTime();
		$('#temp_date').val(date);
		$('#temp_time').val(time);
		$('#edit_vaccine_temp_form').show('fast');
		$("#temp").focus();
	});
	$("#edit_temp").button();
	$("#edit_temp").click(function(){
		var item = jQuery("#vaccine_temp").getGridParam('selrow');
		if(item){
			jQuery("#vaccine_temp").GridToForm(item,"#edit_vaccine_temp_form");
			var date = $('#date').val();
			var edit_date = editDate1(date);
			$('#temp_date').val(edit_date);
			var edit_time = editDate2(date);
			$('#temp_time').val(edit_time);
			$('#edit_vaccine_temp_form').show('fast');
			$("#temp").focus();
		} else {
			$.jGrowl("Please select vaccine to edit!")
		}
	});
	$("#delete_temp").button();
	$("#delete_temp").click(function(){
		var item = jQuery("#vaccine_temp").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this vaccine temperature entry?  This is not recommended unless entering the temperature was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/office/delete_temp');?>",
					data: "temp_id=" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#vaccine_temp").trigger("reloadGrid");
						jQuery("#vaccine_temp_inactive").trigger("reloadGrid");
					}
				});
			}
		} else {
			$.jGrowl("Please select vaccine temperature to delete!")
		}
	});
	$("#save_temp").button();
	$("#save_temp").click(function(){
		var a = $("#date1");
		var b = $("#date2");
		var c = $("#temp");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Date");
		bValid = bValid && checkEmpty(b,"Time");
		bValid = bValid && checkEmpty(c,"Temperature");
		if (bValid) {
			var str = $("#edit_vaccine_temp_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/office/edit_temp');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						jQuery("#vaccine_temp").trigger("reloadGrid");
						$('#edit_vaccine_temp_form').clearForm();
						$('#edit_vaccine_temp_form').hide('fast');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_temp").button();
	$("#cancel_temp").click(function(){
		$('#edit_vaccine_temp_form').clearForm();
		$('#edit_vaccine_temp_form').hide('fast');
	});
	
	$("#sup_description").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/sup_cpt');?>",
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
		minLength: 3,
		select: function(event, ui){
			$("#sup_cpt").val(ui.item.cpt);
			$("#sup_quantity").val(ui.item.quantity);
			$("#sup_charge").val(ui.item.charge);
			$("#sup_manufacturer").val(ui.item.charge.manufacturer);
		}
	});
	$("#sup_expiration").mask("99/99/9999");
	$("#sup_expiration").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#sup_date_purchase").mask("99/99/9999");
	$("#sup_date_purchase").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	jQuery("#supplements_inventory").jqGrid({
		url:"<?php echo site_url('assistant/office/supplement_inventory/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Purchased','Expiration Date','Supplement','Strength','Quantity','Manufacturer','Lot','CPT','Charge'],
		colModel:[
			{name:'supplement_id',index:'supplement_id',width:1,hidden:true},
			{name:'date_purchase',index:'date_purchase',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'sup_expiration',index:'sup_expiration',width:100,hidden:true,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'sup_description',index:'sup_description',width:500},
			{name:'sup_strength',index:'sup_strength',width:1,hidden:true},
			{name:'quantity',index:'quantity',width:100},
			{name:'sup_manufacturer',index:'sup_manufacturer',width:1,hidden:true},
			{name:'sup_lot',index:'sup_lot',width:1,hidden:true},
			{name:'cpt',index:'cpt',width:1,hidden:true},
			{name:'charge',index:'charge',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#supplements_inventory_pager'),
		sortname: 'sup_description',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Supplement Inventory",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#supplements_inventory_pager',{search:false,edit:false,add:false,del:false});
	jQuery("#supplements_inventory_inactive").jqGrid({
		url:"<?php echo site_url('assistant/office/supplement_inventory_inactive/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Purchased','Expiration Date','Supplement','Strength','Quantity','Manufacturer','Lot','CPT','Charge'],
		colModel:[
			{name:'supplement_id',index:'supplement_id',width:1,hidden:true},
			{name:'date_purchase',index:'date_purchase',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'sup_expiration',index:'sup_expiration',width:100,hidden:true,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'sup_description',index:'sup_description',width:500},
			{name:'sup_strength',index:'sup_strength',width:1,hidden:true},
			{name:'quantity',index:'quantity',width:100},
			{name:'sup_manufacturer',index:'imm_manufacturer',width:1,hidden:true},
			{name:'sup_lot',index:'sup_lot',width:1,hidden:true},
			{name:'cpt',index:'cpt',width:1,hidden:true},
			{name:'charge',index:'charge',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#supplements_inventory_inactive_pager'),
		sortname: 'sup_description',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Inactive Supplements Inventory",
	 	height: "100%",
	 	hiddengrid: true,
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#supplements_inventory_inactive_pager',{search:false,edit:false,add:false,del:false});
	$("#supplements_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false
	});
	$("#add_supplement").button().click(function(){
		$('#edit_supplement_form').clearForm();
		$("#supplements_dialog").dialog('open');
		$("#sup_description").focus();
	});
	$("#edit_supplement").button().click(function(){
		var item = jQuery("#supplements_inventory").getGridParam('selrow');
		if(item){
			jQuery("#supplements_inventory").GridToForm(item,"#edit_supplement_form");
			var date = $('#sup_expiration').val();
			var edit_date = editDate(date);
			$('#sup_expiration').val(edit_date);
			$("#supplements_dialog").dialog('open');
			$("#sup_description").focus();
		} else {
			$.jGrowl("Please select supplement to edit!")
		}
	});
	$("#inactivate_supplement").button().click(function(){
		var item = jQuery("#supplements_inventory").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/office/inactivate_supplement');?>",
				data: "supplement_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#supplements_inventory").trigger("reloadGrid");
					jQuery("#supplements_inventory_inactive").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select supplement to inactivate!")
		}
	});
	$("#delete_supplement").button().click(function(){
		var item = jQuery("#supplements_inventory").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this supplement entry?  This is not recommended unless entering the supplement was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/office/delete_supplement');?>",
					data: "supplement_id=" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#supplements_inventory").trigger("reloadGrid");
						jQuery("#supplements_inventory_inactive").trigger("reloadGrid");
					}
				});
			}
		} else {
			$.jGrowl("Please select supplement to delete!")
		}
	});
	$("#reactivate_supplement").button().click(function(){
		var item = jQuery("#supplements_inventory_inactive").getGridParam('selrow');
		if(item){
			$("#reactivate_supplement_id").val(item);
			$("#reactivate_supplement_dialog").dialog('open');
			$("#reactivate_sup_quantity").focus();
		} else {
			$.jGrowl("Please select supplement to reactivate!")
		}
	});
	$("#save_supplement").button().click(function(){
		var a = $("#sup_description");
		var b = $("#sup_manufacturer");
		var c = $("#sup_charge");
		var d = $("#sup_quantity");
		var e = $("#sup_date_purchase");
		var f = $("#sup_strength");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Supplement");
		bValid = bValid && checkEmpty(b,"Manufacturer");
		bValid = bValid && checkEmpty(c,"Charge");
		bValid = bValid && checkEmpty(d,"Quantity");
		bValid = bValid && checkEmpty(e,"Date purchased");
		bValid = bValid && checkEmpty(f,"Strength");
		if (bValid) {
			var str = $("#edit_supplement_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/office/edit_supplement');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						jQuery("#supplements_inventory").trigger("reloadGrid");
						$('#edit_supplement_form').clearForm();
						$('#supplements_dialog').dialog('close');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_supplement").button().click(function(){
		$('#edit_supplement_form').clearForm();
		$('#supplements_dialog').dialog('close');
	});
	$("#reactivate_supplement_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 500, 
		modal: true,
		buttons: {
			'Reactivate': function() {
				var a = $("#reactivate_sup_quantity");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Quantity");
				if (bValid) {
					var str = $("#reactivate_sup_quantity").val();
					var item = $("#reactivate_supplement_id").val();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/office/reactivate_supplement');?>",
						data: "supplement_id=" + item + "&quantity=" + str,
						success: function(data){
							$.jGrowl(data);
							jQuery("#supplements_inventory_inactive").trigger("reloadGrid");
							jQuery("#supplements_inventory").trigger("reloadGrid");
							$("#reactivate_sup_quantity").val('');
							$("#reactivate_supplement_id").val('');
							$("#reactivate_supplement_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#reactivate_sup_quantity").val('');
				$("#reactivate_supplement_id").val('');
				$("#reactivate_supplement_dialog").dialog('close');
			}
		}
	});
	
	$("#search_field_1").addOption({"":"Select Field","age":"Patient's age","insurance":"Patient's primary insurance","issue":"Patient's active medical issue list","billing":"Patient's billing code","rxl_medication":"Patient's active medication list","imm_immunization":"Patient's immunization list"},false);
	$("#search_op_1").addOption({"":"Select Operator"},false);
	$("#search_field_1").change(function(){
		var a = $("#search_field_1").val();
		if (a == "age") {
			$("#search_op_1").removeOption(/./);
			$("#search_op_1").addOption({"":"Select Operator","less than":"is less than","equal":"is equal to","greater than":"is greater than","contains":"contains","not equal":"is not equal to"},false);
			$("#search_desc_1").val("");
		}
		if (a == "issue" || a == "rxl_medication" || a == "imm_immunization" || a == "insurance") {
			$("#search_op_1").removeOption(/./);
			$("#search_op_1").addOption({"":"Select Operator","equal":"is equal to","contains":"contains","not equal":"is not equal to"},false);
			$("#search_desc_1").val("");
		}
		if (a == "billing") {
			$("#search_op_1").removeOption(/./);
			$("#search_op_1").addOption({"":"Select Operator","equal":"is equal to","not equal":"is not equal to"},false);
			$("#search_desc_1").val("");
			$("#search_desc_1").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "<?php echo site_url('search/cpt');?>",
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
	});
	$("#search_op_1").change(function(){
		var a = $("#search_op_1").val();
		if (a == "between") {
			$("#search_desc_1").val(" AND ");
		}
	});
	$("#search_gender_both").attr('checked',true);
	$("#search_add").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#search_add").click(function() {
		var a = $("#super_query_div > :last-child").attr("id");
		var a1 = a.split("_");
		var count = parseInt(a1[2]) + 1;
		$("#super_query_div").append('<br><select name="search_join[]" id="search_join_'+count+'" class="text ui-widget-content ui-corner-all search_join_class"></select> <select name="search_field[]" id="search_field_'+count+'" class="text ui-widget-content ui-corner-all search_field_class"></select> <select name="search_op[]" id="search_op_'+count+'" class="text ui-widget-content ui-corner-all search_op_class"></select> <input type="text" name="search_desc[]" id="search_desc_'+count+'"  class="text ui-widget-content ui-corner-all search_desc_class"></input>');
		$("#search_field_"+count).addOption({"":"Select Field","age":"Patient's age","insurance":"Patient's primary insurance","issue":"Patient's active medical issue list","billing":"Patient's billing code","rxl_medication":"Patient's active medication list","imm_immunization":"Patient's immunization list"},false);
		$("#search_op_"+count).addOption({"":"Select Operator"},false);
		$("#search_join_"+count).addOption({"AND":"And (&)","OR":"Or (||)"},false);
		$("#search_field_"+count).change(function(){
			var a = $("#search_field_"+count).val();
			if (a == "age") {
				$("#search_op_"+count).removeOption(/./);
				$("#search_op_"+count).addOption({"":"Select Operator","less than":"is less than","equal":"is equal to","greater than":"is greater than","between":"is between"},false);
				$("#search_desc_"+count).val("");
			}
			if (a == "issue" || a == "rxl_medication" || a == "imm_immunization" || a == "insurance") {
				$("#search_op_"+count).removeOption(/./);
				$("#search_op_"+count).addOption({"":"Select Operator","equal":"is equal to","contains":"contains","not equal":"is not equal to"},false);
				$("#search_desc_"+count).val("");
			}
			if (a == "billing") {
				$("#search_op_"+count).removeOption(/./);
				$("#search_op_"+count).addOption({"":"Select Operator","equal":"is equal to","not equal":"is not equal to"},false);
				$("#search_desc_"+count).val("");
				$("#search_desc_"+count).autocomplete({
					source: function (req, add){
						$.ajax({
							url: "<?php echo site_url('search/cpt');?>",
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
		});
		$("#search_op_"+count).change(function(){
			var a = $("#search_op_"+count).val();
			if (a == "between") {
				$("#search_desc_"+count).val(" AND ");
			}
		});
	});
	$("#super_query_submit").button();
	$("#super_query_submit").click(function(){
		var json_result = $("#super_query_form").serializeObject();
		jQuery("#super_query_results").jqGrid('GridUnload');
		jQuery("#super_query_results").jqGrid({
			url:"<?php echo site_url('assistant/office/super_query/');?>",
			datatype: "json",
			postData: json_result,
			mtype: "POST",
			colNames:['PID','Last Name','First Name','DOB'],
			colModel:[
				{name:'pid',index:'pid',width:50},
				{name:'lastname',index:'lastname',width:150},
				{name:'firstname',index:'firstname',width:150},
				{name:'DOB',index:'DOB',width:150,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#super_query_results_pager'),
			sortname: 'lastname',
		 	viewrecords: true,
		 	sortorder: "asc",
		 	caption:"Search Results",
		 	height: "100%",
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#super_query_results_pager',{search:false,edit:false,add:false,del:false});
	});
	$("#super_query_reset").button();
	$("#super_query_reset").click(function(){
		$("#super_query_div").html('Search patients with the following filters:<br><button type="button" id="search_add">Add </button> <input type="hidden" name="search_join[]" id="search_join_first" value="start"></input><select name="search_field[]" id="search_field_1" class="text ui-widget-content ui-corner-all search_field_class"></select> <select name="search_op[]" id="search_op_1" class="text ui-widget-content ui-corner-all search_op_class"></select> <input type="text" name="search_desc[]" id="search_desc_1"  class="text ui-widget-content ui-corner-all search_desc_class"></input>');
		$("#search_field_1").addOption({"":"Select Field","age":"Patient's age","insurance":"Patient's primary insurance","issue":"Patient's active medical issue list","billing":"Patient's billing code","rxl_medication":"Patient's active medication list","imm_immunization":"Patient's immunization list"},false);
		$("#search_op_1").addOption({"":"Select Operator"},false);
		$("#search_field_1").change(function(){
			var a = $("#search_field_1").val();
			if (a == "age") {
				$("#search_op_1").removeOption(/./);
				$("#search_op_1").addOption({"":"Select Operator","less than":"is less than","equal":"is equal to","greater than":"is greater than","contains":"contains","not equal":"is not equal to","between":"is between"},false);
				$("#search_desc_1").val("");
			}
			if (a == "issue" || a == "rxl_medication" || a == "imm_immunization" || a == "insurance") {
				$("#search_op_1").removeOption(/./);
				$("#search_op_1").addOption({"":"Select Operator","equal":"is equal to","contains":"contains","not equal":"is not equal to"},false);
				$("#search_desc_1").val("");
			}
			if (a == "billing") {
				$("#search_op_1").removeOption(/./);
				$("#search_op_1").addOption({"":"Select Operator","equal":"is equal to","not equal":"is not equal to"},false);
				$("#search_desc_1").val("");
				$("#search_desc_1").autocomplete({
					source: function (req, add){
						$.ajax({
							url: "<?php echo site_url('search/cpt');?>",
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
		});
		$("#search_op_1").change(function(){
			var a = $("#search_op_1").val();
			if (a == "between") {
				$("#search_desc_1").val(" AND ");
			}
		});
		$("#search_add").button({
			icons: {
				primary: "ui-icon-plus"
			}
		});
		$("#search_add").click(function() {
			var a = $("#super_query_div > :last-child").attr("id");
			var a1 = a.split("_");
			var count = parseInt(a1[2]) + 1;
			$("#super_query_div").append('<br><select name="search_join[]" id="search_join_'+count+'" class="text ui-widget-content ui-corner-all search_join_class"></select> <select name="search_field[]" id="search_field_'+count+'" class="text ui-widget-content ui-corner-all search_field_class"></select> <select name="search_op[]" id="search_op_'+count+'" class="text ui-widget-content ui-corner-all search_op_class"></select> <input type="text" name="search_desc[]" id="search_desc_'+count+'"  class="text ui-widget-content ui-corner-all search_desc_class"></input>');
			$("#search_field_"+count).addOption({"":"Select Field","age":"Patient's age","insurance":"Patient's primary insurance","issue":"Patient's active medical issue list","billing":"Patient's billing code","rxl_medication":"Patient's active medication list","imm_immunization":"Patient's immunization list"},false);
			$("#search_op_"+count).addOption({"":"Select Operator"},false);
			$("#search_join_"+count).addOption({"AND":"And","OR":"Or"},false);
			$("#search_field_"+count).change(function(){
				var a = $("#search_field_"+count).val();
				if (a == "sex") {
					$("#search_op_"+count).removeOption(/./);
					$("#search_op_"+count).addOption({"equal":"is equal to"},true);
					$("#search_desc_"+count).val("");
					var a_list = ["Male","Female"];
					$("#search_desc_"+count).autocomplete({
						minLength: 0,
						delay: 0,
						source: a_list
					});	
					$("#search_desc_"+count).autocomplete("search","");
				}
				if (a == "age") {
					$("#search_op_"+count).removeOption(/./);
					$("#search_op_"+count).addOption({"":"Select Operator","less than":"is less than","equal":"is equal to","greater than":"is greater than"},false);
					$("#search_desc_"+count).val("");
				}
				if (a == "issue" || a == "rxl_medication" || a == "imm_immunization") {
					$("#search_op_"+count).removeOption(/./);
					$("#search_op_"+count).addOption({"":"Select Operator","equal":"is equal to","contains":"contains","not equal":"is not equal to"},false);
					$("#search_desc_"+count).val("");
				}
				if (a == "billing") {
					$("#search_op_"+count).removeOption(/./);
					$("#search_op_"+count).addOption({"":"Select Operator","equal":"is equal to","not equal":"is not equal to"},false);
					$("#search_desc_"+count).val("");
					$("#search_desc_"+count).autocomplete({
						source: function (req, add){
							$.ajax({
								url: "<?php echo site_url('search/cpt');?>",
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
			});
			$("#search_op_"+count).change(function(){
				var a = $("#search_op_"+count).val();
				if (a == "between") {
					$("#search_desc_"+count).val(" AND ");
				}
			});
		});
		$("#super_query_form").clearForm();
		$("#search_gender_both").attr('checked',true);
		$("#search_join_first").val('start');
		jQuery("#super_query_results").jqGrid('GridUnload');
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('assistant/office/age_percentage');?>",
		dataType: "json",
		success: function(data){
			$("#age_group1").html(data.group1);
			$("#age_group2").html(data.group2);
			$("#age_group3").html(data.group3);
		}
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('start/get_sales_tax');?>",
		success: function(data){
			$("#sales_tax").val(data);
		}
	});
	$("#sales_tax").focusout(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('start/update_sales_tax');?>",
			data: "sales_tax=" + $(this).val(),
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#export_demographics").button().click(function(){
		window.open("<?php echo site_url('assistant/office/export_demographics');?>/all");
	});
	$("#export_demographics1").button().click(function(){
		window.open("<?php echo site_url('assistant/office/export_demographics');?>/active");
	});
});
</script>
<div id="heading2"></div>
<div id ="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="maincontent_full">
		<h4>NOSH ChartingSystem Office Tools</h4>
		<div id="noshtabs">
			<div id="office_provider_tabs">
				<ul>
					<li><a href="#provider_office_tabs_1">Vaccine Inventory</a></li>
					<li><a href="#provider_office_tabs_2">Vaccine Temperatures</a></li>
					<li><a href="#provider_office_tabs_3">Supplements Inventory</a></li>
					<li><a href="#provider_office_tabs_4">Queries and Reports</a></li>
					<li><a href="#provider_office_tabs_5">Export Data</a></li>
				</ul>
				<div id="provider_office_tabs_1">
					<table id="vaccine_inventory" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="vaccine_inventory_pager" class="scroll" style="text-align:center;"></div><br>
					<input type="button" id="add_vaccine" value="Add Vaccine"/>
					<input type="button" id="edit_vaccine" value="Edit Vaccine"/>
					<input type="button" id="inactivate_vaccine" value="Inactivate Vaccine"/>
					<input type="button" id="delete_vaccine" value="Delete Vaccine"/><br><br>
					<form name="edit_vaccine_form" id="edit_vaccine_form" style="display: none">
						<input type="hidden" name="vaccine_id" id="vaccine_id"/>
						<input type="hidden" name="imm_cvxcode" id="imm_cvxcode"/>
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Vaccine</legend>
							<table>
								<tbody>
									<tr>
										<td>Vaccine:</td>
										<td><input type="text" name="imm_immunization" id="imm_immunization" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
										<td></td>
									</tr>
									<tr>
										<td>Manufacturer:</td>
										<td><input type="text" name="imm_manufacturer" id="imm_manufacturer" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
										<td></td>
									</tr>
									<tr>
										<td>Brand:</td>
										<td><input type="text" name="imm_brand" id="imm_brand" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
										<td></td>
									</tr>
									<tr>
										<td>Lot number:</td>
										<td><input type="text" name="imm_lot" id="imm_lot" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
										<td></td>
									</tr>
									<tr>
										<td>CPT:</td>
										<td><input type="text" name="cpt" id="cpt" class="text ui-widget-content ui-corner-all"/></td>
										<td></td>
									</tr>
									<tr>
										<td>Quantity:</td>
										<td><input type="text" name="quantity" id="quantity" class="text ui-widget-content ui-corner-all"/></td>
										<td></td>
									</tr>
									<tr>
										<td>Date Purchased:</td>
										<td><input type="text" name="date_purchase" id="date_purchase" class="text ui-widget-content ui-corner-all"/></td>
										<td></td>
									</tr>
									<tr>
										<td>Expiration Date:</td>
										<td><input type="text" name="imm_expiration" id="imm_expiration" class="text ui-widget-content ui-corner-all"/></td>
										<td>
											<button type="button" id="save_vaccine">Save</button>
											<button type="button" id="cancel_vaccine">Cancel</button>
										</td>
									</tr>
								</tbody>
							</table>
						</fieldset><br><br>
					</form>
					<table id="vaccine_inventory_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="vaccine_inventory_inactive_pager" class="scroll" style="text-align:center;"></div><br>
					<input type="button" id="reactivate_vaccine" value="Reactivate Vaccine"/>				
				</div>
				<div id="provider_office_tabs_2">
					<table id="vaccine_temp" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="vaccine_temp_pager" class="scroll" style="text-align:center;"></div><br>
					<input type="button" id="add_temp" value="Add Vaccine Temperature"/>
					<input type="button" id="edit_temp" value="Edit Vaccine Temperature"/>
					<input type="button" id="delete_temp" value="Delete Vaccine Temperature"/><br><br>
					<form name="edit_vaccine_temp_form" id="edit_vaccine_temp_form" style="display: none">
						<input type="hidden" name="temp_id" id="temp_id"/>
						<input type="hidden" name="date" id="date">
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Vaccine Temperature</legend>
							<table>
								<tbody>
									<tr>
										<td>Date:</td>
										<td><input type="text" name="temp_date" id="temp_date" class="text ui-widget-content ui-corner-all"/></td>
										<td></td>
									</tr>
									<tr>
										<td>Time:</td>
										<td><input type="text" name="temp_time" id="temp_time" class="text ui-widget-content ui-corner-all"/></td>
										<td></td>
									</tr>
									<tr>
										<td>Temperature:</td>
										<td><input type="text" name="temp" id="temp" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
										<td></td>
									</tr>
									<tr>
										<td>Action Taken If Out of Range:</td>
										<td><input type="text" name="action" id="action" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
										<td>
											<button type="button" id="save_temp">Save</button> 
											<button type="button" id="cancel_temp">Cancel</button>
										</td>
									</tr>
								</tbody>
							</table>
						</fieldset><br><br>
					</form>
				</div>
				<div id="provider_office_tabs_3">
					Sales Tax %: <input type="text" name="sales_tax" id="sales_tax" class="text ui-widget-content ui-corner-all" placeholder="Leave blank if none"/><br><br>
					<table id="supplements_inventory" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="supplements_inventory_pager" class="scroll" style="text-align:center;"></div><br>
					<input type="button" id="add_supplement" value="Add Supplement"/>
					<input type="button" id="edit_supplement" value="Edit Supplement"/>
					<input type="button" id="inactivate_supplement" value="Inactivate Supplement"/>
					<input type="button" id="delete_supplement" value="Delete Supplement"/><br><br>
					<table id="supplements_inventory_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="supplements_inventory_inactive_pager" class="scroll" style="text-align:center;"></div><br>
					<input type="button" id="reactivate_supplement" value="Reactivate Supplement"/>
				</div>
				<div id="provider_office_tabs_4">
					<fieldset class="ui-corner-all">
						<legend>Age Distribution of Patients in the Practice:</legend>
						<table>
							<tbody>
								<tr>
									<td>0-18 years of age:</td>
									<td><div id="age_group1"></div></td>
								</tr>
								<tr>
									<td>19-64 years of age:</td>
									<td><div id="age_group2"></div></td>
								</tr>
								<tr>
									<td> 65+ years of age:</td>
									<td><div id="age_group3"></div></td>
								</tr>
							</tbody>
						</table>
					</fieldset><br><br>
					<form name="super_query_form" id="super_query_form">
						<fieldset class="ui-corner-all">
							<legend>Super Query</legend>
							<div id="super_query_div">
								Search patients with the following filters:<br>
								<button type="button" id="search_add">Add </button> <input type="hidden" name="search_join[]" id="search_join_first" value="start"/><select name="search_field[]" id="search_field_1" class="text ui-widget-content ui-corner-all search_field_class"></select> <select name="search_op[]" id="search_op_1" class="text ui-widget-content ui-corner-all search_op_class"></select> <input type="text" name="search_desc[]" id="search_desc_1"  class="text ui-widget-content ui-corner-all search_desc_class" />
							</div><br>
							Active Patients Only: <input type="checkbox" name="search_active_only" id="search_active_only" value="Yes" class="text ui-widget-content ui-corner-all"/>
							<hr class="ui-state-default"/>
							Patients without insurance: <input type="checkbox" name="search_no_insurance_only" id="search_no_insurance_only" value="Yes" class="text ui-widget-content ui-corner-all"/>
							<hr class="ui-state-default"/>
							Gender:<br>
							<input type="radio" name="search_gender" id="search_gender_both" value="both" /> Both<br>
							<input type="radio" name="search_gender" value="m" /> Male<br>
							<input type="radio" name="search_gender" value="f" /> Female<br><br>
							<input type="button" id="super_query_submit" value="Submit Query"/> <input type="button" id="super_query_reset" value="Reset Query"/> <br><br>
							<table id="super_query_results" class="scroll" cellpadding="0" cellspacing="0"></table>
							<div id="super_query_results_pager" class="scroll" style="text-align:center;"></div><br>
						</fieldset><br><br>
					</form>
				</div>
				<div id="provider_office_tabs_5">
					Export demographic information to CSV file: <button type="button" id="export_demographics">All Patients</button> <button type="button" id="export_demographics1">Active Patients Only</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="reactivate_vaccine_dialog" title="Reactivate Vaccine">
	<input type="hidden" name="vaccine_id" id="reactivate_vaccine_id"/>
	<table>
		<tr>
			<td>Quantity:</td>
			<td><input type="text" name="quantity" id="reactivate_quantity" class="text ui-widget-content ui-corner-all" /></td>
		</tr>
	</table>
</div>
<div id="supplements_dialog" title="Add/Edit Supplement">
	<form name="edit_supplement_form" id="edit_supplement_form">
		<input type="hidden" name="supplement_id" id="supplement_id"/>
		<input type="hidden" name="cpt" id="sup_cpt"/>
		<button type="button" id="save_supplement">Save</button>
		<button type="button" id="cancel_supplement">Cancel</button>
		<hr class="ui-state-default"/>
		<table>
			<tbody>
				<tr>
					<td>Supplement Description:<br>
					<input type="text" name="sup_description" id="sup_description" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Strength:<br>
					<input type="text" name="sup_strength" id="sup_strength" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Manufacturer:<br>
					<input type="text" name="sup_manufacturer" id="sup_manufacturer" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Lot Number:<br>
					<input type="text" name="sup_lot" id="sup_lot" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Charge:<br>
					$<input type="text" name="charge" id="sup_charge" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Quantity (bottles or packages):<br>
					<input type="text" name="quantity" id="sup_quantity" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Date Purchased:<br>
					<input type="text" name="date_purchase" id="sup_date_purchase" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td>Expiration Date:<br>
					<input type="text" name="sup_expiration" id="sup_expiration" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<div id="reactivate_supplement_dialog" title="Reactivate Supplement">
	<input type="hidden" name="supplement_id" id="reactivate_supplement_id"/>
	<table>
		<tr>
			<td>Quantity:</td>
			<td><input type="text" name="quantity" id="reactivate_sup_quantity" class="text ui-widget-content ui-corner-all" /></td>
		</tr>
	</table>
</div>
