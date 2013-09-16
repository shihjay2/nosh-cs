<form id="oh_form">
	<button type="button" id="copy_oh">Copy From Most Recent Encounter</button>
	<input type="hidden" name="oh_pmh_old" id="oh_pmh_old"/>
	<input type="hidden" name="oh_psh_old" id="oh_psh_old"/>
	<input type="hidden" name="oh_fh_old" id="oh_fh_old"/>
	<hr class="ui-state-default"/>
	<table>
		<tr>
			<td valign="top">
				<table>
					<tr>
						<td>
							<button type="button" id="oh_pmh_reset">Clear</button><br>
							<button type="button" id="oh_pmh_issues">Issues</button><br>
						</td>
						<td>Past Medical History:<br><textarea style="width:300px" rows="4" name="oh_pmh" id="oh_pmh" class="text ui-widget-content ui-corner-all" placeholder="Type a few letters to search and select from ICD9 database."></textarea></td>
					</tr>
					<tr>
						<td>Patient Forms</td>
						<td><select id="oh_pmh_pf_template" class="text ui-widget-content ui-corner-all"></select></td>
					</tr>
					<tr>
						<td colspan="2"><hr class="ui-state-default"/></td>
					</tr>
					<tr>
						<td>
							<button type="button" id="oh_psh_reset">Clear</button><br>
							<button type="button" id="oh_psh_issues">Issues</button><br>
						</td>
						<td>Past Surgical History:<br><textarea style="width:300px" rows="4" name="oh_psh" id="oh_psh" class="text ui-widget-content ui-corner-all" placeholder="Type a few letters to search and select from ICD9 database."></textarea></td>
					</tr>
					<tr>
						<td>Patient Forms</td>
						<td><select id="oh_psh_pf_template" class="text ui-widget-content ui-corner-all"></select></td>
					</tr>
					<tr>
						<td colspan="2"><hr class="ui-state-default"/></td>
					</tr>
					<tr>
						<td>
							<button type="button" id="oh_fh_reset">Clear</button><br>
							<button type="button" id="oh_fh_icd">ICD</button>
						</td>
						<td>Family History:<br><textarea style="width:300px" rows="4" name="oh_fh" id="oh_fh" class="text ui-widget-content ui-corner-all"></textarea></td>
					</tr>
					<tr>
						<td>Patient Forms</td>
						<td><select id="oh_fh_pf_template" class="text ui-widget-content ui-corner-all"></select></td>
					</tr>
					<tr>
						<td><div id="fh_icd-div1" style="display:none">Family Member:</div></td>
						<td><div id="fh_icd-div2" style="display:none"><input type="text" id="fh_fm" style="width:220px" class="text ui-widget-content ui-corner-all"> 	WHO HAS</div></td>
					</tr>
					<tr>
						<td><div id="fh_icd-div3" style="display:none">Search ICD9:</div></td>
						<td><div id="fh_icd-div4" style="display:none"><input type="text" id="fh_icd" style="width:220px" class="text ui-widget-content ui-corner-all"> <button type="button" id="oh_fh_icd_select" style="font-size: 0.8em">Import</button></div></td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table>
					<tr>
						<td><div id="button_oh_sh_status" class="oh_tooltip"><?php echo $sh_status;?></div></td>
						<td><button type="button" id="button_oh_sh">Social History</button></td>
					</tr>
					<tr>
						<td><div id="button_oh_meds_status" class="oh_tooltip"><?php echo $meds_status;?></div></td>
						<td><button type="button" id="button_oh_meds">Medications</button></td>
					</tr>
					<tr>
						<td><div id="button_oh_supplements_status" class="oh_tooltip"><?php echo $supplements_status;?></div></td>
						<td><button type="button" id="button_oh_supplements">Supplements</button></td>
					</tr>
					<tr>
						<td><div id="button_oh_allergies_status" class="oh_tooltip"><?php echo $allergies_status;?></div></td>
						<td><button type="button" id="button_oh_allergies">Allergies</button></td>
					</tr>
					<tr>
						<td><div id="button_oh_etoh_status" class="oh_tooltip"><?php echo $etoh_status;?></div></td>
						<td><button type="button" id="button_oh_etoh">Alcohol Use</button></td>
					</tr>
					<tr>
						<td><div id="button_oh_tobacco_status" class="oh_tooltip"><?php echo $tobacco_status;?></div></td>
						<td><button type="button" id="button_oh_tobacco">Tobacco Use</button></td>
					</tr>
					<tr>
						<td><div id="button_oh_drugs_status" class="oh_tooltip"><?php echo $drugs_status;?></div></td>
						<td><button type="button" id="button_oh_drugs">Illicit Drug Use</button></td>
					</tr>
					<tr>
						<td><div id="button_oh_employment_status" class="oh_tooltip"><?php echo $employment_status;?></div></td>
						<td><button type="button" id="button_oh_employment">Employment</button></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form><br>
