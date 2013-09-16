<div id="messages_ref_dialog" title="Referral Helper">
	<input type="hidden" name="messages_ref_origin" id="messages_ref_origin"/>
	<div name="edit_message_ref_form" id="edit_message_ref_form">
	<div id="messages_ref_grid">
		<button type="button" id="save_ref_helper"><div id="save_ref_helper_label"></div></button>
		<button type="button" id="cancel_ref_helper">Cancel</button>
		<hr class="ui-state-default"/>
		<table id="messages_ref_list" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_ref_list_pager" class="scroll" style="text-align:center;"></div><br>
		<input type="button" id="messages_add_ref" value="Add" class="messages_ref_button"/> 
		<input type="button" id="messages_edit_ref" value="Edit" class="messages_ref_button"/> 
		<input type="button" id="messages_resend_ref" value="Resend" class="messages_ref_button"/> 
		<input type="button" id="messages_delete_ref" value="Delete" class="messages_ref_button"/><br><br>
	</div>
	<div id="messages_ref_edit_fields">
		<button type="button" id="messages_ref_save">Save</button>
		<button type="button" id="messages_ref_cancel">Cancel</button>
		Provider: <select id ="messages_ref_provider_list" name="encounter_provider" class="text ui-widget-content ui-corner-all"></select>
		<div style="float:right;" "id="messages_ref_status"></div>
		<hr class="ui-state-default"/>
		<input type="hidden" name="orders_id" id="messages_ref_orders_id"/>
		<input type="hidden" name="t_messages_id" id="messages_ref_t_messages_id"/>
		<div id="messages_ref_accordion">
			<h3><a href="#">Referral Reason</a></h3>
			<div>
				<div style="display:block;float:left;width:310px">
					Preview:<br><textarea name="orders_referrals" id="messages_ref_orders" rows="10" style="width:290px" class="text ui-widget-content ui-corner-all" placeholder="Type a few letters of order to search."></textarea>
				</div>
				<div style="display:block;float:left;">
					Choose Template: <select id="messages_ref_template" class="text ui-widget-content ui-corner-all"></select><br>
					<button type="button" id="messages_ref_template_save">Copy</button><button type="button" id="messages_ref_orderslist_link">Edit Referral Templates</button><button type="button" id="messages_ref_orders_clear" class="messages_ref_button">Clear</button>
					<div class="ref_template_div">
						<br><form id="messages_ref_form" class="ui-widget"></form>
					</div>
				</div>
				<div id="add_test_cpt3" title="Add Order to Database">
					<input type="hidden" id="messages_ref"/>
					<input type="hidden" id="messages_ref_orders_text"/>
					Order Type: <select id="messages_ref_orders_type"></select><br>
					CPT Code (optional):<br><input type="text" name="messages_ref_cpt" id="messages_ref_cpt" style="width:400px" class="text ui-widget-content ui-corner-all"/><br>
					<div id="add_test_snomed_div3">
						SNOMED Code (optional):<br><input type="text" name="messages_ref_snomed" id="messages_ref_snomed" style="width:400px" class="text ui-widget-content ui-corner-all" placeholder="Type a few letters to search or select from hierarchy."/><br><br>
						SNOMED Database: Click on arrow to expand hierarchy.  Click on item to select code.<br>
						<div id="snomed_tree3" style="height:250px; overflow:auto;"></div>
					</div>
				</div>
				
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_referrals" id="messages_ref_orders" rows="8" style="width:500px" class="text ui-widget-content ui-corner-all"></textarea></td>
						<td valign="top"><br><input type="button" id="messages_ref_orders_edit" value="Edit" class="messages_ref_button"/><br><input type="button" id="messages_ref_orders_clear" value="Clear" class="messages_ref_button"/></td>
					</tr>
				</table>
				<div id="messages_ref1_fieldset">
					<fieldset class="ui-widget-content ui-corner-all">
						<legend>Reason</legend>
						<input type="hidden" name="messages_ref_id" id="messages_ref_id"/>
						<input type="button" id="messages_ref_reason1_button" value="Consultation" class="messages_ref_button"/> 
						<input type="button" id="messages_ref_reason2_button" value="Referral" class="messages_ref_button"/> 
						<input type="button" id="messages_ref_reason3_button" value="Physical Therapy" class="messages_ref_button"/> 
						<input type="button" id="messages_ref_reason4_button" value="Massage Therapy" class="messages_ref_button"/>
						<input type="button" id="messages_ref_reason5_button" value="Sleep Study" class="messages_ref_button"/><br>
					</fieldset><br>
					<div id="messages_ref_reason1" style="display: none">
						<div id="messages_ref_reason1_form">
							<fieldset class="ui-widget-content ui-corner-all">
								<legend>Consultation</legend>
								<table>
									<tbody>
										<tr>
											<td>Confirm diagnosis:</td>
											<td><input type="checkbox" name="messages_ref_reason1_confirm" id="messages_ref_reason1_confirm" value="Confirm the diagnosis." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Advise as to diagnosis:</td>
											<td><input type="checkbox" name="messages_ref_reason1_advise" id="messages_ref_reason1_advise" value="Advise as to the diagnosis." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Suggest medication or treatment:</td>
											<td><input type="checkbox" name="messages_ref_reason1_suggest" id="messages_ref_reason1_suggest" value="Suggest medication or treatment for the diagnosis." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Callback after seeing patient:</td>
											<td><input type="checkbox" name="messages_ref_reason1_callback" id="messages_ref_reason1_callback" value="Please call me when you have seen the patient." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Receive periodic status reports:</td>
											<td><input type="checkbox" name="messages_ref_reason1_status" id="messages_ref_reason1_status" value="I would like to receive periodic status reports on this patient." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Receive thorough written report:</td>
											<td><input type="checkbox" name="messages_ref_reason1_report" id="messages_ref_reason1_report" value="Please send a thorough written report when the consultation is complete." class="text ui-widget-content ui-corner-all"/></td>
											<td><input type="button" id="messages_add_ref1" value="Submit" class="messages_ref_button"/> <input type="button" id="messages_cancel_ref1" value="Cancel" class="messages_ref_button"/></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
					<div id="messages_ref_reason2" style="display: none">
						<div id="messages_ref_reason2_form">
							<fieldset class="ui-widget-content ui-corner-all">
								<legend>Referral</legend>
								<table>
									<tbody>
										<tr>
											<td>Return patient after managing particular problem:</td>
											<td><input type="checkbox" name="messages_ref_reason2_return" id="messages_ref_reason2_return" value="Assume management for this particular problem and return patient after conclusion of care." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Future ongoing management</td>
											<td><input type="checkbox" name="messages_ref_reason2_ongoing" id="messages_ref_reason2_ongoing" value="Assume future management of patient within your area of expertise." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Callback after seeing patient:</td>
											<td><input type="checkbox" name="messages_ref_reason2_callback" id="messages_ref_reason2_callback" value="Please call me when you have seen the patient." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Receive periodic status reports:</td>
											<td><input type="checkbox" name="messages_ref_reason2_status" id="messages_ref_reason2_status" value="I would like to receive periodic status reports on this patient." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Receive thorough written report:</td>
											<td><input type="checkbox" name="messages_ref_reason2_report" id="messages_ref_reason2_report" value="Please send a thorough written report when the consultation is complete." class="text ui-widget-content ui-corner-all"/></td>
											<td><input type="button" id="messages_add_ref2" value="Submit" class="messages_ref_button"/> <input type="button" id="messages_cancel_ref2" value="Cancel" class="messages_ref_button"/></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
					<div id="messages_ref_reason3" style="display: none">
						<div id="messages_ref_reason3_form">
							<fieldset class="ui-widget-content ui-corner-all">
								<legend>Physical Therapy</legend>
								<table>
									<tbody>
										<tr>
											<th colspan="2">Objective</th>
										</tr>
										<tr>
											<td>Decrease pain:</td>
											<td><input type="checkbox" name="messages_ref_reason3_pain" id="messages_ref_reason3_pain" value="Decrease pain." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Increase strength:</td>
											<td><input type="checkbox" name="messages_ref_reason3_strength" id="messages_ref_reason3_strength" value="Increase strength." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Increase mobility:</td>
											<td><input type="checkbox" name="messages_ref_reason3_mobility" id="messages_ref_reason3_mobility" value="increase mobility." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
									</tbody>
								</table><br>
								<table>
									<tbody>
										<tr>
											<th colspan="2">Modalities</th>
										</tr>
										<tr>
											<td>Hot/Cold packs:</td>
											<td><input type="checkbox" name="messages_ref_reason3_packs" id="messages_ref_reason3_packs" value="Hot or cold packs." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>TENS unit:</td>
											<td><input type="checkbox" name="messages_ref_reason3_tens" id="messages_ref_reason3_tens" value="TENS unit." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Back program:</td>
											<td><input type="checkbox" name="messages_ref_reason3_back" id="messages_ref_reason3_back" value="Back program." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Joint mobilization:</td>
											<td><input type="checkbox" name="messages_ref_reason3_joint" id="messages_ref_reason3_joint" value="Joint mobilization." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Home program:</td>
											<td><input type="checkbox" name="messages_ref_reason3_home" id="messages_ref_reason3_home" value="Home program." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Pool therapy:</td>
											<td><input type="checkbox" name="messages_ref_reason3_pool" id="messages_ref_reason3_pool" value="Pool therapy." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Feldenkrais method:</td>
											<td><input type="checkbox" name="messages_ref_reason3_feldenkrais" id="messages_ref_reason3_feldenkrais" value="Feldenkrais method." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Therapeutic exercise:</td>
											<td><input type="checkbox" name="messages_ref_reason3_exercise" id="messages_ref_reason3_exercise" value="Therapeutic exercise." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Myofascial release:</td>
											<td><input type="checkbox" name="messages_ref_reason3_myofascial" id="messages_ref_reason3_myofascial" value="Myofascial release." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Patient education:</td>
											<td><input type="checkbox" name="messages_ref_reason3_education" id="messages_ref_reason3_education" value="Patient education." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Work hardening:</td>
											<td><input type="checkbox" name="messages_ref_reason3_work" id="messages_ref_reason3_work" value="Work hardening." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
									</tbody>
								</table><br>
								<table>
									<tbody>
										<tr>
											<td>Precautions:</td>
											<td><input type="text" name="messages_ref_reason3_precautions" id="messages_ref_reason3_precautions" class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Frequency:</td>
											<td><input type="text" name="messages_ref_reason3_frequency" id="messages_ref_reason3_frequency" class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Duration:</td>
											<td><input type="text" name="messages_ref_reason3_duration" id="messages_ref_reason3_duration" class="text ui-widget-content ui-corner-all"/></td>
											<td><input type="button" id="messages_add_ref3" value="Submit" class="messages_ref_button"/> <input type="button" id="messages_cancel_ref3" value="Cancel" class="messages_ref_button"/></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
					<div id="messages_ref_reason4" style="display: none">
						<div id="messages_ref_reason4_form">
							<fieldset class="ui-widget-content ui-corner-all">
								<legend>Massage Therapy</legend>
								<table>
									<tbody>
										<tr>
											<th colspan="2">Objective</th>
										</tr>
										<tr>
											<td>Decrease pain:</td>
											<td><input type="checkbox" name="messages_ref_reason4_pain" id="messages_ref_reason4_pain" value="Decrease pain." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
										<tr>
											<td>Increase mobility:</td>
											<td><input type="checkbox" name="messages_ref_reason4_mobility" id="messages_ref_reason4_mobility" value="increase mobility." class="text ui-widget-content ui-corner-all"/></td>
										</tr>
									</tbody>
								</table><br>
								<table>
									<tbody>
										<tr>
											<td>Precautions:</td>
											<td><input type="text" name="messages_ref_reason4_precautions" id="messages_ref_reason4_precautions" class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Frequency:</td>
											<td><input type="text" name="messages_ref_reason4_frequency" id="messages_ref_reason4_frequency" class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td>Duration:</td>
											<td><input type="text" name="messages_ref_reason4_duration" id="messages_ref_reason4_duration" class="text ui-widget-content ui-corner-all"/></td>
											<td><input type="button" id="messages_add_ref4" value="Submit" class="messages_ref_button"/> <input type="button" id="messages_cancel_ref4" value="Cancel" class="messages_ref_button"/></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
					<div id="messages_ref_reason5" style="display: none">
						<div id="messages_ref_reason5_form">
							<fieldset class="ui-widget-content ui-corner-all">
								<legend>Sleep Study Referral</legend>
								<table>
									<tbody>
										<tr>
											<td colspan="2">Diagnostic Only:</td>
											<td><input type="checkbox" name="messages_ref_reason5_diag" id="messages_ref_reason5_diag" value="Diagnostic Sleep Study Only." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="2">Diagnostic with Continuous Positive Airway Pressure:</td>
											<td><input type="checkbox" name="messages_ref_reason5_cpap" id="messages_ref_reason5_cpap" value="Diagnostic testing with Continuous Positive Airway Pressure." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="2">Diagnostic with BiLevel Positive Airway Pressure:</td>
											<td><input type="checkbox" name="messages_ref_reason5_bipap" id="messages_ref_reason5_bipap" value="Diagnostic testing with BiLevel Positive Airway Pressure." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>	
										<tr>
											<td></td>
											<td align="right">Inspiratory Pressure (IPAP):</td>
											<td><input type="text" name="messages_ref_reason5_ipap" id="messages_ref_reason5_ipap" size="5" class="text ui-widget-content ui-corner-all"/> cm H20</td>
											<td></td>
										</tr>
										<tr>
											<td></td>
											<td align="right"><br>Expiratory Pressure (EPAP):</td>
											<td><input type="text" name="messages_ref_reason5_epap" id="messages_ref_reason5_epap" size="5" class="text ui-widget-content ui-corner-all"/> cm H20</td>
											<td></td>
										</tr>
										<tr>
											<td></td>
											<td align="right">Spontaneous Mode:</td>
											<td><input type="radio" name="messages_ref_reason5_mode" id="messages_ref_reason5_mode1" value="Spontaneous Mode" class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
											<td></td>
											<td align="right">Spontaneous/Timed Mode:</td>
											<td colspan="2"><input type="radio" name="messages_ref_reason5_mode" id="messages_ref_reason5_mode2" value="Spontaneous/Timed Mode" class="text ui-widget-content ui-corner-all"/> at <input type="text" name="messages_ref_reason5_bpm" id="messages_ref_reason5_bpm" size="5" class="text ui-widget-content ui-corner-all"/> breaths/minute</td>
										</tr>
										<tr>
											<td colspan="2">Diagnostic with Oxygen:</td>
											<td><input type="checkbox" name="messages_ref_reason5_oxygen" id="messages_ref_reason5_oxygen" value="Diagnostic testing with Oxygen." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="2">Diagnostic with Oral Device:</td>
											<td><input type="checkbox" name="messages_ref_reason5_oral" id="messages_ref_reason5_oral" value="Diagnostic testing with Oral Device." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="2">MSLT (Multiple Sleep Latency Test):</td>
											<td><input type="checkbox" name="messages_ref_reason5_mslt" id="messages_ref_reason5_mslt" value="MSLT (Multiple Sleep Latency Test)." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="2">MWT (Maintenance of Wakefulness Test):</td>
											<td><input type="checkbox" name="messages_ref_reason5_mwt" id="messages_ref_reason5_mwt" value="MWT (Maintenance of Wakefulness Test)." class="text ui-widget-content ui-corner-all"/></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="2">Titrate BiPAP settings:</td>
											<td><input type="checkbox" name="messages_ref_reason5_titrate" id="messages_ref_reason5_titrate" value="Titrate BiLevel Positive Airway Pressure settings." class="text ui-widget-content ui-corner-all"/></td>
											<td><input type="button" id="messages_add_ref5" value="Submit" class="messages_ref_button"/> <input type="button" id="messages_cancel_ref5" value="Cancel" class="messages_ref_button"/>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
			<h3><a href="#">Diagnosis Codes</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_referrals_icd" id="messages_ref_codes" rows="3" style="width:500px" class="text ui-widget-content ui-corner-all" placeholder="Use a comma to separate distinct search terms."></textarea></td>	
						<td valign="top"><br>
							<input type="button" id="messages_ref_codes_clear" value="Clear" class="messages_ref_button"/><br>
							<input type="button" id="messages_ref_issues" value="Issues" class="messages_ref_button"/>
						</td>
					</tr>
				</table>
			</div>
			<h3><a href="#">Location</a></h3>
			<div>
				Provider: <select name="address_id" id="messages_ref_location" class="text ui-widget-content ui-corner-all"> </select><input type="button" id="messages_select_ref_location2" value="Add/Edit" class="messages_ref_button"/> 
				Filter by Specialty: <select name="specialty_select" id="messages_specialty_select" class="text ui-widget-content ui-corner-all"></select> 
			</div>
			<h3><a href="#">Insurance</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_insurance"  id="messages_ref_insurance" rows="3" style="width:500px" class="text ui-widget-content ui-corner-all"/></textarea></td>
						<td valign="top"><br>
							<input type="button" id="messages_ref_insurance_clear" value="Clear" class="messages_ref_button"/><br>
							<input type="button" id="messages_ref_insurance_client" value="Bill Client" class="messages_ref_button"/>
						</td>
					</tr>
				</table><br>
				<table id="messages_ref_insurance_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="messages_ref_insurance_pager" class="scroll" style="text-align:center;"></div><br>
			</div>
		</div>
	</div>
	<div id="messages_ref_action_fieldset" style="display:none">
		<div id="messages_ref_choice"></div><br>
		<input type="button" id="messages_print_ref" value="Print" class="messages_ref_button"/> 
		<!--<input type="button" id="messages_electronic_ref" value="Electronic" class="messages_ref_button"/> -->
		<input type="button" id="messages_fax_ref" value="Fax" class="messages_ref_button"/>
		<input type="button" id="messages_done_ref" value="Done" class="messages_ref_button"/> 
	</div>
	</div>
