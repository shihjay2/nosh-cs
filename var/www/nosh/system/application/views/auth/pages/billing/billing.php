<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load('<?php echo site_url("search/loadpage");?>');
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("billing/billing");?>',
		redirect_url: '<?php echo site_url("start");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: 300000
	});
	jQuery("#submit_list").jqGrid({
		url:"<?php echo site_url('billing/billing/submit_list/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','Status','Batch Type','Last Name','First Name','Chief Complaint'],
		colModel:[
			{name:'eid',index:'eid',width:1,hidden:true},
			{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'encounter_signed',index:'encounter_signed',width:110,formatter:signedlabel},
			{name:'bill_submitted',index:'bill_submitted',width:110,formatter:batchlabel},
			{name:'lastname',index:'lastname',width:150},
			{name:'firstname',index:'firstname',width:150},
			{name:'encounter_cc',index:'encounter_cc',width:250}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#submit_list_pager'),
		sortname: 'encounter_DOS',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Billed Encounters Waiting to be Submitted",
	 	height: "100%",
	 	onSelectRow: function(id) {
	 		$("#billing_eid").val(id);
	 		$("#submit_bill_form_id").show('fast');
	 	},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#submit_list_pager',{search:false,edit:false,add:false,del:false});
	function signedlabel (cellvalue, options, rowObject){
		if (cellvalue == 'No') {
			return 'Draft';
		}
		if (cellvalue == 'Yes') {
			return 'Signed';
		}
	}
	function batchlabel (cellvalue, options, rowObject){
		if (cellvalue == 'No') {
			return 'None';
		}
		if (cellvalue == 'Pend') {
			return 'Print Image';
		}
		if (cellvalue == 'HCFA') {
			return 'HCFA-1500';
		}
	}
	$("#submit_batch_printimage").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#submit_batch_printimage").click(function(){
		var eid = $("#billing_eid").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/billing/add_queue');?>",
			data: "eid=" + eid,
			success: function(data){
				$.jGrowl(data);
				$("#billing_eid").val('');
				$('#submit_bill_form_id').hide('fast');
				jQuery("#submit_list").trigger("reloadGrid");
			}
		});	
	});
	$("#submit_batch_hcfa").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#submit_batch_hcfa").click(function(){
		var eid = $("#billing_eid").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/billing/add_queue1');?>",
			data: "eid=" + eid,
			success: function(data){
				$.jGrowl(data);
				$("#billing_eid").val('');
				$('#submit_bill_form_id').hide('fast');
				jQuery("#submit_list").trigger("reloadGrid");
			}
		});	
	});
	$("#submit_single_printimage").button({
		icons: {
			primary: "ui-icon-document"
		}
	});
	$("#submit_single_printimage").click(function(){
		var eid = $("#billing_eid").val();
		window.open("<?php echo site_url ('billing/billing/printimage_single');?>/" + eid);
		$("#billing_eid").val('');
		jQuery("#submit_list").trigger("reloadGrid");
		jQuery("#bills_done").trigger("reloadGrid");
		$('#submit_bill_form_id').hide('fast');
	});
	$("#submit_hcfa").button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$("#submit_hcfa").click(function(){
		var eid = $("#billing_eid").val();
		window.open("<?php echo site_url('billing/billing/generate_hcfa');?>/" + eid);
		$("#billing_eid").val('');
		$('#submit_bill_form_id').hide('fast');
	});
	$("#submit_batch").button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$("#submit_batch").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/billing/check_batch');?>",
			success: function(data){
				if (data == 'OK') {
					window.location = "<?php echo site_url ('billing/billing/printimage_batch');?>";
				} else {
					$.jGrowl("No bills in the queue to batch print!");
				}
			}
		});
	});
	$("#submit_batch").mouseleave(function(){
		jQuery("#submit_list").trigger("reloadGrid");
		jQuery("#bills_done").trigger("reloadGrid");
	});
	$("#submit_batch1").button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$("#submit_batch1").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/billing/check_batch1');?>",
			success: function(data){
				if (data == 'OK') {
					window.location = "<?php echo site_url ('billing/billing/printhcfa_batch');?>";
				} else {
					$.jGrowl("No bills in the queue to batch print!");
				}
			}
		});
	});
	jQuery("#bills_done").jqGrid({
		url:"<?php echo site_url('billing/billing/bills_done/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','Last Name','First Name','Chief Complaint','Charges','Total Balance'],
		colModel:[
			{name:'eid',index:'eid',width:1,hidden:true},
			{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'lastname',index:'lastname',width:160},
			{name:'firstname',index:'firstname',width:160},
			{name:'encounter_cc',index:'encounter_cc',width:225},
			{name:'charges',index:'charges',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			{name:'balance',index:'balance',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#bills_done_pager'),
		sortname: 'encounter_DOS',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Billed Encounters that have been Processed",
	 	height: "100%",
	 	loadComplete: function(data) {
	 		var id1 = $('#billing_list_eid').val();
	 		if (id1 != '') {
	 			jQuery("#bills_done").expandSubGridRow(id1);
	 			$('#billing_list_eid').val('');
	 		}
	 	},
	 	subGrid: true,
	 	subGridRowExpanded: function(subgrid_id, row_id) {
	 		var subgrid_table_id, pager_id;
	 		subgrid_table_id = subgrid_id+"_t";
	 		pager_id = "p_"+subgrid_table_id;
	 		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
	 		jQuery("#"+subgrid_table_id).jqGrid({
	 			url:"<?php echo site_url('billing/billing/billing_payment_history1');?>/"+row_id,
	 			datatype: "json",
	 			mtype: "POST",
	 			colNames:['ID','Date of Payment','Payment Amount','Payment Type'],
	 			colModel:[
	 				{name:"billing_core_id",index:"billing_core_id",width:1,hidden:true},
	 				{name:"dos_f",index:"dos_f",width:100,formatter:'date',formatoptions:{srcformat:"m/d/Y", newformat: "ISO8601Short"}},
	 				{name:"payment",index:"payment",width:200,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
	 				{name:"payment_type",index:"payment_type",width:300,align:"right"}, 
	 			], 
	 			rowNum:10,
	 			pager: pager_id,
	 			sortname: 'dos_f', 
	 			sortorder: "desc", 
	 			height: '100%',
	 			footerrow : true,
	 			userDataOnFooter : true,
	 			onSelectRow: function(id) {
	 				$('#billing_billing_core_id').val(id);
	 				$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/billing/get_payment');?>",
						data: "id=" + id,
						dataType: "json",
						success: function(data){
							$('#billing_payment_eid').val(data.eid);
							$('#billing_payment_payment').val(data.payment);
							$('#billing_payment_payment_type').val(data.payment_type);
							$('#billing_payment_dos_f').val(data.dos_f);
							$('#billing_payment_payment_old').val(data.payment);
							$('#billing_payment_payment_type_old').val(data.payment_type);
							$('#billing_payment_dos_f_old').val(data.dos_f);
							$('#billing_payment_dialog').dialog('open');
						}
					});
	 			},
	 			jsonReader: { repeatitems : false, id: "0" }
	 		});
	 		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
	 			search:false,
	 			edit:false,
	 			add:false,
	 			del:false
	 		}).jqGrid('navButtonAdd',"#"+pager_id,{
	 			caption:"Delete Payment", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery(this).getGridParam('selrow');
					if(id){
		 				$('#billing_billing_core_id').val(id);
		 				$.ajax({
							type: "POST",
							url: "<?php echo site_url('billing/billing/get_payment');?>",
							data: "id=" + id,
							dataType: "json",
							success: function(data){
								$('#billing_list_eid').val(data.eid);
							}
						});
		 				if(confirm('Are you sure you want to delete this payment?')){
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('billing/billing/delete_payment1');?>",
								data: "id=" + id,
								dataType: 'json',
								success: function(data){
									if (data.message == 'Close Chart') {
										window.location = "<?php echo site_url();?>";
									} else {
										$.jGrowl(data.message);
										$("#billing_encounters").setCell(data.id,"balance",data.balance); 
									}
								}
							});
							jQuery(this).trigger("reloadGrid");
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('billing/billing/total_balance/');?>",
								success: function(data){
									$('#total_balance').html(data);
								}
							});
						}
					} else {
						$.jGrowl('Choose payment to delete!');
					}
				}, 
				position:"last"
			});
	 	}
	}).navGrid('#bills_done_pager',{search:false,edit:false,add:false,del:false});
	$("#bill_resubmit").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#bill_resubmit").click(function(){
		var eid = jQuery("#bills_done").getGridParam('selrow');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/billing/bill_resubmit');?>",
			data: "eid=" + eid,
			success: function(data){
				$.jGrowl(data);
				jQuery("#bills_done").trigger("reloadGrid");
				jQuery("#submit_list").trigger("reloadGrid");
			}
		});	
	});
	$("#edit_encounter_charge").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#edit_encounter_charge").click(function(){
		var id = jQuery("#billing_encounters").getGridParam('selrow');
		if(id){
			$("#billing_eid_1").val(id);
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/get_billing1');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#billing_icd1").addOption(data, false).trigger("liszt:updated");
				}
			});
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/get_prevention');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#established_prevent1").attr("value", data.prevent_established1);
					$("#new_prevent1").attr("value", data.prevent_new1);
					$("#established_prevent1_text").html(data.prevent_established1);
					$("#new_prevent1_text").html(data.prevent_new1);
				}
			});	
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/get_insurance_id1');?>/" + id,
				dataType: "json",
				success: function(data){
					$("#billing_insurance_id_1").val(data.insurance_id_1);
					$("#billing_insurance_id_2").val(data.insurance_id_2);
					$("#billing_insurance_id_1_old").val(data.insurance_id_1);
					$("#billing_insurance_id_2_old").val(data.insurance_id_2);
					if (data.insurance_id_1 == '') {
						$("#billing_insuranceinfo1").html("No primary insurance chosen");
					}
					if (data.insurance_id_2 == '') {
						$("#billing_insuranceinfo2").html("No secondary insurance chosen");
					}
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/encounters/get_insurance_info');?>",
						data: "insurance_id_1=" + data.insurance_id_1 + "&insurance_id_2=" + data.insurance_id_2,
						dataType: "json",
						success: function(data){
							$("#billing_insuranceinfo1").html(data.result1);
							$("#billing_insuranceinfo2").html(data.result2);
						}
					});
				}
			});
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/get_assessment');?>/" + id,
				dataType: "json",
				success: function(data){
					if (data != '') {
						if(data.assessment_1.length!=0){
							var label1 = '<strong>Diagnosis #1:</strong> ' + data.assessment_1;
						}
						if(data.assessment_2.length!=0){
							label1 += '<br><strong>Diagnosis #2:</strong> ' + data.assessment_2;
						}
						if(data.assessment_3.length!=0){
							label1 += '<br><strong>Diagnosis #3:</strong> ' + data.assessment_3;
						}
						if(data.assessment_4.length!=0){
							label1 += '<br><strong>Diagnosis #4:</strong> ' + data.assessment_4;
						}
						if(data.assessment_5.length!=0){
							label1 += '<br><strong>Diagnosis #5:</strong> ' + data.assessment_5;
						}
						if(data.assessment_6.length!=0){
							label1 += '<br><strong>Diagnosis #6:</strong> ' + data.assessment_6;
						}
						if(data.assessment_7.length!=0){
							label1 += '<br><strong>Diagnosis #7:</strong> ' + data.assessment_7;
						}
						if(data.assessment_8.length!=0){
							label1 += '<br><strong>Diagnosis #8:</strong> ' + data.assessment_8;
						}
						$("#billing_icd9").html(label1);
					}
				}
			});
			jQuery("#billing_cpt_list").jqGrid('GridUnload');
			jQuery("#billing_cpt_list").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/procedure_codes1');?>/" + id,
				datatype: "json",
				mtype: "POST",
				colNames:['ID','CPT','CPT Description','Charge','Units','Modifier','ICD Pointer','DOS From','DOS To'],
				colModel:[
					{name:'billing_core_id',index:'billing_core_id',width:1,hidden:true},
					{name:'cpt',index:'cpt',width:50},
					{name:'cpt_description',index:'cpt_description',width:100},
					{name:'cpt_charge',index:'cpt_charge',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:'unit',index:'unit',width:50},
					{name:'modifier',index:'modifier',width:50},
					{name:'icd_pointer',index:'icd_pointer',width:50,edittype: 'select'},
					{name:'dos_f',index:'dos_f',width:75},
					{name:'dos_t',index:'dos_t',width:75}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#billing_cpt_list_pager'),
				sortname: 'cpt_charge',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Procedure codes for this encounter - Double click on row to get diagnosis codes for each procedure.",
			 	height: "100%",
			 	ondblClickRow: function(id){
			 		var item = jQuery("#billing_cpt_list").getCell(id,'icd_pointer');
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/chartmenu/define_icd');?>/" + id,
						data: "icd=" + item,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.item, {sticky:true});	
						}
					});
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#billing_cpt_list_pager',{search:false,edit:false,add:false,del:false});
	 		$("#billing_detail_dialog").dialog('open');
		} else {
			$.jGrowl("Please select encounter to edit billing details!");
		}
	});
	$("#payment_encounter_charge1").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	$("#payment_encounter_charge1").click(function(){
		var item = jQuery("#bills_done").getGridParam('selrow');
		if(item){
			$('#billing_payment_eid').val(item);
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/set_billing_id1');?>/" + item,
				success: function(data){
					if (data == "Set!") {
						var currentDate = getCurrentDate();
						$('#billing_payment_payment').val('');
						$('#billing_payment_payment_old').val('');
						$('#billing_payment_payment_type').val('');
						$('#billing_payment_payment_type_old').val('');
						$('#billing_payment_dos_f').val(currentDate);
						$('#billing_payment_dos_f_old').val(currentDate);
						$('#billing_payment_dialog').dialog('open');
						$("#billing_payment_payment").focus();
					}
				}
			});
		} else {
			$.jGrowl("Please select encounter to add payment!");
		}
	});
	
	jQuery("#outstanding_balance").jqGrid({
		url:"<?php echo site_url('billing/billing/outstanding_balance/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Last Name','First Name','Balance','Notes'],
		colModel:[
			{name:'pid',index:'pid',width:100},
			{name:'lastname',index:'lastname',width:150},
			{name:'firstname',index:'firstname',width:150},
			{name:'balance',index:'balance',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			{name:'billing_notes',index:'billing_notes',width:300}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#outstanding_balance_pager'),
		sortname: 'lastname',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Patients with Outstanding Balances - Click for details",
	 	height: "100%",
	 	onSelectRow: function(id) {
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/set_billing_id');?>/" + id,
				success: function(data){
					if (data == "Set!") {
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('billing/billing/total_balance/');?>",
							success: function(data){
								$('#total_balance').html(data);
							}
						});
						jQuery("#billing_encounters").jqGrid('GridUnload');
						jQuery("#billing_encounters").jqGrid({
							url:"<?php echo site_url('billing/billing/billing_encounters/');?>",
							datatype: "json",
							mtype: "POST",
							colNames:['ID','Date','Chief Complaint','Charges','Balance'],
							colModel:[
								{name:'eid',index:'eid',width:1,hidden:true},
								{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
								{name:'encounter_cc',index:'encounter_cc',width:355},
								{name:"charges",index:"charges",width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
								{name:"balance",index:"balance",width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}}
							],
							rowNum:10,
							rowList:[10,20,30],
							pager: jQuery('#billing_encounters_pager'),
							sortname: 'encounter_DOS',
						 	viewrecords: true,
						 	sortorder: "desc",
						 	caption:"Bills from Encounters - Expand Row for Payment History",
						 	height: "100%",
						 	loadComplete: function(data) {
						 		var id1 = $('#billing_list_eid').val();
						 		var id2 = $('#billing_list_other_billing_id').val();
						 		if (id1 != '') {
						 			jQuery("#billing_encounters").expandSubGridRow(id1);
						 			$('#billing_list_eid').val('');
						 		}
						 		if (id2 != '') {
						 			jQuery("#billing_other").expandSubGridRow(id2);
						 			$('#billing_list_other_billing_id').val('');
						 		}
						 	},
						 	subGrid: true,
						 	subGridRowExpanded: function(subgrid_id, row_id) {
						 		var subgrid_table_id, pager_id;
						 		subgrid_table_id = subgrid_id+"_t";
						 		pager_id = "p_"+subgrid_table_id;
						 		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
						 		jQuery("#"+subgrid_table_id).jqGrid({
						 			url:"<?php echo site_url('billing/billing/billing_payment_history1');?>/"+row_id,
						 			datatype: "json",
						 			mtype: "POST",
						 			colNames:['ID','Date of Payment','Payment Amount','Payment Type'],
						 			colModel:[
						 				{name:"billing_core_id",index:"billing_core_id",width:1,hidden:true},
						 				{name:"dos_f",index:"dos_f",width:100,formatter:'date',formatoptions:{srcformat:"m/d/Y", newformat: "ISO8601Short"}},
						 				{name:"payment",index:"payment",width:200,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
						 				{name:"payment_type",index:"payment_type",width:300,align:"right"}, 
						 			], 
						 			rowNum:10,
						 			pager: pager_id,
						 			sortname: 'dos_f', 
						 			sortorder: "desc", 
						 			height: '100%',
						 			footerrow : true,
						 			userDataOnFooter : true,
						 			onSelectRow: function(id) {
						 				$('#billing_billing_core_id').val(id);
						 				$.ajax({
											type: "POST",
											url: "<?php echo site_url('billing/billing/get_payment');?>",
											data: "id=" + id,
											dataType: "json",
											success: function(data){
												$('#billing_payment_eid').val(data.eid);
												$('#billing_payment_payment').val(data.payment);
												$('#billing_payment_payment_type').val(data.payment_type);
												$('#billing_payment_dos_f').val(data.dos_f);
												$('#billing_payment_payment_old').val(data.payment);
												$('#billing_payment_payment_type_old').val(data.payment_type);
												$('#billing_payment_dos_f_old').val(data.dos_f);
												$('#billing_payment_dialog').dialog('open');
											}
										});
						 			},
						 			jsonReader: { repeatitems : false, id: "0" }
						 		});
						 		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
						 			search:false,
						 			edit:false,
						 			add:false,
						 			del:false
						 		}).jqGrid('navButtonAdd',"#"+pager_id,{
						 			caption:"Delete Payment", 
									buttonicon:"ui-icon-trash", 
									onClickButton: function(){ 
										var id = jQuery(this).getGridParam('selrow');
										if(id){
							 				$('#billing_billing_core_id').val(id);
							 				$.ajax({
												type: "POST",
												url: "<?php echo site_url('billing/billing/get_payment');?>",
												data: "id=" + id,
												dataType: "json",
												success: function(data){
													$('#billing_list_eid').val(data.eid);
												}
											});
							 				if(confirm('Are you sure you want to delete this payment?')){
												$.ajax({
													type: "POST",
													url: "<?php echo site_url('billing/billing/delete_payment1');?>",
													data: "id=" + id,
													dataType: 'json',
													success: function(data){
														if (data.message == 'Close Chart') {
															window.location = "<?php echo site_url();?>";
														} else {
															$.jGrowl(data.message);
															$("#billing_encounters").setCell(data.id,"balance",data.balance); 
														}
													}
												});
												jQuery(this).trigger("reloadGrid");
												$.ajax({
													type: "POST",
													url: "<?php echo site_url('billing/billing/total_balance/');?>",
													success: function(data){
														$('#total_balance').html(data);
													}
												});
											}
										} else {
											$.jGrowl('Choose payment to delete!');
										}
									}, 
									position:"last"
								});
						 	}
						}).navGrid('#billing_encounters_pager',{search:false,edit:false,add:false,del:false});
						jQuery("#billing_other").jqGrid('GridUnload');
						jQuery("#billing_other").jqGrid({
							url:"<?php echo site_url('billing/billing/billing_other/');?>",
							datatype: "json",
							mtype: "POST",
							colNames:['ID','Date','Reason','Charge','Balance'],
							colModel:[
								{name:'other_billing_id',index:'other_billing_id',width:1,hidden:true},
								{name:'dos_f',index:'dos_f',width:100,formatter:'date',formatoptions:{srcformat:"m/d/Y", newformat: "ISO8601Short"},unformat:editDate},
								{name:'reason',index:'reason',width:355},
								{name:'cpt_charge',index:'cpt_charge',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},		
								{name:"balance",index:"balance",width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}}
							],
							rowNum:10,
							rowList:[10,20,30],
							pager: jQuery('#billing_other_pager'),
							sortname: 'dos_f',
						 	viewrecords: true,
						 	sortorder: "desc",
						 	caption:"Miscellaneous Bills - Expand Row for Payment History",
						 	height: "100%",
						 	loadComplete: function(data) {
						 		var id1 = $('#billing_list_eid').val();
						 		var id2 = $('#billing_list_other_billing_id').val();
						 		if (id1 != '') {
						 			jQuery("#billing_encounters").expandSubGridRow(id1);
						 			$('#billing_list_eid').val('');
						 		}
						 		if (id2 != '') {
						 			jQuery("#billing_other").expandSubGridRow(id2);
						 			$('#billing_list_other_billing_id').val('');
						 		}
						 	},
						 	subGrid: true,
						 	subGridRowExpanded: function(subgrid_id, row_id) {
						 		var subgrid_table_id, pager_id;
						 		subgrid_table_id = subgrid_id+"_t1";
						 		pager_id = "p1_"+subgrid_table_id;
						 		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
						 		jQuery("#"+subgrid_table_id).jqGrid({
						 			url:"<?php echo site_url('billing/billing/billing_payment_history2');?>/"+row_id,
						 			datatype: "json",
						 			mtype: "POST",
						 			colNames:['ID','Date of Payment','Payment Amount','Payment Type'],
						 			colModel:[
						 				{name:"billing_core_id",index:"billing_core_id",width:1,hidden:true},
						 				{name:"dos_f",index:"dos_f",width:100,formatter:'date',formatoptions:{srcformat:"m/d/Y", newformat: "ISO8601Short"}},
						 				{name:"payment",index:"payment",width:200,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
						 				{name:"payment_type",index:"payment_type",width:300,align:"right"}, 
						 			], 
						 			rowNum:10,
						 			pager: pager_id,
						 			sortname: 'dos_f', 
						 			sortorder: "desc", 
						 			height: '100%',
						 			footerrow : true,
						 			userDataOnFooter : true,
						 			onSelectRow: function(id) {
						 				$('#billing_billing_core_id').val(id);
						 				$.ajax({
											type: "POST",
											url: "<?php echo site_url('billing/billing/get_payment');?>",
											data: "id=" + id,
											dataType: "json",
											success: function(data){
												$('#billing_payment_other_billing_id').val(data.other_billing_id);
												$('#billing_payment_payment').val(data.payment);
												$('#billing_payment_payment_type').val(data.payment_type);
												$('#billing_payment_dos_f').val(data.dos_f);
												$('#billing_payment_payment_old').val(data.payment);
												$('#billing_payment_payment_type_old').val(data.payment_type);
												$('#billing_payment_dos_f_old').val(data.dos_f);
												$('#billing_payment_dialog').dialog('open');
											}
										});
						 			},
						 			jsonReader: { repeatitems : false, id: "0" }
						 		});
						 		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
						 			search:false,
						 			edit:false,
						 			add:false,
						 			del:false
						 		}).jqGrid('navButtonAdd',"#"+pager_id,{
						 			caption:"Delete Payment", 
									buttonicon:"ui-icon-trash", 
									onClickButton: function(){ 
										var id = jQuery(this).getGridParam('selrow');
										if(id){
							 				$('#billing_billing_core_id').val(id);
							 				$.ajax({
												type: "POST",
												url: "<?php echo site_url('billing/billing/get_payment');?>",
												data: "id=" + id,
												dataType: "json",
												success: function(data){
													$('#billing_list_eid').val(data.eid);
												}
											});
							 				if(confirm('Are you sure you want to delete this payment?')){
												$.ajax({
													type: "POST",
													url: "<?php echo site_url('billing/billing/delete_payment2');?>",
													data: "id=" + id,
													dataType: 'json',
													success: function(data){
														if (data.message == 'Close Chart') {
															window.location = "<?php echo site_url();?>";
														} else {
															$.jGrowl(data.message);
															$("#billing_other").setCell(data.id,"balance",data.balance); 
														}
													}
												});
												jQuery(this).trigger("reloadGrid");
												$.ajax({
													type: "POST",
													url: "<?php echo site_url('billing/billing/total_balance/');?>",
													success: function(data){
														$('#total_balance').html(data);
													}
												});
											}
										} else {
											$.jGrowl('Choose payment to delete!');
										}
									}, 
									position:"last"
								});
						 	}
						}).navGrid('#billing_other_pager',{search:false,edit:false,add:false,del:false});
						jQuery("#billing_insurance_list1").jqGrid('GridUnload');
						jQuery("#billing_insurance_list1").jqGrid({
							url:"<?php echo site_url('billing/billing/insurance/');?>",
							datatype: "json",
							mtype: "POST",
							colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Copay','Deductible','Comments','Address ID','Relationship'],
							colModel:[
								{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
								{name:'insurance_plan_name',index:'insurance_plan_name',width:350},
								{name:'insurance_id_num',index:'insurance_id_num',width:100},
								{name:'insurance_group',index:'insurance_group',width:100},
								{name:'insurance_order',index:'insurance_order',width:105},
								{name:'insurance_insu_lastname',index:'insurance_insu_lastname',width:1,hidden:true},
								{name:'insurance_insu_firstname',index:'insurance_insu_firstname',width:1,hidden:true},
								{name:'insurance_insu_dob',index:'insurance_insu_dob',width:1,hidden:true},
								{name:'insurance_insu_gender',index:'insurance_insu_gender',width:1,hidden:true},
								{name:'insurance_insu_address',index:'insurance_insu_address',width:1,hidden:true},
								{name:'insurance_insu_city',index:'insurance_insu_city',width:1,hidden:true},
								{name:'insurance_insu_state',index:'insurance_insu_state',width:1,hidden:true},
								{name:'insurance_insu_zip',index:'insurance_insu_zip',width:1,hidden:true},
								{name:'insurance_copay',index:'insurance_copay',width:1,hidden:true},
								{name:'insurance_deductible',index:'insurance_deductible',width:1,hidden:true},
								{name:'insurance_comments', index:'insurance_comments',width:1,hidden:true},
								{name:'address_id',index:'address_id',width:1,hidden:true},
								{name:'insurance_relationship',index:'insurance_relationship',width:1,hidden:true}
							],
							rowNum:10,
							rowList:[10,20,30],
							pager: jQuery('#billing_insurance_pager1'),
							sortname: 'insurance_order',
						 	viewrecords: true,
						 	sortorder: "asc",
						 	caption:"Insurance Payors",
						 	height: "100%",
						 	onSelectRow: function(id){
						 		var insurance_plan_name = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_plan_name');
								var insurance_id_num = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_id_num');
								var insurance_group = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_group');
								var insurance_insu_lastname = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_insu_lastname');
								var insurance_insu_firstname = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_insu_firstname');
								var text = insurance_plan_name + '; ID: ' + insurance_id_num;
								if(insurance_group != ''){
									text += "; Group: " + insurance_group;
								}
								text += "; " + insurance_insu_lastname + ", " + insurance_insu_firstname;
								var old = $("#messages_lab_insurance").val();
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
								$("#messages_lab_insurance").val(old1+text);
							},
						 	jsonReader: { repeatitems : false, id: "0" }
						}).navGrid('#billing_insurance_pager1',{search:false,edit:false,add:false,del:false});
						$("#billing_list_dialog").dialog('open');
					}
				}
			});	
		},
		jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#outstanding_balance_pager',{search:false,edit:false,add:false,del:false});
	$("#billing_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false
	});
	$("#billing_list").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/billing/total_balance/');?>",
			success: function(data){
				$('#total_balance').html(data);
			}
		});
		$("#billing_list_dialog").dialog('open');
	});
	$("#billing_detail_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 840, 
		modal: true,
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			var b = $("input[name='billing_cpt_helper1']:checked").val();
			if (b != '') {
				$("#billing_cpt1").val(b);
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/encounters/get_cpt_charge');?>",
					data: "cpt=" + b,
					success: function(data){
						$("#billing_cpt_charge1").val(data);
					}
				});
			} else {
				$("#billing_cpt1").val('');
				$("#billing_cpt_charge1").val('');
			}
			$('#cpt_helper_items1').clearDiv();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/total_balance/');?>",
				success: function(data){
					$('#total_balance').html(data);
				}
			});
		}
	});
	$("#billing_other_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#billing_other_reason1").val();
			var b = $("#billing_other_cpt_charge1").val();
			var c = $("#billing_other_dos_f1").val();
			var a1 = $("#billing_other_reason_old").val();
			var b1 = $("#billing_other_cpt_charge_old").val();
			var c1 = $("#billing_other_dos_f_old").val();
			if(a != a1 || b != b1 || c != c1){
				if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Save to update the form.')){ 
					$('#billing_other_form1').clearForm();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/billing/total_balance/');?>",
						success: function(data){
							$('#total_balance').html(data);
						}
					});
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#billing_notes_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 600, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#billing_billing_notes").val();
			var a1 = $("#billing_billing_notes_old").val();
			if(a != a1){
				if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Save to update the form.')){ 
					$('#billing_notes_form').clearForm();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#billing_notes").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#billing_notes").click(function(){
		$('#billing_notes_form').clearForm();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/billing/get_billing_notes/');?>",
			success: function(data){
				$('#billing_billing_notes').val(data);
				$('#billing_billing_notes_old').val(data);
			}
		});
		$('#billing_notes_dialog').dialog('open');
	});
	$("#save_billing_notes").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_billing_notes").click(function(){
		var str = $("#billing_notes_form").serialize();		
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/edit_billing_notes');?>",
				data: str,
				success: function(data){
					if (data == 'Close Chart') {
						window.location = "<?php echo site_url();?>";
					} else {
						$.jGrowl(data);
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('billing/billing/total_balance/');?>",
							success: function(data){
								$('#total_balance').html(data);
							}
						});
						$('#billing_notes_form').clearForm();
						$('#billing_notes_dialog').dialog('close');
					}
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#cancel_billing_notes").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_billing_notes").click(function(){
		$('#billing_notes_form').clearForm();
		$('#billing_notes_dialog').dialog('close');
	});
	$("#billing_other_dos_f1").mask("99/99/9999");
	$("#billing_other_dos_f1").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#billing_payment_dos_f").mask("99/99/9999");
	$("#billing_payment_dos_f").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#billing_payment_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 500, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#billing_payment_payment").val();
			var b = $("#billing_payment_payment_type").val();
			var c = $("#billing_payment_dos_f").val();
			var a1 = $("#billing_payment_payment_old").val();
			var b1 = $("#billing_payment_payment_type_old").val();
			var c1 = $("#billing_payment_dos_f_old").val();
			if(a != a1 || b != b1 || c != c1){
				if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Save to update the form.')){ 
					$('#billing_payment_form').clearForm();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/billing/total_balance/');?>",
						success: function(data){
							$('#total_balance').html(data);
						}
					});
					return true;
				} else {
					return false;
				}
			} else {
				$('#billing_payment_form').clearForm();
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/billing/total_balance/');?>",
					success: function(data){
						$('#total_balance').html(data);
					}
				});
				return true;
			}
		}
	});
	$("#billing_payment_payment_type").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/payment_type');?>",
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
		minLength: 0
	});
	$("#billing_payment_payment_type").focus(function(){
		$("#billing_payment_payment_type").autocomplete("search", '1');
	});
	$("#save_billing_payment_form").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_billing_payment_form").click(function(){
		var a = $("#billing_payment_payment");
		var b = $("#billing_payment_payment_type");
		var c = $("#billing_payment_dos_f");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Payment");
		bValid = bValid && checkEmpty(b,"Payment Type");
		bValid = bValid && checkEmpty(c,"Date of Payment");
		if (bValid) {
			var str = $("#billing_payment_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/billing/payment_save/');?>",
					data: str,
					dataType: "json",
					success: function(data){
						$.jGrowl(data.message);
						$("#billing_payment_payment_old").val(data.payment);
						$("#billing_payment_payment_type_old").val(data.payment_type);
						$("#billing_payment_dos_f_old").val(data.dos_f);
						if (data.eid != ''){
							$('#billing_list_eid').val(data.eid);
							jQuery("#billing_encounters").trigger("reloadGrid");
							jQuery("#bills_done").trigger("reloadGrid");
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('billing/billing/total_balance/');?>",
								success: function(data){
									$('#total_balance').html(data);
								}
							});
						}
						if (data.other_billing_id != ''){
							$('#billing_list_other_billing_id').val(data.other_billing_id);
							jQuery("#billing_other").trigger("reloadGrid");
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('billing/billing/total_balance/');?>",
								success: function(data){
									$('#total_balance').html(data);
								}
							});
						}
						$("#billing_payment_dialog").dialog('close');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_billing_payment_form").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$('#cancel_billing_payment_form').click(function(){
		$("#billing_payment_dialog").dialog('close');
	});
	
	$("#payment_encounter_charge").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	$("#payment_encounter_charge").click(function(){
		var item = jQuery("#billing_encounters").getGridParam('selrow');
		if(item){
			$('#billing_payment_eid').val(item);
			var currentDate = getCurrentDate();
			$("#billing_billing_core_id").val('');
			$('#billing_payment_payment').val('');
			$('#billing_payment_payment_old').val('');
			$('#billing_payment_payment_type').val('');
			$('#billing_payment_payment_type_old').val('');
			$('#billing_payment_dos_f').val(currentDate);
			$('#billing_payment_dos_f_old').val(currentDate);
			$('#billing_payment_dialog').dialog('open');
			$("#billing_payment_payment").focus();
		} else {
			$.jGrowl("Please select encounter to add payment!");
		}
	});
	$("#invoice_encounter_charge").button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$("#invoice_encounter_charge").click(function(){
		var item = jQuery("#billing_encounters").getGridParam('selrow');
		if(item){
			window.open("<?php echo site_url('billing/billing/print_invoice1');?>/" + item);
		} else {
			$.jGrowl("Please select encounter to print invoice!");
		}
	});
	$("#add_charge").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#add_charge").click(function(){
		var currentDate = getCurrentDate();
		$('#billing_other_dos_f1').val(currentDate);
		$('#billing_other_dos_f_old').val(currentDate);
		$('#billing_other_dialog').dialog('open');
		$("#billing_other_reason1").focus();
	});
	$("#edit_charge").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#edit_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			jQuery("#billing_other").GridToForm(item,"#billing_other_form1");
			var a = $("#billing_other_reason1").val();
			var b = $("#billing_other_cpt_charge1").val();
			var c = $("#billing_other_dos_f1").val();
			$("#billing_other_reason_old").val(a);
			$("#billing_other_cpt_charge_old").val(b);
			$("#billing_other_dos_f_old").val(c);
			$('#billing_other_dialog').dialog('open');
			$("#billing_other_reason1").focus();
		} else {
			$.jGrowl("Please select miscellaneous bill to edit!");
		}
	});
	$("#payment_charge").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	$("#payment_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			$('#billing_payment_other_billing_id').val(item);
			var currentDate = getCurrentDate();
			$("#billing_billing_core_id").val('');
			$('#billing_payment_payment').val('');
			$('#billing_payment_payment_old').val('');
			$('#billing_payment_payment_type').val('');
			$('#billing_payment_payment_type_old').val('');
			$('#billing_payment_dos_f').val(currentDate);
			$('#billing_payment_dos_f_old').val(currentDate);
			$('#billing_payment_dialog').dialog('open');
			$("#billing_payment_payment").focus();
		} else {
			$.jGrowl("Please select miscellaneous bill to add payment!");
		}
	});
	$("#invoice_charge").button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$("#invoice_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			window.open("<?php echo site_url('billing/billing/print_invoice2');?>/" + item);
		} else {
			$.jGrowl("Please select encounter to print invoice!");
		}
	});
	$("#delete_charge").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#delete_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this miscellaneous bill?')){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/billing/delete_other_bill');?>",
					data: "billing_core_id=" + item,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							jQuery("#billing_other").trigger("reloadGrid");
						}
					}
				});
			}
		} else {
			$.jGrowl("Please select miscellaneous bill to delete!");
		}
	});
	$("#billing_other_reason1").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/billing_reason');?>",
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
	$("#save_other_billing_form").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_other_billing_form").click(function(){
		var a = $("#billing_other_reason1");
		var b = $("#billing_other_cpt_charge1");
		var c = $("#billing_other_dos_f1");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Reason");
		bValid = bValid && checkEmpty(b,"Charge");
		bValid = bValid && checkEmpty(c,"Date of Charge");
		if (bValid) {
			var str = $("#billing_other_form1").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/billing/billing_other_save/');?>",
					data: str,
					dataType: "json",
					success: function(data){
						$.jGrowl(data.message);
						$("#billing_other_reason_old").val(data.reason);
						$("#billing_other_cpt_charge_old").val(data.cpt_charge);
						$("#billing_other_dos_f_old").val(data.dos_f);
						$('#billing_list_other_billing_id').val(data.other_billing_id);
						jQuery("#billing_other").trigger("reloadGrid");
						$("#billing_other_dialog").dialog('close');
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('billing/billing/total_balance/');?>",
							success: function(data){
								$('#total_balance').html(data);
							}
						});
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_other_billing_form").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$('#cancel_other_billing_form').click(function(){
		$("#billing_other_form1").clearForm();
		$("#billing_other_dialog").dialog('close');
	});
	$("#billing_modifier1").addOption({"":"","25":"25 - Significant, Separately Identifiable E & M Service.","52":"52 - Reduced Service .","59":"59 - Distinct Procedural Service."}, false);
	$("#cpt_helper_dialog1").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#cpt_helper_items1").accordion({active: false, fillSpace: true});
		},
		close: function(event, ui) {
			var b = $("input[name='billing_cpt_helper1']:checked").val();
			if (b) {
				$("#billing_cpt1").val(b);
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/encounters/get_cpt_charge');?>",
					data: "cpt=" + b,
					success: function(data){
						$("#billing_cpt_charge1").val(data);
					}
				});
			}
			$('#cpt_helper_items1').clearDiv();
		}
	});
	$("#save_billing1").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#billing_clear_insurance1").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#billing_clear_insurance2").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#save_billing1").click(function(){
		var ins1 = $("#billing_insurance_id_1").val();
		var ins2 = $("#billing_insurance_id_2").val();
		var eid = $("#billing_eid_1").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/billing/billing_save1');?>",
			data: "insurance_id_1=" + ins1 + "&insurance_id_2=" + ins2 + "&eid=" + eid,
			success: function(data){
				if (data == 'Close Chart') {
					window.location = "<?php echo site_url();?>";
				} else {
					$.jGrowl(data);
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/billing/total_balance/');?>",
						success: function(data){
							$('#total_balance').html(data);
						}
					});
					jQuery("#billing_encounters").trigger("reloadGrid");
				}
			}
		});
	});
	$("#print_invoice1").button({icons: {primary: "ui-icon-print"}}).click(function(){
		var ins1 = $("#billing_insurance_id_1").val();
		var ins2 = $("#billing_insurance_id_2").val();
		var eid = $("#billing_eid_1").val();
		window.open("<?php echo site_url('billing/billing/print_invoice1');?>/" + eid + "/" + ins1 + "/" + ins2);
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/billing/total_balance/');?>",
			success: function(data){
				$('#total_balance').html(data);
			}
		});
		jQuery("#billing_encounters").trigger("reloadGrid");
	});
	$("#print_hcfa1").button({icons: {primary: "ui-icon-print"}}).click(function(){
		var a = $("#billing_insurance_id_1");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Primary Insurance");
		if (bValid) {
			var ins1 = $("#billing_insurance_id_1").val();
			var ins2 = $("#billing_insurance_id_2").val();
			var eid = $("#billing_eid_1").val();
			if (ins1 == '' || ins1 == '0') {
				$.jGrowl("No HCFA-1500 printed due to no primary insurance!");
			} else {
				window.open("<?php echo site_url('billing/billing/generate_hcfa1');?>/" + eid + "/" + ins1 + "/" + ins2);
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/billing/total_balance/');?>",
					success: function(data){
						$('#total_balance').html(data);
					}
				});
				jQuery("#billing_encounters").trigger("reloadGrid");
			}
		}
	});
	$("#billing_icd1").chosen();
	$("#add_billing_cpt1").button();
	$("#add_billing_cpt1").click(function(){
		$('#billing_form1').clearForm();
		$("#billing_unit1").val('1');
		$("#billing_modifier1").val('');
		$("#billing_dos_f1").val();
		$("#billing_dos_t1").val();
		$("#cpt_billing_dialog1").dialog('open');
		$("#billing_cpt1").focus();
	});
	$("#edit_billing_cpt1").button();
	$("#edit_billing_cpt1").click(function(){
		var item = jQuery("#billing_cpt_list").getGridParam('selrow');
		if(item){
			jQuery("#billing_cpt_list").GridToForm(item,"#billing_form1");
			var dx = jQuery("#billing_cpt_list").getCell(item,"icd_pointer");
			var icd_array = String(dx).split("");
			var length = icd_array.length;
			for (var i = 0; i < length; i++) {
				$("#billing_icd1").selectOptions(icd_array[i]);
			}
			$("#billing_icd1").trigger("liszt:updated");
			$("#cpt_billing_dialog1").dialog('open');
			$("#billing_cpt_charge1").focus();
		} else {
			$.jGrowl("Please select row to edit!");
		}
	});
	$("#remove_billing_cpt1").button();
	$("#remove_billing_cpt1").click(function(){
		var item = jQuery("#billing_cpt_list").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "<?php echo site_url('billing/encounters/remove_cpt');?>",
				type: "POST",
				data: "billing_core_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#billing_cpt_list").trigger("reloadGrid");			
				}
			});
		} else {
			$.jGrowl("Please select row to remove!");
		}
	});
	$("#cpt_billing_dialog1").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		buttons: {
			'Save': function() {
				var a = $("#billing_icd1");
				var b = $("#billing_cpt1");
				var c = $("#billing_cpt_charge1");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"ICD Pointer");
				bValid = bValid && checkEmpty(b,"CPT Code");
				bValid = bValid && checkEmpty(c,"CPT Charge");
				if (bValid) {
					var str = $("#billing_form1").serialize();
					var eid = $("#billing_eid_1").val();
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('billing/billing/billing_save');?>/" + eid,
							data: str,
							success: function(data){
								if (data == 'Close Chart') {
									window.location = "<?php echo site_url();?>";
								} else {
									$.jGrowl(data);
									$("#billing_form1").clearForm();
									$("#cpt_billing_dialog1").dialog('close');
									jQuery("#billing_cpt_list").trigger("reloadGrid");
								}
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#billing_form1").clearForm();
				$("#cpt_billing_dialog1").dialog('close');
			}
		}
	});
	$("#billing_cpt1").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/cpt1');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					} else {
						var addterm = [{"label": req.term + ": Select to add CPT to database.", "value":"*/add/*", "value1": req.term}];
						add(addterm);
					}
				}
			});
		},
		minLength: 3,
		select: function(event, ui){
			if (ui.item.value == "*/add/*") {
				$("#configuration_cpt_form").clearForm();
				if (ui.item.value1.length > 5) {
					$("#configuration_cpt_description").val(ui.item.value1);
				} else {
					$("#configuration_cpt_code").val(ui.item.value1);
				}
				$('#configuration_cpt_origin').val("billing_cpt");
				$('#configuration_cpt_dialog').dialog('open');
				$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
			} else {
				$("#billing_cpt_charge1").val(ui.item.charge);
			}
		},
		change: function (event, ui) {
			if(!ui.item){
				$.jGrowl("CPT code must be selected from the database!");
				$("#billing_cpt").addClass("ui-state-error");
			} else {
				$("#billing_cpt").removeClass("ui-state-error");
			}
		}
	});
	$("#cpt_helper1").button({
		icons: {
			primary: "ui-icon-copy"
		}
	});
	$('#cpt_helper1').click(function(){
		$("#cpt_helper_dialog1").dialog('open');
	});
	$("#update_cpt_charge1").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	$('#update_cpt_charge1').click(function(){
		var item = $("#billing_cpt1").val();
		if(item != ''){
			var item2 = $("#billing_cpt_charge1").val();
			$.ajax({
				url: "<?php echo site_url('billing/encounters/update_cpt_charge');?>",
				type: "POST",
				data: "cpt=" + item + "&cpt_charge=" + item2,
				success: function(data){
					$.jGrowl(data);		
				}
			});
		} else {
			$.jGrowl("Please enter a CPT code to update!");
		}
	});
	$("#billing_dos_f1").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#billing_dos_t1").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#billing_dos_f1").mask("99/99/9999");
	$("#billing_dos_t1").mask("99/99/9999");
	
	$("#billing_select_insurance1").button();
	$("#billing_select_insurance1").click(function(){
		var item = jQuery("#billing_insurance_list1").getGridParam('selrow');
		if(item){
			$("#billing_insurance_id_1").val(item);
			var a = $("#billing_insurance_id_1").val();
			var b = $("#billing_insurance_id_2").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/encounters/get_insurance_info');?>",
				data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
				dataType: "json",
				success: function(data){
					$("#billing_insuranceinfo1").html(data.result1);
					$("#billing_insuranceinfo2").html(data.result2);
				}
			});
		} else {
			$.jGrowl("Please select insurance!");
		}
	});
	$("#billing_select_insurance2").button();
	$("#billing_select_insurance2").click(function(){
		var item = jQuery("#billing_insurance_list1").getGridParam('selrow');
		if(item){
			$("#billing_insurance_id_2").val(item);
			var a = $("#billing_insurance_id_1").val();
			var b = $("#billing_insurance_id_2").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/encounters/get_insurance_info');?>",
				data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
				dataType: "json",
				success: function(data){
					$("#billing_insuranceinfo1").html(data.result1);
					$("#billing_insuranceinfo2").html(data.result2);
				}
			});
		} else {
			$.jGrowl("Please select insurance!");
		}
	});
	$("#billing_select_self_pay").button();
	$("#billing_select_self_pay").click(function(){
		$("#billing_insurance_id_1").val('0');
		$("#billing_insurance_id_2").val('');
		var a = $("#billing_insurance_id_1").val();
		var b = $("#billing_insurance_id_2").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/encounters/get_insurance_info');?>",
			data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
			dataType: "json",
			success: function(data){
				$("#billing_insuranceinfo1").html(data.result1);
				$("#billing_insuranceinfo2").html(data.result2);
			}
		});
	});
	$("#billing_clear_insurance1").click(function(){
		$("#billing_insurance_id_1").val('');
		var a = $("#billing_insurance_id_1").val();
		var b = $("#billing_insurance_id_2").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/encounters/get_insurance_info');?>",
			data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
			dataType: "json",
			success: function(data){
				$("#billing_insuranceinfo1").html(data.result1);
				$("#billing_insuranceinfo2").html(data.result2);
			}
		});
	});
	$("#billing_clear_insurance2").click(function(){
		$("#billing_insurance_id_2").val('');
		var a = $("#billing_insurance_id_1").val();
		var b = $("#billing_insurance_id_2").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/encounters/get_insurance_info');?>",
			data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
			dataType: "json",
			success: function(data){
				$("#billing_insuranceinfo1").html(data.result1);
				$("#billing_insuranceinfo2").html(data.result2);
			}
		});
	});
	jQuery("#monthly_stats").jqGrid({
		url:"<?php echo site_url('billing/billing/monthly_stats/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['Month','Patients Seen','Total Billed','Total Payments','DNKA','LMC'],
		colModel:[
			{name:'month',index:'month',width:100},
			{name:'patients_seen',index:'patients_seen',width:100},
			{name:'total_billed',index:'total_billed',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			{name:'total_payments',index:'total_payments',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			{name:'dnka',index:'dnka',width:100},
			{name:'lmc',index:'lmc',width:100}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#monthly_stats_pager'),
		sortname: 'month',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Monthly Statistics - Expand Row for Insurance Statistics",
	 	hiddengrid: true,
	 	height: "100%",
	 	subGrid: true,
	 	subGridRowExpanded: function(subgrid_id, row_id) {
	 		var subgrid_table_id, pager_id;
	 		subgrid_table_id = subgrid_id+"_t2";
	 		pager_id = "p2_"+subgrid_table_id;
	 		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
	 		jQuery("#"+subgrid_table_id).jqGrid({
	 			url:"<?php echo site_url('billing/billing/monthly_stats_insurance');?>/"+row_id,
	 			datatype: "json",
	 			mtype: "POST",
	 			colNames:['Insurance','Patients Seen'],
	 			colModel:[
	 				{name:"insuranceplan",index:"insuranceplan",width:300},
	 				{name:"ins_patients_seen",index:"ins_patients_seen",width:100}
	 			], 
	 			rowNum:10,
	 			pager: pager_id,
	 			sortname: 'insuranceplan', 
	 			sortorder: "desc", 
	 			height: '100%'
	 		});
	 		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
	 			search:false,
	 			edit:false,
	 			add:false,
	 			del:false
	 		});
	 	}
	}).navGrid('#monthly_stats_pager',{search:false,edit:false,add:false,del:false});
	jQuery("#yearly_stats").jqGrid({
		url:"<?php echo site_url('billing/billing/yearly_stats/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['Year','Patients Seen','Total Billed','Total Payments','DNKA','LMC'],
		colModel:[
			{name:'year',index:'year',width:100},
			{name:'patients_seen',index:'patients_seen',width:100},
			{name:'total_billed',index:'total_billed',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			{name:'total_payments',index:'total_payments',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			{name:'dnka',index:'dnka',width:100},
			{name:'lmc',index:'lmc',width:100}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#yearly_stats_pager'),
		sortname: 'year',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Yearly Statistics - Expand Row for Insurance Statistics",
	 	hiddengrid: true,
	 	height: "100%",
	 	subGrid: true,
	 	subGridRowExpanded: function(subgrid_id, row_id) {
	 		var subgrid_table_id, pager_id;
	 		subgrid_table_id = subgrid_id+"_t3";
	 		pager_id = "p3_"+subgrid_table_id;
	 		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
	 		jQuery("#"+subgrid_table_id).jqGrid({
	 			url:"<?php echo site_url('billing/billing/yearly_stats_insurance');?>/"+row_id,
	 			datatype: "json",
	 			mtype: "POST",
	 			colNames:['Insurance','Patients Seen'],
	 			colModel:[
	 				{name:"insuranceplan",index:"insuranceplan",width:300},
	 				{name:"ins_patients_seen",index:"ins_patients_seen",width:100}
	 			], 
	 			rowNum:10,
	 			pager: pager_id,
	 			sortname: 'insuranceplan', 
	 			sortorder: "desc", 
	 			height: '100%'
	 		});
	 		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
	 			search:false,
	 			edit:false,
	 			add:false,
	 			del:false
	 		});
	 	}
	}).navGrid('#yearly_stats_pager',{search:false,edit:false,add:false,del:false});
	var gender = {"m":"Male","f":"Female"};
	$("#demographics_insurance_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#demographics_insurance").jqGrid('GridUnload');
			jQuery("#demographics_insurance").jqGrid({
				url:"<?php echo site_url('billing/billing/insurance_active/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Copay','Deductible','Comments','Address ID','Relationship'],
				colModel:[
					{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
					{name:'insurance_plan_name',index:'insurance_plan_name',width:450},
					{name:'insurance_id_num',index:'insurance_id_num',width:100},
					{name:'insurance_group',index:'insurance_group',width:100},
					{name:'insurance_order',index:'insurance_order',width:105},
					{name:'insurance_insu_lastname',index:'insurance_insu_lastname',width:1,hidden:true},
					{name:'insurance_insu_firstname',index:'insurance_insu_firstname',width:1,hidden:true},
					{name:'insurance_insu_dob',index:'insurance_insu_dob',width:1,hidden:true},
					{name:'insurance_insu_gender',index:'insurance_insu_gender',width:1,hidden:true},
					{name:'insurance_insu_address',index:'insurance_insu_address',width:1,hidden:true},
					{name:'insurance_insu_city',index:'insurance_insu_city',width:1,hidden:true},
					{name:'insurance_insu_state',index:'insurance_insu_state',width:1,hidden:true},
					{name:'insurance_insu_zip',index:'insurance_insu_zip',width:1,hidden:true},
					{name:'insurance_copay',index:'insurance_copay',width:1,hidden:true},
					{name:'insurance_deductible',index:'insurance_deductible',width:1,hidden:true},
					{name:'insurance_comments', index:'insurance_comments',width:1,hidden:true},
					{name:'address_id',index:'address_id',width:1,hidden:true},
					{name:'insurance_relationship',index:'insurance_relationship',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#demographics_insurance_pager'),
				sortname: 'insurance_order',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Insurance Payors",
			 	height: "100%",
			 	onSelectRow: function(id){
			 		var copay = jQuery("#demographics_insurance").getCell(id,'insurance_copay');
					var deductible = jQuery("#demographics_insurance").getCell(id,'insurance_deductible');
					var comments = jQuery("#demographics_insurance").getCell(id,'insurance_comments');
					var text = '';
					if(copay != ''){
						text += "Copay: " + copay + "<br><br>";
					}
					if(deductible != ''){
						text += "Deductible: " + deductible + "<br><br>";
					}
					if (comments != ''){
						text += "Comments: " + comments;
					}
					$.jGrowl(text, {sticky:true, header:'Insurance Information'});
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#demographics_insurance_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#demographics_insurance_inactive").jqGrid('GridUnload')
			jQuery("#demographics_insurance_inactive").jqGrid({
				url:"<?php echo site_url('billing/billing/insurance_inactive/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Copay','Deductible','Comments','Address ID','Relationship'],
				colModel:[
					{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
					{name:'insurance_plan_name',index:'insurance_plan_name',width:450},
					{name:'insurance_id_num',index:'insurance_id_num',width:100},
					{name:'insurance_group',index:'insurance_group',width:100},
					{name:'insurance_order',index:'insurance_order',width:105},
					{name:'insurance_insu_lastname',index:'insurance_insu_lastname',width:1,hidden:true},
					{name:'insurance_insu_firstname',index:'insurance_insu_firstname',width:1,hidden:true},
					{name:'insurance_insu_dob',index:'insurance_insu_dob',width:1,hidden:true},
					{name:'insurance_insu_gender',index:'insurance_insu_gender',width:1,hidden:true},
					{name:'insurance_insu_address',index:'insurance_insu_address',width:1,hidden:true},
					{name:'insurance_insu_city',index:'insurance_insu_city',width:1,hidden:true},
					{name:'insurance_insu_state',index:'insurance_insu_state',width:1,hidden:true},
					{name:'insurance_insu_zip',index:'insurance_insu_zip',width:1,hidden:true},
					{name:'insurance_copay',index:'insurance_copay',width:1,hidden:true},
					{name:'insurance_deductible',index:'insurance_deductible',width:1,hidden:true},
					{name:'insurance_comments', index:'insurance_comments',width:1,hidden:true},
					{name:'address_id',index:'address_id',width:1,hidden:true},
					{name:'insurance_relationship',index:'insurance_relationship',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#demographics_insurance_inactive_pager'),
				sortname: 'insurance_plan_name',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Inactive Insurance Payors",
			 	height: "100%",
			 	hiddengrid: true,
			 	onSelectRow: function(id){
			 		var copay = jQuery("#demographics_insurance_inactive").getCell(id,'insurance_copay');
					var deductible = jQuery("#demographics_insurance_inactive").getCell(id,'insurance_deductible');
					var comments = jQuery("#demographics_insurance_inactive").getCell(id,'insurance_comments');
					var text = '';
					if(copay != ''){
						text += "Copay: " + copay + "<br><br>";
					}
					if(deductible != ''){
						text += "Deductible: " + deductible + "<br><br>";
					}
					if (comments != ''){
						text += "Comments: " + comments;
					}
					$.jGrowl(text, {sticky:true, header:'Insurance Information'});
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#demographics_insurance_inactive_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$("#edit_menu_insurance_main_form").clearForm();
		}
	});
	$("#menu_insurance_plan_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			$("#menu_insurance_plan_fieldset").hide('fast');
			$("#edit_menu_insurance_plan_form").clearForm();
		}
	});
	$("#insurance_menu_demographics").button({
		icons: {
			primary: "ui-icon-suitcase"
		}
	});
	$("#insurance_menu_demographics").click(function() {
		$("#demographics_insurance_dialog").dialog('open');
		$("#menu_insurance_main_fieldset").hide('fast');
	});
	
	$("#demographics_add_insurance").button();
	$("#demographics_add_insurance").click(function(){
		$('#edit_menu_insurance_main_form').clearForm();
		$('#menu_insurance_main_fieldset').show('fast');
	});
	$("#demographics_edit_insurance").button();
	$("#demographics_edit_insurance").click(function(){
		var item = jQuery("#demographics_insurance").getGridParam('selrow');
		if(item){
			jQuery("#demographics_insurance").GridToForm(item,"#edit_menu_insurance_main_form");
			var dob1 = $("#menu_insurance_insu_dob").val();
			var dob = editDate1(dob1);
			$("#menu_insurance_insu_dob").val(dob);
			var plan_name = $("#menu_insurance_plan_name").val();
			$("#insurance_plan_name_display").html(plan_name);
			$('#menu_insurance_main_fieldset').show('fast');
		} else {
			$.jGrowl("Please select insurance to edit!");
		}
	});
	$("#demographics_inactivate_insurance").button();
	$("#demographics_inactivate_insurance").click(function(){
		var item = jQuery("#demographics_insurance").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/inactivate_insurance');?>",
				data: "insurance_id=" + item,
				success: function(data){
					if (data == 'Close Chart') {
						window.location = "<?php echo site_url();?>";
					} else {
						$.jGrowl(data);
						jQuery("#demographics_insurance").trigger("reloadGrid");
						jQuery("#demographics_insurance_inactive").trigger("reloadGrid");
						jQuery("#messages_lab_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_rad_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_cp_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_ref_insurance_grid").trigger("reloadGrid");
					}
				}
			});
			jQuery("#demographics_insurance").delRowData(item);
		} else {
			$.jGrowl("Please select insurance to inactivate!");
		}
	});
	$("#demographics_delete_insurance").button();
	$("#demographics_delete_insurance").click(function(){
		var item = jQuery("#demographics_insurance").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this insurance?  This is not recommended unless entering the insurance was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/billing/delete_insurance');?>",
					data: "insurance_id=" + item,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							jQuery("#demographics_insurance").trigger("reloadGrid");
							jQuery("#demographics_insurance_inactive").trigger("reloadGrid");
							jQuery("#messages_lab_insurance_grid").trigger("reloadGrid");
							jQuery("#messages_rad_insurance_grid").trigger("reloadGrid");
							jQuery("#messages_cp_insurance_grid").trigger("reloadGrid");
							jQuery("#messages_ref_insurance_grid").trigger("reloadGrid");
						}
					}
				});
			}
		} else {
			$.jGrowl("Please select insurance to delete!");
		}
	});
	$("#demographics_reactivate_insurance").button();
	$("#demographics_reactivate_insurance").click(function(){
		var item = jQuery("#demographics_insurance_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/reactivate_insurance');?>",
				data: "insurance_id=" + item,
				success: function(data){
					if (data == 'Close Chart') {
						window.location = "<?php echo site_url();?>";
					} else {
						$.jGrowl(data);
						jQuery("#demographics_insurance_inactive").trigger("reloadGrid");
						jQuery("#demographics_insurance").trigger("reloadGrid");
						jQuery("#messages_lab_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_rad_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_cp_insurance_grid").trigger("reloadGrid");
						jQuery("#messages_ref_insurance_grid").trigger("reloadGrid");
					}
				}
			});
		} else {
			$.jGrowl("Please select medication to reactivate!");
		}
	});
	$("#menu_select_insurance_plan").button();
	$("#menu_select_insurance_plan").click(function(){
		$("#menu_insurance_plan_dialog").dialog('open');
		$("#menu_insurance_search").focus();
	});
	$("#menu_insurance_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/insurance');?>",
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
	$("#menu_insurance_insu_city").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/city');?>",
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
	$("#menu_insurance_plan_city").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/city');?>",
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
	$("#menu_insurance_search_select").button();
	$("#menu_insurance_search_select").click(function(){
		var item = $("#menu_insurance_search").val();
		var pos = item.indexOf('(');
		if (pos == -1) {
			$.jGrowl("Please enter insurance plan!");
		} else {
			var id1 = item.slice(pos);
			var id2 = id1.replace("(", "");
			var id = id2.replace(")", "");
			var pos1 = pos - 1;
			var plan_name = item.slice(0, pos1);
			if(id){
				$("#menu_insurance_plan_name").val(plan_name);
				$("#insurance_plan_name_display").html(plan_name);
				$("#menu_address_id").val(id);
				$("#menu_insurance_main_fieldset").show('fast');
				$("#menu_insurance_plan_dialog").dialog('close');
			} else {
				$.jGrowl("Please enter insurance provider!");
			}       
		}
	});
	$("#menu_insurance_search_edit").button();
	$("#menu_insurance_search_edit").click(function(){
		var item = $("#menu_insurance_search").val();
		var pos = item.indexOf('(');
		if (pos == -1) {
			$("#menu_insurance_search").val('');
			$("#menu_insurance_plan_fieldset").show('fast');
			$("#menu_insurance_plan_facility").focus();
		} else {
			var id1 = item.slice(pos);
			var id2 = id1.replace("(", "");
			var id = id2.replace(")", "");
			if(id){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/insurance1');?>",
					data: "address_id=" + id,
					dataType: "json",
					success: function(data){
						$("#menu_insurance_plan_facility").val(data.facility);
						$("#menu_insurance_plan_address").val(data.street_address1);
						$("#menu_insurance_plan_address2").val(data.street_address2);
						$("#menu_insurance_plan_city").val(data.city);
						$("#menu_insurance_plan_state").val(data.state);
						$("#menu_insurance_plan_zip").val(data.zip);
						$("#menu_insurance_plan_phone").val(data.phone);
						$("#menu_insurance_plan_payor_id").val(data.insurance_plan_payor_id);
						$("#menu_insurance_plan_type").val(data.insurance_plan_type);
						$("#menu_insurance_plan_assignment").val(data.insurance_plan_assignment);
						$("#menu_insurance_plan_ppa_phone").val(data.insurance_plan_ppa_phone);
						$("#menu_insurance_plan_ppa_fax").val(data.insurance_plan_ppa_fax);
						$("#menu_insurance_plan_ppa_url").val(data.insurance_plan_ppa_url);
						$("#menu_insurance_plan_mpa_phone").val(data.insurance_plan_mpa_phone);
						$("#menu_insurance_plan_mpa_fax").val(data.insurance_plan_mpa_fax);
						$("#menu_insurance_plan_mpa_url").val(data.insurance_plan_mpa_url);
						$("#menu_insurance_plan_address_id").val(data.address_id);
						$("#menu_insurance_search").val('');
						$("#menu_insurance_plan_fieldset").show('fast');
						$("#menu_insurance_plan_facility").focus();
					}
				});
			} else {
				$.jGrowl("Please enter insurance plan!");
			}       
		}
	});
	$("#menu_insurance_insu_gender").addOption(gender, false);
	$("#menu_insurance_order").addOption({"":"","Primary":"Primary","Secondary":"Secondary","Unassigned":"Unassigned"}, false);
	$("#menu_insurance_relationship").addOption({"":"","Self":"Self","Spouse":"Spouse","Child":"Child","Other":"Other"}, false);
	$("#menu_insurance_plan_type").addOption({"":"","Other":"Other","Medicare":"Medicare","Medicaid":"Medicaid","Tricare":"Tricare"}, false);
	$("#menu_insurance_plan_assignment").addOption({"":"","No":"No","Yes":"Yes"}, false);
	$("#menu_insurance_insu_dob").mask("99/99/9999");
	$("#menu_insurance_insu_dob").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#menu_insurance_insu_phone").mask("(999) 999-9999");
	$("#menu_insurance_plan_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#menu_insurance_insu_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#menu_insurance_plan_phone").mask("(999) 999-9999");
	$("#menu_insurance_plan_ppa_phone").mask("(999) 999-9999");
	$("#menu_insurance_plan_ppa_fax").mask("(999) 999-9999");
	$("#menu_insurance_plan_mpa_phone").mask("(999) 999-9999");
	$("#menu_insurance_plan_mpa_fax").mask("(999) 999-9999");
	$("#menu_insurance_relationship").change(function(){
		if($("#menu_insurance_relationship").val() == "Self") {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/billing/copy_address');?>",
				dataType: "json",
				success: function(data){
					$("#menu_insurance_insu_lastname").val(data.lastname);
					$("#menu_insurance_insu_firstname").val(data.firstname);
					var dob = editDate1(data.DOB);
					$("#menu_insurance_insu_dob").val(dob);
					$("#menu_insurance_insu_gender").val(data.sex);
					$("#menu_insurance_insu_address").val(data.address);
					$("#menu_insurance_insu_city").val(data.city);
					$("#menu_insurance_insu_state").val(data.state);
					$("#menu_insurance_insu_zip").val(data.zip);
					if (data.phone_home != '') {
						$("#menu_insurance_insu_phone").val(data.phone_home);
					} else {
						$("#menu_insurance_insu_phone").val(data.phone_cell);
					}
				}
			});
		}
	});	
	$("#insurance_copy").button();
	$("#insurance_copy").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/billing/copy_address');?>",
			dataType: "json",
			success: function(data){
				$("#menu_insurance_insu_address").val(data.address);
				$("#menu_insurance_insu_city").val(data.city);
				$("#menu_insurance_insu_state").val(data.state);
				$("#menu_insurance_insu_zip").val(data.zip);
				if (data.phone_home != '') {
					$("#menu_insurance_insu_phone").val(data.phone_home);
				} else {
					$("#menu_insurance_insu_phone").val(data.phone_cell);
				}
			}
		});
	});
	$("#menu_add_insurance_plan").button({
		icons: {
			primary: "ui-icon-disk"
		}
	})
	$("#menu_add_insurance_plan").click(function(){
		var facility = $("#menu_insurance_plan_facility");
		var bValid = true;
		bValid = bValid && checkEmpty(facility,"Insurance Plan Name");
		if (bValid) {
			var str = $("#edit_menu_insurance_plan_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/billing/edit_insurance_provider');?>",
					data: str,
					dataType: "json",
					success: function(data){
						$.jGrowl(data.message);
						$("#menu_insurance_plan_name").val(data.item);
						$("#insurance_plan_name_display").html(data.item);
						$("#menu_address_id").val(data.id);
						$("#edit_menu_insurance_plan_form").clearForm();
						$("#menu_insurance_plan_fieldset").hide('fast');
						$("#menu_insurance_main_fieldset").show('fast');
						$("#menu_insurance_plan_dialog").dialog('close');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$('#save_menu_insurance').button({icons: {primary: "ui-icon-disk"}});
	$("#save_menu_insurance").click(function(){
		var plan_name = $("#menu_insurance_plan_name");
		var id_num = $("#menu_insurance_id_num");
		var relationship = $("#menu_relationship");
		var lastname = $("#menu_insurance_insu_lastname");
		var firstname = $("#menu_insurance_insu_firstname");
		var dob = $("#menu_insurance_insu_dob");
		var address = $("#menu_insurance_insu_address");
		var city = $("#menu_insurance_insu_city");
		var zip = $("#menu_insurance_insu_zip");
		var bValid = true;
		bValid = bValid && checkEmpty(plan_name,"Insurance Plan name");
		bValid = bValid && checkEmpty(id_num,"ID Number");
		bValid = bValid && checkEmpty(relationship,"Relationship");
		bValid = bValid && checkEmpty(lastname,"Insured Last Name");
		bValid = bValid && checkEmpty(firstname,"Insured First Name");
		bValid = bValid && checkEmpty(dob,"Insured Date of Birth");
		bValid = bValid && checkEmpty(address,"Insured Address");
		bValid = bValid && checkEmpty(city,"Insured City");
		bValid = bValid && checkEmpty(zip,"Insured Zip");
		if (bValid) {
			var str = $("#edit_menu_insurance_main_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/billing/edit_insurance');?>",
					data: str,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							$("#edit_menu_insurance_main_form").clearForm();
							$("#menu_insurance_main_fieldset").hide('fast');
							jQuery("#demographics_insurance").trigger("reloadGrid");
							jQuery("#messages_lab_insurance_grid").trigger("reloadGrid");
							jQuery("#messages_rad_insurance_grid").trigger("reloadGrid");
							jQuery("#messages_cp_insurance_grid").trigger("reloadGrid");
							jQuery("#messages_ref_insurance_grid").trigger("reloadGrid");
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$('#cancel_menu_insurance').button({icons: {primary: "ui-icon-close"}});
	$("#cancel_menu_insurance").click(function(){
		$("#edit_menu_insurance_main_form").clearForm();
		$("#menu_insurance_main_fieldset").hide('fast');
	});
	$(".cpt_buttons").button();
	$("#cpt_link1").button().click(function(){
		$("#configuration_dialog").dialog('open');
		$("#configuration_accordion").accordion("option", "active", 7);
	});
});
</script>
<div id="heading2"></div>
<div id ="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="maincontent_full">
		<h4>NOSH ChartingSystem Billing and Accounting</h4>
		<div id="noshtabs">
			<div id="billing_provider_tabs">
				<ul>
					<li><a href="#provider_billing_tabs_1">Bill Submission</a></li>
					<li><a href="#provider_billing_tabs_2">Outstanding Balances</a></li>
					<li><a href="#provider_billing_tabs_3">Reports</a></li>
				</ul>
				<div id="provider_billing_tabs_1">
					<table id="submit_list" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="submit_list_pager" class="scroll" style="text-align:center;"></div><br>
					<form name="submit_bill_form" id="submit_bill_form_id" style="display: none">
						<input type="hidden" id="billing_eid"/>
						<fieldset class="ui-state-default ui-corner-all">
							<legend>Choose Method of Bill Submission</legend>
							<button type="button" id="submit_batch_printimage">Batch Print Image</button>
							<button type="button" id="submit_batch_hcfa">Batch Print HCFA-1500</button>
							<button type="button" id="submit_single_printimage">Create Single Print Image</button>
							<button type="button" id="submit_hcfa">Print HCFA-1500</button>
						</fieldset><br><br>
					</form>
					<button type="button" id="submit_batch">Create Batched Print Image</button>
					<button type="button" id="submit_batch1">Create Batched HCFA-1500</button><br><br>
					<table id="bills_done" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="bills_done_pager" class="scroll" style="text-align:center;"></div><br>	
					<button type="button" id="bill_resubmit">Resubmit Bill</button>		
					<button type="button" id="payment_encounter_charge1">Make Payment to Encounter</button>		
				</div>
				<div id="provider_billing_tabs_2">
					<table id="outstanding_balance" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="outstanding_balance_pager" class="scroll" style="text-align:center;"></div><br>
				</div>
				<div id="provider_billing_tabs_3">
					<table id="monthly_stats" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="monthly_stats_pager" class="scroll" style="text-align:center;"></div><br>
					<table id="yearly_stats" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="yearly_stats_pager" class="scroll" style="text-align:center;"></div>
				</div>
			</div>	
		</div>
	</div>
</div>
<div id="billing_list_dialog" title="Patient Billing">
	<input type="hidden" id="billing_list_eid"/>
	<input type="hidden" id="billing_list_other_billing_id"/>
	<div id="total_balance"></div>
	<button type="button" id="billing_notes">Edit Billing Notes</button>
	<button type="button" id="insurance_menu_demographics">Insurance</button><br>
	<hr class="ui-state-default"/>
	<table id="billing_encounters" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="billing_encounters_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="edit_encounter_charge">View or Edit Billing Details</button>
	<button type="button" id="payment_encounter_charge">Make Payment for Encounter</button>
	<button type="button" id="invoice_encounter_charge">Print Invoice for Encounter</button><br><br><br><br>
	<hr class="ui-state-default"/>
	<table id="billing_other" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="billing_other_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="add_charge">Add Special Bill</button>
	<button type="button" id="edit_charge">Edit Special Bill</button>
	<button type="button" id="payment_charge">Make Payment for Bill</button>
	<button type="button" id="invoice_charge">Print Invoice for Bill</button>
	<button type="button" id="delete_charge">Delete Special Bill</button><br><br>
</div>
<div id="billing_notes_dialog" title="Billing Notes">
	<form id="billing_notes_form">
		<input type="hidden" id="billing_billing_notes_old"/>
		<table>
			<tr>
				<td>Billing Notes:</td>
				<td><textarea name="billing_notes" id="billing_billing_notes" rows="4" style="width:500px" class="text ui-widget-content ui-corner-all"></textarea></td>
			</tr>
		</table><br>
		<button type="button" id="save_billing_notes">Save</button>
		<button type="button" id="cancel_billing_notes">Cancel</button>
	</form>
</div>
<div id="billing_detail_dialog" title="Detailed Billing Information">
	<button type="button" id="save_billing1">Save</button>
	<button type="button" id="print_invoice1">Print Invoice</button>
	<button type="button" id="print_hcfa1">Print HCFA-1500</button>
	<input type="hidden" name="eid" id="billing_eid_1"/>
	<input type="hidden" name="insurance_id_1" id="billing_insurance_id_1"/>
	<input type="hidden" name="insurance_id_2" id="billing_insurance_id_2"/>
	<input type="hidden" name="insurance_id_1_old" id="billing_insurance_id_1_old"/>
	<input type="hidden" name="insurance_id_2_old" id="billing_insurance_id_2_old"/>
	<input type="hidden" name="bill_complex_old" id="billing_bill_complex_old1"/>
	<hr class="ui-state-default"/>
	<table>
		<tr>
			<td>Primary Insurance:</td>
			<td id="billing_insuranceinfo1"></td>
			<td><button type="button" id="billing_clear_insurance1">Clear</button></td>
		</tr>
		<tr>
			<td>Secondary Insurance:</td>
			<td id="billing_insuranceinfo2"></td>
			<td><button type="button" id="billing_clear_insurance2">Clear</button></td>
		</tr>
	</table><br>
	<table id="billing_insurance_list1" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="billing_insurance_pager1" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="billing_select_insurance1">Select as primary insurance</button>
	<button type="button" id="billing_select_insurance2">Select as secondary insurance</button>
	<button type="button" id="billing_select_self_pay">No insurance</button><br>
	<hr class="ui-state-default"/><br>
	<div id="billing_icd9"></div>
	<hr class="ui-state-default"/><br>
	<table id="billing_cpt_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="billing_cpt_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="add_billing_cpt1">Add Row</button>
	<button type="button" id="edit_billing_cpt1">Edit Row</button>
	<button type="button" id="remove_billing_cpt1">Remove Row</button><br><br>
</div>
<div id="cpt_billing_dialog1" title="CPT Editor">
	<form id="billing_form1">
	<input type="hidden" name="billing_core_id" id="billing_core_id1"/>
		<table>
			<tr>
				<td>CPT Code:</td>
				<td><input type="text" name="cpt" id="billing_cpt1" style="width:200px" class="text ui-widget-content ui-corner-all"/> <button type="button" id="cpt_helper1">CPT Helper</button><button type="button" id="cpt_link1">CPT Editor</button></td>
			</tr>
			<tr>
				<td>Charge:</td>
				<td>$<input type="text" name="cpt_charge" id="billing_cpt_charge1" style="width:195px" class="text ui-widget-content ui-corner-all"/> <button type="button" id="update_cpt_charge1">Update Charge</button></td>
			</tr>
			<tr>
				<td>Unit:</td>
				<td><input type="text" name="unit" id="billing_unit1" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Modifier:</td>
				<td><select name="modifier" id="billing_modifier1" style="width:200px" class="text ui-widget-content ui-corner-all"></select></td>
			</tr>
			<tr>
				<td>Date of Service/From:</td>
				<td><input type="text" name="dos_f" id="billing_dos_f1" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Date of Service/To:</td>
				<td><input type="text" name="dos_t" id="billing_dos_t1" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Diagnosis Pointer</td>
				<td><select name="icd_pointer[]" id="billing_icd1" multiple="multiple" style="width:400px" class="multiselect"></select></td>
			</tr>
		</table><br>
	</form>
</div>
<div id="cpt_helper_dialog1" title="CPT Helper">
	<div id="cpt_helper_items1">
		<h3><a href="#">Evaluation and Management Codes</a></h3>
		<div>
			<table>
				<tr>
					<td>New Patient:</td>
					<td>
						<input name="billing_cpt_helper1" id="99202" value="99202" type="radio" class="cpt_buttons"><label for="99202">99202</label>
						<input name="billing_cpt_helper1" id="99203" value="99203" type="radio" class="cpt_buttons"><label for="99203">99203</label>
						<input name="billing_cpt_helper1" id="99204" value="99204" type="radio" class="cpt_buttons"><label for="99204">99204</label>
						<input name="billing_cpt_helper1" id="99205" value="99205" type="radio" class="cpt_buttons"><label for="99205">99205</label>
					</td>
				</tr>
				<tr>
					<td>Established Patient:</td>
					<td>
						<input name="billing_cpt_helper1" id="99212" value="99212" type="radio" class="cpt_buttons"><label for="99212">99212</label>
						<input name="billing_cpt_helper1" id="99213" value="99213" type="radio" class="cpt_buttons"><label for="99213">99213</label>
						<input name="billing_cpt_helper1" id="99214" value="99214" type="radio" class="cpt_buttons"><label for="99214">99214</label>
						<input name="billing_cpt_helper1" id="99215" value="99215" type="radio" class="cpt_buttons"><label for="99215">99215</label>
					</td>
				</tr>
			</table>
		</div>
		<h3><a href="#">Preventative Visit Codes</a></h3>
		<div>
			<input name="billing_cpt_helper1" id="new_prevent" value="" type="radio" class="cpt_buttons"><label for="new_prevent">New - <span id="new_prevent1_text"></span></label>
			<input name="billing_cpt_helper1" id="established_prevent" value="" type="radio" class="cpt_buttons"><label for="established_prevent">Established - <span id="established_prevent1_text"></span></label>
		</div>
		<h3><a href="#">Prolonged Visit Codes</a></h3>
		<div>
			<input name="billing_cpt_helper1" id="99354" value="99354" type="radio" class="cpt_buttons"><label for="99354">99354</label>
			<input name="billing_cpt_helper1" id="99355" value="99355" type="radio" class="cpt_buttons"><label for="99355">99355 - Additional 30 minutes</label>
		</div>
	</div>
</div>
<div id="billing_other_dialog" title="Detailed Billing Information">
	<form id="billing_other_form1">
	<input type="hidden" name="other_billing_id" id="billing_other_billing_id"/>
	<input type="hidden" id="billing_other_reason_old"/>
	<input type="hidden" id="billing_other_cpt_charge_old"/>
	<input type="hidden" id="billing_other_dos_f_old"/>
		<table>
			<tr>
				<td>Reason:</td>
				<td><input type="text" name="reason" id="billing_other_reason1" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Charge:</td>
				<td>$<input type="text" name="cpt_charge" id="billing_other_cpt_charge1" style="width:195px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Date of Charge:</td>
				<td><input type="text" name="dos_f" id="billing_other_dos_f1" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
		</table><br>
		<button type="button" id="save_other_billing_form">Save</button>
		<button type="button" id="cancel_other_billing_form">Cancel</button>
	</form>
</div>	
<div id="billing_payment_dialog" title="Payment">
	<form id="billing_payment_form">
	<input type="hidden" name="billing_core_id" id="billing_billing_core_id"/>
	<input type="hidden" name="other_billing_id" id="billing_payment_other_billing_id"/>
	<input type="hidden" name="eid" id="billing_payment_eid"/>
	<input type="hidden" name="subgrid_table_id" id="billing_subgrid_table_id"/>
	<input type="hidden" id="billing_payment_payment_old"/>
	<input type="hidden" id="billing_payment_payment_type_old"/>
	<input type="hidden" id="billing_payment_dos_f_old"/>
		<table>
			<tr>
				<td>Payment:</td>
				<td>$<input type="text" name="payment" id="billing_payment_payment" style="width:195px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Payment Type:</td>
				<td><input type="text" name="payment_type" id="billing_payment_payment_type" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Date of Payment:</td>
				<td><input type="text" name="dos_f" id="billing_payment_dos_f" style="width:200px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
		</table><br>
		<button type="button" id="save_billing_payment_form">Save</button>
		<button type="button" id="cancel_billing_payment_form">Cancel</button>
	</form>
</div>
<div id="demographics_insurance_dialog" title="Insurance">
	<table id="demographics_insurance" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="demographics_insurance_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="demographics_add_insurance">Add Insurance</button>
	<button type="button" id="demographics_edit_insurance">Edit Insurance</button>
	<button type="button" id="demographics_inactivate_insurance">Inactivate Insurance</button>
	<button type="button" id="demographics_delete_insurance">Delete Insurance</button><br><br>
	<div id="menu_insurance_main_fieldset" style="display: none">
		<form name="edit_menu_insurance_main_form" id="edit_menu_insurance_main_form">
			<input type="hidden" name="insurance_id" id="menu_insurance_id"/>
			<input type="hidden" name="address_id" id="menu_address_id"/>
			<fieldset class="ui-state-default ui-corner-all">
				<legend>Insurance</legend>
				<table>
					<tbody>
						<tr>
							<td colspan="2">Insurance Plan:<br><div id="insurance_plan_name_display"></div><input type="hidden" name="insurance_plan_name" id="menu_insurance_plan_name"/></td>
							<td><button type="button" id="menu_select_insurance_plan">Select Insurance Plan</button></td>
						</tr>
						<tr>
							<td>ID Number:<br><input type="text" name="insurance_id_num" id="menu_insurance_id_num" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Group Number:<br><input type="text" name="insurance_group" id="menu_insurance_group" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Relationship:<br><select name="insurance_relationship" id="menu_insurance_relationship" class="text ui-widget-content ui-corner-all"></td>
						</tr>
						<tr>
							<td>Insured Last Name:<br><input type="text" name="insurance_insu_lastname" id="menu_insurance_insu_lastname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Insured First Name:<br><input type="text" name="insurance_insu_firstname" id="menu_insurance_insu_firstname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Insured Date of Birth:<br><input type="text" name="insurance_insu_dob" id="menu_insurance_insu_dob" style="width:148px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Insured Gender:<br><select name="insurance_insu_gender" id="menu_insurance_insu_gender" class="text ui-widget-content ui-corner-all"></td>
							<td><br><input type="button" id="insurance_copy" value="Use Patient's Address" class="ui-button ui-state-default ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Insured Address:<br><input type="text" name="insurance_insu_address" id="menu_insurance_insu_address" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
							</tr>
						<tr>
							<td>Insured City:<br><input type="text" name="insurance_insu_city" id="menu_insurance_insu_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Insured State:<br><select name="insurance_insu_state" id="menu_insurance_insu_state" class="text ui-widget-content ui-corner-all"></td>
							<td>Insured Zip:<br><input type="text" name="insurance_insu_zip" id="menu_insurance_insu_zip" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Insured Phone:<br><input type="text" name="insurance_insu_phone" id="menu_insurance_insu_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Copay:<br><input type="text" name="insurance_copay" id="menu_insurance_copay" style="widteh:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Deductible:<br><input type="text" name="insurance_deductible" id="menu_insurance_deductible" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Comments:<br><textarea name="insurance_comments" id="menu_insurance_comments" rows="1" style="width:562px" class="text ui-widget-content ui-corner-all"></textarea></td>
						</tr>
						<tr>
							<td colspan="2">Insurance Priority:<br><select name="insurance_order" id="menu_insurance_order" class="text ui-widget-content ui-corner-all"></td>
							<td>
								<button type="button" id="save_menu_insurance">Save</button> 
								<button type="button" id="cancel_menu_insurance">Cancel</button>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset><br><br>
		</form>
	</div>
	<table id="demographics_insurance_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="demographics_insurance_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<input type="button" id="demographics_reactivate_insurance" value="Reactivate Insurance" class="ui-button ui-state-default ui-corner-all"/><br><br>
</div>
<div id="menu_insurance_plan_dialog" title="Insurance Provider">
	<form name="edit_menu_insurance_plan_form" id="edit_menu_insurance_plan_form">
		<input type="hidden" name="address_id" id="menu_insurance_plan_address_id"/>
		<table>
			<tbody>
				<tr>
					<td>Search Insurance Plan:<br><input type="text" name="insurance_search" id="menu_insurance_search" style="width:562px" class="text ui-widget-content ui-corner-all"/> <button type="button" id="menu_insurance_search_select">Select</button> <button type="button" id="menu_insurance_search_edit">Add/Edit</button></td>
				</tr>
			</tbody>
		</table><br>
		<fieldset id="menu_insurance_plan_fieldset" class="ui-state-default ui-corner-all" style="display:none">
			<legend>Insurance Provider</legend>
			<table>
				<tbody>
					<tr>
						<td colspan="3">Insurance Plan Name:<br><input type="text" name="facility" id="menu_insurance_plan_facility" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>Payor ID:<br><input type="text" name="insurance_plan_payor_id" id="menu_insurance_plan_payor_id" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						<td>Insurance Type:<br><select name="insurance_plan_type" id="menu_insurance_plan_type" class="text ui-widget-content ui-corner-all"></td>
						<td>Accept Assignment:<br><select name="insurance_plan_assignment" id="menu_insurance_plan_assignment" class="text ui-widget-content ui-corner-all"/></select></td>
					</tr>
					<tr>
						<td colspan="3">Address:<br><input type="text" name="street_address1" id="menu_insurance_plan_address" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td colspan="3">Address2:<br><input type="text" name="street_address2" id="menu_insurance_plan_address2" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>City:<br><input type="text" name="city" id="menu_insurance_plan_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						<td>State:<br><select name="state" id="menu_insurance_plan_state" class="text ui-widget-content ui-corner-all"></td>
						<td>Zip:<br><input type="text" name="zip" id="menu_insurance_plan_zip" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>Phone:<br><input type="text" name="phone" id="menu_insurance_plan_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						<td>Procedure PA Phone:<br><input type="text" name="insurance_plan_ppa_phone" id="menu_insurance_plan_ppa_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						<td>Procedure PA Fax:<br><input type="text" name="insurance_plan_ppa_fax" id="menu_insurance_plan_ppa_fax" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td colspan="3">Procedure PA Website:<br><input type="text" name="insurance_plan_ppa_url" id="menu_insurance_plan_ppa_url" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td colspan="3">Medication PA Website:<br><input type="text" name="insurance_plan_mpa_url" id="menu_insurance_plan_mpa_url" style="width:562px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td>Medication PA Phone:<br><input type="text" name="insurance_plan_mpa_phone" id="menu_insurance_plan_mpa_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						<td>Medication PA Fax:<br><input type="text" name="insurance_plan_mpa_fax" id="menu_insurance_plan_mpa_fax" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						<td>
							<br><button type="button" id="menu_add_insurance_plan">Add and Save Insurance to Address Book</button>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset><br><br>
	</form>
</div>
