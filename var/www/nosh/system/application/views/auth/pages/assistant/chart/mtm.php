<div id="mtm_dialog" title="Medication Therapy Management">
	<input type="hidden" id="mtm_origin" value=""/>
	<table id="mtm_table" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="mtm_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<div id="mtm_fax_dialog" title="Fax to Provider">
	<form name="mtm_fax_form" id="mtm_fax_form">
		Recipient:<br><input type="text" name="faxrecipient" id="mtm_fax_recipient" size="50" class="text ui-widget-content ui-corner-all"/><br>
		Fax Number:<br><input type="text" name="faxnumber" id="mtm_fax_faxnumber" class="text ui-widget-content ui-corner-all"/> <button type="button" id="mtm_fax_add_fax_contact">Add Contact to Address Book</button><br>
	</form>
</div>
<script type="text/javascript">
	$("#mtm_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		open: function(event, ui) {
			jQuery("#mtm_table").jqGrid('GridUnload');
			jQuery("#mtm_table").jqGrid({
				url:"<?php echo site_url('assistant/chartmenu/mtm/');?>",
				editurl:"<?php echo site_url('assistant/chartmenu/edit_mtm');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Topic','Recommendations','Patient Notes','Actions Taken','Outcome','Related Conditions','Duration','Date Completed'],
				colModel:[
					{name:'mtm_id',index:'mtm_id',width:1,hidden:true},
					{	name:'mtm_description',
						index:'mtm_description',
						width:200,
						editable:true,
						edittype:'text',
						editoptions:{
							size:"41",
							dataInit:function(elem){ 
								var mtm_description_select = [
									"Accountable Care Organization (ACO) coordination of care",
									"Compliance",
									"Comprehensive Medication Review (CMR)",
									"Diabetes Self Management Education and Training (DSME/T)",
									"Drug interaction",
									"Immunization",
									"Need for therapy", 
									"Nutrition",
									"Screening",
									"Side effects", 
									"Smoking cessation",
									"Suboptimal thearpy",
									"Therapeutic drug monitoring",
									"Therapeutic substitution",
									"Weight management"
								];
								$(elem).autocomplete({
									source: mtm_description_select,
									minLength: 0,
									delay: 0
								});
								$(elem).focus(function(){
									$(this).autocomplete("search","");
								});
							}
						},
						editrules:{required:true},
						formoptions:{elmsuffix:"(*)"}
					},
					{name:'mtm_recommendations',index:'mtm_recommendations',width:400,editable:true,edittype:'textarea',editoptions:{rows:"4", cols:"30"},editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
					{name:'mtm_beneficiary_notes',index:'mtm_beneficiary_notes',width:1,hidden:true,editable:true,edittype:'textarea',editoptions:{rows:"4", cols:"30"},editrules:{edithidden:true}},
					{	name:'mtm_action',
						index:'mtm_action',
						width:1,
						hidden:true,
						editable:true,
						edittype:'text',
						editoptions:{
							size:"41",
							dataInit:function(elem){ 
								var mtm_action_select = [
									"Chronic disease management", 
									"Comprehensive Medication Review (CMR)",
									"Immunization",
									"Patient coaching",
									"Patient consultation",
									"Patient education",
									"Physician consultation",
									"Physician and patient consultation",
									"Preventative service",
									"Screening",
								];
								$(elem).autocomplete({
									source: mtm_action_select,
									minLength: 0,
									delay: 0
								});
								$(elem).focus(function(){
									$(this).autocomplete("search","");
								});
							}
						},
						editrules:{edithidden:true}
					},
					{name:'mtm_outcome',index:'mtm_outcome',width:1,hidden:true,editable:true,edittype:'textarea',editoptions:{rows:"4", cols:"30"},editrules:{edithidden:true}},
					{	name:'mtm_related_conditions',
						index:'mtm_related_conditions',
						width:1,
						hidden:true,
						editable:true,
						edittype:'textarea',
						editoptions:{
							rows:"4",
							cols:"30",
							dataInit:function(elem){ 
								function split( val ) {
									return val.split( /;\s*/ );
								}
								function extractLast( term ) {
									return split( term ).pop();
								}
								$(elem).autocomplete({
									source: function (req, add){
										$.ajax({
											url: "<?php echo site_url('assistant/chartmenu/search_issues');?>",
											dataType: "json",
											type: "POST",
											data: "term=" + extractLast(req.term),
											success: function(data){
												if(data.response =='true'){
													add(data.message);
												}
											}
										});
									},
									search: function() {
										var term = extractLast( this.value );
										if ( term.length < 2 ) {
											return false;
										}
									},
									focus: function() {
										return false;
									},
									select: function(event, ui){
										var terms = split( this.value );
										terms.pop();
										terms.push( ui.item.value );
										terms.push( "" );
										this.value = terms.join( ";" );
										return false;
									},
									minLength: 0
								});
								$(elem).focus(function(){
									$(this).autocomplete("search","");
								});
							}
						},
						editrules:{edithidden:true}
					},
					{	name:'mtm_duration',
						index:'mtm_duration',
						width:1,
						hidden:true,
						editable:true,
						edittype:'text',
						editoptions:{
							size:"41",
							dataInit:function(elem){ 
								var mtm_duration_select = [
									"10 minutes", 
									"20 minutes",
									"30 minutes",
									"40 minutes",
									"50 minutes",
									"60 minutes"
								];
								$(elem).autocomplete({
									source: mtm_duration_select,
									minLength: 0,
									delay: 0
								});
								$(elem).focus(function(){
									$(this).autocomplete("search","");
								});
							}
						},
						editrules:{edithidden:true}
					},
					{	name:'mtm_date_completed',
						index:'mtm_date_completed',
						width:100,
						editable:true,
						edittype:'text',
						editoptions:{
							size:"41",
							dataInit:function(elem){
								$(elem).datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
								$(elem).mask("99/99/9999");
							}
						},
						formatter:'date',
						formatoptions:{srcformat:"ISO8601Long", newformat: "m/d/Y"}
					}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#mtm_pager'),
				sortname: 'mtm_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Medication Action Plan Items",
			 	height: "100%",
			 	multiselect: true,
				multiboxonly: true,
				onCellSelect: function(id,iCol) {
					if (iCol > 0) {
			 			jQuery("#mtm_table").viewGridRow(id,{width:500, labelswidth:"35%"});
			 		}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#mtm_pager',{search:false},{width:400, closeAfterEdit:true},{width:400, closeAfterAdd:true});
			$.ajax({
				url: "<?php echo site_url('start/check_fax');?>",
				type: "POST",
				success: function(data){
					if (data != "Yes") {
						$("#mtm_dialog_fax_provider").hide();
					}
				}
			});
		},
		buttons: [{
			text: 'Print',
			id: 'mtm_dialog_print',
			click: function() {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/chartmenu/print_mtm');?>",
					dataType: 'json',
					async: false,
					success: function(data){
						if (data.message == 'OK') {
							success_doc = true;
							id_doc = data.id;
						} else {
							$.jGrowl(data.message);
						}
					}
				});
				if (success_doc == true) {
					window.open("<?php echo site_url('assistant/chartmenu/view_documents');?>/" + id_doc);
					success_doc = false;
					id_doc = '';
				}
			}
		},{
			text: 'Preview',
			id: 'mtm_dialog_preview',
			click: function() {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/chartmenu/print_mtm');?>",
					dataType: 'json',
					async: false,
					success: function(data){
						if (data.message == 'OK') {
							success_doc = true;
							id_doc = data.id;
						} else {
							$.jGrowl(data.message);
						}
					}
				});
				if (success_doc == true) {
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/chartmenu/view_documents1');?>/" + id_doc,
						dataType: "json",
						success: function(data){
							//$('#embedURL').PDFDoc( { source : data.html } );
							$("#embedURL").html(data.html);
							$("#document_filepath").val(data.filepath);
							$("#documents_view_dialog").dialog('open');
						}
					});
					success_doc = false;
					id_doc = '';
				}
			}
		},{
			text: 'Print to Provider',
			id: 'mtm_dialog_print_provider',
			click: function() {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/chartmenu/print_mtm_assistant/print');?>",
					dataType: 'json',
					async: false,
					success: function(data){
						if (data.message == 'OK') {
							success_doc = true;
							id_doc = data.id;
						} else {
							$.jGrowl(data.message);
						}
					}
				});
				if (success_doc == true) {
					window.open("<?php echo site_url('assistant/chartmenu/view_documents');?>/" + id_doc);
					success_doc = false;
					id_doc = '';
				}
			}
		},{
			text: "Fax to Provider",
			id: 'mtm_dialog_fax_provider',
			click: function() {
				$("#mtm_fax_dialog").dialog('open');
			}
		},{
			text: "Close",
			id: 'mtm_dialog_close',
			click: function() {
				var a = $("#mtm_origin").val();
				if (a == "encounter") {
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/chartmenu/encounter_mtm');?>",
						dataType: 'json',
						success: function(data){
							var old = $("#orders_plan").val();
							if(old){
								var pos = old.lastIndexOf('\n');
								if (pos == -1) {
									var old1 = old + '\n';
								} else {
									var a = old.slice(pos);
									if (a == '') {
										var old1 = old;
									} else {
										var old1 = old + '\n';
									}
								}
							} else {
								var old1 = '';
							}
							if (data.value != '') {
								$("#orders_plan").val(old1+data.value);
							}
							if (data.duaration != '') {
								$("#orders_duration").val(data.duration);
							}
							$("#mtm_origin").val('');
							$("#mtm_dialog").dialog('close');
						}
					});
				}
				$("#mtm_origin").val('');
				$("#mtm_dialog").dialog('close');
			}
		}]
	});
	$("#mtm_fax_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#mtm_fax_recipient").val();
			var b = $("#mtm_fax_faxnumber").val();
			if(a != '' || b != ''){
				if(confirm('Are you sure you want to close this window?  If not, press Cancel and press Save to update the form.')){ 
					$('#mtm_fax_form').clearForm();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		},
		buttons: {
			'Send Fax': function() {
				var recipient = $("#mtm_fax_recipient");
				var number = $("#mtm_fax_faxnumber");
				var bValid = true;
				bValid = bValid && checkEmpty(recipient,"Recipient");
				bValid = bValid && checkEmpty(number,"Fax Number");
				if (bValid) {
					var str = $("#mtm_fax_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('assistant/chartmenu/print_mtm_assistant/fax');?>/",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								$('#mtm_fax_form').clearForm();
								$('#mtm_fax_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			'Cancel': function() {
				$('#mtm_fax_form').clearForm();
				$('#mtm_fax_dialog').dialog('close');
			}
		}
	});
	$("#mtm_fax_faxnumber").mask("(999) 999-9999");
	$("#mtm_fax_recipient").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/all_contacts');?>",
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
			$("#mtm_fax_faxnumber").val(ui.item.fax);
		}
	});
	$("#mtm_fax_add_fax_contact").button({icons: {primary: "ui-icon-plus"}}).click(function(){
		var recipient = $("#mtm_fax_recipient");
		var number = $("#mtm_fax_faxnumber");
		var bValid = true;
		bValid = bValid && checkEmpty(recipient,"Recipient");
		bValid = bValid && checkEmpty(number,"Fax Number");
		if (bValid) {
			var str = $("#mtm_fax_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/chartmenu/add_all_contact');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#mtm_list").click(function() {
		$("#mtm_dialog").dialog('open');
	});
	$(".mtm_tooltip").tooltip({
		items: ".mtm_tooltip",
		content: "Medication Therapy Management"
	});
</script>
