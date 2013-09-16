<form id="encounters_pe">
	<table>
		<tr>
			<td valign="top">
				<table>
					<tr>
						<td><div id="button_pe_gen_status" class="pe_tooltip"><?php echo $gen_status;?></div></td>
						<td><input type="hidden" id="num_pe_gen" value="1"/><input type="button" id="button_pe_gen" value="General" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_eye_status" class="pe_tooltip"><?php echo $eye_status;?></div></td>
						<td><input type="hidden" id="num_pe_eye" value="3"/><input type="button" id="button_pe_eye" value="Eye" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_ent_status" class="pe_tooltip"><?php echo $ent_status;?></div></td>
						<td><input type="hidden" id="num_pe_ent" value="6"/><input type="button" id="button_pe_ent" value="Ear, Nose, and Throat" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_neck_status" class="pe_tooltip"><?php echo $neck_status;?></div></td>
						<td><input type="hidden" id="num_pe_neck" value="2"/><input type="button" id="button_pe_neck" value="Neck" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_resp_status" class="pe_tooltip"><?php echo $resp_status;?></div></td>
						<td><input type="hidden" id="num_pe_resp" value="4"/><input type="button" id="button_pe_resp" value="Respiratory" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_cv_status" class="pe_tooltip"><?php echo $cv_status;?></div></td>
						<td><input type="hidden" id="num_pe_cv" value="6"/><input type="button" id="button_pe_cv" value="Cardiovascular" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_ch_status" class="pe_tooltip"><?php echo $ch_status;?></div></td>
						<td><input type="hidden" id="num_pe_ch" value="2"/><input type="button" id="button_pe_ch" value="Chest" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					</table>
			</td>
			<td valign="top">
				<table>	
					<tr>
						<td><div id="button_pe_gi_status" class="pe_tooltip"><?php echo $gi_status;?></div></td>
						<td><input type="hidden" id="num_pe_gi" value="4"/><input type="button" id="button_pe_gi" value="Gastrointestinal" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_gu_status" class="pe_tooltip"><?php echo $gu_status;?></div></td>
						<td><input type="hidden" id="num_pe_gu" value="9"/><input type="button" id="button_pe_gu" value="Genitourinary" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_lymph_status" class="pe_tooltip"><?php echo $lymph_status;?></div></td>
						<td><input type="hidden" id="num_pe_lymph" value="3"/><input type="button" id="button_pe_lymph" value="Lymphatic" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_ms_status" class="pe_tooltip"><?php echo $ms_status;?></div></td>
						<td><input type="hidden" id="num_pe_ms" value="12"/><input type="button" id="button_pe_ms" value="Musculoskeletal" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_neuro_status" class="pe_tooltip"><?php echo $neuro_status;?></div></td>
						<td><input type="hidden" id="num_pe_neuro" value="3"/><input type="button" id="button_pe_neuro" value="Neurological" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_psych_status" class="pe_tooltip"><?php echo $psych_status;?></div></td>
						<td><input type="hidden" id="num_pe_psych" value="4"/><input type="button" id="button_pe_psych" value="Psychological" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_pe_skin_status" class="pe_tooltip"><?php echo $skin_status;?></div></td>
						<td><input type="hidden" id="num_pe_skin" value="2"/><input type="button" id="button_pe_skin" value="Skin" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<div id="pe_gen_dialog" title="General">
	<button type="button" id="save_pe_gen">Save</button>
	<button type="button" id="cancel_pe_gen">Cancel</button>
	<input type="checkbox" id="pe_gen_normal" class="all_normal1" value=""><label for="pe_gen_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_gen_accordion">
		<h3><a href="#"><span id="pe_gen1_h"></span>General</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_gen1" id="pe_gen1" class="pe_entry ui-widget-content text ui-corner-all"></textarea><input type="hidden" id="pe_gen1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_gen1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_gen1_normal" class="all_normal" value=""><label for="pe_gen1_normal">All Normal</label><button type="button" id="pe_gen1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gen1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
			
		</div>
	</div>
</div>			
<div id="pe_eye_dialog" title="Eye">
	<button type="button" id="save_pe_eye">Save</button>
	<button type="button" id="cancel_pe_eye">Cancel</button>
	<input type="checkbox" id="pe_eye_normal" class="all_normal1" value=""><label for="pe_eye_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_eye_accordion">
		<h3><a href="#"><span id="pe_eye1_h"></span>Conjunctiva and Lids</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_eye1" id="pe_eye1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_eye1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_eye1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_eye1_normal" class="all_normal" value=""><label for="pe_eye1_normal">All Normal</label><button type="button" id="pe_eye1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_eye1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
			
		</div>
		<h3><a href="#"><span id="pe_eye2_h"></span>Pupil and Iris</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_eye2" id="pe_eye2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_eye2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_eye2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_eye2_normal" class="all_normal" value=""><label for="pe_eye2_normal">All Normal</label><button type="button" id="pe_eye2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_eye2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_eye3_h"></span>Fundoscopic</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_eye3" id="pe_eye3" class="pe_entry text ui-widget ui-corner-all"></textarea><input type="hidden" id="pe_eye3_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_eye3_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_eye3_normal" class="all_normal" value=""><label for="pe_eye3_normal">All Normal</label><button type="button" id="pe_eye3_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_eye3_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_ent_dialog" title="Ears, Nose, Throat">
	<button type="button" id="save_pe_ent">Save</button>
	<button type="button" id="cancel_pe_ent">Cancel</button>
	<input type="checkbox" id="pe_ent_normal" class="all_normal1" value=""><label for="pe_ent_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_ent_accordion">
		<h3><a href="#"><span id="pe_ent1_h"></span>External Ear and Nose</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ent1" id="pe_ent1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ent1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ent1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ent1_normal" class="all_normal" value=""><label for="pe_ent1_normal">All Normal</label><button type="button" id="pe_ent1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ent2_h"></span>Canals and Tympanic Membrane</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ent2" id="pe_ent2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ent2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ent2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ent2_normal" class="all_normal" value=""><label for="pe_ent2_normal">All Normal</label><button type="button" id="pe_ent2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ent3_h"></span>Hearing Assessment</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ent3" id="pe_ent3" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ent3_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ent3_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ent3_normal" class="all_normal" value=""><label for="pe_ent3_normal">All Normal</label><button type="button" id="pe_ent3_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent3_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ent4_h"></span>Sinuses, Mucosa, Septum, and Turbinates</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ent4" id="pe_ent4" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ent4_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ent4_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ent4_normal" class="all_normal" value=""><label for="pe_ent4_normal">All Normal</label><button type="button" id="pe_ent4_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent4_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ent5_h"></span>Lips, Teeth, and Gums</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ent5" id="pe_ent5" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ent5_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ent5_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ent5_normal" class="all_normal" value=""><label for="pe_ent5_normal">All Normal</label><button type="button" id="pe_ent5_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent5_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ent6_h"></span>Oropharynx</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ent6" id="pe_ent6" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ent6_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ent6_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ent6_normal" class="all_normal" value=""><label for="pe_ent6_normal">All Normal</label><button type="button" id="pe_ent6_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ent6_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_neck_dialog" title="Neck">
	<button type="button" id="save_pe_neck">Save</button>
	<button type="button" id="cancel_pe_neck">Cancel</button>
	<input type="checkbox" id="pe_neck_normal" class="all_normal1" value=""><label for="pe_neck_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_neck_accordion">
		<h3><a href="#"><span id="pe_neck1_h"></span>General</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_neck1" id="pe_neck1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_neck1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_neck1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_neck1_normal" class="all_normal" value=""><label for="pe_neck1_normal">All Normal</label><button type="button" id="pe_neck1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_neck1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_neck2_h"></span>Thyroid</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_neck2" id="pe_neck2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_neck2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_neck2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_neck2_normal" class="all_normal" value=""><label for="pe_neck2_normal">All Normal</label><button type="button" id="pe_neck2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_neck2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_resp_dialog" title="Respiratory">
	<button type="button" id="save_pe_resp">Save</button>
	<button type="button" id="cancel_pe_resp">Cancel</button>
	<input type="checkbox" id="pe_resp_normal" class="all_normal1" value=""><label for="pe_resp_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_resp_accordion">
		<h3><a href="#"><span id="pe_resp1_h"></span>Effort</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_resp1" id="pe_resp1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_resp1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_resp1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_resp1_normal" class="all_normal" value=""><label for="pe_resp1_normal">All Normal</label><button type="button" id="pe_resp1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_resp1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_resp2_h"></span>Percussion</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_resp2" id="pe_resp2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_resp2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_resp2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_resp2_normal" class="all_normal" value=""><label for="pe_resp2_normal">All Normal</label><button type="button" id="pe_resp2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_resp2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_resp3_h"></span>Palpation</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_resp3" id="pe_resp3" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_resp3_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_resp3_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_resp3_normal" class="all_normal" value=""><label for="pe_resp3_normal">All Normal</label><button type="button" id="pe_resp3_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_resp3_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_resp4_h"></span>Auscultation</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_resp4" id="pe_resp4" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_resp4_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_resp4_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_resp4_normal" class="all_normal" value=""><label for="pe_resp4_normal">All Normal</label><button type="button" id="pe_resp4_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_resp4_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_cv_dialog" title="Cardiovascular">
	<button type="button" id="save_pe_cv">Save</button>
	<button type="button" id="cancel_pe_cv">Cancel</button>
	<input type="checkbox" id="pe_cv_normal" class="all_normal1" value=""><label for="pe_cv_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_cv_accordion">
		<h3><a href="#"><span id="pe_cv1_h"></span>Palpation</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_cv1" id="pe_cv1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_cv1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_cv1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_cv1_normal" class="all_normal" value=""><label for="pe_cv1_normal">All Normal</label><button type="button" id="pe_cv1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_cv2_h"></span>Auscultation</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_cv2" id="pe_cv2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_cv2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_cv2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_cv2_normal" class="all_normal" value=""><label for="pe_cv2_normal">All Normal</label><button type="button" id="pe_cv2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_cv3_h"></span>Carotid Arteries</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_cv3" id="pe_cv3" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_cv3_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_cv3_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_cv3_normal" class="all_normal" value=""><label for="pe_cv3_normal">All Normal</label><button type="button" id="pe_cv3_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv3_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_cv4_h"></span>Abdominal Aorta</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_cv4" id="pe_cv4" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_cv4_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_cv4_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_cv4_normal" class="all_normal" value=""><label for="pe_cv4_normal">All Normal</label><button type="button" id="pe_cv4_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv4_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_cv5_h"></span>Femoral Arteries</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_cv5" id="pe_cv5" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_cv5_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_cv5_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_cv5_normal" class="all_normal" value=""><label for="pe_cv5_normal">All Normal</label><button type="button" id="pe_cv5_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv5_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_cv6_h"></span>Extremities</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_cv6" id="pe_cv6" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_cv6_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_cv6_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_cv6_normal" class="all_normal" value=""><label for="pe_cv6_normal">All Normal</label><button type="button" id="pe_cv6_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_cv6_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
			
		</div>
	</div>
