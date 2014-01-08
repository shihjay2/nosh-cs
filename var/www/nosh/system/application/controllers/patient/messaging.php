<?php

class Messaging extends Application
{

	function Messaging()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->auth->restrict('patient');
		$this->load->model('encounters_model');
		$this->load->model('messaging_model');
		$this->load->model('chart_model');
		$this->load->model('demographics_model');
		$this->load->model('practiceinfo_model');
		$this->load->model('contact_model');
		$this->load->model('audit_model');
	}
	
	// --------------------------------------------------------------------

	function index()
	{
		$this->auth->view('patient/messaging');
	}
	
	function internal_inbox()
	{
		$id = $this->session->userdata('user_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM messaging WHERE mailbox=$id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM messaging WHERE mailbox=$id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			$user_id = $row['message_from'];
			$bodytext = nl2br($row['body']);
			$row1 = $this->db->query("SELECT displayname FROM users WHERE id=$user_id")->row_array();
			$displayname = $row1['displayname'] . ' (' . $row['message_from'] . ')';
			$response['rows'][$i]['id']=$row['message_id']; 
			$response['rows'][$i]['cell']=array($row['message_id'],$row['message_to'],$row['read'],$row['date'],$row['message_from'],$displayname,$row['subject'],$row['body'],$row['cc'],$row['pid'],$row['patient_name'],$bodytext,$row['t_messages_id']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function read_message($id)
	{
		$data = array(
			'read' => 'y'
		);
		$this->db->where('message_id', $id);
		$this->db->update('messaging', $data);
		$this->audit_model->update();
		echo "Message read.";
	}
	
	function get_displayname()
	{
		$this->db->where('id', $this->input->post('id'));
		$this->db->select('id, displayname');
		$row = $this->db->get('users')->row_array();
		$records = $row['displayname'] . ' (' . $row['id'] . ')';
		echo $records;
		exit( 0 );
	}
	
	function get_displayname1()
	{
		$to = explode(';', $this->input->post('id'));
		$records = '';
		foreach ($to as $id) {
			$this->db->where('id', $id);
			$this->db->select('id, displayname');
			$row = $this->db->get('users')->row_array();
			if ($records == '') {
				$records = $row['displayname'] . ' (' . $row['id'] . ')';
			} else {
				$records .= ';' . $row['displayname'] . ' (' . $row['id'] . ')';
			}
		}
		echo $records;
		exit( 0 );
	}
	
	function get_displayname2()
	{
		$pid = $this->session->userdata('pid');
		$this->db->where('pid', $pid);
		$query = $this->db->get('demographics');
		$row = $query->row_array();
		$dob1 = $row['DOB'];
		$dob2 = strtotime($dob1);
		$datestring = "%m/%d/%Y";
		$dob = mdate($datestring, $dob2);
		$name =  $row['lastname'] . ', ' . $row['firstname'] . ' (DOB: ' . $dob . ') (ID: ' . $pid . ')';
		$data = array(
			'id' => $pid,
			'value' => $name
		);
		echo json_encode($data);
		exit( 0 );
	}
	
	function send_message()
	{
		$message_id = $this->input->post('message_id');
		$from = $this->session->userdata('user_id');
		$t_messages_id = $this->input->post('t_messages_id');
		if ($this->input->post('patient_name') == '') {
			$subject = $this->input->post('subject');
		} else {
			$subject = $this->input->post('subject') . ' [RE: ' . $this->input->post('patient_name') . ']'; 
		}
		$mailbox = array();
		$messages_to = "";
		$i = 0;
		foreach ($this->input->post('message_to') as $key => $to_row) {
			$to_pos = strpos($to_row, "(");
			$to_pos = $to_pos + 1;
			$to_id = substr($to_row, $to_pos);
			$mailbox[] = str_replace(")", "", $to_id);
			if ($i > 0) {
				$messages_to .= ";" . $to_row;
			} else {
				$messages_to .= $to_row;
			}
			$i++;
		}
		$messages_cc = "";
		if ($this->input->post('cc') != '') {
			$j = 0;
			foreach ($this->input->post('cc') as $key1 => $cc_row) {
				$cc_pos = strpos($cc_row, "(");
				$cc_pos = $cc_pos + 1;
				$cc_id = substr($cc_row, $cc_pos);
				$mailbox[] = str_replace(")", "", $cc_id);
				if ($j > 0) {
					$messages_cc .= ";" . $cc_row;
				} else {
					$messages_cc .= $cc_row;
				}
				$j++;
			}
		}
		foreach ($mailbox as $mailbox_row) {
			if ($mailbox_row != '') {
				$data = array(
					'pid' => $this->input->post('pid'),
					'patient_name' => $this->input->post('patient_name'),
					'message_to' => $messages_to,
					'cc' => $messages_cc,
					'message_from' => $from,
					'subject' => $subject,
					'body' => $this->input->post('body'),
					't_messages_id' => $t_messages_id,
					'status' => 'Sent',
					'mailbox' => $mailbox_row,
					'practice_id' => $this->session->userdata('practice_id')
				);
				$this->messaging_model->add($data);
				$this->audit_model->add();
			}
		}
		$data1a = array(
			'pid' => $this->input->post('pid'),
			'patient_name' => $this->input->post('patient_name'),
			'message_to' => $messages_to,
			'cc' => $messages_cc,
			'message_from' => $from,
			'subject' => $subject,
			'body' => $this->input->post('body'),
			'status' => 'Sent',
			'mailbox' => '0',
			'practice_id' => $this->session->userdata('practice_id')
		);
		if ($message_id != '') {
			$this->messaging_model->update($message_id, $data1a);
			$this->audit_model->update();
		} else {
			$message_id = $this->messaging_model->add($data1a);
			$this->audit_model->add();
		}
		if ($t_messages_id != '' || $t_messages_id != '0') {
			$this->db->where('id', $from);
			$this->db->select('id, displayname');
			$row = $this->db->get('users')->row_array();
			$this->db->where('message_id', $message_id);
			$row1 = $this->db->get('messaging')->row_array();
			$date = explode(" ", $row1['date']);
			$displayname = $row['displayname'] . ' (' . $row['id'] . ')';
			$message1 = $this->input->post('body');
			$message = 'On ' . $row1['date'] . ', ' . $displayname . ' wrote:' . "\n---------------------------------\n" . $message1;
			$data1 = array(
				't_messages_message' => $message,
				't_messages_to' => ''
			);
			$this->chart_model->updateTMessage($t_messages_id, $data1);
			$this->audit_model->update();
		}
		echo 'Message sent!';
	}
	
	function draft_message()
	{
		$message_id = $this->input->post('message_id');
		$from = $this->session->userdata('user_id');
		$messages_to = "";
		$i = 0;
		foreach ($this->input->post('message_to') as $key => $to_row) {
			if ($i > 0) {
				$messages_to .= ";" . $to_row;
			} else {
				$messages_to .= $to_row;
			}
			$i++;
		}
		$messages_cc = "";
		if ($this->input->post('cc') != '') {
			$j = 0;
			foreach ($this->input->post('cc') as $key1 => $cc_row) {
				if ($j > 0) {
					$messages_cc .= ";" . $cc_row;
				} else {
					$messages_cc .= $cc_row;
				}
				$j++;
			}
		}
		$data = array(
			'pid' => $this->input->post('pid'),
			'patient_name' => $this->input->post('patient_name'),
			'message_to' => $messages_to,
			'cc' => $messages_cc,
			'message_from' => $from,
			'subject' => $this->input->post('subject'),
			'body' => $this->input->post('body'),
			'status' => 'Draft',
			'mailbox' => '0',
			'practice_id' => $this->session->userdata('practice_id')
		);
		if ($message_id != '') {
			$this->messaging_model->update($message_id, $data);
			$this->audit_model->update();
		} else {
			$this->messaging_model->add($data);
			$this->audit_model->add();
		}
		echo 'Message saved!';
	}
	
	function delete_message()
	{
		$message_id = $this->input->post('message_id');
		$this->messaging_model->delete($message_id);
		echo 'Message deleted!';
	}
	
	function internal_draft()
	{
		$id = $this->session->userdata('user_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM messaging WHERE message_from=$id AND mailbox='0' AND status='Draft'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM messaging WHERE message_from=$id AND mailbox='0' AND status='Draft' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			$response['rows'][$i]['id']=$row['message_id']; 
			$response['rows'][$i]['cell']=array($row['message_id'],$row['date'],$row['message_to'],$row['cc'],$row['subject'],$row['body'],$row['pid'],$row['patient_name']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function internal_outbox()
	{
		$id = $this->session->userdata('user_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM messaging WHERE message_from=$id AND mailbox='0' AND status='Sent'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM messaging WHERE message_from=$id AND mailbox='0' AND status='Sent' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			$bodytext = nl2br($row['body']);
			$response['rows'][$i]['id']=$row['message_id']; 
			$response['rows'][$i]['cell']=array($row['message_id'],$row['date'],$row['message_to'],$row['cc'],$row['subject'],$bodytext);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
} 
/* End of file: messaging.php */
/* Location: application/controllers/patient/messaging.php */
