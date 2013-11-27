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
		<img src="<?php echo base_url().'images/printmgr.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" >
		<a href="#" id="print_list">Print</a><br />
	</div>
	<div id="menucolumn2" class="menucolumn">
		<img src="<?php echo base_url().'images/search.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" >
		<a href="#" id="documents_list">Documents</a><br />
		<img src="<?php echo base_url().'images/chart2.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" >
		<a href="#" id="encounter_list">Encounters</a><br />
		<img src="<?php echo base_url().'images/chart.png';?>" border="0" height="30" width="30" style="vertical-align:middle;" id="issues_list_img" class="menu_tooltip">
		<a href="#" id="issues_list" class="menu_tooltip">Issues</a><br />
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
		<hr class="ui-state-default"/>
		<input type="hidden" name="pid" id="pid"/>
		<input type="hidden" name="race_code" id="race_code">
		<input type="hidden" name="ethnicity_code" id="ethnicity_code">
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
	<table id="messages" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="messages_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<div id="messages_main_dialog" title="Message">
	<div id="message_view"></div>
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
	<table id="medications" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="medications_pager" class="scroll" style="text-align:center;"></div><br>
	<table id="medications_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="medications_inactive_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<div id="allergies_list_dialog" title="Allergies">
	<table id="allergies" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="allergies_pager" class="scroll" style="text-align:center;"></div><br>
	<table id="allergies_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="allergies_inactive_pager" class="scroll" style="text-align:center;"></div><br>
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
<div id="documents_view_dialog" title="Documents Viewer">
	<input type="hidden" id="view_document_id"/>
	<input type="hidden" id="document_filepath"/>
	<button type="button" id="save_document">Save</button><br>
	<div id="embedURL"></div>
