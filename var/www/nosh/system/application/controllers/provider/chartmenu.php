<?php

class Chartmenu extends Application
{

	function Chartmenu()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->load->library('email');
		$this->auth->restrict('provider');
		$this->load->helper('download');
		$this->load->model('encounters_model');
		$this->load->model('chart_model');
		$this->load->model('demographics_model');
		$this->load->model('practiceinfo_model');
		$this->load->model('fax_model');
		$this->load->model('contact_model');
		$this->load->model('messaging_model');
		$this->load->model('audit_model');
	}
	
	// --------------------------------------------------------------------
	
	function index()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$user_id = $this->session->userdata('user_id');
		$practice_info = $this->practiceinfo_model->get();
		$row1 = $practice_info->row();
		$data['default_pos'] = $row1->default_pos_id;
		$this->auth->view('provider/chart/main', $data);
	}
	
	function get_appointments()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$user_id = $this->session->userdata('user_id');
		$now = now();
		$start_time = $now - 604800;
		$end_time = $now + 604800;
		$this->db->where('provider_id', $user_id);
		$this->db->where('pid', $pid);
		$this->db->where('start BETWEEN ' . $start_time . ' AND ' . $end_time);
		$query = $this->db->get('schedule');		
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row2) {
				$start_human = unix_to_human($row2['start']);
				$key = $row2['visit_type'] . ',' . $row2['appt_id'];
				$value = $start_human . ' (Appt ID: ' . $row2['appt_id'] . ')';
				$data[$key] = $value;
			}
		}
		echo json_encode($data);
	}
	
	function get_copay()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$query1 = $this->chart_model->getActiveInsurance($pid);
		if ($query1->num_rows() > 0) {
			$i = 0;
			$result = "";
			foreach ($query1->result_array() as $row1) {
				if ($i > 0) {
					$result .= "<br><br>";
				}
				$result .= 'Insurance: ' . $row1['insurance_plan_name'] . '; ID: ' . $row1['insurance_id_num'] . '; Group: ' . $row1['insurance_group'];
				if ($row1['insurance_copay'] != '') {
					$result .= '<br>Copay: ' . $row1['insurance_copay']; 
				}
				if ($row1['insurance_deductible'] != '') {
					$result .= '<br>Deductible: ' . $row1['insurance_deductible']; 
				}
				if ($row1['insurance_comments'] != '') {
					$result .= '<br>Comments: ' . $row1['insurance_comments']; 
				}
				$i++;
			}
		} else {
			$result = "None.";
		}
		echo $result;
		exit (0);
	}
	
	// Common Functions------------------------------------------------------------------
	
	function page_intro($title)
	{
		$practice = $this->practiceinfo_model->get()->row_array();
		$data['practiceName'] = $practice['practice_name'];
		$data['website'] = $practice['website'];
		$data['practiceInfo'] = $practice['street_address1'];
		if ($practice['street_address2'] != '') {
			$data['practiceInfo'] .= ', ' . $practice['street_address2'];
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice['city'] . ', ' . $practice['state'] . ' ' . $practice['zip'] . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice['phone'] . ', Fax: ' . $practice['fax'] . '<br />';
		if ($practice['practice_logo'] != '') {
			$logo = str_replace("/var/www/nosh/","", $practice['practice_logo']);
			$data['practiceLogo'] = "<img src='" . base_url() . $logo . "' border='0'>";
		} else {
			$data['practiceLogo'] = '<br><br><br><br><br>';
		}
		$data['title'] = $title;
		return $this->load->view('auth/pages/intro_page', $data, TRUE);
	}
	
	function page_ccr($pid)
	{
		$data['patientInfo'] = $this->demographics_model->get($pid)->row();
		$dob1 = human_to_unix($data['patientInfo']->DOB);
		$data['dob'] = date('m/d/Y', $dob1);
		$query_in = $this->chart_model->getActiveInsurance($pid);
		$data['insuranceInfo'] = '';
		if ($query_in->num_rows() > 0) {
			foreach ($query_in->result_array() as $row_in) {
				$data['insuranceInfo'] .= $row_in['insurance_plan_name'] . '; ID: ' . $row_in['insurance_id_num'] . '; Group: ' . $row_in['insurance_group'] . '; ' . $row_in['insurance_insu_lastname'] . ', ' . $row_in['insurance_insu_firstname'] . '<br><br>';
			}
		}
		$body = 'Active Issues:<br />';
		$query = $this->db->query("SELECT * FROM issues WHERE pid=$pid AND issue_date_inactive='0000-00-00 00:00:00'");
		if ($query->num_rows() > 0) {
			$body .= '<ul>';
			foreach ($query->result_array() as $row) {
				$body .= '<li>' . $row['issue'] . '</li>';
			}
			$body .= '</ul>';
		} else {
			$body .= 'None.';
		}
		$body .= '<hr />Active Medications:<br />';
		$query1 = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		if ($query1->num_rows() > 0) {
			$body .= '<ul>';
			foreach ($query1->result_array() as $row1) {
				if ($row1['rxl_sig'] == '') {
					$body .= '<li>' . $row1['rxl_medication'] . ' ' . $row1['rxl_dosage'] . ' ' . $row1['rxl_dosage_unit'] . ', ' . $row1['rxl_instructions'] . ' for ' . $row1['rxl_reason'] . '</li>';
				} else {
					$body .= '<li>' . $row1['rxl_medication'] . ' ' . $row1['rxl_dosage'] . ' ' . $row1['rxl_dosage_unit'] . ', ' . $row1['rxl_sig'] . ' ' . $row1['rxl_route'] . ' ' . $row1['rxl_frequency'] . ' for ' . $row1['rxl_reason'] . '</li>';
				}
			}
			$body .= '</ul>';
		} else {
			$body .= 'None.';
		}
		$body .= '<hr />Immunizations:<br />';
		$query2 = $this->db->query("SELECT * FROM immunizations WHERE pid=$pid ORDER BY imm_immunization ASC, imm_sequence ASC");
		if ($query2->num_rows() > 0) {
			$body .= '<ul>';
			foreach ($query2->result_array() as $row2) {
				if ($row2['imm_sequence'] == '1') {
					$sequence = 'first';
				}
				if ($row2['imm_sequence'] == '2') {
					$sequence = 'second';
				}
				if ($row2['imm_sequence'] == '3') {
					$sequence = 'third';
				}
				if ($row2['imm_sequence'] == '4') {
					$sequence = 'fourth';
				}
				if ($row2['imm_sequence'] == '5') {
					$sequence = 'fifth';
				}
				$date1 = human_to_unix($row2['imm_date']);
				$date = date('F jS, Y', $date1);
				$body .= '<li>' . $row2['imm_immunization'] . ', ' . $sequence . ', given on ' . $date . '</li>';
			}
			$body .= '</ul>';
		} else {
			$body .= 'None.';
		}
		$body .= '<hr />Allergies:<br />';
		$query3 = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive='0000-00-00 00:00:00'");
		if ($query3->num_rows() > 0) {
			$body .= '<ul>';
			foreach ($query3->result_array() as $row3) {
				$body .= '<li>' . $row3['allergies_med'] . ' - ' . $row3['allergies_reaction'] . '</li>';
			}
			$body .= '</ul>';
		} else {
			$body .= 'No known allergies.';
		}
		$body .= '<br />Printed by ' . $this->session->userdata['displayname'] . '.';
		$data['letter'] = $body;
		return $this->load->view('auth/pages/ccr_page',$data, TRUE);
	}
	
	function page_coverpage($job_id, $totalpages, $faxrecipients, $date)
	{
		$faxInfo = $this->fax_model->getFax($job_id);
		$faxInfo2 = $faxInfo->row_array();
		$data = array(
			'user' => $this->session->userdata('fullname'),
			'faxrecipients' => $faxrecipients,
			'faxsubject' => $faxInfo2['faxsubject'],
			'faxmessage' => $faxInfo2['faxmessage'],
			'faxpages' => $totalpages,
			'faxdate' => $date
		);
		return $this->load->view('auth/pages/coverpage', $data, TRUE);
	}
	
	function fax_document($pid, $type, $coverpage, $filename, $file_original, $faxnumber, $faxrecipient, $job_id, $sendnow)
	{
		$demo_row = $this->demographics_model->get($pid)->row_array();
		if ($job_id == '') {
			$fax_data = array(
				'user' => $this->session->userdata('displayname'),
				'faxsubject' => $type . ' for ' . $demo_row['firstname'] . ' ' . $demo_row['lastname'],
				'faxcoverpage' => $coverpage
			);
			$job_id = $this->fax_model->addFax($fax_data);
			$fax_directory = '/var/www/nosh/sentfax/' . $job_id;
			mkdir($fax_directory, 0777);
		}
		$filename_parts = explode("/", $filename);
		$fax_filename = $fax_directory . "/" . end($filename_parts);
		copy($filename, $fax_filename);
		$pagecount = $this->fax_model->pagecount($fax_filename);
		if ($file_original == '') {
			$file_original = $type . ' for ' . $demo_row['firstname'] . ' ' . $demo_row['lastname'];
		}
		$pages_data = array(
			'file' => $fax_filename,
			'file_original' => $file_original,
			'file_size' => '',
			'pagecount' => $pagecount,
			'job_id' => $job_id
		);
		$this->fax_model->addPages($pages_data);
		if ($sendnow == "yes") {
			$message = $this->send_fax($job_id, $faxnumber, $faxrecipient);
		} else {
			$message = $job_id;
		}
		return $message;
	}
	
	function send_fax($job_id, $faxnumber, $faxrecipient)
	{
		$fax_data = $this->fax_model->getFax($job_id)->row_array();
		if ($faxnumber != '' && $faxrecipient != '') {
			$meta = array("(", ")", "-", " ");
			$fax = str_replace($meta, "", $faxnumber);
			$send_list_data = array(
				'faxrecipient' => $faxrecipient,
				'faxnumber' => str_replace($meta, "", $faxnumber),
				'job_id' => $job_id
			);
			$this->fax_model->addSendList($send_list_data);
		}
		$faxrecipients = '';
		$faxnumbers = '';
		$recipientlist = $this->fax_model->getRecipientList($job_id);
		foreach ($recipientlist->result_array() as $row) {
			$faxrecipients .= $row['faxrecipient'] . ', Fax: ' . $row['faxnumber'] . "\n";
			if ($faxnumbers != '') {
				$faxnumbers .= ',' . $row['faxnumber'];
			} else {
				$faxnumbers .= $row['faxnumber'];
			}
		}
		$practice_row = $this->db->get('practiceinfo')->row_array();
		$faxnumber_array = explode(",", $faxnumbers);
		$faxnumber_to = array();
		foreach ($faxnumber_array as $faxnumber_row) {
			$faxnumber_to[] = $faxnumber_row . '@' . $this->fax_model->fax_type();
		}
		$config['protocol']='smtp';
		$config['smtp_host']='ssl://smtp.googlemail.com';
		$config['smtp_port']='465';
		$config['smtp_timeout']='30';
		$config['charset']='utf-8';
		$config['newline']="\r\n";
		$config['smtp_user']=$practice_row['smtp_user'];
		$config['smtp_pass']=$practice_row['smtp_pass'];
		$this->email->initialize($config);
		$this->email->from($practice_row['email'], $practice_row['practice_name']);
		$this->email->to($faxnumber_to);
		$this->email->subject($fax_data['faxsubject']);
		$date = mdate("%M %d, %Y, %h:%i");
		$faxpages = '';
		$totalpages = 0;
		$senddate = date('Y-m-d H:i:s');
		$pagesInfo = $this->fax_model->getPages($job_id);
		foreach ($pagesInfo->result_array() as $row4) {
			$faxpages .= ' ' . $row4['file'];
			$totalpages = $totalpages + $row4['pagecount'];
		}
		if ($fax_data['faxcoverpage'] == 'yes') {
			$cover_html = $this->page_intro('Cover Page');
			$cover_html .= $this->page_coverpage($job_id, $totalpages, $faxrecipients, $date);
			$fax_directory = '/var/www/nosh/sentfax/' . $job_id;
			$cover_filename = $fax_directory . '/coverpage.pdf';
			$footer = '<div style="border-top: 1px solid #000000;text-align: center; font-size: 8px;">';
			$footer .= "<p>CONFIDENTIALITY NOTICE: The information contained in this facsimile transmission is intended for the recipient named above. If you are not the intended recipient or the intended recipient's agent, you are hereby notified that dissemination, copying, or distribution of the information contained in the transmission is prohibited. If you are not the intended recipient, please notify us immediately by telephone and return the documents to us by mail. Thank you.</p></div>";
			$this->load->library('mpdf');
			$this->mpdf=new mpdf('','Letter',0,'',26,26,26,26,9,9,'P');
			$this->mpdf->useOnlyCoreFonts = true;
			$this->mpdf->AddPage();
			$this->mpdf->SetHTMLFooter($footer,'O');
			$this->mpdf->SetHTMLFooter($footer,'E');
			$this->mpdf->WriteHTML($cover_html);
			$this->mpdf->SetTitle('Coverpage');
			$this->mpdf->debug = true;
			$this->mpdf->showImageErrors = true;
			$this->mpdf->Output($cover_filename,'F');
			$this->email->attach($cover_filename);
		}
		foreach ($pagesInfo->result_array() as $row5) {
			$this->email->attach($row5['file']);
		}
		$fax_update_data = array(
			'sentdate' => date('Y-m-d'),
			'ready_to_send' => '1',
			'senddate' => $senddate,
			'faxdraft' => '0',
			'attempts' => '0'
		);
		$this->fax_model->updateFax($job_id, $fax_update_data);
		$this->email->send();
		$this->session->unset_userdata('job_id');
		return 'Fax Job ' . $job_id . ' Sent';
	}
	
	// --------------------------------------------------------------------
	
	function menu()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$data['lastvisit'] = $this->encounters_model->lastEncounterVisitDate($pid);
		$this->db->where('pid', $pid);
		$now = now();
		$this->db->where('start >', $now);
		$query = $this->db->get('schedule');
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$date = date('F jS, Y, g:i A', $result['start']);
			$data['nextvisit'] = '<br>' . $date;
		} else {
			$data['nextvisit'] = 'None.';
		}
		$data['id'] = $pid;
		$this->load->view('auth/pages/provider/chart/menu', $data);
	}
	
	function demographics_load()
	{
		$data['id'] = $this->session->userdata('pid');
		$data['ptname'] = $this->session->userdata('ptname');
		$data['agealldays'] = $this->session->userdata('agealldays');
		$data['age'] = $this->session->userdata('age');
		$pid = $this->session->userdata('pid');
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row();
		$dob1 = human_to_unix($row->DOB);
		$data['dob'] = date('F jS, Y', $dob1);
		if ($row->sex == 'm') {
			$data['gender'] = 'Male';
		} 
		if ($row->sex == 'f') {
			$data['gender'] = 'Female';
		}
		$data['nickname'] = $row->nickname;
		if ($row->address == '') {
			$data['new'] = 'Y';
		} else {
			$data['new'] = 'N';
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function issues_load()
	{
		$this->load->view('auth/pages/provider/chart/issues');
	}
	
	function rx_load()
	{
		$this->load->view('auth/pages/provider/chart/rx');
	}
	
	function supplements_load()
	{
		$this->load->view('auth/pages/provider/chart/supplements');
	}
	
	function imm_load()
	{
		$this->load->view('auth/pages/provider/chart/imm');
	}
	
	function lab_load()
	{
		$this->load->view('auth/pages/provider/chart/lab');
	}
	
	function rad_load()
	{
		$this->load->view('auth/pages/provider/chart/rad');
	}
	
	function cp_load()
	{
		$this->load->view('auth/pages/provider/chart/cp');
	}
	
	function ref_load()
	{
		$this->load->view('auth/pages/provider/chart/ref');
	}
	
	function billing_load()
	{
		$this->load->view('auth/pages/provider/chart/billing');
	}
	
	function print_load()
	{
		$this->load->view('auth/pages/provider/chart/print');
	}
	
	function letter_load()
	{
		$this->load->view('auth/pages/provider/chart/letter');
	}
	
	function mtm_load()
	{
		$this->load->view('auth/pages/provider/chart/mtm');
	}
	
	
	// --------------------------------------------------------------------

	function inactivate_pt()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$data = array(
			'active' => '0'
		);
		$this->demographics_model->update($pid, $data);
		$this->audit_model->update();
	}
	
	function encounter_id_set()
	{
		$eid = $this->session->userdata('eid');
		if ($eid != FALSE) {
			$this->session->unset_userdata('eid');
		}
		$eid = $this->input->post('eid');
		$this->session->set_userdata('eid', $eid);		
	}

	// --------------------------------------------------------------------
	
	function encounters()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND addendum='n'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND addendum='n' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}

	// --------------------------------------------------------------------
	
	function demographics_list()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->db->query("SELECT * FROM demographics WHERE pid=$pid");
		$result = '<strong>Demographics:</strong>';
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$result .= '<p class="tips"><strong>Address:</strong><br>' . $row['address'] . '<br>' . $row['city'] . ', ' . $row['state'] . ' ' . $row['zip'] . '</p>';
			$result .= '<p class="tips"><strong>Phone Numbers:</strong><br>Home: ' . $row['phone_home'] . '<br>Work: ' . $row['phone_work'] . '<br>Cell: ' . $row['phone_cell'] . '<br>Email: ' . $row['email'] . '</p>';
			$result .= '<p class="tips"><strong>Emergency Contact:</strong><br>Contact: ' . $row['emergency_contact'] . ', ' . $row['emergency_phone'] . '</p>';
			$gender = $this->session->userdata('gender');
			if ($gender == 'female') {
				if ($row['pregnant'] != 'no') {
					$pregnant = 'Yes';
				} else {
					$pregnant = 'No';
				}
				$result .= '<p class="tips"><strong>Other:</strong><br>Sexually Active: ' . ucfirst($row['sexuallyactive']) . '<br>Tobacco Use: ' . ucfirst($row['tobacco']) . '<br>Pregnant: ' . $pregnant . '</p>';
			} else {
				$result .= '<p class="tips"><strong>Other:</strong><br>Sexually Active: ' . ucfirst($row['sexuallyactive']) . '<br>Tobacco Use: ' . ucfirst($row['tobacco']) . '</p>';
			}
			$result .= '<p class="tips">Active since ' . $row['date'] . '</p>';
			if ($row['id'] != '') {
				$result .= '<p class="tips">Online account is active.</p>';
			} else {
				$result .= '<p class="tips">No online account.</p>';
			}
			$this->db->where('pid', $pid);
			$this->db->where('status', 'LMC');
			$count1 = $this->db->get('schedule')->num_rows();
			$this->db->where('pid', $pid);
			$this->db->where('status', 'DNKA');
			$count2 = $this->db->get('schedule')->num_rows();
			$result .= '<p class="tips"># Last minute cancellations: ' . $count1 . '</p>';
			$result .= '<p class="tips"># Did not keep appointments: ' . $count2 . '</p>';
			$result .= '<p class="tips">Billing Notes: ' . nl2br($row['billing_notes']) . '</p>';
			$query1 = $this->chart_model->getActiveInsurance($pid);
			if ($query1->num_rows() > 0) {
				$result .= '<p class="tips"><strong>Active Insurance:</strong>';
				foreach ($query1->result_array() as $row1) {
					$result .= '<br>' . $row1['insurance_plan_name'] . '; ID: ' . $row1['insurance_id_num'] . '; Group: ' . $row1['insurance_group'];
					if ($row1['insurance_copay'] != '') {
						$result .= '; Copay: ' . $row1['insurance_copay']; 
					}
					if ($row1['insurance_deductible'] != '') {
						$result .= '; Deductible: ' . $row1['insurance_deductible']; 
					}
					if ($row1['insurance_comments'] != '') {
						$result .= '; Comments: ' . $row1['insurance_comments']; 
					}
				}
				$result .= '</p>';
			}
		} else {
			$result .= 'None available.';
		}
		echo $result;
		exit( 0 );
	}
	
	function demographics()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->db->query("SELECT * FROM demographics WHERE pid=$pid");
		$row = $query->row_array();
		echo json_encode($row);
		exit( 0 );
	}
	
	function edit_demographics()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo 'Close Chart';
			exit (0);
		}
		$date = strtotime($this->input->post('issue_date_active'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_active = mdate($datestring, $date);
		$dob1 = $this->input->post('DOB');
		$dob2 = strtotime($dob1);
		$datestring = "%Y-%m-%d";
		$dob = mdate($datestring, $dob2);
		if ($this->input->post('reminder_method')=="Cellular Phone") {
			$meta = array("(", ")", "-", " ");
			$number = str_replace($meta, "", $this->input->post('phone_cell'));
			$reminder_to = $number . '@' . $this->input->post('cell_carrier');
		} else {
			if ($this->input->post('reminder_method')=="Email") {
				$reminder_to = $this->input->post('email');
			} else {
				$reminder_to = "";
			}
		}
		$data = array(
			'lastname' => $this->input->post('lastname'),
			'firstname' => $this->input->post('firstname'),
			'middle' => $this->input->post('middle'),
			'nickname' => $this->input->post('nickname'),
			'title' => $this->input->post('title'),
			'sex' => $this->input->post('gender'),
			'DOB'=> $dob,
			'ss' => $this->input->post('ss'),
			'race' => $this->input->post('race'),
			'ethnicity' => $this->input->post('ethnicity'),
			'language' => $this->input->post('language'),
			'address' => $this->input->post('address'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zip' => $this->input->post('zip'),
			'phone_home' => $this->input->post('phone_home'),
			'phone_work' => $this->input->post('phone_work'),
			'phone_cell' => $this->input->post('phone_cell'),
			'email' => $this->input->post('email'),
			'marital_status' => $this->input->post('marital_status'),
			'partner_name' => $this->input->post('partner_name'),
			'employer' => $this->input->post('employer'),
			'emergency_contact' => $this->input->post('emergency_contact'),
			'emergency_phone' => $this->input->post('emergency_phone'),
			'reminder_method' => $this->input->post('reminder_method'),
			'reminder_to' => $reminder_to,
			'cell_carrier' => $this->input->post('cell_carrier'),
			'preferred_provider' => $this->input->post('preferred_provider'),
			'preferred_pharmacy' => $this->input->post('preferred_pharmacy'),
			'active' => $this->input->post('active'),
			'other1' => $this->input->post('other1'),
			'other2' => $this->input->post('other2'),
			'caregiver' => $this->input->post('caregiver'),
			'referred_by' => $this->input->post('referred_by'),
			'comments' => $this->input->post('comments'),
			'rcopia_sync' => 'n',
			'race_code' => $this->input->post('race_code'),
			'ethnicity_code' => $this->input->post('ethnicity_code'),
			'guardian_lastname' => $this->input->post('guardian_lastname'),
			'guardian_firstname' => $this->input->post('guardian_firstname'),
			'guardian_relationship' => $this->input->post('guardian_relationship'),
			'guardian_code' => $this->input->post('guardian_code'),
			'guardian_address' => $this->input->post('guardian_address'),
			'guardian_city' => $this->input->post('guardian_city'),
			'guardian_state' => $this->input->post('guardian_state'),
			'guardian_zip' => $this->input->post('guardian_zip'),
			'guardian_phone_home' => $this->input->post('guardian_phone_home'),
			'guardian_phone_work' => $this->input->post('guardian_phone_work'),
			'guardian_phone_cell' => $this->input->post('guardian_phone_cell'),
			'guardian_email' => $this->input->post('guardian_email'),
			'lang_code' => $this->input->post('lang_code')
		);
		$this->demographics_model->update($pid, $data);
		$this->audit_model->update();
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row(); 
		$data['ptname'] = $row->firstname . ' ' . $row->lastname;
		$dob3 = human_to_unix($row->DOB);
		$now = time();
		$age1 = timespan($dob3, $now);
		$pos1 = strpos($age1, ',');
		$age2 = substr($age1, 0, $pos1);
		$pos2 = $pos1 + 1;
		$pos3 = strpos($age1, ',', $pos2);
		$age3 = substr($age1, 0, $pos3);
		if ($age2 == '1 Year' OR $age3 == '2 Years' OR $age3 == '3 Years') {
			$data['age'] = $age3 . ' Old';
		} else {
			$data['age'] = $age2 . ' Old';
		}
		$agediff = ($now - $dob3)/86400;
		$pos4 = strpos($agediff, '.');
		$data['agealldays'] = substr($agediff, 0, $pos4);
		if ($row->sex == 'm') {
			$data['gender'] = 'Male';
			$data['gender1'] = 'male';
		} 
		if ($row->sex == 'f') {
			$data['gender'] = 'Female';
			$data['gender1'] = 'female';
		}
		$this->session->set_userdata('pid', $pid);
		$this->session->set_userdata('gender', $data['gender1']);
		$this->session->set_userdata('age', $data['age']);
		$this->session->set_userdata('agealldays', $data['agealldays']);
		$this->session->set_userdata('ptname', $data['ptname']);
		echo "Patient information updated!";
	}
	
	function check_registration_code()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$this->db->where('pid', $pid);
		$result = $this->db->get('demographics')->row_array();
		if ($result['registration_code'] != '') {
			echo "Registration Code: " . $result['registration_code'];
		} else {
			echo "n";
		}
	}
	
	function register_patient()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo 'Close Chart';
			exit (0);
		}
		$length = 6;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$token = '';
		for ($i = 0; $i < $length; $i++) {
			$token .= $characters[mt_rand(0, strlen($characters)-1)];
		}
		$data = array(
			'registration_code' => $token
		);
		$this->demographics_model->update($pid, $data);
		$this->audit_model->update();
		$this->db->where('pid', $pid);
		$result = $this->db->get('demographics')->row_array();
		if ($result['email'] != '') {
			$practice_result = $this->db->get('practiceinfo')->row_array();
			$displayname = $this->session->userdata('displayname');
			$message = 'This is a notice that you have been registered as a user on the patient portal for ' . $practice_result['practice_name'] . ". Login here: ".  $practice_result['patient_portal'] . ". Your registration code is " . $token . ".";
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = "ssl://smtp.googlemail.com";
			$config['smtp_port'] = '465';
			$config['smtp_timeout'] = '30';
			$config['smtp_user'] = $practice_result['smtp_user'];
			$config['smtp_pass'] = $practice_result['smtp_pass'];
			$config['charset'] = 'utf-8';
			$config['newline'] = "\r\n";
			$this->email->initialize($config);
			$this->email->from($practice_result['email'], 'NOSH ChartingSystem User Registration');
			$this->email->to($result['email']);
			$this->email->subject('New User Request');
			$this->email->message($message);
			$this->email->send();
			echo "Email sent. ";
		}
		echo "Registration Code: " . $token;
	}
	
	// --------------------------------------------------------------------
	
	function insurance()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM insurance WHERE pid=$pid AND insurance_plan_active='Yes'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM insurance WHERE pid=$pid AND insurance_plan_active='Yes' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;	
		echo json_encode($response);
		exit( 0 );
	}
	
	function insurance_inactive()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM insurance WHERE pid=$pid AND insurance_plan_active='No'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM insurance WHERE pid=$pid AND insurance_plan_active='No' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function edit_insurance()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$dob1 = $this->input->post('insurance_insu_dob');
		$dob2 = strtotime($dob1);
		$datestring = "%Y-%m-%d";
		$dob = mdate($datestring, $dob2);
		$data = array(
			'insurance_plan_name' => $this->input->post('insurance_plan_name'),
			'address_id' => $this->input->post('address_id'),
			'insurance_id_num' => $this->input->post('insurance_id_num'),
			'insurance_group' => $this->input->post('insurance_group'),
			'insurance_order' => $this->input->post('insurance_order'),
			'insurance_relationship' => $this->input->post('insurance_relationship'),
			'insurance_copay' => $this->input->post('insurance_copay'),
			'insurance_deductible' => $this->input->post('insurance_deductible'),
			'insurance_comments' => $this->input->post('insurance_comments'),
			'insurance_insu_lastname' => $this->input->post('insurance_insu_lastname'),
			'insurance_insu_firstname' => $this->input->post('insurance_insu_firstname'),
			'insurance_insu_dob' => $dob,
			'insurance_insu_gender' => $this->input->post('insurance_insu_gender'),
			'insurance_insu_address' => $this->input->post('insurance_insu_address'),
			'insurance_insu_city' => $this->input->post('insurance_insu_city'),
			'insurance_insu_state' => $this->input->post('insurance_insu_state'),
			'insurance_insu_zip' => $this->input->post('insurance_insu_zip'),
			'insurance_insu_phone' => $this->input->post('insurance_insu_phone'),
			'insurance_plan_active' => 'Yes',
			'pid' => $pid
		);	
		if($this->input->post('insurance_id') == '') {
			$add = $this->chart_model->addInsurance($data);
			if ($add) {
				$this->audit_model->add();
				echo "Insurance added!";
			} else {
				echo "Error adding insurance!";
			}
		} else {
			$update = $this->chart_model->updateInsurance($this->input->post('insurance_id'), $data);
			$this->audit_model->update();
			echo "Insurance updated!";
		}
	}
	
	function inactivate_insurance()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$data = array(
			'insurance_plan_active' => 'No'
		);
		$update = $this->chart_model->updateInsurance($this->input->post('insurance_id'), $data);
		$this->audit_model->update();
		echo "Insurance inactivated!";
	}
	
	function delete_insurance()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$this->chart_model->deleteInsurance($this->input->post('insurance_id'));
		$this->audit_model->delete();
		echo "Insurance deleted!";
	}

	function reactivate_insurance()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$data = array(
			'insurance_plan_active' => 'Yes'
		);
		$update = $this->chart_model->updateInsurance($this->input->post('insurance_id'), $data);
		$this->audit_model->update();
		echo "Insurance reactivated!";
	}
	
	function copy_address()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->db->query("SELECT * FROM demographics WHERE pid=$pid");
		$row = $query->row_array();
		echo json_encode($row);
		exit( 0 );
	}
	
	function edit_insurance_provider()
	{
		$data = array(
			'displayname' => $this->input->post('facility'),
			'facility' => $this->input->post('facility'),
			'street_address1' => $this->input->post('street_address1'),
			'street_address2' => $this->input->post('street_address2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zip' => $this->input->post('zip'),
			'phone' => $this->input->post('phone'),
			'insurance_plan_payor_id' => $this->input->post('insurance_plan_payor_id'),
			'insurance_plan_type' => $this->input->post('insurance_plan_type'),
			'insurance_plan_assignment' => $this->input->post('insurance_plan_assignment'),
			'insurance_plan_ppa_phone' => $this->input->post('insurance_plan_ppa_phone'),
			'insurance_plan_ppa_fax' => $this->input->post('insurance_plan_ppa_fax'),
			'insurance_plan_ppa_url' => $this->input->post('insurance_plan_ppa_url'),
			'insurance_plan_mpa_phone' => $this->input->post('insurance_plan_mpa_phone'),
			'insurance_plan_mpa_fax' => $this->input->post('insurance_plan_mpa_fax'),
			'insurance_plan_mpa_url' => $this->input->post('insurance_plan_mpa_url'),
			'specialty' => 'Insurance',
			'insurance_box_31' => $this->input->post('insurance_box_31'),
			'insurance_box_32a' => $this->input->post('insurance_box_32a')
		);
		
		if($this->input->post('address_id') == '') {
			$add = $this->contact_model->addContact($data);
			$this->audit_model->add();
			if ($add) {
				$result['message'] = "Insurance provider added!";
				$this->db->select('displayname');
				$this->db->where('address_id', $add);
				$query = $this->db->get('addressbook');
				$row = $query->row_array();
				$result['item'] = $row['displayname'];
				$result['id'] = $add;
				echo json_encode($result);
				exit( 0 );
			} else {
				$result['message'] = "Error adding insurance provider";
				echo json_encode($result);
				exit( 0 );
			}
		} else {
			$update = $this->contact_model->updateContact($this->input->post('address_id'), $data);
			$this->audit_model->update();
			$result['message'] = "Insurance provider updated!";
			$this->db->where('address_id', $this->input->post('address_id'));
			$query = $this->db->get('addressbook');
			$row = $query->row_array();
			$result['item'] = $row['displayname'];
			$result['id'] = $this->input->post('address_id');
			echo json_encode($result);
			exit( 0 );
		}
	}
	
	// --------------------------------------------------------------------

	function issues_list()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->db->query("SELECT * FROM issues WHERE pid=$pid AND issue_date_inactive='0000-00-00 00:00:00'");
		$result = '<strong>Issues:</strong>';
		if ($query->num_rows() > 0) {
			$result .= '<ul>';
			foreach ($query->result_array() as $row) {
				$result .= '<li>' . $row['issue'] . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= ' None.';
		}
		echo $result;
		exit( 0 );
	}
	
	function issues()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM issues WHERE pid=$pid AND issue_date_inactive='0000-00-00 00:00:00'");
		$count = $query->num_rows(); 
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; 
		if($start < 0) $start = 0;	
		$query1 = $this->db->query("SELECT * FROM issues WHERE pid=$pid AND issue_date_inactive='0000-00-00 00:00:00' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}
	
	function issues_inactive()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM issues WHERE pid=$pid AND issue_date_inactive!='0000-00-00 00:00:00'");
		$count = $query->num_rows(); 
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; 
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM issues WHERE pid=$pid AND issue_date_inactive!='0000-00-00 00:00:00' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;	
		echo json_encode($response);
		exit( 0 );
	}
	
	function edit_issue()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$date = strtotime($this->input->post('issue_date_active'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_active = mdate($datestring, $date);	
		$data = array(
			'issue' => $this->input->post('issue'),
			'issue_date_active' => $date_active,
			'issue_date_inactive' => '',
			'issue_provider' => $this->session->userdata('displayname'),
			'pid' => $pid,
			'rcopia_sync' => 'n'
		);	
		if($this->input->post('issue_id') == '') {
			$add = $this->chart_model->addIssue($data);
			$this->audit_model->add();
			$result = $this->practiceinfo_model->get()->row_array();
			if ($result['mtm_extension'] == 'y') {
				$this->add_mtm_alert($pid, 'issues');
			}
			if ($add) {
				echo "Issue added!";
				exit (0);
			} else {
				echo "Error adding issue!";
				exit (0);
			}
		} else {
			$update = $this->chart_model->updateIssue($this->input->post('issue_id'), $data);
			$this->audit_model->update();
			echo "Issue updated!";
			exit (0);
		}
	}
	
	function inactivate_issue()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$date = now();
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_inactive = mdate($datestring, $date);
		$data = array(
			'issue_date_inactive' => $date_inactive,
			'rcopia_sync' => 'nd1'
		);
		$update = $this->chart_model->updateIssue($this->input->post('issue_id'), $data);
		$this->audit_model->update();
		echo "Issue inactivated!";
		exit (0);
	}
	
	function delete_issue()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$issue_id = $this->input->post('issue_id');
		$practice = $this->practiceinfo_model->get()->row_array();
		if($practice['rcopia_extension'] == 'y') {
			$data = array(
				'rcopia_sync' => 'nd'
			);
			$update = $this->chart_model->updateIssue($issue_id, $data);
			while(!$this->check_rcopia_delete('issues', $issue_id)) {
				sleep(2);
			}
			$this->chart_model->deleteIssue($issue_id);
			$this->audit_model->delete();
		} else {
			$this->chart_model->deleteIssue($issue_id);
			$this->audit_model->delete();
		}
		echo "Issue deleted!";
		exit (0);
	}
	
	function reactivate_issue()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$data = array(
			'issue_date_inactive' => '0000-00-00 00:00:00',
			'rcopia_sync' => 'n'
		);
		$update = $this->chart_model->updateIssue($this->input->post('issue_id'), $data);
		$this->audit_model->update();
		echo "Issue reactivated!";
		exit (0);
	}
	
	// --------------------------------------------------------------------

	function medications_list()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		$result = '<strong>Active Medications:</strong>';
		if ($query->num_rows() > 0) {
			$result .= '<ul>';
			foreach ($query->result_array() as $row) {
				if ($row['rxl_sig'] == '') {
					$result .= '<li>' . $row['rxl_medication'] . ' ' . $row['rxl_dosage'] . ' ' . $row['rxl_dosage_unit'] . ', ' . $row['rxl_instructions'] . ' for ' . $row['rxl_reason'] . '</li>';
				} else {
					$result .= '<li>' . $row['rxl_medication'] . ' ' . $row['rxl_dosage'] . ' ' . $row['rxl_dosage_unit'] . ', ' . $row['rxl_sig'] . ' ' . $row['rxl_route'] . ' ' . $row['rxl_frequency'] . ' for ' . $row['rxl_reason'] . '</li>';
				}
			}
			$result .= '</ul>';
		} else {
			$result .= ' None.';
		}
		echo $result;
		exit( 0 );
	}
	
	function medications()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;	
		echo json_encode($response);
		exit( 0 );
	}
	
	function medications_inactive()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive!='0000-00-00 00:00:00'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive!='0000-00-00 00:00:00' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function edit_medication()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$date = strtotime($this->input->post('rxl_date_active'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_active = mdate($datestring, $date);	
		$data = array(
			'rxl_medication' => $this->input->post('rxl_medication'),
			'rxl_dosage' => $this->input->post('rxl_dosage'),
			'rxl_dosage_unit' => $this->input->post('rxl_dosage_unit'),
			'rxl_sig' => $this->input->post('rxl_sig'),
			'rxl_route' => $this->input->post('rxl_route'),
			'rxl_frequency' => $this->input->post('rxl_frequency'),
			'rxl_instructions' => $this->input->post('rxl_instructions'),
			'rxl_reason' => $this->input->post('rxl_reason'),
			'rxl_date_active' => $date_active,
			'rxl_date_prescribed' => '',
			'rxl_date_inactive' => '',
			'rxl_date_old' => '',
			'rxl_provider' => $this->session->userdata('displayname'),
			'id' => $this->session->userdata('user_id'),
			'pid' => $pid,
			'rcopia_sync' => 'n',
			'rxl_ndcid' => $this->input->post('rxl_ndcid')
		);	
		if($this->input->post('rxl_id') == '') {
			$add = $this->chart_model->addMedication($data);
			$this->audit_model->add();
			$result = $this->practiceinfo_model->get()->row_array();
			if ($result['mtm_extension'] == 'y') {
				$this->add_mtm_alert($pid, 'medications');
			}
			if ($add) {
				echo "Medication added!";
				exit (0);
			} else {
				echo "Error adding medication!";
				exit (0);
			}
		} else {
			$update = $this->chart_model->updateMedication($this->input->post('rxl_id'), $data);
			$this->audit_model->update();
			echo "Medication updated!";
			exit (0);
		}
	}
	
	function past_medication()
	{
		$pid = $this->session->userdata('pid');
		$rxl_medication = $this->input->post('rxl_medication');
		$this->db->where('pid', $pid);
		$this->db->where('rxl_medication', $rxl_medication);
		$query = $this->db->get('rx_list');
		$result['header'] = 'Dates prescribed for ' . $rxl_medication . ': ';
		$result['item'] = '';
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$date = strtotime($row['rxl_date_prescribed']);
				$datestring = "%m/%d/%Y";
				$date_prescribed = mdate($datestring, $date);
				$result['item'] .= $date_prescribed . '<br>';
			}
		} else {
			$result['item'] .= 'None.';
		}
		echo json_encode($result);
		exit (0);
	}
	
	function inactivate_medication()
	{
		$rxl_id = $this->input->post('rxl_id');
		$this->db->where('rxl_id', $rxl_id);
		$query = $this->db->get('rx_list');
		$row = $query->row_array();
		if ($row['rxl_sig'] == '') {
			$instructions = $row['rxl_instructions'];
		} else {
			$instructions = $row['rxl_sig'] . ' ' . $row['rxl_route'] . ' ' . $row['rxl_frequency'];
		}
		$result['medtext'] =  $row['rxl_medication'] . ' ' . $row['rxl_dosage'] . ' ' . $row['rxl_dosage_unit'] . ', ' . $instructions . ' for ' . $row['rxl_reason'];
		$date = now();
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_inactive = mdate($datestring, $date);
		$data = array(
			'rxl_date_inactive' => $date_inactive,
			'rcopia_sync' => 'nd1'
		);
		$update = $this->chart_model->updateMedication($rxl_id, $data);
		$this->audit_model->update();
		$result['message'] = "Medication inactivated!";
		echo json_encode($result);
		exit (0);
	}
	
	function eie_medication()
	{
		$old_rxl_id = $this->input->post('rxl_id');
		$this->db->where('rxl_id', $old_rxl_id);
		$query = $this->db->get('rx_list');
		$row = $query->row_array();
		if ($row['rxl_sig'] == '') {
			$instructions = $row['rxl_instructions'];
		} else {
			$instructions = $row['rxl_sig'] . ' ' . $row['rxl_route'] . ' ' . $row['rxl_frequency'];
		}
		$result['medtext'] =  $row['rxl_medication'] . ' ' . $row['rxl_dosage'] . ' ' . $row['rxl_dosage_unit'] . ', ' . $instructions . ' for ' . $row['rxl_reason'];
		$rxl_medication = $row['rxl_medication'];
		$this->db->where('rxl_medication', $rxl_medication);
		$this->db->where('rxl_date_inactive', '0000-00-00 00:00:00');
		$this->db->where('rxl_date_old !=', '0000-00-00 00:00:00');
		$this->db->order_by('rxl_date_old', 'desc');
		$this->db->limit(1);
		$query1 = $this->db->get('rx_list');
		$count1 = $query1->num_rows();
		if ($count1 > 0) {
			$row1 = $query1->row_array();
			$rxl_id = $row1['rxl_id'];
			$data = array(
				'rxl_date_old' => '0000-00-00 00:00:00',
				'rcopia_sync' => 'nd1'
			);
			$update = $this->chart_model->updateMedication($rxl_id, $data);
			$this->audit_model->update();
		}	
		$practice = $this->practiceinfo_model->get()->row_array();
		if($practice['rcopia_extension'] == 'y') {
			$data = array(
				'rcopia_sync' => 'nd'
			);
			$update = $this->chart_model->updateMedication($old_rxl_id, $data);
			while(!$this->check_rcopia_delete('rx_list', $old_rxl_id)) {
				sleep(2);
			}
			$this->chart_model->deleteMedication($old_rxl_id);
			$this->audit_model->delete();
		} else {
			$this->chart_model->deleteMedication($old_rxl_id);
			$this->audit_model->delete();
		}
		$result['message'] = "Entered medication in error process complete!";
		echo json_encode($result);
		exit (0);
	}
	
	function check_rcopia_delete($table, $id)
	{
		if ($table == 'rx_list') {
			$key = 'rxl_id';
		}
		if ($table == 'allergies') {
			$key = 'allergies_id';
		}
		if ($table == 'issues') {
			$key = 'issue_id';
		}
		$this->db->where($key, $id);
		$result = $this->db->get($table)->row_array();
		if ($result['rcopia_sync'] == 'nd') {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function delete_medication()
	{
		$rxl_id = $this->input->post('rxl_id');
		$practice = $this->practiceinfo_model->get()->row_array();
		if($practice['rcopia_extension'] == 'y') {
			$data = array(
				'rcopia_sync' => 'nd'
			);
			$update = $this->chart_model->updateMedication($rxl_id, $data);
			while(!$this->check_rcopia_delete('rx_list', $rxl_id)) {
				sleep(2);
			}
			$this->chart_model->deleteMedication($rxl_id);
			$this->audit_model->delete();
		} else {
			$this->chart_model->deleteMedication($rxl_id);
			$this->audit_model->delete();
		}
		echo "Medication deleted!";
		exit (0);
	}

	function reactivate_medication()
	{
		$rxl_id = $this->input->post('rxl_id');
		$this->db->where('rxl_id', $rxl_id);
		$query = $this->db->get('rx_list');
		$row = $query->row_array();
		if ($row['rxl_sig'] == '') {
			$instructions = $row['rxl_instructions'];
		} else {
			$instructions = $row['rxl_sig'] . ' ' . $row['rxl_route'] . ' ' . $row['rxl_frequency'];
		}
		$result['medtext'] =  $row['rxl_medication'] . ' ' . $row['rxl_dosage'] . ' ' . $row['rxl_dosage_unit'] . ', ' . $instructions . ' for ' . $row['rxl_reason'];
		$data = array(
			'rxl_date_inactive' => '0000-00-00 00:00:00',
			'rcopia_sync' => 'n'
		);
		$update = $this->chart_model->updateMedication($rxl_id, $data);
		$this->audit_model->update();
		$result['message'] = "Medication reactivated!";
		echo json_encode($result);
		exit (0);
	}
	
	function interactions_medication1()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = 'Close Chart';
			echo json_encode($result);
			exit (0);
		}
		$rxl_medication = $this->input->post('rxl_medication');
		$rx = explode(" ", $rxl_medication);
		$query_allergies = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive='0000-00-00 00:00:00' AND allergies_med='$rxl_medication'");
		if ($query_allergies->num_rows() > 0) {
			$result['message'] = 'Allergies';
			$result['info'] = '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
			$result['info'] .= 'ALERT: Medication prescribed is in the patient allergy list!';
			$result['info'] .= '</div>';
			echo json_encode($result);
			exit (0);
		}
		$query = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		if ($query->num_rows() > 0) {
			$comp_array = array();
			$query1 = "http://www.medscape.com/api/quickreflookup/LookupService.ashx?q=" . $rx[0] . "&all=false&sz=500&limit=500&type=10417&metadata=has-interactions&format=json";
			$cr1 = curl_init($query1);
			curl_setopt($cr1, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($cr1, CURLOPT_COOKIEJAR, 'cookie.txt');
			curl_setopt($cr1, CURLOPT_ENCODING, "");
			curl_setopt($cr1, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($cr1, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$data1 = curl_exec($cr1);
			curl_close($cr1);
			$data1_array = json_decode($data1, true);
			if (isset($data1_array['types'][0]['totalCount'])) {
				if ($data1_array['types'][0]['totalCount'] != 0) {
					$med1 = $data1_array['types'][0]["references"][0]["id"];
				} else {
					$result['message'] = 'Multiple';
					$result['info'] = '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
					$result['info'] .= 'Medication being prescribed is not found in drug database.  Use at your own risk!';
					$result['info'] .= '</div>';
					echo json_encode($result);
					exit (0);
				}	
			} else {
				$result['message'] = 'Multiple';
				$result['info'] = '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
				$result['info'] .= 'Medication being prescribed is not found in drug database.  Use at your own risk!';
				$result['info'] .= '</div>';
				echo json_encode($result);
				exit (0);
			}
			foreach ($query->result_array() as $row) {
				$rx1 = explode(" ", $row['rxl_medication']);
				$query2 = "http://www.medscape.com/api/quickreflookup/LookupService.ashx?q=" . $rx1[0] . "&all=false&sz=500&limit=500&type=10417&metadata=has-interactions&format=json";
				$cr2 = curl_init($query2);
				curl_setopt($cr2, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($cr2, CURLOPT_COOKIEJAR, 'cookie.txt');
				curl_setopt($cr2, CURLOPT_ENCODING, "");
				curl_setopt($cr2, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($cr2, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				$data2 = curl_exec($cr2);
				curl_close($cr2);
				$data2_array = json_decode($data2, true);
				if (isset($data2_array['types'][0]['totalCount'])) {
					if ($data2_array['types'][0]['totalCount'] != 0) {
						$med2 = $data2_array['types'][0]["references"][0]["id"];
					} else {
						$med2 = '0';
					}	
				} else {
					$med2 = '0';
				}
				$query3 = "http://www.medscape.com/druginteraction.do?action=getMultiInteraction&ids=" . $med1 . "," . $med2;
				$cr3 = curl_init($query3);
				curl_setopt($cr3, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($cr3, CURLOPT_COOKIEJAR, 'cookie.txt');
				curl_setopt($cr3, CURLOPT_ENCODING, "");
				curl_setopt($cr3, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($cr3, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				$data3 = curl_exec($cr3);
				curl_close($cr3);
				$data3a = ltrim($data3);
				$data3_array = json_decode($data3, true);
				if ($data3_array["errorCode"] == '1') {
					foreach ($data3_array["multiInteractions"] as $row3) {
						$comp_array[] = $row3;
					}
				}
			}
			if (count($comp_array) > 0) {
				$result['message'] = 'Multiple';
				$result['info'] = '';
				foreach ($comp_array as $key => $row4) {
					$severity[$key] = $row4['severityId'];
				}
				array_multisort($severity, SORT_DESC, $comp_array);
				$s1 = '';
				$s2 = '';
				$s3 = '';
				$s4 = '';
				foreach ($comp_array as $row5) {
					if ($row5['severity'] == 'Contraindicated') {
						$s1 .= '<h4>' . $row5['subject'] . " + " . $row5['object'] . "</h4>" . $row5['text'] . "<br>";
					}
					if ($row5['severity'] == 'Serious - Use Alternative') {
						$s2 .= '<h4>' . $row5['subject'] . " + " . $row5['object'] . "</h4>" . $row5['text'] . "<br>";
					}
					if ($row5['severity'] == 'Significant - Monitor Closely') {
						$s3 .= '<h4>' . $row5['subject'] . " + " . $row5['object'] . "</h4>" . $row5['text'] . "<br>";
					}
					if ($row5['severity'] == 'Minor') {
						$s4 .= '<h4>' . $row5['subject'] . " + " . $row5['object'] . "</h4>" . $row5['text'] . "<br>";
					}
				}
				if ($s1 != '') {
					$result['info'] .= '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br><h3>CONTRAINDICATED</h3>' . $s1;
					$result['info'] .= '</div><br>';	
				}
				if ($s2 != '') {
					$result['info'] .= '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br><h3>SERIOUS - USE ALTERNATIVE</h3>' . $s2;
					$result['info'] .= '</div><br>';	
				}
				if ($s3 != '') {
					$result['info'] .= '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br><h3>SIGNIFICANT - MONITOR CLOSELY</h3>' . $s3;
					$result['info'] .= '</div><br>';	
				}
				if ($s4 != '') {
					$result['info'] .= '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br><h3>MINOR</h3>' . $s4;
					$result['info'] .= '</div><br>';	
				}
			} else {
				$result['message'] = 'None';
			}
		} else {
			$result['message'] = 'None';
		}
		echo json_encode($result);
		exit (0);
	}
	
	function interactions_medication()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = 'Close Chart';
			echo json_encode($result);
			exit (0);
		}
		$rxl_medication = $this->input->post('rxl_medication');
		$query_allergies = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive='0000-00-00 00:00:00' AND allergies_med='$rxl_medication'");
		if ($query_allergies->num_rows() > 0) {
			$result['message'] = '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
			$result['message'] .= 'ALERT: Medication prescribed is in the patient allergy list!';
			$result['message'] .= '</div>';
			echo json_encode($result);
			exit (0);
		}
		$query = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		$result['message'] = '';
		if ($query->num_rows() > 0) {
			$cr = curl_init('https://online.epocrates.com/noFrame/multiCheckClearAllAction.do?method=clearAll');
			curl_setopt($cr, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($cr, CURLOPT_COOKIEJAR, 'cookie.txt');
			curl_setopt($cr, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$data1 = curl_exec($cr);
			curl_close($cr);
			foreach ($query->result_array() as $row) {
				$med = explode(" ", $row['rxl_medication']);
				$cr1a = curl_init('https://online.epocrates.com/drugIdHlightList?name=' . $med[0]);
				curl_setopt($cr1a, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($cr1a, CURLOPT_COOKIEFILE, 'cookie.txt'); 
				curl_setopt($cr1a, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				$html = curl_exec($cr1a);
				curl_close($cr1a);
				$find1 = strstr($html, ",");
				if ($find1 == FALSE) {
					$result['message'] = '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br>';
					$result['message'] .= $row['rxl_medication'] . ' not found in drug interactions database!  Use at your own risk!';
					$result['message'] .= '</div>';
					echo json_encode($result);
					exit (0);
				}
				$item = explode(",", $html);
				$strip = array("[\"10a", "\"]");
				$num = str_replace($strip, "", $item[1]);
				$num = $num - 1;
				$cr1 = curl_init('https://online.epocrates.com/noFrame/multiCheckSelectAction.do;?method=add&drugId=' . $num);
				curl_setopt($cr1, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($cr1, CURLOPT_COOKIEFILE, 'cookie.txt'); 
				$data2 = curl_exec($cr1);
				curl_close($cr1);
			}
			$med2 = explode(" ", $rxl_medication);
			$cr1b = curl_init('https://online.epocrates.com/drugIdHlightList?name=' . $med2[0]);
			curl_setopt($cr1b, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cr1b, CURLOPT_COOKIEFILE, 'cookie.txt'); 
			curl_setopt($cr1b, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$html1 = curl_exec($cr1b);
			curl_close($cr1b);
			$find2 = strstr($html1, ",");
			if ($find2 == FALSE) {
				$result['message'] = '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br>';
				$result['message'] .= $rxl_medication . ' not found in drug interactions database!  Use at your own risk!';
				$result['message'] .= '</div>';
				echo json_encode($result);
				exit (0);
			}
			$item1 = explode(",", $html1);
			$strip1 = array("[\"10a", "\"]");
			$num1 = str_replace($strip1, "", $item1[1]);
			$num1 = $num1 - 1;
			$cr2 = curl_init('https://online.epocrates.com/noFrame/multiCheckSelectAction.do;?method=add&drugId=' . $num1);
			curl_setopt($cr2, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($cr2, CURLOPT_COOKIEFILE, 'cookie.txt'); 
			curl_setopt($cr2, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$data3 = curl_exec($cr2);
			curl_close($cr2);
			$pos = strpos($data3, 'mcheckresultsInner');
			$pos = $pos + 21;
			$pos1 = strpos($data3, 'mcheckresultsInner', $pos);
			$pos1 = $pos1 - 15;
			$length = $pos1 - $pos;
			$text = substr($data3, $pos, $length);
			$strip2 = array(" class=\"start\"", " class=\"mc_Detail\"", "\n", "\t");
			$text = str_replace($strip2, "", $text);
			if ($text == '          <p>No significant interactions known or found for selected drugs. Caution always advised with multiple medications.</p> <!-- end of anyOutput --> <!-- end of !needMore -->') {
				$result['message'] = '';
			} else {
				$result['message'] .= '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br>';
				$result['message'] .= $text;
				$result['message'] .= '</div>';
			}
		}
		echo json_encode($result);
		exit (0);
	}
	
	function prescribe_medication()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = 'Close Chart';
			echo json_encode($result);
			exit (0);
		}
		$med = $this->input->post('rxl_medication');
		$user_id = $this->session->userdata('user_id');
		$this->db->where('id',$user_id);
		$query = $this->db->get('providers');
		$result1 = $query->row();
		$license = $result1->license . ' -' . $result1->license_state;	
		if($this->input->post('dea') == 'Yes') {
			$dea = $result1->dea;
		} else {
			$dea = '';
		}
		if($this->input->post('daw') == 'Yes') {
			$daw = 'Dispense As Written';
		} else {
			$daw = '';
		}	
		$date = strtotime($this->input->post('rxl_date_prescribed'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_active = mdate($datestring, $date);	
		$duedate1 = $this->input->post('rxl_days') * 86400;
		$duedate2 = $date + $duedate1;
		$duedate = mdate($datestring, $duedate2);	
		if($this->input->post('rxl_id') == '') {
			$data = array(
				'rxl_medication' => $this->input->post('rxl_medication'),
				'rxl_dosage' => $this->input->post('rxl_dosage'),
				'rxl_dosage_unit' => $this->input->post('rxl_dosage_unit'),
				'rxl_sig' => $this->input->post('rxl_sig'),
				'rxl_route' => $this->input->post('rxl_route'),
				'rxl_frequency' => $this->input->post('rxl_frequency'),
				'rxl_instructions' => $this->input->post('rxl_instructions'),
				'rxl_reason' => $this->input->post('rxl_reason'),
				'rxl_quantity' => $this->input->post('rxl_quantity'),
				'rxl_refill' => $this->input->post('rxl_refill'),
				'rxl_days' => $this->input->post('rxl_days'),
				'rxl_date_active' => $date_active,
				'rxl_date_inactive' => '',
				'rxl_date_prescribed' => $date_active,
				'rxl_date_old' => '',
				'rxl_provider' => $this->session->userdata('displayname'),
				'id' => $this->session->userdata('user_id'),
				'rxl_dea' => $dea,
				'rxl_daw' => $daw,
				'rxl_license' => $license,
				'rxl_due_date' => $duedate,
				'pid' => $pid,
				'rxl_ndcid' => $this->input->post('rxl_ndcid')
			);
			$add = $this->chart_model->addMedication($data);
			$this->audit_model->add();
			$practice_result = $this->practiceinfo_model->get()->row_array();
			if ($practice_result['mtm_extension'] == 'y') {
				$this->add_mtm_alert($pid, 'medications');
			}
			if ($add) {
				if ($this->input->post('rxl_sig') == '') {
					$instructions = $this->input->post('rxl_instructions');
				} else {
					$instructions = $this->input->post('rxl_sig') . ' ' . $this->input->post('rxl_route') . ' ' . $this->input->post('rxl_frequency');
				}
				$result = array(
					'message' => 'Medication prescribed!',
					'id' => $add,
					'med' => 'Choose an action for ' . $this->input->post('rxl_medication') . ' ' . $this->input->post('rxl_dosage') . ' ' . $this->input->post('rxl_dosage_unit') . '.',
					'medtext' => $this->input->post('rxl_medication') . ' ' . $this->input->post('rxl_dosage') . ' ' . $this->input->post('rxl_dosage_unit') . ', ' . $instructions . ' for ' . $this->input->post('rxl_reason') . ', Quantity: ' . $this->input->post('rxl_quantity') . ', Refills: ' . $this->input->post('rxl_refill')
				);
			} else {
				$result['message'] = 'Error prescribing medication!';
			}
		} else {
			$date1 = now();
			$date_old = mdate($datestring, $date1);
			$data1 = array(
				'rxl_date_old' => $date_old
			);
			$update = $this->chart_model->updateMedication($this->input->post('rxl_id'), $data1);
			$this->db->select('rxl_date_active');
			$this->db->where('rxl_id', $this->input->post('rxl_id'));
			$query2 = $this->db->get('rx_list');
			$result2 = $query2->row();
			$old_date_active = $result2->rxl_date_active;
			$data2 = array(
				'rxl_medication' => $this->input->post('rxl_medication'),
				'rxl_dosage' => $this->input->post('rxl_dosage'),
				'rxl_dosage_unit' => $this->input->post('rxl_dosage_unit'),
				'rxl_sig' => $this->input->post('rxl_sig'),
				'rxl_route' => $this->input->post('rxl_route'),
				'rxl_frequency' => $this->input->post('rxl_frequency'),
				'rxl_instructions' => $this->input->post('rxl_instructions'),
				'rxl_reason' => $this->input->post('rxl_reason'),
				'rxl_quantity' => $this->input->post('rxl_quantity'),
				'rxl_refill' => $this->input->post('rxl_refill'),
				'rxl_days' => $this->input->post('rxl_days'),
				'rxl_date_active' => $old_date_active,
				'rxl_date_inactive' => '',
				'rxl_date_prescribed' => $date_active,
				'rxl_date_old' => '',
				'rxl_provider' => $this->session->userdata('displayname'),
				'id' => $this->session->userdata('user_id'),
				'rxl_dea' => $dea,
				'rxl_daw' => $daw,
				'rxl_license' => $license,
				'rxl_due_date' => $duedate,
				'pid' => $pid,
				'rxl_ndcid' => $this->input->post('rxl_ndcid')
			);
			$add1 = $this->chart_model->addMedication($data2);
			$this->audit_model->add();
			if ($add1) {
				if ($this->input->post('rxl_sig') == '') {
					$instructions = $this->input->post('rxl_instructions');
				} else {
					$instructions = $this->input->post('rxl_sig') . ' ' . $this->input->post('rxl_route') . ' ' . $this->input->post('rxl_frequency');
				}
				$result = array(
					'message' => 'Medication prescribed!',
					'id' => $add1,
					'med' => 'Choose an action for ' . $this->input->post('rxl_medication') . ' ' . $this->input->post('rxl_dosage') . ' ' . $this->input->post('rxl_dosage_unit') . '.',
					'medtext' => $this->input->post('rxl_medication') . ' ' . $this->input->post('rxl_dosage') . ' ' . $this->input->post('rxl_dosage_unit') . ', ' . $instructions . ' for ' . $this->input->post('rxl_reason') . ', Quantity: ' . $this->input->post('rxl_quantity') . ', Refills: ' . $this->input->post('rxl_refill')
				);
			} else {
				$result['message'] = 'Error prescribing medication!';
			}
		}
		echo json_encode($result);
		exit (0);
	}
	
	function convert_number($number) 
	{ 
		if (($number < 0) || ($number > 999999999)) { 
			$res = "not a valid number";
			return $res;
		} 
		$Gn = floor($number / 1000000);  /* Millions (giga) */ 
		$number -= $Gn * 1000000; 
		$kn = floor($number / 1000);     /* Thousands (kilo) */ 
		$number -= $kn * 1000; 
		$Hn = floor($number / 100);      /* Hundreds (hecto) */ 
		$number -= $Hn * 100; 
		$Dn = floor($number / 10);       /* Tens (deca) */ 
		$n = $number % 10;               /* Ones */ 
		$res = ""; 
		if ($Gn) { 
			$res .= $this->convert_number($Gn) . " Million"; 
		} 
		if ($kn) { 
			$res .= (empty($res) ? "" : " ") . $this->convert_number($kn) . " Thousand"; 
		} 
		if ($Hn) { 
			$res .= (empty($res) ? "" : " ") . $this->convert_number($Hn) . " Hundred"; 
		} 
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
			"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
			"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", 
			"Nineteen"); 
		$tens = array("", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", 
			"Seventy", "Eighty", "Ninety"); 

		if ($Dn || $n) { 
			if (!empty($res)) { 
				$res .= " and "; 
			} 
			if ($Dn < 2) { 
				$res .= $ones[$Dn * 10 + $n]; 
			} else { 
				$res .= $tens[$Dn]; 
				if ($n) { 
				    $res .= "-" . $ones[$n]; 
				} 
			} 
		} 
		if (empty($res)) { 
			$res = "zero"; 
		} 
		return $res; 
	} 
	
	function page_medication($rxl_id)
	{
		$pid = $this->session->userdata('pid');
		$this->db->where('rxl_id', $rxl_id);
		$query = $this->db->get('rx_list');
		$data['rx'] = $query->row();
		$quantity = $data['rx']->rxl_quantity;
		$refill = $data['rx']->rxl_refill;
		$data['quantity_words'] = $this->convert_number($quantity);
		$data['refill_words'] = $this->convert_number($refill);
		$data['quantity_words'] = strtoupper($data['quantity_words']);
		$data['refill_words'] = strtoupper($data['refill_words']);
		$practice = $this->practiceinfo_model->get()->row_array();
		$data['practiceName'] = $practice['practice_name'];
		$data['website'] = $practice['website'];
		$data['practiceInfo'] = $practice['street_address1'];
		if ($practice['street_address2'] != '') {
			$data['practiceInfo'] .= ', ' . $practice['street_address2'];
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice['city'] . ', ' . $practice['state'] . ' ' . $practice['zip'] . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice['phone'] . ', Fax: ' . $practice['fax'] . '<br />';
		$data['patientInfo'] = $this->demographics_model->get($pid)->row();
		if ($practice['practice_logo'] != '') {
			$logo = str_replace("/var/www/nosh/","", $practice['practice_logo']);
			$data['practiceLogo'] = "<img src='" . base_url() . $logo . "' border='0'>";
		} else {
			$data['practiceLogo'] = '<br><br><br><br><br>';
		}
		$dob1 = human_to_unix($data['patientInfo']->DOB);
		$data['dob'] = date('m/d/Y', $dob1);
		$rx_date = human_to_unix($data['rx']->rxl_date_prescribed);
		$data['rx_date'] = date('m/d/Y', $rx_date);
		$query1 = $this->chart_model->getActiveInsurance($pid);
		$data['insuranceInfo'] = '';
		if ($query1->num_rows() > 0) {
			foreach ($query1->result_array() as $row) {
				$data['insuranceInfo'] .= $row['insurance_plan_name'] . '; ID: ' . $row['insurance_id_num'] . '; Group: ' . $row['insurance_group'] . '; ' . $row['insurance_insu_lastname'] . ', ' . $row['insurance_insu_firstname'] . '<br><br>';
			}
		}
		$query2 = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive='0000-00-00 00:00:00'");
		$data['allergyInfo'] = '';
		if ($query2->num_rows() > 0) {
			$data['allergyInfo'] .= '<ul>';
			foreach ($query2->result_array() as $row1) {
				$data['allergyInfo'] .= '<li>' . $row1['allergies_med'] . '</li>';
			}
			$data['allergyInfo'] .= '</ul>';
		} else {
			$data['allergyInfo'] .= 'No known allergies.';
		}
		$user_id = $this->session->userdata('user_id');
		$this->db->select('signature');
		$this->db->where('id', $user_id);
		$signature_query = $this->db->get('providers');
		if ($signature_query->num_rows() > 0) {
			$signature = $this->db->get('providers')->row_array();
			$signature1 = str_replace("/var/www/nosh/","", $signature['signature']);
			$data['signature'] = "<img src='" . base_url() . $signature1 . "' border='0'><br>";
		} else {
			$data['signature'] = '&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>';
		}
		return $this->load->view('auth/pages/provider/chart/prescription_page', $data, TRUE);
	}
	
	function eprescribe_medication($rxl_id)
	{
		$pid = $this->session->userdata('pid');
		$data['e_pid'] = $pid;
		$this->db->where('rxl_id', $rxl_id);
		$query = $this->db->get('rx_list');
		$result = $query->row();
		$data['e_medication'] = $result->rxl_medication;
		$data['e_dosage'] = $result->rxl_dosage . ' ' . $result->rxl_dosage_unit;
		if ($result->rxl_instructions != '') {
			$data['e_sig'] = $result->rxl_instructions . ' for ' . $result->rxl_reason;
		} else {
			$data['e_sig'] = $result->rxl_sig . ' ' . $result->rxl_route . ' ' . $result->rxl_frequency . ' for ' . $result->rxl_reason;
		}
		$data['e_quantity'] = $result->rxl_quantity;
		$data['e_refills'] = $result->rxl_refill;
		$data['e_days'] = $result->rxl_days;
		if ($result->rxl_daw == 'Dispense As Written') {
			$data['e_daw'] = 'Yes';
		} else {
			$data['e_daw'] = 'No';
		}
		$result2 = $this->demographics_model->get($pid)->row();
		$data['e_lastname'] = $result2->lastname;
		$data['e_firstname'] = $result2->firstname;
		$data['e_address'] = $result2->address;
		$data['e_city'] = $result2->city;
		$data['e_state'] = $result2->state;
		$data['e_zip'] = $result2->zip;
		$data['e_phone'] = $result2->phone_home;
		$data['e_mobile'] = $result2->phone_cell;
		$dob1 = human_to_unix($result2->DOB);
		$data['e_dob'] = date('m/d/Y', $dob1);
		if ($result2->sex == 'm') {
			$data['e_sex'] = 'M';
		} else {
			$data['e_sex'] = 'F';
		}
		echo json_encode($data);
	}
	
	function print_medication($rxl_id)
	{
		ini_set('memory_limit','196M');
		$html = $this->page_medication($rxl_id);
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/rx_" . now() . "_" . $user_id . ".pdf";
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Prescription Generated by NOSH ChartingSystem');
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
	
	function start_fax_medication()
	{
		$pid = $this->session->userdata('pid');
		if ($this->session->userdata('job_id') == FALSE) {
			$job_id = '';
		} else {
			$job_id = $this->session->userdata('job_id');
		}
		$rxl_id = $this->input->post('fax_prescribe_id');
		$html = $this->page_medication($rxl_id);
		$filename = '/var/www/nosh/' . now() . '_' . $rxl_id . '.pdf';
		$user_id = $this->session->userdata('user_id');
		ini_set('memory_limit','196M');
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Prescription Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->showImageErrors = true;
		$this->mpdf->simpleTables = true;
		$this->mpdf->Output($filename,'F');
		while(!file_exists($filename)) {
			sleep(2);
		}
		$this->db->where('rxl_id', $rxl_id);
		$row1 = $this->db->get('rx_list')->row_array();
		$file_original = $row1['rxl_medication'] . ' ' . $row1['rxl_dosage'] . ' ' . $row1['rxl_dosage_unit'] . ', ' . $row1['rxl_sig'] . ' ' . $row1['rxl_route'] . ' ' . $row1['rxl_frequency'] . ' for ' . $row1['rxl_reason'];
		$result['id'] = $this->fax_document($pid, 'Prescription/Refill Authorization', 'yes', $filename, $file_original , '', '', $job_id, 'no');
		$result['message'] = 'Prescription added to fax queue!';
		$this->session->set_userdata('job_id', $result['id']);
		unlink($filename);
		echo json_encode($result);
		exit (0);
	}
	
	function send_fax_medication()
	{
		$job_id = $this->session->userdata('job_id');
		$result_message = $this->send_fax($job_id, $this->input->post('messages_pharmacy_fax_number'), $this->input->post('messages_pharmacy_name'));
		$this->session->unset_userdata('job_id');
		echo $result_message;
	}
	
	function cancel_fax_medication()
	{
		$job_id = $this->session->userdata('job_id');
		if ($job_id != '') {
			$directory = '/var/www/nosh/sentfax/' . $job_id;
			$command = "rm -R " . $directory;
			$command1 = escapeshellcmd($command);
			exec($command1);
			$this->fax_model->deleteFax($job_id);
			$this->session->unset_userdata('job_id');
			echo "Fax queue deleted!";
		} else {
			echo "Error deleting fax queue!";
		}
	}
	
	function add_pharmacy()
	{
		$data = array(
			'displayname' => $this->input->post('messages_pharmacy_name'),
			'fax' => $this->input->post('messages_pharmacy_fax_number'),
			'specialty' => 'Pharmacy'
		);
		$add = $this->contact_model->addContact($data);
		$this->audit_model->add();
		if ($add) {
			echo "Pharmacy added!";
		} else {
			echo "Error adding pharmacy!";
		}
	}
	
	function rx_fax_list()
	{
		$job_id = $this->session->userdata('job_id');
		if ($job_id == FALSE) {
			$job_id = '0';
		}
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->fax_model->getPages($job_id);
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;		
		$query1 = $this->fax_model->getPages1($job_id, $sidx, $sord, $start, $limit);
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;	
		echo json_encode($response);
		exit( 0 );
	}
	
	function view_faxpage($pages_id)
	{
		$query = $this->fax_model->getPages2($pages_id);
		$result = $query->row_array();
		$file_path = $result['file'];
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
	
	function page_medication_list()
	{
		$pid = $this->session->userdata('pid');	
		$practice = $this->practiceinfo_model->get()->row();
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax . '<br />';
		$patient = $this->demographics_model->get($pid)->row_array();
		$body = 'Active Medications for ' . $patient['firstname'] . ' ' . $patient['lastname'] . ':<br />';
		$query = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		if ($query->num_rows() > 0) {
			$body .= '<ul>';
			foreach ($query->result_array() as $row) {
				$body .= '<li>' . $row['rxl_medication'] . ' ' . $row['rxl_dosage'] . ' ' . $row['rxl_dosage_unit'] . ', ' . $row['rxl_sig'] . ' ' . $row['rxl_route'] . ' ' . $row['rxl_frequency'] . ' for ' . $row['rxl_reason'] . '</li>';
			}
			$body .= '</ul>';
		} else {
			$body .= 'None.';
		}
		$data['letter'] = $body;
		return $this->load->view('auth/pages/provider/chart/letter_page',$data, TRUE);
	}
	
	function print_medication_list()
	{
		ini_set('memory_limit','196M');
		$html = $this->page_medication_list();
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/rx_list_" . now() . "_" . $user_id . ".pdf";
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Document Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
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
			$rx_rx = 'PRESCRIBED MEDICATIONS:  ';
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
			$rx_rx .= "\n";
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
	
	// --------------------------------------------------------------------

	function supplements_list()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->db->query("SELECT * FROM sup_list WHERE pid=$pid AND sup_date_inactive='0000-00-00 00:00:00'");
		$result = '<strong>Active Supplements:</strong>';
		if ($query->num_rows() > 0) {
			$result .= '<ul>';
			foreach ($query->result_array() as $row) {
				$result .= '<li>' . $row['sup_supplement'] . ' ' . $row['sup_dosage'] . ' ' . $row['sup_dosage_unit'] . ', ' . $row['sup_sig'] . ' ' . $row['sup_route'] . ' ' . $row['sup_frequency'] . ' for ' . $row['sup_reason'] . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= ' None.';
		}
		echo $result;
		exit( 0 );
	}
	
	function supplements()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM sup_list WHERE pid=$pid AND sup_date_inactive='0000-00-00 00:00:00'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM sup_list WHERE pid=$pid AND sup_date_inactive='0000-00-00 00:00:00' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;	
		echo json_encode($response);
		exit( 0 );
	}
	
	function supplements_inactive()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM sup_list WHERE pid=$pid AND sup_date_inactive!='0000-00-00 00:00:00'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM sup_list WHERE pid=$pid AND sup_date_inactive!='0000-00-00 00:00:00' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;	
		echo json_encode($response);
		exit( 0 );
	}
	
	function check_supplement_inventory()
	{
		$this->db->select('quantity');
		$this->db->where('supplement_id', $this->input->post('supplement_id'));
		$inventory_query = $this->db->get('supplement_inventory');
		$result = "Supplement does not exist in the inventory!";
		if ($inventory_query->num_rows() > 0) {
			$inventory_result = $inventory_query->row_array();
			if ($inventory_result['quantity'] > 0) {
				$result = "OK";
			}
		}
		echo $result;
	}
	
	function edit_supplement($origin_orders)
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = 'Close Chart';
			echo json_encode($result);
			exit (0);
		}
		$date = strtotime($this->input->post('sup_date_active'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_active = mdate($datestring, $date);
		$data = array(
			'sup_supplement' => $this->input->post('sup_supplement'),
			'sup_dosage' => $this->input->post('sup_dosage'),
			'sup_dosage_unit' => $this->input->post('sup_dosage_unit'),
			'sup_sig' => $this->input->post('sup_sig'),
			'sup_route' => $this->input->post('sup_route'),
			'sup_frequency' => $this->input->post('sup_frequency'),
			'sup_instructions' => $this->input->post('sup_instructions'),
			'sup_reason' => $this->input->post('sup_reason'),
			'sup_date_active' => $date_active,
			'sup_date_inactive' => '',
			'sup_provider' => $this->session->userdata('displayname'),
			'pid' => $pid
		);
		if($this->input->post('amount') != '') {
			if($this->input->post('supplement_id') != '') {
				$data['supplement_id'] = $this->input->post('supplement_id');
				$this->db->select('quantity, cpt, charge');
				$this->db->where('supplement_id', $this->input->post('supplement_id'));
				$inventory_query = $this->db->get('supplement_inventory');
				$inventory_result = $inventory_query->row_array();
				$quantity = $inventory_result['quantity'];
				$amount = $this->input->post('amount');
				$quantity = $quantity - $amount;
				$inventory_data = array(
					'quantity' => $quantity
				);
				$this->db->where('supplement_id', $this->input->post('supplement_id'));
				$this->db->update('supplement_inventory', $inventory_data);
				if ($origin_orders == "Y") {
					$eid = $this->session->userdata('eid');
					$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
					$dos1 = human_to_unix($encounterInfo->encounter_DOS);
					$dos = date('mdY', $dos1);
					$dos2 = date('m/d/Y', $dos1);
					$pos = $encounterInfo->encounter_location;
					$icd_pointer = '';
					if ($this->encounters_model->getAssessment($eid)->num_rows() > 0) {
						$assessment_data = $this->encounters_model->getAssessment($eid)->row_array();
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
					}
					$cpt = array(
						'cpt' => $inventory_result['cpt'],
						'cpt_charge' => $inventory_result['charge'],
						'eid' => $eid,
						'pid' => $pid,
						'dos_f' => $dos2,
						'dos_t' => $dos2,
						'payment' => '0',
						'icd_pointer' => $icd_pointer,
						'unit' => $amount,
						'billing_group' => '1',
						'modifier' => ''
					);
					$this->db->insert('billing_core', $cpt);
					$this->audit_model->add();
					$sales_tax_check = $this->practiceinfo_model->get()->row_array();
					if ($sales_tax_check['sales_tax'] != '') {
						$this->db->where('eid', $eid);
						$this->db->like('cpt','sp','after');
						$sales_tax_add_query1 = $this->db->get('billing_core');
						if ($sales_tax_add_query1->num_rows() > 0) {
							$sales_tax_total1 = $inventory_result['charge'] * $amount;
							foreach ($sales_tax_add_query1->result_array() as $sales_tax_add_row1) {
								$sales_tax_total1 += $sales_tax_add_row1['cpt_charge'] * $sales_tax_add_row1['unit'];
							}
						} else {
							$sales_tax_total1 = $inventory_result['charge'] * $amount;
						}
						$sales_tax1 = array(
							'cpt' => 'sptax',
							'cpt_charge' => number_format($sales_tax_total1 * $sales_tax_check['sales_tax'] / 100, 2),
							'eid' => $eid,
							'pid' => $pid,
							'dos_f' => $dos2,
							'dos_t' => $dos2,
							'payment' => '0',
							'icd_pointer' => $icd_pointer,
							'unit' => '1',
							'billing_group' => '1',
							'modifier' => ''
						);
						$this->db->where('cpt','sptax');
						$this->db->where('eid', $eid);
						$sales_tax_query = $this->db->get('billing_core');
						if ($sales_tax_query->num_rows() > 0) {
							$sales_tax_row1 = $sales_tax_query->row_array();
							$this->db->where('billing_core_id', $sales_tax_row1['billing_core_id']);
							$this->db->update('billing_core', $sales_tax1);
							$this->audit_model->update();
						} else {
							$this->db->insert('billing_core', $sales_tax1);
							$this->audit_model->add();
						}
					}
				} else {
					$sales_tax_check = $this->practiceinfo_model->get()->row_array();
					if ($sales_tax_check['sales_tax'] != '') {
						$sales_tax_total2 = $inventory_result['charge'] * $amount;
						$tax = number_format($sales_tax_total2 * $sales_tax_check['sales_tax'] / 100, 2);
						$cpt_charge = $sales_tax_total2 + $tax;
						$reason = $this->input->post('sup_supplement') . ", Quantity: " . $amount . ", Tax: $" . $tax;
						$unit = '1';
					} else {
						$cpt_charge = $inventory_result['charge'];
						$reason = $this->input->post('sup_supplement');
						$unit = $amount;
					}
					$other_data = array(
						'eid' => '0',
						'pid' => $pid,
						'dos_f' => date('m/d/Y'),
						'cpt_charge' => $cpt_charge,
						'reason' => $reason,
						'payment' => '0',
						'unit' => $unit
					);
					$id1 = $this->chart_model->addBillingCore($other_data);
					$this->audit_model->add();
					$data1 = array(
						'other_billing_id' => $id1
					);
					$this->chart_model->updateBillingCore($id1, $data1);
					$this->audit_model->update();
				}
			}
		}
		if($this->input->post('sup_id') == '') {
			$add = $this->chart_model->addSupplement($data);
			$this->audit_model->add();
			if ($add) {
				$result = array(
					'message' => 'Supplement added!',
					'medtext' => $this->input->post('sup_supplement') . ' ' . $this->input->post('sup_dosage')
				);
				if ($this->input->post('sup_dosage_unit') != "") {
					$result['medtext'] .= ' ' . $this->input->post('sup_dosage_unit');
				}
				if ($this->input->post('sup_sig') != "") {
					if ($this->input->post('sup_instructions') != "") {
						$result['medtext'] .= ', ' . $this->input->post('sup_sig') . ' ' . $this->input->post('sup_route') . ' ' . $this->input->post('sup_frequency') . ', ' . $this->input->post('sup_instructions') . ' for ' . $this->input->post('sup_reason');
					} else {
						$result['medtext'] .= ', ' . $this->input->post('sup_sig') . ' ' . $this->input->post('sup_route') . ' ' . $this->input->post('sup_frequency') . ' for ' . $this->input->post('sup_reason');
					}
				} else {
					$result['medtext'] .= ', ' . $this->input->post('sup_instructions') . ' for ' . $this->input->post('sup_reason');
				}
			} else {
				$result['message'] = "Error adding supplement!";
			}
		} else {
			$update = $this->chart_model->updateSupplement($this->input->post('sup_id'), $data);
			$this->audit_model->update();
			$result = array(
				'message' => 'Supplement updated!',
				'medtext' => $this->input->post('sup_supplement') . ' ' . $this->input->post('sup_dosage')
			);
			if ($this->input->post('sup_dosage_unit') != "") {
				$result['medtext'] .= ' ' . $this->input->post('sup_dosage_unit');
			}
			if ($this->input->post('sup_sig') != "") {
				if ($this->input->post('sup_instructions') != "") {
					$result['medtext'] .= ', ' . $this->input->post('sup_sig') . ' ' . $this->input->post('sup_route') . ' ' . $this->input->post('sup_frequency') . ', ' . $this->input->post('sup_instructions') . ' for ' . $this->input->post('sup_reason');
				} else {
					$result['medtext'] .= ', ' . $this->input->post('sup_sig') . ' ' . $this->input->post('sup_route') . ' ' . $this->input->post('sup_frequency') . ' for ' . $this->input->post('sup_reason');
				}
			} else {
				$result['medtext'] .= ', ' . $this->input->post('sup_instructions') . ' for ' . $this->input->post('sup_reason');
			}
		}
		if($this->input->post('amount') != "") {
			$result['medtext'] .= ", Quantity: " . $this->input->post('amount');
		}
		echo json_encode($result);
		exit (0);
	}
	
	function inactivate_supplement()
	{
		$sup_id = $this->input->post('sup_id');
		$this->db->where('sup_id', $sup_id);
		$query = $this->db->get('sup_list');
		$row = $query->row_array();
		$result['medtext'] =  $row['sup_supplement'] . ' ' . $row['sup_dosage'] . ' ' . $row['sup_dosage_unit'] . ', ' . $row['sup_sig'] . ' ' . $row['sup_route'] . ' ' . $row['sup_frequency'] . ' for ' . $row['sup_reason'];
		$date = now();
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_inactive = mdate($datestring, $date);
		$data = array(
			'sup_date_inactive' => $date_inactive
		);
		$update = $this->chart_model->updateSupplement($sup_id, $data);
		$this->audit_model->update();
		$result['message'] = "Supplement inactivated!";
		echo json_encode($result);
		exit (0);
	}
	
	function delete_supplement()
	{
		$this->chart_model->deleteSupplement($this->input->post('sup_id'));
		$this->audit_model->delete();
		echo "Supplement deleted!";
	}

	function reactivate_supplement()
	{
		$sup_id = $this->input->post('sup_id');
		$this->db->where('sup_id', $sup_id);
		$query = $this->db->get('sup_list');
		$row = $query->row_array();
		$result['medtext'] =  $row['sup_supplement'] . ' ' . $row['sup_dosage'] . ' ' . $row['sup_dosage_unit'] . ', ' . $row['sup_sig'] . ' ' . $row['sup_route'] . ' ' . $row['sup_frequency'] . ' for ' . $row['sup_reason'];
		$data = array(
			'sup_date_inactive' => '0000-00-00 00:00:00'
		);
		$update = $this->chart_model->updateSupplement($sup_id, $data);
		$this->audit_model->update();
		$result['message'] = "Supplement reactivated!";
		echo json_encode($result);
		exit (0);
	}
	
	// --------------------------------------------------------------------
	
	function allergies_list()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive='0000-00-00 00:00:00'");
		$result = '<strong>Active Allergies:</strong>';
		if ($query->num_rows() > 0) {
			$result .= '<ul>';
			foreach ($query->result_array() as $row) {
				$result .= '<li>' . $row['allergies_med'] . ' - ' . $row['allergies_reaction'] . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= ' No known allergies.';
		}
		echo $result;
		exit( 0 );
	}
	
	function allergies()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive='0000-00-00 00:00:00'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive='0000-00-00 00:00:00' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}
	
	function allergies_inactive()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive!='0000-00-00 00:00:00'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive!='0000-00-00 00:00:00' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function ndc_convert($ndc)
	{
		$pos1 = strpos($ndc, '-');
		$parts = explode("-", $ndc);
		if ($pos1 === 4) {
			$parts[0] = '0' . $parts[0];
		} else {
			$pos2 = strrpos($ndc, '-');
			if ($pos2 === 10) {
				$parts[2] = '0' . $parts[2];
			} else {
				$parts[1] = '0' . $parts[1];
			}
		}
		$new = $parts[0] . $parts[1] . $parts[2];
		return $new;
	}
	
	function edit_allergy()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = 'Close Chart';
			echo json_encode($result);
			exit (0);
		}	
		$date = strtotime($this->input->post('allergies_date_active'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_active = mdate($datestring, $date);
		$med_name = explode(", ", $this->input->post('allergies_med'), -1);
		$ndcid = "";
		if ($med_name[0]) {
			$this->db->select('meds_full_package.NDCPACKAGECODE');
			$this->db->from('meds_full_package');
			$this->db->join('meds_full', 'meds_full.PRODUCTNDC=meds_full_package.PRODUCTNDC');
			$this->db->where('meds_full.PROPRIETARYNAME', $med_name[0]);
			$this->db->limit(1);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$med_result = $query->row_array();
				$ndcid = $this->ndc_convert($med_result['NDCPACKAGECODE']);
			}
		}
		$data = array(
			'allergies_med' => $this->input->post('allergies_med'),
			'allergies_reaction' => $this->input->post('allergies_reaction'),
			'allergies_date_active' => $date_active,
			'allergies_date_inactive' => '',
			'allergies_provider' => $this->session->userdata('displayname'),
			'pid' => $pid,
			'rcopia_sync' => 'n',
			'meds_ndcid' => $ndcid
		);	
		if($this->input->post('allergies_id') == '') {
			$add = $this->chart_model->addAllergy($data);
			$this->audit_model->add();
			if ($add) {
				$result['message'] = "Allergy added!";
			} else {
				$result['message'] = "Error adding allergy!";
			}
		} else {
			$update = $this->chart_model->updateAllergy($this->input->post('allergies_id'), $data);
			$this->audit_model->update();
			$result['message'] = "Allergy updated!";
		}
		echo json_encode($result);
			exit (0);
	}
	
	function inactivate_allergy()
	{
		$date = now();
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_inactive = mdate($datestring, $date);
		$data = array(
			'allergies_date_inactive' => $date_inactive,
			'rcopia_sync' => 'nd1'
		);
		$update = $this->chart_model->updateAllergy($this->input->post('allergies_id'), $data);
		$this->audit_model->update();
		echo "Allergy inactivated!";
	}
	
	function delete_allergy()
	{
		$allergies_id = $this->input->post('allergies_id');
		$practice = $this->practiceinfo_model->get()->row_array();
		if($practice['rcopia_extension'] == 'y') {
			$data = array(
				'rcopia_sync' => 'nd'
			);
			$update = $this->chart_model->updateAllergy($allergies_id, $data);
			while(!$this->check_rcopia_delete('allergies', $allergies_id)) {
				sleep(2);
			}
			$this->chart_model->deleteAllergy($allergies_id);
			$this->audit_model->delete();
		} else {
			$this->chart_model->deleteAllergy($allergies_id);
			$this->audit_model->delete();
		}
		echo "Allergy deleted!";
	}

	function reactivate_allergy()
	{
		$data = array(
			'allergies_date_inactive' => '0000-00-00 00:00:00',
			'rcopia_sync' => 'n'
		);
		$update = $this->chart_model->updateAllergy($this->input->post('allergies_id'), $data);
		$this->audit_model->update();
		echo "Allergy reactivated!";
	}
	
	function rcopia_update_allergy_xml($pid, $result1)
	{
		$this->db->select('description');
		$this->db->where('pid', $pid);
		$this->db->where('action', 'update allergy');
		$this->db->where('extensions_name', 'rcopia');
		$this->db->orderby("timestamp", "desc"); 
		$this->db->limit(1);
		$result = $this->db->get('extensions_log')->row_array();
		$xml = new SimpleXMLElement($result1);
		$last_update_date = $xml->Response->LastUpdateDate . "";
		foreach ($xml->Response->AllergyList->Allergy as $allergy) {
			$allergies_id = $allergy->ExternalID . "";
			$allergies_med = $allergy->Allergen->Name . "";
			$allergies_reaction = $allergy->Reaction . "";
			$date_active_pre = explode(" ", $allergy->OnsetDate);
			$date_active_pre1 = explode("/", $date_active_pre[0]);
			$allergies_date_active = $date_active_pre1[2] . "-" . $date_active_pre1[0] . "-" . $date_active_pre1[1] . " 00:00:00";
			$date_inactive_pre = explode(" ", $allergy->LastModifiedDate);
			$date_inactive_pre1 = explode("/", $date_inactive_pre[0]);
			$allergies_date_inactive = $date_inactive_pre1[2] . "-" . $date_inactive_pre1[0] . "-" . $date_inactive_pre1[1] . " 00:00:00";
			$meds_ndcid = $allergy->Allergen->Drug->NDCID . "";
			$delete = $allergy->Deleted . "";
			if ($delete == 'n') {
				$data1 = array(
					'pid' => $pid,
					'allergies_date_active' => $allergies_date_active,
					'allergies_med' => $allergies_med,
					'allergies_reaction' => $allergies_reaction,
					'rcopia_sync' => 'y',
					'meds_ndcid' => $meds_ndcid
				);
				$this->db->insert('allergies', $data1);
			} else {
				$data2['allergies_date_inactive'] = $allergies_date_inactive;
				$this->db->where('meds_ndcid', $meds_ndcid);
				$this->db->where('pid', $pid);
				$this->db->update('allergies', $data2);
			}
		}
		$data = array(
			'rcopia_update_allergy_date' => $last_update_date,
			'rcopia_update_allergy' => 'n'
		);
		$this->db->where('pid', $pid);
		$this->db->update('demographics', $data);
		return "Allergy list updated with DiFirst RCopia";
	}
	
	function rcopia_update_allergy()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo 'Close Chart';
			exit (0);
		}
		$encounter_provider = $this->session->userdata('displayname');
		$this->db->select('rcopia_update_allergy_date');
		$this->db->where('pid', $pid);
		$old = $this->db->get('demographics')->row_array();
		$rcopia_data = array(
			'rcopia_update_allergy' => 'y'
		);
		$this->db->where('pid', $pid);
		$this->db->update('demographics', $rcopia_data);
		$xml1 = "<Request><Command>update_allergy</Command>";
		$xml1 .= "<LastUpdateDate>" . $old['rcopia_update_allergy_date'] . "</LastUpdateDate>";
		$xml1 .= "<Patient><ExternalID>" . $pid . "</ExternalID></Patient>";
		$xml1 .= "</Request></RCExtRequest>";
		$result1 = $this->rcopia($xml1);
		$response1 = new SimpleXMLElement($result1);
		$status1 = $response1->Response->Status . "";
		if ($status1 == "error") {
			$description1 = $response1->Response->Error->Text . "";
			$data1a = array(
				'action' => 'update_allergy',
				'pid' => $pid,
				'extensions_name' => 'rcopia',
				'description' => $description1
			);
			$this->db->insert('extensions_log', $data1a);
			$response = "Error connecting to DrFirst RCopia.  Try again later.";
		} else {
			$response = $this->rcopia_update_allergy_xml($pid, $result1);
		}
		echo $response;
		exit (0);
	}

	// --------------------------------------------------------------------

	function immunizations_list()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->db->query("SELECT * FROM immunizations WHERE pid=$pid ORDER BY imm_immunization ASC, imm_sequence ASC");
		$result = '<strong>Immunizations:</strong>';
		if ($query->num_rows() > 0) {
			$result .= '<ul>';
			foreach ($query->result_array() as $row) {
				$sequence = '';
				if ($row['imm_sequence'] == '1') {
					$sequence = ', first';
				}
				if ($row['imm_sequence'] == '2') {
					$sequence = ', second';
				}
				if ($row['imm_sequence'] == '3') {
					$sequence = ', third';
				}
				if ($row['imm_sequence'] == '4') {
					$sequence = ', fourth';
				}
				if ($row['imm_sequence'] == '5') {
					$sequence = ', fifth';
				}
				$result .= '<li>' . $row['imm_immunization'] . $sequence . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= ' None.';
		}
		echo $result;
		exit( 0 );
	}
	
	function immunizations()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM immunizations WHERE pid=$pid");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM immunizations WHERE pid=$pid ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;	
		echo json_encode($response);
		exit( 0 );
	}
	
	function edit_immunization()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = 'Close Chart';
			echo json_encode($result);
			exit (0);
		}
		$date = strtotime($this->input->post('imm_date'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_active = mdate($datestring, $date);
		if ($this->input->post('imm_expiration')=='') {
			$date_expiration = '';
		} else {
			$date1 = strtotime($this->input->post('imm_expiration'));
			$date_expiration = mdate($datestring, $date1);	
		}	
		if ($this->input->post('imm_elsewhere')=='Yes') {
			$imm_elsewhere = 'Yes';
		} else {
			$imm_elsewhere = 'No';
		}	
		$data = array(
			'imm_immunization' => $this->input->post('imm_immunization'),
			'imm_sequence' => $this->input->post('imm_sequence'),
			'imm_body_site' => $this->input->post('imm_body_site'),
			'imm_route' => $this->input->post('imm_route'),
			'imm_dosage' => $this->input->post('imm_dosage'),
			'imm_dosage_unit' => $this->input->post('imm_dosage_unit'),
			'imm_lot' => $this->input->post('imm_lot'),
			'imm_expiration' => $date_expiration,
			'imm_date' => $date_active,
			'imm_elsewhere' => $imm_elsewhere,
			'imm_vis' => '',
			'imm_manufacturer' => $this->input->post('imm_manufacturer'),
			'imm_provider' => $this->session->userdata('displayname'),
			'imm_cvxcode' => $this->input->post('imm_cvxcode'),
			'pid' => $pid,
			'eid' => ''
		);	
		if($this->input->post('imm_id') == '') {
			$add = $this->chart_model->addImmunization($data);
			$this->audit_model->add();
			if ($add) {
				$result['message'] = "Immunization added!";
			} else {
				$result['message'] = "Error adding immunization!";
			}
		} else {
			$update = $this->chart_model->updateImmunization($this->input->post('imm_id'), $data);
			$this->audit_model->update();
			$result['message'] = "Immunization updated!";
		}
		echo json_encode($result);
		exit (0);
	}
	
	function edit_immunization1()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = 'Close Chart';
			echo json_encode($result);
			exit (0);
		}
		$date = strtotime($this->input->post('imm_date'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_active = mdate($datestring, $date);	
		$date1 = strtotime($this->input->post('imm_expiration'));
		$date_expiration = mdate($datestring, $date1);	
		$medtext = '';
		if ($this->input->post('imm_vis')=='Yes') {
			$imm_vis = 'Yes';
			$medtext .= 'Consent obtained and CDC Vaccine Information Sheet (VIS) given to patient/caregiver.';
		} else {
			$imm_vis = '';
		}
		$eid = $this->session->userdata('eid');
		$data = array(
			'imm_immunization' => $this->input->post('imm_immunization'),
			'imm_sequence' => $this->input->post('imm_sequence'),
			'imm_body_site' => $this->input->post('imm_body_site'),
			'imm_route' => $this->input->post('imm_route'),
			'imm_dosage' => $this->input->post('imm_dosage'),
			'imm_dosage_unit' => $this->input->post('imm_dosage_unit'),
			'imm_lot' => $this->input->post('imm_lot'),
			'imm_expiration' => $date_expiration,
			'imm_date' => $date_active,
			'imm_elsewhere' => 'No',
			'imm_vis' => $imm_vis,
			'imm_manufacturer' => $this->input->post('imm_manufacturer'),
			'imm_provider' => $this->session->userdata('displayname'),
			'imm_cvxcode' => $this->input->post('imm_cvxcode'),
			'pid' => $pid,
			'eid' => $eid,
			'cpt' => $this->input->post('cpt')
		);	
		$medtext .= '<br>' . $this->input->post('imm_immunization') . '; Sequence: ' . $this->input->post('imm_sequence') . '; Dosage: ' . $this->input->post('imm_dosage') . ' ' . $this->input->post('imm_dosage_unit') . ' ' . $this->input->post('imm_route') . ' administered to the ' . $this->input->post('imm_body_site');
		$medtext .= '<br>Manufacturer: ' . $this->input->post('imm_manufacturer') . '; Lot number: ' . $this->input->post('imm_lot') . '; Expiration date: ' . $this->input->post('imm_expiration');
		if($this->input->post('imm_id') == '') {
			$add = $this->chart_model->addImmunization($data);
			$this->audit_model->add();
			if ($add) {
				$vaccine_id = $this->input->post('vaccine_id');
				$this->db->select('quantity');
				$this->db->where('vaccine_id', $vaccine_id);
				$inventory_query = $this->db->get('vaccine_inventory');
				$inventory_result = $inventory_query->row_array();
				$quantity = $inventory_result['quantity'];
				$quantity = $quantity - 1;
				$inventory_data = array(
					'quantity' => $quantity
				);
				$this->db->where('vaccine_id', $vaccine_id);
				$this->db->update('vaccine_inventory', $inventory_data);
				$result =  array(
					'message' => "Immunization added!",
					'medtext' => $medtext
				);
			} else {
				$result['message'] = "Error adding immunization!";
			}		
		} else {
			$update = $this->chart_model->updateImmunization($this->input->post('imm_id'), $data);
			$this->audit_model->update();
			$result =  array(
				'message' => "Immunization updated!",
				'medtext' => $medtext
			);
		}
		echo json_encode($result);
		exit (0);
	}
	
	function delete_immunization()
	{
		$this->chart_model->deleteImmunization($this->input->post('imm_id'));
		$this->audit_model->delete();
		echo "Immunization deleted!";
	}
	
	function get_imm_notes()
	{
		$pid = $this->session->userdata('pid');
		$this->db->select('imm_notes');
		$this->db->where('pid', $pid);
		$result = $this->db->get('demographics')->row_array();
		if (is_null($result['imm_notes']) || $result['imm_notes'] == '') {
			echo "";
		} else {
			echo nl2br($result['imm_notes']) . '<br><br>';
		}
		exit( 0 );
	}
	
	function get_imm_notes1()
	{
		$pid = $this->session->userdata('pid');
		$this->db->select('imm_notes');
		$this->db->where('pid', $pid);
		$result = $this->db->get('demographics')->row_array();
		if (is_null($result['imm_notes']) || $result['imm_notes'] == '') {
			echo "";
		} else {
			echo $result['imm_notes'];
		}
		exit( 0 );
	}
	
	function edit_imm_notes()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$data = array(
			'imm_notes' => $this->input->post('imm_notes')
		);
		$this->demographics_model->update($pid, $data);
		$this->audit_model->update();
		echo "Immunization notes updated!";
		exit( 0 );
	}
	
	function consent_immunizations()
	{
		$pid = $this->session->userdata('pid');
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row(); 
		$name = $row->firstname . ' ' . $row->lastname;
		$datestring = "%M %d, %Y";
		$date = mdate($datestring);
		$pdfinfo = array(
			'name' => $name,
			'vaccine_list' => $this->input->post('vaccine_list'),
			'date' => $date
		);
		$input = '/var/www/nosh/vaccine_consent.pdf';
		$user_id = $this->session->userdata('user_id');
		$output = "/var/www/nosh/vaccine_consent_output_" . $user_id . ".pdf";
		$data='<?xml version="1.0" encoding="UTF-8"?>'."\n".
			'<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">'."\n".
			'<fields>'."\n";
		foreach($pdfinfo as $field => $val) {
			$data.='<field name="'.$field.'">'."\n";
			if(is_array($val)) {
				foreach($val as $opt)
					$data.='<value>'.$opt.'</value>'."\n";
			} else {
				$data.='<value>'.$val.'</value>'."\n";
			}
			$data.='</field>'."\n";
		}
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
		if (file_exists($output)) {
			echo "OK";
		} else {
			echo "Error generating consent!";
		}
	}
	
	function print_consent()
	{
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/vaccine_consent_output_" . $user_id . ".pdf";
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
	
	function page_immunization_list()
	{
		$pid = $this->session->userdata('pid');	
		$practice = $this->practiceinfo_model->get()->row();
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax . '<br />';
		$patient = $this->demographics_model->get($pid)->row_array();
		$body = 'Immunizations for ' . $patient['firstname'] . ' ' . $patient['lastname'] . ':<br />';
		$query = $this->db->query("SELECT * FROM immunizations WHERE pid=$pid ORDER BY imm_immunization ASC, imm_sequence ASC");
		if ($query->num_rows() > 0) {
			$body .= '<ul>';
			foreach ($query->result_array() as $row) {
				if ($row['imm_sequence'] == '1') {
					$sequence = 'first';
				}
				if ($row['imm_sequence'] == '2') {
					$sequence = 'second';
				}
				if ($row['imm_sequence'] == '3') {
					$sequence = 'third';
				}
				if ($row['imm_sequence'] == '4') {
					$sequence = 'fourth';
				}
				if ($row['imm_sequence'] == '5') {
					$sequence = 'fifth';
				}
				$date1 = human_to_unix($row['imm_date']);
				$date = date('F jS, Y', $date1);
				$body .= '<li>' . $row['imm_immunization'] . ', ' . $sequence . ', given on ' . $date . '</li>';
			}
			$body .= '</ul>';
		} else {
			$body .= 'None.';
		}
		$body .= '<br />Printed by ' . $this->session->userdata['displayname'] . '.';
		$data['letter'] = $body;
		return $this->load->view('auth/pages/provider/chart/letter_page',$data, TRUE);
	}
	
	function print_immunization_list()
	{
		ini_set('memory_limit','196M');
		$html = $this->page_immunization_list();
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/imm_list_" . now() . "_" . $user_id . ".pdf";
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Document Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
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
	
	function csv_immunization()
	{
		$this->load->helper('download');
		$pid = $this->session->userdata('pid');
		$this->db->select('immunizations.pid,demographics.lastname,demographics.firstname,demographics.DOB,demographics.sex,demographics.address,demographics.city,demographics.state,demographics.zip,demographics.phone_home,immunizations.imm_cvxcode,immunizations.imm_elsewhere,immunizations.imm_date,immunizations.imm_lot,immunizations.imm_manufacturer,insurance.insurance_plan_name', FALSE);
		$this->db->from('immunizations');
		$this->db->join('demographics', 'demographics.pid=immunizations.pid');
		$this->db->join('insurance', 'insurance.pid=immunizations.pid');
		$this->db->where('immunizations.pid', $pid);
		$this->db->where('insurance.insurance_plan_active', 'Yes');
		$this->db->where('insurance.insurance_order', 'Primary');
		$query = $this->db->get();
		$csv = '';
		if ($query->num_rows() > 0 ) {
			$result = $query->result_array();
			$csv .= "PatientID,Last,First,BirthDate,Gender,PatientAddress,City,State,Zip,Phone,ImmunizationCVX,OtherClinic,DateGiven,LotNumber,Manufacturer,InsuredPlanName";
			foreach ($result as $row) {
				$dob = human_to_unix($row['DOB']);
				$row['DOB'] = date('m/d/Y', $dob);
				$date2 = human_to_unix($row['imm_date']);
				$row['imm_date'] = date('m/d/Y', $date2);
				$row['sex'] = strtoupper($row['sex']);
				if ($row['imm_elsewhere'] == 'Yes') {
					$row['imm_elsewhere'] = $row['imm_date'];
				} else {
					$row['imm_elsewhere'] = '';
				}
				$csv .= "\n";
				$csv .= implode(',', $row);
			}
		}
		$date = now();
		$date1 = date('Ymd', $date);
		$name = $date1 . '_immunization.txt';
		force_download($name, $csv);		
	}
	
	// --------------------------------------------------------------------

	function alerts_list()
	{
		$pid = $this->session->userdata('pid');
		$d1 = now();
		$d1 = $d1 + 1209600;
		$date_active = date('Y-m-d H:i:s', $d1);	
		$query = $this->db->query("SELECT * FROM alerts WHERE pid=$pid AND alert_date_active<='$date_active' AND alert_date_complete='0000-00-00 00:00:00' AND alert_reason_not_complete=''");
		$result = '<strong>Alerts:</strong>';
		if ($query->num_rows() > 0) {
			$result .= '<ul>';
			foreach ($query->result_array() as $row) {
				$d = human_to_unix($row['alert_date_active']);
				$duedate = date('m/d/Y', $d);
				$result .= '<li>' . $row['alert'] . ' (Due ' . $duedate . ') - ' . $row['alert_description'] . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= ' None.';
		}
		echo $result;
		exit( 0 );
	}
	
	function alerts()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM alerts WHERE pid=$pid AND alert_date_complete='0000-00-00 00:00:00' AND alert_reason_not_complete=''");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM alerts WHERE pid=$pid AND alert_date_complete='0000-00-00 00:00:00' AND alert_reason_not_complete='' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}
	
	function alerts1()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM alerts WHERE pid=$pid AND alert_date_complete='0000-00-00 00:00:00' AND alert_reason_not_complete='' AND (alert='Laboratory results pending' OR alert='Radiology results pending' OR alert='Cardiopulmonary results pending')");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM alerts WHERE pid=$pid AND alert_date_complete='0000-00-00 00:00:00' AND alert_reason_not_complete='' AND (alert='Laboratory results pending' OR alert='Radiology results pending' OR alert='Cardiopulmonary results pending') ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}
	
	function alerts_complete()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM alerts WHERE pid=$pid AND alert_date_complete!='0000-00-00 00:00:00' AND alert_reason_not_complete=''");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM alerts WHERE pid=$pid AND alert_date_complete!='0000-00-00 00:00:00' AND alert_reason_not_complete='' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function alerts_not_complete()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM alerts WHERE pid=$pid AND alert_date_complete='0000-00-00 00:00:00' AND alert_reason_not_complete!=''");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM alerts WHERE pid=$pid AND alert_date_complete='0000-00-00 00:00:00' AND alert_reason_not_complete!='' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}
	
	function edit_alert()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$date = strtotime($this->input->post('alert_date_active'));
		$date_active = date('Y-m-d H:i:s', $date);	
		$data = array(
			'alert' => $this->input->post('alert'),
			'alert_description' => $this->input->post('alert_description'),
			'alert_date_active' => $date_active,
			'alert_date_complete' => '',
			'alert_reason_not_complete' => '',
			'alert_provider' => $this->input->post('id'),
			'orders_id' => '',
			'pid' => $pid
		);	
		if($this->input->post('alert_id') == '') {
			$add = $this->chart_model->addAlert($data);
			$this->audit_model->add();
			if ($add) {
				echo "Alert/Task added!";
			} else {
				echo "Error adding alert/task!";
			}
		} else {
			$update = $this->chart_model->updateAlert($this->input->post('alert_id'), $data);
			$this->audit_model->update();
			echo "Alert/Task updated!";
		}
	}
	
	function delete_alert()
	{
		$this->chart_model->deleteAlert($this->input->post('alert_id'));
		$this->audit_model->delete();
		echo "Alert/Task deleted!";
	}
	
	function complete_alert()
	{
		$date_complete = date('Y-m-d H:i:s');
		$data = array(
			'alert_date_complete' => $date_complete
		);
		$update = $this->chart_model->updateAlert($this->input->post('alert_id'), $data);
		$this->audit_model->update();
		$this->db->select('orders_id');
		$this->db->where('alert_id', $this->input->post('alert_id'));
		$query = $this->db->get('alerts');
		$row = $query->row_array();
		if($row['orders_id'] != '') {
			$data1 = array(
				'orders_completed' => 'Yes'
			);
			$this->chart_model->updateOrder($row['orders_id'], $data1);
			$this->audit_model->update();
		}
		echo "Alert/Task marked completed!";
	}
	
	function incomplete_alert()
	{
		$date_complete = date('Y-m-d H:i:s');
		$data = array(
			'alert_reason_not_complete' => $this->input->post('alert_reason_not_complete')
		);
		$update = $this->chart_model->updateAlert($this->input->post('alert_id1'), $data);
		$this->audit_model->update();
		echo "Alert/Task marked incomplete!";
	}

	// --------------------------------------------------------------------
	
	function messages_list()
	{
		$pid = $this->session->userdata('pid');
		$d1 = now();
		$d1 = $d1 + 1209600;
		$date_active = date('Y-m-d H:i:s', $d1);	
		$query = $this->db->query("SELECT * FROM t_messages WHERE pid=$pid");
		$result = '<strong>Number of messages: ';
		$result .= $query->num_rows() . '</strong>';
		if ($query->num_rows() > 0) {
			$result .= '<br><br><strong>Most Recent Signed Message:</strong>';
			$this->db->select('t_messages_date, t_messages_dos, t_messages_subject, t_messages_message');
			$this->db->where('pid', $pid);
			$this->db->where('t_messages_signed', 'Yes');
			$this->db->order_by('t_messages_date desc');
			$this->db->limit(1);
			$query2 = $this->db->get('t_messages');
			if ($query2->num_rows() > 0) {
				$row = $query2->row_array();
				$d = human_to_unix($row['t_messages_dos']);
				$dos = date('m/d/Y', $d);
				$result .= '<p class="tips"><strong>Date of Service: </strong>' . $dos . '</p>';
				$result .= '<p class="tips"><strong>Subject: </strong>' . $row['t_messages_subject'] . '</p>';
				$result .= '<p class="tips"><strong>Message: </strong>' . $row['t_messages_message'] . '</p>';
			} else {
				$result .= '<p class="tips"><strong>None.</strong>';
			}
		}
		echo $result;
		exit( 0 );
	}
	
	function messages()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM t_messages WHERE pid=$pid");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM t_messages WHERE pid=$pid ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function new_message()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$date = now();
		$date_active = date('Y-m-d H:i:s', $date);
		$data = array(
			't_messages_signed' => 'No',
			't_messages_dos' => $date_active,
			'pid' => $pid
		);
		$add = $this->chart_model->addTMessage($data);
		echo $add;
	}
	
	function strstrb($h, $n)
	{
		return array_shift(explode($n,$h,2));
	}
	
	function edit_message()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$date = strtotime($this->input->post('t_messages_dos'));
		$date_active = date('Y-m-d H:i:s', $date);
		$from = $this->session->userdata('displayname') . ' (' . $this->session->userdata('user_id') . ')';
		$to = $this->input->post('t_messages_to');
		$data = array(
			't_messages_subject' => $this->input->post('t_messages_subject'),
			't_messages_message' => $this->input->post('t_messages_message'),
			't_messages_dos' => $date_active,
			't_messages_provider' => $this->session->userdata('displayname'),
			't_messages_signed' => 'No',
			't_messages_to' => $this->input->post('t_messages_to'),
			't_messages_from' => $from,
			'pid' => $pid
		);
		$this->chart_model->updateTMessage($this->input->post('t_messages_id'), $data);
		$this->audit_model->update();
		if ($to != '') {
			$to_array = explode('(', $to);
			$mailbox = $this->strstrb($to_array[1], ')');
			$message_to = $this->input->post('t_messages_to');
			$message_from = $this->session->userdata('user_id');
			$this->db->select('lastname, firstname, DOB, pid');
			$this->db->where('pid', $pid);
			$demo_result = $this->db->get('demographics')->row_array();
			$dob1 = $demo_result['DOB'];
			$dob2 = strtotime($dob1);
			$datestring = "%m/%d/%Y";
			$dob = mdate($datestring, $dob2);
			$patient_name =  $demo_result['lastname'] . ', ' . $demo_result['firstname'] . ' (DOB: ' . $dob . ') (ID: ' . $pid . ')';
			$subject = 'Telephone Message - ' . $this->input->post('t_messages_subject');
			$data1 = array(
				'pid' => $pid,
				'patient_name' => $patient_name,
				'message_to' => $message_to,
				'message_from' => $message_from,
				'subject' => $subject,
				'body' => $this->input->post('t_messages_message'),
				'status' => 'Sent',
				't_messages_id' => $this->input->post('t_messages_id'),
				'mailbox' => $mailbox
			);
			$this->messaging_model->add($data1);
			$this->audit_model->add();
		}
		echo "Telephone message updated!";
	}
	
	function sign_message()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		
		$date = strtotime($this->input->post('t_messages_dos'));
		$date_active = date('Y-m-d H:i:s', $date);
		
		$text = nl2br($this->input->post('t_messages_message'));
		
		$data = array(
			't_messages_subject' => $this->input->post('t_messages_subject'),
			't_messages_message' => $text,
			't_messages_dos' => $date_active,
			't_messages_provider' => $this->session->userdata('displayname'),
			't_messages_signed' => 'Yes',
			't_messages_to' => '',
			't_messages_from' => '',
			'pid' => $pid
		);
		
		if($this->input->post('t_messages_id') == '') {
			$add = $this->chart_model->addTMessage($data);
			if ($add) {
				echo "Telephone message signed!";
			} else {
				echo "Error signing telephone message!";
			}
		} else {
			$update = $this->chart_model->updateTMessage($this->input->post('t_messages_id'), $data);
			echo "Telephone message signed!";
		}
	}
	
	function delete_message()
	{
		$this->chart_model->deleteTMessage($this->input->post('t_messages_id'));
		echo "Telephone message deleted!";
	}
	
	function internal_message_reply()
	{
		$pid = $this->session->userdata('pid');
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		if ($row['id'] == '') {
			if ($row['email'] == '') {
				echo 'No message sent!';
				exit (0);
			} else {
				$this->db->where('practice_id', '1');
				$row1 = $this->db->get('practiceinfo')->row_array();
				$displayname = $this->session->userdata('displayname');
				$message = 'You have new test results available from ' . $displayname . ". Reply to this e-mail at " . $row1['email'] . ' to create a secure account to view your results. After you establish an account, please go to ' . $row1['patient_portal'] . ' to view your results. Only authorized users will be able to access the results.';
				$config['protocol']='smtp';
				$config['smtp_host']='ssl://smtp.googlemail.com';
				$config['smtp_port']='465';
				$config['smtp_timeout']='30';
				$config['charset']='utf-8';
				$config['newline']="\r\n";
				$config['smtp_user']=$row1['smtp_user'];
				$config['smtp_pass']=$row1['smtp_pass'];
				$this->email->initialize($config);
				$this->email->from($row1['email'], $row1['practice_name']);
				$this->email->to($row['email']);
				$this->email->subject('Test Results Available');
				$this->email->message($message);
				if ($this->email->send()) {
    					echo 'E-mail notification sent!';
    					exit (0);
				} else {
					echo 'Error sending e-mail!';
					exit (0);
				}
			}
		} else {
			$from = $this->session->userdata('user_id');
			$dob1 = $row['DOB'];
			$dob2 = strtotime($dob1);
			$datestring = "%m/%d/%Y";
			$dob = mdate($datestring, $dob2);
			$patient_name = $row['lastname'] . ', ' . $row['firstname'] . ' (DOB: ' . $dob . ') (ID: ' . $row['pid'] . ')';
			$patient_name1 = $row['lastname'] . ', ' . $row['firstname'] . ' (ID: ' . $row['pid'] . ')';
			$body = $this->input->post('body') . "\nPlease contact me if you have any questions." . "\n\nSincerely,\n" . $this->session->userdata('displayname');
			$data = array(
				'pid' => $pid,
				'patient_name' => $patient_name,
				'message_to' => $patient_name1,
				'cc' => '',
				'message_from' => $from,
				'subject' => 'Your Test Results',
				'body' => $body,
				'status' => 'Sent',
				'mailbox' => $row['id']
			);
			$message_id = $this->messaging_model->add($data);
			$this->audit_model->add();
			$data1a = array(
				'pid' => $pid,
				'patient_name' => $patient_name,
				'message_to' => $patient_name1,
				'cc' => '',
				'message_from' => $from,
				'subject' => 'Your Test Results',
				'body' => $body,
				'status' => 'Sent',
				'mailbox' => '0'
			);
			$message_id = $this->messaging_model->add($data1a);
			$this->audit_model->add();
			if ($row['email'] == '') {
				echo 'Internal message sent!';
				exit (0);
			} else {
				$this->db->where('practice_id', '1');
				$row1 = $this->db->get('practiceinfo')->row_array();
				$displayname = $this->session->userdata('displayname');
				$message = 'You have new test results available from ' . $displayname . '. Please go to ' . $row1['patient_portal'] . ' to view your results. Only authorized users will be able to access the results.';
				$config['protocol']='smtp';
				$config['smtp_host']='ssl://smtp.googlemail.com';
				$config['smtp_port']='465';
				$config['smtp_timeout']='30';
				$config['charset']='utf-8';
				$config['newline']="\r\n";
				$config['smtp_user']=$row1['smtp_user'];
				$config['smtp_pass']=$row1['smtp_pass'];
				$this->email->initialize($config);
				$this->email->from($row1['email'], $row1['practice_name']);
				$this->email->to($row['email']);
				$this->email->subject('Test Results Available');
				$this->email->message($message);
				if ($this->email->send()) {
    				echo 'Internal message sent with e-mail notification!';
    				exit (0);
				} else {
					echo 'Error sending e-mail!';
					exit (0);
				}
			}
		}
	}
	
	function page_letter_reply($body)
	{
		$pid = $this->session->userdata('pid');
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		$practice = $this->practiceinfo_model->get()->row();
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['patientInfo1'] = $row['firstname'] . ' ' . $row['lastname'];
		$data['patientInfo2'] = $row['address'];
		$data['patientInfo3'] = $row['city'] . ', ' . $row['state'] . ' ' . $row['zip'];
		$data['firstname'] = $row['firstname'];
		$data['body'] = nl2br($body) . "<br><br>Please contact me if you have any questions.";
		$user_id = $this->session->userdata('user_id');
		$this->db->select('signature');
		$this->db->where('id', $user_id);
		$signature_query = $this->db->get('providers');
		if ($signature_query->num_rows() > 0) {
			$signature = $this->db->get('providers')->row_array();
			$signature1 = str_replace("/var/www/nosh/","", $signature['signature']);
			$data['displayname'] = "<img src='" . base_url() . $signature1 . "' border='0'><br>" . $this->session->userdata('displayname');
		} else {
			$data['displayname'] = '<br><br><br><br><br><br><br>' . $this->session->userdata('displayname');
		}
		$data['date'] = $date = date('F jS, Y');
		return $this->load->view('auth/pages/letter_page', $data, TRUE);
	}
	
	function letter_reply()
	{
		ini_set('memory_limit','196M');
		$pid = $this->session->userdata('pid');
		$date = now();
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$directory = $result['documents_dir'] . $pid;
		$file_path = $directory . '/letter_' . $date . '.pdf';
		$body = $this->input->post('body');
		$footer = '<div style="border-top: 1px solid #000000; text-align: center; padding-top: 3mm; font-size: 8px;">Page {PAGENO} of {nb}</div>';
		$footer .= '<p style="text-align:center; font-size: 8px;">';
		$footer .= "CONFIDENTIALITY NOTICE: The information contained in this facsimile transmission is intended for the recipient named above. If you are not the intended recipient or the intended recipient's agent, you are hereby notified that dissemination, copying, or distribution of the information contained in the transmission is prohibited. If you are not the intended recipient, please notify us immediately by telephone and return the documents to us by mail. Thank you.";
		$footer .= '</p><p style="text-align:center; font-size: 8px;">This document was generated by NOSH ChartingSystem</p>';
		$html = $this->page_letter_reply($body);
		$this->load->library('mpdf');
		$this->mpdf=new mpdf('','Letter',0,'',16,16,16,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Letter Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->simpleTables = true;
		$this->mpdf->Output($file_path,'F');
		while(!file_exists($file_path)) {
			sleep(2);
		}
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date1 = mdate($datestring, $date);
		$desc = 'Test Results Letter for ' . $this->session->userdata('ptname');
		$pages_data = array(
			'documents_url' => $file_path,
			'pid' => $pid,
			'documents_type' => 'Letters',
			'documents_desc' => $desc,
			'documents_from' => $this->session->userdata('displayname'),
			'documents_viewed' => $this->session->userdata('displayname'),
			'documents_date' => $date1
		);			
		$arr['id'] = $this->chart_model->addDocuments($pages_data);
		$this->audit_model->add();
		$arr['message'] = 'OK';
		echo json_encode($arr);
	}
	
	function add_orderslist()
	{
		$data = array(
			'orders_category' => $this->input->post('orders_category'),
			'orders_description' => $this->input->post('orders_description'),
			'cpt' => $this->input->post('cpt'),
			'snomed'=> $this->input->post('snomed'),
			'user_id' => $this->input->post('user_id')
		);
		if ($this->input->post('orderslist_id') == '') {
			$add = $this->chart_model->addOrdersList($data);
			$this->audit_model->add();
			$message = "Entry added as a template!";
		} else {
			$this->chart_model->updateOrdersList($this->input->post('orderslist_id'),$data);
			$this->audit_model->update();
			$message = "Entry updated as a template!";
		}
		echo $message;
		exit( 0 );
	}
	
	function edit_lab_provider()
	{
		$data = array(
			'displayname' => $this->input->post('facility'),
			'facility' => $this->input->post('facility'),
			'street_address1' => $this->input->post('street_address1'),
			'street_address2' => $this->input->post('street_address2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zip' => $this->input->post('zip'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax'),
			'comments' => $this->input->post('comments'),
			'ordering_id' => $this->input->post('ordering_id'),
			'specialty' => 'Laboratory'
		);		
		if($this->input->post('address_id') == '') {
			$add = $this->contact_model->addContact($data);
			$this->audit_model->add();
			if ($add) {
				$result['message'] = "Laboratory provider added!";
				$result['item'] = $this->input->post('facility') . ' (' . $add . ')';
				$result['id'] = $add;
				echo json_encode($result);
				exit( 0 );
			} else {
				$result['message'] = "Error adding laboratory provider";
				echo json_encode($result);
				exit( 0 );
			}
		} else {
			$update = $this->contact_model->updateContact($this->input->post('address_id'), $data);
			$this->audit_model->update();
			$result['message'] = "Laboratory provider updated!";
			$result['item'] = $this->input->post('facility') . ' (' . $this->input->post('address_id') . ')';
			$result['id'] = $this->input->post('address_id');
			echo json_encode($result);
			exit( 0 );
		}
	}
	
	function addressdefine()
	{
		$address_id = $this->input->post('address_id');
		$this->db->where('address_id', $address_id);
		$query = $this->db->get('addressbook');
		$row = $query->row_array();
		$result['item'] = $row['displayname'] . ' (' . $this->input->post('address_id') . ')';
		echo json_encode($result);
		exit ( 0 );
	}
	
	function labs_list()
	{
		$pid = $this->session->userdata('pid');		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$t_messages_id = $this->input->post('t_messages_id');		
		if ($t_messages_id == '') {
			$t_messages_id = '0';
		}
		if ($t_messages_id != '0') {
			$query = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_labs!='' AND t_messages_id=$t_messages_id");
		} else {
			$eid = $this->session->userdata('eid');
			if ($eid == FALSE) {
				$eid = '0';
			}
			$query = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_labs!='' AND eid=$eid");
		}
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		if ($t_messages_id != '0') {
			$query1 = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_labs!='' AND t_messages_id=$t_messages_id  ORDER BY $sidx $sord LIMIT $start , $limit");
		} else {
			$query1 = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_labs!='' AND eid=$eid ORDER BY $sidx $sord LIMIT $start , $limit");
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function add_lab_order()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = "Close Chart";
			echo json_encode($result);
			exit( 0 );
		}
		$t_messages_id = $this->input->post('t_messages_id');
		if ($t_messages_id == '') {
			if ($this->session->userdata('eid') == FALSE) {
				$result['message'] = "Close Chart";
				echo json_encode($result);
				exit( 0 );
			} else {
				$eid = $this->session->userdata('eid');
			}
			$t_messages_id = '';
		} else {
			$eid = '';
		}	
		$data = array(
			'orders_labs' => $this->input->post('orders_labs'),
			'orders_labs_obtained' => $this->input->post('orders_labs_obtained'),
			'orders_labs_icd' => $this->input->post('orders_labs_icd'),
			'address_id' => $this->input->post('address_id'),
			'orders_completed' => 'No',
			'orders_radiology' => '',
			'orders_radiology_icd' => '',
			'orders_referrals' => '',
			'orders_referrals_icd' => '',
			'orders_cp' => '',
			'orders_cp_icd' => '',
			'encounter_provider' => $this->session->userdata('displayname'),
			'pid' => $pid,
			'eid' => $eid,
			'orders_insurance' => $this->input->post('orders_insurance'),
			't_messages_id' => $t_messages_id
		);	
		$orders_id = $this->input->post('orders_id');
		if ($orders_id == '') {
			$add = $this->chart_model->addOrder($data);
			$this->audit_model->add();
			if ($add) {
				$date = now();
				$date_active = date('Y-m-d H:i:s', $date);
				$address_id = $this->input->post('address_id');
				$this->db->where('address_id', $address_id);
				$query = $this->db->get('addressbook');
				$row = $query->row_array();
				$description = 'Orders sent to ' . $row['displayname'] . ': '. $this->input->post('orders_labs'); 
				$data1 = array(
					'alert' => 'Laboratory results pending',
					'alert_description' => $description,
					'alert_date_active' => $date_active,
					'alert_date_complete' => '',
					'alert_reason_not_complete' => '',
					'alert_provider' => $this->session->userdata('user_id'),
					'orders_id' => $add,
					'pid' => $pid
				);
				$add1 = $this->chart_model->addAlert($data1);
				$this->audit_model->add();
				if ($add1) {
					$result['message'] = "Laboratory orders saved!";
					$result['id'] = $add;
					$result['choice'] = 'Choose an action for the lab order, reference number ' . $add;
					echo json_encode($result);
					exit( 0 );
				} else {
					$result['message'] = "Error adding laboratory order";
					echo json_encode($result);
					exit( 0 );
				}
			} 
		} else {
			$this->chart_model->updateOrder($orders_id, $data);
			$this->audit_model->update();
			$this->db->where('orders_id', $orders_id);
			$query1 = $this->db->get('alerts');
			$row1 = $query1->row_array();
			$alert_id = $row1['alert_id'];
			$date = now();
			$date_active = date('Y-m-d H:i:s', $date);
			$address_id = $this->input->post('address_id');
			$this->db->where('address_id', $address_id);
			$query = $this->db->get('addressbook');
			$row = $query->row_array();
			$description = 'Orders sent to ' . $row['displayname'] . ': '. $this->input->post('orders_labs'); 
			$data1 = array(
				'alert' => 'Laboratory results pending',
				'alert_description' => $description,
				'alert_date_active' => $date_active,
				'alert_date_complete' => '',
				'alert_reason_not_complete' => '',
				'alert_provider' => $this->session->userdata('user_id'),
				'pid' => $pid
			);
			$this->chart_model->updateAlert($alert_id, $data1);
			$this->audit_model->update();
			$result['message'] = "Laboratory orders updated!";
			$result['id'] = $orders_id;
			$result['choice'] = 'Choose an action for the lab order, reference number ' . $orders_id;
			echo json_encode($result);
			exit( 0 );
		}
	}
	
	function delete_lab()
	{
		$orders_id = $this->input->post("orders_id");
		$this->chart_model->deleteOrder($orders_id);
		$this->audit_model->delete();
		$result = "Laboratory order deleted.";
		echo $result;
		exit( 0 );
	}
	
	function import_lab1()
	{
		$pid = $this->session->userdata('pid');
		$t_messages_id = $this->input->post('t_messages_id');
		$query = $this->db->query("SELECT * FROM orders WHERE pid=$pid AND orders_labs!='' AND t_messages_id=$t_messages_id");
		if ($query->num_rows() > 0) {
			$result = "";
			foreach ($query->result_array() as $row) {
				$address_id = $row['address_id'];
				$this->db->where('address_id', $address_id);
				$query1 = $this->db->get('addressbook');
				$row1 = $query1->row_array();
				$result .= 'Orders sent to ' . $row1['displayname'] . ': '. $row['orders_labs'] . "\n\n"; 
			}
		} 
		echo $result;
		exit( 0 );
	}
	
	function edit_rad_provider()
	{
		$data = array(
			'displayname' => $this->input->post('facility'),
			'facility' => $this->input->post('facility'),
			'street_address1' => $this->input->post('street_address1'),
			'street_address2' => $this->input->post('street_address2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zip' => $this->input->post('zip'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax'),
			'comments' => $this->input->post('comments'),
			'ordering_id' => $this->input->post('ordering_id'),
			'specialty' => 'Radiology'
		);	
		if($this->input->post('address_id') == '') {
			$add = $this->contact_model->addContact($data);
			$this->audit_model->add();
			if ($add) {
				$result['message'] = "Radiology provider added!";
				$result['item'] = $this->input->post('facility') . ' (' . $add . ')';
				$result['id'] = $add;
				echo json_encode($result);
				exit( 0 );
			} else {
				$result['message'] = "Error adding radiology provider";
				echo json_encode($result);
				exit( 0 );
			}
		} else {
			$update = $this->contact_model->updateContact($this->input->post('address_id'), $data);
			$this->audit_model->update();
			$result['message'] = "Radiology provider updated!";
			$result['item'] = $this->input->post('facility') . ' (' . $this->input->post('address_id') . ')';
			$result['id'] = $this->input->post('address_id');
			echo json_encode($result);
			exit( 0 );
		}
	}
	
	function rad_list()
	{
		$pid = $this->session->userdata('pid');		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$t_messages_id = $this->input->post('t_messages_id');		
		if ($t_messages_id == '') {
			$t_messages_id = '0';
		}
		if ($t_messages_id != '0') {
			$query = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_radiology!='' AND t_messages_id=$t_messages_id");
		} else {
			$eid = $this->session->userdata('eid');
			if ($eid == FALSE) {
				$eid = '0';
			}
			$query = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_radiology!='' AND eid=$eid");
		}
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		if ($t_messages_id != '0') {
			$query1 = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_radiology!='' AND t_messages_id=$t_messages_id  ORDER BY $sidx $sord LIMIT $start , $limit");
		} else {
			$query1 = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_radiology!='' AND eid=$eid ORDER BY $sidx $sord LIMIT $start , $limit");
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function add_rad_order()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = "Close Chart";
			echo json_encode($result);
			exit( 0 );
		}
		$t_messages_id = $this->input->post('t_messages_id');
		if ($t_messages_id == '') {
			if ($this->session->userdata('eid') == FALSE) {
				$result['message'] = "Close Chart";
				echo json_encode($result);
				exit( 0 );	
			} else {
				$eid = $this->session->userdata('eid');
			}
			$t_messages_id = '';
		} else {
			$eid = '';
		}	
		$data = array(
			'orders_radiology' => $this->input->post('orders_radiology'),
			'orders_radiology_icd' => $this->input->post('orders_radiology_icd'),
			'address_id' => $this->input->post('address_id'),
			'orders_completed' => 'No',
			'orders_labs' => '',
			'orders_labs_obtained' => '',
			'orders_labs_icd' => '',
			'orders_referrals' => '',
			'orders_referrals_icd' => '',
			'orders_cp' => '',
			'orders_cp_icd' => '',
			'encounter_provider' => $this->session->userdata('displayname'),
			'pid' => $pid,
			'eid' => $eid,
			'orders_insurance' => $this->input->post('orders_insurance'),
			't_messages_id' => $t_messages_id
		);	
		$orders_id = $this->input->post('orders_id');
		if ($orders_id == '') {
			$add = $this->chart_model->addOrder($data);
			$this->audit_model->add();
			if ($add) {
				$date = now();
				$date_active = date('Y-m-d H:i:s', $date);
				$address_id = $this->input->post('address_id');
				$this->db->where('address_id', $address_id);
				$query = $this->db->get('addressbook');
				$row = $query->row_array();
				$description = 'Orders sent to ' . $row['displayname'] . ': '. $this->input->post('orders_radiology'); 
				$data1 = array(
					'alert' => 'Radiology results pending',
					'alert_description' => $description,
					'alert_date_active' => $date_active,
					'alert_date_complete' => '',
					'alert_reason_not_complete' => '',
					'alert_provider' => $this->session->userdata('user_id'),
					'orders_id' => $add,
					'pid' => $pid
				);
				$add1 = $this->chart_model->addAlert($data1);
				$this->audit_model->add();
				if ($add1) {
					$result['message'] = "Radiology orders saved!";
					$result['id'] = $add;
					$result['choice'] = 'Choose an action for the radiology order, reference number ' . $add;
					echo json_encode($result);
					exit( 0 );
				} else {
					$result['message'] = "Error adding radiology order";
					echo json_encode($result);
					exit( 0 );
				}
			} 
		} else {
			$this->chart_model->updateOrder($orders_id, $data);
			$this->audit_model->update();
			$this->db->where('orders_id', $orders_id);
			$query1 = $this->db->get('alerts');
			$row1 = $query1->row_array();
			$alert_id = $row1['alert_id'];
			$date = now();
			$date_active = date('Y-m-d H:i:s', $date);
			$address_id = $this->input->post('address_id');
			$this->db->where('address_id', $address_id);
			$query = $this->db->get('addressbook');
			$row = $query->row_array();
			$description = 'Orders sent to ' . $row['displayname'] . ': '. $this->input->post('orders_radiology'); 
			$data1 = array(
				'alert' => 'Radiology results pending',
				'alert_description' => $description,
				'alert_date_active' => $date_active,
				'alert_date_complete' => '',
				'alert_reason_not_complete' => '',
				'alert_provider' => $this->session->userdata('user_id'),
				'pid' => $pid
			);
			$this->chart_model->updateAlert($alert_id, $data1);
			$this->audit_model->update();
			$result['message'] = "Radiology orders updated!";
			$result['id'] = $orders_id;
			$result['choice'] = 'Choose an action for the radiology order, reference number ' . $orders_id;
			echo json_encode($result);
			exit( 0 );
		}
	}
	
	function delete_rad()
	{
		$orders_id = $this->input->post("orders_id");
		$this->chart_model->deleteOrder($orders_id);
		$this->audit_model->delete();
		$result = "Radiology order deleted.";
		echo $result;
		exit( 0 );
	}
	
	function import_rad1()
	{
		$pid = $this->session->userdata('pid');
		$t_messages_id = $this->input->post('t_messages_id');
		$query = $this->db->query("SELECT * FROM orders WHERE pid=$pid AND orders_radiology!='' AND t_messages_id=$t_messages_id");
		if ($query->num_rows() > 0) {
			$result = "";
			foreach ($query->result_array() as $row) {
				$address_id = $row['address_id'];
				$this->db->where('address_id', $address_id);
				$query1 = $this->db->get('addressbook');
				$row1 = $query1->row_array();
				$result .= 'Orders sent to ' . $row1['displayname'] . ': '. $row['orders_radiology'] . "\n\n"; 
			}
		} 
		echo $result;
		exit( 0 );
	}
	
	function edit_cp_provider()
	{
		$data = array(
			'displayname' => $this->input->post('facility'),
			'facility' => $this->input->post('facility'),
			'street_address1' => $this->input->post('street_address1'),
			'street_address2' => $this->input->post('street_address2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zip' => $this->input->post('zip'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax'),
			'comments' => $this->input->post('comments'),
			'ordering_id' => $this->input->post('ordering_id'),
			'specialty' => 'Cardiopulmonary'
		);	
		if($this->input->post('address_id') == '') {
			$add = $this->contact_model->addContact($data);
			$this->audit_model->add();
			if ($add) {
				$result['message'] = "Cardiopulmonary provider added!";
				$result['item'] = $this->input->post('facility') . ' (' . $add . ')';
				$result['id'] = $add;
				echo json_encode($result);
				exit( 0 );
			} else {
				$result['message'] = "Error adding cardiopulmonary provider";
				echo json_encode($result);
				exit( 0 );
			}
		} else {
			$update = $this->contact_model->updateContact($this->input->post('address_id'), $data);
			$this->audit_model->update();
			$result['message'] = "Cardiopulmonary provider updated!";
			$result['item'] = $this->input->post('facility') . ' (' . $this->input->post('address_id') . ')';
			$result['id'] = $this->input->post('address_id');
			echo json_encode($result);
			exit( 0 );
		}
	}
	
	function cp_list()
	{
		$pid = $this->session->userdata('pid');		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$t_messages_id = $this->input->post('t_messages_id');		
		if ($t_messages_id == '') {
			$t_messages_id = '0';
		}
		if ($t_messages_id != '0') {
			$query = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_cp!='' AND t_messages_id=$t_messages_id");
		} else {
			$eid = $this->session->userdata('eid');
			if ($eid == FALSE) {
				$eid = '0';
			}
			$query = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_cp!='' AND eid=$eid");
		}
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		if ($t_messages_id != '0') {
			$query1 = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_cp!='' AND t_messages_id=$t_messages_id  ORDER BY $sidx $sord LIMIT $start , $limit");
		} else {
			$query1 = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_cp!='' AND eid=$eid ORDER BY $sidx $sord LIMIT $start , $limit");
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function add_cp_order()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = "Close Chart";
			echo json_encode($result);
			exit( 0 );
		}
		$t_messages_id = $this->input->post('t_messages_id');
		if ($t_messages_id == '') {
			if ($this->session->userdata('eid') == FALSE) {
				$result['message'] = "Close Chart";
				echo json_encode($result);
				exit( 0 );	
			} else {
				$eid = $this->session->userdata('eid');
			}
			$t_messages_id = '';
		} else {
			$eid = '';
		}	
		$data = array(
			'orders_cp' => $this->input->post('orders_cp'),
			'orders_cp_icd' => $this->input->post('orders_cp_icd'),
			'address_id' => $this->input->post('address_id'),
			'orders_completed' => 'No',
			'orders_labs' => '',
			'orders_labs_obtained' => '',
			'orders_labs_icd' => '',
			'orders_referrals' => '',
			'orders_referrals_icd' => '',
			'orders_radiology' => '',
			'orders_radiology_icd' => '',
			'encounter_provider' => $this->session->userdata('displayname'),
			'pid' => $pid,
			'eid' => $eid,
			'orders_insurance' => $this->input->post('orders_insurance'),
			't_messages_id' => $t_messages_id
		);	
		$orders_id = $this->input->post('orders_id');
		if ($orders_id == '') {
			$add = $this->chart_model->addOrder($data);
			$this->audit_model->add();
			if ($add) {
				$date = now();
				$date_active = date('Y-m-d H:i:s', $date);
				$address_id = $this->input->post('address_id');
				$this->db->where('address_id', $address_id);
				$query = $this->db->get('addressbook');
				$row = $query->row_array();
				$description = 'Orders sent to ' . $row['displayname'] . ': '. $this->input->post('orders_cp'); 
				$data1 = array(
					'alert' => 'Cardiopulmonary results pending',
					'alert_description' => $description,
					'alert_date_active' => $date_active,
					'alert_date_complete' => '',
					'alert_reason_not_complete' => '',
					'alert_provider' => $this->session->userdata('user_id'),
					'orders_id' => $add,
					'pid' => $pid
				);
				$add1 = $this->chart_model->addAlert($data1);
				$this->audit_model->add();
				if ($add1) {
					$result['message'] = "Cardiopulmonary orders saved!";
					$result['id'] = $add;
					$result['choice'] = 'Choose an action for the cardiopulmonary order, reference number ' . $add;
					echo json_encode($result);
					exit( 0 );
				} else {
					$result['message'] = "Error adding cardiopulmonary order";
					echo json_encode($result);
					exit( 0 );
				}
			} 
		} else {
			$this->chart_model->updateOrder($orders_id, $data);
			$this->audit_model->update();
			$this->db->where('orders_id', $orders_id);
			$query1 = $this->db->get('alerts');
			$row1 = $query1->row_array();
			$alert_id = $row1['alert_id'];
			$date = now();
			$date_active = date('Y-m-d H:i:s', $date);
			$address_id = $this->input->post('address_id');
			$this->db->where('address_id', $address_id);
			$query = $this->db->get('addressbook');
			$row = $query->row_array();
			$description = 'Orders sent to ' . $row['displayname'] . ': '. $this->input->post('orders_cp'); 
			$data1 = array(
				'alert' => 'Cardiopulmonary results pending',
				'alert_description' => $description,
				'alert_date_active' => $date_active,
				'alert_date_complete' => '',
				'alert_reason_not_complete' => '',
				'alert_provider' => $this->session->userdata('user_id'),
				'pid' => $pid
			);
			$this->chart_model->updateAlert($alert_id, $data1);
			$this->audit_model->update();
			$result['message'] = "Cardiopulmonary orders updated!";
			$result['id'] = $orders_id;
			$result['choice'] = 'Choose an action for the Cardiopulmonary order, reference number ' . $orders_id;
			echo json_encode($result);
			exit( 0 );
		}
	}
	
	function delete_cp()
	{
		$orders_id = $this->input->post("orders_id");
		$this->chart_model->deleteOrder($orders_id);
		$this->audit_model->delete();
		$result = "Cardiopulmonary order deleted.";
		echo $result;
		exit( 0 );
	}
	
	function import_cp1()
	{
		$pid = $this->session->userdata('pid');
		$t_messages_id = $this->input->post('t_messages_id');
		$query = $this->db->query("SELECT * FROM orders WHERE pid=$pid AND orders_cp!='' AND t_messages_id=$t_messages_id");
		if ($query->num_rows() > 0) {
			$result = "";
			foreach ($query->result_array() as $row) {
				$address_id = $row['address_id'];
				$this->db->where('address_id', $address_id);
				$query1 = $this->db->get('addressbook');
				$row1 = $query1->row_array();
				$result .= 'Orders sent to ' . $row1['displayname'] . ': '. $row['orders_cp'] . "\n\n"; 
			}
		} 
		echo $result;
		exit( 0 );
	}
	
	function edit_ref_provider()
	{
		if($this->input->post('firstname') == '' OR $this->input->post('lastname') == '') {
			$displayname = $this->input->post('facility');
		} else {
			if($this->input->post('suffix') == '') {
				$displayname = $this->input->post('firstname') . ' ' . $this->input->post('lastname');
			} else {
				$displayname = $this->input->post('firstname') . ' ' . $this->input->post('lastname') . ', ' . $this->input->post('suffix');
			}
		}	
		$data = array(
			'displayname' => $displayname,
			'lastname' => $this->input->post('lastname'),
			'firstname' => $this->input->post('firstname'),
			'prefix' => $this->input->post('prefix'),
			'suffix' => $this->input->post('suffix'),
			'street_address1' => $this->input->post('street_address1'),
			'street_address2' => $this->input->post('street_address2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zip' => $this->input->post('zip'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax'),
			'comments' => $this->input->post('comments'),
			'ordering_id' => $this->input->post('ordering_id'),
			'specialty' => $this->input->post('specialty'),
			'facility' => $this->input->post('facility')
		);	
		if($this->input->post('address_id') == '') {
			$add = $this->contact_model->addContact($data);
			$this->audit_model->add();
			if ($add) {
				$result['message'] = "Referral provider added!";
				$result['item'] = $displayname . ' (' . $add . ')';
				$result['id'] = $add;
				echo json_encode($result);
				exit( 0 );
			} else {
				$result['message'] = "Error adding referral provider";
				echo json_encode($result);
				exit( 0 );
			}
		} else {
			$update = $this->contact_model->updateContact($this->input->post('address_id'), $data);
			$this->audit_model->update();
			$result['message'] = "Referral provider updated!";
			$result['item'] = $displayname . ' (' . $this->input->post('address_id') . ')';
			$result['id'] = $this->input->post('address_id');
			echo json_encode($result);
			exit( 0 );
		}
	}
	
	function make_referral()
	{
		$pid = $this->session->userdata('pid');	
		$query = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		$result['meds'] = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result['meds'][] = $row['rxl_medication'] . ' ' . $row['rxl_dosage'] . ' ' . $row['rxl_dosage_unit'] . ', ' . $row['rxl_sig'] . ' ' . $row['rxl_route'] . ' ' . $row['rxl_frequency'] . ' for ' . $row['rxl_reason'];
			}
		} else {
			$result['meds'][] = 'None.';
		}	
		$query1 = $this->db->query("SELECT * FROM issues WHERE pid=$pid AND issue_date_inactive='0000-00-00 00:00:00'");
		$result['issues'] = array();
		if ($query1->num_rows() > 0) {
			foreach ($query1->result_array() as $row1) {
				$result['issues'][] = $row1['issue'];
			}
		} else {
			$result['issues'][] = 'None.';
		}
		$query2 = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive='0000-00-00 00:00:00'");
		$result['allergies'] = array();
		if ($query2->num_rows() > 0) {
			foreach ($query2->result_array() as $row2) {
				$result['allergies'][] = $row2['allergies_med'] . ' - ' . $row2['allergies_reaction'];
			}
		} else {
			$result['allergies'][] = 'No known allergies.';
		}
		$result['displayname'] = $this->session->userdata('displayname');
		echo json_encode($result);
		exit( 0 );
	}
	
	function ref_list()
	{
		$pid = $this->session->userdata('pid');		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$t_messages_id = $this->input->post('t_messages_id');		
		if ($t_messages_id == '') {
			$t_messages_id = '0';
		}
		if ($t_messages_id != '0') {
			$query = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_referrals!='' AND t_messages_id=$t_messages_id");
		} else {
			$eid = $this->session->userdata('eid');
			if ($eid == FALSE) {
				$eid = '0';
			}
			$query = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_referrals!='' AND eid=$eid");
		}
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		if ($t_messages_id != '0') {
			$query1 = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_referrals!='' AND t_messages_id=$t_messages_id  ORDER BY $sidx $sord LIMIT $start , $limit");
		} else {
			$query1 = $this->db->query("SELECT * FROM orders JOIN addressbook ON orders.address_id=addressbook.address_id WHERE pid=$pid AND orders_referrals!='' AND eid=$eid ORDER BY $sidx $sord LIMIT $start , $limit");
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function add_ref_order()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = "Close Chart";
			echo json_encode($result);
			exit( 0 );
		}
		$t_messages_id = $this->input->post('t_messages_id');
		if ($t_messages_id == '') {
			if ($this->session->userdata('eid') == FALSE) {
				$result['message'] = "Close Chart";
				echo json_encode($result);
				exit( 0 );	
			} else {
				$eid = $this->session->userdata('eid');
			}
			$t_messages_id = '';
		} else {
			$eid = '';
		}	
		$data = array(
			'orders_referrals' => $this->input->post('orders_referrals'),
			'orders_referrals_icd' => $this->input->post('orders_referrals_icd'),
			'address_id' => $this->input->post('address_id'),
			'orders_completed' => 'No',
			'orders_labs' => '',
			'orders_labs_obtained' => '',
			'orders_labs_icd' => '',
			'orders_cp' => '',
			'orders_cp_icd' => '',
			'orders_radiology' => '',
			'orders_radiology_icd' => '',
			'encounter_provider' => $this->session->userdata('displayname'),
			'pid' => $pid,
			'eid' => $eid,
			'orders_insurance' => $this->input->post('orders_insurance'),
			't_messages_id' => $t_messages_id
		);	
		$orders_id = $this->input->post('orders_id');
		if ($orders_id == '') {
			$add = $this->chart_model->addOrder($data);
			$this->audit_model->add();
			if ($add) {
				$date = now();
				$date_active = date('Y-m-d H:i:s', $date);
				$address_id = $this->input->post('address_id');
				$this->db->where('address_id', $address_id);
				$query = $this->db->get('addressbook');
				$row = $query->row_array();
				$description = 'Orders sent to ' . $row['displayname'] . ': '. $this->input->post('orders_ref'); 
				$data1 = array(
					'alert' => 'Referral pending',
					'alert_description' => $description,
					'alert_date_active' => $date_active,
					'alert_date_complete' => '',
					'alert_reason_not_complete' => '',
					'alert_provider' => $this->session->userdata('user_id'),
					'orders_id' => $add,
					'pid' => $pid
				);
				$add1 = $this->chart_model->addAlert($data1);
				$this->audit_model->add();
				if ($add1) {
					$result['message'] = "Referral orders saved!";
					$result['id'] = $add;
					$result['choice'] = 'Choose an action for the referral order, reference number ' . $add;
					echo json_encode($result);
					exit( 0 );
				} else {
					$result['message'] = "Error adding referral order";
					echo json_encode($result);
					exit( 0 );
				}
			} 
		} else {
			$this->chart_model->updateOrder($orders_id, $data);
			$this->audit_model->update();
			$this->db->where('orders_id', $orders_id);
			$query1 = $this->db->get('alerts');
			$row1 = $query1->row_array();
			$alert_id = $row1['alert_id'];
			$date = now();
			$date_active = date('Y-m-d H:i:s', $date);
			$address_id = $this->input->post('address_id');
			$this->db->where('address_id', $address_id);
			$query = $this->db->get('addressbook');
			$row = $query->row_array();
			$description = 'Orders sent to ' . $row['displayname'] . ': '. $this->input->post('orders_ref'); 
			$data1 = array(
				'alert' => 'Referral results pending',
				'alert_description' => $description,
				'alert_date_active' => $date_active,
				'alert_date_complete' => '',
				'alert_reason_not_complete' => '',
				'alert_provider' => $this->session->userdata('user_id'),
				'pid' => $pid
			);
			$this->chart_model->updateAlert($alert_id, $data1);
			$this->audit_model->update();
			$result['message'] = "Referral order updated!";
			$result['id'] = $orders_id;
			$result['choice'] = 'Choose an action for the referral order, reference number ' . $orders_id;
			echo json_encode($result);
			exit( 0 );
		}
	}
	
	function delete_ref()
	{
		$orders_id = $this->input->post("orders_id");
		$this->chart_model->deleteOrder($orders_id);
		$this->audit_model->delete();
		$result = "Referral order deleted.";
		echo $result;
		exit( 0 );
	}
	
	function import_ref1()
	{
		$pid = $this->session->userdata('pid');
		$t_messages_id = $this->input->post('t_messages_id');
		$query = $this->db->query("SELECT * FROM orders WHERE pid=$pid AND orders_referrals!='' AND t_messages_id=$t_messages_id");
		if ($query->num_rows() > 0) {
			$result = "";
			foreach ($query->result_array() as $row) {
				$address_id = $row['address_id'];
				$this->db->where('address_id', $address_id);
				$query1 = $this->db->get('addressbook');
				$row1 = $query1->row_array();
				$result .= 'Referral sent to ' . $row1['displayname'] . ': '. $row['orders_referrals'] . "\n\n"; 
			}
		} 
		echo $result;
		exit( 0 );
	}
	
	function page_orders($orders_id)
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}	
		$this->db->where('orders_id', $orders_id);
		$query = $this->db->get('orders');
		$data['orders'] = $query->row();
		if ($data['orders']->orders_labs != '') {
			$data['title'] = "LABORATORY ORDER";
			$data['title1'] = "LABORATORY PROVIDER";
			$data['title2'] = "ORDER";
			$data['dx'] = nl2br($data['orders']->orders_labs_icd);
			$data['text'] = nl2br($data['orders']->orders_labs) . "<br><br>" . nl2br($data['orders']->orders_labs_obtained);
		}
		if ($data['orders']->orders_radiology != '') {
			$data['title'] = "IMAGING ORDER";
			$data['title1'] = "IMAGING PROVIDER";
			$data['title2'] = "ORDER";
			$data['dx'] = nl2br($data['orders']->orders_radiology_icd);
			$data['text'] = nl2br($data['orders']->orders_radiology);
		}
		if ($data['orders']->orders_cp != '') {
			$data['title'] = "CARDIOPULMONARY ORDER";
			$data['title1'] = "CARDIOPULMONARY PROVIDER";
			$data['title2'] = "ORDER";
			$data['dx'] = nl2br($data['orders']->orders_cp_icd);
			$data['text'] = nl2br($data['orders']->orders_cp);
		}
		if ($data['orders']->orders_referrals != '') {
			$data['title'] = "REFERRAL/GENERAL ORDERS";
			$data['title1'] = "REFERRAL PROVIDER";
			$data['title2'] = "DETAILS";
			$data['dx'] = nl2br($data['orders']->orders_referrals_icd);
			$data['text'] = nl2br($data['orders']->orders_referrals);
		}
		$address_id = $data['orders']->address_id;
		$this->db->where('address_id', $address_id);
		$query1 = $this->db->get('addressbook');
		$data['address'] = $query1->row();
		$practice = $this->practiceinfo_model->get()->row_array();
		$data['practiceName'] = $practice['practice_name'];
		$data['website'] = $practice['website'];
		$data['practiceInfo'] = $practice['street_address1'];
		if ($practice['street_address2'] != '') {
			$data['practiceInfo'] .= ', ' . $practice['street_address2'];
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice['city'] . ', ' . $practice['state'] . ' ' . $practice['zip'] . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice['phone'] . ', Fax: ' . $practice['fax'] . '<br />';
		if ($practice['practice_logo'] != '') {
			$logo = str_replace("/var/www/nosh/","", $practice['practice_logo']);
			$data['practiceLogo'] = "<img src='" . base_url() . $logo . "' border='0'>";
		} else {
			$data['practiceLogo'] = '<br><br><br><br><br>';
		}
		$data['patientInfo'] = $this->demographics_model->get($pid)->row();
		$dob1 = human_to_unix($data['patientInfo']->DOB);
		$data['dob'] = date('m/d/Y', $dob1);
		if ($data['patientInfo']->sex == 'm') {
			$data['sex'] = 'Male';
		} else {
			$data['sex'] = 'Female';
		}
		$orders_date = human_to_unix($data['orders']->orders_date);
		$data['orders_date'] = date('m/d/Y', $orders_date);
		$data['insuranceInfo'] = nl2br($data['orders']->orders_insurance);
		$user_id = $this->session->userdata('user_id');
		$this->db->select('signature');
		$this->db->where('id', $user_id);
		$signature_query = $this->db->get('providers');
		if ($signature_query->num_rows() > 0) {
			$signature = $this->db->get('providers')->row_array();
			$signature1 = str_replace("/var/www/nosh/","", $signature['signature']);
			$data['signature'] = "<img src='" . base_url() . $signature1 . "' border='0'>";
		} else {
			$data['signature'] = '&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>';
		}
		if ($data['orders']->orders_referrals != '') {
			return $this->load->view('auth/pages/referral_page', $data, TRUE);
		} else {
			return $this->load->view('auth/pages/order_page', $data, TRUE);
		}
	}
	
	function print_orders($orders_id)
	{
		ini_set('memory_limit','196M');
		$html = $this->page_orders($orders_id);
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/orders_" . now() . "_" . $user_id . ".pdf";
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Generated by NOSH ChartingSystem');
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
	
	function fax_orders()
	{
		$pid = $this->session->userdata('pid');
		if ($this->session->userdata('job_id') == FALSE) {
			$job_id = '';
		} else {
			$job_id = $this->session->userdata('job_id');
		}	
		$orders_id = $this->input->post('orders_id');
		$html = $this->page_orders($orders_id);
		$filename = '/var/www/nosh/' . now() . '_' . $orders_id . '.pdf';
		$user_id = $this->session->userdata('user_id');
		$this->db->select('signature');
		$this->db->where('id', $user_id);
		$signature = $this->db->get('providers')->row_array();
		ini_set('memory_limit','196M');
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Order Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->showImageErrors = true;
		$this->mpdf->simpleTables = true;
		$this->mpdf->Output($filename,'F');
		while(!file_exists($filename)) {
			sleep(2);
		}
		$this->db->where('orders_id', $orders_id);
		$row1 = $this->db->get('orders')->row_array();
		if ($row1['orders_labs'] != '') {
			$file_original = "Laboratory Order";
		}
		if ($row1['orders_radiology'] != '') {
			$file_original = "Imaging Order";
		}
		if ($row1['orders_cp'] != '') {
			$file_original = "Cardiopulmonary Order";
		}
		if ($row1['orders_referrals'] != '') {
			$file_original = "Referral Order";
		}
		$this->db->where('address_id', $row1['address_id']);
		$row2 = $this->db->get('addressbook')->row_array();
		$result_message = $this->fax_document($pid, $file_original, 'yes', $filename, $file_original, $row2['fax'], $row2['displayname'], $job_id, 'yes');
		$this->session->unset_userdata('job_id');
		unlink($filename);
		echo $result_message;
	}
	
	// --------------------------------------------------------------------

	function history_list()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		$query = $this->db->query("SELECT * FROM other_history WHERE pid=$pid");
		$result = '<h4>Past Medical History:</h4>';
		if ($query->num_rows() > 0) {
			$result .= '<ul>';
			foreach ($query->result_array() as $row) {
				$result .= '<li>' . $row['alert'] . ' - ' . $row['alert_description'] . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= 'None.';
		}
		echo $result;
		exit( 0 );
	}

	// --------------------------------------------------------------------

	function labs()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord'); 
		$query = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Laboratory'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Laboratory' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}

	function radiology()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Imaging'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;		
		$query1 = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Imaging' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}

	function cardiopulm()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Cardiopulmonary'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;		
		$query1 = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Cardiopulmonary' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}

	function endoscopy()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord'); 
		$query = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Endoscopy'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Endoscopy' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}

	function referrals()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord'); 
		$query = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Referrals'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Referrals' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;	
		echo json_encode($response);
		exit( 0 );
	}

	function past_records()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Past Records'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;		
		$query1 = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Past Records' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}

	function other_forms()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord'); 
		$query = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Other Forms'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Other Forms' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}

	function letters()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord'); 
		$query = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Letters'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND documents_type='Letters' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;		
		echo json_encode($response);
		exit( 0 );
	}
	
	function documents_upload()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$directory = $result['documents_dir'] . $pid;	
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'pdf';
		$config['encrypt_name'] = FALSE;
		$this->load->library('upload', $config);
		$field_name = "fileToUpload";
		$this->upload->do_upload($field_name);	
		$pages_data1 = $this->upload->data();
		$new_name = $pages_data1['full_path'] . '_' . now() . '.pdf';
		rename($pages_data1['full_path'], $new_name);		
		$pages_data2 = array(
			'documents_url' => $new_name,
			'pid' => $pid
		);			
		$documents_id = $this->chart_model->addDocuments($pages_data2);	
		$this->audit_model->add();
		if ($documents_id) {
			$arr['result'] = 'Document added!';
			$arr['result1'] = 'Enter specific information about uploaded document:' . $pages_data1['file_name'];
			$arr['id'] = $documents_id;
		} else {
			$arr['result'] = 'Error adding document!';
		}
		echo json_encode($arr);
	}
	
	function documents_upload1()
	{
		$pid = $this->session->userdata('pid');
		$date1 = $this->input->post('documents_date');
		$date2 = strtotime($date1);
		$datestring = "%Y-%m-%d";
		$date = mdate($datestring, $date2);		
		$data = array(
			'documents_type' => $this->input->post('documents_type'),
			'documents_desc' => $this->input->post('documents_desc'),
			'documents_from' => $this->input->post('documents_from'),
			'documents_viewed' => $this->input->post('documents_viewed'),
			'documents_date' => $date
		);	
		$this->chart_model->updateDocuments($this->input->post('documents_id'), $data);
		$this->audit_model->update();
		echo 'Document updated!';
	}
	
	function delete_upload()
	{
		$query = $this->chart_model->getDocuments($this->input->post('documents_id'));
		$result = $query->row_array();
		$delete = unlink($result['documents_url']);
		if ($delete == TRUE) {
			$this->chart_model->deleteDocuments($this->input->post('documents_id'));
			$this->audit_model->delete();
			$arr = 'Document deleted!';
		} else {
			$arr = 'Error deleting document!';
		}	
		echo $arr;
	}
	
	function view_documents($id)
	{
		$query = $this->chart_model->getDocuments($id);
		$result = $query->row_array();
		$file_path = $result['documents_url'];
		$data = array(
			'documents_viewed' => $this->session->userdata('displayname')
		);	
		$this->chart_model->updateDocuments($id, $data);
		$this->audit_model->update();
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
			exit;
			exit();
		} else {
			die('LOGGED! bad file '.$file_path);
		}
	}
	
	function view_documents1($id)
	{
		$pid = $this->session->userdata('pid');
		$query = $this->chart_model->getDocuments($id);
		$result = $query->row_array();
		$file_path = $result['documents_url'];
		$data1 = array(
			'documents_viewed' => $this->session->userdata('displayname')
		);	
		$this->chart_model->updateDocuments($id, $data1);
		$this->audit_model->update();
		$name = now() . '_' . $pid . '.pdf';
		$data['filepath'] = '/var/www/nosh/' . $name;
		copy($file_path, $data['filepath']);
		while(!file_exists($data['filepath'])) {
			sleep(2);
		}
		//$data['html'] = base_url() . $name;
		$data['html'] = '<iframe src="' . base_url() . $name . '" width="770" height="425" style="border: none;"></iframe>';
		$data['id'] = $id;
		echo json_encode($data);
	}
	
	function close_document()
	{
		unlink($this->input->post('document_filepath'));
		echo 'OK';
	}
	
	function delete_document()
	{
		$query = $this->chart_model->getDocuments($this->input->post('documents_id'));
		$result = $query->row_array();
		$exists = file_exists($result['documents_url']);
		$delete = FALSE;
		if ($exists == TRUE) {
			$delete = unlink($result['documents_url']);
		} else {
			$delete = TRUE;
		}
		if ($delete == TRUE) {
			$this->chart_model->deleteDocuments($this->input->post('documents_id'));
			$this->audit_model->delete();
			$arr = 'Document deleted!';
		} else {
			$arr = 'Error deleting document!';
		}
		echo $arr;
	}
	
	function edit_document()
	{
		$date1 = $this->input->post('documents_date');
		$date2 = strtotime($date1);
		$datestring = "%Y-%m-%d";
		$date = mdate($datestring, $date2);		
		$data = array(
			'documents_type' => $this->input->post('documents_type'),
			'documents_desc' => $this->input->post('documents_desc'),
			'documents_from' => $this->input->post('documents_from'),
			'documents_viewed' => $this->session->userdata('displayname'),
			'documents_date' => $date
		);	
		$this->chart_model->updateDocuments($this->input->post('documents_id'), $data);
		$this->audit_model->update();
		echo 'Document updated!';
	}
	
	function letter_template_select_list()
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
		$this->db->where('category', 'letter');
		$result = $this->db->get('templates')->result_array();
		$data['options'] = array();
		foreach ($result as $row) {
			$id = $row['template_id'];
			if ($row['template_name'] == 'Global Default') {
				if ($row['group'] == 'school_absence') {
					$name = 'School - Absence';
				}
				if ($row['group'] == 'school_return') {
					$name = 'School - Return';
				}
				if ($row['group'] == 'work_absence') {
					$name = 'Work - Absence';
				}
				if ($row['group'] == 'work_return') {
					$name = 'Work - Return';
				}
				if ($row['group'] == 'work_modified') {
					$name = 'Work - Modified Duties';
				}
			} else {
				$name = $row['template_name'];
			}
			$data['options'][$id] = $name;
		}
		echo json_encode($data);
	}
	
	function get_letter_template($id)
	{
		$this->db->where('template_id', $id);
		$row = $this->db->get('templates')->row_array();
		$data = unserialize($row['array']);
		echo json_encode($data);
	}
	
	function letter_template_construct()
	{
		$pid = $this->session->userdata('pid');
		$lastvisit = $this->encounters_model->lastEncounterVisitDate($pid);
		$ptname = $this->session->userdata('ptname');
		$row = $this->demographics_model->get($pid)->row_array();
		$dob1 = human_to_unix($row['DOB']);
		$dob = date('F jS, Y', $dob1);
		$datestring = "%Y-%m-%d";
		$arr['start'] = 'This letter is in regards to ' . $row['firstname'] . ' ' . $row['lastname'] . ' (Date of Birth: ' . $dob . '), who is a patient of mine.  ' . $row['firstname'] . ' was last seen by me on ' . $lastvisit . '.  ';
		$arr['firstname'] = $row['firstname'];
		echo json_encode($arr);
		exit (0);
	}
	
	// --------------------------------------------------------------------
	
	function mtm()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord'); 
		$query = $this->db->query("SELECT * FROM mtm WHERE pid=$pid");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM mtm WHERE pid=$pid ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function search_issues()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$pid = $this->session->userdata('pid');
		$data['response'] = 'false';
		$this->db->like('issue', $q);
		$this->db->select('issue');
		$this->db->where('pid', $pid);
		$this->db->where('issue_date_inactive', '0000-00-00 00:00:00');
		$query = $this->db->get('issues');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['issue'],
					'value' => $row['issue']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function edit_mtm()
	{
		$pid = $this->session->userdata('pid');
		$data = array(
			'mtm_description' => $this->input->post('mtm_description'),
			'mtm_recommendations' => $this->input->post('mtm_recommendations'),
			'mtm_beneficiary_notes' => $this->input->post('mtm_beneficiary_notes'),
			'pid' => $pid,
			'mtm_action' => $this->input->post('mtm_action'),
			'mtm_outcome' => $this->input->post('mtm_outcome'),
			'mtm_related_conditions' => $this->input->post('mtm_related_conditions'),
			'mtm_duration' => $this->input->post('mtm_duration')
		);
		if ($this->input->post('mtm_date_completed') != '') {
			$data['mtm_date_completed'] = date('Y-m-d H:i:s', strtotime($this->input->post('mtm_date_completed')));
			$data['complete'] = 'yes';
		} else {
			$data['mtm_date_completed'] = '';
			$data['complete'] = 'no';
		}
		if ($this->input->post('oper') == 'edit') {
			$this->chart_model->update_mtm($this->input->post('id'), $data);
			$this->audit_model->update();
		}
		if ($this->input->post('oper') == 'add') {
			$this->chart_model->add_mtm($data);
			$this->audit_model->add();
		}
		if ($this->input->post('oper') == 'del') {
			$id_del = explode(",",$this->input->post('id'));
			foreach ($id_del as $id_del1) {
				$this->chart_model->delete_mtm($id_del1);
				$this->audit_model->delete();
			}
		}
	}
	
	function encounter_mtm()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->db->query("SELECT * FROM mtm WHERE pid=$pid AND complete='no'");
		$data['value'] = "";
		$data['duration'] = "";
		if ($query->num_rows() > 0) {
			$data['value'] = "Medication Therapy Management Topics and Recommendations:\n";
			foreach ($query->result_array() as $row) {
				$data['value'] .= "Topic: " . $row['mtm_description'] . "\n";
				$data['value'] .= "Recommendations: " . $row['mtm_recommendations'] . "\n";
				if ($row['mtm_beneficiary_notes'] != '') {
					$data['value'] .= "Patient Notes: " . $row['mtm_beneficiary_notes'] . "\n";
				}
				if ($row['mtm_action'] != '') {
					$data['value'] .= "Actions Taken: " . $row['mtm_action'] . "\n";
				}
				if ($row['mtm_outcome'] != '') {
					$data['value'] .= "Outcome: " . $row['mtm_outcome'] . "\n";
				}
				if ($row['mtm_duration'] != '') {
					$data['duration'] += str_replace(" minutes", "", $row['mtm_duration']);
				}
				$data['value'] .= "\n";
			}
		}
		echo json_encode($data);
		exit (0);
	}
	
	function page_mtm_cp($pid)
	{
		$practice = $this->practiceinfo_model->get()->row_array();
		$data['practiceName'] = $practice['practice_name'];
		$data['practiceInfo1'] = $practice['street_address1'];
		if ($practice['street_address2'] != '') {
			$data['practiceInfo1'] .= ', ' . $practice['street_address2'];
		}
		$data['practiceInfo2'] = $practice['city'] . ', ' . $practice['state'] . ' ' . $practice['zip'];
		$data['practiceInfo3'] = 'Phone: ' . $practice['phone'] . ', Fax: ' . $practice['fax'];
		if ($practice['practice_logo'] != '') {
			$logo = str_replace("/var/www/nosh/","", $practice['practice_logo']);
			$data['practiceLogo'] = "<img src='" . base_url() . $logo . "' border='0'>";
		} else {
			$data['practiceLogo'] = '<br><br><br><br><br>';
		}
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		$data['patientInfo1'] = $row['firstname'] . ' ' . $row['lastname'];
		$data['patientInfo2'] = $row['address'];
		$data['patientInfo3'] = $row['city'] . ', ' . $row['state'] . ' ' . $row['zip'];
		$data['date'] = date('F jS, Y');
		$data['salutation'] = "Dear " . $row['firstname'] .",";
		$data['practicePhone'] = $practice['phone'];
		$user_id = $this->session->userdata('user_id');
		$this->db->select('signature');
		$this->db->where('id', $user_id);
		$signature_query = $this->db->get('providers');
		if ($signature_query->num_rows() > 0) {
			$signature = $this->db->get('providers')->row_array();
			$signature1 = str_replace("/var/www/nosh/","", $signature['signature']);
			$data['providerSignature'] = "<img src='" . base_url() . $signature1 . "' border='0'><br>" . $this->session->userdata('displayname');
		} else {
			$data['providerSignature'] = '<br><br><br><br><br><br><br>' . $this->session->userdata('displayname');
		}
		return $this->load->view('auth/pages/mtm_cp_page',$data, TRUE);
	}
	
	function page_mtm_map($pid)
	{
		$practice = $this->practiceinfo_model->get()->row_array();
		$data['practiceName'] = $practice['practice_name'];
		$data['practiceInfo1'] = $practice['street_address1'];
		if ($practice['street_address2'] != '') {
			$data['practiceInfo1'] .= ', ' . $practice['street_address2'];
		}
		$data['practiceInfo2'] = $practice['city'] . ', ' . $practice['state'] . ' ' . $practice['zip'];
		$data['practiceInfo3'] = 'Phone: ' . $practice['phone'] . ', Fax: ' . $practice['fax'];
		if ($practice['practice_logo'] != '') {
			$logo = str_replace("/var/www/nosh/","", $practice['practice_logo']);
			$data['practiceLogo'] = "<img src='" . base_url() . $logo . "' border='0'>";
		} else {
			$data['practiceLogo'] = '<br><br><br><br><br>';
		}
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		$data['patientDOB'] = date('m/d/Y', human_to_unix($row['DOB']));
		$data['patientInfo1'] = $row['firstname'] . ' ' . $row['lastname'];
		$data['patientInfo2'] = $row['address'];
		$data['patientInfo3'] = $row['city'] . ', ' . $row['state'] . ' ' . $row['zip'];
		$data['date'] = date('F jS, Y');
		$data['practicePhone'] = $practice['phone'];
		$query = $this->db->query("SELECT * FROM mtm WHERE pid=$pid AND complete='no'");
		$data['mapItems'] = '';
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $query_row) {
				$data['mapItems'] .= '<div style="width:6.62in;height:0.2in"></div>';
				$data['mapItems'] .= '<table><tr><td colspan="2" style="min-height:0.7in;">';
				$data['mapItems'] .= '<b>What we talked about:</b><br>' . $query_row['mtm_description'] . '</td></tr><tr><td style="width: 3.31in;min-height:0.9in;">';
				$data['mapItems'] .= '<b>What I need to do:</b><br>' . $query_row['mtm_recommendations'] . '</td><td style="width: 3.31in;min-height:0.9in;">';
				$data['mapItems'] .= '<b>What I did and when I did it:</b></td></table>';
			}
		}
		return $this->load->view('auth/pages/mtm_map_page',$data, TRUE);
	}
	
	function page_mtm_pml($pid)
	{
		$practice = $this->practiceinfo_model->get()->row_array();
		$data['practiceName'] = $practice['practice_name'];
		$data['practiceInfo1'] = $practice['street_address1'];
		if ($practice['street_address2'] != '') {
			$data['practiceInfo1'] .= ', ' . $practice['street_address2'];
		}
		$data['practiceInfo2'] = $practice['city'] . ', ' . $practice['state'] . ' ' . $practice['zip'];
		$data['practiceInfo3'] = 'Phone: ' . $practice['phone'] . ', Fax: ' . $practice['fax'];
		if ($practice['practice_logo'] != '') {
			$logo = str_replace("/var/www/nosh/","", $practice['practice_logo']);
			$data['practiceLogo'] = "<img src='" . base_url() . $logo . "' border='0'>";
		} else {
			$data['practiceLogo'] = '<br><br><br><br><br>';
		}
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		$data['patientDOB'] = date('m/d/Y', human_to_unix($row['DOB']));
		$data['patientInfo1'] = $row['firstname'] . ' ' . $row['lastname'];
		$data['patientInfo2'] = $row['address'];
		$data['patientInfo3'] = $row['city'] . ', ' . $row['state'] . ' ' . $row['zip'];
		$data['date'] = date('F jS, Y');
		$data['practicePhone'] = $practice['phone'];
		$allergies_query = $this->db->query("SELECT * FROM allergies WHERE pid=$pid AND allergies_date_inactive='0000-00-00 00:00:00'");
		if ($allergies_query->num_rows() > 0) {
			$data['allergies'] = '<ol>';
			foreach ($allergies_query->result_array() as $allergies_row) {
				$data['allergies'] .= '<li>' . $row['allergies_med'] . ' - ' . $row['allergies_reaction'] . '</li>';
			}
			$data['allergies'] .= '</ol>';
		} else {
			$data['allergies'] = 'None.';
		}
		$rx_query = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		$data['pmlItems'] = '';
		if ($rx_query->num_rows() > 0) {
			foreach ($rx_query->result_array() as $rx_row) {
				$data['pmlItems'] .= '<div style="width:6.62in;height:0.2in"></div>';
				$data['pmlItems'] .= '<table><tr><td colspan="2" style="min-height:0.23in;">';
				$data['pmlItems'] .= '<b>Medication:</b><br>' . $rx_row['rxl_medication'] . ', ' . $rx_row['rxl_dosage'] . ' ' . $rx_row['rxl_dosage_unit'] . '</td></tr><tr><td colspan="2" style="min-height:0.23in;">';
				if ($rx_row['rxl_sig'] == '') {
					$data['pmlItems'] .= '<b>How I use it:</b><br>' . $rx_row['rxl_instructions'] . '</td></tr><tr><td style="width: 3.31in;min-height:0.23in;">';
				} else {
					$data['pmlItems'] .= '<b>How I use it:</b><br>' . $rx_row['rxl_sig'] . ' ' . $rx_row['rxl_route'] . ' ' . $rx_row['rxl_frequency'] . '</td></tr><tr><td style="width: 3.31in;min-height:0.23in;">';
				}
				$data['pmlItems'] .= '<b>Why I use it:</b><br>' . ucfirst($rx_row['rxl_reason']) . '</td><td style="width: 3.31in;min-height:0.23in;">';
				$data['pmlItems'] .= '<b>Prescriber:</b><br>' . $rx_row['rxl_provider'] . '</td></tr><tr><td style="width: 3.31in;min-height:0.23in;">';
				$data['pmlItems'] .= '<b>Date I started using it:</b><br>' . date('m/d/Y', human_to_unix($rx_row['rxl_date_active'])) . '</td><td style="width: 3.31in;min-height:0.23in;">';
				$data['pmlItems'] .= '<b>Date I stopped using it:</b><br></td></tr><tr><td colspan="2" style="min-height:0.23in;">';
				$data['pmlItems'] .= '<b>Why I stopped using it:</b></td></tr></table>';
			}
		}
		return $this->load->view('auth/pages/mtm_pml_page',$data, TRUE);
	}
	
	function print_mtm()
	{
		ini_set('memory_limit','196M');
		$pid = $this->session->userdata('pid');
		$date = now();
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$directory = $result['documents_dir'] . $pid . "/mtm";
		if (file_exists($directory)) {
			foreach (scandir($directory) as $item) {
				if ($item == '.' || $item == '..') continue;
				unlink ($directory.DIRECTORY_SEPARATOR.$item);
			}
		} else {
			mkdir($directory, 0775);
		}
		$input = "";
		$file_path_cp = $directory . '/cp.pdf';
		$html_cp = $this->page_mtm_cp($pid);
		if (file_exists($file_path_cp)) {
			unlink($file_path_cp);
		}
		$footer = '<div style="border-top: 1px solid #000000; font-family: Arial, Helvetica, sans-serif; font-size: 7;"><div style="float:left;width:300px">Form CMS-10396 (01/12)</div><div style="float:left;text-align:right;">Form Approved OMB No. 0938-1154</div></div>';
		$footer .= '<div style="text-align:center; font-family: "Times New Roman", Times, serif; font-size: 12;">Page {PAGENO} of {nb}</div>';
		$this->load->library('mpdf');
		$this->mpdf=new mpdf('','Letter',0,'',26,26,26,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->WriteHTML($html_cp);
		$this->mpdf->SetTitle('Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->Output($file_path_cp,'F');
		while(!file_exists($file_path_cp)) {
			sleep(2);
		}
		$input = $file_path_cp;
		$file_path_map = $directory . '/map.pdf';
		$html_map = $this->page_mtm_map($pid);
		if (file_exists($file_path_map)) {
			unlink($file_path_map);
		}
		$this->mpdf=new mpdf('','Letter',0,'',26,26,26,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->WriteHTML($html_map);
		$this->mpdf->SetTitle('Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->Output($file_path_map,'F');
		while(!file_exists($file_path_map)) {
			sleep(2);
		}
		$input .= " " . $file_path_map;
		$file_path_pml = $directory . '/pml.pdf';
		$html_pml = $this->page_mtm_pml($pid);
		if (file_exists($file_path_pml)) {
			unlink($file_path_pml);
		}
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		$patientDOB = date('m/d/Y', human_to_unix($row['DOB']));
		$patientInfo1 = $row['firstname'] . ' ' . $row['lastname'];
		$header = '<div style="width:6.62in;text-align:center;" class="surround_div"><b class="smallcaps">Personal Medication List For ' . $patientInfo1 . ', ' . $patientDOB . '</b></div><br>(Continued)';
		$this->mpdf=new mpdf('','Letter',0,'',26,26,26,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->SetHTMLHeader($header,'O');
		$this->mpdf->SetHTMLHeader($header,'E');
		$this->mpdf->WriteHTML($html_pml);
		$this->mpdf->SetTitle('Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->Output($file_path_pml,'F');
		while(!file_exists($file_path_pml)) {
			sleep(2);
		}
		$input .= " " . $file_path_pml;
		$file_path = $result['documents_dir'] . $pid . "/mtm_" . now() . ".pdf";
		$commandpdf1 = "pdftk " . $input . " cat output " . $file_path;
		$commandpdf2 = escapeshellcmd($commandpdf1);
		exec($commandpdf2);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date1 = mdate($datestring, $date);
		$desc = 'Medication Therapy Management Letter for ' . $this->session->userdata('ptname');
		$pages_data = array(
			'documents_url' => $file_path,
			'pid' => $pid,
			'documents_type' => 'Letters',
			'documents_desc' => $desc,
			'documents_from' => $this->session->userdata('displayname'),
			'documents_viewed' => $this->session->userdata('displayname'),
			'documents_date' => $date1
		);			
		$arr['id'] = $this->chart_model->addDocuments($pages_data);
		$this->audit_model->add();
		$arr['message'] = 'OK';
		echo json_encode($arr);
	}
	
	function page_mtm_provider($pid)
	{
		$practice = $this->practiceinfo_model->get()->row_array();
		$data['practiceName'] = $practice['practice_name'];
		$data['practiceInfo1'] = $practice['street_address1'];
		if ($practice['street_address2'] != '') {
			$data['practiceInfo1'] .= ', ' . $practice['street_address2'];
		}
		$data['practiceInfo2'] = $practice['city'] . ', ' . $practice['state'] . ' ' . $practice['zip'];
		$data['practiceInfo3'] = 'Phone: ' . $practice['phone'] . ', Fax: ' . $practice['fax'];
		if ($practice['practice_logo'] != '') {
			$logo = str_replace("/var/www/nosh/","", $practice['practice_logo']);
			$data['practiceLogo'] = "<img src='" . base_url() . $logo . "' border='0'>";
		} else {
			$data['practiceLogo'] = '<br><br><br><br><br>';
		}
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		$data['patientDOB'] = date('m/d/Y', human_to_unix($row['DOB']));
		$data['patientInfo1'] = $row['firstname'] . ' ' . $row['lastname'];
		$data['patient_doctor'] = $row['preferred_provider'];
		$data['date'] = date('F jS, Y');
		$query = $this->db->query("SELECT * FROM mtm WHERE pid=$pid AND complete='no'");
		$data['topics'] = '';
		$data['recommendations'] = '';
		if ($query->num_rows() > 0) {
			$data['topics'] = "<ol>";
			$data['recommendations'] = "<ol>";
			foreach ($query->result_array() as $query_row) {
				$data['topics'] .= '<li>' . $query_row['mtm_description'] . '</li>';
				$data['recommendations'] .= '<li>' . $query_row['mtm_recommendations'] . '</li>';
			}
			$data['topics'] .= "</ol>";
			$data['recommendations'] .= "</ol>";
		}
		$user_id = $this->session->userdata('user_id');
		$this->db->select('signature');
		$this->db->where('id', $user_id);
		$signature_query = $this->db->get('providers');
		if ($signature_query->num_rows() > 0) {
			$signature = $this->db->get('providers')->row_array();
			$signature1 = str_replace("/var/www/nosh/","", $signature['signature']);
			$data['providerSignature'] = "<img src='" . base_url() . $signature1 . "' border='0'><br>" . $this->session->userdata('displayname');
		} else {
			$data['providerSignature'] = '<br><br><br><br><br><br><br>' . $this->session->userdata('displayname');
		}
		return $this->load->view('auth/pages/mtm_provider_page',$data, TRUE);
	}
	
	function print_mtm_provider($type)
	{
		ini_set('memory_limit','196M');
		$pid = $this->session->userdata('pid');
		$date = now();
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$directory = $result['documents_dir'] . $pid;
		$file_path_provider = $directory . '/mtm_' . now() . '_provider.pdf';
		$html_provider = $this->page_mtm_provider($pid);
		if (file_exists($file_path_provider)) {
			unlink($file_path_provider);
		}
		$this->load->library('mpdf');
		$this->mpdf=new mpdf('','Letter',0,'',26,26,26,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->AddPage();
		$this->mpdf->WriteHTML($html_provider);
		$this->mpdf->SetTitle('Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->Output($file_path_provider,'F');
		while(!file_exists($file_path_provider)) {
			sleep(2);
		}
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date1 = mdate($datestring, $date);
		$desc = 'Medication Therapy Management Provider Letter for ' . $this->session->userdata('ptname');
		$pages_data = array(
			'documents_url' => $file_path_provider,
			'pid' => $pid,
			'documents_type' => 'Letters',
			'documents_desc' => $desc,
			'documents_from' => $this->session->userdata('displayname'),
			'documents_viewed' => $this->session->userdata('displayname'),
			'documents_date' => $date1
		);
		$arr['id'] = $this->chart_model->addDocuments($pages_data);
		$this->audit_model->add();
		if ($type == "print") {
			$arr['message'] = 'OK';
		}
		if ($type == "fax") {
			$arr['message'] = $this->fax_document($pid, 'MTM Provider Letter', 'yes', $file_path_provider, '', $this->input->post('faxnumber'), $this->input->post('faxrecipient'), '', 'yes');
		}
		echo json_encode($arr);
		exit (0);
	}
	
	function add_mtm_alert($pid, $type)
	{
		if ($type == 'issues') {
			$query = $this->db->query("SELECT * FROM issues WHERE pid=$pid AND issue_date_inactive='0000-00-00 00:00:00'");
		}
		if ($type == 'medications') {
			$query = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		}
		if($query->num_rows() > 1) {
			$query1 = $this->db->query("SELECT * FROM alerts WHERE pid=$pid AND alert_date_complete='0000-00-00 00:00:00' AND alert_reason_not_complete='' AND alert='Medication Therapy Management'");
			if ($query1->num_rows() == 0) {
				$date_active = date('Y-m-d H:i:s', now());
				$description = 'Medication therapy management is needed due to more than 2 active medications or issues.'; 
				$data = array(
					'alert' => 'Medication Therapy Management',
					'alert_description' => $description,
					'alert_date_active' => $date_active,
					'alert_date_complete' => '',
					'alert_reason_not_complete' => '',
					'pid' => $pid
				);
				$this->chart_model->addAlert($data);
				$this->audit_model->add();
			}
		}
	}
	
	// --------------------------------------------------------------------
	
	function page_letter($letter_to, $letter_body, $address_id)
	{
		$body = '';
		if ($address_id != '') {
			$this->db->where('address_id', $address_id);
			$row = $this->db->get('addressbook')->row_array();
			$body .= $row['displayname'] . '<br>' . $row['street_address1'];
			if (isset($row['street_address2'])) {
				$body .= '<br>' . $row['street_address2'];
			}
			$body .= '<br>' . $row['city'] . ', ' . $row['state'] . ' ' . $row['zip'];
			$body .= '<br><br>';
		}
		$body .= $letter_to . ':';
		$body .= '<br><br>';
		$body .= nl2br($letter_body);
		$user_id = $this->session->userdata('user_id');
		$this->db->select('signature');
		$this->db->where('id', $user_id);
		$signature_query = $this->db->get('providers');
		if ($signature_query->num_rows() > 0) {
			$signature = $this->db->get('providers')->row_array();
			$signature1 = str_replace("/var/www/nosh/","", $signature['signature']);
			$sig = "<img src='" . base_url() . $signature1 . "' border='0'><br>" . $this->session->userdata('displayname');
		} else {
			$sig = '<br><br><br><br><br><br><br>' . $this->session->userdata('displayname');
		}
		$body .= '<br><br>Sincerely,<br>' . $sig;
		$body .= '</body></html>';
		return $body;
	}
	
	function print_letter()
	{
		ini_set('memory_limit','196M');
		$pid = $this->session->userdata('pid');
		$date = now();
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$directory = $result['documents_dir'] . $pid;
		$file_path = $directory . '/letter_' . $date . '.pdf';
		$letter_to = $this->input->post('letter_to');
		$letter_body = $this->input->post('letter_body');
		$address_id = $this->input->post('address_id');
		$footer = '<div style="border-top: 1px solid #000000; text-align: center; padding-top: 3mm; font-size: 8px;">Page {PAGENO} of {nb}</div>';
		$footer .= '<p style="text-align:center; font-size: 8px;">';
		$footer .= "CONFIDENTIALITY NOTICE: The information contained in this facsimile transmission is intended for the recipient named above. If you are not the intended recipient or the intended recipient's agent, you are hereby notified that dissemination, copying, or distribution of the information contained in the transmission is prohibited. If you are not the intended recipient, please notify us immediately by telephone and return the documents to us by mail. Thank you.";
		$footer .= '</p><p style="text-align:center; font-size: 8px;">This document was generated by NOSH ChartingSystem</p>';
		$html = $this->page_intro('Letter');
		$html .= $this->page_letter($letter_to, $letter_body, $address_id);
		$this->load->library('mpdf');
		$this->mpdf=new mpdf('','Letter',0,'',26,26,26,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Letter Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->Output($file_path,'F');
		while(!file_exists($file_path)) {
			sleep(2);
		}
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date1 = mdate($datestring, $date);
		$desc = 'Letter for ' . $this->session->userdata('ptname');
		$pages_data = array(
			'documents_url' => $file_path,
			'pid' => $pid,
			'documents_type' => 'Letters',
			'documents_desc' => $desc,
			'documents_from' => $this->session->userdata('displayname'),
			'documents_viewed' => $this->session->userdata('displayname'),
			'documents_date' => $date1
		);			
		$arr['id'] = $this->chart_model->addDocuments($pages_data);
		$this->audit_model->add();
		$arr['message'] = 'OK';
		echo json_encode($arr);
	}
	
	// --------------------------------------------------------------------

	function prevention()
	{
		$pid = $this->session->userdata('pid');
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row();
		if ($row->sex == 'm') {
			$gender = 'Male';
		} 
		if ($row->sex == 'f') {
			$gender = 'Female';
		}
		$dob1 = human_to_unix($row->DOB);
		$now = time();
		$age = ($now - $dob1)/31556926;
		$age = round($age, 0, PHP_ROUND_HALF_DOWN);
		$sexuallyactive = $row->sexuallyactive;
		$tobacco = $row->tobacco;
		$pregnant = $row->pregnant;
		if ($pregnant != 'no') {
			$pregnant1 = '&pregnant=Yes';
		} else {
			$pregnant1 = '';
		}
		$result = '';
		$this->load->library('domparser');
		$cr = curl_init('http://epss.ahrq.gov/ePSS/ePSSwidget.jsp');
		curl_setopt($cr, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cr, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($cr, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$data1 = curl_exec($cr);
		curl_close($cr);
		$fields_string = 'age=' . $age . '&sex=' . $gender . $pregnant1 . '&sexuallyActive=' . $sexuallyactive . '&tobacco=' . $tobacco . '&x=22&y=12';
		$cr1 = curl_init('http://epss.ahrq.gov/ePSS/GetResults.do?' . $fields_string);
		curl_setopt($cr1, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cr1, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt($cr1, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$data = curl_exec($cr1);
		curl_close($cr1);
		$html = $this->domparser->str_get_html($data);
		if (isset($html)) {
			$div = $html->find('div[id=maincontent]',0);
			if (isset($div)) {
				$intro = $html->find('p',0);
				$result .= $intro->outertext;
				$table = $div->find('table',0);
				if (isset($table)) {
					$result .= '<ul>';
					foreach ($html->find('div[id=maincontent]',0)->find('table',0)->find('tr') as $tr) {
						$td = $tr->find('td[bgcolor=#FFFFFF]',0);
						if (isset($td)) {
							$text = $td->innertext;
							$result .= '<li>' . $text . '</li>';
						}	
					}
					$result .= '</ul>';
				}
			} else {
				$result .= 'Unable to contact Electronic Preventative Services Selector!';
			}
			if ($age <= 18) {
				$result .= '<iframe src="http://www.cdc.gov/vaccines/schedules/hcp/imz/child-adolescent-shell.html"   width="100%" height="1200px" frameborder="0" scrolling="auto" id="Iframe" title="Child Immunization Schedule">Child Immunization Schedule</iframe>';
			}
			if ($age > 18) {
				$result .= '<iframe src="http://www.cdc.gov/vaccines/schedules/hcp/imz/adult-shell.html" width="100%" height="1200px" frameborder="0" scrolling="auto" id="Iframe" title="Adult Schedule">Adult Immunization Schedule</iframe>';
			}
		} else {
			$result .= 'Unable to contact Electronic Preventative Services Selector!';
		}
		echo $result;
	}
	
	// --------------------------------------------------------------------

	function billing_encounters()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND addendum='n'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND addendum='n' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			$this->db->where('eid', $row['eid']);
			$query2 = $this->db->get('billing_core');
			if ($query2->num_rows() > 0) {
				$charge = 0;
				$payment = 0;
				foreach ($query2->result_array() as $row1) {
					$charge += $row1['cpt_charge'] * $row1['unit'];
					$payment += $row1['payment'];
				}
				$row['balance'] = $charge - $payment;
				$row['charges'] = $charge;
			} else {
				$row['balance'] = 0;
				$row['charges'] = 0;
			}
			$response['rows'][$i]['id']=$row['eid']; 
			$response['rows'][$i]['cell']=array($row['eid'],$row['encounter_DOS'],$row['encounter_cc'],$row['charges'],$row['balance']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function billing_other()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM billing_core WHERE pid=$pid AND eid='0' AND payment='0'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM billing_core WHERE pid=$pid AND eid='0' AND payment='0' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			$this->db->where('other_billing_id', $row['other_billing_id']);
			$query2 = $this->db->get('billing_core');
			if ($query2->num_rows() > 0) {
				$charge = $row['cpt_charge'] * $row['unit'];
				$payment = 0;
				foreach ($query2->result_array() as $row1) {
					$payment += $row1['payment'];
				}
				$row['balance'] = $charge - $payment;
			} else {
				$row['balance'] = 0;
			}
			$response['rows'][$i]['id']=$row['other_billing_id']; 
			$response['rows'][$i]['cell']=array($row['other_billing_id'],$row['dos_f'],$row['reason'],$row['cpt_charge'] * $row['unit'],$row['balance']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function get_billing1($eid)
	{
		$query= $this->encounters_model->getAssessment($eid);
		$data = $query->row_array();
		if ($data['assessment_1'] != '') {
			$data1['1'] = $data['assessment_1'];
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
		echo json_encode($data1);
		exit ( 0 );
	}
	
	function get_prevention($eid)
	{
		$pid = $this->session->userdata('pid');
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row();
		$dob1 = human_to_unix($row->DOB);
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$dos1 = human_to_unix($encounterInfo->encounter_DOS);
		$agediff = $dos1- $dob1;
		if ($agediff < 31556926) {
			$data['prevent_established1'] = '99391';
			$data['prevent_new1'] = '99381';
		}
		if ($agediff >= 31556926 && $agediff < 157784630) {
			$data['prevent_established1'] = '99392';
			$data['prevent_new1'] = '99382';
		}
		if ($agediff >= 157784630 && $agediff < 378683112) {
			$data['prevent_established1'] = '99393';
			$data['prevent_new1'] = '99383';
		}
		if ($agediff >= 378683112 && $agediff < 568024668) {
			$data['prevent_established1'] = '99394';
			$data['prevent_new1'] = '99384';
		}
		if ($agediff >= 568024668 && $agediff < 1262277040) {
			$data['prevent_established1'] = '99395';
			$data['prevent_new1'] = '99385';
		}
		if ($agediff >= 1262277040 && $agediff < 2051200190) {
			$data['prevent_established1'] = '99396';
			$data['prevent_new1'] = '99386';
		}
		if ($agediff >= 2051200190) {
			$data['prevent_established1'] = '99397';
			$data['prevent_new1'] = '99387';
		}
		echo json_encode($data);
		exit ( 0 );
	}
	
	function get_insurance_id1($eid)
	{
		$query= $this->encounters_model->getBilling($eid);
		$data = $query->row_array();
		echo json_encode($data);
		exit ( 0 );
	}
	
	function procedure_codes1($eid)
	{
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = 'billing_core.' . $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT billing_core.*, cpt.cpt_description FROM billing_core JOIN cpt ON billing_core.cpt=cpt.cpt WHERE eid=$eid");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT billing_core.*, cpt.cpt_description FROM billing_core JOIN cpt ON billing_core.cpt=cpt.cpt WHERE eid=$eid ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function billing_save($eid)
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
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
			'billing_group' => $billing_group
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
		if (strlen($str) < $len) {
			$str1 = str_pad($str, $len);
		} else {
			$str1 = substr($str, 0, $len);
		}
		$str1 = strtoupper($str1);
		return $str1;
	}
	
	function billing_save_common($insurance_id_1, $insurance_id_2, $eid)
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$this->encounters_model->deleteBilling($eid);
		$this->audit_model->delete();
		$practiceInfo = $this->practiceinfo_model->get()->row();
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row();
		if ($insurance_id_1 == '0' || $insurance_id_1 == '') {
			$data0 = array(
				'eid' => $eid,
				'pid' => $pid,
				'insurance_id_1' => $insurance_id_1,
				'insurance_id_2' => $insurance_id_2
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
		if ($result2['street_address2'] == '') {
			$bill_ins_add1 = $result2['street_address1'];
		} else {
			$bill_ins_add1 = $result2['street_address1'] . ', ' . $result2['street_address2'];
		}
		$bill_ins_add1 = $this->string_format($bill_ins_add1, 29);
		$bill_ins_add2 = $result2['city'] . ', ' . $result2['state'] . ' ' . $result2['zip'];
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
		if ($encounterInfo->referring_provider != '') {
			$referring_provider = $encounterInfo->referring_provider;
			$bill_Box17 = $this->string_format($referring_provider, 26);
		} else {
			$bill_Box17 = $this->string_format("", 26);
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
		$this->db->select('id');
		$this->db->where('displayname', $provider);
		$user_id = $this->db->get('users')->row()->id;
		$this->db->select('npi');
		$this->db->where('id', $user_id);
		$query4 = $this->db->get('providers');
		$result4 = $query4->row();
		$npi = $result4->npi;
		$bill_Box17A = $this->string_format("", 17);
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
		$eid = $this->input->post('eid');
		$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $eid);
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
					$data['text'] .= '<table class="order" cellspacing="10"><tr><td style="width:100">Date of Payment:</td><td style="width:100">' . $result1a['dos_f'] . '</td><td style="width:350">' . $result1a['payment_type'] . '</td><td style="width:150">$(' . $result1a['payment'] . ')</td></tr></table>';
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
		$practice = $this->practiceinfo_model->get()->row();
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
		$this->db->select('billing_notes');
		$this->db->where('pid', $pid);
		$result = $this->db->get('demographics')->row_array();
		if (is_null($result['billing_notes']) || $result['billing_notes'] == '') {
			$billing_notes = 'Invoice for encounter (Date of Service: ' . $data['encounter_DOS'] . ') printed on ' . $data['date'] . '.';
		} else {
			$billing_notes = $result['billing_notes'] . "\n" . 'Invoice for encounter (Date of Service: ' . $data['encounter_DOS'] . ') printed on ' . $data['date'] . '.';
		}
		$billing_notes_data = array(
			'billing_notes' => $billing_notes
		);
		$this->demographics_model->update($pid, $billing_notes_data);
		$this->audit_model->update();
		return $this->load->view('auth/pages/invoice_page',$data, TRUE);
	}
	
	function print_invoice1($eid, $insurance_id_1='', $insurance_id_2='')
	{
		if ($insurance_id_1 != '') {
			$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $eid);
		}
		ini_set('memory_limit','196M');
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/invoice_" . now() . "_" . $user_id . ".pdf";
		$html = $this->page_invoice1($eid);
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Generated by NOSH ChartingSystem');
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
	
	function page_invoice2($id)
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}	
		$query1 = $this->db->query("SELECT * FROM billing_core WHERE other_billing_id=$id  AND payment='0'");
		$result1 = $query1->row_array();
		$num_rows1 = $query1->num_rows();
		if ($num_rows1 > 0) {
			$data['text'] = '<table class="order" cellspacing="10"><tr><th style="width:100">DATE</th><th style="width:100">UNITS</th><th style="width:350">DESCRIPTION</th><th style="width:150">CHARGE</th></tr>';
			$charge = 0;
			$payment = 0;
			$data['text'] .= '<tr><td>' . $result1['dos_f'] . '</td><td>' . $result1['unit'] . '</td><td>' . $result1['reason'] . '</td><td>$' . $result1['cpt_charge'] . '</td></tr>';
			$charge += $result1['cpt_charge'] * $result1['unit'];
			$other_billing_id = $result1['billing_core_id'];
			$query2 = $this->db->query("SELECT * FROM billing_core WHERE other_billing_id=$other_billing_id AND payment!='0'");
			if ($query2->num_rows() > 0) {
				foreach ($query2->result_array() as $row2) {
					$data['text'] .= '<tr><td>Date of Payment:</td><td>' . $row2['dos_f'] . '</td><td>' . $row2['payment_type'] . '</td><td>$(' . $row2['payment'] . ')</td></tr>';
					$payment += $row2['payment'];
				}
			}	
			$balance = $charge - $payment;
			$charge = number_format($charge, 2, '.', ',');
			$payment = number_format($payment, 2, '.', ',');
			$balance = number_format($balance, 2, '.', ',');
			$data['text'] .= '<tr><td></td><td></td><td><strong>Total Charges:</strong></td><td><strong>$' . $charge . '</strong></td></tr><tr><td></td><td></td><td><strong>Total Payments:</strong></td><td><strong>$' . $payment . '</strong></td></tr><tr><td></td><td></td><td></td><td><hr/></td></tr><tr><td></td><td></td><td><strong>Remaining Balance:</strong></td><td><strong>$' . $balance . '</strong></td></tr></table>';
		} else {
			$data['text'] = 'No procedures.';
		}
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		$practice = $this->practiceinfo_model->get()->row();
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
		$data['title'] = "INVOICE";
		$date = now();
		$data['date'] = date('F jS, Y', $date);
		$this->db->select('billing_notes');
		$this->db->where('pid', $pid);
		$result = $this->db->get('demographics')->row_array();
		if (is_null($result['billing_notes']) || $result['billing_notes'] == '') {
			$billing_notes = 'Invoice for ' . $result1['reason'] . ' (Date of Bill: ' . $result1['dos_f'] . ') printed on ' . $data['date'] . '.';
		} else {
			$billing_notes = $result['billing_notes'] . "\n" . 'Invoice for ' . $result1['reason'] . ' (Date of Bill: ' . $result1['dos_f'] . ') printed on ' . $data['date'] . '.';
		}
		$billing_notes_data = array(
			'billing_notes' => $billing_notes
		);
		$this->demographics_model->update($pid, $billing_notes_data);
		$this->audit_model->update();
		return $this->load->view('auth/pages/invoice_page2',$data, TRUE);
	}
	
	function print_invoice2($id)
	{
		ini_set('memory_limit','196M');
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/invoice_" . now() . "_" . $user_id . ".pdf";
		$html = $this->page_invoice2($id);
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Generated by NOSH ChartingSystem');
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
	
	function generate_hcfa1($eid, $insurance_id_1, $insurance_id_2)
	{
		$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $eid);
		$pid = $this->session->userdata('pid');
		$this->db->where('eid', $eid);
		$query = $this->db->get('billing');
		$input1 = '';
		if ($query->num_rows() > 0) {
			$i = 0;
			if (file_exists('/var/www/nosh/hcfa1500_output_final.pdf')) {
				unlink('/var/www/nosh/hcfa1500_output_final.pdf');
			}
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
			$data1 = array(
				'bill_submitted' => 'Done'
			);
			$this->encounters_model->updateEncounter($eid, $data1);
			exec($commandpdf3);
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
	}
	
	function get_payment()
	{
		$id = $this->input->post('id');
		$this->db->where('billing_core_id', $id);
		$result = $this->db->get('billing_core')->row_array();
		echo json_encode($result);
	}
	
	function billing_payment_history1($eid)
	{
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM billing_core WHERE eid=$eid AND payment!='0'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM billing_core WHERE eid=$eid AND payment!='0' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$total = 0;
		foreach ($query1->result_array() as $add) {
			$total += $add['payment'];
		}
		$response['rows'] = $records;
		$response['userdata']['dos_f'] = 'Total Payments:';
		$response['userdata']['payment'] = $total;
		echo json_encode($response);
		exit( 0 );
	}
	
	function billing_payment_history2($id)
	{
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM billing_core WHERE other_billing_id=$id AND payment!='0'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM billing_core WHERE other_billing_id=$id AND payment!='0' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$total = 0;
		foreach ($query1->result_array() as $add) {
			$total += $add['payment'];
		}
		$response['rows'] = $records;
		$response['userdata']['dos_f'] = 'Total Payments:';
		$response['userdata']['payment'] = $total;
		echo json_encode($response);
		exit( 0 );
	}
	
	function payment_save()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = "Close Chart";
			echo json_encode($result);
			exit (0);
		}
		$id = $this->input->post('billing_core_id');
		$this->db->where('billing_core_id', $id);
		$query = $this->db->get('billing_core');
		$count = $query->num_rows();
		$data = array(
			'eid' => $this->input->post('eid'),
			'other_billing_id' => $this->input->post('other_billing_id'),
			'pid' => $pid,
			'dos_f' => $this->input->post('dos_f'),
			'payment' => $this->input->post('payment'),
			'payment_type' => $this->input->post('payment_type')
		);
		if ($count > 0) {		
			$this->chart_model->updateBillingCore($id, $data);
			$this->audit_model->update();
			$result['message'] = 'Payment Updated';
		} else {
			$this->chart_model->addBillingCore($data);
			$this->audit_model->add();
			$result['message'] = 'Payment Added';
		}
		$result['eid'] = $this->input->post('eid');
		$result['other_billing_id'] = $this->input->post('other_billing_id');
		$result['dos_f'] = $this->input->post('dos_f');
		$result['payment'] = $this->input->post('payment');
		$result['payment_type'] = $this->input->post('payment_type');
		echo json_encode($result);
	}
	
	function delete_payment1()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$arr['message'] = "Close Chart";
			echo json_encode($arr);
			exit (0);
		}
		$id = $this->input->post('id');
		$this->db->where('billing_core_id', $id);
		$result = $this->db->get('billing_core')->row_array();
		$arr['id'] = $result['eid'];
		$this->chart_model->deleteBillingCore($this->input->post('id'));
		$this->audit_model->delete();
		$this->db->where('eid', $result['eid']);
		$query2 = $this->db->get('billing_core');
		if ($query2->num_rows() > 0) {
			$charge = 0;
			$payment = 0;
			foreach ($query2->result_array() as $row1) {
				$charge += $row1['cpt_charge'] * $row1['unit'];
				$payment += $row1['payment'];
			}
			$arr['balance'] = $charge - $payment;
		} else {
			$arr['balance'] = 0;
		}
		$arr['message'] = "Payment deleted!";
		echo json_encode($arr);
		exit (0);
	}
	
	function delete_payment2()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$arr['message'] = "Close Chart";
			echo json_encode($arr);
			exit (0);
		}
		$id = $this->input->post('id');
		$this->db->where('billing_core_id', $id);
		$result = $this->db->get('billing_core')->row_array();
		$arr['id'] = $result['other_billing_id'];
		$this->chart_model->deleteBillingCore($this->input->post('id'));
		$this->audit_model->delete();
		$this->db->where('other_billing_id', $result['other_billing_id']);
		$query2 = $this->db->get('billing_core');
		$this->db->where('billing_core_id', $result['other_billing_id']);
		$result2 = $this->db->get('billing_core')->row_array();
		if ($query2->num_rows() > 0) {
			$charge = $result2['cpt_charge'] * $result2['unit'];
			$payment = 0;
			foreach ($query2->result_array() as $row1) {
				$payment += $row1['payment'];
			}
			$arr['balance'] = $charge - $payment;
		} else {
			$arr['balance'] = 0;
		}
		$arr['message'] = "Payment deleted!";
		echo json_encode($arr);
		exit (0);
	}
	
	function billing_other_save()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			$result['message'] = "Close Chart";
			echo json_encode($result);
			exit (0);
		}
		$id = $this->input->post('other_billing_id');
		$this->db->where('billing_core_id', $id);
		$query = $this->db->get('billing_core');
		$count = $query->num_rows();
		$data = array(
			'eid' => '0',
			'pid' => $pid,
			'dos_f' => $this->input->post('dos_f'),
			'cpt_charge' => $this->input->post('cpt_charge'),
			'reason' => $this->input->post('reason'),
			'unit' => '1',
			'payment' => '0'
		);
		if ($count > 0) {		
			$this->chart_model->updateBillingCore($id, $data);
			$this->audit_model->update();
			$result['message'] = 'Miscellaneous Bill Updated';
		} else {
			$id1 = $this->chart_model->addBillingCore($data);
			$this->audit_model->add();
			$data1 = array(
				'other_billing_id' => $id1
			);
			$this->chart_model->updateBillingCore($id1, $data1);
			$this->audit_model->update();
			$result['message'] = 'Miscellaneous Bill Added';
		}
		$result['other_billing_id'] = $this->input->post('other_billing_id');
		$result['dos_f'] = $this->input->post('dos_f');
		$result['cpt_charge'] = $this->input->post('cpt_charge');
		$result['reason'] = $this->input->post('reason');
		echo json_encode($result);
	}
	
	function delete_other_bill()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		if ($this->input->post('billing_core_id') == '0') {
			echo "Incorrect Payment ID, try again!";
			exit (0);
		} else {
			$this->chart_model->deleteBillingCore($this->input->post('billing_core_id'));
			$this->audit_model->delete();
			$this->db->where('other_billing_id', $this->input->post('billing_core_id'));
			$this->db->delete('billing_core');
			$this->audit_model->delete();
			echo "Miscellaneous bill deleted!";
			exit (0);
		}
	}
	
	function define_icd($id)
	{
		$this->db->select('eid');
		$this->db->where('billing_core_id', $id);
		$eid_result = $this->db->get('billing_core')->row_array();
		$eid = $eid_result['eid'];
		$icd = $this->input->post('icd');
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
	
	function total_balance()
	{
		$pid = $this->session->userdata('pid');
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND addendum='n'");
		$i = 0;
		if ($query1->num_rows() > 0) {
			$balance1 = 0;
			foreach ($query1->result_array() as $row1) {
				$this->db->where('eid', $row1['eid']);
				$query1a = $this->db->get('billing_core');
				if ($query1a->num_rows() > 0) {
					$charge1 = 0;
					$payment1 = 0;
					foreach ($query1a->result_array() as $row1a) {
						$charge1 += $row1a['cpt_charge'] * $row1a['unit'];
						$payment1 += $row1a['payment'];
					}
					$balance1 += $charge1 - $payment1;
				} else {
					$balance1 += 0;
				}
				$i++; 
			}
		} else {
			$balance1 = 0;
		}
		$query2 = $this->db->query("SELECT * FROM billing_core WHERE pid=$pid AND eid='0' AND payment='0'");
		$j = 0;
		$charge2 = 0;
		$payment2 = 0;
		if ($query2->num_rows() > 0) {
			foreach ($query2->result_array() as $row2) {
				$charge2 += $row2['cpt_charge'] * $row2['unit'];
				$other_billing_id = $row2['billing_core_id'];
				$query2a = $this->db->query("SELECT * FROM billing_core WHERE other_billing_id=$other_billing_id AND payment!='0'");
				if ($query2a->num_rows() > 0) {
					foreach ($query2a->result_array() as $row2a) {
						$payment2 += $row2a['payment'];
					}
				}
				$balance2 = $charge2 - $payment2;
				$j++; 
			}
		} else {
			$balance2 = 0;
		}
		$total_balance = $balance1 + $balance2;
		$this->db->select('billing_notes');
		$this->db->where('pid', $pid);
		$result = $this->db->get('demographics')->row_array();
		if (is_null($result['billing_notes']) || $result['billing_notes'] == '') {
			$billing_notes = "None.";
		} else {
			$billing_notes = nl2br($result['billing_notes']);
		}
		echo "<strong>Total Balance: $" .  number_format($total_balance, 2, '.', ',') . "</strong><br><br><strong>Billing Notes: </strong>" . $billing_notes . "<br>";
		exit( 0 );
	}
	
	function get_billing_notes()
	{
		$pid = $this->session->userdata('pid');
		$this->db->select('billing_notes');
		$this->db->where('pid', $pid);
		$result = $this->db->get('demographics')->row_array();
		if (is_null($result['billing_notes']) || $result['billing_notes'] == '') {
			echo "";
		} else {
			echo $result['billing_notes'];
		}
		exit( 0 );
	}
	
	function edit_billing_notes()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$data = array(
			'billing_notes' => $this->input->post('billing_notes')
		);
		$this->demographics_model->update($pid, $data);
		$this->audit_model->update();
		echo "Billing notes updated!";
		exit( 0 );
	}
	
	// --------------------------------------------------------------------

	function records_release()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM hippa WHERE pid=$pid AND other_hippa_id='0'");
		$count = $query->num_rows();
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM hippa WHERE pid=$pid AND other_hippa_id='0' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function print_chart_save()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$date = strtotime($this->input->post('hippa_date_release'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date_release = mdate($datestring, $date);	
		$data = array(
			'hippa_date_release' => $date_release,
			'pid' => $pid,
			'hippa_reason' => $this->input->post('hippa_reason'),
			'hippa_provider' => $this->input->post('hippa_provider'),
			'hippa_role' => $this->input->post('hippa_role'),
			'other_hippa_id' => 0
		);
		if ($this->input->post('hippa_id') != '') {
			$id = $this->input->post('hippa_id');
			$this->chart_model->updateHippa($id, $data);
			$this->audit_model->update();
		} else {
			$id = $this->chart_model->addHippa($data);
			$this->audit_model->add();
		}
		echo $id;
		exit (0);
	}
	
	function get_release($hippa_id)
	{
		$query = $this->db->query("SELECT * FROM hippa WHERE hippa_id=$hippa_id AND other_hippa_id=0");
		$result = $query->row_array();
		echo json_encode($result);
		exit (0);
	}
	
	function get_release_stats()
	{
		$hippa_id = $this->input->post('hippa_id');
		$query = $this->db->query("SELECT * FROM hippa WHERE hippa_id=$hippa_id AND other_hippa_id=0");
		$result = $query->row_array();
		$date1 = human_to_unix($result['hippa_date_release']);
		$date = date('F jS, Y', $date1);
		$text = "<table><tr><td><strong>Date of Release:</strong></td><td>" . $date . "</td></tr><tr><td><strong>Reason:</strong></td><td>" . $result['hippa_reason'] . "</td></tr><tr><td><strong>To Whom:</strong></td><td>" . $result['hippa_provider'] . "</td></tr></table>";
		echo $text;
		exit( 0 );
	}
	
	function print_queue($id)
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM hippa WHERE other_hippa_id=$id");
		$count = $query->num_rows();
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM hippa WHERE other_hippa_id=$id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$i = 0;
		foreach ($query1->result_array() as $row) {
			if (isset($row['eid'])) {
				$this->db->where('eid', $row['eid']);
				$result1 = $this->db->get('encounters')->row_array();
				$row['description'] = $result1['encounter_cc'];
				$row['date'] = $result1['encounter_DOS'];
				$row['type'] = 'Encounter';
			}
			if (isset($row['t_messages_id'])) {
				$this->db->where('t_messages_id', $row['t_messages_id']);
				$result2 = $this->db->get('t_messages')->row_array();
				$row['description'] = $result2['t_messages_subject'];
				$row['date'] = $result2['t_messages_dos'];
				$row['type'] = 'Telephone Messages';
			}
			if (isset($row['documents_id'])) {
				$this->db->where('documents_id', $row['documents_id']);
				$result3 = $this->db->get('documents')->row_array();
				$row['description'] = $result3['documents_desc'] . ' from ' . $result3['documents_from'];
				$row['date'] = $result3['documents_date'];
				$row['type'] = $result3['documents_type'];
			}
			$response['rows'][$i]['id']=$row['hippa_id']; 
			$response['rows'][$i]['cell']=array($row['hippa_id'],$row['date'],$row['type'],$row['description']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function add_print_queue()
	{
		$pid = $this->session->userdata('pid');
		$data = array(
			'documents_id' => $this->input->post('documents_id'),
			'other_hippa_id' => $this->input->post('hippa_id'),
			'pid' => $pid
		);
		$this->db->insert('hippa', $data);
		$this->audit_model->add();
		echo "Item added to queue!";
	}
	
	function add_print_queue1()
	{
		$pid = $this->session->userdata('pid');
		$data = array(
			'eid' => $this->input->post('eid'),
			'other_hippa_id' => $this->input->post('hippa_id'),
			'pid' => $pid
		);
		$this->db->insert('hippa', $data);
		$this->audit_model->add();
		echo "Item added to queue!";
	}
	
	function add_print_queue2()
	{
		$pid = $this->session->userdata('pid');
		$data = array(
			't_messages_id' => $this->input->post('t_messages_id'),
			'other_hippa_id' => $this->input->post('hippa_id'),
			'pid' => $pid
		);
		$this->db->insert('hippa', $data);
		$this->audit_model->add();
		echo "Item added to queue!";
	}
	
	function print_chart($hippa_id, $fax = '')
	{
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$pid = $this->session->userdata('pid');
		$directory = $result['documents_dir'] . $pid . "/print_" . $hippa_id;
		$directory_links = $directory . "/links";
		if (file_exists($directory)) {
			foreach (scandir($directory) as $item) {
				if ($item == '.' || $item == '..' || $item == 'links') continue;
				unlink ($directory.DIRECTORY_SEPARATOR.$item);
			}
		} else {
			mkdir($directory, 0775);
		}
		if (file_exists($directory_links)) {
			foreach (scandir($directory_links) as $item1) {
				if ($item1 == '.' || $item1 == '..') continue;
				unlink ($directory_links.DIRECTORY_SEPARATOR.$item1);
			}
		} else {
			mkdir($directory_links, 0775);
		}
		$row = $this->demographics_model->get($pid)->row_array();
		$header_text = $row['lastname'] . ', ' . $row['firstname'] . '(DOB: ' . date('m/d/Y', human_to_unix($row['DOB'])) . ', Gender: ' . ucfirst($this->session->userdata('gender')) . ', ID: ' . $pid . ')';
		$header = strtoupper($header_text);
		$footer = '<div style="border-top: 1px solid #000000; text-align: center; padding-top: 3mm; font-size: 8px;">Page {PAGENO} of {nb}</div>';
		$footer .= '<p style="text-align:center; font-size: 8px;">';
		$footer .= "CONFIDENTIALITY NOTICE: The information contained in this facsimile transmission is intended for the recipient named above. If you are not the intended recipient or the intended recipient's agent, you are hereby notified that dissemination, copying, or distribution of the information contained in the transmission is prohibited. If you are not the intended recipient, please notify us immediately by telephone and return the documents to us by mail. Thank you.";
		$footer .= '</p><p style="text-align:center; font-size: 8px;">This document was generated by NOSH ChartingSystem</p>';
		$filename = $directory . '/printchart.pdf';
		if (file_exists($filename)) {
			unlink($filename);
		}
		$html = $this->page_intro('Medical Records');
		$this->db->where('pid', $pid);
		$this->db->where('encounter_signed', 'Yes');
		$this->db->where('addendum', 'n');
		$this->db->order_by('encounter_DOS', 'desc');
		$query1 = $this->db->get('encounters');
		if ($query1->num_rows() > 0) {
			$html .= '<table width="100%"><tr><th style="background-color: gray;color: #FFFFFF;">ENCOUNTERS</th></tr></table>';
			foreach ($query1->result_array() as $row1) {
				$html .= $this->encounters_view($row1['eid']);
			}
		}
		$this->db->where('pid', $pid);
		$this->db->where('t_messages_signed', 'Yes');
		$this->db->order_by('t_messages_dos', 'desc');
		$query2 = $this->db->get('t_messages');
		if ($query2->num_rows() > 0) {
			$html .= '<pagebreak /><table width="100%"><tr><th style="background-color: gray;color: #FFFFFF;">MESSAGES</th></tr></table>';
			foreach ($query2->result_array() as $row2) {
				$html .= $this->t_messages_view($row2['t_messages_id']);
			}
		}
		$html .= '</body></html>';
		ini_set('memory_limit','196M');
		$this->load->library('mpdf');
		$this->mpdf=new mpdf('','Letter',0,'',16,16,16,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->SetHTMLHeader($header,'O');
		$this->mpdf->SetHTMLHeader($header,'E');
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Chart Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->simpleTables = true;
		$this->mpdf->Output($filename,'F');
		while(!file_exists($filename)) {
			sleep(2);
		}
		$file_path_ccr = $directory . '/ccr.pdf';
		$html_ccr = $this->page_intro('Continuity of Care Record');
		$html_ccr .= $this->page_ccr($pid);
		if (file_exists($file_path_ccr)) {
			unlink($file_path_ccr);
		}
		$this->mpdf=new mpdf('','Letter',0,'',16,16,16,26,9,9,'P');
		//$this->mpdf=new mpdf('','Letter',0,'',26,26,26,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->simpleTables = true;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->SetHTMLHeader($header,'O');
		$this->mpdf->SetHTMLHeader($header,'E');
		$this->mpdf->WriteHTML($html_ccr);
		$this->mpdf->SetTitle('Document Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->Output($file_path_ccr,'F');
		while(!file_exists($file_path_ccr)) {
			sleep(2);
		}
		$file_path_docs = $directory . '/printchart_docs.pdf';
		$this->db->where('pid', $pid);
		$this->db->order_by("documents_date", "desc");
		$query3 = $this->db->get('documents');
		if ($query3->num_rows() > 0) {	
			if (file_exists($file_path_docs)) {
				unlink($file_path_docs);
			}
			foreach ($query3->result_array() as $row3) {
				$search = $result['documents_dir'] . $pid . "/";
				$link1 = str_replace($search, '', $row3['documents_url']);
				$link = $directory_links . "/" . now() . "_" . $link1;
				if(!file_exists($link)) {
					symlink($row3['documents_url'], $link);
				}
			}
			$documents_commandpdf1 = "gs -sDEVICE=pdfwrite -dNOPAUSE -dQUIET -dBATCH -sOutputFile=" . $file_path_docs . " " . $directory_links . "/*.pdf";
			exec($documents_commandpdf1);
		}
		while(!file_exists($file_path_docs)) {
			sleep(2);
		}
		if ($fax == 'Fax') {
			return TRUE;
		} else {
			echo "OK";
		}
	}
	
	function print_chart1($hippa_id, $fax = '')
	{
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$pid = $this->session->userdata('pid');
		$directory = $result['documents_dir'] . $pid . "/print_" . $hippa_id;
		$directory_links = $directory . "/links";
		if (file_exists($directory)) {
			foreach (scandir($directory) as $item) {
				if ($item == '.' || $item == '..' || $item == 'links') continue;
				unlink ($directory.DIRECTORY_SEPARATOR.$item);
			}
		} else {
			mkdir($directory, 0775);
		}
		if (file_exists($directory_links)) {
			foreach (scandir($directory_links) as $item1) {
				if ($item1 == '.' || $item1 == '..') continue;
				unlink ($directory_links.DIRECTORY_SEPARATOR.$item1);
			}
		} else {
			mkdir($directory_links, 0775);
		}
		$row = $this->demographics_model->get($pid)->row_array();
		$header_text = $row['lastname'] . ', ' . $row['firstname'] . '(DOB: ' . date('m/d/Y', human_to_unix($row['DOB'])) . ', Gender: ' . ucfirst($this->session->userdata('gender')) . ', ID: ' . $pid . ')';
		$header = strtoupper($header_text);
		$footer = '<div style="border-top: 1px solid #000000; text-align: center; padding-top: 3mm; font-size: 8px;">Page {PAGENO} of {nb}</div>';
		$footer .= '<p style="text-align:center; font-size: 8px;">';
		$footer .= "CONFIDENTIALITY NOTICE: The information contained in this facsimile transmission is intended for the recipient named above. If you are not the intended recipient or the intended recipient's agent, you are hereby notified that dissemination, copying, or distribution of the information contained in the transmission is prohibited. If you are not the intended recipient, please notify us immediately by telephone and return the documents to us by mail. Thank you.";
		$footer .= '</p><p style="text-align:center; font-size: 8px;">This document was generated by NOSH ChartingSystem</p>';
		$file_path_ccr = $directory . '/ccr.pdf';
		$html_ccr = $this->page_intro('Continuity of Care Record');
		$html_ccr .= $this->page_ccr($pid);
		if (file_exists($file_path_ccr)) {
			unlink($file_path_ccr);
		}
		$this->load->library('mpdf');
		$this->mpdf=new mpdf('','Letter',0,'',16,16,16,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->SetHTMLHeader($header,'O');
		$this->mpdf->SetHTMLHeader($header,'E');
		$this->mpdf->WriteHTML($html_ccr);
		$this->mpdf->SetTitle('Document Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->simpleTables = true;
		$this->mpdf->Output($file_path_ccr,'F');
		while(!file_exists($file_path_ccr)) {
			sleep(2);
		}
		$query1 = $this->db->query("SELECT * FROM hippa JOIN encounters ON hippa.eid=encounters.eid WHERE hippa.other_hippa_id=$hippa_id AND hippa.eid IS NOT NULL ORDER BY encounters.encounter_DOS DESC");
		$query2 = $this->db->query("SELECT * FROM hippa JOIN t_messages ON hippa.t_messages_id=t_messages.t_messages_id WHERE hippa.other_hippa_id=$hippa_id AND hippa.t_messages_id IS NOT NULL ORDER BY t_messages.t_messages_dos DESC");
		if ($query1->num_rows() > 0 || $query2->num_rows() > 0) {
			$filename = $directory . '/printchart.pdf';
			if (file_exists($filename)) {
				unlink($filename);
			}
			$html = $this->page_intro('Medical Records');
			if ($query1->num_rows() > 0) {
				$html .= '<table width="100%"><tr><th style="background-color: gray;color: #FFFFFF;">ENCOUNTERS</th></tr></table>';
				foreach ($query1->result_array() as $row1) {
					$html .= $this->encounters_view($row1['eid']);
				}
			}
			if ($query2->num_rows() > 0) {
				$html .= '<pagebreak /><table width="100%"><tr><th style="background-color: gray;color: #FFFFFF;">MESSAGES</th></tr></table>';
				foreach ($query2->result_array() as $row2) {
					$html .= $this->t_messages_view($row2['t_messages_id']);
				}
			}
			$this->mpdf=new mpdf('','Letter',0,'',16,16,16,26,9,9,'P');
			$this->mpdf->useOnlyCoreFonts = true;
			$this->mpdf->shrink_tables_to_fit=1;
			$this->mpdf->AddPage();
			$this->mpdf->SetHTMLFooter($footer,'O');
			$this->mpdf->SetHTMLFooter($footer,'E');
			$this->mpdf->SetHTMLHeader($header,'O');
			$this->mpdf->SetHTMLHeader($header,'E');
			$this->mpdf->WriteHTML($html);
			$this->mpdf->SetTitle('Chart Generated by NOSH ChartingSystem');
			$this->mpdf->debug = true;
			$this->mpdf->simpleTables = true;
			$this->mpdf->Output($filename,'F');
			while(!file_exists($filename)) {
				sleep(2);
			}
		}
		$query3 = $this->db->query("SELECT * FROM hippa JOIN documents ON hippa.documents_id=documents.documents_id WHERE hippa.other_hippa_id=$hippa_id AND hippa.documents_id IS NOT NULL ORDER BY documents.documents_date DESC");
		if ($query3->num_rows() > 0) {
			$file_path_docs = $directory . '/printchart_docs.pdf';
			if (file_exists($file_path_docs)) {
				unlink($file_path_docs);
			}
			if ($query3->num_rows() > 1) {
				foreach ($query3->result_array() as $row3) {
					$search = $result['documents_dir'] . $pid . "/";
					$link1 = str_replace($search, '', $row3['documents_url']);
					$link = $directory_links . "/" . now() . "_" . $link1;
					if(!file_exists($link)) {
						symlink($row3['documents_url'], $link);
					}
				}
				$documents_commandpdf1 = "gs -sDEVICE=pdfwrite -dNOPAUSE -dQUIET -dBATCH -sOutputFile=" . $file_path_docs . " " . $directory_links . "/*.pdf";
				exec($documents_commandpdf1);
			} else {
				$row4 = $query3->row_array();
				copy($row4['documents_url'], $file_path_docs);
			}
			while(!file_exists($file_path_docs)) {
				sleep(2);
			}
		}
		if ($fax == 'Fax') {
			return TRUE;
		} else {
			echo "OK";
		}
	}
	
	function print_chart2($hippa_id, $fax = '')
	{
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$pid = $this->session->userdata('pid');
		$directory = $result['documents_dir'] . $pid . "/print_" . $hippa_id;
		$directory_links = $directory . "/links";
		if (file_exists($directory)) {
			foreach (scandir($directory) as $item) {
				if ($item == '.' || $item == '..' || $item == 'links') continue;
				unlink ($directory.DIRECTORY_SEPARATOR.$item);
			}
		} else {
			mkdir($directory, 0775);
		}
		if (file_exists($directory_links)) {
			foreach (scandir($directory_links) as $item1) {
				if ($item1 == '.' || $item1 == '..') continue;
				unlink ($directory_links.DIRECTORY_SEPARATOR.$item1);
			}
		} else {
			mkdir($directory_links, 0775);
		}
		$row = $this->demographics_model->get($pid)->row_array();
		$header_text = $row['lastname'] . ', ' . $row['firstname'] . '(DOB: ' . date('m/d/Y', human_to_unix($row['DOB'])) . ', Gender: ' . ucfirst($this->session->userdata('gender')) . ', ID: ' . $pid . ')';
		$header = strtoupper($header_text);
		$footer = '<div style="border-top: 1px solid #000000; text-align: center; padding-top: 3mm; font-size: 8px;">Page {PAGENO} of {nb}</div>';
		$footer .= '<p style="text-align:center; font-size: 8px;">';
		$footer .= "CONFIDENTIALITY NOTICE: The information contained in this facsimile transmission is intended for the recipient named above. If you are not the intended recipient or the intended recipient's agent, you are hereby notified that dissemination, copying, or distribution of the information contained in the transmission is prohibited. If you are not the intended recipient, please notify us immediately by telephone and return the documents to us by mail. Thank you.";
		$footer .= '</p><p style="text-align:center; font-size: 8px;">This document was generated by NOSH ChartingSystem</p>';
		$filename = $directory . '/printchart.pdf';
		if (file_exists($filename)) {
			unlink($filename);
		}
		$end = now();
		$start = $end - 31556926;
		$html = $this->page_intro('Medical Records');
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND UNIX_TIMESTAMP(encounter_DOS)>=$start AND UNIX_TIMESTAMP(encounter_DOS)<=$end AND encounter_signed='Yes' AND addendum='n' ORDER BY encounter_DOS DESC");
		if ($query1->num_rows() > 0) {
			$html .= '<table width="100%"><tr><th style="background-color: gray;color: #FFFFFF;">ENCOUNTERS</th></tr></table>';
			foreach ($query1->result_array() as $row1) {
				$html .= $this->encounters_view($row1['eid']);
			}
		}
		$query2 = $this->db->query("SELECT * FROM t_messages WHERE pid=$pid AND UNIX_TIMESTAMP(t_messages_dos)>=$start AND UNIX_TIMESTAMP(t_messages_dos)<=$end AND t_messages_signed='Yes' ORDER BY t_messages_dos DESC");
		if ($query2->num_rows() > 0) {
			$html .= '<pagebreak /><table width="100%"><tr><th style="background-color: gray;color: #FFFFFF;">MESSAGES</th></tr></table>';
			foreach ($query2->result_array() as $row2) {
				$html .= $this->t_messages_view($row2['t_messages_id']);
			}
		}
		$html .= '</body></html>';
		ini_set('memory_limit','196M');
		$this->load->library('mpdf');
		$this->mpdf=new mpdf('','Letter',0,'',16,16,16,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->SetHTMLHeader($header,'O');
		$this->mpdf->SetHTMLHeader($header,'E');
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Chart Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->simpleTables = true;
		$this->mpdf->Output($filename,'F');
		while(!file_exists($filename)) {
			sleep(2);
		}
		$file_path_ccr = $directory . '/ccr.pdf';
		$html_ccr = $this->page_intro('Continuity of Care Record');
		$html_ccr = $this->page_ccr($pid);
		if (file_exists($file_path_ccr)) {
			unlink($file_path_ccr);
		}
		$this->mpdf=new mpdf('','Letter',0,'',16,16,16,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->SetHTMLHeader($header,'O');
		$this->mpdf->SetHTMLHeader($header,'E');
		$this->mpdf->WriteHTML($html_ccr);
		$this->mpdf->SetTitle('Document Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->simpleTables = true;
		$this->mpdf->Output($file_path_ccr,'F');
		while(!file_exists($file_path_ccr)) {
			sleep(2);
		}
		$query3 = $this->db->query("SELECT * FROM documents WHERE pid=$pid AND UNIX_TIMESTAMP(documents_date)>=$start AND UNIX_TIMESTAMP(documents_date)<=$end ORDER BY documents_date DESC");
		if ($query3->num_rows() > 0) {
			$file_path_docs = $directory . '/printchart_docs.pdf';
			if (file_exists($file_path_doc)) {
				unlink($file_path_doc);
			}
			foreach ($query3->result_array() as $row3) {
				$search = $result['documents_dir'] . $pid . "/";
				$link1 = str_replace($search, '', $row3['documents_url']);
				$link = $directory_links . "/" . now() . "_" . $link1;
				if(!file_exists($link)) {
					symlink($row3['documents_url'], $link);
				}
			}
			$documents_commandpdf1 = "gs -sDEVICE=pdfwrite -dNOPAUSE -dQUIET -dBATCH -sOutputFile=" . $file_path_docs . " " . $directory_links . "/*.pdf";
			exec($documents_commandpdf1);
			while(!file_exists($file_path_docs)) {
				sleep(2);
			}
		}
		if ($fax == 'Fax') {
			return TRUE;
		} else {
			echo "OK";
		}
	}
	
	function compile_chart($hippa_id)
	{
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$pid = $this->session->userdata('pid');
		$directory = $result['documents_dir'] . $pid . "/print_" . $hippa_id;
		$filename = $directory . '/printchart.pdf';
		$file_path_ccr = $directory . '/ccr.pdf';
		$file_path_docs = $directory . '/printchart_docs.pdf';
		$input = "";
		if (file_exists($file_path_ccr)) {
			$input .= $file_path_ccr;
		}
		if (file_exists($filename)) {
			$input .= " " . $filename;
		}
		if (file_exists($file_path_docs)) {
			$input .= " " . $file_path_docs;
		}
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/printchart_final_" . now() . "_" . $user_id . ".pdf";
		$commandpdf1 = "pdftk " . $input . " cat output " . $file_path;
		$commandpdf2 = escapeshellcmd($commandpdf1);
		exec($commandpdf2);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return $file_path;
	}
	
	function view_printchart($hippa_id)
	{
		$file_path = $this->compile_chart($hippa_id);
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
	
	function add_all_contact()
	{
		$data = array(
			'displayname' => $this->input->post('faxrecipient'),
			'fax' => $this->input->post('faxnumber')
		);
		$add = $this->contact_model->addContact($data);
		$this->audit_model->add();
		if ($add) {
			echo "Contact added!";
		} else {
			echo "Error adding contact!";
		}
	}
	
	function fax_chart($hippa_id)
	{
		$pid = $this->session->userdata('pid');
		if ($this->print_chart($hippa_id, 'Fax')) {
			$filename = $this->compile_chart($hippa_id);
			$result['message'] = $this->fax_document($pid, 'Medical Record', 'yes', $filename, '', $this->input->post('faxnumber'), $this->input->post('faxrecipient'), '', 'yes');
		} else {
			$result['message'] = "Error generating chart!";
		}
		echo json_encode($result);
		exit( 0 );
	}
	
	function fax_chart1($hippa_id)
	{
		$pid = $this->session->userdata('pid');
		if ($this->print_chart1($hippa_id, 'Fax')) {
			$filename = $this->compile_chart($hippa_id);
			$result['message'] = $this->fax_document($pid, 'Medical Record', 'yes', $filename, '', $this->input->post('faxnumber'), $this->input->post('faxrecipient'), '', 'yes');
		} else {
			$result['message'] = "Error generating chart!";
		}
		echo json_encode($result);
		exit( 0 );
	}
	
	function fax_chart2($hippa_id)
	{
		$pid = $this->session->userdata('pid');
		if ($this->print_chart2($hippa_id, 'Fax')) {
			$filename = $this->compile_chart($hippa_id);
			$result['message'] = $this->fax_document($pid, 'Medical Record', 'yes', $filename, '', $this->input->post('faxnumber'), $this->input->post('faxrecipient'), '', 'yes');
		} else {
			$result['message'] = "Error generating chart!";
		}
		echo json_encode($result);
		exit( 0 );
	}
	
	function delete_chart_item()
	{
		$hippa_id = $this->input->post('hippa_id');
		$this->db->where('hippa_id', $hippa_id);
		$this->db->delete('hippa');
		$this->audit_model->delete();
		echo 'Item removed from queue!';
	}
	
	function clear_queue()
	{
		$other_hippa_id = $this->input->post('other_hippa_id');
		$this->db->where('other_hippa_id', $other_hippa_id);
		$this->db->delete('hippa');
		$this->audit_model->delete();
		echo 'Queue cleared!';
	}
	
	function encounters_view($eid)
	{
		$this->lang->load('date');
		$pid = $this->session->userdata('pid');
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row_array();
		$data['eid'] = $eid;
		$dos1 = human_to_unix($encounterInfo['encounter_DOS']);
		$data['encounter_DOS'] = date('F jS, Y; h:i A', $dos1);
		$data['encounter_provider'] = $encounterInfo['encounter_provider'];
		$date_signed1 = human_to_unix($encounterInfo['date_signed']);
		$data['date_signed'] = date('F jS, Y; h:i A', $date_signed1);
		$data['age1'] = $encounterInfo['encounter_age'];
		$data['encounter_cc'] = nl2br($encounterInfo['encounter_cc']);
		$practiceInfo = $this->practiceinfo_model->get()->row_array();
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
			$billingInfo = $this->encounters_model->getBilling($eid)->row_array();
			if ($billingInfo['bill_complex'] != '') {
				$data['billing'] .= '<br><strong>Medical Complexity: </strong>';
				$data['billing'] .= nl2br($billingInfo['bill_complex']);
				$data['billing'] .= '<br /><br />';
			}
			$data['billing'] .= '</p>';
		} else {
			$data['billing'] = '';
		}
		return $this->load->view('auth/pages/provider/chart/encounters_view', $data, TRUE);
	}
	
	function print_encounters()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND encounter_signed='Yes' AND addendum='n'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND encounter_signed='Yes' AND addendum='n' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function modal_view($eid)
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
		$practiceInfo = $this->practiceinfo_model->get()->row_array();
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
			$billingInfo = $this->encounters_model->getBilling($eid)->row_array();
			if ($billingInfo['bill_complex'] != '') {
				$data['billing'] .= '<br><strong>Medical Complexity: </strong>';
				$data['billing'] .= nl2br($billingInfo['bill_complex']);
				$data['billing'] .= '<br /><br />';
			}
			$data['billing'] .= '</p>';
		} else {
			$data['billing'] = '';
		}
		$data['title'] = 'Encounter Details';
		$this->load->view('auth/pages/provider/chart/encounters/modal_view', $data);
	}
	
	function print_messages()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM t_messages WHERE pid=$pid AND t_messages_signed='Yes'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM t_messages WHERE pid=$pid AND t_messages_signed='Yes' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function t_messages_view($t_messages_id)
	{
		$this->db->where('t_messages_id', $t_messages_id);
		$row = $this->db->get('t_messages')->row_array();
		$d = human_to_unix($row['t_messages_dos']);
		$dos = date('m/d/Y', $d);
		$text = '<table cellspacing="10"><tr><th style="background-color: gray;color: #FFFFFF; text-align: left;">MESSAGE DETAILS</th></tr><tr><td><h4>Date of Service: </h4>' . $dos;
		$text .= '<br><h4>Subject: </h4>' . $row['t_messages_subject'];
		$text .= '<br><h4>Message: </h4>' . $row['t_messages_message'] . '<br><hr />Electronically signed by ' . $row['t_messages_provider'] . '.';
		$text .= '</td></tr></table>';
		return $text;
	}
	
	function import_ccr()
	{
		$pid = $this->session->userdata('pid');
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$directory = $result['documents_dir'] . $pid;	
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'xml';
		$config['encrypt_name'] = FALSE;
		$this->load->library('upload', $config);
		$field_name = "fileToUpload1";
		$this->upload->do_upload($field_name);	
		$pages_data1 = $this->upload->data();
		while(!file_exists($pages_data1['full_path'])) {
			sleep(2);
		}
		$xml = simplexml_load_file($pages_data1['full_path']);
		$phone_home = '';
		$phone_work = '';
		$phone_cell = '';
		foreach ($xml->Actors->Actor[0]->Telephone as $phone) {
			if ((string) $phone->Type->Text == 'Home') {
				$phone_home = (string) $phone->Value;
			}
			if ((string) $phone->Type->Text == 'Mobile') {
				$phone_cell = (string) $phone->Value;
			}
			if ((string) $phone->Type->Text == 'Alternate') {
				$phone_work = (string) $phone->Value;
			}
		}
		$address = (string) $xml->Actors->Actor[0]->Address->Line1;
		$address = ucwords(strtolower($address));
		$city = (string) $xml->Actors->Actor[0]->Address->City;
		$city = ucwords(strtolower($city));
		$data1 = array(
			'address' => $address,
			'city' => $city,
			'state' => (string) $xml->Actors->Actor[0]->Address->State,
			'zip' => (string) $xml->Actors->Actor[0]->Address->PostalCode,
			'phone_home' => $phone_home,
			'phone_work' => $phone_work,
			'phone_cell' => $phone_cell,
		);
		$this->demographics_model->update($pid, $data1);
		$this->audit_model->update(); 
		if (isset($xml->Body->Problems)) {
			foreach ($xml->Body->Problems->Problem as $issue) {
				if ((string) $issue->Status->Text == 'Active') {
					$icd9 = (string) $issue->Description->Code->Value;
					$this->db->where('icd9', $icd9);
					$this->db->select('icd9, icd9_description');
					$query1 = $this->db->get('icd9');
					if ($query1->num_rows() > 0) {
						$row1 = $query1->row_array();
						$issue_post = $row1['icd9_description'] . ' [' . $row1['icd9'] . ']';
					} else {
						$issue_post = (string) $issue->Description->Text . ' [' . (string) $issue->Description->Code->Value . ']';
					}
					$data2 = array(
						'issue' => $issue_post,
						'issue_date_active' => (string) $issue->DateTime->ExactDateTime,
						'issue_date_inactive' => '',
						'issue_provider' => $this->session->userdata('displayname'),
						'pid' => $pid
					);	
					$this->chart_model->addIssue($data2);
					$this->audit_model->add();
				}
			}
		}
		if (isset($xml->Body->Medications)) {
			foreach ($xml->Body->Medications->Medication as $rx) {
				if ((string) $rx->Status->Text == 'Active') {
					$data3 = array(
						'rxl_medication' => (string) $rx->Product->ProductName->Text,
						'rxl_instructions' => (string) $rx->Directions->Direction->Dose->Value,
						'rxl_date_active' => (string) $rx->DateTime->ExactDateTime,
						'rxl_date_prescribed' => '',
						'rxl_date_inactive' => '',
						'rxl_date_old' => '',
						'rxl_provider' => $this->session->userdata('displayname'),
						'pid' => $pid
					);	
					$this->chart_model->addMedication($data3);
					$this->audit_model->add();
				}
			}
		}
		if (isset($xml->Body->Immunizations)) {
			foreach ($xml->Body->Immunizations->Immunization as $imm) {
				if (strpos((string) $imm->Product->ProductName->Text, '#')) {
					$items = explode('#',(string) $imm->Product->ProductName->Text);
					$imm_immunization = rtrim($items[0]);
					$imm_sequence = $items[1];
				} else {
					$imm_immunization = (string) $imm->Product->ProductName->Text;
					$imm_sequence = '';
				}
				$data4 = array(
					'imm_immunization' => $imm_immunization,
					'imm_date' => (string) $imm->DateTime->ExactDateTime,
					'imm_sequence' => $imm_sequence,
					'imm_elsewhere' => 'Yes',
					'imm_vis' => '',
					'pid' => $pid,
					'eid' => ''
				);
				$this->chart_model->addImmunization($data4);
				$this->audit_model->add();
			}
		}
		if (isset($xml->Body->Alerts)) {
			foreach ($xml->Body->Alerts->Alert as $alert) {
				if ((string) $alert->Status->Text == 'Current') {
					$data5 = array(
						'alert' => (string) $alert->Type->Text,
						'alert_description' => (string) $alert->Description->Text,
						'alert_date_active' => (string) $alert->DateTime->ExactDateTime,
						'alert_date_complete' => '',
						'alert_reason_not_complete' => '',
						'alert_provider' => $this->session->userdata('displayname'),
						'orders_id' => '',
						'pid' => $pid
					);	
					$this->chart_model->addAlert($data5);
					$this->audit_model->add();
				}
			}
		}
		echo 'Continuity of Care Record Imported!';
	}
	
	function print_ccr()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			redirect('start');
		}
		ini_set('memory_limit','196M');
		$user_id = $this->session->userdata('user_id');
		$row = $this->demographics_model->get($pid)->row_array();
		$header_text = $row['lastname'] . ', ' . $row['firstname'] . '(DOB: ' . date('m/d/Y', human_to_unix($row['DOB'])) . ', Gender: ' . ucfirst($this->session->userdata('gender')) . ', ID: ' . $pid . ')';
		$header = strtoupper($header_text);
		$footer = '<div style="border-top: 1px solid #000000; text-align: center; padding-top: 3mm; font-size: 8px;">Page {PAGENO} of {nb}</div>';
		$footer .= '<p style="text-align:center; font-size: 8px;">';
		$footer .= "CONFIDENTIALITY NOTICE: The information contained in this facsimile transmission is intended for the recipient named above. If you are not the intended recipient or the intended recipient's agent, you are hereby notified that dissemination, copying, or distribution of the information contained in the transmission is prohibited. If you are not the intended recipient, please notify us immediately by telephone and return the documents to us by mail. Thank you.";
		$footer .= '</p><p style="text-align:center; font-size: 8px;">This document was generated by NOSH ChartingSystem</p>';
		$html = $this->page_intro('Continuity of Care Record');
		$html .= $this->page_ccr($pid);
		$file_path = "/var/www/nosh/ccr_" . now() . "_" . $user_id . ".pdf";
		$this->load->library('mpdf');
		$this->mpdf=new mpdf('','Letter',0,'',16,16,16,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->SetHTMLHeader($header,'O');
		$this->mpdf->SetHTMLHeader($header,'E');
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Document Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
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
	
	function ccda($hippa_id, $type)
	{
		$this->load->helper('download');
		$pid = $this->session->userdata('pid');
		if ($type == 'queue') {
			$query1 = $this->db->query("SELECT * FROM hippa JOIN encounters ON hippa.eid=encounters.eid WHERE hippa.other_hippa_id=$hippa_id AND hippa.eid IS NOT NULL ORDER BY encounters.encounter_DOS DESC");
		}
		if ($type == 'all') {
			$this->db->where('pid', $pid);
			$this->db->where('encounter_signed', 'Yes');
			$this->db->where('addendum', 'n');
			$this->db->order_by('encounter_DOS', 'desc');
			$query1 = $this->db->get('encounters');
		}
		if ($type == '1year') {
			$end = now();
			$start = $end - 31556926;
			$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND UNIX_TIMESTAMP(encounter_DOS)>=$start AND UNIX_TIMESTAMP(encounter_DOS)<=$end AND encounter_signed='Yes' AND addendum='n' ORDER BY encounter_DOS DESC");
		}
		$ccda = '';
		$ccda_name = now() . '_ccda.xml';
		if ($query1->num_rows() > 0) {
			foreach ($query1->result_array() as $row1) {
				$ccda .= $this->generate_ccda($hippa_id, $row1['eid']);
			}
		}
		force_download($ccda_name, $ccda);
	}
	
	function generate_ccda($hippa_id, $eid)
	{
		$this->load->library('APIBaseClass');
		$this->load->library('rxNormApi');
		$ccda = file_get_contents('/var/www/nosh/ccda.xml');
		$practice_info = $this->practiceinfo_model->get()->row_array();
		$ccda = str_replace('?practice_name?', $practice_info['practice_name'], $ccda);
		$date_format = "YmdHisO";
		$ccda = str_replace('?effectiveTime?', date($date_format), $ccda);
		$ccda_name = now() . '_ccda.xml';
		$pid = $this->session->userdata('pid');
		$ccda = str_replace('?pid?', $pid, $ccda);
		$demographics = $this->demographics_model->get($pid)->row_array();
		$ccda = str_replace('?ss?', $demographics['ss'], $ccda);
		$ccda = str_replace('?street_address1?', $demographics['address'], $ccda);
		$ccda = str_replace('?city?', $demographics['city'], $ccda);
		$ccda = str_replace('?state?', $demographics['state'], $ccda);
		$ccda = str_replace('?zip?', $demographics['zip'], $ccda);
		$ccda = str_replace('?phone_home?', $demographics['phone_home'], $ccda);
		$ccda = str_replace('?firstname?', $demographics['firstname'], $ccda);
		$ccda = str_replace('?lastname?', $demographics['lastname'], $ccda);
		if ($demographics['sex'] == 'f') {
			$gender = 'F';
			$gender_full = 'Female';
		} else {
			$gender = 'M';
			$gender_full = 'Male';
		}
		$ccda = str_replace('?gender?', $gender, $ccda);
		$ccda = str_replace('?gender_full?', $gender_full, $ccda);
		$ccda = str_replace('?dob?', date('Ymd', human_to_unix($demographics['DOB'])), $ccda);
		if ($demographics['marital_status'] == 'Annulled') {
			$marital_code = "N";
		}
		if ($demographics['marital_status'] == 'Common law') {
			$marital_code = "C";
		}
		if ($demographics['marital_status'] == 'Divorced') {
			$marital_code = "D";
		}
		if ($demographics['marital_status'] == 'Domestic partner') {
			$marital_code = "P";
		}
		if ($demographics['marital_status'] == 'Interlocutory') {
			$marital_code = "I";
		}
		if ($demographics['marital_status'] == 'Legally Separated') {
			$marital_code = "E";
		}
		if ($demographics['marital_status'] == 'Living together') {
			$marital_code = "G";
		}
		if ($demographics['marital_status'] == 'Married') {
			$marital_code = "M";
		}
		if ($demographics['marital_status'] == 'Other') {
			$marital_code = "O";
		}
		if ($demographics['marital_status'] == 'Registered domestic partner') {
			$marital_code = "R";
		}
		if ($demographics['marital_status'] == 'Separated') {
			$marital_code = "A";
		}
		if ($demographics['marital_status'] == 'Single') {
			$marital_code = "S";
		}
		if ($demographics['marital_status'] == 'Unknown') {
			$marital_code = "U";
		}
		if ($demographics['marital_status'] == 'Unmarried') {
			$marital_code = "B";
		}
		if ($demographics['marital_status'] == 'Unreported') {
			$marital_code = "T";
		}
		if ($demographics['marital_status'] == 'Widowed') {
			$marital_code = "O";
		}
		$ccda = str_replace('?marital_status?', $demographics['marital_status'], $ccda);
		$ccda = str_replace('?marital_code?', $marital_code, $ccda);
		$ccda = str_replace('?race?', $demographics['race'], $ccda);
		$ccda = str_replace('?race_code?', $demographics['race_code'], $ccda);
		$ccda = str_replace('?ethnicity?', $demographics['ethnicity'], $ccda);
		$ccda = str_replace('?ethnicity_code?', $demographics['ethnicity_code'], $ccda);
		$ccda = str_replace('?guardian_code?', $demographics['guardian_code'], $ccda);
		$ccda = str_replace('?guardian_relationship?', $demographics['guardian_relationship'], $ccda);
		$ccda = str_replace('?guardian_lastname?', $demographics['guardian_lastname'], $ccda);
		$ccda = str_replace('?guardian_firstname?', $demographics['guardian_firstname'], $ccda);
		$ccda = str_replace('?guardian_address?', $demographics['guardian_address'], $ccda);
		$ccda = str_replace('?guardian_city?', $demographics['guardian_city'], $ccda);
		$ccda = str_replace('?guardian_state?', $demographics['guardian_state'], $ccda);
		$ccda = str_replace('?guardian_zip?', $demographics['guardian_zip'], $ccda);
		$ccda = str_replace('?guardian_phone_home?', $demographics['guardian_phone_home'], $ccda);
		if ($practice_info['street_address2'] != '') {
			$practice_info['street_address1'] .= ', ' . $practice_info['street_address2'];
		}
		$ccda = str_replace('?practiceinfo_street_address?', $practice_info['street_address1'], $ccda);
		$ccda = str_replace('?practiceinfo_city?', $practice_info['city'], $ccda);
		$ccda = str_replace('?practiceinfo_state?', $practice_info['state'], $ccda);
		$ccda = str_replace('?practiceinfo_zip?', $practice_info['zip'], $ccda);
		$ccda = str_replace('?practiceinfo_phone?', $practice_info['phone'], $ccda);
		$user_id = $this->session->userdata('user_id');
		$this->db->where('id',$user_id);
		$user = $this->db->get('users')->row_array();
		$ccda = str_replace('?user_id?', $user['id'], $ccda);
		$ccda = str_replace('?user_lastname?', $user['lastname'], $ccda);
		$ccda = str_replace('?user_firstname?', $user['firstname'], $ccda);
		$ccda = str_replace('?user_title?', $user['title'], $ccda);
		$date_format1 = "Ymd";
		$ccda = str_replace('?effectiveTimeShort?', date($date_format1), $ccda);
		$this->db->where('hippa_id', $hippa_id);
		$hippa_info = $this->db->get('hippa')->row_array();
		$ccda = str_replace('?hippa_provider?', $hippa_info['hippa_provider'], $ccda);
		$ccda = str_replace('?lang_code?', $demographics['lang_code'], $ccda);
		$ccda = str_replace('?encounter_role?', $hippa_info['hippa_role'], $ccda);
		if ($hippa_info['hippa_role'] == "Primary Care Provider") {
			$hippa_role_code = "PP";
		}
		if ($hippa_info['hippa_role'] == "Consulting Provider") {
			$hippa_role_code = "CP";
		}
		if ($hippa_info['hippa_role'] == "Referring Provider") {
			$hippa_role_code = "RP";
		}
		$ccda = str_replace('?encounter_role_code?', $hippa_role_code, $ccda);
		$ccda = str_replace('?eid?', $eid, $ccda);
		$this->db->where('eid', $eid);
		$encounter_info = $this->db->get('encounters')->row_array();
		$this->db->where('id', $encounter_info['user_id']);
		$provider_info = $this->db->get('users')->row_array();
		$this->db->where('id', $encounter_info['user_id']);
		$provider_info1 = $this->db->get('providers')->row_array();
		$ccda = str_replace('?npi?', $provider_info1['npi'], $ccda);
		$ccda = str_replace('?provider_title?', $provider_info['title'], $ccda);
		$ccda = str_replace('?provider_firstname?', $provider_info['firstname'], $ccda);
		$ccda = str_replace('?provider_lastname?', $provider_info['lastname'], $ccda);
		$ccda = str_replace('?encounter_dos?', date('Ymd', human_to_unix($encounter_info['encounter_DOS'])), $ccda);
		$this->db->where('eid', $eid);
		$assessment_info = $this->db->get('assessment')->row_array();
		$ccda = str_replace('?icd9?', $assessment_info['assessment_icd1'], $ccda);
		$this->db->where('icd9', $assessment_info['assessment_icd1']);
		$assessment_info1 = $this->db->get('icd9')->row_array();
		$ccda = str_replace('?icd9_description?', $assessment_info1['icd9_description'], $ccda);
		$allergies_query = $this->db->query("SELECT * FROM allergies WHERE pid=$pid");
		$allergies_table = "";
		$allergies_file_final = "";
		if ($allergies_query->num_rows() > 0) {
			$i = 1;
			foreach ($allergies_query->result_array() as $allergies_row) {
				$allergies_table .= "<tr>";
				$allergies_table .= "<td>" . $allergies_row['allergies_med'] . "</td>";
				$allergies_table .= "<td><content ID='reaction" . $i . "'>" . $allergies_row['allergies_reaction'] . "</content></td>";
				$allergies_table .= "<td><content ID='severity" . $i . "'>" . $allergies_row['allergies_severity'] . "</content></td>";
				if ($allergies_row['allergies_date_inactive'] == '0000-00-00 00:00:00') {
					$allergies_table .= "<td>Active</td>";
					$allergies_status = "Active";
					$allergies_file = file_get_contents('/var/www/nosh/allergies_active.xml');
					$allergies_file = str_replace('?allergies_date_active?', date('Ymd', human_to_unix($allergies_row['allergies_date_active'])), $allergies_file);
				} else {
					$allergies_table .= "<td>Inactive</td>";
					$allergies_status = "Inactive";
					$allergies_file = file_get_contents('/var/www/nosh/allergies_inactive.xml');
					$allergies_file = str_replace('?allergies_date_active?', date('Ymd', human_to_unix($allergies_row['allergies_date_active'])), $allergies_file);
					$allergies_file = str_replace('?allergies_date_inactive?', date('Ymd', human_to_unix($allergies_row['allergies_date_inactive'])), $allergies_file);
				}
				$allergies_table .= "</tr>";
				$reaction_number = "#reaction" . $i;
				$severity_number = "#severity" . $i;
				$allergies_file = str_replace('?reaction_number?', $reaction_number, $allergies_file);
				$allergies_file = str_replace('?severity_number?', $severity_number, $allergies_file);
				$allergies_file = str_replace('?allergies_med?', $allergies_row['allergies_med'], $allergies_file);
				$allergies_file = str_replace('?allergies_status?', $allergies_status, $allergies_file);
				$allergies_file = str_replace('?allergies_reaction?', $allergies_row['allergies_reaction'], $allergies_file);
				$allergy_random_id1 = $this->gen_uuid();
				$allergy_random_id2 = $this->gen_uuid();
				$allergy_random_id3 = $this->gen_uuid();
				$allergies_file = str_replace('?allergy_random_id1?', $allergy_random_id1, $allergies_file);
				$allergies_file = str_replace('?allergy_random_id2?', $allergy_random_id2, $allergies_file);
				$allergies_file = str_replace('?allergy_random_id3?', $allergy_random_id3, $allergies_file);
				$allergies_file_final .= $allergies_file;
				$i++;
			}
		}
		$ccda = str_replace('?allergies_table?', $allergies_table, $ccda);
		$ccda = str_replace('?allergies_file?', $allergies_file_final, $ccda);
		$ccda = str_replace('?encounter_cc?', $encounter_info['encounter_cc'], $ccda);
		$ccda = str_replace('?encounter_number?', $eid, $ccda);
		$ccda = str_replace('?encounter_provider?', $encounter_info['encounter_provider'], $ccda);
		$ccda = str_replace('?encounter_dos1?', date('m-d-Y', human_to_unix($encounter_info['encounter_DOS'])), $ccda);
		$encounter_random_id1 = $this->gen_uuid();
		$encounter_random_id2 = $this->gen_uuid();
		$encounter_random_id3 = $this->gen_uuid();
		$ccda = str_replace('?encounter_random_id1?', $encounter_random_id1, $ccda);
		$ccda = str_replace('?encounter_random_id2?', $encounter_random_id2, $ccda);
		$ccda = str_replace('?encounter_random_id3?', $encounter_random_id3, $ccda);
		$dx_array[] = $assessment_info['assessment_icd1'];
		if ($assessment_info['assessment_icd2'] != "") {
			$dx_array[] = $assessment_info['assessment_icd2'];
		}
		if ($assessment_info['assessment_icd3'] != "") {
			$dx_array[] = $assessment_info['assessment_icd3'];
		}
		if ($assessment_info['assessment_icd4'] != "") {
			$dx_array[] = $assessment_info['assessment_icd4'];
		}
		if ($assessment_info['assessment_icd5'] != "") {
			$dx_array[] = $assessment_info['assessment_icd5'];
		}
		if ($assessment_info['assessment_icd6'] != "") {
			$dx_array[] = $assessment_info['assessment_icd6'];
		}
		if ($assessment_info['assessment_icd7'] != "") {
			$dx_array[] = $assessment_info['assessment_icd7'];
		}
		if ($assessment_info['assessment_icd8'] != "") {
			$dx_array[] = $assessment_info['assessment_icd8'];
		}
		$encounter_diagnosis = '';
		foreach ($dx_array as $dx_item) {
			$dx_file = file_get_contents('/var/www/nosh/encounter_diagnosis.xml');
			$dx_random_id1 = $this->gen_uuid();
			$dx_random_id2 = $this->gen_uuid();
			$dx_file = str_replace('?dx_random_id1?', $dx_random_id1, $dx_file);
			$dx_file = str_replace('?dx_random_id2?', $dx_random_id2, $dx_file);
			$dx_file = str_replace('?icd9?', $dx_item, $dx_file);
			$dx_file = str_replace('?encounter_dos?', date('Ymd', human_to_unix($encounter_info['encounter_DOS'])), $dx_file);
			$this->db->where('icd9', $dx_item);
			$dx_info = $this->db->get('icd9')->row_array();
			$dx_file = str_replace('?icd9_description?', $dx_info['icd9_description'], $dx_file);
			$encounter_diagnosis .= $dx_file;
		}
		$ccda = str_replace('?encounter_diagnosis?', $encounter_diagnosis, $ccda);
		$imm_query = $this->db->query("SELECT * FROM immunizations WHERE pid=$pid ORDER BY imm_immunization ASC, imm_sequence ASC");
		$imm_table = "";
		$imm_file_final = "";
		if ($imm_query->num_rows() > 0) {
			$j = 1;
			foreach ($imm_query->result_array() as $imm_row) {
				$imm_table .= "<tr>";
				$imm_table .= "<td><content ID='immun" . $j . "'>" . $imm_row['imm_immunization'] . "</content></td>";
				$imm_table .= "<td>" . date('m-d-Y', human_to_unix($imm_row['imm_date'])) . "</td>";
				$imm_table .= "<td>Completed</td>";
				$imm_table .= "</tr>";
				$imm_file = file_get_contents('/var/www/nosh/immunizations.xml');
				$immun_number = "#immun" . $j;
				$imm_file = str_replace('?immun_number?', $immun_number, $imm_file);
				$imm_file = str_replace('?imm_date?', date('Ymd', human_to_unix($imm_row['imm_date'])), $imm_file);
				if ($imm_row['imm_route'] == "intramuscularly") {
					$imm_code = "C28161";
					$imm_code_description = "Intramuscular Route of Administration";
				}
				if ($imm_row['imm_route'] == "subcutaneously") {
					$imm_code = "C38299";
					$imm_code_description = "Subcutaneous Route of Administration";
				}
				if ($imm_row['imm_route'] == "intravenously") {
					$imm_code = "C38273";
					$imm_code_description = "Intravascular Route of Administration";
				}
				if ($imm_row['imm_route'] == "by mouth") {
					$imm_code = "C38289";
					$imm_code_description = "Oropharyngeal Route of Administration";
				}
				$imm_file = str_replace('?imm_code?', $imm_code, $imm_file);
				$imm_file = str_replace('?imm_code_description?', $imm_code_description, $imm_file);
				$imm_file = str_replace('?imm_dosage?', $imm_row['imm_dosage'], $imm_file);
				$imm_file = str_replace('?imm_dosage_unit?', $imm_row['imm_dosage_unit'], $imm_file);
				$imm_file = str_replace('?imm_cvxcode?', $imm_row['imm_cvxcode'], $imm_file);
				$imm_random_id1 = $this->gen_uuid();
				$imm_file = str_replace('?imm_random_id1?', $imm_random_id1, $imm_file);
				$this->db->where('cvx_code', $imm_row['imm_cvxcode']);
				$cvx = $this->db->get('cvx')->row_array();
				$imm_file = str_replace('?vaccine_name?', $cvx['vaccine_name'], $imm_file);
				$imm_file = str_replace('?imm_manufacturer?', $imm_row['imm_manufacturer'], $imm_file);
				$imm_file_final .= $imm_file;
				$j++;
			}
		}
		$ccda = str_replace('?imm_table?', $imm_table, $ccda);
		$ccda = str_replace('?imm_file?', $imm_file_final, $ccda);
		$med_query = $this->db->query("SELECT * FROM rx_list WHERE pid=$pid AND rxl_date_inactive='0000-00-00 00:00:00' AND rxl_date_old='0000-00-00 00:00:00'");
		$med_table = "";
		$med_file_final = "";
		if ($med_query->num_rows() > 0) {
			$k = 1;
			foreach ($med_query->result_array() as $med_row) {
				$med_table .= "<tr>";
				$med_table .= "<td><content ID='med" . $k . "'>" . $med_row['rxl_medication'] . ' ' . $med_row['rxl_dosage'] . ' ' . $med_row['rxl_dosage_unit'] . "</content></td>";
				if ($med_row['rxl_sig'] == '') {
					$instructions = $med_row['rxl_instructions'];
				} else {
					$instructions = $med_row['rxl_sig'] . ' ' . $med_row['rxl_route'] . ' ' . $med_row['rxl_frequency'];
				}
				$med_table .= "<td>" . $instructions . "</td>";
				$med_table .= "<td>" . date('m-d-Y', human_to_unix($med_row['rxl_date_active'])) . "</td>";
				$med_table .= "<td>Active</td>";
				$med_table .= "<td>" . $med_row['rxl_reason'] . "</td>";
				$med_table .= "</tr>";
				$med_file = file_get_contents('/var/www/nosh/medications.xml');
				$med_number = "#med" . $k;
				$med_random_id1 = $this->gen_uuid();
				$med_random_id2 = $this->gen_uuid();
				$med_file = str_replace('?med_random_id1?', $med_random_id1, $med_file);
				$med_file = str_replace('?med_random_id2?', $med_random_id2, $med_file);
				$med_file = str_replace('?med_number?', $med_number, $med_file);
				$med_file = str_replace('?med_date_active?', date('Ymd', human_to_unix($med_row['rxl_date_active'])), $med_file);
				if ($med_row['rxl_route'] == "by mouth") {
					$med_code = "C38289";
					$med_code_description = "Oropharyngeal Route of Administration";
				}
				if ($med_row['rxl_route'] == "per rectum") {
					$med_code = "C38295";
					$med_code_description = "Rectal Route of Administration";
				}
				if ($med_row['rxl_route'] == "subcutaneously") {
					$med_code = "C38299";
					$med_code_description = "Subcutaneous Route of Administration";
				}
				if ($med_row['rxl_route'] == "intravenously") {
					$med_code = "C38273";
					$med_code_description = "Intravascular Route of Administration";
				}
				if ($med_row['rxl_route'] == "intramuscularly") {
					$med_code = "C28161";
					$med_code_description = "Intramuscular Route of Administration";
				}
				$med_file = str_replace('?med_code?', $med_code, $med_file);
				$med_file = str_replace('?med_code_description?', $med_code_description, $med_file);
				$med_file = str_replace('?med_dosage?', $med_row['rxl_dosage'], $med_file);
				$med_file = str_replace('?med_dosage_unit?', $med_row['rxl_dosage_unit'], $med_file);
				$this->rxnormapi = new rxNormApi();
				$this->rxnormapi->output_type = 'json';
				$rxnorm = json_decode($this->rxnormapi->findRxcuiById("NDC", $med_row['rxl_ndcid']), true);
				$rxnorm1 = json_decode($this->rxnormapi->getRxConceptProperties($rxnorm['idGroup']['rxnormId'][0]), true);
				$med_file = str_replace('?med_rxnorm_code?', $rxnorm['idGroup']['rxnormId'][0], $med_file);
				$med_file = str_replace('?med_name?', $rxnorm1['properties']['name'], $med_file);
				$med_file_final .= $med_file;
				$k++;
			}
		}
		$ccda = str_replace('?med_table?', $med_table, $ccda);
		$ccda = str_replace('?med_file?', $med_file_final, $ccda);
		$this->db->where('eid', $eid);
		$orders_query = $this->db->get('orders');
		$orders_table = "";
		$orders_file_final = "";
		if ($orders_query->num_rows() > 0) {
			foreach ($orders_query->result_array() as $orders_row) {
				if ($orders_row['orders_labs'] != '') {
					$orders_labs_array = explode("\n",$orders_row['orders_labs']);
					$n1 = 1;
					foreach ($orders_labs_array as $orders_labs_row) {
						$orders_table .= "<tr>";
						$orders_table .= "<td><content ID='orders_labs_" . $n1 . "'>" . $orders_labs_row . "</td>";
						$orders_table .= "<td>" . date('m-d-Y', human_to_unix($orders_row['orders_date'])) . "</td>";
						$orders_table .= "</tr>";
						$orders_file_final .= $this->get_snomed_code($orders_labs_row, $orders_row['orders_date'], '#orders_lab_' . $n1);
						$n1++;
					}
				}
				if ($orders_row['orders_radiology'] != '') {
					$orders_rad_array = explode("\n",$orders_row['orders_radiology']);
					$n2 = 1;
					foreach ($orders_rad_array as $orders_rad_row) {
						$orders_table .= "<tr>";
						$orders_table .= "<td><content ID='orders_rad_" . $n2 . "'>" . $orders_rad_row . "</td>";
						$orders_table .= "<td>" . date('m-d-Y', human_to_unix($orders_row['orders_date'])) . "</td>";
						$orders_table .= "</tr>";
						$orders_file_final .= $this->get_snomed_code($orders_rad_row, $orders_row['orders_date'], '#orders_rad_' . $n2);
						$n2++;
					}
				}
				if ($orders_row['orders_cp'] != '') {
					$orders_cp_array = explode("\n",$orders_row['orders_cp']);
					$n3 = 1;
					foreach ($orders_cp_array as $orders_cp_row) {
						$orders_table .= "<tr>";
						$orders_table .= "<td><content ID='orders_cp_" . $n3 . "'>" . $orders_cp_row . "</td>";
						$orders_table .= "<td>" . date('m-d-Y', human_to_unix($orders_row['orders_date'])) . "</td>";
						$orders_table .= "</tr>";
						$orders_file_final .= $this->get_snomed_code($orders_cp_row, $orders_row['orders_date'], '#orders_cp_' . $n3);
						$n3++;
					}
				}
				if ($orders_row['orders_referrals'] != '') {
					$referral_orders = explode("\nRequested action:\n",$orders_row['orders_referrals']);
					if (count($referral_orders) > 1) {
						$orders_ref_array = explode("\n",$referral_orders[0]);
						$n4 = 1;
						foreach ($orders_ref_array as $orders_ref_row) {
							$orders_table .= "<tr>";
							$orders_table .= "<td><content ID='orders_ref_" . $n4 . "'>" . $orders_ref_row . "</td>";
							$orders_table .= "<td>" . date('m-d-Y', human_to_unix($orders_row['orders_date'])) . "</td>";
							$orders_table .= "</tr>";
							$orders_file_final .= $this->get_snomed_code($orders_ref_row, $orders_row['orders_date'], '#orders_ref_' . $n4);
							$n4++;
						}
					}
				}
			}
		}
		$ccda = str_replace('?orders_table?', $orders_table, $ccda);
		$ccda = str_replace('?orders_file?', $orders_file_final, $ccda);
		$issues_query = $this->db->query("SELECT * FROM issues WHERE pid=$pid");
		$issues_table = "";
		$issues_file_final = "";
		if ($issues_query->num_rows() > 0) {
			$l = 1;
			foreach ($issues_query->result_array() as $issues_row) {
				$issues_table .= "<list listType='ordered'>";
				$issues_array = explode(' [', $issues_row['issue']);
				$issue_code = str_replace("]", "", $issues_array[1]);
				$issue_code_description = $issues_array[0];
				if ($issues_row['issue_date_inactive'] != '0000-00-00 00:00:00') {
					$issues_table .= "<item><content ID='problem" . $l . "'>" . $issues_row['issue'] . ": Status - Resolved</content></item>";
					$issues_status = "Resolved";
					$issues_code = "413322009";
					$issues_file = file_get_contents('/var/www/nosh/issues_inactive.xml');
					$issues_file = str_replace('?issue_date_inactive?', date('Ymd', human_to_unix($issues_row['issue_date_inactive'])), $issues_file);
				} else {
					$issues_table .= "<item><content ID='problem" . $l . "'>" . $issues_row['issue'] . ": Status - Active</content></item>";
					$issues_status = "Active";
					$issues_code = "55561003";
					$issues_file = file_get_contents('/var/www/nosh/issues_active.xml');
				}
				$issues_table .= "</list>";
				$issues_file = str_replace('?issue_date_active?', date('Ymd', human_to_unix($issues_row['issue_date_active'])), $issues_file);
				$issues_file = str_replace('?issue_code?', $issue_code, $issues_file);
				$issues_file = str_replace('?issue_code_description?', $issue_code_description, $issues_file);
				$issues_number = "#problem" . $l;
				$issues_random_id1 = $this->gen_uuid();
				$issues_file = str_replace('?issues_random_id1?', $issues_random_id1, $issues_file);
				$issues_file = str_replace('?issues_number?', $issues_number, $issues_file);
				$issues_file = str_replace('?issues_code?', $issues_code, $issues_file);
				$issues_file = str_replace('?issues_status?', $issues_status, $issues_file);
				$issues_file_final .= $issues_file;
				$l++;
			}
		}
		$ccda = str_replace('?issues_table?', $issues_table, $ccda);
		$ccda = str_replace('?issues_file?', $issues_file_final, $ccda);
		$this->db->where('eid', $eid);
		$proc_query = $this->db->get('procedure');
		$proc_table = "";
		$proc_file_final = "";
		if ($proc_query->num_rows() > 0) {
			$m = 1;
			foreach ($proc_query->result_array() as $proc_row) {
				$proc_table .= "<tr>";
				$proc_table .= "<td><content ID='proc" . $m . "'>" . $proc_row['proc_type'] . "</td>";
				$proc_table .= "<td>" . date('m-d-Y', human_to_unix($proc_row['proc_date'])) . "</td>";
				$proc_table .= "</tr>";
				$proc_file = file_get_contents('/var/www/nosh/proc.xml');
				$proc_file = str_replace('?proc_date?', date('Ymd', human_to_unix($proc_row['proc_date'])), $proc_file);
				$proc_file = str_replace('?proc_type?', $proc_row['proc_type'], $proc_file);
				$proc_file = str_replace('?proc_cpt?', $proc_row['proc_cpt'], $proc_file);
				$proc_file = str_replace('?practiceinfo_street_address?', $practice_info['street_address1'], $proc_file);
				$proc_file = str_replace('?practiceinfo_city?', $practice_info['city'], $proc_file);
				$proc_file = str_replace('?practiceinfo_state?', $practice_info['state'], $proc_file);
				$proc_file = str_replace('?practiceinfo_zip?', $practice_info['zip'], $proc_file);
				$proc_file = str_replace('?practiceinfo_phone?', $practice_info['phone'], $proc_file);
				$proc_file = str_replace('?practice_name?', $practice_info['practice_name'], $proc_file);
				$proc_number = "#proc" . $m;
				$proc_random_id1 = $this->gen_uuid();
				$proc_file = str_replace('?proc_random_id1?', $proc_random_id1, $proc_file);
				$proc_file_final .= $proc_file;
				$m++;
			}
		}
		$ccda = str_replace('?proc_table?', $proc_table, $ccda);
		$ccda = str_replace('?proc_file?', $proc_file_final, $ccda);
		$this->db->where('eid', $eid);
		$other_history_query = $this->db->get('other_history');
		$other_history_table = "";
		$other_history_file = "";
		if ($other_history_query->num_rows() > 0) {
			$other_history_row = $other_history_query->row_array();
			if ($other_history_row['oh_tobacco'] != '') {
				$other_history_table .= "<td>Smoking Status</td>";
				$other_history_table .= "<td><content ID='other_history" . $o . "'>" . $other_history_row['oh_tobacco'] . "</td>";
				$other_history_table .= "<td>" . date('m-d-Y', human_to_unix($other_history_row['oh_date'])) . "</td>";
				$other_history_table .= "</tr>";
				$other_history_table .= "<tr>";
				if ($demographics['tobacco'] == 'yes') {
					$other_history_code = "77176002";
					$other_history_description = "Smoker";
				} else {
					$other_history_code = "8392000";
					$other_history_description = "Non-Smoker";
				}
				$other_history_file_ = file_get_contents('/var/www/nosh/social_history.xml');
				$other_history_file = str_replace('?other_history_code?', $other_history_code, $other_history_file);
				$other_history_file = str_replace('?other_history_description?', $other_history_description, $other_history_file);
				$other_history_file = str_replace('?other_history_date?', date('Ymd', human_to_unix($other_history_row['oh_date'])), $other_history_file);
			}
		}
		$ccda = str_replace('?other_history_table?', $other_history_table, $ccda);
		$ccda = str_replace('?other_history_file?', $other_history_file, $ccda);
		$this->db->where('eid', $eid);
		$vitals_query = $this->db->get('vitals');
		$vitals_table = "";
		$vitals_file_final = "";
		if ($vitals_query->num_rows() > 0) {
			$vitals_row = $vitals_query->row_array();
			$vitals_table .= '<thead><tr><th align="right">Date / Time: </th><th>' . date('m-d-Y h:i A', human_to_unix($vitals_row['vitals_date'])) . '</th></tr></thead><tbody>';
			$vitals_file_final .= '               <entry typeCode="DRIV"><organizer classCode="CLUSTER" moodCode="EVN"><templateId root="2.16.840.1.113883.10.20.22.4.26"/><id root="';
			$vitals_file_final .= $this->gen_uuid() . '"/><code code="46680005" codeSystem="2.16.840.1.113883.6.96" codeSystemName="SNOMED-CT" displayName="Vital signs"/><statusCode code="completed"/><effectiveTime value="';
			$vitals_file_final .= date('Ymd', human_to_unix($vitals_row['vitals_date'])) . '"/>';
			if ($vitals_row['height'] != '') {
				$vitals_table .= '<tr><th align="left">Height</th><td><content ID="vit_height">';
				$vitals_table .= $vitals_row['height'] . ' ' . $practice_info['height_unit'];
				$vitals_table .= '</content></td></tr>';
				$vitals_code1 = "8302-2";
				$vitals_description1 = "Body height";
				$vitals_file_ = file_get_contents('/var/www/nosh/vitals.xml');
				$vitals_file = str_replace('?vitals_code?', $vitals_code1, $vitals_file);
				$vitals_file = str_replace('?vitals_description?', $vitals_description1, $vitals_file);
				$vitals_file = str_replace('?vitals_date?', date('Ymd', human_to_unix($vitals_row['vitals_date'])), $vitals_file);
				$vitals_file = str_replace('?vitals_id?', '#vit_height', $vitals_file);
				$vitals_file = str_replace('?vitals_value?', $vitals_row['height'], $vitals_file);
				$vitals_file = str_replace('?vitals_unit?', $practice_info['height_unit'], $vitals_file);
				$vitals_random_id1 = $this->gen_uuid();
				$vitals_file = str_replace('?vitals_random_id1?', $vitals_random_id1, $vitals_file);
			}
			if ($vitals_row['weight'] != '') {
				$vitals_table .= '<tr><th align="left">Weight</th><td><content ID="vit_weight">';
				$vitals_table .= $vitals_row['weight'] . ' ' . $practice_info['weight_unit'];
				$vitals_table .= '</content></td></tr>';
				$vitals_code2 = "3141-9";
				$vitals_description2 = "Body weight Measured";
				$vitals_file_ = file_get_contents('/var/www/nosh/vitals.xml');
				$vitals_file = str_replace('?vitals_code?', $vitals_code2, $vitals_file);
				$vitals_file = str_replace('?vitals_description?', $vitals_description2, $vitals_file);
				$vitals_file = str_replace('?vitals_date?', date('Ymd', human_to_unix($vitals_row['vitals_date'])), $vitals_file);
				$vitals_file = str_replace('?vitals_id?', '#vit_weight', $vitals_file);
				$vitals_file = str_replace('?vitals_value?', $vitals_row['weight'], $vitals_file);
				$vitals_file = str_replace('?vitals_unit?', $practice_info['weight_unit'], $vitals_file);
				$vitals_random_id2 = $this->gen_uuid();
				$vitals_file = str_replace('?vitals_random_id1?', $vitals_random_id2, $vitals_file);
			}
			if ($vitals_row['bp_systolic'] != '' && $vitals_row['bp_diastolic']) {
				$vitals_table .= '<tr><th align="left">Blood Pressure</th><td><content ID="vit_bp">';
				$vitals_table .= $vitals_row['bp_systolic'] . '/' . $vitals_row['bp_diastolic'] . ' mmHg';
				$vitals_table .= '</content></td></tr>';
				$vitals_code3 = "8480-6";
				$vitals_description3 = "Intravascular Systolic";
				$vitals_file_ = file_get_contents('/var/www/nosh/vitals.xml');
				$vitals_file = str_replace('?vitals_code?', $vitals_code3, $vitals_file);
				$vitals_file = str_replace('?vitals_description?', $vitals_description3, $vitals_file);
				$vitals_file = str_replace('?vitals_date?', date('Ymd', human_to_unix($vitals_row['vitals_date'])), $vitals_file);
				$vitals_file = str_replace('?vitals_id?', '#vit_bp', $vitals_file);
				$vitals_file = str_replace('?vitals_value?', $vitals_row['bp_systolic'], $vitals_file);
				$vitals_file = str_replace('?vitals_unit?', "mmHg", $vitals_file);
				$vitals_random_id3 = $this->gen_uuid();
				$vitals_file = str_replace('?vitals_random_id1?', $vitals_random_id3, $vitals_file);
			} 
			$vitals_table .= '</tbody>';
			$vitals_file_final .= '                  </organizer>';
			$vitals_file_final .= '               </entry>';
		}
		$ccda = str_replace('?vitals_table?', $vitals_table, $ccda);
		$ccda = str_replace('?vitals_file?', $vitals_file_final, $ccda);
		return $ccda;
	}
	
	function gen_uuid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}
	
	function get_snomed_code($item, $date, $id)
	{
		$pos = strpos($item, ", SNOMED : ");
		$pos1 = strpos($item, ", CPT: ");
		if ($pos !== FALSE) {
			$items = explode(", SNOMED: ", $item);
			$this->db->where('conceptid', $items[1]);
			$this->db->where('active', '1');
			$term_row = $this->db->get('curr_description_f')->row_array();
			$orders_file1 = file_get_contents('/var/www/nosh/orders.xml');
			$orders_file1 = str_replace('?orders_date?', date('Ymd', human_to_unix($date)), $orders_file1);
			$orders_file1 = str_replace('?orders_code?', $items[1], $orders_file1);
			$orders_file1 = str_replace('?orders_code_description?', $term_row['term'], $orders_file1);
			$orders_random_id1 = $this->gen_uuid();
			$orders_file = str_replace('?orders_random_id1?', $orders_random_id1, $orders_file1);
		} elseif ($pos1 !== FALSE) {
			$items = explode(", CPT: ", $item);
			$orders_file2 = file_get_contents('/var/www/nosh/orders_cpt.xml');
			$orders_file2 = str_replace('?orders_date?', date('Ymd', human_to_unix($date)), $orders_file2);
			$orders_file2 = str_replace('?orders_code?', $items[1], $orders_file2);
			$orders_file2 = str_replace('?orders_code_description?', $term_row['term'], $orders_file2);
			$orders_random_id2 = $this->gen_uuid();
			$orders_file = str_replace('?orders_random_id1?', $orders_random_id1, $orders_file2);
		} else {
			$orders_file3 = file_get_contents('/var/www/nosh/orders_generic.xml');
			$orders_file3 = str_replace('?orders_date?', date('Ymd', human_to_unix($date)), $orders_file3);
			$orders_file3 = str_replace('?orders_description?', $item, $orders_file3);
			$orders_file3 = str_replace('?orders_reference_id?', $id, $orders_file3);
			$orders_random_id3 = $this->gen_uuid();
			$orders_file = str_replace('?orders_random_id1?', $orders_random_id1, $orders_file3);
		}
		return $orders_file;
	}
	
	function rcopia($command)
	{
		$practice = $this->practiceinfo_model->get()->row_array();
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
	
	function print_vivacare($link)
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$this->load->library('domparser');
		$this->load->library('mpdf');
		$link = "http://fromyourdoctor.com/topic.do?t=" . $link;
		$html = $this->domparser->file_get_html($link);
		if (isset($html)) {
			$final_html = $this->page_intro('Patient Instructions');
			$title = $html->find('h4',0);
			$final_html .= '<div style="width:700px">';
			$final_html .= '<h2 style="text-align: center;">';
			$final_html .= $title->innertext;
			$final_html .= '</h2>';
			$div = $html->find('[id=usercontent]',0);
			$final_html .= $div->outertext;
			$p1 = $div->nextSibling();
			$final_html .= $p1->outertext;
			$p2 = $p1->nextSibling();
			$final_html .= $p2->outertext;
			$p3 = $p2->nextSibling();
			$final_html .= $p3->outertext;
			$p4 = $p3->nextSibling();
			$final_html .= $p4->outertext;
			$p5 = $p4->nextSibling();
			$final_html .= $p5->outertext;
			$div2 = $html->find('[id=additional-resources]',0);
			$final_html .= $div2->outertext;
			$final_html .= '</div></body></html>';
			$footer = '<div style="border-top: 1px solid #000000; text-align: center; padding-top: 3mm; font-size: 8px;">Page {PAGENO} of {nb}</div>';
			$footer .= '<p style="text-align:center; font-size: 8px;">';
			$footer .= "CONFIDENTIALITY NOTICE: The information contained in this facsimile transmission is intended for the recipient named above. If you are not the intended recipient or the intended recipient's agent, you are hereby notified that dissemination, copying, or distribution of the information contained in the transmission is prohibited. If you are not the intended recipient, please notify us immediately by telephone and return the documents to us by mail. Thank you.";
			$footer .= '</p><p style="text-align:center; font-size: 8px;">This document was generated by NOSH ChartingSystem</p>';
			$pid = $this->session->userdata('pid');
			$result = $this->practiceinfo_model->get()->row_array();
			$directory = $result['documents_dir'] . $pid;
			$file_path = $directory . '/instructions_' . now() . '.pdf';
			$this->mpdf=new mpdf('','Letter',0,'',26,26,26,26,9,6,'P');
			$this->mpdf->useOnlyCoreFonts = true;
			$this->mpdf->shrink_tables_to_fit=1;
			$this->mpdf->AddPage();
			$this->mpdf->SetHTMLFooter($footer,'O');
			$this->mpdf->SetHTMLFooter($footer,'E');
			$this->mpdf->WriteHTML($final_html);
			$this->mpdf->SetTitle('Letter Generated by NOSH ChartingSystem');
			$this->mpdf->debug = true;
			$this->mpdf->Output($file_path,'F');
			while(!file_exists($file_path)) {
				sleep(2);
			}
			$datestring = "%Y-%m-%d %H:%i:%s";
			$date1 = mdate($datestring, now());
			$desc = 'Instructions for ' . $this->session->userdata('ptname');
			$pages_data = array(
				'documents_url' => $file_path,
				'pid' => $pid,
				'documents_type' => 'Letters',
				'documents_desc' => $desc,
				'documents_from' => $this->session->userdata('displayname'),
				'documents_viewed' => $this->session->userdata('displayname'),
				'documents_date' => $date1
			);			
			$arr['message'] = "OK";
			$arr['id'] = $this->chart_model->addDocuments($pages_data);
			$this->audit_model->add();
		} else {
			$arr['message'] = "Unable to download instructions from Vivacare.  Try again later.";
		}
		echo json_encode($arr);
	}
	
	function get_ref_templates_list()
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
		$this->db->where('category', 'referral');
		$result = $this->db->get('templates')->result_array();
		$data['options'] = array();
		foreach ($result as $row) {
			$id = $row['template_id'];
			if ($row['template_name'] == 'Global Default') {
				if ($row['group'] == 'referral') {
					$name = 'Referral';
				}
				if ($row['group'] == 'consultation') {
					$name = 'Consultation';
				}
				if ($row['group'] == 'pt') {
					$name = 'Physical Therapy';
				}
				if ($row['group'] == 'massage') {
					$name = 'Massage Therapy';
				}
				if ($row['group'] == 'sleep_study') {
					$name = 'Sleep Study';
				}
			} else {
				$name = $row['template_name'];
			}
			$data['options'][$id] = $name;
		}
		echo json_encode($data);
	}
	
	function get_ref_template($id)
	{
		$this->db->where('template_id', $id);
		$row = $this->db->get('templates')->row_array();
		$data = unserialize($row['array']);
		echo json_encode($data);
	}
}
/* End of file: chartmenu.php */
/* Location: application/controllers/provider/chartmenu.php */
