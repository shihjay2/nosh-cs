<?php

class Logs extends Application
{

	function Logs()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->auth->restrict('admin');
		$this->load->helper(array('text', 'typography'));
	}

	// --------------------------------------------------------------------

	function index()
	{
		$this->auth->view('admin/logs');
	}
	
	// --------------------------------------------------------------------
	
	function audit()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM audit WHERE practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM audit WHERE practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function extensions()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM extensions_log WHERE practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM extensions_log WHERE practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
} 
/* End of file: logs.php */
/* Location: application/controllers/admin/logs.php */
