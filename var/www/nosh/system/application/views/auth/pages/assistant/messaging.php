<script type="text/javascript">
var timeoutHnd;
	
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd);
		timeoutHnd = setTimeout(gridReload,500);
}

function gridReload(){ 
	var mask = jQuery("#search_all_contact").val();
	jQuery("#all_contacts_list").setGridParam({url:"<?php echo site_url('assistant/messaging/all_contacts');?>/"+mask,page:1}).trigger("reloadGrid");
}
$(document).ready(function() {
	$("#heading2").load('<?php echo site_url("search/loadpage");?>');
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("assistant/messaging");?>',
		redirect_url: '<?php echo site_url("logout");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: false
	});
	$("#new_message").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#new_message").click(function(){
		$("#internal_messages_form").clearForm();
		$("#internal_messages_form_id").show('fast');
		$("#message_view_wrapper").hide('fast');
 		$("#message_view_wrapper2").hide('fast');
 		$("#internal_messages_dialog").dialog('open');
		$("#messages_subject").focus();
	});
	function mail_status(cellvalue, options, rowObject){
		if (cellvalue == "y") {
			return "<span class='ui-icon ui-icon-mail-open'></span>";
		} else {
			return "<span class='ui-icon ui-icon-mail-closed'></span>";
		}
	}
	jQuery("#internal_inbox").jqGrid({
		url:"<?php echo site_url('assistant/messaging/internal_inbox/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','To','','Date','FromID','From','Subject','Message','CC','PID','Patient Name','Body Text','Telephone Messages ID'],
		colModel:[
			{name:'message_id',index:'message_id',width:1,hidden:true},
			{name:'message_to',index:'message_to',width:1,hidden:true},
			{name:'read',index:'read',width:15,formatter:mail_status},
			{name:'date',index:'date',width:120},
			{name:'message_from',index:'message_from',width:1,hidden:true},
			{name:'displayname',index:'displayname',width:180},
			{name:'subject',index:'subject',width:240},
			{name:'body',index:'body',width:1,hidden:true},
			{name:'cc',index:'cc',width:1,hidden:true},
			{name:'pid',index:'pid',width:1,hidden:true},
			{name:'patient_name',index:'patient_name',width:1,hidden:true},
			{name:'bodytext',index:'bodytext',width:1,hidden:true},
			{name:'t_messages_id',index:'t_messages_id',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#internal_inbox_pager'),
		sortname: 'date',
		viewrecords: true,
		sortorder: "desc",
		caption:"Inbox",
		height: "100%",
		multiselect: true,
		multiboxonly: true,
		onCellSelect: function(id,iCol) {
			if (iCol > 0) {
				var row = jQuery("#internal_inbox").getRowData(id);
				var text = '<br><strong>From:</strong> ' + row['displayname'] + '<br><br><strong>Date:</strong> ' + row['date'] + '<br><br><strong>Subject:</strong> ' + row['subject'] + '<br><br><strong>Message:</strong> ' + row['bodytext']; 
				var rawtext = 'From:  ' + row['displayname'] + '\nDate: ' + row['date'] + '\nSubject: ' + row['subject'] + '\n\nMessage: ' + row['body']; 
				$("#message_view").html(text);
				$("#message_view_rawtext").val(rawtext);
				$("#message_view_message_id").val(id);
				$("#message_view_from").val(row['message_from']);
				$("#message_view_to").val(row['message_to']);
				$("#message_view_cc").val(row['cc']);
				$("#message_view_subject").val(row['subject']);
				$("#message_view_body").val(row['body']);
				$("#message_view_date").val(row['date']);
				$("#message_view_pid").val(row['pid']);
				$("#message_view_patient_name").val(row['patient_name']);
				$("#message_view_t_messages_id").val(row['t_messages_id']);
				$("#internal_messages_form_id").hide('fast');
				$("#message_view_wrapper").show('fast');
				$("#message_view_wrapper2").hide('fast');
				$("#internal_messages_dialog").dialog('open');
				if (row['pid'] == '' || row['pid'] == "0") {
		 			$("#export_message").hide();
		 			$("#export_message1").hide();
				} else {
					$("#export_message").show();
					$("#export_message1").hide();
				}
				setTimeout(function() {
					var a = $("#internal_messages_dialog" ).dialog("isOpen");
					if (a) {
						var id = $("#message_view_message_id").val();
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('assistant/messaging/read_message');?>/" + id,
							success: function(data){
								$.jGrowl(data);
								jQuery("#internal_inbox").trigger("reloadGrid");
							}
						});
					}
				}, 3000);
			}
		}
	}).navGrid('#internal_inbox_pager',{search:false,edit:false,add:false,del:false
	}).navButtonAdd('#internal_inbox_pager',{
		caption:"Delete Message", 
		buttonicon:"ui-icon-trash", 
		onClickButton: function(){ 
			var id = jQuery("#internal_inbox").getGridParam('selarrrow');
			if(id){
 				var count = id.length;
 				for (var i = 0; i < count; i++) {
 					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/messaging/delete_message');?>",
						data: "message_id=" + id[i],
						success: function(data){
							$.jGrowl(data);
						}
					});
 				}
 				jQuery("#internal_inbox").trigger("reloadGrid");
			} else {
				$.jGrowl('Choose message(s) to delete!');
			}
		}, 
		position:"last"
	});
	jQuery("#internal_draft").jqGrid({
		url:"<?php echo site_url('assistant/messaging/internal_draft/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','To','CC','Subject','Message','PID','Patient Name'],
		colModel:[
			{name:'message_id',index:'message_id',width:1,hidden:true},
			{name:'date',index:'date',width:120},
			{name:'message_to',index:'message_to',width:90},
			{name:'cc',index:'cc',width:90},
			{name:'subject',index:'subject',width:250},
			{name:'body',index:'body',width:1,hidden:true},
			{name:'pid',index:'pid',width:1,hidden:true},
			{name:'patient_name',index:'patient_name',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#internal_draft_pager'),
		sortname: 'date',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Drafts",
	 	height: "100%",
	 	multiselect: true,
	 	multiboxonly: true,
	 	onCellSelect: function(id,iCol) {
	 		if (iCol > 0) {
		 		jQuery("#internal_draft").GridToForm(id,"#internal_messages_form_id");
		 		$("#internal_messages_dialog").dialog('open');
		 		$("#internal_messages_form_id").show('fast');
		 		$("#messages_subject").focus();
		 	}
	 	}
	}).navGrid('#internal_draft_pager',{search:false,edit:false,add:false,del:false
	}).navButtonAdd('#internal_draft_pager',{
		caption:"Delete Message", 
		buttonicon:"ui-icon-trash", 
		onClickButton: function(){ 
			var id = jQuery("#internal_draft").getGridParam('selarrrow');
			if(id){
 				var count = id.length;
 				for (var i = 0; i < count; i++) {
 					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/messaging/delete_message');?>",
						data: "message_id=" + id[i],
						success: function(data){
							$.jGrowl(data);
						}
					});
 				}
 				jQuery("#internal_draft").trigger("reloadGrid");
			} else {
				$.jGrowl('Choose message(s) to delete!');
			}
		}, 
		position:"last"
	});
	jQuery("#internal_outbox").jqGrid({
		url:"<?php echo site_url('assistant/messaging/internal_outbox/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','To','CC','Subject','PID','Message'],
		colModel:[
			{name:'message_id',index:'message_id',width:1,hidden:true},
			{name:'date',index:'date',width:120},
			{name:'message_to',index:'message_to',width:90},
			{name:'cc',index:'cc',width:90},
			{name:'subject',index:'subject',width:250},
			{name:'pid',index:'pid',width:1,hidden:true},
			{name:'body',index:'body',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#internal_outbox_pager'),
		sortname: 'date',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Sent Messages",
	 	height: "100%",
	 	multiselect: true,
	 	multiboxonly: true,
	 	hiddengrid: true,
	 	onCellSelect: function(id,iCol) {
	 		if (iCol > 0) {
		 		var row = jQuery("#internal_outbox").getRowData(id);
				var text = '<br><strong>To:</strong>  ' + row['message_to'] + '<br><strong>CC:</strong> ' + row['cc'] + '<br<br><strong>Date:</strong>  ' + row['date'] + '<br><br><strong>Subject:</strong>  ' + row['subject'] + '<br><br><strong>Message:</strong> ' + row['body']; 
				$("#message_view2").html(text);
				$("#message_view_subject1").val(row['subject']);
				$("#message_view_body1").val(row['body']);
				$("#message_view_date1").val(row['date']);
				$("#message_view_pid1").val(row['pid']);
				$("#internal_messages_form_id").hide('fast');
		 		$("#message_view_wrapper2").show('fast');
		 		$("#message_view_wrapper").hide('fast');
		 		$("#internal_messages_dialog").dialog('open');
		 		if (row['pid'] == '' || row['pid'] == "0") {
		 			$("#export_message1").hide();
		 			$("#export_message").hide();
				} else {
					$("#export_message1").show();
					$("#export_message").hide();
				}
		 	}
		}
	}).navGrid('#internal_outbox_pager',{search:false,edit:false,add:false,del:false});
	$("#internal_messages_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false
	});
	function split( val ) {
		return val.split( /;\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}
	$("#messages_to").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/all_users');?>",
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
		}
	});
	$("#messages_cc").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/all_users');?>",
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
		}
	});
	$("#messages_patient").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search');?>",
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
		minLength: 1,
		select: function(event, ui){
			$("#messages_pid").val(ui.item.id);
			$("messages_to").focus();
		}
	});
	$("#send_message").button({
		icons: {
			primary: "ui-icon-mail-closed"
		}
	});
	$("#send_message").click(function(){
		var a = $("#messages_to");
		var b = $("#messages_subject");
		var c = $("#messages_body");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"To");
		bValid = bValid && checkEmpty(b,"Subject");
		bValid = bValid && checkEmpty(c,"Message");
		if (bValid) {
			var str = $("#internal_messages_form_id").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/messaging/send_message/');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$("#internal_messages_form_id").clearForm();
						$("#internal_messages_form_id").hide('fast');
						$("#internal_messages_dialog").dialog('close');
						jQuery("#internal_outbox").trigger("reloadGrid");
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#draft_message").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#draft_message").click(function(){
		var str = $("#internal_messages_form_id").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/draft_message/');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$("#internal_messages_form_id").clearForm();
					$("#internal_messages_form_id").hide('fast');
					$("#internal_messages_dialog").dialog('close');
					jQuery("#internal_draft").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#cancel_message").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_message").click(function(){
		var message_id = $("#messages_message_id").val();
		if (message_id == '') {
			$("#internal_messages_form_id").clearForm();
			$("#internal_messages_form_id").hide('fast');
			$("#internal_messages_dialog").dialog('close');
		} else {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/delete_message/');?>",
				data: "message_id=" + message_id,
				success: function(data){
					$.jGrowl(data);
					$("#internal_messages_form_id").clearForm();
					$("#internal_messages_form_id").hide('fast');
					$("#internal_messages_dialog").dialog('close');
					jQuery("#internal_draft").trigger("reloadGrid");
				}
			});
		}
	});
	$("#reply_message").button({
		icons: {
			primary: "ui-icon-arrowreturn-1-w"
		}
	});
	$("#reply_message").click(function(){
		var to = $("#message_view_from").val();
		$("#messages_to_hidden").val(to);
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/messaging/get_displayname');?>",
			data: "id=" + to,
			success: function(data){
				$("#messages_to").val(data);
			}
		});
		var from = $("#message_view_from").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/messaging/get_displayname');?>",
			data: "id=" + from,
			success: function(data){
				var date = $("#message_view_date").val();
				var body = $("#message_view_body").val();
				var newbody = '\n\n' + 'On ' + date + ', ' + data + ' wrote:\n---------------------------------\n' + body;
				$("#messages_body").val(newbody)caret(0);
			}
		});
		var subject = 'Re: ' + $("#message_view_subject").val();
		$("#messages_subject").val(subject);
		var pid = $("#message_view_pid").val();
		var patient_name = $("#message_view_patient_name").val();
		var t_messages_id = $("#message_view_t_messages_id").val();
		$("#messages_pid").val(pid);
		$("#messages_patient").val(patient_name);
		$("#messages_t_messages_id").val(t_messages_id);
		$("#message_view_wrapper").hide('fast');
		$("#internal_messages_form_id").show('fast');
		$("#internal_messages_dialog").dialog('open');
		$("#messages_body").focus();
	});
	$("#reply_all_message").button({
		icons: {
			primary: "ui-icon-arrowreturnthick-1-w"
		}
	});
	$("#reply_all_message").click(function(){
		var to = $("#message_view_from").val();
		var cc = $("#message_view_cc").val();
		$("#messages_to_hidden").val(to);
		$("#messages_cc_hidden").val(cc);
		if (cc == ''){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/get_displayname');?>",
				data: "id=" + to,
				success: function(data){
					var a = data + ';';
					$("#messages_to").val(a);
				}
			});
		} else {
			var to1 = to + ';' + cc;
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/get_displayname1');?>",
				data: "id=" + to1,
				success: function(data){
					var a = data + ';';
					$("#messages_to").val(a);
				}
			});
		}
		var from = $("#message_view_from").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/messaging/get_displayname');?>",
			data: "id=" + from,
			success: function(data){
				var date = $("#message_view_date").val();
				var body = $("#message_view_body").val();
				var newbody = '\n\n' + 'On ' + date + ', ' + data + ' wrote:\n---------------------------------\n' + body;
				$("#messages_body").val(newbody).caret(0);
			}
		});
		var subject = 'Re: ' + $("#message_view_subject").val();
		$("#messages_subject").val(subject);
		var pid = $("#message_view_pid").val();
		var patient_name = $("#message_view_patient_name").val();
		$("#messages_pid").val(pid);
		$("#messages_patient").val(patient_name);
		$("#message_view_wrapper").hide('fast');
		$("#internal_messages_form_id").show('fast');
		$("#internal_messages_dialog").dialog('open');
		$("#messages_body").focus();
	});
	$("#forward_message").button({
		icons: {
			primary: "ui-icon-arrow-1-e"
		}
	});
	$("#forward_message").click(function(){
		var rawtext = $("#message_view_rawtext").val();
		var newbody = '\n\n--------Forwarded Message--------\n' + rawtext;
		$("#messages_body").val(newbody);
		var subject = 'Fwd: ' + $("#message_view_subject").val();
		$("#messages_subject").val(subject);
		$("#message_view_wrapper").hide('fast');
		$("#internal_messages_form_id").show('fast');
		$("#internal_messages_dialog").dialog('open');
		$("#messages_to").focus();
	});
	$("#open_chart").button({
		icons: {
			primary: "ui-icon-folder-open"
		}
	});
	$("#open_chart").click(function(){
		var pid = $("#message_view_pid").val();
		if(pid){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('search/openchart/');?>",
				data: "pid=" + pid,
				success: function(data){
					window.location = "<?php echo site_url('search/openchart1/');?>";
				}
			});
		} else {
			$.jGrowl("No patient is associated with this message!");
		}
	});
	$("#export_message").button({
		icons: {
			primary: "ui-icon-copy"
		}
	});
	$("#export_message").click(function(){
		var pid = $("#message_view_pid").val();
		var subject = $("#message_view_subject").val();
		var message = $("#message_view_body").val();
		var date = $("#message_view_date").val();
		if(pid){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/export_message');?>",
				data: "pid=" + pid + "&t_messages_subject=" + subject + "&t_messages_message=" + message + "&t_messages_date=" + date,
				success: function(data){
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("No patient is associated with this message!");
		}
	});
	$("#export_message1").button({
		icons: {
			primary: "ui-icon-copy"
		}
	});
	$("#export_message1").click(function(){
		var pid = $("#message_view_pid1").val();
		var subject = $("#message_view_subject1").val();
		var message = $("#message_view_body1").val();
		var date = $("#message_view_date1").val();
		if(pid){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/export_message');?>",
				data: "pid=" + pid + "&t_messages_subject=" + subject + "&t_messages_message=" + message + "&t_messages_date=" + date,
				success: function(data){
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("No patient is associated with this message!");
		}
	});
	
	$("#new_fax").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#new_fax").click(function(){
		$("#sendfinal").clearForm();
		$("#img_preview").hide('fast');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/messaging/new_fax');?>",
			success: function(data){
				$.jGrowl(data);
				$("#faxcoverpage").attr('checked', false);
				$("#formmessagecoverpage").hide("fast");
				$("#faxmessage").val('');
				$("#faxschedule").attr('checked', false);
				$("#dateset").hide("fast");
				$("#datepicker").val('');
				$("#time").val('');
				jQuery("#send_list").jqGrid('GridUnload');
				jQuery("#send_list").jqGrid({
					url:"<?php echo site_url('assistant/messaging/send_list');?>",
					editurl:"<?php echo site_url('assistant/messaging/edit_send_list');?>",
					datatype: "json",
					mtype: "POST",
					colNames:['ID','Recipient','Fax Number'],
					colModel:[
						{name:'sendlist_id',index:'sendlist_id',width:1,hidden:true},
						{name:'faxrecipient',index:'faxrecipient',width:300,editable:true},
						{name:'faxnumber',index:'faxnumber',width:100,editable:true}
					],
					rowNum:10,
					rowList:[10,20,30],
					pager: jQuery('#send_list_pager'),
					sortname: 'faxrecipient',
				 	viewrecords: true,
				 	sortorder: "desc",
				 	caption:"Fax Recipients",
				 	emptyrecords:"No recipients.",
				 	height: "100%",
				 	jsonReader: { repeatitems : false, id: "0" }
				}).navGrid('#send_list_pager',{search:false,edit:false,add:false,del:false});
				jQuery("#pages_list").jqGrid('GridUnload');
				jQuery("#pages_list").jqGrid({
					url:"<?php echo site_url('assistant/messaging/pages_list');?>",
					datatype: "json",
					mtype: "POST",
					colNames:['ID','File','Pages','Full Path'],
					colModel:[
						{name:'pages_id',index:'pages_id',width:1,hidden:true},
						{name:'file_original',index:'file_original',width:300},
						{name:'pagecount',index:'pagecount',width:100},
						{name:'file',index:'file',width:1,hidden:true}
					],
					rowNum:10,
					rowList:[10,20,30],
					pager: jQuery('#pages_list_pager'),
					sortname: 'pages_id',
				 	viewrecords: true,
				 	sortorder: "asc",
				 	caption:"Fax Pages",
				 	emptyrecords:"No pages.",
				 	height: "100%",
				 	jsonReader: { repeatitems : false, id: "0" }
				}).navGrid('#pages_list_pager',{search:false,edit:false,add:false,del:false});
				$("#select_recipients").show('fast');
				$("#quick_search_contact").focus();
			}
		});	
	});
	jQuery("#received_faxes").jqGrid({
		url:"<?php echo site_url('assistant/messaging/receive_fax');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','Pages','From','FileName','FaxPath'],
		colModel:[
			{name:'received_id',index:'received_id',width:1,hidden:true},
			{name:'fileDateTime',index:'fileDate',width:150},
			{name:'filePages',index:'filePages',width:50},
			{name:'fileFrom',index:'fileFrom',width:350},
			{name:'fileName',index:'fileName',width:1,hidden:true},
			{name:'filePath',index:'filePath',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#received_faxes_pager'),
		sortname: 'fileDateTime',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	height: "100%",
	 	caption:"Received Faxes",
	 	onSelectRow: function(id){
	 		var file = jQuery("#received_faxes").getCell(id,'fileName');
	 		if(file){
				$("#img_preview").show('fast');
				var thumbnail = file.split(".");
				var image = "<?php echo site_url('assistant/messaging/view_thumbnail');?>/" + thumbnail[0];
				$("#img_preview").html("<img src='" + image + "'></img>");
				$("#select_recipients").hide('fast');
				$("#status_preview").hide('fast');
			} else {
				$.jGrowl("Please select fax to view!");
			}
		},
	 	emptyrecords:"No faxes received.",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#received_faxes_pager',{search:false,edit:false,add:false,del:false});
	$("#delete").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#delete").click(function(){
		var click_id = jQuery("#received_faxes").getGridParam('selrow');
		if(click_id){
			if(confirm('Are you sure you want to delete this fax?')){ 
				var click_filePath = jQuery("#received_faxes").getCell(click_id,'filePath');
				var click_fileName = jQuery("#received_faxes").getCell(click_id,'fileName');
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/messaging/deletefax');?>",
					data: "filePath=" + click_filePath + "&fileName=" + click_fileName,
					success: function(data){
						$.jGrowl(data);
						jQuery("#received_faxes").trigger("reloadGrid");
					}
				});
			}
		} else {
			$.jGrowl("Please select fax to delete!");
		}
	});
	$("#savefax").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#savefax").click(function(){
		var id = jQuery("#received_faxes").getGridParam('selrow');
		$("#view_received_id").val(id);
 		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/messaging/view_fax1');?>/" + id,
			dataType: "json",
			success: function(data){
				$("#embedURL1").html(data.html);
				$("#fax_filepath").val(data.filepath);
				$("#fax_view_dialog").dialog('open');
			}
		});
	});
	$("#fax_patient_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search');?>",
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
		minLength: 1,
		select: function(event, ui){
			$("#fax_pid").val(ui.item.id);
		}
	});
	$("#fax_import_documents_from").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/document_from');?>",
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
		minLength: 2
	});
	$("#fax_import_documents_desc").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/document_description');?>",
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
		minLength: 2
	});
	$("#fax_import_documents_type").addOption({"Laboratory":"Laboratory","Imaging":"Imaging","Cardiopulmonary":"Cardiopulmonary","Endoscopy":"Endoscopy","Referrals":"Referrals","Past Records":"Past Records","Other Forms":"Other Forms"}, false);
	$("#fax_import_documents_date").mask("99/99/9999");
	$("#fax_import_documents_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#fax_import_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		beforeclose: function(event, ui) {
			var a = $("#fax_received_id").val();
			if(a != ''){
				if(confirm('You have not completed importing the fax.  Are you sure you want to close this window?')){ 
					$('#fax_import_form').clearForm();
					$("#fax_import_message").html('');
					return true;
				} else {
					return false;
				}
			} else {
				$('#fax_import_form').clearForm();
				$("#fax_import_message").html('');
				return true;
			}
		}
	});
	$("#save_fax_import").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_fax_import").click(function() {
		var pid = $("#fax_pid");
		var type = $("#fax_import_documents_type");
		var date = $("#fax_import_documents_date");
		var bValid = true;
		bValid = bValid && checkEmpty(pid,"Patient");
		bValid = bValid && checkEmpty(type,"Documents Type");
		bValid = bValid && checkEmpty(date,"Documents Date");
		if (bValid) {
			var str = $("#fax_import_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/messaging/fax_import');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$('#fax_import_form').clearForm();
						$("#fax_import_message").html('');
						$('#fax_import_dialog').dialog('close');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_fax_import").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_fax_import").click(function() {
		$('#fax_import_form').clearForm();
		$("#fax_import_message").html('');
		$('#fax_import_dialog').dialog('close');
	});
	$("#savefax1").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#savefax1").click(function(){
		var click_id = jQuery("#received_faxes").getGridParam('selrow');
		if(click_id){
			$("#fax_received_id").val(click_id);
			var row = jQuery("#received_faxes").getRowData(click_id);
			var text = "Enter details for importing fax from " + row['fileFrom'] + ":";
			$("#fax_import_message").html(text);
			$("#fax_import_dialog").dialog('open');
			$("#fax_patient_search").focus();
		}
	});
	jQuery("#draft_faxes").jqGrid({
		url:"<?php echo site_url('assistant/messaging/drafts_list');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Fax Subject'],
		colModel:[
			{name:'job_id',index:'job_id',width:100},
			{name:'faxsubject',index:'faxsubject',width:450}	
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#draft_faxes_pager'),
		sortname: 'job_id',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Draft Faxes",
	 	height:"100%",
	 	emptyrecords:"No drafts",
	 	hiddengrid: true,
	 	onSelectRow: function(id){
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/set_id/');?>",
				data: "job_id=" + id,
				success: function(data){
					$("#img_preview").hide('fast');
					$("#status_preview").hide('fast');
					jQuery("#send_list").jqGrid('GridUnload');
					jQuery("#send_list").jqGrid({
						url:"<?php echo site_url('assistant/messaging/send_list');?>",
						editurl:"<?php echo site_url('assistant/messaging/edit_send_list');?>",
						datatype: "json",
						mtype: "POST",
						colNames:['ID','Recipient','Fax Number'],
						colModel:[
							{name:'sendlist_id',index:'sendlist_id',width:1,hidden:true},
							{name:'faxrecipient',index:'faxrecipient',width:300,editable:true},
							{name:'faxnumber',index:'faxnumber',width:100,editable:true}
						],
						rowNum:10,
						rowList:[10,20,30],
						pager: jQuery('#send_list_pager'),
						sortname: 'faxrecipient',
					 	viewrecords: true,
					 	sortorder: "desc",
					 	caption:"Fax Recipients",
					 	emptyrecords:"No recipients.",
					 	height: "100%",
					 	jsonReader: { repeatitems : false, id: "0" }
					}).navGrid('#send_list_pager',{search:false,edit:false,add:false,del:false});
					jQuery("#pages_list").jqGrid('GridUnload');
					jQuery("#pages_list").jqGrid({
						url:"<?php echo site_url('assistant/messaging/pages_list');?>",
						datatype: "json",
						mtype: "POST",
						colNames:['ID','File','Pages','Full Path'],
						colModel:[
							{name:'pages_id',index:'pages_id',width:1,hidden:true},
							{name:'file_original',index:'file_original',width:300},
							{name:'pagecount',index:'pagecount',width:100},
							{name:'file',index:'file',width:1,hidden:true}
						],
						rowNum:10,
						rowList:[10,20,30],
						pager: jQuery('#pages_list_pager'),
						sortname: 'pages_id',
					 	viewrecords: true,
					 	sortorder: "asc",
					 	caption:"Fax Pages",
					 	emptyrecords:"No pages.",
					 	height: "100%",
					 	jsonReader: { repeatitems : false, id: "0" }
					}).navGrid('#pages_list_pager',{search:false,edit:false,add:false,del:false});
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/messaging/sendfinal');?>",
						dataType: "json",
						success: function(data){
							$("#faxsubject").val(data.faxsubject);
							if (data.task == "Draft") {
								if (data.faxcoverpage == 'yes') {
									$("#faxcoverpage").attr('checked', true);
									$("#formmessagecoverpage").show("fast");
									$("#faxmessage").val(data.faxmessage);
								} else {
									$("#faxcoverpage").attr('checked', false);
									$("#formmessagecoverpage").hide("fast");
									$("#faxmessage").val('');
								}
								if (data.faxschedule == 'yes') {
									$("#faxschedule").attr('checked', true);
									$("#dateset").show("fast");
									$("#datepicker").val(data.datepicker);
									$("#time").val(data.time);
								} else {
									$("#faxschedule").attr('checked', false);
									$("#dateset").hide("fast");
									$("#datepicker").val('');
									$("#time").val('');
								}
							} else {
								$("#sendfinal").clearForm();
							}
							$.jGrowl(data.message);
							$("#select_recipients").show('fast');
						}
					});
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/messaging/fax_type_check');?>",
						success: function(data){
							if (data == 'nosh') {
								$("#formmessageschedule").hide('fast');
							} else {
								$("#formmessageschedule").show('fast');
							}
						}
					});
				}
			});
	 	},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#draft_faxes_pager',{search:false,edit:false,add:false,del:false
	});
	$("#quick_search_contact").autocomplete({
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
		minLength: 2,
		select: function(event, ui){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/add_fax_recipient');?>",
				data: "displayname=" + ui.item.value + "&fax=" + ui.item.fax,
				success: function(data){
					$.jGrowl(data);
					jQuery("#send_list").trigger("reloadGrid");
				}
			});
		}
	});
	$("#addrecipient").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#addrecipient").click(function(){
		jQuery("#send_list").editGridRow("new",{closeAfterAdd:true});
	});
	$("#editrecipient").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#editrecipient").click(function(){
		var clickedit = jQuery("#send_list").getGridParam('selrow');
		if(clickedit){ 
			jQuery("#send_list").editGridRow(clickedit,{closeAfterEdit:true});
		} else {
			$.jGrowl("Please select recipient to edit!");
		}
	});
	$("#removerecipient").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#removerecipient").click(function(){
		var clickremove = jQuery("#send_list").getGridParam('selrow');
			if(clickremove){
				jQuery("#send_list").delGridRow(clickremove);
			} else {
				$.jGrowl("Please select recipient to remove!");
			}
	});
	$("#addfile_upload").button();
	$('#addfile_form').iframer({ 
		iframe: 'addfile_iframe',
		returnType: 'html',
		onComplete: function(data){ 
			$.jGrowl(data);
			jQuery("#pages_list").trigger("reloadGrid");
		} 
	});
	$("#delfile").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#delfile").click(function(){
		var clickremove = jQuery("#pages_list").getGridParam('selrow');
		if(clickremove){ 
			var click_file = jQuery("#pages_list").getCell(clickremove,'file');
			var click_pages_id = jQuery("#pages_list").getCell(clickremove,'pages_id');
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/deletepage');?>",
				data: "file=" + click_file + "&pages_id=" + click_pages_id,
				success: function(data){
					$.jGrowl(data);
					jQuery("#pages_list").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select files to remove!");
		}
	});
	$("#viewpage").button({
		icons: {
			primary: "ui-icon-print"
		}
	});
	$("#viewpage").click(function(){
		var click_id = jQuery("#pages_list").getGridParam('selrow');
		if(click_id){
			window.open("<?php echo site_url('assistant/messaging/view_page');?>/" + click_id);
		}
	});
	$("#datepicker").datepicker();
	$('#time').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	$("#faxcoverpage").click(function(){
		if ($("#faxcoverpage").is(":checked")) {
			$("#formmessagecoverpage").show("fast");
		} else {
			$("#formmessagecoverpage").hide("fast");
			$("#faxmessage").val('');
		}
	});
	$("#dateset").css("display","none");
	$("#faxschedule").click(function(){
		if ($("#faxschedule").is(":checked")) {
			$("#dateset").show("fast");
		} else {
			$("#dateset").hide("fast");
			$("#datepicker").val('');
			$("#time").val('');
		}
	});
	$("#send_fax").button({
		icons: {
			primary: "ui-icon-arrow-1-e"
		}
	});
	$("#send_fax").click(function(){
		var str = $("#sendfinal").serialize();		
			if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/form_submit2');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$("#sendfinal").clearForm();
					$("#select_recipients").hide('fast');	
				}
			});
		} else {
			alert("Please complete the form");
		}
	});
	$("#cancel_fax").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$('#cancel_fax').click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/messaging/cancelfax');?>",
			success: function(data){
				$.jGrowl(data);
				$("#sendfinal").clearForm();
				$("#select_recipients").hide('fast');
				jQuery("#draft_faxes").trigger("reloadGrid");
			}
		});
	});
	jQuery("#sent_faxes").jqGrid({
		url:"<?php echo site_url('assistant/messaging/sent_list');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Sent Date','Fax Subject','Status','Attempts'],
		colModel:[
			{name:'job_id',index:'job_id',width:50},
			{name:'sentdate',index:'sentdate',width:100},
			{name:'faxsubject',index:'faxsubject',width:250},
			{name:'success',index:'success',width:100,formatter:statusfn},
			{name:'attempts',index:'attempts',width:50}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#sent_faxes_pager'),
		sortname: 'job_id',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	height: "100%",
	 	caption:"Sent Faxes",
	 	hiddengrid: true,
	 	onSelectRow: function(id){
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/set_id/');?>",
				data: "job_id=" + id,
				success: function(data){
					$("#img_preview").hide('fast');
					$("#status_preview").hide('fast');
					jQuery("#send_list").jqGrid('GridUnload');
					jQuery("#send_list").jqGrid({
						url:"<?php echo site_url('assistant/messaging/send_list');?>",
						editurl:"<?php echo site_url('assistant/messaging/edit_send_list');?>",
						datatype: "json",
						mtype: "POST",
						colNames:['ID','Recipient','Fax Number'],
						colModel:[
							{name:'sendlist_id',index:'sendlist_id',width:1,hidden:true},
							{name:'faxrecipient',index:'faxrecipient',width:300,editable:true},
							{name:'faxnumber',index:'faxnumber',width:100,editable:true}
						],
						rowNum:10,
						rowList:[10,20,30],
						pager: jQuery('#send_list_pager'),
						sortname: 'faxrecipient',
					 	viewrecords: true,
					 	sortorder: "desc",
					 	caption:"Fax Recipients",
					 	emptyrecords:"No recipients.",
					 	height: "100%",
					 	jsonReader: { repeatitems : false, id: "0" }
					}).navGrid('#send_list_pager',{search:false,edit:false,add:false,del:false});
					jQuery("#pages_list").jqGrid('GridUnload');
					jQuery("#pages_list").jqGrid({
						url:"<?php echo site_url('assistant/messaging/pages_list');?>",
						datatype: "json",
						mtype: "POST",
						colNames:['ID','File','Pages','Full Path'],
						colModel:[
							{name:'pages_id',index:'pages_id',width:1,hidden:true},
							{name:'file_original',index:'file_original',width:300},
							{name:'pagecount',index:'pagecount',width:100},
							{name:'file',index:'file',width:1,hidden:true}
						],
						rowNum:10,
						rowList:[10,20,30],
						pager: jQuery('#pages_list_pager'),
						sortname: 'pages_id',
					 	viewrecords: true,
					 	sortorder: "asc",
					 	caption:"Fax Pages",
					 	emptyrecords:"No pages.",
					 	height: "100%",
					 	jsonReader: { repeatitems : false, id: "0" }
					}).navGrid('#pages_list_pager',{edit:false,add:false,del:false});
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/messaging/sendfinal');?>",
						dataType: "json",
						success: function(data){
							if (data.task == "Draft") {
								$("#faxsubject").val(data.faxsubject);
								if (data.faxcoverpage == 'yes') {
									$("#faxcoverpage").attr('checked', true);
									$("#formmessagecoverpage").show("fast");
									$("#faxmessage").val(data.faxmessage);
								} else {
									$("#faxcoverpage").attr('checked', false);
									$("#formmessagecoverpage").hide("fast");
									$("#faxmessage").val('');
								}
								if (data.faxschedule == 'yes') {
									$("#faxschedule").attr('checked', true);
									$("#dateset").show("fast");
									$("#datepicker").val(data.datepicker);
									$("#time").val(data.time);
								} else {
									$("#faxschedule").attr('checked', false);
									$("#dateset").hide("fast");
									$("#datepicker").val('');
									$("#time").val('');
								}
							} else {
								$("#sendfinal").clearForm();
							}
							$.jGrowl(data.message);
							$("#select_recipients").show('fast');
						}
					});
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/messaging/fax_type_check');?>",
						success: function(data){
							if (data == 'nosh') {
								$("#formmessageschedule").hide('fast');
							} else {
								$("#formmessageschedule").show('fast');
							}
						}
					});
				}
			});
	 	},
	 	emptyrecords:"No sent faxes.",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#sent_faxes_pager',{search:false,edit:false,add:false,del:false});
	function statusfn (cellvalue, options, rowObject)
	{
		if (cellvalue == '1') {
			return 'Sent';
		} else {
			return 'Not Sent';
		}
	}
	
	
	$("#get_scans").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#get_scans").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/messaging/get_scans');?>",
			success: function(data){
				jQuery("#received_scans").trigger("reloadGrid");
			}
		});
	});
	jQuery("#received_scans").jqGrid({
		url:"<?php echo site_url('assistant/messaging/scans');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','Pages','File Name','FaxPath'],
		colModel:[
			{name:'scans_id',index:'scans_id',width:1,hidden:true},
			{name:'fileDateTime',index:'fileDate',width:150},
			{name:'filePages',index:'filePages',width:50},
			{name:'fileName',index:'fileName',width:350},
			{name:'filePath',index:'filePath',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#received_scans_pager'),
		sortname: 'fileDateTime',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	height: "100%",
	 	caption:"Scanned Documents",
	 	onCellSelect: function(id,iCol){
	 		if (iCol > 0) {
		 		$("#view_scans_id").val(id);
		 		$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/messaging/view_scan1');?>/" + id,
					dataType: "json",
					success: function(data){
						$("#embedURL3").html(data.html);
						$("#scan_filepath").val(data.filepath);
						$("#scan_view_dialog").dialog('open');
					}
				});
			}
	 	},
	 	emptyrecords:"No scanned documents.",
	 	multiselect: true,
	 	multiboxonly: true,
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#received_scans_pager',{search:false,edit:false,add:false,del:false});
	$("#scan_patient_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search');?>",
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
		minLength: 1,
		select: function(event, ui){
			$("#scan_pid").val(ui.item.id);
		}
	});
	$("#scan_import_documents_from").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/document_from');?>",
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
		minLength: 2
	});
	$("#scan_import_documents_desc").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/document_description');?>",
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
		minLength: 2
	});
	$("#scan_import_documents_type").addOption({"Laboratory":"Laboratory","Imaging":"Imaging","Cardiopulmonary":"Cardiopulmonary","Endoscopy":"Endoscopy","Referrals":"Referrals","Past Records":"Past Records","Other Forms":"Other Forms"}, false);
	$("#scan_import_documents_date").mask("99/99/9999");
	$("#scan_import_documents_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#scan_import_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		beforeclose: function(event, ui) {
			var a = $("#scan_scans_id").val();
			if(a != ''){
				if(confirm('You have not completed importing the document.  Are you sure you want to close this window?')){ 
					$('#scan_import_form').clearForm();
					$("#scan_import_message").html('');
					return true;
				} else {
					return false;
				}
			} else {
				$('#scan_import_form').clearForm();
				$("#scan_import_message").html('');
				return true;
			}
		}
	});
	$("#save_scan_import").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_scan_import").click(function() {
		var pid = $("#scan_pid");
		var type = $("#scan_import_documents_type");
		var date = $("#scan_import_documents_date");
		var bValid = true;
		bValid = bValid && checkEmpty(pid,"Patient");
		bValid = bValid && checkEmpty(type,"Documents Type");
		bValid = bValid && checkEmpty(date,"Documents Date");
		if (bValid) {
			var str = $("#scan_import_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/messaging/scan_import');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$('#scan_import_form').clearForm();
						$("#scan_import_message").html('');
						$('#scan_import_dialog').dialog('close');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_scan_import").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_scan_import").click(function() {
		$('#scan_import_form').clearForm();
		$("#scan_import_message").html('');
		$('#scan_import_dialog').dialog('close');
	});
	$("#savescan").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#savescan").click(function(){
		var click_id = jQuery("#received_scans").getGridParam('selrow');
		if(click_id){
			$("#scan_scans_id").val(click_id);
			var row = jQuery("#received_scans").getRowData(click_id);
			var text = "Enter details for importing document named " + row['fileName'] + ":";
			$("#scan_import_message").html(text);
			$("#scan_import_dialog").dialog('open');
			$("#scan_patient_search").focus();
		}
	});
	$("#savescan1").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#savescan1").click(function(){
		$("#scan_pid1").val('');
		$("#scan_patient_search1").val('');
		$("#savescan1_div").show('fast');
		$("#scan_patient_search1").focus();
	});
	$("#scan_patient_search1").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search');?>",
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
		minLength: 1,
		select: function(event, ui){
			$("#scan_pid1").val(ui.item.id);
		}
	});
	$("#savescan1_send").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#savescan1_send").click(function(){
		var click_id = jQuery("#received_scans").getGridParam('selarrrow');
		var pid = $("#scan_pid1").val();
		if(click_id){
			var count = click_id.length;
			for (var i = 0; i < count; i++) {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/messaging/scan_import1');?>",
					data: "scans_id=" + click_id[i] + "&pid=" + pid,
					success: function(data){
					}
				});
			}
			$.jGrowl('Imported ' + i + ' documents!');
			$("#scan_pid1").val('');
			$("#scan_patient_search1").val('');
			$("#savescan1_div").hide('fast');
		}
	});
	$("#savescan1_cancel").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#savescan1_cancel").click(function(){
		$("#scan_pid1").val('');
		$("#scan_patient_search1").val('');
		$("#savescan1_div").hide('fast');
	});
	$("#delete_scan").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#delete_scan").click(function(){
		var click_id = jQuery("#received_scans").getGridParam('selarrrow');
		if(click_id){
			if(confirm('Are you sure you want to delete the seletected documents?')){ 
				var count = click_id.length;
				for (var i = 0; i < count; i++) {
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/messaging/deletescan');?>",
						data: "scans_id=" + click_id[i],
						success: function(data){
						}
					});
				}
				$.jGrowl('Deleted ' + i + ' documents!');
				jQuery("#received_scans").trigger("reloadGrid");
			}
		} else {
			$.jGrowl("Please select document to delete!");
		}
	});
	$("#import_csv").click(function() {
		var html = '<input type="file" name="fileToUpload1" id="fileToUpload1"> <input type="submit" id="import_csv_upload" value="Upload">';
		$("#import_csv_span").html(html);
		$("#import_csv_upload").button();
		$("#import_csv_div").show('fast');
	});
	$('#import_csv_form').iframer({ 
		iframe: 'import_csv_iframe',
		returnType: 'html',
		onComplete: function(data){ 
			$.jGrowl(data);
			jQuery("#all_contacts_list").trigger("reloadGrid");
			$("#import_csv_div").hide('fast');
			$("#import_csv_span").html('');
		} 
	});
	jQuery("#all_contacts_list").jqGrid({
		url:"<?php echo site_url('assistant/messaging/all_contacts');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Name','Specialty','Last Name','First Name','Prefix','Suffix','Facility','Street Address 1','Street Address 2','City','State','Zip','Phone','Fax','Email','Comments','NPI'],
		colModel:[
			{name:'address_id',index:'address_id',width:1,hidden:true},
			{name:'displayname',index:'displayname',width:200},
			{name:'specialty',index:'specialty',width:150},
			{name:'lastname',index:'lastname',width:1,hidden:true},
			{name:'firstname',index:'firstname',width:1,hidden:true},
			{name:'prefix',index:'prefix',width:1,hidden:true},
			{name:'suffix',index:'suffix',width:1,hidden:true},
			{name:'facility',index:'facility',width:1,hidden:true},
			{name:'street_address1',index:'street_address1',width:150},
			{name:'street_address2',index:'street_address2',width:1,hidden:true},
			{name:'city',index:'city',width:100},
			{name:'state',index:'state',width:50},
			{name:'zip',index:'zip',width:1,hidden:true},
			{name:'phone',index:'phone',width:100},
			{name:'fax',index:'fax',width:100},
			{name:'email',index:'email',width:1,hidden:true},
			{name:'comments',index:'comments',width:1,hidden:true},
			{name:'npi',index:'npi',width:1,hidden:true}
		],
		rowNum:20,
		rowList:[10,20,30],
		pager: jQuery('#all_contacts_list_pager'),
		sortname: 'displayname',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	height: "100%",
	 	caption:"Address Book",
	 	emptyrecords:"No contacts.",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#all_contacts_list_pager',{search:false,edit:false,add:false,del:false});
	$("#messaging_add_contact").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#messaging_add_contact").click(function(){
		$('#messaging_contact_form').clearForm();
		$('#contacts_dialog').dialog('open');
		$("#messaging_lastname").focus();
	});
	$("#messaging_edit_contact").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#messaging_edit_contact").click(function(){
		var item = jQuery("#all_contacts_list").getGridParam('selrow');
		if(item){
			jQuery("#all_contacts_list").GridToForm(item,"#messaging_contact_form");
			$('#contacts_dialog').dialog('open');
			$("#messaging_lastname").focus();
		} else {
			$.jGrowl("Please select contact to edit!")
		}
	});
	$("#messaging_delete_contact").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#messaging_delete_contact").click(function(){
		var item = jQuery("#all_contacts_list").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this contact?')){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/messaging/delete_contact');?>",
					data: "address_id=" + item,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							jQuery("#all_contacts_list").trigger("reloadGrid");
						}
					}
				});
			}
		} else {
			$.jGrowl("Please select contact to delete!")
		}
	});
	$("#contacts_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		close: function(event, ui) {
			$('#messaging_contact_form').clearForm();
		}
	});
	$("#messaging_specialty").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/specialty1');?>",
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
	$("#messaging_city").autocomplete({
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
	$("#messaging_state").addOption({"AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorefo","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#messaging_phone").mask("(999) 999-9999");
	$("#messaging_fax").mask("(999) 999-9999");
	$("#messaging_npi").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/npi_lookup');?>",
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
		open: function() { 
			$('.ui-menu').width(300);
		}
	}).focus(function() {
		var a = $("#messaging_lastname").val();
		var b = $("#messaging_firstname").val();
		var c = $("#messaging_state").val();
		if (a != "" && b != "" && c != "") {
			var q = a + "," + b + "," + c
			$("#messaging_npi").autocomplete("search", q);
		}
	}).mask("9999999999");
	$("#messaging_save_contact").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#messaging_save_contact").click(function(){
		var str = $("#messaging_contact_form").serialize();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/messaging/edit_contact');?>",
			data: str,
			success: function(data){
				$.jGrowl(data);
				$("#messaging_contact_form").clearForm();
				$("#contacts_dialog").dialog('close');
				jQuery("#all_contacts_list").trigger("reloadGrid");
			}
		});
	});
	$("#messaging_cancel_contact").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#messaging_cancel_contact").click(function(){
		$("#messaging_contact_form").clearForm();
		$("#contacts_dialog").dialog('close');
	});
	$("#fax_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		close: function(event, ui) {
			var a = $("#fax_filepath").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/close_fax');?>",
				data: "fax_filepath=" + a,
				success: function(data){
					$("#embedURL1").html('');
					$("#fax_filepath").val('');
					$("#view_received_id").val('');
					$("#import_fax_pages").val('');
				}
			});	
		}
	});
	$("#save_fax").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_fax").click(function() {
		var id = $("#view_received_id").val();
		window.open("<?php echo site_url('assistant/messaging/view_fax');?>/" + id);
	});
	$("#import_fax").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#import_fax").click(function() {
		var id = $("#view_received_id").val();
		var pages = $("#import_fax_pages").val();
		var row = jQuery("#received_faxes").getRowData(id);
		if (pages != '') {
			var text = "Enter details for importing fax from " + row['fileFrom'] + ", pages " + pages + ":";
		} else {
			var text = "Enter details for importing fax from " + row['fileFrom'] + ":";
		}
		$("#fax_received_id").val(id);
		$("#fax_import_pages").val(pages);
		$("#fax_import_message").html(text);
		$("#fax_import_dialog").dialog('open');
		$("#fax_patient_search").focus();
	});
	$("#pages_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		close: function(event, ui) {
			var a = $("#document_filepath").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/chartmenu/close_document');?>",
				data: "document_filepath=" + a,
				success: function(data){
					$("#embedURL2").html('');
				}
			});	
		}
	});
	$("#scan_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		close: function(event, ui) {
			var a = $("#scan_filepath").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/messaging/close_scan');?>",
				data: "scan_filepath=" + a,
				success: function(data){
					$("#embedURL3").html('');
					$("#scan_filepath").val('');
					$("#view_scans_id").val('');
					$("#import_scan_pages").val('');
				}
			});	
		}
	});
	$("#save_scan").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_scan").click(function() {
		var id = $("#view_scans_id").val();
		window.open("<?php echo site_url('assistant/messaging/view_scan');?>/" + id);
	});
	$("#import_scan").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#import_scan").click(function(){
		var id = $("#view_scans_id").val();
		var pages = $("#import_scan_pages").val();
		var row = jQuery("#received_scans").getRowData(id);
		if (pages != '') {
			var text = "Enter details for importing document named " + row['fileFrom'] + ", pages " + pages + ":";
		} else {
			var text = "Enter details for importing document named " + row['fileName'] + ":";
		}
		$("#scan_scans_id").val(id);
		$("#scan_import_pages").val(pages);
		$("#scan_import_message").html(text);
		$("#scan_import_dialog").dialog('open');
		$("#scan_patient_search").focus();
	});
	$.ajax({
		url: "<?php echo site_url('start/check_fax');?>",
		type: "POST",
		success: function(data){
			if (data == "Yes") {
				$("#messaging_fax_tab").show('fast');
			} else {
				$("#messaging_fax_tab").hide('fast');
			}
		}
	});
});
</script>
<div id="heading2"></div>
<div id ="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="maincontent_full">
		<h4>NOSH ChartingSystem Message Center</h4>
		<div id="noshtabs">
			<div id="messaging_provider_tabs">
				<ul>
					<li><a href="#provider_messaging_tabs_1">Messaging</a></li>
					<li id="messaging_fax_tab"><a href="#provider_messaging_tabs_2">Faxes</a></li>
					<li><a href="#provider_messaging_tabs_3">Scans</a></li>
					<li><a href="#provider_messaging_tabs_4">Address Book</a></li>
				</ul>
				<div id="provider_messaging_tabs_1" style="overflow:hidden">
					<span style="float:left;width:600px">
						<button type="button" id="new_message">New Message</button><br><hr class="ui-state-default"/>
						<table id="internal_inbox" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="internal_inbox_pager" class="scroll" style="text-align:center;"></div><br>
						<table id="internal_draft" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="internal_draft_pager" class="scroll" style="text-align:center;"></div><br>
						<table id="internal_outbox" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="internal_outbox_pager" class="scroll" style="text-align:center;"></div>
					</span>
					<div id="internal_messages_dialog" title="Internal Message">
						<form name="internal_messages_form" id="internal_messages_form_id" style="display: none">
							<input type="hidden" name="message_id" id="messages_message_id"/>
							<input type="hidden" name="pid" id="messages_pid">
							<input type="hidden" name="t_messages_id" id="messages_t_messages_id">
							<button type="button" id="send_message">Send</button>
							<button type="button" id="draft_message">Save Draft</button>
							<button type="button" id="cancel_message">Cancel</button>
							<hr class="ui-state-default"/>
							<table>
								<tbody>
									<tr>
										<td>Subject:<br>
										<input type="text" name="subject" id="messages_subject" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
									</tr>
									<tr>
										<td>Concerning this patient (optional):<br>
										<input type="text" name="patient_name" id="messages_patient" style="width:400px" class="text ui-widget-content ui-corner-all"/></td>
									</tr>
									<tr>
										<td>To:<br>
										<input type="text" id="messages_to" name="message_to" style="width:400px"class="text ui-widget-content ui-corner-all"/></td>
									</tr>
									<tr>
										<td>CC:<br>
										<input type="text" id="messages_cc" name="cc" style="width:400px"class="text ui-widget-content ui-corner-all"/></td>
									</tr>
									<tr>
										<td>Message:<br>
										<textarea name="body" id="messages_body" rows="12" style="width:400px" class="text ui-widget-content ui-corner-all"></textarea></td>
									</tr>
								</tbody>
							</table>
						</form>
						<div id="message_view_wrapper" style="display:none">
							<button type="button" id="reply_message">Reply</button>
							<button type="button" id="reply_all_message">Reply All</button>
							<button type="button" id="forward_message">Forward</button>
							<button type="button" id="open_chart">Open Chart</button>
							<button type="button" id="export_message">Export to Patient Chart</button><br>
							<input type="hidden" id="message_view_message_id">
							<input type="hidden" id="message_view_to">
							<input type="hidden" id="message_view_from">
							<input type="hidden" id="message_view_cc">
							<input type="hidden" id="message_view_subject">
							<input type="hidden" id="message_view_body">
							<input type="hidden" id="message_view_date">
							<input type="hidden" id="message_view_pid">
							<input type="hidden" id="message_view_patient_name">
							<input type="hidden" id="message_view_rawtext">
							<input type="hidden" id="message_view_t_messages_id">
							<hr class="ui-state-default"/>
							<div id="message_view"></div>
						</div>
						<div id="message_view_wrapper2" style="display:none">
							<button type="button" id="export_message1">Export to Patient Chart</button><br>
							<input type="hidden" id="message_view_subject1">
							<input type="hidden" id="message_view_body1">
							<input type="hidden" id="message_view_date1">
							<input type="hidden" id="message_view_pid1">
							<hr class="ui-state-default"/>
							<div id="message_view2"></div>
						</div>
					</div>
				</div>
				<div id="provider_messaging_tabs_2">
					<table>
						<tr>
							<td valign="top">
								<button type="button" id="new_fax">New Fax</button><br><hr class="ui-state-default"/>
								<table id="received_faxes" class="scroll" cellpadding="0" cellspacing="0"></table>
								<div id="received_faxes_pager" class="scroll" style="text-align:center;"></div><br>
								<button type="button" id="delete">Delete Selected</button>
								<button type="button" id="savefax">Download Selected Fax</button>
								<button type="button" id="savefax1">Save Selected Fax to Patient Chart</button><br><br>
								<table id="draft_faxes" class="scroll" cellpadding="0" cellspacing="0"></table>
								<div id="draft_faxes_pager" class="scroll" style="text-align:center;"></div><br>
								<table id="sent_faxes" class="scroll" cellpadding="0" cellspacing="0"></table>
								<div id="sent_faxes_pager" class="scroll" style="text-align:center;"></div><br>
							</td>
							<td valign="top">
								<div id="img_preview" style="display:none"></div>
								<div id="status_preview" style="display:none"></div>
								<div id="select_recipients" style="display:none">
									Select Fax Recipients: <input type="text" style="width:200px" id="quick_search_contact" class="text ui-widget-content ui-corner-all"/><br><br>
										<table id="send_list" class="scroll" cellpadding="0" cellspacing="0"></table>
										<div id="send_list_pager" class="scroll" style="text-align:center;"></div><br>
										<button type="button" id="addrecipient">Add Recipient</button>
										<button type="button" id="editrecipient">Edit Recipient</button>
										<button type="button" id="removerecipient">Remove Recipient</button><br>
										<hr class="ui-state-default"/>
									<form id="sendfinal">
										Subject: <input type="text" name="faxsubject" id="faxsubject" style="width:200px" class="text ui-widget-content ui-corner-all"/>
										<br>
										Coverpage: <input type="checkbox" name="faxcoverpage" id="faxcoverpage" class="text ui-widget-content ui-corner-all" value="yes"/>
										<br>
										<div id="formmessagecoverpage" style="display:none">
											Message for Coverpage:
											<textarea style="width:200px" rows="2" name="faxmessage" id="faxmessage" class="text ui-widget-content ui-corner-all"></textarea>
											<br>
										</div>
										<div id="dateset">
											Date: <input type="text" name="datepicker" id="datepicker" class="text ui-widget-content ui-corner-all">
											<br>
											Time: <input type="text" name="time" id="time" class="text ui-widget-content ui-corner-all">
											<br>
										</div>
										Save as Draft (do not send): <input type="checkbox" name="faxdraft" value="yes"/>
										<hr class="ui-state-default"/>
									</form>
									<table>
										<tr>
											<td valign="top">
												<table id="pages_list" class="scroll" cellpadding="0" cellspacing="0"></table>
												<div id="pages_list_pager" class="scroll" style="text-align:center;"></div><br/>
												<div id="addfile_div">
													<form id="addfile_form" action="<?php echo site_url('assistant/messaging/pages_upload');?>" method="post" enctype="multipart/form-data">
														<span id="addfile_span"><input type="file" name="fileToUpload" id="fileToUpload"> <input type="submit" id="addfile_upload" value="Upload"></span>
													</form>
												</div>
											</td>
											<td valign="top">
												<button type="button" id="delfile">Remove Pages</button><br>
												<button type="button" id="viewpage">View Pages</button>
											</td>
										</tr>
									</table>
									<hr class="ui-state-default"/>
									<button type="button" id="send_fax">Process Fax</button>
									<button type="button" id="cancel_fax">Cancel</button>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="provider_messaging_tabs_3">
					<table>
						<tr>
							<td valign="top">
								<button type="button" id="get_scans">Get Scanned Documents</button><br><hr class="ui-state-default"/>
								<table id="received_scans" class="scroll" cellpadding="0" cellspacing="0"></table>
								<div id="received_scans_pager" class="scroll" style="text-align:center;"></div><br>
								<button type="button" id="savescan">Import Selected Scan</button>
								<button type="button" id="savescan1">Batch Import</button>
								<button type="button" id="delete_scan">Delete Selected Scan</button>
							</td>
							<td valign="top">
								<div id="savescan1_div" style="display:none">
									<br><br>
									<input type="hidden" id="scan_pid1"/>
									<table>
										<tr>
											<td valign="top">
												Choose patient:<br><input type="text" name="patient_search" id="scan_patient_search1" style="width:500px" class="text ui-widget-content ui-corner-all" /> 
											</td>
											<td valign="top">
												<button type="button" id="savescan1_send">Import</button><br>
												<button type="button" id="savescan1_cancel">Cancel</button>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="provider_messaging_tabs_4">
					<a href="#" id="import_csv">Import CSV File</a>
					<div id="import_csv_div" style="display:none"> 
						<br>
						<form id="import_csv_form" action="<?php echo site_url('assistant/messaging/import_contact');?>" method="post" enctype="multipart/form-data">
							<span id="import_csv_span"></span>
						</form>
					</div><br><br>
					Search: <input type="text" size="50" id="search_all_contact" class="text ui-widget-content ui-corner-all" onkeydown="doSearch(arguments[0]||event)"/><br><br> 
					<table id="all_contacts_list" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="all_contacts_list_pager" class="scroll" style="text-align:center;"></div><br>
					<button type="button" id="messaging_add_contact">Add Contact</button>
					<button type="button" id="messaging_edit_contact">Edit Contact</button>
					<button type="button" id="messaging_delete_contact">Delete Contact</button>
				</div>
			</div>	
		</div>
	</div>
