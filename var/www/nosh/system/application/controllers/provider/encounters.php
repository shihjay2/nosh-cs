<?php

class Encounters extends Application
{

	function Encounters()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->auth->restrict('provider');
		$this->load->helper(array('text', 'typography'));
		$this->load->library('pagination');
		$this->load->model('encounters_model');
		$this->load->model('demographics_model');
		$this->load->model('practiceinfo_model');
		$this->load->model('schedule_model');
		$this->load->model('chart_model');
		$this->load->model('audit_model');
	}

	// --------------------------------------------------------------------

	function index()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('provider/chartmenu/');
		} else {
			$encounterInfo = $this->encounters_model->getEncounter($eid);
			if ($encounterInfo->num_rows() == 0) {
				redirect('provider/chartmenu/');
			} else {
				redirect('provider/encounters/view/');
			}
		}
	}

	// --------------------------------------------------------------------

	function new_encounter()
	{
		$date = strtotime($this->input->post('encounter_date') . " " . $this->input->post('encounter_time'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$encounter_DOS = mdate($datestring, $date);	
		$e1 = $this->input->post('encounter_location');
		$e2 = stristr($e1, '(');
		$e3 = str_replace('(', '', $e2);
		$encounter_location = str_replace(')', '', $e3);	
		$f1 = $this->input->post('encounter_type');
		$f2 = strpos($f1,',');
		$encounter_type = substr($f1, 0, $f2);
		$f3 = $f2 + 1;
		$appt_id = substr($f1, $f3);		
		$data = array(
			'pid' => $this->session->userdata('pid'),
			'appt_id' => $appt_id,
			'encounter_provider' => $this->session->userdata('displayname'),
			'encounter_DOS' => $encounter_DOS,
			'encounter_age' => $this->session->userdata('age'),
			'encounter_type' => $encounter_type,
			'encounter_location' =>  $this->input->post('encounter_location'),
			'encounter_cc' => $this->input->post('encounter_cc'),
			'encounter_signed' => 'No',
			'encounter_condition' => $this->input->post('encounter_condition'),
			'encounter_condition_work' => $this->input->post('encounter_condition_work'),
			'encounter_condition_auto' => $this->input->post('encounter_condition_auto'),
			'encounter_condition_auto_state' => $this->input->post('encounter_condition_auto_state'),
			'encounter_condition_other' => $this->input->post('encounter_condition_other'),
			'addendum' => 'n',
			'user_id' => $this->session->userdata('user_id'),
			'encounter_role' => $this->input->post('encounter_role'),
			'referring_provider' => $this->input->post('referring_provider'),
			'referring_provider_npi' => $this->input->post('referring_provider_npi'),
			'practice_id' => $this->session->userdata('practice_id')
		);
		$eid = $this->encounters_model->addEncounter($data);
		$this->audit_model->add();
		$data2 = array(
			'status' => 'Attended'
		);
		$this->schedule_model->update_event($appt_id, $data2);
		$this->audit_model->update();
		$data3 = array(
			'addendum_eid' => $eid
		);
		$this->encounters_model->updateEncounter($eid, $data3);
		$this->audit_model->update();
		if ($eid > 0) {
			$this->session->set_userdata('eid', $eid);
			$this->session->set_userdata('encounter_DOS', $encounter_DOS);
		} else {
			echo "Adding new encounter failed!";
		}
	}
	
	function new_addendum($eid)
	{
		$encounter = $this->encounters_model->getEncounter($eid)->row_array();
		unset($encounter['eid']);
		unset($encounter['encounter_signed']);
		$encounter['encounter_signed'] = 'No';
		$new_eid = $this->encounters_model->addEncounter($encounter);
		$this->audit_model->add();
		$data1 = array(
			'addendum' => 'y'
		);
		$this->encounters_model->updateEncounter($eid, $data1);
		$this->audit_model->update();
		if ($this->encounters_model->getHPI($eid)->num_rows() > 0) {
			$hpi = $this->encounters_model->getHPI($eid)->row_array();
			unset($hpi['eid']);
			$hpi['eid'] = $new_eid;
			$this->encounters_model->addHPI($hpi);
			$this->audit_model->add();
		}
		if ($this->encounters_model->getROS($eid)->num_rows() > 0) {
			$ros = $this->encounters_model->getROS($eid)->row_array();
			unset($ros['eid']);
			$ros['eid'] = $new_eid;
			$this->encounters_model->addROS($ros);
			$this->audit_model->add();
		}
		$oh = $this->encounters_model->getOtherHistory($eid);
		if ($oh->num_rows() > 0) {
			foreach ($oh->result_array() as $oh_row) {
				unset($oh_row['eid']);
				unset($oh_row['oh_id']);
				$oh_row['eid'] = $new_eid;
				$this->encounters_model->addOtherHistory($oh_row);
				$this->audit_model->add();
			}
		}
		if ($this->encounters_model->getVitals($eid)->num_rows() > 0) {
			$vitals = $this->encounters_model->getVitals($eid)->row_array();
			unset($vitals['eid']);
			$vitals['eid'] = $new_eid;
			$this->encounters_model->addVitals($vitals);
			$this->audit_model->add();
		}
		if ($this->encounters_model->getPE($eid)->num_rows() > 0) {
			$pe = $this->encounters_model->getPE($eid)->row_array();
			unset($pe['eid']);
			$pe['eid'] = $new_eid;
			$this->encounters_model->addPE($pe);
			$this->audit_model->add();
		}
		if ($this->encounters_model->getLabs($eid)->num_rows() > 0) {
			$labs = $this->encounters_model->getLabs($eid)->row_array();
			unset($labs['eid']);
			$labs['eid'] = $new_eid;
			$this->encounters_model->addLabs($labs);
			$this->audit_model->add();
		}
		if ($this->encounters_model->getProcedure($eid)->num_rows() > 0) {
			$procedure = $this->encounters_model->getProcedure($eid)->row_array();
			unset($procedure['eid']);
			$procedure['eid'] = $new_eid;
			$this->encounters_model->addProcedure($procedure);
			$this->audit_model->add();
		}
		if ($this->encounters_model->getAssessment($eid)->num_rows() > 0) {
			$assessment = $this->encounters_model->getAssessment($eid)->row_array();
			unset($assessment['eid']);
			$assessment['eid'] = $new_eid;
			$this->encounters_model->addAssessment($assessment);
			$this->audit_model->add();
		}
		if ($this->encounters_model->getOrders($eid)->num_rows() > 0) {
			$orders = $this->encounters_model->getOrders($eid)->row_array();
			unset($orders['eid']);
			unset($orders['orders_id']);
			$orders['eid'] = $new_eid;
			$this->encounters_model->addOrders($orders);
			$this->audit_model->add();
		}
		if ($this->encounters_model->getRX($eid)->num_rows() > 0) {
			$rx = $this->encounters_model->getRX($eid)->row_array();
			unset($rx['eid']);
			$rx['eid'] = $new_eid;
			$this->encounters_model->addRX($rx);
			$this->audit_model->add();
		}
		if ($this->encounters_model->getPlan($eid)->num_rows() > 0) {
			$plan = $this->encounters_model->getPlan($eid)->row_array();
			unset($plan['eid']);
			$plan['eid'] = $new_eid;
			$this->encounters_model->addPlan($plan);
			$this->audit_model->add();
		}
		if ($this->encounters_model->getBilling($eid)->num_rows() > 0) {
			$billing = $this->encounters_model->getBilling($eid)->row_array();
			unset($billing['eid']);
			unset($billing['bill_id']);
			$billing['eid'] = $new_eid;
			$this->encounters_model->addBilling($billing);
			$this->audit_model->add();
		}
		$billing_core = $this->encounters_model->getBillingCore($eid);
		if ($billing_core->num_rows() > 0) {
			foreach ($billing_core->result_array() as $billing_core_row) {
				unset($billing_core_row['eid']);
				unset($billing_core_row['billing_core_id']);
				$billing_core_row['eid'] = $new_eid;
				$this->encounters_model->addBillingCore($billing_core_row);
				$this->audit_model->add();
			}
		}
		$this->session->set_userdata('eid', $new_eid);
		$this->session->set_userdata('encounter_DOS', $result['encounter_DOS']);
		redirect('provider/encounters/view/');
	}
	
	function previous_versions($eid)
	{
		$encounter = $this->encounters_model->getEncounter($eid)->row_array();
		$this->db->where('addendum_eid', $encounter['addendum_eid']);
		$query = $this->db->get('encounters');
		if ($query->num_rows() > 1) {
			$result = "";
			foreach ($query->result_array() as $row) {
				if ($row['addendum'] != "n" && $row['encounter_signed'] === 'Yes') {
					$result .= '<a href="#" id="' . $row['eid'] . '" class="addendum_class">Date signed: ' . $row['date_signed'] . '</a><br>';
				}
			}
			$result .= '<h4>Current version:</h4><a href="#" id="' . $eid . '" class="addendum_class">Date signed: ' . $encounter['date_signed'] . '</a><br>';
		} else {
			$result = "None.";
		}
		echo $result;
	}
	
	function get_previous_versions($eid)
	{
		$data = $this->modal_view_text($eid);
		$html = $this->load->view('auth/pages/provider/chart/encounters/modal_view_insert', $data);
		echo $html;
	}

	// --------------------------------------------------------------------

	function view()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}	
		$encounterInfo = $this->encounters_model->getEncounter($eid);
		$data['encounter'] = $encounterInfo->row();
		$gender = $this->session->userdata('gender');
		if ($gender == 'male') {	
			$data['sports1'] = '<tr>
									<td>Hernia, undescended testicle, or loss of testicle:</td>
									<td><input name="sports_testicle" id="sports_testicle_n" value="No history of hernias, undescended testicles, or loss of testicles." type="radio"> No</td>
									<td><input name="sports_testicle" id="sports_testicle_y" value="Hernia, undescended testicle, or loss of testicle: " type="radio"> Yes</td>
									<td><div id="sports_testicle_input" style="display:none"><input type="text" name="sports_testicle_text" id="sports_testicle_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>';
			$data['sports2'] = '<tr>
									<td>Absence of a testicle, kidney, or lung:</td>
									<td><input name="sports_absence" id="sports_absence_n" value="No history of an absence of a testicle, kidney, or lung." type="radio"> No</td>
									<td><input name="sports_absence" id="sports_absence_y" value="Absence of a testicle, kidney, or lung:  " type="radio"> Yes</td>
									<td><div id="sports_absence_input" style="display:none"><input type="text" name="sports_absence_text" id="sports_absence_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>';
		} else {
			$data['sports1'] = '<tr>
									<td>Hernia or loss of ovaries:</td>
									<td><input name="sports_testicle" id="sports_testicle_n" value="No history of hernias or loss of ovaries." type="radio"> No</td>
									<td><input name="sports_testicle" id="sports_testicle_y" value="Hernia or loss of ovaries: " type="radio"> Yes</td>
									<td><div id="sports_testicle_input" style="display:none"><input type="text" name="sports_testicle_text" id="sports_testicle_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>';
			$data['sports2'] = '<tr>
									<td>Absence of an ovary, kidney, or lung:</td>
									<td><input name="sports_absence" id="sports_absence_n" value="No history of an absence of an ovary, kidney, or lung." type="radio"> No</td>
									<td><input name="sports_absence" id="sports_absence_y" value="Absence of an ovary, kidney, or lung:  " type="radio"> Yes</td>
									<td><div id="sports_absence_input" style="display:none"><input type="text" name="sports_absence_text" id="sports_absence_text" style="width:300px" class="text ui-widget-content ui-corner-all" /></div></td>
								</tr>';
		}
		$age = $this->session->userdata('agealldays');
		$result = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
		if ($result['mtm_extension'] == 'y') {
			$data['mtm'] = '<button type="button" id="hpi_mtm" class="nosh_button">MTM</button>';
		} else {
			$data['mtm'] = '';
		}
		if ($age <= 365.25) {
			$data['birth'] = '<input type="button" id="hpi_birth_hx_template" value="Birth History" class="nosh_button"> ';
		} else {
			$data['birth'] = '';
		}
		if ($age <= 6574.5) {
			$data['wcc'] = '<button type="button" id="hpi_wcc" class="nosh_button">Well Child Check</button>';
			$data['cpe'] = '';
			$data['preg'] = '';
		} else {
			$data['wcc'] = '';
			$data['cpe'] = '<button type="button" id="hpi_cpe" class="nosh_button">Complete Physical</button>';
			if ($gender == 'male') {
				$data['preg'] = '';
			} else {
				$data['preg'] = '<button type="button" id="hpi_preg" class="nosh_button">Pregnancy Status</button>';
			}
		}
		$this->auth->view('provider/chart/encounters/view', $data);
	}
	
	function hpi_template_select_list()
	{
		$user_id = $this->session->userdata('user_id');
		$age = $this->session->userdata('agealldays');
		$gender = $this->session->userdata('gender');
		if ($gender == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$this->db->where('user_id', $user_id);
		$this->db->or_where('user_id', '0');
		$this->db->where('sex', $sex);
		$this->db->where('category', 'hpi');
		if ($age > 6574.5) {
			$this->db->where('age', 'adult');
		}
		$result = $this->db->get('templates')->result_array();
		$data['options'] = array();
		foreach ($result as $row) {
			$id = $row['template_id'];
			if ($row['template_name'] == 'Global Default') {
				if ($row['group'] == 'hpi_generic') {
					$name = 'Generic';
				}
				if ($row['group'] == 'hpi_asthma') {
					$name = 'Asthma';
				}
				if ($row['group'] == 'hpi_prenatal') {
					$name = 'Prenatal';
				}
				if ($row['group'] == 'hpi_injury') {
					$name = 'Injury';
				}
				if ($row['group'] == 'hpi_sports') {
					$name = 'Sports Physical';
				}
				if ($row['group'] == 'hpi_pain') {
					$name = 'Chronic Pain';
				}
				if ($row['group'] == 'hpi_wwe') {
					$name = 'Well Woman Exam';
				}
				if ($row['group'] == 'hpi_birthhx') {
					$name = 'Birth History';
				}
			} else {
				$name = $row['template_name'];
			}
			$data['options'][$id] = $name;
		}
		echo json_encode($data);
	}
	
	function get_hpi_template($id)
	{
		$this->db->where('template_id', $id);
		$row = $this->db->get('templates')->row_array();
		$data = unserialize($row['array']);
		echo json_encode($data);
	}
	
	function edit_pregnancy()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$data = array(
			'pregnant' => $this->input->post('pregnant')
		);
		$this->demographics_model->update($pid, $data);
		$this->audit_model->update();
		echo "Patient infomration updated.";
	}
	
	function get_prenatal()
	{
		$pid = $this->session->userdata('pid');
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row();
		$result = $row->pregnant;
		echo $result;
	}
	
	function check_encounter()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}	
		$error = "";	
		$hpiInfo = $this->encounters_model->getHPI($eid);
		$rosInfo = $this->encounters_model->getROS($eid);
		$peInfo = $this->encounters_model->getPE($eid);
		$assessmentInfo = $this->encounters_model->getAssessment($eid);
		$billingInfo = $this->encounters_model->getBilling($eid);	
		$a = $hpiInfo->num_rows();
		$b = $rosInfo->num_rows();
		$c = $peInfo->num_rows();
		$d = $assessmentInfo->num_rows();
		$e = $billingInfo->num_rows();
		if ($a = 0) {
			$error .= "Missing History of Present Illness<br>";
		}
		if ($b = 0) {
			$error .= "Missing Review of Systems<br>";
		}
		if ($c = 0) {
			$error .= "Missing Review of Systems<br>";
		}
		if ($d = 0) {
			$error .= "Missing Assessment<br>";
		}
		if ($e = 0) {
			$error .= "Missing Billing<br>";
		}	
		echo $error;
		exit(0);
	}
	
	function sign_encounter()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			echo 'Close Chart';
			exit (0);
		}
		$date = now();
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_signed = mdate($datestring, $date);
		$data = array(
			'encounter_signed' => "Yes",
			'date_signed' => $date_signed
		);
		$this->encounters_model->updateEncounter($eid, $data);
		$this->audit_model->update();
		$this->session->unset_userdata('eid');
		echo 'Encounter Signed!';
		exit (0);
	}
	
	function delete_encounter()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}
		$data = $this->encounters_model->deleteEncounter($eid);
		$this->audit_model->delete();
		echo $data;
		exit(0);
	}

	// --------------------------------------------------------------------

	function modal_view_text($eid)
	{
		$this->lang->load('date');
		$pid = $this->session->userdata('pid');
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row_array();
		$data['eid'] = $eid;
		$dos1 = human_to_unix($encounterInfo['encounter_DOS']);
		$data['encounter_DOS'] = date('F jS, Y', $dos1);
		$data['encounter_provider'] = $encounterInfo['encounter_provider'];
		if ($encounterInfo['encounter_signed'] == 'No') {
			$data['status']	= 'Draft';
		} else {
			$date_signed1 = human_to_unix($encounterInfo['date_signed']);
			$date_signed = date('F jS, Y', $date_signed1);
			$data['status'] = 'Signed on ' . $date_signed . '.';
		}
		$data['age1'] = $encounterInfo['encounter_age'];
		$data['encounter_cc'] = nl2br($encounterInfo['encounter_cc']);	
		$practiceInfo = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
		$data['practiceName'] = $practiceInfo['practice_name'];
		$data['website'] = $practiceInfo['website'];
		$data['practiceInfo'] = $practiceInfo['street_address1'];
		if ($practiceInfo['street_address2'] != '') {
			$data['practiceInfo'] .= ', ' . $practiceInfo['street_address2'];
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practiceInfo['city'] . ', ' . $practiceInfo['state'] . ' ' . $practiceInfo['zip'] . '<br />';
		$data['patientInfo'] = $this->demographics_model->get($pid)->row();
		$dob1 = human_to_unix($data['patientInfo']->DOB);
		$data['dob'] = date('F jS, Y', $dob1);
		$data['age'] = $this->session->userdata('age');
		$data['gender'] = ucfirst($this->session->userdata('gender'));	
		$hpiInfo = $this->encounters_model->getHPI($eid)->row_array();
		if ($hpiInfo) {
			$data['hpi'] = '<br><h4>History of Present Illness:</h4><p class="view">';
			$data['hpi'] .= nl2br($hpiInfo['hpi']);
			$data['hpi'] .= '</p>';
		} else {
			$data['hpi'] = '';
		}	
		$rosInfo = $this->encounters_model->getROS($eid)->row_array();
		if ($rosInfo) {
			$data['ros'] = '<br><h4>Review of Systems:</h4><p class="view">';
			if ($rosInfo['ros_gen'] != '') {
				$data['ros'] .= '<strong>General: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_gen']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_eye'] != '') {
				$data['ros'] .= '<strong>Eye: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_eye']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_ent'] != '') {
				$data['ros'] .= '<strong>Ears, Nose, Throat: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_ent']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_resp'] != '') {
				$data['ros'] .= '<strong>Respiratory: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_resp']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_cv'] != '') {
				$data['ros'] .= '<strong>Cardiovascular: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_cv']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_gi'] != '') {
				$data['ros'] .= '<strong>Gastrointestinal: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_gi']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_gu'] != '') {
				$data['ros'] .= '<strong>Genitourinary: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_gu']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_mus'] != '') {
				$data['ros'] .= '<strong>Musculoskeletal: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_mus']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_neuro'] != '') {
				$data['ros'] .= '<strong>Neurological: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_neuro']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_psych'] != '') {
				$data['ros'] .= '<strong>Psychological: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_psych']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_heme'] != '') {
				$data['ros'] .= '<strong>Hematological, Lymphatic: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_heme']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_endocrine'] != '') {
				$data['ros'] .= '<strong>Endocrine: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_endocrine']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_skin'] != '') {
				$data['ros'] .= '<strong>Skin: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_skin']);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo['ros_wcc'] != '') {
				$data['ros'] .= '<strong>Well Child Check: </strong>';
				$data['ros'] .= nl2br($rosInfo['ros_wcc']);
				$data['ros'] .= '<br /><br />';
			}
			$data['ros'] .= '</p>';
		} else {
			$data['ros'] = '';
		}
		$ohInfo = $this->encounters_model->getOtherHistory($eid)->row_array();
		if ($ohInfo) {
			$data['oh'] = '<br><h4>Other Pertinent History:</h4><p class="view">';
			if ($ohInfo['oh_pmh'] != '') {
				$data['oh'] .= '<strong>Past Medical History: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_pmh']);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo['oh_psh'] != '') {
				$data['oh'] .= '<strong>Past Surgical History: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_psh']);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo['oh_fh'] != '') {
				$data['oh'] .= '<strong>Family History: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_fh']);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo['oh_sh'] != '') {
				$data['oh'] .= '<strong>Social History: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_sh']);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo['oh_etoh'] != '') {
				$data['oh'] .= '<strong>Alcohol Use: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_etoh']);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo['oh_tobacco'] != '') {
				$data['oh'] .= '<strong>Tobacco Use: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_tobacco']);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo['oh_drugs'] != '') {
				$data['oh'] .= '<strong>Illicit Drug Use: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_drugs']);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo['oh_employment'] != '') {
				$data['oh'] .= '<strong>Employment: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_employment']);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo['oh_meds'] != '') {
				$data['oh'] .= '<strong>Medications: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_meds']);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo['oh_supplements'] != '') {
				$data['oh'] .= '<strong>Supplements: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_supplements']);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo['oh_allergies'] != '') {
				$data['oh'] .= '<strong>Allergies: </strong>';
				$data['oh'] .= nl2br($ohInfo['oh_allergies']);
				$data['oh'] .= '<br /><br />';
			}
			$data['oh'] .= '</p>';
		} else {
			$data['oh'] = '';
		}	
		$vitalsInfo = $this->encounters_model->getVitals($eid)->row_array();
		if ($vitalsInfo) {
			$data['vitals'] = '<br><h4>Vital Signs:</h4><p class="view">';
			if ($vitalsInfo['weight'] != '') {
				$data['vitals'] .= '<strong>Weight: </strong>';
				$data['vitals'] .= $vitalsInfo['weight'] . ' ' . $practiceInfo['weight_unit'] . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo['height'] != '') {
				$data['vitals'] .= '<strong>Height: </strong>';
				$data['vitals'] .= $vitalsInfo['height'] . ' ' . $practiceInfo['height_unit'] . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo['headcircumference'] != '') {
				$data['vitals'] .= '<strong>Head Circumference: </strong>';
				$data['vitals'] .= $vitalsInfo['headcircumference'] . ' ' . $practiceInfo['hc_unit'] . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo['BMI'] != '') {
				$data['vitals'] .= '<strong>Body Mass Index: </strong>';
				$data['vitals'] .= $vitalsInfo['BMI'] . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo['temp'] != '') {
				$data['vitals'] .= '<strong>Temperature: </strong>';
				$data['vitals'] .= $vitalsInfo['temp'] . ' ' . $practiceInfo['temp_unit'] . ', ' . $vitalsInfo['temp_method'] . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo['bp_systolic'] != '' && $vitalsInfo['bp_diastolic'] != '') {
				$data['vitals'] .= '<strong>Blood Pressure: </strong>';
				$data['vitals'] .= $vitalsInfo['bp_systolic'] . '/' . $vitalsInfo['bp_diastolic'] . ', ' . $vitalsInfo['bp_position'] . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo['pulse'] != '') {
				$data['vitals'] .= '<strong>Pulse: </strong>';
				$data['vitals'] .= $vitalsInfo['pulse'] . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo['respirations'] != '') {
				$data['vitals'] .= '<strong>Respirations: </strong>';
				$data['vitals'] .= $vitalsInfo['respirations'] . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo['o2_sat'] != '') {
				$data['vitals'] .= '<strong>Oxygen Saturations: </strong>';
				$data['vitals'] .= $vitalsInfo['o2_sat'] . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo['vitals_other'] != '') {
				$data['vitals'] .= '<strong>Notes: </strong>';
				$data['vitals'] .= nl2br($vitalsInfo['vitals_other']) . '<br>';
			}
			$data['vitals'] .= '</p>';
		} else {
			$data['vitals'] = '';
		}	
		$peInfo = $this->encounters_model->getPE($eid)->row_array();
		if ($peInfo) {
			$data['pe'] = '<br><h4>Physical Exam:</h4><p class="view">';
			if ($peInfo['pe_gen1'] != '') {
				$data['pe'] .= '<strong>Constitutional: </strong>';
				$data['pe'] .= nl2br($peInfo['pe_gen1']);
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_eye1'] != '' || $peInfo['pe_eye2'] != '' || $peInfo['pe_eye3'] != '') {
				$data['pe'] .= '<strong>Eye:</strong>';
				if($peInfo['pe_eye1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_eye1']);
				}
				if($peInfo['pe_eye2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_eye2']);
				}
				if($peInfo['pe_eye3'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_eye3']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_ent1'] != '' || $peInfo['pe_ent2'] != '' || $peInfo['pe_ent3'] != '' || $peInfo['pe_ent4'] != '' || $peInfo['pe_ent5'] != '' || $peInfo['pe_ent6'] != '') {
				$data['pe'] .= '<strong>Ears, Nose, Throat:</strong>';
				if($peInfo['pe_ent1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ent1']);
				}
				if($peInfo['pe_ent2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ent2']);
				}
				if($peInfo['pe_ent3'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ent3']);
				}
				if($peInfo['pe_ent4'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ent4']);
				}
				if($peInfo['pe_ent5'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ent5']);
				}
				if($peInfo['pe_ent6'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ent6']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_neck1'] != '' || $peInfo['pe_neck2'] != '') {
				$data['pe'] .= '<strong>Neck:</strong>';
				if($peInfo['pe_neck1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_neck1']);
				}
				if($peInfo['pe_neck2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_neck2']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_resp1'] != '' || $peInfo['pe_resp2'] != '' || $peInfo['pe_resp3'] != '' || $peInfo['pe_resp4'] != '') {
				$data['pe'] .= '<strong>Respiratory:</strong>';
				if($peInfo['pe_resp1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_resp1']);
				}
				if($peInfo['pe_resp2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_resp2']);
				}
				if($peInfo['pe_resp3'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_resp3']);
				}
				if($peInfo['pe_resp4'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_resp4']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_cv1'] != '' || $peInfo['pe_cv2'] != '' || $peInfo['pe_cv3'] != '' || $peInfo['pe_cv4'] != '' || $peInfo['pe_cv5'] != '' || $peInfo['pe_cv6'] != '') {
				$data['pe'] .= '<strong>Cardiovascular:</strong>';
				if($peInfo['pe_cv1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_cv1']);
				}
				if($peInfo['pe_cv2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_cv2']);
				}
				if($peInfo['pe_cv3'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_cv3']);
				}
				if($peInfo['pe_cv4'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_cv4']);
				}
				if($peInfo['pe_cv5'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_cv5']);
				}
				if($peInfo['pe_cv6'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_cv6']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_ch1'] != '' || $peInfo['pe_ch2'] != '') {
				$data['pe'] .= '<strong>Chest:</strong>';
				if($peInfo['pe_ch1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ch1']);
				}
				if($peInfo['pe_ch2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ch2']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_gi1'] != '' || $peInfo['pe_gi2'] != '' || $peInfo['pe_gi3'] != '' || $peInfo['pe_gi4'] != '') {
				$data['pe'] .= '<strong>Gastrointestinal:</strong>';
				if($peInfo['pe_gi1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gi1']);
				}
				if($peInfo['pe_gi2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gi2']);
				}
				if($peInfo['pe_gi3'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gi3']);
				}
				if($peInfo['pe_gi4'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gi4']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_gu1'] != '' || $peInfo['pe_gu2'] != '' || $peInfo['pe_gu3'] != '' || $peInfo['pe_gu4'] != '' || $peInfo['pe_gu5'] != '' || $peInfo['pe_gu6'] != '' || $peInfo['pe_gu7'] != '' || $peInfo['pe_gu8'] != '' || $peInfo['pe_gu9'] != '') {
				$data['pe'] .= '<strong>Genitourinary:</strong>';
				if($peInfo['pe_gu1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gu1']);
				}
				if($peInfo['pe_gu2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gu2']);
				}
				if($peInfo['pe_gu3'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gu3']);
				}
				if($peInfo['pe_gu4'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gu4']);
				}
				if($peInfo['pe_gu5'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gu5']);
				}
				if($peInfo['pe_gu6'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gu6']);
				}
				if($peInfo['pe_gu7'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gu7']);
				}
				if($peInfo['pe_gu8'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gu8']);
				}
				if($peInfo['pe_gu9'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_gu9']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_lymph1'] != '' || $peInfo['pe_lymph2'] != '' || $peInfo['pe_lymph3'] != '') {
				$data['pe'] .= '<strong>Lymphatic:</strong>';
				if($peInfo['pe_lymph1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_lymph1']);
				}
				if($peInfo['pe_lymph2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_lymph2']);
				}
				if($peInfo['pe_lymph3'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_lymph3']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_ms1'] != '' || $peInfo['pe_ms2'] != '' || $peInfo['pe_ms3'] != '' || $peInfo['pe_ms4'] != '' || $peInfo['pe_ms5'] != '' || $peInfo['pe_ms6'] != '' || $peInfo['pe_ms7'] != '' || $peInfo['pe_ms8'] != '' || $peInfo['pe_ms9'] != '' || $peInfo['pe_ms10'] != '' || $peInfo['pe_ms11'] != '' || $peInfo['pe_ms12'] != '') {
				$data['pe'] .= '<strong>Musculoskeletal:</strong>';
				if($peInfo['pe_ms1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms1']);
				}
				if($peInfo['pe_ms2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms2']);
				}
				if($peInfo['pe_ms3'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms3']);
				}
				if($peInfo['pe_ms4'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms4']);
				}
				if($peInfo['pe_ms5'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms5']);
				}
				if($peInfo['pe_ms6'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms6']);
				}
				if($peInfo['pe_ms7'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms7']);
				}
				if($peInfo['pe_ms8'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms8']);
				}
				if($peInfo['pe_ms9'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms9']);
				}
				if($peInfo['pe_ms10'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms10']);
				}
				if($peInfo['pe_ms11'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms11']);
				}
				if($peInfo['pe_ms12'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_ms12']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_skin1'] != '' || $peInfo['pe_skin2'] != '') {
				$data['pe'] .= '<strong>Skin:</strong>';
				if($peInfo['pe_skin1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_skin1']);
				}
				if($peInfo['pe_skin2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_skin2']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_neuro1'] != '' || $peInfo['pe_neuro2'] != '' || $peInfo['pe_neuro3'] != '') {
				$data['pe'] .= '<strong>Neurologic:</strong>';
				if($peInfo['pe_neuro1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_neuro1']);
				}
				if($peInfo['pe_neuro2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_neuro2']);
				}
				if($peInfo['pe_neuro3'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_neuro3']);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo['pe_psych1'] != '' || $peInfo['pe_psych2'] != '' || $peInfo['pe_psych3'] != '' || $peInfo['pe_psych4'] != '') {
				$data['pe'] .= '<strong>Psychiatric:</strong>';
				if($peInfo['pe_psych1'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_psych1']);
				}
				if($peInfo['pe_psych2'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_psych2']);
				}
				if($peInfo['pe_psych3'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_psych3']);
				}
				if($peInfo['pe_psych4'] != '') {
					$data['pe'] .= ' ' . nl2br($peInfo['pe_psych4']);
				}
				$data['pe'] .= '<br /><br />';
			}
			$data['pe'] .= '</p>';
		} else {
			$data['pe'] = '';
		}	
		$labsInfo = $this->encounters_model->getLabs($eid)->row_array();
		if ($labsInfo) {
			$data['labs'] = '<br><h4>Laboratory Testing:</h4><p class="view">';
			if ($labsInfo['labs_ua_urobili'] != '' || $labsInfo['labs_ua_bilirubin'] != '' || $labsInfo['labs_ua_ketones'] != '' || $labsInfo->labs_ua_glucose != '' || $labsInfo->labs_ua_protein != '' || $labsInfo->labs_ua_nitrites != '' || $labsInfo->labs_ua_leukocytes != '' || $labsInfo->labs_ua_blood != '' || $labsInfo->labs_ua_ph != '' || $labsInfo->labs_ua_spgr != '' || $labsInfo->labs_ua_color != '' || $labsInfo->labs_ua_clarity != ''){
				$data['labs'] .= '<strong>Dipstick Urinalysis:</strong><br /><table>';
				if($labsInfo['labs_ua_urobili'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Urobilinogen:</th><td align=\"left\">' . $labsInfo['labs_ua_urobili'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_bilirubin'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Bilirubin:</th><td align=\"left\">' . $labsInfo['labs_ua_bilirubin'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_ketones'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Ketones:</th><td align=\"left\">' . $labsInfo['labs_ua_ketones'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_glucose'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Glucose:</th><td align=\"left\">' . $labsInfo['labs_ua_glucose'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_protein'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Protein:</th><td align=\"left\">' . $labsInfo['labs_ua_protein'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_nitrites'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Nitrites:</th><td align=\"left\">' . $labsInfo['labs_ua_nitrites'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_leukocytes'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Leukocytes:</th><td align=\"left\">' . $labsInfo['labs_ua_leukocytes'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_blood'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Blood:</th><td align=\"left\">' . $labsInfo['labs_ua_blood'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_ph'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">pH:</th><td align=\"left\">' . $labsInfo['labs_ua_ph'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_spgr'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Specific gravity:</th><td align=\"left\">' . $labsInfo['labs_ua_spgr'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_color'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Color:</th><td align=\"left\">' . $labsInfo['labs_ua_color'] . '</td></tr>';
				}
				if($labsInfo['labs_ua_clarity'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Clarity:</th><td align=\"left\">' . $labsInfo['labs_ua_clarity'] . '</td></tr>';
				}
				$data['labs'] .= '</table>';
			}
			if ($labsInfo['labs_upt'] != '') {
				$data['labs'] .= '<strong>Urine HcG: </strong>';
				$data['labs'] .= $labsInfo['labs_upt'];
				$data['labs'] .= '<br /><br />';
			} 
			if ($labsInfo['labs_strep'] != '') {
				$data['labs'] .= '<strong>Rapid Strep: </strong>';
				$data['labs'] .= $labsInfo['labs_strep'];
				$data['labs'] .= '<br /><br />';
			} 
			if ($labsInfo['labs_mono'] != '') {
				$data['labs'] .= '<strong>Mono Spot: </strong>';
				$data['labs'] .= $labsInfo['labs_mono'];
				$data['labs'] .= '<br>';
			}
			if ($labsInfo['labs_flu'] != '') {
				$data['labs'] .= '<strong>Rapid Influenza: </strong>';
				$data['labs'] .= $labsInfo['labs_flu'];
				$data['labs'] .= '<br /><br />';
			}
			if ($labsInfo['labs_microscope'] != '') {
				$data['labs'] .= '<strong>Micrscopy: </strong>';
				$data['labs'] .= nl2br($labsInfo['labs_microscope']);
				$data['labs'] .= '<br /><br />';
			}
			if ($labsInfo['labs_glucose'] != '') {
				$data['labs'] .= '<strong>Fingerstick Glucose: </strong>';
				$data['labs'] .= $labsInfo['labs_glucose'];
				$data['labs'] .= '<br /><br />';
			}
			if ($labsInfo['labs_other'] != '') {
				$data['labs'] .= '<strong>Other: </strong>';
				$data['labs'] .= nl2br($labsInfo['labs_other']);
				$data['labs'] .= '<br /><br />';
			}
			$data['labs'] .= '</p>';
		} else {
			$data['labs'] = '';
		}		
		$procedureInfo = $this->encounters_model->getProcedure($eid)->row_array();
		if ($procedureInfo) {
			$data['procedure'] = '<br><h4>Procedures:</h4><p class="view">';
			if ($procedureInfo['proc_type'] != '') {
				$data['procedure'] .= '<strong>Procedure: </strong>';
				$data['procedure'] .= nl2br($procedureInfo['proc_type']);
				$data['procedure'] .= '<br /><br />';
			}
			if ($procedureInfo['proc_description'] != '') {
				$data['procedure'] .= '<strong>Description of Procedure: </strong>';
				$data['procedure'] .= nl2br($procedureInfo['proc_description']);
				$data['procedure'] .= '<br /><br />';
			}
			if ($procedureInfo['proc_complications'] != '') {
				$data['procedure'] .= '<strong>Complications: </strong>';
				$data['procedure'] .= nl2br($procedureInfo['proc_complications']);
				$data['procedure'] .= '<br /><br />';
			}
			if ($procedureInfo['proc_ebl'] != '') {
				$data['procedure'] .= '<strong>Estimated Blood Loss: </strong>';
				$data['procedure'] .= nl2br($procedureInfo['proc_ebl']);
				$data['procedure'] .= '<br /><br />';
			}
			$data['procedure'] .= '</p>';
		} else {
			$data['procedure'] = '';
		}
		$assessmentInfo = $this->encounters_model->getAssessment($eid)->row_array();
		if ($assessmentInfo) {
			$data['assessment'] = '<br><h4>Assessment:</h4><p class="view">';
			if ($assessmentInfo['assessment_1'] != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo['assessment_1'] . '</strong><br />';
				if ($assessmentInfo['assessment_2'] == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo['assessment_2'] != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo['assessment_2'] . '</strong><br />';
				if ($assessmentInfo['assessment_3'] == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo['assessment_3'] != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo['assessment_3'] . '</strong><br />';
				if ($assessmentInfo['assessment_4'] == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo['assessment_4'] != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo['assessment_4'] . '</strong><br />';
				if ($assessmentInfo['assessment_5'] == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo['assessment_5'] != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo['assessment_5'] . '</strong><br />';
				if ($assessmentInfo['assessment_6'] == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo['assessment_6'] != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo['assessment_6'] . '</strong><br />';
				if ($assessmentInfo['assessment_7'] == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo['assessment_7'] != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo['assessment_7'] . '</strong><br />';
				if ($assessmentInfo['assessment_8'] == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo['assessment_8'] != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo['assessment_8'] . '</strong><br /><br />';
			}
			if ($assessmentInfo['assessment_other'] != '') {
				$data['assessment'] .= '<strong>Additional Diagnoses: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo['assessment_other']);
				$data['assessment'] .= '<br /><br />';
			}
			if ($assessmentInfo['assessment_ddx'] != '') {
				$data['assessment'] .= '<strong>Differential Diagnoses Considered: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo['assessment_ddx']);
				$data['assessment'] .= '<br /><br />';
			}
			if ($assessmentInfo['assessment_notes'] != '') {
				$data['assessment'] .= '<strong>Assessment Discussion: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo['assessment_notes']);
				$data['assessment'] .= '<br /><br />';
			}
			$data['assessment'] .= '</p>';
		} else {
			$data['assessment'] = '';
		}
		$ordersInfo = $this->encounters_model->getOrders($eid)->row_array();
		if ($ordersInfo) {
			$data['orders'] = '<br><h4>Orders:</h4><p class="view">';
			$ordersInfo_labs_query = $this->encounters_model->getOrders_labs($eid);
			if ($ordersInfo_labs_query->num_rows() > 0) {
				$data['orders'] .= '<strong>Labs: </strong>';
				foreach ($ordersInfo_labs_query->result_array() as $ordersInfo_labs_result) {
					$text1 = nl2br($ordersInfo_labs_result['orders_labs']);
					$address_id1 = $ordersInfo_labs_result['address_id'];
					$this->db->where('address_id', $address_id1);
					$query_address1 = $this->db->get('addressbook');
					$address_row1 = $query_address1->row_array();
					$data['orders'] .= 'Orders sent to ' . $address_row1['displayname'] . ': '. $text1 . '<br />';
				}
			}
			$ordersInfo_rad_query = $this->encounters_model->getOrders_radiology($eid);
			if ($ordersInfo_rad_query->num_rows() > 0) {
				$data['orders'] .= '<strong>Imaging: </strong>';
				foreach ($ordersInfo_rad_query->result_array() as $ordersInfo_rad_result) {
					$text2 = nl2br($ordersInfo_rad_result['orders_radiology']);
					$address_id2 = $ordersInfo_rad_result['address_id'];
					$this->db->where('address_id', $address_id2);
					$query_address2 = $this->db->get('addressbook');
					$address_row2 = $query_address2->row_array();
					$data['orders'] .= 'Orders sent to ' . $address_row2['displayname'] . ': '. $text2 . '<br />';
				}
			}
			$ordersInfo_cp_query = $this->encounters_model->getOrders_cp($eid);
			if ($ordersInfo_cp_query->num_rows() > 0) {
				$data['orders'] .= '<strong>Cardiopulmonary: </strong>';
				foreach ($ordersInfo_cp_query->result_array() as $ordersInfo_cp_result) {
					$text3 = nl2br($ordersInfo_cp_result['orders_cp']);
					$address_id3 = $ordersInfo_cp_result['address_id'];
					$this->db->where('address_id', $address_id3);
					$query_address3 = $this->db->get('addressbook');
					$address_row3 = $query_address3->row_array();
					$data['orders'] .= 'Orders sent to ' . $address_row3['displayname'] . ': '. $text3 . '<br />';
				}
			}
			$ordersInfo_ref_query = $this->encounters_model->getOrders_ref($eid);
			if ($ordersInfo_ref_query->num_rows() > 0) {
				$data['orders'] .= '<strong>Referrals: </strong>';
				foreach ($ordersInfo_ref_query->result_array() as $ordersInfo_ref_result) {
					$text4 = nl2br($ordersInfo_ref_result['orders_referrals']);
					$address_id4 = $ordersInfo_ref_result['address_id'];
					$this->db->where('address_id', $address_id4);
					$query_address4 = $this->db->get('addressbook');
					$address_row4 = $query_address4->row_array();
					$data['orders'] .= 'Orders sent to ' . $address_row4['displayname'] . ': '. $text4 . '<br />';
				}
			}
			$data['orders'] .= '</p>';
		} else {
			$data['orders'] = '';
		}		
		$rxInfo = $this->encounters_model->getRX($eid)->row_array();
		if ($rxInfo) {
			$data['rx'] = '<br><h4>Prescriptions and Immunizations:</h4><p class="view">';
			if ($rxInfo['rx_rx'] != '') {
				$data['rx'] .= '<strong>Prescriptions Given: </strong>';
				$data['rx'] .= nl2br($rxInfo['rx_rx']);
				$data['rx'] .= '<br /><br />';
			}
			if ($rxInfo['rx_supplements'] != '') {
				$data['rx'] .= '<strong>Supplements Recommended: </strong>';
				$data['rx'] .= nl2br($rxInfo['rx_supplements']);
				$data['rx'] .= '<br /><br />';
			}
			if ($rxInfo['rx_immunizations'] != '') {
				$data['rx'] .= '<strong>Immunizations Given: </strong>';
				$data['rx'] .= 'CDC Vaccine Information Sheets given for each immunization and consent obtained.<br />';
				$data['rx'] .= nl2br($rxInfo['rx_immunizations']);
				$data['rx'] .= '<br /><br />';
			}
			$data['rx'] .= '</p>';
		} else {
			$data['rx'] = '';
		}		
		$planInfo = $this->encounters_model->getPlan($eid)->row_array();
		if ($planInfo) {
			$data['plan'] = '<br><h4>Plan:</h4><p class="view">';
			if ($planInfo['plan'] != '') {
				$data['plan'] .= '<strong>Recommendations: </strong>';
				$data['plan'] .= nl2br($planInfo['plan']);
				$data['plan'] .= '<br /><br />';
			}
			if ($planInfo['followup'] != '') {
				$data['plan'] .= '<strong>Followup: </strong>';
				$data['plan'] .= nl2br($planInfo['followup']);
				$data['plan'] .= '<br /><br />';
			}
			if ($planInfo['duration'] != '') {
				$data['plan'] .= 'Counseling and face-to-face time consists of more than 50 percent of the visit.  Total face-to-face time is ';
				$data['plan'] .= $planInfo['duration'] . ' minutes.';
				$data['plan'] .= '<br /><br />';
			}
			$data['plan'] .= '</p>';
		} else {
			$data['plan'] = '';
		}		
		$billing_query = $this->encounters_model->getBillingCore($eid);
		if ($billing_query->num_rows() > 0) {
			$data['billing'] = '<p class="view">';
			$billing_count = 0;
			foreach ($billing_query->result_array() as $billing_row) {
				if ($billing_count > 0) {
					$data['billing'] .= ',' . $billing_row['cpt'];
				} else {
					$data['billing'] .= '<strong>CPT Codes: </strong>';
					$data['billing'] .= $billing_row['cpt'];
				}
				$billing_count++;
			}
			if ($this->encounters_model->getBilling($eid)->num_rows() > 0) {
				$billingInfo = $this->encounters_model->getBilling($eid)->row_array();
				if ($billingInfo['bill_complex'] != '') {
					$data['billing'] .= '<br><strong>Medical Complexity: </strong>';
					$data['billing'] .= nl2br($billingInfo['bill_complex']);
					$data['billing'] .= '<br /><br />';
				}
			}
			$data['billing'] .= '</p>';
		} else {
			$data['billing'] = '';
		}
		$data['title'] = 'Encounter Details';
		return $data;
	}
	
	function modal_view($eid)
	{
		$data = $this->modal_view_text($eid);
		$this->load->view('auth/pages/provider/chart/encounters/modal_view', $data);
	}
	
	function modal_view2($eid)
	{
		$data = $this->modal_view_text($eid);
		$this->load->view('auth/pages/provider/chart/encounters/modal_view_insert', $data);
	}

	// --------------------------------------------------------------------

	function get_encounter()
	{
		$eid = $this->session->userdata('eid');
		$this->db->where('eid', $eid);
		$query = $this->db->get('encounters');
		$result = $query->row_array();
		$date = strtotime($result['encounter_DOS']);
		$datestring = "%m/%d/%Y";
		$timestring = "%h:%i %A";
		$data['encounter_date'] = mdate($datestring, $date);
		$data['encounter_time'] = mdate($timestring, $date);
		$data['encounter_type'] = $result['encounter_type'];
		$data['encounter_location'] = $result['encounter_location'];
		$data['encounter_cc'] = $result['encounter_cc'];
		$data['encounter_condition'] = $result['encounter_condition'];
		$data['encounter_condition_work'] = $result['encounter_condition_work'];
		$data['encounter_condition_auto'] = $result['encounter_condition_auto'];
		$data['encounter_condition_auto_state'] = $result['encounter_condition_auto_state'];
		$data['encounter_condition_other'] = $result['encounter_condition_other'];
		$data['encounter_role'] = $result['encounter_role'];
		$data['referring_provider'] = $result['referring_provider'];
		$data['referring_provider_npi'] = $result['referring_provider_npi'];
		$data['eid'] = $eid;
		echo json_encode($data);
	}

	function edit_encounter()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE)
		{
			redirect('start');
		}
		$date = strtotime($this->input->post('encounter_date') . " " . $this->input->post('encounter_time'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$encounter_DOS = mdate($datestring, $date);	
		$e1 = $this->input->post('encounter_location');
		$e2 = stristr($e1, '(');
		$e3 = str_replace('(', '', $e2);
		$encounter_location = str_replace(')', '', $e3);	
		$data = array(
			'encounter_DOS' => $encounter_DOS,
			'encounter_location' => $this->input->post('encounter_location'),
			'encounter_cc' => $this->input->post('encounter_cc'),
			'encounter_condition' => $this->input->post('encounter_condition'),
			'encounter_condition_work' => $this->input->post('encounter_condition_work'),
			'encounter_condition_auto' => $this->input->post('encounter_condition_auto'),
			'encounter_condition_auto_state' => $this->input->post('encounter_condition_auto_state'),
			'encounter_condition_other' => $this->input->post('encounter_condition_other'),
			'encounter_role' => $this->input->post('encounter_role'),
			'referring_provider' => $this->input->post('referring_provider'),
			'practice_id' => $this->session->userdata('practice_id'),
			'referring_provider_npi' => $this->input->post('referring_provider_npi')
		);
		$this->encounters_model->updateEncounter($eid, $data);
		$this->audit_model->update();
		echo "Update successful!";
	}

	// --------------------------------------------------------------------

	function get_hpi()
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getHPI($eid);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			echo $row['hpi'];
		} else {
			echo '';
		}
	}
	
	function hpi_save()
	{		
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$count = $this->encounters_model->getHPI($eid)->num_rows();
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => $encounter_provider,
			'hpi' => $this->input->post('hpi')
		);						
		if ($count > 0) {		
			$this->encounters_model->updateHPI($eid, $data);
			$this->audit_model->update();
			$result = 'History of Present Illness Updated!';
		} else {
			$this->encounters_model->addHPI($data);
			$this->audit_model->add();
			$result = 'History of Present Illness Added!';
		}
		echo $result;
	}

	// --------------------------------------------------------------------

	function check_ros()
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getROS($eid);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			if ($row['ros_gen']) {
				$data['gen'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['gen'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_eye']) {
				$data['eye'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['eye'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_ent']) {
				$data['ent'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['ent'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_resp']) {
				$data['resp'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['resp'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_cv']) {
				$data['cv'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['cv'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_gi']) {
				$data['gi'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height=30" width="30">';
			} else {
				$data['gi'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_gu']) {
				$data['gu'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['gu'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_mus']) {
				$data['mus'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['mus'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_neuro']) {
				$data['neuro'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['neuro'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_psych']) {
				$data['psych'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['psych'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_heme']) {
				$data['heme'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['heme'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_endocrine']) {
				$data['endocrine'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['endocrine'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_skin']) {
				$data['skin'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['skin'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_wcc']) {
				$data['wcc'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['wcc'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			$data['message'] = 'Review of Systems Updated!';
		} else {
			$data['gen'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['eye'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['ent'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['resp'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['cv'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['gi'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['gu'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['mus'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['neuro'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['psych'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['heme'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['endocrine'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['skin'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['wcc'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['message'] = 'Review of Systems Unchanged!';
		}
		echo json_encode($data);
	}

	function get_ros()
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getROS($eid);
		$data = $query->row_array();
		echo json_encode($data);
	}
	
	function get_ros_templates()
	{
		$user_id = $this->session->userdata('user_id');
		$gender = $this->session->userdata('gender');
		$age = $this->session->userdata('agealldays');
		if ($gender == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$this->db->where('user_id', $user_id);
		$this->db->or_where('user_id', '0');
		$this->db->where('sex', $sex);
		$this->db->where('category', 'ros');
		$this->db->where('default', "default");
		$result = $this->db->get('templates')->result_array();
		foreach($result as $row) {
			$id = $row['group'];
			$data[$id] = unserialize($row['array']);
		}
		if ($age <= 60.88) {
			$data['ros_wccage'] = $data['ros_wccage0m'];
			unset($data['ros_wccage0m']);
		}
		if ($age > 60.88 && $age <= 121.76) {
			$data['ros_wccage'] = $data['ros_wccage2m'];
			unset($data['ros_wccage2m']);
		}
		if ($age > 121.76 && $age <= 182.64) {
			$data['ros_wccage'] = $data['ros_wccage4m'];
			unset($data['ros_wccage4m']);
		}
		if ($age > 182.64 && $age <= 273.96) {
			$data['ros_wccage'] = $data['ros_wccage6m'];
			unset($data['ros_wccage6m']);
		}
		if ($age > 273.96 && $age <= 365.24) {
			$data['ros_wccage'] = $data['ros_wccage9m'];
			unset($data['ros_wccage9m']);
		}
		if ($age > 365.24 && $age <= 456.6) {
			$data['ros_wccage'] = $data['ros_wccage12m'];
			unset($data['ros_wccage12m']);
		}
		if ($age > 456.6 && $age <= 547.92) {
			$data['ros_wccage'] = $data['ros_wccage15m'];
			unset($data['ros_wccage15m']);
		}
		if ($age > 547.92 && $age <= 730.48) {
			$data['ros_wccage'] = $data['ros_wccage18m'];
			unset($data['ros_wccage18m']);
		}
		if ($age > 730.48 && $age <= 1095.75) {
			$data['ros_wccage'] = $data['ros_wccage2'];
			unset($data['ros_wccage2']);
		}
		if ($age > 1095.75 && $age <= 1461) {
			$data['ros_wccage'] = $data['ros_wccage3'];
			unset($data['ros_wccage3']);
		}
		if ($age > 1461 && $age <= 1826.25) {
			$data['ros_wccage'] = $data['ros_wccage4'];
			unset($data['ros_wccage4']);
		}
		if ($age > 1826.25 && $age <= 2191.44) {
			$data['ros_wccage'] = $data['ros_wccage5'];
			unset($data['ros_wccage5']);
		}
		echo json_encode($data);
	}
	
	function get_ros_templates_list($parent_id)
	{
		$user_id = $this->session->userdata('user_id');
		$gender = $this->session->userdata('gender');
		if ($gender == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$this->db->where('user_id', $user_id);
		$this->db->or_where('user_id', '0');
		$this->db->where('sex', $sex);
		$this->db->where('group', $parent_id);
		$result = $this->db->get('templates')->result_array();
		$data['select_id'] = $parent_id . "_template";
		$data['options'] = array();
		foreach ($result as $row) {
			$id = $row['template_id'];
			$data['options'][$id] = $row['template_name'];
		}
		echo json_encode($data);
	}
	
	function get_ros_template($id)
	{
		$this->db->where('template_id', $id);
		$row = $this->db->get('templates')->row_array();
		$data = unserialize($row['array']);
		echo json_encode($data);
	}
	
	function tip_ros($item)
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getROS($eid);
		$count = $query->num_rows();
		if ($count > 0) {
			$data = $query->row_array();
			if ($data[$item] == '') {
				echo 'No entry for this item.';
			} else {
				echo nl2br($data[$item]);
			}
		} else {
			echo 'No entry for this item.';
		}
		exit(0);
	}

	function ros()
	{
		$eid = $this->session->userdata('eid');
		$gender = $this->session->userdata('gender');
		$data['encounter_id'] = $eid;
		$query = $this->encounters_model->getROS($eid);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			if ($row['ros_gen']) {
				$data['gen_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['gen_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_eye']) {
				$data['eye_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['eye_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_ent']) {
				$data['ent_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['ent_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_resp']) {
				$data['resp_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['resp_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_cv']) {
				$data['cv_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['cv_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_gi']) {
				$data['gi_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['gi_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_gu']) {
				$data['gu_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['gu_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_mus']) {
				$data['mus_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['mus_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_neuro']) {
				$data['neuro_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['neuro_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_psych']) {
				$data['psych_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['psych_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_heme']) {
				$data['heme_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['heme_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_endocrine']) {
				$data['endocrine_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['endocrine_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_skin']) {
				$data['skin_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['skin_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['ros_wcc']) {
				$data['wcc_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['wcc_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
		} else {
			$data['gen_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['eye_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['ent_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['resp_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['cv_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['gi_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['gu_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['mus_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['neuro_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['psych_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['heme_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['endocrine_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['skin_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['wcc_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}
		$age = $this->session->userdata('agealldays');
		if ($age <= 6574.32) {
			$data['wcc'] = '<tr><td><div id="button_ros_wcc_status" class="ros_tooltip">' . $data['wcc_status'] . '</div></td><td><input type="button" id="button_ros_wcc" value="Well Child Visit" class="ui-button ui-state-default ui-corner-all"></td></tr>';
		} else {
			$data['wcc'] = '';
		}	
		if ($gender == 'male') {	
			$this->load->view('auth/pages/provider/chart/encounters/ros_male', $data);
		} else {
			$this->load->view('auth/pages/provider/chart/encounters/ros_female', $data);
		}
	}

	function ros_save($item)
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$query = $this->encounters_model->getROS($eid);
		$count = $query->num_rows();
		if ($item == 'gen') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_gen' => $this->input->post('ros_gen')
			);
		}
		if ($item == 'eye') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_eye' => $this->input->post('ros_eye')
			);
		}
		if ($item == 'ent') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_ent' => $this->input->post('ros_ent')
			);
		}
		if ($item == 'resp') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_resp' => $this->input->post('ros_resp')
			);
		}
		if ($item == 'cv') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_cv' => $this->input->post('ros_cv')
			);
		}
		if ($item == 'gi') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_gi' => $this->input->post('ros_gi')
			);
		}
		if ($item == 'gu') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_gu' => $this->input->post('ros_gu')
			);
		}
		if ($item == 'mus') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_mus' => $this->input->post('ros_mus')
			);
		}
		if ($item == 'neuro') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_neuro' => $this->input->post('ros_neuro')
			);
		}
		if ($item == 'psych') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_psych' => $this->input->post('ros_psych')
			);
		}
		if ($item == 'heme') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_heme' => $this->input->post('ros_heme')
			);
		}
		if ($item == 'endocrine') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_endocrine' => $this->input->post('ros_endocrine')
			);
		}
		if ($item == 'skin') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_skin' => $this->input->post('ros_skin')
			);
		}
		if ($item == 'wcc') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'ros_wcc' => $this->input->post('ros_wcc')
			);
		}
		if ($count > 0) {		
			$this->encounters_model->updateROS($eid, $data);
			$this->audit_model->update();
			$result = 'Review of Systems Updated';
		} else {
			$this->encounters_model->addROS($data);
			$this->audit_model->add();
			$result = 'Review of Systems Added';
		}
		echo $result;
	}

	// --------------------------------------------------------------------

	function oh()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}
		
		$pid = $this->session->userdata('pid');
		$query = $this->encounters_model->getOtherHistory($eid);		
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			if ($row['oh_sh']) {
				$data['sh_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['sh_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_meds']) {
				$data['meds_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['meds_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_supplements']) {
				$data['supplements_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['supplements_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_allergies']) {
				$data['allergies_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['allergies_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_etoh']) {
				$data['etoh_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['etoh_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_tobacco']) {
				$data['tobacco_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['tobacco_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_drugs']) {
				$data['drugs_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['drugs_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_employment']) {
				$data['employment_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['employment_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
		} else {
			$data['sh_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['meds_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['supplements_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['allergies_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['etoh_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['tobacco_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['drugs_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['employment_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}

		$age = $this->session->userdata('agealldays');
		if ($age > 730.48 && $age <= 6574.32) {
			$data['childcare'] = 'Child Care:<br><input type="text" name="sh8" id="sh8" style="width:250px" class="text ui-widget-content ui-corner-all"/><br><br>';
		} else {
			$data['childcare'] = '';
		}
		
		$this->load->view('auth/pages/provider/chart/encounters/oh', $data);
	}

	function check_oh()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		$query = $this->encounters_model->getOtherHistory($eid);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			if ($row['oh_sh']) {
				$data['sh_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['sh_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_meds']) {
				$data['meds_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['meds_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_supplements']) {
				$data['supplements_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['supplements_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_allergies']) {
				$data['allergies_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['allergies_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_etoh']) {
				$data['etoh_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['etoh_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_tobacco']) {
				$data['tobacco_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['tobacco_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_drugs']) {
				$data['drugs_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['drugs_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['oh_employment']) {
				$data['employment_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['employment_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			$data['message'] = 'Other History Updated!';
		} else {
			$data['sh_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['meds_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['supplements_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['allergies_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['etoh_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['tobacco_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['drugs_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['employment_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['message'] = 'Other History Unchanged!';
		}
		echo json_encode($data);
	}
	
	function get_oh()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		$query = $this->encounters_model->getOtherHistory($eid);
		$data = $query->row_array();
		echo json_encode($data);
	}

	function tip_oh($item)
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getOtherHistory($eid);
		$count = $query->num_rows();
		if ($count > 0) {
			$data = $query->row_array();
			if ($data[$item] == '') {
				echo 'No entry for this item.';
			} else {
				echo nl2br($data[$item]);
			}
		} else {
			echo 'No entry for this item.';
		}
		exit(0);
	}

	function copy_oh($item)
	{
		$pid = $this->session->userdata('pid');
		$eid = $this->session->userdata('eid');	
		if ($item == 'oh') {
			$data['callback'] = 'No previous encounter!';
			$this->db->where('pid', $pid);
			$this->db->where('eid !=', $eid);
			$this->db->where('oh_pmh !=', '');
			$this->db->order_by('eid', 'desc');
			$this->db->limit(1);
			$query = $this->db->get('other_history');
			if ($query->num_rows() > 0) {
				$result = $query->row_array();
				$data['oh_pmh'] = $result['oh_pmh'];
				$data['callback'] = 'Items copied from last encounter!';
			}
			$this->db->where('pid', $pid);
			$this->db->where('eid !=', $eid);
			$this->db->where('oh_psh !=', '');
			$this->db->order_by('eid', 'desc');
			$this->db->limit(1);
			$query1 = $this->db->get('other_history');
			if ($query1->num_rows() > 0) {
				$result1 = $query1->row_array();
				$data['oh_psh'] = $result1['oh_psh'];
				$data['callback'] = 'Items copied from last encounter!';
			}
			$this->db->where('pid', $pid);
			$this->db->where('eid !=', $eid);
			$this->db->where('oh_fh !=', '');
			$this->db->order_by('eid', 'desc');
			$this->db->limit(1);
			$query2 = $this->db->get('other_history');
			if ($query2->num_rows() > 0) {
				$result2 = $query2->row_array();
				$data['oh_fh'] = $result2['oh_fh'];
				$data['callback'] = 'Items copied from last encounter!';
			}
			echo json_encode($data);
			exit(0);
		}
		if ($item == 'sh') {
			$data['callback'] = 'No previous encounter!';
			$this->db->where('pid', $pid);
			$this->db->where('eid !=', $eid);
			$this->db->where('oh_sh !=', '');
			$this->db->order_by('eid', 'desc');
			$this->db->limit(1);
			$query3 = $this->db->get('other_history');
			if ($query3->num_rows() > 0) {
				$result3 = $query3->row_array();
				$data['oh_sh'] = $result3['oh_sh'];
				$data['callback'] = 'Items copied from last encounter!';
			}
			echo json_encode($data);
			exit(0);
		}
		if ($item == 'etoh') {
			$data['callback'] = 'No previous encounter!';
			$this->db->where('pid', $pid);
			$this->db->where('eid !=', $eid);
			$this->db->where('oh_etoh !=', '');
			$this->db->order_by('eid', 'desc');
			$this->db->limit(1);
			$query4 = $this->db->get('other_history');
			if ($query4->num_rows() > 0) {
				$result4 = $query4->row_array();
				$data['oh_etoh'] = $result4['oh_etoh'];
				$data['callback'] = 'Items copied from last encounter!';
			}
			echo json_encode($data);
			exit(0);
		}
		if ($item == 'tobacco') {
			$data['callback'] = 'No previous encounter!';
			$this->db->where('pid', $pid);
			$this->db->where('eid !=', $eid);
			$this->db->where('oh_tobacco !=', '');
			$this->db->order_by('eid', 'desc');
			$this->db->limit(1);
			$query5 = $this->db->get('other_history');
			if ($query5->num_rows() > 0) {
				$result5 = $query5->row_array();
				$data['oh_tobacco'] = $result5['oh_tobacco'];
				$data['callback'] = 'Items copied from last encounter!';
			}
			echo json_encode($data);
			exit(0);
		}
		if ($item == 'drugs') {
			$data['callback'] = 'No previous encounter!';
			$this->db->where('pid', $pid);
			$this->db->where('eid !=', $eid);
			$this->db->where('oh_drugs !=', '');
			$this->db->order_by('eid', 'desc');
			$this->db->limit(1);
			$query6 = $this->db->get('other_history');
			if ($query6->num_rows() > 0) {
				$result6 = $query6->row_array();
				$data['oh_drugs'] = $result6['oh_drugs'];
				$data['callback'] = 'Items copied from last encounter!';
			}
			echo json_encode($data);
			exit(0);
		}
		if ($item == 'employment') {
			$data['callback'] = 'No previous encounter!';
			$this->db->where('pid', $pid);
			$this->db->where('eid !=', $eid);
			$this->db->where('oh_employment !=', '');
			$this->db->order_by('eid', 'desc');
			$this->db->limit(1);
			$query7 = $this->db->get('other_history');
			if ($query7->num_rows() > 0) {
				$result7 = $query7->row_array();
				$data['oh_employment'] = $result7['oh_employment'];
				$data['callback'] = 'Items copied from last encounter!';
			}
			echo json_encode($data);
			exit(0);
		}
	}

	function copy_issues()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->db->query("SELECT * FROM issues WHERE pid=$pid AND issue_date_inactive='0000-00-00 00:00:00'");
		if ($query->num_rows() > 0) {
			$result = '';
			foreach ($query->result_array() as $row) {
				$result .= $row['issue'] . ',';
			}
			echo $result;
		} else {
			echo 'No';
		}
		exit(0);
	}

	function edit_demographics()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$data = array(
			'marital_status' => $this->input->post('marital_status'),
			'partner_name' => $this->input->post('partner_name')
		);
		$this->demographics_model->update($pid, $data);
		$this->audit_model->update();
		echo "Patient infomration updated.";
	}

	function edit_demographics1()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$data = array(
			'employer' => $this->input->post('employer')
		);
		$this->demographics_model->update($pid, $data);
		$this->audit_model->update();
		echo "Patient infomration updated.";
	}
	
	function edit_demographics2()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$data = array(
			'tobacco' => $this->input->post('status')
		);
		$this->demographics_model->update($pid, $data);
		$this->audit_model->update();
		echo "Patient infomration updated.";
	}
	
	function edit_demographics3()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$data = array(
			'sexuallyactive' => $this->input->post('status')
		);
		$this->demographics_model->update($pid, $data);
		$this->audit_model->update();
		echo "Patient infomration updated.";
	}
	
	function oh_save()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$count = $this->encounters_model->getOtherHistory($eid)->num_rows();
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => $encounter_provider,
			'oh_pmh' => $this->input->post('oh_pmh'),
			'oh_psh' => $this->input->post('oh_psh'),
			'oh_fh' => $this->input->post('oh_fh')
		);
							
		if ($count > 0) {		
			$this->encounters_model->updateOtherHistory($eid, $data);
			$this->audit_model->update();
			$result = 'Other History Updated';
		} else {
			$this->encounters_model->addOtherHistory($data);
			$this->audit_model->add();
			$result = 'Other History Added';
		}
		echo $result;
	}

	function oh_save1($item)
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$query = $this->encounters_model->getOtherHistory($eid);
		$count = $query->num_rows();
		if ($item == 'sh') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_sh' => $this->input->post('oh_sh')
			);
		}
		if ($item == 'etoh') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_etoh' => $this->input->post('oh_etoh')
			);
		}
		if ($item == 'tobacco') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_tobacco' => $this->input->post('oh_tobacco')
			);
		}
		if ($item == 'drugs') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_drugs' => $this->input->post('oh_drugs')
			);
		}
		if ($item == 'employment') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_employment' => $this->input->post('oh_employment')
			);
		}
		if ($item == 'meds') {
			$query1 = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
			$result1 = '';
			if ($query1->num_rows() > 0) {
				foreach ($query1->result_array() as $row1) {
					if ($row1['rxl_sig'] != '') {
						$result1 .= $row1['rxl_medication'] . ' ' . $row1['rxl_dosage'] . ' ' . $row1['rxl_dosage_unit'] . ', ' . $row1['rxl_sig'] . ' ' . $row1['rxl_route'] . ' ' . $row1['rxl_frequency'] . ' for ' . $row1['rxl_reason'] . "\n";
					} else {
						$result1 .= $row1['rxl_medication'] . ' ' . $row1['rxl_dosage'] . ' ' . $row1['rxl_dosage_unit'] . ', ' . $row1['rxl_instructions'] . ' for ' . $row1['rxl_reason'] . "\n";
					}
				}
			} else {
				$result1 .= 'None.';
			}
			$result1 = trim($result1);
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_meds' => $result1
			);
		}
		if ($item == 'supplements') {
			$query2 = $this->db->query("SELECT * FROM sup_list WHERE pid=$pid AND sup_date_inactive='0000-00-00 00:00:00'");
			$result2 = '';
			if ($query2->num_rows() > 0) {
				foreach ($query2->result_array() as $row2) {
					if ($row2['sup_sig'] != '') {
						$result2 .=  $row2['sup_supplement'] . ' ' . $row2['sup_dosage'] . ' ' . $row2['sup_dosage_unit'] . ', ' . $row2['sup_sig'] . ' ' . $row2['sup_route'] . ' ' . $row2['sup_frequency'] . ' for ' . $row2['sup_reason'] . "\n";
					} else {
						$result2 .=  $row2['sup_supplement'] . ' ' . $row2['sup_dosage'] . ' ' . $row2['sup_dosage_unit'] . ', ' . $row2['sup_instructions'] . ' for ' . $row2['sup_reason'] . "\n";
					}
				}
			} else {
				$result2 .= 'None.';
			}
			$result2 = trim($result2);
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_supplements' => $result2
			);
		}
		if ($item == 'allergies') {
			$query3 = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive='0000-00-00 00:00:00'");
			$result3 = '';
			if ($query3->num_rows() > 0) {
				foreach ($query3->result_array() as $row3) {
					$result3 .=  $row3['allergies_med'] . ' - ' . $row3['allergies_reaction'] .  "\n";
				}
			} else {
				$result3 .= 'None.';
			}
			$result3 = trim($result3);
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_allergies' => $result3
			);
		}
		if ($count > 0) {		
			$this->encounters_model->updateOtherHistory($eid, $data);
			$this->audit_model->update();
			$result = 'Other History Updated.';
		} else {
			$this->encounters_model->addOtherHistory($data);
			$this->audit_model->add();
			$result = 'Other History Added.';
		}
		echo $result;
	}

	// --------------------------------------------------------------------
	
	function familyhxlist($category)
	{
		$this->db->where('familyhxlist_category', $category);
		$this->db->select('familyhxlist_item');
		$query1 = $this->db->get('familyhxlist');
		
		if ($query1->num_rows() == 0) 
		{
			$response->select[0]['oValue']='';
			$response->select[0]['oText']='No item to select';
			$response->select[0]['oSelected']='true';
			$response->select[0]['oClass']='familyhxlist';
		}
		else
		{
			$i=0;
			if ($query1->num_rows() > 0)
			{
	   			while($row = $query1->row_array()) 
	   			{
					$response->select[$i]['oValue']=$row[familyhxlist_item];
					$response->select[$i]['oText']=$row[familyhxlist_item];
					$response->select[$i]['oSelected']='false';
					$response->select[$i]['oClass']='familyhxlist';
					$i++; 
				}
			}
		}
		echo json_encode($response);
	}
	
	// --------------------------------------------------------------------

	function getcurrentinfo()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE)
		{
			redirect('start');
		}
		
		if ($this->input->post('id') == 'rx_list')
		{
			$items = $this->encounters_model->getMedicationList($pid);
			if ($items->num_rows() > 0)
			{
				$arr['text'] = "The following medications have been reviewed and updated:";
				foreach ($items->result() as $row)
				{
					$arr['text'] .= "\r\n" . $row->rxl_medication . " " . $row->rxl_dosage . " " . $row->rxl_dosage_unit . ", " . $row->rxl_sig . " " . $row->rxl_route . " " . $row->rxl_frequency . " for " . $row->rxl_reason;
				}
			}
			else
			{
				$arr['text'] = "No medications.";
			}
			$arr['result'] = 'Medications updated into the encounter.';
		}
		
		if ($this->input->post('id') == 'sup_list')
		{
			$items = $this->encounters_model->getSupplementList($pid);
			if ($items->num_rows() > 0)
			{
				$arr['text'] = 'The following supplements have been reviewed and updated:';
				foreach ($items->result() as $row)
				{
					$arr['text'] .= "\r\n" . $row->sup_supplement . " " . $row->sup_dosage . " " . $row->sup_dosage_unit . ", " . $row->sup_sig . " " . $row->sup_route . " " . $row->sup_frequency . " for " . $row->sup_reason;
				}
			}
			else
			{
				$arr['text'] = "No supplements.";
			}
			$arr['result'] = 'Supplements updated into the encounter.';
		}
		
		if ($this->input->post('id') != 'allergies')
		{
			$items = $this->encounters_model->getAllergiesList($pid);
			if ($items->num_rows() > 0)
			{
				$arr['text'] = 'The following allergies have been reviewed and updated:';
				foreach ($items->result() as $row)
				{
					$arr['text'] .= "\r\n" . $row->allergies_med;
				}
			}
			else
			{
				$arr['text'] = "No known drug allergies.";
			}
			$arr['result'] = 'Allergies updated into the encounter.';
		}
		echo json_encode($arr);
	}
	
	// -------------------------------------------------------------------
	
	function vitals()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}
		$data['practiceInfo'] = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row();
		$gender = $this->session->userdata('gender');
		$agealldays = $this->session->userdata('agealldays');
		if ($agealldays < 6574.5) {
			$data['hc'] = '<td>Head Circumference:<br><input type="hidden" name="vitals_headcircumference_old" id="vitals_headcircumference_old"/><input type="text" name="headcircumference" id="vitals_headcircumference" style="width:60px" class="text ui-widget-content ui-corner-all"> ' . $data['practiceInfo']->hc_unit . '</td>';
			$data['hc2'] = "{name:'headcircumference',index:'headcircumference',width:50},";
			if ($agealldays > 730.5) {
				$data['graphs'] = 'Growth Charts: <input type="button" id="weight_chart" value="Weight" class="ui-button ui-state-default ui-corner-all"> <input type="button" id="height_chart" value="Height" class="ui-button ui-state-default ui-corner-all"> <input type="button" id="hc_chart" value="Head Circumference" class="ui-button ui-state-default ui-corner-all"> <input type="button" id="bmi_chart" value="BMI" class="ui-button ui-state-default ui-corner-all"> <input type="button" id="weight_height_chart" value="Weight-Height" class="ui-button ui-state-default ui-corner-all"><br><br>';
			} else {
				$data['graphs'] = 'Growth Charts: <input type="button" id="weight_chart" value="Weight" class="ui-button ui-state-default ui-corner-all"> <input type="button" id="height_chart" value="Height" class="ui-button ui-state-default ui-corner-all"> <input type="button" id="hc_chart" value="Head Circumference" class="ui-button ui-state-default ui-corner-all"> <input type="button" id="weight_height_chart" value="Weight-Height" class="ui-button ui-state-default ui-corner-all"><br><br>';
			}
		} else {
			$data['graphs'] = '';
			$data['hc'] = '<td><input type="hidden" name="vitals_headcircumference_old" id="vitals_headcircumference_old"/><input type="hidden" name="headcircumference" id="vitals_headcircumference"></td>';
			$data['hc2'] = "{name:'headcircumference',index:'headcircumference',width:50,hidden:true},";
		}
		$data['encounter_id'] = $eid;
		
		$this->load->view('auth/pages/provider/chart/encounters/vitals', $data);
	}
	
	function get_vitals()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}
		$query = $this->encounters_model->getVitals($eid);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
		} else {
			$row = '';
		}
		echo json_encode($row);
	}

	function vitals_list()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM vitals WHERE pid=$pid");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM vitals WHERE pid=$pid ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;	
		echo json_encode($response);
		exit( 0 );
	}

	function vitals_save()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$count = $this->encounters_model->getVitals($eid)->num_rows();	
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => $encounter_provider,
			'weight' => $this->input->post('weight'),
			'height' => $this->input->post('height'),
			'headcircumference' => $this->input->post('headcircumference'),
			'BMI' => $this->input->post('BMI'),
			'temp' => $this->input->post('temp'),
			'temp_method' => $this->input->post('temp_method'),
			'bp_systolic' => $this->input->post('bp_systolic'),
			'bp_diastolic' => $this->input->post('bp_diastolic'),
			'bp_position' => $this->input->post('bp_position'),
			'pulse' => $this->input->post('pulse'),
			'respirations' => $this->input->post('respirations'),
			'o2_sat' => $this->input->post('o2_sat'),
			'vitals_other' => $this->input->post('vitals_other')
		);						
		if ($count > 0) {		
			$this->encounters_model->updateVitals($eid, $data);
			$this->audit_model->update();
			$result = 'Vitals Updated';
		} else {
			$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
			$demographicsInfo = $this->demographics_model->get($pid)->row();
			$a = human_to_unix($encounterInfo->encounter_DOS);
			$b = human_to_unix($demographicsInfo->DOB);
			$data['pedsage'] = ($a - $b)/2629743;
			$data['vitals_age'] = ($a - $b)/31556926;
			$data['vitals_date'] = $encounterInfo->encounter_DOS;
			$this->encounters_model->addVitals($data);
			$this->audit_model->add();
			$result = 'Vitals Added';
		}
		echo $result;
	}

	// --------------------------------------------------------------------

	function check_pe()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		$query = $this->encounters_model->getPE($eid);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			if ($row['pe_gen1']) {
				$data['gen'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['gen'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_eye1'] || $row['pe_eye2'] || $row['pe_eye3']) {
				$data['eye'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['eye'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_ent1'] || $row['pe_ent2'] || $row['pe_ent3'] || $row['pe_ent4'] || $row['pe_ent5'] || $row['pe_ent6']) {
				$data['ent'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['ent'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_neck1'] || $row['pe_neck2']) {
				$data['neck'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['neck'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_resp1'] || $row['pe_resp2'] || $row['pe_resp3'] || $row['pe_resp4']) {
				$data['resp'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['resp'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_cv1'] || $row['pe_cv2'] || $row['pe_cv3'] || $row['pe_cv4'] || $row['pe_cv5'] || $row['pe_cv6']) {
				$data['cv'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['cv'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_ch1'] || $row['pe_ch2']) {
				$data['ch'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['ch'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_gi1'] || $row['pe_gi2'] || $row['pe_gi3'] || $row['pe_gi4']) {
				$data['gi'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height=30" width="30">';
			} else {
				$data['gi'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_gu1'] || $row['pe_gu2'] || $row['pe_gu3'] || $row['pe_gu4'] || $row['pe_gu5'] || $row['pe_gu6'] || $row['pe_gu7'] || $row['pe_gu8'] || $row['pe_gu9']) {
				$data['gu'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['gu'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_lymph1'] || $row['pe_lymph2'] || $row['pe_lymph3']) {
				$data['lymph'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['lymph'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_ms1'] || $row['pe_ms2'] || $row['pe_ms3'] || $row['pe_ms4'] || $row['pe_ms5'] || $row['pe_ms6'] || $row['pe_ms7'] || $row['pe_ms8'] || $row['pe_ms9'] || $row['pe_ms10'] || $row['pe_ms11'] || $row['pe_ms12']) {
				$data['ms'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['ms'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_neuro1'] || $row['pe_neuro2'] || $row['pe_neuro3']) {
				$data['neuro'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['neuro'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_psych1'] || $row['pe_psych2'] || $row['pe_psych3'] || $row['pe_psych4']) {
				$data['psych'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['psych'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_skin1'] || $row['pe_skin2']) {
				$data['skin'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['skin'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			$data['message'] = 'Physical Examination Updated!';
		} else {
			$data['gen'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['eye'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['ent'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['neck'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['resp'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['cv'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['ch'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['gi'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['gu'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['lymph'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['ms'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['neuro'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['psych'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['skin'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['message'] = 'Physical Examination Unchanged!';
		}
		echo json_encode($data);
	}

	function get_pe($key = '')
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getPE($eid);
		if ($query->num_rows() > 0) {
			$data = $query->row_array();
			if ($key == '') {
				exit (0);
			} else {
				$data1['id'] = $key;
				$data1['text'] = $data[$key];
				echo json_encode($data1);
				exit (0);
			}
		} else {
			exit(0);
		}
	}
	
	function get_pe_individual($key)
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getPE($eid);
		$data = $query->row_array();
		echo json_encode($data);
	}
	
	function get_pe_templates()
	{
		$user_id = $this->session->userdata('user_id');
		$gender = $this->session->userdata('gender');
		if ($gender == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$this->db->where('user_id', $user_id);
		$this->db->or_where('user_id', '0');
		$this->db->where('sex', $sex);
		$this->db->where('category', 'pe');
		$this->db->where('default', "default");
		$result = $this->db->get('templates')->result_array();
		foreach($result as $row) {
			$id = $row['group'];
			$data[$id] = unserialize($row['array']);
		}
		echo json_encode($data);
	}
	
	function get_pe_templates_list($parent_id)
	{
		$user_id = $this->session->userdata('user_id');
		$gender = $this->session->userdata('gender');
		if ($gender == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$this->db->where('user_id', $user_id);
		$this->db->or_where('user_id', '0');
		$this->db->where('sex', $sex);
		$this->db->where('group', $parent_id);
		$result = $this->db->get('templates')->result_array();
		$data['select_id'] = $parent_id . "_template";
		$data['options'] = array();
		foreach ($result as $row) {
			$id = $row['template_id'];
			$data['options'][$id] = $row['template_name'];
		}
		echo json_encode($data);
	}
	
	function get_pe_template($id)
	{
		$this->db->where('template_id', $id);
		$row = $this->db->get('templates')->row_array();
		$data = unserialize($row['array']);
		echo json_encode($data);
	}

	function tip_pe($item, $num)
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getPE($eid);
		$count = $query->num_rows();
		if ($count > 0) {
			$data = $query->row_array();
			$result = '';
			$i = 1;
			while ($i <= $num) {
				$a = $data[$item . $i];
				if ($a != '') {
					$result .= nl2br($a);
					$result .= ' ';
				}
				$i = $i + 1;
			}
			if ($result != '') {
				echo $result;
			} else {
				echo 'No entry for this item.';
			}
		} else {
			echo 'No entry for this item.';
		}
		exit (0);
	}

	function pe()
	{
		$eid = $this->session->userdata('eid');
		$gender = $this->session->userdata('gender');
		$data['encounter_id'] = $eid;
		$query = $this->encounters_model->getPE($eid);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			if ($row['pe_gen1']) {
				$data['gen_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['gen_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_eye1'] || $row['pe_eye2'] || $row['pe_eye3']) {
				$data['eye_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['eye_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_ent1'] || $row['pe_ent2'] || $row['pe_ent3'] || $row['pe_ent4'] || $row['pe_ent5'] || $row['pe_ent6']) {
				$data['ent_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['ent_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_neck1'] || $row['pe_neck2']) {
				$data['neck_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['neck_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_resp1'] || $row['pe_resp2'] || $row['pe_resp3'] || $row['pe_resp4']) {
				$data['resp_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['resp_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_cv1'] || $row['pe_cv2'] || $row['pe_cv3'] || $row['pe_cv4'] || $row['pe_cv5'] || $row['pe_cv6']) {
				$data['cv_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['cv_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_ch1'] || $row['pe_ch2']) {
				$data['ch_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['ch_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_gi1'] || $row['pe_gi2'] || $row['pe_gi3'] || $row['pe_gi4']) {
				$data['gi_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['gi_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_gu1'] || $row['pe_gu2'] || $row['pe_gu3'] || $row['pe_gu4'] || $row['pe_gu5'] || $row['pe_gu6'] || $row['pe_gu7'] || $row['pe_gu8'] || $row['pe_gu9']) {
				$data['gu_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['gu_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_lymph1'] || $row['pe_lymph2'] || $row['pe_lymph3']) {
				$data['lymph_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['lymph_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_ms1'] || $row['pe_ms2'] || $row['pe_ms3'] || $row['pe_ms4'] || $row['pe_ms5'] || $row['pe_ms6'] || $row['pe_ms7'] || $row['pe_ms8'] || $row['pe_ms9'] || $row['pe_ms10'] || $row['pe_ms11'] || $row['pe_ms12']) {
				$data['ms_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['ms_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_neuro1'] || $row['pe_neuro2'] || $row['pe_neuro3']) {
				$data['neuro_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['neuro_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_psych1'] || $row['pe_psych2'] || $row['pe_psych3'] || $row['pe_psych4']) {
				$data['psych_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['psych_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['pe_skin1'] || $row['pe_skin2']) {
				$data['skin_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['skin_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
		} else {
			$data['gen_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['eye_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['ent_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['neck_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['resp_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['cv_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['ch_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['gi_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['gu_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['lymph_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['ms_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['neuro_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['psych_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['skin_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}	
		if ($gender == 'male') {	
			$this->load->view('auth/pages/provider/chart/encounters/pe_male', $data);
		} else {
			$this->load->view('auth/pages/provider/chart/encounters/pe_female', $data);
		}
	}

	function pe_save($item)
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$query = $this->encounters_model->getPE($eid);
		$count = $query->num_rows();
		if ($item == 'gen') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_gen1' => $this->input->post('pe_gen1')
			);
		}
		if ($item == 'eye') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_eye1' => $this->input->post('pe_eye1'),
				'pe_eye2' => $this->input->post('pe_eye2'),
				'pe_eye3' => $this->input->post('pe_eye3')
			);
		}
		if ($item == 'ent') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_ent1' => $this->input->post('pe_ent1'),
				'pe_ent2' => $this->input->post('pe_ent2'),
				'pe_ent3' => $this->input->post('pe_ent3'),
				'pe_ent4' => $this->input->post('pe_ent4'),
				'pe_ent5' => $this->input->post('pe_ent5'),
				'pe_ent6' => $this->input->post('pe_ent6')
			);
		}
		if ($item == 'neck') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_neck1' => $this->input->post('pe_neck1'),
				'pe_neck2' => $this->input->post('pe_neck2')
			);
		}
		if ($item == 'resp') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_resp1' => $this->input->post('pe_resp1'),
				'pe_resp2' => $this->input->post('pe_resp2'),
				'pe_resp3' => $this->input->post('pe_resp3'),
				'pe_resp4' => $this->input->post('pe_resp4')
			);
		}
		if ($item == 'cv') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_cv1' => $this->input->post('pe_cv1'),
				'pe_cv2' => $this->input->post('pe_cv2'),
				'pe_cv3' => $this->input->post('pe_cv3'),
				'pe_cv4' => $this->input->post('pe_cv4'),
				'pe_cv5' => $this->input->post('pe_cv5'),
				'pe_cv6' => $this->input->post('pe_cv6')
			);
		}
		if ($item == 'ch') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_ch1' => $this->input->post('pe_ch1'),
				'pe_ch2' => $this->input->post('pe_ch2')
			);
		}
		if ($item == 'gi') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_gi1' => $this->input->post('pe_gi1'),
				'pe_gi2' => $this->input->post('pe_gi2'),
				'pe_gi3' => $this->input->post('pe_gi3'),
				'pe_gi4' => $this->input->post('pe_gi4')
			);
		}
		if ($item == 'gu') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_gu1' => $this->input->post('pe_gu1'),
				'pe_gu2' => $this->input->post('pe_gu2'),
				'pe_gu3' => $this->input->post('pe_gu3'),
				'pe_gu4' => $this->input->post('pe_gu4'),
				'pe_gu5' => $this->input->post('pe_gu5'),
				'pe_gu6' => $this->input->post('pe_gu6'),
				'pe_gu7' => $this->input->post('pe_gu7'),
				'pe_gu8' => $this->input->post('pe_gu8'),
				'pe_gu9' => $this->input->post('pe_gu9')
			);
		}
		if ($item == 'lymph') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_lymph1' => $this->input->post('pe_lymph1'),
				'pe_lymph2' => $this->input->post('pe_lymph2'),
				'pe_lymph3' => $this->input->post('pe_lymph3')
			);
		}
		if ($item == 'ms') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_ms1' => $this->input->post('pe_ms1'),
				'pe_ms2' => $this->input->post('pe_ms2'),
				'pe_ms3' => $this->input->post('pe_ms3'),
				'pe_ms4' => $this->input->post('pe_ms4'),
				'pe_ms5' => $this->input->post('pe_ms5'),
				'pe_ms6' => $this->input->post('pe_ms6'),
				'pe_ms7' => $this->input->post('pe_ms7'),
				'pe_ms8' => $this->input->post('pe_ms8'),
				'pe_ms9' => $this->input->post('pe_ms9'),
				'pe_ms10' => $this->input->post('pe_ms10'),
				'pe_ms11' => $this->input->post('pe_ms11'),
				'pe_ms12' => $this->input->post('pe_ms12')
			);
		}
		if ($item == 'neuro') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_neuro1' => $this->input->post('pe_neuro1'),
				'pe_neuro2' => $this->input->post('pe_neuro2'),
				'pe_neuro3' => $this->input->post('pe_neuro3')
			);
		}
		if ($item == 'psych') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_psych1' => $this->input->post('pe_psych1'),
				'pe_psych2' => $this->input->post('pe_psych2'),
				'pe_psych3' => $this->input->post('pe_psych3'),
				'pe_psych4' => $this->input->post('pe_psych4')
			);
		}
		if ($item == 'skin') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'pe_skin1' => $this->input->post('pe_skin1'),
				'pe_skin2' => $this->input->post('pe_skin2')
			);
		}	
		if ($count > 0) {		
			$this->encounters_model->updatePE($eid, $data);
			$this->audit_model->update();
			$result = 'Physical Examination Updated';
		} else {
			$this->encounters_model->addPE($data);
			$this->audit_model->add();
			$result = 'Physical Examination Added';
		}
		echo $result;
	}
	
	// --------------------------------------------------------------------

	function check_labs()
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getLabs($eid);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			if ($row['labs_ua_urobili'] || $row['labs_ua_bilirubin'] || $row['labs_ua_ketones'] || $row['labs_ua_protein'] || $row['labs_ua_glucose'] || $row['labs_ua_nitrites'] || $row['labs_ua_leukocytes'] || $row['labs_ua_blood'] || $row['labs_ua_ph'] || $row['labs_ua_spgr'] || $row['labs_ua_color'] || $row['labs_ua_clarity']) {
				$data['ua'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['ua'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['labs_upt'] || $row['labs_strep'] || $row['labs_mono'] || $row['labs_flu'] || $row['labs_glucose']) {
				$data['rapid'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['rapid'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['labs_microscope']) {
				$data['micro'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['micro'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['labs_other']) {
				$data['other'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['other'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			$data['message'] = 'Lab Entry Updated!';
		} else {
			$data['ua'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['rapid'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['micro'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['other'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['message'] = 'Lab Entry Unchanged!';
		}
		echo json_encode($data);
	}	
	
	function get_labs()
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getLabs($eid);
		$data = $query->row_array();
		echo json_encode($data);
	}
	
	function tip_labs($lab)
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getLabs($eid);
		$count = $query->num_rows();
		if ($count > 0) {
			$data = $query->row_array();
			$result = '';
			if ($lab == "ua") {
				if ($data['labs_ua_urobili'] != '') {
					$result .= 'Urobilinogen: ';
					$result .= nl2br($data['labs_ua_urobili']);
					$result .= '<br>';
				}
				if ($data['labs_ua_bilirubin'] != '') {
					$result .= 'Bilirubin: ';
					$result .= nl2br($data['labs_ua_bilirubin']);
					$result .= '<br>';
				}
				if ($data['labs_ua_ketones'] != '') {
					$result .= 'Ketones: ';
					$result .= nl2br($data['labs_ua_ketones']);
					$result .= '<br>';
				}
				if ($data['labs_ua_protein'] != '') {
					$result .= 'Protein: ';
					$result .= nl2br($data['labs_ua_protein']);
					$result .= '<br>';
				}
				if ($data['labs_ua_glucose'] != '') {
					$result .= 'Glucose: ';
					$result .= nl2br($data['labs_ua_glucose']);
					$result .= '<br>';
				}
				if ($data['labs_ua_nitrites'] != '') {
					$result .= 'Nitrites: ';
					$result .= nl2br($data['labs_ua_nitrites']);
					$result .= '<br>';
				}
				if ($data['labs_ua_leukocytes'] != '') {
					$result .= 'Leukocytes: ';
					$result .= nl2br($data['labs_ua_leukocytes']);
					$result .= '<br>';
				}
				if ($data['labs_ua_blood'] != '') {
					$result .= 'Blood: ';
					$result .= nl2br($data['labs_ua_blood']);
					$result .= '<br>';
				}
				if ($data['labs_ua_ph'] != '') {
					$result .= 'pH: ';
					$result .= nl2br($data['labs_ua_ph']);
					$result .= '<br>';
				}
				if ($data['labs_ua_spgr'] != '') {
					$result .= 'Specific gravity: ';
					$result .= nl2br($data['labs_ua_spgr']);
					$result .= '<br>';
				}
				if ($data['labs_ua_color'] != '') {
					$result .= 'Color: ';
					$result .= nl2br($data['labs_ua_color']);
					$result .= '<br>';
				}
				if ($data['labs_ua_clarity'] != '') {
					$result .= 'Clarity: ';
					$result .= nl2br($data['labs_ua_clarity']);
					$result .= '<br>';
				}
			}
			if ($lab == "rapid") {
				if ($data['labs_upt'] != '') {
					$result .= 'Urine HcG: ';
					$result .= nl2br($data['labs_upt']);
					$result .= '<br>';
				}
				if ($data['labs_strep'] != '') {
					$result .= 'Rapid Strep: ';
					$result .= nl2br($data['labs_strep']);
					$result .= '<br>';
				}
				if ($data['labs_mono'] != '') {
					$result .= 'Mono Spot: ';
					$result .= nl2br($data['labs_mono']);
					$result .= '<br>';
				}
				if ($data['labs_flu'] != '') {
					$result .= 'Rapid Influenza: ';
					$result .= nl2br($data['labs_flu']);
					$result .= '<br>';
				}
				if ($data['labs_glucose'] != '') {
					$result .= 'Fingerstick Glucose: ';
					$result .= nl2br($data['labs_glucose']);
					$result .= '<br>';
				}
			}
			if ($lab == "micro") {
				if ($data['labs_microscope'] != '') {
					$result .= 'Microscopy: ';
					$result .= nl2br($data['labs_microscope']);
					$result .= '<br>';
				}
			}
			if ($lab == "other") {
				if ($data['labs_other'] != '') {
					$result .= 'Other Laboratory: ';
					$result .= nl2br($data['labs_other']);
					$result .= '<br>';
				}
			}
			if ($result != '') {
				echo $result;
			} else {
				echo 'No entry for this item.';
			}
		} else {
			echo 'No entry for this item.';
		}
		exit (0);
	}
	
	function labs()
	{
		$eid = $this->session->userdata('eid');
		$gender = $this->session->userdata('gender');
		$data['encounter_id'] = $eid;
		$query = $this->encounters_model->getLabs($eid);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			if ($row['labs_ua_urobili'] || $row['labs_ua_bilirubin'] || $row['labs_ua_ketones'] || $row['labs_ua_protein'] || $row['labs_ua_glucose'] || $row['labs_ua_nitrites'] || $row['labs_ua_leukocytes'] || $row['labs_ua_blood'] || $row['labs_ua_ph'] || $row['labs_ua_spgr'] || $row['labs_ua_color'] || $row['labs_ua_clarity']) {
				$data['ua_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['ua_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['labs_upt'] || $row['labs_strep'] || $row['labs_mono'] || $row['labs_flu'] || $row['labs_glucose']) {
				$data['rapid_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['rapid_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['labs_microscope']) {
				$data['micro_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['micro_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row['labs_other']) {
				$data['other_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['other_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
		} else {
			$data['ua_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['rapid_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['micro_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['other_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}	
		if ($gender == 'male') {	
			$this->load->view('auth/pages/provider/chart/encounters/labs_male', $data);
		} else {
			$this->load->view('auth/pages/provider/chart/encounters/labs_female', $data);
		}
	}

	function labs_save($item)
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$query = $this->encounters_model->getlabs($eid);
		$count = $query->num_rows();
		if ($item == 'ua') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'labs_ua_urobili' => $this->input->post('labs_ua_urobili'),
				'labs_ua_bilirubin' => $this->input->post('labs_ua_bilirubin'),
				'labs_ua_ketones' => $this->input->post('labs_ua_ketones'),
				'labs_ua_protein' => $this->input->post('labs_ua_protein'),
				'labs_ua_glucose' => $this->input->post('labs_ua_glucose'),
				'labs_ua_nitrites' => $this->input->post('labs_ua_nitrites'),
				'labs_ua_leukocytes' => $this->input->post('labs_ua_leukocytes'),
				'labs_ua_blood' => $this->input->post('labs_ua_blood'),
				'labs_ua_ph' => $this->input->post('labs_ua_ph'),
				'labs_ua_spgr' => $this->input->post('labs_ua_spgr'),
				'labs_ua_color' => $this->input->post('labs_ua_color'),
				'labs_ua_clarity' => $this->input->post('labs_ua_clarity')
			);
		}
		if ($item == 'rapid') {
			$gender = $this->session->userdata('gender');
			if ($gender == 'male') {
				$data = array(
					'eid' => $eid,
					'pid' => $pid,
					'encounter_provider' => $encounter_provider,
					'labs_strep' => $this->input->post('labs_strep'),
					'labs_mono' => $this->input->post('labs_mono'),
					'labs_flu' => $this->input->post('labs_flu'),
					'labs_glucose' => $this->input->post('labs_glucose')
				);
			} else {
				$data = array(
					'eid' => $eid,
					'pid' => $pid,
					'encounter_provider' => $encounter_provider,
					'labs_upt' => $this->input->post('labs_upt'),
					'labs_strep' => $this->input->post('labs_strep'),
					'labs_mono' => $this->input->post('labs_mono'),
					'labs_flu' => $this->input->post('labs_flu'),
					'labs_glucose' => $this->input->post('labs_glucose')
				);
			}	
		}
		if ($item == 'micro') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'labs_microscope' => $this->input->post('labs_microscope')
			);
		}
		if ($item == 'other') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'labs_other' => $this->input->post('labs_other')
			);
		}
		if ($count > 0) {		
			$this->encounters_model->updateLabs($eid, $data);
			$this->audit_model->update();
			$result = 'Lab Entry Updated';
		} else {
			$this->encounters_model->addLabs($data);
			$this->audit_model->add();
			$result = 'Lab Entry Added';
		}
		echo $result;
	}

	// --------------------------------------------------------------------

	function proc()
	{
		$this->load->view('auth/pages/provider/chart/encounters/proc');
	}
	
	function get_proc()
	{
		$eid = $this->session->userdata('eid');
		$query= $this->encounters_model->getProcedure($eid);
		$data = $query->row_array();
		echo json_encode($data);
	}

	function proc_save()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}	
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('fullname');
		$query = $this->encounters_model->getProcedure($eid);
		$count = $query->num_rows();	
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => $encounter_provider,
			'proc_type' => $this->input->post('procedure_type'),
			'proc_description' => $this->input->post('procedure_description'),
			'proc_complications' => $this->input->post('procedure_complications'),
			'proc_ebl' => $this->input->post('procedure_ebl'),
			'proc_cpt' => $this->input->post('procedure_cpt')
		);	
		if ($count > 0) {		
			$this->encounters_model->updateProcedure($eid, $data);
			$this->audit_model->update();
			$result = 'Procedure Updated';
		} else {
			$this->encounters_model->addProcedure($data);
			$this->audit_model->add();
			$result = 'Procedure Added';
		}
		echo $result;
	}
	
	function proc_template()
	{
		$id = $this->input->post('procedurelist_id');	
		$data = array(
			'procedure_type' => $this->input->post('procedure_type'),
			'procedure_description' => $this->input->post('procedure_description'),
			'procedure_complications' => $this->input->post('procedure_complications'),
			'procedure_ebl' => $this->input->post('procedure_ebl'),
			'cpt' => $this->input->post('procedure_cpt'),
			'practice_id' => $this->session->userdata('practice_id')
		);	
		if ($id != '') {		
			$this->encounters_model->updateProcedureTemplate($id, $data);
			$this->audit_model->update();
			$result = 'Procedure Template Updated';
		} else {
			$this->encounters_model->addProcedureTemplate($data);
			$this->audit_model->add();
			$result = 'Procedure Template Added';
		}
		echo $result;
	}

	// --------------------------------------------------------------------

	function assessment()
	{
		$this->load->view('auth/pages/provider/chart/encounters/assessment');
	}
	
	function get_assessment()
	{
		$eid = $this->session->userdata('eid');
		$query= $this->encounters_model->getAssessment($eid);
		$data = $query->row_array();
		echo json_encode($data);
	}
	
	function assessment_save()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$count = $this->encounters_model->getAssessment($eid)->num_rows();
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => $encounter_provider,
			'assessment_icd1' => $this->input->post('assessment_icd1'),
			'assessment_icd2' => $this->input->post('assessment_icd2'),
			'assessment_icd3' => $this->input->post('assessment_icd3'),
			'assessment_icd4' => $this->input->post('assessment_icd4'),
			'assessment_icd5' => $this->input->post('assessment_icd5'),
			'assessment_icd6' => $this->input->post('assessment_icd6'),
			'assessment_icd7' => $this->input->post('assessment_icd7'),
			'assessment_icd8' => $this->input->post('assessment_icd8'),
			'assessment_1' => $this->input->post('assessment_1'),
			'assessment_2' => $this->input->post('assessment_2'),
			'assessment_3' => $this->input->post('assessment_3'),
			'assessment_4' => $this->input->post('assessment_4'),
			'assessment_5' => $this->input->post('assessment_5'),
			'assessment_6' => $this->input->post('assessment_6'),
			'assessment_7' => $this->input->post('assessment_7'),
			'assessment_8' => $this->input->post('assessment_8'),
			'assessment_other' => $this->input->post('assessment_other'),
			'assessment_ddx' => $this->input->post('assessment_ddx'),
			'assessment_notes' => $this->input->post('assessment_notes')
		);
							
		if ($count > 0) {		
			$this->encounters_model->updateAssessment($eid, $data);
			$this->audit_model->update();
			$result = 'Assessment Updated';
		} else {
			$this->encounters_model->addAssessment($data);
			$this->audit_model->add();
			$result = 'Assessment Added';
		}
		echo $result;
	}

	// --------------------------------------------------------------------

	function check_orders()
	{
		$eid = $this->session->userdata('eid');
		$query_labs = $this->encounters_model->getOrders_labs($eid);
		$query_radiology = $this->encounters_model->getOrders_radiology($eid);
		$query_cp = $this->encounters_model->getOrders_cp($eid);
		$query_ref = $this->encounters_model->getOrders_ref($eid);
		if ($query_labs->num_rows() > 0) {
			$data['labs_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
		} else {
			$data['labs_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}
		if ($query_radiology->num_rows() > 0) {
			$data['rad_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
		} else {
			$data['rad_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}
		if ($query_cp->num_rows() > 0) {
			$data['cp_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
		} else {
			$data['cp_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}
		if ($query_ref->num_rows() > 0) {
			$data['ref_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
		} else {
			$data['ref_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}
		$data['message'] = 'Orders Updated!';
		
		$query1 = $this->encounters_model->getRX($eid);		
		if ($query1->num_rows() > 0) {
			$row1 = $query1->row_array();
			if ($row1['rx_rx']) {
				$data['rx_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['rx_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row1['rx_supplements']) {
				$data['sup_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['sup_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row1['rx_immunizations']) {
				$data['imm_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['imm_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			$data['message'] = 'Orders Updated!';
		} else {
			$data['rx_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['sup_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['imm_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}
		echo json_encode($data);
	}
	
	function get_orders()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		$query = $this->encounters_model->getPlan($eid);
		$data = $query->row_array();
		echo json_encode($data);
	}
	
	function orders_save_common($plan, $duration, $followup)
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			echo 'Close Chart';
			exit (0);
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$count = $this->encounters_model->getPlan($eid)->num_rows();
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => $encounter_provider,
			'plan' => $plan,
			'duration' => $duration,
			'followup' => $followup
		);
							
		if ($count > 0) {		
			$this->encounters_model->updatePlan($eid, $data);
			$this->audit_model->update();
			$result = 'Orders Updated';
		} else {
			$this->encounters_model->addPlan($data);
			$this->audit_model->add();
			$result = 'Orders Added';
		}
		return $result;
	}
	
	function orders_save()
	{
		$plan =  $this->input->post('plan');
		$duration =  $this->input->post('duration');
		$followup =  $this->input->post('followup');
		$result = $this->orders_save_common($plan, $duration, $followup);
		echo $result;
	}
	
	function orders_rx_save()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			echo 'Close Chart';
			exit (0);
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$rx_rx = "";
		$rx_orders_summary_text = "";
		$count = $this->encounters_model->getRX($eid)->num_rows();
		if ($count > 0) {
			$row = $this->encounters_model->getRX($eid)->row_array();
			$row_parts = explode("\n\n", $row['rx_rx']);
			$rx_text = "";
			$rx_eie_text = "";
			$rx_inactivate_text = "";
			$rx_reactivate_text = "";
			foreach($row_parts as $row_part) {
				if (strpos($row_part, "PRESCRIBED MEDICATIONS:")!==FALSE) {
					$rx_text .= str_replace("PRESCRIBED MEDICATIONS:  ","",$row_part);
				}
				if (strpos($row_part, "ENTERED MEDICATIONS IN ERROR:")!==FALSE) {
					$rx_eie_text .= str_replace("ENTERED MEDICATIONS IN ERROR:  ","",$row_part);
				}
				if (strpos($row_part, "DISCONTINUED MEDICATIONS:")!==FALSE) {
					$rx_inactivate_text .= str_replace("DISCONTINUED MEDICATIONS:  ","",$row_part);
				}
				if (strpos($row_part, "REINSTATED MEDICATIONS:")!==FALSE) {
					$rx_reactivate_text .= str_replace("REINSTATED MEDICATIONS:  ","",$row_part);
				}
			}
			if($rx_text != "" || $this->input->post('rx')) {
				$rx_rx .= "PRESCRIBED MEDICATIONS:  ";
				$rx_orders_summary_text .= "PRESCRIBED MEDICATIONS:  ";
				if ($rx_text) {
					$rx_rx .= $rx_text;
					$rx_orders_summary_text .= $rx_text;
				}
				if ($this->input->post('rx')) {
					$rx_rx .= $this->input->post('rx');
					$rx_orders_summary_text .= $this->input->post('rx');
				}
			}
			if($rx_eie_text != "" || $this->input->post('eie')) {
				$rx_rx .= "ENTERED MEDICATIONS IN ERROR:  ";
				if ($rx_eie_text) {
					$rx_rx .= $rx_eie_text;
				}
				if ($this->input->post('eie')) {
					$rx_rx .= $this->input->post('eie');
				}
			}
			if($rx_inactivate_text != "" || $this->input->post('inactivate')) {
				$rx_rx .= "DISCONTINUED MEDICATIONS:  ";
				$rx_orders_summary_text .= "DISCONTINUED MEDICATIONS:  ";
				if ($rx_inactivate_text) {
					$rx_rx .= $rx_inactivate_text;
					$rx_orders_summary_text .= $rx_inactivate_text;
				}
				if ($this->input->post('inactivate')) {
					$rx_rx .= $this->input->post('inactivate');
					$rx_orders_summary_text .= $this->input->post('inactivate');
				}
			}
			if($rx_reactivate_text != "" || $this->input->post('reactivate')) {
				$rx_rx .= "REINSTATED MEDICATIONS:  ";
				$rx_orders_summary_text .= "REINSTATED MEDICATIONS:  ";
				if ($rx_reactivate_text) {
					$rx_rx .= $rx_inactivate_text;
					$rx_orders_summary_text .= $rx_inactivate_text;
				}
				if ($this->input->post('reactivate')) {
					$rx_rx .= $this->input->post('reactivate');
					$rx_orders_summary_text .= $this->input->post('reactivate');
				}
			}
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'rx_rx' => $rx_rx,
				'rx_orders_summary' => $rx_orders_summary_text
			);
			$this->encounters_model->updateRX($eid, $data);
			$this->audit_model->update();
			$result = 'Medication Orders Updated';
		} else {
			if ($this->input->post('rx')) {
				$rx_text = "PRESCRIBED MEDICATIONS:  " . $this->input->post('rx');
				$rx_rx .= $rx_text . "\n\n";
				$rx_orders_summary_text .= $rx_text . "\n\n";
			}
			if ($this->input->post('eie')) {
				$rx_rx .= "ENTERED MEDICATIONS IN ERROR:  " . $this->input->post('eie') . "\n\n";
			}
			if ($this->input->post('inactivate')) {
				$rx_inactivate_text = "DISCONTINUED MEDICATIONS:  " . $this->input->post('inactivate');
				$rx_rx .= $rx_inactivate_text . "\n\n";
				$rx_orders_summary_text .= $rx_inactivate_text . "\n\n";
			}
			if ($this->input->post('reactivate')) {
				$rx_reactivate_text = "REINSTATED MEDICATIONS:  " . $this->input->post('reactivate');
				$rx_rx .= $rx_reactivate_text . "\n\n";
				$rx_orders_summary_text .= $rx_reactivate_text . "\n\n";
			}
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'rx_rx' => $rx_rx,
				'rx_orders_summary' => $rx_text
			);
			$this->encounters_model->addRX($data);
			$this->audit_model->add();
			$result = 'Medication Orders Added';
		}
		echo $result;
	}
	
	function rcopia_check_update($pid, $old, $row)
	{
		$this->db->select($row);
		$this->db->where('pid', $pid);
		$result = $this->db->get('demographics')->row_array();
		if ($result[$row] != $old) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function rcopia_update_medication_xml($pid, $result1)
	{
		$xml = new SimpleXMLElement($result1);
		$last_update_date = $xml->Response->LastUpdateDate . "";
		$number = $xml->Response->PrescriptionList->Number . "";
		if ($number != "0") {
			$rx_rx = '';
			foreach ($xml->Response->PrescriptionList->Prescription as $prescription) {
				$rxl_medication = ucfirst($prescription->Sig->Drug->BrandName) . ", " . $prescription->Sig->Drug->Form;
				if ($prescription->Sig->Drug->Strength != '') {
					if (strpos($prescription->Sig->Drug->Strength, " ")) {
						$rxl_dosage_parts = explode(" ", $prescription->Sig->Drug->Strength);
						$rxl_dosage = $rxl_dosage_parts[0];
						$rxl_dosage_unit = $rxl_dosage_parts[1];
					} else {
						$rxl_dosage = $prescription->Sig->Drug->Strength . '';
						$rxl_dosage_unit = '';
					}
				} else {
					$rxl_dosage = '';
					$rxl_dosage_unit = '';
				}
				$date_active_pre = explode(" ", $prescription->CreatedDate);
				$date_active_pre1 = explode("/", $date_active_pre[0]);
				$date_active = $date_active_pre1[2] . "-" . $date_active_pre1[0] . "-" . $date_active_pre1[1] . " 00:00:00";
				$this->db->where('rxl_medication', $rxl_medication);
				$this->db->where('rxl_dosage', $rxl_dosage);
				$old_query = $this->db->get('rx_list');
				if ($old_query->num_rows() > 0) {
					$old_result = $old_query->row_array();
					$date1 = now();
					$datestring = "%Y-%m-%d %H:%i:%s";
					$date_old = mdate($datestring, $date1);
					$data1 = array(
						'rxl_date_old' => $date_old
					);
					$update = $this->chart_model->updateMedication($old_result['rxl_id'], $data1);
					$this->audit_model->update();
					$this->db->select('rxl_date_active');
					$this->db->where('rxl_id', $old_result['rxl_id']);
					$query2 = $this->db->get('rx_list');
					$result2 = $query2->row();
					$old_date_active = $result2->rxl_date_active;
				} else {
					$old_date_active = $date_active;
				}
				if ($prescription->Sig->Dose != '' || $prescription->Sig->DoseUnit != '') {
					$rxl_sig = $prescription->Sig->Dose . " " . $prescription->Sig->DoseUnit;
				} else {
					$rxl_sig = '';
				}
				$quantity_unit = $prescription->Sig->QuantityUnit;
				if ($quantity_unit == "" || $quantity_unit == "undefined") {
					$rxl_quantity = $prescription->Sig->Quantity;
				} else {
					$rxl_quantity = $prescription->Sig->Quantity . " " . $prescription->Sig->QuantityUnit;
				}
				if ($prescription->Sig->SubstitutionPermitted == 'y') {
					$rxl_daw =  '';
				} else {
					$rxl_daw = 'Dispense As Written';
				}
				$date_active_pre = explode(" ", $prescription->CreatedDate);
				$date_active_pre1 = explode("/", $date_active_pre[0]);
				$date_active = $date_active_pre1[2] . "-" . $date_active_pre1[0] . "-" . $date_active_pre1[1] . " 00:00:00";
				if ($prescription->StopDate != '') {
					$due_date_parts0 = explode(" ", $prescription->StopDate);
					$due_date_parts = explode("/", $due_date_parts0[0]);
					$rxl_due_date = $due_date_parts[2] . "-" . $due_date_parts[0] . "-" . $due_date_parts[1] . " 00:00:00";
				} else {
					$rxl_due_date = '00-00-00 00:00:00';
				}
				$rxl_provider = $prescription->Provider->FirstName . " " . $prescription->Provider->LastName;
				$rxl_route = $prescription->Sig->Route . "";
				$rxl_frequency = $prescription->Sig->DoseTiming . "";
				$rxl_instructions = $prescription->Sig->DoseOther . "";
				$rxl_refill = $prescription->Sig->Refills . "";
				$rxl_ndcid = $prescription->Sig->Drug->NDCID . "";
				$data2 = array(
					'rxl_medication' => $rxl_medication,
					'rxl_dosage' => $rxl_dosage,
					'rxl_dosage_unit' => $rxl_dosage_unit,
					'rxl_sig' => $rxl_sig,
					'rxl_route' => $rxl_route,
					'rxl_frequency' => $rxl_frequency,
					'rxl_instructions' => $rxl_instructions,
					'rxl_quantity' => $rxl_quantity,
					'rxl_refill' => $rxl_refill,
					'rxl_date_active' => $old_date_active,
					'rxl_date_inactive' => '',
					'rxl_date_prescribed' => $date_active,
					'rxl_date_old' => '',
					'rxl_provider' => $rxl_provider,
					'rxl_daw' => $rxl_daw,
					'rxl_due_date' => $rxl_due_date,
					'rxl_ndcid' => $rxl_ndcid,
					'rcopia_sync' => 'y',
					'pid' => $pid
				);
				$add1 = $this->chart_model->addMedication($data2);
				$this->audit_model->add();
				if ($rxl_sig == '') {
					$instructions = $rxl_instructions;
				} else {
					$instructions = $rxl_sig . ' ' . $rxl_route . ' ' . $rxl_frequency;
				}
				$rx_rx .= $rxl_medication . ' ' . $rxl_dosage . ' ' . $rxl_dosage_unit . ', ' . $instructions . ', Quantity: ' . $rxl_quantity . ', Refills: ' . $rxl_refill . "\n";
			}
		} else {
			$rx_rx = "No updated prescriptions.";
		}
		$data = array(
			'rcopia_update_prescription_date' => $last_update_date,
			'rcopia_update_prescription' => 'n'
		);
		$this->db->where('pid', $pid);
		$this->db->update('demographics', $data);
		return $rx_rx;
	}
	
	function rcopia_update_medication()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			redirect('start');
			exit (0);
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$this->db->select('rcopia_update_prescription_date');
		$this->db->where('pid', $pid);
		$old = $this->db->get('demographics')->row_array();
		$rcopia_data = array(
			'rcopia_update_prescription' => 'y'
		);
		$this->db->where('pid', $pid);
		$this->db->update('demographics', $rcopia_data);
		$xml1 = "<Request><Command>update_prescription</Command>";
		$xml1 .= "<LastUpdateDate>" . $old['rcopia_update_prescription_date'] . "</LastUpdateDate>";
		$xml1 .= "<Patient><ExternalID>" . $pid . "</ExternalID></Patient>";
		$xml1 .= "</Request></RCExtRequest>";
		$result1 = $this->rcopia($xml1);
		$response1 = new SimpleXMLElement($result1);
		$status1 = $response1->Response->Status . "";
		if ($status1 == "error") {
			$description1 = $response1->Response->Error->Text . "";
			$data1a = array(
				'action' => 'update_prescription',
				'pid' => $pid,
				'extensions_name' => 'rcopia',
				'description' => $description1
			);
			$this->db->insert('extensions_log', $data1a);
			$arr['response'] = "Error connecting to DrFirst RCopia.  Try again later.";
		} else {
			$response = $this->rcopia_update_medication_xml($pid, $result1);
			if ($response == "No updated prescriptions.") {
				$arr['response'] = $response;
			} else {
				$arr['medtext'] = $response;
				$arr['response'] = "Updated medications from DrFirst Rcopia.";
			}
		}
		echo json_encode($arr);
		exit (0);
	}
	
	function orders_sup_save()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			echo 'Close Chart';
			exit (0);
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$query = $this->db->query("SELECT * FROM sup_list WHERE pid=$pid AND sup_date_inactive='0000-00-00 00:00:00'");
		if ($query->num_rows() > 0) {
			$rx_orders_summary_text = "";
			foreach ($query->result_array() as $query_row) {
				$rx_orders_summary_text .= $query_row['sup_supplement'] . ' ' . $query_row['sup_dosage'] . ' ' . $query_row['sup_dosage_unit'];
				if ($query_row['sup_sig'] != "") {
					$rx_orders_summary_text .= ", " . $query_row['sup_sig'] . ' ' . $query_row['sup_route'] . ' ' . $query_row['sup_frequency'];
				}
				if ($query_row['sup_instructions'] != "") {
					$rx_orders_summary_text .= ", " . $query_row['sup_instructions'];
				}
				$rx_orders_summary_text .= ' for ' . $query_row['sup_reason'] . "\n";
			}
		} else {
			$rx_orders_summary_text = "";
		}
		$rx_rx = "";
		$count = $this->encounters_model->getRX($eid)->num_rows();
		if ($count > 0) {
			$row = $this->encounters_model->getRX($eid)->row_array();
			$row_parts = explode("\n\n", $row['rx_supplements']);
			$rx_text = "";
			$rx_purchase_text = "";
			$rx_inactivate_text = "";
			$rx_reactivate_text = "";
			foreach($row_parts as $row_part) {
				if (strpos($row_part, "SUPPLEMENTS ADVISED:")!==FALSE) {
					$rx_text .= str_replace("SUPPLEMENTS ADVISED:  ","",$row_part);
				}
				if (strpos($row_part, "SUPPLEMENTS PURCHASED BY PATIENT:")!==FALSE) {
					$rx_purchase_text .= str_replace("SUPPLEMENTS PURCHASED BY PATIENT:  ","",$row_part);
				}
				if (strpos($row_part, "DISCONTINUED SUPPLEMENTS:")!==FALSE) {
					$rx_inactivate_text .= str_replace("DISCONTINUED SUPPLEMENTS:  ","",$row_part);
				}
				if (strpos($row_part, "REINSTATED SUPPLEMENTS:")!==FALSE) {
					$rx_reactivate_text .= str_replace("REINSTATED SUPPLEMENTS:  ","",$row_part);
				}
			}
			if($rx_text != "" || $this->input->post('advised')) {
				$rx_rx .= "SUPPLEMENTS ADVISED:  ";
				if ($rx_text) {
					$rx_rx .= $rx_text;
				}
				if ($this->input->post('advised')) {
					$rx_rx .= $this->input->post('advised');
				}
			}
			if($rx_purchase_text != "" || $this->input->post('purchased')) {
				$rx_rx .= "\n\nSUPPLEMENTS PURCHASED BY PATIENT:  ";
				if ($rx_purchase_text) {
					$rx_rx .= $rx_purchase_text;
				}
				if ($this->input->post('purchased')) {
					$rx_rx .= $this->input->post('purchased');
				}
			}
			if($rx_inactivate_text != "" || $this->input->post('inactivate')) {
				$rx_rx .= "\n\nDISCONTINUED SUPPLEMENTS:  ";
				if ($rx_inactivate_text) {
					$rx_rx .= $rx_inactivate_text;
				}
				if ($this->input->post('inactivate')) {
					$rx_rx .= $this->input->post('inactivate');
				}
			}
			if($rx_reactivate_text != "" || $this->input->post('reactivate')) {
				$rx_rx .= "\n\nREINSTATED SUPPLEMENTS:  ";
				if ($rx_reactivate_text) {
					$rx_rx .= $rx_inactivate_text;
				}
				if ($this->input->post('reactivate')) {
					$rx_rx .= $this->input->post('reactivate');
				}
			}
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'rx_supplements' => $rx_rx,
				'rx_supplements_orders_summary' => $rx_orders_summary_text
			);
			$this->encounters_model->updateRX($eid, $data);
			$this->audit_model->update();
			$result = 'Supplement Orders Updated';
		} else {
			if ($this->input->post('advised')) {
				$rx_text = "SUPPLEMENTS ADVISED:  " . $this->input->post('advised');
				$rx_rx .= $rx_text . "\n\n";
			}
			if ($this->input->post('purchased')) {
				$rx_purchase_text = "SUPPLEMENTS PURCHASED BY PATIENT:  " . $this->input->post('purchased');
				$rx_rx .= $rx_purchase_text . "\n\n";
			}
			if ($this->input->post('inactivate')) {
				$rx_inactivate_text = "DISCONTINUED SUPPLEMENTS:  " . $this->input->post('inactivate');
				$rx_rx .= $rx_inactivate_text . "\n\n";
			}
			if ($this->input->post('reactivate')) {
				$rx_reactivate_text = "REINSTATED SUPPLEMENTS:  " . $this->input->post('reactivate');
				$rx_rx .= $rx_reactivate_text . "\n\n";
			}
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'rx_supplements' => $rx_rx,
				'rx_supplements_orders_summary' => $rx_orders_summary_text
			);
			$this->encounters_model->addRX($data);
			$this->audit_model->add();
			$result = 'Supplement Orders Added';
		}
		echo $result;
	}
	
	function orders_imm_save()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			echo 'Close Chart';
			exit (0);
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$count = $this->encounters_model->getRX($eid)->num_rows();
		if ($count > 0) {
			$row = $this->encounters_model->getRX($eid)->row_array();
			$rx_immunizations = $row['rx_immunizations'] . $this->input->post('rx_immunizations');
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'rx_immunizations' => $rx_immunizations,
			);
			$this->encounters_model->updateRX($eid, $data);
			$result = 'Immunization Orders Updated';
		} else {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'rx_immunizations' => $this->input->post('rx_immunizations'),
			);
			$this->encounters_model->addRX($data);
			$result = 'Immunization Orders Added';
		}
		echo $result;
	}
	
	function tip_orders($item)
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		if ($item == 'rx'){
			$query = $this->encounters_model->getRX($eid);
			$count = $query->num_rows();
			if ($count > 0) {
				$data = $query->row_array();
				if ($data['rx_rx'] == '') {
					echo 'No entry for this item.';
				} else {
					$text = nl2br($data['rx_rx']) . "<br><br>Click on check mark to edit text.";
					echo $text;
				}
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'sup'){
			$query = $this->encounters_model->getRX($eid);
			$count = $query->num_rows();
			if ($count > 0) {
				$data = $query->row_array();
				if ($data['rx_supplements'] == '') {
					echo 'No entry for this item.';
				} else {
					$text = nl2br($data['rx_supplements']) . "<br><br>Click on check mark to edit text.";
					echo $text;
				}
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'imm'){
			$query = $this->encounters_model->getRX($eid);
			$count = $query->num_rows();
			if ($count > 0) {
				$data = $query->row_array();
				if ($data['rx_immunizations'] == '') {
					echo 'No entry for this item.';
				} else {
					$text = nl2br($data['rx_immunizations']) . "<br><br>Click on check mark to edit text.";
					echo $text;
				}
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'labs'){
			$query = $this->encounters_model->getOrders($eid);
			$count = $query->num_rows();
			if ($count > 0) {
				$description = '';
				foreach ($query->result_array() as $data) {
					if ($data['orders_labs'] == '') {
						$description .= '';
					} else {
						$text = nl2br($data['orders_labs']);
						$address_id = $data['address_id'];
						$this->db->where('address_id', $address_id);
						$query1 = $this->db->get('addressbook');
						$row1 = $query1->row_array();
						$description .= 'Orders sent to ' . $row1['displayname'] . ': '. $text . '<br>';
					}
				}
				echo $description;
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'rad'){
			$query = $this->encounters_model->getOrders($eid);
			$count = $query->num_rows();
			if ($count > 0) {
				$description = '';
				foreach ($query->result_array() as $data) {
					if ($data['orders_radiology'] == '') {
						$description .= '';
					} else {
						$text = nl2br($data['orders_radiology']);
						$address_id = $data['address_id'];
						$this->db->where('address_id', $address_id);
						$query1 = $this->db->get('addressbook');
						$row1 = $query1->row_array();
						$description .= 'Orders sent to ' . $row1['displayname'] . ': '. $text . '<br>';
					}
				}
				echo $description;
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'cp'){
			$query = $this->encounters_model->getOrders($eid);
			$count = $query->num_rows();
			if ($count > 0) {
				$description = '';
				foreach ($query->result_array() as $data) {
					if ($data['orders_cp'] == '') {
						$description .= '';
					} else {
						$text = nl2br($data['orders_cp']);
						$address_id = $data['address_id'];
						$this->db->where('address_id', $address_id);
						$query1 = $this->db->get('addressbook');
						$row1 = $query1->row_array();
						$description .= 'Orders sent to ' . $row1['displayname'] . ': '. $text . '<br>';
					}
				}
				echo $description;
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'ref'){
			$query = $this->encounters_model->getOrders($eid);
			$count = $query->num_rows();
			if ($count > 0) {
				$description = '';
				foreach ($query->result_array() as $data) {
					if ($data['orders_referrals'] == '') {
						$description .= '';
					} else {
						$text = nl2br($data['orders_referrals']);
						$address_id = $data['address_id'];
						$this->db->where('address_id', $address_id);
						$query1 = $this->db->get('addressbook');
						$row1 = $query1->row_array();
						$description .= 'Referral sent to ' . $row1['displayname'] . ':<br>'. $text . '<br>';
					}
				}
				echo $description;
			} else {
				echo 'No entry for this item.';
			}
		}
		exit (0);
	}
	
	function tip_edit_orders($item)
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		$arr['type'] = "N";
		if ($item == 'rx'){
			$query = $this->encounters_model->getRX($eid);
			$count = $query->num_rows();
			if ($count > 0) {
				$data = $query->row_array();
				if ($data['rx_rx'] != '') {
					$arr['type'] = 'rx_rx';
					$arr['text'] = $data['rx_rx'];
				}
			}
		}
		if ($item == 'sup'){
			$query = $this->encounters_model->getRX($eid);
			$count = $query->num_rows();
			if ($count > 0) {
				$data = $query->row_array();
				if ($data['rx_supplements'] != '') {
					$arr['type'] = 'rx_supplements';
					$arr['text'] = $data['rx_supplements'];
				}
			}
		}
		if ($item == 'imm'){
			$query = $this->encounters_model->getRX($eid);
			$count = $query->num_rows();
			if ($count > 0) {
				$data = $query->row_array();
				if ($data['rx_immunizations'] != '') {
					$arr['type'] = 'rx_immunizations';
					$arr['text'] = $data['rx_immunizations'];
				}
			}
		}
		echo json_encode($arr);
		exit (0);
	}
	
	function edit_tip_orders($type)
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			echo 'Close Chart';
			exit (0);
		}
		$pid = $this->session->userdata('pid');
		$encounter_provider = $this->session->userdata('displayname');
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => $encounter_provider,
			$type => $this->input->post($type),
		);
		$this->encounters_model->updateRX($eid, $data);
		$this->audit_model->update();
		if ($type == 'rx_rx') {
			$text = "Medication ";
		}
		if ($type == 'rx_supplements') {
			$text = "Supplement ";
		}
		if ($type == 'rx_immunizations') {
			$text = "Immunization ";
		}
		$result = $text . 'Orders Updated';
		echo $result;
	}
	
	function orders()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE){
			redirect('start');
		}
		$pid = $this->session->userdata('pid');
		$query_labs = $this->encounters_model->getOrders_labs($eid);
		$query_radiology = $this->encounters_model->getOrders_radiology($eid);
		$query_cp = $this->encounters_model->getOrders_cp($eid);
		$query_ref = $this->encounters_model->getOrders_ref($eid);
		if ($query_labs->num_rows() > 0) {		
			$data['labs_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
		} else {
			$data['labs_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}
		if ($query_radiology->num_rows() > 0) {
			$data['rad_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
		} else {
			$data['rad_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}
		if ($query_cp->num_rows() > 0) {
			$data['cp_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
		} else {
			$data['cp_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}
		if ($query_ref->num_rows() > 0) {
			$data['ref_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
		} else {
			$data['ref_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}
		$query1 = $this->encounters_model->getRX($eid);		
		if ($query1->num_rows() > 0) {
			$row1 = $query1->row_array();
			if ($row1['rx_rx']) {
				$data['rx_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['rx_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row1['rx_supplements']) {
				$data['sup_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['sup_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
			if ($row1['rx_immunizations']) {
				$data['imm_status'] = '<img src="' . base_url() . 'images/button_accept.png" border="0" height="30" width="30">';
			} else {
				$data['imm_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			}
		} else {
			$data['rx_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['sup_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
			$data['imm_status'] = '<img src="' . base_url() . 'images/cancel.png" border="0" height="30" width="30">';
		}

		$this->load->view('auth/pages/provider/chart/encounters/orders', $data);
	}
	
	function page_orders1($eid)
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}	
		$ordersInfo = $this->encounters_model->getOrders($eid)->row();
		if ($ordersInfo) {
			$data['orders'] = '<br><h4>Orders:</h4><p class="view">';
			$ordersInfo_labs_query = $this->encounters_model->getOrders_labs($eid);
			if ($ordersInfo_labs_query->num_rows() > 0) {
				$data['orders'] .= '<strong>Labs: </strong>';
				foreach ($ordersInfo_labs_query->result_array() as $ordersInfo_labs_result) {
					$text1 = nl2br($ordersInfo_labs_result['orders_labs']);
					$address_id1 = $ordersInfo_labs_result['address_id'];
					$this->db->where('address_id', $address_id1);
					$query_address1 = $this->db->get('addressbook');
					$address_row1 = $query_address1->row_array();
					$data['orders'] .= 'Orders sent to ' . $address_row1['displayname'] . ': '. $text1 . '<br />';
				}
			}
			$ordersInfo_rad_query = $this->encounters_model->getOrders_radiology($eid);
			if ($ordersInfo_rad_query->num_rows() > 0) {
				$data['orders'] .= '<strong>Imaging: </strong>';
				foreach ($ordersInfo_rad_query->result_array() as $ordersInfo_rad_result) {
					$text2 = nl2br($ordersInfo_rad_result['orders_radiology']);
					$address_id2 = $ordersInfo_rad_result['address_id'];
					$this->db->where('address_id', $address_id2);
					$query_address2 = $this->db->get('addressbook');
					$address_row2 = $query_address2->row_array();
					$data['orders'] .= 'Orders sent to ' . $address_row2['displayname'] . ': '. $text2 . '<br />';
				}
			}
			$ordersInfo_cp_query = $this->encounters_model->getOrders_cp($eid);
			if ($ordersInfo_cp_query->num_rows() > 0) {
				$data['orders'] .= '<strong>Cardiopulmonary: </strong>';
				foreach ($ordersInfo_cp_query->result_array() as $ordersInfo_cp_result) {
					$text3 = nl2br($ordersInfo_cp_result['orders_cp']);
					$address_id3 = $ordersInfo_cp_result['address_id'];
					$this->db->where('address_id', $address_id3);
					$query_address3 = $this->db->get('addressbook');
					$address_row3 = $query_address3->row_array();
					$data['orders'] .= 'Orders sent to ' . $address_row3['displayname'] . ': '. $text3 . '<br />';
				}
			}
			$ordersInfo_ref_query = $this->encounters_model->getOrders_ref($eid);
			if ($ordersInfo_ref_query->num_rows() > 0) {
				$data['orders'] .= '<strong>Referrals: </strong>';
				foreach ($ordersInfo_ref_query->result_array() as $ordersInfo_ref_result) {
					$address_id4 = $ordersInfo_ref_result['address_id'];
					$this->db->where('address_id', $address_id4);
					$query_address4 = $this->db->get('addressbook');
					$address_row4 = $query_address4->row_array();
					$data['orders'] .= 'Orders sent to ' . $address_row4['displayname'] . '<br />';
					$data['orders'] .= $address_row4['street_address1'] . '<br />';
					if ($address_row4['street_address2'] != '') {
						$data['orders'] .= $address_row4['street_address2'] . '<br />';
					}
					$data['orders'] .= $address_row4['city'] . ', ' . $address_row4['state'] . ' ' . $address_row4['zip'] . '<br />';
					$data['orders'] .= $address_row4['phone'] . '<br />';
				}
			}
			$data['orders'] .= '</p>';
		} else {
			$data['orders'] = '';
		}		
		$rxInfo = $this->encounters_model->getRX($eid)->row();
		if ($rxInfo) {
			$data['rx'] = '<br><h4>Prescriptions and Immunizations:</h4><p class="view">';
			if ($rxInfo->rx_rx!= '') {
				$data['rx'] .= '<strong>Medications: </strong>';
				$data['rx'] .= nl2br($rxInfo->rx_orders_summary);
				$data['rx'] .= '<br /><br />';
			}
			if ($rxInfo->rx_supplements!= '') {
				$data['rx'] .= '<strong>Supplements to take: </strong>';
				$data['rx'] .= nl2br($rxInfo->rx_supplements_orders_summary);
				$data['rx'] .= '<br /><br />';
			}
			if ($rxInfo->rx_immunizations != '') {
				$data['rx'] .= '<strong>Immunizations: </strong>';
				$data['rx'] .= 'CDC Vaccine Information Sheets given for each immunization and consent obtained.<br />';
				$data['rx'] .= nl2br($rxInfo->rx_immunizations);
				$data['rx'] .= '<br /><br />';
			}
			$data['rx'] .= '</p>';
		} else {
			$data['rx'] = '';
		}		
		$planInfo = $this->encounters_model->getPlan($eid)->row();
		if ($planInfo) {
			$data['plan'] = '<br><h4>Plan:</h4><p class="view">';
			if ($planInfo->plan!= '') {
				$data['plan'] .= '<strong>Recommendations: </strong>';
				$data['plan'] .= nl2br($planInfo->plan);
				$data['plan'] .= '<br /><br />';
			}
			if ($planInfo->followup != '') {
				$data['plan'] .= '<strong>Followup: </strong>';
				$data['plan'] .= nl2br($planInfo->followup);
				$data['plan'] .= '<br /><br />';
			}
			$data['plan'] .= '</p>';
		} else {
			$data['plan'] = '';
		}		
		$practice = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row();
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip . '<br />';
		$data['patientInfo'] = $this->demographics_model->get($pid)->row();
		$dob1 = human_to_unix($data['patientInfo']->DOB);
		$data['dob'] = date('m/d/Y', $dob1);
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$dos1 = human_to_unix($encounterInfo->encounter_DOS);
		$data['encounter_DOS'] = date('F jS, Y', $dos1);
		$data['encounter_provider'] = $encounterInfo->encounter_provider;
		$query1 = $this->chart_model->getActiveInsurance($pid);
		$data['insuranceInfo'] = '';
		if ($query1->num_rows() > 0) {
			foreach ($query1->result_array() as $row) {
				$data['insuranceInfo'] .= $row['insurance_plan_name'] . '; ID: ' . $row['insurance_id_num'] . '; Group: ' . $row['insurance_group'] . '; ' . $row['insurance_insu_lastname'] . ', ' . $row['insurance_insu_firstname'] . '<br><br>';
			}
		}
		return $this->load->view('auth/pages/provider/chart/instruction_page',$data, TRUE);
	}
	
	function print_orders()
	{
		ini_set('memory_limit','196M');
		$eid = $this->session->userdata('eid');
		$html = $this->page_orders1($eid);
		if (file_exists('/var/www/nosh/orders.pdf')) {
			unlink('/var/www/nosh/orders.pdf');
		}
		$file_path = '/var/www/nosh/orders.pdf';
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Generated by NOSH EMR');
		$this->mpdf->debug = true;
		$this->mpdf->showImageErrors = true;
		$this->mpdf->Output($file_path,'F');
		while(!file_exists($file_path)) {
			sleep(2);
		}
		if ($fp = fopen ($file_path, "r")) {
			$file_info = pathinfo($file_path);
			$file_name = $file_info["basename"];
			$file_size = filesize($file_path);
			$file_extension = strtolower($file_info["extension"]);	 
			if($file_extension!='pdf') {
				die('LOGGED! bad extension');
			}
			ob_start();
			header('Content-type: application/pdf');
			header('Content-Disposition: attachment; filename="'.$file_name.'"');
			header("Content-length: $file_size");
			ob_end_flush(); 
			while(!feof($fp)) {
				$file_buffer = fread($fp, 2048);
				echo $file_buffer;
			}
			fclose($fp);
			exit();
		} else {
			die('LOGGED! bad file '.$file_path);
		}
	}

	// --------------------------------------------------------------------

	function billing()
	{
		$eid = $this->session->userdata('eid');
		$pid = $this->session->userdata('pid');
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row();
		$dob1 = human_to_unix($row->DOB);
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$dos1 = human_to_unix($encounterInfo->encounter_DOS);
		$agediff = $dos1- $dob1;
		if ($agediff < 31556926) {
			$data['prevent_established'] = '99391';
			$data['prevent_new'] = '99381';
		}
		if ($agediff >= 31556926 && $agediff < 157784630) {
			$data['prevent_established'] = '99392';
			$data['prevent_new'] = '99382';
		}
		if ($agediff >= 157784630 && $agediff < 378683112) {
			$data['prevent_established'] = '99393';
			$data['prevent_new'] = '99383';
		}
		if ($agediff >= 378683112 && $agediff < 568024668) {
			$data['prevent_established'] = '99394';
			$data['prevent_new'] = '99384';
		}
		if ($agediff >= 568024668 && $agediff < 1262277040) {
			$data['prevent_established'] = '99395';
			$data['prevent_new'] = '99385';
		}
		if ($agediff >= 1262277040 && $agediff < 2051200190) {
			$data['prevent_established'] = '99396';
			$data['prevent_new'] = '99386';
		}
		if ($agediff >= 2051200190) {
			$data['prevent_established'] = '99397';
			$data['prevent_new'] = '99387';
		}
		$this->load->view('auth/pages/provider/chart/encounters/billing', $data);
	}
	
	function get_billing()
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getAssessment($eid);
		if ($query->num_rows() > 0) {
			$data = $query->row_array();
			$data1['message'] = "OK";
			if ($data['assessment_1'] != '') {
				$data1['1'] = $data['assessment_1'];
			} else {
				$data1['message'] = "No diagnoses available.";
			}
			if ($data['assessment_2'] != '') {
				$data1['2'] = $data['assessment_2'];
			}
			if ($data['assessment_3'] != '') {
				$data1['3'] = $data['assessment_3'];
			}
			if ($data['assessment_4'] != '') {
				$data1['4'] = $data['assessment_4'];
			}
			if ($data['assessment_5'] != '') {
				$data1['5'] = $data['assessment_5'];
			}
			if ($data['assessment_6'] != '') {
				$data1['6'] = $data['assessment_6'];
			}
			if ($data['assessment_7'] != '') {
				$data1['7'] = $data['assessment_7'];
			}
			if ($data['assessment_8'] != '') {
				$data1['8'] = $data['assessment_8'];
			}
		} else {
			$data1['message'] = "No diagnoses available.";
		}
		echo json_encode($data1);
		exit ( 0 );
	}
	
	function get_billing1($icd_pointer)
	{
		$eid = $this->session->userdata('eid');
		$query = $this->encounters_model->getAssessment($eid);
		$data = $query->row_array();
		$icd_pointer_name = "assessment_" . $icd_pointer;
		echo $data[$icd_pointer_name];
	}
	
	function get_billing_dos()
	{
		$eid = $this->session->userdata('eid');
		$this->db->select('encounter_DOS');
		$this->db->where('eid', $eid);
		$row = $this->db->get('encounters')->row_array();
		$timestamp = mysql_to_unix($row['encounter_DOS']);
		$date = date('m/d/Y', $timestamp);
		echo $date;
		exit (0);
	}
	
	function get_insurance_id()
	{
		$eid = $this->session->userdata('eid');
		$query= $this->encounters_model->getBilling($eid);
		$data = $query->row_array();
		echo json_encode($data);
		exit ( 0 );
	}
	
	function get_insurance_info()
	{
		$insurance_id_1 = $this->input->post('insurance_id_1');
		$insurance_id_2 = $this->input->post('insurance_id_2');
		if ($insurance_id_1 != '') {
			if ($insurance_id_1 == '0') {
				$arr['result1'] = 'Self pay; no insurance.';
				$arr['result2'] = 'None chosen.';
			} else {
				$this->db->where('insurance_id', $insurance_id_1);
				$query1 = $this->db->get('insurance');
				$result1 = $query1->row_array();
				if ($query1->num_rows() > 0) {
					$arr['result1'] = $result1['insurance_plan_name'];
				} else {
					$arr['result1'] = 'None chosen.';
				}	
			}	
		} else {
			$arr['result1'] = 'None chosen.';
		}
		if ($insurance_id_2 != '') {
			$this->db->where('insurance_id', $insurance_id_2);
			$query2 = $this->db->get('insurance');
			$result2 = $query2->row_array();
			if ($query2->num_rows() > 0) {
				$arr['result2'] = $result2['insurance_plan_name'];
			} else {
				$arr['result2'] = 'None chosen.';
			}	
		} else {
			$arr['result2'] = 'None chosen.';
		}
		echo json_encode($arr);
		exit ( 0 );
	}
	
	function compile_billing()
	{
		$eid = $this->session->userdata('eid');
		$pid = $this->session->userdata('pid');
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row();
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$dos1 = human_to_unix($encounterInfo->encounter_DOS);
		$dos = date('mdY', $dos1);
		$dos2 = date('m/d/Y', $dos1);
		$pos = $encounterInfo->encounter_location;
		$assessment_data= $this->encounters_model->getAssessment($eid)->row_array();
		$icd_pointer = '';
		if ($assessment_data['assessment_1'] != '') {
			$icd_pointer .= "1";
		}
		if ($assessment_data['assessment_2'] != '') {
			$icd_pointer .= "2";
		}
		if ($assessment_data['assessment_3'] != '') {
			$icd_pointer .= "3";
		}
		if ($assessment_data['assessment_4'] != '') {
			$icd_pointer .= "4";
		}
		$labsInfo = $this->encounters_model->getLabs($eid)->row();
		if ($labsInfo) {
			if ($labsInfo->labs_ua_urobili != '' || $labsInfo->labs_ua_bilirubin != '' || $labsInfo->labs_ua_ketones != '' || $labsInfo->labs_ua_glucose != '' || $labsInfo->labs_ua_protein != '' || $labsInfo->labs_ua_nitrites != '' || $labsInfo->labs_ua_leukocytes != '' || $labsInfo->labs_ua_blood != '' || $labsInfo->labs_ua_ph != '' || $labsInfo->labs_ua_spgr != '' || $labsInfo->labs_ua_color != '' || $labsInfo->labs_ua_clarity != ''){
				$cpt3 = '81002';
				$this->db->where('cpt', $cpt3);
				$this->db->where('eid', $eid);
				if ($this->db->get('billing_core')->num_rows() == 0) {
					$this->db->where('cpt', $cpt3);
					$this->db->where('practice_id', $this->session->userdata('practice_id'));
					$query3 = $this->db->get('cpt_relate');
					$result3 = $query3->row_array();
					$cpt_charge3 = $result3['cpt_charge'];
					$cpt3 = array(
						'cpt' => '81002',
						'cpt_charge' => $cpt_charge3,
						'eid' => $eid,
						'pid' => $pid,
						'dos_f' => $dos2,
						'dos_t' => $dos2,
						'payment' => '0',
						'icd_pointer' => $icd_pointer,
						'unit' => '1',
						'billing_group' => '1',
						'modifier' => '',
						'practice_id' => $this->session->userdata('practice_id')
					);
					$this->db->insert('billing_core', $cpt3);
					$this->audit_model->add();
				}
			}
			if ($labsInfo->labs_upt != '') {
				$cpt4 = '81025';
				$this->db->where('cpt', $cpt4);
				$this->db->where('eid', $eid);
				if ($this->db->get('billing_core')->num_rows() == 0) {
					$this->db->where('cpt', $cpt4);
					$this->db->where('practice_id', $this->session->userdata('practice_id'));
					$query4 = $this->db->get('cpt_relate');
					$result4 = $query4->row_array();
					$cpt_charge4 = $result4['cpt_charge'];
					$cpt4 = array(
						'cpt' => '81025',
						'cpt_charge' => $cpt_charge4,
						'eid' => $eid,
						'pid' => $pid,
						'dos_f' => $dos2,
						'dos_t' => $dos2,
						'payment' => '0',
						'icd_pointer' => $icd_pointer,
						'unit' => '1',
						'billing_group' => '1',
						'modifier' => '',
						'practice_id' => $this->session->userdata('practice_id')
					);
					$this->db->insert('billing_core', $cpt4);
					$this->audit_model->add();
				}
			} 
			if ($labsInfo->labs_strep != '') {
				$cpt5 = '87880';
				$this->db->where('cpt', $cpt5);
				$this->db->where('eid', $eid);
				if ($this->db->get('billing_core')->num_rows() == 0) {
					$this->db->where('cpt', $cpt5);
					$this->db->where('practice_id', $this->session->userdata('practice_id'));
					$query5 = $this->db->get('cpt_relate');
					$result5 = $query5->row_array();
					$cpt_charge5 = $result5['cpt_charge'];
					$cpt5 = array(
						'cpt' => '87880',
						'cpt_charge' => $cpt_charge5,
						'eid' => $eid,
						'pid' => $pid,
						'dos_f' => $dos2,
						'dos_t' => $dos2,
						'payment' => '0',
						'icd_pointer' => $icd_pointer,
						'unit' => '1',
						'billing_group' => '1',
						'modifier' => '',
						'practice_id' => $this->session->userdata('practice_id')
					);
					$this->db->insert('billing_core', $cpt5);
					$this->audit_model->add();
				}
			} 
			if ($labsInfo->labs_mono != '') {
				$cpt6 = '86308';
				$this->db->where('cpt', $cpt6);
				$this->db->where('eid', $eid);
				if ($this->db->get('billing_core')->num_rows() == 0) {
					$this->db->where('cpt', $cpt6);
					$this->db->where('practice_id', $this->session->userdata('practice_id'));
					$query6 = $this->db->get('cpt_relate');
					$result6 = $query6->row_array();
					$cpt_charge6 = $result6['cpt_charge'];
					$cpt6 = array(
						'cpt' => '86308',
						'cpt_charge' => $cpt_charge6,
						'eid' => $eid,
						'pid' => $pid,
						'dos_f' => $dos2,
						'dos_t' => $dos2,
						'payment' => '0',
						'icd_pointer' => $icd_pointer,
						'unit' => '1',
						'billing_group' => '1',
						'modifier' => '',
						'practice_id' => $this->session->userdata('practice_id')
					);
					$this->db->insert('billing_core', $cpt6);
					$this->audit_model->add();
				}
			}
			if ($labsInfo->labs_flu != '') {
				$cpt7 = '87804';
				$this->db->where('cpt', $cpt7);
				$this->db->where('eid', $eid);
				if ($this->db->get('billing_core')->num_rows() == 0) {
					$this->db->where('cpt', $cpt7);
					$this->db->where('practice_id', $this->session->userdata('practice_id'));
					$query7 = $this->db->get('cpt_relate');
					$result7 = $query7->row_array();
					$cpt_charge7 = $result7['cpt_charge'];
					$cpt7 = array(
						'cpt' => '87804',
						'cpt_charge' => $cpt_charge7,
						'eid' => $eid,
						'pid' => $pid,
						'dos_f' => $dos2,
						'dos_t' => $dos2,
						'payment' => '0',
						'icd_pointer' => $icd_pointer,
						'unit' => '1',
						'billing_group' => '1',
						'modifier' => '',
						'practice_id' => $this->session->userdata('practice_id')
					);
					$this->db->insert('billing_core', $cpt7);
					$this->audit_model->add();
				}
			}
			if ($labsInfo->labs_glucose != '') {
				$cpt8 = '82962';
				$this->db->where('cpt', $cpt8);
				$this->db->where('eid', $eid);
				if ($this->db->get('billing_core')->num_rows() == 0) {
					$this->db->where('cpt', $cpt8);
					$this->db->where('practice_id', $this->session->userdata('practice_id'));
					$query8 = $this->db->get('cpt_relate');
					$result8 = $query8->row_array();
					$cpt_charge8 = $result8['cpt_charge'];
					$cpt9 = array(
						'cpt' => '82962',
						'cpt_charge' => $cpt_charge8,
						'eid' => $eid,
						'pid' => $pid,
						'dos_f' => $dos2,
						'dos_t' => $dos2,
						'payment' => '0',
						'icd_pointer' => $icd_pointer,
						'unit' => '1',
						'billing_group' => '1',
						'modifier' => '',
						'practice_id' => $this->session->userdata('practice_id')
					);
					$this->db->insert('billing_core', $cpt9);
					$this->audit_model->add();
				}
			}
		}
		$query9= $this->encounters_model->getProcedure($eid);
		if ($query9->num_rows() > 0) {
			$result9 = $query9->row_array();
			$this->db->where('cpt', $result9['proc_cpt']);
			$this->db->where('eid', $eid);
			if ($this->db->get('billing_core')->num_rows() == 0) {
				$this->db->where('cpt', $result9['proc_cpt']);
				$this->db->where('practice_id', $this->session->userdata('practice_id'));
				$query10 = $this->db->get('cpt_relate');
				if ($query10->num_rows() > 0) {
					$result10 = $query10->row_array();
					$cpt_charge10 = $result10['cpt_charge'];
					$cpt10 = array(
						'cpt' => $result9['proc_cpt'],
						'cpt_charge' => $cpt_charge10,
						'eid' => $eid,
						'pid' => $pid,
						'dos_f' => $dos2,
						'dos_t' => $dos2,
						'payment' => '0',
						'icd_pointer' => $icd_pointer,
						'unit' => '1',
						'billing_group' => '1',
						'modifier' => '',
						'practice_id' => $this->session->userdata('practice_id')
					);
					$this->db->insert('billing_core', $cpt10);
					$this->audit_model->add();
				}
			}
		}
		$this->db->where('eid', $eid);
		$query11 = $this->db->get('immunizations');
		if ($query11->num_rows() > 0) {
			$result11 = $query11->result_array();
			foreach ($result11 as $row11) {
				$this->db->where('cpt', $row11['cpt']);
				$this->db->where('eid', $eid);
				if ($this->db->get('billing_core')->num_rows() == 0) {
					$this->db->where('cpt', $row11['cpt']);
					$this->db->where('practice_id', $this->session->userdata('practice_id'));
					$query12 = $this->db->get('cpt_relate');
					$result12 = $query12->row_array();
					if ($query12->num_rows() > 0) {
						$cpt_charge12 = $result12['cpt_charge'];
					} else {
						$cpt_charge12 = '0';
					}
					$cpt12 = array(
						'cpt' => $row11['cpt'],
						'cpt_charge' => $cpt_charge12,
						'eid' => $eid,
						'pid' => $pid,
						'dos_f' => $dos2,
						'dos_t' => $dos2,
						'payment' => '0',
						'icd_pointer' => $icd_pointer,
						'unit' => '1',
						'billing_group' => '1',
						'modifier' => '',
						'practice_id' => $this->session->userdata('practice_id')
					);
					$this->db->insert('billing_core', $cpt12);
					$this->audit_model->add();
				}
			}	
		}
		echo 'CPT codes complied from the encounter!';
		exit( 0 );
	}
	
	function procedure_codes()
	{
		$eid = $this->session->userdata('eid');		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = 'billing_core.' . $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT billing_core.*, cpt_relate.cpt_description FROM billing_core JOIN cpt_relate ON billing_core.cpt=cpt_relate.cpt WHERE billing_core.eid=$eid");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT billing_core.*, cpt_relate.cpt_description FROM billing_core JOIN cpt_relate ON billing_core.cpt=cpt_relate.cpt WHERE billing_core.eid=$eid ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function define_cpt()
	{
		$cpt = $this->input->post('cpt');
		$this->db->where('cpt', $cpt);
		$this->db->select('cpt, cpt_description');
		$query = $this->db->get('cpt');
		$result = $query->row_array();
		$arr['item'] = $result['cpt'] . ' - ' . $result['cpt_description'];
		echo json_encode($arr);
		exit( 0 );
	}
	
	function define_icd()
	{
		$icd = $this->input->post('icd');
		$eid = $this->session->userdata('eid');
		$icd_array = str_split($icd);
		$arr['item'] = '';
		foreach ($icd_array as $icd1) {
			if ($icd1) {
				$name = 'assessment_' . $icd1;
				$this->db->where('eid', $eid);
				$this->db->select($name);
				$query = $this->db->get('assessment');
				$result = $query->row_array();
				$arr['item'] .= 'Diagnosis ' . $icd1 . ': ' . $result[$name] . '<br>';
			}
		}
		echo json_encode($arr);
		exit( 0 );
	}
	
	function remove_cpt()
	{
		$billing_core_id = $this->input->post("billing_core_id");
		$this->encounters_model->deleteBillingCore1($billing_core_id);
		$this->audit_model->delete();
		$result = "Row deleted.";
		echo $result;
		exit( 0 );
	}
	
	function get_cpt_charge()
	{
		$cpt = $this->input->post('cpt');
		$this->db->where('cpt', $cpt);
		$this->db->select('cpt_charge');
		$query = $this->db->get('cpt');
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$arr = $result['cpt_charge'];
		} else {
			$arr = '';
		}
		echo $arr;
		exit( 0 );
	}
	
	function update_cpt_charge()
	{
		$data = array(
			'cpt_charge' => $this->input->post('cpt_charge')
		);
		$cpt = $this->input->post('cpt');
		$this->db->where('cpt', $cpt);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$query = $this->db->get('cpt_relate');
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$this->db->where('cpt_relate_id', $row['cpt_relate_id']);
			$this->db->update('cpt_relate', $data);
			$this->audit_model->update();
		} else {
			$this->db->where('cpt', $cpt);
			$row1 = $this->db->get('cpt')->row_array();
			$data['cpt_description'] = $row1['cpt_description'];
			$data['cpt'] = $row1['cpt'];
			$data['practice_id'] = $this->session->userdata('practice_id');
			$this->db->insert('cpt_relate', $data);
			$this->audit_model->add();
		}
		echo 'CPT charge updated!';
		exit( 0 );
	}
	
	function billing_save()
	{
		$eid = $this->session->userdata('eid');
		if ($eid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$pid = $this->session->userdata('pid');
		$id = $this->input->post('billing_core_id');
		$this->db->where('billing_core_id', $id);
		$query = $this->db->get('billing_core');
		$count = $query->num_rows();
		$icd_array = $this->input->post('icd_pointer');
		$billing_group = "1";
		if (in_array("5", $icd_array)) {
			$billing_group = "2";
		}
		if (in_array("6", $icd_array)) {
			$billing_group = "2";
		}
		if (in_array("7", $icd_array)) {
			$billing_group = "2";
		}
		if (in_array("8", $icd_array)) {
			$billing_group = "2";
		}
		$icd_pointer = implode("", $icd_array);
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'cpt' => $this->input->post('cpt'),
			'cpt_charge' => $this->input->post('cpt_charge'),
			'icd_pointer' => $icd_pointer,
			'unit' => $this->input->post('unit'),
			'modifier' => $this->input->post('modifier'),
			'dos_f' => $this->input->post('dos_f'),
			'dos_t' => $this->input->post('dos_t'),
			'payment' => '0',
			'billing_group' => $billing_group,
			'practice_id' => $this->session->userdata('practice_id')
		);
							
		if ($count > 0) {		
			$this->encounters_model->updateBillingCore($id, $data);
			$this->audit_model->update();
			$result = 'Billing Updated';
		} else {
			$this->encounters_model->addBillingCore($data);
			$this->audit_model->add();
			$result = 'Billing Added';
		}
		echo $result;
	}
	
	function string_format($str, $len)
	{
		if (strlen((string)$str) < $len) {
			$str1 = str_pad((string)$str, $len);
		} else {
			$str1 = substr((string)$str, 0, $len);
		}
		$str1 = strtoupper((string)$str1);
		return $str1;
	}
	
	function billing_save_common($insurance_id_1, $insurance_id_2, $bill_complex='')
	{
		$eid = $this->session->userdata('eid');
		$this->encounters_model->deleteBilling($eid);
		$this->audit_model->delete();
		$pid = $this->session->userdata('pid');
		$practiceInfo = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row();
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row();
		if ($insurance_id_1 == '0' || $insurance_id_1 == '') {
			$data0 = array(
				'eid' => $eid,
				'pid' => $pid,
				'insurance_id_1' => $insurance_id_1,
				'insurance_id_2' => $insurance_id_2,
				'bill_complex' => $bill_complex
			);
			$this->encounters_model->addBilling($data0);
			$this->audit_model->add();
			$data_encounter = array(
				'bill_submitted' => 'Done'
			);
			$this->encounters_model->updateEncounter($eid, $data_encounter);
			$this->audit_model->update();
			return 'Billing Saved!';
		 	exit ( 0 );
		}
		$data_encounter = array(
			'bill_submitted' => 'No'
		);
		$this->encounters_model->updateEncounter($eid, $data_encounter);
		$this->audit_model->update();
		$this->db->where('insurance_id', $insurance_id_1);
		$query1 = $this->db->get('insurance');
		$result1 = $query1->row_array();
		$bill_Box11C = $result1['insurance_plan_name'];
		$bill_Box11C = $this->string_format($bill_Box11C, 29);
		$bill_Box1A = $result1['insurance_id_num'];
		$bill_Box1A = $this->string_format($bill_Box1A, 29);
		$bill_Box4 = $result1['insurance_insu_lastname'] . ', ' . $result1['insurance_insu_firstname'];
		$bill_Box4 = $this->string_format($bill_Box4, 29);
		$this->db->where('address_id', $result1['address_id']);
		$query2 = $this->db->get('addressbook');
		$result2 = $query2->row_array();
		if ($result2['insurance_plan_type'] == 'Medicare') {
			$bill_Box1 = "X                                            ";
			$bill_Box1P = 'Medicare';
		}
		if ($result2['insurance_plan_type'] == 'Medicaid') {
			$bill_Box1 = "       X                                     ";
			$bill_Box1P = 'Medicaid';
		}
		if ($result2['insurance_plan_type'] == 'Tricare') {
			$bill_Box1 = "              X                              ";
			$bill_Box1P = 'Tricare';
		}
		if ($result2['insurance_plan_type'] == 'ChampVA') {
			$bill_Box1 = "                     X                       ";
			$bill_Box1P = 'ChampVA';
		}
		if ($result2['insurance_plan_type'] == 'Group Health Plan') {
			$bill_Box1 = "                            X                ";
			$bill_Box1P = 'Group Health Plan';
		}
		if ($result2['insurance_plan_type'] == 'FECA') {
			$bill_Box1 = "                                   X         ";
			$bill_Box1P = 'FECA';
		}
		if ($result2['insurance_plan_type'] == 'Other') {
			$bill_Box1 = "                                            X";
			$bill_Box1P = 'Other';
		}
		$bill_payor_id = $result2['insurance_plan_payor_id'];
		$bill_payor_id = $this->string_format($bill_payor_id, 5);
		$bill_ins_add1 = $result2['street_address1'];
		$bill_ins_add1 = $this->string_format($bill_ins_add1, 29);
		if ($result2['street_address2'] == '') {
			$bill_ins_add2 = $result2['city'] . ', ' . $result2['state'] . ' ' . $result2['zip'];
		} else {
			$bill_ins_add2 = $result2['street_address2'];
		}
		$bill_ins_add2 = $this->string_format($bill_ins_add2, 29);
		if ($result2['insurance_plan_assignment'] == 'Yes') {
			$bill_Box27 = "X     ";
			$bill_Box27P = "Yes";
		} else {
			$bill_Box27 = "     X";
			$bill_Box27P = "No";
		}
		if ($result1['insurance_relationship'] == 'Self') {
			$bill_Box6 = "X              ";
			$bill_Box6P = "SelfBox6";
		}
		if ($result1['insurance_relationship'] == 'Spouse') {
			$bill_Box6 = "     X         ";
			$bill_Box6P = "Spouse";
		}
		if ($result1['insurance_relationship'] == 'Child') {
			$bill_Box6 = "         X     ";
			$bill_Box6P = "Child";
		}
		if ($result1['insurance_relationship'] == 'Other') {
			$bill_Box6 = "              X";
			$bill_Box6P = "Other";
		}
		$bill_Box7A = $result1['insurance_insu_address'];
		$bill_Box7A = $this->string_format($bill_Box7A, 29);
		$bill_Box7B = $result1['insurance_insu_city'];
		$bill_Box7B = $this->string_format($bill_Box7B, 23);
		$bill_Box7C = $result1['insurance_insu_state'];
		$bill_Box7C = $this->string_format($bill_Box7C, 4);
		$bill_Box7D = $result1['insurance_insu_zip'];
		$bill_Box7D = $this->string_format($bill_Box7D, 12);
		$bill_Box11 = $result1['insurance_group'];
		$bill_Box11 = $this->string_format($bill_Box11, 29);
		$bill_Box11A1 = human_to_unix($result1['insurance_insu_dob']);
		$bill_Box11A1 = date('m d Y', $bill_Box11A1);
		if ($result1['insurance_insu_gender'] == 'm') {
			$bill_Box11A2 = "X       ";
			$bill_Box11A2P = 'M';
		} else {
			$bill_Box11A2 = "       X";
			$bill_Box11A2P = 'F';
		}
		if ($insurance_id_2 == '' || $insurance_id_2 == '0') {
			$bill_Box9D = '';
			$bill_Box9 = '';
			$bill_Box9A = '';
			$bill_Box9B1 = '          ';
			$bill_Box9B2 = '       ';
			$bill_Box9B2P = '';
			$bill_Box9C = "";
			$bill_Box11D = '     X';
			$bill_Box11DP = 'No';
		} else {
			$this->db->where('insurance_id', $insurance_id_2);
			$query3 = $this->db->get('insurance');
			$result3 = $query3->row_array();
			$bill_Box9D = $result3['insurance_plan_name'];
			$bill_Box9 = $result3['insurance_insu_lastname'] . ', ' . $result3['insurance_insu_firstname'];
			$bill_Box9A = $result3['insurance_group'];
			$bill_Box9B1 = human_to_unix($result3['insurance_insu_dob']);
			$bill_Box9B1 = date('m d Y', $bill_Box9B1);
			if ($result3['insurance_insu_gender'] == 'm') {
				$bill_Box9B2 = "X      ";
				$bill_Box9B2P = 'M';
			} else {
				$bill_Box9B2 = "      X";
				$bill_Box9B2P = 'F';
			}
			$bill_Box11D = 'X     ';
			$bill_Box11DP = 'Yes';
			if ($row->employer != '') {
				$bill_Box9C = $row->employer;
			} else {
				$bill_Box9C = "";
			}
		}
		$bill_Box9D = $this->string_format($bill_Box9D, 28);
		$bill_Box9 = $this->string_format($bill_Box9, 28);
		$bill_Box9A = $this->string_format($bill_Box9A, 28);
		$bill_Box9C = $this->string_format($bill_Box9C, 28);
		$bill_Box2 = $row->lastname . ', ' . $row->firstname;
		$bill_Box2 = $this->string_format($bill_Box2, 28);
		$bill_Box3A = human_to_unix($row->DOB);
		$bill_Box3A = date('m d Y', $bill_Box3A);
		if ($row->sex == 'm') {
			$bill_Box3B = "X     ";
			$bill_Box3BP = 'M';
		} else {
			$bill_Box3B = "     X";
			$bill_Box3BP = 'F';
		}
		if ($row->marital_status == 'Single') {
			$bill_Box8A = "X            ";
			$bill_Box8AP = 'SingleBox8';
		} else {
			if ($row->marital_status == 'Married') {
				$bill_Box8A = "      X      ";
				$bill_Box8AP = 'Married';
			} else {
				$bill_Box8A = "            X";
				$bill_Box8AP = 'Other';
			}
		}
		if ($row->employer != '') {
			$bill_Box8B = "X            ";
			$bill_Box8BP = "EmployedBox8";
			$bill_Box11B = $row->employer;
		} else {
			$bill_Box8B = "             ";
			$bill_Box8BP = "";
			$bill_Box11B = "";
		}
		$bill_Box11B = $this->string_format($bill_Box11B, 29);
		$bill_Box5A = $row->address;
		$bill_Box5A = $this->string_format($bill_Box5A, 28);
		$bill_Box5B = $row->city;
		$bill_Box5B = $this->string_format($bill_Box5B, 24);
		$bill_Box5C = $row->state;
		$bill_Box5C = $this->string_format($bill_Box5C, 3);
		$bill_Box5D = $row->zip;
		$bill_Box5D = $this->string_format($bill_Box5D, 12);
		$bill_Box5E = $row->phone_home;
		$bill_Box5E = $this->string_format($bill_Box5E, 14);
		$bill_Box10 = $encounterInfo->encounter_condition;
		$bill_Box10 = $this->string_format($bill_Box10, 19);
		$work = $encounterInfo->encounter_condition_work;
		if ($work == 'Yes') {
			$bill_Box10A = "X      ";
			$bill_Box10AP = 'Yes';
		} else {
			$bill_Box10A = "      X";
			$bill_Box10AP = 'No';
		}
		$auto = $encounterInfo->encounter_condition_auto;
		if ($auto == 'Yes') {
			$bill_Box10B1 = "X      ";
			$bill_Box10B1P = 'Yes';
			$bill_Box10B2 = $encounterInfo->encounter_condition_auto_state;	
		} else {
			$bill_Box10B1 = "      X";
			$bill_Box10B1P = 'No';
			$bill_Box10B2 = "";
		}
		$bill_Box10B2 = $this->string_format($bill_Box10B2, 3);
		$other = $encounterInfo->encounter_condition_other;
		if ($other == 'Yes') {
			$bill_Box10C = "X      ";
			$bill_Box10CP = "Yes";
		} else {
			$bill_Box10C = "      X";
			$bill_Box10CP = 'No';
		}
		$provider = $encounterInfo->encounter_provider;
		$this->db->select('id');
		$this->db->where('displayname', $provider);
		$user_id = $this->db->get('users')->row()->id;
		$this->db->select('npi');
		$this->db->where('id', $user_id);
		$query4 = $this->db->get('providers');
		$result4 = $query4->row();
		$npi = $result4->npi;
		if ($encounterInfo->referring_provider != 'Primary Care Provider' || $encounterInfo->referring_provider != '') {
			$bill_Box17 = $this->string_format($encounterInfo->referring_provider, 26);
			$bill_Box17A = $this->string_format($encounterInfo->referring_provider_npi, 17);
		} else {
			if ($encounterInfo->referring_provider != 'Primary Care Provider') {
				$bill_Box17 = $this->string_format('', 26);
				$bill_Box17A = $this->string_format('', 17);
			} else {
				$bill_Box17 = $this->string_format($provider, 26);
				$bill_Box17A = $this->string_format($npi, 17);
			}
		}
		if ($result2['insurance_box_31'] == 'n') {
			$bill_Box31 = $this->string_format($provider, 21);
		} else {
			$this->db->where('id', $encounterInfo->user_id);
			$provider2 = $this->db->get('users')->row_array();
			$provider2a = $provider2['lastname'] . ", " . $provider2['firstname'];
			$bill_Box31 = $this->string_format($provider2a, 21);
		}
		$bill_Box33B = $this->string_format($provider, 29);
		$pos = $encounterInfo->encounter_location;
		$bill_Box25 = $practiceInfo->tax_id;
		$bill_Box25 = $this->string_format($bill_Box25, 15);
		$bill_Box26 = $this->string_format($pid, 14);	
		$bill_Box32A = $practiceInfo->practice_name;
		$bill_Box32A = $this->string_format($bill_Box32A, 26);
		$bill_Box32B = $practiceInfo->street_address1;
		if ($practiceInfo->street_address2 != '') {
			$bill_Box32B .= ', ' . $practiceInfo->street_address2;
		}
		$bill_Box32B = $this->string_format($bill_Box32B, 26);
		$bill_Box32C = $practiceInfo->city . ', ' . $practiceInfo->state . ' ' . $practiceInfo->zip;
		$bill_Box32C = $this->string_format($bill_Box32C, 26);
		if ($result2['insurance_box_32a'] == 'n') {
			$bill_Box32D = $practiceInfo->npi;
		} else {
			$this->db->where('id', $encounterInfo->user_id);
			$provider3 = $this->db->get('providers')->row_array();
			$bill_Box32D = $provider3['npi'];
		}
		$bill_Box32D = $this->string_format($bill_Box32D, 10);
		$bill_Box33A = $practiceInfo->phone;
		$bill_Box33A = $this->string_format($bill_Box33A, 14);
		$bill_Box33C = $practiceInfo->billing_street_address1;
		if ($practiceInfo->billing_street_address2 != '') {
			$bill_Box33C .= ', ' . $practiceInfo->billing_street_address2;
		}
		$bill_Box33C = $this->string_format($bill_Box33C, 29);
		$bill_Box33D = $practiceInfo->billing_city . ', ' . $practiceInfo->billing_state . ' ' . $practiceInfo->billing_zip;
		$bill_Box33D = $this->string_format($bill_Box33D, 29);
		$this->db->where('eid', $eid);
		$this->db->where('billing_group', '1');
		$this->db->not_like('cpt','sp','after');
		$this->db->order_by('cpt_charge', 'desc'); 
		$query5 = $this->db->get('billing_core');
		$result5 = $query5->result_array();
		$num_rows5 = $query5->num_rows();
		if ($num_rows5 > 0) {
			$query6= $this->encounters_model->getAssessment($eid);
			$result6 = $query6->row_array();
			$bill_Box21_1 = $this->string_format($result6['assessment_icd1'], 8);
			$bill_Box21_2 = $this->string_format($result6['assessment_icd2'], 8);
			$bill_Box21_3 = $this->string_format($result6['assessment_icd3'], 8);
			$bill_Box21_4 = $this->string_format($result6['assessment_icd4'], 8);
			$i = 0;
			foreach ($result5 as $key5 => $value5) {
				$cpt_charge5[$key5]  = $value5['cpt_charge'];
			}
			array_multisort($cpt_charge5, SORT_DESC, $result5);
			while ($i < $num_rows5 ) {
				$cpt_final[$i] = $result5[$i];
				$cpt_final[$i]['dos_f'] = str_replace('/', '', $cpt_final[$i]['dos_f']);
				$cpt_final[$i]['dos_f'] = $this->string_format($cpt_final[$i]['dos_f'], 8);
				$cpt_final[$i]['dos_t'] = str_replace('/', '', $cpt_final[$i]['dos_t']);
				$cpt_final[$i]['dos_t'] = $this->string_format($cpt_final[$i]['dos_t'], 8);
				$cpt_final[$i]['pos'] = $this->string_format($pos, 5);
				$cpt_final[$i]['cpt'] = $this->string_format($cpt_final[$i]['cpt'], 6);
				$cpt_final[$i]['modifier'] = $this->string_format($cpt_final[$i]['modifier'], 11);
				$cpt_final[$i]['unit1'] = $cpt_final[$i]['unit'];
				$cpt_final[$i]['unit'] = $this->string_format($cpt_final[$i]['unit'] ,5);
				$cpt_final[$i]['cpt_charge'] = number_format($cpt_final[$i]['cpt_charge'], 2, ' ', '');
				$cpt_final[$i]['cpt_charge1'] = $cpt_final[$i]['cpt_charge'];
				$cpt_final[$i]['cpt_charge'] = $this->string_format($cpt_final[$i]['cpt_charge'], 8);
				$cpt_final[$i]['npi'] = $this->string_format($npi, 11);
				$cpt_final[$i]['icd_pointer'] =  $this->string_format($cpt_final[$i]['icd_pointer'], 4);
				$i++;
			}
			if ($num_rows5 < 6) {
				$array['dos_f'] = $this->string_format('', 8);
				$array['dos_t'] = $this->string_format('', 8);
				$array['pos'] = $this->string_format('', 5);
				$array['cpt'] = $this->string_format('', 6);
				$array['modifier'] = $this->string_format('', 11);
				$array['unit1'] = '0';
				$array['unit'] = $this->string_format('', 5);
				$array['cpt_charge1'] = '0';
				$array['cpt_charge'] = $this->string_format('', 8);
				$array['npi'] = $this->string_format('', 11);
				$array['icd_pointer'] =  $this->string_format('', 4);
				$cpt_final = array_pad($cpt_final, 6, $array);
			}
			$bill_Box28 = $cpt_final[0]['cpt_charge1'] * $cpt_final[0]['unit1'] + $cpt_final[1]['cpt_charge1'] * $cpt_final[1]['unit1'] + $cpt_final[2]['cpt_charge1'] * $cpt_final[2]['unit1'] + $cpt_final[3]['cpt_charge1'] * $cpt_final[3]['unit1'] + $cpt_final[4]['cpt_charge1'] * $cpt_final[4]['unit1'] + $cpt_final[5]['cpt_charge1'] * $cpt_final[5]['unit1'];
			$bill_Box28 = number_format($bill_Box28, 2, ' ', '');
			$bill_Box28 = $this->string_format($bill_Box28, 9);
			$bill_Box29 = $this->string_format('0 00', 8);
			$bill_Box30 = $this->string_format($bill_Box28, 8);
			$data1 = array(
				'eid' 						=> $eid,
				'pid' 						=> $pid,
				'insurance_id_1' 			=> $insurance_id_1,
				'insurance_id_2' 			=> $insurance_id_2,
				'bill_complex'				=> $bill_complex,
				'bill_Box11C' 				=> $bill_Box11C,	//Insurance Plan Name
				'bill_payor_id'				=> $bill_payor_id,
				'bill_ins_add1'				=> $bill_ins_add1,
				'bill_ins_add2'				=> $bill_ins_add2,
				'bill_Box1'					=> $bill_Box1,
				'bill_Box1P'				=> $bill_Box1P,
				'bill_Box1A' 				=> $bill_Box1A, 	//Insured ID Number
				'bill_Box2' 				=> $bill_Box2, 	//Patient Name
				'bill_Box3A' 				=> $bill_Box3A, 	//Patient Date of Birth
				'bill_Box3B' 				=> $bill_Box3B, 	//Patient Sex
				'bill_Box3BP' 				=> $bill_Box3BP,
				'bill_Box4'					=> $bill_Box4, 	//Insured Name
				'bill_Box5A' 				=> $bill_Box5A, 	//Patient Address
				'bill_Box6'					=> $bill_Box6, 	//Patient Relationship to Insured
				'bill_Box6P'				=> $bill_Box6P,
				'bill_Box7A'				=> $bill_Box7A, 	//Insured Address
				'bill_Box5B' 				=> $bill_Box5B, 	//Patient City
				'bill_Box5C' 				=> $bill_Box5C, 	//Patient State
				'bill_Box8A'				=> $bill_Box8A, 	//Patient Marital Status
				'bill_Box8AP'				=> $bill_Box8AP,
				'bill_Box7B'				=> $bill_Box7B, 	//Insured City
				'bill_Box7C'				=> $bill_Box7C, 	//Insured State
				'bill_Box5D'				=> $bill_Box5D,	//Patient Zip
				'bill_Box5E'				=> $bill_Box5E,
				'bill_Box8B'				=> $bill_Box8B,	//Patient Employment
				'bill_Box8BP'				=> $bill_Box8BP,
				'bill_Box7D'				=> $bill_Box7D,	//Insured Zip
				'bill_Box9'					=> $bill_Box9, 	//Other Insured Name
				'bill_Box11'				=> $bill_Box11, 	//Insured Group Number
				'bill_Box9A'				=> $bill_Box9A, 	//Other Insured Group Number
				'bill_Box10'				=> $bill_Box10, 
				'bill_Box10A'				=> $bill_Box10A,	//Condition Employment
				'bill_Box10AP'				=> $bill_Box10AP,	//Condition Employment
				'bill_Box11A1'				=> $bill_Box11A1,	//Insured Date of Birth
				'bill_Box11A2'				=> $bill_Box11A2,	//Insured Sex
				'bill_Box11A2P'				=> $bill_Box11A2P,
				'bill_Box9B1'				=> $bill_Box9B1,	//Other Insured Date of Birth
				'bill_Box9B2'				=> $bill_Box9B2,	//Other Insured Sex
				'bill_Box9B2P'				=> $bill_Box9B2P,
				'bill_Box10B1'				=> $bill_Box10B1,	//Condition Auto Accident
				'bill_Box10B1P'				=> $bill_Box10B1P,	//Condition Auto Accident
				'bill_Box10B2'				=> $bill_Box10B2,	//Condition Auto Accident State
				'bill_Box11B'				=> $bill_Box11B,	//Insured Employer
				'bill_Box9C'				=> $bill_Box9C,	//Other Insured Employer
				'bill_Box10C'				=> $bill_Box10C,	//Condition Other Accident
				'bill_Box10CP'				=> $bill_Box10CP,	//Condition Other Accident
				'bill_Box9D'				=> $bill_Box9D,	//Other Insurance Plan Name
				'bill_Box11D'				=> $bill_Box11D,
				'bill_Box11DP'				=> $bill_Box11DP,	//Other Insurance Plan Exist
				'bill_Box17' 				=> $bill_Box17,	//Provider Use for Box 31 and 33B too
				'bill_Box17A' 				=> $bill_Box17A,	//Provider NPI
				'bill_Box21_1' 				=> $bill_Box21_1,	//ICD1
				'bill_Box21_2' 				=> $bill_Box21_2,	//ICD2
				'bill_Box21_3' 				=> $bill_Box21_3,	//ICD3
				'bill_Box21_4' 				=> $bill_Box21_4,	//ICD4
				'bill_DOS1F' 				=> $cpt_final[0]['dos_f'],
				'bill_DOS1T' 				=> $cpt_final[0]['dos_t'],
				'bill_DOS2F' 				=> $cpt_final[1]['dos_f'],
				'bill_DOS2T' 				=> $cpt_final[1]['dos_t'],
				'bill_DOS3F' 				=> $cpt_final[2]['dos_f'],
				'bill_DOS3T' 				=> $cpt_final[2]['dos_t'],
				'bill_DOS4F' 				=> $cpt_final[3]['dos_f'],
				'bill_DOS4T' 				=> $cpt_final[3]['dos_t'],
				'bill_DOS5F' 				=> $cpt_final[4]['dos_f'],
				'bill_DOS5T'				=> $cpt_final[4]['dos_t'],
				'bill_DOS6F' 				=> $cpt_final[5]['dos_f'],
				'bill_DOS6T' 				=> $cpt_final[5]['dos_t'],
				'bill_Box24B1' 				=> $cpt_final[0]['pos'],	//Place of Service 1
				'bill_Box24B2' 				=> $cpt_final[1]['pos'],	//Place of Service 2
				'bill_Box24B3'				=> $cpt_final[2]['pos'],	//Place of Service 3
				'bill_Box24B4' 				=> $cpt_final[3]['pos'],	//Place of Service 4
				'bill_Box24B5' 				=> $cpt_final[4]['pos'],	//Place of Service 5
				'bill_Box24B6' 				=> $cpt_final[5]['pos'],	//Place of Service 6
				'bill_Box24D1' 				=> $cpt_final[0]['cpt'],	//CPT1
				'bill_Box24D2'				=> $cpt_final[1]['cpt'],	//CPT2
				'bill_Box24D3' 				=> $cpt_final[2]['cpt'],	//CPT3
				'bill_Box24D4' 				=> $cpt_final[3]['cpt'],	//CPT4
				'bill_Box24D5' 				=> $cpt_final[4]['cpt'],	//CPT5
				'bill_Box24D6' 				=> $cpt_final[5]['cpt'],	//CPT6
				'bill_Modifier1'			=> $cpt_final[0]['modifier'],
				'bill_Modifier2'			=> $cpt_final[1]['modifier'],
				'bill_Modifier3'			=> $cpt_final[2]['modifier'],
				'bill_Modifier4'			=> $cpt_final[3]['modifier'],
				'bill_Modifier5'			=> $cpt_final[4]['modifier'],
				'bill_Modifier6'			=> $cpt_final[5]['modifier'],
				'bill_Box24E1'				=> $cpt_final[0]['icd_pointer'],	//Diagnosis Pointer 1
				'bill_Box24E2'				=> $cpt_final[1]['icd_pointer'],	//Diagnosis Pointer 2
				'bill_Box24E3'				=> $cpt_final[2]['icd_pointer'],	//Diagnosis Pointer 3
				'bill_Box24E4'				=> $cpt_final[3]['icd_pointer'],	//Diagnosis Pointer 4
				'bill_Box24E5'				=> $cpt_final[4]['icd_pointer'],	//Diagnosis Pointer 5
				'bill_Box24E6'				=> $cpt_final[5]['icd_pointer'],	//Diagnosis Pointer 6
				'bill_Box24F1' 				=> number_format($cpt_final[0]['cpt_charge'] * $cpt_final[0]['unit'], 2, ' ', ''),	//Charges 1
				'bill_Box24F2'				=> number_format($cpt_final[1]['cpt_charge'] * $cpt_final[1]['unit'], 2, ' ', ''),	//Charges 2
				'bill_Box24F3' 				=> number_format($cpt_final[2]['cpt_charge'] * $cpt_final[2]['unit'], 2, ' ', ''),	//Charges 3
				'bill_Box24F4' 				=> number_format($cpt_final[3]['cpt_charge'] * $cpt_final[3]['unit'], 2, ' ', ''),	//Charges 4
				'bill_Box24F5' 				=> number_format($cpt_final[4]['cpt_charge'] * $cpt_final[4]['unit'], 2, ' ', ''),	//Charges 5
				'bill_Box24F6' 				=> number_format($cpt_final[5]['cpt_charge'] * $cpt_final[5]['unit'], 2, ' ', ''),	//Charges 6
				'bill_Box24G1'				=> $cpt_final[0]['unit'],	//Units 1
				'bill_Box24G2'				=> $cpt_final[1]['unit'],	//Units 2
				'bill_Box24G3'				=> $cpt_final[2]['unit'],	//Units 3
				'bill_Box24G4'				=> $cpt_final[3]['unit'],	//Units 4
				'bill_Box24G5'				=> $cpt_final[4]['unit'],	//Units 5
				'bill_Box24G6'				=> $cpt_final[5]['unit'],	//Units 6
				'bill_Box24J1' 				=> $cpt_final[0]['npi'],	//NPI 1
				'bill_Box24J2' 				=> $cpt_final[1]['npi'],	//NPI 2
				'bill_Box24J3' 				=> $cpt_final[2]['npi'],	//NPI 3
				'bill_Box24J4' 				=> $cpt_final[3]['npi'],	//NPI 4
				'bill_Box24J5' 				=> $cpt_final[4]['npi'],	//NPI 5
				'bill_Box24J6' 				=> $cpt_final[5]['npi'],	//NPI 6
				'bill_Box25' 				=> $bill_Box25,	//Clinic Tax ID
				'bill_Box26' 				=> $bill_Box26,	//pid
				'bill_Box27'				=> $bill_Box27,	//Accept Assignment
				'bill_Box27P'				=> $bill_Box27P,	//Accept Assignment
				'bill_Box28' 				=> $bill_Box28,	//Total Charges
				'bill_Box29'				=> $bill_Box29,
				'bill_Box30'				=> $bill_Box30,
				'bill_Box31'				=> $bill_Box31,
				'bill_Box32A' 				=> $bill_Box32A,	//Clinic Name
				'bill_Box32B' 				=> $bill_Box32B,	//Clinic Address 1
				'bill_Box32C'				=> $bill_Box32C,	//Clinic Address 2
				'bill_Box32D' 				=> $bill_Box32D,	//Clinic NPI use for 33E too
				'bill_Box33A' 				=> $bill_Box33A,	//Clinic Phone
				'bill_Box33B'				=> $bill_Box33B,
				'bill_Box33C'				=> $bill_Box33C,	//Billing Address 1
				'bill_Box33D'				=> $bill_Box33D,	//Billing Address 2
				'bill_Box33E'				=> $bill_Box32D
			);
			$this->encounters_model->addBilling($data1);
			$this->audit_model->add();
			unset($cpt_final[0]);
			unset($cpt_final[1]);
			unset($cpt_final[2]);
			unset($cpt_final[3]);
			unset($cpt_final[4]);
			unset($cpt_final[5]);
			if ($num_rows5 > 6 && $num_rows5 < 11) {
				$k = 6;
				foreach ($cpt_final as $k=>$v) {
					$l = $k - 6;
					$cpt_final[$l] = $cpt_final[$k];
					unset($cpt_final[$k]);
					$k++;
				}
				$num_rows6 = count($cpt_final);
				if ($num_rows6 < 6) {
					$array1['dos_f'] = $this->string_format('', 8);
					$array1['dos_t'] = $this->string_format('', 8);
					$array1['pos'] = $this->string_format('', 5);
					$array1['cpt'] = $this->string_format('', 6);
					$array1['modifier'] = $this->string_format('', 11);
					$array1['unit1'] = '0';
					$array1['unit'] = $this->string_format('', 5);
					$array1['cpt_charge1'] = '0';
					$array1['cpt_charge'] = $this->string_format('', 8);
					$array1['npi'] = $this->string_format('', 11);
					$array1['icd_pointer'] =  $this->string_format('', 4);
					$cpt_final = array_pad($cpt_final, 6, $array1);
				}
				$bill_Box28 = $cpt_final[0]['cpt_charge1'] * $cpt_final[0]['unit1'] + $cpt_final[1]['cpt_charge1'] * $cpt_final[1]['unit1'] + $cpt_final[2]['cpt_charge1'] * $cpt_final[2]['unit1'] + $cpt_final[3]['cpt_charge1'] * $cpt_final[3]['unit1'] + $cpt_final[4]['cpt_charge1'] * $cpt_final[4]['unit1'] + $cpt_final[5]['cpt_charge1'] * $cpt_final[5]['unit1'];
				$bill_Box28 = number_format($bill_Box28, 2, ' ', '');
				$bill_Box28 = $this->string_format($bill_Box28, 9);
				$bill_Box29 = $this->string_format('0 00', 8);
				$bill_Box30 = $this->string_format($bill_Box28, 8);
				$data2 = array(
					'eid' 						=> $eid,
					'pid' 						=> $pid,
					'insurance_id_1' 			=> $insurance_id_1,
					'insurance_id_2' 			=> $insurance_id_2,
					'bill_complex'				=> $bill_complex,
					'bill_Box11C' 				=> $bill_Box11C,	//Insurance Plan Name
					'bill_payor_id'				=> $bill_payor_id,
					'bill_ins_add1'				=> $bill_ins_add1,
					'bill_ins_add2'				=> $bill_ins_add2,
					'bill_Box1'					=> $bill_Box1,
					'bill_Box1P'				=> $bill_Box1P,
					'bill_Box1A' 				=> $bill_Box1A, 	//Insured ID Number
					'bill_Box2' 				=> $bill_Box2, 	//Patient Name
					'bill_Box3A' 				=> $bill_Box3A, 	//Patient Date of Birth
					'bill_Box3B' 				=> $bill_Box3B, 	//Patient Sex
					'bill_Box3BP' 				=> $bill_Box3BP,
					'bill_Box4'					=> $bill_Box4, 	//Insured Name
					'bill_Box5A' 				=> $bill_Box5A, 	//Patient Address
					'bill_Box6'					=> $bill_Box6, 	//Patient Relationship to Insured
					'bill_Box6P'				=> $bill_Box6P,
					'bill_Box7A'				=> $bill_Box7A, 	//Insured Address
					'bill_Box5B' 				=> $bill_Box5B, 	//Patient City
					'bill_Box5C' 				=> $bill_Box5C, 	//Patient State
					'bill_Box8A'				=> $bill_Box8A, 	//Patient Marital Status
					'bill_Box8AP'				=> $bill_Box8AP,
					'bill_Box7B'				=> $bill_Box7B, 	//Insured City
					'bill_Box7C'				=> $bill_Box7C, 	//Insured State
					'bill_Box5D'				=> $bill_Box5D,	//Patient Zip
					'bill_Box5E'				=> $bill_Box5E,
					'bill_Box8B'				=> $bill_Box8B,	//Patient Employment
					'bill_Box8BP'				=> $bill_Box8BP,
					'bill_Box7D'				=> $bill_Box7D,	//Insured Zip
					'bill_Box9'					=> $bill_Box9, 	//Other Insured Name
					'bill_Box11'				=> $bill_Box11, 	//Insured Group Number
					'bill_Box9A'				=> $bill_Box9A, 	//Other Insured Group Number
					'bill_Box10'				=> $bill_Box10, 
					'bill_Box10A'				=> $bill_Box10A,	//Condition Employment
					'bill_Box10AP'				=> $bill_Box10AP,	//Condition Employment
					'bill_Box11A1'				=> $bill_Box11A1,	//Insured Date of Birth
					'bill_Box11D'				=> $bill_Box11D,
					'bill_Box11DP'				=> $bill_Box11DP,
					'bill_Box11A2'				=> $bill_Box11A2,	//Insured Sex
					'bill_Box11A2P'				=> $bill_Box11A2P,
					'bill_Box9B1'				=> $bill_Box9B1,	//Other Insured Date of Birth
					'bill_Box9B2'				=> $bill_Box9B2,	//Other Insured Sex
					'bill_Box9B2P'				=> $bill_Box9B2P,
					'bill_Box10B1'				=> $bill_Box10B1,	//Condition Auto Accident
					'bill_Box10B1P'				=> $bill_Box10B1P,	//Condition Auto Accident
					'bill_Box10B2'				=> $bill_Box10B2,	//Condition Auto Accident State
					'bill_Box11B'				=> $bill_Box11B,	//Insured Employer
					'bill_Box9C'				=> $bill_Box9C,	//Other Insured Employer
					'bill_Box10C'				=> $bill_Box10C,	//Condition Other Accident
					'bill_Box10CP'				=> $bill_Box10CP,	//Condition Other Accident
					'bill_Box9D'				=> $bill_Box9D,	//Other Insurance Plan Name
					'bill_Box11D'				=> $bill_Box11D,	//Other Insurance Plan Exist
					'bill_Box11DP'				=> $bill_Box11DP,
					'bill_Box17' 				=> $bill_Box17,	//Provider Use for Box 31 and 33B too
					'bill_Box17A' 				=> $bill_Box17A,	//Provider NPI
					'bill_Box21_1' 				=> $bill_Box21_1,	//ICD1
					'bill_Box21_2' 				=> $bill_Box21_2,	//ICD2
					'bill_Box21_3' 				=> $bill_Box21_3,	//ICD3
					'bill_Box21_4' 				=> $bill_Box21_4,	//ICD4
					'bill_DOS1F' 				=> $cpt_final[0]['dos_f'],
					'bill_DOS1T' 				=> $cpt_final[0]['dos_t'],
					'bill_DOS2F' 				=> $cpt_final[1]['dos_f'],
					'bill_DOS2T' 				=> $cpt_final[1]['dos_t'],
					'bill_DOS3F' 				=> $cpt_final[2]['dos_f'],
					'bill_DOS3T' 				=> $cpt_final[2]['dos_t'],
					'bill_DOS4F' 				=> $cpt_final[3]['dos_f'],
					'bill_DOS4T' 				=> $cpt_final[3]['dos_t'],
					'bill_DOS5F' 				=> $cpt_final[4]['dos_f'],
					'bill_DOS5T'				=> $cpt_final[4]['dos_t'],
					'bill_DOS6F' 				=> $cpt_final[5]['dos_f'],
					'bill_DOS6T' 				=> $cpt_final[5]['dos_t'],
					'bill_Box24B1' 				=> $cpt_final[0]['pos'],	//Place of Service 1
					'bill_Box24B2' 				=> $cpt_final[1]['pos'],	//Place of Service 2
					'bill_Box24B3'				=> $cpt_final[2]['pos'],	//Place of Service 3
					'bill_Box24B4' 				=> $cpt_final[3]['pos'],	//Place of Service 4
					'bill_Box24B5' 				=> $cpt_final[4]['pos'],	//Place of Service 5
					'bill_Box24B6' 				=> $cpt_final[5]['pos'],	//Place of Service 6
					'bill_Box24D1' 				=> $cpt_final[0]['cpt'],	//CPT1
					'bill_Box24D2'				=> $cpt_final[1]['cpt'],	//CPT2
					'bill_Box24D3' 				=> $cpt_final[2]['cpt'],	//CPT3
					'bill_Box24D4' 				=> $cpt_final[3]['cpt'],	//CPT4
					'bill_Box24D5' 				=> $cpt_final[4]['cpt'],	//CPT5
					'bill_Box24D6' 				=> $cpt_final[5]['cpt'],	//CPT6
					'bill_Modifier1'			=> $cpt_final[0]['modifier'],
					'bill_Modifier2'			=> $cpt_final[1]['modifier'],
					'bill_Modifier3'			=> $cpt_final[2]['modifier'],
					'bill_Modifier4'			=> $cpt_final[3]['modifier'],
					'bill_Modifier5'			=> $cpt_final[4]['modifier'],
					'bill_Modifier6'			=> $cpt_final[5]['modifier'],
					'bill_Box24E1'				=> $cpt_final[0]['icd_pointer'],	//Diagnosis Pointer 1
					'bill_Box24E2'				=> $cpt_final[1]['icd_pointer'],	//Diagnosis Pointer 2
					'bill_Box24E3'				=> $cpt_final[2]['icd_pointer'],	//Diagnosis Pointer 3
					'bill_Box24E4'				=> $cpt_final[3]['icd_pointer'],	//Diagnosis Pointer 4
					'bill_Box24E5'				=> $cpt_final[4]['icd_pointer'],	//Diagnosis Pointer 5
					'bill_Box24E6'				=> $cpt_final[5]['icd_pointer'],	//Diagnosis Pointer 6
					'bill_Box24F1' 				=> number_format($cpt_final[0]['cpt_charge'] * $cpt_final[0]['unit'], 2, ' ', ''),	//Charges 1
					'bill_Box24F2'				=> number_format($cpt_final[1]['cpt_charge'] * $cpt_final[1]['unit'], 2, ' ', ''),	//Charges 2
					'bill_Box24F3' 				=> number_format($cpt_final[2]['cpt_charge'] * $cpt_final[2]['unit'], 2, ' ', ''),	//Charges 3
					'bill_Box24F4' 				=> number_format($cpt_final[3]['cpt_charge'] * $cpt_final[3]['unit'], 2, ' ', ''),	//Charges 4
					'bill_Box24F5' 				=> number_format($cpt_final[4]['cpt_charge'] * $cpt_final[4]['unit'], 2, ' ', ''),	//Charges 5
					'bill_Box24F6' 				=> number_format($cpt_final[5]['cpt_charge'] * $cpt_final[5]['unit'], 2, ' ', ''),	//Charges 6
					'bill_Box24G1'				=> $cpt_final[0]['unit'],	//Units 1
					'bill_Box24G2'				=> $cpt_final[1]['unit'],	//Units 2
					'bill_Box24G3'				=> $cpt_final[2]['unit'],	//Units 3
					'bill_Box24G4'				=> $cpt_final[3]['unit'],	//Units 4
					'bill_Box24G5'				=> $cpt_final[4]['unit'],	//Units 5
					'bill_Box24G6'				=> $cpt_final[5]['unit'],	//Units 6
					'bill_Box24J1' 				=> $cpt_final[0]['npi'],	//NPI 1
					'bill_Box24J2' 				=> $cpt_final[1]['npi'],	//NPI 2
					'bill_Box24J3' 				=> $cpt_final[2]['npi'],	//NPI 3
					'bill_Box24J4' 				=> $cpt_final[3]['npi'],	//NPI 4
					'bill_Box24J5' 				=> $cpt_final[4]['npi'],	//NPI 5
					'bill_Box24J6' 				=> $cpt_final[5]['npi'],	//NPI 6
					'bill_Box25' 				=> $bill_Box25,	//Clinic Tax ID
					'bill_Box26' 				=> $bill_Box26,	//pid
					'bill_Box27'				=> $bill_Box27,	//Accept Assignment
					'bill_Box27P'				=> $bill_Box27P,	//Accept Assignment
					'bill_Box28' 				=> $bill_Box28,	//Total Charges
					'bill_Box29'				=> $bill_Box29,
					'bill_Box30'				=> $bill_Box30,
					'bill_Box31'				=> $bill_Box31,
					'bill_Box32A' 				=> $bill_Box32A,	//Clinic Name
					'bill_Box32B' 				=> $bill_Box32B,	//Clinic Address 1
					'bill_Box32C'				=> $bill_Box32C,	//Clinic Address 2
					'bill_Box32D' 				=> $bill_Box32D,	//Clinic NPI use for 33E too
					'bill_Box33A' 				=> $bill_Box33A,	//Clinic Phone
					'bill_Box33B'				=> $bill_Box33B,
					'bill_Box33C'				=> $bill_Box33C,	//Billing Address 1
					'bill_Box33D'				=> $bill_Box33D,	//Billing Address 2
					'bill_Box33E'				=> $bill_Box32D
				);
				$this->encounters_model->addBilling($data2);
				$this->audit_model->add();
				unset($cpt_final[0]);
				unset($cpt_final[1]);
				unset($cpt_final[2]);
				unset($cpt_final[3]);
				unset($cpt_final[4]);
				unset($cpt_final[5]);
			}	
		} else {
			return "No CPT charges filed. Billing not saved.";
			exit (0);
		}
		$this->db->where('eid', $eid);
		$this->db->where('billing_group', '2');
		$this->db->order_by('cpt_charge', 'desc'); 
		$query7 = $this->db->get('billing_core');
		$result7 = $query7->result_array();
		$num_rows7 = $query7->num_rows();
		if ($num_rows7 > 0) {
			$query8= $this->encounters_model->getAssessment($eid);
			$result8 = $query8->row_array();
			$bill_Box21_1 = $this->string_format($result8['assessment_icd1'], 8);
			$bill_Box21_2 = $this->string_format($result8['assessment_icd2'], 8);
			$bill_Box21_3 = $this->string_format($result8['assessment_icd3'], 8);
			$bill_Box21_4 = $this->string_format($result8['assessment_icd4'], 8);
			$i = 0;
			foreach ($result7 as $key7 => $value7) {
				$cpt_charge7[$key7]  = $value7['cpt_charge'];
			}
			array_multisort($cpt_charge7, SORT_DESC, $result7);
			while ($i < $num_rows7 ) {
				$cpt_final[$i] = $result7[$i];
				$cpt_final[$i]['dos_f'] = str_replace('/', '', $cpt_final[$i]['dos_f']);
				$cpt_final[$i]['dos_f'] = $this->string_format($cpt_final[$i]['dos_f'], 8);
				$cpt_final[$i]['dos_t'] = str_replace('/', '', $cpt_final[$i]['dos_t']);
				$cpt_final[$i]['dos_t'] = $this->string_format($cpt_final[$i]['dos_t'], 8);
				$cpt_final[$i]['pos'] =  $this->string_format($pos, 5);
				$cpt_final[$i]['cpt'] = $this->string_format($cpt_final[$i]['cpt'], 6);
				$cpt_final[$i]['modifier'] = $this->string_format($cpt_final[$i]['modifier'], 11);
				$cpt_final[$i]['unit1'] = $cpt_final[$i]['unit'];
				$cpt_final[$i]['unit'] = $this->string_format($cpt_final[$i]['unit'] ,5);
				$cpt_final[$i]['cpt_charge'] = number_format($cpt_final[$i]['cpt_charge'], 2, ' ', '');
				$cpt_final[$i]['cpt_charge1'] = $cpt_final[$i]['cpt_charge'];
				$cpt_final[$i]['cpt_charge'] = $this->string_format($cpt_final[$i]['cpt_charge'], 8);
				$cpt_final[$i]['npi'] = $this->string_format($npi, 11);
				$cpt_final[$i]['icd_pointer'] =  $this->string_format($cpt_final[$i]['icd_pointer'], 4);
				$i++;
			}
			if ($num_rows7 < 6) {
				$array['dos_f'] = $this->string_format('', 8);
				$array['dos_t'] = $this->string_format('', 8);
				$array['pos'] = $this->string_format('', 5);
				$array['cpt'] = $this->string_format('', 6);
				$array['modifier'] = $this->string_format('', 11);
				$array['unit1'] = '0';
				$array['unit'] = $this->string_format('', 5);
				$array['cpt_charge1'] = '0';
				$array['cpt_charge'] = $this->string_format('', 8);
				$array['npi'] = $this->string_format('', 11);
				$array['icd_pointer'] =  $this->string_format('', 4);
				$cpt_final = array_pad($cpt_final, 6, $array);
			}
			$bill_Box28 = $cpt_final[0]['cpt_charge1'] * $cpt_final[0]['unit1'] + $cpt_final[1]['cpt_charge1'] * $cpt_final[1]['unit1'] + $cpt_final[2]['cpt_charge1'] * $cpt_final[2]['unit1'] + $cpt_final[3]['cpt_charge1'] * $cpt_final[3]['unit1'] + $cpt_final[4]['cpt_charge1'] * $cpt_final[4]['unit1'] + $cpt_final[5]['cpt_charge1'] * $cpt_final[5]['unit1'];
			$bill_Box28 = number_format($bill_Box28, 2, ' ', '');
			$bill_Box28 = $this->string_format($bill_Box28, 9);
			$bill_Box29 = $this->string_format('0 00', 8);
			$bill_Box30 = $this->string_format($bill_Box28, 8);
			$data3 = array(
				'eid' 						=> $eid,
				'pid' 						=> $pid,
				'insurance_id_1' 			=> $insurance_id_1,
				'insurance_id_2' 			=> $insurance_id_2,
				'bill_complex'				=> $bill_complex,
				'bill_Box11C' 				=> $bill_Box11C,	//Insurance Plan Name
				'bill_payor_id'				=> $bill_payor_id,
				'bill_ins_add1'				=> $bill_ins_add1,
				'bill_ins_add2'				=> $bill_ins_add2,
				'bill_Box1'					=> $bill_Box1,
				'bill_Box1P'				=> $bill_Box1P,
				'bill_Box1A' 				=> $bill_Box1A, 	//Insured ID Number
				'bill_Box2' 				=> $bill_Box2, 	//Patient Name
				'bill_Box3A' 				=> $bill_Box3A, 	//Patient Date of Birth
				'bill_Box3B' 				=> $bill_Box3B, 	//Patient Sex
				'bill_Box3BP' 				=> $bill_Box3BP,
				'bill_Box4'					=> $bill_Box4, 	//Insured Name
				'bill_Box5A' 				=> $bill_Box5A, 	//Patient Address
				'bill_Box6'					=> $bill_Box6, 	//Patient Relationship to Insured
				'bill_Box6P'				=> $bill_Box6P,
				'bill_Box7A'				=> $bill_Box7A, 	//Insured Address
				'bill_Box5B' 				=> $bill_Box5B, 	//Patient City
				'bill_Box5C' 				=> $bill_Box5C, 	//Patient State
				'bill_Box8A'				=> $bill_Box8A, 	//Patient Marital Status
				'bill_Box8AP'				=> $bill_Box8AP,
				'bill_Box7B'				=> $bill_Box7B, 	//Insured City
				'bill_Box7C'				=> $bill_Box7C, 	//Insured State
				'bill_Box5D'				=> $bill_Box5D,	//Patient Zip
				'bill_Box5E'				=> $bill_Box5E,
				'bill_Box8B'				=> $bill_Box8B,	//Patient Employment
				'bill_Box8BP'				=> $bill_Box8BP,
				'bill_Box7D'				=> $bill_Box7D,	//Insured Zip
				'bill_Box9'					=> $bill_Box9, 	//Other Insured Name
				'bill_Box11'				=> $bill_Box11, 	//Insured Group Number
				'bill_Box9A'				=> $bill_Box9A, 	//Other Insured Group Number
				'bill_Box10'				=> $bill_Box10, 
				'bill_Box10A'				=> $bill_Box10A,	//Condition Employment
				'bill_Box10AP'				=> $bill_Box10AP,	//Condition Employment
				'bill_Box11A1'				=> $bill_Box11A1,	//Insured Date of Birth
				'bill_Box11A2'				=> $bill_Box11A2,	//Insured Sex
				'bill_Box11A2P'				=> $bill_Box11A2P,
				'bill_Box9B1'				=> $bill_Box9B1,	//Other Insured Date of Birth
				'bill_Box9B2'				=> $bill_Box9B2,	//Other Insured Sex
				'bill_Box9B2P'				=> $bill_Box9B2P,
				'bill_Box10B1'				=> $bill_Box10B1,	//Condition Auto Accident
				'bill_Box10B1P'				=> $bill_Box10B1P,	//Condition Auto Accident
				'bill_Box10B2'				=> $bill_Box10B2,	//Condition Auto Accident State
				'bill_Box11B'				=> $bill_Box11B,	//Insured Employer
				'bill_Box9C'				=> $bill_Box9C,	//Other Insured Employer
				'bill_Box10C'				=> $bill_Box10C,	//Condition Other Accident
				'bill_Box10CP'				=> $bill_Box10CP,	//Condition Other Accident
				'bill_Box9D'				=> $bill_Box9D,	//Other Insurance Plan Name
				'bill_Box11D'				=> $bill_Box11D,	//Other Insurance Plan Exist
				'bill_Box11DP'				=> $bill_Box11DP,
				'bill_Box17' 				=> $bill_Box17,	//Provider Use for Box 31 and 33B too
				'bill_Box17A' 				=> $bill_Box17A,	//Provider NPI
				'bill_Box21_1' 				=> $bill_Box21_1,	//ICD1
				'bill_Box21_2' 				=> $bill_Box21_2,	//ICD2
				'bill_Box21_3' 				=> $bill_Box21_3,	//ICD3
				'bill_Box21_4' 				=> $bill_Box21_4,	//ICD4
				'bill_DOS1F' 				=> $cpt_final[0]['dos_f'],
				'bill_DOS1T' 				=> $cpt_final[0]['dos_t'],
				'bill_DOS2F' 				=> $cpt_final[1]['dos_f'],
				'bill_DOS2T' 				=> $cpt_final[1]['dos_t'],
				'bill_DOS3F' 				=> $cpt_final[2]['dos_f'],
				'bill_DOS3T' 				=> $cpt_final[2]['dos_t'],
				'bill_DOS4F' 				=> $cpt_final[3]['dos_f'],
				'bill_DOS4T' 				=> $cpt_final[3]['dos_t'],
				'bill_DOS5F' 				=> $cpt_final[4]['dos_f'],
				'bill_DOS5T'				=> $cpt_final[4]['dos_t'],
				'bill_DOS6F' 				=> $cpt_final[5]['dos_f'],
				'bill_DOS6T' 				=> $cpt_final[5]['dos_t'],
				'bill_Box24B1' 				=> $cpt_final[0]['pos'],	//Place of Service 1
				'bill_Box24B2' 				=> $cpt_final[1]['pos'],	//Place of Service 2
				'bill_Box24B3'				=> $cpt_final[2]['pos'],	//Place of Service 3
				'bill_Box24B4' 				=> $cpt_final[3]['pos'],	//Place of Service 4
				'bill_Box24B5' 				=> $cpt_final[4]['pos'],	//Place of Service 5
				'bill_Box24B6' 				=> $cpt_final[5]['pos'],	//Place of Service 6
				'bill_Box24D1' 				=> $cpt_final[0]['cpt'],	//CPT1
				'bill_Box24D2'				=> $cpt_final[1]['cpt'],	//CPT2
				'bill_Box24D3' 				=> $cpt_final[2]['cpt'],	//CPT3
				'bill_Box24D4' 				=> $cpt_final[3]['cpt'],	//CPT4
				'bill_Box24D5' 				=> $cpt_final[4]['cpt'],	//CPT5
				'bill_Box24D6' 				=> $cpt_final[5]['cpt'],	//CPT6
				'bill_Modifier1'			=> $cpt_final[0]['modifier'],
				'bill_Modifier2'			=> $cpt_final[1]['modifier'],
				'bill_Modifier3'			=> $cpt_final[2]['modifier'],
				'bill_Modifier4'			=> $cpt_final[3]['modifier'],
				'bill_Modifier5'			=> $cpt_final[4]['modifier'],
				'bill_Modifier6'			=> $cpt_final[5]['modifier'],
				'bill_Box24E1'				=> $cpt_final[0]['icd_pointer'],	//Diagnosis Pointer 1
				'bill_Box24E2'				=> $cpt_final[1]['icd_pointer'],	//Diagnosis Pointer 2
				'bill_Box24E3'				=> $cpt_final[2]['icd_pointer'],	//Diagnosis Pointer 3
				'bill_Box24E4'				=> $cpt_final[3]['icd_pointer'],	//Diagnosis Pointer 4
				'bill_Box24E5'				=> $cpt_final[4]['icd_pointer'],	//Diagnosis Pointer 5
				'bill_Box24E6'				=> $cpt_final[5]['icd_pointer'],	//Diagnosis Pointer 6
				'bill_Box24F1' 				=> number_format($cpt_final[0]['cpt_charge'] * $cpt_final[0]['unit'], 2, ' ', ''),	//Charges 1
				'bill_Box24F2'				=> number_format($cpt_final[1]['cpt_charge'] * $cpt_final[1]['unit'], 2, ' ', ''),	//Charges 2
				'bill_Box24F3' 				=> number_format($cpt_final[2]['cpt_charge'] * $cpt_final[2]['unit'], 2, ' ', ''),	//Charges 3
				'bill_Box24F4' 				=> number_format($cpt_final[3]['cpt_charge'] * $cpt_final[3]['unit'], 2, ' ', ''),	//Charges 4
				'bill_Box24F5' 				=> number_format($cpt_final[4]['cpt_charge'] * $cpt_final[4]['unit'], 2, ' ', ''),	//Charges 5
				'bill_Box24F6' 				=> number_format($cpt_final[5]['cpt_charge'] * $cpt_final[5]['unit'], 2, ' ', ''),	//Charges 6
				'bill_Box24G1'				=> $cpt_final[0]['unit'],	//Units 1
				'bill_Box24G2'				=> $cpt_final[1]['unit'],	//Units 2
				'bill_Box24G3'				=> $cpt_final[2]['unit'],	//Units 3
				'bill_Box24G4'				=> $cpt_final[3]['unit'],	//Units 4
				'bill_Box24G5'				=> $cpt_final[4]['unit'],	//Units 5
				'bill_Box24G6'				=> $cpt_final[5]['unit'],	//Units 6
				'bill_Box24J1' 				=> $cpt_final[0]['npi'],	//NPI 1
				'bill_Box24J2' 				=> $cpt_final[1]['npi'],	//NPI 2
				'bill_Box24J3' 				=> $cpt_final[2]['npi'],	//NPI 3
				'bill_Box24J4' 				=> $cpt_final[3]['npi'],	//NPI 4
				'bill_Box24J5' 				=> $cpt_final[4]['npi'],	//NPI 5
				'bill_Box24J6' 				=> $cpt_final[5]['npi'],	//NPI 6
				'bill_Box25' 				=> $bill_Box25,	//Clinic Tax ID
				'bill_Box26' 				=> $bill_Box26,	//pid
				'bill_Box27'				=> $bill_Box27,	//Accept Assignment
				'bill_Box27P'				=> $bill_Box27P,	//Accept Assignment
				'bill_Box28' 				=> $bill_Box28,	//Total Charges
				'bill_Box29'				=> $bill_Box29,
				'bill_Box30'				=> $bill_Box30,
				'bill_Box31'				=> $bill_Box31,
				'bill_Box32A' 				=> $bill_Box32A,	//Clinic Name
				'bill_Box32B' 				=> $bill_Box32B,	//Clinic Address 1
				'bill_Box32C'				=> $bill_Box32C,	//Clinic Address 2
				'bill_Box32D' 				=> $bill_Box32D,	//Clinic NPI use for 33E too
				'bill_Box33A' 				=> $bill_Box33A,	//Clinic Phone
				'bill_Box33B'				=> $bill_Box33B,
				'bill_Box33C'				=> $bill_Box33C,	//Billing Address 1
				'bill_Box33D'				=> $bill_Box33D,	//Billing Address 2
				'bill_Box33E'				=> $bill_Box32D
			);
			$this->encounters_model->addBilling($data3);
			$this->audit_model->add();
			unset($cpt_final[0]);
			unset($cpt_final[1]);
			unset($cpt_final[2]);
			unset($cpt_final[3]);
			unset($cpt_final[4]);
			unset($cpt_final[5]);
			if ($num_rows7 > 6 && $num_rows7 < 11) {
				$k = 6;
				foreach ($cpt_final as $k=>$v) {
					$l = $k - 6;
					$cpt_final[$l] = $cpt_final[$k];
					unset($cpt_final[$k]);
					$k++;
				}
				$num_rows6 = count($cpt_final);
				if ($num_rows6 < 6) {
					$array1['dos_f'] = $this->string_format('', 8);
					$array1['dos_t'] = $this->string_format('', 8);
					$array1['pos'] = $this->string_format('', 5);
					$array1['cpt'] = $this->string_format('', 6);
					$array1['modifier'] = $this->string_format('', 11);
					$array1['unit1'] = '0';
					$array1['unit'] = $this->string_format('', 5);
					$array1['cpt_charge1'] = '0';
					$array1['cpt_charge'] = $this->string_format('', 8);
					$array1['npi'] = $this->string_format('', 11);
					$array1['icd_pointer'] =  $this->string_format('', 4);
					$cpt_final = array_pad($cpt_final, 6, $array1);
				}
				$bill_Box28 = $cpt_final[0]['cpt_charge1'] * $cpt_final[0]['unit1'] + $cpt_final[1]['cpt_charge1'] * $cpt_final[1]['unit1'] + $cpt_final[2]['cpt_charge1'] * $cpt_final[2]['unit1'] + $cpt_final[3]['cpt_charge1'] * $cpt_final[3]['unit1'] + $cpt_final[4]['cpt_charge1'] * $cpt_final[4]['unit1'] + $cpt_final[5]['cpt_charge1'] * $cpt_final[5]['unit1'];
				$bill_Box28 = number_format($bill_Box28, 2, ' ', '');
				$bill_Box28 = $this->string_format($bill_Box28, 9);
				$bill_Box29 = $this->string_format('0 00', 8);
				$bill_Box30 = $this->string_format($bill_Box28, 8);
				$data4 = array(
					'eid' 						=> $eid,
					'pid' 						=> $pid,
					'insurance_id_1' 			=> $insurance_id_1,
					'insurance_id_2' 			=> $insurance_id_2,
					'bill_complex'				=> $bill_complex,
					'bill_Box11C' 				=> $bill_Box11C,	//Insurance Plan Name
					'bill_payor_id'				=> $bill_payor_id,
					'bill_ins_add1'				=> $bill_ins_add1,
					'bill_ins_add2'				=> $bill_ins_add2,
					'bill_Box1'					=> $bill_Box1,
					'bill_Box1P'				=> $bill_Box1P,
					'bill_Box1A' 				=> $bill_Box1A, 	//Insured ID Number
					'bill_Box2' 				=> $bill_Box2, 	//Patient Name
					'bill_Box3A' 				=> $bill_Box3A, 	//Patient Date of Birth
					'bill_Box3B' 				=> $bill_Box3B, 	//Patient Sex
					'bill_Box3BP' 				=> $bill_Box3BP,
					'bill_Box4'					=> $bill_Box4, 	//Insured Name
					'bill_Box5A' 				=> $bill_Box5A, 	//Patient Address
					'bill_Box6'					=> $bill_Box6, 	//Patient Relationship to Insured
					'bill_Box6P'				=> $bill_Box6P,
					'bill_Box7A'				=> $bill_Box7A, 	//Insured Address
					'bill_Box5B' 				=> $bill_Box5B, 	//Patient City
					'bill_Box5C' 				=> $bill_Box5C, 	//Patient State
					'bill_Box8A'				=> $bill_Box8A, 	//Patient Marital Status
					'bill_Box8AP'				=> $bill_Box8AP,
					'bill_Box7B'				=> $bill_Box7B, 	//Insured City
					'bill_Box7C'				=> $bill_Box7C, 	//Insured State
					'bill_Box5D'				=> $bill_Box5D,	//Patient Zip
					'bill_Box5E'				=> $bill_Box5E,
					'bill_Box8B'				=> $bill_Box8B,	//Patient Employment
					'bill_Box8BP'				=> $bill_Box8BP,
					'bill_Box7D'				=> $bill_Box7D,	//Insured Zip
					'bill_Box9'					=> $bill_Box9, 	//Other Insured Name
					'bill_Box11'				=> $bill_Box11, 	//Insured Group Number
					'bill_Box9A'				=> $bill_Box9A, 	//Other Insured Group Number
					'bill_Box10'				=> $bill_Box10, 
					'bill_Box10A'				=> $bill_Box10A,	//Condition Employment
					'bill_Box10AP'				=> $bill_Box10AP,	//Condition Employment
					'bill_Box11A1'				=> $bill_Box11A1,	//Insured Date of Birth
					'bill_Box11A2'				=> $bill_Box11A2,	//Insured Sex
					'bill_Box11A2P'				=> $bill_Box11A2P,
					'bill_Box9B1'				=> $bill_Box9B1,	//Other Insured Date of Birth
					'bill_Box9B2'				=> $bill_Box9B2,	//Other Insured Sex
					'bill_Box9B2P'				=> $bill_Box9B2P,
					'bill_Box10B1'				=> $bill_Box10B1,	//Condition Auto Accident
					'bill_Box10B1P'				=> $bill_Box10B1P,	//Condition Auto Accident
					'bill_Box10B2'				=> $bill_Box10B2,	//Condition Auto Accident State
					'bill_Box11B'				=> $bill_Box11B,	//Insured Employer
					'bill_Box9C'				=> $bill_Box9C,	//Other Insured Employer
					'bill_Box10C'				=> $bill_Box10C,	//Condition Other Accident
					'bill_Box10CP'				=> $bill_Box10CP,	//Condition Other Accident
					'bill_Box9D'				=> $bill_Box9D,	//Other Insurance Plan Name
					'bill_Box11D'				=> $bill_Box11D,	//Other Insurance Plan Exist
					'bill_Box11DP'				=> $bill_Box11DP,
					'bill_Box17' 				=> $bill_Box17,	//Provider Use for Box 31 and 33B too
					'bill_Box17A' 				=> $bill_Box17A,	//Provider NPI
					'bill_Box21_1' 				=> $bill_Box21_1,	//ICD1
					'bill_Box21_2' 				=> $bill_Box21_2,	//ICD2
					'bill_Box21_3' 				=> $bill_Box21_3,	//ICD3
					'bill_Box21_4' 				=> $bill_Box21_4,	//ICD4
					'bill_DOS1F' 				=> $cpt_final[0]['dos_f'],
					'bill_DOS1T' 				=> $cpt_final[0]['dos_t'],
					'bill_DOS2F' 				=> $cpt_final[1]['dos_f'],
					'bill_DOS2T' 				=> $cpt_final[1]['dos_t'],
					'bill_DOS3F' 				=> $cpt_final[2]['dos_f'],
					'bill_DOS3T' 				=> $cpt_final[2]['dos_t'],
					'bill_DOS4F' 				=> $cpt_final[3]['dos_f'],
					'bill_DOS4T' 				=> $cpt_final[3]['dos_t'],
					'bill_DOS5F' 				=> $cpt_final[4]['dos_f'],
					'bill_DOS5T'				=> $cpt_final[4]['dos_t'],
					'bill_DOS6F' 				=> $cpt_final[5]['dos_f'],
					'bill_DOS6T' 				=> $cpt_final[5]['dos_t'],
					'bill_Box24B1' 				=> $cpt_final[0]['pos'],	//Place of Service 1
					'bill_Box24B2' 				=> $cpt_final[1]['pos'],	//Place of Service 2
					'bill_Box24B3'				=> $cpt_final[2]['pos'],	//Place of Service 3
					'bill_Box24B4' 				=> $cpt_final[3]['pos'],	//Place of Service 4
					'bill_Box24B5' 				=> $cpt_final[4]['pos'],	//Place of Service 5
					'bill_Box24B6' 				=> $cpt_final[5]['pos'],	//Place of Service 6
					'bill_Box24D1' 				=> $cpt_final[0]['cpt'],	//CPT1
					'bill_Box24D2'				=> $cpt_final[1]['cpt'],	//CPT2
					'bill_Box24D3' 				=> $cpt_final[2]['cpt'],	//CPT3
					'bill_Box24D4' 				=> $cpt_final[3]['cpt'],	//CPT4
					'bill_Box24D5' 				=> $cpt_final[4]['cpt'],	//CPT5
					'bill_Box24D6' 				=> $cpt_final[5]['cpt'],	//CPT6
					'bill_Modifier1'			=> $cpt_final[0]['modifier'],
					'bill_Modifier2'			=> $cpt_final[1]['modifier'],
					'bill_Modifier3'			=> $cpt_final[2]['modifier'],
					'bill_Modifier4'			=> $cpt_final[3]['modifier'],
					'bill_Modifier5'			=> $cpt_final[4]['modifier'],
					'bill_Modifier6'			=> $cpt_final[5]['modifier'],
					'bill_Box24E1'				=> $cpt_final[0]['icd_pointer'],	//Diagnosis Pointer 1
					'bill_Box24E2'				=> $cpt_final[1]['icd_pointer'],	//Diagnosis Pointer 2
					'bill_Box24E3'				=> $cpt_final[2]['icd_pointer'],	//Diagnosis Pointer 3
					'bill_Box24E4'				=> $cpt_final[3]['icd_pointer'],	//Diagnosis Pointer 4
					'bill_Box24E5'				=> $cpt_final[4]['icd_pointer'],	//Diagnosis Pointer 5
					'bill_Box24E6'				=> $cpt_final[5]['icd_pointer'],	//Diagnosis Pointer 6
					'bill_Box24F1' 				=> number_format($cpt_final[0]['cpt_charge'] * $cpt_final[0]['unit'], 2, ' ', ''),	//Charges 1
					'bill_Box24F2'				=> number_format($cpt_final[1]['cpt_charge'] * $cpt_final[1]['unit'], 2, ' ', ''),	//Charges 2
					'bill_Box24F3' 				=> number_format($cpt_final[2]['cpt_charge'] * $cpt_final[2]['unit'], 2, ' ', ''),	//Charges 3
					'bill_Box24F4' 				=> number_format($cpt_final[3]['cpt_charge'] * $cpt_final[3]['unit'], 2, ' ', ''),	//Charges 4
					'bill_Box24F5' 				=> number_format($cpt_final[4]['cpt_charge'] * $cpt_final[4]['unit'], 2, ' ', ''),	//Charges 5
					'bill_Box24F6' 				=> number_format($cpt_final[5]['cpt_charge'] * $cpt_final[5]['unit'], 2, ' ', ''),	//Charges 6
					'bill_Box24G1'				=> $cpt_final[0]['unit'],	//Units 1
					'bill_Box24G2'				=> $cpt_final[1]['unit'],	//Units 2
					'bill_Box24G3'				=> $cpt_final[2]['unit'],	//Units 3
					'bill_Box24G4'				=> $cpt_final[3]['unit'],	//Units 4
					'bill_Box24G5'				=> $cpt_final[4]['unit'],	//Units 5
					'bill_Box24G6'				=> $cpt_final[5]['unit'],	//Units 6
					'bill_Box24J1' 				=> $cpt_final[0]['npi'],	//NPI 1
					'bill_Box24J2' 				=> $cpt_final[1]['npi'],	//NPI 2
					'bill_Box24J3' 				=> $cpt_final[2]['npi'],	//NPI 3
					'bill_Box24J4' 				=> $cpt_final[3]['npi'],	//NPI 4
					'bill_Box24J5' 				=> $cpt_final[4]['npi'],	//NPI 5
					'bill_Box24J6' 				=> $cpt_final[5]['npi'],	//NPI 6
					'bill_Box25' 				=> $bill_Box25,	//Clinic Tax ID
					'bill_Box26' 				=> $bill_Box26,	//pid
					'bill_Box27'				=> $bill_Box27,	//Accept Assignment
					'bill_Box27P'				=> $bill_Box27P,	//Accept Assignment
					'bill_Box28' 				=> $bill_Box28,	//Total Charges
					'bill_Box29'				=> $bill_Box29,
					'bill_Box30'				=> $bill_Box30,
					'bill_Box31'				=> $bill_Box31,
					'bill_Box32A' 				=> $bill_Box32A,	//Clinic Name
					'bill_Box32B' 				=> $bill_Box32B,	//Clinic Address 1
					'bill_Box32C'				=> $bill_Box32C,	//Clinic Address 2
					'bill_Box32D' 				=> $bill_Box32D,	//Clinic NPI use for 33E too
					'bill_Box33A' 				=> $bill_Box33A,	//Clinic Phone
					'bill_Box33B'				=> $bill_Box33B,
					'bill_Box33C'				=> $bill_Box33C,	//Billing Address 1
					'bill_Box33D'				=> $bill_Box33D,	//Billing Address 2
					'bill_Box33E'				=> $bill_Box32D
				);
				$this->encounters_model->addBilling($data4);
				$this->audit_model->add();
			}	
		}
		return 'Billing saved and waiting to be submitted!';
	}
	
	function billing_save1()
	{
		$insurance_id_1 = $this->input->post('insurance_id_1');
		$insurance_id_2 = $this->input->post('insurance_id_2');
		$bill_complex = $this->input->post('bill_complex');
		$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $bill_complex);
		echo $result;
	}
	
	function page_invoice1($eid)
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}	
		$assessmentInfo = $this->encounters_model->getAssessment($eid)->row();
		if ($assessmentInfo) {
			$data['assessment'] = '';
			if ($assessmentInfo->assessment_1 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_1 . '<br />';
				if ($assessmentInfo->assessment_2 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_2 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_2 . '<br />';
				if ($assessmentInfo->assessment_3 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_3 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_3 . '<br />';
				if ($assessmentInfo->assessment_4 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_4 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_4 . '<br />';
				if ($assessmentInfo->assessment_5 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_5 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_5 . '<br />';
				if ($assessmentInfo->assessment_6 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_6 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_6 . '<br />';
				if ($assessmentInfo->assessment_7 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_7 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_7 . '<br />';
				if ($assessmentInfo->assessment_8 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_8 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_8 . '<br /><br />';
			}
		} else {
			$data['assessment'] = '';
		}	
		$this->db->where('eid', $eid);
		$this->db->order_by('cpt_charge', 'desc'); 
		$query1 = $this->db->get('billing_core');
		$result1 = $query1->result_array();
		$num_rows1 = $query1->num_rows();
		if ($num_rows1 > 0) {
			$charge = 0;
			$payment = 0;
			$data['text'] = "";
			foreach ($result1 as $key1 => $value1) {
				$cpt_charge1[$key1]  = $value1['cpt_charge'];
			}
			array_multisort($cpt_charge1, SORT_DESC, $result1);
			foreach ($result1 as $result1a) {
				if ($result1a['cpt']) {
					$this->db->where('cpt', $result1a['cpt']);
					$query2 = $this->db->get('cpt');
					$result2 = $query2->row_array();
					$data['text'] .= '<table class="order" cellspacing="10"><tr><th style="width:100">PROCEDURE</th><th style="width:100">UNITS</th><th style="width:350">DESCRIPTION</th><th style="width:150">CHARGE</th></tr>';
					$data['text'] .= '<tr><td>' . $result1a['cpt'] . '</td><td>' . $result1a['unit'] . '</td><td>' . $result2['cpt_description'] . '</td><td>$' . $result1a['cpt_charge'] . '</td></tr></table>';
					$charge += $result1a['cpt_charge'] * $result1a['unit'];
				} else {
					$data['text'] .= '<table class="order" cellspacing="10"><tr><td>Date of Payment:</td><td>' . $result1a['dos_f'] . '</td><td>' . $result1a['payment_type'] . '</td><td>$(' . $result1a['payment'] . ')</td></tr></table>';
					$payment = $payment + $result1a['payment'];
				}
			}
			$balance = $charge - $payment;
			$charge = number_format($charge, 2, '.', ',');
			$payment = number_format($payment, 2, '.', ',');
			$balance = number_format($balance, 2, '.', ',');
			$data['text'] .= '<table class="order" cellspacing="10"><tr><td style="width:100"></td><td style="width:100"></td><td style="width:350"><strong>Total Charges:</strong></td><td style="width:150"><strong>$' . $charge . '</strong></td></tr><tr><td></td><td></td><td><strong>Total Payments:</strong></td><td><strong>$' . $payment . '</strong></td></tr><tr><td></td><td></td><td></td><td><hr/></td></tr><tr><td></td><td></td><td><strong>Remaining Balance:</strong></td><td><strong>$' . $balance . '</strong></td></tr></table>';
		} else {
			$data['text'] = 'No procedures.';
		}
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		$practice = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row();
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['text'] .= '<br>Please send a check payable to ' . $practice->practice_name . ' and mail it to:';
		$data['text'] .= '<br>' . $practice->billing_street_address1;
		if ($practice->billing_street_address2 != '') {
			$data['text'] .= ', ' . $practice->billing_street_address2;
		}
		$data['text'] .= '<br>' . $practice->billing_city . ', ' . $practice->billing_state . ' ' . $practice->billing_zip;
		$data['patientInfo1'] = $row['firstname'] . ' ' . $row['lastname'];
		$data['patientInfo2'] = $row['address'];
		$data['patientInfo3'] = $row['city'] . ', ' . $row['state'] . ' ' . $row['zip'];
		$data['patientInfo'] = $this->demographics_model->get($pid)->row();
		$dob1 = human_to_unix($data['patientInfo']->DOB);
		$data['dob'] = date('m/d/Y', $dob1);
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$dos1 = human_to_unix($encounterInfo->encounter_DOS);
		$data['encounter_DOS'] = date('F jS, Y', $dos1);
		$data['encounter_provider'] = $encounterInfo->encounter_provider;
		$query1 = $this->chart_model->getActiveInsurance($pid);
		$data['insuranceInfo'] = '';
		if ($query1->num_rows() > 0) {
			foreach ($query1->result_array() as $row) {
				$data['insuranceInfo'] .= $row['insurance_plan_name'] . '; ID: ' . $row['insurance_id_num'] . '; Group: ' . $row['insurance_group'] . '; ' . $row['insurance_insu_lastname'] . ', ' . $row['insurance_insu_firstname'] . '<br><br>';
			}
		}
		$data['title'] = "INVOICE";
		$date = now();
		$data['date'] = date('F jS, Y', $date);
		return $this->load->view('auth/pages/invoice_page',$data, TRUE);
	}
	
	function print_invoice($insurance_id_1, $insurance_id_2, $bill_complex='')
	{
		$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $bill_complex);
		$eid = $this->session->userdata('eid');
		$html = $this->page_invoice1($eid);
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/invoice_" . now() . "_" . $user_id . ".pdf";
		ini_set('memory_limit','196M');
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Generated by NOSH EMR');
		$this->mpdf->debug = true;
		$this->mpdf->showImageErrors = true;
		$this->mpdf->simpleTables = true;
		$this->mpdf->Output($file_path,'F');
		while(!file_exists($file_path)) {
			sleep(2);
		}
		if ($fp = fopen ($file_path, "r")) {
			$file_info = pathinfo($file_path);
			$file_name = $file_info["basename"];
			$file_size = filesize($file_path);
			$file_extension = strtolower($file_info["extension"]);	 
			if($file_extension!='pdf') {
				die('LOGGED! bad extension');
			}
			ob_start();
			header('Content-type: application/pdf');
			header('Content-Disposition: attachment; filename="'.$file_name.'"');
			header("Content-length: $file_size");
			ob_end_flush(); 
			while(!feof($fp)) {
				$file_buffer = fread($fp, 2048);
				echo $file_buffer;
			}
			fclose($fp);
			unlink($file_path);
			exit();
		} else {
			die('LOGGED! bad file '.$file_path);
		}
	}
	
	function generate_hcfa($insurance_id_1, $insurance_id_2, $bill_complex='')
	{
		$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $bill_complex);
		$pid = $this->session->userdata('pid');
		$eid = $this->session->userdata('eid');
		$this->db->where('eid', $eid);
		$query = $this->db->get('billing');
		$input1 = '';
		if ($query->num_rows() > 0) {
			$i = 0;
			foreach ($query->result_array() as $pdfinfo) {
				$input = '/var/www/nosh/hcfa1500.pdf';
				$output = '/var/www/nosh/hcfa1500_output_' . $i . '.pdf';
				if (file_exists($output)) {
					unlink($output);
				}
				$data='<?xml version="1.0" encoding="UTF-8"?>'."\n".
					'<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">'."\n".
					'<fields>'."\n";
				foreach($pdfinfo as $field => $val) {
					$data.='<field name="'.$field.'">'."\n";
					if($field == 'bill_DOS1F' || $field == 'bill_DOS1T' || $field == 'bill_DOS2F' || $field == 'bill_DOS2T' || $field == 'bill_DOS3F' || $field == 'bill_DOS3T' || $field == 'bill_DOS4F' || $field == 'bill_DOS4T' || $field == 'bill_DOS5F' || $field == 'bill_DOS5T' || $field == 'bill_DOS6F' || $field == 'bill_DOS6T') {
						$val_array = str_split($val, 2);
						$val_array1 = array($val_array[0], ' ', $val_array[1], ' ', $val_array[2], $val_array[3]);
						$val = implode($val_array1);
					}
					if($field == 'bill_Box3A' || $field == 'bill_Box9B1' || $field == 'bill_Box11A1'){
						$val_array2 = str_split($val, 3);
						$val_array3 = array($val_array2[0], $val_array2[1], '', $val_array2[2], $val_array2[3]);
						$val = implode($val_array3);
					}
					if($field == 'bill_Box24F1' ||$field == 'bill_Box24F2' || $field == 'bill_Box24F3' || $field == 'bill_Box24F4' || $field == 'bill_Box24F5' || $field == 'bill_Box24F6' || $field == 'bill_Box28' || $field == 'bill_Box29' || $field == 'bill_Box30') {
						$val = rtrim($val);
					}
					if(is_array($val)) {
						foreach($val as $opt)
							$data.='<value>'.$opt.'</value>'."\n";
					} else {
						$data.='<value>'.$val.'</value>'."\n";
					}
					$data.='</field>'."\n";
				}
				$data.='<field name="Date">'."\n<value>".date('m/d/Y')."</value>\n</field>\n";
				$data.='<field name="Date2">'."\n<value>".date('m/d/y')."</value>\n</field>\n";
				$data.='</fields>'."\n".
					'<ids original="'.md5($input).'" modified="'.time().'" />'."\n".
					'<f href="'.$input.'" />'."\n".
					'</xfdf>'."\n";		
				$xfdf_fn= '/var/www/nosh/temp.xfdf';
				$xfp= fopen( $xfdf_fn, 'w' );
				if( $xfp ) {
				   fwrite( $xfp, $data );
				   fclose( $xfp );
				} else {
					$result_message = 'Error making xfdf!';
					echo $result_message;
					exit (0);
				}		
				$commandpdf = "pdftk " . $input . " fill_form " . $xfdf_fn . " output " . $output;
				$commandpdf1 = escapeshellcmd($commandpdf);
				exec($commandpdf1);
				if ($i > 0) {
					$input1 .= ' ' . $output;
				} else {
					$input1 = $output;	
				}
				$i++;
			}
			$user_id = $this->session->userdata('user_id');
			$file_path = "/var/www/nosh/hcfa1500_output_final_" . $user_id . ".pdf";
			$commandpdf2 = "pdftk " . $input1 . " cat output " . $file_path;
			$commandpdf3 = escapeshellcmd($commandpdf2);
			exec($commandpdf3);
			while(!file_exists($file_path)) {
				sleep(5);
			}
			if ($fp = fopen ($file_path, "r")) {
				$file_info = pathinfo($file_path);
				$file_name = $file_info["basename"];
				$file_size = filesize($file_path);
				$file_extension = strtolower($file_info["extension"]);	 
				if($file_extension!='pdf') {
					die('LOGGED! bad extension');
				}
				//ob_start();
				header('Content-type: application/pdf');
				header('Content-Disposition: attachment; filename="'.$file_name.'"');
				readfile($file_path);
				//header("Content-length: $file_size");
				//ob_end_flush(); 
				//while(!feof($fp)) {
					//$file_buffer = fread($fp, 2048);
					//echo $file_buffer;
				//}
				fclose($fp);
				unlink($file_path);
				exit();
			} else {
				die('LOGGED! bad file '.$file_path);
			}
		}
	}
	
	function print_hcfa()
	{
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/hcfa1500_output_final_" . $user_id . ".pdf";
		if ($fp = fopen ($file_path, "r")) {
			$file_info = pathinfo($file_path);
			$file_name = $file_info["basename"];
			$file_size = filesize($file_path);
			$file_extension = strtolower($file_info["extension"]);	 
			if($file_extension!='pdf') {
				die('LOGGED! bad extension');
			}
			ob_start();
			header('Content-type: application/pdf');
			header('Content-Disposition: attachment; filename="'.$file_name.'"');
			header("Content-length: $file_size");
			ob_end_flush(); 
			while(!feof($fp)) {
				$file_buffer = fread($fp, 2048);
				echo $file_buffer;
			}
			fclose($fp);
			unlink($file_path);
			exit();
		} else {
			die('LOGGED! bad file '.$file_path);
		}
	}
	
	function get_eid()
	{
		echo $this->session->userdata('eid');
	}
	
	function check_assessment()
	{
		$eid = $this->session->userdata('eid');
		$result = "No diagnoses for this encounter!<br>Make sure these are established before billing is submitted!";
		$query = $this->encounters_model->getAssessment($eid);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			if ($row['assessment_icd1'] != '') {
				$result = "OK!";
			}
		}
		echo $result;
	}
	
	function rcopia($command)
	{
		$practice = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
		$apiVendor = $practice['rcopia_apiVendor'];
		$apiPractice = $practice['rcopia_apiPractice'];
		$apiPass = $practice['rcopia_apiPass'];
		$apiSystem = $practice['rcopia_apiSystem'];
		$url = 'https://update.drfirst.com/servlet/rcopia.servlet.EngineServlet?';
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><RCExtRequest version = \"2.08\">";
		$xml .= "<Caller><VendorName>" . $apiVendor . "</VendorName><VendorPassword>" . $apiPass . "</VendorPassword></Caller>";
		$xml .= "<SystemName>" . $apiSystem . "</SystemName>";
		$xml .= "<RcopiaPracticeUsername>" . $apiPractice . "</RcopiaPracticeUsername>";
		$xml .= $command;
		$fields = array(
			'xml' => urlencode($xml)
		);
		$fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$headers = array(
			"Content-type: application/xml"
		);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch,CURLOPT_FAILONERROR,1);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, 15);
		$result = curl_exec($ch);
		return $result;
	}
	
	function pf_template_select_list($destination)
	{
		$pid = $this->session->userdata('pid');
		$this->db->where('pid', $pid);
		$this->db->where('forms_destination', $destination);
		$query = $this->db->get('forms');
		$data['options'] = array();
		if ($query->num_rows() > 0) {
			$data['options'][''] = "*Select completed forms below.";
			foreach ($query->result_array() as $row) {
				$data['options'][$row['forms_id']] = $row['forms_title'] . ", completed on " . date('m/d/Y', human_to_unix($row['forms_date']));
			}
		} else {
			$data['options'][''] = "No forms completed by patient.";
		}
		echo json_encode($data);
	}
	
	function get_pf_template($id)
	{
		$this->db->where('forms_id', $id);
		$row = $this->db->get('forms')->row_array();
		echo $row['forms_content_text'];
	}
} 
/* End of file: encounters.php */
/* Location: application/controllers/provider/encounters.php */
