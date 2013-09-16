<form id="encounters_labs">
	<table>
		<tr>
			<td valign="top">
				<table>
					<tr>
						<td><div id="button_labs_ua_status" class="labs_tooltip"><?php echo $ua_status;?></div></td>
						<td><input type="button" id="button_labs_ua" value="Dipstick UA" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_labs_rapid_status" class="labs_tooltip"><?php echo $rapid_status;?></div></td>
						<td><input type="button" id="button_labs_rapid" value="Rapid Tests" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_labs_micro_status" class="labs_tooltip"><?php echo $micro_status;?></div></td>
						<td><input type="button" id="button_labs_micro" value="Microscopy" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_labs_other_status" class="labs_tooltip"><?php echo $other_status;?></div></td>
						<td><input type="button" id="button_labs_other" value="Other" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<div id="labs_ua_dialog" title="Dipstick Urinalysis">
	<button type="button" id="save_labs_ua">Save</button>
	<button type="button" id="cancel_labs_ua">Cancel</button>
	<br><hr class="ui-state-default"/><br>
	<form id="labs_ua_form">
		<input type="hidden" id="labs_ua_urobili_old">
		<input type="hidden" id="labs_ua_bilirubin_old">
		<input type="hidden" id="labs_ua_ketones_old">
		<input type="hidden" id="labs_ua_glucose_old">
		<input type="hidden" id="labs_ua_protein_old">
		<input type="hidden" id="labs_ua_nitrites_old">
		<input type="hidden" id="labs_ua_leukocytes_old">
		<input type="hidden" id="labs_ua_blood_old">
		<input type="hidden" id="labs_ua_ph_old">
		<input type="hidden" id="labs_ua_spgr_old">
		<input type="hidden" id="labs_ua_color_old">
		<input type="hidden" id="labs_ua_clarity_old">
		<table>
			<tr>
				<td align="left">Urobilinogen:</td>
				<td align="left">
					<select name="labs_ua_urobili" id="labs_ua_urobili" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Normal">Normal</option>
						<option value="2 mg/Dl">2 mg/Dl</option>
						<option value="4 mg/Dl">4 mg/Dl</option>
						<option value="8 mg/Dl">8 mg/Dl</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Bilirubin:</td>
				<td align="left">
					<select name="labs_ua_bilirubin" id="labs_ua_bilirubin" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Negative">Negative</option>
						<option value="+">+</option>
						<option value="++">++</option>
						<option value="+++">+++</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Ketones:</td>
				<td align="left">
					<select name="labs_ua_ketones" id="labs_ua_ketones" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Negative">Negative</option>
						<option value="5 mg/Dl">5 mg/Dl</option>
						<option value="15 mg/Dl">15 mg/Dl</option>
						<option value="40 mg/Dl">40 mg/Dl</option>
						<option value="80 mg/Dl">80 mg/Dl</option>
						<option value="160 mg/Dl">160 mg/Dl</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Glucose:</td>
				<td align="left">
					<select name="labs_ua_glucose" id="labs_ua_glucose" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Negative">Negative</option>
						<option value="50 mg/Dl">50 mg/Dl</option>
						<option value="100 mg/Dl">100 mg/Dl</option>
						<option value="250 mg/Dl">250 mg/Dl</option>
						<option value="500 mg/Dl">500 mg/Dl</option>
						<option value="1000 mg/Dl">1000 mg/Dl</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Protein:</td>
				<td align="left">
					<select name="labs_ua_protein" id="labs_ua_protein" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Negative">Negative</option>
						<option value="Trace">Trace</option>
						<option value="30 mg/Dl">30 mg/Dl</option>
						<option value="100 mg/Dl">100 mg/Dl</option>
						<option value="300 mg/Dl">300 mg/Dl</option>
						<option value="2000 mg/Dl">2000 mg/Dl</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Nitrites:</td>
				<td align="left">
					<select name="labs_ua_nitrites" id="labs_ua_nitrites" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Negative">Negative</option>
						<option value="Positive">Positive</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Leukocytes:</td>
				<td align="left">
					<select name="labs_ua_leukocytes" id="labs_ua_leukocytes" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Negative">Negative</option>
						<option value="Trace">Trace</option>
						<option value="+">+</option>
						<option value="++">++</option>
						<option value="+++">+++</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Blood:</td>
				<td align="left">
					<select name="labs_ua_blood" id="labs_ua_blood" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Negative">Negative</option>
						<option value="Trace">Trace</option>
						<option value="+">+</option>
						<option value="++">++</option>
						<option value="+++">+++</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">pH:</td>
				<td align="left">
					<select name="labs_ua_ph" id="labs_ua_ph" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="6.5">6.5</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Specific gravity:</td>
				<td align="left">
					<select name="labs_ua_spgr" id="labs_ua_spgr" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="1.000">1.000</option>
						<option value="1.005">1.005</option>
						<option value="1.010">1.010</option>
						<option value="1.015">1.015</option>
						<option value="1.020">1.020</option>
						<option value="1.025">1.025</option>
						<option value="1.030">1.030</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Color:</td>
				<td align="left">
					<select name="labs_ua_color" id="labs_ua_color" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Colorless">Colorless</option>
						<option value="Yellow">Yellow</option>
						<option value="Amber">Amber</option>
						<option value="Orange">Orange</option>
						<option value="Green">Green</option>
						<option value="Red">Red</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Clarity:</td>
				<td align="left">
					<select name="labs_ua_clarity" id="labs_ua_clarity" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Clear">Clear</option>
						<option value="Hazy">Hazy</option>
						<option value="Cloudy">Cloudy</option>
						<option value="Turbid">Turbid</option>
					</select>
				</td>
			</tr>
		</table>
	</form>
