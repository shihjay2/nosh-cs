<form id="assessment_form">
	<button type="button" id="save_assessment">Save</button> 
	<button type="button" id="cancel_assessment">Cancel</button>
	<hr />
	<input type="hidden" name="assessment_icd1" id="assessment_icd1"/>
	<input type="hidden" name="assessment_icd2" id="assessment_icd2"/>
	<input type="hidden" name="assessment_icd3" id="assessment_icd3"/>
	<input type="hidden" name="assessment_icd4" id="assessment_icd4"/>
	<input type="hidden" name="assessment_icd5" id="assessment_icd5"/>
	<input type="hidden" name="assessment_icd6" id="assessment_icd6"/>
	<input type="hidden" name="assessment_icd7" id="assessment_icd7"/>
	<input type="hidden" name="assessment_icd8" id="assessment_icd8"/>
	<input type="hidden" name="assessment_1" id="assessment_1"/>
	<input type="hidden" name="assessment_2" id="assessment_2"/>
	<input type="hidden" name="assessment_3" id="assessment_3"/>
	<input type="hidden" name="assessment_4" id="assessment_4"/>
	<input type="hidden" name="assessment_5" id="assessment_5"/>
	<input type="hidden" name="assessment_6" id="assessment_6"/>
	<input type="hidden" name="assessment_7" id="assessment_7"/>
	<input type="hidden" name="assessment_8" id="assessment_8"/>
	<input type="hidden" name="assessment_icd1_old" id="assessment_icd1_old"/>
	<input type="hidden" name="assessment_icd2_old" id="assessment_icd2_old"/>
	<input type="hidden" name="assessment_icd3_old" id="assessment_icd3_old"/>
	<input type="hidden" name="assessment_icd4_old" id="assessment_icd4_old"/>
	<input type="hidden" name="assessment_icd5_old" id="assessment_icd5_old"/>
	<input type="hidden" name="assessment_icd6_old" id="assessment_icd6_old"/>
	<input type="hidden" name="assessment_icd7_old" id="assessment_icd7_old"/>
	<input type="hidden" name="assessment_icd8_old" id="assessment_icd8_old"/>
	<input type="hidden" name="assessment_1_old" id="assessment_1_old"/>
	<input type="hidden" name="assessment_2_old" id="assessment_2_old"/>
	<input type="hidden" name="assessment_3_old" id="assessment_3_old"/>
	<input type="hidden" name="assessment_4_old" id="assessment_4_old"/>
	<input type="hidden" name="assessment_5_old" id="assessment_5_old"/>
	<input type="hidden" name="assessment_6_old" id="assessment_6_old"/>
	<input type="hidden" name="assessment_7_old" id="assessment_7_old"/>
	<input type="hidden" name="assessment_8_old" id="assessment_8_old"/>
	<input type="hidden" name="assessment_other_old" id="assessment_other_old"/>
	<input type="hidden" name="assessment_ddx_old" id="assessment_ddx_old"/>
	<input type="hidden" name="assessment_notes_old" id="assessment_notes_old"/>
	<div id="assessment_icd1_div_button" style="display:none"><div id="assessment_icd1_div"></div> <button type="button" id="clear_icd1" style="font-size: 0.8em">Clear</button><br></div>
	<div id="assessment_icd2_div_button" style="display:none"><div id="assessment_icd2_div"></div> <button type="button" id="clear_icd2" style="font-size: 0.8em">Clear</button><br></div>
	<div id="assessment_icd3_div_button" style="display:none"><div id="assessment_icd3_div"></div> <button type="button" id="clear_icd3" style="font-size: 0.8em">Clear</button><br></div>
	<div id="assessment_icd4_div_button" style="display:none"><div id="assessment_icd4_div"></div> <button type="button" id="clear_icd4" style="font-size: 0.8em">Clear</button><br></div>
	<div id="assessment_icd5_div_button" style="display:none"><div id="assessment_icd5_div"></div> <button type="button" id="clear_icd5" style="font-size: 0.8em">Clear</button><br></div>
	<div id="assessment_icd6_div_button" style="display:none"><div id="assessment_icd6_div"></div> <button type="button" id="clear_icd6" style="font-size: 0.8em">Clear</button><br></div>
	<div id="assessment_icd7_div_button" style="display:none"><div id="assessment_icd7_div"></div> <button type="button" id="clear_icd7" style="font-size: 0.8em">Clear</button><br></div>
	<div id="assessment_icd8_div_button" style="display:none"><div id="assessment_icd8_div"></div> <button type="button" id="clear_icd8" style="font-size: 0.8em">Clear</button><br></div><br>
	<fieldset class="ui-state-default ui-corner-all">
		<legend>Assessment Chooser</legend>
		<input type="button" id="assessment_issues" value="Issues Helper" class="ui-button ui-state-default ui-corner-all"> OR<br><br>
		<table>
			<tbody>
				<tr>
					<td>
						Search ICD:<br>
						<input type="text" name="assessment_icd_search" id="assessment_icd_search" size="50" class="text ui-widget-content ui-corner-all"/><br><br>
						<table>
							<tr>
								<td>Select as: </td>
								<td>
									<button type="button" id="assessment_select_icd_1">Diagnosis #1</button>
									<button type="button" id="assessment_select_icd_2">Diagnosis #2</button>
									<button type="button" id="assessment_select_icd_3">Diagnosis #3</button>
									<button type="button" id="assessment_select_icd_4">Diagnosis #4</button>
									<button type="button" id="assessment_select_icd_5">Diagnosis #5</button>
									<button type="button" id="assessment_select_icd_6">Diagnosis #6</button>
									<button type="button" id="assessment_select_icd_7">Diagnosis #7</button>
									<button type="button" id="assessment_select_icd_8">Diagnosis #8</button><br>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type="button" id="assessment_select_icd_9" value="Additional Diagnosis" class="ui-button ui-state-default ui-corner-all"/>
									<input type="button" id="assessment_select_icd_10" value="Differential Diagnosis" class="ui-button ui-state-default ui-corner-all"/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</fieldset><br>
	<table>
		<tr>
			<td valign="top">Additional diagnoses:<br><input type="button" id="assessment_other_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"></td>
			<td valign="top"><textarea rows="4" style="width:500px" name="assessment_other" id="assessment_other" class="text ui-widget-content ui-corner-all"></textarea></td>
		</tr>
		<tr>
			<td valign="top">Differential diagnoses:<br><input type="button" id="assessment_ddx_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"></td>
			<td valign="top"><textarea rows="4" style="width:500px" name="assessment_ddx" id="assessment_ddx" class="text ui-widget-content ui-corner-all"></textarea></td>
		</tr>
		<tr>
			<td valign="top">Assessment discussion:<br><input type="button" id="assessment_notes_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"></td>
			<td valign="top"><textarea rows="4" style="width:500px" name="assessment_notes" id="assessment_notes" class="text ui-widget-content ui-corner-all"></textarea></td>
		</tr>
	</table>	
