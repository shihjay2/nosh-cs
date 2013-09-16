<div id="messages_ref_dialog" title="Referral Helper">
	<input type="hidden" name="messages_ref_origin" id="messages_ref_origin"/>
	<form name="edit_message_ref_form" id="edit_message_ref_form">
	<div id="messages_ref_grid">
		<button type="button" id="save_ref_helper" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
			<span style="float:right;" class="ui-button-text"><div id="save_ref_helper_label"></div></span>
		</button><button type="button" id="cancel_ref_helper" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
			<span style="float:right;" class="ui-button-text">Cancel</span>
		</button> 
		<hr class="ui-state-default"/>
		<table id="messages_ref_list" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_ref_list_pager" class="scroll" style="text-align:center;"></div><br>
		<input type="button" id="messages_add_ref" value="Add Referral" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_edit_ref" value="Edit Referral" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_delete_ref" value="Delete Referral" class="ui-button ui-state-default ui-corner-all"/><br>
	</div>
	<div id="messages_ref_edit_fields">
		<button type="button" id="messages_ref_save" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
			<span style="float:right;" class="ui-button-text">Save</span>
		</button><button type="button" id="messages_ref_cancel" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
			<span style="float:right;" class="ui-button-text">Cancel</span>
		</button> 
		<div style="float:right;" "id="messages_ref_status"></div>
		<hr class="ui-state-default"/>
		<input type="hidden" name="address_id" id="messages_ref_location_id"/>
		<input type="hidden" name="orders_id" id="messages_ref_orders_id"/>
		<input type="hidden" name="t_messages_id" id="messages_ref_t_messages_id"/>
		<div id="messages_ref_accordion">
			<h3><a href="#">Referral Reason</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_ref" id="messages_ref_orders" rows="3" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"></textarea></td>
						<td valign="top"><br><input type="button" id="messages_ref_orders_edit" value="Edit" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/><br><input type="button" id="messages_ref_orders_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
				</table>
				<div id="messages_ref1_fieldset">
					<fieldset class="ui-state-default ui-corner-all">
						<legend>Reason</legend>
						<input type="hidden" name="messages_ref_id" id="messages_ref_id"/>
						<input type="button" id="messages_ref_reason1_button" value="Consultation" class="ui-button ui-state-default ui-corner-all"/> 
						<input type="button" id="messages_ref_reason2_button" value="Referral" class="ui-button ui-state-default ui-corner-all"/> 
						<input type="button" id="messages_ref_reason3_button" value="Physical Therapy" class="ui-button ui-state-default ui-corner-all"/> 
						<input type="button" id="messages_ref_reason4_button" value="Massage Therapy" class="ui-button ui-state-default ui-corner-all"/>
						<input type="button" id="messages_ref_reason5_button" value="Sleep Study" class="ui-button ui-state-default ui-corner-all"/><br>
					</fieldset><br>
					<div id="messages_ref_reason1" style="display: none">
						<div id="messages_ref_reason1_form">
							<fieldset class="ui-state-default ui-corner-all">
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
											<td><input type="button" id="messages_add_ref1" value="Submit" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_cancel_ref1" value="Cancel" class="ui-button ui-state-default ui-corner-all"/></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
					<div id="messages_ref_reason2" style="display: none">
						<div id="messages_ref_reason2_form">
							<fieldset class="ui-state-default ui-corner-all">
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
											<td><input type="button" id="messages_add_ref2" value="Submit" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_cancel_ref2" value="Cancel" class="ui-button ui-state-default ui-corner-all"/></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
					<div id="messages_ref_reason3" style="display: none">
						<div id="messages_ref_reason3_form">
							<fieldset class="ui-state-default ui-corner-all">
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
											<td><input type="button" id="messages_add_ref3" value="Submit" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_cancel_ref3" value="Cancel" class="ui-button ui-state-default ui-corner-all"/></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
					<div id="messages_ref_reason4" style="display: none">
						<div id="messages_ref_reason4_form">
							<fieldset class="ui-state-default ui-corner-all">
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
											<td><input type="button" id="messages_add_ref4" value="Submit" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_cancel_ref4" value="Cancel" class="ui-button ui-state-default ui-corner-all"/></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
					<div id="messages_ref_reason5" style="display: none">
						<div id="messages_ref_reason5_form">
							<fieldset class="ui-state-default ui-corner-all">
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
											<td><input type="button" id="messages_add_ref5" value="Submit" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_cancel_ref5" value="Cancel" class="ui-button ui-state-default ui-corner-all"/>
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
						<td valign="top">Preview:<br><textarea name="orders_ref_icd" id="messages_ref_codes" rows="3" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"></textarea></td>	
						<td valign="top"><br>
							<input type="button" id="messages_ref_codes_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/><br>
							<input type="button" id="messages_ref_issues" value="Issues" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/>
						</td>
					</tr>
					<tr>
						<td valign="top">Search ICD:<br><input type="text" name="messages_ref_icd_search" id="messages_ref_icd_search" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_select_ref_icd" value="Select" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
				</table>
			</div>
			<h3><a href="#">Location</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><input type="text" name="messages_ref_location"  id="messages_ref_location" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_ref_location_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
						<td></td>
					</tr>
				</table>
				<table>
					<tr>
						<td valign="top">Search Referral Provider:<br><input type="text" name="messages_ref_location_search" id="messages_ref_location_search" style="width:500px" class="text ui-widget-content ui-corner-all"/></td>
						<td valign="top"><br><input type="button" id="messages_select_ref_location1" value="Select" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/> <input type="button" id="messages_select_ref_location2" value="Add/Edit" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/></td>
					</tr>
				</table>
				<div id="messages_edit_ref_location" style="display:none"><hr class="ui-state-default"/>
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
								<td colspan="2" valign="top">Comments:<br><input type="text" name="comments" id="messages_ref_location_comments" style="width:330px" class="text ui-widget-content ui-corner-all"/></td>
								<td valign="top">
									<br><button type="button" id="messages_add_ref_location" style="font-size: 0.8em" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
										<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
										<span style="float:right;" class="ui-button-text">Add and Save Contact to Address Book</span>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<h3><a href="#">Insurance</a></h3>
			<div>
				<table>
					<tr>
						<td valign="top">Preview:<br><textarea name="orders_insurance"  id="messages_ref_insurance" rows="3" style="width:500px" class="text ui-state-default ui-widget-content ui-corner-all"/></textarea></td>
						<td valign="top"><br>
							<input type="button" id="messages_ref_insurance_clear" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/><br>
							<input type="button" id="messages_ref_insurance_client" value="Bill Client" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"/>
						</td>
					</tr>
				</table><br>
				<table id="messages_ref_insurance_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="messages_ref_insurance_pager" class="scroll" style="text-align:center;"></div><br>
			</div>
		</div>
	</div>
	<div id="messages_ref_action_fieldset" style="display:none">
		<fieldset class="ui-state-default ui-corner-all">
		<legend>Action</legend>
		<div id="messages_ref_choice"></div><br><br>
		<input type="button" id="messages_print_ref" value="Print" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="mesvar a = $("#messages_ref_location_lastname").val();sages_electronic_ref" value="Electronic" class="ui-button ui-state-default ui-corner-all"/> 
		<input type="button" id="messages_fax_ref" value="Fax" class="ui-button ui-state-default ui-corner-all"/>
		<input type="button" id="messages_done_ref" value="Done" class="ui-button ui-state-default ui-corner-all"/> 
	</div>
	</form>