<div id="oh_sh_dialog" title="Social History">
	<button type="button" id="save_oh_sh">Save</button>
	<button type="button" id="cancel_oh_sh">Cancel</button>
	<button type="button" id="copy_oh_sh">Copy From Most Recent Encounter</button>
	<br><hr class="ui-state-default"/>
	<div style="display:block;float:left;width:310px">
		Preview:<br><textarea name="oh_sh" id="oh_sh" rows="10" style="width:290px" class="text ui-widget-content ui-corner-all"></textarea>
	</div>
	<div style="display:block;float:left;">
		Patient Forms: <select id="oh_sh_pf_template" class="text ui-widget-content ui-corner-all"></select><br><br>
		<button type="button" id="save_oh_sh_form">Save</button><button type="button" id="cancel_oh_sh_form">Cancel</button><button type="button" id="oh_sh_reset">Clear</button><br>
		<div id="oh_sh_form">
			Marital Status:<br><select name="marital_status" id="oh_sh_marital_status" style="width:164px" class="text ui-widget-content ui-corner-all"></select><input type="hidden" id="oh_sh_marital_status_old"><br><br>
			Spouse/Partner Name:<br><input type="text" name="partner_name" id="oh_sh_partner_name" style="width:164px" class="text ui-widget-content ui-corner-all"/><input type="hidden" id="oh_sh_partner_name_old"><br><br>
			Family members in the household:<br>
			<input type="text" name="sh1" id="sh1" style="width:270px" class="text ui-widget-content ui-corner-all"/><br><br>
			Children:<br>
			<input type="text" name="sh2" id="sh2" style="width:270px" class="text ui-widget-content ui-corner-all"/><br><br>
			Pets:<br>
			<input type="text" name="sh3" id="sh3" style="width:270px" class="text ui-widget-content ui-corner-all"/><br><br>
			Diet:<br>
			<input type="text" name="sh4" id="sh4" style="width:270px" class="text ui-widget-content ui-corner-all"/><br><br>
			Exercise:<br>
			<input type="text" name="sh5" id="sh5" style="width:270px" class="text ui-widget-content ui-corner-all"/><br><br>
			Sleep:<br>
			<input type="text" name="sh6" id="sh6" style="width:270px" class="text ui-widget-content ui-corner-all"/><br><br>
			Hobbies:<br>
			<input type="text" name="sh7" id="sh7" style="width:270px" class="text ui-widget-content ui-corner-all"/><br><br>
			<?php echo $childcare;?>
			Sexually Active:<br>
			<input name="sh9" id="sh9_n" value="Not sexually active." type="radio"> <label for="sh9_n">No</label><br><input name="sh9" id="sh9_y" value="Sexually active." type="radio"> <label for="sh9_y">Yes</label><br><br>
			Number of sexual partners<br>
			<input name="sh10" id="sh10_n" value="One current sexual partner." type="radio"> <label for="sh10_n">One</label><br><input name="sh10" id="sh10_y" value="Multiple current sexual partners." type="radio"> <label for="sh10_y">Multiple</label><br><br>
			Sex Partner Preference<br>
			<input name="sh11" id="sh11_1" value="Heterosexual." type="radio"> <label for="sh11_1">Heterosexual</label><br><input name="sh11" id="sh11_2" value="Homosexual." type="radio"> <label for="sh11_2">Homosexual</label><br><input name="sh11" id="sh11_3" value="Bisexual." type="radio"> <label for="sh11_3">Bisexual</label>
		</div>
	</div>
</div>
<div id="oh_etoh_dialog" title="Alcohol">
	<button type="button" id="save_oh_etoh">Save</button>
	<button type="button" id="cancel_oh_etoh">Cancel</button>
	<button type="button" id="copy_oh_etoh">Copy From Most Recent Encounter</button>
	<br><hr class="ui-state-default"/>
	<div style="display:block;float:left;width:310px">
		Preview:<br><textarea name="oh_etoh" id="oh_etoh" rows="10" style="width:290px" class="text ui-widget-content ui-corner-all"></textarea>
	</div>
	<div style="display:block;float:left;">
		<button type="button" id="save_oh_etoh_form">Save</button><button type="button" id="cancel_oh_etoh_form">Cancel</button><button type="button" id="oh_etoh_reset">Clear</button><br>
		<div id="oh_etoh_form">
			<table>
				<tr>
					<td>Alcohol Use:</td>
					<td><input name="oh_etoh_select" id="oh_etoh_n" value="No alcohol use." type="radio"> <label for="oh_etoh_n">No</label></td>
					<td><input name="oh_etoh_select" id="oh_etoh_y" value="Frequency of alcohol use: " type="radio"> <label for="oh_etoh_y">Yes</label></td>
				</tr>
			</table><br>
			<div id="oh_etoh_input" style="display:none">
				<table>
					<tr>
						<td>Frequency:</td>
						<td><input type="text" name="oh_etoh_text" id="oh_etoh_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
					</tr>
				</table><br>
			</div>
		</div>
	</div>
</div>
<div id="oh_tobacco_dialog" title="Tobacco">
	<button type="button" id="save_oh_tobacco">Save</button>
	<button type="button" id="cancel_oh_tobacco">Cancel</button>
	<button type="button" id="copy_oh_tobacco">Copy From Most Recent Encounter</button>
	<br><hr class="ui-state-default"/>
	<div style="display:block;float:left;width:310px">
		Preview:<br><textarea name="oh_tobacco" id="oh_tobacco" rows="10" style="width:290px" class="text ui-widget-content ui-corner-all"></textarea>
	</div>
	<div style="display:block;float:left;">
		<button type="button" id="save_oh_tobacco_form">Save</button><button type="button" id="cancel_oh_tobacco_form">Cancel</button><button type="button" id="oh_tobacco_reset">Clear</button><br>
		<div id="oh_tobacco_form">
			<table>
				<tr>
					<td>Tobacco Use:</td>
					<td><input name="oh_tobacco_select" id="oh_tobacco_n" value="No tobacco use." type="radio"> <label for="oh_tobacco_n">No</label></td>
					<td><input name="oh_tobacco_select" id="oh_tobacco_y" value="Frequency of tobacco use: " type="radio"> <label for="oh_tobacco_y">Yes</label></td>
				</tr>
			</table><br>
			<div id="oh_tobacco_input" style="display:none">
				<table>
					<tr>
						<td>Frequency:</td>
						<td><input type="text" name="oh_tobacco_text" id="oh_tobacco_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
					</tr>
				</table><br>
			</div>
		</div>
	</div>