</div>			
<div id="labs_rapid_dialog" title="Rapid Tests">
	<button type="button" id="save_labs_rapid">Save</button>
	<button type="button" id="cancel_labs_rapid">Cancel</button>
	<br><hr class="ui-state-default"/><br>
	<form id="labs_rapid_form">
		<table>
			<tr>
				<td align="left">Rapid Strep:</td>
				<td align="left">
					<select name="labs_strep" id="labs_strep" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Negative">Negative</option>
						<option value="Positive">Positive</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Mono Spot:</td>
				<td align="left">
					<select name="labs_mono" id="labs_mono" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Negative">Negative</option>
						<option value="Positive">Positive</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Rapid Influenza:</td>
				<td align="left">
					<select name="labs_flu" id="labs_flu" class="text ui-widget-content ui-corner-all">
						<option value="">Select One</option>
						<option value="Negative">Negative</option>
						<option value="Positive">Positive</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Fingerstick Glucose:</td>
				<td align="left">
					<input type="text" name="labs_glucose" id="labs_glucose" style="width:60px" class="text ui-widget-content ui-corner-all"/>
				</td>
			</tr>
		</table>
	</form>
</div>
<div id="labs_micro_dialog" title="Microscopy">
	<button type="button" id="save_labs_micro">Save</button>
	<button type="button" id="cancel_labs_micro">Cancel</button>
	<br><hr class="ui-state-default"/><br>
	<form id="labs_micro_form">
		<table>
			<tr>
				<td valign="top">Microscopy:</td>
				<td valign="top"><textarea style="width:500px" rows="3" name="labs_microscope" id="labs_microscope" class="text ui-widget-content ui-corner-all"></textarea></td>
			</tr>
		</table>
	</form>
