<?php

class Chartmenu extends Application
{

	function Chartmenu()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->load->library('email');
		$this->auth->restrict('patient');
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
		$this->auth->view('patient/chart');
	}
	
	function forms()
	{
		$this->auth->view('patient/forms');
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
		echo "Your information is updated!";
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
	
	
	// --------------------------------------------------------------------

	function billing_encounters()
	{
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
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
					$charge += $row1['cpt_charge'];
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
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM billing_core WHERE pid=$pid AND eid='0' AND payment='0' AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM billing_core WHERE pid=$pid AND eid='0' AND payment='0' AND practice_id=$practice_idORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			$this->db->where('other_billing_id', $row['other_billing_id']);
			$query2 = $this->db->get('billing_core');
			if ($query2->num_rows() > 0) {
				$charge = $row['cpt_charge'];
				$payment = 0;
				foreach ($query2->result_array() as $row1) {
					$payment += $row1['payment'];
				}
				$row['balance'] = $charge - $payment;
			} else {
				$row['balance'] = 0;
			}
			$response['rows'][$i]['id']=$row['other_billing_id']; 
			$response['rows'][$i]['cell']=array($row['other_billing_id'],$row['dos_f'],$row['reason'],$row['cpt_charge'],$row['balance']);
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
		$query = $this->db->query("SELECT * FROM billing_core JOIN cpt ON billing_core.cpt=cpt.cpt WHERE eid=$eid");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM billing_core JOIN cpt ON billing_core.cpt=cpt.cpt WHERE eid=$eid ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
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
			$data['text'] = '<table class="order" cellspacing="10"><tr><th style="width:100">PROCEDURE</th><th style="width:100">MODIFIER</th><th style="width:350">DESCRIPTION</th><th style="width:150">CHARGE</th></tr>';
			$charge = 0;
			$payment = 0;
			foreach ($result1 as $key1 => $value1) {
				$cpt_charge1[$key1]  = $value1['cpt_charge'];
			}
			array_multisort($cpt_charge1, SORT_DESC, $result1);
			foreach ($result1 as $result1a) {
				if ($result1a['cpt']) {
					$this->db->where('cpt', $result1a['cpt']);
					$query2 = $this->db->get('cpt');
					$result2 = $query2->row_array();
					$data['text'] .= '<tr><td>' . $result1a['cpt'] . '</td><td>' . $result1a['modifier'] . '</td><td>' . $result2['cpt_description'] . '</td><td>$' . $result1a['cpt_charge'] . '</td></tr>';
					$charge = $charge + $result1a['cpt_charge'];
				} else {
					$data['text'] .= '<tr><td>Date of Payment:</td><td>' . $result1a['dos_f'] . '</td><td>' . $result1a['payment_type'] . '</td><td>$(' . $result1a['payment'] . ')</td></tr>';
					$payment = $payment + $result1a['payment'];
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
		$practice = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row();
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax . '<br />';
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
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$result = $this->db->get('demographics_notes')->row_array();
		if (is_null($result['billing_notes']) || $result['billing_notes'] == '') {
			$billing_notes = 'Invoice for encounter (Date of Service: ' . $data['encounter_DOS'] . ') printed on ' . $data['date'] . '.';
		} else {
			$billing_notes = $result['billing_notes'] . "\n" . 'Invoice for encounter (Date of Service: ' . $data['encounter_DOS'] . ') printed on ' . $data['date'] . '.';
		}
		$billing_notes_data = array(
			'billing_notes' => $billing_notes
		);
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->update('demographics_notes', $billing_notes_data);
		$this->audit_model->update();
		return $this->load->view('auth/pages/provider/chart/invoice_page',$data, TRUE);
	}
	
	function print_invoice1($eid)
	{
		ini_set('memory_limit','196M');
		$file_path = '/var/www/nosh/invoice.pdf';
		if (file_exists('/var/www/nosh/invoice.pdf')) {
			unlink('/var/www/nosh/invoice.pdf');
		}
		$html = $this->page_invoice1($eid);
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
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
			$data['text'] = '<table class="order" cellspacing="10"><tr><th style="width:100">DATE</th><th style="width:450">REASON</th><th style="width:150">CHARGE</th></tr>';
			$charge = 0;
			$payment = 0;
			$data['text'] .= '<tr><td>' . $result1['dos_f'] . '</td><td>' . $result1['reason'] . '</td><td>$' . $result1['cpt_charge'] . '</td></tr>';
			$charge = $charge + $result1['cpt_charge'];		
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
		$data['title'] = "INVOICE";
		$date = now();
		$data['date'] = date('F jS, Y', $date);
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$result = $this->db->get('demographics_notes')->row_array();
		if (is_null($result['billing_notes']) || $result['billing_notes'] == '') {
			$billing_notes = 'Invoice for ' . $result1['reason'] . ' (Date of Bill: ' . $result1['dos_f'] . ') printed on ' . $data['date'] . '.';
		} else {
			$billing_notes = $result['billing_notes'] . "\n" . 'Invoice for ' . $result1['reason'] . ' (Date of Bill: ' . $result1['dos_f'] . ') printed on ' . $data['date'] . '.';
		}
		$billing_notes_data = array(
			'billing_notes' => $billing_notes
		);
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->update('demographics_notes', $billing_notes_data);
		$this->audit_model->update();
		return $this->load->view('auth/pages/provider/chart/invoice_page2',$data, TRUE);
	}
	
	function print_invoice2($id)
	{
		ini_set('memory_limit','196M');
		$file_path = '/var/www/nosh/invoice.pdf';
		if (file_exists('/var/www/nosh/invoice.pdf')) {
			unlink('/var/www/nosh/invoice.pdf');
		}
		$html = $this->page_invoice2($id);
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
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
			exit();
		} else {
			die('LOGGED! bad file '.$file_path);
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
		$response->userdata['dos_f'] = 'Total Payments:';
		$response->userdata['payment'] = $total;
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
		$response->userdata['dos_f'] = 'Total Payments:';
		$response->userdata['payment'] = $total;
		echo json_encode($response);
		exit( 0 );
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
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('pid');
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND practice_id=$practice_id");
		$i = 0;
		if ($query1->num_rows() > 0) {
			foreach ($query1->result_array() as $row1) {
				$this->db->where('eid', $row1['eid']);
				$query1a = $this->db->get('billing_core');
				if ($query1a->num_rows() > 0) {
					$charge1 = 0;
					$payment1 = 0;
					foreach ($query1a->result_array() as $row1a) {
						$charge1 += $row1a['cpt_charge'];
						$payment1 += $row1a['payment'];
					}
					$balance1 = $charge1 - $payment1;
				} else {
					$balance1 = 0;
				}
				$i++; 
			}
		} else {
			$balance1 = 0;
		}
		$query2 = $this->db->query("SELECT * FROM billing_core WHERE pid=$pid AND eid='0' AND payment='0' AND practice_id=$practice_id");
		$j = 0;
		$charge2 = 0;
		$payment2 = 0;
		if ($query2->num_rows() > 0) {
			foreach ($query2->result_array() as $row2) {
				$charge2 += $row2['cpt_charge'];
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
		$this->db->where('pid', $pid);
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$result = $this->db->get('demographics_notes')->row_array();
		if (is_null($result['billing_notes']) || $result['billing_notes'] == '') {
			echo "";
		} else {
			echo $result['billing_notes'];
		}
		exit( 0 );
	}
	
	// --------------------------------------------------------------------

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
			'other_hippa_id' => 0,
			'practice_id' => $this->session->userdata('practice_id')
		
		);
		$id = $this->chart_model->addHippa($data);
		$this->audit_model->add();
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
	
	function page_intro($pid)
	{
		$row = $this->demographics_model->get($pid)->row_array();
		$dob1 = human_to_unix($row['DOB']);
		$dob = date('m/d/Y', $dob1);
		$gender = ucfirst($this->session->userdata('gender'));
		$header = $row['lastname'] . ', ' . $row['firstname'] . '(DOB: ' . $dob . ', Gender: ' . $gender . ', ID: ' . $pid . ')';
		$data['header'] = strtoupper($header);
		$practice = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row();
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax . '<br />';
		$data['title'] = 'Medical Records';
		return $this->load->view('auth/pages/provider/chart/intro_view',$data, TRUE);
	}
	
	function print_encounter($eid)
	{
		if (file_exists('/var/www/nosh/printchart_final.pdf')) {
			unlink('/var/www/nosh/printchart_final.pdf');
		}
		$filename = '/var/www/nosh/printchart_final.pdf';
		$pid = $this->session->userdata('pid');
		$html = $this->page_intro($pid);
		$html .= $this->encounters_view($eid);
		$html .= '</body></html>';
		ini_set('memory_limit','196M');
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Chart Generated by NOSH EMR');
		$this->mpdf->debug = true;
		$this->mpdf->simpleTables = true;
		$this->mpdf->Output($filename,'F');	
		while(!file_exists('/var/www/nosh/printchart_final.pdf')) {
			sleep(2);
		}
		echo 'OK';
	}
	
	function print_chart($hippa_id)
	{
		if (file_exists('/var/www/nosh/printchart.pdf')) {
			unlink('/var/www/nosh/printchart.pdf');
		}
		if (file_exists('/var/www/nosh/printchart_final.pdf')) {
			unlink('/var/www/nosh/printchart_final.pdf');
		}
		$filename = '/var/www/nosh/printchart.pdf';
		$pid = $this->session->userdata('pid');
		$html = $this->page_intro($pid);
		$this->db->where('pid', $pid);
		$this->db->where('encounter_signed', 'Yes');
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Chart Generated by NOSH EMR');
		$this->mpdf->debug = true;
		$this->mpdf->simpleTables = true;
		$this->mpdf->Output($filename,'F');	
		while(!file_exists($filename)) {
			sleep(2);
		}
		$input = $filename;
		$this->db->where('pid', $pid);
		$this->db->group_by("documents_type"); 
		$this->db->order_by("documents_date", "asc");
		$query3 = $this->db->get('documents');
		if ($query3->num_rows() > 0) {	
			foreach ($query3->result_array() as $row3) {
				$input .= ' ' . $row3['documents_url'];
			}
		}
		$commandpdf1 = "pdftk " . $input . " cat output /var/www/nosh/printchart_final.pdf";
		$commandpdf2 = escapeshellcmd($commandpdf1);
		exec($commandpdf2);
		if (file_exists('/var/www/nosh/printchart_final.pdf')) {
			echo "OK";
		} else {
			echo "Error generating chart!";
		}
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
		echo 'OK';
	}
	
	function view_printchart()
	{
		$file_path = '/var/www/nosh/printchart_final.pdf';
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
	
	function encounters_view($eid)
	{
		$this->lang->load('date');
		$pid = $this->session->userdata('pid');
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$data['eid'] = $eid;
		$dos1 = human_to_unix($encounterInfo->encounter_DOS);
		$data['encounter_DOS'] = date('F jS, Y; h:i A', $dos1);
		$data['encounter_provider'] = $encounterInfo->encounter_provider;
		$date_signed1 = human_to_unix($encounterInfo->date_signed);
		$data['date_signed'] = date('F jS, Y; h:i A', $date_signed1);
		$data['age1'] = $encounterInfo->encounter_age;
		$data['encounter_cc'] = nl2br($encounterInfo->encounter_cc);
		$practiceInfo = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row();	
		$hpiInfo = $this->encounters_model->getHPI($eid)->row();
		if ($hpiInfo) {
			$data['hpi'] = '<br><h3>History of Present Illness:</h3><p>';
			$data['hpi'] .= nl2br($hpiInfo->hpi);
			$data['hpi'] .= '</p>';
		} else {
			$data['hpi'] = '';
		}	
		$rosInfo = $this->encounters_model->getROS($eid)->row();
		if ($rosInfo) {
			$data['ros'] = '<br><h3>Review of Systems:</h3><p>';
			if ($rosInfo->ros_gen != '') {
				$data['ros'] .= '<strong>General: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_gen);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_eye != '') {
				$data['ros'] .= '<strong>Eye: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_eye);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_ent != '') {
				$data['ros'] .= '<strong>Ears, Nose, Throat: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_ent);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_resp != '') {
				$data['ros'] .= '<strong>Respiratory: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_resp);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_cv != '') {
				$data['ros'] .= '<strong>Cardiovascular: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_cv);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_gi != '') {
				$data['ros'] .= '<strong>Gastrointestinal: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_gi);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_gu != '') {
				$data['ros'] .= '<strong>Genitourinary: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_gu);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_mus != '') {
				$data['ros'] .= '<strong>Musculoskeletal: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_mus);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_neuro != '') {
				$data['ros'] .= '<strong>Neurological: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_neuro);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_psych != '') {
				$data['ros'] .= '<strong>Psychological: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_heme != '') {
				$data['ros'] .= '<strong>Hematological, Lymphatic: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_heme);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_endocrine != '') {
				$data['ros'] .= '<strong>Endocrine: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_endocrine);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_skin != '') {
				$data['ros'] .= '<strong>Skin: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_skin);
				$data['ros'] .= '<br />';
			}
			if ($rosInfo->ros_wcc != '') {
				$data['ros'] .= '<strong>Well Child Check: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_wcc);
				$data['ros'] .= '<br />';
			}
			$data['ros'] .= '</p>';
		} else {
			$data['ros'] = '';
		}
		$ohInfo = $this->encounters_model->getOtherHistory($eid)->row();
		if ($ohInfo) {
			$data['oh'] = '<br><h3>Other Pertinent History:</h3><p>';
			if ($ohInfo->oh_pmh != '') {
				$data['oh'] .= '<strong>Past Medical History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_pmh);
				$data['oh'] .= '<br />';
			}
			if ($ohInfo->oh_psh != '') {
				$data['oh'] .= '<strong>Past Surgical History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_psh);
				$data['oh'] .= '<br />';
			}
			if ($ohInfo->oh_fh != '') {
				$data['oh'] .= '<strong>Family History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_fh);
				$data['oh'] .= '<br />';
			}
			if ($ohInfo->oh_sh != '') {
				$data['oh'] .= '<strong>Social History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_sh);
				$data['oh'] .= '<br />';
			}
			if ($ohInfo->oh_etoh != '') {
				$data['oh'] .= '<strong>Alcohol Use: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_etoh);
				$data['oh'] .= '<br />';
			}
			if ($ohInfo->oh_tobacco != '') {
				$data['oh'] .= '<strong>Tobacco Use: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_tobacco);
				$data['oh'] .= '<br />';
			}
			if ($ohInfo->oh_drugs != '') {
				$data['oh'] .= '<strong>Illicit Drug Use: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_drugs);
				$data['oh'] .= '<br />';
			}
			if ($ohInfo->oh_employment != '') {
				$data['oh'] .= '<strong>Employment: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_employment);
				$data['oh'] .= '<br />';
			}
			if ($ohInfo->oh_meds != '') {
				$data['oh'] .= '<strong>Medications: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_meds);
				$data['oh'] .= '<br />';
			}
			if ($ohInfo->oh_supplements != '') {
				$data['oh'] .= '<strong>Supplements: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_supplements);
				$data['oh'] .= '<br />';
			}
			if ($ohInfo->oh_allergies != '') {
				$data['oh'] .= '<strong>Allergies: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_allergies);
				$data['oh'] .= '<br />';
			}
			$data['oh'] .= '</p>';
		} else {
			$data['oh'] = '';
		}	
		$vitalsInfo = $this->encounters_model->getVitals($eid)->row();
		if ($vitalsInfo) {
			$data['vitals'] = '<br><h3>Vital Signs:</h3><p>';
			if ($vitalsInfo->weight != '') {
				$data['vitals'] .= '<strong>Weight: </strong>';
				$data['vitals'] .= $vitalsInfo->weight . ' ' . $practiceInfo->weight_unit . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->height != '') {
				$data['vitals'] .= '<strong>Height: </strong>';
				$data['vitals'] .= $vitalsInfo->height . ' ' . $practiceInfo->height_unit . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->headcircumference != '') {
				$data['vitals'] .= '<strong>Head Circumference: </strong>';
				$data['vitals'] .= $vitalsInfo->headcircumference . ' ' . $practiceInfo->hc_unit . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->BMI != '') {
				$data['vitals'] .= '<strong>Body Mass Index: </strong>';
				$data['vitals'] .= $vitalsInfo->BMI . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->temp != '') {
				$data['vitals'] .= '<strong>Temperature: </strong>';
				$data['vitals'] .= $vitalsInfo->temp . ' ' . $practiceInfo->temp_unit . ', ' . $vitalsInfo->temp_method . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->bp_systolic != '' && $vitalsInfo->bp_diastolic != '') {
				$data['vitals'] .= '<strong>Blood Pressure: </strong>';
				$data['vitals'] .= $vitalsInfo->bp_systolic . '/' . $vitalsInfo->bp_diastolic . ', ' . $vitalsInfo->bp_position . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->pulse != '') {
				$data['vitals'] .= '<strong>Pulse: </strong>';
				$data['vitals'] .= $vitalsInfo->pulse . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->respirations != '') {
				$data['vitals'] .= '<strong>Respirations: </strong>';
				$data['vitals'] .= $vitalsInfo->respirations . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->o2_sat != '') {
				$data['vitals'] .= '<strong>Oxygen Saturations: </strong>';
				$data['vitals'] .= $vitalsInfo->o2_sat . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->vitals_other != '') {
				$data['vitals'] .= '<strong>Notes: </strong>';
				$data['vitals'] .= nl2br($vitalsInfo->vitals_other) . '<br>';
			}
			$data['vitals'] .= '</p>';
		} else {
			$data['vitals'] = '';
		}	
		$peInfo = $this->encounters_model->getPE($eid)->row();
		if ($peInfo) {
			$data['pe'] = '<br><h3>Physical Exam:</h3><p>';
			if ($peInfo->pe_gen1 != '') {
				$data['pe'] .= '<strong>Constitutional: </strong>';
				$data['pe'] .= nl2br($peInfo->pe_gen1);
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_eye1 != '' || $peInfo->pe_eye2 != '' || $peInfo->pe_eye3 != '') {
				$data['pe'] .= '<strong>Eye:</strong>';
				if($peInfo->pe_eye1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_eye1);
				}
				if($peInfo->pe_eye2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_eye2);
				}
				if($peInfo->pe_eye3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_eye3);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_ent1 != '' || $peInfo->pe_ent2 != '' || $peInfo->pe_ent3 != '' || $peInfo->pe_ent4 != '' || $peInfo->pe_ent5 != '' || $peInfo->pe_ent6 != '') {
				$data['pe'] .= '<strong>Ears, Nose, Throat:</strong>';
				if($peInfo->pe_ent1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent1);
				}
				if($peInfo->pe_ent2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent2);
				}
				if($peInfo->pe_ent3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent3);
				}
				if($peInfo->pe_ent4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent4);
				}
				if($peInfo->pe_ent5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent5);
				}
				if($peInfo->pe_ent6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent6);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_neck1 != '' || $peInfo->pe_neck2 != '') {
				$data['pe'] .= '<strong>Neck:</strong>';
				if($peInfo->pe_neck1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neck1);
				}
				if($peInfo->pe_neck2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neck2);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_resp1 != '' || $peInfo->pe_resp2 != '' || $peInfo->pe_resp3 != '' || $peInfo->pe_resp4 != '') {
				$data['pe'] .= '<strong>Respiratory:</strong>';
				if($peInfo->pe_resp1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp1);
				}
				if($peInfo->pe_resp2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp2);
				}
				if($peInfo->pe_resp3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp3);
				}
				if($peInfo->pe_resp4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp4);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_cv1 != '' || $peInfo->pe_cv2 != '' || $peInfo->pe_cv3 != '' || $peInfo->pe_cv4 != '' || $peInfo->pe_cv5 != '' || $peInfo->pe_cv6 != '') {
				$data['pe'] .= '<strong>Cardiovascular:</strong>';
				if($peInfo->pe_cv1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv1);
				}
				if($peInfo->pe_cv2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv2);
				}
				if($peInfo->pe_cv3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv3);
				}
				if($peInfo->pe_cv4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv4);
				}
				if($peInfo->pe_cv5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv5);
				}
				if($peInfo->pe_cv6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv6);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_ch1 != '' || $peInfo->pe_ch2 != '') {
				$data['pe'] .= '<strong>Chest:</strong>';
				if($peInfo->pe_ch1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ch1);
				}
				if($peInfo->pe_ch2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ch2);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_gi1 != '' || $peInfo->pe_gi2 != '' || $peInfo->pe_gi3 != '' || $peInfo->pe_gi4 != '') {
				$data['pe'] .= '<strong>Gastrointestinal:</strong>';
				if($peInfo->pe_gi1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi1);
				}
				if($peInfo->pe_gi2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi2);
				}
				if($peInfo->pe_gi3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi3);
				}
				if($peInfo->pe_gi4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi4);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_gu1 != '' || $peInfo->pe_gu2 != '' || $peInfo->pe_gu3 != '' || $peInfo->pe_gu4 != '' || $peInfo->pe_gu5 != '' || $peInfo->pe_gu6 != '' || $peInfo->pe_gu7 != '' || $peInfo->pe_gu8 != '' || $peInfo->pe_gu9 != '') {
				$data['pe'] .= '<strong>Genitourinary:</strong>';
				if($peInfo->pe_gu1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu1);
				}
				if($peInfo->pe_gu2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu2);
				}
				if($peInfo->pe_gu3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu3);
				}
				if($peInfo->pe_gu4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu4);
				}
				if($peInfo->pe_gu5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu5);
				}
				if($peInfo->pe_gu6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu6);
				}
				if($peInfo->pe_gu7 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu7);
				}
				if($peInfo->pe_gu8 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu8);
				}
				if($peInfo->pe_gu9 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu9);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_lymph1 != '' || $peInfo->pe_lymph2 != '' || $peInfo->pe_lymph3 != '') {
				$data['pe'] .= '<strong>Lymphatic:</strong>';
				if($peInfo->pe_lymph1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_lymph1);
				}
				if($peInfo->pe_lymph2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_lymph2);
				}
				if($peInfo->pe_lymph3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_lymph3);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_ms1 != '' || $peInfo->pe_ms2 != '' || $peInfo->pe_ms3 != '' || $peInfo->pe_ms4 != '' || $peInfo->pe_ms5 != '' || $peInfo->pe_ms6 != '' || $peInfo->pe_ms7 != '' || $peInfo->pe_ms8 != '' || $peInfo->pe_ms9 != '' || $peInfo->pe_ms10 != '' || $peInfo->pe_ms11 != '' || $peInfo->pe_ms12 != '') {
				$data['pe'] .= '<strong>Musculoskeletal:</strong>';
				if($peInfo->pe_ms1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms1);
				}
				if($peInfo->pe_ms2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms2);
				}
				if($peInfo->pe_ms3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms3);
				}
				if($peInfo->pe_ms4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms4);
				}
				if($peInfo->pe_ms5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms5);
				}
				if($peInfo->pe_ms6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms6);
				}
				if($peInfo->pe_ms7 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms7);
				}
				if($peInfo->pe_ms8 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms8);
				}
				if($peInfo->pe_ms9 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms9);
				}
				if($peInfo->pe_ms10 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms10);
				}
				if($peInfo->pe_ms11 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms11);
				}
				if($peInfo->pe_ms12 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms12);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_skin1 != '' || $peInfo->pe_skin2 != '') {
				$data['pe'] .= '<strong>Skin:</strong>';
				if($peInfo->pe_skin1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_skin1);
				}
				if($peInfo->pe_skin2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_skin2);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_neuro1 != '' || $peInfo->pe_neuro2 != '' || $peInfo->pe_neuro3 != '') {
				$data['pe'] .= '<strong>Neurologic:</strong>';
				if($peInfo->pe_neuro1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neuro1);
				}
				if($peInfo->pe_neuro2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neuro2);
				}
				if($peInfo->pe_neuro3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neuro3);
				}
				$data['pe'] .= '<br />';
			}
			if ($peInfo->pe_psych1 != '' || $peInfo->pe_psych2 != '' || $peInfo->pe_psych3 != '' || $peInfo->pe_psych3 != '') {
				$data['pe'] .= '<strong>Psychiatric:</strong>';
				if($peInfo->pe_psych1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych1);
				}
				if($peInfo->pe_psych2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych2);
				}
				if($peInfo->pe_psych3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych3);
				}
				if($peInfo->pe_psych3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych3);
				}
				$data['pe'] .= '<br />';
			}
			$data['pe'] .= '</p>';
		} else {
			$data['pe'] = '';
		}	
		$labsInfo = $this->encounters_model->getLabs($eid)->row();
		if ($labsInfo) {
			$data['labs'] = '<br><h3>Laboratory Testing:</h3><p>';
			if ($labsInfo->labs_ua_urobili != '' || $labsInfo->labs_ua_bilirubin != '' || $labsInfo->labs_ua_ketones != '' || $labsInfo->labs_ua_glucose != '' || $labsInfo->labs_ua_protein != '' || $labsInfo->labs_ua_nitrites != '' || $labsInfo->labs_ua_leukocytes != '' || $labsInfo->labs_ua_blood != '' || $labsInfo->labs_ua_ph != '' || $labsInfo->labs_ua_spgr != '' || $labsInfo->labs_ua_color != '' || $labsInfo->labs_ua_clarity != ''){
				$data['labs'] .= '<strong>Dipstick Urinalysis:</strong><br /><table>';
				if($labsInfo->labs_ua_urobili != '') {
					$data['labs'] .= '<tr><th align=\"left\">Urobilinogen:</th><td align=\"left\">' . $labsInfo->labs_ua_urobili . '</td></tr>';
				}
				if($labsInfo->labs_ua_bilirubin != '') {
					$data['labs'] .= '<tr><th align=\"left\">Bilirubin:</th><td align=\"left\">' . $labsInfo->labs_ua_bilirubin . '</td></tr>';
				}
				if($labsInfo->labs_ua_ketones != '') {
					$data['labs'] .= '<tr><th align=\"left\">Ketones:</th><td align=\"left\">' . $labsInfo->labs_ua_ketones . '</td></tr>';
				}
				if($labsInfo->labs_ua_glucose != '') {
					$data['labs'] .= '<tr><th align=\"left\">Glucose:</th><td align=\"left\">' . $labsInfo->labs_ua_glucose . '</td></tr>';
				}
				if($labsInfo->labs_ua_protein != '') {
					$data['labs'] .= '<tr><th align=\"left\">Protein:</th><td align=\"left\">' . $labsInfo->labs_ua_protein . '</td></tr>';
				}
				if($labsInfo->labs_ua_nitrites != '') {
					$data['labs'] .= '<tr><th align=\"left\">Nitrites:</th><td align=\"left\">' . $labsInfo->labs_ua_nitrites . '</td></tr>';
				}
				if($labsInfo->labs_ua_leukocytes != '') {
					$data['labs'] .= '<tr><th align=\"left\">Leukocytes:</th><td align=\"left\">' . $labsInfo->labs_ua_leukocytes . '</td></tr>';
				}
				if($labsInfo->labs_ua_blood != '') {
					$data['labs'] .= '<tr><th align=\"left\">Blood:</th><td align=\"left\">' . $labsInfo->labs_ua_blood . '</td></tr>';
				}
				if($labsInfo->labs_ua_ph != '') {
					$data['labs'] .= '<tr><th align=\"left\">pH:</th><td align=\"left\">' . $labsInfo->labs_ua_ph . '</td></tr>';
				}
				if($labsInfo->labs_ua_spgr != '') {
					$data['labs'] .= '<tr><th align=\"left\">Specific gravity:</th><td align=\"left\">' . $labsInfo->labs_ua_spgr . '</td></tr>';
				}
				if($labsInfo->labs_ua_color != '') {
					$data['labs'] .= '<tr><th align=\"left\">Color:</th><td align=\"left\">' . $labsInfo->labs_ua_color . '</td></tr>';
				}
				if($labsInfo->labs_ua_clarity != '') {
					$data['labs'] .= '<tr><th align=\"left\">Clarity:</th><td align=\"left\">' . $labsInfo->labs_ua_clarity . '</td></tr>';
				}
				$data['labs'] .= '</table>';
			}
			if ($labsInfo->labs_upt != '') {
				$data['labs'] .= '<strong>Urine HcG: </strong>';
				$data['labs'] .= $labsInfo->labs_upt;
				$data['labs'] .= '<br />';
			} 
			if ($labsInfo->labs_strep != '') {
				$data['labs'] .= '<strong>Rapid Strep: </strong>';
				$data['labs'] .= $labsInfo->labs_strep;
				$data['labs'] .= '<br />';
			} 
			if ($labsInfo->labs_mono != '') {
				$data['labs'] .= '<strong>Mono Spot: </strong>';
				$data['labs'] .= $labsInfo->labs_mono;
				$data['labs'] .= '<br>';
			}
			if ($labsInfo->labs_flu != '') {
				$data['labs'] .= '<strong>Rapid Influenza: </strong>';
				$data['labs'] .= $labsInfo->labs_flu;
				$data['labs'] .= '<br />';
			}
			if ($labsInfo->labs_microscope != '') {
				$data['labs'] .= '<strong>Micrscopy: </strong>';
				$data['labs'] .= nl2br($labsInfo->labs_microscope);
				$data['labs'] .= '<br />';
			}
			if ($labsInfo->labs_glucose != '') {
				$data['labs'] .= '<strong>Fingerstick Glucose: </strong>';
				$data['labs'] .= $labsInfo->labs_glucose;
				$data['labs'] .= '<br />';
			}
			if ($labsInfo->labs_other != '') {
				$data['labs'] .= '<strong>Other: </strong>';
				$data['labs'] .= nl2br($labsInfo->labs_other);
				$data['labs'] .= '<br />';
			}
			$data['labs'] .= '</p>';
		} else {
			$data['labs'] = '';
		}		
		$procedureInfo = $this->encounters_model->getProcedure($eid)->row();
		if ($procedureInfo) {
			$data['procedure'] = '<br><h3>Procedures:</h3><p>';
			if ($procedureInfo->proc_type != '') {
				$data['procedure'] .= '<strong>Procedure: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_type);
				$data['procedure'] .= '<br />';
			}
			if ($procedureInfo->proc_description != '') {
				$data['procedure'] .= '<strong>Description of Procedure: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_description);
				$data['procedure'] .= '<br />';
			}
			if ($procedureInfo->proc_complications != '') {
				$data['procedure'] .= '<strong>Complications: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_complications);
				$data['procedure'] .= '<br />';
			}
			if ($procedureInfo->proc_ebl != '') {
				$data['procedure'] .= '<strong>Estimated Blood Loss: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_ebl);
				$data['procedure'] .= '<br />';
			}
			$data['procedure'] .= '</p>';
		} else {
			$data['procedure'] = '';
		}
		$assessmentInfo = $this->encounters_model->getAssessment($eid)->row();
		if ($assessmentInfo) {
			$data['assessment'] = '<br><h3>Assessment:</h3><p>';
			if ($assessmentInfo->assessment_1 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_1 . '</strong><br />';
				if ($assessmentInfo->assessment_2 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_2 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_2 . '</strong><br />';
				if ($assessmentInfo->assessment_3 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_3 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_3 . '</strong><br />';
				if ($assessmentInfo->assessment_4 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_4 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_4 . '</strong><br />';
				if ($assessmentInfo->assessment_5 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_5 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_5 . '</strong><br />';
				if ($assessmentInfo->assessment_6 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_6 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_6 . '</strong><br />';
				if ($assessmentInfo->assessment_7 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_7 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_7 . '</strong><br />';
				if ($assessmentInfo->assessment_8 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_8 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_8 . '</strong><br /><br />';
			}
			if ($assessmentInfo->assessment_other != '') {
				$data['assessment'] .= '<strong>Additional Diagnoses: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo->assessment_other);
				$data['assessment'] .= '<br />';
			}
			if ($assessmentInfo->assessment_ddx != '') {
				$data['assessment'] .= '<strong>Differential Diagnoses Considered: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo->assessment_ddx);
				$data['assessment'] .= '<br />';
			}
			if ($assessmentInfo->assessment_notes != '') {
				$data['assessment'] .= '<strong>Assessment Discussion: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo->assessment_notes);
				$data['assessment'] .= '<br />';
			}
			$data['assessment'] .= '</p>';
		} else {
			$data['assessment'] = '';
		}		
		$ordersInfo = $this->encounters_model->getOrders($eid)->row();
		if ($ordersInfo) {
			$data['orders'] = '<br><h3>Orders:</h3><p>';
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
		$rxInfo = $this->encounters_model->getRX($eid)->row();
		if ($rxInfo) {
			$data['rx'] = '<br><h3>Prescriptions and Immunizations:</h3><p>';
			if ($rxInfo->rx_rx!= '') {
				$data['rx'] .= '<strong>Prescriptions Given: </strong>';
				$data['rx'] .= nl2br($rxInfo->rx_rx);
				$data['rx'] .= '<br />';
			}
			if ($rxInfo->rx_supplements!= '') {
				$data['rx'] .= '<strong>Supplements Recommended: </strong>';
				$data['rx'] .= nl2br($rxInfo->rx_supplements);
				$data['rx'] .= '<br />';
			}
			if ($rxInfo->rx_immunizations != '') {
				$data['rx'] .= '<strong>Immunizations Given: </strong>';
				$data['rx'] .= 'CDC Vaccine Information Sheets given for each immunization and consent obtained.<br />';
				$data['rx'] .= nl2br($rxInfo->rx_immunizations);
				$data['rx'] .= '<br />';
			}
			$data['rx'] .= '</p>';
		} else {
			$data['rx'] = '';
		}		
		$planInfo = $this->encounters_model->getPlan($eid)->row();
		if ($planInfo) {
			$data['plan'] = '<br><h3>Plan:</h3><p>';
			if ($planInfo->plan!= '') {
				$data['plan'] .= '<strong>Recommendations: </strong>';
				$data['plan'] .= nl2br($planInfo->plan);
				$data['plan'] .= '<br />';
			}
			if ($planInfo->followup != '') {
				$data['plan'] .= '<strong>Followup: </strong>';
				$data['plan'] .= nl2br($planInfo->followup);
				$data['plan'] .= '<br />';
			}
			if ($planInfo->duration != '') {
				$data['plan'] .= 'Counseling and face-to-face time consists of more than 50 percent of the visit.  Total face-to-face time is ';
				$data['plan'] .= $planInfo->duration . ' minutes.';
				$data['plan'] .= '<br />';
			}
			$data['plan'] .= '</p>';
		} else {
			$data['plan'] = '';
		}		
		$billing_query = $this->encounters_model->getBillingCore($eid);
		if ($billing_query->num_rows() > 0) {
			$data['billing'] = '<p>';
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
			$billingInfo = $this->encounters_model->getBilling($eid)->row();
			if ($billingInfo->bill_complex != '') {
				$data['billing'] .= '<br><strong>Medical Complexity: </strong>';
				$data['billing'] .= nl2br($billingInfo->bill_complex);
				$data['billing'] .= '<br />';
			}
			$data['billing'] .= '</p>';
		} else {
			$data['billing'] = '';
		}
		return $this->load->view('auth/pages/patient/encounters_view', $data, TRUE);
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
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$data['eid'] = $eid;
		$dos1 = human_to_unix($encounterInfo->encounter_DOS);
		$data['encounter_DOS'] = date('F jS, Y', $dos1);
		$data['encounter_provider'] = $encounterInfo->encounter_provider;
		if ($encounterInfo->encounter_signed == 'No') {
			$data['status']	= 'Draft';
		} else {
			$date_signed1 = human_to_unix($encounterInfo->date_signed);
			$date_signed = date('F jS, Y', $date_signed1);
			$data['status'] = 'Signed on ' . $date_signed . '.';
		}
		$data['age1'] = $encounterInfo->encounter_age;
		$data['encounter_cc'] = nl2br($encounterInfo->encounter_cc);	
		$practiceInfo = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row();
		$data['practiceName'] = $practiceInfo->practice_name;
		$data['website'] = $practiceInfo->website;
		$data['practiceInfo'] = $practiceInfo->street_address1;
		if ($practiceInfo->street_address2 != '') {
			$data['practiceInfo'] .= ', ' . $practiceInfo->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practiceInfo->city . ', ' . $practiceInfo->state . ' ' . $practiceInfo->zip . '<br />';
		$data['patientInfo'] = $this->demographics_model->get($pid)->row();
		$dob1 = human_to_unix($data['patientInfo']->DOB);
		$data['dob'] = date('F jS, Y', $dob1);
		$data['age'] = $this->session->userdata('age');
		$data['gender'] = ucfirst($this->session->userdata('gender'));	
		$hpiInfo = $this->encounters_model->getHPI($eid)->row();
		if ($hpiInfo) {
			$data['hpi'] = '<br><h4>History of Present Illness:</h4><p class="view">';
			$data['hpi'] .= nl2br($hpiInfo->hpi);
			$data['hpi'] .= '</p>';
		} else {
			$data['hpi'] = '';
		}	
		$rosInfo = $this->encounters_model->getROS($eid)->row();
		if ($rosInfo) {
			$data['ros'] = '<br><h4>Review of Systems:</h4><p class="view">';
			if ($rosInfo->ros_gen != '') {
				$data['ros'] .= '<strong>General: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_gen);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_eye != '') {
				$data['ros'] .= '<strong>Eye: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_eye);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_ent != '') {
				$data['ros'] .= '<strong>Ears, Nose, Throat: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_ent);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_resp != '') {
				$data['ros'] .= '<strong>Respiratory: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_resp);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_cv != '') {
				$data['ros'] .= '<strong>Cardiovascular: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_cv);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_gi != '') {
				$data['ros'] .= '<strong>Gastrointestinal: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_gi);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_gu != '') {
				$data['ros'] .= '<strong>Genitourinary: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_gu);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_mus != '') {
				$data['ros'] .= '<strong>Musculoskeletal: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_mus);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_neuro != '') {
				$data['ros'] .= '<strong>Neurological: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_neuro);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych != '') {
				$data['ros'] .= '<strong>Psychological: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_heme != '') {
				$data['ros'] .= '<strong>Hematological, Lymphatic: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_heme);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_endocrine != '') {
				$data['ros'] .= '<strong>Endocrine: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_endocrine);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_skin != '') {
				$data['ros'] .= '<strong>Skin: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_skin);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_wcc != '') {
				$data['ros'] .= '<strong>Well Child Check: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_wcc);
				$data['ros'] .= '<br /><br />';
			}
			$data['ros'] .= '</p>';
		} else {
			$data['ros'] = '';
		}
		$ohInfo = $this->encounters_model->getOtherHistory($eid)->row();
		if ($ohInfo) {
			$data['oh'] = '<br><h4>Other Pertinent History:</h4><p class="view">';
			if ($ohInfo->oh_pmh != '') {
				$data['oh'] .= '<strong>Past Medical History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_pmh);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_psh != '') {
				$data['oh'] .= '<strong>Past Surgical History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_psh);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_fh != '') {
				$data['oh'] .= '<strong>Family History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_fh);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_sh != '') {
				$data['oh'] .= '<strong>Social History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_sh);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_etoh != '') {
				$data['oh'] .= '<strong>Alcohol Use: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_etoh);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_tobacco != '') {
				$data['oh'] .= '<strong>Tobacco Use: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_tobacco);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_drugs != '') {
				$data['oh'] .= '<strong>Illicit Drug Use: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_drugs);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_employment != '') {
				$data['oh'] .= '<strong>Employment: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_employment);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_meds != '') {
				$data['oh'] .= '<strong>Medications: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_meds);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_supplements != '') {
				$data['oh'] .= '<strong>Supplements: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_supplements);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_allergies != '') {
				$data['oh'] .= '<strong>Allergies: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_allergies);
				$data['oh'] .= '<br /><br />';
			}
			$data['oh'] .= '</p>';
		} else {
			$data['oh'] = '';
		}	
		$vitalsInfo = $this->encounters_model->getVitals($eid)->row();
		if ($vitalsInfo) {
			$data['vitals'] = '<br><h4>Vital Signs:</h4><p class="view">';
			if ($vitalsInfo->weight != '') {
				$data['vitals'] .= '<strong>Weight: </strong>';
				$data['vitals'] .= $vitalsInfo->weight . ' ' . $practiceInfo->weight_unit . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->height != '') {
				$data['vitals'] .= '<strong>Height: </strong>';
				$data['vitals'] .= $vitalsInfo->height . ' ' . $practiceInfo->height_unit . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->headcircumference != '') {
				$data['vitals'] .= '<strong>Head Circumference: </strong>';
				$data['vitals'] .= $vitalsInfo->headcircumference . ' ' . $practiceInfo->hc_unit . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->BMI != '') {
				$data['vitals'] .= '<strong>Body Mass Index: </strong>';
				$data['vitals'] .= $vitalsInfo->BMI . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->temp != '') {
				$data['vitals'] .= '<strong>Temperature: </strong>';
				$data['vitals'] .= $vitalsInfo->temp . ' ' . $practiceInfo->temp_unit . ', ' . $vitalsInfo->temp_method . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->bp_systolic != '' && $vitalsInfo->bp_diastolic != '') {
				$data['vitals'] .= '<strong>Blood Pressure: </strong>';
				$data['vitals'] .= $vitalsInfo->bp_systolic . '/' . $vitalsInfo->bp_diastolic . ', ' . $vitalsInfo->bp_position . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->pulse != '') {
				$data['vitals'] .= '<strong>Pulse: </strong>';
				$data['vitals'] .= $vitalsInfo->pulse . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->respirations != '') {
				$data['vitals'] .= '<strong>Respirations: </strong>';
				$data['vitals'] .= $vitalsInfo->respirations . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->o2_sat != '') {
				$data['vitals'] .= '<strong>Oxygen Saturations: </strong>';
				$data['vitals'] .= $vitalsInfo->o2_sat . '<br>';
			} else {
				$data['vitals'] .= '';
			}
			if ($vitalsInfo->vitals_other != '') {
				$data['vitals'] .= '<strong>Notes: </strong>';
				$data['vitals'] .= nl2br($vitalsInfo->vitals_other) . '<br>';
			}
			$data['vitals'] .= '</p>';
		} else {
			$data['vitals'] = '';
		}	
		$peInfo = $this->encounters_model->getPE($eid)->row();
		if ($peInfo) {
			$data['pe'] = '<br><h4>Physical Exam:</h4><p class="view">';
			if ($peInfo->pe_gen1 != '') {
				$data['pe'] .= '<strong>Constitutional: </strong>';
				$data['pe'] .= nl2br($peInfo->pe_gen1);
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_eye1 != '' || $peInfo->pe_eye2 != '' || $peInfo->pe_eye3 != '') {
				$data['pe'] .= '<strong>Eye:</strong>';
				if($peInfo->pe_eye1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_eye1);
				}
				if($peInfo->pe_eye2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_eye2);
				}
				if($peInfo->pe_eye3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_eye3);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_ent1 != '' || $peInfo->pe_ent2 != '' || $peInfo->pe_ent3 != '' || $peInfo->pe_ent4 != '' || $peInfo->pe_ent5 != '' || $peInfo->pe_ent6 != '') {
				$data['pe'] .= '<strong>Ears, Nose, Throat:</strong>';
				if($peInfo->pe_ent1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent1);
				}
				if($peInfo->pe_ent2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent2);
				}
				if($peInfo->pe_ent3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent3);
				}
				if($peInfo->pe_ent4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent4);
				}
				if($peInfo->pe_ent5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent5);
				}
				if($peInfo->pe_ent6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent6);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_neck1 != '' || $peInfo->pe_neck2 != '') {
				$data['pe'] .= '<strong>Neck:</strong>';
				if($peInfo->pe_neck1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neck1);
				}
				if($peInfo->pe_neck2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neck2);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_resp1 != '' || $peInfo->pe_resp2 != '' || $peInfo->pe_resp3 != '' || $peInfo->pe_resp4 != '') {
				$data['pe'] .= '<strong>Respiratory:</strong>';
				if($peInfo->pe_resp1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp1);
				}
				if($peInfo->pe_resp2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp2);
				}
				if($peInfo->pe_resp3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp3);
				}
				if($peInfo->pe_resp4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp4);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_cv1 != '' || $peInfo->pe_cv2 != '' || $peInfo->pe_cv3 != '' || $peInfo->pe_cv4 != '' || $peInfo->pe_cv5 != '' || $peInfo->pe_cv6 != '') {
				$data['pe'] .= '<strong>Cardiovascular:</strong>';
				if($peInfo->pe_cv1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv1);
				}
				if($peInfo->pe_cv2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv2);
				}
				if($peInfo->pe_cv3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv3);
				}
				if($peInfo->pe_cv4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv4);
				}
				if($peInfo->pe_cv5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv5);
				}
				if($peInfo->pe_cv6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv6);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_ch1 != '' || $peInfo->pe_ch2 != '') {
				$data['pe'] .= '<strong>Chest:</strong>';
				if($peInfo->pe_ch1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ch1);
				}
				if($peInfo->pe_ch2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ch2);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_gi1 != '' || $peInfo->pe_gi2 != '' || $peInfo->pe_gi3 != '' || $peInfo->pe_gi4 != '') {
				$data['pe'] .= '<strong>Gastrointestinal:</strong>';
				if($peInfo->pe_gi1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi1);
				}
				if($peInfo->pe_gi2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi2);
				}
				if($peInfo->pe_gi3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi3);
				}
				if($peInfo->pe_gi4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi4);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_gu1 != '' || $peInfo->pe_gu2 != '' || $peInfo->pe_gu3 != '' || $peInfo->pe_gu4 != '' || $peInfo->pe_gu5 != '' || $peInfo->pe_gu6 != '' || $peInfo->pe_gu7 != '' || $peInfo->pe_gu8 != '' || $peInfo->pe_gu9 != '') {
				$data['pe'] .= '<strong>Genitourinary:</strong>';
				if($peInfo->pe_gu1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu1);
				}
				if($peInfo->pe_gu2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu2);
				}
				if($peInfo->pe_gu3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu3);
				}
				if($peInfo->pe_gu4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu4);
				}
				if($peInfo->pe_gu5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu5);
				}
				if($peInfo->pe_gu6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu6);
				}
				if($peInfo->pe_gu7 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu7);
				}
				if($peInfo->pe_gu8 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu8);
				}
				if($peInfo->pe_gu9 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu9);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_lymph1 != '' || $peInfo->pe_lymph2 != '' || $peInfo->pe_lymph3 != '') {
				$data['pe'] .= '<strong>Lymphatic:</strong>';
				if($peInfo->pe_lymph1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_lymph1);
				}
				if($peInfo->pe_lymph2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_lymph2);
				}
				if($peInfo->pe_lymph3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_lymph3);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_ms1 != '' || $peInfo->pe_ms2 != '' || $peInfo->pe_ms3 != '' || $peInfo->pe_ms4 != '' || $peInfo->pe_ms5 != '' || $peInfo->pe_ms6 != '' || $peInfo->pe_ms7 != '' || $peInfo->pe_ms8 != '' || $peInfo->pe_ms9 != '' || $peInfo->pe_ms10 != '' || $peInfo->pe_ms11 != '' || $peInfo->pe_ms12 != '') {
				$data['pe'] .= '<strong>Musculoskeletal:</strong>';
				if($peInfo->pe_ms1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms1);
				}
				if($peInfo->pe_ms2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms2);
				}
				if($peInfo->pe_ms3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms3);
				}
				if($peInfo->pe_ms4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms4);
				}
				if($peInfo->pe_ms5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms5);
				}
				if($peInfo->pe_ms6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms6);
				}
				if($peInfo->pe_ms7 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms7);
				}
				if($peInfo->pe_ms8 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms8);
				}
				if($peInfo->pe_ms9 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms9);
				}
				if($peInfo->pe_ms10 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms10);
				}
				if($peInfo->pe_ms11 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms11);
				}
				if($peInfo->pe_ms12 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms12);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_skin1 != '' || $peInfo->pe_skin2 != '') {
				$data['pe'] .= '<strong>Skin:</strong>';
				if($peInfo->pe_skin1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_skin1);
				}
				if($peInfo->pe_skin2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_skin2);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_neuro1 != '' || $peInfo->pe_neuro2 != '' || $peInfo->pe_neuro3 != '') {
				$data['pe'] .= '<strong>Neurologic:</strong>';
				if($peInfo->pe_neuro1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neuro1);
				}
				if($peInfo->pe_neuro2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neuro2);
				}
				if($peInfo->pe_neuro3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neuro3);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_psych1 != '' || $peInfo->pe_psych2 != '' || $peInfo->pe_psych3 != '' || $peInfo->pe_psych4 != '') {
				$data['pe'] .= '<strong>Psychiatric:</strong>';
				if($peInfo->pe_psych1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych1);
				}
				if($peInfo->pe_psych2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych2);
				}
				if($peInfo->pe_psych3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych3);
				}
				if($peInfo->pe_psych4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych4);
				}
				$data['pe'] .= '<br /><br />';
			}
			$data['pe'] .= '</p>';
		} else {
			$data['pe'] = '';
		}	
		$labsInfo = $this->encounters_model->getLabs($eid)->row();
		if ($labsInfo) {
			$data['labs'] = '<br><h4>Laboratory Testing:</h4><p class="view">';
			if ($labsInfo->labs_ua_urobili != '' || $labsInfo->labs_ua_bilirubin != '' || $labsInfo->labs_ua_ketones != '' || $labsInfo->labs_ua_glucose != '' || $labsInfo->labs_ua_protein != '' || $labsInfo->labs_ua_nitrites != '' || $labsInfo->labs_ua_leukocytes != '' || $labsInfo->labs_ua_blood != '' || $labsInfo->labs_ua_ph != '' || $labsInfo->labs_ua_spgr != '' || $labsInfo->labs_ua_color != '' || $labsInfo->labs_ua_clarity != ''){
				$data['labs'] .= '<strong>Dipstick Urinalysis:</strong><br /><table>';
				if($labsInfo->labs_ua_urobili != '') {
					$data['labs'] .= '<tr><th align=\"left\">Urobilinogen:</th><td align=\"left\">' . $labsInfo->labs_ua_urobili . '</td></tr>';
				}
				if($labsInfo->labs_ua_bilirubin != '') {
					$data['labs'] .= '<tr><th align=\"left\">Bilirubin:</th><td align=\"left\">' . $labsInfo->labs_ua_bilirubin . '</td></tr>';
				}
				if($labsInfo->labs_ua_ketones != '') {
					$data['labs'] .= '<tr><th align=\"left\">Ketones:</th><td align=\"left\">' . $labsInfo->labs_ua_ketones . '</td></tr>';
				}
				if($labsInfo->labs_ua_glucose != '') {
					$data['labs'] .= '<tr><th align=\"left\">Glucose:</th><td align=\"left\">' . $labsInfo->labs_ua_glucose . '</td></tr>';
				}
				if($labsInfo->labs_ua_protein != '') {
					$data['labs'] .= '<tr><th align=\"left\">Protein:</th><td align=\"left\">' . $labsInfo->labs_ua_protein . '</td></tr>';
				}
				if($labsInfo->labs_ua_nitrites != '') {
					$data['labs'] .= '<tr><th align=\"left\">Nitrites:</th><td align=\"left\">' . $labsInfo->labs_ua_nitrites . '</td></tr>';
				}
				if($labsInfo->labs_ua_leukocytes != '') {
					$data['labs'] .= '<tr><th align=\"left\">Leukocytes:</th><td align=\"left\">' . $labsInfo->labs_ua_leukocytes . '</td></tr>';
				}
				if($labsInfo->labs_ua_blood != '') {
					$data['labs'] .= '<tr><th align=\"left\">Blood:</th><td align=\"left\">' . $labsInfo->labs_ua_blood . '</td></tr>';
				}
				if($labsInfo->labs_ua_ph != '') {
					$data['labs'] .= '<tr><th align=\"left\">pH:</th><td align=\"left\">' . $labsInfo->labs_ua_ph . '</td></tr>';
				}
				if($labsInfo->labs_ua_spgr != '') {
					$data['labs'] .= '<tr><th align=\"left\">Specific gravity:</th><td align=\"left\">' . $labsInfo->labs_ua_spgr . '</td></tr>';
				}
				if($labsInfo->labs_ua_color != '') {
					$data['labs'] .= '<tr><th align=\"left\">Color:</th><td align=\"left\">' . $labsInfo->labs_ua_color . '</td></tr>';
				}
				if($labsInfo->labs_ua_clarity != '') {
					$data['labs'] .= '<tr><th align=\"left\">Clarity:</th><td align=\"left\">' . $labsInfo->labs_ua_clarity . '</td></tr>';
				}
				$data['labs'] .= '</table>';
			}
			if ($labsInfo->labs_upt != '') {
				$data['labs'] .= '<strong>Urine HcG: </strong>';
				$data['labs'] .= $labsInfo->labs_upt;
				$data['labs'] .= '<br /><br />';
			} 
			if ($labsInfo->labs_strep != '') {
				$data['labs'] .= '<strong>Rapid Strep: </strong>';
				$data['labs'] .= $labsInfo->labs_strep;
				$data['labs'] .= '<br /><br />';
			} 
			if ($labsInfo->labs_mono != '') {
				$data['labs'] .= '<strong>Mono Spot: </strong>';
				$data['labs'] .= $labsInfo->labs_mono;
				$data['labs'] .= '<br>';
			}
			if ($labsInfo->labs_flu != '') {
				$data['labs'] .= '<strong>Rapid Influenza: </strong>';
				$data['labs'] .= $labsInfo->labs_flu;
				$data['labs'] .= '<br /><br />';
			}
			if ($labsInfo->labs_microscope != '') {
				$data['labs'] .= '<strong>Micrscopy: </strong>';
				$data['labs'] .= nl2br($labsInfo->labs_microscope);
				$data['labs'] .= '<br /><br />';
			}
			if ($labsInfo->labs_glucose != '') {
				$data['labs'] .= '<strong>Fingerstick Glucose: </strong>';
				$data['labs'] .= $labsInfo->labs_glucose;
				$data['labs'] .= '<br /><br />';
			}
			if ($labsInfo->labs_other != '') {
				$data['labs'] .= '<strong>Other: </strong>';
				$data['labs'] .= nl2br($labsInfo->labs_other);
				$data['labs'] .= '<br /><br />';
			}
			$data['labs'] .= '</p>';
		} else {
			$data['labs'] = '';
		}		
		$procedureInfo = $this->encounters_model->getProcedure($eid)->row();
		if ($procedureInfo) {
			$data['procedure'] = '<br><h4>Procedures:</h4><p class="view">';
			if ($procedureInfo->proc_type != '') {
				$data['procedure'] .= '<strong>Procedure: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_type);
				$data['procedure'] .= '<br /><br />';
			}
			if ($procedureInfo->proc_description != '') {
				$data['procedure'] .= '<strong>Description of Procedure: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_description);
				$data['procedure'] .= '<br /><br />';
			}
			if ($procedureInfo->proc_complications != '') {
				$data['procedure'] .= '<strong>Complications: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_complications);
				$data['procedure'] .= '<br /><br />';
			}
			if ($procedureInfo->proc_ebl != '') {
				$data['procedure'] .= '<strong>Estimated Blood Loss: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_ebl);
				$data['procedure'] .= '<br /><br />';
			}
			$data['procedure'] .= '</p>';
		} else {
			$data['procedure'] = '';
		}
		$assessmentInfo = $this->encounters_model->getAssessment($eid)->row();
		if ($assessmentInfo) {
			$data['assessment'] = '<br><h4>Assessment:</h4><p class="view">';
			if ($assessmentInfo->assessment_1 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_1 . '</strong><br />';
				if ($assessmentInfo->assessment_2 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_2 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_2 . '</strong><br />';
				if ($assessmentInfo->assessment_3 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_3 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_3 . '</strong><br />';
				if ($assessmentInfo->assessment_4 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_4 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_4 . '</strong><br />';
				if ($assessmentInfo->assessment_5 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_5 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_5 . '</strong><br />';
				if ($assessmentInfo->assessment_6 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_6 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_6 . '</strong><br />';
				if ($assessmentInfo->assessment_7 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_7 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_7 . '</strong><br />';
				if ($assessmentInfo->assessment_8 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_8 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_8 . '</strong><br /><br />';
			}
			if ($assessmentInfo->assessment_other != '') {
				$data['assessment'] .= '<strong>Additional Diagnoses: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo->assessment_other);
				$data['assessment'] .= '<br /><br />';
			}
			if ($assessmentInfo->assessment_ddx != '') {
				$data['assessment'] .= '<strong>Differential Diagnoses Considered: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo->assessment_ddx);
				$data['assessment'] .= '<br /><br />';
			}
			if ($assessmentInfo->assessment_notes != '') {
				$data['assessment'] .= '<strong>Assessment Discussion: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo->assessment_notes);
				$data['assessment'] .= '<br /><br />';
			}
			$data['assessment'] .= '</p>';
		} else {
			$data['assessment'] = '';
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
			if ($planInfo->duration != '') {
				$data['plan'] .= 'Counseling and face-to-face time consists of more than 50 percent of the visit.  Total face-to-face time is ';
				$data['plan'] .= $planInfo->duration . ' minutes.';
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
			$billingInfo = $this->encounters_model->getBilling($eid)->row();
			if ($billingInfo->bill_complex != '') {
				$data['billing'] .= '<br><strong>Medical Complexity: </strong>';
				$data['billing'] .= nl2br($billingInfo->bill_complex);
				$data['billing'] .= '<br /><br />';
			}
			$data['billing'] .= '</p>';
		} else {
			$data['billing'] = '';
		}
		$data['title'] = 'Encounter Details';
		$this->load->view('auth/pages/patient/modal_view', $data);
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
	
	function forms_grid()
	{
		$pid = $this->session->userdata('pid');
		$age = $this->session->userdata('agealldays');
		$this->db->where('pid', $pid);
		$demo_row = $this->db->get('demographics')->row_array();
		$sex = $demo_row['sex'];
		$dob = human_to_unix($demo_row['DOB']);
		$now = time();
		$agediff = ($now - $dob)/86400;
		$pos4 = strpos($agediff, '.');
		$age1 = substr($agediff, 0, $pos4);
		if ($age1 > 6574.5) {
			$age = 'adult';
		} else {
			$age = 'child';
		}
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		if ($age1 > 6574.5) {
			$query = $this->db->query("SELECT * FROM templates WHERE category='forms' AND sex='$sex' AND age!='child'");
		} else {
			$query = $this->db->query("SELECT * FROM templates WHERE category='forms' AND sex='$sex' AND age!='adult'");
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
		if ($age1 > 6574.5) {
			$query1 = $this->db->query("SELECT * FROM templates WHERE category='forms' AND sex='$sex' AND age!='child' ORDER BY $sidx $sord LIMIT $start , $limit");
		} else {
			$query1 = $this->db->query("SELECT * FROM templates WHERE category='forms' AND sex='$sex' AND age!='adult' ORDER BY $sidx $sord LIMIT $start , $limit");
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$records1 = array();
		$i=0;
		foreach ($records as $records_row) {
			$records1[$i]['template_id'] = $records_row['template_id'];
			$records1[$i]['template_name'] = $records_row['template_name'];
			$this->db->where('template_id', $records_row['template_id']);
			$this->db->where('pid', $pid);
			$this->db->order_by('forms_date', 'desc');
			$this->db->limit(1);
			$query2 = $this->db->get('forms');
			if ($query2->num_rows() > 0) {
				$row2 = $query2->row_array();
				if ($records_row['array'] == $row2['array']) {
					$records1[$i]['forms_id'] = $row2['forms_id'];
					$records1[$i]['forms_date'] = $row2['forms_date'];
				} else {
					$records1[$i]['forms_id'] = '';
					$records1[$i]['forms_date'] = '';
				}
			} else {
				$records1[$i]['forms_id'] = '';
				$records1[$i]['forms_date'] = '';
			}
			$i++;
		}
		$response['rows'] = $records1;
		echo json_encode($response);
		exit( 0 );
	}
	
	function get_form($id)
	{
		$this->db->where('template_id', $id);
		$row = $this->db->get('templates')->row_array();
		$data = unserialize($row['array']);
		echo $data;
	}
	
	function get_form_data($id)
	{
		$this->db->where('forms_id', $id);
		$row = $this->db->get('forms')->row_array();
		$data = unserialize($row['forms_content']);
		echo $data;
	}
	
	function save_form_data()
	{
		$pid = $this->session->userdata('pid');
		$data = array(
			'pid' => $pid,
			'template_id' => $this->input->post('template_id'),
			'forms_title' => $this->input->post('forms_title'),
			'forms_content' => serialize($this->input->post('forms_content')),
			'forms_destination' => $this->input->post('forms_destination'),
			'forms_content_text' => $this->input->post('forms_content_text'),
			'array' => serialize($this->input->post('array'))
		);
		if ($this->input->post('forms_id') != '') {
			$this->db->where('forms_id', $this->input->post('forms_id'));
			$this->db->update('forms', $data);
			$this->audit_model->update();
		} else {
			$this->db->insert('forms', $data);
			$this->audit_model->add();
		}
		echo ("Form saved and submitted to your provider!");
		exit (0);
	}
	
	function tests()
	{
		$pid = $this->session->userdata('pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord'); 
		$query = $this->db->query("SELECT * FROM tests WHERE pid=$pid");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM tests WHERE pid=$pid ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
}
/* End of file: chartmenu.php */
/* Location: application/controllers/provider/chartmenu.php */