</div>
<div id="oh_drugs_dialog" title="Drugs">
	<button type="button" id="save_oh_drugs">Save</button>
	<button type="button" id="cancel_oh_drugs">Cancel</button>
	<button type="button" id="copy_oh_drugs">Copy From Most Recent Encounter</button>
	<br><hr class="ui-state-default"/>
	<div style="display:block;float:left;width:310px">
		Preview:<br><textarea name="oh_drugs" id="oh_drugs" rows="10" style="width:290px" class="text ui-widget-content ui-corner-all"></textarea>
	</div>
	<div style="display:block;float:left;">
		<button type="button" id="save_oh_drugs_form">Save</button><button type="button" id="cancel_oh_drugs_form">Cancel</button><button type="button" id="oh_drugs_reset">Clear</button><br>
		<div id="oh_drugs_form">
			<table>
				<tr>
					<td>Drug Use:</td>
					<td><input name="oh_drugs_select" id="oh_drugs_n" value="No illicit drug use." type="radio"> <label for="oh_drugs_n">No</label></td>
					<td><input name="oh_drugs_select" id="oh_drugs_y"value="Type of drug use: " type="radio"> <label for="oh_drugs_y">Yes</label></td>
				</tr>
			</table><br>
			<div id="oh_drugs_input" style="display:none">
				<table>
					<tr>
						<td>Type:</td>
						<td><input type="text" name="oh_drugs_text" id="oh_drugs_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></td>
					</tr>
					<tr>
						<td>Frequency:</td>
						<td><input type="text" name="oh_drugs_text1" id="oh_drugs_text1" style="width:300px" class="text ui-widget-content ui-corner-all" /></td>
					</tr>
				</table><br>
			</div>
		</div>
	</div>
