<?php

class Reminder extends Application
{

	function Reminder()
	{
		parent::Application();
		$this->load->database();
		$this->load->library('session');
		$this->load->library('email');
		$this->load->model('demographics_model');
		$this->load->model('practiceinfo_model');
		$this->load->model('messaging_model');
		$this->load->model('chart_model');
		$this->load->model('audit_model');
	}

	// --------------------------------------------------------------------

	function index()
	{
		$start = time();
		$end = time() + (2 * 24 * 60 * 60);
		$query1 = $this->db->query("SELECT demographics.reminder_to, demographics.reminder_method, schedule.appt_id, schedule.provider_id, schedule.start FROM schedule JOIN demographics ON schedule.pid=demographics.pid WHERE schedule.status='Pending' AND schedule.start BETWEEN $start AND $end");
		$j=0;
		$i=0;
		if ($query1->num_rows() > 0) {
			foreach ($query1->result_array() as $row) {
				$to = $row['reminder_to'];
				if ($to != '') {
					$id = $row['provider_id'];
					$appt_id = $row['appt_id'];
					$startdate = date("F j, Y, g:i a", $row['start']);
					$query0 = $this->db->query("SELECT displayname FROM users WHERE id=$id");
					foreach ($query0->result_array() as $row0) {
						$displayname = $row0['displayname'];
						$query2 = $this->db->query("SELECT * FROM practiceinfo WHERE practice_id=1");
						foreach ($query2->result_array() as $row2) {
							$phone = $row2['phone'];
							if ($row['reminder_method'] == 'Cellular Phone') {
								$message = 'Reminder - medical appt with ' . $displayname . ' on ' . $startdate . '.';
								$message .= ' To cancel/reschedule, call ' . $phone . '.';
							} else {
								$message = 'This message is a courtesy reminder of your medical appointment with ' . $displayname . ' on ' . $startdate . '.';
								$message .= ' If you need to cancel or reschedule your appointment, please contact us at ' . $phone . ' or reply to this e-mail at ' . $row2['email'] . '.';
								$message .= $row2['additional_message'];
							}
							$config['protocol']='smtp';
							$config['smtp_host']='ssl://smtp.googlemail.com';
							$config['smtp_port']='465';
							$config['smtp_timeout']='30';
							$config['smtp_user']=$row2['smtp_user'];
							$config['smtp_pass']=$row2['smtp_pass'];
							$config['charset']='utf-8';
							$config['newline']="\r\n";
							$this->email->initialize($config);
							$this->email->from($row2['email'], $row2['practice_name']);
							$this->email->to($to);
							$this->email->subject('Appointment Reminder');
							$this->email->message($message);
							if ($this->email->send()) {
								$data = array(
									'status' => 'Reminder Sent'
								);
								$this->db->where('appt_id', $appt_id);
								$this->db->update('schedule', $data);
							} else {
								$error = $this->email->print_debugger();
								echo $error;
							}
						}
					}
					$i++;
				}
				$j++;
			}
		}
		$updox = $this->check_extension('updox_extension');
		if ($updox) {
			$this->updox_sync();
		}
		$rcopia = $this->check_extension('rcopia_extension');
		if ($rcopia) {
			$this->rcopia_sync();
		}
		$arr = "Number of appointments: " . $j . "<br>";
		$arr .= "Number of appointment reminders sent: " . $i;
		echo $arr;
		exit (0);
	}
	