</div>
<div id="billing_load"></div>
<div id="print_load"></div>
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
				url: "<?php echo site_url('billing/chartmenu/');?>/" + id,
				success: function(data){
					elem.tooltip('option', 'content', data);
					elem.tooltip("option","position",{ my: "left+15 center", at: "right bottom", collision: "flipfit" });
				}
			});
		}
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('billing/chartmenu/demographics_load');?>",
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
				url: "<?php echo site_url('billing/chartmenu/demographics');?>",
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
				url:"<?php echo site_url('billing/chartmenu/insurance/');?>",
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
				url:"<?php echo site_url('billing/chartmenu/insurance_inactive/');?>",
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
						$("#menu_insurance_plan_select").addOption({"":"No insurance provider."});
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
						url: "<?php echo site_url('billing/chartmenu/edit_demographics');?>",
						data: str,
						success: function(data){
							if (data == 'Close Chart') {
								window.location = "<?php echo site_url();?>";
							} else {
								$.jGrowl(data);
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('billing/chartmenu/demographics_load');?>",
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
					url: "<?php echo site_url('billing/chartmenu/edit_demographics');?>",
					data: str,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('billing/chartmenu/demographics_load');?>",
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
						url: "<?php echo site_url('billing/chartmenu/edit_demographics');?>",
						data: str,
						success: function(data){
							if (data == 'Close Chart') {
								window.location = "<?php echo site_url();?>";
							} else {
								$.jGrowl(data);
								$("#demographics_list_dialog").dialog('close');
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('billing/chartmenu/demographics_load');?>",
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
					url: "<?php echo site_url('billing/chartmenu/edit_demographics');?>",
					data: str,
					success: function(data){
						if (data == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data);
							$("#demographics_list_dialog").dialog('close');
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('billing/chartmenu/demographics_load');?>",
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
				url: "<?php echo site_url('billing/chartmenu/inactivate_insurance');?>",
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
					url: "<?php echo site_url('billing/chartmenu/delete_insurance');?>",
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
				url: "<?php echo site_url('billing/chartmenu/reactivate_insurance');?>",
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
							url: "<?php echo site_url('billing/chartmenu/edit_insurance');?>",
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
				var facility = $("#menu_insurance_plan_facility");
				var bValid = true;
				bValid = bValid && checkEmpty(facility,"Insurance Plan Name");
				if (bValid) {
					var str = $("#edit_menu_insurance_plan_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('billing/chartmenu/edit_insurance_provider');?>",
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
				url: "<?php echo site_url('billing/chartmenu/copy_address');?>",
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
			url: "<?php echo site_url('billing/chartmenu/copy_address');?>",
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
				url:"<?php echo site_url('billing/chartmenu/messages/');?>",
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
						$.jGrowl("You cannot view or edit unsigned messages.");
					}
					if (signed == 'Yes') {
						$("#messages_main_dialog").dialog('open');
						$("#edit_message_fieldset").hide('fast');
						var row = jQuery("#messages").getRowData(id);
						var text = '<br><strong>Date:</strong>  ' + row['t_messages_dos'] + '<br><br><strong>Subject:</strong>  ' + row['t_messages_subject'] + '<br><br><strong>Message:</strong> ' + row['t_messages_message']; 
						$("#message_view").html(text);
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$("#edit_message_fieldset").hide('fast');
			$('#edit_message_form').clearForm();
		}
	});
	$("#messages_main_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false
	});
	$("#messages_list").click(function() {
		$("#messages_list_dialog").dialog('open');
		$("#edit_message_fieldset").hide('fast');
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
				url:"<?php echo site_url('billing/chartmenu/encounters/');?>",
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
			 			$.jGrowl('User priviledges prevent you from viewing or editing unsigned notes!');
			 		}
			 		if (status == "Signed") {
			 			$("#encounter_view").load('<?php echo site_url("billing/encounters/modal_view");?>/' + id);
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
	$("#issues_load").load('<?php echo site_url("billing/chartmenu/issues_load");?>');
	
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
				url:"<?php echo site_url('billing/chartmenu/medications/');?>",
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
						url: "<?php echo site_url('billing/chartmenu/past_medication');?>",
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
				url:"<?php echo site_url('billing/chartmenu/medications_inactive/');?>",
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
		}
	});
	$("#medications_list").click(function() {
		$("#medications_list_dialog").dialog('open');
	});
	
	//Supplements
	$("#supplements_load").load('<?php echo site_url("billing/chartmenu/supplements_load");?>');
	
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
				url:"<?php echo site_url('billing/chartmenu/allergies/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Medication','Reason'],
				colModel:[
					{name:'allergies_id',index:'allergies_id',width:1,hidden:true},
					{name:'allergies_date_active',index:'allergies_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'allergies_med',index:'allergies_med',width:310},
					{name:'allergies_reaction',index:'allergies_reaction',width:355}
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
				url:"<?php echo site_url('billing/chartmenu/allergies_inactive/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Medication','Reason'],
				colModel:[
					{name:'allergies_id',index:'allergies_id',width:1,hidden:true},
					{name:'allergies_date_active',index:'allergies_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'allergies_med',index:'allergies_med',width:310},
					{name:'allergies_reaction',index:'allergies_reaction',width:355}
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
		}
	});
	$("#allergies_list").click(function() {
		$("#allergies_list_dialog").dialog('open');
	});
	
	//Immunization
	$("#imm_load").load('<?php echo site_url("billing/chartmenu/imm_load");?>');
	
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
				url:"<?php echo site_url('billing/chartmenu/alerts/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Due Date','Alert','Description','Provider'],
				colModel:[
					{name:'alert_id',index:'alert_id',width:1,hidden:true},
					{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'alert',index:'alert',width:200},
					{name:'alert_description',index:'alert',width:430},
					{name:'alert_provider',index:'alert_provider',width:1,hidden:true}
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
				url:"<?php echo site_url('billing/chartmenu/alerts_complete/');?>",
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
				url:"<?php echo site_url('billing/chartmenu/alerts_not_complete/');?>",
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
				url: "<?php echo site_url('billing/chartmenu/complete_alert');?>",
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
					url: "<?php echo site_url('billing/chartmenu/delete_alert');?>",
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
		var id = $("#alert_provider_id");
		var bValid = true;
		bValid = bValid && checkEmpty(alert,"Alert");
		bValid = bValid && checkEmpty(id,"Provider to Alert");
		if (bValid) {
			var str = $("#edit_alert_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/chartmenu/edit_alert');?>",
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
					url: "<?php echo site_url('billing/chartmenu/incomplete_alert');?>",
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
		open: function () {
			jQuery("#labs").jqGrid('GridUnload');
			jQuery("#labs").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/labs/');?>",
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
			 	onSelectRow: function(id){
			 		$("#view_document_id").val(id);
			 		$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/chartmenu/view_documents1');?>/" + id,
						dataType: "json",
						success: function(data){
							$("#embedURL").html(data.html);
							$("#document_filepath").val(data.filepath);
							$("#documents_view_dialog").dialog('open');
						}
					});
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager8',{search:false,edit:false,add:false,del:false
			});
			jQuery("#radiology").jqGrid('GridUnload');
			jQuery("#radiology").jqGrid({
				url:"<?php echo site_url ('billing/chartmenu/radiology/');?>",
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
			 	onSelectRow: function(id){
			 		$("#view_document_id").val(id);
			 		$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/chartmenu/view_documents1');?>/" + id,
						dataType: "json",
						success: function(data){
							$("#embedURL").html(data.html);
							$("#document_filepath").val(data.filepath);
							$("#documents_view_dialog").dialog('open');
						}
					});
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager9',{search:false,edit:false,add:false,del:false
			});
			jQuery("#cardiopulm").jqGrid('GridUnload');
			jQuery("#cardiopulm").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/cardiopulm/');?>",
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
			 	onSelectRow: function(id){
			 		$("#view_document_id").val(id);
			 		$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/chartmenu/view_documents1');?>/" + id,
						dataType: "json",
						success: function(data){
							$("#embedURL").html(data.html);
							$("#document_filepath").val(data.filepath);
							$("#documents_view_dialog").dialog('open');
						}
					});
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager10',{search:false,edit:false,add:false,del:false
			});
			jQuery("#endoscopy").jqGrid('GridUnload');
			jQuery("#endoscopy").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/endoscopy/');?>",
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
			 	caption:"Endoscopy",
			 	hiddengrid: true,
			 	height: "100%",
			 	onSelectRow: function(id){
			 		$("#view_document_id").val(id);
			 		$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/chartmenu/view_documents1');?>/" + id,
						dataType: "json",
						success: function(data){
							$("#embedURL").html(data.html);
							$("#document_filepath").val(data.filepath);
							$("#documents_view_dialog").dialog('open');
						}
					});
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager11',{search:false,edit:false,add:false,del:false
			});
			jQuery("#referrals").jqGrid('GridUnload');
			jQuery("#referrals").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/referrals/');?>",
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
			 	onSelectRow: function(id){
			 		$("#view_document_id").val(id);
			 		$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/chartmenu/view_documents1');?>/" + id,
						dataType: "json",
						success: function(data){
							$("#embedURL").html(data.html);
							$("#document_filepath").val(data.filepath);
							$("#documents_view_dialog").dialog('open');
						}
					});
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager12',{search:false,edit:false,add:false,del:false
			});
			jQuery("#past_records").jqGrid('GridUnload');
			jQuery("#past_records").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/past_records/');?>",
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
			 	onSelectRow: function(id){
			 		$("#view_document_id").val(id);
			 		$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/chartmenu/view_documents1');?>/" + id,
						dataType: "json",
						success: function(data){
							$("#embedURL").html(data.html);
							$("#document_filepath").val(data.filepath);
							$("#documents_view_dialog").dialog('open');
						}
					});
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager13',{search:false,edit:false,add:false,del:false
			});
			jQuery("#outside_forms").jqGrid('GridUnload');
			jQuery("#outside_forms").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/other_forms/');?>",
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
			 	onSelectRow: function(id){
			 		$("#view_document_id").val(id);
			 		$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/chartmenu/view_documents1');?>/" + id,
						dataType: "json",
						success: function(data){
							$("#embedURL").html(data.html);
							$("#document_filepath").val(data.filepath);
							$("#documents_view_dialog").dialog('open');
						}
					});
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager14',{search:false,edit:false,add:false,del:false
			});
			jQuery("#letters").jqGrid('GridUnload');
			jQuery("#letters").jqGrid({
				url:"<?php echo site_url('billing/chartmenu/letters/');?>",
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
			 	onSelectRow: function(id){
			 		$("#view_document_id").val(id);
			 		$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/chartmenu/view_documents1');?>/" + id,
						dataType: "json",
						success: function(data){
							$("#embedURL").html(data.html);
							$("#document_filepath").val(data.filepath);
							$("#documents_view_dialog").dialog('open');
						}
					});
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager15',{search:false,edit:false,add:false,del:false
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
				url: "<?php echo site_url('billing/chartmenu/close_document');?>",
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
		window.open("<?php echo site_url('billing/chartmenu/view_documents');?>/" + id);
	});
	$("#documents_list").click(function() {
		$("#documents_list_dialog").dialog('open');
	});
	//Billing
	$("#billing_load").load('<?php echo site_url("billing/chartmenu/billing_load");?>');
	//Printing
	$("#print_load").load('<?php echo site_url("billing/chartmenu/print_load");?>');
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
</script>
						
