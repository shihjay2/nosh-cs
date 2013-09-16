<form id="encounters_ros">
	<table>
		<tr>
			<td valign="top">
				<table>
					<tr>
						<td><div id="button_ros_gen_status" class="ros_tooltip"><?php echo $gen_status;?></div></td>
						<td><input type="button" id="button_ros_gen" value="General" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_eye_status" class="ros_tooltip"><?php echo $eye_status;?></div></td>
						<td><input type="button" id="button_ros_eye" value="Eye" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_ent_status" class="ros_tooltip"><?php echo $ent_status;?></div></td>
						<td><input type="button" id="button_ros_ent" value="Ear, Nose, and Throat" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_resp_status" class="ros_tooltip"><?php echo $resp_status;?></div></td>
						<td><input type="button" id="button_ros_resp" value="Respiratory" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_cv_status" class="ros_tooltip"><?php echo $cv_status;?></div></td>
						<td><input type="button" id="button_ros_cv" value="Cardiovascular" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_gi_status" class="ros_tooltip"><?php echo $gi_status;?></div></td>
						<td><input type="button" id="button_ros_gi" value="Gastrointestinal" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_gu_status" class="ros_tooltip"><?php echo $gu_status;?></div></td>
						<td><input type="button" id="button_ros_gu" value="Genitourinary" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table>	
					<tr>
						<td><div id="button_ros_mus_status" class="ros_tooltip"><?php echo $mus_status;?></div></td>
						<td><input type="button" id="button_ros_mus" value="Musculoskeletal" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_neuro_status" class="ros_tooltip"><?php echo $neuro_status;?></div></td>
						<td><input type="button" id="button_ros_neuro" value="Neurological" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_psych_status" class="ros_tooltip"><?php echo $psych_status;?></div></td>
						<td><input type="button" id="button_ros_psych" value="Psychological" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_heme_status" class="ros_tooltip"><?php echo $heme_status;?></div></td>
						<td><input type="button" id="button_ros_heme" value="Hematological/Lymphatic" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_endocrine_status" class="ros_tooltip"><?php echo $endocrine_status;?></div></td>
						<td><input type="button" id="button_ros_endocrine" value="Endocrine" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_ros_skin_status" class="ros_tooltip"><?php echo $skin_status;?></div></td>
						<td><input type="button" id="button_ros_skin" value="Skin" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<?php echo $wcc;?>
				</table>
			</td>
		</tr>
	</table>
</form>
<div id="ros_gen_dialog" title="General">
	<button type="button" id="save_ros_gen">Save</button>
	<button type="button" id="cancel_ros_gen">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_gen" id="ros_gen" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_gen_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_gen_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_gen_normal" class="all_normal" value=""><label for="ros_gen_normal">All Normal</label><button type="button" id="ros_gen_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_gen_nc" class="nc">Noncontributory</button><button type="button" id="ros_gen_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_gen_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
		
	</div>
</div>
<div id="ros_eye_dialog" title="Eye">
	<button type="button" id="save_ros_eye">Save</button>
	<button type="button" id="cancel_ros_eye">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_eye" id="ros_eye" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_eye_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_eye_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_eye_normal" class="all_normal" value=""><label for="ros_eye_normal">All Normal</label><button type="button" id="ros_eye_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_eye_nc" class="nc">Noncontributory</button><button type="button" id="ros_eye_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_eye_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_ent_dialog" title="Ear, Nose, and Throat">
	<button type="button" id="save_ros_ent">Save</button>
	<button type="button" id="cancel_ros_ent">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_ent" id="ros_ent" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_ent_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_ent_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_ent_normal" class="all_normal" value=""><label for="ros_ent_normal">All Normal</label><button type="button" id="ros_ent_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_ent_nc" class="nc">Noncontributory</button><button type="button" id="ros_ent_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_ent_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_resp_dialog" title="Respiratory">
	<button type="button" id="save_ros_resp">Save</button>
	<button type="button" id="cancel_ros_resp">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_resp" id="ros_resp" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_resp_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_resp_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_resp_normal" class="all_normal" value=""><label for="ros_resp_normal">All Normal</label><button type="button" id="ros_resp_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_resp_nc" class="nc">Noncontributory</button><button type="button" id="ros_resp_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_resp_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_cv_dialog" title="Cardiovascular">
	<button type="button" id="save_ros_cv">Save</button>
	<button type="button" id="cancel_ros_cv">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_cv" id="ros_cv" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_cv_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_cv_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_cv_normal" class="all_normal" value=""><label for="ros_cv_normal">All Normal</label><button type="button" id="ros_cv_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_cv_nc" class="nc">Noncontributory</button><button type="button" id="ros_cv_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_cv_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
		
	</div>
