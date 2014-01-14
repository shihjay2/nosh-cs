<?php
// This install controller is only for quick insertion of an admin user into the system.
// I strongly recommend you delete this file after you've installed NOSH ChartingSystem.
// This controller is not in any way needed to run the application.

class Install extends Controller {

	function __construct()
	{
		parent::Controller();
		$this->load->library('encrypt');
		$this->load->library('csv');
		$this->load->helper('file');
	}

	// --------------------------------------------------------------------

	function index()
	{
		include(APPPATH.'config/database'.EXT);
		if ($db['default']['username'] != "") {
			$db_con = mysqli_connect('localhost', $db['default']['username'], $db['default']['password']);
			$db_selected = mysqli_select_db($db_con, 'nosh');
			if ($db_selected) {
				echo "NOSH ChartingSystem is already installed.";
				exit (0);
			}
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[40]|callback_reg_username_check');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[12]|matches[conf_password]');
		$this->form_validation->set_rules('conf_password', 'Password confirmation', 'trim|required|min_length[4]|max_length[12]|matches[password]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_reg_email_check');
		$this->form_validation->set_rules('practice_name', 'Practice Name', 'trim|required');
		$this->form_validation->set_rules('street_address1', 'Street Address', 'trim|required');
		$this->form_validation->set_rules('street_address2', 'Street Address', 'trim');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('zip', 'Zip', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('fax', 'Fax', 'trim|required');
		$this->form_validation->set_rules('documents_dir', 'Documents Directory', 'required');
		$this->form_validation->set_rules('db_username', 'MySQL Username', 'required');
		$this->form_validation->set_rules('db_password', 'MySQL Password');
		$this->form_validation->set_rules('smtp_user', 'Gmail username for sending e-mail', 'required');
		$this->form_validation->set_rules('smtp_pass', 'Gmail password for sending e-mail');
		if ($this->form_validation->run() == FALSE) {
			$vars['message'] = '';
			$this->load->view('install', $vars);
		} else {
			$username = set_value('username');
			$password = sha1($this->config->item('encryption_key').set_value('password'));
			$email = set_value('email');
			$practice_name = set_value('practice_name');
			$street_address1 = set_value('street_address1');
			$street_address2 = set_value('street_address2');
			$city = set_value('city');
			$state = set_value('state');
			$zip = set_value('zip');
			$phone = set_value('phone');
			$fax = set_value('fax');
			$documents_dir = set_value('documents_dir');
			$db_username = set_value('db_username');
			$db_password = set_value('db_password');
			$smtp_user = set_value('smtp_user');
			$smtp_pass = set_value('smtp_pass');
			$con = mysqli_connect("localhost",$db_username,$db_password);
			if (mysqli_connect_errno()) {
				die('Could not connect: ' . mysqli_connect_error());
			}
			$sql = "CREATE DATABASE nosh";
			$create = mysqli_query($con, $sql);
			if (!$create) {
				die('Error creating database: ' . mysqli_error($con));
			}
			mysqli_close($con);
			$filename = '/var/www/nosh/system/application/config/database.php';
			$str = file_get_contents($filename);
			$fp = fopen($filename,'w');
			$search_1 = '$default_db_username = ""';
			$search_2 = '$default_db_password = ""';
			$replace_1 = '$default_db_username = "' . $db_username . '"';
			$replace_2 = '$default_db_password = "' . $db_password . '"';
			$str = str_replace($search_1,$replace_1,$str);
			$str = str_replace($search_2,$replace_2,$str);
			fwrite($fp,$str,strlen($str));
			$dir_pos = strpos($documents_dir, "/");
			if ($dir_pos === '0') {
				$documents_dir1 = $documents_dir;
			} else {
				$documents_dir1 = "/" . $documents_dir;
			}
			$this->do_install($username, $password, $email, $practice_name, $street_address1, $street_address2, $city, $state, $zip, $phone, $fax, $documents_dir1, $smtp_user, $smtp_pass);
		}
	}

	// --------------------------------------------------------------------

	function do_install($username = '', $password = '', $email = '', $practice_name = '', $street_address1 = '', $street_address2 = '', $city = '', $state = '', $zip = '', $phone = '', $fax = '', $documents_dir1 = '', $smtp_user = '', $smtp_pass = '')
	{
		if ( ! extension_loaded('dom')) {
			show_error('The NOSH ChartingSystem requires the DOM extension to be enabled to generate PDFs.  After you have satisfied this, you can try re-installing.');
		}
		if ( ! is_writable($documents_dir1)) {
			show_error('You need to set the patientdata folder to writable permissions.  After you have satisfied this, you can try re-installing.');
		}
		if ($practice_name == '' OR $street_address1 == '' OR $city == '' OR $state == '' OR $zip == '' OR $phone == '' OR $fax == '') {
			show_error('Something went wrong...Fill all fields in the installation.');
		}
		$this->load->database();
		$this->load->dbforge();
		
		// Sessions
		$sessions_definition = array(
			'session_id' => array('type' => 'VARCHAR', 'constraint' => 40, 'default' => '0'),
			'ip_address' => array('type' => 'VARCHAR', 'constraint' => 16, 'default' => '0'),
			'user_agent' => array('type' => 'VARCHAR', 'constraint' => 50),
			'last_activity' => array('type' => 'INT', 'constraint' => 10, 'default' => '0'),
			'user_data' => array('type' => 'TEXT')
		);
		$this->dbforge->add_field($sessions_definition);
		$this->dbforge->add_key('session_id', TRUE);
		$this->dbforge->create_table('ci_sessions', TRUE);
		
		// Audit
		$audit_definition = array(
			'audit_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'user_id' => array('type' => 'INT', 'constraint' => 11),
			'displayname' => array('type' => 'VARCHAR', 'constraint' => 255),
			'group_id' => array('type' => 'INT', 'constraint' => 11),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'action' => array('type' => 'VARCHAR', 'constraint' => 255),
			'query' => array('type' => 'LONGTEXT'),
			'timestamp' => array('type' => 'TIMESTAMP'),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($audit_definition);
		$this->dbforge->add_key('audit_id', TRUE);
		$this->dbforge->create_table('audit', TRUE);
		
		// Extensions
		$extensions_definition = array(
			'extensions_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'extensions_name' => array('type' => 'VARCHAR', 'constraint' => 255),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'action' => array('type' => 'VARCHAR', 'constraint' => 255),
			'description' => array('type' => 'LONGTEXT'),
			'timestamp' => array('type' => 'TIMESTAMP'),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($extensions_definition);
		$this->dbforge->add_key('extensions_id', TRUE);
		$this->dbforge->create_table('extensions_log', TRUE);
		
		// MTM
		$mtm_definition = array(
			'mtm_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'mtm_description' => array('type' => 'LONGTEXT'),
			'mtm_recommendations' => array('type' => 'LONGTEXT'),
			'mtm_beneficiary_notes' => array('type' => 'LONGTEXT'),
			'complete' => array('type' => 'VARCHAR', 'constraint' => 4),
			'mtm_action' => array('type' => 'LONGTEXT'),
			'mtm_outcome' => array('type' => 'LONGTEXT'),
			'mtm_related_conditions' => array('type' => 'LONGTEXT'),
			'mtm_duration' => array('type' => 'VARCHAR', 'constraint' => 255),
			'mtm_date_completed' => array('type' => 'DATE'),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($mtm_definition);
		$this->dbforge->add_key('mtm_id', TRUE);
		$this->dbforge->create_table('mtm', TRUE);
		
		// Users
		$users_definition = array(
			'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'username' => array('type' => 'VARCHAR', 'constraint' => 255),
			'email' => array('type' => 'VARCHAR', 'constraint' => 255),
			'displayname' => array('type' => 'VARCHAR', 'constraint' => 255),
			'firstname' => array('type' => 'VARCHAR', 'constraint' => 100),
			'lastname' => array('type' => 'VARCHAR', 'constraint' => 100),
			'middle' => array('type' => 'VARCHAR', 'constraint' => 100),
			'title' => array('type' => 'VARCHAR', 'constraint' => 100),
			'password' => array('type' => 'VARCHAR', 'constraint' => 255),
			'group_id' => array('type' => 'INT', 'constraint' => 11, 'default' => '100'),
			'token' => array('type' => 'VARCHAR', 'constraint' => 255),
			'identifier' => array('type' => 'VARCHAR', 'constraint' => 255),
			'active' => array('type' => 'INT', 'constraint' => 11),
			'secret_question' => array('type' => 'VARCHAR', 'constraint' => 255),
			'secret_answer' => array('type' => 'VARCHAR', 'constraint' => 255),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($users_definition);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('users', TRUE);
		
		// Groups
		$groups_definition = array(
			'id' => array('type' => 'INT', 'constraint' => 11),
			'title' => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => ''),
			'description' => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => '')
		);
		$this->dbforge->add_field($groups_definition);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('groups', TRUE);

		// Providers
		$providers_definition = array(
			'id' => array('type' => 'BIGINT', 'constraint' => 20),
			'license' => array('type' => 'VARCHAR', 'constraint' => 100),
			'license_state' => array('type' => 'VARCHAR', 'constraint' => 100),
			'npi' => array('type' => 'VARCHAR', 'constraint' => 100),
			'npi_taxonomy' => array('type' => 'VARCHAR', 'constraint' => 100),
			'upin' => array('type' => 'VARCHAR', 'constraint' => 100),
			'dea' => array('type' => 'VARCHAR', 'constraint' => 100),
			'medicare'=> array('type' => 'VARCHAR', 'constraint' => 100),
			'specialty' => array('type' => 'VARCHAR', 'constraint' => 100),
			'tax_id' => array('type' => 'VARCHAR', 'constraint' => 100),
			'signature' => array('type' => 'VARCHAR', 'constraint' => 100),
			'timeslotsperhour' => array('type' => 'INT', 'constraint' => 10),
			'sun_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'sun_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'mon_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'mon_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'tue_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'tue_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'wed_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'wed_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'thu_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'thu_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'fri_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'fri_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'sat_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'sat_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'rcopia_username' => array('type' => 'VARCHAR', 'constraint' => 100),
			'schedule_increment' => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => '20'),
			'practice_id' => array('type' => 'INT', 'constraint' => 11),
			'peacehealth_id' => array('type' => 'VARCHAR', 'constraint' => 100)
		);
		$this->dbforge->add_field($providers_definition);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('providers', TRUE);
		
		// Practice Information
		$practiceinfo_definition = array(
			'practice_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'practice_name' => array('type' => 'VARCHAR', 'constraint' => 255),
			'street_address1' => array('type' => 'VARCHAR', 'constraint' => 255),
			'street_address2' => array('type' => 'VARCHAR', 'constraint' => 255),
			'city' => array('type' => 'VARCHAR', 'constraint' => 100),
			'state' => array('type' => 'VARCHAR', 'constraint' => 100),
			'zip' => array('type' => 'VARCHAR', 'constraint' => 100),
			'phone' => array('type' => 'VARCHAR', 'constraint' => 100),
			'fax' => array('type' => 'VARCHAR', 'constraint' => 100),
			'email' => array('type' => 'VARCHAR', 'constraint' => 100),
			'website' => array('type' => 'VARCHAR', 'constraint' => 255),
			'primary_contact' => array('type' => 'VARCHAR', 'constraint' => 100),
			'npi' => array('type' => 'VARCHAR', 'constraint' => 100),
			'medicare' => array('type' => 'VARCHAR', 'constraint' => 100),
			'tax_id' => array('type' => 'VARCHAR', 'constraint' => 100),
			'weight_unit' => array('type' => 'VARCHAR', 'constraint' => 100),
			'height_unit' => array('type' => 'VARCHAR', 'constraint' => 100),
			'temp_unit' => array('type' => 'VARCHAR', 'constraint' => 100),
			'hc_unit' => array('type' => 'VARCHAR', 'constraint' => 100),
			'sun_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'sun_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'mon_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'mon_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'tue_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'tue_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'wed_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'wed_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'thu_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'thu_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'fri_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'fri_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'sat_o' => array('type' => 'VARCHAR', 'constraint' => 10),
			'sat_c' => array('type' => 'VARCHAR', 'constraint' => 10),
			'minTime' => array('type' => 'VARCHAR', 'constraint' => 10),
			'maxTime' => array('type' => 'VARCHAR', 'constraint' => 10),
			'weekends' => array('type' => 'TINYINT', 'constraint' => 4),
			'default_pos_id' => array('type' => 'TINYINT', 'constraint' => 4),
			'documents_dir' => array('type' => 'VARCHAR', 'constraint' => 255),
			'billing_street_address1' => array('type' => 'VARCHAR', 'constraint' => 255),
			'billing_street_address2' => array('type' => 'VARCHAR', 'constraint' => 255),
			'billing_city' => array('type' => 'VARCHAR', 'constraint' => 100),
			'billing_state' => array('type' => 'VARCHAR', 'constraint' => 100),
			'billing_zip' => array('type' => 'VARCHAR', 'constraint' => 100),
			'fax_type' => array('type' => 'VARCHAR', 'constraint' => 100),
			'fax_email' => array('type' => 'VARCHAR', 'constraint' => 100),
			'fax_email_password' => array('type' => 'VARCHAR', 'constraint' => 100),
			'fax_email_hostname' => array('type' => 'VARCHAR', 'constraint' => 100),
			'smtp_user' => array('type' => 'VARCHAR', 'constraint' => 100),
			'smtp_pass' => array('type' => 'VARCHAR', 'constraint' => 100),
			'patient_portal' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rcopia_extension' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rcopia_apiVendor' => array('type' => 'VARCHAR', 'constraint' => 100),
			'rcopia_apiPass' => array('type' => 'VARCHAR', 'constraint' => 100),
			'rcopia_apiPractice' => array('type' => 'VARCHAR', 'constraint' => 100),
			'rcopia_apiSystem' => array('type' => 'VARCHAR', 'constraint' => 100),
			'rcopia_update_notification_lastupdate' => array('type' => 'VARCHAR', 'constraint' => 100),
			'updox_extension' => array('type' => 'VARCHAR', 'constraint' => 4),
			'version' => array('type' => 'VARCHAR', 'constraint' => 20),
			'mtm_extension' => array('type' => 'VARCHAR', 'constraint' => 4),
			'practice_logo' => array('type' => 'VARCHAR', 'constraint' => 255),
			'mtm_logo' => array('type' => 'VARCHAR', 'constraint' => 255),
			'mtm_alert_users' => array('type' => 'LONGTEXT'),
			'additional_message' => array('type' => 'LONGTEXT'),
			'snomed_extension' => array('type' => 'VARCHAR', 'constraint' => 4),
			'vivacare' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sales_tax' => array('type' => 'VARCHAR', 'constraint' => 10, 'default' => ''),
			'practicehandle' => array('type' => 'VARCHAR', 'constraint' => 255),
			'peacehealth_id' => array('type' => 'VARCHAR', 'constraint' => 100),
			'active' => array('type' => 'VARCHAR', 'constraint' => 10)
		);
		$this->dbforge->add_field($practiceinfo_definition);
		$this->dbforge->add_key('practice_id', TRUE);
		$this->dbforge->create_table('practiceinfo', TRUE);
		
		// Address Book
		$addressbook_definition = array(
			'address_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'specialty' => array('type' => 'VARCHAR', 'constraint' => 100),
			'displayname' => array('type' => 'VARCHAR', 'constraint' => 255),
			'lastname' => array('type' => 'VARCHAR', 'constraint' => 100),
			'firstname' => array('type' => 'VARCHAR', 'constraint' => 100),
			'facility' => array('type' => 'VARCHAR', 'constraint' => 100),
			'prefix' => array('type' => 'VARCHAR', 'constraint' => 100),
			'suffix' => array('type' => 'VARCHAR', 'constraint' => 100),
			'street_address1' => array('type' => 'VARCHAR', 'constraint' => 255),
			'street_address2' => array('type' => 'VARCHAR', 'constraint' => 255),
			'city' => array('type' => 'VARCHAR', 'constraint' => 100),
			'state' => array('type' => 'VARCHAR', 'constraint' => 100),
			'zip' => array('type' => 'VARCHAR', 'constraint' => 100),
			'phone' => array('type' => 'VARCHAR', 'constraint' => 100),
			'fax' => array('type' => 'VARCHAR', 'constraint' => 100),
			'email' => array('type' => 'VARCHAR', 'constraint' => 100),
			'comments' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_plan_payor_id' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_plan_type' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_plan_assignment' => array('type' => 'VARCHAR', 'constraint' => 4),
			'insurance_plan_ppa_phone' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_plan_ppa_fax' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_plan_ppa_url' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_plan_mpa_phone' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_plan_mpa_fax' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_plan_mpa_url' => array('type' => 'VARCHAR', 'constraint' => 255),
			'ordering_id' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_box_31' => array('type' => 'VARCHAR', 'constraint' => 4),
			'insurance_box_32a' => array('type' => 'VARCHAR', 'constraint' => 4),
			'npi' => array('type' => 'VARCHAR', 'constraint' => 255),
			'electronic_order' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($addressbook_definition);
		$this->dbforge->add_key('address_id', TRUE);
		$this->dbforge->create_table('addressbook', TRUE);
		
		// Messaging
		$messaging_definition = array(
			'message_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'INT', 'constraint' => 11),
			'message_to' => array('type' => 'VARCHAR', 'constraint' => 255),
			'message_from' => array('type' => 'INT', 'constraint' => 11),
			'date' => array('type' => 'TIMESTAMP'),
			'cc' => array('type' => 'VARCHAR', 'constraint' => 255),
			'subject' => array('type' => 'VARCHAR', 'constraint' => 255),
			'body' => array('type' => 'LONGTEXT'),
			'patient_name' => array('type' => 'VARCHAR', 'constraint' => 255),
			'status' => array('type' => 'VARCHAR', 'constraint' => 255),
			't_messages_id' => array('type' => 'INT', 'constraint' => 11),
			'mailbox' => array('type' => 'INT', 'constraint' => 11),
			'practice_id' => array('type' => 'INT', 'constraint' => 11),
			'read' => array('type' => 'VARCHAR', 'constraint' => 4),
			'documents_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($messaging_definition);
		$this->dbforge->add_key('message_id', TRUE);
		$this->dbforge->create_table('messaging', TRUE);
		
		// Recieved Faxes
		$received_definition = array(
			'received_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'fileName' => array('type' => 'VARCHAR', 'constraint' => 255),
			'filePath' => array('type' => 'VARCHAR', 'constraint' => 255),
			'fileFrom' => array('type' => 'VARCHAR', 'constraint' => 255),
			'fileDateTime' => array('type' => 'DATETIME'),
			'filePages' => array('type' => 'INT', 'constraint' => 11),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($received_definition);
		$this->dbforge->add_key('received_id', TRUE);
		$this->dbforge->create_table('received', TRUE);
		
		// Send Faxes Queue
		$sendfax_definition = array(
			'job_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'user' => array('type' => 'VARCHAR', 'constraint' => 255),
			'faxsubject' => array('type' => 'VARCHAR', 'constraint' => 255),
			'faxcoverpage' => array('type' => 'VARCHAR', 'constraint' => 10),
			'faxmessage' => array('type' => 'LONGTEXT'),
			'faxschedule' => array('type' => 'VARCHAR', 'constraint' => 10),
			'datepicker' => array('type' => 'DATE'),
			'time' => array('type' => 'TIME'),
			'faxdraft' => array('type' => 'VARCHAR', 'constraint' => 10),
			'sentdate' => array('type' => 'DATE'),
			'success' => array('type' => 'TINYINT', 'constraint' => 4, 'default' => '0'),
			'attempts' => array('type' => 'TINYINT', 'constraint' => 4, 'default' => '0'),
			'ready_to_send' => array('type' => 'TINYINT', 'constraint' => 4, 'default' => '0'),
			'command' => array('type' => 'LONGTEXT'),
			'last_attempt' => array('type' => 'DATETIME'),
			'senddate'=> array('type' => 'DATETIME'),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($sendfax_definition);
		$this->dbforge->add_key('job_id', TRUE);
		$this->dbforge->create_table('sendfax', TRUE);
		
		// Fax Recipients
		$recipients_definition = array(
			'sendlist_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'job_id' => array('type' => 'INT', 'constraint' => 11),
			'faxrecipient' => array('type' => 'VARCHAR', 'constraint' => 255),
			'faxnumber' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($recipients_definition);
		$this->dbforge->add_key('sendlist_id', TRUE);
		$this->dbforge->create_table('recipients', TRUE);
		
		// Fax Pages
		$pages_definition = array(
			'pages_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'job_id' => array('type' => 'INT', 'constraint' => 11),
			'file_original' => array('type' => 'VARCHAR', 'constraint' => 255),
			'file_size' => array('type' => 'VARCHAR', 'constraint' => 100),
			'file' => array('type' => 'VARCHAR', 'constraint' => 255),
			'pagecount' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($pages_definition);
		$this->dbforge->add_key('pages_id', TRUE);
		$this->dbforge->create_table('pages', TRUE);
		
		// Schedule
		$schedule_definition = array(
			'appt_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'INT', 'constraint' => 11),
			'start' => array('type' => 'INT', 'constraint' => 100),
			'end' => array('type' => 'INT', 'constraint' => 100),
			'title' => array('type' => 'VARCHAR', 'constraint' => 255),
			'visit_type' => array('type' => 'VARCHAR', 'constraint' => 255),
			'reason' => array('type' => 'VARCHAR', 'constraint' => 255),
			'status' => array('type' => 'VARCHAR', 'constraint' => 100),
			'provider_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'user_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'timestamp' => array('type' => 'TIMESTAMP', 'default' => '0000-00-00 00:00:00')
		);
		$this->dbforge->add_field($schedule_definition);
		$this->dbforge->add_key('appt_id', TRUE);
		$this->dbforge->create_table('schedule', TRUE);

		// Repeat Schedule
		$repeat_schedule_definition = array(
			'repeat_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'repeat_day' => array('type' => 'VARCHAR', 'constraint' => 20),
			'repeat_start_time' => array('type' => 'VARCHAR', 'constraint' => 20),
			'repeat_end_time' => array('type' => 'VARCHAR', 'constraint' => 20),
			'repeat' => array('type' => 'INT', 'constraint' => 100),
			'until' => array('type' => 'INT', 'constraint' => 100),
			'title' => array('type' => 'VARCHAR', 'constraint' => 255),
			'reason' => array('type' => 'VARCHAR', 'constraint' => 255),
			'provider_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'start' => array('type' => 'INT', 'constraint' => 100)
		);
		$this->dbforge->add_field($repeat_schedule_definition);
		$this->dbforge->add_key('repeat_id', TRUE);
		$this->dbforge->create_table('repeat_schedule', TRUE);
		
		// Calendar Settings
		$calendar_definition = array(
			'calendar_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'visit_type' => array('type' => 'VARCHAR', 'constraint' => 255),
			'duration' => array('type' => 'INT', 'constraint' => 11),
			'classname' => array('type' => 'VARCHAR', 'constraint' => 20),
			'active' => array('type' => 'VARCHAR', 'constraint' => 4),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($calendar_definition);
		$this->dbforge->add_key('calendar_id', TRUE);
		$this->dbforge->create_table('calendar', TRUE);

		// Demographics
		$demographics_definition = array(
			'pid' => array('type' => 'BIGINT', 'constraint' => 20, 'auto_increment' => TRUE),
			'lastname' => array('type' => 'VARCHAR', 'constraint' => 100),
			'firstname' => array('type' => 'VARCHAR', 'constraint' => 100),
			'middle' => array('type' => 'VARCHAR', 'constraint' => 100),
			'nickname' => array('type' => 'VARCHAR', 'constraint' => 100),
			'title' => array('type' => 'VARCHAR', 'constraint' => 100),
			'sex' => array('type' => 'VARCHAR', 'constraint' => 100),
			'DOB' => array('type' => 'DATETIME'),
			'ss' => array('type' => 'VARCHAR', 'constraint' => 100),
			'race' => array('type' => 'VARCHAR', 'constraint' => 100),
			'ethnicity' => array('type' => 'VARCHAR', 'constraint' => 100),
			'language' => array('type' => 'VARCHAR', 'constraint' => 100),
			'address' => array('type' => 'VARCHAR', 'constraint' => 255),
			'city' => array('type' => 'VARCHAR', 'constraint' => 100),
			'state' => array('type' => 'VARCHAR', 'constraint' => 100),
			'zip' => array('type' => 'VARCHAR', 'constraint' => 100),
			'phone_home' => array('type' => 'VARCHAR', 'constraint' => 100),
			'phone_work' => array('type' => 'VARCHAR', 'constraint' => 100),
			'phone_cell' => array('type' => 'VARCHAR', 'constraint' => 100),
			'email' => array('type' => 'VARCHAR', 'constraint' => 100),
			'marital_status' => array('type' => 'VARCHAR', 'constraint' => 100),
			'partner_name' => array('type' => 'VARCHAR', 'constraint' => 255),
			'employer' => array('type' => 'VARCHAR', 'constraint' => 100),
			'emergency_contact' => array('type' => 'VARCHAR', 'constraint' => 100),
			'emergency_phone' => array('type' => 'VARCHAR', 'constraint' => 100),
			'reminder_method' => array('type' => 'VARCHAR', 'constraint' => 100),
			'cell_carrier' => array('type' => 'VARCHAR', 'constraint' => 100),
			'reminder_to' => array('type' => 'VARCHAR', 'constraint' => 100),
			'photo' => array('type' => 'VARCHAR', 'constraint' => 255),
			'preferred_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'preferred_pharmacy' => array('type' => 'VARCHAR', 'constraint' => 255),
			'active' => array('type' => 'TINYINT', 'constraint' => 4),
			'date' => array('type' => 'TIMESTAMP'),
			'other1' => array('type' => 'VARCHAR', 'constraint' => 255),
			'other2' => array('type' => 'VARCHAR', 'constraint' => 255),
			'comments' => array('type' => 'LONGTEXT'),
			'tobacco' => array('type' => 'VARCHAR', 'constraint' => 5),
			'sexuallyactive' => array('type' => 'VARCHAR', 'constraint' => 5),
			'pregnant' => array('type' => 'VARCHAR', 'constraint' => 255),
			'caregiver' => array('type' => 'VARCHAR', 'constraint' => 255),
			'referred_by' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rcopia_sync' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rcopia_update_medications' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rcopia_update_medications_date' => array('type' => 'VARCHAR', 'constraint' => 20),
			'rcopia_update_allergy' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rcopia_update_allergy_date' => array('type' => 'VARCHAR', 'constraint' => 20),
			'rcopia_update_prescription' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rcopia_update_prescription_date' => array('type' => 'VARCHAR', 'constraint' => 20),
			'registration_code' => array('type' => 'VARCHAR', 'constraint' => 255),
			'race_code' => array('type' => 'VARCHAR', 'constraint' => 100),
			'ethnicity_code' => array('type' => 'VARCHAR', 'constraint' => 100),
			'guardian_firstname' => array('type' => 'VARCHAR', 'constraint' => 255),
			'guardian_lastname' => array('type' => 'VARCHAR', 'constraint' => 255),
			'guardian_code' => array('type' => 'VARCHAR', 'constraint' => 100),
			'guardian_address' => array('type' => 'VARCHAR', 'constraint' => 255),
			'guardian_city' => array('type' => 'VARCHAR', 'constraint' => 100),
			'guardian_state' => array('type' => 'VARCHAR', 'constraint' => 100),
			'guardian_zip' => array('type' => 'VARCHAR', 'constraint' => 100),
			'guardian_phone_home' => array('type' => 'VARCHAR', 'constraint' => 100),
			'guardian_phone_work' => array('type' => 'VARCHAR', 'constraint' => 100),
			'guardian_phone_cell' => array('type' => 'VARCHAR', 'constraint' => 100),
			'guardian_email' => array('type' => 'VARCHAR', 'constraint' => 100),
			'guardian_relationship' => array('type' => 'VARCHAR', 'constraint' => 100),
			'lang_code' => array('type' => 'VARCHAR', 'constraint' => 100),
		);
		$this->dbforge->add_field($demographics_definition);
		$this->dbforge->add_key('pid', TRUE);
		$this->dbforge->create_table('demographics', TRUE);

		$demographics_query = 'ALTER TABLE demographics AUTO_INCREMENT = 1000';
		$this->db->query($demographics_query);

		// Encounters
		$encounters_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'appt_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'encounter_date' => array('type' => 'TIMESTAMP'),
			'encounter_signed' => array('type' => 'VARCHAR', 'constraint' => 4),
			'date_signed' => array('type' => 'TIMESTAMP'),
			'encounter_DOS' => array('type' => 'DATETIME'),
			'encounter_age' => array('type' => 'VARCHAR', 'constraint' => 100),
			'encounter_type' => array('type' => 'VARCHAR', 'constraint' => 100),
			'encounter_location' => array('type' => 'VARCHAR', 'constraint' => 100),
			'encounter_activity' => array('type' => 'VARCHAR', 'constraint' => 100),
			'encounter_cc' => array('type' => 'LONGTEXT'),
			'encounter_condition' => array('type' => 'VARCHAR', 'constraint' => 255),
			'encounter_condition_work' => array('type' => 'VARCHAR', 'constraint' => 4),
			'encounter_condition_auto' => array('type' => 'VARCHAR', 'constraint' => 4),
			'encounter_condition_auto_state' => array('type' => 'VARCHAR', 'constraint' => 2),
			'encounter_condition_other' => array('type' => 'VARCHAR', 'constraint' => 4),
			'bill_submitted' => array('type' => 'VARCHAR', 'constraint' => 4),
			'addendum' => array('type' => 'VARCHAR', 'constraint' => 4),
			'addendum_eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'user_id' => array('type' => 'INT', 'constraint' => 11),
			'encounter_role' => array('type' => 'VARCHAR', 'constraint' => 255),
			'referring_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'practice_id' => array('type' => 'INT', 'constraint' => 11),
			'referring_provider_npi' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($encounters_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('encounters', TRUE);
		
		// History of Present Illness
		$hpi_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'hpi_date' => array('type' => 'TIMESTAMP'),
			'hpi' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($hpi_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('hpi', TRUE);
		
		// Review of Systems
		$ros_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'ros_date' => array('type' => 'TIMESTAMP'),
			'ros_gen' => array('type' => 'LONGTEXT'),
			'ros_eye' => array('type' => 'LONGTEXT'),
			'ros_ent' => array('type' => 'LONGTEXT'),
			'ros_resp' => array('type' => 'LONGTEXT'),
			'ros_cv' => array('type' => 'LONGTEXT'),
			'ros_gi' => array('type' => 'LONGTEXT'),
			'ros_gu' => array('type' => 'LONGTEXT'),
			'ros_mus' => array('type' => 'LONGTEXT'),
			'ros_neuro' => array('type' => 'LONGTEXT'),
			'ros_psych' => array('type' => 'LONGTEXT'),
			'ros_heme' => array('type' => 'LONGTEXT'),
			'ros_endocrine' => array('type' => 'LONGTEXT'),
			'ros_skin' => array('type' => 'LONGTEXT'),
			'ros_wcc' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($ros_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('ros', TRUE);
		
		// Other History
		$other_history_definition = array(
			'oh_id' => array('type' => 'BIGINT', 'constraint' => 20, 'auto_increment' => TRUE),
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'oh_date' => array('type' => 'TIMESTAMP'),
			'oh_pmh' => array('type' => 'LONGTEXT'),
			'oh_psh' => array('type' => 'LONGTEXT'),
			'oh_fh' => array('type' => 'LONGTEXT'),
			'oh_sh' => array('type' => 'LONGTEXT'),
			'oh_etoh' => array('type' => 'LONGTEXT'),
			'oh_tobacco' => array('type' => 'LONGTEXT'),
			'oh_drugs' => array('type' => 'LONGTEXT'),
			'oh_employment' => array('type' => 'LONGTEXT'),
			'oh_meds' => array('type' => 'LONGTEXT'),
			'oh_supplements' => array('type' => 'LONGTEXT'),
			'oh_allergies' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($other_history_definition);
		$this->dbforge->add_key('oh_id', TRUE);
		$this->dbforge->create_table('other_history', TRUE);
		
		// Vitals
		$vitals_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'vitals_date' => array('type' => 'DATETIME'),
			'vitals_age' => array('type' => 'VARCHAR', 'constraint' => 3),
			'pedsage' => array('type' => 'VARCHAR', 'constraint' => 100),
			'weight' => array('type' => 'VARCHAR', 'constraint' => 10),
			'height' => array('type' => 'VARCHAR', 'constraint' => 10),
			'headcircumference' => array('type' => 'VARCHAR', 'constraint' => 10),
			'BMI' => array('type' => 'VARCHAR', 'constraint' => 10),
			'temp' => array('type' => 'VARCHAR', 'constraint' => 10),
			'temp_method' => array('type' => 'VARCHAR', 'constraint' => 100),
			'bp_systolic' => array('type' => 'VARCHAR', 'constraint' => 10),
			'bp_diastolic' => array('type' => 'VARCHAR', 'constraint' => 10),
			'bp_position' => array('type' => 'VARCHAR', 'constraint' => 100),
			'pulse' => array('type' => 'VARCHAR', 'constraint' => 10),
			'respirations' => array('type' => 'VARCHAR', 'constraint' => 10),
			'o2_sat' => array('type' => 'VARCHAR', 'constraint' => 10),
			'vitals_other' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($vitals_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('vitals', TRUE);
		
		// Physical Exam
		$pe_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'pe_date' => array('type' => 'TIMESTAMP'),
			'pe_gen1' => array('type' => 'LONGTEXT'),
			'pe_eye1' => array('type' => 'LONGTEXT'),
			'pe_eye2' => array('type' => 'LONGTEXT'),
			'pe_eye3' => array('type' => 'LONGTEXT'),
			'pe_ent1' => array('type' => 'LONGTEXT'),
			'pe_ent2' => array('type' => 'LONGTEXT'),
			'pe_ent3' => array('type' => 'LONGTEXT'),
			'pe_ent4' => array('type' => 'LONGTEXT'),
			'pe_ent5' => array('type' => 'LONGTEXT'),
			'pe_ent6' => array('type' => 'LONGTEXT'),
			'pe_neck1' => array('type' => 'LONGTEXT'),
			'pe_neck2' => array('type' => 'LONGTEXT'),
			'pe_resp1' => array('type' => 'LONGTEXT'),
			'pe_resp2' => array('type' => 'LONGTEXT'),
			'pe_resp3' => array('type' => 'LONGTEXT'),
			'pe_resp4' => array('type' => 'LONGTEXT'),
			'pe_cv1' => array('type' => 'LONGTEXT'),
			'pe_cv2' => array('type' => 'LONGTEXT'),
			'pe_cv3' => array('type' => 'LONGTEXT'),
			'pe_cv4' => array('type' => 'LONGTEXT'),
			'pe_cv5' => array('type' => 'LONGTEXT'),
			'pe_cv6' => array('type' => 'LONGTEXT'),
			'pe_ch1' => array('type' => 'LONGTEXT'),
			'pe_ch2' => array('type' => 'LONGTEXT'),
			'pe_gi1' => array('type' => 'LONGTEXT'),
			'pe_gi2' => array('type' => 'LONGTEXT'),
			'pe_gi3' => array('type' => 'LONGTEXT'),
			'pe_gi4' => array('type' => 'LONGTEXT'),
			'pe_gu1' => array('type' => 'LONGTEXT'),
			'pe_gu2' => array('type' => 'LONGTEXT'),
			'pe_gu3' => array('type' => 'LONGTEXT'),
			'pe_gu4' => array('type' => 'LONGTEXT'),
			'pe_gu5' => array('type' => 'LONGTEXT'),
			'pe_gu6' => array('type' => 'LONGTEXT'),
			'pe_gu7' => array('type' => 'LONGTEXT'),
			'pe_gu8' => array('type' => 'LONGTEXT'),
			'pe_gu9' => array('type' => 'LONGTEXT'),
			'pe_lymph1' => array('type' => 'LONGTEXT'),
			'pe_lymph2' => array('type' => 'LONGTEXT'),
			'pe_lymph3' => array('type' => 'LONGTEXT'),
			'pe_ms1' => array('type' => 'LONGTEXT'),
			'pe_ms2' => array('type' => 'LONGTEXT'),
			'pe_ms3' => array('type' => 'LONGTEXT'),
			'pe_ms4' => array('type' => 'LONGTEXT'),
			'pe_ms5' => array('type' => 'LONGTEXT'),
			'pe_ms6' => array('type' => 'LONGTEXT'),
			'pe_ms7' => array('type' => 'LONGTEXT'),
			'pe_ms8' => array('type' => 'LONGTEXT'),
			'pe_ms9' => array('type' => 'LONGTEXT'),
			'pe_ms10' => array('type' => 'LONGTEXT'),
			'pe_ms11' => array('type' => 'LONGTEXT'),
			'pe_ms12' => array('type' => 'LONGTEXT'),
			'pe_skin1' => array('type' => 'LONGTEXT'),
			'pe_skin2' => array('type' => 'LONGTEXT'),
			'pe_neuro1' => array('type' => 'LONGTEXT'),
			'pe_neuro2' => array('type' => 'LONGTEXT'),
			'pe_neuro3' => array('type' => 'LONGTEXT'),
			'pe_psych1' => array('type' => 'LONGTEXT'),
			'pe_psych2' => array('type' => 'LONGTEXT'),
			'pe_psych3' => array('type' => 'LONGTEXT'),
			'pe_psych4' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($pe_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('pe', TRUE);
		
		// Labs
		$labs_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'labs_date' => array('type' => 'TIMESTAMP'),
			'labs_ua_urobili' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_bilirubin' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_ketones' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_glucose' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_protein' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_nitrites' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_leukocytes' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_blood' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_ph' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_spgr' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_color' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_ua_clarity' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_upt' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_strep' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_mono' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_flu' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_microscope' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_glucose' => array('type' => 'VARCHAR', 'constraint' => 100),
			'labs_other' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($labs_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('labs', TRUE);
		
		// Procedure
		$procedure_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'proc_date' => array('type' => 'TIMESTAMP'),
			'proc_type' => array('type' => 'VARCHAR', 'constraint' => 100),
			'proc_cpt' => array('type' => 'VARCHAR', 'constraint' => 5),
			'proc_description' => array('type' => 'LONGTEXT'),
			'proc_complications' => array('type' => 'LONGTEXT'),
			'proc_ebl' => array('type' => 'VARCHAR', 'constraint' => 100)
		);
		$this->dbforge->add_field($procedure_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('procedure', TRUE);
		
		// Images
		$images_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'image_date' => array('type' => 'TIMESTAMP'),
			'image_location' => array('type' => 'VARCHAR', 'constraint' => 255),
			'image_description' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($images_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('images', TRUE);

		// Assessment
		$assessment_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'assessment_date' => array('type' => 'TIMESTAMP'),
			'assessment_icd1' => array('type' => 'VARCHAR', 'constraint' => 20),
			'assessment_icd2' => array('type' => 'VARCHAR', 'constraint' => 20),
			'assessment_icd3' => array('type' => 'VARCHAR', 'constraint' => 20),
			'assessment_icd4' => array('type' => 'VARCHAR', 'constraint' => 20),
			'assessment_icd5' => array('type' => 'VARCHAR', 'constraint' => 20),
			'assessment_icd6' => array('type' => 'VARCHAR', 'constraint' => 20),
			'assessment_icd7' => array('type' => 'VARCHAR', 'constraint' => 20),
			'assessment_icd8' => array('type' => 'VARCHAR', 'constraint' => 20),
			'assessment_1' => array('type' => 'LONGTEXT'),
			'assessment_2' => array('type' => 'LONGTEXT'),
			'assessment_3' => array('type' => 'LONGTEXT'),
			'assessment_4' => array('type' => 'LONGTEXT'),
			'assessment_5' => array('type' => 'LONGTEXT'),
			'assessment_6' => array('type' => 'LONGTEXT'),
			'assessment_7' => array('type' => 'LONGTEXT'),
			'assessment_8' => array('type' => 'LONGTEXT'),
			'assessment_other' => array('type' => 'LONGTEXT'),
			'assessment_ddx' => array('type' => 'LONGTEXT'),
			'assessment_notes' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($assessment_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('assessment', TRUE);

		// Orders
		$orders_definition = array(
			'orders_id' => array('type' => 'BIGINT', 'constraint' => 20, 'auto_increment' => TRUE),
			'address_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			't_messages_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'orders_date' => array('type' => 'TIMESTAMP'),
			'orders_insurance' => array('type' => 'VARCHAR', 'constraint' => 255),
			'orders_referrals' => array('type' => 'LONGTEXT'),
			'orders_labs' => array('type' => 'LONGTEXT'),
			'orders_radiology' => array('type' => 'LONGTEXT'),
			'orders_cp' => array('type' => 'LONGTEXT'),
			'orders_referrals_icd' => array('type' => 'VARCHAR', 'constraint' => 255),
			'orders_labs_icd' => array('type' => 'VARCHAR', 'constraint' => 255),
			'orders_radiology_icd' => array('type' => 'VARCHAR', 'constraint' => 255),
			'orders_cp_icd' => array('type' => 'VARCHAR', 'constraint' => 255),
			'orders_labs_obtained' => array('type' => 'VARCHAR', 'constraint' => 255),
			'orders_completed' => array('type' => 'TINYINT', 'constraint' => 4),
			'id' => array('type' => 'BIGINT', 'constraint' => 20)
		);
		$this->dbforge->add_field($orders_definition);
		$this->dbforge->add_key('orders_id', TRUE);
		$this->dbforge->create_table('orders', TRUE);
		
		// Medications
		$rx_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rx_date' => array('type' => 'TIMESTAMP'),
			'rx_rx' => array('type' => 'LONGTEXT'),
			'rx_supplements' => array('type' => 'LONGTEXT'),
			'rx_immunizations' => array('type' => 'LONGTEXT'),
			'rx_orders_summary' => array('type' => 'LONGTEXT'),
			'rx_supplements_orders_summary' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($rx_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('rx', TRUE);
		
		// Plan
		$plan_definition = array(
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'encounter_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'plan_date' => array('type' => 'TIMESTAMP'),
			'plan' => array('type' => 'LONGTEXT'),
			'duration' => array('type' => 'VARCHAR', 'constraint' => 100),
			'followup' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($plan_definition);
		$this->dbforge->add_key('eid', TRUE);
		$this->dbforge->create_table('plan', TRUE);
		
		// Immunizations
		$immunizations_definition = array(
			'imm_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_date' => array('type' => 'DATETIME'),
			'imm_immunization' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_sequence' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_body_site' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_dosage' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_dosage_unit' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_route' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_elsewhere' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_vis' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_lot' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_manufacturer' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_expiration' => array('type' => 'DATETIME'),
			'imm_brand' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_cvxcode' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_provider' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($immunizations_definition);
		$this->dbforge->add_key('imm_id', TRUE);
		$this->dbforge->create_table('immunizations', TRUE);
		
		// Prescriptions
		$rx_list_definition = array(
			'rxl_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'rxl_date_active' => array('type' => 'DATETIME'),
			'rxl_date_prescribed' => array('type' => 'DATETIME'),
			'rxl_medication' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_dosage' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_dosage_unit' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_sig' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_route' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_frequency' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_instructions' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_quantity' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_refill' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_reason' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_date_inactive' => array('type' => 'DATETIME'),
			'rxl_date_old' => array('type' => 'DATETIME'),
			'rxl_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'id' => array('type' => 'BIGINT', 'constraint' => 20),
			'rxl_dea' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_daw' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_license' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rxl_days' => array('type' => 'INT', 'constraint' => 4),
			'rxl_due_date' => array('type' => 'DATETIME'),
			'rcopia_sync' => array('type' => 'VARCHAR', 'constraint' => 4),
			'rxl_ndcid' => array('type' => 'VARCHAR', 'constraint' => 11)
		);
		$this->dbforge->add_field($rx_list_definition);
		$this->dbforge->add_key('rxl_id', TRUE);
		$this->dbforge->create_table('rx_list', TRUE);

		// Supplements
		$sup_list_definition = array(
			'sup_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'sup_date_active' => array('type' => 'DATETIME'),
			'sup_date_prescribed' => array('type' => 'DATETIME'),
			'sup_supplement' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_dosage' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_dosage_unit' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_sig' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_route' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_frequency' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_instructions' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_quantity' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_reason' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_date_inactive' => array('type' => 'DATETIME'),
			'sup_date_old' => array('type' => 'DATETIME'),
			'sup_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'id' => array('type' => 'BIGINT', 'constraint' => 20),
			'supplement_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($sup_list_definition);
		$this->dbforge->add_key('sup_id', TRUE);
		$this->dbforge->create_table('sup_list', TRUE);
		
		// Allergies
		$allergies_definition = array(
			'allergies_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'allergies_date_active' => array('type' => 'DATETIME'),
			'allergies_date_inactive' => array('type' => 'DATETIME'),
			'allergies_med' => array('type' => 'VARCHAR', 'constraint' => 255),
			'allergies_reaction' => array('type' => 'VARCHAR', 'constraint' => 255),
			'allergies_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rcopia_sync' => array('type' => 'VARCHAR', 'constraint' => 4),
			'meds_ndcid' => array('type' => 'VARCHAR', 'constraint' => 11),
			'allergies_severity' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($allergies_definition);
		$this->dbforge->add_key('allergies_id', TRUE);
		$this->dbforge->create_table('allergies', TRUE);
		
		// Issues
		$issues_definition = array(
			'issue_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'issue' => array('type' => 'VARCHAR', 'constraint' => 255),
			'issue_date_active' => array('type' => 'DATETIME'),
			'issue_date_inactive' => array('type' => 'DATETIME'),
			'issue_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'rcopia_sync' => array('type' => 'VARCHAR', 'constraint' => 4)
		);
		$this->dbforge->add_field($issues_definition);
		$this->dbforge->add_key('issue_id', TRUE);
		$this->dbforge->create_table('issues', TRUE);
		
		// Alerts
		$alerts_definition = array(
			'alert_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'orders_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'alert' => array('type' => 'VARCHAR', 'constraint' => 255),
			'alert_description' => array('type' => 'LONGTEXT'),
			'alert_date_active' => array('type' => 'DATETIME'),
			'alert_date_complete' => array('type' => 'DATETIME'),
			'alert_reason_not_complete' => array('type' => 'VARCHAR', 'constraint' => 255),
			'alert_provider' => array('type' => 'BIGINT', 'constraint' => 20),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($alerts_definition);
		$this->dbforge->add_key('alert_id', TRUE);
		$this->dbforge->create_table('alerts', TRUE);	
		
		// Telephone Messages
		$t_messages_definition = array(
			't_messages_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			't_messages_to' => array('type' => 'VARCHAR', 'constraint' => 255),
			't_messages_from' => array('type' => 'VARCHAR', 'constraint' => 255),
			't_messages_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			't_messages_signed' => array('type' => 'VARCHAR', 'constraint' => 4),
			't_messages_date' => array('type' => 'TIMESTAMP'),
			't_messages_dos' => array('type' => 'DATETIME'),
			't_messages_subject' => array('type' => 'VARCHAR', 'constraint' => 255),
			't_messages_message' => array('type' => 'LONGTEXT'),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($t_messages_definition);
		$this->dbforge->add_key('t_messages_id', TRUE);
		$this->dbforge->create_table('t_messages', TRUE);
		
		// Documents
		$documents_definition = array(
			'documents_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'documents_type' => array('type' => 'VARCHAR', 'constraint' => 255),
			'documents_url' => array('type' => 'VARCHAR', 'constraint' => 255),
			'documents_desc' => array('type' => 'VARCHAR', 'constraint' => 255),
			'documents_from' => array('type' => 'VARCHAR', 'constraint' => 255),
			'documents_viewed' => array('type' => 'VARCHAR', 'constraint' => 20),
			'documents_date' => array('type' => 'DATETIME')
		);
		$this->dbforge->add_field($documents_definition);
		$this->dbforge->add_key('documents_id', TRUE);
		$this->dbforge->create_table('documents', TRUE);
		
		// Insurance
		$insurance_definition = array(
			'insurance_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'address_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'insurance_plan_name' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_order' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_id_num' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_group' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_relationship' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_copay' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_deductible' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_comments' => array('type' => 'LONGTEXT'),
			'insurance_plan_active' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_insu_firstname' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_insu_lastname' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_insu_address' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_insu_city' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_insu_state' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_insu_zip' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_insu_phone' => array('type' => 'VARCHAR', 'constraint' => 255),
			'insurance_insu_dob' => array('type' => 'DATETIME'),
			'insurance_insu_gender' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($insurance_definition);
		$this->dbforge->add_key('insurance_id', TRUE);
		$this->dbforge->create_table('insurance', TRUE);
		
		// Billing
		$billing_definition = array(
			'bill_id' => array('type' => 'BIGINT', 'constraint' => 20, 'auto_increment' => TRUE),
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'insurance_id_1' => array('type' => 'INT', 'constraint' => 11),
			'insurance_id_2' => array('type' => 'INT', 'constraint' => 11),
			'bill_date' => array('type' => 'TIMESTAMP'),
			'bill_complex' => array('type' => 'VARCHAR', 'constraint' => 255),
			'bill_Box11C' => array('type' => 'VARCHAR', 'constraint' => 29), //Insurance Plan Name
			'bill_payor_id' => array('type' => 'VARCHAR', 'constraint' => 5),
			'bill_ins_add1' => array('type' => 'VARCHAR', 'constraint' => 29),
			'bill_ins_add2' => array('type' => 'VARCHAR', 'constraint' => 29),
			'bill_Box1' => array('type' => 'VARCHAR', 'constraint' => 45),
			'bill_Box1P' => array('type' => 'VARCHAR', 'constraint' => 45),
			'bill_Box1A' => array('type' => 'VARCHAR', 'constraint' => 29), //Insured ID Number
			'bill_Box2' => array('type' => 'VARCHAR', 'constraint' => 28), //Patient Name
			'bill_Box3A' => array('type' => 'VARCHAR', 'constraint' => 10), //Patient Date of Birth
			'bill_Box3B' => array('type' => 'VARCHAR', 'constraint' => 6), //Patient Sex
			'bill_Box3BP' => array('type' => 'VARCHAR', 'constraint' => 6),
			'bill_Box4' => array('type' => 'VARCHAR', 'constraint' => 29), //Insured Name
			'bill_Box5A' => array('type' => 'VARCHAR', 'constraint' => 28), //Patient Address
			'bill_Box6' => array('type' => 'VARCHAR', 'constraint' => 15), //Patient Relationship to Insured
			'bill_Box6P' => array('type' => 'VARCHAR', 'constraint' => 15),
			'bill_Box7A' => array('type' => 'VARCHAR', 'constraint' => 29), //Insured Address
			'bill_Box5B' => array('type' => 'VARCHAR', 'constraint' => 24), //Patient City
			'bill_Box5C' => array('type' => 'VARCHAR', 'constraint' => 3), //Patient State
			'bill_Box8A' => array('type' => 'VARCHAR', 'constraint' => 13), //Patient Marital Status
			'bill_Box8AP' => array('type' => 'VARCHAR', 'constraint' => 13),
			'bill_Box7B' => array('type' => 'VARCHAR', 'constraint' => 23), //Insured City
			'bill_Box7C' => array('type' => 'VARCHAR', 'constraint' => 4), //Insured State
			'bill_Box5D' => array('type' => 'VARCHAR', 'constraint' => 12), //Patient Zip
			'bill_Box5E' => array('type' => 'VARCHAR', 'constraint' => 14), //Patient Phone
			'bill_Box8B' => array('type' => 'VARCHAR', 'constraint' => 13), //Patient Employment
			'bill_Box8BP' => array('type' => 'VARCHAR', 'constraint' => 13),
			'bill_Box7D' => array('type' => 'VARCHAR', 'constraint' => 12), //Insured Zip
			'bill_Box7E' => array('type' => 'VARCHAR', 'constraint' => 14), //Insured Phone
			'bill_Box9' => array('type' => 'VARCHAR', 'constraint' => 28), //Other Insured Name
			'bill_Box11' => array('type' => 'VARCHAR', 'constraint' => 29), //Insured Group Number
			'bill_Box9A' => array('type' => 'VARCHAR', 'constraint' => 28), //Other Insured Group Number
			'bill_Box10' => array('type' => 'VARCHAR', 'constraint' => 19),
			'bill_Box10A' => array('type' => 'VARCHAR', 'constraint' => 7), //Condition Employment
			'bill_Box10AP' => array('type' => 'VARCHAR', 'constraint' => 7), //Condition Employment
			'bill_Box11A1' => array('type' => 'VARCHAR', 'constraint' => 10), //Insured Date of Birth
			'bill_Box11A2' => array('type' => 'VARCHAR', 'constraint' => 8), //Insured Sex
			'bill_Box11A2P' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_Box9B1' => array('type' => 'VARCHAR', 'constraint' => 10), //Other Insured Date of Birth
			'bill_Box9B2' => array('type' => 'VARCHAR', 'constraint' => 7), //Other Insured Sex
			'bill_Box9B2P' => array('type' => 'VARCHAR', 'constraint' => 7),
			'bill_Box10B1' => array('type' => 'VARCHAR', 'constraint' => 7), //Condition Auto Accident
			'bill_Box10B1P' => array('type' => 'VARCHAR', 'constraint' => 7), //Condition Auto Accident
			'bill_Box10B2' => array('type' => 'VARCHAR', 'constraint' => 3), //Condition Auto Accident State
			'bill_Box11B' => array('type' => 'VARCHAR', 'constraint' => 29), //Insured Employer
			'bill_Box9C' => array('type' => 'VARCHAR', 'constraint' => 28), //Other Insured Employer
			'bill_Box10C' => array('type' => 'VARCHAR', 'constraint' => 7), //Condition Other Accident
			'bill_Box10CP' => array('type' => 'VARCHAR', 'constraint' => 7), //Condition Other Accident
			'bill_Box9D' => array('type' => 'VARCHAR', 'constraint' => 28), //Other Insurance Plan Name
			'bill_Box11D' => array('type' => 'VARCHAR', 'constraint' => 6), //Other Insurance Plan Exist
			'bill_Box11DP' => array('type' => 'VARCHAR', 'constraint' => 6), //Other Insurance Plan Exist
			'bill_Box17' => array('type' => 'VARCHAR', 'constraint' => 26), //Provider Use for Box 31 and 33B too
			'bill_Box17A' => array('type' => 'VARCHAR', 'constraint' => 17), //Provider NPI
			'bill_Box21_1' => array('type' => 'VARCHAR', 'constraint' => 8), //ICD1
			'bill_Box21_2' => array('type' => 'VARCHAR', 'constraint' => 8), //ICD2
			'bill_Box21_3' => array('type' => 'VARCHAR', 'constraint' => 8), //ICD3
			'bill_Box21_4' => array('type' => 'VARCHAR', 'constraint' => 8), //ICD4
			'bill_DOS1F' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS1T' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS2F' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS2T' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS3F' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS3T' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS4F' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS4T' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS5F' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS5T' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS6F' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_DOS6T' => array('type' => 'VARCHAR', 'constraint' => 8),
			'bill_Box24B1' => array('type' => 'VARCHAR', 'constraint' => 5),	//Place of Service 1
			'bill_Box24B2' => array('type' => 'VARCHAR', 'constraint' => 5),	//Place of Service 2
			'bill_Box24B3' => array('type' => 'VARCHAR', 'constraint' => 5),	//Place of Service 3
			'bill_Box24B4' => array('type' => 'VARCHAR', 'constraint' => 5),	//Place of Service 4
			'bill_Box24B5' => array('type' => 'VARCHAR', 'constraint' => 5),	//Place of Service 5
			'bill_Box24B6' => array('type' => 'VARCHAR', 'constraint' => 5),	//Place of Service 6
			'bill_Box24D1' => array('type' => 'VARCHAR', 'constraint' => 6),	//CPT1
			'bill_Box24D2' => array('type' => 'VARCHAR', 'constraint' => 6),	//CPT2
			'bill_Box24D3' => array('type' => 'VARCHAR', 'constraint' => 6),	//CPT3
			'bill_Box24D4' => array('type' => 'VARCHAR', 'constraint' => 6),	//CPT4
			'bill_Box24D5' => array('type' => 'VARCHAR', 'constraint' => 6),	//CPT5
			'bill_Box24D6' => array('type' => 'VARCHAR', 'constraint' => 6),	//CPT6
			'bill_Modifier1' => array('type' => 'VARCHAR', 'constraint' => 11),
			'bill_Modifier2' => array('type' => 'VARCHAR', 'constraint' => 11),
			'bill_Modifier3' => array('type' => 'VARCHAR', 'constraint' => 11),
			'bill_Modifier4' => array('type' => 'VARCHAR', 'constraint' => 11),
			'bill_Modifier5' => array('type' => 'VARCHAR', 'constraint' => 11),
			'bill_Modifier6' => array('type' => 'VARCHAR', 'constraint' => 11),
			'bill_Box24E1' => array('type' => 'VARCHAR', 'constraint' => 4), //Diagnosis Pointer 1
			'bill_Box24E2' => array('type' => 'VARCHAR', 'constraint' => 4), //Diagnosis Pointer 2
			'bill_Box24E3' => array('type' => 'VARCHAR', 'constraint' => 4), //Diagnosis Pointer 3
			'bill_Box24E4' => array('type' => 'VARCHAR', 'constraint' => 4), //Diagnosis Pointer 4
			'bill_Box24E5' => array('type' => 'VARCHAR', 'constraint' => 4), //Diagnosis Pointer 5
			'bill_Box24E6' => array('type' => 'VARCHAR', 'constraint' => 4), //Diagnosis Pointer 6
			'bill_Box24F1' => array('type' => 'VARCHAR', 'constraint' => 8), //Charges 1
			'bill_Box24F2' => array('type' => 'VARCHAR', 'constraint' => 8), //Charges 2
			'bill_Box24F3' => array('type' => 'VARCHAR', 'constraint' => 8), //Charges 3
			'bill_Box24F4' => array('type' => 'VARCHAR', 'constraint' => 8), //Charges 4
			'bill_Box24F5' => array('type' => 'VARCHAR', 'constraint' => 8), //Charges 5
			'bill_Box24F6' => array('type' => 'VARCHAR', 'constraint' => 8), //Charges 6
			'bill_Box24G1' => array('type' => 'VARCHAR', 'constraint' => 5), //Units 1
			'bill_Box24G2' => array('type' => 'VARCHAR', 'constraint' => 5), //Units 2
			'bill_Box24G3' => array('type' => 'VARCHAR', 'constraint' => 5), //Units 3
			'bill_Box24G4' => array('type' => 'VARCHAR', 'constraint' => 5), //Units 4
			'bill_Box24G5' => array('type' => 'VARCHAR', 'constraint' => 5), //Units 5
			'bill_Box24G6' => array('type' => 'VARCHAR', 'constraint' => 5), //Units 6
			'bill_Box24J1' => array('type' => 'VARCHAR', 'constraint' => 11), //NPI 1
			'bill_Box24J2' => array('type' => 'VARCHAR', 'constraint' => 11), //NPI 2
			'bill_Box24J3' => array('type' => 'VARCHAR', 'constraint' => 11), //NPI 3
			'bill_Box24J4' => array('type' => 'VARCHAR', 'constraint' => 11), //NPI 4
			'bill_Box24J5' => array('type' => 'VARCHAR', 'constraint' => 11), //NPI 5
			'bill_Box24J6' => array('type' => 'VARCHAR', 'constraint' => 11), //NPI 6
			'bill_Box25' => array('type' => 'VARCHAR', 'constraint' => 15), //Clinic Tax ID
			'bill_Box26' => array('type' => 'VARCHAR', 'constraint' => 14), //pid
			'bill_Box27' => array('type' => 'VARCHAR', 'constraint' => 6), //Accept Assignment
			'bill_Box27P' => array('type' => 'VARCHAR', 'constraint' => 6),
			'bill_Box28' => array('type' => 'VARCHAR', 'constraint' => 9), //Total Charges
			'bill_Box29' => array('type' => 'VARCHAR', 'constraint' => 8), //Amount Paid
			'bill_Box30' => array('type' => 'VARCHAR', 'constraint' => 8), //Balance Due
			'bill_Box31' => array('type' => 'VARCHAR', 'constraint' => 21),
			'bill_Box32A' => array('type' => 'VARCHAR', 'constraint' => 26), //Clinic Name
			'bill_Box32B' => array('type' => 'VARCHAR', 'constraint' => 26), //Clinic Address 1
			'bill_Box32C' => array('type' => 'VARCHAR', 'constraint' => 26), //Clinic Address 2
			'bill_Box32D' => array('type' => 'VARCHAR', 'constraint' => 10), //Clinic NPI use for 33E too
			'bill_Box33A' => array('type' => 'VARCHAR', 'constraint' => 14), //Clinic Phone
			'bill_Box33B' => array('type' => 'VARCHAR', 'constraint' => 29),
			'bill_Box33C' => array('type' => 'VARCHAR', 'constraint' => 29), //Billing Address 1
			'bill_Box33D' => array('type' => 'VARCHAR', 'constraint' => 29), //Billing Address 2
			'bill_Box33E' => array('type' => 'VARCHAR', 'constraint' => 10)
		);
		$this->dbforge->add_field($billing_definition);
		$this->dbforge->add_key('bill_id', TRUE);
		$this->dbforge->create_table('billing', TRUE);
		
		// Core Billing
		$billing_core_definition = array(
			'billing_core_id' => array('type' => 'BIGINT', 'constraint' => 20, 'auto_increment' => TRUE),
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'other_billing_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 5),
			'cpt_charge' => array('type' => 'VARCHAR', 'constraint' => 6),
			'icd_pointer' => array('type' => 'VARCHAR', 'constraint' => 4),
			'unit' => array('type' => 'VARCHAR', 'constraint' => 1),
			'modifier' => array('type' => 'VARCHAR', 'constraint' => 2),
			'dos_f' => array('type' => 'VARCHAR', 'constraint' => 10),
			'dos_t' => array('type' => 'VARCHAR', 'constraint' => 10),
			'billing_group' => array('type' => 'VARCHAR', 'constraint' => 1),
			'payment' => array('type' => 'VARCHAR', 'constraint' => 6),
			'reason' => array('type' => 'VARCHAR', 'constraint' => 255),
			'payment_type' => array('type' => 'VARCHAR', 'constraint' => 255),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($billing_core_definition);
		$this->dbforge->add_key('billing_core_id', TRUE);
		$this->dbforge->create_table('billing_core', TRUE);
				
		// HIPPA
		$hippa_definition = array(
			'hippa_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'hippa_date_release' => array('type' => 'DATETIME'),
			'hippa_reason' => array('type' => 'VARCHAR', 'constraint' => 255),
			'hippa_provider' => array('type' => 'VARCHAR', 'constraint' => 255),
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			't_messages_id' => array('type' => 'INT', 'constraint' => 11),
			'documents_id' => array('type' => 'INT', 'constraint' => 11),
			'other_hippa_id' => array('type' => 'INT', 'constraint' => 11),
			'hippa_role' => array('type' => 'VARCHAR', 'constraint' => 100),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($hippa_definition);
		$this->dbforge->add_key('hippa_id', TRUE);
		$this->dbforge->create_table('hippa', TRUE);
		
		// Procedure Template
		$procedurelist_definition = array(
			'procedurelist_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'user_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'procedure_type' => array('type' => 'VARCHAR', 'constraint' => 255),
			'procedure_description' => array('type' => 'LONGTEXT'),
			'procedure_complications' => array('type' => 'LONGTEXT'),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 255),
			'procedure_ebl' => array('type' => 'VARCHAR', 'constraint' => 100),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($procedurelist_definition);
		$this->dbforge->add_key('procedurelist_id', TRUE);
		$this->dbforge->create_table('procedurelist', TRUE);
		
		// Orders Template
		$orderslist_definition = array(
			'orderslist_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'user_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'orders_category' => array('type' => 'VARCHAR', 'constraint' => 255),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 255),
			'orders_description' => array('type' => 'LONGTEXT'),
			'snomed' => array('type' => 'VARCHAR', 'constraint' => 255),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($orderslist_definition);
		$this->dbforge->add_key('orderslist_id', TRUE);
		$this->dbforge->create_table('orderslist', TRUE);
		
		// Vaccine Temperatures
		$vaccine_temp_definition = array(
			'temp_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'date' => array('type' => 'DATETIME'),
			'temp' => array('type' => 'VARCHAR', 'constraint' => 100),
			'action' => array('type' => 'LONGTEXT'),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($vaccine_temp_definition);
		$this->dbforge->add_key('temp_id', TRUE);
		$this->dbforge->create_table('vaccine_temp', TRUE);
		
		// Vaccine Inventory
		$vaccine_inventory_definition = array(
			'vaccine_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'date_purchase' => array('type' => 'DATETIME'),
			'imm_immunization' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_lot' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_manufacturer' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_expiration' => array('type' => 'DATETIME'),
			'imm_brand' => array('type' => 'VARCHAR', 'constraint' => 255),
			'imm_cvxcode' => array('type' => 'VARCHAR', 'constraint' => 255),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 255),
			'quantity' => array('type' => 'INT', 'constraint' => 11),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($vaccine_inventory_definition);
		$this->dbforge->add_key('vaccine_id', TRUE);
		$this->dbforge->create_table('vaccine_inventory', TRUE);
		
		// Supplements Inventory
		$supplement_inventory_definition = array(
			'supplement_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'date_purchase' => array('type' => 'DATETIME'),
			'sup_description' => array('type' => 'LONGTEXT'),
			'sup_strength' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_manufacturer' => array('type' => 'VARCHAR', 'constraint' => 255),
			'sup_expiration' => array('type' => 'DATETIME'),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 255),
			'charge' => array('type' => 'VARCHAR', 'constraint' => 255),
			'quantity' => array('type' => 'INT', 'constraint' => 11),
			'sup_lot' => array('type' => 'VARCHAR', 'constraint' => 255),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($supplement_inventory_definition);
		$this->dbforge->add_key('supplement_id', TRUE);
		$this->dbforge->create_table('supplement_inventory', TRUE);
		
		// Scans
		$scans_definition = array(
			'scans_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'fileName' => array('type' => 'VARCHAR', 'constraint' => 255),
			'filePath' => array('type' => 'VARCHAR', 'constraint' => 255),
			'fileDateTime' => array('type' => 'DATETIME'),
			'filePages' => array('type' => 'INT', 'constraint' => 11),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($scans_definition);
		$this->dbforge->add_key('scans_id', TRUE);
		$this->dbforge->create_table('scans', TRUE);
		
		// Test Results
		$tests_definition = array(
			'tests_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'pid' => array('type' => 'BIGINT', 'constraint' => 20),
			'test_name' => array('type' => 'VARCHAR', 'constraint' => 255),
			'test_datetime' => array('type' => 'DATETIME'),
			'test_result' => array('type' => 'LONGTEXT'),
			'test_units' => array('type' => 'VARCHAR', 'constraint' => 100),
			'test_reference' => array('type' => 'LONGTEXT'),
			'test_flags' => array('type' => 'VARCHAR', 'constraint' => 100),
			'test_provider_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'test_unassigned' => array('type' => 'LONGTEXT'),
			'test_from' => array('type' => 'LONGTEXT'),
			'test_type' => array('type' => 'VARCHAR', 'constraint' => 255),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($tests_definition);
		$this->dbforge->add_key('tests_id', TRUE);
		$this->dbforge->create_table('tests', TRUE);
		
		// Templates
		$template_definition = array(
			'template_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'user_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'default' => array('type' => 'VARCHAR', 'constraint' => 100),
			'template_name' => array('type' => 'VARCHAR', 'constraint' => 100),
			'age' => array('type' => 'VARCHAR', 'constraint' => 100),
			'category' => array('type' => 'VARCHAR', 'constraint' => 100),
			'sex' => array('type' => 'VARCHAR', 'constraint' => 100),
			'group' => array('type' => 'VARCHAR', 'constraint' => 100),
			'array' => array('type' => 'LONGTEXT'),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($template_definition);
		$this->dbforge->add_key('template_id', TRUE);
		$this->dbforge->create_table('templates', TRUE);
		$template_sql_file = "/var/www/nosh/import/templates.sql";
		include(APPPATH.'config/database'.EXT);
		$template_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $template_sql_file;
		system($template_command);
		
		$group_id = '1';
		$displayname = 'Administrator';
		
		// Tags
		$tags_definition = array(
			'tags_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'tag' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($tags_definition);
		$this->dbforge->add_key('tags_id', TRUE);
		$this->dbforge->create_table('tags', TRUE);
		
		// Tags Relationship
		$tags_relate_definition = array(
			'tags_relate_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'tags_id' => array('type' => 'INT', 'constraint' => 40),
			'pid' => array('type' => 'INT', 'constraint' => 11),
			'eid' => array('type' => 'BIGINT', 'constraint' => 20),
			't_messages_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'message_id' => array('type' => 'INT', 'constraint' => 11),
			'documents_id' => array('type' => 'INT', 'constraint' => 11),
			'hippa_id' => array('type' => 'INT', 'constraint' => 11),
			'appt_id' => array('type' => 'INT', 'constraint' => 11),
			'tests_id' => array('type' => 'INT', 'constraint' => 11),
			'mtm_id' => array('type' => 'INT', 'constraint' => 40),
			'practice_id' => array('type' => 'INT', 'constraint' => 11)
		);
		$this->dbforge->add_field($tags_relate_definition);
		$this->dbforge->add_key('tags_relate_id', TRUE);
		$this->dbforge->create_table('tags_relate', TRUE);
		
		// Forms
		$forms_definition = array(
			'forms_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'pid' => array('type' => 'INT', 'constraint' => 11),
			'template_id' => array('type' => 'INT', 'constraint' => 11),
			'forms_date' => array('type' => 'TIMESTAMP'),
			'forms_title' => array('type' => 'VARCHAR', 'constraint' => 255),
			'forms_content' => array('type' => 'LONGTEXT'),
			'forms_content_text' => array('type' => 'LONGTEXT'),
			'forms_destination' => array('type' => 'VARCHAR', 'constraint' => 255),
			'array' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($forms_definition);
		$this->dbforge->add_key('forms_id', TRUE);
		$this->dbforge->create_table('forms', TRUE);
		
		// Relational table for demographics
		$demographics_relate_definition = array(
			'demographics_relate_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'pid' => array('type' => 'INT', 'constraint' => 11),
			'practice_id' => array('type' => 'INT', 'constraint' => 11),
			'id' => array('type' => 'BIGINT', 'constraint' => 20)
		);
		$this->dbforge->add_field($demographics_relate_definition);
		$this->dbforge->add_key('demographics_relate_id', TRUE);
		$this->dbforge->create_table('demographics_relate', TRUE);
		
		$demographics_notes_definition = array(
			'demographics_notes_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'pid' => array('type' => 'INT', 'constraint' => 11),
			'practice_id' => array('type' => 'INT', 'constraint' => 11),
			'billing_notes' => array('type' => 'LONGTEXT'),
			'imm_notes' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($demographics_notes_definition);
		$this->dbforge->add_key('demographics_notes_id', TRUE);
		$this->dbforge->create_table('demographics_notes', TRUE);
		
		$orderslist1_definition = array(
			'orderslist1_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'orders_code' => array('type' => 'BIGINT', 'constraint' => 20),
			'orders_category' => array('type' => 'VARCHAR', 'constraint' => 255),
			'orders_vendor' => array('type' => 'VARCHAR', 'constraint' => 255),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 255),
			'orders_description' => array('type' => 'LONGTEXT'),
			'result_code' => array('type' => 'BIGINT', 'constraint' => 20),
			'result_name' => array('type' => 'VARCHAR', 'constraint' => 255),
			'units' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($orderslist1_definition);
		$this->dbforge->add_key('orderslist1_id', TRUE);
		$this->dbforge->create_table('orderslist1', TRUE);
		$orderslist1_sql_file = "/var/www/nosh/import/orderslist1.sql";
		include(APPPATH.'config/database'.EXT);
		$orderslist1_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $orderslist1_sql_file;
		system($orderslist1_command);
		
		// Insert Administrator
		$data1 = array(
			'username' => $username,
			'password' => $password,
			'email' => $email,
			'group_id' => $group_id,
			'displayname' => $displayname,
			'active' => '1',
			'practice_id' => '1'
		);
		$this->db->insert('users', $data1);
		
		// Clean up documents directory string
		$check_string = substr($documents_dir1, -1);
		if ($check_string != '/') {
			$documents_dir1 .= '/';
		}
		
		// Insert some settings
		$data2 = array(
			'practice_name' => $practice_name,
			'street_address1' => $street_address1,
			'street_address2' => $street_address2,
			'city' => $city,
			'state' => $state,
			'zip' => $zip,
			'phone' => $phone,
			'fax' => $fax,
			'documents_dir' => $documents_dir1,
			'fax_type' => '',
			'smtp_user' => $smtp_user,
			'smtp_pass' => $smtp_pass,
			'vivacare' => '',
			'version' => '1.7.3',
			'active' => 'Y'
		);
		$this->db->insert('practiceinfo', $data2);
		$scans_directory = $documents_dir1 . 'scans';
		mkdir($scans_directory, 0777);
		$data3 = array(
			'id' => '1',
			'title' => 'admin',
			'description' => 'Administrator'
		);
		$this->db->insert('groups', $data3);
		$data4 = array(
			'id' => '2',
			'title' => 'provider',
			'description' => 'Provider'
		);
		$this->db->insert('groups', $data4);
		$data5 = array(
			'id' => '3',
			'title' => 'assistant',
			'description' => 'Assistant'
		);
		$this->db->insert('groups', $data5);
		$data6 = array(
			'id' => '4',
			'title' => 'billing',
			'description' => 'Billing'
		);
		$this->db->insert('groups', $data6);
		$data7 = array(
			'id' => '100',
			'title' => 'patient',
			'description' => 'Patient'
		);
		$this->db->insert('groups', $data7);
		$data8 = array(
			'visit_type' => 'Closed',
			'classname' => 'colorblack',
			'active' => 'y',
			'practice_id' => '1'
		);
		$this->db->insert('calendar', $data8);
		
		// Insert default values for procedure template
		$defaultdata1 = array(
			'procedure_type' => 'Laceration repair',
			'procedure_description' => '',
			'procedure_complications' => 'None.',
			'procedure_ebl' => 'Less than 5 mL.'
		);
		$this->db->insert('procedurelist', $defaultdata1);
		$defaultdata2 = array(
			'procedure_type' => 'Excision - lesion completely removed',
			'procedure_description' => '',
			'procedure_complications' => 'None.',
			'procedure_ebl' => 'Less than 5 mL.'
		);
		$this->db->insert('procedurelist', $defaultdata2);
		$defaultdata3 = array(
			'procedure_type' => 'Shave - no penetration of fat, no sutures',
			'procedure_description' => '',
			'procedure_complications' => 'None.',
			'procedure_ebl' => 'Less than 5 mL.'
		);
		$this->db->insert('procedurelist', $defaultdata3);
		$defaultdata4 = array(
			'procedure_type' => 'Biopsy - lesion partially removed',
			'procedure_description' => '',
			'procedure_complications' => 'None.',
			'procedure_ebl' => 'Less than 5 mL.'
		);
		$this->db->insert('procedurelist', $defaultdata4);
		
		$defaultdata5 = array(
			'procedure_type' => 'Skin tag removal',
			'procedure_description' => '' ,
			'procedure_complications' => 'None.',
			'procedure_ebl' => 'Less than 5 mL.'
		);
		$this->db->insert('procedurelist', $defaultdata5);
		$defaultdata6 = array(
			'procedure_type' => 'Cryotherapy',
			'procedure_description' => '',
			'procedure_complications' => 'None.',
			'procedure_ebl' => 'Less than 5 mL.'
		);
		$this->db->insert('procedurelist', $defaultdata6);
		
		// Insert default values for orders template
		$defaultdata7 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Comprehensive metabolic panel (CMP)',
			'snomed' => '167209002'
		);
		$this->db->insert('orderslist', $defaultdata7);
		$defaultdata8 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Complete blood count with platelets and differential (CBC)',
			'snomed' => '117356000'
		);
		$this->db->insert('orderslist', $defaultdata8);
		$defaultdata9 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Antinuclear antibody panel (ANA)',
			'snomed' => '394977005'
		);
		$this->db->insert('orderslist', $defaultdata9);
		$defaultdata10 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Fasting lipid panel',
			'snomed' => '394977005'
		);
		$this->db->insert('orderslist', $defaultdata10);
		$defaultdata11 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Erythrocyte sedimentation rate (ESR)',
			'snomed' => '104155006'
		);
		$this->db->insert('orderslist', $defaultdata11);
		$defaultdata12 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Hemoglobin A1c (HgbA1c)',
			'snomed' => '166902009'
		);
		$this->db->insert('orderslist', $defaultdata12);
		$defaultdata13 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'INR',
			'snomed' => '440685005'
		);
		$this->db->insert('orderslist', $defaultdata13);
		$defaultdata14 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Liver function panel (LFT)',
			'snomed' => '143927001'
		);
		$this->db->insert('orderslist', $defaultdata14);
		$defaultdata15 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Pap smear with HPV testing',
			'snomed' => '119252009'
		);
		$this->db->insert('orderslist', $defaultdata15);
		$defaultdata16 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Pap smear',
			'snomed' => '119252009'
		);
		$this->db->insert('orderslist', $defaultdata16);
		$defaultdata17 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Prostate specific antigen (PSA)',
			'snomed' => '143526001'
		);
		$this->db->insert('orderslist', $defaultdata17);
		$defaultdata18 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Hepatitis C antibody',
			'snomed' => '166123004'
		);
		$this->db->insert('orderslist', $defaultdata18);
		$defaultdata19 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'RPR',
			'snomed' => '19869000'
		);
		$this->db->insert('orderslist', $defaultdata19);
		$defaultdata20 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Peripheral smear',
			'snomed' => '104130000'
		);
		$this->db->insert('orderslist', $defaultdata20);
		$defaultdata21 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Follicle stimulating hormone (FSH)',
			'snomed' => '273971007'
		);
		$this->db->insert('orderslist', $defaultdata21);
		$defaultdata22 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Luteinizing hormone (LH)',
			'snomed' => '69527006'
		);
		$this->db->insert('orderslist', $defaultdata22);
		$defaultdata23 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Follicle stimulating hormone and Leutinizing hormone (FSH and LH)',
			'snomed' => '250660006'
		);
		$this->db->insert('orderslist', $defaultdata23);
		$defaultdata24 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Gonorrhea and Chlamydia GenProbe (GC/Chl PCR)',
			'snomed' => '399143002'
		);
		$this->db->insert('orderslist', $defaultdata24);
		$defaultdata25 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Thyroid stimulating hormone (TSH)',
			'snomed' => '313440008'
		);
		$this->db->insert('orderslist', $defaultdata25);
		
		$defaultdata26 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Thyroid panel (TSH, T3, Free T4)',
			'snomed' => '35650009'
		);
		$this->db->insert('orderslist', $defaultdata26);
		$defaultdata27 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Urinalysis',
			'snomed' => '53853004'
		);
		$this->db->insert('orderslist', $defaultdata27);
		$defaultdata28 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Urine culture',
			'snomed' => '144792004'
		);
		$this->db->insert('orderslist', $defaultdata28);
		$defaultdata29 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Wound culture',
			'snomed' => '77601007'
		);
		$this->db->insert('orderslist', $defaultdata29);
		$defaultdata30 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Respiratory Allergen Testing',
			'snomed' => '388464003'
		);
		$this->db->insert('orderslist', $defaultdata30);
		$defaultdata31 = array(
			'orders_category' => 'Laboratory',
			'orders_description' => 'Herpes Type 2 antibody',
			'snomed' => '117739006'
		);
		$this->db->insert('orderslist', $defaultdata31);
		$defaultdata32 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'CT of the abdomen with contrast',
			'snomed' => '32962002'
		);
		$this->db->insert('orderslist', $defaultdata32);
		$defaultdata33 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'CT of the abdomen without contrast',
			'snomed' => '169070004'
		);
		$this->db->insert('orderslist', $defaultdata33);
		$defaultdata34 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'CT of the chest with contrast',
			'snomed' => '75385009'
		);
		$this->db->insert('orderslist', $defaultdata34);
		$defaultdata35 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'CT of the chest without contrast',
			'snomed' => '169069000'
		);
		$this->db->insert('orderslist', $defaultdata35);
		$defaultdata36 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'CT of the head with contrast',
			'snomed' => '396207002'
		);
		$this->db->insert('orderslist', $defaultdata36);
		$defaultdata37 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'CT of the head without contrast',
			'snomed' => '396205005'
		);
		$this->db->insert('orderslist', $defaultdata37);
		$defaultdata38 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'CT of the sinuses',
			'snomed' => '431247005'
		);
		$this->db->insert('orderslist', $defaultdata38);
		$defaultdata39 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'CT of the neck with contrast',
			'snomed' => '431326009'
		);
		$this->db->insert('orderslist', $defaultdata39);
		$defaultdata40 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'CT of the neck without contrast',
			'snomed' => '169068008'
		);
		$this->db->insert('orderslist', $defaultdata40);
		$defaultdata41 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'DEXA scan',
			'snomed' => '300004007'
		);
		$this->db->insert('orderslist', $defaultdata41);
		$defaultdata42 = array(
			'orders_category' => 'Radiology',
			'orders_description' => 'Bilateral screening mammogram',
			'snomed' => '275980005'
		);
		$this->db->insert('orderslist', $defaultdata42);
		$defaultdata43 = array(
			'orders_category' => 'Frequency',
			'orders_description' => 'Once a week'
		);
		$this->db->insert('orderslist', $defaultdata43);
		$defaultdata44 = array(
			'orders_category' => 'Frequency',
			'orders_description' => 'Two times a week'
		);
		$this->db->insert('orderslist', $defaultdata44);
		$defaultdata45 = array(
			'orders_category' => 'Frequency',
			'orders_description' => 'Three times a week'
		);
		$this->db->insert('orderslist', $defaultdata45);
		$orderslist_data = array(
			'user_id' => '0'
		);
		$this->db->update('orderslist', $orderslist_data);
		$template_array = array();
		$template_array[] = array(
			'category' => 'letter',
			'json' => '{"html":[{"type":"div","class":"letter_buttonset","id":"letter_school_absence_1_div","html":[{"type":"span","html":"Start Date:"},{"type":"br"},{"type":"text","id":"letter_school_absence_1a","class":"letter_date letter_start_date","css":{"width":"200px"},"name":"letter_school_absence_1","caption":""}]},{"type":"br"},{"type":"div","class":"letter_buttonset","id":"letter_school_absence_2_div","html":[{"type":"span","html":"Return Date:"},{"type":"br"},{"type":"text","id":"letter_school_absence_2a","class":"letter_date letter_return_date","css":{"width":"200px"},"name":"letter_school_absence_2","caption":""}]},{"type":"hidden","class":"letter_hidden","value":"Please excuse _firstname from school starting on _start_date.  _firstname can return to school on _return_date.","id":"letter_school_absence_hidden"}]}',
			'group' => 'school_absence',
			'sex' => 'm'
		);
		$template_array[] = array(
			'category' => 'letter',
			'json' => '{"html":[{"type":"div","class":"letter_buttonset","id":"letter_school_absence_1_div","html":[{"type":"span","html":"Start Date:"},{"type":"br"},{"type":"text","id":"letter_school_absence_1a","class":"letter_date letter_start_date","css":{"width":"200px"},"name":"letter_school_absence_1","caption":""}]},{"type":"br"},{"type":"div","class":"letter_buttonset","id":"letter_school_absence_2_div","html":[{"type":"span","html":"Return Date:"},{"type":"br"},{"type":"text","id":"letter_school_absence_2a","class":"letter_date letter_return_date","css":{"width":"200px"},"name":"letter_school_absence_2","caption":""}]},{"type":"hidden","class":"letter_hidden","value":"Please excuse _firstname from school starting on _start_date.  _firstname can return to school on _return_date.","id":"letter_school_absence_hidden"}]}',
			'group' => 'school_absence',
			'sex' => 'f'
		);
		$template_array[] = array(
			'category' => 'letter',
			'json' => '{"html": [{"type":"div","class":"letter_buttonset","id":"letter_school_return_1_div","html": [{"type":"span","html":"Return Date:"},{"type":"br"},{"type":"text","id":"letter_school_return_1a","class":"letter_date letter_return_date","css": {"width":"200px"},"name":"letter_school_return_1","caption":""}]},{"type":"hidden","class":"letter_hidden","value":"_firstname can return to school on _return_date.","id":"letter_school_return_hidden"}]}',
			'group' => 'school_return',
			'sex' => 'm'
		);
		$template_array[] = array(
			'category' => 'letter',
			'json' => '{"html": [{"type":"div","class":"letter_buttonset","id":"letter_school_return_1_div","html": [{"type":"span","html":"Return Date:"},{"type":"br"},{"type":"text","id":"letter_school_return_1a","class":"letter_date letter_return_date","css": {"width":"200px"},"name":"letter_school_return_1","caption":""}]},{"type":"hidden","class":"letter_hidden","value":"_firstname can return to school on _return_date.","id":"letter_school_return_hidden"}]}',
			'group' => 'school_return',
			'sex' => 'f'
		);
		$template_array[] = array(
			'category' => 'letter',
			'json' => '{"html":[{"type":"div","class":"letter_buttonset","id":"letter_work_absence_1_div","html":[{"type":"span","html":"Start Date:"},{"type":"br"},{"type":"text","id":"letter_work_absence_1a","class":"letter_date letter_start_date","css":{"width":"200px"},"name":"letter_work_absence_1","caption":""}]},{"type":"br"},{"type":"div","class":"letter_buttonset","id":"letter_work_absence_2_div","html":[{"type":"span","html":"Return Date:"},{"type":"br"},{"type":"text","id":"letter_work_absence_2a","class":"letter_date letter_return_date","css":{"width":"200px"},"name":"letter_work_absence_2","caption":""}]},{"type":"hidden","class":"letter_hidden","value":"Please excuse _firstname from work starting on _start_date.  _firstname can return to work on _return_date.","id":"letter_work_absence_hidden"}]}',
			'group' => 'work_absence',
			'sex' => 'm'
		);
		$template_array[] = array(
			'category' => 'letter',
			'json' => '{"html":[{"type":"div","class":"letter_buttonset","id":"letter_work_absence_1_div","html":[{"type":"span","html":"Start Date:"},{"type":"br"},{"type":"text","id":"letter_work_absence_1a","class":"letter_date letter_start_date","css":{"width":"200px"},"name":"letter_work_absence_1","caption":""}]},{"type":"br"},{"type":"div","class":"letter_buttonset","id":"letter_work_absence_2_div","html":[{"type":"span","html":"Return Date:"},{"type":"br"},{"type":"text","id":"letter_work_absence_2a","class":"letter_date letter_return_date","css":{"width":"200px"},"name":"letter_work_absence_2","caption":""}]},{"type":"hidden","class":"letter_hidden","value":"Please excuse _firstname from work starting on _start_date.  _firstname can return to work on _return_date.","id":"letter_work_absence_hidden"}]}',
			'group' => 'work_absence',
			'sex' => 'f'
		);
		$template_array[] = array(
			'category' => 'letter',
			'json' => '{"html": [{"type":"div","class":"letter_buttonset","id":"letter_work_return_1_div","html": [{"type":"span","html":"Return Date:"},{"type":"br"},{"type":"text","id":"letter_work_return_1a","class":"letter_date letter_return_date","css": {"width":"200px"},"name":"letter_work_return_1","caption":""}]},{"type":"hidden","class":"letter_hidden","value":"_firstname can return to work on _return_date.","id":"letter_work_return_hidden"}]}',
			'group' => 'work_return',
			'sex' => 'm'
		);
		$template_array[] = array(
			'category' => 'letter',
			'json' => '{"html": [{"type":"div","class":"letter_buttonset","id":"letter_work_return_1_div","html": [{"type":"span","html":"Return Date:"},{"type":"br"},{"type":"text","id":"letter_work_return_1a","class":"letter_date letter_return_date","css": {"width":"200px"},"name":"letter_work_return_1","caption":""}]},{"type":"hidden","class":"letter_hidden","value":"_firstname can return to work on _return_date.","id":"letter_work_return_hidden"}]}',
			'group' => 'work_return',
			'sex' => 'f'
		);
		$template_array[] = array(
			'category' => 'letter',
			'json' => '{"html": [{"type":"div","class":"letter_buttonset","id":"letter_work_modified_1_div","html": [{"type":"span","html":"Start Date:"},{"type":"br"},{"type":"text","id":"letter_work_modified_1a","class":"letter_date letter_start_date","css": {"width":"200px"},"name":"letter_work_modified_1","caption":""}]},{"type":"br"},{"type":"div","class":"letter_buttonset","id":"letter_work_modified_2_div","html": [{"type":"span","html":"End Date:"},{"type":"br"},{"type":"text","id":"letter_work_modified_2a","class":"letter_date letter_end_date","css": {"width":"200px"},"name":"letter_work_modified_2","caption":""}]},{"type":"hidden","class":"letter_hidden","value":"_firstname should begin the following modified work restrictions starting on _start_date and ending on _end_date.","id":"letter_work_modified_hidden"},{"type":"div","class":"letter_buttonset","id":"letter_work_modified_3_div","html": [{"type":"span","html":"Select from list:"},{"type":"br"},{"type":"select","multiple":"multiple","id":"letter_work_modified_3a","class":"letter_select","css": {"width":"200px"},"name":"letter_work_modified_3","caption":"","options": {"shoulder": {"type":"optgroup","label":"Shoulder","options": {"Limited use of the right shoulder.  ":"Limited use of the right shoulder.","No use of the right shoulder.  ":"No use of the right shoulder.","Limited use of the left shoulder.  ":"Limited use of the left shoulder.","No use of the left shoulder.  ":"No use of the left shoulder.","Limited use of both shoulders.  ":"Limited use of both shoulders.","No use of both shoulders.  ":"No use of both shoulders."}},"arm": {"type":"optgroup","label":"Arm","options": {"Limited use of the right arm.  ":"Limited use of the right arm.","No use of the right arm.  ":"No use of the right arm.","Limited use of the left arm.  ":"Limited use of the left arm.","No use of the left arm.  ":"No use of the left arm.","Limited use of both arms.  ":"Limited use of both arms.","No use of both arms.  ":"No use of both arms."}},"hand": {"type":"optgroup","label":"Hand","options": {"Limited use of the right hand.  ":"Limited use of the right hand.","No use of the right hand.  ":"No use of the right hand.","Limited use of the left hand.  ":"Limited use of the left hand.","No use of the left hand.  ":"No use of the left hand.","Limited use of both hands.  ":"Limited use of both hands.","No use of both hands.  ":"No use of both hands."}},"leg": {"type":"optgroup","label":"Leg","options": {"Limited use of the right leg.  ":"Limited use of the right leg.","No use of the right leg.  ":"No use of the right leg.","Limited use of the left leg.  ":"Limited use of the left leg.","No use of the left leg.  ":"No use of the left leg.","Limited use of both legs.  ":"Limited use of both legs.","No use of both legs.  ":"No use of both legs."}},"device": {"type":"optgroup","label":"Devices","options": {"Need to use splint provided while at work.  ":"Need to use splint provided while at work.","Need to use crutches provided while at work.  ":"Need to use crutches provided while at work.","Need to use back brace provided while at work.":"Need to use back brace provided while at work."}},"actions": {"type":"optgroup","label":"Actions","options": {"Limited bending.  ":"Limited bending.","No bending.  ":"No bending.","Limited climbing.  ":"Limited climbing.","No climbing.  ":"No climbing.","Limited heavy lifting.  ":"Limited heavy lifting.","No heavy lifting.  ":"No heavy lifting.","Limited overhead reaching.  ":"Limited overhead reaching.","No overhead reaching.  ":"No overhead reaching.","Limited pulling.  ":"Limited pulling.","No pulling.  ":"No pulling.","Limited pushing.  ":"Limited pushing.","No pushing.  ":"No pushing.","Limited squatting.  ":"Limited squatting.","No squatting.  ":"No squatting.","Limited standing.  ":"Limited standing.","No standing.  ":"No standing","Limited stooping.  ":"Limited stooping.","No stooping.  ":"No stooping.","Limited twisting.  ":"Limited twisting.","No twisting.  ":"No twisting.","Limited weight bearing.  ":"Limited weight bearing.","No weight bearing.  ":"No weight bearing.","Limited work near moving machinery.  ":"Limited work near moving machinery.","No work near moving machinery.  ":"No work near moving machinery.","Limited work requiring depth perception.  ":"Limited work requiring depth perception.","No work requiring depth perception.  ":"No work requiring depth perception."}}}}]}]}',
			'group' => 'work_modified',
			'sex' => 'm'
		);
		$template_array[] = array(
			'category' => 'letter',
			'json' => '{"html": [{"type":"div","class":"letter_buttonset","id":"letter_work_modified_1_div","html": [{"type":"span","html":"Start Date:"},{"type":"br"},{"type":"text","id":"letter_work_modified_1a","class":"letter_date letter_start_date","css": {"width":"200px"},"name":"letter_work_modified_1","caption":""}]},{"type":"br"},{"type":"div","class":"letter_buttonset","id":"letter_work_modified_2_div","html": [{"type":"span","html":"End Date:"},{"type":"br"},{"type":"text","id":"letter_work_modified_2a","class":"letter_date letter_end_date","css": {"width":"200px"},"name":"letter_work_modified_2","caption":""}]},{"type":"hidden","class":"letter_hidden","value":"_firstname should begin the following modified work restrictions starting on _start_date and ending on _end_date.","id":"letter_work_modified_hidden"},{"type":"div","class":"letter_buttonset","id":"letter_work_modified_3_div","html": [{"type":"span","html":"Select from list:"},{"type":"br"},{"type":"select","multiple":"multiple","id":"letter_work_modified_3a","class":"letter_select","css": {"width":"200px"},"name":"letter_work_modified_3","caption":"","options": {"shoulder": {"type":"optgroup","label":"Shoulder","options": {"Limited use of the right shoulder.  ":"Limited use of the right shoulder.","No use of the right shoulder.  ":"No use of the right shoulder.","Limited use of the left shoulder.  ":"Limited use of the left shoulder.","No use of the left shoulder.  ":"No use of the left shoulder.","Limited use of both shoulders.  ":"Limited use of both shoulders.","No use of both shoulders.  ":"No use of both shoulders."}},"arm": {"type":"optgroup","label":"Arm","options": {"Limited use of the right arm.  ":"Limited use of the right arm.","No use of the right arm.  ":"No use of the right arm.","Limited use of the left arm.  ":"Limited use of the left arm.","No use of the left arm.  ":"No use of the left arm.","Limited use of both arms.  ":"Limited use of both arms.","No use of both arms.  ":"No use of both arms."}},"hand": {"type":"optgroup","label":"Hand","options": {"Limited use of the right hand.  ":"Limited use of the right hand.","No use of the right hand.  ":"No use of the right hand.","Limited use of the left hand.  ":"Limited use of the left hand.","No use of the left hand.  ":"No use of the left hand.","Limited use of both hands.  ":"Limited use of both hands.","No use of both hands.  ":"No use of both hands."}},"leg": {"type":"optgroup","label":"Leg","options": {"Limited use of the right leg.  ":"Limited use of the right leg.","No use of the right leg.  ":"No use of the right leg.","Limited use of the left leg.  ":"Limited use of the left leg.","No use of the left leg.  ":"No use of the left leg.","Limited use of both legs.  ":"Limited use of both legs.","No use of both legs.  ":"No use of both legs."}},"device": {"type":"optgroup","label":"Devices","options": {"Need to use splint provided while at work.  ":"Need to use splint provided while at work.","Need to use crutches provided while at work.  ":"Need to use crutches provided while at work.","Need to use back brace provided while at work.":"Need to use back brace provided while at work."}},"actions": {"type":"optgroup","label":"Actions","options": {"Limited bending.  ":"Limited bending.","No bending.  ":"No bending.","Limited climbing.  ":"Limited climbing.","No climbing.  ":"No climbing.","Limited heavy lifting.  ":"Limited heavy lifting.","No heavy lifting.  ":"No heavy lifting.","Limited overhead reaching.  ":"Limited overhead reaching.","No overhead reaching.  ":"No overhead reaching.","Limited pulling.  ":"Limited pulling.","No pulling.  ":"No pulling.","Limited pushing.  ":"Limited pushing.","No pushing.  ":"No pushing.","Limited squatting.  ":"Limited squatting.","No squatting.  ":"No squatting.","Limited standing.  ":"Limited standing.","No standing.  ":"No standing","Limited stooping.  ":"Limited stooping.","No stooping.  ":"No stooping.","Limited twisting.  ":"Limited twisting.","No twisting.  ":"No twisting.","Limited weight bearing.  ":"Limited weight bearing.","No weight bearing.  ":"No weight bearing.","Limited work near moving machinery.  ":"Limited work near moving machinery.","No work near moving machinery.  ":"No work near moving machinery.","Limited work requiring depth perception.  ":"Limited work requiring depth perception.","No work requiring depth perception.  ":"No work requiring depth perception."}}}}]}]}',
			'group' => 'work_modified',
			'sex' => 'f'
		);
		foreach ($template_array as $template_ind) {
			$template_array = serialize(json_decode($template_ind['json']));
			$template_data = array(
				'user_id' => '0',
				'template_name' => 'Global Default',
				'default' => 'default',
				'category' => $template_ind['category'],
				'sex' => $template_ind['sex'],
				'group' => $template_ind['group'],
				'array' => $template_array
			);
			$this->db->insert('templates', $template_data);
		}
		$role_definition = array(
			'guardian_roles_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'code' => array('type' => 'VARCHAR', 'constraint' => 255),
			'description' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($role_definition);
		$this->dbforge->add_key('guardian_roles_id', TRUE);
		$this->dbforge->create_table('guardian_roles', TRUE);
		$role_csv = "/var/www/nosh/import/familyrole.csv";
		if (($role_handle = fopen($role_csv, "r")) !== FALSE) {
			while (($role1 = fgetcsv($role_handle, 0, ",")) !== FALSE) {
				if ($role1[0] != '') {
					$role_description = ucfirst($role1[1]);
					$role_data = array (
						'code' => $role1[0],
						'description' => $role_description
					);
					$this->db->insert('guardian_roles', $role_data);
				}
			}
			fclose($role_csv);
		}
		$lang_definition = array(
			'lang_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'code' => array('type' => 'VARCHAR', 'constraint' => 255),
			'description' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($lang_definition);
		$this->dbforge->add_key('lang_id', TRUE);
		$this->dbforge->create_table('lang', TRUE);
		$lang_csv = "/var/www/nosh/import/lang.csv";
		if (($lang_handle = fopen($lang_csv, "r")) !== FALSE) {
			while (($lang1 = fgetcsv($lang_handle, 0, "\t")) !== FALSE) {
				if ($lang1[0] != '') {
					$lang_data = array (
						'code' => $lang1[0],
						'description' => $lang1[6]
					);
					$this->db->insert('lang', $lang_data);
				}
			}
			fclose($lang_csv);
		}
		$this->do_install2();
		$this->do_install2b();
		$this->do_install3();
		$this->do_install4();
		$this->do_install5();
		$this->do_install6();
	}
	
	function do_install1($message)
	{
		$this->load->database();
		$this->load->dbforge();
		if ($this->db->table_exists('practiceinfo')) {
			if ($message=='error') {
				$this->dbforge->drop_table('meds');
				$this->dbforge->drop_table('supplements_list');
				$this->dbforge->drop_table('icd9');
				$this->dbforge->drop_table('cpt');
				$this->dbforge->drop_table('npi');
				$this->dbforge->drop_table('pos');
				$this->dbforge->drop_table('cvx');
				$this->dbforge->drop_table('gc');
				$vars['message'] = "Installation was incomplete.  Installation repeating.";
				$this->load->view('install_progress', $vars);
			} else {
				$vars['message'] = "";
				$this->load->view('install_progress', $vars);
			}
		} else {
			redirect('install/do_install');
		}
	}
	
	function do_install2()
	{
		ini_set('memory_limit','196M');
		set_time_limit(0);
		$this->load->database();
		$this->load->dbforge();
		$fullmed_definition = array(
			'PRODUCTNDC' => array('type' => 'VARCHAR', 'constraint' => 255),
			'PRODUCTTYPENAME' => array('type' => 'VARCHAR', 'constraint' => 255),
			'PROPRIETARYNAME' => array('type' => 'VARCHAR', 'constraint' => 255),
			'PROPRIETARYNAMESUFFIX' => array('type' => 'VARCHAR', 'constraint' => 255),
			'NONPROPRIETARYNAME' => array('type' => 'VARCHAR', 'constraint' => 255),
			'DOSAGEFORMNAME' => array('type' => 'VARCHAR', 'constraint' => 255),
			'ROUTENAME' => array('type' => 'VARCHAR', 'constraint' => 255),
			'STARTMARKETINGDATE' => array('type' => 'VARCHAR', 'constraint' => 255),
			'ENDMARKETINGDATE' => array('type' => 'VARCHAR', 'constraint' => 255),
			'MARKETINGCATEGORYNAME' => array('type' => 'VARCHAR', 'constraint' => 255),
			'APPLICATIONNUMBER' => array('type' => 'VARCHAR', 'constraint' => 255),
			'LABELERNAME' => array('type' => 'VARCHAR', 'constraint' => 255),
			'SUBSTANCENAME' => array('type' => 'VARCHAR', 'constraint' => 255),
			'ACTIVE_NUMERATOR_STRENGTH' => array('type' => 'VARCHAR', 'constraint' => 255),
			'ACTIVE_INGRED_UNIT' => array('type' => 'VARCHAR', 'constraint' => 255),
			'PHARM_CLASSES' => array('type' => 'VARCHAR', 'constraint' => 255),
			'DEASCHEDULE' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($fullmed_definition);
		$this->dbforge->add_key('PRODUCTNDC', TRUE);
		$this->dbforge->create_table('meds_full', TRUE);
		$fullmed1_definition = array(
			'PRODUCTNDC' => array('type' => 'VARCHAR', 'constraint' => 255),
			'NDCPACKAGECODE' => array('type' => 'VARCHAR', 'constraint' => 255),
			'PACKAGEDESCRIPTION' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($fullmed1_definition);
		$this->dbforge->add_key('NDCPACKAGECODE', TRUE);
		$this->dbforge->create_table('meds_full_package', TRUE);
		$meds_sql_file = "/var/www/nosh/import/meds_full.sql";
		include(APPPATH.'config/database'.EXT);
		$meds_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $meds_sql_file;
		system($meds_command);
		$meds1_sql_file = "/var/www/nosh/import/meds_full_package.sql";
		$meds1_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $meds1_sql_file;
		system($meds1_command);
	}
	
	function do_install2b()
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$this->load->database();
		$this->load->dbforge();
		$this->load->library('domparser');
		$supplements_definition = array(
			'supplements_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'supplement_name' => array('type' => 'VARCHAR', 'constraint' => 100)
		);
		$this->dbforge->add_field($supplements_definition);
		$this->dbforge->add_key('supplements_id', TRUE);
		$this->dbforge->create_table('supplements_list', TRUE);
		$supplements_file = "/var/www/nosh/import/supplements_list.sql";
		include(APPPATH.'config/database'.EXT);
		$supplements_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $supplements_file;
		system($supplements_command);
	}
	
	function do_install3()
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$this->load->database();
		$this->load->dbforge();
		// ICD Codes
		$icd9_definition = array(
			'icd9_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'icd9' => array('type' => 'VARCHAR', 'constraint' => 255),
			'icd9_description' => array('type' => 'VARCHAR', 'constraint' => 255),
			'icd9_common' => array('type' => 'TINYINT', 'constraint' => 4)
		);
		$this->dbforge->add_field($icd9_definition);
		$this->dbforge->add_key('icd9_id', TRUE);
		$this->dbforge->create_table('icd9', TRUE);
		$icd_file = "/var/www/nosh/import/icd9.sql";
		include(APPPATH.'config/database'.EXT);
		$icd_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $icd_file;
		system($icd_command);
	}
	
	function do_install4()
	{
		$this->load->database();
		$this->load->dbforge();
		// CPT Codes
		$cpt_definition = array(
			'cpt_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 255),
			'cpt_description' => array('type' => 'LONGTEXT'),
			'cpt_common' => array('type' => 'TINYINT', 'constraint' => 4)
		);
		$this->dbforge->add_field($cpt_definition);
		$this->dbforge->add_key('cpt_id', TRUE);
		$this->dbforge->create_table('cpt', TRUE);
		$cpt_file = "/var/www/nosh/import/cpt.sql";
		include(APPPATH.'config/database'.EXT);
		$cpt_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $cpt_file;
		system($cpt_command);
		$cpt_relate_definition = array(
			'cpt_relate_id' => array('type' => 'INT', 'constraint' => 40, 'auto_increment' => TRUE),
			'practice_id' => array('type' => 'INT', 'constraint' => 11),
			'cpt' => array('type' => 'VARCHAR', 'constraint' => 255),
			'cpt_description' => array('type' => 'LONGTEXT'),
			'cpt_charge' => array('type' => 'VARCHAR', 'constraint' => 255),
			'favorite' => array('type' => 'TINYINT', 'constraint' => 4),
			'unit' => array('type' => 'INT', 'constraint' => 1)
		);
		$this->dbforge->add_field($cpt_relate_definition);
		$this->dbforge->add_key('cpt_relate_id', TRUE);
		$this->dbforge->create_table('cpt_relate', TRUE);
	}
	
	function do_install4a()
	{
		ini_set('memory_limit','96M');
		$this->load->database();
		$this->load->dbforge();
		// CPT Codes
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
		
		$directory = '/var/www/nosh/import/';
		
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'csv';
		$this->load->library('upload', $config);
		$field_name = "fileToUpload";
		$this->upload->do_upload($field_name);
		
		$pages_data1 = $this->upload->data();
		
		while(!file_exists($pages_data1['full_path'])) {
			sleep(2);
		}
		
		if (($handle = fopen($pages_data1['full_path'], "r")) !== FALSE) {
			while (($cpt = fgetcsv($handle, 0, "\t")) !== FALSE) {
				if ($cpt[0] != '') {
					$data = array (
						'cpt' => $cpt[0],
						'cpt_description' => $cpt[1]
					);
					$this->db->insert('cpt', $data);
				}
			}
			fclose($pages_data1['full_path']);
		}
		
		$arr['status'] = 'CPT database installed and codes imported.<br>';
		$arr['status1'] = '&nbsp;&nbsp;&nbsp;Installing NPI Taxonomy, Point of Service List, WHO growth chart values, and CVX vaccine databases...';
		echo json_encode($arr);
	}
	
	function do_install5()
	{
		ini_set('memory_limit','96M');
		$this->load->database();
		$this->load->dbforge();
		
		// NPI Codes
		$npi_definition = array(
			'npi_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'code' => array('type' => 'VARCHAR', 'constraint' => 255),
			'type' => array('type' => 'LONGTEXT'),
			'classification' => array('type' => 'LONGTEXT'),
			'specialization' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($npi_definition);
		$this->dbforge->add_key('npi_id', TRUE);
		$this->dbforge->create_table('npi', TRUE);
		
		$npilist = file_get_contents('/var/www/nosh/import/npi_taxonomy.csv');
		$npilistarray = $this->csv->parse($npilist);
		foreach($npilistarray as $npi) {
			if ($npi[0] != '' AND $npi[0] != 'Code') {
				$data = array (
					'code' => $npi[0],
					'type' => $npi[1],
					'classification' => $npi[2],
					'specialization' => $npi[3]
				);
				$this->db->insert('npi', $data);
			}
		}
		
		// POS Codes
		$pos_definition = array(
			'pos_id' => array('type' => 'INT', 'constraint' => 11),
			'pos_description' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($pos_definition);
		$this->dbforge->add_key('pos_id', TRUE);
		$this->dbforge->create_table('pos', TRUE);
		
		$poslist = file_get_contents('/var/www/nosh/import/pos.csv');
		$poslistarray = $this->csv->parse($poslist);
		
		foreach($poslistarray as $pos) {
			if ($pos[0] != '') {
				$data1 = array (
					'pos_id' => $pos[0],
					'pos_description' => $pos[1]
				);
				$this->db->insert('pos', $data1);
			}
		}
		
		// CVX Codes
		$cvx_definition = array(
			'cvx_code' => array('type' => 'INT', 'constraint' => 11),
			'description' => array('type' => 'VARCHAR', 'constraint' => 255),
			'vaccine_name' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($cvx_definition);
		$this->dbforge->create_table('cvx', TRUE);
		$cvx_file = "/var/www/nosh/import/cvx.sql";
		include(APPPATH.'config/database'.EXT);
		$cvx_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $cvx_file;
		system($cvx_command);
		
		// Growth Chart
		$gc_definition = array(
			'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'sex'=> array('type' => 'VARCHAR', 'constraint' => 100),
			'type'=> array('type' => 'VARCHAR', 'constraint' => 100),
			'Age' => array('type' => 'VARCHAR', 'constraint' => 11),
			'Length' => array('type' => 'VARCHAR', 'constraint' => 11),
			'Height' => array('type' => 'VARCHAR', 'constraint' => 11),
			'unit' => array('type' => 'VARCHAR', 'constraint' => 11),
			'L' => array('type' => 'VARCHAR', 'constraint' => 11),
			'M' => array('type' => 'VARCHAR', 'constraint' => 11),
			'S' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P01' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P1' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P3' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P5' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P10' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P15' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P25' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P50' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P75' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P85' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P90' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P95' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P97' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P99' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P999' => array('type' => 'VARCHAR', 'constraint' => 11)
		);
		$this->dbforge->add_field($gc_definition);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('gc', TRUE);
		$gc_file = "/var/www/nosh/import/gc.sql";
		$gc_command = "mysql -u " . $db['default']['username']. " -p". $db['default']['password'] . " nosh < " . $gc_file;
		system($gc_command);
	}
	
	function do_install6()
	{
		$this->load->database();
		$this->load->dbforge();
		$this->load->library('session');
		$this->load->library('auth');
		if ($this->db->table_exists('meds_full') AND $this->db->table_exists('icd9') AND $this->db->table_exists('cpt') AND $this->db->table_exists('npi') AND $this->db->table_exists('pos') AND $this->db->table_exists('cvx') AND $this->db->table_exists('gc')) {
			$userdata = $this->db->query("SELECT * FROM `users`");
			$row = $userdata->row_array();
			$data = array(
				'username' => $row['username'],
				'user_id' => $row['id'],
				'group_id' => $row['group_id'],
				'logged_in' => TRUE,
				'practice_id' => '1'
				);
			$this->session->set_userdata($data);
			$this->auth->_generate();
			redirect('start');
		} else {
			redirect('install/do_install1/error');
		}
	}
	
// --------------------------------------------------------------------
	
	function fix_db_conn()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('db_username', 'MySQL Username', 'required');
		$this->form_validation->set_rules('db_password', 'MySQL Password');
		if ($this->form_validation->run() == FALSE) {
			$vars['message'] = '';
			$this->load->view('install_fix_db_conn', $vars);
		} else {
			$db_username = set_value('db_username');
			$db_password = set_value('db_password');
			include(APPPATH.'config/database'.EXT);
			$filename = '/var/www/nosh/system/application/config/database.php';
			$str = file_get_contents($filename);
			$fp = fopen($filename,'w');
			$search_1 = '$default_db_username = "' . $default_db_username . '"';
			$search_2 = '$default_db_password = "' . $default_db_password . '"';
			$replace_1 = '$default_db_username = "' . $db_username . '"';
			$replace_2 = '$default_db_password = "' . $db_password . '"';
			$str = str_replace($search_1,$replace_1,$str);
			$str = str_replace($search_2,$replace_2,$str);
			fwrite($fp,$str,strlen($str));
			$wantedPerms = 0755;
			redirect('front');
		}
	}
	
// --------------------------------------------------------------------
	
	function do_install_template()
	{
		$this->load->database();
		$this->load->dbforge();
		// Templates
		$template_definition = array(
			'template_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'user_id' => array('type' => 'BIGINT', 'constraint' => 20),
			'default' => array('type' => 'VARCHAR', 'constraint' => 100),
			'template_name' => array('type' => 'VARCHAR', 'constraint' => 100),
			'age' => array('type' => 'VARCHAR', 'constraint' => 100),
			'category' => array('type' => 'VARCHAR', 'constraint' => 100),
			'sex' => array('type' => 'VARCHAR', 'constraint' => 100),
			'group' => array('type' => 'VARCHAR', 'constraint' => 100),
			'array' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($template_definition);
		$this->dbforge->add_key('template_id', TRUE);
		$this->dbforge->create_table('templates', TRUE);
		$template_char = array("\t","\n","\r");
		if ($handle = opendir('/var/www/nosh/import/templates')) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$template_filepath[] = '/var/www/nosh/import/templates/' . $entry;
				}
			}
			closedir($handle);
		}
		foreach ($template_filepath as $template_file) {
			$template_str = file_get_contents($template_file);
			$template_str = str_replace($template_char, '', $template_str);
			$template_array = serialize(json_decode($template_str));
			$template_name_array = explode("/", $template_file);
			$template_name_array1 = explode("_", $template_name_array[6]);
			$template_category = $template_name_array1[0];
			$template_sex = $template_name_array1[2];
			$template_group = $template_name_array1[0] . "_" . $template_name_array1[1];
			$template_age = str_replace(".txt", "", $template_name_array1[3]);
			$template_data = array(
				'user_id' => '0',
				'template_name' => 'Global Default',
				'default' => 'default',
				'category' => $template_category,
				'sex' => $template_sex,
				'group' => $template_group,
				'age' => $template_age,
				'array' => $template_array
			);
			$this->db->insert('templates', $template_data);
		
		}
		echo count($template_filepath);
	}
	
	function create_json_file_pe()
	{
		$this->load->library('domparser');
		$html = $this->domparser->file_get_html('/var/www/nosh/system/application/views/auth/pages/provider/chart/encounters/pe_female.php');
		$gender = "f";
		$forms = $html->find('form[style="display:none"]');
		foreach ($forms as $form) {
			$filename = $form->id;
			$replace = "_" . $gender . "_adult.txt";
			$filename1 = str_replace("_form", $replace, $filename);
			$parent_id = str_replace("_form", '', $filename);
			$json = '{"html":[';
			$i = 1;
			$table = $form->find('table',1);
			$remove = array("\t","\n","\r",">");
			foreach ($table->find('tr') as $tr) {
				$radio = $tr->find('input[type="radio"]',0);
				$checkbox = $tr->find('input[type="checkbox"]',0);
				$text = $tr->find('input[type="text"]',0);
				if (isset($radio) || isset($checkbox) || isset($text)) {
					foreach ($tr->find('input') as $input) {
						$id = $input->id;
						$find = "_normal";
						$normal = strpos($id, $find);
						$text = strpos($id, "_text");
					}
					$j = "a";
					if ($normal === FALSE && $text === FALSE) {
						if ($i > 1) {
							$json .= ',{"type":"br"},';
						}
						$json .= '{"type":"div","class":"pe_buttonset","id":"' . $parent_id . '_' . $i . '_div","html":[{"type":"span","html":"';
						$a = $tr->find('td');
						$json .= $a[0]->innertext;
						$json .= '"},{"type":"br"},{"type":"';
						$b = $a[1]->find('input',0);
						$b_type = $b->type;
						if ($b_type == 'radio') {
							$b_type = 'checkbox';
						}
						$json .= $b_type . '","id":"';
						$json .= $parent_id . '_' . $i . $j . '","class":"';
						$b_caption = ltrim($a[1]->plaintext);
						$b_caption = str_replace($remove, '', $b_caption);
						if ($b_type == 'text') {
							$b_degreetest = strpos($b_caption,"(");
							if ($b_degreetest === TRUE) {
								$b_caption1 = explode("(",$b_caption);
								$b_placeholder = str_replace(" degrees)","",$b_caption[1]);
								$b_class = 'pe_normal","css":{"width":"60px"},"placeholder":"' . $b_placeholder . '"';
							} else {
								$b_class = 'pe_normal","css":{"width":"60px"}';
							}
						} else {
							$b_class = 'pe_normal","value":"' . $b->value . '"';
						}
						$json .= $b_class . ',"name":"';
						$json .= $parent_id . '_' . $i . '","caption":"';
						$json .= $b_caption . '"}';
						$j++;
						if (isset($a[2])) {
							$c_array = $a[2]->find('input');
							$c_text = $a[2]->innertext;
							$c_text1 = explode("<br>",$c_text);
							$k = 0;
							foreach ($c_array as $c) {
								$d_detail = '';
								$json .= ',{"type":"';
								$c_type = $c->type;
								if ($c_type == 'radio') {
									$c_type = 'checkbox';
								}
								$json .= $c_type . '","id":"';
								$json .= $parent_id . '_' . $i . $j . '","class":"';
								$pos = strpos($c_text1[$k], ">");
								$c_caption = substr($c_text1[$k], $pos);
								$c_caption = str_replace($remove, '', $c_caption);
								$c_caption = ltrim($c_caption);
								if ($c_type == 'text') {
									$c_degreetest = strpos($c_caption,"(");
									if ($c_degreetest === TRUE) {
										$c_caption1 = explode("(",$c_caption);
										$c_placeholder = str_replace(" degrees)","",$c_caption[1]);
										$c_class = 'pe_normal","css":{"width":"60px"},"placeholder":"' . $c_placeholder . '"';
									} else {
										$c_class = 'pe_normal","css":{"width":"60px"}';
									}
								} else {
									$c_value = $c->value;
									$find1 = ": ";
									$abnormal = strpos($c_value, $find1);
									if ($abnormal == FALSE) {
										$c_class = 'pe_other","value":"' . $c->value . '"';
									
									} else {
										$c_class = 'pe_other pe_detail","value":"' . $c->value . '"';
										$d_detail .= ',{"type":"br"},{"type":"text","id":"' . $parent_id . '_' . $i . $j . '_detail"';
										$d_detail .= ',"css":{"width":"150px"},"class":"pe_other pe_detail_text","name":"' . $parent_id . '_' . $i . '","placeholder":"Details"}';
									}
								}
								$json .= $c_class . ',"name":"';
								$json .= $parent_id . '_' . $i . '","caption":"';
								$json .= $c_caption . '"}' . $d_detail;
								$j++;
								$k++;
							}
						}
						$json .= ']}';
						$i++;
					}
				}
			}
			$json .= ']}';
			$filepath = '/var/www/nosh/import/templates/' . $filename1;
			file_put_contents($filepath,$json);
		}
		echo "Done!";
	}
	
	function create_json_file_hpi()
	{
		$this->load->library('domparser');
		$html = $this->domparser->file_get_html('/var/www/nosh/system/application/views/auth/pages/provider/chart/encounters/view.php');
		$gender = "m";
		$forms = $html->find('div[style="display:none"]');
		foreach ($forms as $form) {
			$filename = $form->id;
			$fieldset = strpos($filename, '_fieldset');
			if ($fieldset != FALSE) {
				$replace = "_" . $gender . "_adult.txt";
				$filename1 = str_replace("_fieldset", $replace, $filename);
				$parent_id = str_replace("_fieldset", '', $filename);
				$json = '{"html":[';
				$i = 1;
				$remove = array("\t","\n","\r",">");
				foreach ($form->find('tr') as $tr) {
					$radio = $tr->find('input[type="radio"]',0);
					$checkbox = $tr->find('input[type="checkbox"]',0);
					$text = $tr->find('input[type="text"]',0);
					if (isset($radio) || isset($checkbox) || isset($text)) {
						foreach ($tr->find('input') as $input) {
							$id = $input->id;
							$find = "_normal";
							$normal = strpos($id, $find);
							$text = strpos($id, "_text");
						}
						$j = "a";
						if ($normal === FALSE) {
							if ($i > 1) {
								$json .= ',{"type":"br"},';
							}
							$json .= '{"type":"div","class":"hpi_buttonset","id":"' . $parent_id . '_' . $i . '_div","html":[{"type":"span","html":"';
							$a = $tr->find('td');
							$json .= $a[0]->innertext;
							$json .= '"},{"type":"br"},{"type":"';
							$b = $a[1]->find('input',0);
							$b_type = $b->type;
							if ($b_type == 'radio') {
								$b_type = 'checkbox';
							}
							$json .= $b_type . '","id":"';
							$json .= $parent_id . '_' . $i . $j . '","class":"';
							$b_caption = ltrim($a[1]->plaintext);
							$b_caption = str_replace($remove, '', $b_caption);
							if ($b_type == 'text') {
								$b_class = 'hpi_normal","css":{"width":"200px"}';
							} else {
								$b_class = 'hpi_normal","value":"' . $b->value . '"';
							}
							$json .= $b_class . ',"name":"';
							$json .= $parent_id . '_' . $i . '","caption":"';
							$json .= $b_caption . '"}';
							$j++;
							if (isset($a[2])) {
								$c_array = $a[2]->find('input');
								$c_text = $a[2]->innertext;
								$c_text1 = explode("<br>",$c_text);
								$k = 0;
								foreach ($c_array as $c) {
									$d_detail = '';
									$json .= ',{"type":"';
									$c_type = $c->type;
									if ($c_type == 'radio') {
										$c_type = 'checkbox';
									}
									$json .= $c_type . '","id":"';
									$json .= $parent_id . '_' . $i . $j . '","class":"';
									$pos = strpos($c_text1[$k], ">");
									$c_caption = substr($c_text1[$k], $pos);
									$c_caption = str_replace($remove, '', $c_caption);
									$c_caption = ltrim($c_caption);
									if ($c_type == 'text') {
										$c_class = 'hpi_normal","css":{"width":"200px"}';
									} else {
										$c_value = $c->value;
										$find1 = ": ";
										$abnormal = strpos($c_value, $find1);
										if ($abnormal == FALSE) {
											$c_class = 'hpi_other","value":"' . $c->value . '"';
									
										} else {
											$c_class = 'hpi_other hpi_detail","value":"' . $c->value . '"';
											$d_detail .= ',{"type":"br"},{"type":"text","id":"' . $parent_id . '_' . $i . $j . '_detail"';
											$d_detail .= ',"css":{"width":"200px"},"class":"hpi_other hpi_detail_text","name":"' . $parent_id . '_' . $i . '","placeholder":"Details"}';
										}
									}
									$json .= $c_class . ',"name":"';
									$json .= $parent_id . '_' . $i . '","caption":"';
									$json .= $c_caption . '"}' . $d_detail;
									$j++;
									$k++;
								}
							}
							$json .= ']}';
							$i++;
						}
					}
				}
				$json .= ']}';
				$filepath = '/var/www/nosh/import/templates/' . $filename1;
				file_put_contents($filepath,$json);
			}
		}
		echo 'Done!';
	}
	
	function create_json_file_ros()
	{
		$this->load->library('domparser');
		$html = $this->domparser->file_get_html('/var/www/nosh/system/application/views/auth/pages/provider/chart/encounters/ros_female_old.php');
		$gender = "f";
		$forms = $html->find('div[style="font-size: 0.9em"]');
		foreach ($forms as $form) {
			$filename = $form->id;
			$fieldset = strpos($filename, '_dialog');
			if ($fieldset != FALSE) {
				$replace = "_" . $gender . "_adult.txt";
				$filename1 = str_replace("_dialog", $replace, $filename);
				$parent_id = str_replace("_dialog", '', $filename);
				$json = '{"html":[';
				$i = 1;
				$inside_form = $form->find('form',0);
				$table_test = $inside_form->find('table',1);
				if (isset($table_test)) {
					$table = $inside_form->find('table',1);
				} else {
					$table = $inside_form->find('table',0);
				}
				$remove = array("\t","\n","\r",">");
				foreach ($table->find('tr') as $tr) {
					$radio = $tr->find('input[type="radio"]',0);
					$checkbox = $tr->find('input[type="checkbox"]',0);
					$text = $tr->find('input[type="text"]',0);
					if (isset($radio) || isset($checkbox) || isset($text)) {
						foreach ($tr->find('input') as $input) {
							$id = $input->id;
							$find = "_normal";
							$normal = strpos($id, $find);
							$text = strpos($id, "_text");
						}
						$j = "a";
						if ($normal === FALSE) {
							if ($i > 1) {
								$json .= ',{"type":"br"},';
							}
							$json .= '{"type":"div","class":"ros_buttonset","id":"' . $parent_id . '_' . $i . '_div","html":[{"type":"span","html":"';
							$a = $tr->find('td');
							$json .= $a[0]->innertext;
							$json .= '"},{"type":"br"}';
							if (isset($a[1])) {
								$c_array = $a[1]->find('input');
								foreach ($c_array as $c) {
									$d_detail = '';
									$json .= ',{"type":"';
									$c_type = $c->type;
									if ($c_type == 'radio') {
										$c_type = 'checkbox';
									}
									$json .= $c_type . '","id":"';
									$json .= $parent_id . '_' . $i . $j . '","class":"';
									if ($c_type == 'text') {
										$c_class = 'ros_normal","css":{"width":"200px"}';
										$c_caption = '';
									} else {
										$c_value = $c->value;
										$find1 = ": ";
										$abnormal = strpos($c_value, $find1);
										if ($abnormal == FALSE) {
											if ($j == "a") {
												$c_class = 'ros_normal","value":"' . $c->value . '"';
											} else {
												$c_class = 'ros_other","value":"' . $c->value . '"';
											}
										} else {
											$c_class = 'ros_other ros_detail","value":"' . $c->value . '"';
											$d_detail .= ',{"type":"br"},{"type":"text","id":"' . $parent_id . '_' . $i . $j . '_detail"';
											$d_detail .= ',"css":{"width":"200px"},"class":"ros_other ros_detail_text","name":"' . $parent_id . '_' . $i . '","placeholder":"Details"}';
										}
										$c_id = $c->id;
										$c_caption_find = $a[1]->find('label[for="' . $c_id . '"]',0);
										$c_caption = $c_caption_find->innertext;
										$c_caption = str_replace($remove, '', $c_caption);
										$c_caption = ltrim($c_caption);
									}
									$json .= $c_class . ',"name":"';
									$json .= $parent_id . '_' . $i . '","caption":"';
									$json .= $c_caption . '"}' . $d_detail;
									$j++;
								}
							}
							$json .= ']}';
							$i++;
						}
					}
				}
				$json .= ']}';
				$filepath = '/var/www/nosh/import/templates/' . $filename1;
				file_put_contents($filepath,$json);
			}
		}
		echo 'Done!';
	}
	
	function create_json_file_ros_wcc()
	{
		$this->load->library('domparser');
		$html = $this->domparser->file_get_html('/var/www/nosh/system/application/views/auth/pages/provider/chart/encounters/ros_female_wcc.php');
		$gender = "f";
		$forms = $html->find('div[style="display:none"]');
		foreach ($forms as $form) {
			$class = $form->id;
			$filename1 = $class . "_" . $gender . "_child.txt";
			$parent_id = "ros_wccage";
			$json = '{"html":[';
			$i = 1;
			$remove = array("\t","\n","\r",">");
			foreach ($form->find('tr') as $tr) {
				$radio = $tr->find('input[type="radio"]',0);
				$checkbox = $tr->find('input[type="checkbox"]',0);
				$text = $tr->find('input[type="text"]',0);
				if (isset($radio) || isset($checkbox) || isset($text)) {
					foreach ($tr->find('input') as $input) {
						$id = $input->id;
						$find = "_normal";
						$normal = strpos($id, $find);
					}
					$j = "a";
					if ($normal === FALSE) {
						if ($i > 1) {
							$json .= ',{"type":"br"},';
						}
						$json .= '{"type":"div","class":"ros_buttonset","id":"' . $parent_id . '_' . $i . '_div","html":[{"type":"span","html":"';
						$a = $tr->find('td');
						$json .= $a[0]->innertext;
						$json .= '"},{"type":"br"}';
						if (isset($a[1])) {
							$c_array = $a[1]->find('input');
							$c_text = $a[1]->innertext;
							$c_text1 = explode("<br>",$c_text);
							$k = 0;
							foreach ($c_array as $c) {
								$d_detail = '';
								$json .= ',{"type":"';
								$c_type = $c->type;
								if ($c_type == 'radio') {
									$c_type = 'checkbox';
								}
								$json .= $c_type . '","id":"';
								$json .= $parent_id . '_' . $i . $j . '","class":"';
								if ($c_type == 'text') {
									$c_class = 'ros_normal","css":{"width":"200px"}';
									$c_caption = '';
								} else {
									if ($j == "a") {
										$c_class = 'ros_normal","value":"' . $c->value . '"';
									} else {
										$c_class = 'ros_other","value":"' . $c->value . '"';
									}
									$c_id = $c->id;
									$c_caption_find = $a[1]->find('label[for="' . $c_id . '"]',0);
									$c_caption = $c_caption_find->innertext;
									$c_caption = str_replace($remove, '', $c_caption);
									$c_caption = ltrim($c_caption);
								}
								$json .= $c_class . ',"name":"';
								$json .= $parent_id . '_' . $i . '","caption":"';
								$json .= $c_caption . '"}' . $d_detail;
								$j++;
								$k++;
							}
						}
						$json .= ']}';
						$i++;
					}
				}
			}
			$json .= ']}';
			$filepath = '/var/www/nosh/import/templates/' . $filename1;
			file_put_contents($filepath,$json);
		}
		echo 'Done!';
	}
	
// --------------------------------------------------------------------
	
	function do_install3_old()
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$this->load->database();
		$this->load->dbforge();
		if ($this->db->table_exists('icd9')) {
			$arr['status'] = 'ICD database installed and codes saved.<br>';
			$arr['status1'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Installing CPT database...';
			$arr['status2'] = '70';
			echo json_encode($arr);
			exit(0);
		}
		
		// ICD Codes
		$icd9_definition = array(
			'icd9_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'icd9' => array('type' => 'VARCHAR', 'constraint' => 255),
			'icd9_description' => array('type' => 'VARCHAR', 'constraint' => 255),
			'icd9_common' => array('type' => 'TINYINT', 'constraint' => 4)
		);
		$this->dbforge->add_field($icd9_definition);
		$this->dbforge->add_key('icd9_id', TRUE);
		$this->dbforge->create_table('icd9', TRUE);
		$this->load->library('domparser');
		
		// Extract ICD codes and input to database
		$year = date('Y');
		$link1 = array();
		$html = $this->domparser->file_get_html("http://www.icd9data.com/" . $year . "/Volume1/default.htm");
		if (isset($html)) {
			$div = $html->find('[class=codeList]',0);
			if (isset($div)) {
				foreach ($div->find('li') as $li) {
					$a = $li->find('a',0);
					$link1[] = $a->href;
				}
			}
		}
		file_put_contents('/var/www/nosh/temp.bin', serialize($link1));
		$arr['status'] = 'ICD database created and starting the import of codes from icd9data.com.<br>';
		$arr['status1'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Installing ICD database and saving codes from www.icd9data.com...';
		$arr['status2'] = '45';
		echo json_encode($arr);
		exit(0);
	}
	
	function do_install3a()
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$this->load->library('domparser');
		$link1 = unserialize(file_get_contents('/var/www/nosh/temp.bin'));
		$link2 = array();
		unlink('/var/www/nosh/temp.bin');
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
		$arr['status'] = 'Level 1 pages copied from icd9data.com.<br>';
		$arr['status1'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Installing ICD database and saving codes from www.icd9data.com...';
		$arr['status2'] = '50';
		file_put_contents('/var/www/nosh/temp.bin', serialize($link2));
		echo json_encode($arr);
		exit(0);
	}
	
	function do_install3b()
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$this->load->library('domparser');
		$link2 = unserialize(file_get_contents('/var/www/nosh/temp.bin'));
		$link3 = array();
		unlink('/var/www/nosh/temp.bin');
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
		}
		$arr['status'] = 'Level 2 pages copied from icd9data.com.<br>';
		$arr['status1'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Installing ICD database and saving codes from www.icd9data.com...';
		$arr['status2'] = '55';
		file_put_contents('/var/www/nosh/temp.bin', serialize($link3));
		echo json_encode($arr);
		exit(0);
	}
	
	function do_install3c()
	{
		$this->load->database();
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$this->load->library('domparser');
		$link3 = unserialize(file_get_contents('/var/www/nosh/temp.bin'));
		unlink('/var/www/nosh/temp.bin');
		$i = 0;
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
		$arr['status'] = $i . ' ICD9 codes saved.<br>';
		$arr['status1'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Installing CPT database...';
		$arr['status2'] = '70';
		echo json_encode($arr);
	}
	
	function do_install5_old()
	{
		ini_set('memory_limit','96M');
		$this->load->database();
		$this->load->dbforge();
		
		// NPI Codes
		$npi_definition = array(
			'npi_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'code' => array('type' => 'VARCHAR', 'constraint' => 255),
			'type' => array('type' => 'LONGTEXT'),
			'classification' => array('type' => 'LONGTEXT'),
			'specialization' => array('type' => 'LONGTEXT')
		);
		$this->dbforge->add_field($npi_definition);
		$this->dbforge->add_key('npi_id', TRUE);
		$this->dbforge->create_table('npi', TRUE);
		
		$npilist = file_get_contents('/var/www/nosh/import/npi_taxonomy.csv');
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
		
		// POS Codes
		$pos_definition = array(
			'pos_id' => array('type' => 'INT', 'constraint' => 11),
			'pos_description' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($pos_definition);
		$this->dbforge->add_key('pos_id', TRUE);
		$this->dbforge->create_table('pos', TRUE);
		
		$poslist = file_get_contents('/var/www/nosh/import/pos.csv');
		$poslistarray = $this->csv->parse($poslist);
		
		foreach($poslistarray as $pos) {
			if ($pos[0] != '') {
				$data1 = array (
					'pos_id' => $pos[0],
					'pos_description' => $pos[1]
				);
				$this->db->insert('pos', $data1);
			}
		}
		
		// CVX Codes
		$cvx_definition = array(
			'cvx_code' => array('type' => 'INT', 'constraint' => 11),
			'description' => array('type' => 'VARCHAR', 'constraint' => 255),
			'vaccine_name' => array('type' => 'VARCHAR', 'constraint' => 255)
		);
		$this->dbforge->add_field($cvx_definition);
		$this->dbforge->create_table('cvx', TRUE);
		
		$xml = simplexml_load_file('http://www2a.cdc.gov/vaccines/iis/iisstandards/XML.asp?rpt=cvx');
		foreach ($xml->CVXInfo as $cvx) {
			$data2 = array(
				'cvx_code' => (string) $cvx->Value[2],
				'description' => ucfirst((string) $cvx->Value[0]),
				'vaccine_name' => ucfirst((string) $cvx->Value[1])
			);
			$this->db->insert('cvx', $data2);
		}
		
		// Growth Chart
		$gc_definition = array(
			'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
			'sex'=> array('type' => 'VARCHAR', 'constraint' => 100),
			'type'=> array('type' => 'VARCHAR', 'constraint' => 100),
			'Age' => array('type' => 'VARCHAR', 'constraint' => 11),
			'Length' => array('type' => 'VARCHAR', 'constraint' => 11),
			'Height' => array('type' => 'VARCHAR', 'constraint' => 11),
			'unit' => array('type' => 'VARCHAR', 'constraint' => 11),
			'L' => array('type' => 'VARCHAR', 'constraint' => 11),
			'M' => array('type' => 'VARCHAR', 'constraint' => 11),
			'S' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P01' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P1' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P3' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P5' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P10' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P15' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P25' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P50' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P75' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P85' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P90' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P95' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P97' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P99' => array('type' => 'VARCHAR', 'constraint' => 11),
			'P999' => array('type' => 'VARCHAR', 'constraint' => 11)
		);
		$this->dbforge->add_field($gc_definition);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('gc', TRUE);
		
		$gc_files = array();
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/lhfa_boys_p_exp.txt",
			'sex' => 'm',
			'type' => 'height-age',
			'unit' => 'cm'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/lhfa_girls_p_exp.txt",
			'sex' => 'f',
			'type' => 'height-age',
			'unit' => 'cm'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/wfa_boys_p_exp.txt",
			'sex' => 'm',
			'type' => 'weight-age',
			'unit' => 'kg'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/wfa_girls_p_exp.txt",
			'sex' => 'f',
			'type' => 'weight-age',
			'unit' => 'kg'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/bfa_boys_p_exp.txt",
			'sex' => 'm',
			'type' => 'bmi-age',
			'unit' => 'kg/m2'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/bfa_girls_p_exp.txt",
			'sex' => 'f',
			'type' => 'bmi-age',
			'unit' => 'kg/m2'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/hcfa_boys_p_exp.txt",
			'sex' => 'm',
			'type' => 'head-age',
			'unit' => 'cm'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/hcfa_girls_p_exp.txt",
			'sex' => 'f',
			'type' => 'head-age',
			'unit' => 'cm'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/wfl_boys_p_exp.txt",
			'sex' => 'm',
			'type' => 'weight-length',
			'unit' => 'kg'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/wfl_girls_p_exp.txt",
			'sex' => 'f',
			'type' => 'weight-length',
			'unit' => 'kg'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/tab_wfh_boys_p_2_5.txt",
			'sex' => 'm',
			'type' => 'weight-height',
			'unit' => 'kg'
		);
		$gc_files[] = array(
			'filename' => "/var/www/nosh/import/tab_wfh_girls_p_2_5.txt",
			'sex' => 'f',
			'type' => 'weight-height',
			'unit' => 'kg'
		);
		foreach ($gc_files as $gc_file) {
			$handle = fopen($gc_file['filename'], "r");
			$header = fgetcsv($handle, 0, "\t");
			$data3 = array();
			$data3['sex'] = $gc_file['sex'];
			$data3['type'] = $gc_file['type'];
			$data3['unit'] = $gc_file['unit'];
			while ($row = fgetcsv($handle, 0, "\t")) {
				$arr = array();
				foreach ($header as $i => $col) {
					if ($col == 'Day') {
						$col = 'Age';
					}
					$data3[$col] = $row[$i];
				}
				$this->db->insert('gc', $data3);
			}
			fclose($handle);
		}
		$arr['status'] = 'NPI Taxonomy, Point of Service List, WHO growth chart values, and CVX vaccine databases installed.<br>';
		echo json_encode($arr);
	}
}
/* End of file: install.php */
/* Location: application/controllers/install.php */