</div>
<div id="fax_import_dialog" title="Import Fax">
	<div id="fax_import_message"></div><br>
	<div id="fax_import_fieldset">		
		<form name="fax_import_form" id="fax_import_form">
			<input type="hidden" name="received_id" id="fax_received_id"/>
			<input type="hidden" name="pid" id="fax_pid"/>
			<input type="hidden" name="fax_import_pages" id="fax_import_pages"/>
			<table>
				<tbody>
					<tr>
						<td><label for="fax_import_patient_search">Choose Patient</label></td>
						<td><input type="text" name="patient_search" id="fax_patient_search" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
					</tr>
					<tr>
						<td><label for="fax_import_documents_from">From</label></td>
						<td><input type="text" name="documents_from" id="fax_import_documents_from" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
					</tr>
					<tr>
						<td><label for="fax_import_documents_type">Document Type</label></td>
						<td><select name="documents_type" id="fax_import_documents_type" class="text ui-widget-content ui-corner-all"></select></td>
					</tr>
					<tr>
						<td><label for="fax_import_documents_desc">Description</label></td>
						<td><input type="text" name="documents_desc" id="fax_import_documents_desc" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
					</tr>
					<tr>
						<td><label for="fax_import_document_date">Document Date</label></td>
						<td><input type="text" name="documents_date" id="fax_import_documents_date" class="text ui-widget-content ui-corner-all" /></td>
					</tr>
				</tbody>
			</table>
			<hr class="ui-state-default"/>
			<button type="button" id="save_fax_import">Save</button>
			<button type="button" id="cancel_fax_import">Cancel</button>
		</form>
	</div>