</div>
<script type="text/javascript">
	$("#messages_ref_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function(){
			 $("#messages_ref_accordion").accordion({ autoHeight: false });
			 $("#messages_ref_grid").show('fast');
			 $("#messages_ref_edit_fields").hide('fast');
			 jQuery("#messages_ref_list").jqGrid('GridUnload');
			 jQuery("#messages_ref_list").jqGrid({
				url:"<?php echo site_url('provider/chartmenu/ref_list');?>",
				postData: {t_messages_id: function(){return $("#messages_ref_t_messages_id").val();}},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Referral','Diagnosis','Location1','Location','Insurance'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_referrals',index:'orders_ref',width:300},
					{name:'orders_referrals_icd',index:'orders_ref_icd',width:200},
					{name:'address_id',index:'address_id',hidden:true},
					{name:'displayname',index:'displayname',width:100},
					{name:'orders_insurance',index:'orders_insurance',hidden:true}
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
							url: "<?php echo site_url('provider/chartmenu/addressdefine');?>",
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
				url:"<?php echo site_url('provider/chartmenu/insurance/');?>",
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
			 	caption:"Insurance Payors - Double click to select insurance for imaging order",
			 	height: "100%",
			 	ondblClickRow: function(id){
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
		},
		beforeclose: function(event, ui) {
			var a = $("#messages_ref_orders").val();
			if(a != ''){
				if(confirm('The form fields are not empty.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
					$('#edit_message_ref_form').clearForm();
					$("#messages_ref").val('');
					$("#messages_ref_icd_search").val('');
					$("#edit_message_ref_location_form").clearForm();
					$("#messages_ref_location_search").val('');
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
	$("#messages_add_ref").click(function(){
		$('#edit_message_ref_form').clearForm();
		var id = $("#t_messages_id").val();
		$("#messages_ref_t_messages_id").val(id);
		$("#messages_ref_status").html('');
		$("#messages_ref_edit_fields").show('fast');
		$("#messages_ref_accordion").accordion("activate", 0);
		$("#messages_ref_grid").hide('fast');
	});
	$("#messages_edit_ref").click(function(){
		var item = jQuery("#messages_ref_list").getGridParam('selrow');
		if(item){
			jQuery("#messages_ref_list").GridToForm(item,"#edit_message_ref_form");
			var address_id = $("#messages_ref_location_id").val();
			$.ajax({
				url: "<?php echo site_url('provider/chartmenu/addressdefine');?>",
				type: "POST",
				data: "address_id=" + address_id,
				dataType: 'json',
				success: function(data){
					$("#messages_ref_location").val(data.item);
				}
			});
			var status = 'Details for Referral Order #' + item;
			$("#messages_ref_status").html(status);
			$("#messages_ref_edit_fields").show('fast');
			$("#messages_ref_accordion").accordion("activate", 0);
			$("#messages_ref_grid").hide('fast');
		} else {
			$.jGrowl("Please select ref to edit!");
		}
	});var i = $("#messages_ref_location_address_id").val();
	$("#messages_delete_ref").click(function(){
		var item = jQuery("#messages_ref_list").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "<?php echo site_url('provider/chartmenu/delete_ref');?>",
				type: "POST",
				data: "orders_id=" + item,
				success: function(data){
					$.jGrowl(data);
					jQuery("#messages_ref_list").trigger("reloadGrid");			
				}
			});
		} else {
			$.jGrowl("Please select ref to delete!");
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
	$("#messages_ref_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/ref');?>",
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
	$("#messages_select_ref1").click(function(){
		var a = $("#messages_ref_search").val();
		var pos = a.indexOf('[');
		if (pos == -1) {
			$.jGrowl("Please enter test!");
		} else {
			var pos1 = pos - 1;
			var ref = a.slice(0, pos1);
			var old = $("#messages_ref_orders").val();
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
			$("#messages_ref_orders").val(old1+ref);
			$("#messages_ref_search").val('');
		}
	});
	$("#messages_select_ref2").click(function(){
		var a = $("#messages_ref_search").val();
		var pos = a.indexOf('[');
		if (pos == -1) {
			$("#messages_ref").val(a);
			$("#messages_ref_search").val('');
			$("#messages_ref").focus();
			$("#messages_ref_edit").show('fast');
		} else {
			var id1 = a.slice(pos);
			var id2 = id1.replace("[", "");
			var id = id2.replace("]", "");
			if(id){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/order1');?>",
					data: "orderslist_id=" + id,
					dataType: "json",
					success: function(data){
						$("#messages_ref").val(data.orders_description);
						$("#messages_ref_id").val(data.orderslist_id);
						$("#messages_ref_reft").val(data.reft);
						$("#messages_ref_search").val('');
						$("#messages_ref").focus();
						$("#messages_ref_edit").show('fast');
					}
				});
			} else {
				$.jGrowl("Please enter test to edit!");
			}
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
			url: "<?php echo site_url('provider/chartmenu/make_referral');?>",
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
			url: "<?php echo site_url('provider/chartmenu/make_referral');?>",
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
		minLength: 3,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
	});
	$("#messages_ref_icd_search").autocomplete({
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
	$('#messages_ref_icd_search').bt('Use a comma to separate distinct search terms!',{width: 200});
	$("#messages_select_ref_icd").click(function(){
		var item = $("#messages_ref_icd_search").val();
		var old = $("#messages_ref_codes").val();
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
		if(item){
			$("#messages_ref_codes").val(old1+item);
			$("#messages_ref_icd_search").val('');
		} else {
			$.jGrowl("Field is empty!");
		}
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
	$("#messages_ref_location_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/ref_provider');?>",
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
	$('#messages_ref_location_search').bt('Search by name or by specialty!');
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
		minLength: 3,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
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
		minLength: 3,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
	});
	$("#messages_select_ref_location1").click(function (){
		var item = $("#messages_ref_location_search").val();
		var pos = item.indexOf('(');
		if (pos == -1) {
			$.jGrowl("Please enter imaging provider!");
		} else {
			var id1 = item.slice(pos);
			var id2 = id1.replace("(", "");
			var id = id2.replace(")", "");
			if(id){
				$("#messages_ref_location").val(item);
				$("#messages_ref_location_id").val(id);
			} else {
				$.jGrowl("Please enter Referral provider!");
			}       
		}
	});
	$("#messages_select_ref_location2").click(function (){
		var item = $("#messages_ref_location_search").val();
		var pos = item.indexOf('(');
		if (pos == -1) {
			$("#messages_ref_location_facility").val(item);
			$("#messages_ref_location_facility").focus();
			$("#messages_edit_ref_location").show('fast');
		} else {
			var id1 = item.slice(pos);
			var id2 = id1.replace("(", "");
			var id = id2.replace(")", "");
			if(id){
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
						$("#messages_ref_location_search").val('');
						$("#messages_ref_location_lastname").focus();
						$("#messages_edit_ref_location").show('fast');
					}
				});
			} else {
				$.jGrowl("Please enter Referral provider!");
			}       
		}
	});
	$("#messages_ref_location_state").addOption({"AL":"Arefama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorefo","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#messages_ref_location_phone").mask("(999) 999-9999");
	$("#messages_ref_location_fax").mask("(999) 999-9999");
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
				url: "<?php echo site_url('provider/chartmenu/edit_ref_provider');?>",
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
	$("#messages_ref_save").click(function(){
		var order = $("#messages_ref_orders");
		var codes = $("#messages_ref_codes");
		var location = $("#messages_ref_location");
		var insurance = $("#messages_ref_insurance")
		var bValid = true;
		bValid = bValid && checkEmpty(order,"Tests");
		bValid = bValid && checkEmpty(codes,"Diagnosis Codes");
		bValid = bValid && checkEmpty(location,"reforatory Provider");
		bValid = bValid && checkEmpty(insurance,"Insurance");
		if (bValid) {
			var a = $("#messages_ref_orders").val();
			var b = $("#messages_ref_codes").val();
			var c = $("#messages_ref_location_id").val();
			var d = $("#messages_ref_t_messages_id").val();
			var e = $("#messages_ref_orders_id").val();
			var f = $("#messages_ref_insurance").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/add_ref_order');?>",
				data: "orders_ref=" + a + "&orders_ref_icd=" + b + "&address_id=" + c + "&t_messages_id=" + d + "&orders_id=" + e + "&orders_insurance=" + f,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					$("#messages_ref_orders_id").val(data.id);
					$('#messages_ref_choice').html(data.choice);
					$("#messages_ref_action_fieldset").show('fast');
					$("#messages_ref_edit_fields").hide('fast');
					$("#messages_edit_ref").hide('fast');
					$("#messages_edit_ref_location").hide('fast');
					jQuery("#alerts").trigger("reloadGrid");
					jQuery("messages_ref_list").trigger("reloadGrid");
				}
			});
		}
	});
	$("#messages_ref_cancel").click(function(){
		$("#messages_ref_edit_fields").hide('fast');
		$("#messages_ref_action_fieldset").hide('fast');
		$("#messages_ref_grid").show('fast');
		jQuery("#messages_ref_list").trigger("reloadGrid");
	});
	$("#save_ref_helper").click(function(){
		var origin = $("#messages_ref_origin").val();
		if (origin == 'message') {
			var id = $("#t_messages_id").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/import_ref1');?>",
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
				url: "<?php echo site_url('provider/encounters/check_orders');?>",
				dataType: "json",
				success: function(data){
					$('#button_orders_labs_status').html(data.labs_status);
					$('#button_orders_ref_status').html(data.ref_status);
					$('#button_orders_ref_status').html(data.ref_status);
					$('#button_orders_ref_status').html(data.ref_status);
					$('#button_orders_rx_status').html(data.rx_status);
					$('#button_orders_imm_status').html(data.imm_status);
					$('#button_orders_sup_status').html(data.sup_status);
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/tip_orders/ref');?>",
						success: function(data){
							$('#orders_ref_tip').html(data);
						}
					});
				}
			});
		}
		$('#edit_message_ref_form').clearForm();
		$("#messages_ref").val('');
		$("#messages_ref_origin").val('');
		$("#messages_ref_reft").val('');
		$("#messages_ref_icd_search").val('');
		$("#edit_message_ref_location_form").clearForm();
		$("#messages_ref_location_search").val('');
		$("#messages_ref_edit_fields").hide('fast');
		$("#messages_ref_action_fieldset").hide('fast');
		$("#messages_edit_ref").hide('fast');
		$("#messages_edit_ref_location").hide('fast');
		$("#edit_message_ref_form").show('fast');
		$("#messages_ref_dialog").dialog('close');
	});
	$("#cancel_ref_helper").click(function(){
		$('#edit_message_ref_form').clearForm();
		$("#messages_ref").val('');
		$("#messages_ref_origin").val('');
		$("#messages_ref_reft").val('');
		$("#messages_ref_icd_search").val('');
		$("#edit_message_ref_location_form").clearForm();
		$("#messages_ref_location_search").val('');
		$("#messages_ref_edit_fields").hide('fast');
		$("#messages_ref_action_fieldset").hide('fast');
		$("#messages_edit_ref").hide('fast');
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
			window.open("<?php echo site_url('provider/chartmenu/print_orders');?>/" + order_id);
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
					url: "<?php echo site_url('provider/chartmenu/fax_orders');?>",
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
</script>