</div>
<div id="pe_ch_dialog" title="Chest">
	<button type="button" id="save_pe_ch">Save</button>
	<button type="button" id="cancel_pe_ch">Cancel</button>
	<input type="checkbox" id="pe_ch_normal" class="all_normal1" value=""><label for="pe_ch_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_ch_accordion">
		<h3><a href="#"><span id="pe_ch1_h"></span>Inspection</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ch1" id="pe_ch1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ch1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ch1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ch1_normal" class="all_normal" value=""><label for="pe_ch1_normal">All Normal</label><button type="button" id="pe_ch1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ch1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ch2_h"></span>Palpation</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ch2" id="pe_ch2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ch2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ch2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ch2_normal" class="all_normal" value=""><label for="pe_ch2_normal">All Normal</label><button type="button" id="pe_ch2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ch2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_gi_dialog" title="Gastrointestinal">
	<button type="button" id="save_pe_gi">Save</button>
	<button type="button" id="cancel_pe_gi">Cancel</button>
	<input type="checkbox" id="pe_gi_normal" class="all_normal1" value=""><label for="pe_gi_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_gi_accordion">
		<h3><a href="#"><span id="pe_gi1_h"></span>Masses and Tenderness</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_gi1" id="pe_gi1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_gi1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_gi1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_gi1_normal" class="all_normal" value=""><label for="pe_gi1_normal">All Normal</label><button type="button" id="pe_gi1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gi1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_gi2_h"></span>Liver and Spleen</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_gi2" id="pe_gi2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_gi2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_gi2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_gi2_normal" class="all_normal" value=""><label for="pe_gi2_normal">All Normal</label><button type="button" id="pe_gi2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gi2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_gi3_h"></span>Hernia</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_gi3" id="pe_gi3" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_gi3_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_gi3_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_gi3_normal" class="all_normal" value=""><label for="pe_gi3_normal">All Normal</label><button type="button" id="pe_gi3_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gi3_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_gi4_h"></span>Anus, Perineum, and Rectum</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_gi4" id="pe_gi4" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_gi4_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_gi4_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_gi4_normal" class="all_normal" value=""><label for="pe_gi4_normal">All Normal</label><button type="button" id="pe_gi4_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gi4_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_gu_dialog" title="Genitourinary">
	<button type="button" id="save_pe_gu">Save</button>
	<button type="button" id="cancel_pe_gu">Cancel</button>
	<input type="checkbox" id="pe_gu_normal" class="all_normal1" value=""><label for="pe_gu_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_gu_accordion">
		<h3><a href="#"><span id="pe_gu7_h"></span>Scrotum</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="8" name="pe_gu7" id="pe_gu7" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_gu7_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_gu7_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_gu7_normal" class="all_normal" value=""><label for="pe_gu7_normal">All Normal</label><button type="button" id="pe_gu7_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gu7_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_gu8_h"></span>Penis</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="8" name="pe_gu8" id="pe_gu8" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_gu8_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_gu8_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_gu8_normal" class="all_normal" value=""><label for="pe_gu8_normal">All Normal</label><button type="button" id="pe_gu8_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gu8_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_gu9_h"></span>Prostate</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="8" name="pe_gu9" id="pe_gu9" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_gu9_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_gu9_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_gu9_normal" class="all_normal" value=""><label for="pe_gu9_normal">All Normal</label><button type="button" id="pe_gu9_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_gu9_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_lymph_dialog" title="Lymphatic">
	<button type="button" id="save_pe_lymph">Save</button>
	<button type="button" id="cancel_pe_lymph">Cancel</button>
	<input type="checkbox" id="pe_lymph_normal" class="all_normal1" value=""><label for="pe_lymph_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_lymph_accordion">
		<h3><a href="#"><span id="pe_lymph1_h"></span>Neck</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_lymph1" id="pe_lymph1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_lymph1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_lymph1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_lymph1_normal" class="all_normal" value=""><label for="pe_lymph1_normal">All Normal</label><button type="button" id="pe_lymph1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_lymph1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_lymph2_h"></span>Axillae</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_lymph2" id="pe_lymph2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_lymph2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_lymph2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_lymph2_normal" class="all_normal" value=""><label for="pe_lymph2_normal">All Normal</label><button type="button" id="pe_lymph2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_lymph2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_lymph3_h"></span>Groin</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_lymph3" id="pe_lymph3" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_lymph3_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_lymph3_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_lymph3_normal" class="all_normal" value=""><label for="pe_lymph3_normal">All Normal</label><button type="button" id="pe_lymph3_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_lymph3_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_ms_dialog" title="Musculoskeletal">
	<button type="button" id="save_pe_ms">Save</button>
	<button type="button" id="cancel_pe_ms">Cancel</button>
	<input type="checkbox" id="pe_ms_normal" class="all_normal1" value=""><label for="pe_ms_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_ms_accordion">
		<h3><a href="#"><span id="pe_ms1_h"></span>Gait and Station</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms1" id="pe_ms1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms1_normal" class="all_normal" value=""><label for="pe_ms1_normal">All Normal</label><button type="button" id="pe_ms1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms2_h"></span>Digits and Nails</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms2" id="pe_ms2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms2_normal" class="all_normal" value=""><label for="pe_ms2_normal">All Normal</label><button type="button" id="pe_ms2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms3_h"></span>Bones, Joints, and Muscles - Shoulder</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms3" id="pe_ms3" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms3_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms3_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms3_normal" class="all_normal" value=""><label for="pe_ms3_normal">All Normal</label><input type="checkbox" id="pe_ms3_normal1" class="all_normal2" value="Full range of motion of the shoulders bilaterally."><label for="pe_ms3_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms3_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms3_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms4_h"></span>Bones, Joints, and Muscles - Elbow</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms4" id="pe_ms4" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms4_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms4_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms4_normal" class="all_normal" value=""><label for="pe_ms4_normal">All Normal</label><input type="checkbox" id="pe_ms4_normal1" class="all_normal2" value="Full range of motion of the elbows bilaterally."><label for="pe_ms4_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms4_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms4_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms5_h"></span>Bones, Joints, and Muscles - Wrist</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms5" id="pe_ms5" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms5_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms5_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms5_normal" class="all_normal" value=""><label for="pe_ms5_normal">All Normal</label><input type="checkbox" id="pe_ms5_normal1" class="all_normal2" value="Full range of motion of the wrists bilaterally."><label for="pe_ms5_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms5_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms5_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms6_h"></span>Bones, Joints, and Muscles - Hand</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms6" id="pe_ms6" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms6_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms6_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms6_normal" class="all_normal" value=""><label for="pe_ms6_normal">All Normal</label><input type="checkbox" id="pe_ms6_normal1" class="all_normal2" value="Full range of motion of the fingers and hands bilaterally."><label for="pe_ms6_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms6_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms6_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms7_h"></span>Bones, Joints, and Muscles - Hip</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms7" id="pe_ms7" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms7_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms7_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms7_normal" class="all_normal" value=""><label for="pe_ms7_normal">All Normal</label><input type="checkbox" id="pe_ms7_normal1" class="all_normal2" value="Full range of motion of the hips bilaterally."><label for="pe_ms7_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms7_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms7_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms8_h"></span>Bones, Joints, and Muscles - Knee</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms8" id="pe_ms8" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms8_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms8_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms8_normal" class="all_normal" value=""><label for="pe_ms8_normal">All Normal</label><input type="checkbox" id="pe_ms8_normal1" class="all_normal2" value="Full range of motion of the knees bilaterally."><label for="pe_ms8_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms8_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms8_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms9_h"></span>Bones, Joints, and Muscles - Ankle</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms9" id="pe_ms9" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms9_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms9_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms9_normal" class="all_normal" value=""><label for="pe_ms9_normal">All Normal</label><input type="checkbox" id="pe_ms9_normal1" class="all_normal2" value="Full range of motion of the ankles bilaterally."><label for="pe_ms9_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms9_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms9_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms10_h"></span>Bones, Joints, and Muscles - Foot</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms10" id="pe_ms10" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms10_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms10_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms10_normal" class="all_normal" value=""><label for="pe_ms10_normal">All Normal</label><input type="checkbox" id="pe_ms10_normal1" class="all_normal2" value="Full range of motion of the toes and feet bilaterally."><label for="pe_ms10_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms10_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms10_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms11_h"></span>Bones, Joints, and Muscles - Cervical Spine</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms11" id="pe_ms11" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms11_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms11_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms11_normal" class="all_normal" value=""><label for="pe_ms11_normal">All Normal</label><input type="checkbox" id="pe_ms11_normal1" class="all_normal2" value="Full range of motion of the cervical spine."><label for="pe_ms11_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms11_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms11_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_ms12_h"></span>Bones, Joints, and Muscles - Thoracic and Lumbar Spine</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_ms12" id="pe_ms12" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_ms12_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_ms12_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_ms12_normal" class="all_normal" value=""><label for="pe_ms12_normal">All Normal</label><input type="checkbox" id="pe_ms12_normal1" class="all_normal2" value="Full range of motion of the thoracic and lumbar spine."><label for="pe_ms12_normal1">All Normal Range of Motion</label><button type="button" id="pe_ms12_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_ms12_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_neuro_dialog" title="Neurological">
	<button type="button" id="save_pe_neuro">Save</button>
	<button type="button" id="cancel_pe_neuro">Cancel</button>
	<input type="checkbox" id="pe_neuro_normal" class="all_normal1" value=""><label for="pe_neuro_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_neuro_accordion">
		<h3><a href="#"><span id="pe_neuro1_h"></span>Cranial Nerves</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_neuro1" id="pe_neuro1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_neuro1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_neuro1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_neuro1_normal" class="all_normal" value=""><label for="pe_neuro1_normal">All Normal</label><button type="button" id="pe_neuro1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_neuro1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_neuro2_h"></span>Deep Tendon Reflexes</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_neuro2" id="pe_neuro2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_neuro2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_neuro2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_neuro2_normal" class="all_normal" value=""><label for="pe_neuro2_normal">All Normal</label><input type="checkbox" id="pe_neuro2_normal1" class="all_normal2" value="Biceps, Patellar, and Achillies deep tendon reflexes are equal bilaterally."><label for="pe_neuro2_normal1">Equal and Bilateral</label><button type="button" id="pe_neuro2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_neuro2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_neuro3_h"></span>Sensation and Motor</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_neuro3" id="pe_neuro3" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_neuro3_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_neuro3_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_neuro3_normal" class="all_normal" value=""><label for="pe_neuro3_normal">All Normal</label><button type="button" id="pe_neuro3_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_neuro3_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_psych_dialog" title="Psychological">
	<button type="button" id="save_pe_psych">Save</button>
	<button type="button" id="cancel_pe_psych">Cancel</button>
	<input type="checkbox" id="pe_psych_normal" class="all_normal1" value=""><label for="pe_psych_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_psych_accordion">
		<h3><a href="#"><span id="pe_psych1_h"></span>Judgement and Insight</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_psych1" id="pe_psych1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_psych1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_psych1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_psych1_normal" class="all_normal" value=""><label for="pe_psych1_normal">All Normal</label><button type="button" id="pe_psych1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_psych1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_psych2_h"></span>Orientation</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_psych2" id="pe_psych2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_psych2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_psych2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_psych2_normal" class="all_normal" value=""><label for="pe_psych2_normal">All Normal</label><button type="button" id="pe_psych2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_psych2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_psych3_h"></span>Memory</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_psych3" id="pe_psych3" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_psych3_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_psych3_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_psych3_normal" class="all_normal" value=""><label for="pe_psych3_normal">All Normal</label><button type="button" id="pe_psych3_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_psych3_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_psych4_h"></span>Mood and Affect</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_psych4" id="pe_psych4" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_psych4_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_psych4_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_psych4_normal" class="all_normal" value=""><label for="pe_psych4_normal">All Normal</label><button type="button" id="pe_psych4_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_psych4_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="pe_skin_dialog" title="Skin">
	<button type="button" id="save_pe_skin">Save</button>
	<button type="button" id="cancel_pe_skin">Cancel</button>
	<input type="checkbox" id="pe_skin_normal" class="all_normal1" value=""><label for="pe_skin_normal">All Normal</label>
	<br><hr class="ui-state-default"/>
	<div id="pe_skin_accordion">
		<h3><a href="#"><span id="pe_skin1_h"></span>Inspection</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_skin1" id="pe_skin1" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_skin1_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_skin1_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_skin1_normal" class="all_normal" value=""><label for="pe_skin1_normal">All Normal</label><button type="button" id="pe_skin1_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_skin1_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
		<h3><a href="#"><span id="pe_skin2_h"></span>Palpation</a></h3>
		<div>
			<div style="display:block;float:left;width:310px">
				Preview:<br><textarea style="width:290px" rows="16" name="pe_skin2" id="pe_skin2" class="pe_entry text ui-widget-content ui-corner-all"></textarea><input type="hidden" id="pe_skin2_old"/>
			</div>
			<div style="display:block;float:left">
				Choose Template: <select id="pe_skin2_template" class="pe_template_choose text ui-widget-content ui-corner-all"></select><br>
				<br><input type="checkbox" id="pe_skin2_normal" class="all_normal" value=""><label for="pe_skin2_normal">All Normal</label><button type="button" id="pe_skin2_reset" class="reset">Clear</button>
				<div class="pe_template_div">
					<br><form id="pe_skin2_form" class="pe_template_form ui-widget"></form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="dform_pe_load" title="Loading...">
	<img src="<?php echo base_url().'images/indicator.gif';?>"> Loading templates.
