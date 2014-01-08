<div id="menutop">
	<h3><span id="menu_ptname"></span> (ID: <?php echo $id;?>)</h3>
	<strong>Nickname:</strong> <span id="menu_nickname"></span><br />
	<strong>Date of Birth:</strong> <span id="menu_dob"></span><br />
	<strong>Age:</strong> <span id="menu_age"></span><br />
	<strong>Gender:</strong> <span id="menu_gender1"></span><br />
	<strong>Last Encounter:</strong> <?php echo $lastvisit;?><br />
	<strong>Next Appointment:</strong> <?php echo $nextvisit;?><br />
</div>
<div id="menucontainer">
	<div id="menucolumn1" class="menucolumn">
		<img src="<?php echo base_url().'images/personal.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" id="demographics_list_img" class="menu_tooltip">
		<a href="#" id="demographics_list">Demographics</a><br />
		<img src="<?php echo base_url().'images/newmessage.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" id="messages_list_img" class="menu_tooltip">
		<a href="#" id="messages_list">Messages</a><br />
		<img src="<?php echo base_url().'images/alert.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" id="alerts_list_img" class="menu_tooltip">
		<a href="#" id="alerts_list">Alerts</a><br />
		<img src="<?php echo base_url().'images/rx.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" id="medications_list_img" class="menu_tooltip">
		<a href="#" id="medications_list">Medications</a><br />
		<img src="<?php echo base_url().'images/important.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" id="allergies_list_img" class="menu_tooltip">
		<a href="#" id="allergies_list">Allergies</a><br />
		<img src="<?php echo base_url().'images/prevent.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" >
		<a href="#" id="prevention_list">Prevention</a><br />
		<img src="<?php echo base_url().'images/printmgr.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" >
		<a href="#" id="print_list">Print</a><br />
	</div>
	<div id="menucolumn2" class="menucolumn">
		<img src="<?php echo base_url().'images/search.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" >
		<a href="#" id="documents_list">Documents</a><br />
		<img src="<?php echo base_url().'images/chart2.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" >
		<a href="#" id="encounter_list">Encounters</a><br />
		<img src="<?php echo base_url().'images/chart.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" id="issues_list_img" class="menu_tooltip">
		<a href="#" id="issues_list">Issues</a><br />
		<img src="<?php echo base_url().'images/supplements.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" id="supplements_list_img" class="menu_tooltip">
		<a href="#" id="supplements_list">Supplements</a><br />
		<img src="<?php echo base_url().'images/immunization.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" id="immunizations_list_img" class="menu_tooltip">
		<a href="#" id="immunizations_list">Immunizations</a><br />
		<img src="<?php echo base_url().'images/billing.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" >
		<a href="#" id="billing_list">Billing</a><br />
	</div>
</div>
<div id="demographics_list_dialog" title="Demographics">
	<form name="edit_demographics_form" id="edit_demographics_form">
		<button type="button" id="save_menu_demographics">Save</button>
		<button type="button" id="save_menu_demographics1">Save and Close</button>
		<button type="button" id="cancel_menu_demographics" >Cancel</button>
		<button type="button" id="insurance_menu_demographics">Insurance</button>
		<button type="button" id="register_menu_demographics">Register for Patient Portal</button>
		<span id="menu_registration_code"></span>
		<hr class="ui-state-default"/>
		<input type="hidden" name="pid" id="pid"/>
		<input type="hidden" name="race_code" id="menu_race_code">
		<input type="hidden" name="ethnicity_code" id="menu_ethnicity_code">
		<input type="hidden" name="guardian_code" id="menu_guardian_code">
		<input type="hidden" name="lang_code" id="menu_lang_code">
		<div id="demographics_accordion">
			<h3>Name and Identity</h3>
			<div>
				<table cellspacing="0" cellpadding="3">
					<tbody>
						<tr>
							<td>Last Name:<br><input type="text" name="lastname" id="menu_lastname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>First Name:<br><input type="text" name="firstname" id="menu_firstname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Nickname:<br><input type="text" name="nickname" id="menu_nickname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Middle Name:<br><input type="text" name="middle" id="menu_middle" style="width:82px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Title:<br><input type="text" name="title" id="menu_title" style="width:82px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
					</tbody>
				</table>
				<table cellspacing="0" cellpadding="3">
					<tbody>
						<tr>
							<td>Date of Birth:<br><input type="text" name="DOB" id="menu_DOB" style="width:148px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Gender:<br><select name="gender" id="menu_gender" class="text ui-widget-content ui-corner-all"></td>
							<td>SSN:<br><input type="text" name="ss" id="menu_ss" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Race:<br><input type="text" name="race" id="menu_race" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Marital Status:<br><select name="marital_status" id="menu_marital_status" style="width:164px" class="text ui-widget-content ui-corner-all"></td>
							<td>Spouse/Partner Name:<br><input type="text" name="partner_name" id="menu_partner_name" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Employer:<br><input type="text" name="employer" id="menu_employer" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Ethnicity:<br><input type="text" name="ethnicity" id="menu_ethnicity" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Caregiver(s):<br><input type="text" name="caregiver" id="menu_caregiver" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Status:<br><select name="active" id="menu_active" style="width:164px" class="text ui-widget-content ui-corner-all"></select></td>
							<td>Referred By:<br><input type="text" name="referred_by" id="menu_referred_by" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Preferred Language:<br><input type="text" name="language" id="menu_language" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Contact</h3>
			<div>
				<table cellspacing="0" cellpadding="3">
					<tbody>
						<tr>
							<td colspan="3">Select existing patient to copy contact information:<br><input type="text" id="menu_autocomplete_patient" style="width:560px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Address:<br><input type="text" name="address" id="menu_address" style="width:560px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>City:<br><input type="text" name="city" id="menu_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>State:<br><select name="state" id="menu_state" class="text ui-widget-content ui-corner-all"></select></td>
							<td>Zip:<br><input type="text" name="zip" id="menu_zip" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Email:<br><input type="text" name="email" id="menu_email" style="width:560px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Home Phone:<br><input type="text" name="phone_home" id="menu_phone_home" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Work Phone:<br><input type="text" name="phone_work" id="menu_phone_work" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Cellular Phone:<br><input type="text" name="phone_cell" id="menu_phone_cell" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Emergency Contact:<br><input type="text" name="emergency_contact" id="menu_emergency_contact" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Emergency Phone:<br><input type="text" name="emergency_phone" id="menu_emergency_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Appointment Reminder Method:<br><select name="reminder_method" id="menu_reminder_method" class="text ui-widget-content ui-corner-all"></select><td>
							<td>Cellular Phone Carrier:<br><select name="cell_carrier" id="menu_cell_carrier" class="text ui-widget-content ui-corner-all"></select><td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Guardian</h3>
			<div>
				<button type="button" id="guardian_import">Same contact information as patient</button>
				<table cellspacing="0" cellpadding="3">
					<tbody>
						<tr>
							<td>Last Name:<br><input type="text" name="guardian_lastname" id="menu_guardian_lastname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>First Name:<br><input type="text" name="guardian_firstname" id="menu_guardian_firstname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Relationship:<br><input type="text" name="guardian_relationship" id="menu_guardian_relationship" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Address:<br><input type="text" name="guardian_address" id="menu_guardian_address" style="width:560px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>City:<br><input type="text" name="guardian_city" id="menu_guardian_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>State:<br><select name="guardian_state" id="menu_guardian_state" class="text ui-widget-content ui-corner-all"></select></td>
							<td>Zip:<br><input type="text" name="guardian_zip" id="menu_guardian_zip" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="3">Email:<br><input type="text" name="guardian_email" id="menu_guardian_email" style="width:560px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>Home Phone:<br><input type="text" name="guardian_phone_home" id="menu_guardian_phone_home" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Work Phone:<br><input type="text" name="guardian_phone_work" id="menu_guardian_phone_work" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Cellular Phone:<br><input type="text" name="guardian_phone_cell" id="menu_guardian_phone_cell" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Other</h3>
			<div>
				<table cellspacing="0" cellpadding="3">
					<tbody>
						<tr>
							<td>Preferred Provider:<br><input type="text" name="preferred_provider" id="menu_preferred_provider" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Preferred Pharmacy:<br><input type="text" name="preferred_pharmacy" id="menu_preferred_pharmacy" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Other Field 1:<br><input type="text" name="other1" id="menu_other1" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
							<td>Other Field 2:<br><input type="text" name="other2" id="menu_other2" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td colspan="4">Comments:<br><textarea name="comments" id="menu_comments" rows="1" style="width:560px" class="text ui-widget-content ui-corner-all"></textarea></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>
<div id="demographics_insurance_plan_dialog" title="">
	<form name="edit_menu_insurance_plan_form" id="edit_menu_insurance_plan_form">
		<input type="hidden" name="address_id" id="menu_insurance_plan_address_id"/>
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
					<td></td>
				</tr>
				<tr>
					<td>HCFA-1500 Box 31 format:<br><select name="insurance_box_31" id="menu_insurance_box_31" class="text ui-widget-content ui-corner-all"></select></td>
					<td>HCFA-1500 Box 32a/33a format:<br><select name="insurance_box_32a" id="menu_insurance_box_32a" class="text ui-widget-content ui-corner-all"></select</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<div id="menu_insurance_main_dialog" title="Insurance Plan">
	<form name="edit_menu_insurance_main_form" id="edit_menu_insurance_main_form">
		<input type="hidden" name="insurance_id" id="menu_insurance_id"/>
		<input type="hidden" name="insurance_plan_name" id="menu_insurance_plan_name"/>
		<table>
			<tbody>
				<tr>
					<td>Insurance Provider:<br><select name="address_id" id="menu_insurance_plan_select" class="text ui-widget-content ui-corner-all"></select></td>
					<td><button type="button" id="add_insurance_plan">Add Insurance Provider</button></td>
					<td>Insurance Priority:<br><select name="insurance_order" id="menu_insurance_order" class="text ui-widget-content ui-corner-all"></td>
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
					<td>Insured Gender:<br><select name="insurance_insu_gender" id="menu_insurance_insu_gender" class="text ui-widget-content ui-corner-all"></select></td>
					<td><br><input type="button" id="insurance_copy" value="Use Patient's Address" class="nosh_button"/></td>
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
					<td colspan="3">Comments:<br><textarea name="insurance_comments" id="menu_insurance_comments" rows="3" style="width:562px" class="text ui-widget-content ui-corner-all"></textarea></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<div id="demographics_insurance_dialog" title="Insurance">
	<table id="demographics_insurance" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="demographics_insurance_pager" class="scroll" style="text-align:center;"></div><br>
	<div id="demographics_insurance_details"></div><br>
	<button type="button" id="demographics_add_insurance">Add Insurance</button>
	<button type="button" id="demographics_edit_insurance">Edit Insurance</button>
	<button type="button" id="demographics_inactivate_insurance">Inactivate Insurance</button>
	<button type="button" id="demographics_delete_insurance">Delete Insurance</button><br><br>
	<table id="demographics_insurance_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="demographics_insurance_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<input type="button" id="demographics_reactivate_insurance" value="Reactivate Insurance" class="nosh_button"/><br><br>
</div>
<div id="messages_list_dialog" title="Messages">
	<button type="button" id="new_telephone_message">New Message</button> 
	<hr class="ui-state-default"/>
	<table id="messages" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="messages_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<div id="messages_main_dialog" title="Message">
	<ul id="t_messages_tags"></ul>
	<div id="edit_message_fieldset" style="display: none">
		<form name="edit_message_form" id="edit_message_form">
			<input type="hidden" name="t_messages_id" id="t_messages_id"/>
			<button type="button" id="save_message">Save Draft</button>
			<button type="button" id="sign_message">Sign</button>
			<button type="button" id="cancel_message">Cancel</button>
			<button type="button" id="delete_message">Delete</button>
			<hr class="ui-state-default"/>
			<table>
				<tbody>
					<tr>
						<td>Subject:</td>
						<td><input type="text" name="t_messages_subject" id="t_messages_subject" style="width:450px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>To:</td>
						<td><input type="text" name="t_messages_to" id="t_messages_to" style="width:450px"class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td valign="top">Message:</td>
						<td valign="top"><textarea name="t_messages_message" id="t_messages_message" rows="14" style="width:450px" class="text ui-widget-content ui-corner-all"></textarea></td>
						<td valign="top">
							<button type="button" id="message_telephone">Phone</button><br>
							<button type="button" id="message_rx">RX</button><br>
							<button type="button" id="message_sup">Supplements</button><br>
							<button type="button" id="message_lab">Lab</button><br>
							<button type="button" id="message_rad">Imaging</button><br>
							<button type="button" id="message_cp">Cardiopulmonary</button><br>
							<button type="button" id="message_ref">Referral</button><br>
							<button type="button" id="message_reply">Results</button>
						</td>
					</tr>
					<tr>
						<td valign="top">Date of Service:</td>
						<td valign="top"><input type="text" name="t_messages_dos" id="t_messages_dos" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<div id="message_view"></div>