</form>
<script type="text/javascript">
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/get_assessment');?>",
		dataType: "json",
		success: function(data){
			if (data != '') {
				$("#assessment_icd1").val(data.assessment_icd1);
				$("#assessment_icd2").val(data.assessment_icd2);
				$("#assessment_icd3").val(data.assessment_icd3);
				$("#assessment_icd4").val(data.assessment_icd4);
				$("#assessment_icd5").val(data.assessment_icd5);
				$("#assessment_icd6").val(data.assessment_icd6);
				$("#assessment_icd7").val(data.assessment_icd7);
				$("#assessment_icd8").val(data.assessment_icd8);
				$("#assessment_1").val(data.assessment_1);
				$("#assessment_2").val(data.assessment_2);
				$("#assessment_3").val(data.assessment_3);
				$("#assessment_4").val(data.assessment_4);
				$("#assessment_5").val(data.assessment_5);
				$("#assessment_6").val(data.assessment_6);
				$("#assessment_7").val(data.assessment_7);
				$("#assessment_8").val(data.assessment_8);
				$("#assessment_icd1_old").val(data.assessment_icd1);
				$("#assessment_icd2_old").val(data.assessment_icd2);
				$("#assessment_icd3_old").val(data.assessment_icd3);
				$("#assessment_icd4_old").val(data.assessment_icd4);
				$("#assessment_icd5_old").val(data.assessment_icd5);
				$("#assessment_icd6_old").val(data.assessment_icd6);
				$("#assessment_icd7_old").val(data.assessment_icd7);
				$("#assessment_icd8_old").val(data.assessment_icd8);
				$("#assessment_1_old").val(data.assessment_1);
				$("#assessment_2_old").val(data.assessment_2);
				$("#assessment_3_old").val(data.assessment_3);
				$("#assessment_4_old").val(data.assessment_4);
				$("#assessment_5_old").val(data.assessment_5);
				$("#assessment_6_old").val(data.assessment_6);
				$("#assessment_7_old").val(data.assessment_7);
				$("#assessment_8_old").val(data.assessment_8);
				if(data.assessment_1.length!=0){
					var label1 = '<strong>Diagnosis #1:</strong> ' + data.assessment_1;
					$("#assessment_icd1_div").html(label1);
					$("#assessment_icd1_div_button").show('fast');
				}
				if(data.assessment_2.length!=0){
					var label2 = '<strong>Diagnosis #2:</strong> ' + data.assessment_2;
					$("#assessment_icd2_div").html(label2);
					$("#assessment_icd2_div_button").show('fast');
				}
				if(data.assessment_3.length!=0){
					var label3 = '<strong>Diagnosis #3:</strong> ' + data.assessment_3;
					$("#assessment_icd3_div").html(label3);
					$("#assessment_icd3_div_button").show('fast');
				}
				if(data.assessment_4.length!=0){
					var label4 = '<strong>Diagnosis #4:</strong> ' + data.assessment_4;
					$("#assessment_icd4_div").html(label4);
					$("#assessment_icd4_div_button").show('fast');
				}
				if(data.assessment_5.length!=0){
					var label5 = '<strong>Diagnosis #5:</strong> ' + data.assessment_5;
					$("#assessment_icd5_div").html(label5);
					$("#assessment_icd5_div_button").show('fast');
				}
				if(data.assessment_6.length!=0){
					var label6 = '<strong>Diagnosis #6:</strong> ' + data.assessment_6;
					$("#assessment_icd6_div").html(label6);
					$("#assessment_icd6_div_button").show('fast');
				}
				if(data.assessment_7.length!=0){
					var label7 = '<strong>Diagnosis #7:</strong> ' + data.assessment_7;
					$("#assessment_icd7_div").html(label7);
					$("#assessment_icd7_div_button").show('fast');
				}
				if(data.assessment_8.length!=0){
					var label8 = '<strong>Diagnosis #8:</strong> ' + data.assessment_8;
					$("#assessment_icd8_div").html(label8);
					$("#assessment_icd8_div_button").show('fast');
				}
				$("#assessment_other").val(data.assessment_other);
				$("#assessment_ddx").val(data.assessment_ddx);
				$("#assessment_notes").val(data.assessment_notes);
				$("#assessment_other_old").val(data.assessment_other);
				$("#assessment_ddx_old").val(data.assessment_ddx);
				$("#assessment_notes_old").val(data.assessment_notes);
			}
		}
	});
	$("#save_assessment").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#cancel_assessment").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#save_assessment").click(function(){
		var str = $("#assessment_form").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/assessment_save');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					var a = $("#assessment_icd1").val();
					var b = $("#assessment_icd2").val();
					var c = $("#assessment_icd3").val();
					var d = $("#assessment_icd4").val();
					var e = $("#assessment_icd5").val();
					var f = $("#assessment_icd6").val();
					var g = $("#assessment_icd7").val();
					var h = $("#assessment_icd8").val();
					var i = $("#assessment_1").val();
					var j = $("#assessment_2").val();
					var k = $("#assessment_3").val();
					var l = $("#assessment_4").val();
					var m = $("#assessment_5").val();
					var n = $("#assessment_6").val();
					var o = $("#assessment_7").val();
					var p = $("#assessment_8").val();
					var q = $("#assessment_other").val();
					var r = $("#assessment_ddx").val();
					var s = $("#assessment_notes").val();
					$("#assessment_icd1_old").val(a);
					$("#assessment_icd2_old").val(b);
					$("#assessment_icd3_old").val(c);
					$("#assessment_icd4_old").val(d);
					$("#assessment_icd5_old").val(e);
					$("#assessment_icd6_old").val(f);
					$("#assessment_icd7_old").val(g);
					$("#assessment_icd8_old").val(h);
					$("#assessment_1_old").val(i);
					$("#assessment_2_old").val(j);
					$("#assessment_3_old").val(k);
					$("#assessment_4_old").val(l);
					$("#assessment_5_old").val(m);
					$("#assessment_6_old").val(n);
					$("#assessment_7_old").val(o);
					$("#assessment_8_old").val(p);
					$("#assessment_other_old").val(q);
					$("#assessment_ddx_old").val(r);
					$("#assessment_notes_old").val(s);
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/get_billing');?>",
						dataType: "json",
						success: function(data){
							$("#billing_icd").removeOption(/./);
							$("#billing_icd").addOption(data, false);
						}
					});	
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#cancel_assessment").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_assessment');?>",
			dataType: "json",
			success: function(data){
				$("#assessment_icd1").val(data.assessment_icd1);
				$("#assessment_icd2").val(data.assessment_icd2);
				$("#assessment_icd3").val(data.assessment_icd3);
				$("#assessment_icd4").val(data.assessment_icd4);
				$("#assessment_icd5").val(data.assessment_icd5);
				$("#assessment_icd6").val(data.assessment_icd6);
				$("#assessment_icd7").val(data.assessment_icd7);
				$("#assessment_icd8").val(data.assessment_icd8);
				$("#assessment_1").val(data.assessment_1);
				$("#assessment_2").val(data.assessment_2);
				$("#assessment_3").val(data.assessment_3);
				$("#assessment_4").val(data.assessment_4);
				$("#assessment_5").val(data.assessment_5);
				$("#assessment_6").val(data.assessment_6);
				$("#assessment_7").val(data.assessment_7);
				$("#assessment_8").val(data.assessment_8);
				$("#assessment_icd1_old").val(data.assessment_icd1);
				$("#assessment_icd2_old").val(data.assessment_icd2);
				$("#assessment_icd3_old").val(data.assessment_icd3);
				$("#assessment_icd4_old").val(data.assessment_icd4);
				$("#assessment_icd5_old").val(data.assessment_icd5);
				$("#assessment_icd6_old").val(data.assessment_icd6);
				$("#assessment_icd7_old").val(data.assessment_icd7);
				$("#assessment_icd8_old").val(data.assessment_icd8);
				$("#assessment_1_old").val(data.assessment_1);
				$("#assessment_2_old").val(data.assessment_2);
				$("#assessment_3_old").val(data.assessment_3);
				$("#assessment_4_old").val(data.assessment_4);
				$("#assessment_5_old").val(data.assessment_5);
				$("#assessment_6_old").val(data.assessment_6);
				$("#assessment_7_old").val(data.assessment_7);
				$("#assessment_8_old").val(data.assessment_8);
				if(data.assessment_1.length!=0){
					var label1 = '<strong>Diagnosis #1:</strong> ' + data.assessment_1;
					$("#assessment_icd1_div").html(label1);
					$("#assessment_icd1_div_button").show('fast');
				}
				if(data.assessment_2.length!=0){
					var label2 = '<strong>Diagnosis #2:</strong> ' + data.assessment_2;
					$("#assessment_icd2_div").html(label2);
					$("#assessment_icd2_div_button").show('fast');
				}
				if(data.assessment_3.length!=0){
					var label3 = '<strong>Diagnosis #3:</strong> ' + data.assessment_3;
					$("#assessment_icd3_div").html(label3);
					$("#assessment_icd3_div_button").show('fast');
				}
				if(data.assessment_4.length!=0){
					var label4 = '<strong>Diagnosis #4:</strong> ' + data.assessment_4;
					$("#assessment_icd4_div").html(label4);
					$("#assessment_icd4_div_button").show('fast');
				}
				if(data.assessment_5.length!=0){
					var label5 = '<strong>Diagnosis #5:</strong> ' + data.assessment_5;
					$("#assessment_icd5_div").html(label5);
					$("#assessment_icd5_div_button").show('fast');
				}
				if(data.assessment_6.length!=0){
					var label6 = '<strong>Diagnosis #6:</strong> ' + data.assessment_6;
					$("#assessment_icd6_div").html(label6);
					$("#assessment_icd6_div_button").show('fast');
				}
				if(data.assessment_7.length!=0){
					var label7 = '<strong>Diagnosis #7:</strong> ' + data.assessment_7;
					$("#assessment_icd7_div").html(label7);
					$("#assessment_icd7_div_button").show('fast');
				}
				if(data.assessment_8.length!=0){
					var label8 = '<strong>Diagnosis #8:</strong> ' + data.assessment_8;
					$("#assessment_icd8_div").html(label8);
					$("#assessment_icd8_div_button").show('fast');
				}
				$("#assessment_other").val(data.assessment_other);
				$("#assessment_ddx").val(data.assessment_ddx);
				$("#assessment_notes").val(data.assessment_notes);
				$("#assessment_other_old").val(data.assessment_other);
				$("#assessment_ddx_old").val(data.assessment_ddx);
				$("#assessment_notes_old").val(data.assessment_notes);
			}
		});
	});
	$("#assessment_issues").button();
	$("#assessment_issues").click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').hide('fast');
		$('#issues_psh_header').hide('fast');
		$('#issues_lab_header').hide('fast');
		$('#issues_rad_header').hide('fast');
		$('#issues_cp_header').hide('fast');
		$('#issues_ref_header').hide('fast');
		$('#issues_assessment_header').show('fast');
		$('#edit_issue_form').hide('fast');
	});
	$("#assessment_icd_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/icd9');?>",
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
	$("#assessment_select_icd_1").button();
	$('#assessment_select_icd_1').click(function(){
		var issue = $("#assessment_icd_search").val();
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
	});
	$("#assessment_select_icd_2").button();
	$('#assessment_select_icd_2').click(function(){
		var issue = $("#assessment_icd_search").val();
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
	});
	$("#assessment_select_icd_3").button();
	$('#assessment_select_icd_3').click(function(){
		var issue = $("#assessment_icd_search").val();
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
	});
	$("#assessment_select_icd_4").button();
	$('#assessment_select_icd_4').click(function(){
		var issue = $("#assessment_icd_search").val();
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
	});
	$("#assessment_select_icd_5").button();
	$('#assessment_select_icd_5').click(function(){
		var issue = $("#assessment_icd_search").val();
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
	});
	$("#assessment_select_icd_6").button();
	$('#assessment_select_icd_6').click(function(){
		var issue = $("#assessment_icd_search").val();
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
	});
	$("#assessment_select_icd_7").button();
	$('#assessment_select_icd_7').click(function(){
		var issue = $("#assessment_icd_search").val();
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
	});
	$("#assessment_select_icd_8").button();
	$('#assessment_select_icd_8').click(function(){
		var issue = $("#assessment_icd_search").val();
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
	});
	$("#assessment_select_icd_9").button();
	$("#assessment_select_icd_9").click(function(){
		var issue = $("#assessment_icd_search").val();
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
	});
	$("#assessment_select_icd_10").button();
	$("#assessment_select_icd_10").click(function(){
		var issue = $("#assessment_icd_search").val();
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
	});
	$("#clear_icd1").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$('#clear_icd1').click(function(){
		$("#assessment_1").val('');
		$("#assessment_icd1").val('');
		$("#assessment_icd1_div").html('');
		$("#assessment_icd1_div_button").hide('fast');
	});
	$("#clear_icd2").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$('#clear_icd2').click(function(){
		$("#assessment_2").val('');
		$("#assessment_icd2").val('');
		$("#assessment_icd2_div").html('');
		$("#assessment_icd2_div_button").hide('fast');
	});
	$("#clear_icd3").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$('#clear_icd3').click(function(){
		$("#assessment_3").val('');
		$("#assessment_icd3").val('');
		$("#assessment_icd3_div").html('');
		$("#assessment_icd3_div_button").hide('fast');
	});
	$("#clear_icd4").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$('#clear_icd4').click(function(){
		$("#assessment_4").val('');
		$("#assessment_icd4").val('');
		$("#assessment_icd4_div").html('');
		$("#assessment_icd4_div_button").hide('fast');
	});
	$("#clear_icd5").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$('#clear_icd5').click(function(){
		$("#assessment_5").val('');
		$("#assessment_icd5").val('');
		$("#assessment_icd5_div").html('');
		$("#assessment_icd5_div_button").hide('fast');
	});
	$("#clear_icd6").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$('#clear_icd6').click(function(){
		$("#assessment_6").val('');
		$("#assessment_icd6").val('');
		$("#assessment_icd6_div").html('');
		$("#assessment_icd6_div_button").hide('fast');
	});
	$("#clear_icd7").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$('#clear_icd7').click(function(){
		$("#assessment_7").val('');
		$("#assessment_icd7").val('');
		$("#assessment_icd7_div").html('');
		$("#assessment_icd7_div_button").hide('fast');
	});
	$("#clear_icd8").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$('#clear_icd8').click(function(){
		$("#assessment_8").val('');
		$("#assessment_icd8").val('');
		$("#assessment_icd8_div").html('');
		$("#assessment_icd8_div_button").hide('fast');
	});
	$("#assessment_other_reset").button();
	$('#assessment_other_reset').click(function(){
		$("#assessment_other").val('');
	});
	$("#assessment_ddx_reset").button();
	$('#assessment_ddx_reset').click(function(){
		$("#assessment_ddx").val('');
	});
	$("#assessment_notes_reset").button();
	$('#assessment_notes_reset').click(function(){
		$("#assessment_notes").val('');
	});
</script>