</div>
<div id="ros_gi_dialog" title="Gastrointestinal">
	<button type="button" id="save_ros_gi">Save</button>
	<button type="button" id="cancel_ros_gi">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_gi" id="ros_gi" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_gi_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_gi_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_gi_normal" class="all_normal" value=""><label for="ros_gi_normal">All Normal</label><button type="button" id="ros_gi_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_gi_nc" class="nc">Noncontributory</button><button type="button" id="ros_gi_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_gi_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_gu_dialog" title="Genitourinary">
	<button type="button" id="save_ros_gu">Save</button>
	<button type="button" id="cancel_ros_gu">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_gu" id="ros_gu" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_gu_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_gu_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_gu_normal" class="all_normal" value=""><label for="ros_gu_normal">All Normal</label><button type="button" id="ros_gu_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_gu_nc" class="nc">Noncontributory</button><button type="button" id="ros_gu_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_gu_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_mus_dialog" title="Musculoskeletal">
	<button type="button" id="save_ros_mus">Save</button>
	<button type="button" id="cancel_ros_mus">Cancel
	</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_mus" id="ros_mus" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_mus_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_mus_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_mus_normal" class="all_normal" value=""><label for="ros_mus_normal">All Normal</label><button type="button" id="ros_mus_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_mus_nc" class="nc">Noncontributory</button><button type="button" id="ros_mus_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_mus_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_neuro_dialog" title="Neurological">
	<button type="button" id="save_ros_neuro">Save</button>
	<button type="button" id="cancel_ros_neuro">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_neuro" id="ros_neuro" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_neuro_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_neuro_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_neuro_normal" class="all_normal" value=""><label for="ros_neuro_normal">All Normal</label><button type="button" id="ros_neuro_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_neuro_nc" class="nc">Noncontributory</button><button type="button" id="ros_neuro_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_neuro_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_psych_dialog" title="Psychological">
	<button type="button" id="save_ros_psych">Save</button>
	<button type="button" id="cancel_ros_psych">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_psych" id="ros_psych" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_psych_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_psych_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_psych_normal" class="all_normal" value=""><label for="ros_psych_normal">All Normal</label><button type="button" id="ros_psych_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_psych_nc" class="nc">Noncontributory</button><button type="button" id="ros_psych_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_psych_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>	
<div id="ros_heme_dialog" title="Hematological/Lymphatic">
	<button type="button" id="save_ros_heme">Save</button>
	<button type="button" id="cancel_ros_heme">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_heme" id="ros_heme" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_heme_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_heme_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_heme_normal" class="all_normal" value=""><label for="ros_heme_normal">All Normal</label><button type="button" id="ros_heme_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_heme_nc" class="nc">Noncontributory</button><button type="button" id="ros_heme_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_heme_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_endocrine_dialog" title="Endocrine">
	<button type="button" id="save_ros_endocrine">Save</button>
	<button type="button" id="cancel_ros_endocrine">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_endocrine" id="ros_endocrine" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_endocrine_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_endocrine_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_endocrine_normal" class="all_normal" value=""><label for="ros_endocrine_normal">All Normal</label><button type="button" id="ros_endocrine_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_endocrine_nc" class="nc">Noncontributory</button><button type="button" id="ros_endocrine_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_endocrine_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_skin_dialog" title="Skin">
	<button type="button" id="save_ros_skin">Save</button>
	<button type="button" id="cancel_ros_skin">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_skin" id="ros_skin" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_skin_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_skin_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_skin_normal" class="all_normal" value=""><label for="ros_skin_normal">All Normal</label><button type="button" id="ros_skin_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_skin_nc" class="nc">Noncontributory</button><button type="button" id="ros_skin_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_skin_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="ros_wcc_dialog" title="Well Child Check">
	<button type="button" id="save_ros_wcc">Save</button>
	<button type="button" id="cancel_ros_wcc">Cancel</button>
	<br><hr class="ui-state-default"/>
	<div>
		<div style="display:block;float:left;width:310px">
			Preview:<br><textarea style="width:290px" rows="16" name="ros_wcc" id="ros_wcc" class="ros_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="ros_wcc_old"/>
		</div>
		<div style="display:block;float:left">
			Choose Template: <select id="ros_wcc_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
			<br><input type="checkbox" id="ros_wcc_normal" class="all_normal" value=""><label for="ros_wcc_normal">All Normal</label><button type="button" id="ros_wcc_hpi" class="per_hpi">Per HPI</button><button type="button" id="ros_wcc_nc" class="nc">Noncontributory</button><button type="button" id="ros_wcc_reset" class="reset">Clear</button>
			<div class="ros_template_div">
				<br><form id="ros_wcc_form" class="ros_template_form ui-widget"></form>
				<br><form id="ros_wcc_age_form" class="ros_template_form ui-widget"></form>
			</div>
		</div>
	</div>
