<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load('<?php echo site_url("search/loadpage");?>');
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("patient/messaging");?>',
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
 		$.ajax({
			url: "<?php echo site_url('patient/messaging/get_displayname2');?>",
			dataType: "json",
			type: "POST",
			success: function(data){
				$("#messages_pid").val(data.id);
				$("#messages_patient").val(data.value);
			}
		});
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
		url:"<?php echo site_url('patient/messaging/internal_inbox/');?>",
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
		 		setTimeout(function() {
					var a = $("#internal_messages_dialog" ).dialog("isOpen");
					if (a) {
						var id = $("#message_view_message_id").val();
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('patient/messaging/read_message');?>/" + id,
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
						url: "<?php echo site_url('patient/messaging/delete_message');?>",
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
		url:"<?php echo site_url('patient/messaging/internal_draft/');?>",
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
		 		var a = jQuery("#internal_draft").getCell(id,"message_to");
				var a_array = String(a).split(";");
				var a_length = a_array.length;
				for (var i = 0; i < a_length; i++) {
					$("#messages_to").selectOptions(a_array[i]);
				}
				$("#messages_to").trigger("liszt:updated");
				var b = jQuery("#internal_draft").getCell(id,"cc");
				var b_array = String(b).split(";");
				var b_length = b_array.length;
				for (var j = 0; j < b_length; j++) {
					$("#messages_cc").selectOptions(b_array[j]);
				}
				$("#messages_cc").trigger("liszt:updated");
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
						url: "<?php echo site_url('patient/messaging/delete_message');?>",
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
		url:"<?php echo site_url('patient/messaging/internal_outbox/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','To','CC','Date','Subject','Message'],
		colModel:[
			{name:'message_id',index:'message_id',width:1,hidden:true},
			{name:'date',index:'date',width:120},
			{name:'message_to',index:'message_to',width:90},
			{name:'cc',index:'cc',width:90},
			{name:'subject',index:'subject',width:250},
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
				$("#internal_messages_form_id").hide('fast');
		 		$("#message_view_wrapper2").show('fast');
		 		$("#message_view_wrapper").hide('fast');
		 		$("#internal_messages_dialog").dialog('open');
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
	$("#messages_to").chosen();
	$("#messages_cc").chosen();
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('search/all_users2');?>",
		dataType: "json",
		success: function(data){
			$("#messages_to").addOption(data, false).trigger("liszt:updated");
			$("#messages_cc").addOption(data, false).trigger("liszt:updated");
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
					url: "<?php echo site_url('patient/messaging/send_message/');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$("#internal_messages_form_id").clearForm();
						$("#messages_to").trigger("liszt:updated");
						$("#messages_cc").trigger("liszt:updated");
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
				url: "<?php echo site_url('patient/messaging/draft_message/');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$("#internal_messages_form_id").clearForm();
					$("#messages_to").trigger("liszt:updated");
					$("#messages_cc").trigger("liszt:updated");
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
				url: "<?php echo site_url('patient/messaging/delete_message/');?>",
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
			url: "<?php echo site_url('patient/messaging/get_displayname');?>",
			data: "id=" + to,
			success: function(data){
				$("#messages_to").val(data);
				$("#messages_to").trigger("liszt:updated");
			}
		});
		var from = $("#message_view_from").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('patient/messaging/get_displayname');?>",
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
				url: "<?php echo site_url('patient/messaging/get_displayname');?>",
				data: "id=" + to,
				success: function(data){
					$("#messages_to").val(data);
					$("#messages_to").trigger("liszt:updated");
				}
			});
		} else {
			var to1 = to + ';' + cc;
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/messaging/get_displayname1');?>",
				data: "id=" + to1,
				success: function(data){
					var a_array = String(data).split(";");
					var a_length = a_array.length;
					for (var i = 0; i < a_length; i++) {
						$("#messages_to").selectOptions(a_array[i]);
					}
					$("#messages_to").trigger("liszt:updated");
				}
			});
		}
		var from = $("#message_view_from").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('patient/messaging/get_displayname');?>",
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
});
</script>
<div id="heading2"></div>
<div id ="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="maincontent_full" style="overflow:hidden">
		<h4>NOSH ChartingSystem Message Center</h4>
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
				<input type="hidden" name="patient_name" id="messages_patient">
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
							<td>To:<br>
							<select name="message_to[]" id="messages_to" multiple="multiple" style="width:400px" class="multiselect"></select>
						</tr>
						<tr>
							<td>CC:<br>
							<select name="cc[]" id="messages_cc" multiple="multiple" style="width:400px" class="multiselect"></select>
						</tr>
						<tr>
							<td>Message:<br>
							<textarea name="body" id="messages_body" rows="12" style="width:400px" class="text ui-widget-content ui-corner-all"></textarea></td>
						</tr>
					</tbody>
				</table>
			</form>
			<div id="message_view_wrapper" style="display:none;font-size: 0.9em">
				<button type="button" id="reply_message">Reply</button>
				<button type="button" id="reply_all_message">Reply All</button>
				<button type="button" id="forward_message">Forward</button><br>
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
			<div id="message_view_wrapper2" style="display:none;font-size: 0.9em">
				<div id="message_view2"></div>
			</div>
		</div>
	</div>
</div>
