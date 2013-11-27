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
		$this->load->model('audit_model');
		$this->load->model('chart_model');
		$this->load->model('encounters_model');
	}

	// --------------------------------------------------------------------

	function index()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->select('demographics.lastname, demographics.firstname, demographics.DOB, demographics.pid');
		$this->db->from('demographics');
		$this->db->join('demographics_relate', 'demographics_relate.pid=demographics.pid');
		$this->db->like('demographics.lastname', $q);
		$this->db->or_like('demographics.firstname', $q);
		$this->db->or_like('demographics.pid', $q);
		$this->db->where('demographics_relate.practice_id', $this->session->userdata('practice_id'));
		$query = $this->db->get();
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
		$data1 = array(
			'billing_notes' => '',
			'imm_notes' => '',
			'pid' => $pid,
			'practice_id' => $this->session->userdata('practice_id')
		);
		$this->db->insert('demographics_notes', $data1);
		$this->audit_model->add();
		$data2 = array(
			'pid' => $pid,
			'practice_id' => $this->session->userdata('practice_id')
		);
		$this->db->insert('demographics_relate', $data2);
		$this->audit_model->add();
		$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
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
		$data1 = array(
			'billing_notes' => '',
			'imm_notes' => '',
			'pid' => $pid,
			'practice_id' => $this->session->userdata('practice_id')
		);
		$this->db->insert('demographics_notes', $data1);
		$this->audit_model->add();
		$data2 = array(
			'pid' => $pid,
			'practice_id' => $this->session->userdata('practice_id')
		);
		$this->db->insert('demographics_relate', $data2);
		$this->audit_model->add();
		$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$data['message'] = array();
		if ($q == "***") {
			$this->db->where('favorite', '1');
			$this->db->where('practice_id', $this->session->userdata('practice_id'));
			$query0 = $this->db->get('cpt_relate');
			if ($query0->num_rows() > 0) {
				$data['response'] = 'true';
				foreach ($query0->result_array() as $row0) {
					$records0 = $row0['cpt_description'] . ' [' . $row0['cpt'] . ']';
					$data['message'][] = array(
						'label' => $records0,
						'value' => $row0['cpt'],
						'charge' => $row0['cpt_charge'],
						'unit' => $row0['unit'],
						'category' => 'Favorites'
					);
				}
			}
		} else {
			$pos2 = explode(',', $q);
			if ($pos2 == FALSE) {
				$this->db->like('cpt_description', $q);
			} else {
				foreach ($pos2 as $r) {
					$this->db->like('cpt_description', $r);
				}
			}	
			$this->db->or_like('cpt', $q);
			$this->db->where('practice_id', $this->session->userdata('practice_id'));
			$query2 = $this->db->get('cpt_relate');
			if ($query2->num_rows() > 0) {
				$data['response'] = 'true';
				foreach ($query2->result_array() as $row2) {
					$records2 = $row2['cpt_description'] . ' [' . $row2['cpt'] . ']';
					$data['message'][] = array(
						'label' => $records2,
						'value' => $row2['cpt'],
						'charge' => $row2['cpt_charge'],
						'unit' => $row2['unit'],
						'category' => 'Practice CPT Database'
					);
				}
			}
			$pos1 = explode(',', $q);
			if ($pos1 == FALSE) {
				$this->db->like('cpt_description', $q);
			} else {
				foreach ($pos1 as $p) {
					$this->db->like('cpt_description', $p);
				}
			}	
			$this->db->or_like('cpt', $q);
			$query1 = $this->db->get('cpt');
			if ($query1->num_rows() > 0) {
				$data['response'] = 'true';
				foreach ($query1->result_array() as $row1) {
					$records1 = $row1['cpt_description'] . ' [' . $row1['cpt'] . ']';
					$data['message'][] = array(
						'label' => $records1,
						'value' => $row1['cpt'],
						'charge' => $row1['cpt_charge'],
						'unit' => '1',
						'category' => 'Universal CPT Database'
					);
				}
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
				if ($this->recursive_array_search($medication, $data) === FALSE) {
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
				if ($this->recursive_array_search($label, $data) === FALSE) {
					$data['message'][] = array(
						'label' => $label,
						'value' => $dosage,
						'unit' => $unit,
						'ndc' => $row['PRODUCTNDC']
					);
				}
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
					'id' => $row['address_id'],
					'npi' => $row['npi']
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
		echo json_encode($row);
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query = $this->db->get('orderslist');
		if ($query->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['orders_description'];
				if ($row['cpt'] != '') {
					$records .= '; CPT: ' . $row['cpt'];
				}
				if ($row['snomed'] != '') {
					$records .= '; SNOMED: ' . $row['snomed'];
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
					$records1 .= '; CPT: ' . $row1['cpt'];
				}
				if ($row1['snomed'] != '') {
					$records1 .= '; SNOMED: ' . $row1['snomed'];
				}
				$data['message'][] = array(
					'label' => $records1,
					'value' => $records1,
					'category' => 'Personal'
				);
			}
		}
		$this->db->where('orders_category', 'Laboratory');
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query2 = $this->db->get('orderslist1');
		if ($query2->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query2->result_array() as $row2) {
				$records2 = $row2['orders_description'] . "; Code: " . $row2['orders_code'];
				if ($row2['cpt'] != '') {
					$records2 .= '; CPT: ' . $row2['cpt'];
				}
				if ($this->recursive_array_search($records2, $data) === FALSE) {
					$data['message'][] = array(
						'label' => $records2,
						'value' => $records2,
						'category' => 'Electronic Order Entry: ' . $row2['orders_vendor']
					);
				}
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function recursive_array_search($needle, $haystack)
	{
		foreach ($haystack as $key=>$value) {
			$current_key = $key;
			if ($needle === $value OR (is_array($value) && $this->recursive_array_search($needle, $value) !== FALSE)) {
				return $current_key;
			}
		}
		return FALSE;
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query = $this->db->get('orderslist');
		if ($query->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['orders_description'];
				if ($row['cpt'] != '') {
					$records .= '; CPT: ' . $row['cpt'];
				}
				if ($row['snomed'] != '') {
					$records .= '; SNOMED: ' . $row['snomed'];
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
					$records1 .= '; CPT: ' . $row1['cpt'];
				}
				if ($row['snomed'] != '') {
					$records1 .= '; SNOMED: ' . $row1['snomed'];
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query = $this->db->get('orderslist');
		if ($query->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['orders_description'];
				if ($row['cpt'] != '') {
					$records .= '; CPT: ' . $row['cpt'];
				}
				if ($row['snomed'] != '') {
					$records .= '; SNOMED: ' . $row['snomed'];
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
					$records1 .= '; CPT: ' . $row1['cpt'];
				}
				if ($row['snomed'] != '') {
					$records1 .= '; SNOMED: ' . $row1['snomed'];
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->like('orders_description', $q);
		$this->db->distinct();
		$query = $this->db->get('orderslist');
		if ($query->num_rows() > 0) {
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$records = $row['orders_description'];
				if ($row['cpt'] != '') {
					$records .= '; CPT: ' . $row['cpt'];
				}
				if ($row['snomed'] != '') {
					$records .= '; SNOMED: ' . $row['snomed'];
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
					$records1 .= '; CPT: ' . $row1['cpt'];
				}
				if ($row['snomed'] != '') {
					$records1 .= '; SNOMED: ' . $row1['snomed'];
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
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
		$query = $this->practiceinfo_model->getProviders($this->session->userdata('practice_id'));
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
	
	function providers1()
	{
		$query = $this->practiceinfo_model->getProviders($this->session->userdata('practice_id'));
		$data['response'] = 'false';
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][$row['id']] = $row['displayname'];
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
		$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
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
		$practice = $this->practiceinfo_model->get($this->session->userdata('practice_id'))->row_array();
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
	
	function get_tags($type, $id)
	{
		$this->db->where($type, $id);
		$query = $this->db->get('tags_relate');
		$result = "";
		$data = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$this->db->where('tags_id', $row['tags_id']);
				$row1 = $this->db->get('tags')->row_array();
				$data[] = array(
					'label' => $row1['tag'],
					'value' => $row1['tag']
				);
			}
		}
		echo json_encode($data);
	}
	
	function search_tags()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->from('tags');
		$this->db->join('tags_relate', 'tags_relate.tags_id=tags.tags_id');
		$this->db->like('tag', $q);
		$this->db->where('tags_relate.practice_id', $this->session->userdata('practice_id'));
		$this->db->distinct();
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query->result_array() as $row) {
				$data['message'][] = array(
					'label' => $row['tag'],
					'value' => $row['tag'],
					'category' => 'Previous Tags'
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
	
	function search_tags1()
	{
		$this->db->from('tags');
		$this->db->join('tags_relate', 'tags_relate.tags_id=tags.tags_id');
		$this->db->where('tags_relate.practice_id', $this->session->userdata('practice_id'));
		$this->db->distinct();
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$data['message'] = 'OK';
			foreach ($query->result_array() as $row) {
				$i = $row['tags_id'];
				$data[$i] = $row['tag'];
			}
		} else {
			$data['message'] = "No tags available.";
		}
		echo json_encode($data);
		exit ( 0 );
	}
	
	function save_tag($type, $id)
	{
		$this->db->where('tag', $this->input->post('tag'));
		$query1 = $this->db->get('tags');
		if ($query1->num_rows > 0) {
			$row1 = $query1->row_array();
			$tags_id = $row1['tags_id'];
		} else {
			$data1 = array(
				'tag' => $this->input->post('tag')
			);
			$this->db->insert('tags', $data1);
			$tags_id = $this->db->insert_id();
			$this->audit_model->add();
		}
		$data2 = array(
			'tags_id' => $tags_id,
			$type => $id,
			'pid' => $this->session->userdata('pid'),
			'practice_id' => $this->session->userdata('practice_id')
		);
		$this->db->insert('tags_relate', $data2);
		$this->audit_model->add();
	}
	
	function remove_tag($type, $id)
	{
		$this->db->where('tag', $this->input->post('tag'));
		$row = $this->db->get('tags')->row_array();
		$this->db->where('tags_id', $row['tags_id']);
		$this->db->where($type, $id);
		$this->db->delete('tags_relate');
		$this->audit_model->delete();
	}
	
	function tag_query($pid='')
	{
		$practice_id = $this->session->userdata('practice_id');
		$query_text = "SELECT * FROM tags_relate WHERE";
		$j = 0;
		$tags = $this->input->post('tags_array');
		foreach ($tags[0] as $tag) {
			if ($j == 0) {
				$query_text .= " tags_id=$tag";
			} else {
				$query_text .= " AND tags_id=$tag";
			}
			$j++;
		}
		if ($pid != '') {
			$query_text .= " AND pid=$pid";
		}
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$sord = strtolower($sord);
		$query = $this->db->query($query_text);
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query_text1 = $query_text . " ORDER BY " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		$query1 = $this->db->query($query_text1);
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$records1 = array();
		$i = 0;
		foreach ($records as $row) {
			$records1[$i]['index'] = $i;
			$records1[$i]['pid'] = $row['pid'];
			$this->db->where('pid', $row['pid']);
			$row1 = $this->db->get('demographics')->row_array();
			$records1[$i]['lastname'] = $row1['lastname'];
			$records1[$i]['firstname'] = $row1['firstname'];
			if ($row['eid'] != '') {
				$this->db->where('eid', $row['eid']);
				$row2 = $this->db->get('encounters')->row_array();
				$records1[$i]['doc_date'] = $row2['encounter_date'];
				$records1[$i]['doctype'] = 'Encounter';
				$records1[$i]['doctype_index'] = 'eid';
				$records1[$i]['doc_id'] = $row['eid'];
			}
			if ($row['t_messages_id'] != '') {
				$this->db->where('t_messages_id', $row['t_messages_id']);
				$row3 = $this->db->get('t_messages')->row_array();
				$records1[$i]['doc_date'] = $row3['t_messages_date'];
				$records1[$i]['doctype'] = 'Telephone Message';
				$records1[$i]['doctype_index'] = 't_messages_id';
				$records1[$i]['doc_id'] = $row['t_messages_id'];
			}
			if ($row['message_id'] != '') {
				$this->db->where('message_id', $row['message_id']);
				$row4 = $this->db->get('messaging')->row_array();
				$records1[$i]['doc_date'] = $row4['date'];
				$records1[$i]['doctype'] = 'Message';
				$records1[$i]['doctype_index'] = 'message_id';
				$records1[$i]['doc_id'] = $row['message_id'];
			}
			if ($row['documents_id'] != '') {
				$this->db->where('documents_id', $row['documents_id']);
				$row5 = $this->db->get('documents')->row_array();
				$records1[$i]['doc_date'] = $row5['documents_date'];
				$records1[$i]['doctype'] = 'Documents';
				$records1[$i]['doctype_index'] = 'documents_id';
				$records1[$i]['doc_id'] = $row['documents_id'];
			}
			if ($row['hippa_id'] != '') {
				$this->db->where('hippa_id', $row['hippa_id']);
				$row6 = $this->db->get('hippa')->row_array();
				$records1[$i]['doc_date'] = $row6['hippa_date_release'];
				$records1[$i]['doctype'] = 'Records Release';
				$records1[$i]['doctype_index'] = 'hippa_id';
				$records1[$i]['doc_id'] = $row['hippa_id'];
			}
			if ($row['appt_id'] != '') {
				$this->db->where('eid', $row['appt_id']);
				$row7 = $this->db->get('schedule')->row_array();
				$records1[$i]['doc_date'] = $row7['timestamp'];
				$records1[$i]['doctype'] = 'Appointment';
				$records1[$i]['doctype_index'] = 'appt_id';
				$records1[$i]['doc_id'] = $row['appt_id'];
			}
			if ($row['tests_id'] != '') {
				$this->db->where('tests_id', $row['tests_id']);
				$row8 = $this->db->get('tests')->row_array();
				$records1[$i]['doc_date'] = $row8['test_datetime'];
				$records1[$i]['doctype'] = 'Test Results';
				$records1[$i]['doctype_index'] = 'tests_id';
				$records1[$i]['doc_id'] = $row['tests_id'];
			}
			if ($row['mtm_id'] != '') {
				$this->db->where('mtm_id', $row['mtm_id']);
				$row9 = $this->db->get('mtm')->row_array();
				$records1[$i]['doc_date'] = $row9['mtm_date_completed'];
				$records1[$i]['doctype'] = 'Medication Therapy Management';
				$records1[$i]['doctype_index'] = 'mtm_id';
				$records1[$i]['doc_id'] = $row['mtm_id'];
			}
			$i++;
		}
		$response['rows'] = $records1;
		echo json_encode($response);
		exit( 0 );
	}
	
	function modal_view($eid, $pid)
	{
		$this->lang->load('date');
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
		$this->load->view('auth/pages/modal_view', $data);
	}
	
	function t_messages_view($t_messages_id, $pid)
	{
		$this->db->where('t_messages_id', $t_messages_id);
		$row = $this->db->get('t_messages')->row_array();
		$row1 = $this->demographics_model->get($pid)->row_array();
		$d = human_to_unix($row['t_messages_dos']);
		$dos = date('m/d/Y', $d);
		if ($row['t_messages_signed'] == 'Yes') {
			$status = "Signed";
		} else {
			$status = "Draft";
		}
		$text = '<strong>Patient:</strong>  ' . $row1['firstname'] . " " . $row1['lastname'] . '<br><br><strong>Status:</strong>  ' . $status . '<br><br><strong>Date:</strong>  ' . $dos . '<br><br><strong>Subject:</strong>  ' . $row['t_messages_subject'] . '<br><br><strong>Message:</strong> ' . nl2br($row['t_messages_message']); 
		echo $text;
	}
	
	function messages_view($message_id, $pid)
	{
		$this->db->where('message_id', $message_id);
		$row = $this->db->get('messaging')->row_array();
		$row1 = $this->demographics_model->get($pid)->row_array();
		$d = human_to_unix($row['date']);
		$date = date('m/d/Y', $d);
		$text = '<strong>Patient:</strong>  ' . $row1['firstname'] . " " . $row1['lastname'] . '<br><br><strong>Date:</strong>  ' . $date . '<br><br><strong>Subject:</strong>  ' . $row['subject'] . '<br><br><strong>Message:</strong> ' . nl2br($row['body']); 
		echo $text;
	}
	
	function appt_view($appt_id, $pid)
	{
		$this->db->where('appt_id', $appt_id);
		$row = $this->db->get('schedule')->row_array();
		$row1 = $this->demographics_model->get($pid)->row();
		$start = date('m/d/Y h:i A', $row['start']);
		$end = date('m/d/Y h:i A', $row['end']);
		$text = '<strong>Patient:</strong>  ' . $row1['firstname'] . " " . $row1['lastname'] . '<br><br><strong>Start Date:</strong>  ' . $start . '<br><br><strong>End Date:</strong>  ' . $end . '<br><br><strong>Visit Type:</strong> ' . $row['visit_type'] . '<br><br><strong>Reason:</strong> ' . $row['reason'] . '<br><br><strong>Status:</strong> ' . $row['status']; 
		echo $text;
	}
	
	function hippa_view($hippa_id, $pid)
	{
		$this->db->where('hippa_id', $hippa_id);
		$row = $this->db->get('hippa')->row_array();
		$row1 = $this->demographics_model->get($pid)->row();
		$d = human_to_unix($row['hippa_date_release']);
		$date = date('m/d/Y', $d);
		$text = '<strong>Patient:</strong>  ' . $row1['firstname'] . " " . $row1['lastname'] . '<br><br><strong>Date Released:</strong>  ' . $date . '<br><br><strong>Release to:</strong>  ' . $row['hippa_provider'] . '<br><br><strong>Reason:</strong> ' . $row['hippa_reason']; 
		echo $text;
	}
	
	function mtm_view($mtm_id, $pid)
	{
		$this->db->where('mtm_id', $mtm_id);
		$row = $this->db->get('mtm')->row_array();
		$row1 = $this->demographics_model->get($pid)->row();
		$text = '<strong>Patient:</strong>  ' . $row1['firstname'] . " " . $row1['lastname'];
		if ($row['mtm_date_completed'] != '') {
			$d = human_to_unix($row['mtm_date_completed']);
			$date = date('m/d/Y', $d);
			$text .= '<br><br><strong>Date Completed:</strong>  ' . $date;
		}
		$text .= '<br><br><strong>Description:</strong>  ' . nl2br($row['mtm_description']) . '<br><br><strong>Recommendations:</strong>  ' . nl2br($row['mtm_recommendations']) . '<br><br><strong>Beneficiary Notes:</strong>  ' . nl2br($row['mtm_beneficiary_notes']) . '<br><br><strong>Action:</strong>  ' . nl2br($row['mtm_action']) . '<br><br><strong>Outcomes:</strong>  ' . nl2br($row['mtm_outcomes']) . '<br><br><strong>Related Conditions:</strong>  ' . nl2br($row['mtm_related_conditions']);
		echo $text;
	}
	
	function documents_view($id, $pid)
	{
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
		$data['html'] = '<iframe src="' . base_url() . $name . '" width="770" height="425" style="border: none;"></iframe>';
		$data['id'] = $id;
		echo json_encode($data);
	}
	
	function close_document()
	{
		unlink($this->input->post('document_filepath'));
		echo 'OK';
	}
	
	function npi_lookup()
	{
		$this->load->library('domparser');
		$q = $this->input->post('term');
		$q_items = explode(",", $q);
		$data['response'] = "false";
		$lastname = $q_items[0];
		$firstname = $q_items[1];
		$state = $q_items[2];
		$url = 'http://docnpi.com/api/index.php?first_name=' . $firstname . '&last_name=' . $lastname . '&org_name=&address=&state=' . $state . '&city_name=&zip=&taxonomy=&ident=&is_person=true&is_address=false&is_org=false&is_ident=true&format=aha_table';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_FAILONERROR,1);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, 15);
		$data1 = curl_exec($ch);
		curl_close($ch);
		$html = $this->domparser->str_get_html($data1);
		if (isset($html)) {
			$table = $html->find('table[id=npi_results_table]',0);
			if (isset($table)) {
				$data['response'] = "true";
				$data['message'] = array();
				foreach ($table->find('tbody',0)->find('tr') as $tr) {
					if ($tr->class == "tr_ind") {
						$npi = $tr->id;
						$name_item = $tr->find('span[class=name_ind]',0);
						$name = $name_item->innertext;
						$address_item = $tr->find('span[class=address]',0);
						$address = $address_item->innertext;
						$specialty_item = $tr->find('span[class=tax]',0);
						$specialty = $specialty_item->innertext;
						$specialty = str_replace("( ","",$specialty);
						$specialty = str_replace(") ","",$specialty);
						$text = $name . "; Specialty: " . $specialty . "; Address: " . $address;
						$data['message'][] = array(
							'label' => $text,
							'value' => $npi
						);
					}
				}
			}
		}
		echo json_encode($data);
	}
	
	function payor_id($address_id)
	{
		$this->db->where('address_id', $address_id);
		$row = $this->db->get('addressbook')->row_array();
		if ($row['insurance_plan_payor_id'] == "") {
			$arr = "Unknown";
		} else {
			$arr = $row['insurance_plan_payor_id'];
		}
		echo $arr;
	}
	
	function demographics_copy()
	{
		$q = strtolower($this->input->post('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$this->db->from('demographics');
		$this->db->join('demographics_relate', 'demographics_relate.pid=demographics.pid');
		$this->db->like('demographics.lastname', $q);
		$this->db->or_like('demographics.firstname', $q);
		$this->db->or_like('demographics.pid', $q);
		$this->db->where('demographics_relate.practice_id', $this->session->userdata('practice_id'));
		$query = $this->db->get();
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
					'value' => $name,
					'address' => $row['address'],
					'city' => $row['city'],
					'state' => $row['state'],
					'zip' => $row['zip'],
					'phone_home' => $row['phone_home'],
					'phone_work' => $row['phone_work'],
					'phone_cell' => $row['phone_cell'],
					'email' => $row['email'],
					'emergency_contact' => $row['emergency_contact'],
					'emergency_phone' => $row['emergency_phone'],
					'reminder_method' => $row['reminder_method'],
					'cell_carrier' => $row['cell_carrier']
				);
			}
		}
		echo json_encode($data);
		exit( 0 );
	}
		
	function query_payment_type_list()
	{
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->where('payment_type IS NOT NULL');
		$this->db->select('payment_type');
		$this->db->distinct();
		$query = $this->db->get('billing_core');
		$data = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$key = $row['payment_type'];
				$data[$key] = $key;
			}
		}
		echo json_encode($data);
	}
	
	function query_cpt_list()
	{
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->select('cpt');
		$this->db->distinct();
		$query = $this->db->get('billing_core');
		$data = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$key = $row['cpt'];
				$data[$key] = $key;
			}
		}
		echo json_encode($data);
	}
	
	function query_year_list()
	{
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->select('dos_f');
		$this->db->distinct();
		$query = $this->db->get('billing_core');
		$data = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$date_array = explode("/", $row['dos_f']);
				if (isset($date_array[2])) {
					if (array_search($date_array[2], $data) === FALSE) {
						$key = $date_array[2];
						$data[$key] = $key;
					}
				}
			}
		}
		echo json_encode($data);
	}
	
	function financial_query()
	{
		$practice_id = $this->session->userdata('practice_id');
		$query_text1 = "SELECT * FROM billing_core WHERE practice_id=$practice_id";
		$variables_array = $this->input->post('variables');
		$type = $this->input->post('type');
		$i = 0;
		foreach ($variables_array[0] as $variable) {
			if ($i == 0) {
				if ($type == 'payment_type') {
					$query_text1 .= " AND payment_type = " . $this->db->escape($variable);
				}
				if ($type == 'cpt') {
					$query_text1 .= " AND cpt = " . $this->db->escape($variable);
				}
			} else {
				if ($type == 'payment_type') {
					$query_text1 .= " OR payment_type = " . $this->db->escape($variable);
				}
				if ($type == 'cpt') {
					$query_text1 .= " OR cpt = " . $this->db->escape($variable);
				}
			}
			$i++;
		}
		$year_array = $this->input->post('year');
		$j = 0;
		foreach ($year_array[0] as $year) {
			if ($j == 0) {
				$query_text1 .= " AND dos_f LIKE '%" . $this->db->escape_like_str($year) . "%'";
			} else {
				$query_text1 .= " OR dos_f LIKE '%" . $this->db->escape_like_str($year) . "%'";
			}
			$j++;
		}
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$sord = strtolower($sord);
		$query = $this->db->query($query_text1);
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query_text2 = $query_text1 . " ORDER BY " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		$query1 = $this->db->query($query_text2);
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$records1 = array();
		$k = 0;
		foreach ($records as $records_row) {
			$this->db->where('pid', $records_row['pid']);
			$query2_row = $this->db->get('demographics')->row_array();
			if ($type == 'payment_type') {
				$type1 = $records_row['payment_type'];
				$amount = $records_row['payment'];
			} else {
				$type1 = "CPT code: " . $records_row['cpt'];
				$amount = $records_row['cpt_charge'];
			}
			$records1[$k] = array(
				'billing_core_id' => $records_row['billing_core_id'],
				'dos_f' => $records_row['dos_f'],
				'lastname' => $query2_row['lastname'],
				'firstname' => $query2_row['firstname'],
				'amount' => $amount,
				'type' => $type1
			);
			$k++;
		}
		$response['rows'] = $records1;
		echo json_encode($response);
		exit( 0 );
	}
	
	function financial_query_print()
	{
		$practice_id = $this->session->userdata('practice_id');
		$query_text1 = "SELECT * FROM billing_core WHERE practice_id=$practice_id";
		$variables_array = $this->input->post('variables');
		$type = $this->input->post('type');
		$i = 0;
		foreach ($variables_array[0] as $variable) {
			if ($i == 0) {
				if ($type == 'payment_type') {
					$query_text1 .= " AND payment_type = " . $this->db->escape($variable);
				}
				if ($type == 'cpt') {
					$query_text1 .= " AND cpt = " . $this->db->escape($variable);
				}
			} else {
				if ($type == 'payment_type') {
					$query_text1 .= " OR payment_type = " . $this->db->escape($variable);
				}
				if ($type == 'cpt') {
					$query_text1 .= " OR cpt = " . $this->db->escape($variable);
				}
			}
			$i++;
		}
		$year_array = $this->input->post('year');
		$j = 0;
		foreach ($year_array[0] as $year) {
			if ($j == 0) {
				$query_text1 .= " AND dos_f LIKE '%" . $this->db->escape_like_str($year) . "%'";
			} else {
				$query_text1 .= " OR dos_f LIKE '%" . $this->db->escape_like_str($year) . "%'";
			}
			$j++;
		}
		$query_text1 .= " ORDER BY dos_f DESC";
		$query = $this->db->query($query_text1);
		if ($query->num_rows() > 0) {
			$records1 = array();
			$k = 0;
			foreach ($query->result_array() as $records_row) {
				$this->db->where('pid', $records_row['pid']);
				$query2_row = $this->db->get('demographics')->row_array();
				if ($type == 'payment_type') {
					$type1 = $records_row['payment_type'];
					$amount = $records_row['payment'];
				} else {
					$type1 = "CPT code: " . $records_row['cpt'];
					$amount = $records_row['cpt_charge'];
				}
				$records1[$k] = array(
					'billing_core_id' => $records_row['billing_core_id'],
					'dos_f' => $records_row['dos_f'],
					'lastname' => $query2_row['lastname'],
					'firstname' => $query2_row['firstname'],
					'amount' => $amount,
					'type' => $type1
				);
				$k++;
			}
			$response['id_doc'] = now() . "_" . $this->session->userdata('user_id');
			$file_path = "/var/www/nosh/financial_query_" . $response['id_doc'] . ".pdf";
			$html = $this->page_intro('Financial Query Results', $this->session->userdata('practice_id'));
			$html .= $this->page_results($records1);
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
			$response['message'] = "OK";
		} else {
			$response['message'] = "No result.";
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function financial_query_print1($id)
	{
		$file_path = "/var/www/nosh/financial_query_" . $id . ".pdf";
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
	
	function page_results($results)
	{
		$body = "<table><tr><th>Date</th><th>Last Name</th><th>First Name</th><th>Amount</th><th>Type</th></tr>";
		setlocale(LC_MONETARY, 'en_US.UTF-8');
		foreach ($results as $results_row1) {
			$body .= "<tr><td>" . $results_row1['dos_f'] . "</td><td>" . $results_row1['lastname'] . "</td><td>" . $results_row1['firstname'] . "</td><td>" . money_format('%n', $results_row1['amount']) . "</td><td>" . $results_row1['type'] . "</td></tr>";
		}
		$body .= '</table></body></html>';
		return $body;
	}
}
/* End of file: search.php */
/* Location: application/controllers/search.php */
