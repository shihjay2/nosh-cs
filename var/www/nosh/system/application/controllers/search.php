<?php

class Search extends Application
{

	function Search()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->load->model('demographics_model');
		$this->load->model('practiceinfo_model');
	}

	// --------------------------------------------------------------------

	function index()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('lastname', $q);
		$this->db->or_like('firstname', $q);
		$this->db->or_like('pid', $q);
		$this->db->select('lastname, firstname, DOB, pid');
		$query = $this->db->get('demographics');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$dob1 = $row['DOB'];
				$dob2 = strtotime($dob1);
				$datestring = "%m/%d/%Y";
				$dob = mdate($datestring, $dob2);
				$id = $row['pid'];
				$name =  $row['lastname'] . ', ' . $row['firstname'] . ' (DOB: ' . $dob . ') (ID: ' . $row['pid'] . ')';
				$data['message'][] = array(
					'id' => $id,
					'label' => $name,
					'value' => $name
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function loadpage()
	{
		$user = '';
		if(user_group('admin')) {
			$user = 'admin';
		} 
		if(user_group('assistant')) {
			$user = 'assistant';
		} 
		if(user_group('provider')) {
			$user = 'provider';
		} 
		if(user_group('billing')) {
			$user = 'billing';
		}	
		$pid = $this->session->userdata('pid');
		if ($pid == '') {
			$pt = '';
		} else {
			$demographics = $this->demographics_model->get($pid);
			$row = $demographics->row(); 
			$pt = $row->firstname . ' ' . $row->lastname;;
		}	
		$eid = $this->session->userdata('eid');
		if ($eid == '') {
			$encounter = '';
		} else {
			$encounter = $eid;
		}
		$data['pt'] = $pt;
		$data['user'] = $user;
		$data['encounter'] = $encounter;
		$this->load->view('auth/pages/search', $data);
	}
	
	function openchart()
	{
		$oldpid = $this->session->userdata('pid');
		if ($oldpid != '') {
			$this->session->unset_userdata('age');
			$this->session->unset_userdata('ptname');
			$this->session->unset_userdata('agealldays');
			$this->session->unset_userdata('gender');
			$this->session->unset_userdata('pid');
			$this->session->unset_userdata('eid');
		}	
		$pid = $this->input->post('pid');
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row(); 
		$data['ptname'] = $row->firstname . ' ' . $row->lastname;
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
	}	
	
	function openchart1()
	{		
		if(user_group('admin')) {
			redirect('admin/chartmenu');
		}
		if(user_group('provider')) {
			redirect('provider/chartmenu');
		}
		if(user_group('assistant')) {
			redirect('assistant/chartmenu');
		}
		if(user_group('billing')) {
			redirect('billing/chartmenu');
		}
	}
	
	function closechart()
	{
		$this->session->unset_userdata('age');
		$this->session->unset_userdata('agealldays');
		$this->session->unset_userdata('gender');
		$this->session->unset_userdata('pid');
		$this->session->unset_userdata('ptname');
		$this->session->unset_userdata('eid');
		if($this->session->userdata('pid') == FALSE) {
			redirect('start');
		} else {
			redirect('search/closechart');
		}
	}
	
	function newpatient()
	{
		$dob1 = $this->input->post('DOB');
		$dob2 = strtotime($dob1);
		$datestring = "%Y-%m-%d";
		$dob = mdate($datestring, $dob2);
		$data = array(
			'lastname' => $this->input->post('lastname'),
			'firstname' => $this->input->post('firstname'),
			'DOB' => $dob,
			'sex' => $this->input->post('gender'),
			'active' => '1',
			'sexuallyactive' => 'no',
			'tobacco' => 'no',
			'pregnant' => 'no'
		);
		$pid = $this->demographics_model->add($data);
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$directory = $result['documents_dir'] . $pid;
		mkdir($directory, 0775);
		echo $this->input->post('firstname') . ' ' . $this->input->post('lastname') . ' added!';
		exit(0);
	}
	
	function newpatient1()
	{
		$this->session->unset_userdata('age');
		$this->session->unset_userdata('agealldays');
		$this->session->unset_userdata('gender');
		$this->session->unset_userdata('pid');	
		$now = time();
		$dob1 = $this->input->post('DOB');
		$dob2 = strtotime($dob1);
		$datestring = "%Y-%m-%d";
		$dob = mdate($datestring, $dob2);	
		$data = array(
			'lastname' => $this->input->post('lastname'),
			'firstname' => $this->input->post('firstname'),
			'DOB' => $dob,
			'sex' => $this->input->post('gender'),
			'active' => '1',
			'sexuallyactive' => 'no',
			'tobacco' => 'no',
			'pregnant' => 'no'
		);
		$pid = $this->demographics_model->add($data);	
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$directory = $result['documents_dir'] . $pid;
		mkdir($directory, 0775);	
		$data['ptname'] = $this->input->post('firstname') . ' ' . $this->input->post('lastname');
		$age1 = timespan($dob2, $now);
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
		$agediff = ($now - $dob2)/86400;
		$pos4 = strpos($agediff, '.');
		$data['agealldays'] = substr($agediff, 0, $pos4);
		if ($this->input->post('gender') == 'm') {
			$data['gender'] = 'Male';
			$data['gender1'] = 'male';
		} 
		if ($this->input->post('gender') == 'f') {
			$data['gender'] = 'Female';
			$data['gender1'] = 'female';
		}
		$this->session->set_userdata('pid', $pid);
		$this->session->set_userdata('gender', $data['gender1']);
		$this->session->set_userdata('age', $data['age']);
		$this->session->set_userdata('agealldays', $data['agealldays']);
		$this->session->set_userdata('ptname', $data['ptname']);	
		echo $this->input->post('firstname') . ' ' . $this->input->post('lastname') . ' added!';
		exit(0);
	}
	
	// --------------------------------------------------------------------
	
	function specialty()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('classification', $q);
		$this->db->or_like('specialization', $q);
		$this->db->select('classification, specialization, code');
		$query = $this->db->get('npi');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				if ($row['specialization'] != '') {
					$records = $row['classification'] . ', ' . $row['specialization'] . ' (' . $row['code'] . ')';
				} else {
					$records = $row['classification'] . ' (' . $row['code'] . ')';
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function specialty1()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('classification', $q);
		$this->db->or_like('specialization', $q);
		$this->db->select('classification, specialization');
		$query = $this->db->get('npi');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				if ($row['specialization'] != '') {
					$records = $row['classification'] . ', ' . $row['specialization'];
				} else {
					$records = $row['classification'];
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function pid()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		
		$this->db->like('lastname', $q);
		$this->db->or_like('firstname', $q);
		$this->db->or_like('pid', $q);
		$this->db->select('lastname, firstname, DOB, pid');
		$query = $this->db->get('demographics');
		
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$dob1 = $row['DOB'];
				$dob2 = strtotime($dob1);
				$datestring = "%m/%d/%Y";
				$dob = mdate($datestring, $dob2);
				$id = $row['pid'];
				$name =  $row['lastname'] . ', ' . $row['firstname'] . ' (DOB: ' . $dob . ') (ID: ' . $row['pid'] . ')';
				$data['message'][] = array(
					'label' => $name,
					'value' => $id
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function users()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('displayname', $q);
		$this->db->or_like('id', $q);
		$this->db->select('id, displayname');
		$this->db->where('group_id !=', '100'); 
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['displayname'] . ' (' . $row['id'] . ')';
				$data['message'][] = array(
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function all_users()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('displayname', $q);
		$this->db->or_like('id', $q);
		$this->db->select('id, displayname');
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['displayname'] . ' (' . $row['id'] . ')';
				$data['message'][] = array(
					'id' => $row['id'],
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function all_users1()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('displayname', $q);
		$this->db->or_like('id', $q);
		$this->db->where('group_id !=', '100');
		$this->db->select('id, displayname');
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['displayname'] . ' (' . $row['id'] . ')';
				$data['message'][] = array(
					'id' => $row['id'],
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}

	function icd9()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$pos = explode(',', $q);
		if ($pos == FALSE) {
			$this->db->like('icd9_description', $q);
		} else {
			foreach ($pos as $p) {
				$this->db->like('icd9_description', $p);
			}
		}
		$this->db->or_like('icd9', $q);
		$this->db->select('icd9, icd9_description');
		$query = $this->db->get('icd9');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['icd9_description'] . ' [' . $row['icd9'] . ']';
				$data['message'][] = array(
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function cpt()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$pos = explode(',', $q);
		if ($pos == FALSE) {
			$this->db->like('cpt_description', $q);
		} else {
			foreach ($pos as $p) {
				$this->db->like('cpt_description', $p);
			}
		}	
		$this->db->or_like('cpt', $q);
		$this->db->select('cpt, cpt_description');
		$query = $this->db->get('cpt');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['cpt_description'] . ' [' . $row['cpt'] . ']';
				$data['message'][] = array(
					'label' => $records,
					'value' => $row['cpt']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function cpt1()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$pos = explode(',', $q);
		if ($pos == FALSE) {
			$this->db->like('cpt_description', $q);
		} else {
			foreach ($pos as $p) {
				$this->db->like('cpt_description', $p);
			}
		}	
		$this->db->or_like('cpt', $q);
		$this->db->select('cpt, cpt_description, cpt_charge');
		$query = $this->db->get('cpt');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['cpt_description'] . ' [' . $row['cpt'] . ']';
				$data['message'][] = array(
					'label' => $records,
					'value' => $row['cpt'],
					'charge' => $row['cpt_charge']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function cc()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('encounter_cc', $q);
		$this->db->select('encounter_cc');
		$this->db->distinct();
		$query = $this->db->get('encounters');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['encounter_cc'],
					'value' => $row['encounter_cc']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function reaction()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('allergies_reaction', $q);
		$this->db->select('allergies_reaction');
		$this->db->distinct();
		$query = $this->db->get('allergies');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['allergies_reaction'],
					'value' => $row['allergies_reaction']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function pos()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('pos_id', $q);
		$this->db->or_like('pos_description', $q);
		$this->db->select('pos_id, pos_description');
		$query = $this->db->get('pos');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['pos_description'] . ' (' . $row['pos_id'] . ')';
				$data['message'][] = array(
					'label' => $records,
					'value' => $row['pos_id']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function rx()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('PROPRIETARYNAME', $q);
		$this->db->select('PROPRIETARYNAME, DOSAGEFORMNAME, ACTIVE_NUMERATOR_STRENGTH, ACTIVE_INGRED_UNIT');
		$this->db->distinct();
		$query = $this->db->get('meds_full');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$medication = trim($row['PROPRIETARYNAME']) . ", " . strtolower($row['DOSAGEFORMNAME']);
				$dosage = trim($row['ACTIVE_NUMERATOR_STRENGTH']);
				$unit = trim($row['ACTIVE_INGRED_UNIT']);
				$label = $medication . ' (Dose: ' . $dosage . ') (Unit: ' . $unit . ')';
				$data['message'][] = array(
					'label' => $label,
					'value' => $label,
					'medication' => $medication,
					'dosage' => $dosage,
					'unit' => $unit
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function rx_name()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('PROPRIETARYNAME', $q);
		$this->db->select('PROPRIETARYNAME, DOSAGEFORMNAME, PRODUCTNDC');
		$this->db->distinct();
		$query = $this->db->get('meds_full');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$medication = trim($row['PROPRIETARYNAME']) . ", " . strtolower($row['DOSAGEFORMNAME']);
				$data['message'][] = array(
					'label' => $medication,
					'value' => $medication,
					'name' => $row['PROPRIETARYNAME'],
					'form' => $row['DOSAGEFORMNAME']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function rx_name1()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('rxl_medication', $q);
		$this->db->select('rxl_medication, rxl_dosage, rxl_dosage_unit, rxl_ndcid');
		$this->db->distinct();
		$query = $this->db->get('rx_list');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$label = $row['rxl_medication'] . ", Dosage: " . $row['rxl_dosage'] . " " . $row['rxl_dosage_unit'];
				$data['message'][] = array(
					'label' => $label,
					'value' => $row['rxl_medication'],
					'name' => '',
					'form' => '',
					'dosage' => $row['rxl_dosage'],
					'dosage_unit' => $row['rxl_dosage_unit'],
					'ndcid' => $row['rxl_ndcid'],
					'category' => 'Previously Prescribed'
				);
			}
		}
		$this->db->like('PROPRIETARYNAME', $q);
		$this->db->select('PROPRIETARYNAME, DOSAGEFORMNAME, PRODUCTNDC');
		$this->db->distinct();
		$query1 = $this->db->get('meds_full');
		if ($query1->num_rows() > 0) {
			foreach ($query1->result_array() as $row1) {
				$medication = trim($row1['PROPRIETARYNAME']) . ", " . strtolower($row1['DOSAGEFORMNAME']);
				$data['response'] = 'true';
				$data['message'][] = array(
					'label' => $medication,
					'value' => $medication,
					'name' => $row1['PROPRIETARYNAME'],
					'form' => $row1['DOSAGEFORMNAME'],
					'dosage' => '',
					'dosage_unit' => '',
					'ndcid' => '',
					'category' => 'Medication Database'
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function rx_dosage()
	{
		$q = $this->input->post('term');
		if (!$q) return;
		$data['response'] = 'false';
		$q_parts = explode(";", $q);
		$this->db->where('PROPRIETARYNAME', $q_parts[0]);
		$this->db->where('DOSAGEFORMNAME', $q_parts[1]);
		$this->db->select('ACTIVE_NUMERATOR_STRENGTH, ACTIVE_INGRED_UNIT, PRODUCTNDC');
		$this->db->order_by('ACTIVE_NUMERATOR_STRENGTH', 'asc');
		$this->db->distinct();
		$query = $this->db->get('meds_full');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$dosage = trim($row['ACTIVE_NUMERATOR_STRENGTH']);
				$unit = trim($row['ACTIVE_INGRED_UNIT']);
				$label = $dosage . ' ' . $unit;
				$data['message'][] = array(
					'label' => $label,
					'value' => $dosage,
					'unit' => $unit,
					'ndc' => $row['PRODUCTNDC']
				);
			}
		}
		echo json_encode($data);
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
	
	function rx_ndc_convert($ndc)
	{
		$this->db->where('PRODUCTNDC', $ndc);
		$this->db->limit(1);
		$result = $this->db->get('meds_full_package')->row_array();
		$ndcid = $this->ndc_convert($result['NDCPACKAGECODE']);
		echo $ndcid;
	}
	
	function rx_sig()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('rxl_sig', $q);
		$this->db->select('rxl_sig');
		$this->db->distinct();
		$query = $this->db->get('rx_list');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['rxl_sig'],
					'value' => $row['rxl_sig']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function rx_frequency()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('rxl_frequency', $q);
		$this->db->select('rxl_frequency');
		$this->db->distinct();
		$query = $this->db->get('rx_list');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['rxl_frequency'],
					'value' => $row['rxl_frequency']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function rx_instructions()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('rxl_instructions', $q);
		$this->db->select('rxl_instructions');
		$this->db->distinct();
		$query = $this->db->get('rx_list');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['rxl_instructions'],
					'value' => $row['rxl_instructions']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function rx_reason()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('rxl_reason', $q);
		$this->db->select('rxl_reason');
		$this->db->distinct();
		$query = $this->db->get('rx_list');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['rxl_reason'],
					'value' => $row['rxl_reason']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function rx_ndc()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('meds_labelcode', $q);
		$this->db->or_like('meds_productcode', $q);
		$this->db->or_like('meds_packagecode', $q);
		$this->db->select('meds_medication, meds_labelcode, meds_productcode, meds_packagecode');
		$query = $this->db->get('meds');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$a = str_replace('*', '', $row['meds_productcode']);
				$b = str_replace('*', '', $row['meds_packagecode']);
				$ndc = $row['meds_labelcode'] . $a . $b;
				$records .= $row['meds_medication'] . '(' . $ndc . ')';
				$data['message'][] = array(
					'label' => $records,
					'value' => $records
				);
			}
		}
	}
	
	function supplements($order)
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('sup_description', $q);
		$this->db->where('quantity >', '0');
		$this->db->select('sup_description, quantity, cpt, charge, sup_strength, supplement_id');
		$this->db->distinct();
		$query = $this->db->get('supplement_inventory');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				if ($order == "Y") {
					if(strpos($row['sup_strength'], " ") === FALSE) {
						$dosage_array[0] = $row['sup_strength'];
						$dosage_array[1] = '';
					} else {
						$dosage_array = explode(' ', $row['sup_strength']);
					}
					$label = $row['sup_description'] . ", Quantity left: " . $row['quantity'];
					$data['message'][] = array(
						'label' => $label,
						'value' => $row['sup_description'],
						'category' => 'Supplements Inventory',
						'quantity' => $row['quantity'],
						'dosage' => $dosage_array[0],
						'dosage_unit' => $dosage_array[1],
						'supplement_id' => $row['supplement_id']
					);
				} else {
					if(strpos($row['sup_strength'], " ") === FALSE) {
						$dosage_array[0] = $row['sup_strength'];
						$dosage_array[1] = '';
					} else {
						$dosage_array = explode(' ', $row['sup_strength']);
					}
					$data['message'][] = array(
						'label' => $row['sup_description'],
						'value' => $row['sup_description'],
						'category' => 'Supplements Inventory',
						'dosage' => $dosage_array[0],
						'dosage_unit' => $dosage_array[1],
						'supplement_id' => $row['supplement_id']
					);
				}
			}
		}
		$this->db->like('sup_supplement', $q);
		$this->db->select('sup_supplement, sup_dosage, sup_dosage_unit');
		$this->db->distinct();
		$query0 = $this->db->get('sup_list');
		if ($query0->num_rows() > 0) {
			if (!isset($data['message'])) {
				$data['message'] = array();
				$data['response'] = 'true';
			}
			foreach ($query0->result_array() as $row0) {
				if ($order == "Y") {
					$label0 = $row0['sup_supplement'] . ", Dosage: " . $row0['sup_dosage'] . " " . $row0['sup_dosage_unit'];
					$data['message'][] = array(
						'label' => $label0,
						'value' => $row0['sup_supplement'],
						'category' => 'Previously Prescribed',
						'quantity' => '',
						'dosage' => $row0['sup_dosage'],
						'dosage_unit' => $row0['sup_dosage_unit'],
						'supplement_id' => ''
					);
				} else {
					$data['message'][] = array(
						'label' => $row0['sup_supplement'],
						'value' => $row0['sup_supplement'],
						'category' => ''
					);
				}
			}
		}
		$this->db->like('supplement_name', $q);
		$this->db->select('supplement_name');
		$this->db->distinct();
		$query1 = $this->db->get('supplements_list');
		if ($query1->num_rows() > 0) {
			if (!isset($data['message'])) {
				$data['message'] = array();
				$data['response'] = 'true';
			}
			foreach ($query1->result_array() as $row1) {
				if ($order == "Y") {
					$data['message'][] = array(
						'label' => $row1['supplement_name'],
						'value' => $row1['supplement_name'],
						'category' => 'Supplement Database',
						'quantity' => '',
						'dosage' => '',
						'dosage_unit' => '',
						'supplement_id' => ''
					);
				} else {
					$data['message'][] = array(
						'label' => $row1['supplement_name'],
						'value' => $row1['supplement_name'],
						'category' => ''
					);
				}
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function sup_dosage()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('sup_supplement', $q);
		$this->db->select('sup_dosage, sup_dosage_unit');
		$this->db->order_by('sup_dosage', 'asc');
		$this->db->distinct();
		$query = $this->db->get('sup_list');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$dosage = trim($row['sup_dosage']);
				$unit = trim($row['sup_dosage_unit']);
				$label = $dosage . ' ' . $unit;
				$data['message'][] = array(
					'label' => $label,
					'value' => $dosage,
					'unit' => $unit
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function sup_sig()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('sup_sig', $q);
		$this->db->select('sup_sig');
		$this->db->distinct();
		$query = $this->db->get('sup_list');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['sup_sig'],
					'value' => $row['sup_sig']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function sup_frequency()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('sup_frequency', $q);
		$this->db->select('sup_frequency');
		$this->db->distinct();
		$query = $this->db->get('sup_list');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['sup_frequency'],
					'value' => $row['sup_frequency']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function sup_instructions()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('sup_instructions', $q);
		$this->db->select('sup_instructions');
		$this->db->distinct();
		$query = $this->db->get('sup_list');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['sup_instructions'],
					'value' => $row['sup_instructions']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function sup_reason()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('sup_reason', $q);
		$this->db->select('sup_reason');
		$this->db->distinct();
		$query = $this->db->get('sup_list');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['sup_reason'],
					'value' => $row['sup_reason']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function sup_cpt()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('sup_description', $q);
		$this->db->distinct();
		$query = $this->db->get('supplement_inventory');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['sup_description'],
					'value' => $row['sup_description'],
					'cpt' => $row['cpt'],
					'charge' => $row['charge'],
					'quantity' => $row['quantity'],
					'manufacturer' => $row['sup_manufacturer']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	
	function imm()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('description', $q);
		$this->db->or_like('vaccine_name', $q);
		$this->db->select('vaccine_name, cvx_code');
		$this->db->distinct();
		$query = $this->db->get('cvx');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['vaccine_name'],
					'value' => $row['vaccine_name'],
					'cvx' => $row['cvx_code']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function imm1()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$quantity = 0;
		$this->db->like('imm_immunization', $q);
		$this->db->select('imm_immunization, imm_cvxcode, cpt, imm_expiration, imm_manufacturer, imm_lot, vaccine_id');
		$this->db->where('quantity >', $quantity);
		$this->db->distinct();
		$query = $this->db->get('vaccine_inventory');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['imm_immunization'],
					'value' => $row['imm_immunization'],
					'cvx' => $row['imm_cvxcode'],
					'cpt' => $row['cpt'],
					'expiration' => $row['imm_expiration'],
					'manufacturer' => $row['imm_manufacturer'],
					'lot' => $row['imm_lot'],
					'vaccine_id' => $row['vaccine_id']
				);
			}
		} else {
			$data['message'] = array();
			$data['response'] = 'true';
			$data['message'][] = array(
				'label' => 'No vaccines in the inventory!',
				'value' => ''
			);
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function alert()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('alert', $q);
		$this->db->select('alert');
		$this->db->distinct();
		$query = $this->db->get('alerts');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['alert'],
					'value' => $row['alert']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function alert_description()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('alert_description', $q);
		$this->db->select('alert_description');
		$this->db->distinct();
		$query = $this->db->get('alerts');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['alert_description'],
					'value' => $row['alert_description']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function alert_reason_not_complete()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('alert_reason_not_complete', $q);
		$this->db->select('alert_reason_not_complete');
		$this->db->distinct();
		$query = $this->db->get('alerts');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['alert_reason_not_complete'],
					'value' => $row['alert_reason_not_complete']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function address()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('address', $q);
		$this->db->select('address');
		$this->db->distinct();
		$query = $this->db->get('demographics');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['address'],
					'value' => $row['address']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function city()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('city', $q);
		$this->db->select('city');
		$this->db->distinct();
		$query = $this->db->get('demographics');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['city'],
					'value' => $row['city']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function provider()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->where('group_id', '2');
		$this->db->like('displayname', $q);
		$this->db->select('displayname');
		$this->db->distinct();
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['displayname'],
					'value' => $row['displayname']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function provider1()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->where('group_id', '2');
		$this->db->like('displayname', $q);
		$this->db->select('displayname, id');
		$this->db->distinct();
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['displayname'],
					'value' => $row['displayname'],
					'id' => $row['id']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function users1()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->where('group_id !=', '100'); 
		$this->db->like('displayname', $q);
		$this->db->select('id, displayname');
		$this->db->distinct();
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['displayname'],
					'value' => $row['displayname'],
					'id' => $row['id']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function insurance()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->where('specialty', 'Insurance');
		$this->db->like('displayname', $q);
		$this->db->select('displayname, address_id');
		$this->db->distinct();
		$query = $this->db->get('addressbook');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['displayname'] . ' (' . $row['address_id'] . ')';
				$data['message'][] = array(
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function insurance1()
	{
		$this->db->where('address_id', $this->input->post('address_id'));
		$row = $this->db->get('addressbook')->row_array();
		echo json_encode($row);
		exit( 0 );
	}
	
	function insurance2()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->where('specialty', 'Insurance');
		$this->db->like('displayname', $q);
		$this->db->select('displayname, address_id');
		$this->db->distinct();
		$query = $this->db->get('addressbook');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['displayname'] . ' (' . $row['address_id'] . ')';
				$data['message'][] = array(
					'label' => $records,
					'value' => $row['displayname'],
					'id' => $row['address_id']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function insurance3()
	{
		$this->db->where('specialty', 'Insurance');
		$query = $this->db->get('addressbook');
		$data['response'] = 'false';
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][$row['address_id']] = $row['displayname'];
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function addressbook()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('displayname', $q);
		$this->db->select('displayname');
		$this->db->distinct();
		$query = $this->db->get('addressbook');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['displayname'],
					'value' => $row['displayname']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function document_from()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('displayname', $q);
		$this->db->select('displayname');
		$this->db->distinct();
		$query = $this->db->get('addressbook');
		$this->db->like('documents_from', $q);
		$this->db->select('documents_from');
		$this->db->distinct();
		$query1 = $this->db->get('documents');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['displayname'],
					'value' => $row['displayname']
				);
			}
		}
		if ($query1->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query1->result_array() as $row1) {
				$data['message'][] = array(
					'label' => $row1['documents_from'],
					'value' => $row1['documents_from']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function pharmacy()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->where('specialty', 'Pharmacy');
		$this->db->like('displayname', $q);
		$this->db->select('displayname, fax');
		$this->db->distinct();
		$query = $this->db->get('addressbook');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['displayname'],
					'value' => $row['displayname'],
					'fax' => $row['fax']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function pharmacy1()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->where('specialty', 'Pharmacy');
		$this->db->like('displayname', $q);
		$this->db->select('displayname');
		$this->db->distinct();
		$query = $this->db->get('addressbook');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['displayname'],
					'value' => $row['displayname']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function all_contacts()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('displayname', $q);
		$this->db->select('displayname, fax');
		$this->db->distinct();
		$query = $this->db->get('addressbook');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['displayname'],
					'value' => $row['displayname'],
					'fax' => $row['fax']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function all_contacts1()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('displayname', $q);
		$this->db->distinct();
		$query = $this->db->get('addressbook');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['displayname'],
					'value' => $row['displayname'],
					'id' => $row['address_id']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function all_contacts2()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('displayname', $q);
		$this->db->where('specialty !=', 'Pharmacy');
		$this->db->where('specialty !=', 'Laboratory');
		$this->db->where('specialty !=', 'Radiology');
		$this->db->where('specialty !=', 'Cardiopulmonary');
		$this->db->where('specialty !=', 'Insurance');
		$this->db->distinct();
		$query = $this->db->get('addressbook');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['displayname'] . ", Specialty: " . $row['specialty'],
					'value' => $row['displayname'],
					'id' => $row['address_id']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function lab_provider()
	{
		$this->db->where('specialty', 'Laboratory');
		$query = $this->db->get('addressbook');
		$data['response'] = 'false';
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][$row['address_id']] = $row['displayname'];
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function rad_provider()
	{
		$this->db->where('specialty', 'Radiology');
		$query = $this->db->get('addressbook');
		$data['response'] = 'false';
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][$row['address_id']] = $row['displayname'];
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function cp_provider()
	{
		$this->db->where('specialty', 'Cardiopulmonary');
		$query = $this->db->get('addressbook');
		$data['response'] = 'false';
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][$row['address_id']] = $row['displayname'];
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function ref_provider($specialty)
	{
		if ($specialty != "all") {
			$this->db->where('specialty', $specialty);
		} else {
			$this->db->where('specialty !=', 'Pharmacy');
			$this->db->where('specialty !=', 'Laboratory');
			$this->db->where('specialty !=', 'Radiology');
			$this->db->where('specialty !=', 'Cardiopulmonary');
			$this->db->where('specialty !=', 'Insurance');
		}
		$query = $this->db->get('addressbook');
		$data['response'] = 'false';
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][$row['address_id']] = $row['displayname'] . " - " . $row['specialty'];
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function ref_provider_specialty()
	{
		$this->db->where('specialty !=', 'Pharmacy');
		$this->db->where('specialty !=', 'Laboratory');
		$this->db->where('specialty !=', 'Radiology');
		$this->db->where('specialty !=', 'Cardiopulmonary');
		$this->db->where('specialty !=', 'Insurance');
		$this->db->distinct();
		$query = $this->db->get('addressbook');
		$data['response'] = 'false';
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][$row['specialty']] = $row['specialty'];
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function order_provider1()
	{
		$address_id = $this->input->post('address_id');
		$this->db->where('address_id', $address_id);
		$query = $this->db->get('addressbook');
		$row = $query->row_array();
		$arr = array(
			'lastname' => $row['lastname'],
			'firstname' => $row['firstname'],
			'specialty'=> $row['specialty'],
			'email' => $row['email'],
			'comments' => $row['comments'],
			'prefix' => $row['prefix'],
			'suffix' => $row['suffix'],
			'facility' => $row['facility'],
			'street_address1' => $row['street_address1'],
			'street_address2' => $row['street_address2'],
			'city' => $row['city'],
			'state' => $row['state'],
			'zip' => $row['zip'],
			'phone' => $row['phone'],
			'fax' => $row['fax'],
			'address_id' => $row['address_id']
		);
		echo json_encode($arr);
		exit( 0 );
	}
	
	function lab()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$data['message'] = array();
		$this->db->where('orders_category', 'Laboratory');
		$this->db->where('user_id', '0');
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query = $this->db->get('orderslist');
		if ($query->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['orders_description'];
				if ($row['cpt'] != '') {
					$records .= ', CPT: ' . $row['cpt'];
				}
				if ($row['snomed'] != '') {
					$records .= ', SNOMED: ' . $row['snomed'];
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records,
					'category' => 'Global'
				);
			}
		}
		$this->db->where('orders_category', 'Laboratory');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query1 = $this->db->get('orderslist');
		if ($query1->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query1->result_array() as $row1) {
				$records1 = $row1['orders_description'];
				if ($row1['cpt'] != '') {
					$records1 .= ', CPT: ' . $row1['cpt'];
				}
				if ($row1['snomed'] != '') {
					$records1 .= ', SNOMED: ' . $row1['snomed'];
				}
				$data['message'][] = array(
					'label' => $records1,
					'value' => $records1,
					'category' => 'Personal'
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function order1()
	{
		$orderslist_id = $this->input->post('orderslist_id');
		$this->db->where('orderslist_id', $orderslist_id);
		$query = $this->db->get('orderslist');
		$row = $query->row_array();
		$arr = array(
			'orders_description' => $row['orders_description'],
			'orderslist_id' => $row['orderslist_id'],
			'cpt' => $row['cpt']
		);
		echo json_encode($arr);
		exit( 0 );
	}
	
	function rad()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$data['message'] = array();
		$this->db->where('orders_category', 'Radiology');
		$this->db->where('user_id', '0');
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query = $this->db->get('orderslist');
		if ($query->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['orders_description'];
				if ($row['cpt'] != '') {
					$records .= ', CPT: ' . $row['cpt'];
				}
				if ($row['snomed'] != '') {
					$records .= ', SNOMED: ' . $row['snomed'];
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records,
					'category' => 'Global'
				);
			}
		}
		$this->db->where('orders_category', 'Radiology');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query1 = $this->db->get('orderslist');
		if ($query1->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query1->result_array() as $row1) {
				$records1 = $row1['orders_description'];
				if ($row1['cpt'] != '') {
					$records1 .= ', CPT: ' . $row1['cpt'];
				}
				if ($row['snomed'] != '') {
					$records1 .= ', SNOMED: ' . $row1['snomed'];
				}
				$data['message'][] = array(
					'label' => $records1,
					'value' => $records1,
					'category' => 'Personal'
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function cp()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$data['message'] = array();
		$this->db->where('orders_category', 'Cardiopulmonary');
		$this->db->where('user_id', '0');
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query = $this->db->get('orderslist');
		if ($query->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['orders_description'];
				if ($row['cpt'] != '') {
					$records .= ', CPT: ' . $row['cpt'];
				}
				if ($row['snomed'] != '') {
					$records .= ', SNOMED: ' . $row['snomed'];
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records,
					'category' => 'Global'
				);
			}
		}
		$this->db->where('orders_category', 'Cardiopulmonary');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query1 = $this->db->get('orderslist');
		if ($query1->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query1->result_array() as $row1) {
				$records1 = $row1['orders_description'];
				if ($row['cpt'] != '') {
					$records1 .= ', CPT: ' . $row1['cpt'];
				}
				if ($row['snomed'] != '') {
					$records1 .= ', SNOMED: ' . $row1['snomed'];
				}
				$data['message'][] = array(
					'label' => $records1,
					'value' => $records1,
					'category' => 'Personal'
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function ref()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$data['message'] = array();
		$this->db->where('orders_category', 'Referral');
		$this->db->where('user_id', '0');
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query = $this->db->get('orderslist');
		if ($query->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['orders_description'];
				if ($row['cpt'] != '') {
					$records .= ', CPT: ' . $row['cpt'];
				}
				if ($row['snomed'] != '') {
					$records .= ', SNOMED: ' . $row['snomed'];
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records,
					'category' => 'Global'
				);
			}
		}
		$this->db->where('orders_category', 'Referral');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query1 = $this->db->get('orderslist');
		if ($query1->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query1->result_array() as $row1) {
				$records1 = $row1['orders_description'];
				if ($row['cpt'] != '') {
					$records1 .= ', CPT: ' . $row1['cpt'];
				}
				if ($row['snomed'] != '') {
					$records1 .= ', SNOMED: ' . $row1['snomed'];
				}
				$data['message'][] = array(
					'label' => $records1,
					'value' => $records1,
					'category' => 'Personal'
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function frequency()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->where('orders_category', 'Frequency');
		$this->db->like('orders_description', $q);
		$this->db->select('orders_description, orderslist_id');
		$this->db->distinct();
		$query = $this->db->get('orderslist');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['orders_description'],
					'value' => $row['orders_description']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function procedure_type()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('procedure_type', $q);
		$this->db->select('procedurelist_id, procedure_type, procedure_description, procedure_complications, procedure_ebl, cpt');
		$query = $this->db->get('procedurelist');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['procedure_type'],
					'value' => $row['procedure_type'],
					'procedurelist_id' => $row['procedurelist_id'],
					'procedure_description' => $row['procedure_description'],
					'procedure_complications' => $row['procedure_complications'],
					'procedure_ebl' => $row['procedure_ebl'],
					'cpt' => $row['cpt']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function billing_reason()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('reason', $q);
		$this->db->select('reason');
		$this->db->distinct();
		$query = $this->db->get('billing_core');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['reason'],
					'value' => $row['reason']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function hippa_reason()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('hippa_reason', $q);
		$this->db->select('hippa_reason');
		$this->db->distinct();
		$query = $this->db->get('hippa');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['hippa_reason'],
					'value' => $row['hippa_reason']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function document_description()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('documents_desc', $q);
		$this->db->select('documents_desc');
		$this->db->distinct();
		$query = $this->db->get('documents');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['documents_desc'],
					'value' => $row['documents_desc']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function subject()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('t_messages_subject', $q);
		$this->db->select('t_messages_subject');
		$this->db->distinct();
		$query = $this->db->get('t_messages');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['t_messages_subject'],
					'value' => $row['t_messages_subject']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function payment_type()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'true';
		$this->db->like('payment_type', $q);
		$this->db->select('payment_type');
		$this->db->distinct();
		$query = $this->db->get('billing_core');
		$data['message'] = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['payment_type'],
					'value' => $row['payment_type']
				);
			}
		}
		$data['message'][] = array(
			'label' => '*********',
			'value' => ''
		);
		$data['message'][] = array(
			'label' => 'Billing Service Adjustment',
			'value' => 'Billing Service Adjustment'
		);
		$data['message'][] = array(
			'label' => 'Cash',
			'value' => 'Cash'
		);
		$data['message'][] = array(
			'label' => 'Check',
			'value' => 'Check, #'
		);
		$data['message'][] = array(
			'label' => 'Credit/Debit Card',
			'value' => 'Credit/Debit Card'
		);
		$data['message'][] = array(
			'label' => 'Insurance Copay',
			'value' => 'Insurance Copay'
		);
		$data['message'][] = array(
			'label' => 'Insurance Deductible',
			'value' => 'Insurance Deductible'
		);
		$data['message'][] = array(
			'label' => 'Insurance Payment',
			'value' => 'Insurance Payment'
		);
		$data['message'][] = array(
			'label' => 'Write-Off',
			'value' => 'Write-Off'
		);
		echo json_encode($data);
		exit( 0 );
	}
	
	function guardian_relationship()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('description', $q);
		$this->db->distinct();
		$query = $this->db->get('guardian_roles');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['description'],
					'value' => $row['description'],
					'code' => $row['code']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function language()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('description', $q);
		$this->db->distinct();
		$query = $this->db->get('lang');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['description'],
					'value' => $row['description'],
					'code' => $row['code']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function providers()
	{
		$query = $this->practiceinfo_model->getProviders();
		$data['response'] = 'false';
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][$row['displayname']] = $row['displayname'];
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function documents_count()
	{
		$pid = $this->session->userdata('pid');
		$this->db->where('pid', $pid);
		$this->db->where('documents_type', 'Laboratory');
		$labs_count = $this->db->get('documents')->num_rows();
		if ($labs_count === 1) {
			$data['labs_count'] = $labs_count . " document.";
		} else {
			$data['labs_count'] = $labs_count . " documents.";
		}
		$this->db->where('pid', $pid);
		$this->db->where('documents_type', 'Imaging');
		$radiology_count = $this->db->get('documents')->num_rows();
		if ($radiology_count === 1) {
			$data['radiology_count'] = $radiology_count . " document.";
		} else {
			$data['radiology_count'] = $radiology_count . " documents.";
		}
		$this->db->where('pid', $pid);
		$this->db->where('documents_type', 'Cardiopulmonary');
		$cardiopulm_count = $this->db->get('documents')->num_rows();
		if ($cardiopulm_count === 1) {
			$data['cardiopulm_count'] = $cardiopulm_count . " document.";
		} else {
			$data['cardiopulm_count'] = $cardiopulm_count . " documents.";
		}
		$this->db->where('pid', $pid);
		$this->db->where('documents_type', 'Endoscopy');
		$endoscopy_count = $this->db->get('documents')->num_rows();
		if ($endoscopy_count === 1) {
			$data['endoscopy_count'] = $endoscopy_count . " document.";
		} else {
			$data['endoscopy_count'] = $endoscopy_count . " documents.";
		}
		$this->db->where('pid', $pid);
		$this->db->where('documents_type', 'Referrals');
		$referrals_count = $this->db->get('documents')->num_rows();
		if ($referrals_count === 1) {
			$data['referrals_count'] = $referrals_count . " document.";
		} else {
			$data['referrals_count'] = $referrals_count . " documents.";
		}
		$this->db->where('pid', $pid);
		$this->db->where('documents_type', 'Past Records');
		$past_records_count = $this->db->get('documents')->num_rows();
		if ($past_records_count === 1) {
			$data['past_records_count'] = $past_records_count . " document.";
		} else {
			$data['past_records_count'] = $past_records_count . " documents.";
		}
		$this->db->where('pid', $pid);
		$this->db->where('documents_type', 'Other Forms');
		$outside_forms_count = $this->db->get('documents')->num_rows();
		if ($outside_forms_count === 1) {
			$data['outside_forms_count'] = $outside_forms_count . " document.";
		} else {
			$data['outside_forms_count'] = $outside_forms_count . " documents.";
		}
		$this->db->where('pid', $pid);
		$this->db->where('documents_type', 'Letters');
		$letters_count = $this->db->get('documents')->num_rows();
		if ($letters_count === 1) {
			$data['letters_count'] = $letters_count . " document.";
		} else {
			$data['letters_count'] = $letters_count . " documents.";
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function fix_patient_dir()
	{
		$query = $this->practiceinfo_model->get();
		$result = $query->row_array();
		$query1 = $this->db->get('demographics');
		if ($query1->num_rows() > 0) {
			$arr = "";
			foreach ($query1->result_array() as $row) {
				$directory = $result['documents_dir'] . $row['pid'];
				if (!file_exists($directory) && !is_dir($directory)) {
					mkdir($directory, 0775);
					$arr .= $row['lastname'] . ", " . $row['firstname'] . "\n";
				}
			}
		}
		echo $arr;
		exit(0);
	}
	
	function snomed($type)
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->like('term', $q);
		$this->db->like('term', '(' . $type . ')');
		$this->db->where('active', '1');
		$this->db->distinct();
		$query = $this->db->get('curr_description_f');
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['term'] . ", Code: " . $row['conceptid'],
					'value' => $row['conceptid']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function snomed_parent($type)
	{
		if ($type == "imaging") {
			$this->db->where('destinationid', '371571005');
		}
		if ($type == "lab") {
			$this->db->where('destinationid', '15220000');
		}
		if ($type == "cp") {
			$this->db->where('destinationid', '276341003');
			$this->db->or_where('destinationid', '23426006');
		}
		if ($type == "ref") {
			$this->db->where('destinationid', '281100006');
		}
		$this->db->where('typeid','116680003');
		$this->db->where('active', '1');
		$this->db->distinct();
		$query = $this->db->get('curr_relationship_f');
		$arr = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$this->db->where('conceptid', $row['sourceid']);
				$this->db->where('active', '1');
				$term_row = $this->db->get('curr_description_f')->row_array();
				$arr[] = array(
					'data' => $term_row['term'],
					'attr' => array('id' => $row['sourceid']),
					'state' => 'closed'
				);
			}
		}
		echo json_encode($arr);
		exit ( 0 );
	}
	
	function snomed_child($id)
	{
		$this->db->where('destinationid', $id);
		$this->db->where('typeid','116680003');
		$this->db->where('active', '1');
		$this->db->distinct();
		$query = $this->db->get('curr_relationship_f');
		$arr = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$this->db->where('conceptid', $row['sourceid']);
				$this->db->where('active', '1');
				$term_row = $this->db->get('curr_description_f')->row_array();
				$arr[] = array(
					'data' => $term_row['term'],
					'attr' => array('id' => $row['sourceid']),
					'state' => 'closed'
				);
			}
		}
		echo json_encode($arr);
		exit ( 0 );
	}
	
	function vivacare_data()
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$this->load->library('domparser');
		$practice = $this->practiceinfo_model->get()->row_array();
		$data['response'] = "false";
		if ($practice['vivacare'] != "") {
			$html = $this->domparser->file_get_html("http://fromyourdoctor.com/" . $practice['vivacare'] . "/health/library.do");
			if (isset($html)) {
				$div = $html->find('[id=usercontent]',0);
				$div1 = $html->find('[id=formselectA]',0);
				if (isset($div)) {
					$data['response'] = "true";
					foreach ($div->find('ul[!id]') as $ul) {
						foreach ($ul->find('li') as $li) {
							$a = $li->find('a',0);
							$text = $a->innertext;
							$text = str_replace("\n","",$text);
							$link = $a->href;
							$link = str_replace("\n","",$link);
							$link_array = explode("=", $link);
							$search = 'option[value=' . $link_array[2] . ']';
							$item = $div1->find($search, 0);
							if (isset($item)) {
								$cat = $item->parent()->first_child();
								$category = $cat->innertext;
								$category = str_replace("\n","",$category);
							} else {
								$category = "Other";
							}
							$data['message'][] = array(
								'label' => $text,
								'value' => $text,
								'link' => $link_array[2],
								'category' => $category
							);
						}
					}
				}
			}
		}
		echo json_encode($data);
	}
}
/* End of file: search.php */
/* Location: application/controllers/search.php */