</div>
<div id="messages_edit_ref_location" title="">
	<input type="hidden" name="messages_ref_location_address_id" id="messages_ref_location_address_id"/>
	<table>
		<tbody>
			<tr>
				<td>Last Name:<br><input type="text" name="lastname" id="messages_ref_location_lastname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td colspan="2">First Name:<br><input type="text" name="firstname" id="messages_ref_location_firstname" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
			<tr>	
				<td>Prefix:<br><input type="text" name="prefix" id="messages_ref_location_prefix" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td>Suffix:<br><input type="text" name="suffix" id="messages_ref_location_suffix" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td align="left">Specialty:<br><input type="text" name="specialty" id="messages_ref_location_specialty" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>	
				<td>Facility:<br><input type="text" name="facility" id="messages_ref_location_facility" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td colspan="2">E-mail:<br><input type="text" name="email" id="messages_ref_location_email" style="width:446px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td colspan="3">Address:<br><input type="text" name="street_address1" id="messages_ref_location_address" style="width:648px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td colspan="3">Address2:<br><input type="text" name="street_address2" id="messages_ref_location_address2" style="width:648px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>City:<br><input type="text" name="city" id="messages_ref_location_city" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td>State:<br><select name="state" id="messages_ref_location_state" class="text ui-widget-content ui-corner-all"></td>
				<td align="left">Zip:<br><input type="text" name="zip" id="messages_ref_location_zip" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td>Phone:<br><input type="text" name="phone" id="messages_ref_location_phone" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
				<td colspan="2">Fax:<br><input type="text" name="fax" id="messages_ref_location_fax" style="width:164px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
			<tr>
				<td colspan="3" valign="top">Comments:<br><input type="text" name="comments" id="messages_ref_location_comments" style="width:648px" class="text ui-widget-content ui-corner-all"/></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	$.ajax({
		url: "<?php echo site_url('start/check_fax');?>",
		type: "POST",
		success: function(data){
			if (data == "Yes") {
				$("#messages_fax_ref").show('fast');
			} else {
				$("#messages_fax_ref").hide('fast');
			}
		}
	});
	$(".messages_ref_button").button();
	$("#messages_ref_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(){
			 $("#messages_ref_accordion").accordion({ heightStyle: "content" });
			 $("#messages_ref_grid").show('fast');
			 $("#messages_ref_edit_fields").hide('fast');
			 jQuery("#messages_ref_list").jqGrid('GridUnload');
			 jQuery("#messages_ref_list").jqGrid({
				url:"<?php echo site_url('assistant/chartmenu/ref_list');?>",
				postData: {t_messages_id: function(){return $("#messages_ref_t_messages_id").val();}},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Referral','Diagnosis','Location1','Location','Insurance','Provider'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_referrals',index:'orders_referrals',width:300},
					{name:'orders_referrals_icd',index:'orders_referrals_icd',width:200},
					{name:'address_id',index:'address_id',hidden:true},
					{name:'displayname',index:'displayname',width:100},
					{name:'orders_insurance',index:'orders_insurance',hidden:true},
					{name:'encounter_provider',index:'encounter_provider',hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_ref_list_pager'),
				sortname: 'orders_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Referral Orders - Click on Location to get full description",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" },
			 	onCellSelect: function(rowid,iCol,cellcontent,e){
			 		if (iCol == 'address_id') {
				 		$.ajax({
							url: "<?php echo site_url('assistant/chartmenu/addressdefine');?>",
							type: "POST",
							data: "address_id=" + cellcontent,
							dataType: 'json',
							success: function(data){
								$.jGrowl(data.item, {sticky:true});				
							}
						});
			 		}		
				}
			}).navGrid('#messages_ref_list_pager',{search:false,edit:false,add:false,del:false});
			 jQuery("#messages_ref_insurance_grid").jqGrid('GridUnload');
			 jQuery("#messages_ref_insurance_grid").jqGrid({
				url:"<?php echo site_url('assistant/chartmenu/insurance/');?>",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Copay','Deductible','Comments','Address ID','Relationship'],
				colModel:[
					{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
					{name:'insurance_plan_name',index:'insurance_plan_name',width:350},
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
				pager: jQuery('#messages_ref_insurance_pager'),
				sortname: 'insurance_order',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Insurance Payors - Click to select insurance for imaging order",
			 	height: "100%",
			 	onSelectRow: function(id){
			 		var insurance_plan_name = jQuery("#messages_ref_insurance_grid").getCell(id,'insurance_plan_name');
					var insurance_id_num = jQuery("#messages_ref_insurance_grid").getCell(id,'insurance_id_num');
					var insurance_group = jQuery("#messages_ref_insurance_grid").getCell(id,'insurance_group');
					var insurance_insu_lastname = jQuery("#messages_ref_insurance_grid").getCell(id,'insurance_insu_lastname');
					var insurance_insu_firstname = jQuery("#messages_ref_insurance_grid").getCell(id,'insurance_insu_firstname');
					var text = insurance_plan_name + '; ID: ' + insurance_id_num;
					if(insurance_group != ''){
						text += "; Group: " + insurance_group;
					}
					text += "; " + insurance_insu_lastname + ", " + insurance_insu_firstname;
					var old = $("#messages_ref_insurance").val();
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
					$("#messages_ref_insurance").val(old1+text);
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_ref_insurance_pager',{search:false,edit:false,add:false,del:false});
			$.ajax({
				url: "<?php echo site_url('search/ref_provider_specialty');?>",
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#messages_specialty_select").addOption({"":"All specialties."});
						$("#messages_specialty_select").addOption(data.message);
					} else {
						$("#messages_specialty_select").addOption({"":"No specialties.  Click Add."});
					}
				}
			});
			$.ajax({
				url: "<?php echo site_url('search/ref_provider/all');?>/",
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#messages_ref_location").addOption({"":"Add referral provider."});
						$("#messages_ref_location").addOption(data.message);
					} else {
						$("#messages_ref_location").addOption({"":"No referral provider.  Click Add."});
					}
				}
			});
			$.ajax({
				url: "<?php echo site_url('search/providers');?>",
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#messages_ref_provider_list").addOption({"":"Select a provider for the order."});
						$("#messages_ref_provider_list").addOption(data.message);
					} else {
						$("#messages_ref_provider_list").addOption({"":"No providers."});
					}
				}
			});
		},
		beforeclose: function(event, ui) {
			var a = $("#messages_ref_orders").val();
			if(a != ''){
				if(confirm('The form fields are not empty.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
					$('#edit_message_ref_form').clearDiv();
					$("#messages_ref").val('');
					$("#messages_ref_action_fieldset").hide('fast');
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	});
	$('#messages_specialty_select').change(function() {
		if ($(this).val() != ""){
			$("#messages_ref_location").removeOption(/./);
			$.ajax({
				url: "<?php echo site_url('search/ref_provider');?>/" + $(this).val(),
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#messages_ref_location").addOption({"":"Add referral provider."});
						$("#messages_ref_location").addOption(data.message);
					} else {
						$("#messages_ref_location").addOption({"":"No referral provider.  Click Add."});
					}
				}
			});
		}
	});
	$("#messages_add_ref").click(function(){
		$('#edit_message_ref_form').clearDiv();
		var id = $("#t_messages_id").val();
		$("#messages_ref_t_messages_id").val(id);
		$("#messages_ref_status").html('');
		$("#messages_ref_edit_fields").show('fast');
		$("#messages_ref_accordion").accordion("option", "active", 0);
		$("#messages_ref_orders").focus();
		$("#messages_ref_location").val('');
		$("#messages_specialty_select").val('');
		$("#messages_ref_template").val('');
		$("#messages_ref_grid").hide('fast');
	});
	$("#messages_edit_ref").click(function(){
		var item = jQuery("#messages_ref_list").getGridParam('selrow');
		if(item){
			jQuery("#messages_ref_list").GridToForm(item,"#edit_message_ref_form");
			var status = 'Details for Referral Order #' + item;
			$("#messages_ref_status").html(status);
			$("#messages_ref_edit_fields").show('fast');
			$("#messages_ref_action_fieldset").hide('fast');
			$("#messages_ref_accordion").accordion("option", "active", 0);
			$("#messages_ref_orders").focus();
			$("#messages_ref_template").val('');
			$("#messages_ref_grid").hide('fast');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_resend_ref").click(function(){
		var item = jQuery("#messages_ref_list").getGridParam('selrow');
		if(item){
			$("#messages_ref_orders_id").val(item);
			$('#messages_ref_choice').html("Choose an action for the referral order, reference number " + item);
			$("#messages_ref_action_fieldset").show('fast');
			$("#messages_ref_edit_fields").hide('fast');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_delete_ref").click(function(){
		var item = jQuery("#messages_ref_list").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "<?php echo site_url('assistant/chartmenu/delete_ref');?>",
				type: "POST",
				data: "orders_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#messages_ref_list").trigger("reloadGrid");			
				}
			});
		} else {
			$.jGrowl("Please select order to delete!");
		}
	});
	$("#messages_ref_orders_clear").click(function(){
		$("#messages_ref_orders").val('');
	});
	$("#messages_ref_codes_clear").click(function(){
		$("#messages_ref_codes").val('');
	});
	$("#messages_ref_location_clear").click(function(){
		$("#messages_ref_location").val('');
	});
	$("#messages_ref_insurance_clear").click(function(){
		$("#messages_ref_insurance").val('');
	});
	function split( val ) {
		return val.split( /\n\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}
	$("#messages_ref_codes").autocomplete({
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
	$("#messages_ref_reason1_button").click(function(){
		$("#messages_ref_reason1").show('fast');
		$("#messages_ref_reason2").hide('fast');
		$("#messages_ref_reason3").hide('fast');
		$("#messages_ref_reason4").hide('fast');
		$("#messages_ref_reason5").hide('fast');
	});
	$("#messages_ref_reason2_button").click(function(){
		$("#messages_ref_reason2").show('fast');
		$("#messages_ref_reason1").hide('fast');
		$("#messages_ref_reason3").hide('fast');
		$("#messages_ref_reason4").hide('fast');
		$("#messages_ref_reason5").hide('fast');
	});
	$("#messages_ref_reason3_button").click(function(){
		$("#messages_ref_reason3").show('fast');
		$("#messages_ref_reason1").hide('fast');
		$("#messages_ref_reason2").hide('fast');
		$("#messages_ref_reason4").hide('fast');
		$("#messages_ref_reason5").hide('fast');
	});
	$("#messages_ref_reason4_button").click(function(){
		$("#messages_ref_reason4").show('fast');
		$("#messages_ref_reason1").hide('fast');
		$("#messages_ref_reason2").hide('fast');
		$("#messages_ref_reason3").hide('fast');
		$("#messages_ref_reason5").hide('fast');
	});
	$("#messages_ref_reason5_button").click(function(){
		$("#messages_ref_reason5").show('fast');
		$("#messages_ref_reason1").hide('fast');
		$("#messages_ref_reason2").hide('fast');
		$("#messages_ref_reason3").hide('fast');
		$("#messages_ref_reason4").hide('fast');
	});
	$("#messages_add_ref1").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/chartmenu/make_referral');?>",
			dataType: "json",
			success: function(data){
				var preview = "Requested action:\n";
				preview += 'Consultation - Please send the patient back for follow-up and treatment.\n';
				if($('#messages_ref_reason1_confirm').attr('checked')){ 
					preview += $("#messages_ref_reason1_confirm").val() + '\n';
				}
				if($('#messages_ref_reason1_advise').attr('checked')){ 
					preview += $("#messages_ref_reason1_advise").val() + '\n';
				}
				if($('#messages_ref_reason1_suggest').attr('checked')){ 
					preview += $("#messages_ref_reason1_suggest").val() + '\n';
				}
				preview += '\n';
				var issues = data.issues;
				preview += "Active Issues:";
				var issues_len = issues.length;
				for(var i=0; i<issues_len; i++) {
					preview += '\n' + issues[i];
				}
				preview += '\n\n';
				var meds = data.meds;
				preview += "Active Medications:";
				var meds_len = meds.length;
				for(var j=0; j<meds_len; j++) {
					preview += '\n' + meds[j];
				}
				preview += '\n\n';
				var allergies = data.allergies;
				preview += "Allergies:";
				var allergies_len = allergies.length;
				for(var k=0; k<allergies_len; k++) {
					preview += '\n' + allergies[k];
				}
				preview += '\n\n';
				if($('#messages_ref_reason1_callback').attr('checked')){ 
					preview += $("#messages_ref_reason1_callback").val() + '\n';
				}
				if($('#messages_ref_reason1_status').attr('checked')){ 
					preview += $("#messages_ref_reason1_status").val() + '\n';
				}
				if($('#messages_ref_reason1_report').attr('checked')){ 
					preview += $("#messages_ref_reason1_report").val() + '\n';
				}
				preview += '\n' + 'Sincerely,' + '\n\n' + data.displayname;
				$("#messages_ref_orders").val(preview);
				$("#messages_ref_reason1_form").clearDiv();
				$("#messages_ref_reason1").hide('fast');
			}
		});
	});
	$("#messages_cancel_ref1").click(function(){
		$("#messages_ref_reason1_form").clearDiv();
		$("#messages_ref_reason1").hide('fast');
	});
	$("#messages_add_ref2").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/chartmenu/make_referral');?>",
			dataType: "json",
			success: function(data){
				var preview = "Requested action:\n";
				preview += 'Referral - Please provide primary physician with summaries of subsequent visits.\n';
				if($('#messages_ref_reason2_return').attr('checked')){ 
					preview += $("#messages_ref_reason2_return").val() + '\n';
				}
				if($('#messages_ref_reason2_ongoing').attr('checked')){ 
					preview += $("#messages_ref_reason2_ongoing").val() + '\n';
				}
				preview += '\n';
				var issues = data.issues;
				preview += "Active Issues:";
				var issues_len = issues.length;
				for(var i=0; i<issues_len; i++) {
					preview += '\n' + issues[i];
				}
				preview += '\n\n';
				var meds = data.meds;
				preview += "Active Medications:";
				var meds_len = meds.length;
				for(var j=0; j<meds_len; j++) {
					preview += '\n' + meds[j];
				}
				preview += '\n\n';
				var allergies = data.allergies;
				preview += "Allergies:";
				var allergies_len = allergies.length;
				for(var k=0; k<allergies_len; k++) {
					preview += '\n' + allergies[k];
				}
				preview += '\n\n';
				if($('#messages_ref_reason2_callback').attr('checked')){ 
					preview += $("#messages_ref_reason2_callback").val() + '\n';
				}
				if($('#messages_ref_reason2_status').attr('checked')){ 
					preview += $("#messages_ref_reason2_status").val() + '\n';
				}
				if($('#messages_ref_reason2_report').attr('checked')){ 
					preview += $("#messages_ref_reason2_report").val() + '\n';
				}
				preview += '\n' + 'Sincerely,' + '\n\n' + data.displayname;
				$("#messages_ref_orders").val(preview);
				$("#messages_ref_reason2_form").clearDiv();
				$("#messages_ref_reason2").hide('fast');
			}
		});
	});
	$("#messages_cancel_ref2").click(function(){
		$("#messages_ref_reason2_form").clearDiv();
		$("#messages_ref_reason2").hide('fast');
	});
	$("#messages_add_ref3").click(function(){
		var freq = $("#messages_ref_reason3_frequency");
		var duration = $("#messages_ref_reason3_duration");
		var bValid = true;
		bValid = bValid && checkEmpty(freq,"Frequency");		
		bValid = bValid && checkEmpty(duration,"Duration");	
		if (bValid) {
			var preview = "Physical therapy referral:\n\nObjective:\n";
			if($('#messages_ref_reason3_pain').attr('checked')){ 
				preview += $("#messages_ref_reason3_pain").val() + '\n';
			}
			if($('#messages_ref_reason3_strength').attr('checked')){ 
				preview += $("#messages_ref_reason3_strength").val() + '\n';
			}
			if($('#messages_ref_reason3_mobility').attr('checked')){ 
				preview += $("#messages_ref_reason3_mobility").val() + '\n';
			}
			preview += '\nModalities:\n';
			if($('#messages_ref_reason3_packs').attr('checked')){ 
				preview += $("#messages_ref_reason3_packs").val() + '\n';
			}
			if($('#messages_ref_reason3_tens').attr('checked')){ 
				preview += $("#messages_ref_reason3_tens").val() + '\n';
			}
			if($('#messages_ref_reason3_back').attr('checked')){ 
				preview += $("#messages_ref_reason3_back").val() + '\n';
			}
			if($('#messages_ref_reason3_joint').attr('checked')){ 
				preview += $("#messages_ref_reason3_joint").val() + '\n';
			}
			if($('#messages_ref_reason3_home').attr('checked')){ 
				preview += $("#messages_ref_reason3_home").val() + '\n';
			}
			if($('#messages_ref_reason3_pool').attr('checked')){ 
				preview += $("#messages_ref_reason3_pool").val() + '\n';
			}
			if($('#messages_ref_reason3_feldenkrais').attr('checked')){ 
				preview += $("#messages_ref_reason3_feldenkrais").val() + '\n';
			}
			if($('#messages_ref_reason3_exercise').attr('checked')){ 
				preview += $("#messages_ref_reason3_exercise").val() + '\n';
			}
			if($('#messages_ref_reason3_myofascial').attr('checked')){ 
				preview += $("#messages_ref_reason3_myofascial").val() + '\n';
			}
			if($('#messages_ref_reason3_education').attr('checked')){ 
				preview += $("#messages_ref_reason3_education").val() + '\n';
			}
			if($('#messages_ref_reason3_work').attr('checked')){ 
				preview += $("#messages_ref_reason3_work").val() + '\n';
			}
			preview += '\n';
			if($('#messages_ref_reason3_precautions').val() != ''){ 
				preview += 'Precautions: ' + $("#messages_ref_reason3_precautions").val() + '\n';
			}
			if($('#messages_ref_reason3_frequency').val() != ''){ 
				preview += 'Frequency: ' + $("#messages_ref_reason3_frequency").val() + ' for ' + $("#messages_ref_reason3_duration").val() + '\n';
			}
			$("#messages_ref_orders").val(preview);
			$("#messages_ref_reason3_form").clearDiv();
			$("#messages_ref_reason3").hide('fast');
		}
	});
	$("#messages_cancel_ref3").click(function(){
		$("#messages_ref_reason3_form").clearDiv();
		$("#messages_ref_reason3").hide('fast');
	});
	$("#messages_add_ref4").click(function(){
		var freq = $("#messages_ref_reason4_frequency");
		var duration = $("#messages_ref_reason4_duration");
		var bValid = true;
		bValid = bValid && checkEmpty(freq,"Frequency");
		bValid = bValid && checkEmpty(duration,"Duration");	
		if (bValid) {
			var preview = "Massage therapy referral:\n\nObjective:\n";
			if($('#messages_ref_reason4_pain').attr('checked')){ 
				preview += $("#messages_ref_reason4_pain").val() + '\n';
			}
			if($('#messages_ref_reason4_mobility').attr('checked')){ 
				preview += $("#messages_ref_reason4_mobility").val() + '\n';
			}
			preview += '\n';
			if($('#messages_ref_reason4_precautions').val() != ''){ 
				preview += 'Precautions: ' + $("#messages_ref_reason4_precautions").val() + '\n';
			}
			if($('#messages_ref_reason4_frequency').val() != ''){ 
				preview += 'Frequency and duration: ' + $("#messages_ref_reason4_frequency").val() + ' for ' + $("#messages_ref_reason4_duration").val() + '\n';
			}
			$("#messages_ref_orders").val(preview);
			$("#messages_ref_reason4_form").clearDiv();
			$("#messages_ref_reason4").hide('fast');
		}
	});
	$("#messages_cancel_ref4").click(function(){
		$("#messages_ref_reason4_form").clearDiv();
		$("#messages_ref_reason4").hide('fast');
	});
	
	$("#messages_add_ref5").click(function(){
		var bValid = true;
		if (bValid) {
			var preview = "Sleep study referral:\n";
			$("#messages_ref_reason5_form :checked")
			if($('#messages_ref_reason5_diag').attr('checked')){ 
				preview += $("#messages_ref_reason5_diag").val() + '\n';
			}
			if($('#messages_ref_reason5_cpap').attr('checked')){ 
				preview += $("#messages_ref_reason5_cpap").val() + '\n';
			}
			if($('#messages_ref_reason5_bipap').attr('checked')){ 
				preview += $("#messages_ref_reason5_bipap").val() + '\n';
			}
			if($('#messages_ref_reason5_ipap').val() != ''){ 
				preview += 'Inspiratory pressure: ' + $("#messages_ref_reason5_ipap").val() + ' cm H20\n';
			}
			if($('#messages_ref_reason5_epap').val() != ''){ 
				preview += 'Expiratory pressure: ' + $("#messages_ref_reason5_epap").val() + ' cm H20\n';
			}
			if($('#messages_ref_reason5_mode1').attr('checked')){ 
				preview += $("#messages_ref_reason5_mode1").val() + '\n';
			}
			if($('#messages_ref_reason5_mode2').attr('checked')){
				preview += $("#messages_ref_reason5_mode2").val() + ' at ' + $("#messages_ref_reason5_bpm").val() + ' breaths per minute\n';
			}
			if($('#messages_ref_reason5_oxygen').attr('checked')){ 
				preview += $("#messages_ref_reason5_oxygen").val() + '\n';
			}
			if($('#messages_ref_reason5_oral').attr('checked')){ 
				preview += $("#messages_ref_reason5_oral").val() + '\n';
			}
			if($('#messages_ref_reason5_mslt').attr('checked')){ 
				preview += $("#messages_ref_reason5_mslt").val() + '\n';
			}
			if($('#messages_ref_reason5_mwt').attr('checked')){ 
				preview += $("#messages_ref_reason5_mwt").val() + '\n';
			}
			if($('#messages_ref_reason5_titrate').attr('checked')){ 
				preview += $("#messages_ref_reason5_titrate").val() + '\n';
			}
			$("#messages_ref_orders").val(preview);
			$("#messages_ref_reason5_form").clearDiv();
			$("#messages_ref_reason5").hide('fast');
		}
	});
	$("#messages_cancel_ref5").click(function(){
		$("#messages_ref_reason5_form").clearDiv();
		$("#messages_ref_reason5").hide('fast');
	});
	$("#messages_ref_reason3_frequency").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/frequency');?>",
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
	$("#messages_ref_issues").click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').hide('fast');
		$('#issues_psh_header').hide('fast');
		$('#issues_ref_header').show('fast');
		$('#issues_lab_header').hide('fast');
		$('#issues_rad_header').hide('fast');
		$('#issues_cp_header').hide('fast');
		$('#issues_assessment_header').hide('fast');
		$('#edit_issue_form').hide('fast');
	});	
	$("#messages_select_ref_location2").click(function (){
		$("#messages_edit_ref_location").dialog('open');
	});
	$("#messages_ref_location_state").addOption({"AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorefo","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#messages_ref_location_phone").mask("(999) 999-9999");
	$("#messages_ref_location_fax").mask("(999) 999-9999");
	$("#messages_add_ref_location").button({
		icons: {
			primary: "ui-icon-plus"
		}
	});
	$("#messages_add_ref_location").click(function(){
		var lastname = $("#messages_ref_location_lastname");
		var firstname = $("#messages_ref_location_firstname");
		var bValid = true;
		bValid = bValid && checkEmpty(lastname,"Last Name");
		bValid = bValid && checkEmpty(firstname,"First Name");
		if (bValid) {
			var a = $("#messages_ref_location_lastname").val();
			var a1 = $("#messages_ref_location_firstname").val();
			var a2 = $("#messages_ref_location_prefix").val();
			var a3 = $("#messages_ref_location_suffix").val();
			var a4 = $("#messages_ref_location_specialty").val();
			var b = $("#messages_ref_location_address").val();
			var c = $("#messages_ref_location_address2").val();
			var d = $("#messages_ref_location_city").val();
			var e = $("#messages_ref_location_state").val();
			var f = $("#messages_ref_location_zip").val();
			var g = $("#messages_ref_location_phone").val();
			var h = $("#messages_ref_location_fax").val();
			var i = $("#messages_ref_location_address_id").val();
			var j = $("#messages_ref_location_comments").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/chartmenu/edit_ref_provider');?>",
				data: "lastname=" + a + "&firstname=" + a1 + "&prefix=" + a2 + "&suffix=" + a3 + "&specialty=" + a4 + "&street_address1=" + b + "&street_address2=" + c + "&city=" + d + "&state=" + e + "&zip=" + f + "&phone=" + g + "&fax=" + h + "&address_id=" + i + "&comments=" + j,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					$("#messages_ref_location").val(data.item);
					$("#messages_ref_location_id").val(data.id);
					$("#messages_ref_location_lastname").val('');
					$("#messages_ref_location_firstname").val('');
					$("#messages_ref_location_prefix").val('');
					$("#messages_ref_location_suffix").val('');
					$("#messages_ref_location_specialty").val('');
					$("#messages_ref_location_address").val('');
					$("#messages_ref_location_address2").val('');
					$("#messages_ref_location_city").val('');
					$("#messages_ref_location_state").val('');
					$("#messages_ref_location_zip").val('');
					$("#messages_ref_location_phone").val('');
					$("#messages_ref_location_fax").val('');
					$("#messages_ref_location_address_id").val('');
					$("#messages_ref_location_comments").val('');
					$("#messages_ref_location_ordering_id").val('');
					$("#messages_edit_ref_location").hide('fast');
				}
			});
		}
	});
	$("#messages_ref_insurance_client").click(function(){
		var text = "Bill Client";
		var old = $("#messages_ref_insurance").val();
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
		$("#messages_ref_insurance").val(old1+text);
	});
	$("#messages_ref_save").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#messages_ref_save").click(function(){
		var order = $("#messages_ref_orders");
		var codes = $("#messages_ref_codes");
		var location = $("#messages_ref_location");
		var insurance = $("#messages_ref_insurance");
		var bValid = true;
		bValid = bValid && checkEmpty(order,"Tests");
		bValid = bValid && checkEmpty(codes,"Diagnosis Codes");
		bValid = bValid && checkEmpty(location,"Referral Provider");
		bValid = bValid && checkEmpty(insurance,"Insurance");
		if (bValid) {
			var a = $("#messages_ref_orders").val();
			var b = $("#messages_ref_codes").val();
			var c = $("#messages_ref_location").val();
			var d = $("#messages_ref_t_messages_id").val();
			var e = $("#messages_ref_orders_id").val();
			var f = $("#messages_ref_insurance").val();
			var g = $("#messages_ref_provider_list").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/chartmenu/add_ref_order');?>",
				data: "orders_referrals=" + a + "&orders_referrals_icd=" + b + "&address_id=" + c + "&t_messages_id=" + d + "&orders_id=" + e + "&orders_insurance=" + f +"&provider=" + g,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					$("#messages_ref_orders_id").val(data.id);
					$('#messages_ref_choice').html(data.choice);
					$("#messages_ref_action_fieldset").show('fast');
					$("#messages_ref_edit_fields").hide('fast');
					$("#messages_edit_ref_location").hide('fast');
					jQuery("#alerts").trigger("reloadGrid");
					jQuery("messages_ref_list").trigger("reloadGrid");
				}
			});
		}
	});
	$("#messages_ref_cancel").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#messages_ref_cancel").click(function(){
		$("#messages_ref_edit_fields").hide('fast');
		$("#messages_ref_action_fieldset").hide('fast');
		$("#messages_ref_grid").show('fast');
		jQuery("#messages_ref_list").trigger("reloadGrid");
	});
	$("#save_ref_helper").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_ref_helper").click(function(){
		var origin = $("#messages_ref_origin").val();
		if (origin == 'message') {
			var id = $("#t_messages_id").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/chartmenu/import_ref1');?>",
				data: "t_messages_id=" + id,
				success: function(data){
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
					if(data != ''){
						$("#t_messages_message").val(old1+data);	
					}
				}
			});
		} else {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/check_orders');?>",
				dataType: "json",
				success: function(data){
					$('#button_orders_labs_status').html(data.labs_status);
					$('#button_orders_ref_status').html(data.ref_status);
					$('#button_orders_ref_status').html(data.ref_status);
					$('#button_orders_ref_status').html(data.ref_status);
					$('#button_orders_rx_status').html(data.rx_status);
					$('#button_orders_imm_status').html(data.imm_status);
					$('#button_orders_sup_status').html(data.sup_status);
				}
			});
		}
		$('#edit_message_ref_form').clearDiv();
		$("#messages_ref").val('');
		$("#messages_ref_origin").val('');
		$("#messages_ref_reft").val('');
		$("#messages_ref_edit_fields").hide('fast');
		$("#messages_ref_action_fieldset").hide('fast');
		$("#messages_edit_ref_location").hide('fast');
		$("#edit_message_ref_form").show('fast');
		$("#messages_ref_dialog").dialog('close');
	});
	$("#cancel_ref_helper").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_ref_helper").click(function(){
		$('#edit_message_ref_form').clearDiv();
		$("#messages_ref").val('');
		$("#messages_ref_origin").val('');
		$("#messages_ref_reft").val('');
		$("#messages_ref_edit_fields").hide('fast');
		$("#messages_ref_action_fieldset").hide('fast');
		$("#messages_edit_ref_location").hide('fast');
		$("#edit_message_ref_form").show('fast');
		$("#messages_ref_dialog").dialog('close');
	});
	$("#messages_print_ref").click(function(){
		var ref = $("#messages_ref_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(ref,"Cardioopulmonary Order");
		if (bValid) {
			var order_id = $("#messages_ref_orders_id").val();
			window.open("<?php echo site_url('assistant/chartmenu/print_orders');?>/" + order_id);
		}
	});
	$("#messages_electronic_ref").click(function(){
		$.jGrowl('Future feature!');
	});
	$("#messages_fax_ref").click(function(){
		var ref = $("#messages_ref_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(ref,"Referral Order");
		if (bValid) {
			var order_id = $("#messages_ref_orders_id").val();
			if(order_id){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/chartmenu/fax_orders');?>",
					data: "orders_id=" + order_id,
					success: function(data){
						$.jGrowl(data);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#messages_done_ref").click(function(){
		$("#messages_ref_action_fieldset").hide('fast');
		$("#messages_ref_grid").show('fast');
		jQuery("#messages_ref_list").trigger("reloadGrid");
	});
	$("#messages_edit_ref_location").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#messages_ref_location_lastname").focus();
			$("#messages_ref_location_specialty").autocomplete({
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
			$("#messages_ref_location_city").autocomplete({
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
			var id = $("#messages_ref_location").val();
			if(id){
				$("#messages_edit_ref_location").dialog("option", "title", "Edit Referral Provider");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/order_provider1');?>",
					data: "address_id=" + id,
					dataType: "json",
					success: function(data){
						$("#messages_ref_location_lastname").val(data.lastname);
						$("#messages_ref_location_firstname").val(data.firstname);
						$("#messages_ref_location_prefix").val(data.prefix);
						$("#messages_ref_location_suffix").val(data.suffix);
						$("#messages_ref_location_specialty").val(data.specialty);
						$("#messages_ref_location_facility").val(data.facility);
						$("#messages_ref_location_address").val(data.street_address1);
						$("#messages_ref_location_address2").val(data.street_address2);
						$("#messages_ref_location_city").val(data.city);
						$("#messages_ref_location_state").val(data.state);
						$("#messages_ref_location_zip").val(data.zip);
						$("#messages_ref_location_phone").val(data.phone);
						$("#messages_ref_location_fax").val(data.fax);
						$("#messages_ref_location_address_id").val(data.address_id);
						$("#messages_ref_location_comments").val(data.comments);
					}
				});
			} else {
				$("#messages_edit_ref_location").dialog("option", "title", "Add Referral Provider");
			}
		},
		buttons: {
			'Save': function() {
				var lastname = $("#messages_ref_location_lastname");
				var firstname = $("#messages_ref_location_firstname");
				var bValid = true;
				bValid = bValid && checkEmpty(lastname,"Last Name");
				bValid = bValid && checkEmpty(firstname,"First Name");
				if (bValid) {
					var a = $("#messages_ref_location_lastname").val();
					var a1 = $("#messages_ref_location_firstname").val();
					var a2 = $("#messages_ref_location_prefix").val();
					var a3 = $("#messages_ref_location_suffix").val();
					var a4 = $("#messages_ref_location_specialty").val();
					var a5 = $("#messages_ref_location_facility").val();
					var b = $("#messages_ref_location_address").val();
					var c = $("#messages_ref_location_address2").val();
					var d = $("#messages_ref_location_city").val();
					var e = $("#messages_ref_location_state").val();
					var f = $("#messages_ref_location_zip").val();
					var g = $("#messages_ref_location_phone").val();
					var h = $("#messages_ref_location_fax").val();
					var i = $("#messages_ref_location_address_id").val();
					var j = $("#messages_ref_location_comments").val();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('assistant/chartmenu/edit_ref_provider');?>",
						data: "lastname=" + a + "&firstname=" + a1 + "&prefix=" + a2 + "&suffix=" + a3 + "&specialty=" + a4 + "&facility=" + a5 + "&street_address1=" + b + "&street_address2=" + c + "&city=" + d + "&state=" + e + "&zip=" + f + "&phone=" + g + "&fax=" + h + "&address_id=" + i + "&comments=" + j,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.message);
							$("#messages_edit_ref_location").clearDiv();
							$("#messages_edit_ref_location").dialog('close');
							$("#messages_edit_ref_location").dialog("option", "title", "");
							$("#messages_ref_location").removeOption(/./);
							$.ajax({
								url: "<?php echo site_url('search/ref_provider/all');?>",
								dataType: "json",
								type: "POST",
								success: function(data1){
									if(data1.response =='true'){
										$("#messages_ref_location").addOption(data1.message);
										$("#messages_ref_location").val(data.id);
									}
								}
							});
						}
					});
				}
			},
			Cancel: function() {
				$("#messages_edit_ref_location").clearDiv();
				$("#messages_edit_ref_location").dialog('close');
				$("#messages_edit_ref_location").dialog("option", "title", "");
			}
		},
		close: function(event, ui) {
			$("#messages_edit_ref_location").clearDiv();
			$("#messages_edit_ref_location").dialog("option", "title", "");
		}
	});
	$("#messages_ref_orders_type").addOption({"0":'Global',"<?php echo $this->session->userdata('user_id');?>":'Personal'});
	$("#add_test_cpt3").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		buttons: {
			'Save': function() {
				var a = $("#messages_ref").val();
				var b = $("#messages_ref_cpt").val();
				var c = $("#messages_ref_orders_type").val();
				var d = a + b;
				var e = $("#messages_ref_snomed").val();
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/chartmenu/add_orderslist');?>",
					data: "orders_description=" + a + "&cpt=" + b + "&orders_category=Referral&user_id=" + c + "&snomed=" +e,
					success: function(data){
						$.jGrowl(data);
					}
				});
				if(b){
					a = a + ', CPT ' + b;
				}
				var terms = split($("#messages_ref_orders_text").val());
				terms.pop();
				terms.push(a);
				terms.push( "" );
				$("#messages_ref_orders").focus();
				$("#messages_ref_orders").val(terms.join( "\n" ));
				$("#add_test_cpt3").clearDiv();
				$("#add_test_cpt3").dialog('close');
				return false;
			},
			Cancel: function() {
				var terms = split($("#messages_ref_orders_text").val());
				terms.pop();
				terms.push( "" );
				$("#messages_ref_orders").focus();
				$("#messages_ref_orders").val(terms.join( "\n" ));
				$("#add_test_cpt3").clearDiv();
				$("#add_test_cpt3").dialog('close');
				return false;
			}
		},
		open: function(event, ui) {
			$.ajax({
				url: "<?php echo site_url('start/check_snomed_extension');?>",
				type: "POST",
				success: function(data){
					if(data =='y'){
						$("#add_test_snomed_div3").show();
						$("#snomed_tree3").jstree({
							"plugins" : [ "json_data", "sort", "ui", "themeroller" ],
							"json_data" : {
								"ajax" : {
									"type": 'POST',
									"url": function (node) {
										var nodeId = "";
										var url = "";
										if (node == -1) {
											url = "<?php echo site_url('search/snomed_parent/ref');?>";
										} else {
											nodeId = node.attr('id');
											url = "<?php echo site_url('search/snomed_child');?>/" + nodeId;
										}
										return url;
									},
									"success": function (new_data) {
										return new_data;
									}
								}
							},
							"themeroller" : {
								"item" : 'ui-widget-content'
							}
						}).bind("select_node.jstree", function (event, data) {
							$("#messages_ref_snomed").val(data.rslt.obj.attr("id"));
						});
						$("#messages_ref_snomed").autocomplete({
							source: function (req, add){
								$.ajax({
									url: "<?php echo site_url('search/snomed');?>/procedure",
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
					} else {
						$("#add_test_snomed_div3").hide();
					}
				}
			});
			$("#messages_ref_cpt").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "<?php echo site_url('search/cpt');?>",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								add(data.message);
							} else {
								var addterm = [{"label": req.term + ": Select to add CPT to database.", "value":"*/add/*", "value1": req.term}];
								add(addterm);
							}
						}
					});
				},
				select: function(event, ui){
					if (ui.item.value == "*/add/*") {
						$("#configuration_cpt_form").clearForm();
						if (ui.item.value1.length > 5) {
							$("#configuration_cpt_description").val(ui.item.value1);
						} else {
							$("#configuration_cpt_code").val(ui.item.value1);
						}
						$('#configuration_cpt_origin').val("messages_ref_cpt");
						$('#configuration_cpt_dialog').dialog('open');
						$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
					}
				},
				minLength: 3
			});
			$("#messages_ref_orders_type").val('0');
		},
		close: function(event, ui) {
			var a = $("#messages_ref").val();
			if (a != "") {
				if(confirm('Are you sure you want to close this window?  The test has not been saved.')){ 
					var terms = split($("#messages_ref_orders_text").val());
					terms.pop();
					terms.push( "" );
					$("#messages_ref_orders").focus();
					$("#messages_ref_orders").val(terms.join( "\n" ));
					$("#add_test_cpt3").clearDiv();
					$("#add_test_cpt3").dialog('close');
					return false;
				}
			}
		}
	});
	$("#messages_ref_orderslist_link").button().click(function(){
		$("#configuration_dialog").dialog('open');
		$("#configuration_accordion").accordion("option", "active", 6);
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('assistant/chartmenu/get_ref_templates_list');?>",
		dataType: "json",
		success: function(data){
			$('#messages_ref_template').addOption({"":"*Select a template"});
			$('#messages_ref_template').addOption(data.options);
		}
	});
	$('#messages_ref_template').change(function (){
		var a = $(this).val();
		if (a != "") {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/chartmenu/get_ref_template');?>/" + a,
				dataType: "json",
				success: function(data){
					$('#messages_ref_form').html('');
					$('#messages_ref_form').dform(data);
					$('.ref_buttonset').buttonset();
					$('input.ref_other[type="checkbox"]').button();
					$(".ref_select").chosen();
				}
			});
		}
	});
	$("#messages_ref_template_save").button({icons: {primary: "ui-icon-arrowthick-1-w"}}).click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('assistant/chartmenu/make_referral');?>",
			dataType: "json",
			success: function(data){
				var preview = "Requested action:\n";
				var b = $(".ref_hidden").val();
				preview += b + '\n';
				$('input.ref_intro[type="checkbox"]').each(function (){
					if ($(this).is(':checked')) {
						preview += $(this).val() + '\n';
					}
				});
				$('select.ref_intro').each(function (){
					if ($(this).val() != "") {
						var select_label = $(this).parent().children('span').html();
						preview += select_label + " " + $(this).val() + '\n';
					}
				});
				$('input.ref_intro[type="text"]').each(function (){
					if ($(this).val() != "") {
						var label = $(this).attr("placeholder");
						preview += label + ": " + $(this).val() + '\n';
					}
				});
				preview += '\n';
				var issues = data.issues;
				preview += "Active Issues:";
				var issues_len = issues.length;
				for(var i=0; i<issues_len; i++) {
					preview += '\n' + issues[i];
				}
				preview += '\n\n';
				var meds = data.meds;
				preview += "Active Medications:";
				var meds_len = meds.length;
				for(var j=0; j<meds_len; j++) {
					preview += '\n' + meds[j];
				}
				preview += '\n\n';
				var allergies = data.allergies;
				preview += "Allergies:";
				var allergies_len = allergies.length;
				for(var k=0; k<allergies_len; k++) {
					preview += '\n' + allergies[k];
				}
				preview += '\n\n';
				$('input.ref_after[type="checkbox"]').each(function (){
					if ($(this).is(':checked')) {
						preview += $(this).val() + '\n';
					}
				});
				$('select.ref_after').each(function (){
					if ($(this).val() != "") {
						var select_label = $(this).parent().children('span').html();
						preview += select_label + " " + $(this).val() + '\n';
					}
				});
				$('input.ref_after[type="text"]').each(function (){
					if ($(this).val() != "") {
						var label = $(this).attr("placeholder");
						preview += label + ": " + $(this).val() + '\n';
					}
				});
				preview += '\n' + 'Sincerely,' + '\n\n' + data.displayname;
				var terms = split($("#messages_ref_orders").val());
				terms.pop();
				terms.push( preview );
				terms.push( "" );
				var new_terms = terms.join( "\n" );
				$("#messages_ref_orders").val(new_terms);
				$('#messages_ref_form').clearDiv();
			}
		});
	});
</script>