</div>
<div id="messages_telephone_dialog" title="Message Helper">
	<form name="edit_message_telephone_form" id="edit_message_telephone_form">
		<input type="button" id="save_telephone_helper" value="Import Form Items to Message" class="nosh_button"/> <input type="button" id="cancel_telephone_helper" value="Cancel" class="nosh_button"/><br>
		<hr class="ui-state-default"/>
		<table>
			<tbody>
				<tr>
					<td valign="top">Subjective:</td>
					<td><textarea name="message_subjective" id="message_subjective" rows="8" style="width:500px" class="text ui-widget-content ui-corner-all"></textarea></td>
				</tr>
				<tr>
					<td valign="top">Assessment:</td>
					<td><input type="text" name="message_assessment" id="message_assessment" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td valign="top">Plan:</td>
					<td><textarea name="message_plan" id="message_plan" rows="8" style="width:500px" class="text ui-widget-content ui-corner-all"></textarea></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<div id="messages_rx_load"></div>
<div id="messages_lab_load"></div>
<div id="messages_rad_load"></div>
<div id="messages_cp_load"></div>
<div id="messages_ref_load"></div>
<div id="messages_reply_dialog" title="Results Correspondence">
	<form name="edit_message_reply_form" id="edit_message_reply_form">
		<button type="button" id="save_reply_helper">Import Reply to Message</button>
		<button type="button" id="save_reply_helper_email">Email</button>
		<button type="button" id="save_reply_helper_letter">Send Letter</button>
		<button type="button" id="cancel_reply_helper">Cancel</button><br>
		<hr class="ui-state-default"/>
		<table id="messages_reply_alerts" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_reply_alerts_pager1" class="scroll" style="text-align:center;"></div><br>
		<button type="button" id="complete_message_reply_alert">Mark as Completed</button><br><br>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>Results Message</legend>
			<table>
				<tbody>
					<tr>
						<td valign="top">Tests Performed:</td>
						<td><textarea name="message_reply_tests_performed"id="message_reply_tests_performed" rows="4" style="width:500px" class="text ui-widget-content ui-corner-all"></textarea></td>
					</tr>
					<tr>
						<td valign="top">Message to Patient:</td>
						<td><textarea name="message_reply_message"id="message_reply_message" rows="4" style="width:500px" class="text ui-widget-content ui-corner-all"></textarea></td>
					</tr>
					<tr>
						<td valign="top">Followup:</td>
						<td><input type="text" name="message_reply_followup" id="message_reply_followup" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
					</tr>
				</tbody>
			</table>
		</fieldset><br>
	</form>
</div>
<div id="encounter_view_dialog" title="Encounter">
	<div id="encounter_view"></div>
</div>
<div id="encounter_list_dialog" title="Past Encounters">
	<table id="encounters" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="encounters_pager" class="scroll" style="text-align:center;"></div>
</div>
<div id="issues_load"></div>
<div id="supplements_load"></div>
<div id="imm_load"></div>
<div id="medications_list_dialog" title="Medications" >
	<div id="oh_meds_header" style="display:none">
		<button type="button" id="save_oh_meds" class="nosh_button_save">Save Medication List</button>
		<hr class="ui-state-default"/>
	</div>
	<table id="medications" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="medications_pager" class="scroll" style="text-align:center;"></div><br>
	<input type="button" id="add_rx" value="Add Medication" class="nosh_button"/>
	<input type="button" id="edit_rx" value="Edit Medication" class="nosh_button"/>
	<input type="button" id="inactivate_rx" value="Inactivate Medication" class="nosh_button"/>
	<input type="button" id="delete_rx" value="Delete Medication" class="nosh_button"/><br><br>
	<form name="edit_rx_form" id="edit_rx_form" style="display: none">
		<input type="hidden" name="rxl_id" id="rxl_id"/>
		<input type="hidden" name="rxl_ndcid" id="rxl_ndcid"/>
		<input type="hidden" id="rxl_name"/>
		<input type="hidden" id="rxl_form"/>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>Medication</legend>
			<table cellspacing="0" cellpadding="1">
				<tbody>
					<tr>
						<td colspan="2">Medication:<br><input type="text" name="rxl_medication" id="rxl_medication" style="width:356px" class="text ui-widget-content ui-corner-all"/></td>
						<td>Dosage:<br><input type="text" name="rxl_dosage" id="rxl_dosage" style="width:117px" class="text ui-widget-content ui-corner-all"/></td>
						<td>Unit:<br><input type="text" name="rxl_dosage_unit" id="rxl_dosage_unit" style="width:117px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Sig:<br><input type="text" name="rxl_sig" id="rxl_sig" style="width:235px" class="text ui-widget-content ui-corner-all"/></td>
						<td>Route:<br><select id ="rxl_route" name="rxl_route" class="text ui-widget-content ui-corner-all"></select></td>
						<td colspan="2">Frequency:<br><input type="text" name="rxl_frequency" id="rxl_frequency" style="width:240px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="4">Special Instructions:<br><input type="text" name="rxl_instructions" id="rxl_instructions" style="width:600px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="4">Reason:<br><input type="text" name="rxl_reason" id="rxl_reason" style="width:600px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="4">Date Active:<br><input type="text" name="rxl_date_active" id="rxl_date_active" class="text ui-widget-content ui-corner-all"/></td>
						<td>
							<button type="button" id="save_medication" class="nosh_button_save">Save</button><br>
							<button type="button" id="cancel_medication" class="nosh_button_cancel">Cancel</button>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset><br>
	</form>
	<table id="medications_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="medications_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<input type="button" id="reactivate_rx" value="Reactivate Medication" class="nosh_button"/><br><br>	
</div>
<div id="allergies_list_dialog" title="Allergies">
	<button type="button" id="save_oh_allergies" style="display:none">Save Allergies List</button> 
	<button type="button" id="rcopia_update_allergies" style="display:none">Update from RCopia</button>
	<div id="allergies_header" style="display:none">
		<hr class="ui-state-default"/>
	</div>
	<table id="allergies" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="allergies_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="add_allergy">Add Allergy</button>
	<button type="button" id="edit_allergy">Edit Allergy</button>
	<button type="button" id="inactivate_allergy">Inactivate Allergy</button>
	<button type="button" id="delete_allergy">Delete Allergy</button><br><br>
	<form name="edit_allergy_form" id="edit_allergy_form" style="display: none">
		<input type="hidden" name="allergies_id" id="allergies_id"/>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>Allergy</legend>
			<table>
				<tbody>
					<tr>
						<td>Medication:</td>
						<td><input type="text" name="allergies_med" id="allergies_med" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Reaction:</td>
						<td><input type="text" name="allergies_reaction" id="allergies_reaction" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Date Active:</td>
						<td><input type="text" name="allergies_date_active" id="allergies_date_active" class="text ui-widget-content ui-corner-all"/></td>
						<td>
							<button type="button" id="save_allergy">Save</button> 
							<button type="button" id="cancel_allergy">Cancel</button>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset><br>
	</form>
	<table id="allergies_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="allergies_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="reactivate_allergy">Reactivate Allergy</button><br><br>
</div>
<div id="alerts_list_dialog" title="Alerts and Tasks">
	<table id="alerts" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="alerts_pager1" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="add_alert">Add</button> 
	<button type="button" id="edit_alert">Edit</button>
	<button type="button" id="complete_alert">Mark as Completed</button> 
	<button type="button" id="incomplete_alert">Mark as Incomplete</button>
	<button type="button" id="delete_alert">Delete</button><br><br>
	<form name="edit_alert_form" id="edit_alert_form" style="display: none">
		<input type="hidden" name="alert_id" id="alert_id"/>
		<input type="hidden" name="id" id="alert_provider_id"/>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>Alert or Task</legend>
			<table>
				<tbody>
					<tr>
						<td>Alert:</td>
						<td><input type="text" name="alert" id="alert" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>User or Provider to Alert:</td>
						<td><input type="text" name="alert_provider" id="alert_provider" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td>Description:</td>
						<td><textarea name="alert_description" id="alert_description" rows="2" style="width:500px" class="text ui-widget-content ui-corner-all"></textarea></td>
						<td></td>
					</tr>
					<tr>
						<td>Due Date:</td>
						<td><input type="text" name="alert_date_active" id="alert_date_active" class="text ui-widget-content ui-corner-all"/></td>
						<td>
							<button type="button" id="save_alert">Save</button> 
							<button type="button" id="cancel_alert">Cancel</button>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset><br>
	</form>
	<form name="edit_alert_form1" id="edit_alert_form1" style="display: none">
		<input type="hidden" name="alert_id1" id="alert_id1"/>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>Alert or Task Not Completed</legend>
			<table>
				<tbody>
					<tr>
						<td>Reason:</td>
						<td><input type="text" name="alert_reason_not_complete" id="alert_reason_not_complete" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td>
							<button type="button" id="save_alert1">Save</button> 
							<button type="button" id="cancel_alert1">Cancel</button>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset><br>
	</form>
	<table id="alerts_complete" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="alerts_complete_pager" class="scroll" style="text-align:center;"></div><br>
	<table id="alerts_not_complete" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="alerts_not_complete_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<div id="documents_list_dialog" title="Documents">
	<button type="button" id="menu_new_letter">New Letter</button>
	<button type="button" id="menu_tests">Test Results</button>
	<hr class="ui-state-default"/>
	<table id="labs" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager8" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="radiology" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager9" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="cardiopulm" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager10" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="endoscopy" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager11" class="scroll" style="text-align:center;"></div> 
	<br>			
	<table id="referrals" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager12" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="past_records" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager13" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="outside_forms" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager14" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="letters" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager15" class="scroll" style="text-align:center;"></div> 
	<br>
</div>
<div id="documents_edit_dialog" title="Edit Documents">
	<ul id="documents_tags"></ul>
	<form id="documents_edit_form">
		<input type="hidden" name="documents_id" id="menu_documents_id"/>
		<table>
			<tbody>
				<tr>
					<td><label for="menu_documents_from">From</label></td>
					<td><input type="text" name="documents_from" id="menu_documents_from" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="menu_documents_type">Document Type</label></td>
					<td><select name="documents_type" id="menu_documents_type" class="text ui-widget-content ui-corner-all"></select></td>
				</tr>
				<tr>
					<td><label for="menu_documents_desc">Description</label></td>
					<td><input type="text" name="documents_desc" id="menu_documents_desc" style="width:500px" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="menu_document_date">Document Date</label></td>
					<td><input type="text" name="documents_date" id="menu_documents_date" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
			</tbody>
		</table>
		<hr class="ui-state-default"/>
		<button type="button" id="save_menu_documents">Save</button> 
		<button type="button" id="cancel_menu_documents">Cancel</button>
	</form>
</div>
<div id="documents_view_dialog" title="Documents Viewer">
	<ul id="documents_view_tags"></ul>
	<input type="hidden" id="view_document_id"/>
	<input type="hidden" id="document_filepath"/>
	<button type="button" id="save_document">Save</button><br>
	<div id="embedURL"></div>
</div>
<div id="tests_dialog" title="Test Results">
	Search: <input type="text" size="50" id="search_all_tests" class="text ui-widget-content ui-corner-all" onkeydown="doSearch1(arguments[0]||event)"/><br><br> 
	<table id="tests_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="tests_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="chart_results">Chart Selected Results</button>
	<div id="chart_loading" style="display: block;float: right;"><img src="<?php echo base_url().'images/indicator.gif';?>"> Loading graph...</div><br><br>
	<div id="tests_container" style="width: 750px; height: 550px; margin: 0 auto"></div>
</div>
<div id="prevention_list_dialog" title="Prevention Recommendations">
	<div id="prevention_load"><img src="<?php echo base_url().'images/indicator.gif';?>"> Loading...</div>
	<div id="prevention_items"></div>
