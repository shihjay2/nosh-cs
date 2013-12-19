<?php

class Billing extends Application
{

	function Billing()
	{
		parent::Application();
		$this->load->database();
		$this->load->helper('download');
		$this->load->library('session');
		$this->auth->restrict('provider');
		$this->load->model('encounters_model');
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
		$this->auth->view('provider/billing');
	}
	
	function submit_list()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters JOIN demographics ON encounters.pid=demographics.pid WHERE encounters.bill_submitted!='Done' AND encounters.addendum='n' AND encounters.practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters JOIN demographics ON encounters.pid=demographics.pid WHERE encounters.bill_submitted!='Done' AND encounters.addendum='n' AND encounters.practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function submit_batch_queue()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters JOIN demographics ON encounters.pid=demographics.pid WHERE encounters.bill_submitted='Pend' AND encounters.addendum='n' AND encounters.practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters JOIN demographics ON encounters.pid=demographics.pid WHERE encounters.bill_submitted='Pend' AND encounters.addendum='n' AND encounters.practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function add_queue()
	{
		$eid = $this->input->post('eid');
		$data = array(
			'bill_submitted' => 'Pend'
		);
		$this->encounters_model->updateEncounter($eid, $data);
		$this->audit_model->update();
		echo "Billed encounter added to the print image queue!";
	}
	
	function add_queue1()
	{
		$eid = $this->input->post('eid');
		$data = array(
			'bill_submitted' => 'HCFA'
		);
		$this->encounters_model->updateEncounter($eid, $data);
		$this->audit_model->update();
		echo "Billed encounter added to the print HCFA-1500 queue!";
	}
	
	function printimage($eid)
	{
		$this->db->where('eid', $eid);
		$query = $this->db->get('billing');
		$new_template = '';
		foreach ($query->result_array() as $result) {
			$template = file_get_contents('/var/www/nosh/billing.txt');
			$search = array(
				"^Bx11c**********************^",            
				"^Pay^",                                    
				"^InsuranceAddress***********^",           
				"^InsuranceAddress2**********^",           
				"^Bx1****************************************^",
				"^Bx1a***********************^",
				"^Bx2***********************^",
				"^Bx3a****^",
				"^Bx3b^",
				"^Bx4************************^",
				"^Bx5a**********************^",
				"^Bx6**********^",
				"^Bx7a***********************^",
				"^Bx5b******************^",
				"^5^",
				"^Bx8a*******^",
				"^Bx7b*****************^",
				"^7*^",  
				"^Bx5d******^",
				"^Bx5e********^",
				"^Bx8b*******^",
				"^Bx7d******^",
				"^Bx7e********^", 
				"^Bx9***********************^",
				"^Bx10*************^",
				"^Bx11***********************^",
				"^Bx9a**********************^",
				"^Bx10a^",
				"^Bx11a***^",
				"^Bx11aa^",    
				"^Bx9b****^",
				"^Bx9bb^",
				"^Bx10b^",
				"^b^",
				"^Bx11b**********************^",
				"^Bx9c**********************^",
				"^Bx10c^",
				"^Bx9d**********************^",
				"^Bx10d************^",
				"^B11d^",
				"^Bx17********************^",
				"^Bx17a**********^",
				"^Bx21a*^",
				"^Bx21b*^",
				"^Bx21c*^",
				"^Bx21d*^",
				"^DOS1F*^",
				"^DOS1T*^",
				"^a1*^",
				"^CT1*^",
				"^d1*******^",
				"^e1^",
				"^f1****^",
				"^g1*^",
				"^j1*******^",
				"^DOS2F*^",
				"^DOS2T*^",
				"^a2*^",
				"^CT2*^",
				"^d2*******^",
				"^e2^",
				"^f2****^",
				"^g2*^",
				"^j2*******^", 
				"^DOS3F*^",
				"^DOS3T*^",
				"^a3*^",
				"^CT3*^",
				"^d3*******^",
				"^e3^",
				"^f3****^",
				"^g3*^",
				"^j3*******^",
				"^DOS4F*^",
				"^DOS4T*^",
				"^a4*^",
				"^CT4*^",
				"^d4*******^",
				"^e4^",
				"^f4****^",
				"^g4*^",
				"^j4*******^",
				"^DOS5F*^",
				"^DOS5T*^",
				"^a5*^",
				"^CT5*^",
				"^d5*******^",
				"^e5^",
				"^f5****^",
				"^g5*^",
				"^j5*******^",
				"^DOS6F*^",
				"^DOS6T*^",
				"^a6*^",
				"^CT6*^",
				"^d6*******^",
				"^e6^",
				"^f6****^",
				"^g6*^",
				"^j6*******^",
				"^Bx25*********^",
				"^Bx26********^",
				"^Bx27^",
				"^Bx28***^",
				"^Bx29**^",
				"^Bx30**^",
				"^Bx33a******^",
				"^Bx32a*******************^",
				"^Bx33b**********************^",
				"^Bx32b*******************^",
				"^Bx33c**********************^",
				"^Bx32c*******************^",
				"^Bx33d**********************^",
				"^Bx31***************^",
				"^Bx32d***^",
				"^Bx33e***^"
			);
			$replace = array(
				$result['bill_Box11C'],
				$result['bill_payor_id'],
				$result['bill_ins_add1'],
				$result['bill_ins_add2'],
				$result['bill_Box1'],
				$result['bill_Box1A'],
				$result['bill_Box2'],
				$result['bill_Box3A'],
				$result['bill_Box3B'],
				$result['bill_Box4'],
				$result['bill_Box5A'],
				$result['bill_Box6'],
				$result['bill_Box7A'],
				$result['bill_Box5B'],
				$result['bill_Box5C'],
				$result['bill_Box8A'],
				$result['bill_Box7B'],
				$result['bill_Box7C'],
				$result['bill_Box5D'],
				$result['bill_Box5E'],
				$result['bill_Box8B'],
				$result['bill_Box7D'],
				$result['bill_Box7E'],
				$result['bill_Box9'],
				$result['bill_Box10'],
				$result['bill_Box11'],
				$result['bill_Box9A'],
				$result['bill_Box10A'],
				$result['bill_Box11A1'],
				$result['bill_Box11A2'],
				$result['bill_Box9B1'],
				$result['bill_Box9B2'],
				$result['bill_Box10B1'],
				$result['bill_Box10B2'],
				$result['bill_Box11B'],
				$result['bill_Box9C'],
				$result['bill_Box10C'],
				$result['bill_Box9D'],
				"                   ",
				$result['bill_Box11D'],
				$result['bill_Box17'],
				$result['bill_Box17A'],
				$result['bill_Box21_1'],
				$result['bill_Box21_2'],
				$result['bill_Box21_3'],
				$result['bill_Box21_4'],
				$result['bill_DOS1F'],
				$result['bill_DOS1T'],
				$result['bill_Box24B1'],
				$result['bill_Box24D1'],
				$result['bill_Modifier1'],
				$result['bill_Box24E1'],
				$result['bill_Box24F1'],
				$result['bill_Box24G1'],
				$result['bill_Box24J1'],
				$result['bill_DOS2F'],
				$result['bill_DOS2T'],
				$result['bill_Box24B2'],
				$result['bill_Box24D2'],
				$result['bill_Modifier2'],
				$result['bill_Box24E2'],
				$result['bill_Box24F2'],
				$result['bill_Box24G2'],
				$result['bill_Box24J2'],
				$result['bill_DOS3F'],
				$result['bill_DOS3T'],
				$result['bill_Box24B3'],
				$result['bill_Box24D3'],
				$result['bill_Modifier3'],
				$result['bill_Box24E3'],
				$result['bill_Box24F3'],
				$result['bill_Box24G3'],
				$result['bill_Box24J3'],
				$result['bill_DOS4F'],
				$result['bill_DOS4T'],
				$result['bill_Box24B4'],
				$result['bill_Box24D4'],
				$result['bill_Modifier4'],
				$result['bill_Box24E4'],
				$result['bill_Box24F4'],
				$result['bill_Box24G4'],
				$result['bill_Box24J4'],
				$result['bill_DOS5F'],
				$result['bill_DOS5T'],
				$result['bill_Box24B5'],
				$result['bill_Box24D5'],
				$result['bill_Modifier5'],
				$result['bill_Box24E5'],
				$result['bill_Box24F5'],
				$result['bill_Box24G5'],
				$result['bill_Box24J5'],
				$result['bill_DOS6F'],
				$result['bill_DOS6T'],
				$result['bill_Box24B6'],
				$result['bill_Box24D6'],
				$result['bill_Modifier6'],
				$result['bill_Box24E6'],
				$result['bill_Box24F6'],
				$result['bill_Box24G6'],
				$result['bill_Box24J6'],
				$result['bill_Box25'],
				$result['bill_Box26'],
				$result['bill_Box27'],
				$result['bill_Box28'],
				$result['bill_Box29'],
				$result['bill_Box30'],
				$result['bill_Box33A'],
				$result['bill_Box32A'],
				$result['bill_Box33B'],
				$result['bill_Box32B'],
				$result['bill_Box33C'],
				$result['bill_Box32C'],
				$result['bill_Box33D'],
				$result['bill_Box31'],
				$result['bill_Box32D'],
				$result['bill_Box33E']
			);
			$new_template .= str_replace($search, $replace, $template);
		}
		return $new_template;
	}
	
	function printimage_single($eid)
	{
		$printimage = $this->printimage($eid);
		$data = array(
			'bill_submitted' => 'Done'
		);
		$this->encounters_model->updateEncounter($eid, $data);
		$this->audit_model->update();
		$date = now();
		$date1 = date('Ymd', $date);
		$name = $date1 . '_printimage.txt';
		force_download($name, $printimage);
	}
	
	function check_batch()
	{
		$practice_id = $this->session->userdata('practice_id');
		$query = $this->db->query("SELECT * FROM encounters WHERE bill_submitted='Pend' AND practice_id=$practice_id");
		if ($query->num_rows() > 0) {
			echo 'OK';
		} else {
			echo 'None';
		}
		exit (0);
	}
	
	function check_batch1()
	{
		$practice_id = $this->session->userdata('practice_id');
		$query = $this->db->query("SELECT * FROM encounters WHERE bill_submitted='HCFA' AND practice_id=$practice_id");
		if ($query->num_rows() > 0) {
			echo 'OK';
		} else {
			echo 'None';
		}
		exit (0);
	}
	
	function printimage_batch()
	{
		$practice_id = $this->session->userdata('practice_id');
		$result = $this->db->query("SELECT * FROM encounters WHERE bill_submitted='Pend' AND practice_id=$practice_id")->result_array();
		$entire = '';
		foreach ($result as $row) {
			$entire .= $this->printimage($row['eid']);
			$data = array(
				'bill_submitted' => 'Done'
			);
			$this->encounters_model->updateEncounter($row['eid'], $data);
			$this->audit_model->update();
		}
		$date = now();
		$date1 = date('Ymd', $date);
		$name = $date1 . '_batchprintimage.txt';
		force_download($name, $entire);
	}
	
