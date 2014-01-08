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
		$results_scan=0;
		if ($query1->num_rows() > 0) {
			foreach ($query1->result_array() as $row) {
				$to = $row['reminder_to'];
				if ($to != '') {
					$id = $row['provider_id'];
					$appt_id = $row['appt_id'];
					$startdate = date("F j, Y, g:i a", $row['start']);
					$query0 = $this->db->query("SELECT * FROM users WHERE id=$id");
					foreach ($query0->result_array() as $row0) {
						$displayname = $row0['displayname'];
						$practice_id = $row0['practice_id'];
						$query2 = $this->db->query("SELECT * FROM practiceinfo WHERE practice_id=$practice_id");
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
		$query3 = $this->db->query("SELECT * FROM practiceinfo");
		foreach ($query3->result_array() as $practice_row) {
			$updox = $this->check_extension('updox_extension', $practice_row['practice_id']);
			if ($updox) {
				$this->updox_sync($practice_row['practice_id']);
			}
			$rcopia = $this->check_extension('rcopia_extension', $practice_row['practice_id']);
			if ($rcopia) {
				$this->rcopia_sync($practice_row['practice_id']);
			}
			$results_scan = $this->get_scans($practice_row['practice_id']);
		}
		$results_count = $this->get_results();
		$arr = "Number of appointments: " . $j . "<br>";
		$arr .= "Number of appointment reminders sent: " . $i . "<br>";
		$arr .= "Number of results obtained: " . $results_count . "<br>";
		$arr .= "Number of documents scanned: " . $results_scan . "<br>";
		echo $arr;
		exit (0);
	}
	
	function check_extension($extension, $practice_id)
	{
		$this->db->where('practice_id', $practice_id);
		$result = $this->db->get('practiceinfo')->row_array();
		if ($result[$extension] == 'y') {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function updox_sync($practice_id)
	{
		$result = $this->practiceinfo_model->get($practice_id)->row_array();
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
	}
	
	function rcopia_sync($practice_id)
	{
		// Update Notification
		$this->db->where('practice_id', $practice_id);
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
		$result0 = $this->rcopia($xml0, $practice_id);
		$response0 = new SimpleXMLElement($result0);
		if ($response0->Response->Status == "error") {
			$description0 = $response0->Response->Error->Text . "";
			$data0a = array(
				'action' => 'update_notification',
				'pid' => '0',
				'extensions_name' => 'rcopia',
				'description' => $description0,
				'practice_id' => $practice_id
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
							'mailbox' => $provider_row['id'],
							'practice_id' => $practice_id
						);
						$this->db->insert('messaging', $data_message);
					}
				}
			}
			$data_update = array(
				'rcopia_update_notification_lastupdate' => $last_update_date
			);
			$this->db->where('practice_id', $practice_id);
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
				$result1 = $this->rcopia($xml1, $practice_id);
				$response1 = new SimpleXMLElement($result1);
				$status1 = $response1->Response->PatientList->Patient->Status . "";
				if ($status1 == "error") {
					$description1 = $response1->Response->PatientList->Patient->Error->Text . "";
					$data1a = array(
						'action' => 'send_patient',
						'pid' => $row1['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description1,
						'practice_id' => $practice_id
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
				$result2 = $this->rcopia($xml2, $practice_id);
				$response2 = new SimpleXMLElement($result2);
				$status2 = $response2->Response->AllergyList->Allergy->Status . "";
				if ($status2 == "error") {
					$description2 = $response2->Response->AllergyList->Allergy->Error->Text . "";
					$data2a = array(
						'action' => 'send_allergy',
						'pid' => $row2['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description2,
						'practice_id' => $practice_id
					);
					$this->db->insert('extensions_log', $data2a);
					if ($description2 == "Can find neither name, Rcopia ID, or NDC ID for drug.") {
						$data2c['rcopia_sync'] = 'ye';
						$this->db->where('allergies_id',$row2['allergies_id']);
						$this->db->update('allergies',$data2c);
						$this->audit_model->update();
					}
				} else {
					$data2b['rcopia_sync'] = 'y';
					$this->db->where('allergies_id',$row2['allergies_id']);
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
				if ($row3['rxl_sig'] != '') {
					if(strpos($row3['rxl_sig'], ' ') !== false) {
						$sig_parts1 = explode(" ", $row3['rxl_sig']);
						$dose = $sig_parts1[0];
						$dose_unit = $sig_parts1[1];
					} else {
						$dose = $row3['rxl_sig'];
						$dose_unit = '';
					}
				} else {
					$dose = '';
					$dose_unit = '';
				}
				if ($row3['rxl_quantity'] != '') {
					if(strpos($row3['rxl_quantity'], ' ') !== false) {
						$quantity_parts1 = explode(" ", $row3['rxl_quantity']);
						$quantity = $quantity_parts1[0];
						$quantity_unit = $quantity_parts1[1];
					} else {
						$quantity = $row3['rxl_quantity'];
						$quantity_unit = '';
					}
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
				$xml3 .= "<Dose>" . $dose . "</Dose>";
				$xml3 .= "<DoseUnit>" . $dose_unit . "</DoseUnit>";
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
				$result3 = $this->rcopia($xml3, $practice_id);
				$response3 = new SimpleXMLElement($result3);
				$status3 = $response3->Response->MedicationList->Medication->Status . "";
				if ($status3 == "error") {
					$description3 = $response3->Response->MedicationList->Medication->Error->Text . "";
					$data3a = array(
						'action' => 'send_medication',
						'pid' => $row3['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description3,
						'practice_id' => $practice_id
					);
					$this->db->insert('extensions_log', $data3a);
				} else {
					$data3b['rcopia_sync'] = 'y';
					$this->db->where('rxl_id',$row3['rxl_id']);
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
				$result4 = $this->rcopia($xml4, $practice_id);
				$response4 = new SimpleXMLElement($result4);
				$status4 = $response4->Response->ProblemList->Problem->Status . "";
				if ($status4 == "error") {
					$description4 = $response4->Response->ProblemList->Problem->Error->Text . "";
					$data4a = array(
						'action' => 'send_problem',
						'pid' => $row4['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description4,
						'practice_id' => $practice_id
					);
					$this->db->insert('extensions_log', $data4a);
				} else {
					$data4b['rcopia_sync'] = 'y';
					$this->db->where('issue_id',$row4['issue_id']);
					$this->db->update('issues',$data4b);
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
				$result5 = $this->rcopia($xml5, $practice_id);
				$response5 = new SimpleXMLElement($result5);
				$status5 = $response5->Response->AllergyList->Allergy->Status . "";
				if ($status5 == "error") {
					$description5 = $response5->Response->AllergyList->Allergy->Error->Text . "";
					$data5a = array(
						'action' => 'delete_allergy',
						'pid' => $row5['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description5,
						'practice_id' => $practice_id
					);
					$this->db->insert('extensions_log', $data5a);
					$data5b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row5['pid']);
					$this->db->update('allergies',$data5b);
					$this->audit_model->update();
				} else {
					$data5b['rcopia_sync'] = 'y';
					$this->db->where('allergies_id',$row5['allergies_id']);
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
				$result6 = $this->rcopia($xml6, $practice_id);
				$response6 = new SimpleXMLElement($result6);
				$status6 = $response6->Response->MedicationList->Medication->Status . "";
				if ($status6 == "error") {
					$description6 = $response3->Response->MedicationList->Medication->Error->Text . "";
					$data6a = array(
						'action' => 'delete_medication',
						'pid' => $row6['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description6,
						'practice_id' => $practice_id
					);
					$this->db->insert('extensions_log', $data6a);
					$data6b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row6['pid']);
					$this->db->update('rx_list',$data6b);
					$this->audit_model->update();
				} else {
					$data6b['rcopia_sync'] = 'y';
					$this->db->where('rxl_id',$row6['rxl_id']);
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
				$result7 = $this->rcopia($xml7, $practice_id);
				$response7 = new SimpleXMLElement($result7);
				$status7 = $response7->Response->ProblemList->Problem->Status . "";
				if ($status7 == "error") {
					$description7 = $response7->Response->ProblemList->Problem->Error->Text . "";
					$data7a = array(
						'action' => 'delete_problem',
						'pid' => $row7['pid'],
						'extensions_name' => 'rcopia',
						'description' => $description7,
						'practice_id' => $practice_id
					);
					$this->db->insert('extensions_log', $data7a);
					$data7b['rcopia_sync'] = 'y';
					$this->db->where('pid',$row7['pid']);
					$this->db->update('issues',$data7b);
					$this->audit_model->update();
				} else {
					$data7b['rcopia_sync'] = 'y';
					$this->db->where('issue_id',$row7['issue_id']);
					$this->db->update('issues',$data7b);
					$this->audit_model->update();
				}
			}
		}
		
	}
	
	function rcopia($command, $practice_id)
	{
		$practice = $this->practiceinfo_model->get($practice_id)->row_array();
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
	
	function get_results()
	{
		$dir = '/srv/ftp/shared/import/';
		$files = scandir($dir);
		$count = count($files);
		$full_count=0;
		for ($i = 2; $i < $count; $i++) {
			$line = $files[$i];
			$file = $dir . $line;
			$hl7 = file_get_contents($file);
			$hl7_lines = explode("\r", $hl7);
			$results = array();
			$j = 0;
			foreach ($hl7_lines as $line) {
				$line_section = explode("|", $line);
				if ($line_section[0] == "MSH") {
					if (strpos($line_section[3], "LAB") !== FALSE) {
						$test_type = "Laboratory";
					} else {
						$test_type = "Imaging";
					}
				}
				if ($line_section[0] == "PID") {
					$name_section = explode("^", $line_section[5]);
					$lastname = $name_section[0];
					$firstname = $name_section[1];
					$year = substr($line_section[7], 0, 4);
					$month = substr($line_section[7], 4, 2);
					$day = substr($line_section[7], 6, 2);
					$dob = $year . "-" . $month . "-" . $day . " 00:00:00";
					$sex = strtolower($line_section[8]);
				}
				if ($line_section[0] == "ORC") {
					$provider_section = explode("^", $line_section[12]);
					$provider_lastname = $provider_section[1];
					$provider_firstname = $provider_section[2];
					$provider_id = $provider_section[0];
					$practice_section = explode("^", $line_section[17]);
					$practice_lab_id = $practice_section[0];
				}
				if ($line_section[0] == "OBX") {
					$test_name_section = explode("^", $line_section[3]);
					$results[$j]['test_name'] = $test_name_section[1];
					$results[$j]['test_result'] = $line_section[5];
					$results[$j]['test_units'] = $line_section[6];
					$results[$j]['test_reference'] = $line_section[7];
					$results[$j]['test_flags'] = $line_section[8];
					$year1 = substr($line_section[14], 0, 4);
					$month1 = substr($line_section[14], 4, 2);
					$day1 = substr($line_section[14], 6, 2);
					$hour1 = substr($line_section[14], 8, 2);
					$minute1 = substr($line_section[14], 10, 2);
					$results[$j]['test_datetime'] = $year1 . "-" . $month1 . "-" . $day1 . " " . $hour1 . ":" . $minute1 .":00";
					$j++;
				}
				if ($line_section[0] == "NTE") {
					$from = $line_section[3];
				}
			}
			$this->db->where('peacehealth_id', $practice_lab_id);
			$practice_query = $this->db->get('practiceinfo');
			if ($practice_query->num_rows() > 0) {
				$practice_row = $practice_query->row_array();
				$practice_id = $practice_row['practice_id'];
			} else {
				$cmd = 'rm ' . $file;
				exec($cmd);
				exit (0);
			}
			$this->db->select('users.lastname, users.firstname, users.title, users.id');
			$this->db->from('users');
			$this->db->join('providers', 'providers.id=users.id');
			$this->db->where('providers.peacehealth_id', $provider_id);
			$provider_query = $this->db->get();
			if ($provider_query->num_rows() > 0) {
				$provider_row = $provider_query->row_array();
				$provider_id = $provider_row['id'];
			} else {
				$provider_id = '';
			}
			$this->db->where('lastname', $lastname);
			$this->db->where('firstname', $firstname);
			$this->db->where('DOB', $dob);
			$this->db->where('sex', $sex);
			$patient_query = $this->db->get('demographics');
			if ($patient_query->num_rows() > 0) {
				$patient_row = $patient_query->row_array();
				$messages_pid = $patient_row['pid'];
				$this->db->select('firstname, lastname, DOB');
				$this->db->where('pid', $messages_pid);
				$patient_row = $this->db->get('demographics')->row_array();
				$dob_message = mdate("%m/%d/%Y", strtotime($patient_row['DOB']));
				$patient_name =  $patient_row['lastname'] . ', ' . $patient_row['firstname'] . ' (DOB: ' . $dob_message . ') (ID: ' . $pid . ')';
				$tests = 'y';
				$test_desc = "";
				$k = 0;
				foreach ($results as $results_row) {
					$test_data = array(
						'pid' => $patient_row['pid'],
						'test_name' => $results_row['test_name'],
						'test_result' => $results_row['test_result'],
						'test_units' => $results_row['test_units'],
						'test_reference' => $results_row['test_reference'],
						'test_flags' => $results_row['test_flags'],
						'test_from' => $from,
						'test_datetime' => $results_row['test_datetime'],
						'test_type' => $test_type,
						'test_provider_id' => $provider_id,
						'practice_id' => $practice_id
					);
					$this->db->insert('tests', $test_data);
					$this->audit_model->add();
					if ($k == 0) {
						$test_desc .= $results_row['test_name'];
					} else {
						$test_desc .= ", " . $results_row['test_name'];
					}
					$k++;
				}
				$practice_row = $this->practiceinfo_model->get($practice_id)->row_array();
				$directory = $practice_row['documents_dir'] . $pid;
				$file_path = $directory . '/tests_' . now() . '.pdf';
				$html = $this->page_intro('Test Results');
				$html .= $this->page_results($pid, $results, $patient_name);
				$this->load->library('mpdf');
				$this->mpdf=new mpdf('','Letter',0,'',26,26,26,26,9,9,'P');
				$this->mpdf->useOnlyCoreFonts = true;
				$this->mpdf->shrink_tables_to_fit=1;
				$this->mpdf->AddPage();
				$this->mpdf->SetHTMLFooter($footer,'O');
				$this->mpdf->SetHTMLFooter($footer,'E');
				$this->mpdf->WriteHTML($html);
				$this->mpdf->SetTitle('Document Generated by NOSH ChartingSystem');
				$this->mpdf->debug = true;
				$this->mpdf->Output($file_path,'F');
				while(!file_exists($file_path)) {
					sleep(2);
				}
				$documents_date = mdate("%Y-%m-%d %H:%i:%s", now());
				$test_desc = 'Test results for ' . $patient_name;
				$pages_data = array(
					'documents_url' => $file_path,
					'pid' => $pid,
					'documents_type' => $test_type,
					'documents_desc' => $test_desc,
					'documents_from' => $from,
					'documents_date' => $documents_date
				);			
				$documents_id = $this->chart_model->addDocuments($pages_data);
				$this->audit_model->add();
			} else {
				$messages_pid = '';
				$patient_name = "Unknown patient: " . $lastname . ", " . $firstname . ", DOB: " . $month . "/" . $day . "/" . $year;
				$tests = 'unk';
				foreach ($results as $results_row) {
					$test_data = array(
						'test_name' => $results_row['test_name'],
						'test_result' => $results_row['test_result'],
						'test_units' => $results_row['test_units'],
						'test_reference' => $results_row['test_reference'],
						'test_flags' => $results_row['test_flags'],
						'test_unassigned' => $patient_name,
						'test_from' => $from,
						'test_datetime' => $results_row['test_datetime'],
						'test_type' => $test_type,
						'test_provider_id' => $provider_id,
						'practice_id' => $practice_id
					);
					$this->db->insert('tests', $test_data);
					$this->audit_model->add();
				}
				$documents_id = '';
			}
			$subject = "Test results for " . $patient_name;
			$body = "Test results for " . $patient_name . "\n\n";
			foreach ($results as $results_row1) {
				$body .= $results_row1['test_name'] . ": " . $results_row1['test_result'] . ", Units: " . $results_row1['test_units'] . ", Normal reference range: " . $results_row1['test_reference'] . ", Date: " . $results_row1['test_datetime'] . "\n";
			}
			$body .= "\n" . $from;
			if ($tests="unk") {
				$body .= "\n" . "Patient is unknown to the system.  Please reconcile this test result in your dashboard.";
			}
			if ($provider_id != '') {
				$provider_name = $provider_row['firstname'] . " " . $provider_row['lastname'] . ", " . $provider_row['title'] . " (" . $provider_id . ")";
				$data_message = array(
					'pid' => $pid,
					'message_to' => $provider_name,
					'message_from' => $provider_row['id'],
					'subject' => $subject,
					'body' => $body,
					'patient_name' => $patient_name,
					'status' => 'Sent',
					'mailbox' => $provider_id,
					'practice_id' => $practice_id,
					'documents_id' => $documents_id
				);
				$this->db->insert('messaging', $data_message);
			}
			$cmd = 'rm ' . $file;
			exec($cmd);
			$full_count++;
		}
		return $full_count;
	}
	
	function page_intro($title, $practice_id)
	{
		$practice = $this->practiceinfo_model->get($practice_id)->row_array();
		$data['practiceName'] = $practice['practice_name'];
		$data['website'] = $practice['website'];
		$data['practiceInfo'] = $practice['street_address1'];
		if ($practice['street_address2'] != '') {
			$data['practiceInfo'] .= ', ' . $practice['street_address2'];
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice['city'] . ', ' . $practice['state'] . ' ' . $practice['zip'] . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice['phone'] . ', Fax: ' . $practice['fax'] . '<br />';
		if ($practice['practice_logo'] != '') {
			$logo = str_replace("/var/www/","http://localhost/", $practice['practice_logo']);
			$data['practiceLogo'] = "<img src='" . $logo . "' border='0'>";
		} else {
			$data['practiceLogo'] = '<br><br><br><br><br>';
		}
		$data['title'] = $title;
		return $this->load->view('auth/pages/intro_page', $data, TRUE);
	}
	
	function page_results($pid, $results, $patient_name)
	{
		$body = '';
		$body .= "<br>Test results for " . $patient_name . "<br><br>";
		$body .= "<table><tr><th>Date</th><th>Test</th><th>Result</th><th>Units</th><th>Normal reference range</th><th>Flags</th></tr>";
		foreach ($results as $results_row1) {
			$body .= "<tr><td>" . $results_row1['test_datetime'] . "</td><td>" . $results_row1['test_name'] . "</td><td>" . $results_row1['test_result'] . "</td><td>" . $results_row1['test_units'] . "</td><td>" . $results_row1['test_reference'] . "</td><td>" . $results_row1['test_flags'] . "</td></tr>";
			$from = $results_row1['test_from'];
		}
		$body .= "</table><br>" . $from;
		$body .= '</body></html>';
		return $body;
	}
	
	function get_scans($practice_id)
	{
		$query = $this->practiceinfo_model->get($practice_id);
		$result = $query->row_array();
		$dir = $result['documents_dir'] . 'scans/';
		$files = scandir($dir);
		$count = count($files);
		$j=0;
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
				'filePages' => $filePages,
				'practice_id' => $practice_id
			);
			$this->messaging_model->addscan($data);
			rename($filePath1, $filePath);
			$j++;
		}
		return $j;
	}
}
/* End of file: reminder.php */
/* Location: application/controllers/reminder.php */