</div>
<div id="labs_other_dialog" title="Other">
	<button type="button" id="save_labs_other">Save</button>
	<button type="button" id="cancel_labs_other">Cancel</button>
	<br><hr class="ui-state-default"/><br>
	<form id="labs_other_form">
		<table>
			<tr>
				<td valign="top">Other tests:</td>
				<td valign="top"><textarea style="width:500px" rows="3" name="labs_other" id="labs_other" class="text ui-widget-content ui-corner-all"></textarea></td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
	function check_labs1() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/check_labs');?>",
			dataType: "json",
			success: function(data){
				$.jGrowl(data.message);
				$('#button_labs_ua_status').html(data.ua);
				$('#button_labs_rapid_status').html(data.rapid);
				$('#button_labs_micro_status').html(data.micro);
				$('#button_labs_other_status').html(data.other);
			}
		});
	}
	$("#labs_ua_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#labs_ua_urobili").val();
			var a1 = $("#labs_ua_urobili_old").val();
			var b = $("#labs_ua_bilirubin").val();
			var b1 = $("#labs_ua_bilirubin_old").val();
			var c = $("#labs_ua_ketones").val();
			var c1 = $("#labs_ua_ketones_old").val();
			var d = $("#labs_ua_protein").val();
			var d1 = $("#labs_ua_protein_old").val();
			var e = $("#labs_ua_nitrites").val();
			var e1 = $("#labs_ua_nitrites_old").val();
			var f = $("#labs_ua_leukocytes").val();
			var f1 = $("#labs_ua_leukocytes_old").val();
			var g = $("#labs_ua_blood").val();
			var g1 = $("#labs_ua_blood_old").val();
			var h = $("#labs_ua_ph").val();
			var h1 = $("#labs_ua_ph_old").val();
			var i = $("#labs_ua_spgr").val();
			var i1 = $("#labs_ua_spgr_old").val();
			var j = $("#labs_ua_color").val();
			var j1 = $("#labs_ua_color_old").val();
			var k = $("#labs_ua_clarity").val();
			var k1 = $("#labs_ua_clarity_old").val();
			var l = $("#labs_ua_glucose").val();
			var l1 = $("#labs_ua_glucose_old").val();
			if(a != '' && a != a1 && b != '' && b != b1 && c != '' && c != c1 && d != '' && d != d1 && e != '' && e != e1 && f != '' && f != f1 && g != '' && g != g1 && h != '' && h != h1 && i != '' && i != i1 && j != '' && j != j1 && k != '' && k != k1 && l != '' && l != l1){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_labs1();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#labs_rapid_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(evmicro, ui) {
			var a = $("#labs_upt").val();
			var a1 = $("#labs_upt_old").val();
			var b = $("#labs_strep").val();
			var b1 = $("#labs_strep_old").val();
			var c = $("#labs_mono").val();
			var c1 = $("#labs_mono_old").val();
			var d = $("#labs_flu").val();
			var d1 = $("#labs_flu_old").val();
			var e = $("#labs_glucose").val();
			var e1 = $("#labs_glucose_old").val();
			if(a != '' && a != a1 && b != '' && b != b1 && c != '' && c != c1 && d != '' && d != d1 && e != '' && e != e1){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_labs1();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}	
	});
	$("#labs_micro_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#labs_microscope").val();
			var a1 = $("#labs_microscope_old").val();
			if(a != '' && a != a1){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_labs1();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#labs_other_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#labs_other").val();
			var a1 = $("#labs_other_old").val();
			if(a != '' && a != a1){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_labs1();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#button_labs_ua").button();
	$("#button_labs_ua").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_labs');?>",
			dataType: "json",
			success: function(data){
				$('#labs_ua_urobili').val(data.labs_ua_urobili);
				$('#labs_ua_urobili_old').val(data.labs_ua_urobili);
				$('#labs_ua_bilirubin').val(data.labs_ua_bilirubin);
				$('#labs_ua_bilirubin_old').val(data.labs_ua_bilirubin);
				$('#labs_ua_ketones').val(data.labs_ua_ketones);
				$('#labs_ua_ketones_old').val(data.labs_ua_ketones);
				$('#labs_ua_glucose').val(data.labs_ua_glucose);
				$('#labs_ua_glucose_old').val(data.labs_ua_glucose);
				$('#labs_ua_protein').val(data.labs_ua_protein);
				$('#labs_ua_protein_old').val(data.labs_ua_protein);
				$('#labs_ua_nitrites').val(data.labs_ua_nitrites);
				$('#labs_ua_nitrites_old').val(data.labs_ua_nitrites);
				$('#labs_ua_leukocytes').val(data.labs_ua_leukocytes);
				$('#labs_ua_leukocytes_old').val(data.labs_ua_leukocytes);
				$('#labs_ua_blood').val(data.labs_ua_blood);
				$('#labs_ua_blood_old').val(data.labs_ua_blood);
				$('#labs_ua_ph').val(data.labs_ua_ph);
				$('#labs_ua_ph_old').val(data.labs_ua_ph);
				$('#labs_ua_spgr').val(data.labs_ua_spgr);
				$('#labs_ua_spgr_old').val(data.labs_ua_spgr);
				$('#labs_ua_color').val(data.labs_ua_color);
				$('#labs_ua_color_old').val(data.labs_ua_color);
				$('#labs_ua_clarity').val(data.labs_ua_clarity);
				$('#labs_ua_clarity_old').val(data.labs_ua_clarity);
			}
		});
		$("#labs_ua_dialog").dialog('open');
		$("#labs_ua").focus();
	});
	$('.labs_tooltip').tooltip({
		items: ".labs_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		open: function(event, ui){
			var elem = $(this);
			var id = $(this).attr("id");
			var parts = id.split('_');
			var id1 = parts[2];
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/tip_labs/');?>/" + id1,
				success: function(data){
					elem.tooltip('option', 'content', data);
				}
			});
		}
	});
	$("#button_labs_rapid").button();
	$("#button_labs_rapid").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_labs');?>",
			dataType: "json",
			success: function(data){
				$('#labs_upt').val(data.labs_upt);
				$('#labs_upt_old').val(data.labs_upt);
				$('#labs_strep').val(data.labs_strep);
				$('#labs_strep_old').val(data.labs_strep);
				$('#labs_mono').val(data.labs_mono);
				$('#labs_mono_old').val(data.labs_mono);
				$('#labs_flu').val(data.labs_flu);
				$('#labs_flu_old').val(data.labs_flu);
				$('#labs_glucose').val(data.labs_glucose);
				$('#labs_glucose_old').val(data.labs_glucose);
			}
		});
		$("#labs_rapid_dialog").dialog('open');
		$("#labs_rapid").focus();
	});
	$("#button_labs_micro").button();
	$("#button_labs_micro").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_labs');?>",
			dataType: "json",
			success: function(data){
				$('#labs_microscope').val(data.labs_microscope);
				$('#labs_microscope_old').val(data.labs_microscope);
			}
		});
		$("#labs_micro_dialog").dialog('open');
		$("#labs_micro").focus();
	});
	$("#button_labs_other").button();
	$("#button_labs_other").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_labs');?>",
			dataType: "json",
			success: function(data){
				$('#labs_other').val(data.labs_other);
				$('#labs_other_old').val(data.labs_other);
			}
		});
		$("#labs_other_dialog").dialog('open');
		$("#labs_other").focus();
	});
	$('#save_labs_ua').click(function(){
		var str = $("#labs_ua_form").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/labs_save/ua');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$('#labs_ua_urobili').val('');
					$('#labs_ua_urobili_old').val('');
					$('#labs_ua_bilirubin').val('');
					$('#labs_ua_bilirubin_old').val('');
					$('#labs_ua_ketones').val('');
					$('#labs_ua_ketones_old').val('');
					$('#labs_ua_protein').val('');
					$('#labs_ua_protein_old').val('');
					$('#labs_ua_glucose').val('');
					$('#labs_ua_glucose_old').val('');
					$('#labs_ua_nitrites').val('');
					$('#labs_ua_nitrites_old').val('');
					$('#labs_ua_leukocytes').val('');
					$('#labs_ua_leukocytes_old').val('');
					$('#labs_ua_blood').val('');
					$('#labs_ua_blood_old').val('');
					$('#labs_ua_ph').val('');
					$('#labs_ua_ph_old').val('');
					$('#labs_ua_spgr').val('');
					$('#labs_ua_spgr_old').val('');
					$('#labs_ua_color').val('');
					$('#labs_ua_color_old').val('');
					$('#labs_ua_clarity').val('');
					$('#labs_ua_clarity_old').val('');
					$("#labs_ua_dialog").dialog('close');
					check_labs1();
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$('#cancel_labs_ua').click(function(){
		$('#labs_ua_urobili').val('');
		$('#labs_ua_urobili_old').val('');
		$('#labs_ua_bilirubin').val('');
		$('#labs_ua_bilirubin_old').val('');
		$('#labs_ua_ketones').val('');
		$('#labs_ua_ketones_old').val('');
		$('#labs_ua_protein').val('');
		$('#labs_ua_protein_old').val('');
		$('#labs_ua_glucose').val('');
		$('#labs_ua_glucose_old').val('');
		$('#labs_ua_nitrites').val('');
		$('#labs_ua_nitrites_old').val('');
		$('#labs_ua_leukocytes').val('');
		$('#labs_ua_leukocytes_old').val('');
		$('#labs_ua_blood').val('');
		$('#labs_ua_blood_old').val('');
		$('#labs_ua_ph').val('');
		$('#labs_ua_ph_old').val('');
		$('#labs_ua_spgr').val('');
		$('#labs_ua_spgr_old').val('');
		$('#labs_ua_color').val('');
		$('#labs_ua_color_old').val('');
		$('#labs_ua_clarity').val('');
		$('#labs_ua_clarity_old').val('');
		$("#labs_ua_dialog").dialog('close');
	});
	$('#save_labs_rapid').click(function(){
		var str = $("#labs_rapid_form").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/labs_save/rapid');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$('#labs_upt').val('');
					$('#labs_upt_old').val('');
					$('#labs_strep').val('');
					$('#labs_strep_old').val('');
					$('#labs_mono').val('');
					$('#labs_mono_old').val('');
					$('#labs_flu').val('');
					$('#labs_flu_old').val('');
					$('#labs_glucose').val('');
					$('#labs_glucose_old').val('');
					$("#labs_rapid_dialog").dialog('close');
					check_labs1();
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$('#cancel_labs_rapid').click(function(){
		$('#labs_upt').val('');
		$('#labs_upt_old').val('');
		$('#labs_strep').val('');
		$('#labs_strep_old').val('');
		$('#labs_mono').val('');
		$('#labs_mono_old').val('');
		$('#labs_flu').val('');
		$('#labs_flu_old').val('');
		$('#labs_glucose').val('');
		$('#labs_glucose_old').val('');
		$("#labs_rapid_dialog").dialog('close');
	});
	$('#save_labs_micro').click(function(){
		var str = $("#labs_micro_form").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/labs_save/micro');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$('#labs_microscope').val('');
					$('#labs_microscope_old').val('');
					$("#labs_micro_dialog").dialog('close');
					check_labs1();
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$('#cancel_labs_micro').click(function(){
		$('#labs_microscope').val('');
		$('#labs_microscope_old').val('');
		$("#labs_micro_dialog").dialog('close');
	});
	$('#save_labs_other').click(function(){
		var str = $("#labs_other_form").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/labs_save/other');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$('#labs_other').val('');
					$('#labs_other_old').val('');
					$("#labs_other_dialog").dialog('close');
					check_labs1();
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$('#cancel_labs_other').click(function(){
		$('#labs_other').val('');
		$('#labs_other_old').val('');
		$("#labs_other_dialog").dialog('close');
	});
	$("#save_labs_ua").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#cancel_labs_ua").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#save_labs_rapid").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#cancel_labs_rapid").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#save_labs_micro").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#cancel_labs_micro").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#save_labs_other").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#cancel_labs_other").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
</script>
