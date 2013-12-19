<?php

class Users extends Application
{

	function Users()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->auth->restrict('admin');
		$this->load->helper(array('text', 'typography'));
		$this->load->model('users_model');
		$this->load->model('demographics_model');
	}

	// --------------------------------------------------------------------

	function index()
	{
		$this->auth->view('admin/users');
	}
	
	function inactive()
	{
		$this->load->view('auth/pages/admin/inactive_users');
	}
	

	// --------------------------------------------------------------------

	function users_list_provider()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM users, providers WHERE users.group_id=2 AND users.id=providers.id AND users.active=1 AND users.practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM users, providers WHERE users.group_id=2 AND users.id=providers.id AND users.active=1 AND users.practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function users_list_provider_inactive()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM users, providers WHERE users.group_id=2 AND users.id=providers.id AND users.active=0 AND users.practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM users, providers WHERE users.group_id=2 AND users.id=providers.id AND users.active=0 AND users.practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	// --------------------------------------------------------------------
	
	function edit_users_list_provider()
	{
		if($this->input->post('title') != ''){
			$displayname = $this->input->post('firstname') . " " . $this->input->post('lastname') . ", " . $this->input->post('title');
		} else {
			$displayname = $this->input->post('firstname') . " " . $this->input->post('lastname');
		}
		$specialty = substr($this->input->post('specialty'), 0, -13);
		$npi_taxonomy = substr($this->input->post('specialty'), -11, 10);
		$group_id = '2';
		$active = '1';
		$data1 = array(
			'username' => $this->input->post('username'),
			'firstname' => $this->input->post('firstname'),
			'middle' => $this->input->post('middle'),
			'lastname' => $this->input->post('lastname'),
			'title' => $this->input->post('title'),
			'displayname' => $displayname,
			'email' => $this->input->post('email'),
			'group_id' => $group_id,
			'active'=> $active,
			'practice_id' => $this->session->userdata('practice_id')
		);
		$data2 = array(
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
			'practice_id' => $this->session->userdata('practice_id')
		);
		$action = $this->input->post('oper');
		if ($action == 'edit') {
			$this->users_model->update($this->input->post('id'), $data1);
			$this->users_model->updateProvider($this->input->post('id'), $data2);
		}
		if ($action == 'add') {
			$id = $this->users_model->add($data1);
			$this->users_model->addProvider($id, $data2);
			$arr['id'] = $id;
			echo json_encode($arr);
		}
	}
	
	// --------------------------------------------------------------------
	
	function users_list_assistant()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM users WHERE group_id=3 AND active=1 AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM users WHERE group_id=3 AND active=1 AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function users_list_assistant_inactive()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM users WHERE group_id=3 AND active=0 AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM users WHERE group_id=3 AND active=0 AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}

	// --------------------------------------------------------------------
	
	function edit_users_list_assistant()
	{
		if($this->input->post('title') != ''){
			$displayname = $this->input->post('firstname') . " " . $this->input->post('lastname') . ", " . $this->input->post('title');
		} else {
			$displayname = $this->input->post('firstname') . " " . $this->input->post('lastname');
		}
		$group_id = '3';
		$active = '1';
		$data = array(
			'username' => $this->input->post('username'),
			'firstname' => $this->input->post('firstname'),
			'middle' => $this->input->post('middle'),
			'lastname' => $this->input->post('lastname'),
			'title' => $this->input->post('title'),
			'displayname' => $displayname,
			'email' => $this->input->post('email'),
			'group_id' => $group_id,
			'active'=> $active,
			'practice_id' => $this->session->userdata('practice_id')
		);
		$action = $this->input->post('oper');
		if ($action == 'edit') {
			$this->users_model->update($this->input->post('id'), $data);
		}
		if ($action == 'add') {
			$arr['id'] = $this->users_model->add($data);
			echo json_encode($arr);
		}
	}
	
	// --------------------------------------------------------------------
	
	function users_list_billing()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM users WHERE group_id=4 AND active=1 AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM users WHERE group_id=4 AND active=1 AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function users_list_billing_inactive()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM users WHERE group_id=4 AND active=0 AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM users WHERE group_id=4 AND active=0 AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	// --------------------------------------------------------------------
	
	function edit_users_list_billing()
	{
		if($this->input->post('title') != ''){
			$displayname = $this->input->post('firstname') . " " . $this->input->post('lastname') . ", " . $this->input->post('title');
		} else {
			$displayname = $this->input->post('firstname') . " " . $this->input->post('lastname');
		}
		$group_id = '4';
		$active = '1';
		$data = array(
			'username' => $this->input->post('username'),
			'firstname' => $this->input->post('firstname'),
			'middle' => $this->input->post('middle'),
			'lastname' => $this->input->post('lastname'),
			'title' => $this->input->post('title'),
			'displayname' => $displayname,
			'email' => $this->input->post('email'),
			'group_id' => $group_id,
			'active'=> $active,
			'practice_id' => $this->session->userdata('practice_id')
		);
		$action = $this->input->post('oper');
		if ($action == 'edit') {
			$this->users_model->update($this->input->post('id'), $data);
		}
		if ($action == 'add') {
			$arr['id'] = $this->users_model->add($data);
			echo json_encode($arr);
		}
	}
	
	// --------------------------------------------------------------------
	
	function users_list_patient()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT users.*, demographics.pid FROM users LEFT JOIN demographics ON users.id = demographics.id WHERE users.group_id=100 AND users.active=1 AND users.practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT users.*, demographics.pid FROM users LEFT JOIN demographics ON users.id = demographics.id WHERE users.group_id=100 AND users.active=1 AND users.practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function users_list_patient_inactive()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT users.*, demographics.pid FROM users LEFT JOIN demographics ON users.id = demographics.id WHERE users.group_id=100 AND users.active=0 AND users.practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT users.*, demographics.pid FROM users LEFT JOIN demographics ON users.id = demographics.id WHERE users.group_id=100 AND users.active=0 AND users.practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	// --------------------------------------------------------------------
	
	function edit_users_list_patient()
	{
		if($this->input->post('title') != ''){
			$displayname = $this->input->post('firstname') . " " . $this->input->post('lastname') . ", " . $this->input->post('title');
		} else {
			$displayname = $this->input->post('firstname') . " " . $this->input->post('lastname');
		}
		$group_id = '100';
		$active = '1';
		$data1 = array(
			'username' => $this->input->post('username'),
			'firstname' => $this->input->post('firstname'),
			'middle' => $this->input->post('middle'),
			'lastname' => $this->input->post('lastname'),
			'title' => $this->input->post('title'),
			'displayname' => $displayname,
			'email' => $this->input->post('email'),
			'group_id' => $group_id,
			'active'=> $active,
			'practice_id' => $this->session->userdata('practice_id')
		);
		$action = $this->input->post('oper');
		if ($action == 'edit') {
			$this->users_model->update($this->input->post('id'), $data1);
			$data2 = array(
				'id' => $this->input->post('id')
			);
			$this->demographics_model->update($this->input->post('pid'), $data2);
		}
		if ($action == 'add') {
			$id = $this->users_model->add($data1);
			$data2 = array(
				'id' => $id
			);
			$this->demographics_model->update($this->input->post('pid'), $data2);
			$arr['id'] = $id;
			echo json_encode($arr);
		}
	}
	
	// --------------------------------------------------------------------
	
	function enable()
	{
		$active = '1';
		$password = $this->auth->_salt($this->input->post('password'));
		$data = array(
			'active' => $active,
			'password' => $password
		);
		$this->users_model->update($this->input->post('id'), $data);
	}
	
	function disable()
	{
		$active = '0';
		$disable = 'disable';
		$password = $this->auth->_salt($disable);
		$data = array(
			'active' => $active,
			'password' => $password
		);
		$this->users_model->update($this->input->post('id'), $data);
		$this->db->where('id', $this->input->post('id'));
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$query = $this->db->get('demographics_relate');
		if ($query->num_rows() > 0) {
			$data1 = array(
				'id' => NULL
			);
			$row = $query->row_array();
			$this->db->where('demographics_relate_id', $row['demographics_relate_id']);
			$this->db->update('demographics_relate', $data1);
		}
	}
	
	function reset_password()
	{
		$data['password'] = $this->auth->_salt($this->input->post('password'));
		$this->users_model->update($this->input->post('id'), $data);
		echo "Password changed!";
	}
	
	function check_admin()
	{
		$practice_id = $this->session->userdata('practice_id');
		if ($practice_id == '1') {
			$arr = "OK";
		} else {
			$this->db->where('practice_id', $practice_id);
			$row = $this->db->get('practiceinfo')->row_array();
			$query = $this->db->query("SELECT * FROM users, providers WHERE users.group_id=2 AND users.id=providers.id AND users.active=1 AND users.practice_id=$practice_id");
			$count = $query->num_rows(); 
			if ($row['provider_limit'] <= $count) {
				$arr = "No more providers can be added based on your provider limit for your practice account.  Please upgrade your subscription to enable additional providers!";
			} else {
				$arr = "OK";
			}
		}
		echo $arr;
	}
} 
/* End of file: users.php */
/* Location: application/controllers/admin/users.php */
