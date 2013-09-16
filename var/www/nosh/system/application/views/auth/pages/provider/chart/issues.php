<div id="issues_list_dialog" title="Issues">
	<div id="issues_pmh_header" style="display:none">
		<button type="button" id="copy_oh_pmh_all_issues">Copy All Active Issues</button>
		<button type="button" id="copy_oh_pmh_one_issue">Copy Issue</button>
		<hr class="ui-state-default"/>
	</div>
	<div id="issues_psh_header" style="display:none">
		<button type="button" id="copy_oh_psh_all_issues">Copy All Active Issues</button>
		<button type="button" id="copy_oh_psh_one_issue">Copy Issue</button>
		<hr class="ui-state-default"/>
	</div>
	<div id="issues_lab_header" style="display:none">
		<button type="button" id="copy_lab_all_issues">Copy All Active Issues</button>
		<button type="button" id="copy_lab_one_issue">Copy Issue</button>
		<hr class="ui-state-default"/>
	</div>
	<div id="issues_rad_header" style="display:none">
		<button type="button" id="copy_rad_all_issues">Copy All Active Issues</button>
		<button type="button" id="copy_rad_one_issue">Copy Issue</button>
		<hr class="ui-state-default"/>
	</div>
	<div id="issues_cp_header" style="display:none">
		<button type="button" id="copy_cp_all_issues">Copy All Active Issues</button> 
		<button type="button" id="copy_cp_one_issue">Copy Issue</button>
		<hr class="ui-state-default"/>
	</div>
	<div id="issues_ref_header" style="display:none">
		<button type="button" id="copy_ref_all_issues">Copy All Active Issues</button> 
		<button type="button" id="copy_ref_one_issue">Copy Issue</button>
		<hr class="ui-state-default"/>
	</div>
	<div id="issues_assessment_header" style="display:none">
		Select Issue as Diagnosis:<br>
		<button type="button" id="copy_assessment_issue_1">#1</button> 
		<button type="button" id="copy_assessment_issue_2">#2</button>
		<button type="button" id="copy_assessment_issue_3">#3</button>
		<button type="button" id="copy_assessment_issue_4">#4</button>
		<button type="button" id="copy_assessment_issue_5">#5</button> 
		<button type="button" id="copy_assessment_issue_6">#6</button>
		<button type="button" id="copy_assessment_issue_7">#7</button>
		<button type="button" id="copy_assessment_issue_8">#8</button>
		<button type="button" id="copy_assessment_issue_9">Additional Diagnosis</button>
		<button type="button" id="copy_assessment_issue_10">Differential Diagnosis</button>
		<hr class="ui-state-default"/>
	</div>
	<table id="issues" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="issues_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="add_issue">Add Issue</button>
	<button type="button" id="edit_issue">Edit Issue</button>
	<button type="button" id="inactivate_issue">Inactivate Issue</button>
	<button type="button" id="delete_issue">Delete Issue</button><br><br>
	<form name="edit_issue_form" id="edit_issue_form" style="display: none">
		<input type="hidden" name="issue_id" id="issue_id"/>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>Issue</legend>
			<table>
				<tbody>
					<tr>
						<td>Issue:</td>
						<td><input type="text" name="issue" id="issue" style="width:500px" class="text ui-widget-content ui-corner-all" placeholder="Use a comma to separate distinct search terms."/></td>
					</tr>
					<tr>
						<td>Date Active:</td>
						<td><input type="text" name="issue_date_active" id="issue_date_active" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<button type="button" id="save_issue">Save</button>
							<button type="button" id="cancel_issue">Cancel</button>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset><br>
	</form>
	<table id="issues_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="issues_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="reactivate_issue">Reactivate Issue</button><br><br>
