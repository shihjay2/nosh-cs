<?php

class Office extends Application
{

	function Office()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->auth->restrict('assistant');
		$this->load->model('office_model');
		$this->load->model('audit_model');
	}

	// --------------------------------------------------------------------

	function index()
	{
		$this->auth->view('assistant/office');
	}

	// --------------------------------------------------------------------
	
	function vaccine_inventory()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM vaccine_inventory WHERE quantity>0 AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM vaccine_inventory WHERE quantity>0 AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );	
	}
	
	function vaccine_inventory_inactive()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM vaccine_inventory WHERE quantity=0 AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM vaccine_inventory WHERE quantity=0 AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );	
	}
	
	function edit_vaccine()
	{
		$date = strtotime($this->input->post('imm_expiration'));
		$date1 = strtotime($this->input->post('date_purchase'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$expiration = mdate($datestring, $date);
		$purchase = mdate($datestring, $date1);
		$data = array(
			'imm_immunization' => $this->input->post('imm_immunization'),
			'imm_cvxcode' => $this->input->post('imm_cvxcode'),
			'imm_manufacturer' => $this->input->post('imm_manufacturer'),
			'imm_brand' => $this->input->post('imm_brand'),
			'imm_lot' => $this->input->post('imm_lot'),
			'quantity' => $this->input->post('quantity'),
			'cpt' => $this->input->post('cpt'),
			'imm_expiration' => $expiration,
			'date_purchase'=> $purchase,
			'practice_id' => $this->session->userdata('practice_id')
		);
		if($this->input->post('vaccine_id') == '') {
			$add = $this->office_model->addVaccineInventory($data);
			$this->audit_model->add();
			if ($add) {
				echo "Vaccine added!";
				exit (0);
			} else {
				echo "Error adding vaccine!";
				exit (0);
			}
		} else {
			$update = $this->office_model->updateVaccineInventory($this->input->post('vaccine_id'), $data);
			$this->audit_model->update();
			echo "Vaccine updated!";
			exit (0);
		}
	}
	
	function inactivate_vaccine()
	{
		$vaccine_id = $this->input->post('vaccine_id');
		$data = array(
			'quantity' => 0
		);
		$update = $this->office_model->updateVaccineInventory($vaccine_id, $data);
		$this->audit_model->update();
		echo "Vaccine inactivated!";
		exit (0);
	}
	
	function delete_vaccine()
	{
		$this->office_model->deleteVaccineInventory($this->input->post('vaccine_id'));
		$this->audit_model->delete();
		echo "Vaccine deleted!";
		exit (0);
	}

	function reactivate_vaccine()
	{
		$vaccine_id = $this->input->post('vaccine_id');
		$quantity = $this->input->post('quantity');
		$data = array(
			'quantity' => $quantity
		);
		$update = $this->office_model->updateVaccineInventory($vaccine_id, $data);
		$this->audit_model->update();
		echo "Vaccine reactivated!";
		exit (0);
	}
	
	// --------------------------------------------------------------------
	
	function supplement_inventory()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM supplement_inventory WHERE quantity>0 AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM supplement_inventory WHERE quantity>0 AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function supplement_inventory_inactive()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM supplement_inventory WHERE quantity<=0 AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM supplement_inventory WHERE quantity<=0 AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function edit_supplement()
	{
		$date = strtotime($this->input->post('sup_expiration'));
		$date1 = strtotime($this->input->post('date_purchase'));
		$datestring = "%Y-%m-%d %H:%i:%s";
		$expiration = mdate($datestring, $date);
		$purchase = mdate($datestring, $date1);
		$data = array(
			'sup_description' => $this->input->post('sup_description'),
			'sup_strength' => $this->input->post('sup_strength'),
			'sup_manufacturer' => $this->input->post('sup_manufacturer'),
			'quantity' => $this->input->post('quantity'),
			'charge' => $this->input->post('charge'),
			'sup_expiration' => $expiration,
			'date_purchase'=> $purchase,
			'sup_lot' => $this->input->post('sup_lot'),
			'practice_id' => $this->session->userdata('practice_id')
		);
		if($this->input->post('cpt') != '') {
			$data['cpt'] = $this->input->post('cpt');
		} else {
			$this->db->like('cpt', 'sp');
			$this->db->select('cpt');
			$cpt_query = $this->db->get('cpt_relate');
			if ($cpt_query->num_rows() > 0) {
				$cpt_array = $cpt_query->result_array();
				rsort($cpt_array);
				$cpt_num = str_replace("sp", "", $cpt_array[0]['cpt']);
				$cpt_num_new = $cpt_num + 1;
				$cpt_num_new = str_pad($cpt_num_new, 3, "0", STR_PAD_LEFT);
				$data['cpt'] = 'sp' . $cpt_num_new;
			} else {
				$data['cpt'] = 'sp001';
			}
		}
		$data1['cpt'] = $data['cpt'];
		$charge = str_replace("$", "", $this->input->post('charge'));
		$data1['cpt_charge'] = $charge;
		$pos = strpos($charge, ".");
		if ($pos === FALSE) {
			$charge .= ".00";
		}
		$data1['cpt_description'] = $this->input->post('sup_description');
		$this->db->where('cpt', $data1['cpt']);
		$cpt_query1 = $this->db->get('cpt_relate');
		if ($cpt_query1->num_rows() > 0) {
			$this->db->where('cpt', $data1['cpt']);
			$this->db->update('cpt_relate', $data1);
			$this->audit_model->update();
		} else {
			$this->db->insert('cpt_relate', $data1);
			$this->audit_model->add();
		}
		if($this->input->post('supplement_id') == '') {
			$add = $this->office_model->addSupplementInventory($data);
			$this->audit_model->add();
			if ($add) {
				echo "Supplement added!";
				exit (0);
			} else {
				echo "Error adding supplement!";
				exit (0);
			}
		} else {
			$update = $this->office_model->updateSupplementInventory($this->input->post('supplement_id'), $data);
			$this->audit_model->update();
			echo "Supplement updated!";
			exit (0);
		}
	}
	
	function inactivate_supplement()
	{
		$supplement_id = $this->input->post('supplement_id');
		$data = array(
			'quantity' => 0
		);
		$update = $this->office_model->updateSupplementInventory($supplement_id, $data);
		$this->audit_model->update();
		echo "Supplement inactivated!";
		exit (0);
	}
	
	function delete_supplement()
	{
		$this->office_model->deleteSupplementInventory($this->input->post('supplement_id'));
		$this->audit_model->delete();
		echo "Supplement deleted!";
		exit (0);
	}

	function reactivate_supplement()
	{
		$supplement_id = $this->input->post('supplement_id');
		$quantity = $this->input->post('quantity');
		$data = array(
			'quantity' => $quantity
		);
		$update = $this->office_model->updateSupplementInventory($supplement_id, $data);
		$this->audit_model->update();
		echo "Supplement reactivated!";
		exit (0);
	}
	
	// --------------------------------------------------------------------
	
	function vaccine_temp()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM vaccine_temp WHERE practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM vaccine_temp WHERE practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );	
	}
	
	function edit_temp()
	{
		$strdate = $this->input->post('temp_date') . ' ' . $this->input->post('temp_time');
		$strdate1 = strtotime($strdate);
		$datestring = "%Y-%m-%d %H:%i:%s";
		$date = mdate($datestring, $strdate1);
		$data = array(
			'temp' => $this->input->post('temp'),
			'date'=> $date,
			'action' => $this->input->post('action'),
			'practice_id' => $this->session->userdata('practice_id')
		);
		if($this->input->post('temp_id') == '') {
			$add = $this->office_model->addVaccineTemp($data);
			$this->audit_model->add();
			if ($add) {
				echo "Vaccine temperature added!";
				exit (0);
			} else {
				echo "Error adding vaccine temperature!";
				exit (0);
			}
		} else {
			$update = $this->office_model->updateVaccineTemp($this->input->post('temp_id'), $data);
			$this->audit_model->update();
			echo "Vaccine temperature updated!";
			exit (0);
		}
	}
	
	function delete_temp()
	{
		$this->office_model->deleteVaccineTemp($this->input->post('temp_id'));
		$this->audit_model->delete();
		echo "Vaccine deleted!";
		exit (0);
	}
	
	// --------------------------------------------------------------------
	
	function super_query()
	{
		$practice_id = $this->session->userdata('practice_id');
		$search_field = $this->input->post('search_field');
		$search_op = $this->input->post('search_op');
		$search_desc = $this->input->post('search_desc');
		$search_join = $this->input->post('search_join');
		$search_active_only = $this->input->post('search_active_only');
		$search_no_insurance_only = $this->input->post('search_no_insurance_only');
		$search_gender = $this->input->post('search_gender');
		$query_text1 = "SELECT DISTINCT demographics.pid, demographics.lastname, demographics.firstname, demographics.DOB FROM demographics";
		$query_text_join = " JOIN demographics_relate ON demographics.pid=demographics_relate.pid";
		$query_text = " WHERE demographics_relate.practice_id=$practice_id";
		for($i = 0; $i<count($search_field); $i++)
		{
			if(isset($search_field[$i])) {
				if($search_field[$i] == 'age') {
					$ago = strtotime($search_desc[$i] . " years ago");
					$unix_target1 = $ago - 15778463;
					$unix_target2 = $ago + 15778463;
					$target1 = date('Y-m-d 00:00:00', $unix_target1);
					$target2 = date('Y-m-d 00:00:00', $unix_target2);
					if($search_op[$i] == 'equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND demographics.DOB BETWEEN " . $this->db->escape($target1) . " AND " . $this->db->escape($target2);
							} else {
								$query_text .= " OR demographics.DOB BETWEEN " . $this->db->escape($target1) . " AND " . $this->db->escape($target2);
							}		
						} else {
							$query_text .= " AND demographics.DOB BETWEEN " . $this->db->escape($target1) . " AND " . $this->db->escape($target2);
						}
					}
					if($search_op[$i] == 'greater than') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND demographics.DOB < " . $this->db->escape($target1);
							} else {
								$query_text .= " OR demographics.DOB < " . $this->db->escape($target1);
							}		
						} else {
							$query_text .= " AND demographics.DOB < " . $this->db->escape($target1);
						}
					}
					if($search_op[$i] == 'less than') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND demographics.DOB > " . $this->db->escape($target2);
							} else {
								$query_text .= " OR demographics.DOB > " . $this->db->escape($target2);
							}		
						} else {
							$query_text .= " AND demographics.DOB > " . $this->db->escape($target2);
						}
					}
				}
				if($search_field[$i] == 'insurance') {
					$query_text_join .= " JOIN insurance ON insurance.pid=demographics.pid";
					if($search_op[$i] == 'equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND insurance.insurance_order = 'Primary' AND insurance.insurance_plan_active = 'Yes' AND insurance.insurance_plan_name = " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " AND insurance.insurance_order = 'Primary' AND insurance.insurance_plan_active = 'Yes' OR insurance.insurance_plan_name = " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND insurance.insurance_order = 'Primary' AND insurance.insurance_plan_active = 'Yes' AND insurance.insurance_plan_name = " . $this->db->escape($search_desc[$i]);
						}
					}
					if($search_op[$i] == 'contains') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND insurance.insurance_order = 'Primary' AND insurance.insurance_plan_active = 'Yes' AND insurance.insurance_plan_name LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
							} else {
								$query_text .= " AND insurance.insurance_order = 'Primary' AND insurance.insurance_plan_active = 'Yes' OR insurance.insurance_plan_name LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
							}		
						} else {
							$query_text .= " AND insurance.insurance_order = 'Primary' AND insurance.insurance_plan_active = 'Yes' AND insurance.insurance_plan_name LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
						}
					}
				}
				if($search_field[$i] == 'issue') {
					$query_text_join .= " JOIN issues ON issues.pid=demographics.pid";
					if($search_op[$i] == 'equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND issues.issue = " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " OR issues.issue = " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND issues.issue = " . $this->db->escape($search_desc[$i]);
						}
					}
					if($search_op[$i] == 'contains') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND issues.issue LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
							} else {
								$query_text .= " OR issues.issue LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
							}		
						} else {
							$query_text .= " AND issues.issue LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
						}
					}
					if($search_op[$i] == 'not equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND issues.issue != " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " OR issues.issue != " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND issues.issue != " . $this->db->escape($search_desc[$i]);
						}
					}
					$query_text .= " AND issues.issue_date_inactive='0000-00-00 00:00:00'";
				}
				if($search_field[$i] == 'billing') {
					$query_text_join .= " JOIN billing_core ON billing_core.pid=demographics.pid";
					if($search_op[$i] == 'equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND billing_core.cpt = " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " OR billing_core.cpt = " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND billing_core.cpt = " . $this->db->escape($search_desc[$i]);
						}
					}
					if($search_op[$i] == 'not equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND billing_core.cpt != " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " OR billing_core.cpt != " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND billing_core.cpt != " . $this->db->escape($search_desc[$i]);
						}
					}
				}
				if($search_field[$i] == 'rxl_medication') {
					$query_text_join .= " JOIN rx_list ON rx_list.pid=demographics.pid";
					if($search_op[$i] == 'equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND rx_list.rxl_medication = " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " OR rx_list.rxl_medication = " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND rx_list.rxl_medication = " . $this->db->escape($search_desc[$i]);
						}
					}
					if($search_op[$i] == 'contains') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND rx_list.rxl_medication LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
							} else {
								$query_text .= " OR rx_list.rxl_medication LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
							}		
						} else {
							$query_text .= " AND rx_list.rxl_medication LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
						}
					}
					if($search_op[$i] == 'not equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND rx_list.rxl_medication != " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " OR rx_list.rxl_medication != " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND rx_list.rxl_medication != " . $this->db->escape($search_desc[$i]);
						}
					}
					$query_text .= " AND rx_list.rxl_date_inactive='0000-00-00 00:00:00' AND rx_list.rxl_date_old='0000-00-00 00:00:00'";
				}
				if($search_field[$i] == 'imm_immunization') {
					$query_text_join .= " JOIN immunizations ON immunizations.pid=demographics.pid";
					if($search_op[$i] == 'equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND immunizations.imm_immunization = " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " OR immunizations.imm_immunization = " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND immunizations.imm_immunization = " . $this->db->escape($search_desc[$i]);
						}
					}
					if($search_op[$i] == 'contains') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND immunizations.imm_immunization LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
							} else {
								$query_text .= " OR immunizations.imm_immunization LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
							}		
						} else {
							$query_text .= " AND immunizations.imm_immunization LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
						}
					}
					if($search_op[$i] == 'not equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND immunizations.imm_immunization != " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " OR immunizations.imm_immunization != " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND immunizations.imm_immunization != " . $this->db->escape($search_desc[$i]);
						}
					}
				}
				if($search_field[$i] == 'sup_supplement') {
					$query_text_join .= " JOIN sup_list ON sup_list.pid=demographics.pid";
					if($search_op[$i] == 'equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND sup_list.sup_supplement = " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " OR sup_list.sup_supplement = " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND sup_list.sup_supplement = " . $this->db->escape($search_desc[$i]);
						}
					}
					if($search_op[$i] == 'contains') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND sup_list.sup_supplement LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
							} else {
								$query_text .= " OR sup_list.sup_supplement LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
							}		
						} else {
							$query_text .= " AND sup_list.sup_supplement LIKE '%" . $this->db->escape_like_str($search_desc[$i]) . "%'";
						}
					}
					if($search_op[$i] == 'not equal') {
						if($search_join[$i] != "start") {
							if($search_join[$i] == 'AND') {
								$query_text .= " AND sup_list.sup_supplement != " . $this->db->escape($search_desc[$i]);
							} else {
								$query_text .= " OR sup_list.sup_supplement != " . $this->db->escape($search_desc[$i]);
							}		
						} else {
							$query_text .= " AND sup_list.sup_supplement != " . $this->db->escape($search_desc[$i]);
						}
					}
					$query_text .= " AND sup_list.sup_date_inactive='0000-00-00 00:00:00'";
				}
			}
		}
		if($search_active_only == "Yes") {
			$query_text .= " AND demographics.active = '1'";
		}
		if($search_no_insurance_only == "Yes") {
			$query_text_join .= " LEFT JOIN insurance ON insurance.pid=demographics.pid";
			$query_text .= " AND insurance.pid IS NULL";
		}
		if($search_gender == "m" || $search_gender == "f") {
			$query_text .= " AND demographics.sex = '" . $search_gender. "'";
		}
		$query_full_text = $query_text1 . $query_text_join . $query_text;
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$sord = strtolower($sord);
		$query = $this->db->query($query_full_text);
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query_text1 = $query_full_text . " ORDER BY " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		$query1 = $this->db->query($query_text1);
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );	
	}
	
	function age_percentage()
	{
		$practice_id = $this->session->userdata('practice_id');
		$current_date = now();
		$this->db->select('*');
		$this->db->from('demographics');
		$this->db->join('demographics_relate', 'demographics_relate.pid = demographics.pid');
		$this->db->where('demographics.active', '1');
		$this->db->where('demographics_relate.practice_id', $practice_id);
		$total = $this->db->get()->num_rows();
		
		$a = $current_date - 568024668;
		$a1 = date('Y-m-d H:i:s', $a);
		$this->db->select('*');
		$this->db->from('demographics');
		$this->db->join('demographics_relate', 'demographics_relate.pid = demographics.pid');
		$this->db->where('demographics.DOB >=', $a1);
		$this->db->where('demographics.active', '1');
		$this->db->where('demographics_relate.practice_id', $practice_id);
		$num1 = $this->db->get()->num_rows();
		
		$b = $current_date - 2051200190;
		$b1 = date('Y-m-d H:i:s', $b);
		$this->db->select('*');
		$this->db->from('demographics');
		$this->db->join('demographics_relate', 'demographics_relate.pid = demographics.pid');
		$this->db->where('demographics.DOB <', $a1);
		$this->db->where('demographics.DOB >=', $b1);
		$this->db->where('demographics.active', '1');
		$this->db->where('demographics_relate.practice_id', $practice_id);
		$num2 = $this->db->get()->num_rows();
		
		$this->db->select('*');
		$this->db->from('demographics');
		$this->db->join('demographics_relate', 'demographics_relate.pid = demographics.pid');
		$this->db->where('demographics.DOB <', $b1);
		$this->db->where('demographics.active', '1');
		$this->db->where('demographics_relate.practice_id', $practice_id);
		$num3 = $this->db->get()->num_rows();
		
		$result['group1'] = round($num1/$total*100) . "% of patients";
		$result['group2'] = round($num2/$total*100) . "% of patients";
		$result['group3'] = round($num3/$total*100) . "% of patients";
		echo json_encode($result);
		exit (0);
	}
	
	function export_demographics($type)
	{
		$practice_id = $this->session->userdata('practice_id');
		$this->load->dbutil();
		$this->load->helper('download');
		if ($type == "all") {
			$query = $this->db->query("SELECT * FROM demographics JOIN demographics_relate ON demographics.pid=demographics_relate.pid  WHERE demographics_relate.practice_id=$practice_id");
		} else {
			$query = $this->db->query("SELECT * FROM demographics WHERE active='1'");
		}
		$result = $this->dbutil->csv_from_result($query);
		$date = now();
		$date1 = date('Ymd', $date);
		$name = $date1 . '_demographics.txt';
		force_download($name, $result);
	}
} 
/* End of file: office.php */
/* Location: application/controllers/assistant/office.php */