</div>
<div id="dform_ros_load" title="Loading...">
	<img src="<?php echo base_url().'images/indicator.gif';?>"> Loading templates.
</div>
<script type="text/javascript">
	$("#dform_ros_load").dialog({
		height: 100,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		modal: true
	});
	$("#dform_ros_load").dialog('open');
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('assistant/encounters/get_ros_templates');?>",
		dataType: "json",
		success: function(data){
			$('#ros_gen_form').dform(data.ros_gen);
			$('#ros_eye_form').dform(data.ros_eye);
			$('#ros_ent_form').dform(data.ros_ent);
			$('#ros_resp_form').dform(data.ros_resp);
			$('#ros_cv_form').dform(data.ros_cv);
			$('#ros_gi_form').dform(data.ros_gi);
			$('#ros_gu_form').dform(data.ros_gu);
			$('#ros_mus_form').dform(data.ros_mus);
			$('#ros_neuro_form').dform(data.ros_neuro);
			$('#ros_psych_form').dform(data.ros_psych);
			$('#ros_heme_form').dform(data.ros_heme);
			$('#ros_endocrine_form').dform(data.ros_endocrine);
			$('#ros_skin_form').dform(data.ros_skin);
			var age = parseInt("<?php echo $this->session->userdata('agealldays');?>");
			if (age <= 2191.44) {
				$('#ros_wcc_form').dform(data.ros_wcc);
				$('#ros_wcc_age_form').dform(data.ros_wccage);
			}
			$('.ros_buttonset').buttonset();
			$('.ros_detail_text').hide();
			$('input[type="checkbox"]').change(function() {
				var parent_id = $(this).attr("id");
				var parts = parent_id.split('_');
				if (parts[1] == 'wccage') {
					var parent_id_entry = 'ros_wcc';
				} else {
					var parent_id_entry = parts[0] + '_' + parts[1];
				}
				var old = $("#" + parent_id_entry).val();
				var a = $(this).val();
				if ($(this).prop('checked')) {
					if (old != '') {
						var b = old + a + '  ';
					} else {
						var b = a + '  ';
					}
					$("#" + parent_id_entry).val(b); 
				} else {
					var a1 = a + '  ';
					var c = old.replace(a1,'');
					c = c.replace(a, '');
					$("#" + parent_id_entry).val(c); 
				}
			});
			$('input[type="text"]').focusin(function() {
				old_text = $(this).val();
			});
			$('input[type="text"]').focusout(function() {
				var a = $(this).val();
				if (a != '') {
					var parent_id = $(this).attr("id");
					var parts = parent_id.split('_');
					if (parts[1] == 'wccage') {
						var parent_id_entry = 'ros_wcc';
					} else {
						var parent_id_entry = parts[0] + '_' + parts[1];
					}
					var x = parent_id.length - 1;
					var parent_div = parent_id.slice(0,x);
					var start1 = $("#" + parent_div + "_div").find('span:first').text();
					var start1_n = start1.lastIndexOf(' (');
					if (start1_n != -1) {
						var start1_n1 = start1.substring(0,start1_n);
						var start1_n2 = start1_n1.toLowerCase();
					} else {
						var start1_n1 = start1;
						var start1_n2 = start1;
					}
					var start2 = $("label[for='" + parent_id + "']").text();
					var start3_n = start1.lastIndexOf('degrees');
					if (start3_n != -1) {
						var end_text = ' degrees.';
					} else {
						var end_text = '';
					}
					var start4 = $(this).closest('div.ui-accordion').find('h3.ui-state-active').text();
					var start4_n = start4.lastIndexOf('-');
					if (start4_n != -1) {
						var parts2 = start4.split(' - ');
						var mid_text = ', ' + parts2[1].toLowerCase();
					} else {
						var mid_text = ', ' + start4.toLowerCase();
					}
					if (!!start2) {
						var start_text = start2 + ' ' + start1_n2;
					} else {
						var start_text = start1_n1;
					}
					var old = $("#" + parent_id_entry).val();
					var a_pointer = a.length - 1;
					var a_pointer2 = a.lastIndexOf('.');
					if (!!old) {
						if (!!start_text) {
							var c = start_text + mid_text + ': ' + a + end_text;
							if (old_text != '') {
								var c_old = start_text + mid_text + ': ' + old_text + end_text;
							}
						} else {
							if (a_pointer != a_pointer2) {
								var c = a + '.';
							} else {
								var c = a;
							}
						}
						if (old_text != '') {
							var old_text_pointer = old_text.length - 1;
							var old_text_pointer2 = old_text.lastIndexOf('.');
							if (old_text_pointer != old_text_pointer2) {
								var old_text1 = old_text + '.';
							} else {
								var old_text1 = old_text;
							}
							if (!!start_text) {
								var b = old.replace(c_old, c);
							} else {
								var b = old.replace(old_text1, c);
							}
							old_text = '';
						} else {
							var b = old + ' ' + c;
						}
					} else {
						if (!!start_text) {
							var b = start_text + mid_text + ': ' + a + end_text;
						} else {
							if (a_pointer != a_pointer2) {
								var b = a + '.';
							} else {
								var b = a;
							}
						}
					}
					$("#" + parent_id_entry).val(b);
				}
			});
			$('.ros_detail').click(function() {
				var detail_id = $(this).attr("id") + '_detail';
				if ($(this).prop('checked')) {
					$('#' + detail_id).show('fast');
					$('#' + detail_id).focus();
				} else {
					var parent_id = $(this).attr("id");
					var parts = parent_id.split('_');
					if (parts[1] == 'wccage') {
					var parent_id_entry = 'ros_wcc';
					} else {
						var parent_id_entry = parts[0] + '_' + parts[1];
					}
					var old = $("#" + parent_id_entry).val();
					var a = ' ' + $('#' + detail_id).val();
					var a1 = a + '  ';
					var c = old.replace(a1,'');
					c = c.replace(a, '');
					$("#" + parent_id_entry).val(c);
					$('#' + detail_id).hide('fast');
				}
			});
			$('.ros_normal').click(function() {
				if ($(this).prop('checked')) {
					var parent_id = $(this).attr("id");
					var x = parent_id.length - 1;
					parent_id = parent_id.slice(0,x);
					$("#" + parent_id + "_div").find('.ros_other:checkbox').each(function(){
						var parent_id = $(this).attr("id");
						$(this).prop('checked',false);
						var parts = parent_id.split('_');
						if (parts[1] == 'wccage') {
							var parent_id_entry = 'ros_wcc';
						} else {
							var parent_id_entry = parts[0] + '_' + parts[1];
						}
						var old = $("#" + parent_id_entry).val();
						var a = $(this).val();
						var a1 = a + '  ';
						var c = old.replace(a1,'');
						c = c.replace(a, '');
						$("#" + parent_id_entry).val(c);
						$("#" + parent_id_entry + "_form input").button('refresh');
						if (parts[1] == 'wccage') {
							$("#ros_wcc_age_form input").button('refresh');
						}
					});
					$("#" + parent_id + "_div").find('.ros_detail_text').each(function(){
						var parent_id = $(this).attr("id");
						var parts = parent_id.split('_');
						if (parts[1] == 'wccage') {
							var parent_id_entry = 'ros_wcc';
						} else {
							var parent_id_entry = parts[0] + '_' + parts[1];
						}
						var old = $("#" + parent_id_entry).val();
						var a = ' ' + $(this).val();
						var a1 = a + '  ';
						var c = old.replace(a1,'');
						c = c.replace(a, '');
						$("#" + parent_id_entry).val(c);
						$(this).hide();
					});
					
				}
			});
			$('.ros_other').click(function() {
				if ($(this).prop('checked')) {
					var parent_id = $(this).attr("id");
					var x = parent_id.length - 1;
					parent_id = parent_id.slice(0,x);
					$("#" + parent_id + "_div").find('.ros_normal:checkbox').each(function(){
						var parent_id = $(this).attr("id");
						$(this).prop('checked',false);
						var parts = parent_id.split('_');
						if (parts[1] == 'wccage') {
							var parent_id_entry = 'ros_wcc';
						} else {
							var parent_id_entry = parts[0] + '_' + parts[1];
						}
						var old = $("#" + parent_id_entry).val();
						var a = $(this).val();
						var a1 = a + '  ';
						var c = old.replace(a1,'');
						c = c.replace(a, '');
						$("#" + parent_id_entry).val(c);
						$("#" + parent_id_entry + "_form input").button('refresh');
						if (parts[1] == 'wccage') {
							$("#ros_wcc_age_form input").button('refresh');
						}
					});
				}
			});
			function updateTextArea(parent_id_entry) {
				var newtext = '';
				$('#' + parent_id_entry + '_form :checked').each(function() {
					newtext += $(this).val() + '  ';
				});
				$('#' + parent_id_entry).val(newtext);
			}
			$('.all_normal').button().click(function(){
				var a = $(this).prop('checked');
				var parent_id = $(this).attr("id");
				var parts = parent_id.split('_');
				if (parts[1] == 'wcc') {
					if(a){
						$("#ros_wcc_form").find("input.ros_normal:checkbox").each(function(){
							$(this).attr("checked",true);
						});
						$("#ros_wcc_age_form").find("input.ros_normal:checkbox").each(function(){
							$(this).attr("checked",true);
						});
						var newtext = '';
						$('#ros_wcc_form :checked').each(function() {
							newtext += $(this).val() + '  ';
						});
						$('#ros_wcc_age_form :checked').each(function() {
							newtext += $(this).val() + '  ';
						});
						$('#ros_wcc').val(newtext);
					} else {
						$("#ros_wcc").val('');
						$("#ros_wcc_form").find('input.ros_normal:checkbox').each(function(){
						});
						$("#ros_wcc_age_form").find('input.ros_normal:checkbox').each(function(){
							$(this).attr("checked",false);
						});
					}
					$('#ros_wcc_form input[type="checkbox"]').button('refresh');
					$('#ros_wcc_age_form input[type="checkbox"]').button('refresh');
				} else {
					var parent_id_entry = parts[0] + '_' + parts[1];
					if(a){
						$("#" + parent_id_entry + "_form").find("input.ros_normal:checkbox").each(function(){
							$(this).attr("checked",true);
						});
						updateTextArea(parent_id_entry);
					} else {
						$("#" + parent_id_entry).val('');
						$("#" + parent_id_entry + "_form").find('input.ros_normal:checkbox').each(function(){
							$(this).attr("checked",false);
						});
					}
					$("#" + parent_id_entry + '_form input[type="checkbox"]').button('refresh');
				}
			});
			$("#dform_ros_load").dialog('close');
		}
	});
	$('.reset').button().click(function(){
		var parent_id = $(this).attr("id");
		var n = parent_id.lastIndexOf('_');
		parent_id = parent_id.substring(0,n);
		$("#" + parent_id).val('');
	});
	$('.per_hpi').button().click(function(){
		var parent_id = $(this).attr("id");
		var n = parent_id.lastIndexOf('_');
		parent_id = parent_id.substring(0,n);
		var old = $("#" + parent_id).val();
		var a = "Per History of Present Illness.  ";
		$("#" + parent_id).val(old + a);
	
	});
	$('.nc').button().click(function(){
		var parent_id = $(this).attr("id");
		var n = parent_id.lastIndexOf('_');
		parent_id = parent_id.substring(0,n);
		var old = $("#" + parent_id).val();
		var a = "Noncontributory.  ";
		$("#" + parent_id).val(old + a);
	});
	
	function check_ros_status() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/check_ros');?>",
			dataType: "json",
			success: function(data){
				$('#button_ros_gen_status').html(data.gen);
				$('#button_ros_eye_status').html(data.eye);
				$('#button_ros_ent_status').html(data.ent);
				$('#button_ros_resp_status').html(data.resp);
				$('#button_ros_cv_status').html(data.cv);
				$('#button_ros_gi_status').html(data.gi);
				$('#button_ros_gu_status').html(data.gu);
				$('#button_ros_mus_status').html(data.mus);
				$('#button_ros_neuro_status').html(data.neuro);
				$('#button_ros_psych_status').html(data.psych);
				$('#button_ros_heme_status').html(data.heme);
				$('#button_ros_endocrine_status').html(data.endocrine);
				$('#button_ros_skin_status').html(data.skin);
				$('#button_ros_wcc_status').html(data.wcc);
			}
		});
	}
	
	$("#ros_gen_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_gen").val();
			var b = $("#ros_gen_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#ros_eye_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_eye").val();
			var b = $("#ros_eye_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}	
	});
	$("#ros_ent_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_ent").val();
			var b = $("#ros_ent_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#ros_resp_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_resp").val();
			var b = $("#ros_resp_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#ros_cv_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_cv").val();
			var b = $("#ros_cv_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}	
	});
	$("#ros_gi_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_gi").val();
			var b = $("#ros_gi_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#ros_gu_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_gu").val();
			var b = $("#ros_gu_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}	
	});
	$("#ros_mus_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_mus").val();
			var b = $("#ros_mus_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#ros_neuro_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_neuro").val();
			var b = $("#ros_neuro_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#ros_psych_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_psych").val();
			var b = $("#ros_psych_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#ros_heme_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_heme").val();
			var b = $("#ros_heme_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#ros_endocrine_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_endocrine").val();
			var b = $("#ros_endocrine_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}	
	});
	$("#ros_skin_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_skin").val();
			var b = $("#ros_skin_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#ros_wcc_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#ros_wcc").val();
			var b = $("#ros_wcc_old").val();
			if(a != '' && a != b){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_ros_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#button_ros_gen").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_gen').val(data.ros_gen);
				$('#ros_gen_old').val(data.ros_gen);
			}
		});
		$("#ros_gen_dialog").dialog('open').css('overflow','hidden');
		$("#ros_gen").focus();
	});
	$('.ros_tooltip').tooltip({
		items: ".ros_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		open: function(event, ui) {
			var elem = $(this);
			var id = $(this).attr("id");
			var parts = id.split('_');
			var id1 = parts[1] + "_" + parts[2];
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/tip_ros/');?>/" + id1,
				success: function(data) {
					elem.tooltip('option', 'content', data);
				},
			});
		}
	});
	$("#button_ros_eye").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_eye').val(data.ros_eye);
				$('#ros_eye_old').val(data.ros_eye);
			}
		});
		$("#ros_eye_dialog").dialog('open').css('overflow','hidden');
		$("#ros_eye").focus();
	});
	$("#button_ros_ent").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_ent').val(data.ros_ent);
				$('#ros_ent_old').val(data.ros_ent);
			}
		});
		$("#ros_ent_dialog").dialog('open').css('overflow','hidden');
		$("#ros_ent").focus();
	});
	$("#button_ros_resp").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_resp').val(data.ros_resp);
				$('#ros_resp_old').val(data.ros_resp);
			}
		});
		$("#ros_resp_dialog").dialog('open').css('overflow','hidden');
		$("#ros_resp").focus();
	});
	$("#button_ros_cv").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_cv').val(data.ros_cv);
				$('#ros_cv_old').val(data.ros_cv);
			}
		});
		$("#ros_cv_dialog").dialog('open').css('overflow','hidden');
		$("#ros_cv").focus();
	});
	$("#button_ros_gi").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_gi').val(data.ros_gi);
				$('#ros_gi_old').val(data.ros_gi);
			}
		});
		$("#ros_gi_dialog").dialog('open').css('overflow','hidden');
		$("#ros_gi").focus();
	});
	$("#button_ros_gu").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_gu').val(data.ros_gu);
				$('#ros_gu_old').val(data.ros_gu);
			}
		});
		$("#ros_gu_dialog").dialog('open').css('overflow','hidden');
		$("#ros_gu_menarche").datepicker({dateFormat: 'MM d, yy', showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
		$("#ros_gu_lmp").datepicker({dateFormat: 'MM d, yy', showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
		$("#ros_gu").focus();
	});
	$("#button_ros_mus").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_mus').val(data.ros_mus);
				$('#ros_mus_old').val(data.ros_mus);
			}
		});
		$("#ros_mus_dialog").dialog('open').css('overflow','hidden');
		$("#ros_mus").focus();
	});
	$("#button_ros_neuro").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_neuro').val(data.ros_neuro);
				$('#ros_neuro_old').val(data.ros_neuro);
			}
		});
		$("#ros_neuro_dialog").dialog('open').css('overflow','hidden');
		$("#ros_neuro").focus();
	});
	$("#button_ros_psych").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_psych').val(data.ros_psych);
				$('#ros_psych_old').val(data.ros_psych);
			}
		});
		$("#ros_psych_dialog").dialog('open').css('overflow','hidden');
		$("#ros_psych").focus();
	});
	$("#button_ros_heme").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_heme').val(data.ros_heme);
				$('#ros_heme_old').val(data.ros_heme);
			}
		});
		$("#ros_heme_dialog").dialog('open').css('overflow','hidden');
		$("#ros_heme").focus();
	});
	$("#button_ros_endocrine").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_endocrine').val(data.ros_endocrine);
				$('#ros_endocrine_old').val(data.ros_endocrine);
			}
		});
		$("#ros_endocrine_dialog").dialog('open').css('overflow','hidden');
		$("#ros_endocrine").focus();
	});
	$("#button_ros_skin").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_skin').val(data.ros_skin);
				$('#ros_skin_old').val(data.ros_skin);
			}
		});
		$("#ros_skin_dialog").dialog('open').css('overflow','hidden');
		$("#ros_skin").focus();
	});
	$("#button_ros_wcc").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/get_ros');?>",
			dataType: "json",
			success: function(data){
				$('#ros_wcc').val(data.ros_wcc);
				$('#ros_wcc_old').val(data.ros_wcc);
			}
		});
		$("#ros_wcc_dialog").dialog('open').css('overflow','hidden');
		var age = parseInt("<?php echo $this->session->userdata('agealldays');?>");
		if (age <= 2191.44) {
			$(".ros_wcc_0_5").show('fast');
		} else {
			$(".ros_wcc_0_5").hide('fast');
		}
		if (age > 730.48 && age <= 6574.32 ) {
			$(".ros_wcc_2_20").show('fast');
		} else {
			$(".ros_wcc_2_20").hide('fast');
		}
		if (age <= 60.88) {
			$(".ros_wcc_0m").show('fast');
		} else {
			$(".ros_wcc_0m").hide('fast');
		}
		if (age > 60.88 && age <= 121.76) {
			$(".ros_wcc_2m").show('fast');
		} else {
			$(".ros_wcc_2m").hide('fast');
		}
		if (age > 121.76 && age <= 182.64) {
			$(".ros_wcc_4m").show('fast');
		} else {
			$(".ros_wcc_4m").hide('fast');
		}
		if (age > 182.64 && age <= 273.96) {
			$(".ros_wcc_6m").show('fast');
		} else {
			$(".ros_wcc_6m").hide('fast');
		}
		if (age > 273.96 && age <= 365.24) {
			$(".ros_wcc_9m").show('fast');
		} else {
			$(".ros_wcc_9m").hide('fast');
		}
		if (age > 365.24 && age <= 456.6) {
			$(".ros_wcc_12m").show('fast');
		} else {
			$(".ros_wcc_12m").hide('fast');
		}
		if (age > 456.6 && age <= 547.92) {
			$(".ros_wcc_15m").show('fast');
		} else {
			$(".ros_wcc_15m").hide('fast');
		}
		if (age > 547.92 && age <= 730.48) {
			$(".ros_wcc_18m").show('fast');
		} else {
			$(".ros_wcc_18m").hide('fast');
		}
		if (age > 730.48 && age <= 1095.75) {
			$(".ros_wcc_2").show('fast');
		} else {
			$(".ros_wcc_2").hide('fast');
		}
		if (age > 1095.75 && age <= 1461) {
			$(".ros_wcc_3").show('fast');
		} else {
			$(".ros_wcc_3").hide('fast');
		}
		if (age > 1461 && age <= 1826.25) {
			$(".ros_wcc_4").show('fast');
		} else {
			$(".ros_wcc_4").hide('fast');
		}
		if (age > 1826.25 && age <= 2191.44) {
			$(".ros_wcc_5").show('fast');
		} else {
			$(".ros_wcc_5").hide('fast');
		}
		$("#ros_wcc").focus();
	});
	
	$('#save_ros_gen').click(function(){
		var a1 = $('#ros_gen').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/gen');?>",
			data: "ros_gen=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_gen').val('');
				$('#ros_gen_old').val('');
				$("#ros_gen_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_gen').click(function(){
		$('#ros_gen').val('');
		$('#ros_gen_old').val('');
		$("#ros_gen_dialog").dialog('close');
	});
	
	$('#save_ros_eye').click(function(){
		var a1 = $('#ros_eye').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/eye');?>",
			data: "ros_eye=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_eye').val('');
				$('#ros_eye_old').val('');
				$("#ros_eye_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_eye').click(function(){
		$('#ros_eye').val('');
		$('#ros_eye_old').val('');
		$("#ros_eye_dialog").dialog('close');
	});
	
	$('#save_ros_ent').click(function(){
		var a1 = $('#ros_ent').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/ent');?>",
			data: "ros_ent=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_ent').val('');
				$('#ros_ent_old').val('');
				$("#ros_ent_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_ent').click(function(){
		$('#ros_ent').val('');
		$('#ros_ent_old').val('');
		$("#ros_ent_dialog").dialog('close');
	});
	
	$('#save_ros_resp').click(function(){
		var a1 = $('#ros_resp').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/resp');?>",
			data: "ros_resp=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_resp').val('');
				$('#ros_resp_old').val('');
				$("#ros_resp_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_resp').click(function(){
		$('#ros_resp').val('');
		$('#ros_resp_old').val('');
		$("#ros_resp_dialog").dialog('close');
	});
	$('#save_ros_cv').click(function(){
		var a1 = $('#ros_cv').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/cv');?>",
			data: "ros_cv=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_cv').val('');
				$('#ros_cv_old').val('');
				$("#ros_cv_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_cv').click(function(){
		$('#ros_cv').val('');
		$('#ros_cv_old').val('');
		$("#ros_cv_dialog").dialog('close');
	});
	
	$('#save_ros_gi').click(function(){
		var a1 = $('#ros_gi').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/gi');?>",
			data: "ros_gi=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_gi').val('');
				$('#ros_gi_old').val('');
				$("#ros_gi_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_gi').click(function(){
		$('#ros_gi').val('');
		$('#ros_gi_old').val('');
		$("#ros_gi_dialog").dialog('close');
	});
	
	$('#save_ros_gu').click(function(){
		var a1 = $('#ros_gu').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/gu');?>",
			data: "ros_gu=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_gu').val('');
				$('#ros_gu_old').val('');
				$("#ros_gu_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_gu').click(function(){
		$('#ros_gu').val('');
		$('#ros_gu_old').val('');
		$("#ros_gu_dialog").dialog('close');
	});
	
	$('#save_ros_mus').click(function(){
		var a1 = $('#ros_mus').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/mus');?>",
			data: "ros_mus=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_mus').val('');
				$('#ros_mus_old').val('');
				$("#ros_mus_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_mus').click(function(){
		$('#ros_mus').val('');
		$('#ros_mus_old').val('');
		$("#ros_mus_dialog").dialog('close');
	});
	
	$('#save_ros_neuro').click(function(){
		var a1 = $('#ros_neuro').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/neuro');?>",
			data: "ros_neuro=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_neuro').val('');
				$('#ros_neuro_old').val('');
				$("#ros_neuro_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_neuro').click(function(){
		$('#ros_neuro').val('');
		$('#ros_neuro_old').val('');
		$("#ros_neuro_dialog").dialog('close');
	});
	
	$('#save_ros_psych').click(function(){
		var a1 = $('#ros_psych').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/psych');?>",
			data: "ros_psych=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_psych').val('');
				$('#ros_psych_old').val('');
				$("#ros_psych_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_psych').click(function(){
		$('#ros_psych').val('');
		$('#ros_psych_old').val('');
		$("#ros_psych_dialog").dialog('close');
	});
	
	$('#save_ros_heme').click(function(){
		var a1 = $('#ros_heme').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/heme');?>",
			data: "ros_heme=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_heme').val('');
				$('#ros_heme_old').val('');
				$("#ros_heme_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_heme').click(function(){
		$('#ros_heme').val('');
		$('#ros_heme_old').val('');
		$("#ros_heme_dialog").dialog('close');
	});
	
	$('#save_ros_endocrine').click(function(){
		var a1 = $('#ros_endocrine').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/endocrine');?>",
			data: "ros_endocrine=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_endocrine').val('');
				$('#ros_endocrine_old').val('');
				$("#ros_endocrine_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_endocrine').click(function(){
		$('#ros_endocrine').val('');
		$('#ros_endocrine_old').val('');
		$("#ros_endocrine_dialog").dialog('close');
	});
	
	$('#save_ros_skin').click(function(){
		var a1 = $('#ros_skin').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/skin');?>",
			data: "ros_skin=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_skin').val('');
				$('#ros_skin_old').val('');
				$("#ros_skin_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_skin').click(function(){
		$('#ros_skin').val('');
		$('#ros_skin_old').val('');
		$("#ros_skin_dialog").dialog('close');
	});
	
	$('#save_ros_wcc').click(function(){
		var a1 = $('#ros_wcc').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/encounters/ros_save/wcc');?>",
			data: "ros_wcc=" + a1,
			success: function(data){
				$.jGrowl(data);
				$('#ros_wcc').val('');
				$('#ros_wcc_old').val('');
				$("#ros_wcc_dialog").dialog('close');
				check_ros_status();
			}
		});
	});
	$('#cancel_ros_wcc').click(function(){
		$('#ros_wcc').val('');
		$('#ros_wcc_old').val('');
		$("#ros_wcc_dialog").dialog('close');
	});
	
	$('#button_ros_gen').button();
	$('#button_ros_eye').button();
	$('#button_ros_ent').button();
	$('#button_ros_resp').button();
	$('#button_ros_cv').button();
	$('#button_ros_gi').button();
	$('#button_ros_gu').button();
	$('#button_ros_mus').button();
	$('#button_ros_neuro').button();
	$('#button_ros_psych').button();
	$('#button_ros_heme').button();
	$('#button_ros_endocrine').button();
	$('#button_ros_skin').button();
	$('#button_ros_wcc').button();
	$('#save_ros_gen').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_eye').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_ent').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_resp').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_cv').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_gi').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_gu').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_mus').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_neuro').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_psych').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_heme').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_endocrine').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_skin').button({icons: {primary: "ui-icon-disk"},});
	$('#save_ros_wcc').button({icons: {primary: "ui-icon-disk"},});
	$('#cancel_ros_gen').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_eye').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_ent').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_resp').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_cv').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_gi').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_gu').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_mus').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_neuro').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_psych').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_heme').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_endocrine').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_skin').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_ros_wcc').button({icons: {primary: "ui-icon-close"},});
</script>