</div>
<script type="text/javascript">
	$("#issues_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event,ui) {
			jQuery("#issues").jqGrid('GridUnload');
			jQuery("#issues").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/issues/');?>",
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
				url:"<?php echo site_url('provider/chartmenu/issues_inactive/');?>",
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
		},
		close: function(event, ui) {
			$('#edit_issue_form').clearForm();
			$('#issues_pmh_header').hide('fast');
			$('#issues_psh_header').hide('fast');
		}
	});
	$("#issues_list").click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').hide('fast');
		$('#issues_psh_header').hide('fast');
		$('#issues_assessment_header').hide('fast');
		$('#edit_issue_form').hide('fast');
	});
	$("#issue_date_active").mask("99/99/9999");
	$("#issue_date_active").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	var issue_cache = {};
	$("#issue").autocomplete({
		source: function (req, add){
			if (req.term in issue_cache){
				add(issue_cache[req.term]);
				return;
			}
			$.ajax({
				url: "<?php echo site_url('search/icd9');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						issue_cache[req.term] = data.message;
						add(data.message);
					}				
				}
			});
		},
		minLength: 3
	});
	
	$("#add_issue").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#add_issue").click(function(){
		$('#edit_issue_form').clearForm();
		var currentDate = getCurrentDate();
		$('#issue_date_active').val(currentDate);
		$('#edit_issue_form').show('fast');
		$("#issue").focus();
	});
	$("#edit_issue").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#edit_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			jQuery("#issues").GridToForm(item,"#edit_issue_form");
			var date = $('#issue_date_active').val();
			var edit_date = editDate(date);
			$('#issue_date_active').val(edit_date);
			$('#edit_issue_form').show('fast');
		} else {
			$.jGrowl("Please select issue to edit!")
		}
	});
	$("#inactivate_issue").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	$("#inactivate_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/inactivate_issue');?>",
				data: "issue_id=" + item,
				success: function(data){
					if (data == 'Close Chart') {
						window.location = "<?php echo site_url();?>";
					} else {
						$.jGrowl(data);
						jQuery("#issues").trigger("reloadGrid");
						jQuery("#issues_inactive").trigger("reloadGrid");
					}
				}
			});
		} else {
			$.jGrowl("Please select issue to inactivate!")
		}
	});
	$("#delete_issue").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#delete_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this issue?')){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/delete_issue');?>",
					data: "issue_id=" + item,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							jQuery("#issues").trigger("reloadGrid");
						}
					}
				});
			}
		} else {
			$.jGrowl("Please select issue to delete!")
		}
	});
	$("#reactivate_issue").button({
		icons: {
			primary: "ui-icon-arrowreturnthick-1-w"
		}
	});
	$("#reactivate_issue").click(function(){
		var item = jQuery("#issues_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/reactivate_issue');?>",
				data: "issue_id=" + item,
				success: function(data){
					if (data == 'Close Chart') {
						window.location = "<?php echo site_url();?>";
					} else {
						$.jGrowl(data);
						jQuery("#issues_inactive").trigger("reloadGrid");
						jQuery("#issues").trigger("reloadGrid");
					}
				}
			});
		} else {
			$.jGrowl("Please select issue to inactivate!")
		}
	});
	$("#save_issue").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_issue").click(function(){
		var issue = $("#issue");
		var bValid = true;
		bValid = bValid && checkEmpty(issue,"Issue");
		if (bValid) {
			var str = $("#edit_issue_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/edit_issue');?>",
					data: str,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							jQuery("#issues").trigger("reloadGrid");
							$('#edit_issue_form').clearForm();
							$('#edit_issue_form').hide('fast');
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_issue").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_issue").click(function(){
		$('#edit_issue_form').clearForm();
		$('#edit_issue_form').hide('fast');
	});
	$("#copy_oh_pmh_all_issues").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_oh_pmh_all_issues").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/copy_issues');?>",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var old = $("#oh_pmh").val();
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
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issues = issues1.slice(0, len1);
					$("#oh_pmh").val(old1+issues);
					$.jGrowl('All active issues copied!');
					$('#issues_pmh_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_oh_pmh_one_issue").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_oh_pmh_one_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var old = $("#oh_pmh").val();
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
			$("#oh_pmh").val(old1+issue);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_oh_psh_all_issues").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_oh_psh_all_issues").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/copy_issues');?>",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var old = $("#oh_psh").val();
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
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issues = issues1.slice(0, len1);
					$("#oh_psh").val(old1+issues);
					$.jGrowl('All active issues copied!');
					$('#issues_psh_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_oh_psh_one_issue").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_oh_psh_one_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var old = $("#oh_psh").val();
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
			$("#oh_psh").val(old1+issue);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_lab_all_issues").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_lab_all_issues").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/copy_issues');?>",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var terms = split($("#messages_lab_codes").val());
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issue = issues1.slice(0, len1);
					terms.pop();
					terms.push( issue );
					terms.push( "" );
					var new_terms = terms.join( "\n" );
					$("#messages_lab_codes").val(new_terms);
					$.jGrowl('All active issues copied!');
					$('#issues_lab_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_lab_one_issue").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_lab_one_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var terms = split($("#messages_lab_codes").val());
			terms.pop();
			terms.push( issue );
			terms.push( "" );
			var new_terms = terms.join( "\n" );
			$("#messages_lab_codes").val(new_terms);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_rad_all_issues").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_rad_all_issues").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/copy_issues');?>",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var terms = split($("#messages_rad_codes").val());
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issue = issues1.slice(0, len1);
					terms.pop();
					terms.push( issue );
					terms.push( "" );
					var new_terms = terms.join( "\n" );
					$("#messages_rad_codes").val(new_terms);
					$.jGrowl('All active issues copied!');
					$('#issues_rad_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_rad_one_issue").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_rad_one_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var terms = split($("#messages_rad_codes").val());
			terms.pop();
			terms.push( issue );
			terms.push( "" );
			var new_terms = terms.join( "\n" );
			$("#messages_rad_codes").val(new_terms);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_cp_all_issues").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_cp_all_issues").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/copy_issues');?>",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var terms = split($("#messages_cp_codes").val());
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issue = issues1.slice(0, len1);
					terms.pop();
					terms.push( issue );
					terms.push( "" );
					var new_terms = terms.join( "\n" );
					$("#messages_cp_codes").val(new_terms);
					$.jGrowl('All active issues copied!');
					$('#issues_cp_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_cp_one_issue").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_cp_one_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var terms = split($("#messages_cp_codes").val());
			terms.pop();
			terms.push( issue );
			terms.push( "" );
			var new_terms = terms.join( "\n" );
			$("#messages_cp_codes").val(new_terms);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_ref_all_issues").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_ref_all_issues").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/copy_issues');?>",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var terms = split($("#messages_ref_codes").val());
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issue = issues1.slice(0, len1);
					terms.pop();
					terms.push( issue );
					terms.push( "" );
					var new_terms = terms.join( "\n" );
					$("#messages_ref_codes").val(new_terms);
					$.jGrowl('All active issues copied!');
					$('#issues_ref_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_ref_one_issue").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_ref_one_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var terms = split($("#messages_ref_codes").val());
			terms.pop();
			terms.push( issue );
			terms.push( "" );
			var new_terms = terms.join( "\n" );
			$("#messages_ref_codes").val(new_terms);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_assessment_issue_1").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_assessment_issue_1").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var pos = issue.indexOf('[');
			if (pos == -1) {
				$.jGrowl('Please select issue to copy!');
			} else {
				var icd1 = issue.slice(pos);
				var icd2 = icd1.replace("[", "");
				var icd = icd2.replace("]", "");
				$("#assessment_icd1").val(icd);
				$("#assessment_1").val(issue);
				var label = '<strong>Diagnosis #1:</strong> ' + issue;
				$("#assessment_icd1_div").html(label);
				$("#assessment_icd1_div_button").show('fast');
				$.jGrowl('Issue copied to Diagnosis #1!');
			}
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_assessment_issue_2").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_assessment_issue_2").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var pos = issue.indexOf('[');
			if (pos == -1) {
				$.jGrowl('Please select issue to copy!');
			} else {
				var icd1 = issue.slice(pos);
				var icd2 = icd1.replace("[", "");
				var icd = icd2.replace("]", "");
				$("#assessment_icd2").val(icd);
				$("#assessment_2").val(issue);
				var label = '<strong>Diagnosis #2:</strong> ' + issue;
				$("#assessment_icd2_div").html(label);
				$("#assessment_icd2_div_button").show('fast');
				$.jGrowl('Issue copied to Diagnosis #2!');
			}
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_assessment_issue_3").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_assessment_issue_3").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var pos = issue.indexOf('[');
			if (pos == -1) {
				$.jGrowl('Please select issue to copy!');
			} else {
				var icd1 = issue.slice(pos);
				var icd2 = icd1.replace("[", "");
				var icd = icd2.replace("]", "");
				$("#assessment_icd3").val(icd);
				$("#assessment_3").val(issue);
				var label = '<strong>Diagnosis #3:</strong> ' + issue;
				$("#assessment_icd3_div").html(label);
				$("#assessment_icd3_div_button").show('fast');
				$.jGrowl('Issue copied to Diagnosis #3!');
			}
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_assessment_issue_4").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_assessment_issue_4").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var pos = issue.indexOf('[');
			if (pos == -1) {
				$.jGrowl('Please select issue to copy!');
			} else {
				var icd1 = issue.slice(pos);
				var icd2 = icd1.replace("[", "");
				var icd = icd2.replace("]", "");
				$("#assessment_icd4").val(icd);
				$("#assessment_4").val(issue);
				var label = '<strong>Diagnosis #4:</strong> ' + issue;
				$("#assessment_icd4_div").html(label);
				$("#assessment_icd4_div_button").show('fast');
				$.jGrowl('Issue copied to Diagnosis #4!');
			}
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_assessment_issue_5").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_assessment_issue_5").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var pos = issue.indexOf('[');
			if (pos == -1) {
				$.jGrowl('Please select issue to copy!');
			} else {
				var icd1 = issue.slice(pos);
				var icd2 = icd1.replace("[", "");
				var icd = icd2.replace("]", "");
				$("#assessment_icd5").val(icd);
				$("#assessment_5").val(issue);
				var label = '<strong>Diagnosis #5:</strong> ' + issue;
				$("#assessment_icd5_div").html(label);
				$("#assessment_icd5_div_button").show('fast');
				$.jGrowl('Issue copied to Diagnosis #5!');
			}
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_assessment_issue_6").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_assessment_issue_6").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var pos = issue.indexOf('[');
			if (pos == -1) {
				$.jGrowl('Please select issue to copy!');
			} else {
				var icd1 = issue.slice(pos);
				var icd2 = icd1.replace("[", "");
				var icd = icd2.replace("]", "");
				$("#assessment_icd6").val(icd);
				$("#assessment_6").val(issue);
				var label = '<strong>Diagnosis #6:</strong> ' + issue;
				$("#assessment_icd6_div").html(label);
				$("#assessment_icd6_div_button").show('fast');
				$.jGrowl('Issue copied to Diagnosis #6!');
			}
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_assessment_issue_7").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_assessment_issue_7").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var pos = issue.indexOf('[');
			if (pos == -1) {
				$.jGrowl('Please select issue to copy!');
			} else {
				var icd1 = issue.slice(pos);
				var icd2 = icd1.replace("[", "");
				var icd = icd2.replace("]", "");
				$("#assessment_icd7").val(icd);
				$("#assessment_7").val(issue);
				var label = '<strong>Diagnosis #7:</strong> ' + issue;
				$("#assessment_icd7_div").html(label);
				$("#assessment_icd7_div_button").show('fast');
				$.jGrowl('Issue copied to Diagnosis #7!');
			}
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_assessment_issue_8").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_assessment_issue_8").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var pos = issue.indexOf('[');
			if (pos == -1) {
				$.jGrowl('Please select issue to copy!');
			} else {
				var icd1 = issue.slice(pos);
				var icd2 = icd1.replace("[", "");
				var icd = icd2.replace("]", "");
				$("#assessment_icd8").val(icd);
				$("#assessment_8").val(issue);
				var label = '<strong>Diagnosis #8:</strong> ' + issue;
				$("#assessment_icd8_div").html(label);
				$("#assessment_icd8_div_button").show('fast');
				$.jGrowl('Issue copied to Diagnosis #8!');
			}
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_assessment_issue_9").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_assessment_issue_9").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var old = $("#assessment_other").val();
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
			$("#assessment_other").val(old1+issue);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_assessment_issue_10").button({
		icons: {
			primary: "ui-icon-arrowthickstop-1-s"
		}
	});
	$("#copy_assessment_issue_10").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var old = $("#assessment_ddx").val();
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
			$("#assessment_ddx").val(old1+issue);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	function split( val ) {
		return val.split( /\n\s*/ );
	}
</script>