</div>
<div id="scan_import_dialog" title="Import Scanned Document">
	<div id="scan_import_message"></div><br>
	<div id="scan_import_fieldset">		
		<form name="scan_import_form" id="scan_import_form">
			<input type="hidden" name="scans_id" id="scan_scans_id"/>
			<input type="hidden" name="pid" id="scan_pid"/>
			<input type="hidden" name="scan_import_pages" id="scan_import_pages"/>
			<table>
				<tbody>
					<tr>
						<td><label for="scan_import_patient_search">Choose Patient</label></td>
						<td><input type="text" name="patient_search" id="scan_patient_search" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
					</tr>
					<tr>
						<td><label for="scan_import_documents_from">From</label></td>
						<td><input type="text" name="documents_from" id="scan_import_documents_from" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
					</tr>
					<tr>
						<td><label for="scan_import_documents_type">Document Type</label></td>
						<td><select name="documents_type" id="scan_import_documents_type" class="text ui-widget-content ui-corner-all"></select></td>
					</tr>
					<tr>
						<td><label for="scan_import_documents_desc">Description</label></td>
						<td><input type="text" name="documents_desc" id="scan_import_documents_desc" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
					</tr>
					<tr>
						<td><label for="scan_import_document_date">Document Date</label></td>
						<td><input type="text" name="documents_date" id="scan_import_documents_date" class="text ui-widget-content ui-corner-all" /></td>
					</tr>
				</tbody>
			</table>
			<hr class="ui-state-default"/>
			<button type="button" id="save_scan_import">Save</button>
			<button type="button" id="cancel_scan_import">Cancel</button>
		</form>
	</div>
