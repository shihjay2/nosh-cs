<?php

class Start extends Application
{
	function Start()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->load->library('email');
		$this->load->model('practiceinfo_model');
		$this->load->model('users_model');
		$this->load->model('schedule_model');
		$this->load->model('encounters_model');
		$this->load->model('fax_model');
		$this->load->model('demographics_model');
		$this->load->model('audit_model');
		$this->load->model('chart_model');
	}
	
	function index($practicehandle='')
	{
		if ($practicehandle != '') {
			$this->db->where('practicehandle', $practicehandle);
			$practice_query = $this->db->get('practiceinfo');
			if ($practice_query->num_rows == 0) {
				$data_error = array(
					'practicehandle' => $practicehandle
				);
				$this->auth->view('login_error', $data_error);
			}
		}
		if(logged_in()) {
			$user_id = $this->session->userdata('user_id');
			$settingsInfo = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
			$practice_id = $this->session->userdata('practice_id');
			$data['practiceinfo'] = $settingsInfo->row();
			$this->session->set_userdata('practice_active', $data['practiceinfo']->active);
			$query = $this->users_model->get($user_id);
			$result = $query->row();
			$data['displayname'] = $result->displayname;
			$displayname = $result->displayname;
			$this->session->set_userdata('displayname', $data['displayname']);
			$fax_query = $this->db->query("SELECT * FROM received WHERE practice_id=$practice_id")->num_rows();
			if(user_group('provider')) {
				$data['number_messages'] = $this->db->query("SELECT * FROM messaging WHERE mailbox=$user_id")->num_rows();
				$data['number_documents'] = $this->db->query("SELECT * FROM scans WHERE practice_id=$practice_id")->num_rows() + $fax_query;
				$data['number_appts'] = $this->schedule_model->getNumberAppts($user_id);
				$from = $displayname . ' (' . $this->session->userdata('user_id') . ')';
				$query1 = $this->db->query("SELECT * FROM t_messages JOIN demographics ON t_messages.pid=demographics.pid WHERE t_messages.t_messages_from='$from' AND t_messages.t_messages_signed='No'");
				$query2 = $this->db->query("SELECT * FROM encounters JOIN demographics ON encounters.pid=demographics.pid WHERE encounters.encounter_provider='$displayname' AND encounters.encounter_signed='No'");
				$data['number_drafts'] = $query1->num_rows() + $query2->num_rows();
				$query3 = $this->db->query("SELECT * FROM alerts JOIN demographics ON alerts.pid=demographics.pid WHERE alerts.alert_provider='$user_id' AND alerts.alert_date_complete='0000-00-00 00:00:00' AND alerts.alert_reason_not_complete='' AND (alerts.alert='Laboratory results pending' OR alerts.alert='Radiology results pending' OR alerts.alert='Cardiopulmonary results pending' OR alerts.alert='Referral pending' OR alerts.alert='Reminder' OR alerts.alert='REMINDER')");
				$data['number_reminders'] = $query3->num_rows(); 
				$data['number_bills'] = $this->db->query("SELECT * FROM encounters WHERE bill_submitted='No' AND user_id=$user_id")->num_rows();
				$query7 = $this->db->query("SELECT * FROM tests WHERE pid IS NULL AND practice_id=$practice_id");
				$data['number_tests'] = $query7->num_rows();
				if($data['practiceinfo']->mtm_extension == 'y') {
					$mtm_users_array = explode(",", $data['practiceinfo']->mtm_alert_users);
					if (in_array($user_id, $mtm_users_array)) {
						$query6 = $this->db->query("SELECT * FROM alerts WHERE alert_date_complete='0000-00-00 00:00:00' AND alert_reason_not_complete='' AND alert='Medication Therapy Management' AND practice_id=$practice_id");
						$data['mtm_alerts'] = $query6->num_rows();
						$data['mtm_alerts_status'] = "y";
					} else {
						$data['mtm_alerts_status'] = "n";
					}
				} else {
					$data['mtm_alerts_status'] = "n";
				}
			}
			if(user_group('assistant')) {
				$data['number_messages'] = $this->db->query("SELECT * FROM messaging WHERE mailbox=$user_id")->num_rows();
				$data['number_documents'] = $this->db->query("SELECT * FROM scans WHERE practice_id=$practice_id")->num_rows() + $fax_query;
				$from = $displayname . ' (' . $this->session->userdata('user_id') . ')';
				$query4 = $this->db->query("SELECT * FROM t_messages JOIN demographics ON t_messages.pid=demographics.pid WHERE t_messages.t_messages_from='$from' AND t_messages.t_messages_signed='No'");
				$data['number_drafts'] = $query4->num_rows();
				$query5 = $this->db->query("SELECT * FROM alerts JOIN demographics ON alerts.pid=demographics.pid WHERE alerts.alert_provider='$user_id' AND alerts.alert_date_complete='0000-00-00 00:00:00' AND alerts.alert_reason_not_complete='' AND (alerts.alert='Laboratory results pending' OR alerts.alert='Radiology results pending' OR alerts.alert='Cardiopulmonary results pending' OR alerts.alert='Referral pending' OR alerts.alert='Reminder' OR alerts.alert='REMINDER')");
				$data['number_reminders'] = $query5->num_rows(); 
				$data['number_bills'] = $this->db->query("SELECT * FROM encounters WHERE bill_submitted='No' AND practice_id=$practice_id")->num_rows();
				$query8 = $this->db->query("SELECT * FROM tests WHERE pid IS NULL AND practice_id=$practice_id");
				$data['number_tests'] = $query8->num_rows();
			}
			if(user_group('billing')) {
				$data['number_messages'] = $this->db->query("SELECT * FROM messaging WHERE mailbox=$user_id")->num_rows();
				$data['number_bills'] = $this->db->query("SELECT * FROM encounters WHERE bill_submitted='No' AND practice_id=$practice_id")->num_rows();
				$data['number_documents'] = $this->db->query("SELECT * FROM scans WHERE practice_id=$practice_id")->num_rows() + $fax_query;
			}
			if(user_group('patient')) {
				$this->db->where('id', $user_id);
				$row = $this->db->get('demographics')->row_array();
				$this->session->set_userdata('pid', $row['pid']);
			}
			if(user_group('admin')) {
				if ($practice_id == '1') {
					$data['saas_admin'] = 'y';
				} else {
					$data['saas_admin'] = 'n';
				}
			}
			$this->auth->view('dashboard', $data);
		} else {
			$this->auth->login($practicehandle);
		}
	}
	
	function draft_messages()
	{
		$from = $this->session->userdata('displayname') . ' (' . $this->session->userdata('user_id') . ')';
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM t_messages JOIN demographics ON t_messages.pid=demographics.pid WHERE t_messages.t_messages_from='$from' AND t_messages.t_messages_signed='No'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM t_messages JOIN demographics ON t_messages.pid=demographics.pid WHERE t_messages.t_messages_from='$from' AND t_messages.t_messages_signed='No' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function draft_encounters()
	{
		$user_id = $this->session->userdata('user_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters JOIN demographics ON encounters.pid=demographics.pid WHERE encounters.user_id=$user_id AND encounters.encounter_signed='No'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters JOIN demographics ON encounters.pid=demographics.pid WHERE encounters.user_id=$user_id AND encounters.encounter_signed='No' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function alerts()
	{
		$provider = $this->session->userdata('user_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM alerts JOIN demographics ON alerts.pid=demographics.pid WHERE alerts.alert_provider='$provider' AND alerts.alert_date_complete='0000-00-00 00:00:00' AND alerts.alert_reason_not_complete='' AND (alerts.alert='Laboratory results pending' OR alerts.alert='Radiology results pending' OR alerts.alert='Cardiopulmonary results pending' OR alerts.alert='Referral pending' OR alerts.alert='Reminder' OR alerts.alert='REMINDER')");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM alerts JOIN demographics ON alerts.pid=demographics.pid WHERE alerts.alert_provider='$provider' AND alerts.alert_date_complete='0000-00-00 00:00:00' AND alerts.alert_reason_not_complete='' AND (alerts.alert='Laboratory results pending' OR alerts.alert='Radiology results pending' OR alerts.alert='Cardiopulmonary results pending' OR alerts.alert='Referral pending' OR alerts.alert='Reminder' OR alerts.alert='REMINDER') ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function mtm_alerts()
	{
		$provider = $this->session->userdata('user_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM alerts JOIN demographics ON alerts.pid=demographics.pid WHERE alerts.alert='Medication Therapy Management' AND alerts.alert_date_complete='0000-00-00 00:00:00' AND alerts.alert_reason_not_complete=''");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM alerts JOIN demographics ON alerts.pid=demographics.pid WHERE alerts.alert='Medication Therapy Management' AND alerts.alert_date_complete='0000-00-00 00:00:00' AND alerts.alert_reason_not_complete='' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function get_version()
	{
		if (!$this->session->userdata('practice_id')) {
			$practice_id = "1";
		} else {
			$practice_id = $this->session->userdata('practice_id');
		}
		$this->db->where('practice_id', $practice_id);
		$result = $this->db->get('practiceinfo')->row_array();
		echo $result['version'];
	}
	
	function get_secret_answer()
	{
		$id = $this->session->userdata('user_id');
		$this->db->select('secret_question, secret_answer');
		$this->db->where('id', $id);
		$result = $this->db->get('users')->row_array();
		echo json_encode($result);
	}
	
	function change_password()
	{
		$old_password = $this->auth->_salt($this->input->post('old_password'));
		$id = $this->session->userdata('user_id');
		$this->db->where('password', $old_password);
		$this->db->where('id', $id);
		$query = $this->db->get('users');
		if ($query->num_rows() != 1) {
			echo 'Your old password is incorrect!';
			exit (0);
		}
		$new_password = $this->auth->_salt($this->input->post('new_password'));
		$data = array (
			'password' => $new_password,
			'secret_question' => $this->input->post('secret_question'),
			'secret_answer' => $this->input->post('secret_answer')
		);
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		echo 'Password changed!';
		exit (0);
	}
	
	function change_secret_answer()
	{
		$id = $this->session->userdata('user_id');
		$data = array (
			'secret_question' => $this->input->post('secret_question'),
			'secret_answer' => $this->input->post('secret_answer')
		);
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		echo 'Secret question and answer set!';
		exit (0);
	}
	
	function check_secret_answer()
	{
		$id = $this->session->userdata('user_id');
		$this->db->where('id', $id);
		$result = $this->db->get('users')->row_array();
		if ($result['secret_question'] == '') {
			echo "Need secret question and answer!";
		}
		exit (0);
	}
	
	function forgot_password($username)
	{
		$this->db->where('username', $username);
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			if ($result['secret_question'] == '') {
				$arr['response'] = "You need to setup a secret question and answer.  Contact the practice administrator to manually reset your password.";
			} else {
				$arr['response'] = $result['secret_question'];
			}
			$arr['id'] = $result['id'];
		} else {
			$arr['response'] = "You are not a registered user.";
		}
		echo json_encode($arr);
		exit (0);
	}
	
	function forgot_password1()
	{
		$id = $this->input->post('id');
		$count = intval($this->input->post('count'));
		if ($count > 2) {
			$arr['response'] = "Close";
			echo json_encode($arr);
			exit (0);
		}
		$this->db->where('id', $id);
		$result = $this->db->get('users')->row_array();
		if ($result['secret_answer'] == $this->input->post('secret_answer')) {
			$arr['response'] = "OK";
		} else {
			$arr['response'] = "Secret answer is incorrect!";
			$count++;
			$arr['count'] = strval($count);
		}
		echo json_encode($arr);
		exit (0);
	}
	
	function forgot_password2($id)
	{
		$new_password = $this->auth->_salt($this->input->post('new_password'));
		$data = array (
			'password' => $new_password
		);
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		echo 'Password changed! Login again.';
		exit (0);
	}
	
	function new_password($id)
	{
		$new_password = $this->auth->_salt($this->input->post('new_password1'));
		$data = array (
			'password' => $new_password
		);
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		echo 'Password created! Login again.';
		exit (0);
	}
	
	function rpHash($value) 
	{ 
		switch(PHP_INT_SIZE) {
			case 4:
				$hash = 5381; 
				$value = strtoupper($value); 
				for($i = 0; $i < strlen($value); $i++) { 
					$hash = (($hash << 5) + $hash) + ord(substr($value, $i));
				}
				return $hash;
				break;
			case 8:
				$hash = 5381; 
				$value = strtoupper($value); 
				for($i = 0; $i < strlen($value); $i++) { 
					$hash = ($this->leftShift32($hash, 5) + $hash) + ord(substr($value, $i)); 
				} 
				return $hash; 
			break;
		}
		$hash = 5381; 
		$value = strtoupper($value); 
		for($i = 0; $i < strlen($value); $i++) { 
			$hash = (($hash << 5) + $hash) + ord(substr($value, $i));
		}
		return $hash; 
	}
	
	function leftShift32($number, $steps)
	{
		$binary = decbin($number); 
		$binary = str_pad($binary, 32, "0", STR_PAD_LEFT); 
		$binary = $binary.str_repeat("0", $steps); 
		$binary = substr($binary, strlen($binary) - 32); 
		return ($binary{0} == "0" ? bindec($binary) : -(pow(2, 31) - bindec(substr($binary, 1)))); 
	}
	
	function register_user()
	{
		if ($this->rpHash($this->input->post('numberReal')) == $this->input->post('numberRealHash')) {
			$registration_code = $this->input->post('registration_code');
			if ($registration_code != '') {
				$count = intval($this->input->post('count'));
				if ($count > 2) {
					$arr['response'] = "3";
					echo json_encode($arr);
					exit (0);
				}
				$dob1 = $this->input->post('dob');
				$dob2 = strtotime($dob1);
				$datestring = "%Y-%m-%d";
				$dob = mdate($datestring, $dob2);
				$this->db->where('registration_code', $registration_code);
				$this->db->where('firstname', $this->input->post('firstname'));
				$this->db->where('lastname', $this->input->post('lastname'));
				$this->db->where('DOB', $dob);
				$result = $this->db->get('demographics');
				if ($result->num_rows() > 0) {
					$arr['response'] = "1";
					$displayname = $this->input->post('firstname') . " " . $this->input->post('lastname');
					$patient_result = $result->row_array();
					$this->db->where('pid', $patient_result['pid']);
					foreach ($this->db->get('demographics_relate')->result_array() as $demographics_relate_row) {
						if ($demographics_relate_row['id'] != "") {
							$arr['response'] = "5";
							$this->db->where('practice_id', $demographics_relate_row['practice_id']);
							$row2 = $this->db->get('practiceinfo')->row_array();
							$this->db->where('id', $demographics_relate_row['id']);
							$row3 = $this->db->get('users')->row_array();
							$message = 'You tried to register for a patient portal for ' . $row2['practice_name'] . ". You already have a registered account with a username of " . $row3['username'] . '.';
							$config['protocol']='smtp';
							$config['smtp_host']='ssl://smtp.googlemail.com';
							$config['smtp_port']='465';
							$config['smtp_timeout']='30';
							$config['charset']='utf-8';
							$config['newline']="\r\n";
							$config['smtp_user']=$row2['smtp_user'];
							$config['smtp_pass']=$row2['smtp_pass'];
							$this->email->initialize($config);
							$this->email->from($row2['email'], $row2['practice_name']);
							$this->email->to($this->input->post('email'));
							$this->email->subject('Patient Portal Registration Message');
							$this->email->message($message);
							$this->email->send();
						} else {
							$data1 = array(
								'username' => $this->input->post('username'),
								'firstname' => $this->input->post('firstname'),
								'lastname' => $this->input->post('lastname'),
								'email' => $this->input->post('email'),
								'group_id' => '100',
								'active' => '1',
								'displayname' => $displayname,
								'practice_id' => $demographics_relate_row['practice_id']
							);
							$arr['id'] = $this->users_model->add($data1);
							$data2 = array(
								'id' => $arr['id']
							);
							$this->db->where('demographics_relate_id', $demographics_relate_row['demographics_relate_id']);
							$this->db->update('demographics_relate', $data2);
							$this->db->where('practice_id', $demographics_relate_row['practice_id']);
							$row1 = $this->db->get('practiceinfo')->row_array();
							$message = 'You are now registered to your patient portal for ' . $row1['practice_name'] . ". Your username is " . $this->input->post('username') . '.';
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
							$this->email->to($this->input->post('email'));
							$this->email->subject('Patient Portal Registration Confirmation');
							$this->email->message($message);
							$this->email->send();
						}
					}
				} else {
					$arr['response'] = "2";
					$count++;
					$arr['count'] = strval($count);
				}
			} else {
				$this->db->where('practice_id', $this->input->post('practice_id'));
				$row1 = $this->db->get('practiceinfo')->row_array();
				$displayname = $this->session->userdata('displayname');
				$message = 'You have a new user request from ' . $this->input->post('firstname') . " " . $this->input->post('lastname') . ". Date of birth is " . $this->input->post('dob') . ". Desired user name is " . $this->input->post('username') . ". Return e-mail to " . $this->input->post('email');
				$config['protocol'] = 'smtp';
				$config['smtp_host'] = "ssl://smtp.googlemail.com";
				$config['smtp_port'] = '465';
				$config['smtp_timeout'] = '30';
				$config['smtp_user'] = $row1['smtp_user'];
				$config['smtp_pass'] = $row1['smtp_pass'];
				$config['charset'] = 'utf-8';
				$config['newline'] = "\r\n";
				$this->email->initialize($config);
				$this->email->from($row1['email'], 'NOSH ChartingSystem User Registration');
				$this->email->to($row1['email']);
				$this->email->subject('New User Request');
				$this->email->message($message);
				$this->email->send();
				$arr['response'] = "4";
			}
		} else {
			$count = intval($this->input->post('count'));
			$arr['response'] = "2";
			$count++;
			$arr['count'] = strval($count);
		}
		echo json_encode($arr);
		exit (0);
	}
	
	function change_signature()
	{
		$this->load->library('signaturetoimage');
		$id = $this->session->userdata('user_id');
		$date = now();
		$filename = "/var/www/nosh/images/signature_" . $id . "_" . $date . ".png";
		$json = $this->input->post('output');
		$img = $this->signaturetoimage->sigJsonToImage($json);
		imagepng($img, $filename);
		imagedestroy($img);
		$data = array(
			'signature' => $filename
		);
		$this->db->where('id', $id);
		$this->db->update('providers', $data);
		$this->index();
	}
	
	function preview_signature()
	{
		if(user_group('provider')){
			$user_id = $this->session->userdata('user_id');
			$this->db->select('signature');
			$this->db->where('id', $user_id);
			$signature = $this->db->get('providers')->row_array();
			if ($signature['signature'] != '') {
				$signature1 = str_replace("/var/www/nosh/","",$signature['signature']);
				$result = "<img src='" . base_url() . $signature1 . "'>";
			} else {
				$result = '';
			}
		} else {
			$result = '';
		}
		echo $result;
	}
	
	function provider_info()
	{
		$id = $this->session->userdata('user_id');
		$this->db->select('id, license, license_state, npi, specialty, upin, dea, medicare, tax_id, rcopia_username, schedule_increment, peacehealth_id');
		$this->db->where('id', $id);
		$result = $this->db->get('providers')->row_array();
		echo json_encode($result);
	}
	
	function edit_provider_info()
	{
		$specialty = substr($this->input->post('specialty'), 0, -13);
		$npi_taxonomy = substr($this->input->post('specialty'), -11, 10);
		$data = array(
			'specialty' => $specialty,
			'license' => $this->input->post('license'),
			'license_state' => $this->input->post('license_state'),
			'npi' => $this->input->post('npi'),
			'npi_taxonomy' => $npi_taxonomy,
			'upin' => $this->input->post('upin'),
			'dea' => $this->input->post('dea'),
			'medicare' => $this->input->post('medicare'),
			'tax_id' => $this->input->post('tax_id'),
			'rcopia_username' => $this->input->post('rcopia_username'),
			'schedule_increment' => $this->input->post('schedule_increment'),
			'peacehealth_id' => $this->input->post('peacehealth_id')
		);
		$this->users_model->updateProvider($this->input->post('id'), $data);
		echo "Provider information updated!";
	}
	
	function check_rcopia_extension()
	{
		$result = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
		echo $result['rcopia_extension'];
	}
	
	function check_mtm_extension()
	{
		$result = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
		if ($result['mtm_extension'] == 'y') {
			$arr['response'] = 'y';
			$arr['row'] = '<img src="' . base_url() . 'images/graph.png" border="0" height="30" width="30" style="vertical-align:middle;" id="mtm_list_img" class="mtm_tooltip"> <a href="#" id="mtm_list" class="mtm_tooltip">MTM</a><br />';
		} else {
			$arr['response'] = 'n';
		}
		echo json_encode($arr);
	}
	
	function check_fax()
	{
		$result = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
		if ($result['fax_type'] != "") {
			echo "Yes";
		} else {
			echo "No";
		}
	}
	
	function check_cookie()
	{
		$result = "";
		if((array_key_exists('login_attempts', $_COOKIE)) && ($_COOKIE['login_attempts'] >= 1)) {
			$result = "Username and Password combination does not exist.  You have 5 attempts before lock out.";
		}
		echo $result;
	}
	
	function check_snomed_extension()
	{
		$result = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
		echo $result['snomed_extension'];
	}
	
	function test_updox()
	{
		$url = 'https://www.myupdox.com/udb/updoxapi/Integration.listConnectorMessages';
		//$url = 'https://www.myupdox.com/udb/updoxapi/Integration.syncConnect';
		$host = gethostname();
		$ver = '5.3';
		$fields = array(
			'MAGIC' => '123',
			'VERSION' => '1',
			'REPLY' => 'FALSE',
			'TYPE' => '9999',
			'SERIAL_ID' => '9999',
			'ARG_COUNT' => '2',
			'host' => urlencode($host),
			'ver' => urlencode($ver)
		);
		$fields_string = '';
		$apiuid = "";
		$apipwd = "";
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$license = base64_encode($apiuid . ":" . $apipwd );
		$length = strlen($fields_string);
		$headers = array(
			"Content-Type: application/updox",
			"Content-Length: " . $length,
			"Authorization: Basic " . $license 
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
		curl_close($ch);
		echo $result;
	}
	
	function options_load()
	{
		if(user_group('provider') || user_group('assistant') || user_group('billing')) {
			if (user_group('provider')) {
				$data['url'] = 'provider/chartmenu/add_orderslist';
				$data['user'] = 'provider';
			}
			if (user_group('assistant')) {
				$data['url'] = 'assistant/chartmenu/add_orderslist';
				$data['user'] = 'assistant';
			}
			if (user_group('billing')) {
				$data['url'] = '';
				$data['user'] = 'billing';
			}
			$this->load->view('auth/pages/options', $data);
		} else {
			echo "";
		}
	}
	
	function orders_list($type, $user_type)
	{
		if ($user_type == 'Global') {
			$user_id = '0';
		} else {
			$user_id = $this->session->userdata('user_id');
		}
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM orderslist WHERE orders_category='$type' AND user_id='$user_id'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM orderslist WHERE orders_category='$type' AND user_id='$user_id' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function delete_orders_list()
	{
		$this->db->where('orderslist_id', $this->input->post('orderslist_id'));
		$this->db->delete('orderslist');
		echo "Template entry deleted.";
		exit( 0 );
	}
	
	function cpt_list($mask='')
	{
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		if($mask == ''){
			$query = $this->db->query("SELECT * FROM cpt");
		} else {
			$mask = "'%".$mask."%'";
			$query = $this->db->query("SELECT * FROM cpt WHERE cpt_description LIKE $mask OR cpt LIKE $mask");
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
		if($mask == ''){
			$query1 = $this->db->query("SELECT * FROM cpt ORDER BY $sidx $sord LIMIT $start , $limit");
		} else {
			$query1 = $this->db->query("SELECT * FROM cpt WHERE cpt_description LIKE $mask OR cpt LIKE $mask ORDER BY $sidx $sord LIMIT $start , $limit");
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$records1 = array();
		$i = 0;
		foreach ($records as $records_row) {
			$this->db->where('cpt', $records_row['cpt']);
			$this->db->where('practice_id', $this->session->userdata('practice_id'));
			$query2 = $this->db->get('cpt_relate');
			if ($query2->num_rows() > 0) {
				$query2_row = $query2->row_array();
				$records1[$i] = array(
					'cpt_id' => $records_row['cpt_id'],
					'cpt_relate_id' => $query2_row['cpt_relate_id'],
					'cpt' => $query2_row['cpt'],
					'cpt_description' => $query2_row['cpt_description'],
					'cpt_charge' => $query2_row['cpt_charge'],
					'favorite' => $query2_row['favorite'],
					'unit' => $query2_row['unit']
				);
			} else {
				$records1[$i] = array(
					'cpt_id' => $records_row['cpt_id'],
					'cpt_relate_id' => '',
					'cpt' => $records_row['cpt'],
					'cpt_description' => $records_row['cpt_description'],
					'cpt_charge' => '',
					'favorite' => '0',
					'unit' => '1'
				);
			}
			$i++;
		}
		$response['rows'] = $records1;
		echo json_encode($response);
		exit( 0 );
	}
	
	function edit_cpt_list()
	{
		$data = array(
			'cpt' => $this->input->post('cpt'),
			'cpt_description' => $this->input->post('cpt_description'),
		);
		if ($this->input->post('cpt_id') != '') {
			$this->db->where('cpt_id', $this->input->post('cpt_id'));
			$this->db->update('cpt', $data);
			$arr['message'] = "CPT code updated!";
		} else {
			$this->db->insert('cpt', $data);
			$arr['message'] = "CPT code added!";
		}
		if ($this->input->post('cpt_charge') != '') {
			$charge = str_replace("$", "", $this->input->post('cpt_charge'));
			$pos = strpos($charge, ".");
			if ($pos === FALSE) {
				$charge .= ".00";
			}
			$data_relate = array(
				'cpt' => $this->input->post('cpt'),
				'cpt_description' => $this->input->post('cpt_description'),
				'cpt_charge' => $charge,
				'practice_id' => $this->session->userdata('practice_id'),
				'favorite' => $this->input->post('favorite'),
				'unit' => $this->input->post('unit')
			);
			if ($this->input->post('cpt_relate_id') != '') {
				$this->db->where('cpt_relate_id', $this->input->post('cpt_relate_id'));
				$this->db->update('cpt_relate', $data_relate);
				$arr['message'] .= "  CPT charge updated!";
			} else {
				$this->db->insert('cpt_relate', $data_relate);
				$arr['message'] .= "  CPT charge added!";
			}
			$arr['charge'] = $charge;
		} else {
			$arr['charge'] = '';
		}
		echo json_encode($arr);
	}
	
	function delete_cpt()
	{
		$this->db->where('cpt_id', $this->input->post('id'));
		$this->db->delete('cpt');
		echo "CPT code deleted!";
	}
	
	function get_sales_tax()
	{
		$result = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
		echo $result['sales_tax'];
	}
	
	function update_sales_tax()
	{
		$data['sales_tax'] = $this->input->post('sales_tax');
		$this->db->where('practice_id',$this->session->userdata('practice_id'));
		$this->db->update('practiceinfo', $data);
		if ($data['sales_tax'] != "") {
			$this->db->where('cpt','sptax');
			$query = $this->db->get('cpt');
			if ($query->num_rows() == 0) {
				$data1 = array(
					'cpt' => 'sptax',
					'cpt_description' => 'Sales Tax',
					'cpt_charge' => '',
					'practice_id' => $this->session->userdata('practice_id')
				);
				$this->db->insert('cpt_relate',$data1);
			}
		}
		echo "Sales tax percentage updated!";
	}
	
	function check_username()
	{
		$this->db->where('username', $this->input->post('username'));
		if ($this->db->get('users')->num_rows() > 0) {
			echo "Username already exists!";
		} else {
			echo "OK";
		}
	}
	
	function patient_forms_list()
	{
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM templates WHERE category='forms' AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM templates WHERE category='forms' AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$records1 = array();
		$i = 0;
		foreach ($records as $row) {
			$records1[$i]['template_id'] = $row['template_id'];
			$records1[$i]['template_name'] = $row['template_name'];
			$records1[$i]['sex'] = $row['sex'];
			$records1[$i]['group'] = $row['group'];
			$records1[$i]['age'] = $row['age'];
			$i++;
		}
		$response['rows'] = $records1;
		echo json_encode($response);
		exit( 0 );
	}
	
	function save_patient_form($type)
	{
		if ($type == 'user') {
			$user_id = $this->session->userdata('user_id');
		} else {
			$user_id = "0";
		}
		$group = strtolower($this->input->post('template_name'));
		$group = str_replace(" ", "_", $group);
		$array = serialize($this->input->post('array'));
		if ($this->input->post('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => $this->input->post('template_name'),
				'age' => $this->input->post('age'),
				'category' => 'forms',
				'sex' => 'm',
				'group' => $group,
				'array' => $array,
				'practice_id' => $this->session->userdata('practice_id')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => $this->input->post('template_name'),
				'age' => $this->input->post('age'),
				'category' => 'forms',
				'sex' => 'f',
				'group' => $group,
				'array' => $array,
				'practice_id' => $this->session->userdata('practice_id')
			);
			if ($this->input->post('template_id') == '') {
				$this->db->insert('templates', $template_data1);
				$this->audit_model->add();
				$this->db->insert('templates', $template_data2);
				$this->audit_model->add();
				$message = "Form added as a template!";
			} else {
				$this->db->where('template_id', $this->input->post('template_id'));
				$template_row = $this->db->get('templates')->row_array();
				if ($template_row['sex'] == 'm') {
					$template_id1 = $this->input->post('template_id');
				} else {
					$template_id2 = $this->input->post('template_id');
				}
				$this->db->where('group', $template_row['group']);
				$this->db->where('template_id !=', $this->input->post('template_id'));
				$template_query1 = $this->db->get('templates');
				if ($template_query1->num_rows() > 0) {
					$template_row1 = $template_query1->row_array();
					if ($template_row1['sex'] == 'm') {
						$template_id1 = $template_row1['template_id'];
					} else {
						$template_id2 = $template_row1['template_id'];
					}
					$this->db->where('template_id', $template_id1);
					$this->db->update('templates', $template_data1);
					$this->audit_model->update();
					$this->db->where('template_id', $template_id2);
					$this->db->update('templates', $template_data2);
					$this->audit_model->update();
				} else {
					if ($template_row['sex'] == 'm') {
						$this->db->insert('templates', $template_data2);
						$this->audit_model->add();
					} else {
						$this->db->insert('templates', $template_data1);
						$this->audit_model->add();
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => $this->input->post('template_name'),
				'age' => $this->input->post('age'),
				'category' => 'forms',
				'sex' => $this->input->post('sex'),
				'group' => $group,
				'array' => $array,
				'practice_id' => $this->session->userdata('practice_id')
			);
			if ($this->input->post('template_id') == '') {
				$this->db->insert('templates', $template_data3);
				$this->audit_model->add();
				$message = "Form added as a template!";
			} else {
				$this->db->where('template_id', $this->input->post('template_id'));
				$this->db->update('templates', $template_data3);
				$this->audit_model->update();
				$message = "Form updated as a template!";
			}
		}
		echo $message;
		exit( 0 );
	}
	
	function delete_patient_forms()
	{
		$this->db->where('template_id', $this->input->post('template_id'));
		$this->db->delete('templates');
		$this->audit_model->delete();
		echo "Form template deleted!";
		exit( 0 );
	}
	
	function get_patient_forms()
	{
		$this->db->where('template_id', $this->input->post('template_id'));
		$row = $this->db->get('templates')->row_array();
		$array = unserialize($row['array']);
		echo $array;
		exit( 0 );
	}
	
	function get_practices()
	{
		$query = $this->db->get('practiceinfo');
		$data['message'] = array();
		foreach ($query->result_array() as $row) {
			$data['message'][$row['practice_id']] = $row['practice_name'];
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function tests()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord'); 
		$query = $this->db->query("SELECT * FROM tests WHERE pid IS NULL");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM tests WHERE pid IS NULL ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function tests_import()
	{
		$pid = $this->input->post('pid');
		$tests_id_array = json_decode($this->input->post('tests_id_array'));
		$i = 0;
		$results = array();
		foreach ($tests_id_array as $tests_id) {
			$data = array(
				'pid' => $pid,
				'test_unassigned' => ''
			);
			$this->db->where('tests_id', $tests_id);
			$this->db->update('tests', $data);
			$this->db->where('tests_id', $tests_id);
			$results[$i] = $this->db->get('tests')->row_array();
			$provider_id = $results[$i]['test_provider_id'];
			$from = $results[$i]['test_from'];
			$test_type = $results[$i]['test_type'];
			$i++;
		}
		$this->db->where('pid', $pid);
		$patient_row = $this->db->get('demographics')->row_array();
		$dob_message = mdate("%m/%d/%Y", strtotime($patient_row['DOB']));
		$patient_name =  $patient_row['lastname'] . ', ' . $patient_row['firstname'] . ' (DOB: ' . $dob_message . ') (ID: ' . $pid . ')';
		$practice_row = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
		$directory = $practice_row['documents_dir'] . $pid;
		$file_path = $directory . '/tests_' . now() . '.pdf';
		$html = $this->page_intro('Test Results', $this->session->userdata('practice_id'));
		$html .= $this->page_results($pid, $results, $patient_name);
		$this->load->library('mpdf');
		$this->mpdf=new mpdf('','Letter',0,'',26,26,26,26,9,9,'P');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit=1;
		$this->mpdf->AddPage();
		$this->mpdf->SetHTMLFooter($footer,'O');
		$this->mpdf->SetHTMLFooter($footer,'E');
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Document Generated by NOSH ChartingSystem');
		$this->mpdf->debug = true;
		$this->mpdf->Output($file_path,'F');
		while(!file_exists($file_path)) {
			sleep(2);
		}
		$documents_date = mdate("%Y-%m-%d %H:%i:%s", now());
		$test_desc = 'Test results for ' . $patient_name;
		$pages_data = array(
			'documents_url' => $file_path,
			'pid' => $pid,
			'documents_type' => $test_type,
			'documents_desc' => $test_desc,
			'documents_from' => $from,
			'documents_date' => $documents_date
		);
		if(user_group('provider')) {
			$pages_data['documents_viewed'] = $this->session->userdata('displayname');
		}
		$documents_id = $this->chart_model->addDocuments($pages_data);
		$this->audit_model->add();
		if(user_group('assistant')) {
			$this->db->where('id', $provider_id);
			$provider_row = $this->db->get('users')->row_array();
			$provider_name = $provider_row['firstname'] . " " . $provider_row['lastname'] . ", " . $provider_row['title'] . " (" . $provider_id . ")";
			$subject = "Test results for " . $patient_name;
			$body = "Test results for " . $patient_name . "\n\n";
			foreach ($results as $results_row1) {
				$body .= $results_row1['test_name'] . ": " . $results_row1['test_result'] . ", Units: " . $results_row1['test_units'] . ", Normal reference range: " . $results_row1['test_reference'] . ", Date: " . $results_row1['test_datetime'] . "\n";
			}
			$body .= "\n" . $from;
			$data_message = array(
				'pid' => $pid,
				'message_to' => $provider_name,
				'message_from' => $this->session->userdata('user_id'),
				'subject' => $subject,
				'body' => $body,
				'patient_name' => $patient_name,
				'status' => 'Sent',
				'mailbox' => $provider_id,
				'practice_id' => $practice_id,
				'documents_id' => $documents_id
			);
			$this->db->insert('messages', $data_message);
		}
	}
	
	function page_intro($title, $practice_id)
	{
		$practice = $this->practiceinfo_model->get($practice_id)->row_array();
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
			$logo = str_replace("/var/www/","http://localhost/", $practice['practice_logo']);
			$data['practiceLogo'] = "<img src='" . $logo . "' border='0'>";
		} else {
			$data['practiceLogo'] = '<br><br><br><br><br>';
		}
		$data['title'] = $title;
		return $this->load->view('auth/pages/intro_page', $data, TRUE);
	}
	
	function page_results($pid, $results, $patient_name)
	{
		$body = '';
		$body .= "<br>Test results for " . $patient_name . "<br><br>";
		$body .= "<table><tr><th>Date</th><th>Test</th><th>Result</th><th>Units</th><th>Normal reference range</th><th>Flags</th></tr>";
		foreach ($results as $results_row1) {
			$body .= "<tr><td>" . $results_row1['test_datetime'] . "</td><td>" . $results_row1['test_name'] . "</td><td>" . $results_row1['test_result'] . "</td><td>" . $results_row1['test_units'] . "</td><td>" . $results_row1['test_reference'] . "</td><td>" . $results_row1['test_flags'] . "</td></tr>";
			$from = $results_row1['test_from'];
		}
		$body .= "</table><br>" . $from;
		$body .= '</body></html>';
		return $body;
	}
	
	function login_practice_logo($practice_id)
	{
		$this->db->where('practice_id', $practice_id);
		$row = $this->db->get('practiceinfo')->row_array();
		$practicehandle = $this->session->userdata('practicehandle');
		if ($practicehandle != FALSE) {
			if ($practicehandle != $row['practicehandle']) {
				$data1 = array(
					'practicehandle' => $row['practicehandle']
				);
				$this->session->set_userdata($data1);
			}
		}
		$html = "";
		if ($row['practice_logo'] != '') {
			$logo1 = str_replace("/var/www/nosh/","", $row['practice_logo']);
			$logo = base_url() . $logo1;
			$html = "<img src='" . $logo . "' border='0'>";
		}
		echo $html;
	}
}

/* End of file: start.php */
/* Location: application/controllers/start.php */