</div>
<div id="letter_load"></div>
<div id="billing_load"></div>
<div id="print_load"></div>
<div id="mtm_load"></div>
<script type="text/javascript">
	//Demographics
	$('.menu_tooltip').tooltip({
		items: ".menu_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		postion: { my: "left+15 center", at: "right bottom", collision: "flipfit" },
		open: function(event, ui){
			var elem = $(this);
			var id = $(this).attr("id");
			id = id.replace("_img", "");
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/');?>/" + id,
				success: function(data){
					elem.tooltip('option', 'content', data);
					elem.tooltip("option","position",{ my: "left+15 center", at: "right bottom", collision: "flipfit" });
				}
			});
		}
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/chartmenu/demographics_load');?>",
		dataType: "json",
		success: function(data){
			$('#menu_ptname').html(data.ptname);
			$('#menu_nickname').html(data.nickname);
			$('#menu_dob').html(data.dob);
			$('#menu_age').html(data.age);
			$('#menu_gender1').html(data.gender);
			if (data.new == 'Y') {
				$("#demographics_list_dialog").dialog('open');
			}
		}
	});
	$("#demographics_accordion").accordion({
		activate: function (event, ui) {
			var id = ui.newPanel[0].id;
			$("#" + id + " .text").first().focus();
		}
	});
	$("#demographics_accordion .ui-accordion-content").each(function(){
		$(this).find(".text").last().on('keydown', function(e) {
			if (e.which == 9) {
				if (!e.shiftKey) {
					var active = $("#demographics_accordion").accordion("option", "active");
					if (active < 3) {
						$("#demographics_accordion").accordion("option", "active", active + 1);
					}
				}
			}
		});
	});
	$("#guardian_import").button().click(function(){
		$('#menu_guardian_address').val($('#menu_address').val());
		$('#menu_guardian_city').val($('#menu_city').val());
		$('#menu_guardian_zip').val($('#menu_zip').val());
		$('#menu_guardian_phone_home').val($('#menu_phone_home').val());
		$('#menu_guardian_phone_cell').val($('#menu_phone_cell').val());
		$('#menu_guardian_phone_work').val($('#menu_phone_work').val());
	});
	$("#demographics_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/demographics');?>",
				dataType: "json",
				success: function(data){
					$("#menu_lastname").val(data.lastname);
					$("#menu_firstname").val(data.firstname);
					$("#menu_middle").val(data.middle);
					$("#menu_title").val(data.title);
					$("#menu_nickname").val(data.nickname);
					var dob = editDate1(data.DOB);
					$("#menu_DOB").val(dob);
					$("#menu_gender").val(data.sex);
					$("#menu_ss").val(data.ss);
					$("#menu_race").val(data.race);
					$("#menu_ethnicity").val(data.ethnicity);
					$("#menu_language").val(data.language);
					$("#menu_address").val(data.address);
					$("#menu_city").val(data.city);
					$("#menu_state").val(data.state);
					$("#menu_zip").val(data.zip);
					$("#menu_phone_home").val(data.phone_home);
					$("#menu_phone_work").val(data.phone_work);
					$("#menu_phone_cell").val(data.phone_cell);
					$("#menu_email").val(data.email);
					$("#menu_marital_status").val(data.marital_status);
					$("#menu_partner_name").val(data.partner_name);
					$("#menu_employer").val(data.employer);
					$("#menu_emergency_contact").val(data.emergency_contact);
					$("#menu_emergency_phone").val(data.emergency_phone);
					$("#menu_reminder_method").val(data.reminder_method);
					$("#menu_cell_carrier").val(data.cell_carrier);
					$("#menu_preferred_provider").val(data.preferred_provider);
					$("#menu_preferred_pharmacy").val(data.preferred_pharmacy);
					$("#menu_other1").val(data.other1);
					$("#menu_other2").val(data.other2);
					$("#menu_comments").val(data.comments);
					$("#menu_caregiver").val(data.caregiver);
					$("#menu_active").val(data.active);
					$("#menu_referred_by").val(data.referred_by);
					$("#menu_race_code").val(data.race_code);
					$("#menu_ethnicity_code").val(data.ethnicity_code);
					$("#menu_guardian_lastname").val(data.guardian_lastname);
					$("#menu_guardian_firstname").val(data.guardian_firstname);
					$("#menu_guardian_relationship").val(data.guardian_relationship);
					$("#menu_guardian_code").val(data.guardian_code);
					$("#menu_guardian_address").val(data.guardian_address);
					$("#menu_guardian_city").val(data.guardian_city);
					$("#menu_guardian_state").val(data.guardian_state);
					$("#menu_guardian_zip").val(data.guardian_zip);
					$("#menu_guardian_phone_home").val(data.guardian_phone_home);
					$("#menu_guardian_phone_work").val(data.guardian_phone_work);
					$("#menu_guardian_phone_cell").val(data.guardian_phone_cell);
					$("#menu_guardian_email").val(data.guardian_email);
					$("#menu_lang_code").val(data.lang_code);
					$("#menu_lastname").focus();
				}
			});
		},
		close: function(event, ui) {
			$('#edit_demographics_form').clearForm();
		}
	});
	$("#demographics_insurance_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#demographics_insurance_details").html("");
			jQuery("#demographics_insurance").jqGrid('GridUnload');
			jQuery("#demographics_insurance").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/insurance/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Phone','Copay','Deductible','Comments','Address ID','Relationship'],
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
					{name:'insurance_insu_phone',index:'insurance_insu_phone',width:1,hidden:true},
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
					var text = '<strong>Additional insurance information for ' + jQuery("#demographics_insurance").getCell(id,'insurance_plan_name') + ":</strong><br>";
					if(copay != ''){
						text += "Copay: " + copay + "<br>";
					}
					if(deductible != ''){
						text += "Deductible: " + deductible + "<br>";
					}
					if (comments != ''){
						text += "Comments: " + comments;
					}
					$("#demographics_insurance_details").html(text);
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#demographics_insurance_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#demographics_insurance_inactive").jqGrid('GridUnload')
			jQuery("#demographics_insurance_inactive").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/insurance_inactive/');?>",
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
			 		var copay = jQuery("#demographics_insurance").getCell(id,'insurance_copay');
					var deductible = jQuery("#demographics_insurance").getCell(id,'insurance_deductible');
					var comments = jQuery("#demographics_insurance").getCell(id,'insurance_comments');
					var text = '<strong>Additional insurance information for ' + jQuery("#demographics_insurance").getCell(id,'insurance_plan_name') + ":</strong><br>";
					if(copay != ''){
						text += "Copay: " + copay + "<br>";
					}
					if(deductible != ''){
						text += "Deductible: " + deductible + "<br>";
					}
					if (comments != ''){
						text += "Comments: " + comments;
					}
					$("#demographics_insurance_details").html(text);
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#demographics_insurance_inactive_pager',{search:false,edit:false,add:false,del:false});
			$.ajax({
				url: "<?php echo site_url('search/insurance3');?>",
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#menu_insurance_plan_select").addOption({"":"Select or add insurance provider."});
						$("#menu_insurance_plan_select").addOption(data.message);
					} else {
						$("#menu_insurance_plan_select").addOption({"":"No insurance provider.  Click Add."});
					}
				}
			});
		},
		close: function(event, ui) {
			$("#edit_menu_insurance_main_form").clearForm();
		}
	});
	$("#menu_autocomplete_patient").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/demographics_copy');?>",
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
		select: function( event, ui ) {
			$("#menu_address").val(ui.item.address);
			$("#menu_city").val(ui.item.city);
			$("#menu_state").val(ui.item.state);
			$("#menu_zip").val(ui.item.zip);
			$("#menu_phone_home").val(ui.item.phone_home);
			$("#menu_phone_work").val(ui.item.phone_work);
			$("#menu_phone_cell").val(ui.item.phone_cell);
			$("#menu_email").val(ui.item.email);
			$("#menu_emergency_contact").val(ui.item.emergency_contact);
			$("#menu_emergency_phone").val(ui.item.emergency_phone);
			$("#menu_reminder_method").val(ui.item.reminder_method);
			$("#menu_cell_carrier").val(ui.item.cell_carrier);
		}
	});
	$("#menu_address").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/address');?>",
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
	$("#menu_city").autocomplete({
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
	$("#menu_guardian_relationship").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/guardian_relationship');?>",
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
		select: function( event, ui ) {
			$("#menu_guardian_code").val(ui.item.code);
		}
	});
	$("#menu_guardian_address").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/address');?>",
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
	$("#menu_guardian_city").autocomplete({
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
	$("#menu_preferred_provider").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/provider');?>",
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
	$("#menu_preferred_pharmacy").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/pharmacy1');?>",
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
	var gender = {"m":"Male","f":"Female"};
	$("#menu_gender").addOption(gender, false);
	var marital = {"":"","Single":"Single","Married":"Married","Common law":"Common law","Domestic partner":"Domestic partner","Registered domestic partner":"Registered domestic partner","Interlocutory":"Interlocutory","Living together":"Living together","Legally Separated":"Legally Separated","Divorced":"Divorced","Separated":"Separated","Widowed":"Widowed","Other":"Other","Unknown":"Unknown","Unmarried":"Unmarried","Unreported":"Unreported"};
	$("#menu_marital_status").addOption(marital, false);
	$("#menu_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#menu_guardian_state").addOption({"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#menu_DOB").mask("99/99/9999");
	$("#menu_DOB").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#menu_ss").mask("999-99-9999");
	var race_options = [
		{
			value: "American Indian or Alaska Native",
			label: "American Indian or Alaska Native",
			code: "1002-5"
		},
		{
			value: "Asian",
			label: "Asian",
			code: "2028-9"
		},
		{
			value: "Black or African American",
			label: "Black or African American",
			code: "2054-5"
		},
		{
			value: "Native Hawaiian or Other Pacific Islander",
			label: "Native Hawaiian or Other Pacific Islander",
			code: "2076-8"
		},
		{
			value: "White",
			label: "White",
			code: "2106-3"
		}
	];
	var ethnicity_options = [
		{
			value: "Hispanic or Latino",
			label: "Hispanic or Latino",
			code: "2135-2"
		},
		{
			value: "Not Hispanic or Latino",
			label: "Not Hispanic or Latino",
			code: "2186-5"
		}
	];
	$("#menu_race").autocomplete({
		source: race_options,
		minLength: 0,
		delay: 0,
		select: function( event, ui ) {
			$("#menu_race_code").val(ui.item.code);
		}
	}).click(function () {
		$(this).autocomplete("search", "");
	});
	$("#menu_ethnicity").autocomplete({
		source: ethnicity_options,
		minLength: 0,
		delay: 0,
		select: function( event, ui ) {
			$("#menu_ethnicity_code").val(ui.item.code);
		}
	}).click(function () {
		$(this).autocomplete("search", "");
	});
	$("#menu_language").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/language');?>",
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
		select: function( event, ui ) {
			$("#menu_lang_code").val(ui.item.code);
		}
	});
	$("#menu_phone_home").mask("(999) 999-9999");
	$("#menu_phone_work").mask("(999) 999-9999");
	$("#menu_phone_cell").mask("(999) 999-9999");
	$("#menu_guardian_phone_home").mask("(999) 999-9999");
	$("#menu_guardian_phone_work").mask("(999) 999-9999");
	$("#menu_guardian_phone_cell").mask("(999) 999-9999");
	$("#menu_emergency_phone").mask("(999) 999-9999");
	$("#menu_reminder_method").addOption({"":"","Email":"Email","Cellular Phone":"Cellular Phone"}, false);
	$("#menu_cell_carrier").addOption({"":"","txt.att.net":"AT&T","sms.mycricket.com":"Cricket","messaging.nextel.com":"Nextel","qwestmp.com":"Qwest","messaging.sprintpcs.com":"Sprint(PCS)","number@page.nextel.com":"Sprint(Nextel)","tmomail.net":"T-Mobile","email.uscc.net":"US Cellular","vtext.com":"Verizon","vmobl.com":"Virgin Mobile"}, false);
	$("#menu_active").addOption({"1":"Active","0":"Inactive"}, false);
	$("#demographics_list").click(function() {
		$("#demographics_list_dialog").dialog('open');
	});
	$("#save_menu_demographics").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_menu_demographics").click(function() {
		var a = $("#menu_reminder_method").val();
		var b = $("#menu_cell_carrier").val();
		if (a == "Cellular Phone") {
			if (b != "") {
				var str = $("#edit_demographics_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/edit_demographics');?>",
						data: str,
						success: function(data){
							if (data == 'Close Chart') {
								window.location = "<?php echo site_url();?>";
							} else {
								$.jGrowl(data);
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('provider/chartmenu/demographics_load');?>",
									dataType: "json",
									success: function(data){
										$('#menu_ptname').html(data.ptname);
										$('#menu_nickname').html(data.nickname);
										$('#menu_dob').html(data.dob);
										$('#menu_age').html(data.age);
										$('#menu_gender1').html(data.gender);
									}
								});
							}
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			} else {
				$.jGrowl("Cellular carrier needs to be completed for cellular phone appointment reminders!");
			}
		} else {
			var str = $("#edit_demographics_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/edit_demographics');?>",
					data: str,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/demographics_load');?>",
								dataType: "json",
								success: function(data){
									$('#menu_ptname').html(data.ptname);
									$('#menu_nickname').html(data.nickname);
									$('#menu_dob').html(data.dob);
									$('#menu_age').html(data.age);
									$('#menu_gender1').html(data.gender);
								}
							});
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#save_menu_demographics1").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_menu_demographics1").click(function() {
		var a = $("#menu_reminder_method").val();
		var b = $("#menu_cell_carrier").val();
		if (a == "Cellular Phone") {
			if (b != "") {
				var str = $("#edit_demographics_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/edit_demographics');?>",
						data: str,
						success: function(data){
							if (data == 'Close Chart') {
								window.location = "<?php echo site_url();?>";
							} else {
								$.jGrowl(data);
								$("#demographics_list_dialog").dialog('close');
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('provider/chartmenu/demographics_load');?>",
									dataType: "json",
									success: function(data){
										$('#menu_ptname').html(data.ptname);
										$('#menu_nickname').html(data.nickname);
										$('#menu_dob').html(data.dob);
										$('#menu_age').html(data.age);
										$('#menu_gender1').html(data.gender);
									}
								});
							}
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			} else {
				$.jGrowl("Cellular carrier needs to be completed for cellular phone appointment reminders!");
			}
		} else {
			var str = $("#edit_demographics_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/edit_demographics');?>",
					data: str,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							$("#demographics_list_dialog").dialog('close');
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/demographics_load');?>",
								dataType: "json",
								success: function(data){
									$('#menu_ptname').html(data.ptname);
									$('#menu_nickname').html(data.nickname);
									$('#menu_dob').html(data.dob);
									$('#menu_age').html(data.age);
									$('#menu_gender1').html(data.gender);
								}
							});
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_menu_demographics").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_menu_demographics").click(function() {
		$("#edit_demographics_form").clearForm();
		$("#demographics_list_dialog").dialog('close');
	});
	$("#insurance_menu_demographics").button({
		icons: {
			primary: "ui-icon-suitcase"
		}
	});
	$("#insurance_menu_demographics").click(function() {
		$("#demographics_insurance_dialog").dialog('open');
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/chartmenu/check_registration_code');?>",
		success: function(data){
			if (data == 'n') {
				$("#register_menu_demographics").show();
			} else {
				$("#register_menu_demographics").hide();
				$("#menu_registration_code").html(data);
			}
		}
	});
	$("#register_menu_demographics").button().click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/register_patient');?>",
			success: function(data){
				$("#register_menu_demographics").hide();
				$("#menu_registration_code").html(data);
			}
		});
	});
	
	$("#demographics_add_insurance").button();
	$("#demographics_add_insurance").click(function(){
		$('#edit_menu_insurance_main_form').clearForm();
		$('#menu_insurance_plan_select').val('');
		$("#add_insurance_plan span").text("Add Insurance Provider");
		$('#menu_insurance_main_dialog').dialog('open');
	});
	$("#demographics_edit_insurance").button();
	$("#demographics_edit_insurance").click(function(){
		var item = jQuery("#demographics_insurance").getGridParam('selrow');
		if(item){
			jQuery("#demographics_insurance").GridToForm(item,"#edit_menu_insurance_main_form");
			var dob1 = $("#menu_insurance_insu_dob").val();
			var dob = editDate1(dob1);
			$("#menu_insurance_insu_dob").val(dob);
			$("#menu_insurance_plan_name").val($("#menu_insurance_plan_select option:selected").text());
			$("#add_insurance_plan span").text("Edit Insurance Provider");
			$('#menu_insurance_main_dialog').dialog('open');
		} else {
			$.jGrowl("Please select insurance to edit!")
		}
	});
	$("#demographics_inactivate_insurance").button();
	$("#demographics_inactivate_insurance").click(function(){
		var item = jQuery("#demographics_insurance").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/inactivate_insurance');?>",
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
			$.jGrowl("Please select insurance to inactivate!")
		}
	});
	$("#demographics_delete_insurance").button();
	$("#demographics_delete_insurance").click(function(){
		var item = jQuery("#demographics_insurance").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this insurance?  This is not recommended unless entering the insurance was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/delete_insurance');?>",
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
			$.jGrowl("Please select insurance to delete!")
		}
	});
	$("#demographics_reactivate_insurance").button();
	$("#demographics_reactivate_insurance").click(function(){
		var item = jQuery("#demographics_insurance_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/reactivate_insurance');?>",
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
			$.jGrowl("Please select medication to reactivate!")
		}
	});
	$("#menu_insurance_main_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#menu_insurance_plan_select").focus();
		},
		buttons: {
			'Save': function() {
				var plan_name = $("#menu_insurance_plan_name");
				var id_num = $("#menu_insurance_id_num");
				var lastname = $("#menu_insurance_insu_lastname");
				var firstname = $("#menu_insurance_insu_firstname");
				var relationship = $("#menu_insurance_relationship");
				var address = $("#menu_insurance_insu_address");
				var city = $("#menu_insurance_insu_city");
				var state = $("#menu_insurance_insu_state");
				var zip = $("#menu_insurance_insu_zip");
				var bValid = true;
				bValid = bValid && checkEmpty(plan_name,"Insurance Plan name");
				bValid = bValid && checkEmpty(id_num,"ID Number");
				bValid = bValid && checkEmpty(lastname,"Insured Last Name");
				bValid = bValid && checkEmpty(firstname,"Insured First Name");
				bValid = bValid && checkEmpty(relationship,"Relationship");
				bValid = bValid && checkEmpty(address,"Insured Address");
				bValid = bValid && checkEmpty(city,"Insured City");
				bValid = bValid && checkEmpty(state,"Insured State");
				bValid = bValid && checkEmpty(zip,"Insured Zip");
				if (bValid) {
					var str = $("#edit_menu_insurance_main_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/edit_insurance');?>",
							data: str,
							success: function(data){
								if (data == 'Close Chart') {
									window.location = "<?php echo site_url();?>";
								} else {
									$.jGrowl(data);
									$("#edit_menu_insurance_main_form").clearForm();
									$("#menu_insurance_main_dialog").dialog('close');
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
			},
			Cancel: function() {
				$('#edit_menu_insurance_main_form').clearForm();
				$("#menu_insurance_main_dialog").dialog('close');
			}
		},
		close: function(event, ui) {
			$('#edit_menu_insurance_main_form').clearForm();
		}
	});
	$("#demographics_insurance_plan_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#menu_insurance_plan_facility").focus();
			var id = $("#menu_insurance_plan_select").val();
			if (id != "") {
				$("#demographics_insurance_plan_dialog").dialog("option", "title", "Edit Insurance Provider");
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
						$("#menu_insurance_box_31").val(data.insurance_box_31);
						$("#menu_insurance_box_32a").val(data.insurance_box_32a);
					}
				});
			} else {
				$("#demographics_insurance_plan_dialog").dialog("option", "title", "Add Insurance Provider");
				$("#menu_insurance_box_31").val('n');
				$("#menu_insurance_box_32a").val('n');
			}
		},
		buttons: {
			'Save': function() {
				var a = $("#menu_insurance_plan_facility");
				var b = $("#menu_insurance_plan_type");
				var c = $("#menu_insurance_plan_assignment");
				var d = $("#menu_insurance_plan_address");
				var e = $("#menu_insurance_plan_city");
				var f = $("#menu_insurance_plan_state");
				var g = $("#menu_insurance_plan_zip");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Insurance Plan Name");
				bValid = bValid && checkEmpty(b,"Insurance Plan Type");
				bValid = bValid && checkEmpty(c,"Accept Assignment");
				bValid = bValid && checkEmpty(d,"Street Address");
				bValid = bValid && checkEmpty(e,"City");
				bValid = bValid && checkEmpty(f,"State");
				bValid = bValid && checkEmpty(g,"Zip");
				if (bValid) {
					var str = $("#edit_menu_insurance_plan_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/edit_insurance_provider');?>",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								$("#menu_insurance_plan_select").removeOption(/./);
								$.ajax({
									url: "<?php echo site_url('search/insurance3');?>",
									dataType: "json",
									type: "POST",
									success: function(data1){
										if(data1.response =='true'){
											$("#menu_insurance_plan_select").addOption(data1.message);
											$("#menu_insurance_plan_select").val(data.id);
											$("#menu_insurance_plan_name").val($("#menu_insurance_plan_select option:selected").text());
											$("#demographics_insurance_plan_dialog").dialog('close');
											$("#demographics_insurance_plan_dialog").dialog("option", "title", "");
										}
									}
								});
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_menu_insurance_plan_form').clearForm();
				$("#demographics_insurance_plan_dialog").dialog('close');
				$("#demographics_insurance_plan_dialog").dialog("option", "title", "");
			}
		},
		close: function(event, ui) {
			$('#edit_menu_insurance_plan_form').clearForm();
			$("#demographics_insurance_plan_dialog").dialog("option", "title", "");
		}
	});
	$("#add_insurance_plan").button().click(function(){
		$("#demographics_insurance_plan_dialog").dialog('open');
	});
	$('#menu_insurance_plan_select').change(function() {
		if ($(this).val() != ""){
			$("#menu_insurance_plan_name").val($("#menu_insurance_plan_select option:selected").text());
			$("#add_insurance_plan span").text("Edit Insurance Provider");
		} else {
			$("#add_insurance_plan span").text("Add Insurance Provider");
		}
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
	$("#menu_insurance_insu_gender").addOption(gender, false);
	$("#menu_insurance_order").addOption({"":"","Primary":"Primary","Secondary":"Secondary","Unassigned":"Unassigned"}, false);
	$("#menu_insurance_relationship").addOption({"":"","Self":"Self","Spouse":"Spouse","Child":"Child","Other":"Other"}, false);
	$("#menu_insurance_plan_type").addOption({"":"","Group Health Plan":"Group Health Plan","Other":"Other","Medicare":"Medicare","Medicaid":"Medicaid","Tricare":"Tricare","ChampVA":"ChampVA","FECA":"FECA"}, false);
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
				url: "<?php echo site_url('provider/chartmenu/copy_address');?>",
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
			url: "<?php echo site_url('provider/chartmenu/copy_address');?>",
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
	$("#menu_insurance_box_31").addOption({"n":"First Last, Title (Default)","y":"Last, First" });
	$("#menu_insurance_box_32a").addOption({"n":"Company NPI (Default)","y":"Personal NPI" });
	
	//Messages
	$("#messages_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#messages").jqGrid('GridUnload');
			jQuery("#messages").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/messages/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date of Service','Subject','Message','Provider','Signed','To'],
				colModel:[
					{name:'t_messages_id',index:'t_messages_id',width:1,hidden:true},
					{name:'t_messages_dos',index:'t_messages_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'t_messages_subject',index:'t_messages_subject',width:425},
					{name:'t_messages_message',index:'t_messages_message',width:1,hidden:true},
					{name:'t_messages_provider',index:'t_messages_provider',width:100},
					{name:'t_messages_signed',index:'t_messages_signed',width:100},
					{name:'t_messages_to',index:'t_messages_to',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_pager'),
				sortname: 't_messages_dos',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Messages",
			 	height: "100%",
			 	onSelectRow: function(id) {
			 		var item = jQuery("#messages").getGridParam('selrow');
		 			var signed = jQuery("#messages").getCell(id,'t_messages_signed');
		 			if (signed == 'No') {
						jQuery("#messages").GridToForm(id,"#edit_message_form");
						$("#message_view").html('');
						var date = $('#t_messages_dos').val();
						var edit_date = editDate(date);
						$('#t_messages_dos').val(edit_date);
						$("#edit_message_fieldset").show('fast');
						t_messages_tags();
					}
					if (signed == 'Yes') {
						$("#edit_message_fieldset").hide('fast');
						var row = jQuery("#messages").getRowData(id);
						var text = '<br><strong>Date:</strong>  ' + row['t_messages_dos'] + '<br><br><strong>Subject:</strong>  ' + row['t_messages_subject'] + '<br><br><strong>Message:</strong> ' + row['t_messages_message']; 
						$("#message_view").html(text);
						$("#t_messages_id").val(row['t_messages_id']);
						t_messages_tags();
					}
					$("#messages_main_dialog").dialog('open');
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$("#edit_message_fieldset").hide('fast');
			$('#edit_message_form').clearForm();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/messages_list');?>",
				success: function(data){
					$('#messages_tip').html(data);
				}
			});
		}
	});
	$("#messages_telephone_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		beforeclose: function(event, ui) {
			var a = $("#message_subjective").val();
			var b = $("#message_assessment").val();
			var c = $("#message_plan").val();
			var d = a + b + c;
			if(d != ''){
				if(confirm('The form fields are not empty.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
					$('#edit_message_telephone_form').clearForm();
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$("#messages_rx_load").load('<?php echo site_url("provider/chartmenu/rx_load");?>');
	$("#messages_lab_load").load('<?php echo site_url("provider/chartmenu/lab_load");?>');
	$("#messages_rad_load").load('<?php echo site_url("provider/chartmenu/rad_load");?>');
	$("#messages_cp_load").load('<?php echo site_url("provider/chartmenu/cp_load");?>');
	$("#messages_ref_load").load('<?php echo site_url("provider/chartmenu/ref_load");?>');
	$("#messages_reply_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 450, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#message_reply_tests_performed").focus();
		},
		close: function(event, ui) {
			$('#edit_message_reply_form').clearForm();
		}
	});
	$("#messages_list").click(function() {
		$("#messages_list_dialog").dialog('open');
		$("#edit_message_fieldset").hide('fast');
	});
	$("#new_telephone_message").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#new_telephone_message").click(function() {
		$("#edit_message_form").clearForm();
		$.ajax({
			url: "<?php echo site_url('provider/chartmenu/new_message');?>",
			dataType: "json",
			type: "POST",
			success: function(data){
				$("#t_messages_id").val(data);
				var currentDate = getCurrentDate();
				$("#t_messages_dos").val(currentDate);
				jQuery("#messages").trigger("reloadGrid");
				$("#edit_message_fieldset").show('fast');
				$("#message_view").html('');
				t_messages_tags();
				$("#messages_main_dialog").dialog('open');
			}
		});
	});
	$("#t_messages_subject").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/subject');?>",
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
	$("#t_messages_to").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/users');?>",
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
	$("#t_messages_dos").mask("99/99/9999");
	$("#t_messages_dos").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#message_telephone").button().click(function() {
		$("#messages_telephone_dialog").dialog('open');
		$("#message_subjective").focus();
	});
	$("#message_rx").button().click(function() {
		$("#orders_rx_header").hide('fast');
		$("#messages_rx_header").show('fast');
		$("#messages_rx_main").show('fast');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('start/check_rcopia_extension');?>",
			success: function(data){
				if (data == 'y') {
					$('#rcopia_orders_rx').hide('fast');
					$('#rcopia_rx_helper').show('fast');
				} else {
					$('#rcopia_orders_rx').hide('fast');
					$('#rcopia_rx_helper').hide('fast');
				}
			}
		});
		$("#messages_rx_dialog").dialog('open');
	});
	$("#message_sup").button().click(function() {
		$("#supplement_origin_orders").val("Y");
		$("#supplements_list_dialog").dialog('open');
		$("#messages_supplements_header").show();
		$("#orders_supplements").focus();
	});
	$("#message_lab").button();
	$("#message_lab").click(function() {
		$("#save_lab_helper_label").html('Import to Message');
		$("#messages_lab_origin").val('message');
		var id = $("#t_messages_id").val();
		$("#messages_lab_t_messages_id").val(id);
		$("#messages_lab_header").show('fast');
		jQuery("#messages_lab_list").trigger("reloadGrid");
		$("#messages_lab_dialog").dialog('open');
	});
	$("#message_rad").button();
	$("#message_rad").click(function() {
		$("#save_rad_helper_label").html('Import to Message');
		$("#messages_rad_origin").val('message');
		var id = $("#t_messages_id").val();
		$("#messages_rad_t_messages_id").val(id);
		$("#messages_rad_header").show('fast');
		jQuery("#messages_rad_list").trigger("reloadGrid");
		$("#messages_rad_dialog").dialog('open');
	});
	$("#message_cp").button();
	$("#message_cp").click(function() {
		$("#save_cp_helper_label").html('Import to Message');
		$("#messages_cp_origin").val('message');
		var id = $("#t_messages_id").val();
		$("#messages_cp_t_messages_id").val(id);
		$("#messages_cp_header").show('fast');
		jQuery("#messages_cp_list").trigger("reloadGrid");
		$("#messages_cp_dialog").dialog('open');
	});
	$("#message_ref").button();
	$("#message_ref").click(function() {
		$("#save_ref_helper_label").html('Import to Message');
		$("#messages_ref_origin").val('message');
		var id = $("#t_messages_id").val();
		$("#messages_ref_t_messages_id").val(id);
		$("#messages_ref_header").show('fast');
		jQuery("#messages_ref_list").trigger("reloadGrid");
		$("#messages_ref_dialog").dialog('open');
	});
	$("#message_reply").button();
	$("#message_reply").click(function() {
		jQuery("#messages_reply_alerts").jqGrid('GridUnload');
		jQuery("#messages_reply_alerts").jqGrid({
			url:"<?php echo site_url('provider/chartmenu/alerts1/');?>",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Due Date','Alert','Description'],
			colModel:[
				{name:'alert_id',index:'alert_id',width:1,hidden:true},
				{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
				{name:'alert',index:'alert',width:200},
				{name:'alert_description',index:'alert',width:465}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#messages_reply_alerts_pager1'),
			sortname: 'alert_date_active',
		 	viewrecords: true,
		 	sortorder: "desc",
		 	caption:"Pending Orders",
		 	height: "100%",
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#messages_reply_alerts_pager1',{search:false,edit:false,add:false,del:false});
		$("#messages_reply_dialog").dialog('open');
	});
	$("#complete_message_reply_alert").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	$("#complete_message_reply_alert").click(function(){
		var item = jQuery("#messages_reply_alerts").getGridParam('selrow');
		var row = jQuery("#messages_reply_alerts").getRowData(item);
		var test = row['alert_description'];
		var test1 = test.split(":");
		var test2 = $.trim(test1[1]);
		var old = $("#message_reply_tests_performed").val();
		if (old == '') {
			$("#message_reply_tests_performed").val(test2);
		} else {
			$("#message_reply_tests_performed").val(old + '\n' + test2);
		}
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/complete_alert');?>",
				data: "alert_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#messages_reply_alerts").trigger("reloadGrid");
					jQuery("#alerts").trigger("reloadGrid");
					jQuery("#alerts_complete").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select order to mark as complete!")
		}
	});
	$("#save_reply_helper").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_reply_helper").click(function() {
		var old = $("#t_messages_message").val();
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
		var a = $("#message_reply_tests_performed").val();
		var b = $("#message_reply_message").val();
		var c = $("#message_reply_followup").val();
		if(c){
			var c1 = 'Followup recommendations:  ' + c;
			if(b){
				var b1 = 'Conclusion:  ' + b + '\n\n';
				if(a != ""){
					var a1 = 'The following tests were performed: ' + a + '\n\n';	
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'The following tests were performed: ' + a;	
				} else {
					var a1 = '';
				}
			}
		} else {
			var c1 = '';
			if(b){
				var b1 = 'Conclusion:  ' + b;
				if(a){
					var a1 = 'The following tests were performed: ' + a + '\n\n';	
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'The following tests were performed: ' + a;	
				} else {
					var a1 = '';
				}
			}
		}
		$("#t_messages_message").val(old1+a1+b1+c1);
		$('#edit_message_reply_form').clearForm();
		$("#messages_reply_dialog").dialog('close');
	});
	$("#save_reply_helper_email").button({
		icons: {
			primary: "ui-icon-mail-closed"
		}
	});
	$("#save_reply_helper_email").click(function() {
		var old = $("#t_messages_message").val();
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
		var a = $("#message_reply_tests_performed").val();
		var b = $("#message_reply_message").val();
		var c = $("#message_reply_followup").val();
		if(c){
			var c1 = 'Followup recommendations:  ' + c;
			if(b){
				var b1 = 'Conclusion:  ' + b + '\n\n';
				if(a != ""){
					var a1 = 'The following tests were performed: ' + a + '\n\n';	
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'The following tests were performed: ' + a;	
				} else {
					var a1 = '';
				}
			}
		} else {
			var c1 = '';
			if(b){
				var b1 = 'Conclusion:  ' + b;
				if(a){
					var a1 = 'The following tests were performed: ' + a + '\n\n';	
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'The following tests were performed: ' + a;	
				} else {
					var a1 = '';
				}
			}
		}
		$("#t_messages_message").val(old1+a1+b1+c1);
		var body = encodeURIComponent(a1+b1+c1);
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/internal_message_reply');?>",
			data: "body=" + body,
			success: function(data){
				$.jGrowl(data);
			}
		});
		$('#edit_message_reply_form').clearForm();
		$("#messages_reply_dialog").dialog('close');
	});
	$("#save_reply_helper_letter").button({
		icons: {
			primary: "ui-icon-mail-closed"
		}
	});
	$("#save_reply_helper_letter").click(function() {
		var old = $("#t_messages_message").val();
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
		var a = $("#message_reply_tests_performed").val();
		var b = $("#message_reply_message").val();
		var c = $("#message_reply_followup").val();
		if(c){
			var c1 = 'Followup recommendations:  ' + c;
			if(b){
				var b1 = 'Conclusion:  ' + b + '\n\n';
				if(a != ""){
					var a1 = 'The following tests were performed: ' + a + '\n\n';	
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'The following tests were performed: ' + a;	
				} else {
					var a1 = '';
				}
			}
		} else {
			var c1 = '';
			if(b){
				var b1 = 'Conclusion:  ' + b;
				if(a){
					var a1 = 'The following tests were performed: ' + a + '\n\n';	
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'The following tests were performed: ' + a;	
				} else {
					var a1 = '';
				}
			}
		}
		$("#t_messages_message").val(old1+a1+b1+c1);
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/letter_reply');?>",
			data: "body=" + a1 + b1 + c1,
			dataType: "json",
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
			window.open("<?php echo site_url('provider/chartmenu/view_documents');?>/" + id_doc);
			success_doc = false;
			id_doc = '';
		}
		$('#edit_message_reply_form').clearForm();
		$("#messages_reply_dialog").dialog('close');
	});
	$("#cancel_reply_helper").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_reply_helper").click(function() {
		$('#edit_message_reply_form').clearForm();
		$("#messages_reply_dialog").dialog('close');
	});
	$("#messages_main_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false
	});
	$("#save_message").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_message").click(function() {
		var subject = $("#t_messages_subject");
		var message1 = $("#t_messages_message");
		var dos = $("#t_messages_dos");
		var bValid = true;
		bValid = bValid && checkEmpty(subject,"Subject");
		bValid = bValid && checkEmpty(message1,"Message");
		bValid = bValid && checkEmpty(dos,"Date of Service");
		if (bValid) {
			var str = $("#edit_message_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/edit_message');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						jQuery("#messages").trigger("reloadGrid");
						$("#edit_message_form").clearForm();
						$("#edit_message_fieldset").hide('fast');
						$("#messages_main_dialog").dialog('close');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#sign_message").button({
		icons: {
			primary: "ui-icon-locked"
		}
	});
	$("#sign_message").click(function() {
		var subject = $("#t_messages_subject");
		var message1 = $("#t_messages_message");
		var dos = $("#t_messages_dos");
		var bValid = true;
		bValid = bValid && checkEmpty(subject,"Subject");
		bValid = bValid && checkEmpty(message1,"Message");
		bValid = bValid && checkEmpty(dos,"Date of Service");
		if (bValid) {
			var str = $("#edit_message_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/sign_message');?>",
					data: str,
					success: function(data){
						if(data) {
							$.jGrowl(data);
							jQuery("#messages").trigger("reloadGrid");
							$("#edit_message_form").clearForm();
							$("#edit_message_fieldset").hide('fast');
							$("#messages_main_dialog").dialog('close');
						} else {
							$.jGrowl(data);
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_message").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_message").click(function() {
		$('#edit_message_form').clearForm();
		$("#edit_message_fieldset").hide('fast');
		$("#messages_main_dialog").dialog('close');
	});
	$("#delete_message").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#delete_message").click(function() {
		var str = $("#t_messages_id").val();
		if(str != ''){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/delete_message');?>",
				data: "t_messages_id=" + str,
				success: function(data){
					if(data) {
						$.jGrowl(data);
						jQuery("#messages").trigger("reloadGrid");
						$("#edit_message_form").clearForm();
						$("#edit_message_fieldset").hide('fast');
						$("#messages_main_dialog").dialog('close');
					} else {
						$.jGrowl(data);
					}
				}
			});
		} else {
			$.jGrowl("No message to delete!  Message has not been saved previously!");
		}
	});
	$("#save_telephone_helper").click(function() {
		var old = $("#t_messages_message").val();
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
		var a = $("#message_subjective").val();
		var b = $("#message_assessment").val();
		var c = $("#message_plan").val();
		if(c){
			var c1 = 'PLAN:  ' + c;
			if(b){
				var b1 = 'ASSESSMENT:  ' + b + '\n\n';
				if(a != ""){
					var a1 = 'SUBJECTIVE:  ' + a + '\n\n';	
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'SUBJECTIVE:  ' + a;	
				} else {
					var a1 = '';
				}
			}
		} else {
			var c1 = '';
			if(b){
				var b1 = 'ASSESSMENT:  ' + b;
				if(a){
					var a1 = 'SUBJECTIVE:  ' + a + '\n\n';	
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'SUBJECTIVE:  ' + a;	
				} else {
					var a1 = '';
				}
			}
		}
		$("#t_messages_message").val(old1+a1+b1+c1);
		$('#edit_message_telephone_form').clearForm();
		$("#messages_telephone_dialog").dialog('close');
	});
	$("#cancel_telephone_helper").click(function() {
		$('#edit_message_telephone_form').clearForm();
		$("#messages_telephone_dialog").dialog('close');
	});

	//Encounters
	$("#encounter_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false
	});
	$("#encounter_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function (event, ui) {
			jQuery("#encounters").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/encounters/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Chief Complaint','Status'],
				colModel:[
					{name:'eid',index:'eid',width:1,hidden:true},
					{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'encounter_cc',index:'encounter_cc',width:500},
					{name:'encounter_signed',index:'encounter_signed',width:100,formatter:signedlabel}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#encounters_pager'),
				sortname: 'encounter_DOS',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Encounters",
			 	height: "100%",
			 	onSelectRow: function(id) {
			 		var status = jQuery("#encounters").getCell(id,'encounter_signed');
			 		if (status == "Draft") {
			 			$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/encounter_id_set/');?>",
							data: "eid=" + id,
							success: function(data) {
								window.location = "<?php echo site_url ('provider/encounters/view/');?>";
							}
						});
			 		}
			 		if (status == "Signed") {
			 			$("#encounter_view").load('<?php echo site_url("provider/encounters/modal_view");?>/' + id);
						$("#encounter_view_dialog").dialog('open');
			 		}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#encounters_pager',{search:false,edit:false,add:false,del:false});
		}
	});
	$("#encounter_list").click(function() {
		$("#encounter_list_dialog").dialog('open');
	});
	function signedlabel (cellvalue, options, rowObject){
		if (cellvalue == 'No') {
			return 'Draft';
		}
		if (cellvalue == 'Yes') {
			return 'Signed';
		}
	}
	//Issues
	$("#issues_load").load('<?php echo site_url("provider/chartmenu/issues_load");?>');
	
	//Medications
	$("#medications_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 450, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#medications").jqGrid('GridUnload');
			jQuery("#medications").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/medications/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason','NDC'],
				colModel:[
					{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
					{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_medication',index:'rxl_medication',width:255},
					{name:'rxl_dosage',index:'rxl_dosage',width:50},
					{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
					{name:'rxl_sig',index:'rxl_sig',width:50},
					{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
					{name:'rxl_frequency',index:'rxl_frequency',width:105},
					{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
					{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true},
					{name:'rxl_ndcid',index:'rxl_ndcid',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#medications_pager'),
				sortname: 'rxl_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Medications",
			 	height: "100%",
			 	onSelectRow: function(id){
			 		var med = jQuery("#medications").getCell(id,'rxl_medication');
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/past_medication');?>",
						data: "rxl_medication=" + med,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.item, {sticky:true, header:data.header});
						}
					});
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#medications_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#medications_inactive").jqGrid('GridUnload');
			jQuery("#medications_inactive").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/medications_inactive/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason'],
				colModel:[
					{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
					{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_medication',index:'rxl_medication',width:255},
					{name:'rxl_dosage',index:'rxl_dosage',width:50},
					{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
					{name:'rxl_sig',index:'rxl_sig',width:50},
					{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
					{name:'rxl_frequency',index:'rxl_frequency',width:105},
					{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
					{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#medications_inactive_pager'),
				sortname: 'rxl_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Inactive Medications",
			 	height: "100%",
			 	hiddengrid: true,
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#medications_inactive_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$('#edit_rx_form').clearForm();
			$('#edit_rx_form').hide('fast');
			$('#oh_meds_header').hide('fast');
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/medications_list');?>",
				success: function(data){
					$('#medications_tip').html(data);
				}
			});
		}
	});
	$("#medications_list").click(function() {
		$("#medications_list_dialog").dialog('open');
		$("#oh_meds_header").hide('fast');
	});
	$("#rxl_date_active").mask("99/99/9999");
	$("#rxl_date_active").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#rxl_route").addOption({"by mouth":"PO","per rectum":"PR","subcutaneously":"SC","intramuscularly":"IM","intravenously":"IV"}, false);
	$("#rxl_route").selectOptions();
	$("#rxl_medication").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_name');?>",
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
			$('#rxl_name').val(ui.item.name);
			$('#rxl_form').val(ui.item.form);
			$('#rxl_dosage').val('');
			$('#rxl_dosage_unit').val('');
		}
	});
	$("#rxl_dosage").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_dosage');?>",
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
		minLength: 0,
		select: function(event, ui){
			$("#rxl_dosage_unit").val(ui.item.unit);
			$.ajax({
				url: "<?php echo site_url('search/rx_ndc_convert');?>/" + ui.item.ndc,
				type: "POST",
				success: function(data){
					$("#rxl_ndcid").val(data);
				}
			});
		}
	});
	$("#rxl_dosage").focus(function(){
		var rx_name = $("#rxl_name").val();
		if (rx_name == '') {
			$.jGrowl('Medication field empty!');
		} else {
			rx_name = rx_name + ";" + $("#rxl_form").val();
			$("#rxl_dosage").autocomplete("search", rx_name);
		}
	});
	$("#rxl_sig").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_sig');?>",
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
		minLength: 1
	});
	$("#rxl_frequency").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_frequency');?>",
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
		minLength: 1
	});
	$("#rxl_instructions").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_instructions');?>",
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
	$("#rxl_reason").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/rx_reason');?>",
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
	
	$("#add_rx").click(function(){
		$('#edit_rx_form').clearForm();
		var currentDate = getCurrentDate();
		$('#rxl_date_active').val(currentDate);
		$('#edit_rx_form').show('fast');
		$("#rxl_search").focus();
	});
	$("#edit_rx").click(function(){
		var item = jQuery("#medications").getGridParam('selrow');
		if(item){
			jQuery("#medications").GridToForm(item,"#edit_rx_form");
			var date = $('#rxl_date_active').val();
			var edit_date = editDate(date);
			$('#rxl_date_active').val(edit_date);
			$('#edit_rx_form').show('fast');
			$("#rxl_medication").focus();
		} else {
			$.jGrowl("Please select medication to edit!")
		}
	});
	$("#inactivate_rx").click(function(){
		var item = jQuery("#medications").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/inactivate_medication');?>",
				data: "rxl_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					jQuery("#medications").trigger("reloadGrid");
					jQuery("#medications_inactive").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select medication to inactivate!")
		}
	});
	$("#delete_rx").click(function(){
		var item = jQuery("#medications").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this medication?  This is not recommended unless entering the medication was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/delete_medication');?>",
					data: "rxl_id=" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#medications").trigger("reloadGrid");
						jQuery("#medications_inactive").trigger("reloadGrid");
					}
				});
			}
		} else {
			$.jGrowl("Please select medication to inactivate!")
		}
	});
	$("#reactivate_rx").click(function(){
		var item = jQuery("#medications_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/reactivate_medication');?>",
				data: "rxl_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					jQuery("#medications_inactive").trigger("reloadGrid");
					jQuery("#medications").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select medication to reactivate!")
		}
	});
	$("#save_medication").click(function(){
		var medication = $("#rxl_medication");
		var bValid = true;
		bValid = bValid && checkEmpty(medication,"Medication");
		if (bValid) {
			var str = $("#edit_rx_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/edit_medication');?>",
					data: str,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							jQuery("#medications").trigger("reloadGrid");
							$('#edit_rx_form').clearForm();
							$('#edit_rx_form').hide('fast');
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_medication").click(function(){
		$('#edit_rx_form').clearForm();
		$('#edit_rx_form').hide('fast');
	});
	//Supplements
	$("#supplements_load").load('<?php echo site_url("provider/chartmenu/supplements_load");?>');
	
	//Allergies
	$("#allergies_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#allergies").jqGrid('GridUnload');
			jQuery("#allergies").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/allergies/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Medication','Reason'],
				colModel:[
					{name:'allergies_id',index:'allergies_id',width:1,hidden:true},
					{name:'allergies_date_active',index:'allergies_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'allergies_med',index:'allergies_med',width:310},
					{name:'allergies_reaction',index:'allergies_reaction',width:320}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#allergies_pager'),
				sortname: 'allergies_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Allergies",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#allergies_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#allergies_inactive").jqGrid('GridUnload');
			jQuery("#allergies_inactive").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/allergies_inactive/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Medication','Reason'],
				colModel:[
					{name:'allergies_id',index:'allergies_id',width:1,hidden:true},
					{name:'allergies_date_active',index:'allergies_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'allergies_med',index:'allergies_med',width:310},
					{name:'allergies_reaction',index:'allergies_reaction',width:320}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#allergies_inactive_pager'),
				sortname: 'allergies_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption: "Inactive Allergies",
			 	hiddengrid: true,
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#allergies_inactive_pager',{search:false,edit:false,add:false,del:false});
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('start/check_rcopia_extension');?>",
				success: function(data){
					if (data == 'y') {
						$('#rcopia_update_allergies').show('fast');
						$('#allergies_header').show('fast');
					} else {
						$('#rcopia_update_allergies').hide('fast');
						$('#allergies_header').show('fast');
					}
				}
			});
		},
		close: function(event, ui) {
			$('#edit_allergy_form').clearForm();
			$('#oh_allergies_header').hide('fast');
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/allergies_list');?>",
				success: function(data){
					$('#allergies_tip').html(data);
				}
			});
		}
	});
	$("#rcopia_update_allergies").button({
		icons: {
			primary: "ui-icon-link"
		}
	}).click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/rcopia_update_allergy');?>",
			success: function(data){
				if (data == 'Close Chart') {
					window.location = "<?php echo site_url();?>";
				} else {
					$.jGrowl(data);
					jQuery("#allergies").trigger("reloadGrid");
					jQuery("#allergies_inactive").trigger("reloadGrid");
				}
			}
		});
	});
	$("#allergies_list").click(function() {
		$("#allergies_list_dialog").dialog('open');
		$('#edit_allergy_form').hide('fast');
	});
	$("#allergies_date_active").mask("99/99/9999");
	$("#allergies_date_active").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	var allergies_cache = {};
	$("#allergies_med").autocomplete({
		source: function (req, add){
			if (req.term in allergies_cache){
				add(allergies_cache[req.term]);
				return;
			}
			$.ajax({
				url: "<?php echo site_url('search/rx_name');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						allergies_cache[req.term] = data.message;
						add(data.message);
					}				
				}
			});
		},
		minLength: 3
	});
	$("#allergies_reaction").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/reaction');?>",
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
	$("#add_allergy").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#add_allergy").click(function(){
		$('#edit_allergy_form').clearForm();
		var currentDate = getCurrentDate();
		$('#allergies_date_active').val(currentDate);
		$('#edit_allergy_form').show('fast');
		$("#allergies_med").focus();
	});
	$("#edit_allergy").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#edit_allergy").click(function(){
		var item = jQuery("#allergies").getGridParam('selrow');
		if(item){
			jQuery("#allergies").GridToForm(item,"#edit_allergy_form");
			var date = $('#allergies_date_active').val();
			var edit_date = editDate(date);
			$('#allergies_date_active').val(edit_date);
			$('#edit_allergy_form').show('fast');
			$("#allergies_med").focus();
		} else {
			$.jGrowl("Please select allergy to edit!")
		}
	});
	$("#inactivate_allergy").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#inactivate_allergy").click(function(){
		var item = jQuery("#allergies").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/inactivate_allergy');?>",
				data: "allergies_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#allergies").trigger("reloadGrid");
					jQuery("#allergies_inactive").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select allergy to inactivate!")
		}
	});
	$("#delete_allergy").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#delete_allergy").click(function(){
		var item = jQuery("#allergies").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this allergy?')){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/delete_allergy');?>",
					data: "allergies_id=" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#allergies").trigger("reloadGrid");
					}
				});
			}
		} else {
			$.jGrowl("Please select allergy to delete!")
		}
	});
	$("#reactivate_allergy").button({
		icons: {
			primary: "ui-icon-arrowreturnthick-1-w"
		}
	});
	$("#reactivate_allergy").click(function(){
		var item = jQuery("#allergies_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/reactivate_allergy');?>",
				data: "allergies_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#allergies_inactive").trigger("reloadGrid");
					jQuery("#allergies").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select allergy to inactivate!")
		}
	});
	$("#save_allergy").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_allergy").click(function(){
		var med = $("#allergies_med");
		var bValid = true;
		bValid = bValid && checkEmpty(med,"Medication");
		if (bValid) {
			var str = $("#edit_allergy_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/edit_allergy');?>",
					data: str,
					dataType: "json",
					success: function(data){
						if (data.message == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data.message);
							jQuery("#allergies").trigger("reloadGrid");
							$('#edit_allergy_form').clearForm();
							$('#edit_allergy_form').hide('fast');
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_allergy").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_allergy").click(function(){
		$('#edit_allergy_form').clearForm();
		$('#edit_allergy_form').hide('fast');
	});
	
	//Immunization
	$("#imm_load").load('<?php echo site_url("provider/chartmenu/imm_load");?>');
	
	//Alerts
	$("#alerts_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#alerts").jqGrid('GridUnload');
			jQuery("#alerts").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/alerts/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Due Date','Alert','Description'],
				colModel:[
					{name:'alert_id',index:'alert_id',width:1,hidden:true},
					{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'alert',index:'alert',width:200},
					{name:'alert_description',index:'alert',width:430}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#alerts_pager1'),
				sortname: 'alert_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Pending Alerts and Tasks",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#alerts_pager1',{search:false,edit:false,add:false,del:false});
			jQuery("#alerts_complete").jqGrid('GridUnload');
			jQuery("#alerts_complete").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/alerts_complete/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Due Date','Alert','Description'],
				colModel:[
					{name:'alert_id',index:'alert_id',width:1,hidden:true},
					{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'alert',index:'alert',width:200},
					{name:'alert_description',index:'alert',width:430}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#alerts_complete_pager'),
				sortname: 'alert_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Completed Alerts and Tasks",
			 	height: "100%",
			 	hiddengrid: true,
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#alerts_complete_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#alerts_not_complete").jqGrid('GridUnload');
			jQuery("#alerts_not_complete").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/alerts_not_complete/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Due Date','Alert','Description','Reason'],
				colModel:[
					{name:'alert_id',index:'alert_id',width:1,hidden:true},
					{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'alert',index:'alert',width:150},
					{name:'alert_description',index:'alert',width:280},
					{name:'alert_reason_not_complete',index:'alert_reason_not_complete',width:195}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#alerts_not_complete_pager'),
				sortname: 'alert_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Alerts and Tasks Not Completed",
			 	hiddengrid: true,
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#alerts_not_complete_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$('#edit_alert_form').clearForm();
			$('#edit_alert_form1').clearForm();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/alerts_list');?>",
				success: function(data){
					$('#alerts_tip').html(data);
				}
			});	
		}
	});
	$("#alerts_list").click(function() {
		$("#alerts_list_dialog").dialog('open');
		$('#edit_alert_form').hide('fast');
		$('#edit_alert_form1').hide('fast');	
	});
	$("#alert_date_active").mask("99/99/9999");
	$("#alert_date_active").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#alert").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/alert');?>",
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
	$("#alert_provider").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/users1');?>",
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
			$('#alert_provider_id').val(ui.item.id);
		}
	});
	$("#alert_description").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/alert_description');?>",
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
	$("#alert_reason_not_complete").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/alert_reason_not_complete');?>",
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
	$("#add_alert").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#add_alert").click(function(){
		$('#edit_alert_form').clearForm();
		var currentDate = getCurrentDate();
		$('#alert_date_active').val(currentDate);
		$('#edit_alert_form').show('fast');
		$("#alert").focus();
	});
	$("#edit_alert").button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
	$("#edit_alert").click(function(){
		var item = jQuery("#alerts").getGridParam('selrow');
		if(item){
			jQuery("#alerts").GridToForm(item,"#edit_alert_form");
			var date = $('#alert_date_active').val();
			var edit_date = editDate(date);
			$('#alert_date_active').val(edit_date);
			$('#edit_alert_form').show('fast');
			$("#alert").focus();
		} else {
			$.jGrowl("Please select alert to edit!")
		}
	});
	$("#complete_alert").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	$("#complete_alert").click(function(){
		var item = jQuery("#alerts").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/complete_alert');?>",
				data: "alert_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#alerts").trigger("reloadGrid");
					jQuery("#alerts_complete").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select alert to mark as complete!")
		}
	});
	$("#incomplete_alert").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#incomplete_alert").click(function(){
		var item = jQuery("#alerts").getGridParam('selrow');
		if(item){
			$("#alert_id1").val(item);
			$('#edit_alert_form1').show('fast');
		} else {
			$.jGrowl("Please select alert to mark as incomplete!")
		}
	});
	$("#delete_alert").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#delete_alert").click(function(){
		var item = jQuery("#alerts").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this alert?')){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/delete_alert');?>",
					data: "alert_id=" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#alerts").trigger("reloadGrid");
					}
				});
			}
		} else {
			$.jGrowl("Please select alert to delete!")
		}
	});
	$("#save_alert").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_alert").click(function(){
		var alert = $("#alert");
		var bValid = true;
		bValid = bValid && checkEmpty(alert,"Alert");
		if (bValid) {
			var str = $("#edit_alert_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/edit_alert');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						jQuery("#alerts").trigger("reloadGrid");
						$('#edit_alert_form').clearForm();
						$('#edit_alert_form').hide('fast');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_alert").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_alert").click(function(){
		$('#edit_alert_form').clearForm();
		$('#edit_alert_form').hide('fast');
	});
	$("#save_alert1").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_alert1").click(function(){
		var alert = $("#alert_reason_not_complete");
		var bValid = true;
		bValid = bValid && checkEmpty(alert,"Reason");
		if (bValid) {
			var str = $("#edit_alert_form1").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/incomplete_alert');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						jQuery("#alerts").trigger("reloadGrid");
						jQuery("#alerts_not_complete").trigger("reloadGrid");
						var item = $("#alert_id1").val();
						$('#edit_alert_form1').clearForm();
						$('#edit_alert_form1').hide('fast');
						
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_alert1").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_alert1").click(function(){
		$('#edit_alert_form1').clearForm();
		$('#edit_alert_form1').hide('fast');
	});
	//Documents
	function refresh_documents() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('search/documents_count');?>",
			dataType: "json",
			success: function(data){
				jQuery("#labs").jqGrid('setCaption', 'Labs: ' + data.labs_count);
				jQuery("#radiology").jqGrid('setCaption', 'Imaging: ' + data.radiology_count);
				jQuery("#cardiopulm").jqGrid('setCaption', 'Cardiopulmonary: ' + data.cardiopulm_count);
				jQuery("#endoscopy").jqGrid('setCaption', 'Endoscopy: ' + data.endoscopy_count);
				jQuery("#referrals").jqGrid('setCaption', 'Referrals: ' + data.referrals_count);
				jQuery("#past_records").jqGrid('setCaption', 'Past Records: ' + data.past_records_count);
				jQuery("#outside_forms").jqGrid('setCaption', 'Outside Forms: ' + data.outside_forms_count);
				jQuery("#letters").jqGrid('setCaption', 'Letters: ' + data.letters_count);
			}
		});
	}
	$("#documents_list_dialog").dialog({ 
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		open: function() {
			jQuery("#labs").jqGrid('GridUnload');
			jQuery("#labs").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/labs/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager8'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Labs",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/view_documents1');?>/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager8',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#pager8',{
				caption:"Edit", 
				buttonicon:"ui-icon-pencil", 
				onClickButton: function(){ 
					var id = jQuery("#labs").getGridParam('selrow');
					if(id){
						jQuery("#labs").GridToForm(id,"#documents_edit_form");
						var date = $('#menu_documents_date').val();
						var edit_date = editDate(date);
						$('#menu_documents_date').val(edit_date);
						documents_tags();
						$('#documents_edit_dialog').dialog('open');
						$("#menu_documents_from").focus();
					} else {
						$.jGrowl('Choose document to edit!');
					}
				}, 
				position:"last"
			}).navButtonAdd('#pager8',{
				caption:"Delete", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery("#labs").getGridParam('selrow');
					if(id){
						if(confirm('Are you sure you want to delete this document?')){ 
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/delete_document');?>",
								data: "documents_id=" + id,
								success: function(data){
									$.jGrowl(data);
									jQuery("#labs").trigger("reloadGrid");
									refresh_documents();
								}
							});
						}
					} else {
						$.jGrowl('Choose document to delete!');
					}
				}, 
				position:"last"
			});
			jQuery("#radiology").jqGrid('GridUnload');
			jQuery("#radiology").jqGrid({
				url:"<?php echo site_url ('provider/chartmenu/radiology/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager9'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Imaging",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/view_documents1');?>/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager9',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#pager9',{
				caption:"Edit", 
				buttonicon:"ui-icon-pencil", 
				onClickButton: function(){ 
					var id = jQuery("#radiology").getGridParam('selrow');
					if(id){
						jQuery("#radiology").GridToForm(id,"#documents_edit_form");
						var date = $('#menu_documents_date').val();
						var edit_date = editDate(date);
						$('#menu_documents_date').val(edit_date);
						documents_tags();
						$('#documents_edit_dialog').dialog('open');
						$("#menu_documents_from").focus();
					} else {
						$.jGrowl('Choose document to edit!');
					}
				}, 
				position:"last"
			}).navButtonAdd('#pager9',{
				caption:"Delete", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery("#radiology").getGridParam('selrow');
					if(id){
						if(confirm('Are you sure you want to delete this document?')){ 
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/delete_document');?>",
								data: "documents_id=" + id,
								success: function(data){
									$.jGrowl(data);
									jQuery("#radiology").trigger("reloadGrid");
									refresh_documents();
								}
							});
						}
					} else {
						$.jGrowl('Choose document to delete!');
					}
				}, 
				position:"last"
			});
			jQuery("#cardiopulm").jqGrid('GridUnload');
			jQuery("#cardiopulm").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/cardiopulm/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager10'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Cardiopulmonary",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/view_documents1');?>/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager10',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#pager10',{
				caption:"Edit", 
				buttonicon:"ui-icon-pencil", 
				onClickButton: function(){ 
					var id = jQuery("#cardiopulm").getGridParam('selrow');
					if(id){
						jQuery("#cardiopulm").GridToForm(id,"#documents_edit_form");
						var date = $('#menu_documents_date').val();
						var edit_date = editDate(date);
						$('#menu_documents_date').val(edit_date);
						documents_view_tags();
						$('#documents_edit_dialog').dialog('open');
						$("#menu_documents_from").focus();
					} else {
						$.jGrowl('Choose document to edit!');
					}
				}, 
				position:"last"
			}).navButtonAdd('#pager10',{
				caption:"Delete", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery("#cardiopulm").getGridParam('selrow');
					if(id){
						if(confirm('Are you sure you want to delete this document?')){ 
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/delete_document');?>",
								data: "documents_id=" + id,
								success: function(data){
									$.jGrowl(data);
									jQuery("#cardiopulm").trigger("reloadGrid");
									refresh_documents();
								}
							});
						}
					} else {
						$.jGrowl('Choose document to delete!');
					}
				}, 
				position:"last"
			});
			jQuery("#endoscopy").jqGrid('GridUnload');
			jQuery("#endoscopy").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/endoscopy/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager11'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Endoscopy: " + $('#endoscopy_count').val(),
				hiddengrid: true,
				height: "100%",
				onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/view_documents1');?>/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager11',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#pager11',{
				caption:"Edit", 
				buttonicon:"ui-icon-pencil", 
				onClickButton: function(){ 
					var id = jQuery("#endoscopy").getGridParam('selrow');
					if(id){
						jQuery("#endoscopy").GridToForm(id,"#documents_edit_form");
						var date = $('#menu_documents_date').val();
						var edit_date = editDate(date);
						$('#menu_documents_date').val(edit_date);
						documents_tags();
						$('#documents_edit_dialog').dialog('open');
						$("#menu_documents_from").focus();
					} else {
						$.jGrowl('Choose document to edit!');
					}
				}, 
				position:"last"
			}).navButtonAdd('#pager11',{
				caption:"Delete", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery("#endoscopy").getGridParam('selrow');
					if(id){
						if(confirm('Are you sure you want to delete this document?')){ 
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/delete_document');?>",
								data: "documents_id=" + id,
								success: function(data){
									$.jGrowl(data);
									jQuery("#endosocpy").trigger("reloadGrid");
									refresh_documents();
								}
							});
						}
					} else {
						$.jGrowl('Choose document to delete!');
					}
				}, 
				position:"last"
			});
			jQuery("#referrals").jqGrid('GridUnload');
			jQuery("#referrals").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/referrals/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager12'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Referrals",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/view_documents1');?>/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager12',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#pager12',{
				caption:"Edit", 
				buttonicon:"ui-icon-pencil", 
				onClickButton: function(){ 
					var id = jQuery("#referrals").getGridParam('selrow');
					if(id){
						jQuery("#referrals").GridToForm(id,"#documents_edit_form");
						var date = $('#menu_documents_date').val();
						var edit_date = editDate(date);
						$('#menu_documents_date').val(edit_date);
						documents_tags();
						$('#documents_edit_dialog').dialog('open');
						$("#menu_documents_from").focus();
		 			} else {
						$.jGrowl('Choose document to edit!');
					}
				}, 
				position:"last"
			}).navButtonAdd('#pager12',{
				caption:"Delete", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery("#referrals").getGridParam('selrow');
					if(id){
						if(confirm('Are you sure you want to delete this document?')){ 
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/delete_document');?>",
								data: "documents_id=" + id,
								success: function(data){
									$.jGrowl(data);
									jQuery("#referrals").trigger("reloadGrid");
									refresh_documents();
								}
							});
						}
					} else {
						$.jGrowl('Choose document to delete!');
					}
				}, 
				position:"last"
			});
			jQuery("#past_records").jqGrid('GridUnload');
			jQuery("#past_records").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/past_records/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager13'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Past Records",
				hiddengrid: true,
				height: "100%",
				onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/view_documents1');?>/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager13',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#pager13',{
				caption:"Edit", 
				buttonicon:"ui-icon-pencil", 
				onClickButton: function(){ 
					var id = jQuery("#past_records").getGridParam('selrow');
					if(id){
						jQuery("#past_records").GridToForm(id,"#documents_edit_form");
						var date = $('#menu_documents_date').val();
						var edit_date = editDate(date);
						$('#menu_documents_date').val(edit_date);
						documents_tags();
						$('#documents_edit_dialog').dialog('open');
						$("#menu_documents_from").focus();
					} else {
						$.jGrowl('Choose document to edit!');
					}
				}, 
				position:"last"
			}).navButtonAdd('#pager13',{
				caption:"Delete", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery("#past_records").getGridParam('selrow');
					if(id){
						if(confirm('Are you sure you want to delete this document?')){ 
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/delete_document');?>",
								data: "documents_id=" + id,
								success: function(data){
									$.jGrowl(data);
									jQuery("#past_records").trigger("reloadGrid");
									refresh_documents();
								}
							});
						}
					} else {
						$.jGrowl('Choose document to delete!');
					}
				}, 
				position:"last"
			});
			jQuery("#outside_forms").jqGrid('GridUnload');
			jQuery("#outside_forms").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/other_forms/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager14'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Other Forms",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/view_documents1');?>/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager14',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#pager14',{
				caption:"Edit", 
				buttonicon:"ui-icon-pencil", 
				onClickButton: function(){ 
					var id = jQuery("#outside_forms").getGridParam('selrow');
					if(id){
						jQuery("#outside_forms").GridToForm(id,"#documents_edit_form");
						var date = $('#menu_documents_date').val();
						var edit_date = editDate(date);
						$('#menu_documents_date').val(edit_date);
						documents_tags();
						$('#documents_edit_dialog').dialog('open');
						$("#menu_documents_from").focus();
					} else {
						$.jGrowl('Choose document to edit!');
					}
				}, 
				position:"last"
			}).navButtonAdd('#pager14',{
				caption:"Delete", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery("#outside_forms").getGridParam('selrow');
					if(id){
						if(confirm('Are you sure you want to delete this document?')){ 
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/delete_document');?>",
								data: "documents_id=" + id,
								success: function(data){
									$.jGrowl(data);
									jQuery("#outside_forms").trigger("reloadGrid");
									refresh_documents();
								}
							});
						}
					} else {
						$.jGrowl('Choose document to delete!');
					}
				}, 
				position:"last"
			});
			jQuery("#letters").jqGrid('GridUnload');
			jQuery("#letters").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/letters/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager15'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Letters",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "<?php echo site_url('provider/chartmenu/view_documents1');?>/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager15',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#pager15',{
				caption:"Edit", 
				buttonicon:"ui-icon-pencil", 
				onClickButton: function(){ 
					var id = jQuery("#letters").getGridParam('selrow');
					if(id){
						jQuery("#letters").GridToForm(id,"#documents_edit_form");
						var date = $('#menu_documents_date').val();
						var edit_date = editDate(date);
						$('#menu_documents_date').val(edit_date);
						documents_tags();
						$('#documents_edit_dialog').dialog('open');
						$("#menu_documents_from").focus();
					} else {
						$.jGrowl('Choose document to edit!');
					}
				}, 
				position:"last"
			}).navButtonAdd('#pager15',{
				caption:"Delete", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery("#letters").getGridParam('selrow');
					if(id){
						if(confirm('Are you sure you want to delete this document?')){ 
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('provider/chartmenu/delete_document');?>",
								data: "documents_id=" + id,
								success: function(data){
									$.jGrowl(data);
									jQuery("#letters").trigger("reloadGrid");
									refresh_documents()
								}
							});
						}
					} else {
						$.jGrowl('Choose document to delete!');
					}
				}, 
				position:"last"
			});
			refresh_documents();
		}
	});
	$("#documents_view_dialog").dialog({ 
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		close: function(event, ui) {
			var a = $("#document_filepath").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/close_document');?>",
				data: "document_filepath=" + a,
				success: function(data){
					$("#embedURL").html('');
					$("#document_filepath").val('');
					$("#view_document_id").val('');
				}
			});	
		}
	});
	$("#save_document").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_document").click(function() {
		var id = $("#view_document_id").val();
		window.open("<?php echo site_url('provider/chartmenu/view_documents');?>/" + id);
	});
	$("#documents_list").click(function() {
		$("#documents_list_dialog").dialog('open');
	});
	$("#menu_documents_from").autocomplete({
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
	$("#menu_documents_desc").autocomplete({
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
	$("#menu_documents_type").addOption({"Laboratory":"Laboratory","Imaging":"Imaging","Cardiopulmonary":"Cardiopulmonary","Endoscopy":"Endoscopy","Referrals":"Referrals","Past Records":"Past Records","Other Forms":"Other Forms","Letters":"Letters"}, false);
	$("#menu_documents_date").mask("99/99/9999");
	$("#menu_documents_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#documents_edit_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		beforeclose: function(event, ui) {
			var a = $("#menu_documents_id").val();
			if(a != ''){
				if(confirm('You have not completed editing the document.  Are you sure you want to close this window?')){ 
					$('#documents_edit_form').clearForm();
					return true;
				} else {
					return false;
				}
			} else {
				$('#documents_edit_form').clearForm();
				return true;
			}
		}
	});
	$("#save_menu_documents").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#save_menu_documents").click(function() {
		var type = $("#menu_documents_type");
		var date = $("#menu_documents_date");
		var bValid = true;
		bValid = bValid && checkEmpty(type,"Documents Type");
		bValid = bValid && checkEmpty(date,"Documents Date");
		if (bValid) {
			var str = $("#documents_edit_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/chartmenu/edit_document');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$('#documents_edit_form').clearForm();
						$('#documents_edit_dialog').dialog('close');
						jQuery("#labs").trigger("reloadGrid");
						jQuery("#radiology").trigger("reloadGrid");
						jQuery("#cardiopulm").trigger("reloadGrid");
						jQuery("#endoscopy").trigger("reloadGrid");
						jQuery("#referrals").trigger("reloadGrid");
						jQuery("#past_records").trigger("reloadGrid");
						jQuery("#outside_forms").trigger("reloadGrid");
						jQuery("#letters").trigger("reloadGrid");
						refresh_documents();
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_menu_documents").button({
		icons: {
			primary: "ui-icon-trash"
		}
	});
	$("#cancel_menu_documents").click(function() {
		$('#documents_edit_form').clearForm();
		$('#documents_edit_dialog').dialog('close');
	});
	$("#menu_new_letter").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#menu_new_letter").click(function() {
		$("#letter_dialog").dialog('open');
	});
	$("#menu_tests").button({
		icons: {
			primary: "ui-icon-image"
		}
	});
	$("#menu_tests").click(function() {
		$("#tests_dialog").dialog('open');
	});
	$("#tests_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		open: function(event, ui) {
			$("#chart_loading").hide();
			jQuery("#tests_list").jqGrid('GridUnload');
			jQuery("#tests_list").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/tests/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Test','Result','Unit','Normal','Flags','Type'],
				colModel:[
					{name:'tests_id',index:'tests_id',width:1,hidden:true},
					{name:'test_datetime',index:'test_datetime',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'test_name',index:'test_name',width:310},
					{name:'test_result',index:'test_result',width:120},
					{name:'test_units',index:'test_units',width:50},
					{name:'test_reference',index:'test_reference',width:100},
					{name:'test_flags',index:'test_flags',width:50,
						cellattr: function (rowId, val, rawObject, cm, rdata) {
							if (rawObject.test_flags == "L") {
								var response = "Below low normal";
							}
							if (rawObject.test_flags == "H") {
								var response = "Above high normal";
							}
							if (rawObject.test_flags == "LL") {
								var response = "Below low panic limits";
							}
							if (rawObject.test_flags == "HH") {
								var response = "Above high panic limits";
							}
							if (rawObject.test_flags == "<") {
								var response = "Below absolute low-off instrument scale";
							}
							if (rawObject.test_flags == ">") {
								var response = "Above absolute high-off instrument scale";
							}
							if (rawObject.test_flags == "N") {
								var response = "Normal";
							}
							if (rawObject.test_flags == "A") {
								var response = "Abnormal";
							}
							if (rawObject.test_flags == "AA") {
								var response = "Very abnormal";
							}
							if (rawObject.test_flags == "U") {
								var response = "Significant change up";
							}
							if (rawObject.test_flags == "D") {
								var response = "Significant change down";
							}
							if (rawObject.test_flags == "B") {
								var response = "Better";
							}
							if (rawObject.test_flags == "W") {
								var response = "Worse";
							}
							if (rawObject.test_flags == "S") {
								var response = "Susceptible";
							}
							if (rawObject.test_flags == "R") {
								var response = "Resistant";
							}
							if (rawObject.test_flags == "I") {
								var response = "Intermediate";
							}
							if (rawObject.test_flags == "MS") {
								var response = "Moderately susceptible";
							}
							if (rawObject.test_flags == "VS") {
								var response = "Very susceptible";
							}
							if (rawObject.test_flags == "") {
								var response = "";
							}
							return 'title="' + response + '"';
						}
					},
					{name:'test_type',index:'test_type',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#tests_list_pager'),
				sortname: 'test_datetime',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Test Results",
			 	height: "100%",
			 	gridview: true,
			 	rowattr: function (rd) {
					if (rd.test_flags == "HH" || rd.test_flags == "LL" || rd.test_flags == "H" || rd.test_flags == "L") {
						return {"class": "myAltRowClass"};
					}
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#tests_list_pager',{search:false,edit:false,add:false,del:false});
			$("#chart_results").button().click(function() {
				var item = jQuery("#tests_list").getGridParam('selrow');
				if (item) {
					$("#chart_loading").show();
					var options = {
						chart: {
							renderTo: 'tests_container',
							defaultSeriesType: 'line',
							marginRight: 130,
							marginBottom: 50,
							width: 750
						},
						title: {
							text: '',
							x: -20
						},
						xAxis: {
							title: {
								text: ''
							},
							type: 'datetime'
						},
						yAxis: {
							title: {
								text: ''
							},
							plotLines: [{
								value: 0,
								width: 1,
								color: '#808080'
							}]
						},
						legend: {
							layout: 'vertical',
							align: 'right',
							verticalAlign: 'top',
							x: -10,
							y: 100,
							borderWidth: 0
						},
						series: [
							{type: 'line', data: []}
						],
						credits: {
							href: 'http://noshemr.wordpress.com',
							text: 'NOSH ChartingSystem'
						}
					};
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/chart_test');?>/" + item,
						dataType: "json",
						success: function(data){
							options.title.text = data.title;
							options.xAxis.title.text = data.xaxis;
							options.yAxis.title.text = data.yaxis;
							options.series[0].name = data.name;
							newData = [];
							for (i in data.patient) {
								newData.push( [ new Date(data.patient[i][0]).getTime(), parseFloat(data.patient[i][1]) ] );
							}
							options.series[0].data = newData;
							var chart = new Highcharts.Chart(options);
							$("#chart_loading").hide();
						}
					});
				} else {
					$.jGrowl('Choose item to chart!');
				}
			});
		},
		close: function (event, ui) {
			$("#tests_container").html('');
		}
	});
	var timeoutHnd1;
	function doSearch1(ev){ 
		if(timeoutHnd1) 
			clearTimeout(timeoutHnd1);
			timeoutHnd1 = setTimeout(gridReload1,500);
	}
	function gridReload1(){ 
		var mask = jQuery("#search_all_tests").val();
		jQuery("#tests_list").setGridParam({url:"<?php echo site_url('provider/chartmenu/tests');?>/"+mask,page:1}).trigger("reloadGrid");
	}
	//Prevention
	$("#prevention_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			$('#prevention_items').html('');
			$('#prevention_load').show('fast');
		}
	});
	$("#prevention_list").click(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/prevention');?>",
			success: function(data){
				$('#prevention_items').html(data);
				$('#prevention_load').hide('fast');
			}
		});	
		$("#prevention_list_dialog").dialog('open');
	});
	//Letters
	$("#letter_load").load('<?php echo site_url("provider/chartmenu/letter_load");?>');
	//Billing
	$("#billing_load").load('<?php echo site_url("provider/chartmenu/billing_load");?>');
	//Printing
	$("#print_load").load('<?php echo site_url("provider/chartmenu/print_load");?>');
	$('.nosh_button').button();
	$(".nosh_button_save").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$(".nosh_button_cancel").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$(".nosh_button_calculator").button({
		icons: {
			primary: "ui-icon-calculator"
		}
	});
	$(".nosh_button_check").button({
		icons: {
			primary: "ui-icon-check"
		}
	});
	//MTM
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('start/check_mtm_extension');?>",
		dataType: "json",
		success: function(data){
			if (data.response == 'y') {
				$("#menucolumn2").append(data.row);
				$("#mtm_load").load('<?php echo site_url("provider/chartmenu/mtm_load");?>');
			}
		}
	});
	$("#t_messages_tags").tagit({
		tagSource: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/search_tags');?>",
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
		tagsChanged: function(a, b) {
			if (b == "added") {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/save_tag/t_messages_id') . '/';?>" + $("#t_messages_id").val(),
					data: 'tag=' + a
				});
			}
			if (b == "popped") {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/remove_tag/t_messages_id') . '/';?>" + $("#t_messages_id").val(),
					data: 'tag=' + a
				});
			}
		}
	});
	function t_messages_tags() {
		var id = $("#t_messages_id").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('search/get_tags/t_messages_id');?>" + "/" + id,
			dataType: "json",
			success: function(data){
				$("#t_messages_tags").tagit("fill",data);
			}
		});
	}
	$("#documents_tags").tagit({
		tagSource: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/search_tags');?>",
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
		tagsChanged: function(a, b) {
			if (b == "added") {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/save_tag/documents_id') . '/';?>" + $("#menu_documents_id").val(),
					data: 'tag=' + a
				});
			}
			if (b == "popped") {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/remove_tag/documents_id') . '/';?>" + $("#menu_documents_id").val(),
					data: 'tag=' + a
				});
			}
		}
	});
	function documents_tags() {
		var id = $("#menu_documents_id").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('search/get_tags/documents_id');?>" + "/" + id,
			dataType: "json",
			success: function(data){
				$("#documents_tags").tagit("fill",data);
			}
		});
	}
	$("#documents_view_tags").tagit({
		tagSource: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/search_tags');?>",
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
		tagsChanged: function(a, b) {
			if (b == "added") {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/save_tag/documents_id') . '/';?>" + $("#view_document_id").val(),
					data: 'tag=' + a
				});
			}
			if (b == "popped") {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/remove_tag/documents_id') . '/';?>" + $("#view_document_id").val(),
					data: 'tag=' + a
				});
			}
		}
	});
	function documents_view_tags() {
		var id = $("#view_document_id").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('search/get_tags/documents_id');?>" + "/" + id,
			dataType: "json",
			success: function(data){
				$("#documents_view_tags").tagit("fill",data);
			}
		});
	}
</script>