</div>
<div id="contacts_dialog" title="Add/Edit Entry for Address Book">
	<form id="messaging_contact_form">
		<input type="hidden" name="address_id" id="messaging_address_id"/>
		<table>
			<tr>
				<td>Last Name:<br><input type="text" name="lastname" id="messaging_lastname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td colspan="2">First Name:<br><input type="text" name="firstname" id="messaging_firstname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>	
				<td>Prefix:<br><input type="text" name="prefix" id="messaging_prefix" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td>Suffix:<br><input type="text" name="suffix" id="messaging_suffix" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td align="left">Specialty:<br><input type="text" name="specialty" id="messaging_specialty" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>	
				<td>Facility:<br><input type="text" name="facility" id="messaging_facility" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td colspan="2">E-mail:<br><input type="text" name="email" id="messaging_email" style="width:446px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td colspan="3">Address:<br><input type="text" name="street_address1" id="messaging_address" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
			
			</tr>
			<tr>
				<td colspan="3">Address2:<br><input type="text" name="street_address2" id="messaging_address2" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>City:<br><input type="text" name="city" id="messaging_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td>State:<br><select name="state" id="messaging_state" class="text ui-widget-content ui-corner-all"></td>
				<td>Zip:<br><input type="text" name="zip" id="messaging_zip" style="width:100px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Phone:<br><input type="text" name="phone" id="messaging_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td>Fax:<br><input type="text" name="fax" id="messaging_fax" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td>NPI:<br><input type="text" name="npi" id="messaging_npi" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td colspan="3">Comments:<br><input type="text" name="comments" id="messaging_comments" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
		</table>
		<hr class="ui-state-default"/>
		<button type="button" id="messaging_save_contact">Add and Save Contact to Address Book</button>
		<button type="button" id="messaging_cancel_contact">Cancel</button>
	</form>
</div>
<div id="fax_view_dialog" title="Documents Viewer">
	<input type="hidden" id="view_received_id"/>
	<input type="hidden" id="fax_filepath"/>
	<table>
		<tr>
			<td>
				<button type="button" id="save_fax">Download</button>
				<button type="button" id="import_fax">Import</button>
			</td>
			<td align="right">
				Select Pages (leave blank for all): <input type="text" style="width:100px" id="import_fax_pages"/>
			</td>
		</tr>
	</table>
	<div id="embedURL1"></div>
</div>
<div id="pages_view_dialog" title="Pages Viewer">
	<div id="embedURL2"></div>
</div>
<div id="scan_view_dialog" title="Documents Viewer">
	<input type="hidden" id="view_scans_id"/>
	<input type="hidden" id="scan_filepath"/>
	<table>
		<tr>
			<td>
				<button type="button" id="save_scan">Download</button>
				<button type="button" id="import_scan">Import</button>
			</td>
			<td align="right">
				Select Pages (leave blank for all): <input type="text" style="width:100px" id="import_scan_pages"/>
			</td>
		</tr>
	</table>
	<div id="embedURL3"></div>
</div>
