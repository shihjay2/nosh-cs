<?php

class Chartmenu extends Application
{

	function Chartmenu()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->load->library('email');
		$this->load->library('zip');
		$this->auth->restrict('admin');
		$this->load->helper('file');
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
		if ($pid == '') {
			redirect('start');
		}
		
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row(); 
		$data['ptname'] = $row->firstname . ' ' . $row->lastname;
		$data['dob'] = substr($row->DOB, 0, 10);
		
		$dob1 = human_to_unix($row->DOB);
		$now = time();
		$age1 = timespan($dob1, $now);
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
		
		$agediff = ($now - $dob1)/86400;
		$pos4 = strpos($agediff, '.');
		$data['agealldays'] = substr($agediff, 0, $pos4);
		
		if ($row->sex == 'm') {
			$data['gender'] = 'Male';
		} 
		if ($row->sex == 'f') {
			$data['gender'] = 'Female';
		}
		
		$data['patientnumber'] = $pid;
		
		$this->session->set_userdata('gender', $data['gender']);
		$this->session->set_userdata('age', $data['age']);
		$this->session->set_userdata('agealldays', $data['agealldays']);
		$this->session->set_userdata('ptname', $data['ptname']);
		
		$this->auth->view('admin/patient', $data);
	}

	// --------------------------------------------------------------------

	function closechart()
	{
		$this->session->unset_userdata('age');
		$this->session->unset_userdata('agealldays');
		$this->session->unset_userdata('gender');
		$this->session->unset_userdata('pid');
		$this->session->unset_userdata('ptname');
		redirect('start');
	}
	
	function close_document()
	{
		unlink($this->input->post('document_filepath'));
		echo 'OK';
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
		$data['html'] = '<iframe src="' . base_url() . $name . '" width="770" height="425" style="border: none;"></iframe>';
		echo json_encode($data);
	}
	
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
	
	function encounters()
	{
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND encounter_signed='Yes' AND addendum='n' AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND encounter_signed='Yes' AND addendum='n' AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function page_instructions($eid)
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
				$data['rx'] .= '<strong>Prescriptions Given: </strong>';
				$data['rx'] .= nl2br($rxInfo->rx_rx);
				$data['rx'] .= '<br /><br />';
			}
			if ($rxInfo->rx_supplements!= '') {
				$data['rx'] .= '<strong>Supplements Recommended: </strong>';
				$data['rx'] .= nl2br($rxInfo->rx_supplements);
				$data['rx'] .= '<br /><br />';
			}
			if ($rxInfo->rx_immunizations != '') {
				$data['rx'] .= '<strong>Immunizations Given: </strong>';
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
	
	function view_instructions($eid)
	{
		ini_set('memory_limit','196M');
		$pid = $this->session->userdata('pid');
		$html = $this->page_instructions($eid);
		$name = now() . '_' . $pid . '.pdf';
		$data['filepath'] = '/var/www/nosh/' . $name;
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Generated by NOSH EMR');
		$this->mpdf->debug = true;
		$this->mpdf->showImageErrors = true;
		$this->mpdf->Output($data['filepath'],'F');
		while(!file_exists($data['filepath'])) {
			sleep(2);
		}
		$data['html'] = '<iframe src="' . base_url() . $name . '" width="770" height="425" style="border: none;"></iframe>';
		echo json_encode($data);
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
	
	function records_release()
	{
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM hippa WHERE pid=$pid AND other_hippa_id='0' AND practice_id=$practice_id");
		$count = $query->num_rows();
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM hippa WHERE pid=$pid AND other_hippa_id='0' AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
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
			'other_hippa_id' => 0,
			'practice_id' => $this->session->userdata('practice_id')
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
			'pid' => $pid,
			'practice_id' => $this->session->userdata('practice_id')
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
			'pid' => $pid,
			'practice_id' => $this->session->userdata('practice_id')
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
			'pid' => $pid,
			'practice_id' => $this->session->userdata('practice_id')
		);
		$this->db->insert('hippa', $data);
		$this->audit_model->add();
		echo "Item added to queue!";
	}
	
	function print_chart($hippa_id)
	{
		$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		echo "OK";
	}
	
	function print_chart1($hippa_id)
	{
		$practice_id = $this->session->userdata('practice_id');
		$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
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
		echo "OK";
	}
	
	function print_chart2($hippa_id)
	{
		$practice_id = $this->session->userdata('practice_id');
		$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
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
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND UNIX_TIMESTAMP(encounter_DOS)>=$start AND UNIX_TIMESTAMP(encounter_DOS)<=$end AND encounter_signed='Yes' AND addendum='n' AND practice_id=$practice_id ORDER BY encounter_DOS DESC");
		if ($query1->num_rows() > 0) {
			$html .= '<table width="100%"><tr><th style="background-color: gray;color: #FFFFFF;">ENCOUNTERS</th></tr></table>';
			foreach ($query1->result_array() as $row1) {
				$html .= $this->encounters_view($row1['eid']);
			}
		}
		$query2 = $this->db->query("SELECT * FROM t_messages WHERE pid=$pid AND UNIX_TIMESTAMP(t_messages_dos)>=$start AND UNIX_TIMESTAMP(t_messages_dos)<=$end AND t_messages_signed='Yes' AND practice_id=$practice_id ORDER BY t_messages_dos DESC");
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
		echo "OK";
	}
	
	function compile_chart($hippa_id)
	{
		$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
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
		$practiceInfo = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
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
		return $this->load->view('auth/pages/provider/chart/encounters_view', $data, TRUE);
	}
	
	function print_encounters()
	{
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND encounter_signed='Yes' AND addendum='n' AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND encounter_signed='Yes' AND addendum='n' AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
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
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM t_messages WHERE pid=$pid AND t_messages_signed='Yes' AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM t_messages WHERE pid=$pid AND t_messages_signed='Yes' AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
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
		$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
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
		$practice_id = $this->session->userdata('practice_id');
		if ($type == 'queue') {
			$query1 = $this->db->query("SELECT * FROM hippa JOIN encounters ON hippa.eid=encounters.eid WHERE hippa.other_hippa_id=$hippa_id AND hippa.eid IS NOT NULL AND hippa.practice_id=$practice_id ORDER BY encounters.encounter_DOS DESC");
		}
		if ($type == 'all') {
			$this->db->where('pid', $pid);
			$this->db->where('encounter_signed', 'Yes');
			$this->db->where('addendum', 'n');
			$this->db->where('practice_id', $practice_id);
			$this->db->order_by('encounter_DOS', 'desc');
			$query1 = $this->db->get('encounters');
		}
		if ($type == '1year') {
			$end = now();
			$start = $end - 31556926;
			$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND UNIX_TIMESTAMP(encounter_DOS)>=$start AND UNIX_TIMESTAMP(encounter_DOS)<=$end AND encounter_signed='Yes' AND addendum='n' AND practice_id=$practice_id ORDER BY encounter_DOS DESC");
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
		$practice_info = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
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
	
	function page_intro($title)
	{
		$practice_id = $this->session->userdata('practice_id');
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
	
	function check_print_entire_chart()
	{
		$practice_id = $this->session->userdata['practice_id'];
		$this->db->where('practice_id', $practice_id);
		$query = $this->db->get('demographics_relate');
		if ($query->num_rows() > 0) {
			echo "OK";
			$this->session->set_userdata('print_chart_percent', '0');
		} else {
			echo "No patients in your practice!";
		}
	}
	
	function print_entire_chart()
	{
		ini_set('memory_limit','196M');
		$practice_id = $this->session->userdata['practice_id'];
		$this->db->where('practice_id', $practice_id);
		$query = $this->db->get('demographics_relate');
		$total = $query->num_rows();
		$i=0;
		$data = array();
		foreach ($query->result_array() as $row) {
			$file = $this->print_entire_chart1($row['pid']);
			$this->zip->read_file($file);
			$i++;
			$percent = round($i/$total*100);
			$this->session->set_userdata('print_chart_percent', $percent);
		}
		$zip_file_name = '/var/www/nosh/charts_' . $practice_id . '.zip';
		if (file_exists($zip_file_name)) {
			unlink($zip_file_name);
		}
		$this->zip->archive($zip_file_name);
		$data['message'] = "OK";
		$data['html'] = '<a href="' . base_url(). 'charts_' . $practice_id . '.zip">Download ZIP File</a>';
		echo json_encode($data);
	}
	
	function print_entire_chart1($pid)
	{
		$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
		$result = $query->row_array();
		$this->db->where('pid', $pid);
		$patient = $this->db->get('demographics')->row_array();
		$lastname = str_replace(' ', '_', $patient['lastname']);
		$firstname = str_replace(' ', '_', $patient['firstname']);
		$dob = date('Ymd', human_to_unix($patient['DOB']));
		$directory = $result['documents_dir'] . $pid . "/print_entire";
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
			while(!file_exists($file_path_docs)) {
				sleep(2);
			}
		}
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
		$file_path = $directory . '/' . $pid . '_' . $lastname . '_' . $firstname . '_' . $dob . '_printchart_final.pdf';
		if (file_exists($file_path)) {
			unlink ($file_path);
		}
		$commandpdf1 = "pdftk " . $input . " cat output " . $file_path;
		$commandpdf2 = escapeshellcmd($commandpdf1);
		exec($commandpdf2);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return $file_path;
	}
	
	function print_entire_chart_progress()
	{
		echo $this->session->userdata('print_chart_percent');
	}
	
	function check_csv_patient_demographics()
	{
		$practice_id = $this->session->userdata['practice_id'];
		$this->db->where('practice_id', $practice_id);
		$query = $this->db->get('demographics_relate');
		if ($query->num_rows() > 0) {
			echo "OK";
			$this->session->set_userdata('csv_percent', '0');
		} else {
			echo "No patients in your practice!";
		}
	}
	
	function generate_csv_patient_demographics()
	{
		ini_set('memory_limit','196M');
		$practice_id = $this->session->userdata['practice_id'];
		$this->db->where('practice_id', $practice_id);
		$query = $this->db->get('demographics_relate');
		$total = $query->num_rows();
		$i=0;
		$data = array();
		$csv = "Last Name;First Name;Gender;Date of Birth;Address;City;State;Zip;Home Phone;Work Phone;Cell Phone";
		foreach ($query->result_array() as $row) {
			$this->db->select('lastname, firstname, sex, DOB, address, city, state, zip, phone_home, phone_work, phone_cell');
			$this->db->where('pid', $row['pid']);
			$row1 = $this->db->get('demographics')->row_array();
			$csv .= "\n";
			$csv .= implode(";", $row1);
			$i++;
			$percent = round($i/$total*100);
			$this->session->set_userdata('csv_percent', $percent);
		}
		$csv_file_name = '/var/www/nosh/csv_' . $practice_id . '.csv';
		if (file_exists($csv_file_name)) {
			unlink($csv_file_name);
		}
		write_file($csv_file_name, $csv);
		$data['message'] = "OK";
		$data['html'] = '<a href="' . base_url(). 'csv_' . $practice_id . '.csv">Download CSV File</a>';
		echo json_encode($data);
	}
	
	function csv_progress()
	{
		echo $this->session->userdata('csv_percent');
	}
} 
/* End of file: chartmenu.php */
/* Location: application/controllers/provider/chartmenu.php */
