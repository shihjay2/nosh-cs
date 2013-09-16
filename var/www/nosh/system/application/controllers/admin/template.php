<?php

class Template extends Application
{

	function Template()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->auth->restrict('admin');
		$this->load->helper(array('text', 'typography'));
		$this->load->model('template_model');	
	}

	// --------------------------------------------------------------------

	function index()
	{
		$this->auth->view('admin/template');
	}
	
	// --------------------------------------------------------------------
	
	function familyhxlist()
	{
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');

		$query = $this->db->query("SELECT * FROM familyhxlist");
		$count = $query->num_rows(); 

		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}

		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;

		$query1 = $this->db->query("SELECT * FROM familyhxlist ORDER BY $sidx $sord LIMIT $start , $limit");

		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;

		$records = $query1->result_array();
		$response->rows = $records;
		
		echo json_encode($response);
		exit( 0 );
	}
	
	function procedurelist()
	{
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');

		$query = $this->db->query("SELECT * FROM procedurelist");
		$count = $query->num_rows(); 

		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}

		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;

		$query1 = $this->db->query("SELECT * FROM procedurelist ORDER BY $sidx $sord LIMIT $start , $limit");

		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;

		$records = $query1->result_array();
		$response->rows = $records;
		
		echo json_encode($response);
		exit( 0 );
	}
	
	function orderslist()
	{
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');

		$query = $this->db->query("SELECT * FROM orderslist");
		$count = $query->num_rows(); 

		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 

		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;

		$query1 = $this->db->query("SELECT * FROM orderslist ORDER BY $sidx $sord LIMIT $start , $limit");

		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;

		$records = $query1->result_array();
		$response->rows = $records;
		
		echo json_encode($response);
		exit( 0 );
	}
	
	// --------------------------------------------------------------------
	
	function edit_familyhxlist()
	{
		$data = array(
			'familyhxlist_category' => $this->input->post('familyhxlist_category'),
			'familyhxlist_item' => $this->input->post('familyhxlist_item')
		);
		
		$action = $this->input->post('oper');
							
		if ($action == 'edit')
		{		
			$this->template_model->update_familyhx($this->input->post('id'), $data);
		}
		
		if ($action == 'add')
		{
			$this->template_model->add_familyhx($data);
		}
		
		if ($action == 'del')
		{
			$this->template_model->del_familyhx($this->input->post('id'));
		}
	}
	
	function edit_procedurelist()
	{
		$$data = array(
			'procedure_type' => $this->input->post('procedure_type'),
			'procedure_description' => $this->input->post('procedure_description'),
			'procedure_complications' => $this->input->post('procedure_complications'),
			'procedure_ebl' => $this->input->post('procedure_ebl'),
		);
		
		$action = $this->input->post('oper');
							
		if ($action == 'edit')
		{		
			$this->template_model->update_procedurelist($this->input->post('id'), $data);
		}
		
		if ($action == 'add')
		{
			$this->template_model->add_procedurelist($data);
		}
		
		if ($action == 'del')
		{
			$this->template_model->del_procedurelist($this->input->post('id'));
		}
	}
	
	function edit_orderslist()
	{
		$data = array(
			'orders_category' => $this->input->post('orders_category'),
			'orders_description' => $this->input->post('orders_description')
		);
		
		$action = $this->input->post('oper');
							
		if ($action == 'edit')
		{		
			$this->template_model->update_orderslist($this->input->post('id'), $data);
		}
		
		if ($action == 'add')
		{
			$this->template_model->add_orderslist($data);
		}
		
		if ($action == 'del')
		{
			$this->template_model->del_orderslist($this->input->post('id'));
		}
	}
} 
/* End of file: template.php */
/* Location: application/controllers/admin/template.php */