</div>
<script type="text/javascript">
	$("#dform_pe_load").dialog({
		height: 100,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		modal: true
	});
	$("#dform_pe_load").dialog('open');
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/get_pe_templates');?>",
		dataType: "json",
		success: function(data){
			$('#pe_gen1_form').dform(data.pe_gen1);
			$('#pe_eye1_form').dform(data.pe_eye1);
			$('#pe_eye2_form').dform(data.pe_eye2);
			$('#pe_eye3_form').dform(data.pe_eye3);
			$('#pe_ent1_form').dform(data.pe_ent1);
			$('#pe_ent2_form').dform(data.pe_ent2);
			$('#pe_ent3_form').dform(data.pe_ent3);
			$('#pe_ent4_form').dform(data.pe_ent4);
			$('#pe_ent5_form').dform(data.pe_ent5);
			$('#pe_ent6_form').dform(data.pe_ent6);
			$('#pe_neck1_form').dform(data.pe_neck1);
			$('#pe_neck2_form').dform(data.pe_neck2);
			$('#pe_resp1_form').dform(data.pe_resp1);
			$('#pe_resp2_form').dform(data.pe_resp2);
			$('#pe_resp3_form').dform(data.pe_resp3);
			$('#pe_resp4_form').dform(data.pe_resp4);
			$('#pe_cv1_form').dform(data.pe_cv1);
			$('#pe_cv2_form').dform(data.pe_cv2);
			$('#pe_cv3_form').dform(data.pe_cv3);
			$('#pe_cv4_form').dform(data.pe_cv4);
			$('#pe_cv5_form').dform(data.pe_cv5);
			$('#pe_cv6_form').dform(data.pe_cv6);
			$('#pe_ch1_form').dform(data.pe_ch1);
			$('#pe_ch2_form').dform(data.pe_ch2);
			$('#pe_gi1_form').dform(data.pe_gi1);
			$('#pe_gi2_form').dform(data.pe_gi2);
			$('#pe_gi3_form').dform(data.pe_gi3);
			$('#pe_gi4_form').dform(data.pe_gi4);
			//$('#pe_gu1_form').dform(data.pe_gu1);
			//$('#pe_gu2_form').dform(data.pe_gu2);
			//$('#pe_gu3_form').dform(data.pe_gu3);
			//$('#pe_gu4_form').dform(data.pe_gu4);
			//$('#pe_gu5_form').dform(data.pe_gu5);
			//$('#pe_gu6_form').dform(data.pe_gu6);
			$('#pe_gu7_form').dform(data.pe_gu7);
			$('#pe_gu8_form').dform(data.pe_gu8);
			$('#pe_gu9_form').dform(data.pe_gu9);
			$('#pe_lymph1_form').dform(data.pe_lymph1);
			$('#pe_lymph2_form').dform(data.pe_lymph2);
			$('#pe_lymph3_form').dform(data.pe_lymph3);
			$('#pe_ms1_form').dform(data.pe_ms1);
			$('#pe_ms2_form').dform(data.pe_ms2);
			$('#pe_ms3_form').dform(data.pe_ms3);
			$('#pe_ms4_form').dform(data.pe_ms4);
			$('#pe_ms5_form').dform(data.pe_ms5);
			$('#pe_ms6_form').dform(data.pe_ms6);
			$('#pe_ms7_form').dform(data.pe_ms7);
			$('#pe_ms8_form').dform(data.pe_ms8);
			$('#pe_ms9_form').dform(data.pe_ms9);
			$('#pe_ms10_form').dform(data.pe_ms10);
			$('#pe_ms11_form').dform(data.pe_ms11);
			$('#pe_ms12_form').dform(data.pe_ms12);
			$('#pe_neuro1_form').dform(data.pe_neuro1);
			$('#pe_neuro2_form').dform(data.pe_neuro2);
			$('#pe_neuro3_form').dform(data.pe_neuro3);
			$('#pe_psych1_form').dform(data.pe_psych1);
			$('#pe_psych2_form').dform(data.pe_psych2);
			$('#pe_psych3_form').dform(data.pe_psych3);
			$('#pe_psych4_form').dform(data.pe_psych4);
			$('#pe_skin1_form').dform(data.pe_skin1);
			$('#pe_skin2_form').dform(data.pe_skin2);
			$('.pe_buttonset').buttonset();
			$('.pe_detail_text').hide();
			$('.pe_template_div input[type="checkbox"]').change(function() {
				var parent_id = $(this).attr("id");
				var parts = parent_id.split('_');
				var parent_id_entry = parts[0] + '_' + parts[1];
				var old = $("#" + parent_id_entry).val();
				var a = $(this).val();
				if ($(this).is(':checked')) {
					if (old != '') {
						var b = old + '  ' + a;
					} else {
						var b = a;
					}
					$("#" + parent_id_entry).val(b); 
				} else {
					var a1 = '  ' + a;
					var c = old.replace(a1,'');
					c = c.replace(a, '');
					$("#" + parent_id_entry).val(c); 
				}
			});
			$('.pe_template_div input[type="text"]').focusin(function() {
				old_text = $(this).val();
			});
			$('.pe_template_div input[type="text"]').focusout(function() {
				var a = $(this).val();
				if (a != '') {
					var parent_id = $(this).attr("id");
					var parts = parent_id.split('_');
					var parent_id_entry = parts[0] + '_' + parts[1];
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
			$('.pe_detail').click(function() {
				var detail_id = $(this).attr("id") + '_detail';
				if ($(this).is(':checked')) {
					$('#' + detail_id).show('fast');
					$('#' + detail_id).focus();
				} else {
					var parent_id = $(this).attr("id");
					var parts = parent_id.split('_');
					var parent_id_entry = parts[0] + '_' + parts[1];
					var old = $("#" + parent_id_entry).val();
					if ($('#' + detail_id).val() != '') {
						var text_pointer = $('#' + detail_id).val().length - 1;
						var text_pointer2 = $('#' + detail_id).val().lastIndexOf('.');
						if (text_pointer != text_pointer2) {
							var text1 = $('#' + detail_id).val() + '.';
						} else {
							var text1 = $('#' + detail_id).val();
						}
						var a = ' ' + text1;
						var a1 = a + '  ';
						var c = old.replace(a1,'');
						c = c.replace(a, '');
						$("#" + parent_id_entry).val(c);
					}
					$('#' + detail_id).val('');
					$('#' + detail_id).hide('fast');
				}
			});
			$('.pe_normal').click(function() {
				if ($(this).is(':checked')) {
					var parent_id = $(this).attr("id");
					var x = parent_id.length - 1;
					parent_id = parent_id.slice(0,x);
					$("#" + parent_id + "_div").find('.pe_other:checkbox').each(function(){
						var parent_id = $(this).attr("id");
						$(this).prop('checked',false);
						var parts = parent_id.split('_');
						var parent_id_entry = parts[0] + '_' + parts[1];
						var old = $("#" + parent_id_entry).val();
						var a = $(this).val();
						var a1 = a + '  ';
						var c = old.replace(a1,'');
						c = c.replace(a, '');
						$("#" + parent_id_entry).val(c);
						$("#" + parent_id_entry + "_form input").button('refresh');
					});
					$("#" + parent_id + "_div").find('.pe_detail_text').each(function(){
						var parent_id = $(this).attr("id");
						var parts = parent_id.split('_');
						var parent_id_entry = parts[0] + '_' + parts[1];
						var old = $("#" + parent_id_entry).val();
						if ($(this).val() != '') {
							var text_pointer = $(this).val().length - 1;
							var text_pointer2 = $(this).val().lastIndexOf('.');
							if (text_pointer != text_pointer2) {
								var text1 = $(this).val() + '.';
							} else {
								var text1 = $(this).val();
							}
							var a = ' ' + text1;
							var a1 = a + '  ';
							var c = old.replace(a1,'');
							c = c.replace(a, '');
							$("#" + parent_id_entry).val(c);
						}
						$(this).val('');
						$(this).hide();
					});
				}
			});
			$('.pe_other').click(function() {
				if ($(this).is(':checked')) {
					var parent_id = $(this).attr("id");
					var x = parent_id.length - 1;
					parent_id = parent_id.slice(0,x);
					$("#" + parent_id + "_div").find('.pe_normal:checkbox').each(function(){
						var parent_id = $(this).attr("id");
						$(this).prop('checked',false);
						var parts = parent_id.split('_');
						var parent_id_entry = parts[0] + '_' + parts[1];
						var old = $("#" + parent_id_entry).val();
						var a = $(this).val();
						var a1 = a + '  ';
						var c = old.replace(a1,'');
						c = c.replace(a, '');
						$("#" + parent_id_entry).val(c);
						$("#" + parent_id_entry + "_form input").button('refresh');
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
				var a = $(this).is(':checked');
				var parent_id = $(this).attr("id");
				var n = parent_id.lastIndexOf('_');
				var parent_id_entry = parent_id.substring(0,n);
				if(a){
					$("#" + parent_id_entry + "_form").find("input.pe_normal:checkbox").each(function(){
						$(this).prop("checked",true);
					});
					updateTextArea(parent_id_entry);
				} else {
					$("#" + parent_id_entry).val('');
					$("#" + parent_id_entry + "_form").find('input.pe_normal:checkbox').each(function(){
						$(this).prop("checked",false);
					});
				}
				$("#" + parent_id_entry + '_form input[type="checkbox"]').button('refresh');
			});
			$('.all_normal1').button().click(function(){
				var a = $(this).is(':checked');
				var parent_id = $(this).attr("id");
				var parent_id_entry = parent_id.replace('normal','dialog');
				if(a){
					$("#" + parent_id_entry).find(".all_normal").each(function(){
						$(this).prop("checked",true);
						var parent_id1 = $(this).attr("id");
						var n1 = parent_id1.lastIndexOf('_');
						var parent_id_entry1 = parent_id1.substring(0,n1);
						$("#" + parent_id_entry1 + "_form").find("input.pe_normal:checkbox").each(function(){
							$(this).prop("checked",true);
						});
						updateTextArea(parent_id_entry1);
						$("#" + parent_id_entry1 + '_form input[type="checkbox"]').button('refresh');
					}).button('refresh');
					$("#" + parent_id_entry).find(".all_normal2").each(function(){
						$(this).prop("checked",true);
						var parent_id2 = $(this).attr("id");
						var parent_id_entry2 = parent_id2.replace('_normal1','');
						var old2 = $("#" + parent_id_entry2).val();
						var a2 = $(this).val();
						if (old2 != '') {
							var b2 = old2 + '  ' + a2;
						} else {
							var b2 = a2;
						}
						$("#" + parent_id_entry2).val(b2); 
					}).button('refresh');
				} else {
					$("#" + parent_id_entry).find(".all_normal").each(function(){
						$(this).prop("checked",false);
						var parent_id2 = $(this).attr("id");
						var n2 = parent_id2.lastIndexOf('_');
						var parent_id_entry2 = parent_id2.substring(0,n2);
						$("#" + parent_id_entry2).val('');
						$("#" + parent_id_entry2 + "_form").find('input.pe_normal:checkbox').each(function(){
							$(this).prop("checked",false);
						});
						$("#" + parent_id_entry2 + '_form input[type="checkbox"]').button('refresh');
					}).button('refresh');
					$("#" + parent_id_entry).find(".all_normal2").each(function(){
						$(this).prop("checked",true);
						var parent_id2 = $(this).attr("id");
						var parent_id_entry2 = parent_id2.replace('_normal1','');
						var old2 = $("#" + parent_id_entry2).val();
						var a2 = $(this).val();
						var a3 = '  ' + a2;
						var c2 = old2.replace(a3,'');
						c2 = c2.replace(a2, '');
						$("#" + parent_id_entry2).val(c2); 
					}).button('refresh');
				}
			});
			$(".all_normal2").button().click(function(){
				var parent_id = $(this).attr("id");
				var parent_id_entry = parent_id.replace('_normal1','');
				var old = $("#" + parent_id_entry).val();
				var a = $(this).val();
				if ($(this).is(':checked')) {
					if (old != '') {
						var b = old + '  ' + a;
					} else {
						var b = a;
					}
					$("#" + parent_id_entry).val(b); 
				} else {
					var a1 = '  ' + a;
					var c = old.replace(a1,'');
					c = c.replace(a, '');
					$("#" + parent_id_entry).val(c); 
				}
			});
			$("#dform_pe_load").dialog('close');
		}
	});
	$('.reset').button().click(function(){
		var parent_id = $(this).attr("id");
		var n = parent_id.lastIndexOf('_');
		parent_id = parent_id.substring(0,n);
		$("#" + parent_id).val('');
	});
	$('.pe_template_choose').each(function(){
		var parent_id = $(this).attr("id");
		var n = parent_id.lastIndexOf('_');
		parent_id = parent_id.substring(0,n);
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_pe_templates_list');?>" + "/" + parent_id,
			dataType: "json",
			success: function(data){
				$('#' + data.select_id).addOption(data.options);
			}
		});
	}).change(function (){
		var parent_id = $(this).attr("id");
		var n = parent_id.lastIndexOf('_');
		parent_id = parent_id.substring(0,n);
		
	});
	
	function check_pe_status() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/check_pe');?>",
			dataType: "json",
			success: function(data){
				$.jGrowl(data.message);
				$('#button_pe_gen_status').html(data.gen);
				$('#button_pe_eye_status').html(data.eye);
				$('#button_pe_ent_status').html(data.ent);
				$('#button_pe_neck_status').html(data.neck);
				$('#button_pe_resp_status').html(data.resp);
				$('#button_pe_cv_status').html(data.cv);
				$('#button_pe_ch_status').html(data.ch);
				$('#button_pe_gi_status').html(data.gi);
				$('#button_pe_gu_status').html(data.gu);
				$('#button_pe_lymph_status').html(data.lymph);
				$('#button_pe_ms_status').html(data.ms);
				$('#button_pe_neuro_status').html(data.neuro);
				$('#button_pe_psych_status').html(data.psych);
				$('#button_pe_skin_status').html(data.skin);
			}
		});
	}
	
	$("#pe_gen_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_gen_accordion").accordion({
				change: function(event, ui) {
					$("#pe_gen_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			
			$("#pe_gen_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + '_old').val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_gen1").val();
			var a0 = $("#pe_gen1_old").val();
			if(a != '' && a != a0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#pe_eye_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_eye_accordion").accordion({
				change: function(event, ui) {
					$("#pe_eye_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_eye_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_eye1").val();
			var a0 = $("#pe_eye1_old").val();
			var b = $("#pe_eye2").val();
			var b0 = $("#pe_eye2_old").val();
			var c = $("#pe_eye3").val();
			var c0 = $("#pe_eye3_old").val();
			if(a != '' && a != a0 && b != '' && b != b0 && c != '' && c != c0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}	
	});
	$("#pe_ent_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_ent_accordion").accordion({
				change: function(event, ui) {
					$("#pe_ent_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_ent_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_ent1").val();
			var a0 = $("#pe_ent1_old").val();
			var b = $("#pe_ent2").val();
			var b0 = $("#pe_ent2_old").val();
			var c = $("#pe_ent3").val();
			var c0 = $("#pe_ent3_old").val();
			var d = $("#pe_ent4").val();
			var d0 = $("#pe_ent4_old").val();
			var e = $("#pe_ent5").val();
			var e0 = $("#pe_ent5_old").val();
			var f = $("#pe_ent6").val();
			var f0 = $("#pe_ent6_old").val();
			if(a != '' && a != a0 && b != '' && b != b0 && c != '' && c != c0 && d != '' && d != d0 && e != '' && e != e0 && f != '' && f != f0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#pe_neck_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_neck_accordion").accordion({
				change: function(event, ui) {
					$("#pe_neck_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_neck_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_neck1").val();
			var a0 = $("#pe_neck1_old").val();
			var b = $("#pe_neck2").val();
			var b0 = $("#pe_neck2_old").val();
			if(a != '' && a != a0 && b != '' && b != b0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#pe_resp_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_resp_accordion").accordion({
				change: function(event, ui) {
					$("#pe_resp_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_resp_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_resp1").val();
			var a0 = $("#pe_resp1_old").val();
			var b = $("#pe_resp2").val();
			var b0 = $("#pe_resp2_old").val();
			var c = $("#pe_resp3").val();
			var c0 = $("#pe_resp3_old").val();
			var d = $("#pe_resp4").val();
			var d0 = $("#pe_resp4_old").val();
			if(a != '' && a != a0 && b != '' && b != b0 && c != '' && c != c0 && d != '' && d != d0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#pe_cv_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_cv_accordion").accordion({
				change: function(event, ui) {
					$("#pe_cv_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_cv_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_cv1").val();
			var a0 = $("#pe_cv1_old").val();
			var b = $("#pe_cv2").val();
			var b0 = $("#pe_cv2_old").val();
			var c = $("#pe_cv3").val();
			var c0 = $("#pe_cv3_old").val();
			var d = $("#pe_cv4").val();
			var d0 = $("#pe_cv4_old").val();
			var e = $("#pe_cv5").val();
			var e0 = $("#pe_cv5_old").val();
			var f = $("#pe_cv6").val();
			var f0 = $("#pe_cv6_old").val();
			if(a != '' && a != a0 && b != '' && b != b0 && c != '' && c != c0 && d != '' && d != d0 && e != '' && e != e0 && f != '' && f != f0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}	
	});
	$("#pe_ch_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_ch_accordion").accordion({
				change: function(event, ui) {
					$("#pe_ch_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_ch_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_ch1").val();
			var a0 = $("#pe_ch1_old").val();
			var b = $("#pe_ch2").val();
			var b0 = $("#pe_ch2_old").val();
			if(a != '' && a != a0 && b != '' && b != b0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#pe_gi_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_gi_accordion").accordion({
				change: function(event, ui) {
					$("#pe_gi_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_gi_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_gi1").val();
			var a0 = $("#pe_gi1_old").val();
			var b = $("#pe_gi2").val();
			var b0 = $("#pe_gi2_old").val();
			var c = $("#pe_gi3").val();
			var c0 = $("#pe_gi3_old").val();
			var d = $("#pe_gi4").val();
			var d0 = $("#pe_gi4_old").val();
			if(a != '' && a != a0 && b != '' && b != b0 && c != '' && c != c0 && d != '' && d != d0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#pe_gu_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_gu_accordion").accordion({
				change: function(event, ui) {
					$("#pe_gu_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_gu_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_gu1").val();
			var a0 = $("#pe_gu1_old").val();
			var b = $("#pe_gu2").val();
			var b0 = $("#pe_gu2_old").val();
			var c = $("#pe_gu3").val();
			var c0 = $("#pe_gu3_old").val();
			var d = $("#pe_gu4").val();
			var d0 = $("#pe_gu4_old").val();
			var e = $("#pe_gu5").val();
			var e0 = $("#pe_gu5_old").val();
			var f = $("#pe_gu6").val();
			var f0 = $("#pe_gu6_old").val();
			var g = $("#pe_gu7").val();
			var g0 = $("#pe_gu7_old").val();
			var h = $("#pe_gu8").val();
			var h0 = $("#pe_gu8_old").val();
			var i = $("#pe_gu9").val();
			var i0 = $("#pe_gu9_old").val();
			if(a != '' && a != a0 && b != '' && b != b0 && c != '' && c != c0 && d != '' && d != d0 && e != '' && e != e0 && f != '' && f != f0 && g != '' && g != g0 && h != '' && h != h0 && i != '' && i != i0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}	
	});
	$("#pe_lymph_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_lymph_accordion").accordion({
				change: function(event, ui) {
					$("#pe_lymph_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_lymph_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_lymph1").val();
			var a0 = $("#pe_lymph1_old").val();
			var b = $("#pe_lymph2").val();
			var b0 = $("#pe_lymph2_old").val();
			var c = $("#pe_lymph3").val();
			var c0 = $("#pe_lymph3_old").val();
			if(a != '' && a != a0 && b != '' && b != b0 && c != '' && c != c0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#pe_ms_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_ms_accordion").accordion({
				change: function(event, ui) {
					$("#pe_ms_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_ms_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_ms1").val();
			var a0 = $("#pe_ms1_old").val();
			var b = $("#pe_ms2").val();
			var b0 = $("#pe_ms2_old").val();
			var c = $("#pe_ms3").val();
			var c0 = $("#pe_ms3_old").val();
			var d = $("#pe_ms4").val();
			var d0 = $("#pe_ms4_old").val();
			var e = $("#pe_ms5").val();
			var e0 = $("#pe_ms5_old").val();
			var f = $("#pe_ms6").val();
			var f0 = $("#pe_ms6_old").val();
			var g = $("#pe_ms7").val();
			var g0 = $("#pe_ms7_old").val();
			var h = $("#pe_ms8").val();
			var h0 = $("#pe_ms8_old").val();
			var i = $("#pe_ms9").val();
			var i0 = $("#pe_ms9_old").val();
			var j = $("#pe_ms10").val();
			var j0 = $("#pe_ms10_old").val();
			var k = $("#pe_ms11").val();
			var k0 = $("#pe_ms11_old").val();
			var l = $("#pe_ms12").val();
			var l0 = $("#pe_ms12_old").val();
			if(a != '' && a != a0 && b != '' && b != b0 && c != '' && c != c0 && d != '' && d != d0 && e != '' && e != e0 && f != '' && f != f0 && g != '' && g != g0 && h != '' && h != h0 && i != '' && i != i0 && j != '' && j != j0 && k != '' && k != k0 && l != '' && l != l0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#pe_neuro_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_neuro_accordion").accordion({
				change: function(event, ui) {
					$("#pe_neuro_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_neuro_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_neuro1").val();
			var a0 = $("#pe_neuro1_old").val();
			var b = $("#pe_neuro2").val();
			var b0 = $("#pe_neuro2_old").val();
			var c = $("#pe_neuro3").val();
			var c0 = $("#pe_neuro3_old").val();
			if(a != '' && a != a0 && b != '' && b != b0 && c != '' && c != c0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#pe_psych_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_psych_accordion").accordion({
				change: function(event, ui) {
					$("#pe_psych_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_psych_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_psych1").val();
			var a0 = $("#pe_psych1_old").val();
			var b = $("#pe_psych2").val();
			var b0 = $("#pe_psych2_old").val();
			var c = $("#pe_psych3").val();
			var c0 = $("#pe_psych3_old").val();
			var d = $("#pe_psych4").val();
			var d0 = $("#pe_psych4_old").val();
			if(a != '' && a != a0 && b != '' && b != b0 && c != '' && c != c0 && d != '' && d != d0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#pe_skin_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#pe_skin_accordion").accordion({
				change: function(event, ui) {
					$("#pe_skin_dialog").find('.pe_entry').each(function(){
						var parent_id = $(this).attr("id");
						if (!!$(this).val()) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					});
				},
				heightStyle: "content"
			});
			$("#pe_skin_dialog").find('.pe_entry').each(function(){
				var parent_id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/get_pe');?>/" + parent_id,
					dataType: "json",
					success: function(data){
						$('#' + data.id).val(data.text);
						$('#' + data.id + "_old").val(data.text);
						if (!!data.text) {
							var img = '<img src="<?php echo base_url();?>/images/button_accept.png" border="0" height="15" width="15">'
						} else {
							var img = '<img src="<?php echo base_url();?>/images/cancel.png" border="0" height="15" width="15">'
						}
						$('#' + parent_id + '_h').html(img);
					}
				});
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#pe_skin1").val();
			var a0 = $("#pe_skin1_old").val();
			var b = $("#pe_skin2").val();
			var b0 = $("#pe_skin2_old").val();
			if(a != '' && a != a0 && b != '' && b != b0){
				if(confirm('Updates have not been saved.  Are you sure you want to close this window?  If not, press Cancel.')){ 
					check_pe_status();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$('.pe_tooltip').tooltip({
		items: ".pe_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		open: function(event, ui) {
			var elem = $(this);
			var id = $(this).attr("id");
			var parts = id.split('_');
			var id1 = parts[1] + "_" + parts[2];
			var idnum = $("#num_"+id1).val();
			var id2 = id1 + "/" + idnum;
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/tip_pe/');?>/" + id2,
				success: function(data) {
					elem.tooltip('option', 'content', data);
				},
			});
		}
	});
	//$('.pe_tooltip').tooltip({
	//	items: ".pe_tooltip",
	//	hide: false,
	//	show: false,
	//	content: function(callback){
	//		var id = $(this).attr("id");
	//		var parts = id.split('_');
	//		var id1 = parts[1] + "_" + parts[2];
	//		var idnum = $("#num_"+id1).val();
	//		var id2 = id1 + "/" + idnum;
	//		$.ajax({
	//			type: "POST",
	//			url: "<?php echo site_url('provider/encounters/tip_pe/');?>/" + id2,
	//			success: function(data){
	//				callback(data);
	//			}
	//		});
	//	}
	//}).off("mouseover").mouseenter(function(){
	//	$(this).tooltip('open');
	//}).hover(function(){
	//	$(this).tooltip('open');
	//}, function(){
	//	$(this).tooltip('close');
	//}).mouseleave(function(){
	//	$(this).tooltip('close');
	//});
	$("#button_pe_gen").button().click(function() {
		$("#pe_gen_dialog").dialog('open');
		$("#pe_gen1").focus();
	});
	$("#button_pe_eye").button().click(function() {
		$("#pe_eye_dialog").dialog('open');
		$("#pe_eye1").focus();
	});
	$("#button_pe_ent").button().click(function() {
		$("#pe_ent_dialog").dialog('open');
		$("#pe_ent1").focus();
	});
	$("#button_pe_neck").button().click(function() {
		$("#pe_neck_dialog").dialog('open');
		$("#pe_neck1").focus();
	});
	$("#button_pe_resp").button().click(function() {
		$("#pe_resp_dialog").dialog('open');
		$("#pe_resp1").focus();
	});
	$("#button_pe_cv").button().click(function() {
		$("#pe_cv_dialog").dialog('open');
		$("#pe_cv1").focus();
	});
	$("#button_pe_ch").button().click(function() {
		$("#pe_ch_dialog").dialog('open');
		$("#pe_ch1").focus();
	});
	$("#button_pe_gi").button().click(function() {
		$("#pe_gi_dialog").dialog('open');
		$("#pe_gi1").focus();
	});
	$("#button_pe_gu").button().click(function() {
		$("#pe_gu_dialog").dialog('open');
		$("#pe_gu1").focus();
	});
	$("#button_pe_lymph").button().click(function() {
		$("#pe_lymph_dialog").dialog('open');
		$("#pe_lymph1").focus();
	});
	$("#button_pe_ms").button().click(function() {
		$("#pe_ms_dialog").dialog('open');
		$("#pe_ms1").focus();
	});
	$("#button_pe_neuro").button().click(function() {
		$("#pe_neuro_dialog").dialog('open');
		$("#pe_neuro1").focus();
	});
	$("#button_pe_psych").button().click(function() {
		$("#pe_psych_dialog").dialog('open');
		$("#pe_psych1").focus();
	});
	$("#button_pe_skin").button().click(function() {
		$("#pe_skin_dialog").dialog('open');
		$("#pe_skin1").focus();
	});
	
	$('#save_pe_gen').click(function(){
		var a = $('#pe_gen1').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/gen');?>",
			data: "pe_gen1=" + a,
			success: function(data){
				$.jGrowl(data);
				$('#pe_gen1').val('');
				$('#pe_gen1_old').val('');
				$("#pe_gen_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_gen').click(function(){
		$('#pe_gen1').val('');
		$('#pe_gen1_old').val('');
		$("#pe_gen_dialog").dialog('close');
	});

	$('#save_pe_eye').click(function(){
		var a = $('#pe_eye1').val();
		var b = $('#pe_eye2').val();
		var c = $('#pe_eye3').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/eye');?>",
			data: "pe_eye1=" + a + "&pe_eye2=" + b + "&pe_eye3=" + c,
			success: function(data){
				$.jGrowl(data);
				$('#pe_eye1').val('');
				$('#pe_eye1_old').val('');
				$('#pe_eye2').val('');
				$('#pe_eye2_old').val('');
				$('#pe_eye3').val('');
				$('#pe_eye3_old').val('');
				$("#pe_eye_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_eye').click(function(){
		$('#pe_eye1').val('');
		$('#pe_eye1_old').val('');
		$('#pe_eye2').val('');
		$('#pe_eye2_old').val('');
		$('#pe_eye3').val('');
		$('#pe_eye3_old').val('');
		$("#pe_eye_dialog").dialog('close');
	});
	
	$('#save_pe_ent').click(function(){
		var a = $('#pe_ent1').val();
		var b = $('#pe_ent2').val();
		var c = $('#pe_ent3').val();
		var d = $('#pe_ent4').val();
		var e = $('#pe_ent5').val();
		var f = $('#pe_ent6').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/ent');?>",
			data: "pe_ent1=" + a + "&pe_ent2=" + b + "&pe_ent3=" + c + "&pe_ent4=" + d + "&pe_ent5=" + e + "&pe_ent6=" + f,
			success: function(data){
				$.jGrowl(data);
				$('#pe_ent1').val('');
				$('#pe_ent1_old').val('');
				$('#pe_ent2').val('');
				$('#pe_ent2_old').val('');
				$('#pe_ent3').val('');
				$('#pe_ent3_old').val('');
				$('#pe_ent4').val('');
				$('#pe_ent4_old').val('');
				$('#pe_ent5').val('');
				$('#pe_ent5_old').val('');
				$('#pe_ent6').val('');
				$('#pe_ent6_old').val('');
				$("#pe_ent_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_ent').click(function(){
		$('#pe_ent1').val('');
		$('#pe_ent1_old').val('');
		$('#pe_ent2').val('');
		$('#pe_ent2_old').val('');
		$('#pe_ent3').val('');
		$('#pe_ent3_old').val('');
		$('#pe_ent4').val('');
		$('#pe_ent4_old').val('');
		$('#pe_ent5').val('');
		$('#pe_ent5_old').val('');
		$('#pe_ent6').val('');
		$('#pe_ent6_old').val('');
		$("#pe_ent_dialog").dialog('close');
	});
	
	$('#save_pe_neck').click(function(){
		var a = $('#pe_neck1').val();
		var b = $('#pe_neck2').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/neck');?>",
			data: "pe_neck1=" + a + "&pe_neck2=" + b,
			success: function(data){
				$.jGrowl(data);
				$('#pe_neck1').val('');
				$('#pe_neck1_old').val('');
				$('#pe_neck2').val('');
				$('#pe_neck2_old').val('');
				$("#pe_neck_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_neck').click(function(){
		$('#pe_neck1').val('');
		$('#pe_neck1_old').val('');
		$('#pe_neck2').val('');
		$('#pe_neck2_old').val('');
		$("#pe_neck_dialog").dialog('close');
	});
	
	$('#save_pe_resp').click(function(){
		var a = $('#pe_resp1').val();
		var b = $('#pe_resp2').val();
		var c = $('#pe_resp3').val();
		var d = $('#pe_resp4').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/resp');?>",
			data: "pe_resp1=" + a + "&pe_resp2=" + b + "&pe_resp3=" + c + "&pe_resp4=" + d,
			success: function(data){
				$.jGrowl(data);
				$('#pe_resp1').val('');
				$('#pe_resp1_old').val('');
				$('#pe_resp2').val('');
				$('#pe_resp2_old').val('');
				$('#pe_resp3').val('');
				$('#pe_resp3_old').val('');
				$('#pe_resp4').val('');
				$('#pe_resp4_old').val('');
				$("#pe_resp_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_resp').click(function(){
		$('#pe_resp1').val('');
		$('#pe_resp1_old').val('');
		$('#pe_resp2').val('');
		$('#pe_resp2_old').val('');
		$('#pe_resp3').val('');
		$('#pe_resp3_old').val('');
		$('#pe_resp4').val('');
		$('#pe_resp4_old').val('');
		$("#pe_resp_dialog").dialog('close');
	});
	
	$('#save_pe_cv').click(function(){
		var a = $('#pe_cv1').val();
		var b = $('#pe_cv2').val();
		var c = $('#pe_cv3').val();
		var d = $('#pe_cv4').val();
		var e = $('#pe_cv5').val();
		var f = $('#pe_cv6').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/cv');?>",
			data: "pe_cv1=" + a + "&pe_cv2=" + b + "&pe_cv3=" + c + "&pe_cv4=" + d + "&pe_cv5=" + e + "&pe_cv6=" + f,
			success: function(data){
				$.jGrowl(data);
				$('#pe_cv1').val('');
				$('#pe_cv1_old').val('');
				$('#pe_cv2').val('');
				$('#pe_cv2_old').val('');
				$('#pe_cv3').val('');
				$('#pe_cv3_old').val('');
				$('#pe_cv4').val('');
				$('#pe_cv4_old').val('');
				$('#pe_cv5').val('');
				$('#pe_cv5_old').val('');
				$('#pe_cv6').val('');
				$('#pe_cv6_old').val('');
				$("#pe_cv_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_cv').click(function(){
		$('#pe_cv1').val('');
		$('#pe_cv1_old').val('');
		$('#pe_cv2').val('');
		$('#pe_cv2_old').val('');
		$('#pe_cv3').val('');
		$('#pe_cv3_old').val('');
		$('#pe_cv4').val('');
		$('#pe_cv4_old').val('');
		$('#pe_cv5').val('');
		$('#pe_cv5_old').val('');
		$('#pe_cv6').val('');
		$('#pe_cv6_old').val('');
		$("#pe_cv_dialog").dialog('close');
	});
	
	$('#save_pe_ch').click(function(){
		var a = $('#pe_ch1').val();
		var b = $('#pe_ch2').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/ch');?>",
			data: "pe_ch1=" + a + "&pe_ch2=" + b,
			success: function(data){
				$.jGrowl(data);
				$('#pe_ch1').val('');
				$('#pe_ch1_old').val('');
				$('#pe_ch2').val('');
				$('#pe_ch2_old').val('');
				$("#pe_ch_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_ch').click(function(){
		$('#pe_ch1').val('');
		$('#pe_ch1_old').val('');
		$('#pe_ch2').val('');
		$('#pe_ch2_old').val('');
		$("#pe_ch_dialog").dialog('close');
	});
	
	$('#save_pe_gi').click(function(){
		var a = $('#pe_gi1').val();
		var b = $('#pe_gi2').val();
		var c = $('#pe_gi3').val();
		var d = $('#pe_gi4').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/gi');?>",
			data: "pe_gi1=" + a + "&pe_gi2=" + b + "&pe_gi3=" + c + "&pe_gi4=" + d,
			success: function(data){
				$.jGrowl(data);
				$('#pe_gi1').val('');
				$('#pe_gi1_old').val('');
				$('#pe_gi2').val('');
				$('#pe_gi2_old').val('');
				$('#pe_gi3').val('');
				$('#pe_gi3_old').val('');
				$('#pe_gi4').val('');
				$('#pe_gi4_old').val('');
				$("#pe_gi_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_gi').click(function(){
		$('#pe_gi1').val('');
		$('#pe_gi1_old').val('');
		$('#pe_gi2').val('');
		$('#pe_gi2_old').val('');
		$('#pe_gi3').val('');
		$('#pe_gi3_old').val('');
		$('#pe_gi4').val('');
		$('#pe_gi4_old').val('');
		$("#pe_gi_dialog").dialog('close');
	});
	
	$('#save_pe_gu').click(function(){
		var a = $('#pe_gu7').val();
		var b = $('#pe_gu8').val();
		var c = $('#pe_gu9').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/gu');?>",
			data: "pe_gu1=&pe_gu2=&pe_gu3=&pe_gu4=&pe_gu5=&pe_gu6=&pe_gu7=" + a + "&pe_gu8=" + b + "&pe_gu9=" + c,
			success: function(data){
				$.jGrowl(data);
				$('#pe_gu7').val('');
				$('#pe_gu7_old').val('');
				$('#pe_gu8').val('');
				$('#pe_gu8_old').val('');
				$('#pe_gu9').val('');
				$('#pe_gu9_old').val('');
				$("#pe_gu_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_gu').click(function(){
		$('#pe_gu1').val('');
		$('#pe_gu1_old').val('');
		$('#pe_gu2').val('');
		$('#pe_gu2_old').val('');
		$('#pe_gu3').val('');
		$('#pe_gu3_old').val('');
		$('#pe_gu4').val('');
		$('#pe_gu4_old').val('');
		$('#pe_gu5').val('');
		$('#pe_gu5_old').val('');
		$('#pe_gu6').val('');
		$('#pe_gu6_old').val('');
		$("#pe_gu_dialog").dialog('close');
	});
	
	$('#save_pe_lymph').click(function(){
		var a = $('#pe_lymph1').val();
		var b = $('#pe_lymph2').val();
		var c = $('#pe_lymph3').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/lymph');?>",
			data: "pe_lymph1=" + a + "&pe_lymph2=" + b + "&pe_lymph3=" + c,
			success: function(data){
				$.jGrowl(data);
				$('#pe_lymph1').val('');
				$('#pe_lymph1_old').val('');
				$('#pe_lymph2').val('');
				$('#pe_lymph2_old').val('');
				$('#pe_lymph3').val('');
				$('#pe_lymph3_old').val('');
				$("#pe_lymph_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_lymph').click(function(){
		$('#pe_lymph1').val('');
		$('#pe_lymph1_old').val('');
		$('#pe_lymph2').val('');
		$('#pe_lymph2_old').val('');
		$('#pe_lymph3').val('');
		$('#pe_lymph3_old').val('');
		$("#pe_lymph_dialog").dialog('close');
	});
	
	$('#save_pe_ms').click(function(){
		var a = $('#pe_ms1').val();
		var b = $('#pe_ms2').val();
		var c = $('#pe_ms3').val();
		var d = $('#pe_ms4').val();
		var e = $('#pe_ms5').val();
		var f = $('#pe_ms6').val();
		var g = $('#pe_ms7').val();
		var h = $('#pe_ms8').val();
		var i = $('#pe_ms9').val();
		var j = $('#pe_ms10').val();
		var k = $('#pe_ms11').val();
		var l = $('#pe_ms12').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/ms');?>",
			data: "pe_ms1=" + a + "&pe_ms2=" + b + "&pe_ms3=" + c + "&pe_ms4=" + d + "&pe_ms5=" + e + "&pe_ms6=" + f + "&pe_ms7=" + g + "&pe_ms8=" + h + "&pe_ms9=" + i + "&pe_ms10=" + j + "&pe_ms11=" + k + "&pe_ms12=" + l,
			success: function(data){
				$.jGrowl(data);
				$('#pe_ms1').val('');
				$('#pe_ms1_old').val('');
				$('#pe_ms2').val('');
				$('#pe_ms2_old').val('');
				$('#pe_ms3').val('');
				$('#pe_ms3_old').val('');
				$('#pe_ms4').val('');
				$('#pe_ms4_old').val('');
				$('#pe_ms5').val('');
				$('#pe_ms5_old').val('');
				$('#pe_ms6').val('');
				$('#pe_ms6_old').val('');
				$('#pe_ms7').val('');
				$('#pe_ms7_old').val('');
				$('#pe_ms8').val('');
				$('#pe_ms8_old').val('');
				$('#pe_ms9').val('');
				$('#pe_ms9_old').val('');
				$('#pe_ms10').val('');
				$('#pe_ms10_old').val('');
				$('#pe_ms11').val('');
				$('#pe_ms11_old').val('');
				$('#pe_ms12').val('');
				$('#pe_ms12_old').val('');
				$("#pe_ms_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_ms').click(function(){
		$('#pe_ms1').val('');
		$('#pe_ms1_old').val('');
		$('#pe_ms2').val('');
		$('#pe_ms2_old').val('');
		$('#pe_ms3').val('');
		$('#pe_ms3_old').val('');
		$('#pe_ms4').val('');
		$('#pe_ms4_old').val('');
		$('#pe_ms5').val('');
		$('#pe_ms5_old').val('');
		$('#pe_ms6').val('');
		$('#pe_ms6_old').val('');
		$('#pe_ms7').val('');
		$('#pe_ms7_old').val('');
		$('#pe_ms8').val('');
		$('#pe_ms8_old').val('');
		$('#pe_ms9').val('');
		$('#pe_ms9_old').val('');
		$('#pe_ms10').val('');
		$('#pe_ms10_old').val('');
		$('#pe_ms11').val('');
		$('#pe_ms11_old').val('');
		$('#pe_ms12').val('');
		$('#pe_ms12_old').val('');
		$("#pe_ms_dialog").dialog('close');
	});
	
	$('#save_pe_neuro').click(function(){
		var a = $('#pe_neuro1').val();
		var b = $('#pe_neuro2').val();
		var c = $('#pe_neuro3').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/neuro');?>",
			data: "pe_neuro1=" + a + "&pe_neuro2=" + b + "&pe_neuro3=" + c,
			success: function(data){
				$.jGrowl(data);
				$('#pe_neuro1').val('');
				$('#pe_neuro1_old').val('');
				$('#pe_neuro2').val('');
				$('#pe_neuro2_old').val('');
				$('#pe_neuro3').val('');
				$('#pe_neuro3_old').val('');
				$("#pe_neuro_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_neuro').click(function(){
		$('#pe_neuro1').val('');
		$('#pe_neuro1_old').val('');
		$('#pe_neuro2').val('');
		$('#pe_neuro2_old').val('');
		$('#pe_neuro3').val('');
		$('#pe_neuro3_old').val('');
		$("#pe_neuro_dialog").dialog('close');
	});
	
	$('#save_pe_psych').click(function(){
		var a = $('#pe_psych1').val();
		var b = $('#pe_psych2').val();
		var c = $('#pe_psych3').val();
		var d = $('#pe_psych4').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/psych');?>",
			data: "pe_psych1=" + a + "&pe_psych2=" + b + "&pe_psych3=" + c + "&pe_psych4=" + d,
			success: function(data){
				$.jGrowl(data);
				$('#pe_psych1').val('');
				$('#pe_psych1_old').val('');
				$('#pe_psych2').val('');
				$('#pe_psych2_old').val('');
				$('#pe_psych3').val('');
				$('#pe_psych3_old').val('');
				$('#pe_psych4').val('');
				$('#pe_psych4_old').val('');
				$("#pe_psych_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_psych').click(function(){
		$('#pe_psych1').val('');
		$('#pe_psych1_old').val('');
		$('#pe_psych2').val('');
		$('#pe_psych2_old').val('');
		$('#pe_psych3').val('');
		$('#pe_psych3_old').val('');
		$('#pe_psych4').val('');
		$('#pe_psych4_old').val('');
		$("#pe_psych_dialog").dialog('close');
	});
	
	$('#save_pe_skin').click(function(){
		var a = $('#pe_skin1').val();
		var b = $('#pe_skin2').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/pe_save/skin');?>",
			data: "pe_skin1=" + a + "&pe_skin2=" + b,
			success: function(data){
				$.jGrowl(data);
				$('#pe_skin1').val('');
				$('#pe_skin1_old').val('');
				$('#pe_skin2').val('');
				$('#pe_skin2_old').val('');
				$("#pe_skin_dialog").dialog('close');
				check_pe_status();
			}
		});
	});
	$('#cancel_pe_skin').click(function(){
		$('#pe_skin1').val('');
		$('#pe_skin1_old').val('');
		$('#pe_skin2').val('');
		$('#pe_skin2_old').val('');
		$("#pe_skin_dialog").dialog('close');
	});
	$('#save_pe_gen').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_eye').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_ent').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_neck').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_resp').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_cv').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_ch').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_gi').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_gu').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_lymph').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_ms').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_neuro').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_psych').button({icons: {primary: "ui-icon-disk"},});
	$('#save_pe_skin').button({icons: {primary: "ui-icon-disk"},});
	$('#cancel_pe_gen').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_eye').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_ent').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_neck').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_resp').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_cv').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_ch').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_gi').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_gu').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_lymph').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_ms').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_neuro').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_psych').button({icons: {primary: "ui-icon-close"},});
	$('#cancel_pe_skin').button({icons: {primary: "ui-icon-close"},});
	$("#pe_gen_form tr").buttonset();
	$("#pe_eye1_form tr").buttonset();
	$("#pe_eye2_form tr").buttonset();
	$("#pe_eye3_form tr").buttonset();
	$("#pe_ent1_form tr").buttonset();
	$("#pe_ent2_form tr").buttonset();
	$("#pe_ent3_form tr").buttonset();
	$("#pe_ent4_form tr").buttonset();
	$("#pe_ent5_form tr").buttonset();
	$("#pe_ent6_form tr").buttonset();
</script>