</div>
<div id="oh_employment_dialog" title="Employment">
	<button type="button" id="save_oh_employment">Save</button>
	<button type="button" id="cancel_oh_employment">Cancel</button>
	<button type="button" id="copy_oh_employment">Copy From Most Recent Encounter</button>
	<br><hr class="ui-state-default"/>
	<div style="display:block;float:left;width:310px">
		Preview:<br><textarea name="oh_employment" id="oh_employment" rows="10" style="width:290px" class="text ui-widget-content ui-corner-all"></textarea>
	</div>
	<div style="display:block;float:left;">
		<button type="button" id="save_oh_employment_form">Save</button><button type="button" id="cancel_oh_employment_form">Cancel</button><button type="button" id="oh_employment_reset">Clear</button><br>
		<div id="oh_employment_form">
			<table>
				<tr>
					<td valign="top">Employment Status:</td>
					<td><input name="oh_employment_select" id="oh_employment_1" value="Employed." type="radio"> <label for="oh_employment_1">Employed</label><br>
					<input name="oh_employment_select" id="oh_employment_2" value="Unemployed." type="radio"> <label for="oh_employment_2">Unemployed</label><br>
					<input name="oh_employment_select" id="oh_employment_3" value="Student." type="radio"> <label for="oh_employment_3">Student</label><br>
					<input name="oh_employment_select" id="oh_employment_4" value="Disabled." type="radio"> <label for="oh_employment_4">Disabled</label><br>
					<input name="oh_employment_select" id="oh_employment_5" value="Retired." type="radio"> <label for="oh_employment_5">Retired</label><br>
					<input name="oh_employment_select" id="oh_employment_6" value="Homemaker." type="radio"> <label for="oh_employment_6">Homemaker</label></td>
				</tr>
			</table><br>
			<div id="oh_employment_input" style="display:none">
				<table>
					<tr>
						<td>Employment Field:</td>
						<td><input type="text" name="oh_employment_text" id="oh_employment_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
					</tr>
					<tr>
						<td>Employer:</td>
						<td><input type="text" name="employer" id="oh_employment_employer" style="width:300px" class="text ui-widget-content ui-corner-all"/><input type="hidden" id="oh_employment_employer_old"></td>
					</tr>
				</table><br>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#copy_oh").button({icons: {primary: "ui-icon-clipboard"}}).click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/copy_oh/oh');?>",
			dataType: "json",
			success: function(data){
				if(data.callback == 'Items copied from last encounter!'){
					$.jGrowl(data.callback);
					$("#oh_pmh").val(data.oh_pmh);
					$("#oh_psh").val(data.oh_psh);
					$("#oh_fh").val(data.oh_fh);
				} else {
					$.jGrowl(data.callback);
					$("#oh_pmh").val('');
					$("#oh_psh").val('');
					$("#oh_fh").val('');
				}
			}
		});
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('assistant/encounters/get_oh');?>",
		dataType: "json",
		success: function(data){
			$("#oh_pmh").val(data.oh_pmh);
			$("#oh_psh").val(data.oh_psh);
			$("#oh_fh").val(data.oh_fh);
			$("#oh_pmh_old").val(data.oh_pmh);
			$("#oh_psh_old").val(data.oh_psh);
			$("#oh_fh_old").val(data.oh_fh);
		}
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('assistant/encounters/pf_template_select_list/PMH');?>",
		dataType: "json",
		success: function(data){
			$('#oh_pmh_pf_template').addOption(data.options);
			$('#oh_pmh_pf_template').sortOptions();
			$('#oh_pmh_pf_template').val("");
		}
	});
	$('#oh_pmh_pf_template').change(function(){
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_pf_template');?>" + "/" + a,
			success: function(data){
				var old = $("#oh_pmh").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#oh_pmh").val(b);
			}
		});
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('assistant/encounters/pf_template_select_list/PSH');?>",
		dataType: "json",
		success: function(data){
			$('#oh_psh_pf_template').addOption(data.options);
			$('#oh_psh_pf_template').sortOptions();
			$('#oh_psh_pf_template').val("");
		}
	});
	$('#oh_psh_pf_template').change(function(){
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_pf_template');?>" + "/" + a,
			success: function(data){
				var old = $("#oh_psh").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#oh_psh").val(b);
			}
		});
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('assistant/encounters/pf_template_select_list/FH');?>",
		dataType: "json",
		success: function(data){
			$('#oh_fh_pf_template').addOption(data.options);
			$('#oh_fh_pf_template').sortOptions();
			$('#oh_fh_pf_template').val("");
		}
	});
	$('#oh_fh_pf_template').change(function(){
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_pf_template');?>" + "/" + a,
			success: function(data){
				var old = $("#oh_fh").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#oh_fh").val(b);
			}
		});
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('assistant/encounters/pf_template_select_list/SH');?>",
		dataType: "json",
		success: function(data){
			$('#oh_sh_pf_template').addOption(data.options);
			$('#oh_sh_pf_template').sortOptions();
			$('#oh_sh_pf_template').val("");
		}
	});
	$('#oh_sh_pf_template').change(function(){
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_pf_template');?>" + "/" + a,
			success: function(data){
				var old = $("#oh_sh").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#oh_sh").val(b);
			}
		});
	});
	function split( val ) {
		return val.split( /\n\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}
	$("#oh_pmh").focus().autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/icd9');?>",
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
			this.value = terms.join( "\n" );
			return false;
		}
	});
	$('#oh_pmh_reset').button().click(function(){
		$("#oh_pmh").val('');
	});
	$('#oh_pmh_issues').button().click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').show('fast');
		$('#issues_psh_header').hide('fast');
		$('#issues_lab_header').hide('fast');
		$('#issues_rad_header').hide('fast');
		$('#issues_cp_header').hide('fast');
		$('#issues_ref_header').hide('fast');
		$('#issues_assessment_header').hide('fast');
		$('#edit_issue_form').hide('fast');
	});
	$('#oh_fh_icd').button().click(function(){
		if($("#fh_icd-div1").is(":hidden")) {
			$("#fh_icd-div1").show();
			$("#fh_icd-div2").show();
			$("#fh_icd-div3").show();
			$("#fh_icd-div4").show();
		} else {
			$("#fh_icd-div1").hide();
			$("#fh_icd-div2").hide();
			$("#fh_icd-div3").hide();
			$("#fh_icd-div4").hide();
		}
	});
	
	$('#oh_psh_reset').button().click(function(){
		$("#oh_psh").val('');
	});
	$('#oh_psh_issues').button().click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').hide('fast');
		$('#issues_psh_header').show('fast');
		$('#issues_lab_header').hide('fast');
		$('#issues_rad_header').hide('fast');
		$('#issues_cp_header').hide('fast');
		$('#issues_ref_header').hide('fast');
		$('#issues_assessment_header').hide('fast');
		$('#edit_issue_form').hide('fast');
	});
	$("#oh_psh").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/icd9');?>",
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
			this.value = terms.join( "\n" );
			return false;
		}
	});
	
	$('#oh_fh_reset').button().click(function(){
		$("#oh_fh").val('');
	});
	$("#fh_icd").autocomplete({
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
	$('#oh_fh_icd_select').button().click(function(){
		var icd = $("#fh_icd").val();
		var fm = $("#fh_fm").val();
		if (icd != '' || fh != '') {
			var old = $("#oh_fh").val();
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
			var full = icd + ' - ' + fm;
			$("#oh_fh").val(old1+full);
			$("#fh_icd").val('');
			$("#fh_fm").val('');
		} else {
			$.jGrowl("Empty field!  Try again.");
		};
	});

	function check_oh_status() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/check_oh');?>",
			dataType: "json",
			success: function(data){
				$.jGrowl(data.message);
				$('#button_oh_sh_status').html(data.sh_status);
				$('#button_oh_etoh_status').html(data.etoh_status);
				$('#button_oh_tobacco_status').html(data.tobacco_status);
				$('#button_oh_drugs_status').html(data.drugs_status);
				$('#button_oh_employment_status').html(data.employment_status);
				$('#button_oh_meds_status').html(data.meds_status);
				$('#button_oh_supplements_status').html(data.supplements_status);
				$('#button_oh_allergies_status').html(data.allergies_status);
			}
		});
	}
	
	$("#oh_sh_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#oh_sh").val();
			var b = $("#oh_sh_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_oh_status()
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#button_oh_sh").button();
	$("#button_oh_sh").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_oh');?>",
			dataType: "json",
			success: function(data){
				$('#oh_sh').val(data.oh_sh);
				$('#oh_sh_old').val(data.oh_sh);
			}
		});
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/chartmenu/demographics');?>",
			dataType: "json",
			success: function(data){
				$("#oh_sh_marital_status").val(data.marital_status);
				$("#oh_sh_partner_name").val(data.partner_name);
				$("#oh_sh_marital_status_old").val(data.marital_status);
				$("#oh_sh_partner_name_old").val(data.partner_name);
			}
		});
		$("#oh_sh_dialog").dialog('open');
		$("#oh_sh").focus();
	});
	$('.oh_tooltip').tooltip({
		items: ".oh_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		position: { my: "right+15 bottom", at: "left top", collision: "flipfit" },
		open: function(event, ui) {
			var elem = $(this);
			var id = $(this).attr("id");
			var parts = id.split('_');
			var id1 = parts[1] + "_" + parts[2];
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/tip_oh/');?>/" + id1,
				success: function(data) {
					elem.tooltip('option', 'content', data);
					elem.tooltip("option","position",{ my: "right+15 bottom", at: "left top", collision: "flipfit" });
				},
			});
		}
	});
	$("#save_oh_sh").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$('#save_oh_sh').click(function(){
		var a1 = $('#oh_sh').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/oh_save1/sh');?>",
			data: "oh_sh=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#oh_sh').val('');
				$('#oh_sh_old').val('');
				$('#oh_sh_form').clearDiv();
				$("#oh_sh_dialog").dialog('close');
				check_oh_status()
			}
		});
	});
	$("#cancel_oh_sh").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$('#cancel_oh_sh').click(function(){
		$('#oh_sh').val('');
		$('#oh_sh_old').val('');
		$("#oh_sh_dialog").dialog('close');
	});
	$("#copy_oh_sh").button({
		icons: {
			primary: "ui-icon-clipboard"
		}
	});
	$("#copy_oh_sh").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/copy_oh/sh');?>",
			dataType: "json",
			success: function(data){
				if(data.callback == 'Items copied from last encounter!'){
					$.jGrowl(data.callback);
					$("#oh_sh").val(data.oh_sh);
				} else {
					$.jGrowl(data.callback);
					$("#oh_sh").val('');
				}
			}
		});
	});
	$('#oh_sh_reset').button();
	$('#oh_sh_reset').click(function(){
		$("#oh_sh").val('');
	});
	$("#save_oh_sh_form").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$('#save_oh_sh_form').click(function(){
		var old = $("#oh_sh").val();
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
		var a = $("#sh1").val();
		var b = $("#sh2").val();
		var c = $("#sh3").val();
		var d = $("#oh_sh_marital_status").val();
		var d0 = $("#oh_sh_marital_status_old").val();
		var e = $("#oh_sh_partner_name").val();
		var e0 = $("#oh_sh_partner_name").val();
		var f = $("#sh4").val();
		var g = $("#sh5").val();
		var h = $("#sh6").val();
		var i = $("#sh7").val();
		var j = $("#sh8").val();
		var k = $("input[name='sh9']:checked").val();
		var l = $("input[name='sh10']:checked").val();
		var m = $("input[name='sh11']:checked").val();
		if(a){
			var a1 = 'Family members in the household: ' + a + '\n';
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'Children: ' + b + '\n';
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'Pets: ' + c + '\n';
		} else {
			var c1 = '';
		}
		if(d){
			var d1 = 'Marital status: ' + d + '\n';
		} else {
			var d1 = '';
		}
		if(e){
			var e1 = 'Partner name: ' + e + '\n';
		} else {
			var e1 = '';
		}
		if(f){
			var f1 = 'Diet: ' + f + '\n';
		} else {
			var f1 = '';
		}
		if(g){
			var g1 = 'Exercise: ' + g + '\n';
		} else {
			var g1 = '';
		}
		if(h){
			var h1 = 'Sleep: ' + h + '\n';
		} else {
			var h1 = '';
		}
		if(i){
			var i1 = 'Hobbies: ' + i + '\n';
		} else {
			var i1 = '';
		}
		if(j){
			var j1 = 'Child care arrangements: ' + j + '\n';
		} else {
			var j1 = '';
		}
		if(k){
			var k1 = k + '\n';
		} else {
			var k1 = '';
		}
		if(l){
			var l1 = l + '\n';
		} else {
			var l1 = '';
		}
		if(m){
			var m1 = m + '\n';
		} else {
			var m1 = '';
		}
		var full = d1+e1+a1+b1+c1+f1+g1+h1+i1+j1+k1+l1+m1;
		var len = full.length;
		var len1 = len - 1;
		var full1 = full.slice(0, len1);
		$("#oh_sh").val(old1+full1);
		if(d != d0 || e != e0) {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/edit_demographics');?>",
				data: "marital_status=" + d + "&partner_name=" + e,
				success: function(data){
					$.jGrowl(data);
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/chartmenu/demographics_list');?>",
						success: function(data){
							$('#demographics_tip').html(data);
						}
					});
				}
			});
		}
		var sh9_y = $('#sh9_y').attr('checked');
		var sh9_n = $('#sh9_n').attr('checked');
		if(sh9_y){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/edit_demographics3');?>",
				data: "status=yes",
				success: function(data){
					$.jGrowl(data);
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/chartmenu/demographics_list');?>",
						success: function(data){
							$('#demographics_tip').html(data);
						}
					});
				}
			});
		}
		if(sh9_n){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/edit_demographics3');?>",
				data: "status=no",
				success: function(data){
					$.jGrowl(data);
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/chartmenu/demographics_list');?>",
						success: function(data){
							$('#demographics_tip').html(data);
						}
					});
				}
			});
		}
	});
	$("#cancel_oh_sh_form").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$('#cancel_oh_sh_form').click(function(){
		$('#oh_sh_form').clearDiv();
	});
	$("#oh_sh_marital_status").addOption(marital, false);
	
	$("#button_oh_meds").button();
	$("#button_oh_meds").click(function() {
		$("#medications_list_dialog").dialog('open');
		$("#oh_meds_header").show('fast');
		$("#oh_meds").focus();
	});
	$('#save_oh_meds').click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/oh_save1/meds');?>",
			success: function(data){
				$.jGrowl(data);
				$("#oh_meds_header").hide('fast');
				$("#medications_list_dialog").dialog('close');
				check_oh_status()
			}
		});
	});
	$("#button_oh_supplements").button();
	$("#button_oh_supplements").click(function() {
		$("#supplements_list_dialog").dialog('open');
		$("#oh_supplements_header").show('fast');
		$("#oh_supplements").focus();
	});
	$("#button_oh_allergies").button();
	$("#button_oh_allergies").click(function() {
		$("#allergies_list_dialog").dialog('open');
		$("#save_oh_allergies").show('fast');
		$("#allergies_header").show('fast');
		$("#oh_allergies").focus();
	});
	$("#save_oh_allergies").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$('#save_oh_allergies').click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/oh_save1/allergies');?>",
			success: function(data){
				$.jGrowl(data);
				$("#save_oh_allergies").hide('fast');
				$("#allergies_header").hide('fast');
				$("#allergies_list_dialog").dialog('close');
				check_oh_status()
			}
		});
	});

	$("#oh_etoh_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#oh_etoh").val();
			var b = $("#oh_etoh_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_oh_status()
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#button_oh_etoh").button();
	$("#button_oh_etoh").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_oh');?>",
			dataType: "json",
			success: function(data){
				$('#oh_etoh').val(data.oh_etoh);
				$('#oh_etoh_old').val(data.oh_etoh);
			}
		});
		$("#oh_etoh_dialog").dialog('open');
		$("#oh_etoh").focus();
	});
	$("#save_oh_etoh").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$('#save_oh_etoh').click(function(){
		var a1 = $('#oh_etoh').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/oh_save1/etoh');?>",
			data: "oh_etoh=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#oh_etoh').val('');
				$('#oh_etoh_old').val('');
				$('#oh_etoh_form').clearDiv();
				$("#oh_etoh_dialog").dialog('close');
				check_oh_status()
			}
		});
	});
	$("#cancel_oh_etoh").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$('#cancel_oh_etoh').click(function(){
		$('#oh_etoh').val('');
		$('#oh_etoh_old').val('');
		$("#oh_etoh_dialog").dialog('close');
	});
	$("#copy_oh_etoh").button({
		icons: {
			primary: "ui-icon-clipboard"
		}
	});
	$("#copy_oh_etoh").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/copy_oh/etoh');?>",
			dataType: "json",
			success: function(data){
				if(data.callback == 'Items copied from last encounter!'){
					$.jGrowl(data.callback);
					$("#oh_etoh").val(data.oh_etoh);
				} else {
					$.jGrowl(data.callback);
					$("#oh_etoh").val('');
				}
			}
		});
	});
	$('#oh_etoh_reset').button();
	$('#oh_etoh_reset').click(function(){
		$("#oh_etoh").val('');
	});
	$("input[name='oh_etoh_select']").click(function(){
		var a = $('#oh_etoh_y').attr('checked');
		if(a){
			$('#oh_etoh_input').show('fast');
			$('#oh_etoh_text').focus();
		} else {
			$('#oh_etoh_input').hide('fast');
		}
	});
	$("#save_oh_etoh_form").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$('#save_oh_etoh_form').click(function(){
		var old = $("#oh_etoh").val();
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
		var a = $("input[name='oh_etoh_select']:checked").val();
		var a0 = $("#oh_etoh_text").val();
		if(a){
			var a1 = a + a0;
		} else {
			var a1 = '';
		}
		$("#oh_etoh").val(old1+a1);
	});
	$("#cancel_oh_etoh_form").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$('#cancel_oh_etoh_form').click(function(){
		$('#oh_etoh_form').clearDiv();
	});	
	
	$("#oh_tobacco_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#oh_tobacco").val();
			var b = $("#oh_tobacco_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_oh_status()
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#button_oh_tobacco").button();
	$("#button_oh_tobacco").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_oh');?>",
			dataType: "json",
			success: function(data){
				$('#oh_tobacco').val(data.oh_tobacco);
				$('#oh_tobacco_old').val(data.oh_tobacco);
			}
		});
		$("#oh_tobacco_dialog").dialog('open');
		$("#oh_tobacco").focus();
	});
	$('#save_oh_tobacco').click(function(){
		var a1 = $('#oh_tobacco').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/oh_save1/tobacco');?>",
			data: "oh_tobacco=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#oh_tobacco').val('');
				$('#oh_tobacco_old').val('');
				$('#oh_tobacco_form').clearDiv();
				$("#oh_tobacco_dialog").dialog('close');
				check_oh_status()
			}
		});
	});
	$('#cancel_oh_tobacco').click(function(){
		$('#oh_tobacco').val('');
		$('#oh_tobacco_old').val('');
		$("#oh_tobacco_dialog").dialog('close');
	});
	$("#copy_oh_tobacco").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/copy_oh/tobacco');?>",
			dataType: "json",
			success: function(data){
				if(data.callback == 'Items copied from last encounter!'){
					$.jGrowl(data.callback);
					$("#oh_tobacco").val(data.oh_tobacco);
				} else {
					$.jGrowl(data.callback);
					$("#oh_tobacco").val('');
				}
			}
		});
	});
	$('#oh_tobacco_reset').click(function(){
		$("#oh_tobacco").val('');
	});
	$("input[name='oh_tobacco_select']").click(function(){
		var a = $('#oh_tobacco_y').attr('checked');
		if(a){
			$('#oh_tobacco_input').show('fast');
			$('#oh_tobacco_text').focus();
		} else {
			$('#oh_tobacco_input').hide('fast');
		}
	});
	$('#save_oh_tobacco_form').click(function(){
		var old = $("#oh_tobacco").val();
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
		var a = $("input[name='oh_tobacco_select']:checked").val();
		var a0 = $("#oh_tobacco_text").val();
		if(a){
			var a1 = a + a0;
		} else {
			var a1 = '';
		}
		$("#oh_tobacco").val(old1+a1);
		var tobacco_y = $('#oh_tobacco_y').attr('checked');
		var tobacco_n = $('#oh_tobacco_n').attr('checked');
		if(tobacco_y){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/edit_demographics2');?>",
				data: "status=yes",
				success: function(data){
					$.jGrowl(data);
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/chartmenu/demographics_list');?>",
						success: function(data){
							$('#demographics_tip').html(data);
						}
					});
				}
			});
		}
		if(tobacco_n){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/edit_demographics2');?>",
				data: "status=no",
				success: function(data){
					$.jGrowl(data);
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/chartmenu/demographics_list');?>",
						success: function(data){
							$('#demographics_tip').html(data);
						}
					});
				}
			});
		}
	});
	$('#cancel_oh_tobacco_form').click(function(){
		$('#oh_tobacco_form').clearDiv();
	});
	$("#save_oh_tobacco").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#cancel_oh_tobacco").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#copy_oh_tobacco").button({
		icons: {
			primary: "ui-icon-clipboard"
		}
	});
	$('#oh_tobacco_reset').button();
	$("#save_oh_tobacco_form").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#cancel_oh_tobacco_form").button({
		icons: {
			primary: "ui-icon-close"
		}
	});

	$("#oh_drugs_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#oh_drugs").val();
			var b = $("#oh_drugs_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_oh_status()
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#button_oh_drugs").button();
	$("#button_oh_drugs").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_oh');?>",
			dataType: "json",
			success: function(data){
				$('#oh_drugs').val(data.oh_drugs);
				$('#oh_drugs_old').val(data.oh_drugs);
			}
		});
		$("#oh_drugs_dialog").dialog('open');
		$("#oh_drugs").focus();
	});
	$('#save_oh_drugs').click(function(){
		var a1 = $('#oh_drugs').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/oh_save1/drugs');?>",
			data: "oh_drugs=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#oh_drugs').val('');
				$('#oh_drugs_old').val('');
				$('#oh_drugs_form').clearDiv();
				$("#oh_drugs_dialog").dialog('close');
				check_oh_status()
			}
		});
	});
	$('#cancel_oh_drugs').click(function(){
		$('#oh_drugs').val('');
		$('#oh_drugs_old').val('');
		$("#oh_drugs_dialog").dialog('close');
	});
	$("#copy_oh_drugs").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/copy_oh/drugs');?>",
			dataType: "json",
			success: function(data){
				if(data.callback == 'Items copied from last encounter!'){
					$.jGrowl(data.callback);
					$("#oh_drugs").val(data.oh_drugs);
				} else {
					$.jGrowl(data.callback);
					$("#oh_drugs").val('');
				}
			}
		});
	});
	$('#oh_drugs_reset').click(function(){
		$("#oh_drugs").val('');
	});
	$("input[name='oh_drugs_select']").click(function(){
		var a = $('#oh_drugs_y').attr('checked');
		if(a){
			$('#oh_drugs_input').show('fast');
			$('#oh_drugs_text').focus();
		} else {
			$('#oh_drugs_input').hide('fast');
		}
	});
	$('#save_oh_drugs_form').click(function(){
		var old = $("#oh_drugs").val();
		if(old){
			var pos = old.lastIndexOf('\n');
			if (pos == -1) {
				var old1 = old + '\n\n';
			} else {
				var a = old.slice(pos);
				if (a == '') {
					var old1 = old + '\n';
				} else {
					var old1 = old + '\n\n';
				}
			}
		} else {
			var old1 = '';
		}
		var a = $("input[name='oh_drugs_select']:checked").val();
		if(a){
			if (a == 'No illicit drug use.') {
				var a1 = a;
				$("#oh_drugs").val(old1+a1);
			} else {
				var drug = $("#oh_drugs_text");
				var drug1 = $("#oh_drugs_text1");
				var a0 = $("#oh_drugs_text").val();
				var a2 = $("#oh_drugs_text1").val();
				var bValid = true;
				bValid = bValid && checkEmpty(drug,"Type of Drug");
				bValid = bValid && checkEmpty(drug1,"Frequency");
				if (bValid) {
					var a1 = a + a0 + '\nFrequency of drug use: ' + a2;
					$("#oh_drugs").val(old1+a1);
					$('#oh_drugs_input').hide('fast');
				}
			}
		} else {
			var a1 = '';
			$("#oh_drugs").val(old1+a1);
			$('#oh_drugs_input').hide('fast');
		}
	});
	$('#cancel_oh_drugs_form').click(function(){
		$('#oh_drugs_form').clearDiv();
	});
	$("#save_oh_drugs").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#cancel_oh_drugs").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#copy_oh_drugs").button({
		icons: {
			primary: "ui-icon-clipboard"
		}
	});
	$('#oh_drugs_reset').button();
	$("#save_oh_drugs_form").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#cancel_oh_drugs_form").button({
		icons: {
			primary: "ui-icon-close"
		}
	});

	$("#oh_employment_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#oh_employment").val();
			var b = $("#oh_employment_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_oh_status()
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#button_oh_employment").button();
	$("#button_oh_employment").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_oh');?>",
			dataType: "json",
			success: function(data){
				$('#oh_employment').val(data.oh_employment);
				$('#oh_employment_old').val(data.oh_employment);
			}
		});
		$("#oh_employment_dialog").dialog('open');
		$("#oh_employment").focus();
	});
	$('#save_oh_employment').click(function(){
		var a1 = $('#oh_employment').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/oh_save1/employment');?>",
			data: "oh_employment=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#oh_employment').val('');
				$('#oh_employment_old').val('');
				$('#oh_employment_form').clearDiv();
				$("#oh_employment_dialog").dialog('close');
				check_oh_status()
			}
		});
	});
	$('#cancel_oh_employment').click(function(){
		$('#oh_employment').val('');
		$('#oh_employment_old').val('');
		$("#oh_employment_dialog").dialog('close');
	});
	$("#copy_oh_employment").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/copy_oh/employment');?>",
			dataType: "json",
			success: function(data){
				if(data.callback == 'Items copied from last encounter!'){
					$.jGrowl(data.callback);
					$("#oh_employment").val(data.oh_employment);
				} else {
					$.jGrowl(data.callback);
					$("#oh_employment").val('');
				}
			}
		});
	});
	$('#oh_employment_reset').click(function(){
		$("#oh_employment").val('');
	});
	$("input[name='oh_employment_select']").click(function(){
		var a = $('#oh_employment_1').attr('checked');
		if(a){
			$('#oh_employment_input').show('fast');
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/chartmenu/demographics');?>",
				dataType: "json",
				success: function(data){
					$("#oh_employment_employer").val(data.employer);
					$("#oh_employment_employer_old").val(data.employer);
				}
			});
			$("oh_employment_employer").focus();
		} else {
			$('#oh_employment_input').hide('fast');
		}
	});
	$('#save_oh_employment_form').click(function(){
		var old = $("#oh_employment").val();
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
		var a = $("input[name='oh_employment_select']:checked").val();
		var b = $("#oh_employment_text").val();
		var c = $("#oh_employment_employer").val();
		var c0 = $("#oh_employment_employer_old").val();
		if(a){
			var a1 = a + '\n';
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'Employment field: ' + b + '\n';
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'Employer: ' + c + '\n';
		} else {
			var c1 = '';
		}
		var full = a1+b1+c1;
		var len = full.length;
		var len1 = len - 1;
		var full1 = full.slice(0, len1);
		$("#oh_employment").val(old1+full1);
		if(c != c0){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/edit_demographics1');?>",
				data: "employer=" + c,
				success: function(data){
					$.jGrowl(data);
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/chartmenu/demographics_list');?>",
						success: function(data){
							$('#demographics_tip').html(data);
						}
					});
				}
			});
		}
	});
	$('#cancel_oh_employment_form').click(function(){
		$('#oh_employment_form').clearDiv();
	});
	$("#save_oh_employment").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#cancel_oh_employment").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#copy_oh_employment").button({
		icons: {
			primary: "ui-icon-clipboard"
		}
	});
	$('#oh_employment_reset').button();
	$("#save_oh_employment_form").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#cancel_oh_employment_form").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	function oh_autosave() {
		var old2a = $("#oh_pmh_old").val();
		var new2a = $("#oh_pmh").val();
		var old2b = $("#oh_psh_old").val();
		var new2b = $("#oh_psh").val();
		var old2c = $("#oh_fh_old").val();
		var new2c = $("#oh_fh").val();
		if (old2a != new2a || old2b != new2b || old2c != new2c) {
			var oh_str = $("#oh_form").serialize();
			if(oh_str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/encounters/oh_save');?>",
					data: oh_str,
					success: function(data){
						$.jGrowl(data);
						var a = $("#oh_pmh").val();
						var b = $("#oh_psh").val();
						var c = $("#oh_fh").val();
						$("#oh_pmh_old").val(a);
						$("#oh_psh_old").val(b);
						$("#oh_fh_old").val(c);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	}
	setInterval(oh_autosave, 10000);
</script>
