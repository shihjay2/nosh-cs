<?php

class Chartmenu extends Application
{

	function Chartmenu()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->auth->restrict('admin');
		$this->load->model('demographics_model');	
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
	
	function inactivate_pt()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == '') {
			redirect('start');
		}
		
		$data = array(
			'active' => '0'
		);
		$this->demographics_model->update($pid, $data);
	}
	
	function delete_pt()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == '') {
			redirect('start');
		}
		
		$this->demographics_model->delete($pid);
	}
} 
/* End of file: chartmenu.php */
/* Location: application/controllers/provider/chartmenu.php */