	function check_extension($extension)
	{
		$result = $this->db->get('practiceinfo')->row_array();
		if ($result[$extension] == 'y') {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function updox_sync()
	{
		$result = $this->practiceinfo_model->get()->row_array();
		$dir = $result['documents_dir'] . 'updox/';
		$files = scandir($dir);
		$count = count($files);
		for ($i = 2; $i < $count; $i++) {
			$line = $files[$i];
			$trim_line = str_replace(".pdf", "", $line);
			$line_parts = explode("_", $trim_line);
			$year = substr($line_parts[5], 0, 4);
			$month = substr($line_parts[5], 4, 2);
			$day = substr($line_parts[5], 6, 2);
			$dob = $year . "-" . $month . "-" . $day . " 00:00:00";
			$this->db->where('DOB', $dob);
			$this->db->where('lastname', $line_parts[1]);
			$this->db->where('firstname', $line_parts[0]);
			$patient_query = $this->db->get('demographics');
			if ($patient_query->num_rows() > 0) {
				$patient_result = $patient_query->row_array();
				$original_file = $dir . $line;
				$new_file = $result['documents_dir'] . $patient_result['pid'] . "/" . $trim_line . '_' . now() . '.pdf';
				rename($original_file, $new_file);
				$from_pos = strpos($line_parts[3], " from ");
				if ($from_pos === FALSE) {
					$documents_desc = $line_parts[3];
					$documents_from = "Unknown";
				} else {
					$desc_parts = explode(" from ", $line_parts[3]);
					$documents_desc = $desc_parts[0];
					$documents_from = $desc_parts[1];
				}
				$pages_data = array(
					'documents_url' => $new_file,
					'pid' => $patient_result['pid'],
					'documents_type' => $line_parts[2],
					'documents_desc' => $documents_desc,
					'documents_from' => $documents_from,
					'documents_date' => $line_parts[4]
				);
				$documents_id = $this->chart_model->addDocuments($pages_data);	
				$this->audit_model->add();
			} else {
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
		echo $count;
	}
	
	function rcopia_sync()
	{
		// Update Notification
		$row0 = $this->db->get('practiceinfo')->row_array();
		if ($row0['rcopia_update_notification_lastupdate'] == "") {
			$datestring = "%m/%d/%Y %H:%i:%s";
			$date0 = mdate($datestring, now());
		} else {
			$date0 = $row0['rcopia_update_notification_lastupdate'];
		}
		$xml0 = "<Request><Command>update_notification</Command>";
		$xml0 .= "<LastUpdateDate>" . $date0 . "</LastUpdateDate>";
		$xml0 .= "</Request></RCExtRequest>";
		$result0 = $this->rcopia($xml0);
		$response0 = new SimpleXMLElement($result0);
		if ($response0->Response->Status == "error") {
			$description0 = $response0->Response->Error->Text . "";
			$data0a = array(
				'action' => 'update_notification',
				'pid' => '0',
				'extensions_name' => 'rcopia',
				'description' => $description0
			);
			$this->db->insert('extensions_log', $data0a);
		} else {
			$last_update_date = $response0->Response->LastUpdateDate . "";
			$number = $response0->Response->NotificationList->Number . "";
			if ($number != "0") {
				foreach ($response0->Response->NotificationList->Notification as $notification) {
					$type = $notification->Type . "";
					$status = $notification->Status . "";
					$rcopia_username = $notification->Provider->Username . "";
					$medication_message = $notification->Sig->Drug->BrandName . "";
					$form_message = $notification->Sig->Drug->Form . "";
					$dose_message = $notification->Sig->Drug->Strength . "";
					$sig_message = $notification->Sig->Dose . "";
					$sig1_message = $notification->Sig->DoseUnit . "";
					$route_message = $notification->Sig->Route . "";
					$frequency_message = $notification->Sig->DoseTiming . "";
					$instructions_message = $notification->Sig->DoseOther . "";
					$quantity_message = $notification->Sig->Quantity . "";
					$quantity_message1 = $notification->Sig->QuantityUnit . "";
					$refill_message = $notification->Sig->Refills . "";
					$pharmacy_message = $notification->Pharmacy->Name . "";
					$medication_message = "Medication: " . $medication_message . ", " . $form_message . ", " . $dose_message;
					$medication_message .= "\nInstructions: " . $sig_message . " " . $sig1_message . " " . $route_message . ", " . $frequency_message;
					$medication_message .= "\nOther Instructions: " . $instructions_message;
					$medication_message .= "\nQuantity: " . $quantity_message . " " . $quantity_message1;
					$medication_message .= "\nRefills: " . $refill_message;
					$medication_message .= "\nPharmacy: " . $pharmacy_message;
					$messages_pid = $notification->Patient->ExternalID . "";
					$sender = $notification->Sender . "";
					$title = $notification->Title . "";
					$text = $notification->Text . "";
					$full_text = "From: " . $sender . "\nMessage: " . $text;
					$this->db->select('firstname, lastname, DOB');
					$this->db->where('pid', $messages_pid);
					$patient_row = $this->db->get('demographics')->row_array();
					$dob_message = mdate("%m/%d/%Y", strtotime($patient_row['DOB']));
					$patient_name =  $patient_row['lastname'] . ', ' . $patient_row['firstname'] . ' (DOB: ' . $dob_message . ') (ID: ' . $messages_pid . ')';
					$this->db->select('users.lastname, users.firstname, users.title, users.id');
					$this->db->from('users');
					$this->db->join('providers', 'providers.id=users.id');
					$this->db->where('providers.rcopia_username', $rcopia_username);
					$provider_query = $this->db->get();
					if ($provider_query->num_rows() > 0) {
						$provider_row = $provider_query->row_array();
						$provider_name = $provider_row['firstname'] . " " . $provider_row['lastname'] . ", " . $provider_row['title'] . " (" . $provider_row['id'] . ")";
						if ($type == "refill") {
							$subject = "Refill Request for " . $patient_name;
							$body = $medication_message;
						}
						if ($type == "message") {
							$subject = $title;
							$body = $full_text;
						}
						$data_message = array(
							'pid' => $messages_pid,
							'message_to' => $provider_name,
							'message_from' => $provider_row['id'],
							'subject' => $subject,
							'body' => $body,
							'patient_name' => $patient_name,
							'status' => 'Sent',
							'mailbox' => $provider_row['id']
						);
						$this->db->insert('messages', $data_message);
					}
				}
			}
			$data_update = array(
				'rcopia_update_notification_lastupdate' => $last_update_date
			);
			$this->db->where('practice_id', '1');
			$this->db->update('practiceinfo', $data_update);
		}
		
		// Send Patient
		$this->db->where('rcopia_sync', 'n');
		$query1 = $this->db->get('demographics');
		if ($query1->num_rows() > 0) {
			foreach ($query1->result_array() as $row1) {
				$dob = explode(" ", $row1['DOB']);
				$dob1 = explode("-", $dob[0]);
				$dob_final = $dob1[1] . "/" . $dob1[2] . "/" . $dob1[0];
				$xml1 = "<Request><Command>send_patient</Command><Synchronous>y</Synchronous><CheckEligibility>y</CheckEligibility>";
				$xml1 .= "<PatientList><Patient>";
				$xml1 .= "<FirstName>" . $row1['firstname'] . "</FirstName>";
				$xml1 .= "<LastName>" . $row1['lastname'] . "</LastName>";
				$xml1 .= "<MiddleName>" . $row1['middle'] . "</MiddleName>";
				$xml1 .= "<DOB>" . $dob_final . "</DOB>";
				$xml1 .= "<Sex>". $row1['sex'] . "</Sex>";
				$xml1 .= "<ExternalID>" . $row1['pid'] . "</ExternalID>";
				$xml1 .= "<HomePhone>" . $row1['phone_home'] . "</HomePhone>";
				$xml1 .= "<WorkPhone>" . $row1['phone_work'] . "</WorkPhone>";
				$xml1 .= "<Address1>" . $row1['address'] . "</Address1>";
				$xml1 .= "<Address2></Address2>";
				$xml1 .= "<City>" . $row1['city'] . "</City>";
				$xml1 .= "<State>" . $row1['state'] . "</State>";
				$xml1 .= "<Zip>" . $row1['zip'] . "</Zip>";
				$xml1 .= "</Patient></PatientList></Request></RCExtRequest>";
				$result1 = $this->rcopia($xml1);
				$response1 = new SimpleXMLElement($result1);
				$status1 = $response1->Response->PatientList->Patient->Status . "";
				if ($status1 == "error") {
					$description1 = $response1->Response->PatientList->Patient->Error->Text . "";
					$data1a = array(
						'action' => 'send_patient',
						'pid' => $row1['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description1
					);
					$this->db->insert('extensions_log', $data1a);
				} else {
					$data1b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row1['pid']);
					$this->db->update('demographics',$data1b);
					$this->audit_model->update();
				}
			}
		}
		
		// Send Allergy
		$this->db->where('rcopia_sync', 'n');
		$this->db->where('allergies_date_inactive','0000-00-00 00:00:00');
		$query2 = $this->db->get('allergies');
		if ($query2->num_rows() > 0) {
			foreach ($query2->result_array() as $row2) {
				$da = explode(" ", $row2['allergies_date_active']);
				$da1 = explode("-", $da[0]);
				$da_final = $da1[1] . "/" . $da1[2] . "/" . $da1[0];
				$xml2 = "<Request><Command>send_allergy</Command><Synchronous>y</Synchronous>";
				$xml2 .= "<AllergyList><Allergy>";
				$xml2 .= "<ExternalID>" . $row2['allergies_id'] . "</ExternalID>";
				$xml2 .= "<Patient><ExternalID>" . $row2['pid'] . "</ExternalID></Patient>";
				$xml2 .= "<Allergen><Name>" . $row2['allergies_med'] . "</Name>";
				$xml2 .= "<Drug><NDCID>" . $row2['meds_ndcid'] . "</NDCID></Drug></Allergen>";
				$xml2 .= "<Reaction>" . $row2['allergies_reaction'] . "</Reaction>";
				$xml2 .= "<OnsetDate>" . $da_final . "</OnsetDate>";
				$xml2 .= "</Allergy></AllergyList></Request></RCExtRequest>";
				$result2 = $this->rcopia($xml2);
				$response2 = new SimpleXMLElement($result2);
				$status2 = $response2->Response->AllergyList->Allergy->Status . "";
				if ($status2 == "error") {
					$description2 = $response2->Response->AllergyList->Allergy->Error->Text . "";
					$data2a = array(
						'action' => 'send_allergy',
						'pid' => $row2['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description2
					);
					$this->db->insert('extensions_log', $data2a);
					if ($description2 == "Can find neither name, Rcopia ID, or NDC ID for drug.") {
						$data2c['rcopia_sync'] = 'ye';
						$this->db->where('pid',$row2['pid']);
						$this->db->update('allergies',$data2c);
						$this->audit_model->update();
					}
				} else {
					$data2b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row2['pid']);
					$this->db->update('allergies',$data2b);
					$this->audit_model->update();
				}
			}
		}
		
		//Send Medication
		$this->db->where('rcopia_sync', 'n');
		$this->db->where('rxl_date_inactive','0000-00-00 00:00:00');
		$this->db->where('rxl_date_old','0000-00-00 00:00:00');
		$query3 = $this->db->get('rx_list');
		if ($query3->num_rows() > 0) {
			foreach ($query3->result_array() as $row3) {
				$dm = explode(" ", $row3['rxl_date_active']);
				$dm1 = explode("-", $dm[0]);
				$dm_final = $dm1[1] . "/" . $dm1[2] . "/" . $dm1[0];
				if ($row3['rxl_due_date'] != '') {
					$dn = explode(" ", $row3['rxl_due_date']);
					$dn1 = explode("-", $dn[0]);
					$dn_final = $dn1[1] . "/" . $dn1[2] . "/" . $dn1[0];
				} else {
					$dn_final = "";
				}
				if ($row3['rxl_ndcid'] != '') {
					$ndcid = $row3['rxl_ndcid'];
					$generic_name = '';
					$form = '';
					$strength = '';
				} else {
					$ndcid = '';
					$medication_parts1 = explode(", ", $row3['rxl_medication']);
					$generic_name = $medication_parts1[0];
					$form = $medication_parts1[1];
					$strength = $row3['rxl_dosage'] . " " . $row3['rxl_dosage_unit'];
				}
				$sig_parts1 = explode(" ", $row3['rxl_sig']);
				if ($row3['rxl_quantity'] != '') {
					$quantity_parts1 =explode(" ", $row3['rxl_quantity']);
					$quantity = $quantity_parts1[0];
					$quantity_unit = $quantity_parts1[1];
				} else {
					$quantity = '';
					$quantity_unit = '';
				}
				if ($row3['rxl_daw'] != '') {
					$daw = 'n';
				} else {
					$daw = 'y';
				}
				$xml3 = "<Request><Command>send_medication</Command><Synchronous>y</Synchronous>";
				$xml3 .= "<MedicationList><Medication>";
				$xml3 .= "<ExternalID>" . $row3['rxl_id'] . "</ExternalID>";
				$xml3 .= "<Patient><ExternalID>" . $row3['pid'] . "</ExternalID></Patient>";
				$xml3 .= "<Sig>";
				$xml3 .= "<Drug><NDCID>" . $ndcid . "</NDCID>";
				$xml3 .= "<GenericName>" . $generic_name . "</GenericName>";
				$xml3 .= "<Form>" . $form . "</Form>";
				$xml3 .= "<Strength>" . $strength . "</Strength></Drug>";
				$xml3 .= "<Dose>" . $sig_parts1[0] . "</Dose>";
				$xml3 .= "<DoseUnit>" . $sig_parts1[1] . "</DoseUnit>";
				$xml3 .= "<Route>" . $row3['rxl_route'] . "</Route>";
				$xml3 .= "<DoseTiming>" . $row3['rxl_frequency'] . "</DoseTiming>";
				$xml3 .= "<DoseOther>" . $row3['rxl_instructions'] . "</DoseOther>";
				$xml3 .= "<Quantity>" . $quantity . "</Quantity>";
				$xml3 .= "<QuantityUnit>" . $quantity_unit . "</QuantityUnit>";
				$xml3 .= "<Refills>" . $row3['rxl_refill'] . "</Refills>";
				$xml3 .= "<SubstitutionPermitted>" . $daw . "</SubstitutionPermitted>";
				$xml3 .= "</Sig>";
				$xml3 .= "<StartDate>" . $dm_final . "</StartDate>";
				$xml3 .= "<StopDate>" . $dn_final . "</StopDate>";
				$xml3 .= "</Medication></MedicationList></Request></RCExtRequest>";
				$result3 = $this->rcopia($xml3);
				$response3 = new SimpleXMLElement($result3);
				$status3 = $response3->Response->MedicationList->Medication->Status . "";
				if ($status3 == "error") {
					$description3 = $response3->Response->MedicationList->Medication->Error->Text . "";
					$data3a = array(
						'action' => 'send_medication',
						'pid' => $row3['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description3
					);
					$this->db->insert('extensions_log', $data3a);
				} else {
					$data3b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row3['pid']);
					$this->db->update('rx_list',$data3b);
					$this->audit_model->update();
				}
			}
		}
		
		//Send Problem List
		$this->db->where('rcopia_sync', 'n');
		$this->db->where('issue_date_inactive','0000-00-00 00:00:00');
		$query4 = $this->db->get('issues');
		if ($query4->num_rows() > 0) {
			foreach ($query4->result_array() as $row4) {
				$di = explode(" [", $row4['issue']);
				$code = str_replace("]", "", $di[1]);
				$xml4 = "<Request><Command>send_problem</Command><Synchronous>y</Synchronous>";
				$xml4 .= "<ProblemList><Problem>";
				$xml4 .= "<ExternalID>" . $row4['issue_id'] . "</ExternalID>";
				$xml4 .= "<Patient><ExternalID>" . $row4['pid'] . "</ExternalID></Patient>";
				$xml4 .= "<Code>" . $code . "</Code>";
				$xml4 .= "<Description>" . $di[0] . "</Description>";
				$xml4 .= "</Problem></ProblemList></Request></RCExtRequest>";
				$result4 = $this->rcopia($xml4);
				$response4 = new SimpleXMLElement($result4);
				$status4 = $response4->Response->ProblemList->Problem->Status . "";
				if ($status4 == "error") {
					$description4 = $response4->Response->ProblemList->Problem->Error->Text . "";
					$data4a = array(
						'action' => 'send_problem',
						'pid' => $row4['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description4
					);
					$this->db->insert('extensions_log', $data4a);
				} else {
					$data4b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row4['pid']);
					$this->db->update('allergies',$data4b);
					$this->audit_model->update();
				}
			}
		}
		
		//Delete Allergy
		$this->db->where('rcopia_sync', 'nd');
		$this->db->or_where('rcopia_sync', 'nd1');
		$query5 = $this->db->get('allergies');
		if ($query5->num_rows() > 0) {
			foreach ($query5->result_array() as $row5) {
				$dda = explode(" ", $row5['allergies_date_active']);
				$daa1 = explode("-", $dda[0]);
				$dda_final = $dda1[1] . "/" . $dda1[2] . "/" . $dda1[0];
				$xml5 = "<Request><Command>send_allergy</Command><Synchronous>y</Synchronous>";
				$xml5 .= "<AllergyList><Allergy><Deleted>y</Deleted>";
				$xml5 .= "<ExternalID>" . $row5['allergies_id'] . "</ExternalID>";
				$xml5 .= "<Patient><ExternalID>" . $row5['pid'] . "</ExternalID></Patient>";
				$xml5 .= "<Allergen><Name>" . $row5['allergies_med'] . "</Name>";
				$xml5 .= "<Drug><NDCID>" . $row5['meds_ndcid'] . "</NDCID></Drug></Allergen>";
				$xml5 .= "<Reaction>" . $row5['allergies_reaction'] . "</Reaction>";
				$xml5 .= "<OnsetDate>" . $dda_final . "</OnsetDate>";
				$xml5 .= "</Allergy></AllergyList></Request></RCExtRequest>";
				$result5 = $this->rcopia($xml5);
				$response5 = new SimpleXMLElement($result5);
				$status5 = $response5->Response->AllergyList->Allergy->Status . "";
				if ($status5 == "error") {
					$description5 = $response5->Response->AllergyList->Allergy->Error->Text . "";
					$data5a = array(
						'action' => 'delete_allergy',
						'pid' => $row5['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description5
					);
					$this->db->insert('extensions_log', $data5a);
					$data5b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row5['pid']);
					$this->db->update('allergies',$data5b);
					$this->audit_model->update();
				} else {
					$data5b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row5['pid']);
					$this->db->update('allergies',$data5b);
					$this->audit_model->update();
				}
			}
		}
		
		//Delete Medication
		$this->db->where('rcopia_sync', 'nd');
		$this->db->or_where('rcopia_sync', 'nd1');
		$query6 = $this->db->get('rx_list');
		if ($query6->num_rows() > 0) {
			foreach ($query6->result_array() as $row6) {
				$ddm = explode(" ", $row6['rxl_date_active']);
				$ddm1 = explode("-", $ddm[0]);
				$ddm_final = $ddm1[1] . "/" . $ddm1[2] . "/" . $ddm1[0];
				if ($row3['rxl_due_date'] != '') {
					$ddn = explode(" ", $row6['rxl_due_date']);
					$ddn1 = explode("-", $ddn[0]);
					$ddn_final = $ddn1[1] . "/" . $ddn1[2] . "/" . $ddn1[0];
				} else {
					$ddn_final = "";
				}
				if ($row6['rxl_ndcid'] != '') {
					$ndcid1 = $row6['rxl_ndcid'];
					$generic_name1 = '';
					$form1 = '';
					$strength1 = '';
				} else {
					$ndcid1 = '';
					$medication_parts2 = explode(", ", $row6['rxl_medication']);
					$generic_name1 = $medication_parts2[0];
					$form1 = $medication_parts2[1];
					$strength1 = $row6['rxl_dosage'] . " " . $row6['rxl_dosage_unit'];
				}
				$sig_parts2 = explode(" ", $row6['rxl_sig']);
				if ($row6['rxl_quantity'] != '') {
					$quantity_parts2 = explode(" ", $row6['rxl_quantity']);
					$quantity1 = $quantity_parts2[0];
					$quantity_unit1 = $quantity_parts2[1];
				} else {
					$quantity1 = '';
					$quantity_unit1 = '';
				}
				if ($row6['rxl_daw'] != '') {
					$daw1 = 'n';
				} else {
					$daw1 = 'y';
				}
				$xml6 = "<Request><Command>send_medication</Command><Synchronous>y</Synchronous>";
				$xml6 .= "<MedicationList><Medication><Deleted>y</Deleted>";
				$xml6 .= "<ExternalID>" . $row6['rxl_id'] . "</ExternalID>";
				$xml6 .= "<Patient><ExternalID>" . $row6['pid'] . "</ExternalID></Patient>";
				$xml6 .= "<Sig>";
				$xml6 .= "<Drug><NDCID>" . $ndcid1 . "</NDCID>";
				$xml6 .= "<GenericName>" . $generic_name1 . "</GenericName>";
				$xml6 .= "<Form>" . $form1 . "</Form>";
				$xml6 .= "<Strength>" . $strength1 . "</Strength></Drug>";
				$xml6 .= "<Dose>" . $sig_parts2[0] . "</Dose>";
				$xml6 .= "<DoseUnit>" . $sig_parts2[1] . "</DoseUnit>";
				$xml6 .= "<Route>" . $row6['rxl_route'] . "</Route>";
				$xml6 .= "<DoseTiming>" . $row6['rxl_frequency'] . "</DoseTiming>";
				$xml6 .= "<DoseOther>" . $row6['rxl_instructions'] . "</DoseOther>";
				$xml6 .= "<Quantity>" . $quantity1 . "</Quantity>";
				$xml6 .= "<QuantityUnit>" . $quantity_unit1 . "</QuantityUnit>";
				$xml6 .= "<Refills>" . $row6['rxl_refill'] . "</Refills>";
				$xml6 .= "<SubstitutionPermitted>" . $daw2 . "</SubstitutionPermitted>";
				$xml6 .= "</Sig>";
				$xml6 .= "<StartDate>" . $ddm_final . "</StartDate>";
				$xml6 .= "<StopDate>" . $ddn_final . "</StopDate>";
				$xml6 .= "</Medication></MedicationList></Request></RCExtRequest>";
				$result6 = $this->rcopia($xml6);
				$response6 = new SimpleXMLElement($result6);
				$status6 = $response6->Response->MedicationList->Medication->Status . "";
				if ($status6 == "error") {
					$description6 = $response3->Response->MedicationList->Medication->Error->Text . "";
					$data6a = array(
						'action' => 'delete_medication',
						'pid' => $row6['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description6
					);
					$this->db->insert('extensions_log', $data6a);
					$data6b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row6['pid']);
					$this->db->update('rx_list',$data6b);
					$this->audit_model->update();
				} else {
					$data6b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row6['pid']);
					$this->db->update('rx_list',$data6b);
					$this->audit_model->update();
				}
			}
		}
		
		//Delete Problem List
		$this->db->where('rcopia_sync', 'nd');
		$this->db->or_where('rcopia_sync', 'nd1');
		$query7 = $this->db->get('issues');
		if ($query7->num_rows() > 0) {
			foreach ($query7->result_array() as $row7) {
				$ddi = explode(" [", $row7['issue']);
				$code1 = str_replace("]", "", $ddi[1]);
				$xml7 = "<Request><Command>send_problem</Command><Synchronous>y</Synchronous>";
				$xml7 .= "<ProblemList><Problem><Deleted>y</Deleted>";
				$xml7 .= "<ExternalID>" . $row7['issue_id'] . "</ExternalID>";
				$xml7 .= "<Patient><ExternalID>" . $row7['pid'] . "</ExternalID></Patient>";
				$xml7 .= "<Code>" . $code1 . "</Code>";
				$xml7 .= "<Description>" . $ddi[0] . "</Description>";
				$xml7 .= "</Problem></ProblemList></Request></RCExtRequest>";
				$result7 = $this->rcopia($xml7);
				$response7 = new SimpleXMLElement($result7);
				$status7 = $response7->Response->ProblemList->Problem->Status . "";
				if ($status7 == "error") {
					$description7 = $response7->Response->ProblemList->Problem->Error->Text . "";
					$data7a = array(
						'action' => 'delete_problem',
						'pid' => $row7['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description7
					);
					$this->db->insert('extensions_log', $data7a);
					$data7b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row7['pid']);
					$this->db->update('issues',$data7b);
					$this->audit_model->update();
				} else {
					$data7b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row7['pid']);
					$this->db->update('issues',$data7b);
					$this->audit_model->update();
				}
			}
		}
		
	}
	
	function rcopia($command)
	{
		$practice = $this->practiceinfo_model->get()->row_array();
		$apiVendor = $practice['rcopia_apiVendor'];
		$apiPractice = $practice['rcopia_apiPractice'];
		$apiPass = $practice['rcopia_apiPass'];
		$apiSystem = $practice['rcopia_apiSystem'];
		$url = 'https://update.drfirst.com/servlet/rcopia.servlet.EngineServlet?';
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><RCExtRequest version = \"2.08\">";
		$xml .= "<Caller><VendorName>" . $apiVendor . "</VendorName><VendorPassword>" . $apiPass . "</VendorPassword></Caller>";
		$xml .= "<SystemName>" . $apiSystem . "</SystemName>";
		$xml .= "<RcopiaPracticeUsername>" . $apiPractice . "</RcopiaPracticeUsername>";
		$xml .= $command;
		$fields = array(
			'xml' => urlencode($xml)
		);
		$fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$headers = array(
			"Content-type: application/xml"
		);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch,CURLOPT_FAILONERROR,1);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, 15);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	function rcopia_check()
	{
		$date = now();
		$datestring = "%m/%d/%Y %H:%i:%s";
		$date1 = mdate($datestring, $date);
		$row0 = $this->db->get('practiceinfo')->row_array();
		$xml0 = "<Request><Command>update_notification</Command>";
		$xml0 .= "<LastUpdateDate>" . $row0['rcopia_update_notification_lastupdate'] . "</LastUpdateDate>";
		$xml0 .= "</Request></RCExtRequest>";
		$result0 = $this->rcopia($xml0);
		echo $result0;
	}
}
/* End of file: reminder.php */
/* Location: application/controllers/reminder.php */
