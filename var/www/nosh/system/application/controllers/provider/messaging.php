<?php

class Messaging extends Application
{

	function Messaging()
	{
		parent::Application();
		$this->load->database();
		$this->load->helper('download');
		$this->load->library('session');
		$this->load->library('email');
		$this->auth->restrict('provider');
		$this->load->model('encounters_model');
		$this->load->model('messaging_model');
		$this->load->model('chart_model');
		$this->load->model('demographics_model');
		$this->load->model('practiceinfo_model');
		$this->load->model('fax_model');
		$this->load->model('contact_model');
		$this->load->model('audit_model');
	}
	
	// --------------------------------------------------------------------

	function index()
	{
		$this->auth->view('provider/messaging');
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
			$response['rows'][$i]['cell']=array($row['message_id'],$row['message_to'],$row['read'],$row['date'],$row['message_from'],$displayname,$row['subject'],$row['body'],$row['cc'],$row['pid'],$row['patient_name'],$bodytext,$row['t_messages_id'],$row['documents_id']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function read_message($id, $documents_id="")
	{
		$data = array(
			'read' => 'y'
		);
		$this->db->where('message_id', $id);
		$this->db->update('messaging', $data);
		$this->audit_model->update();
		$arr = "Message read.";
		if ($documents_id != "") {
			$data1 = array(
				'documents_viewed' => $this->session->userdata('displayname')
			);
			$this->db->where('documents_id', $documents_id);
			$this->db->update('documents', $data1);
			$this->audit_model->update();
			$arr .= "  Test(s) marked as reviewed.";
		}
		echo $arr;
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
		$to = explode(',', $this->input->post('id'));
		$records = '';
		foreach ($to as $id) {
			$this->db->where('id', $id);
			$this->db->select('id, displayname');
			$row = $this->db->get('users')->row_array();
			if ($records == '') {
				$records = $row['displayname'] . ' (' . $row['id'] . ')';
			} else {
				$records .= ', ' . $row['displayname'] . ' (' . $row['id'] . ')';
			}
		}
		echo $records;
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
		$to = explode(';', $this->input->post('message_to'));
		foreach ($to as $to_row) {
			$to_pos = strpos($to_row, "(");
			$to_pos = $to_pos + 1;
			$to_id = substr($to_row, $to_pos);
			$mailbox[] = str_replace(")", "", $to_id);
		}
		if ($this->input->post('cc') != '') {
			$cc = explode(';', $this->input->post('cc'));
			foreach ($cc as $cc_row) {
				$cc_pos = strpos($cc_row, "(");
				$cc_pos = $cc_pos + 1;
				$cc_id = substr($cc_row, $cc_pos);
				$mailbox[] = str_replace(")", "", $cc_id);
			}
		}
		foreach ($mailbox as $mailbox_row) {
			if ($mailbox_row != '') {
				$data = array(
					'pid' => $this->input->post('pid'),
					'patient_name' => $this->input->post('patient_name'),
					'message_to' => $this->input->post('message_to'),
					'cc' => $this->input->post('cc'),
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
				$this->db->where('id', $mailbox_row);
				$user_row = $this->db->get('users')->row_array();
				if ($user_row['group_id'] === '100') {
					$config['protocol']='smtp';
					$config['smtp_host']='ssl://smtp.googlemail.com';
					$config['smtp_port']='465';
					$config['smtp_timeout']='30';
					$config['charset']='utf-8';
					$config['newline']="\r\n";
					$practice_id = $this->session->userdata('practice_id');
					$email_query = $this->db->query("SELECT * FROM practiceinfo WHERE practice_id=$practice_id");
					$email_row = $email_query->row_array();
					$config['smtp_user']=$email_row['smtp_user'];
					$config['smtp_pass']=$email_row['smtp_pass'];
					$email_message = "You have a new message in the patient portal. Please login at " . $email_row['patient_portal'] . " to view your message.";
					$this->email->initialize($config);
					$this->email->from($email_row['email'], $email_row['practice_name']);
					$this->email->to($user_row['email']);
					$this->email->subject('New Message in your Patient Portal');
					$this->email->message($email_message);
					$this->email->send();
				}
			}
		}
		$data1a = array(
			'pid' => $this->input->post('pid'),
			'patient_name' => $this->input->post('patient_name'),
			'message_to' => $this->input->post('message_to'),
			'cc' => $this->input->post('cc'),
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
		$data = array(
			'pid' => $this->input->post('pid'),
			'patient_name' => $this->input->post('patient_name'),
			'message_to' => $this->input->post('message_to'),
			'cc' => $this->input->post('cc'),
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
	
	function export_message()
	{
		$pid = $this->input->post('pid');
		$date = now();
		$date_active = date('Y-m-d H:i:s', $date);
		$from = $this->session->userdata('displayname') . ' (' . $this->session->userdata('user_id') . ')';
		$message = 'Internal messaging with patient on: ' . $this->input->post('t_messages_date') . "\n\r" . $this->input->post('t_messages_message');
		$message = str_replace("<br>", "", $message);
		$data = array(
			't_messages_subject' => 'Internal messaging with patient: ' . $this->input->post('t_messages_subject'),
			't_messages_message' => $message,
			't_messages_dos' => $date_active,
			't_messages_provider' => $this->session->userdata('displayname'),
			't_messages_signed' => 'No',
			't_messages_from' => $from,
			'pid' => $pid,
			'practice_id' => $this->session->userdata('practice_id')
		);
		$this->chart_model->addTMessage($data);
		echo "Message exported to the chart as a patient Message.";
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
			$response['rows'][$i]['cell']=array($row['message_id'],$row['date'],$row['message_to'],$row['cc'],$row['subject'],$row['pid'],$bodytext);
			$i++;
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function scans()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord'); 
		$query = $this->db->query("SELECT * FROM scans WHERE practice_id=$practice_id");
		$count = $query->num_rows(); 
		if( $count >0 ) {
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM scans WHERE practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	// --------------------------------------------------------------------
	
	function get_scans()
	{
		$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
		$result = $query->row_array();
		$dir = $result['documents_dir'] . 'scans/';
		$files = scandir($dir);
		$count = count($files);
		for ($i = 2; $i < $count; $i++) {
			$line = $files[$i];
			$filePath = '/var/www/nosh/scans/' . $line;
			$filePath1 = $dir . $line;
			$date = fileatime($filePath1);
			$fileDateTime = date('Y-m-d H:i:s', $date);
			$pdftext = file_get_contents($filePath1);
			$filePages = preg_match_all("/\/Page\W/", $pdftext, $dummy);
			$data = array(
				'fileName' => $line,
				'filePath' => $filePath,
				'fileDateTime' => $fileDateTime,
				'filePages' => $filePages
			);
			$this->messaging_model->addscan($data);
			rename($filePath1, $filePath);
		}
	}
	
	function view_scan($id)
	{
		$this->db->where('scans_id', $id);
		$result = $this->db->get('scans')->row_array();
		$file_path = $result['filePath'];
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
	
	function view_scan1($id)
	{
		$this->db->where('scans_id', $id);
		$result = $this->db->get('scans')->row_array();
		$file_path = $result['filePath'];
		$name = now() . '_scan.pdf';
		$data['filepath'] = '/var/www/nosh/' . $name;
		copy($file_path, $data['filepath']);
		$data['html'] = '<iframe src="' . base_url() . $name . '" width="770" height="425" style="border: none;"></iframe>';
		echo json_encode($data);
	}
	
	function scan_import()
	{
		$scans_id = $this->input->post('scans_id');
		$pid = $this->input->post('pid');
		$this->db->where('pid', $pid);
		$count = $this->db->get('demographics')->num_rows();
		if ($count > 0) {
			$this->db->where('scans_id', $scans_id);
			$row = $this->db->get('scans')->row_array();
			$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
			$result = $query->row_array();
			$directory = $result['documents_dir'] . $pid;
			if ($this->input->post('scan_import_pages') == '') {
				$filePath = $directory . "/" . $row['fileName'] . '_' . now() . '.pdf';
				if (!copy($row['filePath'], $filePath)) {
					echo "Scan import failed!";
					exit (0);
				}
			} else {
				$page_array = explode(",", $this->input->post('scan_import_pages'));
				$page = " ";
				foreach ($page_array as $page_item) {
					$page .= "A" . $page_item . " ";
				}
				$filename = str_replace(".pdf", "", $row['fileName']);
				$filePath = $directory . "/" . $filename . "_" . now() . "_excerpt.pdf";
				$commandpdf2 = 'pdftk A="' . $row['filePath'] . '" cat' . $page . 'output "' . $filePath . '"';
				$commandpdf3 = escapeshellcmd($commandpdf2);
				exec($commandpdf3);
			}
			$date1 = $this->input->post('documents_date');
			$date2 = strtotime($date1);
			$datestring = "%Y-%m-%d";
			$date = mdate($datestring, $date2);		
			$data = array(
				'documents_url' => $filePath,
				'pid' => $pid,
				'documents_type' => $this->input->post('documents_type'),
				'documents_desc' => $this->input->post('documents_desc'),
				'documents_from' => $this->input->post('documents_from'),
				'documents_viewed' => $this->input->post('documents_viewed'),
				'documents_date' => $date
			);	
			$this->chart_model->addDocuments($data);
			$this->audit_model->add();
			echo 'Document added!';
		} else {
			echo 'No patient for document to be imported!';
			exit (0);
		}
	}
	
	function scan_import1()
	{
		$scans_id = $this->input->post('scans_id');
		$pid = $this->input->post('pid');
		$this->db->where('pid', $pid);
		$count = $this->db->get('demographics')->num_rows();
		if ($count > 0) {
			$this->db->where('scans_id', $scans_id);
			$row = $this->db->get('scans')->row_array();
			$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
			$result = $query->row_array();
			$filePath = $result['documents_dir'] . $pid . '/' . $row['fileName'] . '_' . now() . '.pdf';	
			if (!copy($row['filePath'], $filePath)) {
				echo "Scan import failed!";
				exit (0);
			}
			$name = str_replace(' ', '_', $row['fileName']);
			$extension = array('.pdf','.jpg','.gif');
			$name = str_replace($extension, '', $name);
			$type = 'Other Forms';
			$date_pos = strrpos($name, '_');
			$date_item = substr($name, $date_pos);
			$date_item = str_replace('_', '', $date_item);
			$date_items = explode('.', $date_item);
			$date = '20' . $date_items[2] . '-' . $date_items[0] . '-' . $date_items[1];
			$name1 = str_replace($date_item, '', $name);
			if (strpos($name, 'from_')) {
				$name2 = str_replace('_from_', '#', $name1);
				$name2_items = explode('#', $name2);
				$from = str_replace('_', ' ', $name2_items[1]);
				$desc = str_replace('_', ' ', $name2_items[0]);
			} else {
				$from = 'About Family Health';
				$desc = str_replace('_', ' ', $name1);
			}
			if (stristr($name, 'lab')) {
				$type = 'Laboratory';
			}
			if (stristr($name, 'x-ray') || stristr($name, 'mri') || strpos('ct', $name) === 0 || stristr($name, 'mammogram') || strpos('us', $name) === 0) {
				$type = 'Imaging';
			}
			if (stristr($name, 'progress')) {
				$type = 'Referrals';
			}
			if (stristr($name, 'ekg')) {
				$type = 'Cardiopulmonary';
			}
			if ($from == 'PT ') {
				$from = 'Physical Therapy';
			}
			if ($from == 'Quest ') {
				$from = 'Quest Diagnostics';
			}
			if ($from == 'Epic ') {
				$from = 'Epic Imaging';
			}
			$data = array(
				'documents_url' => $filePath,
				'pid' => $pid,
				'documents_type' => $type,
				'documents_desc' => $desc,
				'documents_from' => $from,
				'documents_viewed' => $this->input->post('documents_viewed'),
				'documents_date' => $date
			);	
			$this->chart_model->addDocuments($data);
			$this->audit_model->add();
			echo 'Document added!';
		} else {
			echo 'No patient for document to be imported!';
			exit (0);
		}
	}
	
	function deletescan()
	{
		$scans_id = $this->input->post('scans_id');
		$this->db->where('scans_id', $scans_id);
		$row = $this->db->get('scans')->row_array();
		unlink($row['filePath']);
		$this->db->where('scans_id', $scans_id);
		$this->db->delete('scans');
		echo 'Document deleted!';
		exit (0);
	}	
	
	function receive_fax()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord'); 
		$query = $this->db->query("SELECT * FROM received WHERE practice_id=$practice_id");
		$count = $query->num_rows(); 
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM received WHERE practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function deletefax()
	{
		$trashDir = "/var/www/nosh/received/trash";
		$filename = explode('.', $this->input->post('fileName'));
		$fileName = $this->input->post('fileName');
		$thumbnail_del = "/var/www/nosh/received/thumbnails/" . $filename[0] . ".png";
		$this->db->query("DELETE FROM `received` WHERE `fileName` = '$fileName'");
		$command2 = "mv \"" . $this->input->post('filePath') . "\" " . $trashDir;
		$command2a = escapeshellcmd($command2);
		exec($command2a);
		$command3 = "mv \"" . $thumbnail_del . "\" " . $trashDir;
		$command3a = escapeshellcmd($command3);
		exec($command3a);
		$arr['result'] = 'Fax Deleted';
		echo $arr['result'];
	}	
	
	function view_thumbnail($file)
	{
		$file_path = "/var/www/nosh/received/thumbnails/" . $file . ".png";
		if ($fp = fopen ($file_path, "r")) {
			$file_info = pathinfo($file_path);
			$file_name = $file_info["basename"];
			$file_size = filesize($file_path);
			$file_extension = strtolower($file_info["extension"]);
			if($file_extension!='png') {
				die('LOGGED! bad extension');
			}
			ob_start();
			header('Content-type: image/png');
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
	
	function view_fax($id)
	{
		$this->db->where('received_id', $id);
		$result = $this->db->get('received')->row_array();
		$file_path = $result['filePath'];
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
	
	function view_fax1($id)
	{
		$this->db->where('received_id', $id);
		$result = $this->db->get('received')->row_array();
		$file_path = $result['filePath'];
		$name = now() . '_fax.pdf';
		$data['filepath'] = '/var/www/nosh/' . $name;
		copy($file_path, $data['filepath']);
		$data['html'] = '<iframe src="' . base_url() . $name . '" width="770" height="425" style="border: none;"></iframe>';
		echo json_encode($data);
	}
	
	function view_page($id)
	{
		$this->db->where('pages_id', $id);
		$result = $this->db->get('pages')->row_array();
		$file_path = $result['file'];
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
	
	function annotate_fax($id)
	{
		ini_set('memory_limit','196M');
		$this->load->library('mpdf');
		$this->db->where('received_id', $id);
		$result = $this->db->get('received')->row_array();
		$file_path = $result['filePath'];
		$new_file_path = '';
		$this->mpdf=new mpdf();
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->SetImportUse();
		$pagecount = $this->mpdf->SetSourceFile($file_path);
		$tplId = $this->mpdf->ImportPage($pagecount);
		$this->mpdf->UseTemplate($tplId);
		$this->mpdf->WriteHTML($cover_html);
		$this->mpdf->debug = true;
		$this->mpdf->showImageErrors = true;
		$this->mpdf->Output($new_file_path,'F');
	}
	
	function drafts_list()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM sendfax WHERE faxdraft='yes' OR faxdraft IS NULL AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM sendfax WHERE faxdraft='yes' OR faxdraft IS NULL AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function new_fax()
	{
		$fax_data = array(
			'user' => $this->session->userdata('displayname'),
			'practice_id' => $this->session->userdata('practice_id')
		);
		$job_id = $this->fax_model->addFax($fax_data);
		$this->session->set_userdata('fax_job_id', $job_id);
		$directory = '/var/www/nosh/sentfax/' . $job_id;
		$command = "mkdir " . $directory;
		$command1 = escapeshellcmd($command);
		exec($command1);
		chmod($directory, 0777);
		echo "New fax job " . $job_id . " created!";
	}
	
	function set_id($page)
	{
		$this->session->unset_userdata('fax_job_id');
		$this->session->unset_userdata('fax_page');
		$job_id = $this->input->post('job_id', TRUE);
		$this->session->set_userdata('fax_page', $page);
		$this->session->set_userdata('fax_job_id', $job_id);
		$arr['result'] = $job_id;
		echo json_encode($arr);
		exit (0);
	}
	
	function sent_list()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM sendfax WHERE senddate IS NOT NULL AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM sendfax WHERE senddate IS NOT NULL AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function show_status()
	{
		$job_id = $this->input->post('job_id');
		$logfile = '/var/www/webfaxhp/sentfax/' . $job_id . '/log';
		if (file_exists($logfile)) {
			$statuslog = file_get_contents($logfile);
			$statuslog1 = strstr($statuslog, 'Rendering');
			echo htmlentities($statuslog1);
		} else {
			echo "Error sending fax or no fax log created!";
		}
		exit( 0 );
	}
	
	function cancelfax()
	{
		$job_id = $this->session->userdata('fax_job_id');
		$page = $this->session->userdata('fax_page');
		$this->db->where('job_id', $job_id);
		$this->db->where('success', '1');
		$query = $this->db->get('sendfax');
		$count = $query->num_rows();
		if ($count > 0) { 
			$this->session->unset_userdata('fax_job_id');
			$this->session->unset_userdata('fax_page');
			echo 'Canceled operation for fax job ' . $job_id;
		} else { 
			if ($job_id != '') {
				$directory = '/var/www/nosh/sentfax/' . $job_id;
				$command = "rm -R " . $directory;
				$command1 = escapeshellcmd($command);
				exec($command1);
				$this->fax_model->deleteFax($job_id);
				$this->session->unset_userdata('fax_job_id');
				$this->session->unset_userdata('fax_page');
				echo 'Fax job ' .  $job_id . ' deleted and canceled!';
			}
		}
	}
	
	function add_fax_recipient()
	{
		$job_id = $this->session->userdata('fax_job_id');
		$meta = array("(", ")", "-", " ");
		$fax = str_replace($meta, "", $this->input->post('fax'));
		$send_list_data = array(
			'faxrecipient' => $this->input->post('displayname'),
			'faxnumber' => $fax,
			'job_id' => $job_id
		);
		$this->fax_model->addSendList($send_list_data);
		echo 'Contact added to recipient list';
	}
	
	function send_list()
	{
		$job_id = $this->session->userdata('fax_job_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM recipients WHERE job_id=$job_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM recipients WHERE job_id=$job_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}

	function edit_send_list()
	{
		$job_id = $this->session->userdata('fax_job_id');
		if ($job_id == '') {
			redirect('start');
		}
		$meta = array("(", ")", "-", " ");
		$fax = str_replace($meta, "", $this->input->post('faxnumber'));
		$send_list_data = array(
			'faxrecipient' => $this->input->post('faxrecipient'),
			'faxnumber' => $fax,
			'job_id' => $job_id
		);
		$action = $this->input->post('oper');
		if ($action == 'edit') {
			$this->fax_model->updateSendList($this->input->post('id'), $send_list_data);
		}
		if ($action == 'add') {
			$this->fax_model->addSendList($send_list_data);
		}
		if ($action == 'del') {
			$this->fax_model->deleteSendList($this->input->post('id'));
		}
	}
	
	function pages_list()
	{
		$job_id = $this->session->userdata('fax_job_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM pages WHERE job_id=$job_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM pages WHERE job_id=$job_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function pages_upload()
	{
		$job_id = $this->session->userdata('fax_job_id');
		if ($job_id == '') {
			redirect('start');
		}
		$directory = '/var/www/nosh/sentfax/' . $job_id . '/';
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'pdf|gif|jpg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		$field_name = "fileToUpload";
		$this->upload->do_upload($field_name);
		$pages_data1 = $this->upload->data();
		while(!file_exists($pages_data1['full_path'])) {
			sleep(2);
		}
		$pdftext = file_get_contents($pages_data1['full_path']);
		$pagecount = preg_match_all("/\/Page\W/", $pdftext, $dummy);
		$pages_data2 = array(
			'file' => $pages_data1['full_path'],
			'file_original' => $pages_data1['orig_name'],
			'file_size' => $pages_data1['file_size'],
			'pagecount' => $pagecount,
			'job_id' => $job_id
		);		
		$this->fax_model->addPages($pages_data2);
		echo 'Document added!';
	}
	
	function deletepage()
	{
		$run_delete = $this->fax_model->deletePage($this->input->post('pages_id', TRUE));
		$command = "rm " . $this->input->post('file', TRUE);
		$command1 = escapeshellcmd($command);
		$run_cmd = exec($command1);
		echo 'Document removed!';
	}
	
	function sendfinal()
	{
		$job_id = $this->session->userdata('fax_job_id');
		$query = $this->fax_model->getFax($job_id);
		if ($query->num_rows() > 0 ) {
			$data = $query->row_array();
			$data['task'] = "Draft";
		} else {
			$data['task'] = 'New';
		}
		$data['message'] = 'Fax job ' . $job_id . ' loaded!';
		echo json_encode($data);
	}
	
	function page_coverpage($job_id, $totalpages, $faxrecipients, $date)
	{
		$faxInfo = $this->fax_model->getFax($job_id);
		$faxInfo2 = $faxInfo->row_array();
		$data = array(
			'user' => $this->session->userdata('fullname'),
			'faxrecipients' => $faxrecipients,
			'faxsubject' => $faxInfo2['faxsubject'],
			'faxmessage' => $faxInfo2['faxmessage'],
			'faxpages' => $totalpages,
			'faxdate' => $date
		);
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
		return $this->load->view('auth/pages/coverpage',$data, TRUE);
	}
	
	function form_submit2()
	{
		ini_set('memory_limit','196M');
		$this->load->library('mpdf');
		$job_id = $this->session->userdata('fax_job_id');
		$fax_data = array(
			'faxdraft' => $this->input->post('faxdraft'),
			'faxschedule' => $this->input->post('faxschedule'),
			'faxsubject' => $this->input->post('faxsubject'),
			'faxcoverpage' => $this->input->post('faxcoverpage'),
			'faxmessage' => $this->input->post('faxmessage')
		);
		$fax_data['senddate'] = date('Y-m-d H:i:s');
		$fax_data['success'] = '1';
		$fax_data['attempts'] = '0';
		$directory1 = '/var/www/nosh/sentfax/' . $job_id;
		if ($this->input->post('faxdraft') == 'yes') {
			$this->fax_model->updateFax($job_id, $fax_data);
			$faxrecipients1 = '';
			$faxnumbers1 = '';
			$recipientlist1 = $this->fax_model->getRecipientList($job_id);	
			foreach ($recipientlist1->result_array() as $row2) {
				$faxrecipients1 .= $row2['faxrecipient'] . ', Fax: ' . $row2['faxnumber'] . "<br>";
				if ($faxnumbers1 != '') {
					$faxnumbers1 .= ',' . $row2['faxnumber'];
				} else {
					$faxnumbers1 .= $row2['faxnumber'];
				}
			}
			$datestring1 = "%M %d, %Y, %h:%i";
			$date1 = mdate($datestring1);
			$faxpages1 = '';
			$totalpages1 = 0;
			$pagesInfo1 = $this->fax_model->getPages($job_id);
			foreach ($pagesInfo1->result_array() as $row3) {
				$faxpages1 .= ' ' . $row3['file'];
				$totalpages1 = $totalpages1 + $row3['pagecount'];
			}
			if ($this->input->post('faxcoverpage') == 'yes') {
				$html = $this->page_coverpage($job_id, $totalpages1, $faxrecipients1, $date1);
				$filename = $directory1 . '/coverpage.pdf';
				$this->mpdf->useOnlyCoreFonts = true;
				$this->mpdf->WriteHTML($html);
				$this->mpdf->SetTitle('Coverpage Generated by NOSH EMR');
				$this->mpdf->debug = true;
				$this->mpdf->showImageErrors = true;
				$this->mpdf->Output($filename,'F');
			}
			$arr = 'Fax Job ' . $job_id . ' Updated';
			echo $arr;
		} else {
			$faxInfo = $this->fax_model->getFax($job_id);
			$faxInfo2 = $faxInfo->row_array();
			$faxrecipients = '';
			$faxnumbers = '';
			$recipientlist = $this->fax_model->getRecipientList($job_id);	
			foreach ($recipientlist->result_array() as $row3) {
				$faxrecipients .= $row3['faxrecipient'] . ', Fax: ' . $row3['faxnumber'] . "\n";
				if ($faxnumbers != '') {
					$faxnumbers .= ',' . $row3['faxnumber'];
				} else {
					$faxnumbers .= $row3['faxnumber'];
				}
			}
			$this->db->where('practice_id', $this->session->userdata('practice_id'));
			$practice_row = $this->db->get('practiceinfo')->row_array();
			$faxnumber_array = explode(",", $faxnumbers);
			$faxnumber_to = array();
			foreach ($faxnumber_array as $faxnumber_row) {
				$faxnumber_to[] = $faxnumber_row . '@' . $this->fax_model->fax_type();
			}
			$config['protocol']='smtp';
			$config['smtp_host']='ssl://smtp.googlemail.com';
			$config['smtp_port']='465';
			$config['smtp_timeout']='30';
			$config['charset']='utf-8';
			$config['newline']="\r\n";
			$config['smtp_user']=$practice_row['smtp_user'];
			$config['smtp_pass']=$practice_row['smtp_pass'];
			$this->email->initialize($config);
			$this->email->from($practice_row['email'], $practice_row['practice_name']);
			$this->email->to($faxnumber_to);
			$this->email->subject($fax_data['faxsubject']);
			$datestring = "%M %d, %Y, %h:%i";
			$date = mdate($datestring);	
			$faxpages = '';
			$totalpages = 0;
			$senddate = date('Y-m-d H:i:s');
			$pagesInfo = $this->fax_model->getPages($job_id);
			foreach ($pagesInfo->result_array() as $row4) {
				$faxpages .= ' ' . $row4['file'];
				$totalpages = $totalpages + $row4['pagecount'];
			}
			if ($faxInfo2['faxcoverpage'] == 'yes') {
				$cover_html = $this->page_coverpage($job_id, $totalpages, $faxrecipients, $date);
				$cover_filename = $directory1 . '/coverpage.pdf';
				$this->mpdf=new mpdf();
				$this->mpdf->useOnlyCoreFonts = true;
				$this->mpdf->WriteHTML($cover_html);
				$this->mpdf->SetTitle('Coverpage Generated by NOSH EMR');
				$this->mpdf->debug = true;
				$this->mpdf->showImageErrors = true;
				$this->mpdf->Output($cover_filename,'F');
				$this->email->attach($cover_filename);
			}
			foreach ($pagesInfo->result_array() as $row5) {
				$this->email->attach($row5['file']);
			}
			$fax_data['sentdate'] = date('Y-m-d');
			$fax_data['ready_to_send'] = '1';
			$fax_data['senddate'] = $senddate;
			$fax_data['faxdraft'] = '0';
			$fax_data['attempts'] = '0';
			$fax_data['success'] = '1';
			$this->fax_model->updateFax($job_id, $fax_data);
			$this->email->send();
			if ($faxInfo2['faxschedule'] == 'yes') {
				$arr['result'] = 'Fax Job ' . $job_id . ' Scheduled to be sent on ' . $datepicker . ' at ' . $time;
				echo $arr['result'];
			} else {	
				$arr['result'] = 'Fax Job ' . $job_id . ' Sent';
				echo $arr['result'];
			}
		}
	}
	
	function fax_import()
	{
		$pid = $this->input->post('pid');
		$this->db->where('pid', $pid);
		if ($this->db->get('demographics')->num_rows() > 0) {
			$query = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
			$result = $query->row_array();
			$directory = $result['documents_dir'] . $pid;
			$date1 = $this->input->post('documents_date');
			$date2 = strtotime($date1);
			$datestring = "%Y-%m-%d";
			$date = mdate($datestring, $date2);
			$this->db->where('received_id', $this->input->post('received_id'));
			$result = $this->db->get('received')->row_array();
			if ($this->input->post('fax_import_pages') == '') {
				$file1 = $result['filePath'];
				$file2 = $directory . "/" . $result['fileName'] . '_' . now() . '.pdf';
				if (!copy($file1, $file2)) {
					echo "Fax import failed!";
					exit (0);
				}
			} else {
				$page_array = explode(",", $this->input->post('fax_import_pages'));
				$page = " ";
				foreach ($page_array as $page_item) {
					$page .= "A" . $page_item . " ";
				}
				$filename = str_replace(".pdf", "", $result['fileName']);
				$file2 = $directory . "/" . $filename . "_" . now() . "_excerpt.pdf";
				$commandpdf2 = 'pdftk A="' . $result['filePath'] . '" cat' . $page . 'output "' . $file2 . '"';
				$commandpdf3 = escapeshellcmd($commandpdf2);
				exec($commandpdf3);
			}
			$pages_data2 = array(
				'documents_url' => $file2,
				'pid' => $pid,
				'documents_type' => $this->input->post('documents_type'),
				'documents_desc' => $this->input->post('documents_desc'),
				'documents_from' => $this->input->post('documents_from'),
				'documents_viewed' => $this->input->post('documents_viewed'),
				'documents_date' => $date
			);			
			$documents_id = $this->chart_model->addDocuments($pages_data2);	
			$this->audit_model->add();
			echo 'Fax imported!';
			exit (0);
		} else {
			echo 'No patient for fax to be imported!';
			exit (0);
		}
	}	
	
	function all_contacts($mask='')
	{
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		if($mask == ''){
			$query = $this->db->query("SELECT * FROM addressbook");
		} else {
			$mask = "'%".$mask."%'";
			$query = $this->db->query("SELECT * FROM addressbook WHERE displayname LIKE $mask OR specialty LIKE $mask");
		}
		$count = $query->num_rows(); 
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		if($mask == ''){
			$query1 = $this->db->query("SELECT * FROM addressbook ORDER BY $sidx $sord LIMIT $start , $limit");
		} else {
			$query1 = $this->db->query("SELECT * FROM addressbook WHERE displayname LIKE $mask OR specialty LIKE $mask ORDER BY $sidx $sord LIMIT $start , $limit");
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;	
		echo json_encode($response);
		exit( 0 );
	}
	
	function edit_contact()
	{
		if($this->input->post('firstname') == '' OR $this->input->post('lastname') == '') {
			$displayname = $this->input->post('facility');
		} else {
			if($this->input->post('suffix') == '') {
				$displayname = $this->input->post('firstname') . ' ' . $this->input->post('lastname');
			} else {
				$displayname = $this->input->post('firstname') . ' ' . $this->input->post('lastname') . ', ' . $this->input->post('suffix');
			}
		}	
		$data = array(
			'displayname' => $displayname,
			'lastname' => $this->input->post('lastname'),
			'firstname' => $this->input->post('firstname'),
			'prefix' => $this->input->post('prefix'),
			'suffix' => $this->input->post('suffix'),
			'facility' => $this->input->post('facility'),
			'street_address1' => $this->input->post('street_address1'),
			'street_address2' => $this->input->post('street_address2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zip' => $this->input->post('zip'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax'),
			'email' => $this->input->post('email'),
			'comments' => $this->input->post('comments'),
			'specialty' => $this->input->post('specialty'),
			'npi' => $this->input->post('npi')
		);	
		if($this->input->post('address_id') == '') {
			$add = $this->contact_model->addContact($data);
			$this->audit_model->add();
			if ($add) {
				echo "Contact added!";
				exit( 0 );
			} else {
				echo "Error adding contact";
				exit( 0 );
			}
		} else {
			$update = $this->contact_model->updateContact($this->input->post('address_id'), $data);
			$this->audit_model->update();
			echo "Contact updated!";
			exit( 0 );
		}
	}
	
	function delete_contact()
	{
		$this->contact_model->deleteContact($this->input->post('address_id'));
		echo "Contact deleted!";
		exit (0);
	}
	
	function import_contact()
	{
		$directory = '/var/www/nosh/import/';
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'csv';
		$this->load->library('upload', $config);
		$field_name = "fileToUpload1";
		$this->upload->do_upload($field_name);
		$pages_data1 = $this->upload->data();
		while(!file_exists($pages_data1['full_path'])) {
			sleep(2);
		}
		$this->load->library('csvreader');  
		$csv = $this->csvreader->parse_file($pages_data1['full_path']);
		$i = 1;
		foreach ($csv as $field) {
			if($field['firstname'] == '' OR $field['lastname'] == '') {
				$field['displayname'] = $field['facility'];
			} else {
				if($this->input->post('suffix') == '') {
					$field['displayname'] = $field['firstname'] . ' ' . $field['lastname'];
				} else {
					$field['displayname'] = $field['firstname'] . ' ' . $field['lastname'] . ', ' . $field['suffix'];
				}
			}	
			$add = $this->contact_model->addContact($field);
			$this->audit_model->add();
			$i++;
		}
		$message =  "Imported " . $i . " records!";
		echo $message;
	}
	
	function close_fax()
	{
		unlink($this->input->post('fax_filepath'));
		echo 'OK';
	}
	
	function close_scan()
	{
		unlink($this->input->post('scan_filepath'));
		echo 'OK';
	}
} 
/* End of file: messaging.php */
/* Location: application/controllers/provider/messaging.php */