	function printhcfa_batch()
	{
		$practice_id = $this->session->userdata('practice_id');
		$result = $this->db->query("SELECT * FROM encounters WHERE bill_submitted='HCFA' AND practice_id=$practice_id")->result_array();
		$entire = '';
		foreach ($result as $row) {
			if ($entire === '') {
				$entire .= $this->generate_hcfabatch($row['eid']);
			} else {
				$entire .= ' ' . $this->generate_hcfabatch($row['eid']);
			}
			$data = array(
				'bill_submitted' => 'Done'
			);
			$this->encounters_model->updateEncounter($row['eid'], $data);
			$this->audit_model->update();
		}
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/hcfa1500_output_final_" . $user_id . "_batch.pdf";
		$commandpdf2 = "pdftk " . $entire . " cat output " . $file_path;
		$commandpdf3 = escapeshellcmd($commandpdf2);
		exec($commandpdf3);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		$files = explode(" ", $entire);
		foreach ($files as $row1) {
			unlink($row1);
		}
		$file_path = "/var/www/nosh/hcfa1500_output_final_" . $user_id . "_batch.pdf";
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
	
	function generate_hcfa($eid)
	{
		$this->db->where('eid', $eid);
		$query = $this->db->get('billing');
		$input1 = '';
		if ($query->num_rows() > 0) {
			$i = 0;
			if (file_exists('/var/www/nosh/hcfa1500_output_final.pdf')) {
				unlink('/var/www/nosh/hcfa1500_output_final.pdf');
			}
			foreach ($query->result_array() as $pdfinfo) {
				$input = '/var/www/nosh/hcfa1500.pdf';
				$output = '/var/www/nosh/hcfa1500_output_' . $i . '.pdf';
				if (file_exists($output)) {
					unlink($output);
				}
				$data='<?xml version="1.0" encoding="UTF-8"?>'."\n".
					'<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">'."\n".
					'<fields>'."\n";
				foreach($pdfinfo as $field => $val) {
					$data.='<field name="'.$field.'">'."\n";
					if($field == 'bill_DOS1F' || $field == 'bill_DOS1T' || $field == 'bill_DOS2F' || $field == 'bill_DOS2T' || $field == 'bill_DOS3F' || $field == 'bill_DOS3T' || $field == 'bill_DOS4F' || $field == 'bill_DOS4T' || $field == 'bill_DOS5F' || $field == 'bill_DOS5T' || $field == 'bill_DOS6F' || $field == 'bill_DOS6T') {
						$val_array = str_split($val, 2);
						$val_array1 = array($val_array[0], ' ', $val_array[1], ' ', $val_array[2], $val_array[3]);
						$val = implode($val_array1);
					}
					if($field == 'bill_Box3A' || $field == 'bill_Box9B1' || $field == 'bill_Box11A1'){
						$val_array2 = str_split($val, 3);
						$val_array3 = array($val_array2[0], $val_array2[1], '', $val_array2[2], $val_array2[3]);
						$val = implode($val_array3);
					}
					if($field == 'bill_Box24F1' ||$field == 'bill_Box24F2' || $field == 'bill_Box24F3' || $field == 'bill_Box24F4' || $field == 'bill_Box24F5' || $field == 'bill_Box24F6' || $field == 'bill_Box28' || $field == 'bill_Box29' || $field == 'bill_Box30') {
						$val = rtrim($val);
					}
					if(is_array($val)) {
						foreach($val as $opt)
							$data.='<value>'.$opt.'</value>'."\n";
					} else {
						$data.='<value>'.$val.'</value>'."\n";
					}
					$data.='</field>'."\n";
				}
				$data.='<field name="Date">'."\n<value>".date('m/d/Y')."</value>\n</field>\n";
				$data.='<field name="Date2">'."\n<value>".date('m/d/y')."</value>\n</field>\n";
				$data.='</fields>'."\n".
					'<ids original="'.md5($input).'" modified="'.time().'" />'."\n".
					'<f href="'.$input.'" />'."\n".
					'</xfdf>'."\n";		
				$xfdf_fn= '/var/www/nosh/temp.xfdf';
				$xfp= fopen( $xfdf_fn, 'w' );
				if( $xfp ) {
					fwrite( $xfp, $data );
					fclose( $xfp );
				} else {
					$result_message = 'Error making xfdf!';
					echo $result_message;
					exit (0);
				}
				$commandpdf = "pdftk " . $input . " fill_form " . $xfdf_fn . " output " . $output;
				$commandpdf1 = escapeshellcmd($commandpdf);
				exec($commandpdf1);
				if ($i > 0) {
					$input1 .= ' ' . $output;
				} else {
					$input1 = $output;
				}
				$i++;
			}
			$user_id = $this->session->userdata('user_id');
			$file_path = "/var/www/nosh/hcfa1500_output_final_" . $user_id . ".pdf";
			$commandpdf2 = "pdftk " . $input1 . " cat output " . $file_path;
			$commandpdf3 = escapeshellcmd($commandpdf2);
			$data1 = array(
				'bill_submitted' => 'Done'
			);
			$this->encounters_model->updateEncounter($eid, $data1);
			exec($commandpdf3);
			while(!file_exists($file_path)) {
				sleep(2);
			}
			$files = explode(" ", $input1);
			foreach ($files as $row1) {
				unlink($row1);
			}
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
	}
	
	function print_hcfa()
	{
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/hcfa1500_output_final_" . $user_id . ".pdf";
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
	
	function generate_hcfabatch($eid)
	{
		$this->db->where('eid', $eid);
		$query = $this->db->get('billing');
		$input1 = '';
		if ($query->num_rows() > 0) {
			$i = 0;
			if (file_exists('/var/www/nosh/hcfa1500_output_final.pdf')) {
				unlink('/var/www/nosh/hcfa1500_output_final.pdf');
			}
			foreach ($query->result_array() as $pdfinfo) {
				$input = '/var/www/nosh/hcfa1500.pdf';
				$output = '/var/www/nosh/hcfa1500_output_' . $i . '.pdf';
				if (file_exists($output)) {
					unlink($output);
				}
				$data='<?xml version="1.0" encoding="UTF-8"?>'."\n".
					'<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">'."\n".
					'<fields>'."\n";
				foreach($pdfinfo as $field => $val) {
					$data.='<field name="'.$field.'">'."\n";
					if($field == 'bill_DOS1F' || $field == 'bill_DOS1T' || $field == 'bill_DOS2F' || $field == 'bill_DOS2T' || $field == 'bill_DOS3F' || $field == 'bill_DOS3T' || $field == 'bill_DOS4F' || $field == 'bill_DOS4T' || $field == 'bill_DOS5F' || $field == 'bill_DOS5T' || $field == 'bill_DOS6F' || $field == 'bill_DOS6T') {
						$val_array = str_split($val, 2);
						$val_array1 = array($val_array[0], ' ', $val_array[1], ' ', $val_array[2], $val_array[3]);
						$val = implode($val_array1);
					}
					if($field == 'bill_Box3A' || $field == 'bill_Box9B1' || $field == 'bill_Box11A1'){
						$val_array2 = str_split($val, 3);
						$val_array3 = array($val_array2[0], $val_array2[1], '', $val_array2[2], $val_array2[3]);
						$val = implode($val_array3);
					}
					if($field == 'bill_Box24F1' ||$field == 'bill_Box24F2' || $field == 'bill_Box24F3' || $field == 'bill_Box24F4' || $field == 'bill_Box24F5' || $field == 'bill_Box24F6' || $field == 'bill_Box28' || $field == 'bill_Box29' || $field == 'bill_Box30') {
						$val = rtrim($val);
					}
					if(is_array($val)) {
						foreach($val as $opt)
							$data.='<value>'.$opt.'</value>'."\n";
					} else {
						$data.='<value>'.$val.'</value>'."\n";
					}
					$data.='</field>'."\n";
				}
				$data.='<field name="Date">'."\n<value>".date('m/d/Y')."</value>\n</field>\n";
				$data.='<field name="Date2">'."\n<value>".date('m/d/Y')."</value>\n</field>\n";
				$data.='</fields>'."\n".
					'<ids original="'.md5($input).'" modified="'.time().'" />'."\n".
					'<f href="'.$input.'" />'."\n".
					'</xfdf>'."\n";		
				$xfdf_fn= '/var/www/nosh/temp.xfdf';
				$xfp= fopen( $xfdf_fn, 'w' );
				if( $xfp ) {
					fwrite( $xfp, $data );
					fclose( $xfp );
				} else {
					$result_message = 'Error making xfdf!';
					echo $result_message;
					exit (0);
				}
				$commandpdf = "pdftk " . $input . " fill_form " . $xfdf_fn . " output " . $output;
				$commandpdf1 = escapeshellcmd($commandpdf);
				exec($commandpdf1);
				if ($i > 0) {
					$input1 .= ' ' . $output;
				} else {
					$input1 = $output;
				}
				$i++;
			}
			$user_id = $this->session->userdata('user_id');
			$file_path = "/var/www/nosh/hcfa1500_output_final_" . $eid . "_" . $user_id . ".pdf";
			$commandpdf2 = "pdftk " . $input1 . " cat output " . $file_path;
			$commandpdf3 = escapeshellcmd($commandpdf2);
			$data1 = array(
				'bill_submitted' => 'Done'
			);
			$this->encounters_model->updateEncounter($eid, $data1);
			exec($commandpdf3);
			while(!file_exists($file_path)) {
				sleep(2);
			}
			$files = explode(" ", $input1);
			foreach ($files as $row1) {
				unlink($row1);
			}
		}
		return $file_path;
	}
	
	function bills_done()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters JOIN demographics ON encounters.pid=demographics.pid WHERE encounters.bill_submitted='Done' AND encounters.addendum='n' AND encounters.practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters JOIN demographics ON encounters.pid=demographics.pid WHERE encounters.bill_submitted='Done' AND encounters.addendum='n' AND encounters.practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
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
					$charge += $row1['cpt_charge'] * $row1['unit'];
					$payment += $row1['payment'];
				}
				$row['balance'] = $charge - $payment;
				$row['charges'] = $charge;
			} else {
				$row['balance'] = 0;
				$row['charges'] = 0;
			}
			$response['rows'][$i]['id']=$row['eid']; 
			$response['rows'][$i]['cell']=array($row['eid'],$row['encounter_DOS'],$row['lastname'],$row['firstname'],$row['encounter_cc'],$row['charges'],$row['balance']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function bill_resubmit()
	{
		$eid = $this->input->post('eid');
		$this->db->where('eid', $eid);
		$query = $this->db->get('billing');
		$arr = "No bill for this encounter!";
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			if ($row['insurance_id_1'] == '0' || $row['insurance_id_1'] == '') {
				$arr = "No insurance was assigned.  Cannot be resubmitted.";
			} else {
				$data = array(
					'bill_submitted' => 'No'
				);
				$this->encounters_model->updateEncounter($eid, $data);
				$this->audit_model->update();
				$arr = "Billed changed to unsent status!";
			}
		}
		echo $arr;
	}
	
	function outstanding_balance()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM demographics JOIN demographics_relate ON demographics.pid=demographics_relate.pid WHERE demographics_relate.practice_id=$practice_id");
		$count = 0;
		$full_array = array();
		foreach ($query->result_array() as $row) {
			$pid = $row['pid'];
			$this->db->where('pid', $pid);
			$this->db->where('practice_id', $this->session->userdata('practice_id'));
			$notes = $this->db->get('demographics_notes')->row_array();
			$query_a = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND addendum='n'");
			$g = 0;
			if ($query_a->num_rows() > 0) {
				$balance = 0;
				foreach ($query_a->result_array() as $row_a) {
					$this->db->where('eid', $row_a['eid']);
					$query_b = $this->db->get('billing_core');
					if ($query_b->num_rows() > 0) {
						$charge = 0;
						$payment = 0;
						foreach ($query_b->result_array() as $row_b) {
							$charge += $row_b['cpt_charge'] * $row_b['unit'];
							$payment += $row_b['payment'];
						}
						$balance += $charge - $payment;
					} else {
						$balance += 0;
					}
					$g++; 
				}
			} else {
				$balance = 0;
			}
			$query_c = $this->db->query("SELECT * FROM billing_core WHERE pid=$pid AND eid='0' AND payment='0'");
			$h = 0;
			if ($query_c->num_rows() > 0) {
				$balance1 = 0;
				foreach ($query_c->result_array() as $row_c) {
					$this->db->where('other_billing_id', $row_c['other_billing_id']);
					$query_d = $this->db->get('billing_core');
					if ($query_d->num_rows() > 0) {
						$charge1 = $row_c['cpt_charge'] * $row_c['unit'];
						$payment1 = 0;
						foreach ($query_d->result_array() as $row_d) {
							$payment1 += $row_d['payment'];
						}
						$balance1 += $charge1 - $payment1;
					} else {
						$balance1 += 0;
					}
					$h++; 
				}
			} else {
				$balance1 = 0;
			}
			$totalbalance = $balance + $balance1;
			if ($totalbalance >= 0.01 || $notes['billing_notes'] != '') {
				$count++;
				$full_array[] = array(
					'pid' => $row['pid'],
					'lastname' => $row['lastname'],
					'firstname' => $row['firstname'],
					'balance' => $totalbalance,
					'billing_notes' => $notes['billing_notes']
				);
			}
		}
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		if (count($full_array) > 0) {
			foreach ($full_array as $key => $value) {
				$index[$key]  = $value[$sidx];
			}
			if ($sord == 'desc') {
				array_multisort($index, SORT_DESC, $full_array);
			} else {
				array_multisort($index, SORT_ASC, $full_array);
			}
			$records = array_slice($full_array, $start , $limit);
		} else {
			$records = $full_array;
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function set_billing_id($id)
	{
		$pid = $this->session->userdata('billing_pid');
		if ($pid != FALSE) {
			$this->session->unset_userdata('billing_pid');
		}
		$this->session->set_userdata('billing_pid', $id);
		echo "Set!";
		exit (0);
	}
	
	function set_billing_id1($eid)
	{
		$pid = $this->session->userdata('billing_pid');
		if ($pid != FALSE) {
			$this->session->unset_userdata('billing_pid');
		}
		$this->db->where('eid', $eid);
		$encounter = $this->db->get('encounters')->row_array();
		$this->session->set_userdata('billing_pid', $encounter['pid']);
		echo "Set!";
		exit (0);
	}
	
	function billing_encounters()
	{
		$pid = $this->session->userdata('billing_pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND addendum='n'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND addendum='n' ORDER BY $sidx $sord LIMIT $start , $limit");
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
					$charge += $row1['cpt_charge'] * $row1['unit'];
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
		$pid = $this->session->userdata('billing_pid');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM billing_core WHERE pid=$pid AND eid='0' AND payment='0'");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM billing_core WHERE pid=$pid AND eid='0' AND payment='0' ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			$this->db->where('other_billing_id', $row['other_billing_id']);
			$query2 = $this->db->get('billing_core');
			if ($query2->num_rows() > 0) {
				$charge = $row['cpt_charge'] * $row['unit'];
				$payment = 0;
				foreach ($query2->result_array() as $row1) {
					$payment += $row1['payment'];
				}
				$row['balance'] = $charge - $payment;
			} else {
				$row['balance'] = 0;
			}
			$response['rows'][$i]['id']=$row['other_billing_id']; 
			$response['rows'][$i]['cell']=array($row['other_billing_id'],$row['dos_f'],$row['reason'],$row['cpt_charge'] * $row['unit'],$row['balance']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function insurance()
	{
		$pid = $this->session->userdata('billing_pid');
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
	
	function get_billing1($eid)
	{
		$query= $this->encounters_model->getAssessment($eid);
		$data = $query->row_array();
		if ($data['assessment_1'] != '') {
			$data1['1'] = "1 - " . $data['assessment_1'];
		}
		if ($data['assessment_2'] != '') {
			$data1['2'] = "2 - " . $data['assessment_2'];
		}
		if ($data['assessment_3'] != '') {
			$data1['3'] = "3 - " . $data['assessment_3'];
		}
		if ($data['assessment_4'] != '') {
			$data1['4'] = "4 - " . $data['assessment_4'];
		}
		if ($data['assessment_5'] != '') {
			$data1['5'] = "5 - " . $data['assessment_5'];
		}
		if ($data['assessment_6'] != '') {
			$data1['6'] = "6 - " . $data['assessment_6'];
		}
		if ($data['assessment_7'] != '') {
			$data1['7'] = "7 - " . $data['assessment_7'];
		}
		if ($data['assessment_8'] != '') {
			$data1['8'] = "8 - " . $data['assessment_8'];
		}
		echo json_encode($data1);
		exit ( 0 );
	}
	
	function get_prevention($eid)
	{
		$pid = $this->session->userdata('billing_pid');
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row();
		$dob1 = human_to_unix($row->DOB);
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$dos1 = human_to_unix($encounterInfo->encounter_DOS);
		$agediff = $dos1- $dob1;
		if ($agediff < 31556926) {
			$data['prevent_established1'] = '99391';
			$data['prevent_new1'] = '99381';
		}
		if ($agediff >= 31556926 && $agediff < 157784630) {
			$data['prevent_established1'] = '99392';
			$data['prevent_new1'] = '99382';
		}
		if ($agediff >= 157784630 && $agediff < 378683112) {
			$data['prevent_established1'] = '99393';
			$data['prevent_new1'] = '99383';
		}
		if ($agediff >= 378683112 && $agediff < 568024668) {
			$data['prevent_established1'] = '99394';
			$data['prevent_new1'] = '99384';
		}
		if ($agediff >= 568024668 && $agediff < 1262277040) {
			$data['prevent_established1'] = '99395';
			$data['prevent_new1'] = '99385';
		}
		if ($agediff >= 1262277040 && $agediff < 2051200190) {
			$data['prevent_established1'] = '99396';
			$data['prevent_new1'] = '99386';
		}
		if ($agediff >= 2051200190) {
			$data['prevent_established1'] = '99397';
			$data['prevent_new1'] = '99387';
		}
		echo json_encode($data);
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
		$query = $this->db->query("SELECT billing_core.*, cpt.cpt_description FROM billing_core JOIN cpt ON billing_core.cpt=cpt.cpt WHERE eid=$eid");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT billing_core.*, cpt.cpt_description FROM billing_core JOIN cpt ON billing_core.cpt=cpt.cpt WHERE eid=$eid ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function billing_save($eid)
	{
		$pid = $this->session->userdata('billing_pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$id = $this->input->post('billing_core_id');
		$this->db->where('billing_core_id', $id);
		$query = $this->db->get('billing_core');
		$count = $query->num_rows();
		$icd_array = $this->input->post('icd_pointer');
		$billing_group = "1";
		if (in_array("5", $icd_array)) {
			$billing_group = "2";
		}
		if (in_array("6", $icd_array)) {
			$billing_group = "2";
		}
		if (in_array("7", $icd_array)) {
			$billing_group = "2";
		}
		if (in_array("8", $icd_array)) {
			$billing_group = "2";
		}
		$icd_pointer = implode("", $icd_array);
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'cpt' => $this->input->post('cpt'),
			'cpt_charge' => $this->input->post('cpt_charge'),
			'icd_pointer' => $icd_pointer,
			'unit' => $this->input->post('unit'),
			'modifier' => $this->input->post('modifier'),
			'dos_f' => $this->input->post('dos_f'),
			'dos_t' => $this->input->post('dos_t'),
			'payment' => '0',
			'billing_group' => $billing_group,
			'practice_id' => $this->session->userdata('practice_id')
		);
		if ($count > 0) {
			$this->encounters_model->updateBillingCore($id, $data);
			$this->audit_model->update();
			$result = 'Billing Updated';
		} else {
			$this->encounters_model->addBillingCore($data);
			$this->audit_model->add();
			$result = 'Billing Added';
		}
		echo $result;
	}
	
	function string_format($str, $len)
	{
		if (strlen($str) < $len) {
        	$str1 = str_pad($str, $len);
    	} else {
    		$str1 = substr($str, 0, $len);
    	}
    	$str1 = strtoupper($str1);
    	return $str1;
	}
	
	function billing_save_common($insurance_id_1, $insurance_id_2, $eid)
	{
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('billing_pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$this->encounters_model->deleteBilling($eid);
		$this->audit_model->delete();
		$practiceInfo = $this->practiceinfo_model->get($practice_id)->row();
		$encounterInfo = $this->encounters_model->getEncounter($eid)->row();
		$demographics = $this->demographics_model->get($pid);
		$row = $demographics->row();
		if ($insurance_id_1 == '0' || $insurance_id_1 == '') {
			$data0 = array(
				'eid' => $eid,
				'pid' => $pid,
				'insurance_id_1' => $insurance_id_1,
				'insurance_id_2' => $insurance_id_2
			);
			$this->encounters_model->addBilling($data0);
			$this->audit_model->add();
			$data_encounter = array(
				'bill_submitted' => 'Done'
			);
			$this->encounters_model->updateEncounter($eid, $data_encounter);
			$this->audit_model->update();
			return 'Billing Saved!';
		 	exit ( 0 );
		}
		$data_encounter = array(
			'bill_submitted' => 'No'
		);
		$this->encounters_model->updateEncounter($eid, $data_encounter);
		$this->audit_model->update();
		$this->db->where('insurance_id', $insurance_id_1);
		$query1 = $this->db->get('insurance');
		$result1 = $query1->row_array();
		$bill_Box11C = $result1['insurance_plan_name'];
		$bill_Box11C = $this->string_format($bill_Box11C, 29);
		$bill_Box1A = $result1['insurance_id_num'];
		$bill_Box1A = $this->string_format($bill_Box1A, 29);
		$bill_Box4 = $result1['insurance_insu_lastname'] . ', ' . $result1['insurance_insu_firstname'];
		$bill_Box4 = $this->string_format($bill_Box4, 29);
		$this->db->where('address_id', $result1['address_id']);
		$query2 = $this->db->get('addressbook');
		$result2 = $query2->row_array();
		if ($result2['insurance_plan_type'] == 'Medicare') {
			$bill_Box1 = "X                                            ";
			$bill_Box1P = 'Medicare';
		}
		if ($result2['insurance_plan_type'] == 'Medicaid') {
			$bill_Box1 = "       X                                     ";
			$bill_Box1P = 'Medicaid';
		}
		if ($result2['insurance_plan_type'] == 'Tricare') {
			$bill_Box1 = "              X                              ";
			$bill_Box1P = 'Tricare';
		}
		if ($result2['insurance_plan_type'] == 'ChampVA') {
			$bill_Box1 = "                     X                       ";
			$bill_Box1P = 'ChampVA';
		}
		if ($result2['insurance_plan_type'] == 'Group Health Plan') {
			$bill_Box1 = "                            X                ";
			$bill_Box1P = 'Group Health Plan';
		}
		if ($result2['insurance_plan_type'] == 'FECA') {
			$bill_Box1 = "                                   X         ";
			$bill_Box1P = 'FECA';
		}
		if ($result2['insurance_plan_type'] == 'Other') {
			$bill_Box1 = "                                            X";
			$bill_Box1P = 'Other';
		}
		$bill_payor_id = $result2['insurance_plan_payor_id'];
		$bill_payor_id = $this->string_format($bill_payor_id, 5);
		if ($result2['street_address2'] == '') {
			$bill_ins_add1 = $result2['street_address1'];
		} else {
			$bill_ins_add1 = $result2['street_address1'] . ', ' . $result2['street_address2'];
		}
		$bill_ins_add1 = $this->string_format($bill_ins_add1, 29);
		$bill_ins_add2 = $result2['city'] . ', ' . $result2['state'] . ' ' . $result2['zip'];
		$bill_ins_add2 = $this->string_format($bill_ins_add2, 29);
		if ($result2['insurance_plan_assignment'] == 'Yes') {
			$bill_Box27 = "X     ";
			$bill_Box27P = "Yes";
		} else {
			$bill_Box27 = "     X";
			$bill_Box27P = "No";
		}
		if ($result1['insurance_relationship'] == 'Self') {
			$bill_Box6 = "X              ";
			$bill_Box6P = "SelfBox6";
		}
		if ($result1['insurance_relationship'] == 'Spouse') {
			$bill_Box6 = "     X         ";
			$bill_Box6P = "Spouse";
		}
		if ($result1['insurance_relationship'] == 'Child') {
			$bill_Box6 = "         X     ";
			$bill_Box6P = "Child";
		}
		if ($result1['insurance_relationship'] == 'Other') {
			$bill_Box6 = "              X";
			$bill_Box6P = "Other";
		}
		$bill_Box7A = $result1['insurance_insu_address'];
		$bill_Box7A = $this->string_format($bill_Box7A, 29);
		$bill_Box7B = $result1['insurance_insu_city'];
		$bill_Box7B = $this->string_format($bill_Box7B, 23);
		$bill_Box7C = $result1['insurance_insu_state'];
		$bill_Box7C = $this->string_format($bill_Box7C, 4);
		$bill_Box7D = $result1['insurance_insu_zip'];
		$bill_Box7D = $this->string_format($bill_Box7D, 12);
		$bill_Box11 = $result1['insurance_group'];
		$bill_Box11 = $this->string_format($bill_Box11, 29);
		$bill_Box11A1 = human_to_unix($result1['insurance_insu_dob']);
		$bill_Box11A1 = date('m d Y', $bill_Box11A1);
		if ($result1['insurance_insu_gender'] == 'm') {
			$bill_Box11A2 = "X       ";
			$bill_Box11A2P = 'M';
		} else {
			$bill_Box11A2 = "       X";
			$bill_Box11A2P = 'F';
		}
		if ($insurance_id_2 == '' || $insurance_id_2 == '0') {
			$bill_Box9D = '';
			$bill_Box9 = '';
			$bill_Box9A = '';
			$bill_Box9B1 = '          ';
			$bill_Box9B2 = '       ';
			$bill_Box9B2P = '';
			$bill_Box9C = "";
			$bill_Box11D = '     X';
			$bill_Box11DP = 'No';
		} else {
			$this->db->where('insurance_id', $insurance_id_2);
			$query3 = $this->db->get('insurance');
			$result3 = $query3->row_array();
			$bill_Box9D = $result3['insurance_plan_name'];
			$bill_Box9 = $result3['insurance_insu_lastname'] . ', ' . $result3['insurance_insu_firstname'];
			$bill_Box9A = $result3['insurance_group'];
			$bill_Box9B1 = human_to_unix($result3['insurance_insu_dob']);
			$bill_Box9B1 = date('m d Y', $bill_Box9B1);
			if ($result3['insurance_insu_gender'] == 'm') {
				$bill_Box9B2 = "X      ";
				$bill_Box9B2P = 'M';
			} else {
				$bill_Box9B2 = "      X";
				$bill_Box9B2P = 'F';
			}
			$bill_Box11D = 'X     ';
			$bill_Box11DP = 'Yes';
			if ($row->employer != '') {
				$bill_Box9C = $row->employer;
			} else {
				$bill_Box9C = "";
			}
		}
		$bill_Box9D = $this->string_format($bill_Box9D, 28);
		$bill_Box9 = $this->string_format($bill_Box9, 28);
		$bill_Box9A = $this->string_format($bill_Box9A, 28);
		$bill_Box9C = $this->string_format($bill_Box9C, 28);
		$bill_Box2 = $row->lastname . ', ' . $row->firstname;
		$bill_Box2 = $this->string_format($bill_Box2, 28);
		$bill_Box3A = human_to_unix($row->DOB);
		$bill_Box3A = date('m d Y', $bill_Box3A);
		if ($row->sex == 'm') {
			$bill_Box3B = "X     ";
			$bill_Box3BP = 'M';
		} else {
			$bill_Box3B = "     X";
			$bill_Box3BP = 'F';
		}
		if ($row->marital_status == 'Single') {
			$bill_Box8A = "X            ";
			$bill_Box8AP = 'SingleBox8';
		} else {
			if ($row->marital_status == 'Married') {
				$bill_Box8A = "      X      ";
				$bill_Box8AP = 'Married';
			} else {
				$bill_Box8A = "            X";
				$bill_Box8AP = 'Other';
			}
		}
		if ($row->employer != '') {
			$bill_Box8B = "X            ";
			$bill_Box8BP = "EmployedBox8";
			$bill_Box11B = $row->employer;
		} else {
			$bill_Box8B = "             ";
			$bill_Box8BP = "";
			$bill_Box11B = "";
		}
		$bill_Box11B = $this->string_format($bill_Box11B, 29);
		$bill_Box5A = $row->address;
		$bill_Box5A = $this->string_format($bill_Box5A, 28);
		$bill_Box5B = $row->city;
		$bill_Box5B = $this->string_format($bill_Box5B, 24);
		$bill_Box5C = $row->state;
		$bill_Box5C = $this->string_format($bill_Box5C, 3);
		$bill_Box5D = $row->zip;
		$bill_Box5D = $this->string_format($bill_Box5D, 12);
		$bill_Box5E = $row->phone_home;
		$bill_Box5E = $this->string_format($bill_Box5E, 14);
		$bill_Box10 = $encounterInfo->encounter_condition;
		$bill_Box10 = $this->string_format($bill_Box10, 19);
		$work = $encounterInfo->encounter_condition_work;
		if ($work == 'Yes') {
			$bill_Box10A = "X      ";
			$bill_Box10AP = 'Yes';
		} else {
			$bill_Box10A = "      X";
			$bill_Box10AP = 'No';
		}
		$auto = $encounterInfo->encounter_condition_auto;
		if ($auto == 'Yes') {
			$bill_Box10B1 = "X      ";
			$bill_Box10B1P = 'Yes';
			$bill_Box10B2 = $encounterInfo->encounter_condition_auto_state;	
		} else {
			$bill_Box10B1 = "      X";
			$bill_Box10B1P = 'No';
			$bill_Box10B2 = "";
		}
		$bill_Box10B2 = $this->string_format($bill_Box10B2, 3);
		$other = $encounterInfo->encounter_condition_other;
		if ($other == 'Yes') {
			$bill_Box10C = "X      ";
			$bill_Box10CP = "Yes";
		} else {
			$bill_Box10C = "      X";
			$bill_Box10CP = 'No';
		}
		$provider = $encounterInfo->encounter_provider;
		$this->db->select('id');
		$this->db->where('displayname', $provider);
		$user_id = $this->db->get('users')->row()->id;
		$this->db->select('npi');
		$this->db->where('id', $user_id);
		$query4 = $this->db->get('providers');
		$result4 = $query4->row();
		$npi = $result4->npi;
		if ($encounterInfo->referring_provider != 'Primary Care Provider' || $encounterInfo->referring_provider != '') {
			$bill_Box17 = $this->string_format($encounterInfo->referring_provider, 26);
			$bill_Box17A = $this->string_format($encounterInfo->referring_provider_npi, 17);
		} else {
			if ($encounterInfo->referring_provider != 'Primary Care Provider') {
				$bill_Box17 = $this->string_format('', 26);
				$bill_Box17A = $this->string_format('', 17);
			} else {
				$bill_Box17 = $this->string_format($provider, 26);
				$bill_Box17A = $this->string_format($npi, 17);
			}
		}
		if ($result2['insurance_box_31'] == 'n') {
			$bill_Box31 = $this->string_format($provider, 21);
		} else {
			$this->db->where('id', $encounterInfo->user_id);
			$provider2 = $this->db->get('users')->row_array();
			$provider2a = $provider2['lastname'] . ", " . $provider2['firstname'];
			$bill_Box31 = $this->string_format($provider2a, 21);
		}
		$bill_Box33B = $this->string_format($provider, 29);
		$pos = $encounterInfo->encounter_location;
		$bill_Box25 = $practiceInfo->tax_id;
		$bill_Box25 = $this->string_format($bill_Box25, 15);
		$bill_Box26 = $this->string_format($pid, 14);	
		$bill_Box32A = $practiceInfo->practice_name;
		$bill_Box32A = $this->string_format($bill_Box32A, 26);
		$bill_Box32B = $practiceInfo->street_address1;
		if ($practiceInfo->street_address2 != '') {
			$bill_Box32B .= ', ' . $practiceInfo->street_address2;
		}
		$bill_Box32B = $this->string_format($bill_Box32B, 26);
		$bill_Box32C = $practiceInfo->city . ', ' . $practiceInfo->state . ' ' . $practiceInfo->zip;
		$bill_Box32C = $this->string_format($bill_Box32C, 26);
		if ($result2['insurance_box_32a'] == 'n') {
			$bill_Box32D = $practiceInfo->npi;
		} else {
			$this->db->where('id', $encounterInfo->user_id);
			$provider3 = $this->db->get('providers')->row_array();
			$bill_Box32D = $provider3['npi'];
		}
		$bill_Box32D = $this->string_format($bill_Box32D, 10);
		$bill_Box33A = $practiceInfo->phone;
		$bill_Box33A = $this->string_format($bill_Box33A, 14);
		$bill_Box33C = $practiceInfo->billing_street_address1;
		if ($practiceInfo->billing_street_address2 != '') {
			$bill_Box33C .= ', ' . $practiceInfo->billing_street_address2;
		}
		$bill_Box33C = $this->string_format($bill_Box33C, 29);
		$bill_Box33D = $practiceInfo->billing_city . ', ' . $practiceInfo->billing_state . ' ' . $practiceInfo->billing_zip;
		$bill_Box33D = $this->string_format($bill_Box33D, 29);
		$this->db->where('eid', $eid);
		$this->db->where('billing_group', '1');
		$this->db->not_like('cpt','sp','after');
		$this->db->order_by('cpt_charge', 'desc'); 
		$query5 = $this->db->get('billing_core');
		$result5 = $query5->result_array();
		$num_rows5 = $query5->num_rows();
		if ($num_rows5 > 0) {
			$query6= $this->encounters_model->getAssessment($eid);
			$result6 = $query6->row_array();
			$bill_Box21_1 = $this->string_format($result6['assessment_icd1'], 8);
			$bill_Box21_2 = $this->string_format($result6['assessment_icd2'], 8);
			$bill_Box21_3 = $this->string_format($result6['assessment_icd3'], 8);
			$bill_Box21_4 = $this->string_format($result6['assessment_icd4'], 8);
			$i = 0;
			foreach ($result5 as $key5 => $value5) {
				$cpt_charge5[$key5]  = $value5['cpt_charge'];
			}
			array_multisort($cpt_charge5, SORT_DESC, $result5);
			while ($i < $num_rows5 ) {
				$cpt_final[$i] = $result5[$i];
				$cpt_final[$i]['dos_f'] = str_replace('/', '', $cpt_final[$i]['dos_f']);
				$cpt_final[$i]['dos_f'] = $this->string_format($cpt_final[$i]['dos_f'], 8);
				$cpt_final[$i]['dos_t'] = str_replace('/', '', $cpt_final[$i]['dos_t']);
				$cpt_final[$i]['dos_t'] = $this->string_format($cpt_final[$i]['dos_t'], 8);
				$cpt_final[$i]['pos'] = $this->string_format($pos, 5);
				$cpt_final[$i]['cpt'] = $this->string_format($cpt_final[$i]['cpt'], 6);
				$cpt_final[$i]['modifier'] = $this->string_format($cpt_final[$i]['modifier'], 11);
				$cpt_final[$i]['unit1'] = $cpt_final[$i]['unit'];
				$cpt_final[$i]['unit'] = $this->string_format($cpt_final[$i]['unit'] ,5);
				$cpt_final[$i]['cpt_charge'] = number_format($cpt_final[$i]['cpt_charge'], 2, ' ', '');
				$cpt_final[$i]['cpt_charge1'] = $cpt_final[$i]['cpt_charge'];
				$cpt_final[$i]['cpt_charge'] = $this->string_format($cpt_final[$i]['cpt_charge'], 8);
				$cpt_final[$i]['npi'] = $this->string_format($npi, 11);
				$cpt_final[$i]['icd_pointer'] =  $this->string_format($cpt_final[$i]['icd_pointer'], 4);
				$i++;
			}
			if ($num_rows5 < 6) {
				$array['dos_f'] = $this->string_format('', 8);
				$array['dos_t'] = $this->string_format('', 8);
				$array['pos'] = $this->string_format('', 5);
				$array['cpt'] = $this->string_format('', 6);
				$array['modifier'] = $this->string_format('', 11);
				$array['unit1'] = '0';
				$array['unit'] = $this->string_format('', 5);
				$array['cpt_charge1'] = '0';
				$array['cpt_charge'] = $this->string_format('', 8);
				$array['npi'] = $this->string_format('', 11);
				$array['icd_pointer'] =  $this->string_format('', 4);
				$cpt_final = array_pad($cpt_final, 6, $array);
			}
			$bill_Box28 = $cpt_final[0]['cpt_charge1'] * $cpt_final[0]['unit1'] + $cpt_final[1]['cpt_charge1'] * $cpt_final[1]['unit1'] + $cpt_final[2]['cpt_charge1'] * $cpt_final[2]['unit1'] + $cpt_final[3]['cpt_charge1'] * $cpt_final[3]['unit1'] + $cpt_final[4]['cpt_charge1'] * $cpt_final[4]['unit1'] + $cpt_final[5]['cpt_charge1'] * $cpt_final[5]['unit1'];
			$bill_Box28 = number_format($bill_Box28, 2, ' ', '');
			$bill_Box28 = $this->string_format($bill_Box28, 9);
			$bill_Box29 = $this->string_format('0 00', 8);
			$bill_Box30 = $this->string_format($bill_Box28, 8);
			$data1 = array(
				'eid' 						=> $eid,
				'pid' 						=> $pid,
				'insurance_id_1' 			=> $insurance_id_1,
				'insurance_id_2' 			=> $insurance_id_2,
				'bill_Box11C' 				=> $bill_Box11C,	//Insurance Plan Name
				'bill_payor_id'				=> $bill_payor_id,
				'bill_ins_add1'				=> $bill_ins_add1,
				'bill_ins_add2'				=> $bill_ins_add2,
				'bill_Box1'					=> $bill_Box1,
				'bill_Box1P'				=> $bill_Box1P,
				'bill_Box1A' 				=> $bill_Box1A, 	//Insured ID Number
				'bill_Box2' 				=> $bill_Box2, 	//Patient Name
				'bill_Box3A' 				=> $bill_Box3A, 	//Patient Date of Birth
				'bill_Box3B' 				=> $bill_Box3B, 	//Patient Sex
				'bill_Box3BP' 				=> $bill_Box3BP,
				'bill_Box4'					=> $bill_Box4, 	//Insured Name
				'bill_Box5A' 				=> $bill_Box5A, 	//Patient Address
				'bill_Box6'					=> $bill_Box6, 	//Patient Relationship to Insured
				'bill_Box6P'				=> $bill_Box6P,
				'bill_Box7A'				=> $bill_Box7A, 	//Insured Address
				'bill_Box5B' 				=> $bill_Box5B, 	//Patient City
				'bill_Box5C' 				=> $bill_Box5C, 	//Patient State
				'bill_Box8A'				=> $bill_Box8A, 	//Patient Marital Status
				'bill_Box8AP'				=> $bill_Box8AP,
				'bill_Box7B'				=> $bill_Box7B, 	//Insured City
				'bill_Box7C'				=> $bill_Box7C, 	//Insured State
				'bill_Box5D'				=> $bill_Box5D,	//Patient Zip
				'bill_Box5E'				=> $bill_Box5E,
				'bill_Box8B'				=> $bill_Box8B,	//Patient Employment
				'bill_Box8BP'				=> $bill_Box8BP,
				'bill_Box7D'				=> $bill_Box7D,	//Insured Zip
				'bill_Box9'					=> $bill_Box9, 	//Other Insured Name
				'bill_Box11'				=> $bill_Box11, 	//Insured Group Number
				'bill_Box9A'				=> $bill_Box9A, 	//Other Insured Group Number
				'bill_Box10'				=> $bill_Box10, 
				'bill_Box10A'				=> $bill_Box10A,	//Condition Employment
				'bill_Box10AP'				=> $bill_Box10AP,	//Condition Employment
				'bill_Box11A1'				=> $bill_Box11A1,	//Insured Date of Birth
				'bill_Box11A2'				=> $bill_Box11A2,	//Insured Sex
				'bill_Box11A2P'				=> $bill_Box11A2P,
				'bill_Box9B1'				=> $bill_Box9B1,	//Other Insured Date of Birth
				'bill_Box9B2'				=> $bill_Box9B2,	//Other Insured Sex
				'bill_Box9B2P'				=> $bill_Box9B2P,
				'bill_Box10B1'				=> $bill_Box10B1,	//Condition Auto Accident
				'bill_Box10B1P'				=> $bill_Box10B1P,	//Condition Auto Accident
				'bill_Box10B2'				=> $bill_Box10B2,	//Condition Auto Accident State
				'bill_Box11B'				=> $bill_Box11B,	//Insured Employer
				'bill_Box9C'				=> $bill_Box9C,	//Other Insured Employer
				'bill_Box10C'				=> $bill_Box10C,	//Condition Other Accident
				'bill_Box10CP'				=> $bill_Box10CP,	//Condition Other Accident
				'bill_Box9D'				=> $bill_Box9D,	//Other Insurance Plan Name
				'bill_Box11D'				=> $bill_Box11D,
				'bill_Box11DP'				=> $bill_Box11DP,	//Other Insurance Plan Exist
				'bill_Box17' 				=> $bill_Box17,	//Provider Use for Box 31 and 33B too
				'bill_Box17A' 				=> $bill_Box17A,	//Provider NPI
				'bill_Box21_1' 				=> $bill_Box21_1,	//ICD1
				'bill_Box21_2' 				=> $bill_Box21_2,	//ICD2
				'bill_Box21_3' 				=> $bill_Box21_3,	//ICD3
				'bill_Box21_4' 				=> $bill_Box21_4,	//ICD4
				'bill_DOS1F' 				=> $cpt_final[0]['dos_f'],
				'bill_DOS1T' 				=> $cpt_final[0]['dos_t'],
				'bill_DOS2F' 				=> $cpt_final[1]['dos_f'],
				'bill_DOS2T' 				=> $cpt_final[1]['dos_t'],
				'bill_DOS3F' 				=> $cpt_final[2]['dos_f'],
				'bill_DOS3T' 				=> $cpt_final[2]['dos_t'],
				'bill_DOS4F' 				=> $cpt_final[3]['dos_f'],
				'bill_DOS4T' 				=> $cpt_final[3]['dos_t'],
				'bill_DOS5F' 				=> $cpt_final[4]['dos_f'],
				'bill_DOS5T'				=> $cpt_final[4]['dos_t'],
				'bill_DOS6F' 				=> $cpt_final[5]['dos_f'],
				'bill_DOS6T' 				=> $cpt_final[5]['dos_t'],
				'bill_Box24B1' 				=> $cpt_final[0]['pos'],	//Place of Service 1
				'bill_Box24B2' 				=> $cpt_final[1]['pos'],	//Place of Service 2
				'bill_Box24B3'				=> $cpt_final[2]['pos'],	//Place of Service 3
				'bill_Box24B4' 				=> $cpt_final[3]['pos'],	//Place of Service 4
				'bill_Box24B5' 				=> $cpt_final[4]['pos'],	//Place of Service 5
				'bill_Box24B6' 				=> $cpt_final[5]['pos'],	//Place of Service 6
				'bill_Box24D1' 				=> $cpt_final[0]['cpt'],	//CPT1
				'bill_Box24D2'				=> $cpt_final[1]['cpt'],	//CPT2
				'bill_Box24D3' 				=> $cpt_final[2]['cpt'],	//CPT3
				'bill_Box24D4' 				=> $cpt_final[3]['cpt'],	//CPT4
				'bill_Box24D5' 				=> $cpt_final[4]['cpt'],	//CPT5
				'bill_Box24D6' 				=> $cpt_final[5]['cpt'],	//CPT6
				'bill_Modifier1'			=> $cpt_final[0]['modifier'],
				'bill_Modifier2'			=> $cpt_final[1]['modifier'],
				'bill_Modifier3'			=> $cpt_final[2]['modifier'],
				'bill_Modifier4'			=> $cpt_final[3]['modifier'],
				'bill_Modifier5'			=> $cpt_final[4]['modifier'],
				'bill_Modifier6'			=> $cpt_final[5]['modifier'],
				'bill_Box24E1'				=> $cpt_final[0]['icd_pointer'],	//Diagnosis Pointer 1
				'bill_Box24E2'				=> $cpt_final[1]['icd_pointer'],	//Diagnosis Pointer 2
				'bill_Box24E3'				=> $cpt_final[2]['icd_pointer'],	//Diagnosis Pointer 3
				'bill_Box24E4'				=> $cpt_final[3]['icd_pointer'],	//Diagnosis Pointer 4
				'bill_Box24E5'				=> $cpt_final[4]['icd_pointer'],	//Diagnosis Pointer 5
				'bill_Box24E6'				=> $cpt_final[5]['icd_pointer'],	//Diagnosis Pointer 6
				'bill_Box24F1' 				=> number_format($cpt_final[0]['cpt_charge'] * $cpt_final[0]['unit'], 2, ' ', ''),	//Charges 1
				'bill_Box24F2'				=> number_format($cpt_final[1]['cpt_charge'] * $cpt_final[1]['unit'], 2, ' ', ''),	//Charges 2
				'bill_Box24F3' 				=> number_format($cpt_final[2]['cpt_charge'] * $cpt_final[2]['unit'], 2, ' ', ''),	//Charges 3
				'bill_Box24F4' 				=> number_format($cpt_final[3]['cpt_charge'] * $cpt_final[3]['unit'], 2, ' ', ''),	//Charges 4
				'bill_Box24F5' 				=> number_format($cpt_final[4]['cpt_charge'] * $cpt_final[4]['unit'], 2, ' ', ''),	//Charges 5
				'bill_Box24F6' 				=> number_format($cpt_final[5]['cpt_charge'] * $cpt_final[5]['unit'], 2, ' ', ''),	//Charges 6
				'bill_Box24G1'				=> $cpt_final[0]['unit'],	//Units 1
				'bill_Box24G2'				=> $cpt_final[1]['unit'],	//Units 2
				'bill_Box24G3'				=> $cpt_final[2]['unit'],	//Units 3
				'bill_Box24G4'				=> $cpt_final[3]['unit'],	//Units 4
				'bill_Box24G5'				=> $cpt_final[4]['unit'],	//Units 5
				'bill_Box24G6'				=> $cpt_final[5]['unit'],	//Units 6
				'bill_Box24J1' 				=> $cpt_final[0]['npi'],	//NPI 1
				'bill_Box24J2' 				=> $cpt_final[1]['npi'],	//NPI 2
				'bill_Box24J3' 				=> $cpt_final[2]['npi'],	//NPI 3
				'bill_Box24J4' 				=> $cpt_final[3]['npi'],	//NPI 4
				'bill_Box24J5' 				=> $cpt_final[4]['npi'],	//NPI 5
				'bill_Box24J6' 				=> $cpt_final[5]['npi'],	//NPI 6
				'bill_Box25' 				=> $bill_Box25,	//Clinic Tax ID
				'bill_Box26' 				=> $bill_Box26,	//pid
				'bill_Box27'				=> $bill_Box27,	//Accept Assignment
				'bill_Box27P'				=> $bill_Box27P,	//Accept Assignment
				'bill_Box28' 				=> $bill_Box28,	//Total Charges
				'bill_Box29'				=> $bill_Box29,
				'bill_Box30'				=> $bill_Box30,
				'bill_Box31'				=> $bill_Box31,
				'bill_Box32A' 				=> $bill_Box32A,	//Clinic Name
				'bill_Box32B' 				=> $bill_Box32B,	//Clinic Address 1
				'bill_Box32C'				=> $bill_Box32C,	//Clinic Address 2
				'bill_Box32D' 				=> $bill_Box32D,	//Clinic NPI use for 33E too
				'bill_Box33A' 				=> $bill_Box33A,	//Clinic Phone
				'bill_Box33B'				=> $bill_Box33B,
				'bill_Box33C'				=> $bill_Box33C,	//Billing Address 1
				'bill_Box33D'				=> $bill_Box33D,	//Billing Address 2
				'bill_Box33E'				=> $bill_Box32D
			);
			$this->encounters_model->addBilling($data1);
			$this->audit_model->add();
			unset($cpt_final[0]);
			unset($cpt_final[1]);
			unset($cpt_final[2]);
			unset($cpt_final[3]);
			unset($cpt_final[4]);
			unset($cpt_final[5]);
			if ($num_rows5 > 6 && $num_rows5 < 11) {
				$k = 6;
				foreach ($cpt_final as $k=>$v) {
					$l = $k - 6;
					$cpt_final[$l] = $cpt_final[$k];
					unset($cpt_final[$k]);
					$k++;
				}
				$num_rows6 = count($cpt_final);
				if ($num_rows6 < 6) {
					$array1['dos_f'] = $this->string_format('', 8);
					$array1['dos_t'] = $this->string_format('', 8);
					$array1['pos'] = $this->string_format('', 5);
					$array1['cpt'] = $this->string_format('', 6);
					$array1['modifier'] = $this->string_format('', 11);
					$array1['unit1'] = '0';
					$array1['unit'] = $this->string_format('', 5);
					$array1['cpt_charge1'] = '0';
					$array1['cpt_charge'] = $this->string_format('', 8);
					$array1['npi'] = $this->string_format('', 11);
					$array1['icd_pointer'] =  $this->string_format('', 4);
					$cpt_final = array_pad($cpt_final, 6, $array1);
				}
				$bill_Box28 = $cpt_final[0]['cpt_charge1'] * $cpt_final[0]['unit1'] + $cpt_final[1]['cpt_charge1'] * $cpt_final[1]['unit1'] + $cpt_final[2]['cpt_charge1'] * $cpt_final[2]['unit1'] + $cpt_final[3]['cpt_charge1'] * $cpt_final[3]['unit1'] + $cpt_final[4]['cpt_charge1'] * $cpt_final[4]['unit1'] + $cpt_final[5]['cpt_charge1'] * $cpt_final[5]['unit1'];
				$bill_Box28 = number_format($bill_Box28, 2, ' ', '');
				$bill_Box28 = $this->string_format($bill_Box28, 9);
				$bill_Box29 = $this->string_format('0 00', 8);
				$bill_Box30 = $this->string_format($bill_Box28, 8);
				$data2 = array(
					'eid' 						=> $eid,
					'pid' 						=> $pid,
					'insurance_id_1' 			=> $insurance_id_1,
					'insurance_id_2' 			=> $insurance_id_2,
					'bill_Box11C' 				=> $bill_Box11C,	//Insurance Plan Name
					'bill_payor_id'				=> $bill_payor_id,
					'bill_ins_add1'				=> $bill_ins_add1,
					'bill_ins_add2'				=> $bill_ins_add2,
					'bill_Box1'					=> $bill_Box1,
					'bill_Box1P'				=> $bill_Box1P,
					'bill_Box1A' 				=> $bill_Box1A, 	//Insured ID Number
					'bill_Box2' 				=> $bill_Box2, 	//Patient Name
					'bill_Box3A' 				=> $bill_Box3A, 	//Patient Date of Birth
					'bill_Box3B' 				=> $bill_Box3B, 	//Patient Sex
					'bill_Box3BP' 				=> $bill_Box3BP,
					'bill_Box4'					=> $bill_Box4, 	//Insured Name
					'bill_Box5A' 				=> $bill_Box5A, 	//Patient Address
					'bill_Box6'					=> $bill_Box6, 	//Patient Relationship to Insured
					'bill_Box6P'				=> $bill_Box6P,
					'bill_Box7A'				=> $bill_Box7A, 	//Insured Address
					'bill_Box5B' 				=> $bill_Box5B, 	//Patient City
					'bill_Box5C' 				=> $bill_Box5C, 	//Patient State
					'bill_Box8A'				=> $bill_Box8A, 	//Patient Marital Status
					'bill_Box8AP'				=> $bill_Box8AP,
					'bill_Box7B'				=> $bill_Box7B, 	//Insured City
					'bill_Box7C'				=> $bill_Box7C, 	//Insured State
					'bill_Box5D'				=> $bill_Box5D,	//Patient Zip
					'bill_Box5E'				=> $bill_Box5E,
					'bill_Box8B'				=> $bill_Box8B,	//Patient Employment
					'bill_Box8BP'				=> $bill_Box8BP,
					'bill_Box7D'				=> $bill_Box7D,	//Insured Zip
					'bill_Box9'					=> $bill_Box9, 	//Other Insured Name
					'bill_Box11'				=> $bill_Box11, 	//Insured Group Number
					'bill_Box9A'				=> $bill_Box9A, 	//Other Insured Group Number
					'bill_Box10'				=> $bill_Box10, 
					'bill_Box10A'				=> $bill_Box10A,	//Condition Employment
					'bill_Box10AP'				=> $bill_Box10AP,	//Condition Employment
					'bill_Box11A1'				=> $bill_Box11A1,	//Insured Date of Birth
					'bill_Box11D'				=> $bill_Box11D,
					'bill_Box11DP'				=> $bill_Box11DP,
					'bill_Box11A2'				=> $bill_Box11A2,	//Insured Sex
					'bill_Box11A2P'				=> $bill_Box11A2P,
					'bill_Box9B1'				=> $bill_Box9B1,	//Other Insured Date of Birth
					'bill_Box9B2'				=> $bill_Box9B2,	//Other Insured Sex
					'bill_Box9B2P'				=> $bill_Box9B2P,
					'bill_Box10B1'				=> $bill_Box10B1,	//Condition Auto Accident
					'bill_Box10B1P'				=> $bill_Box10B1P,	//Condition Auto Accident
					'bill_Box10B2'				=> $bill_Box10B2,	//Condition Auto Accident State
					'bill_Box11B'				=> $bill_Box11B,	//Insured Employer
					'bill_Box9C'				=> $bill_Box9C,	//Other Insured Employer
					'bill_Box10C'				=> $bill_Box10C,	//Condition Other Accident
					'bill_Box10CP'				=> $bill_Box10CP,	//Condition Other Accident
					'bill_Box9D'				=> $bill_Box9D,	//Other Insurance Plan Name
					'bill_Box11D'				=> $bill_Box11D,	//Other Insurance Plan Exist
					'bill_Box11DP'				=> $bill_Box11DP,
					'bill_Box17' 				=> $bill_Box17,	//Provider Use for Box 31 and 33B too
					'bill_Box17A' 				=> $bill_Box17A,	//Provider NPI
					'bill_Box21_1' 				=> $bill_Box21_1,	//ICD1
					'bill_Box21_2' 				=> $bill_Box21_2,	//ICD2
					'bill_Box21_3' 				=> $bill_Box21_3,	//ICD3
					'bill_Box21_4' 				=> $bill_Box21_4,	//ICD4
					'bill_DOS1F' 				=> $cpt_final[0]['dos_f'],
					'bill_DOS1T' 				=> $cpt_final[0]['dos_t'],
					'bill_DOS2F' 				=> $cpt_final[1]['dos_f'],
					'bill_DOS2T' 				=> $cpt_final[1]['dos_t'],
					'bill_DOS3F' 				=> $cpt_final[2]['dos_f'],
					'bill_DOS3T' 				=> $cpt_final[2]['dos_t'],
					'bill_DOS4F' 				=> $cpt_final[3]['dos_f'],
					'bill_DOS4T' 				=> $cpt_final[3]['dos_t'],
					'bill_DOS5F' 				=> $cpt_final[4]['dos_f'],
					'bill_DOS5T'				=> $cpt_final[4]['dos_t'],
					'bill_DOS6F' 				=> $cpt_final[5]['dos_f'],
					'bill_DOS6T' 				=> $cpt_final[5]['dos_t'],
					'bill_Box24B1' 				=> $cpt_final[0]['pos'],	//Place of Service 1
					'bill_Box24B2' 				=> $cpt_final[1]['pos'],	//Place of Service 2
					'bill_Box24B3'				=> $cpt_final[2]['pos'],	//Place of Service 3
					'bill_Box24B4' 				=> $cpt_final[3]['pos'],	//Place of Service 4
					'bill_Box24B5' 				=> $cpt_final[4]['pos'],	//Place of Service 5
					'bill_Box24B6' 				=> $cpt_final[5]['pos'],	//Place of Service 6
					'bill_Box24D1' 				=> $cpt_final[0]['cpt'],	//CPT1
					'bill_Box24D2'				=> $cpt_final[1]['cpt'],	//CPT2
					'bill_Box24D3' 				=> $cpt_final[2]['cpt'],	//CPT3
					'bill_Box24D4' 				=> $cpt_final[3]['cpt'],	//CPT4
					'bill_Box24D5' 				=> $cpt_final[4]['cpt'],	//CPT5
					'bill_Box24D6' 				=> $cpt_final[5]['cpt'],	//CPT6
					'bill_Modifier1'			=> $cpt_final[0]['modifier'],
					'bill_Modifier2'			=> $cpt_final[1]['modifier'],
					'bill_Modifier3'			=> $cpt_final[2]['modifier'],
					'bill_Modifier4'			=> $cpt_final[3]['modifier'],
					'bill_Modifier5'			=> $cpt_final[4]['modifier'],
					'bill_Modifier6'			=> $cpt_final[5]['modifier'],
					'bill_Box24E1'				=> $cpt_final[0]['icd_pointer'],	//Diagnosis Pointer 1
					'bill_Box24E2'				=> $cpt_final[1]['icd_pointer'],	//Diagnosis Pointer 2
					'bill_Box24E3'				=> $cpt_final[2]['icd_pointer'],	//Diagnosis Pointer 3
					'bill_Box24E4'				=> $cpt_final[3]['icd_pointer'],	//Diagnosis Pointer 4
					'bill_Box24E5'				=> $cpt_final[4]['icd_pointer'],	//Diagnosis Pointer 5
					'bill_Box24E6'				=> $cpt_final[5]['icd_pointer'],	//Diagnosis Pointer 6
					'bill_Box24F1' 				=> number_format($cpt_final[0]['cpt_charge'] * $cpt_final[0]['unit'], 2, ' ', ''),	//Charges 1
					'bill_Box24F2'				=> number_format($cpt_final[1]['cpt_charge'] * $cpt_final[1]['unit'], 2, ' ', ''),	//Charges 2
					'bill_Box24F3' 				=> number_format($cpt_final[2]['cpt_charge'] * $cpt_final[2]['unit'], 2, ' ', ''),	//Charges 3
					'bill_Box24F4' 				=> number_format($cpt_final[3]['cpt_charge'] * $cpt_final[3]['unit'], 2, ' ', ''),	//Charges 4
					'bill_Box24F5' 				=> number_format($cpt_final[4]['cpt_charge'] * $cpt_final[4]['unit'], 2, ' ', ''),	//Charges 5
					'bill_Box24F6' 				=> number_format($cpt_final[5]['cpt_charge'] * $cpt_final[5]['unit'], 2, ' ', ''),	//Charges 6
					'bill_Box24G1'				=> $cpt_final[0]['unit'],	//Units 1
					'bill_Box24G2'				=> $cpt_final[1]['unit'],	//Units 2
					'bill_Box24G3'				=> $cpt_final[2]['unit'],	//Units 3
					'bill_Box24G4'				=> $cpt_final[3]['unit'],	//Units 4
					'bill_Box24G5'				=> $cpt_final[4]['unit'],	//Units 5
					'bill_Box24G6'				=> $cpt_final[5]['unit'],	//Units 6
					'bill_Box24J1' 				=> $cpt_final[0]['npi'],	//NPI 1
					'bill_Box24J2' 				=> $cpt_final[1]['npi'],	//NPI 2
					'bill_Box24J3' 				=> $cpt_final[2]['npi'],	//NPI 3
					'bill_Box24J4' 				=> $cpt_final[3]['npi'],	//NPI 4
					'bill_Box24J5' 				=> $cpt_final[4]['npi'],	//NPI 5
					'bill_Box24J6' 				=> $cpt_final[5]['npi'],	//NPI 6
					'bill_Box25' 				=> $bill_Box25,	//Clinic Tax ID
					'bill_Box26' 				=> $bill_Box26,	//pid
					'bill_Box27'				=> $bill_Box27,	//Accept Assignment
					'bill_Box27P'				=> $bill_Box27P,	//Accept Assignment
					'bill_Box28' 				=> $bill_Box28,	//Total Charges
					'bill_Box29'				=> $bill_Box29,
					'bill_Box30'				=> $bill_Box30,
					'bill_Box31'				=> $bill_Box31,
					'bill_Box32A' 				=> $bill_Box32A,	//Clinic Name
					'bill_Box32B' 				=> $bill_Box32B,	//Clinic Address 1
					'bill_Box32C'				=> $bill_Box32C,	//Clinic Address 2
					'bill_Box32D' 				=> $bill_Box32D,	//Clinic NPI use for 33E too
					'bill_Box33A' 				=> $bill_Box33A,	//Clinic Phone
					'bill_Box33B'				=> $bill_Box33B,
					'bill_Box33C'				=> $bill_Box33C,	//Billing Address 1
					'bill_Box33D'				=> $bill_Box33D,	//Billing Address 2
					'bill_Box33E'				=> $bill_Box32D
				);
				$this->encounters_model->addBilling($data2);
				$this->audit_model->add();
				unset($cpt_final[0]);
				unset($cpt_final[1]);
				unset($cpt_final[2]);
				unset($cpt_final[3]);
				unset($cpt_final[4]);
				unset($cpt_final[5]);
			}	
		} else {
			return "No CPT charges filed. Billing not saved.";
			exit (0);
		}
		$this->db->where('eid', $eid);
		$this->db->where('billing_group', '2');
		$this->db->order_by('cpt_charge', 'desc'); 
		$query7 = $this->db->get('billing_core');
		$result7 = $query7->result_array();
		$num_rows7 = $query7->num_rows();
		if ($num_rows7 > 0) {
			$query8= $this->encounters_model->getAssessment($eid);
			$result8 = $query8->row_array();
			$bill_Box21_1 = $this->string_format($result8['assessment_icd5'], 8);
			$bill_Box21_2 = $this->string_format($result8['assessment_icd6'], 8);
			$bill_Box21_3 = $this->string_format($result8['assessment_icd7'], 8);
			$bill_Box21_4 = $this->string_format($result8['assessment_icd8'], 8);
			$i = 0;
			foreach ($result7 as $key7 => $value7) {
				$cpt_charge7[$key7]  = $value7['cpt_charge'];
			}
			array_multisort($cpt_charge7, SORT_DESC, $result7);
			while ($i < $num_rows7 ) {
				$cpt_final[$i] = $result7[$i];
				$cpt_final[$i]['dos_f'] = str_replace('/', '', $cpt_final[$i]['dos_f']);
				$cpt_final[$i]['dos_f'] = $this->string_format($cpt_final[$i]['dos_f'], 8);
				$cpt_final[$i]['dos_t'] = str_replace('/', '', $cpt_final[$i]['dos_t']);
				$cpt_final[$i]['dos_t'] = $this->string_format($cpt_final[$i]['dos_t'], 8);
				$cpt_final[$i]['pos'] =  $this->string_format($pos, 5);
				$cpt_final[$i]['cpt'] = $this->string_format($cpt_final[$i]['cpt'], 6);
				$cpt_final[$i]['modifier'] = $this->string_format($cpt_final[$i]['modifier'], 11);
				$cpt_final[$i]['unit1'] = $cpt_final[$i]['unit'];
				$cpt_final[$i]['unit'] = $this->string_format($cpt_final[$i]['unit'] ,5);
				$cpt_final[$i]['cpt_charge'] = number_format($cpt_final[$i]['cpt_charge'], 2, ' ', '');
				$cpt_final[$i]['cpt_charge1'] = $cpt_final[$i]['cpt_charge'];
				$cpt_final[$i]['cpt_charge'] = $this->string_format($cpt_final[$i]['cpt_charge'], 8);
				$cpt_final[$i]['npi'] = $this->string_format($npi, 11);
				$cpt_final[$i]['icd_pointer'] =  $this->string_format($cpt_final[$i]['icd_pointer'], 4);
				$icd_pointer_final = '';
				foreach (str_split($cpt_final[$i]['icd_pointer']) as $icd_element) {
					$icd_pointer_final .= $icd_element - 4;
				}
				$cpt_final[$i]['icd_pointer'] =  $this->string_format($icd_pointer_final, 4);
				$i++;
			}
			if ($num_rows7 < 6) {
				$array['dos_f'] = $this->string_format('', 8);
				$array['dos_t'] = $this->string_format('', 8);
				$array['pos'] = $this->string_format('', 5);
				$array['cpt'] = $this->string_format('', 6);
				$array['modifier'] = $this->string_format('', 11);
				$array['unit1'] = '0';
				$array['unit'] = $this->string_format('', 5);
				$array['cpt_charge1'] = '0';
				$array['cpt_charge'] = $this->string_format('', 8);
				$array['npi'] = $this->string_format('', 11);
				$array['icd_pointer'] =  $this->string_format('', 4);
				$cpt_final = array_pad($cpt_final, 6, $array);
			}
			$bill_Box28 = $cpt_final[0]['cpt_charge1'] * $cpt_final[0]['unit1'] + $cpt_final[1]['cpt_charge1'] * $cpt_final[1]['unit1'] + $cpt_final[2]['cpt_charge1'] * $cpt_final[2]['unit1'] + $cpt_final[3]['cpt_charge1'] * $cpt_final[3]['unit1'] + $cpt_final[4]['cpt_charge1'] * $cpt_final[4]['unit1'] + $cpt_final[5]['cpt_charge1'] * $cpt_final[5]['unit1'];
			$bill_Box28 = number_format($bill_Box28, 2, ' ', '');
			$bill_Box28 = $this->string_format($bill_Box28, 9);
			$bill_Box29 = $this->string_format('0 00', 8);
			$bill_Box30 = $this->string_format($bill_Box28, 8);
			$data3 = array(
				'eid' 						=> $eid,
				'pid' 						=> $pid,
				'insurance_id_1' 			=> $insurance_id_1,
				'insurance_id_2' 			=> $insurance_id_2,
				'bill_Box11C' 				=> $bill_Box11C,	//Insurance Plan Name
				'bill_payor_id'				=> $bill_payor_id,
				'bill_ins_add1'				=> $bill_ins_add1,
				'bill_ins_add2'				=> $bill_ins_add2,
				'bill_Box1'					=> $bill_Box1,
				'bill_Box1P'				=> $bill_Box1P,
				'bill_Box1A' 				=> $bill_Box1A, 	//Insured ID Number
				'bill_Box2' 				=> $bill_Box2, 	//Patient Name
				'bill_Box3A' 				=> $bill_Box3A, 	//Patient Date of Birth
				'bill_Box3B' 				=> $bill_Box3B, 	//Patient Sex
				'bill_Box3BP' 				=> $bill_Box3BP,
				'bill_Box4'					=> $bill_Box4, 	//Insured Name
				'bill_Box5A' 				=> $bill_Box5A, 	//Patient Address
				'bill_Box6'					=> $bill_Box6, 	//Patient Relationship to Insured
				'bill_Box6P'				=> $bill_Box6P,
				'bill_Box7A'				=> $bill_Box7A, 	//Insured Address
				'bill_Box5B' 				=> $bill_Box5B, 	//Patient City
				'bill_Box5C' 				=> $bill_Box5C, 	//Patient State
				'bill_Box8A'				=> $bill_Box8A, 	//Patient Marital Status
				'bill_Box8AP'				=> $bill_Box8AP,
				'bill_Box7B'				=> $bill_Box7B, 	//Insured City
				'bill_Box7C'				=> $bill_Box7C, 	//Insured State
				'bill_Box5D'				=> $bill_Box5D,	//Patient Zip
				'bill_Box5E'				=> $bill_Box5E,
				'bill_Box8B'				=> $bill_Box8B,	//Patient Employment
				'bill_Box8BP'				=> $bill_Box8BP,
				'bill_Box7D'				=> $bill_Box7D,	//Insured Zip
				'bill_Box9'					=> $bill_Box9, 	//Other Insured Name
				'bill_Box11'				=> $bill_Box11, 	//Insured Group Number
				'bill_Box9A'				=> $bill_Box9A, 	//Other Insured Group Number
				'bill_Box10'				=> $bill_Box10, 
				'bill_Box10A'				=> $bill_Box10A,	//Condition Employment
				'bill_Box10AP'				=> $bill_Box10AP,	//Condition Employment
				'bill_Box11A1'				=> $bill_Box11A1,	//Insured Date of Birth
				'bill_Box11A2'				=> $bill_Box11A2,	//Insured Sex
				'bill_Box11A2P'				=> $bill_Box11A2P,
				'bill_Box9B1'				=> $bill_Box9B1,	//Other Insured Date of Birth
				'bill_Box9B2'				=> $bill_Box9B2,	//Other Insured Sex
				'bill_Box9B2P'				=> $bill_Box9B2P,
				'bill_Box10B1'				=> $bill_Box10B1,	//Condition Auto Accident
				'bill_Box10B1P'				=> $bill_Box10B1P,	//Condition Auto Accident
				'bill_Box10B2'				=> $bill_Box10B2,	//Condition Auto Accident State
				'bill_Box11B'				=> $bill_Box11B,	//Insured Employer
				'bill_Box9C'				=> $bill_Box9C,	//Other Insured Employer
				'bill_Box10C'				=> $bill_Box10C,	//Condition Other Accident
				'bill_Box10CP'				=> $bill_Box10CP,	//Condition Other Accident
				'bill_Box9D'				=> $bill_Box9D,	//Other Insurance Plan Name
				'bill_Box11D'				=> $bill_Box11D,	//Other Insurance Plan Exist
				'bill_Box11DP'				=> $bill_Box11DP,
				'bill_Box17' 				=> $bill_Box17,	//Provider Use for Box 31 and 33B too
				'bill_Box17A' 				=> $bill_Box17A,	//Provider NPI
				'bill_Box21_1' 				=> $bill_Box21_1,	//ICD1
				'bill_Box21_2' 				=> $bill_Box21_2,	//ICD2
				'bill_Box21_3' 				=> $bill_Box21_3,	//ICD3
				'bill_Box21_4' 				=> $bill_Box21_4,	//ICD4
				'bill_DOS1F' 				=> $cpt_final[0]['dos_f'],
				'bill_DOS1T' 				=> $cpt_final[0]['dos_t'],
				'bill_DOS2F' 				=> $cpt_final[1]['dos_f'],
				'bill_DOS2T' 				=> $cpt_final[1]['dos_t'],
				'bill_DOS3F' 				=> $cpt_final[2]['dos_f'],
				'bill_DOS3T' 				=> $cpt_final[2]['dos_t'],
				'bill_DOS4F' 				=> $cpt_final[3]['dos_f'],
				'bill_DOS4T' 				=> $cpt_final[3]['dos_t'],
				'bill_DOS5F' 				=> $cpt_final[4]['dos_f'],
				'bill_DOS5T'				=> $cpt_final[4]['dos_t'],
				'bill_DOS6F' 				=> $cpt_final[5]['dos_f'],
				'bill_DOS6T' 				=> $cpt_final[5]['dos_t'],
				'bill_Box24B1' 				=> $cpt_final[0]['pos'],	//Place of Service 1
				'bill_Box24B2' 				=> $cpt_final[1]['pos'],	//Place of Service 2
				'bill_Box24B3'				=> $cpt_final[2]['pos'],	//Place of Service 3
				'bill_Box24B4' 				=> $cpt_final[3]['pos'],	//Place of Service 4
				'bill_Box24B5' 				=> $cpt_final[4]['pos'],	//Place of Service 5
				'bill_Box24B6' 				=> $cpt_final[5]['pos'],	//Place of Service 6
				'bill_Box24D1' 				=> $cpt_final[0]['cpt'],	//CPT1
				'bill_Box24D2'				=> $cpt_final[1]['cpt'],	//CPT2
				'bill_Box24D3' 				=> $cpt_final[2]['cpt'],	//CPT3
				'bill_Box24D4' 				=> $cpt_final[3]['cpt'],	//CPT4
				'bill_Box24D5' 				=> $cpt_final[4]['cpt'],	//CPT5
				'bill_Box24D6' 				=> $cpt_final[5]['cpt'],	//CPT6
				'bill_Modifier1'			=> $cpt_final[0]['modifier'],
				'bill_Modifier2'			=> $cpt_final[1]['modifier'],
				'bill_Modifier3'			=> $cpt_final[2]['modifier'],
				'bill_Modifier4'			=> $cpt_final[3]['modifier'],
				'bill_Modifier5'			=> $cpt_final[4]['modifier'],
				'bill_Modifier6'			=> $cpt_final[5]['modifier'],
				'bill_Box24E1'				=> $cpt_final[0]['icd_pointer'],	//Diagnosis Pointer 1
				'bill_Box24E2'				=> $cpt_final[1]['icd_pointer'],	//Diagnosis Pointer 2
				'bill_Box24E3'				=> $cpt_final[2]['icd_pointer'],	//Diagnosis Pointer 3
				'bill_Box24E4'				=> $cpt_final[3]['icd_pointer'],	//Diagnosis Pointer 4
				'bill_Box24E5'				=> $cpt_final[4]['icd_pointer'],	//Diagnosis Pointer 5
				'bill_Box24E6'				=> $cpt_final[5]['icd_pointer'],	//Diagnosis Pointer 6
				'bill_Box24F1' 				=> number_format($cpt_final[0]['cpt_charge'] * $cpt_final[0]['unit'], 2, ' ', ''),	//Charges 1
				'bill_Box24F2'				=> number_format($cpt_final[1]['cpt_charge'] * $cpt_final[1]['unit'], 2, ' ', ''),	//Charges 2
				'bill_Box24F3' 				=> number_format($cpt_final[2]['cpt_charge'] * $cpt_final[2]['unit'], 2, ' ', ''),	//Charges 3
				'bill_Box24F4' 				=> number_format($cpt_final[3]['cpt_charge'] * $cpt_final[3]['unit'], 2, ' ', ''),	//Charges 4
				'bill_Box24F5' 				=> number_format($cpt_final[4]['cpt_charge'] * $cpt_final[4]['unit'], 2, ' ', ''),	//Charges 5
				'bill_Box24F6' 				=> number_format($cpt_final[5]['cpt_charge'] * $cpt_final[5]['unit'], 2, ' ', ''),	//Charges 6
				'bill_Box24G1'				=> $cpt_final[0]['unit'],	//Units 1
				'bill_Box24G2'				=> $cpt_final[1]['unit'],	//Units 2
				'bill_Box24G3'				=> $cpt_final[2]['unit'],	//Units 3
				'bill_Box24G4'				=> $cpt_final[3]['unit'],	//Units 4
				'bill_Box24G5'				=> $cpt_final[4]['unit'],	//Units 5
				'bill_Box24G6'				=> $cpt_final[5]['unit'],	//Units 6
				'bill_Box24J1' 				=> $cpt_final[0]['npi'],	//NPI 1
				'bill_Box24J2' 				=> $cpt_final[1]['npi'],	//NPI 2
				'bill_Box24J3' 				=> $cpt_final[2]['npi'],	//NPI 3
				'bill_Box24J4' 				=> $cpt_final[3]['npi'],	//NPI 4
				'bill_Box24J5' 				=> $cpt_final[4]['npi'],	//NPI 5
				'bill_Box24J6' 				=> $cpt_final[5]['npi'],	//NPI 6
				'bill_Box25' 				=> $bill_Box25,	//Clinic Tax ID
				'bill_Box26' 				=> $bill_Box26,	//pid
				'bill_Box27'				=> $bill_Box27,	//Accept Assignment
				'bill_Box27P'				=> $bill_Box27P,	//Accept Assignment
				'bill_Box28' 				=> $bill_Box28,	//Total Charges
				'bill_Box29'				=> $bill_Box29,
				'bill_Box30'				=> $bill_Box30,
				'bill_Box31'				=> $bill_Box31,
				'bill_Box32A' 				=> $bill_Box32A,	//Clinic Name
				'bill_Box32B' 				=> $bill_Box32B,	//Clinic Address 1
				'bill_Box32C'				=> $bill_Box32C,	//Clinic Address 2
				'bill_Box32D' 				=> $bill_Box32D,	//Clinic NPI use for 33E too
				'bill_Box33A' 				=> $bill_Box33A,	//Clinic Phone
				'bill_Box33B'				=> $bill_Box33B,
				'bill_Box33C'				=> $bill_Box33C,	//Billing Address 1
				'bill_Box33D'				=> $bill_Box33D,	//Billing Address 2
				'bill_Box33E'				=> $bill_Box32D
			);
			$this->encounters_model->addBilling($data3);
			$this->audit_model->add();
			unset($cpt_final[0]);
			unset($cpt_final[1]);
			unset($cpt_final[2]);
			unset($cpt_final[3]);
			unset($cpt_final[4]);
			unset($cpt_final[5]);
			if ($num_rows7 > 6 && $num_rows7 < 11) {
				$k = 6;
				foreach ($cpt_final as $k=>$v) {
					$l = $k - 6;
					$cpt_final[$l] = $cpt_final[$k];
					unset($cpt_final[$k]);
					$k++;
				}
				$num_rows6 = count($cpt_final);
				if ($num_rows6 < 6) {
					$array1['dos_f'] = $this->string_format('', 8);
					$array1['dos_t'] = $this->string_format('', 8);
					$array1['pos'] = $this->string_format('', 5);
					$array1['cpt'] = $this->string_format('', 6);
					$array1['modifier'] = $this->string_format('', 11);
					$array1['unit1'] = '0';
					$array1['unit'] = $this->string_format('', 5);
					$array1['cpt_charge1'] = '0';
					$array1['cpt_charge'] = $this->string_format('', 8);
					$array1['npi'] = $this->string_format('', 11);
					$array1['icd_pointer'] =  $this->string_format('', 4);
					$cpt_final = array_pad($cpt_final, 6, $array1);
				}
				$bill_Box28 = $cpt_final[0]['cpt_charge1'] * $cpt_final[0]['unit1'] + $cpt_final[1]['cpt_charge1'] * $cpt_final[1]['unit1'] + $cpt_final[2]['cpt_charge1'] * $cpt_final[2]['unit1'] + $cpt_final[3]['cpt_charge1'] * $cpt_final[3]['unit1'] + $cpt_final[4]['cpt_charge1'] * $cpt_final[4]['unit1'] + $cpt_final[5]['cpt_charge1'] * $cpt_final[5]['unit1'];
				$bill_Box28 = number_format($bill_Box28, 2, ' ', '');
				$bill_Box28 = $this->string_format($bill_Box28, 9);
				$bill_Box29 = $this->string_format('0 00', 8);
				$bill_Box30 = $this->string_format($bill_Box28, 8);
				$data4 = array(
					'eid' 						=> $eid,
					'pid' 						=> $pid,
					'insurance_id_1' 			=> $insurance_id_1,
					'insurance_id_2' 			=> $insurance_id_2,
					'bill_Box11C' 				=> $bill_Box11C,	//Insurance Plan Name
					'bill_payor_id'				=> $bill_payor_id,
					'bill_ins_add1'				=> $bill_ins_add1,
					'bill_ins_add2'				=> $bill_ins_add2,
					'bill_Box1'					=> $bill_Box1,
					'bill_Box1P'				=> $bill_Box1P,
					'bill_Box1A' 				=> $bill_Box1A, 	//Insured ID Number
					'bill_Box2' 				=> $bill_Box2, 	//Patient Name
					'bill_Box3A' 				=> $bill_Box3A, 	//Patient Date of Birth
					'bill_Box3B' 				=> $bill_Box3B, 	//Patient Sex
					'bill_Box3BP' 				=> $bill_Box3BP,
					'bill_Box4'					=> $bill_Box4, 	//Insured Name
					'bill_Box5A' 				=> $bill_Box5A, 	//Patient Address
					'bill_Box6'					=> $bill_Box6, 	//Patient Relationship to Insured
					'bill_Box6P'				=> $bill_Box6P,
					'bill_Box7A'				=> $bill_Box7A, 	//Insured Address
					'bill_Box5B' 				=> $bill_Box5B, 	//Patient City
					'bill_Box5C' 				=> $bill_Box5C, 	//Patient State
					'bill_Box8A'				=> $bill_Box8A, 	//Patient Marital Status
					'bill_Box8AP'				=> $bill_Box8AP,
					'bill_Box7B'				=> $bill_Box7B, 	//Insured City
					'bill_Box7C'				=> $bill_Box7C, 	//Insured State
					'bill_Box5D'				=> $bill_Box5D,	//Patient Zip
					'bill_Box5E'				=> $bill_Box5E,
					'bill_Box8B'				=> $bill_Box8B,	//Patient Employment
					'bill_Box8BP'				=> $bill_Box8BP,
					'bill_Box7D'				=> $bill_Box7D,	//Insured Zip
					'bill_Box9'					=> $bill_Box9, 	//Other Insured Name
					'bill_Box11'				=> $bill_Box11, 	//Insured Group Number
					'bill_Box9A'				=> $bill_Box9A, 	//Other Insured Group Number
					'bill_Box10'				=> $bill_Box10, 
					'bill_Box10A'				=> $bill_Box10A,	//Condition Employment
					'bill_Box10AP'				=> $bill_Box10AP,	//Condition Employment
					'bill_Box11A1'				=> $bill_Box11A1,	//Insured Date of Birth
					'bill_Box11A2'				=> $bill_Box11A2,	//Insured Sex
					'bill_Box11A2P'				=> $bill_Box11A2P,
					'bill_Box9B1'				=> $bill_Box9B1,	//Other Insured Date of Birth
					'bill_Box9B2'				=> $bill_Box9B2,	//Other Insured Sex
					'bill_Box9B2P'				=> $bill_Box9B2P,
					'bill_Box10B1'				=> $bill_Box10B1,	//Condition Auto Accident
					'bill_Box10B1P'				=> $bill_Box10B1P,	//Condition Auto Accident
					'bill_Box10B2'				=> $bill_Box10B2,	//Condition Auto Accident State
					'bill_Box11B'				=> $bill_Box11B,	//Insured Employer
					'bill_Box9C'				=> $bill_Box9C,	//Other Insured Employer
					'bill_Box10C'				=> $bill_Box10C,	//Condition Other Accident
					'bill_Box10CP'				=> $bill_Box10CP,	//Condition Other Accident
					'bill_Box9D'				=> $bill_Box9D,	//Other Insurance Plan Name
					'bill_Box11D'				=> $bill_Box11D,	//Other Insurance Plan Exist
					'bill_Box11DP'				=> $bill_Box11DP,
					'bill_Box17' 				=> $bill_Box17,	//Provider Use for Box 31 and 33B too
					'bill_Box17A' 				=> $bill_Box17A,	//Provider NPI
					'bill_Box21_1' 				=> $bill_Box21_1,	//ICD1
					'bill_Box21_2' 				=> $bill_Box21_2,	//ICD2
					'bill_Box21_3' 				=> $bill_Box21_3,	//ICD3
					'bill_Box21_4' 				=> $bill_Box21_4,	//ICD4
					'bill_DOS1F' 				=> $cpt_final[0]['dos_f'],
					'bill_DOS1T' 				=> $cpt_final[0]['dos_t'],
					'bill_DOS2F' 				=> $cpt_final[1]['dos_f'],
					'bill_DOS2T' 				=> $cpt_final[1]['dos_t'],
					'bill_DOS3F' 				=> $cpt_final[2]['dos_f'],
					'bill_DOS3T' 				=> $cpt_final[2]['dos_t'],
					'bill_DOS4F' 				=> $cpt_final[3]['dos_f'],
					'bill_DOS4T' 				=> $cpt_final[3]['dos_t'],
					'bill_DOS5F' 				=> $cpt_final[4]['dos_f'],
					'bill_DOS5T'				=> $cpt_final[4]['dos_t'],
					'bill_DOS6F' 				=> $cpt_final[5]['dos_f'],
					'bill_DOS6T' 				=> $cpt_final[5]['dos_t'],
					'bill_Box24B1' 				=> $cpt_final[0]['pos'],	//Place of Service 1
					'bill_Box24B2' 				=> $cpt_final[1]['pos'],	//Place of Service 2
					'bill_Box24B3'				=> $cpt_final[2]['pos'],	//Place of Service 3
					'bill_Box24B4' 				=> $cpt_final[3]['pos'],	//Place of Service 4
					'bill_Box24B5' 				=> $cpt_final[4]['pos'],	//Place of Service 5
					'bill_Box24B6' 				=> $cpt_final[5]['pos'],	//Place of Service 6
					'bill_Box24D1' 				=> $cpt_final[0]['cpt'],	//CPT1
					'bill_Box24D2'				=> $cpt_final[1]['cpt'],	//CPT2
					'bill_Box24D3' 				=> $cpt_final[2]['cpt'],	//CPT3
					'bill_Box24D4' 				=> $cpt_final[3]['cpt'],	//CPT4
					'bill_Box24D5' 				=> $cpt_final[4]['cpt'],	//CPT5
					'bill_Box24D6' 				=> $cpt_final[5]['cpt'],	//CPT6
					'bill_Modifier1'			=> $cpt_final[0]['modifier'],
					'bill_Modifier2'			=> $cpt_final[1]['modifier'],
					'bill_Modifier3'			=> $cpt_final[2]['modifier'],
					'bill_Modifier4'			=> $cpt_final[3]['modifier'],
					'bill_Modifier5'			=> $cpt_final[4]['modifier'],
					'bill_Modifier6'			=> $cpt_final[5]['modifier'],
					'bill_Box24E1'				=> $cpt_final[0]['icd_pointer'],	//Diagnosis Pointer 1
					'bill_Box24E2'				=> $cpt_final[1]['icd_pointer'],	//Diagnosis Pointer 2
					'bill_Box24E3'				=> $cpt_final[2]['icd_pointer'],	//Diagnosis Pointer 3
					'bill_Box24E4'				=> $cpt_final[3]['icd_pointer'],	//Diagnosis Pointer 4
					'bill_Box24E5'				=> $cpt_final[4]['icd_pointer'],	//Diagnosis Pointer 5
					'bill_Box24E6'				=> $cpt_final[5]['icd_pointer'],	//Diagnosis Pointer 6
					'bill_Box24F1' 				=> number_format($cpt_final[0]['cpt_charge'] * $cpt_final[0]['unit'], 2, ' ', ''),	//Charges 1
					'bill_Box24F2'				=> number_format($cpt_final[1]['cpt_charge'] * $cpt_final[1]['unit'], 2, ' ', ''),	//Charges 2
					'bill_Box24F3' 				=> number_format($cpt_final[2]['cpt_charge'] * $cpt_final[2]['unit'], 2, ' ', ''),	//Charges 3
					'bill_Box24F4' 				=> number_format($cpt_final[3]['cpt_charge'] * $cpt_final[3]['unit'], 2, ' ', ''),	//Charges 4
					'bill_Box24F5' 				=> number_format($cpt_final[4]['cpt_charge'] * $cpt_final[4]['unit'], 2, ' ', ''),	//Charges 5
					'bill_Box24F6' 				=> number_format($cpt_final[5]['cpt_charge'] * $cpt_final[5]['unit'], 2, ' ', ''),	//Charges 6
					'bill_Box24G1'				=> $cpt_final[0]['unit'],	//Units 1
					'bill_Box24G2'				=> $cpt_final[1]['unit'],	//Units 2
					'bill_Box24G3'				=> $cpt_final[2]['unit'],	//Units 3
					'bill_Box24G4'				=> $cpt_final[3]['unit'],	//Units 4
					'bill_Box24G5'				=> $cpt_final[4]['unit'],	//Units 5
					'bill_Box24G6'				=> $cpt_final[5]['unit'],	//Units 6
					'bill_Box24J1' 				=> $cpt_final[0]['npi'],	//NPI 1
					'bill_Box24J2' 				=> $cpt_final[1]['npi'],	//NPI 2
					'bill_Box24J3' 				=> $cpt_final[2]['npi'],	//NPI 3
					'bill_Box24J4' 				=> $cpt_final[3]['npi'],	//NPI 4
					'bill_Box24J5' 				=> $cpt_final[4]['npi'],	//NPI 5
					'bill_Box24J6' 				=> $cpt_final[5]['npi'],	//NPI 6
					'bill_Box25' 				=> $bill_Box25,	//Clinic Tax ID
					'bill_Box26' 				=> $bill_Box26,	//pid
					'bill_Box27'				=> $bill_Box27,	//Accept Assignment
					'bill_Box27P'				=> $bill_Box27P,	//Accept Assignment
					'bill_Box28' 				=> $bill_Box28,	//Total Charges
					'bill_Box29'				=> $bill_Box29,
					'bill_Box30'				=> $bill_Box30,
					'bill_Box31'				=> $bill_Box31,
					'bill_Box32A' 				=> $bill_Box32A,	//Clinic Name
					'bill_Box32B' 				=> $bill_Box32B,	//Clinic Address 1
					'bill_Box32C'				=> $bill_Box32C,	//Clinic Address 2
					'bill_Box32D' 				=> $bill_Box32D,	//Clinic NPI use for 33E too
					'bill_Box33A' 				=> $bill_Box33A,	//Clinic Phone
					'bill_Box33B'				=> $bill_Box33B,
					'bill_Box33C'				=> $bill_Box33C,	//Billing Address 1
					'bill_Box33D'				=> $bill_Box33D,	//Billing Address 2
					'bill_Box33E'				=> $bill_Box32D
				);
				$this->encounters_model->addBilling($data4);
				$this->audit_model->add();
			}	
		}
		return 'Billing saved and waiting to be submitted!';
	}
	
	function billing_save1()
	{
		$insurance_id_1 = $this->input->post('insurance_id_1');
		$insurance_id_2 = $this->input->post('insurance_id_2');
		$eid = $this->input->post('eid');
		$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $eid);
		echo $result;
	}
	
	function page_invoice1($eid)
	{
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('billing_pid');
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
			$charge = 0;
			$payment = 0;
			$data['text'] = "";
			foreach ($result1 as $key1 => $value1) {
				$cpt_charge1[$key1]  = $value1['cpt_charge'];
			}
			array_multisort($cpt_charge1, SORT_DESC, $result1);
			foreach ($result1 as $result1a) {
				if ($result1a['cpt']) {
					$this->db->where('cpt', $result1a['cpt']);
					$query2 = $this->db->get('cpt');
					$result2 = $query2->row_array();
					$data['text'] .= '<table class="order" cellspacing="10"><tr><th style="width:100">PROCEDURE</th><th style="width:100">UNITS</th><th style="width:350">DESCRIPTION</th><th style="width:150">CHARGE</th></tr>';
					$data['text'] .= '<tr><td>' . $result1a['cpt'] . '</td><td>' . $result1a['unit'] . '</td><td>' . $result2['cpt_description'] . '</td><td>$' . $result1a['cpt_charge'] . '</td></tr></table>';
					$charge += $result1a['cpt_charge'] * $result1a['unit'];
				} else {
					$data['text'] .= '<table class="order" cellspacing="10"><tr><td style="width:100">Date of Payment:</td><td style="width:100">' . $result1a['dos_f'] . '</td><td style="width:350">' . $result1a['payment_type'] . '</td><td style="width:150">$(' . $result1a['payment'] . ')</td></tr></table>';
					$payment = $payment + $result1a['payment'];
				}
			}
			$balance = $charge - $payment;
			$charge = number_format($charge, 2, '.', ',');
			$payment = number_format($payment, 2, '.', ',');
			$balance = number_format($balance, 2, '.', ',');
			$data['text'] .= '<table class="order" cellspacing="10"><tr><td style="width:100"></td><td style="width:100"></td><td style="width:350"><strong>Total Charges:</strong></td><td style="width:150"><strong>$' . $charge . '</strong></td></tr><tr><td></td><td></td><td><strong>Total Payments:</strong></td><td><strong>$' . $payment . '</strong></td></tr><tr><td></td><td></td><td></td><td><hr/></td></tr><tr><td></td><td></td><td><strong>Remaining Balance:</strong></td><td><strong>$' . $balance . '</strong></td></tr></table>';
		} else {
			$data['text'] = 'No procedures.';
		}
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$notes = $this->db->get('demographics_notes')->row_array();
		$practice = $this->practiceinfo_model->get($practice_id)->row();
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['text'] .= '<br>Please send a check payable to ' . $practice->practice_name . ' and mail it to:';
		$data['text'] .= '<br>' . $practice->billing_street_address1;
		if ($practice->billing_street_address2 != '') {
			$data['text'] .= ', ' . $practice->billing_street_address2;
		}
		$data['text'] .= '<br>' . $practice->billing_city . ', ' . $practice->billing_state . ' ' . $practice->billing_zip;
		$data['patientInfo1'] = $row['firstname'] . ' ' . $row['lastname'];
		$data['patientInfo2'] = $row['address'];
		$data['patientInfo3'] = $row['city'] . ', ' . $row['state'] . ' ' . $row['zip'];
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
		if (is_null($notes['billing_notes']) || $notes['billing_notes'] == '') {
			$billing_notes = 'Invoice for encounter (Date of Service: ' . $data['encounter_DOS'] . ') printed on ' . $data['date'] . '.';
		} else {
			$billing_notes = $notes['billing_notes'] . "\n" . 'Invoice for encounter (Date of Service: ' . $data['encounter_DOS'] . ') printed on ' . $data['date'] . '.';
		}
		$billing_notes_data = array(
			'billing_notes' => $billing_notes
		);
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->update('demographics_notes', $billing_notes_data);
		$this->audit_model->update();
		return $this->load->view('auth/pages/invoice_page',$data, TRUE);
	}
	
	function print_invoice1($eid, $insurance_id_1='', $insurance_id_2='')
	{
		if ($insurance_id_1 != '') {
			$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $eid);
		}
		ini_set('memory_limit','196M');
		$user_id = $this->session->userdata('user_id');
		$file_path = "/var/www/nosh/invoice_" . now() . "_" . $user_id . ".pdf";
		$html = $this->page_invoice1($eid);
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetTitle('Generated by NOSH ChartingSystem');
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
	
	function page_invoice2($id)
	{
		$practice_id = $this->session->userdata('practice_id');
		$pid = $this->session->userdata('billing_pid');
		if ($pid == FALSE) {
			redirect('start');
		}	
		$query1 = $this->db->query("SELECT * FROM billing_core WHERE other_billing_id=$id  AND payment='0'");
		$result1 = $query1->row_array();
		$num_rows1 = $query1->num_rows();
		if ($num_rows1 > 0) {
			$data['text'] = '<table class="order" cellspacing="10"><tr><th style="width:100">DATE</th><th style="width:100">UNITS</th><th style="width:350">DESCRIPTION</th><th style="width:150">CHARGE</th></tr>';
			$charge = 0;
			$payment = 0;
			$data['text'] .= '<tr><td>' . $result1['dos_f'] . '</td><td>' . $result1['unit'] . '</td><td>' . $result1['reason'] . '</td><td>$' . $result1['cpt_charge'] . '</td></tr>';
			$charge += $result1['cpt_charge'] * $result1['unit'];
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
		$this->db->where('pid', $pid);
		$row = $this->db->get('demographics')->row_array();
		$practice = $this->practiceinfo_model->get($practice_id)->row();
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['text'] .= '<br>Please send a check payable to ' . $practice->practice_name . ' and mail it to:';
		$data['text'] .= '<br>' . $practice->billing_street_address1;
		if ($practice->billing_street_address2 != '') {
			$data['text'] .= ', ' . $practice->billing_street_address2;
		}
		$data['text'] .= '<br>' . $practice->billing_city . ', ' . $practice->billing_state . ' ' . $practice->billing_zip;
		$data['patientInfo1'] = $row['firstname'] . ' ' . $row['lastname'];
		$data['patientInfo2'] = $row['address'];
		$data['patientInfo3'] = $row['city'] . ', ' . $row['state'] . ' ' . $row['zip'];
		$data['patientInfo'] = $this->demographics_model->get($pid)->row();
		$dob1 = human_to_unix($data['patientInfo']->DOB);
		$data['dob'] = date('m/d/Y', $dob1);
		$data['title'] = "INVOICE";
		$date = now();
		$data['date'] = date('F jS, Y', $date);
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$notes = $this->db->get('demographics_notes')->row_array();
		if (is_null($notes['billing_notes']) || $notes['billing_notes'] == '') {
			$billing_notes = 'Invoice for ' . $result1['reason'] . ' (Date of Bill: ' . $result1['dos_f'] . ') printed on ' . $data['date'] . '.';
		} else {
			$billing_notes = $notes['billing_notes'] . "\n" . 'Invoice for ' . $result1['reason'] . ' (Date of Bill: ' . $result1['dos_f'] . ') printed on ' . $data['date'] . '.';
		}
		$billing_notes_data = array(
			'billing_notes' => $billing_notes
		);
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->update('demographics_notes', $billing_notes_data);
		$this->audit_model->update();
		return $this->load->view('auth/pages/invoice_page2',$data, TRUE);
	}
	
	function print_invoice2($id)
	{
		$html = $this->page_invoice2($id);
		ini_set('memory_limit','196M');
		if (file_exists('/var/www/nosh/invoice.pdf')) {
			unlink('/var/www/nosh/invoice.pdf');
		}
		$file_path = '/var/www/nosh/invoice.pdf';
		$this->load->library('mpdf');
		$this->mpdf->useOnlyCoreFonts = true;
		$this->mpdf->shrink_tables_to_fit = 1;
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
			exit();
		} else {
			die('LOGGED! bad file '.$file_path);
		}
	}
	
	function generate_hcfa1($eid, $insurance_id_1, $insurance_id_2)
	{
		$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $eid);
		$pid = $this->session->userdata('pid');
		$this->db->where('eid', $eid);
		$query = $this->db->get('billing');
		$input1 = '';
		if ($query->num_rows() > 0) {
			$i = 0;
			if (file_exists('/var/www/nosh/hcfa1500_output_final.pdf')) {
				unlink('/var/www/nosh/hcfa1500_output_final.pdf');
			}
			foreach ($query->result_array() as $pdfinfo) {
				$input = '/var/www/nosh/hcfa1500.pdf';
				$output = '/var/www/nosh/hcfa1500_output_' . $i . '.pdf';
				if (file_exists($output)) {
					unlink($output);
				}
				$data='<?xml version="1.0" encoding="UTF-8"?>'."\n".
					'<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">'."\n".
					'<fields>'."\n";
				foreach($pdfinfo as $field => $val) {
					$data.='<field name="'.$field.'">'."\n";
					if($field == 'bill_DOS1F' || $field == 'bill_DOS1T' || $field == 'bill_DOS2F' || $field == 'bill_DOS2T' || $field == 'bill_DOS3F' || $field == 'bill_DOS3T' || $field == 'bill_DOS4F' || $field == 'bill_DOS4T' || $field == 'bill_DOS5F' || $field == 'bill_DOS5T' || $field == 'bill_DOS6F' || $field == 'bill_DOS6T') {
						$val_array = str_split($val, 2);
						$val_array1 = array($val_array[0], ' ', $val_array[1], ' ', $val_array[2], $val_array[3]);
						$val = implode($val_array1);
					}
					if($field == 'bill_Box3A' || $field == 'bill_Box9B1' || $field == 'bill_Box11A1'){
						$val_array2 = str_split($val, 3);
						$val_array3 = array($val_array2[0], $val_array2[1], '', $val_array2[2], $val_array2[3]);
						$val = implode($val_array3);
					}
					if($field == 'bill_Box24F1' ||$field == 'bill_Box24F2' || $field == 'bill_Box24F3' || $field == 'bill_Box24F4' || $field == 'bill_Box24F5' || $field == 'bill_Box24F6' || $field == 'bill_Box28' || $field == 'bill_Box29' || $field == 'bill_Box30') {
						$val = rtrim($val);
					}
					if(is_array($val)) {
						foreach($val as $opt)
							$data.='<value>'.$opt.'</value>'."\n";
					} else {
						$data.='<value>'.$val.'</value>'."\n";
					}
					$data.='</field>'."\n";
				}
				$data.='<field name="Date">'."\n<value>".date('m/d/Y')."</value>\n</field>\n";
				$data.='<field name="Date2">'."\n<value>".date('m/d/y')."</value>\n</field>\n";
				$data.='</fields>'."\n".
					'<ids original="'.md5($input).'" modified="'.time().'" />'."\n".
					'<f href="'.$input.'" />'."\n".
					'</xfdf>'."\n";		
				$xfdf_fn= '/var/www/nosh/temp.xfdf';
				$xfp= fopen( $xfdf_fn, 'w' );
				if( $xfp ) {
				   fwrite( $xfp, $data );
				   fclose( $xfp );
				} else {
					$result_message = 'Error making xfdf!';
					echo $result_message;
					exit (0);
				}		
				$commandpdf = "pdftk " . $input . " fill_form " . $xfdf_fn . " output " . $output;
				$commandpdf1 = escapeshellcmd($commandpdf);
				exec($commandpdf1);
				if ($i > 0) {
					$input1 .= ' ' . $output;
				} else {
					$input1 = $output;	
				}
				$i++;
			}
			$user_id = $this->session->userdata('user_id');
			$file_path = "/var/www/nosh/hcfa1500_output_final_" . $user_id . ".pdf";
			$commandpdf2 = "pdftk " . $input1 . " cat output " . $file_path;
			$commandpdf3 = escapeshellcmd($commandpdf2);
			$data1 = array(
				'bill_submitted' => 'Done'
			);
			$this->encounters_model->updateEncounter($eid, $data1);
			exec($commandpdf3);
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
		$response['userdata']['dos_f'] = 'Total Payments:';
		$response['userdata']['payment'] = $total;
		echo json_encode($response);
		exit( 0 );
	}
	
	function billing_payment_history2($id)
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT * FROM billing_core WHERE other_billing_id=$id AND payment!='0' AND practice_id=$practice_id");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT * FROM billing_core WHERE other_billing_id=$id AND payment!='0' AND practice_id=$practice_id ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$total = 0;
		foreach ($query1->result_array() as $add) {
			$total += $add['payment'];
		}
		$response['rows'] = $records;
		$response['userdata']['dos_f'] = 'Total Payments:';
		$response['userdata']['payment'] = $total;
		echo json_encode($response);
		exit( 0 );
	}
	
	function payment_save()
	{
		$pid = $this->session->userdata('billing_pid');
		if ($pid == FALSE) {
			$result['message'] = "Close Chart";
			echo json_encode($result);
			exit (0);
		}
		$id = $this->input->post('billing_core_id');
		$this->db->where('billing_core_id', $id);
		$query = $this->db->get('billing_core');
		$count = $query->num_rows();
		$data = array(
			'eid' => $this->input->post('eid'),
			'other_billing_id' => $this->input->post('other_billing_id'),
			'pid' => $pid,
			'dos_f' => $this->input->post('dos_f'),
			'payment' => $this->input->post('payment'),
			'payment_type' => $this->input->post('payment_type'),
			'practice_id' => $this->session->userdata('practice_id')
		);
		if ($count > 0) {		
			$this->chart_model->updateBillingCore($id, $data);
			$this->audit_model->update();
			$result['message'] = 'Payment Updated';
		} else {
			$this->chart_model->addBillingCore($data);
			$this->audit_model->add();
			$result['message'] = 'Payment Added';
		}
		$result['eid'] = $this->input->post('eid');
		$result['other_billing_id'] = $this->input->post('other_billing_id');
		$result['dos_f'] = $this->input->post('dos_f');
		$result['payment'] = $this->input->post('payment');
		$result['payment_type'] = $this->input->post('payment_type');
		echo json_encode($result);
	}
	
	function delete_payment1()
	{
		$pid = $this->session->userdata('billing_pid');
		if ($pid == FALSE) {
			$arr['message'] = "Close Chart";
			echo json_encode($arr);
			exit (0);
		}
		$id = $this->input->post('id');
		$this->db->where('billing_core_id', $id);
		$result = $this->db->get('billing_core')->row_array();
		$arr['id'] = $result['eid'];
		$this->chart_model->deleteBillingCore($this->input->post('id'));
		$this->audit_model->delete();
		$this->db->where('eid', $result['eid']);
		$query2 = $this->db->get('billing_core');
		if ($query2->num_rows() > 0) {
			$charge = 0;
			$payment = 0;
			foreach ($query2->result_array() as $row1) {
				$charge += $row1['cpt_charge'] * $row1['unit'];
				$payment += $row1['payment'];
			}
			$arr['balance'] = $charge - $payment;
		} else {
			$arr['balance'] = 0;
		}
		$arr['message'] = "Payment deleted!";
		echo json_encode($arr);
		exit (0);
	}
	
	function delete_payment2()
	{
		$pid = $this->session->userdata('billing_pid');
		if ($pid == FALSE) {
			$arr['message'] = "Close Chart";
			echo json_encode($arr);
			exit (0);
		}
		$id = $this->input->post('id');
		$this->db->where('billing_core_id', $id);
		$result = $this->db->get('billing_core')->row_array();
		$arr['id'] = $result['other_billing_id'];
		$this->chart_model->deleteBillingCore($this->input->post('id'));
		$this->audit_model->delete();
		$this->db->where('other_billing_id', $result['other_billing_id']);
		$query2 = $this->db->get('billing_core');
		$this->db->where('billing_core_id', $result['other_billing_id']);
		$result2 = $this->db->get('billing_core')->row_array();
		if ($query2->num_rows() > 0) {
			$charge = $result2['cpt_charge'] * $result2['unit'];
			$payment = 0;
			foreach ($query2->result_array() as $row1) {
				$payment += $row1['payment'];
			}
			$arr['balance'] = $charge - $payment;
		} else {
			$arr['balance'] = 0;
		}
		$arr['message'] = "Payment deleted!";
		echo json_encode($arr);
		exit (0);
	}
	
	function billing_other_save()
	{
		$pid = $this->session->userdata('billing_pid');
		if ($pid == FALSE) {
			$result['message'] = "Close Chart";
			echo json_encode($result);
			exit (0);
		}
		$id = $this->input->post('other_billing_id');
		$this->db->where('billing_core_id', $id);
		$query = $this->db->get('billing_core');
		$count = $query->num_rows();
		$data = array(
			'eid' => '0',
			'pid' => $pid,
			'dos_f' => $this->input->post('dos_f'),
			'cpt_charge' => $this->input->post('cpt_charge'),
			'reason' => $this->input->post('reason'),
			'unit' => '1',
			'payment' => '0',
			'practice_id' => $this->session->userdata('practice_id')
		);
		if ($count > 0) {		
			$this->chart_model->updateBillingCore($id, $data);
			$this->audit_model->update();
			$result['message'] = 'Miscellaneous Bill Updated';
		} else {
			$id1 = $this->chart_model->addBillingCore($data);
			$this->audit_model->add();
			$data1 = array(
				'other_billing_id' => $id1
			);
			$this->chart_model->updateBillingCore($id1, $data1);
			$this->audit_model->update();
			$result['message'] = 'Miscellaneous Bill Added';
		}
		$result['other_billing_id'] = $this->input->post('other_billing_id');
		$result['dos_f'] = $this->input->post('dos_f');
		$result['cpt_charge'] = $this->input->post('cpt_charge');
		$result['reason'] = $this->input->post('reason');
		echo json_encode($result);
	}
	
	function delete_other_bill()
	{
		$pid = $this->session->userdata('pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		if ($this->input->post('billing_core_id') == '0') {
			echo "Incorrect Payment ID, try again!";
			exit (0);
		} else {
			$this->chart_model->deleteBillingCore($this->input->post('billing_core_id'));
			$this->audit_model->delete();
			$this->db->where('other_billing_id', $this->input->post('billing_core_id'));
			$this->db->delete('billing_core');
			$this->audit_model->delete();
			echo "Miscellaneous bill deleted!";
			exit (0);
		}
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
		$pid = $this->session->userdata('billing_pid');
		$query1 = $this->db->query("SELECT * FROM encounters WHERE pid=$pid AND addendum='n' AND practice_id=$practice_id");
		$i = 0;
		if ($query1->num_rows() > 0) {
			$balance1 = 0;
			foreach ($query1->result_array() as $row1) {
				$this->db->where('eid', $row1['eid']);
				$query1a = $this->db->get('billing_core');
				if ($query1a->num_rows() > 0) {
					$charge1 = 0;
					$payment1 = 0;
					foreach ($query1a->result_array() as $row1a) {
						$charge1 += $row1a['cpt_charge'] * $row1a['unit'];
						$payment1 += $row1a['payment'];
					}
					$balance1 += $charge1 - $payment1;
				} else {
					$balance1 += 0;
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
				$charge2 += $row2['cpt_charge'] * $row2['unit'];
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
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$notes = $this->db->get('demographics_notes')->row_array();
		if (is_null($notes['billing_notes']) || $notes['billing_notes'] == '') {
			$billing_notes = "None.";
		} else {
			$billing_notes = nl2br($notes['billing_notes']);
		}
		echo "<strong>Total Balance: $" .  number_format($total_balance, 2, '.', ',') . "</strong><br><br><strong>Billing Notes: </strong>" . $billing_notes . "<br>";
		exit( 0 );
	}
	
	function monthly_stats()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT DATE_FORMAT(encounter_DOS, '%Y-%m') AS month, COUNT(*) AS patients_seen FROM encounters WHERE addendum='n' AND practice_id=$practice_id GROUP BY month");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT DATE_FORMAT(encounter_DOS, '%Y-%m') AS month, COUNT(*) AS patients_seen FROM encounters WHERE addendum='n' AND practice_id=$practice_id GROUP BY month ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			$month_piece = explode("-", $row['month']);
			$year = $month_piece[0];
			$month = $month_piece[1];
			$row['total_billed'] = 0;
			$row['total_payments'] = 0;
			$row['dnka'] = 0;
			$row['lmc'] = 0;
			$query1a = $this->db->query("SELECT eid FROM encounters WHERE YEAR(encounter_DOS)=$year AND MONTH(encounter_DOS)=$month AND addendum='n' AND practice_id=$practice_id");
			foreach ($query1a->result_array() as $row1) {
				$this->db->where('eid', $row1['eid']);
				$query2 = $this->db->get('billing_core');
				if ($query2->num_rows() > 0) {
					$charge = 0;
					$payment = 0;
					foreach ($query2->result_array() as $row2) {
						if ($row2['payment_type'] != "Write-Off") {
							$charge += $row2['cpt_charge'] * $row2['unit'];
							$payment += $row2['payment'];
						}	
					}
					$row['total_billed'] += $charge;
					$row['total_payments'] += $payment;
				}
			}
			$query1b = $this->db->query("SELECT status FROM schedule JOIN providers ON providers.id=schedule.provider_id WHERE FROM_UNIXTIME(schedule.end, '%Y')=$year AND FROM_UNIXTIME(schedule.end, '%m')='$month' AND providers.practice_id=$practice_id");
			foreach ($query1b->result_array() as $row3) {
				if ($row3['status'] == "DNKA") { 
					$row['dnka'] += 1;
				}
				if ($row3['status'] == "LMC") {
					$row['lmc'] += 1;
				}
			}
			$response['rows'][$i]['id']=$row['month']; 
			$response['rows'][$i]['cell']=array($row['month'],$row['patients_seen'],$row['total_billed'],$row['total_payments'],$row['dnka'],$row['lmc']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function monthly_stats_insurance($id)
	{
		$practice_id = $this->session->userdata('practice_id');
		$month_piece = explode("-", $id);
		$year = $month_piece[0];
		$month = $month_piece[1];
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT t2.insurance_plan_name AS insuranceplan, COUNT(*) AS ins_patients_seen FROM billing AS t1 LEFT JOIN insurance AS t2 ON t1.insurance_id_1=t2.insurance_id LEFT JOIN encounters AS t3 ON t1.eid=t3.eid WHERE YEAR(t3.encounter_DOS)=$year AND MONTH(t3.encounter_DOS)=$month AND t3.addendum='n' AND t3.practice_id=$practice_id GROUP BY insuranceplan");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT t2.insurance_plan_name AS insuranceplan, COUNT(*) AS ins_patients_seen FROM billing AS t1 LEFT JOIN insurance AS t2 ON t1.insurance_id_1=t2.insurance_id LEFT JOIN encounters AS t3 ON t1.eid=t3.eid WHERE YEAR(t3.encounter_DOS)=$year AND MONTH(t3.encounter_DOS)=$month AND t3.addendum='n' AND t3.practice_id=$practice_id GROUP BY insuranceplan ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			if (is_null($row['insuranceplan'])) {
				$row['insuranceplan'] = 'Cash Only';
			}
			$response['rows'][$i]['id']=$row['insuranceplan']; 
			$response['rows'][$i]['cell']=array($row['insuranceplan'],$row['ins_patients_seen']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function yearly_stats()
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT DATE_FORMAT(encounter_DOS, '%Y') AS year, COUNT(*) AS patients_seen FROM encounters WHERE addendum='n' AND practice_id=$practice_id GROUP BY year");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT DATE_FORMAT(encounter_DOS, '%Y') AS year, COUNT(*) AS patients_seen FROM encounters WHERE addendum='n' AND practice_id=$practice_id GROUP BY year ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			$year = $row['year'];
			$row['total_billed'] = 0;
			$row['total_payments'] = 0;
			$row['dnka'] = 0;
			$row['lmc'] = 0;
			$query1a = $this->db->query("SELECT eid FROM encounters WHERE YEAR(encounter_DOS)=$year AND addendum='n' AND practice_id=$practice_id");
			foreach ($query1a->result_array() as $row1) {
				$this->db->where('eid', $row1['eid']);
				$query2 = $this->db->get('billing_core');
				if ($query2->num_rows() > 0) {
					$charge = 0;
					$payment = 0;
					foreach ($query2->result_array() as $row2) {
						if ($row2['payment_type'] != "Write-Off") {
							$charge += $row2['cpt_charge'] * $row2['unit'];
							$payment += $row2['payment'];
						}	
					}
					$row['total_billed'] += $charge;
					$row['total_payments'] += $payment;
				}
			}
			$query1b = $this->db->query("SELECT status FROM schedule JOIN providers ON providers.id=schedule.provider_id WHERE FROM_UNIXTIME(schedule.end, '%Y')=$year AND providers.practice_id=$practice_id");
			foreach ($query1b->result_array() as $row3) {
				if ($row3['status'] == "DNKA") { 
					$row['dnka'] += 1;
				}
				if ($row3['status'] == "LMC") {
					$row['lmc'] += 1;
				}
			}
			$response['rows'][$i]['id']=$row['year']; 
			$response['rows'][$i]['cell']=array($row['year'],$row['patients_seen'],$row['total_billed'],$row['total_payments'],$row['dnka'],$row['lmc']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function yearly_stats_insurance($id)
	{
		$practice_id = $this->session->userdata('practice_id');
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		$query = $this->db->query("SELECT t2.insurance_plan_name AS insuranceplan, COUNT(*) AS ins_patients_seen FROM billing AS t1 LEFT JOIN insurance AS t2 ON t1.insurance_id_1=t2.insurance_id LEFT JOIN encounters AS t3 ON t1.eid=t3.eid WHERE YEAR(t3.encounter_DOS)=$id AND t3.addendum='n' AND t3.practice_id=$practice_id GROUP BY insuranceplan");
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $this->db->query("SELECT t2.insurance_plan_name AS insuranceplan, COUNT(*) AS ins_patients_seen FROM billing AS t1 LEFT JOIN insurance AS t2 ON t1.insurance_id_1=t2.insurance_id LEFT JOIN encounters AS t3 ON t1.eid=t3.eid WHERE YEAR(t3.encounter_DOS)=$id AND t3.addendum='n' AND t3.practice_id=$practice_id GROUP BY insuranceplan ORDER BY $sidx $sord LIMIT $start , $limit");
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$i = 0;
		foreach ($query1->result_array() as $row) {
			if (is_null($row['insuranceplan'])) {
				$row['insuranceplan'] = 'Cash Only';
			}
			$response['rows'][$i]['id']=$row['insuranceplan']; 
			$response['rows'][$i]['cell']=array($row['insuranceplan'],$row['ins_patients_seen']);
			$i++; 
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	function get_billing_notes()
	{
		$pid = $this->session->userdata('billing_pid');
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$notes = $this->db->get('demographics_notes')->row_array();
		if (is_null($notes['billing_notes']) || $notes['billing_notes'] == '') {
			echo "";
		} else {
			echo $notes['billing_notes'];
		}
		exit( 0 );
	}
	
	function edit_billing_notes()
	{
		$pid = $this->session->userdata('billing_pid');
		if ($pid == FALSE) {
			echo "Close Chart";
			exit (0);
		}
		$data = array(
			'billing_notes' => $this->input->post('billing_notes')
		);
		$this->db->where('pid', $pid);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->update('demographics_notes', $data);
		$this->audit_model->update();
		echo "Billing notes updated!";
		exit( 0 );
	}
	
	function insurance_active()
	{
		$pid = $this->session->userdata('billing_pid');
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
		$pid = $this->session->userdata('billing_pid');
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
	
	function edit_insurance()
	{
		$pid = $this->session->userdata('billing_pid');
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
	
	function inactivate_insurance()
	{
		$pid = $this->session->userdata('billing_pid');
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
		$pid = $this->session->userdata('billing_pid');
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
		$pid = $this->session->userdata('billing_pid');
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
		$pid = $this->session->userdata('billing_pid');
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
			'specialty' => 'Insurance'
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
	
	function get_assessment($eid)
	{
		$query= $this->encounters_model->getAssessment($eid);
		$data = $query->row_array();
		echo json_encode($data);
	}
} 
/* End of file: billing.php */
/* Location: application/controllers/provider/billing.php */
