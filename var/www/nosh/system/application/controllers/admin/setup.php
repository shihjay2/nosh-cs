<?php

class Setup extends Application
{

	function Setup()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->auth->restrict('admin');
		$this->load->helper(array('text', 'typography'));
		$this->load->library('csv');
		$this->load->model('practiceinfo_model');
		$this->load->model('demographics_model');
		$this->load->model('audit_model');
	}

	// --------------------------------------------------------------------

	function index()
	{
		$this->auth->view('admin/setup');
	}
	
	function information()
	{
		$settingsInfo = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
		$data['admin'] = $settingsInfo->row();
		$this->load->view('auth/pages/admin/information', $data);
	}
	
	function extensions()
	{
		$this->load->view('auth/pages/admin/extensions');
	}
	
	function cpt()
	{
		$this->load->view('auth/pages/admin/cpt');
	}
	
	function update()
	{
		$this->load->view('auth/pages/admin/update');
	}

	// --------------------------------------------------------------------

	function practicelocation()
	{
		$data = array(
			'practice_name' => $this->input->post('practice_name'),
			'street_address1' => $this->input->post('street_address1'),
			'street_address2' => $this->input->post('street_address2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zip' => $this->input->post('zip'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax'),
			'email' => $this->input->post('email'),
			'website' => $this->input->post('website'),
			'billing_street_address1' => $this->input->post('billing_street_address1'),
			'billing_street_address2' => $this->input->post('billing_street_address2'),
			'billing_city' => $this->input->post('billing_city'),
			'billing_state' => $this->input->post('billing_state'),
			'billing_zip' => $this->input->post('billing_zip'),
			'fax_type' => $this->input->post('fax_type'),
			'fax_email' => $this->input->post('fax_email'),
			'fax_email_password' => $this->input->post('fax_email_password'),
			'fax_email_hostname' => $this->input->post('fax_email_hostname'),
			'smtp_user' => $this->input->post('smtp_user'),
			'smtp_pass' => $this->input->post('smtp_pass'),
			'patient_portal' => $this->input->post('patient_portal'),
			'additional_message' => $this->input->post('additional_message')
		);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->update('practiceinfo', $data);
		$this->audit_model->update();
		$result = 'Practice Settings Updated';
		echo $result;
	}
	
	function get_practicelocation()
	{
		$settingsInfo = $this->practiceinfo_model->get($this->session->userdata('practice_id'));
		$data = $settingsInfo->row_array();
		echo json_encode($data);
		exit( 0 );
	}
	
	function edit_extensions()
	{
		if ($this->input->post('mtm_alert_users')) {
			$mtm_alert_users = implode(",", $this->input->post('mtm_alert_users'));
		} else {
			$mtm_alert_users = '';
		}
		$data = array(
			'rcopia_extension' => $this->input->post('rcopia_extension'),
			'rcopia_apiVendor' => $this->input->post('rcopia_apiVendor'),
			'rcopia_apiPass' => $this->input->post('rcopia_apiPass'),
			'rcopia_apiPractice' => $this->input->post('rcopia_apiPractice'),
			'rcopia_apiSystem' => $this->input->post('rcopia_apiSystem'),
			'updox_extension' => $this->input->post('updox_extension'),
			'mtm_extension' => $this->input->post('mtm_extension'),
			'mtm_alert_users' => $mtm_alert_users,
			'snomed_extension' => $this->input->post('snomed_extension'),
			'vivacare' => $this->input->post('vivacare'),
			'peacehealth_id' => $this->input->post('peacehealth_id')
		);
		if ($this->input->post('rcopia_extension') == 'y') {
			$date = now();
			$datestring = "%m/%d/%Y %H:%i:%s";
			$date1 = mdate($datestring, $date);
			$data['rcopia_update_notification_lastupdate'] = $date1;
		}
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->update('practiceinfo', $data);
		$this->audit_model->update();
		$result = 'Extensions Updated';
		echo $result;
	}
	
	function get_extensions()
	{
		$extensions = $this->practiceinfo_model->get_extensions($this->session->userdata('practice_id'))->row_array();
		echo json_encode($extensions);
		exit( 0 );
	}
	
	function get_providers()
	{
		$query = $this->practiceinfo_model->getProviders($this->session->userdata('practice_id'));
		if ($query->num_rows() > 0) {
			$data1['message'] = "OK";
			foreach ($query->result_array() as $data) {
				$key = $data['id'];
				$value = $data['displayname'];
				$data1[$key] = $value;
			}
		} else {
			$data1['message'] = "No providers available.";
		}
		echo json_encode($data1);
		exit ( 0 );
	}
	
	function check_extension($extension)
	{
		if ($extension == 'snomed') {
			$result = "Extension was not installed correctly.  Run snomed_install.sh from /var/www/nosh/extensions/snomed again.";
			if ($this->db->table_exists('curr_description_f')) {
				if ($this->db->get('curr_description_f')->num_rows() > 0) {
						$result = "Extension status: OK!";
				}
			}
		}
		echo $result;
		exit ( 0 );
	}
	
	// --------------------------------------------------------------------
	
	function checkdir()
	{
		$dir = $this->input->post('documents_dir');
		if ( ! is_writable($dir)){
			$result = 'You need to set the folder to writable permissions.';
		} else {
			$result = 'Success!';
		}
		echo $result;
	}
	
	function practiceinfo()
	{
		$data = array(
			'primary_contact' => $this->input->post('primary_contact'),
			'npi' => $this->input->post('npi'),
			'medicare' => $this->input->post('medicare'),
			'tax_id' => $this->input->post('tax_id'),
			'weight_unit' => $this->input->post('weight_unit'),
			'height_unit' => $this->input->post('height_unit'),
			'temp_unit' => $this->input->post('temp_unit'),
			'hc_unit' => $this->input->post('hc_unit'),
			'default_pos_id' => $this->input->post('default_pos_id'),
			'documents_dir' => $this->input->post('documents_dir')
		);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->update('practiceinfo', $data);
		$this->audit_model->update();
		$result = 'Practice settings updated!';
		echo $result;
	}
	
	function get_practice_logo()
	{
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$row = $this->db->get('practiceinfo')->row_array();
		if ($row['practice_logo'] != '') {
			$logo = str_replace("/var/www/nosh/","",$row['practice_logo']);
			$result['link'] = "<img src='" . base_url() . $logo . "' id='image_target'>";
			$img = $this->getImageFile($row['practice_logo']);
			$result['button'] = "";
			if (imagesx($img) > 350) {
				$result['message'] = "Image width is too large (less than 350px is recommended).  Use the cropping tool to get to the correct width.";
				$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
			} else {
				$result['message'] = "Image width is correct.";
			}
			if (imagesy($img) > 100) {
				$result['message'] .= "  Image height is too large (less than 100px is recommended).  Use the cropping tool to get to the correct height.";
				$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
			} else {
				$result['message'] .= "  Image height is correct.";
			}
		} else {
			$result['link'] = '';
			$result['message'] = '';
		}
		echo json_encode($result);
	}
	
	function practice_logo_upload()
	{
		$config['upload_path'] = "/var/www/nosh/images";
		$config['allowed_types'] = 'gif|jpg|png';
		$config['encrypt_name'] = FALSE;
		$this->load->library('upload', $config);
		$field_name = "fileToUpload";
		if ( ! $this->upload->do_upload($field_name)) {
			$arr['result'] = $this->upload->display_errors();
		} else {
			$image_data1 = $this->upload->data();
			$extension = end(explode(".", $image_data1['full_path']));
			$extension = '.' . $extension;
			$filepath = str_replace($extension, "", $image_data1['full_path']);
			$practice_logo = $filepath . '_' . now() . $extension;
			rename($image_data1['full_path'], $practice_logo);
			$data = array(
				'practice_logo' => $practice_logo
			);
			$this->db->where('practice_id', $this->session->userdata('practice_id'));
			$this->db->update('practiceinfo', $data);
			$this->audit_model->update();
			$img = $this->getImageFile($practice_logo);
			if (imagesx($img) > 350 || imagesy($img) > 100) {
				$width = imagesx($img);
				$height = imagesy($img);
				$scaledDimensions = $this->getDimensions($width,$height,350,100);
				$scaledWidth = $scaledDimensions['scaledWidth'];
				$scaledHeight = $scaledDimensions['scaledHeight'];
				$scaledImage = imagecreatetruecolor($scaledWidth, $scaledHeight);
				imagecopyresampled($scaledImage, $img, 0, 0, 0, 0, $scaledWidth, $scaledHeight, $width, $height);
				$this->saveImage($scaledImage, $practice_logo);
			}
			$arr['result'] = 'Practice logo uploaded!';
		}
		echo json_encode($arr);
	}
	
	function no_practice_logo()
	{
		$data = array(
			'practice_logo' => ''
		);
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$this->db->update('practiceinfo', $data);
		$this->audit_model->update();
	}
	
	function cropimage()
	{
		$this->db->where('practice_id', $this->session->userdata('practice_id'));
		$row = $this->db->get('practiceinfo')->row_array();
		$targ_w = 350;
		$targ_h = 100;
		$img_r = $this->getImageFile($row['practice_logo']);
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		$x = $this->input->post('x');
		$y = $this->input->post('y');
		$w = $this->input->post('w');
		$h = $this->input->post('h');
		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$targ_w,$targ_h,$w,$h);
		$this->saveImage($dst_r, $row['practice_logo']);
		$logo = str_replace("/var/www/nosh/","",$row['practice_logo']);
		$result['link'] = "<img src='" . base_url() . $logo . "' id='image_target'>";
		$result['growl'] = "Logo cropped and saved!";
		$img = $this->getImageFile($row['practice_logo']);
		$result['button'] = "";
		if (imagesx($img) > 350) {
			$result['message'] = "Image width is too large (less than 350px is recommended).  Use the cropping tool to get to the correct width.";
			$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
		} else {
			$result['message'] = "Image width is correct.";
		}
		if (imagesy($img) > 100) {
			$result['message'] .= "  Image height is too large (less than 100px is recommended).  Use the cropping tool to get to the correct height.";
			$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
		} else {
			$result['message'] .= "  Image height is correct.";
		}
		echo json_encode($result);
	}
	
	function getImageFile($file)
	{
		$type =  exif_imagetype($file);
		switch($type){
			case 2:
				$img = imagecreatefromjpeg($file);
				break;
			case 1:
				$img = imagecreatefromgif($file);
				break;
			case 3:
				$img = imagecreatefrompng($file);
				break;
			default:
				$img = false;
				break;
		}
		return $img;
	}
	
	function getDimensions($width, $height, $frameWidth, $frameHeight)
	{
		//scale the longer side first and the shorter side as per the ratio
		if($width > $height)
		{
			$newWidth = $frameWidth;
			$newHeight = $frameWidth/$width*$height;
		}else{
			$newHeight = $frameHeight;
			$newWidth = $frameHeight/$height*$width;
		}
		return array('scaledWidth' => $newWidth , 'scaledHeight' => $newHeight);
	}
	
	function saveImage($img, $finalDestination)
	{
		//get the filetype of the file to be saved to determine the format of the output image 		
		$type = strtolower(strrchr($finalDestination, '.'));
		switch($type)
		{
			case '.jpg':
			case '.jpeg':
				if (imagetypes() & IMG_JPG) {
					imagejpeg($img, $finalDestination, 100);
				}
				break;
			case '.gif':
				if (imagetypes() & IMG_GIF) {
					imagegif($img, $finalDestination, 100);
				}
				break;
			case '.png':
				if (imagetypes() & IMG_PNG) {
					imagepng($img, $finalDestination, 0);
				}
				break;
			default:
				break;
		}
		imagedestroy($img);
	}
	
	// --------------------------------------------------------------------
	
	function medlist_update()
	{
		$this->load->library('domparser');
		$product = '/var/www/nosh/import/drugs/Product.txt';
		$product_link = '/var/www/nosh/import/drugs/meds_full.txt';
		$package = '/var/www/nosh/import/drugs/package.txt';
		$package_link = '/var/www/nosh/import/drugs/meds_full_package.txt';
		if (file_exists($product)) {
			unlink ($product);
		}
		if (file_exists($package)) {
			unlink ($package);
		}
		$this->load->library('domparser');
		$html = $this->domparser->file_get_html('http://www.fda.gov/Drugs/InformationOnDrugs/ucm142438.htm#download');
		$e = $html->find('a[href*=zip]', 0);
		$link = $e->href;
		$wget = "wget http://www.fda.gov" . $link . " --directory-prefix='/var/www/nosh/import/'";
		$last_line = system($wget, $return_val);
		$zip = strrchr($link, '/');
		$unzip = "unzip /var/www/nosh/import" . $zip . " -d /var/www/nosh/import/drugs/";
		exec($unzip);
		unlink('/var/www/nosh/import/' . $zip);
		if (!file_exists($product_link)) {
			symlink($product, $product_link);
		}
		if (!file_exists($package_link)) {
			symlink($package, $package_link);
		}
		ini_set('memory_limit','196M');
		$this->db->empty_table('meds');
		include(APPPATH.'config/database'.EXT);
		$product_command = "mysqlimport -u " . $db['default']['username']. " -p". $db['default']['password'] . " --local --delete nosh " . $product_link;
		$package_command = "mysqlimport -u " . $db['default']['username']. " -p". $db['default']['password'] . " --local --delete nosh " . $package_link;
		shell_exec($product_command);
		shell_exec($package_command);
		sleep(5);
		$i = 0;
		foreach($this->db->get('meds_full_package')->result_array() as $package_row) {
			$this->db->where('PRODUCTNDC', $package_row['PRODUCTNDC']);
			$data = $this->db->get('meds_full')->row_array();
			$data1 = array(
				'meds_ndc' => $data['PRODUCTNDC'],
				'meds_dosage' => $data['ACTIVE_NUMERATOR_STRENGTH'],
				'meds_dosage_unit' => $data['ACTIVE_INGRED_UNIT'],
				'meds_medication' => $data['PROPRIETARYNAME'] . ", " . $data['DOSAGEFORMNAME'],
				'meds_ndcid' => $this->ndc_convert($package_row['NDCPACKAGECODE']),
				'meds_package' => $package_row['PACKAGEDESCRIPTION']
			);
			$this->db->insert('meds', $data1);
			$i++;
		}
		$arr['result'] = 'Medication list updated with ' . $i . ' records!';
		echo json_encode($arr);
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
	
	function icd_update()
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$this->db->truncate('icd9');
		$this->load->library('domparser');
		$year = date('Y');
		$link1 = array();
		$html = $this->domparser->file_get_html("http://www.icd9data.com/" . $year . "/Volume1/default.htm");
		// Level 1
		if (isset($html)) {
			$div = $html->find('[class=codeList]',0);
			if (isset($div)) {
				foreach ($div->find('li') as $li) {
					$a = $li->find('a',0);
					$link1[] = $a->href;
				}
			}
		}
		$link2 = array();
		foreach ($link1 as $page1) {
			$cr1 = curl_init("http://www.icd9data.com" . $page1);
			curl_setopt($cr1, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($cr1, CURLOPT_CONNECTTIMEOUT, 0);
			$data1 = curl_exec($cr1);
			curl_close($cr1);
			$dom1 = $this->domparser->str_get_html($data1);
			$div1 = $dom1->find('[class=codeList]',0);
			if (isset($div1)) {
				foreach ($div1->find('li') as $li1) {
					$a1 = $li1->find('a',0);
					$link2[] = $a1->href;
				}
			}
		}
		$link3 = array();
		$i = 0;
		foreach ($link2 as $page2) {
			$cr2 = curl_init("http://www.icd9data.com" . $page2);
			curl_setopt($cr2, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($cr2, CURLOPT_CONNECTTIMEOUT, 0);
			$data2 = curl_exec($cr2);
			curl_close($cr2);
			$dom2 = $this->domparser->str_get_html($data2);
			$div2 = $dom2->find('[class=codeList]',0);
			if (isset($div2)) {
				foreach ($div2->find('li') as $li2) {
					$a2 = $li2->find('a',0);
					$link3[] = $a2->href;
				}
			}
			$linecheck2 = $dom2->find('[class=localLine]',0);
			if (isset($linecheck2)) {
				$main_description0 = $linecheck2->find('[class=threeDigitCodeListDescription]',0);
				$main_description0_text = $main_description0->innertext;
				foreach ($dom2->find('[class=localLine]') as $line2) {
					$greencheck2 = $line2->find('img[src*=bullet_triangle_green.png]',0);
					if (isset($greencheck2)) {
						$icd9_0 = $line2->find('[class=identifier]',0);
						$data0['icd9'] = $icd9_0->innertext;
						$description0 = $line2->find('[class=threeDigitCodeListDescription]',0);
						$description0_text = $description0->innertext;
						$data0['icd9_description'] = str_replace("&#8230;", $main_description0_text, $description0_text);
						$data0['icd9_description'] = str_replace('[', '(', $data0['icd9_description']);
						$data0['icd9_description'] = str_replace(']', ')', $data0['icd9_description']);
						$this->db->insert('icd9', $data0);
						$i++;
					}
				}
			}
		}
		foreach ($link3 as $page3) {
			$cr3 = curl_init("http://www.icd9data.com" . $page3);
			curl_setopt($cr3, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($cr3, CURLOPT_CONNECTTIMEOUT, 0);
			$data3 = curl_exec($cr3);
			curl_close($cr3);
			$dom3 = $this->domparser->str_get_html($data3);
			$linecheck3 = $dom3->find('[class=localLine]',0);
			if (isset($linecheck3)) {
				$main_description = $linecheck3->find('[class=threeDigitCodeListDescription]',0);
				$main_description_text = $main_description->innertext;
				foreach ($dom3->find('[class=localLine]') as $line3) {
					$greencheck3 = $line3->find('img[src*=bullet_triangle_green.png]',0);
					if (isset($greencheck3)) {
						$icd9 = $line3->find('[class=identifier]',0);
						$data['icd9'] = $icd9->innertext;
						$description = $line3->find('[class=threeDigitCodeListDescription]',0);
						$description_text = $description->innertext;
						$data['icd9_description'] = str_replace("&#8230;", $main_description_text, $description_text);
						$data['icd9_description'] = str_replace('[', '(', $data['icd9_description']);
						$data['icd9_description'] = str_replace(']', ')', $data['icd9_description']);
						$this->db->insert('icd9', $data);
						$i++;
					}
				}
			}
		}
		$arr['result'] = 'ICD9 database updated with ' . $i . 'codes saved.<br>';
		echo json_encode($arr);
	}
	
	function cpt_list($mask='')
	{
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$sidx = $this->input->post('sidx');
		$sord = $this->input->post('sord');
		if($mask == ''){
			$query = $this->db->query("SELECT * FROM cpt");
		} else {
			$mask = "'%".$mask."%'";
			$query = $this->db->query("SELECT * FROM cpt WHERE cpt_description LIKE $mask OR cpt LIKE $mask");
		}
		$count = $query->num_rows(); 
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		if($mask == ''){
			$query1 = $this->db->query("SELECT * FROM cpt ORDER BY $sidx $sord LIMIT $start , $limit");
		} else {
			$query1 = $this->db->query("SELECT * FROM cpt WHERE cpt_description LIKE $mask OR cpt LIKE $mask ORDER BY $sidx $sord LIMIT $start , $limit");
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records = $query1->result_array();
		$response['rows'] = $records;
		echo json_encode($response);
		exit( 0 );
	}
	
	function edit_cpt_list()
	{
		$charge = str_replace("$", "", $this->input->post('cpt_charge'));
		$pos = strpos($charge, ".");
		if ($pos === FALSE) {
			$charge .= ".00";
		}
		$data = array(
			'cpt' => $this->input->post('cpt'),
			'cpt_description' => $this->input->post('cpt_description'),
			'cpt_charge' => $charge
		);
		
		$action = $this->input->post('oper');
		if ($action == 'edit') {
			$this->db->where('cpt_id', $this->input->post('id'));
			$this->db->update('cpt', $data);
		}
		if ($action == 'add') {
			$this->db->insert('cpt', $data);
		}
	}
	
	function delete_cpt()
	{
		$this->db->where('cpt_id', $this->input->post('id'));
		$this->db->delete('cpt');
		
	}
	
	function cpt_update()
	{
		$this->load->dbforge();
		ini_set('memory_limit','96M');
		$directory = '/var/www/nosh/import/';
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'csv|txt';
		$this->load->library('upload', $config);
		$field_name = "fileToUpload";
		$this->upload->do_upload($field_name);
		$pages_data1 = $this->upload->data();
		while(!file_exists($pages_data1['full_path'])) {
			sleep(2);
		}
		$this->dbforge->rename_table('cpt', 'cpt_copy');
		$cpt_definition = array(
			'cpt_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 255),
			'cpt_description' => array('type' => 'LONGTEXT'),
			'cpt_charge' => array('type' => 'VARCHAR', 'constraint' => 255),
			'cpt_common' => array('type' => 'TINYINT', 'constraint' => 4)
		);
		$this->dbforge->add_field($cpt_definition);
		$this->dbforge->add_key('cpt_id', TRUE);
		$this->dbforge->create_table('cpt', TRUE);
		$file_array = file($pages_data1['full_path']);
		foreach ($file_array as $key => $value) {
			if ($key > 32) {
				$pos = strpos($value, " ");
				$cpt_description = substr($value, $pos);
				$cpt = substr($value, 0, $pos);
				$data = array (
					'cpt' => $cpt,
					'cpt_description' => $cpt_description
				);
				$this->db->insert('cpt', $data);
			}
		}
		$cpt_copy_query = $this->db->query("SELECT * FROM cpt_copy WHERE cpt_charge IS NOT NULL");
		$cpt_arr = $cpt_copy_query->result_array();
		foreach ($cpt_arr as $cpt_row) {
			$this->db->where('cpt', $cpt_row['cpt']);
			$cpt_query = $this->db->get('cpt');
			if ($cpt_query->num_rows() > 0) {
				$cpt_arr1 = $cpt_query->row_array();
				$cpt_id = $cpt_arr1['cpt_id'];
				$cpt_arr2 = array (
					'cpt_charge' => $cpt_row['cpt_charge']
				);
				$this->db->where('cpt_id', $cpt_id);
				$this->db->update('cpt', $cpt_arr2);
			} else {
				$this->db->insert('cpt', $cpt_row);
			}
		}
		$this->dbforge->drop_table('cpt_copy');
		$arr['result'] = 'CPT database updated!';
		echo json_encode($arr);
	}
	
	function npi_update()
	{
		ini_set('memory_limit','96M');
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
		$npilist = file_get_contents($pages_data1['full_path']);
		$this->db->truncate('npi');
		$npilistarray = $this->csv->parse($npilist);
		foreach($npilistarray as $npi) {
			if ($npi[0] != '' AND $npi[0] != 'Code') {
				$data = array (
					'code' => $npi[0],
					'type' => $npi[1],
					'classification' => $npi[2],
					'specialization' => $npi[3],
				);
				$this->db->insert('npi', $data);
			}
		}
		echo json_encode('NPI taxonomy database updated!');
	}
	
	function cvx_update()
	{
		$this->db->truncate('cvx');
		$xml = simplexml_load_file('http://www2a.cdc.gov/vaccines/iis/iisstandards/XML.asp?rpt=cvx');
		foreach ($xml->CVXInfo as $cvx) {
			$data2 = array(
				'cvx_code' => (string) $cvx->Value[2],
				'description' => ucfirst((string) $cvx->Value[0]),
				'vaccine_name' => ucfirst((string) $cvx->Value[1])
			);
			$this->db->insert('cvx', $data2);
		}
		echo json_encode('CVX immunization database updated!');
	}
	
	function supplements_update()
	{
		$this->db->empty_table('supplements_list');
		$html = $this->domparser->file_get_html("http://www.nlm.nih.gov/medlineplus/druginfo/herb_All.html");
		if (isset($html)) {
			foreach ($html->find('[class=herbul]') as $div) {
				foreach ($div->find('li') as $li) {
					$a = $li->find('a',0);
					$data['supplement_name'] = $a->innertext;
					$this->db->where('supplement_name', $data['supplement_name']);
					$count = $this->db->get('supplements_list')->num_rows();
					if ($count == '0') {
						$this->db->insert('supplements_list', $data);
					}
				}
			}
		}
		echo json_encode('Supplements list from the U.S. National Library of Medicine updated!');
	}
	
	function system_test()
	{
		$this->load->dbforge();
		include(APPPATH.'config/database'.EXT);
		$this->dbforge->rename_table('cpt', 'cpt_copy');
		$cpt_file = "/var/www/nosh/import/cpt.sql";
		$cpt_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $cpt_file;
		system($cpt_command);
		$cpt_copy_query = $this->db->query("SELECT * FROM cpt_copy WHERE cpt_charge IS NOT NULL");
		$cpt_arr = $cpt_copy_query->result_array();
		foreach ($cpt_arr as $cpt_row) {
			$this->db->where('cpt', $cpt_row['cpt']);
			$cpt_query = $this->db->get('cpt');
			if ($cpt_query->num_rows() > 0) {
				$cpt_arr1 = $cpt_query->row_array();
				$cpt_id = $cpt_arr1['cpt_id'];
				$cpt_arr2 = array (
					'cpt_charge' => $cpt_row['cpt_charge']
				);
				$this->db->where('cpt_id', $cpt_id);
				$this->db->update('cpt', $cpt_arr2);
			} else {
				$this->db->insert('cpt', $cpt_row);
			}
		}
		$this->dbforge->drop_table('cpt_copy');
	}
	
	function icd_update_test()
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		//$this->db->empty_table('icd9');
		$this->load->library('domparser');
		$year = date('Y');
		$link1 = array();
		$html = $this->domparser->file_get_html("http://www.icd9data.com/" . $year . "/Volume1/default.htm");
		// Level 1
		if (isset($html)) {
			$div = $html->find('[class=codeList]',0);
			if (isset($div)) {
				foreach ($div->find('li') as $li) {
					$a = $li->find('a',0);
					$link1[] = $a->href;
				}
			}
		}
		$link2 = array();
		foreach ($link1 as $page1) {
			$cr1 = curl_init("http://www.icd9data.com" . $page1);
			curl_setopt($cr1, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($cr1, CURLOPT_CONNECTTIMEOUT, 0);
			$data1 = curl_exec($cr1);
			curl_close($cr1);
			$dom1 = $this->domparser->str_get_html($data1);
			$div1 = $dom1->find('[class=codeList]',0);
			if (isset($div1)) {
				foreach ($div1->find('li') as $li1) {
					$a1 = $li1->find('a',0);
					$link2[] = $a1->href;
				}
			}
		}
		$link3 = "";
		foreach ($link2 as $page2) {
			$cr2 = curl_init("http://www.icd9data.com" . $page2);
			curl_setopt($cr2, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($cr2, CURLOPT_CONNECTTIMEOUT, 0);
			$data2 = curl_exec($cr2);
			curl_close($cr2);
			$dom2 = $this->domparser->str_get_html($data2);
			$div2 = $dom2->find('[class=codeList]',0);
			if (isset($div2)) {
				foreach ($div2->find('li') as $li2) {
					$a2 = $li2->find('a',0);
					$link3 .= $a2->href . "<br>";
				}
			}
		}
		echo $link3;
	}
}
/* End of file: setup.php */
/* Location: application/controllers/admin/setup.php */
